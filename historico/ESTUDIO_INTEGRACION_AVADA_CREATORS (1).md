# Estudio técnico — Integración Avada + Tiritaito for Creators
*Documento de análisis y hoja de ruta · Tiritaito Web project*
*Preparado para sesión futura de revisión técnica*
*Ad maiorem Dei gloriam*

---

## Cómo usar este documento

Este documento es un **briefing técnico** preparado antes de la sesión de análisis. Describe el sistema tal y como existe hoy, plantea los problemas relevantes y propone líneas de investigación. Léelo antes de empezar a tocar código. No contiene decisiones tomadas — contiene preguntas que hay que responder y contexto para responderlas bien.

**Contexto de los dos proyectos Claude:**
- **Tiritaito Web** (este proyecto): maneja el PHP estructural que lee y muestra contenido. Páginas Avada, snippets, shortcodes, widgets.
- **Tiritaito for Creators**: maneja la app que *escribe* ese contenido. Un único HTML con vanilla JS que publica vía REST API propia.

El archivo de coordinación entre ambos proyectos es `03_COORDINACION_WEB_APP.md`.

---

## 1. Arquitectura actual — mapa completo

```
CAPA DE ESCRITURA (Creators app)
  └── Endpoint REST propio: /tiritaito/v1/datos (POST)
  └── Endpoint REST propio: /tiritaito/v1/subir (POST)
       │
       ▼
CAPA DE DATOS (WordPress)
  └── wp_options: tt_virgen, tt_brisa, tt_homilia_audio,
      tt_homilia_texto, tt_lenguas_url, tt_tip_1, tt_tip_2,
      tt_docx_lectura_url, tt_youtube_json_url,
      tt_seminarios_json_url, [tt_viacrucis_json_url pendiente]
  └── Biblioteca de Medios: audios, vídeos, imágenes, DOCX, JSON
       │
       ▼
CAPA DE LECTURA (Avada + snippets PHP)
  └── Code Snippets PHP → get_option('tt_*') → shortcode → Avada Code Block
  └── Visitantes ven el contenido actualizado sin tocar WordPress
```

**Snippet PHP activo:** `TT Creators + Biblioteca — Endpoint central v2`
- Tipo: Run everywhere (se ejecuta en CADA request de WordPress)
- Registra rutas REST en `rest_api_init`
- Define constantes de tokens y CORS en `init`
- Registra callbacks de todos los endpoints

---

## 2. Análisis de rendimiento: impacto real en la web pública

### 2.1 wp_options y autoload: la realidad técnica

WordPress carga todas las opciones con `autoload = yes` en **una sola consulta SQL** al inicio de cada request, mediante `wp_load_alloptions()`. El resultado se guarda en memoria (object cache o array interno de `$wp_object_cache`).

Consecuencia: **las llamadas a `get_option('tt_virgen')` desde shortcodes no generan consultas SQL adicionales**. El dato ya está en memoria. Coste real: acceso a un array PHP en memoria — microsegundos.

**Pero hay un matiz importante sobre autoload y contenido grande:**

| Clave | Tipo | Tamaño típico | Autoload recomendado |
|-------|------|---------------|----------------------|
| `tt_virgen` | Texto largo (homilías) | 200–2.000 caracteres | ⚠️ Ver nota |
| `tt_brisa` | Texto corto | 50–300 caracteres | ✅ yes |
| `tt_homilia_audio` | URL | ~100 caracteres | ✅ yes |
| `tt_homilia_texto` | Texto muy largo | 500–5.000 caracteres | ⚠️ Ver nota |
| `tt_lenguas_url`, `tt_tip_1`, `tt_tip_2` | URLs | ~100 caracteres | ✅ yes |
| `tt_docx_lectura_url` | URL | ~100 caracteres | ✅ yes |
| `tt_youtube_json_url`, `tt_seminarios_json_url` | URLs | ~100 caracteres | ✅ yes |

**Nota sobre autoload y textos largos:** si la opción se carga en TODAS las páginas (autoload=yes) pero solo se usa en una o dos, estás inflando la memoria del proceso en cada request. Para textos de homilías que se muestran en una sola página, `autoload = 'no'` + `get_option()` explícito en el shortcode es más eficiente. WordPress hará una consulta SQL extra solo en esa página específica — y LiteSpeed la cacheará.

**Acción de análisis:** revisar en qué páginas se usa cada clave `tt_*` y ajustar el autoload en el snippet PHP usando la firma completa de `update_option($key, $value, $autoload)`.

### 2.2 El snippet "Run everywhere": coste real

El snippet principal se ejecuta en cada request. Lo que hace en cada request:

```
init (prioridad 0):   Comprueba REQUEST_METHOD — si no es OPTIONS, retorna en ~1μs
rest_api_init:        Registra rutas REST — WordPress ya hace esto internamente;
                      añadir rutas propias es O(n) sobre el array de rutas, negligible
                      para 8 rutas personalizadas
```

**Diagnóstico:** el coste en requests normales (páginas Avada) es **prácticamente cero**. El hook `rest_api_init` solo se ejecuta cuando WordPress detecta una petición a la REST API — no en cada carga de página normal.

**Corrección importante:** `add_action('rest_api_init')` registra el callback, pero ese callback (`register_rest_route`) solo se llama cuando WordPress procesa una URL bajo `/wp-json/`. En páginas normales, `rest_api_init` nunca se dispara. Coste en frontend público: ~0.

**Lo que SÍ corre en cada request:**
- El hook `init` con prioridad 0 para CORS — comprueba `$_SERVER['REQUEST_METHOD']` y retorna. Coste: ~1μs.
- La definición de constantes y funciones PHP — el motor PHP las parsea una vez y usa opcode cache. Coste en producción: ~0.

**Conclusión:** el snippet no ralentiza la web pública. El riesgo de rendimiento no está aquí.

### 2.3 Los endpoints REST y LiteSpeed Cache

Configuración actual: LiteSpeed Cache excluye `/blog/wp-json/tiritaito/` de la caché.

**Por qué es correcto:** los endpoints de escritura (POST, DELETE) nunca deben cachearse. El endpoint de lectura `GET /tiritaito/v1/datos` tampoco, porque devuelve el contenido que acaba de publicar el equipo y debe ser siempre fresco.

**Pero hay una trampa:** si LiteSpeed cachea la *página de inicio* o cualquier *página Avada* que muestre contenido devocional, el visitante podría ver datos desactualizados aunque la app haya publicado contenido nuevo hace 5 minutos.

**Acción de análisis:**
1. Verificar que LiteSpeed purga la caché de las páginas relevantes cuando se actualiza una `wp_option`. Esto se puede hacer con un hook `updated_option` en el snippet PHP.
2. Alternativa: configurar un TTL corto (ej. 5 minutos) para las páginas con contenido dinámico.
3. Alternativa más robusta: llamar a la API de LiteSpeed Cache desde el snippet PHP al guardar opciones → purge automático.

```php
// Añadir al callback tt_cb_post_datos(), tras guardar las opciones:
do_action( 'litespeed_purge_all' ); // purga todo
// O más quirúrgico:
do_action( 'litespeed_purge_url', 'https://www.tiritaito.com/blog/' ); // purga solo la home
```

### 2.4 El endpoint GET /tiritaito/v1/datos desde el frontend

**Pregunta clave:** ¿se usa este endpoint desde el frontend público (JS en las páginas Avada), o solo desde la app Creators?

- Si se usa desde el **frontend**: cada visita lanza una petición AJAX → PHP → DB. Se multiplica por visitas. Hay que cachearlo o eliminarlo.
- Si se usa solo desde la **app**: cero impacto en el frontend público.

**Arquitectura recomendada (la más rápida posible):** los widgets en las páginas Avada deben leer `get_option()` **directamente en PHP server-side**, dentro del shortcode. No mediante AJAX al endpoint REST. El visitante recibe el HTML ya renderizado con el contenido, sin peticiones adicionales desde el navegador.

Si actualmente hay widgets que hacen fetch al endpoint REST desde JavaScript, eso es un problema de arquitectura que hay que resolver.

---

## 3. Avada: diagnóstico de uso y optimización

### 3.1 Qué hace Avada bien y dónde está el límite

| Función | Avada nativo | Custom PHP | Decisión recomendada |
|---------|-------------|------------|----------------------|
| Layout de páginas | ✅ Fusion Builder | ❌ Costoso de mantener | Siempre Avada |
| Colores, tipografía, espaciado global | ✅ Global Options | ❌ CSS manual redundante | Siempre Avada Global Options |
| Cargar contenido dinámico (wp_options) | ⚠️ Solo con Dynamic Content (requiere licencia) | ✅ Shortcode PHP | Shortcode PHP en Code Block |
| Animaciones, hover effects | ✅ Fusion Builder nativo | ⚠️ JS custom solo si Avada no llega | Avada primero |
| Formularios | ✅ Avada Forms | ⚠️ Solo si Avada Forms no cubre el caso | Avada Forms |
| Sliders, carousels | ✅ Avada Slider | ❌ | Siempre Avada |
| Reproductores de audio/vídeo custom | ⚠️ Avada tiene embed básico | ✅ Nuestros players son más ricos | Code Block + JS custom |
| Podcast player | ❌ No tiene | ✅ Ya implementado | Mantener custom |

### 3.2 Avada Code Block: el puente entre Avada y nuestro PHP

El **Code Block** de Avada es el elemento donde conviven Avada y nuestro código. Se puede poner HTML, PHP (si el hosting lo permite en Avada), shortcodes y JavaScript.

**Patrón correcto:**
```
Página Avada
  └── Sección Avada (layout, colores, espaciado)
      └── Code Block de Avada
          └── Shortcode registrado en nuestro snippet PHP
              └── Shortcode ejecuta get_option('tt_*') → devuelve HTML
```

**Lo que NO hay que hacer:**
- PHP directamente en el Code Block si el hosting lo restringe (Raiola puede limitarlo)
- JavaScript que hace fetch al endpoint REST para leer datos que podrían venir server-side
- Shortcodes con lógica de DB compleja que se ejecutan muchas veces por página

### 3.3 Variables CSS de Avada vs nuestras variables CSS

Avada genera sus propias variables CSS en el `<head>` desde Global Options. Si nuestro código custom usa variables propias (como `--tt-red`) y Avada usa las suyas, hay una doble capa que puede crear conflictos o redundancias.

**Acciones de análisis:**
1. Auditar si los colores en nuestros snippets CSS duplican lo que ya está en Avada Global Options
2. Si Avada ya define `--fusion-primary-color` o similar con el rojo `#BF4646`, podemos mapear nuestras variables a las de Avada en un solo lugar
3. Identificar qué CSS custom se puede mover a Avada Global Options → Custom CSS (que Avada minifica junto con el resto)

### 3.4 JavaScript custom: defer y async con Avada

Avada tiene opciones para deferir/asyncronizar JavaScript. Si nuestros snippets inyectan JS (via `wp_enqueue_script` o directamente en Code Blocks), hay que verificar:

1. **¿Nuestro JS depende del DOM de Avada?** Si sí, no puede ir en `<head>` sin defer
2. **¿Avada está deferiendo sus propios scripts y el nuestro depende de ellos?** Posible race condition
3. **¿Los players de audio/vídeo se inicializan antes de que el DOM esté listo?** DOMContentLoaded vs load

**Herramienta para el análisis:** PageSpeed Insights en las páginas con contenido dinámico. Identificar qué scripts bloquean el render y cuáles son nuestros.

### 3.5 Avada y la caché del frontend

Avada tiene su propio sistema de optimización que puede entrar en conflicto con LiteSpeed Cache:

- **Avada CSS Output:** puede generar CSS dinámico que LiteSpeed intenta cachear con parámetros de versión
- **Avada JS Compiler:** combina y minifica JS — si usamos scripts externos en Code Blocks, pueden quedar fuera de la optimización
- **Critical CSS de Avada:** puede no incluir el CSS de nuestros shortcodes si se genera antes de que los shortcodes produzcan HTML

**Acción de análisis:** revisar si hay CSS de nuestros shortcodes que no aparezca en el Critical CSS de Avada y por tanto cause CLS (Cumulative Layout Shift).

---

## 4. Tiritaito for Creators: impacto en la web pública

### 4.1 ¿La app ralentiza el servidor?

**Respuesta corta: No, si está bien configurada.**

Las peticiones de la app son:
- Asíncronas: el equipo hace una acción → la app llama al endpoint → WordPress procesa
- Poco frecuentes: máximo varias veces al día
- Limitadas por rate limiting: 60 ops/hora/IP
- Desacopladas del frontend: ocurren en background, no bloquean a ningún visitante

El único momento de impacto potencial es una **subida de archivo grande** (vídeo de Lenguas de Hoy, hasta 200 MB). Ese proceso consume:
- CPU del servidor durante la subida y procesamiento
- Disco durante la escritura al Media Library

En un hosting compartido como Raiola, si se sube un vídeo de 100 MB mientras hay visitas en la web, puede haber un pico de carga. Es improbable que sea notable, pero es el único riesgo real.

**Acción de análisis:** verificar los límites de Raiola para subidas y si hay configuración de prioridad de procesos que aísle las subidas grandes del tráfico web.

### 4.2 El endpoint de lectura: ¿quién lo llama?

`GET /tiritaito/v1/datos` acepta tanto `TT_READ_TOKEN` como `TT_WRITE_TOKEN`.

- Si lo llaman los **widgets PHP** (shortcodes en Avada): no, los shortcodes usan `get_option()` directamente — el endpoint no entra en juego
- Si lo llama la **app Creators** al abrir: sí, una vez al iniciar la app para cargar el estado
- Si lo llaman **widgets JS** en el frontend público: ⚠️ esto sería un problema

**Acción de análisis:** buscar en todos los snippets activos si hay algún `fetch('/wp-json/tiritaito/v1/datos')` desde JavaScript que se ejecute en el frontend público.

### 4.3 Purga de caché después de publicar

Este es el punto más importante para la experiencia del equipo.

**Flujo actual (potencialmente defectuoso):**
1. El equipo publica el mensaje de la Virgen desde la app
2. La app hace POST a `/tiritaito/v1/datos` → WordPress guarda en `wp_options`
3. El visitante carga la página de inicio → LiteSpeed sirve la versión cacheada (sin el mensaje nuevo)
4. El mensaje nuevo no aparece hasta que expira la caché

**Flujo correcto:**
1. Mismo paso 1 y 2
2. WordPress guarda → el snippet lanza un hook de purga a LiteSpeed
3. LiteSpeed invalida la caché de las páginas relevantes
4. El siguiente visitante ya ve el mensaje nuevo

**Implementación:** añadir al callback `tt_cb_post_datos()` la purga de LiteSpeed tras guardar. Requiere conocer qué páginas muestran cada clave.

```php
// Mapa clave → páginas que hay que purgar
$purge_map = [
    'tt_virgen'        => ['/', '/blog/'],          // home y blog
    'tt_brisa'         => ['/'],                     // solo home
    'tt_homilia_audio' => ['/santa-misa/'],
    'tt_homilia_texto' => ['/santa-misa/'],
    'tt_lenguas_url'   => ['/lenguas-de-hoy/'],
    'tt_tip_1'         => ['/tip-del-dia/'],
    'tt_tip_2'         => ['/tip-del-dia/'],
    'tt_docx_lectura_url' => ['/santa-misa/'],
];
foreach ($ok as $clave) {
    if (isset($purge_map[$clave])) {
        foreach ($purge_map[$clave] as $url) {
            do_action('litespeed_purge_url', home_url($url));
        }
    }
}
```

---

## 5. Inventario de snippets activos: qué hay que auditar

Antes de tocar nada, el primer paso de la sesión de análisis debe ser un inventario completo de los snippets activos en Code Snippets.

**Para cada snippet, anotar:**

| Campo | Qué responder |
|-------|---------------|
| Nombre | — |
| Tipo | PHP / HTML / CSS / JS |
| Run everywhere / Solo frontend / Solo admin | — |
| Qué hace en cada request de página normal | — |
| Si tiene consultas DB (`WP_Query`, `get_posts`, `$wpdb->get`) | Cuántas y en qué condiciones |
| Si se puede mover a Avada nativo | Sí / No / Parcialmente |
| Si está activo y en uso | Sí / No / Dudoso |
| Dependencias con otros snippets | — |

**Snippets que ya conocemos:**
- `TT Creators + Biblioteca — Endpoint central v2` — el snippet principal de la app
- Snippets de reproductores de música y seminarios (cargan JSON de URL en wp_options)
- Snippet de podcast (`tt_podcast` shortcode)
- Snippet de lecturas litúrgicas (DOCX desde wp_options)
- Snippets de widgets devocionales (brisa, virgen, etc.)

---

## 6. Avada vs código custom: dónde reducir código

### Lo que Avada puede hacer sin código y que quizás estamos haciendo a mano

| Elemento | ¿Avada lo puede hacer? | Ganancia si se mueve a Avada |
|----------|------------------------|------------------------------|
| Rejillas de contenido estático | ✅ Fusion Builder Grid | Menos CSS custom, más visual |
| Secciones con fondo de color/imagen | ✅ Avada Container | Elimina CSS de layout |
| Tipografía de títulos | ✅ Global Options | Un solo punto de control |
| Botones con hover | ✅ Avada Button element | Sin JS propio |
| Acordeón de FAQ | ✅ Avada Toggle element | Sin JS propio |
| Tabs (pestañas) | ✅ Avada Tabs element | Sin JS propio |
| Contador animado | ✅ Avada Counter element | Sin JS propio |
| Formulario de contacto | ✅ Avada Forms | Sin gestión propia |
| Separadores visuales | ✅ Avada Separator | Sin HTML custom |
| Iconos | ✅ Avada Icon element | Sin SVG inline ni icon fonts |

### Lo que Avada NO puede hacer y necesita nuestro código

| Elemento | Por qué necesita código custom |
|----------|-------------------------------|
| Reproductor de música con playlists JSON | Avada Embed es básico; nuestro player tiene UX propia |
| Reproductor de podcasts con RSS | Requiere parsear RSS en PHP |
| Contenido dinámico de wp_options | Avada Dynamic Content solo en planes Pro; shortcode PHP es más flexible |
| Player de vídeo con URL de wp_options | La URL cambia cada día; Avada Video Element usa URL fija |
| Lecturas DOCX con URL dinámica | Requiere PHP |

---

## 7. Riesgos técnicos conocidos

### 7.1 Tokens expuestos en el HTML de la app

`TT_WRITE_TOKEN` está hardcodeado en el HTML de la app Creators. El HTML no es público (solo lo tiene el equipo), pero es un riesgo si alguien con acceso al archivo lo filtra.

**No es un riesgo del frontend público.** Es un riesgo operativo del equipo.

Mitigación actual: redistribuir el HTML si el token se compromete. Tiempo: 5 minutos.

### 7.2 Avada y el post_content serializado

Las páginas construidas con Avada Live almacenan el layout en `post_content` como shortcodes de Fusion Builder. Cualquier intento de editar `post_content` desde la REST API o desde el snippet PHP rompería el diseño.

**El sistema actual tiene barrera activa:** el callback `tt_cb_editar_entrada` devuelve HTTP 422 si detecta `fusion_builder` en `post_content`. Esta barrera es correcta y no debe eliminarse.

**Sin embargo:** hay que verificar que esta barrera también cubre entradas creadas con Avada Live que no tienen exactamente `fusion_builder` pero sí `[fusion_` en el contenido (los shortcodes). El código actual ya cubre ambos casos — verificar con una prueba real.

### 7.3 LiteSpeed Cache y los endpoints REST

Verificar que LiteSpeed no cachea respuestas de la REST API con cabeceras incorrectas. Una respuesta POST cacheada sería un desastre (el segundo POST devolvería la respuesta del primero). La configuración actual excluye `/wp-json/tiritaito/` — comprobar que la exclusión funciona correctamente y cubre también las rutas de `biblioteca/v1/`.

### 7.4 CORS y Raiola

El snippet gestiona CORS manualmente (permite `*`). Verificar que el hosting Raiola no añade sus propias cabeceras CORS que entren en conflicto y generen headers duplicados (`Access-Control-Allow-Origin: *, *` — algunos navegadores lo rechazan).

---

## 8. Hoja de ruta para la sesión de análisis

### Fase A — Inventario (sin tocar código)
1. Listar todos los snippets activos en Code Snippets con su estado
2. Identificar qué páginas Avada usan qué shortcodes
3. Identificar qué shortcodes leen de wp_options y cuáles hacen consultas DB propias
4. Revisar Google PageSpeed para las páginas principales — anotar métricas de partida
5. Comprobar si hay JS frontend que llame al endpoint REST

### Fase B — Quick wins (cambios seguros, alto impacto)
1. Añadir purga de LiteSpeed Cache en `tt_cb_post_datos()` tras guardar
2. Ajustar `autoload` a `no` para las opciones de texto largo (`tt_virgen`, `tt_homilia_texto`)
3. Verificar y corregir si la exclusión de LiteSpeed cubre también `biblioteca/v1/`
4. Desactivar snippets que no estén en uso activo

### Fase C — Análisis de migración a Avada (tiempo necesario)
1. Para cada elemento custom, evaluar si Avada puede reemplazarlo
2. Para los que sí, migrar de forma incremental (no todo a la vez)
3. Para los players (música, podcast), documentar por qué no se pueden migrar y qué se podría mejorar con Avada como contenedor

### Fase D — Optimización de snippets
1. Revisar si los hooks de CORS están correctamente configurados
2. Eliminar código redundante o comentado
3. Documentar cada snippet con su función exacta y qué páginas lo usan

---

## 9. Preguntas abiertas que hay que responder en la sesión

1. **¿Qué versión/plan de Avada tenemos?** El plan Business o el Enterprise tienen Avada Dynamic Content, que podría reemplazar algunos shortcodes PHP. Sin Dynamic Content, necesitamos PHP sí o sí.

2. **¿Los widgets devocionales (brisa, virgen, etc.) están implementados como shortcodes PHP o como Code Blocks con fetch JavaScript?** La respuesta determina si hay impacto en el frontend.

3. **¿LiteSpeed Cache tiene configurado el purge automático por hooks de WordPress?** Si sí, actualizar `wp_option` podría disparar un purge automático sin que lo gestionemos nosotros.

4. **¿Raiola ofrece Redis u otro object cache persistente?** Con object cache activo, el rendimiento de `get_option()` mejoraría aún más (la caché sobrevive entre requests, no solo dentro de un mismo request).

5. **¿Hay algún snippet con `WP_Query` o `$wpdb->get_*` que se ejecute en el frontend en páginas Avada?** Eso sería el mayor riesgo de rendimiento real y el primero en resolver.

6. **¿Las páginas de contenido devocional están excluidas de la caché o tienen TTL corto?** Si están cacheadas con TTL largo (24h), el equipo publica y los visitantes no ven los cambios hasta el día siguiente.

---

## 10. Resumen ejecutivo

| Área | Estado actual | Riesgo | Acción prioritaria |
|------|--------------|--------|-------------------|
| Snippets PHP + Avada | Funcionando | Bajo | Inventario completo |
| wp_options autoload | Sin optimizar | Bajo-Medio | Ajustar para texto largo |
| LiteSpeed + endpoints REST | Configurado | Bajo | Verificar cobertura biblioteca/v1 |
| LiteSpeed + páginas Avada | Desconocido | ALTO | Verificar purga tras publicar |
| Tokens en HTML de la app | Presente | Bajo (operativo) | Proceso de rotación documentado |
| JS frontend llamando a REST | Desconocido | ALTO si existe | Auditar todos los snippets |
| Avada Dynamic Content | Desconocido | — | Verificar plan de licencia |
| Rendimiento general de la app | Sin medir | — | PageSpeed en páginas dinámicas |

---

*Este documento fue generado por el asistente técnico del proyecto Tiritaito for Creators.*
*Para la mayor gloria de Dios · tiritaito.com*

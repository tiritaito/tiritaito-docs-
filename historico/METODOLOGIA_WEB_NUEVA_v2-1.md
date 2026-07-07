# TIRITAITO — Metodología de Desarrollo para la Web Nueva (v2)
**Cómo construir cada pieza: Avada nativo, Avada Library o Code Snippets — y por qué**
*Extiende y corrige el diagnóstico original de Hno C (v1) con verificación contra documentación oficial de Avada*
*Documento de referencia técnica para Hno A · julio 2026*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

> **Regla de oro de este documento:**
> El diagnóstico original de Hno C (v1, adjunto como referencia) se mantiene íntegro — está
> bien construido y distingue con honestidad entre hallazgo confirmado e hipótesis pendiente.
> Esta v2 no lo sustituye: lo verifica contra la documentación oficial de Avada y lo conecta
> con el resto de documentos del proyecto. Tres cosas nuevas:
> 1. Verificación de lo que v1 dejó marcado como "pendiente de confirmar".
> 2. Una corrección importante sobre cómo funciona realmente la Avada Library (sección 3).
> 3. La aplicación práctica del método a las secciones reales de `ALCANCE_WEB_NUEVA_v1.1.md`.

---

## Qué cambia respecto a v1 — resumen

- ✅ **Confirmado:** Toggles, Flyout Menu, Image Carousel, Avada Slider, Lightbox y Modal son elementos nativos reales de Avada Builder. Las hipótesis de v1 apuntaban en la dirección correcta.
- ⚠️ **Corregido:** la Avada Library "Global" **no admite campos variables por instancia** — sincroniza el 100% del contenido en todas partes. La recomendación de v1 para el accordion "¿Qué es X?" hay que ajustarla. Ver sección 3 — es el hallazgo más importante de esta versión.
- 🔲 **Sigue pendiente** (solo se puede confirmar dentro de Local, no por documentación): el código completo de la home, y si los elementos nativos replican exactamente el comportamiento actual del carrusel con modal de vídeo.
- ➕ **Nuevo en esta versión:** sección 8, que aplica el árbol de decisión a cada sección real de la web nueva — Ejército de Intercesores, Hombres de Dios, Seminarios, etc.

---

## 0. Cómo se relaciona este documento con los demás

| Documento | Responde a | Úsalo cuando... |
|---|---|---|
| `00_CORE.md` | ¿Cuál es el stack, los endpoints, las convenciones de código? | Necesites un dato técnico concreto: URL, variable CSS, patrón JS |
| `ALCANCE_WEB_NUEVA_v1.1.md` | ¿Qué secciones tiene la web y con qué prioridad? | Necesites saber qué construir y en qué orden |
| `INFORME_ESTRATEGICO...` | ¿Qué puede hacer Avada de fondo? ¿Cuál es la hoja de ruta? | Necesites el mapa completo de capacidades de Avada o las fases del proyecto |
| **Este documento** | **¿Dónde construyo ESTA pieza concreta, y por qué ahí y no en otro sitio?** | **Estés a punto de crear un módulo, snippet o sección y dudes dónde va** |

Este documento no repite las tablas grandes de los otros tres — las referencia. Su función es
la decisión de "dónde vive cada cosa", no el catálogo completo de opciones de Avada.

---

## 1. Diagnóstico heredado — resumen

*(Detalle completo en el documento original de Hno C. Metodología del diagnóstico: 2 auditorías
Lighthouse/PageSpeed + código fuente completo de `ejercito-de-intercesores` + revisión
estructural de `home`, `san-juan-pablo-ii`, `rincon-de-nico`.)*

**Lo que funciona bien — no reinventar:**

| Elemento | Estado |
|---|---|
| Rendimiento real de usuario (datos de campo) | LCP 2,4s · CLS 0,09 · TTFB 0,6s — bueno |
| Sistema de variables CSS `--tt-*` | Bien diseñado; el problema es que no siempre se usa |
| `fetch_feed()` server-side para el podcast | Sólido, sin proxies externos |
| Shortcode `[tt_podcast]` | Confirmado funcionando correctamente |
| Caché LiteSpeed + exclusión de `/wp-json/tiritaito/` | Correcta |

**Los tres patrones de deuda técnica:**

- **A — Componentes reinventados en vez de reutilizados.** Hasta tres sistemas de
  reproductor distintos (`.pp-*`, `.mp-*`, `.hmds-*`) haciendo lo mismo. El accordion
  "¿Qué es X?" + vídeo copiado letra por letra en al menos dos páginas.
- **B — CSS/JS global cargándose donde no se usa.** `.tiritaito-santo` (~700 líneas) y
  `.tiritaito-libros` (~500 líneas) se cargan en páginas que no usan esas clases —
  probablemente marcados "Run everywhere" por comodidad, no por necesidad real.
- **C — Funcionalidad nativa de Avada sustituida por código custom.** El menú flyout nativo
  (`fusion-flyout-menu`) está forzado a `display:none` y sustituido por un hamburguesa hecho
  a mano. Swiper.js se carga desde `unpkg.com` en cada página en vez de usar el carrusel
  nativo de Avada.

**Causa raíz común:** todo el contenido se ha pegado directamente en el `post_content` de
cada página, reinventando CSS/JS en cada ocasión, en vez de construir snippets globales
reutilizables o usar los elementos nativos de Fusion Builder. No es un problema de Avada —
es un problema de disciplina de componentización.

---

## 2. Principio rector — actualizado

> **Antes de escribir una línea de CSS o JS nuevo, comprobar en este orden:**
> 1. ¿Lo resuelve un elemento **nativo de Fusion Builder**? → sección 5, tabla verificada.
> 2. ¿Ya existe un **snippet global de Tiritaito** que hace esto o algo muy parecido? →
>    inventario en sección 10.
> 3. ¿Es contenido reutilizable con **variación por página**? → antes de tirar a Code
>    Snippets, distinguir si es candidato a Avada Library — y si es candidato, **Guardado o
>    Global no son intercambiables** (sección 3, corrección importante).
> 4. Solo si nada de lo anterior encaja, se construye un componente nuevo — y se construye
>    parametrizable, pensando en la siguiente página que lo va a necesitar, no en la página
>    actual únicamente.

Este principio, aplicado con disciplina, habría evitado los tres patrones de deuda del
diagnóstico. La diferencia entre v1 y esta versión está en el paso 3: hay que saber qué
significa realmente "Avada Library" antes de recomendarla como solución.

---

## 3. ⚠️ CORRECCIÓN — cómo funciona realmente la Avada Library

Esto es lo más importante de esta versión. v1 proponía el accordion "¿Qué es X?" + vídeo
como "candidato perfecto a elemento global de Library con campos variables (título, texto,
ID de YouTube)". **La documentación oficial de Avada confirma que eso no es posible tal
como está planteado.**

### Lo que dice la documentación oficial

Un elemento **Global** de la Avada Library sincroniza el contenido al 100% en todas sus
instancias: editar una copia edita todas las demás, en todo el sitio, automáticamente. No
existe un modo de "misma estructura, contenido distinto por instancia" dentro de un elemento
Global — de hecho, los elementos Global explícitamente no admiten contenido dinámico ni
campos variables (confirmado también por documentación de terceros especializados en
generación de contenido para Avada). Un Global sirve para "esto debe decir exactamente lo
mismo en todas partes" — un footer, un aviso legal, un CTA de donación fijo.

Lo que sí varía por instancia es un elemento **Guardado (no-global)**: se inserta como
plantilla de partida, y cada copia insertada es independiente — puedes cambiar el título, el
texto o el ID de vídeo de una sin afectar a las demás. La contrapartida: si más adelante
cambias el **diseño** (no el contenido), no hay sincronización — hay que entrar página por
página. Esto ya estaba correctamente descrito en `INFORME_ESTRATEGICO...` sección 2.1.E; la
inconsistencia estaba solo en el documento de diagnóstico v1.

### La implicación práctica para el accordion "¿Qué es X?"

Ninguna de las dos opciones de Avada Library por sí sola es perfecta — hay que elegir según
quién mantiene el contenido:

| Opción | Cuándo usarla | Coste |
|---|---|---|
| **Guardado (no-global)** en Avada Library | Si Hna C o los editores van a crear/editar estas fichas visualmente, sin tocar código | Si cambia el diseño del accordion en el futuro, hay que actualizar cada instancia a mano |
| **Shortcode parametrizable** vía Code Snippets — ej. `[tt_accordion titulo="¿Qué es el Ejército de Intercesores?" video="ID_YOUTUBE"]texto[/tt_accordion]` | Si Hno A lo mantiene por código | El diseño se actualiza en un solo sitio (el snippet) y se propaga a todas las instancias automáticamente — mismo patrón que `[tt_podcast]`, que ya está probado y funcionando |

**Recomendación:** dado que el patrón `[tt_podcast feed="..."]` ya es un componente probado,
estable y con sincronización de diseño centralizada, un `[tt_accordion]` construido igual es
la opción más coherente con el resto del sistema. La opción de Avada Library "Guardado" queda
como alternativa si en algún momento se quiere que Hna C o los editores puedan crearlos sin
depender de Hno A — pero no como solución técnica "gratis": sigue siendo trabajo manual
página a página si el diseño cambia.

*Fuente: documentación oficial de ThemeFusion/Avada sobre elementos globales de la Avada
Builder Library — ver sección "Fuentes verificadas" al final de este documento.*

---

## 4. Árbol de decisión (v2 — corregido)

```
ANTES DE CONSTRUIR NADA NUEVO
│
├── 1. ¿Lo resuelve un elemento NATIVO de Fusion Builder?
│      (Toggles · Flyout Menu · Image Carousel · Avada Slider · Lightbox · Modal — sección 5)
│      → SÍ: úsalo. No hay snippet que mantener, no hay CSS que pueda romperse.
│      → NO: sigue.
│
├── 2. ¿Ya existe un snippet global de Tiritaito que hace esto o algo muy parecido?
│      → SÍ: reutilízalo o extiéndelo con un parámetro nuevo.
│      → NO: sigue.
│
└── 3. Hay que construir algo nuevo. ¿Dónde vive?
       │
       ├── ¿Se usa en más de una página?
       │     │
       │     ├── NO → Code Block de la entrada en Avada Live
       │     │         (nunca en Code Snippets global)
       │     │
       │     └── SÍ → ¿El contenido es EXACTAMENTE igual en todas las páginas
       │                donde aparece — mismo texto, misma imagen, mismo vídeo?
       │                │
       │                ├── SÍ, siempre idéntico
       │                │     → Avada Library: elemento GLOBAL
       │                │       (footer, CTA fija, aviso legal)
       │                │
       │                └── NO, cambia por página, pero la ESTRUCTURA se repite
       │                      │
       │                      ├── Lo mantiene Hna C / editores, sin código
       │                      │     → Avada Library: elemento GUARDADO (no-global)
       │                      │
       │                      └── Lo mantiene Hno A, con lógica de servidor
       │                            → Code Snippets: shortcode parametrizable
       │                              (patrón [tt_podcast], ya probado)
       │
       └── Y en cualquier caso: constrúyelo pensando en el siguiente santo, el
           siguiente seminario, el siguiente "Rincón de X" — con parámetros,
           no con contenido fijo copiado y pegado.
```

---

## 5. Elementos nativos de Fusion Builder — tabla verificada

| Necesidad detectada | Solución custom actual | Elemento nativo de Avada | Estado |
|---|---|---|---|
| Menú hamburguesa / navegación móvil | HTML/CSS/JS a mano por página | **Flyout Menu** — Avada → Opciones → Menú → Menú Móvil → Estilo = Flyout | ✅ Confirmado, nativo, sin plugins. **Ojo:** el estilo Flyout solo admite ítems de menú de nivel superior — si el menú de Tiritaito tiene submenús desplegables, revisarlo antes de adoptarlo sin más |
| Carrusel de imágenes | JS a mano + Swiper de `unpkg.com` | **Image Carousel Element** — 5 layouts (Standard, Marquee, Coverflow, Cards, Slider), autoplay, swipe táctil, lightbox integrado | ✅ Confirmado, nativo. Pensado para imágenes; no confirma reproducción de vídeo embebido directamente |
| Carrusel con vídeo | (mismo JS a mano) | **Avada Slider Element** — admite imagen Y vídeo (YouTube/Vimeo) por diapositiva, con leyendas y botones | ✅ Confirmado, nativo — mejor candidato que Image Carousel si el carrusel debe mezclar vídeo |
| Accordion desplegable | `toggle-ios` custom por página | **Toggles Element** — modo "Toggle" (uno abierto a la vez) o "Accordion" (varios a la vez) | ✅ Confirmado, nativo |
| Modal / vídeo al clic | Modal custom por módulo | **Lightbox Element** (imagen o vídeo en overlay) o **Modal Element** (contenido libre) | ✅ Ambos confirmados, nativos. Lightbox es más simple para "un clic → vídeo o imagen"; Modal es mejor para contenido más complejo (formularios, texto largo) |
| Listado/archivo de entradas con diseño de tarjeta | — | **Post Cards** (categoría propia dentro de Avada Library) | 🔲 Existe, pero no verificado en profundidad — candidato a evaluar para "Seminarios pasados" y portada de "Hombres de Dios" |
| Reproductor de audio simple | 3 sistemas distintos (`.pp-*`, `.mp-*`, `.hmds-*`) | No es un elemento de Avada — es decisión de consolidación de snippets propios en `[tt_podcast]` | Ya funciona, ver sección 10 |

**Nota técnica adicional:** el punto de quiebre responsive del Header de Avada (Avada →
Opciones → Responsive → Header Responsive Breakpoint) tiene un valor por defecto de 800px,
independiente de los breakpoints del sistema `--tt-*` (1024/768/480px). Si se usa el Header
Builder nativo, hay que decidir si se alinea ese valor a 768px o se acepta el desfase — no es
un error, es una configuración a decidir conscientemente.

**Importante, como ya advertía v1:** estas equivalencias son puntos de partida confirmados
por documentación, no garantías de que reproduzcan el comportamiento exacto actual (autoplay,
gestos táctiles, apertura de modal). Cada una debe probarse en Local antes de dar por migrado
el contenido real — ver sección 11.

---

## 6. Convenciones de código — sin cambios

Confirmadas en `00_CORE.md`, se mantienen sin modificación:

- Snippets tipo **HTML** con `<style>` + `<script>` juntos (Code Snippets free no separa CSS/JS).
- Prefijo `tt-` + BEM → `tt-modulo__elemento--modificador`.
- Variables CSS siempre `var(--tt-*)`, nunca hex sueltos.
- Breakpoints en `1024px` / `768px` / `480px`.
- Patrón JS obligatorio — condicional de existencia fuera de `ttReady`, lógica dentro, IIFE:

  ```javascript
  if (document.getElementById('mi-modulo-root')) {
    function initMiModulo() {
      (function() {
        'use strict';
        // lógica del módulo
      })();
    }
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initMiModulo);
    } else {
      initMiModulo();
    }
  }
  ```
- Los módulos nunca redefinen variables del Global ni tocan elementos genéricos.
- `border-radius: 25px` en cards/botones/contenedores — firma visual Tiritaito.
- Tipografía: títulos en "Yeah Papa", cuerpo en "Helvetica Neue" — ninguna fuente de Google
  Fonts debería aparecer (su presencia en el sitio actual, vía `@import` en el CSS del
  podcast, es deuda a no replicar).

---

## 7. Checklists

**Antes de crear un módulo nuevo:**

- [ ] ¿Ya existe un elemento nativo de Fusion Builder que resuelva esto? (sección 5)
- [ ] ¿Ya existe un snippet global de Tiritaito con funcionalidad equivalente? (sección 10)
- [ ] Si se construye desde cero: ¿está pensado para reutilizarse — parámetros, no contenido fijo?
- [ ] Si es candidato a Avada Library: ¿Guardado o Global? (sección 3 — no son intercambiables)
- [ ] ¿El JS sigue el patrón condicional + IIFE?
- [ ] ¿Usa `var(--tt-*)` en vez de hex sueltos?
- [ ] ¿Se ha probado en consola del navegador que no hay errores de elementos inexistentes?
- [ ] ¿El snippet necesita estar en TODAS las páginas, o solo en una plantilla concreta?

**Antes de marcar un snippet "Run everywhere":**

- [ ] ¿Se usa en más del 50% de las plantillas del sitio?
- [ ] Si la respuesta es "es más fácil que decidir dónde", **no marcarlo global** — es
      exactamente el patrón que generó el CSS de Santos y de Biblioteca cargándose en
      páginas donde no se usa.
- [ ] ¿Se ha comprobado el peso que añade a páginas que no lo necesitan?

---

## 8. Aplicación a las secciones reales de la Web Nueva

Cruce entre este método y `ALCANCE_WEB_NUEVA_v1.1.md`. Esto es lo que responde la pregunta
"vale, ¿y esto en concreto dónde lo construyo?" para cada sección priorizada.

| Sección (Alcance) | Dónde vive según el árbol de decisión | Nota |
|---|---|---|
| Página de inicio — estructura | Fusion Builder puro | Sin snippets nuevos salvo el widget de citas |
| Citas / Santo del día | Snippet PHP ya existente | Sin cambios en dónde vive; solo pendiente si además tiene URL propia (pregunta #2 de Alcance) |
| Seminarios — próximos | Página Avada estática (Fusion Builder) | Según recomendación ya aprobada en Alcance |
| Seminarios — archivo de pasados | Entradas WP + candidato a evaluar: **Post Cards** de Avada Library | Pendiente probar en Local si cubre el listado sin shortcode propio |
| Seminarios — reproductor YT | Snippet ya existente | Sin cambios, solo insertar el shortcode |
| Ejército de Intercesores — accordion "¿Qué es X?" | Ver corrección sección 3: **Guardado (no-global)** o `[tt_accordion]` — **nunca Global** | Hallazgo más importante de esta versión — no construir como sugería el diagnóstico original |
| Ejército de Intercesores — el resto de la sección | Depende de responder qué es exactamente (pregunta #1 de Alcance) | Bloqueado por decisión de Hna C, sin relación con este documento |
| Conecta cada día | Snippet ya existente, montar en Avada | Sin cambios |
| Tiritaito Music | Snippet ya existente (`.mp-*`) | Evaluar si el reproductor con cola puede reducirse — ver inventario sección 10 |
| Charlas de la Biblia | Snippet `[tt_podcast]` | Sin cambios — es el patrón de referencia para nuevos shortcodes |
| Rincón de Nico | Snippet `[tt_podcast]` + mismo accordion que Ejército de Intercesores | El accordion debe ser el mismo componente reutilizado una vez, no una segunda copia |
| Hombres de Dios — ficha de santo | **Guardado (no-global)** en Avada Library como plantilla de partida, para v1 | Coherente con la decisión ya tomada en Alcance de "empezar simple, sin CPT". Si en v2 se añade CPT + ACF, esto pasa a ser una Layout Content Section con Dynamic Data — un solo diseño para todos los santos, editable centralizadamente |
| Hombres de Dios — portada/listado | Candidato a evaluar: **Post Cards** | Pendiente probar en Local |
| Oraciones (v1, solo texto) | Fusion Builder puro | Sin snippets — confirma que "sin audio en v1" es coherente con "menos código que mantener" |
| Menú / header | **Flyout Menu** nativo (sección 5) | Sustituye el hamburguesa a mano — comprobar antes si hay submenús |
| "Grupo de alabanza" (mapa + horario, home) | Sin auditar — pendiente | Se mantiene como pregunta abierta del diagnóstico original |
| Galería "Próximos eventos" (home) | Candidato: **Image Carousel** o **Avada Slider** (sección 5) | Sin auditar el código actual — pendiente igual que en el diagnóstico original |

---

## 9. Flujo de trabajo sugerido en Local by Flywheel

1. **Antes de construir un módulo nuevo**, comprobar en este orden: elemento nativo (sección
   5) → snippet existente (sección 10) → Avada Library con la distinción correcta (sección 3).
2. Prototipar el módulo en Local, probando explícitamente en una página de prueba con
   DevTools abierto (Console y Network) antes de darlo por terminado.
3. Si el módulo es candidato a reutilizarse en más de una plantilla y lo va a mantener
   alguien sin tocar código, evaluar Avada Library **Guardado** antes que Code Snippets — pero
   sabiendo que no sincroniza diseño (sección 3).
4. Documentar cada snippet nuevo con un comentario de cabecera: nombre, ubicación
   (Head/Footer/Library), y si se ejecuta por canal/entrada o es fijo.
5. Antes de dar una plantilla por cerrada, auditar qué CSS/JS global se está cargando en ella
   y verificar que cada uno se usa realmente — evitar el patrón de Santos/Biblioteca
   cargándose sin uso.

---

## 10. Inventario de componentes a consolidar

| Componente actual | Prefijo | Dónde se ha visto | Acción propuesta (v2) |
|---|---|---|---|
| Reproductor de podcast (RSS) | `.pp-*` | Global, `[tt_podcast]` | Mantener — funciona bien, es el patrón de referencia |
| "Tiritaito Music" (con cola) | `.mp-*` | Ejército de Intercesores | Evaluar si `[tt_podcast]` cubre el caso de uso; si necesita cola/playlist, mantenerlo pero migrarlo a snippet global reutilizable, no atado a una sola página |
| Mini reproductor de un track | `.hmds-*` | Ejército de Intercesores | Eliminar — sustituir por `[tt_podcast]` de un solo episodio |
| Accordion "¿Qué es X?" + vídeo | `toggle-ios` | Ejército de Intercesores, Rincón de Nico | Ver corrección sección 3 — Guardado (no-global) o `[tt_accordion]`, nunca Global |
| Menú hamburguesa custom | (sin prefijo `tt-`) | Al menos Ejército de Intercesores | Sustituir por Flyout Menu nativo (sección 5) |
| Sistema de Santos | `.tiritaito-santo` | Global, Hombres de Dios | Mantener el CSS, pero cargarlo solo donde se usa — nunca "Run everywhere" |
| Sistema de Biblioteca | `.tiritaito-libros` | Global, sin páginas reales aún | Igual — no cargar hasta que exista contenido real (v2) |
| Carrusel Swiper (`unpkg.com`) | — | Varias páginas | Sustituir por Image Carousel / Avada Slider nativo (sección 5) — probar antes de eliminar la dependencia externa |
| "Grupo de alabanza" | Sin confirmar | Home | No auditado a nivel de código — pendiente |
| Galería "Próximos eventos" | Sin confirmar | Home | No auditado a nivel de código — pendiente |

---

## 11. Qué queda confirmado vs qué sigue pendiente de probar en Local

**✅ Confirmado en documentación oficial de Avada (esta versión):**
- Toggles Element existe, con dos modos: Toggle (uno abierto) / Accordion (varios abiertos).
- Flyout Menu es nativo y se activa desde Opciones → Menú → Menú Móvil — con la limitación
  de que solo admite ítems de nivel superior.
- Image Carousel Element y Avada Slider Element existen; el segundo admite vídeo por diapositiva.
- Lightbox Element y Modal Element existen, ambos abren contenido en overlay al clic.
- La Avada Library "Global" sincroniza el 100% del contenido — no admite campos variables
  por instancia. Esto corrige la hipótesis del diagnóstico original.

**🔲 Solo se puede confirmar dentro de Local (no verificable por documentación):**
- Si Image Carousel / Avada Slider replican exactamente el comportamiento actual de
  "Próximos eventos" (autoplay, swipe táctil, apertura de vídeo en modal al clic).
- Si el "Footer oficial" visto en la barra de admin es realmente un Global Layout heredado,
  o si hay footers duplicados por página.
- Código fuente completo de la home — "Grupo de alabanza" y "Próximos eventos" a nivel de
  CSS/JS, no solo estructura.
- Si el teaser de "Ejército de Intercesores" en portada carga bien su imagen o está roto.
- Si Post Cards cubre el listado de "Seminarios pasados" y la portada de "Hombres de Dios"
  sin necesitar shortcode propio.

---

## 12. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Añadir este documento (junto a `00_CORE.md`) a la base de conocimiento permanente de la
   cuenta de Claude de Hno A — Infraestructura y Snippets — según la organización ya
   propuesta en `INVESTIGACION_HERRAMIENTAS...` Parte 1.
2. Cuando Hno A entre en Local, validar en este orden: Flyout Menu → Toggles → Image
   Carousel/Avada Slider → Lightbox — son los cuatro reemplazos de mayor impacto en peso de
   página, y no dependen de ninguna decisión pendiente de Hna C.
3. Las 6 preguntas que bloquean el desarrollo siguen siendo las de `ALCANCE_WEB_NUEVA_v1.1.md`
   — este documento no añade bloqueantes nuevos, solo aclara el "cómo" una vez que el "qué"
   esté decidido.

**Preguntas abiertas nuevas de esta versión (para la reunión semanal):**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | El accordion "¿Qué es X?": ¿lo mantiene Hno A por código, o Hna C/editores visualmente? | Determina si se construye como `[tt_accordion]` (shortcode) o como elemento Guardado de Avada Library (sección 3) |
| 2 | Para "Hombres de Dios": ¿cuántos santos con contenido sustancial hay aproximadamente? | Ya estaba abierta en `INVESTIGACION_HERRAMIENTAS...` 2.4 — además de decidir migración, determina si vale la pena evaluar Post Cards para el listado |
| 3 | ¿El menú de la web nueva va a tener submenús desplegables? | El Flyout Menu nativo solo admite nivel superior — si hay submenús, hay que revisar el diseño del menú antes de adoptarlo sin más |

---

## Fuentes verificadas en esta versión

- ThemeFusion / Avada — Avada Builder Library, elementos globales y guardados:
  avada.com/documentation/avada-builder-library-global-elements/ ·
  avada.com/documentation/how-to-use-the-avada-builder-library/
- ThemeFusion / Avada — Toggles Element: avada.com/documentation/toggles-element/
- ThemeFusion / Avada — Flyout Menu y Menú Móvil: avada.com/documentation/flyout-menu/ ·
  avada.com/documentation/mobile-menu-settings/
- ThemeFusion / Avada — Image Carousel Element: avada.com/documentation/image-carousel-element/
- ThemeFusion / Avada — Avada Slider Element: avada.com/element/avada-slider/
- ThemeFusion / Avada — Lightbox Element: avada.com/documentation/lightbox-element/

*Este documento se actualizará conforme se audite el código fuente pendiente (home completa,
Grupo de alabanza, Próximos eventos) y conforme se validen los elementos nativos dentro de
Local.*

*Para la mayor gloria de Dios · tiritaito.com*

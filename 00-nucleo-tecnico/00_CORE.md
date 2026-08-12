# TIRITAITO.COM — Contexto Maestro
*Reemplaza: ENTORNO_TÉCNICO · GUÍA_DE_ESTILO_VISUAL · tiritaito-guia-diseno-visual.md · secciones 2,9-14 de protocolo-tiritaito-creators-2.md*
*Leer siempre junto con: 01_CREATORS_APP.md (si trabajas en la PWA) · 02_REF_PODCAST.md (si trabajas en el podcast) · TIRITAITO_FOR_CREATORS_VERSIONS.md (si trabajas en V1/V2 de la app)*
*Corregido contra el HTML real de la app (auth, endpoints) y contra el backend reconstruido en Local — 26 julio 2026 · Ampliado con hallazgos de la ronda de Global Options y el catálogo de elementos Avada — 11 agosto 2026*

---

## 1. STACK TÉCNICO

| Componente | Detalle |
|---|---|
| Hosting | Raiola Networks (bloquea `/wp-admin/` para usuarios no logados) |
| CMS | WordPress en `/blog/` + Avada Live Builder |
| Plugin código | Code Snippets (free): tipos PHP y HTML únicamente — NO JS/CSS separados |
| Caché | LiteSpeed Cache — excluir `/blog/wp-json/tiritaito/` |
| Plugins activos | WPMobile.app · Jetpack · Limit Login Attempts · Administrador Archivos WP · ACF Pro (incluido con la licencia de Avada) |

**Regla crítica de Avada:** el `post_content` serializa Code Blocks en Base64. Jamás escribir ahí desde fuera. Todo dato dinámico → `wp_options`, ACF, o Biblioteca de Medios (ver Sección 3 para cuál usar en cada caso).

**`admin-ajax.php` no disponible** para usuarios públicos (bloqueo nivel servidor Raiola). Usar siempre REST API o shortcodes PHP server-side.

---

## 2. INFRAESTRUCTURA WORDPRESS

```
WordPress:        https://www.tiritaito.com/blog/
REST API:         https://www.tiritaito.com/blog/wp-json/wp/v2/
Endpoint propio:  https://www.tiritaito.com/blog/wp-json/tiritaito/v1/
Usuario app:      makecom  (rol: Editor)
Auth:             Token propio — TT_WRITE_TOKEN, vía header HTTP `X-TT-Token`
                   (Application Password descartado definitivamente)
```

### Constantes JS base

```javascript
const WP_BASE        = 'https://www.tiritaito.com/blog/wp-json';
const TT_WRITE_TOKEN  = 'PEGAR AQUÍ EL TOKEN REAL DE PRODUCCIÓN'; // nunca hardcodear en un doc compartido
const LOGO_URL       = 'https://www.tiritaito.com/blog/wp-content/uploads/2026/06/IMAGE-2026-06-19-10-47-20_resultado.webp';
const APP_PIN        = '1234'; // cambiar antes de producción
```

### Funciones JS base

✅ **Confirmado contra el HTML real de la app (26 julio 2026)** — ya no es una reconstrucción sin verificar.

```javascript
// Peticiones REST autenticadas
async function wpFetch(endpoint, options = {}) {
  const url     = WP_BASE + endpoint;
  const headers = { 'X-TT-Token': TT_WRITE_TOKEN, ...(options.headers || {}) };
  if (!(options.body instanceof FormData)) headers['Content-Type'] = 'application/json';
  const res = await fetch(url, { ...options, headers });
  if (!res.ok) throw new Error(`HTTP ${res.status}: ${await res.text()}`);
  return res.json();
}

// Subida con barra de progreso (usar XHR, no fetch — fetch no expone upload.progress)
function subirArchivo(file, onProgress) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest(), form = new FormData();
    form.append('file', file, file.name);
    xhr.upload.addEventListener('progress', e => {
      if (e.lengthComputable && onProgress) onProgress(Math.round((e.loaded/e.total)*100));
    });
    xhr.addEventListener('load', () => {
      xhr.status >= 200 && xhr.status < 300
        ? resolve(JSON.parse(xhr.responseText))
        : reject(new Error(`HTTP ${xhr.status}`));
    });
    xhr.addEventListener('error', () => reject(new Error('Error de red')));
    xhr.open('POST', WP_BASE + '/tiritaito/v1/subir');
    xhr.setRequestHeader('X-TT-Token', TT_WRITE_TOKEN);
    xhr.send(form); // NO añadir Content-Type — FormData lo pone solo con boundary correcto
  });
}

// Devocional + resto de wp_options — leer y escribir (modo híbrido, ver Sección 3)
async function leerOpciones() {
  const res = await fetch(WP_BASE + '/tiritaito/v1/datos', { headers: { 'X-TT-Token': TT_WRITE_TOKEN } });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}
async function guardarOpciones(datos) {
  await wpFetch('/tiritaito/v1/datos', { method: 'POST', body: JSON.stringify(datos) });
}
```

---

## 3. MAPA DE DATOS

⚠️ Este mapa cambió el 26 de julio de 2026: el contenido Devocional (Virgen, Brisa, Homilía,
Lenguas) migró de `wp_options` a una Options Page de ACF, y Novedades migró de `wp_options`
a un Custom Post Type (CPT) propio con ACF. El resto de claves se queda en `wp_options`.

### 3.1 ACF Options Page — "Devocional — Contenido Diario"

| Campo ACF | Tipo | Clave que usa la app (`/tiritaito/v1/datos`) | Frecuencia |
|---|---|---|---|
| `virgen` | Texto largo | `tt_virgen` | Diaria |
| `virgen_fecha` | Fecha (campo propio, separado del texto) — **obligatoria** antes de publicar (v2-05) | `tt_virgen_fecha` | Diaria |
| `brisa` | Texto largo | `tt_brisa` | Diaria |
| `brisa_autor` | Texto (campo propio, separado del texto) | `tt_brisa_autor` | Diaria |
| `homilia_audio` | URL Media Library | `tt_homilia_audio` | Diaria |
| `homilia_texto` | Texto largo — ⚠️ debería dejar de llevar fecha (decisión de Carlitos, 26 julio 2026: siempre es la fecha de publicación); la app en v2-05 todavía la pide y la pega al final del texto — pendiente de corregir | `tt_homilia_texto` | Diaria |
| `lenguas_url` | URL Media Library | `tt_lenguas_url` | Diaria |

⚠️ **Nombre de campo ACF ≠ clave de la API.** El campo dentro de ACF no lleva el prefijo
`tt_` (ej. `virgen_fecha`), pero la app manda y recibe esa clave CON el prefijo
(`tt_virgen_fecha`) en el JSON de `/tiritaito/v1/datos`. El snippet PHP traduce entre una
forma y otra. Escribir código nuevo contra el endpoint usando la clave con `tt_`.

⚠️ Escribir estos campos SIEMPRE con `update_field()`, nunca con `update_option()` — Avada
Dynamic Content (prefijo `awb_acfop_`) solo lee lo que ACF guarda con su propio prefijo
interno (`options_` + nombre del campo). Confirmado en el piloto del 22-23 julio 2026.

🔲 Pendiente sin decidir: si conviene desactivar el autoload de los campos de texto largo
(`virgen`, `brisa`, `homilia_texto`) — no verificado, sin datos reales que lo justifiquen.

**`tt_tip_1` / `tt_tip_2` — eliminados por decisión (26 julio 2026), pero ⚠️ todavía
presentes en el código de la app (confirmado en v2-05).** El concepto de "Tip del día" ya
no debe existir: ese contenido se sube ahora directamente como Novedades (ver 3.3). Falta
que Proyecto 5 retire la UI real.

### 3.2 wp_options — lo que se queda igual

| Clave | Tipo | Módulo | Frecuencia |
|---|---|---|---|
| `tt_docx_lectura_url` | URL Media Library | Lecturas | Semanal |
| `tt_youtube_json_url` | URL Media Library (`music-data.json`) | YouTube | Ocasional |
| `tt_seminarios_json_url` | URL Media Library (`Seminarios.json`) | YouTube | Ocasional |
| `tt_viacrucis_json_url` | URL Media Library (JSON) — 🔲 tipo inferido por el patrón de nombre; confirmar con Hno A | Vía Crucis | Ocasional |
| `tt_fiesta_dias` | JSON directo, no URL — 🔲 sigue sin estructura confirmada | Fiestas / calendario | Ocasional |

### 3.3 CPT `novedades` — Custom Post Type propio

Sustituye por completo a la antigua clave `tt_novedades` de `wp_options`. Esquema completo
en `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 6 — no se repite aquí para no duplicar
mantenimiento. 6 campos ACF por entrada (`tipo`, `media_url`, `texto`, `enlace`, `fecha`,
`activo`, `titulo`), gestionados vía endpoint propio `tiritaito/v1/novedades` (Sección 4),
nunca vía `/wp/v2/posts`.

✅ **Registro del CPT, confirmado contra el PHP real (26 julio 2026):** `public => false`
(no genera páginas propias públicas), `show_ui => true` (visible en wp-admin para revisar
a mano), `show_in_rest => true` (necesario para que ACF y REST lo vean), `supports =>
['title']`.

**El campo `activo` no filtra el listado público** (decisión de equipo, 26 julio 2026) —
Post Cards en Avada muestra todas las novedades, visibles u ocultas. `activo` queda solo
como control interno del editor en la app. Ver `GUIA_AVADA_LOCAL.md` Sección 9.

**Sanitizado por campo (confirmado 26 julio 2026):** cada campo de Devocional se limpia
según lo que realmente contiene (texto largo, URL, o fecha con patrón `YYYY-MM-DD`
validado por expresión regular) — a propósito, para evitar que `esc_url_raw()` se aplique
a un nombre de persona o a una fecha y corrompa acentos (ej. "José María"). El mismo
principio aplica a los campos de Novedades.

**Endpoint GET (público) de lectura:** token GET `ttcr2026sanjoseyvirgenmaria` —
🔲 probablemente sea el `TT_READ_TOKEN` mencionado en la investigación de integración
Avada+Creators, sin nombre formal asignado en este documento; confirmar y renombrar la
constante si es así.

**Header:** `Cache-Control: no-store` · **CORS:** priority 15

---

## 4. ENDPOINTS REST COMPLETOS

✅ **Confirmado contra el snippet PHP real y completo (`apps/v2/snippet-tt-creators-endpoint-central.php`, 26 julio 2026)** — ya no es una tabla reconstruida por evidencia indirecta del HTML.

| Método | Ruta | Auth | Función |
|---|---|---|---|
| GET | `/tiritaito/v1/datos` | Pública | Lee Devocional (ACF Options Page) + resto de wp_options (modo híbrido, ver Sección 3) |
| POST | `/tiritaito/v1/datos` | `X-TT-Token` | Guarda Devocional (ACF) + resto de wp_options (modo híbrido) |
| POST | `/tiritaito/v1/subir` | `X-TT-Token` | Sube archivo a la Biblioteca de Medios — ⚠️ sin validación de tipo MIME ni tamaño máximo en el propio endpoint (ver aviso más abajo) |
| GET | `/tiritaito/v1/medios` | `X-TT-Token` | Lista Biblioteca de Medios — a diferencia de `/datos`, esta ruta pide token incluso para leer |
| DELETE | `/tiritaito/v1/medio/{id}` | `X-TT-Token` | Elimina archivo permanente |
| GET | `/tiritaito/v1/novedades` | Pública | Lista todas las novedades (activas y ocultas, sin filtrar) |
| POST | `/tiritaito/v1/novedades` | `X-TT-Token` | Crea una novedad — el `id` lo asigna el servidor |
| PUT | `/tiritaito/v1/novedades/{id}` | `X-TT-Token` | Edita una novedad |
| DELETE | `/tiritaito/v1/novedades/{id}` | `X-TT-Token` | Elimina una novedad |

⚠️ **Confirmado que ya NO existen en el backend actual (26 julio 2026):** las rutas
`/tiritaito/v1/entradas` y `/tiritaito/v1/entrada/{id}` (gestión de posts normales desde la
app) y todo el subsistema `biblioteca/v1/*` (subida/importación de portadas de libros, con
su propio `BIBLIOTECA_TOKEN`) — ambos presentes en una versión anterior del snippet, pero
ausentes en el que está realmente activo hoy. 🔲 No confirmado si es una eliminación
deliberada o si esas piezas simplemente no se han reconstruido todavía tras la pérdida del
Local del 26 de julio — preguntar a Hno A antes de asumir cualquiera de las dos.

---

## 5. IDENTIDAD VISUAL — Variables CSS

```css
:root {
  /* Marca */
  --tt-red:      #BF4646;   /* acento, botones, alegría cristiana */
  --tt-red-d:    #A33B3B;   /* hover/active */
  --tt-red-bg:   #FDF2F2;   /* fondos sutiles */

  /* Escala grises iOS */
  --tt-txt:      #1d1d1f;   /* texto principal */
  --tt-txt2:     #3a3a3c;
  --tt-txt3:     #6e6e73;
  --tt-txt4:     #86868b;   /* placeholders, labels */
  --tt-sep:      #c7c7cc;   /* separadores */

  /* Superficies — NUNCA modo oscuro */
  --tt-bg:       #FFFFFF;   /* fondo = identidad de marca */
  --tt-surf:     #FFFFFF;
  --tt-surf2:    #F5F5F7;
  --tt-surf3:    #EBEBF0;

  /* Estados */
  --tt-green:    #34C759;
  --tt-green-bg: #F0FAF4;
  --tt-orange:   #FF9500;
  --tt-alert:    #FF3B30;

  /* Geometría — 25px es la firma visual Tiritaito */
  --tt-r:        25px;      /* cards, botones, containers */
  --tt-r-sm:     14px;      /* inputs, items secundarios */
  --tt-r-xs:     8px;       /* badges, iconos pequeños */
  --tt-sh:       0 2px 16px rgba(0,0,0,.07);
  --tt-sh-md:    0 4px 28px rgba(0,0,0,.10);

  /* Layout PWA */
  --tt-nav:      68px;
  --tt-sab:      env(safe-area-inset-bottom, 0px);
  --tt-sat:      env(safe-area-inset-top, 0px);
}
```

🔲 **Pendiente de formalizar (añadido 11 agosto 2026, sin resolver todavía):** en la ronda de
configuración de Avada Global Options, apareció un segundo valor de radio, `10px`, decidido
por Hna C de forma independiente en dos elementos distintos — Toggles y campos de
Formulario — sin relación aparente entre ambas decisiones más que la persona que las tomó.
Es un candidato razonable a cuarto token (ej. `--tt-r-int: 10px` para elementos interactivos
pequeños, dejando `--tt-r: 25px` para contenedores y superficies grandes), pero **no se
añade aquí todavía** porque no hay confirmación explícita de que sea una regla deliberada y
no dos coincidencias. Ver `GUIA_AVADA_LOCAL.md` Sección 13 y `CATALOGO_ELEMENTOS_AVADA.md`
Sección 13.1 para la evidencia completa. Mientras no se resuelva, no uses `10px` en ningún
elemento nuevo fuera de Toggles/Forms sin confirmarlo antes.

---

## 6. TIPOGRAFÍA

| Uso | Fuente | Notas |
|---|---|---|
| Títulos | **"Yeah! Papa"** | woff2 en Media Library: `uploads/2026/04/YeahPapa.woff2` |
| Cuerpo | **"Helvetica Neue"** | Sistema iOS, sin descarga |

```css
@font-face {
  font-family: 'Yeah Papa';
  src: url('https://www.tiritaito.com/blog/wp-content/uploads/2026/04/YeahPapa.woff2') format('woff2');
  font-display: swap;
}
body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
h1,h2,h3,h4 { font-family: 'Yeah Papa', 'Helvetica Neue', sans-serif; font-weight: normal; }
```

Jerarquía: `.tt-h1` 32px / `.tt-h2` 24px / `.tt-h3` 20px / `.tt-body` 15px / `.tt-caption` 12px / `.tt-label` 11px uppercase

✅ **Calibración de tamaño, confirmada de forma independiente 3 veces (última: 11 agosto
2026, durante la ronda de Global Options):** "Yeah Papa" necesita un tamaño en `px`
notablemente mayor que "Helvetica Neue" para lograr el mismo peso visual — la fuente "ocupa
menos" espacio dentro de su misma caja de diseño que una tipografía de palo seco estándar.
No es un defecto de la fuente ni un error de configuración — es una característica del
propio diseño de "Yeah Papa" que hay que tener en cuenta desde el principio al calibrar
cualquier título nuevo, en vez de descubrirlo cada vez por ensayo y error. Ejemplo real ya
aplicado en Avada: el título de Toggles se ajustó de 16px a 30px para lograr el peso visual
esperado.

---

## 7. CONVENCIONES DE MÓDULOS (Code Snippets)

| Regla | Valor |
|---|---|
| Tipo de snippet | HTML (con `<style>` + `<script>` juntos) — Code Snippets free no separa JS/CSS |
| Prefijo clases | `tt-` + BEM → `tt-modulo__elemento--modificador` |
| Variables CSS | Siempre `var(--tt-*)`, nunca hex sueltos |
| Breakpoints | `1024px` / `768px` / `480px` |
| Aislamiento | Los módulos NUNCA redefinen variables del Global ni tocan elementos genéricos |

### Patrón JS obligatorio

```javascript
// Condicional de existencia FUERA de ttReady
if (document.getElementById('mi-modulo-root')) {
  // Toda la lógica DENTRO de ttReady (o DOMContentLoaded si ttReady no está disponible)
  function initMiModulo() {
    (function() {
      'use strict';
      // IIFE — lógica del módulo
    })();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMiModulo);
  } else {
    initMiModulo();
  }
}
```

---

## 8. TRAMPAS TÉCNICAS CONOCIDAS

| Trampa | Descripción |
|---|---|
| `has_shortcode()` + Avada | Falso negativo — Avada codifica en Base64. CSS de módulos → `wp_head` siempre, incondicionalmente |
| `fetch` vs XHR en subidas | `fetch` no expone `upload.progress` — usar XHR para barras de progreso |
| FormData + Content-Type | No añadir `Content-Type: application/json` si el body es FormData — lo rompe |
| `type=module` race condition | Conectar PIN desde dentro del módulo, no con `onclick` en HTML |
| Snippets PHP duplicados | Dos snippets con mismo nombre de función → fatal "Cannot redeclare" |
| Code Snippets: HTML≠JS | JS en snippets tipo HTML (footer), nunca tipo PHP |
| Editar `post_content` Avada | Base64 serializado — sobrescribirlo desde fuera rompe el diseño |
| `admin-ajax.php` | Bloqueado por Raiola para usuarios públicos — usar REST API |
| `sessionStorage` vs `localStorage` para PIN | `sessionStorage` — debe expirar al cerrar pestaña |
| `TT_WRITE_TOKEN` expuesto en el HTML | El token vive hardcodeado en el HTML de la app (riesgo operativo, no de frontend público) — si se compromete, redistribuir el HTML es la mitigación actual, no hay rotación automática |
| ACF Options Page + `update_option()` | Escribir un campo de la Options Page con `update_option()` en vez de `update_field()` lo deja invisible para Avada Dynamic Content — ACF lo guarda con el prefijo `options_` por delante del nombre, no con el nombre plano. Confirmado en el piloto de Novedades (23 julio 2026) |
| Sin límite de peticiones en el backend actual (26 julio 2026) | El snippet reconstruido tras la pérdida del Local no tiene rate limiting — el PHP anterior sí lo tenía (60 peticiones/hora por IP con el token de escritura). No confirmado si es una omisión temporal o una decisión — preguntar a Hno A antes de asumir que es definitivo |
| Subida de archivos sin validar tipo ni tamaño (26 julio 2026) | `tt_subir_archivo()` llama directo a `media_handle_upload()` sin comprobar el tipo MIME real ni un tamaño máximo — el PHP anterior sí validaba ambos antes de subir. Mismo aviso: confirmar con Hno A si es intencional |
| Confundir "Slideshows" con "Post Slider" en Avada (11 agosto 2026) | Slideshows (Avada → Options) solo controla varias imágenes DENTRO de una misma entrada. Para rotar entre entradas distintas (ej. los 9 santos de Hombres de Dios) hace falta Post Slider, un elemento del Builder — confusión ya cometida y corregida dos veces de forma independiente durante la ronda de Global Options. Ver `GUIA_AVADA_LOCAL.md` Sección 9 |

*Eliminada la fila "Application Password con espacios" — ya no aplica desde que se descartó ese patrón de autenticación.*

---

## 9. CHECKLIST NUEVO COMPONENTE

- [ ] `border-radius: var(--tt-r)` en cards y botones — ver nota pendiente de la Sección 5 sobre `10px` antes de introducir un radio distinto
- [ ] Fondo `var(--tt-bg)` — blanco puro, sin modo oscuro
- [ ] Títulos en 'Yeah Papa' (calibrar el tamaño en `px` más alto de lo que parece necesario a simple vista, ver Sección 6), cuerpo en 'Helvetica Neue'
- [ ] Colores siempre vía `var(--tt-*)`, nunca hex sueltos
- [ ] Breakpoints en 1024 / 768 / 480px
- [ ] JS en IIFE, condicional fuera, lógica dentro
- [ ] Mensajes de error en lenguaje humano (no códigos HTTP)
- [ ] Barra de progreso visible en subidas de archivo
- [ ] Si el componente lee/escribe contenido dinámico: ¿va en ACF (Options Page o CPT) o en `wp_options`? Ver Sección 3 antes de decidir, y siempre `update_field()` si es ACF
- [ ] Si el componente se puede resolver con un elemento nativo de Avada: consultar `CATALOGO_ELEMENTOS_AVADA.md` antes de escribir código nuevo (ver `GUIA_AVADA_LOCAL.md` Sección 8)

---

## 10. NOTAS SOBRE EL REPRODUCTOR DE PODCAST

El reproductor existente (`TT Podcast`) usa prefijo `.pp-*` y hex directo — es anterior al estándar `tt-` / `var(--tt-*)`. No es un error, es deuda técnica. No migrar salvo que se refactorice explícitamente.

`has_shortcode()` falla con Avada. El CSS del podcast se inyecta con `wp_head` unconditional (priority 5) como workaround documentado.

---

*Para la mayor gloria de Dios · tiritaito.com*

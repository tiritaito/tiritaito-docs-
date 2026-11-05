# TIRITAITO.COM — Contexto Maestro
*Reemplaza: ENTORNO_TÉCNICO · GUÍA_DE_ESTILO_VISUAL · tiritaito-guia-diseno-visual.md · secciones 2,9-14 de protocolo-tiritaito-creators-2.md*
*Leer siempre junto con: 01_CREATORS_APP.md (si trabajas en la PWA) · 02_REF_PODCAST.md (si trabajas en el podcast) · TIRITAITO_FOR_CREATORS_VERSIONS.md (si trabajas en V1/V2 de la app)*
*Corregido contra el HTML real de la app (auth, endpoints) y contra el backend reconstruido en Local — 26 julio 2026 · Ampliado con hallazgos de la ronda de Global Options y el catálogo de elementos Avada — 11 agosto 2026 · Ampliado 10 de octubre de 2026 con el mapa de datos del podcast y la convención de las novenas · Ampliado 30 de octubre de 2026 con el importador de vídeos de YouTube · Ampliado 4 de noviembre de 2026 con molde, categoría y ocultar de verdad en Novedades*

---

## 1. STACK TÉCNICO

| Componente | Detalle |
|---|---|
| Hosting | Raiola Networks (bloquea `/wp-admin/` para usuarios no logados) |
| CMS | WordPress en `/blog/` + Avada Live Builder |
| Plugin código | Code Snippets (free): tipos PHP y HTML únicamente — NO JS/CSS separados. Incluye los snippets «TT Podcast — Importador» y «TT Vídeos YouTube — Importador» (ambos con alcance «Run snippet everywhere») |
| Caché | LiteSpeed Cache — excluir `/blog/wp-json/tiritaito/` |
| Plugins activos | WPMobile.app · Jetpack · Limit Login Attempts · Administrador Archivos WP · ACF Pro (incluido con la licencia de Avada) |
| Claves en `wp-config.php` | `TT_YT_API_KEY` (clave de la API de YouTube del importador de vídeos; **una distinta para Local**; el valor nunca va en el repositorio ni en documentos) |

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
| `tt_youtube_json_url` | URL Media Library (`music-data.json`) — ⏳ **transitoria**: la sustituye el importador de vídeos (3.6) cuando migre Música | YouTube | Ocasional |
| `tt_seminarios_json_url` | URL Media Library (`Seminarios.json`) — ⏳ **transitoria**: igual, cuando migre Seminarios | YouTube | Ocasional |
| `tt_viacrucis_json_url` | URL Media Library (JSON) — 🔲 tipo inferido por el patrón de nombre; confirmar con Hno A — ⏳ **transitoria**: igual, cuando migre Vía Crucis | Vía Crucis | Ocasional |
| `tt_fiesta_dias` | JSON directo, no URL — 🔲 sigue sin estructura confirmada | Fiestas / calendario | Ocasional |

### 3.3 CPT `novedades` — Custom Post Type propio

Sustituye por completo a la antigua clave `tt_novedades` de `wp_options`. Esquema completo
en `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 6 — no se repite aquí para no duplicar
mantenimiento. 10 campos ACF por entrada (`tipo`, `media_url`, `texto`, `enlace`, `fecha`,
`activo`, `titulo`, `categoria`, `molde` e `imagen`, esta última sin uso: la foto sale de `media_url`), gestionados vía endpoint propio `tiritaito/v1/novedades` (Sección 4),
nunca vía `/wp/v2/posts`.

✅ **Registro del CPT, confirmado contra el PHP real (26 julio 2026):** `public => false`
(no genera páginas propias públicas), `show_ui => true` (visible en wp-admin para revisar
a mano), `show_in_rest => true` (necesario para que ACF y REST lo vean), `supports =>
['title']`.

**El campo `activo` oculta de verdad (desde el 4 de noviembre de 2026)** — revierte la
decisión del 26 julio. `activo = false` pasa la entrada a **borrador** y deja de salir en la
web (Post Cards solo pinta publicadas); `true` la devuelve a publicada. El endpoint lee el
estado real de la entrada, no solo el campo. Las novedades ocultas solo se devuelven a quien
manda `X-TT-Token`.

**Campos `molde` y `categoria` (Select de ACF, retorno Valor):** sus opciones se leen de ACF,
no están escritas en el PHP ni en la app. Añadir un molde nuevo = añadir una línea a las
opciones del campo. `molde` debe tener valor por defecto (`estandar`): una novedad sin molde
sale en blanco. Las claves `field_…` no se escriben en el PHP: se buscan por nombre en tiempo
de ejecución, porque cambian entre Local y producción. Guía: `GUIA_ANADIR_MOLDE_NOVEDADES.md`.

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

### 3.4 CPT `episodio`, `temporada` y taxonomía `canal` (podcast)

El contenido del podcast vive en dos tipos propios y una taxonomía, alimentados por el importador. Esquema completo, estado y hallazgos en `03-guias-practicas/PODCAST_ESTRUCTURA.md` Sección 3; aquí solo los nombres.

- **`episodio`:** `ep_guid`, `ep_audio_url`, `ep_youtube_url` (Texto), `ep_imagen_url`, `ep_duracion`, `ep_numero`, `ep_temporada`, `ep_temporada_ref`, `ep_temporada_url`, `ep_texto` (Editor), `ep_hash`.
- **`temporada`:** `tmp_numero`, `tmp_canal`, `tmp_canal_url`, `tmp_canal_nombre`, `tmp_episodios`, `tmp_descripcion`.
- **Canal (Hombres de Dios):** `hdd_podcast_feed`, `hdd_podcast_caratula`, `hdd_temporadas`, `hdd_episodios`. **Resto de canales:** `pod_feed`, `pod_caratula`, `pod_temporadas`, `pod_episodios`.
- **Privados del importador:** `_tt_pod_clave`, `_tt_pod_canal`, `_tt_pod_term`; registro en `wp_options`, clave `tt_podcast_importador_log`.

⚠️ El importador escribe con `update_field()` **por clave de campo** (no por nombre), para que ACF guarde también la referencia.

### 3.5 CPT de las novenas de días

Cada novena de días tiene un tipo de contenido propio (`novena_<tema>`, público, sin archivo) y un prefijo de tres letras para sus casillas: `nes_` (Espíritu Santo), `nsc_` (Sagrados Corazones), `nin_` (Inmaculada). Casilla: `<prefijo>_<apartado>_<campo>`; el número del día, `<prefijo>_dia`. **Convención completa, fichas y casillas propuestas** en `03-guias-practicas/ORACIONES_ESTRUCTURA.md` Sección 3.4. Solo `novena_inmaculada` y `nin_dia` están creadas (☑); las demás son propuesta hasta sellar sus fichas.

### 3.6 CPT de vídeos de YouTube (`cancion`, `viacrucis`, `seminario`) y taxonomía `categoria_musica`

Una ficha por vídeo, alimentada por el importador de vídeos (`apps/v2/tt-video-importador.php`, escrito el 30 octubre 2026, **sin ejecutar aún en Local**). Esquema, decisiones y casos de prueba en `02-metodologia/investigaciones/TIRITAITO_MUSIC_WEB_NUEVA.md` Secciones 5, 6 y 6.8; aquí solo los nombres.

- **Tipos:** `cancion` (creado en Local; público, con página propia compartible) · `viacrucis` y `seminario` (⚠️ nombres propuestos, sin crear). **Taxonomía:** `categoria_musica` (creada) · `categoria_viacrucis` (⚠️ propuesta). «Todas» **no** es categoría: su papel lo hace la pestaña «All» de Avada.
- **Grupo de casillas «TT — Vídeo YouTube»** (asignado a todos los tipos de vídeo): `vid_video_id`, `vid_youtube_url` (Texto), `vid_miniatura_url` (URL), `vid_fecha_video` (Texto `AAAA-MM-DD`), `vid_comprobado` (Texto, fecha y hora en UTC), `vid_hash`; opcionales: `vid_kids`, `sem_anio`, `sem_mes`, `vcr_numero`.
- **Orden:** nativo, en `menu_order` (Avada ordena por *Menu Order*, ascendente); no hay casilla de orden.
- **Privados del importador:** `_tt_vid_area`, `_tt_vid_playlists`, `_tt_vid_retirada`, `_tt_vid_vaciada` (en las fichas) y `_tt_vid_playlist` (en cada categoría: ID de la playlist que la alimenta). Ajustes y registro en `wp_options`: `tt_video_importador_filas`, `tt_video_importador_log`.
- **Regla de los 30 días de YouTube:** los datos de la API solo se guardan 30 días sin refrescar. El importador refresca a diario, vacía las retiradas a los 25 días y avisa a los 10 (amarillo) y 20 (rojo).
- **Endpoint del semáforo:** `GET /tiritaito/v1/videos/estado` (token `X-TT-Token`).

⚠️ Igual que en el podcast, el importador escribe con `update_field()` **por clave de campo**.

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
| GET | `/tiritaito/v1/novedades` | Pública (con o sin token) | Sin token: solo las publicadas. Con `X-TT-Token`: publicadas y ocultas. Incluye `categoria` y `molde` |
| GET | `/tiritaito/v1/novedades/opciones` | Pública | Opciones de molde y categoría leídas de ACF, y `molde_defecto` |
| POST | `/tiritaito/v1/novedades` | `X-TT-Token` | Crea una novedad — el `id` lo asigna el servidor |
| PUT | `/tiritaito/v1/novedades/{id}` | `X-TT-Token` | Edita una novedad. `molde`, `categoria` y `activo` solo se tocan si se mandan; los demás campos, si no se mandan, se borran |
| DELETE | `/tiritaito/v1/novedades/{id}` | `X-TT-Token` | Elimina una novedad |
| GET | `/tiritaito/v1/videos/estado` | `X-TT-Token` | Semáforo del importador de vídeos (color, días, filas, frenos, última ejecución). Snippet propio `apps/v2/tt-video-importador.php`, no el central. 🔲 Escrito 30 octubre 2026, sin probar; usa `TT_WRITE_TOKEN` si está definida como constante — **verificar contra el snippet central** |

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
| Atributo `seasons` de `[tt_podcast]` con comillas mal anidadas (16 septiembre 2026) | Debe ser JSON válido con comillas dobles por dentro y comillas simples envolviendo todo el atributo (`seasons='{"1":"Nombre"}'`). Comillas simples anidadas rompen el parseo de WordPress **en silencio** — no da error, el mapa de temporadas llega vacío y los nombres no se aplican. Verificado ejecutando `shortcode_parse_atts()` real de WordPress contra el string (solo web vieja, V1) |
| `update_field()` por nombre en entradas nuevas (10 octubre 2026) | Sin una referencia previa, ACF no localiza el campo: usar la clave del campo (`field_…`) |
| ACF Relationship / Objeto de entrada como *Button URL* (9 octubre 2026) | No devuelve una dirección: el botón no lleva a ningún sitio. Usar una casilla de URL escrita por el importador |
| No hay «Post Content» en el contenido dinámico (9 octubre 2026) | Usar una casilla de tipo Editor con el texto completo (`ep_texto`) |
| Renombrar la entrada de un canal duplicaba sus episodios (10 octubre 2026) | Pasaba si la identidad del canal dependía de su slug. El importador v1.1 usa el ID de la entrada (`_tt_pod_canal`, `_tt_pod_term`) |
| **Error 153 de YouTube** («Video player configuration error») (30 octubre 2026) | El reproductor exige que la petición identifique a la web con la cabecera `Referer` (historial de la IFrame Player API, 9 julio 2025). No suprimirla con un `Referrer-Policy` estricto de servidor o plugin de seguridad; usar `strict-origin-when-cross-origin` en el iframe o en la página. Prueba A2: sin `Referrer-Policy` en Local ni en producción, no apareció el 153 |
| Regla de los 30 días de la API de YouTube (30 octubre 2026) | Los datos pedidos a la API sin login solo pueden guardarse 30 días naturales sin refrescarlos o borrarlos. Un importador parado más de 30 días, o fichas retiradas que nadie refresca, la incumplen. El importador de vídeos refresca a diario, vacía a los 25 días y avisa a 10 y 20 |
| Una playlist **privada** no la lee una clave de API (30 octubre 2026) | La fila saldrá en error («privada o no se puede leer»); nunca retira fichas por ello |
| `is_singular( array() )` es verdadero en cualquier página (30 octubre 2026) | Con un array vacío WordPress no filtra: comprobar que la lista de tipos no está vacía antes de usarlo (el importador de vídeos lo hace) |

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
- [ ] Si el contenido llega de un feed externo y se repite: ¿entra por el importador (tipo propio + relación)? Ver `PODCAST_ESTRUCTURA.md`. Si son los días de una novena: ver `ORACIONES_ESTRUCTURA.md`
- [ ] Si el contenido es una lista de vídeos de una playlist de YouTube: ¿una fila nueva en el importador de vídeos? Ver Sección 3.6

---

## 10. NOTAS SOBRE EL REPRODUCTOR DE PODCAST

El reproductor `TT Podcast` (`.pp-*`, `[tt_podcast]`) es solo de la **web vieja (V1)**: usa prefijo `.pp-*` y hex directo, es anterior al estándar `tt-` / `var(--tt-*)` y es deuda técnica; no se migra. La web nueva usa el elemento Audio de Avada y el sistema de `PODCAST_ESTRUCTURA.md`.

`has_shortcode()` falla con Avada. El CSS del podcast se inyecta con `wp_head` unconditional (priority 5) como workaround documentado.

---

*Para la mayor gloria de Dios · tiritaito.com*

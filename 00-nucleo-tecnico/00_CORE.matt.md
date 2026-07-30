<!--
  ⚠️ ARCHIVO DE TRABAJO CON DATOS FICTICIOS (PLACEHOLDER)
  Este NO es el archivo oficial de producción/Local. Todos los tokens,
  dominios, credenciales y valores concretos aquí son ficticios y NO
  funcionarán contra ningún entorno real de Tiritaito. Trabaja con
  normalidad sobre este archivo — tus cambios se revisan y se aplican
  al archivo oficial (00_CORE.md) por el equipo interno.
  Si necesitas un valor real para probar algo end-to-end, pídelo
  directamente — no lo asumas ni lo inventes.
-->

# TIRITAITO.COM — Contexto Maestro (copia de trabajo — datos ficticios)
*Reemplaza: ENTORNO_TÉCNICO · GUÍA_DE_ESTILO_VISUAL · tiritaito-guia-diseno-visual.md · secciones 2,9-14 de protocolo-tiritaito-creators-2.md*
*Leer siempre junto con: 01_CREATORS_APP.md (si trabajas en la PWA) · 02_REF_PODCAST.md (si trabajas en el podcast) · TIRITAITO_FOR_CREATORS_VERSIONS.matt.md (si trabajas en V1/V2 de la app)*

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
WordPress:        https://www.ejemplo-tiritaito.local/blog/
REST API:         https://www.ejemplo-tiritaito.local/blog/wp-json/wp/v2/
Endpoint propio:  https://www.ejemplo-tiritaito.local/blog/wp-json/tiritaito/v1/
Usuario app:      usuario_ejemplo  (rol: Editor)
Auth:             Token propio — TT_WRITE_TOKEN, vía header HTTP `X-TT-Token`
                   (Application Password descartado definitivamente)
```

### Constantes JS base (placeholder)

```javascript
const WP_BASE        = 'https://www.ejemplo-tiritaito.local/blog/wp-json';
const TT_WRITE_TOKEN  = 'PLACEHOLDER_NO_ES_TOKEN_REAL_PIDE_EL_VALOR_REAL_SI_LO_NECESITAS';
const LOGO_URL       = 'https://www.ejemplo-tiritaito.local/blog/wp-content/uploads/logo-placeholder.png';
const APP_PIN        = '0000'; // placeholder — nunca el PIN real
```

### Funciones JS base

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
| `homilia_texto` | Texto largo — ⚠️ debería dejar de llevar fecha (decisión: siempre es la fecha de publicación); pendiente de corregir en la app | `tt_homilia_texto` | Diaria |
| `lenguas_url` | URL Media Library | `tt_lenguas_url` | Diaria |

⚠️ **Nombre de campo ACF ≠ clave de la API.** El campo dentro de ACF no lleva el prefijo
`tt_` (ej. `virgen_fecha`), pero la app manda y recibe esa clave CON el prefijo
(`tt_virgen_fecha`) en el JSON de `/tiritaito/v1/datos`. El snippet PHP traduce entre una
forma y otra. Escribir código nuevo contra el endpoint usando la clave con `tt_`.

⚠️ Escribir estos campos SIEMPRE con `update_field()`, nunca con `update_option()` — Avada
Dynamic Content (prefijo `awb_acfop_`) solo lee lo que ACF guarda con su propio prefijo
interno (`options_` + nombre del campo).

**`tt_tip_1` / `tt_tip_2` — eliminados por decisión de producto.** El concepto de "Tip del
día" ya no debe existir: ese contenido se sube ahora directamente como Novedades (ver 3.3).

### 3.2 wp_options — lo que se queda igual

| Clave | Tipo | Módulo | Frecuencia |
|---|---|---|---|
| `tt_docx_lectura_url` | URL Media Library | Lecturas | Semanal |
| `tt_youtube_json_url` | URL Media Library (`music-data.json`) | YouTube | Ocasional |
| `tt_seminarios_json_url` | URL Media Library (`Seminarios.json`) | YouTube | Ocasional |
| `tt_viacrucis_json_url` | URL Media Library (JSON) | Vía Crucis | Ocasional |
| `tt_fiesta_dias` | JSON directo, no URL | Fiestas / calendario | Ocasional |

### 3.3 CPT `novedades` — Custom Post Type propio

Sustituye por completo a la antigua clave `tt_novedades` de `wp_options`. Esquema completo
en `TIRITAITO_FOR_CREATORS_VERSIONS.matt.md` Sección 6. 6 campos ACF por entrada (`tipo`,
`media_url`, `texto`, `enlace`, `fecha`, `activo`, `titulo`), gestionados vía endpoint
propio `tiritaito/v1/novedades`, nunca vía `/wp/v2/posts`.

**Registro del CPT:** `public => false` (no genera páginas propias públicas), `show_ui =>
true` (visible en wp-admin), `show_in_rest => true` (necesario para ACF y REST), `supports
=> ['title']`.

**El campo `activo` no filtra el listado público** (decisión de equipo) — Post Cards en
Avada muestra todas las novedades, visibles u ocultas. `activo` queda solo como control
interno del editor en la app.

**Sanitizado por campo:** cada campo de Devocional se limpia según lo que realmente
contiene (texto largo, URL, o fecha con patrón `YYYY-MM-DD` validado por expresión
regular) — a propósito, para evitar que `esc_url_raw()` se aplique a un nombre de persona
o a una fecha y corrompa acentos (ej. "José María"). El mismo principio aplica a los campos
de Novedades.

**Endpoint GET (público) de lectura:** token GET `PLACEHOLDER_TOKEN_LECTURA` —
🔲 probablemente sea el `TT_READ_TOKEN` mencionado en investigación previa, sin nombre
formal asignado en este documento.

**Header:** `Cache-Control: no-store` · **CORS:** priority 15

---

## 4. ENDPOINTS REST COMPLETOS

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

⚠️ **Confirmado que ya NO existen en el backend actual:** las rutas `/tiritaito/v1/entradas`
y `/tiritaito/v1/entrada/{id}` (gestión de posts normales desde la app) y todo el
subsistema `biblioteca/v1/*` (subida/importación de portadas de libros, con su propio
`BIBLIOTECA_TOKEN`) — ambos presentes en una versión anterior del snippet, pero ausentes en
el que está realmente activo hoy. 🔲 No confirmado si es una eliminación deliberada o si
esas piezas simplemente no se han reconstruido todavía.

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

---

## 6. TIPOGRAFÍA

| Uso | Fuente | Notas |
|---|---|---|
| Títulos | **"Yeah! Papa"** | woff2 en Media Library: `uploads/2026/04/YeahPapa.woff2` |
| Cuerpo | **"Helvetica Neue"** | Sistema iOS, sin descarga |

```css
@font-face {
  font-family: 'Yeah Papa';
  src: url('https://www.ejemplo-tiritaito.local/blog/wp-content/uploads/2026/04/YeahPapa.woff2') format('woff2');
  font-display: swap;
}
body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
h1,h2,h3,h4 { font-family: 'Yeah Papa', 'Helvetica Neue', sans-serif; font-weight: normal; }
```

Jerarquía: `.tt-h1` 32px / `.tt-h2` 24px / `.tt-h3` 20px / `.tt-body` 15px / `.tt-caption` 12px / `.tt-label` 11px uppercase

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
| ACF Options Page + `update_option()` | Escribir un campo de la Options Page con `update_option()` en vez de `update_field()` lo deja invisible para Avada Dynamic Content — ACF lo guarda con el prefijo `options_` por delante del nombre, no con el nombre plano |
| Sin límite de peticiones en el backend actual | El snippet reconstruido no tiene rate limiting — una versión anterior sí lo tenía (60 peticiones/hora por IP con el token de escritura). No confirmado si es una omisión temporal o una decisión |
| Subida de archivos sin validar tipo ni tamaño | `tt_subir_archivo()` llama directo a `media_handle_upload()` sin comprobar el tipo MIME real ni un tamaño máximo — una versión anterior sí validaba ambos antes de subir |

---

## 9. CHECKLIST NUEVO COMPONENTE

- [ ] `border-radius: var(--tt-r)` en cards y botones
- [ ] Fondo `var(--tt-bg)` — blanco puro, sin modo oscuro
- [ ] Títulos en 'Yeah Papa', cuerpo en 'Helvetica Neue'
- [ ] Colores siempre vía `var(--tt-*)`, nunca hex sueltos
- [ ] Breakpoints en 1024 / 768 / 480px
- [ ] JS en IIFE, condicional fuera, lógica dentro
- [ ] Mensajes de error en lenguaje humano (no códigos HTTP)
- [ ] Barra de progreso visible en subidas de archivo
- [ ] Si el componente lee/escribe contenido dinámico: ¿va en ACF (Options Page o CPT) o en `wp_options`? Ver Sección 3 antes de decidir, y siempre `update_field()` si es ACF

---

## 10. NOTAS SOBRE EL REPRODUCTOR DE PODCAST

El reproductor existente (`TT Podcast`) usa prefijo `.pp-*` y hex directo — es anterior al estándar `tt-` / `var(--tt-*)`. No es un error, es deuda técnica. No migrar salvo que se refactorice explícitamente.

`has_shortcode()` falla con Avada. El CSS del podcast se inyecta con `wp_head` unconditional (priority 5) como workaround documentado.

---

*Para la mayor gloria de Dios · tiritaito.com*

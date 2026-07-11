# TIRITAITO.COM — Contexto Maestro
*Reemplaza: ENTORNO_TÉCNICO · GUÍA_DE_ESTILO_VISUAL · tiritaito-guia-diseno-visual.md · secciones 2,9-14 de protocolo-tiritaito-creators-2.md*
*Leer siempre junto con: 01_CREATORS_APP.md (si trabajas en la PWA) · 02_REF_PODCAST.md (si trabajas en el podcast)*

---

## 1. STACK TÉCNICO

| Componente | Detalle |
|---|---|
| Hosting | Raiola Networks (bloquea `/wp-admin/` para usuarios no logados) |
| CMS | WordPress en `/blog/` + Avada Live Builder |
| Plugin código | Code Snippets (free): tipos PHP y HTML únicamente — NO JS/CSS separados |
| Caché | LiteSpeed Cache — excluir `/blog/wp-json/tiritaito/` |
| Plugins activos | WPMobile.app · Jetpack · Limit Login Attempts · Administrador Archivos WP |

**Regla crítica de Avada:** el `post_content` serializa Code Blocks en Base64. Jamás escribir ahí desde fuera. Todo dato dinámico → `wp_options` + Biblioteca de Medios.

**`admin-ajax.php` no disponible** para usuarios públicos (bloqueo nivel servidor Raiola). Usar siempre REST API o shortcodes PHP server-side.

---

## 2. INFRAESTRUCTURA WORDPRESS

```
WordPress:        https://www.tiritaito.com/blog/
REST API:         https://www.tiritaito.com/blog/wp-json/wp/v2/
Endpoint propio:  https://www.tiritaito.com/blog/wp-json/tiritaito/v1/
Usuario app:      makecom  (rol: Editor)
Auth:             Application Password de WordPress
```

### Constantes JS base

```javascript
const WP_BASE  = 'https://www.tiritaito.com/blog/wp-json';
const WP_USER  = 'makecom';
const WP_PASS  = 'xxxx xxxx xxxx xxxx xxxx xxxx'; // .replace(/\s/g,'') antes del btoa()
const AUTH     = 'Basic ' + btoa(WP_USER + ':' + WP_PASS.replace(/\s/g, ''));
const LOGO_URL = 'https://www.tiritaito.com/blog/wp-content/uploads/2026/06/IMAGE-2026-06-19-10-47-20_resultado.webp';
const APP_PIN  = '1234'; // cambiar antes de producción
```

### Funciones JS base (siempre usar estas, no reinventar)

```javascript
// Peticiones REST autenticadas
async function wpFetch(endpoint, options = {}) {
  const url     = WP_BASE + endpoint;
  const headers = { 'Authorization': AUTH, ...(options.headers || {}) };
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
    xhr.open('POST', WP_BASE + '/wp/v2/media');
    xhr.setRequestHeader('Authorization', AUTH);
    xhr.setRequestHeader('Content-Disposition', `attachment; filename="${encodeURIComponent(file.name)}"`);
    xhr.send(form); // NO añadir Content-Type — FormData lo pone solo con boundary correcto
  });
}

// wp_options — leer (público) y escribir (autenticado)
async function leerOpciones() {
  const res = await fetch(WP_BASE + '/tiritaito/v1/datos');
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}
async function guardarOpciones(datos) {
  await wpFetch('/tiritaito/v1/datos', { method: 'POST', body: JSON.stringify(datos) });
}
```

---

## 3. MAPA DE DATOS — wp_options

| Clave | Tipo | Módulo | Frecuencia |
|---|---|---|---|
| `tt_virgen` | Texto largo (fecha en 1ª línea) | Devocional | Diaria |
| `tt_brisa` | Texto largo (autor en última línea con —) | Devocional | Diaria |
| `tt_homilia_audio` | URL Media Library | Devocional | Diaria |
| `tt_homilia_texto` | Texto largo (fecha en última línea) | Devocional | Diaria |
| `tt_lenguas_url` | URL Media Library | Devocional | Diaria |
| `tt_tip_1` | URL Media Library | Devocional | Diaria |
| `tt_tip_2` | URL Media Library (opcional) | Devocional | Diaria |
| `tt_docx_lectura_url` | URL Media Library | Lecturas | Semanal |
| `tt_youtube_json_url` | URL Media Library (`music-data.json`) | YouTube | Ocasional |
| `tt_seminarios_json_url` | URL Media Library (`Seminarios.json`) | YouTube | Ocasional |

**Endpoint tiritaito/v1:**
- Token GET (público): `ttcr2026sanjoseyvirgenmaria`
- POST: requiere Application Password de `makecom`
- Header: `Cache-Control: no-store`
- CORS: priority 15

---

## 4. ENDPOINTS REST COMPLETOS

| Método | Ruta | Función |
|---|---|---|
| GET | `/tiritaito/v1/datos` | Lee wp_options (widgets + app) |
| POST | `/tiritaito/v1/datos` | Guarda wp_options (app) |
| GET | `/wp/v2/media` | Lista Biblioteca de Medios |
| POST | `/wp/v2/media` | Sube archivo |
| DELETE | `/wp/v2/media/{id}?force=true` | Elimina archivo permanente |
| GET | `/wp/v2/posts` | Lista entradas |
| POST | `/wp/v2/posts` | Crea entrada |
| PUT | `/wp/v2/posts/{id}` | Edita entrada |
| DELETE | `/wp/v2/posts/{id}?force=true` | Elimina entrada |
| GET | `/wp/v2/categories` | Lista categorías |

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
  src: url('https://www.tiritaito.com/blog/wp-content/uploads/2026/04/YeahPapa.woff2') format('woff2');
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
| Application Password con espacios | `.replace(/\s/g,'')` antes de `btoa()` o da 401 |
| `fetch` vs XHR en subidas | `fetch` no expone `upload.progress` — usar XHR para barras de progreso |
| FormData + Content-Type | No añadir `Content-Type: application/json` si el body es FormData — lo rompe |
| `type=module` race condition | Conectar PIN desde dentro del módulo, no con `onclick` en HTML |
| Snippets PHP duplicados | Dos snippets con mismo nombre de función → fatal "Cannot redeclare" |
| Code Snippets: HTML≠JS | JS en snippets tipo HTML (footer), nunca tipo PHP |
| Editar `post_content` Avada | Base64 serializado — sobrescribirlo desde fuera rompe el diseño |
| `admin-ajax.php` | Bloqueado por Raiola para usuarios públicos — usar REST API |
| `sessionStorage` vs `localStorage` para PIN | `sessionStorage` — debe expirar al cerrar pestaña |

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

---

## 10. NOTAS SOBRE EL REPRODUCTOR DE PODCAST

El reproductor existente (`TT Podcast`) usa prefijo `.pp-*` y hex directo — es anterior al estándar `tt-` / `var(--tt-*)`. No es un error, es deuda técnica. No migrar salvo que se refactorice explícitamente.

`has_shortcode()` falla con Avada. El CSS del podcast se inyecta con `wp_head` unconditional (priority 5) como workaround documentado.

---

*Para la mayor gloria de Dios · tiritaito.com*

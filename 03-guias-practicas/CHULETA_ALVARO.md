# TIRITAITO.COM — Chuleta rápida de construcción (Álvaro)
**Referencia compacta para las cuentas ligeras de construcción — Proyecto 3 y Proyecto 7**
*Destilada de `00_CORE.md`, `04_ENTORNO_LOCAL.md`, `GUIA_AVADA_LOCAL.md` y `CATALOGO_ELEMENTOS_AVADA.md` — 22 de septiembre de 2026, a raíz del estudio de consumo de uso de Claude*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es esto y qué hacer si no basta

Esta chuleta sustituye, en las cuentas **ligeras** de construcción (Proyecto 3 y Proyecto 7), a la base completa (`00_CORE.md` + `04_ENTORNO_LOCAL.md` + `GUIA_AVADA_LOCAL.md` + `CATALOGO_ELEMENTOS_AVADA.md` + `apps/v2/` completa + `exports/`). Contiene solo lo que hace falta para el 90 % del trabajo diario: consultas puntuales y construcción rutinaria.

**Si la pregunta no está aquí, o necesitas leer el HTML/PHP real de la app, o algo requiere de verdad el histórico completo de una sección (`GUIA_AVADA_LOCAL.md`, `CATALOGO_ELEMENTOS_AVADA.md`): dilo explícitamente y pásate a la cuenta de Consulta Técnica Profunda (Proyecto 10), que sí tiene el repositorio completo.** No inventes ni aproximes un valor que no está aquí.

Cada dato lleva su nivel de certeza tal como está en el documento de origen: ✅ confirmado · ⚠️ con un matiz sin cerrar · 🔲 sin confirmar · ❌ descartado.

---

## 1. Stack e infraestructura

| Componente | Detalle |
|---|---|
| Hosting (producción) | Raiola Networks — bloquea `/wp-admin/` a usuarios no logados |
| CMS | WordPress + Avada Live Builder |
| Entorno de trabajo | **Local by Flywheel**, dominio **`tiritaito-real.local`** — ⚠️ instalado en la **raíz**, sin `/blog/` como producción. Nunca intentes alinearlo con producción vía Ajustes → Generales: provoca un bucle de redirecciones y rompe los estilos. Si hace falta corregir algo, usa WP-CLI (botón "Open Site Shell" en Local) |
| Plugin de código | Code Snippets (free) — solo tipos **PHP** y **HTML**, nunca JS/CSS separados |
| Caché | LiteSpeed Cache — excluir `/blog/wp-json/tiritaito/` (en producción) |
| ACF Pro / FileBird Pro | Incluidos gratis con la licencia de Avada |
| SSL en Local | Se genera solo, pero necesita "Trust" manual en la pestaña SSL del sitio — si no, puede romper en silencio las respuestas de la API |

**Regla crítica de Avada:** el `post_content` serializa Code Blocks en Base64. Nunca escribir ahí desde fuera. Todo dato dinámico va a `wp_options`, ACF, o Biblioteca de Medios (ver Sección 3).

**`admin-ajax.php` no disponible** para usuarios públicos (bloqueo de Raiola). Usar siempre REST API o shortcodes PHP server-side.

---

## 2. Autenticación y funciones JS base

✅ Confirmado contra el HTML real de la app y el backend reconstruido.

```
Auth: token propio TT_WRITE_TOKEN, header HTTP X-TT-Token
      (Application Password descartado definitivamente — nunca usarlo)
```

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

// Subida con progreso — SIEMPRE XHR, nunca fetch (fetch no expone upload.progress)
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
    xhr.send(form); // NUNCA añadir Content-Type — FormData lo pone solo, con el boundary correcto
  });
}
```

**Dominio de Local para `WP_BASE`:** `https://tiritaito-real.local/wp-json` — nunca el de producción (`www.tiritaito.com/blog/wp-json`) para código que corre en Local. Si necesitas el token real o el PIN real, pídelo — no están en esta chuleta a propósito.

---

## 3. Mapa de datos — dónde vive cada cosa

⚠️ Cambió el 26 de julio de 2026: Devocional y Novedades migraron de `wp_options` a ACF. Antes de escribir código nuevo, comprueba aquí primero.

### 3.1 ACF Options Page — "Devocional — Contenido Diario"

| Campo ACF (sin prefijo `tt_`) | Clave API (con prefijo `tt_`) | Nota |
|---|---|---|
| `virgen` | `tt_virgen` | Texto largo |
| `virgen_fecha` | `tt_virgen_fecha` | **Obligatoria** antes de publicar; el PHP valida `YYYY-MM-DD` con regex |
| `brisa` | `tt_brisa` | Texto largo |
| `brisa_autor` | `tt_brisa_autor` | Texto simple |
| `homilia_audio` | `tt_homilia_audio` | URL Media Library |
| `homilia_texto` | `tt_homilia_texto` | Texto largo — ya NO lleva fecha pegada al final (corregido en v2-06) |
| `lenguas_url` | `tt_lenguas_url` | URL Media Library |

⚠️ **El nombre del campo ACF y la clave de la API no coinciden** (uno lleva `tt_`, el otro no) — el snippet PHP hace la traducción. Escribe código nuevo siempre con la clave `tt_`.

⚠️ **Escribe SIEMPRE con `update_field()`, nunca `update_option()`** — Avada Dynamic Content solo lee lo que ACF guarda con su prefijo interno `options_`, no con el nombre plano. Confundir esto deja el campo invisible para Avada sin dar ningún error.

**`tt_tip_1` / `tt_tip_2` — eliminados por decisión de producto.** Si ves esto en algún sitio, es deuda pendiente de retirar, no algo a mantener.

### 3.2 wp_options — lo que se queda igual

| Clave | Tipo | Módulo |
|---|---|---|
| `tt_docx_lectura_url` | URL Media Library | Lecturas semanales |
| `tt_youtube_json_url` | URL (`music-data.json`) | Tiritaito Music |
| `tt_seminarios_json_url` | URL (`Seminarios.json`) | Seminarios |
| `tt_viacrucis_json_url` | URL (JSON) | Vía Crucis |
| `tt_fiesta_dias` | JSON directo, no URL | Fiestas/calendario |

### 3.3 CPT `novedades`

`public => false` (no genera páginas propias) · `show_ui => true` · `show_in_rest => true` · `supports => ['title']`. 6 campos ACF: `tipo` (imagen/video), `media_url` (⚠️ tipo Texto, nunca "Image" de ACF), `texto`, `enlace`, `fecha` (Return Format `Ymd`, el endpoint convierte a `YYYY-MM-DD` en la frontera), `activo` (**no filtra el listado público** — decisión de equipo, Post Cards muestra todas), `titulo` (si llega vacío, autogenera "Novedad " + fecha/hora).

Endpoint dedicado `tiritaito/v1/novedades` — **nunca** `/wp/v2/posts`.

---

## 4. Endpoints REST completos

| Método | Ruta | Auth | Función |
|---|---|---|---|
| GET | `/tiritaito/v1/datos` | Pública | Lee Devocional (ACF) + resto de `wp_options` |
| POST | `/tiritaito/v1/datos` | `X-TT-Token` | Guarda ambos |
| POST | `/tiritaito/v1/subir` | `X-TT-Token` | Sube archivo a Biblioteca de Medios — ⚠️ sin validar tipo MIME ni tamaño máximo |
| GET | `/tiritaito/v1/medios` | `X-TT-Token` | Lista Biblioteca — pide token incluso para leer |
| DELETE | `/tiritaito/v1/medio/{id}` | `X-TT-Token` | Elimina archivo permanente |
| GET | `/tiritaito/v1/novedades` | Pública | Lista todas (activas y ocultas) |
| POST/PUT/DELETE | `/tiritaito/v1/novedades[/{id}]` | `X-TT-Token` | Crea/edita/borra |

❌ **Ya NO existen:** `/tiritaito/v1/entradas`, `/tiritaito/v1/entrada/{id}`, ni el subsistema `biblioteca/v1/*`. 🔲 Sin confirmar si es deliberado o si falta reconstruirlo.

⚠️ **Sin rate limiting** en el backend actual (una versión anterior sí tenía 60 peticiones/hora por IP) — no confirmado si es temporal o definitivo.

---

## 5. Identidad visual — lo que hay que aplicar siempre

```css
:root {
  --tt-red:      #BF4646;  --tt-red-d:    #A33B3B;  --tt-red-bg:   #FDF2F2;
  --tt-txt:      #1d1d1f;  --tt-txt2:     #3a3a3c;  --tt-txt3:     #6e6e73;
  --tt-txt4:     #86868b;  --tt-sep:      #c7c7cc;
  --tt-bg:       #FFFFFF;  --tt-surf:     #FFFFFF;  --tt-surf2:    #F5F5F7;  --tt-surf3: #EBEBF0;
  --tt-green:    #34C759;  --tt-green-bg: #F0FAF4;  --tt-orange:   #FF9500;  --tt-alert: #FF3B30;
  --tt-r:        25px;     --tt-r-sm:     14px;     --tt-r-xs:     8px;
  --tt-nav:      68px;
}
```

⚠️ **10px:** apareció en Toggles y en Forms, por decisión de Hna C, sin confirmar todavía si es un cuarto token real o dos excepciones puntuales. **No lo uses en nada nuevo fuera de esos dos sin preguntar primero.**

**Tipografía:** "Yeah Papa" en títulos (`h1` 32px / `h2` 24px / `h3` 20px), "Helvetica Neue" en cuerpo (15px). ⚠️ "Yeah Papa" necesita un tamaño en px notablemente mayor que Helvetica Neue para el mismo peso visual — confirmado varias veces (ej.: el título de Toggles se ajustó de 16px a 30px). Calíbralo así desde el principio, no lo descubras cada vez.

**Breakpoints del código** (snippets propios): `1024px / 768px / 480px`.

---

## 6. Valores reales de Avada Global Options — verificado contra el export

*Fuente: `03-guias-practicas/exports/avada-global-options.json` (última actualización conocida: 14 agosto 2026). Si algo aquí no coincide con lo que ves en Local, confía en el export real, no en esta chuleta — pídelo si lo necesitas.*

| Ajuste | Valor real |
|---|---|
| Layout / Site Width | Wide, **1200px** |
| Colores 1–8 (orden confirmado en Local) | 1=`--tt-bg` #FFFFFF · 2=`--tt-surf2` #F5F5F7 · 3=`--tt-sep` #c7c7cc · 4=`--tt-txt4` #86868b · 5=`--tt-red` #BF4646 (llamado "Rojo Director" en el panel) · 6=`--tt-red-d` #A33B3B · 7=`--tt-txt2` #3a3a3c · 8=`--tt-txt` #1d1d1f |
| Colores 9–13 | 🔲 **Sin cargar todavía** (`--tt-red-bg`, `--tt-txt3`, `--tt-green`, `--tt-orange`, `--tt-alert`) — usa hex directo con nota de qué variable sustituye |
| Breakpoints responsive | ~**1024px** (Medium) y **480px** (Small) — el `768px` del código es convención propia, no existe como tal en el panel de Avada |
| Responsive Typography | Sensitivity **0.30**, Factor **1.50** — el texto SÍ se reduce solo en pantallas pequeñas |
| Radio de botón | El export dice **10px** en las 4 esquinas — ⚠️ **contradice** la nota de `CATALOGO_ELEMENTOS_AVADA.md` de que se corrigió a 25px. No está resuelto cuál manda; verifícalo en el panel real antes de asumir ninguno de los dos |
| Lightbox | Skin **Metro White**, opacidad **0.90**, flechas On, deeplinking On, autoplay Off |
| Rendimiento | Video Facade **On**, Offscreen Rendering **On**, Font Face Rendering **Swap**, fuentes **no** en CDN de Google (⚠️ el export dice `gfonts_load_method: cdn` — contradice esto último; no lo des por hecho sin comprobar) |
| Portfolio | ❌ Desactivado, confirmado — no lo reactives sin una decisión de equipo |
| Custom CSS global | Ya NO está vacío — contiene el radio de 10px de los Toggles |

---

## 7. Regla de oro — nunca el campo "Clase CSS"

**Desde el 6 de septiembre de 2026, prohibido usar el campo "Clase CSS" (pestaña Diseño) de cualquier elemento, columna o container.** El panel global Custom CSS de Avada sigue permitido mientras no dé problemas.

Antes de pedir código para un efecto visual, mira si ya es nativo:

| Necesito... | Dónde vive, sin código |
|---|---|
| Bordes redondeados, sombra | Container/Columna → Diseño/Extras → Border Radius, Box Shadow |
| Degradado de fondo | Container/Columna → Fondo → Degradado |
| Cristal esmerilado (desenfoque) | Container/Columna (y columnas anidadas) → Extras → Filtros de Fondo — 🔲 confirmar que la versión de Avada del Local ya lo trae |
| Degradado en el TEXTO | Elemento Título (Gradient Font Color) o elemento Highlight — nunca el Bloque de Texto normal |
| Hover de botón | El elemento Botón trae 10 transiciones nativas de fábrica |
| Un color distinto solo al pasar el ratón | El círculo junto al selector de color activa el estado "Hover" — patrón repetido en casi todos los elementos |
| Alineación flexible de columnas | Container → General → Row/Column Alignment/Justification (Flexbox nativo) |

Si de verdad no está tras mirar todas las pestañas del elemento (no solo "Diseño"): se escala a Carlitos (Proyecto 9), nunca se resuelve con Clase CSS.

---

## 8. Árbol de decisión — antes de escribir cualquier snippet

```
1. ¿Lo resuelve un elemento NATIVO de Avada, solo o con ACF (Dynamic Content)?
   → SÍ: úsalo. Cero código que mantener. ES LA OPCIÓN PREFERIDA.
   → NO: sigue.
2. ¿Ya existe un snippet global de Tiritaito que hace esto o algo parecido?
   → SÍ: reutilízalo o amplíalo.
   → NO: sigue.
3. ¿Se usa en más de una página?
   NO → Code Block de la entrada en Avada Live.
   SÍ → ¿Contenido EXACTAMENTE igual en todas partes?
          SÍ → Avada Library: elemento GLOBAL.
          NO → ¿Lo mantiene Hna C sin código? → Avada Library: GUARDADO (no-global).
               ¿Lo mantiene Hno A con lógica de servidor? → shortcode
               parametrizable (patrón [tt_podcast]) — ÚLTIMO RECURSO.
```

**Distinciones que ya se han confundido dos veces — no las repitas:**
- **Slideshows** (Options) = varias imágenes DENTRO de una misma entrada. **Post Slider** (Builder Element) = rotar ENTRE entradas distintas (ej. los 9 santos). No son lo mismo.
- **"Sliding Bar"** es dos cosas: un panel legacy de Global Options (a desactivar, como Portfolio) y un TIPO de Off-Canvas moderno (el que sí se usa para el menú móvil).
- **Flyout Menu** clásico = legacy. El método actual es el **Off Canvas Builder**. Ninguno de los dos resuelve nativamente submenús desplegables.

⚠️ Existe ya un Off-Canvas "Menu Movil" en Local, pero con Conditions desactivadas — no está confirmado si está diseñado por dentro y probado.

---

## 9. Trampas técnicas que más cuestan si se repiten

| Trampa | Qué pasa |
|---|---|
| `has_shortcode()` + Avada | Falso negativo — Avada codifica en Base64. El CSS de módulos va en `wp_head` siempre, incondicional |
| `fetch` para subidas | No expone `upload.progress` — usar XHR |
| `Content-Type` con FormData | Nunca lo añadas a mano — rompe el boundary |
| Snippets PHP duplicados | Mismo nombre de función en dos snippets → fatal "Cannot redeclare" |
| ACF Options Page + `update_option()` | Invisible para Dynamic Content — usa siempre `update_field()` |
| Confundir Slideshows con Post Slider | Ver Sección 8 |
| Atributo `seasons` de `[tt_podcast]` | Debe ser JSON válido con comillas dobles por dentro y comillas simples envolviendo todo (`seasons='{"1":"Nombre"}'`) — comillas simples anidadas rompen el parseo **en silencio**, sin error |
| Subida de archivos sin validar | `tt_subir_archivo()` no comprueba tipo MIME ni tamaño — una versión anterior sí lo hacía |

---

## 10. Convenciones de código

- Snippets tipo **HTML** con `<style>` + `<script>` juntos (Code Snippets free no separa CSS/JS)
- Prefijo `tt-` + BEM: `tt-modulo__elemento--modificador`
- Variables CSS siempre `var(--tt-*)`, nunca hex sueltos
- Patrón JS obligatorio:

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

---

## 11. Cómo usar esta cuenta de forma eficiente

- **Esfuerzo (junto al modelo): Bajo o Medio** para consultas puntuales. Sube a Alto solo si de verdad es una tarea difícil — Máximo casi nunca hace falta para "dame el valor de X" y agota el uso mucho más rápido.
- **Un chat nuevo por consulta**, no reciclar un hilo largo — cada turno reenvía todo el historial.
- Si necesitas construir algo grande, verificar un contrato de endpoint que no esté aquí, o leer el HTML/PHP real de la app: pásate a **Proyecto 10 — Consulta Técnica Profunda**.

---

*Para la mayor gloria de Dios · tiritaito.com*

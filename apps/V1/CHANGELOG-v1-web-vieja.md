# Changelog — Tiritaito for Creators
## Rama v1-xx · conectada a la Web Vieja (producción, Avada, tiritaito.com/blog)
*Un solo archivo por rama · entrada nueva arriba · sin repetición entre versiones*

---

## ⚠️ Nota de rama — Julio 2026

Esta rama (**v1-xx**) queda en **modo mantenimiento**, igual que la propia web
vieja: solo recibe correcciones críticas, sin funcionalidad nueva. Toda la
funcionalidad nueva a partir de julio 2026 se construye en la rama paralela
**v2-xx**, conectada a la Web Nueva / entorno Local — ver `CHANGELOG-v2-web-nueva.md`.

Las dos ramas comparten v1-07 como última base común. A partir de ahí,
evolucionan por separado: un cambio en v2-xx no implica que exista aquí, y
viceversa.

---

## v1-11 — 2026-09-02

✅ Generadores YouTube (Música/Vía Crucis/Seminarios): el catch de
   generarYPublicar() ya no oculta el error real de la API de YouTube
   detrás del toast genérico "Sin conexión...". Ahora distingue: API
   Key inválida (mensaje ya existente), falta de API Key configurada,
   mensaje real de la API de Google ("❌ Error de YouTube: <mensaje>"
   — cubre playlistNotFound, quotaExceeded, key inválida, etc.), y
   error de red genuino del navegador (sigue cayendo en el toast
   genérico, sin cambios). mensajeError() no se tocó — el cambio
   está aislado a este catch, sin efecto en otros módulos
⚠️ Verificación: node --check sobre el bloque <script> extraído (sin
   errores). Lógica de clasificación de errores probada contra 8
   casos reales (formatos exactos de wpFetch, subirArchivo y
   getPlaylistItems) — confirmado que cada mensaje cae en la rama
   correcta, incluida la exclusión explícita de errores nativos de
   red ("Failed to fetch"/NetworkError) para que no se muestren como
   si fueran un error de YouTube
🔲 Pendiente: con este cambio, la próxima vez que "Todas" u otra
   playlist falle al generar, el toast mostrará el motivo real —
   usar ese mensaje para diagnosticar de raíz por qué viene fallando
   (playlist privada real, cuota agotada, u otra causa) en vez de
   seguir descartando hipótesis a ciegas
▪️ No tocado en esta versión: Devocional, Novedades, Biblioteca de
   Medios, Lecturas, Lectura del santo, PIN, navegación — sin cambios

---

## v1-10 · Agosto 2026 — Corrección de la instalación PWA + botón de instalar
 
- ⚠️ **Bug corregido — la app no se ofrecía como instalable, solo como acceso directo.** Causa raíz: `manifest.json` y `sw.js` de la entrega v1-09 apuntaban a una ruta de servidor incorrecta. Se asumió `TiritaitoforCreators/App/` y el nombre `tiritaito-creators-v1-09.html`; la ruta real, confirmada contra el servidor en vivo, es `Tiritaito for Creators/App/` (con espacios, codificados como `%20` en la URL) y el nombre de archivo es **fijo**: `tiritaito-creators-v1-01.html`, sobrescrito en cada entrega — el navegador no encontraba el manifest (404 real, verificado), así que nunca llegaba a considerar la app instalable
- ✅ **Confirmado en producción (agosto 2026): V1 SÍ usa nombre de archivo fijo**, igual que V2 — contradice lo que documenta `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 1 y 2 ("V1 — un archivo nuevo por versión, como siempre"). Verificado leyendo el HTML real servido en `tiritaito-creators-v1-01.html`, que mostraba el footer "v1-08" (versión anterior a esta). ⚠️ **Pendiente de decisión de equipo**: corregir esa sección del documento para reflejar la práctica real, tal como ya se hizo para V2 el 26 de julio — no se toca aquí porque ese documento es propiedad de Hno A y excede el ámbito de esta sesión
- **Consecuencia práctica para todas las entregas futuras de V1 con PWA**: `manifest.json` y `sw.js` ya no necesitan cambiar su `start_url`/`scope`/rutas de assets en cada versión nueva, porque el nombre de archivo del HTML nunca cambia. Lo único que hay que actualizar en cada entrega es `CACHE_NAME` dentro de `sw.js` (ver más abajo)
- `manifest.json` corregido: `start_url` y `scope` ahora apuntan a la ruta real con espacios codificados
- `sw.js` corregido: ruta de `tiritaito-creators-v1-01.html` en `ASSETS_ESTATICOS`, y `CACHE_NAME` incrementado a `tt-creators-v1-10` (necesario para que los móviles que ya intentaron cachear la versión rota descarten esa caché fallida)
- **Nuevo: botón "Instalar app en este móvil"**, en la pestaña Ayuda, arriba de las preguntas frecuentes
  - Aparece únicamente cuando el navegador confirma que la app es instalable (evento `beforeinstallprompt` de Chrome/Android) y la app no está ya instalada
  - Al pulsarlo, lanza el diálogo nativo de instalación del navegador
  - Desaparece automáticamente en cuanto la instalación se completa (`appinstalled`), y no vuelve a aparecer en esa sesión
  - En iPhone/Safari el botón no aparece — Apple no dispara ese evento; la FAQ actualizada explica el camino manual ("Compartir → Añadir a pantalla de inicio") para ese caso
- FAQ de instalación actualizada para explicar el nuevo botón y diferenciar el comportamiento Android/iPhone
- ▪️ No tocado en esta versión: Devocional, Novedades, Biblioteca de Medios, Recursos, PIN, navegación — sin cambios funcionales de fondo
### Lección para el protocolo de sesión, de cara al futuro
 
Antes de dar por buena cualquier ruta de servidor que no esté ya verificada en un documento de la base de conocimiento, hay que confirmarla contra el servidor real (`web_fetch`) en lugar de asumir el texto que se transcribe en el chat — en este caso el espacio en el nombre de la carpeta se perdió al repetir la ruta de memoria en un mensaje anterior, y ese único carácter fue la causa completa del fallo.
 

---

## v1-08 · Agosto 2026 — Módulo Novedades para el ticker de la home

- **Nueva pestaña Novedades** en la navegación inferior (2.º tab, icono de estrella)
- Guarda un array JSON en `tt_novedades` (wp_options) — mismo patrón que `tt_fiesta_dias`, no un CPT (eso es solo la rama V2/Local)
- Cada novedad: tipo (imagen/vídeo), archivo subido desde la galería del móvil a la Biblioteca de Medios, título, texto, enlace opcional, fecha editable, visibilidad (activo/oculta), orden manual
- **Borrado automático del archivo asociado**: al editar una novedad sustituyendo su foto/vídeo, o al eliminarla por completo, la app llama a `DELETE /tiritaito/v1/medio/{id}` sobre el `media_id` que deja de usarse — no quedan archivos huérfanos en la Biblioteca de Medios. Si ese borrado falla, la novedad se guarda/elimina igualmente y se avisa con un aviso, sin bloquear la operación principal
- **Reordenación manual por gestos táctiles**, corregida durante esta sesión — solo reordena entre sí las novedades de la app, no afecta a nada del propio Code Block del ticker
- Nueva entrada de FAQ en Ayuda explicando el módulo
- ✅ **Confirmado — Web Vieja**: `tt_novedades` ya está en la whitelist del snippet PHP (`tt_opciones_permitidas()`), aplicado por Hno A durante esta sesión. Verificado directamente contra `GET /tiritaito/v1/datos`, que ya devuelve la clave con datos reales guardados
- ⚠️ **Pendiente Tiritaito Web**: transformar el ticker de noticias de la home (Code Block Avada) para que combine sus 4 entradas hardcodeadas actuales con las novedades publicadas desde la app, leídas vía `GET /tiritaito/v1/datos` — prompt ya entregado en esta sesión, pendiente de aplicar

### Bug corregido durante la sesión — reordenamiento no guardaba en móvil

- **Síntoma reportado**: al arrastrar una novedad para reordenarla en el móvil, el elemento se elevaba visualmente pero al soltar volvía a su posición original, con una sensación de movimiento trabado y sin suavidad
- **Diagnóstico**: la implementación inicial usaba la API HTML5 de arrastre (`draggable="true"` + `dragstart`/`dragover`/`drop`), que no dispara de forma fiable en navegadores móviles táctiles (Safari iOS, Chrome Android) — el `dragstart` sí llegaba a activarse parcialmente (de ahí la elevación visual), pero el `drop` nunca se completaba, así que el nuevo orden nunca llegaba a guardarse
- **Corrección**: sustituido el mecanismo completo por gestos táctiles nativos (`touchstart`/`touchmove`/`touchend`) sobre el asa de arrastre, con reordenamiento en vivo de los elementos vecinos según la posición del dedo, y guardado único al soltar
- ⚠️ **Sin confirmación final del usuario en esta sesión**: aunque el PHP ya está verificado como correcto y activo (ver arriba), no hay una prueba explícita del usuario, tras ese cambio de PHP, confirmando que el gesto de reordenar guarda sin el aviso de rechazo — pendiente de confirmar en la próxima sesión o interacción

---

## v1-07 · Julio 2026 — Fix: persistencia de "Lectura del santo"

- **Bug corregido**: `S.fiestaDias` se reiniciaba desde `S.opciones` cada vez que se pintaba la pestaña Recursos (incluido al cambiar de pestaña y volver), descartando cualquier día marcado sin guardar. Ahora esa lectura solo ocurre una vez, al arrancar la app (`iniciarApp`)
- **Aviso persistente**: si el servidor rechaza `tt_fiesta_dias` (whitelist PHP pendiente), el estado de la tarjeta ya no depende solo del toast — queda un mensaje fijo `⚠️ El servidor todavía no guarda esta clave…` hasta que se resuelva y se vuelva a guardar con éxito
- Nuevo estado `S.fiestaBackendPendiente`
- **Diagnóstico confirmado**: los síntomas reportados (el sábado con "santo" activado no se ve en la web + los días activados se desactivan al recargar la app) son 100% compatibles con que **`tt_fiesta_dias` sigue sin estar en la whitelist del snippet PHP** (`tt_opciones_permitidas()`), pendiente desde v1-06. Mientras falte esa línea: el POST se rechaza (no se guarda) y el GET nunca devuelve la clave (ni a la app ni al widget) — por eso ambos síntomas ocurren a la vez

---

## v1-06 · Julio 2026 — Configuración de "lectura del santo" para el widget de Misa

- Nueva tarjeta **Lectura del santo** en Recursos (entre Lecturas y Generadores YouTube)
  - 7 chips (Lunes–Domingo) para marcar en qué días la web debe mostrar la lectura propia del santo (Sección B del DOCX) en vez de la ordinaria
  - Botón "Guardar configuración" → `POST /tiritaito/v1/datos` con `tt_fiesta_dias` (días separados por coma, ej. `"1,4"`)
  - Detecta `rechazadas[]` en la respuesta y avisa si el servidor aún no acepta la clave (pendiente whitelist, ver abajo)
- Estado global: añadido `S.fiestaDias`
- **Widget de lecturas de Misa (Tiritaito Web, codeblock Avada)**: preparado para dejar de tener `DOCX_URL` y `FIESTA` hardcodeados en el propio código
  - Nueva función `fetchConfig()`: lee `tt_docx_lectura_url` y `tt_fiesta_dias` desde `GET /tiritaito/v1/datos?token=TT_READ_TOKEN` en cada carga de página
  - `init()` ahora depende de esa configuración remota — ya no hace falta editar el codeblock manualmente cada vez que cambian las lecturas o los días de fiesta
  - Entregado como archivo aparte (`widget-lecturas-misa.html`) para pegar en el codeblock de Avada
- ⚠️ **Pendiente Tiritaito Web**: añadir `'tt_fiesta_dias' => 'textarea'` a la whitelist del Snippet PHP (`tt_opciones_permitidas()`) — igual que el pendiente ya existente de `tt_viacrucis_json_url`. Hasta entonces, guardar la configuración desde la app mostrará el aviso de "no aceptado por el servidor"

---

## v1-05 · Junio 2026 — Recursos: Lecturas y Generadores YouTube

- Nueva pestaña **Recursos** en la navegación inferior (4.º tab, icono libro)
- **Módulo 3 — Lecturas de la semana**: sube DOCX, muestra barra de progreso, guarda URL en `tt_docx_lectura_url`, muestra enlace al archivo publicado
- **Módulo 4 — Generadores YouTube**: tres sub-tabs (Música, Vía Crucis, Seminarios)
  - Gestión de playlists por tipo: añadir nombre + ID, eliminar, persistido en `localStorage`
  - Genera JSON en el navegador, sube a la Biblioteca de Medios, guarda URL en la `wp_option` correspondiente
  - Música y Vía Crucis: solo vídeos públicos, ordenación inteligente por título
  - Seminarios: públicos + no listados, extrae año/mes de la descripción, ordena por año desc y mes asc
  - Detecta si el servidor rechaza la clave (`rechazadas[]`) y avisa sin lanzar error falso
  - Archivos generados: `music-data.json`, `viacrucis-data.json`, `Seminarios.json`
- **API Key de YouTube**: guardada en `localStorage`, oculta, con botón de guardar y estado visual
- CSS: `.tt-playlist-item`, `.tt-playlist-name`, `.tt-playlist-id`, `.tt-playlist-del`
- Estado global: añadido `S.ytSubTab`
- ⚠️ **Pendiente Tiritaito Web**: añadir `'tt_viacrucis_json_url' => 'url'` a la whitelist del Snippet PHP para que Vía Crucis guarde correctamente la URL

---

## v1-04 · Junio 2026 — Arquitectura proxy-token

**Cambio de arquitectura**: se elimina Application Password por completo. La app ya no depende del usuario `makecom` para autenticarse — usa un token propio.

- Eliminadas constantes `WP_USER`, `WP_PASS`, `AUTH`, `TT_READ_TOKEN` del HTML
- Añadida constante `TT_WRITE_TOKEN` — único token necesario, sirve para leer y escribir
- `wpFetch()`: cabecera `Authorization: Basic` → `X-TT-Token: TT_WRITE_TOKEN`
- `subirArchivo()`: endpoint `/wp/v2/media` → `/tiritaito/v1/subir`, sin `Content-Disposition`
- `leerOpciones()`: ahora usa `TT_WRITE_TOKEN` en lugar de `TT_READ_TOKEN`
- `cargarMedios()`: endpoint `/wp/v2/media` → `/tiritaito/v1/medios`
- `eliminarMedio()`: endpoint `/wp/v2/media/{id}?force=true` → `/tiritaito/v1/medio/{id}`
- Snippet PHP activo: "TT Creators + Biblioteca — Endpoint central v2" (reemplaza al anterior)
- Nuevas rutas PHP: `/tiritaito/v1/subir`, `/tiritaito/v1/medios`, `/tiritaito/v1/medio/{id}`
- Rutas preparadas para Módulo 5: `GET/POST /tiritaito/v1/entradas`, `PUT/DELETE /tiritaito/v1/entrada/{id}`
- Seguridad PHP: rate limiting 60 ops/hora/IP · validación MIME real por contenido · límite 200 MB · barrera Avada con HTTP 422

---

## v1-03 · Junio 2026 — Application Password regenerada

- Regenerada y sustituida `WP_PASS` — la contraseña anterior nunca llegó a autenticar (confirmado por columna "Último uso: —" en WordPress)
- Descartado problema de hosting: Make.com se autenticaba correctamente con la misma instalación

---

## v1-02 · Junio 2026 — Token de lectura

- `leerOpciones()` ahora envía `X-TT-Token` en el `GET /tiritaito/v1/datos` (el endpoint ya lo exigía y respondía 403 sin él)
- Añadida constante `TT_READ_TOKEN` con el valor real del servidor

---

## v1-01 · Junio 2026 — Primera versión

- Estructura base: pantalla de PIN estilo iOS (4 dígitos, teclado con letras), navegación inferior (Hoy / Biblioteca / Ayuda)
- **Módulo 1 — Panel Devocional** completo:
  - Mensaje de la Virgen (`tt_virgen`): fecha + texto
  - Hora de la Brisa (`tt_brisa`): texto + autor con `—`
  - Homilía audio (`tt_homilia_audio`): subida XHR con barra de progreso
  - Homilía texto (`tt_homilia_texto`): texto largo + fecha al final
  - Lenguas de Hoy (`tt_lenguas_url`): subida de vídeo con progreso
  - Tip del día (`tt_tip_1`, `tt_tip_2`): dos selectores, el segundo opcional con botón "Quitar"
  - Estado actual publicado visible en cada tarjeta
- **Módulo 2 — Biblioteca de Medios**:
  - Cuadrícula de miniaturas con paginación ("Cargar más")
  - Filtros por tipo y búsqueda por nombre con debounce
  - Vista de detalle en modal: preview, URL permanente, botón "Copiar URL"
  - Subida con barra de progreso XHR y botón flotante "+"
  - Eliminación con confirmación explícita
- **Sección Ayuda** con acordeón de preguntas frecuentes
- Sistema de toasts, confirmaciones (`confirmarEliminar`), mensajes de error en lenguaje humano
- Identidad visual completa: rojo `#BF4646`, blanco puro, radius 25px, tipografía Yeah Papa + Helvetica Neue, animaciones spring iOS, iconos SVG inline

---

*Para la mayor gloria de Dios · tiritaito.com*

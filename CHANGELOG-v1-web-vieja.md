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

# Changelog — Tiritaito for Creators
## Rama v2-xx · conectada a la Web Nueva (entorno Local, Hno A)
*Un solo archivo por rama · entrada nueva arriba · sin repetición entre versiones*

---

## ⚠️ Nota de rama — Julio 2026

Esta rama (**v2-xx**) es la que recibe funcionalidad nueva a partir de julio
2026. Arranca en **v2-01** partiendo del mismo archivo base que la última
versión de la rama v1-xx (**v1-07**), que queda congelada en modo
mantenimiento en su propio changelog — ver `CHANGELOG-v1-web-vieja.md`.

A partir de aquí, las dos ramas evolucionan por separado: un cambio en v1-xx
no implica que exista aquí, y viceversa.

---

## v2-02 · Julio 2026 — Conexión al entorno Local

- **`TT_WRITE_TOKEN`** actualizado al token propio del Local (`36374494995SFGJKF453534`) — v2-01 arrastraba por error el token de la web vieja al copiarse desde v1-07
- **`WP_BASE`** actualizado a `http://tiritaito-real.local/wp-json` — antes apuntaba a producción (`tiritaito.com/blog`)
- **`LOGO_URL`** actualizado a la imagen ya subida en el Local
- ⚠️ **Sin confirmar por Hno A todavía**: el valor de `WP_BASE` se dedujo del dominio de la URL del logo (mismo dominio, esquema `http` sin `https`, sin subcarpeta `/blog`), no de una verificación directa contra `/wp-json`. Pendiente que Hno A confirme pegando esa URL en el navegador y viendo que devuelve JSON del sitio, no un error
- No usa `TT_READ_TOKEN` ni `BIBLIOTECA_TOKEN` — el primero es para los widgets públicos del lado del servidor, el segundo parece pertenecer a una app distinta ("Biblioteca"), ninguno de los dos es responsabilidad de esta app

---

## v2-01 · Julio 2026 — Módulo Novedades

- **Nueva pestaña "Novedades"** en la navegación inferior, entre "Hoy" y
  "Biblioteca"
- CRUD completo de novedades: crear, editar, eliminar (con confirmación explícita,
  mismo patrón que la Biblioteca de Medios)
- Cada novedad guarda: `tipo` (imagen/vídeo, detectado automáticamente por el MIME
  del archivo elegido — no hace falta seleccionarlo a mano), `media_url`, `texto`
  (opcional), `enlace` (opcional), `fecha` (editable, por defecto la fecha de hoy),
  `activo` (visible/oculta sin necesidad de borrar)
- Subida de archivo reutiliza `subirArchivo()` (mismo XHR con barra de progreso que
  ya usan Homilía-audio y Lenguas de Hoy)
- **Contrato de datos**: todas las novedades viven en una sola clave de
  `wp_options`, `tt_novedades`, como array JSON — se lee y se escribe entera con
  `guardarOpciones({ tt_novedades: JSON.stringify(...) })`, exactamente el mismo
  patrón que `tt_fiesta_dias`. Sin rutas REST propias por elemento ni autenticación
  distinta a `X-TT-Token`
- Nuevas funciones: `parseNovedades()`, `pintarNovedades()`,
  `renderPreviewNovedad()`, `abrirModalNovedad()`, `guardarNovedad()`,
  `confirmarEliminarNovedad()`, `eliminarNovedad()`, `toggleNovedadActivo()`
- Estado global: añadido `S.novedades`, cargado una sola vez en `iniciarApp()`
  (misma disciplina de inicialización que `S.fiestaDias`)
- CSS: `.tt-novedad-item`, `.tt-novedad-thumb`, `.tt-novedad-info`,
  `.tt-novedad-texto`, `.tt-novedad-meta`, `.tt-novedad-badge`,
  `.tt-novedad-actions`, `.tt-novedad-actedit`, `.tt-novedad-actdel`
- ⚠️ **Pendiente Web Nueva (Hno A)**: añadir `tt_novedades` a la whitelist del
  snippet PHP (`tt_opciones_permitidas()`) del Local — sin esa línea, el `POST` se
  rechazará igual que ya pasa con `tt_fiesta_dias` y `tt_viacrucis_json_url`.
  También pendiente: el widget/shortcode que lea `tt_novedades` y lo pinte en la
  home (hero + tira horizontal) — no es tarea de este proyecto, la construye Hno A
  en su cuenta de Web Nueva

---

*Para la mayor gloria de Dios · tiritaito.com*

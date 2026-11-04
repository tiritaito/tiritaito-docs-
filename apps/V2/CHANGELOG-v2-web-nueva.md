## v2-07 — 2026-11-04

✅ Novedades: ocultar de verdad. `activo:false` en el PUT pasa la entrada a
   borrador (deja de salir en la web); `true` la vuelve a publicar. La app
   (con token) recibe publicadas + ocultas; la lectura pública sigue
   devolviendo solo las publicadas. `activo` en la respuesta refleja el
   estado real de la entrada, no el campo ACF
✅ Novedades: dos desplegables nuevos en el formulario, Molde y Categoría.
   Sus opciones se leen de ACF vía `GET /tiritaito/v1/novedades/opciones`:
   ni la app ni el PHP llevan las listas escritas, ni claves `field_…`.
   Novedad nueva → molde por defecto (`molde_defecto`). Editar → se precargan
   los valores guardados. Sin opciones cargadas no se abre el formulario
✅ Lista de Novedades: las ocultas se ven atenuadas con la etiqueta «Oculta»
   y cada tarjeta muestra el nombre de su molde
✅ `mensajeError()`: ahora lee el código HTTP real del principio del mensaje.
   Antes un 400 caía en «Sin conexión» y `includes('5')` daba falsos «error
   de servidor» si el cuerpo contenía un 5. Un 400 por molde o categoría
   inexistente avisa en lenguaje humano
✅ PHP (snippet central): ruta nueva `GET /novedades/opciones`; `molde` y
   `categoria` en GET/POST/PUT; validación contra las opciones de ACF
   (400 sin escribir nada si no valen); molde por defecto si llega vacío;
   `activo` solo se toca en el PUT si la app lo manda; purga de LiteSpeed
   tras cada cambio; los Select de ACF se leen siempre como valor aunque su
   Formato de retorno sea «Matriz»
✅ PHP: corregidos dos fallos del parche original. (1) Decía «sustituir la
   Sección 7» pero en el snippet real esa es Biblioteca de Medios; Novedades
   es la Sección 8. (2) Con un cuerpo no JSON, `$body` era `null` y
   `array_key_exists()` daba error fatal en PHP 8: ahora `tt_novedades_body()`
   devuelve siempre un array
▪️ No tocado: Devocional, Biblioteca de Medios, Recursos, PIN, navegación,
   `subirArchivo()`
⚠️ Verificado en el sandbox sobre los archivos reales, NO en Local: `php -l` (anteponiendo `<?php`, porque el snippet no lo lleva) y 14 pruebas de lógica PHP
   con un WordPress simulado; `node --check` y 16 pruebas de las funciones
   JS nuevas. Sin probar contra el WordPress real: lectura de los campos ACF
   `molde` y `categoria` (deben ser tipo Select con esos nombres exactos),
   el respaldo por `get_field_object`, y la purga de LiteSpeed
🔲 Pendiente de Proyecto 3 en Local: pruebas curl 1–5 del prompt, backfill
   del Bloque C y los 4 criterios de «terminado» (en especial añadir un molde
   en ACF y comprobar que aparece en la app sin tocar código)
🔲 Pendiente (sin cambios desde v2-03): bug de subida en V2, sigue sin
   diagnosticar

## v2-06 — 2026-07-30

✅ Novedades: añadido input de "Título (opcional)" al formulario (crear
   y editar) — `guardarNovedad()` ahora incluye `titulo` en el payload
   enviado al backend. Confirmado contra el PHP real
   (`snippet-tt-creators-endpoint-central.php`) que `tt_novedades_crear()`
   y `tt_novedades_editar()` ya leían este campo desde antes; solo
   faltaba que la app lo mandara. Si `titulo` llega vacío, el PHP sigue
   autogenerando "Novedad " + fecha/hora como título del post — sin
   cambios en ese comportamiento
✅ Comentario de contrato de datos de Novedades (encima del código del
   módulo) actualizado: ya no dice "asumido, sin verificar contra el
   PHP real" — se revisó línea por línea antes de este cambio y el
   contrato queda confirmado, incluyendo `titulo` en los ejemplos de
   GET/POST/PUT
✅ Homilía — texto: quitado el campo de fecha del formulario y dejó de
   anexarse al final del texto publicado. `guardarHomiliaTexto()`
   simplificado — ya no lee `homilia-texto-fecha` ni construye el
   valor concatenado. La fecha real ya es la de publicación en
   WordPress, no hacía falta pedirla ni guardarla dentro del texto
✅ Retirada por completo la UI de "Tip del día": la tarjeta HTML (dos
   tips, inputs de archivo, botones "Quitar publicado"), las entradas
   `tip1`/`tip2` del mapa de `pintarEstadoDevocional()`, y los dos
   `addEventListener` de `tip1-input`/`tip2-input`. Cero referencias a
   `tt_tip_1`/`tt_tip_2` en todo el archivo (verificado por grep). La
   FAQ de Ayuda que mencionaba "tips" como parte de lo que se sube
   desde "Hoy" también se corrigió para no referenciar algo que ya no
   existe
▪️ No tocado en esta versión: Virgen, Brisa, Homilía-audio, Lenguas,
   Biblioteca de Medios, Recursos (Lecturas DOCX, Lectura del santo,
   Generadores YouTube), PIN, navegación — sin cambios
⚠️ Verificación de sintaxis y estructura realizada antes de entregar:
   `node --check` sobre el bloque `<script>` extraído (sin errores),
   audit Python de `getElementById` vs `id=` (sin referencias
   colgantes a tip1/tip2; los 11 "faltantes" restantes son IDs
   generados dinámicamente por plantillas con interpolación —
   `tt-modal-*`, `yt-${tipo}-*` — comportamiento esperado y ya
   documentado como seguro de ignorar), y conteo de balance de
   `<div>`/`<section>` en la zona donde se eliminó la tarjeta de Tip
   del día (44 aperturas / 44 cierres en Devocional, 5 tarjetas
   `tt-card` tras la retirada, tal como se esperaba)
🔲 Pendiente (no aplicado en esta versión, fuera del alcance de las
   tres mejoras pedidas): el bug de subida en V2 reportado en el
   changelog de v2-03 sigue sin diagnosticar — no se ha tocado nada
   relacionado con `subirArchivo()` en esta sesión


## v2-05 — 2026-07-26

✅ Mensaje de la Virgen y Hora de la Brisa: fecha y autor pasan a ser
   campos separados (tt_virgen_fecha, tt_brisa_autor) en vez de venir
   pegados dentro del texto — permite que Avada los muestre con
   estilo propio vía Dynamic Content
✅ Fecha del mensaje de la Virgen pasa a ser obligatoria antes de
   publicar (antes era opcional)
▪️ Sin cambios en el resto de módulos

---

## v2-04 — fecha exacta sin confirmar

⚠️ **Entrada reconstruida, no registrada en su momento.** Esta versión
   existió como paso de trabajo entre v2-03 y v2-05, pero su changelog
   nunca se escribió — Carlitos no recuerda con precisión qué cambió
   en ella, y el HTML que se usó como base para construir v2-05 resultó
   ser, según confirmación directa de Carlitos, idéntico al de v2-03
   salvo el número en el footer. No hay evidencia de código de ningún
   cambio funcional real entre v2-03 y v2-04.
🔲 Si Carlitos recuerda o encuentra más adelante qué se cambió
   realmente en v2-04, esta entrada debe corregirse con el detalle real
   — lo de arriba es un registro honesto de lo que se sabe ahora, no
   una afirmación de que no hubo cambios

---

## v2-03 — 2026-07-25

✅ Migrado el módulo Novedades: de un único blob JSON en `wp_options`
   (`tt_novedades`) a entradas reales de WordPress, Post Type propio
   "novedades" (CPT + ACF), vía endpoint dedicado `tiritaito/v1/novedades`
✅ `cargarNovedades()` (nueva): sustituye a `parseNovedades()` — GET al
   endpoint en vez de leer `S.opciones.tt_novedades`
✅ `guardarNovedad()`: ya no reescribe el array completo — POST para
   crear, PUT para editar, una novedad a la vez
✅ `eliminarNovedad()`: DELETE por id en vez de reescribir el blob entero
✅ El `id` de cada novedad ya no lo genera el navegador (antes
   `nov_<timestamp>_<random>`) — ahora es el ID real del post,
   asignado por el servidor al crear
✅ `iniciarApp()` actualizada: `await cargarNovedades()` en el mismo
   punto de inicialización única que ya usaba `S.fiestaDias`, respetando
   la regla de "solo se lee en iniciarApp, nunca en funciones de pintado
   de pestaña" (bug ya corregido una vez en v1-07, no se repite aquí)
✅ Contrato de datos documentado en comentario dentro del propio HTML,
   encima del código del módulo: qué manda y espera cada verbo
   (GET/POST/PUT/DELETE) contra `tiritaito/v1/novedades`
⚠️ Contrato de datos NO verificado contra el PHP real de Hno A — está
   escrito como lo que la app asume, a falta de la primera prueba real
   contra Local. Si el PHP devuelve una forma distinta (otros nombres
   de campo, respuesta envuelta en `{data:[...]}`, etc.), el wrapper de
   `cargarNovedades()` ya tolera el caso `{data:[...]}`, pero cualquier
   otra discrepancia necesitará un ajuste puntual — el comentario en el
   HTML indica exactamente qué se está asumiendo, para localizarlo rápido
⚠️ Pendiente de probar en Local contra el CPT real (`rest_base`
   confirmado: `novedades`) antes de dar la migración por cerrada
▪️ No tocado en esta versión: `subirArchivo()`, Devocional, Biblioteca
   de Medios, Recursos, PIN, navegación — sin cambios
▪️ Bug de subida en V2 (reportado, causa aún sin diagnosticar) — queda
   aparte, no se ha tocado en esta sesión; ver nota aparte para Hno C/Hno A

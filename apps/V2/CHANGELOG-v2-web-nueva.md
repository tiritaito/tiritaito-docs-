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

# TIRITAITO_FOR_CREATORS_VERSIONS | Versiones de Tiritaito for Creators

**Dueño**: Hno A
**Audiencia**: Hno A · Carlitos (coordinación de GitHub)
**Propósito**: Diferencia entre V1 y V2 de la app, dónde vive cada archivo en GitHub, formato de changelog, y alcance confirmado de funcionalidades nuevas por versión
**Versión**: (controlada por Git)
**Última actualización**: 2026-07-26

---

## 0. Qué es este documento

⚠️ **Este documento absorbe `01_CREATORS_APP.md` (retirado, 26 julio 2026).** Ese documento
llevaba tiempo sin contenido propio más allá de una nota de "pendiente de actualizar" —
todo lo que debía describir (arquitectura, endpoints, especificación de módulos) ya vive
aquí, repartido entre las secciones 1, 6 y 7. No hay una sección separada de "arquitectura
general" porque la app es deliberadamente simple: un único archivo HTML autocontenido con
JS vanilla, que habla con WordPress vía los endpoints de la Sección 4 de `00_CORE.md` — no
hay más capas que documentar aparte de eso.

Responde: **¿qué diferencia hay entre V1 y V2 de Tiritaito for Creators, dónde vive cada archivo en GitHub, y qué funcionalidad nueva está confirmada para V2?**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Instrucciones completas del Proyecto 5 de Claude (protocolo de sesión) | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` |
| Mapa completo de `wp_options` / ACF / CPT del sistema | `00_CORE.md` |
| **V1 vs V2, changelog, alcance confirmado por versión, especificación de cada módulo** | **Este documento** |

---

## 1. V1 vs V2 — diferencias clave

| | V1 (web vieja) | V2 (web nueva) |
|---|---|---|
| Endpoint | `tiritaito.com/blog/wp-json` | `tiritaito-real.local/wp-json` (en Local) |
| Ciclo de trabajo | Solo bugs críticos, sin funcionalidad nueva | Desarrollo activo, funcionalidades nuevas |
| Nombre del archivo en GitHub | Uno por versión: `v1-07.html`, `v1-08.html`... (ver Sección 2) | ⚠️ **Fijo siempre**, no cambia con la versión (ver Sección 2 — decisión de Carlitos, 26 julio 2026) |
| Dónde vive el número real de versión | En el propio nombre del archivo | Solo en el footer del HTML y en el título de la entrada de `CHANGELOG-v2-web-nueva.md` |
| Autenticación | Token `TT_WRITE_TOKEN`, header `X-TT-Token` | Token `TT_WRITE_TOKEN`, header `X-TT-Token` (mismo patrón, confirmado 26 julio 2026 contra el HTML real) |

El código de ambas versiones es muy parecido — los cambios son puntuales: el endpoint, y las funcionalidades nuevas exclusivas de V2.

⚠️ **V1 tiene fecha de caducidad conocida (decisión de Carlitos, 26 julio 2026): desaparece
por completo en cuanto la Web Nueva sea oficial.** No es un mantenimiento indefinido — es
un puente hasta el lanzamiento. Cuando llegue ese momento, esta sección debe actualizarse
para reflejar que V1 quedó retirada, y el Proyecto 1 (Web Vieja) se archiva o reconvierte
(ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.1).

---

## 2. Dónde vive cada archivo en GitHub

```
tiritaito-docs/
└── apps/
    ├── v1/
    │   ├── tiritaito-creators-v1-XX.html   ← HTML de la app, web vieja
    │   └── CHANGELOG-v1-web-vieja.md       ← changelog de V1
    └── v2/
        ├── tiritaito-creators-v2-01.html               ← HTML de la app (nombre fijo)
        ├── CHANGELOG-v2-web-nueva.md                    ← changelog de V2
        └── snippet-tt-creators-endpoint-central.php     ← PHP del endpoint (nuevo,
                                                             26 julio 2026 — decisión de
                                                             Carlitos: vive aquí, no en
                                                             un documento aparte)
```

⚠️ **El PHP compartido el 26 de julio de 2026 no se ha subido** — estaba desactualizado
(sin las rutas de Novedades ni la lógica de ACF de Devocional, y con una función que Hno A
dijo haber eliminado) y llegó cortado a mitad de una función, copiado de una conversación
de Telegram. Antes de subir el archivo a esta carpeta, conseguir la versión real y completa
directamente desde Local o Code Snippets.

### ⚠️ Regla de nombres — DISTINTA para V1 y V2 (corregido 26 julio 2026)

**V1 — un archivo nuevo por versión, como siempre:** cada versión nueva es un archivo nuevo (`v1-07` → `v1-08`), nunca se sobreescribe el mismo nombre.

**V2 — nombre de archivo FIJO, siempre `tiritaito-creators-v2-01.html`, se sobreescribe.** Decisión de Carlitos, 26 julio 2026. El número real de la versión (v2-02, v2-03, v2-04, v2-05...) **vive solo en dos sitios**: el footer del propio HTML (`<div class="tt-footer-note">`) y el título de la entrada correspondiente en `CHANGELOG-v2-web-nueva.md`. El nombre del archivo en GitHub nunca cambia.

**Por qué el cambio:** esta regla ya se estaba rompiendo en la práctica desde hace tiempo sin que estuviera escrita así — el archivo en GitHub llevaba semanas subiéndose siempre como `tiritaito-creators-v2-01.html` mientras el número real avanzaba solo por dentro del HTML. Se detectó la discrepancia entre lo escrito y lo que hacía el equipo (Proyecto 5, sesión 26 julio 2026) y Carlitos decidió corregir el documento para que refleje la práctica real, en vez de forzar la práctica a seguir el documento.

**Consecuencia práctica al subir un cambio de V2:** aunque Claude entregue el archivo llamándolo `tiritaito-creators-v2-05.html` (o cualquier número), **hay que subirlo a GitHub como `tiritaito-creators-v2-01.html`, sobrescribiendo el que ya está ahí.** El número real solo se confirma leyendo el footer o el changelog.

⚠️ **Aviso de este mismo cambio de política:** si en algún momento se decide que sí conviene volver a un archivo por versión (por ejemplo, para poder comparar versiones antiguas directamente en GitHub sin depender del historial de commits), es una decisión reversible — pero mientras no se diga lo contrario, la regla es la de arriba.

---

## 3. Formato de changelog

Cada entrada nueva, arriba de las anteriores:

```markdown
## v2-02 — 2026-07-11

✅ Añadido módulo de Novedades (crear, editar, borrar)
✅ Guarda en wp_options como tt_novedades (JSON)
⚠️ Pendiente en Local: whitelist de tt_opciones_permitidas() + widget de home
```

**Si una versión no se registró en su momento** (pasó con v2-04, julio 2026): no se inventa contenido. Se escribe una entrada honesta explicando qué se sabe y qué no — ver el propio `CHANGELOG-v2-web-nueva.md`, entrada "v2-04 — fecha exacta sin confirmar", como ejemplo del formato a seguir en ese caso.

---

## 4. Cómo subir un cambio — paso a paso

### V1 (sin cambios respecto a siempre)

1. Descargar el HTML nuevo que entregó Claude en la sesión de trabajo
2. En local, en la carpeta del repo `tiritaito-docs`: `git pull origin main`
3. Copiar el HTML nuevo a `apps/v1/`, con el número de versión siguiente (`v1-08.html`, etc.)
4. Abrir `CHANGELOG-v1-web-vieja.md` y pegar la entrada nueva arriba
5. `git add` de los dos archivos (HTML + changelog)
6. `git commit -m "v1-08: descripción corta"`
7. `git push origin main`
8. Pulsar **"Sync now"** en el conector de GitHub del Proyecto 1 antes de la siguiente sesión

### V2 — nombre fijo (actualizado 26 julio 2026)

1. Descargar el HTML nuevo que entregó Claude (puede venir con cualquier número en el nombre — no importa)
2. En local, en la carpeta del repo `tiritaito-docs`: `git pull origin main`
3. Copiar el HTML nuevo a `apps/v2/`, **renombrándolo siempre a `tiritaito-creators-v2-01.html`** — sobrescribe el que ya está
4. Abrir `CHANGELOG-v2-web-nueva.md` y pegar la entrada nueva arriba, con el número real de versión en el título (`## v2-06 — 2026-08-XX`, etc.) — este número es la única referencia fiable a qué versión es
5. `git add` de los dos archivos (HTML + changelog)
6. `git commit -m "v2-06: descripción corta"` — el número va en el mensaje del commit, no en el nombre del archivo
7. `git push origin main`
8. Pulsar **"Sync now"** en el conector de GitHub del Proyecto 5 (y del Proyecto 3, si ese cambio también le afecta — ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 3) antes de la siguiente sesión

---

## 5. V2 — alcance confirmado hasta ahora

| Módulo | Estado (26 julio 2026) |
|---|---|
| Novedades | ✅ Backend confirmado y probado de extremo a extremo (crear, editar, ocultar/mostrar, borrar) — CPT `novedades` + ACF (6 campos) + endpoint `tiritaito/v1/novedades`. ⚠️ El campo `titulo` existe en el backend pero **la app todavía no lo envía** (confirmado revisando `guardarNovedad()` en v2-05 — el payload no incluye `titulo`). Pendiente además: montar Post Cards + Dynamic Content en Avada (no filtra por `activo`, ver `GUIA_AVADA_LOCAL.md` Sección 9) |
| Devocional (Virgen, Brisa, Homilía, Lenguas) | 🔄 En migración parcial — 7 de 12 claves antiguas de `wp_options` migradas a ACF Options Page "Devocional — Contenido Diario" (ver Sección 7). Virgen y Brisa ya separan fecha/autor en campos propios en la app (v2-05); `virgen_fecha` es obligatoria antes de publicar. ⚠️ Homilía — texto **sigue pidiendo y pegando la fecha en el texto** en v2-05 — la decisión de quitarla (26 julio 2026, siempre es la fecha de publicación) no se ha aplicado todavía en la app |
| Tip del día | ❌ Decisión: eliminado por completo (26 julio 2026) — pero ⚠️ **sigue construido en la app** (`tt_tip_1`/`tt_tip_2`, confirmado en v2-05). Falta que Proyecto 5 lo retire de verdad |
| Cualquier otro módulo nuevo | 🔲 Sin decidir — no dar nada por hecho hasta que se confirme aquí |

---

## 6. Novedades — especificación técnica

⚠️ **Esquema actualizado el 26 de julio de 2026.** El esquema anterior (bloque JSON único en `wp_options`, clave `tt_novedades`) queda sustituido por completo por un Custom Post Type propio con ACF — confirmado funcionando de extremo a extremo.

**CPT:** `novedades` (`public => false`, visible en wp-admin; `rest_base` confirmado: `novedades`)

**Campos ACF (6):**

| Campo | Tipo | Nota |
|---|---|---|
| `tipo` | Texto | `"imagen"` o `"video"` |
| `media_url` | Texto/URL | ⚠️ Debe ser tipo Texto, no tipo "Image" de ACF — evita depender de un ID de archivo que la app no tiene (hallazgo del piloto, 23 julio) |
| `texto` | Texto largo | Opcional |
| `enlace` | Texto/URL | Opcional |
| `fecha` | Fecha, Return Format `Ymd` | El endpoint convierte `Ymd` ↔ `YYYY-MM-DD` en la frontera — el campo ACF interno se queda siempre en `Ymd` para que Post Cards ordene bien |
| `activo` | Verdadero/Falso | Control interno del editor — **no filtra el listado público** (decisión de equipo, 26 julio 2026, ver `GUIA_AVADA_LOCAL.md` Sección 9) |
| `titulo` | Texto | Campo confirmado en el backend desde el inicio (Opción A) — ⚠️ **sin input todavía en la app** (v2-05 no lo envía) |

**Endpoint dedicado — `tiritaito/v1/novedades`** (nunca `/wp/v2/posts`):

| Método | Ruta | Función |
|---|---|---|
| GET | `/tiritaito/v1/novedades` | Lista todas las novedades — activas y ocultas, sin filtrar |
| POST | `/tiritaito/v1/novedades` | Crea una novedad — el `id` lo asigna el servidor (ID real del post), nunca lo genera la app |
| PUT | `/tiritaito/v1/novedades/{id}` | Edita una novedad |
| DELETE | `/tiritaito/v1/novedades/{id}` | Elimina una novedad |

**El shortcode `[tt_novedades]`** que se llegó a construir en el snippet PHP replicando el patrón antiguo de `wp_options` **queda descartado** — la lectura pública es Post Cards de Avada + Dynamic Content, no un shortcode propio.

**Pendiente:**
- App: añadir el input de `titulo` al formulario de Novedades
- Avada: montar Post Cards + Dynamic Content (el campo `activo` no tendrá efecto — se muestran todas)

---

## 7. Devocional — especificación técnica

**ACF Options Page:** "Devocional — Contenido Diario"

| Campo ACF | Tipo | Clave que envía/recibe la app vía `/tiritaito/v1/datos` |
|---|---|---|
| `virgen` | Texto largo | `tt_virgen` |
| `virgen_fecha` | Fecha | `tt_virgen_fecha` — **obligatoria** antes de publicar (validación añadida en v2-05) |
| `brisa` | Texto largo | `tt_brisa` |
| `brisa_autor` | Texto | `tt_brisa_autor` |
| `homilia_audio` | URL Media Library | `tt_homilia_audio` |
| `homilia_texto` | Texto largo | `tt_homilia_texto` — ⚠️ debería dejar de llevar fecha (decisión 26 julio: siempre es la fecha de publicación), pendiente de aplicar en la app |
| `lenguas_url` | URL Media Library | `tt_lenguas_url` |

⚠️ **Nota de nomenclatura, para no confundir campo ACF con clave de API:** el nombre del campo dentro de ACF no lleva el prefijo `tt_` (ej. `virgen_fecha`), pero la clave que la app manda y recibe en el JSON de `/tiritaito/v1/datos` sí lo lleva (`tt_virgen_fecha`). El snippet PHP hace la traducción entre una forma y otra. Si en algún momento se escribe código nuevo contra este endpoint, usar la clave con `tt_`, no el nombre interno de ACF.

Las otras 5 claves antiguas de Devocional/Recursos (`tt_docx_lectura_url`, `tt_youtube_json_url`, `tt_seminarios_json_url`, `tt_viacrucis_json_url`, `tt_fiesta_dias`) **se quedan en `wp_options` sin tocar** — ver `00_CORE.md` Sección 3.2.

---

## 8. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Proyecto 5: quitar de la app el input de `titulo` pendiente → añadirlo al formulario de Novedades
2. Proyecto 5: quitar el input de fecha de Homilía-texto y dejar de anexarla al texto
3. Proyecto 5: retirar por completo la UI de "Tip del día" (tarjetas, inputs, botones de quitar) — confirmado de nuevo por Carlitos el 26 de julio, es la próxima actualización de la app
4. Al subir cualquiera de esos cambios: seguir el paso a paso de la Sección 4 — nombre de archivo fijo para V2, número real solo en el footer y en el changelog
5. Confirmar con Hno A qué cambió realmente en v2-04 (si se recuerda) para completar esa entrada del changelog — no es urgente, es un hueco honesto, no un bloqueante
6. Conseguir el snippet PHP real y completo del endpoint central y subirlo a `apps/v2/` (Sección 2) — el que se compartió el 26 de julio no sirve, ver el aviso de esa sección
7. Cuando el snippet PHP esté subido: dar acceso de lectura a Proyecto 5 sobre ese archivo (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.2) para que pueda verificar el contrato real antes de escribir cada fetch nuevo

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿La regla de "nombre de archivo fijo" se extiende también a V1, o V1 se queda con un archivo por versión como hasta ahora? | Asunción actual: solo V2 cambia — V1 está en mantenimiento mínimo, tiene fecha de caducidad conocida (Sección 1) y no se ha reportado confusión ahí. Confirmar si es correcto |
| 2 | ¿Qué otras funcionalidades nuevas llevará V2, además de Novedades y Devocional? | El equipo aún no lo ha determinado — no asumir nada hasta que se confirme aquí |
| 3 | ¿`01_CREATORS_APP.md` tenía contenido real en el repositorio de GitHub que no llegó a este Proyecto de Investigación? | Antes de confirmar que su fusión aquí (Sección 0) no perdió nada, verificar contra el archivo real en GitHub |

---

*Para la mayor gloria de Dios · tiritaito.com*

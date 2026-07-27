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
        └── snippet-tt-creators-endpoint-central.php     ← PHP del endpoint (decisión de
                                                             Carlitos: vive aquí, no en
                                                             un documento aparte)
```

✅ **Resuelto (26 julio 2026):** la primera copia del PHP compartida ese mismo día era en
realidad la del sitio de producción (la web vieja) — desactualizada respecto a este
proyecto (sin las rutas de Novedades ni la lógica de ACF de Devocional) y llegó cortada a
mitad de una función, copiada de una conversación de Telegram. Se obtuvo después la versión
real y completa del backend de Local, verificada línea por línea contra lo reportado por
Hno A — coincide en todo. Lista para subir a `apps/v2/snippet-tt-creators-endpoint-central.php`.
Ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 7.1 para tres avisos abiertos encontrados
al revisarla (sin bloquear la subida).

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

✅ **Confirmado contra el snippet PHP real y completo** (`apps/v2/snippet-tt-creators-endpoint-central.php`, subido 26 julio 2026 — la primera versión compartida ese mismo día estaba desactualizada y cortada; ver Sección 8 para el historial).

**CPT:** `novedades` — `public => false` (no genera páginas propias públicas), `show_ui => true` (visible en wp-admin), `show_in_rest => true` (necesario para ACF y REST), `supports => ['title']`. `rest_base` confirmado: `novedades`.

**Campos ACF (6):**

| Campo | Tipo | Nota |
|---|---|---|
| `tipo` | Texto | `"imagen"` o `"video"` |
| `media_url` | Texto/URL | ⚠️ Debe ser tipo Texto, no tipo "Image" de ACF — evita depender de un ID de archivo que la app no tiene (hallazgo del piloto, 23 julio) |
| `texto` | Texto largo | Opcional |
| `enlace` | Texto/URL | Opcional |
| `fecha` | Fecha, Return Format `Ymd` | El endpoint convierte `Ymd` ↔ `YYYY-MM-DD` en la frontera (`tt_novedades_fecha_a_app()` / `tt_novedades_fecha_a_acf()`) — el campo ACF interno se queda siempre en `Ymd` para que Post Cards ordene bien |
| `activo` | Verdadero/Falso | Control interno del editor — **no filtra el listado público** (decisión de equipo, 26 julio 2026, ver `GUIA_AVADA_LOCAL.md` Sección 9) |
| `titulo` | Texto | Confirmado en el backend — si llega vacío, el título del post se autogenera como `"Novedad " + fecha/hora`. ⚠️ **Sin input todavía en la app** (v2-05 no lo envía) |

**Endpoint dedicado — `tiritaito/v1/novedades`** (nunca `/wp/v2/posts`):

| Método | Ruta | Auth | Función |
|---|---|---|---|
| GET | `/tiritaito/v1/novedades` | Pública | Lista todas las novedades — activas y ocultas, sin filtrar |
| POST | `/tiritaito/v1/novedades` | `X-TT-Token` | Crea una novedad — el `id` lo asigna el servidor |
| PUT | `/tiritaito/v1/novedades/{id}` | `X-TT-Token` | Edita una novedad |
| DELETE | `/tiritaito/v1/novedades/{id}` | `X-TT-Token` | Elimina una novedad |

**El shortcode `[tt_novedades]`** que se llegó a construir por error replicando el patrón antiguo de `wp_options` **queda descartado** — la lectura pública es Post Cards de Avada + Dynamic Content, no un shortcode propio. No está presente en el PHP real subido.

**Pendiente:**
- App: añadir el input de `titulo` al formulario de Novedades
- Avada: montar Post Cards + Dynamic Content (el campo `activo` no tendrá efecto — se muestran todas)

---

## 7. Devocional — especificación técnica

✅ **Confirmado contra el snippet PHP real y completo** (`apps/v2/snippet-tt-creators-endpoint-central.php`, 26 julio 2026).

**ACF Options Page:** "Devocional — Contenido Diario"

| Campo ACF | Tipo | Clave que envía/recibe la app vía `/tiritaito/v1/datos` |
|---|---|---|
| `virgen` | Texto largo | `tt_virgen` |
| `virgen_fecha` | Fecha | `tt_virgen_fecha` — **obligatoria** antes de publicar (validación añadida en v2-05); el PHP valida el patrón `YYYY-MM-DD` con expresión regular y descarta el valor si no coincide |
| `brisa` | Texto largo | `tt_brisa` |
| `brisa_autor` | Texto | `tt_brisa_autor` |
| `homilia_audio` | URL Media Library | `tt_homilia_audio` |
| `homilia_texto` | Texto largo | `tt_homilia_texto` — ⚠️ debería dejar de llevar fecha (decisión 26 julio: siempre es la fecha de publicación), pendiente de aplicar en la app |
| `lenguas_url` | URL Media Library | `tt_lenguas_url` |

⚠️ **Nota de nomenclatura, para no confundir campo ACF con clave de API:** el nombre del campo dentro de ACF no lleva el prefijo `tt_` (ej. `virgen_fecha`), pero la clave que la app manda y recibe en el JSON de `/tiritaito/v1/datos` sí lo lleva (`tt_virgen_fecha`). El snippet PHP hace la traducción entre una forma y otra (`tt_mapa_campos_devocional()`). Si en algún momento se escribe código nuevo contra este endpoint, usar la clave con `tt_`, no el nombre interno de ACF.

✅ **Sanitizado por tipo de campo, no binario texto/URL (confirmado 26 julio 2026):** `virgen`/`brisa`/`homilia_texto` usan `sanitize_textarea_field()`; `homilia_audio`/`lenguas_url` usan `esc_url_raw()`; `virgen_fecha` valida el patrón de fecha; `brisa_autor` y el resto usan `sanitize_text_field()`. A propósito, para que un nombre de persona (ej. "José María" en `brisa_autor`) no se trate como URL y se corrompan los acentos.

Las otras 5 claves antiguas de Devocional/Recursos (`tt_docx_lectura_url`, `tt_youtube_json_url`, `tt_seminarios_json_url`, `tt_viacrucis_json_url`, `tt_fiesta_dias`) **se quedan en `wp_options` sin tocar** — ver `00_CORE.md` Sección 3.2.

---

## 7.1 Avisos abiertos sobre el backend actual — no bloquean, pero hay que confirmarlos

Tres observaciones al revisar el PHP real y completo (26 julio 2026), comparándolo con la versión anterior del snippet (la de la web vieja, no la de este proyecto):

| Observación | Detalle | Estado |
|---|---|---|
| Sin límite de peticiones (rate limit) | El PHP anterior limitaba a 60 peticiones/hora por IP con el token de escritura. El actual no tiene ningún límite | 🔲 Sin confirmar si es una omisión temporal tras la reconstrucción del 26 de julio, o una decisión deliberada |
| Subida de archivos sin validar tipo ni tamaño | `tt_subir_archivo()` llama directo a `media_handle_upload()`, sin lista de tipos MIME permitidos ni tamaño máximo — el PHP anterior sí los comprobaba antes de subir | 🔲 Igual que arriba, sin confirmar |
| Sin rastro de Biblioteca (libros) ni de gestión de entradas (`/entradas`) | El PHP anterior tenía un subsistema completo `biblioteca/v1/*` (con `BIBLIOTECA_TOKEN` propio) y rutas `/tiritaito/v1/entradas` para gestionar posts normales desde la app. Ninguno de los dos está en el backend actual | 🔲 No se sabe si es un descarte deliberado o si simplemente no se han reconstruido todavía — relevante para la pregunta abierta #5 de `ALCANCE_WEB_NUEVA.md` sobre la PWA de Biblioteca |

---

## 8. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Proyecto 5: quitar de la app el input de `titulo` pendiente → añadirlo al formulario de Novedades
2. Proyecto 5: quitar el input de fecha de Homilía-texto y dejar de anexarla al texto
3. Proyecto 5: retirar por completo la UI de "Tip del día" (tarjetas, inputs, botones de quitar) — confirmado de nuevo por Carlitos el 26 de julio, es la próxima actualización de la app
4. Al subir cualquiera de esos cambios: seguir el paso a paso de la Sección 4 — nombre de archivo fijo para V2, número real solo en el footer y en el changelog
5. Confirmar con Hno A qué cambió realmente en v2-04 (si se recuerda) para completar esa entrada del changelog — no es urgente, es un hueco honesto, no un bloqueante
6. ✅ Snippet PHP real y completo obtenido y subido a `apps/v2/snippet-tt-creators-endpoint-central.php` (26 julio 2026) — la primera versión compartida ese mismo día era de la web vieja, no de esta; ya corregido
7. Confirmar con Hno A los tres avisos de la Sección 7.1 (rate limit, validación de subidas, Biblioteca/entradas ausentes)
8. Dar acceso de lectura a Proyecto 5 sobre `apps/v2/snippet-tt-creators-endpoint-central.php` (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.2) para que pueda verificar el contrato real antes de escribir cada fetch nuevo

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿La regla de "nombre de archivo fijo" se extiende también a V1, o V1 se queda con un archivo por versión como hasta ahora? | Asunción actual: solo V2 cambia — V1 está en mantenimiento mínimo, tiene fecha de caducidad conocida (Sección 1) y no se ha reportado confusión ahí. Confirmar si es correcto |
| 2 | ¿Qué otras funcionalidades nuevas llevará V2, además de Novedades y Devocional? | El equipo aún no lo ha determinado — no asumir nada hasta que se confirme aquí |
| 3 | ¿`01_CREATORS_APP.md` tenía contenido real en el repositorio de GitHub que no llegó a este Proyecto de Investigación? | Antes de confirmar que su fusión aquí (Sección 0) no perdió nada, verificar contra el archivo real en GitHub |
| 4 | ¿Rate limit, validación de subidas, y Biblioteca/entradas ausentes (Sección 7.1) — deliberado o pendiente de reconstruir? | Determina si hace falta una sesión de Proyecto 3 para restaurarlas, o si el sistema se queda así a propósito |

---

*Para la mayor gloria de Dios · tiritaito.com*

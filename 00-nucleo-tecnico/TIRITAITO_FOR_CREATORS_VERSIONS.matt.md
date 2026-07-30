<!--
  ⚠️ ARCHIVO DE TRABAJO CON DATOS FICTICIOS (PLACEHOLDER)
  Este NO es el archivo oficial. Todos los tokens, dominios y valores
  concretos aquí son ficticios. Trabaja con normalidad sobre este
  archivo — tus cambios se revisan y se aplican al archivo oficial
  (TIRITAITO_FOR_CREATORS_VERSIONS.md) por el equipo interno.
-->

# TIRITAITO_FOR_CREATORS_VERSIONS (copia de trabajo — datos ficticios)

**Propósito**: Diferencia entre V1 y V2 de la app, dónde vive cada archivo en GitHub,
formato de changelog, y alcance confirmado de funcionalidades nuevas por versión

---

## 0. Qué es este documento

Responde: **¿qué diferencia hay entre V1 y V2 de Tiritaito for Creators, dónde vive cada
archivo en GitHub, y qué funcionalidad nueva está confirmada para V2?**

---

## 1. V1 vs V2 — diferencias clave

| | V1 (web vieja) | V2 (web nueva) |
|---|---|---|
| Endpoint | Dominio de producción (placeholder — no compartido en esta copia) | Dominio de entorno local (placeholder — no compartido en esta copia) |
| Ciclo de trabajo | Solo bugs críticos, sin funcionalidad nueva | Desarrollo activo, funcionalidades nuevas |
| Nombre del archivo en GitHub | Uno por versión: `v1-07.html`, `v1-08.html`... | ⚠️ **Fijo siempre**, no cambia con la versión — ver Sección 2 |
| Dónde vive el número real de versión | En el propio nombre del archivo | Solo en el footer del HTML y en el título de la entrada del changelog |
| Autenticación | Token `TT_WRITE_TOKEN`, header `X-TT-Token` | Token `TT_WRITE_TOKEN`, header `X-TT-Token` (mismo patrón) |

El código de ambas versiones es muy parecido — los cambios son puntuales: el endpoint, y
las funcionalidades nuevas exclusivas de V2.

⚠️ **V1 tiene fecha de caducidad conocida:** desaparece por completo en cuanto la Web Nueva
sea oficial. No es un mantenimiento indefinido — es un puente hasta el lanzamiento.

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
        ├── tiritaito-creators-v2-01.matt.html          ← copia de trabajo con placeholders
        ├── CHANGELOG-v2-web-nueva.md                    ← changelog de V2
        ├── snippet-tt-creators-endpoint-central.php     ← PHP del endpoint (oficial)
        └── snippet-tt-creators-endpoint-central.matt.php ← copia de trabajo con placeholders
```

### ⚠️ Regla de nombres — DISTINTA para V1 y V2

**V1 — un archivo nuevo por versión, como siempre:** cada versión nueva es un archivo
nuevo (`v1-07` → `v1-08`), nunca se sobreescribe el mismo nombre.

**V2 — nombre de archivo FIJO, siempre `tiritaito-creators-v2-01.html`, se sobreescribe.**
El número real de la versión (v2-02, v2-03, v2-04, v2-05...) **vive solo en dos sitios**:
el footer del propio HTML (`<div class="tt-footer-note">`) y el título de la entrada
correspondiente en `CHANGELOG-v2-web-nueva.md`. El nombre del archivo en GitHub nunca
cambia.

**Consecuencia práctica al proponer un cambio de V2:** aunque generes el archivo
llamándolo con el número real (`tiritaito-creators-v2-06.matt.html` o similar), la
convención al subirlo a la copia de trabajo mantiene siempre el sufijo `.matt.html` sobre
el nombre base fijo — el equipo interno se encarga de la numeración real cuando aplica el
cambio al archivo oficial.

---

## 3. Formato de changelog

Cada entrada nueva, arriba de las anteriores:

```markdown
## v2-02 — 2026-07-11

✅ Añadido módulo de Novedades (crear, editar, borrar)
✅ Guarda en wp_options como tt_novedades (JSON)
⚠️ Pendiente en Local: whitelist de tt_opciones_permitidas() + widget de home
```

**Si una versión no se registró en su momento:** no se inventa contenido. Se escribe una
entrada honesta explicando qué se sabe y qué no.

---

## 4. Cómo se sube un cambio (referencia — el equipo interno gestiona el archivo oficial)

Esta sección describe el proceso que sigue el equipo interno con el archivo oficial. Como
colaborador trabajando sobre la copia `.matt.*`, tu entrega es la propia copia actualizada
más una descripción clara de qué cambiaste — el equipo interno se encarga de aplicarlo al
archivo oficial y de la numeración real de versión.

---

## 5. V2 — alcance confirmado hasta ahora

| Módulo | Estado |
|---|---|
| Novedades | ✅ Backend confirmado y probado de extremo a extremo (crear, editar, ocultar/mostrar, borrar) — CPT `novedades` + ACF (6 campos) + endpoint `tiritaito/v1/novedades`. ⚠️ El campo `titulo` existe en el backend pero la app todavía no lo envía. Pendiente además: montar Post Cards + Dynamic Content en Avada |
| Devocional (Virgen, Brisa, Homilía, Lenguas) | 🔄 En migración parcial — 7 de 12 claves antiguas de `wp_options` migradas a ACF Options Page "Devocional — Contenido Diario". Virgen y Brisa ya separan fecha/autor en campos propios; `virgen_fecha` es obligatoria antes de publicar. ⚠️ Homilía — texto sigue pidiendo y pegando la fecha en el texto — pendiente de corregir |
| Tip del día | ❌ Decisión: eliminado por completo — pero ⚠️ sigue construido en la app. Falta que se retire de verdad |
| Cualquier otro módulo nuevo | 🔲 Sin decidir — no dar nada por hecho hasta que se confirme |

---

## 6. Novedades — especificación técnica

**CPT:** `novedades` — `public => false` (no genera páginas propias públicas), `show_ui =>
true` (visible en wp-admin), `show_in_rest => true` (necesario para ACF y REST),
`supports => ['title']`. `rest_base` confirmado: `novedades`.

**Campos ACF (6):**

| Campo | Tipo | Nota |
|---|---|---|
| `tipo` | Texto | `"imagen"` o `"video"` |
| `media_url` | Texto/URL | ⚠️ Debe ser tipo Texto, no tipo "Image" de ACF — evita depender de un ID de archivo que la app no tiene |
| `texto` | Texto largo | Opcional |
| `enlace` | Texto/URL | Opcional |
| `fecha` | Fecha, Return Format `Ymd` | El endpoint convierte `Ymd` ↔ `YYYY-MM-DD` en la frontera — el campo ACF interno se queda siempre en `Ymd` para que Post Cards ordene bien |
| `activo` | Verdadero/Falso | Control interno del editor — no filtra el listado público |
| `titulo` | Texto | Si llega vacío, el título del post se autogenera como `"Novedad " + fecha/hora`. ⚠️ Sin input todavía en la app |

**Endpoint dedicado — `tiritaito/v1/novedades`** (nunca `/wp/v2/posts`):

| Método | Ruta | Auth | Función |
|---|---|---|---|
| GET | `/tiritaito/v1/novedades` | Pública | Lista todas las novedades — activas y ocultas, sin filtrar |
| POST | `/tiritaito/v1/novedades` | `X-TT-Token` | Crea una novedad — el `id` lo asigna el servidor |
| PUT | `/tiritaito/v1/novedades/{id}` | `X-TT-Token` | Edita una novedad |
| DELETE | `/tiritaito/v1/novedades/{id}` | `X-TT-Token` | Elimina una novedad |

**El shortcode `[tt_novedades]`** que se llegó a construir por error replicando el patrón
antiguo de `wp_options` **queda descartado** — la lectura pública es Post Cards de Avada +
Dynamic Content, no un shortcode propio.

**Pendiente:**
- App: añadir el input de `titulo` al formulario de Novedades
- Avada: montar Post Cards + Dynamic Content (el campo `activo` no tendrá efecto — se muestran todas)

---

## 7. Devocional — especificación técnica

**ACF Options Page:** "Devocional — Contenido Diario"

| Campo ACF | Tipo | Clave que envía/recibe la app vía `/tiritaito/v1/datos` |
|---|---|---|
| `virgen` | Texto largo | `tt_virgen` |
| `virgen_fecha` | Fecha | `tt_virgen_fecha` — **obligatoria** antes de publicar; el PHP valida el patrón `YYYY-MM-DD` con expresión regular y descarta el valor si no coincide |
| `brisa` | Texto largo | `tt_brisa` |
| `brisa_autor` | Texto | `tt_brisa_autor` |
| `homilia_audio` | URL Media Library | `tt_homilia_audio` |
| `homilia_texto` | Texto largo | `tt_homilia_texto` — ⚠️ debería dejar de llevar fecha, pendiente de aplicar en la app |
| `lenguas_url` | URL Media Library | `tt_lenguas_url` |

⚠️ **Nota de nomenclatura:** el nombre del campo dentro de ACF no lleva el prefijo `tt_`
(ej. `virgen_fecha`), pero la clave que la app manda y recibe en el JSON de
`/tiritaito/v1/datos` sí lo lleva (`tt_virgen_fecha`). El snippet PHP hace la traducción
entre una forma y otra. Si se escribe código nuevo contra este endpoint, usar la clave con
`tt_`, no el nombre interno de ACF.

**Sanitizado por tipo de campo, no binario texto/URL:** `virgen`/`brisa`/`homilia_texto`
usan `sanitize_textarea_field()`; `homilia_audio`/`lenguas_url` usan `esc_url_raw()`;
`virgen_fecha` valida el patrón de fecha; `brisa_autor` y el resto usan
`sanitize_text_field()`. A propósito, para que un nombre de persona (ej. "José María" en
`brisa_autor`) no se trate como URL y se corrompan los acentos.

Las otras 5 claves antiguas de Devocional/Recursos (`tt_docx_lectura_url`,
`tt_youtube_json_url`, `tt_seminarios_json_url`, `tt_viacrucis_json_url`,
`tt_fiesta_dias`) **se quedan en `wp_options` sin tocar** — ver `00_CORE.matt.md` Sección 3.2.

---

## 7.1 Avisos abiertos sobre el backend actual — no bloquean, pero hay que confirmarlos

| Observación | Detalle | Estado |
|---|---|---|
| Sin límite de peticiones (rate limit) | Una versión anterior del PHP limitaba a 60 peticiones/hora por IP con el token de escritura. El actual no tiene ningún límite | 🔲 Sin confirmar si es omisión temporal o decisión deliberada |
| Subida de archivos sin validar tipo ni tamaño | `tt_subir_archivo()` llama directo a `media_handle_upload()`, sin lista de tipos MIME permitidos ni tamaño máximo — una versión anterior sí los comprobaba | 🔲 Sin confirmar |
| Sin rastro de Biblioteca (libros) ni de gestión de entradas (`/entradas`) | Una versión anterior tenía un subsistema completo `biblioteca/v1/*` y rutas `/tiritaito/v1/entradas`. Ninguno de los dos está en el backend actual | 🔲 No se sabe si es un descarte deliberado o si simplemente no se han reconstruido todavía |

---

## 8. Próximos pasos y preguntas abiertas (de esta copia de trabajo)

Si tu trabajo sobre esta copia sugiere que algo aquí debería reflejarse en el archivo
oficial, descríbelo con claridad al entregarlo — el equipo interno decide si se aplica y
cómo.

---

*Para la mayor gloria de Dios · tiritaito.com*

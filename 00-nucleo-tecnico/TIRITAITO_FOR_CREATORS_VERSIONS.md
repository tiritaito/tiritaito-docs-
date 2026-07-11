# TIRITAITO_FOR_CREATORS_VERSIONS | Versiones de Tiritaito for Creators

**Dueño**: Hno A
**Audiencia**: Hno A · Carlitos (coordinación de GitHub)
**Propósito**: Diferencia entre V1 y V2 de la app, dónde vive cada archivo en GitHub, formato de changelog, y alcance confirmado de funcionalidades nuevas por versión
**Versión**: (controlada por Git)
**Última actualización**: 2026-07-11

---

## 0. Qué es este documento

Responde: **¿qué diferencia hay entre V1 y V2 de Tiritaito for Creators, dónde vive cada archivo en GitHub, y qué funcionalidad nueva está confirmada para V2?**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Instrucciones completas del Proyecto 5 de Claude (protocolo de sesión) | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` |
| Especificación general de la app (arquitectura, endpoints) | `01_CREATORS_APP.md` |
| Mapa completo de `wp_options` del sistema | `00_CORE.md` |
| **V1 vs V2, changelog, alcance confirmado por versión** | **Este documento** |

---

## 1. V1 vs V2 — diferencias clave

| | V1 (web vieja) | V2 (web nueva) |
|---|---|---|
| Endpoint | `tiritaito.com/blog/wp-json` | `tiritaito-real.local/wp-json` (en Local) |
| Ciclo de trabajo | Solo bugs críticos, sin funcionalidad nueva | Desarrollo activo, funcionalidades nuevas |
| Versionado | v1-01, v1-02... v1-07, v1-08... | v2-01, v2-02... |
| Autenticación | Token `TT_WRITE_TOKEN` | Token `TT_WRITE_TOKEN` (mismo patrón) |

El código de ambas versiones es muy parecido — los cambios son puntuales: el endpoint, y las funcionalidades nuevas exclusivas de V2.

---

## 2. Dónde vive cada archivo en GitHub

```
tiritaito-docs/
└── apps/
    ├── v1/
    │   ├── tiritaito-creators-v1-XX.html   ← HTML de la app, web vieja
    │   └── CHANGELOG-v1-web-vieja.md       ← changelog de V1
    └── v2/
        ├── tiritaito-creators-v2-XX.html   ← HTML de la app, web nueva (Local)
        └── CHANGELOG-v2-web-nueva.md       ← changelog de V2
```

**Regla de subida:** cada versión nueva es un archivo nuevo (`v1-07` → `v1-08`), nunca se sobreescribe el mismo nombre. El changelog correspondiente se actualiza en el mismo commit, con la entrada nueva **arriba** del todo.

---

## 3. Formato de changelog

Cada entrada nueva, arriba de las anteriores:

```markdown
## v2-02 — 2026-07-11

✅ Añadido módulo de Novedades (crear, editar, borrar)
✅ Guarda en wp_options como tt_novedades (JSON)
⚠️ Pendiente en Local: whitelist de tt_opciones_permitidas() + widget de home
```

---

## 4. Cómo subir un cambio — paso a paso

1. Descargar el HTML nuevo que entregó Claude en la sesión de trabajo
2. En local, en la carpeta del repo `tiritaito-docs`: `git pull origin main`
3. Copiar el HTML nuevo a `apps/v1/` o `apps/v2/` según corresponda, con el número de versión siguiente
4. Abrir el changelog correspondiente y pegar la entrada nueva arriba
5. `git add` de los dos archivos (HTML + changelog)
6. `git commit -m "v2-02: descripción corta"`
7. `git push origin main`
8. Antes de la siguiente sesión de trabajo con Claude en el Proyecto 5, pulsar **"Sync now"** en el conector de GitHub — si no, Claude seguirá viendo la versión anterior

---

## 5. V2 — alcance confirmado hasta ahora

⚠️ **El equipo todavía no ha cerrado el alcance completo de qué funcionalidades nuevas lleva V2.** Lo único confirmado y construido hasta esta fecha:

| Módulo | Estado |
|---|---|
| Novedades | ✅ Construido en la app (v2-01) — ver Sección 6 |
| Cualquier otro módulo nuevo | 🔲 Sin decidir — no dar nada por hecho hasta que se confirme aquí |

---

## 6. Novedades — especificación técnica

**Clave de `wp_options`:** `tt_novedades`
**Patrón:** igual que `tt_fiesta_dias` — la app manda la lista completa en cada cambio, vía `POST /tiritaito/v1/datos`.

Formato de cada elemento de la lista:

```json
{
  "id": "nov_1752345678_ab12",
  "tipo": "imagen",
  "media_url": "https://.../archivo.jpg",
  "texto": "Texto opcional",
  "enlace": "https://... (opcional)",
  "fecha": "2026-07-11",
  "activo": true
}
```

El campo `activo` determina si se muestra en la web — las novedades ocultas (`activo: false`) permanecen en la lista pero no deben pintarse.

**Pendiente en Local (Hno A):**
1. Añadir `tt_novedades` a la whitelist de `tt_opciones_permitidas()` — sin esto, el guardado falla con el mismo problema ya visto con `tt_fiesta_dias` y `tt_viacrucis_json_url`
2. Construir el widget/shortcode que lea `tt_novedades` y lo pinte en la home: la novedad más reciente en grande (hero), el resto en una tira horizontal deslizable debajo
3. El widget debe pintar solo los elementos con `activo: true`

---

## 7. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Hno A: añadir `tt_novedades` a la whitelist en el snippet PHP del Local
2. Hno A: construir el widget de Novedades en Avada, según el diseño hero + tira horizontal ya propuesto
3. Carlitos: subir este documento + los archivos de `apps/` a GitHub, y actualizar `README.md`, `00_CORE.md` y `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` según los bloques ya entregados

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Qué otras funcionalidades nuevas llevará V2, además de Novedades? | El equipo aún no lo ha determinado — no asumir nada hasta que se confirme aquí |

---

*Para la mayor gloria de Dios · tiritaito.com*

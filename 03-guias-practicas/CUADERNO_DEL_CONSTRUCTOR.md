# TIRITAITO.COM — Cuaderno del Constructor
**Lo que Álvaro va descubriendo al construir en Avada — funciona, no funciona, o se
investigó y esto lo resuelve — para que sus 2 cuentas de construcción no tengan que
redescubrirlo cada vez**
*Nace el 1 de septiembre de 2026 — ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 3.3
y el explicador visual (`cuaderno-del-constructor.html`)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Un cuaderno de campo, no un manual. Aquí se anota, sobre la marcha, lo que se descubre al
construir — antes de que llegue a los documentos oficiales, no en vez de ellos.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Qué elemento de Avada usar para una necesidad ya confirmada, con su nivel de certeza | `CATALOGO_ELEMENTOS_AVADA.md` |
| Cómo funciona Avada/Local mecánicamente, ya confirmado y estable | `GUIA_AVADA_LOCAL.md` |
| Una trampa de código ya confirmada (JS, PHP, WordPress) | `00_CORE.md` Sección 8 |
| **Algo recién descubierto, todavía sin pasar en limpio a ninguno de los tres de arriba** | **Este documento** |

## 1. Los tres tipos de anotación

No son pasos de un proceso — cualquiera puede darse en cualquier momento:

| Tipo | Significa |
|---|---|
| ✅ Esto funciona así | Algo que salió bien a la primera. Se anota igual, para no tener que redescubrirlo. |
| ⚠️ Cuidado con esto | Algo que dio problemas, aunque se resolviera solo. |
| 🔲→✅ Se investigó y esto lo resuelve | Vuelve ya resuelto de un documento para Carlitos (Proyecto 3 Sección 0.7 → Proyecto 9). |

## 2. Cómo se usa

1. Cualquiera de las 2 cuentas de construcción de Álvaro (Proyecto 3 o 7), o el Proyecto 9
   (Carlitos), propone una entrada nueva al terminar algo que encaje en uno de los tres
   tipos de arriba.
2. Ninguna cuenta escribe el documento oficial directamente — la entrada se propone aquí,
   y Carlitos (Proyecto 2) decide cuándo ya está madura para trasladarla.
3. Cuando se traslada, se retira de "Anotaciones activas" (Sección 3) y pasa a "Ya
   graduadas" (Sección 4), con el documento de destino anotado — así queda rastro de dónde
   vive ahora.

## 3. Anotaciones activas

### ✅ Esto funciona así

| # | Hallazgo | Contexto | Fecha |
|---|---|---|---|
| — | *(vacío por ahora)* | | |

### ⚠️ Cuidado con esto

| # | Hallazgo | Contexto | Fecha |
|---|---|---|---|
| — | *(vacío por ahora)* | | |

### 🔲→✅ Se investigó y esto lo resuelve

| # | Hallazgo | Estado | Fecha |
|---|---|---|---|
| 1 | El CPT `novedades` podría no aparecer en el selector "Post Type" de Post Cards en Avada por faltar `'publicly_queryable' => true` en el `register_post_type()` (junto a `public => false`). Diagnóstico de una sesión anterior del Proyecto de Investigación, sin verificar todavía contra el PHP real actual. | 🔲 Pendiente de que Álvaro lo pruebe en Local y confirme el resultado — NO aplicar el fix en el PHP oficial hasta esa confirmación | Sin fecha confirmada |

## 4. Ya graduadas — trasladadas a documentación oficial

*(Ejemplos de referencia, con hallazgos reales ya confirmados en el proyecto, para que se
vea el patrón — no hace falta anotar estos de nuevo, ya viven en su documento de destino.)*

| # | Hallazgo | Documento destino | Fecha |
|---|---|---|---|
| 1 | Escribir un campo de una ACF Options Page con `update_option()` en vez de `update_field()` lo deja invisible para Avada Dynamic Content — hay que usar siempre `update_field()`. | `00_CORE.md` Sección 8 | 23 julio 2026 |
| 2 | "Slideshows" (Avada → Options) solo controla varias imágenes DENTRO de una misma entrada — no rota entre entradas distintas. Para eso hace falta Post Slider. | `GUIA_AVADA_LOCAL.md` Sección 9 / `CATALOGO_ELEMENTOS_AVADA.md` Sección 4 | 11 agosto 2026 |
| 3 | Los iframes de YouTube construidos dinámicamente por JS necesitan el atributo `referrerpolicy`, o falla la reproducción (Error 153), desde que YouTube exige un header Referer válido. | `00_CORE.md` Sección 8 | — |

## 5. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Confirmar la entrada #1 de la Sección 3 (`publicly_queryable`) contra Local real — es el
   primer caso activo real de este cuaderno.
2. Según se use en la práctica, revisar si el formato de tabla es cómodo para las cuentas
   de Álvaro, o conviene simplificarlo.

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Con qué frecuencia debería Carlitos revisar este cuaderno para reconciliar entradas maduras? | Sin cadencia fijada todavía — se decide con el uso real |

---

*Para la mayor gloria de Dios · tiritaito.com*

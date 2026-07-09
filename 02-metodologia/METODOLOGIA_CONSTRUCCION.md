# TIRITAITO.COM — Metodología de Construcción
**Diagnóstico heredado, inventario de consolidación y aplicación práctica a cada sección real de la web nueva**
*Actualizado tras la sesión de alcance de julio 2026 (`ALCANCE_WEB_NUEVA.md`) — sustituye la aplicación práctica sección-por-sección y la decisión de Hombres de Dios de la versión anterior*
*Audiencia: Hno A (aplicación técnica) · Hna C (para entender el porqué de cada decisión)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde una pregunta muy concreta, sección por sección: **"tengo esta pieza de contenido
decidida en `ALCANCE_WEB_NUEVA.md` — ¿dónde y cómo la construyo?"**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Cómo funciona Avada mecánicamente (Header Builder, Layouts, Global vs Guardado) | `GUIA_AVADA_LOCAL.md` — **este documento no repite ese árbol de decisión, lo aplica** |
| Qué secciones tiene la web y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| Qué contenido de la web vieja migrar, y cómo | `MIGRACION_CONTENIDO.md` |
| **Para una sección concreta ya decidida: dónde construirla y con qué patrón** | **Este documento** |

**Regla de uso:** antes de construir cualquier pieza nueva, aplica primero el árbol de
decisión completo de `GUIA_AVADA_LOCAL.md` Sección 8. Este documento asume ese árbol ya
conocido y solo lo aplica caso por caso.

---

## ✅ Nota resuelta — el diagnóstico original v1 ya apareció

El diagnóstico original de Hno C (`Diagnóstico original v1.md`) se localizó y se ha
cruzado línea por línea contra el resumen que `METODOLOGIA_WEB_NUEVA_v2` hacía de él.
**Sin contradicciones** — v2 lo resumió con fidelidad. De lo que aportaba de nuevo, esto
es lo que queda tras confirmarlo con el equipo:

1. **Incorporado** — evidencia técnica del Patrón C: el `<head>` actual ya carga
   `avada-swiper-md-css` condicionalmente (Sección 1.2).
2. **Resuelto — el teaser de "Ejército de Intercesores" en portada carga, pero lento.**
   No está roto; es un problema de rendimiento/optimización de imagen, no de código roto.
3. **Resuelto — el "Footer oficial" es un Global Layout heredado por todas las páginas**,
   sin duplicación. Sin riesgo, cerrado.
4. **Descartado a propósito** — la fuente "Inter" es un detalle menor; por decisión de
   Carlitos no se actualiza ningún documento solo por eso.

---

## 1. Diagnóstico heredado — resumen

*(Metodología original: 2 auditorías Lighthouse/PageSpeed + código fuente completo de la
página `ejercito-de-intercesores` + revisión estructural de `home`, `san-juan-pablo-ii`,
`rincon-de-nico`.)*

### 1.1 Lo que funciona bien — no reinventar

| Elemento | Estado |
|---|---|
| Rendimiento real de usuario (datos de campo) | LCP 2,4s · CLS 0,09 · TTFB 0,6s — bueno |
| Sistema de variables CSS `--tt-*` | Bien diseñado; el problema es que no siempre se usa |
| `fetch_feed()` server-side para el podcast | Sólido, sin proxies externos |
| Shortcode `[tt_podcast]` | Confirmado funcionando correctamente |
| Caché LiteSpeed + exclusión de `/wp-json/tiritaito/` | Correcta |

### 1.2 Los tres patrones de deuda técnica

| Patrón | Descripción | Ejemplo concreto |
|---|---|---|
| **A — Componentes reinventados** | Hasta tres sistemas de reproductor distintos haciendo lo mismo | `.pp-*`, `.mp-*`, `.hmds-*` — el accordion "¿Qué es X?" + vídeo copiado letra por letra en al menos dos páginas |
| **B — CSS/JS global cargándose donde no se usa** | Marcado "Run everywhere" por comodidad, no por necesidad real | `.tiritaito-santo` (~700 líneas) y `.tiritaito-libros` (~500 líneas) se cargan en páginas que no usan esas clases |
| **C — Funcionalidad nativa de Avada sustituida por código custom** | Se reinventó en JS/CSS lo que Avada ya resolvía nativamente | Menú flyout nativo forzado a `display:none` y sustituido por un hamburguesa a mano; Swiper.js cargado desde `unpkg.com` en cada página en vez del carrusel nativo. **Evidencia concreta:** el `<head>` actual ya carga `avada-swiper-md-css` condicionalmente |

**Causa raíz común:** todo el contenido se pegó directamente en el `post_content` de cada
página, reinventando CSS/JS cada vez, en vez de construir snippets globales reutilizables
o usar los elementos nativos de Fusion Builder. No es un problema de Avada — es un
problema de disciplina de componentización.

---

## 2. Inventario de componentes a consolidar

| Componente actual | Prefijo | Dónde se ha visto (web vieja) | Acción propuesta |
|---|---|---|---|
| Reproductor de podcast (RSS) | `.pp-*` | Global, `[tt_podcast]` | **Mantener** — funciona bien, es el patrón de referencia para todo lo nuevo |
| "Tiritaito Music" (con cola) | `.mp-*` | Ejército de Intercesores | Evaluar si `[tt_podcast]` cubre el caso; si necesita cola/playlist, mantenerlo pero como snippet global reutilizable |
| Mini reproductor de un track | `.hmds-*` | Ejército de Intercesores | **Eliminar** — sustituir por `[tt_podcast]` de un solo episodio |
| Accordion "¿Qué es X?" + vídeo | `toggle-ios` | Ejército de Intercesores, Rincón de Nico | **Guardado (no-global)** o `[tt_accordion]`, **nunca Global** (`GUIA_AVADA_LOCAL.md` Sección 8) — en la web nueva aplica a la "Introducción" del Ejército (Sección 3) |
| Menú hamburguesa custom | (sin prefijo `tt-`) | Al menos Ejército de Intercesores | Sustituir por Off Canvas Builder nativo (`GUIA_AVADA_LOCAL.md` Sección 9.1) |
| Sistema de Santos | `.tiritaito-santo` | Global, Hombres de Dios | Mantener el CSS, pero cargarlo solo donde se usa — nunca "Run everywhere". Ahora se combina con el método de Guardados de la Sección 4 |
| Sistema de Biblioteca | `.tiritaito-libros` | Global, sin páginas reales en la web vieja | ⚠️ **Actualizado:** en la web nueva, Biblioteca (Libros/Películas/Oraciones) ya es prioridad de v1 (`ALCANCE_WEB_NUEVA.md`) — este CSS deja de ser hipotético. Sigue aplicando la regla de cargarlo solo donde se usa |
| Carrusel Swiper (`unpkg.com`) | — | Varias páginas | Sustituir por Image Carousel / Avada Slider nativo |
| "Grupo de alabanza" | Sin confirmar | Home (web vieja) | ⚠️ **Actualizado:** en la web nueva ya no es widget de home — pasa a ser un apartado informativo dentro de **Qué hacemos** (`ALCANCE_WEB_NUEVA.md` Sección 4.C). Sigue sin auditar a nivel de código |
| Teaser de "Ejército de Intercesores" | — | Home (web vieja) | 🔲 **Revisar con Hna C:** la home nueva ya no tiene necesariamente un teaser propio de Ejército de Intercesores — ahora vive dentro del acceso general a la página **Tiritaito**. Confirmar si el teaser individual se mantiene o desaparece |

---

## 3. Aplicación práctica — sección por sección

Cruce entre el árbol de decisión de `GUIA_AVADA_LOCAL.md` y la nueva estructura de
`ALCANCE_WEB_NUEVA.md`. Responde "¿y esto en concreto dónde lo construyo?" para cada pieza
ya decidida.

### Página de inicio y Novedades

| Pieza | Dónde vive | Nota |
|---|---|---|
| Página de inicio — estructura | Fusion Builder puro | Accesos a las cinco páginas contenedoras + apartado Novedades arriba de todo |
| Novedades | Code Block con el snippet de Tips ya existente + contenido editorial de avisos | No es página ni entrada (`ALCANCE_WEB_NUEVA.md` Sección 4.B) — vive dentro del Layout de la home |

### Qué hacemos

| Pieza | Dónde vive | Nota |
|---|---|---|
| Seminarios — próximos | Página Avada estática (Fusion Builder) | 🔲 Pendiente el rediseño de carteles (más visual) y si se unifican con las fechas |
| Seminarios — vídeos pasados | Snippet ya existente (JSON/playlist, `tt_seminarios_json_url`) | 🔲 Método de integración con el nuevo formato visual sin definir — ver `ALCANCE_WEB_NUEVA.md` pregunta abierta #3 |
| Grupo de alabanza | Fusion Builder puro | Contenido informativo, sin snippets |
| Día de familias | Fusion Builder puro | Contenido informativo, sin snippets — sección nueva, sin precedente técnico en la web vieja |

### Conecta cada día

| Pieza | Dónde vive | Nota |
|---|---|---|
| Hora de la brisa, Mensaje de la Virgen, Lenguas | Snippets ya existentes, montar en Avada | Sin cambios |
| Misa | Snippet de lecturas DOCX ya existente | 🔲 Si absorbe el Salmo del día, revisar si el snippet necesita ampliarse o si el salmo se monta aparte dentro del mismo Layout |
| Tip | Snippet ya existente | Mismo snippet en dos ubicaciones: aquí y en Novedades |
| Habla por la palabra (cita aleatoria) | Lógica PHP server-side ya existente (generador de citas) | 🔲 Sin ubicación decidida — falta definir el mecanismo de compartir por QR/URL descrito en `ALCANCE_WEB_NUEVA.md` Sección 4.D |
| Elige tu santo | — | 🔲 Sin ubicación decidida |

### Tiritaito

| Pieza | Dónde vive | Nota |
|---|---|---|
| Tiritaito Music | Snippet ya existente (`.mp-*`) | Evaluar si se reduce (Sección 2) |
| Rincón de Nico | Snippet `[tt_podcast]` + accordion | Sin cambios |
| Charlas de la Biblia | Snippet `[tt_podcast]` | **Actualizado: vive en Tiritaito, ya no en Biblioteca** |
| Ejército de Intercesores — Introducción | **Guardado (no-global)** o `[tt_accordion]` | Mismo patrón que el resto de accordions "¿Qué es X?" |
| Ejército de Intercesores — Batalla de cada día (vídeo + intenciones + lenguas) | 🔲 Candidato a snippet PHP nuevo con lógica de fecha, similar al patrón de `wp_options` de Conecta cada día | El contenido cambia a diario — probablemente necesita clave(s) nueva(s) en `wp_options`. **Requiere coordinación previa** (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 6, regla 1) antes de tocar el contrato de datos de la PWA Creators |
| Ejército de Intercesores — Batalla de la semana | 🔲 Igual que arriba, con periodicidad semanal | Misma nota sobre coordinación previa |
| Ejército de Intercesores — Formulario de inscripción | Avada Forms (nativo) | Confirmado en `INFORME_ESTRATEGICO_2026_1.md` Sección 2.1.G |
| Ejército de Intercesores — Claves de intercesión | Fusion Builder puro | Contenido estático |
| Ejército de Intercesores — Testimonios | 🔲 Post Cards si son muchos, contenido estático si son pocos | Volumen sin confirmar |

**Principio de unidad de podcast** (`ALCANCE_WEB_NUEVA.md` Sección 5): Rincón de Nico y
Charlas de la Biblia pueden tener tono/estilo propio, pero el formato del reproductor debe
ser el mismo — ya resuelto técnicamente por el patrón `[tt_podcast]`, no hace falta nada
nuevo.

### Biblioteca

| Pieza | Dónde vive | Nota |
|---|---|---|
| Libros | 🔲 Pendiente aclarar qué es la PWA de biblioteca de libros mencionada en la sesión de alcance (`ALCANCE_WEB_NUEVA.md` pregunta abierta #5) antes de definir el Layout | No documentar el método hasta que se resuelva esa pregunta |
| Películas | 🔲 Plantilla compartida con Libros — depende de lo anterior | — |
| Oraciones | 🔲 Pendiente si es una entrada única densa o una estructura distinta (`ALCANCE_WEB_NUEVA.md` pregunta abierta #6) | Con los 7 tipos, aplica el mismo principio de unidad de presentación que en Tiritaito |

### Menú y Próximos eventos

| Pieza | Dónde vive | Nota |
|---|---|---|
| Menú / header | Off Canvas Builder nativo (`GUIA_AVADA_LOCAL.md` Sección 9.1) | Pendiente confirmar si hay submenús |
| Próximos eventos | 🔲 Sin resolver | Candidato: feature "Eventos" de Avada, ya activada en el Setup Wizard (`GUIA_AVADA_LOCAL.md` Sección 4.0.1) |

---

## 4. Hombres de Dios — actualizado: Layout + Elementos Guardados (reemplaza Dynamic Content)

⚠️ **Corrección respecto a la versión anterior de este documento.** Aquí se recomendaba
"Layout Content Section con Dynamic Content". La sesión de alcance de julio 2026
(`ALCANCE_WEB_NUEVA.md` Sección 4.H) **lo descarta explícitamente**, a petición directa de
Carlitos — no por un hallazgo técnico nuevo, sino porque el equipo, con años ya trabajando
en Avada, prefiere un método más flexible y menos automatizado.

### 4.1 El método confirmado

Un **Layout** único sirve de plantilla compartida — probablemente el envoltorio (Header,
Page Title Bar, Footer) que Avada aplica automáticamente a todas las entradas de Hombres
de Dios por condición. Dentro de ese envoltorio, el contenido de cada santo se construye
combinando **Containers, columnas o elementos Guardados (no-global) de la Avada Library**,
insertados y editados a mano, con el contenido específico de cada santo.

**La razón de fondo, no solo el método:** no todos los santos usan la misma combinación de
piezas — unos llevan audio, otros discursos, otros solo biografía. Dynamic Content asume
una estructura de campos uniforme (vía ACF) que se rellena igual en todas las instancias;
aquí la estructura varía santo a santo, así que Dynamic Content no encaja bien con la
realidad del contenido.

### 4.2 Lo que esto cambia respecto a la decisión anterior

| Aspecto | Con Dynamic Content (descartado) | Con Layout + Guardados (confirmado) |
|---|---|---|
| Consistencia de campos | Misma estructura para todos los santos | Cada santo puede combinar módulos distintos |
| Mantenimiento | Automático — cambiar el Layout cambia todos los santos a la vez | Manual — cada instancia se edita por separado si cambia el contenido |
| Valor de un CPT + ACF | Alto — automatiza el llenado de campos uniformes | Bajo — sin campos uniformes que rellenar automáticamente, ACF pierde buena parte de su utilidad aquí |
| Post Cards para el listado/portada | Compatible | Sigue siendo compatible — solo necesita Posts o CPT (con o sin ACF) del que tirar, independientemente de si el contenido interno usa Dynamic Content o Guardados |

### 4.3 Lo que sigue pendiente de definir en Local

| Pendiente | Nota |
|---|---|
| ¿Posts normales con categoría, o Custom Post Type? | Con Guardados en vez de Dynamic Content, la razón principal para un CPT+ACF (automatizar campos) ya no aplica con la misma fuerza — puede que Posts con categoría baste. La herramienta ACF sigue activada por si acaso (`GUIA_AVADA_LOCAL.md` Sección 4.0.1), pero no es necesaria por el método elegido |
| ¿Qué elementos Guardados se preparan de antemano como piezas reutilizables? | P.ej. "bloque de biografía", "bloque de audio", "bloque de discursos" — cada santo elige y edita los que le apliquen |
| Confirmar en Local que el Layout + Guardados funciona como se espera visualmente | 🔲 Pendiente de prueba práctica, no de documentación |

---

## 5. Checklists

### Antes de crear un módulo nuevo

- [ ] ¿Ya existe un elemento nativo de Fusion Builder que resuelva esto? (`GUIA_AVADA_LOCAL.md` Sección 9)
- [ ] ¿Ya existe un snippet global de Tiritaito con funcionalidad equivalente? (Sección 2 de este documento)
- [ ] Si se construye desde cero: ¿está pensado para reutilizarse — parámetros, no contenido fijo?
- [ ] Si es candidato a Avada Library: ¿Guardado o Global? (`GUIA_AVADA_LOCAL.md` Sección 8 — no son intercambiables)
- [ ] ¿El JS sigue el patrón condicional + IIFE? (`00_CORE.md`)
- [ ] ¿Usa `var(--tt-*)` en vez de hex sueltos?
- [ ] ¿Se ha probado en consola del navegador que no hay errores de elementos inexistentes?
- [ ] Si toca `wp_options` o el contrato de datos de la PWA Creators: ¿se ha coordinado con el equipo antes? (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 6)

### Antes de marcar un snippet "Run everywhere"

- [ ] ¿Se usa en más del 50% de las plantillas del sitio?
- [ ] Si la respuesta es "es más fácil que decidir dónde", **no marcarlo global** — es exactamente el Patrón B de la Sección 1
- [ ] ¿Se ha comprobado el peso que añade a páginas que no lo necesitan?

---

## 6. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Hno A: validar en Local, en este orden — Off Canvas Builder (menú) → Toggles (Introducción del Ejército) → Layout + Guardados para Hombres de Dios → snippets nuevos de "Batalla de cada día/semana" del Ejército de Intercesores
2. Coordinar con el equipo antes de crear las claves nuevas de `wp_options` que probablemente necesite la Batalla de cada día/semana del Ejército de Intercesores
3. Cuando se resuelvan las preguntas abiertas de `ALCANCE_WEB_NUEVA.md` sobre Biblioteca (PWA de libros, estructura de Oraciones), completar las filas correspondientes de la Sección 3 de este documento

**Preguntas abiertas propias de este documento:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Se mantiene el teaser individual de "Ejército de Intercesores" en la home, o desaparece al agruparse bajo Tiritaito? | Sección 2 |
| 2 | ¿Qué elementos Guardados conviene preparar de antemano para Hombres de Dios (biografía, audio, discursos...)? | Sección 4.3 |
| 3 | ¿Testimonios de intercesión: volumen aproximado, para decidir Post Cards vs contenido estático? | Sección 3, Tiritaito |

**Heredadas de `ALCANCE_WEB_NUEVA.md` (no se repiten aquí en detalle, solo se enlazan):**
Salmo del día dentro de Misa, ubicación de "Habla por la palabra" y "Elige tu santo",
integración de vídeos de seminarios pasados, "Canción del Ejército", naturaleza de la PWA
de libros, estructura de Oraciones, feature Eventos de Avada.

**Resuelto en esta revisión:** método técnico de Hombres de Dios (Layout + Guardados,
Dynamic Content descartado) · reestructuración completa de la aplicación práctica
sección-por-sección según el nuevo Alcance · Charlas de la Biblia reubicada de Biblioteca
a Tiritaito · Ejército de Intercesores desglosado en sus piezas reales.

---

*Para la mayor gloria de Dios · tiritaito.com*

# TIRITAITO.COM — Metodología de Construcción
**Diagnóstico heredado, inventario de consolidación y aplicación práctica a cada sección real de la web nueva**
*Fusiona: METODOLOGIA_WEB_NUEVA_v2.md (Secciones 1, 8, 10, 12) + Diagnóstico original v1 de Hno C (localizado y cruzado sin contradicciones — ver nota tras la Sección 0)*
*Audiencia: Hno A (aplicación técnica) · Hna C (para entender el porqué de cada decisión)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde una pregunta muy concreta, sección por sección: **"tengo esta pieza de contenido decidida por Hna C — ¿dónde y cómo la construyo?"**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Cómo funciona Avada mecánicamente (Header Builder, Layouts, Global vs Guardado) | `GUIA_AVADA_LOCAL.md` — **este documento no repite ese árbol de decisión, lo aplica** |
| Qué secciones tiene la web y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| Qué contenido de la web vieja migrar, y cómo | `MIGRACION_CONTENIDO.md` |
| **Para una sección concreta ya decidida: dónde construirla y con qué patrón** | **Este documento** |

**Regla de uso:** antes de construir cualquier pieza nueva, aplica primero el árbol de decisión completo de `GUIA_AVADA_LOCAL.md` Sección 8. Este documento asume ese árbol ya conocido y solo lo aplica caso por caso.

---

## ✅ Nota resuelta — el diagnóstico original v1 ya apareció

El diagnóstico original de Hno C (`Diagnóstico original v1.md`) se localizó y se ha cruzado línea por línea contra el resumen que `METODOLOGIA_WEB_NUEVA_v2` hacía de él. **Sin contradicciones** — v2 lo resumió con fidelidad. De lo que aportaba de nuevo, esto es lo que queda tras confirmarlo con el equipo:

1. **Incorporado** — evidencia técnica del Patrón C: el `<head>` actual ya carga `avada-swiper-md-css` condicionalmente (Sección 1.2).
2. **Resuelto — el teaser de "Ejército de Intercesores" en portada carga, pero lento.** No está roto; es un problema de rendimiento/optimización de imagen, no de código roto. Pasa de pregunta abierta a ítem de trabajo (Sección 2 y Sección 6).
3. **Resuelto — el "Footer oficial" es un Global Layout heredado por todas las páginas**, sin duplicación. Sin riesgo, cerrado.
4. **Descartado a propósito** — la fuente "Inter" es un detalle menor; por decisión de Carlitos no se actualiza ningún documento solo por eso.

---

## 1. Diagnóstico heredado — resumen

*(Metodología original: 2 auditorías Lighthouse/PageSpeed + código fuente completo de la página `ejercito-de-intercesores` + revisión estructural de `home`, `san-juan-pablo-ii`, `rincon-de-nico`.)*

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
| **C — Funcionalidad nativa de Avada sustituida por código custom** | Se reinventó en JS/CSS lo que Avada ya resolvía nativamente | Menú flyout nativo forzado a `display:none` y sustituido por un hamburguesa a mano; Swiper.js cargado desde `unpkg.com` en cada página en vez del carrusel nativo. **Evidencia concreta:** el `<head>` actual ya carga `avada-swiper-md-css` condicionalmente — Avada trae su propio Swiper interno, lo que refuerza que el carrusel nativo de Fusion Builder puede cubrir la necesidad sin la dependencia externa |

**Causa raíz común:** todo el contenido se pegó directamente en el `post_content` de cada página, reinventando CSS/JS cada vez, en vez de construir snippets globales reutilizables o usar los elementos nativos de Fusion Builder. No es un problema de Avada — es un problema de disciplina de componentización.

---

## 2. Inventario de componentes a consolidar

| Componente actual | Prefijo | Dónde se ha visto | Acción propuesta |
|---|---|---|---|
| Reproductor de podcast (RSS) | `.pp-*` | Global, `[tt_podcast]` | **Mantener** — funciona bien, es el patrón de referencia para todo lo nuevo |
| "Tiritaito Music" (con cola) | `.mp-*` | Ejército de Intercesores | Evaluar si `[tt_podcast]` cubre el caso; si necesita cola/playlist, mantenerlo pero como snippet global reutilizable, no atado a una sola página |
| Mini reproductor de un track | `.hmds-*` | Ejército de Intercesores | **Eliminar** — sustituir por `[tt_podcast]` de un solo episodio |
| Accordion "¿Qué es X?" + vídeo | `toggle-ios` | Ejército de Intercesores, Rincón de Nico | Ver Sección 3 — **Guardado (no-global)** o `[tt_accordion]`, **nunca Global** (`GUIA_AVADA_LOCAL.md` Sección 8) |
| Menú hamburguesa custom | (sin prefijo `tt-`) | Al menos Ejército de Intercesores | Sustituir por Off Canvas Builder nativo (`GUIA_AVADA_LOCAL.md` Sección 9.1) |
| Sistema de Santos | `.tiritaito-santo` | Global, Hombres de Dios | Mantener el CSS, pero cargarlo solo donde se usa — nunca "Run everywhere" |
| Sistema de Biblioteca | `.tiritaito-libros` | Global, sin páginas reales aún | Igual — no cargar hasta que exista contenido real (v2 del proyecto) |
| Carrusel Swiper (`unpkg.com`) | — | Varias páginas | Sustituir por Image Carousel / Avada Slider nativo — probar antes de eliminar la dependencia externa |
| "Grupo de alabanza" | Sin confirmar | Home | No auditado a nivel de código — pendiente |
| Galería "Próximos eventos" | Sin confirmar | Home | No auditado a nivel de código — candidato: Image Carousel o Avada Slider, pendiente confirmar en Local |
| Teaser de "Ejército de Intercesores" | — | Home | **Confirmado: no está roto, pero carga lento** — candidato a optimización de imagen (compresión, tamaño servido). Aplicar Lazy Load (`GUIA_AVADA_LOCAL.md` Sección 4.4) al reconstruir la home, no migrar la imagen tal cual |

---

## 3. Aplicación práctica — sección por sección

Cruce entre el árbol de decisión de `GUIA_AVADA_LOCAL.md` y `ALCANCE_WEB_NUEVA.md`. Esto responde "vale, ¿y esto en concreto dónde lo construyo?" para cada sección priorizada.

| Sección (Alcance) | Dónde vive | Nota |
|---|---|---|
| Página de inicio — estructura | Fusion Builder puro | Sin snippets nuevos salvo el widget de citas |
| Citas / Santo del día | Snippet PHP ya existente | Pendiente si además tiene URL propia (pregunta abierta en Alcance) |
| Seminarios — próximos | Página Avada estática (Fusion Builder) | Según recomendación ya aprobada en Alcance |
| Seminarios — archivo de pasados | Entradas WP (Posts) | Candidato a **Post Cards** de Avada Library — **compatible**, porque ya se decidió que sean Posts, no páginas sueltas (ver Sección 4) |
| Seminarios — reproductor YT | Snippet ya existente | Sin cambios, solo insertar el shortcode |
| Ejército de Intercesores — accordion "¿Qué es X?" | **Guardado (no-global)** o `[tt_accordion]` — **nunca Global** | Hallazgo más importante de la Metodología v2 — no construir como sugería el diagnóstico original |
| Ejército de Intercesores — el resto de la sección | Depende de responder qué es exactamente (pregunta bloqueante en Alcance) | Bloqueado por decisión de Hna C, sin relación con este documento |
| Conecta cada día | Snippet ya existente, montar en Avada | Sin cambios |
| Tiritaito Music | Snippet ya existente (`.mp-*`) | Evaluar si el reproductor con cola puede reducirse (Sección 2) |
| Charlas de la Biblia | Snippet `[tt_podcast]` | Sin cambios — es el patrón de referencia para nuevos shortcodes |
| Rincón de Nico | Snippet `[tt_podcast]` + mismo accordion que Ejército de Intercesores | El accordion debe ser el mismo componente reutilizado una vez, no una segunda copia |
| Hombres de Dios — ficha de santo y portada/listado | **Layout Content Section con Dynamic Content** — ver Sección 4, actualizada | Decisión de Carlitos: se construye vía Layouts de Avada, no páginas sueltas ni Guardado — supera la recomendación "empezar simple sin CPT" de `ALCANCE_WEB_NUEVA.md`, a reconciliar cuando se revise ese documento con Hna C |
| Oraciones (v1, solo texto) | Fusion Builder puro | Sin snippets — coherente con "sin audio en v1" |
| Menú / header | Off Canvas Builder nativo (`GUIA_AVADA_LOCAL.md` 9.1) | Sustituye el hamburguesa a mano — pendiente confirmar si hay submenús |
| "Grupo de alabanza" (mapa + horario, home) | Sin auditar — pendiente | Pregunta abierta desde el diagnóstico original |
| Galería "Próximos eventos" (home) | Candidato: Image Carousel o Avada Slider | Sin auditar el código actual — pendiente probar en Local |

---

## 4. Hombres de Dios — resuelto vía Layouts de Avada (actualización)

**Decisión de Carlitos:** "Hombres de Dios" se construye de nuevo mediante **Layouts de Avada** (Layout Content Section con Dynamic Content) — no páginas individuales sueltas, no Avada Library Guardado. Esto resuelve limpiamente el hallazgo que este documento tenía abierto sobre Post Cards.

✅ **Por qué esto resuelve el problema:** un Layout Content Section con Dynamic Content necesita, igual que Post Cards, un tipo de contenido de WordPress del que tirar (Posts o un Custom Post Type) — nunca páginas sueltas. Al construir directamente sobre esa base, Post Cards **sí es compatible** para el listado/portada, sin necesidad de la solución intermedia que se planteaba antes (convertir páginas en Posts con categoría).

**Lo que queda pendiente de definir en Local, no de investigación documental:**

| Pendiente | Nota |
|---|---|
| ¿Posts normales con categoría, o Custom Post Type con ACF? | Determina la complejidad de mantenimiento — ver `MIGRACION_CONTENIDO.md` Sección 4. La herramienta ya está lista: Hno A activó "Herramientas de desarrollo (ACF)" en el Setup Wizard del 7 julio 2026 (`GUIA_AVADA_LOCAL.md` Sección 4.0.1) — no resuelve la decisión, pero quita un paso técnico si se opta por CPT |
| Campos de la "ficha de santo" (nombre, biografía, oración, fecha de fiesta, podcast asociado...) | Se define al revisar `ALCANCE_WEB_NUEVA.md` con Hna C |
| Confirmar en Local que el Layout Content Section + Dynamic Content + Post Cards funcionan juntos como se espera | Sigue siendo 🔲 pendiente de prueba práctica, no de documentación |

Esto también **supera la recomendación de "empezar simple, sin CPT"** que `ALCANCE_WEB_NUEVA.md` tenía anotada para v1 — el equipo salta directamente a la ruta más estructurada. Queda anotado para reconciliar formalmente cuando se revise ese documento con Hna C.

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

### Antes de marcar un snippet "Run everywhere"

- [ ] ¿Se usa en más del 50% de las plantillas del sitio?
- [ ] Si la respuesta es "es más fácil que decidir dónde", **no marcarlo global** — es exactamente el Patrón B de la Sección 1
- [ ] ¿Se ha comprobado el peso que añade a páginas que no lo necesitan?

---

## 6. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Hno A: validar en Local, en este orden — Off Canvas Builder (menú) → Toggles (accordion) → Image Carousel/Avada Slider → Layout Content Section + Dynamic Content + Post Cards para Hombres de Dios
2. Hna C + Carlitos: revisar `ALCANCE_WEB_NUEVA.md` y definir los campos de la ficha de santo, ahora que el formato (Layouts) ya está decidido
3. Al reconstruir la home, optimizar la imagen del teaser de "Ejército de Intercesores" (compresión + Lazy Load) — no migrarla tal cual, ya se confirmó que carga lenta

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Posts normales con categoría o Custom Post Type con ACF para "Hombres de Dios"? | Determina la complejidad de mantenimiento del Layout (Sección 4) |
| 2 | ¿El accordion "¿Qué es X?" lo mantiene Hno A por código, o Hna C/editores visualmente? | Determina si se construye como `[tt_accordion]` (shortcode) o como elemento Guardado de Avada Library |
| 3 | "Grupo de alabanza" en la home — código fuente sin auditar | Necesario para confirmar si Image Carousel/Avada Slider replican el comportamiento actual |

**Resuelto desde la última versión:** diagnóstico original v1 localizado y cruzado (sin contradicciones) · teaser de portada confirmado como problema de rendimiento, no de código roto · "Footer oficial" confirmado como Global Layout heredado, sin duplicación.

---

*Para la mayor gloria de Dios · tiritaito.com*

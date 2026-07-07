# ALCANCE WEB NUEVA — TIRITAITO.COM
**Documento de decisión para reunión semanal del equipo**
*Versión 1.0 — junio 2026 · Aprobación: Hna C*
*Ad maiorem Dei gloriam*

---

> **Regla de oro de este documento:**
> Lo que no está aquí no entra en la primera versión.
> Si algo nuevo surge durante el desarrollo, se anota y se evalúa para v2.
> Hna C tiene la última palabra sobre qué entra y qué no.

---

## INVENTARIO DE SECCIONES — Por prioridad

*Orden aprobado por Hna C. Mayor número = menor urgencia para el lanzamiento.*

| # | Sección | Prioridad | Estado técnico | Decisión previa necesaria |
|---|---|---|---|---|
| 1 | Página de inicio | 🔴 Crítica | Diseño pendiente | Sí — ver sección A |
| 2 | Seminarios | 🔴 Crítica | Parcialmente lista | Sí — ver sección B |
| 3 | Ejército de intercesores | 🔴 Crítica | **Sin definir** | **Sí — urgente, ver sección C** |
| 4 | Conecta cada día | 🔴 Crítica | ✅ Lista (widgets ya existen) | No |
| 5 | Tiritaito Music | 🟡 Alta | ✅ Lista (widget YT ya existe) | No |
| 6 | Charlas de la Biblia | 🟡 Alta | ✅ Lista (player podcast ya existe) | No |
| 7 | Rincón de Nico | 🟡 Alta | ✅ Lista (player podcast + YT) | No |
| 8 | Hombres de Dios | 🟡 Alta | Estructura de datos pendiente | Sí — ver sección D |
| 9 | Oraciones | 🟢 Media | Depende del formato elegido | Sí — ver sección E |
| — | Biblioteca | 🔵 V2 | Sin definir | Posponer a v2 |
| — | Citas / Santo del día | ⚠️ Especial | Ver nota | Ver sección F |

---

## ANÁLISIS POR SECCIÓN

### Sección A — PÁGINA DE INICIO

**Qué tiene ahora:**
- Hero con imagen
- Generador de citas aleatorias / santo del día (la gente entra específicamente a por esto)
- Accesos a secciones principales

**Lo que hay que decidir en la reunión:**

1. ¿El generador de citas / santo del día tiene su **propia URL** además de estar en inicio?
   - *(Recomendación técnica: SÍ. Si la gente entra específicamente a buscarlo, necesita una URL propia que puedan guardar en favoritos o compartir. Esto es una feature de fidelización.)*

2. ¿La página de inicio muestra el contenido del día de "Conecta cada día" a modo de preview, o son secciones completamente separadas?

3. ¿Hay un acceso visible a la App de la comunidad desde la página de inicio?

**Estado técnico:** la lógica del generador de citas es PHP server-side. Se migra limpio como snippet. El diseño visual necesita wireframe de Hna C.

---

### Sección B — SEMINARIOS

**Qué tiene ahora:**
- Información sobre los retiros actuales y próximos
- Reproductor de YouTube con vídeos de retiros pasados (feed `tt_seminarios_json_url`)

**Lo que hay que decidir:**

1. ¿La información de cada seminario (fechas, lugar, precio, inscripción) vive como **página estática** de Avada, o como **entradas de WordPress** con plantilla?
   - *(Recomendación: página estática de Avada para los próximos, archivo de entradas para los pasados. Así los editores pueden añadir seminarios pasados sin tocar Avada.)*

2. ¿El vídeo de cada retiro pasado se añade al JSON de seminarios mediante la herramienta Generador JSON, o tiene otra mecánica?

**Estado técnico:** el widget del reproductor YT ya existe. Solo hay que integrarlo en la nueva página.

---

### Sección C — EJÉRCITO DE INTERCESORES ⚠️

**Esta sección necesita definición urgente. Es prioridad 3 pero no existe aún.**

Antes de que Hno A abra Avada, hay que responder:

1. **¿Qué es exactamente el Ejército de Intercesores?** ¿Qué hace la persona cuando entra a esa página?

2. **¿Es una página de información** (quiénes somos, qué hacemos, cómo unirse) o **tiene funcionalidad interactiva** (formulario de unión, intenciones de oración, lista de intercesores)?

3. **Si hay formulario de unión:** ¿qué datos recoge? ¿Dónde van esos datos? ¿Email, base de datos, Jetpack Forms, otro?

4. **¿Hay contenido actualizado** en esta sección o es mayormente estático?

> **Riesgo:** si se empieza a construir esta sección sin responder estas preguntas, hay que rehacer el trabajo. Es mejor definirla bien en la reunión y empezarla la semana siguiente.

---

### Sección D — HOMBRES DE DIOS

**Lo que existe:**
- Algunos santos con **solo contenido permanente** (biografía, oración, historia)
- Otros con **contenido permanente + contenido variable** (reflexiones que se actualizan)

**Lo que hay que decidir:**

1. ¿Cómo se organiza la información de cada santo? ¿Qué campos tiene una "ficha de santo"?
   - Ejemplo: nombre, imagen, biografía corta, oración, fecha de fiesta, podcast asociado, vídeos...

2. Para los santos con contenido variable: ¿quién lo actualiza? ¿Los editores desde la Creators App o directamente desde WordPress?

3. ¿Hay una portada de "Hombres de Dios" con todos los santos, o cada santo tiene su propia URL?

**Decisión técnica que depende de esto:** si cada santo tiene una "ficha" con campos estructurados, conviene crear un tipo de contenido personalizado (Custom Post Type) en WordPress. Si es más sencillo, páginas individuales de Avada con el mismo template bastan.

> **Recomendación para v1:** empezar simple. Una página de Avada por santo, organizadas con el sistema de Layouts. La complejidad (CPT, ACF) se añade en v2 si hace falta.

---

### Sección E — ORACIONES

**Propuesta de unificación:** una sola página "Oraciones" que agrupa:
Lenguas · Rosario · Vía Crucis · Salmos · Adoraciones · Posesión Mariana · Novenas

**Lo que hay que decidir:**

1. ¿Cada oración es **solo texto** o tiene **audio** también?

2. ¿Las oraciones cambian con el tiempo o son contenido estático permanente?

3. ¿La "posesión mariana" y las "novenas" son textos fijos o rotan (novena del mes, etc.)?

**Recomendación para v1:** empezar con páginas de texto + imágenes, sin audio. El audio se añade en v2 si las métricas muestran que es necesario. Esto reduce drásticamente el tiempo de construcción.

---

### Sección F — CITAS ALEATORIAS / SANTO DEL DÍA ⚠️ FEATURE ESTRATÉGICA

**Por qué merece atención especial:**
La gente entra a la web explícitamente a por esto. Es una feature de fidelización que ninguna app grande de oración tiene exactamente igual. Es un activo de Tiritaito.

**Decisiones:**

1. ¿Queda en la página de inicio únicamente, o tiene **su propia URL** (ej: `tiritaito.com/blog/cita-del-dia`)?
   - *(Recomendación firme: que tenga su propia URL. La gente la guardará en favoritos y la compartirá.)*

2. ¿"Citas aleatorias" y "Santo del día" son la misma feature o dos separadas?

3. ¿Las citas vienen de una base de datos interna o de una fuente externa?

4. ¿El santo del día cambia cada día automáticamente (lógica PHP por fecha) o es manual?

**Nota técnica:** la lógica del santo del día por fecha es PHP puro, sin base de datos externa. Es un snippet de Code Snippets de unas 50 líneas. No es complejo de implementar, pero hay que definirlo bien antes de hacerlo.

---

## SECCIONES TÉCNICAMENTE LISTAS (sin decisiones previas)

Estas cuatro secciones pueden empezar a construirse en Avada desde el primer día, porque la lógica ya existe en snippets de la web actual:

### Conecta cada día
- Los widgets (Virgen, Brisa, Homilía, Lenguas, Tips) ya leen de `wp_options` via REST API
- Solo hay que crear la página en Avada e insertar los shortcodes
- La PWA Creators ya los actualiza

### Tiritaito Music
- El widget del reproductor YouTube ya existe y lee de `tt_youtube_json_url`
- Solo hay que crear la página en Avada e insertar el shortcode

### Charlas de la Biblia (Hno Tute)
- El player de podcast ya existe con `[tt_podcast feed="URL"]`
- Solo hay que crear la página en Avada y pegar el shortcode
- Si tiene vídeo de YouTube además del audio, se añade un bloque de YT de Avada encima

### Rincón de Nico
- Igual que Charlas de la Biblia
- Shortcode `[tt_podcast]` para el feed RSS de cuentos
- Bloque YT de Avada para el vídeo opcional
- El diseño visual puede ser más colorido y orientado a niños (Hna C define esto)

---

## RECOMENDACIÓN DE V1 vs V2

### V1 — Para el lanzamiento

| Sección | Incluir en v1 | Condición |
|---|---|---|
| Página de inicio | ✅ | Definir qué muestra (reunión) |
| Conecta cada día | ✅ | Sin condiciones — lista |
| Tiritaito Music | ✅ | Sin condiciones — lista |
| Charlas de la Biblia | ✅ | Sin condiciones — lista |
| Rincón de Nico | ✅ | Sin condiciones — lista |
| Seminarios | ✅ | Definir estructura de retiros (reunión) |
| Ejército de intercesores | ✅ | **Solo si se define en la próxima reunión** |
| Hombres de Dios | ✅ versión simple | Páginas individuales sin CPT |
| Oraciones | ✅ versión simple | Solo texto, sin audio |
| Citas / Santo del día | ✅ | Definir si tiene URL propia (reunión) |

### V2 — Post-lanzamiento

| Sección | Por qué posponer |
|---|---|
| Biblioteca | Sin formato definido, no es urgente |
| Audio en Oraciones | Añadir según métricas de uso |
| CPT para Hombres de Dios | Solo si la estructura simple no escala |
| Funcionalidad interactiva en Ejército | Si la v1 es solo información |

---

## DECISIONES DE LA REUNIÓN SEMANAL

Estas son las preguntas que Hna C tiene que responder para desbloquear el desarrollo:

| # | Pregunta | Bloquea a |
|---|---|---|
| 1 | ¿Qué es exactamente el Ejército de Intercesores? | Sección C |
| 2 | ¿Las citas aleatorias tienen su propia URL? | Sección F |
| 3 | ¿Oraciones v1 es solo texto (sin audio)? | Sección E |
| 4 | ¿Hombres de Dios v1 son páginas simples (sin CPT)? | Sección D |
| 5 | ¿Seminarios: páginas estáticas o entradas WP? | Sección B |
| 6 | ¿La página de inicio muestra preview de Conecta cada día? | Sección A |

---

## LO QUE ESTE ALCANCE NO INCLUYE

Para que quede explícito — estas cosas están fuera de la v1 de la web nueva:

- **Estrategia de plataformas** (YouTube, Spotify, Apple Music, WhatsApp) — reunión separada
- **Biblioteca** — sin formato definido
- **Funcionalidad de usuario registrado** — si aplica, es v3 o más
- **WPMobile.app** — proyecto paralelo independiente
- **Tiritaito for Creators** — continúa en paralelo, se integra automáticamente

---

## SIGUIENTE PASO INMEDIATO

Una vez que la reunión semanal responda las 6 preguntas de arriba:

1. Hno A abre Local by Flywheel y configura la base de Avada (Fase 1 del informe estratégico)
2. Hno C documenta las decisiones en este archivo (actualiza v1.1)
3. Hna C revisa el wireframe de la página de inicio antes de que Hno A la construya

---

*Para la mayor gloria de Dios · tiritaito.com*
*Versión aprobada por: _______________ · Fecha: _______________*

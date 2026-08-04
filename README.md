# tiritaito-docs
**Repositorio de documentación técnica y de producto para la reconstrucción de Tiritaito.com**

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## Qué es este repositorio

Toda la documentación que el equipo usa para construir la Web Nueva de Tiritaito.com, en un solo sitio, sin fragmentación. Cada documento tiene **un dueño de contenido, una audiencia y una función clara** — si algo parece que debería estar en dos sitios a la vez, es una señal de que hay que fusionar, no duplicar.

**Regla de mantenimiento:** se edita el documento existente, no se crean copias con sufijo de versión (`_v2`, `_final`, `_nuevo`). El historial de versiones lo lleva git, no el nombre del archivo. **Excepciones documentadas:**
- El HTML de Tiritaito for Creators V2 (`apps/v2/tiritaito-creators-v2-01.html`) es un caso especial — su nombre de archivo es siempre fijo por decisión de Carlitos (26 julio 2026); el número real de versión vive en el footer del HTML y en `CHANGELOG-v2-web-nueva.md`, ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 2.
- Los archivos con sufijo `.matt.` (ej. `00_CORE.matt.md`) son copias de trabajo deliberadas con datos ficticios, para colaboración externa vía Codex — no son versiones antiguas ni duplicados accidentales. Ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11.

---

## Estructura

```
tiritaito-docs/
├── README.md                              ← este documento
├── 00-nucleo-tecnico/
│   ├── 00_CORE.md
│   ├── 00_CORE.matt.md                      ⚠️ copia con placeholder — colaboración externa
│   ├── 02_REF_PODCAST.md
│   ├── 04_ENTORNO_LOCAL.md
│   ├── 04_ENTORNO_LOCAL.matt.md             ⚠️ copia con placeholder
│   ├── TIRITAITO_FOR_CREATORS_VERSIONS.md   (absorbe 01_CREATORS_APP.md, retirado 26/07/2026)
│   └── TIRITAITO_FOR_CREATORS_VERSIONS.matt.md  ⚠️ copia con placeholder
├── 01-producto/
│   └── ALCANCE_WEB_NUEVA.md
├── 02-metodologia/
│   ├── METODOLOGIA_CONSTRUCCION.md
│   └── MIGRACION_CONTENIDO.md
├── 03-guias-practicas/
│   └── GUIA_AVADA_LOCAL.md
├── 04-vision-y-equipo/
│   ├── ARQUITECTURA_Y_ROADMAP.md
│   ├── ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md   ← incluye Sección 11: sistema Matt/Codex
│   └── INSTRUCCIONES_PROYECTOS_CLAUDE.md       ← nuevo, 2 agosto 2026 — texto literal de instrucciones de todos los Proyectos
├── apps/
│   ├── v1/
│   │   ├── tiritaito-creators-v1-07.html
│   │   └── CHANGELOG-v1-web-vieja.md
│   └── v2/
│       ├── tiritaito-creators-v2-01.html            ← nombre SIEMPRE fijo, se sobreescribe
│       ├── tiritaito-creators-v2-01.matt.html       ⚠️ copia con placeholder
│       ├── CHANGELOG-v2-web-nueva.md
│       ├── snippet-tt-creators-endpoint-central.php  ← PHP del endpoint (real y completo)
│       └── snippet-tt-creators-endpoint-central.matt.php  ⚠️ copia con placeholder, sanitización literal
└── historico/
    ├── INFORME_ESTRATEGICO_2026_1.md
    ├── INVESTIGACION_HERRAMIENTAS_2026.md
    ├── METODOLOGIA_WEB_NUEVA_v2.md
    ├── Diagnóstico original v1.md
    ├── CONFIGURACION_PROYECTOS_CLAUDE_Y_GITHUB.md
    └── ALCANCE_WEB_NUEVA_v1.md
```

⚠️ **Cambio de estructura (2 de agosto de 2026):** `COLABORACION_EXTERNA_CODEX.md` **no existe como documento independiente** — se creó brevemente y se retiró en la misma sesión de trabajo. Su contenido completo vive ahora dentro de `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11, para no tener tres documentos de coordinación (roles/cuentas, instrucciones, colaboración externa) cuando dos bastan. Si en algún momento aparece una referencia suelta a `COLABORACION_EXTERNA_CODEX.md` en algún documento antiguo, es un enlace obsoleto — corregir a `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11.

---

## Índice de documentos

| Documento | Carpeta | Propósito — responde a... | Audiencia principal | Mantiene actualizado | Frecuencia |
|---|---|---|---|---|---|
| `00_CORE.md` | `00-nucleo-tecnico/` | Stack técnico, endpoints, convenciones de código, mapa de `wp_options`/ACF/CPT | Hno A | Hno A | Baja — cambia solo si cambia el stack |
| `02_REF_PODCAST.md` | `00-nucleo-tecnico/` | Arquitectura del sistema de podcast, shortcode `[tt_podcast]` | Hno A | Hno A | Baja |
| `04_ENTORNO_LOCAL.md` | `00-nucleo-tecnico/` | Constantes y particularidades del entorno Local (distinto de producción) | Hno A | Hno A | Media — reescrito 26/07/2026 con la auth real confirmada |
| `TIRITAITO_FOR_CREATORS_VERSIONS.md` | `00-nucleo-tecnico/` | Qué es y cómo funciona la PWA de editores, V1 vs V2, dónde vive cada archivo en GitHub, formato de changelog, alcance confirmado por versión, especificación técnica de cada módulo | Hno A | Hno A | Media — cada versión nueva de la app. Absorbe desde el 26/07/2026 lo que antes cubría `01_CREATORS_APP.md` |
| `ALCANCE_WEB_NUEVA.md` | `01-producto/` | Qué secciones tiene la web nueva y con qué prioridad | Hna C | Hna C | Alta mientras se decide el alcance |
| `METODOLOGIA_CONSTRUCCION.md` | `02-metodologia/` | Diagnóstico técnico heredado + dónde construir cada pieza de contenido ya decidida | Hno A, Hna C | Hno C (investigación) | Media |
| `MIGRACION_CONTENIDO.md` | `02-metodologia/` | Qué contenido de la web vieja migrar, cuál recrear, plan de SEO | Hna C, Hno A, Carlitos | Hno C (investigación) | Media — vivo mientras avanza la revisión de contenido |
| `GUIA_AVADA_LOCAL.md` | `03-guias-practicas/` | Referencia completa de Avada + Local: licencia, Global Options, Header/Footer Builder, Layouts, elementos nativos, ACF, principio de Responsive y de mínimo código | Hno A | Hno C (investigación) | Baja una vez estabilizada |
| `ARQUITECTURA_Y_ROADMAP.md` | `04-vision-y-equipo/` | FODA, política sobre la web vieja (incluida su fecha de caducidad), fases del proyecto, glosario | Carlitos | Hno C (investigación) | Baja |
| `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | `04-vision-y-equipo/` | Roles, mapa de Proyectos de Claude, ámbito de cada uno, GitHub, WPMobile.app, Search Console, **y sistema completo de colaboración externa Matt/Codex (Sección 11)** | Carlitos | Hno C (investigación) | Media — cambia cuando cambia la organización del equipo o el sistema de Matt |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | `04-vision-y-equipo/` | Texto exacto y completo de las "Instrucciones personalizadas" de cada uno de los 8 Proyectos de Claude — listo para copiar y pegar en claude.ai, sin tablas ni contexto alrededor | Carlitos, cada dueño de cuenta | Hno C (investigación) | Media — cada vez que cambia el criterio de trabajo de un Proyecto |

**`historico/`** — documentos superados, conservados como referencia de cómo evolucionaron las decisiones. No se editan ni se usan como fuente de verdad; si algo de ahí sigue siendo válido, ya está incorporado en la versión activa correspondiente.

**`apps/`** — código fuente de Tiritaito for Creators (HTML autocontenido), su changelog y, desde el 26 de julio de 2026, el snippet PHP del endpoint central que lo sirve — los tres juntos en `v1/` (web vieja, solo mantenimiento) y `v2/` (web nueva, desarrollo activo). Desde el 2 de agosto de 2026, algunos de estos archivos tienen también una copia `.matt.*` con placeholders para colaboración externa. Ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` para el detalle de cada versión y `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11 para el sistema de copias.

---

## Cómo empezar, según quién seas

| Si eres... | Lee primero |
|---|---|
| Nuevo en el equipo (interno) | Este README → `ARQUITECTURA_Y_ROADMAP.md` → `ALCANCE_WEB_NUEVA.md` |
| Colaborador externo (Codex/Matt) | El documento de bienvenida específico (ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11.6) — no este README completo |
| Hno A, sesión de código en Local | `00_CORE.md` + `04_ENTORNO_LOCAL.md` + el documento específico de tu tarea |
| Hno A, sesión de Avada/maquetación | `GUIA_AVADA_LOCAL.md` + `METODOLOGIA_CONSTRUCCION.md` |
| Hno A, sesión de Tiritaito for Creators (V1 o V2) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` |
| Hna C, decisión de producto | `ALCANCE_WEB_NUEVA.md` |
| Hna C, revisando qué migrar | `MIGRACION_CONTENIDO.md` |
| Carlitos, coordinación general | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` |
| Carlitos, coordinación de Matt/Codex | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11 |
| Cualquiera que necesite pegar instrucciones en un Proyecto de Claude | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` |

---

## Estado global — pendientes que cruzan varios documentos

*(Panel de control rápido — el detalle de cada uno vive en su documento correspondiente.)*

| Pendiente | Dónde se resuelve | Bloquea a |
|---|---|---|
| Revisión final de `ALCANCE_WEB_NUEVA.md` con Hna C | `ALCANCE_WEB_NUEVA.md` | Fase 2 de `ARQUITECTURA_Y_ROADMAP.md`; método definitivo de `MIGRACION_CONTENIDO.md` |
| ✅ Snippet PHP real y completo del endpoint central — obtenido y subido a `apps/v2/` (26/07/2026) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 6-7 | — |
| Confirmar con Hno A tres avisos del PHP real: sin límite de peticiones, sin validación de tipo/tamaño en subidas, sin Biblioteca ni gestión de entradas | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 7.1 | Decidir si se restauran o si el sistema se queda así a propósito |
| Confirmar en Local que el método **Layout + Elementos Guardados** funciona visualmente para "Hombres de Dios" | `METODOLOGIA_CONSTRUCCION.md` Sección 4 | Construcción de esa sección |
| Confirmar en Local si **Post Cards** cubre el listado de "Seminarios pasados" y la portada de "Hombres de Dios" (a diferencia de Novedades, si necesitan filtrar de verdad, sí haría falta el hook) | `GUIA_AVADA_LOCAL.md` Sección 19, pregunta 2 | Construcción de esas dos piezas |
| Montar Post Cards + Dynamic Content de **Novedades** en Avada (backend ya confirmado y probado) | `METODOLOGIA_CONSTRUCCION.md` Sección 3 | Que Novedades se vea en la web pública |
| Montar Dynamic Content de **Devocional** (Virgen, Brisa, Homilía, Lenguas) en Avada (backend ACF ya confirmado parcialmente) | `METODOLOGIA_CONSTRUCCION.md` Sección 3 | Que Conecta cada día se vea en la web pública |
| Retirar de la app la UI de **"Tip del día"** (eliminado por decisión, 26/07/2026, pero todavía construido en el HTML real) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 8 | Que la decisión se refleje de verdad, no solo en los documentos |
| Añadir el input de **`titulo`** al formulario de Novedades en la app (el backend ya lo admite) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 6 | Que el campo se pueda usar de verdad |
| Quitar de la app el input de **fecha de Homilía-texto** (decisión: nunca lleva fecha) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 7 | — |
| Crear la cuenta de Repuesto y conectar GitHub | ✅ Resuelto (26/07/2026) — dos cuentas de repuesto ya creadas y configuradas para el Proyecto 3, ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2 | — |
| Política de licencia de WPMobile.app en sitio de desarrollo | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 7 | Cualquier prueba de la app contra el entorno Local |
| Alcance completo de V2 de Tiritaito for Creators (solo Novedades y Devocional parcial confirmados por ahora) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 5 | Construcción de cualquier módulo nuevo más allá de esos dos |
| Dar acceso de GitHub a `apps/v2/` completa (HTML + PHP + changelog) a los Proyectos 2, 3 y 5, cada uno con el nivel de acceso que le corresponde | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.2 y 3 | Que cada proyecto pueda verificar el estado real sin depender de que alguien pegue el archivo a mano |
| Confirmar si `01_CREATORS_APP.md` tenía contenido real en GitHub que no llegara a este Proyecto de Investigación antes de darlo por eliminado | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 0 | Eliminación definitiva de ese archivo del repositorio |
| V1 desaparece en cuanto la Web Nueva sea oficial — sin fecha todavía | `ARQUITECTURA_Y_ROADMAP.md` · `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.1 · `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 1 | Archivado del Proyecto 1 y de la mitad V1 del Proyecto 5, el día que llegue |
| **Reestructuración de instrucciones (2/08/2026): `INSTRUCCIONES_PROYECTOS_CLAUDE.md` creado, `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` reescrito con Sección 11 (Matt) absorbida — ambos documentos generados, pendientes de subir a GitHub** | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 3 · `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | Que el repositorio real refleje la nueva estructura |
| ⚠️ **Sin verificar de forma fiable: si el bloque de instrucciones del Proyecto 2 en `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 2 coincide exactamente, carácter por carácter, con lo que está pegado ahora mismo en la configuración real de claude.ai.** Un intento de comprobar esto dentro de la sesión de trabajo no fue una verificación real (se comparó el documento contra una reescritura de memoria, no contra la fuente real) — sigue pendiente una comprobación genuina: abrir la Configuración del Proyecto 2 en claude.ai y comparar visualmente contra el documento | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 9 | Confianza real en que el documento es la fuente de verdad, no solo una copia que se asume correcta |
| Repegar en claude.ai las instrucciones ampliadas de Proyecto 3 y Proyecto 5 (con los bloques de generación de copia `.matt.*`) | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Secciones 3 y 5 | Que Hno A reciba de verdad el nuevo criterio |
| Dar a Matt acceso de colaborador al repositorio, con permisos de escritura | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11.8 | Que el sistema de colaboración externa quede operativo |
| Decidir si Matt tiene acceso a `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` e `INSTRUCCIONES_PROYECTOS_CLAUDE.md` tal cual, dado que contienen coordinación interna del equipo | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11.9 | Alcance exacto de qué ve Matt |

---

*Para la mayor gloria de Dios · tiritaito.com*

# tiritaito-docs
**Repositorio de documentación técnica y de producto para la reconstrucción de Tiritaito.com**

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## Qué es este repositorio

Toda la documentación que el equipo usa para construir la Web Nueva de Tiritaito.com, en un solo sitio, sin fragmentación. Cada documento tiene **un dueño de contenido, una audiencia y una función clara** — si algo parece que debería estar en dos sitios a la vez, es una señal de que hay que fusionar, no duplicar.

**Regla de mantenimiento:** se edita el documento existente, no se crean copias con sufijo de versión (`_v2`, `_final`, `_nuevo`). El historial de versiones lo lleva git, no el nombre del archivo.

---

## Estructura

```
tiritaito-docs/
├── README.md                              ← este documento
├── 00-nucleo-tecnico/
│   ├── 00_CORE.md
│   ├── 01_CREATORS_APP.md
│   ├── 02_REF_PODCAST.md
│   ├── 04_ENTORNO_LOCAL.md
│   └── TIRITAITO_FOR_CREATORS_VERSIONS.md
├── 01-producto/
│   └── ALCANCE_WEB_NUEVA.md
├── 02-metodologia/
│   ├── METODOLOGIA_CONSTRUCCION.md
│   └── MIGRACION_CONTENIDO.md
├── 03-guias-practicas/
│   └── GUIA_AVADA_LOCAL.md
├── 04-vision-y-equipo/
│   ├── ARQUITECTURA_Y_ROADMAP.md
│   └── ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md
├── apps/
│   ├── v1/
│   │   ├── tiritaito-creators-v1-07.html
│   │   └── CHANGELOG-v1-web-vieja.md
│   └── v2/
│       ├── tiritaito-creators-v2-01.html
│       └── CHANGELOG-v2-web-nueva.md
└── historico/
    ├── INFORME_ESTRATEGICO_2026_1.md
    ├── INVESTIGACION_HERRAMIENTAS_2026.md
    ├── METODOLOGIA_WEB_NUEVA_v2.md
    ├── Diagnóstico original v1.md
    ├── CONFIGURACION_PROYECTOS_CLAUDE_Y_GITHUB.md
    └── ALCANCE_WEB_NUEVA_v1.md
```

---

## Índice de documentos

| Documento | Carpeta | Propósito — responde a... | Audiencia principal | Mantiene actualizado | Frecuencia |
|---|---|---|---|---|---|
| `00_CORE.md` | `00-nucleo-tecnico/` | Stack técnico, endpoints, convenciones de código, mapa de `wp_options` | Hno A | Hno A | Baja — cambia solo si cambia el stack |
| `01_CREATORS_APP.md` | `00-nucleo-tecnico/` | Qué es y cómo funciona la PWA de editores | Hno A | Hno A | Media — cada fase nueva de la app |
| `02_REF_PODCAST.md` | `00-nucleo-tecnico/` | Arquitectura del sistema de podcast, shortcode `[tt_podcast]` | Hno A | Hno A | Baja |
| `04_ENTORNO_LOCAL.md` | `00-nucleo-tecnico/` | Constantes y particularidades del entorno Local (distinto de producción) | Hno A | Hno A | Media — ⚠️ pendiente de reescritura (patrón de token) |
| `TIRITAITO_FOR_CREATORS_VERSIONS.md` | `00-nucleo-tecnico/` | V1 vs V2 de la app, dónde vive cada archivo en GitHub, formato de changelog, alcance confirmado por versión | Hno A | Hno A | Media — cada versión nueva de la app |
| `ALCANCE_WEB_NUEVA.md` | `01-producto/` | Qué secciones tiene la web nueva y con qué prioridad | Hna C | Hna C | Alta mientras se decide el alcance — ⚠️ pendiente de revisión final con Hna C |
| `METODOLOGIA_CONSTRUCCION.md` | `02-metodologia/` | Diagnóstico técnico heredado + dónde construir cada pieza de contenido ya decidida | Hno A, Hna C | Hno C (investigación) | Media |
| `MIGRACION_CONTENIDO.md` | `02-metodologia/` | Qué contenido de la web vieja migrar, cuál recrear, plan de SEO | Hna C, Hno A, Carlitos | Hno C (investigación) | Media — vivo mientras avanza la revisión de contenido |
| `GUIA_AVADA_LOCAL.md` | `03-guias-practicas/` | Referencia completa de Avada + Local: licencia, Global Options, Header/Footer Builder, Layouts, elementos nativos, principio de Responsive | Hno A | Hno C (investigación) | Baja una vez estabilizada |
| `ARQUITECTURA_Y_ROADMAP.md` | `04-vision-y-equipo/` | FODA, política sobre la web vieja, fases del proyecto, glosario | Carlitos | Hno C (investigación) | Baja |
| `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | `04-vision-y-equipo/` | Roles, mapa de Proyectos de Claude, GitHub, WPMobile.app, Search Console | Carlitos | Hno C (investigación) | Media — cambia cuando cambia la organización del equipo |

**`historico/`** — documentos superados, conservados como referencia de cómo evolucionaron las decisiones. No se editan ni se usan como fuente de verdad; si algo de ahí sigue siendo válido, ya está incorporado en la versión activa correspondiente.

**`apps/`** — código fuente de Tiritaito for Creators (HTML autocontenido) y sus changelogs, separado en `v1/` (web vieja, solo mantenimiento) y `v2/` (web nueva, desarrollo activo). Ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` para el detalle de cada versión.

---

## Cómo empezar, según quién seas

| Si eres... | Lee primero |
|---|---|
| Nuevo en el equipo | Este README → `ARQUITECTURA_Y_ROADMAP.md` → `ALCANCE_WEB_NUEVA.md` |
| Hno A, sesión de código en Local | `00_CORE.md` + `04_ENTORNO_LOCAL.md` + el documento específico de tu tarea |
| Hno A, sesión de Avada/maquetación | `GUIA_AVADA_LOCAL.md` + `METODOLOGIA_CONSTRUCCION.md` |
| Hno A, sesión de Tiritaito for Creators (V1 o V2) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` + `01_CREATORS_APP.md` |
| Hna C, decisión de producto | `ALCANCE_WEB_NUEVA.md` |
| Hna C, revisando qué migrar | `MIGRACION_CONTENIDO.md` |
| Carlitos, coordinación | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` |

---

## Estado global — pendientes que cruzan varios documentos

*(Panel de control rápido — el detalle de cada uno vive en su documento correspondiente.)*

| Pendiente | Dónde se resuelve | Bloquea a |
|---|---|---|
| Revisión final de `ALCANCE_WEB_NUEVA.md` con Hna C | `ALCANCE_WEB_NUEVA.md` | Fase 2 de `ARQUITECTURA_Y_ROADMAP.md`; método definitivo de `MIGRACION_CONTENIDO.md` |
| Reescritura de `04_ENTORNO_LOCAL.md` (token en vez de Application Password) | `04_ENTORNO_LOCAL.md` | Cualquier snippet nuevo que se autentique contra el endpoint REST en Local |
| Confirmar en Local que el método **Layout + Elementos Guardados** funciona visualmente para "Hombres de Dios" (Dynamic Content ya fue descartado explícitamente — ver `ALCANCE_WEB_NUEVA.md` Sección 4.H) | `METODOLOGIA_CONSTRUCCION.md` Sección 4 | Construcción de esa sección |
| Confirmar en Local si **Post Cards** cubre el listado de "Seminarios pasados" y la portada de "Hombres de Dios" | `GUIA_AVADA_LOCAL.md` Sección 19, pregunta 2 | Construcción de esas dos piezas |
| Crear la cuenta de Repuesto y conectar GitHub | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 6 | Continuidad si la cuenta principal de Hno A agota su límite |
| Política de licencia de WPMobile.app en sitio de desarrollo | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 7 | Cualquier prueba de la app contra el entorno Local |
| Alcance completo de V2 de Tiritaito for Creators (solo Novedades confirmado por ahora) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 5 | Construcción de cualquier módulo nuevo más allá de Novedades |
| Añadir `tt_novedades` a la whitelist de `tt_opciones_permitidas()` y construir el widget de home | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 6 | Que el módulo de Novedades funcione de extremo a extremo |
| **Instrucciones actualizadas del Proyecto 3 (criterio de Responsive proactivo, añadido 13 julio 2026) pendientes de repegar a mano en claude.ai** | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 3 | Que Hno A reciba de verdad el nuevo criterio — subir el documento a GitHub actualiza la base de conocimiento del Proyecto, pero **no** las instrucciones personalizadas, que solo se actualizan si alguien las repega a mano en la configuración del Proyecto en claude.ai |

---

*Para la mayor gloria de Dios · tiritaito.com*

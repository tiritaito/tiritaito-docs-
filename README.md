# tiritaito-docs
**Repositorio de documentación técnica y de producto para la reconstrucción de Tiritaito.com**

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## Qué es este repositorio

Toda la documentación que el equipo usa para construir la Web Nueva de Tiritaito.com, en un solo sitio, sin fragmentación. Cada documento tiene **un dueño de contenido, una audiencia y una función clara** — si algo parece que debería estar en dos sitios a la vez, es una señal de que hay que fusionar, no duplicar.

**Regla de mantenimiento:** se edita el documento existente, no se crean copias con sufijo de versión (`_v2`, `_final`, `_nuevo`). El historial de versiones lo lleva git, no el nombre del archivo. **Excepción documentada:** el HTML de Tiritaito for Creators V2 (`apps/v2/tiritaito-creators-v2-01.html`) es un caso especial — su nombre de archivo es siempre fijo por decisión de Carlitos (26 julio 2026); el número real de versión vive en el footer del HTML y en `CHANGELOG-v2-web-nueva.md`, ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 2.

---

## Estructura

```
tiritaito-docs/
├── README.md                              ← este documento
├── 00-nucleo-tecnico/
│   ├── 00_CORE.md
│   ├── 02_REF_PODCAST.md
│   ├── 04_ENTORNO_LOCAL.md
│   └── TIRITAITO_FOR_CREATORS_VERSIONS.md   (absorbe 01_CREATORS_APP.md, retirado 26/07/2026)
├── 01-producto/
│   └── ALCANCE_WEB_NUEVA.md
├── 02-metodologia/
│   ├── METODOLOGIA_CONSTRUCCION.md
│   └── MIGRACION_CONTENIDO.md
├── 03-guias-practicas/
│   ├── GUIA_AVADA_LOCAL.md
│   ├── CATALOGO_ELEMENTOS_AVADA.md          ← nuevo, 11 agosto 2026
│   ├── CUADERNO_DEL_CONSTRUCTOR.md          ← nuevo, 1 septiembre 2026
│   └── exports/                             ← nuevo, 14 agosto 2026                            
│       ├── avada-global-options.json        (export saneado, sin credenciales)
│       ├── claves_conocidas.json            (línea base de claves para detectar drift)
│       └── saneador-avada-options.html      (herramienta de saneado, corre en el navegador)
├── 04-vision-y-equipo/
│   ├── ARQUITECTURA_Y_ROADMAP.md
│   ├── ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md
│   └── INSTRUCCIONES_PROYECTOS_CLAUDE.md
├── apps/
│   ├── v1/
│   │   ├── tiritaito-creators-v1-07.html
│   │   └── CHANGELOG-v1-web-vieja.md
│   └── v2/
│       ├── tiritaito-creators-v2-01.html            ← nombre SIEMPRE fijo, se sobreescribe
│       ├── CHANGELOG-v2-web-nueva.md
│       └── snippet-tt-creators-endpoint-central.php  ← PHP del endpoint (✅ real y
│                                                         completo, obtenido 26/07/2026)
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
| `00_CORE.md` | `00-nucleo-tecnico/` | Stack técnico, endpoints, convenciones de código, mapa de `wp_options`/ACF/CPT | Hno A | Hno A | Baja — cambia solo si cambia el stack |
| `02_REF_PODCAST.md` | `00-nucleo-tecnico/` | Arquitectura del sistema de podcast, shortcode `[tt_podcast]` | Hno A | Hno A | Baja |
| `04_ENTORNO_LOCAL.md` | `00-nucleo-tecnico/` | Constantes y particularidades del entorno Local (distinto de producción) | Hno A | Hno A | Media — reescrito 26/07/2026 con la auth real confirmada |
| `TIRITAITO_FOR_CREATORS_VERSIONS.md` | `00-nucleo-tecnico/` | Qué es y cómo funciona la PWA de editores, V1 vs V2, dónde vive cada archivo en GitHub, formato de changelog, alcance confirmado por versión, especificación técnica de cada módulo | Hno A | Hno A | Media — cada versión nueva de la app. Absorbe desde el 26/07/2026 lo que antes cubría `01_CREATORS_APP.md` |
| `ALCANCE_WEB_NUEVA.md` | `01-producto/` | Qué secciones tiene la web nueva y con qué prioridad | Hna C | Hna C | Alta mientras se decide el alcance |
| `METODOLOGIA_CONSTRUCCION.md` | `02-metodologia/` | Diagnóstico técnico heredado + dónde construir cada pieza de contenido ya decidida | Hno A, Hna C | Hno C (investigación) | Media |
| `MIGRACION_CONTENIDO.md` | `02-metodologia/` | Qué contenido de la web vieja migrar, cuál recrear, plan de SEO | Hna C, Hno A, Carlitos | Hno C (investigación) | Media — vivo mientras avanza la revisión de contenido |
| `GUIA_AVADA_LOCAL.md` | `03-guias-practicas/` | Referencia completa de Avada + Local: licencia, Global Options, Header/Footer Builder, Layouts, elementos nativos, ACF, principio de Responsive y de mínimo código | Hno A | Hno C (investigación) | Baja una vez estabilizada |
| `CATALOGO_ELEMENTOS_AVADA.md` | `03-guias-practicas/` | Qué elemento de Avada resuelve una necesidad de contenido concreta, con su nivel de certeza (confirmado en Local / documentado sin probar) — organizado por necesidad, no por nombre de elemento | Hno A (lo consulta Proyecto 3 antes de recomendar) | Hno A construye, Hno C (investigación) reconcilia contra el resto de `tiritaito-docs` | Media — crece según se van revisando más elementos y se construyen más secciones reales |
| `CUADERNO_DEL_CONSTRUCTOR.md` | `03-guias-practicas/` | Borrador de campo de lo que Álvaro descubre al construir (funciona / da problemas / se investigó y esto lo resuelve), antes de pasar en limpio a los documentos oficiales | Hno A (lo alimentan sus 2 cuentas de construcción y el Proyecto 9) | Hno C (investigación) reconcilia las entradas maduras hacia su documento de destino | Alta al principio, según ritmo real de construcción |
| `ARQUITECTURA_Y_ROADMAP.md` | `04-vision-y-equipo/` | FODA, política sobre la web vieja (incluida su fecha de caducidad), fases del proyecto, glosario | Carlitos | Hno C (investigación) | Baja |
| `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | `04-vision-y-equipo/` | Roles, mapa de Proyectos de Claude, ámbito de cada uno, GitHub, WPMobile.app, Search Console | Carlitos | Hno C (investigación) | Media — cambia cuando cambia la organización del equipo |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | `04-vision-y-equipo/` | Texto exacto a copiar en las Instrucciones personalizadas de cada Proyecto de Claude en claude.ai | Todos los dueños de cuenta | Hno C (investigación), con quien edite directamente en claude.ai | Media — cada vez que cambia el criterio de trabajo de algún Proyecto |

**`historico/`** — documentos superados, conservados como referencia de cómo evolucionaron las decisiones. No se editan ni se usan como fuente de verdad; si algo de ahí sigue siendo válido, ya está incorporado en la versión activa correspondiente.

**`apps/`** — código fuente de Tiritaito for Creators (HTML autocontenido), su changelog y, desde el 26 de julio de 2026, el snippet PHP del endpoint central que lo sirve — los tres juntos en `v1/` (web vieja, solo mantenimiento) y `v2/` (web nueva, desarrollo activo). Ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` para el detalle de cada versión.

**`03-guias-practicas/exports/`** — export real y saneado de Avada Global
Options (`avada-global-options.json`), su línea base de claves para detectar
cambios (`claves_conocidas.json`), y la herramienta que genera ambos
(`saneador-avada-options.html`, corre en el navegador). Se regenera cada vez
que alguien cambia y guarda un ajuste de Avada Global Options — ver
`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 6.1.

---

## Cómo empezar, según quién seas

| Si eres... | Lee primero |
|---|---|
| Nuevo en el equipo | Este README → `ARQUITECTURA_Y_ROADMAP.md` → `ALCANCE_WEB_NUEVA.md` |
| Hno A, sesión de código en Local | `00_CORE.md` + `04_ENTORNO_LOCAL.md` + el documento específico de tu tarea |
| Hno A, sesión de Avada/maquetación | `GUIA_AVADA_LOCAL.md` + `CATALOGO_ELEMENTOS_AVADA.md` + `METODOLOGIA_CONSTRUCCION.md` |
| Hno A, sesión de Tiritaito for Creators (V1 o V2) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` |
| Hna C, decisión de producto | `ALCANCE_WEB_NUEVA.md` |
| Hna C, revisando qué migrar | `MIGRACION_CONTENIDO.md` |
| Carlitos, coordinación | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` |

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
| Instrucciones actualizadas del Proyecto 3 (ACF, mínimo código, verificación antes de asumir, catálogo de elementos y construcción por niveles — Secciones 0.3 a 0.6, ampliadas por última vez 11/08/2026) pendientes de repegar a mano en claude.ai | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 3 | Que Hno A reciba de verdad el nuevo criterio — subir el documento a GitHub actualiza la base de conocimiento del Proyecto, pero **no** las instrucciones personalizadas, que solo se actualizan si alguien las repega a mano en la configuración del Proyecto en claude.ai |
| Dar acceso de GitHub a `apps/v2/` completa (HTML + PHP + changelog) a los Proyectos 2, 3 y 5, cada uno con el nivel de acceso que le corresponde | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.2 y 3 | Que cada proyecto pueda verificar el estado real sin depender de que alguien pegue el archivo a mano |
| Confirmar si `01_CREATORS_APP.md` tenía contenido real en GitHub que no llegara a este Proyecto de Investigación antes de darlo por eliminado | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 0 | Eliminación definitiva de ese archivo del repositorio |
| V1 desaparece en cuanto la Web Nueva sea oficial — sin fecha todavía | `ARQUITECTURA_Y_ROADMAP.md` · `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.1 · `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 1 | Archivado del Proyecto 1 y de la mitad V1 del Proyecto 5, el día que llegue |
| ✅ Ronda completa de Avada Global Options cerrada en tres cuentas de trabajo (28/07-10/08/2026), síntesis en `CATALOGO_ELEMENTOS_AVADA.md` (11/08/2026) | `GUIA_AVADA_LOCAL.md` · `CATALOGO_ELEMENTOS_AVADA.md` | Desbloquea la construcción de páginas reales con criterio Avada-first |
| Formalizar si `10px` es un cuarto token de radio (junto a `25/14/8px`) o queda como excepción en Toggles/Forms | `00_CORE.md` Sección 5 · `GUIA_AVADA_LOCAL.md` Sección 13 | Bordes de Tabs, Testimonials, Audio y cualquier elemento nuevo — dejados a propósito sin decidir mientras tanto |
| Confirmar si el Off-Canvas "Menu Movil" ya creado en Local está diseñado por dentro y probado, o solo registrado como borrador | `GUIA_AVADA_LOCAL.md` Sección 9.1 | Cierre real del menú móvil — uno de los tres bloqueantes de Fase 1 |
| Completar en `CATALOGO_ELEMENTOS_AVADA.md` el nombre de los ~70 elementos de Avada Builder Elements todavía sin inventariar ni por nombre | `CATALOGO_ELEMENTOS_AVADA.md` Sección 12 | Que el catálogo cubra el listado completo de elementos activos, no solo los ~24-50 ya revisados a fondo |

---

*Para la mayor gloria de Dios · tiritaito.com*

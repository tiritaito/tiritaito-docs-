# INVESTIGACIÓN — Herramientas de Trabajo del Equipo
**Claude Projects · Migración de contenido · Avada en Local · WPMobile.app**
*Elaborado por: Hno C · junio 2026*
*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## RESUMEN EJECUTIVO

Cuatro hallazgos importantes antes de entrar en detalle:

1. **La licencia de Avada de la web vieja SÍ sirve para el Local.** No hay que comprar nada. Está confirmado por la documentación oficial de ThemeFusion — ver Parte 3.1.
2. **Nuestro protocolo de "resumen de sesión" ya es la mejor práctica oficial de Anthropic para ahorrar tokens.** Sin saberlo, lo hicimos bien desde el principio.
3. **GitHub vale la pena, pero no es urgente.** Es una mejora de infraestructura, no un bloqueante.
4. **WPMobile.app tiene una limitación real que afecta a cómo probaremos la app** — hay que saberlo antes de construirla.

---

## PARTE 1 — CLAUDE PROJECTS: configuración y economía de tokens

### 1.1 Cómo funciona realmente la memoria de un Proyecto

Un Proyecto de Claude es un espacio con tres partes: instrucciones personalizadas, base de conocimiento (archivos que subimos) y un historial de conversaciones (chats). Es importante entender un mecanismo que no es intuitivo:

**Los chats dentro de un mismo Proyecto NO comparten contexto entre sí automáticamente.** Solo comparten la base de conocimiento (los archivos subidos permanentemente al Proyecto) y las instrucciones. Si en el Chat A del Proyecto Web decidimos algo importante, el Chat B del mismo Proyecto no lo sabe — a menos que lo escribamos en un archivo y lo subamos a la base de conocimiento del Proyecto.

**Esto confirma que nuestra cultura de documentación (00_CORE.md, 01_CREATORS_APP.md, 02_REF_PODCAST.md) no es un capricho — es la única forma correcta de que el conocimiento persista entre sesiones.**

### 1.2 Límites reales de las cuentas gratuitas

| Límite | Cuenta gratuita | Implicación para nosotros |
|---|---|---|
| Proyectos por cuenta | Máximo 5 | No es un problema — proponemos 1-3 por cuenta |
| RAG (expansión de memoria) | **No disponible** | El límite de contexto de cada Proyecto es fijo, no se expande |
| Mensajes | Variable, dependiendo de la longitud de la conversación y la carga del servidor — no es un número fijo de mensajes, sino de "tokens" (fragmentos de texto procesados) | Cuanto más larga la conversación, más cara cada respuesta nueva |
| Modelo disponible | Solo el modelo estándar (no el más avanzado) | Suficiente para nuestras tareas |

**El punto más importante de esta tabla: sin RAG, la base de conocimiento de cada Proyecto gratuito tiene un techo fijo.** Las cuentas de pago expanden automáticamente ese techo; las gratuitas no. Esto refuerza una regla que ya teníamos apuntada como aprendizaje: los archivos grandes (como el HTML completo de la app) no deben vivir permanentemente en la base de conocimiento del Proyecto — se adjuntan solo cuando se trabaja en ellos. Los documentos que sí deben vivir siempre ahí son los pequeños y estables: `00_CORE.md`, `01_CREATORS_APP.md`, `02_REF_PODCAST.md`.

**Dato adicional a tener en cuenta:** se ha reportado (no es documentación oficial, pero sí un patrón observado por varios usuarios) que subir **muchos archivos pequeños** a un Proyecto puede activar mecanismos de búsqueda parcial antes de lo esperado, incluso si el total de tokens es bajo. La lección práctica: **mejor pocos archivos consolidados que muchos archivos sueltos.** Esto ya lo hacemos bien con nuestros tres documentos maestros.

### 1.3 Reglas de economía de tokens — prácticas oficiales

Estas son recomendaciones directas del Centro de Ayuda de Anthropic, adaptadas a nuestro caso:

| Práctica | Por qué funciona | Ya lo hacemos? |
|---|---|---|
| Subir documentos base una sola vez al Proyecto | El contenido queda en caché — reutilizarlo cuesta mucho menos que volver a pegarlo | ✅ Sí, con los 3 documentos maestros |
| Instrucciones del Proyecto cortas y generales | El detalle específico de cada tarea va en el chat, no en las instrucciones permanentes | Verificar |
| Agrupar varias preguntas en un solo mensaje | Cada mensaje nuevo reprocesa toda la conversación anterior — menos mensajes = menos coste | Mejorable |
| Empezar una conversación nueva cada cierto número de intercambios | Una conversación de 30 mensajes es mucho más cara por mensaje nuevo que una recién empezada | ✅ Ya lo hacemos — ver siguiente punto |
| Si hace falta continuidad, pedir un resumen y pegarlo como primer mensaje de un chat nuevo | Evita cargar todo el historial viejo | **✅ Esto es EXACTAMENTE nuestro "Resumen de sesión" del protocolo de la Creators App** |
| Eliminar archivos obsoletos de la base de conocimiento | Los archivos viejos compiten por el mismo espacio fijo | Aplica sobre todo si subimos versiones antiguas de la app sin querer |

**Conclusión de esta sección:** nuestro protocolo de sesión (resumen al terminar, chat nuevo la próxima vez) no era solo una buena costumbre — es literalmente la técnica que Anthropic recomienda para alargar el uso de una cuenta gratuita. Vale la pena mantenerlo y aplicarlo también en el Proyecto Web e Investigación, no solo en Creators App.

### 1.4 ¿Vale la pena usar GitHub? — Veredicto

**Qué hace realmente la integración de GitHub en Claude:** permite conectar un repositorio y que Claude lea archivos concretos de una rama específica. Está disponible **incluso en cuentas gratuitas**. Dos límites importantes que hay que conocer antes de decidir:

- **No trae historial de commits, ni Pull Requests, ni metadatos** — solo el contenido de los archivos tal como están ahora mismo.
- **No se sincroniza solo.** Hay que pulsar "Sync now" manualmente antes de cada sesión donde el código haya cambiado. Si Hno A modifica el HTML y no sincroniza, Claude sigue viendo la versión vieja.

**Veredicto: sí vale la pena, pero de forma acotada — no para todo.**

| Qué llevar a GitHub | Por qué sí |
|---|---|
| El HTML de Tiritaito for Creators | Ahora mismo solo tenemos "v1-01, v1-02..." como nombres de archivo, sin historial real de cambios ni forma de volver atrás si algo se rompe. Git da eso gratis. |
| Los tres documentos maestros (00_CORE, 01_CREATORS_APP, 02_REF_PODCAST) | Historial de decisiones de arquitectura, y copia de seguridad fuera del ordenador local |

| Qué NO llevar a GitHub (todavía) | Por qué no |
|---|---|
| Los snippets PHP de Code Snippets | Viven dentro de la base de datos de WordPress, gestionados por el plugin. Exportarlos a mano a un repositorio cada vez que cambian añade fricción sin beneficio claro con el volumen actual de snippets |

**Recomendación concreta:** Hno A crea un repositorio privado (por ejemplo `tiritaito-creators`) con el HTML de la app y los tres documentos. Antes de empezar una sesión de trabajo en Claude, hace `git push` de los cambios y pulsa "Sync now" en Claude. Esto es una mejora de infraestructura, no un requisito para seguir trabajando como hasta ahora — se puede introducir con calma, sin prisa.

### 1.5 Confirmación de la organización de las 7 cuentas

La propuesta del informe anterior sigue siendo válida. Con el límite de 5 proyectos por cuenta gratuita confirmado, ninguna de las cuentas propuestas se acerca a ese techo. El único ajuste que añado: en las cuentas de **Infraestructura** y **Diseño/Avada**, mantener la base de conocimiento permanente reducida a los tres documentos maestros únicamente — nada más — precisamente porque no tenemos RAG para expandirnos si se llena.

---

## PARTE 2 — MIGRACIÓN DE CONTENIDO: framework de decisión

La pregunta no es "¿migramos o no?" en bloque — es sección por sección. Aquí está el criterio para decidir cada una.

### 2.1 Contenido que NO conviene migrar (recrear limpio en la web nueva)

| Contenido | Por qué recrear en vez de migrar |
|---|---|
| Entradas de "Charlas de la Biblia" y "Rincón de Nico" | El contenido único de cada entrada es mínimo — solo el shortcode `[tt_podcast feed="..."]` cambia. Recrear a mano en el nuevo sistema de Layouts lleva minutos por canal |
| Páginas construidas como Code Block con shortcodes de `wp_options` (Conecta cada día) | No hay contenido que migrar — los datos viven en `wp_options`, no en `post_content`. Solo hay que volver a montar la página con el shortcode en Avada nuevo |
| Cualquier página con CSS de experimentos abandonados | Ya identificamos que gran parte del CSS actual es deuda técnica — no tiene sentido migrar el problema |

**Riesgo técnico a evitar:** el `post_content` de Avada serializa los Code Blocks en Base64. Un export/import XML de WordPress entre dos instalaciones de Avada puede romper esa serialización si los IDs de plugin o de builder no coinciden exactamente. Es una razón técnica adicional para recrear en vez de migrar estas páginas.

### 2.2 Contenido que SÍ conviene migrar

| Contenido | Método recomendado |
|---|---|
| Entradas de "Hombres de Dios" con contenido sustancial (biografía, oración, texto largo) | **Si son muchas** (aprox. más de 15-20 con contenido real): exportación/importación XML de WordPress (Herramientas → Exportar / Importar), filtrando por categoría. **Si son pocas**: copiar el texto a mano es más seguro y más rápido que depurar un import roto |
| Archivos de medios (imágenes, portadas de podcast, audios de homilías) | Estos hay que migrarlos siempre, migre o no el contenido textual. Opciones: plugin de migración (All-in-One WP Migration, Duplicator) para mover la carpeta `/wp-content/uploads/` completa, o copia manual vía gestor de archivos de Raiola para los archivos que realmente se usan |
| Datos de `wp_options` (las 10 claves del sistema Creators) | No es "migración" en el sentido clásico — es simplemente volver a ejecutar el Snippet A en el nuevo WordPress y apuntar la PWA Creators al nuevo endpoint |

### 2.3 El problema que nadie mencionó todavía: el SEO

Este es un riesgo real que hay que gestionar conscientemente. Si la web nueva cambia las URLs de páginas que ya posicionan en Google (gracias a Google Search Console, que ya tenéis activo), perder esas URLs sin redirección significa perder ese posicionamiento de golpe.

**Plan recomendado antes del lanzamiento:**
1. Desde Google Search Console, exportar el listado de páginas con más clics/impresiones de los últimos 12 meses
2. Para cada una, decidir la URL equivalente en la web nueva
3. Configurar redirecciones 301 (puede hacerse con un snippet PHP sencillo, o con un plugin de redirecciones si se prefiere gestión visual)
4. Verificar en Search Console, semanas después del lanzamiento, que no aparecen errores 404 masivos

### 2.4 Pregunta abierta para decidir con Hna A y Hna C

El volumen real de "Hombres de Dios" determina si conviene exportar/importar o copiar a mano. Cuando se sepa el número aproximado de santos con contenido sustancial, se puede cerrar esta decisión en la reunión semanal.

---

## PARTE 3 — AVADA EN LOCAL: licencia y guía práctica para Hno A

### 3.1 La pregunta de la licencia — resuelta

**No hace falta comprar una licencia nueva.** Según la documentación oficial de licencias de ThemeFusion (la empresa detrás de Avada):

- Cada licencia de Avada incluye **un sitio adicional de staging/desarrollo/local gratuito**, sin coste extra.
- Ese sitio se reconoce automáticamente como "staging" si su dominio coincide con ciertos patrones — y **`.local` está explícitamente en esa lista** (ej: `tiritaito.local`).
- Local by Flywheel usa por defecto el sufijo `.local` para los sitios que crea, así que en circunstancias normales, el sitio nuevo debería calificar automáticamente.

**Paso práctico:** en el WordPress del Local, ir al panel de Avada → Registro, y pegar el mismo código de compra que se usó en la web en producción. El sistema de Avada debería detectarlo y registrarlo como staging, no como un segundo sitio de pago.

**Aviso honesto:** esto es lo que dice la documentación oficial consultada. Si al registrar aparece cualquier mensaje pidiendo una licencia nueva, no asumáis que hay que pagar — es más probable que sea un problema de detección de dominio (por ejemplo, si alguien cambió el sufijo del sitio Local de `.local` a otra cosa al crearlo). En ese caso, contactad con el soporte de Avada citando esta política antes de comprar nada.

### 3.2 Mapa mental — lo primero que hay que entender

Avada organiza cada página en **cuatro secciones estructurales**: Header (cabecera), Page Title Bar (barra de título), Content (contenido) y Footer (pie). Un **Layout** es el contenedor que decide qué versión de cada sección se muestra, y bajo qué condiciones.

```
LAYOUT (contenedor con condiciones)
   ├── Header Section     → ¿qué cabecera se usa aquí?
   ├── Page Title Bar      → ¿se muestra la barra de título?
   ├── Content Section     → el contenido de la página en sí
   └── Footer Section      → ¿qué pie se usa aquí?
```

Existe siempre un **Layout Global** por defecto. Se pueden crear **Layouts Condicionales** adicionales (por ejemplo: "todas las entradas de Podcast", "la página de inicio", "landing pages sin menú") y Avada aplica el más específico cuando hay varios que podrían coincidir.

**Nota importante confirmada en la documentación oficial:** en cuanto se asigna una Header Section personalizada al Layout Global, las opciones de cabecera de Global Options dejan de tener efecto — todo pasa a controlarse desde esa Header Section. Esto explica exactamente lo que os pasó en la web vieja: con el header desactivado en Global Options pero sin ninguna Header Section asignada al Layout Global, no había ni header de Avada ni header propio — de ahí el hueco en blanco.

### 3.3 Guía paso a paso — crear el Header con Header Builder

1. Ir a **Avada → Layouts → Layout Section Builder**
2. En el desplegable, elegir tipo **Header**, ponerle un nombre (ej. "Header Global Tiritaito")
3. Pulsar **Create New Layout Section**
4. Se abre el editor estándar de WordPress — elegir **Avada Builder** o **Avada Live** para diseñarlo
5. Construir el header con los elementos habituales: logo, menú, iconos — con total libertad, incluyendo columnas, fondos, lo que haga falta
6. Guardar
7. Ir a **Avada → Layouts → Layout Builder**
8. En el **Layout Global**, pasar el ratón sobre la sección Header → aparece un icono `+`
9. Seleccionar la Header Section recién creada (o crear una nueva desde ahí directamente)

**A partir de este momento, ese header aparece en todo el sitio automáticamente — sin tener que ponerlo entrada por entrada.**

### 3.4 Guía paso a paso — crear el Footer con Footer Builder

Es exactamente el mismo proceso que el header, cambiando el tipo a **Footer** en el paso 2. Como ya tenéis experiencia usando esto (el footer actual ya está montado como Layout Section, según lo que comentasteis), este paso os resultará familiar.

### 3.5 Guía paso a paso — Layouts condicionales (para páginas especiales)

Ejemplo práctico: queremos que "Rincón de Nico" tenga un header más colorido, sin el menú principal.

1. **Avada → Layouts → Layout Builder → Create New Layout**
2. Ponerle nombre, ej. "Layout Rincón de Nico"
3. Asignar una Header Section distinta (se puede crear una nueva, más lúdica, con el mismo proceso de la sección 3.3)
4. Pulsar en **Conditions** (parte inferior del Layout) y definir cuándo se aplica: por ejemplo, "categoría = Rincón de Nico"
5. Guardar

**Dato clave de la documentación oficial:** los Layouts no se combinan entre sí. Si una página encaja en varias condiciones, gana el Layout más específico o el de mayor prioridad. Si hay varios Layouts condicionales que podrían aplicar a la misma página, su posición en la lista de Layout Builder importa — los que están más abajo en la lista tienen prioridad sobre los de más arriba.

**Para páginas sin cabecera (landing pages, por ejemplo el "Ejército de Intercesores" si se decide que sea así):** el método más simple no es un Layout — es ir a las opciones de la página individual (Avada Page Options) y desactivar el header solo ahí, sin necesidad de montar un Layout condicional completo.

### 3.6 Global Options — qué configurar primero

Antes de tocar ninguna página, en Avada → Global Options:

1. **Colors:** cargar los 13 colores de la paleta con el sistema `--tt-*` (ya definido en el color palette del informe anterior)
2. **Typography:** confirmar que "Yeah Papa" está asignada a los encabezados y "Helvetica Neue" al cuerpo
3. **Performance:** activar Lazy Load de imágenes e iframes, desactivar Emojis de WordPress, activar Remove jQuery Migrate — esto ya se detalló en el informe estratégico anterior

### 3.7 Errores comunes a evitar (aprendidos de la web vieja)

| Error | Cómo evitarlo en la web nueva |
|---|---|
| Ocultar el header con CSS en vez de con Layouts | Usar siempre Header Builder + Layout Global. El CSS de "ocultar" no debería hacer falta nunca |
| Poner el menú como Global Element en cada entrada | El Layout Global ya lo aplica automáticamente en todo el sitio |
| Mezclar Global Options de header con Header Builder | Elegir uno de los dos sistemas. Si se usa Header Builder, no tocar las opciones legacy de Global Options — quedan inactivas de todas formas |
| Construir páginas antes de tener el Layout Global terminado | Terminar Header + Footer + Global Options primero. Las páginas se construyen después, sobre una base ya sólida |

---

## PARTE 4 — TIRITAITO EN EL MÓVIL (WPMobile.app): qué prever ahora

### 4.1 Cómo funciona realmente

WPMobile.app no es una app hecha desde cero — es un plugin de WordPress que **envuelve el contenido de la web actual** (entradas, páginas, tipos de contenido personalizados) en una app nativa para iOS y Android. Genera la app automáticamente a partir de lo que ya existe en WordPress, sin necesidad de programar la app por separado. Cuando se publica contenido nuevo en WordPress, la app se actualiza sola. También incluye notificaciones push y estadísticas de uso en tiempo real, alojadas en el propio WordPress.

**Dato de licencia importante:** la licencia de WPMobile.app es por sitio de WordPress — no es lo mismo ese contrato que el de Avada. No hemos encontrado en la documentación pública una política equivalente de "sitio de staging gratuito" como la de Avada, así que **antes de conectar WPMobile.app al Local, hay que revisar en el panel de la cuenta de WPMobile.app si permite tener el plugin activo en dos sitios (producción + local) sin conflicto.** Esto es una pregunta directa a hacerle a su soporte técnico si se llega a ese punto — mejor prevenir que encontrarse un bloqueo.

### 4.2 Implicación para la arquitectura de la web nueva

Como WPMobile.app lee directamente el contenido de WordPress (entradas, páginas, tipos de contenido), esto tiene una consecuencia directa para cómo construyamos "Hombres de Dios", "Oraciones" y el resto de secciones:

**Cuanto más estructurado y basado en entradas/tipos de contenido esté algo, más fácil será que aparezca bien en la app.** Contenido que vive solo dentro de un Code Block de Avada con diseño muy personalizado puede no traducirse bien a la vista simplificada de la app.

**Recomendación:** para las secciones que sabemos que queremos también en la app de la comunidad (probablemente Conecta cada día, Hombres de Dios, Oraciones), conviene que el contenido textual "de fondo" esté en entradas o páginas normales de WordPress — aunque visualmente en la web se presente con todo el diseño de Avada. Así WPMobile.app tiene algo limpio que leer.

### 4.3 La limitación de local/intranet — qué significa para las pruebas

Confirmado directamente por el soporte de WPMobile.app: la app **sí puede funcionar contra un sitio local**, pero solo si el móvil está en la misma red local que el ordenador donde corre el sitio. Fuera de esa red, la app no tiene forma de alcanzar el contenido.

**Consecuencia práctica:** cuando llegue el momento de probar la app contra la web nueva en Local, hay dos caminos:
- **Probar en la misma red WiFi** de la comunidad — suficiente para pruebas internas rápidas
- **Usar la función "Live Link" de Local by Flywheel** (ya mencionada en el informe estratégico) para exponer temporalmente el sitio local con una URL pública, y así probar la app desde cualquier red, incluyendo los móviles de quien no está físicamente en la comunidad ese día

### 4.4 Checklist de "preparar el terreno" — sin construir todavía

No es momento de construir la app. Pero sí conviene tener esto en mente mientras se construye la web nueva, para que el terreno esté preparado:

- [ ] Al definir la estructura de "Hombres de Dios" y "Oraciones" (Parte C del documento de Alcance), tener presente que un formato basado en entradas facilita la futura integración con la app
- [ ] Cuando se configure WPMobile.app en el futuro, preguntar a su soporte por la política de sitio de desarrollo/staging antes de asumir que hace falta una segunda licencia
- [ ] Guardar como nota para el futuro: probar la app contra el Local requiere estar en la misma red, o usar Live Link

---

## PARTE 5 — GOOGLE SEARCH CONSOLE: checklist de configuración completa

Ya está activo, pero "activo" no es lo mismo que "bien configurado y aprovechado". Checklist para que Hna MF tenga datos realmente útiles:

- [ ] Verificar que el sitemap XML está enviado (Search Console → Sitemaps)
- [ ] Revisar el informe de Cobertura/Páginas indexadas — identificar si hay páginas importantes que Google no está indexando
- [ ] Revisar el informe de Core Web Vitals — esto da datos objetivos de velocidad real que sufren los usuarios, complementario al diagnóstico de Avada Performance
- [ ] Exportar el listado de páginas con más clics de los últimos 12 meses — este es el insumo clave para el plan de redirecciones 301 de la Parte 2.3
- [ ] Configurar alertas de errores (opcional, pero útil): Search Console avisa por email si detecta un pico de errores 404 o de rastreo — muy útil justo después del lanzamiento de la web nueva

---

## PRÓXIMOS PASOS

1. **Pendiente de Hna C:** leer el documento de Alcance Web Nueva (`ALCANCE_WEB_NUEVA_v1.md`) y responder las 6 preguntas que bloquean el desarrollo — sigue siendo el paso más urgente de todos, independiente de esta investigación
2. **Hno A:** puede empezar ya con la Parte 3 de este documento (Header/Footer Builder + Global Options) en el Local — no depende de que se resuelvan las preguntas de Hna C
3. **Hna MF:** aplicar el checklist de Google Search Console de la Parte 5 — no depende de nada más
4. **Decisión de equipo, sin prisa:** si se adopta GitHub para el repositorio de la Creators App (Parte 1.4)
5. **Nota para más adelante:** revisar con soporte de WPMobile.app la política de sitio local/staging antes de conectar la app al entorno de desarrollo

---

## FUENTES CONSULTADAS

- Centro de Ayuda de Anthropic — Proyectos, límites de uso, integración con GitHub: support.claude.com
- ThemeFusion / Avada — FAQ de licencias y registro, documentación de Layouts, Header Builder, Footer Builder: avada.com/documentation
- WPMobile.app — documentación de compatibilidad y funcionamiento: support.wpmobile.app, wpmobile.app

---

*Para la mayor gloria de Dios y bajo el manto de la Virgen María · tiritaito.com*

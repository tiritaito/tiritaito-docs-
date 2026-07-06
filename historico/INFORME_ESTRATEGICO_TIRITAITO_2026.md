# INFORME ESTRATÉGICO — TIRITAITO.COM
**Documento de trabajo interno del equipo de desarrollo**
*Elaborado: junio 2026 · Revisión: trimestral*
*Ad maiorem Dei gloriam*

---

## RESUMEN EJECUTIVO

**Situación:** Tiritaito.com opera sobre una base técnica con deuda acumulada — CSS huérfano, arquitectura Avada no aprovechada, y un entorno de producción que es simultáneamente el único laboratorio de desarrollo. La web carga lenta y el equipo trabaja sin una arquitectura formal compartida.

**Decisión estratégica adoptada:** Construir una nueva web desde cero en entorno local (Local by Flywheel), con Avada como sistema base, mientras la web actual sobrevive en mantenimiento mínimo.

**Veredicto sobre esa decisión:** Correcta. Los riesgos son gestionables. Las oportunidades son mayores que los riesgos si se ejecuta con disciplina.

**Lo más urgente antes de escribir una sola línea de código en la web nueva:** Entender qué puede hacer Avada por vosotros de forma nativa, antes de ir a Code Snippets. Este informe responde esa pregunta.

---

## PARTE 1 — ANÁLISIS DE LA SITUACIÓN ACTUAL

### 1.1 Lo que funciona (no romper)

| Elemento | Estado | Decisión |
|---|---|---|
| Sistema REST API `tiritaito/v1` | Funciona, bien arquitecturado | Mantener arquitectura, migrar código limpio |
| Podcast con `fetch_feed()` server-side | Funciona, sin dependencias externas | Mantener el patrón, mejorar estilo |
| Sistema de variables CSS `--tt-*` | Definido, parcialmente implementado | Formalizar en web nueva |
| Fuente "Yeah Papa" en Avada | Cargada correctamente | Mantener |
| Google Search Console | Activo | Continuar |
| Local by Flywheel | Instalado | Base del desarrollo nuevo |
| Tiritaito for Creators (PWA) | v1-01 funcional | Proyecto independiente, continuar |

### 1.2 La deuda técnica (no heredar)

| Problema | Impacto real | Causa raíz |
|---|---|---|
| Header oculto con CSS en vez de desactivado en Avada | Avada genera HTML completo que el usuario descarga para no ver nada | No saber que existía Header Builder en Avada |
| Global Elements usados como header/footer en cada entrada | Hay que colocarlo manualmente en cada página, y genera doble carga si algo falla | No usar el sistema de Layouts de Avada |
| Custom CSS con 60-70% de código muerto | El navegador procesa reglas que no sirven para nada | Experimentos sin limpieza posterior |
| Paleta Avada con 12 colores aleatorios | El color de marca está entre ruido, no se puede usar para workflows del equipo | No haber configurado la paleta como sistema |
| CSS roto con errores de sintaxis | Puede causar comportamientos inesperados en algún navegador | Código copiado de foros sin revisar |
| Sin entorno local hasta ahora | Cualquier error en producción afecta a usuarios reales | Sin separación dev/prod |
| Sin separación clara Avada/Code Snippets | No hay regla compartida sobre "esto va aquí o allí" | Nunca se definió formalmente |

### 1.3 La causa raíz de todo

**No es falta de capacidad. Es falta de arquitectura formal.**

El equipo tiene talento técnico (Hno A), capacidad de investigación y conceptualización (Hno C), criterio visual y de producto (Hna C), y datos de usuario (Hna MF). Lo que no tenía era un mapa claro de qué herramienta hace qué. Este informe construye ese mapa.

---

## PARTE 2 — AVADA 7.15.5: MAPA COMPLETO DE CAPACIDADES

Este es el núcleo de la investigación. La mayoría de los equipos que trabajan con Avada usan el 20-30% de sus capacidades y compensan el resto con código. Vamos a mapear el 100%.

### 2.1 Lo que Avada puede hacer SIN código

#### A. Sistema de diseño global (Avada → Global Options)

**Colores (Color Palette):**
- 13 slots de color disponibles
- Estos colores están disponibles en TODOS los selectores de color del builder (fondo de contenedor, color de texto, botón, separador...)
- Si cambias un color aquí, cambia en todo el sitio automáticamente
- **Oportunidad:** los 13 colores del sistema `--tt-*` van aquí. Hna C puede cambiar un tono desde aquí sin tocar código.

**Tipografía (Typography):**
- Fuente primaria, secundaria, de menú, de botones — configurables visualmente
- Tamaños, pesos, interlineado, espaciado de letras — por tipo de elemento
- Responsive: tamaño diferente en móvil y escritorio sin CSS
- La fuente "Yeah Papa" ya está cargada. Hay que verificar que está asignada correctamente aquí.

**Espaciado global:**
- Padding de secciones por defecto
- Margen entre elementos por tipo
- Define una vez, aplica en todo el site

#### B. Header Builder (Avada → Headers)

**Esta es la capacidad que más cambia el flujo de trabajo.**

El Header Builder de Avada 7.x es un constructor visual completo para la cabecera, separado del Fusion Builder de páginas. Permite:

- Diseñar el header arrastrando y soltando elementos (logo, menú, iconos, botón, búsqueda, redes sociales)
- Headers diferentes para: escritorio / tablet / móvil
- Headers diferentes para: homepage / páginas internas / blog / páginas específicas
- Sticky header (que se fija al bajar) configurable visualmente
- Headers condicionales (mostrar el header A en páginas de tipo X, header B en páginas de tipo Y)
- Todo esto sin una sola línea de CSS ni Global Elements

**Lo que tenéis ahora (Global Element en cada página) vs Header Builder:**

| Criterio | Global Element actual | Header Builder |
|---|---|---|
| HTML generado por Avada | Sí (aunque vacío) | No (lo gestiona todo el Builder) |
| Hay que añadirlo a cada página | Sí | No — se asigna una vez en el Layout |
| Responsive nativo | Hay que programarlo | Incluido |
| Condicional por tipo de página | No | Sí |
| Sticky configurable | Con CSS | Con un toggle |

#### C. Footer Builder (Avada → Footers)

Igual que el Header Builder pero para el pie de página. Mismas capacidades. Misma recomendación.

#### D. Avada Layouts (Avada → Layouts)

**Esta es la capacidad que más tiempo os va a ahorrar.**

Un Layout en Avada es una plantilla que define qué cabecera, qué pie y qué contenido de página se muestra según condiciones. Funciona como la jerarquía de plantillas de WordPress, pero visual.

Podéis definir:
- **Layout Global:** lo que se aplica a todo el sitio por defecto
- **Layout Blog:** para todas las entradas
- **Layout Podcast:** para entradas de la categoría "Podcast"
- **Layout Página simple:** para páginas de texto simple
- **Layout sin cabecera:** para páginas de aterrizaje

Cada Layout especifica: qué Header Builder usar + qué Footer Builder usar + cómo se renderiza el contenido de la página.

**Consecuencia práctica:** en la web nueva, nunca más ponéis el Global Element del menú en cada entrada manualmente. Se define una vez en el Layout y Avada lo aplica automáticamente donde corresponde.

#### E. Global Elements y Saved Elements (lo que ya usáis)

**Global Element:** un bloque que se edita una vez y cambia en todos los sitios donde está incluido. Correcto para contenido que se repite (una sección de llamada a la acción, un bloque de donación, etc.).

**Saved Element:** un bloque guardado que se puede insertar en varias páginas, pero cada instancia es independiente. Correcto para layouts que se reutilizan como punto de partida.

**Recomendación:** con el Header/Footer Builder y los Layouts en la web nueva, los Global Elements se reservan para contenido de negocio (secciones reutilizables de contenido), no para infraestructura.

#### F. Avada Studio

Biblioteca de plantillas preconstruidas (páginas completas, secciones, elementos). Útil como punto de partida visual que luego se adapta al ADN Tiritaito. No usar como resultado final — siempre personalizar.

#### G. Opciones por página (Page Options)

Cada página/entrada tiene un panel "Avada Page Options" que permite:
- Desactivar el header solo en esta página
- Cambiar el ancho del contenido
- Ocultar la barra de título
- Definir una clase CSS personalizada para esta página

El `.page-id-10835` del CSS actual es exactamente esto — en la web nueva, eso se hace desde Page Options, sin CSS.

#### H. Condicionales y visibilidad

En cualquier elemento del Fusion Builder (contenedor, columna, widget) se puede definir:
- Visible en: escritorio / tablet / móvil (o combinación)
- Clase CSS personalizada
- Animación de entrada

#### I. Dynamic Content

Avada puede conectar el contenido de sus elementos a datos de WordPress: título del post, imagen destacada, autor, fecha, campos personalizados (ACF). Para contenido editorial estándar, puede eliminar la necesidad de shortcodes PHP simples.

### 2.2 Lo que Avada NO puede hacer (y necesita Code Snippets)

| Necesidad | Por qué no es Avada | Solución |
|---|---|---|
| Endpoint REST API propio (`tiritaito/v1`) | Lógica de servidor pura | Code Snippets PHP |
| Procesamiento de feed RSS del podcast (`fetch_feed`) | Lógica de servidor con caché | Code Snippets PHP |
| Shortcodes complejos con datos dinámicos de `wp_options` | Lógica de negocio | Code Snippets PHP |
| Player de podcast interactivo | JS complejo con estado | Code Snippets HTML (con `<style>` + `<script>`) |
| Widgets devocionales (Virgen, Brisa, Homilía) | Leen de REST API en tiempo real | Code Snippets HTML |
| CORS headers en REST API | PHP puro | Code Snippets PHP |
| Reglas de caché específicas | `.htaccess` o PHP | LiteSpeed Cache config |

### 2.3 La regla de oro — cuándo usar cada cosa

```
¿El elemento es visual y se puede configurar en Avada Theme Options o el Builder?
    → Avada. No toques código.

¿Es lógica de servidor (PHP), datos dinámicos del REST API, o un shortcode?
    → Code Snippets PHP.

¿Es un módulo con JS interactivo complejo y su propio estilo visual?
    → Code Snippets HTML (con <style> + <script> integrados).

¿Es CSS que afecta a TODA la web y no tiene opción en Avada?
    → Avada Custom CSS (máximo 30 líneas, bien comentadas).

¿Es CSS de un módulo específico?
    → Dentro del propio snippet HTML del módulo. Nunca en Custom CSS global.
```

---

## PARTE 3 — ARQUITECTURA DE LA WEB NUEVA

### 3.1 Capas del sistema

```
┌─────────────────────────────────────────────────────┐
│  AVADA (diseño, layout, tipografía, colores)        │
│  ├── Global Options: paleta tt-*, tipografía        │
│  ├── Header Builder: menú y cabecera                │
│  ├── Footer Builder: pie de página                  │
│  ├── Layouts: plantillas por tipo de contenido      │
│  └── Fusion Builder: contenido de páginas           │
├─────────────────────────────────────────────────────┤
│  CODE SNIPPETS PHP (lógica de servidor)             │
│  ├── Snippet A: endpoint tiritaito/v1 (REST)        │
│  ├── Snippet B: shortcode podcast [tt_podcast]      │
│  ├── Snippet C: shortcode lecturas DOCX             │
│  └── Snippet D: CSS global via wp_head (si aplica) │
├─────────────────────────────────────────────────────┤
│  CODE SNIPPETS HTML (módulos interactivos)          │
│  ├── Player podcast (pp-*)                          │
│  ├── Widget devocional (tt-devocional-*)            │
│  └── Otros widgets (tt-seminarios-*, etc.)          │
├─────────────────────────────────────────────────────┤
│  AVADA CUSTOM CSS (máximo, máximo 30 líneas)        │
│  └── Solo lo que no tiene opción en Avada Builder   │
├─────────────────────────────────────────────────────┤
│  WORDPRESS CORE + PLUGINS                           │
│  └── LiteSpeed Cache · Jetpack · Code Snippets      │
└─────────────────────────────────────────────────────┘
```

### 3.2 Convención de nomenclatura (formal, para toda la web nueva)

**Snippets PHP:**
- Nombre: `TT [Función] — [Descripción breve]`
- Ejemplo: `TT Core — Endpoint REST tiritaito/v1`
- Ejemplo: `TT Podcast — Shortcode y CSS`

**Snippets HTML:**
- Nombre: `TT Módulo — [Nombre del módulo]`
- Ejemplo: `TT Módulo — Player Podcast`
- Ejemplo: `TT Módulo — Widget Devocional`

**Clases CSS:**
- Prefijo: `tt-` + BEM → `tt-modulo__elemento--modificador`
- El player de podcast usa `.pp-*` (deuda técnica heredada — no migrar salvo refactor)

**Variables CSS:**
- Solo `var(--tt-*)` — nunca colores hex sueltos en snippets HTML

### 3.3 Configuración Avada Global Options para la web nueva

**Paleta de colores (los 13 slots):**

| Slot | Variable del sistema | Hex | Uso |
|---|---|---|---|
| Color 1 | `--tt-red` | `#BF4646` | Acento principal, botones |
| Color 2 | `--tt-red-d` | `#A33B3B` | Hover/active |
| Color 3 | `--tt-red-bg` | `#FDF2F2` | Fondos suaves |
| Color 4 | `--tt-txt` | `#1d1d1f` | Texto principal |
| Color 5 | `--tt-txt2` | `#3a3a3c` | Texto secundario |
| Color 6 | `--tt-txt3` | `#6e6e73` | Texto terciario |
| Color 7 | `--tt-txt4` | `#86868b` | Placeholders, labels |
| Color 8 | `--tt-sep` | `#c7c7cc` | Separadores |
| Color 9 | `--tt-bg` | `#FFFFFF` | Fondo principal |
| Color 10 | `--tt-surf2` | `#F5F5F7` | Superficies secundarias |
| Color 11 | `--tt-green` | `#34C759` | Estados positivos |
| Color 12 | `--tt-orange` | `#FF9500` | Advertencias |
| Color 13 | `--tt-alert` | `#FF3B30` | Errores |

Una vez configurados así, Hna C puede seleccionar "Color 1" en cualquier botón de Avada y siempre obtendrá el rojo de marca correcto.

---

## PARTE 4 — FORTALEZAS Y DEBILIDADES DEL PLAN

### 4.1 FORTALEZAS

**F1 — Entorno local profesional (Local by Flywheel)**
Local tiene características que cambian el flujo de trabajo completamente:
- "Live Link": comparte la URL local con cualquiera sin configuración. Hna C puede ver el trabajo en su móvil en tiempo real.
- "Blueprints": guarda una copia del estado limpio (WordPress + Avada configurado) para reutilizarla como punto de partida en futuros proyectos.
- Múltiples sitios en paralelo: podéis tener la web nueva y un entorno de pruebas simultáneamente.
- SSL local: el comportamiento es idéntico a producción.

**F2 — Equipo complementario**
Los tres roles se complementan bien. El riesgo habitual es que el desarrollador construya sin criterio de producto, o que el product manager pida sin base técnica. Aquí Hna C hace de puente. Eso es un activo real.

**F3 — Base técnica existente (no partir de cero en todo)**
El sistema REST API, los snippets de podcast, el sistema de variables `--tt-*`, el contrato de datos de `wp_options` — todo eso es código probado que simplemente se migra limpio a la nueva web.

**F4 — Avada 7.15.5 es maduro**
Esta versión tiene el Header Builder, Footer Builder y Layouts completamente funcionales. La inversión en aprender Avada ahora da retorno en años.

**F5 — Decisión de no heredar deuda**
Empezar desde cero en local, sin presión de producción, permite hacerlo bien. Esto es raro y valioso.

### 4.2 DEBILIDADES

**D1 — Curva de aprendizaje de Avada (real y subestimada)**
Avada tiene demasiadas opciones. Sin un mapa claro de qué ignorar, es fácil perderse durante semanas. Este informe es el mapa.

**D2 — Riesgo de replicar los mismos errores**
Si no se formaliza la arquitectura ANTES de construir, la web nueva acumulará la misma deuda que la vieja, solo que más rápido. La arquitectura primero, el código después.

**D3 — La web vieja necesita mantenimiento mínimo**
Durante el desarrollo de la nueva, la vieja puede empeorar. Hay que definir explícitamente qué no se toca.

**D4 — Migración de contenido**
El contenido (entradas, imágenes, páginas) habrá que migrarlo. Si se hace con exportación XML de WordPress, los shortcodes de Avada pueden tener problemas si el nuevo site tiene IDs de media diferentes.

**D5 — WPMobile.app — terreno casi inexplorado**
La licencia está comprada y la APK creada, pero poco más. El desarrollo de la app nativa requiere decisiones de arquitectura propias que aún no están definidas.

**D6 — 7 cuentas de Claude gratuitas**
El límite de mensajes diarios de las cuentas gratuitas es real. En sesiones intensas de desarrollo, se puede llegar al límite. La estrategia de contexto por archivo adjunto (que ya usáis en el Proyecto Web) mitiga esto pero no lo elimina.

### 4.3 RIESGOS

**R1 — Scope creep (riesgo alto)**
"Ya que estamos haciendo la web nueva, hagamos también X, Y, Z". Es el riesgo más peligroso. La web nueva debe tener un alcance definido y congelado antes de empezar.

*Mitigación:* Hna C define el alcance. Si no está en el documento de alcance, no entra hasta v2.

**R2 — La web vieja se degrada mientras (riesgo medio)**
LiteSpeed Cache puede compensar algo, pero si hay un fallo en un snippet mientras estáis enfocados en la nueva web, puede tardar en resolverse.

*Mitigación:* definir la lista de "solo esto se toca en la vieja": correcciones de bugs críticos únicamente.

**R3 — Avada Lock-in (riesgo bajo pero real)**
El contenido construido con Fusion Builder no es portable a otro tema o constructor. Si en tres años decidís cambiar de Avada, tendréis que reconstruir todo.

*Mitigación:* es un riesgo conocido y aceptado. Avada es maduro, tiene empresa detrás, y cubre vuestras necesidades. La alternativa (Gutenberg puro, Elementor, Bricks) tiene sus propios lock-ins.

**R4 — Fragmentación de Claude Projects (riesgo medio)**
Con 7 cuentas gratuitas sin organización, el riesgo es que cada uno trabaje con contexto incompleto y las decisiones de arquitectura se desincronicen.

*Mitigación:* ver Parte 6 de este informe.

**R5 — WPMobile.app vs PWA Creators (confusión de herramientas)**
Son herramientas distintas para públicos distintos, pero si no está claro para todos los miembros del equipo, pueden tomar decisiones contradictorias.

*Mitigación:* definición formal en la próxima reunión semanal (ver Parte 6).

### 4.4 OPORTUNIDADES

**O1 — Avada Header Builder elimina el problema del header**
El hack CSS de ocultar el header desaparece. El header es un objeto visual que Hna C puede modificar sin tocar código.

**O2 — Avada Layouts elimina el problema del Global Element manual**
Una vez definidos los layouts por tipo de contenido, el equipo editorial publica sin preocuparse de si el menú aparece.

**O3 — Paleta formal de colores en Avada**
Hna C, que tiene criterio visual, puede experimentar con variaciones de tono desde Global Options sin arriesgar producción y sin pedir ayuda técnica.

**O4 — Velocidad desde cero**
Una web nueva bien configurada en Avada + LiteSpeed Cache, con imágenes optimizadas desde el principio, puede cargar entre 2 y 4 veces más rápido que la actual. La deuda de velocidad no se hereda.

**O5 — Blueprint de Local como activo reutilizable**
Una vez que la web nueva esté configurada correctamente (Avada bien ajustado, paleta, tipografía, snippets base), se guarda como Blueprint en Local. Cualquier sitio futuro (si Tiritaito crece a comunidades locales, o si hacéis otra web) parte de esa base limpia.

**O6 — WPMobile.app integrado desde el principio**
Si la arquitectura de la nueva web se diseña pensando en la app desde el inicio (tipos de contenido correctos, campos personalizados si hacen falta, REST API bien estructurada), la integración con WPMobile.app será mucho más limpia que si se añade después.

---

## PARTE 5 — VELOCIDAD: DIAGNÓSTICO Y PLAN

La velocidad es la prioridad #1. Las causas principales de lentitud en la web actual:

| Causa | Impacto estimado | Solución en web nueva |
|---|---|---|
| Header generado por Avada aunque oculto | Medio | Header Builder elimina el problema |
| Imágenes sin optimizar / sin lazy load | Alto | Activar Lazy Load en Avada Performance + optimizar en subida |
| CSS muerto procesado por el navegador | Bajo-medio | CSS limpio desde el inicio |
| Scripts cargados globalmente que se usan en una sola página | Medio-alto | Revisar plugins + Code Snippets condicionales |
| LiteSpeed Cache mal configurado | Variable | Configurar correctamente en web nueva |
| Google Fonts posiblemente cargando (aunque no se usen) | Medio | Avada Global Options → Typography → solo fuentes en uso |
| Sin Critical CSS | Medio | Activar en Avada Performance |

**Avada → Global Options → Advanced → Performance — opciones que activar en la web nueva:**

| Opción | Activar | Nota |
|---|---|---|
| Lazy Load Images | ✅ Sí | Imágenes cargan cuando se necesitan |
| Lazy Load Iframes | ✅ Sí | Para vídeos embebidos |
| Remove jQuery Migrate | ✅ Sí | Script de compatibilidad innecesario |
| Disable Emojis | ✅ Sí | WordPress carga scripts de emoji por defecto |
| Container Lazy Loading | ✅ Sí | Secciones Avada en diferido |
| Google Fonts Loading | `swap` | Evita texto invisible mientras carga |
| Critical CSS | Evaluar | Hay que probarlo — puede interferir con LiteSpeed |
| Preload Resources | Evaluar | Útil para la fuente "Yeah Papa" |

---

## PARTE 6 — ORGANIZACIÓN DEL EQUIPO Y CLAUDE

### 6.1 Roles formalizados

| Persona | Rol | Responsabilidad principal | No toca |
|---|---|---|---|
| Hno A | Desarrollador principal | PHP, JS, REST API, snippets complejos, Lovable | Decisiones de contenido |
| Hno C | Investigación y documentación | Investiga, documenta, valida conceptos, conecta con Claude para research | Código en producción solo |
| Hna C | Dirección y diseño | Define alcance, criterio visual, decisiones de producto, Avada visual | No bloquea por perfeccionismo |
| Hna MF | Datos y métricas | Google Search Console, análisis de uso, propone mejoras basadas en datos | — |
| Editores (4) | Contenido | Subir/editar su contenido en sus secciones | Todo lo demás |

### 6.2 Las 7 cuentas de Claude — distribución propuesta

El principio: cada cuenta tiene un **archivo de contexto adjunto al inicio de cada sesión**. Sin contexto adjunto, la sesión empieza de cero.

| Cuenta | Persona | Proyecto Claude | Contexto a adjuntar |
|---|---|---|---|
| 1 | Hno A | Web — Infraestructura y Snippets | `00_CORE.md` + snippet activo |
| 2 | Hno A | Tiritaito for Creators | `00_CORE.md` + `01_CREATORS_APP.md` + HTML actual |
| 3 | Hno A / Hno C | WPMobile.app | Doc de WPMobile cuando empiece |
| 4 | Hno C | Investigación (este uso) | Este informe + contexto del tema |
| 5 | Hna C | Diseño y Avada | `00_CORE.md` + capturas de Theme Options |
| 6 | Hna MF | Datos y métricas | Datos de GSC, preguntas de análisis |
| 7 | Overflow | Cualquiera | La cuenta que llega al límite la toma otra persona |

**Regla de sesión para todos:**
1. Adjuntar el archivo de contexto relevante
2. Describir la tarea concreta en el primer mensaje
3. Al terminar, guardar el resumen de sesión (Hno C puede centralizar esto)

### 6.3 Coordinación de decisiones técnicas

Hay decisiones que si las toma Hno A sin comunicarlo, rompen el trabajo de otros. Estas requieren comunicación previa (en la reunión semanal o por escrito):

1. Añadir una nueva clave a `wp_options` (afecta al contrato de datos de la PWA Creators)
2. Cambiar el endpoint REST (afecta a todos los widgets y a la PWA)
3. Añadir o eliminar un snippet PHP (riesgo de conflictos de función)
4. Cambiar el nombre de una clase CSS de módulo (puede romper estilos en páginas)
5. Actualizar Avada o WordPress (puede requerir revisión de snippets)

---

## PARTE 7 — QUÉ HACER CON LA WEB VIEJA (MODO SUPERVIVENCIA)

**Política:** la web vieja solo se toca para correcciones de bugs que impidan su funcionamiento básico. No se añaden features. No se experimenta.

**Lista de "no tocar" en la web vieja:**
- No actualizar Avada hasta que la nueva esté lista
- No añadir snippets PHP nuevos
- No experimentar con LiteSpeed Cache
- No cambiar estructura de páginas existentes

**Acciones de mantenimiento mínimo permitidas:**
- Corrección de errores en snippets activos
- Subida de contenido por los editores
- Actualización del endpoint REST si es crítico (comunicando al equipo)
- Actualización de WordPress core (seguridad) — siempre con backup previo

**Respecto a la PWA Tiritaito for Creators:**
No es parte de la web vieja ni de la web nueva — es un proyecto independiente que puede continuar en paralelo sin interferir con ninguna de las dos.

---

## PARTE 8 — HOJA DE RUTA

### Fase 0 — Aprender antes de construir (1-2 semanas)
*Responsable: Hno C (investigación) + Hno A (validación técnica)*

- [ ] Completar este informe con el equipo en la reunión semanal
- [ ] Ver la documentación oficial de Avada: Header Builder, Footer Builder, Layouts, Global Options
- [ ] En el Local existente, hacer ejercicios con Avada (sin prisa, sin presión): crear un header visual, configurar un Layout, probar la paleta de colores
- [ ] Definir el alcance formal de la web nueva (Hna C lo decide): qué páginas, qué secciones, qué no entra en v1
- [ ] Configurar Google Search Console correctamente para que Hna MF tenga datos reales desde ya

### Fase 1 — Configurar la base de Avada (3-5 días)
*Responsable: Hno A + Hna C*

- [ ] Crear nuevo sitio en Local by Flywheel (WordPress limpio)
- [ ] Instalar Avada + activar licencia
- [ ] Configurar Global Options: paleta de 13 colores (sistema `--tt-*`), tipografía (Yeah Papa + Helvetica Neue), spacing
- [ ] Diseñar el Header en Header Builder
- [ ] Diseñar el Footer en Footer Builder
- [ ] Crear los Layouts: Global, Blog, Podcast, Landing
- [ ] Guardar como Blueprint en Local
- [ ] Migrar los snippets PHP base (Snippet A REST API, Snippet Podcast)

### Fase 2 — Construir páginas principales (variable)
*Responsable: Hno A + Hna C (diseño) + Hno C (revisión y docs)*

- [ ] Homepage
- [ ] Página de comunidad / oración
- [ ] Sección de podcast
- [ ] Entradas de blog (plantilla)
- [ ] Páginas de aterrizaje (sin header) si aplica

### Fase 3 — Migración de contenido
*Responsable: definir según alcance*

- [ ] Exportar/importar medios
- [ ] Revisar entradas migradas una a una
- [ ] Verificar todos los shortcodes

### Fase 4 — QA y velocidad
*Responsable: Hno C + Hna MF*

- [ ] Test de velocidad (Google PageSpeed Insights)
- [ ] Test en móvil real
- [ ] Test con Live Link de Local (todos los miembros del equipo)
- [ ] LiteSpeed Cache configurado correctamente
- [ ] Verificar que todos los snippets funcionan

### Fase 5 — Lanzamiento
*Decisión de Hna C*

---

## PARTE 9 — CONCLUSIONES Y RECOMENDACIONES

### Recomendación 1 — No empezar a construir hasta terminar la Fase 0
La tentación es abrir Avada y empezar a hacer páginas. El error que os ha traído hasta aquí fue exactamente ese. Una semana aprendiendo Avada correctamente (Header Builder, Layouts, Global Options) os ahorrará meses de deuda técnica.

### Recomendación 2 — Hna C define el alcance antes de la Fase 1
"¿Qué páginas tiene la web nueva en su primera versión?" Esa pregunta tiene que tener respuesta escrita antes de instalar Avada. Si no, el scope creep llegará.

### Recomendación 3 — Un solo desarrollador principal por sprint
Dos personas tocando los mismos archivos de Avada en Local pueden crear conflictos. Decidir quién construye en cada sprint. El otro revisa.

### Recomendación 4 — El Blueprint de Local como entregable de Fase 1
La Fase 1 no termina cuando "parece bien". Termina cuando el Blueprint está guardado en Local. Ese Blueprint es el activo más valioso que vais a crear — es la base limpia de todo lo que viene.

### Recomendación 5 — WPMobile.app: reunión de definición antes de Fase 2
La app para la comunidad necesita una reunión específica para definir: ¿qué contenido muestra? ¿Cómo se autentica el usuario? ¿Qué relación tiene con los tipos de contenido de WordPress? Esa decisión afecta a la arquitectura de la web nueva.

### Recomendación 6 — Hna MF: activar Google Search Console completo ahora
Los datos que recoja mientras construís la web nueva serán el criterio objetivo para priorizar qué páginas van primero. No esperar a la web nueva para tener datos buenos.

---

## GLOSARIO PARA EL EQUIPO

| Término | Qué es |
|---|---|
| Fusion Builder | El editor visual de páginas de Avada (el que usáis para hacer páginas) |
| Header Builder | Editor visual de cabeceras de Avada (separado del Builder de páginas) |
| Avada Layouts | Sistema de plantillas de Avada que define qué header/footer usa cada tipo de página |
| Global Options | El panel de configuración global de Avada (colores, tipografía, rendimiento) |
| Global Element | Un bloque de Avada que se edita una vez y cambia en todos los sitios donde está |
| Saved Element | Un bloque de Avada guardado que se inserta como punto de partida (edición independiente) |
| Code Snippets (PHP) | Lógica de servidor: REST API, shortcodes, procesamiento de feeds |
| Code Snippets (HTML) | Módulos interactivos: player de podcast, widgets devocionales |
| Local by Flywheel | Entorno de desarrollo local donde se construye la web nueva |
| Blueprint | Copia guardada de un sitio en Local — punto de partida reutilizable |
| Live Link | Función de Local que comparte el sitio local por URL pública temporal |
| wp_options | Tabla de WordPress donde se guarda configuración del sitio (textos, URLs dinámicas) |
| REST API | Sistema de comunicación entre la PWA Creators y WordPress |
| PWA Creators | Tiritaito for Creators — herramienta interna para editores, independiente de la web |
| WPMobile.app | Herramienta para crear la app nativa para la comunidad de usuarios |

---

*Este documento se revisa en la reunión semanal del equipo.*
*Para la mayor gloria de Dios · tiritaito.com*

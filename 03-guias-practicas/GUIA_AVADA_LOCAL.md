# TIRITAITO.COM — Guía Avada + Local
**Referencia técnica completa: licencia, infraestructura, Global Options, Header/Footer Builder, Layouts, elementos nativos y convenciones**
*Audiencia principal: Hno A · Fusiona y verifica: INVESTIGACION_HERRAMIENTAS_TRABAJO_2026.md (Parte 3-4) + INFORME_ESTRATEGICO_2026_1.md (Parte 2, 5, 8) + METODOLOGIA_WEB_NUEVA_v2.md (Secciones 3-11) + 04_ENTORNO_LOCAL.md*
*Verificado contra documentación oficial de avada.com — julio 2026*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento y cómo usarlo

Este documento responde una sola pregunta: **¿cómo configuro y construyo con Avada en el entorno Local, y por qué así y no de otra forma?** Es la referencia práctica para los Frentes 1 (configurar el Local) y 3 (Global Options + Layouts) del plan de trabajo actual.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Un dato técnico del stack (endpoints, `wp_options`, convenciones JS) | `00_CORE.md` |
| Qué secciones tiene la web y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| Dónde construir una pieza concreta de contenido ya decidida (Global vs Guardado vs Snippet) | `METODOLOGIA_CONSTRUCCION.md` |
| Qué contenido de la web vieja migrar y cómo | `MIGRACION_CONTENIDO.md` |
| **Cómo configurar Avada y Local paso a paso, y qué puede hacer Avada de fondo** | **Este documento** |

Este documento no repite el mapa de qué-va-dónde por sección (eso vive en `METODOLOGIA_CONSTRUCCION.md`) — se centra en la mecánica de Avada y Local en sí.

**Nota honesta:** todo lo marcado ✅ está confirmado contra documentación oficial de ThemeFusion/Avada (con fecha de verificación). Todo lo marcado 🔲 solo se puede confirmar dentro de Local, probando de verdad — no lo des por sentado sin probarlo.

---

## 1. Licencia de Avada en Local — resuelto y verificado

**No hace falta comprar una licencia nueva.**

✅ **Confirmado directamente en avada.com (última actualización: 5 febrero 2026):**
- Cada licencia de Avada incluye un sitio de staging/desarrollo/local gratuito, sin coste extra — uno por licencia.
- Ese sitio se reconoce automáticamente como staging si su dominio coincide con una lista cerrada de patrones. La lista de **TLDs completos** (no solo subdominios) incluye explícitamente: `*.dev`, `*.local`, `*.staging`, `*.test`.
- **`tiritaito-real.local` encaja directamente en el patrón `*.local`** — que es exactamente el sufijo que usa Local by Flywheel por defecto. Califica sin pasos adicionales.

⚠️ **Corrección de dominio (7 julio 2026):** el dominio real del sitio en Local **no es `tiritaito.local`, es `tiritaito-real.local`** (con guion, sin mayúscula inicial en código). El nombre correcto no cambia la conclusión de la licencia — sigue terminando en `.local`, sigue calificando — pero cualquier código, constante o URL que use el nombre viejo fallará. Esta versión del documento ya usa el nombre correcto en todas partes.

**Paso práctico:** en el WordPress del Local, ir a Avada → Registro, y pegar el mismo código de compra que se usó en producción. El sistema debería reconocerlo como staging automáticamente.

**Si aun así pide una licencia nueva:** no seguir sin más — contactar con soporte de Avada citando esta política (`avada.com/documentation/avada-registration-and-licensing-faq/`) antes de pagar nada. Es más probable que sea un problema de detección de dominio que una exigencia real.

⚠️ **Matiz importante que no estaba en la investigación anterior:** la lista de patrones válidos es cerrada. Si en algún momento se decide *no* usar el dominio actual de Local (`tiritaito-real.local`) y renombrarlo a otra cosa, hay que comprobar antes que el nuevo dominio sigue encajando en alguno de los patrones — si no, no calificará como staging automáticamente.

---

## 2. Infraestructura del entorno Local

| Componente | Detalle |
|---|---|
| Entorno | Local by Flywheel |
| Dominio local | **`tiritaito-real.local`** — corregido el 7 julio 2026, el valor anterior (`tiritaito.local`) era incorrecto |
| WordPress | mismo `/blog/` subdirectorio que producción, para mantener coherencia de rutas |
| SSL | Local lo genera automáticamente — comportamiento igual que en producción. Si el navegador sigue avisando de "no seguro", falta pulsar "Trust" en la pestaña SSL del sitio dentro de Local |

### Constantes del entorno (usar siempre estas, nunca las de producción de `00_CORE.md`, en código que corra en Local)

```
WP_BASE  = https://tiritaito-real.local/blog/wp-json
APP_PIN  = 1234   ⚠️ cambiar antes de lanzar
```

✅ **Resuelto y definitivo:** el sistema de autenticación de Tiritaito for Creators es **token propio (`TT_WRITE_TOKEN`)** desde v1-04, no Application Password. Es la decisión definitiva del equipo — `WP_USER`/`WP_PASS`/`AUTH Basic` quedan descartados en cualquier documento nuevo. `00_CORE.md` y `04_ENTORNO_LOCAL.md` siguen describiendo el patrón antiguo (Basic Auth) y necesitan reescribirse para reflejar el token — queda anotado como tarea pendiente de esa fusión, no de este documento.

⚠️ **Nota aparte, fuera del alcance de este documento:** cualquier snippet que use `wpFetch()` o `guardarOpciones()` contra el WordPress del Local necesita el Application Password real del usuario `makecom` **de ese WordPress local** (nunca el de producción) — sin eso, esas llamadas fallan con 401. Esto es del Proyecto Web Nueva (Backend), no de este documento; queda mencionado aquí solo para que no se pierda de vista.

### Herramientas exclusivas de Local (aprovecharlas)

| Herramienta | Para qué sirve | Dónde está |
|---|---|---|
| Base de datos directa (Adminer) | Inspeccionar `wp_options` sin pasar por el panel de WP — más rápido al depurar | Botón "Database" en Local |
| WP-CLI | Comandos de WordPress desde terminal — pruebas rápidas de snippets | Botón "Open Site Shell" en Local |
| Live Link | URL pública temporal del sitio local — para que Hna C o el equipo lo vean sin estar en el mismo ordenador/red | Botón "Live Link" en Local |
| Blueprint | Guarda el estado actual del sitio como plantilla reutilizable | Menú del sitio → "Save as Blueprint" |

### Checklist antes de cada sesión de trabajo en Local

- [ ] Confirmar que `tiritaito-real.local` sigue siendo el dominio activo (puede cambiar si se recrea el sitio)
- [ ] Confirmar que la Avada está registrada como staging (Avada → Registro no pide licencia nueva)
- [ ] Si el código incluye una URL o credencial, verificar que viene de este documento o de `04_ENTORNO_LOCAL.md` actualizado — nunca de producción

---

## 3. Mapa mental — cómo organiza Avada una página

Avada organiza cada página en **cuatro secciones estructurales**. Un **Layout** es el contenedor que decide qué versión de cada sección se muestra, y bajo qué condiciones.

```
LAYOUT (contenedor con condiciones)
   ├── Header Section     → ¿qué cabecera se usa aquí?
   ├── Page Title Bar      → ¿se muestra la barra de título?
   ├── Content Section     → el contenido de la página en sí
   └── Footer Section      → ¿qué pie se usa aquí?
```

Existe siempre un **Layout Global** por defecto. Se pueden crear **Layouts Condicionales** adicionales (ej. "todas las entradas de Podcast", "la página de inicio", "landing pages sin menú") y Avada aplica el más específico cuando hay varios que podrían coincidir.

✅ **Confirmado — la causa raíz del problema de la web vieja:** en cuanto se asigna una Header Section personalizada al Layout Global, las opciones de cabecera de Global Options **dejan de tener efecto** — todo pasa a controlarse desde esa Header Section. Esto explica exactamente lo que pasó antes: header desactivado en Global Options pero sin ninguna Header Section asignada al Layout Global → ni header de Avada ni header propio → hueco en blanco, pero con el HTML del header igualmente descargado por el usuario.

---

## 4. Global Options — el sistema de diseño global

### 4.0 Punto de partida — Avada Setup Wizard

✅ **Confirmado en documentación oficial (avada.com, verificado julio 2026):** Avada arranca automáticamente el **Setup Wizard** justo después de registrar la licencia. Bifurca en dos caminos:

| Camino | Qué hace | Cuándo tiene sentido |
|---|---|---|
| **Prebuilt Website** | Importa un sitio completo — colores, tipografía y páginas ya resueltos de una de las +100 plantillas de Avada Studio | Si se quiere partir de una base visual ya montada y adaptarla |
| **New Website** | Se eligen paleta de color y tipografía desde cero (Paso 3), y opcionalmente se importa un header/footer de Avada Studio (con opciones de invertir colores o no importar imágenes) | Si se prefiere construir la identidad visual de Tiritaito desde el principio, sin heredar nada de una plantilla |

Termina en menos de 5 minutos y deja enlaces directos a Layouts, Menús, Global Options y la cuenta de soporte.

*Fuente: avada.com/documentation/how-to-use-the-avada-setup-wizard/*

### 4.0.1 Registro de la sesión real — 7 julio 2026

El equipo eligió el camino **New Website** ("Sitio Nuevo"). Esto es lo que salió de cada paso, y lo que queda pendiente de cada uno:

**Paso 3 — Colores.** El Wizard solo admite 8 colores iniciales, no los 13 de la paleta `--tt-*`. Se mapearon los 8 primeros en orden claro→oscuro (recomendación oficial de Avada): `--tt-bg`, `--tt-surf2`, `--tt-sep`, `--tt-txt4`, `--tt-red`, `--tt-red-d`, `--tt-txt2`, `--tt-txt`. 🔲 Pendiente: añadir los 5 restantes (`--tt-red-bg`, `--tt-txt3`, `--tt-green`, `--tt-orange`, `--tt-alert`) después del Wizard, en Global Options → Colors → Add New Color.

**Paso 4 — Tipografía.** Confirmado: este paso **no admite subir fuentes propias** — solo un esquema prediseñado de Avada, tamaño base y proporción de escala. Se usó como placeholder para poder avanzar, no como configuración final. 🔲 Pendiente (clave, bloquea la identidad visual real): registrar "Yeah Papa" en Avada → Options → Typography → Custom Fonts, y aplicarla en la pestaña Heading Typography (H1-H6). Sin confirmar todavía si el `.woff2` ya subido a Media Library se puede enlazar directo desde ahí o si hay que volver a subirlo en ese panel específico — a probar en Local.

**Paso 5 — Diseños (Header).** Se eligió la plantilla **"Studio"** de Avada Studio como punto de partida. Cuatro cambios pedidos no son posibles dentro del Wizard — quedan para después, editando el Header en Avada → Layouts → Layout Section Builder (Sección 5 de este documento):

| Cambio pedido | Dónde se hará | Estado |
|---|---|---|
| Quitar la barra negra superior | Sin confirmar si es una fila del Header Builder o el Top Bar legacy de Global Options — hay que abrirlo en Local para saberlo | 🔲 Pendiente |
| Quitar buscador + iconos de usuario/carrito | Eliminar esos elementos individuales del Header | 🔲 Pendiente |
| Fondo de acuarela | Background del contenedor del Header → Imagen → Cover | 🔲 Pendiente |
| Tipografía "Yeah Papa" en el título | Sin confirmar si "Studio" usa el Site Title de WordPress (Global Options → Logo) o un elemento Título suelto | 🔲 Pendiente |

**Paso 6 — Contenido y Características.** Las páginas de arranque (Hogar, Acerca de, Servicios) son plantillas desechables de Avada Studio — no coinciden con las secciones reales de `ALCANCE_WEB_NUEVA.md` (Seminarios, Ejército de Intercesores, Hombres de Dios...) y se sustituirán al construir de verdad. Sugerencia pendiente de aplicar: cambiar "Servicios" por "Contacto".

Las Características (Features) se revisaron una por una, no en bloque:

| Función | Estado | Nota |
|---|---|---|
| Eventos | ✅ Activada | Candidata a evaluar para "Seminarios — próximos" |
| Formas (Forms) | ✅ Activada | Para formularios de contacto/oración |
| Off Canvas | ✅ Activada | Coincide con el método de menú móvil recomendado en Sección 9.1 |
| Portafolio | ✅ Activada | ⚠️ Marcada para reconsiderar — no hay contenido de portafolio planeado; activa CPT + plantillas Single/Archive innecesarias, exactamente el Patrón B de deuda técnica que `METODOLOGIA_CONSTRUCCION.md` ya advierte evitar |
| Herramientas de desarrollo (ACF) | ✅ Activada | Relevante para la pregunta abierta "Posts vs CPT+ACF" de "Hombres de Dios" (`METODOLOGIA_CONSTRUCCION.md` Sección 4) — no la resuelve, solo deja la herramienta lista si se opta por CPT |
| Modo de mantenimiento, Gestión de medios | ✅ Activadas | Sin objeción |
| Comprar, Foro, Marca personalizada, Chat en vivo | ☐ Sin activar | Correcto — sin caso de uso documentado en Tiritaito |

### 4.0.2 Sistema de diseño — configuración manual (independiente del Wizard)

Todo lo que se configura aquí se aplica en todo el sitio automáticamente. Es la base que hay que cerrar **antes** de construir ninguna página. Si el Setup Wizard ya dejó una base (Sección 4.0.1), esto es la verificación/ajuste fino sobre esa base — en concreto, añadir los 5 colores que el Wizard no permitió y registrar la tipografía real, no un punto de partida alternativo.

### 4.1 Colores (Avada → Global Options → Colors)

13 slots de color disponibles en todos los selectores de color del Builder. La paleta `--tt-*` va aquí completa:

| Slot | Variable | Hex | Uso |
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

Una vez configurado así, Hna C puede seleccionar "Color 1" en cualquier botón de Avada y siempre obtiene el rojo de marca correcto — sin depender de código.

### 4.2 Tipografía (Avada → Global Options → Typography)

- Fuente primaria (cuerpo): **Helvetica Neue** — fuente de sistema iOS, sin descarga.
- Fuente de encabezados: **Yeah Papa** — ya cargada como `.woff2` en Media Library (`uploads/2026/04/YeahPapa.woff2`). Confirmar que está asignada aquí, no solo declarada en CSS.
- Tamaños/pesos/interlineado configurables por tipo de elemento, con valores responsive distintos en móvil/escritorio, sin CSS.
- **Ninguna fuente de Google Fonts debería aparecer** — su presencia en el sitio actual (vía `@import` en el CSS del podcast) es deuda técnica a no replicar.

### 4.3 Espaciado global

Padding de secciones por defecto y margen entre elementos por tipo, definidos una vez y aplicados en todo el sitio.

### 4.4 Rendimiento (Avada → Global Options → Advanced → Performance)

Activar en la web nueva desde el primer día:

| Opción | Activar | Nota |
|---|---|---|
| Lazy Load Images | ✅ Sí | Imágenes cargan cuando se necesitan |
| Lazy Load Iframes | ✅ Sí | Para vídeos embebidos |
| Remove jQuery Migrate | ✅ Sí | Script de compatibilidad innecesario |
| Disable Emojis | ✅ Sí | WordPress carga scripts de emoji por defecto |
| Container Lazy Loading | ✅ Sí | Secciones Avada en diferido |
| Google Fonts Loading | `swap` | Evita texto invisible mientras carga |
| Critical CSS | Evaluar | Probar en Local — puede interferir con LiteSpeed Cache en producción |
| Preload Resources | Evaluar | Útil para la fuente "Yeah Papa" |

### 4.5 ⚠️ Avada Performance Wizard — existe, pero NO ahora

Además de configurar la tabla de arriba a mano, Avada tiene un asistente guiado en **Avada → Performance**. Antes de usarlo, un aviso importante:

✅ **Confirmado en documentación oficial (avada.com, verificado julio 2026):** el propio fabricante advierte que el Performance Wizard **debe ejecutarse solo cuando el sitio está prácticamente terminado**, nunca durante la configuración inicial. El Wizard escanea el sitio y desactiva funciones/elementos que no detecta en uso — si se ejecuta ahora, con la web nueva recién empezada, puede desactivar cosas que hagan falta dentro de dos semanas, y hay que acordarse de reactivarlas a mano.

Lo que hace, para cuando llegue el momento:
- Escanea y sugiere desactivar Features/Elements de Avada no usados (con botón "Find Recommendations")
- Optimiza qué subconjuntos de Font Awesome cargar
- Configura compilación/carga asíncrona de CSS y JS, y generación de Critical CSS
- Recomienda antes de empezar: correr PageSpeed Insights/Lighthouse en incógnito para tener una foto de referencia

**Dónde vive esto en el Roadmap:** no es Fase 1 (Sección 15) — es **Fase 4, "QA y velocidad"**, en `ARQUITECTURA_Y_ROADMAP.md`, justo antes del lanzamiento. Lo que sí se puede — y se debe — hacer ya es la tabla manual de arriba (4.4), que son ajustes seguros de activar desde el principio y no dependen de que el sitio esté terminado.

*Fuente: avada.com/documentation/how-to-use-the-performance-wizard/*

---

## 5. Header Builder — guía paso a paso

**Esta es la capacidad que más cambia el flujo de trabajo respecto a la web vieja.**

1. Ir a **Avada → Layouts → Layout Section Builder**
2. En el desplegable, elegir tipo **Header**, ponerle un nombre (ej. "Header Global Tiritaito")
3. Pulsar **Create New Layout Section**
4. Se abre el editor estándar de WordPress — elegir **Avada Builder** o **Avada Live** para diseñarlo
5. Construir el header con los elementos habituales: logo, menú, iconos — con total libertad, incluyendo columnas y fondos
6. Guardar
7. Ir a **Avada → Layouts → Layout Builder**
8. En el **Layout Global**, pasar el ratón sobre la sección Header → aparece un icono `+`
9. Seleccionar la Header Section recién creada

A partir de este momento, ese header aparece en todo el sitio automáticamente — sin ponerlo entrada por entrada.

**Lo que esto elimina respecto a la web vieja:**

| Criterio | Global Element manual (como antes) | Header Builder |
|---|---|---|
| HTML generado por Avada aunque esté "oculto" | Sí | No |
| Hay que añadirlo a cada página | Sí | No — se asigna una vez en el Layout |
| Responsive nativo | Hay que programarlo | Incluido |
| Condicional por tipo de página | No | Sí |
| Sticky header | Con CSS | Con un toggle |

---

## 6. Footer Builder — guía paso a paso

Mismo proceso exacto que el Header, cambiando el tipo a **Footer** en el paso 2 de la Sección 5. Mismas capacidades: responsive nativo, condicional por Layout, sin CSS de por medio.

---

## 7. Layouts condicionales — guía paso a paso

Ejemplo práctico: "Rincón de Nico" con un header más colorido, sin el menú principal.

1. **Avada → Layouts → Layout Builder → Create New Layout**
2. Ponerle nombre, ej. "Layout Rincón de Nico"
3. Asignar una Header Section distinta (nueva, más lúdica, creada con el proceso de la Sección 5)
4. Pulsar **Conditions** (parte inferior del Layout) y definir cuándo se aplica — ej. "categoría = Rincón de Nico"
5. Guardar

**Regla de prioridad confirmada:** los Layouts no se combinan entre sí. Si una página encaja en varias condiciones, gana el más específico o el de mayor prioridad. Si varios Layouts condicionales podrían aplicar a la misma página, **su posición en la lista importa: los que están más abajo tienen prioridad sobre los de más arriba.**

**Para páginas sin cabecera** (landing pages, o el "Ejército de Intercesores" si se decide que sea así): el método más simple **no es un Layout** — es ir a las opciones de la página individual (Page Options, Sección 10) y desactivar el header solo ahí.

---

## 8. Global Elements vs Saved Elements vs Code Snippets — árbol de decisión

**Esto es lo más importante que corrige la investigación anterior. Léelo antes de construir cualquier componente reutilizable.**

### 8.1 La corrección

Un elemento **Global** de la Avada Library sincroniza el contenido al 100% en todas sus instancias: editar una copia edita todas las demás, en todo el sitio, automáticamente. **No existe un modo de "misma estructura, contenido distinto por instancia" dentro de un elemento Global** — los elementos Global no admiten contenido dinámico ni campos variables. Sirve para "esto debe decir exactamente lo mismo en todas partes" (footer, aviso legal, CTA de donación fija).

Lo que sí varía por instancia es un elemento **Guardado (no-global)**: se inserta como plantilla de partida, y cada copia insertada es independiente — puedes cambiar título, texto o vídeo de una sin afectar a las demás. La contrapartida: si cambia el **diseño** más adelante, no hay sincronización — hay que entrar página por página.

*Fuente: avada.com/documentation/avada-builder-library-global-elements/ · avada.com/documentation/how-to-use-the-avada-builder-library/*

### 8.2 Árbol de decisión completo

```
ANTES DE CONSTRUIR NADA NUEVO
│
├── 1. ¿Lo resuelve un elemento NATIVO de Fusion Builder? (Sección 9)
│      → SÍ: úsalo. No hay snippet que mantener.
│      → NO: sigue.
│
├── 2. ¿Ya existe un snippet global de Tiritaito que hace esto o algo parecido?
│      → SÍ: reutilízalo o extiéndelo con un parámetro nuevo.
│      → NO: sigue.
│
└── 3. Hay que construir algo nuevo. ¿Dónde vive?
       │
       ├── ¿Se usa en más de una página?
       │     ├── NO → Code Block de la entrada en Avada Live (nunca Code Snippets global)
       │     └── SÍ → ¿El contenido es EXACTAMENTE igual en todas las páginas donde aparece?
       │                ├── SÍ, siempre idéntico → Avada Library: elemento GLOBAL
       │                └── NO, cambia por página, pero la ESTRUCTURA se repite
       │                      ├── Lo mantiene Hna C/editores, sin código → Avada Library: GUARDADO
       │                      └── Lo mantiene Hno A, con lógica de servidor → Code Snippets: shortcode
       │                            parametrizable (patrón [tt_podcast], ya probado)
       │
       └── En cualquier caso: constrúyelo pensando en el siguiente santo, el siguiente
           seminario, el siguiente "Rincón de X" — con parámetros, no contenido fijo.
```

### 8.3 Tabla resumen de decisión

| Opción | Cuándo usarla | Coste |
|---|---|---|
| **Global** (Avada Library) | Contenido idéntico en todas partes (footer, CTA fija) | Ninguno — es su propósito |
| **Guardado / no-global** (Avada Library) | Estructura repetida, contenido distinto, lo mantiene Hna C/editores sin tocar código | Si cambia el diseño, hay que actualizar cada instancia a mano |
| **Shortcode parametrizable** (Code Snippets) | Estructura repetida, contenido distinto, lo mantiene Hno A | El diseño se actualiza en un solo sitio y se propaga automáticamente — mismo patrón que `[tt_podcast]` |

---

## 9. Elementos nativos de Fusion Builder — tabla verificada

| Necesidad | Solución custom actual | Elemento nativo de Avada | Estado |
|---|---|---|---|
| Menú móvil / navegación | HTML/CSS/JS a mano | Ver Sección 9.1 — **corrección importante** | ⚠️ Ver abajo |
| Carrusel de imágenes | JS a mano + Swiper de `unpkg.com` | **Image Carousel Element** — 5 layouts, autoplay, swipe táctil, lightbox integrado | ✅ Confirmado. Pensado para imágenes; no confirma vídeo embebido |
| Carrusel con vídeo | (mismo JS a mano) | **Avada Slider Element** — imagen Y vídeo (YouTube/Vimeo) por diapositiva | ✅ Confirmado — mejor candidato si el carrusel mezcla vídeo |
| Accordion desplegable | `toggle-ios` custom | **Toggles Element** — modo Toggle (uno abierto) o Accordion (varios abiertos) | ✅ Confirmado |
| Modal / vídeo al clic | Modal custom | **Lightbox Element** (imagen/vídeo en overlay) o **Modal Element** (contenido libre) | ✅ Ambos confirmados |
| Listado con diseño de tarjeta | — | **Post Cards** (Avada Library) | 🔲 Existe, no verificado en profundidad — candidato para "Seminarios pasados" y portada "Hombres de Dios" |
| Reproductor de audio | 3 sistemas distintos (`.pp-*`, `.mp-*`, `.hmds-*`) | No es un elemento Avada — consolidación de snippets propios | Ver `METODOLOGIA_CONSTRUCCION.md` |

### 9.1 ⚠️ Corrección — menú móvil: Flyout Menu es método legacy

La investigación anterior recomendaba el **Flyout Menu clásico** (Avada → Opciones → Menú → Menú Móvil → Estilo = Flyout) como reemplazo nativo del hamburguesa hecho a mano. Verificando ahora mismo contra la documentación oficial, hay un matiz que cambia la recomendación:

✅ **Confirmado (documentación oficial, actualizada):**
- El Flyout Menu clásico (vía Global Options → Menú Móvil, o nativo en Header Layout 6) **sigue existiendo y funcionando**, pero la propia documentación de Avada lo marca explícitamente como **"legacy method"**, remitiendo a un método actualizado.
- El **método actual recomendado por Avada es el Off Canvas Builder**: se construye el menú como contenido dentro de un Off Canvas (popup o barra deslizante), y se dispara desde un icono en el Header con Dynamic Content → "Open Off Canvas". Da más control de diseño (imagen, botones, tipografía) que el Flyout clásico.
- **La limitación de submenús se mantiene en ambos métodos:** ni el Flyout clásico ni el Off Canvas resuelven de forma nativa un menú con varios niveles — "Flyout menus don't work well with menus with submenu items" está confirmado en la documentación oficial. Existen workarounds documentados por la comunidad (CSS + JS para forzar apertura de submenú en el móvil), pero no es una solución "gratis" de Avada.

**Recomendación actualizada:** antes de construir el menú, confirmar la pregunta ya abierta en `METODOLOGIA_WEB_NUEVA_v2` — **¿el menú de Tiritaito tiene submenús desplegables?**
- Si NO tiene submenús → Off Canvas Builder (método actual, mejor control de diseño que el Flyout clásico).
- Si SÍ tiene submenús → ninguno de los dos métodos nativos los resuelve limpiamente; evaluar en Local si el menú "Classic" o "Modern" (sin Flyout) del Menú Móvil estándar cubre el caso mejor que forzar un Flyout con workaround.

*Fuentes: avada.com/documentation/flyout-menu/ · avada.com/documentation/mobile-menu-settings/ · avada.com/documentation/how-to-make-a-flyout-menu-with-the-off-canvas-builder/ · avada.com/documentation/global-option-header-layouts/*

---

## 10. Page Options — opciones por página individual

Cada página/entrada tiene un panel "Avada Page Options" que permite, sin código:
- Desactivar el header solo en esa página (método recomendado para landing pages, Sección 7)
- Cambiar el ancho del contenido
- Ocultar la barra de título
- Definir una clase CSS personalizada para esa página concreta

El `.page-id-XXXX` del CSS de la web vieja es exactamente esto hecho con código — en la web nueva, se hace desde aquí, sin CSS.

---

## 11. Avada Studio y Dynamic Content — mención breve

- **Avada Studio:** biblioteca de plantillas preconstruidas (páginas, secciones, elementos). Útil como punto de partida visual que luego se adapta al ADN Tiritaito — nunca como resultado final sin personalizar.
- **Dynamic Content:** permite conectar elementos del Fusion Builder a datos de WordPress (título del post, imagen destacada, autor, fecha, campos personalizados). Para contenido editorial estándar, puede eliminar la necesidad de shortcodes PHP simples — relevante si en v2 se añade un Custom Post Type para "Hombres de Dios" (ver `METODOLOGIA_CONSTRUCCION.md`).

---

## 12. Lo que Avada NO puede hacer (necesita Code Snippets)

| Necesidad | Por qué no es Avada | Solución |
|---|---|---|
| Endpoint REST API propio | Lógica de servidor pura | Code Snippets PHP |
| Procesamiento de feed RSS del podcast | Lógica de servidor con caché | Code Snippets PHP |
| Shortcodes con datos dinámicos de `wp_options` | Lógica de negocio | Code Snippets PHP |
| Player interactivo con estado JS complejo | JS con estado | Code Snippets HTML (`<style>` + `<script>`) |
| Widgets devocionales | Leen de REST API en tiempo real | Code Snippets HTML |
| CORS headers | PHP puro | Code Snippets PHP |

### Regla de oro

```
¿Es visual y se puede configurar en Avada Theme Options o el Builder?
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

## 13. Convenciones de código — sin cambios respecto a `00_CORE.md`

- Snippets tipo **HTML** con `<style>` + `<script>` juntos (Code Snippets free no separa CSS/JS).
- Prefijo `tt-` + BEM → `tt-modulo__elemento--modificador`.
- Variables CSS siempre `var(--tt-*)`, nunca hex sueltos.
- Breakpoints en `1024px` / `768px` / `480px`.
- Patrón JS obligatorio — condicional de existencia fuera de `ttReady`, lógica dentro, IIFE:

```javascript
if (document.getElementById('mi-modulo-root')) {
  function initMiModulo() {
    (function() {
      'use strict';
      // lógica del módulo
    })();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMiModulo);
  } else {
    initMiModulo();
  }
}
```

- Los módulos nunca redefinen variables del Global ni tocan elementos genéricos.
- `border-radius: 25px` en cards/botones/contenedores — firma visual Tiritaito.
- Nomenclatura de snippets PHP: `TT [Función] — [Descripción breve]` (ej. `TT Podcast — Shortcode y CSS`).
- Nomenclatura de snippets HTML: `TT Módulo — [Nombre]` (ej. `TT Módulo — Widget Devocional`).

---

## 14. Errores comunes a evitar (aprendidos de la web vieja)

| Error | Cómo evitarlo en la web nueva |
|---|---|
| Ocultar el header con CSS en vez de con Layouts | Usar siempre Header Builder + Layout Global — el CSS de "ocultar" no debería hacer falta nunca |
| Poner el menú como Global Element en cada entrada | El Layout Global ya lo aplica automáticamente en todo el sitio |
| Mezclar Global Options de header con Header Builder | Elegir uno de los dos sistemas. Si se usa Header Builder, no tocar las opciones legacy de Global Options — quedan inactivas de todas formas |
| Construir páginas antes de tener el Layout Global terminado | Terminar Header + Footer + Global Options primero |
| Marcar un snippet "Run everywhere" porque es más fácil que decidir dónde | Es exactamente el patrón que generó el CSS de Santos/Biblioteca cargándose sin uso — decidir siempre el alcance real |
| `has_shortcode()` para condicionar CSS con Avada | Falso negativo — Avada codifica en Base64. CSS de módulos → `wp_head` siempre, incondicionalmente |

---

## 15. Roadmap — Fase 0 y Fase 1 (checklist accionable)

### Fase 0 — Aprender antes de construir

- [ ] Ver documentación oficial de Avada: Header Builder, Footer Builder, Layouts, Global Options, Off Canvas Builder
- [ ] En el Local, hacer ejercicios sin presión: crear un header visual, configurar un Layout, probar la paleta de colores
- [ ] Confirmar registro de Avada como staging (Sección 1)

### Fase 1 — Configurar la base de Avada

- [ ] Crear/confirmar el sitio en Local by Flywheel (WordPress limpio)
- [ ] Instalar Avada + registrar licencia como staging
- [ ] Configurar Global Options: paleta de 13 colores, tipografía (Yeah Papa + Helvetica Neue), spacing (Sección 4)
- [ ] Diseñar el Header en Header Builder (Sección 5)
- [ ] Diseñar el Footer en Footer Builder (Sección 6)
- [ ] Decidir método de menú móvil — Off Canvas vs Flyout legacy, según submenús (Sección 9.1)
- [ ] Crear los Layouts: Global, Blog, Podcast, Landing (Sección 7)
- [ ] Guardar como Blueprint en Local
- [ ] Migrar los snippets PHP base (endpoint REST, shortcode podcast)

**Este roadmap no depende de las 6 preguntas pendientes de `ALCANCE_WEB_NUEVA.md`** — Hno A puede avanzar esta Fase 1 en paralelo, sin bloquearse por las decisiones de contenido de Hna C.

---

## 16. Checklist maestro — antes de dar una plantilla por cerrada

- [ ] ¿Ya existe un elemento nativo de Fusion Builder que resuelva esto? (Sección 9)
- [ ] ¿Ya existe un snippet global de Tiritaito equivalente?
- [ ] Si se construye desde cero: ¿pensado para reutilizarse — parámetros, no contenido fijo?
- [ ] Si es candidato a Avada Library: ¿Guardado o Global? (Sección 8 — no son intercambiables)
- [ ] ¿El JS sigue el patrón condicional + IIFE?
- [ ] ¿Usa `var(--tt-*)` en vez de hex sueltos?
- [ ] ¿Se ha probado en consola del navegador que no hay errores de elementos inexistentes?
- [ ] ¿El snippet necesita estar en TODAS las páginas, o solo en una plantilla concreta?
- [ ] ¿Se ha comprobado el peso que añade a páginas que no lo necesitan?

---

## 17. Qué queda confirmado vs qué sigue pendiente de probar en Local

**✅ Confirmado en documentación oficial (esta versión, julio 2026):**
- Licencia de staging: `tiritaito-real.local` califica sin coste extra (Sección 1).
- Toggles, Image Carousel, Avada Slider, Lightbox, Modal existen como elementos nativos.
- Global Elements sincronizan el 100% del contenido — no admiten campos variables por instancia (Sección 8).
- El Flyout Menu clásico es un método legacy; el método actual es el Off Canvas Builder (Sección 9.1).
- Ni Flyout ni Off Canvas resuelven de forma nativa menús con submenús.

**✅ Confirmado en la sesión real del 7 julio 2026 (Sección 4.0.1):**
- El dominio correcto del Local es `tiritaito-real.local`, no `tiritaito.local`.
- El Setup Wizard solo admite 8 de los 13 colores de la paleta, y no admite fuentes propias — ambas cosas requieren configuración manual posterior.
- Off Canvas y Eventos ya están activados como Features de Avada; Portafolio también, pero sin caso de uso — candidato a desactivar.

**🔲 Solo se puede confirmar dentro de Local:**
- Si Image Carousel / Avada Slider replican el comportamiento exacto de "Próximos eventos" (autoplay, swipe, modal de vídeo).
- Si Post Cards cubre el listado de "Seminarios pasados" y la portada de "Hombres de Dios" sin shortcode propio.
- Código fuente completo de la home — "Grupo de alabanza" y "Próximos eventos" a nivel CSS/JS.
- Si "Yeah Papa" se registra correctamente en Avada → Typography → Custom Fonts, o si hace falta volver a subir el `.woff2` ahí específicamente.
- Si la barra negra superior del header "Studio" es una fila del Header Builder o el Top Bar legacy de Global Options.
- Si el título "Studio" del header es el Site Title de WordPress o un elemento de Título suelto.

---

## 18. Fuentes verificadas

- Avada Registration and Licensing FAQ — patrones de staging: avada.com/documentation/avada-registration-and-licensing-faq/ (act. 5 feb 2026)
- How To Set Up An Avada Staging Site: avada.com/documentation/how-to-set-up-an-avada-staging-site/
- Staging Sites vs Local Development Environments: avada.com/documentation/staging-sites-vs-local-development-environments/
- Avada Builder Library, elementos globales y guardados: avada.com/documentation/avada-builder-library-global-elements/ · avada.com/documentation/how-to-use-the-avada-builder-library/
- Toggles Element: avada.com/documentation/toggles-element/
- Flyout Menu (legacy): avada.com/documentation/flyout-menu/
- Mobile Menu Settings: avada.com/documentation/mobile-menu-settings/
- Off Canvas Builder — método actual de Flyout: avada.com/documentation/how-to-make-a-flyout-menu-with-the-off-canvas-builder/
- Global Option Header Layouts: avada.com/documentation/global-option-header-layouts/
- Image Carousel Element: avada.com/documentation/image-carousel-element/
- Avada Slider Element: avada.com/element/avada-slider/
- Lightbox Element: avada.com/documentation/lightbox-element/
- How To Use The Avada Setup Wizard: avada.com/documentation/how-to-use-the-avada-setup-wizard/
- How To Use The Performance Wizard: avada.com/documentation/how-to-use-the-performance-wizard/

---

## 19. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Hno A: añadir los 5 colores restantes de la paleta `--tt-*` en Global Options → Colors (Sección 4.0.1)
2. Hno A: registrar "Yeah Papa" en Typography → Custom Fonts y confirmar si el `.woff2` ya subido sirve o hay que resubirlo
3. Hno A: abrir el header "Studio" en Local para resolver los 4 pendientes de la tabla de la Sección 4.0.1 (barra negra, buscador/iconos, fondo acuarela, tipografía del título)
4. Hno A + Carlitos: decidir si se desactiva "Portafolio" en Características, ya que no hay contenido planeado
5. Cuando se llegue a construir el menú: decidir Off Canvas vs Flyout según la pregunta de submenús (abajo)

**Preguntas abiertas que necesitan decisión del equipo:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿El menú de la web nueva va a tener submenús desplegables? | Determina si el Off Canvas Builder (Sección 9.1) es suficiente o hace falta un workaround adicional |
| 2 | ¿Post Cards cubre el listado de "Seminarios pasados" y la portada de "Hombres de Dios"? | Solo se puede confirmar probando en Local — pendiente de sesión práctica |
| 3 | ¿Se desactiva "Portafolio"? | Sigue activo sin caso de uso — riesgo de repetir el Patrón B de deuda técnica si se deja así |

**Resuelto desde la última versión:** autenticación de Tiritaito for Creators — es token propio (`TT_WRITE_TOKEN`), definitivo, Application Password descartado · dominio real del Local corregido a `tiritaito-real.local` · primera sesión de Setup Wizard completada y registrada (Sección 4.0.1).

---

*Para la mayor gloria de Dios · tiritaito.com*

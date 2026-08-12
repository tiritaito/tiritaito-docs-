# TIRITAITO.COM — Catálogo de Elementos de Avada
**De "tengo esta necesidad de contenido" a "este es el elemento que la resuelve, y así de seguro estoy"**
*Construido a partir de 3 informes de sesión de Global Options — Cuenta 1, Cuenta 2, Cuenta 3, 28 julio – 10 agosto 2026 — y 2 documentos de investigación sobre el Off Canvas Builder · Primera versión: 11 agosto 2026*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

> **Nota sobre las fuentes, para poder rastrear cualquier dato hasta su origen:** los tres
> informes de Global Options se citan aquí como **Cuenta 1**, **Cuenta 2** y **Cuenta 3**.
> Cuenta 1 (Colors, Logo, Background, Typography, Responsive, Custom CSS, Avada Builder
> Elements) y Cuenta 3 (Page Title Bar, Breadcrumbs, Sliding Bar, Social Media, Contact
> Template, Search, Privacy & Consent, Extras, Advanced/CPT, Maintenance Mode, Custom Auth
> Pages, Performance, Events Calendar) se identifican así en el título de su propio informe.
> El tercer informe (Layout, Blog General/Single/Meta, Portfolio, Slideshows, Elastic Slider,
> Lightbox, Forms) **no se numera a sí mismo** — se referencia aquí como **Cuenta 2** por
> eliminación, ya que cubre el resto de las secciones repartidas entre las tres cuentas. Si
> esa identificación no es correcta, avísame y corrijo las referencias en todo el documento.
> Los dos documentos de investigación de Off-Canvas se citan como **Investigación Off-Canvas
> A** (la guía más breve, en español) e **Investigación Off-Canvas B** (la más extensa y
> técnica, con hooks, tablas de troubleshooting y ejemplos de código).

---

## 0. Qué es este documento

**No es un registro de qué se configuró** — eso ya lo cuentan los tres informes de sesión y
`GUIA_AVADA_LOCAL.md`. Este documento responde una pregunta distinta:

> **"Tengo esta necesidad de contenido — ¿qué elemento de Avada la resuelve, y cómo de
> seguro estoy de que es la elección correcta?"**

Es la pieza que faltaba para poder recomendar elementos con criterio en una sesión de
construcción real, en vez de a bulto o por intuición.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Qué valor exacto tiene cada campo de cada panel de Global Options, campo por campo | El informe de sesión original de la cuenta correspondiente (Cuenta 1/2/3) |
| Cómo funciona Avada/Local mecánicamente (licencia, Header Builder, árbol Global/Guardado/Snippet) | `GUIA_AVADA_LOCAL.md` |
| Dónde construir una pieza de contenido ya decidida en `ALCANCE_WEB_NUEVA.md` | `METODOLOGIA_CONSTRUCCION.md` |
| Qué secciones tiene la web y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| **Qué elemento de Avada resuelve una necesidad de contenido concreta, y con qué certeza** | **Este documento** |

**Alcance actual:** los tres informes de Global Options revisaron con profundidad real
unos 24 elementos de Avada Builder Elements (más los sistemas de Options relacionados:
Blog, Portfolio, Slideshows, Lightbox, Forms, Events, Page Title Bar, Breadcrumbs, Search,
Social Media, etc.), de un catálogo instalado que ronda los 100 elementos activos
(`Avada → Options → Advanced → Avada Builder Elements`). Este documento no se limita a los
24 revisados — para el resto, crea entradas marcadas 🔲 aunque no haya nada más que el
nombre, según la Sección 12. La profundidad del catálogo crecerá con el tiempo; su forma
completa ya existe desde hoy.

---

## 1. Cómo leer cada entrada

Cada necesidad sigue el mismo patrón: **necesidad → elemento → nivel de certeza.** Se usan
cuatro marcadores, nunca mezclados sin explicación:

| Marcador | Significa |
|---|---|
| ✅ | **Confirmado en Local con evidencia real** — visto en captura, verificado por triple fuente, o probado de extremo a extremo contra el sitio real de Tiritaito |
| ⚠️ | **Aplicado o decidido, pero con algo sin cerrar del todo** — un matiz, un detalle sin reconfirmar, o una pregunta de producto todavía abierta |
| 🔲 | **Solo documentado por Avada (oficial o por investigación general), sin probar con contenido real de Tiritaito** — sabemos que existe y cómo se supone que funciona, pero no se ha verificado en `tiritaito-real.local` |
| ❌ | **Confirmado que NO hace algo, o decisión explícita de no construirlo** — una limitación real, o un "no" ya decidido por el equipo |

Si un informe dice "sin revisar", la entrada se queda en 🔲 — nunca se sube a ✅ por mi
cuenta. Cuando dos informes confirman lo mismo de forma independiente, se dice explícitamente
("confirmado por Cuenta 1 y Cuenta 2"), porque esa es la evidencia más fuerte que existe en
todo este catálogo.

---

## 2. Estructura general de página

### Necesito controlar el ancho general del sitio

→ **Elemento:** Global Options → Layout

- ✅ **Layout = Wide** (el sitio ocupa el ancho real del navegador, sin marco con sombra) +
  **Site Width = `1200px`** — confirmado por Cuenta 2, con dos correcciones de unidad
  previas ("1200 píxeles" y "1200 px" con espacio, ambas inválidas para CSS).
- ⚠️ **"Boxed" no da lo que parece.** No es "contenido centrado con margen" — es un marco
  con sombra alrededor de TODO el sitio. Ese efecto de contenido centrado se consigue con
  Wide + Site Width fijo, que es justo lo que hay configurado. Anotar esta distinción evita
  que alguien reactive Boxed pensando que hace otra cosa.
- ✅ **`Default Page Template = 100% Width` — mecanismo de tres capas, no un interruptor
  simple:**
  1. Esta opción global **no fuerza nada** a ir de borde a borde.
  2. Lo único que hace es dar a cada **Container**, al crearse, la *opción disponible* de
     activar "Interior Content Width: 100%".
  3. Ir a pantalla completa sigue siendo **siempre una decisión explícita por Container**,
     sección por sección. Sin ese toggle activado en el Container concreto, el contenido
     sigue centrado en los 1200px del Site Width.
  Confirmado igual por Cuenta 2 (Sección Layout) y por Cuenta 3, que documenta que el equipo
  revirtió esta opción a `100% Width` por acuerdo tomado el 30 de julio de 2026 — ver
  Sección 13.3 más abajo, es una de las cinco cosas a reconciliar en la documentación
  oficial.
- 100% Width Padding: 30px — baja prioridad, el propio panel avisa que no afecta a los
  Containers de Avada Builder.

**Por qué importa esta distinción:** aparece de nuevo más abajo en "Blog Single Post" y es
la misma explicación exacta — no la repito completa ahí, solo la referencio.

### Necesito una franja de título debajo del header (Page Title Bar)

→ **Elemento:** Global Options → Page Title Bar (valor de respaldo) + Layout Section
Builder → Page Title Bar Builder (por grupo de páginas)

- ✅ **Arquitectura confirmada por Cuenta 3:** no es un diseño único para todo el sitio —
  se gobierna por Layouts, igual que el Header (`GUIA_AVADA_LOCAL.md` Sección 3). Lo
  configurado en Global Options es solo el valor de respaldo si ningún Layout condicional
  aplica. El propio panel de Avada recomienda esto: "para más flexibilidad, usa el Page
  Title Bar Builder".
- Valor de respaldo cerrado: Show Bar and Content · Breadcrumbs · Height 300px/240px móvil
  · Background = Color 2 (`--tt-surf2`, ver Sección 8.1 para el mapeo confirmado) ·
  Heading Font Size **54px** · Text Alignment Center.
- ⚠️ El tamaño 54px del titular no es arbitrario — está calibrado teniendo en cuenta que
  "Yeah Papa" necesita más tamaño en px que Helvetica Neue para el mismo peso visual (ver
  Sección 8.2 y Sección 13.4, hallazgo confirmado 3 veces).
- 🔲 **Pendiente, y de quién depende:** construir los Layouts condicionales por grupo de
  páginas es trabajo de Hno A, no de Global Options. Para Hombres de Dios en concreto, sigue
  sin decidirse si lleva imagen de fondo propia o se apaga porque el bloque de cada santo ya
  trae su propio titular — solo se puede decidir viendo el primer santo construido en Local.

### Necesito una ruta de navegación (Inicio › Sección › Página)

→ **Elemento:** Global Options → Breadcrumbs

- ✅ Configuración cerrada por Cuenta 3: Mobile Devices On · Separator `›` · Font Size 14px
  · Text Hover Color = Color 6 (`--tt-red-d`) · Current Page Color = Color 5 (`--tt-red`).
  Estos dos últimos usan la notación `Var(--awb-colorN)` y **coinciden exactamente** con el
  mapeo de colores ya confirmado en la Sección 8.1 — una corroboración más, no buscada a
  propósito, de que ese mapeo es correcto.
- ⚠️ Text Color usa hex directo (`#6e6e73`), no variable Avada, porque `--tt-txt3` es uno de
  los 5 colores que el Wizard todavía no ha cargado en los 13 slots (confirmado por Cuenta 3
  que seguían sin cargar en su sesión). Sustituir por `Var(--awb-colorN)` correspondiente
  cuando se confirme que Colores 9-13 ya están completos.
- ❌ **Post Categories/Terms en Off, a propósito** — depende de la decisión pendiente 3.1
  (¿Hombres de Dios usa CPT o Posts con categoría?). Activarlo antes podría mostrar un nivel
  de categoría vacío o mal etiquetado.

---

## 3. Menú y panel lateral para móvil (Off Canvas Builder)

Esta es la sección con más material nuevo de toda la ronda — dos documentos de
investigación dedicados enteros a esto. La trato aparte por su tamaño, no porque sea menos
"necesidad de contenido" que el resto.

### ⚠️ Aviso antes de nada — estado real, no el de ningún ejemplo

El Off Canvas Builder es, con alta confianza, el elemento correcto para el menú móvil de
Tiritaito — eso está bien fundamentado (ver más abajo). Pero **no tengo evidencia en ninguno
de los 5 documentos de esta ronda, ni en la documentación ya existente del proyecto, de que
exista ya un Off-Canvas construido y funcionando en Local.** Lo que sí está confirmado (por
el estado más reciente que tengo del proyecto) es: se decidió el enfoque "Camino 1" (panel
lateral, fondo blanco) tras evaluar que el accordion con código custom no era replicable de
forma nativa sin CSS/JS a medida, y se dieron instrucciones de construcción — **pero la
ejecución seguía pendiente**, no confirmada como hecha. Marco esta necesidad en ⚠️/🔲, no en
✅, precisamente por eso. Si tienes una sesión más reciente que confirme que ya está
construido y probado, dímelo y subo el marcador.

### Necesito un panel de menú lateral para móvil

→ **Elemento:** Off Canvas Builder

- ✅ **Identificación del elemento correcto** — confirmado contra documentación oficial de
  Avada en `GUIA_AVADA_LOCAL.md` Sección 9.1, y reforzado ampliamente por los dos documentos
  de investigación de esta ronda: el Flyout Menu clásico está marcado por el propio Avada
  como *"legacy method"*; el método actual recomendado es el Off Canvas Builder.
- ⚠️ **Enfoque ya decidido para Tiritaito** ("Camino 1": panel lateral, fondo blanco),
  instrucciones de construcción ya entregadas — **pendiente de ejecutarse en Local**, según
  la información más reciente disponible. No confundir "decidido" con "construido".
- ⚠️ **Limitación de submenús — ya documentada, no resuelta por esta investigación:** ni el
  Off-Canvas ni el Flyout clásico resuelven de forma nativa un menú con varios niveles
  (`GUIA_AVADA_LOCAL.md` Sección 9.1, confirmado contra documentación oficial). Los dos
  documentos de investigación de esta ronda no añaden nada nuevo sobre este punto concreto —
  sí aportan mucho sobre cómo construir y configurar el panel una vez tomada la decisión.
  Sigue pendiente confirmar si el menú de Tiritaito lleva submenús desplegables
  (`GUIA_AVADA_LOCAL.md` Sección 19, pregunta abierta #1).

### Los dos tipos de Off-Canvas — cuál usar según el caso

| | **Popup** | **Sliding Bar** (dentro del Off-Canvas Builder) |
|---|---|---|
| Despliegue | Modal centrado o posicionado | Desliza desde un borde (Top/Bottom/Left/Right) |
| Estado inicial | Siempre cerrado hasta activarse | Cerrado por defecto (opción de iniciar abierto) |
| Transición | Desvanecimiento típico | **Overlap** (se superpone) o **Push** (empuja el contenido) |
| Dimensiones | Ancho/alto personalizado (auto, 100%, px) | Ancho fijo (lateral) o alto fijo (top/abajo) |
| Ejemplos de uso | Ventanas de suscripción, anuncios, login | **Menús móviles**, mini-carrito, panel de filtro |

🔲 Para el menú de Tiritaito, el candidato natural es **Sliding Bar, posición Left o Right,
transición Overlap, ancho 300–400px, Full Height** — coherente con el enfoque "Camino 1" ya
descrito (panel lateral). Esto es una recomendación de la investigación general, no una
configuración ya probada en Local.

### ⚠️ Aviso importante — "Sliding Bar" es DOS cosas distintas en Avada

No confundir:

1. **`Global Options → Sliding Bar`** (revisado por Cuenta 3, Sección 3.10 de este
   catálogo) — un panel LEGACY, independiente del Off-Canvas Builder, que el propio Avada
   recomienda sustituir por el Off Canvas Builder.
2. **"Sliding Bar" como TIPO de Off-Canvas** (tabla de arriba) — una opción dentro del
   Off-Canvas Builder moderno, al crear un panel nuevo.

Es el mismo nombre para dos cosas relacionadas pero distintas — uno es el sistema antiguo que
se está sustituyendo, el otro es una opción del sistema nuevo que lo sustituye. Ver Sección
11.3 para el detalle del panel legacy.

### Cómo se configura, paso a paso (resumen operativo)

1. **Menú base en WordPress:** `Apariencia → Menús`, crear el menú con sus items.
2. **Crear el Off-Canvas:** `Avada → Off Canvas → Create New Off-Canvas`, nombrarlo, elegir
   Avada Builder o Avada Live para diseñarlo. Se puede partir de cero (Container + columna +
   elemento Menú apuntando al menú de WordPress) o importar un diseño de Avada Studio.
3. **Pestaña General:** tipo (Popup/Sliding Bar), dimensiones, posición, alineación interna.
4. **Pestaña Design:** fondo, bordes, **Z-Index** (importante — valor bajo puede dejar el
   panel oculto detrás de otros elementos; subirlo si pasa, ej. a 9999) y sombra.
5. **Pestaña Overlay:** activar capa oscura semitransparente detrás del panel (ej.
   `rgba(0,0,0,0.5)`) para enfocar la atención.
6. **Pestaña Close:** activar "Close on Overlay Click" y "Close With ESC Key" — ambos On,
   es la recomendación de accesibilidad de ambos documentos de investigación.
7. **Pestaña Animation:** Slide es la recomendación estándar para menús, velocidad moderada.
8. **Pestaña Conditions:** ⚠️ **crítico** — `Enable Conditions` está en `No` por defecto, lo
   que deja el Off-Canvas en "borrador", invisible en todo el sitio aunque esté diseñado y
   guardado. Hay que activarlo explícitamente (`Yes`) para que se publique. Si se deja sin
   gestionar, se muestra en TODO el sitio por defecto.
9. **Pestaña Triggers:** ver apartado siguiente.

### Cómo se abre — triggers

**Disparadores manuales (los relevantes para un menú):**
- **Special Menu Item "Off-Canvas Toggle"** en `Apariencia → Menús → Elementos especiales
  de Avada` — el método más directo para un ítem de menú que abre el panel.
- **Contenido dinámico en un icono/botón del Header:** acción "Open Off-Canvas" vía Dynamic
  Content, seleccionando el Off-Canvas concreto.
- **Selector CSS manual** (pestaña Triggers → On Click), apuntando a una clase/ID propios.

**Disparadores automáticos (documentados, probablemente NO aplican al menú móvil, pero
existen para otros usos futuros — ver nota al final de esta sección):** On Page Load, Time
On Page, On Scroll, On Click, Exit Intent, After Inactivity, After Add to Cart (requiere
WooCommerce, no instalado).

### Accesibilidad — lo que hay que verificar a mano

- Avada es WCAG 2.1 compliant de fábrica, pero ambos documentos de investigación insisten en
  **verificar manualmente**, no dar por hecho:
  - El foco del teclado debería entrar al panel al abrirse y volver al disparador al
    cerrarse — probar con Tab/Esc real.
  - Botón de cierre con `aria-label="Cerrar"` o equivalente.
  - Contraste suficiente en el overlay y en el texto sobre fondos/imágenes (mínimo 4.5:1).
  - Herramientas recomendadas para verificar: Lighthouse, WAVE, axe DevTools.
- 🔲 Nada de esto está probado todavía contra el Off-Canvas real de Tiritaito, porque el
  panel en sí sigue pendiente de construirse (ver aviso al inicio de esta sección).

### Para el caso raro en que de verdad haga falta código

Coherente con el principio de mínimo código ya establecido para este proyecto (Sección 0.3
de las instrucciones del Proyecto): el Off-Canvas se resuelve casi siempre desde la interfaz
de Avada, sin código. Para el caso excepcional en que no baste:

- 🔲 **No existen hooks públicos exclusivos para Off-Canvas** — esto lo dice explícitamente
  la Investigación Off-Canvas B. Lo que sí existen son hooks genéricos de Avada reutilizables:
  - `add_filter('avada_before_body_content', ...)` — para inyectar código justo antes del
    contenido principal.
  - `avada_after_main_content` — mencionado como alternativa para inyectar HTML cerca del
    cierre del contenido principal, aunque la propia investigación aclara que en la práctica
    suele ser más simple editar la plantilla de footer en un child theme.
  - CSS puntual vía la pestaña **Custom CSS** del propio Off-Canvas (con selectores del tipo
    `#offcanvas-miID .fusion-off-canvas-content { ... }`) — para retoques de diseño que no
    dependan de lógica de servidor.
  - JS puntual vía la pestaña **Code Fields** del propio Off-Canvas, o un snippet aparte si
    la lógica es compleja.
- ⚠️ Estos hooks son documentación general de Avada (Investigación Off-Canvas B), **no
  verificados contra el backend real de Tiritaito**. Antes de usarlos, confirmar en Local que
  se disparan donde se espera.

### Integración con Post Cards — "vistazo rápido" sin cambiar de página

- 🔲 Avada soporta contenido dinámico dentro de un Off-Canvas: el elemento **Post Cards**
  tiene una opción para asociar un Off-Canvas ("Link Off-Canvas"). Un botón con acción "Open
  Off-Canvas" dentro de una tarjeta abre el panel mostrando los campos dinámicos de esa
  entrada concreta (Dynamic Data: título, imagen destacada, campos ACF...), sin recargar la
  página — el mismo patrón que un "Quick View" de tienda online.
- 🔲 **Aplicación posible, sin decidir ni probar todavía:** esto podría encajar con
  **Biblioteca** (vista previa de un libro o película sin salir del listado) o con
  **Hombres de Dios** (vista previa de un santo desde la portada) — mencionado aquí como
  posibilidad documentada, no como recomendación de construir ahora. Conecta con la
  arquitectura ya decidida de Hombres de Dios (Layout + Guardados, `METODOLOGIA_CONSTRUCCION.md`
  Sección 4) solo si en algún momento se quisiera una vista previa desde la portada,
  adicional a la ficha completa.

### Rendimiento

- 🔲 Recomendaciones generales de la Investigación Off-Canvas B, no verificadas en Local:
  usar el Lazy Load nativo de Avada para imágenes dentro del panel (no plugins externos);
  preferir animaciones con `transform`/`opacity` en vez de cambios de ancho/alto (Avada ya
  usa `transform: translate` para el Sliding Bar, que es lo óptimo); no cargar scripts
  pesados del contenido del Off-Canvas hasta que se abra; excluir el Off-Canvas del Critical
  CSS de la página para no retrasar el *Time to Interactive*.

### Problemas comunes (condensado de ambos documentos de investigación)

| Problema | Causa más probable | Solución |
|---|---|---|
| No aparece al hacer clic | `Enable Conditions` en `No`, o condición no cumplida | Activar Conditions; revisar que la página actual esté incluida |
| Aparece siempre, sin control | "Display on Entire Site" activo sin exclusiones | Revisar `Manage Conditions` |
| Se ve sin estilos / en blanco | Conflicto de caché o CSS/JS | Limpiar caché (incl. la de Avada), revisar consola del navegador |
| Queda oculto detrás de otro elemento | Z-Index insuficiente | Subirlo en la pestaña Design |
| El botón ESC no cierra | "Close With ESC Key" no activado | Activarlo en la pestaña Close |
| Los enlaces del menú no funcionan | Elemento Menú sin el menú de WordPress correcto asignado, o cierre automático demasiado agresivo | Revisar asignación; ajustar temporizador de cierre |

### Fuera de alcance hoy, documentado por si acaso

- 🔲 WooCommerce (Mini Cart, trigger "After Add to Cart") — Tiritaito no tiene tienda ni
  carrito; documentado solo porque apareció en la investigación general, sin aplicación
  actual.
- 🔲 WPML/multilenguaje — Tiritaito no es multilingüe hoy; la investigación menciona que
  históricamente hubo problemas de traducción de Off-Canvas en WPML, resueltos en la versión
  4.7.5 del plugin. Sin relevancia mientras no se plantee una versión en otro idioma.

---

## 4. Listados y contenido dinámico repetible

### Necesito una rejilla de tarjetas con contenido dinámico

→ **Elemento:** Post Cards (Avada Library)

- ✅ Ya en uso real para Novedades — patrón visual confirmado, backend probado de extremo a
  extremo.
- ✅ Ordena de forma nativa por Custom Field ACF (ej. fecha) — confirmado en el piloto de
  Novedades (22-23 julio 2026).
- ❌ **No filtra de forma nativa por valor de campo.** Si hiciera falta filtrar de verdad
  (a diferencia de Novedades, que decidió no filtrar), requiere el hook
  `fusion_post_cards_shortcode_query_override`. Decisión de equipo: no se construyó ese hook
  para Novedades (26 julio 2026) — el listado muestra todas las entradas, activas u ocultas.
- 🔲 Sin confirmar todavía si Post Cards cubre bien el listado de "Seminarios pasados" y la
  portada de "Hombres de Dios" — a diferencia de Novedades, si esas dos secciones necesitan
  filtrar de verdad, ahí sí haría falta el hook (`GUIA_AVADA_LOCAL.md` Sección 19, pregunta
  abierta #2).

### Necesito rotar entre varias entradas distintas (ej. los 9 santos de Hombres de Dios, un listado de Novedades destacadas)

→ **Elemento:** Post Slider (Avada Builder Element)

- ✅ **Identificado como el elemento correcto — confirmado de forma independiente por dos
  cuentas distintas en la misma ronda de trabajo** (Cuenta 1 y Cuenta 2), ambas corrigiendo
  por separado la misma confusión inicial con "Slideshows" (ver entrada siguiente).
- 🔲 **Sin configurar ni probar todavía en Local** — pendiente de sesión dedicada, junto con
  la pregunta de si Post Cards filtra o no para este mismo tipo de listado
  (`GUIA_AVADA_LOCAL.md` Sección 19, pregunta abierta #2).

### ⚠️ No confundir con: necesito varias imágenes DENTRO de una misma entrada

→ **Elemento:** Options → Slideshows (esto NO es Post Slider)

- ⚠️ **Corrección conceptual importante, no solo de configuración — el nombre confunde.**
  "Slideshows" no rota entre entradas distintas. Controla **Posts Slideshow Images**: cuántas
  imágenes destacadas puede tener una MISMA entrada individual, formando un mini-carrusel
  dentro de esa sola ficha (ej. varias fotos de un mismo santo, deslizables dentro de su
  propia página). Para "rotar entre los 9 santos" hace falta Post Slider (entrada anterior).
- ✅ Configurado por Cuenta 2 (se decidió avanzar porque los valores no chocan con ninguna
  decisión pendiente, aunque originalmente solo se pidió explorar): Posts Slideshow Images 5
  · Autoplay On · Smooth Height Off (correcto si las fotos se recortan con ratio
  consistente, mismo patrón `object-fit: cover` que Novedades) · Slideshow Speed 7000ms ·
  Navigation Box 30px/30px.
- ⚠️ Navigation Arrow Size corregido a `14px` (antes "14 píxeles", unidad inválida) — sin
  reconfirmar por captura después del cambio.
- 🔲 Sin confirmar si un CPT nuevo (para Hombres de Dios, si se decide CPT) hereda
  automáticamente este número de imágenes igual que Posts/Pages/Portfolio, o si habría que
  replicarlo con ACF — solo se sabe probándolo en Local cuando se construya la primera ficha.

### Necesito un listado de próximos eventos

→ **Elemento:** Events (feature completa de Avada) — pero ⚠️ ver aviso de posible
solapamiento entre dos paneles distintos, abajo

- ✅ **Conclusión de Cuenta 3 (Events Calendar, Options):** la feature Eventos de Avada es
  perfectamente utilizable tal cual para "Próximos Eventos" (la pieza sin resolver de
  `ALCANCE_WEB_NUEVA.md`) — no hace falta construir nada desde cero. Trae listado con filtro,
  ficha con imagen, compartir social, meta información. Cambios aplicados: "Display All
  Events Link" Off→On (para poder volver al listado completo desde una ficha compartida
  directamente) y "Events Featured Image Hover Type" none→Zoom In.
- ⚠️ **Aviso — posible solapamiento sin resolver del todo:** Cuenta 1 revisó por separado el
  elemento "Events" dentro de **Avada Builder Elements** (la sección de estilos por defecto
  de elementos insertables en Fusion Builder) y lo dejó con una decisión de producto
  pendiente: `Number of Events Per Page: 4 · Column Spacing 40px · Content Padding 20px ·
  Events Text Display en "No Text"` — sin decidir si cada tarjeta debe llevar aunque sea una
  línea de texto o si toda la información va en el cartel visual. Panel marcado como
  posiblemente incompleto (colores y radio sin capturar). **No tengo certeza de si
  "Avada Builder Elements → Events" y "Options → Events Calendar" son exactamente el mismo
  panel visto en dos momentos de la ronda, o dos paneles relacionados pero distintos** (uno
  gobernando el archivo/calendario completo, otro gobernando el estilo de la tarjeta de
  evento como elemento suelto). Antes de dar "Próximos Eventos" por cerrado del todo,
  recomiendo confirmar esto en Local y resolver la pregunta de "Events Text Display".

### Necesito la vista clásica de archivo del blog de WordPress

→ **Elemento:** Options → General Blog + Blog Meta — **⚠️ esto NO es lo que se usa para
Novedades ni para ningún listado dinámico real**

- ⚠️ **Aclaración necesaria antes de usar esta entrada para nada:** este panel gobierna la
  vista de archivo automática de WordPress (`/blog`), que hoy no tiene ningún caso de uso
  real planeado en `ALCANCE_WEB_NUEVA.md` — los listados reales (Novedades, futuros CPT) se
  construyen con Post Cards. Del panel entero, **solo dos campos afectan a Post Cards**:
  el formato de fecha y el color del botón "Load More" (este último con hallazgo abierto,
  ver abajo).
- ✅ Configurado por Cuenta 2: Blog Content Display Excerpt · Strip HTML On · Featured
  Image On · Blog Layout/Archive Layout Large (recomendación de menor certeza de todo su
  informe, más gusto que corrección técnica).
- ⚠️ Excerpt Length subido a 20-25 palabras (indicado, sin reconfirmar por captura) — el
  original (10 palabras) casi siempre cortaba la frase a medias.
- ⚠️ Blog Alternate Layout Date Box Color → Color 5 (indicado, sin reconfirmar) — el
  original (Color 2) era casi invisible. Day Format corregido de "J" mayúscula (sin función
  real en formato de fecha PHP) a "j" minúscula.
- 🔲 **Pendiente, aplazado a propósito:** los 4 colores del botón "Load More" (el único
  campo de este panel con efecto real en Post Cards) no aparecieron siguiendo los pasos
  documentados. No es urgente — se resuelve el día que un Post Cards use esa paginación.
- ✅ **Blog Meta** (formato de metadatos): Post Meta = Off como interruptor maestro (apaga
  autor, fecha, comentarios, "leer más" y tags de un golpe). Meta Data Font Size 12px SÍ se
  usa en Post Cards aunque el resto del panel no. Date Format `j \d\e F \d\e Y` (produce
  "26 de julio de 2026") — requiere que `Ajustes → Generales → Idioma del sitio` esté en
  español, o el mes saldría mezclado en inglés.

### Necesito la ficha individual de una entrada clásica de WordPress

→ **Elemento:** Options → Blog Single Post — **⚠️ probablemente NO es lo que se use para
Hombres de Dios**

- ✅ Configurado por Cuenta 2: Previous/Next Pagination On · Post Title Below · Social
  Sharing Box On · Author Info Box Off (sin bylines individuales — coherente con contenido
  firmado como Tiritaito, no por persona) · Related Posts Off · Comments Off.
- ✅ **"100% Width Page" corregido de Off a On** — misma explicación de tres capas que en la
  Sección 2.1 (Layout): no fuerza pantalla completa, solo desbloquea la opción por Container.
- ⚠️ **Pendiente fuera de Avada:** `WordPress → Ajustes → Comentarios` → desmarcar "Permitir
  que la gente envíe comentarios en las entradas nuevas". El interruptor de Avada solo apaga
  la visualización; esto cierra el sistema de comentarios de WordPress en sí — importante en
  un sitio sin equipo de moderación dedicado. No confirmado como hecho.
- ⚠️ **Nota de arquitectura, importante:** el propio panel de Avada avisa "For more
  flexibility... we recommend using the Live Builder to create a custom Content Layout" —
  confirma que esta pestaña es el método clásico/heredado. `METODOLOGIA_CONSTRUCCION.md` ya
  decidió que Hombres de Dios usa Layout + Elementos Guardados, no este sistema. Esta
  configuración probablemente termine como red de seguridad para contenido futuro que sí sea
  una entrada clásica, no como la config real de las fichas de santos.

---

## 5. Piezas de contenido para construir dentro de una página

### Necesito un acordeón / desplegable de tipo "¿Qué es X?"

→ **Elemento:** Toggles

- ✅ Ya en uso para el patrón "¿Qué es X?" (Ejército de Intercesores, Rincón de Nico —
  `METODOLOGIA_CONSTRUCCION.md` Sección 2). Configuración completa verificada con capturas
  reales por Cuenta 1: Boxed Mode On · fondo Superficie secundaria · Padding 24px ·
  Título Yeah Papa 30px (ajustado desde 16px por el efecto de calibración de tamaño, ver
  Sección 8.2) color Rojo · Contenido Helvetica Neue 16px · Icono 20px con caja propia.
- ⚠️ **Border-radius resuelto a 10px vía Custom CSS** (el elemento no lo expone nativamente
  en su panel), verificado funcionando visualmente — pero abre la pregunta de si 10px es un
  cuarto token de diseño real; ver Sección 13.1.
- ⚠️ Dos notas menores sin resolver, no bloqueantes: "Toggle Icon Color" dice Fondo Blanco
  pero el icono se ve en rojo en la práctica (probablemente otro campo gobierna el color
  real, sin identificar cuál); "Toggle Active Accent Color" quedó vacío, sin decidir si debía
  rellenarse.
- 📌 **Pendiente de construcción real, no de configuración:** se detectaron 2 toggles ya
  construidos con contenido real que tenían la mitad de su composición interna vacía. Cuenta
  1 generó y guardó 2 propuestas de composición (imagen/vídeo + texto 50/50; texto arriba +
  media a ancho completo abajo) para aplicarlas construyendo la estructura de columnas dentro
  de cada toggle en Avada Live — requiere edición manual, no se resuelve desde Global
  Options.

### Necesito tarjetas que giran (imagen por un lado, texto por el otro)

→ **Elemento:** Flip Boxes

- ⚠️ **Ya en uso real para "¿Qué hacemos?"** (Seminarios, Grupo de alabanza, Día de
  familias) — no viene de los 5 documentos de esta ronda, viene del estado ya documentado del
  proyecto. Requirió un parche de CSS para funcionar bien, documentado como deuda técnica.
- ❌ **Migración recomendada a largo plazo, ya decidida en `ALCANCE_WEB_NUEVA.md`:** pasar de
  Flip Boxes a **Column + Title + Text Block** (padding nativo, sin conflictos de tipografía
  JS, vinculación de toda la tarjeta sin código). Los tres elementos de destino de esa
  migración (Column, Title, Text Block) YA tienen estilos por defecto revisados por Cuenta 1
  — ver entradas más abajo — lo que facilita esa migración cuando se decida acometerla.
- 🔲 Flip Boxes en sí **no tiene entrada propia en ninguno de los 5 informes de esta ronda**
  — su estilo por defecto en Avada Builder Elements sigue sin revisar formalmente.

### Necesito ampliar una imagen o vídeo al hacer clic

→ **Elemento:** Options → Lightbox (comportamiento global) + Lightbox Element (inserción
puntual) — **⚠️ son dos superficies de configuración relacionadas pero distintas**

- ✅ **Comportamiento global configurado por Cuenta 2:** Lightbox On · For Featured Images On
  Single Post Pages On · Skin **Metro White** · Background Opacity 0.90 · Arrows On ·
  Deeplinking On (permite compartir el enlace directo a una imagen concreta) · Autoplay Off
  (el visitante controla el ritmo).
- ✅ **Autocorrección documentada con honestidad por Cuenta 2:** había recomendado
  previamente el skin Dark; al revisarlo, eso no tenía en cuenta el principio de `00_CORE.md`
  "Superficies — NUNCA modo oscuro". Metro White + Background Opacity 0.90 logra el mismo
  efecto de foto destacada sobre fondo oscurecido sin violar ese principio de marca en los
  propios controles del lightbox.
- ⚠️ **Punto sin resolver del todo, sin urgencia:** "Lightbox Behavior = First Featured Image
  of Every Post" — la documentación oficial no aclara con detalle qué pasa exactamente al
  navegar con flechas entre dos entradas distintas. Comprobación aplazada a cuando haya
  contenido real que probar: abrir el lightbox en la primera ficha de un santo con foto y ver
  si las flechas saltan a la foto de otro santo. Si es así, cambiar a "Only Featured Image of
  Individual Post".
- 🔲 **Lightbox Element** (el elemento insertable para envolver una imagen/vídeo suelto en un
  "clic para ampliar", ya identificado como candidato en `GUIA_AVADA_LOCAL.md` Sección 9,
  junto a Modal Element, para reemplazar el modal custom de la web vieja) — su estilo
  hereda presumiblemente del comportamiento global de arriba, pero esto no está verificado
  específicamente en esta ronda.

### Necesito una ventana emergente con contenido libre (no solo imagen/vídeo)

→ **Elemento:** Modal Element

- ✅ **Identificación del elemento** ya confirmada contra documentación oficial en
  `GUIA_AVADA_LOCAL.md` Sección 9, como alternativa a Lightbox Element cuando el contenido no
  es solo imagen/vídeo.
- 🔲 **Estilo por defecto sin revisar** — aparece explícitamente en la lista de "elementos
  todavía sin revisar" del informe de Cuenta 1 (Avada Builder Elements).

### Necesito un carrusel de imágenes

→ **Elemento:** Image Carousel Element (probablemente el "Carousel" revisado por Cuenta 1)
— ⚠️ **si el carrusel mezcla vídeo, el candidato es otro: Avada Slider Element**

- ✅ **"Carousel" configurado por Cuenta 1:** Navigation Box Color = Texto Principal, Hover =
  Rojo Director, Autoplay Speed subido de 2500ms a 5000ms (más tiempo de lectura para
  contenido con texto). Panel completo de solo 3 campos, todos revisados.
- ⚠️ **No tengo certeza total de que "Carousel" (nombre corto usado por Cuenta 1) y "Image
  Carousel Element" (nombre completo ya documentado en `GUIA_AVADA_LOCAL.md` Sección 9) sean
  literalmente el mismo elemento** — es la lectura más probable, pero no está confirmado
  explícitamente en ninguno de los dos documentos.
- ✅ Image Carousel Element ya confirmado contra documentación oficial: 5 layouts, autoplay,
  swipe táctil, lightbox integrado — pensado para imágenes, no confirma vídeo embebido.
- ✅ **Avada Slider Element** ya confirmado contra documentación oficial como mejor candidato
  si el carrusel mezcla imagen Y vídeo (YouTube/Vimeo) por diapositiva — relevante para
  seminarios o contenido de Rincón de Nico/Charlas de la Biblia si algún día llevan carrusel
  propio. **Sin estilo por defecto revisado por ninguna de las tres cuentas en esta ronda.**

### Necesito una galería de imágenes con paginación

→ **Elemento:** Gallery

- ✅ Configurado por Cuenta 1: Load More Button Text ya en español ("Cargar más") · Number
  of Columns 3 · Spacing 10px · Lightbox Content "None".
- ⚠️ Hover Type en "None" (sin efecto al pasar el ratón) — pendiente de confirmar si es la
  intención deseada o falta activarlo.

### Necesito insertar un vídeo nativo (no incrustado de YouTube)

→ **Elemento:** Video

- ✅ Configurado por Cuenta 1: Maximum Width 100% · Controls Show · Preloading Auto —
  estándar y correcto para vídeo dentro de Toggles (ej. dentro de un acordeón "¿Qué es X?").
- 🔲 Este elemento es para vídeo autoalojado; para contenido de YouTube (seminarios, Rincón de
  Nico) el patrón ya establecido es distinto (JSON + generador, `tt_seminarios_json_url` /
  `tt_youtube_json_url`), no pasa por este elemento.

### Necesito insertar audio nativo

→ **Elemento:** Audio — **⚠️ esto NO gobierna el reproductor de podcast real**

- ✅ Configurado por Cuenta 1: Fondo Texto Principal (oscuro) · Progress Color Fondo Blanco ·
  Controls "Light" — coherente con el reproductor de vídeo ya visto en Toggles.
- ❌ **Aclaración importante:** este panel NO gobierna el reproductor de podcast real del
  sitio (`TT Podcast`, prefijo `.pp-*`), que sigue siendo un snippet custom aparte,
  documentado como deuda técnica intencional sin migrar (`00_CORE.md` Sección 10). Este
  elemento solo afecta a audios nativos insertados directamente vía Builder.
- ⚠️ Border Radius en 0px, pendiente de la decisión general de radio (Sección 13.1).

### Necesito mostrar testimonios (ej. testimonios de intercesión del Ejército de Intercesores)

→ **Elemento:** Testimonials

- ✅ Configurado por Cuenta 1: Background Superficie secundaria · Text Color Texto Principal
  · Speed 4000ms · Random Order Off.
- ⚠️ Border Radius en 0px, misma nota pendiente que Toggles/Audio (Sección 13.1).
- 🔲 **Conexión directa con contenido real, sin resolver:**
  `METODOLOGIA_CONSTRUCCION.md` deja abierta la pregunta de si "Testimonios" del Ejército de
  Intercesores se construye con Post Cards (si son muchos) o contenido estático (si son
  pocos) — el elemento Testimonials en sí es un tercer candidato no evaluado todavía frente a
  esos dos, y merece considerarse cuando se resuelva el volumen aproximado.

### Necesito iconos con estilo de marca en toda la web

→ **Elemento:** Icon

- ✅ Configurado por Cuenta 1: Background On, color Rojo Hover · Icon Color/Hover Color en
  Fondo Blanco · Border Radius 50% (círculo perfecto, sin relación con el debate 10px/25px).
- ✅ Corregido: Icon Hover Background Color de un gris neutro a Rojo Director, para que el
  patrón de interacción sea coherente (más color al pasar el ratón, no menos).

### Necesito mostrar contadores numéricos (ej. "X seminarios realizados")

→ **Elemento:** Counter Boxes

- 🔲 **Sin caso de uso identificado** en `ALCANCE_WEB_NUEVA.md`. Valores revisados por
  Cuenta 1 sin bandera roja (colores coherentes con la paleta), pero no se invirtió más
  tiempo sin un caso de uso real.

### Necesito que columnas y contenedores tengan espaciado consistente por defecto

→ **Elementos:** Column, Container

- ✅ **Column** — Margins 0/20px · Spacing 4% · Medium hereda de Large · Small a Full Width.
  Sin cambios respecto al criterio ya decidido en sesión previa.
- ✅ **Container** — Padding Site Width 0/0/0/0 · Padding 100% Width 0/30/0/30px (coherente
  con la decisión de `Default Page Template = 100% Width`, Sección 2.1) · Border Color =
  Separador · **Legacy Container Support en Off** — decisión correcta para una instalación
  nueva; el propio panel avisa de que revertirlo después obligaría a re-editar cualquier
  página ya guardada, y como todavía no hay páginas construidas, este es el momento correcto
  para fijarlo así.
- ⚠️ Container 100% Height (Navigation, Animation, Scroll Sensitivity, Dots) sin revisar en
  profundidad — baja prioridad, dado que el criterio ya fijado en `GUIA_AVADA_LOCAL.md`
  Sección 8.4-bis descarta secciones a pantalla completa salvo excepción justificada, así que
  es poco probable que esta función llegue a usarse.

### Necesito botones con estilo de marca por defecto

→ **Elemento:** Button

- ✅ **4 inconsistencias detectadas y corregidas** por Cuenta 1: Font Family de "Public Sans"
  (ajena a la identidad, probablemente arrastrada de una plantilla sin limpiar) a Helvetica
  Neue · Border Radius de 4px a 25px · Gradient Color/Hover Color **estaban invertidos** (rojo
  oscuro en reposo, rojo vivo en hover — al contrario del patrón esperado), corregido a Rojo
  Director en reposo, Rojo Hover al pasar el ratón.
- 🔲 Border Size 0, por lo que Border Color/Hover Color no tienen efecto real. Bevel Color sin
  efecto porque Button Type está en "Flat", no "3D" — mencionado solo para que no se pierda
  tiempo ajustando campos sin efecto visible.

### Necesito que las imágenes tengan un estilo consistente

→ **Elemento:** Image

- ✅ Configurado por Cuenta 1: Style Type "None" (sin sombra por defecto, cada instancia
  puede activar su propio estilo) · Border Radius global en 0px, **dejado deliberadamente
  neutro** — cada imagen puede llevar su propio radio según el contexto visual (a sangre
  completa vs. dentro de una card), en vez de forzar un único valor global.

### Necesito títulos de sección con separador y márgenes consistentes

→ **Elemento:** Title

- ✅ Configurado por Cuenta 1: Separator None · Margins 10/0/15/0px · Mobile Margins
  10/0/10/0px. Sin problemas detectados.

### Necesito texto organizado en varias columnas (tipo periódico)

→ **Elemento:** Text Block

- ✅ Configurado por Cuenta 1: Inline Columns en 1 (función de texto multi-columna no
  activa) — coherente con que no hay ningún caso de uso documentado de texto en columnas.

### Necesito animaciones de aparición al hacer scroll

→ **Elemento:** Animations

- ✅ Configurado por Cuenta 1: Offset "Top of element hits bottom of viewport" (estándar,
  razonable).
- ⚠️ **Element Appearance Animations en "Desktop Only"** — decisión a confirmar
  conscientemente: las animaciones de aparición al hacer scroll están desactivadas en móvil.
  Puede ser una decisión de rendimiento correcta, coherente con el resto de optimizaciones ya
  activas, pero el equipo debe saber que el efecto visual será distinto entre escritorio y
  móvil antes de darlo por cerrado.

---

## 6. Formularios, contacto, mapa y búsqueda

### Necesito que todos los formularios del sitio tengan estilo de marca

→ **Elemento:** Options → Forms

- ✅ Configurado por Cuenta 2: Input/Select Height 50px (cómodo para móvil) · **Font Size
  16px** — el equipo lo fijó así, mejor que la sugerencia inicial de 15px: Safari en iPhone
  hace zoom automático al enfocar un campo con letra menor de 16px, y este valor lo evita en
  un proyecto mobile-first · Border Size 1px · Border Color On Focus corregido a Color 5
  (Rojo Director) — el original (Color 4, gris apagado) apenas destacaba al enfocar un campo,
  importante en formularios como el de inscripción del Ejército de Intercesores · Enable
  Date/Time Picker Localization corregido a On — sin esto, cualquier selector de fecha
  saldría con nombres de mes/día en inglés.
- ⚠️ **Form Border Radius = 10px, por decisión de Hna C** — no coincide con ningún token
  `--tt-r-*` documentado hoy (25px/14px/8px). Ver Sección 13.1, conecta directamente con el
  mismo hallazgo de Toggles.
- 🔲 Sin credenciales todavía, correctamente vacío, no urgente: Cloudflare Turnstile, Google
  reCAPTCHA (Security Score en 0.0 — recordar subirlo cuando se active de verdad), HubSpot,
  Mailchimp.
- ⚠️ **El botón "Enviar" y los mensajes de error/éxito de un formulario NO viven en este
  panel** — se configuran al construir cada formulario real, en el Form Builder de Avada, no
  en Global Options.

### Necesito un formulario de contacto y mapa ya listos de fábrica

→ **Elemento:** Options → Contact Template — 🚨 **hallazgo urgente, léase antes de nada**

- 🚨 **Acción de limpieza recomendada YA, independientemente de si se usa esta plantilla:**
  el campo de dirección del mapa tenía puesto el texto de ejemplo de fábrica de Avada
  ("775 New York Ave, Brooklyn, Kings, New York 11203"), sin sustituir. Si por error se
  activa esta plantilla antes de corregirlo, el sitio mostraría un mapa de Brooklyn en una
  web católica en español. Esto es limpieza de seguridad, no una decisión de producto — no
  hace falta esperar a nada para vaciarlo.
- ⏸️ **Todo lo demás sigue bloqueado por la decisión de arquitectura pendiente 3.3** (¿hace
  falta página de Contacto?). Solo funciona si se usa literalmente la plantilla de página
  "Contact" que trae Avada de fábrica — no aplica a una página de contacto construida a mano
  en Fusion Builder, salvo la clave de Google Maps, que sí es compartida con cualquier mapa
  del sitio (ver entrada siguiente).
- 🔲 Recomendado activar "Display Data Privacy Confirmation Box" (hoy Off) el día que se use
  un formulario público que recoja datos — conecta con Privacy & Consent (Sección 9.2).

### Necesito un mapa de Google embebido

→ **Elemento:** Google Map — comparte clave API con Contact Template (entrada anterior)

- ⚠️ Google API Type en "JS API" (más personalización, con posible coste según volumen de
  uso, frente a Embed/Static API que son siempre gratuitas). Directamente ligado a la
  decisión de arquitectura 3.3 — no se invierte más tiempo en el resto del panel de estilo
  hasta que se retome esa decisión.

### Necesito una caja de búsqueda y una página de resultados

→ **Elemento:** Options → Search (Search Form + Search Page)

- ✅ Configurado por Cuenta 3 y cerrado casi del todo: Design "Clean" · Live Search On ·
  Minimal Character Count bajado de 4 a 3 (por nombres cortos como "Ana" o "Pío") · Search
  Page Layout Grid 3 columnas · Pagination Type cambiado a "Load More Button" (menos fricción
  en móvil que la paginación clásica) · Excerpt Length subido de 10 a 20 palabras · Results
  Meta reducido a Date + Read More Link (se quitaron Author — mostraría el usuario técnico
  interno —, Comments y Categories).
- ❌ **"Limit Search Results Post Types" en Off, a propósito** — atado a la decisión
  pendiente 3.1 (CPT vs Posts para Hombres de Dios), igual que Breadcrumbs.

---

## 7. Redes sociales y compartir

### Necesito mostrar iconos de redes sociales en el header o el footer

→ Configuración correspondiente: **Header Builder / Footer Builder — NO Global Options**

- ❌ **"Header Social Icons Styling" (Global Options) no tiene ningún efecto** — el propio
  panel avisa que un override global de Header ya está en uso. Esto es un ejemplo directo de
  por qué las secciones de Menú/Header/Footer de Avada Options quedan inactivas una vez hay
  una Header Section personalizada asignada al Layout Global (`GUIA_AVADA_LOCAL.md` Sección
  3, ya confirmado como causa raíz de un problema de la web vieja). **La configuración real
  de iconos sociales en el header vive en el Header Builder, no aquí.** Ver también Sección
  13.5.

### Necesito botones de compartir en publicaciones

→ **Elemento:** Options → Social Sharing

- ✅ Configurado y cerrado por Cuenta 3: Tagline "¡Comparte esta historia, elige tu
  plataforma!" · Background Superficie secundaria · Redes activas reducidas de 11 a 5
  (Facebook, WhatsApp, Telegram, Email, Copy Link — se quitaron X, Bluesky, Reddit, LinkedIn,
  Tumblr, Xing por no encajar con el público real del sitio) · Icon Font Size 16px · Icons
  Boxed Off.
- 🔲 **Sigue bloqueado, sin dato:** las URLs reales de Instagram/YouTube/Facebook de
  Tiritaito — necesarias para el repositorio de "Social Media Icons/Links" (una pieza
  distinta de Social Sharing), nadie las ha confirmado todavía. Es la pieza más antigua sin
  resolver de todo el reparto de Cuenta 3.

---

## 8. Identidad visual y aspectos transversales

### Necesito la paleta de colores de marca disponible en cualquier selector de Avada

→ **Panel:** Global Options → Colors

- ✅✅✅ **RESUELTO CON ALTA CONFIANZA — pregunta que llevaba abierta desde antes de esta
  ronda.** El orden real de los 8 slots de color que dejó el Setup Wizard en
  `tiritaito-real.local` es:

  | Slot | Variable `--tt-*` | Hex | Nombre en el panel real de Avada |
  |---|---|---|---|
  | Color 1 | `--tt-bg` | `#FFFFFF` | Fondo Blanco |
  | Color 2 | `--tt-surf2` | `#F5F5F7` | Superficie secundaria |
  | Color 3 | `--tt-sep` | `#c7c7cc` | Separador |
  | Color 4 | `--tt-txt4` | `#86868b` | Marcador de posición de texto |
  | Color 5 | `--tt-red` | `#BF4646` | Rojo Director (ver nota de nomenclatura abajo) |
  | Color 6 | `--tt-red-d` | `#A33B3B` | Rojo Hover |
  | Color 7 | `--tt-txt2` | `#3a3a3c` | Texto secundario |
  | Color 8 | `--tt-txt` | `#1d1d1f` | Texto Principal |

  **Evidencia, de más a menos directa:**
  1. Cuenta 1 verificó esta tabla **directamente en el panel real** (nombre↔hex↔variable),
     confirmada por triple fuente: Álvaro directamente, y de forma independiente por Cuenta 2
     en su propio informe.
  2. Cuenta 2 había llegado a esta misma secuencia de forma **indirecta**, cruzando pistas de
     Forms y de General Blog (ej. "Color 2 se ve casi invisible en el fondo de una cajita de
     fecha" → coincide con `--tt-surf2`), con tres puntos confirmados de forma literal
     (Color 2, Color 5, Color 8) y el resto inferido por coherencia.
  3. **Coincide exactamente**, sin que nadie lo buscara a propósito, con el uso que hace
     Cuenta 3 de `Var(--awb-color5)` = Rojo Director y `Var(--awb-color6)` = Rojo Hover en
     Breadcrumbs, y `Var(--awb-color2)` = Superficie secundaria en Page Title Bar.
  4. **Coincide también, palabra por palabra**, con el orden claro→oscuro ya descrito en
     `GUIA_AVADA_LOCAL.md` Sección 4.0.1 como resultado del Wizard (7 julio 2026).
- ❌ **Este orden NO coincide con la tabla "final" de `GUIA_AVADA_LOCAL.md` Sección 4.1**,
  que pone Color 1 = `--tt-red` y arranca por el rojo en vez de por el blanco. Esa tabla no
  refleja lo que hay realmente en Local — ver Sección 13.2, es una de las cinco cosas a
  llevar a Proyecto 2.
- ⚠️ **Nomenclatura confusa dentro del propio Avada:** "Rojo Director" no es un nombre
  intuitivo — es el rojo principal de marca, no un color especial de "director". Cuenta 1
  recomienda renombrarlo a algo como "Rojo Principal" para no confundirlo con "Rojo Hover".
- 🔲 **Los 5 colores restantes (Colores 9-13: `--tt-red-bg`, `--tt-txt3`, `--tt-green`,
  `--tt-orange`, `--tt-alert`) siguen sin cargar**, confirmado por Cuenta 3 en su propia
  sesión (Breadcrumbs usa hex directo para `--tt-txt3` precisamente por esto). Se añaden
  progresivamente según necesidad, tal como ya establece `GUIA_AVADA_LOCAL.md` Sección 4.0.1
  — no es un pendiente urgente, pero conviene saber que a fecha de esta ronda de informes
  seguían sin estar.

### Necesito que Yeah Papa y Helvetica Neue estén activas en todo el sitio

→ **Panel:** Global Options → Typography

- ✅ Confirmado por Cuenta 1: Yeah Papa (títulos) y Helvetica Neue (cuerpo) en Custom Fonts,
  sin fuentes de Google Fonts detectadas. Los 6 tamaños de heading (50/43/37/31/25/20px)
  confirmados como ya verificados con Hna C en sesión previa.
- ⚠️ **Hallazgo confirmado 3 veces de forma independiente: Yeah Papa necesita un tamaño en
  px notablemente mayor que Helvetica Neue para el mismo peso visual** — la fuente "ocupa
  menos" espacio dentro de la misma caja de diseño. Confirmado por Cuenta 1 y de forma
  cruzada por Cuenta 3 (cuyo Page Title Bar usa 54px de Heading Font Size, consistente con
  esta calibración, aunque el detalle exacto de esa nota interna no está en el texto
  disponible para este catálogo). **Recomendación de Cuenta 1, sin aplicar todavía:**
  incorporar esta nota a `00_CORE.md` Sección 6, para que cualquier título nuevo en Yeah Papa
  se calibre con esto en mente desde el principio. Ver Sección 13.4.

### Necesito el logo en el header, en sticky y en móvil

→ **Panel:** Global Options → Logo

- ✅ Logo definitivo subido en Default Logo, con transparencia confirmada.
- ⚠️ Sticky Header Logo y Mobile Logo dejados vacíos **a propósito** — heredan del Default
  Logo hasta que el Header Builder esté más avanzado. No es un olvido.
- ✅ Alignment Left · Background Off (correcto, el logo ya lleva fondo circular incorporado)
  · Custom Link URL vacío — verificados correctos.
- ⚠️ Logo Margins (34/34/0/0px) en valor por defecto — pendiente de ajuste fino visual cuando
  el header esté construido de verdad; no es un pendiente técnico, es una decisión estética a
  verificar en contexto real.

### Necesito confirmar que el fondo del sitio sea blanco puro (identidad de marca)

→ **Panel:** Global Options → Background

- ✅ Verificado visualmente por Cuenta 1: el fondo se ve blanco puro, pese a que los campos
  internos muestran `Var(--awb-color3)`/`Var(--awb-color1)` (una particularidad de
  referencia interna de Avada, sin efecto visual real dado el mapeo confirmado arriba). Sin
  imagen de fondo, sin patrón — correcto.

### Necesito que los puntos de cambio de pantalla (breakpoints) de Avada coincidan con el código

→ **Panel:** Global Options → Responsive — ✅ **pregunta abierta de `GUIA_AVADA_LOCAL.md`
Sección 19 (#5), ahora resuelta**

- ✅ **Confirmado por Cuenta 1:** los 5 breakpoints reales de Avada (Small/Medium en el panel
  de Elementos, Header/Site Content/Sidebar, Grid) se subieron de sus valores originales
  (480/768, 800×3, 1000) a **~1024px**, alineados con el estándar del código de los snippets
  (`00_CORE.md` Sección 7). Verificado con capturas reales antes y después, en varios anchos
  de pantalla: **sin ningún síntoma de rotura en ningún punto probado.** El punto de entrada
  a "modo escritorio completo" se movió correctamente al lugar esperado.
- ⚠️ **Único matiz sin cerrar del todo:** comportamiento específico del header en la franja
  800–1024px — en todas las capturas disponibles el header ya aparecía en su versión
  compacta en todos los anchos, sin poder confirmar si existe una versión de escritorio
  distinta. Si el header de Tiritaito usa un único diseño sin importar el ancho (lo más
  probable dado el diseño "Studio" ya elegido), esto no tiene relevancia práctica.

### Necesito CSS que no cabe en ningún panel de Avada

→ **Panel:** Global Options → Custom CSS

- ⚠️ **Ya no está vacío** — contiene una regla justificada para resolver el border-radius de
  Toggles (10px, ver Sección 5 y Sección 13.1), dado que ese elemento no expone ese control
  en su propio panel. Cualquier documento que dé por hecho "Custom CSS sin deuda técnica"
  necesita actualizarse — ver Sección 14.

---

## 9. Rendimiento y privacidad

### Necesito que el sitio cargue rápido

→ **Panel:** Global Options → Performance

- ⚠️ **Aviso importante de Cuenta 3:** el panel real es mucho más grande de lo que
  documentaba `GUIA_AVADA_LOCAL.md` Sección 4.4 — esa tabla cubría solo una fracción. Esta
  sesión la completó con capturas reales por primera vez.
- ✅ 4 ajustes nuevos aplicados y confirmados: Font Face Rendering Block→Swap Non-Icon Fonts
  (evita pantalla en blanco mientras carga Yeah Papa) · Preload Key Fonts Icon Fonts→All ·
  **Enable Video Facade Off→On** (carga solo miniatura de YouTube hasta pulsar play —
  relevante con Rincón de Nico, Charlas de la Biblia, seminarios) · Optimize Offscreen
  Rendering Off→On (mejora en páginas largas como Hombres de Dios o listados).
- ❌ Sin tocar, por diseño: "Enable Critical CSS" en Off y el propio Performance Wizard —
  ambos reservados a Fase 4 del roadmap ("QA y velocidad"), siguiendo la advertencia oficial
  de Avada de no tocarlos con el sitio a medio construir.
- 🔲 **Aviso técnico sin resolver, no bloqueante:** apareció el mensaje "JS Compiler is
  disabled. File does not exist or access is restricted" aunque el interruptor esté en On —
  puede ser un estado desactualizado. Recomendado probar "Reset Avada Caches" y comprobar si
  desaparece.

### Necesito gestionar el consentimiento de cookies y scripts de terceros

→ **Panel:** Global Options → Privacy & Consent

- ✅ Configurado por Cuenta 3: Google & Font Awesome Fonts Mode cambiado de CDN a **Local**
  (coherente con la decisión ya tomada de no depender de Google Fonts) · Privacy Consent
  Tools activado con antelación, mientras el sitio aún no tiene scripts de terceros — más
  fácil activarlo ahora que más adelante.
- ✅ **Conexión técnica confirmada con Advanced/Code Fields:** el sistema de scripts de
  tracking solo se bloquea hasta que el usuario consiente si "Privacy Consent Tools" está On
  — ya lo está, así que cuando se añada cualquier script de seguimiento, el mecanismo ya
  está listo detrás.
- 🔲 **Sigue apagado, correctamente:** la barra visible de consentimiento de cookies —
  necesita dos respuestas que nadie ha dado todavía: ¿qué herramientas de seguimiento tendrá
  el sitio? y ¿existe ya un texto legal de Política de Privacidad?

---

## 10. Comportamientos sueltos de UX (Extras)

Nueve piezas pequeñas revisadas por Cuenta 3, cada una resuelve una necesidad distinta y
puntual:

| Necesito... | Campo | Cambio aplicado |
|---|---|---|
| Un botón de "volver arriba" | ToTop → Script | Desktop → **Desktop & Mobile** (más útil en móvil, donde el scroll cuesta más) |
| Numeración de páginas en listados largos | Pagination → Box Width/Height | 30px → **40-44px** (accesibilidad táctil en móvil) |
| Numeración de páginas con la firma visual del sitio | Pagination → Border Radius | 0 → **14px** (coherencia con los 25px de marca) |
| Un carrusel de "también te puede interesar" al final de una entrada | Related Posts/Projects → Image Size | Fixed → **Auto** (Fixed encajaba con 940px; el sitio está en 100% Width) |
| Organizar tarjetas en cuadrícula | Grid/Masonry → Separator Style | Double Border Solid → **Solid** (más coherente con "iOS limpio") |
| Que el símbolo "[...]" de un resumen cortado sea clicable | Miscellaneous → Make Excerpt Symbol Link | Off → **On** |
| Buena práctica SEO en enlaces sociales | Miscellaneous → Add "nofollow" to social links | Off → **On** |

🔲 Sin cambios registrados en esta ronda, solo definidos conceptualmente: **Featured Image
Rollover** (efecto hover sobre imágenes destacadas en listados) y **Post Views/Visits
Counting** (contador interno, no público, de vistas por entrada) — existen, se revisaron
conceptualmente, sin decisión de valor pendiente identificada.

---

## 11. Funciones desactivadas o en pausa

### Portfolio

→ ❌ **Desactivado, confirmado de forma independiente por Cuenta 1 y Cuenta 3** (Cuenta 3
confirma con captura que ya venía desactivado antes de su sesión — sin conflicto entre
ambas).

- ⚠️ Ruta real de desactivación, no obvia: no existe un panel "Portfolio" con un simple
  interruptor — vive en `Advanced → Post Types → Avada Portfolio → Off`.
- 📌 Hallazgo antes de apagarlo: el panel de estilo de Portfolio (ya inaccesible) tenía
  valores que no son los de fábrica (columnas=1, slug escrito a mano, permalink activado,
  fuente 13px) — alguien del equipo entró aquí en algún momento sin dejarlo documentado.
  Verificado que no había ninguna entrada real de Portfolio, así que apagarlo no supuso
  pérdida de contenido.
- ✅ Resuelve formalmente la pregunta abierta #3 de `ALCANCE_WEB_NUEVA.md` y la nota "marcada
  para reconsiderar" de `GUIA_AVADA_LOCAL.md` 4.0.1.

### Elastic Slider

→ ❌ **Desactivado, sin acción necesaria.**

- ✅ Confirmado por Cuenta 2, por captura y por el propio panel de Avada: "Elastic Slider is
  disabled in Advanced > Post Types section." Función Legacy, apagada de fábrica. Activarla
  exigiría reactivar "jQuery Migrate", en conflicto directo con la decisión de rendimiento ya
  tomada (`GUIA_AVADA_LOCAL.md` 4.4: Remove jQuery Migrate = Sí).

### Sliding Bar (panel legacy — no confundir con el tipo de Off-Canvas, ver Sección 3)

→ 🔲 **Sin uso decidido — candidato a desactivar, como Portfolio.**

- ⚠️ Corrección conceptual de Cuenta 3: no es un carrusel automático (como se pensó al
  principio de esa sesión) — es un cajón de contenido deslizante que se abre/cierra con un
  botón. El propio panel recomienda usar el Off Canvas Builder en su lugar — el mismo sistema
  moderno que ya se usa para el menú móvil.
- 🔲 Sin ningún caso de uso confirmado en `ALCANCE_WEB_NUEVA.md`. Valores actuales
  documentados (posición Top, sticky On, 2 columnas, toggle circular) pero sin recomendación
  de invertir tiempo sin necesidad real.
- Ideas sin decidir, solo como punto de partida para el equipo: acceso rápido a "Pide
  oración", mini-resumen del Devocional del día, enlaces de donación/redes. Ninguna pedida
  por el equipo todavía.

### Maintenance Mode

→ ❌ Off — correcto, la web está en desarrollo activo. Contenido del mensaje (para cuando se
acerque el lanzamiento) pendiente sin urgencia.

### Custom Auth Pages

→ ✅ Todo en "WP Default" — estado seguro y neutro, no un pendiente sin resolver. Confirma
que no hace falta tocar nada mientras no exista una zona pública con login (favoritos,
cuentas de usuario). Pendiente solo si en algún momento se decide construir esa zona —
decisión de producto, no técnica.

---

## 12. Elementos sin revisar todavía

De los cerca de 100 elementos activos en `Avada → Options → Advanced → Avada Builder
Elements`, estas son las piezas explícitamente nombradas pero no revisadas en profundidad en
esta ronda:

| Elemento | Estado | Nota |
|---|---|---|
| Modal | 🔲 Sin estilo revisado | Caso de uso ya identificado (ventana emergente con contenido libre) — ver Sección 5 |
| Section Separator | 🔲 Sin revisar | Por nombre y uso estándar en Avada (no verificado contra Tiritaito): probablemente una línea/elemento decorativo para separar dos secciones visualmente |
| Alert | 🔲 Sin revisar | Por nombre y uso estándar (no verificado): probablemente un aviso o mensaje destacado dentro del contenido |
| Popover | 🔲 Sin revisar | Por nombre y uso estándar (no verificado): probablemente información adicional al pasar el ratón o hacer clic sobre un elemento pequeño |
| FAQ | 🔲 Sin revisar | Elemento dedicado, distinto de Toggles — vale la pena evaluar si encaja mejor que reutilizar Toggles para una futura página de preguntas frecuentes, si llegara a haberla |
| Blog | 🔲 Cerrado en una sesión previa a esta ronda, sin detalle disponible aquí | Documentado en el informe de Cuenta 1 como "sin uso actual pero con potencial futuro" — el porqué de la configuración no se pierde, pero este catálogo no tiene el detalle de esa sesión anterior |

**El resto (~70 elementos adicionales) no se ha inventariado todavía ni siquiera por
nombre.** No invento nombres que no tengo evidencia de haber visto en ningún informe. Pendiente:
una sesión que copie la lista completa desde `Avada → Options → Advanced → Avada Builder
Elements` y añada aquí, entrada por entrada, al menos el nombre con marcador 🔲 — así el
catálogo tiene ya la forma completa aunque la profundidad se rellene con el tiempo.

---

## 13. Las cinco cosas a medio cerrar — para llevar a Proyecto 2

Ya están resueltas o documentadas donde corresponde dentro de este catálogo. Las repito aquí
juntas porque tú no editas `GUIA_AVADA_LOCAL.md` ni `00_CORE.md` directamente — esto es lo
que Hno C (Proyecto 2) necesita aplicar en los documentos oficiales.

### 13.1 Border-radius 10px — ¿un cuarto token real?

Aparece **dos veces de forma independiente**, en Toggles (Sección 5) y en Form Border Radius
(Sección 6), las dos por decisión de Hna C. Podría ser un cuarto token real ("elementos
interactivos pequeños = 10px, contenedores grandes = 25px") en vez de dos excepciones
sueltas sin relación. La evidencia está documentada tal cual en las dos entradas
correspondientes de este catálogo — la decisión de si se formaliza como token nuevo en
`00_CORE.md` Sección 5 no es mía. Relacionado: el propio Custom CSS (Sección 8) ya no está
vacío por esta misma razón (la regla de Toggles vive ahí), y la pregunta abierta de Cuenta 1
sigue sin responder: ¿10px sustituye a 25px como estándar global, convive como valor nuevo, o
queda solo en estos dos casos? Mientras no se resuelva, Tabs, Testimonials y Audio se
quedaron con border-radius en 0px a propósito, para no rellenar un valor que pueda tener que
deshacerse.

### 13.2 Orden real de los 8 colores del Wizard — contradice la tabla de GUIA_AVADA_LOCAL.md 4.1

Resuelto con confianza alta en este catálogo (Sección 8.1) — ahora con evidencia directa
(Cuenta 1, triple fuente), no solo reconstruida por indicios (Cuenta 2). El orden real
(Color 1 = `--tt-bg`, ... Color 5 = `--tt-red`, ...) coincide exactamente con
`GUIA_AVADA_LOCAL.md` Sección 4.0.1, pero **contradice directamente** la tabla de la Sección
4.1 de ese mismo documento, que empieza por `--tt-red` en Color 1. No corrijo
`GUIA_AVADA_LOCAL.md` yo mismo — queda anotado aquí para que Proyecto 2 reconcilie ambas
secciones, probablemente sustituyendo el Color 1-8 de la Sección 4.1 por el orden ya
confirmado, dejando Colores 9-13 como pendientes de cargar (siguen sin estar, confirmado por
Cuenta 3).

### 13.3 "Default Page Template = 100% Width" — explicación de tres capas

Documentada consistentemente en este catálogo (Sección 2.1, referenciada en Blog Single Post
y Container) como: (1) el valor global no fuerza nada a pantalla completa, (2) solo
desbloquea la opción por Container, (3) activar el ancho completo sigue siendo siempre una
decisión explícita, sección por sección. Uso esta explicación en vez de cualquier versión
simplificada que pueda haber quedado en otro documento, tal como se me indicó. Nota aparte:
Cuenta 3 reporta que el equipo revirtió esta opción de "Site Width" a "100% Width" el 30 de
julio de 2026, y que esto contradice lo que según ella dice hoy `GUIA_AVADA_LOCAL.md` Sección
4.0.1 sobre un "conflicto crítico ya resuelto" en sentido contrario. **No puedo verificar esa
contradicción específica por mi cuenta** — la versión de `GUIA_AVADA_LOCAL.md` que tengo
disponible no incluye ningún pasaje sobre "Default Page Template" en su Sección 4.0.1, así
que puede que sea una versión distinta a la que Cuenta 3 tenía delante. Lo que sí es seguro,
porque lo confirman dos cuentas de forma independiente (Cuenta 2 en su informe de Layout y
Cuenta 3 en su aviso cruzado), es que el valor real y actual en Local es **100% Width**.
Proyecto 2 debería confirmar y anotar el motivo del cambio, no solo el valor final, para que
la próxima persona que lea el documento sepa si confiar en él.

### 13.4 Slideshows ≠ Post Slider

Resuelto y aplicado de forma consistente en todo este catálogo (Sección 4) — cada vez que
aparece uno de los dos, se aclara la diferencia con el otro. Ya lo cito también en la entrada
de Flip Boxes (Sección 5) y en la de Page Title Bar (Sección 2) por el mismo espíritu de
calibración tipográfica de Yeah Papa que comparten.

### 13.5 Menú/Header/Footer de Avada Options — inactivos por el override de Layout

Documentado con un ejemplo concreto y verificable en la Sección 7 (Social Media): "Header
Social Icons Styling" no tiene efecto porque ya hay una Header Section personalizada
asignada al Layout Global. Cualquier mención en el catálogo a "dónde vive la configuración
del menú/header/footer" apunta al Header Builder / Off Canvas Builder (Sección 3), no a
Global Options — coherente con la causa raíz ya documentada en `GUIA_AVADA_LOCAL.md` Sección
3. Esto también resuelve, con alta probabilidad, la pregunta que Cuenta 2 dejó abierta sobre
qué cubren las "Secciones 4, 5 y 10 de Global Options" sin dueño asignado entre las tres
cuentas — si esas secciones corresponden a Menú/Header/Footer (lo más probable dado el
contexto de esa nota), ya no necesitan una cuarta ronda de revisión: están inactivas por
diseño, no por descuido.

### Hallazgos adicionales encontrados en esta síntesis, no pedidos explícitamente

No estaban en la lista de cinco, pero aparecieron al cruzar los informes y merece la pena que
Proyecto 2 los tenga también:

- **Breakpoints responsive ya confirmados en ~1024px** (Sección 8), resolviendo la pregunta
  abierta #5 de `GUIA_AVADA_LOCAL.md` Sección 19 — ese documento debería actualizarse para
  reflejar que esto ya no es un pendiente, salvo el matiz menor del header en 800-1024px.
- **Yeah Papa necesita más tamaño en px que Helvetica Neue** para el mismo peso visual,
  confirmado 3 veces de forma independiente (Sección 8.2) — recomendado incorporar a
  `00_CORE.md` Sección 6.
- **Custom CSS ya no está vacío** (Sección 8.6) — cualquier documento que asuma "sin deuda
  técnica en Custom CSS" necesita actualizarse.
- **Posible solapamiento sin resolver entre "Avada Builder Elements → Events" y "Options →
  Events Calendar"** (Sección 4) — dos informes tratan "Eventos" con estados de cierre
  distintos (uno lo da por cerrado, el otro deja una decisión de producto pendiente); no
  tengo certeza de si es el mismo panel visto en dos momentos o dos paneles relacionados
  pero diferentes.
- **El elemento "Blog" de Avada Builder Elements** aparece mencionado como ya cerrado en una
  sesión anterior a esta ronda, sin que el detalle de esa sesión esté disponible en ninguno
  de los 5 documentos — solo se puede dejar constancia de que existe, no completarlo aquí.

---

## 14. Próximos pasos y preguntas abiertas

### Acciones sueltas, ninguna bloqueante salvo la marcada 🚨

| # | Acción | Urgencia | Sección |
|---|---|---|---|
| 1 | 🚨 Vaciar la dirección de ejemplo de Brooklyn en el mapa de Contact Template | Alta — riesgo de vergüenza pública si se activa la plantilla por error | 6 |
| 2 | `WordPress → Ajustes → Comentarios` → desmarcar envío de comentarios nuevos | Media | 4 |
| 3 | `WordPress → Ajustes → Generales → Idioma del sitio` → confirmar que está en español | Media — afecta formatos de fecha en varios sitios | 4 |
| 4 | Renombrar "Rojo Director" a "Rojo Principal" en el panel de Colors | Baja | 8.1 |
| 5 | Localizar los 4 colores del botón "Load More" (General Blog) | Baja, aplazado a propósito | 4 |
| 6 | Reconfirmar por captura: Navigation Arrow Size 14px (Slideshows) | Baja | 4 |
| 7 | Probar "Reset Avada Caches" para el aviso de "JS Compiler is disabled" | Baja | 9 |
| 8 | Añadir los 5 colores restantes (Colores 9-13) cuando haga falta un color de la paleta que todavía no esté cargado | Media, según necesidad | 8.1 |

### Preguntas que necesitan decisión de Hna C / Carlitos / el equipo

| # | Pregunta | Bloquea | Sección |
|---|---|---|---|
| 1 | ¿10px sustituye a 25px como radio estándar, convive, o queda solo en Toggles/Forms? | Border-radius de Tabs, Testimonials, Audio | 13.1 |
| 2 | ¿La tarjeta de "Próximos Eventos" debe mostrar texto de contexto, o todo va en el cartel visual (Events Text Display)? | Cierre de "Próximos Eventos" | 4 |
| 3 | ¿Hay uso real pensado para el Sliding Bar legacy, o se desactiva como Portfolio? | — | 11 |
| 4 | URLs reales de Instagram/YouTube/Facebook de Tiritaito | Repositorio de Social Media Icons/Links | 7 |
| 5 | ¿Qué herramientas de seguimiento tendrá el sitio? ¿Existe ya texto legal de Privacidad? | Barra de consentimiento de cookies | 9.2 |
| 6 | ¿Las animaciones de aparición deben verse igual en móvil que en escritorio, o "Desktop Only" es la decisión correcta? | Coherencia de experiencia entre dispositivos | 5 |
| 7 | ¿Gallery Hover Type debe tener algún efecto, o "None" es la elección deseada? | Cierre de Gallery | 5 |
| 8 | ¿"Avada Builder Elements → Events" y "Options → Events Calendar" son el mismo panel o dos paneles distintos? | Cierre real de "Próximos Eventos" | 4, 13 |
| 9 | ¿El menú de la web nueva lleva submenús desplegables? | Si el Off Canvas Builder basta o hace falta un workaround adicional | 3 |
| 10 | ¿Se confirma que el panel lateral de menú móvil ya está construido y probado en Local, más allá de la decisión y las instrucciones ya dadas? | Cierre real de la pieza de navegación móvil | 3 |

**Heredadas de otros documentos, ya conocidas, no se repiten en detalle aquí:** la decisión
3.1 (CPT vs Posts para Hombres de Dios) bloquea, además de lo ya documentado en
`ALCANCE_WEB_NUEVA.md`, varios campos de este catálogo (Breadcrumbs, Search); la decisión 3.3
(¿página de Contacto?) bloquea Contact Template y parte de Google Map.

### ¿Cambia esto el índice de `README.md`?

Sí — pero no lo edito yo, según lo pedido. `README.md` necesitaría, cuando alguien lo
actualice:
1. Una fila nueva en el "Índice de documentos", en la carpeta `03-guias-practicas/`, junto a
   `GUIA_AVADA_LOCAL.md` — propósito: "Qué elemento de Avada resuelve una necesidad de
   contenido concreta, y con qué certeza" · audiencia probablemente Hno A · mantiene
   Hno C (investigación) · frecuencia Media, crece con cada ronda de Global Options nueva.
2. Una línea nueva en el árbol de "Estructura", dentro de `03-guias-practicas/`.
3. Posiblemente una mención en "Cómo empezar, según quién seas" — la fila de "Hno A, sesión
   de Avada/maquetación" hoy solo lista `GUIA_AVADA_LOCAL.md` + `METODOLOGIA_CONSTRUCCION.md`;
   este catálogo encajaría ahí como tercera referencia.

---

*Para la mayor gloria de Dios · tiritaito.com*

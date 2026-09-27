# TIRITAITO.COM — Investigación: mejoras de Avada con Custom CSS + CSS ID (y Page Options)
**Cómo funciona de verdad, por qué a veces choca, y una lógica de trabajo para que el código encaje en Avada en vez de pelearse con él**
*Proyecto 2 (Hno C) · 23 septiembre 2026 · Destino sugerido en el repo: `02-metodologia/investigaciones/INVESTIGACION_CSS_MEJORAS_AVADA.md`*
*Estado: **PROPUESTA para revisión de Carlitos.** No se ha tocado ningún documento oficial ni se ha redactado ninguna instrucción para los Proyectos 3, 4, 6 y 7 — eso solo se hace después de que confirmes (Secciones 9 y 10).*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## Marcadores usados en este documento

| Marcador | Significa |
|---|---|
| ✅ | Confirmado: documentación oficial de Avada, o un archivo real que me pasaste |
| ⚠️ | Evidencia parcial (comunidad, hilos antiguos) o riesgo detectado |
| 🔲 | Hipótesis razonable, **por comprobar en Local con DevTools** — no darla por cierta |
| ❌ | Confirmado que no existe o que no debe hacerse |

Los términos técnicos se explican en una frase la primera vez que salen, y hay un glosario al final.

---

## 0. Resumen (2 minutos, para Hna C y para Carlitos)

**Qué se pidió:** entender bien el patrón que os ha funcionado (CSS en *Avada → Options → Custom CSS* + un ID en el elemento), qué es el CSS de *Page Options*, por qué a veces choca, y proponer una lógica de trabajo sin parches ni `!important` por sistema.

**Lo esencial:**

1. **El patrón es legítimo y es el que Avada documenta como método principal:** dar una clase o un ID al elemento y escribir el CSS "principalmente" en Global Options. ✅ No es un truco vuestro.
2. **Pero Avada también dice cuándo usar cada gancho: ID para algo único, clase para varios.** Vuestra idea ("un mismo CSS aplicado a todos los elementos que queramos") pide clase; un ID no puede repetirse en una misma página. ✅ Y la clase está prohibida desde el 6 de septiembre. **Esta es la decisión de fondo** (Sección 4.8).
3. **Page Options sí tiene CSS propio** (pestaña "Custom CSS"; solo afecta a esa página). Sirve para *datos o ajustes de una página*, no para el diseño reutilizable. ✅ Los documentos del proyecto hablan de un "campo de clase CSS de página" que la documentación oficial **no lista**: probablemente se refieran a esta pestaña (Sección 2.4).
4. **"A veces choca" tiene causas concretas y comprobables** (Sección 3). Las más probables en vuestro caso: (a) reglas sin gancho, (b) estilos que Avada escribe dentro del propio elemento, (c) orden de carga y cachés — Local no es producción, que usa LiteSpeed —, (d) **Avada 7.16 (4 de agosto) reconstruyó justo el elemento Tabs**, y (e) detalles de vuestro CSS: URLs de relleno, URLs del Local, una fuente que no consta cargada y colores sin respaldo.
5. **Propuesta:** tres niveles (Global / Variante / Página), una ficha de registro por mejora, diez reglas de escritura y una escalera para decidir si un `!important` está justificado (Sección 4).
6. **De vuestros tres bloques:** el de radios de Toggles está bien; el de Tabs tiene 4 fallos concretos que corregir antes de darlo por bueno; el de Toggle-intro funciona, pero de sus 24 `!important` la mayoría probablemente sobra (Sección 5).

**Cinco decisiones que necesito de Carlitos** (detalle en la Sección 10): **D1** ID / clase · **D2** qué hacer con el tope de 30 líneas de la GUÍA · **D3** dónde vive el CSS (base de datos o archivo) · **D4** el radio de 10px como token oficial y qué hacer con el 15px del vídeo · **D5** fijar la versión de Avada que se usa.

---

## 0 bis. Cómo encaja con las reglas actuales del proyecto — tres choques que hay que resolver a propósito

Antes de seguir: lo que habéis descubierto no es solo "una técnica más". Toca reglas que ya están escritas.

| # | Regla actual | Dónde está | Qué pasa con este patrón |
|---|---|---|---|
| 1 | El campo "Clase CSS" de un elemento/columna/container queda prohibido desde el 6/09/2026; el Custom CSS global se permite "bajo vigilancia" | `GUIA_AVADA_LOCAL.md` §8, §12 · `CATALOGO_ELEMENTOS_AVADA.md` §5 bis | El ID es **otro campo**, así que la letra no se incumple. Pero el mecanismo es el mismo: un gancho que escribe el editor en un campo del elemento + reglas en el CSS global. Hay que **decidirlo**, no dejar que se cuele por otra puerta. |
| 2 | Custom CSS global: "máximo 30 líneas, bien comentadas" | `GUIA_AVADA_LOCAL.md` §12 (regla de oro) | El Custom CSS que pasaste tiene del orden de **170 líneas** (estimado a ojo; cuéntalas en el editor para la cifra exacta). El criterio de "vigilancia" ya está superado de hecho. Pregunta abierta #11 del CATALOGO §14. |
| 3 | "CSS de un módulo específico → dentro del propio snippet; nunca en Custom CSS global" | `GUIA_AVADA_LOCAL.md` §12 | Los bloques de Tabs y Toggle-intro son CSS de piezas concretas y viven en el global. Tiene sentido (no son snippets, son elementos de Avada), pero contradice la letra. |
| (menor) | Convención `var(--tt-*, respaldo)` y "nunca hex sueltos" | `GUIA_AVADA_LOCAL.md` §13 | El bloque de Tabs usa hex (`#3a3a3c`); el de Toggle-intro usa `var(--tt-txt2)` sin respaldo. |

**Mi lectura (no un hecho):** el tope de 30 líneas y la regla de "nunca en global" se pensaron para Code Snippets, no para mejoras de elementos de Avada. Este patrón es una categoría nueva y necesita **su propia regla** (Sección 4). Lo decide Carlitos. No tengo `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` (Secciones 1 y 6) en esta sesión, así que **no puedo comprobar el motivo original de la prohibición**; si ese motivo era otro del que supongo, la recomendación de la Sección 4.8 puede cambiar.

---

## 1. Qué se usó y qué NO se pudo hacer

| Usé | No pude |
|---|---|
| Documentación oficial de Avada: Custom CSS, Page Options, Live Local Options, elementos Tabs, Toggles y Audio; notas de la versión 7.16; comunicado "A New Chapter for Avada" | Abrir vuestro Local ni DevTools: **todo lo que depende del HTML real de vuestra versión de Avada queda como 🔲** |
| Vuestros archivos: `avada-global-options.json` (export del 14 agosto), `claves_conocidas.json`, saneador, CATALOGO, CUADERNO, GUIA, README, y el Custom CSS que pasaste hoy | Leer el grupo de Facebook "Avada Users" (la propia Avada lo cita como lugar donde se discute CSS) ni el foro de soporte |
| Blogs, hilos y CSS públicos de usuarios de Avada (2017–2026) y un par de hilos sobre LiteSpeed | Saber qué **versión exacta** de Avada tiene el Local ni la producción |
| MDN (referencia de CSS) para `!important` | Ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` |

Dos avisos oficiales que conviene tener presentes: (1) el CSS propio queda **fuera del alcance del soporte de Avada** ✅; (2) la documentación oficial se ha mudado a `classic.avada.com` (Avada Classic) ✅ — las URLs `avada.com/documentation/...` redirigen allí.

---

## 2. Cómo funciona el CSS en Avada (verificado)

### 2.1 Dónde puede vivir un estilo — de lo más nativo a lo menos

| # | Capa | Alcance | Estado |
|---|---|---|---|
| 1 | **Opciones del propio elemento** (pestañas General, Diseño, Extras…) | Una instancia | ✅ |
| 2 | **Global Options → Avada Builder Elements → [elemento]** | Todas las instancias de ese elemento que estén en "Default" | ✅ (lo dice la doc de Tabs y de Toggles) |
| 3 | **Page Options** (Layout, Header, Content, Footer…) | Una página; **sobrescribe** a Global Options | ✅ |
| 4 | **Global Options → Custom CSS** | Todo el sitio; "sobrescribe el CSS del tema" | ✅ |
| 5 | **`style.css` de un child theme** | Todo el sitio; la doc lo da por equivalente al 4 | ✅ doc · ⚠️ orden de carga discutido por usuarios (2.6). *No consta child theme en los documentos que tengo.* |
| 6 | **Page Options → Custom CSS** (Avada Live) o icono `</>` de la barra del constructor de backend | Solo esa página | ✅ |
| 7 | **Code Fields** (Espacio antes de `</head>`, después de `<body>`, antes de `</body>`) — en Global Options y en Page Options | Sitio o página; código libre | ✅ |

Regla práctica: se sube por esta escalera solo si el peldaño anterior no lo resuelve. Por eso el paso 1 de la lógica de trabajo (Sección 4.3) es siempre "¿lo hace ya Avada?".

### 2.2 Los ganchos: "CSS Class" y "CSS ID"

✅ Según la documentación oficial, **todos los elementos del constructor, incluidas Columna y Container, tienen los campos "CSS Class" y "CSS ID"**, y se puede usar uno de los dos (no hace falta ambos). En Tabs y Toggles el gancho se pega al **elemento envoltorio**:

| Elemento | Dónde va el gancho | Hijos |
|---|---|---|
| **Tabs** | Envoltorio (el padre) | Los hijos solo tienen título, icono, color de icono y contenido: **sin campo de clase ni ID** ✅ |
| **Toggles** | Envoltorio (el padre) | **Cada toggle hijo tiene además su propia clase e ID** ✅ |

Consecuencia directa para vuestro CSS de Tabs: como una pestaña hija no puede llevar gancho propio, para diferenciar pestañas hay que usar su **posición** (`nth-of-type`), que es justo lo que hicisteis. Es la forma correcta; solo hay que vigilar que la posición no cambie si Avada añade elementos hermanos (Sección 3, fila 7).

### 2.3 El Custom CSS global — lo que dice Avada, en cristiano

✅ Lo que ponéis ahí "sobrescribe el CSS por defecto del tema". La propia Avada admite que **"a veces hace falta `!important`"** (es decir: no lo prohíbe, pero tampoco lo presenta como norma). Otros avisos oficiales de esa misma página:
- **No codifiquéis a mano las rutas de imágenes o SVG**: el contenido de ese campo "se codifica automáticamente". ⚠️ Vuestro botón de play (Bloque 3) lleva el SVG ya codificado (`%3Csvg…`, `%23BF4646`). Funciona hoy, pero es el primer sospechoso si el triángulo sale de otro color o desaparece (Sección 5.3).
- No incluir HTML en el campo.
- El soporte de Avada **no atiende** problemas de CSS propio.

### 2.4 Page Options y su CSS

✅ Pestañas oficiales de Page Options (act. 23 junio 2026): Settings, Layout, Header, Sliders, Page Title Bar, Content, Sidebars, Footer, SEO, **Code Fields**, **Custom CSS**, Import/Export. La pestaña **Custom CSS** añade CSS "para la página o entrada concreta". En Avada Live está en el panel lateral de Page Options; en el constructor de backend, en el icono `</>` de la barra. Y la regla general: **lo que se ponga en Page Options sobrescribe lo global en esa página**.

**Hallazgo sobre los documentos del proyecto:** `GUIA_AVADA_LOCAL.md` §10 dice que Page Options permite "definir una clase CSS personalizada para esta página concreta", y las preguntas abiertas #8 (GUIA §19) y del README preguntan por "el campo de clase CSS a nivel de página entera". **La documentación oficial no lista ningún campo así.** Lo que sí lista es la pestaña *Custom CSS* (código) y *Code Fields*. 🔲 Confirmar abriendo Page Options en Local; si es así, esa pregunta abierta se cierra sola y hay que corregir la redacción de GUIA §10.

**Lo que hay que saber antes de usar Page Options para CSS:**

| Punto | Detalle |
|---|---|
| Alcance | Solo esa página/entrada. Si copiáis la página, conviene comprobar que el CSS viaja con ella 🔲 |
| Versionado | El export saneado (`avada-global-options.json`) es de **Global Options**: el CSS de página **no puede aparecer ahí** (vive con la página) ⚠️. Sin registro, ese CSS queda invisible para el repo. |
| Páginas de plantilla | Si la pieza vive en un Layout + Elementos Guardados (como Hombres de Dios), el CSS de página tendría que repetirse en cada entrada: **no escala** → usar nivel V |
| Cuándo sí | Ajustes o **datos** de una página concreta (Sección 4.2, nivel P) |

### 2.5 La recomendación oficial sobre ganchos

✅ La documentación oficial resume así la elección: **ID cuando el cambio es exclusivo de un elemento; clase cuando quieres cambiar varios.** Y añade que el CSS debería ponerse "principalmente" en el Custom CSS de Global Options. Esto es lo que apoya el patrón de Carlitos… y lo que hace chocar la idea de "un mismo código para todos los elementos con este ID".

### 2.6 Orden de carga — aquí la información se contradice

| Fuente | Qué dice | Estado |
|---|---|---|
| Doc oficial | El CSS global y el del child theme tienen prioridad sobre el CSS del tema | ✅ |
| Usuarios (2017–2023) | El `style.css` del child sale muy arriba y puede quedar **por debajo** del CSS dinámico de Avada; según versión hubo que cambiar el "de qué depende" al encolarlo | ⚠️ |
| Usuarios (2019) | El CSS dinámico de Avada se sirve como archivo compilado o, si se desactiva el compilador, **en línea** dentro de `<head>` | ⚠️ · vuestro export: `css_cache_method: file` ✅ |
| Usuarios (2021–2023) | En el **editor Live** no existía el mismo CSS dinámico, y por eso lo encolado a mano no se veía al editar (solución publicada en 2023) | ⚠️ |

**No hay respuesta cerrada.** Se resuelve mirando el `<head>` de vuestro Local (Sección 3.2, paso 6). Y se resuelve, sobre todo, **no dejando que el orden decida**: si vuestras reglas llevan un gancho fuerte, ganan aunque carguen antes.

### 2.7 Avada 7.16: el terreno acaba de cambiar (4 agosto 2026)

- ✅ Avada 7.16 **reconstruyó Tabs, Testimonials y Pricing Table** y **sustituyó los últimos scripts de Bootstrap**. Después salió la 7.16.1 (arreglo de seguridad).
- ✅ Tabs ahora trae de serie: auto-rotación con indicador, transiciones (fundido, deslizamiento, zoom), descripción por pestaña, títulos con degradado, **color de fondo del contenido** y **radio de borde**, ancho mínimo, alineación del título y enlace directo a pestaña.
- ⚠️ La tabla de opciones de la documentación de Tabs (act. 13 agosto) todavía lista menos opciones que la página del propio elemento (18 agosto): la doc va con retraso respecto al producto.
- ✅ **La imagen de fondo por pestaña NO aparece** entre las novedades ni entre las opciones del hijo (solo título, icono, color de icono y contenido). Vuestra afirmación ("imposible para Avada") **se sostiene**.
- ✅ Contexto: Avada 8, tal como se anunció, **no se lanzará**; el trabajo se reorienta a "Avada One", y Avada Classic sigue como está. Un roadmap de enero de 2025 hablaba de "estilos globales personalizados" (variantes de un elemento que heredan del estilo global) — justo lo que vosotros resolvéis con CSS + gancho —, pero pertenecía a ese desarrollo: 🔲 **no lo deis por disponible en Classic**.

**Por qué importa:** vuestro CSS de Tabs depende del marcado interno de un elemento que Avada acaba de rehacer. Además, algunas cosas que hacíais con CSS (fondo/radio del contenido) ya son nativas en 7.16: si el Local está en 7.16, esas reglas podrían sobrar. 🔲 Confirmar versión instalada (Avada → System Status).

---
## 3. Por qué a veces funciona perfecto y a veces choca

**Idea base, en cristiano.** Cada regla CSS compite con las demás por decidir cómo se ve algo. Gana la más *específica* (un ID pesa más que una clase, y una clase más que una etiqueta); si empatan, gana la que se carga después. `!important` es "gritar": suele ganar, pero si dos gritan vuelve a mandar la fuerza del selector. Casi todos los "choques" son una de estas trece situaciones.

### 3.1 Las causas, de la más a la menos probable en vuestro caso

| # | Causa | Qué pasa (en cristiano) | Cómo se comprueba | Arreglo limpio |
|---|---|---|---|---|
| 1 | **Fuerza del selector** ✅ | `#tt-conecta-tabs .tab-pane` (1 ID + 1 clase) le gana a `.fusion-tabs .tab-pane` (2 clases) sin gritar. Choca cuando una regla vuestra **no lleva el gancho** (p. ej. una regla suelta) o cuando Avada usa un selector más fuerte o `!important`. La propia Avada admite que "a veces hace falta `!important`" | DevTools → pestaña *Styles*: si vuestra regla sale **tachada**, mira cuál la vence y por qué | Empezar siempre por el gancho; subir fuerza con el gancho, no con `!important` |
| 2 | **Estilos dentro del propio elemento (`style="…"`)** 🔲 | Si Avada escribe el valor en el atributo `style` del elemento, ningún selector normal lo vence; solo `!important`. Es el único caso donde gritar está justificado… y aun así conviene mirar si se puede actuar sobre el hijo que consume ese valor | DevTools → *Elements*: mira el atributo `style` del elemento y de sus padres (¿lleva variables `--awb-…`?) | Escalera del `!important` (Sección 4.6) |
| 3 | **Orden de carga** ⚠️ | Doc oficial y usuarios se contradicen (Sección 2.6). Con empate de fuerza, el orden decide y puede cambiar entre Local, Live y web pública | `<head>` de la web pública: anota el orden de los `<link>`/`<style>` de Avada, del CSS global y del de página; repite en Live | Que el orden no decida: gancho fuerte |
| 4 | **Live editor ≠ web pública** ⚠️ | Hilos de 2021–2023 cuentan que el CSS dinámico no era el mismo en el editor Live. Además el Live envuelve los elementos en capas extra, lo que rompe selectores como `>` o `nth-of-type` | Revisar cada mejora en **tres sitios**: Live, web pública con sesión, web pública en incógnito | No dar por buena una mejora que solo se ha visto en el Live |
| 5 | **Cachés** ✅/⚠️ | Avada compila su CSS en archivo (export: `css_cache_method: file`); además hay caché de navegador y, en producción, de LiteSpeed. Un cambio de CSS puede no verse hasta regenerar | Tras guardar: *Reset Avada Caches* (GUIA §4.4), vaciar caché del navegador, probar en incógnito | Anotar en la ficha "probado con caché limpia" |
| 6 | **LiteSpeed en producción** ⚠️ | LiteSpeed Cache ya está en uso en Tiritaito (CATALOGO §4). Sus opciones de combinar/minificar y de "CSS único por página" (UCSS) pueden **quitar CSS de elementos que empiezan ocultos o que se crean con JavaScript**: hay casos publicados de menús móviles que se rompen con UCSS (enero 2026). Las pestañas inactivas (ocultas) y el `iframe` del vídeo (se crea al pulsar) encajan en ese patrón. *Es una hipótesis para Tiritaito: no sé qué opciones tiene activas.* | Probar en staging/producción **con LiteSpeed como en producción**, no solo en Local; desactivar un momento combinar/UCSS y ver si el fallo desaparece | Si se confirma: añadir esas reglas a las excepciones de UCSS |
| 7 | **El marcado interno de Avada cambia con la versión** ✅ | 7.16 reconstruyó Tabs y quitó los últimos scripts de Bootstrap. Vuestras reglas de Tabs dependen de `.tab-pane`, `.tab-content > .tab-pane:nth-of-type(n)` y `.awb-tab-pane-inner`. `nth-of-type` se descuadra si Avada añade un `div` hermano (barra de progreso de auto-rotación, descripción…) 🔲 | Anotar la versión de Avada en cada ficha; repasar el registro tras cada actualización | Regresión obligatoria (Sección 4.7) |
| 8 | **Tabs en modo móvil** 🔲 | En vuestro export: `tabs_mobile_mode: accordion` y `tabs_mobile_breakpoint: medium`. Por debajo de ese punto (1024px) las pestañas pasan a acordeón: es otro marcado. El bloque de Tabs no tiene ninguna regla móvil | Probar a 1024, 768 y 480 px, en vertical y horizontal, y en un móvil real | Reglas móviles explícitas, o apagar el efecto en móvil |
| 9 | **Valores inválidos, incompletos o "de relleno"** ✅ | Placeholders `URL_LENGUAS`, `URL_MISA`, `URL_TIP`, `URL_CITA`; URLs del Local; Poppins sin carga visible; `var(--tt-txt2)` sin respaldo (si esa variable no existe en la página, la propiedad se ignora **sin dar error**). Y valores corruptos en Global Options (Sección 8.1) | Ver Sección 5 | Reglas R5, R2 y R8 (Sección 4.5) |
| 10 | **Reglas sin gancho** ⚠️ | Un selector como `.fusion-tabs .nav-tabs li {…}` afecta a **todas** las pestañas del sitio. Es el clásico de los foros: "lo arreglé en móvil y se rompió en escritorio" | Buscar en el CSS reglas que no empiezan por vuestro ID/clase | Regla R1 |
| 11 | **ID repetido en una página** ✅ | Un ID debe ser único en cada página (HTML). Avada lo dice así. Si reutilizáis `tt-toggle-intro` en dos toggles de la misma página, el CSS se aplica, pero el HTML es inválido y cualquier enlace o script que use ese ID solo verá el primero | En *Elements*, buscar `id="tt-toggle-intro"` y contar coincidencias | Clase registrada para lo repetible (Sección 4.8) |
| 12 | **El truco del ancho completo `100vw`** ✅ (CSS general) | `100vw` incluye la barra de desplazamiento: en Windows/Linux con barra visible puede aparecer scroll horizontal. Además exige que el padre esté centrado | Probar en Windows con la barra de desplazamiento visible | 🔲 Colocar las Tabs en un Container a ancho completo (mecanismo de GUIA §4.0.3) y quitar el truco |
| 13 | **Dependencias ocultas de ajustes globales** ✅ | El Toggle-intro solo funciona con *Performance → Enable Video Facade = On* (`video_facade: on` en el export): si alguien lo apaga, no existe `lite-youtube` y esas reglas no aplican. El bloque de radios solo aplica a Toggles en Boxed Mode | Anotar la dependencia en la ficha | Ficha de registro (Sección 4.4) |

### 3.2 Método de diagnóstico en 10 minutos (para Álvaro / Proyecto 9)

1. **Reproducir** el choque en la web pública en incógnito (no en el Live).
2. **DevTools (F12)** → selecciona el elemento → pestaña *Styles*: ¿está mi regla? ¿tachada? ¿por cuál?
3. Pestaña *Computed*: filtra por la propiedad y despliega la flecha: te enseña **la regla ganadora**.
4. Mira el atributo **`style`** del elemento y de sus padres.
5. Para fuentes: *Computed* → al final, **"Rendered Fonts"**: dice qué fuente se está usando de verdad.
6. *Elements* → `<head>`: apunta el **orden** de los estilos de Avada, del CSS global y del de página.
7. **"Quitar y mirar"**: desmarca cada `!important` en DevTools; si todo sigue igual, sobraba.
8. Repetir a **1024 / 768 / 480 px** y en un móvil real; y con la **caché limpia**.

**Para que yo pueda diagnosticar mejor**, necesito 1–2 casos reales donde "chocó": ¿qué elemento, en qué página, qué se veía y qué esperabais ver? (Pregunta 2 de la Sección 10.)

---

## 4. Lógica de trabajo propuesta

### 4.1 Idea central

> **El diseño vive en un sitio, los datos en otro, y todo lo que dependa de las tripas de Avada queda anotado.**

Una mejora de este tipo no es "un trozo de CSS": es un **mini-componente** con un nombre, un gancho, unos parámetros y unas dependencias. Si se trata así, los choques dejan de ser sorpresas y pasan a ser una lista de comprobación.

### 4.2 Tres niveles

| Nivel | Cuándo | Dónde vive | Gancho | Ejemplo hoy |
|---|---|---|---|---|
| **G — Global** | **Todas** las instancias de un elemento deben cambiar, sin excepción | Global Options → Custom CSS | Ninguno: se apunta a la clase propia del elemento (p. ej. Toggles en Boxed Mode). Se anota como "global a propósito" | Radio de 10px de los Toggles |
| **V — Variante** | Solo **algunas** instancias (opt-in), en una o varias páginas | Diseño en Global Options → Custom CSS + un gancho en el elemento | **ID** si es una sola por página · **clase registrada** si puede haber dos o más | Conecta (ID) · Toggle-intro (ID) · audio propio (clase) |
| **P — Página** | Algo de **una sola página/entrada** que no va a repetirse | Page Options → Custom CSS | El gancho del elemento, si hace falta | (ninguno todavía) |

Notas: **P solo debería llevar datos o ajustes puntuales.** Si el diseño aparece en una segunda página, se promueve a V. Y aunque el Conecta se use solo en la home, hoy es V; podría ser P si nadie más va a usarlo — decisión de Carlitos, pero en ambos casos **se registra**.

**El patrón que mejor separa "diseño" y "datos"** (esquema ilustrativo con vuestros propios nombres; **no probado en Local**; solo compensa si un mismo componente se usa en varias páginas con datos distintos):

```
/* GLOBAL — el DISEÑO, una sola vez. Cada regla lee una variable CON valor por defecto */
#tt-conecta-tabs .tab-content > .tab-pane:nth-of-type(1) { background-image: var(--tt-tab-1, none); }

/* PÁGINA (Page Options → Custom CSS) — los DATOS de esa página */
#tt-conecta-tabs { --tt-tab-1: url(/wp-content/uploads/2026/09/Libros-1.webp); }
```

Dos ventajas: (1) si la variable no está definida, la pestaña simplemente no lleva imagen (sin errores 404, sin placeholders); (2) el orden de carga deja de importar, porque la regla de diseño no declara la variable, solo la lee. Rutas que empiezan por `/` funcionan igual en Local y en producción.

### 4.3 Árbol de decisión — antes de escribir una línea de CSS

```
NECESITO UN EFECTO QUE AVADA NO DA "DE SERIE"
│
├─ 1. ¿Lo hace ya Avada? (opciones del elemento → Global Options del elemento →
│     Page Options → Container/Columna: Fondo, Diseño, Extras — CATALOGO §5 bis)
│      → SÍ: úsalo y para.
│
├─ 2. ¿Necesita HTML propio, lógica o datos (no solo aspecto)?
│      → SÍ: no es solo CSS: Code Snippet / Code Block (GUIA §8 y §12).
│
├─ 3. ¿Debe cambiar TODAS las instancias de ese elemento en todo el sitio?
│      → SÍ: NIVEL G. Global Options → Custom CSS, sin gancho. Se registra.
│
├─ 4. ¿Es de UNA sola página o entrada y no se va a repetir?
│      → SÍ: NIVEL P. Page Options → Custom CSS. Solo ajustes o datos de esa página.
│         (si aparece en una 2ª página: se promueve a V)
│
└─ 5. Ninguna de las anteriores: NIVEL V (variante opt-in).
       Diseño en Global Options → Custom CSS + un gancho en el elemento:
         ├─ ¿Puede haber 2 o más en la misma página?  → gancho = CLASE registrada
         └─ ¿Siempre una sola por página?             → gancho = ID único
       Se rellena la ficha de registro (Sección 4.4).
```

### 4.4 Ficha de registro — una por mejora

Es lo que evita que el CSS se vuelva "materia oscura" (sobre todo el de nivel P, que no sale en el export). Propuesta de campos:

| Campo | Qué se anota |
|---|---|
| Nombre y nivel | `TT Toggle intro` · V |
| Elemento de Avada y **versión probada** | Toggles · Avada 7.x.x |
| Gancho | Tipo (ID/clase), nombre y **campo exacto** donde se escribe |
| Esqueleto HTML obligatorio | Si el CSS depende de HTML escrito **dentro del contenido** del elemento (no son campos de Avada) |
| Parámetros | Variables con su valor por defecto |
| Dependencias | Clases internas de Avada, ajustes globales (p. ej. Video Facade), componentes de terceros |
| Dónde se usa | Páginas |
| Pruebas hechas | 3 pantallas · Live/pública/incógnito · caché limpia · producción con LiteSpeed |
| Responsable y fecha | |

Borrador rellenado para vuestros tres bloques, en la Sección 5.4.

### 4.5 Diez reglas de escritura ("integrado, no parche")

| # | Regla | Por qué (en cristiano) |
|---|---|---|
| R1 | **Todo con gancho.** Cada regla empieza por el ID/clase de la mejora. Solo las de nivel G apuntan al elemento sin gancho, y se anotan como "global a propósito" | Una regla suelta afecta a todo el sitio (causa 10) |
| R2 | **Colores y fuentes de la paleta, no a mano.** Colores: `var(--awb-colorN)` (Avada los genera y la propia doc los usa en sus ejemplos) ✅. Si usáis `--tt-*`, con respaldo: `var(--tt-txt2, var(--awb-color7))` | Si cambia la paleta, todo cambia a la vez; y si una variable no existe, no se rompe en silencio |
| R3 | **Parámetros = variables con valor por defecto en el sitio de uso:** `var(--tt-x, valor)` | Permite que una página cambie el dato sin tocar el diseño (Sección 4.2) |
| R4 | **Un valor, un sitio.** Radios, tamaños y colores repetidos → una variable (p. ej. el 10px) | Cambiarlo una vez, no siete |
| R5 | **Cero placeholders y cero URLs absolutas del Local.** Rutas que empiezan por `/wp-content/…` | Los placeholders generan peticiones 404; `https://tiritaito-real.local/…` se rompe al pasar a producción salvo que la migración lo reescriba |
| R6 | **Depender lo mínimo de las tripas de Avada.** Preferir el gancho y clases documentadas; lo inevitable (`.tab-pane`, `lite-youtube`, `.fusion-panel`) va a la ficha como "dependencia" | Son lo que se rompe con una actualización (causa 7) |
| R7 | **Responsive explícito** con los puntos del proyecto 1024/768/480 (`00_CORE.md` §7), probando además el modo móvil propio del elemento (Tabs → acordeón) | Causa 8 |
| R8 | **Formato limpio:** `px`, decimales con punto, nada traducido; navegador **sin traducción automática** en el panel de Avada | Sección 8.1: hay valores corrompidos por esto |
| R9 | **Cabecera de comentario** en cada bloque: nombre, nivel, gancho, dónde se usa, parámetros, dependencias, versión de Avada probada, fecha | Es la ficha, viva dentro del propio CSS |
| R10 | **Accesibilidad:** contraste ≥ 4,5:1 del texto sobre fondos/imágenes/velos; foco de teclado visible; no ocultar controles sin sustituto | Un velo oscuro con texto oscuro (Bloque 2) es el ejemplo típico |

### 4.6 Escalera del `!important` — antes de añadir uno

1. **¿Gana con un gancho más fuerte?** (ID + clase interna) → entonces no hace falta.
2. **¿El elemento expone una variable de Avada que gobierna eso?** → cambia la variable en vez de pelearte con la propiedad. 🔲 (a comprobar en DevTools qué variables `--awb-…` expone cada elemento).
3. **¿El valor viene de un `style=""` en línea?** → apunta al hijo que lo consume o, si no hay más remedio, `!important` **con comentario** `/* vence a: style en línea de Avada */`.
4. **¿Avada usa `!important` en esa regla?** → igualarlo, con comentario.
5. **Prueba de "quitar y mirar":** si sin él sigue funcionando, se borra.

Dos detalles técnicos de MDN que conviene recordar: `!important` sobre una propiedad abreviada (`background`) marca **todas** sus subpropiedades como importantes ✅, y sobre una variable CSS hace que ese valor sea el importante ✅.

### 4.7 Ciclo de vida de una mejora

| Paso | Qué se hace | Quién |
|---|---|---|
| 1. Proponer | Se decide el nivel, el gancho y los parámetros; se escribe el CSS con las reglas R1–R10 | Carlitos |
| 2. Probar | Live + pública + incógnito · 1024/768/480 · caché limpia · (ideal) producción con LiteSpeed | Álvaro / Proyecto 9 |
| 3. Registrar | Ficha completa (Sección 4.4) y entrada en el CATALOGO | Proyecto 2 |
| 4. Vigilar | Tras **cada actualización de Avada**: 10 minutos por ficha con el método de la Sección 3.2 | Álvaro |
| 5. Retirar | Cuando Avada lo haga nativo (ejemplo: 7.16 ya trae color de fondo y radio del contenido de Tabs) se retira la regla y se anota | Proyecto 2 / Carlitos |

### 4.8 ID o clase — el conflicto con la prohibición del 6 de septiembre (**D1**)

| Opción | En qué consiste | A favor | En contra |
|---|---|---|---|
| **A. Mantener la prohibición: solo ID** | Ganchos = IDs únicos. Para repetir en una misma página: IDs con prefijo (`tt-audio-1`, `tt-audio-2`) y selector de prefijo `[id^="tt-audio-"]` | No se toca la decisión; cada ID es válido | Un selector de prefijo pesa como una clase, **menos que un ID** (habría que comprobar el orden); patrón poco habitual; alguien debe teclear bien un ID distinto por instancia |
| **B. Reabrir la clase, con registro** | La clase se permite **solo** si está en el registro (prefijo `tt-v-…`); Álvaro solo aplica clases registradas; Carlota no inventa ganchos | Es lo que Avada recomienda para "varios"; reutilizable; el registro mantiene el control | Cambia la regla del 6/09 y hay que reescribir las instrucciones de 4 Proyectos |
| **C. Híbrido** | **ID** para piezas únicas por página (Conecta, Toggle-intro); **clase registrada** solo para variantes repetibles (audio, botones…) | Cada caso real vuestro tiene su gancho natural; mínimo cambio con máximo encaje | Dos convenciones que mantener |

**Mi recomendación: C**, con esta gobernanza: el gancho es **el contrato** entre Carlitos (que escribe el CSS) y Álvaro (que solo *aplica* ganchos registrados); nadie inventa ganchos sueltos; un gancho nuevo pasa por Carlitos/Proyecto 9 y por el registro. Así se conserva el espíritu de la prohibición ("no usar el campo como atajo") sin quedaros sin la herramienta.

**Cuándo NO recomendaría C:** si el motivo original de la prohibición era evitar *cualquier* campo de gancho por elemento (no solo la clase). Entonces la única opción coherente es A. **No tengo el documento que lo explica** (Sección 0 bis), por eso lo dejo en tus manos.

El audio (Sección 7) es el mejor caso de prueba: puede haber varios en una página, y con solo ID sale peor.

### 4.9 Dónde vive el código — base de datos o archivo (**D3**)

| | Global Options → Custom CSS (base de datos) | `style.css` de un child theme (archivo) |
|---|---|---|
| A favor | Se edita desde el panel; no requiere child theme; entra en el export saneado | Se ve con `git diff`; revisable; comentarios y herramientas de editor |
| En contra | En el JSON del repo es **una sola cadena** con saltos de línea: ilegible para revisar cambios; depende de que alguien regenere el export; cualquiera con acceso puede editarlo | Hoy no consta child theme; hay que desplegar archivos; orden de carga discutido por usuarios (Sección 2.6); el Live editor exigió trucos de encolado ⚠️ |
| Doc oficial | Equivalentes: "sin diferencia material" ✅ | |

**Propuesta intermedia (sin decidir todavía):** mantener el Custom CSS en la base de datos y guardar **una copia `.css` en el repo** como fuente de verdad legible (p. ej. que el saneador la exporte aparte). Es tarea de Carlitos, no la he tocado.

### 4.10 Quién hace qué

| Rol | En este patrón |
|---|---|
| Carlitos | Escribe y aprueba el CSS (es un módulo de código) y decide los ganchos |
| Álvaro | **Aplica** ganchos registrados en el campo del elemento y prueba en 3 pantallas |
| Carlota | En los bocetos usa solo variantes que estén en el registro, o pide una nueva; no inventa ganchos |
| Proyecto 9 | Diagnóstico con DevTools cuando algo choca |
| Proyecto 2 (yo) | Investiga, mantiene el registro y lo fusiona en el CATALOGO tras tu confirmación |

---
## 5. Revisión de vuestros tres bloques de Custom CSS

Resumen: el Bloque 1 está bien; el Bloque 2 tiene 4 fallos concretos; el Bloque 3 funciona pero tiene mucho `!important` que probablemente sobra. **Total: 25 declaraciones con `!important`** (24 en el Bloque 3, 1 en el Bloque 2, 0 en el Bloque 1). Esto lo he contado sobre el texto pegado; que sobren o no solo se sabe con la prueba de "quitar y mirar" (Sección 3.2, paso 7).

### 5.1 Bloque 1 — Radio de 10px en los Toggles · nivel G

| | Hallazgo |
|---|---|
| ✅ | La documentación oficial de Toggles **no ofrece ninguna opción de radio de borde** (sí Boxed Mode, borde, fondo, padding, tipografías…): el CSS es necesario. Es el caso que el propio CATALOGO §5 bis cita como justificado |
| ✅ | Nivel G bien definido, con cabecera y fecha. Y `accordion_boxed_mode: 1` en el export confirma que los toggles llevan Boxed Mode por defecto, así que la regla aplica a todos |
| ⚠️ | `overflow: hidden` en cada panel puede recortar sombras o el aro de foco del teclado dentro del panel. Probar con la tecla Tab |
| ⚠️ | El 10px está escrito dos veces (panel y contenido). Convertirlo en variable (R4) y darle nombre de token: es la forma de cerrar la pregunta 13.1 del CATALOGO ("¿cuarto token?") |
| ⚠️ | Depende de selectores internos (`.fusion-panel.fusion-toggle-boxed-mode`, `.fusion-toggle-content`). 7.16 sustituyó los últimos scripts de Bootstrap: incluir en la regresión |
| ⚠️ | **El export del repo solo contiene este bloque** (comentado "29 julio 2026"): los otros dos no están en el repo (Sección 8.2) |

### 5.2 Bloque 2 — «Conecta cada día», Tabs con imagen de fondo por pestaña · nivel V (ID `tt-conecta-tabs`, una por página)

**Lo que está bien** ✅: todas las reglas cuelgan del ID; usáis `nth-of-type` en lugar de los IDs generados al azar (correcto, como explica vuestro comentario, y la única vía porque la pestaña hija no admite gancho); imágenes en `.webp`; un solo `!important`, y justificado en el comentario (Avada mete `font-family` en línea en los textos de contenido dinámico → causa 2).

**Lo que hay que corregir:**

| # | Hallazgo | Evidencia | Gravedad | Acción propuesta |
|---|---|---|---|---|
| 1 | **4 URLs de relleno** en el CSS real: `URL_LENGUAS`, `URL_MISA`, `URL_TIP`, `URL_CITA` | Vuestro CSS | Media: el navegador pide archivos que no existen (404) y esas 4 pestañas no llevan foto | Quitar esas 4 reglas hasta tener imagen, o dejarlas con `var(--…, none)` (Sección 4.2) |
| 2 | **URLs absolutas del Local** (`https://tiritaito-real.local/wp-content/uploads/2026/09/…`) en las 3 imágenes reales | Vuestro CSS | Alta al migrar: se rompen en producción salvo que la migración reescriba también el contenido serializado (WP-CLI lo hace; otras herramientas no siempre) | Rutas que empiezan por `/wp-content/…` (R5) |
| 3 | **Poppins no consta cargada** y **la pila de fuentes está mal ordenada** | El export (14 agosto) solo registra como fuentes propias Helvetica Nueva y YEAH-PAPA, y los sets de tipografía usan Fraunces y Public Sans. La regla `.tt-yeahpapa` declara `'Poppins', sans-serif, 'Yeah-Papa', sans-serif`: una familia genérica (`sans-serif`) siempre encuentra fuente, así que lo que va detrás **en la práctica nunca se usa** | Media: en móviles/equipos sin Poppins se verá la sans-serif del sistema; y `.tt-yeahpapa` **no pinta Yeah Papa** aunque se llame así | 🔲 Comprobar con *Rendered Fonts* (Sección 3.2, paso 5). Decidir si Poppins es fuente de marca (no lo es en los documentos) y, si lo es, cargarla en local; y reordenar la pila |
| 4 | **Contraste dudoso**: texto `#3a3a3c` (gris muy oscuro) sobre foto **oscurecida** con un velo negro al 35% | Vuestro CSS. El velo se escribió "para legibilidad", pero oscurece la foto y el texto también es oscuro | Alta si las fotos son de tono medio: bajo el 4,5:1 recomendado (WCAG) | 🔲 Medir con cada foto (DevTools → contraste); o texto claro con velo oscuro, o velo claro con texto oscuro |
| 5 | **Truco `100vw` + `left/right: 50%` + márgenes `-50vw`** para salir del ancho del sitio | Vuestro CSS | Media: posible scroll horizontal con barra visible (Windows/Linux) | 🔲 Tabs dentro de un Container a ancho completo (mecanismo de GUIA §4.0.3) y quitar el truco |
| 6 | **Valores a mano**: `#3a3a3c` (el comentario dice "Color 7") y `rgba(0,0,0,0.35)` | Vuestro CSS | Baja | `var(--awb-color7)` con respaldo; el velo como variable (R2, R4) |
| 7 | **Sin reglas móviles** aunque en móvil las Tabs pasan a acordeón | Export: `tabs_mobile_mode: accordion`, breakpoint medium | Media 🔲 | Probar a 1024/768/480 y decidir (Sección 3, causa 8) |
| 8 | **Dependencia del marcado interno** (`.tab-pane`, `.awb-tab-pane-inner`, `nth-of-type`) justo tras la reconstrucción de Tabs en 7.16 | Nota de versión de Avada | Alta para el futuro | Ficha con versión probada y regresión tras cada actualización |
| 9 | **7 reglas casi iguales** para las imágenes | Vuestro CSS | Baja | 1 regla + 7 variables (Sección 4.2) |
| 10 | **Pestaña 6 "Tip"** en el diseño, aunque el "Tip del día" se eliminó por decisión el 26/07/2026 | README (pendiente "Retirar de la app la UI de Tip del día") | 🔲 Pregunta | Confirmar si la pestaña sigue en el diseño |

### 5.3 Bloque 3 — Toggle de introducción, vídeo + texto 50/50 · nivel V (ID `tt-toggle-intro`)

**Lo que está bien** ✅: gancho por ID; nombres `tt-…` con BEM; el punto de cambio móvil es 1024px, el estándar del proyecto; `aspect-ratio` en lugar de trucos de relleno.

**Lo que hay que revisar:**

| # | Hallazgo | Estado |
|---|---|---|
| 1 | **24 `!important`.** Con selectores de ID (mucho más fuertes que los de Avada) la mayoría **probablemente sobra**. Los candidatos legítimos son propiedades que Avada o el componente de vídeo fijen en línea. Se comprueba de uno en uno con "quitar y mirar" | 🔲 |
| 2 | **Dependencia oculta de un ajuste global:** *Performance → Enable Video Facade* debe estar **On** (`video_facade: on` en el export). Si se apaga, no existe `lite-youtube` y las reglas del botón play dejan de aplicar | ✅ (a la ficha) |
| 3 | **Dependencia oculta de HTML:** las clases `.tt-intro-toggle`, `__video` y `__texto` tienen que existir **dentro del contenido del toggle**. No son campos de Avada: si un editor reescribe el contenido en el editor visual, se pierde el diseño. Está en la zona gris entre "no meter código en el elemento" y "Code Snippet": debe constar en la ficha como **esqueleto obligatorio** y decidirse expresamente | ⚠️ |
| 4 | **SVG del botón ya codificado** (`%3Csvg…`, `%23BF4646`), cuando la doc oficial pide **no** codificar rutas/SVG. Funciona hoy | ⚠️ Primer sospechoso si el triángulo cambia de color. Comprobar el valor *computado* de `background-image` |
| 5 | **El rojo `#BF4646` dentro del SVG no puede leer variables CSS**, así que no sigue a la paleta. Alternativa a probar: dibujar el triángulo con una máscara y dar color con `background-color: var(--awb-color5)` | 🔲 |
| 6 | **`var(--tt-txt2)` sin respaldo.** La convención del proyecto es `var(--tt-*, respaldo)`. Si `--tt-*` no está definida en esa página, el texto hereda un color cualquiera **sin error** | ⚠️ 🔲 Comprobar en *Computed* que `--tt-txt2` existe en `:root` |
| 7 | **Poppins otra vez, y `font-size: 15px` fijo** (no sigue la tipografía adaptable de Avada: `typography_sensitivity` = 0.30) | ⚠️ |
| 8 | **Radio de 15px** en el vídeo, cuando el estándar decidido es 10px y el Bloque 1 ya da 10px a los `iframe` dentro de toggles | ⚠️ Decisión D4 |
| 9 | En los pseudo-elementos `::before/::after` sobra `content: none`; con `display: none` basta | ⚠️ menor |

### 5.4 Borrador de fichas de registro (para que Carlitos corrija)

| | **TT Toggles — radio** | **TT Conecta cada día** | **TT Toggle intro** |
|---|---|---|---|
| Nivel | G | V (o P, decisión tuya) | V |
| Elemento | Toggles | Tabs | Toggles |
| Gancho | ninguno (todos en Boxed Mode) | ID `tt-conecta-tabs` en el Tabs padre | ID `tt-toggle-intro` en el Toggles padre |
| Esqueleto HTML | no | no (usa Contenido Dinámico, clases `.awb-dd`) | **sí**: `.tt-intro-toggle` > `__video` + `__texto` dentro del contenido |
| Parámetros | radio 10px | imagen por pestaña (7), velo 0,35, color de texto | radio 15px (?), tamaño del play 64px |
| Dependencias | `.fusion-panel.fusion-toggle-boxed-mode`, `.fusion-toggle-content`; Boxed Mode | `.tab-content > .tab-pane:nth-of-type(n)`, `.awb-tab-pane-inner`; modo móvil de Tabs; variables `--tt-*` | Video Facade = On; `lite-youtube`, `.lty-playbtn`, `.fusion-video` |
| Versión de Avada probada | 🔲 desconocida | 🔲 desconocida (¿7.15 o 7.16?) | 🔲 desconocida |
| Estado | en uso (29/07) | pendiente de los 4 arreglos (5.2) | funciona; revisar `!important` |

---

## 6. Cómo lo usan otros usuarios de Avada — lo que encontré y sus límites

| Fuente | Qué hacen | Lección |
|---|---|---|
| **Avada (doc oficial)** | Gancho (clase o ID) + CSS "principalmente" en Global Options; ID para algo único, clase para varios; recomienda DevTools; remite al grupo de Facebook "Avada Users" para dudas de CSS | Vuestro patrón es el oficial ✅ |
| **awb4wp.com** (guía de trucos de un desarrollador, 2022) | Para pestañas personalizadas creó **una clase** (`box-tab`) y se la asignó al elemento; avisa de que en Tabs hay "poco control" del diseño y de un fallo de teclado en las pestañas inactivas | Variante por clase = práctica común ⚠️ (el aviso de teclado es de 2022; con la reconstrucción de 7.16 puede haber cambiado) |
| **Blog de Tawfiq** (pestañas en móvil, 2020–2022) | Reglas globales sobre `.fusion-tabs .nav-tabs li` con `!important`; un comentarista avisa de que el arreglo para móvil **se coló en pantallas medianas y grandes** | El clásico de la regla sin gancho ⚠️ (causa 10) |
| **Un child theme público de un sitio institucional** | `.nav-tabs .tab-link .fusion-tab-heading { color: white !important; }` para mantener el texto de las pestañas blanco | `!important` por sistema en selectores de Avada ⚠️ |
| **Blog de Megabite** (orden de carga, 2017–2023) | El CSS del child sale "muy arriba"; solución con cambios en `functions.php`; en 2017 el foro de Avada recomendaba **anteponer un ID** (`#wrapper`) para ganar; en el Live editor hubo que encolarlo dos veces | Origen de la costumbre de ganar por especificidad ⚠️ |
| **Hilo de WoodMart (enero 2026)** | Un menú móvil oculto se rompe al activar UCSS de LiteSpeed | Mismo mecanismo que la causa 6 ⚠️ |
| **Gist de un usuario** | Reordenar columnas en móvil con un ID propio (`#reverse-cols1`) + `!important` | Uso de ID como gancho único ⚠️ |

**Límites honestos:** lo más rico (el grupo de Facebook y el foro de soporte) no lo pude leer; lo que encontré es sobre todo de 2017–2023; y **Tabs se reconstruyó en 7.16**, así que el consejo de Tabs anterior a agosto de 2026 puede no aplicar. Conclusión útil pese a todo: **la práctica común es "variante por clase" y "!important por costumbre"**; lo que proponemos (registro + escalera del `!important` + variables) es más disciplinado que lo habitual.

---

## 7. El ejemplo del audio (reproductor nativo pobre → estilo propio)

1. **Paso 1 de la lógica: ¿qué hace ya el elemento?** ✅ El elemento Audio de Avada ya trae: velocidad de reproducción, bucle, autoplay, precarga, color de fondo, esquema de controles (claro/oscuro), color de la barra de progreso, ancho máximo, alineación, borde, **radio**, y **sombra** con posición, difuminado y color. Antes de escribir CSS hay que definir con precisión **qué le falta** (¿forma de los botones? ¿tipografía de los tiempos? ¿volumen? ¿maquetación?). *Pregunta 8 de la Sección 10.*
2. **Nivel:** V. Y **con clase**, no con ID: puede haber varios audios en una página (una lista de audios). Es el caso donde la Opción A (solo ID) sale peor y por eso lo uso de prueba para D1.
3. **Qué hay que comprobar antes de nada** 🔲: qué marcado usa el Audio en vuestro Local. Si usa MediaElement.js (el reproductor que WordPress incluye), sus clases (`.mejs-container`, `.mejs-controls`, `.mejs-time-rail`…) son una API de terceros más estable que las clases internas de Avada. La documentación oficial de Avada solo dice que usa una etiqueta HTML5 de audio: **no verifiqué que use MediaElement**.
4. **Ojo con la accesibilidad (R10):** los controles deben seguir siendo operables con teclado y con foco visible.
5. **No confundir** con el reproductor de podcast del sitio (`[tt_podcast]`, prefijo `.pp-*`), que es un snippet aparte y tiene otro tratamiento (CATALOGO §5, Audio).

---

## 8. Hallazgos adicionales (no pedidos, pero explican choques o afectan a los documentos)

### 8.1 Valores corrompidos en Global Options — probablemente por la traducción automática del navegador ⚠️

Al leer el export vi un patrón **sistemático**, no aleatorio:

| Campo del export | Valor guardado | Debería ser | Efecto probable |
|---|---|---|---|
| `nav_typography` (tamaño, espaciado), `mobile_menu_typography` (tamaño, interlineado, espaciado), `button_typography` (tamaño, interlineado, espaciado), `footer_headings_typography` (tamaño, interlineado, espaciado), `faq_accordion_content_typography.font-size`, `body_typography` (interlineado, espaciado), `h1…h6_typography.line-height`, `post_title_typography` | Variables **en español**, p. ej. `var(--awb-tipografía3-tamaño-de-fuente)` | `var(--awb-typography3-font-size)` | Variable inexistente → esa propiedad **no se aplica** y hereda; afecta a menú, botones y titulares del pie |
| `custom_fonts.name` | `Helvetica Nueva` | `Helvetica Neue` | La fuente propia se registra con otro nombre que el que pide `body_typography` (`Helvetica Neue`); ya lo anotó el CATALOGO el 16/09 como "tres grafías" |
| `h1…h6_typography.margin-top/bottom` | `0,6em`, `0,55em`, `0,5em` | `0.6em`… | Decimal con coma: **inválido en CSS** |
| `privacy_necessary_opacity`, `privacy_bar_desc_font_size`, `before_after_transition_time` | `0,7` · `0,85 em` · `0,5` | `0.7` · `0.85em` · `0.5` | Ídem |
| `header_border_color`, `page_title_border_color`, `tagline_border_color` | `Rgba(226.226.226,0)` | `rgba(226,226,226,0)` | Color inválido |
| `success_bg_color` | `Rgba(18,184,120,0,1)` | `rgba(18,184,120,0.1)` | Color inválido |
| `header_sticky_nav_font_size`, `page_title_font_size`, etc. | `14 píxeles` | `14px` | Ya recogido en GUIA §19, punto 10 (barrido) |

**Causa probable (hipótesis fuerte, sin confirmar):** el panel de Avada se está editando con la **traducción automática del navegador** activada. Una sola causa explica todo: `px`→"píxeles", `Neue`→"Nueva", `font-size`→"tamaño-de-fuente" y los decimales con coma. **Arreglo:** desactivar la traducción automática para el dominio del panel de Avada y reintroducir los valores **en Avada** (el JSON del repo es solo una copia). El barrido del punto 10 de GUIA §19 debería ampliarse de "píxeles" a todo este patrón.

**Por qué importa para vuestra pregunta:** es otra fuente de "a veces funciona y a veces no", esta vez en Global Options y no en el CSS. Tampoco conviene descartar que el propio campo Custom CSS se vea afectado: el CSS que pasaste hoy está **intacto**, buena señal.

### 8.2 El export del repo está atrasado respecto al Custom CSS ✅

El campo `custom_css` del export (14 agosto) solo contiene el bloque de los Toggles. **Los bloques de Tabs y Toggle-intro no están en el repo.** README dice que el export se regenera cada vez que alguien cambia y guarda un ajuste de Global Options. Regenerar export y `claves_conocidas.json`.

### 8.3 Dos contradicciones entre el CATALOGO y el export ⚠️

| CATALOGO §9 (✅) | Export (14 agosto) | Verificar |
|---|---|---|
| "Google & Font Awesome Fonts Mode cambiado de CDN a **Local**" | `gfonts_load_method: "cdn"` | Cuál es la verdad hoy |
| "Privacy Consent Tools activado con antelación" | `privacy_embeds: "0"` (apagado) | Ídem |

Puede que el export sea anterior a esos cambios o que el Local se haya restaurado desde una copia más antigua. Solo Carlitos/Álvaro pueden confirmarlo abriendo el panel.

### 8.4 Testimonials también se reconstruyó en 7.16 🔲

CATALOGO §5 documenta valores globales de Testimonials (fondo, velocidad 4000ms, orden aleatorio). Con el elemento rehecho (4 layouts, diseño de tarjeta), esos campos pueden haber cambiado de sitio o desaparecido. Revisar esa entrada si el Local está en 7.16.

### 8.5 Sobre la versión de Avada

GUIA §18 dice "la línea 7.15/7.16". Toda esta investigación depende de saber cuál es, y de si se va a actualizar a 7.16.x. Decisión **D5**.

---

## 9. Documentos que habría que actualizar — solo propuesta, nada aplicado

| Documento | Sección | Cambio propuesto | Depende de |
|---|---|---|---|
| `CATALOGO_ELEMENTOS_AVADA.md` | §5 bis | Añadir "Mejoras vía Custom CSS + gancho": el registro de fichas (Sección 4.4/5.4). Actualizar Toggles (radio: no nativo ✅) y Tabs (7.16: fondo del contenido, radio, títulos con degradado ya nativos; **imagen de fondo por pestaña sigue sin serlo** ✅) | Confirmación tuya + versión instalada |
| `CATALOGO_ELEMENTOS_AVADA.md` | §5 (Testimonials), §9 (fuentes/privacidad), §14 (#11) | Revisar Testimonials tras 7.16; resolver las dos contradicciones del 8.3; contestar la pregunta #11 | Comprobación en Local |
| `GUIA_AVADA_LOCAL.md` | §8, §12, §13, §19 (#7 y #8) | Sustituir el tope de "30 líneas" y "nunca en global" por la gobernanza del registro (si D2 = sí); reflejar la decisión de ID/clase (D1); cerrar las preguntas #7 y #8 | D1, D2 |
| `GUIA_AVADA_LOCAL.md` | §10 | Corregir "clase CSS personalizada para esta página": la doc oficial lista una pestaña *Custom CSS*, no un campo de clase | 🔲 Comprobar en Local |
| `GUIA_AVADA_LOCAL.md` | §19 punto 10 | Ampliar el barrido de "píxeles" al patrón completo de 8.1 | — |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | Proyectos 3, 4, 6, 7 (y 9) | Texto nuevo de la prohibición / del gancho permitido y del registro. **No lo he redactado**: lo hago solo tras tu confirmación de D1 | D1 |
| `CUADERNO_DEL_CONSTRUCTOR.md` | §3 | Entrada "✅ Tabs con imagen de fondo por pestaña vía ID + `nth-of-type`" cuando Álvaro/Proyecto 9 la confirmen en Local | Prueba en Local |
| `README.md` | Estado global | Nueva fila: "Decisión ID/clase + registro de mejoras CSS" | D1 |
| `exports/` | — | Regenerar `avada-global-options.json` y `claves_conocidas.json` con el Custom CSS actual | — |

---

## 10. Próximos pasos y preguntas abiertas

### Próximos pasos (por orden)

| # | Paso | Quién |
|---|---|---|
| 1 | **Decidir D1** (A/B/C). Bloquea el audio y cualquier variante repetible | Carlitos |
| 2 | **Sesión de 15–20 minutos con DevTools en Local** con el método de la Sección 3.2: confirmar versión de Avada, orden del `<head>`, atributos `style`, *Rendered Fonts*, `--tt-txt2`, contraste de las fotos, comportamiento a 1024/768/480 | Álvaro / Proyecto 9 |
| 3 | Corregir el Bloque 2 (los 4 fallos de la Sección 5.2) o marcarlo "pendiente" hasta tener las imágenes | Carlitos / Álvaro |
| 4 | Prueba de "quitar y mirar" en los 24 `!important` del Bloque 3 | Álvaro |
| 5 | Desactivar la traducción automática del navegador en el panel de Avada y barrer los valores de la Sección 8.1 | Carlitos / Álvaro |
| 6 | Regenerar el export saneado con el Custom CSS actual | Carlitos |
| 7 | Probar en **staging/producción con LiteSpeed** al menos el Bloque 2 y el Bloque 3 | Carlitos |
| 8 | Tras tu confirmación: redactar el texto para Proyectos 3/4/6/7 y fusionar el registro en CATALOGO §5 bis | Proyecto 2 |

### Preguntas abiertas

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | **D1 — ¿ID solo (A), clase con registro (B) o híbrido (C)?** | Decide cómo se reutiliza una mejora y qué se escribe en las instrucciones de 4 Proyectos |
| 2 | ¿En qué 1–2 casos concretos "chocó"? (elemento, página, qué se veía) | Permite diagnosticar con precisión en vez de por lista de candidatos |
| 3 | **D5 — ¿Qué versión de Avada tiene el Local y la producción?** ¿Se actualiza a 7.16.x? | Tabs cambió: parte de vuestro CSS podría sobrar o romperse |
| 4 | ¿Dónde se carga Poppins y por qué no es Yeah Papa / Helvetica Neue? | El export no la registra; hoy solo se vería con Poppins instalada |
| 5 | ¿La pestaña "Tip" sigue en el diseño del Conecta? | Se eliminó el "Tip del día" el 26/07 |
| 6 | ¿Qué optimizaciones de CSS tiene LiteSpeed activas en producción (combinar, UCSS)? | Causa 6: funciona en Local, puede romperse en producción |
| 7 | **D2 — ¿Sustituimos el tope de 30 líneas y la regla "nunca en global" por la gobernanza del registro?** | El límite actual ya está superado ~5 veces |
| 8 | ¿Qué le falta exactamente al Audio nativo? | Sin eso no se puede decidir si hace falta CSS |
| 9 | **D4 — ¿10px pasa a token oficial (nombre?) y el vídeo de 15px es intencional?** | Cierra la pregunta 13.1 del CATALOGO |
| 10 | **D3 — ¿Custom CSS en la base de datos + copia en el repo, o child theme?** | Versionado y revisión del CSS |
| 11 | ¿Aceptas que el registro de mejoras sea una sección del CATALOGO (no un documento aparte)? | Regla de que los estudios se fusionan en el CATALOGO |

---

## Glosario en una frase

| Término | Qué es |
|---|---|
| **Gancho** | Un ID o una clase que se escribe en un campo del elemento para que el CSS sepa a cuál dirigirse |
| **Selector** | La parte de una regla CSS que dice a qué elementos se aplica |
| **Especificidad** | La "fuerza" de un selector: un ID gana a una clase y una clase a una etiqueta; si empatan, gana el que carga después |
| **`!important`** | Marca que hace ganar a una regla casi siempre; útil como último recurso, dañino como costumbre |
| **Variable CSS** | Un valor con nombre (`--awb-color5`) que se define una vez y se reutiliza; si no existe, la propiedad que la usa se ignora sin avisar |
| **Estilo en línea** | Un estilo escrito dentro del propio elemento (`style="…"`); vence a casi cualquier selector |
| **Marcado / DOM** | La estructura de etiquetas HTML de la página; el CSS "cuelga" de ella y se rompe si cambia |
| **Pseudo-elemento** | Un elemento decorativo que crea el CSS (`::before`, `::after`) sin que exista en el HTML |
| **Video Facade** | Ajuste de Avada que carga solo una miniatura de YouTube hasta que se pulsa play |
| **UCSS** | Función de LiteSpeed que deja en cada página solo el CSS que ve en ella; puede quitar el de cosas ocultas |
| **Regresión** | Que algo que funcionaba deje de funcionar tras un cambio (p. ej. una actualización de Avada) |
| **Token de diseño** | Un valor de marca con nombre oficial (colores, radios) que se usa siempre en vez de números sueltos |
| **DevTools** | Herramientas del navegador (tecla F12) para inspeccionar qué reglas se aplican a un elemento |

---

## Fuentes

**Documentación oficial de Avada** (las URLs `avada.com/documentation/...` redirigen a `classic.avada.com`):
- `avada.com/documentation/how-to-add-custom-css-in-avada/` (act. 31 oct 2025)
- `avada.com/documentation/avada-page-options/` (act. 23 jun 2026)
- `avada.com/documentation/avada-live-local-options-management` (act. 30 mar 2026)
- `avada.com/documentation/tabs-element/` (act. 13 ago 2026) y `avada.com/element/tabs/` (18 ago 2026)
- `avada.com/documentation/toggles-element/` (act. 30 ago 2025)
- `avada.com/documentation/audio-element/` y `avada.com/element/audio`
- `avada.com/blog/avada-7-16-has-been-released/` (5 ago 2026) · `avada.com/whats-new/`
- `avada.com/blog/a-new-chapter-for-avada/` (28 may 2026)
- `avada.com/blog/avada-roadmap-december-progress-update/` (6 ene 2025)

**Referencia de CSS:** `developer.mozilla.org/docs/Web/CSS/important`

**Comunidad (2017–2026; ⚠️ ver límites en la Sección 6):**
- `megabite.com/a-fix-for-the-innefective-avada-child-theme-style-sheet/`
- `awb4wp.com/elements/tabs-2/`
- `blog.tawfiq.me/avada-change-fusion-tabs-heading-position-in-mobile-layout/`
- `gist.github.com/marklchaves/aadd94aa13bd7e753031b4d54e948669`
- `toolset.com/forums/topic/css-loading-issue-with-views-active`
- `xtemos.com/?p=703705` (WoodMart + UCSS de LiteSpeed, ene 2026)

**Archivos del proyecto:** `avada-global-options.json` (export del 14 agosto), `claves_conocidas.json`, `saneador-avada-options.html`, `CATALOGO_ELEMENTOS_AVADA.md`, `CUADERNO_DEL_CONSTRUCTOR.md`, `GUIA_AVADA_LOCAL.md`, `README.md` y el Custom CSS de Global Options pegado el 23 sep 2026.

---

*Para la mayor gloria de Dios · tiritaito.com*

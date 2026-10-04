# TIRITAITO.COM — Hombres de Dios: estructura y registro de secciones
**Cómo está montada la ficha de cada santo en Avada — un cajón, una plantilla, unas casillas — y qué sección es cada cosa, con su nombre exacto**
*Primera versión: 3 de octubre de 2026, con las decisiones del mismo día incorporadas · Construido a partir de la prueba en Local del 29-30 de septiembre y de los traspasos y fichas de Álvaro (Proyecto 3) del 1 al 3 de octubre de 2026 · Avada 7.16.1 · Local `tiritaito-real.local`*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Un **registro vivo**: dice cómo está construida hoy la ficha de "Hombres de Dios", qué casilla de ACF alimenta cada bloque (con su nombre exacto), qué está comprobado y qué falta. Cada sección nueva que se construya se añade aquí con su ficha. No sustituye a los documentos oficiales: cuando un hallazgo madure, se traslada a donde corresponda (Sección 10).

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Qué elemento de Avada resuelve una necesidad de contenido concreta | `CATALOGO_ELEMENTOS_AVADA.md` |
| Cómo funcionan Avada y Local mecánicamente | `GUIA_AVADA_LOCAL.md` |
| Algo recién descubierto al construir, sin pasar todavía a un documento oficial | `CUADERNO_DEL_CONSTRUCTOR.md` |
| La investigación y las pruebas del podcast | `02-metodologia/investigaciones/INVESTIGACION_PODCAST.md` y el informe de pruebas en Local |
| **Cómo está montada la ficha del santo, el nombre exacto de cada casilla, y qué falta por construir** | **Este documento** |

**Cómo leer los estados** (cada dato lleva su origen, para poder rastrearlo):

| Marcador | Significa |
|---|---|
| ✅ | Comprobado con captura o código fuente revisados en Proyecto 2 (hoy: solo la prueba del 29-30 septiembre) |
| ☑ | Declarado por Álvaro como funcionando, **sin que Proyecto 2 haya visto la captura** |
| 🔲 | Sin probar |
| ⚠️ | Hecho o decidido, pero con algo sin cerrar |

---

## 1. La decisión de arquitectura

**Hombres de Dios se construye con: un tipo de contenido propio (CPT) + casillas ACF + UN solo Layout de Avada + "mostrar solo si" en cada bloque.**

| | Antes (documentado) | Ahora |
|---|---|---|
| Método | Layout + Elementos Guardados (`METODOLOGIA_CONSTRUCCION.md` Sección 4, según `CATALOGO_ELEMENTOS_AVADA.md`) | Layout único + Dynamic Content sobre ACF |
| Cambiar el diseño de una sección | Entrar santo por santo | Se cambia una vez en el Layout y cambian los 12 |
| Sección que un santo no usa | Se omite al construir su ficha a mano | Su casilla queda vacía y el bloque desaparece solo |

**Por qué se cambió:** un elemento Global sincroniza también el contenido (no admite campos distintos por santo) y un elemento Guardado no sincroniza el diseño — así lo documenta `GUIA_AVADA_LOCAL.md` Sección 8.1. Ninguno de los dos cumple a la vez las dos cosas que se piden: diseño compartido y contenido propio. Layout + Dynamic Content sí.

**Cómo se comprobó:** prueba en Local el 29-30 de septiembre de 2026 con un tipo de contenido de prueba. Un santo con las casillas rellenas mostraba los bloques; uno con todo vacío no dejaba ni rastro en el código fuente de la página (✅ comprobado con Ctrl+U). Esta decisión **sustituye** a la anterior; la reconciliación de los documentos oficiales está en la Sección 10.

**Escala:** hoy son 12 santos y llegarán más. Añadir un santo es crear una entrada nueva en el cajón y rellenar sus casillas: la plantilla no se toca. Y si un día hace falta una sección nueva, se añade una vez y vale para todos (Sección 9).

---

## 2. Cómo funciona, en un dibujo

```
CAJÓN "Hombres de Dios"  (tipo de contenido: hombres_de_dios)
 ├── Santo 1 ── casillas rellenas: hdd_biografia, hdd_frases (3 filas)...
 ├── Santo 2 ── casillas rellenas: hdd_biografia, hdd_videos (5 filas)...
 └── ... (12 santos hoy, y llegarán más; cada uno rellena solo lo suyo)
            │
            │  cada santo se pinta con LA MISMA plantilla
            ▼
PLANTILLA  (Layout "TT — HdD — Ficha del santo", UNA para todos)
 ├── Bloque "Su vida"      → lee hdd_biografia   → desaparece si está vacía
 ├── Bloque "Discursos"    → lee hdd_discurso_*  → desaparece si no hay título / filas
 ├── Bloque "Sus palabras" → lee hdd_frases      → desaparece si no hay filas
 ├── Bloque "Vídeos"       → lee hdd_video_*     → desaparece si no hay vídeo destacado
 ├── (bloques que se vayan añadiendo, uno por sección)
 └── Elemento "Content" nativo → válvula de escape para algo puntual de un santo
```

Piezas: **cajón** (tipo de contenido), **casillas** (campos ACF, con nombre exacto), **plantilla** (Layout), **huecos** (Dynamic Content: "pon aquí lo que diga la casilla X del santo actual") e **interruptor** (Conditional Rendering: "enciende este bloque solo si la casilla tiene algo"). La explicación para el equipo, en palabras sencillas, está en el Apéndice A.

---

## 3. La base construida

Fuente: traspaso de Álvaro del 1 de octubre de 2026.

| Pieza | Estado real |
|---|---|
| Tipo de contenido | Clave `hombres_de_dios`, etiquetas "Hombres de Dios" / "Hombre de Dios". Público, **sin archivo**, prefijo de URLs apagado, slug propio `hombres-de-dios`. Admite título, editor e imagen destacada. ☑ |
| Layout | "TT — HdD — Ficha del santo", condición **All** del tipo de contenido. Zona de contenido: pieza "TT — HdD — Contenido". ☑ |
| Grupo de casillas | ACF "TT — HdD — Ficha del santo", ubicación: tipo de contenido = Hombre de Dios. Prefijo `hdd_`. ☑ |
| Santos de prueba | "TT — Santo de prueba" (con datos) y "TT — Santo de prueba vacío". Las dos fichas abren sin 404. ☑ |
| Vista previa en el editor | "View Dynamic Data As" = Hombre de Dios. Avada previsualiza **un santo por su cuenta** (cogió el vacío) y no ofrece selector de santo. No supone ningún problema (confirmado por Carlitos, 3 oct). ☑ |

**Consecuencia práctica de la vista previa:** si el santo previsualizado tiene la casilla vacía, el editor enseña la marca literal (`{acf_text,field:hdd_biografia}`). No es un error; en la web real se ve bien (Sección 6, hallazgo 2).

---

## 4. Registro de secciones

### 4.1 Resumen

| Sección | Tipo | Casillas | Desaparece si | Estado |
|---|---|---|---|---|
| Su vida | SENCILLA | `hdd_biografia` | `hdd_biografia` vacía | ☑ construida; falta ajustar el aspecto al boceto |
| Discursos | ESPECIAL (destacado + lista) | `hdd_discurso_ultimo_*` y `hdd_discursos` | título destacado vacío / lista sin filas | ☑ construida y probada (2 oct) |
| Sus palabras | LISTA | `hdd_frases` | 0 filas | ☑ construida (2 oct) |
| Vídeos | ESPECIAL (destacado + lista) | `hdd_video_*` y `hdd_videos` | sin vídeo destacado / lista sin filas | ☑ construida (3 oct); 6 diferencias con el boceto por decidir |
| Podcast | por diseñar | por diseñar | por diseñar | 🔲 Sección 8 |
| Resto de secciones del boceto | — | — | — | 🔲 sin ficha todavía: se registran según Carlota y Álvaro las cierren |

### 4.2 FICHA — Su vida

- **Tipo:** SENCILLA
- **Casilla:** `hdd_biografia` · Área de texto · "Nuevas líneas" = "Añadir párrafos automáticamente" (con "Sin formato" se pierden los párrafos)
- **Desaparece si:** Container → Conditional Rendering → ACF Field → `hdd_biografia` → Not Equal To → valor vacío
- **Plegado:** elemento nativo Collapsible / Read More; funciona en Local
- **Pendiente:** aspecto del botón y del container frente al boceto
- **Santos que la usan:** los de prueba
- ☑ La captura de Álvaro del 1 de octubre mostraba aún "Su vida" en el santo vacío (sospecha: caché de LiteSpeed). Álvaro confirmó después que funciona y Carlitos confirma (3 oct) que la caché no da problema.

### 4.3 FICHA — Discursos (probada en Local el 2 de octubre de 2026)

- **Tipo:** ESPECIAL — un destacado SENCILLO + una LISTA
- **Santos que la usan:** por confirmar (el ejemplo del boceto apunta a San Juan Pablo II)

| Casilla | Clase | Para qué |
|---|---|---|
| `hdd_discurso_ultimo_titulo` | Texto | título del discurso destacado |
| `hdd_discurso_ultimo_titulo_rojo` | Texto | año o lugar, se pinta en rojo |
| `hdd_discurso_ultimo_texto` | Editor WYSIWYG | texto con "Leer más" |
| `hdd_discurso_ultimo_imagen` | Imagen | imagen del destacado. **Formato de retorno que funcionó: sin anotar** (completar) |
| `hdd_discursos` | Repetidor | "Otros discursos" |
| ↳ `hdd_discurso_titulo` | Texto | título completo, con el año al principio (ej. "1983 — Discurso a los jóvenes de Costa Rica") |
| ↳ `hdd_discurso_texto` | Área de texto | texto del discurso |
| ↳ `hdd_discurso_anio` | Texto | **ELIMINAR** (decidido el 3 oct: sin uso, el año va en el título; pendiente de que Álvaro la borre en ACF) |
| ↳ `hdd_discurso_titulo_rojo` | Texto | **ELIMINAR** (decidido el 3 oct: sin uso; pendiente de que Álvaro la borre en ACF) |

- **Desaparece si:** Container destacado → `hdd_discurso_ultimo_titulo` vacío (ACF Field · no igual a · vacío). Container lista → `hdd_discursos` sin filas (ACF Repeater Count · mayor que · 0).
- **Cómo está construido:**
  - Container "TT — HdD — Discurso destacado": texto fijo "Jóvenes apasionados" (provisional) + Título fijo "Discurso" + fila 1/2 + 1/2. Izquierda: Imagen con Dynamic Data → ACF Image → `hdd_discurso_ultimo_imagen`. Derecha: Text Block con las dos marcas dentro del mismo texto (`{acf_text,field:hdd_discurso_ultimo_titulo} {acf_text,field:hdd_discurso_ultimo_titulo_rojo}`) + Text Block con `hdd_discurso_ultimo_texto` + Collapsible / Read More (~250px).
  - Container "TT — HdD — Otros discursos": Título fijo "Otros discursos" + Toggles (modo Toggles) con Select Dynamic Data → ACF Repeater → Field = `hdd_discursos`. Un solo hijo plantilla; en su Title y su Toggle Content, dato de tipo ACF Repeater Subfield (`hdd_discursos` / `hdd_discurso_titulo` y `hdd_discursos` / `hdd_discurso_texto`).
- **Pendiente:** aspecto frente al boceto; borrar en ACF las dos subcasillas sin uso (decidido el 3 de octubre de 2026).

### 4.4 FICHA — Sus palabras

- **Tipo:** LISTA · **Boceto:** "Citas sobre la foto · Post Cards" (Carlota) · **Santos que la usan:** por definir

| Casilla | Clase | Para qué |
|---|---|---|
| `hdd_frases` | Repetidor | lista de frases del santo (mínimo 0 filas) |
| ↳ `hdd_frase_texto` | Área de texto | la frase |
| ↳ `hdd_frase_autor` | Texto | opcional: de dónde es la frase |
| (foto de fondo) | imagen destacada nativa de WordPress | sin casilla ACF |

- **Cómo está construido:** Container "TT — HdD — Sus palabras" con la imagen destacada como fondo (Dynamic Content) + degradado oscuro hacia abajo + título "Sus palabras" dentro del bloque. Post Cards con Content Source = ACF Repeater, Repeater Field = `hdd_frases`, layout Carrusel, 1 columna, autoplay, flechas ocultas, puntos visibles. Molde propio "TT — HdD — Frase": Text Block con "ACF Repeater Sub Field" → `hdd_frase_texto`, y Text Block del autor con "ACF Repeater Sub Field" → `hdd_frase_autor` y Conditional Rendering (solo si no está vacío).
- **Desaparece si:** `hdd_frases` tiene 0 filas (ACF Repeater Count > 0 en el Container).
- **Partes opcionales dentro del bloque:** el autor.
- **Las cuatro partes que estaban "por probar" funcionan, según Álvaro (2 oct):** fondo con imagen destacada dinámica en un Container, carrusel con autoplay en Post Cards sobre repetidor, autor opcional dentro del molde, e interruptor del bloque entero. ☑
- **Pendiente (no bloquea, para Carlota):** radio de la foto (25px del boceto frente a 15px de la norma de Avada, `--tt-r-web`); puntos del carrusel (el activo solo cambia de color, no se alarga — ya documentado como limitación de Post Cards en `CATALOGO_ELEMENTOS_AVADA.md` Sección 5 bis); ajustes frente al boceto y revisión Desktop/Medium/Small.

### 4.5 FICHA — Vídeos (probada en Local el 3 de octubre de 2026)

- **Tipo:** ESPECIAL — un destacado SENCILLO + una LISTA, con recorte "Ver más" · **Santos que la usan:** por decidir (probado con el santo de prueba)

| Casilla | Clase | Para qué |
|---|---|---|
| `hdd_video_url` | Texto | enlace completo de YouTube del vídeo destacado (el grande) |
| `hdd_video_titulo` | Texto | título bajo el vídeo destacado |
| `hdd_videos` | Repetidor | los demás vídeos del santo |
| ↳ `hdd_vid_titulo` | Texto | título de cada vídeo |
| ↳ `hdd_vid_url` | Texto | enlace completo de YouTube de cada vídeo |

Los nombres los eligió Álvaro (el boceto decía "titulo" y "url" sin prefijo). **Decidido el 3 de octubre de 2026: se mantienen tal cual**, para no tener que cambiarlos en la construcción. Es la única excepción a la convención de la Sección 5 y no se replica en secciones nuevas.

- **Desaparece si:** Container "TT — HdD — Vídeos" → `hdd_video_url` no igual a vacío (sin destacado, desaparece el apartado entero). Columna "Más vídeos" → `hdd_videos` ACF Repeater Count mayor que 0 (sin filas, desaparecen el título "Más vídeos", las tarjetas y el enlace de recorte).
- **Partes opcionales dentro del bloque:** ninguna.
- **Cómo está construido:**
  - Vídeo grande: elemento YouTube con "ACF Text" en Video ID or Url (`hdd_video_url`) y Text Block con "ACF Text" (`hdd_video_titulo`). Video Facade activo.
  - Más vídeos: Post Cards con Content Source = ACF Repeater, campo `hdd_videos`, molde propio "TT — HdD — Vídeos" con "ACF Repeater Sub Field" en los dos datos.
  - Lightbox: columna del molde con Link URL dinámico (`hdd_vid_url`) y Link Target = Lightbox.
  - "Ver más": recorte con Collapsed Height 290px escritorio / 200px tablet / 190px móvil. Enlace "Seguir viendo". Rejilla de 3 columnas en escritorio, 2 en móvil.
- **Comprobado en Local, según Álvaro (3 oct):** ☑ vídeo grande desde casilla con su título · ☑ Post Cards sobre repetidor con molde propio, una tarjeta por fila · ☑ recorte "Ver más" sobre la rejilla · ☑ Lightbox desde la miniatura de cada fila · ☑ las dos condiciones "mostrar solo si".

**Diferencias con el boceto de Carlota, por decidir:**

| # | Diferencia | Salida posible |
|---|---|---|
| 1 | Esquinas del vídeo grande: el boceto pide 15px; el elemento YouTube solo tiene las pestañas General y Extras, sin Border Radius | Posible TRASPASO a Proyecto 11 |
| 2 | Etiqueta roja "DESTACADO": no construida | Construir |
| 3 | Texto del enlace: boceto "Ver más vídeos" / "Ver menos"; construido "Seguir viendo" | Confirmar si el elemento deja cambiar el texto según el estado, o si Carlota acepta "Seguir viendo" |
| 4 | Con 3 vídeos o menos el boceto no muestra el enlace | Confirmar si aparece igualmente en Local |
| 5 | Ancho máximo del vídeo grande: 600px por defecto | Confirmar el valor de Dimensions que se dejó |
| 6 | Título del destacado: 20px semibold en el boceto | Confirmar si se aplicó |

---

## 5. Convenciones de nombres y su estado

| Elemento | Convención | Ejemplo |
|---|---|---|
| Casillas | prefijo `hdd_`, escritas a mano (ACF inventa el nombre a partir de la etiqueta y casi nunca coincide) | `hdd_biografia` |
| Lista (Repetidor) | plural | `hdd_frases`, `hdd_discursos`, `hdd_videos` |
| Subcasilla de una lista | singular + campo | `hdd_frase_texto`, `hdd_discurso_titulo` |
| Destacado (casillas sencillas junto a una lista) | `hdd_<tema>_ultimo_*` | `hdd_discurso_ultimo_titulo` |
| Layout, Containers, moldes de tarjeta | prefijo "TT — HdD —" | "TT — HdD — Frase" |

**Decisiones sobre nombres (3 de octubre de 2026):**

| # | Caso | Decisión |
|---|---|---|
| 1 | Vídeos mezcla `hdd_video_*` (destacado) con `hdd_vid_*` (lista) | Se mantiene tal cual. Única excepción a la convención; no se replica |
| 2 | Discursos tiene dos subcasillas sin uso dentro del repetidor (`hdd_discurso_anio`, `hdd_discurso_titulo_rojo`) | Se eliminan en ACF (pendiente de Álvaro) |
| 3 | `hdd_discurso_ultimo_imagen`: falta anotar el formato de retorno que funcionó | Completar la ficha |

**Regla de oro:** cuando un santo real ya tiene una casilla rellenada, su nombre no se cambia nunca (el contenido quedaría huérfano). Borrar una casilla sin uso, mientras solo los santos de prueba tienen contenido, no deja nada huérfano.

---

## 6. Hallazgos técnicos de la construcción

Descubiertos al construir. Cuando maduren, se trasladan a `CATALOGO_ELEMENTOS_AVADA.md` (Sección 10). Estado: ✅ comprobado en Proyecto 2 · ☑ según Álvaro.

| # | Hallazgo | Origen | Estado |
|---|---|---|---|
| 1 | En el interruptor "mostrar solo si", el campo debe ser el **nombre técnico exacto** (`hdd_biografia`). Con la etiqueta ("Biografía.") no avisa y falla | Álvaro, 1 oct | ☑ |
| 2 | En el editor, `{acf_text,field:hdd_biografia}` sale literal cuando el santo previsualizado tiene la casilla vacía. No es un error | Álvaro, 1 oct | ☑ |
| 3 | En un molde sobre un repetidor, "ACF Text" no lee subcasillas y "ACF Repeater Single Value" con Index vacío repite siempre la fila 1. Lo que funciona es **"ACF Repeater Sub Field"** (Post Cards) / "ACF Repeater Subfield" (Toggles) | Álvaro, 2 oct | ☑ |
| 4 | Un Toggles se alimenta de un repetidor sin Post Cards: Select Dynamic Data → ACF Repeater; un hijo = plantilla repetida por fila | Álvaro, 2 oct | ☑ |
| 5 | El Title de un Toggle admite **un solo** dato dinámico (por eso el año va dentro del título) | Álvaro, 2 oct | ☑ |
| 6 | El elemento **Título** no admite Dynamic Data: usar Text Block para texto que cambia por santo (el Título sigue valiendo para texto fijo) | Álvaro, 2 oct | ☑ |
| 7 | Dos marcas dinámicas dentro del mismo Text Block se pintan juntas en la web real; el editor las muestra como texto crudo | Álvaro, 2 oct | ☑ |
| 8 | Casilla de imagen ACF en el elemento Imagen: funciona. Editor enriquecido (WYSIWYG) en un Text Block: funciona, con párrafos y negrita | Álvaro, 2 oct | ☑ |
| 9 | Área de texto: con "Nuevas líneas" = "Añadir párrafos automáticamente" se conservan los párrafos; con "Sin formato" se pierden | Álvaro, 1 oct | ☑ |
| 10 | Collapsible / Read More nativo de Avada funciona para recortar texto largo | Álvaro, 1-2 oct | ☑ |
| 11 | Fondo con imagen destacada dinámica en un Container; carrusel con autoplay en Post Cards sobre repetidor; Conditional Rendering **dentro** del molde de una tarjeta (autor opcional) | Álvaro, 2 oct | ☑ |
| 12 | Elemento YouTube con "ACF Text" en Video ID or Url, con Video Facade activo; Lightbox desde una columna con Link URL dinámico y Link Target = Lightbox; recorte "Ver más" (Collapsed Height) sobre una rejilla de Post Cards | Álvaro, 3 oct | ☑ |
| 13 | El elemento YouTube **no tiene Border Radius** (solo pestañas General y Extras) | Álvaro, 3 oct | ☑ |
| 14 | Post Cards no distingue el punto activo del carrusel por forma o tamaño, solo por color | `CATALOGO_ELEMENTOS_AVADA.md` Sección 5 bis (17 sept) y Álvaro, 2 oct | ✅ / ☑ |
| 15 | Si el nombre de una subcasilla en el molde no coincide con el que ACF generó, la tarjeta sale vacía sin error (ACF generó `texto_de_la_frase` a partir de "Texto de la frase") | Prueba, 29 sept | ✅ |
| 16 | El molde de fábrica "Starter Blog Post Card" pinta el título de la entrada y no lee subcasillas: hace falta molde propio, sin editar el de fábrica | Prueba, 29 sept | ✅ |
| 17 | En el ACF de Local no hay pestaña "Archivo"; vive en las URLs | Álvaro, 1 oct | ☑ |

---

## 7. Qué falta para dar Hombres de Dios por terminado (salvo el podcast)

| # | Pendiente | Quién | Bloquea a |
|---|---|---|---|
| 1 | Borrar en ACF las dos subcasillas sin uso de Discursos y anotar el formato de retorno de la imagen del destacado | Álvaro | Cargar contenido real de santos |
| 2 | Confirmar si Header y Footer están asignados al Layout (Álvaro vio el pie con contenido de ejemplo de Avada Studio) | Álvaro | Aspecto real de la ficha |
| 3 | Decidir la barra de título: hoy sale la de respaldo, con "Home". `CATALOGO_ELEMENTOS_AVADA.md` Sección 2 ya dejaba abierto si Hombres de Dios lleva imagen propia o la apaga | Carlitos + Carlota | Cabecera de la ficha |
| 4 | Confirmar "Show in Nav Menus" del tipo de contenido (no visto confirmado) | Álvaro | Que Post Cards pueda listar santos (portada) |
| 5 | Diferencias con el boceto de Vídeos (Sección 4.5) y de Sus palabras (Sección 4.4) | Carlota + Álvaro (TRASPASO a Proyecto 11 si hace falta CSS) | Cierre visual de esas dos secciones |
| 6 | Aspecto de Su vida y Discursos frente al boceto | Álvaro | Cierre visual |
| 7 | Registrar con ficha el resto de secciones del boceto de Carlota | Carlota + Álvaro | Cierre del alcance de la ficha |
| 8 | Diseñar la sección Podcast (Sección 8), en un chat aparte | Carlitos | Cierre de la ficha |
| 9 | Cargar el contenido real de los 12 santos (y de los que lleguen) | Equipo | Lanzamiento de la sección |

---

## 8. Hueco reservado — Podcast

**El podcast es una sección más de la ficha**, pero su estructura **todavía no está diseñada**: se hace en un chat nuevo, partiendo de este documento (plan del 3 de octubre de 2026). Aquí queda solo lo ya averiguado en las pruebas en Local (1-3 octubre), para que ese chat no parta de cero.

| Qué | Dato | Origen |
|---|---|---|
| Alcance | 4 canales de santos dentro de Hombres de Dios. Charlas de la Biblia, Rincón de Nico y Oraciones van aparte (la arquitectura de Oraciones está sin determinar) | Equipo, 3 oct |
| Opción elegida | Catálogo propio de episodios en WordPress (una entrada por episodio, rellenada desde el feed) pintado con Avada nativo. Los episodios los crea un importador propio (Proyecto 2), no un plugin | Decisiones 30 sept - 3 oct |
| Diseño buscado | El Layout de Hombres de Dios como molde global que lee y presenta el podcast, con el contenido específico de cada santo (y la dirección de su feed) en campos ACF de su propia entrada; el podcast es una sección más, y un campo vacío la oculta | Carlitos, 2-3 oct |
| Cómo llegan los episodios a la ficha | Un solo Layout con Post Cards (Content Source = **ACF Relationship**, campo "episodios" de tipo Relación en cada santo): Santo Uno 12 + "Cargar más", Santo Dos 3 episodios, Santo Tres (campo vacío) zona en blanco sin error | Prueba P3, 2 oct (resuelta) |
| Descartado | Content Source = Related: no llega a los episodios | Prueba P3, 1 oct |
| Vídeo de YouTube de un episodio | Se oculta con lógica condicional cuando el campo está vacío; con facade de Avada reproduce sin error 153 | Pruebas, 2-3 oct |
| Compartir | Avada genera las etiquetas Open Graph con su opción activada (no hay plugin SEO); un episodio con imagen propia saca su título, descripción e imagen | Pruebas, 2-3 oct |

**Cosas a tener en cuenta al diseñar la sección:** los episodios salen **ordenados por fecha, no por el orden elegido** en la relación; el botón "Cargar más" hay que ponerlo a mano; si se rehace un Post Cards, reconstruirlo desde cero (el antiguo conservó el filtro por canal guardado); pendientes: medir el rendimiento de Santo Uno con un episodio real y probar Open Graph con un episodio real. Transcripciones: por ahora no se hace nada.

---

## 9. Cómo se añade una sección nueva

**Siempre en tres pasos:**
1. Crear las casillas nuevas en ACF, con el nombre exacto de la ficha.
2. Añadir un bloque nuevo al Layout, con sus huecos y su interruptor "mostrar solo si" (casilla sencilla → ACF Field · no igual a · vacío; lista → ACF Repeater Count · mayor que · 0).
3. Rellenar la casilla en los santos que la usen; los demás la dejan vacía y el bloque no aparece.

**Qué es seguro y qué no:**

| Cambio | ¿Seguro? |
|---|---|
| Añadir una sección nueva | Sí |
| Cambiar el diseño de una sección | Sí: se hace una vez y cambia en los 12 |
| Quitar un bloque del Layout | Sí: los datos siguen guardados |
| Que un santo no use una sección | Sí: se deja vacía |
| Renombrar una casilla con santos ya rellenados | **No**: el contenido queda huérfano (comportamiento normal de ACF) |
| Convertir una casilla sencilla en lista | **No**: crear una casilla nueva y pasar el contenido |

**Formato de ficha** (idioma común entre diseño y construcción):

```
FICHA DE SECCIÓN
- Nombre de la sección:
- Tipo: SENCILLA | LISTA | ESPECIAL (por probar)
- Casillas (nombre interno exacto · clase · para qué sirve):
- Si es LISTA: nombre de la lista y subcasillas (cada una con su nombre exacto):
- Desaparece si: (qué casilla vacía apaga el bloque)
- Partes opcionales dentro del bloque (si las hay):
- Santos que la usan:
```

**Reglas de oro:** (1) nunca renombrar una casilla con contenido; (2) nunca convertir sencilla en lista; (3) nombres escritos a mano, con el prefijo `hdd_` y la convención de la Sección 5; (4) cada bloque es autónomo, con su título dentro, y no depende del de arriba ni del de abajo — prohibido alternar fondos por posición; (5) título del santo y foto de portada: los nativos de WordPress; (6) nunca Clase CSS inventada: solo clases `tt-` registradas, y si hace falta un efecto que Avada no da, TRASPASO a Proyecto 11.

---

## 10. Documentos oficiales por reconciliar

Esta decisión cambia lo escrito en otros documentos. **Se actualizan cuando Carlitos lo confirme**, no antes.

| Documento | Sección | Qué cambia | Estado |
|---|---|---|---|
| `README.md` | Estructura, Índice, "Cómo empezar", Estado global | Añadir este documento; en Estado global, la fila "Confirmar que Layout + Elementos Guardados funciona para Hombres de Dios" queda **sustituida** por esta decisión; la de Post Cards para la portada de Hombres de Dios sigue abierta | 🔲 pendiente |
| `METODOLOGIA_CONSTRUCCION.md` | Sección 4 | Sustituir "Layout + Elementos Guardados" por "Layout único + CPT + ACF + mostrar solo si" | 🔲 documento no disponible en esta sesión: hay que subirlo |
| `ALCANCE_WEB_NUEVA.md` | Pregunta abierta 3.1 | CPT vs Posts con categoría: **resuelta, CPT `hombres_de_dios`** | 🔲 documento no disponible en esta sesión |
| `GUIA_AVADA_LOCAL.md` | Sección 8.2 (árbol de decisión) | Añadir la rama "contenido distinto por entrada en un CPT con ACF, estructura repetida" → Layout único + Dynamic Content, antes de la rama "Guardado" | 🔲 pendiente |
| `GUIA_AVADA_LOCAL.md` | Sección 17 y 19 | Añadir lo confirmado en Local (29 sept - 3 oct); la pregunta 2 (Post Cards para la portada de Hombres de Dios) sigue abierta | 🔲 pendiente |
| `CATALOGO_ELEMENTOS_AVADA.md` | Sección 2 (Breadcrumbs: Post Categories/Terms en Off) y Sección 6 (Search: Limit Search Results Post Types en Off) | Ambos estaban apagados "a propósito" por depender de 3.1; con la decisión CPT, **ya se pueden decidir** | 🔲 pendiente |
| `CATALOGO_ELEMENTOS_AVADA.md` | Sección 4 y 5 | Añadir los patrones de la Sección 6 de este documento cuando se confirmen con captura; corregir la mención a "los 9 santos": son 12 y llegarán más | 🔲 pendiente |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | Proyectos 3/7/9 y 4/6 | Guardar los prompts de los Apéndices B y C si se quiere que vivan en el repositorio | 🔲 a decidir |

---

## 11. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Álvaro: borrar en ACF las dos subcasillas sin uso de Discursos y anotar el formato de retorno de la imagen del destacado, antes de cargar contenido real.
2. Álvaro: confirmar Header/Footer y "Show in Nav Menus". Carlitos y Carlota: decidir la barra de título.
3. Carlota + Álvaro: cerrar las diferencias de Vídeos y Sus palabras; registrar con ficha el resto de secciones.
4. Reconciliar los documentos oficiales (Sección 10) cuando Carlitos lo confirme.
5. Chat nuevo para el podcast, con este documento actualizado en el repositorio: estructura de la sección Podcast y prompts para Carlota y Álvaro.

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Qué santos usan cada sección? Ninguna ficha lo tiene cerrado | Carga de contenido real |
| 2 | ¿La barra de título de la ficha lleva imagen propia o se apaga? | Cabecera de la ficha |
| 3 | ¿Aceptan el radio de 15px de la norma Avada o mantienen el 25px del boceto en Sus palabras y Vídeos? | Coherencia visual con el resto de la web nueva |
| 4 | ¿Cuántas secciones más tiene el boceto de Carlota que aún no tienen ficha? | Alcance real de la ficha |

**Resueltas el 3 de octubre de 2026:** nombres de Vídeos (se mantienen), subcasillas sin uso de Discursos (se eliminan), vista previa de "View Dynamic Data As" (sin problema), número de santos (12, y llegarán más), caché de LiteSpeed (sin problema).

---

## Apéndice A — Bloque común (explicación base para explicar el sistema)

*Va al principio del primer mensaje de un chat nuevo de Carlota o de Álvaro, justo antes del prompt de cada uno (Apéndices B y C). Versión actualizada el 3 de octubre de 2026 con lo ya construido.*

```
CÓMO FUNCIONA LA FICHA DE "HOMBRES DE DIOS" — explicación base

Usa esta explicación para contárselo a tu persona con palabras muy sencillas, con las comparaciones de abajo, cada vez que haga falta. No des por hecho que conoce ningún término técnico: explícalo la primera vez que aparezca.

LA IDEA EN UNA FRASE
Hay UNA sola plantilla para todos los santos (hoy 12, y llegarán más). Cada santo rellena sus casillas, y la plantilla dibuja solo lo que está relleno.

LAS PIEZAS (cada una con su comparación)
1. TIPO DE CONTENIDO "Hombres de Dios": un cajón nuevo en WordPress, aparte de Páginas y Entradas. Cada santo es una hoja dentro del cajón, con su propia dirección web.
2. CASILLAS (campos ACF): el formulario que rellena cada santo (biografía, frases...). Cada casilla tiene un nombre interno exacto, por ejemplo "hdd_biografia". Es como el DNI de la casilla: se escribe a mano, siempre con el prefijo "hdd_", y NO se cambia una vez que hay santos rellenados.
   Hay dos clases de casilla:
   - SENCILLA: guarda una cosa (un texto, una imagen, un enlace).
   - LISTA: guarda varias filas iguales (por ejemplo, 10 frases). Se llama "Repetidor". Cada fila tiene sus propias subcasillas, también con nombre exacto.
3. LA PLANTILLA (Layout de Avada): una sola, hecha una vez para todos los santos. Si se cambia su diseño, cambian los 12 a la vez. No tiene texto escrito: solo huecos.
4. HUECOS (Contenido Dinámico): cada hueco de la plantilla dice "aquí pon lo que ponga la casilla X del santo que se esté viendo".
5. INTERRUPTOR "MOSTRAR SOLO SI" (Conditional Rendering): cada bloque lleva un interruptor automático: "enciende esta habitación solo si la casilla tiene algo". Si está vacía, el bloque ENTERO no existe, ni siquiera con su título.
6. PARA LISTAS: se usa el elemento Post Cards (una tarjeta por fila, con un "molde de tarjeta" propio) o un Toggles (un desplegable por fila).

NOMBRES DE CASILLA (convención, para que todo sea coherente)
- Lista (Repetidor): en plural, ej. hdd_frases, hdd_discursos, hdd_videos.
- Subcasilla de una lista: singular + campo, ej. hdd_frase_texto, hdd_discurso_titulo.
- Destacado junto a una lista: hdd_<tema>_ultimo_..., ej. hdd_discurso_ultimo_titulo.
- Nunca dos casillas con el mismo nombre ni abreviaturas distintas para lo mismo. (Única excepción ya existente y aceptada: en Vídeos, el destacado usa hdd_video_* y la lista hdd_vid_*; no se replica.)

LO QUE YA ESTÁ COMPROBADO EN LOCAL
✅ Una plantilla compartida se aplica a todos los santos, con la condición "All" del tipo de contenido.
✅ Un hueco de texto muestra la casilla del santo actual.
✅ Post Cards sobre una lista muestra una tarjeta por fila, con molde propio.
✅ Un bloque cuya casilla está vacía desaparece entero: se comprobó en el código fuente de la página, sin rastro.
☑ (según Álvaro, ya construido en las secciones Su vida, Discursos, Sus palabras y Vídeos): texto largo con "Leer más"; imagen y editor enriquecido desde una casilla; Toggles alimentado por una lista; carrusel con autoplay sobre una lista; fondo con la imagen destacada del santo; partes opcionales DENTRO de una tarjeta (el autor de una frase); vídeo de YouTube desde una casilla; vídeo en ventana emergente (Lightbox) desde cada fila de una lista; recorte "Ver más" sobre una rejilla.

TRAMPAS YA VIVIDAS
⚠️ Si el nombre de una casilla o subcasilla en la plantilla no coincide EXACTAMENTE con el de ACF, Avada no avisa y el bloque sale vacío. En el interruptor "mostrar solo si" hay que poner el nombre técnico (hdd_...), nunca la etiqueta.
⚠️ En un molde sobre una lista, "ACF Text" no lee las subcasillas: hay que usar "ACF Repeater Sub Field".
⚠️ El molde de tarjeta de fábrica no lee subcasillas: hace falta uno propio. Nunca se edita el de fábrica.
⚠️ El elemento Título no admite datos dinámicos: para texto que cambia por santo, usar Text Block.
⚠️ En el editor, si el santo que se previsualiza tiene la casilla vacía, el hueco se ve como un texto con llaves. No es un error.

LÍMITES CONOCIDOS DE AVADA (no los prometas en un diseño)
❌ El elemento YouTube no tiene esquinas redondeadas propias.
❌ En el carrusel de Post Cards, el punto activo solo se distingue por color, no por forma ni tamaño.

TODAVÍA NO COMPROBADO (marcar siempre como "POR PROBAR")
🔲 Casillas de tipo enlace (para las direcciones de YouTube se usó texto).
🔲 El podcast: su estructura se diseña aparte.

LAS SECCIONES NO ESTÁN CERRADAS
No hay una lista definitiva. Se van definiendo mientras se diseña. Añadir, cambiar o quitar secciones no rompe las demás.

CÓMO SE AÑADE UNA SECCIÓN (siempre igual, 3 pasos)
1. Crear las casillas nuevas en ACF (con el nombre exacto de la ficha).
2. Añadir un bloque nuevo a la plantilla, con sus huecos y su interruptor "mostrar solo si".
3. Rellenar la casilla en los santos que la usen. Los demás la dejan vacía y el bloque no aparece.

CADA SECCIÓN SE DESCRIBE CON UNA FICHA (formato fijo)
FICHA DE SECCIÓN
- Nombre de la sección:
- Tipo: SENCILLA | LISTA | ESPECIAL (por probar)
- Casillas (nombre interno exacto · clase · para qué sirve):
- Si es LISTA: nombre de la lista y sus subcasillas (cada una con su nombre exacto):
- Desaparece si: (qué casilla vacía apaga el bloque)
- Partes opcionales dentro del bloque (si las hay):
- Santos que la usan:

REGLAS DE ORO
- Nunca cambiar el nombre de una casilla que ya tiene contenido en algún santo. Si hace falta, crear una nueva y pasar el contenido.
- Nunca convertir una casilla sencilla en lista (ni al revés): crear una nueva.
- Los nombres de casilla se escriben a mano. ACF los inventa a partir de la etiqueta y casi nunca coinciden con lo que uno espera.
- Cada bloque es autónomo: su título va DENTRO del bloque y no depende del bloque de arriba ni del de abajo. Prohibido alternar fondos por posición: si falta un bloque, se rompería.
- Título del santo y foto de portada: usar los nativos de WordPress (título de la entrada e imagen destacada), no casillas ACF.
```

---

## Apéndice B — Prompt para Carlota

**Para quién:** Carlota, en su cuenta de bocetos. **Dónde:** Proyecto 4 o Proyecto 6, en el primer mensaje de un chat nuevo, pegando antes el Apéndice A. **Cuándo:** para cada ronda de bocetos de una sección de la ficha de Hombres de Dios (un chat nuevo por ronda, para no agotar el uso). Si el equipo ya tiene secciones construidas, pegar debajo las fichas del registro (Sección 4) para que respete los nombres y el estilo.

```
TU PARTE: eres quien diseña los bocetos de la ficha. Sigue tu chuleta para el ADN visual y las reglas de siempre (HTML autocontenido, escritorio y móvil, dibuja libre, nunca modo oscuro, nunca código de producción).

CÓMO PENSAR CADA SECCIÓN: antes de dibujar un bloque, pregúntate de qué clase es:
- SENCILLO: se rellena una vez por santo (biografía, foto grande, una cita destacada...).
- LISTA: varios elementos iguales (frases, discursos, vídeos...). Todos los elementos tienen la misma estructura; si una parte es opcional, dilo.
- ESPECIAL: algo que no encaja en las dos anteriores, o un destacado + una lista (como Discursos o Vídeos), o algo de la lista "por probar". Se puede proponer; se marca "POR PROBAR" y Álvaro lo comprueba antes de construirlo.
Con esa clasificación, tu creatividad es libre: cualquier diseño que se pueda ver como "un bloque autónomo que se llena con casillas" sirve. Prefiere los patrones comprobados, pero no dejes de proponer algo bueno solo por estar sin probar: márcalo. Respeta los límites conocidos de Avada del bloque común (esquinas del vídeo, punto activo del carrusel): si tu diseño los necesita, dilo como diferencia a decidir.

CÓMO TRABAJAR
- Fase 1 (bocetos): diseña los bloques, 2-3 direcciones, escritorio y móvil. Incluye un interruptor (o dos versiones) para ver la ficha COMPLETA y una ficha con solo 3 secciones, para comprobar que el diseño aguanta cuando faltan bloques. Comprueba también que el texto corto y el largo se ven bien. Un chat nuevo por ronda.
- Puedes descubrir secciones nuevas mientras diseñas; es lo esperado. Cada vez que propongas una, dilo: "esto añade una sección nueva".
- Fase 2 (boceto cerrado con el equipo): añade al mismo HTML, debajo de cada bloque, un panel plegable "Cómo construirlo" para Álvaro. Debe incluir la FICHA DE SECCIÓN completa, con los nombres de casilla propuestos siguiendo la convención del bloque común (prefijo hdd_, lista en plural, subcasilla en singular + campo), qué elemento de Avada sugieres para cada parte y qué valores concretos (tamaños, colores, espaciados), en lenguaje muy sencillo, como si estuvieras a su lado señalando la pantalla. Si sugieres un elemento del que no estés segura, dilo: Álvaro lo verifica.

Explícale a Carlota, con las comparaciones del bloque común, todo lo que no entienda. No le sueltes términos sin explicar.
```

---

## Apéndice C — Prompt para Álvaro

**Para quién:** Álvaro, en cualquiera de sus tres cuentas de construcción. **Dónde:** Proyecto 3, 7 o 9, en el primer mensaje de un chat nuevo, pegando antes el Apéndice A. **Cuándo:** cada vez que Carlota cierre una sección (un chat nuevo por sección), pegando debajo la ficha de esa sección. La base ya está construida (Sección 3): este prompt es el vigente desde el 3 de octubre de 2026 y sustituye a las etapas 1 y 2 anteriores.

```
TU PARTE: eres quien construye. Trabajas en Local (tiritaito-real.local), nunca en producción. Un chat nuevo por sección.

CÓMO EXPLICAR: paso a paso, como si estuvieras a su lado señalando la pantalla ("en el panel de la derecha, pestaña Diseño, busca el campo..."). Explica cada término técnico la primera vez.

LA BASE YA ESTÁ CONSTRUIDA (no la rehagas):
- Tipo de contenido con clave hombres_de_dios, público, sin archivo, slug hombres-de-dios.
- Layout "TT — HdD — Ficha del santo", condición All del tipo de contenido. Zona de contenido: pieza "TT — HdD — Contenido".
- Grupo de casillas ACF "TT — HdD — Ficha del santo", prefijo hdd_.
- "View Dynamic Data As" = Hombre de Dios. Avada previsualiza un santo por su cuenta; si cae uno con la casilla vacía, el editor enseña la marca con llaves. No es un error.
- Dos santos de prueba: uno con datos y uno vacío.
- Secciones ya hechas: Su vida, Discursos, Sus palabras, Vídeos. Si te pasan sus fichas, úsalas como referencia de estilo y de nombres.

AÑADIR UNA SECCIÓN (la rutina que se repite por cada FICHA DE SECCIÓN que te llegue de Carlota):
a) Crea las casillas nuevas en ACF con los nombres EXACTOS de la ficha. Si el nombre no sigue la convención del bloque común, avisa antes de crear nada: una vez que hay contenido real no se puede renombrar. Anota el nombre real que ACF acabe guardando, sobre todo el de las subcasillas, y el formato de retorno de las casillas de imagen.
b) Añade el Container a la plantilla con su hueco y su interruptor "mostrar solo si": casilla sencilla → ACF Field · no igual a · vacío (con el NOMBRE TÉCNICO hdd_..., nunca la etiqueta); lista → ACF Repeater Count · mayor que · 0.
c) Si es lista: Post Cards con Content Source = ACF Repeater y un molde propio con nombre "TT — HdD — [sección]", o un Toggles alimentado por el repetidor. Dentro del molde, "ACF Repeater Sub Field" (nunca "ACF Text"). Nunca edites el molde de fábrica. Para texto que cambia por santo, Text Block (el elemento Título no admite datos dinámicos).
d) Prueba con un santo que tenga la sección y otro que no, en incógnito, con la caché purgada y con Ctrl+U: el bloque vacío no debe dejar rastro.
e) Revisa Desktop, Medium y Small.
f) Devuelve la ficha completa con los nombres reales, y la lista de diferencias con el boceto que hayas tenido que aceptar o que no hayas podido hacer.

SI LA FICHA ES "ESPECIAL" O USA ALGO "POR PROBAR": antes de construirla, haz una mini-prueba aparte y anota el resultado. Si no funciona, dilo claramente, no lo inventes.

REGLAS DE SIEMPRE: nada de Clase CSS inventada (solo clases "tt-" ya registradas; si hace falta un efecto que Avada no da, haz un traspaso a Proyecto 11), nada de PHP (eso va a Carlitos, Proyecto 2), y no cambies el nombre de ninguna casilla que ya tenga contenido.

Explícale a Álvaro, con las comparaciones del bloque común, todo lo que no entienda.
```

---

*Para la mayor gloria de Dios · tiritaito.com*

# TIRITAITO.COM — Oraciones y Novenas: estructura
**Registro vivo: qué es la sección Oraciones, cómo están montadas las novenas (cinco formatos, recetas, convención de nombres), las fichas de cada novena, lo contrastado entre los prompts y las fichas, y lo que queda pendiente**
*Para el repositorio: `03-guias-practicas/ORACIONES_ESTRUCTURA.md` · Redactado por Proyecto 2 (Hno C) el 10 de octubre de 2026 · Modelo: `HOMBRES_DE_DIOS_ESTRUCTURA.md` y `PODCAST_ESTRUCTURA.md` · **Documento abierto: se sella cuando lleguen las fichas que faltan (Sección 1)***

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento y cómo leerlo

Responde: **¿cómo está montada la sección Oraciones de la web nueva, y en concreto las novenas, con qué nombres exactos, y qué falta?** Se actualiza cada vez que se cierra una ficha.

| Si necesitas… | Ve a… |
|---|---|
| Cómo está montado el podcast (los canales de Oraciones con feed, las temporadas) | `PODCAST_ESTRUCTURA.md` |
| Cómo está montada la ficha de cada santo (el método del que parten las novenas de días) | `HOMBRES_DE_DIOS_ESTRUCTURA.md` |
| Qué elemento de Avada resuelve una necesidad concreta | `CATALOGO_ELEMENTOS_AVADA.md` |
| **Qué es Oraciones, las novenas, sus fichas y lo pendiente** | **Este documento** |

**Estados:**

| Marcador | Significa |
|---|---|
| ✅ | Comprobado con captura o código revisados en Proyecto 2 |
| ☑ | Declarado por Álvaro o por Carlitos como hecho o funcionando, **sin captura ni resultado revisado por Proyecto 2** |
| 📖 | Documentado por Avada, sin probar en Local |
| 🔲 | Sin probar o sin recibir (en las pruebas se marca «POR PROBAR») |
| ⚠️ | Hecho o decidido, pero con algo sin cerrar |

---

## 1. Estado en una mirada (10 de octubre de 2026)

- **Estructura y decisiones de las novenas: cerradas** (Sección 3.2), salvo las abiertas que se listan.
- **Construcción:** Carlitos indica (10 oct) que **las novenas ya están construidas**. **Los resultados no han llegado:** las dos fichas recibidas (Oración única y Inmaculada) llegaron **con todos sus campos de estado y de pruebas sin rellenar** («[RELLENAR]»). Por eso **todo lo construido consta aquí como ☑ «declarado» y todas las pruebas como 🔲 hasta que llegue el resultado de Álvaro**.
- **Fichas recibidas:** Oración única (San José y Lourdes) e Inmaculada. **Fichas que faltan:** Espíritu Santo, Sagrados Corazones y Pentecostés. Se añadirán cuando lleguen («esta tarde», según Carlitos).
- **Oraciones, el portal y la arquitectura general (O2 u O3):** sin cerrar (Sección 2.2).
- **Conecta cada día** no forma parte de este documento: se estudia aparte.

---

## 2. Oraciones: qué es y qué sabemos

### 2.1 Los siete tipos

Oraciones es una de las tres partes de Biblioteca (`ALCANCE_WEB_NUEVA.md` 4.G). La idea (5 oct) es **una página que lleva a los distintos contenidos**. El contenido de los tipos que llevan podcast es **fijo**: no se les añadirá contenido nuevo (6 oct), pero sus feeds se importan igual.

| Tipo | Qué es | Cómo se resuelve | Estado |
|---|---|---|---|
| **Adoraciones** | Su página corresponde a un podcast, más un vídeo de YouTube aparte, «Adorando en silencio (hora de la brisa)», que hay que presentar bien | Canal del podcast (`PODCAST_ESTRUCTURA.md`) + un vídeo aparte | 🔲 sin feed, sin construir |
| **Rosario** | Podcast de **2 temporadas**: «Rosario» y «Coronilla de la Divina Misericordia». Contenido fijo | Canal del podcast con 2 temporadas (la cuadrícula de temporadas). Sin decidir si van juntas o como entradas separadas | 🔲 |
| **Novenas** | Unas 8 novenas de 9 días | Ver Sección 3 | ☑ construidas (Sección 1) |
| **Oración en lenguas** | Una lista de YouTube con el mismo formato que Tiritaito Music | Depende de Tiritaito Music (primero hay que planear su sección y su lógica interna) | 🔲 aplazada |
| **Vía Crucis** | La misma lógica que Tiritaito Music, con **dos temporadas** que pueden ampliarse con los años (hoy `tt_viacrucis_json_url`) | Depende de Tiritaito Music | 🔲 aplazada |
| **Salmos** | Podcast con los **150 salmos en 6 temporadas de 25 episodios**, en orden (1–25, 26–50…). Contenido fijo | Canal del podcast con 6 temporadas. Será una buena prueba de la cuadrícula de temporadas | 🔲 |
| **Posesión mariana** | Entrada sencilla: una explicación, un vídeo de YouTube y la oración escrita | Página normal de Avada, mismo patrón que la «oración única» de las novenas (Sección 3.5) | 🔲 sin construir |

**Podcast en Oraciones:** Adoraciones, Rosario, Salmos y Novenas son canales del podcast; Oración en lenguas, Vía Crucis y Posesión mariana no (`PODCAST_ESTRUCTURA.md`, Sección 14).

### 2.2 Lo que sigue sin decidirse en Oraciones

| # | Pendiente |
|---|---|
| 1 | **Arquitectura general:** ¿una entrada por tipo (O2) o un tipo de contenido con plantilla única (O3)? Se decide al estructurar el portal. **Para el podcast es indiferente entre ambas**; solo se desaconseja meter los canales en una sola entrada densa |
| 2 | **Portal de Oraciones:** la página que lleva a los siete tipos (su diseño y su estructura) |
| 3 | **Rosario:** ¿las dos temporadas juntas o como entradas separadas? |
| 4 | **Adoraciones:** cómo se presenta el vídeo aparte |
| 5 | Lenguas y Vía Crucis: dependen de la sección de Tiritaito Music |

---

## 3. Novenas

### 3.1 Las ocho novenas y su formato

Cada novena es una página. Hay **cinco formatos, que funcionan como recetas**: una novena nueva elige el que le corresponde.

| Novena | Formato | Estado de la ficha |
|---|---|---|
| Espíritu Santo (Elena Guerra) | **Días de texto** (carrusel de 9 días) | 🔲 ficha pendiente de llegar |
| Sagrados Corazones de Jesús, María y José | **Días de texto** | 🔲 ficha pendiente de llegar |
| San José | **Oración única** | ✅ ficha recibida (sin resultados) |
| Lourdes | **Oración única** (misma plantilla, duplicando la página) | ✅ ficha recibida |
| Santa Teresita | **Podcast**: es la **temporada 1 del feed de Santa Teresita** (el canal del santo) | ☑ según Carlitos |
| Divina Misericordia | **Podcast**: es una **temporada del feed de Novenas** | ☑ según Carlitos |
| Inmaculada | ⚠️ **Cambió de formato** (Sección 3.7, punto 1): de «lista de YouTube» a **días con carrusel**, con foto por día y vídeo pendiente de Tiritaito Music | ✅ ficha recibida (sin resultados) |
| Pentecostés | **Vídeo**: una página con un vídeo y su texto | 🔲 ficha pendiente de llegar |

La entrada «Novenas» de Oraciones es una página con las tarjetas de las 8 novenas hechas a mano: **el contenido es fijo** y añadir una novena es duplicar una columna.

| Formato | Qué se crea para una novena nueva | Peso | Depende de |
|---|---|---|---|
| **Días de texto** | Tipo de contenido propio, grupo de casillas, molde de tarjeta, portada (página) y 9 entradas | El más pesado (como una sección de Hombres de Dios) | — |
| **Oración única** | Una página con imagen, explicación si la hay y la oración | Ligero | — |
| **Podcast** | Nada nuevo: la novena es una temporada de un canal que ya existe y su página es la **página de temporada del podcast**. Solo hace falta una tarjeta en el portal de Novenas que enlace a ella | Ligero | El sistema del podcast |
| **Lista de YouTube** | Una página con la lista | — | Tiritaito Music (sin decidir) |
| **Vídeo** | Una página con un vídeo y su texto | Ligero | — |

### 3.2 Decisiones

| Decisión | Estado | Origen |
|---|---|---|
| Cada novena es una página; hay 5 formatos que funcionan como recetas | Cerrada | Equipo, 7 oct |
| **Novenas de días de texto:** una portada (título, imagen, introducción si la hay; la diseña Carlota con libertad) y debajo un carrusel (Post Cards) con los 9 días, de uno en uno. Un tipo de contenido propio y un molde de tarjeta por cada novena de días | Cerrada | Equipo, 7 oct |
| No hace falta que cada día sea compartible; basta con que lo sea la novena | Cerrada | Equipo, 7 oct |
| El elemento Tabs queda descartado (novenas y Conecta cada día) | Cerrada | Equipo, 7 oct |
| Introducción del Espíritu Santo = introducción a toda la novena (va en la portada). Sagrados Corazones: sin introducción; la oración de la novena va dentro de cada día | Cerrada | Equipo, 7 oct |
| La tarjeta del día tiene alto fijo y su contenido se desplaza hacia abajo dentro de ella | Aceptada; 📖 sin probar | Carlitos, 7 oct |
| Novenas con podcast: solo dos (Santa Teresita y Divina Misericordia); cada una es una **temporada** de su canal y su página es la de temporada | Cerrada | Equipo, 8 oct |
| Radio de esquinas: **15 px** dentro de Avada (norma del 24 sept). El boceto de Carlota dibuja 25 px | Norma de la web; ⚠️ la ficha lo marca como pendiente de Carlota | — |
| Vídeo del día dentro del carrusel: reproductor en la tarjeta, o miniatura que abre una ventana emergente (Lightbox) | **Abierta:** la decide la prueba P3 | — |
| Qué se ve si alguien abre la dirección de un día suelto | **Abierta** (Álvaro solo lo anota) | — |
| Novena del Espíritu Santo con podcast: su temporada 2 del feed de Novenas tiene 9 episodios; incluirlo (audio del día, o lista bajo el carrusel) | **Abierta:** hueco opcional reservado | Carlitos, 8 oct |
| Pentecostés: ¿qué vídeo lleva y hay algo más en la página? | **Abierta** | — |
| Inmaculada: formato días (con carrusel) y vídeo de cada día | **Parcial:** días cerrado según su ficha; el vídeo, aplazado a Tiritaito Music | Ficha recibida el 10 oct |

### 3.3 Cómo se construye una novena de días

**Lo que ve el visitante:** la portada arriba y, debajo, **una tarjeta alta con el día 1**; con flechas y puntos pasa al día siguiente; dentro de la tarjeta el texto se desplaza hacia abajo si no cabe.

**Lo que se construye, por cada novena de días:**
1. **Un tipo de contenido propio** (por ejemplo `novena_inmaculada`): 9 entradas, una por día. **Público** (Post Cards no ve los tipos que no lo son), sin archivo.
2. **Un grupo de casillas** (ACF) con lo que cambia cada día.
3. **Un molde de tarjeta** («el molde del día»): lleva escrito lo **fijo** y huecos para lo que **cambia**. Se diseña una vez y los 9 días salen iguales.
4. **Una página portada** (página normal de Avada) con diseño libre y, debajo, **un Post Cards en modo carrusel** (1 tarjeta visible, flechas y puntos, sin reproducción automática) con Order By = casilla del número del día, numérico y ascendente.

**Lo que NO es:** no hay un Layout para los días (el molde hace ese papel), ni pestañas, ni una página por día.

**Etiqueta «DÍA N»:** no hay contador nativo «Día N de 9»; cada tarjeta lleva su propia etiqueta, leída de la casilla del número.

**Comprobado y sin comprobar:**

| Pieza | Estado |
|---|---|
| Post Cards sobre un tipo propio, ordenado por un campo | ✅ (Novedades, por fecha) |
| Casillas sencillas leídas con Contenido Dinámico dentro de una tarjeta | ✅ (Novedades) |
| Parte opcional dentro de una tarjeta que desaparece si su casilla está vacía | ☑ (autor de «Sus palabras») |
| Vídeo de YouTube desde una casilla, con Video Facade | ☑ (informe de pruebas del podcast) |
| Vídeo en Lightbox desde una casilla | ☑ (Vídeos de Hombres de Dios) |
| Post Cards en carrusel con 1 tarjeta visible, flechas, puntos, sin autoplay; deslizar con el dedo en móvil real | 📖 / 🔲 |
| Alto fijo con scroll interno (altura mínima = máxima + overflow): ¿acepta `vh`? ¿se llega con teclado? ¿móvil? | 📖 / 🔲 |
| Vídeo dentro del carrusel: ¿sigue sonando al pasar de día? (si sí: miniatura + Lightbox) | 🔲 |
| Orden por un número (1, 2, 10) en vez de por fecha | 🔲 |
| Tarjeta de cita: elemento Blockquote o columna con borde y fondo | 🔲 |

**Límites conocidos de Avada:** el punto activo del carrusel solo se distingue por color; no hay contador «Día N de 9»; el elemento YouTube no tiene esquinas redondeadas propias.

### 3.4 Convención de nombres

| Qué | Convención |
|---|---|
| Tipo de contenido (uno por novena de días) | `novena_<tema>` (`novena_espiritu_santo`, `novena_sagrados_corazones`, `novena_inmaculada`). Público, sin archivo |
| Prefijo de casillas | Tres letras y guion bajo: `nes_` (Espíritu Santo), `nsc_` (Sagrados Corazones), `nin_` (Inmaculada) |
| Casilla | `<prefijo>_<apartado>_<campo>`, en singular, minúsculas, sin tildes ni eñes. El número del día: `<prefijo>_dia`. El vídeo: `<prefijo>_video` |
| Dirección de YouTube | Casilla de tipo **texto** |
| Grupo de casillas y molde | «TT — Novena <Tema> — Día» (mismo nombre para los dos) |

**Reglas de oro:** no se renombra una casilla con contenido; no se convierte una casilla sencilla en lista; los nombres de casilla se escriben a mano; **el orden de los apartados de un día es el orden en que se reza** y no se cambia sin permiso; **los textos de las oraciones son sagrados**: no se resumen, reescriben ni corrigen por cuenta propia (para pruebas, texto de relleno); cada parte del molde es autónoma (su título va dentro; **prohibido alternar fondos por posición**); nada de Clase CSS inventada.

**Propuesta de casillas por día** (pendiente de sellar con sus fichas):

| Novena | Casillas por día |
|---|---|
| Espíritu Santo (`nes_`) | `nes_dia`, `nes_video`, `nes_meditacion_titulo`, `nes_meditacion_texto`, `nes_momento_personal` |
| Sagrados Corazones (`nsc_`) | `nsc_dia`, `nsc_video`, `nsc_palabra_texto`, `nsc_palabra_cita`, `nsc_bloque_etiqueta`, `nsc_bloque_texto`, `nsc_bloque_autor` |
| Inmaculada (`nin_`) | `nin_dia` (creada); a la espera de decisión: `nin_imagen`, `nin_video`, casilla de texto opcional |

### 3.5 Fichas

*Las fichas se reproducen tal como llegaron, con los campos sin rellenar marcados como pendientes. Cuando llegue el resultado de Álvaro, se sustituye cada «PENDIENTE» por su valor.*

#### Ficha — Oración única (San José y Lourdes)

```
FICHA DE NOVENA — ORACIÓN ÚNICA (San José, Lourdes)
- Novena: San José y Lourdes (misma plantilla; la segunda se hace duplicando la página)
- Formato: página normal de Avada. Sin tipo de contenido, sin casillas ACF, sin carrusel
- Clave del tipo de contenido y prefijo de casillas: no aplica
- Portada (título, imagen, introducción): Container con foto de fondo + capa oscura rgba(20,10,10,.55). Padding 56/40/48/40 escritorio · 34/12/28/12 móvil. Title (Yeah Papa, blanco, centrado, 62px / 40px móvil). Introducción opcional (Helvetica Neue 15px, blanca, centrada)
- Fijo, escrito dentro de la tarjeta (por orden): Imagen (opcional) · Title "Explicación" + texto (opcional) · Title "Oración" + texto (obligatorio)
- Casillas por día: no aplica
- Desaparece si: no aplica; lo opcional se resuelve no añadiendo el bloque
- Elementos de Avada: Container, Title, Text Block, Image, columna "tarjeta"
- Valores concretos:
  · Tarjeta: ancho máx. 760px centrada, fondo rgba(255,255,255,.74), borde 1px blanco semitransparente, sombra suave, desenfoque 22px (Filtros de Fondo), padding 30/34 escritorio · 18/16 móvil, altura automática, sin scroll interno
  · Radio: 15px (el boceto dibuja 25px; decisión pendiente de Carlota)
  · Títulos de parte: Yeah Papa 28px, Color 6. Explicación: Helvetica Neue 16px, interlineado 1.7, Color 7. Oración: Helvetica Neue 18px, interlineado 1.8, Color 8
  · Imagen: ancho 100%, 16:9, radio 15px, margen inferior 24px
- Estado de construcción: PENDIENTE (Carlitos indica que está hecha; sin resultado de Álvaro)
- Container guardado en la Biblioteca de Avada: sí. PENDIENTE: como Guardado (no global) o Global
- POR PROBAR (resultado):
  · Filtros de Fondo disponibles en Local: PENDIENTE
  · Legibilidad del título blanco con la foto real: PENDIENTE
  · Tarjeta de altura automática en móvil real: PENDIENTE
  · Desktop / Medium / Small: PENDIENTE
- Diferencias con el boceto: PENDIENTE
- Textos: de relleno. Las oraciones reales las carga el equipo
```

**Nota de Proyecto 2 sobre «Container guardado»:** si el contenedor se guarda como **Global**, un cambio de texto en una novena cambiaría también la otra (un elemento Global sincroniza el contenido, `GUIA_AVADA_LOCAL.md` 8.1). Como San José y Lourdes tienen textos distintos, **debe guardarse como Guardado (no global)**.

#### Ficha — Inmaculada (novena de días, un vídeo o foto por día)

```
FICHA DE NOVENA — INMACULADA (novena de días, un vídeo/foto por día)
- Novena: Inmaculada
- Clave del tipo de contenido y prefijo de casillas: novena_inmaculada · nin_
  · Público, sin archivo. Dirección real: PENDIENTE
  · Grupo de casillas: "TT — Novena Inmaculada — Día", ubicación = tipo de contenido novena_inmaculada
- Portada: Container con foto de fondo + capa oscura rgba(20,10,10,.55). Padding 56/40/36/40 escritorio · 34/12/24/12 móvil. Title "Novena a la Inmaculada" (Yeah Papa, blanco, centrado, 62px / 40px móvil) + introducción opcional. Debajo, Post Cards en carrusel
- Fijo, escrito dentro del molde "TT — Novena Inmaculada — Día" (por orden):
  1. Cabecera fija "DÍA N" (Text Block: "DÍA " + dato de nin_dia). Yeah Papa 44px, rojo. Relleno 16/34/8/34 escritorio · 12/16/6/16 móvil. Línea fina debajo
  2. Zona con scroll interno (altura máxima + overflow auto). Relleno 20/34/40/34 escritorio · 16/16/40/16 móvil
  3. Foto (Image, 16:9, radio 15px, ancho máx. 560px y centrada en escritorio)
  4. Texto opcional (Text Block): POR DECIDIR si es fijo o lleva casilla
- Casillas por día (nombre interno exacto · clase · para qué sirve · opcional):
  · nin_dia · Número · número del día 1–9, y base del orden y de la etiqueta "DÍA N" · obligatoria
  · Nombre real guardado por ACF: PENDIENTE
  · No creadas, a la espera de decisión: nin_imagen (si la foto cambia cada día), nin_video (cuando se decida el vídeo), casilla de texto opcional
- Desaparece si: no hay partes con interruptor por ahora (pendiente de si el texto opcional lleva casilla)
- Elementos de Avada: Post Cards (Content Source = Posts, Post Type = novena_inmaculada, Order By = Custom Field nin_dia numérico ascendente, Layout Carousel, 1 tarjeta visible, flechas y puntos, sin autoplay, número de entradas ≥ 9), molde propio de tarjeta, Text Block, Image
- Valores concretos:
  · Tarjeta: ancho máx. 840px, alto 480px escritorio · 560px móvil, fondo rgba(255,255,255,.74), borde 1px blanco semitransparente, sombra suave, desenfoque 22px, radio 15px (boceto 25px, decisión pendiente)
  · Flechas redondas 46px, puntos 10px, activo en rojo, colocados debajo de la tarjeta
- Días de prueba: Día 1 (nin_dia = 1), Día 2 (nin_dia = 2), Día 3 (nin_dia = 10) para comprobar el orden
- POR PROBAR (resultado):
  · P1 carrusel 1 tarjeta + deslizar en móvil real: PENDIENTE
  · P2 scroll interno, cabecera quieta, unidades vh, teclado: PENDIENTE
  · P4 orden numérico (1, 2, 10): PENDIENTE
  · P6 qué se ve al abrir la dirección de un día suelto (solo anotado): PENDIENTE
  · Flechas y puntos debajo de la tarjeta: PENDIENTE
  · Filtros de Fondo en Local: PENDIENTE
  · Desktop / Medium / Small: PENDIENTE
- Diferencias con el boceto: PENDIENTE
- Preguntas abiertas para Carlota:
  1. ¿La foto es la misma los 9 días (escrita en el molde, sin casilla) o cambia cada día (nin_imagen)?
  2. El texto opcional: ¿fijo en el molde o con casilla?
  3. Radio 15px o 25px
- Pendiente de decidir (depende de Tiritaito Music): el vídeo de cada día, que ocuparía el lugar de la foto con nin_video
```

#### Fichas pendientes de llegar

- **Espíritu Santo** (`novena_espiritu_santo` · `nes_`): portada con introducción de Elena Guerra y León XIII; 10 apartados por día (inventario de la Sección 3.6).
- **Sagrados Corazones** (`novena_sagrados_corazones` · `nsc_`): portada sin introducción; 11 apartados por día.
- **Pentecostés** (vídeo): página con un vídeo y su texto.

### 3.6 Qué lleva cada día (inventario de los textos de la web vieja)

**Piezas visuales que usa hoy el diseño** (Carlota puede rediseñarlas; lo que no puede es quitar los apartados): título de apartado con línea fina; texto; tarjeta de cita (fondo rosado suave, borde rojo a la izquierda, cursiva, comillas grandes, atribución en rojo a la derecha); vídeo; tarjeta de canción; separador «Haz silencio y medita»; puntos de día, etiqueta «DÍA N» y flechas.

**Espíritu Santo (Elena Guerra).** Portada: título, imagen e introducción (historia de la beata Elena Guerra y León XIII), una sola vez. Cada día, en este orden:

| # | Apartado | ¿Cambia cada día? |
|---|---|---|
| 1 | Vídeo del día | Cambia |
| 2 | Señal de la cruz | Fijo |
| 3 | Separador «Haz silencio y medita» | Fijo |
| 4 | Oración antes de la meditación | Fijo |
| 5 | Separador «Haz silencio y medita» | Fijo |
| 6 | Meditación: título del día + texto largo (el autor, «Elena Guerra», es fijo) | Cambia |
| 7 | Momento para la meditación personal (1–2 párrafos) | Cambia |
| 8 | Oración final + «Padre Nuestro, Ave María, Gloria» | Fijo |
| 9 | Himno «Ven Creador Espíritu amoroso» (con su nota de las primeras vísperas de Pentecostés) | Fijo |
| 10 | Frase final para concluir la novena (con su nota sobre la señal de la cruz) | Fijo |

**Posible apartado nuevo (por decidir): audio del día.** El feed de Novenas tiene esta novena como temporada 2, con 9 episodios de 3:46 a 5:05 numerados del 1 al 9 (el día N es el episodio N). Formas de incluirlo, de menos a más acoplada: (1) bajo el carrusel, la lista de los 9 episodios de esa temporada; (2) una casilla opcional por día con la dirección del audio; (3) enlazar cada día con su episodio importado. Se reserva un hueco opcional; añadirlo después es una casilla y un bloque opcional, sin rehacer nada.

**Sagrados Corazones de Jesús, María y José.** Portada: título e imagen (con el título dibujado); sin introducción. Cada día, en este orden:

| # | Apartado | ¿Cambia cada día? |
|---|---|---|
| 1 | Vídeo del día | Cambia |
| 2 | Señal de la cruz | Fijo |
| 3 | Palabra de Dios: cita bíblica (tarjeta de cita) + su referencia | Cambia |
| 4 | Separador «Haz silencio y medita» | Fijo |
| 5 | Bloque del día: etiqueta que varía (Palabra de Jesús, Oración, Reflexión o Mensaje) + texto + atribución | Cambia |
| 6 | Separador | Fijo |
| 7 | Oración de la novena | Fijo |
| 8 | Separador | Fijo |
| 9 | Canción «Enséñennos a amar» (hoy, tarjeta del módulo Tiritaito Music) | Fijo (⚠️ por confirmar que es la misma los 9 días) |
| 10 | «Pedimos por la paz en esta tierra»: la intención | Fijo |
| 11 | Padre Nuestro, Ave María, Gloria · «Sagrado Corazón de Jesús, en ti confío y espero» · «Terminamos haciendo la señal de la Cruz» | Fijo |

**Lo que se desprende:** casi todo el texto de cada día es idéntico los 9 días: lo fijo se escribe **una sola vez dentro del molde**; lo que cambia son de 4 a 6 casillas por día. Cada novena de días lleva su propio tipo de contenido y su propio molde, porque sus apartados y su orden son distintos.

### 3.7 Contraste entre los prompts y las fichas (10 oct)

| # | Qué | Lo que se encuentra | Qué se hace |
|---|---|---|---|
| 1 | **Formato de la Inmaculada** | Los prompts y las decisiones del 7 oct la dan como **«lista de YouTube», aplazada** hasta decidir Tiritaito Music. Su ficha la describe como **novena de días con carrusel** (`novena_inmaculada`, `nin_`), con foto por día y el vídeo pendiente de Tiritaito Music | ⚠️ Se documenta el formato de la ficha (más reciente) y **se pregunta a Carlitos si lo confirma** (Sección 4, pregunta 1) |
| 2 | Radio de las esquinas | Ambas fichas usan 15 px y dicen que el boceto dibuja 25 px | La norma dentro de Avada es **15 px** (`CATALOGO_ELEMENTOS_AVADA.md` 13.1); se sigue 15 px |
| 3 | Resultados de las pruebas | Las dos fichas llegaron con estado y pruebas sin rellenar | Todo ☑/🔲 hasta recibir el resultado de Álvaro |
| 4 | Versión de Avada | Los prompts dicen 7.16.1; el registro de Álvaro del 9 oct, 7.16.2 (el panel lo marca así) | Se anota 7.16.2 |
| 5 | **Filtros de Fondo** (el desenfoque de la tarjeta) | Ambas fichas los usan; `CATALOGO_ELEMENTOS_AVADA.md` 5 bis pedía comprobar antes que la versión instalada los incluye | 🔲 Se cierra con el resultado de la ficha |
| 6 | Contenedor guardado | La ficha de Oración única deja abierto Guardado o Global | Debe ser **Guardado** (Sección 3.5) |
| 7 | Verificación de los feeds | Los prompts dicen que las novenas con podcast están «verificadas contra los dos feeds (Sección 5 bis)», pero **esa sección no existe en el documento** | ☑ No se puede verificar desde aquí; se pide confirmar los feeds (Sección 4, pregunta 2) |
| 8 | Qué canal es cada novena con podcast | Los prompts proponían que los canales de novenas pasaran a ser «Teresita y Misericordia». Los feeds indicados dicen otra cosa: **Teresita es la temporada 1 del feed del santo** y **Divina Misericordia y Espíritu Santo son temporadas del feed «Novenas»** (que sigue siendo un solo canal) | Se documenta lo segundo (Sección 3.1); ☑ por confirmar |
| 9 | Nombre del registro | Los prompts anuncian un `NOVENAS_ESTRUCTURA.md` | Sustituido por este documento (incluye también Oraciones) |
| 10 | Tipo de las fichas | La ficha de Oración única añade campos que el formato no tiene («Estado de construcción», «Container guardado») | Se conservan: son útiles; se proponen para el formato (Sección 5) |
| 11 | Pruebas P3 y P5 (vídeo y cita) | Ninguna de las dos fichas recibidas las usa | Se evaluarán con las fichas del Espíritu Santo y de los Sagrados Corazones |

### 3.8 Contenido por revisar antes de cargarlo (lo corrige el equipo editorial, no Álvaro)

| Dónde | Qué |
|---|---|
| Espíritu Santo, días 2 a 9 | El título de la meditación aparece como «[TÍTULO DEL DÍA N]» sin rellenar (8 de 9 días) |
| Espíritu Santo | Títulos sin tilde: «DIA 1», «MOMENTO PARA LA MEDITACION PERSONAL» |
| Sagrados Corazones | Todo el texto sin tildes ni eñes («Senor», «Ensenennos a amar» → «Enséñennos a amar») |
| Sagrados Corazones, día 7 | La palabra de Dios sobre la vid cita «Jn 15, 15»; el texto es de Jn 15, 5 (probable errata: comprobar) |
| Sagrados Corazones | Confirmar que la canción es la misma los 9 días y si sigue como tarjeta de Tiritaito Music o pasa a un vídeo normal |

### 3.9 Cómo añadir una novena nueva

1. **Elegir su formato** (Sección 3.1).
2. **Días de texto:** crear el tipo `novena_<tema>`, el grupo «TT — Novena <Tema> — Día» (prefijo de tres letras), el molde de la tarjeta y la portada con su Post Cards en carrusel (Sección 3.3); dos días de prueba con texto de relleno, y el Día 3 con el número 10 para comprobar el orden.
3. **Oración única o Vídeo:** duplicar la página existente y cambiar textos e imágenes; el contenedor de la ficha debe ser Guardado (no global).
4. **Podcast:** si la novena es una temporada de un canal, no hay nada que construir; falta solo su tarjeta en el portal de Novenas, que enlaza a la página de esa temporada.
5. **Lista de YouTube:** esperar a Tiritaito Music.
6. Rellenar la ficha (plantilla de la Sección 5) y añadirla a este documento.

---

## 4. Preguntas abiertas

| # | Pregunta | Bloquea a |
|---|---|---|
| 1 | **Inmaculada:** ¿se confirma el formato de su ficha (novena de días con carrusel, foto por día y vídeo después) en lugar de «lista de YouTube»? ¿Su lista tiene 9 vídeos, uno por día? | Su ficha y el formato «Lista de YouTube» |
| 2 | ¿Se confirman los feeds? **Santa Teresita:** `https://anchor.fm/s/1100f52d8/podcast/rss` (novena = temporada 1) · **Novenas:** `https://anchor.fm/s/11070301c/podcast/rss` (Divina Misericordia = temporada 1; Espíritu Santo = temporada 2, 9 episodios). **Proyecto 2 no ha podido leerlos** | Formato podcast; canales del podcast |
| 3 | ¿La página de temporada estándar del podcast sirve para una novena, o necesita su propio diseño? | Novenas con podcast |
| 4 | Espíritu Santo: ¿se incluye el podcast (audio del día, o lista bajo el carrusel)? | Hueco opcional |
| 5 | Pentecostés: ¿qué vídeo lleva y hay algo más? | Su ficha |
| 6 | ¿Qué se ve si alguien abre la dirección de un día suelto? (P6) | Decisión de equipo |
| 7 | Inmaculada: ¿la foto es la misma los 9 días o cambia (`nin_imagen`)? ¿El texto opcional es fijo o con casilla? | Su molde |
| 8 | Sagrados Corazones: ¿la canción es la misma los 9 días y sigue como tarjeta de Tiritaito Music? | Su ficha |
| 9 | Resultados de Álvaro: estado de cada novena y resultado de P1–P6, Filtros de Fondo y Desktop / Medium / Small | **Sellar este documento** |
| 10 | Oraciones: arquitectura (O2 u O3), portal, Rosario (¿juntas o separadas?) y Adoraciones (vídeo aparte) | Toda la sección Oraciones |

---

## 5. Plantilla de ficha (idioma común entre Carlota y Álvaro)

```
FICHA DE NOVENA
- Novena:
- Formato: (días de texto / oración única / podcast / lista de YouTube / vídeo)
- Clave del tipo de contenido y prefijo de casillas:
- Portada (título, imagen, introducción: qué hay):
- Fijo, escrito dentro del molde (por orden):
- Casillas por día (nombre interno exacto · clase · para qué sirve · opcional sí/no):
- Desaparece si: (qué casilla vacía apaga cada parte opcional)
- Elementos de Avada sugeridos para cada parte:
- Valores concretos (tamaños, colores, espaciados, alto de la tarjeta en escritorio y en móvil):
- Estado de construcción: (hecha / pendiente)
- Container guardado en la Biblioteca (solo oración única y vídeo): Guardado (no global)
- POR PROBAR (resultado):
- Diferencias con el boceto:
- Preguntas abiertas:
```

---

*Para la mayor gloria de Dios · tiritaito.com*

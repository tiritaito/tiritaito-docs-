# TIRITAITO.COM — Tiritaito Music en la Web Nueva
**Cómo presentar la música, qué construye Avada de forma nativa, qué queda como código de Carlitos, y el brief para que Carlota haga el boceto**
*Investigación de Proyecto 2 (Hno C) — 16 de septiembre de 2026*
*Fuentes: código real del módulo de la web vieja (compartido en sesión) · documentación oficial de avada.com verificada en esta sesión · referencia visual hakuna.org/hakuna-group-music consultada en directo · `avada-global-options.json` (export real, 14 agosto 2026)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde tres preguntas encadenadas:

1. **¿Qué es hoy Tiritaito Music, técnicamente?** (diagnóstico del módulo real, no de lo que creemos recordar)
2. **¿Cuánto de eso puede hacer Avada de forma nativa, con las reglas del 6 de septiembre de 2026?**
3. **¿Qué le entregamos a Carlota para que haga el boceto sin adivinar nada?**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Qué elemento de Avada resuelve una necesidad de contenido en general | `CATALOGO_ELEMENTOS_AVADA.md` |
| Dónde vive cada pieza de contenido ya decidida | `METODOLOGIA_CONSTRUCCION.md` |
| Qué secciones tiene la web y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| Quién construye qué desde el 6 de septiembre de 2026 | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.3 y 2.4 |
| **Cómo se presenta y se construye la entrada de Tiritaito Music en concreto** | **Este documento** |

⚠️ **Este documento no decide nada.** Propone, mide y pone precio a cada opción. Las tres
decisiones reales (dónde viven los datos, cuánta interacción queremos, qué dirección visual)
son de Carlitos y Hna C — están recogidas al final como preguntas abiertas.

---

## 1. Dónde encaja esto en lo ya decidido

`ALCANCE_WEB_NUEVA.md` Sección 4.E: **Tiritaito Music es una de las 4 entradas propias de la
página contenedora "Tiritaito"** (junto a Rincón de Nico, Charlas de la Biblia y Ejército de
Intercesores). No es una página contenedora, es una entrada.

Dos cosas ya escritas que este documento toca directamente:

| Documento | Lo que dice hoy | Lo que esta sesión aporta |
|---|---|---|
| `METODOLOGIA_CONSTRUCCION.md` Sección 2 | "Tiritaito Music (con cola) `.mp-*` — **Evaluar si `[tt_podcast]` cubre el caso;** si necesita cola/playlist, mantenerlo pero como snippet global reutilizable" | ✅ **Evaluación resuelta:** el módulo real tiene cola, búsqueda, mini-reproductor persistente, compartir con enlace profundo y pestañas por playlist. `[tt_podcast]` (lector de RSS con reproductor de audio) **no cubre nada de eso**. La fila puede cerrarse |
| `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 1 y 2.4 | "el consumo de JSON de Seminarios/Música/Vía Crucis" se cita literalmente como el ejemplo de funcionalidad que Avada no tiene de ninguna manera, y que **diseña y construye Carlitos** | Sigue siendo cierto **solo si mantenemos el JSON**. Si los datos pasan a un CPT, la mayor parte de la página vuelve a ser nativa. Ese es el verdadero punto de decisión (Sección 5) |

---

## 2. Qué es hoy Tiritaito Music — diagnóstico del código real

*Verificado línea por línea contra el archivo compartido en esta sesión, no de memoria.*

### 2.1 Qué hace, funcionalmente

| Función | Detalle |
|---|---|
| Fuente de datos | Un único JSON (`music-data.json`) en la Biblioteca de Medios, generado desde Tiritaito for Creators (pestaña Recursos → Generadores YouTube → Música), que consulta la YouTube Data API v3 con una clave guardada en el `localStorage` del navegador del editor |
| Estructura del JSON | `{ playlists: [ { name, tracks: [ { id, title, thumb } ] } ] }` |
| Pestañas | Una por playlist (`name`), con animación de "ripple" al pulsar |
| Listado | Tarjetas con miniatura, número de orden, título, ecualizador animado en la pista activa |
| Reproducción | Iframe de YouTube con la JS API activada (`enablejsapi=1`), escuchando `postMessage` para detectar fin de vídeo y encadenar la siguiente |
| Cola | Añadir/quitar/vaciar; en móvil, deslizando la tarjeta hacia la izquierda |
| Mini-reproductor | Barra fija inferior, expandible a panel completo con carátula, controles y cola; arrastrable para cerrar |
| Búsqueda | Filtra por título **solo dentro de la pestaña activa** |
| Compartir | WhatsApp, Telegram, X, Facebook + copiar enlace, con enlace profundo `?mp_play=<videoId>` que arranca esa canción al abrir la página |
| Aleatorio | Funcional |
| Ordenación | Función `sortKey()` propia: detecta "introducción/prólogo" → primero, números, ordinales en español, romanos, días de la semana, meses, "conclusión/epílogo" → último |
| Filtrado de privados | Una llamada `fetch` a `youtube.com/oembed` **por cada pista** para descartar vídeos privados/ocultos y deduplicar |

**Es una aplicación completa, no un bloque de contenido.** Ese es el dato de partida: son
~1.100 líneas entre CSS, HTML y JS, con estado, animaciones, gestos táctiles y un contrato
de datos propio.

### 2.2 Lo que está bien y no conviene tirar

- El **modelo de datos es limpio y suficiente**: `id` + `title` + `thumb`, agrupados por playlist. Cualquier arquitectura futura puede partir de ahí.
- El **ordenador de pistas (`sortKey`)** resuelve un problema real (YouTube no ordena por criterio litúrgico ni por ordinales en español) y es reutilizable tal cual, esté donde esté.
- El **enlace profundo `?mp_play=`** es una buena idea de producto: compartir una canción concreta y que se abra reproduciéndose. Merece sobrevivir a cualquier rediseño.
- El **flujo App → WordPress → web** ya está montado y probado en otra pieza (Novedades), y el equipo tiene la experiencia documentada (`CUADERNO_DEL_CONSTRUCTOR.md`, entrada ✅ #1).

### 2.3 Lo que hay que corregir sí o sí antes de reutilizarlo

Doce hallazgos, ordenados por gravedad. Todos verificados en el código compartido.

| # | Hallazgo | Por qué importa | Gravedad |
|---|---|---|---|
| 1 | **`DATA_URL` está escrita a mano en el código**: apunta a `.../uploads/2026/09/music-data.json`. El módulo **no lee** `tt_youtube_json_url` del endpoint, que es justo la clave que la app actualiza al pulsar "Generar y publicar" | Cada regeneración desde Creators sube un archivo nuevo (carpeta del mes en curso, y WordPress renombra si el nombre ya existe) y actualiza `tt_youtube_json_url` — pero la web sigue sirviendo el archivo viejo. El circuito app→web **está roto en el último tramo**, salvo que alguien edite el snippet a mano cada vez | 🔴 Alta |
| 2 | **Una petición a `youtube.com/oembed` por pista, en cada carga de página**, y la interfaz no se muestra hasta que **todas** las playlists terminan (`if(++done===total)`) | Con 5 playlists × 30 canciones son 150 peticiones a un tercero antes del primer pintado. Además el `.catch(){}` se traga los fallos: si la red falla o YouTube limita, **la canción desaparece del listado sin aviso**. Es un problema de corrección, no solo de velocidad | 🔴 Alta |
| 3 | El botón **"Autoplay" no hace nada**: `togAuto()` cambia una clase y muestra un aviso, pero ningún punto del código lee ese estado. El reproductor siempre encadena la siguiente | Un control que miente al usuario | 🟠 Media |
| 4 | El módulo **define variables en `:root`** (`--r`, `--s`, `--t`, `--sh`, `--fn`…) en vez de usar `var(--tt-*)` | Viola directamente `00_CORE.md` Sección 7 ("los módulos NUNCA redefinen variables del Global"). Nombres de una y dos letras = riesgo real de colisión con cualquier otro módulo | 🟠 Media |
| 5 | **Deriva de color**: `--rd:#9b2c2c` no es `--tt-red-d` (`#A33B3B`); `--rs`/`--rg` están construidos sobre `rgba(204,0,0,…)`, que es `#CC0000`, no el rojo de marca `#BF4646` | El módulo no se ve exactamente del color de Tiritaito | 🟠 Media |
| 6 | **No usa "Yeah Papa" en ningún sitio.** El titular "Tiritaito Music" va en `SF Pro Display` peso 800 | Es la desviación de ADN visual más visible de todo el módulo | 🟠 Media |
| 7 | **Radios fuera de token**: 24 / 22 / 20 / 14 / 12 / 10 / 8 px. Los tokens son 25 / 14 / 8 (+10 pendiente de formalizar) | Coherencia visual | 🟡 Baja |
| 8 | El contador del hero (`N canciones · M categorías`) usa `allT.length`, que en ese momento es **solo la primera playlist**, no el total | El dato que se muestra es falso | 🟡 Baja |
| 9 | La búsqueda dice "Buscar canciones…" pero **solo busca dentro de la pestaña activa** | Expectativa incumplida | 🟡 Baja |
| 10 | `PAGE_URL` y la URL del logo están escritas a mano apuntando a **producción** (`www.tiritaito.com/blog/...`) | Al mover a Local/web nueva, los enlaces de compartir y el logo se rompen | 🟠 Media |
| 11 | **`z-index` sin contrato con Avada**: mini-reproductor 1000, fondo oscuro 999, hoja de compartir 9999, aviso 9998. Además `body.mp-lock{position:fixed}` bloquea el scroll | El header sticky está activo (`header_sticky: 1` en el export) y el Off-Canvas del menú móvil se recomienda subir a 9999 (`CATALOGO_ELEMENTOS_AVADA.md` Sección 3). Hay que decidir quién gana cuando el menú se abre con el reproductor desplegado | 🟠 Media |
| 12 | `!important` en prácticamente cada declaración, más un reset `.mp, .mp *` que fuerza márgenes, bordes y alineación | Es defensivo y está bien acotado, pero tiene una consecuencia estructural: **el módulo es inmune a Global Options.** Ningún cambio de color, tipografía o radio hecho por Álvaro desde el panel de Avada le afectará nunca | 🔴 Estructural |

🔲 **Un punto que no puedo confirmar desde aquí:** la función `esc()` del módulo, tal como
llegó a esta sesión, muestra sustituciones que parecen no hacer nada (`&` → `&`). Es
**muy probable** que sea un artefacto de cómo se decodificó el archivo al pegarlo, no un
fallo real — la app de Creators usa un `escapeHtml()` basado en el DOM, que sí es correcto.
**Hay que mirarlo en el archivo real del repositorio antes de darlo por bueno o por roto.**

### 2.4 El hallazgo #12, explicado para el equipo

Es el más importante de todos y no es un bug — es una consecuencia de diseño:

> Mientras Tiritaito Music sea un módulo de código autocontenido, **ningún cambio visual que
> pida Carlota lo puede aplicar Álvaro.** Todo, hasta cambiar un color o un tamaño de letra,
> vuelve a Carlitos y a una nueva versión del snippet.

Eso no es necesariamente malo — es exactamente el reparto que fija
`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.4 para lo que Avada no resuelve. Pero hay
que entrar en ello **sabiéndolo**, y hay que decidir cuánta superficie de la página queda
bajo esa regla. Ese es el hilo de todo el documento.

---

## 3. La referencia: cómo lo hace Hakuna Group Music

*Consultada en directo en `hakuna.org/hakuna-group-music` durante esta sesión.*

### 3.1 Su estructura real, de arriba abajo

```
┌─────────────────────────────────────────────────────┐
│  HERO — vídeo de fondo + logo del grupo (PNG)        │
│  + una frase corta de misión                         │
├─────────────────────────────────────────────────────┤
│  BANNER DE CAMPAÑA — ilustraciones + CTA             │
│  ("La Gira 2027 · Ver fechas y entradas →")          │
├─────────────────────────────────────────────────────┤
│  ÍNDICE DE ANCLAS  ✦ Nuestra música  ✦ Cancionero    │
│  ✦ Fotos y vídeos  ✦ La historia detrás  ✦ …        │
├─────────────────────────────────────────────────────┤
│  NUESTRA MÚSICA                                      │
│   · Últimos lanzamientos → 4 portadas enlazadas      │
│   · Top canciones → widget embebido de Spotify       │
│   · Discografía → carrusel de portadas con flechas   │
├─────────────────────────────────────────────────────┤
│  CANCIONERO — banner + botón DESCARGAR (PDF acordes) │
├─────────────────────────────────────────────────────┤
│  FOTOS Y VÍDEOS — galería + 3 conciertos completos   │
├─────────────────────────────────────────────────────┤
│  LA HISTORIA DETRÁS                                  │
│   · Reels: "¿Cómo nació?" por canción                │
│   · "La Pecera": Track 1 / Track 2 / Track 3,        │
│     cada uno = embed + título + subtítulo + enlace   │
├─────────────────────────────────────────────────────┤
│  ¿QUIÉNES SOMOS? — carrusel de fotos + 2 textos      │
├─────────────────────────────────────────────────────┤
│  ACTUALIDAD — tarjetas de prensa                     │
├─────────────────────────────────────────────────────┤
│  PRÓXIMOS CONCIERTOS — fechas + entradas             │
└─────────────────────────────────────────────────────┘
```

### 3.2 Las cuatro lecciones que sí valen para nosotros

**1. No construyen reproductor propio.** Delegan la reproducción en Spotify y en YouTube.
Su página es un **escaparate**, no una aplicación. Todo el esfuerzo está en la presentación,
no en la mecánica de reproducir. Esto es lo más importante de toda la referencia, y va en
dirección contraria a lo que hace hoy Tiritaito Music.

**2. El índice de anclas al principio.** Una página larga deja de dar miedo cuando lo
primero que ves es la lista de lo que hay dentro. Es barato, es nativo en Avada (Sección 4)
y encaja con el criterio de altura acotada de `GUIA_AVADA_LOCAL.md` Sección 8.4-bis: la
página puede ser larga si se navega bien.

**3. El patrón "La Pecera" es exactamente nuestro caso.** Un bloque con título de sesión y,
debajo, N piezas, cada una con: vídeo + título de pista + subtítulo de la sesión + enlace
"Ver en YouTube". Eso es, literalmente, una playlist de Tiritaito Music renderizada sin
reproductor propio.

**4. Separan "escuchar" de "conocer".** Hay una sección para la música y otra distinta para
la historia detrás de cada canción. Tiritaito tiene material para eso (letras, para qué se
escribió, en qué momento del año se canta) y hoy no hay ningún sitio donde ponerlo.

### 3.3 Lo que NO conviene copiar

| Lo suyo | Por qué no |
|---|---|
| Vídeo `.mov` de fondo en el hero | Formato no óptimo para web y peso alto; si se quiere vídeo, se hace autoalojado, mudo, corto y en `.mp4`/`.webm` con el elemento Video de Avada. Y choca con el criterio de rendimiento ya fijado en Fase 4 |
| Embeds de Instagram | Dependencia de un tercero que ya hemos apagado por privacidad (`privacy_embed_types` incluye `instagram`), y visualmente rompe el ADN |
| Densidad de secciones (10 bloques) | Hakuna es una organización con gira, tienda, prensa y película. Tiritaito Music es **una entrada dentro de la página Tiritaito**, no un sitio propio. Copiar su longitud sería scope creep (`ARQUITECTURA_Y_ROADMAP.md`, R1) |
| Widget embebido de Spotify | Solo si Tiritaito tiene perfil de artista en Spotify con catálogo real. 🔲 Sin confirmar: el export de Avada tiene una URL de Spotify en los iconos sociales (`open.spotify.com/intl-es/artist/20GmvwtwR9YMgL2LrJ9wK1`), pero con un `#` delante que la deja rota. Hay que confirmar si ese perfil está vivo y con música |

---

## 4. Qué puede hacer Avada de forma nativa — verificado en esta sesión

*Todo lo de esta tabla está contrastado contra documentación oficial consultada hoy. Lo que
no he podido verificar aparece marcado 🔲 y hay que probarlo en Local antes de prometérselo
a Carlota.*

| Necesidad de Tiritaito Music | Elemento nativo | Certeza | Nota |
|---|---|---|---|
| Índice de secciones al principio | **Menu Anchor Element**, o la opción "Name Of Menu Anchor" del propio Container + botones/menú apuntando a `#ancla` | ✅ Confirmado | La doc oficial recomienda el ancla del Container salvo que se necesite apuntar a mitad de sección. `scroll_offset: "full"` ya está en el export — el desplazamiento respeta el header sticky |
| Índice generado solo | **Table of Contents Element** — construye la lista a partir de los encabezados de la página | ✅ Confirmado | Alternativa de cero mantenimiento al índice a mano |
| Rejilla de canciones con pestañas de categoría | **Post Cards Element** con `Show Filters = Sí` | ✅ Confirmado en la doc | El elemento trae control completo del estilo de los filtros: tipografía, altura del contenedor, alineación, color de enlace activo y borde del activo. **Es, literalmente, la barra de pestañas del módulo actual, nativa** |
| Carrusel de canciones deslizable | **Post Cards**, Layout = `Carousel` (también `Marquee`, `Coverflow`, `Stacking Cards`, `Slider`) | ✅ Confirmado | Con control de ratón/rueda, modo libre, autoplay, bucle y flechas. Las flechas se pueden disparar además desde un elemento Botón apuntando a `#mi-id-next` / `#mi-id-prev` — es decir, **navegación personalizada sin una sola línea de CSS** |
| Ordenar canciones por un campo propio (nº de pista, fecha) | Post Cards → `Order By: Custom Field` + `Custom Field Name` + `Custom Field Type` | ✅ Confirmado (ya probado en Novedades) | |
| Filtrar canciones por el valor de un campo ACF | Post Cards → `Custom Field - Name` + `Custom Field - Value Comparison` + `Custom Field - Value` | ⚠️ **Contradice lo que dice hoy nuestra documentación** — ver Sección 9.1 | La doc oficial del elemento lista estas tres opciones en la pestaña General, sin restringirlas a productos. Nuestro catálogo afirma que Post Cards ❌ no filtra por valor de campo y que hace falta un hook. **Hay que probarlo en Local antes de dar por buena ninguna de las dos versiones** |
| Vídeo de YouTube que no lastre la carga | **YouTube Element** con `Video Facade = On` | ✅ Confirmado | Carga solo la miniatura hasta que se pulsa play. Ya está activado globalmente en nuestro Local (`GUIA_AVADA_LOCAL.md` Sección 4.4, 11 agosto 2026). ⚠️ **Importante: el Facade es del elemento YouTube de Avada. Un `<iframe>` pegado a mano en un Code Block NO lo aprovecha** |
| Abrir la canción en ventana ampliada sin salir de la página | **Lightbox Element**, o el `Link Target` de una Columna o de un Botón | ✅ Confirmado para Columna, Botón y Lightbox Element | Nuestro lightbox global ya está configurado (skin Metro White, opacidad 0.90, flechas, deeplinking). 🔲 Sin confirmar si el `Link Target` del **Post Card Image** incluye Lightbox — la doc lista "Link Image / Link Target / Custom Link" sin detallar los valores |
| Vista rápida de una canción sin cambiar de página | Post Cards → **`Link Off Canvas`** | 🔲 Documentado, sin probar | Rellena los datos dinámicos del panel con los de la tarjeta pulsada. Es el patrón "Quick View" ya anotado en `CATALOGO_ELEMENTOS_AVADA.md` Sección 3 |
| Recorrer un campo repetidor de ACF como si fueran tarjetas | Post Cards → `Content Source` = **`Repeater Field`** (y también `Relationship Field`) | 🔲 Documentado, sin probar | Muy relevante: permitiría tener **una sola entrada** con un repetidor de canciones, sin crear un CPT. Hay que probarlo |
| Desplegable "¿Qué es Tiritaito Music?" | **Toggles** | ✅ Ya en uso real | Ya calibrado: Boxed Mode, Yeah Papa 30px, radio 10px vía Custom CSS global |
| Galería de fotos del grupo | **Gallery** | ✅ Configurado | 3 columnas, espaciado 10px |
| Reproductor de audio nativo | **Audio Element** | ✅ Configurado, pero ❌ **no aplica aquí** | Es para audio autoalojado. Toda la música de Tiritaito vive en YouTube |
| Efectos visuales (sombra, degradado, cristal esmerilado, hover) | Container / Columna / Botón / Título, pestañas Diseño / Fondo / Extras | ✅ Confirmado 6 septiembre 2026 | `CATALOGO_ELEMENTOS_AVADA.md` Sección 5 bis. **Nada de esto necesita Clase CSS** |

### 4.1 Lo que Avada NO tiene, de ninguna manera

Lo digo sin rodeos, porque es lo que define el tamaño del código que hay que escribir:

1. **Leer un JSON externo y pintar contenido con él.** No existe elemento nativo para eso. Confirmado, y ya es la doctrina del proyecto (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.4).
2. **Reproducción continua con cola propia.** Avada puede poner N vídeos en una página; no puede encadenarlos, ni mantener una cola, ni un mini-reproductor persistente al hacer scroll.
3. **Búsqueda instantánea dentro de una lista de la propia página.** La búsqueda de Avada es la del sitio entero, no un filtro en vivo sobre una rejilla.
4. **Enlace profundo `?mp_play=` que arranca una canción concreta.** Requiere JS.

Todo lo demás de la lista de la Sección 2.1 **sí tiene equivalente nativo**.

---

## 5. La decisión de fondo: dónde viven los datos

Antes de hablar de diseño hay que resolver esto, porque condiciona todo lo demás.

```
                    ¿DÓNDE VIVEN LAS CANCIONES?
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
   ① JSON en la          ② CPT `canciones`     ③ JSON → CPT
   Biblioteca de          + ACF, dentro           (sincronizado
   Medios (hoy)           de WordPress            por snippet)
        │                     │                     │
   Avada NO lo ve.       Avada lo ve todo:     La app sigue
   Solo lo lee un        Post Cards, filtros,   generando; un
   módulo de código.     Dynamic Content,       snippet importa
                         búsqueda del sitio,    el JSON al CPT.
                         Off-Canvas.            Avada lo ve todo.
        │                     │                     │
   Actualizar =          Actualizar = a mano   Actualizar = igual
   1 clic en la app      en wp-admin, o un     que hoy, 1 clic
   (pero hoy el          módulo nuevo en la    (+ el snippet hace
   circuito está roto,   app (Proyecto 5)      el resto solo)
   ver hallazgo #1)
```

| | ① JSON (hoy) | ② CPT + ACF | ③ Híbrido JSON→CPT |
|---|---|---|---|
| Esfuerzo de arranque | Ninguno | Alto (cargar el catálogo entero) | Medio (un snippet importador) |
| Esfuerzo de mantenimiento | Bajo (1 clic) | Alto si se hace a mano; bajo si Proyecto 5 construye el módulo | Bajo (1 clic) |
| ¿Avada puede pintarlo nativo? | ❌ No | ✅ Sí, todo | ✅ Sí, todo |
| ¿Carlota y Álvaro controlan el diseño? | ❌ No | ✅ Sí | ✅ Sí |
| ¿Las canciones aparecen en la búsqueda del sitio? | ❌ No | ✅ Sí | ✅ Sí |
| ¿Sirve para la app de WPMobile más adelante? | ❌ Difícil | ✅ Sí (`ORGANIZACION…` Sección 7.2) | ✅ Sí |
| Riesgo | El circuito ya está roto una vez | Nadie mantiene 100 fichas a mano | El importador hay que escribirlo y probarlo bien |

**Mi lectura, sin adornos:** la opción ③ es la que más se parece a lo que el equipo ya sabe
hacer bien. El patrón *App escribe → ACF guarda → Avada pinta* está probado de extremo a
extremo y documentado (`CUADERNO_DEL_CONSTRUCTOR.md`, entrada ✅ #1, 2 septiembre 2026).
La diferencia es que aquí quien escribe en ACF no sería la app directamente, sino un
importador que lee el JSON que la app ya genera.

Pero **③ tiene un coste real que no voy a esconder**: es un snippet PHP nuevo, con lógica de
sincronización (crear, actualizar, no duplicar, retirar lo que ya no está en el JSON). Eso es
trabajo de Carlitos (Proyecto 9 / Proyecto 3), no es gratis, y no debería empezarse sin
decidir antes si de verdad queremos el escaparate nativo o nos vale el módulo actual saneado.

---

## 6. Tres arquitecturas posibles

### Propuesta A — Escaparate 100 % nativo *(cero código nuevo)*

Requiere datos en CPT (opción ② o ③).

```
HERO (Container + Título Yeah Papa + Imagen/acuarela)
ÍNDICE DE ANCLAS (Menu Anchor + Botones)
─────────────────────────────────────────
CANCIÓN DESTACADA → YouTube Element, Facade On, 100% ancho
─────────────────────────────────────────
TODA LA MÚSICA → Post Cards, Grid 3 col, Show Filters = Sí
                 (filtros = las categorías actuales)
                 Cada tarjeta: miniatura + título + botón ▶
                 El botón abre el vídeo en Lightbox
─────────────────────────────────────────
ESCUCHAR SEGUIDO → Botones a la playlist de YouTube
─────────────────────────────────────────
LA HISTORIA DETRÁS → Toggles / Post Cards (opcional, v2)
─────────────────────────────────────────
¿QUÉ ES TIRITAITO MUSIC? → Toggles
```

- ✅ Carlota diseña y Álvaro construye, sin intermediarios.
- ✅ Video Facade resuelve el rendimiento aunque haya 100 canciones.
- ✅ Las canciones entran en la búsqueda del sitio y en la app futura.
- ❌ Se pierde la reproducción continua, la cola, el mini-reproductor y la búsqueda instantánea.
- ❌ El generador de JSON de la app deja de tener sentido para Música (seguiría usándose para Vía Crucis y Seminarios).

### Propuesta B — Escaparate nativo + reproductor de YouTube *(código casi cero)*

Igual que A, pero la sección de reproducción usa **el reproductor de playlist propio de
YouTube** (`youtube.com/embed/videoseries?list=PL…`), que ya trae de fábrica cola,
siguiente/anterior, aleatorio y listado interno.

- ✅ Reproducción continua real, sin escribir ni mantener nada.
- ✅ Siempre sincronizado con YouTube: no hay JSON que regenerar nunca.
- ❌ Marca y "vídeos relacionados" de YouTube dentro de nuestra página.
- ❌ La cola no se puede estilar con el ADN de Tiritaito.
- 🔲 **A verificar en Local:** el YouTube Element de Avada pide un *Video ID*, no una playlist. Hay un campo de "parámetros adicionales de la API" donde en teoría cabe `&list=PL…` sobre un primer vídeo. Si no funciona, esta sección necesitaría un Code Block con un iframe a mano — y entonces pierde el Video Facade (ver Sección 4).

### Propuesta C — Módulo propio v2 *(el actual, saneado y reconectado)*

Se conserva el reproductor tal cual, pero Carlitos lo reescribe corrigiendo los doce
hallazgos de la Sección 2.3. Lo mínimo imprescindible:

1. Leer `tt_youtube_json_url` desde `/tiritaito/v1/datos` en vez de la URL escrita a mano.
2. **Quitar las llamadas a oEmbed del navegador.** El filtrado de privados debe hacerse **una sola vez, al generar el JSON en la app** (la app ya consulta `status.privacyStatus` en `getPlaylistItems()` — la información ya está ahí, solo hay que guardarla). Esto elimina de golpe el hallazgo #2 completo.
3. Usar `var(--tt-*)` y prefijar cualquier variable propia como `--ttmp-*`, sin tocar `:root`.
4. Yeah Papa en los titulares, radios 25/14/8, rojo `#BF4646` y `#A33B3B`.
5. Arreglar el botón de Autoplay, el contador del hero y el alcance de la búsqueda.
6. Fijar el contrato de `z-index` con el header sticky y el Off-Canvas.
7. Sacar `PAGE_URL` y el logo a constantes que no apunten a producción.

- ✅ Paridad total de funciones; nada de lo que hay hoy se pierde.
- ✅ Encaja literalmente en el reparto del 6 de septiembre: Carlitos diseña el código completo, Álvaro lo pega en un Code Block.
- ❌ La página entera queda fuera del alcance de Avada: cada ajuste visual vuelve a Carlitos.
- ❌ El boceto de Carlota pasa a ser una especificación para código, no algo construible en el Live Builder.

### Propuesta D — **Recomendada**: escaparate nativo + un módulo acotado

```
┌──────────────────────────────────────────────────┐
│ HERO                        ← Avada nativo       │
│ ÍNDICE DE ANCLAS            ← Avada nativo       │
├──────────────────────────────────────────────────┤
│ ╔══════════════════════════════════════════════╗ │
│ ║  REPRODUCTOR + LISTA                         ║ │
│ ║  ← ÚNICO Code Block, diseñado por Carlitos   ║ │
│ ╚══════════════════════════════════════════════╝ │
├──────────────────────────────────────────────────┤
│ LA HISTORIA DETRÁS          ← Avada nativo       │
│ FOTOS DEL GRUPO             ← Avada nativo       │
│ ¿QUÉ ES TIRITAITO MUSIC?    ← Toggles nativo     │
│ CTAs (YouTube, Spotify…)    ← Botones nativos    │
└──────────────────────────────────────────────────┘
```

La idea es sencilla: **reducir la superficie de código de "toda la página" a "una sección"**.
Todo lo que rodea al reproductor vuelve a ser territorio de Carlota y Álvaro, se puede
retocar desde los paneles de Avada y hereda la identidad global automáticamente. El código
queda confinado exactamente a lo que Avada no puede hacer.

**Por qué la recomiendo:** es la única opción que respeta a la vez las dos reglas del 6 de
septiembre — *código solo donde Avada genuinamente no llega* y *Carlota diseña, Álvaro
construye* — sin sacrificar la funcionalidad que ya existe y que la gente usa.

**Lo que hay que decidir para poder ejecutarla:** si dentro de ese Code Block va el módulo
actual saneado (variante C) o el reproductor de playlist de YouTube (variante B). Eso es
una decisión de producto, no técnica, y la formulo en la Sección 10.

---

## 7. Tres direcciones visuales para el boceto

Esto es lo que Carlota tiene que elegir **antes** de dibujar. Son tres formas distintas de
presentar el mismo contenido, compatibles con la Propuesta D.

### Dirección 1 — "Escaparate" *(la más parecida a Hakuna)*

```
┌────────────────────────────────────────────┐
│   ♪  TIRITAITO MUSIC                       │
│   Cantamos lo que rezamos                  │
│   [acuarela de fondo, media pantalla]      │
├────────────────────────────────────────────┤
│  ✦ Escuchar  ✦ Canciones  ✦ La historia    │
├────────────────────────────────────────────┤
│  ▶  LA DE ESTE MES                         │
│  ┌──────────────────────────────────────┐  │
│  │        [vídeo grande 16:9]           │  │
│  └──────────────────────────────────────┘  │
├────────────────────────────────────────────┤
│  TODAS LAS CANCIONES                       │
│  ( Todas )( Navidad )( Adviento )( … )     │
│  ┌────┐ ┌────┐ ┌────┐                      │
│  │ ▶  │ │ ▶  │ │ ▶  │   ← rejilla 3 col    │
│  └────┘ └────┘ └────┘                      │
└────────────────────────────────────────────┘
```

Prioriza **descubrir**. Mucho aire, imágenes grandes, la canción destacada manda.
Encaja con el criterio de altura acotada y con el ADN luminoso.

### Dirección 2 — "Discografía" *(por colecciones)*

```
┌────────────────────────────────────────────┐
│   ♪  TIRITAITO MUSIC        [hero corto]   │
├────────────────────────────────────────────┤
│  NUESTRAS COLECCIONES                      │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐    │
│  │ portada  │ │ portada  │ │ portada  │    │
│  │ NAVIDAD  │ │ ADVIENTO │ │ ALABANZA │    │
│  │ 12 canc. │ │  8 canc. │ │ 20 canc. │    │
│  └──────────┘ └──────────┘ └──────────┘    │
├────────────────────────────────────────────┤
│  ▸ Navidad                        12 ▾     │
│     1. Título de la canción         ▶      │
│     2. Título de la canción         ▶      │
│  ▸ Adviento                        8 ▾     │
│  ▸ Alabanza                       20 ▾     │
└────────────────────────────────────────────┘
```

Prioriza **orden y contención**. Cada playlist es un "disco" con su portada; el listado se
despliega. Mucho menos scroll cuando hay muchas canciones. Es el que mejor aguanta el
crecimiento del catálogo. Se construye con Toggles + Post Cards, todo nativo.

### Dirección 3 — "Reproductor primero" *(lo que hay hoy, con ADN correcto)*

```
┌────────────────────────────────────────────┐
│  ♪ TIRITAITO MUSIC      [logo]  N canciones│
├────────────────────────────────────────────┤
│  🔍 Buscar canciones…                       │
│  ( Navidad )( Adviento )( Alabanza )       │
├────────────────────────────────────────────┤
│  REPRODUCIENDO        ⤨ ▶ ☰ ↗ +            │
│  Título de la canción · Categoría          │
│  ┌──────────────────────────────────────┐  │
│  │           [vídeo]                    │  │
│  └──────────────────────────────────────┘  │
├────────────────────────────────────────────┤
│  ▭ 1. Canción                    ▶         │
│  ▭ 2. Canción                    ▶         │
└────────────────────────────────────────────┘
       [ barra fija inferior de reproducción ]
```

Prioriza **usar**. La página es la aplicación. Máxima funcionalidad, mínima carga editorial,
máxima dependencia de código.

### Cómo elegir

| Si el equipo quiere… | Dirección |
|---|---|
| Que alguien que llega de fuera entienda en 5 segundos qué es Tiritaito Music | 1 |
| Que un catálogo grande (50+ canciones) siga siendo navegable dentro de un año | 2 |
| Que quien ya conoce el proyecto pueda poner música y dejarla sonando | 3 |
| Un poco de cada cosa | 1 arriba + 2 abajo, con el módulo de la 3 confinado a una sección (= Propuesta D) |

---

## 8. Brief para Carlota — lo que necesita para el boceto

### 8.1 Valores reales, contrastados contra el export

*No de memoria: salen de `03-guias-practicas/exports/avada-global-options.json`.*

| Qué | Valor real en Local hoy |
|---|---|
| Color 1 · Fondo Blanco | `#FFFFFF` |
| Color 2 · Superficie secundaria | `#F5F5F7` |
| Color 3 · Separador | `#c7c7cc` |
| Color 4 · Marcador de posición | `#86868b` |
| Color 5 · Rojo Director (rojo de marca) | `#BF4646` |
| Color 6 · Rojo Hover | `#A33B3B` |
| Color 7 · Texto secundario | `#3a3a3c` |
| Color 8 · Texto Principal | `#1d1d1f` |
| H1 / H2 / H3 | Yeah-Papa, 50 / 43 / 37 px, `letter-spacing: 2px` |
| H4 / H5 / H6 | Yeah-Papa, 31 / 25 / 20 px |
| Título de entrada (`post_title`) | Yeah-Papa **78 px** |
| Cuerpo | Helvetica Neue 16px, interlineado heredado |
| Radio de botón | **10px** en las cuatro esquinas ⚠️ ver Sección 9.2 |
| Radio de formularios | 10px |
| Ancho del sitio | Wide, 1200px |
| Breakpoints | ~1024px (Medium) · 480px (`visibility_small`) |
| Sensibilidad tipográfica responsive | 0.30 (el texto **sí** se reduce solo en pantallas pequeñas) |
| Lightbox | Skin Metro White, opacidad 0.90, flechas On, deeplinking On, autoplay Off |
| Rendimiento | Video Facade **On**, Offscreen Rendering **On**, fuentes en local (no Google Fonts) |

⚠️ **Los colores 9 a 13 siguen sin cargar** (`--tt-red-bg`, `--tt-txt3`, `--tt-green`,
`--tt-orange`, `--tt-alert`). Si el boceto los necesita, hay que usar hex directo y anotar
qué variable sustituye.

### 8.2 Lo que Carlota tiene que decidir en el boceto

1. **Dirección visual** (Sección 7): 1, 2, 3 o mixta.
2. **Qué manda en el hero**: acuarela, foto del grupo, logo grande, o vídeo corto mudo.
3. **Si hay canción destacada** y quién decide cuál cada mes.
4. **Aspecto de la tarjeta de canción**: ¿miniatura de YouTube (16:9, con el marco de YouTube) o portada propia cuadrada? Esto tiene consecuencia real: si se quiere portada propia, alguien tiene que diseñar/subir una imagen por canción, y eso cambia el modelo de datos.
5. **Qué pasa al pulsar una canción**: se abre en lightbox, se reproduce en un bloque fijo arriba, o se va a YouTube.
6. **Cuánto contenido editorial hay**: ¿hay letras? ¿hay historia detrás? ¿hay acordes (tipo "Cancionero" de Hakuna)? Si no hay, esas secciones no se dibujan.

### 8.3 Lo que Carlota **no** debe dar por hecho

- ❌ Nada de campo "Clase CSS" en lo que le explique a Álvaro (decisión del 6 de septiembre). Si un efecto no aparece en `CATALOGO_ELEMENTOS_AVADA.md` Sección 5 bis, se escala a Carlitos, no se resuelve con una clase.
- 🔲 El efecto de **cristal esmerilado** (Filtros de Fondo) sigue sin confirmarse en nuestra versión de Avada. No contar con él hasta que Álvaro lo verifique.
- 🔲 Si el boceto incluye pestañas de filtro sobre un **carrusel**, hay un aviso: la documentación de Avada dice explícitamente que los filtros del elemento Portfolio **no funcionan con layout Carousel**. No está confirmado si Post Cards se comporta igual, pero es lo más probable. Filtros → rejilla; carrusel → sin filtros.
- 🔲 El número real de canciones y de categorías. **Es el dato que más condiciona el diseño y hoy no lo tenemos** — ver preguntas abiertas.

### 8.4 Referencias que puede mirar

| Referencia | Para qué |
|---|---|
| `hakuna.org/hakuna-group-music` | Estructura de página larga con índice de anclas; patrón "La Pecera" para las playlists |
| `avada.website/music` | Sitio preconstruido de Avada orientado a música — útil para ver qué consigue Avada nativo sin ayuda |
| `avada.website/podcasts` | Mismo motivo, con listados de episodios |
| El propio módulo actual | Para ver qué funciones existen hoy y no conviene perder sin decidirlo |

---

## 9. Hallazgos laterales que afectan a otros documentos

*Siguiendo la regla 7 de las instrucciones de este Proyecto: lo digo antes de seguir, y no
lo aplico sin que me lo confirmes.*

### 9.1 Post Cards **sí** parece filtrar por valor de campo personalizado

`CATALOGO_ELEMENTOS_AVADA.md` Sección 4 y `GUIA_AVADA_LOCAL.md` Sección 9 afirman que Post
Cards **no** filtra de forma nativa por valor de campo, y que haría falta el hook
`fusion_post_cards_shortcode_query_override`. La documentación oficial del elemento, revisada
hoy, lista en su pestaña General tres opciones que dicen lo contrario:
`Custom Field - Name`, `Custom Field - Value Comparison` y `Custom Field - Value`, sin
restringirlas a productos.

No afirmo que nuestra documentación esté mal — pueden ser opciones añadidas después de julio,
o comportarse de forma distinta a lo esperado. **Pero es exactamente el tipo de dato que
bloquea decisiones reales** (el listado de Novedades sin filtrar por `activo`, y el listado
de "Seminarios pasados" que está pendiente por esto mismo). Propongo una prueba corta en
Local antes de tocar ningún documento.

### 9.2 El radio de botón del export (10px) no coincide con el catálogo (25px)

`CATALOGO_ELEMENTOS_AVADA.md` Sección 5 dice que Cuenta 1 corrigió el Border Radius del
elemento Botón "de 4px a 25px". El export real del 14 de agosto dice `10px` en las cuatro
esquinas. Como el propio catálogo declara que el export es la evidencia más fuerte, o bien
alguien lo cambió después, o bien la nota del catálogo no refleja lo aplicado. Conecta
directamente con la pregunta abierta del cuarto token de 10px.

### 9.3 Tres grafías distintas de las fuentes en el export

`custom_fonts.name` registra `"Helvetica Nueva"` y `"YEAH-PAPA"`, mientras que
`h1_typography.font-family` usa `"Yeah-Papa"` y `body_typography.font-family` usa
`"Helvetica Neue"`. Puede ser irrelevante (Avada normaliza) o puede ser la causa de que
alguna fuente no cargue en algún sitio. Merece una comprobación visual rápida.

### 9.4 Avada se ha partido en dos: "Avada Classic" y "Avada One"

Al consultar la documentación oficial hoy, avada.com redirige parte de sus documentos a
**classic.avada.com**, con un aviso de que ese es el nuevo hogar de la documentación de
Avada Classic. Según el blog oficial de ThemeFusion, Avada Classic (la línea que usamos,
7.15/7.16) **sigue recibiendo mantenimiento, correcciones de seguridad y funciones menores,
sin plan de cambiar eso**, y Avada One es una plataforma nueva, con arquitectura nueva y
modelo de suscripción, pensada para coexistir a largo plazo.

**Para Tiritaito no cambia nada operativo hoy.** Pero sí afecta a las URLs de fuentes citadas
en `GUIA_AVADA_LOCAL.md` Sección 18 y en `CATALOGO_ELEMENTOS_AVADA.md`, que con el tiempo
pueden dejar de resolver o empezar a describir el producto equivocado. Conviene anotarlo.

### 9.5 Fila cerrable en `METODOLOGIA_CONSTRUCCION.md`

La evaluación pendiente de "¿`[tt_podcast]` cubre Tiritaito Music?" queda resuelta: no lo
cubre (Sección 1 de este documento). La fila puede pasar de "evaluar" a una decisión escrita.

---

## 10. Próximos pasos y preguntas abiertas

### Próximos pasos

1. **Carlitos + Hna C:** elegir arquitectura (A, B, C o D) — es lo que desbloquea todo lo demás. Mi recomendación razonada es **D**, pendiente de resolver la pregunta abierta #2.
2. **Carlitos:** conseguir el `music-data.json` real (o el número de playlists y canciones). Sin ese dato, el boceto se dibuja a ciegas — ver pregunta abierta #1.
3. **Hno A (Proyecto 3/7), prueba corta en Local, ~30 minutos:** (a) ¿funcionan los filtros de Post Cards sobre un campo personalizado ACF? (b) ¿el `Link Target` del Post Card Image incluye Lightbox? (c) ¿acepta el YouTube Element un parámetro `&list=PL…`? Las tres respuestas cambian qué se le puede prometer a Carlota.
4. **Carlitos:** decidir si se escribe el importador JSON → CPT (opción ③ de la Sección 5), que es el paso que convierte la página en territorio de Avada.
5. **Proyecto 5 (Hno A), independientemente de todo lo anterior:** guardar el `privacyStatus` de cada pista **al generar el JSON**, para que el filtrado de vídeos privados deje de hacerse en el navegador de cada visitante (hallazgo #2, el más grave del módulo). La app ya tiene ese dato en `getPlaylistItems()`; solo hay que no tirarlo.
6. **Carlitos:** confirmar si el módulo actual de producción tiene la `DATA_URL` actualizada a mano después de cada generación, o si lleva tiempo sirviendo un JSON antiguo (hallazgo #1).
7. **Hno C (yo), cuando lo confirmes:** aplicar en `METODOLOGIA_CONSTRUCCION.md`, `CATALOGO_ELEMENTOS_AVADA.md` y `GUIA_AVADA_LOCAL.md` lo que decidas de la Sección 9.
8. **Carlota (Proyecto 4/6):** con la arquitectura elegida y el inventario real, hacer el boceto siguiendo el brief de la Sección 8.

### Preguntas abiertas

| # | Pregunta | Bloquea a | Quién decide |
|---|---|---|---|
| 1 | **¿Cuántas playlists y cuántas canciones hay hoy en `music-data.json`?** Diseñar para 15 canciones y diseñar para 150 son dos bocetos distintos | El boceto entero | Carlitos (mirar el JSON) |
| 2 | **¿Queremos reproducción continua con cola propia de Tiritaito, o basta "pulso una canción y suena"?** Es *la* pregunta: separa la Propuesta A de la C/D | Arquitectura | Hna C + Carlitos |
| 3 | ¿Las canciones pasan a ser contenido de WordPress (CPT) o se quedan en el JSON? | Si la página es nativa o de código | Carlitos |
| 4 | Si pasan a CPT: ¿las carga alguien a mano, las carga un módulo nuevo de Creators, o las importa un snippet desde el JSON? | Trabajo de Proyecto 3 y 5 | Carlitos |
| 5 | ¿Hay contenido editorial por canción — letra, historia, acordes — o solo vídeo y título? | Si existen las secciones "La historia detrás" y "Cancionero" | Hna C |
| 6 | ¿Existe un perfil de Spotify de Tiritaito con catálogo real? La URL del export está rota (lleva un `#` delante) | Si se añade un bloque de Spotify | Carlitos |
| 7 | ¿Se mantiene el enlace profundo `?mp_play=` para compartir una canción concreta? Si sí, condiciona la arquitectura (necesita JS) | Arquitectura | Hna C |
| 8 | ¿Quién gana en pantalla cuando el menú móvil (Off-Canvas) se abre con el reproductor desplegado? | Contrato de `z-index` del módulo | Carlitos + Hno A |
| 9 | ¿La entrada de Tiritaito Music se construye antes o después que Rincón de Nico y Charlas de la Biblia? El "principio de unidad de podcast" (`ALCANCE_WEB_NUEVA.md` Sección 5) puede querer que compartan patrón visual | Orden de trabajo | Hna C |

---

*Para la mayor gloria de Dios · tiritaito.com*

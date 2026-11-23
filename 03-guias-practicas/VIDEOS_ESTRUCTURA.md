# TIRITAITO.COM — Vídeos de YouTube en la web nueva: estructura
**Registro vivo: cómo entran los vídeos de YouTube en la web nueva (Música, Vía Crucis, Seminarios y lo que venga), con los nombres exactos, lo construido, lo probado, lo que hay que tener en cuenta para el lanzamiento y lo que queda pendiente**
*Para el repositorio: `03-guias-practicas/VIDEOS_ESTRUCTURA.md` · Es el único documento vivo de los vídeos de YouTube de la web nueva (junto con el código `apps/v2/tt-video-importador.php` y `apps/v2/tt-sem-tipo-automatico.php`) · Redactado por Proyecto 2 (Hno C) el 20 de noviembre de 2026 · Modelo: `PODCAST_ESTRUCTURA.md`*
*Fuentes: `TIRITAITO_MUSIC_WEB_NUEVA.md` (con su Sección 17, de cierre) · `SEMINARIOS_VIDEOS_WEB_NUEVA.md` (16 sept) · `INVESTIGACION_MIGRACION_LANZAMIENTO.md` (20 nov) · `00_CORE.md` · `04_ENTORNO_LOCAL.md` · `ALCANCE_WEB_NUEVA.md` · `conecta_estructura.md` · `README.md`*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento y cómo leerlo

Responde a una pregunta: **¿cómo funcionan los vídeos de YouTube en la web nueva, qué hay hecho, con qué nombres, qué hay que cuidar al lanzar y qué falta?**

Está escrito para que lo entienda cualquiera del equipo. Los nombres técnicos exactos (de casillas y de tipos de contenido) están en tablas aparte para Hno A; el resto se explica sin ellos.

| Si necesitas… | Ve a… |
|---|---|
| Entenderlo en cinco minutos | Secciones 1 y 2 |
| Saber qué secciones de la web usan vídeos de YouTube | Sección 3 |
| Las playlists reales, con su código | Sección 5 |
| Cómo se ordena y se titula cada cosa | Sección 7 |
| Música, Vía Crucis, Seminarios o Lenguas, cada una por separado | Sección 8 |
| Qué hago cuando subo, quito o cambio un vídeo en YouTube | Sección 9.4 |
| Qué reglas de YouTube hay que cumplir | Sección 10 |
| **Qué cuidar al llevar la web de Local a producción** | **Sección 14** |
| Cómo añadir una playlist o un área nueva | Sección 15 |
| **Lo pendiente y las preguntas abiertas** | **Sección 18** |
| El podcast (Spotify) | `PODCAST_ESTRUCTURA.md` — es otro sistema |
| Las novenas y Oraciones | `ORACIONES_ESTRUCTURA.md` |

**Estados** (los mismos símbolos que el resto del repositorio):

| Marcador | Significa |
|---|---|
| ✅ | Comprobado: en Local (pruebas de Álvaro del 26–27 oct y del 6 nov), en un archivo real o en documentación oficial |
| ☑ | Declarado por Álvaro o por otro documento como funcionando, **sin captura revisada por Proyecto 2** |
| 🔲 | Sin probar o sin construir |
| ❌ | Probado y no funciona |
| ◐ | Funciona en parte |
| ⚠️ | Decidido o encontrado, pero con algo sin cerrar |

**Glosario sencillo** (cada palabra, una vez):

| Palabra | Qué quiere decir aquí |
|---|---|
| Ficha | Una «entrada» de WordPress. Cada vídeo de YouTube es **una ficha** en la web |
| Casilla | Un dato de la ficha (la dirección del vídeo, la fecha, la miniatura…). Las crea ACF, el complemento de campos |
| Categoría | Un «cajón» donde se agrupan fichas: Navidad, Virgen María… Una ficha puede estar en varios |
| Tipo de contenido | Una «clase» propia de fichas, con su lista en el panel de WordPress: canciones, estaciones del Vía Crucis, vídeos de seminarios |
| Importador | Un programa propio, dentro de la web, que **cada madrugada pregunta a YouTube** qué vídeos hay en cada playlist y crea o actualiza las fichas. Nadie teclea los vídeos |
| Playlist | Una lista de reproducción de YouTube. Es la fuente de verdad |
| Clave de YouTube | La «contraseña» con la que nuestra web tiene permiso para preguntar a YouTube. Es secreta |
| Borrador | Una ficha guardada pero **no visible** en la web. Así «retiramos» un vídeo sin borrarlo |
| Semáforo | Un indicador verde / amarillo / rojo que dice si el importador lleva días sin funcionar |
| Lightbox | La ventana flotante que se abre encima de la página y reproduce el vídeo |
| Modal | Otra ventana que se abre encima de la página (el panel «Más información» de Seminarios) |
| Post Cards | El elemento de Avada que pinta una lista de fichas como tarjetas |
| Producción | La web pública real (`www.tiritaito.com`) |
| Local | La copia privada de trabajo (`tiritaito-real.local`) |

---

## 1. Estado en una mirada (20 de noviembre de 2026)

- **Decisiones de estructura: cerradas** (Sección 4).
- **El importador de vídeos (v1.2) funciona en Local.** Álvaro pasó casi todas las pruebas el 6 de noviembre (Sección 13). Después se cambiaron los títulos (7 nov) y los de **Seminarios ya salen como «Mes Año»** (confirmado por Carlitos el 12 nov). **Falta confirmar** que la v1.2 deja bien los títulos de Música (sin «Tiritaito Music» y con la autora aparte).
- **Fichas ya creadas en Local:** Música 62 publicadas (más 2 en borrador, las subidas repetidas), Vía Crucis 28, Seminarios 29.
- **Pantallas construidas en Avada:** solo **Música** (portada, «Todas las canciones», una página por lista, ficha de canción y panel de compartir; 11 nov). **De Seminarios y de Vía Crucis tenemos los datos, pero no consta que sus pantallas estén construidas.**
- **Lenguas:** decidida, sin crear. **Rincón de Nico:** hay que aclarar si usa este importador o el del podcast (Sección 3.2).
- **Producción:** todavía no hay nada instalado. Cómo se lleva allí depende del plan de lanzamiento (Sección 14).
- ⚠️ **Un aviso sin explicar:** según `conecta_estructura.md`, el importador de música avisó en algún momento de «frenado: iba a retirar 22 de 64 fichas». No se ha tocado y **no sabemos la causa** (Sección 18).
- **Por tanto:** los vídeos están **cerrados en estructura y decisiones, y abiertos en pantallas de Seminarios y Vía Crucis, en producción y en unos pocos pendientes**.

---

## 2. La idea, en cinco líneas

1. **Cada vídeo de YouTube es una ficha de la web.** Una canción = una ficha; una estación del Vía Crucis = una ficha; un vídeo de seminario = una ficha.
2. **YouTube manda.** Las playlists de YouTube son la fuente de verdad. Quien mantiene los vídeos solo trabaja en YouTube.
3. **El importador lo pasa a la web solo**, cada madrugada (05:15), o cuando alguien pulsa «Sincronizar ahora». **Un solo importador** sirve para todas las áreas: una área nueva es una fila nueva en su tabla de ajustes.
4. **Avada dibuja las fichas de forma nativa** (Post Cards, filtros, Lightbox). Carlota diseña, Álvaro construye, sin código propio de pantalla.
5. **Una caída nunca borra nada.** Si YouTube falla, el importador no retira ninguna ficha.

```
YOUTUBE (playlists)               WORDPRESS                              AVADA
───────────────────      ──────────────────────────────      ───────────────────────────
 Tiritaito Music  ┐
 Espíritu Santo   │
 Virgen María     │
 Navidad          ├─►  IMPORTADOR  ─►  una FICHA por vídeo  ──►  Post Cards (tarjetas)
 San José         │    (cada           + sus categorías          · filtros por categoría
 Vía Crucis (2)   │     madrugada)     + su orden                · orden = el que calcula
 Seminarios       │                    + su dirección propia       el importador
 Lenguas (luego)  ┘                                              · tarjeta → ficha
                                                                 · vídeo → Lightbox
                          └─ pantalla de ESTADO + SEMÁFORO (verde / amarillo / rojo)
```

Una canción que está en dos playlists tiene **dos categorías pero una sola ficha**: 64 fichas de Música, no las 88 entradas que cuenta YouTube sumando playlists.

---

## 3. Qué secciones de la web usan vídeos de YouTube

### 3.1 Las que usan el importador

| Área | Qué es | Playlists | Fichas hoy (Local) | Dónde se verá en la web | Estado |
|---|---|---|---|---|---|
| **Música** | Las canciones de Tiritaito Music | 5 (Sección 5) | 62 publicadas + 2 en borrador | Portada «Tiritaito Music», «Todas las canciones», una página por lista, ficha de canción | ✅ datos · ☑ pantallas construidas (11 nov) |
| **Vía Crucis** | Las estaciones, en dos playlists | 2 | 28 | Oraciones → Vía Crucis (`ORACIONES_ESTRUCTURA.md`) | ✅ datos · 🔲 pantalla sin documentar |
| **Seminarios** | Vídeos de seminarios pasados, de los 5 tipos | 1 (29 vídeos) | 29 | Panel «Más información» de cada seminario y una página con el catálogo completo | ✅ datos · 🔲 pantallas sin construir según los documentos |
| **Lenguas** | Oración en lenguas | 1 (11 vídeos) | 0 | Oraciones → Oración en lenguas | 🔲 se crea **cuando acaben las pruebas** (decisión del 22 oct) |

### 3.2 ⚠️ Rincón de Nico: hay que aclararlo antes de darlo por montado

Me pides que tenga en cuenta «El Rincón de Nico» entre las secciones de vídeo. **Los documentos dicen otra cosa**: `ALCANCE_WEB_NUEVA.md` §4.F y `PODCAST_ESTRUCTURA.md` §14 lo tratan como **un canal de podcast** (se alimenta del importador del podcast con un feed de Spotify, y su feed está «sin conseguir»). No aparece en ninguna fila del importador de vídeos.

Pueden ser dos cosas distintas, y no quiero adivinar:

| Posibilidad | Qué significaría |
|---|---|
| **(a)** El Rincón de Nico tiene también una playlist de YouTube y se quieren sus vídeos como los de Música | Se monta como un área más del importador de vídeos (Sección 15). Necesito: el código de su playlist, el nombre visible, si hay categorías, y cómo se ordena |
| **(b)** Sigue siendo solo podcast | No cambia nada aquí; vive en `PODCAST_ESTRUCTURA.md` |

**Hasta que me lo confirmes, no lo he dado por construido ni lo he puesto como área del importador.** Ver pregunta abierta nº 1 de la Sección 18.

### 3.3 Lo que NO usa el importador (para que nadie lo busque aquí)

| Pieza | Cómo se resuelve | Dónde se explica |
|---|---|---|
| Podcast (Charlas de la Biblia, santos, Rosario, Salmos, Novenas con audio…) | Importador **del podcast**, con feeds de Spotify | `PODCAST_ESTRUCTURA.md` |
| Novenas de días: Inmaculada, Sagrados Corazones, Espíritu Santo | **A mano**: el vídeo de cada día va en una casilla de su ficha (Sección 8.5) | `ORACIONES_ESTRUCTURA.md` |
| Pentecostés, Posesión mariana, «Adorando en silencio» | **A mano**: un vídeo cada una | `ORACIONES_ESTRUCTURA.md` |
| **Lenguas del día** en «Conecta cada día» | Lo sube el equipo cada día desde la app Tiritaito for Creators. **No es la playlist «Lenguas»** | `conecta_estructura.md` §3.2 |
| **Ejército** en «Conecta cada día» | Siete días con su vídeo, escritos a mano en una página de ajustes (dirección completa `youtube.com/watch?v=…`, no vale `youtu.be`) | `conecta_estructura.md` §3.1 |
| Salmo del día | El audio sale del podcast Salmos, no de YouTube | `conecta_estructura.md` §3.4 |

💡 Idea guardada, **no decidida**: la playlist «Lenguas Ejército» (8 vídeos) no se importa por ahora; podría servir más adelante para la página del Ejército.

---

## 4. Decisiones tomadas

| # | Decisión | Fecha |
|---|---|---|
| — | Los vídeos de seminarios pasados van dentro del panel «Más información» de cada seminario (con el elemento **Modal**, mejor que Off-Canvas): los **2 más recientes** de ese tipo, cada miniatura abre un **Lightbox**, siempre en YouTube. Los 5 bloques lo llevan, también en móvil. **Si un seminario no tiene vídeos, esa sección se oculta entera**. «Ver todos los vídeos →» lleva a una **entrada nueva** con el catálogo completo | 17 sept |
| Camino 2 | **El servidor pregunta a YouTube** (no la app del navegador del editor). Sin pasos a mano | 13 oct |
| C1 | **A y luego D:** primero escaparate nativo con Avada; el reproductor con cola, más adelante. El enlace `?mp_play=` se sustituye por la dirección de cada ficha | 22 oct |
| C2 | «Más reciente» en Música = **fecha de publicación del vídeo** | 22 oct |
| C3 | Orden de Seminarios: **mes y año del texto, los dos descendentes**; desempate por la fecha del vídeo | 22–23 oct |
| C4 | Orden de Vía Crucis = **número del título** (1º…14º o (I)…(XIII)). «(IX) La Última Cena» **se queda donde está**. Un vídeo sin número va el primero, con aviso | 22–23 oct |
| C5 | Las dos canciones subidas dos veces se resuelven en YouTube: se quedan «Él me ama» (`P-3LMZvoH_Q`) y «Bajo tu manto» (`tHN2sKkguYA`) | 22 oct |
| C6 | Vídeos retirados: **borrador**, con freno del 20 % y limpieza a los 25 días (Sección 9.2) | 22 oct |
| C8 | Un solo grupo de casillas con prefijo común `vid_` para todas las áreas; el orden no tiene casilla (usa el orden nativo de WordPress) | 22 oct |
| C9 | **Cada vídeo tiene su propia dirección y se puede compartir**, pero pidiendo a Google que no la liste (`noindex`) y fuera del mapa de páginas | 22 oct |
| C10 | Novenas de días: vídeos fijos, **a mano** | 23 oct |
| C12 | Lenguas entra como área del importador **cuando acaben las pruebas** | 22 oct |
| C13 | Clave de YouTube en la **cuenta de Tiritaito**, sin restricción de aplicación, con una **clave distinta para Local** | 13 y 22 oct |
| C14 | «Todas» **no es una categoría**: su papel lo hace la pestaña «All» que Avada añade sola | 28 oct |
| C15 | Aviso de importador parado: **amarillo a 10 días, rojo a 20**, con semáforo también en la app de Creators | 22 oct |
| C16 | **Primero todas las pruebas; después, con todo funcionando, Carlota hace los diseños** | 23 oct |
| K1, K3, K4, K8 | Dirección visual 1 con hueco para el reproductor · canción destacada automática (la más reciente) · tarjeta con la miniatura de YouTube (16:9) · esquinas de **15 px** | 22 oct |
| C7 *(revisada)* | **Títulos limpios:** Música sin «Tiritaito Music» ni autora (la autora, aparte); Seminarios solo «Mes Año»; Vía Crucis, tal cual está en YouTube. **Invierte** la decisión del 23 de octubre | 7 nov |
| — | El tipo de cada seminario es una **categoría** (`categoria_seminario`) que asigna un programa auxiliar mirando el título original | 7 nov |
| — | Las fichas de Música usan el tipo `cancion` y `categoria_musica` (**no** el tipo `lista_musica` del boceto antiguo, que chocaba con lo decidido) | 11 nov |
| — | Cerrado el tema de Tiritaito Music; sus pendientes pasan a la Sección 18 | 12 nov |

---

## 5. Las playlists reales de YouTube

*Leídas el 23 de octubre a partir de las direcciones que pasó Carlitos. El recuento es el que muestra la página pública de YouTube. Los códigos de playlist son públicos: no son secretos (la clave de YouTube sí lo es).*

| Playlist en YouTube | Código | Vídeos en YouTube | Área | Observaciones |
|---|---|---|---|---|
| Tiritaito Music | `PLX21N4KPkRTRJOH4-uPMq2YS62-l-hePc` | 70 | Música | Su nombre en la web es «Todas», pero **no crea categoría** (C14). Ver pregunta 6 de la Sección 18: YouTube dice 70 y la web carga 64 |
| Virgen María | `PLX21N4KPkRTStJJCgbOy05SGymdCgtA0m` | 7 | Música | ✅ Coincide |
| San José | `PLX21N4KPkRTQ1Vh96bifV73J78KfYXHSD` | 2 | Música | ✅ Coincide |
| *(sin identificar)* | `PLX21N4KPkRTSUDoivrUR3PJ4LmylhW2Ep` | ? | Música | Candidata a «Espíritu Santo» o «Navidad» |
| *(sin identificar)* | `PLX21N4KPkRTQWUiQ_IOwJ2GtsI8U6vEPa` | ? | Música | La otra candidata. **Los documentos no dicen cuál es cuál**; consta que ambas categorías cargaron sus 7 canciones el 6 de nov |
| Con Jesús hacia la Cruz | `PLX21N4KPkRTTz6ILAWvbk1a7SBywzw_3O` | 15 | Vía Crucis | Una está **no listada** y no se importa (Sección 8.2) |
| Via Crucis de la Divina Misericordia 2024 / Tiritaito | `PLX21N4KPkRTR9zSSsTdq85v3Oc4qYecP1` | 15 | Vía Crucis | Igual: 15 en YouTube, 14 importadas |
| Seminarios | `PLX21N4KPkRTR8_vFAGAlKswgbV7f_EF4J` | 29 | Seminarios | **Una sola playlist para los 5 tipos.** Incluye no listados |
| Lenguas | `PLX21N4KPkRTQotWXk-qpVQSSzaXGuFbwU` | 11 | Lenguas (luego) | — |
| Lenguas Ejército | `PLX21N4KPkRTRjM3ZaOA25EfPwOpojpvU8` | 8 | — | Subconjunto de Lenguas. No se importa por ahora |
| Pentecostés | `PLX21N4KPkRTSkCX7g6WnlphX2mWSvFCT4` | ? | A mano | No se pudo abrir. ¿Son los vídeos del día de Espíritu Santo? (pregunta 14) |
| Sagrados Corazones | `PLX21N4KPkRTSY6vRwaGMZORrCifg2g_Gb` | ? | A mano | No se pudo abrir |

---

## 6. Cómo se guarda cada vídeo (nombres exactos)

*Para Hno A. La regla de oro: **ninguna casilla con contenido se renombra**.*

### 6.1 Tipos de contenido y categorías

| Qué | Nombre | Para qué | Estado |
|---|---|---|---|
| Tipo | `cancion` | Las canciones de Música. Público, con página propia compartible | ✅ |
| Categorías | `categoria_musica` | Espíritu Santo, Virgen María, Navidad, San José | ✅ |
| Tipo | `viacrucis` | Las estaciones | ✅ |
| Categorías | `categoria_viacrucis` | Una por playlist | ✅ |
| Tipo | `video_seminario` | Los vídeos de seminarios | ✅ |
| Categorías | `categoria_seminario` | El **tipo de seminario** de cada vídeo (los 5 tipos) | ✅ |

⚠️ **No confundir** `video_seminario` (los vídeos, que alimenta el importador) con `seminario` (las 6 fichas de tipo de seminario, que el importador no toca). Ver pregunta 12 de la Sección 18.

El tipo antiguo `videos_seminarios` estaba previsto limpiarlo; **su estado final no consta**.

### 6.2 Grupo de casillas «TT — Vídeo YouTube» (el mismo para todos los tipos)

| Nombre exacto | Qué guarda | Quién la rellena |
|---|---|---|
| `vid_video_id` | El código del vídeo (11 caracteres). **Es su identidad**: nunca se fusionan dos vídeos por tener el mismo título | Importador |
| `vid_youtube_url` | La dirección `https://www.youtube.com/watch?v=…` (casilla de **texto**, no de URL) | Importador |
| `vid_miniatura_url` | La miniatura que da YouTube (320×180), enlazada, no descargada | Importador |
| `vid_fecha_video` | Fecha de publicación del vídeo (`AAAA-MM-DD`) | Importador |
| `vid_comprobado` | Última vez que YouTube confirmó esta ficha (fecha y hora, en UTC). Sirve para la regla de los 30 días | Importador |
| `vid_hash` | Una «huella» del contenido: si no cambia, no se reescribe nada | Importador (técnica) |
| `vid_titulo_yt` *(opcional)* | El título original de YouTube. **Obligatoria en Seminarios**: el programa auxiliar busca ahí el tipo | Importador |
| `vid_autora` *(opcional)* | La hermana o hermanas que cantan (Música). Vacía si no hay | Importador |
| `vid_kids` *(opcional)* | `sí` / `no` / `desconocido` según «Hecho para niños» | Importador |
| `sem_anio`, `sem_mes` | Año y mes del seminario (solo Seminarios) | Importador |
| `vcr_numero` *(opcional)* | Número de estación (solo Vía Crucis) | Importador |

**El orden no tiene casilla:** se guarda en el campo nativo de WordPress `menu_order`, y Avada ordena por «Menu Order», de menor a mayor.

**Datos privados del importador** (no son casillas; no se tocan): `_tt_vid_area` (de qué área es la ficha) · `_tt_vid_playlists` (en qué playlists estaba) · `_tt_vid_retirada` y `_tt_vid_vaciada` (control de retiradas) · `_tt_vid_playlist` (en cada categoría: la playlist que la alimenta). **La identidad la dan estos códigos, no los nombres**: renombrar una categoría no crea duplicados.

**Dónde se guardan los ajustes:** `tt_video_importador_filas` (la tabla de playlists) y `tt_video_importador_log` (el registro).

---

## 7. Títulos y orden, área por área

### 7.1 Títulos

| Área | Cómo queda el título de la ficha | Dónde queda lo demás |
|---|---|---|
| **Música** | Sin «Tiritaito Music» ni «Tiritaito» al final y **sin la autora**. Ejemplo: «Bajo tu manto» en vez de «Bajo tu manto - Hna. Emi \| Tiritaito Music» | La autora, en `vid_autora`; el título original, en `vid_titulo_yt` |
| **Seminarios** | Solo **«Mes Año»** (por ejemplo, «Marzo 2025»). El tipo ya está en la categoría y repetirlo sería redundante | El título original, en `vid_titulo_yt`; año y mes, en `sem_anio` y `sem_mes` |
| **Vía Crucis** | **Tal cual está en YouTube** | — |
| Cualquier otra | Tal cual está en YouTube | — |

**La regla de Música, probada sobre los 64 títulos reales** (sobre el papel; ver Sección 13 para lo que falta confirmar en Local): se quita del final «Tiritaito Music» o «Tiritaito»; y si lo que queda tras el último separador empieza por «Hna.», «Hno.», «Hermana» o «Hermano», **eso es la autora**. Resultado: 53 de 64 con autora detectada, 10 con el sufijo quitado y sin autora, 1 sin cambios, **0 casos raros**. El título medio baja de 45 a 18 caracteres y el máximo de 98 a 55.

**Límites honestos:** un nombre sin «Hna.» o «Hno.» delante no se reconoce como autora y se queda en el título; ante un caso dudoso, el importador **deja el título como está y avisa**, nunca lo altera a ciegas. En Seminarios, si el mes y el año no se encuentran en el texto del vídeo, se deja el título de YouTube y se avisa.

**Si hay que corregir un título** (mayúsculas, tildes, una errata), **se corrige en YouTube**. Hay unas 11 canciones con título en MAYÚSCULAS y algunas erratas de tilde («Hágase en mi», «Que seria de mi sin ti», «Hazme decir si»).

**Una edición a mano en la ficha se conserva** hasta que YouTube cambie *ese* vídeo; entonces se pisa con el dato nuevo. Por eso los cambios de título conviene hacerlos en YouTube.

### 7.2 Orden

| Área | Regla | Cómo se calcula |
|---|---|---|
| **Música** | Más reciente primero, por la fecha del vídeo | `menu_order = 2147000000 − fecha del vídeo` |
| **Seminarios** | Año y mes descendentes (el más reciente primero); si empatan, por la fecha del vídeo; si no hay mes y año en el texto, por la fecha del vídeo | Año y mes del texto |
| **Vía Crucis** | Por el número del título. Un vídeo sin número va el primero, con aviso. Si ninguna lista trae números, por la posición en la playlist | `menu_order = 10 + 10 × número` |

**Cada ficha recibe un número que depende solo de sus propios datos**, no de su puesto en el ranking. Así, **añadir una canción nueva no obliga a reescribir las demás** y una segunda sincronización da cero cambios.

El número del título se entiende en arábigo con «º» (`7º`) y en romano entre paréntesis hasta XX (`(XIII)`), y **no confunde «Novena» con un 9** (el generador antiguo sí lo hacía).

---

## 8. Cada área en detalle

### 8.1 Música

**Datos (✅, Local):** 64 fichas creadas; tras limpiar las dos subidas repetidas, **62 publicadas y 2 en borrador**. Categorías: Espíritu Santo 7, Virgen María 7, Navidad 7, San José 2 = **23 canciones**; las otras ~41 solo están en «Todas» y se ven con la pestaña «All» de Avada.

**Casos que ya se resolvieron:**
- «Él me ama» y «Bajo tu manto» estaban **subidas dos veces**: se dejó una de cada y se quitaron las otras de las playlists (no de YouTube).
- «Hágase en mí» son **dos canciones distintas** con casi el mismo título: la identidad es el código del vídeo, nunca el título. Con el título limpio quedan **dos con el mismo título**; las distingue la autora.
- Un código repetido dentro de una misma playlist se ignora y avisa.

**Pantallas construidas (☑ según Álvaro, 11 nov; sin capturas revisadas):**

| Pieza | Qué es |
|---|---|
| Tarjeta «TT — Música — Canción» | Una fila: foto (la miniatura), título con su lista debajo, y tres puntos que abren el panel de compartir. Foto y título llevan a la ficha |
| Ficha «TT — Música — Ficha» | Estilo Apple Music: botón «‹ Todas las canciones», portada centrada con esquinas de 15 px (máx. 280 px), título, categoría y la fila de la pista, que **abre el vídeo en Lightbox**. Fondo claro, nunca modo oscuro |
| Panel «TT — Música — Compartir» | Panel lateral con WhatsApp, Telegram, Email y Copiar enlace, con el título y la dirección de **esa** canción |
| Página «Tiritaito Music» | Portada: título, botón «Escuchar todas las canciones», «Últimas canciones» (5), tarjetas de listas hechas a mano |
| Página «Todas las canciones» | Todas las canciones con pestañas por categoría y «Cargar más» |
| Una página por lista | Hecha «Virgen María»; las demás se duplican cambiando la categoría |

**Diferencias con el boceto, aceptadas:** sin reproductor fijo, cola, barra de progreso ni recuento «N canciones»; las canciones abren su ficha, no un reproductor; «Escuchar todas» es un enlace, no una cadena de reproducción. **El reproductor con cola es la fase D**, para más adelante.

**Limitaciones conocidas de la ficha:** la portada cuadrada (1:1) no es la miniatura (16:9); recortarla bien necesitaría un traspaso a Proyecto 11. El año en la línea de categoría pediría una casilla `vid_anio` que el importador todavía no escribe.

### 8.2 Vía Crucis

**Qué es:** dos playlists que se pueden ampliar: «Via Crucis de la Divina Misericordia 2024» (estaciones «1º»…«14º») y «Con Jesús hacia la Cruz» (un vídeo introductorio sin número y las estaciones «(I)»…«(XIII)»). **Cada año nuevo = una playlist nueva = una fila nueva** en los ajustes.

**Datos (✅, Local):** 28 fichas. El orden sale bien: «(XIII)» no se va al final y el vídeo introductorio va el primero, con aviso.

**Cosas a tener presentes:**
- El orden de las playlists en YouTube no es numérico (una está alfabética, la otra sin regla); por eso se ordena por el **número del título**, no por la posición.
- **«(IX): La Última Cena»** va entre la VIII y la X. Cronológicamente iría antes de la I, pero se decidió dejarla ahí.
- ⚠️ **Cada playlist tiene un vídeo no listado que no se importa** (15 en YouTube, 14 importadas). ¿Falta una estación? Sin mirar todavía (pregunta 7 de la Sección 18).
- El vídeo introductorio se titula «…Novena a la Divina Misericordia». ¿Tiene relación con la novena del podcast? Sin responder (pregunta 11).
- **Pantalla:** no consta que esté construida ni dónde se verá exactamente; depende de `ORACIONES_ESTRUCTURA.md`.

### 8.3 Seminarios

**Qué es:** los vídeos de seminarios pasados, de los **cinco tipos** que tiene el boceto J v5: Seminario General · Seminario de Jóvenes · Seminario de Matrimonio · De Discípulos a Apóstoles · Intercesión Profética.

**Cómo llegan los datos (✅, Local):**
- **Una sola playlist** («Seminarios», 29 vídeos) para los cinco tipos. Entran los **no listados («ocultos»)**; los privados, no.
- Cada vídeo es una ficha `video_seminario` con su `sem_anio` y su `sem_mes` y su título «Mes Año».
- **El tipo es una categoría** (`categoria_seminario`) que asigna un programa auxiliar (`tt-sem-tipo-automatico.php`, v1.3) **leyendo el título original** de YouTube (`vid_titulo_yt`). No pisa un tipo puesto a mano. Se rellena todo de una vez entrando como administrador en `…/wp-admin/index.php?tt_sem_tipo_relleno=1`. La nota del 7 de noviembre dice «26 de 29 asignados, 3 a mano»; **el resultado de la v1.3 no está confirmado** en Local.
- Como las fichas llevan `noindex` y no salen en el mapa de páginas, un seminario «oculto» **sigue sin aparecer en Google**.

**Lo que verá la gente (decisión del 17 sept):**

```
BLOQUE «Seminario de Jóvenes»  [Más información]
        │ al pulsar se abre una ventana (Modal) con:
        ▼
   PRÓXIMAS FECHAS ...
   VÍDEOS DE ESTE SEMINARIO
     [▶ miniatura]  [▶ miniatura]     ← los 2 más recientes de ese tipo
                                         (cada uno abre un Lightbox)
   Ver todos los vídeos →  ───────►  ENTRADA NUEVA «Vídeos de los Seminarios»
                                      (nombre provisional) con el catálogo
                                      completo y pestañas por tipo
   Si ese tipo no tiene vídeos: toda esta sección se oculta.
```

- Los dos del panel se sacan con **Post Cards limitado a 2, filtrado por el tipo y ordenado por «Menu Order»** (el filtro por categoría está ✅ probado).
- El panel es un **Modal**, no una capa flotante dibujada encima del bloque: esa capa ya falló sin explicación en Novedades. El Modal no es idéntico al boceto (cubre la pantalla, no solo el bloque); es una diferencia aceptada.
- Abierto a **sustituir uno de los dos por un vídeo elegido a mano** («el más evangelizador»). Sin construir.

**Para quien sube un vídeo de seminario a YouTube** *(la convención que hasta hoy no estaba escrita en ningún sitio)*:
1. Ponerlo en la playlist «Seminarios» (público o no listado).
2. **Escribir el mes y el año, en español, en la primera línea de la descripción** (por ejemplo, «marzo 2025»). Si no está ahí, se busca en el título. Esa regla viene del generador antiguo y la aplica el importador nuevo; se probó con los 29 vídeos reales.
3. Que el título nombre el tipo de seminario como siempre: así el programa auxiliar sabe a qué tipo pertenece. Si no lo reconoce, el tipo se pone a mano en la ficha.

**Qué se pierde si falta el mes y el año:** el vídeo se queda con el título de YouTube, se ordena por la fecha del vídeo y el importador lo anota en sus avisos.

**Lo que sigue sin construir ni decidir** (Sección 18): las pantallas (panel y página del catálogo) · si el Lightbox funciona **dentro** del Modal · cuál es el sexto «tipo» (el tipo `seminario` tiene 6 fichas y el boceto 5 bloques) · si «Seminario General» y «Vida en el Espíritu» son lo mismo · si la miniatura de 320×180 se ve bien en las tarjetas grandes del catálogo (en el panel de 2 columnas basta).

### 8.4 Lenguas

**Decidido:** entra como área del importador cuando acaben las pruebas. **Falta:** crear su tipo de contenido y su categoría, asignarles el grupo `vid_` (Sección 15), añadir su fila (playlist «Lenguas», 11 vídeos) y decidir **cómo se ordena** (sin definir). La playlist «Lenguas Ejército» no entra por ahora.

⚠️ **No confundir** con las «Lenguas del día» de «Conecta cada día», que sube el equipo cada día desde la app y no usan esta playlist.

### 8.5 A mano (sin importador)

Son listas **cerradas** (9 días) que no cambian: se escribe el código del vídeo una vez en una casilla del propio día y se muestra con el elemento YouTube de Avada. Si algún día los vídeos se regrabaran cada año, valdría la pena que los pusiera el importador (cambio aditivo, no obliga a rehacer nada).

| Pieza | Casilla | Estado |
|---|---|---|
| **Novena de la Inmaculada** | `nin_video` (texto), una por día | ✅ códigos listos (tabla abajo) |
| **Sagrados Corazones** | `nsc_video` (texto), una por día. La canción de cierre, «Enséñennos a amar» (`Rwh6N8Q7D1s`), es la misma los 9 días y va fija en el molde | ☑ · códigos de los días sin tener |
| **Espíritu Santo** | `nes_video` (texto), «Vídeo del día» | 🔲 faltan los códigos y **cuál es su canción fija** |

Como un vídeo vivo dentro de un carrusel no es fiable (los de varios días pueden sonar a la vez), **en las novenas el vídeo de cada día irá con un botón «Ver vídeo» que abre el Lightbox** (por probar con el vídeo de una casilla).

**Inmaculada, códigos de los 9 días:**

| Día | Código | Día | Código | Día | Código |
|---|---|---|---|---|---|
| 1 | `cpYSsadmJjs` | 4 | `SsHJUD_1y9k` | 7 | `WzrfZT7BV4g` |
| 2 | `_03D1rmKLkc` | 5 | `Hi5AGpwvT-Y` | 8 | `ACQjtFzhmuQ` |
| 3 | `6zWW4_50nbo` | 6 | `Cf06bNw1K74` | 9 | `VgNZYuKyCiM` |

---

## 9. El importador

*Código: `apps/v2/tt-video-importador.php` (**v1.2**, 7 nov) · Programa auxiliar de tipos de seminario: `apps/v2/tt-sem-tipo-automatico.php` (**v1.3**) · Se instala como snippet PHP de Code Snippets, alcance «Run snippet everywhere».*

### 9.1 Qué hace y qué no hace

| Hace | No hace (a propósito) |
|---|---|
| Lee las playlists de la tabla de ajustes, página por página | **No borra nada.** Una caída o una playlist vacía nunca retira fichas |
| Crea una ficha por vídeo y actualiza las que cambian | No fusiona dos vídeos por tener el mismo título: solo avisa «posible duplicado» |
| Asigna las categorías según las playlists en las que está el vídeo | No toca categorías creadas a mano ni las ajenas a sus filas |
| Calcula el orden de cada ficha | No se ejecuta nunca al cargar una página de la web: los visitantes **nunca** llaman a YouTube |
| Pasa a **borrador** lo que ya no está en ninguna playlist | No republica un borrador puesto a mano |
| Avisa cuando algo se para (semáforo) | No guarda ninguna clave en la base de datos ni en pantalla |
| Con el botón **«Simular»** enseña lo que haría **sin escribir nada** | — |

- **Cuándo se ejecuta:** cada madrugada a las **05:15** (el podcast, a las 04:30), o al pulsar «Sincronizar ahora». Con presupuesto de 25 segundos por pasada y continuación automática al minuto si queda trabajo.
- **Dónde está:** *Herramientas → Vídeos YouTube — Importador*. Allí están la tabla de ajustes, el estado, los avisos, el semáforo y el historial.
- **Cuánto cuesta preguntar a YouTube:** unas **15–20 unidades al día de 10.000** (≈ 0,2 %). Aunque se pulsara «Sincronizar ahora» 100 veces al día, serían unas 1.700. El límite no es un riesgo.

### 9.2 Las protecciones

| Protección | Qué hace |
|---|---|
| **Una caída nunca borra** | Si falla YouTube, la clave, el límite diario o una playlist, no se retira ninguna ficha. Cada playlist falla por separado |
| **Solo se retira si se leyó todo** | Una playlist con una página sin leer **cuenta como fallida** |
| **Freno del 20 %** | Si se fueran a retirar más del 20 % de las fichas publicadas de un área de golpe, **se detiene y avisa**. Aparece el botón **«Aplicar de todos modos»**, que hay que pulsar a propósito. En un área muy pequeña (2 fichas), retirar una ya supera el 20 % y frena |
| **Retirada reversible** | La ficha pasa a borrador. Si el vídeo vuelve a una playlist, se republica con la misma dirección |
| **Segunda sincronización = cero cambios** | Si nada cambió en YouTube, no se escribe nada (solo `vid_comprobado`, como mucho cada 20 horas) |
| **Regla de los 30 días** | Ver Sección 10 |
| **Semáforo** | Se mide **por fila**: días desde su última sincronización correcta. 🟢 menos de 10 días · 🟡 10 o más · 🔴 20 o más. El estado general es el peor de todas las filas |

**Dónde se ve el semáforo:** (1) la pantalla de Estado del importador ✅; (2) un aviso en el escritorio de WordPress ✅; (3) la app de Creators 🔲 (el endpoint `GET /tiritaito/v1/videos/estado`, con la cabecera `X-TT-Token`, ya existe; **falta que Proyecto 5 lo pinte**). Si lo que falla es el reloj de WordPress, (1) y (2) solo los ve quien entre en WordPress, y por eso el semáforo de la app es el que realmente avisa.

### 9.3 La tabla de ajustes: una fila por playlist

Es una sola pantalla. **Añadir una playlist es añadir una fila, sin tocar código.**

| Columna | Qué es | Ejemplo |
|---|---|---|
| Código de la playlist | El `PL…` de YouTube | Sección 5 |
| Área | Música, Vía Crucis, Seminarios… | Música |
| Nombre visible de la categoría | Se separa del nombre en YouTube | «Virgen María» |
| ¿Crea categoría? | Sí, salvo la playlist que contiene todo | **No** en «Tiritaito Music» |
| ¿Incluir ocultos (no listados)? | Seminarios = sí; el resto = no | No |
| Regla de orden | Fecha del vídeo / texto / posición / número del título | Fecha del vídeo ↓ |
| Tipo de contenido y categoría | Qué se crea en WordPress | `cancion` · `categoria_musica` |
| ¿Activa? | Para pausar una fila sin borrarla | Sí |

**Filas previstas hoy:** Música (5: «Tiritaito Music» sin categoría, Espíritu Santo, Virgen María, Navidad, San José) · Vía Crucis (2) · Seminarios (1) · Lenguas (1, cuando se decida). Las reglas de título viven en el código (`tt_vid_titulo_visible`); editarlas desde la pantalla sería una mejora futura.

### 9.4 Qué hago cuando…

| Quiero… | Hago… |
|---|---|
| Añadir una **canción nueva** | La subo a YouTube, la pongo en «Tiritaito Music» y en la playlist de su categoría. Sin título especial: el sufijo se quita solo; si lleva autora, al final como «Hna. …» o «Hno. …». **Solo entran vídeos públicos** en Música. Aparece al día siguiente o con «Sincronizar ahora» |
| Añadir un **vídeo de seminario** | Ver Sección 8.3 (playlist «Seminarios», mes y año en la primera línea de la descripción) |
| Añadir un **Vía Crucis nuevo** (otro año) | Playlist nueva → fila nueva (Carlitos o Álvaro) |
| **Quitar** un vídeo de la web | Lo quito de **todas** las playlists. Pasa a borrador solo; no se borra, y vuelve si lo devuelvo |
| Cambiar un **título** | En YouTube |
| Poner un vídeo **privado** | Se retira en la siguiente sincronización |
| Ver si va todo bien | *Herramientas → Vídeos YouTube — Importador*: color del semáforo, tabla de filas y avisos |
| Probar sin riesgo | Botón «Simular» |
| Sincronizar ya | «Sincronizar ahora» |

### 9.5 Si algo falla

| Síntoma | Qué significa | Qué hacer |
|---|---|---|
| El Estado marca ❌ con «falta…» | Falta un tipo de contenido, una categoría o una casilla (el importador **no los crea**) | Crear lo que dice y volver a mirar |
| Una fila sale en **error** | Playlist privada, código mal copiado o playlist vacía. **Una playlist privada no la lee una clave** | Revisar el código y la privacidad (abrirla en una ventana de incógnito). No se ha retirado nada |
| **Todas** las filas en error | Clave inválida o límite diario agotado | Revisar la clave en `wp-config.php` y la cuota en Google Cloud. No se ha retirado ni cambiado nada |
| «Frenado: iba a retirar N fichas» | Más del 20 % de un área desapareció de las playlists | **Antes de pulsar «Aplicar de todos modos», mirar por qué** (¿se vació una playlist? ¿un vídeo pasó a privado?). Ver pendiente nº 1 |
| Semáforo amarillo o rojo | Hace 10 o 20 días que alguna fila no se sincroniza bien | Mirar el historial; pulsar «Sincronizar ahora»; comprobar el reloj de WordPress |
| Un seminario sin tipo | El título no nombra el tipo de forma reconocible | Asignar el tipo a mano en la ficha |

---

## 10. Reglas de YouTube que hay que cumplir (en sencillo)

*No soy abogado. El riesgo real es bajo, pero cumplirlas es barato. Fuentes: Developer Policies de YouTube y documentación de Google, consultadas el 14 de octubre.*

| Regla | En cristiano | Qué hacemos |
|---|---|---|
| **30 días** | Los datos que copiamos de YouTube (título, miniatura, fecha) solo se pueden guardar **como máximo 30 días sin comprobarlos otra vez, o hay que borrarlos** | El importador los comprueba cada día. A una ficha retirada que lleva **25 días sin poder confirmarse** (el vídeo ya no existe, es privado…) le **vacía el título, la miniatura y la fecha** y deja solo el código y la dirección. Si vuelve, se rellena todo. Avisos a 10 días (amarillo) y 20 (rojo) |
| **Error 153** | El reproductor de YouTube exige saber desde qué web se pide el vídeo. Si el servidor o un complemento de seguridad lo oculta, el vídeo sale en blanco | No usar una política de «referer» estricta. Probado el 27 oct: sin ese problema en Local ni en producción ✅ |
| **«Hecho para niños»** | Si se incrusta un vídeo marcado así, hay que desactivar el rastreo | Activar **«Load Via YouTube No Cookie»** en todos los elementos YouTube. El importador guarda el estado en `vid_kids` |
| **Nada encima del reproductor** | Prohibido poner capas sobre el vídeo o sus controles; mínimo 200×200 px | Tenerlo en cuenta en el diseño (tarjetas con vídeo, ventanas) |
| **Miniaturas** | Descargarlas como imagen propia puede chocar con las reglas y cuenta como dato sujeto a los 30 días | **Se enlazan** por su dirección; no se descargan |
| **Un proyecto de Google por cliente** | No usar el mismo proyecto de la clave de la app de Creators | La clave nueva del importador vive en **un proyecto propio** |
| **Playlist privada** | Una clave no puede leerla | Saldrá como error de esa fila, sin retirar nada |
| **Política de privacidad del sitio** | YouTube pide ciertos enlaces a quien usa su API | ⚠️ **No he podido determinar si una web que solo incrusta vídeos queda dentro**; revisar el texto de privacidad (pregunta 17) |

---

## 11. La clave de YouTube

**Qué es y por qué importa:** es lo que permite al importador preguntar a YouTube. Es **secreta**: vive solo en `wp-config.php` (constante `TT_YT_API_KEY`), nunca en la base de datos, el navegador, el repositorio ni un documento. El nombre de la constante puede constar en los documentos; **el valor, jamás**.

**Decisión mantenida (22 oct):** clave con **restricción de API** (solo «YouTube Data API v3») y **sin restricción de aplicación**. Una clave limitada «a esta web» o «a esta IP» fallaría, porque el servidor no envía esos datos o su IP de salida puede cambiar sin avisar. **Riesgo asumido:** si la clave se filtrara, alguien podría gastar nuestro cupo diario de YouTube (no podría tocar nada más) y se arreglaría rotándola en minutos. **Cuándo revisarlo:** si se cambia a un hosting con IP de salida fija, o si más personas llegan a tener acceso al servidor.

**Pasos** (con la cuenta de Google de Tiritaito, no una personal):
1. Entrar en Google Cloud (`console.cloud.google.com`).
2. Crear un **proyecto nuevo**, solo para el importador (nombre sugerido: «Tiritaito Web — Importador YouTube»).
3. Habilitar «YouTube Data API v3» en ese proyecto.
4. Credenciales → Crear credenciales → **Clave de API**.
5. Restricciones de API: **solo «YouTube Data API v3»**. Restricciones de aplicación: **«Ninguna»**. Guardar.
6. Guardar la clave en `wp-config.php` como `TT_YT_API_KEY`.
7. Comprobar en la página «Cuotas» que aparecen consultas tras la primera sincronización.
8. **Una clave distinta para Local** (mismo proceso, mismo proyecto). **La de producción no se copia nunca a Local ni a copias de seguridad de Local.**
9. Rotación: repetir los pasos 4–6 y borrar la antigua.

**Estado:** la de Local existe ✅. **La de producción: por crear** (es una tarea del plan de lanzamiento). Dos cosas que solo se saben en producción: si Raiola permite editar `wp-config.php` y si bloquea las llamadas del propio sitio hacia `googleapis.com` (prueba A8).

---

## 12. Lo construido en Avada y lo que aprendimos

*Pruebas de Álvaro, 26–27 oct (Avada 7.16.3) y construcción del 11 nov. El editor de Avada Live muestra vacío o con texto de relleno lo dinámico: **solo vale lo que se ve en la web pública**.*

| Necesidad | Qué funciona | Estado |
|---|---|---|
| Lista de fichas con pestañas por categoría | Post Cards con *Show Filters*, en **rejilla** y con «Número de posts» = `-1`. Avada añade su pestaña «All» (por eso «Todas» no es categoría) | ✅ |
| | **Limitaciones:** las pestañas salen en orden alfabético (no hay ajuste de orden ni de pestaña inicial); con **carrusel no hay filtros**; con «Cargar más» el filtro solo ve lo ya cargado | ✅ |
| Filtrar por una categoría concreta | Post Cards, *Include Categorías De Música* (o la que toque) | ✅ |
| Filtrar por el valor de una casilla | Post Cards, «Posts By» = Custom Field. **Funciona** (los documentos antiguos decían que no) | ✅ |
| Orden manual | *Menu Order*, numérico (2 antes que 10) | ✅ |
| Miniatura leída de una casilla de dirección | Elemento Image con «ACF Image», o imagen de fondo de columna con *Custom Field*. Unos 130 kB por 10 miniaturas | ✅ |
| Subtítulo con las categorías | *Post Terms*, en el molde y en el Layout | ✅ |
| **Abrir el vídeo al pulsar** | **Lightbox en una Columna** (con la dirección del vídeo): abre el correcto, suena y se para al cerrar. «Image» y «Post Card Image» no ofrecen Lightbox. En Botón, la opción existe, sin probar con vídeo dinámico | ✅ / 🔲 |
| Ir a la ficha | Enlace «Permalink» | ✅ |
| Panel lateral (Off-Canvas) con el vídeo | Abre el vídeo de esa canción, pero **no lo detiene al cerrar** | ⚠️ |
| Vídeo vivo dentro de un **carrusel** | ❌ Pueden sonar varios a la vez y sigue sonando al deslizar. Usar miniatura + Lightbox | ❌ |
| Vídeo dentro de un **Modal** | Funciona y se para al cerrar (con código fijo). Con código dinámico, sin probar | ✅ / 🔲 |
| Compartir | *Social Sharing* con el dato dinámico de la entrada (por defecto apuntaba a la portada) | ⚠️ falta confirmar «Copiar enlace» |
| Vídeo que no pese | Elemento YouTube con *Video Facade* (solo muestra la miniatura hasta pulsar). **Un iframe pegado en un Code Block no lo aprovecha** | ✅ |
| Aviso de cookies | El módulo de privacidad de Avada está **apagado**, así que no bloquea YouTube | ✅ |

**Lo que Avada NO tiene** (y por eso es la fase D, con código): reproducción continua con cola, mini-reproductor persistente, búsqueda en vivo sobre una rejilla y un enlace profundo `?mp_play=` (este último sustituido por la dirección de cada ficha).

---

## 13. Pruebas

### 13.1 Lo comprobado en Local

**Pruebas de Avada y YouTube (26–27 oct):** A1 filtros ✅ con ajustes · A1-bis casilla ✅ · A2 error 153 ✅ · A4 miniatura ✅ · A5 contenedores ⚠️ · A6 al pulsar ✅/⚠️ · A7 orden ✅ · A9 ficha ⚠️ (la imagen de compartir salía genérica y faltaban `og:description` y `twitter:*`; se resolvió con etiquetas del importador).

**Pruebas del importador (Álvaro, 6 nov, versión v1.0):** pasan **M0** (estado), **M1** (64 fichas, 0 errores), **M2** (segunda sincronización sin cambios), **M3** (playlist rota), **M4** (clave inválida o límite agotado: 5 errores, 0 cambios), **M5** (retirada), **M6** (freno del 20 %), **M8** (código repetido), **M9** («Hágase en mí»), **M10** (62 publicadas y 2 en borrador), **M11** (categorías), **M12** (orden de Música), **M13** (Vía Crucis, 28 fichas), **M14** (Seminarios, 29 fichas), **M15** (edición manual), **M17** (cambios en YouTube), **M18** (tarea diaria; el reloj de WordPress espera a la primera visita), **M19** (regla de los 25 días), **M20** (área nueva sin tocar código), **M22** (`noindex` y fuera del mapa), **M24** (semáforo: amarillo a 11 días y rojo a 21) y una versión sencilla de A3 (la fecha guardada coincide con la de YouTube).

### 13.2 Lo que no está cerrado

| Prueba | Situación |
|---|---|
| **M7** Simulación | ◐ solo se vio el aviso «No se ha escrito nada». Falta comprobar que antes y después las fichas son idénticas |
| **M16** Pasadas parciales | 🔲 no se hizo (toca código; riesgo bajo con unas 120 fichas) |
| **M21** Lectura en Avada | ◐ Post Cards lee las 62 canciones; no se dio por cerrada |
| **M23** Título limpio y autora (v1.2) | ✅ **Seminarios** (Carlitos, 12 nov) · 🔲 **Música sin confirmar** |
| Programa de tipos de seminario (v1.3) | 🔲 resultado sin confirmar |
| **A3** completa | 🔲 comparar `position` y fechas con YouTube; averiguar por qué «Tiritaito Music» tiene 70 vídeos y la web carga 64; ver qué devuelve YouTube con un vídeo **eliminado** |
| **A8** Servidor | 🔲 solo se cierra en producción (o en el ensayo, en el mismo servidor) |
| Hallazgo sin repetir | La simulación dijo una vez «0 retiradas» y la sincronización real retiró 2 (los duplicados). Por el código usan el mismo cálculo, así que lo más probable es un cambio de YouTube entre las dos; no se ha vuelto a ver |
| Etiquetas de compartir duplicadas | Salen las del importador **y** las de Avada (imagen genérica de 501×500). Qué usarán WhatsApp y Telegram **solo lo dirá una prueba real en producción** |
| Pruebas pequeñas de Álvaro | Botón con Lightbox con el vídeo de una casilla · Modal con código de vídeo dinámico · cómo se muestra la pestaña «All» en español · Lightbox en Columna dentro de un Post Cards real con 70 miniaturas |

---

## 14. Para el lanzamiento: qué toca a los vídeos

*Esta sección conecta este documento con `INVESTIGACION_MIGRACION_LANZAMIENTO.md`. Ese informe aún tiene decisiones abiertas (D1–D11, entre ellas raíz o `/blog/`, y si la migración será por «cambio» de base de datos o por «sustitución»). **Aquí no se decide nada de eso**: se señala qué efecto tiene cada cosa sobre los vídeos.*

### 14.1 Por qué los vídeos son delicados en un lanzamiento

Si la web nueva se lleva entera de Local a producción, **los tipos, las categorías, las casillas, los programas y la tarea diaria viajan con ella**, ya montados. Eso es una ventaja, pero tiene una consecuencia: **la tarea diaria del importador se despierta sola en producción**. Sin la clave de producción dará error (sin borrar nada, por diseño); y con la clave de Local en producción se rompe la regla de claves separadas.

### 14.2 Qué hay que decidir o comprobar, por tema

| Tema | Qué pasa con los vídeos | Estado |
|---|---|---|
| **Clave de YouTube** | Producción necesita **la suya**, distinta de la de Local, y creada antes de abrir. Hay que ponerla en `wp-config.php` | 🔲 por crear |
| **Los 4 prefijos de dirección** | Hoy no consta cómo están configurados los prefijos de `cancion`, `viacrucis` y `video_seminario`, ni el de las categorías (hoy `/categoria_musica/…`, y se apuntó «quizá `/musica/`»). Una canción y una página con el mismo título **compiten por la misma dirección** (⚠️ no comprobado). **Decidirlo antes del lanzamiento es gratis; después exige redirecciones de por vida** por cada enlace compartido | 🔲 decisión pendiente (D3 y D4 del plan) |
| **Raíz o `/blog/`** | Si el sitio se queda en `/blog/`, los enlaces escritos como `/algo` se rompen. Revisar el botón «‹ Todas las canciones», los de volver y las tarjetas. Si pasa a la raíz, cambian la dirección de la app y la exclusión de caché | 🔲 depende de D1 |
| **Enlaces `?mp_play=`** de la web vieja | Desaparecen. Si hay códigos QR o enlaces repartidos, llevarán a la portada de Música, no a la canción | 🔲 ¿hay alguno? |
| **Sincronizar antes de exportar** | Hacer una sincronización manual en Local en los días previos, y otra en producción en cuanto esté la clave. Si no, `vid_comprobado` viaja viejo | 🔲 |
| **Resolver el «frenado 22 de 64»** | Antes de exportar | 🔲 pendiente nº 1 |
| **El token de la app** | El token de escritura vive dentro del programa del endpoint central y llegaría a producción con el sitio. **Cambiarlo antes de activar nada**; el endpoint del semáforo lo usa | 🔲 |
| **Cron (tarea programada)** | Si Raiola bloquea `wp-cron.php`, los importadores no se ejecutan a su hora. Comprobarlo en el ensayo (mismo servidor) | 🔲 A8 |
| **Salida a internet** | Que el servidor pueda llamar a `googleapis.com` | 🔲 A8 |
| **Caché (LiteSpeed)** | Puede guardar las fichas y páginas con cambios diarios. Comprobar que el endpoint `/wp-json/tiritaito/` (donde vive el semáforo) está excluido y que las páginas se renuevan | 🔲 |
| **Vista previa al compartir** | Solo se comprueba en producción, en las primeras horas. **El ensayo con contraseña no sirve**: WhatsApp y Telegram no pasan la contraseña | 🔲 |
| **Buscadores** | Las fichas de vídeo ya llevan `noindex` y salen del mapa de páginas. El plan propone decidir lo mismo para episodios y temporadas del podcast (D5). Antes de abrir, comprobar que «Disuadir a los buscadores» no viaja marcado desde Local | ⚠️ |
| **Páginas de prueba** | Limpiar en Local lo que no debe salir (por ejemplo «TT — Santo de prueba», que sigue conteniendo el feed del podcast de San Serafín) | 🔲 |
| **Licencia y versión de Avada** | El ensayo debe llevar la misma versión que Local. Los vídeos dependen de Post Cards y Lightbox | 🔲 |

### 14.3 Orden recomendado para los vídeos el día del lanzamiento

*(Adaptado del orden de snippets del plan, §3.9. Se ensaya igual en el subdominio de pruebas.)*

1. Con los importadores **sin activar**: comprobar que existen los tipos (`cancion`, `viacrucis`, `video_seminario`), las categorías y el grupo de casillas «TT — Vídeo YouTube».
2. Poner la clave de producción en `wp-config.php`.
3. **Sustituir el token** y activar el endpoint central.
4. Activar el importador de vídeos y pulsar **«Simular»**; revisar el resultado.
5. Sincronizar, y mirar el Estado (semáforo en verde).
6. Activar el programa de **tipos de seminario** y lanzar su relleno de una vez (`tt_sem_tipo_relleno=1`).
7. Recién entonces comprobar «Próxima sincronización».
8. Primeras horas: probar la vista previa al compartir con enlaces reales y comprobar que las fichas **no** figuran en el mapa de páginas.

### 14.4 Lo que no se mueve

El importador del **podcast** no se toca. Y **una caída o un error nunca borran fichas**: si algo sale mal el día del lanzamiento, los vídeos no se pierden, solo se detiene la sincronización.

---

## 15. Cómo añadir una playlist o un área nueva

**Una playlist más en un área que ya existe** (por ejemplo, otro año de Vía Crucis):
1. Copiar el código de la playlist de YouTube.
2. En la tabla de ajustes del importador, añadir una fila con el área, el nombre visible de la categoría y la regla de orden.
3. Pulsar **«Simular»**, revisar, y después «Sincronizar ahora».
4. Si hace falta una página o una tarjeta nueva, que Álvaro duplique la de otra lista y cambie la categoría.

**Un área nueva** (Lenguas, o Rincón de Nico si se confirma la opción (a) de la Sección 3.2):
1. **Crear a mano** el tipo de contenido (público; con título, editor, imagen destacada y campos personalizados) y su categoría. El importador **no los crea**; su pantalla de Estado dice qué falta.
2. Asignar el grupo de casillas **«TT — Vídeo YouTube»** al tipo nuevo.
3. Añadir la fila en la tabla de ajustes (código, área, nombre de categoría, ¿crea categoría?, ¿incluye ocultos?, orden, tipo y categoría, activa).
4. «Simular» y luego sincronizar. Revisar el Estado.
5. Construir en Avada con la tarjeta y la ficha de otra área como punto de partida.
6. Anotar el tipo, la categoría y la playlist en este documento (Secciones 5 y 6) y en `00_CORE.md` §3.6.

---

## 16. Si algún día se cambia de opinión

*«Rehace» = obliga a rehacer lo ya construido.*

| Decisión de hoy | Cambio posible | Qué supone | ¿Rehace? |
|---|---|---|---|
| El servidor pregunta a YouTube | Volver al archivo que genera la app | Cambiar la parte lectora del importador; vuelve a hacer falta la clave en el navegador del editor | No |
| Clave sin restricción de aplicación | Restringir por IP | Cinco minutos si la IP de salida es estable; si cambia, la importación se rompe | No |
| Una ficha por vídeo con categorías | Relaciones entre fichas | Cambiar escritor, molde y filtros; se pierde el filtro nativo | **Sí** (alto) |
| Prefijo común `vid_` | Prefijos por área | Crear 3 grupos y 3 moldes. Renombrar casillas con contenido es caro | **Sí, si es después** |
| «Todas» sin categoría | Crear la categoría «Todas» | Pequeño, pero se duplicaría con la pestaña «All» de Avada | No |
| Escaparate nativo (A) | Añadir reproductor con cola (D) | Un bloque de código que lea las fichas (el tipo ya está preparado). Trabajo de Carlitos o Proyecto 11 | **No** (aditivo) |
| Títulos limpios | Volver al título de YouTube | Quitar la regla; reescribe los títulos en la siguiente sincronización | No |
| Fichas compartibles | Sin página propia | **Post Cards dejaría de verlas**: es lo que Avada necesita | **Sí** (alto) |
| `noindex` en las fichas | Indexarlas | Quitar el `noindex` y el filtro del mapa | No |
| Vídeos de novenas a mano | Que los ponga el importador | Añadir un segundo modo | No (aditivo) |
| Retirada por borrador (a 25 días, vaciar) | Papelera | Cambiar la acción | No |
| Panel de Seminarios con 2 vídeos automáticos | Uno elegido a mano | Una casilla más en la ficha del seminario | No (aditivo) |

**Lo caro de cambiar:** pasar al modelo de relaciones, renombrar casillas con contenido real y quitar la página propia de cada ficha.

---

## 17. Diseño: lo decidido y lo que decide Carlota

**Decidido:** Dirección 1 con un hueco reservado para el futuro reproductor con cola · tarjeta con la miniatura 16:9 · esquinas de 15 px · filtros = categorías (con «All» de Avada) · canción destacada = la más reciente, automática · cada vídeo tiene su página compartible.

**Lo que Carlota decide cuando todo funcione (C16):**

| # | Decisión | Estado |
|---|---|---|
| K2 | Qué manda en el hero de la portada de Música (acuarela, foto del grupo, logo, vídeo corto mudo) | 🔲 |
| K5 | Qué pasa al pulsar una tarjeta | ✅ resuelto en la construcción: abre la **ficha**; allí, la fila de la pista abre el **Lightbox**. El panel lateral solo sirve para compartir |
| K10 | Plantilla de la ficha de canción | ◐ construida (11 nov); falta la **aprobación visual** y decidir si el título usa la fuente de marca o la negrita del cuerpo, y si se muestra la autora (`vid_autora`) en lugar del «Tiritaito» escrito a mano |
| K11 | El hueco del reproductor con cola | 🔲 |
| — | Aspecto de la página del catálogo de Seminarios y del panel «Más información» (boceto J v5, ya dibujado) | 🔲 construir |

**Lo que debe tener presente:** nada por encima del reproductor ni de sus controles; sin «Clase CSS»; cristal esmerilado y filtros sobre carrusel, sin confirmar. **Aviso de producto del estudio de Seminarios**, para Hna C: con este diseño los vídeos quedan **a dos pasos** (pulsar «Más información» y luego «Ver todos»). Es una decisión legítima si los vídeos son material de apoyo; conviene que sea a propósito. La entrada nueva con el catálogo completo ya mitiga esto.

---

## 18. Pendientes y preguntas abiertas

### 18.1 Pendientes (cosas por hacer)

| # | Pendiente | Quién |
|---|---|---|
| 1 | **Entender el «frenado, iba a retirar 22 de 64 fichas»** de Música (es más del 20 %, por eso saltó el freno). No se ha pulsado «Aplicar de todos modos». Mirar el historial y la pantalla de Estado antes de nada. **Sin causa conocida: no la doy por explicada** | Carlitos, con Álvaro |
| 2 | Confirmar en Música la v1.2 (títulos limpios y `vid_autora`) y el resultado del programa de tipos v1.3 | Álvaro |
| 3 | **Seminarios: construir** el panel «Más información» (Modal con 2 vídeos), la entrada «Vídeos de los Seminarios» y el enlace «Ver todos». Probar antes que el Lightbox funciona **dentro** del Modal | Álvaro (diseño: Carlota) |
| 4 | **Vía Crucis: construir** su pantalla (depende de Oraciones) | Álvaro |
| 5 | **Producción:** crear la clave de YouTube, hacer A8 (editar `wp-config.php`, salida a `googleapis.com`, reloj) y decidir cron real | Álvaro / Carlitos |
| 6 | **Decidir los prefijos de dirección** (`cancion`, `viacrucis`, `video_seminario`, categorías) **antes de lanzar** | Carlitos |
| 7 | Mirar en YouTube el vídeo no listado de «Con Jesús hacia la Cruz» y el de la otra playlist de Vía Crucis (¿falta una estación?) | Carlitos |
| 8 | Vista previa real al compartir (WhatsApp, Telegram) y qué bloque de etiquetas usan; solo en producción | Álvaro / Carlitos |
| 9 | Revisar en móvil y tablet la portada, «Todas las canciones», una lista y la ficha; confirmar el Lightbox en móvil real | Álvaro |
| 10 | Las 8 listas reales de Música (nombre, frase y portada) y añadir «Tiritaito Music» al menú | Álvaro / Carlitos |
| 11 | Crear el área de **Lenguas** (cuando acaben las pruebas) y decidir su orden | Álvaro |
| 12 | **Semáforo en la app de Creators.** Además, comprobar contra el programa del endpoint central cómo se guarda `TT_WRITE_TOKEN` (si no, el endpoint responde 503) y que las cabeceras de otros orígenes (CORS) están permitidas en esta ruta | Proyecto 5 / Carlitos |
| 13 | LiteSpeed puede guardar en caché las fichas en producción: comprobar | Álvaro |
| 14 | Novenas a mano: códigos de Sagrados Corazones y Espíritu Santo y la canción fija de Espíritu Santo | Carlitos |
| 15 | Limpiar el tipo antiguo `videos_seminarios` si sigue existiendo | Álvaro |
| 16 | Cuando las tres áreas estén en producción, **retirar** de la app de Creators los generadores de YouTube y la lista de playlists del navegador; las opciones `tt_youtube_json_url`, `tt_seminarios_json_url` y `tt_viacrucis_json_url` dejan de hacer falta | Proyecto 5 |
| 17 | Corregir `CATALOGO_ELEMENTOS_AVADA.md`, `GUIA_AVADA_LOCAL.md` y `HOMBRES_DE_DIOS_ESTRUCTURA.md` con lo aprendido en las pruebas de Avada (Sección 12) | Proyecto 2 |
| 18 | Opcionales, más adelante: casilla `vid_anio`, recorte 1:1 de la portada (Proyecto 11), reproductor con cola (fase D) | Carlitos / Proyecto 11 |

### 18.2 Preguntas abiertas

| # | Pregunta | Bloquea a | Quién decide |
|---|---|---|---|
| 1 | **El Rincón de Nico: ¿usa el importador de vídeos (tiene playlist de YouTube) o es solo podcast?** Los documentos dicen podcast | Si hay una fila más en el importador (Sección 3.2) | Carlitos |
| 2 | ¿«Ver todos los vídeos →» lleva a la entrada nueva «Vídeos de los Seminarios»? (decidido así el 17 sept; confirmar el nombre definitivo) | Construcción de Seminarios | Carlitos / Hna C |
| 3 | Los dos vídeos del panel: ¿siempre los **dos más recientes** o a veces uno elegido a mano? | Si hace falta una casilla | Hna C |
| 4 | ¿Los vídeos de seminario importan lo bastante como para tener sitio más visible que «dos pasos»? | Diseño de la página | Hna C |
| 5 | ¿Qué se ve en un tipo de seminario sin vídeos? Decidido: se oculta la sección. Falta confirmarlo al construir | Estado vacío | Hna C |
| 6 | ¿Por qué «Tiritaito Music» tiene 70 vídeos en YouTube y la web carga 64? Se sabe que 2 son no listados y no entran en Música; **el resto no está explicado** | Que no falten canciones | Prueba A3 |
| 7 | ¿Falta una estación en cada playlist de Vía Crucis (la no listada)? | Vía Crucis completo | Carlitos |
| 8 | ¿Qué devuelve YouTube con un vídeo **eliminado** o privado dentro de una playlist? (el programa solo descarta «privado» y los títulos «Deleted video» / «Private video») | Regla de retiradas | A3 y M17 |
| 9 | ¿Se cumple el orden «Mes Año» cuando alguien se olvida de escribir el mes y el año? Se avisa, pero ¿quién mira los avisos? | Fiabilidad del orden de Seminarios | Carlitos |
| 10 | ¿Las playlists «Espíritu Santo» y «Navidad» son las dos sin identificar de la Sección 5? ¿Cuál es cuál? | Documentación | Carlitos |
| 11 | ¿«Con Jesús hacia la Cruz – Novena a la Divina Misericordia» está relacionado con la novena del podcast? | Vía Crucis y Novenas | Carlitos |
| 12 | El boceto tiene **5** tipos de seminario y el tipo `seminario` tiene **6** fichas: ¿cuál es el sexto? ¿«Seminario General» y «Vida en el Espíritu» son lo mismo? | El mapeo tipo ↔ categoría | Carlitos |
| 13 | ¿La miniatura de 320×180 se ve bien en las tarjetas grandes del catálogo de Seminarios? (en el panel de 2 columnas basta) | Calidad de imagen | Carlota / Álvaro |
| 14 | ¿«Pentecostés» es la playlist de los vídeos del día de Espíritu Santo? | Novena del Espíritu Santo | Carlitos |
| 15 | ¿Hay códigos QR o enlaces `?mp_play=` repartidos de la web vieja? | Redirecciones | Carlitos |
| 16 | ¿Se puede bajar el cupo diario del proyecto de Google para limitar el daño de una filtración? | Mitigación | Carlitos, al crear la clave |
| 17 | ¿Cumple la política de privacidad del sitio lo que pide YouTube a quien usa su API? | Cumplimiento | Hna C |
| 18 | ¿Cómo se muestra la pestaña «All» de Avada en español y cómo se cambia? | Aspecto de los filtros | Álvaro |
| 19 | ¿Se importa algún día «Lenguas Ejército» para la página del Ejército? | Página del Ejército | Carlitos / Hna C |

### 18.3 Próximos pasos

1. **Carlitos:** responder la pregunta 1 (Rincón de Nico) y mirar el aviso del «frenado 22 de 64» (pendiente 1).
2. **Álvaro:** confirmar Música v1.2 y el programa de tipos; hacer las pruebas pequeñas de la Sección 13.2, **incluida la del Lightbox dentro del Modal**, que desbloquea Seminarios.
3. **Carlitos + Álvaro:** decidir los prefijos de dirección **antes** de exportar (pendiente 6) y crear la clave de producción (pendiente 5), en el orden del plan de lanzamiento.
4. **Álvaro (con Carlota):** construir las pantallas de Seminarios y de Vía Crucis.
5. **Proyecto 5:** pintar el semáforo en la app (pendiente 12).
6. **Proyecto 2:** cuando se confirme este documento, pasar a histórico lo que ya recoge (Secciones 6.8 y 17 del estudio de Música, y el estudio de Seminarios), añadir su fila al `README.md` y apuntarlo en `00_CORE.md` §3.6.

---

## 19. Documentos relacionados

`TIRITAITO_MUSIC_WEB_NUEVA.md` (estudio de origen, se queda como constancia) · `SEMINARIOS_VIDEOS_WEB_NUEVA.md` (estudio de origen de Seminarios) · `INVESTIGACION_MIGRACION_LANZAMIENTO.md` (plan de lanzamiento) · `PODCAST_ESTRUCTURA.md` (el otro importador, el del podcast; modelo de este documento) · `ORACIONES_ESTRUCTURA.md` (Vía Crucis, Lenguas y novenas) · `conecta_estructura.md` (Lenguas del día, Ejército, Salmo) · `00_CORE.md` §3.6 y §4 (nombres y endpoint) · `04_ENTORNO_LOCAL.md` (Local frente a producción) · `ALCANCE_WEB_NUEVA.md` §4.C, §4.F y §4.G · `CATALOGO_ELEMENTOS_AVADA.md` · `apps/v2/tt-video-importador.php` · `apps/v2/tt-sem-tipo-automatico.php`.

---

*Para la mayor gloria de Dios · tiritaito.com*

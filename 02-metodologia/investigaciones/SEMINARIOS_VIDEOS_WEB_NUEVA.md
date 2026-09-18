# TIRITAITO.COM — Los vídeos de los seminarios en la Web Nueva
**Qué existe hoy, qué pide el boceto J v5, qué puede hacer Avada sin código, y dónde deberían vivir los vídeos**
*Investigación de Proyecto 2 (Hno C) — 16 de septiembre de 2026*
*Fuentes: `boceto-j-seminarios-v5.html` (real, leído línea por línea en esta sesión) · `apps/v2/tiritaito-creators-v2-01.html` (código real del generador de JSON) · documentación oficial verificada hoy en `classic.avada.com` y `avada.com` · referencia oficial de la YouTube Data API v3 · `TIRITAITO_MUSIC_WEB_NUEVA.md` (16 septiembre 2026)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde a la pregunta abierta **#3 de `ALCANCE_WEB_NUEVA.md`** — *"¿cómo se integra el
sistema de vídeos de seminarios pasados con el nuevo formato visual?"* — que lleva abierta
desde julio de 2026, y que el boceto J v5 acaba de convertir en urgente porque ya dibuja el
hueco donde van.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| El mismo análisis para la música | `TIRITAITO_MUSIC_WEB_NUEVA.md` |
| Qué elemento de Avada resuelve una necesidad en general | `CATALOGO_ELEMENTOS_AVADA.md` |
| Dónde vive cada pieza de contenido ya decidida | `METODOLOGIA_CONSTRUCCION.md` |
| Quién construye qué desde el 6 de septiembre de 2026 | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Secciones 2.3 y 2.4 |
| **Dónde viven los vídeos de seminarios y cómo se pintan en la página** | **Este documento** |

⚠️ **Este documento no decide nada.** Mide, pone precio y señala lo que no sé. Las decisiones
son de Carlitos y Hna C, y están al final como preguntas abiertas.

⚠️ **Hay un tramo de la cadena que no he podido verificar** — el módulo real de la web vieja
que pinta los vídeos. Lo digo en la Sección 3.4 en vez de rellenarlo con suposiciones. Todo
lo demás está contrastado contra archivo real o documentación oficial.

---

## 1. Dónde encaja: tres documentos esperando esta respuesta

| Documento | Lo que dice hoy | Estado |
|---|---|---|
| `ALCANCE_WEB_NUEVA.md` Sección 4.C y pregunta abierta #3 | *"Vídeos de seminarios anteriores — 🔲 existe ya en la web vieja un sistema de reproductor de playlist + generador de JSON de YouTube (…`tt_seminarios_json_url`…); falta definir cómo se integra con el nuevo formato visual"* | Abierta desde julio 2026 (2 meses) |
| `METODOLOGIA_CONSTRUCCION.md` Sección 3, "Qué hacemos" | *"Seminarios — vídeos pasados · Snippet ya existente (JSON/playlist) · 🔲 Método de integración con el nuevo formato visual sin definir"* | La misma pregunta, escrita en otro sitio |
| `CATALOGO_ELEMENTOS_AVADA.md` Sección 4 | *"🔲 Sin confirmar todavía si Post Cards cubre bien el listado de 'Seminarios pasados' … si esa sección necesita filtrar de verdad, ahí sí haría falta el hook"* | Depende de una prueba en Local que nadie ha hecho |

**Lo nuevo es que ya existe un boceto aprobado que reserva el sitio.** Eso cambia la
naturaleza de la pregunta: ya no es *"¿dónde ponemos los vídeos?"* sino *"¿con qué llenamos
este hueco concreto, y de dónde sale el dato?"*.

---

## 2. Qué pide el boceto J v5 — lectura literal

*Leído del archivo real, no de la descripción.*

### 2.1 La estructura completa de la página

```
┌──────────────────────────────────────────────────────┐
│ CABECERA sticky con acuarela — "Seminarios"          │
│ (se encoge al hacer scroll, tipo header_sticky_      │
│  shrinkage de Avada)                                 │
├──────────────────────────────────────────────────────┤
│ ▓▓▓ BLOQUE 1 · Seminario General            (380px)  │
│ ▓▓▓ foto de fondo + título + [Más información]       │
│ ▓▓▓                                                  │
│      ↓ al pulsar, la foto se oscurece al 72 % y      │
│        aparece este panel centrado encima:           │
│      ┌────────────────────────────────────┐          │
│      │  Seminario General            [✕]  │          │
│      │  PRÓXIMAS FECHAS                   │          │
│      │   Vida en el Espíritu   14–16 nov  │          │
│      │   Vida en el Espíritu    9–11 ene  │          │
│      │                                    │          │
│      │  VÍDEOS DE ESTE SEMINARIO      ←←← │ ESTO     │
│      │   ┌──────┐ ┌──────┐                │          │
│      │   │  ▶   │ │  ▶   │   ← 2 col 16:9 │          │
│      │   └──────┘ └──────┘                │          │
│      │   Ver todos los vídeos →           │          │
│      └────────────────────────────────────┘          │
├──────────────────────────────────────────────────────┤
│ ▓▓▓ BLOQUE 2 · Seminario de Jóvenes                  │
│ ▓▓▓ BLOQUE 3 · Seminario de Matrimonio               │
│ ▓▓▓ BLOQUE 4 · De Discípulos a Apóstoles             │
│ ▓▓▓ BLOQUE 5 · Intercesión Profética                 │
│      (los cinco con el mismo panel y su rejilla)     │
├──────────────────────────────────────────────────────┤
│ INFORMACIÓN GENERAL — precio · lugar · contacto      │
├──────────────────────────────────────────────────────┤
│ AGENDA — lista cronológica, sin filtro (decisión     │
│ de Hna C), con "Ver toda la agenda →"                │
└──────────────────────────────────────────────────────┘
```

### 2.2 Lo que el boceto dice exactamente sobre los vídeos

| Detalle | Valor en el archivo |
|---|---|
| Dónde viven | Dentro del panel `.sem-panel-info` de cada bloque — **contenido oculto hasta pulsar "Más información"** |
| Cuántos se ven | **Dos** miniaturas por seminario (`.sem-videos-grid`, `repeat(2, 1fr)`, `gap: 8px`) |
| Formato de cada miniatura | `aspect-ratio: 16/9`, `border-radius: var(--tt-r-xs)` = **8px**, fondo `rgba(255,255,255,.12)`, con un `▶` centrado al 80 % de opacidad |
| Enlace de salida | `Ver todos los vídeos →`, subrayado, centrado, 12,5px, blanco, **`href="#"` — sin destino definido** |
| Contexto visual | Sobre fondo negro al 72 %, texto blanco, ancho máximo del panel 480px |
| En móvil | El título cambia a `Vídeos` y el enlace a `Ver todos →`. ⚠️ **Solo el bloque "General" lleva vídeos en la vista móvil; los otros cuatro solo tienen fechas** |

### 2.3 Las cinco preguntas que el boceto deja abiertas

Ninguna es un defecto del boceto — son decisiones que corresponden a otra fase. Pero hay
que responderlas antes de que Álvaro construya nada.

1. **¿A dónde lleva "Ver todos los vídeos →"?** Es el `href="#"` más importante del archivo.
2. **¿Qué dos vídeos se muestran?** ¿Los dos más recientes, dos elegidos a mano, o los dos primeros de la playlist?
3. **¿Qué pasa al pulsar una miniatura?** ¿Lightbox, YouTube, o reproducción en el propio panel?
4. **¿Los cuatro bloques de móvil deben llevar vídeos también?** (asumo que sí, y que es una simplificación de maquetado, pero no lo doy por hecho)
5. **¿Qué se ve si un seminario todavía no tiene vídeos?** El bloque queda con un hueco de dos rectángulos vacíos.

### 2.4 Un aviso de producto, no técnico

Con este diseño, **los vídeos quedan a dos clics de profundidad**: hay que pulsar "Más
información" y después "Ver todos". Si los vídeos de seminarios son un activo de
evangelización — gente que no pudo venir, gente que quiere repasar — este diseño los
esconde. No digo que esté mal: decir *"los vídeos son material de apoyo, lo que importa es
que la gente venga al próximo"* es una decisión de producto perfectamente legítima. Solo
conviene tomarla a propósito y no por herencia del boceto.

🔲 **Esto es para Hna C, no para mí.** Si la respuesta es "sí, los vídeos importan", el
boceto probablemente necesita una sección propia de vídeos además de las dos miniaturas del
panel.

---

## 3. La cadena de datos real, hoy

### 3.1 El generador de la app — verificado línea por línea

*Contra `apps/v2/tiritaito-creators-v2-01.html`, no de memoria.*

```
EDITOR (Tiritaito for Creators → Recursos → Generadores YouTube → Seminarios)
   │
   │ 1. Guarda una API Key de YouTube en el localStorage de SU navegador
   │ 2. Añade N playlists (Nombre + ID de playlist), guardadas también
   │    en el localStorage de SU navegador
   │ 3. Pulsa "Generar y publicar"
   ▼
YOUTUBE DATA API v3  ·  playlistItems?part=snippet,status&maxResults=50
   │
   │ Para seminarios: getPlaylistItems(pl.id, soloPublicos = FALSE)
   │   → descarta solo los privados; los "ocultos" (unlisted) SÍ entran
   │ Para cada vídeo extrae: id · title · thumb (mqdefault) · description
   │ Después llama a extraerFecha(description, title) buscando "mes año"
   │ Ordena: año descendente, mes ascendente
   ▼
Seminarios.json  → POST /tiritaito/v1/subir  → Biblioteca de Medios
   │
   ▼
POST /tiritaito/v1/datos  → guarda la URL en wp_options.tt_seminarios_json_url
   │
   ▼
??? EL MÓDULO DE LA WEB QUE LO PINTA  ← ver Sección 3.4
```

### 3.2 La estructura del JSON

```json
{
  "playlists": [
    {
      "name": "Seminario de Vida en el Espíritu",
      "tracks": [
        {
          "id": "dQw4w9WgXcQ",
          "title": "Charla 1 — Vida en el Espíritu · marzo 2025",
          "thumb": "https://img.youtube.com/vi/dQw4w9WgXcQ/mqdefault.jpg",
          "year": 2025,
          "month": 3
        }
      ]
    }
  ]
}
```

**Nota importante:** Música y Seminarios comparten el mismo envoltorio (`playlists[].tracks[]`)
pero **no los mismos campos**. Seminarios añade `year` y `month`; Música no los tiene. Y la
ordenación es distinta: Música usa `ordenMusica()` (ordinales en español, romanos, días,
meses); Seminarios usa la fecha extraída del texto. Cualquier pieza que consuma los dos JSON
tiene que saberlo.

### 3.3 Seis hallazgos sobre el generador — todos verificados en el código real

| # | Hallazgo | Por qué importa | Gravedad |
|---|---|---|---|
| 1 | **La fecha se adivina leyendo texto.** `extraerFecha()` busca un patrón `mes año` (ej. "marzo 2025") **solo en la primera línea de la descripción**, y si no lo encuentra, en el título. Si no está en ninguno de los dos, el vídeo se queda con `year: null, month: null` | El orden de los vídeos depende de que quien sube el vídeo a YouTube escriba el mes y el año, en español, en el sitio correcto. Es una convención humana no escrita en ningún documento del proyecto. Un despiste manda el vídeo al final de la lista sin avisar | 🔴 Alta |
| 2 | **Existe una alternativa robusta que no se está usando.** ✅ Confirmado hoy contra la referencia oficial de la YouTube Data API v3: `playlistItems.contentDetails.videoPublishedAt` devuelve *"the date and time that the video was published to YouTube"* en formato ISO 8601. Hoy la app pide `part=snippet,status`; bastaría con pedir `part=snippet,status,contentDetails` | No sustituye al texto (un seminario de marzo puede subirse en junio), pero sirve de **respaldo fiable** cuando el texto no trae fecha, en vez de dejar el vídeo sin ordenar. ⚠️ Ojo con confundirlo con `snippet.publishedAt`, que es cuándo se **añadió a la playlist**, no cuándo se publicó el vídeo | 🟠 Media |
| 3 | **El orden es mixto y probablemente no es el que se quiere.** `sort` hace **año descendente, mes ascendente**: primero 2026-enero → 2026-diciembre, luego 2025-enero → 2025-diciembre… | El resultado no es "lo más reciente primero", que es lo que uno esperaría de un listado de vídeos pasados. Dentro de cada año va del más antiguo al más nuevo. Puede ser deliberado (seguir el orden del curso), pero no está escrito en ningún sitio | 🟠 Media |
| 4 | **Los vídeos sin fecha caen al final, en silencio.** `(b.year \|\| 0) - (a.year \|\| 0)` convierte `null` en `0`, y `(a.month \|\| 13)` los manda al final del año | Un vídeo mal etiquetado no desaparece, pero se hunde. Y nadie recibe ningún aviso | 🟡 Baja |
| 5 | **La miniatura se pide en `mqdefault`** (320×180 px) | Suficiente para la rejilla de 2 columnas del panel (unos 230px de ancho). **Insuficiente si "Ver todos" muestra tarjetas grandes.** Existe `hqdefault` (480×360, con bandas negras) y `maxresdefault` (1280×720, que no todos los vídeos tienen) | 🟡 Baja |
| 6 | **El JSON no guarda el `privacyStatus`.** La app lo consulta para filtrar, y luego lo tira | Es exactamente el hallazgo #2 del documento de Música: si el módulo de la web quiere volver a comprobar que un vídeo sigue visible, tiene que preguntárselo a YouTube desde el navegador de cada visitante. El dato ya estaba en la mano y se descartó | 🟠 Media |

### 3.4 El tramo que no puedo verificar desde aquí

**No tengo el código del módulo que pinta los vídeos de seminarios en la web vieja.** Con
Tiritaito Music sí lo tuve delante y por eso aquel diagnóstico es firme; aquí no.

Lo que sé, y de dónde lo sé:

| Dato | Certeza |
|---|---|
| Existe un módulo que consume JSON de playlists y pinta carruseles agrupados, con un caso de uso explícito de Seminarios (agrupación por tipo de seminario con orden de grupo fijo) | ⚠️ De trabajo previo del propio Carlitos en otra cuenta, no verificado contra el archivo real del repositorio |
| Ese módulo usa un patrón de atributo `data-grupos` para configurar las agrupaciones sin tocar el código | ⚠️ Misma procedencia |
| El módulo lee la `description` de YouTube antes que el título para detectar el grupo | ⚠️ Misma procedencia |
| Si lee `tt_seminarios_json_url` desde el endpoint, o si tiene la URL escrita a mano | ❓ **No lo sé, y es lo más importante** |
| Si filtra vídeos privados llamando a oEmbed desde el navegador, como hace Música | ❓ No lo sé |

🔴 **La pregunta que hay que responder antes que ninguna otra:** en Música, la `DATA_URL`
estaba **escrita a mano** en el código, apuntando a un archivo concreto de
`uploads/2026/09/`. Como cada "Generar y publicar" sube un archivo nuevo y actualiza
`tt_youtube_json_url`, **el circuito app → web estaba roto en el último tramo**: la web
seguía sirviendo el JSON viejo.

**Si el módulo de seminarios tiene el mismo defecto, los vídeos que se ven hoy en la web
vieja pueden llevar meses sin actualizarse sin que nadie lo sepa.** Es comprobable en dos
minutos: abrir el módulo y mirar si hay una URL literal o una llamada a
`/tiritaito/v1/datos`.

**Lo que necesito para cerrar esta sección:** el código del módulo (Code Snippets → el
snippet de vídeos de seminarios) y, si es posible, el `Seminarios.json` real o al menos el
número de playlists y de vídeos.

---

## 4. La coincidencia que lo cambia todo

Al cruzar el boceto con el generador aparece algo que en Música no pasaba:

> **El "nombre de playlist" del JSON y el "bloque" del boceto son exactamente la misma cosa.**

En Música, las playlists son categorías temáticas (Navidad, Adviento, Alabanza) que no se
corresponden con ninguna estructura de la web. En Seminarios, la playlist **es** el tipo de
seminario, y el boceto ya tiene cinco bloques que son esos cinco tipos:

```
     JSON (hoy)                        BOCETO J v5 (nuevo)
 ┌────────────────────┐            ┌────────────────────────┐
 │ playlists[0].name  │ ═════════► │ Bloque 1 · General      │
 │ playlists[1].name  │ ═════════► │ Bloque 2 · Jóvenes      │
 │ playlists[2].name  │ ═════════► │ Bloque 3 · Matrimonio   │
 │ playlists[3].name  │ ═════════► │ Bloque 4 · Discípulos   │
 │ playlists[4].name  │ ═════════► │ Bloque 5 · Intercesión  │
 └────────────────────┘            └────────────────────────┘
```

**Por qué importa, en una frase:** la taxonomía ya existe, en los tres sitios a la vez
(YouTube, el JSON y el diseño). Si algún día los vídeos pasan a ser contenido de WordPress,
la categoría a la que pertenece cada vídeo **no hay que inventarla ni decidirla** — ya está
escrita en el nombre de la playlist.

⚠️ **Con una condición:** que los nombres coincidan exactamente. Si en YouTube la playlist
se llama "Seminario Vida en el Espíritu 2025" y el bloque se llama "Seminario General", la
correspondencia se rompe. Hay que fijar los cinco nombres canónicos una vez y respetarlos.

🔲 **Y un matiz sin resolver:** el bloque se llama "Seminario General" pero sus dos fechas
son de "Vida en el Espíritu". ¿Son la misma cosa con dos nombres, o el Seminario General
agrupa varias ediciones distintas? Si es lo segundo, un bloque puede tener **varias**
playlists, y el mapeo deja de ser 1 a 1.

---

## 5. Qué puede hacer Avada de forma nativa

*Todo lo de esta tabla está verificado hoy contra documentación oficial. Lo marcado 🔲 hay
que probarlo en Local antes de prometérselo a nadie.*

| Necesidad del boceto | Elemento nativo | Certeza | Nota |
|---|---|---|---|
| El panel que oscurece el bloque al pulsar "Más información" | **Modal Element** + Botón con "Modal Window Anchor" | ✅ Confirmado | *"Modal boxes are hidden by default and can be triggered by a Menu item, a Button Element, or a Modal Text/HTML Link Element… Avada Elements can be used inside it"*. ⚠️ Visualmente **no es idéntico**: el Modal de Avada es una ventana centrada sobre la pantalla; el boceto dibuja un velo que cubre exactamente ese bloque. Ver Sección 8.0 |
| Una miniatura que al pulsarla abre el vídeo en grande, sin salir de la página | **Columna → `Link URL` + `Link Target = Lightbox`** | ✅ Confirmado, y es el camino más limpio | *"Simply add your video URL in the Link URL field, and set the Link Target Field to Lightbox. The whole column then becomes a link, and clicking on it brings the video up in a Lightbox, ready to play."* Nuestro lightbox global ya está configurado (Metro White, opacidad 0.90, flechas, deeplinking) |
| Lo mismo, pero como pieza cerrada con miniatura, título y descripción | **Lightbox Element**, tipo de contenido Video | ✅ Confirmado | *"The Lightbox Element has a Video Content type, and when this is selected, you can add any video URL here, as well as control a thumbnail, title and description"* — es, literalmente, una tarjeta de vídeo nativa |
| Un botón que abre un vídeo | **Botón → `Button URL` = URL del vídeo + `Button Target = Lightbox`** | ✅ Confirmado | Sirve para "Ver todos" si se decide que abra un vídeo o una playlist |
| Un vídeo de YouTube que no lastre la carga | **YouTube Element** con `Video Facade = On` | ✅ Confirmado, y ya activado globalmente en nuestro Local | Carga solo la miniatura hasta que se pulsa play. ⚠️ **El Facade es del elemento de Avada: un `<iframe>` pegado a mano en un Code Block NO lo aprovecha** |
| Elegir el tamaño de la miniatura del vídeo | YouTube Element → **`Thumbnail Size`** | ✅ Confirmado | *"The sizes are YouTube standard, and not all sizes are available for every video"* — resuelve el hallazgo #5 de la Sección 3.3 sin tocar el generador |
| Reproducir una **playlist entera** de YouTube, con su cola y su siguiente/anterior | YouTube Element → **`Additional API Parameter`** con `&listType=playlist&list=PL…` | 🔲 **Muy probable, sin confirmar** | La propia documentación de Avada documenta los parámetros `list` y `listType`, con el ejemplo `youtube.com/embed?listType=playlist&list=PLC77007E23FF423C6`, y advierte que *"if you specify values for the list and listType parameters, the IFrame embed URL does not need to specify a video ID"*. Pero el campo **Video ID del elemento es obligatorio** en Avada. Lo esperable es que `VIDEO_ID + &list=PL…` cargue ese vídeo dentro de la playlist y encadene los siguientes. **Hay que probarlo, y comprobar que el Video Facade no se traga el parámetro al construir el iframe** |
| Una rejilla o carrusel de vídeos, si cada vídeo es contenido de WordPress | **Post Cards**, Layout = Grid / Carousel / Masonry / Marquee / Coverflow / Stacking Cards / Slider | ✅ Confirmado | |
| Pestañas de filtro por tipo de seminario sobre esa rejilla | Post Cards → **`Show Filters`** | ✅ Confirmado en la doc | Con control completo de tipografía, altura, alineación, color activo y borde del activo |
| Filtrar la rejilla por el valor de un campo ACF | Post Cards → **`Custom Field - Name` + `Value Comparison` + `Value`** | ⚠️ **Segunda confirmación independiente** — contradice nuestra documentación | Ver Sección 11.1 |
| Ordenar por un campo propio (año, mes, fecha) | Post Cards → `Order By: Custom Field` + `Custom Field Name` + `Custom Field Type` | ✅ Confirmado, ya probado en Novedades | |
| Vista rápida de un vídeo sin cambiar de página | Post Cards → **`Link Off Canvas`** | 🔲 Documentado, sin probar | *"This allows you to fill the Off Canvas dynamic data endpoints with the data of the single Post Cards"* |
| Recorrer un repetidor de ACF como si fueran tarjetas | Post Cards → `Content Source` = **`Repeater Field`** | 🔲 Documentado, sin probar | Permitiría **una sola entrada por seminario** con un repetidor de vídeos dentro, sin crear un CPT. Es la opción más ligera si se va a WordPress |
| Poner el ID de YouTube de un campo ACF dentro de un YouTube Element | Dynamic Content sobre el campo `Video ID or Url` | 🔲 **Sin confirmar — y es crítico** | Avada admite Dynamic Data con ACF (`ACF Text`, `ACF Select`, `ACF Link`, `ACF File`, `ACF Image`, `ACF Gallery`), pero *"what will depend on the builder option type selected"*. Si este campo concreto no admite contenido dinámico, la ruta "cada vídeo es una entrada + YouTube Element" se cae, y hay que usar el camino de Columna → Lightbox (que sí funciona con un campo de URL) |

### 5.1 Lo que Avada no tiene, de ninguna manera

1. **Leer un JSON externo y pintar contenido con él.** No existe elemento nativo. Es doctrina ya escrita del proyecto (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2.4), y los vídeos de seminarios son uno de los tres ejemplos que esa sección cita por su nombre.
2. **Agrupar automáticamente una lista de vídeos por palabras clave detectadas en su descripción.** Es lo que hace hoy el módulo con `data-grupos`, y no tiene equivalente.
3. **Un punto de navegación del carrusel que se alargue cuando está activo.** ✅ Confirmado hoy leyendo la lista completa de opciones de Post Cards: hay `Dots Position`, `Dots Spacing`, `Dots Margin`, `Dots Alignment`, `Dots Styling`, `Dots Size` y `Dots Color` — **ninguna distingue el punto activo del resto salvo, como mucho, por color**. Esto responde de paso el Problema 4 del informe de Álvaro sobre Novedades: no es que no lo haya encontrado, es que no está.

---

## 6. La decisión de fondo: dónde viven los vídeos

```
                     ¿DÓNDE VIVEN LOS VÍDEOS DE SEMINARIOS?
                                     │
    ┌───────────────┬────────────────┼────────────────┬────────────────┐
    │               │                │                │                │
① Solo en       ② JSON en la    ③ Contenido de   ④ JSON → WP     ⑤ Dos IDs
  YouTube         Biblioteca      WordPress        (importador)     a mano por
  (enlazar)       de Medios       (CPT o ACF)                       bloque
    │               │ (hoy)          │                │                │
 Cero dato      Avada NO lo ve.  Avada lo ve     La app sigue     El editor
 en nuestra     Solo lo lee un   todo: Post      generando; un    escribe 2
 web.           módulo de        Cards, filtros, snippet lo       IDs por
                código.          búsqueda,       vuelca a WP.     seminario
                                 app futura.                      en wp-admin.
```

| | ① Solo YouTube | ② JSON (hoy) | ③ WordPress | ④ Híbrido | ⑤ Dos a mano |
|---|---|---|---|---|---|
| Código nuevo | Ninguno | Ninguno (ya existe) | Ninguno | **Un snippet importador** | Ninguno |
| Trabajo de arranque | Ninguno | Ninguno | Alto (cargar el catálogo) | Medio | Muy bajo |
| Mantenimiento | Ninguno | 1 clic en la app | A mano en wp-admin | 1 clic en la app | 2 campos por seminario, cuando haya vídeos nuevos |
| ¿Avada lo pinta nativo? | ❌ | ❌ | ✅ | ✅ | ✅ |
| ¿Carlota y Álvaro controlan el diseño? | ✅ (no hay nada que diseñar) | ❌ | ✅ | ✅ | ✅ |
| ¿Entra en la búsqueda del sitio? | ❌ | ❌ | ✅ | ✅ | ❌ |
| ¿Lo indexa Google? | ❌ | ⚠️ Incierto — depende de que Googlebot ejecute el JS y espere al `fetch` | ✅ | ✅ | ⚠️ Parcial |
| ¿Sirve para WPMobile.app? | ❌ | ❌ | ✅ | ✅ | ⚠️ Parcial |
| Riesgo principal | La gente se va del sitio | El circuito puede estar roto sin que se note (Sección 3.4) | Nadie mantiene N fichas a mano | El importador hay que escribirlo y probarlo bien | Se queda desactualizado en silencio |

**Mi lectura, sin adornos:** la opción ⑤ está infravalorada y nadie la ha puesto sobre la
mesa. El boceto solo pide **dos** vídeos por seminario en la vista principal. Diez campos en
total, que cambian cuatro o cinco veces al año. Eso no justifica ninguna infraestructura.
La infraestructura solo hace falta para lo que hay detrás del "Ver todos" — y esa es una
decisión separable, que se puede tomar más tarde y sin bloquear la construcción del boceto.

**Dicho de otra manera:** el boceto se puede construir entero, hoy, sin resolver la
pregunta grande. Solo hay que decidir a dónde apunta un enlace.

---

## 7. El cruce con Tiritaito Music: un importador, tres consumidores

Esto es lo más importante que aporta mirar los dos casos juntos, y no se ve mirando ninguno
por separado.

`TIRITAITO_MUSIC_WEB_NUEVA.md` Sección 5 plantea un importador JSON → CPT para la música, y
lo descarta a medias porque *"es un snippet PHP nuevo, con lógica de sincronización… no es
gratis"*. Ese cálculo cambia al contar los tres consumidores reales:

```
                    ┌──────────────────────────────┐
                    │  IMPORTADOR JSON → WordPress │
                    │  (un solo snippet PHP)       │
                    └──────────────┬───────────────┘
                                   │
         ┌─────────────────────────┼─────────────────────────┐
         ▼                         ▼                         ▼
 tt_youtube_json_url      tt_seminarios_json_url     tt_viacrucis_json_url
     (Música)                 (Seminarios)              (Vía Crucis)
         │                         │                         │
         ▼                         ▼                         ▼
  Entrada Tiritaito        Página Seminarios          Oraciones ·
  Music (página            (boceto J v5)              Vía Crucis
  contenedora Tiritaito)
```

Los tres JSON los genera **el mismo botón de la misma app**, con **la misma forma de
envoltorio** (`playlists[].tracks[]`). Un importador que sepa leer esa forma sirve para los
tres. El coste se divide entre tres piezas de la web, no se carga sobre una.

⚠️ **Con un matiz honesto, ya señalado en la Sección 3.2:** los `tracks` no traen los mismos
campos en los tres casos. Seminarios añade `year` y `month`; Música no. El importador tiene
que tolerar campos ausentes, no exigirlos. Es una complicación menor, pero real.

🔲 **Sigue siendo una decisión de Carlitos, no una recomendación mía.** Lo que sí afirmo es
que **evaluar el importador solo para Música lleva a la respuesta equivocada**, porque
reparte un coste compartido sobre un único beneficiario.

---

## 8. Cuatro maneras de construir el boceto J

### 8.0 Antes: el panel en sí

Los cuatro caminos comparten el mismo problema previo, que conviene separar del de los
vídeos.

El boceto dibuja un velo negro **que cubre exactamente el bloque** (`position: absolute;
inset: 0` dentro de `.sem-bloque`) y que aparece con un fundido. El Modal de Avada es una
ventana centrada sobre toda la pantalla. **No es lo mismo visualmente.**

⚠️ **Y hay un precedente inquietante:** el informe de Álvaro sobre Novedades (15 septiembre)
cuenta que intentó exactamente esto — una segunda capa flotando encima usando "Posición
Absoluta" de Avada — y *"al publicar la página, esa segunda capa no aparece en ningún sitio…
Se queda invisible, sin que yo sepa por qué"*. Ese mismo fallo, sin diagnosticar, está justo
en el camino del panel de Seminarios.

Tres caminos posibles, por orden de menor riesgo:

| Camino | Qué se pierde | Riesgo |
|---|---|---|
| **Modal Element de Avada** | El velo cubre la pantalla entera, no solo el bloque | ✅ Bajo — es el uso normal del elemento |
| **Off-Canvas** disparado desde el bloque | Entra por un lado en vez de aparecer encima | ✅ Bajo, pero cambia más el diseño |
| **Container con capa superpuesta** (lo que dibuja el boceto) | Nada | 🔴 Alto — es el patrón que ya falló en Novedades y sigue sin diagnosticar |

**Recomendación:** resolver primero el misterio de la Posición Absoluta de Novedades
(pendiente ya escalado a Carlitos) **antes** de que Álvaro empiece Seminarios. Es el mismo
problema dos veces, y merece una sola investigación.

### 8.1 Camino A — **A mano, cero infraestructura** *(recomendado para empezar)*

- Las **dos miniaturas** de cada panel: dos Columnas, cada una con la imagen de miniatura de YouTube y `Link URL` + `Link Target = Lightbox`. O dos Lightbox Elements de tipo Video, con miniatura y título propios.
- **"Ver todos los vídeos →"**: un Botón que abre la playlist de ese seminario en YouTube, en pestaña nueva.
- ✅ Construible hoy, sin resolver ninguna pregunta de arquitectura.
- ✅ Todo dentro del alcance de Carlota y Álvaro; ni una línea de código.
- ❌ El editor tiene que cambiar 10 campos a mano cuando hay vídeos nuevos.
- ❌ Los vídeos no existen para la búsqueda del sitio ni para Google.
- ❌ El generador de JSON de la app deja de tener sentido para Seminarios.

### 8.2 Camino B — **A mano arriba, playlist embebida debajo**

Igual que A, pero "Ver todos" abre un **Modal con la playlist de YouTube embebida**
(`&listType=playlist&list=PL…`), en vez de sacar a la gente del sitio.

- ✅ La gente no abandona la web; la cola y el siguiente/anterior los pone YouTube gratis.
- ✅ Siempre sincronizado: no hay JSON que regenerar nunca.
- ❌ Marca de YouTube y vídeos relacionados dentro de nuestra página.
- 🔲 **Depende de la prueba del `&list=`** (Sección 5). Si no funciona, este camino necesita un `<iframe>` a mano en un Code Block — y entonces pierde el Video Facade.

### 8.3 Camino C — **El módulo de código, reconectado** *(lo que hay hoy)*

Se conserva el módulo actual, corregido: que lea `tt_seminarios_json_url` del endpoint en
vez de tener la URL escrita a mano, y que deje de comprobar la privacidad desde el navegador
del visitante.

- ✅ Nada de lo que existe hoy se pierde; el generador de la app sigue teniendo sentido.
- ✅ Encaja literalmente en el reparto del 6 de septiembre: Carlitos diseña el código, Álvaro lo pega en un Code Block.
- ❌ Esa sección queda fuera del alcance de Avada para siempre: cada ajuste visual vuelve a Carlitos.
- ❌ Hay que meter un módulo de JavaScript **dentro de un panel oculto**, cinco veces en la misma página. Es el escenario más frágil de todos: el módulo tiene que inicializarse aunque su contenedor esté invisible, y pintar en cinco sitios distintos.
- 🔲 Y antes de nada hay que ver el código real (Sección 3.4).

### 8.4 Camino D — **Los vídeos pasan a ser contenido de WordPress**

Cada vídeo es una entrada (o una fila de un repetidor ACF), con su tipo de seminario como
categoría. El panel muestra un Post Cards limitado a 2; "Ver todos" lleva a una página de
vídeos con filtros por tipo.

- ✅ Todo nativo. Carlota diseña, Álvaro construye, sin intermediarios.
- ✅ Los vídeos entran en la búsqueda del sitio, en Google y en la app futura de WPMobile.
- ✅ La taxonomía ya existe (Sección 4) — no hay que inventarla.
- ❌ Alguien tiene que cargar el catálogo: a mano, con un módulo nuevo en la app, o con el importador de la Sección 7.
- 🔲 Depende de que el YouTube Element admita Dynamic Content en su campo de Video ID, o de resolverlo con el camino Columna → Lightbox.

### 8.5 Comparación rápida

| | A · A mano | B · A mano + playlist | C · Módulo | D · WordPress |
|---|---|---|---|---|
| Se puede empezar | Hoy | Tras una prueba de 10 min | Tras ver el código | Tras decidir la carga de datos |
| Código nuevo | 0 | 0 | Sí (reescritura) | 0, o un importador |
| Quién lo mantiene visualmente | Álvaro | Álvaro | Carlitos | Álvaro |
| Quién mantiene el contenido | Editor, a mano | Editor, a mano | La app, 1 clic | Depende |
| Aguanta si hay 100 vídeos | ✅ (solo se ven 2) | ✅ | ✅ | ✅ |
| Riesgo técnico | Muy bajo | Bajo | Alto | Medio |

**Mi lectura:** **A o B ahora, D más adelante si se decide que los vídeos importan de
verdad.** El camino C solo tiene sentido si al mirar el código resulta que el módulo está
sano y bien conectado — y aun así arrastra el problema de meterlo dentro de cinco paneles
ocultos.

Lo que A y B tienen de bueno no es que sean elegantes: es que **no bloquean nada**. Se puede
construir el boceto entero, verlo funcionando, y decidir lo de fondo después, con la página
delante en vez de sobre un papel.

---

## 9. Lo que hay que probar en Local — lista corta

Para Álvaro (Proyecto 3/7). Son cinco pruebas, ninguna larga. Con las respuestas, este
documento pasa de "opciones" a "plan".

| # | Prueba | Cuánto | Qué desbloquea |
|---|---|---|---|
| 1 | Un YouTube Element con un Video ID cualquiera y, en **Additional API Parameter**, `&listType=playlist&list=PL…` de una playlist real de Tiritaito. Comprobar que al terminar el primer vídeo pasa al siguiente. **Probarlo con Video Facade On y Off**, por separado | 10 min | El camino B entero |
| 2 | Una Columna con una imagen de miniatura, `Link URL` = URL de un vídeo de YouTube y `Link Target = Lightbox`. Comprobar que abre el lightbox y reproduce | 5 min | La tarjeta de vídeo del panel (caminos A, B y D) |
| 3 | En un Post Card, poner un YouTube Element y mirar si su campo **Video ID or Url** tiene el icono de Contenido Dinámico y si acepta un campo ACF de texto | 10 min | El camino D |
| 4 | Post Cards con **`Custom Field - Name` / `Value Comparison` / `Value`** sobre un campo ACF real. ¿Filtra de verdad? | 15 min | Corrige o confirma nuestra documentación (Sección 11.1), y desbloquea de paso el pendiente de Novedades |
| 5 | Un Modal Element disparado desde un Botón, con dos Columnas-lightbox dentro. ¿Se ve bien? ¿Los lightbox funcionan **dentro** del modal? | 10 min | El panel "Más información" completo |

⚠️ **La prueba 5 es la que más me preocupa** y la que menos se parece a lo ya probado: un
lightbox abriéndose desde dentro de un modal es una capa sobre otra capa. Si falla, hay que
replantear el panel, no los vídeos.

---

## 10. Y lo que hace falta de Carlitos

| # | Qué | Por qué |
|---|---|---|
| 1 | **El código del módulo de vídeos de seminarios de la web vieja** | Sin él, la Sección 3.4 se queda en suposiciones y el camino C no se puede evaluar |
| 2 | **El `Seminarios.json` real**, o el número de playlists y de vídeos | Diseñar para 12 vídeos y para 120 son dos diseños distintos. Es el mismo dato que falta en el documento de Música |
| 3 | **Los cinco nombres canónicos** de los seminarios, y si coinciden con los nombres de las playlists en YouTube | De eso depende que la correspondencia de la Sección 4 sea real o imaginaria |
| 4 | Si "Seminario General" y "Vida en el Espíritu" son lo mismo | Decide si el mapeo bloque↔playlist es 1 a 1 o 1 a varios |

---

## 11. Hallazgos laterales que afectan a otros documentos

*Siguiendo la regla 7 de las instrucciones de este Proyecto: lo digo antes de seguir, y no
lo aplico sin que me lo confirmes.*

### 11.1 Post Cards **sí** tiene filtro por campo personalizado — segunda confirmación

`TIRITAITO_MUSIC_WEB_NUEVA.md` Sección 9.1 ya lo señaló. Lo he vuelto a comprobar hoy
leyendo la tabla completa de opciones del Post Cards Element: en la pestaña General aparecen
`Custom Field - Name`, `Custom Field - Value Comparison` (con la nota *"The 'like' comparison
is needed when you want to check serialized data for equality"*) y `Custom Field - Value`,
sin ninguna restricción a productos.

`CATALOGO_ELEMENTOS_AVADA.md` Sección 4 y `GUIA_AVADA_LOCAL.md` Sección 9 afirman lo
contrario: que **no** filtra y que hace falta el hook
`fusion_post_cards_shortcode_query_override`.

Ya no es un indicio suelto: son dos lecturas independientes de la documentación oficial
contra una afirmación nuestra. **Sigo sin tocar ningún documento hasta que la prueba 4 de la
Sección 9 lo confirme en Local**, pero conviene saber que este dato está bloqueando dos
decisiones a la vez (el listado de Novedades y el de Seminarios pasados).

### 11.2 El punto activo del carrusel no existe en Post Cards — confirmado

Álvaro preguntó en su informe de Novedades si Avada trae de fábrica el efecto de "punto
activo alargado" que pide el boceto de Carlota (8×8px redondo → 26px de ancho en píldora).

✅ **Respuesta, verificada hoy contra la lista completa de opciones del elemento: no.** Post
Cards expone `Dots Position`, `Dots Spacing`, `Dots Margin`, `Dots Alignment`, `Dots Styling`,
`Dots Size` y `Dots Color`. **No hay ningún campo que distinga el punto activo del resto por
tamaño o forma.** No es que Álvaro no lo encontrara: no está.

Esto entra directamente en el ámbito de la decisión del 6 de septiembre: si el efecto se
quiere, no se resuelve con Clase CSS — o se acepta la diferencia solo por color, o se escala
a Carlitos para el panel global de Custom CSS. Merece entrada en
`CATALOGO_ELEMENTOS_AVADA.md` Sección 5 bis, en la tabla de "lo que probablemente SÍ sigue
necesitando algo más".

### 11.3 La documentación de Avada ya vive en `classic.avada.com`

Confirmado de nuevo hoy: `avada.com/documentation/...` redirige a `classic.avada.com/...`
con un aviso de *"classic.avada.com is the new home of Avada Classic, and its documentation
lives here"*, y las páginas canónicas apuntan ya al dominio nuevo. Es el hallazgo 9.4 del
documento de Música, ahora con evidencia directa en tres documentos distintos.

⚠️ **Detalle nuevo:** las páginas de `classic.avada.com` traen `meta-robots: noindex,
nofollow`. Eso significa que **la documentación de Avada Classic está dejando de aparecer en
Google**. Para nosotros no es un problema hoy —sabemos ir directamente— pero conviene que las
fuentes citadas en `GUIA_AVADA_LOCAL.md` Sección 18 y en `CATALOGO_ELEMENTOS_AVADA.md` se
actualicen al dominio nuevo antes de que alguien intente buscarlas y no las encuentre.

### 11.4 La pregunta abierta #3 de `ALCANCE_WEB_NUEVA.md` está a medio responder

El boceto J v5 ya decide **dónde** aparecen los vídeos (dentro del panel de cada seminario,
dos miniaturas y un enlace). Lo que sigue abierto es **de dónde salen** y **a dónde lleva el
enlace**. La pregunta, tal como está redactada hoy, mezcla las dos cosas. Propongo partirla
en dos cuando se actualice ese documento.

### 11.5 Una fila cerrable en `METODOLOGIA_CONSTRUCCION.md`

La fila *"Seminarios — vídeos pasados · 🔲 Método de integración con el nuevo formato visual
sin definir"* puede pasar a describir lo que decida esta sesión, en vez de seguir abierta.

---

## 12. Próximos pasos y preguntas abiertas

### Próximos pasos

1. **Carlitos:** pasarme el código del módulo de vídeos de seminarios de la web vieja, y el `Seminarios.json` real (o el recuento de playlists y vídeos). Sin eso, la Sección 3.4 se queda como está.
2. **Carlitos:** comprobar en dos minutos si ese módulo lee `tt_seminarios_json_url` o tiene la URL escrita a mano. Es la comprobación con mejor relación esfuerzo/valor de todo este documento.
3. **Carlitos + Hna C:** decidir camino (A, B, C o D) — o, más exactamente, decidir **A o B ahora** y aplazar la decisión de fondo.
4. **Hna C:** responder las cinco preguntas de la Sección 2.3 y la de la Sección 2.4 (¿los vídeos importan lo bastante como para tener sitio propio, o son material de apoyo detrás de dos clics?).
5. **Hno A (Proyecto 3/7):** las cinco pruebas de la Sección 9, ~50 minutos en total.
6. **Antes de que Álvaro empiece Seminarios:** resolver el misterio de la Posición Absoluta invisible que ya bloqueó Novedades (Sección 8.0). Es el mismo problema dos veces.
7. **Proyecto 5 (Hno A), independiente de todo lo anterior:** añadir `contentDetails` al `part=` de `getPlaylistItems()` y guardar `videoPublishedAt` como fecha de respaldo cuando `extraerFecha()` no encuentre nada. Y guardar el `privacyStatus`, que ya se consulta y se tira (hallazgos #2 y #6 de la Sección 3.3). Es el mismo cambio que ya pide el documento de Música para el mismo generador.
8. **Hno C (yo), cuando lo confirmes:** aplicar en `ALCANCE_WEB_NUEVA.md`, `METODOLOGIA_CONSTRUCCION.md` y `CATALOGO_ELEMENTOS_AVADA.md` lo que decidas de la Sección 11.

### Preguntas abiertas

| # | Pregunta | Bloquea a | Quién decide |
|---|---|---|---|
| 1 | **¿A dónde lleva "Ver todos los vídeos →"?** (YouTube · página propia de vídeos · modal con la playlist · un ancla en la misma página) | La construcción del boceto | Hna C |
| 2 | **¿Cuántas playlists y cuántos vídeos hay hoy en `Seminarios.json`?** | Si hace falta infraestructura o basta con ponerlos a mano | Carlitos |
| 3 | ¿Los vídeos pasan a ser contenido de WordPress, se quedan en el JSON, o se ponen a mano? | Camino A/B vs C vs D | Carlitos |
| 4 | Si pasan a WordPress: ¿los carga alguien a mano, un módulo nuevo de Creators, o el importador compartido con Música y Vía Crucis (Sección 7)? | Trabajo de Proyecto 3 y 5 | Carlitos |
| 5 | ¿Qué dos vídeos se muestran en el panel: los dos más recientes, o dos elegidos a mano? | Si hace falta lógica o basta con dos campos | Hna C |
| 6 | ¿"Seminario General" y "Vida en el Espíritu" son lo mismo? ¿Un bloque puede tener varias playlists? | El mapeo de la Sección 4 | Carlitos |
| 7 | ¿Los cuatro bloques que en la vista móvil no llevan vídeos es una decisión o una simplificación del boceto? | Qué construye Álvaro en móvil | Hna C |
| 8 | ¿Qué se muestra en un seminario que todavía no tiene vídeos? | Estado vacío de la rejilla | Hna C |
| 9 | ¿El orden "año descendente, mes ascendente" del generador es deliberado, o debería ser lo más reciente primero? | Corrección en Proyecto 5 | Carlitos |
| 10 | ¿Existe una convención escrita sobre cómo nombrar y describir los vídeos en YouTube (mes y año en la primera línea)? Hoy el sistema depende de ella y no está en ningún documento | Fiabilidad del orden | Carlitos |

---

*Para la mayor gloria de Dios · tiritaito.com*

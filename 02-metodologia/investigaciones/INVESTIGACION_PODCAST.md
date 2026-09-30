# TIRITAITO.COM — El Podcast: alcance real, diagnóstico y cómo presentarlo

**Investigación completa del sistema `[tt_podcast]` de cara a la web nueva: qué es realmente,
qué está roto, qué dice la evidencia externa verificada, y qué decisiones hay que tomar antes
de que Carlota diseñe el boceto**

*Sesión de Proyecto 2 — 16 de septiembre de 2026*
*Fuentes: código real del snippet compartido por Carlitos · feed RSS real verificado en vivo ·
documentación oficial de Google Search Central (actualizada 15/06/2026) · W3C/WAI ·
código fuente de WordPress (`wp-includes/shortcodes.php`, espejo oficial de GitHub)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde: **¿qué es realmente el sistema de podcast de Tiritaito hoy, qué problemas tiene
medidos (no intuidos), y qué hace falta decidir para presentarlo bien en la web nueva?**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| El árbol de funciones del JavaScript del reproductor | 🔲 **No existe todavía** — falta la pieza (ver Sección 2.2) |
| Qué elemento de Avada resuelve una necesidad visual | `CATALOGO_ELEMENTOS_AVADA.md` |
| Qué secciones tiene la web y con qué prioridad | `ALCANCE_WEB_NUEVA.md` — ⚠️ **incompleto en lo que toca al podcast, ver Sección 1** |
| **Qué es el podcast, qué falla, y qué decidir antes de diseñar** | **Este documento** |

**Marcadores de certeza usados** (mismos que `CATALOGO_ELEMENTOS_AVADA.md`):
✅ confirmado con evidencia directa · ⚠️ confirmado pero con algo sin cerrar ·
🔲 documentado o razonado, sin verificar contra Tiritaito · ❌ confirmado que NO, o
decisión explícita de no hacerlo.

---

## 1. 🚨 Lo primero — el alcance documentado del podcast está mal

Esto no es un matiz. Cambia la pregunta arquitectónica que llevaba abierta desde
`02_REF_PODCAST.md`, y conviene resolverlo **antes** de que Carlota haga ningún boceto.

### 1.1 Lo que dice la documentación

`ALCANCE_WEB_NUEVA.md` Sección 4.E y `METODOLOGIA_CONSTRUCCION.md` Sección 3 tratan el
podcast como **dos programas**, ambos dentro de la página contenedora "Tiritaito":

- Rincón de Nico → `[tt_podcast]` + accordion
- Charlas de la Biblia → `[tt_podcast]`

Y añaden el "principio de unidad de podcast": cada uno puede tener tono propio, pero el
formato del reproductor debe ser el mismo.

### 1.2 Lo que dice la evidencia real

Dos pruebas independientes, ambas verificadas en esta sesión:

**Prueba 1 — el comentario del propio código.** Dentro de `tt_podcast_shortcode()`, justo
encima del esqueleto HTML:

```php
// Esqueleto HTML del player — igual en los 12 canales, generado una sola vez
```

**Doce canales.** No dos.

**Prueba 2 — el feed que Carlitos va a pegar.** Descargué y leí el feed real
(`anchor.fm/s/111a10f60/podcast/rss`) en esta sesión. Sus metadatos de canal:

| Campo | Valor real |
|---|---|
| `<title>` | San Serafín de Sarov, santo del Espíritu Santo |
| `<description>` | 30 días de preparación para Pentecostés |
| `<itunes:author>` | Tiritaito |
| Episodios | 36, todos `<itunes:season>1</itunes:season>` |
| Duración | Entre 1:30 y 12:37 — episodios cortos |

**San Serafín de Sarov es uno de los 9 santos de "Hombres de Dios"**
(`ALCANCE_WEB_NUEVA.md` Sección 4.H). Es decir: el reproductor de podcast no vive solo en
la página Tiritaito — también vive dentro de las fichas de santos.

### 1.3 Por qué esto importa tanto

`ALCANCE_WEB_NUEVA.md` 4.H sí anticipaba que los santos son heterogéneos: *"no todos los
santos usan la misma combinación de piezas — unos llevan audio, otros discursos, otros solo
biografía"*. Lo que **no** dice en ninguna parte es que ese audio se sirve con un feed RSS
completo y el mismo reproductor de 36 episodios que Charlas de la Biblia.

Consecuencias concretas:

| Consecuencia | Detalle |
|---|---|
| El podcast no es una sección, es **un componente transversal** | Aparece en Tiritaito, en Hombres de Dios, y probablemente en más sitios. Su diseño no se decide dentro de una página — se decide una vez para toda la web |
| El "principio de unidad de podcast" tiene más alcance del escrito | No es "que Rincón de Nico y Charlas de la Biblia se parezcan" — es que 12 sitios distintos de la web compartan presentación |
| El peso técnico se multiplica por 12 | Todo lo de la Sección 3 (rendimiento, accesibilidad) se paga 12 veces, no 2 |
| La ficha de un santo puede necesitar **dos** tratamientos distintos | Un santo con 36 capítulos de audio no se presenta igual que uno con solo biografía. El Layout compartido de 4.H tiene que absorber esa diferencia |

### 1.4 ⚠️ Qué documentos habría que actualizar — pendiente de tu confirmación

Siguiendo el protocolo de este Proyecto, **no edito nada sin tu visto bueno.** Esto es lo
que quedaría desalineado:

| Documento | Sección | Qué habría que corregir |
|---|---|---|
| `ALCANCE_WEB_NUEVA.md` | 4.E, 4.H | Que el podcast no son 2 piezas sino ~12, y que Hombres de Dios incluye fichas con reproductor completo |
| `METODOLOGIA_CONSTRUCCION.md` | 3 (Tiritaito, Hombres de Dios) | Fila nueva: dónde vive el podcast dentro de una ficha de santo |
| `02_REF_PODCAST.md` | Todo | Hoy está vacío ("Verificar estado real ⚠️"). Es el destino natural de este documento |
| `CATALOGO_ELEMENTOS_AVADA.md` | Nueva entrada | "Necesito un reproductor de podcast con lista de episodios" → hoy no tiene entrada |

🔲 **Pregunta que solo puedes responder tú o Álvaro: ¿cuáles son los 12 canales?**
Mi hipótesis, sin confirmar, es 9 santos + Rincón de Nico + Charlas de la Biblia = 11, más
uno que no identifico. **No lo doy por bueno** — la aritmética cuadra sospechosamente bien,
que es justo la razón para desconfiar de ella. Necesito la lista real.

---

## 2. Estado real del reproductor `[tt_podcast]`

### 2.1 ✅ Lo que está confirmado con el código real delante

**Arquitectura de datos — sólida, sin objeciones.**

```
NAVEGADOR                    WORDPRESS (servidor)              ANCHOR / SPOTIFY
    │                              │                                  │
    │  pide la página              │                                  │
    ├─────────────────────────────►│                                  │
    │                              │  fetch_feed() — nativo de WP     │
    │                              │  caché 1 hora (transient)        │
    │                              ├─────────────────────────────────►│
    │                              │◄─────────────────────────────────┤
    │                              │        RSS con 36 items          │
    │                              │                                  │
    │                              │ parsea: enclosure (mp3),         │
    │                              │ itunes:season/episode/duration/  │
    │                              │ image, descripción limpia,       │
    │                              │ y detecta YouTube por regex      │
    │                              │                                  │
    │◄─────────────────────────────┤                                  │
    │  HTML + window.tt_podcast_data                                  │
    │  (sin AJAX, sin proxy, sin fetch del navegador)                 │
```

Esto está bien hecho y **no es candidato a reescribir**: todo el trabajo pesado ocurre en el
servidor, con caché, usando funciones nativas de WordPress. El navegador no habla nunca con
Anchor. Es exactamente el patrón que `00_CORE.md` define como correcto.

✅ **Respuesta a una pregunta que llevaba abierta:** el bloque de datos
(`window.tt_podcast_data`) **va dentro del `return` del shortcode**, no en un hook aparte.
Correcto — se imprime solo donde el shortcode aparece de verdad, sin depender de
`has_shortcode()`, que con Avada da falso negativo.

### 2.2 🔲 Lo que sigue faltando — y es la pieza central

**El bloque `<script>` con las funciones del reproductor no aparece en ninguna parte del
código compartido.** El PHP termina exactamente aquí:

```php
$data_script = '<script>window.tt_podcast_data = ' . wp_json_encode([...]) . ';</script>';
return $html_skeleton . $data_script;
}
```

El HTML invoca **trece funciones** que no están definidas en nada de lo que tengo:
`ppTogglePlay`, `ppSeek`, `ppSkip`, `ppPrev`, `ppNext`, `ppSetVolume`, `ppCycleSpeed`,
`ppToggleDesc`, `ppShowVideo`, `ppShowAudio`, y las que el acordeón de temporadas necesite
(`ppRenderSeasons`, `ppLoadEpisode`, `ppToggleSeason`).

Si esto fuera literalmente todo el snippet, el reproductor no arrancaría nunca: el spinner
"Cargando episodios…" se quedaría girando para siempre, porque `#ppPlayer` sigue con
`style="display:none"` hasta que algún JS lo cambie.

Como el reproductor **sí funciona hoy**, ese bloque existe en algún sitio. Tres hipótesis,
sin poder elegir entre ellas sin verlo:

| Hipótesis | Cómo cambiaría el análisis |
|---|---|
| El pegado se cortó; el `<script>` va tras el `return` en la misma función | La mejor noticia: solo falta copiar el tramo |
| Es un **snippet separado** (tipo HTML) que se carga en todas las páginas | Sería el mismo Patrón B de deuda técnica que el CSS — JS de reproductor cargándose en páginas sin reproductor |
| Vive en otro sitio del sistema | Poco probable: no hay `wp_enqueue_script` en ningún fragmento visto |

**Esto es lo primero que hay que pedirle a Álvaro.** Todo lo de la Sección 3.2
(accesibilidad) está analizado sobre el HTML que sale del PHP; el script podría estar
añadiendo atributos ARIA dinámicamente y corregir parte de lo que señalo. No puedo
descartarlo sin verlo, y lo digo explícitamente en cada punto donde aplica.

---

## 3. Diagnóstico técnico — medido, no intuido

### 3.1 Rendimiento — el CSS se paga en todas las páginas del sitio

**Medición real del CSS del reproductor** (medido, no estimado):

| Métrica | Valor |
|---|---|
| Tamaño en bruto | **16,5 KB** (16.915 bytes) |
| Reglas CSS | 143 bloques |
| Usos de `!important` | **408** |
| Colores hex sueltos | 59 |
| Usos de `var(--tt-*)` | **0** |
| Selectores `.pp-*` distintos | 58 |
| `@import` a dominio externo | 1 (Google Fonts) |

Este CSS se inyecta con `add_action('wp_head', ..., 5)` **sin condición ninguna** — en la
home, en Conecta cada día, en Qué hacemos, en el formulario del Ejército de Intercesores, en
absolutamente todas las páginas del sitio, tengan o no reproductor.

⚠️ **Matiz importante, en defensa del código actual:** esto **no es un descuido**. Está
documentado en `00_CORE.md` Sección 10 como *workaround* deliberado, porque `has_shortcode()`
da falso negativo con Avada (Avada serializa el `post_content` en Base64, así que WordPress
no puede detectar el shortcode por ese método). La decisión fue consciente. Lo que sí es
nuevo es tener el **coste medido**, que hasta ahora no estaba en ningún documento.

#### 🚨 El `@import` de Google Fonts — dos problemas en uno

```css
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap");
```

**Problema 1 — rendimiento.** Un `@import` dentro de un `<style>` en `wp_head` es lo más
lento que existe para cargar CSS: el navegador tiene que descargar y analizar el bloque
`<style>` antes de descubrir siquiera que hay otro archivo que pedir, y no puede
adelantarse con el *preload scanner*. Bloquea el pintado de la página. Y se paga en todas
las páginas, no solo donde hay podcast.

**Problema 2 — contradice una decisión ya tomada por el equipo, dos veces.**

- `GUIA_AVADA_LOCAL.md` Sección 4.2 dice textualmente: *"Ninguna fuente de Google Fonts
  debería aparecer — su presencia en el sitio actual (vía `@import` en el CSS del podcast)
  es deuda técnica a no replicar."* Esta investigación **confirma ese diagnóstico contra el
  código real**: era una sospecha razonada, ahora es un hecho verificado.
- `CATALOGO_ELEMENTOS_AVADA.md` Sección 9.2 registra que en Global Options se cambió
  "Google & Font Awesome Fonts Mode" de CDN a **Local**, precisamente para no depender de
  Google.

⚠️ **Discrepancia que he encontrado y no puedo resolver desde aquí:** el export real
(`avada-global-options.json`, 14 agosto 2026 — posterior a esa ronda) tiene
`"gfonts_load_method": "cdn"`, no local. O el cambio no se guardó, o se revirtió, o
`gfonts_load_method` no es el campo que corresponde a esa opción del panel. **Hay que
comprobarlo en Local.** Lo anoto como pregunta abierta, no como afirmación.

**Nota de privacidad, sin ser abogado:** cargar fuentes desde `fonts.googleapis.com` envía
la IP de cada visitante a Google en cada carga de página. Hay jurisprudencia europea al
respecto y es justo lo que el equipo quiso evitar al configurar Privacy & Consent en Avada.
Para un sitio católico español dirigido en parte a niños, merece una decisión consciente.
**No afirmo que haya incumplimiento legal** — no es mi campo, y depende de factores que no
conozco. Señalo que la decisión ya tomada por el equipo y lo que hace el código no coinciden.

**La buena noticia: la fuente "Inter" ni siquiera se usa en el titular.** El `h1` del hero
tiene su propia pila de fuentes del sistema (`-apple-system, BlinkMacSystemFont, "SF Pro
Display", "Helvetica Neue"`). Inter solo gobierna el cuerpo del reproductor. Sustituirla por
Helvetica Neue (que es la fuente de cuerpo del proyecto, ya cargada localmente en Avada) y
borrar el `@import` es **un cambio de una línea que no cambia nada visualmente de forma
apreciable** y resuelve los dos problemas de golpe.

### 3.2 Accesibilidad — el punto más flojo, con diferencia

> *Accesibilidad, en una frase: que una persona ciega, sorda, o que no puede usar el ratón,
> pueda usar la web igual que los demás.*

**WCAG** es el estándar internacional que define eso, con tres niveles: A (mínimo
imprescindible), AA (el que casi todas las normativas exigen) y AAA (excelencia).

Analizo el HTML real que sale del PHP. ⚠️ **Salvedad honesta y aplicable a todo este
apartado: el `<script>` que falta podría estar corrigiendo parte de esto en tiempo de
ejecución. No puedo descartarlo. Todo lo marcado ⚠️ necesita reconfirmarse cuando aparezca.**

| # | Hallazgo | Criterio WCAG | Certeza |
|---|---|---|---|
| A1 | `.pp-reset *{ outline:none!important }` **elimina el indicador de foco de todo el reproductor**. Ninguna regla posterior lo restaura. Quien navegue con teclado no verá nunca dónde está | 2.4.7 Focus Visible (**AA**) | ✅ Confirmado solo con el CSS, no depende del script |
| A2 | Los botones de control (atrás 15s, anterior, play, siguiente, +30s) contienen **solo un SVG, sin texto ni `aria-label`**. Un lector de pantalla los anuncia como "botón", sin decir cuál | 4.1.2 Name, Role, Value (**A**) | ⚠️ El script podría añadir labels |
| A3 | La barra de progreso es un `<div>` con `role="slider"` pero **sin `tabindex`, sin `aria-valuenow/min/max`, sin `aria-label`, y con `onclick` en vez de manejo de teclado**. Declara ser un control deslizante y no se puede usar con teclado | 2.1.1 Keyboard (**A**) + 4.1.2 (**A**) | ⚠️ Igual |
| A4 | El control de volumen (`<input type="range">`) **no tiene etiqueta asociada** | 1.3.1 (**A**) / 4.1.2 (**A**) | ✅ Confirmado en el HTML |
| A5 | **No hay transcripciones.** WCAG 1.2.1 exige, en **nivel A** (el mínimo), texto alternativo completo para todo audio pregrabado. Un podcast sin transcripción no cumple ni el nivel mínimo | 1.2.1 (**A**) | ✅ Confirmado |
| A6 | `<audio id="ppAudio" preload="metadata">` **sin atributo `controls` y sin contenido alternativo**. Si el JavaScript falla o no carga, no hay absolutamente ninguna forma de reproducir nada | (robustez / mejora progresiva) | ✅ Confirmado |
| A7 | ✅ **Bien hecho:** el botón "Descripción" sí lleva texto visible y `aria-expanded`. Es el único control correctamente accesible de todo el reproductor | — | ✅ |

**Sobre si esto es obligatorio legalmente:** el Real Decreto 1112/2018 obliga al sector
público español; la Ley Europea de Accesibilidad (vigente desde junio de 2025) obliga a
comercio electrónico, banca y servicios digitales. 🔲 **Muy probablemente Tiritaito no entra
en ninguno de los dos supuestos** — no soy abogado y no lo afirmo. Pero para un sitio cuyo
propósito declarado es la evangelización, dejar fuera a quien no oye o no ve es una cuestión
de misión antes que de cumplimiento. Lo planteo así, no como amenaza legal.

### 3.3 Dos `<h1>` en la misma página — problema de estructura y de SEO

El esqueleto del reproductor emite su propio titular:

```html
<h1 id="ppPodcastTitle"></h1>
```

Relleno con `$channel_title` del feed — en nuestro caso, *"San Serafín de Sarov, santo del
Espíritu Santo"*.

⚠️ Pero la Page Title Bar de Avada ya está configurada como "Show Bar and Content"
(`CATALOGO_ELEMENTOS_AVADA.md` Sección 2), lo que normalmente pinta el título de la entrada
como `h1`. Si ambos coinciden, la página tiene **dos `h1`**, y además el acordeón de
temporadas usa `<h3>` saltándose el `h2`. Para un lector de pantalla, la jerarquía del
documento queda rota; para Google, el titular principal de la página es ambiguo.

🔲 **Sin verificar en Local** — depende de la configuración concreta del Layout de esa
entrada. Es una comprobación de 30 segundos con las herramientas de desarrollo del navegador
y merece hacerse antes de construir.

### 3.4 Namespacing de IDs — pregunta que ahora sí tiene respuesta

Todos los IDs están escritos a fuego sin sufijo de instancia (`ppAudio`, `ppSeasons`,
`ppVolume`…) y los datos van en **una única variable global** `window.tt_podcast_data`. Dos
reproductores en la misma página se pisarían entre sí y se romperían los dos.

**Con la arquitectura real (un `[tt_podcast]` por página/entrada, ~12 páginas distintas),
esto NO es un problema hoy.** Nunca coinciden dos en la misma pantalla.

⚠️ **Pero hay un caso límite nuevo que antes no existía**, y aparece precisamente por el
hallazgo de la Sección 1: `CATALOGO_ELEMENTOS_AVADA.md` Sección 3 documenta que Post Cards
puede abrir un Off-Canvas con "vista rápida" del contenido de una entrada, y lo menciona
como posible para **Hombres de Dios**. Si esa vista previa llegara a renderizar el contenido
completo de la ficha de un santo, tendrías el reproductor de la portada **y** el de la vista
previa a la vez. No está construido ni decidido — lo señalo para que no se descubra tarde.

### 3.5 El acordeón de temporadas depende de datos que no controlamos

✅ **Hallazgo concreto y accionable, verificado en el feed real.** El agrupamiento en
temporadas sale del tag `<itunes:season>` de cada episodio. En este feed **los 36 episodios
están etiquetados como temporada 1**, así que el reproductor mostraría un único acordeón con
36 capítulos seguidos, sin ninguna sub-estructura.

Pero el contenido **sí tiene tres bloques naturales claramente distintos**, visibles en la
descripción de cada episodio:

| Capítulos | Bloque real | Nº |
|---|---|---|
| 1 – 20 | Conversaciones con Motovilov | 20 |
| 21 – 26 | Instrucciones espirituales | 6 |
| 27 – 36 | Preparación para Pentecostés | 10 |

**La solución no está en el código — está aguas arriba, en Spotify for Podcasters.** Si se
etiquetan esos tres bloques como temporadas 1, 2 y 3 en la plataforma, el reproductor los
agrupa solo, y el shortcode queda:

```
[tt_podcast feed="https://anchor.fm/s/111a10f60/podcast/rss" seasons='{"1":"Conversaciones con Motovilov","2":"Instrucciones espirituales","3":"Preparación para Pentecostés"}']
```

Esto vale como **regla general para los 12 canales**: la calidad de la presentación depende
tanto de cómo se etiqueta el contenido al publicarlo como del código que lo pinta. Merece una
pauta escrita para quien sube episodios.

⚠️ **Nota aparte sobre este feed concreto:** el título del canal dice "San Serafín de Sarov,
santo del Espíritu Santo" pero la descripción dice "30 días de preparación para Pentecostés"
— que describe solo 10 de los 36 capítulos. Como el reproductor pinta el título del canal
como titular de la sección, conviene revisar esos metadatos en Spotify antes de lanzar.

---

## 4. SEO y descubrimiento — verificado contra Google, no supuesto

### 4.1 ❌ Los datos estructurados de podcast NO sirven para Google

> *Datos estructurados, en una frase: etiquetas invisibles en el código que le explican a
> Google qué es cada cosa de la página, para que muestre resultados enriquecidos.*

Es la recomendación número uno en casi todos los artículos de SEO para podcast que circulan
por internet. **He verificado la lista oficial y actual de Google** (Search Central,
"Structured data markup that Google Search supports", última actualización **15 de junio de
2026**). La lista completa contiene 24 tipos: Article, Breadcrumb, Carousel, Course list,
Dataset, Discussion forum, Education Q&A, Employer aggregate rating, Event, Image metadata,
Job posting, Local business, Math solver, Movie, Organization, Product, Profile page, Q&A,
Recipe, Review snippet, Software app, Speakable, Subscription/paywalled, Vacation rental.

**No hay ninguna entrada de Podcast. Ni `PodcastSeries`, ni `PodcastEpisode`.**

Los artículos que lo recomiendan citan casi todos "Google Podcasts", **un producto que Google
discontinuó**. El tipo existe en schema.org, pero existir en schema.org y producir un
resultado enriquecido en Google son dos cosas distintas — la propia documentación de Google
lo advierte: *"Hay más atributos y objetos en schema.org que no requiere Google Search"*.

Además, el contexto general va en esa dirección: Google lleva tres rondas (2023, 2025, 2026)
retirando tipos de datos estructurados poco usados. Los resultados enriquecidos de FAQ
desaparecieron el 7 de mayo de 2026.

**Conclusión honesta: no inviertas ni una hora en `PodcastEpisode` esperando un resultado
enriquecido en Google.** No lo vas a obtener.

### 4.2 ✅ Lo que sí está disponible y sí aplica

| Recurso | Qué aporta | Esfuerzo |
|---|---|---|
| **Texto real en la página** (transcripciones) | Lo único que Google puede indexar de verdad. Un MP3 es opaco; una transcripción es contenido | Medio — pero ver 5.3, aquí es casi gratis |
| **Datos estructurados `Article`** | Sí está en la lista oficial. Aplica a la entrada que contiene el podcast, no al podcast | Bajo |
| **`Organization`** | Está en la lista. Panel de conocimiento de Tiritaito, logo, redes | Bajo, una vez para todo el sitio |
| **`Breadcrumb`** | Está en la lista. Ya configurado en Avada (`CATALOGO` Sección 2) | ✅ Hecho |
| **Open Graph** (previsualización al compartir) | Cómo se ve el enlace en WhatsApp o Telegram — el canal real de difusión de este contenido | Bajo |

⚠️ **Aviso conectado con algo ya aprendido en este proyecto:** ya está registrado que los
rastreadores de WhatsApp, Facebook y Telegram **no ejecutan JavaScript**. El reproductor pinta
su contenido en el cliente. Es decir: **compartir el enlace de una página de podcast por
WhatsApp hoy mostraría una previsualización vacía o genérica**, no el título del episodio.
Para un contenido que se difunde precisamente por WhatsApp, esto pesa bastante más que
cualquier dato estructurado. La solución es que el título y la imagen vivan en el HTML que
genera el servidor, no en el que pinta el JavaScript.

---

## 5. Cómo presentarlo — el catálogo de decisiones

Lo que sigue son las piezas que componen una presentación de podcast completa. No todas hacen
falta; están ordenadas por relación valor/esfuerzo para el caso concreto de Tiritaito.

### 5.1 🔴 Botones de "escúchalo en tu app" — lo que más falta

**Lo que falta hoy, y probablemente sea lo más importante de todo este documento.** Un oyente
que descubre el podcast en la web y quiere seguirlo no va a volver a la web cada día: quiere
suscribirse en la app que ya usa, para que los capítulos nuevos le lleguen solos.

Sin esos botones, cada oyente nuevo se pierde después de un episodio. El feed ya trae el
enlace público (`podcasters.spotify.com/pod/show/csftrillo57`), y Anchor/Spotify distribuye
automáticamente a las principales plataformas.

Es una fila de botones estáticos. **Coste casi nulo, impacto probablemente el mayor de la
lista.** Funciona sin JavaScript, sin datos estructurados y sin tocar el reproductor.

### 5.2 🔴 Enlace directo a un episodio concreto

Hoy no se puede compartir "el capítulo 17". El reproductor no refleja en la URL qué episodio
está sonando. Para un contenido devocional que se comparte de uno en uno por WhatsApp —
"escucha este de hoy" — es una limitación de fondo, no un detalle.

La solución estándar es reflejar el episodio en la URL (`?ep=17` o `#ep-17`) y que el
reproductor lo lea al cargar. Requiere el script, así que está bloqueado por la Sección 2.2.

### 5.3 🟡 Transcripciones — aquí hay una oportunidad que casi nadie tiene

Normalmente transcribir es caro. **En este feed, buena parte ya está escrita.**

Los capítulos 27 a 36 (Preparación para Pentecostés) traen la oración **completa, palabra por
palabra**, en la descripción del episodio. Y el PHP ya la extrae, la limpia y la pasa al
navegador en el campo `descClean`. Ya está en la página.

Es decir: para un tercio de este podcast, la transcripción **ya existe y ya está cargada** —
solo está escondida detrás de un desplegable de "Descripción" que hay que pulsar.

Esto resuelve tres cosas a la vez:

1. **Accesibilidad** — el requisito WCAG 1.2.1 nivel A de la Sección 3.2
2. **SEO** — texto real indexable donde antes solo había un MP3 opaco
3. **Uso real** — una oración escrita se puede leer, copiar y compartir; un audio no

🔲 Los capítulos 1–26 solo traen una línea de descripción ("Conversaciones con Motovilov"),
así que ahí sí haría falta transcribir de verdad. Con episodios de 2–5 minutos, las
herramientas actuales de transcripción automática lo hacen razonablemente bien y luego se
repasa a mano. **Decisión de producto, no técnica** — pero el coste es mucho menor de lo que
parece a primera vista.

### 5.4 🟡 Controles en la pantalla de bloqueo del móvil (Media Session API)

> *En una frase: que al bloquear el móvil sigan apareciendo los controles de play/pausa con
> la carátula, como en Spotify.*

✅ Es una API estándar del navegador (W3C Working Draft, 5 de junio de 2026), soportada en
Chrome, Edge y Safari, escritorio y móvil. Son unas 20 líneas de JavaScript y el reproductor
ya tiene todos los datos que necesita (título, imagen del episodio, duración).

Para contenido devocional que se escucha caminando o con el móvil en el bolsillo, la
diferencia de experiencia es grande. Es de lo más rentable que se puede añadir.

### 5.5 🟡 Recordar dónde se quedó el oyente

Un capítulo de 12 minutos que se interrumpe obliga a empezar de cero. Guardar la posición en
`localStorage` (memoria del propio navegador, sin servidor ni cuentas de usuario) son pocas
líneas y se nota mucho.

### 5.6 🟢 Funcionamiento sin JavaScript (mejora progresiva)

Hoy, si el JavaScript falla, **la página no ofrece absolutamente nada**: el `<audio>` no
tiene `controls` y el reproductor sigue oculto. Añadir `controls` al elemento `<audio>` y una
lista de enlaces a los MP3 que el PHP ya conoce daría un suelo mínimo funcional. Barato, y es
una red de seguridad real.

### 5.7 Resumen priorizado

| # | Pieza | Valor | Esfuerzo | ¿Bloqueado? |
|---|---|---|---|---|
| 1 | Botones de suscripción a plataformas | 🔴 Muy alto | Muy bajo | No — se puede hacer ya |
| 2 | Quitar el `@import` de Google Fonts | 🔴 Alto | Muy bajo | No — una línea |
| 3 | Restaurar el indicador de foco (`outline`) | 🔴 Alto | Muy bajo | No — una línea |
| 4 | `aria-label` en los botones de control | 🔴 Alto | Bajo | ⚠️ Confirmar si el script ya los pone |
| 5 | Transcripción visible (capítulos que ya la traen) | 🟡 Alto | Bajo | No |
| 6 | Media Session API (pantalla de bloqueo) | 🟡 Alto | Medio | Sí — falta el script |
| 7 | Enlace directo a episodio | 🟡 Alto | Medio | Sí — falta el script |
| 8 | Barra de progreso accesible por teclado | 🟡 Medio | Medio | Sí — falta el script |
| 9 | Recordar posición de escucha | 🟡 Medio | Bajo | Sí — falta el script |
| 10 | CSS condicional en vez de global | 🟢 Medio | Alto | Ver Sección 6 |
| 11 | Transcribir los capítulos que no la traen | 🟢 Medio | Alto | Decisión de producto |
| 12 | Funcionamiento sin JavaScript | 🟢 Bajo | Bajo | No |

---

## 6. El CSS global — tres salidas posibles

El problema de la Sección 3.1 (16,5 KB en todas las páginas) tiene tres soluciones reales.
Ninguna es obvia; la decisión es tuya.

| Opción | Cómo funciona | A favor | En contra |
|---|---|---|---|
| **A. Dejarlo como está** | No tocar nada | Coste cero, funciona, ya documentado como workaround consciente | 16,5 KB en todas las páginas del sitio, para siempre |
| **B. Mover el CSS dentro del `return` del shortcode** | El `<style>` viaja con el HTML, igual que ya hace `$data_script` | Resuelve el problema de raíz, sin depender de `has_shortcode()`. Coherente con lo que ya se hace con los datos | El CSS deja de estar en `<head>`; puede provocar un parpadeo breve al cargar. Si alguna vez hubiera dos reproductores, se duplicaría |
| **C. Condicional por página en Avada** | Cargar el CSS solo en las plantillas que lo necesitan | Lo más limpio en teoría | Con 12 canales repartidos entre Tiritaito y Hombres de Dios, la condición es compleja y frágil de mantener |

🔲 **Mi lectura, sin ser decisión:** la opción B parece la más alineada con lo que el
proyecto ya hace (los datos ya viajan así) y con el principio de mínimo mantenimiento. Pero
**no la recomiendo en firme sin ver el script**, porque si el JS también se carga globalmente,
el problema es más grande que el CSS y conviene resolverlos juntos, de una vez, en vez de en
dos pasadas.

---

## 7. Veredicto — ¿reescribir o ampliar?

**Provisional, a falta del `<script>`.** Con lo que tengo:

| Capa | Estado | Veredicto |
|---|---|---|
| **PHP / datos** | `fetch_feed()` con caché, parseo completo de iTunes, detección de YouTube, sin proxies | ✅ **Sólido. No tocar la arquitectura.** Es una base buena |
| **HTML / estructura** | Completo y coherente, pero con `h1` propio y sin ARIA | ⚠️ **Retocar**, no reescribir |
| **CSS** | Funciona y se ve bien, pero 408 `!important`, 0 variables de marca, `@import` externo, breakpoints ajenos al estándar | ⚠️ **Candidato natural a rehacer cuando Carlota entregue el boceto** — que es justo lo que viene después |
| **JavaScript** | 🔲 **Sin ver** | 🔲 **Sin veredicto.** Aquí vive toda la complejidad de estado |

**Recomendación de método, no de resultado:** el CSS se va a rehacer de todas formas cuando
llegue el boceto de Carlota — es su trabajo definir cómo se ve esto. Así que la pregunta real
no es "¿reescribo el CSS?", sino **"¿el PHP y el JS aguantan un CSS nuevo encima?"**. El PHP
sí, con certeza. El JS, no lo sé todavía.

Lo que sí conviene hacer **antes** del boceto, porque condiciona el diseño: cerrar la
pregunta de la Sección 1 (¿12 canales de qué?) y decidir si la ficha de un santo con podcast
y la página de Charlas de la Biblia comparten presentación o no. Carlota necesita esa
respuesta para no diseñar dos veces.

---

## 8. Lo que NO recomiendo, y por qué

Por honestidad, y para ahorrar tiempo:

| No hacer | Por qué |
|---|---|
| ❌ Datos estructurados `PodcastEpisode`/`PodcastSeries` para SEO | Verificado: Google no los usa. Sección 4.1 |
| ❌ Un plugin de podcast de WordPress (Seriously Simple Podcasting, PowerPress…) | Están pensados para **alojar** el podcast en WordPress y generar el feed. Aquí es al revés: el feed lo genera Spotify y WordPress solo lo consume. Sería instalar un sistema entero para usar el 5% |
| ❌ El embed oficial de Spotify en vez del reproductor propio | Rompe el ADN visual, mete un iframe pesado de terceros, implicaciones de cookies, y pierde el acordeón por temporadas. El reproductor propio, pese a su deuda, hace más y mejor |
| ❌ Migrar cada episodio a una entrada de WordPress (CPT) | Multiplicaría por 36 el trabajo editorial de un solo canal, y el feed ya es la fuente de verdad. Solo tendría sentido si se quisiera un listado unificado de episodios de varios podcasts — que nadie ha pedido |
| ❌ Resolver el namespacing de IDs ahora | Sección 3.4: con un reproductor por página no molesta. Trabajo real sin problema real detrás |

---

## 9. Próximos pasos y preguntas abiertas

### Próximos pasos

1. **Pedir a Álvaro el bloque `<script>`** que sigue al `return $html_skeleton . $data_script;`
   — o confirmación de que es un snippet separado. **Desbloquea los puntos 6, 7, 8 y 9 de la
   tabla de la Sección 5.7 y el veredicto de la Sección 7.** Es el paso 1 de todo lo demás.
2. **Responder la pregunta de los 12 canales** (Sección 1.4). Bloquea el boceto de Carlota.
3. Corregir el shortcode antes de pegarlo, con la sintaxis de la Sección 3.5.
4. Comprobar en Local la discrepancia de `gfonts_load_method` (Sección 3.1).
5. Comprobar en Local si hay dos `<h1>` en una página con reproductor (Sección 3.3).
6. Decidir si se etiquetan las temporadas en Spotify for Podcasters (Sección 3.5) y, si sí,
   escribir la pauta para quien sube episodios.
7. Una vez resuelto 1 y 2: pasar a Carlota el encargo de boceto, ya con la arquitectura clara.
8. Cuando todo esté cerrado: volcar este documento en `02_REF_PODCAST.md`, que hoy está vacío.

### Preguntas abiertas

| # | Pregunta | Bloquea a | Quién responde |
|---|---|---|---|
| 1 | ¿Dónde está el bloque `<script>` del reproductor? | Prácticamente todo lo pendiente | Álvaro |
| 2 | ¿Cuáles son los 12 canales? | Boceto de Carlota, alcance de la Sección 1 | Carlitos / Álvaro |
| 3 | ¿La ficha de un santo con podcast y la página de Charlas de la Biblia comparten presentación, o son cosas distintas? | Boceto de Carlota | Carlota + Carlitos |
| 4 | ¿Se etiquetan las temporadas en Spotify, o se asume un acordeón único por canal? | Diseño del acordeón | Quien publique los episodios |
| 5 | ¿Se transcriben los capítulos que no traen texto, o solo se aprovechan los que ya lo tienen? | Alcance de la Sección 5.3 | Decisión de producto |
| 6 | `gfonts_load_method: "cdn"` en el export vs. "cambiado a Local" en el catálogo — ¿cuál es el estado real? | Cierre del apartado de fuentes | Álvaro, en Local |
| 7 | ¿El CSS del podcast se queda global (A), viaja con el shortcode (B) o se condiciona (C)? | Sección 6 | Carlitos, tras ver el script |

---

## 10. Anotaciones listas para trasladar — tú decides si y cuándo

Redactadas para copiar tal cual. No las aplico sin tu confirmación.

**Para `00_CORE.md` Sección 8 (Trampas técnicas conocidas):**

> `[tt_podcast]` — el atributo `seasons` debe ser JSON válido: comillas dobles dentro,
> comillas simples envolviendo (`seasons='{"1":"Nombre"}'`). Comillas simples anidadas
> rompen el parseo de WordPress **en silencio**: no da error, el mapa de temporadas
> simplemente llega vacío y los nombres no se aplican. Verificado ejecutando
> `shortcode_parse_atts()` real de WordPress contra el string.

**Para `CUADERNO_DEL_CONSTRUCTOR.md` — "⚠️ Cuidado con esto":**

> El agrupamiento por temporadas de `[tt_podcast]` depende del tag `<itunes:season>` del
> feed, no del código. Si el canal publica todo como temporada 1, el atributo `seasons` del
> shortcode no sirve de nada — hay que etiquetar las temporadas en Spotify for Podcasters
> primero. Confirmado contra el feed real de San Serafín (36 capítulos, todos temporada 1,
> con tres bloques de contenido claramente distintos). 16 septiembre 2026.

**Para `CATALOGO_ELEMENTOS_AVADA.md` — entrada nueva:**

> **Necesito un reproductor de podcast con lista de episodios por temporada**
> → **Elemento:** ninguno nativo. Code Snippet propio `[tt_podcast]` dentro de un Code Block.
> ✅ Es uno de los casos donde Avada genuinamente no ofrece nada equivalente: consume un feed
> RSS externo en el servidor, con caché, y construye una lista con estado de reproducción. El
> elemento Audio de Avada (Sección 5 de este catálogo) solo sirve para un audio suelto
> autoalojado y **no gobierna este reproductor** — ya está anotado así.

**Para `GUIA_AVADA_LOCAL.md` Sección 4.2 — confirmación de algo ya sospechado:**

> ✅ Confirmado contra el código real (16 septiembre 2026): el `@import` de Google Fonts que
> esta sección señalaba como deuda técnica existe literalmente, carga la fuente "Inter" desde
> `fonts.googleapis.com`, y se ejecuta en **todas** las páginas del sitio vía `wp_head`,
> tengan reproductor o no. La fuente ni siquiera se usa en el titular del reproductor, que
> tiene su propia pila del sistema.

---

*Para la mayor gloria de Dios · tiritaito.com*

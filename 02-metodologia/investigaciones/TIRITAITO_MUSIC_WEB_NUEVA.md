# TIRITAITO.COM — Tiritaito Music en la Web Nueva (modelo: el podcast)
**Cómo llevar las playlists de YouTube (Música, Vía Crucis, Seminarios y la novena de la Inmaculada) a la web nueva: fuente de datos, modelo, importador, piezas que dependen, opciones, recomendación, plan de pruebas y brief para el boceto**
*Investigación de Proyecto 2 (Hno C) — Fase 3 de 3 · entregado el 14 de octubre de 2026 · **revisado el 23 de octubre de 2026 con las decisiones de Carlitos (22 y 23 de octubre)** · Actualiza el documento del 16 de septiembre de 2026 (no se crea versión con sufijo) · **importador escrito el 30 de octubre de 2026 (Sección 6.8), sin ejecutar todavía en Local***
*Fuentes: archivos reales de la sesión (`music-data.json`, `Viacrucis.json`, `NovenaYT.json`, fragmentos del generador v1-15) · documentos del repositorio (`PODCAST_ESTRUCTURA.md`, `ORACIONES_ESTRUCTURA.md`, `00_CORE.md`, la versión anterior de este documento) · documentación oficial de YouTube, Google Cloud, Avada y WordPress consultada el 14 de octubre de 2026*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento y cómo leerlo

Responde a una pregunta: **¿cómo se lleva Tiritaito Music (y las demás playlists de YouTube) a la web nueva con formato nativo de Avada, con el mínimo código y sin teclear cada vídeo a mano?** Es el resultado de tres fases: inventario (Fase 1), investigación externa (Fase 2) y esta síntesis con recomendación y plan de pruebas (Fase 3). **No contiene código de producción**: solo investiga, recomienda y planifica.

**Versión del 23 de octubre:** incorpora las decisiones de Carlitos del 22 y 23 de octubre (Sección 11.1): «Todas» sin categoría propia (su papel lo hace la pestaña «All» de Avada), **los títulos se importan tal como están en YouTube** (si hay que cambiarlos, se cambian en YouTube), fichas compartibles, `vid_` como prefijo común, R1 + R3 para las retiradas, avisos de importador parado con semáforo, «A y luego D», y **Carlota hace los diseños visuales solo cuando todas las pruebas hayan pasado y todo funcione**. «(IX) La Última Cena» se queda donde está. El 23 de octubre se cerraron además el mes descendente de Seminarios y las novenas a mano, y se incorporaron los IDs de las playlists reales (Sección 2.6). **Las nueve pruebas de Avada y YouTube las hace solo Álvaro** (decisión del 25 de octubre). El 28 de octubre se incorporan los resultados de las pruebas del bloque A (Sección 13.3). La Sección 11 distingue lo cerrado de lo pendiente.

| Si necesitas… | Ve a… |
|---|---|
| Un resumen rápido | Sección 1 |
| Los datos reales de las playlists | Sección 2 |
| Qué se corrigió y qué hallazgos son nuevos | Sección 3 |
| Cómo se lee YouTube y cómo se crea la clave de la API | Sección 4 |
| Cómo se guarda cada canción en WordPress | Sección 5 |
| Cómo funciona el importador | Sección 6 |
| Cómo encajan Lenguas, Vía Crucis, Inmaculada y Sagrados Corazones | Sección 7 |
| Qué hace Avada solo y qué no | Sección 8 |
| Las opciones, sus riesgos y qué se rehace si se cambia de opinión | Sección 9 |
| **La recomendación** | **Sección 10** |
| **Decisiones tomadas y las que quedan** | **Sección 11** |
| Reglas de YouTube, rendimiento, accesibilidad y SEO | Sección 12 |
| **El plan de pruebas en Local** | **Sección 13** |
| Qué hay que actualizar en otros documentos | Sección 14 |
| Próximos pasos y preguntas abiertas | Sección 15 |
| Referencia visual (Hakuna), direcciones de boceto y brief para Carlota | Sección 16 |

**Estados** (mismos símbolos que el resto del repositorio):

| Marcador | Significa |
|---|---|
| ✅ | Comprobado: en un archivo real de esta sesión, en documentación oficial consultada hoy, o con un cálculo que se puede repetir |
| ☑ | Declarado (por Carlitos, por otro documento o por fuentes de terceros), **sin comprobar aquí** |
| 🔲 | Sin probar o sin construir |
| ❌ | Probado y no funciona |
| ⚠️ | Decidido o encontrado, pero con algo sin cerrar |
| 📖 | Documentado por Avada, sin probar en Local |

**De dónde sale cada dato:** `[R]` = repositorio o archivos de la sesión · `[W]` = documentación oficial en la web.

**Qué he leído y qué no (para no dar nada por visto):**

| Documento | ¿Leído en esta sesión? |
|---|---|
| `TIRITAITO_MUSIC_WEB_NUEVA.md` (16 sept), `PODCAST_ESTRUCTURA.md`, `00_CORE.md`, `ORACIONES_ESTRUCTURA.md` | ✅ Sí, completos |
| `music-data.json` (subido como «music-data (1).json»), `Viacrucis.json`, `NovenaYT.json`, fragmentos del generador v1-15 | ✅ Sí. Los números de la Sección 2 salen de recontar los archivos |
| `ALCANCE_WEB_NUEVA.md`, `METODOLOGIA_CONSTRUCCION.md`, `CATALOGO_ELEMENTOS_AVADA.md`, `GUIA_AVADA_LOCAL.md`, documento de Seminarios | 🔲 **No.** Lo que se dice de ellos viene de citas en los documentos que sí leí o del encargo de esta fase (se marca ☑) |
| El código del módulo `.mp-*` de la web vieja y el HTML de la app | 🔲 **No** estaban en esta sesión. Los hallazgos #3–#7 y #9–#12 del informe anterior **no se han vuelto a comprobar** |

**Glosario mínimo** (cada término se explica una vez):

| Término | En una frase |
|---|---|
| Tipo de contenido (CPT) | Una «clase» propia de entradas de WordPress, como «canción» o «episodio», con su lista en el panel |
| Taxonomía y categoría | La **taxonomía** es el «cajón» que agrupa fichas (aquí, `categoria_musica`); cada **categoría** (en WordPress, «término») es un valor dentro del cajón: Navidad, Virgen María… WordPress trae dos taxonomías de fábrica llamadas «Categorías» y «Etiquetas»; la nuestra es propia |
| Casilla (ACF) | Un campo propio de una ficha, por ejemplo «dirección del vídeo» |
| API y clave de API | La puerta por la que un programa pide datos a YouTube; la clave es la contraseña que identifica a quién pregunta |
| Cuota | El número de preguntas que YouTube deja hacer cada día con una clave |
| Hash | Una «huella» del contenido de una ficha: si la huella no cambia, no hace falta reescribirla |
| WP-Cron | El reloj interno de WordPress que lanza tareas programadas (como la sincronización diaria) |
| `Referer` | La cabecera con la que el navegador dice a YouTube desde qué web se pide el vídeo |
| Video Facade | Una miniatura que sustituye al reproductor hasta que alguien pulsa play |
| `menu_order` | El campo nativo de WordPress para ordenar entradas a mano; Avada puede ordenar por él |
| Sitemap / noindex | El mapa de páginas que se ofrece a Google / la orden de «no indexar esta página» |
| `wp-config.php` | El archivo de configuración de WordPress en el servidor; ahí se guardan secretos |
| Off-Canvas | Un panel que se desliza desde un lado de la pantalla sobre la página |
| Vista previa al compartir | La tarjeta con título e imagen que aparece al pegar un enlace en WhatsApp o Telegram |
| Semáforo | Un indicador verde / amarillo / rojo del estado del importador |

---

## 1. Resumen en 10 líneas

1. **Qué cambia:** Tiritaito Music deja de ser un JSON leído por un módulo de código y pasa a ser contenido de WordPress: **una canción = una ficha**, con una **categoría** por cada playlist en la que está (menos «Todas», cuya pestaña es la «All» de Avada). Avada la dibuja de forma nativa y Carlota y Álvaro controlan el diseño.
2. **Quién la rellena:** un **importador propio, en el servidor, que consulta la API de YouTube cada día**. Nadie teclea vídeos.
3. **Un solo importador** sirve para Música, Vía Crucis y Seminarios, y para **Lenguas cuando acaben las pruebas**: un área nueva es una fila nueva en la tabla de ajustes, más su tipo de contenido. **Inmaculada, Sagrados Corazones y Espíritu Santo van a mano** (9 días fijos, Sección 7).
4. **Datos reales ✅:** 5 playlists, 88 entradas de YouTube, 64 vídeos distintos, **62 canciones** tras quitar 2 subidas duplicadas. Vía Crucis: 28 vídeos en 2 playlists. Inmaculada: 9.
5. **Coste de la API ✅:** unas **15–20 unidades al día de 10.000** (≈0,2 %). Los visitantes nunca llaman a YouTube.
6. **Clave:** nueva, en un proyecto de Google Cloud propio y en la cuenta de Tiritaito, limitada a «YouTube Data API v3», **sin restricción de aplicación** (decisión mantenida el 22 de octubre), en `wp-config.php`; **una clave distinta para Local**.
7. **Títulos:** el importador los sube **tal como están en YouTube** (decisión del 23 de octubre) y, si hace falta cambiar alguno, se cambia en YouTube. Quedan con su sufijo («/ Tiritaito Music» en 63 de 64; media de 45 caracteres, máximo 98). La regla de «título limpio y autora aparte», ya probada sobre los títulos reales (Sección 2.5), queda como **mejora futura**, sin rehacer nada.
8. **Cada canción tiene dirección propia y es compartible** (sustituye al enlace `?mp_play=`). Necesita una plantilla de ficha y comprobar la vista previa al compartir.
9. **Presentación:** **A y luego D.** Dirección 1 con un hueco reservado para el reproductor con cola. **Carlota diseña después de que todas las pruebas hayan pasado y todo funcione** (Sección 10). El importador avisa cuando se para: **amarillo a 10 días, rojo a 20, con semáforo en la app de Creators**.
10. **Estado:** el importador **está escrito (30 de octubre) pero sin ejecutar en Local**: sintaxis validada y 33 comprobaciones de sus funciones de cálculo; los 25 casos M0–M24 siguen en 🔲 (Sección 6.8). **Las pruebas de Avada y YouTube están hechas salvo A3 y A8** (Sección 13.3); siguen el importador con sus **25 casos** y, solo entonces, el diseño de Carlota. Quedan **3 respuestas pendientes de Carlitos** (Sección 11.2).

---

## 2. Datos reales (recontados sobre los archivos)

### 2.1 Música — `music-data.json` ✅ `[R]`

| Playlist | Entradas | Notas |
|---|---|---|
| Todas | 65 | Contiene 64 vídeos distintos (`tHN2sKkguYA` aparece 2 veces). **No crea categoría** (28 oct): su papel lo hace la pestaña «All» de Avada |
| Espíritu Santo | 7 | Todas están también en «Todas» |
| Virgen María | 7 | Ídem |
| Navidad | 7 | Ídem |
| San José | 2 | Ídem |
| **Total** | **88 entradas** | **64 vídeos distintos** |

- Las cuatro categorías suman **23 canciones, sin solapes entre ellas** hoy. Las otras **41 solo están en «Todas»** y se ven con la pestaña «All» de Avada.
- Las miniaturas del JSON de Música son todas `img.youtube.com/vi/<id>/mqdefault.jpg` (88 de 88): las **construye el generador**, no vienen de YouTube.
- 63 de 64 títulos contienen «Tiritaito» (solo falta en «Ven, Jesús a nuestras vidas»). **18 variantes distintas del sufijo** («- Hna. Emi / Tiritaito Music» en 32; el resto con espacios, barras y autoras distintas). Unas 44 canciones llevan «Hna. Emi». 13 títulos van en MAYÚSCULAS (11 tras quitar los duplicados). Longitud media 45 caracteres, máxima 98.

### 2.2 La identidad de una canción no es su título ✅

| Caso | Vídeos | Qué es | Qué implica |
|---|---|---|---|
| «Él me ama» | `P-3LMZvoH_Q` y `DETq7RBupns` (solo en «Todas») | **La misma canción subida dos veces** (un título en minúsculas y otro en MAYÚSCULAS) | Se deja un vídeo y se quita el otro **de las playlists** (no de YouTube) |
| «Bajo tu manto» | `tHN2sKkguYA` (está en «Virgen María» y, **dos veces**, en «Todas») y `ufGu9PJwH7c` (solo en «Todas») | **La misma canción subida dos veces**, con títulos idénticos | Igual. ⚠️ Si el bueno fuera `ufGu9PJwH7c`, hay que **añadirlo también a «Virgen María»** |
| «Hágase en mí» | `b0NmnYywmas` (en «Virgen María») y `YiFwoQSCQxs` | **Dos canciones distintas** con casi el mismo título | **Nunca se fusionan por título**; la identidad es el ID del vídeo |
| `tHN2sKkguYA` en «Todas» | Dos entradas del mismo ID dentro de una misma lista | Un repetido accidental | El importador lo ignora y avisa |

Tras limpiar las dos subidas duplicadas quedan **62 canciones**.

### 2.3 Vía Crucis e Inmaculada ✅ `[R]`

| Archivo | Playlist | Vídeos | Orden observado en el archivo |
|---|---|---|---|
| `Viacrucis.json` | «Via Crucis de la Divina Misericordia» (sin tilde en «Via») | 14 («1º Estación» a «14º Estación») | 7, 9, 10, 11, 12, 13, 14, 1, 2, 3, 4, 5, 6, 8. **No es numérico ni alfabético** |
| `Viacrucis.json` | «Con Jesús hacia la Cruz» | 14: **1 vídeo introductorio sin número** («Con Jesús hacia la Cruz - Novena a la Divina Misericordia») + «(I)» a «(XIII)» | **Coincide exactamente con el orden alfabético de los títulos** (comprobado). Por eso «(IX)» va antes que «(V)» |
| `NovenaYT.json` | «Novena de la Inmaculada» | 9: «Novena a la Inmaculada - Día 1» a «Día 9» | En orden |

- **«(IX): La Última Cena»** está numerada IX, entre la VIII (Jesús cae con la Cruz) y la X (Jesús es despojado). Cronológicamente iría antes de la I, pero **Carlitos confirmó el 23 de octubre que se queda ahí**: se ordena por el número del título.
- Los títulos de Vía Crucis llevan el sufijo repetido y desigual («Vía Crucis de la Divina Misericordia / Tiritaito», «Vía Crucis Divina Misericordia / Tiritaito», «…/Tiritaito» sin espacio).
- `Viacrucis.json` y `NovenaYT.json` usan miniaturas `i.ytimg.com` (el dominio que devuelve la propia API); el generador v1-15 usa `img.youtube.com`. Esto confirma que **`Viacrucis.json` no salió del generador v1-15** (tampoco coincide el nombre de archivo con el `viacrucis-data.json` que genera la app) ✅.

### 2.4 El orden del JSON de Música, deducido del código ✅/☑

La función `ordenMusica()` del generador (fragmentos v1-15) puntúa los títulos y ordena de menor a mayor. Aplicada a los 64 títulos de Música **solo uno puntúa distinto de 9999: «Selfie al Cielo!… Semana Santa 2026»** (sube por el «2026» del título). Como el resto empata, **el orden de «Todas» en el JSON es el orden de la playlist en YouTube, salvo ese vídeo, que sube al primer puesto** ✅ (cálculo) / ☑ (no comprobado contra YouTube).

**`ordenMusica()` falla con Vía Crucis** ✅ (probado con los títulos reales): puntúa el vídeo introductorio con un 9 (la palabra «Novena» coincide con el ordinal «noven»), igual que a «(IX)»; y deja «(XIII)» en 9999 porque su tabla de números romanos termina en XII. Si se reutilizara tal cual, el orden saldría mal.

### 2.5 Mejora futura: título limpio y autora aparte (regla ya probada sobre los títulos reales) ✅ `[R]`

*Decisión del 23 de octubre: la v1 sube los títulos tal como están en YouTube. Esta regla **no se construye ahora**; queda probada por si se quiere más adelante.*

**La regla:** (1) se quita del final «Tiritaito Music» o «Tiritaito» con su separador (`-`, `/`, `|`); (2) en Vía Crucis se quitan además sus textos propios («Vía Crucis de la Divina Misericordia», «Vía Crucis Divina Misericordia»); (3) si lo que queda tras el último separador empieza por «Hna.», «Hno.», «Hermana» o «Hermano», **eso es la autora** y se mueve a su casilla.

**Resultado sobre los 64 títulos de Música:**

| Resultado | Canciones |
|---|---|
| **Con autora detectada** | **53 de 64**: «Hna. Emi» 42, «Hna. Alba» 6, «Hno. Nico» 1, «Hna. Raldu» 1, «Hna. Cori» 1, «Hna Emi» (sin punto) 1 y una canción de **cuatro voces** («Hna. Alba, Hna. Emi, Hna. María y Pau F.») |
| Sufijo quitado, **sin autora** en el título | 10 |
| Sin sufijo (no se toca) | 1 («Ven, Jesús a nuestras vidas») |
| Casos raros (queda «Tiritaito» o un separador suelto) | **0** |

- **Longitud del título:** media **18** caracteres, máximo **55** (antes: 45 y 98).
- **Vía Crucis:** los 28 títulos quedan sin el sufijo; «Con Jesús hacia la Cruz» se conserva porque forma parte del título; el vídeo introductorio («…Novena a la Divina Misericordia») no cambia.
- **«Hágase en mi» queda dos veces con el mismo título**; una lleva autora («Hna. Emi») y la otra no. Mostrar la autora en la tarjeta es lo que las distingue.
- **Límite de la regla:** los nombres sin prefijo («Hna.», «Hno.»…) no se detectan como autora y se quedan en el título. Cualquier caso dudoso se avisa y **nunca se altera a ciegas**.

### 2.6 Las playlists reales de YouTube ✅ `[R]`

*Leídas el 23 de octubre a partir de las direcciones que pasó Carlitos. El recuento es el que muestra la página pública de YouTube.*

| Playlist en YouTube | ID | Vídeos hoy | Área | Observaciones |
|---|---|---|---|---|
| Tiritaito Music | `PLX21N4KPkRTRJOH4-uPMq2YS62-l-hePc` | **70** | Música («Todas») | **El JSON tenía 65** (+5): ¿vídeos nuevos o no listados? (N22, A3). Su nombre visible en la web será «Todas» |
| Virgen María | `PLX21N4KPkRTStJJCgbOy05SGymdCgtA0m` | 7 | Música | ✅ Coincide con el JSON |
| San José | `PLX21N4KPkRTQ1Vh96bifV73J78KfYXHSD` | 2 | Música | ✅ Coincide con el JSON |
| *(sin abrir)* | `PLX21N4KPkRTSUDoivrUR3PJ4LmylhW2Ep` | ? | Música (¿Espíritu Santo o Navidad?) | ☑ Hipótesis por eliminación, sin comprobar |
| *(sin abrir)* | `PLX21N4KPkRTQWUiQ_IOwJ2GtsI8U6vEPa` | ? | Música (¿Espíritu Santo o Navidad?) | ☑ Ídem |
| Con Jesús hacia la Cruz | `PLX21N4KPkRTTz6ILAWvbk1a7SBywzw_3O` | **15** | Vía Crucis | El JSON tenía 14 (N22) |
| Via Crucis de la Divina Misericordia 2024 / Tiritaito | `PLX21N4KPkRTR9zSSsTdq85v3Oc4qYecP1` | **15** | Vía Crucis | El JSON tenía 14, y el nombre en el JSON no llevaba «2024» (N22) |
| Seminarios | `PLX21N4KPkRTR8_vFAGAlKswgbV7f_EF4J` | 29 | Seminarios | El recuento público no dice cuántos son ocultos (A3). **Una sola playlist para los 5 tipos** (P6) |
| Lenguas | `PLX21N4KPkRTQotWXk-qpVQSSzaXGuFbwU` | 11 | Lenguas (tras las pruebas) | — |
| Lenguas Ejército | `PLX21N4KPkRTRjM3ZaOA25EfPwOpojpvU8` | 8 | — | Subconjunto de Lenguas con solo las del Ejército. **No se importa por ahora**; podría servir para la página del Ejército o para Conecta cada día |
| Pentecostés | `PLX21N4KPkRTSkCX7g6WnlphX2mWSvFCT4` | ? | A mano | No se pudo abrir desde aquí |
| Sagrados Corazones | `PLX21N4KPkRTSY6vRwaGMZORrCifg2g_Gb` | ? | A mano | No se pudo abrir desde aquí |

- **Cuatro playlists no se pudieron abrir desde aquí** (la lectura devolvió un error). Pueden ser privadas, tener una errata en el ID o ser un bloqueo del lector que usé. **Importa:** una playlist **privada** no la lee una clave de API. Si dos de ellas son Espíritu Santo y Navidad, el generador actual sí las leyó, así que probablemente no sean privadas. Se confirma con la prueba A3 y abriéndolas en una ventana de incógnito (P5).
- Los IDs de este cuadro son públicos; no son secretos, a diferencia de la clave de API.

---

## 3. Hallazgos

### 3.1 Correcciones al informe del 16 de septiembre

| # | Antes | Ahora | Estado |
|---|---|---|---|
| **#2** | El módulo hacía una petición a `youtube.com/oembed` por pista para descartar vídeos privados; con «5 playlists × 30 canciones» eran 150 peticiones | **Corregido.** El generador de Música **ya filtra solo vídeos públicos al generar** (`getPlaylistItems(…, soloPublicos = !isSeminarios)`), así que las llamadas oEmbed del módulo eran **redundantes**. Con los datos reales eran **88 peticiones** por carga, no 150. Basta quitarlas; **no hace falta guardar `privacyStatus`**. Siguen siendo ciertos el pintado bloqueado hasta que terminan todas y el `.catch` que se traga los fallos. **Matiz nuevo:** un vídeo que pase a privado después de generar el JSON seguiría en la web hasta la siguiente sincronización (≤ 24 h con el importador) | ✅ `[R]` (código v1-15) |
| **#8** | «El contador del hero usa `allT.length`, que es solo la primera playlist, no el total: el dato es falso» | **Corregido.** La primera playlist **es «Todas»**, que ya contiene todo. El contador no mostraba «solo una categoría»: mostraba **65 entradas**, cuando lo real es 64 vídeos distintos y 62 canciones (cuenta un ID repetido y las dos subidas duplicadas). La parte «M categorías» **no se ha podido recomprobar** (no hay código del módulo en esta sesión) | ✅ cifra / 🔲 «M categorías» |
| #1 | `DATA_URL` escrita a mano: el circuito app → web está roto en el último tramo | Sigue siendo cierto **para el módulo viejo**. **Desaparece** con el importador (ya no hay JSON intermedio) | ☑ |
| #3–#7, #9–#12 | Autoplay que no hace nada, variables en `:root`, deriva de color, sin «Yeah Papa», radios fuera de token, buscador solo de la pestaña activa, URLs a producción, `z-index`, `!important` | **Sin cambios y sin recomprobar.** Solo importan si se mantiene un módulo de código (opciones C y D de la Sección 9) | ☑ |

### 3.2 Hallazgos nuevos de las Fases 1, 2 y 3

| # | Hallazgo | Por qué importa | Fuente | Estado |
|---|---|---|---|---|
| N1 | **La identidad es el ID del vídeo, no el título** (2 subidas duplicadas, 2 canciones con título casi igual) | Cualquier fusión por título destruiría «Hágase en mí» | Sección 2.2 | ✅ `[R]` |
| N2 | **Las 4 categorías cubren 23 de 64 canciones**; las otras 41 solo están en «Todas», (la pestaña «All» de Avada las muestra todas) | Filtrar por una categoría concreta solo ayuda a una parte del catálogo; «All» muestra el resto | Sección 2.1 | ✅ `[R]` |
| N3 | **`snippet.publishedAt` es la fecha en que el vídeo se añadió a la playlist, no la fecha del vídeo.** La fecha del vídeo es `contentDetails.videoPublishedAt`, y **el generador actual no pide la parte `contentDetails`** (solo `snippet,status`) | «Por orden de recientemente subidos» tiene tres lecturas posibles (fecha del vídeo, fecha de añadido, posición). El importador debe pedir `snippet,contentDetails,status` | API y código v1-15 | ✅ `[W]` `[R]` |
| N4 | **El orden de las playlists de Vía Crucis en el JSON no sirve de prueba del orden de YouTube**: una es alfabética y la otra no sigue ninguna regla | Si la playlist 2 está alfabética en YouTube, la regla «posición» daría el orden equivocado | Sección 2.3 | ✅ cálculo / 🔲 YouTube |
| N5 | `ordenMusica()` falla con Vía Crucis (la intro puntúa 9, «(XIII)» queda en 9999) | La regla de respaldo «número del título» necesita un analizador nuevo (arábigos con «º», romanos hasta XX, e ignorar «Novena») | Sección 2.4 | ✅ `[R]` |
| N6 | **Error 153 de YouTube** («Video player configuration error»): el reproductor exige que la petición identifique a la web mediante la cabecera `Referer`. El historial oficial de la IFrame Player API lo añade el 9 de julio de 2025. Se arregla con `referrerpolicy="strict-origin-when-cross-origin"` en el iframe o en la cabecera de la página | Un `Referrer-Policy` demasiado estricto del servidor o de un plugin de seguridad deja vídeos en blanco. **La fila falta en `00_CORE.md` Sección 8** | `[W]` | ✅ |
| N7 | **Regla de los 30 días:** los datos que se piden a la API sin login de nadie solo pueden guardarse un tiempo limitado: **como máximo 30 días naturales; pasado ese plazo hay que refrescarlos o borrarlos** | La sincronización diaria lo cumple por diseño, pero una **canción retirada (en borrador) deja de refrescarse** y un importador parado más de 30 días incumple. Ver Sección 6.5 | `[W]` Developer Policies III.E.4 | ✅ |
| N8 | **Descargar la miniatura como imagen de la Biblioteca de Medios** (el plan alternativo de la prueba 4) puede chocar con la prohibición de guardar copias de contenido audiovisual de YouTube y, en todo caso, cuenta como dato de la API sujeto a los 30 días. La política **no aclara** si una miniatura es «contenido audiovisual» | Preferir enlazar la miniatura por su URL; usar la descarga solo si la prueba 4 lo exige y con esta cautela | `[W]` III.E.1 y III.E.4 | ⚠️ |
| N9 | **«Made for Kids»:** si se incrusta en una web un vídeo marcado así por YouTube, hay que desactivar el rastreo. El estado se lee con `videos.list` (`status.madeForKids`) | Tiritaito Music tiene canciones que podrían estar marcadas. Remedio barato: activar **«Load Via YouTube No Cookie»** en todos los vídeos y que el importador lea el estado una vez | `[W]` III.E.4.j y guía oficial | ✅ norma / 🔲 qué vídeos |
| N10 | **Un proyecto de Google Cloud por cliente:** la política pide un único proyecto de API por cada «cliente de la API» y que no se use para varios | La clave nueva del importador debe vivir en **un proyecto nuevo**, distinto del de la clave que hoy usa la app de Creators en el navegador | `[W]` III.D.1.c | ✅ (interpretación: ☑) |
| N11 | **Cada ficha pública genera una URL y entra en el mapa de páginas (`/wp-sitemap.xml`)**: WordPress incluye por defecto todos los tipos de contenido públicos | **Decidido (C9):** las fichas son compartibles, pero llevan `noindex` y se sacan del mapa. Para los seminarios ocultos es lo que evita que «oculto» deje de serlo | `[W]` WordPress 5.5 | ✅ |
| N12 | **Post Cards solo muestra por defecto las entradas publicadas** (la opción «Post Status» vacía = publicadas), así que una canción retirada a borrador **desaparece sola de la web** | Confirma que «borrador» es un retiro limpio. Ver también N7 | `[W]` Avada | ✅ |
| N13 | **Overlays y reproductor:** las reglas de YouTube prohíben poner capas por encima del reproductor o de sus controles y exigen un reproductor de al menos 200×200 px (recomendado 480×270) | Condiciona el diseño de tarjetas con vídeo dentro de un carrusel y de las ventanas emergentes | `[W]` Required Minimum Functionality | ✅ |
| N14 | **El título largo es un problema de diseño** (media de 45 caracteres, máximo 98; «/ Tiritaito Music» en 63 de 64) | **Decidido el 23 de octubre: se acepta tal cual** y, si molesta, se cambian los títulos en YouTube. Carlota deberá diseñar la tarjeta para títulos de hasta 98 caracteres. La limpieza queda como mejora futura (Sección 2.5) | Sección 2.5 | ✅ `[R]` |
| N15 | **Espíritu Santo también tiene «Vídeo del día»** (apartado 1 de cada día, casilla `nes_video`); el encargo de esta fase solo menciona los vídeos de Sagrados Corazones | Si esos 9 vídeos están en una playlist, se plantea la misma decisión que con la Inmaculada | `ORACIONES_ESTRUCTURA.md` 3.6 | ✅ `[R]` / 🔲 |
| N16 | **Sagrados Corazones, apartado 9:** la canción «Enséñennos a amar» es hoy una tarjeta del módulo Tiritaito Music y es **una canción real de la lista**: `Rwh6N8Q7D1s` | Se resuelve con un elemento YouTube de Avada con ese ID fijo escrito en el molde; no depende del importador | `ORACIONES_ESTRUCTURA.md` 3.6 y `music-data.json` | ✅ `[R]` |
| N17 | El generador de Seminarios **no usa la fecha de la API**: extrae «mes año» de la primera línea de la descripción o del título (`extraerFecha`). **Y ordena año descendente pero mes ascendente dentro de cada año**, no «fecha descendente» como escribí en la primera entrega | Con ese orden, «los 2 más recientes» del panel serían los meses más antiguos del último año. **Decidido (C3):** año y mes descendentes, desempate por la fecha del vídeo | Fragmentos v1-15 | ✅ `[R]` |
| N18 | Si algún día se limpian los títulos, **«Hágase en mi» quedaría dos veces con el mismo título** | Hoy no pasa: los títulos de YouTube son distintos | Sección 2.5 | ✅ `[R]` |
| N19 | **13 títulos en MAYÚSCULAS** (11 tras quitar los duplicados) | Se cambiarán en YouTube si hace falta (23 oct) | Sección 2.1 | ✅ `[R]` |
| N20 | Los 28 títulos de Vía Crucis llevan **otro sufijo** distinto del de Música | Solo importa si algún día se limpian: los «textos a quitar» serían **por área** | Sección 2.3 | ✅ `[R]` |
| N21 | **La ficha compartible necesita plantilla y vista previa:** WordPress suele tomar la imagen de la vista previa de la «imagen destacada», y nuestra miniatura vive en una casilla ☑ | Si la vista previa sale sin imagen, hace falta un snippet pequeño | Sección 12 | 🔲 (A9) |
| N22 | **Las playlists reales tienen más vídeos que los JSON**: «Tiritaito Music» 70 frente a 65; «Con Jesús hacia la Cruz» 15 frente a 14; «Vía Crucis… 2024» 15 frente a 14 | Pueden ser vídeos nuevos o **no listados** que el generador descarta. Si son no listados, **no se importan** (Música no incluye ocultos) | Sección 2.6 | 🔲 (A3) |

---

## 4. La fuente de datos: la API de YouTube

### 4.1 Lo que se ha comprobado en documentación oficial `[W]` (consultada el 14 de octubre de 2026)

| Dato | Resultado | Estado |
|---|---|---|
| `playlistItems.list` cuesta **1 unidad** por llamada | Confirmado | ✅ |
| `maxResults` acepta de 0 a 50 | Confirmado; las páginas siguientes se piden con `pageToken` | ✅ |
| Cupo por defecto | **10.000 unidades diarias** para todos los métodos salvo `search.list` y `videos.insert` | ✅ |
| `snippet.position` | Orden del vídeo en la playlist, **base cero** (el primero es 0) | ✅ |
| `contentDetails.videoPublishedAt` | Fecha en que el **vídeo** se publicó en YouTube | ✅ |
| `snippet.publishedAt` | Fecha en que el elemento se **añadió a la playlist** (no la del vídeo) | ✅ |
| `status.privacyStatus` | Privacidad del elemento | ✅ |
| `snippet.thumbnails` | La API devuelve las miniaturas (`medium` = 320×180) | ✅ |
| Sin login de nadie basta una clave de API; con datos privados de un usuario hace falta OAuth | Confirmado | ✅ |
| Estado «Made for Kids» | `videos.list` con la parte `status` → `status.madeForKids`; cuesta 1 unidad por llamada de hasta 50 vídeos | ✅ |

**El RSS de playlist queda descartado** (unas 15 entradas, solo fuentes de terceros, sin documentación oficial, no probado desde el entorno) ☑. No se ha reabierto.

### 4.2 Coste previsto

| Concepto | Cálculo | Unidades/día |
|---|---|---|
| Música | «Todas» (65 → 2 páginas) + 4 categorías (1 cada una) | 6 |
| Vía Crucis | 2 playlists de 14 | 2 |
| Inmaculada (opcional) | 1 playlist de 9 | 1 |
| Seminarios | Número de vídeos desconocido; supongo hasta 5 páginas | ≤ 5 |
| Refresco de retiradas y «Made for Kids» | `videos.list`, 1 unidad por 50 vídeos | ≤ 3 |
| **Total diario** | | **≈ 15–20 (≈ 0,2 % del cupo)** |

Aunque se pulsara «Sincronizar ahora» 100 veces al día serían unas 1.700 unidades. El cupo no es un riesgo.

### 4.3 Qué es «restringir la clave» (la duda del 13 de octubre)

Una clave de API puede llevar **dos candados**. Google los llama así:

1. **Restricción de API — «¿qué puede hacer esta clave?».** Se limita a servicios concretos (aquí, solo «YouTube Data API v3»). Si alguien robara la clave, solo podría hacer lo que esa API permite. **En la consola es obligatoria**: no deja crear la clave sin al menos una restricción de API ✅ `[W]`.
2. **Restricción de aplicación — «¿desde dónde puede usarse?».** Hay cuatro tipos y solo se puede elegir **uno**: *sitios web* (comprueba el `Referer` del navegador), *direcciones IP* (los servidores o tareas programadas permitidos), *apps Android* y *apps iOS*. Sin restricción de aplicación, la clave **funciona desde cualquier sitio** ✅ `[W]`.

**Por qué ni «sitios web» ni «IP» encajan aquí:**

| Candado | Problema para nuestro importador |
|---|---|
| Sitios web | Comprueba un dato que envían los **navegadores**. Una petición hecha por PHP desde el servidor **no lleva `Referer`**; por tanto, esa clave **fallaría** (deducido del funcionamiento descrito por Google ☑) |
| Dirección IP | Habría que dar a Google la IP desde la que sale el servidor. En un hosting compartido como Raiola la IP de **salida** puede **no ser la del sitio y cambiar sin aviso**; no está documentado ☑. Si cambia, la importación se rompería sin que nadie lo note |

**Decisión tomada el 13 de octubre:** clave sin restricción de aplicación, **con restricción de API** (solo YouTube Data API v3).

**Qué riesgo se asume:** si la clave se filtrara, cualquiera podría gastar nuestro cupo diario de YouTube hasta agotarlo (no podría tocar nada más). **Cómo se reduce:** la clave vive solo en `wp-config.php` (nunca en la base de datos, nunca en el navegador, nunca en el repositorio ni en un documento), y se puede **rotar** en minutos creando otra clave, cambiando una línea y borrando la antigua.

**Valoración objetiva (22 de octubre). Decisión mantenida: sin restricción de aplicación.**

- **Cómo podría filtrarse la clave, siendo realistas:** (1) alguien con acceso al hosting o a un administrador; el repositorio lista el plugin «Administrador Archivos WP», que puede abrir `wp-config.php`; (2) una copia de seguridad completa del sitio que incluya ese archivo (como la de Local a Google Drive: deducido de cómo funcionan, no comprobado); (3) pegarla en un chat, un documento o el repositorio. **Los visitantes no pueden verla**, porque no sale del servidor.
- **Qué pasaría si la usaran otros:** solo podrían llamar a YouTube Data API v3 y gastar nuestro cupo diario. No tocan la web, el canal ni datos privados. Ese día el importador no leería, pero «una caída nunca borra nada». Se arregla rotando la clave.
- **Dinero:** no he visto en la documentación consultada ningún cobro asociado a esta API, solo cuota. No lo he verificado a fondo; no vincular una tarjeta de facturación salvo que la consola lo exija.
- **Si la web crece:** **por tráfico, no cambia nada**, porque los visitantes nunca llaman a YouTube; lo hace el importador una vez al día. Solo crece el uso si crece el número de playlists y vídeos. **Cuándo revisarlo:** si se cambia a un hosting con IP de salida fija (entonces conviene restringir por IP), o si más personas llegan a tener acceso al servidor.
- **Medida práctica:** **una clave distinta para Local**, también restringida a la API, para que la de producción no salga de producción.
- **Veredicto:** riesgo bajo, impacto bajo-moderado y reversible. No justifica complicarlo hoy.


### 4.4 Pasos para crear la clave (con la cuenta de Tiritaito, decisión C13)

*Camino según la documentación oficial de YouTube y de Google Cloud. Los nombres exactos de los menús de la consola pueden variar un poco; la guía es la página «Credenciales» (`console.cloud.google.com/apis/credentials`).*

1. **Entrar en Google Cloud** (`console.cloud.google.com`) con la cuenta de Google que sea la dueña del proyecto de la web (preferible una cuenta de Tiritaito, no personal).
2. **Crear un proyecto nuevo**, solo para el importador (sugerencia de nombre: «Tiritaito Web — Importador YouTube»). **No** reutilizar el proyecto de la clave actual de la app de Creators (N10).
3. **Habilitar «YouTube Data API v3»** en ese proyecto (Biblioteca de APIs → buscar → Habilitar). Google exige que la API esté habilitada antes de poder restringir una clave a ella ✅.
4. **Credenciales → Crear credenciales → Clave de API.**
5. **Restricciones de API:** «Restringir clave» → marcar **solo «YouTube Data API v3»**. **Restricciones de aplicación:** «Ninguna». Guardar.
6. **Copiar la clave** y guardarla en `wp-config.php` como una constante (nombre propuesto: `TT_YT_API_KEY`). El nombre de la constante puede constar en los documentos; **el valor, jamás**.
7. **Comprobar** en la página «Cuotas» del proyecto que aparecen consultas tras la primera sincronización de prueba.
8. **Rotación:** si hubiera que cambiarla, repetir los pasos 4–6 y borrar la antigua.
9. **Una clave distinta para Local** (pasos 4–5 otra vez, en el mismo proyecto), también restringida solo a YouTube Data API v3. La de producción no se copia nunca a Local ni a las copias de seguridad de Local.

🔲 **Dos cosas que solo se pueden saber en producción** (como ya pasó con el importador del podcast): si Raiola permite editar `wp-config.php` y si bloquea las peticiones salientes desde el propio sitio hacia `googleapis.com`. Es la prueba A8 y la M0.

🔲 Se podría intentar **bajar el cupo diario del proyecto** desde la página «Cuotas» para limitar el daño de una filtración; no he verificado que la consola lo permita para esta API.

---

## 5. Modelo de datos: una canción = una ficha (modelo B)

### 5.1 Cómo encaja

```
YouTube (playlists)                  WordPress                                  Avada
─────────────────────      ───────────────────────────────      ───────────────────────────────
 Tiritaito Music (todas)┐
 Espíritu Santo         ├─► IMPORTADOR ─► ficha «canción»  ──►  Post Cards (Taxonomy + Show Filters)
 Virgen María           │    (servidor,    UNA por vídeo         · filtros = «All» + categorías
 Navidad                │     cada día)    + sus categorías      · orden = Menu Order
 San José              ─┘                  + dirección propia   · tarjeta → Off-Canvas / Lightbox / ficha
                                                                  · ficha compartible → Layout único

 Cada playlist = una categoría, salvo «Tiritaito Music» (todas): su papel lo hace la pestaña «All» de Avada.
 Una canción en 2 playlists tiene 2 categorías, pero UNA sola ficha: 64 fichas, no 88 (las 88 son el recuento de YouTube).
```

### 5.2 Tipo de contenido y taxonomía

| Qué | Decisión / propuesta | Notas |
|---|---|---|
| Tipo de contenido | `cancion` ⚠️ (nombre propuesto) | **Público y con página propia compartible** (decisión C9), sin archivo, `show_in_rest`. Público es obligatorio para que Post Cards lo vea (hallazgo ya anotado en `learnings`) |
| Taxonomía | `categoria_musica` | Unida solo a `cancion`. Cada categoría lleva el **nombre visible** de su fila de ajustes («Virgen María»), no el de YouTube |
| «Todas» | **No crea categoría** (decidido el 28 de octubre, tras las pruebas) | Avada ya añade su pestaña «All» y con una categoría «Todas» salían las dos iguales. La playlist «Tiritaito Music» sigue alimentando el catálogo. El orden de Música lo da la fecha del propio vídeo, no una lista |
| Título de la ficha | **El título de YouTube, tal cual** (decidido el 23 de octubre) | Si cambia en YouTube, cambia en la web en la siguiente sincronización. Si hay que corregirlo (mayúsculas, sufijo, tildes), **se corrige en YouTube** |
| Indexación | `noindex` y fuera del mapa de páginas | Interpretación de «me basta con que sea compartible». Se puede cambiar con un ajuste |

### 5.3 Casillas (prefijo común `vid_`, decidido el 22 de octubre)

**Un solo grupo de casillas, «TT — Vídeo YouTube», asignado a los tipos `cancion`, `viacrucis` y `seminario`** (y a los que se añadan). Así hay **un molde de tarjeta** reutilizable. Lo propio de cada área lleva su prefijo.

| Nombre exacto | Tipo | Quién la rellena | Para qué |
|---|---|---|---|
| `vid_video_id` | Texto | Importador | **Identidad.** ID de 11 caracteres |
| `vid_youtube_url` | **Texto** (no URL, lección del podcast) | Importador | `https://www.youtube.com/watch?v=<id>`; sirve para Lightbox y para el elemento YouTube, que acepta ID o dirección ✅ `[W]` |
| `vid_miniatura_url` | URL | Importador | La miniatura `medium` (320×180) que devuelve la API; si falta, la construida con el ID |
| `vid_fecha_video` | Texto `AAAA-MM-DD` | Importador | Fecha de publicación del vídeo |
| `vid_comprobado` | Texto (fecha y hora) | Importador | Última vez que YouTube confirmó esta ficha (regla de los 30 días) |
| `vid_hash` | Texto (técnica) | Importador | Huella para no reescribir si nada cambió |
| `vid_kids` *(opcional)* | Texto | Importador | `sí` / `no` / `desconocido` según `status.madeForKids` |
| **Solo Seminarios:** `sem_anio`, `sem_mes` | Número | Importador | Año y mes sacados del texto |
| **Solo Vía Crucis:** `vcr_numero` *(opcional)* | Número | Importador | Número de estación, si se quiere mostrar |

- **No hay casilla de orden:** el orden vive en `menu_order`, el orden nativo de WordPress que Avada ya ordena (✅ prueba H2 del podcast; en Post Cards, prueba A7).
- **Datos privados del importador** (no son casillas): `_tt_vid_area` (a qué área pertenece la ficha), `_tt_vid_playlists` (en qué playlists se vio por última vez) y, en cada categoría, `_tt_vid_playlist` (ID de la playlist que la alimenta). Igual que en el podcast, **la identidad la dan estos IDs, no los nombres**: renombrar una categoría no duplica nada.
- **Ganancia y coste de `vid_` (explicado el 22 de octubre):** se gana menos montaje (un grupo y un molde en vez de tres), un importador más simple y áreas nuevas sin crear campos. Se pierde que el nombre ya no dice el área (como sí hacen `nes_`, `nsc_`, `nin_`, `ep_`, `hdd_`) y que un cambio en el grupo afecta a todas las áreas.
- **Regla de oro:** estos nombres se deciden **antes** de crear nada; renombrar una casilla con contenido es caro.

### 5.4 Identidad y duplicados

- La identidad es el **ID del vídeo**. Dos IDs con el mismo título **nunca se fusionan**; el importador solo muestra un aviso «posible duplicado».
- Los dos «Hágase en mi» tienen títulos distintos en YouTube («Hágase en mi / Tiritaito Music» y «Hágase en mi - Hna. Emi | Tiritaito Music»), así que se distinguen sin más.
- Los dos duplicados reales se resuelven en YouTube (decisión C5): se quedan `P-3LMZvoH_Q` («Él me ama») y `tHN2sKkguYA` («Bajo tu manto»); se quitan de las playlists `DETq7RBupns`, `ufGu9PJwH7c` y el `tHN2sKkguYA` repetido dentro de «Todas». El de «Bajo tu manto» ya está en «Virgen María», así que no hay que mover nada más.

### 5.5 Qué cambia en cada área

| Área | Tipo de contenido (propuesta) | Casillas propias | Orden | Cómo entra |
|---|---|---|---|---|
| Música | `cancion` | — | Fecha del vídeo, descendente | Importador |
| Vía Crucis | `viacrucis` | `vcr_numero` (opcional) | Número del título (arábigo o romano), con la posición de respaldo | Importador |
| Seminarios | `seminario` | `sem_anio`, `sem_mes`; incluye **ocultos** | Año y mes del texto, descendentes; desempate por fecha del vídeo | Importador |
| Lenguas | por definir | — | Por definir | Importador, **cuando acaben las pruebas** |
| Inmaculada, Sagrados Corazones, Espíritu Santo | Sus tipos de novena, ya definidos (`novena_inmaculada`…) | `nin_video`, `nsc_video`, `nes_video` (Texto) | El número de día | **A mano**, sin importador (Sección 7) |

---

## 6. El importador

*(Diseño. El código se escribió el 30 de octubre en `apps/v2/tt-video-importador.php`; lo que se implementó exactamente, las interpretaciones y lo que no se pudo verificar están en la Sección 6.8.)*

### 6.1 Flujo

```
 AJUSTES (tabla editable)      LECTOR                NORMALIZADOR               ESCRITOR
 ──────────────────────   ────────────────────   ─────────────────────────   ─────────────────────
 playlist → área →        API de YouTube         título tal cual; fecha;     crea / actualiza fichas
 categoría visible →      (o el JSON actual,     fecha; orden según la       y casillas; asigna
 ¿ocultos? → regla de     como alternativa)      regla del área; ID          categorías; retira lo
 orden                                           repetido fuera              que ya no está
        │                       │                        │                          │
        └───────────────────────┴──── COMPARADOR (hash) ─┴──── FRENOS ─────────────┘
                                 «si no ha cambiado nada, no se escribe nada»
                                                   │
                          REGISTRO + pantalla de Estado + semáforo (Sección 6.6)
```

### 6.2 La tabla de ajustes: una fila por playlist de YouTube

Es **una sola pantalla de WordPress** (como la del importador del podcast) con **una fila por cada playlist de YouTube**. Sustituye a la lista de playlists que hoy vive en el navegador del editor. Añadir una playlist es añadir una fila, sin tocar código.

| Columna | Qué es | Ejemplo |
|---|---|---|
| ID de la playlist de YouTube | El código `PL…` | `PL…` (los reales los pone Carlitos) |
| Área | Música, Vía Crucis, Seminarios, Lenguas… | Música |
| Nombre visible de la categoría | Separado del nombre en YouTube | «Virgen María» |
| ¿Crea categoría? | Sí en todas salvo en la playlist que contiene todo | **No** en «Tiritaito Music» |
| ¿Incluir ocultos (no listados)? | Seminarios = sí; el resto = no | No |
| Regla de orden | Fecha del vídeo / texto / posición / número del título | Fecha del vídeo ↓ |
| Tipo de contenido y taxonomía | Qué se crea en WordPress | `cancion` · `categoria_musica` |
| ¿Activa? | Para pausar una fila sin borrarla | Sí |

**Mejora futura (no en la v1):** ajustes de título por área («textos a quitar» y prefijos de autora). Ver Sección 2.5.

**Filas previstas hoy (IDs en la Sección 2.6):** Música (5: «Tiritaito Music» **sin categoría**, porque su papel lo hace la pestaña «All» de Avada, Espíritu Santo, Virgen María, Navidad, San José) · Vía Crucis (2: «Vía Crucis de la Divina Misericordia 2024» y «Con Jesús hacia la Cruz») · Seminarios (1 playlist de 29 vídeos) · Lenguas (1, tras las pruebas; «Lenguas Ejército» queda fuera por ahora).

### 6.3 Comportamiento (lo decidido; ➕ = refinamiento mío)

| # | Regla | Cómo se comprueba |
|---|---|---|
| 1 | **Lector separado de la escritura.** El lector entrega siempre el mismo formato; el JSON actual sirve como lector alternativo, así que cambiar de camino es un ajuste, no una reescritura | M1 |
| 2 | **Ajustes editables** por una persona (6.2) | M20 |
| 3 | **Una caída nunca borra nada.** Si falla YouTube, la clave, la cuota o una playlist sale vacía, no se retira ninguna ficha. Cada playlist falla por separado | M3, M4 |
| 3b ➕ | **Solo se retira si todas las playlists del área se leyeron completas en esa pasada.** Una playlist con una página sin leer **cuenta como fallida** | M3 |
| 4 | **Retirada reversible con freno:** una canción que ya no está en ninguna playlist pasa a **borrador**. Si fueran a retirarse más del **20 % del área** de golpe, el importador **se detiene y avisa** (botón «Aplicar de todos modos») | M5, M6 |
| 5 | **Modo simulación:** muestra qué se crearía, cambiaría y retiraría **sin escribir nada** | M7 |
| 6 | **Segunda sincronización = cero cambios** (hash) | M2 |
| 7 | **Un ID repetido en la misma lista se ignora** (con aviso) | M8 |
| 8 | **Área nueva = fila nueva**, sin código. Lo que sí hay que crear a mano es su tipo de contenido y su taxonomía (y asignarle el grupo `vid_`); el importador **no los crea**, y su pantalla de Estado dice qué falta | M20 |
| 9 | **Título tal cual** (decidido el 23 de octubre): la ficha usa el título de YouTube sin tocarlo; sin reglas de limpieza en la v1 | M23 |
| 10 | **Mejora futura prevista:** «textos a quitar» y autora aparte (Sección 2.5), sin rehacer nada | — |
| 11 ➕ | **Regla de los 30 días con red de seguridad (R1 + R3):** Sección 6.5 | M5, M19 |
| 12 ➕ | **Categorías gobernadas por YouTube:** si quitas la canción de «Navidad» en YouTube, pierde esa categoría en la web. Las creadas a mano no se tocan | M11 |
| 13 ➕ | **Avisos sin acciones:** «mismo título normalizado con ID distinto» (no fusiona), «vídeo sin número en una lista numerada», «en una categoría pero no en la playlist «Tiritaito Music»», «orden dudoso» | M9, M13 |
| 14 | **Avisos de importador parado y semáforo** (Sección 6.6) | M24 |

### 6.4 El orden: calculado una vez, guardado en `menu_order`

La regla de orden es **una columna de la tabla de ajustes**; el importador la aplica y guarda el resultado en `menu_order`; Avada solo ordena por Menu Order, ascendente. Cambiar de regla no obliga a tocar Avada.

| Regla | Qué hace | Áreas |
|---|---|---|
| **Fecha del vídeo, descendente** | Más reciente primero, con `contentDetails.videoPublishedAt` | **Música** (decidido, C2) |
| **Año y mes del texto, descendentes** | «Mes año» de la primera línea de la descripción o del título; **desempate por la fecha del vídeo**; si no hay texto con mes y año, la fecha del vídeo | **Seminarios** (decidido, C3; ver la corrección de la Sección 3.2, N17) |
| **Número del título** | Analizador nuevo: arábigos con «º» (`7º`), romanos entre paréntesis hasta XX (`(XIII)`), «Día N», **sin confundir «Novena» con un 9**. **Un vídeo sin número va el primero, con aviso** | **Vía Crucis** (decidido, C4); posición de respaldo |
| **Posición** | `snippet.position` de YouTube (base cero) | Respaldo de Vía Crucis |

- **Cada ficha recibe un número que depende solo de sus propios datos** (por ejemplo, un valor que decrece con la fecha), no de su puesto en el ranking. Así, **añadir una canción nueva no obliga a reescribir las otras 63** y la segunda sincronización sigue dando cero cambios.
- Una canción en varias playlists tiene un único orden, porque la fecha es del vídeo, no de la playlist.
- **Música no necesita «lista maestra»** para el orden: lo da la fecha del propio vídeo.
- 🔲 «Más reciente» tiene tres lecturas posibles (N3). Se decidió la fecha de publicación; la prueba A3 compara las tres con YouTube.

### 6.5 Canciones retiradas y la regla de los 30 días (R1 + R3, decidido el 22 de octubre)

**En sencillo.** YouTube nos deja copiar sus datos (título, miniatura, fecha) con una condición: **comprobarlos como máximo cada 30 días, o borrarlos**. Mientras una canción está en una playlist, el importador la comprueba cada día. Una canción retirada ya no está en ninguna, así que nadie la comprueba. *No soy abogado; el riesgo real es bajo, pero cumplirlo es barato.* `[W]` Developer Policies III.E.4.

| Pieza | Qué hace |
|---|---|
| **R1** | La ficha retirada pasa a **borrador**. Mientras el vídeo **exista** en YouTube, el importador lo sigue comprobando por su ID (`videos.list`, 1 unidad por 50 vídeos) |
| **R3, a los 25 días** | Si una ficha lleva **25 días sin poder confirmarse** (el vídeo ya no existe, es privado o el refresco falla sin que nadie lo note), el importador **vacía título, miniatura y fecha y deja solo el ID** |
| Si la canción vuelve | Se rellena todo desde YouTube. Solo se perderían ediciones manuales hechas en esa ficha |

Los números encajan con los avisos: **10 días** amarillo, **20 días** rojo, **25 días** vaciado automático, **30 días** el límite de YouTube.

### 6.6 Avisos de importador parado y semáforo

El estado se mide por **fila**: días desde su **última sincronización correcta**. El estado general es el peor de todas las filas activas.

| Color | Significa |
|---|---|
| 🟢 Verde | Todas las filas se sincronizaron bien hace menos de 10 días |
| 🟡 Amarillo | Alguna fila lleva **10 días o más** sin sincronizarse bien |
| 🔴 Rojo | Alguna fila lleva **20 días o más** |

**Dónde se ve:** (1) la pantalla de Estado del importador, (2) un aviso en el escritorio de WordPress y (3) **un semáforo en la app de Creators** (decidido el 22 de octubre). Si lo que falla es el reloj de WordPress, (1) y (2) solo los ve quien entre en WordPress, y por eso el semáforo de la app es lo que realmente avisa.

**Trabajo que implica el semáforo:** Proyecto 2 (PHP) expone el estado mediante un endpoint con token (🔲 nombre por definir, por ejemplo `/tiritaito/v1/videos/estado`); Proyecto 5 lo pinta en la app. Un email queda fuera de la v1 (🔲 sin comprobar que el correo del sitio funcione).

### 6.7 Qué se reutiliza del importador del podcast y qué no

| Se reutiliza (copiando las funciones con otro prefijo, p. ej. `tt_vid_`; decisión D9) | No se reutiliza |
|---|---|
| Bloqueo anti-doble-ejecución | El lector de feed de Spotify |
| Registro e historial | Las temporadas y la numeración de episodios |
| Hash para no reescribir | La relación canal → temporadas → episodios |
| Escritura ACF por **clave de campo** (no por nombre) | |
| Creación del término de la taxonomía; identidad por ID | |
| WP-Cron diario + botón «Sincronizar ahora» + presupuesto de segundos por pasada | |

**El importador del podcast no se toca.** **Pendiente heredado del podcast que aquí se repite:** cron real en Raiola y que `wp-cron.php` no esté bloqueado (A8).

### 6.8 Implementación del 30 de octubre de 2026 (`apps/v2/tt-video-importador.php`, v1.0)

*Escrito por Proyecto 2 siguiendo las Secciones 5, 6 y 11. **Validado solo en sintaxis y en sus funciones de cálculo** (33 comprobaciones: números de Vía Crucis, órdenes, fecha de Seminarios, hash, semáforo, filas por defecto). **No se ha ejecutado contra WordPress, ACF ni la API real**: todo lo que toca la base de datos o YouTube está sin probar.*

| Pieza | Cómo quedó |
|---|---|
| Instalación | Snippet PHP de Code Snippets, alcance «everywhere», pantalla en **Herramientas → Vídeos YouTube — Importador**. Funciones `tt_vid_*`; no toca el importador del podcast |
| Clave | Constante `TT_YT_API_KEY` en `wp-config.php`. Nunca se muestra ni se escribe en el registro (los mensajes de error la tapan) |
| Ajustes | Opción `tt_video_importador_filas`, editable en la pantalla (una fila por playlist, columnas de la Sección 6.2) |
| Lector | `playlistItems` con `snippet,contentDetails,status`, todas las páginas. **Una página fallida = playlist fallida**; una playlist vacía también cuenta como fallida (regla 3). Cuota agotada o clave mala cortan el resto de filas de esa pasada |
| Fichas | Una por vídeo, identidad = ID. Título tal cual. Fecha de la ficha = fecha del vídeo. Slug = título; si choca, título + ID del vídeo |
| Categorías | Identidad = ID de la playlist guardado en la propia categoría (`_tt_vid_playlist`). Renombrar en los ajustes renombra el mismo término. Si ya existe una categoría con ese nombre, **se adopta**. Las categorías ajenas a las filas no se tocan |
| Orden (`menu_order`, ascendente) | Música: `2147000000 − fecha del vídeo`. Seminarios: año y mes descendentes del texto, desempate por fecha del vídeo. Vía Crucis: `10 + 10·n` con el número del título; sin número = primero (con aviso); si ninguna lista lleva números, la posición. Cada ficha depende solo de sus datos |
| Hash | Título, fecha, miniatura, año/mes, número, orden y área. Las categorías se comparan aparte en cada pasada |
| Segunda pasada | 0 cambios. **Matiz:** `vid_comprobado` de una ficha sin cambios se reescribe como mucho **cada 20 horas** (regla de los 30 días); dentro del mismo día no hay ninguna escritura |
| Simulación | Botón «Simular»: calcula el plan completo y **no escribe nada** (ni fichas, ni categorías, ni registro) |
| Retiradas (R1) | Solo si **todas** las filas del área se leyeron completas y no queda trabajo pendiente. Pasa a **borrador** y se anota `_tt_vid_retirada`. Un borrador puesto a mano **no** se toca ni se republica |
| Freno | Si se retirarían **más del 20 % de las fichas publicadas del área**, no se retira nada, se avisa (escritorio y Estado) y aparece «Aplicar de todos modos» |
| R1 sostenido y R3 | Las retiradas se comprueban por ID con `videos.list` (1 unidad por 50). Si el vídeo existe, se refrescan sus datos y `vid_comprobado`. **A los 25 días sin poder confirmarse** se vacían título, miniatura, fecha, año/mes y estado «kids»; queda el ID y el slug. Si vuelve, se rellena todo y se republica |
| «Made for Kids» | `vid_kids` (opcional): se lee una vez por ficha (`sí` / `no` / `desconocido`) |
| Semáforo | Por fila: días desde su última sincronización **correcta**. Amarillo ≥ 10, rojo ≥ 20; el general es el peor. Se ve en Estado y en el escritorio de WordPress |
| Endpoint | `GET /tiritaito/v1/videos/estado` con cabecera `X-TT-Token`; devuelve color, días, filas, frenos y última ejecución. Sin token configurado en el servidor responde 503 (cerrado) |
| Fichas compartibles | `noindex` y fuera de `/wp-sitemap.xml` para los tipos de las filas (C9); etiquetas `og:*` y `twitter:*` con título, descripción e imagen (la miniatura de la ficha) |
| Cron | Sincronización diaria a las **05:15** (distinta de la del podcast, 04:30) + botón; presupuesto de 25 s por pasada con continuación al minuto |

**Filas iniciales:** Música con «Tiritaito Music» (sin categoría), «Virgen María» y «San José» activas; **«Espíritu Santo» y «Navidad» creadas inactivas y sin ID** (faltan; los dos IDs sin identificar de la Sección 2.6 son sus candidatos, sin asignar). **Vía Crucis (2 filas) y Seminarios (1) vienen inactivas** hasta que existan sus tipos de contenido y se marque «Activa».

**Interpretaciones mías y cosas que no pude verificar** (revisar antes de dar nada por bueno):

| # | Qué | Estado |
|---|---|---|
| I1 | Los nombres `viacrucis`, `seminario` y `categoria_viacrucis` son **propuestas**: los documentos no los fijan. Se cambian en la tabla de ajustes | ⚠️ |
| I2 | La lectura de «mes y año» de Seminarios se escribió **desde la descripción de este documento**, no desde `extraerFecha()` del generador (no estaba en la sesión). El caso M14 lo contrasta con los 29 seminarios reales | 🔲 |
| I3 | El endpoint usa la constante `TT_WRITE_TOKEN` si existe; si el snippet central guarda el token de otra forma, responderá 503 hasta que se ajuste. **No he visto ese snippet** | 🔲 |
| I4 | Si la app (otro origen) no recibe las cabeceras CORS en esta ruta, habrá que añadirlas como en las demás rutas de `tiritaito/v1` | 🔲 |
| I5 | El desactivado de las etiquetas de Jetpack (`jetpack_enable_open_graph`, `jetpack_disable_twitter_cards`) está escrito de memoria y sin probar. Si la vista previa sigue saliendo genérica, mirar el código fuente de una ficha y buscar qué otro plugin o Avada emite `og:image` | 🔲 |
| I6 | `og:image` usa la miniatura de 320×180 de la ficha; puede quedar pequeña en WhatsApp. Hay un filtro (`tt_vid_og_imagen`) para cambiarla | 🔲 |
| I7 | `vid_comprobado` guarda fecha y hora en **UTC** (`AAAA-MM-DD HH:MM:SS`). Para M19, editarlo a mano con ese formato | ✅ decisión |
| I8 | Una playlist **privada** no se puede leer con clave: saldrá como error de esa fila, sin retirar nada. Qué devuelve la API con un vídeo **eliminado** dentro de una playlist (pregunta abierta 8) **no está comprobado**; el código descarta los títulos «Deleted video» / «Private video» | 🔲 |
| I9 | `sem_tipo` (P6) **no está implementado**: sigue pendiente decidir cómo se rellena | 🔲 |
| I10 | El freno cuenta sobre las fichas **publicadas del área**; en un área muy pequeña (p. ej. 2 fichas) retirar una ya supera el 20 % y frena | ⚠️ |

**Cómo se prueban M4, M16, M19 y M24 sin tocar YouTube:** M4 (botón «Simular cuota agotada»), M24 (botones «Fingir última sincronización correcta hace 11 / 21 días»), M16 (`'presupuesto' => 0` en `tt_vid_cfg()`, devolviéndolo a 25), M19 (poner `vid_comprobado` de una ficha retirada en hace 26 días y cambiar su `vid_video_id` por 11 caracteres inventados para que no se pueda confirmar).

---

## 7. Qué piezas dependen de esto y cómo encajan (pregunta F)

*Fuente de las fichas: `ORACIONES_ESTRUCTURA.md` (leído completo). Lo que dice de Seminarios sale del encargo de esta fase; no he releído su documento.*

### 7.0 El criterio: ¿importador o a mano?

| | **Abierto** → importador | **Fijo** → a mano |
|---|---|---|
| Cómo es la lista | **Crece o cambia con el tiempo** y alguien la mantiene en YouTube | **Cerrada**: 9 días, no se espera que cambie |
| Quién trabaja cuando algo cambia | Nadie: YouTube manda y el importador sigue | Una persona edita un campo |
| Coste inicial | Una fila de ajustes (más su tipo de contenido) | 9 pegadas, una sola vez |
| Áreas | **Música, Seminarios, Vía Crucis** (cada año una nueva), **Lenguas** | **Inmaculada, Sagrados Corazones, Espíritu Santo** |

**Cuándo cambiaría el criterio:** si los vídeos de una novena se **regraban cada año**, el importador ahorraría trabajo (pregunta P4). **Confirmado el 23 de octubre: se ponen a mano.** Como un vídeo vivo dentro de un carrusel no es fiable (A5), en las novenas el vídeo de cada día irá con un **botón «Ver vídeo» que abre el Lightbox** (por probar con el vídeo de la casilla, Sección 13.4).

```
                         ┌──────────────────────────────────────────────┐
  IMPORTADOR (nuevo) ───►│ Música · Vía Crucis · Seminarios · Lenguas*   │  una fila de ajustes por playlist
                         └──────────────────────────────────────────────┘      * tras las pruebas
                         ┌──────────────────────────────────────────────┐
  A MANO (elemento       │ Inmaculada · Sagrados Corazones · Espíritu    │  el ID o la dirección va en una casilla
  YouTube de Avada)  ───►│ Santo · Pentecostés · Posesión mariana ·      │  del propio día / página; sin importador
                         │ Adoraciones (vídeo aparte)                    │
                         └──────────────────────────────────────────────┘
```

| Pieza | Estado | Cómo encaja |
|---|---|---|
| **Lenguas** | ✅ **Decidido (22 oct):** entra como área del importador cuando acaben las pruebas | Una fila de ajustes + su tipo de contenido. 🔲 Faltan el ID de la playlist, el número de vídeos y la regla de orden. *No confundir con `lenguas_url` del Devocional diario (`00_CORE` 3.1), que es otra cosa* |
| **Vía Crucis** | ✅ Área del importador, 2 filas hoy; cada año nuevo = una fila más | Sus «temporadas» son **playlists**. Orden por el número del título (C4). ⚠️ El vídeo introductorio de «Con Jesús hacia la Cruz» se titula «…Novena a la Divina Misericordia»: confirmar si está relacionado con esa novena |
| **Inmaculada** | ✅ **Decidido (22 oct): vídeo fijo, a mano.** Resuelve la contradicción de ORACIONES 3.7 #1 (queda como **novena de días con carrusel**, no como «lista de YouTube») | 9 pegadas en `nin_video` (Texto). IDs en 7.1 |
| **Sagrados Corazones** | ✅ A mano. **La canción de cierre es la misma los 9 días** (declarado, resuelve ORACIONES 3.8) | Playlist `PLX21N4KPkRTSY6vRwaGMZORrCifg2g_Gb` (sin abrir desde aquí). `nsc_video` (Texto) lleva el vídeo del día; el apartado 9, «Enséñennos a amar» (`Rwh6N8Q7D1s`), lleva un ID fijo en el molde |
| **Espíritu Santo** | ✅ A mano, con la misma lógica. Sus «Vídeos del día» **están en una playlist de YouTube** (declarado); sigue siendo manual porque es fijo | `nes_video` (Texto). 🔲 Falta saber **cuál es su canción fija** (P3) |
| Pentecostés, Posesión mariana, Adoraciones | Un vídeo cada una; **Pentecostés tiene una playlist** (`PLX21N4KPkRTSkCX7g6WnlphX2mWSvFCT4`, sin abrir desde aquí; ¿es la de los vídeos del día de Espíritu Santo? P3) | A mano. Sin dependencia |
| Seminarios | Boceto J v5; el panel muestra los 2 más recientes; incluye ocultos | Área del importador. Dependen de A5 (vídeo en Modal) |

### 7.1 Inmaculada: los 9 vídeos, listos para pegar ✅ `[R]`

*Salen de `NovenaYT.json`; los títulos «Día 1» a «Día 9» coinciden con el orden del archivo.*

| Día | ID del vídeo |
|---|---|
| 1 | `cpYSsadmJjs` |
| 2 | `_03D1rmKLkc` |
| 3 | `6zWW4_50nbo` |
| 4 | `SsHJUD_1y9k` |
| 5 | `Hi5AGpwvT-Y` |
| 6 | `Cf06bNw1K74` |
| 7 | `WzrfZT7BV4g` |
| 8 | `ACQjtFzhmuQ` |
| 9 | `VgNZYuKyCiM` |

**Para Sagrados Corazones y Espíritu Santo no tengo los IDs** (P3).

**Alternativas descartadas** (por si se reabre): *(b)* el importador rellena `nin_video` buscando por `nin_dia` (añade un segundo modo al importador para 9 valores fijos); *(c)* tratar la Inmaculada como un área más (contradice la ficha ya construida y rehace la página). Pasar de *a mano* a *(b)* es aditivo, no rehace nada.

---

## 8. Qué puede hacer Avada de forma nativa — contrastado hoy

*Documentación oficial de Avada (ya redirigida a `classic.avada.com`: la línea Avada Classic es la que usamos). Lo que no pude comprobar queda en 🔲.*

| Necesidad | Elemento y opción | Estado |
|---|---|---|
| Rejilla de canciones desde un tipo propio | **Post Cards**: Content Source, Post Type, Taxonomy, «Posts By» | ✅ opciones documentadas `[W]` |
| Pestañas de categoría | Post Cards → **Show Filters**; «Posts By» ofrece la taxonomía propia | ✅ **Probado (A1):** funciona en rejilla. Avada añade su pestaña «All» («Yes without All» la quita, pero entonces abre en la primera categoría); **no hay ajuste de orden (salen alfabéticas) ni de pestaña inicial**; con Layout Carousel **no hay filtros** |
| Ver todas las canciones con filtros | «Number of Posts»: `-1` | ✅ **Probado (A1):** con `-1` y rejilla funciona; con «Load More» el filtro solo ve lo cargado |
| Carrusel de canciones | Layouts Carousel, Marquee, Coverflow, Stacking Cards, Slider; Autoplay, Loop, flechas y puntos | ✅ `[W]` |
| Navegación propia con botones | CSS ID del elemento + `#mi-id-next` / `#mi-id-prev` como enlace de un Botón | ✅ `[W]` |
| Ordenar por orden manual | Order By → Menu Order | ✅ **Probado (A7):** numérico (2 antes que 10) |
| Mostrar solo publicadas | «Post Status» vacío = publicadas | ✅ `[W]` (N12) |
| Filtrar por valor de una casilla | «Posts By» = Custom Field | ✅ **Probado (A1-bis):** funciona (8 de 10 fichas). **El catálogo y `learnings` estaban equivocados** (Sección 14) |
| Vista rápida sin cambiar de página | Post Cards → «Link Off Canvas» | ⚠️ **Probado (A6):** abre el panel con el vídeo de esa canción, pero **no detiene el vídeo al cerrar**; necesita un botón dentro de la tarjeta (un enlace en la Columna lo bloquea) |
| Vídeo que no lastre la carga | **YouTube Element**: *Video ID or Url*, *Additional API Parameter* (p. ej. `&rel=0`), **Video Facade**, **Load Via YouTube No Cookie**, tamaño de miniatura | ✅ `[W]` |
| Vídeo en ventana ampliada | Lightbox (Link Target de Columna o Botón) | ✅ **Probado (A6):** en **Columna** abre el vídeo correcto, suena y se para al cerrar; en Botón existe la opción (sin probar con vídeo dinámico). **Image y Post Card Image no ofrecen Lightbox** |
| Imagen de tarjeta desde una casilla URL | Elemento **Image** con contenido dinámico «ACF Image» | ✅ **Probado (A4):** se ve la miniatura 16:9; 10 miniaturas ≈130 kB. Post Card Image no ofrece leer una URL |
| Vídeo dentro de una tarjeta de carrusel o de un Modal | YouTube Element con Facade | ⚠️ **Probado (A5):** en **carrusel** pueden sonar varios a la vez y sigue sonando al deslizar (usar miniatura + Lightbox). En **Modal** funciona y se para al cerrar (con ID fijo; con ID dinámico, sin probar) |
| Consentimiento de cookies de Avada y YouTube | Módulo de privacidad de Avada | ✅ **Mirado (A2):** Privacy Consent Tools está **Off** hoy, así que Avada no bloquea YouTube (el catálogo decía On) |

**Lo que Avada NO tiene, de ninguna manera** (sin cambios): leer un JSON externo y pintarlo (ya no hace falta: los datos pasan a ser entradas); **reproducción continua con cola, mini-reproductor persistente, búsqueda instantánea sobre una rejilla, enlace profundo `?mp_play=`** (requiere JavaScript). Decisión C1: **A y luego D**; el enlace profundo se sustituye por la dirección de cada ficha.

---

## 9. Opciones, riesgos y qué se rehace si se cambia

### 9.1 Dónde viven los datos (decidido; se deja constancia de lo descartado)

| Opción | Ventajas | Riesgos | Estado |
|---|---|---|---|
| ① JSON en la Biblioteca de Medios (hoy) | Un clic en la app | Avada no lo ve; el circuito estuvo roto; cada cambio visual vuelve a Carlitos (hallazgo #12) | Descartada |
| ② Fichas escritas a mano | Cero código | Nadie mantiene 62+ fichas a mano | Descartada |
| ③ JSON → fichas (importador desde el JSON) | Conserva el botón de la app | Doble paso; depende de la app y de la clave en el navegador del editor | Descartada (queda como lector alternativo) |
| **④ API de YouTube → fichas (camino 2)** | Sin pasos a mano; ajustes en WordPress; la clave no sale del servidor | PHP nuevo; depende de la salida a internet de Raiola (A8); regla de los 30 días | ✅ **Elegida (13 oct)** |

### 9.2 Cómo se presenta y se reproduce (**decidido el 22 de octubre: A y luego D**)

| Opción | Qué es | Ventajas | Riesgos |
|---|---|---|---|
| **A — Escaparate nativo** ✅ *primer paso* | Post Cards con filtros; la tarjeta abre el vídeo (Off-Canvas, Lightbox o la propia ficha, según A5 y A6) | Cero código de interfaz; Carlota diseña y Álvaro construye; hereda Global Options; sin iframes hasta pulsar | Sin cola ni mini-reproductor ni búsqueda en vivo. Depende de A1, A2, A4, A6 y A9 |
| B — A + reproductor de lista de YouTube | Un reproductor de playlist de YouTube (`videoseries`) con cola de fábrica | Reproducción continua real sin mantener nada | Marca y «relacionados» de YouTube; la cola no se estiliza; 🔲 el elemento YouTube de Avada pide un Video ID, y un iframe a mano **pierde el Facade** |
| C — Módulo propio v2 | Reproductor completo reescrito para leer de WordPress | Paridad total | Toda la página fuera del alcance de Avada; hay que volver a sanear los 12 hallazgos |
| **D — A + un Code Block acotado** ✅ *segundo paso* | El escaparate nativo y, en una sola sección, un reproductor con cola | Conserva lo que Avada no hace sin entregarle toda la página | Un código que mantener; `z-index` con el header sticky y el Off-Canvas (hallazgo #11) |

**Enlace profundo `?mp_play=`:** queda **sustituido por la dirección de cada ficha** (decisión C9). Ya no hace falta JavaScript para compartir una canción.

### 9.3 Qué se rehace si se cambia de opinión *(formato de la tabla 13.1 del podcast)*

«Rehace» = obliga a rehacer lo ya construido.

| Decisión | Cambio previsto | Cómo se haría | Qué supone | ¿Rehace? |
|---|---|---|---|---|
| Camino 2 (el servidor consulta la API) | Volver al JSON de la app | Cambiar el lector del importador (regla 1) | Un ajuste; la clave del navegador del editor vuelve a ser necesaria | No |
| Clave sin restricción de aplicación | Con restricción de IP | Si se conoce una IP de salida estable, añadirla en la consola | Cinco minutos; si la IP cambia, la importación se rompe | No |
| Modelo B (taxonomía) | Modelo A (relaciones) | Cambiar el escritor, el molde y los filtros | Alto: el filtro nativo desaparece | **Sí** |
| Prefijo común `vid_` | Prefijos por área | Crear 3 grupos y 3 moldes | **Hoy**: elegir antes de crear. **Después**: renombrar casillas con contenido | **Sí si es después** |
| «Todas» sin categoría (usa el «All» de Avada) | Crear la categoría «Todas» | Quitar la excepción de la fila | Pequeño; **se duplica con el «All» de Avada** | No |
| Orden en `menu_order` | Orden por casilla propia | Añadir una casilla y cambiar Order By | Pequeño | No |
| A (escaparate nativo) | Añadir D (cola) | Un Code Block que lea las fichas por la API REST de WordPress (el tipo es `show_in_rest`) | Código nuevo de Carlitos o Proyecto 11 | **No** (aditivo) |
| C o D | Volver a A | Quitar el Code Block | Se pierden las funciones de cola | No (sustractivo) |
| Retiradas R1 + R3 | Papelera a los 25 días | Cambiar la acción del importador | Pequeño | No |
| Título tal cual | Título limpio y autora aparte | Añadir la regla de «textos a quitar» y una casilla de autora | Reescribe los títulos en la siguiente sincronización | No (aditivo) |
| Fichas compartibles | Sin página propia | Quitar el tipo `publicly_queryable`; **Post Cards dejaría de verlas** | Alto: es lo que Avada necesita | **Sí** |
| `noindex` en las fichas | Indexarlas | Quitar el `noindex` y el filtro del mapa | Pequeño | No |
| Vídeos de las novenas a mano | El importador los rellena | Añadir un segundo modo | Código nuevo | No (aditivo) |
| Sin letras (13 oct) | Añadir letras o historia | Una casilla de texto en la ficha | Pequeño | No (aditivo) |

**Lo que sí sería caro de cambiar:** pasar del modelo B al A, renombrar casillas que ya tengan contenido, y quitar la página propia de las fichas.

---

## 10. Recomendación (aceptada el 22 de octubre)

**Construir en cinco tiempos, con una capa de datos única y la presentación nativa primero. Carlota diseña al final.**

1. **Pruebas de Avada y YouTube (A1–A9)** antes de escribir una línea del importador. Son horas, no días.
2. **Importador y fichas de Música**, con plantillas básicas y sin diseño, solo para probar. Después **Vía Crucis y Seminarios**, y **Lenguas** cuando las pruebas pasen. Se pasan los 25 casos del bloque B.
3. **Avisos y semáforo en la app** en cuanto el importador exponga su estado (puede ir en paralelo).
4. **Cuando todo funcione, Carlota hace los diseños visuales**, con su libertad creativa habitual y dentro de lo que las pruebas hayan confirmado, y Álvaro los construye: molde de tarjeta, plantilla de la ficha y secciones.
5. **Reproductor con cola (D)** como añadido posterior, con la misma capa de datos.

**Por qué:**

- **Es lo único que sirve para todas las áreas.** Elegir entre A y D no cambia la capa de datos.
- **Convierte la página en territorio de Avada** (hallazgo #12).
- **No pierde nada recuperable:** al ser fichas con `show_in_rest`, un reproductor con cola puede leerlas más adelante sin rehacer fichas, importador ni moldes.
- **Corrige a la vez lo importante del módulo viejo:** desaparecen las 88 peticiones por carga, el JSON desconectado y las variables fuera de token; el filtrado de privados pasa a la sincronización diaria.
- **El riesgo real está identificado y es barato de medir:** que Show Filters no admita una taxonomía propia (A1), con plan alternativo.

**Quién construye qué:**

| Pieza | Quién |
|---|---|
| Todas las pruebas, A1–A9 (también A3 y A8) | Álvaro (Proyecto 3/7) |
| Proyecto de Google Cloud y claves (con la cuenta de Tiritaito) | Álvaro |
| Diseños visuales (después de las pruebas y con todo funcionando) | Carlota (Proyecto 4/6) |
| Importador (PHP de servidor) y endpoint del estado | Proyecto 2 |
| Molde de tarjeta, plantilla de la ficha y secciones, a partir del diseño de Carlota | Álvaro |
| Semáforo en la app | Proyecto 5 |
| Reproductor con cola (D) | Carlitos o Proyecto 11, más adelante |

**Lo que cambiaría esta recomendación:** que A1 falle y no haya alternativa nativa (entonces, modelo A de relaciones, que Avada ya lee en el podcast).

---

## 11. Decisiones: las tomadas y las que quedan

### 11.1 Decisiones cerradas el 22 de octubre

| # | Tema | Resultado |
|---|---|---|
| C1 | Cola, mini-reproductor, búsqueda y enlace profundo | **A y luego D.** El enlace `?mp_play=` se sustituye por la dirección de cada ficha |
| C2 | «Más reciente» en Música | **Fecha de publicación del vídeo** |
| C3 | Fecha de Seminarios | **La de la descripción** (mes y año), con año y mes **descendentes**, desempate por la fecha del vídeo y respaldo de la fecha del vídeo. Corrige el mes ascendente del generador actual (**confirmado el 23 de octubre**) |
| C4 | Orden de Vía Crucis | **Número del título** (arábigo o romano); armonizar títulos en YouTube es opcional, el analizador entiende ambos; sin número = primero con aviso. «(IX) La Última Cena» **se queda en su sitio** (confirmado el 23 de octubre) |
| C5 | Duplicados | Se quedan **`P-3LMZvoH_Q`** («Él me ama», 4:28) y **`tHN2sKkguYA`** («Bajo tu manto», 4:17). Se quitan de las playlists `DETq7RBupns`, `ufGu9PJwH7c` y el `tHN2sKkguYA` repetido en «Todas» |
| C6 | Canciones retiradas | **R1 + R3 a los 25 días** (Sección 6.5); freno del 20 % |
| C7 | Títulos | **Se importan tal como están en YouTube** (23 oct). Si hay que cambiar alguno (sufijo, mayúsculas, tildes), se cambia en YouTube. Título limpio y autora aparte: **mejora futura** (Sección 2.5) |
| C8 | Casillas | **Prefijo común `vid_`** más prefijos propios (`sem_`, `vcr_`); sin casilla de orden; tipo `cancion` |
| C9 | Fichas públicas | **Cada canción tiene dirección propia y es compartible.** Indexar no hace falta: `noindex` y fuera del mapa de páginas (interpretación mía, reversible) |
| C10 | Inmaculada, Sagrados Corazones, Espíritu Santo | **Vídeos fijos, a mano** (confirmado el 23 de octubre). Criterio fijo/abierto en 7.0 |
| C11 | Vídeos del día de Espíritu Santo | Están en una playlist; igualmente a mano |
| C12 | Lenguas | Entra como área **cuando acaben las pruebas** |
| C13 | Clave | En la **cuenta de Tiritaito**, **sin restricción de aplicación** (se mantiene), **clave distinta para Local** |
| C14 | «Todas» | **No es una categoría** (28 oct, tras las pruebas): la pestaña «All» de Avada hace ese papel; antes se había decidido lo contrario |
| C15 | Aviso de importador parado | **Amarillo a 10 días, rojo a 20**, con **semáforo en la app de Creators** |
| C16 | Orden de trabajo con Carlota | **Primero todas las pruebas; solo después, cuando todo funcione, Carlota hace los diseños visuales**, con su libertad creativa habitual (23 oct) |
| K1 | Dirección visual | **Dirección 1 con la 3 dentro** (= A y luego D): Carlota dibuja la 1 con un hueco para el reproductor |
| K3 | Canción destacada | **Automática: la más reciente.** Sin interruptor. Si luego se quiere elegir, una casilla en una página de opciones de WordPress, no en la app |
| K4 | Tarjeta | **Miniatura de YouTube** (16:9) |
| K7 | Canciones sin categoría | Resuelto: se ven con la pestaña «All» de Avada |
| K8 | Radio de esquinas | **15 px** |

### 11.2 Pendientes de Carlitos

| # | Pendiente | Bloquea | Mi propuesta |
|---|---|---|---|
| P1 | ~~Títulos en MAYÚSCULAS~~ | — | **Cerrado el 23 de octubre:** se importan tal cual y se cambiarán en YouTube si hace falta |
| P2 | ~~Mes ascendente de Seminarios~~ | — | **Cerrado el 23 de octubre:** mes descendente |
| P3 | **Espíritu Santo:** me diste las playlists de Pentecostés y de Sagrados Corazones. ¿La de «Pentecostés» es la de los vídeos del día de Espíritu Santo? Y **¿cuál es su canción fija?** | Molde de esa novena | — |
| P4 | ~~Novenas regrabadas cada año~~ | — | **Cerrado el 23 de octubre:** se ponen a mano |
| P5 | **Cuatro playlists que no pude abrir** (las dos sin identificar, Pentecostés y Sagrados Corazones): ábrelas en una ventana de incógnito y dime si son públicas, no listadas o privadas. **Una privada no la lee la clave** | Primera carga | — |
| P6 | ~~¿Cómo se sabe el tipo de cada seminario?~~ Se resuelve con una casilla `sem_tipo` y Post Cards filtrando por valor (probado en A1-bis). **Falta decidir cómo se rellena** (regla de texto o a mano) | Fichas de Seminarios | Hablarlo al llegar a Seminarios; no frena Música |

### 11.3 Carlota

**Cerradas:** K1 (Dirección 1 con hueco), K3 (destacada automática), K4 (miniatura), K7, K8 (15 px). **Cuándo decide Carlota:** solo cuando todas las pruebas hayan pasado y todo funcione (C16); hasta entonces no hace falta ningún boceto.

**Abiertas:**

| # | Decisión | Dato que necesita |
|---|---|---|
| K2 | Qué manda en el hero (acuarela, foto del grupo, logo, vídeo corto mudo) | — |
| K5 | **Qué pasa al pulsar una tarjeta (ABIERTA, 28 oct):** se prueba el **Lightbox en Columna**, ya probado (abre, suena, se para al cerrar, sin código). El Off-Canvas no para el vídeo al cerrar y necesitaría un arreglo de código; «ir a la ficha» también es posible | Resultados de A5 y A6 (Sección 13.3). Falta probarlo en un caso real y que Carlota lo diseñe. **Nada por encima del reproductor ni de sus controles** y reproductor mínimo de 200×200 px (N13) |
| K6 | **Títulos largos:** la tarjeta debe tolerar títulos de hasta 98 caracteres en dos líneas (media de 45), salvo que se editen en YouTube | Sección 2.1 |
| K10 | **Plantilla de la ficha de canción** (la página compartible): vídeo, título, autora, categorías, botón de compartir o copiar enlace, volver | N21 y A9 |
| K11 | **El hueco reservado para el reproductor con cola** (opción D) y cómo convive con el header sticky y los paneles | Hallazgo #11 |

---

## 12. Reglas de YouTube, rendimiento, accesibilidad y SEO (pregunta G)

| Tema | Qué hay que tener presente | Estado |
|---|---|---|
| **Error 153 / `Referer`** | La web debe enviar `Referer` al reproductor: no usar un `Referrer-Policy` que lo suprima (YouTube recomienda `strict-origin-when-cross-origin`). Comprobar la cabecera de producción y los plugins de seguridad | ✅ norma `[W]` · ✅ A2: sin `Referrer-Policy` en Local ni en producción; no apareció el 153 |
| **30 días** | Refresco diario; R1 + R3 a los 25 días; avisos a 10 y 20 días (6.5, 6.6) | ✅ norma · ☑ escrito el 30 oct, 🔲 sin probar (M5, M19, M24) |
| **Made for Kids** | Activar «Load Via YouTube No Cookie» en todos los vídeos y leer `status.madeForKids` una vez | ✅ norma · 🔲 |
| **Clave** | Proyecto de Google Cloud solo para el importador; **clave de Local aparte**; nunca en el repositorio ni en documentos | ✅ norma (interpretación ☑) |
| **Política de privacidad del sitio** | Las políticas de YouTube piden enlazar sus condiciones y la política de privacidad de Google en los «clientes de la API». **No he podido determinar si un sitio que solo incrusta vídeos queda dentro**; conviene revisar el texto de privacidad | 🔲 |
| **Rendimiento** | Cada tarjeta carga una miniatura de `i.ytimg.com`, no un iframe. Con Off-Canvas, Lightbox o Facade, ningún reproductor se carga hasta pulsar. El importador **nunca se ejecuta al cargar la página**. El Facade solo lo aprovecha el elemento YouTube de Avada; **un iframe pegado en un Code Block no** | ✅ A4: 10 miniaturas ≈130 kB · 🔲 carga diferida sin demostrar (medir con 70) |
| **64 tarjetas en una página** | `Number of Posts = -1` y miniaturas perezosas; Offscreen Rendering ya está activo ☑. Medir el peso real | 🔲 |
| **Accesibilidad** | ¿Se llega al panel (Off-Canvas o Lightbox) con teclado y se cierra con Esc? ¿La miniatura del Facade tiene etiqueta para lectores de pantalla y texto alternativo? En carruseles el punto activo solo se distingue por color (límite conocido de ORACIONES) | ✅ A6: Lightbox y Off-Canvas se llegan con teclado y cierran con Esc · 🔲 etiquetas de la miniatura |
| **Compartir** | Una ficha compartible solo sirve si **la vista previa** (WhatsApp, Telegram) muestra título e imagen. WordPress suele tomar la imagen de la «imagen destacada», y nuestra miniatura vive en una casilla ☑ (no comprobado; Jetpack está activo según `00_CORE`). Si falla, un pequeño snippet puede emitir la miniatura como imagen de vista previa | ⚠️ A9: falta `og:image` con la miniatura y `og:description` → snippet; la vista previa real queda 🔲 hasta producción |
| **SEO** | Las fichas públicas entran por defecto en `/wp-sitemap.xml` y son páginas con casi solo un vídeo (contenido delgado). Decisión C9: **`noindex` y fuera del mapa**. El repositorio no lista ningún plugin de SEO, así que se haría con un snippet. Para los seminarios ocultos es además lo que evita que «oculto» deje de serlo | ✅ `[W]` · 🔲 (M22) |

---

## 13. Plan de pruebas en Local (pregunta H)

**Antes:** copia de seguridad de la base de datos y **una clave de API distinta para Local** (también restringida solo a YouTube Data API v3). Si un resultado no coincide con lo esperado, no seguir: captura de pantalla y avisar. **Ninguna prueba se ha ejecutado** (el importador no existe todavía): todo está en 🔲.

### 13.1 Bloque A — Avada y YouTube (se hace **antes** de escribir el importador)

*Las cinco pruebas pendientes de la Fase 2 son A1–A5; A6–A9 son añadidas.*

| # | Prueba | Cómo | Resultado esperado | Estado |
|---|---|---|---|---|
| A1 | **Show Filters con una taxonomía propia** | Crear `cancion` y `categoria_musica` con 4 categorías (una de ellas «Todas», asignada a todas las fichas) y 8 fichas de prueba. Post Cards: Post Type = `cancion`, Taxonomy = `categoria_musica`, Show Filters = Sí, Number of Posts = `-1`, Layout Grid. Repetir con `Number of Posts` = 3 y con Layout Carousel | Pestañas con las categorías; al pulsar, filtra. **Anotar si Avada añade además su propia pestaña «Todos»** (duplicaría la nuestra). Con 3 por página, comprobar si el filtro ve solo la página cargada. **Si no lista la taxonomía:** probar «Posts By» con un Post Cards por categoría. **Si tampoco:** modelo A (relaciones) | 🔲 |
| A2 | **`referrerpolicy` y error 153** | YouTube Element con Video Facade On/Off y con No Cookie On/Off; y un iframe a mano con y sin `referrerpolicy="strict-origin-when-cross-origin"`. Mirar en Local y en producción la cabecera `Referrer-Policy` de la página. Con el módulo de privacidad de Avada activo, ¿YouTube pide consentimiento antes de mostrar la miniatura? | El vídeo suena sin error 153 en todos los casos; la cabecera de producción no suprime el `Referer` | 🔲 |
| A3 | **`position` y `videoPublishedAt` frente a YouTube** | Con el Explorador de APIs de Google (o la clave de prueba) pedir `playlistItems` con `snippet,contentDetails,status` de «Todas», de «Con Jesús hacia la Cruz» y de un seminario. Comparar con lo que se ve en la propia playlist de YouTube. Playlists reales e IDs en la Sección 2.6. **Averiguar también por qué «Tiritaito Music» tiene 70 vídeos y el JSON 65** (¿nuevos o no listados?) y qué devuelve la API en las cuatro playlists que no se abrieron | `position` = orden de la playlist; `videoPublishedAt` = fecha del vídeo; se ve si los tres órdenes coinciden y cuál es el real en Vía Crucis (resuelve N4) | 🔲 |
| A4 | **Imagen de la tarjeta desde una casilla URL** (la H3 del podcast, nunca hecha) | Molde de tarjeta con Post Card Image o Imagen con contenido dinámico apuntando a `vid_miniatura_url` | Se ve la miniatura. **Si no:** descargar como imagen destacada, **con la cautela de N8**; medir el peso | 🔲 |
| A5 | **Vídeo dentro de una tarjeta de carrusel, de un Modal y de un Off-Canvas** | YouTube Element con Facade dentro de cada uno | Carrusel: ¿sigue sonando al pasar de tarjeta? ¿el arrastre choca con el play? Modal y Off-Canvas: ¿**se detiene al cerrar**? | 🔲 |
| A6 | **Qué pasa al pulsar la tarjeta: tres variantes** | (1) **Off-Canvas** con «Link Off Canvas» de Post Cards, y un YouTube Element dentro alimentado con la casilla de la tarjeta pulsada. (2) **Lightbox**: `Link Target` de Columna, Botón y Post Card Image con `vid_youtube_url`. (3) **Enlace a la ficha** (`Permalink`) | (1) El panel recibe el vídeo de **esa** canción y trae un botón de compartir. (2) Abre el vídeo en Lightbox y cierra con Esc. (3) Va a la página de la ficha. En las tres: se llega con teclado | 🔲 |
| A7 | **Orden por Menu Order en Post Cards** | Fichas con `menu_order` 5, 1, 10 | Salen 1, 5, 10 (ascendente) | 🔲 |
| A8 | **Servidor** | (1) ¿se puede editar `wp-config.php`? (2) ¿sale el servidor a `googleapis.com` (una llamada mínima)? (3) ¿se ejecuta a su hora un evento de prueba (plugin de inspección del cron)? | Los tres, sí. Si no, hay que hablar con Raiola antes de seguir | 🔲 |
| A9 | **Ficha compartible** | Layout único para `cancion` con Dynamic Content (título, YouTube Element con `vid_youtube_url`). Compartir su enlace en WhatsApp y Telegram | La página se ve completa; la vista previa muestra **título e imagen**. Si no hay imagen: valorar un snippet que emita la miniatura | 🔲 |

### 13.2 Bloque B — El importador (con Música como primer caso)

*30 de octubre: el importador está escrito (Sección 6.8) pero **ningún caso se ha ejecutado**. La columna «Estado» sigue en 🔲 hasta que Álvaro los pase en Local.*

| # | Prueba | Cómo | Resultado esperado | Estado |
|---|---|---|---|---|
| M0 | **Estado** | Activar el importador y abrir su pantalla | ✅ tipo, taxonomía, grupo de casillas, constante de la clave presente (sin mostrar su valor) y salida a internet. Si falta algo, ❌ con el motivo | 🔲 |
| M1 | **Primera carga de Música** | Cargar las 5 filas con el estado actual de YouTube | **64 fichas** (62 si ya se limpiaron los duplicados). Categorías: Espíritu Santo 7, Virgen María 7, Navidad 7, San José 2; «Tiritaito Music» **no crea categoría**. 1 aviso por el ID repetido; 0 errores | 🔲 |
| M2 | **Segunda sincronización** | Pulsar de nuevo sin tocar nada | 0 creadas, 0 actualizadas, 64 sin cambios, **0 escrituras** | 🔲 |
| M3 | **Una playlist rota** | Una fila con un ID inventado y otra con una playlist privada | Solo esas filas pasan a «error» con el motivo; las demás se importan; **no se retira nada** (3b) | 🔲 |
| M4 | **Clave inválida o cuota agotada** (simulada) | Clave falsa; forzar la respuesta de cuota agotada | Todas las filas en «error»; 0 retiradas; 0 cambios | 🔲 |
| M5 | **Canción retirada (R1)** | Quitar una canción de todas las playlists y sincronizar; luego volver a añadirla | Pasa a **borrador** y desaparece de Post Cards; **mientras el vídeo exista se sigue refrescando** su `vid_comprobado`; al volver, se publica con el mismo slug | 🔲 |
| M6 | **Freno del 20 %** | Quitar de golpe más de 12 de 64 | **Se detiene, avisa y no retira nada**; el botón «Aplicar de todos modos» sí lo hace | 🔲 |
| M7 | **Modo simulación** | Cambiar títulos, quitar una canción y simular | Lista lo que se crearía, cambiaría y retiraría; recuento de entradas y fechas de modificación **idénticos** antes y después | 🔲 |
| M8 | **ID repetido** | «Todas» real (`tHN2sKkguYA` ×2) | Una sola ficha y un aviso | 🔲 |
| M9 | **«Hágase en mí»** | `b0NmnYywmas` y `YiFwoQSCQxs` | **Dos fichas distintas**, slugs distintos, ninguna fusionada. Los títulos son distintos, tal como en YouTube. Como mucho, aviso | 🔲 |
| M10 | **Subidas duplicadas** («Él me ama», «Bajo tu manto») | Importar antes y después de limpiar las playlists | Antes: 2 avisos de «posible duplicado». Después: 62 publicadas y 2 en borrador | 🔲 |
| M11 | **Categorías** | Renombrar «Navidad» en los ajustes; quitar una canción de «Navidad» en YouTube; quitar una de «Tiritaito Music» pero no de «Navidad» | El mismo término se renombra (0 duplicados); la canción pierde esa categoría; en el último caso, **aviso «en una categoría pero no en la playlist «Tiritaito Music»»** | 🔲 |
| M12 | **Orden de Música** | Comprobar el resultado | Más reciente primero según la fecha del vídeo; añadir una canción no reescribe las demás | 🔲 |
| M13 | **Orden de Vía Crucis** | Cargar las 2 playlists | «1º»…«14º» en orden; «(I)»…«(XIII)» con «(XIII)» **sin quedar al final por error**; el vídeo introductorio **no** puntúa como 9 y va el primero con aviso | 🔲 |
| M14 | **Seminarios** | Fila con «ocultos = sí» | No listados incluidos, privados excluidos; año y mes del texto, **descendentes**; desempate por la fecha del vídeo; los 2 más recientes primero | 🔲 |
| M15 | **Edición manual** | Cambiar a mano el título de una ficha; luego cambiar el título en YouTube | Se conserva hasta que YouTube cambie **ese** vídeo; entonces se pisa (dato más actual); por eso los cambios de título conviene hacerlos en YouTube | 🔲 |
| M16 | **Pasadas parciales** | Presupuesto 0 y sincronizar varias veces. **Devolver el valor** | Una ficha por pasada, «pendiente»; al final 64 sin duplicados | 🔲 |
| M17 | **Cambios en YouTube** | Cambiar un título; poner un vídeo en privado; eliminar otro | Título actualizado con el mismo slug; el privado y el eliminado se retiran en la siguiente sincronización | 🔲 |
| M18 | **Tarea diaria y botón** | Mirar «Próxima sincronización» | Mañana a una hora distinta de la del podcast (04:30); el botón sincroniza ya | 🔲 |
| M19 | **R3 y regla de los 30 días** | Poner `vid_comprobado` de una ficha retirada en hace 26 días y hacer que su vídeo no se pueda confirmar | **Se vacían título, miniatura y fecha y queda el ID**; si la canción vuelve, se rellena todo | 🔲 |
| M20 | **Área nueva sin tocar código** | Crear tipo y taxonomía de Lenguas (o de Vía Crucis), asignar el grupo `vid_` y añadir su fila | Se importa; si falta algo, el Estado lo dice | 🔲 |
| M21 | **Lectura en Avada** | Post Cards sobre el tipo: tarjetas, filtros, orden | Aparecen publicadas, ordenadas y filtradas; los borradores no salen | 🔲 |
| M22 | **Fichas fuera de Google** | Abrir `/wp-sitemap.xml` y el código de una ficha | Las fichas **no figuran** en el mapa y emiten `noindex`, pero **su enlace funciona** | 🔲 |
| M23 | **Títulos tal cual** | Importar los 64 títulos de Música y los 28 de Vía Crucis | Cada ficha lleva **exactamente** el título de YouTube, sin tocar sufijos, mayúsculas ni tildes | 🔲 |
| M24 | **Avisos y semáforo** | Simular una última sincronización correcta de hace 11 días y de hace 21 | Amarillo y rojo en Estado y en el escritorio; el endpoint del estado devuelve el color (y la app lo pintará cuando Proyecto 5 lo construya) | 🔲 |

**Qué se podrá probar sin el importador:** solo el bloque A. **Qué solo se cierra en producción:** A8, A2 (cabecera real), M18 con cron real y el rendimiento con todas las fichas.

### 13.3 Resultados del bloque A (Álvaro, 26–27 de octubre de 2026; Avada 7.16.3)

| Prueba | Resultado | Qué significa |
|---|---|---|
| A7 Orden | ✅ | Menu Order ordena en numérico. El elemento Title sí admite contenido dinámico |
| A1 Filtros | ✅ con ajustes | Filtros por categoría bien en rejilla con `-1`; la pestaña «All» de Avada se duplica con «Todas» (**no se crea «Todas»**); pestañas alfabéticas; sin filtros en carrusel; con «Load More» solo ven lo cargado |
| A1-bis Casilla | ✅ | Post Cards filtra por valor de casilla (8 de 10). Sirve para el tipo de seminario |
| A4 Miniatura | ✅ | Miniatura desde una casilla con Image + «ACF Image». ≈130 kB por 10; carga diferida sin demostrar |
| A6 Al pulsar | ✅ / ⚠️ | Permalink ✅. Lightbox en Columna ✅ (abre, suena, se para al cerrar). Botón: existe la opción, sin probar con vídeo dinámico. Off-Canvas ⚠️: no detiene el vídeo al cerrar |
| A5 Contenedores | ⚠️ | Vídeo vivo en carrusel ❌; miniatura + Lightbox en carrusel ✅; Modal ✅ con ID fijo |
| A9 Ficha | ⚠️ | Layout y vídeo ✅; `og:image` genérica, faltan `og:description` y `twitter:*` → snippet (traspaso a Proyecto 2) |
| A2 Error 153 | ✅ | Seis bloques reproducen; no apareció el 153; sin `Referrer-Policy` en Local ni en producción; Privacy Consent Tools Off |
| A3 API de YouTube | 🔲 | Falta. Entonces no había acceso a la cuenta de Google; ya hay (Carlitos y Álvaro) |
| A8 Raiola | 🔲 | Lo hace Carlitos con la web vieja (mismo hosting) |

### 13.4 Pruebas pequeñas que quedan (Álvaro)

1. **Botón con Lightbox con el vídeo de una casilla** (para el vídeo de cada día de las novenas).
2. **Modal con un ID de vídeo dinámico** (para Seminarios).
3. **La pestaña «All» en español:** cómo se muestra y cómo cambiarla.
4. **Lightbox en Columna dentro de un Post Cards real de Música** y, cuando haya 70 miniaturas, la carga diferida.


---

## 14. Qué hay que actualizar en otros documentos (lista para el cuarto chat)

*No se ha editado ningún otro documento en esta fase. El cuarto chat debe cargar **solo** estos.*

| # | Documento | Qué cambiar | ¿Cuándo? |
|---|---|---|---|
| 1 | `00_CORE.md` | **(a)** Sección 8: añadir la fila del **error 153** (el reproductor de YouTube exige la cabecera `Referer`; no suprimirla con un `Referrer-Policy` estricto; usar `strict-origin-when-cross-origin` en el iframe o en la página; fuente: historial de la IFrame Player API, 9 julio 2025). **(b)** Sección 3.2: marcar `tt_youtube_json_url`, `tt_seminarios_json_url` y `tt_viacrucis_json_url` como transitorias. **(c)** Sección 3: subsección nueva del tipo `cancion`, la taxonomía `categoria_musica`, el grupo `vid_*` y los datos privados `_tt_vid_*`. **(d)** Sección 1: el snippet del importador de vídeos y la constante de la clave (**solo el nombre**) en `wp-config.php`; clave de Local aparte | (a) ya; (b)–(d) cuando se cree |
| 2 | `README.md` y `TIRITAITO_FOR_CREATORS_VERSIONS.md` | El nombre del archivo de la app V1 es fijo (`v1-01`); la versión real es la **v1-15** y vive en el pie del HTML | Ya |
| 3 | `ORACIONES_ESTRUCTURA.md` | **2.1:** Lenguas y Vía Crucis son áreas del importador (Lenguas, tras las pruebas); quitar «hoy `tt_viacrucis_json_url`» al migrar. **3.1, 3.2, 3.7 #1 y pregunta 1 de la Sección 4:** la Inmaculada queda como carrusel de días con vídeo fijo a mano. **3.6/3.8:** Sagrados Corazones, la canción es la misma todos los días (ID `Rwh6N8Q7D1s`); Espíritu Santo, vídeos del día en una playlist, a mano, canción fija pendiente | Ya, salvo lo pendiente de P3 |
| 4 | `ALCANCE_WEB_NUEVA.md` | Pregunta abierta #10: **respondida** (un mismo importador sirve para Música, Vía Crucis y Seminarios). Fila 4.E: Tiritaito Music es una ficha por canción, con página propia compartible | Ya |
| 5 | `METODOLOGIA_CONSTRUCCION.md` Sección 2 | Fila «Tiritaito Music (con cola)»: cerrar la evaluación (`[tt_podcast]` no la cubre) y anotar la decisión **A y luego D** | Ya |
| 6 | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Secciones 1 y 2.4 | El ejemplo «consumo de JSON de Seminarios/Música/Vía Crucis» como funcionalidad que Avada no tiene y construye Carlitos **deja de ser cierto**: pasa a ser un importador de servidor (Proyecto 2) y la página queda en territorio de Avada | Cuando se construya |
| 7 | `CATALOGO_ELEMENTOS_AVADA.md` y `GUIA_AVADA_LOCAL.md` | **Ya con resultados (13.3):** Post Cards **sí** filtra por valor de casilla; Show Filters con taxonomía propia (duplica «All», sin orden ni pestaña inicial, sin filtros en carrusel); Image + «ACF Image» para miniaturas; Lightbox solo en Columna y Botón; Off-Canvas no detiene el vídeo; Privacy Consent Tools hoy Off y fuentes en CDN (los documentos decían On y Local); radio 10 / 15 / 25 px | Cuarto chat |
| 8 | `CUADERNO_DEL_CONSTRUCTOR.md` | Entradas de A1–A9 conforme se terminen | Tras A1–A9 |
| 9 | App de Creators (Proyecto 5) | **Semáforo** del importador (necesita el endpoint del estado de Proyecto 2). Los generadores YouTube y la lista de playlists del `localStorage` quedan obsoletos cuando migren las áreas | Semáforo: tras el importador. Generadores: al migrar, no antes |
| 10 | `HOMBRES_DE_DIOS_ESTRUCTURA.md` | Hallazgo 6: el elemento **Title sí admite contenido dinámico** en Avada 7.16.3 | Cuarto chat |

---

## 15. Próximos pasos y preguntas abiertas

### Próximos pasos

| # | Quién | Qué |
|---|---|---|
| 1 | Álvaro | Crear el **proyecto de Google Cloud y las dos claves** (producción y Local) con la cuenta de Tiritaito (Sección 4.4), **sin compartir su valor en chats ni documentos** |
| 2 | Álvaro (Proyecto 3/7) | **A1–A7, A9 y A2: hechas (26–27 oct).** Falta **A3** (ya hay acceso a la cuenta de Google) y las pruebas pequeñas de la Sección 13.4 |
| 3 | Carlitos | **A8** con la web vieja (mismo hosting que la nueva): pasos en el apartado A8 del prompt |
| 4 | Carlitos | Limpiar los duplicados en las playlists (C5); responder **P3, P5 y P6** (Sección 11.2) |
| 5 | Proyecto 2 → Álvaro | **Importador, endpoint del estado y etiquetas para compartir: escritos el 30 oct (Sección 6.8).** Falta **instalarlos en Local y pasar los 25 casos M0–M24** (Álvaro), y que Carlitos ponga los IDs de «Espíritu Santo» y «Navidad» (P5) |
| 6 | Proyecto 5 | **Semáforo** en la app, cuando exista el endpoint (puede ir en paralelo al paso 6) |
| 7 | Proyecto 2 | Crear el área de **Lenguas** cuando pasen los 25 casos |
| 8 | Carlota (Proyecto 4/6) | **Solo cuando todo lo anterior funcione** (C16): diseños visuales de la Dirección 1 con hueco para el reproductor, decidiendo K2, K5, K6, K10 y K11 (Sección 11.3) con el brief de la Sección 16 |
| 9 | Álvaro | Molde de tarjeta, plantilla de la ficha y secciones, a partir del diseño de Carlota |
| 10 | Carlitos o Proyecto 11 | Reproductor con cola (D), más adelante |
| 11 | Cuarto chat | Aplicar la lista de la Sección 14 |

### Preguntas abiertas

| # | Pregunta | Bloquea a | Quién decide |
|---|---|---|---|
| 1 | **P3** Espíritu Santo: ¿«Pentecostés» es su playlist de vídeos del día? ¿Cuál es su canción fija? | Su molde | Carlitos |
| 2 | **P5** Cuatro playlists sin abrir: ¿públicas, no listadas o privadas? | Primera carga | Carlitos |
| 3 | **P6** ¿Cómo se rellena el tipo de cada seminario (casilla `sem_tipo`): por regla de texto o a mano? | Fichas de Seminarios | Carlitos |
| 4 | ¿Cómo se muestra la pestaña «All» de Avada en español y cómo se cambia? | Aspecto de los filtros | Álvaro (13.4) |
| 5 | ¿Por qué las playlists reales tienen más vídeos que los JSON (70 frente a 65, 15 frente a 14)? ¿Nuevos o no listados? | Primera carga | Prueba A3 |
| 6 | ¿La vista previa al compartir una ficha muestra imagen? | K10 | Prueba A9 |
| 7 | ¿En YouTube el orden de «Todas» coincide con la fecha del vídeo y con la fecha de añadido? | Confirmar C2 | Prueba A3 |
| 8 | ¿Qué devuelve la API con un vídeo **eliminado** o privado dentro de una playlist? El generador solo descarta `private` | Regla de retiradas | A3 y M17 |
| 9 | ¿La política de privacidad del sitio cumple lo que piden las condiciones de YouTube para quien incrusta vídeos? | Cumplimiento | Hna C |
| 10 | ¿Puede reducirse el cupo diario del proyecto desde la consola? | Mitigación de una filtración | Carlitos, al crear la clave |
| 11 | ¿«Con Jesús hacia la Cruz - Novena a la Divina Misericordia» está relacionado con la novena del podcast? | Vía Crucis y Novenas | Carlitos |
| 12 | ¿Existe un perfil de Spotify de Tiritaito? (la URL del export lleva un `#` y está rota; heredada del 16 de septiembre) | Bloque de Spotify | Carlitos |
| 13 | ¿Avada añade su propia pestaña «Todos» a los filtros? | Duplicado con nuestra categoría | Prueba A1 |
| 14 | ¿Algunos títulos de YouTube llevan erratas de tilde («Hágase en mi», «Que seria de mi sin ti», «Hazme decir si»)? Se mostrarán tal cual | Calidad editorial | Carlitos, en YouTube |

---

## 16. Referencia visual, direcciones de boceto y brief para Carlota (conservado y actualizado)

### 16.1 La referencia: Hakuna Group Music (`hakuna.org/hakuna-group-music`)

Consultada en directo el 16 de septiembre. Su página es un **escaparate**, no una aplicación. Las cuatro lecciones que valen para nosotros:

1. **No construyen reproductor propio:** delegan en Spotify y YouTube. Todo el esfuerzo está en la presentación.
2. **Índice de anclas al principio:** una página larga deja de dar miedo si lo primero que se ve es la lista de lo que hay dentro (Menu Anchor o Table of Contents, nativos en Avada).
3. **El patrón «La Pecera»** (título de sesión y debajo N piezas con vídeo, título y enlace) es literalmente una playlist de Tiritaito Music sin reproductor propio.
4. **Separan «escuchar» de «conocer»** (la historia detrás de cada canción). Tiritaito no tiene hoy letras ni historia: **no se dibujan esas secciones** (decisión del 13 de octubre).

**Lo que no conviene copiar:** vídeo `.mov` de fondo; embeds de Instagram (apagados por privacidad en el export); sus 10 secciones (Tiritaito Music es una entrada, no un sitio: scope creep); el widget de Spotify (sin confirmar que el perfil exista).

### 16.2 Tres direcciones de boceto (decidido el 22 de octubre: la 1 con la 3 dentro)

**Dirección 1 — «Escaparate»** (la más parecida a Hakuna): hero corto con acuarela · índice de anclas · **canción destacada** en grande (automática: la más reciente) · «Todas las canciones» con pestañas de categoría y rejilla de 3 columnas (miniatura, título, ▶). Prioriza **descubrir**; la que mejor se apoya en Show Filters.

**Dirección 2 — «Discografía»** (por colecciones): cada playlist es un «disco» con su portada y su contador. ⚠️ Solo hay 4 categorías además de «Todas» y cubren 23 de 64 canciones: solo funcionaría si se crean más categorías. **Descartada por ahora.**

**Dirección 3 — «Reproductor primero»**: la página es la aplicación (buscador, pestañas, vídeo grande, lista y barra fija). **Es la opción D**: máxima funcionalidad y máxima dependencia de código.

**La elegida: Dirección 1 con la 3 dentro** (= A y luego D). Carlota (cuando acaben las pruebas) dibuja la 1 completa y deja un **hueco reservado** para el reproductor con cola, que se construye después sin tocar lo demás:

```
┌────────────────────────────────────────────────┐
│ HERO (K2) · índice de anclas                    │  ← Avada nativo
├────────────────────────────────────────────────┤
│ ▶ LA MÁS RECIENTE                               │  ← Post Cards, 1 ficha (automática)
├────────────────────────────────────────────────┤
│ TODAS LAS CANCIONES                             │
│ ( Todas )( Espíritu Santo )( Virgen María )…    │  ← Show Filters = categorías
│ ┌────┐ ┌────┐ ┌────┐   título (K6)               │  ← tarjetas 16:9; al pulsar → K5
│ └────┘ └────┘ └────┘                            │
├────────────────────────────────────────────────┤
│ ░░░░ HUECO RESERVADO: reproductor con cola ░░░░ │  ← opción D, después (K11)
└────────────────────────────────────────────────┘
```

### 16.3 Valores reales para el boceto ☑

*Salen de `avada-global-options.json` (export del 14 de agosto) citado en el informe anterior; no se han vuelto a abrir en esta sesión.*

| Qué | Valor |
|---|---|
| Colores 1–8 | `#FFFFFF`, `#F5F5F7`, `#c7c7cc`, `#86868b`, rojo de marca `#BF4646`, rojo hover `#A33B3B`, texto secundario `#3a3a3c`, texto principal `#1d1d1f` |
| Colores 9–13 | **Sin cargar** (`--tt-red-bg`, `--tt-txt3`, `--tt-green`, `--tt-orange`, `--tt-alert`): usar hex directo y anotar qué variable sustituyen |
| Titulares | Yeah Papa; H1/H2/H3 50/43/37 px con `letter-spacing: 2px`; H4/H5/H6 31/25/20 px; título de entrada 78 px |
| Cuerpo | Helvetica Neue 16 px |
| Radio | **15 px** (decidido el 22 de octubre; coincide con la norma de `ORACIONES_ESTRUCTURA.md`); el export decía 10 px (es anterior) y el boceto anterior 25 px |
| Ancho y breakpoints | 1200 px · ~1024 px (Medium) · 480 px (Small) |
| Lightbox | Skin Metro White, opacidad 0,90, flechas, deeplinking, sin autoplay |
| Rendimiento | Video Facade On, Offscreen Rendering On, fuentes en local |

**Lo que Carlota no debe dar por hecho:**
- Nada de «Clase CSS» (decisión del 6 de septiembre). Si un efecto no está en `CATALOGO_ELEMENTOS_AVADA.md` 5 bis, se escala.
- 🔲 El **cristal esmerilado** (Filtros de Fondo) sigue sin confirmarse en nuestra versión.
- 🔲 Los **filtros sobre un carrusel** probablemente no funcionen (la documentación de Portfolio lo dice; para Post Cards es la prueba A1). Filtros → rejilla; carrusel → sin filtros.
- Nada por encima del reproductor de YouTube ni de sus controles (N13).

### 16.4 Hallazgos laterales heredados (16 de septiembre)

| # | Qué | Estado hoy |
|---|---|---|
| 9.1 | **Post Cards parece filtrar por valor de campo** («Custom Field – Name / Value Comparison / Value»), lo que contradice `CATALOGO_ELEMENTOS_AVADA.md` y `GUIA_AVADA_LOCAL.md` (hacía falta un hook) | ✅ Las opciones **vuelven a constar** en la documentación oficial de hoy (pestaña General). Sigue **sin probar**: conviene hacerlo junto con A1. No se ha tocado ningún documento |
| 9.2 | Radio de botón 10 px (export) frente a 25 px (catálogo) | Parcialmente aclarado: el catálogo recoge hoy 15 px como norma (según ORACIONES). Falta confirmarlo leyendo el catálogo |
| 9.3 | Tres grafías de las fuentes en el export («Helvetica Nueva», «YEAH-PAPA», «Yeah-Papa») | Sin cambios; comprobación visual pendiente |
| 9.4 | Avada se ha partido en «Avada Classic» y «Avada One»; la documentación de Classic vive ya en `classic.avada.com` | ✅ **Reconfirmado hoy:** las páginas de `avada.com` redirigen a `classic.avada.com`. Las URL citadas en `GUIA_AVADA_LOCAL.md` y en el catálogo pueden dejar de resolver |
| 9.5 | La fila de `[tt_podcast]` en `METODOLOGIA_CONSTRUCCION.md` se puede cerrar | Sigue pendiente (Sección 14, fila 5) |

### 16.5 Brief para Carlota (se entrega cuando acaben las pruebas)

**Cuándo:** solo después de que todas las pruebas hayan pasado y todo funcione (C16). Hasta entonces no hay que dibujar nada. Cuando llegue el momento, con su libertad creativa habitual y dentro de lo que las pruebas hayan confirmado; este brief se completará con sus resultados (sobre todo A5 y A6).

**Ya decidido:**
- **Dirección 1 con un hueco para el reproductor con cola.**
- **Tarjeta:** miniatura 16:9 de YouTube, esquinas de **15 px**.
- **Filtros = categorías**, con «Todas» como una más (4 categorías reales y «Todas»).
- **Canción destacada:** la más reciente, automática; no hay interruptor.
- **Cada canción tiene su página**, compartible: necesita una plantilla de ficha.

**Títulos:** tal como están en YouTube, con el sufijo «/ Tiritaito Music» (63 de 64), media de 45 caracteres y máximo 98; 13 en MAYÚSCULAS (11 tras quitar duplicados). La tarjeta debe tolerar dos líneas, salvo que se editen en YouTube. «Hágase en mi» aparece dos veces con títulos distintos.

**Lo que ella decide (Sección 11.3):** K2 hero · K5 qué pasa al pulsar (Off-Canvas, Lightbox o ficha) · K6 títulos largos · K10 plantilla de la ficha · K11 el hueco del reproductor.

**Lo que debe tener presente:**
- Nada por encima del reproductor de YouTube ni de sus controles; reproductor mínimo de 200×200 px.
- Sin «Clase CSS». Cristal esmerilado y filtros sobre carrusel, sin confirmar.

---

*Para la mayor gloria de Dios · tiritaito.com*

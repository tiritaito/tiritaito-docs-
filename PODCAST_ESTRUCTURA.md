# TIRITAITO.COM — Podcast de la web nueva: estructura
**Registro vivo: cómo está montado el podcast (Canal → Temporada → Episodio), las casillas con su nombre exacto, las piezas de Avada, el importador, los hallazgos y lo que queda pendiente**
*Para el repositorio: `03-guias-practicas/PODCAST_ESTRUCTURA.md` · Es el único documento del podcast de la web nueva (junto con el código del importador, `apps/v2/tt-podcast-importador.php`) · Redactado por Proyecto 2 (Hno C) el 10 de octubre de 2026 · Modelo: `HOMBRES_DE_DIOS_ESTRUCTURA.md`*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento y cómo leerlo

Responde: **¿cómo funciona el podcast de la web nueva, qué hay construido, con qué nombres exactos, y qué falta?** Se actualiza cada vez que una pieza se construye o se comprueba, igual que `HOMBRES_DE_DIOS_ESTRUCTURA.md`.

| Si necesitas… | Ve a… |
|---|---|
| El podcast de la web vieja (V1) | `02_REF_PODCAST.md` — **solo V1** |
| **Cómo funciona todo, los nombres reales, lo construido, el importador, las pruebas y lo pendiente** | **Este documento** |

**Estados** (mismos símbolos que el resto del repositorio):

| Marcador | Significa |
|---|---|
| ✅ | Comprobado: en Local (informe de pruebas del 1–3 oct) o en la simulación del importador (118 comprobaciones) |
| ☑ | Declarado por Álvaro como funcionando, **sin captura revisada por Proyecto 2** |
| 🔲 | Sin probar o sin construir |
| ❌ | Probado y no funciona |
| ◐ | Funciona en parte |

---

## 1. Estado en una mirada (10 de octubre de 2026)

- **Decisiones de estructura: cerradas** (Sección 2).
- **Importador v1.1: escrito y probado en una simulación** (118 comprobaciones ✅). **En Local real:** una sincronización de San Serafín (36 episodios, 1 temporada, 0 errores) y el "Estado" en verde, ambos ☑ por Álvaro. **El plan completo de pruebas P3–P13 no consta como ejecutado.** La v1.1 todavía no se ha instalado en Local.
- **Construido en Avada** (☑ salvo lo marcado): moldes de tarjeta, página de temporada, ficha del episodio y bloque "Podcast" en la ficha del santo.
- **Canales con feed hoy:** solo San Serafín, cargado en la entrada "TT — Santo de prueba" (no en la ficha real). **Pendiente:** los otros 9 canales, entre ellos Charlas de la Biblia (Sección 14).
- **Por tanto:** el podcast está **cerrado en estructura y decisiones, y abierto en pruebas y despliegue**.

---

## 2. El método y las decisiones

### 2.1 La regla única

> **Una entrada tiene podcast si su campo de feed está relleno.** El importador crea las temporadas y los episodios y rellena las relaciones: canal → temporadas, canal → episodios y temporada → episodios. Cada bloque se dibuja solo si su relación tiene más de 0 elementos.

```
CANAL  (entrada: un santo, Charlas, Rincón o un tipo de oración)
 ├── FEED        ← persona
 ├── CARÁTULA    ← importador, una vez (la persona puede cambiarla)
 ├── TEMPORADAS  ← importador (solo las que tienen episodios)
 └── EPISODIOS   ← importador (todos los del canal)
        │  bloque "Podcast": 2 o más temporadas → tarjetas de temporada · 1 temporada → lista de episodios
        ▼
TEMPORADA  (CPT "temporada")
 ├── nombre · portada · descripción      ← persona (el importador da valores iniciales)
 ├── número · canal (+ dirección y nombre del canal)   ← importador
 └── EPISODIOS                            ← importador
        ▼
EPISODIO  (CPT "episodio", taxonomía "canal")
 └── audio · vídeo (si hay) · imagen · duración · nº · temporada (+ su dirección) · texto completo
        ▼  ficha: reproductor de Avada + texto + Anterior / Siguiente
```

El audio sigue en Spotify; la web solo lo reproduce con el elemento **Audio** de Avada.

### 2.2 Decisiones

| # | Decisión | Fecha |
|---|---|---|
| — | Cada episodio es una entrada de un tipo propio y público; el audio sigue en Spotify | 1–3 oct |
| — | La descripción del episodio va en el contenido de la entrada (nadie edita episodios con el Builder) | 4 oct |
| — | 10 canales: 4 santos (Santa Teresita, Emiliano Tardif, Ana Catalina Emmerick, San Serafín de Sarov), Charlas de la Biblia, Rincón de Nico, Rosario, Salmos, Adoraciones, Novenas | 4 oct |
| — | Oración en lenguas, Vía Crucis y Posesión mariana no llevan podcast | 4 oct |
| D1 | El feed vive en una casilla de la entrada del canal + pantalla de solo lectura del importador | 4 oct |
| D2 | Casillas de feed y relación en la propia entrada del canal (no en la taxonomía) | 4 oct |
| D3 | Oraciones: una entrada por canal (O2) o un CPT (O3); se decide al estructurar Oraciones | 4 oct |
| D4 | Imagen: la del canal como imagen destacada de todos; la del episodio, solo enlazada. **Cada temporada tiene su propia portada** (su imagen destacada) | 4 oct |
| D5 | Slug: título; si ya existe, título + 8 últimas letras del GUID | 4 oct (la v1.1 usa las **últimas** 8) |
| D6 | Orden por número de episodio (con `menu_order`) | 4 oct |
| D7 | Anterior/Siguiente con el elemento Pagination; reproducción automática, en el futuro | 4 oct |
| D8 | Imagen para compartir: imagen destacada siempre + logo de marca | 4 oct |
| D9 | Importador del podcast independiente; el núcleo común, en el futuro | 4 oct |
| D10 | WP-Cron diario + botón "Sincronizar ahora"; cron real en el futuro (Sección 13.2) | 4 oct |
| D11 | **T1:** el feed manda en las temporadas; se corrigen en Spotify | 4 oct |
| D12 | **Opción 2:** con una sola temporada, la página del canal lista directamente los episodios | 4 oct |

---

## 3. Registro de definiciones (nombres reales en el Local)

*Fuente: REGISTRO DE DEFINICIONES de Álvaro (9 oct) + importador v1.1. La regla de oro: **ninguna casilla con contenido se renombra**. Los nombres se escriben a mano.*

### 3.1 Tipos de contenido y taxonomía

| Qué | Clave | Notas | Estado |
|---|---|---|---|
| Tipo de contenido | `episodio` | Público, no jerárquico, archivo apagado, **prefijo de URLs apagado**; admite Título, Editor, Imagen destacada y Campos personalizados. Sin revisiones (el importador las desactiva) | ☑ (el "Estado" del importador lo da por existente) |
| Tipo de contenido | `temporada` | Mismos ajustes | ☑ |
| Taxonomía | `canal` | Unida solo al tipo `episodio` | ☑ |

### 3.2 Grupo ACF "TT — Podcast — Episodio" (ubicación: tipo `episodio`)

| Nombre exacto | Tipo | Quién la rellena |
|---|---|---|
| `ep_guid` | Texto | Importador (técnica) |
| `ep_audio_url` | URL | Importador, en cada sincronización |
| `ep_youtube_url` | **Texto** (no URL) | Importador (si el feed trae un enlace en la descripción) |
| `ep_imagen_url` | URL | Importador, en cada sincronización |
| `ep_duracion` | Texto | Importador |
| `ep_numero` | Número | Importador (también va a `menu_order`) |
| `ep_temporada` | Número | Importador |
| `ep_temporada_ref` | Objeto de publicación → Temporada | Importador |
| `ep_hash` | Texto | Importador (técnica) |
| **`ep_texto`** *(v1.1)* | **Editor** | Importador: el texto completo con párrafos |
| **`ep_temporada_url`** *(v1.1)* | **URL** | Importador: dirección de la página de su temporada |

### 3.3 Grupo ACF "TT — Podcast — Temporada" (ubicación: tipo `temporada`)

| Nombre exacto | Tipo | Quién la rellena |
|---|---|---|
| `tmp_numero` | Número | Importador |
| `tmp_canal` | Objeto de publicación | Importador |
| `tmp_episodios` | Relación → Episodio | Importador |
| `tmp_descripcion` | Área de texto | **Una persona** (opcional). Nombre y tipo confirmados por Proyecto 2 |
| **`tmp_canal_url`** *(v1.1)* | **URL** | Importador: dirección de la página del canal |
| **`tmp_canal_nombre`** *(v1.1)* | **Texto** | Importador: título de la entrada del canal |

**Nombre y portada de la temporada:** el nombre es el título de su ficha ("Temporada N" al crearla; una persona lo cambia); la portada es su **Imagen destacada** (el importador pone la carátula del canal; una persona puede cambiarla). Una vez puestos, el importador no los toca.

### 3.4 Casillas del canal

| Canal | Casillas | Estado |
|---|---|---|
| Hombres de Dios (`hombres_de_dios`) | Grupo **"TT — HdD — Podcast"** (aparte de "TT — HdD — Ficha"): `hdd_podcast_feed` (URL, persona) · `hdd_podcast_caratula` (Imagen) · `hdd_temporadas` (Relación → Temporada) · `hdd_episodios` (Relación → Episodio) | ☑ |
| Resto de canales | Grupo **"TT — Podcast — Canal"**: `pod_feed` (URL, persona) · `pod_caratula` (Imagen) · `pod_temporadas` (Relación → Temporada) · `pod_episodios` (Relación → Episodio) | 🔲 **sin crear** |

Todas las relaciones: **no bidireccionales**; formato de retorno, el de por defecto.

### 3.5 Datos privados del importador (no son casillas; no editar)

`_tt_pod_clave` (en cada temporada: `<ID del canal>:<nº>`) · `_tt_pod_canal` (en cada episodio: ID del canal dueño) · `_tt_pod_term` (en cada canal: término de la taxonomía que le corresponde).

---

## 4. Registro de piezas de Avada

| Tipo | Nombre | Lee | Estado |
|---|---|---|---|
| Molde de tarjeta | `TT — Podcast — Episodio` | `ep_numero` y `ep_duracion` (ACF Text, "mostrar solo si"), título, imagen destacada, enlace Permalink. Fila: número · miniatura · título · duración · ▶; hover `#F5F5F7` | ✅ visto en la lista según Álvaro (orden, hover, "mostrar solo si"); móvil ☑ |
| Molde de tarjeta | `TT — Podcast — Temporada` | imagen destacada, título, enlace Permalink | ☑ construido, **nunca visto dentro de una lista real** |
| Layout | `TT — Podcast — Temporada` (All del tipo `temporada`) | imagen destacada, título, `tmp_descripcion` ("mostrar solo si"), lista por `tmp_episodios` ordenada por Menu Order ascendente | ✅ página pública vista |
| Layout | `TT — Podcast — Episodio` (All del tipo `episodio`) | título, imagen destacada, `ep_audio_url` (ACF Link, Return HTML = No), `ep_youtube_url` (ACF Text, "mostrar solo si"), Excerpt, Pagination | ✅ página pública vista; suena el audio |
| Bloque | "Podcast" dentro de `TT — HdD — Contenido` (Layout `TT — HdD — Ficha`) | `hdd_temporadas` (cuenta), `hdd_episodios` | ☑ "se ve estupendamente"; la cuadrícula de temporadas **no se ha visto nunca** |

**Condiciones del bloque Podcast** (cada elemento admite una sola condición, y basta con una):

| Elemento | Condición |
|---|---|
| Container "Podcast" | ACF Relationship Count · `hdd_temporadas` · Greater Than · `0` |
| Post Cards de episodios | ACF Relationship Count · `hdd_temporadas` · Equal To · `1` |
| Post Cards de temporadas | ACF Relationship Count · `hdd_temporadas` · Greater Than · `1` |

**Ficha de sección — Podcast (Hombres de Dios):** desaparece si `hdd_temporadas` tiene 0. Santos que la usan: Santa Teresita, Emiliano Tardif, Ana Catalina Emmerick, San Serafín de Sarov. Los otros 8 dejan el feed vacío y el bloque no existe.

---

## 5. El importador "TT Podcast — Importador" v1.1

*Código: `tt-podcast-importador.php` (snippet de Code Snippets, tipo PHP, alcance "everywhere") · Pantalla: Herramientas → Podcast — Importador · Sincronización diaria a las 04:30 (WP-Cron) · Presupuesto de 25 s por pasada, con continuación al minuto.*

| Hace | No hace (a propósito) |
|---|---|
| Lee el feed de cada entrada con el feed relleno, en cualquier tipo de contenido | No borra ni despublica nada |
| Crea temporadas y episodios; refresca audio e imagen en cada sincronización | No recrea un episodio que está en la papelera |
| Rellena las tres relaciones desde el estado real de la base de datos | No toca nombre, portada ni descripción de una temporada ya creada |
| Descarga la carátula del canal **una vez** | No se detiene en un feed enorme: cada pasada avanza |
| Reanuda sola una sincronización a medias | Con un feed vacío, no toca nada |

**Novedades de la v1.1 sobre la v1.0:** casillas nuevas (`ep_texto`, `tmp_canal_url`, `tmp_canal_nombre`, `ep_temporada_url`); enlace de YouTube con prioridad (el que va tras «ver en YouTube», después un enlace `/live/`, y solo al final el primero); un episodio sin temporada hereda la de sus vecinos por fecha, y sin número ocupa el hueco entre ellos, siempre con aviso; sufijo de slug con las 8 **últimas** letras del GUID; la descarga de la carátula no se descuenta del tiempo de los episodios; **identidad del canal estable** (ver hallazgo 14).

---

## 6. Resultado de las pruebas H1–H7 (Álvaro, 9 oct)

| Prueba | Resultado |
|---|---|
| H1 Episodio sin editor de Avada | 🔲 no verificada |
| H2 Orden por Menu Order | ✅ (1, 2, 3… en la web pública) |
| H3 Imagen desde `ep_imagen_url` | 🔲 no probada; se usa la imagen destacada |
| H4 Contador de episodios en la tarjeta de temporada | 🔲 no probada |
| H5 Enlazar a la entrada de `tmp_canal` | ❌ no funciona con ACF Relationship; **resuelto en la v1.1** con casillas de URL |
| H5 "Ver la temporada" desde `ep_temporada_ref` | 🔲 no probada (mismo fallo esperado); **resuelto en la v1.1** con `ep_temporada_url` |
| H6 Anterior/Siguiente del mismo canal | ◐ el elemento existe con "Same Taxonomy Term"; no verificado a dónde lleva ni en qué orden |
| H7 Contar relaciones | ✅ con ACF Relationship Count (Equal To / Greater Than…) |

*Nota de Álvaro: las pruebas H1–H7 no las hizo él ni vio el resultado de las de otra persona.*

---

## 7. Diferencias con el boceto de Carlota (ficha del episodio)

| # | Boceto | Real | Salida |
|---|---|---|---|
| 1 | Etiqueta "Reproduciendo · nombre del canal" | No hay | Probar un contenido nativo (términos de la entrada); si no, una casilla `ep_canal_nombre` |
| 2 | Orden: portada → título → "Temporada N · Episodio M" | Título arriba, sin la línea | Nativo: mover el título y añadir la línea |
| 3 | Botón "Ver vídeo / Cambiar a audio" | No hay | **Traspaso a Proyecto 11** |
| 4 | Barra de audio con botones redondos ⏮ ▶ ⏭ | Barra nativa, línea de progreso gruesa y oscura | **Traspaso a Proyecto 11** |
| 5 | Cuadro gris con el texto entero | Resumen de ~20 palabras | **Resuelto en la v1.1:** `ep_texto` (Editor) |
| 6 | Botón "Ver la temporada N" | No hay | **Resuelto en la v1.1:** `ep_temporada_url` |
| 7 | Anterior/Siguiente como botones | Enlaces de texto en inglés ("Previous / Next") | **Traspaso a Proyecto 11** |
| 8 | Portada con sombra suave | Sin sombra | Mirar en el elemento Imagen |
| 9 | «‹ canal» en la página de la temporada | No funciona | **Resuelto en la v1.1:** `tmp_canal_url` y `tmp_canal_nombre` |

Otras diferencias menores: miniaturas y portada con imagen destacada en vez de `ep_imagen_url`; portada gris con "TT" cuando falta imagen (no construida); etiqueta "Temporada" sobre el nombre (no construida); contador "N episodios" (no probado); ▶ de contorno en vez de relleno; el elemento YouTube no tiene Border Radius; duración en Poppins y `#3a3a3c` en vez de Helvetica Neue y `#6e6e73` (origen de Poppins sin identificar); títulos de episodio y temporada aún en rojo en vez de `#1d1d1f`.

---

## 8. Hallazgos técnicos (para trasladar al Cuaderno del Constructor y al Catálogo)

| # | Hallazgo | Fuente |
|---|---|---|
| 1 | Contar relaciones: Conditional Rendering → *ACF Relationship Count* (Equal To / Greater Than…). **Una sola condición por elemento**; basta | ☑ Álvaro |
| 2 | Audio desde una casilla URL: Audio → contenido dinámico *ACF Link*, `ep_audio_url`, *Return HTML* = No. Funciona | ✅ informe + ☑ Álvaro |
| 3 | Enlazar una tarjeta entera: Column → Link URL → icono de discos apilados (no la cadena) → Permalink, con Before/After/Fallback vacíos | ☑ |
| 4 | Imagen destacada: elegir *Main Featured Image* (las opciones 2–5 son extras vacíos) | ☑ |
| 5 | **No existe "Post Content" en el contenido dinámico del Text Block**. *Excerpt* / *Archive Description* sí funciona (WordPress lo genera del contenido) pero corta a ~20 palabras y aplana los párrafos → casilla `ep_texto` | ☑ |
| 6 | **ACF Relationship (o Objeto de entrada) como Button URL no da una dirección**: el botón no lleva a ningún sitio → casillas de URL escritas por el importador | ☑ |
| 7 | Una fila de tarjeta se rompe si el título es largo: Column con *No Wrap* + título con ancho 100 % (55 % en móvil) + duración oculta en móvil | ☑ |
| 8 | Audio: con *Controls Color Scheme* = Light, iconos y tiempos se vuelven blancos sobre gris claro; con Dark se leen pero la línea de progreso queda gruesa y oscura. Los campos de Design del Audio solo afectan a ese elemento | ☑ |
| 9 | Pagination trae "Same Taxonomy Term" y "Swap Post Order"; los enlaces salen en inglés ("Previous / Next") | ☑ |
| 10 | Image Max Width no tiene icono de pantalla propio | ☑ |
| 11 | La primera sincronización solo creó 1 episodio (la descarga de la carátula consumió el tiempo) y el importador **continuó solo a los 60 s**: la continuación funciona en WordPress real | ☑ Álvaro. La v1.1 ya no descuenta la carátula |
| 12 | **El enlace de YouTube "el primero que aparezca" falla en Charlas de la Biblia**: muchas descripciones llevan antes un enlace de «Proyecto Biblia» (otro canal); el de la charla va tras «ver en YouTube:» y es de tipo `youtube.com/live/…` | ✅ comprobado en el feed real y en la simulación |
| 13 | Un feed puede traer el enlace de YouTube como texto plano (sin `<a>`) o como `<a>` con un carácter invisible delante; ambos casos los cubre la v1.1 | ✅ |
| 14 | **Renombrar la entrada del canal (su slug o su título) duplicaba todos los episodios** en el diseño de la v1.0 revisado en la simulación: la taxonomía se buscaba por el slug del canal. Corregido: la identidad del canal es el ID de la entrada (`_tt_pod_canal`, `_tt_pod_term`) | ✅ simulación |
| 15 | Los GUID de las primeras Charlas son direcciones (`https://www.tiritaito.com/blog/?p=16495`): sus 8 primeras letras son iguales para todos; se usan las 8 últimas | ✅ feed real |
| 16 | **Los títulos ya llevan su número** ("Capítulo 36: …", "41. …") y en la temporada 1 de Charlas el número del título y el de la etiqueta difieren en 1 ("25. Fin del Génesis" es el episodio 24). Mostrar además `ep_numero` duplica y a veces contradice | ✅ feed real (decisión pendiente, Sección 9) |
| 17 | En Charlas el número de episodio **reinicia en cada temporada** (1–24, 1–42, 1–4); por eso la lista de "todos los episodios del canal" mezclaría las temporadas y solo se usa cuando hay una | ✅ feed real |

---

## 9. Pendientes y decisiones

| # | Pendiente | De quién |
|---|---|---|
| 1 | **Instalar la v1.1 en Local** (pegar el nuevo archivo sobre el snippet actual) y **crear las 4 casillas nuevas** (3.2 y 3.3). Hasta que existan, el "Estado" marcará ❌ y el importador guardará esos valores sin registrar | Carlitos · Álvaro |
| 2 | **Plan de pruebas P0–P13** en Local, con el resultado documentado (hoy solo consta la primera sincronización de Álvaro) | Carlitos |
| 3 | **Charlas de la Biblia:** crear el grupo `pod_*`, sincronizar su feed (3 temporadas, unos 70 episodios) y construir su bloque Podcast. Será la primera vez que se vea la cuadrícula de temporadas (Sección 14) | Álvaro |
| 4 | **Prefijo de URLs apagado** en `episodio` y `temporada`: sus direcciones comparten raíz con las páginas, y un episodio titulado como una página (por ejemplo «Rosario») dejaría una de las dos inalcanzable. Decidir **antes de producción**; cambiarlo después rompe los enlaces compartidos | Carlitos |
| 5 | Mover el feed de San Serafín a su ficha real. Con la v1.1 la identidad es el canal, así que **cambiar de entrada vuelve a crear los 36 episodios**; hacerlo creando el canal real y borrando después el de prueba | Carlitos · Álvaro |
| 6 | Crear el juego `pod_*` en los demás canales (Rincón de Nico, Oraciones) y confirmar si son Entradas o Páginas | Álvaro |
| 7 | Dirección del orden de los episodios (del 1 al N, propuesto, o al revés) | Carlitos · Carlota |
| 8 | ¿Se muestra el número del episodio en la tarjeta, o se quita porque el título ya lo lleva? (hallazgo 16) | Carlota · Carlitos |
| 9 | El enlace de YouTube **se queda también en el texto del episodio** ("ver en YouTube: https://…"). ¿Se quita esa línea al guardarlo? Hoy no se quita | Carlitos |
| 10 | Traspasos a Proyecto 11: botón vídeo/audio, barra del reproductor, Anterior/Siguiente como botones, que la miniatura no se encoja en móvil | Álvaro |
| 11 | Pruebas de Álvaro aún sin hacer: H1, H3, H4, H6 (destino y orden), Ctrl+U de los bloques ocultos, y ver la cuadrícula de temporadas | Álvaro |
| 12 | Un contenido que dé el nombre del canal en la ficha del episodio (diferencia 1) | Álvaro |
| 13 | Cron real en Raiola y que `wp-cron.php` no esté bloqueado | Carlitos |
| 14 | Versión de ACF Pro y si exporta tipos y taxonomías (cómo se llevan las definiciones a producción) | Carlitos |
| 15 | Confirmar la lista de 10 canales (el comentario del código viejo decía "los 12") y conseguir los feeds de los otros 8 | Carlitos |
| 16 | **Novenas con podcast:** confirmar leyendo los feeds de Santa Teresita y de Novenas que Teresita es la temporada 1 y que el feed de Novenas trae Misericordia (1) y Espíritu Santo (2); y decidir si la página de temporada estándar sirve para una novena o necesita su propio diseño (`ORACIONES_ESTRUCTURA.md` §4) | Carlitos · Álvaro |

---

## 10. Cómo añadir un canal nuevo

1. **Comprobar que existe su entrada** (santo, Charlas, Rincón o tipo de oración) y que el juego de casillas de su tipo (`hdd_*` o `pod_*`) está creado y visible en esa entrada.
2. **Pegar la dirección del feed** en su casilla `hdd_podcast_feed` o `pod_feed`.
3. **Pulsar "Sincronizar solo este"** en Herramientas → Podcast — Importador y comprobar la fila del canal (episodios feed / web, temporadas, avisos).
4. **Dar nombre y portada a cada temporada** (título e imagen destacada de su ficha) y, si quieres, `tmp_descripcion`.
5. **Copiar el bloque "Podcast"** de la ficha del santo a la entrada del canal, cambiando los nombres de casilla de `hdd_` a `pod_`.
6. Comprobar los estados (una temporada, varias, episodio sin vídeo…) en escritorio, tableta y móvil.

---

## 11. Instalación y uso del importador

**Antes de pegar el código** tienen que existir en WordPress los dos tipos de contenido, la taxonomía y las casillas de la Sección 3. El importador **no los crea**: escribe en lo que ya existe, y su pantalla de **Estado** dice qué falta o está con el tipo equivocado.

**Instalar** (Code Snippets, versión gratuita): Snippets → Añadir nuevo · título **«TT Podcast — Importador»** · tipo **PHP** · alcance **«Run snippet everywhere»** (si se deja solo en el administrador, la tarea diaria no se ejecuta) · pegar el archivo entero **sin la etiqueta `<?php`** · guardar y activar. **Para pasar de la v1.0 a la v1.1:** crear antes las 4 casillas nuevas y pegar el archivo nuevo **sobre** el snippet existente. Los episodios que importó la v1.0 reciben `ep_texto` y `ep_temporada_url` en la siguiente sincronización, sin rehacerse.

**Ajustes:** solo en dos funciones al principio del código: `tt_pod_cfg()` (claves de los tipos y de la taxonomía, segundos por pasada, hora diaria) y `tt_pod_pares()` (los pares de casillas: añadir un tipo de canal es añadir un par).

**Uso** (Herramientas → Podcast — Importador):

| Quiero… | Hago… |
|---|---|
| Añadir un canal | Relleno su casilla de feed y pulso «Sincronizar solo este» |
| Ver si todo va bien | Tabla de canales: estado, episodios (feed / web), temporadas y avisos; abajo, última ejecución e historial |
| Sincronizar todo | «Sincronizar ahora» |
| Pausar la tarea diaria | Quito la marca «Sincronización diaria automática» |
| Dar nombre y portada a una temporada | Edito su ficha (título, imagen destacada, `tmp_descripcion`); el importador no lo volverá a tocar |
| Quitar un episodio | Lo mando a la papelera (no se recrea) o lo paso a borrador (sigue en borrador) |
| Una sincronización quedó «pendiente» | Continúa sola al minuto, o pulso otra vez |
| Se quedó un bloqueo | «Liberar bloqueo», solo si lleva más de 10 minutos |

**Una edición manual de un episodio se conserva** hasta que el feed cambie *ese* episodio.

**Si algo falla:**

| Síntoma | Causa probable | Qué hacer |
|---|---|---|
| No aparece el menú | Snippet desactivado o con alcance equivocado | Revisar que está activo y en «everywhere» |
| «No hay ningún canal con la casilla de feed rellena» | La casilla se llama distinto o está en otro grupo | Comparar con la Sección 3; mirar el Estado |
| «No se pudo leer el feed» | Dirección mal copiada, o el servidor bloquea la salida | Probar la dirección en el navegador; pedir a Raiola que no bloquee las peticiones salientes |
| Hay episodios en la tabla pero no salen en Avada | La relación se creó como Texto, o Post Cards guarda filtros antiguos | Rehacer la casilla como Relación; borrar el Post Cards y crear uno nuevo |
| Estado ❌ «no admite imagen destacada» | El tipo no la tiene activada | Activarla en la definición del tipo |

**Borrar y reimportar** (no probado): borrar por tipo con WP-CLI y volver a sincronizar. **En producción rompe los enlaces compartidos**, salvo que los slugs salgan iguales (salen iguales mientras el título del feed no cambie).

---

## 12. Plan de pruebas en Local

**Antes:** copia de seguridad de la base de datos. Si un resultado no coincide con lo esperado, no seguir: captura de la pantalla del importador y del episodio.

| # | Prueba | Cómo | Resultado esperado | Estado |
|---|---|---|---|---|
| P0 | Estado | Activar el snippet y abrir la pantalla | Todo ✅ (puede avisar de `pod_*` si aún no existen) | ☑ (Álvaro, v1.0) |
| P1 | Primera carga (San Serafín) | En la entrada del santo de prueba, `hdd_podcast_feed` = `https://anchor.fm/s/111a10f60/podcast/rss`; «Sincronizar solo este» | 36 creados, 0 errores; tabla 36 / 36; 1 temporada | ☑ (Álvaro, v1.0) |
| P1b | Lo creado | Abrir el «Capítulo 36» | Audio con dirección larga de `anchor.fm/…/play/…%3A%2F%2F…`; duración `4:00`; imagen destacada; slug `capitulo-36-de-la-misericordia-y-la-paz`; fecha 22/23 mayo 2026 | 🔲 |
| P1c | Relaciones | Abrir el santo y «Temporada 1» | Santo: 1 temporada y 36 episodios; temporada: 36 episodios; portada = carátula | 🔲 |
| P2 | Segunda sincronización | Pulsar de nuevo | 0 creados, 0 actualizados, 36 sin cambios | 🔲 |
| P3 | Refresco de audio | Cambiar a mano `ep_audio_url` a `x` y sincronizar | Vuelve a su dirección; cuenta como «refrescado» | 🔲 |
| P4 | Reescritura | Vaciar `ep_hash` de un episodio y sincronizar | Se rehace desde el feed; su slug no cambia | 🔲 |
| P5 | Papelera | Mandar un episodio a la papelera y sincronizar | No se recrea; la relación pasa a 35 | 🔲 |
| P6 | Borrador | Pasar uno a borrador y sincronizar | Sigue en borrador; no está en la relación | 🔲 |
| P7 | Borrar y reimportar | Borrar uno definitivamente y sincronizar | Se recrea con el mismo slug | 🔲 |
| P8 | Feed roto | Poner una dirección inventada | Estado «error» con el motivo; nada cambia | 🔲 |
| P9 | Nombre y portada de temporada | Cambiar el título e imagen de «Temporada 1» y sincronizar | Se respetan | 🔲 |
| P10 | Pasadas parciales | Con otro canal de prueba, `'presupuesto' => 0` y sincronizar varias veces | 1 episodio por pasada, «pendiente», al final 36 sin duplicados. **Devolver el valor a 25** | 🔲 |
| P11 | Tarea diaria | Mirar «Próxima sincronización automática» | Mañana a las 04:30 | 🔲 |
| P12 | Avería simulada | Renombrar a mano una casilla del importador | ❌ «Falta la casilla…» | 🔲 |
| P13 | Lectura en Avada | Post Cards con *ACF Relationship* sobre `hdd_episodios` y `hdd_temporadas` | Aparecen las tarjetas | ☑ (Álvaro) |
| P14 *(v1.1)* | Renombrar el canal | Cambiar el slug y el título de la entrada del canal y sincronizar | No se crea ningún episodio nuevo | 🔲 |
| P15 *(v1.1)* | Casillas nuevas | Tras instalar la v1.1 y crear las 4 casillas, sincronizar | `ep_texto`, `ep_temporada_url`, `tmp_canal_url` y `tmp_canal_nombre` rellenas en lo ya importado | 🔲 |

**Qué se probó en la simulación** (118 comprobaciones ✅, con la librería real de lectura de feeds y un feed con la misma estructura que los reales): primera carga de 36 episodios; segunda sincronización con **0 escrituras**; audio rotado; título cambiado con slug intacto; papelera y borrador; feed vacío que no toca nada; dos temporadas, YouTube, colisión de slug, GUID de tipo URL, sin audio, sin números; episodio sin temporada ni número entre vecinos; respeto al nombre y portada de las temporadas; pasadas parciales sin duplicados; mismo feed en dos canales; episodio huérfano adoptado; episodios de la v1.0 adoptados; renombrar el canal sin duplicar; 12 descripciones reales de Charlas para el enlace de YouTube; casillas y direcciones refrescadas; diagnóstico; programación y bloqueo. Las consultas SQL se comprobaron con una base de datos real (SQLite).

**Qué NO se probó** (🔲, lo cierra el Local real): funcionamiento con WordPress y ACF reales; lectura con `fetch_feed` del feed vivo de Spotify (con la versión de SimplePie de tu WordPress, que puede no ser la 1.8.0 de la simulación); descarga real de la carátula; la pantalla de administración (menú, botones, avisos); rendimiento con ~300 episodios; cron real en Raiola.

---

## 13. Previsión de futuro

### 13.1 Si más adelante se quiere el otro camino

*Para no rehacer lo construido.* «Rehace» = obliga a rehacer lo ya hecho.

| Decisión de hoy | Cambio previsto | Cómo se haría | Qué supone | ¿Rehace? |
|---|---|---|---|---|
| D2 (feed en la entrada) | Feed en la etiqueta del canal | Mover ~10 direcciones a una casilla de la etiqueta; el importador lee de otro sitio (una sola función, `tt_pod_descubrir_canales`) | Pocas horas. Solo compensa si hubiera un canal sin página propia | No |
| D3 (Oraciones: O2 u O3) | Pasar de una a otra | Si las entradas cambian de tipo, se vuelven a escribir 4 direcciones de feed y el importador regenera el resto | Pocas horas | No |
| D4 (imagen del episodio enlazada) | Descargar la imagen de cada episodio | El importador la descarga una vez y la pone de imagen destacada; la ficha y la tarjeta leen la destacada | Biblioteca más grande (peso no medido) y primera carga más lenta | No |
| D6 (orden por número) | Orden distinto por canal | Una casilla de orden y dos bloques alternos con Conditional Rendering | Un bloque más por Layout | No |
| D7 (Anterior/Siguiente) | Reproducción automática | JavaScript pequeño **en la página de la temporada**, que carga el siguiente en el mismo reproductor (cambiar de página no es fiable en móvil: los navegadores bloquean la reproducción automática sin un toque) | Trabajo de Proyecto 11; si crece, pasa a Proyecto 2 | No |
| D9 (importador independiente) | Núcleo común con Seminarios, Música y Vía Crucis | Sacar las funciones comunes a un núcleo y dejar un lector por fuente | Refactorización del código; los datos no cambian | No |
| D10 (WP-Cron) | Cron real | Ver 13.2 | Solo infraestructura | No |
| D11 (T1: el feed manda) | Rangos en la web, si no se pudiera corregir Spotify | Dos casillas en la temporada (`tmp_ep_desde`, `tmp_ep_hasta`) y una regla en el importador; esas temporadas las crea una persona | Una segunda fuente de verdad | No (aditivo) |
| D12 (una temporada → lista directa) | Mostrar siempre la tarjeta de temporada | Quitar el sub-bloque «Episodios directos» | Un bloque menos | No (sustractivo) |

**Lo que sí sería caro de cambiar:** convertir la temporada de entrada a etiqueta (o al revés), y renombrar casillas con contenido real.

### 13.2 Cron real

**Hoy (WP-Cron):** WordPress lanza las tareas programadas cuando alguien visita el sitio; con caché de página fuerte, una visita servida desde caché puede no lanzarlas a su hora. Para una sincronización **diaria** el impacto es pequeño y el botón cubre cualquier fallo.

| Escalón | Qué es | Ventajas | Riesgos |
|---|---|---|---|
| **2a** (recomendado como primer paso) | Dejar WP-Cron activo y añadir un «despertador» que llame a `wp-cron.php` cada pocos minutos | Puntualidad real sin apagar nada | Depende de que el servidor permita esa llamada |
| **2b** | Desactivar WP-Cron en `wp-config.php` y dejar solo el cron real | Puntual y sin carga por visita | **Si el cron real no está bien puesto, todas las tareas programadas del sitio se paran sin avisar** |

**Con Raiola (sin verificar, no hay documentación localizada):** ¿ofrece cron en el panel de este plan? ¿permite editar `wp-config.php`? Y sobre todo: ¿bloquea las peticiones del propio sitio a `wp-cron.php`? Se sabe que `/wp-admin/` está bloqueado para el público; en Local no se puede ver, solo en producción. **Comprobación:** programar un evento de prueba con un plugin de inspección de cron (por ejemplo WP Crontrol) y ver si se ejecuta a su hora. **Pasar al cron real no obliga a tocar el importador:** la función de sincronización es la misma para el botón y para la tarea. **No desactivar WP-Cron hasta tener el cron real confirmado.**

---

## 14. Canales: estado y particularidades

| Canal | Entrada | Feed | Estado |
|---|---|---|---|
| San Serafín de Sarov | Santo (HdD) | `https://anchor.fm/s/111a10f60/podcast/rss` | ☑ sincronizado en «TT — Santo de prueba» |
| Santa Teresita | Santo (HdD) | `https://anchor.fm/s/1100f52d8/podcast/rss` (dicho por Carlitos el 8 oct; **Proyecto 2 no lo ha leído**) | 🔲 Su feed incluye la **novena de Santa Teresita** como **temporada 1** |
| Emiliano Tardif, Ana Catalina Emmerick | Santos (HdD) | Sin conseguir | 🔲 |
| Charlas de la Biblia | Entrada propia bajo Tiritaito | `https://anchor.fm/s/1100f326c/podcast/rss` | 🔲 pendiente (abajo) |
| Rincón de Nico | Entrada propia bajo Tiritaito | Sin conseguir | 🔲 |
| Rosario | Oraciones | Sin conseguir | 🔲 **2 temporadas** («Rosario» y «Coronilla de la Divina Misericordia»); contenido fijo |
| Salmos | Oraciones | Sin conseguir | 🔲 **6 temporadas de 25 episodios** (150 salmos, en orden: 1–25, 26–50…); contenido fijo. Será una buena prueba de la cuadrícula de temporadas |
| Adoraciones | Oraciones | Sin conseguir | 🔲 Además lleva un vídeo de YouTube aparte, «Adorando en silencio (hora de la brisa)» |
| Novenas | Oraciones | `https://anchor.fm/s/11070301c/podcast/rss` (dicho por Carlitos el 8 oct; **no leído por Proyecto 2**) | 🔲 Temporadas: Divina Misericordia (1) y Espíritu Santo (2, 9 episodios); cada una es una novena |

**Novenas con podcast:** solo dos (Santa Teresita y Divina Misericordia), y **no tienen página propia**: la novena de Santa Teresita es la temporada 1 del canal del santo, y la de la Divina Misericordia, una temporada del canal «Novenas». Su página es la **página de temporada** del sistema (Sección 4); lo único que hace falta es una tarjeta en el portal de Novenas que enlace a ella. La novena del Espíritu Santo (temporada 2 del canal «Novenas») podría llevar además su audio por día. Ver `ORACIONES_ESTRUCTURA.md`.

**San Serafín (comprobado leyendo el feed el 4 oct):** 36 episodios, todos temporada 1, cada uno con su imagen y su número; ninguna descripción trae enlace de YouTube; las descripciones forman tres bloques (1–20 «Conversaciones con Motovilov», 21–26 «Instrucciones espirituales», 27–36 «Preparación para Pentecostés»), que sugieren cómo etiquetar las temporadas en Spotify. El feed es `episodic` (más nuevo primero), pero el contenido es una serie 1→36.

**Charlas de la Biblia (comprobado leyendo el feed el 10 oct; llegó cortado hasta «18. El primer encuentro de Jacob con el Señor»):**
- Programa activo («Charlas de la Biblia (Hno Tute)»). **3 temporadas, 70 episodios:** 1 Génesis (24), 2 Éxodo a Deuteronomio (42; el 42 es la «Charla especial para vacaciones»), 3 Evangelio de San Juan (4 y creciendo). Los nombres de temporada **no vienen** en el feed. Nombres posibles según los títulos, a confirmar: «Génesis», «Éxodo, Levítico, Números y Deuteronomio», «El Evangelio de San Juan».
- **La numeración reinicia en cada temporada.**
- **Los títulos ya llevan número** («41. Fin del pentateuco…») y en la temporada 1 difieren en 1 de la etiqueta («25. Fin del Génesis» es el episodio 24). Mostrar además `ep_numero` duplica y a veces contradice.
- **Enlace de YouTube** en todas las descripciones que se vieron, como texto plano tras «ver en YouTube:» (a veces dentro de un `<a>`, o «En YouTube:»), de tipo `youtube.com/live/…`. Muchas llevan **antes** enlaces de «Proyecto Biblia» (`watch?v=…`): por eso la v1.1 prioriza el de «ver en YouTube» (probado con 12 descripciones reales). **La línea del enlace se queda también en el texto del episodio** (decisión pendiente).
- Los primeros GUID son direcciones (`https://www.tiritaito.com/blog/?p=16495`).
- **El episodio 41:** en el feed leído, «41. Fin del pentateuco» **sí** trae temporada 2 y número 41. Álvaro había dicho que no traía temporada: o se refería a otro episodio (los 17 primeros no se vieron) o se corrigió después. La v1.1 trata un episodio sin temporada heredando la de sus vecinos por fecha, y sin número ocupando el hueco, siempre con aviso (conviene corregirlo también en Spotify).

**Cómo se monta Charlas** (ver también la Sección 10): 1) instalar la v1.1 y crear las 4 casillas nuevas; 2) crear el grupo «TT — Podcast — Canal» (`pod_feed` URL, `pod_caratula` Imagen, `pod_temporadas` y `pod_episodios` Relación no bidireccional) con la regla de ubicación «Página/Entrada es igual a *Charlas de la Biblia*» (no «todas las páginas»); 3) pegar el feed y «Sincronizar solo este» (esperado: 70 / 70, 3 temporadas); 4) dar nombre y portada a las 3 temporadas; 5) copiar el bloque Podcast de la ficha del santo cambiando `hdd_` por `pod_`; 6) comprobar que se ve la **cuadrícula de 3 temporadas**, que cada ficha muestra el vídeo **de la charla** (no el de «Proyecto Biblia») y que una segunda sincronización da 0 creados y 0 actualizados.

---

## 15. Límites de diseño (para Carlota)

- **No hay barra de progreso ni punto de «novedad» como los de Spotify:** dependen de lo que ha escuchado cada visitante y la arquitectura no guarda estado por persona.
- **No se puede reproducir desde la tarjeta** sin entrar en la ficha (reproducción continua = futuro, 13.1).
- El reproductor es el elemento **Audio de Avada**; su estilo propio es un traspaso a Proyecto 11 (clase `tt-…`).
- **El número de episodios dentro de la tarjeta de temporada** está sin probar (el *Count* se conoce como condición, no se sabe si como texto).
- Con Avada 7.12 o superior, Post Cards admite **tarjetas alternas** (la primera más grande, por ejemplo).
- Probablemente **no haga falta «Cargar más»** (cada página lista una sola temporada); lo decide la temporada más grande de los diez canales.
- Los títulos ya llevan su número: valorar no repetirlo en la tarjeta (hallazgo 16).

---

## 16. Documentos relacionados

`02_REF_PODCAST.md` (podcast de la web vieja, V1) · `HOMBRES_DE_DIOS_ESTRUCTURA.md` (la ficha del santo, donde vive el bloque del podcast) · `CATALOGO_ELEMENTOS_AVADA.md` (elementos nativos) · `apps/v2/tt-podcast-importador.php` (código del importador).

---

*Para la mayor gloria de Dios · tiritaito.com*

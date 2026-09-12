# TIRITAITO.COM — Cuaderno del Constructor
**Lo que Álvaro va descubriendo al construir en Avada — funciona, no funciona, o se
investigó y esto lo resuelve — para que sus 2 cuentas de construcción no tengan que
redescubrirlo cada vez**
*Nace el 1 de septiembre de 2026 — ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 3.3
y el explicador visual (`cuaderno-del-constructor.html`)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Un cuaderno de campo, no un manual. Aquí se anota, sobre la marcha, lo que se descubre al
construir — antes de que llegue a los documentos oficiales, no en vez de ellos.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Qué elemento de Avada usar para una necesidad ya confirmada, con su nivel de certeza | `CATALOGO_ELEMENTOS_AVADA.md` |
| Cómo funciona Avada/Local mecánicamente, ya confirmado y estable | `GUIA_AVADA_LOCAL.md` |
| Una trampa de código ya confirmada (JS, PHP, WordPress) | `00_CORE.md` Sección 8 |
| **Algo recién descubierto, todavía sin pasar en limpio a ninguno de los tres de arriba** | **Este documento** |

## 1. Los tres tipos de anotación

No son pasos de un proceso — cualquiera puede darse en cualquier momento:

| Tipo | Significa |
|---|---|
| ✅ Esto funciona así | Algo que salió bien a la primera. Se anota igual, para no tener que redescubrirlo. |
| ⚠️ Cuidado con esto | Algo que dio problemas, aunque se resolviera solo. |
| 🔲→✅ Se investigó y esto lo resuelve | Vuelve ya resuelto de un documento para Carlitos (Proyecto 3 Sección 0.7 → Proyecto 9). |

## 2. Cómo se usa

1. Cualquiera de las 2 cuentas de construcción de Álvaro (Proyecto 3 o 7), o el Proyecto 9
   (Carlitos), propone una entrada nueva al terminar algo que encaje en uno de los tres
   tipos de arriba.
2. Ninguna cuenta escribe el documento oficial directamente — la entrada se propone aquí,
   y Carlitos (Proyecto 2) decide cuándo ya está madura para trasladarla.
3. Cuando se traslada, se retira de "Anotaciones activas" (Sección 3) y pasa a "Ya
   graduadas" (Sección 4), con el documento de destino anotado — así queda rastro de dónde
   vive ahora.

## 3. Anotaciones activas

---

### 🔲→✅ Se investigó y esto lo resuelve

| # | Hallazgo | Estado | Fecha |
|---|---|---|---|
| 3 | Para un carrusel de verdad (swipe táctil) con tarjetas de contenido libre (imagen+título+texto+botón), sin usar Portfolio: Post Cards Element, Content Source = Pages, Layout = Carousel. Confirmado en documentación oficial que Post Cards admite explícitamente "posts, pages, products, or custom post types" como fuente, y que "Carousel" es uno de sus layouts nativos (junto a Grid, Masonry, Marquee, Coverflow, Stacking Cards, Slider). Descarta la necesidad de reactivar Portfolio (status_fusion_portfolio: "0" en el export, desactivado a propósito el 14 agosto) para conseguir un carrusel deslizable — Post Cards ya lo hace con el mismo motor, sobre páginas normales de WordPress, sin arrastrar la configuración sucia heredada de Portfolio (columnas=1, slug a mano). Corrección importante tras segunda vuelta de investigación: Post Cards NO tiene un selector nativo de "elige estas páginas concretas por nombre" — WordPress no da categorías/etiquetas a las Páginas de forma nativa, a diferencia de los Posts. La vía confirmada sin plugins ni código es: poner Orden (1, 2, 3, 4...) en Atributos de Página de cada página candidata (100% nativo de WordPress, pestaña "Atributos de página"), y en Post Cards configurar Posts By: All + Number Of Posts exacto + Order By: Menu Order + Order: Ascending. ⚠️ Riesgo a vigilar: si se crea una página nueva con un número de Orden bajo, puede colarse en el listado desplazando a una de las páginas buenas — válido mientras el sitio tenga pocas páginas; revisar si el sitio crece mucho. Caso de uso: apartado "Tiritaito" en la home (Rincón de Nico, Music, Charlas de la Biblia, Ejército de Intercesores). | ✅ Confirmado por documentación oficial — pendiente que Álvaro lo pruebe en Local y confirme resultado visual | 11 septiembre 2026 |

---

# Anotaciones para CUADERNO_DEL_CONSTRUCTOR.md — 2 de septiembre de 2026
Caso: Novedades no se veía en Post Cards — sesión de diagnóstico y cierre

---

## Para la sección "🔲→✅ Se investigado y esto lo resuelve"

| # | Hallazgo | Estado | Fecha |
|---|---|---|---|
| 2 | Novedades no se veía en Post Cards — dos causas distintas, ambas resueltas: (1) el CPT novedades (public => false) no aparecía en ningún selector de tipo de contenido de Avada (Post Type de Post Cards, View Dynamic Data As). Fix: añadir show_in_nav_menus => true al register_post_type(), sin tocar public, sin abrir páginas públicas nuevas. (2) Tras la reconstrucción del Local (26 julio), el grupo ACF de Novedades se recreó con un campo imagen (tipo Imagen) en vez de tipo + media_url (tipo Texto) que el endpoint PHP real seguía usando. El dato nunca se perdió — solo era invisible para Avada. Fix: declarar tipo y media_url en ACF con esos nombres exactos, tipo Texto; los datos ya existentes se "adoptan" solos. Las 2 novedades sin ese dato (creadas antes del incidente) se rellenaron a mano con wp post meta update, usando wp_get_attachment_url() sobre el ID del campo viejo imagen. | ✅ Confirmado — las 4 novedades ya muestran imagen con el molde real | 2 septiembre 2026 |

### "✅ Esto funciona así"

| # | Hallazgo | Contexto | Fecha |
|---|---|---|---|
| 1 | Patrón App → ACF → Avada, verificado de extremo a extremo. Cuando una app externa escribe en un CPT vía update_field() y Avada lo pinta con Post Cards + Dynamic Content, hay 3 capas a comprobar por separado — el síntoma ("se ve en blanco") es igual para las tres, pero el arreglo es distinto: (1) ¿El CPT es visible para Avada? (selectores Post Type / View Dynamic Data As → public/show_in_nav_menus). (2) ¿ACF tiene los campos con el nombre exacto del backend? (verificar con wp post meta list <id> contra un registro real, no fiarse del panel a simple vista). (3) ¿El molde de Avada apunta al campo correcto, con la condición correcta? (revisar Contenido Dinámico elemento por elemento, sobre todo tras reconstrucciones de entorno). | Descubierto arreglando Novedades — aplicable a cualquier CPT futuro (Hombres de Dios, etc.) | 2 septiembre 2026 |

### ⚠️ Cuidado con esto

| # | Hallazgo | Contexto | Fecha |
|---|---|---|---|
| 1 | No se pudo confirmar el camino del vídeo en Novedades de extremo a extremo — al intentar subir una novedad de prueba con tipo=video desde la app, la subida falló. Sin diagnosticar todavía: puede ser el mismo elemento Vídeo "huérfano" en el molde de Avada (mismo patrón que Imagen, ver entrada #2 de arriba), o puede ser un fallo distinto en la app/endpoint al crear el registro. No dar el soporte de vídeo por confirmado hasta que esto se investigue. | Sesión de cierre del arreglo de Novedades | 2 septiembre 2026 |

### 🔲→✅ Se investigó y esto lo resuelve


| # | Hallazgo | Estado | Fecha |
|---|---|---|---|
| — | *(vacío por ahora)* | 🔲 Pendiente | Sin fecha confirmada | 

---

### ✅ Esto funciona así

| # | Hallazgo | Contexto | Fecha |
|---|---|---|---|
| — | *(vacío por ahora)* | | |

### ⚠️ Cuidado con esto

| # | Hallazgo | Contexto | Fecha |
|---|---|---|---|
| — | *(vacío por ahora)* | | |

### 🔲→✅ Se investigó y esto lo resuelve

| # | Hallazgo | Estado | Fecha |
|---|---|---|---|
| 1 | El CPT `novedades` podría no aparecer en el selector "Post Type" de Post Cards en Avada por faltar `'publicly_queryable' => true` en el `register_post_type()` (junto a `public => false`). Diagnóstico de una sesión anterior del Proyecto de Investigación, sin verificar todavía contra el PHP real actual. | 🔲 Pendiente de que Álvaro lo pruebe en Local y confirme el resultado — NO aplicar el fix en el PHP oficial hasta esa confirmación | Sin fecha confirmada |

## 4. Ya graduadas — trasladadas a documentación oficial

*(Ejemplos de referencia, con hallazgos reales ya confirmados en el proyecto, para que se
vea el patrón — no hace falta anotar estos de nuevo, ya viven en su documento de destino.)*

| # | Hallazgo | Documento destino | Fecha |
|---|---|---|---|
| 1 | Escribir un campo de una ACF Options Page con `update_option()` en vez de `update_field()` lo deja invisible para Avada Dynamic Content — hay que usar siempre `update_field()`. | `00_CORE.md` Sección 8 | 23 julio 2026 |
| 2 | "Slideshows" (Avada → Options) solo controla varias imágenes DENTRO de una misma entrada — no rota entre entradas distintas. Para eso hace falta Post Slider. | `GUIA_AVADA_LOCAL.md` Sección 9 / `CATALOGO_ELEMENTOS_AVADA.md` Sección 4 | 11 agosto 2026 |
| 3 | Los iframes de YouTube construidos dinámicamente por JS necesitan el atributo `referrerpolicy`, o falla la reproducción (Error 153), desde que YouTube exige un header Referer válido. | `00_CORE.md` Sección 8 | — |

## 5. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Confirmar la entrada #1 de la Sección 3 (`publicly_queryable`) contra Local real — es el
   primer caso activo real de este cuaderno.
2. Según se use en la práctica, revisar si el formato de tabla es cómodo para las cuentas
   de Álvaro, o conviene simplificarlo.

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Con qué frecuencia debería Carlitos revisar este cuaderno para reconciliar entradas maduras? | Sin cadencia fijada todavía — se decide con el uso real |

---

*Para la mayor gloria de Dios · tiritaito.com*

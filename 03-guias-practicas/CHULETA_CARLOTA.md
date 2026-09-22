# TIRITAITO.COM — Chuleta rápida de bocetos (Carlota)
**Referencia compacta para las cuentas de bocetos — Proyecto 4 y Proyecto 6**
*Destilada de `00_CORE.md`, `GUIA_AVADA_LOCAL.md` y `CATALOGO_ELEMENTOS_AVADA.md` — 22 de septiembre de 2026, a raíz del estudio de consumo de uso de Claude*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es esto

Esta chuleta sustituye, en tus cuentas de bocetos, a `GUIA_AVADA_LOCAL.md` + `CATALOGO_ELEMENTOS_AVADA.md` + `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` + `ARQUITECTURA_Y_ROADMAP.md` + `METODOLOGIA_CONSTRUCCION.md` + el export completo de Avada. Contiene solo lo que hace falta para decidir un boceto y saber qué es construible en Avada sin código. **`ALCANCE_WEB_NUEVA.md` sigue como documento aparte en tu proyecto** — ese sí lo necesitas completo, para saber qué se está diseñando y con qué prioridad.

No lleva nada de backend (endpoints, PHP, `wp_options`) — eso no te hace falta para dibujar.

Certeza tal como está en el documento de origen: ✅ confirmado · ⚠️ con un matiz sin cerrar · 🔲 sin confirmar · ❌ descartado.

---

## 1. Identidad visual — la paleta y tipografía reales

```css
:root {
  --tt-red:      #BF4646;  --tt-red-d:    #A33B3B;  --tt-red-bg:   #FDF2F2;
  --tt-txt:      #1d1d1f;  --tt-txt2:     #3a3a3c;  --tt-txt3:     #6e6e73;
  --tt-txt4:     #86868b;  --tt-sep:      #c7c7cc;
  --tt-bg:       #FFFFFF;  --tt-surf:     #FFFFFF;  --tt-surf2:    #F5F5F7;  --tt-surf3: #EBEBF0;
  --tt-green:    #34C759;  --tt-green-bg: #F0FAF4;  --tt-orange:   #FF9500;  --tt-alert: #FF3B30;
  --tt-r:        25px;     --tt-r-sm:     14px;     --tt-r-xs:     8px;
}
```

**Fondo siempre blanco puro — nunca modo oscuro.** Es identidad de marca, no una opción de tema.

**Tipografía:** "Yeah Papa" en títulos, "Helvetica Neue" en cuerpo. ⚠️ "Yeah Papa" necesita un tamaño en px notablemente mayor que Helvetica Neue para el mismo peso visual — confirmado varias veces (ej.: un título se ajustó de 16px a 30px para verse con el mismo peso). Ten esto en cuenta al dibujar títulos: lo que "se ve bien" en px de una tipografía normal se queda pequeño en Yeah Papa.

**Geometría:** 25px es la firma visual de Tiritaito (cards, botones, contenedores grandes). 14px para inputs e items secundarios. 8px para badges e iconos pequeños. ⚠️ **10px** apareció en Toggles y en Forms, decisión tuya, pero sin confirmar todavía si es un cuarto token real o queda solo ahí — no lo generalices a otros elementos en un boceto nuevo sin que se confirme antes.

---

## 2. Valores reales de Avada Global Options — no diseñes "a ciegas"

*Fuente: `avada-global-options.json` (última actualización conocida: 14 agosto 2026). El boceto tiene que parecerse a la web real, no a lo que "en teoría" debería verse.*

| Ajuste | Valor real |
|---|---|
| Ancho del sitio | Wide, **1200px** |
| Breakpoints | ~**1024px** (tablet/Medium) y **480px** (móvil/Small) |
| El texto se reduce solo en pantallas pequeñas | Sí — Sensitivity 0.30 |
| Radio de botón | El export dice **10px** en las 4 esquinas — ⚠️ contradice la nota de que se corrigió a 25px. No está resuelto cuál manda; si dibujas botones, pregunta antes de fijarlo en el boceto |
| Lightbox (clic para ampliar imagen/vídeo) | Fondo Metro White, opacidad 0.90, con flechas |
| Portfolio | ❌ Desactivado — no lo cuentes como recurso disponible |
| Colores 9–13 (`--tt-red-bg`, `--tt-txt3`, `--tt-green`, `--tt-orange`, `--tt-alert`) | 🔲 Todavía sin cargar en el panel de Avada — puedes usarlos en el boceto (son parte de la marca), pero avisa de que hay que cargarlos antes de que Álvaro pueda usarlos como color de Avada |

---

## 3. Regla de oro — nunca dibujes pensando en la Clase CSS

**Desde el 6 de septiembre de 2026, el equipo no usa el campo "Clase CSS" de ningún elemento, columna o container.** Antes de dar un efecto por imposible en un boceto, comprueba si ya es nativo en Avada:

| Quiero este efecto... | Es nativo en Avada, así |
|---|---|
| Bordes redondeados, sombra | Container/Columna → pestaña Diseño → Border Radius, Box Shadow |
| Degradado de fondo | Container/Columna → pestaña Fondo → Degradado |
| Cristal esmerilado / desenfoque de lo que hay detrás | Container/Columna → pestaña Extras → Filtros de Fondo — 🔲 hay que confirmar en Local que esta función ya está disponible antes de prometerlo en un boceto |
| Degradado de color EN el texto | Elemento Título (Gradient Font Color) o elemento Highlight — **no** el Bloque de Texto normal |
| Texto con sombra, contorno, o animado (aparece al hacer scroll, rota entre frases) | Elemento Título, con sus distintos "tipos" |
| 10 efectos distintos de hover en un botón | Ya vienen de fábrica en el elemento Botón — no hay que inventar ninguno |
| Un color distinto solo al pasar el ratón | Casi cualquier selector de color de Avada tiene un círculo pequeño al lado que activa el estado "Hover" |
| Alineación flexible de columnas (no en fila simple) | Container → pestaña General → Row/Column Alignment (Flexbox nativo) |
| Espaciado distinto en móvil/tablet/escritorio | El icono "Responsive" existe en Container, Columna, Botón, Imagen, Bloque de texto y Título |

**Lo que probablemente sí sigue necesitando código** (para que no prometas algo que luego no se puede construir): recortar una imagen en una forma libre no rectangular ("blob"), un cursor personalizado, un punto de navegación de carrusel que se alarga cuando está activo (confirmado que Post Cards no lo tiene, ni por tamaño ni por forma — solo por color).

Si un efecto no aparece aquí: prueba varias combinaciones nativas antes de descartarlo (mira TODAS las pestañas del elemento, no solo "Diseño"). Solo si de verdad no está, dilo con franqueza dentro del propio boceto — eso se lleva a Carlitos, nunca se resuelve con Clase CSS ni se calla en silencio.

---

## 4. Qué elemento de Avada usar según lo que quieres mostrar

| Necesito mostrar... | Elemento de Avada |
|---|---|
| Una rejilla de tarjetas con contenido dinámico (ej. Novedades) | **Post Cards** — admite filtros, ordena por campo personalizado |
| Rotar entre varias entradas distintas (ej. los 9 santos de Hombres de Dios) | **Post Slider** — no "Slideshows" (eso solo controla varias imágenes DENTRO de una misma entrada) |
| Un acordeón tipo "¿Qué es X?" | **Toggles** |
| Tarjetas que giran (imagen por un lado, texto por otro) | **Flip Boxes** — aunque el equipo prefiere ir migrando esto a Column + Title + Text Block a medio plazo |
| Ampliar una imagen o vídeo al hacer clic, sin salir de la página | **Lightbox Element**, o `Link Target = Lightbox` en una Columna |
| Una ventana emergente con contenido libre (no solo imagen/vídeo) | **Modal Element** |
| Un carrusel de imágenes | **Image Carousel Element**; si mezcla vídeo, **Avada Slider Element** |
| Un menú lateral para móvil | **Off Canvas Builder** (el Flyout clásico es legacy) — sin submenús desplegables resueltos de forma nativa |
| Testimonios | **Testimonials** |
| Contadores numéricos | **Counter Boxes** |
| Un índice de secciones al principio de una página larga | **Menu Anchor Element**, o Table of Contents Element |

---

## 5. Criterios de diseño ya decididos — no los repitas de cero en cada boceto

- **Ninguna sección debe ocupar la pantalla completa por defecto** — ni en la home ni en el resto de páginas — salvo que haya una razón explícita para ello. Cada sección ocupa lo que su contenido necesita.
- **Unidad dentro de la diversidad:** cuando una página agrupa contenido de naturaleza distinta (ej. Tiritaito: Music, Rincón de Nico, Charlas de la Biblia, Ejército de Intercesores), cada pieza puede tener tono propio, pero el patrón de presentación debe sentirse igual.
- **Siempre dos vistas mínimo en el boceto:** escritorio y móvil.
- Genera el boceto como archivo HTML autocontenido con la paleta real — nunca el modo rápido de visualización del chat (tiene su propio sistema de diseño, con tipografías limitadas y modo oscuro automático, y no representa la marca real).

---

## 6. Cómo trabajar de forma eficiente en esta cuenta

- **Un chat por boceto o por idea.** Cada vuelta de un hilo reenvía todo el HTML generado hasta ese momento — un hilo que acumula muchas versiones se vuelve muy pesado muy rápido.
- **Esfuerzo (junto al modelo): Medio para las primeras rondas de exploración**, cuando lo que hace falta es ver 2-3 direcciones distintas, no perfección. Sube a Alto solo para pulir la dirección ya elegida — no hace falta más exactitud de la que el boceto necesita en cada fase.
- Si algo de este documento no basta para resolver una duda de si algo es nativo en Avada, dilo — no lo inventes ni lo dejes en el aire.

---

*Para la mayor gloria de Dios · tiritaito.com*

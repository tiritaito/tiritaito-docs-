# TIRITAITO.COM — Tipografía en Avada
**Cómo funciona, qué hacer en cada caso y guía de configuración (una sola vez)**
*Versión 1.1 · 23 de octubre de 2026 · **tras la prueba de Álvaro y la decisión de fuente (Poppins)** (resultados y problemas abiertos en la Parte 4) · Verificado contra documentación oficial de avada.com (21-22 octubre 2026), contra el informe de Álvaro del 23 de octubre y contra `GUIA_AVADA_LOCAL.md`, `CATALOGO_ELEMENTOS_AVADA.md` y `00_CORE.md`*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde cinco preguntas, para tres públicos:

| Pregunta | Para quién | Dónde |
|---|---|---|
| ¿De dónde sale el tamaño de una letra en la web nueva y por qué? | **Todo el equipo** | Parte 1 |
| Un texto se ve demasiado grande o pequeño: ¿qué hago? | **Todo el equipo** | Parte 2 |
| ¿Cómo dejamos toda la tipografía bien configurada, una vez? | **Álvaro** | Parte 3 |
| ¿Qué salió de la prueba y qué queda por arreglar? | **Todo el equipo** | Parte 4 |
| ¿Cómo toco la tipografía a partir de ahora? | **Álvaro** | Parte 5 |

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Exportar y sanear Global Options (antes y después de configurar) | `GUIA_AVADA_LOCAL.md` Sección 2.2 |
| Paleta, radios, convenciones de código | `00_CORE.md` |
| Qué elemento de Avada resuelve una necesidad de contenido | `CATALOGO_ELEMENTOS_AVADA.md` |
| **De dónde sale el tamaño de una letra y cómo se configura** | **Este documento** |

**Marcadores:** ✅ confirmado en documentación oficial de Avada · ⚠️ fuente no oficial, o con un matiz · 🔲 hay que probarlo en Local · ❌ confirmado que no.

---

# PARTE 1 — Cómo funciona (para todo el equipo)

## 1.1 La idea, en tres frases

1. El **tipo de letra y su tamaño se deciden en un solo sitio**: el global (Avada → Options → Typography).
2. Los elementos (títulos, textos, botones...) **por defecto heredan** del global. En un caso concreto se puede cambiar el tamaño, la fuente o el color de ese elemento sin problema; lo único que conviene evitar es un **número fijo** de tamaño, porque es lo que se ve grande en móvil.
3. El tamaño es **fluido**: cada texto tiene un mínimo (para móvil) y un máximo (para escritorio), y entre medias crece solo según el ancho de la pantalla. Ya no hay un "salto" entre móvil y escritorio.

## 1.2 Los cinco roles de letra

✅ Avada trae **cinco plantillas de letra** ya creadas, llamadas *Global Typography sets*: Headings, Subheadings, Lead, Body y Small. Cada una guarda la fuente, su tamaño, el interlineado y el espaciado. Todo lo demás (los H1-H6, el cuerpo, los elementos) puede *tirar* de una de ellas.

| Rol (set) | Para qué lo usamos | Fuente | Tamaño móvil → escritorio |
|---|---|---|---|
| **Headings** | Títulos H1-H6 y títulos de página | Yeah Papa | Cada nivel el suyo (Anexo A) |
| **Subheadings** | Títulos de elementos: Toggles, tarjetas | Yeah Papa | 22 → 30 px |
| **Lead** | Entradilla: el párrafo introductorio, algo mayor | Poppins | 18 → 22 px |
| **Body** | Texto normal | Poppins | 16 → 18 px |
| **Small** | Fechas, pies de foto, etiquetas | Poppins | 13 → 14 px |

⚠️ Los **nombres y el orden** son los de Avada. Los **usos y los tamaños son una propuesta nuestra**, pendiente de que Hna C la valide mirándola en pantalla.

✅ **Decisión de Carlitos (23 oct 2026):** el texto (Lead, Body, Small) va en **Poppins** y los títulos (Headings, Subheadings) en **Yeah Papa**. Es la norma de la **web nueva en Avada**; la web vieja y la app siguen como estaban (Parte 4.3).

✅ Carlitos ha visto el resultado con Poppins y queda bien. Si algún día algo se ve grande o pequeño, se recalcula con la 5.4.

## 1.3 El esquema

```
        LOS 5 SETS   (aquí viven la fuente y el tamaño)
   Headings · Subheadings · Lead · Body · Small
        │
        ├──► Heading Typography  H1 … H6   (set Headings + su tamaño propio por nivel)
        ├──► Body Typography               (set Body)
        └──► Campos de los elementos       (Title, Text Block, Button, Toggles…)
                 └─ Lo normal: vacíos o vinculados a un set.

   ISLAS (tienen su propio campo y NO siguen a H1-H6 ni a Body en tamaño)
   Page Title Bar · Menú (principal y móvil) · Sliders de Avada · Formularios
```

## 1.4 Cómo decide Avada el tamaño de un texto

Avada mira, **de más fuerte a más débil**, y se queda con el primer valor que encuentra:

| # | Dónde mira | Qué dice la documentación |
|---|---|---|
| 1 | **Formato en línea** (en Avada Live, texto seleccionado con otro tamaño o fuente) | ✅ Las ediciones en línea prevalecen sobre las opciones del elemento. Es una fuente oculta de tamaños fijos |
| 2 | **Campo Typography del propio elemento** | ✅ En el elemento Título, si el campo está vacío, usa la tipografía global del nivel H1-H6 que corresponda |
| 3 | **Valores por defecto del elemento** (Avada → Options → Avada Builder Elements → [elemento]) | ✅ La opción "Default" de un elemento usa lo asignado ahí |
| 4 | **Typography global**: Heading Typography (H1-H6) y Body Typography | ✅ Body suele tomar sus valores del set Body; los H1-H6 pueden tirar del set Headings y cambiar solo su tamaño |
| 5 | **Los 5 sets** | ✅ Se aplican enteros o en parte. **No se enlazan solos: hay que seleccionarlos** |

✅ **Cómo ver qué viene de un set:** en el panel, un campo en **gris** tiene su valor del set; uno en **blanco** es propio de ese sitio. Al pasar el ratón por un campo aparece un pequeño globo y se ve de qué set viene.

⚠️ **"Vacío" y "Default" no siempre son lo mismo.** En algunos elementos, dejar el campo vacío da solo la *familia* global, y "default" da lo que diga Global Options. Hay que mirar cada elemento (lo hace Álvaro en el Paso 1).

## 1.5 Las islas

Piezas con su **propio** campo de tamaño. Lo que ponemos en el global no las alcanza.

| Isla | Dónde se configura | Qué hacemos |
|---|---|---|
| **Page Title Bar** (título de página) | Avada → Options → Page Title Bar → Heading Font Size | Valor propio fluido (32 → 54 px) |
| **Toggles** (título del desplegable) | Avada → Options → Avada Builder Elements → Toggles | Vincular al set Subheadings (o valor propio fluido 22 → 30) |
| **Menú principal y menú móvil** | Elemento Menú del Header / Off-Canvas | ✅ Vacío = solo la *familia* global; el tamaño es propio. Se revisa, no se cambia |
| **Sliders de Avada** | Dentro de cada slider | ✅ Su tipografía responsive es independiente (hoy no usamos) |
| **Formularios** | Options → Forms | 16 px fijos a propósito (evita el zoom automático de Safari en iPhone). **No tocar** |

Cualquier otra pieza con tamaño propio **es una excepción y se anota en el registro de la Parte 6**.

## 1.6 Qué significa "fluido", sin jerga

Escribimos cada tamaño como una **fórmula con tres números**: *mínimo, ideal, máximo*.

> Ejemplo, el H1: `clamp(34px, 26.25px + 2.07vw, 56px)`
> "Nunca más pequeño de 34, nunca más grande de 56, y entre medias crece solo con el ancho."

Es como un acordeón con tope: en un móvil (375 px) el H1 mide 34 px; en una tablet (768 px), unos 42; en un portátil o pantalla grande (1440 px), 56. No hace falta escribir nada distinto para cada pantalla. ✅ Avada admite estas fórmulas en sus campos de tipografía, incluidos los sets. Se escriben **sin** `font-size:` delante.

⚠️ La documentación de `clamp()` ya habla de "Avada One"; nosotros usamos Avada Classic (7.15/7.16). **Por eso la prueba del Paso 2.**

## 1.7 Seis criterios de trabajo (flexibles)

Son para trabajar con orden, **no normas rígidas**: en un caso concreto se puede cambiar algo en ese elemento y no pasa nada. Lo que sí importa de verdad es que **el global esté bien** y no arreglar un problema global elemento a elemento.

1. **Fuente y tamaño viven solo en los 5 sets, en H1-H6 y en Body.** En ningún otro sitio.
2. **Lo normal: el campo de Typography del elemento va vacío o vinculado a un set.** Si un caso concreto necesita su propio tamaño (un hero, por ejemplo), se le pone, mejor como fórmula fluida que como número fijo. No hace falta anotarlo.
3. **Para cambiar la importancia de un título, se cambia su nivel (H1-H6), no su tamaño.**
4. **Mejor evitar tamaños y fuentes en línea** en Avada Live (al seleccionar texto y cambiarle el tamaño): luego son difíciles de encontrar.
5. **En CSS propio (Proyecto 11) no se fija `font-size` en px:** se hereda del elemento o se usa una variable de un set (ver Paso 2, prueba 3).
6. **El color por defecto del texto vive en el global** (Texto secundario, Color 7, `#3a3a3c`). En un elemento solo se cambia el color si hay un motivo concreto (por ejemplo, texto blanco sobre una imagen oscura). No se pone color a todos los textos uno por uno.

---

# PARTE 2 — Qué hacer en cada caso

## 2.1 Tabla rápida

| Veo... | Probablemente es... | Haz esto |
|---|---|---|
| Un **título** enorme en móvil | Tamaño propio en el elemento, o formato en línea | 1) Selecciona el elemento y **vacía** su campo Typography. 2) Si sigue, abre la vista **Texto** y quita cualquier `style="font-size…"`. 3) Si sigue, mira si el nivel (H1-H6) es el adecuado (regla 3) |
| **Todos los H2** (o todos los párrafos) se ven pequeños o grandes | La escala global | **No lo cambies en cada elemento.** Avisa: se ajusta una vez en Heading Typography o en el set |
| Un **párrafo** se ve distinto al resto | Tamaño propio o formato en línea | Igual que el primer caso. El texto normal debe ser el set **Body** |
| Quiero una **entradilla** grande | — | En el campo Typography del Text Block, elige el set **Lead** |
| Quiero un texto **pequeño** (fecha, pie de foto) | — | Elige el set **Small** |
| Quiero un **título de tarjeta o de desplegable** | — | Elige el set **Subheadings** |
| Quiero un **título destacado distinto** (hero) | Caso concreto, permitido | Ponle su propio tamaño a ese elemento, mejor con fórmula fluida que con número fijo. No hace falta anotarlo |
| Un **elemento nuevo** que añado | — | Deja su campo Typography **vacío**. Si ofrece elegir un set, elige el que corresponda a su rol |
| El **menú**, la **barra de título** o un **Toggle** se ven mal | Isla | Su propio campo (tabla 1.5) |
| Nada de lo anterior lo explica | Puede ser CSS de mejora | **Traspaso a Proyecto 11** (formato en tu chuleta), con la captura del Inspeccionar |
| Quiero **cambiar la escala** de toda la web | Decisión de equipo | Nunca desde un elemento. Se ajusta en el global, con Carlitos, y se exporta y sanea antes y después |

## 2.2 Cómo comprobar de dónde sale un tamaño (2 minutos)

1. Abre la página **en el navegador** (no en Avada Live) y ajusta el ancho a 375 px.
2. Clic derecho sobre el texto → **Inspeccionar**.
3. Pestaña **Computed** → mira `font-size`: es el tamaño real en ese momento.
4. Pestaña **Styles**: la primera regla que fija `font-size` es la que manda.
   - Si está en `style="…"` dentro del elemento → es **formato en línea** o un campo propio del elemento.
   - Si pone `.fusion-title` / `h2` y viene del global → la escala global.
   - Si es una regla de nuestro CSS → traspaso a Proyecto 11.

---

# PARTE 3 — Guía de configuración para Álvaro

**Quién:** Álvaro, en su Local · **Cuándo:** una sola vez · **Cuánto:** unas 2-3 horas · **Cómo:** abre un chat nuevo en tu cuenta de construcción (Proyecto 3, 7 o 9), **adjunta este documento** y pide ayuda paso a paso si te atascas.

**Regla de oro de esta guía:** vas **anotando cada resultado**. Hay una prueba al principio (Paso 2) cuyo resultado decide cómo sigues. Si algo no sale como dice la guía, **para y avisa**: no improvises.

## Antes de empezar (10 minutos)

1. **Guarda un Blueprint de Local** (menú del sitio → *Save as Blueprint*). Es tu marcha atrás.
2. **Exporta y sanea Global Options "antes"** siguiendo `GUIA_AVADA_LOCAL.md` Sección 2.2. Guarda el JSON limpio en tu ordenador (**no se sube al repositorio**). Nunca pegues un export sin sanear en ningún chat.
3. **Crea una página privada "Prueba tipografía"** con, en este orden: un Título por cada nivel H1 a H6, un Text Block normal, un Text Block con el set **Lead** (cuando exista), uno con el set **Small**, un Toggle y un Botón. La usarás en el Paso 5.

## Paso 1 — Leer lo que hay (30 minutos)

Rellena esta tabla (cópiala a un documento tuyo). **No cambies nada todavía.** Rutas dentro de Avada → Options, salvo que se indique.

| Superficie | Dónde | Valor hoy | ¿Gris (viene de un set) o blanco (propio)? |
|---|---|---|---|
| Set Headings | Typography → Global Typography | fuente · tamaño · interlineado | — |
| Set Subheadings | ídem | | |
| Set Lead | ídem | | |
| Set Body | ídem | | |
| Set Small | ídem | | |
| Body | Typography → Body Typography | | |
| H1, H2, H3, H4, H5, H6 | Typography → Heading Typography | (6 filas) | |
| Título de entrada | Typography → Heading Typography (apartado de títulos de entrada) | | |
| Page Title Bar | Page Title Bar → Heading Font Size | | |
| Defaults de **Title**, **Text Block**, **Button**, **Toggles**, **Testimonials** | Avada Builder Elements → [elemento] | ¿campo vacío, set o px? | |
| Menú principal y móvil | Elemento Menú del Header | solo leer | |
| Sensibilidad y Factor | Responsive → Responsive Typography | (0.30 y 1.50 en el export del 14 agosto) | |

Dos cosas a mirar con atención:

- **¿Qué fuente y qué tamaño tienen hoy los 5 sets?** El Setup Wizard solo admitía un esquema de tipografía prediseñado, que se usó "como placeholder" (`GUIA_AVADA_LOCAL.md` §4.0.1). Es posible que los sets sigan con esa fuente de relleno. 🔲 No lo hemos visto: es una hipótesis, y por eso se mira.
- **Las fuentes**: el export usaba tres grafías (`Yeah-Papa` / `YEAH-PAPA`, `Helvetica Neue` / `Helvetica Nueva`). Antes de elegir fuente en un set, mira **qué nombre usa hoy el campo de H1** y usa esa misma entrada de la lista.

## Paso 2 — Tres pruebas (20 minutos) · ⚠️ decide cómo sigues

Anota el resultado de cada una.

| Prueba | Qué hacer | Resultado esperado | Anota |
|---|---|---|---|
| **P1 — ¿el H1 admite la fórmula?** | Typography → Heading Typography → H1 → Font Size: pega `clamp(34px, 26.25px + 2.07vw, 56px)` (**sin** escribir `font-size:`). Guarda. Abre la página de pruebas en el navegador, ancho 375 px → Inspeccionar el H1 → Computed → `font-size`. Repite a 1440 px | 34 px a 375 px, y 56 px a 1440 px | ✅ lo acepta y mide eso · ⚠️ lo acepta pero mide otra cosa (di cuánto) · ❌ no lo acepta (error, vuelve al valor anterior o lo guarda vacío) |
| **P2 — ¿un set admite la fórmula?** | Global Typography → set **Lead** → Font Size: pega `clamp(18px, 16.59px + 0.38vw, 22px)`. Guarda | Se guarda y se queda | ✅ / ❌ |
| **P3 — ¿hay variables CSS de los sets?** | En el navegador: Inspeccionar → selecciona la etiqueta `<html>` → panel Styles → filtra por `--awb-typography` | Aparecen variables tipo `--awb-typography1-font-family` ⚠️ (nombre según una fuente no oficial) | Copia los nombres que veas, o "no aparece nada" |

### Qué hacer según el resultado

| P1 | P2 | Qué hacer |
|---|---|---|
| ✅ | ✅ | **Ruta A:** sigue con el Paso 3 tal cual |
| ✅ | ❌ | **Ruta B:** los sets guardan solo **fuente** (no tamaño); el tamaño fluido va **directo** en H1-H6 y Body. Anótalo y sigue |
| ⚠️ (mide distinto) | — | Pon la sensibilidad (Responsive → Responsive Typography) en **0**, vuelve a medir el H1. Si ahora da 34 px, sigue. Si no, **avisa** |
| ❌ | — | **PARA y avisa a Carlitos.** No se improvisa: hay un plan alternativo (CSS maestro, vía Proyectos 11 y 2) |

## Paso 3 — Configurar (en este orden)

⚠️ Los valores salen del **Anexo A**. Son una **propuesta** que Hna C debe validar a ojo en el Paso 5; si algo no convence, se ajusta ahí, no por cada elemento.

**No toques** (déjalo como esté): interlineado, espaciado de letras, peso (variante), mayúsculas, colores, formularios, menú. Esta tarea cambia **solo fuente y tamaño**. Si ves un interlineado raro en un set (menos de 1,1 en títulos o más de 1,8 en cuerpo), anótalo en el informe, no lo cambies.

### 3.1 Los cinco sets (Typography → Global Typography)

| Set | Fuente | Fuente de respaldo | Tamaño |
|---|---|---|---|
| Headings | Yeah Papa | Helvetica, Arial, sans-serif | el del H2 (Anexo A) — cada nivel lo cambia luego |
| Subheadings | Yeah Papa | Helvetica, Arial, sans-serif | `clamp(22px, 19.18px + 0.75vw, 30px)` |
| Lead | Poppins *(actualizado 23 oct)* | Helvetica, Arial, sans-serif | `clamp(18px, 16.59px + 0.38vw, 22px)` |
| Body | Poppins *(actualizado 23 oct)* | Helvetica, Arial, sans-serif | `clamp(16px, 15.30px + 0.19vw, 18px)` |
| Small | Poppins *(actualizado 23 oct)* | Helvetica, Arial, sans-serif | `clamp(13px, 12.65px + 0.09vw, 14px)` |

*(Si la Ruta es B, deja el tamaño de los sets como esté y pon solo la fuente.)*

### 3.2 Body (Typography → Body Typography)
Vincúlalo al set **Body** (selector de set: icono de globo). Los campos pasan a gris. No toques el color ni la decoración de enlaces.

### 3.3 Headings H1 a H6 (Typography → Heading Typography)
Para **cada nivel**: vincula al set **Headings** (campo en gris) y **solo el tamaño** lo escribes tú en ese campo, con la fórmula del Anexo A. Quedará el tamaño en blanco (propio) y lo demás en gris. Es justo lo que la documentación de Avada describe.

### 3.4 Título de entrada
En el mismo panel, apartado de títulos de entrada: `clamp(36px, 21.21px + 3.94vw, 78px)`.

### 3.5 Islas
- **Page Title Bar** → Heading Font Size: `clamp(32px, 24.25px + 2.07vw, 54px)`. 🔲 Si el campo solo admite un número, déjalo como está y **anótalo**: se resolverá con otra vía.
- **Toggles** → Avada Builder Elements → Toggles → tipografía del título: vincula al set **Subheadings**. Si el campo no ofrece sets, pon `clamp(22px, 19.18px + 0.75vw, 30px)`.
- **Menú**: solo comprobar que el tamaño en móvil es cómodo de leer. Si algo se ve mal, **anótalo**, no lo cambies.

### 3.6 Defaults de los elementos que usamos
Avada → Options → Avada Builder Elements → **Title, Text Block, Button, Toggles, Testimonials**: el campo de tipografía debe estar **vacío o vinculado a un set** (regla 2). Si encuentras un px suelto: anótalo y, si es claramente un resto de plantilla, vacíalo.

### 3.7 Sensibilidad (Responsive → Responsive Typography)
Una vez todo lo anterior hecho y comprobado: **Responsive Typography Sensitivity = 0**. Así solo manda una fuente de verdad. Consecuencia que debes conocer: cualquier texto que siga con un px fijo (una isla anotada) ya no se reducirá solo en móvil. Por eso va **al final**.

### 3.8 Cachés
**Reset Avada Caches** (Avada → Maintenance), purgar la caché del navegador y recargar sin caché.

## Paso 4 — Limpiar las páginas ya construidas

Páginas y Layouts ya hechos (home, Novedades, Hombres de Dios, Seminarios, podcast, Oraciones...). En Avada Live, para cada **Title** y **Text Block**:

1. **Campo Typography del elemento**: si tiene un valor (un px, una fuente suelta), **vacíalo** y mira cómo queda. Si queda peor, no lo borres: anótalo como excepción.
2. **Formato en línea**: abre la vista **Texto** del Text Block y busca `font-size`. Si lo hay, quítalo.
3. **Nivel**: si un título está en un nivel H1-H6 que no corresponde, **cambia el nivel**, no el tamaño (regla 3).

## Paso 5 — Verificar (20 minutos)

Abre la página de pruebas **en el navegador** (no en Avada Live), con Inspeccionar → Computed. Tamaños esperados (±1 px):

| Elemento | 375 px | 768 px | 1024 px | 1440 px |
|---|---|---|---|---|
| H1 | 34 | 42 | 47 | 56 |
| H2 | 30 | 37 | 41 | 48 |
| H3 | 26 | 31 | 34,5 | 40 |
| H4 | 23 | 26,7 | 29 | 33 |
| H5 | 21 | 23,6 | 25 | 28 |
| H6 | 19 | 20 | 21 | 22 |
| Body | 16 | 16,8 | 17,2 | 18 |

Además:

- [ ] El texto normal no baja de 16 px en móvil.
- [ ] Ningún título ocupa más de 3 líneas a 375 px y no hay scroll horizontal.
- [ ] A 1024 px el aspecto es coherente (sin salto brusco).
- [ ] Con el zoom del navegador al 200 %, el texto sigue creciendo y no se corta.
- [ ] Lo has visto en **un móvil real** (no por Live Link).
- [ ] Carlitos ha mirado cómo queda y le parece bien.

## Paso 6 — Cerrar y avisar

1. Reset de cachés (ya en 3.8) y **Blueprint nuevo**.
2. **Exporta y sanea Global Options "después"** (`GUIA_AVADA_LOCAL.md` §2.2). Compara con el "antes" **solo las claves de tipografía**: sensibilidad, factor, Body, H1-H6, título de entrada, Page Title Bar y los sets.
3. Rellena el **informe del Anexo B** y envíaselo a Carlitos. Con él se cierra el documento y se actualizan chuletas e instrucciones.
4. **No subas nada al repositorio**: lo hace Carlitos.

## Si algo sale mal

| Pasa... | Haz... |
|---|---|
| Un campo no acepta la fórmula | No fuerces. Anota qué campo es y qué hizo. Vuelve al valor anterior de tu tabla del Paso 1 |
| Algo se ve peor de repente | Vuelve al Blueprint y empieza otra vez desde el Paso 1 con el chat de siempre |
| La fuente de un set no está en la lista | No instales otra. Mira qué entrada usa hoy H1 y usa esa; si no, avisa |
| No entiendes qué manda sobre un texto | Parte 2.2 de este documento |

---

# PARTE 4 — Resultado de la prueba de Álvaro (23 octubre 2026)

## 4.1 En una mirada

| Qué | Resultado |
|---|---|
| **P1** — ¿el H1 admite la fórmula? | ✅ Sí. Medido: 34,01 px (375) · 42,15 (768) · 47,65 (1034) · 56 (1440) |
| **P2** — ¿un set admite la fórmula? | ✅ Sí (set Lead) |
| **P3** — ¿hay variables CSS de los sets? | ✅ Existen `--awb-typography1` a `5`, con 7 propiedades cada una (familia, tamaño, peso, estilo, interlineado, espaciado, mayúsculas). ⚠️ **Pero hay un problema grave** (4.4) |
| Ruta seguida | **A** (fórmula aceptada en H1-H6 y en los sets) |
| Hipótesis del Wizard (los 5 sets con letra de relleno) | ✅ **Confirmada:** los 5 sets traían **Inter** |
| Verificación numérica (Paso 5) | ✅ a 375, 768, 1034 y 1440 px, todo dentro de ±1 px del Anexo A |
| Sensibilidad | Puesta en **0,00** (antes 0,30, según el export del 14 agosto; factor 1,50). Hecha antes de terminar las pruebas: los px fijos ya no se reducen en móvil |
| Pendiente de verificar | Móvil real ❌ · zoom 200 % ❌ · validación de Hna C ❌ · Page Title Bar sin medir en pantalla · menú sin revisar |
| Red de seguridad | ⚠️ No se guardó export ni Blueprint "antes" (sí Blueprint "después"). **No hay vuelta atrás limpia al estado anterior**; la referencia más cercana es el export del 14 de agosto |

## 4.2 Qué quedó aplicado

| Rol | Fuente | Tamaño | Peso | Fuente de respaldo |
|---|---|---|---|---|
| Headings | YEAH-PAPA | `clamp(30px, 23.66px + 1.69vw, 48px)` | 400 | vacía |
| Subheadings | YEAH-PAPA | `clamp(22px, 19.18px + 0.75vw, 30px)` | 400 | vacía |
| Lead | **Poppins** | `clamp(18px, 16.59px + 0.38vw, 22px)` | 400 | vacía |
| Body | **Poppins** | `clamp(16px, 15.30px + 0.19vw, 18px)` | 400 | vacía |
| Small | **Poppins** | `clamp(13px, 12.65px + 0.09vw, 14px)` | 400 | vacía |

Body Typography pasó de 16 px fijo a la fórmula de Body. Page Title Bar: `clamp(32px, 24.25px + 2.07vw, 54px)` guardado. Toggles (título): `clamp(22px, 19.18px + 0.75vw, 30px)` escrito directamente, **no vinculado** al set (mismo valor, permitido por 1.5).

## 4.3 Decisión de fuente: Poppins (Carlitos, 23 octubre 2026)

**Carlitos confirma Poppins para el texto** (Lead, Body y Small), tal como lo dejó Álvaro. Yeah Papa se mantiene en Headings y Subheadings. Pasa a ser la norma de la **web nueva en Avada**. La web vieja y la app siguen con Helvetica Neue salvo que se decida otra cosa (pregunta abierta 1).

Qué implica y qué hay que hacer:

| Qué | Estado / acción |
|---|---|
| Poppins en Lead, Body y Small, peso 400 | ✅ Hecho por Álvaro. Norma desde hoy |
| **Botón en Poppins** | ✅ Coherente con la decisión. El catálogo (§5) dice Helvetica Neue: se actualiza al cerrar la versión 2 |
| **Carga desde Google Fonts (`cdn`)** | ❌ **Decisión de Carlitos: subir Poppins como fuente propia, igual que Yeah Papa.** Pasos: ① descargar Poppins (peso 400 y 700; Poppins tiene licencia libre, SIL Open Font License) en formato `.woff2` — la herramienta *Google Webfonts Helper* los da ya convertidos; ② Avada → Options → Typography → **Custom Fonts**: añadir la familia «Poppins» con un archivo por peso (400 y 700), como se hizo con Yeah Papa (Avada pide varios formatos para compatibilidad total, pero con Yeah Papa bastó el `.woff2`); ③ en los sets Lead, Body y Small, en el botón y donde se use, **volver a elegir Poppins en la lista de fuentes propias** (no en la de Google); ④ comprobar en DevTools → Network que no hay peticiones a `fonts.googleapis.com` ni `fonts.gstatic.com` y que el archivo de Poppins sale de nuestro dominio. Así no se depende de un interruptor que ya apareció sin aplicar (el catálogo §9 decía Local; Álvaro lo vio en `cdn`). *Alternativa de 2 minutos si falta tiempo:* Avada → Options → Privacy → *Google Fonts Mode* = Local. Se puede dejar puesto también como red de seguridad |
| **Fuente de respaldo vacía** | Poner `Helvetica, Arial, sans-serif` en los 5 sets (es lo que se ve mientras carga la fuente, o si falla) |
| **Helvetica Nueva** (fuente propia subida) | Queda sin uso salvo en el contenido del acordeón (peso 200 a 16 px). Vincular ese contenido al set Body. **No se borra** de Custom Fonts por ahora |
| **Negrita en Poppins** | 🔲 Comprobar un texto en negrita. Si el peso 700 no se carga, el navegador fabrica una negrita falsa (se ve borrosa y ancha). Si pasa, avisar: se añade el peso a Body |
| **Tamaños** | ✅ Carlitos ha visto el resultado y queda bien. Si algo se ve grande o pequeño, se recalcula (5.4) |
| **Bocetos de Carlota** | Estaban en Helvetica Neue. Desde hoy se dibujan con Poppins. **Hay que decírselo a Hna C**: cambia su trabajo (instrucciones de Proyecto 4/6, bloque A9 del documento de cambios) |
| **Código nuevo en Avada** | Los módulos y mejoras **no fijan `font-family`: heredan** (regla en las instrucciones del Proyecto 11) |
| **Peso 400 en todos los sets** (antes 600/600/500) | ✅ Aceptado: los pesos de antes eran los del relleno (Inter). Yeah Papa va en peso normal |

Documentos que cambian (cuando se cierre la versión 2): `00_CORE.md` §6 (para la web nueva), el catálogo, la guía, las instrucciones de Proyecto 4/6 y las chuletas (ver `CAMBIOS_REPOSITORIO_2026-10-23.md`).

## 4.4 Hallazgo grave: variables con nombre en español

**Qué se vio (✅ confirmado por Álvaro en su Local):**

- **14 claves del export** (Body, H1-H6, título de entrada, botón, menú, menú móvil, footer y FAQ) apuntan a variables con **nombre en español** — por ejemplo `var(--awb-tipografía4-línea-altura)` — que **no existen**. Las que existen están en inglés (`--awb-typography4-line-height`).
- Efecto medido: el interlineado calculado de `<body>` es **"normal"**, aunque el set Body dice 1,72. El campo *Line Height* de Body sigue **en rojo**.

**Por qué importa:**

- Una variable que no existe invalida esa propiedad: el navegador ignora el valor y usa el heredado o el de por defecto. Es decir, **las propiedades de esas 14 claves que dependan de un set pueden no estar aplicándose**: interlineado seguro, y 🔲 por comprobar familia, espaciado y peso.
- Los tamaños de H1-H6 y Body **sí funcionan** porque Álvaro los escribió directamente como fórmula (P1 lo demuestra).
- 🔲 Es probable que esto **también contribuyera al problema original** ("grande en móvil, pequeña en escritorio"): un campo de tamaño atado a una variable inexistente no recibe el valor del set. Es una inferencia mía, no una prueba.

**Causa: sin confirmar.** No he encontrado documentación de Avada sobre esto. Dos hipótesis:

1. Los campos se guardaron con la **interfaz de Avada en español** y Avada tradujo el nombre de la variable al guardar.
2. Los vinculó el **Setup Wizard** (7 de julio), que corrió con la interfaz en español. Encajaría con que las 14 claves sean justo las que el Wizard rellena por defecto.

**Receta rápida (30-60 minutos; la hace Álvaro):**

1. **Ver cuáles son:** en el export saneado, busca `tipograf`. Cada resultado es un campo roto (Body, H1-H6, título de entrada, botón, menú, menú móvil, footer, FAQ).
2. **Cambiar el idioma de tu usuario a inglés:** Usuarios → Tu perfil → Idioma → English. Recarga el panel.
3. **Volver a elegir la ficha (set) en cada campo roto** y guardar. El número del nombre roto te dice qué ficha era: `tipografía1` = Headings, `2` = Subheadings, `3` = Lead, `4` = Body, `5` = Small (por ejemplo, `tipografía4-línea-altura` = el interlineado del set Body). Empieza por **Body → Line Height**.
4. **Comprobar el primero:** el campo deja de estar en rojo y, en DevTools, `<body>` pasa a medir 1,72 de interlineado. Si es así, repite con los otros 13. **Si no funciona**, escribe el valor directamente en esos campos (por ejemplo el interlineado como número): funciona igual y no depende de la ficha.
5. **Al terminar:** vuelve a exportar y sanear, y busca `tipograf`: no debe salir nada. Puedes volver el idioma a español.

No hace falta saber por qué pasó. Si algo falla, la tabla de abajo es el detalle con más pasos.

**Si la receta rápida no funciona (referencia detallada; cada paso se anota):**

| Paso | Qué hacer | Qué anotar |
|---|---|---|
| **R0 — Inventario** | En el JSON **saneado**, busca `tipograf` (con y sin tilde) y haz una lista de cada `var(--awb-…)` roto con su clave | Tabla: clave → variable rota → variable correcta (en inglés) |
| **R1 — Una sola prueba** | Cambia el idioma de **tu usuario** a English (Usuarios → Tu perfil → Idioma) y recarga el panel. En Typography → Body Typography, **vuelve a vincular** el *Line Height* al set Body con el selector del propio campo y guarda | ¿El campo deja de estar en rojo? ¿`<body>` mide ahora 1,72 de interlineado? ¿El export trae `var(--awb-typography4-line-height)`? |
| **R1b — Control** | Con el idioma otra vez en español, guarda un cambio sin importancia y exporta de nuevo | ¿Vuelve a aparecer "tipografía"? Si vuelve, **todo cambio de tipografía debe hacerse con el idioma en inglés** |
| **R2 — Si R1 funciona** | Repite para las otras 13 claves, con el idioma en inglés | Tras exportar y sanear, la búsqueda de `tipograf` debe dar **0** resultados |
| **R3 — Si R1 no funciona** | Escribe los valores **directamente** en esos campos (por ejemplo, el interlineado como número) en vez de vincularlos al set. Es una excepción: se anota | Qué campos, qué valores. Y avisar a Carlitos |

Si R3, queda una vía más: un reemplazo controlado en la base de datos (herramienta de Local), solo con Blueprint hecho y la tabla de R0 completa. Lo decide Carlitos; no se hace sin esa tabla.

⚠️ **Hasta que R1 y R1b confirmen la causa**, Álvaro edita la tipografía de Avada con el idioma de su usuario en **inglés**. Es inocuo: solo cambia la interfaz del panel.

## 4.5 Otros hallazgos

| Hallazgo | Qué hacer |
|---|---|
| **Carrusel de santos (home): cada santo es un H1** (9 H1 + el título de la página). Álvaro lo vinculó al set Subheadings: 29,98 px en Yeah Papa a 1440 | Cambiar el **nivel** a H3 en el molde (regla 3). El tamaño no cambia porque va vinculado al set. Una página debería tener un solo H1 |
| Solo se limpió la **home** (1 tamaño suelto: «Pulsa para ir», 16 px, vaciado) | Pendiente: el resto de páginas y Layouts (Paso 4) |
| Título «TIRITAITO» de la home con familia y peso **escritos a mano** (mismo valor que el global) | Vaciarlos o vincular al set Headings, para que no se desalineen si cambia el global |
| Contenido del acordeón: Helvetica Nueva **peso 200** a 16 px fijo | Peso 200 es muy fino para leer. Vincular al set Body |
| Título de FAQ 24 px fijo · `post_titles_extras` 20 px fijo | Islas: vincular a un set o dar valor fluido (`post_titles_extras` está dentro de Heading Typography) |
| `es_title_font_size` 42 px, `countdown` con «18 píxeles» (unidad inválida) y otros px sueltos | Probablemente son de funciones sin usar (el prefijo `es_` suele ser del Elastic Slider, desactivado; Countdown no se usa). 🔲 Sin comprobar. Entra en el barrido de unidades inválidas de `GUIA_AVADA_LOCAL.md` §19 |
| `#ffffff` guardado como color en Body, H1-H6, título de entrada, menú, footer y barra de privacidad | ✅ **Decisión de Carlitos:** los colores por defecto se fijan **en el global** y solo se cambian en un elemento si hay un motivo concreto (así lo plantea Avada: lo global manda salvo que el elemento diga otra cosa). **Qué hacer:** Avada → Options → Typography → **Body Typography** → Color → paleta, **Color 7 · Texto secundario** (`#3a3a3c`; decisión de Carlitos, 24 oct); lo mismo en **Heading Typography** (H1-H6 y título de entrada). Es un gris muy oscuro: sobre blanco se lee de sobra. Si los títulos deben ir de otro color por defecto, se elige ahí. **Menú, footer y barra de privacidad:** dejarlos como están (están sobre sus propios fondos). Después, mirar la home y una página interior: si algún texto blanco sobre fondo oscuro ha desaparecido, se pone blanco en ese elemento |
| Menú: tamaño con variable rota, sin revisar | Se resuelve con la reparación (4.4); luego revisar en móvil |

---

# PARTE 5 — Cómo tocar la tipografía a partir de ahora (Álvaro)

## 5.1 Antes de tocar nada

1. **Idioma del usuario en inglés** mientras la reparación 4.4 no haya confirmado la causa.
2. **Blueprint** y **export saneado "antes"** (`GUIA_AVADA_LOCAL.md` §2.2). En la tarea del 23 de octubre se omitieron y ya no hay vuelta atrás limpia: no volver a pasar.
3. Tener claro **qué quieres cambiar** y mirar la tabla de abajo: casi siempre se toca **un solo sitio**.

## 5.2 Qué tocar según lo que quieras cambiar

| Quiero cambiar... | Dónde (Avada → Options → Typography) | Cómo |
|---|---|---|
| La **fuente** de todos los títulos | Global Typography → set **Headings** → Font Family | Elegir la entrada de la lista que ya usa H1. Dejar respaldo |
| El **tamaño** de un nivel (por ejemplo todos los H2) | Heading Typography → H2 → Font Size | Solo el tamaño, con fórmula (5.4). Familia y demás se quedan en gris |
| La **fuente** del texto normal | Global Typography → set **Body** (y Lead, Small si deben ir a juego) | Igual que arriba |
| El **tamaño o interlineado** del texto normal | Global Typography → set **Body** | Fórmula (5.4) |
| Un texto con un rol distinto (entradilla, pie de foto) | En el elemento: campo Typography → elegir el set | **Lead** / **Small** / **Subheadings** |
| **Un título concreto** que se ve distinto | **No se toca su tamaño** | Cambiar su nivel H1-H6 o vaciar su campo Typography |
| El **color** de todo el texto | Body Typography y Heading Typography → Color | Elegir de la paleta (Color 7 · Texto secundario); nunca un hex suelto |
| El **menú**, la **barra de título**, un **Toggle** | Su propio campo (1.5) | Fórmula (5.4) o set Subheadings |

## 5.3 Procedimiento de cualquier cambio global

1. Blueprint + export saneado "antes" (5.1).
2. Haz el cambio **en un solo sitio** (5.2).
3. **Reset Avada Caches**, purga la caché del navegador, recarga.
4. Mide a **375, 768 y 1440 px** (Inspeccionar → Computed → `font-size`) en la página de pruebas y en una página real. Compara con el Anexo A.
5. Export saneado "después". Compara solo las claves tocadas.
6. Anota el cambio y el motivo en el registro (Parte 6). Si cambia **la escala** de toda la web, avisa antes a Carlitos y a Hna C.

## 5.4 Cómo escribir o cambiar una fórmula

Fórmula: `clamp(MÍN px, A px + B vw, MÁX px)`, con el mínimo en 375 px y el máximo en 1440 px.

- **B** = (MÁX − MÍN) ÷ 1065 × 100
- **A** = MÍN − (B ÷ 100) × 375

Ejemplo: H2 con máximo 52 en vez de 48 → B = 2,07, A = 22,25 → `clamp(30px, 22.25px + 2.07vw, 52px)`. **Se escribe sin `font-size:`** y sin espacios extraños. Después, mide: debe dar 30 px a 375 y 52 px a 1440.

## 5.5 Lo que sí conviene evitar

- **Nunca escribir a mano una variable** `var(--awb-…)` en un campo: se elige el set con el selector del propio campo.
- **Nunca guardar un campo que esté en rojo:** el rojo significa valor inválido.
- Arreglar un problema **global** (todos los H2, todo el cuerpo) elemento a elemento: se arregla una vez en el global.
- No tocar el interlineado, el espaciado ni el peso de un set sin avisar: afectan a toda la web.
- No cambiar la **familia** de un set sin que lo decida Carlitos con Hna C: es identidad de marca.

---

# ANEXO A — Valores de la escala

Mínimo en **375 px**, máximo en **1440 px**. Fórmulas **sin** `font-size:` si van en un campo del panel; con `font-size:` si algún día van en CSS.

| Rol | Mín → Máx | Fórmula | 375 | 768 | 1024 | 1440 |
|---|---|---|---|---|---|---|
| H1 | 34 → 56 | `clamp(34px, 26.25px + 2.07vw, 56px)` | 34 | 42,1 | 47,4 | 56 |
| H2 | 30 → 48 | `clamp(30px, 23.66px + 1.69vw, 48px)` | 30 | 36,6 | 41 | 48 |
| H3 | 26 → 40 | `clamp(26px, 21.07px + 1.31vw, 40px)` | 26 | 31,1 | 34,5 | 39,9 |
| H4 | 23 → 33 | `clamp(23px, 19.48px + 0.94vw, 33px)` | 23 | 26,7 | 29,1 | 33 |
| H5 | 21 → 28 | `clamp(21px, 18.54px + 0.66vw, 28px)` | 21 | 23,6 | 25,3 | 28 |
| H6 | 19 → 22 | `clamp(19px, 17.94px + 0.28vw, 22px)` | 19 | 20,1 | 20,8 | 22 |
| Título de entrada | 36 → 78 | `clamp(36px, 21.21px + 3.94vw, 78px)` | 36 | 51,5 | 61,6 | 77,9 |
| Page Title Bar | 32 → 54 | `clamp(32px, 24.25px + 2.07vw, 54px)` | 32 | 40,1 | 45,4 | 54 |
| Subheadings | 22 → 30 | `clamp(22px, 19.18px + 0.75vw, 30px)` | 22 | 24,9 | 26,9 | 30 |
| Body | 16 → 18 | `clamp(16px, 15.30px + 0.19vw, 18px)` | 16 | 16,8 | 17,2 | 18 |
| Lead | 18 → 22 | `clamp(18px, 16.59px + 0.38vw, 22px)` | 18 | 19,5 | 20,5 | 22 |
| Small | 13 → 14 | `clamp(13px, 12.65px + 0.09vw, 14px)` | 13 | 13,3 | 13,6 | 13,9 |

**De dónde salen:** los máximos de escritorio conservan la escala de los H1-H6 que había (50/43/37/31/25/20 px) con un máximo algo mayor, porque el texto se veía pequeño en escritorio; los mínimos son nuevos y más bajos, porque el texto se veía grande en móvil. "Yeah Papa" necesita más píxeles que Helvetica Neue para el mismo peso visual (`00_CORE.md` §6), así que no se bajan más. **Es una propuesta: se valida a ojo en el Paso 5.** 

**Cómo se calculó:** `tamaño = A + B·(ancho/100)`, con B = (máx − mín)/(1440 − 375) y A = mín − B·375, acotado entre mín y máx. Si Hna C quiere otro mínimo o máximo, se recalcula con esa fórmula.

---

# ANEXO B — Informe de Álvaro (plantilla)

```
INFORME DE TIPOGRAFÍA — fecha:
1) Pruebas:  P1 ✅/⚠️/❌ (mide: 375px=__ / 1440px=__)   P2 ✅/❌   P3 (variables vistas: ...)
2) Ruta seguida: A / B / otra (explicar)
3) Los 5 sets: ¿tenían fuente de relleno? ¿cuál?
4) Valores finales aplicados: (pega la tabla; di cuáles difieren del Anexo A y por qué)
5) Islas: Page Title Bar ✅/❌ · Toggles ✅/❌ · Menú: (observaciones)
6) Excepciones anotadas (elemento, página, motivo):
7) Páginas limpiadas: (lista) · tamaños en línea encontrados: (cuántos, dónde)
8) Sensibilidad: valor final y por qué
9) Verificación: Paso 5 hecho ✅/❌ · móvil real ✅/❌ · Hna C valida ✅/❌
10) Cosas raras o dudas:
```

---

# PARTE 6 — Registro de excepciones, próximos pasos y preguntas abiertas

## Registro de excepciones conocidas (informativo, a 23 octubre 2026)

Solo recoge lo que se encontró en la prueba. **No hace falta anotar las nuevas**.

| Elemento / página | Qué tiene | Por qué | Quién lo anotó |
|---|---|---|---|
| Toggles (título) | `clamp(22px, 19.18px + 0.75vw, 30px)` directo, no vinculado al set | Mismo valor que Subheadings; permitido (1.5) | Álvaro |
| Page Title Bar | `clamp(32px, 24.25px + 2.07vw, 54px)` guardado | Isla (1.5). 🔲 Sin medir en pantalla | Álvaro |
| Botón | Poppins; tamaño atado a variable rota | Fuente ✅ decidida (4.3); tamaño por reparar (4.4) | Álvaro |
| Contenido de acordeón | Helvetica Nueva peso 200, 16 px fijo | 🔲 Vincular a Body (4.3) | Álvaro |
| Título de FAQ | 24 px fijo | 🔲 Por resolver (4.5) | Álvaro |
| `post_titles_extras` | 20 px fijo | 🔲 Por resolver (4.5) | Álvaro |
| Home, Title «TIRITAITO» | Familia y peso a mano (mismo valor que el global) | 🔲 Por vaciar (4.5) | Álvaro |
| Carrusel de santos (home) | Vinculado a Subheadings; nivel H1 ×9 | 🔲 Pasar a H3 (4.5) | Álvaro |
| `es_title_font_size` 42, `countdown` «18 píxeles» y otros | px sueltos del export original | 🔲 Probablemente funciones sin usar; sin revisar | Álvaro |

## Próximos pasos

**Imprescindible (poco trabajo):**

1. **Álvaro:** subir Poppins como fuente propia (Custom Fonts), volver a elegirla en los sets, el botón y donde se use, y comprobar en Network que no hay peticiones a Google (4.3).
2. **Álvaro:** reparar las variables en español con la receta rápida (4.4): 30-60 minutos.
3. **Álvaro:** fijar en el global el color por defecto del texto: Color 7 · Texto secundario en Body y Heading Typography (4.5), y mirar la home.
4. **Carlitos:** decirle a Carlota que el texto ahora es Poppins, y pegar los bloques del grupo A de `CAMBIOS_REPOSITORIO_2026-10-23.md`.
5. **Carlitos:** mirar la home en su móvil. El problema original era el móvil: es la prueba que de verdad importa.
6. **Hno C:** con 1, 2 y 3 hechos, cerrar este documento como versión 2 y actualizar `00_CORE.md`, el catálogo y la guía.

**Cuando se toque, sin urgencia:** pasar el carrusel de santos a H3 (hoy son 9 H1); restaurar la fuente de respaldo; vincular el acordeón a Body; limpiar páginas a medida que se editen; revisar la negrita solo si se ve rara; auditar el CSS maestro (Proyecto 11).

## Preguntas abiertas

| # | Pregunta | Quién decide |
|---|---|---|
| 1 | ¿La app (Creators) y la web vieja siguen con Helvetica Neue, o también pasan a Poppins? | Carlitos |
| 2 | ¿El Color 7 (Texto secundario) es también el color por defecto de los títulos, o solo del cuerpo? | Carlitos |
| 3 | ¿Funciona la reparación de variables con el idioma en inglés? Si no, ¿valores directos en esos campos? | Álvaro |

*Sin urgencia (se resuelven solos al trabajar):* si la Page Title Bar y los Toggles admiten sets; cuántos sets más se pueden añadir; si la familia, el espaciado y el peso de las 14 claves afectadas también fallan.

---

*Para la mayor gloria de Dios · tiritaito.com*

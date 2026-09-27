# TIRITAITO.COM — Propuesta: cómo trabajar con código en Avada
**Un modo de hacerlo sencillo, estable, y con una sola casa para cada dato**
*Proyecto 2 (Hno C) · 24 septiembre 2026 · **v2** — reescrita tras las respuestas de Carlitos (ganchos = clase, sin PHP, radio de 15px, CSS en GitHub sin duplicar)*
*Estado: **PROPUESTA — nada aplicado.** No he tocado ningún documento ni ninguna instrucción de cuenta. Los tres casos concretos de CSS (radios de Toggles, Conecta, Toggle-intro) quedan para cuando el sistema esté montado. Hoy no hay que tocar nada en Avada.*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Resumen

1. **El sistema tiene cuatro piezas:** Carlota dibuja libre; las cuentas de construcción de Álvaro construyen con lo nativo y, si Avada no puede, hacen un **traspaso**; una **cuenta de Código** (nueva) resuelve todo lo visible en Avada; y el **PHP** con contexto completo lo resuelve Carlitos.
2. **Ganchos: decidido el modelo B.** Siempre una clase `tt-…` que da la cuenta de Código. Nunca ID, nunca clases inventadas.
3. **Una sola casa para cada dato.** El CSS y sus fichas viven en **un único archivo de GitHub** que leen, por el conector, las cuentas que lo necesitan. Lo de Avada es la copia desplegada. No hay índice aparte, no se pega el CSS en cada chat y el campo `custom_css` del JSON de opciones deja de contar (Sección 3).
4. **Radio: 15px como norma general** de la web nueva, en una variable única que se puede cambiar cuando se quiera (Sección 7).
5. **Cuando Avada se actualice**, la cuenta de Código investiga qué ha cambiado y se revisa solo eso, con una red de seguridad barata (Sección 6).
6. **Para entregarlo todo en una tanda** (incluidas las correcciones del punto 9) necesito que subas 5 archivos y respondas 4 preguntas (Sección 10).

---

## 1. El sistema en una imagen

```
 CARLOTA (P4 / P6)       CONSTRUCCIÓN (P3 / P7 / repuesto)        CÓDIGO (P11, nueva)
 ─────────────────       ─────────────────────────────────        ───────────────────
 Dibuja libre.           Construye con lo nativo de Avada.        Mira si ya existe la mejora.
 Si algo puede no   ──►  ¿Se puede? Listo.              ──────►   Pide el HTML real.
 ser nativo, lo          ¿Ya hay una mejora en el CSS?            Escribe bloque + ficha.
 anota en 1 línea.       Usa su clase.                            Da pasos, prueba, diagnostica.
                         ¿No la hay? → TRASPASO                        │
                                                                       ▼
                                          Avada → Custom CSS   ◄──►   GitHub: avada-custom-css.css (manda)

 PHP / servidor con contexto completo ──► Carlitos
```

| Quién | Qué hace | Qué NO hace |
|---|---|---|
| **Carlota** (P4/P6) | Dibuja bocetos libres; si un efecto puede no ser nativo, lo anota en una línea | No piensa en clases, IDs ni CSS; no escala nada |
| **Construcción** (P3, P7 y la cuenta de repuesto) | Construye con lo nativo. Lee el archivo de CSS para saber qué mejoras existen y qué clase escribir. Si no existe la que hace falta, genera el **traspaso** | No escribe CSS; no inventa clases; no toca el Custom CSS |
| **Código** (P11, nueva) | Comprueba si ya existe; pide el HTML real; escribe el bloque con ficha; da los pasos; prueba; diagnostica; entrega el archivo completo | **No toca PHP** ni snippets de servidor; no pide permiso para lo rutinario ni devuelve a Álvaro trabajo que puede hacer ella |
| **Carlitos** | Casos puntuales de PHP con contexto completo; decide cuando cambian las reglas firmes | No participa en el día a día del código |
| **P10** (consulta profunda) | Sin cambios de función (solo su regla sobre la Clase CSS) | — |

---

## 2. Reglas: cinco firmes y el resto flexible

| # | Regla firme | Por qué |
|---|---|---|
| F1 | **Un solo sitio para el CSS de mejoras de elementos de Avada:** *Avada → Options → Custom CSS*. Page Options → Custom CSS solo por excepción autorizada | Lo de Page Options no sale en ningún archivo del repo: queda invisible |
| F2 | **Un solo archivo maestro en GitHub:** `03-guias-practicas/avada-custom-css.css`. **Manda él**; lo de Avada es la copia desplegada, idéntica y con el mismo sello de versión | Copia de seguridad (el Local ya se perdió una vez), historial y vuelta atrás en segundos |
| F3 | **Un solo gancho: una clase `tt-…` por mejora.** La escribe Álvaro en el campo "CSS Class" **solo** si se la da la cuenta de Código (o si aparece en una ficha del archivo). Nunca ID | Una regla que no hay que pensar; nada que migrar el día que una pieza se repita; el campo ID queda libre para enlaces internos |
| F4 | **Cada bloque lleva su ficha** (cabecera). **Las fichas son el registro:** no hay índice aparte | Un solo sitio que mantener; cualquier cuenta puede listar las mejoras leyendo el archivo |
| F5 | **Nadie edita el CSS a mano en Avada.** Todo cambio pasa por la cuenta de Código y por el archivo. Si alguien lo tocó a mano, se vuelca al archivo antes de seguir | Evita que Avada y GitHub se desincronicen |

**Separación** (sustituye a la regla vieja de "nunca en Custom CSS global"):
- **Mejora de un elemento de Avada** (Toggles, Tabs, Audio…) → Custom CSS global, con ficha.
- **Módulo propio** (snippet con HTML/JS/PHP, como el podcast) → su CSS **dentro de su snippet**, como hasta ahora.

**Vigilancia** (sustituye al tope de 30 líneas): la cuenta de Código avisa a Carlitos si el archivo pasa de **600 líneas** o de **15 mejoras**.

**Estabilidad** (para no cambiar el sistema a la primera de cambio): las reglas firmes solo se revisan en **hitos** — fin de una fase de construcción, actualización mayor de Avada, o cuando haya 5 anotaciones acumuladas en el **CUADERNO_DEL_CONSTRUCTOR** (ya existe y hace de buzón; no se crea nada nuevo). Entre hitos, una fricción se anota; no se cambia la regla.

**Flexible** (lo decide la cuenta de Código): cómo se escribe cada bloque, los selectores, los nombres tras `tt-`, las variables, cuándo dividir un bloque.

---

## 3. Una sola casa para cada dato

| Dato | Su única casa | Quién lo lee | Nota |
|---|---|---|---|
| El CSS y las fichas (el registro) | `03-guias-practicas/avada-custom-css.css` | P11, P3, P7, cuenta de repuesto, P2 | Manda el archivo. Avada tiene la copia desplegada |
| Versión del CSS y versión de Avada probada | **Primera línea de ese mismo archivo** (el sello) | Todos | Ningún otro documento repite la versión |
| Radio general | Variable `--tt-r-web` al principio del archivo (espejo de `00_CORE.md` §5, que es el canónico) | — | Un solo número que cambiar |
| Manual de la cuenta de Código | `03-guias-practicas/CHULETA_CODIGO_AVADA.md` | P11, P2 | Reglas, formato de respuesta, diagnóstico |
| Plantilla del traspaso | `CHULETA_ALVARO.md` §7 | P3, P7, repuesto | P11 lo entiende sin tenerla: son campos en cristiano |
| Paleta y tokens | `00_CORE.md` §5 | — | Las chuletas llevan un resumen, como ya era |
| Campo `custom_css` de `avada-global-options.json` | **Ninguna: se ignora** | — | Conviene que el saneador lo vacíe (Sección 10). Cambiar el CSS ya **no obliga a regenerar el export** |
| Buzón de ideas del sistema | `CUADERNO_DEL_CONSTRUCTOR.md` | P2 | Ya existe |

**Acceso.** Cada cuenta que lo necesita tiene el archivo por el conector de GitHub, **seleccionando solo ese archivo** (no la carpeta: el consumo de uso engorda con cada archivo de más). Carlota no lo necesita.

**Sincronización sin pegar el CSS.** El conector no se actualiza solo (hay que pulsar *Sync now*). Para que nadie trabaje con una versión vieja:
1. P11 abre cada chat diciendo: *"Tengo la v012 del CSS. En Avada → Options → Custom CSS, ¿la primera línea dice v012?"*
2. Si coincide, sigue. Si no: *"Pulsa Sync now en el conector"*. Y si aun así no coincide, *"pégame el CSS que hay en Avada"* (solo en ese caso).
3. Al terminar, P11 entrega el archivo con la versión subida (v013). Álvaro lo **pega en Avada** y lo **sube a GitHub** (reemplazando el anterior).

---

## 4. La ficha y el sello (formato)

Solo ilustra el formato; el bloque real se tratará cuando toque:

```css
/* TT-CSS · v001 · 2026-09-24 · Avada 7.16.1 */
:root { --tt-r-web: 15px; }  /* radio general de la web nueva */

/* ══════════════════════════════════════════════════════════════
   TT-MEJORA · Toggles con esquinas redondeadas
   Cómo se usa: global — no lleva clase (afecta a todos los Toggles con Boxed Mode)
   Qué hace:    redondea cada toggle y las imágenes/vídeos de su interior
   Ajustable:   --tt-r-web
   Depende de:  Toggles en Boxed Mode · clases internas .fusion-panel y .fusion-toggle-content
   Probado con: Avada 7.16.1 · AAAA-MM-DD · escritorio / tablet / móvil
   ══════════════════════════════════════════════════════════════ */
```

- La línea **"Cómo se usa"** es la que leen las cuentas de construcción: dice qué clase escribir y en qué elemento.
- La línea **"Depende de"** es la que lee la cuenta de Código cuando Avada se actualiza.
- Las fichas las escribe la cuenta de Código sola; Álvaro no rellena nada.
- Con cada versión subida a GitHub, volver atrás es pegar la versión anterior en Avada.

---

## 5. El traspaso y cómo trabaja la cuenta de Código

**Traspaso** (lo genera la cuenta de construcción; Álvaro lo copia en el chat de Código):

```
TRASPASO A CÓDIGO
Qué quiero conseguir: (una frase, en cristiano)
Dónde: página · sección · elemento de Avada
Boceto de Carlota: (nombre del archivo o descripción)
Qué probé sin código y por qué no basta: (1-3 líneas)
Avada: (versión, la de la primera línea del CSS)
```

**Respuesta de Código en formato fijo de 4 partes:**

| Parte | Contenido |
|---|---|
| 1. Qué vamos a hacer | Una frase |
| 2. Pasos | Numerados, con dónde hacer clic y qué escribir exactamente |
| 3. Cómo comprobar que ha ido bien | Ordenador, tablet y móvil, con la caché limpia |
| 4. Si algo sale mal | Qué captura enviar |

- **Primero mira si ya existe** una mejora que sirva: "ya está hecha, escribe esta clase aquí".
- **Antes de escribir un selector, pide el HTML real** del elemento (web pública, no el editor Live; Avada 7.16.1): clic derecho → Inspeccionar → clic derecho en la etiqueta → Copiar → Copiar outerHTML. Nunca escribe selectores de memoria.
- **Autonomía:** hace ella todo lo que puede hacer (bloque, ficha, sello, archivo, pasos, comprobaciones). Pregunta solo cuando falta un dato que **solo Álvaro puede ver** o hay una decisión de diseño que cambia el resultado.
- **Escala a Carlitos solo si:** (a) la mejora sería global, para todos los elementos de un tipo; (b) hace falta PHP, snippet o servidor; (c) dos intentos de diagnóstico sin resolver; (d) contradice una regla firme; (e) se supera el umbral de 600 líneas / 15 mejoras.
- **Diagnóstico en pasos sencillos** (el manual lo lleva completo, "para tontos"): reproducir en la web pública en incógnito → clic derecho → Inspeccionar → ver si la regla sale tachada y cuál la vence → mirar si el elemento lleva un estilo escrito dentro → para fuentes, *Computed* → "Rendered Fonts" → probar a quitar cada `!important` → repetir a 1024 / 768 / 480 px con caché limpia.
- **Ajustes:** Sonnet 5 · esfuerzo Medio · un chat por tarea · web solo para consultar documentación oficial de Avada.

---

## 6. Cuando Avada se actualiza

**Tu idea, adoptada:** en vez de revisar todas las mejoras, la cuenta de Código **investiga qué ha cambiado** y se revisa solo eso. Es más fácil y basta casi siempre.

1. Álvaro abre un chat en Código: "Avada se ha actualizado a X".
2. P11 lee las **notas de versión oficiales** desde la versión de la primera línea del CSS hasta X.
3. Las cruza con la línea "Depende de" de cada ficha y da dos listas: **en riesgo** (tocan elementos que cambiaron) y **sin cambios anunciados**.
4. Álvaro revisa a fondo las de "en riesgo" (3 pantallas).
5. Si todo está bien, P11 entrega el CSS con el sello actualizado (nueva versión de Avada) y se sube.

**Una red de seguridad barata, porque las notas de versión no cuentan todo.** Hay un aviso concreto: en agosto, la documentación de Tabs seguía listando menos opciones que la página del propio elemento, así que no conviene fiarse de que se anuncie todo cambio de marcado interno. Por eso propongo una **página de pruebas** privada (sin publicar) con un ejemplo de cada mejora; cuando P11 crea una mejora, le dice a Álvaro qué ejemplo añadir. Tras actualizar, Álvaro abre esa única página en ordenador y móvil (2 minutos) para cazar lo que las notas no dijeron. Si no te gusta, se quita; el resto del método no cambia.

---

## 7. Las decisiones, ya cerradas

| # | Decisión |
|---|---|
| **D1** | **Modelo B:** siempre una clase `tt-…` dada por la cuenta de Código; nunca ID. Cambié mi recomendación (antes, híbrido) porque tu objetivo es sencillez y estabilidad: una sola regla, nada que migrar cuando una pieza se repita, y el campo ID queda libre para enlaces internos |
| **D2** | Se sustituyen el tope de 30 líneas y "nunca en global" por: separación (elementos de Avada → global; módulos propios → su snippet), umbral de 600 líneas / 15 mejoras y fichas obligatorias |
| **D3** | El CSS **maestro es el archivo de GitHub**; Avada tiene la copia desplegada. Sin child theme. El JSON de opciones deja de contar para el CSS |
| **D4** | **15px como norma general** de la web nueva, en la variable `--tt-r-web`; cambiable puntualmente en un bloque concreto. Sustituye a la propuesta de 10px. Mi lectura, a confirmar: los radios de 25/14/8px se quedan solo para la web vieja, los snippets ya hechos y la app; en Avada hay que alinear a 15px lo que hoy está a 10px (botón y formularios) cuando toque |
| **D5** | Avada **7.16.1**. Vive en el sello del archivo de CSS, no en otros documentos |
| **Alcance** | Sin PHP. Los casos puntuales de PHP los resuelve Carlitos, por el contexto completo que requieren |

---

## 8. Qué cambia en el proyecto

### 8.1 Cuentas

| Cuenta | Cambio |
|---|---|
| **P11 — Código en Avada (Hno A), NUEVA** | Instrucciones nuevas. Base: `CHULETA_CODIGO_AVADA.md` + `avada-custom-css.css` (⚠️ ~5–10k tokens en total, estimación). Sonnet 5, esfuerzo Medio |
| **P3 / P7** | Base: `CHULETA_ALVARO.md` + `avada-custom-css.css`. Sustituir los párrafos "PROHIBIDO — CAMPO CLASE CSS" y "CUANDO ALGO NO SE PUEDE CONSTRUIR NATIVO": usa las clases del archivo, genera el traspaso, no escribe CSS |
| **P9 → cuenta de repuesto de construcción** | **Mi opinión: buena idea, el cuello de botella está en construcción.** Mismas instrucciones y base que P3/P7, copiadas tal cual (como ya se hizo con P7). Condiciones: (1) antes de dar por hecho que hacen falta más cuentas, aplica la medición de `CONSUMO_USO_CLAUDE_EQUIPO.md` §7 (30–40 minutos por cuenta) y la dieta de bases de §8.1: el ahorro grande viene de la base ligera, no de sumar cuentas; (2) tu P9 era la cuenta con contexto completo para PHP: si la conviertes, esos casos irían a P2 (pregunta 1 de la Sección 10) |
| **P4 / P6 (Carlota)** | Quitar la prohibición y la obligación de escalar; dibuja libre y anota "posible código". Quitar las referencias a documentos que ya no carga |
| **P10** | Actualizar "MISMAS REGLAS DE CONSTRUCCIÓN… nunca uses el campo Clase CSS" |
| **P2 (yo)** | Mantiene el manual y las reglas firmes; (si se confirma) resuelve los casos puntuales de PHP; repegar el bloque actual (el de claude.ai es anterior al 22/09) |

### 8.2 Archivos nuevos

| Archivo | Para qué |
|---|---|
| `03-guias-practicas/CHULETA_CODIGO_AVADA.md` | El manual de P11 |
| `03-guias-practicas/avada-custom-css.css` | El archivo maestro. Primera versión: **copia literal** del CSS de hoy (sin arreglos) + la línea de sello. Se pega igual en Avada una vez para que el sello coincida |

### 8.3 Documentos existentes (todos se entregan completos en la tanda)

| Documento | Secciones | Cambio |
|---|---|---|
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | §2 (P2), §3/§7 (P3/P7), §4/§6 (P4/P6), §9 (P9), §10 (P10), cierre | Bloques nuevos; **nueva sección P11**; renumerar; cerrar el próximo paso #4 y la pregunta #3 |
| `CHULETA_ALVARO.md` | §0, §5–6, §7, §10 | §7 pasa a "cuando hace falta código: mira el archivo de CSS o traspaso"; fila de Custom CSS global; radio 15px |
| `CHULETA_CARLOTA.md` | §1–2, §3 | §3: "dibuja libre; la tabla de lo nativo es una ayuda"; radio 15px |
| `CATALOGO_ELEMENTOS_AVADA.md` | §5 bis, §13.1, §14 (#11), nueva §5 ter | Reescribir "Qué queda prohibido y qué no"; §5 ter breve que remite al manual y al archivo (sin duplicar); fusionar lo investigado en 7.16; radio 15px |
| `GUIA_AVADA_LOCAL.md` | §8, §10, §12, §13, §14, §16, §17, §19 | Sustituir "30 líneas" y "nunca en global"; corregir §10; regla de clase; radio; barrido de valores traducidos |
| `00_CORE.md` | §5 | Token del radio de 15px y nota de dónde se carga el `:root` |
| `README.md` | Índice, árbol, Estado global | Filas de P11 y de los dos archivos nuevos; el `custom_css` del JSON deja de contar |
| `CONSUMO_USO_CLAUDE_EQUIPO.md` | §4.1, §8.1 | Fila para P11 y para la cuenta de repuesto |
| `CUADERNO_DEL_CONSTRUCTOR.md` | §2 | Mencionar que también recibe fricciones del sistema de código |
| `METODOLOGIA_CONSTRUCCION.md` | §5 | Casilla: ¿hace falta código en Avada? → archivo de CSS o traspaso |
| `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | Mapa de Proyectos, roles, §1, §2.3.1, §6 | **No lo tengo**: hay que subirlo |

---

## 9. Lo que encontré al leer los documentos — todo se corrige en la tanda

| # | Hallazgo | Cómo se corrige |
|---|---|---|
| 1 | Las instrucciones de P4/P6 citan documentos que la cuenta ligera ya no carga (CATALOGO §5 bis, GUIA, METODOLOGIA, `avada-global-options.json`) | Se reescribe el bloque sobre la base real (chuleta + ALCANCE) |
| 2 | P9 cita "la Sección 0.7 de sus instrucciones" de P3, que ya no existe | Se reescribe el bloque de P9 (cuenta de repuesto) |
| 3 | Numeración de INSTRUCCIONES rota (8, 9, 10 y otra vez 9) | Se renumera |
| 4 | Radio: CATALOGO §13.1 lo daba por decidido en 10px; 00_CORE, GUIA y chuletas lo daban por pendiente, y las chuletas marcan como contradicción que el botón tenga 10px | Se propaga la norma de **15px** a todos y se elimina la "contradicción". Queda como tarea de configuración alinear en Avada lo que hoy está a 10px |
| 5 | Las instrucciones de este Proyecto 2 en claude.ai son anteriores al 22/09 (`.matt.*` sigue activo; el documento lo tiene en pausa) | Sigo el documento (no genero `.matt`). Carlitos repega el bloque nuevo |
| 6 | `00_CORE.md` §5 define el `:root` con los `--tt-*`, pero ningún documento dice dónde se carga en la web nueva; el CSS actual usa `var(--tt-txt2)` | Álvaro lo comprueba (2 minutos) y se anota en `00_CORE.md`. Los bloques nuevos no dependen de ello: el radio va en `--tt-r-web`, definida en el propio archivo |
| 7 | CHULETA_ALVARO §6 dice que el Custom CSS global "contiene el radio de 10px de los Toggles" (ya tiene tres bloques) y el export solo guarda el primero | Se reescribe la fila: el CSS vive en su archivo; el `custom_css` del JSON se ignora |
| 8 | GUIA §12 conserva "máximo 30 líneas" y "nunca en Custom CSS global" | Se sustituyen (D2) |
| 9 | El CSS de Conecta tiene 7 pestañas (incluye "Tip" y "Salmo"): el Tip se eliminó el 26/07 y el Salmo se evalúa integrarlo en Misa (ALCANCE §4.D) | Es uno de los "tres casos": se resuelve después, con P11 |

---

## 10. Próximos pasos y preguntas abiertas

### Próximos pasos

| # | Paso | Quién |
|---|---|---|
| 1 | **Subir a esta conversación 5 archivos:** `CATALOGO_ELEMENTOS_AVADA.md`, `GUIA_AVADA_LOCAL.md`, `README.md`, `CUADERNO_DEL_CONSTRUCTOR.md` y `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md`. Tengo su texto en la conversación, pero sin el archivo en disco solo podría editarlos reescribiéndolos enteros, con riesgo de alterar algo | Carlitos |
| 2 | Responder las 4 preguntas de abajo | Carlitos |
| 3 | Yo entrego **en una sola tanda** todo lo de las Secciones 8 y 9, más `avada-custom-css.css` con el sello | Proyecto 2 |
| 4 | Montaje: crear P11 y pegar sus instrucciones; convertir P9 en cuenta de repuesto; conector de GitHub **archivo a archivo** en P3, P7, repuesto y P11; subir los archivos; *Sync now*; **repegar las instrucciones de las demás cuentas** (subir un documento a GitHub no las actualiza) | Carlitos / Álvaro |
| 5 | Álvaro: pegar el `avada-custom-css.css` inicial en Avada (una vez, para que el sello coincida) y comprobar en Local si están definidos los `--tt-*` (DevTools → `html` → *Computed* → buscar `--tt-red`) | Álvaro |
| 6 | Saneador: que **vacíe el campo `custom_css`** del JSON (una línea en su lista de campos a vaciar; aparecerá en la columna "Vaciados", es normal). No escribo código: lo hace Carlitos o Álvaro | Carlitos / Álvaro |
| 7 | Más adelante: alinear a 15px en Avada lo que hoy está a 10px (botón y formularios); y solo entonces, los tres casos concretos con P11 | Álvaro / P11 |

### Preguntas abiertas

| # | Pregunta | Mi recomendación |
|---|---|---|
| 1 | **PHP con contexto completo:** ¿lo resuelves con esta cuenta (P2), con P10 o con la P9 actual? | P2: ya tiene todo el repo, es tuya y no hay que crear nada. Habría que levantar mi regla "no escribes código" **solo para esos casos puntuales**. Así P9 queda libre para ser cuenta de repuesto |
| 2 | **Radio:** ¿15px es la norma de toda la web nueva y los radios de 25/14/8px se quedan solo para la web vieja, los snippets ya hechos y la app? | Asumo que sí |
| 3 | **P11:** ¿va en una cuenta de Claude nueva? | Sí: tiene su propio uso, que es justo lo que quieres cuidar |
| 4 | **Página de pruebas** privada con un ejemplo de cada mejora (Sección 6): ¿sí? | Sí. Si no, se quita y el resto no cambia |

*(Sobre Carlota: he aplicado lo que dijiste — dibuja libre, sin pensar en este sistema —, y la tabla de "qué es nativo" queda como ayuda. Dime si quieres otra cosa.)*

---

## Glosario en una frase

| Término | Qué es |
|---|---|
| **Clase (CSS Class)** | Un nombre que se escribe en un campo del elemento para que el CSS sepa a cuál dirigirse |
| **Ficha** | La cabecera de comentario de cada bloque de CSS: cómo se usa, qué hace, de qué depende y con qué versión se probó |
| **Sello** | La primera línea del archivo de CSS: versión del archivo, fecha y versión de Avada probada |
| **Archivo maestro** | El archivo de GitHub que manda; Avada tiene una copia desplegada idéntica |
| **Traspaso** | El mensaje corto que una cuenta de construcción genera para pasar un problema de código a la cuenta de Código |
| **Hito** | Un momento fijado (fin de fase, actualización mayor de Avada, buzón lleno) en el que se revisa el sistema |

---

*Para la mayor gloria de Dios · tiritaito.com*

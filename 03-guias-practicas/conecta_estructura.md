# TIRITAITO.COM — Conecta cada día
**Cómo está montado el apartado, de dónde sale cada contenido, qué hay que hacer para el lanzamiento y qué queda pendiente**
*Redactado el 20 de noviembre de 2026 a partir de los informes de Álvaro (Ejército, Misa, Salmo), el informe de la cuenta de Código (selector de botones) y las respuestas de Carlitos · Entorno de referencia: Local (`tiritaito-real.local`), Avada 7.16.4*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Explica, en lenguaje sencillo, cómo funciona «Conecta cada día» en la web nueva: qué lleva, cómo se cambia de un contenido a otro, quién sube cada cosa y qué hay que tener en cuenta al pasar de Local a producción.

| Si necesitas... | Ve a este documento |
|---|---|
| Qué secciones tiene la web y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| Dónde viven los datos diarios (nombres de casillas, tokens, endpoints) | `00_CORE.md` Secciones 3 y 4 |
| Qué cambia entre Local y producción | `04_ENTORNO_LOCAL.md` |
| El CSS y el script del selector | `avada-custom-css.css` (fichas de cada bloque) |
| **Cómo está montado Conecta cada día y qué falta para lanzarlo** | **Este documento** |

---

## 1. Qué es y qué lleva

«Conecta cada día» es el apartado de la página de inicio con los contenidos que cambian a diario. Son **seis**, siempre en este orden:

1. **Ejército** — la tarjeta del día de la semana (imagen, vídeo, título e intenciones).
2. **Hora de la brisa**
3. **Mensaje de la Virgen**
4. **Misa** — lecturas del día y homilía.
5. **Salmo del día**
6. **Lenguas**

**Lo que NO está aquí** (por si alguien lo busca):
- **Tip y Promesa del día** y **Habla por la palabra** → están en la home, en «Dios te habla».
- **Elige tu santo** → sigue sin ubicación decidida; no se ha pensado dónde irá.

---

## 2. Cómo se ve y cómo funciona

### 2.1 Lo que ve la persona

Una fila de **seis botones** (píldoras: blancas con borde rojizo; la activa se pone roja). Al pulsar uno, aparece su contenido debajo y los demás se esconden. No se recarga la página. Al entrar, está abierta **Hora de la brisa**.

### 2.2 Por qué se hizo así

Se probó primero con el elemento «Tabs» de Avada, pero se rompía el diseño al meter bloques grandes dentro de cada pestaña (huecos blancos enormes, contenido pegado abajo). Por eso se cambió por un selector a medida: **6 botones + 6 bloques normales + un script muy pequeño** que enseña solo el bloque del botón pulsado. Cada bloque tiene todo su diseño nativo de Avada, sin las limitaciones de las pestañas.

### 2.3 Las piezas

| Pieza | Qué es | Nombre que lo une |
|---|---|---|
| Barra de botones | Un contenedor con 6 columnas, un botón en cada una | clase `tt-selector` |
| Cada botón | Botón de Avada, sin enlace | `tt-boton-` + nombre |
| Cada contenido | Un contenedor por sección | `tt-panel-` + nombre |
| Script | Un bloque de código en la última columna de la barra | — |

Los nombres son: `ejercito`, `brisa`, `virgen`, `misa`, `salmo`, `lenguas`. El nombre es lo que une cada botón con su bloque.

**Regla importante:** si algún día se añade o se quita una sección, hay que tocar **a la vez** tres sitios: el script, el CSS y los bloques. Si solo se toca uno, el selector se descuadra.

### 2.4 Cómo se comporta

- Si el script falla o el navegador no lo ejecuta, se ven **los seis bloques a la vez** (nunca una pantalla en blanco).
- Dentro del editor de Avada (Edit Live) también se ven los seis a la vez: es lo esperado, no un fallo.
- Funciona con teclado (Tab para moverse, Enter o espacio para abrir).
- Al cambiar de botón, los vídeos y audios del bloque que se esconde se **pausan** solos.
- Los vídeos de YouTube dentro de un bloque necesitan llevar `enablejsapi=1` para poder pausarse.

---

## 3. Una ficha por sección

### 3.1 Ejército

| | |
|---|---|
| **Qué muestra** | La tarjeta del **día de la semana**: imagen, vídeo (se abre en una ventana flotante y se para al cerrarla), título e intenciones. Debajo, la **oración final** (texto fijo, ya puesta). |
| **Cómo elige el día** | Hay siete columnas (lunes a domingo) y un script que enseña solo la de hoy, según la hora de Madrid. |
| **De dónde salen los datos** | De una página de ajustes de WordPress llamada **«Ejército — Contenido semanal»** (grupo «TT — Ejército — Semana»). Tiene 28 casillas: para cada día, título, intenciones, vídeo e imagen. |
| **Cada cuánto cambia** | Los siete días se rellenan una vez y se repiten cada semana; cambia como mucho una vez al año. |
| **Formato del vídeo** | Dirección completa de YouTube: `https://www.youtube.com/watch?v=…` (no vale `youtu.be`). |
| **Si un día no hay contenido** | Sin comprobar qué se ve; por eso hay que rellenar siempre los siete días. |

**Por qué no son fichas sueltas:** la idea inicial era siete fichas (un tipo de contenido propio), pero Avada no leía sus casillas dentro de las tarjetas. La página de ajustes sí se lee bien, así que se cambió. Las fichas y el grupo de casillas antiguos (`ejercito_dia`) **siguen sin borrar**; se pueden borrar cuando se dé por bueno el sistema nuevo.

**Cosas que aprendimos (para no repetir errores):**
- Un mismo identificador repetido en varias columnas rompe el selector: se copió al duplicar una columna y siempre se veía el lunes. Al duplicar columnas, cambiar el identificador.
- La condición de fecha nativa de Avada no filtró por día en Local, así que el día lo elige el script.
- Un elemento de pestañas de Avada no admite columnas con condición dentro.

### 3.2 Hora de la brisa, Mensaje de la Virgen y Lenguas

| | Hora de la brisa | Mensaje de la Virgen | Lenguas |
|---|---|---|---|
| **Qué se sube** | Texto y nombre del autor | Texto y fecha | Vídeo o audio de lenguas |
| **Quién lo sube** | El equipo, desde la app **Tiritaito for Creators** | Igual | Igual |
| **Cada cuánto** | A diario | A diario | A diario |
| **Dónde se guarda** | Página de ajustes «Devocional — Contenido Diario» | Igual | Igual |

- La **fecha de la Virgen es obligatoria**: la app no deja publicar sin ella.
- Avada lo lee con contenido dinámico; no hay código propio de estas tres.
- Si un día no se sube nada, se queda lo del día anterior (no se borra solo). El equipo debe subir contenido cada día.

### 3.3 Misa

Tiene dos partes que funcionan distinto.

**Lecturas (automáticas):** el equipo no hace nada. Un programa del servidor (`tt-misa-lecturas.php`) **consulta cada hora** a Evangelizo, comprueba si las lecturas guardadas son las de hoy (hora de Madrid) y, si no, las pide y las guarda. Primera lectura, Salmo, Segunda lectura (si hay) y Evangelio, cada una con su cita. Es «todo o nada»: si algo falla, no escribe nada y se queda lo anterior.

**Homilía (la sube el equipo):** texto y audio, desde la app. Se **vacía sola a las 48 horas** si no se renueva (si se reenvía exactamente lo mismo, la cuenta no se reinicia). Sin fecha, por decisión.

*Detalles que conviene tener presentes:**
- Las lecturas se guardan en la entrada **«Conecta cada Día»**. El programa la encuentra por su número (en Local es el 3572; **en producción será otro**, hay que indicárselo, ver 5.2).
- Las casillas están en dos grupos: «Misa — Lecturas» y «Conecta — Homilía». Sus reglas de ubicación deben apuntar a «Conecta cada Día». En una revisión de Álvaro, el grupo de lecturas estaba asignado a otra entrada («¿Qué hacemos?») y el editor enseñaba las casillas vacías aunque la web mostraba datos viejos. Se da por resuelto, pero al pasar a producción hay que comprobar que el grupo está asignado a la entrada correcta y que tras la primera sincronización se ve la fecha de hoy.
- Para probar a mano (solo administradores, en `/wp-admin/`): `?tt_misa_ahora=1` sincroniza hoy y `?tt_misa_tick=1` ejecuta el ciclo completo. El registro queda en la opción `tt_misa_log`.
- Las lecturas llegan de Evangelizo: hay que confirmar con ellos que el uso está permitido.

**Actualizado el 25 de noviembre de 2026**

- Las lecturas (primera, salmo, segunda y evangelio) y sus citas las trae un solo snippet, `tt-misa-lecturas.php` (**v1.1**, en `apps/v2/`), desde el Reader de Evangelizo. Lo hace una vez al día (hora de Madrid) y lo guarda en la entrada «Conecta cada Día».
- **Novedad v1.1:** el snippet guarda también el pie de fuente del texto bíblico («Extraído de la Biblia…») en la casilla `misa_fuente` (tipo Texto, grupo ACF «Misa — Lecturas»). La casilla es opcional: si no existe, todo sigue funcionando y se anota un aviso en `tt_misa_log`.
- **Crédito en la web:** bajo las lecturas hay un texto «Lecturas: Evangelizo.org» con enlace a `https://evangelizo.org`. Debajo, un segundo texto lee `misa_fuente` mediante Dynamic Content (ACF Text) y se oculta si está vacío (Conditional Rendering: `misa_fuente` no igual a vacío).
- **Permiso de Evangelizo:** decidido el 25 de noviembre de 2026 que no hace falta pedirlo ni enviar correo de cortesía. El Reader es un servicio público que Evangelizo documenta para incorporar sus lecturas en otras webs, y no hay condiciones de uso propias publicadas. Los derechos de la traducción bíblica pertenecen a su editor, no a Evangelizo.
- **Límites del Reader:** la fecha no puede superar los 30 días desde hoy (el snippet no pide más de 7). Se llama por `http://` desde el servidor, no desde el navegador del visitante.
- 🔲 **Pendiente:** Álvaro monta y prueba en Local la casilla `misa_fuente` y el crédito visible.

**En la lista de pendientes de producción:** quitar «permiso de Evangelizo».

### 3.4 Salmo del día

| | |
|---|---|
| **Qué muestra** | «Salmo N» y su audio. |
| **De dónde sale** | **Evangelizo** da el número del salmo de hoy; el audio es el episodio «Salmo N» del podcast **Salmos** (150 episodios, 6 temporadas, sin repetidos ni faltantes). |
| **Quién hace algo** | Nadie. Se recoge solo cada día. |
| **Dónde se guarda** | Página de ajustes «Salmo del día» (número, título, audio y fecha). |
| **Cuándo se actualiza** | Con la primera visita del día, cada hora si el servidor ejecuta tareas programadas, o cuando se visita la dirección `/wp-json/tiritaito/v1/salmo/sync`. |
| **Si Evangelizo falla** | Se queda el salmo anterior y se recupera cuando vuelve. Además guarda 7 días de adelanto. |
| **Para ver qué hay ahora** | `/wp-json/tiritaito/v1/salmo/hoy` |

**Comprobación de episodios:** el archivo `tt-salmo-verificar-episodios.php` (solo lectura) dice si falta o sobra algún «Salmo N» del 1 al 150. Conviene pasarlo antes del lanzamiento.

**Cosas conocidas:**
- Los días en que el Reader no da salmo (por ejemplo, un cántico) las casillas quedan vacías.
- El aviso `ultimo_error` sigue mostrando el error viejo tras recuperarse. Es solo cosmético.
- Etiquetas de temporada mal en Spotify para los salmos 141, 68, 43 y 105; solo afecta a la página de temporadas del podcast.
- **El audio no sonaba en Safari de Mac** (sí en Chrome). No se encontró la causa. Ver pruebas del lanzamiento (5.4).

---

## 4. Si un día falta algo (resumen)

| Sección | Qué pasa si no hay contenido nuevo |
|---|---|
| Ejército | Hay que tener los siete días rellenos; no hay nada diario que hacer |
| Brisa, Virgen, Lenguas | Se queda lo del día anterior |
| Misa — lecturas | Se queda lo anterior; el registro (`tt_misa_log`) avisa del error |
| Misa — homilía | Se vacía sola a las 48 h |
| Salmo | Se queda el salmo anterior |

---

## 5. Para el lanzamiento (Local → producción)

### 5.1 Qué cambia respecto a Local

| | Local | Producción |
|---|---|---|
| Dirección | `tiritaito-real.local` | `www.tiritaito.com` (y puede llevar `/blog/`, sin decidir) |
| Avada | 7.16.4 | **Sin comprobar** — hay que igualarla |
| Caché | Sin LiteSpeed | Con LiteSpeed |
| Tareas programadas | Funcionan | Sin comprobar si Raiola las permite |
| Número de la entrada «Conecta cada Día» | 3572 | Otro |

### 5.2 Hacer, en este orden

1. **Igualar la versión de Avada** de producción a la 7.16.4 (o la que se acuerde), porque el selector depende de elementos internos de Avada.
2. **Crear las casillas** en producción (grupos y páginas de ajustes: «Ejército — Contenido semanal», «Salmo del día», «Misa — Lecturas», «Conecta — Homilía», «Devocional — Contenido Diario»), con las reglas de ubicación apuntando a «Conecta cada Día».
3. **Instalar los dos programas** en Code Snippets, tipo PHP, «Run everywhere»: `tt-misa-lecturas.php` y `tt-salmo-del-dia.php`.
4. **Indicar el número de la entrada** «Conecta cada Día» de producción (opción `tt_conecta_page_id`).
5. **Pegar el CSS maestro** y el script del selector, y montar los bloques con sus clases.
6. **Cargar los datos reales** del Ejército (los de Local son de prueba) y comprobar que los vídeos tienen el formato correcto.
7. **Tareas programadas reales**: pedir a Raiola un cron, o un servicio que visite `/wp-json/tiritaito/v1/salmo/sync` a las **00:05 hora de Madrid**. Sin esto, el cambio de medianoche depende de que llegue una visita. Lo mismo para la sincronización de Misa.
8. **Caché (LiteSpeed):**
   - Excluir del minificado, combinado, aplazado y retraso de JavaScript el texto `data-tt-sel`; si no, los bloques pueden verse un instante antes de esconderse.
   - Comprobar que la caché se **vacía al cambiar de día**: la página lleva contenido por día y puede quedarse con el del día anterior (el Ejército en especial). Si no se vacía sola, excluir esa página de la caché de página.
   - Excluir `/wp-json/tiritaito/` (ya está decidido para el resto de la app).
9. **Cambios de la app** (dirección `WP_BASE`, token, PIN) según `04_ENTORNO_LOCAL.md` sección 5.
10. **Limpieza:** el CSS antiguo de pestañas (`#tt-conecta-tabs`) solo sirve si ese elemento Tabs sigue en la página; si ya no está, retirarlo. Borrar también el tipo de contenido antiguo del Ejército cuando se dé por bueno el sistema nuevo.

### 5.3 Después de actualizar Avada, siempre

El selector se apoya en nombres internos de Avada que no son oficiales (`fusion-builder-row`, `fusion-layout-column`, `fusion-button`, `fusion-button-text`). Tras cada actualización, revisar primero Conecta cada día.

### 5.4 Pruebas antes de abrir al público

- [ ] Ordenador, tablet y **móvil real**: solo se ve el bloque elegido y los botones caben (scroll horizontal si no).
- [ ] Teclado: Tab y Enter abren cada botón.
- [ ] Ejército: solo la tarjeta de hoy; el vídeo abre en ventana y se para al cerrar; cambia al día siguiente (y comprobar el sábado).
- [ ] Salmo: suena en Chrome **y en Safari de Mac y en un iPhone real**. Si no suena en Safari, afecta a los usuarios de iPhone y hay que resolverlo antes de lanzar (puede ser un problema del audio que sirve Spotify).
- [ ] Misa: tras la primera sincronización aparece la fecha de hoy; la homilía aparece al subirla y desaparece a las 48 h.
- [ ] Vídeo dentro de un bloque: se pausa al cambiar de botón.
- [ ] Con LiteSpeed activo: al día siguiente se ve el contenido nuevo, no el viejo.
- [ ] Sin JavaScript: se ven los seis bloques, no una página vacía.
- [ ] Quitar el relleno grande (150 px arriba y 210 px abajo) que quedó en algunas columnas de las pruebas del Ejército.

---

## 6. Pendientes y cosas por confirmar

| Pendiente | Quién |
|---|---|
| Versión de Avada en producción | Carlitos |
| ¿La web nueva va en la raíz o en `/blog/`? (afecta a todas las direcciones) | Carlitos |
| Tareas programadas en Raiola (¿bloquea `wp-cron.php`?) | Carlitos |
| Audio del Salmo en Safari / iPhone | Álvaro y Carlitos |
| Permiso de Evangelizo para usar lecturas y número del salmo | Carlitos |
| Que la caché no deje el día anterior | Carlitos |
| Borrar el tipo de contenido antiguo del Ejército y el CSS de pestañas viejo | Álvaro, cuando se confirme |
| Tamaño del archivo de CSS maestro (supera las 600 líneas; se planteó dividirlo) | Carlitos |
| Dónde irá «Elige tu santo» | Equipo |
| Salmo: etiquetas de temporada mal en Spotify (141, 68, 43, 105) y aviso `ultimo_error` cosmético | Menor, sin urgencia |
| Importador de vídeos de música: avisó «frenado, iba a retirar 22 de 64 fichas» (no se ha tocado; no es de Conecta, pero conviene mirarlo antes de producción) | Carlitos |

---

*Para la mayor gloria de Dios · tiritaito.com*

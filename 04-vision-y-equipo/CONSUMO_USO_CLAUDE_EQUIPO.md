# TIRITAITO.COM — Consumo de uso de Claude en el equipo
**Por qué Álvaro y Carlota se quedan sin uso ("créditos") con muy pocos mensajes, qué está confirmado y qué se puede hacer**
*Investigación de Proyecto 2 (Hno C) — 21 de septiembre de 2026*
*Fuentes: documentación oficial de Anthropic consultada hoy (Help Center y claude.com/pricing) · documentos de `tiritaito-docs` · cálculos propios, siempre marcados ⚠️*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde: **¿por qué las cuentas de Álvaro y Carlota agotan el uso tras un par de mensajes, cuando antes aguantaban conversaciones muy largas, y qué se puede hacer para evitarlo?**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Cómo se reparten las cuentas de Claude y su economía general | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Secciones 2 y 4 |
| El texto literal de instrucciones de cada Proyecto | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` |
| **Por qué se agota el uso, cómo comprobarlo y cómo reducirlo** | **Este documento** |

⚠️ **Este documento no decide nada ni cambia ningún otro documento.** Diagnostica, mide y propone. Lo que toque instrucciones de Proyectos o bases de conocimiento se aplica solo tras la confirmación de Carlitos (Sección 9).

**Marcadores:** ✅ confirmado en documentación oficial · ⚠️ estimación o inferencia mía · 🔲 no lo sé / sin comprobar · ❌ descartado.

---

## 1. Resumen

Lo que me llega es que dos personas agotan el uso con "un par de mensajes". Eso es demasiado poco para una cuenta de pago normal con Sonnet: apunta a algo extremo (⚠️ inferencia mía). Hay cuatro candidatos, y **con lo que sé desde aquí no puedo cerrar cuál es el principal** — faltan tres datos de sus cuentas (Sección 6 y 7).

**Confirmado (✅)**

1. **Fable 5 y Fable 5.1 no entran en el uso incluido de Pro ni de los asientos Team estándar.** Se pagan con créditos a tarifa de API desde el primer mensaje. El crédito gratuito de $100 con que Anthropic cubrió el cambio **caducó el 17 de septiembre de 2026** — cuatro días antes de esta consulta.
2. **Sonnet 5, Opus 5 y Fable 5.1 tienen ventana de 1 millón de tokens.** Una base de conocimiento de ~250k tokens cabe entera y viaja en cada mensaje; RAG solo entra cuando la base se acerca al límite. Este propio Proyecto 2 lo demuestra: sus 28 documentos llegan completos en cada mensaje.
3. **El uso no se mide en mensajes.** Depende de la longitud de la conversación, los adjuntos, el modelo, el esfuerzo, las herramientas y los conectores.

**Estimado (⚠️, ±30 %)**

4. La base que carga cada Proyecto ronda **250k tokens (P3/P7), 150k (P4/P6) y 110k (P5)**. Más de la mitad de P3 y P5 es prescindible.
5. **Nuestras propias instrucciones multiplican la salida:** cada cambio de la app pide HTML completo *y* copia `.matt` (~80k tokens de salida); el PHP, dos copias; los bocetos, archivo completo en cada vuelta.

**No sé (🔲)**

6. Plan de cada cuenta (Free / Pro / Team / Max), modelo elegido, el mensaje exacto que ven, y si reclamaron el crédito gratuito.

**Qué haría, en este orden:** (1) hoy, sin coste: Sonnet 5, esfuerzo medio, conectores apagados, tope de créditos, un chat por tarea; (2) dieta de las bases de conocimiento (~−50 %); (3) cambiar la salida: parches en vez de archivos enteros y `.matt` generado por script; (4) decidir plan **después** de medir, no antes.

---

## 2. Cómo se consume el uso hoy — hechos oficiales

| # | Hecho | Certeza | Fuente |
|---|---|---|---|
| 1 | El uso incluido se mide en una sesión de 5 horas y, en planes de pago, un límite semanal. Settings > Usage muestra ambas barras; el semanal distingue Opus del resto | ✅ | Usage limit best practices |
| 2 | No hay número fijo de mensajes. Pesan: longitud del mensaje, adjuntos, longitud de la conversación, herramientas (Research, web), modelo, esfuerzo, artifacts y tareas de varios pasos (ejecutar código, crear archivos) | ✅ | Usage limit best practices |
| 3 | El contenido de los Proyectos "se cachea y no cuenta contra tus límites cuando se reutiliza"; solo cuenta lo nuevo o no cacheado | ✅ | Usage limit best practices (⚠️ no dice cuánto dura la caché) |
| 4 | Los créditos de uso se cobran **a tarifas de la API** y se aplican "a todos los tokens procesados, incluido el contenido del Proyecto" | ✅ | Manage usage credits |
| 5 | Ventana de contexto en chat, planes de pago: **1M** en Fable 5.1, Opus 5 y Sonnet 5; 500K en Opus 4.x y Sonnet 4.6; 200K en el resto (p. ej. Haiku 4.5) | ✅ | Context window |
| 6 | RAG en Proyectos: solo planes de pago; se activa "cuando el conocimiento se acerca a la ventana"; "cuando es posible, se usa el contexto completo" | ✅ | RAG for projects |
| 7 | Fable 5 / 5.1: en **Pro y asientos Team estándar** van con créditos desde el primer mensaje; en **Max y asientos premium** entran en el plan hasta el 50 % del límite semanal y "gastan más rápido" | ✅ | Fable models on your plan |
| 8 | Esfuerzo Low/Medium "estiran el uso". En Fable 5.1 y Opus 5 el pensamiento **no se puede apagar** | ✅ | Change the model, effort... |
| 9 | Herramientas y conectores son "token-intensive". Recomiendan instrucciones cortas, quitar archivos no usados, apagar lo que no se necesite | ✅ | How do usage and length limits work |
| 10 | Todo (claude.ai, escritorio, móvil, Claude Code, Cowork) descuenta del mismo pool | ✅ | How do usage and length limits work |
| 11 | Free: Sonnet y Haiku, sin Opus ni Fable, hasta 5 Proyectos, sin RAG y sin créditos | ✅ | claude.com/pricing + RAG for projects |
| 12 | Conector GitHub: "los repositorios deben caber en la ventana de contexto"; recomienda empezar con un subconjunto | ✅ | Use the GitHub integration |

---

## 3. Qué ha cambiado — cronología

| Fecha | Qué pasó | Relevancia |
|---|---|---|
| 19-jul-2026 | Termina la promoción que incluía Fable 5 en Pro y Team estándar; pasa a créditos. Fable 5.1 **nunca** estuvo incluido | ✅ Quien use Fable dejó de gastar "plan" y pasó a gastar "saldo" |
| 20-jul → 2-ago | Ventana para reclamar $100 de crédito (Pro), o $100 por asiento estándar comprado hasta $2.500, agrupado, **solo el propietario** (Team) | ✅ Reclamar activa además los créditos de uso en la cuenta |
| 1-sep | Reorganización del equipo: Carlota hace todos los bocetos en HTML; Álvaro construye; aparece el Proyecto 9 | Propio |
| 6 → 17-sep | Crecen `CATALOGO_ELEMENTOS_AVADA.md` y `GUIA_AVADA_LOCAL.md`; aparece la carpeta `investigaciones/` | ⚠️ Si algún conector sincroniza **carpetas** enteras, cada archivo nuevo engorda la base de ese Proyecto sin que nadie lo decida |
| **17-sep, 23:59 PT** | **Caducan los créditos gratuitos**, se hubieran reclamado o no | ✅ |
| May → sep | Cambios de límites de Claude Code (5 h doblado el 6-may; el +50 % semanal sustituido por +25 % el 14-sep) | ❌ Afectan a Claude Code, no al chat de claude.ai. Datos de terceros, no oficiales |
| Horas punta | Anthropic no ha publicado cuáles son | 🔲 No sirve como explicación |

---

## 4. Qué viaja en cada mensaje

```
            CADA MENSAJE QUE SE ENVÍA
┌───────────────────────────────────────────────┐
│ 1. Sistema + herramientas + conectores        │  fijo (más si hay conectores)
│ 2. Instrucciones del Proyecto                 │  P3 ≈ 10k tokens ⚠️
│ 3. BASE DE CONOCIMIENTO del Proyecto          │  la parte gorda: 60k–300k ⚠️
│ 4. Historial de la conversación               │  crece cada turno (HTML, capturas)
│ 5. El mensaje nuevo                           │
└───────────────────────────────────────────────┘
                      ↓
        Claude razona (esfuerzo / pensamiento)
                      ↓
┌───────────────────────────────────────────────┐
│ 6. Respuesta (salida)                         │  lo que más pesa por token
└───────────────────────────────────────────────┘
```

La caché abarata el bloque 3 **mientras dura**. Cuánto dura en claude.ai no está documentado (🔲); en la API es de 5 minutos por defecto y la página de precios lo indica así.

### 4.1 Base estimada por Proyecto (⚠️ a ojo, ±30 %)

*Regla usada: 1 token ≈ 3,3 caracteres en Markdown en español, ≈ 3 en código, ≈ 2,7 en JSON. La medida real es el indicador de capacidad de cada Proyecto.*

| Proyecto | Qué carga (según `INSTRUCCIONES_PROYECTOS_CLAUDE.md`) | Estimado |
|---|---|---|
| P2 Investigación | Todo el repositorio, 28 documentos (comprobado: llega completo en el prompt) | ~300k |
| P3 / P7 Construcción | CORE, ENTORNO, GUIA, CATALOGO, METODOLOGIA, ALCANCE, VERSIONS, `apps/v2/` completa (HTML ×2, PHP ×2, changelog), `exports/` (3 archivos) | ~240k |
| P4 / P6 Bocetos | ORGANIZACION, CORE, REF_PODCAST, METODOLOGIA, GUIA, CATALOGO, ARQUITECTURA, ALCANCE, `avada-global-options.json` | ~150k |
| P5 Creators | CORE, VERSIONS, `apps/v2/` completa | ~110k |

### 4.2 Los archivos que más pesan (⚠️)

| Archivo | Tokens aprox. | ¿Quién lo necesita de verdad? |
|---|---|---|
| `tiritaito-creators-v2-01.html` + su copia `.matt.html` | ~40k **cada una** | Solo P5 (P3 no edita la app) |
| `GUIA_AVADA_LOCAL.md` | ~34k | P3; P4 solo unas secciones |
| `CATALOGO_ELEMENTOS_AVADA.md` | ~33k | P3 y P4 (Sección 5 bis) |
| `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | ~26k | P2. **P4 no lo necesita para hacer bocetos** |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | ~19k | Solo P2 |
| `avada-global-options.json` | ~18k | Solo sus valores concretos (colores, radios, tipografía) |
| `claves_conocidas.json` | ~14k | **Nadie:** es la línea base del saneador, no una referencia |
| `saneador-avada-options.html` | ~5k | **Nadie** en un Proyecto de Claude |
| Copias `.matt.*` (HTML, PHP, 3 md) | ~55k en total | Matt, en el repositorio; **ningún Proyecto** |
| `investigaciones/` (Music, Seminarios, Podcast) | ~30k | P2 |

---

## 5. Cuánto cuesta un mensaje

*Tarifas de API de claude.com/pricing, consultadas el 21-sep-2026. Se aplican a los **créditos** (Sección 2, hecho 4). Para el uso incluido en Pro/Team/Max no hay equivalencia en dólares publicada: úsese solo como orden de magnitud. Supone que claude.ai factura la caché como la API (⚠️ no confirmado).*

| Modelo | Entrada / salida ($ por millón) | Caché: leer / escribir |
|---|---|---|
| Haiku 4.5 | 1 / 5 | 0,10 / 1,25 |
| Sonnet 5 | 2 / 10 | 0,20 / 2,50 |
| Opus 5 | 5 / 25 | 0,50 / 6,25 |
| Fable 5.1 | 10 / 50 | 0,25 / 12,50 |

### 5.1 Mensaje normal de P3 (250k de entrada, 6k de salida)

| Modelo | Con la caché viva | Con la caché caducada | Mensajes con $100 (viva / caducada) |
|---|---|---|---|
| Haiku 4.5 | $0,06 | $0,34 | 1.818 / 292 |
| Sonnet 5 | $0,11 | $0,69 | 909 / 146 |
| Opus 5 | $0,28 | $1,71 | 364 / 58 |
| **Fable 5.1** | $0,36 | **$3,42** | 276 / **29** |

**Con $10 de saldo en Fable 5.1:** entre **3** mensajes (caché caducada) y **28** (caché viva). En Sonnet 5: entre 14 y 90. Con estos números, "un par de mensajes" es perfectamente compatible con Fable, un saldo pequeño y pausas largas entre mensajes.

### 5.2 Un cambio de la app en P5 (110k de entrada, ~80k de salida por HTML ×2)

| Modelo | Coste total | De eso, solo la salida | Con un parche (~4k de salida) |
|---|---|---|---|
| Sonnet 5 | $0,82 – $1,08 | $0,80 | $0,04 |
| Opus 5 | $2,06 – $2,69 | $2,00 | $0,10 |
| Fable 5.1 | $4,03 – $5,38 | $4,00 | $0,20 |

La salida es lo que más pesa y lo más evitable.

---

## 6. Hipótesis, ordenadas

### 6.1 Árbol de diagnóstico: qué mensaje ve la persona

```
¿Qué ve Álvaro / Carlota al parar?
│
├─ "Has alcanzado el límite de sesión / semanal"  (barra de Settings > Usage llena)
│     → se acabó el USO INCLUIDO del plan
│     → mirar H4, H5, H6, H7, H8 (peso de cada mensaje)
│
├─ "Añade créditos" / saldo a cero  (Settings > Usage > Usage credits)
│     → se acabó el SALDO de pago por uso
│     → mirar H1 y H2 primero; después H4–H6
│
├─ Selector con "Fable 5" o "Fable 5.1" en cuenta Pro / Team estándar
│     → cada mensaje gasta créditos desde el primero (H1)
│
└─ Plan Free
      → asignación mínima, sin Opus/Fable/RAG/créditos (H3)
```

### 6.2 Las hipótesis

| # | Hipótesis | Evidencia | Cómo comprobarla (≈2 min) | Si se confirma |
|---|---|---|---|---|
| **H1** | Usan **Fable 5 / 5.1** en Pro o Team estándar | ✅ Política oficial + la palabra "créditos" + fecha del 19-jul | Selector de modelo junto al botón de enviar; Settings > Usage > movimientos de créditos | Pasar a Sonnet 5; tope mensual; sin recarga automática |
| **H2** | Se **agotó o caducó el crédito gratuito** (caducó el 17-sep) | ✅ Fechas oficiales | Settings > Usage: saldo y caducidad. Si es **Team**: ¿reclamó el propietario antes del 2-ago? | Decidir si se recarga; ver Sección 8.3 |
| **H3** | La cuenta es **Free** | ⚠️ `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 2 anota una cuenta en Plan gratuito que no se ha identificado | Settings > Billing | Free no sirve para este flujo (Sección 8.3) |
| **H4** | La **base de conocimiento entera viaja en cada mensaje** | ⚠️ Estimación de la Sección 4; con ventana de 1M no salta RAG; P2 lo demuestra | Página del Proyecto: % de capacidad y si muestra el aviso de que Claude "buscará información concreta" (= RAG activo). Prueba A/B de la Sección 7 | Dieta (Sección 8.1) |
| **H5** | **Nuestras instrucciones multiplican la salida** | ✅ Texto literal: P5 "entrega el HTML completo" + copia `.matt.html`; P3 "snippet completo" + `.matt.php`; P4 boceto completo | Mirar tamaño de las últimas respuestas o archivos generados | Parches + script (Sección 8.2) |
| **H6** | La **caché caduca** durante las pausas | ⚠️ Álvaro construye en Avada entre mensajes; TTL de API = 5 min; el de claude.ai 🔲 | Prueba de las dos esperas (Sección 7, paso 4) | Trabajar en bloques; agrupar preguntas; base más pequeña abarata cada fallo |
| **H7** | **Conversaciones largas** con historial pesado (HTML, JSON, capturas pegados) | ✅ Cada turno reenvía el historial. Coincide con "antes eran muy largas" | Nº de mensajes del chat al llegar al límite; peso de adjuntos | Un chat por tarea + resumen de traspaso (Sección 8.4) |
| **H8** | **Modelo, esfuerzo, herramientas** | ✅ Opus 5 y Fable 5.1 piensan siempre; Opus tiene límite semanal propio; conectores, Research y web pesan; Claude Code, Cowork y Chrome comparten el pool | Menú del modelo (esfuerzo marcado); Settings > Capabilities y Conectores; barra semanal de Opus | Sonnet 5 + esfuerzo medio; apagar lo que no se use |

**Sobre "antes aguantaban mucho más" (⚠️, lo explicaría una combinación):** hasta el 19-jul Fable 5 estaba dentro del plan; los modelos con ventana de 200K obligaban a las mismas bases a funcionar con RAG (mucho menos texto por mensaje) y con 1M pueden cargarse enteras (🔲 no confirmado que el umbral de RAG dependa del modelo elegido); y desde el 1-sep el trabajo es más pesado (bocetos completos, entregas duplicadas).

**Descartado (❌):** los cambios de límites de Claude Code y la reducción en horas punta no afectan al chat de claude.ai; las cifras que circulan son de terceros.

---

## 7. Protocolo de medición — 30 a 40 minutos por cuenta

Objetivo: separar qué pesa cada cosa, con datos de sus cuentas y sin adivinar. Hace falta el **modelo fijo (Sonnet 5)** y el **mismo mensaje corto** ("Responde solo: ok") en todos los pasos. Anotar el % de la barra de sesión en Settings > Usage antes y después de cada uno.

| Paso | Qué hacer | Qué revela |
|---|---|---|
| 0 | Anotar plan (Settings > Billing), captura de Settings > Usage (sesión, semanal, créditos y movimientos) y de la página de cada Proyecto (barra de capacidad + aviso de RAG) | El punto de partida y H1–H4 |
| 1 | Chat nuevo **fuera de cualquier Proyecto** | Coste del sistema + herramientas + conectores |
| 2 | Chat nuevo **dentro** del Proyecto | Diferencia con el paso 1 = coste de instrucciones + base |
| 3 | Segundo mensaje **inmediato** en ese chat | Coste con la caché viva |
| 4 | Tercer mensaje tras **10 minutos** de espera | Si sube respecto al paso 3, la caché caduca (H6) |
| 5 | Repetir el paso 2 con **esfuerzo Alto** | Peso del esfuerzo |
| 6 | Repetir el paso 2 con **conectores encendidos** | Peso de las herramientas |
| 7 | Repetir el paso 2 con **Opus 5** y, si la cuenta lo permite, **Fable 5.1**. Con créditos activos, anotar el **saldo exacto** antes y después | Precio real por mensaje en dólares |

Con los pasos 1–4 se sabe qué parte del gasto es "base" y cuál es "caché caducada". Con el 7, cuánto sale una equivocación de modelo.

---

## 8. Remedios

### 8.0 Hoy, sin coste (10 minutos por cuenta)

- ✅ **Modelo:** Sonnet 5 para construcción y bocetos; Opus 5 solo si hace falta; **Fable 5.x solo con autorización de Carlitos.** Comprobarlo en cada chat nuevo.
- ✅ **Esfuerzo:** Medio para lo rutinario; Alto solo en análisis difíciles.
- ✅ **Créditos:** Settings > Usage > poner **tope mensual** y **desactivar la recarga automática**.
- ✅ **Conectores y herramientas** apagados en chats de construcción; que no busque en la web si no hace falta.
- ✅ **Un chat por tarea.** Al cerrar, pedir un resumen de 10 líneas y pegarlo en el primer mensaje del siguiente (el protocolo de P5 ya lo hace; conviene generalizarlo).
- ✅ **Agrupar preguntas** en un solo mensaje y no reenviar la misma captura varias veces.
- ⚠️ **Petición manual mientras no se cambien las instrucciones:** "no repitas el archivo entero; dame solo lo que cambia".

### 8.1 Dieta de las bases de conocimiento — la palanca más grande

*Ahorro estimado por Proyecto: ~50–60 % del bloque 3. Sin pérdida funcional según lo que piden las instrucciones de cada uno (⚠️ juicio mío, a revisar con quien use cada cuenta).*

| Proyecto | Quitar | Sustituir por | Ahorro aprox. |
|---|---|---|---|
| **P3 / P7** | HTML de la app (real y `.matt`), `.matt.php`, changelog de V2 — P3 solo necesita el **PHP real** | — | ~−90k |
| P3 / P7 | `avada-global-options.json`, `claves_conocidas.json`, `saneador-avada-options.html` | Un **resumen de valores reales** (≈3k tokens): colores por slot, radios, tipografías, breakpoints, lightbox, rendimiento. Si falta un valor, se adjunta el JSON en ese chat concreto | ~−34k |
| P3 / P7 | Carpeta `investigaciones/` si el conector sincroniza la carpeta `02-metodologia/` entera | Dejar solo `METODOLOGIA_CONSTRUCCION.md` | ~−30k |
| **P4 / P6** | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md`, `ARQUITECTURA_Y_ROADMAP.md`, `METODOLOGIA_CONSTRUCCION.md` | — (no se usan para dibujar bocetos) | ~−45k |
| P4 / P6 | `avada-global-options.json` | El mismo **resumen de valores reales** | ~−15k |
| **P5** | Copias `.matt.*` (HTML y PHP) | — (las genera un script, Sección 8.2) | ~−45k |
| Todos | `claves_conocidas.json` y `saneador-avada-options.html` | — | ~−19k |

**Objetivo orientativo:** P3 ≈ 100–120k · P4 ≈ 60–90k · P5 ≈ 60k.

**Segunda fase, más costosa:** trocear `GUIA_AVADA_LOCAL.md` y `CATALOGO_ELEMENTOS_AVADA.md` por módulos (mecánica de Avada · entorno Local · efectos visuales · histórico) para que cada Proyecto cargue solo lo suyo. Cuesta rehacer referencias cruzadas; conviene decidirlo con los números de la Sección 7 en la mano.

⚠️ **Cuidado con dos cosas:**
1. **Un conector por carpeta engorda solo.** Cada archivo nuevo del repositorio entra en los Proyectos que sincronizan esa carpeta. Preferir seleccionar **archivos concretos** (el propio Anthropic recomienda "empezar con un subconjunto").
2. **El resumen de valores puede desfasarse.** Debe llevar en cabecera la fecha del export de que deriva y decir que ante la duda manda el export (mismo criterio que ya rige en el proyecto).

### 8.2 Salida — lo más evitable

| Hoy (según las instrucciones) | Propuesta | Ahorro |
|---|---|---|
| **P5:** "Entrega el HTML actualizado completo" + copia `.matt.html` (~80k tokens de salida) | Parches `str_replace` (o Claude Code, ver 8.3). La copia `.matt.html` la genera **un script** al hacer commit | −90 %, y la copia deja de ser una "reconstrucción" |
| **P3:** snippet PHP completo + `.matt.php` (~11k) | El PHP completo se mantiene (decisión ya tomada: es un snippet único compartido). Solo la copia `.matt.php` pasa a script | ~−50 % |
| **P2:** copias `.matt.*` de los md cuando cambian CORE, ENTORNO o VERSIONS | Mantener (son pequeños y llevan redacción, no solo sustitución) | — |
| **P4 / P6:** boceto completo en cada vuelta | Primera versión completa sobre una **plantilla de boceto** con los tokens reales; después `str_replace` sobre el archivo ya creado en el chat; la versión final se entrega **una vez** | ⚠️ −60–80 % por iteración |

**Por qué un script para los `.matt` de HTML y PHP:**
- Solo sustituye un puñado de valores sensibles (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11.4) y añade la cabecera de aviso.
- Cuesta **cero tokens**.
- Es **más fiel** que hacerlo con Claude, que es justo lo que las instrucciones exigen ("sanitización fiel, nunca una reconstrucción aproximada").
- Elimina el "límite honesto" de la Sección 11.5.1 (la copia no se actualiza si se edita fuera de una sesión).
- Lo escribe Carlitos o Álvaro, no Proyecto 2 (no escribo código).

🔲 **Antes de contar con `str_replace` en P5:** hay que comprobar si en las cuentas de Álvaro los archivos del Proyecto son visibles como ficheros para las herramientas. **En este Proyecto 2 no lo son:** la carpeta de proyecto del entorno de ejecución está vacía y los documentos llegan dentro del prompt. Si tampoco lo son allí, el parche exige adjuntar el archivo al chat o usar Claude Code.

### 8.3 Plan y créditos — decidir después de medir

| Opción | Qué es | Precio (claude.com/pricing, 21-sep) | Qué resuelve | Qué no |
|---|---|---|---|---|
| Pro + dieta + Sonnet 5 | El plan actual, bien usado | $20/mes ($17 anual) por cuenta | La mayoría de los casos si la causa es H1, H4 o H5 | Depende del uso real |
| Pro + créditos con tope | Válvula de seguridad a tarifa de API | Según saldo; tope mensual configurable | Evita el bloqueo puntual | No baja el consumo |
| Team, asientos estándar | 2–150 asientos; **proyectos compartidos**; facturación central; **analítica de uso**; controles de gasto | $25/asiento/mes ($20 anual) | Una sola base compartida en vez de duplicados (P3/P7, P4/P6); ver quién gasta qué; presupuesto central | El uso sigue siendo por asiento; "más que Pro", sin cifra |
| Team premium / Max 5x | 5× el estándar / 5× Pro; Fable incluido hasta el 50 % del semanal | $125 ($100 anual) / desde $100 | Trabajo continuo durante el día | Fable gasta el semanal más rápido |
| Free | — | $0 | Nada útil para este flujo | Sin RAG, sin créditos, sin Opus/Fable |

⚠️ **Cálculo de referencia, no una propuesta:** si las 8 cuentas actuales fueran todas Pro, cuestan $136–160/mes. Tres o cuatro asientos Team estándar cuestan $60–100/mes. `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` recoge que las cuentas de repuesto nacieron precisamente como parche de capacidad; un Team con proyectos compartidos ataca esa causa. No lo recomiendo sin saber qué plan tiene cada cuenta hoy.

⚠️ **No cambiar de plan antes de la medición.** Si la causa es H1 (Fable) o H4/H5, un plan más caro solo tapa el problema.

**Para P5 (app Creators):** evaluar **Claude Code de escritorio**. Lee solo lo necesario, edita por diferencias y trabaja sobre el repositorio local, sin pegar ni copiar archivos. Comparte pool con el chat (✅). 🔲 Sin probar en este equipo.

### 8.4 Hábitos de conversación (para una guía de una página)

| Hábito | Por qué |
|---|---|
| Un chat por tarea; cerrar con resumen de 10 líneas | Cada turno reenvía todo el historial |
| Agrupar preguntas en un solo mensaje | Cada mensaje vuelve a cargar el contexto |
| No pegar archivos que ya están en el Proyecto; nombrar el documento y la sección | El contenido del Proyecto ya está ahí |
| Trabajar en bloques continuos, sin pausas largas | La caché puede caducar (H6) |
| Pedir cambios como "solo el trozo modificado" | La salida es lo más caro por token |
| Mirar Settings > Usage al empezar y al terminar la jornada | Detectar el problema el mismo día |

---

## 9. Qué documentos habría que actualizar

*Regla 7 de las instrucciones de este Proyecto. **Nada de esto está aplicado.** Necesita confirmación de Carlitos; los bloques de instrucciones se redactan solo después, siguiendo el circuito de la Sección 2.3.1 de `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md`.*

| Documento | Sección | Qué cambiaría |
|---|---|---|
| `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | 4 "Economía de tokens" | Está desactualizada: omite el cambio de Fable a créditos (19-jul), las ventanas de 1M que retrasan RAG, el peso de la salida, Settings > Usage y los créditos de uso. Añadir esta información y el enlace a este informe |
| `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | 2 y 9 | Anotar el **plan real** de cada cuenta (hoy figura una en Free sin identificar) |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | P5 | Sustituir "entrega el HTML completo" y la copia `.matt.html` por parches; `.matt` por script |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | P3 y P7 (Sección 9 del bloque) | `.matt.php` por script; el PHP completo se mantiene |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | P4 y P6 | Bocetos: plantilla base + `str_replace` en las iteraciones; una sola entrega final |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | P2 | Limitar la generación de `.matt.*` a los md; el HTML/PHP, por script |
| `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | P3, P4 | Las referencias a `avada-global-options.json` pasarían al resumen de valores |
| `README.md` | Estado global | Fila nueva: consumo de uso de Claude |
| `03-guias-practicas/` | (archivo nuevo) | Resumen de valores reales de Avada, derivado del export |

⚠️ **Recordatorio de la Sección 11.7 de ORGANIZACION:** cualquier cambio de instrucciones tiene que repegarse a mano en claude.ai; subir el documento a GitHub no lo aplica.

---

## 10. Lo que la documentación oficial no aclara (🔲)

| # | Qué falta | Por qué importa |
|---|---|---|
| 1 | Cuánto dura la caché en claude.ai | Decide si H6 es real. Se mide con el paso 4 de la Sección 7 |
| 2 | Cómo pesan entrada y salida en el uso **incluido** (no en créditos) | Sin ello no se puede convertir los dólares de la Sección 5 en "% de sesión" |
| 3 | Si el umbral de RAG depende del modelo elegido (la ventana es 200K, 500K o 1M según el modelo). Un reporte de comunidad sin confirmar dice que RAG se activa por **número de archivos**, no por tamaño | Explicaría por qué "antes iba" y ahora no |
| 4 | Si la compactación automática de conversaciones largas descuenta uso. **Dos artículos oficiales se contradicen** (uno dice que consume más; el otro, que no cuenta) | Afecta a los chats largos con ejecución de código |
| 5 | Si los archivos de un Proyecto son visibles como ficheros para las herramientas de código | Decide si `str_replace` sobre archivos del Proyecto es viable |
| 6 | Cuánto pesa Fable / Opus contra el uso incluido | Anthropic dice solo "más rápido"; las cifras que circulan son de terceros |

---

## 11. Próximos pasos y preguntas abiertas

### Próximos pasos

1. **Álvaro y Carlota, cada cuenta que usen (≈10 min):** enviar a Carlitos — plan (Settings > Billing); captura de Settings > Usage (sesión, semanal, créditos, movimientos); captura del selector de modelo con el esfuerzo; texto exacto del aviso de límite; nº de mensajes del chat donde pararon; captura de la página de cada Proyecto (barra de capacidad + aviso de RAG).
2. **Carlitos:** confirmar si son cuentas individuales o asientos de un Team; si Team, si el propietario reclamó el crédito antes del 2-ago.
3. **Todos, hoy:** aplicar la Sección 8.0 en cada cuenta.
4. **Una cuenta (Álvaro o Carlota), 30–40 min:** ejecutar el protocolo de la Sección 7.
5. **Carlitos:** comprobar si los conectores de GitHub de P3/P4/P6/P7 apuntan a **archivos** o a **carpetas**.
6. **Proyecto 2:** con la medición hecha y tu confirmación, preparar la lista exacta de archivos por Proyecto (Sección 8.1) y el resumen de valores reales.
7. **Proyecto 2:** tras confirmación, redactar los bloques de instrucciones nuevos y actualizar `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 4 (Sección 9).
8. **Carlitos o Álvaro:** hacer el script de los `.matt` de HTML y PHP.
9. **Carlitos:** decidir plan y presupuesto (Sección 8.3), con los datos ya medidos.
10. **Álvaro:** probar Claude Code de escritorio con P5.

### Preguntas abiertas

| # | Pregunta | Bloquea a | Quién decide |
|---|---|---|---|
| 1 | ¿Qué plan y qué modelo tiene cada cuenta de Álvaro y Carlota? | Casi todo | Álvaro, Carlota |
| 2 | ¿Qué mensaje exacto ven al parar ("límite de sesión/semanal" o "añade créditos")? | Elegir entre H1–H2 y H4–H8 | Álvaro, Carlota |
| 3 | ¿Reclamaron el crédito gratuito? ¿Son cuentas individuales o asientos de un Team? | H2 | Carlitos |
| 4 | ¿Los conectores de GitHub apuntan a archivos o a carpetas? | Sección 8.1 | Carlitos |
| 5 | ¿Hay saldo de créditos con recarga automática activada en alguna cuenta? | Riesgo de gasto | Carlitos |
| 6 | ¿Se acepta que los `.matt` de HTML y PHP los genere un script y no Claude? | Sección 8.2 | Carlitos |
| 7 | ¿Se aprueba trocear GUIA y CATALOGO por módulos, sabiendo que cuesta rehacer referencias cruzadas? | Segunda fase de la dieta | Carlitos |
| 8 | ¿Qué presupuesto mensual hay para Claude? | Sección 8.3 | Carlitos |
| 9 | ¿Se evalúa Claude Design para los bocetos de Carlota (¿comparte pool? ¿exporta HTML con nuestros tokens?)? Sin verificar | Posible ahorro en bocetos | Carlota, Carlitos |

---

## 12. Fuentes

**Oficiales (Anthropic), consultadas el 21-sep-2026:**
- Usage limit best practices — https://support.claude.com/en/articles/9797557-usage-limit-best-practices
- How do usage and length limits work? — https://support.claude.com/en/articles/11647753-how-do-usage-and-length-limits-work
- Retrieval augmented generation (RAG) for projects — https://support.claude.com/en/articles/11473015-retrieval-augmented-generation-rag-for-projects
- How large is the context window on paid Claude plans? — https://support.claude.com/en/articles/8606394-how-large-is-the-context-window-on-paid-claude-plans
- Change the model, effort, and thinking settings — https://support.claude.com/en/articles/8664678-change-the-model-effort-and-thinking-settings
- Manage usage credits for paid Claude plans — https://support.claude.com/en/articles/12429409-manage-usage-credits-for-paid-claude-plans
- Claude Fable models on your plan — https://support.claude.com/en/articles/15424964-claude-fable-models-on-your-plan
- Claude Fable 5 one-time free credits promotion — https://support.claude.com/en/articles/15862783
- Use the GitHub integration — https://support.claude.com/en/articles/10167454-use-the-github-integration
- Plans & Pricing — https://claude.com/pricing

**No oficiales, usadas solo para descartar hipótesis (❌) o señalar lagunas (🔲):**
- Cambios de límites de Claude Code (terceros): ai-toolbox.co, morphllm.com, explainx.ai
- Reporte de comunidad sobre RAG por número de archivos: github.com/anthropics/claude-code/issues/25759 (sin confirmar)

**Del propio repositorio:** `INSTRUCCIONES_PROYECTOS_CLAUDE.md`, `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md`, `README.md`, `apps/v2/*` (incluidas las copias `.matt.*`). Los tamaños en tokens son **estimaciones mías** sobre el contenido tal como llega a este Proyecto; la medida real es el indicador de capacidad de cada Proyecto en claude.ai.

---

*Para la mayor gloria de Dios · tiritaito.com*

# TIRITAITO.COM — Organización del Equipo y Herramientas
**Roles, mapa de Proyectos de Claude, GitHub, economía de tokens, WPMobile.app, Search Console, y colaboración externa**
*Fusiona: CONFIGURACION_PROYECTOS_CLAUDE_Y_GITHUB.md (autoridad sobre el mapa de proyectos) + INFORME_ESTRATEGICO_2026_1.md (Parte 6.1, 6.3) + INVESTIGACION_HERRAMIENTAS_TRABAJO_2026.md (Partes 1, 4, 5)*
*Verificado contra support.claude.com — julio 2026 · Instrucciones del Proyecto 3 ampliadas con criterio de Responsive proactivo — 13 julio 2026 · Ampliadas con criterio de previsualización con bocetos — 14 julio 2026*
*Reestructurado 2 de agosto de 2026: el texto literal de instrucciones de cada Proyecto se extrae a `INSTRUCCIONES_PROYECTOS_CLAUDE.md` (más fácil de copiar/pegar sin mezclar con contexto); se absorbe el contenido de `COLABORACION_EXTERNA_CODEX.md` como Sección 11, en vez de mantenerlo como tercer documento independiente*
*Audiencia: Carlitos (coordinación transversal) · cada dueño de cuenta para su propia sección*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde: **¿quién hace qué, en qué cuenta de Claude, qué herramientas de apoyo (GitHub, Search Console) hay que mantener, y cómo se coordina con colaboradores externos?** Es el documento de coordinación transversal del proyecto — no de contenido ni de Avada, y ya no contiene el texto literal de instrucciones a copiar (eso vive aparte, ver tabla).

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Cómo funciona Avada / Local técnicamente | `GUIA_AVADA_LOCAL.md` |
| Qué elemento de Avada resuelve una necesidad de contenido concreta | `CATALOGO_ELEMENTOS_AVADA.md` |
| Dónde construir una pieza de contenido decidida | `METODOLOGIA_CONSTRUCCION.md` |
| Qué migrar de la web vieja | `MIGRACION_CONTENIDO.md` |
| Visión general, FODA, fases del proyecto | `ARQUITECTURA_Y_ROADMAP.md` |
| Cómo subir la app a GitHub, V1 vs V2, changelog | `TIRITAITO_FOR_CREATORS_VERSIONS.md` |
| **El texto exacto a copiar y pegar en las Instrucciones personalizadas de cada Proyecto** | **`INSTRUCCIONES_PROYECTOS_CLAUDE.md`** |
| **Quién hace qué, en qué Proyecto de Claude, cómo se coordina el equipo, y el sistema de colaboración externa (Matt/Codex)** | **Este documento** |

---

## ⚠️ Nota de reconciliación — dos mapas de cuentas que no coincidían

Al revisar los documentos originales, `INFORME_ESTRATEGICO_2026_1.md` (Parte 6.2) y `CONFIGURACION_PROYECTOS_CLAUDE_Y_GITHUB.md` (Parte 3) describían **dos repartos distintos de las 7 cuentas de Claude**, sin que ninguno de los dos documentos marcara al otro como sustituido.

**Este documento adoptó `CONFIGURACION_PROYECTOS_CLAUDE_Y_GITHUB.md` como versión válida** en su primera redacción. El reparto de `INFORME_ESTRATEGICO` queda archivado en `historico/` como referencia de cómo evolucionó la decisión, no como alternativa vigente.

### ✅ Actualización directa de Carlitos (misma fecha) — reemplaza también a `CONFIGURACION_PROYECTOS_CLAUDE_Y_GITHUB.md` en tres puntos

1. **Proyecto 3 (Backend y Snippets) + Proyecto 3B (Maquetación y Avada) se fusionan en un solo proyecto** — dos proyectos separados para el mismo entorno (Local/Web Nueva) generaba confusión sin aportar valor real.
2. **El Proyecto 7 (Datos y Métricas, Hna MF) no se va a crear.** Cancelado, no pospuesto.
3. **Regla explícita que no estaba escrita hasta ahora: cada proyecto vive en una cuenta distinta** — no varios proyectos compartiendo una misma cuenta. Con la fusión y la cancelación, el número de proyectos activos/planeados coincide exactamente con las 7 cuentas disponibles.

**Estado confirmado por Carlitos en esta misma conversación:** Web Vieja, Investigación (este proyecto), Diseño (Hna C), Creators, y el Web Nueva ya fusionado están **configurados y en uso**. Solo quedan pendientes la cuenta de Repuesto (sin crear) y WPMobile.app (sin asignar).

---

## 1. Roles del equipo

| Persona | Rol | Responsabilidad principal | No toca |
|---|---|---|---|
| Carlitos | Coordinador del sistema técnico | Vela porque el sistema técnico del proyecto (cuentas de Claude, GitHub, documentación, infraestructura) funcione bien y de forma coherente. Mantiene el README actualizado | Decisiones de producto |
| Hno A | Desarrollador principal | PHP, JS, REST API, snippets complejos, Avada/Local, Tiritaito for Creators | Decisiones de contenido |
| Hno C | Investigación y documentación | Investiga, documenta, valida conceptos | Código en producción |
| Hna C | Cerebro del equipo — dirección, diseño y coordinación | Coordina al equipo en el día a día, define alcance, criterio visual, decisiones de producto, Avada visual. Con acceso técnico para decidir bien | Código en desarrollo |
| Hna MF | Datos y métricas | Google Search Console, análisis de uso, propone mejoras basadas en datos | — |
| Editores (4) | Contenido | Subir/editar su contenido en sus secciones | Todo lo demás |
| Matt | Colaborador externo | Trabaja con Codex (IA de OpenAI) directamente sobre `tiritaito-docs`, sobre archivos sin dato sensible y sobre las copias `.matt.*` de los sensibles. Ver Sección 11 | Archivos oficiales sensibles — nunca los ve ni los edita |

**✅ Actualización directa de Carlitos (julio 2026), a petición de Hna C:** la coordinación se divide en dos capas — Carlitos coordina que el *sistema* funcione (herramientas, cuentas, documentación), Hna C coordina al *equipo* (personas, decisiones, trabajo del día a día). Esto no cambia la autoridad de Carlitos sobre la arquitectura de documentación — mantener el README actualizado es, en la práctica, la forma concreta en la que ejerce esa autoridad — es una división de qué se coordina, no una cesión de esa autoridad concreta.

---

## 2. Mapa de Proyectos de Claude — versión resuelta (actualizada 26 julio 2026)

**Regla confirmada:** cada proyecto vive en su propia cuenta — no se comparten cuentas entre proyectos.

⚠️ **Corrección de conteo (26 julio 2026):** este documento decía "7 cuentas, una por rol", asumiendo una sola cuenta de repuesto. Carlitos confirma que **ya hay dos cuentas de repuesto creadas y configuradas para el Proyecto 3** — no una. El total pasa de 7 a **8 cuentas**. No es un error de este documento tan grave como para reescribir toda la lógica (la regla "una cuenta = un proyecto" se mantiene; simplemente el proyecto "Web Nueva" tiene ahora dos copias de repuesto en vez de una), pero el número había quedado escrito como si fuera fijo y ya no lo es — queda corregido aquí.

| # | Proyecto | Cuenta | Rol | Estado | Instrucciones |
|---|---|---|---|---|---|
| 1 | Web Vieja (Mantenimiento) | Hno A | Solo bugs críticos, modo supervivencia | ✅ Configurado — ver nota de cierre en Sección 2.1 | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 1 |
| 2 | Investigación | Hno C | Documentos y diagramas claros para el equipo | ✅ Activo — es este proyecto | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 2 |
| 3 | **Web Nueva** (fusión de Backend+Snippets y Maquetación+Avada) | Hno A | Construcción técnica y visual completa en Local, incluida la recomendación de elementos de Avada a partir de `CATALOGO_ELEMENTOS_AVADA.md` (ampliado 11 agosto 2026, ver Sección 3) | ✅ Configurado | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 3 |
| 4 | Diseño y Avada | Hna C | Decisiones de producto y visión visual, sin código | ✅ Configurado | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 4 |
| 5 | Tiritaito for Creators | Hno A | PWA de editores — V1 (web vieja) y V2 (web nueva). Ver Sección 2.2 para el límite exacto de su ámbito frente al Proyecto 3 | ✅ Configurado — actualizado con V1/V2 | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 5 |
| 6 | Web Nueva — Repuesto A (copia idéntica del Proyecto 3) | Cuenta de repuesto | Continuidad si la cuenta principal de Hno A agota su límite | ✅ Creada y configurada (26 julio 2026) | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 6 |
| 7 | Web Nueva — Repuesto B (copia idéntica del Proyecto 3) | Cuenta de repuesto | Segunda continuidad — 🔲 motivo de tener dos repuestos en vez de uno sin documentar, preguntar a Carlitos si hace falta anotarlo | ✅ Creada y configurada (26 julio 2026) | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 7 |
| 8 | WPMobile.app | Sin asignar | Desarrollo de la app cuando se active | 🔵 Pendiente | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 8 (sin redactar) |

**~~Datos y Métricas (Hna MF)~~ — cancelado, no se va a crear como proyecto de Claude.** Si Hna MF necesita el checklist de Search Console, ver Sección 8 — queda como referencia sin un proyecto dedicado.

**Cuentas usadas:** 7 de 8 configuradas y en uso. Queda 1 pendiente (WPMobile.app).

**Nota sobre Matt:** Matt no tiene una cuenta de Claude ni un Proyecto propio en este mapa — trabaja con Codex, su propia herramienta, directamente sobre el repositorio de GitHub. No aplica el sistema de "una cuenta por proyecto" de esta sección. Ver Sección 11.

⚠️ **Nota de capacidad, añadida 11 agosto 2026:** una de las cuentas que participó en la ronda de Avada Global Options aparece en Plan gratuito en al menos una captura compartida por Carlitos. Las cuentas gratuitas no expanden memoria sola cuando la base de conocimiento crece (Sección 4) — con `CATALOGO_ELEMENTOS_AVADA.md` sumado a lo que ya carga el Proyecto 3, conviene confirmar el tipo de plan de cada cuenta activa antes de seguir ampliando su base de conocimiento. No se ha confirmado todavía si esa cuenta es una de las numeradas en la tabla de arriba o una cuenta de prueba aparte.

### 2.1 V1 y Proyecto 1 — fecha de caducidad conocida

**Decisión de Carlitos (26 julio 2026): la Web Vieja (V1) desaparece en cuanto la Web Nueva sea oficial.** Esto significa que el Proyecto 1 (Web Vieja) y la mitad "V1" del Proyecto 5 (Tiritaito for Creators) tienen una vida útil limitada y conocida, no indefinida. No hace falta actuar ahora — se anota aquí para que en la reunión de lanzamiento no se olvide: en ese momento, Proyecto 1 se puede archivar o reconvertir, y `TIRITAITO_FOR_CREATORS_VERSIONS.md` debe marcar V1 como retirado.

### 2.2 Proyecto 3 vs Proyecto 5 — dónde termina uno y empieza el otro

Con el snippet PHP del endpoint central viviendo ahora dentro de `apps/v2/` (Sección 5.4) y con el protocolo de traspaso de prompts entre proyectos (`INSTRUCCIONES_PROYECTOS_CLAUDE.md`, Proyecto 3 punto 0.3), conviene dejar el límite explícito, no implícito:

| | Proyecto 3 (Web Nueva) | Proyecto 5 (Tiritaito for Creators) |
|---|---|---|
| Construye/edita el snippet PHP del endpoint central | ✅ Sí, es el único que lo toca | ❌ Nunca — ni siquiera para depurar un bug de la app |
| Construye/edita el HTML de la app | ❌ Nunca | ✅ Sí, es el único que lo toca |
| Decide qué campos ACF existen y de qué tipo | ✅ Sí | ❌ No — recibe la decisión ya tomada |
| Si la app necesita un dato nuevo del servidor | Recibe la petición y construye el endpoint | Describe qué necesita (campo, tipo, verbo REST) y lo traslada a Proyecto 3 — nunca inventa el PHP por su cuenta |
| Construye en Avada (Fusion Builder, Dynamic Content) | ✅ Sí | ❌ Nunca |
| Recomienda qué elemento de Avada usar para una necesidad | ✅ Sí, a partir de `CATALOGO_ELEMENTOS_AVADA.md` | ❌ No aplica — no trabaja con Avada |

Si en algún momento una tarea no encaja claramente en ninguna de las dos filas, es señal de pararse y preguntar antes de que cada proyecto construya una solución distinta para el mismo problema.

---

## 3. Instrucciones de cada proyecto — dónde viven ahora

⚠️ **Cambio de estructura (2 de agosto de 2026):** el texto literal de "Instrucciones personalizadas" de cada Proyecto ya no vive dentro de este documento — se movió a **`INSTRUCCIONES_PROYECTOS_CLAUDE.md`**, un documento dedicado solo a eso: un bloque de texto limpio por Proyecto, sin tablas ni explicación alrededor, fácil de seleccionar entero y pegar en claude.ai.

**Por qué se hizo este cambio:** mezclar el texto a copiar con el contexto explicativo de este documento hacía que copiar y pegar fuera propenso a error (fácil llevarse de más o de menos), y ya provocó una discrepancia real — ver Sección 11.7 para el registro completo de ese episodio.

Este documento conserva, para cada Proyecto: la cuenta que usa, su rol, su base de conocimiento, y cualquier decisión de coordinación que lo afecte — pero no el texto de instrucciones en sí. Consulta la tabla de la Sección 2 para el enlace directo a la sección correspondiente de `INSTRUCCIONES_PROYECTOS_CLAUDE.md`.

⚠️ **Ampliación pendiente de repegar (11 agosto 2026):** tras el cierre de la ronda de Avada Global Options, el bloque del Proyecto 3 en `INSTRUCCIONES_PROYECTOS_CLAUDE.md` creció con las Secciones 0.5 (consulta obligatoria de `CATALOGO_ELEMENTOS_AVADA.md` antes de recomendar) y 0.6 (construcción en los distintos niveles de Avada Live Builder — elemento, columna, container/sección, entrada, página — y cuándo preguntar por código en vez de decidirlo en silencio). Esto **no está aplicado todavía en claude.ai** — sigue el mismo patrón de siempre: subir el documento a GitHub actualiza la base de conocimiento, pero las instrucciones personalizadas solo cambian si alguien las repega a mano.

---

## 4. Economía de tokens en cuentas gratuitas — verificado

✅ **Confirmado en support.claude.com (julio 2026):** las cuentas gratuitas pueden crear un máximo de **5 Proyectos**. La expansión automática de memoria (RAG) **solo está disponible en planes de pago** — en cuentas gratuitas, cuando el contenido del Proyecto se acerca al límite de contexto, no se expande solo; hay que quitar documentos o crear un Proyecto nuevo.

*Fuente: support.claude.com/en/articles/9517075-what-are-projects*

⚠️ **Relevancia directa, añadida 11 agosto 2026:** con `CATALOGO_ELEMENTOS_AVADA.md` sumándose a la base de conocimiento ya cargada del Proyecto 3 (`00_CORE.md`, `04_ENTORNO_LOCAL.md`, `GUIA_AVADA_LOCAL.md`, `METODOLOGIA_CONSTRUCCION.md`, `ALCANCE_WEB_NUEVA.md`, `TIRITAITO_FOR_CREATORS_VERSIONS.md`, la carpeta `apps/v2/` completa), y con la intención declarada de ampliar el catálogo con el tiempo hasta cubrir los casi 100 elementos activos de Avada (no solo los ~24-50 ya revisados), esta limitación deja de ser teórica si la cuenta que lo recibe resulta ser gratuita — ver el aviso de la Sección 2.

**Reglas de economía de tokens (prácticas oficiales, ya aplicadas por el equipo):**

| Práctica | Por qué funciona | Ya lo hace el equipo |
|---|---|---|
| Subir documentos base una sola vez al Proyecto | El contenido queda cacheado — reutilizarlo cuesta mucho menos que repetirlo | ✅ Con los documentos de `tiritaito-docs` |
| Instrucciones del Proyecto cortas y generales | El detalle específico va en el chat, o se remite a un documento | ✅ Aplicado — además, ahora el texto literal vive en un documento dedicado (Sección 3). Principio reforzado en las Secciones 0.5-0.6 del Proyecto 3: el detalle de "qué elemento sirve para qué" vive en `CATALOGO_ELEMENTOS_AVADA.md` (se sincroniza solo por GitHub), las instrucciones solo dicen cómo usarlo |
| Agrupar varias preguntas en un solo mensaje | Cada mensaje nuevo reprocesa toda la conversación anterior | Mejorable |
| Empezar una conversación nueva cada cierto número de intercambios | Una conversación larga es más cara por mensaje nuevo que una recién empezada | ✅ Ya se hace |
| Pedir un resumen y pegarlo como primer mensaje de un chat nuevo | Evita cargar todo el historial viejo | ✅ Es el protocolo de sesión de Tiritaito for Creators |
| Eliminar archivos obsoletos de la base de conocimiento | Los archivos viejos compiten por el mismo espacio fijo | Aplicar al migrar a `tiritaito-docs` — retirar duplicados |

---

## 5. GitHub — el porqué, la estructura y el paso a paso

### 5.1 El porqué

El problema que resuelve: evitar que dos sitios (cuenta principal y cuenta de repuesto) trabajen con versiones distintas del mismo documento sin que nadie se dé cuenta. Con GitHub, hay una sola fuente de verdad — el documento vive en un repositorio, y cada Proyecto de Claude simplemente lo lee de ahí.

### 5.2 Lo que GitHub SÍ hace — verificado

✅ **Confirmado en support.claude.com (julio 2026):**
- Se pueden elegir archivos y carpetas concretos de un repositorio — no hace falta traerlo entero
- Ese contenido se añade a la base de conocimiento del Proyecto
- Se puede ajustar qué archivos se leen en cualquier momento
- Se pueden conectar varios repositorios a la vez, si caben en la ventana de contexto

*Fuente: support.claude.com/en/articles/10167454-use-the-github-integration*

### 5.3 Lo que GitHub NO hace — verificado, con matiz importante

✅ **Confirmado, textual de la documentación oficial:** *"No recuperamos el historial de commits, PRs, u otros metadatos."* Solo se sincronizan nombres y contenido de archivos de una rama concreta. Hay que pulsar **"Sync now"** para traer los cambios más recientes — no ocurre solo.

⚠️ **Matiz:** existen reportes documentados de que el conector a veces muestra **"Conectado"** sin que el contenido esté realmente actualizado — no es lo habitual, pero ha ocurrido. Probarlo antes de confiar en él para algo delicado (como `04_ENTORNO_LOCAL.md`).

**Recordatorio directo:** cualquier actualización hecha aquí a un documento **no llega sola** al Proyecto correspondiente — necesita: (1) que el archivo actualizado se suba a GitHub, y (2) que el dueño de la cuenta pulse "Sync now" antes de la próxima sesión donde importe. Sin esos dos pasos, se sigue trabajando con la versión antigua aunque el repositorio ya esté al día. Esto aplica también a las instrucciones personalizadas de cada Proyecto: subir `INSTRUCCIONES_PROYECTOS_CLAUDE.md` a GitHub actualiza la base de conocimiento, pero **las instrucciones personalizadas hay que repegarlas a mano** en Configuración de cada Proyecto — no se sincronizan solas nunca, vengan o no de GitHub.

⚠️ **Novedad 26 julio 2026, específica de V2 de la app:** el archivo HTML de V2 se guarda siempre con el mismo nombre (`tiritaito-creators-v2-01.html`, ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 2) — esto significa que un simple vistazo al nombre del archivo en GitHub **no dice nada** sobre qué versión es. Antes de dar por buena una sincronización de Proyecto 5 o Proyecto 3 después de un cambio en la app, comprobar el número real dentro del footer del HTML o en `CHANGELOG-v2-web-nueva.md`, no fiarse del nombre.

### 5.4 Estructura del repositorio (actualizada — con `apps/` y colaboración externa)

```
tiritaito-docs/
├── README.md
├── 00-nucleo-tecnico/
│   ├── 00_CORE.md
│   ├── 00_CORE.matt.md                      ⚠️ copia con placeholder — Sección 11
│   ├── 02_REF_PODCAST.md
│   ├── 04_ENTORNO_LOCAL.md          ⚠️ datos sensibles — repo privado obligatorio
│   ├── 04_ENTORNO_LOCAL.matt.md             ⚠️ copia con placeholder
│   ├── TIRITAITO_FOR_CREATORS_VERSIONS.md   (absorbe 01_CREATORS_APP.md, retirado 26/07)
│   └── TIRITAITO_FOR_CREATORS_VERSIONS.matt.md  ⚠️ copia con placeholder
├── 01-producto/
│   └── ALCANCE_WEB_NUEVA.md
├── 02-metodologia/
│   ├── METODOLOGIA_CONSTRUCCION.md
│   └── MIGRACION_CONTENIDO.md
├── 03-guias-practicas/
│   ├── GUIA_AVADA_LOCAL.md
│   └── CATALOGO_ELEMENTOS_AVADA.md          ← nuevo, 11 agosto 2026, sin dato sensible
├── 04-vision-y-equipo/
│   ├── ARQUITECTURA_Y_ROADMAP.md
│   ├── ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md   ← este documento
│   └── INSTRUCCIONES_PROYECTOS_CLAUDE.md       ← nuevo, 2 agosto 2026
├── apps/
│   ├── v1/
│   │   ├── tiritaito-creators-v1-XX.html
│   │   └── CHANGELOG-v1-web-vieja.md
│   └── v2/
│       ├── tiritaito-creators-v2-01.html    ⚠️ nombre SIEMPRE fijo, se sobreescribe
│       ├── tiritaito-creators-v2-01.matt.html   ⚠️ copia con placeholder
│       ├── CHANGELOG-v2-web-nueva.md
│       ├── snippet-tt-creators-endpoint-central.php
│       └── snippet-tt-creators-endpoint-central.matt.php   ⚠️ copia con placeholder
└── historico/
    ├── INFORME_ESTRATEGICO_2026_1.md
    ├── INVESTIGACION_HERRAMIENTAS_2026.md
    ├── METODOLOGIA_WEB_NUEVA_v2.md
    └── Diagnóstico original v1.md
```

**Nota sobre `01_CREATORS_APP.md` (retirado 26 julio 2026):** su contenido, tal como estaba
documentado hasta ahora, no tenía sustancia propia — quedaba absorbido por completo en
`TIRITAITO_FOR_CREATORS_VERSIONS.md`. Al subir esta actualización, eliminarlo del
repositorio. ⚠️ Si en el archivo real de GitHub tenía contenido que no llegó a este
Proyecto de Investigación, avisar antes de borrarlo definitivamente.

**Nota sobre `CATALOGO_ELEMENTOS_AVADA.md` (añadido 11 agosto 2026):** no lleva ninguna copia `.matt.*` — no contiene tokens, dominios ni credenciales, solo referencia de qué elemento de Avada sirve para qué. Matt puede leerlo y editarlo directamente si en algún momento colabora en esa parte del proyecto (sin decisión tomada todavía al respecto).

### 5.5 Paso a paso

1. **Repositorio privado** `tiritaito-docs` (ya creado) — solo `.md` y el contenido de `apps/`, nunca credenciales sueltas fuera de `04_ENTORNO_LOCAL.md`
2. **Conectar GitHub a cada cuenta que lo necesite:** Configuración → Conectores → GitHub → Autorizar → elegir repositorio(s)
3. **Añadir el repositorio al Proyecto correspondiente:** dentro del Proyecto → "+" → "Add from GitHub" → marcar solo las carpetas/archivos que ese Proyecto necesita
4. **Flujo de cada día:** editar en github.com (icono del lápiz) → Commit → pulsar "Sync now" en el conector antes de una sesión importante → trabajar con normalidad
5. **Verificación de fiabilidad (recomendado periódicamente):** cambiar una palabra en un documento de prueba, sincronizar, y preguntar algo que Claude solo pueda responder bien si leyó la versión nueva

---

## 6. Decisiones que requieren coordinación previa

Decisiones que, si las toma una sola persona sin comunicarlo, rompen el trabajo de otros:

1. Añadir una nueva clave a `wp_options` o un campo nuevo a una Options Page/CPT de ACF (afecta al contrato de datos de la PWA Creators)
2. Cambiar el endpoint REST o el sistema de autenticación
3. Añadir o eliminar un snippet PHP (riesgo de conflictos de función)
4. Cambiar el nombre de una clase CSS de módulo
5. Actualizar Avada o WordPress
6. Cambiar el formato de una sección ya decidida en `ALCANCE_WEB_NUEVA.md`
7. Cambiar la convención de nombres de archivo de la app en GitHub (ver Sección 5.3 — ya cambió una vez sin decisión formal previa)
8. Dar acceso de escritura sobre el repositorio a un nuevo colaborador externo, o cambiar el alcance de lo que puede ver/editar (ver Sección 11)
9. Formalizar `10px` como cuarto token de radio en `00_CORE.md` Sección 5, en vez de dejarlo como excepción puntual de Toggles/Forms (añadido 11 agosto 2026, ver Sección 6.1)

---

## 6.1 Protocolo de sincronización documental

**El problema que resuelve:** que un hallazgo de Hno A o una decisión de Hna C llegue a actualizarse en `tiritaito-docs` sin depender de que alguien se acuerde de traerlo a este Proyecto de Investigación.

### Regla de activación

- ✅ Anotar: "Post Cards sí funciona sobre el CPT de santos, probado en Local" · "Hna C decide que Oraciones sí lleva audio desde v1" · "V2 confirma módulo de Novedades con clave tt_novedades"
- ❌ No hace falta: probar un color y descartarlo, dudas resueltas dentro de la misma sesión sin cambiar ninguna recomendación

### Tabla de mapeo — tipo de cambio → documento → quién lo aplica

| Tipo de cambio | Documento a actualizar | Quién lo aplica |
|---|---|---|
| Configuración de Avada ya probada y estable | `GUIA_AVADA_LOCAL.md` | Hno C (aquí), a partir del resumen de Hno A |
| Confirmación sobre un elemento nativo | `GUIA_AVADA_LOCAL.md` / `METODOLOGIA_CONSTRUCCION.md` | Hno C |
| Qué elemento de Avada resuelve una necesidad de contenido nueva, o un elemento pasa de 🔲 a ✅ tras probarse en Local | `CATALOGO_ELEMENTOS_AVADA.md` | Hno A construye la entrada inicial; Hno C (aquí) reconcilia contra el resto de `tiritaito-docs` si hay contradicción con algo ya escrito |
| Decisión de producto o alcance | `ALCANCE_WEB_NUEVA.md` | Hna C decide, Hno C redacta |
| Nueva pieza de contenido — dónde vive y con qué patrón | `METODOLOGIA_CONSTRUCCION.md` Sección 3 | Hno C |
| Cambio en `wp_options`, ACF, endpoint REST o autenticación | `00_CORE.md` / `04_ENTORNO_LOCAL.md` | Hno A directamente — genera también la copia `.matt.*` correspondiente si el archivo la tiene (Sección 11) |
| Nueva versión o módulo de Tiritaito for Creators (V1/V2) | `TIRITAITO_FOR_CREATORS_VERSIONS.md` | Hno A directamente — avisar a Hno C si el módulo requiere un cambio de datos que también deba constar en `00_CORE.md` |
| Cambio de organización del equipo o cuentas | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | Hno C, con confirmación de Carlitos |
| Cambio en el texto de instrucciones de un Proyecto | `INSTRUCCIONES_PROYECTOS_CLAUDE.md` | Quien lo edite directamente en claude.ai debe traerlo de vuelta aquí en la siguiente sesión de Proyecto 2 (ver Sección 11.7) |
| Patrón visual confirmado tras ver bocetos (ej. Novedades) | `ALCANCE_WEB_NUEVA.md` (Sección 4/5) y `GUIA_AVADA_LOCAL.md` (Sección 8.4-bis) | Hno C, a partir del resumen de Hno A |
| Decisión sobre si algo se aplica ya en la app o queda pendiente | `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 5 | Quien confirme contra el HTML real — nunca asumir |
| Cambio en el sistema de colaboración externa (nuevo colaborador, nuevo archivo sensible, cambio de protocolo) | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 11 | Carlitos, con quien haya detectado el cambio |

---

## 7. WPMobile.app — qué prever ahora, sin construir todavía

### 7.1 Cómo funciona

No es una app hecha desde cero — es un plugin de WordPress que **envuelve el contenido de la web actual** en una app nativa para iOS y Android. Se actualiza sola cuando se publica contenido nuevo en WordPress.

### 7.2 Implicación directa para cómo se construye la web nueva

Cuanto más estructurado esté algo (Posts, CPT) más fácil será que aparezca bien en la app. Esto conecta con la decisión ya tomada para "Hombres de Dios" (Layouts de Avada sobre Posts/CPT) y con el uso creciente de CPT+ACF en el proyecto (Novedades).

### 7.3 Licencia — pendiente de verificar

La licencia de WPMobile.app es por sitio de WordPress. No hay confirmación de una política de "sitio de staging gratuito" — hay que preguntar directamente a su soporte antes de conectar nada al Local.

### 7.4 Limitación de pruebas en Local

Solo funciona si el móvil está en la misma red que el ordenador, salvo que se use **Live Link** de Local by Flywheel.

### 7.5 Checklist de "preparar el terreno"

- [ ] Al definir la estructura de "Hombres de Dios" y "Oraciones", tener presente que un formato basado en entradas facilita la integración futura
- [ ] Preguntar a soporte de WPMobile.app por la política de sitio de desarrollo/staging
- [ ] Recordar: probar la app contra el Local requiere misma red, o Live Link

---

## 8. Google Search Console — checklist de configuración completa

- [ ] Verificar que el sitemap XML está enviado
- [ ] Revisar el informe de Cobertura/Páginas indexadas
- [ ] Revisar Core Web Vitals
- [ ] Exportar el listado de páginas con más clics de los últimos 12 meses — insumo del plan de redirecciones 301 de `MIGRACION_CONTENIDO.md` Sección 2
- [ ] Configurar alertas de errores 404/rastreo

---

## 9. Estado actual — julio/agosto 2026

| Elemento | Estado real |
|---|---|
| Proyecto 1 (Web Vieja) | ✅ Configurado |
| Proyecto 2 (Investigación) | ✅ Activo — es este mismo proyecto |
| Proyecto 3 (Web Nueva, fusionado) | ✅ Configurado — instrucciones ampliadas 26 julio 2026 (ACF 0.3, verificación 0.4) y 2 agosto 2026 (`.matt.php`, Sección 9) — **y de nuevo el 11 de agosto de 2026 con las Secciones 0.5 (catálogo de elementos) y 0.6 (construcción por niveles + pregunta de código)** — pendiente de que Hno A repegue el bloque completo actualizado en claude.ai |
| Proyecto 4 (Diseño y Avada) | ✅ Configurado |
| Proyecto 5 (Tiritaito for Creators) | ✅ Configurado — actualizado con V1/V2, la regla de nombre de archivo fijo para V2, y la generación de copia `.matt.html` (2 agosto 2026) |
| Proyecto 6 y 7 (Web Nueva — Repuesto A y B) | ✅ Creadas y configuradas (26 julio 2026) — corrige el conteo de "7 cuentas" a 8, ver Sección 2. Pendiente confirmar que su conector de GitHub incluye `CATALOGO_ELEMENTOS_AVADA.md` (Sección 3) |
| Proyecto 8 (WPMobile.app) | 🔵 Sin definir todavía |
| ~~Datos y Métricas~~ | ❌ Cancelado |
| Repositorio de GitHub `tiritaito-docs` | ✅ Creado — estructura con `apps/` (V1 y V2) lista, PHP del endpoint pasa a vivir dentro de `apps/v2/` (26 julio 2026), archivos `.matt.*` incorporados (2 agosto 2026), `CATALOGO_ELEMENTOS_AVADA.md` incorporado en `03-guias-practicas/` (11 agosto 2026) |
| `01_CREATORS_APP.md` | ❌ Retirado (26 julio 2026) — fusionado en `TIRITAITO_FOR_CREATORS_VERSIONS.md`, sin contenido propio que se perdiera |
| Documento de versiones de Tiritaito for Creators | ✅ `TIRITAITO_FOR_CREATORS_VERSIONS.md` — actualizado 26 julio 2026 con la política de nombre de archivo fijo para V2 y absorbe `01_CREATORS_APP.md` |
| Snippet PHP del endpoint central | ✅ Real y completo, obtenido y subido a `apps/v2/snippet-tt-creators-endpoint-central.php` (26 julio 2026) — confirma todo lo reportado por Hno A (Novedades, ACF, Tip eliminado). ⚠️ Tres avisos abiertos sin confirmar con Hno A: sin rate limit, sin validación de subida de archivos, sin Biblioteca ni gestión de entradas — ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 7.1 |
| `04_ENTORNO_LOCAL.md` | ✅ Reescrito 26 julio 2026 — auth confirmada (X-TT-Token), token en define() resuelto |
| Módulo Novedades (V2) | ✅ Backend confirmado y probado — 🔲 falta título en la app, falta montar Post Cards en Avada |
| Módulo Devocional (V2) | 🔄 Migración parcial a ACF — 🔲 falta quitar fecha de Homilía-texto en la app, falta Dynamic Content en Avada |
| Tip del día | ❌ Decisión: eliminado — 🔲 todavía construido en la app, pendiente de retirar |
| `GUIA_AVADA_LOCAL.md` | ✅ Ampliado con Sección 8.4 (Responsive), 8.4-bis (altura/previsualización), la decisión de no filtrar Post Cards por `activo` (Sección 9), y — **11 agosto 2026** — orden real de colores (4.1), mecanismo de Default Page Template (4.0.3), panel de Performance completo (4.4), breakpoints confirmados (8.4) y distinción Slideshows/Post Slider (9) |
| **`CATALOGO_ELEMENTOS_AVADA.md`** | ✅ Creado (11 agosto 2026) — resultado de la ronda de tres cuentas de Avada Global Options (28/07-10/08) más el material de Off-Canvas. Organizado por necesidad de contenido, con nivel de certeza por entrada. 🔲 Pendiente completar el nombre de los ~70 elementos de Avada Builder Elements todavía sin inventariar |
| **`INSTRUCCIONES_PROYECTOS_CLAUDE.md`** | ✅ Creado (2 agosto 2026) — texto literal de todos los Proyectos extraído de este documento. Ampliado (11 agosto 2026) con las Secciones 0.5-0.6 del Proyecto 3 |
| **Sistema de colaboración externa (Matt/Codex)** | ✅ Diseñado y con 5 archivos `.matt.*` generados (2 agosto 2026) — pendiente de subir a GitHub y dar acceso a Matt. Ver Sección 11 |

---

## 10. Próximos pasos y preguntas abiertas (generales del documento)

**Próximos pasos:**
1. Carlitos: subir todos los documentos actualizados a GitHub (ver README.md para el listado completo) y eliminar `01_CREATORS_APP.md` (con la salvedad de la Sección 5.4)
2. ✅ Snippet PHP completo y real obtenido y subido a `apps/v2/snippet-tt-creators-endpoint-central.php` (26 julio 2026)
3. Hno A: confirmar los tres avisos abiertos sobre el backend actual (sin rate limit, sin validación de subidas, sin Biblioteca/entradas — ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 7.1)
4. Hno A: repegar las instrucciones actualizadas del Proyecto 3 en claude.ai (`INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 3, incluidas las Secciones 0.5-0.6 sobre el catálogo de elementos y la construcción por niveles) — subir a GitHub no las aplica solas
5. Hno A: dar acceso de GitHub al Proyecto 3 y al Proyecto 5 sobre la carpeta `apps/v2/` completa, y confirmar que el Proyecto 3 (y sus Repuestos) tienen acceso a `03-guias-practicas/CATALOGO_ELEMENTOS_AVADA.md`
6. Hno C: dar acceso de GitHub a este Proyecto (2) sobre `apps/v2/` también, para poder verificar el estado real sin depender de que se pegue el archivo a mano
7. Proyecto 5: aplicar las tres tareas pendientes de la app (quitar Tip, quitar fecha de Homilía-texto, añadir input de título a Novedades) — ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 8
8. Hno A: verificar con soporte de WPMobile.app la política de sitio de desarrollo
9. Cuando se acerque el lanzamiento de la Web Nueva: archivar o reconvertir el Proyecto 1 y marcar V1 como retirada en `TIRITAITO_FOR_CREATORS_VERSIONS.md` (Sección 2.1)
10. Ver Sección 11.8 para los próximos pasos específicos del sistema de colaboración externa
11. Confirmar el tipo de plan (gratuito/pago) de la cuenta que reciba el catálogo ampliado, antes de seguir haciéndolo crecer (Sección 2, Sección 4)
12. Hna C / equipo: decidir si `10px` se formaliza como cuarto token de radio (Sección 6, punto 9)

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿WPMobile.app admite el plugin activo en dos sitios sin licencia adicional? | Bloquea cualquier prueba contra el entorno Local |
| 2 | ¿Quién asume el Proyecto 8 (WPMobile.app)? | Sin asignar |
| 3 | ¿Por qué dos cuentas de repuesto para el Proyecto 3 en vez de una? | No es urgente saberlo, pero si hay un motivo (por ejemplo, repartir carga entre dos personas) vale la pena anotarlo aquí para que no se pierda |
| 4 | ¿La regla de nombre de archivo fijo (Sección 5.3) se extiende a V1, o se queda solo en V2? | Ver `TIRITAITO_FOR_CREATORS_VERSIONS.md` Sección 8, pregunta 1 |
| 5 | ¿`01_CREATORS_APP.md` tenía contenido real en GitHub que no llegó a este Proyecto? | Antes de confirmar su eliminación definitiva, ver nota de Sección 5.4 |
| 6 | ¿Es de pago la cuenta que participó en la ronda de Avada Global Options y que aparece en una captura como Plan gratuito? | Determina si el plan de ampliar `CATALOGO_ELEMENTOS_AVADA.md` progresivamente es viable tal cual, o si hace falta gestionar el tamaño de la base de conocimiento con más cuidado (Sección 2, Sección 4) |

---

## 11. Colaboración externa vía Codex — el sistema de Matt

*Sección incorporada el 2 de agosto de 2026, absorbiendo el contenido íntegro de `COLABORACION_EXTERNA_CODEX.md` (documento independiente, ahora retirado — su contenido vive aquí completo, sin resumir)*

### 11.1 Por qué existe este sistema

Matt se suma al equipo como colaborador externo, usando Codex (de OpenAI) para trabajar directamente sobre archivos del repositorio `tiritaito-docs`. A diferencia del equipo interno (que usa Proyectos de Claude ya auditados), Matt necesita:

1. **Poder ver y editar directamente** buena parte del repositorio real — no una copia aparte ni un resumen.
2. **Nunca acceder** a los archivos que contienen credenciales, tokens de autenticación, o el contrato técnico exacto del backend en producción/Local — no por desconfianza, sino porque un token filtrado permite crear, editar y borrar contenido real del sistema, y cuantas más personas/sistemas tengan acceso a él, mayor la superficie de riesgo.

La solución adoptada: **archivos-copia con datos ficticios (placeholder)**, que conviven en la misma carpeta que el archivo oficial, con el sufijo `.matt.` en el nombre. Matt lee y escribe únicamente sobre esas copias. El resto del repositorio (documentos sin dato sensible, incluido `CATALOGO_ELEMENTOS_AVADA.md`) lo edita directamente sobre el archivo real.

### 11.2 Decisiones tomadas y por qué (registro de la conversación del 2 de agosto de 2026)

| Decisión | Alternativa descartada | Por qué |
|---|---|---|
| Un solo repositorio (`tiritaito-docs`), con archivos-copia dentro | Repositorio separado, solo con contenido no sensible | Más simple de mantener sincronizado; Carlitos confía en que Matt no dará a su Codex acceso a los archivos oficiales sensibles, aunque técnicamente pueda verlos al tener acceso de escritura al repo |
| Matt tiene **escritura** sobre archivos sin dato sensible, y **lectura + escritura solo sobre las copias `.matt.*`** de los archivos sensibles | Solo lectura en todo, cambios pasados a mano | Permite que Matt trabaje con fluidez usando Codex directamente, sin fricción de copiar/pegar constante |
| Aviso de cambios: **acuerdo directo entre Matt y Carlitos por WhatsApp**, sin automatización | GitHub Actions + servicio puente (CallMeBot/Twilio) para notificación automática | Carlitos confía en que Matt avisará directamente; se descarta la complejidad de configurar una integración técnica para esto |
| Visualización del cambio: **el diff nativo de GitHub** (verde/rojo, automático) | Marcar el texto con color dentro del propio archivo | Técnicamente inviable — un archivo de texto no puede llevar una marca de color persistente dentro de sí mismo; el diff de GitHub ya cumple el mismo propósito sin configuración adicional |
| Generación de la copia `.matt.*`: **instrucción en las instrucciones personalizadas** de los Proyectos de Claude que editan los archivos oficiales (Proyecto 2 y Proyecto 5, y Proyecto 3 para el PHP), para que se genere junto con cualquier actualización del archivo oficial dentro de la misma sesión de trabajo | Automatización externa que vigile el repositorio y genere la copia sin intervención humana | Claude no tiene manera de ejecutar procesos fuera de una conversación activa — la vía real es que la instrucción viva dentro del propio Proyecto y se aplique cada vez que alguien pide un cambio en una sesión |

### 11.3 Convención de nombres y qué archivos tienen copia

**Convención:** `nombre_archivo.matt.extensión`, viviendo en la misma carpeta que el archivo oficial.

**Por qué el sufijo `.matt` y no algo genérico:** identifica sin ambigüedad de quién/para quién es la copia. Si en el futuro se suma otro colaborador externo con el mismo esquema, el patrón (`.nombre.extensión`) ya está establecido y es fácil de repetir sin inventar una convención nueva cada vez.

#### Archivos con copia `.matt.*` (información sensible — Matt nunca ve el original)

| Archivo oficial | Copia | Qué contiene de sensible |
|---|---|---|
| `00-nucleo-tecnico/00_CORE.md` | `00-nucleo-tecnico/00_CORE.matt.md` | Fragmentos de estructura de token, dominio de producción |
| `00-nucleo-tecnico/04_ENTORNO_LOCAL.md` | `00-nucleo-tecnico/04_ENTORNO_LOCAL.matt.md` | Dominio interno del entorno Local |
| `00-nucleo-tecnico/TIRITAITO_FOR_CREATORS_VERSIONS.md` | `00-nucleo-tecnico/TIRITAITO_FOR_CREATORS_VERSIONS.matt.md` | Contrato técnico exacto del backend |
| `apps/v2/tiritaito-creators-v2-01.html` | `apps/v2/tiritaito-creators-v2-01.matt.html` | `TT_WRITE_TOKEN` en texto plano, dominio real, PIN |
| `apps/v2/snippet-tt-creators-endpoint-central.php` | `apps/v2/snippet-tt-creators-endpoint-central.matt.php` | Lógica completa de autenticación del backend, incluido el token |

✅ **Corregido 2 de agosto de 2026 — nota sobre la copia del PHP:** en la primera versión de este sistema, la copia `.matt.php` se generó como una *reconstrucción de referencia* (forma general del contrato, sin tener el código fuente real delante), no como una sanitización literal del archivo. Al obtenerse el PHP real y completo, se corrigió: `snippet-tt-creators-endpoint-central.matt.php` es ahora una **sanitización fiel línea por línea** del archivo oficial, con el único cambio siendo la sustitución del valor de `TT_WRITE_TOKEN` por el placeholder — misma estructura, mismos nombres de función, misma lógica, mismos comentarios originales. Este es el estándar que debe cumplir cualquier copia `.matt.*` a partir de ahora: **nunca una aproximación basada en lo que se recuerda o se infiere del archivo, siempre una sanitización del archivo real que se tiene delante.**

#### Archivos SIN copia — Matt accede directamente al oficial

Todo el resto del repositorio: `README.md`, `ALCANCE_WEB_NUEVA.md`, `METODOLOGIA_CONSTRUCCION.md`, `GUIA_AVADA_LOCAL.md`, `CATALOGO_ELEMENTOS_AVADA.md`, `ARQUITECTURA_Y_ROADMAP.md`, `MIGRACION_CONTENIDO.md`, `02_REF_PODCAST.md`, los changelogs de `apps/v1/` y `apps/v2/`, y — pendiente de confirmación final, ver Sección 11.9 — este mismo documento (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md`) e `INSTRUCCIONES_PROYECTOS_CLAUDE.md`.

### 11.4 Placeholders — qué valor sustituye a cada dato real

| Dato real | Placeholder usado |
|---|---|
| `TT_WRITE_TOKEN` (token de escritura) | `PLACEHOLDER_NO_ES_TOKEN_REAL` (variantes con sufijo según archivo) |
| Token GET de lectura pública | `PLACEHOLDER_TOKEN_LECTURA` |
| Dominio del entorno Local (`tiritaito-real.local`) | `ejemplo-local.local` |
| `APP_PIN` real | `0000` |
| URLs de Media Library con el dominio real | Mismo dominio placeholder, con nombre de archivo genérico (`logo-placeholder.png`) |

Cada archivo `.matt.*` lleva además, al principio, un comentario visible explicando que es una copia de trabajo con datos ficticios — para que nadie (Matt, Codex, o cualquier persona que lo abra después) lo confunda con el real.

### 11.5 Protocolo de sincronización — cómo se mantiene todo alineado

#### 11.5.1 Cuando el equipo interno actualiza un archivo oficial con copia

Si Proyecto 2 (Investigación), Proyecto 3 (Web Nueva, para el PHP), o Proyecto 5 (Tiritaito for Creators) entregan una versión nueva de `00_CORE.md`, `04_ENTORNO_LOCAL.md`, `TIRITAITO_FOR_CREATORS_VERSIONS.md`, el HTML de la app, o el PHP del endpoint central: **generan también, en la misma respuesta, la copia `.matt.*` correspondiente**, con los mismos cambios de contenido pero los valores sensibles sustituidos por placeholder — sin que haga falta pedirlo aparte.

Esta regla vive en `INSTRUCCIONES_PROYECTOS_CLAUDE.md`, dentro del bloque de cada Proyecto afectado — no como una automatización externa, se aplica cada vez que alguien pide un cambio dentro de una sesión de trabajo real con esos Proyectos.

⚠️ **Límite honesto de este mecanismo:** si un cambio se aplica al archivo oficial *fuera* de una sesión con esos Proyectos (por ejemplo, editado directamente en GitHub a mano), la copia `.matt.*` no se actualiza sola — alguien tiene que pedir explícitamente, en la siguiente sesión, que se regenere la copia a partir del archivo oficial actualizado.

#### 11.5.2 Cuando Matt edita una copia `.matt.*`

1. Matt trabaja con Codex sobre el archivo `.matt.*` correspondiente, con permiso de escritura.
2. Al terminar un cambio, **Matt avisa directamente a Carlitos por WhatsApp** — acuerdo directo entre ambos, sin automatización.
3. Carlitos revisa el cambio en GitHub — el diff nativo (líneas verdes añadidas, rojas eliminadas) ya muestra con claridad qué cambió, sin necesitar configuración adicional.
4. Si Carlitos decide aplicar el cambio al archivo oficial: lo trae a este Proyecto de Investigación (o al Proyecto 5/3, según corresponda) para que se aplique con el mismo criterio de revisión que cualquier otro cambio del equipo — nunca se copia el contenido de `.matt.*` directamente al oficial sin pasar por esa revisión, porque la copia pudo haberse escrito sin conocer el valor real de un token o una URL que sí importan en el archivo oficial.

#### 11.5.3 Convención de commits (recomendada, no forzada técnicamente)

Cuando un cambio propuesto por Matt se aplica al archivo oficial, se recomienda marcarlo en el mensaje de commit (ej. `"Aplicado: sugerencia de Matt (Codex) - [breve descripción]"`), para que el historial de git deje rastro de que ese cambio se originó fuera del equipo interno, sin necesidad de más detalle.

### 11.6 Documento de bienvenida entregado a Matt

Para referencia — este es el contenido ya compartido con Matt al incorporarse, explicando el sistema desde su perspectiva:

```markdown
# Tiritaito.com — Contexto para colaboración externa

Bienvenido/a. Gracias por ayudar con la reconstrucción de Tiritaito.com,
un espacio digital católico pensado para acompañar la fe y la oración.

## Cómo funciona tu acceso

Tienes acceso de lectura y escritura sobre la mayoría del repositorio
tiritaito-docs. Los archivos que contienen credenciales o datos
técnicos sensibles (tokens, dominios internos, contrato exacto del
backend) tienen una copia paralela con el sufijo .matt. en el nombre
(ej. 00_CORE.matt.md junto a 00_CORE.md) — trabaja siempre sobre esas
copias, nunca sobre el archivo original cuando exista una versión
.matt. de él. Los valores dentro de esas copias son ficticios y no
funcionan contra ningún entorno real.

## Cuando termines un cambio

Avísame directamente por WhatsApp. Reviso el cambio en GitHub y decido
si se aplica al archivo oficial.

## Principio de construcción del proyecto

Antes de proponer un snippet de código nuevo, este equipo prioriza
siempre la opción nativa de Avada/WordPress + ACF si existe una — el
código a medida es el último recurso, no el primero.

Ad maiorem Dei gloriam · tiritaito.com
```

### 11.7 Episodio registrado — la discrepancia del 2 de agosto de 2026

Se deja constancia de esto porque es exactamente el tipo de aprendizaje que este documento existe para capturar, y porque motivó el cambio de estructura de la Sección 3.

**Qué pasó:** al diseñar el sistema de colaboración con Matt, se añadió un bloque de instrucciones nuevo al Proyecto 2 (este Proyecto), pidiendo que generara copias `.matt.*` automáticamente. Carlitos pegó ese bloque directamente en la configuración de claude.ai del Proyecto 2. Pero el documento `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md`, que en ese momento contenía el texto completo de instrucciones de todos los Proyectos dentro de sí mismo, **no se actualizó al mismo tiempo** — quedó con la versión antigua, sin el bloque de Matt.

El resultado: dos versiones distintas de las instrucciones del Proyecto 2 coexistiendo — la real en claude.ai (correcta, con el bloque de Matt) y la del documento (desactualizada, sin él) — sin que nada avisara de la discrepancia hasta que Carlitos la notó al pedir ayuda con un tema no relacionado.

**Causa raíz:** tener el texto de instrucciones mezclado dentro de un documento grande, con tablas y explicaciones alrededor, hacía que actualizar "solo el bloque de texto" en dos sitios a la vez (claude.ai y el documento) fuera fácil de hacer en uno y olvidar en el otro.

**Corrección aplicada (2 de agosto de 2026):** el texto de instrucciones se extrajo a `INSTRUCCIONES_PROYECTOS_CLAUDE.md`, un documento dedicado únicamente a eso. Este documento (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md`) dejó de contener el texto literal, reduciendo a un solo lugar dónde puede desincronizarse.

**Regla fijada a partir de este episodio** (ya incluida en `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 0): si alguna vez se edita el texto de instrucciones de un Proyecto directamente en claude.ai sin pasar primero por ese documento, debe traerse de vuelta al documento en la siguiente sesión de Proyecto 2 — no puede quedar una versión "solo en claude.ai" sin su equivalente en el repositorio, ni al revés.

⚠️ **Recordatorio de que este riesgo sigue vivo, no solo histórico (11 agosto 2026):** la ampliación de las Secciones 0.5-0.6 del Proyecto 3 (Sección 3 de este documento, y Sección 9) es, ahora mismo, exactamente este mismo escenario — ya está en `INSTRUCCIONES_PROYECTOS_CLAUDE.md`, pendiente de repegarse en claude.ai. Mientras no se repegue, existe la misma discrepancia que motivó esta corrección.

### 11.8 Próximos pasos y preguntas abiertas (específicas de colaboración externa)

**Próximos pasos:**
1. Carlitos: subir los 5 archivos `.matt.*` ya generados a sus carpetas correspondientes en GitHub
2. Carlitos: subir `INSTRUCCIONES_PROYECTOS_CLAUDE.md` y este documento actualizado a `04-vision-y-equipo/` en GitHub, y retirar `COLABORACION_EXTERNA_CODEX.md` del repositorio (su contenido ya vive aquí completo)
3. Hno A: repegar el bloque de instrucciones ampliadas del Proyecto 5 en claude.ai (`INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 5)
4. Hno A: repegar el bloque de instrucciones ampliadas del Proyecto 3 en claude.ai (`INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 3, con las Secciones 0.5-0.6 nuevas y la Sección 9 de generación de copia `.matt.php`)
5. Carlitos: confirmar que el bloque ya pegado en el Proyecto 2 coincide exactamente con `INSTRUCCIONES_PROYECTOS_CLAUDE.md` Sección 2 — verificado al redactar este documento, pero conviene una comprobación visual final
6. Confirmar con Matt su nivel de plan de OpenAI (Free/Business) y, si es Free, pedirle que desactive el entrenamiento de modelos en su configuración de ChatGPT antes de conectar el repositorio
7. Dar a Matt acceso de colaborador al repositorio `tiritaito-docs` en GitHub, con permisos de escritura

### 11.9 Preguntas abiertas (específicas de colaboración externa)

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Matt tiene acceso a este documento (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md`) y a `INSTRUCCIONES_PROYECTOS_CLAUDE.md` tal cual, o se excluyen por ser coordinación interna del equipo (no un secreto de seguridad, pero tampoco necesario para su trabajo)? | Sección 11.3 — pendiente de decisión explícita de Carlitos. Cobra más relevancia ahora que `INSTRUCCIONES_PROYECTOS_CLAUDE.md` contiene el texto completo de cómo se instruye a cada IA del equipo interno |
| 2 | Si en el futuro se suma un segundo colaborador externo con un esquema similar, ¿se reutiliza la misma convención `.nombre.extensión`, o conviene una carpeta dedicada en vez de archivos sueltos en cada directorio? | No urgente — anotado para cuando surja el caso |
| 3 | ¿Qué pasa si Matt necesita, en algún momento, un valor real (por ejemplo, para probar algo end-to-end contra el entorno Local)? ¿Se le da puntualmente por un canal directo, o nunca se comparte bajo ninguna circunstancia? | No resuelto todavía — conviene fijarlo antes de que surja la necesidad real, no en el momento |

---

*Para la mayor gloria de Dios · tiritaito.com*

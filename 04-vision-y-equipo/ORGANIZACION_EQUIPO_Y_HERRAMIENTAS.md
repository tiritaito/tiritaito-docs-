# TIRITAITO.COM — Organización del Equipo y Herramientas
**Roles, mapa de Proyectos de Claude, GitHub, economía de tokens, WPMobile.app y Search Console**
*Fusiona: CONFIGURACION_PROYECTOS_CLAUDE_Y_GITHUB.md (autoridad sobre el mapa de proyectos) + INFORME_ESTRATEGICO_2026_1.md (Parte 6.1, 6.3) + INVESTIGACION_HERRAMIENTAS_TRABAJO_2026.md (Partes 1, 4, 5)*
*Verificado contra support.claude.com — julio 2026*
*Audiencia: Carlitos (coordinación transversal) · cada dueño de cuenta para su propia sección*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde: **¿quién hace qué, en qué cuenta de Claude, con qué instrucciones, y qué herramientas de apoyo (GitHub, Search Console) hay que mantener?** Es el documento de coordinación transversal del proyecto — no de contenido ni de Avada.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Cómo funciona Avada / Local técnicamente | `GUIA_AVADA_LOCAL.md` |
| Dónde construir una pieza de contenido decidida | `METODOLOGIA_CONSTRUCCION.md` |
| Qué migrar de la web vieja | `MIGRACION_CONTENIDO.md` |
| Visión general, FODA, fases del proyecto | `ARQUITECTURA_Y_ROADMAP.md` |
| **Quién hace qué, en qué Proyecto de Claude, y cómo se coordina el equipo** | **Este documento** |

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
| Carlitos | Coordinador del proyecto | Decisión final sobre documentación y alcance | — |
| Hno A | Desarrollador principal | PHP, JS, REST API, snippets complejos, Avada/Local | Decisiones de contenido |
| Hno C | Investigación y documentación | Investiga, documenta, valida conceptos | Código en producción |
| Hna C | Dirección y diseño | Define alcance, criterio visual, decisiones de producto, Avada visual | No bloquea por perfeccionismo |
| Hna MF | Datos y métricas | Google Search Console, análisis de uso, propone mejoras basadas en datos | — |
| Editores (4) | Contenido | Subir/editar su contenido en sus secciones | Todo lo demás |

---

## 2. Mapa de Proyectos de Claude — versión resuelta (actualizada)

**Regla confirmada:** cada proyecto vive en su propia cuenta — no se comparten cuentas entre proyectos. Con la fusión de Backend+Maquetación y la cancelación de Datos y Métricas, el número de proyectos coincide exactamente con las 7 cuentas disponibles.

```
                            7 CUENTAS DE CLAUDE — una cuenta por proyecto
                                          │
    ┌───────────┬───────────┬────────────┼───────────┬───────────┬────────────┐
    │           │           │            │           │           │            │
 Cuenta 1    Cuenta 2    Cuenta 3     Cuenta 4    Cuenta 5    Cuenta 6     Cuenta 7
    │           │           │            │           │           │            │
 Hno A       Hno C       Hno A        Hna C       Hno A       Repuesto    Sin asignar
    │           │           │            │           │           │            │
 Web Vieja  Investig.   Web Nueva    Diseño y    Tiritaito   Web Nueva    WPMobile.app
 (manten.)   (este)    (fusionado:    Avada     for Creators  (copia      (cuando se
                       backend +                              idéntica    active)
                      maquetación)                            de C.3)
    ✅           ✅          ✅           ✅          ✅          🔵           🔵
```

| # | Proyecto | Cuenta | Rol | Estado |
|---|---|---|---|---|
| 1 | Web Vieja (Mantenimiento) | Hno A | Solo bugs críticos, modo supervivencia | ✅ Configurado |
| 2 | Investigación | Hno C | Documentos y diagramas claros para el equipo | ✅ Activo — es este proyecto |
| 3 | **Web Nueva** (fusión de Backend+Snippets y Maquetación+Avada) | Hno A | Construcción técnica y visual completa en Local | ✅ Configurado |
| 4 | Diseño y Avada | Hna C | Decisiones de producto y visión visual, sin código | ✅ Configurado |
| 5 | Tiritaito for Creators | Hno A | PWA de editores | ✅ Configurado |
| 6 | Web Nueva — Repuesto (copia idéntica del Proyecto 3) | Cuenta libre | Continuidad si la cuenta principal de Hno A agota su límite | 🔵 Recomendado, no creado |
| 7 | WPMobile.app | Sin asignar | Desarrollo de la app cuando se active | 🔵 Pendiente |

**~~Datos y Métricas (Hna MF)~~ — cancelado, no se va a crear como proyecto de Claude.** Si Hna MF necesita el checklist de Search Console, ver Sección 8 — queda como referencia sin un proyecto dedicado.

**Cuentas usadas:** 5 de 7 configuradas y en uso. Quedan 2 pendientes (Repuesto, WPMobile.app) — no libres de verdad, ya tienen destino, solo falta crearlas.

---

## 3. Instrucciones de cada proyecto

*Cópialas tal cual en "Instrucciones personalizadas" al crear cada Proyecto en claude.ai. Referencias de documentos ya actualizadas a la nueva arquitectura de `tiritaito-docs`.*

### Proyecto 1 — Web Vieja (Mantenimiento)

```
Eres el Desarrollador Web Senior de Tiritaito.com — la web ACTUAL en
producción ("la web vieja"). Tu misión aquí es EXCLUSIVAMENTE de
mantenimiento y supervivencia, no de mejora ni experimentación.

CONTEXTO: El equipo está construyendo una web nueva desde cero en Local
by Flywheel con Avada. Mientras tanto, este sitio de producción debe
seguir funcionando con el mínimo riesgo posible.

REGLA DE ORO — MODO SUPERVIVENCIA:
- Solo se tocan bugs que impiden el funcionamiento básico del sitio.
- No se añaden funcionalidades nuevas.
- No se experimenta con plugins, caché o actualizaciones no esenciales.
- Antes de proponer cualquier cambio, pregunta si es una corrección
  crítica o una mejora. Si es una mejora, recuerda que debería esperar
  a la Web Nueva, y no la implementes salvo que se pida explícitamente.

FILOSOFÍA DE ENTREGA DE CÓDIGO:
1. Código complejo o largo (+300 líneas): PHP estructurado para Code
   Snippets, con un [shortcode] corto para pegar en Avada Live.
2. Retoques visuales pequeños: bloque HTML + <style> + <script>
   (jQuery nativo de WP) listo para el Code Block de Avada.
3. Código extenso (700-1000 líneas): NUNCA reescribir entero. Solo la
   parte cambiada, usando /* ... [RESTO DEL CÓDIGO IGUAL] ... */.

SEGURIDAD Y COMENTARIOS: sanitizado/escape siempre. Comentarios en
español, sencillos y educativos.

ADN VISUAL: estilo iOS/Apple limpio y luminoso. border-radius: 25px en
botones e interactivos. Estructuras para acuarelas en cabeceras sobre
fondo blanco.

TONO: Directo, resolutivo, en español. Código primero, resumen breve
después. Si algo no encaja, avisa antes de proceder.

Ad maiorem Dei gloriam.
```

**Base de conocimiento:** `00_CORE.md` · `02_REF_PODCAST.md`

---

### Proyecto 2 — Investigación (Hno C) *(este proyecto)*

```
Eres el asistente de investigación y documentación de Hno C para
Tiritaito.com. Tu misión NO es escribir código — es investigar,
analizar y producir documentos claros y visuales que Hno C use para
explicar hallazgos al resto del equipo.

A QUIÉN VA DIRIGIDO CADA DOCUMENTO:
- Hna C: dirección y visión — conclusiones y decisiones claras, sin
  jerga técnica sin explicar.
- Hno A: desarrollador — detalle técnico, bien organizado.
- Hna MF y editores: perfil no técnico — cualquier término técnico se
  explica en una frase sencilla la primera vez que aparece.

CÓMO TRABAJAR:
1. Investiga a fondo antes de concluir. Usa búsqueda web para
   cualquier dato que pueda haber cambiado. Prioriza fuentes oficiales.
2. Sé honesto sobre lo que no está confirmado — decláralo como
   pregunta abierta, nunca rellenes con una respuesta tranquilizadora.
3. Estructura con tablas, listas priorizadas, encabezados claros.
4. Estructuras espaciales/de proceso/jerárquicas → diagrama visual.
5. Cierra cada documento con: próximos pasos + preguntas abiertas.

FORMATO: documentos largos → Markdown descargable, cerrando con "Para
la mayor gloria de Dios · tiritaito.com". Comparativas rápidas → tabla
en el chat. Estructuras/flujos → diagrama visual.

TONO: Directo, veraz, en español. Prefiere tardar más y acertar.

Ad maiorem Dei gloriam.
```

**Base de conocimiento:** `00_CORE.md` + todos los documentos de `tiritaito-docs` relevantes al momento (Alcance, Metodología, Migración, Guía Avada, Arquitectura y Roadmap, este mismo documento)

---

### Proyecto 3 — Web Nueva (fusionado: Backend+Snippets y Maquetación+Avada) *(configurado)*

```
Eres un Desarrollador Web Senior experto en WordPress, Avada Live/
Fusion Builder y optimización de rendimiento. Tu objetivo es ayudar a
construir la Web Nueva de Tiritaito.com desde cero en Local by
Flywheel — tanto la parte de código (backend, snippets, lógica de
servidor) como la maquetación visual con las herramientas nativas de
Avada (Global Options, Header/Footer/Off Canvas Builder, Layouts,
Fusion Builder). Es un espacio diseñado para ayudar a la gente a
crecer en la fe y la oración, transmitiendo paz, unción y alegría
cristiana.

0. CÓMO DECIDIR SI LA TAREA ES CÓDIGO O AVADA VISUAL (consulta esto
PRIMERO, antes de escribir nada):
- ¿Se resuelve con clics en el panel de Avada (Global Options,
  Builder, Layouts, Page Options)? → da pasos concretos, con el
  nombre exacto de cada menú/opción, SIN código. Consulta
  GUIA_AVADA_LOCAL.md — puede que Avada ya resuelva la necesidad de
  forma nativa.
- ¿Es lógica de servidor, endpoint REST, shortcode dinámico, o datos
  de wp_options? → Code Snippets PHP.
- ¿Es un módulo visual con JS interactivo y su propio estilo? → Code
  Snippets HTML (con <style> + <script> integrados).
- ¿Es contenido ya decidido y no sabes dónde construirlo? → consulta
  METODOLOGIA_CONSTRUCCION.md antes de decidir tú solo.

1. FILOSOFÍA DE ENTREGA DE CÓDIGO (cuando la tarea es código):
- Código complejo/largo (+300 líneas): PHP estructurado para Code
  Snippets, con [shortcode] corto para Avada Live.
- Retoques visuales pequeños: HTML + <style> + <script> (jQuery
  nativo) listo para Code Block de Avada.
- Código extenso (700-1000 líneas): NUNCA reescribir entero, solo la
  parte cambiada con /* ... [RESTO DEL CÓDIGO IGUAL] ... */.

2. CÓMO GUIAR CUANDO LA TAREA ES AVADA VISUAL:
- Pasos concretos de dónde hacer clic en el panel de Avada, con el
  nombre exacto de cada menú y opción — no supongas que se recuerda
  la ruta de memoria.
- Si la tarea necesita una decisión de producto/visual no tomada:
  dilo y sugiere consultarlo con Hna C en el proyecto Diseño y Avada.

3. SEGURIDAD Y COMENTARIOS: sanitizado/escape siempre, comentarios en
español, sencillos y educativos.

4. ADN VISUAL: iOS/Apple limpio y luminoso, border-radius: 25px en
botones/cards/contenedores, paleta --tt-*, "Yeah Papa" en títulos,
Helvetica Neue en cuerpo, acuarelas sobre blanco.

5. TONO: Directo, resolutivo y práctico, en español. Código o pasos
primero, resumen breve después.

6. SINFONÍA CON LOVABLE: si se comparte código de Lovable, no cambies
el diseño — tradúcelo a PHP/shortcode con seguridad WP.

7. ENTORNO — IMPORTANTE: este proyecto construye en Local by Flywheel,
NO en producción. Para URLs/credenciales concretas, usa SIEMPRE
04_ENTORNO_LOCAL.md — si no está adjunto, pregunta antes de asumir
ningún valor. La autenticación de Tiritaito for Creators es token
propio (TT_WRITE_TOKEN), no Application Password — definitivo.

REFERENCIA CONSTANTE:
- GUIA_AVADA_LOCAL.md para la mecánica de Avada y Local.
- METODOLOGIA_CONSTRUCCION.md para dónde vive cada pieza de contenido.
- ALCANCE_WEB_NUEVA.md para qué sección se construye y su prioridad.
- 04_ENTORNO_LOCAL.md para el entorno — nunca datos de producción.

8. AL TERMINAR — SEÑAL DE DOCUMENTACIÓN: si esta sesión confirmó,
cambió o resolvió algo que no coincide con lo ya escrito en
GUIA_AVADA_LOCAL.md, METODOLOGIA_CONSTRUCCION.md o ALCANCE_WEB_NUEVA.md
(p. ej. "esto sí/no funciona como se pensaba", "se descarta tal
opción"), dilo explícitamente al final en 2-3 líneas: qué cambió y qué
documento debería reflejarlo. Esa nota se lleva al Proyecto de
Investigación para aplicarse allí — no la apliques tú mismo aquí.

Si algo no encaja con el sistema, avisa antes de proceder.
Ad maiorem Dei gloriam.
```

**Base de conocimiento:** `00_CORE.md` · `04_ENTORNO_LOCAL.md` ⚠️ *pendiente de reescribir con el patrón de token (ver `GUIA_AVADA_LOCAL.md` Sección 2)* · `GUIA_AVADA_LOCAL.md` · `METODOLOGIA_CONSTRUCCION.md` · `ALCANCE_WEB_NUEVA.md`

---

### Proyecto 4 — Diseño y Avada (Hna C)

```
Eres el asistente de diseño y producto de Hna C para Tiritaito.com.
Ella dirige el equipo, tiene visión del público y buen criterio
visual, pero está aprendiendo lo técnico poco a poco. Tu misión es
ayudarla a tomar decisiones de diseño y alcance — NUNCA escribir
código para que ella lo pegue en ningún sitio.

CÓMO EXPLICAR:
- Cualquier concepto técnico se explica primero en una frase sencilla,
  "para todos los públicos", antes de más detalle si ella lo pide.
- Nunca asumas que conoce un término técnico previo — este proyecto no
  comparte memoria entre chats salvo lo que esté en los archivos base.

TU PAPEL EN LAS DECISIONES:
- Ayúdala a evaluar si una propuesta aporta valor real al usuario
  final, o si es una idea técnica interesante pero prescindible.
- Cuando una petición requiera código real: "esto lo tiene que
  construir Hno A en el Proyecto Web Nueva".
- Para configuraciones visuales de Avada, pasos concretos, no código.

FORMATO: tablas y comparativas para decisiones con varias opciones.
Diagramas cuando ayude a ver una estructura o flujo. Resúmenes cortos
al final de cada conversación con la decisión tomada.

AL TERMINAR — SEÑAL DE DOCUMENTACIÓN: si en esta conversación Hna C
tomó una decisión real de alcance o producto, díselo explícitamente:
"esto conviene anotarlo en ALCANCE_WEB_NUEVA.md — llévaselo a Hno C en
el Proyecto de Investigación". No lo des por sobreentendido.

TONO: Cercano, claro, en español, sin tecnicismos sin explicar.
Directo y veraz — nunca suavices un problema real por quedar bien.

Ad maiorem Dei gloriam.
```

**Base de conocimiento:** `00_CORE.md` + `ALCANCE_WEB_NUEVA.md`

---

### Proyecto 5 — Tiritaito for Creators (Hno A)

```
Eres el desarrollador de "Tiritaito for Creators", la PWA autocontenida
en un único archivo HTML que usa el equipo de editores para publicar
sin entrar al panel de WordPress. NO trabajas con Avada ni Code
Snippets aquí — trabajas directamente sobre el archivo HTML de la app.

PROTOCOLO DE SESIÓN (obligatorio):

AL EMPEZAR: pide que se adjunte el archivo tiritaito-creators-vX-XX.html
actual si no está ya en el chat. Confirma la versión antes de tocar nada.

DURANTE:
- str_replace quirúrgico. NUNCA reescribas el archivo entero salvo
  petición explícita.
- Incluye siempre la cabecera de la función siguiente en el fragmento
  nuevo, para delimitar bien el cambio.
- Todo onclick/oninput inline → función colgada de window.*.
- Verifica sintaxis JS antes de entregar.

AL TERMINAR: verifica sintaxis, entrega HTML actualizado + changelog,
cierra con resumen de sesión (qué se hizo, cambios técnicos, bugs
resueltos, bugs nuevos, técnicas aprendidas).

SEGURIDAD: la app se autentica con token propio (TT_WRITE_TOKEN) —
NUNCA Application Password, ese patrón quedó descartado definitivamente.
Nunca expongas ni sugieras exponer credenciales de forma distinta a
como ya está resuelto en el sistema actual.

ADN VISUAL: border-radius 25px, var(--tt-*), iOS/Apple limpio.

TONO: Directo, resolutivo, en español. Código primero, resumen breve
después.

Ad maiorem Dei gloriam.
```

**Base de conocimiento:** `00_CORE.md` · `01_CREATORS_APP.md` *(el HTML de la app y el CHANGELOG.md se adjuntan manualmente cada sesión — nunca permanente)*

---

### Proyecto 7 — WPMobile.app, pendiente de definir

Sin instrucciones redactadas todavía. Se activa cuando el equipo empiece a configurar la app en serio (ver Sección 7). Sin dueño asignado por ahora — a decidir cuándo llegue el momento.

**Datos y Métricas (Hna MF) — cancelado.** No habrá un Proyecto de Claude dedicado a esto. El checklist de Google Search Console (Sección 8) queda documentado igualmente, por si se necesita de forma puntual sin un proyecto propio.

### Proyecto 6 — Web Nueva · Repuesto

Mismas instrucciones que el Proyecto 3 (Web Nueva fusionado), copiadas tal cual. Base de conocimiento **vacía a propósito** — se conecta vía GitHub (Sección 5), no con archivos sueltos, para que nunca se desincronice con la cuenta principal.

---

## 4. Economía de tokens en cuentas gratuitas — verificado

✅ **Confirmado en support.claude.com (julio 2026):** las cuentas gratuitas pueden crear un máximo de **5 Proyectos**. La expansión automática de memoria (RAG) **solo está disponible en planes de pago** (Pro, Max, Team, Enterprise) — en cuentas gratuitas, cuando el contenido del Proyecto se acerca al límite de contexto, no se expande solo; hay que quitar documentos o crear un Proyecto nuevo.

*Fuente: support.claude.com/en/articles/9517075-what-are-projects*

**Reglas de economía de tokens (prácticas oficiales, ya aplicadas por el equipo):**

| Práctica | Por qué funciona | Ya lo hace el equipo |
|---|---|---|
| Subir documentos base una sola vez al Proyecto | El contenido queda cacheado — reutilizarlo cuesta mucho menos que repetirlo | ✅ Con los documentos de `tiritaito-docs` |
| Instrucciones del Proyecto cortas y generales | El detalle específico de cada tarea va en el chat | Ver Sección 3 |
| Agrupar varias preguntas en un solo mensaje | Cada mensaje nuevo reprocesa toda la conversación anterior | Mejorable |
| Empezar una conversación nueva cada cierto número de intercambios | Una conversación larga es más cara por mensaje nuevo que una recién empezada | ✅ Ya se hace |
| Pedir un resumen y pegarlo como primer mensaje de un chat nuevo | Evita cargar todo el historial viejo | ✅ Es el protocolo de sesión de Tiritaito for Creators |
| Eliminar archivos obsoletos de la base de conocimiento | Los archivos viejos compiten por el mismo espacio fijo | Aplicar al migrar a `tiritaito-docs` — retirar duplicados |

**Nota sin confirmar oficialmente, pero consistente con el comportamiento observado:** subir muchos archivos pequeños a un Proyecto puede activar mecanismos de búsqueda parcial antes de lo esperado. La práctica de mantener pocos documentos consolidados (como se ha hecho en toda esta reestructuración) evita este riesgo.

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

⚠️ **Matiz que no estaba en la investigación anterior, y que conviene conocer:** existen reportes documentados (incluyendo issues públicos del repositorio de Anthropic) de que el conector a veces muestra **"Conectado"** sin que el contenido esté realmente actualizado o accesible — no es el comportamiento habitual, pero ha ocurrido. La recomendación de la investigación original de probarlo antes de confiar en él para algo delicado (como `04_ENTORNO_LOCAL.md`) sigue siendo válida y ahora tiene más peso.

### 5.4 Estructura del repositorio (ya creada)

```
tiritaito-docs/
├── README.md
├── 00-nucleo-tecnico/
│   ├── 00_CORE.md
│   ├── 01_CREATORS_APP.md
│   ├── 02_REF_PODCAST.md
│   └── 04_ENTORNO_LOCAL.md          ⚠️ datos sensibles — repo privado obligatorio
├── 01-producto/
│   └── ALCANCE_WEB_NUEVA.md
├── 02-metodologia/
│   ├── METODOLOGIA_CONSTRUCCION.md
│   └── MIGRACION_CONTENIDO.md
├── 03-guias-practicas/
│   └── GUIA_AVADA_LOCAL.md
├── 04-vision-y-equipo/
│   ├── ARQUITECTURA_Y_ROADMAP.md
│   └── ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md   ← este documento
└── historico/
    ├── INFORME_ESTRATEGICO_2026_1.md
    ├── INVESTIGACION_HERRAMIENTAS_2026.md
    ├── METODOLOGIA_WEB_NUEVA_v2.md
    └── Diagnóstico original v1.md
```

### 5.5 Paso a paso

1. **Repositorio privado** `tiritaito-docs` (ya creado) — solo `.md`, sin código ni el HTML de la app
2. **Conectar GitHub a cada cuenta que lo necesite:** Configuración → Conectores → GitHub → Autorizar → elegir repositorio(s)
3. **Añadir el repositorio al Proyecto correspondiente:** dentro del Proyecto → "+" → "Add from GitHub" → marcar solo las carpetas/archivos que ese Proyecto necesita (no hace falta marcarlos todos en todos los proyectos)
4. **Flujo de cada día:** editar en github.com (icono del lápiz) → Commit → pulsar "Sync now" en el conector antes de una sesión importante → trabajar con normalidad
5. **Verificación de fiabilidad (recomendado la primera vez y periódicamente, por el matiz de la Sección 5.3):** cambiar una palabra en un documento de prueba, sincronizar, y preguntar algo que Claude solo pueda responder bien si leyó la versión nueva

---

## 6. Decisiones que requieren coordinación previa

Decisiones que, si las toma una sola persona sin comunicarlo, rompen el trabajo de otros. Requieren la reunión semanal o comunicación explícita por escrito:

1. Añadir una nueva clave a `wp_options` (afecta al contrato de datos de la PWA Creators)
2. Cambiar el endpoint REST o el sistema de autenticación (afecta a todos los widgets y a la PWA)
3. Añadir o eliminar un snippet PHP (riesgo de conflictos de función — "Cannot redeclare")
4. Cambiar el nombre de una clase CSS de módulo (puede romper estilos en páginas)
5. Actualizar Avada o WordPress (puede requerir revisión de snippets)
6. Cambiar el formato de una sección ya decidida en `ALCANCE_WEB_NUEVA.md` (como ya ocurrió con "Hombres de Dios" — ver `METODOLOGIA_CONSTRUCCION.md`)

---

## 6.1 Protocolo de sincronización documental — cómo no perder lo que se decide en Local

**El problema que resuelve:** hasta ahora, que un hallazgo de Hno A o una decisión de Hna C llegue a actualizarse en `tiritaito-docs` depende de que alguien se acuerde de traerlo a este Proyecto de Investigación. Sin un paso explícito, se pierde — exactamente el mismo patrón de fragmentación que motivó reconstruir toda esta documentación desde cero.

### Regla de activación

No todo necesita anotarse. La señal es: **¿esto cambia una recomendación ya escrita, cierra una pregunta abierta, o es información nueva que el resto del equipo necesita saber?**

- ✅ Anotar: "Post Cards sí funciona sobre el CPT de santos, probado en Local" · "el menú sí tiene submenús, se descarta Off Canvas simple" · "Hna C decide que Oraciones sí lleva audio desde v1"
- ❌ No hace falta: probar un color y descartarlo, un intento de layout que no llegó a nada, dudas resueltas dentro de la misma sesión sin cambiar ninguna recomendación

### Mecanismo — un paso añadido a los Proyectos 3 y 4

Las instrucciones de **Proyecto 3 (Web Nueva)** y **Proyecto 4 (Diseño y Avada)** en la Sección 3 incluyen ahora un paso final: si la sesión confirma, cambia o resuelve algo que no coincide con lo escrito en `tiritaito-docs`, Claude lo señala explícitamente al terminar — qué cambió y qué documento debería reflejarlo. Esa nota (un resumen de 2-3 líneas, no hace falta más) se trae a este Proyecto de Investigación para aplicarse.

### Tabla de mapeo — tipo de cambio → documento → quién lo aplica

| Tipo de cambio | Documento a actualizar | Quién lo aplica |
|---|---|---|
| Configuración de Avada ya probada y estable (Global Options, Header/Footer Builder, Layouts) | `GUIA_AVADA_LOCAL.md` | Hno C (aquí), a partir del resumen de Hno A |
| Confirmación sobre un elemento nativo (p. ej. si Post Cards funciona sobre X contenido) | `GUIA_AVADA_LOCAL.md` Sección 17 / `METODOLOGIA_CONSTRUCCION.md` | Hno C |
| Decisión de producto o alcance (qué sección se construye, con qué formato) | `ALCANCE_WEB_NUEVA.md` | Hna C decide, Hno C redacta el cambio |
| Nueva pieza de contenido ya construida — dónde vive y con qué patrón | `METODOLOGIA_CONSTRUCCION.md` Sección 3 | Hno C |
| Cambio en `wp_options`, endpoint REST o autenticación | `00_CORE.md` / `04_ENTORNO_LOCAL.md` | Hno A directamente (son documentos de su propiedad) — avisar igualmente a Hno C si afecta a algo cruzado con otro documento |
| Cambio de organización del equipo o cuentas | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` | Hno C, con confirmación de Carlitos |

### Qué no cambia

Esto no sustituye la Sección 6 de arriba — las decisiones que requieren coordinación previa se siguen hablando en la reunión semanal *antes* de tocarlas. Este protocolo es para lo que ya se decidió o confirmó y hay que dejar constancia *después*.

---

## 7. WPMobile.app — qué prever ahora, sin construir todavía

### 7.1 Cómo funciona

No es una app hecha desde cero — es un plugin de WordPress que **envuelve el contenido de la web actual** (entradas, páginas, tipos de contenido) en una app nativa para iOS y Android. Se actualiza sola cuando se publica contenido nuevo en WordPress. Incluye notificaciones push y estadísticas de uso alojadas en el propio WordPress.

### 7.2 Implicación directa para cómo se construye la web nueva

Como WPMobile.app lee directamente el contenido de WordPress, **cuanto más estructurado esté algo (Posts, CPT) más fácil será que aparezca bien en la app.** Contenido que vive solo dentro de un Code Block de Avada con diseño muy personalizado puede no traducirse bien a la vista simplificada de la app.

**Esto conecta directamente con la decisión ya tomada para "Hombres de Dios"** (Layouts de Avada sobre Posts/CPT, ver `METODOLOGIA_CONSTRUCCION.md` Sección 4) — es, sin buscarlo, la opción que mejor prepara el terreno para una futura integración con WPMobile.app.

### 7.3 Licencia — pendiente de verificar, no asumir

La licencia de WPMobile.app es por sitio de WordPress — un contrato distinto al de Avada. No hay confirmación de una política de "sitio de staging gratuito" equivalente. **Antes de conectar WPMobile.app al Local, hay que preguntar directamente a su soporte técnico** si permite tener el plugin activo en dos sitios (producción + local) sin conflicto — mejor prevenir que encontrarse un bloqueo.

### 7.4 Limitación de pruebas en Local

Confirmado por el soporte de WPMobile.app (documentación de la investigación original): la app puede funcionar contra un sitio local, pero solo si el móvil está en la misma red que el ordenador. Fuera de esa red, no alcanza el contenido. Dos caminos para probarla:
- Misma red WiFi de la comunidad — suficiente para pruebas internas rápidas
- **Live Link** de Local by Flywheel — URL pública temporal, permite probar desde cualquier red

### 7.5 Checklist de "preparar el terreno" — sin construir todavía

- [ ] Al definir la estructura de "Hombres de Dios" y "Oraciones", tener presente que un formato basado en entradas facilita la futura integración
- [ ] Preguntar a soporte de WPMobile.app por la política de sitio de desarrollo/staging antes de asumir que hace falta una segunda licencia
- [ ] Recordar: probar la app contra el Local requiere misma red, o Live Link

---

## 8. Google Search Console — checklist de configuración completa

Ya está activo, pero "activo" no es lo mismo que "bien aprovechado". Sin un Proyecto de Claude dedicado (cancelado, ver Sección 2), este checklist queda como referencia para quien lo aplique puntualmente — probablemente Hna MF de forma directa, sin acompañamiento de Claude:

- [ ] Verificar que el sitemap XML está enviado (Search Console → Sitemaps)
- [ ] Revisar el informe de Cobertura/Páginas indexadas — identificar páginas importantes que Google no está indexando
- [ ] Revisar Core Web Vitals — datos objetivos de velocidad real que sufren los usuarios
- [ ] Exportar el listado de páginas con más clics de los últimos 12 meses — insumo clave para el plan de redirecciones 301 de `MIGRACION_CONTENIDO.md` Sección 2
- [ ] Configurar alertas de errores 404/rastreo — especialmente útil justo después del lanzamiento

---

## 9. Estado actual — honesto, sin adornar

| Elemento | Estado real |
|---|---|
| Proyecto 1 (Web Vieja) | ✅ Configurado |
| Proyecto 2 (Investigación) | ✅ Activo — es este mismo proyecto |
| Proyecto 3 (Web Nueva, fusionado) | ✅ Configurado |
| Proyecto 4 (Diseño y Avada) | ✅ Configurado |
| Proyecto 5 (Tiritaito for Creators) | ✅ Configurado |
| Proyecto 6 (Web Nueva — Repuesto) | 🔵 Recomendado, no creado |
| Proyecto 7 (WPMobile.app) | 🔵 Sin definir todavía, no urgente |
| ~~Datos y Métricas~~ | ❌ Cancelado — no se va a crear |
| Repositorio de GitHub `tiritaito-docs` | ✅ Creado, con la estructura de carpetas de la Sección 5.4 |
| `04_ENTORNO_LOCAL.md` | ⚠️ Necesita reescritura por dos motivos: el dominio documentado (`tiritaito.local`) era incorrecto — el real es **`tiritaito-real.local`**, corregido el 7 julio 2026 en `GUIA_AVADA_LOCAL.md` — y el patrón de autenticación (Application Password) quedó obsoleto. Ver `GUIA_AVADA_LOCAL.md` Sección 2 |

---

## 10. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Hno A: crear la cuenta de Repuesto y copiar en ella, tal cual, las instrucciones del Proyecto 3 (Web Nueva fusionado)
2. Hno A: conectar GitHub a la cuenta de Repuesto en cuanto se cree, apuntando al mismo repositorio `tiritaito-docs`
3. Hno A: reescribir `04_ENTORNO_LOCAL.md` con el patrón de token, y subir la versión corregida al repositorio
4. Hno A: verificar con soporte de WPMobile.app la política de sitio de desarrollo antes de conectar nada

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Cuándo se crea la cuenta de Repuesto y el acceso GitHub para ella? | Necesario antes de que el límite de mensajes de la cuenta principal de Hno A se convierta en un problema real |
| 2 | ¿WPMobile.app admite el plugin activo en dos sitios (producción + Local) sin licencia adicional? | Bloquea cualquier prueba de la app contra el entorno Local |
| 3 | ¿Quién asume el Proyecto 7 (WPMobile.app) cuando llegue el momento? | Actualmente sin asignar |

---

*Para la mayor gloria de Dios · tiritaito.com*

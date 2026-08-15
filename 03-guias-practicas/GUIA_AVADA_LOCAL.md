# TIRITAITO.COM — Guía Avada + Local
**Referencia técnica completa: licencia, infraestructura, Global Options, Header/Footer Builder, Layouts, elementos nativos y convenciones**
*Audiencia principal: Hno A · Fusiona y verifica: INVESTIGACION_HERRAMIENTAS_TRABAJO_2026.md (Parte 3-4) + INFORME_ESTRATEGICO_2026_1.md (Parte 2, 5, 8) + METODOLOGIA_WEB_NUEVA_v2.md (Secciones 3-11) + 04_ENTORNO_LOCAL.md*
*Verificado contra documentación oficial de avada.com — julio 2026 · Corregido contra sesión de diagnóstico en Local — 11-12 julio 2026 · Ampliado con principio de Responsive — 13 julio 2026 · Ampliado con altura de sección y previsualización — 14 julio 2026 · Ampliado con mecanismo de dos ordenadores (Cloud Backups) — 31 julio 2026*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento y cómo usarlo

Este documento responde una sola pregunta: **¿cómo configuro y construyo con Avada en el entorno Local, y por qué así y no de otra forma?** Es la referencia práctica para los Frentes 1 (configurar el Local) y 3 (Global Options + Layouts) del plan de trabajo actual.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Un dato técnico del stack (endpoints, `wp_options`, convenciones JS) | `00_CORE.md` |
| Qué secciones tiene la web y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| Dónde construir una pieza concreta de contenido ya decidida (Global vs Guardado vs Snippet) | `METODOLOGIA_CONSTRUCCION.md` |
| Qué contenido de la web vieja migrar y cómo | `MIGRACION_CONTENIDO.md` |
| Qué elemento de Avada resuelve una necesidad de contenido concreta, y con qué certeza | `CATALOGO_ELEMENTOS_AVADA.md` |
| El valor real y actual de un ajuste concreto de Global Options, sin depender de que las tablas de este documento estén al día | `03-guias-practicas/exports/avada-global-options.json` |
| **Cómo configurar Avada y Local paso a paso, y qué puede hacer Avada de fondo** | **Este documento** |

Este documento no repite el mapa de qué-va-dónde por sección (eso vive en `METODOLOGIA_CONSTRUCCION.md`) — se centra en la mecánica de Avada y Local en sí. Tampoco repite, elemento por elemento, qué sirve para qué necesidad de contenido — eso vive en `CATALOGO_ELEMENTOS_AVADA.md`.

**Nota honesta:** todo lo marcado ✅ está confirmado contra documentación oficial de ThemeFusion/Avada o contra una sesión real de trabajo en Local (con fecha de verificación). Todo lo marcado 🔲 solo se puede confirmar dentro de Local, probando de verdad — no lo des por sentado sin probarlo.

---

## 1. Licencia de Avada en Local — resuelto y verificado

**No hace falta comprar una licencia nueva.**

✅ **Confirmado directamente en avada.com (última actualización: 5 febrero 2026):**
- Cada licencia de Avada incluye un sitio de staging/desarrollo/local gratuito, sin coste extra — uno por licencia.
- Ese sitio se reconoce automáticamente como staging si su dominio coincide con una lista cerrada de patrones. La lista de **TLDs completos** (no solo subdominios) incluye explícitamente: `*.dev`, `*.local`, `*.staging`, `*.test`.
- **`tiritaito-real.local` encaja directamente en el patrón `*.local`** — que es exactamente el sufijo que usa Local by Flywheel por defecto. Califica sin pasos adicionales.

⚠️ **Corrección de dominio (7 julio 2026):** el dominio real del sitio en Local **no es `tiritaito.local`, es `tiritaito-real.local`** (con guion, sin mayúscula inicial en código). El nombre correcto no cambia la conclusión de la licencia — sigue terminando en `.local`, sigue calificando — pero cualquier código, constante o URL que use el nombre viejo fallará. Esta versión del documento ya usa el nombre correcto en todas partes.

**Paso práctico:** en el WordPress del Local, ir a Avada → Registro, y pegar el mismo código de compra que se usó en producción. El sistema debería reconocerlo como staging automáticamente.

**Si aun así pide una licencia nueva:** no seguir sin más — contactar con soporte de Avada citando esta política (`avada.com/documentation/avada-registration-and-licensing-faq/`) antes de pagar nada. Es más probable que sea un problema de detección de dominio que una exigencia real.

⚠️ **Matiz importante que no estaba en la investigación anterior:** la lista de patrones válidos es cerrada. Si en algún momento se decide *no* usar el dominio actual de Local (`tiritaito-real.local`) y renombrarlo a otra cosa, hay que comprobar antes que el nuevo dominio sigue encajando en alguno de los patrones — si no, no calificará como staging automáticamente.

---

## 2. Infraestructura del entorno Local

⚠️ **Corrección crítica (confirmada con WP-CLI, 12 de julio de 2026): el Local NO usa subdirectorio `/blog/`.** WordPress está instalado directamente en la raíz del sitio — a diferencia de producción, que sí vive en `/blog/`. Confirmado con `pwd` y `ls -la blog` en el Site Shell de Local: la carpeta `blog` no existe en el Local. Intentar alinear esto con producción (añadiendo `/blog` en Ajustes → Generales) provocó un bucle infinito de redirecciones en el panel de administración y la pérdida de todos los estilos visuales — WordPress buscaba sus propios archivos en una ruta que no existe. Se corrigió revirtiendo desde WP-CLI (el panel estaba bloqueado por el bucle):

```
wp option update siteurl 'https://tiritaito-real.local'
wp option update home 'https://tiritaito-real.local'
wp rewrite flush --hard
```

| Componente | Detalle |
|---|---|
| Entorno | Local by Flywheel |
| Dominio local | **`tiritaito-real.local`** — sin `/blog/`, confirmado 12 julio 2026 |
| WordPress | Instalado en la **raíz** del sitio — **no** en un subdirectorio `/blog/` como producción. No intentar alinear esto con producción vía Ajustes → Generales; provoca un bucle de redirecciones (ver arriba) |
| SSL | Local lo genera automáticamente — comportamiento igual que en producción. Si el navegador sigue avisando de "no seguro", falta pulsar "Trust" en la pestaña SSL del sitio dentro de Local |

⚠️ **Riesgo real, ya ocurrido una vez (26 julio 2026):** el entorno Local se perdió por
completo antes de una sesión de trabajo y hubo que reconstruir el backend desde cero
(snippet PHP, CPT, campos ACF). Sin causa documentada todavía. Recomendación: guardar un
Blueprint actualizado de vez en cuando (Sección 2, tabla de herramientas, más abajo) como
red de seguridad.

### Constantes del entorno (usar siempre estas, nunca las de producción de `00_CORE.md`, en código que corra en Local)

```
WP_BASE  = https://tiritaito-real.local/wp-json
APP_PIN  = 1234   ⚠️ cambiar antes de lanzar
```

✅ **Resuelto y definitivo:** el sistema de autenticación de Tiritaito for Creators es **token propio (`TT_WRITE_TOKEN`)** desde v1-04, no Application Password — vía header `X-TT-Token`. Es la decisión definitiva del equipo, confirmada contra el HTML real de la app el 26 de julio de 2026.

✅ **Resuelto (26 julio 2026) — discrepancia de tokens:** el snippet "TT Creators + Biblioteca — Endpoint central" se reconstruyó desde cero en la sesión del 26 de julio (el Local se había perdido por completo, ver arriba). El token vive en `define()` dentro del propio snippet — ya no hay discrepancia entre comentario y código porque el snippet es nuevo. Decisión de Carlitos: se queda en `define()`, no se migra a `wp-config.php`. ✅ Confirmado por segunda vez contra el archivo PHP real y completo, ya subido a `apps/v2/snippet-tt-creators-endpoint-central.php` — la primera copia compartida ese mismo día resultó ser de la web vieja, no de esta.

### Herramientas exclusivas de Local (aprovecharlas)

| Herramienta | Para qué sirve | Dónde está |
|---|---|---|
| Base de datos directa (Adminer) | Inspeccionar `wp_options` sin pasar por el panel de WP — más rápido al depurar | Botón "Database" en Local |
| WP-CLI | Comandos de WordPress desde terminal — pruebas rápidas de snippets, y la única vía para corregir el sitio si el panel de administración queda bloqueado (ver corrección de `/blog/` arriba) | Botón "Open Site Shell" en Local |
| Live Link | URL pública temporal del sitio local — para que Hna C o el equipo lo vean sin estar en el mismo ordenador/red. ⚠️ **No fiable para verificar diseño/tipografía/CSS** — confirmado que Local reescribe las rutas del dominio local sobre la marcha pero reconoce abiertamente que no las coge todas (fuente: localwp.com/help-docs/local-features/live-links/); afecta a cualquier CSS/JS/fuente con ruta absoluta, no solo a `@font-face`. Sirve solo para enseñar estructura general a Hna C — para QA visual real, usar el modo responsive de Chrome DevTools o Safari directamente sobre `tiritaito-real.local`. El nombre del Live Link (ej. `sneaky-doctor`) es aleatorio y cambia en cada reinicio — no perseguirlo. Desde julio 2026, Local añade usuario/contraseña automáticamente al Live Link; si se comparte con Hna C, pasarle también esas credenciales | Botón "Live Link" en Local |
| Blueprint | Guarda el estado actual del sitio como plantilla reutilizable — también sirve como copia de seguridad ante una pérdida del entorno (ver aviso arriba). Es una foto fija en el momento de guardarlo, no un enlace en vivo: importarlo en otro sitio no lo mantiene sincronizado con el original después | Menú del sitio → "Save as Blueprint" |
| Cloud Backups (Local v10+) | Backups a la nube (Google Drive o Dropbox), restaurables en otro ordenador conectado a la misma cuenta — ver Sección 2.1 para el mecanismo completo de trabajo con dos ordenadores que el equipo usa esto para resolver | Pestaña "Backups" del sitio, dentro de Local |

### Checklist antes de cada sesión de trabajo en Local

- [ ] Confirmar que `tiritaito-real.local` sigue siendo el dominio activo, **sin** `/blog/` (puede cambiar si se recrea el sitio)
- [ ] Confirmar que la Avada está registrada como staging (Avada → Registro no pide licencia nueva)
- [ ] Confirmar que el certificado SSL sigue en estado **"Trusted"** en la pestaña SSL del sitio dentro de Local — puede perderse al recrear el sitio, y si no está confiado puede llegar a romper silenciosamente las respuestas de la API (`/wp-json` devolviendo un carácter suelto en vez del JSON completo)
- [ ] Si el código incluye una URL o credencial, verificar que viene de este documento o de `04_ENTORNO_LOCAL.md` actualizado — nunca de producción
- [ ] Si el Local se ha recreado recientemente: verificar que el snippet PHP, el CPT `novedades` y la Options Page de ACF siguen existiendo — no darlos por hecho
- [ ] Si esta sesión va a trabajar con dos ordenadores a la vez: seguir el procedimiento de la Sección 2.1, no improvisar
- [ ] Si hay duda sobre el valor real de un ajuste de Global Options — o si algo
  se ha roto y se sospecha de un cambio de configuración — consultar
  directamente `03-guias-practicas/exports/avada-global-options.json` antes de
  asumir el valor de cualquier tabla de este documento. Las tablas de aquí
  pueden quedar desactualizadas si alguien cambia un ajuste sin documentarlo;
  el export es la fuente más fresca sin necesidad de abrir Local.

---

## 2.1 ⭐ Trabajar con dos ordenadores a la vez — mecanismo acordado (31 julio 2026)

**Contexto:** el equipo ha empezado a usar un segundo ordenador con Local instalado, para
las ocasiones en que hace falta que dos personas construyan en Local al mismo tiempo. Local
**no sincroniza nada entre ordenadores de forma automática ni en tiempo real** — no existe
esa función, ni en Local ni de forma segura por ningún otro medio razonable, porque
WordPress vive sobre una base de datos MySQL completa, no sobre archivos de texto sueltos:
dos personas escribiendo en dos copias de esa base de datos al mismo tiempo, sin
coordinación, no se puede fusionar automáticamente después — alguien tiene que decidir cuál
de las dos copias es la buena. Esta sección documenta la regla que el equipo ha acordado
para ese arbitraje, y el procedimiento paso a paso alrededor de ella.

**Regla de fondo, ya sabida por el equipo antes de este incidente concreto** —
`ARQUITECTURA_Y_ROADMAP.md` Recomendación 3 ya advertía: *"Dos personas tocando los mismos
archivos de Avada en Local pueden crear conflictos. Decidir quién construye en cada sprint;
el otro revisa."* Lo de abajo es la aplicación práctica de esa recomendación al caso
concreto de dos ordenadores físicos distintos, con la herramienta real (Cloud Backups) que
el equipo ya tiene configurada para ello.

### El mecanismo, en una frase

> El ordenador principal ("el de toda la vida") es siempre la fuente de verdad. El segundo
> ordenador solo se activa para sesiones puntuales de trabajo conjunto, arrancando desde una
> copia reciente del principal. Al terminar, **gana siempre el ordenador principal** — lo
> hecho en el segundo se vuelve a aplicar a mano sobre el principal, nunca al revés.

### Cuándo se usa

Solo cuando de verdad hace falta que dos personas construyan en Local a la vez. Fuera de
esas sesiones, el segundo ordenador no se toca — el ordenador principal sigue siendo el
único activo, como hasta ahora.

### Herramienta — Local Cloud Backups (Local v10+)

El mecanismo se apoya en la función de backups a la nube de Local (ver tabla de
"Herramientas exclusivas de Local" más arriba en esta sección). Confirmado en la práctica
por el equipo, 31 de julio de 2026: conecta ambos ordenadores a la misma cuenta de Google
Drive de trabajo del equipo (no personal), vía Local → sitio → pestaña **Backups** → **Back
up to:**.

⚠️ **Aviso ya confirmado en este proyecto — error típico al conectar por primera vez:** si
al pulsar "Create backup" aparece un error citando `insufficient authentication scopes` o
`Error 403: Insufficient Permission`, la causa casi siempre es que el consentimiento de
Google no se completó del todo la primera vez (Local se quedó con un permiso parcial, no el
necesario para crear/editar archivos). Solución confirmada por el propio soporte de Local
(community.localwp.com, abril 2026) y verificada por el equipo en este mismo incidente:

1. En Local: **Settings → Connected Accounts → Google Drive → Disconnect Account**
2. Volver a conectar desde cero
3. En la pantalla de Google, pulsar **"Continuar"** en la pantalla de consentimiento
   completa — no cerrarla antes de tiempo

Si el error persiste tras esto, revisar si la cuenta de Google es una cuenta de Google
Workspace con políticas de administrador que puedan estar restringiendo el permiso — no es
el caso de la cuenta de trabajo actual del equipo, pero es la siguiente pista documentada
por soporte de Local si volviera a pasar con otra cuenta en el futuro.

### Procedimiento paso a paso

**1. Antes de empezar la sesión conjunta:**
- En el ordenador principal: pestaña **Backups** → **Create backup** — esto deja un punto de
  partida limpio y reciente, con fecha conocida
- En el segundo ordenador: si ya existe un sitio `tiritaito-real` vacío o desactualizado,
  **borrarlo primero** — no puede convivir con el sitio nuevo que se va a crear desde el
  backup, ambos usarían el mismo dominio `tiritaito-real.local`
- Crear el sitio en el segundo ordenador eligiendo **restaurar desde el backup** recién
  creado en la cuenta de Google Drive compartida, en vez de "sitio nuevo en blanco"

**2. Durante la sesión conjunta:**
- Cada persona trabaja en su propio ordenador con normalidad
- Recomendado (no obligatorio, pero fuertemente aconsejado — ver nota más abajo): llevar
  en paralelo una tabla de registro de cada cambio de configuración hecho en Avada
  (panel exacto, campo, valor), para poder reconstruirlo a mano después sin depender de la
  memoria

**3. Al terminar la sesión conjunta:**
- **Gana siempre el ordenador principal.** El sitio del segundo ordenador no se sube ni se
  fusiona automáticamente con nada
- Los cambios hechos en el segundo ordenador durante la sesión se vuelven a aplicar **a
  mano**, uno por uno, directamente en el ordenador principal — usando la tabla de registro
  del punto 2 como guía paso a paso
- Una vez aplicados y verificados en el principal, el segundo ordenador queda otra vez sin
  usarse hasta la siguiente sesión conjunta

### ⚠️ Límite honesto de este mecanismo — léase antes de asumir que es una solución completa

Este procedimiento **no elimina el riesgo de perder trabajo** — lo reduce y lo hace
recuperable, que es distinto. Si durante la sesión conjunta se avanza mucho en el segundo
ordenador y luego, por lo que sea, no se lleva bien la cuenta de qué se cambió, ese trabajo
se pierde de verdad al no fusionarse nunca de forma automática con el principal. La tabla de
registro del punto 2 es la pieza que hace que ese riesgo sea gestionable — sin ella, cada
sesión con dos ordenadores es una apuesta a que nadie olvide nada.

**No existe, ni en Local ni de forma segura por otro medio, una sincronización instantánea o
en tiempo real entre dos instalaciones de Local en ordenadores distintos** — se investigó
explícitamente esta posibilidad (31 julio 2026) y se confirmó que no existe como función de
producto. Herramientas externas de sincronización de bajo nivel podrían acercarse a eso a
costa de mucho más riesgo técnico (corrupción de base de datos si se sincroniza mientras
está en uso) — no evaluadas ni recomendadas todavía; si el equipo quiere explorar esa vía
en algún momento, merece una sesión de investigación dedicada solo a eso, no una decisión
tomada de pasada aquí.

---

## 3. Mapa mental — cómo organiza Avada una página

Avada organiza cada página en **cuatro secciones estructurales**. Un **Layout** es el contenedor que decide qué versión de cada sección se muestra, y bajo qué condiciones.

```
LAYOUT (contenedor con condiciones)
   ├── Header Section     → ¿qué cabecera se usa aquí?
   ├── Page Title Bar      → ¿se muestra la barra de título?
   ├── Content Section     → el contenido de la página en sí
   └── Footer Section      → ¿qué pie se usa aquí?
```

Existe siempre un **Layout Global** por defecto. Se pueden crear **Layouts Condicionales** adicionales (ej. "todas las entradas de Podcast", "la página de inicio", "landing pages sin menú") y Avada aplica el más específico cuando hay varios que podrían coincidir.

✅ **Confirmado — la causa raíz del problema de la web vieja:** en cuanto se asigna una Header Section personalizada al Layout Global, las opciones de cabecera de Global Options **dejan de tener efecto** — todo pasa a controlarse desde esa Header Section. Esto explica exactamente lo que pasó antes: header desactivado en Global Options pero sin ninguna Header Section asignada al Layout Global → ni header de Avada ni header propio → hueco en blanco, pero con el HTML del header igualmente descargado por el usuario.

---

## 4. Global Options — el sistema de diseño global

### 4.0 Punto de partida — Avada Setup Wizard

✅ **Confirmado en documentación oficial (avada.com, verificado julio 2026):** Avada arranca automáticamente el **Setup Wizard** justo después de registrar la licencia. Bifurca en dos caminos:

| Camino | Qué hace | Cuándo tiene sentido |
|---|---|---|
| **Prebuilt Website** | Importa un sitio completo — colores, tipografía y páginas ya resueltos de una de las +100 plantillas de Avada Studio | Si se quiere partir de una base visual ya montada y adaptarla |
| **New Website** | Se eligen paleta de color y tipografía desde cero (Paso 3), y opcionalmente se importa un header/footer de Avada Studio (con opciones de invertir colores o no importar imágenes) | Si se prefiere construir la identidad visual de Tiritaito desde el principio, sin heredar nada de una plantilla |

Termina en menos de 5 minutos y deja enlaces directos a Layouts, Menús, Global Options y la cuenta de soporte.

*Fuente: avada.com/documentation/how-to-use-the-avada-setup-wizard/*

### 4.0.1 Registro de la sesión real — 7 julio 2026

El equipo eligió el camino **New Website** ("Sitio Nuevo"). Esto es lo que salió de cada paso, y lo que queda pendiente de cada uno:

**Paso 3 — Colores.** El Wizard solo admite 8 colores iniciales, no los 13 de la paleta `--tt-*`. Se mapearon los 8 primeros en orden claro→oscuro (recomendación oficial de Avada): `--tt-bg`, `--tt-surf2`, `--tt-sep`, `--tt-txt4`, `--tt-red`, `--tt-red-d`, `--tt-txt2`, `--tt-txt`. 🔲 Pendiente: añadir los 5 restantes (`--tt-red-bg`, `--tt-txt3`, `--tt-green`, `--tt-orange`, `--tt-alert`) después del Wizard, en Global Options → Colors → Add New Color.

**Paso 4 — Tipografía.** Confirmado: este paso **no admite subir fuentes propias** — solo un esquema prediseñado de Avada, tamaño base y proporción de escala. Se usó como placeholder para poder avanzar, no como configuración final. 🔲 Pendiente (clave, bloquea la identidad visual real): registrar "Yeah Papa" en Avada → Options → Typography → Custom Fonts, y aplicarla en la pestaña Heading Typography (H1-H6). Sin confirmar todavía si el `.woff2` ya subido a Media Library se puede enlazar directo desde ahí o si hay que volver a subirlo en ese panel específico — a probar en Local.

**Paso 5 — Diseños (Header).** Se eligió la plantilla **"Studio"** de Avada Studio como punto de partida. Cuatro cambios pedidos no son posibles dentro del Wizard — quedan para después, editando el Header en Avada → Layouts → Layout Section Builder (Sección 5 de este documento):

| Cambio pedido | Dónde se hará | Estado |
|---|---|---|
| Quitar la barra negra superior | Sin confirmar si es una fila del Header Builder o el Top Bar legacy de Global Options — hay que abrirlo en Local para saberlo | 🔲 Pendiente |
| Quitar buscador + iconos de usuario/carrito | Eliminar esos elementos individuales del Header | 🔲 Pendiente |
| Fondo de acuarela | Background del contenedor del Header → Imagen → Cover | 🔲 Pendiente |
| Tipografía "Yeah Papa" en el título | Sin confirmar si "Studio" usa el Site Title de WordPress (Global Options → Logo) o un elemento Título suelto | 🔲 Pendiente |

**Paso 6 — Contenido y Características.** Las páginas de arranque (Hogar, Acerca de, Servicios) son plantillas desechables de Avada Studio — no coinciden con las secciones reales de `ALCANCE_WEB_NUEVA.md` (Seminarios, Ejército de Intercesores, Hombres de Dios...) y se sustituirán al construir de verdad. Sugerencia pendiente de aplicar: cambiar "Servicios" por "Contacto".

Las Características (Features) se revisaron una por una, no en bloque:

| Función | Estado | Nota |
|---|---|---|
| Eventos | ✅ Activada | Candidata a evaluar para "Seminarios — próximos" |
| Formas (Forms) | ✅ Activada | Para formularios de contacto/oración |
| Off Canvas | ✅ Activada | Coincide con el método de menú móvil recomendado en Sección 9.1 |
| Portafolio | ❌ Desactivada (confirmado 14 agosto 2026 contra `status_fusion_portfolio` en el export real) | Ya no es "a reconsiderar" — la decisión ya se tomó y se aplicó (ver también `CATALOGO_ELEMENTOS_AVADA.md` Sección 11). Quedan valores de estilo no-de-fábrica en los campos de Portfolio (columnas=1, slug reescrito a mano en español, permalink activado) de una edición anterior al desactivarlo — sin efecto real mientras el CPT esté apagado |
| Herramientas de desarrollo (ACF) | ✅ Activada | Confirmado: ACF Pro viene incluido gratis con la licencia de Avada, con soporte nativo en Avada Dynamic Content. Ya en uso real (Novedades, Devocional — ver `METODOLOGIA_CONSTRUCCION.md`) |
| Modo de mantenimiento, Gestión de medios | ✅ Activadas | Sin objeción |
| Comprar, Foro, Marca personalizada, Chat en vivo | ☐ Sin activar | Correcto — sin caso de uso documentado en Tiritaito |

### 4.0.2 Sistema de diseño — configuración manual (independiente del Wizard)

Todo lo que se configura aquí se aplica en todo el sitio automáticamente. Es la base que hay que cerrar **antes** de construir ninguna página. Si el Setup Wizard ya dejó una base (Sección 4.0.1), esto es la verificación/ajuste fino sobre esa base — en concreto, añadir los 5 colores que el Wizard no permitió y registrar la tipografía real, no un punto de partida alternativo.

### 4.0.3 ⭐ "Default Page Template = 100% Width" — mecanismo de tres capas, no un interruptor simple (añadido 11 agosto 2026)

Confirmado por dos sesiones de trabajo independientes (Global Options — Layout, y la revisión de Blog Single Post): este ajuste **no fuerza nada a ir de borde a borde**. Funciona en tres capas:

1. La opción global (`Avada → Options → Layout → Default Page Template = 100% Width`) por sí sola no cambia el aspecto de ninguna página.
2. Lo único que hace es dar a cada **Container**, al crearse, la *opción disponible* de activar `Interior Content Width: 100%` en sus propios ajustes.
3. Ir a pantalla completa sigue siendo **siempre una decisión explícita por Container**, sección por sección. Sin ese toggle activado en el Container concreto, el contenido sigue centrado en el `Site Width` fijo (`1200px`, ver Sección 4.1).

El equipo revirtió esta opción de `Site Width` a `100% Width` por acuerdo del 30 de julio de 2026, precisamente para tener esa flexibilidad disponible sección por sección sin tener que cambiar el valor global cada vez. **No confundir con "Boxed"** (otro valor de `Layout`, no de `Default Page Template`): Boxed no es "contenido centrado con margen" — es un marco con sombra alrededor de todo el sitio. El efecto de contenido centrado que sí se quiere se consigue con `Layout = Wide` + `Site Width` fijo, que es la configuración real actual — ver Sección 4.1.

⚠️ **Nota de honestidad documental:** una nota de trabajo anterior daba esta opción por "conflicto crítico ya resuelto" en sentido contrario a lo aquí descrito. No se ha podido localizar el pasaje original de esa nota para verificar la contradicción exacta — puede tratarse de una versión de este documento anterior a esta edición. Lo que sí está confirmado con evidencia directa, de forma independiente por dos sesiones de trabajo, es que el valor real y actual en Local es `100% Width`, con el mecanismo de tres capas descrito arriba.

### 4.1 Colores (Avada → Global Options → Colors)

13 slots de color disponibles en todos los selectores de color del Builder. La paleta `--tt-*` va aquí completa.

✅ **Orden real confirmado en Local (11 agosto 2026), sustituye la asignación teórica de una versión anterior de esta tabla:** verificado directamente en el panel de Avada (nombre ↔ hex ↔ variable), con triple confirmación — verificación directa en el panel, reconstrucción independiente cruzando otra sesión de trabajo, y coincidencia exacta con el orden claro→oscuro ya descrito en la Sección 4.0.1 como resultado del propio Wizard (7 julio 2026):

| Slot | Variable | Hex | Nombre en el panel real de Avada |
|---|---|---|---|
| Color 1 | `--tt-bg` | `#FFFFFF` | Fondo Blanco |
| Color 2 | `--tt-surf2` | `#F5F5F7` | Superficie secundaria |
| Color 3 | `--tt-sep` | `#c7c7cc` | Separador |
| Color 4 | `--tt-txt4` | `#86868b` | Marcador de posición de texto |
| Color 5 | `--tt-red` | `#BF4646` | Rojo Director (ver nota de nomenclatura abajo) |
| Color 6 | `--tt-red-d` | `#A33B3B` | Rojo Hover |
| Color 7 | `--tt-txt2` | `#3a3a3c` | Texto secundario |
| Color 8 | `--tt-txt` | `#1d1d1f` | Texto Principal |

⚠️ **Nomenclatura confusa dentro del propio Avada:** el panel llama "Rojo Director" al rojo principal de marca (Color 5) — no es un nombre intuitivo, no es un color especial de "director". Se recomienda renombrarlo a "Rojo Principal" en el propio panel de Avada si hay ocasión, para no confundirlo con "Rojo Hover" (Color 6).

🔲 **Colores 9-13 (`--tt-red-bg`, `--tt-txt3`, `--tt-green`, `--tt-orange`, `--tt-alert`) siguen sin cargar** a fecha de la última verificación (11 agosto 2026) — se añaden progresivamente según necesidad. Mientras tanto, cualquier snippet o panel que necesite uno de estos 5 colores debe usar hex directo, con una nota explícita de qué variable `--tt-*` sustituye, para poder cambiarlo a `Var(--awb-colorN)` en cuanto se cargue.

Una vez completado, Hna C puede seleccionar "Color 1" en cualquier botón de Avada y siempre obtiene el color de marca correcto — sin depender de código.

### 4.2 Tipografía (Avada → Global Options → Typography)

- Fuente primaria (cuerpo): **Helvetica Neue** — fuente de sistema iOS, sin descarga.
- Fuente de encabezados: **Yeah Papa** — ya cargada como `.woff2` en Media Library (`uploads/2026/04/YeahPapa.woff2`). Confirmar que está asignada aquí, no solo declarada en CSS.
- Tamaños/pesos/interlineado configurables por tipo de elemento, con valores responsive distintos en móvil/escritorio, sin CSS.
- **Ninguna fuente de Google Fonts debería aparecer** — su presencia en el sitio actual (vía `@import` en el CSS del podcast) es deuda técnica a no replicar.

✅ **Confirmado 12 julio 2026:** el desbordamiento de texto visto inicialmente en los botones de "Qué hacemos" (Seminarios / Grupo de alabanza / Día de familias) vía Live Link **no era un fallo de maquetación real** — era "Yeah Papa" fallando al cargar por la limitación de Live Link con fuentes (ver tabla de Live Link en Sección 2). Accediendo directamente a `tiritaito-real.local`, el diseño se ve correcto, con la fuente cargando bien y sin desbordamiento. No requiere ninguna corrección en Avada — queda cerrado, no es un pendiente de diseño abierto.

### 4.3 Espaciado global

Padding de secciones por defecto y margen entre elementos por tipo, definidos una vez y aplicados en todo el sitio.

### 4.4 Rendimiento (Avada → Global Options → Advanced → Performance)

⚠️ **Aviso importante (11 agosto 2026):** el panel real de Performance es considerablemente más grande de lo que documentaba la tabla original de esta sección — esa tabla cubría solo una fracción de las opciones reales. La tabla de abajo incorpora ahora los ajustes confirmados con capturas reales del panel completo.

Activar en la web nueva desde el primer día:

| Opción | Activar | Nota |
|---|---|---|
| Lazy Load Images | ✅ Sí | Imágenes cargan cuando se necesitan |
| Lazy Load Iframes | ✅ Sí | Para vídeos embebidos |
| Remove jQuery Migrate | ✅ Sí | Script de compatibilidad innecesario — nota: esto es lo que mantiene "Elastic Slider" desactivado, ver Sección 9 |
| Disable Emojis | ✅ Sí | WordPress carga scripts de emoji por defecto |
| Container Lazy Loading | ✅ Sí | Secciones Avada en diferido |
| Google Fonts Loading | `swap` | Evita texto invisible mientras carga |
| Font Face Rendering | ✅ **Block → Swap Non-Icon Fonts** (confirmado 11 agosto 2026) | Evita pantalla en blanco mientras carga "Yeah Papa" |
| Preload Key Fonts | ✅ **Icon Fonts → All** (confirmado 11 agosto 2026) | Precarga también la tipografía real, no solo iconos |
| Enable Video Facade | ✅ **Off → On** (confirmado 11 agosto 2026) | Carga solo la miniatura de YouTube hasta que se pulsa play — relevante para Rincón de Nico, Charlas de la Biblia y vídeos de seminarios |
| Optimize Offscreen Rendering | ✅ **Off → On** (confirmado 11 agosto 2026) | Mejora de rendimiento en páginas largas (Hombres de Dios, listados), con exención automática del contenido de apertura |
| Critical CSS | Evaluar | Reservado a Fase 4 — ver Sección 4.5 |
| Preload Resources | Evaluar | Útil para la fuente "Yeah Papa" |

🔲 **Aviso técnico sin resolver, no bloqueante (11 agosto 2026):** apareció el mensaje "JS Compiler is disabled. File does not exist or access is restricted" aunque el interruptor correspondiente esté en On — puede ser un estado desactualizado del panel. Recomendado probar "Reset Avada Caches" (Avada → Maintenance) y comprobar si el aviso desaparece.

### 4.5 ⚠️ Avada Performance Wizard — existe, pero NO ahora

Además de configurar la tabla de arriba a mano, Avada tiene un asistente guiado en **Avada → Performance**. Antes de usarlo, un aviso importante:

✅ **Confirmado en documentación oficial (avada.com, verificado julio 2026):** el propio fabricante advierte que el Performance Wizard **debe ejecutarse solo cuando el sitio está prácticamente terminado**, nunca durante la configuración inicial. El Wizard escanea el sitio y desactiva funciones/elementos que no detecta en uso — si se ejecuta ahora, con la web nueva recién empezada, puede desactivar cosas que hagan falta dentro de dos semanas, y hay que acordarse de reactivarlas a mano.

Lo que hace, para cuando llegue el momento:
- Escanea y sugiere desactivar Features/Elements de Avada no usados (con botón "Find Recommendations")
- Optimiza qué subconjuntos de Font Awesome cargar
- Configura compilación/carga asíncrona de CSS y JS, y generación de Critical CSS
- Recomienda antes de empezar: correr PageSpeed Insights/Lighthouse en incógnito para tener una foto de referencia

**Dónde vive esto en el Roadmap:** no es Fase 1 (Sección 15) — es **Fase 4, "QA y velocidad"**, en `ARQUITECTURA_Y_ROADMAP.md`, justo antes del lanzamiento. Lo que sí se puede — y se debe — hacer ya es la tabla manual de arriba (4.4), que son ajustes seguros de activar desde el principio y no dependen de que el sitio esté terminado.

*Fuente: avada.com/documentation/how-to-use-the-performance-wizard/*

---

## 5. Header Builder — guía paso a paso

**Esta es la capacidad que más cambia el flujo de trabajo respecto a la web vieja.**

1. Ir a **Avada → Layouts → Layout Section Builder**
2. En el desplegable, elegir tipo **Header**, ponerle un nombre (ej. "Header Global Tiritaito")
3. Pulsar **Create New Layout Section**
4. Se abre el editor estándar de WordPress — elegir **Avada Builder** o **Avada Live** para diseñarlo
5. Construir el header con los elementos habituales: logo, menú, iconos — con total libertad, incluyendo columnas y fondos
6. Guardar
7. Ir a **Avada → Layouts → Layout Builder**
8. En el **Layout Global**, pasar el ratón sobre la sección Header → aparece un icono `+`
9. Seleccionar la Header Section recién creada

A partir de este momento, ese header aparece en todo el sitio automáticamente — sin ponerlo entrada por entrada.

**Lo que esto elimina respecto a la web vieja:**

| Criterio | Global Element manual (como antes) | Header Builder |
|---|---|---|
| HTML generado por Avada aunque esté "oculto" | Sí | No |
| Hay que añadirlo a cada página | Sí | No — se asigna una vez en el Layout |
| Responsive nativo | Hay que programarlo | Incluido |
| Condicional por tipo de página | No | Sí |
| Sticky header | Con CSS | Con un toggle |

---

## 6. Footer Builder — guía paso a paso

Mismo proceso exacto que el Header, cambiando el tipo a **Footer** en el paso 2 de la Sección 5. Mismas capacidades: responsive nativo, condicional por Layout, sin CSS de por medio.

---

## 7. Layouts condicionales — guía paso a paso

Ejemplo práctico: "Rincón de Nico" con un header más colorido, sin el menú principal.

1. **Avada → Layouts → Layout Builder → Create New Layout**
2. Ponerle nombre, ej. "Layout Rincón de Nico"
3. Asignar una Header Section distinta (nueva, más lúdica, creada con el proceso de la Sección 5)
4. Pulsar **Conditions** (parte inferior del Layout) y definir cuándo se aplica — ej. "categoría = Rincón de Nico"
5. Guardar

**Regla de prioridad confirmada:** los Layouts no se combinan entre sí. Si una página encaja en varias condiciones, gana el más específico o el de mayor prioridad. Si varios Layouts condicionales podrían aplicar a la misma página, **su posición en la lista importa: los que están más abajo tienen prioridad sobre los de más arriba.**

**Para páginas sin cabecera** (landing pages, o el "Ejército de Intercesores" si se decide que sea así): el método más simple **no es un Layout** — es ir a las opciones de la página individual (Page Options, Sección 10) y desactivar el header solo ahí.

---

## 8. Global Elements vs Saved Elements vs Code Snippets — árbol de decisión

**Esto es lo más importante que corrige la investigación anterior. Léelo antes de construir cualquier componente reutilizable.**

**Principio de fondo que gobierna todo este árbol (reforzado 26 julio 2026):** en cualquier
rama de este árbol, la prioridad es **usar el menor código posible.** Antes de llegar a
"Code Snippets: shortcode parametrizable", intenta primero si un elemento nativo de Avada
(Sección 9) combinado con ACF (Dynamic Content) resuelve lo mismo sin escribir una sola
línea de PHP o JS nuevo. El código es el último recurso del árbol, no un punto de partida
igual de válido que los demás. Para saber qué elemento nativo encaja con una necesidad
concreta, empieza por `CATALOGO_ELEMENTOS_AVADA.md` antes de recorrer este árbol de cero.

### 8.1 La corrección

Un elemento **Global** de la Avada Library sincroniza el contenido al 100% en todas sus instancias: editar una copia edita todas las demás, en todo el sitio, automáticamente. **No existe un modo de "misma estructura, contenido distinto por instancia" dentro de un elemento Global** — los elementos Global no admiten contenido dinámico ni campos variables. Sirve para "esto debe decir exactamente lo mismo en todas partes" (footer, aviso legal, CTA de donación fija).

Lo que sí varía por instancia es un elemento **Guardado (no-global)**: se inserta como plantilla de partida, y cada copia insertada es independiente — puedes cambiar título, texto o vídeo de una sin afectar a las demás. La contrapartida: si cambia el **diseño** más adelante, no hay sincronización — hay que entrar página por página.

*Fuente: avada.com/documentation/avada-builder-library-global-elements/ · avada.com/documentation/how-to-use-the-avada-builder-library/*

### 8.2 Árbol de decisión completo

```
ANTES DE CONSTRUIR NADA NUEVO
│
├── 1. ¿Lo resuelve un elemento NATIVO de Fusion Builder, solo o combinado
│      con ACF (Dynamic Content)? (Sección 9, o CATALOGO_ELEMENTOS_AVADA.md)
│      → SÍ: úsalo. No hay snippet que mantener. ESTA ES LA OPCIÓN
│        PREFERIDA — mínimo código posible (ver principio de fondo arriba).
│      → NO: sigue.
│
├── 2. ¿Ya existe un snippet global de Tiritaito que hace esto o algo parecido?
│      → SÍ: reutilízalo o extiéndelo con un parámetro nuevo.
│      → NO: sigue.
│
└── 3. Hay que construir algo nuevo. ¿Dónde vive?
       │
       ├── ¿Se usa en más de una página?
       │     ├── NO → Code Block de la entrada en Avada Live (nunca Code Snippets global)
       │     └── SÍ → ¿El contenido es EXACTAMENTE igual en todas las páginas donde aparece?
       │                ├── SÍ, siempre idéntico → Avada Library: elemento GLOBAL
       │                └── NO, cambia por página, pero la ESTRUCTURA se repite
       │                      ├── Lo mantiene Hna C/editores, sin código → Avada Library: GUARDADO
       │                      └── Lo mantiene Hno A, con lógica de servidor → Code Snippets: shortcode
       │                            parametrizable (patrón [tt_podcast], ya probado) — ÚLTIMO RECURSO
       │
       └── En cualquier caso: constrúyelo pensando en el siguiente santo, el siguiente
           seminario, el siguiente "Rincón de X" — con parámetros, no contenido fijo.
```

**Nota de conexión con la Sección 8.3 (Responsive):** este árbol decide *dónde vive* una pieza. Una vez decidido eso, la Sección 8.3 aplica siempre, sin excepción, sobre cualquiera de las cuatro ramas de salida — Global, Guardado, shortcode o Code Block.

### 8.3 Tabla resumen de decisión

| Opción | Cuándo usarla | Coste |
|---|---|---|
| **Elemento nativo + ACF (Dynamic Content)** | Contenido dinámico o repetible que un Slider, Post Cards, Toggles u otro elemento nativo ya sabe pintar | Ninguno — cero código, mantenimiento 100% visual |
| **Global** (Avada Library) | Contenido idéntico en todas partes (footer, CTA fija) | Ninguno — es su propósito |
| **Guardado / no-global** (Avada Library) | Estructura repetida, contenido distinto, lo mantiene Hna C/editores sin tocar código | Si cambia el diseño, hay que actualizar cada instancia a mano |
| **Shortcode parametrizable** (Code Snippets) | Estructura repetida, contenido distinto, lo mantiene Hno A, y ninguna opción nativa lo resuelve | El diseño se actualiza en un solo sitio y se propaga automáticamente — mismo patrón que `[tt_podcast]`, pero es la opción de más mantenimiento de las cuatro |

---

## 8.4 ⭐ Principio de trabajo — Responsive es parte del diseño, no un paso aparte

**Añadido 13 julio 2026, a partir de un aviso de Hno C y una sesión real de Hno A construyendo la vista previa de "Conecta cada día" en la home.** Investigación completa y hallazgos verificados contra documentación oficial: ver `RESPONSIVE_AVADA.md` (carpeta `historico/` una vez incorporado aquí — o donde Carlitos decida archivarlo).

### Qué es esto y qué NO es

Esto **no es una plantilla de layout que hay que replicar** en todas las secciones. No existe un
"molde correcto" de cuántas columnas o qué orden usar — eso depende de cada contenido y lo
decide quien construye la sección, caso por caso. **Lo que sí es fijo es la pregunta que hay
que hacerse siempre:** *"¿cómo se ve/comporta esto en ordenador, en tablet y en móvil — y es
la mejor forma para cada uno, no solo una copia reducida del diseño de ordenador?"*

Esa pregunta se hace **desde el principio de cada construcción**, no como revisión final.

### El principio, en una frase

> Antes de dar cualquier sección, Container, Columna o elemento de código por terminado,
> revisar y decidir conscientemente cómo se comporta en las 3 pantallas — Desktop, Tablet
> (Medium) y Móvil (Small) — usando las herramientas nativas de Avada para ello. No asumir
> que el comportamiento automático por defecto es suficiente sin comprobarlo.

### Por qué hace falta decirlo explícitamente

Avada ya es responsive de fábrica en lo básico (apila columnas solo, por ejemplo) — pero ese
comportamiento automático no es suficiente para diseños algo más elaborados (varias columnas
con contenido distinto entre sí, texto grande, imágenes de fondo, botones anchos). Ahí hace
falta un ajuste consciente — y hasta el 13 de julio de 2026, ese ajuste no era un paso
explícito en ningún checklist del proyecto. El problema nunca fue que Avada no supiera
hacerlo — fue que faltaba el hábito de comprobarlo antes de cerrar.

### La herramienta — Responsive Option Sets

✅ **Confirmado en documentación oficial de Avada:** dentro del editor (Fusion Builder / Avada
Live), los elementos principales — **Container, Columna, Botón, Imagen, Bloque de texto,
Título** — tienen un icono "Responsive" que permite configurar valores **distintos e
independientes** para las 3 pantallas: Desktop (Large) / Medium (Tablet) / Small (Móvil).

Lo que se puede ajustar de forma distinta por pantalla, sin código, incluye: ancho de
columna, **orden de columna** (qué se muestra primero), márgenes, espaciado interno, imagen
de fondo, y alineación de varios elementos. Un valor puesto en la vista Desktop se hereda por
defecto en Tablet y Móvil — solo hay que tocar la pantalla concreta si de verdad necesita un
valor distinto ahí.

**Confirmado en la práctica (sesión Hno A, 13 julio 2026):** construyendo la vista previa de
"Conecta cada día" en la home, se necesitaba que 3 columnas iguales en Desktop/Tablet
pasaran a un layout distinto en Móvil. Se logró completo con Column Width + Column Order,
cada uno con su propio ajuste por pantalla — sin ninguna fila duplicada ni código. Confirma
que la herramienta funciona tal como documenta Avada; lo único que hacía falta era usarla
con disciplina, no un problema técnico de fondo.

### Tamaño de letra automático — otra pieza a revisar, no solo el layout

Además del layout (columnas), Avada reduce el tamaño de letra automáticamente en pantallas
pequeñas mediante **Responsive Typography** — con dos ajustes globales en Avada → Options →
Responsive:

| Ajuste | Qué controla |
|---|---|
| Responsive Typography Sensitivity | En 0, el texto nunca se reduce. Cuanto más alto, más se reduce en pantallas pequeñas |
| Minimum Font Size Factor | El tamaño mínimo al que puede llegar el texto, para que nunca sea ilegible |

Si un texto se ve desbordado o cortado en móvil, esto es lo primero a revisar — puede ser un
problema de este ajuste global, no del Container o Columna concretos.
✅ **Valor real confirmado (14 agosto 2026), contra `03-guias-practicas/exports/avada-global-options.json`:**
`typography_sensitivity = 0.30` (no está en 0 — el texto SÍ se reduce de forma
automática en pantallas pequeñas). Deja de ser un valor desconocido: si algún
texto se ve desbordado en móvil, la pregunta ya no es "¿está activo este
ajuste?" sino "¿es 0.30 la intensidad correcta, o hay que subirla o bajarla?".
`typography_factor` (el campo relacionado) está en `1.50`.

### Dónde se decide el punto exacto de cambio de pantalla

Avada → Options → Responsive → **Element Responsive Breakpoints** — aquí se define, en
píxeles, a partir de qué ancho empieza "Medium" y a partir de cuál "Small".

✅ **Confirmado en Local (11 agosto 2026) — ya no es un pendiente.** Los 5 breakpoints reales
de Avada (Small/Medium del panel de Elementos, Header/Site Content/Sidebar, Grid) partían de
valores de fábrica distintos entre sí (480/768, 800×3, 1000) y se subieron todos a
**~1024px**, alineados con el estándar `1024/768/480px` ya usado por el código de los
snippets (`00_CORE.md` Sección 7). Verificado con capturas reales antes y después, en varios
anchos de pantalla, sin ningún síntoma de rotura en ningún punto probado. El punto de
entrada a "modo escritorio completo" se movió correctamente al lugar esperado. **Único
matiz sin cerrar del todo:** el comportamiento específico del header en la franja
800-1024px no se pudo confirmar del todo por separado — en todas las capturas disponibles
el header ya aparecía en su versión compacta en todos los anchos probados. Si el header de
Tiritaito usa un único diseño sin importar el ancho (lo más probable dado el diseño
"Studio" ya elegido), esto no tiene relevancia práctica.

*Nota aparte: el punto de quiebre del Header tiene su propio ajuste independiente (Header
Responsive Breakpoint, por defecto 800px) — distinto de los otros tres. Revisar también.*
*Nota (14 agosto 2026): el valor `768px` que usa el código de los snippets
(`00_CORE.md` Sección 7) es una convención propia del proyecto, no un campo
de Avada. Confirmado contra el export real: los ajustes de breakpoint de
Avada usan `1024` (varios campos) y `480` (`visibility_small`) — no busques
un `768` dentro del panel de Avada, porque no existe ahí.*

### Cómo se revisa — nunca por Live Link

⚠️ **Ya confirmado en la Sección 2 de este documento:** Live Link no es fiable para revisar
diseño/tipografía — no reescribe siempre todas las rutas absolutas. La revisión responsive se
hace con:

1. El icono Responsive dentro del propio editor de Avada (Desktop/Medium/Small) — el método
   principal, en tiempo real mientras se construye
2. Modo responsive de Chrome DevTools o Safari, directamente sobre `tiritaito-real.local`
3. Un móvil real, en la misma red que el Local — la prueba más fiable de todas

### Cómo aplicar esto sin caer en rigidez

Este principio **no dicta la solución** (no dice "usa 3 columnas" o "usa 1 grande + 2
pequeñas") — dicta que la pregunta se haga siempre, y que la respuesta a esa pregunta sea una
decisión consciente, visible en el propio Builder (con los iconos Responsive usados de
verdad), no una casualidad de lo que Avada hace por defecto sin tocar nada.

**Ver también:** Sección 8.4-bis (altura de sección y previsualización, mismo espíritu de
"decidir con criterio, no por inercia del editor"), Sección 16 (Checklist maestro) y Sección
9.1 (menú móvil, que ya sigue este mismo principio con Off Canvas Builder).

---

## 8.4-bis ⭐ Principio de trabajo — Altura de sección y previsualización antes de construir

**Añadido 14 julio 2026, a partir de la sesión de equipo sobre la página de inicio (Novedades,
estructura de secciones y forma de trabajar con Claude en el Proyecto 3).**

Esta sección vive justo después de la 8.4 (Responsive) porque comparte el mismo espíritu:
antes de dar una sección por cerrada, hay una pregunta concreta que hay que hacerse
conscientemente — no confiar en el comportamiento por defecto del editor sin comprobarlo.

### Altura de sección — no ocupar la pantalla completa por defecto

> Ninguna sección de Avada (Container en Fusion Builder) debe ocupar la pantalla completa
> por defecto — ni en la página de inicio ni en el resto de páginas de la web — salvo que
> haya una razón explícita y consciente para usar `min-height: 100vh` (ej. un hero de
> apertura muy concreto, si se decide así de forma deliberada). El criterio es que cada
> sección ocupe aproximadamente lo que su contenido necesita, no que llene el viewport
> porque es el comportamiento por defecto del editor.

Aplica en las tres vistas de la Sección 8.4, con más fuerza en Small (móvil), donde cada
scroll adicional cuesta más que en escritorio. El motivo de fondo, tal como lo planteó el
equipo: evitar que la página se sienta excesivamente larga, sobre todo al enlazar varias
secciones seguidas en la home.

**Fuente de esta regla:** decisión de equipo (sesión del 14 de julio de 2026), no
documentación oficial de Avada — es un criterio de diseño propio de Tiritaito, no una
limitación o recomendación de ThemeFusion. Avada permite tanto secciones de altura completa
como de altura acotada; la elección es nuestra, no una restricción técnica.

**Aplicación conocida — Novedades:** debe ocupar aproximadamente "media pantalla", con
inspiración visual externa (una referencia que Carlitos compartirá en foto con Hno C) — 🔲
el patrón visual exacto (si lleva una noticia destacada grande + rejilla debajo, solo
rejilla, u otro formato) queda sin cerrar todavía. La primera captura compartida en esta
sesión de investigación correspondía al hero/slider principal de la web de referencia, no
al bloque de noticias/novedades — no se toma como válida para este criterio; se decide
cuando se comparta la referencia correcta y se construya la home de verdad.

### Previsualización con bocetos — dónde vive esta capacidad

Antes de construir una sección nueva o rediseñar una existente, el Proyecto 3 (Web Nueva,
cuenta de Hno A) debe proponer 2-3 bocetos visuales (mockups) de opciones distintas,
respetando el ADN visual de Tiritaito, para elegir antes de construir en Avada — en vez de
construir y luego rehacer si la elección no convence. El detalle completo de este criterio
vive en las Instrucciones personalizadas de ese Proyecto (Sección 0.2, ver
`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 3) — no se repite aquí para no duplicar
mantenimiento; este documento solo señala que existe y por qué conecta con el principio de
altura de sección de arriba: decidir bien el boceto ayuda a decidir con criterio cuánto alto
necesita realmente la sección, en vez de heredar un `100vh` por defecto sin pensarlo.

**Nota técnica:** esto es una capacidad de Claude (generación de imágenes/bocetos dentro de
la conversación), no una función de Avada — no hay ningún ajuste de Avada que "genere
previsualizaciones". El boceto es solo apoyo para decidir; la construcción real sigue siendo
manual en Fusion Builder, siguiendo el árbol de decisión ya existente (Sección 8).

**Este principio aplica a toda la web, no solo a la home** — el mismo criterio de "boceto
antes de construir" y "altura acotada salvo excepción justificada" vale para Qué Hacemos,
Tiritaito, Biblioteca, Hombres de Dios, y cualquier sección futura.

---

## 9. Elementos nativos de Fusion Builder — tabla verificada

⚠️ **Esta tabla se mantiene deliberadamente corta — cubre solo los elementos con relación directa a la mecánica de Avada/Local que documenta este archivo.** Para el catálogo completo de qué elemento de Avada resuelve cada necesidad de contenido de Tiritaito, con nivel de certeza por entrada (confirmado en Local / documentado, sin probar), ver `CATALOGO_ELEMENTOS_AVADA.md` — no se duplica aquí para no mantener la misma información en dos sitios.

| Necesidad | Solución custom actual | Elemento nativo de Avada | Estado |
|---|---|---|---|
| Menú móvil / navegación | HTML/CSS/JS a mano | Ver Sección 9.1 — **corrección importante** | ⚠️ Ver abajo |
| Carrusel de imágenes | JS a mano + Swiper de `unpkg.com` | **Image Carousel Element** — 5 layouts, autoplay, swipe táctil, lightbox integrado | ✅ Confirmado. Pensado para imágenes; no confirma vídeo embebido |
| Carrusel con vídeo | (mismo JS a mano) | **Avada Slider Element** — imagen Y vídeo (YouTube/Vimeo) por diapositiva | ✅ Confirmado — mejor candidato si el carrusel mezcla vídeo |
| Accordion desplegable | `toggle-ios` custom | **Toggles Element** — modo Toggle (uno abierto) o Accordion (varios abiertos) | ✅ Confirmado |
| Modal / vídeo al clic | Modal custom | **Lightbox Element** (imagen/vídeo en overlay) o **Modal Element** (contenido libre) | ✅ Ambos confirmados |
| Listado con diseño de tarjeta | — | **Post Cards** (Avada Library) | ✅ Confirmado en Local (piloto de Novedades, 22-23 julio 2026): ordena de forma nativa por Custom Field ACF (ej. fecha). ❌ NO filtra por valor de campo de forma nativa — decisión de equipo (26 julio 2026): no se construye el filtro; el listado de Novedades muestra todas las entradas, activas u ocultas, sin distinción. El campo `activo` queda como control interno del editor en la app, sin efecto en la web pública. Si en el futuro "Seminarios pasados" u otra sección sí necesitan filtrar de verdad, ahí haría falta el hook `fusion_post_cards_shortcode_query_override` |
| Rotar entre entradas distintas (ej. los 9 santos) | — | **Post Slider** — no confundir con "Slideshows" (Options), que solo controla varias imágenes dentro de UNA misma entrada, no rotación entre entradas distintas | 🔲 Identificado como el elemento correcto, confirmado de forma independiente por dos sesiones de trabajo (11 agosto 2026) — sin configurar ni probar todavía en Local. Ver `CATALOGO_ELEMENTOS_AVADA.md` Sección 4 |
| Reproductor de audio | 3 sistemas distintos (`.pp-*`, `.mp-*`, `.hmds-*`) | No es un elemento Avada — consolidación de snippets propios | Ver `METODOLOGIA_CONSTRUCCION.md` |

### 9.1 ⚠️ Corrección — menú móvil: Flyout Menu es método legacy

La investigación anterior recomendaba el **Flyout Menu clásico** (Avada → Opciones → Menú → Menú Móvil → Estilo = Flyout) como reemplazo nativo del hamburguesa hecho a mano. Verificando ahora mismo contra la documentación oficial, hay un matiz que cambia la recomendación:

✅ **Confirmado (documentación oficial, actualizada):**
- El Flyout Menu clásico (vía Global Options → Menú Móvil, o nativo en Header Layout 6) **sigue existiendo y funcionando**, pero la propia documentación de Avada lo marca explícitamente como **"legacy method"**, remitiendo a un método actualizado.
- El **método actual recomendado por Avada es el Off Canvas Builder**: se construye el menú como contenido dentro de un Off Canvas (popup o barra deslizante), y se dispara desde un icono en el Header con Dynamic Content → "Open Off Canvas". Da más control de diseño (imagen, botones, tipografía) que el Flyout clásico.
- **La limitación de submenús se mantiene en ambos métodos:** ni el Flyout clásico ni el Off Canvas resuelven de forma nativa un menú con varios niveles — "Flyout menus don't work well with menus with submenu items" está confirmado en la documentación oficial. Existen workarounds documentados por la comunidad (CSS + JS para forzar apertura de submenú en el móvil), pero no es una solución "gratis" de Avada. **Sigue sin confirmar (11 agosto 2026) si el menú de Tiritaito lleva submenús desplegables** — ver pregunta abierta #1 más abajo.

⚠️ **Estado real de la construcción, no solo de la decisión (actualizado 11 agosto 2026):** existe ya un Off-Canvas creado en Local con el nombre "Menu Movil" (confirmado por captura), pero con sus Conditions desactivadas — es decir, registrado como borrador, no publicado. No hay evidencia todavía de que esté diseñado por dentro (menú real de WordPress asignado, estilos aplicados) ni probado en pantalla. No confundir "existe una entrada con ese nombre" con "está construido y funcionando".

**Para la guía completa de configuración del Off Canvas Builder** — los dos tipos (Popup / Sliding Bar), pasos de configuración, triggers, accesibilidad, hooks para el caso raro en que haga falta código, y tabla de problemas comunes — ver `CATALOGO_ELEMENTOS_AVADA.md` Sección 3. No se repite aquí para no duplicar mantenimiento; esta sección se queda solo con la decisión de fondo (Off Canvas sobre Flyout) y su estado real de construcción.

**Recomendación actualizada:** antes de dar el menú móvil por construido, confirmar la pregunta ya abierta — **¿el menú de Tiritaito tiene submenús desplegables?**
- Si NO tiene submenús → Off Canvas Builder (método actual, mejor control de diseño que el Flyout clásico), tal como ya se decidió.
- Si SÍ tiene submenús → ninguno de los dos métodos nativos los resuelve limpiamente; evaluar en Local si el menú "Classic" o "Modern" (sin Flyout) del Menú Móvil estándar cubre el caso mejor que forzar un Flyout con workaround.

*Fuentes: avada.com/documentation/flyout-menu/ · avada.com/documentation/mobile-menu-settings/ · avada.com/documentation/how-to-make-a-flyout-menu-with-the-off-canvas-builder/ · avada.com/documentation/global-option-header-layouts/*

---

## 10. Page Options — opciones por página individual

Cada página/entrada tiene un panel "Avada Page Options" que permite, sin código:
- Desactivar el header solo en esa página (método recomendado para landing pages, Sección 7)
- Cambiar el ancho del contenido
- Ocultar la barra de título
- Definir una clase CSS personalizada para esta página concreta

El `.page-id-XXXX` del CSS de la web vieja es exactamente esto hecho con código — en la web nueva, se hace desde aquí, sin CSS.

---

## 11. Avada Studio y Dynamic Content — mención breve

- **Avada Studio:** biblioteca de plantillas preconstruidas (páginas, secciones, elementos). Útil como punto de partida visual que luego se adapta al ADN Tiritaito — nunca como resultado final sin personalizar.
- **Dynamic Content:** permite conectar elementos del Fusion Builder a datos de WordPress (título del post, imagen destacada, autor, fecha, campos personalizados). Para contenido editorial estándar, puede eliminar la necesidad de shortcodes PHP simples — confirmado con soporte nativo para campos ACF (texto, imagen, repetidor, relación), incluyendo **Options Page de ACF** (prefijo `awb_acfop_`) desde el piloto de Novedades/Devocional (22-23 julio 2026, ver `METODOLOGIA_CONSTRUCCION.md`).

---

## 12. Lo que Avada NO puede hacer (necesita Code Snippets)

| Necesidad | Por qué no es Avada | Solución |
|---|---|---|
| Endpoint REST API propio | Lógica de servidor pura | Code Snippets PHP |
| Procesamiento de feed RSS del podcast | Lógica de servidor con caché | Code Snippets PHP |
| Shortcodes con datos dinámicos de `wp_options` | Lógica de negocio | Code Snippets PHP |
| Player interactivo con estado JS complejo | JS con estado | Code Snippets HTML (`<style>` + `<script>`) |
| Widgets devocionales | Leen de REST API en tiempo real | Code Snippets HTML |
| CORS headers | PHP puro | Code Snippets PHP |

### Regla de oro

```
¿Es visual y se puede configurar en Avada Theme Options, el Builder, o con ACF+Dynamic
Content?
    → Avada + ACF. No toques código. Esta es la opción por defecto (ver Sección 8,
      principio de mínimo código, y CATALOGO_ELEMENTOS_AVADA.md para identificar el
      elemento concreto).

¿Es lógica de servidor (PHP), datos dinámicos del REST API, o un shortcode?
    → Code Snippets PHP — solo si de verdad no hay forma nativa.

¿Es un módulo con JS interactivo complejo y su propio estilo visual?
    → Code Snippets HTML (con <style> + <script> integrados).

¿Es CSS que afecta a TODA la web y no tiene opción en Avada?
    → Avada Custom CSS (máximo 30 líneas, bien comentadas).

¿Es CSS de un módulo específico?
    → Dentro del propio snippet HTML del módulo. Nunca en Custom CSS global.
```

**Recordatorio de responsive (Sección 8.4):** esta regla decide *dónde* vive el código. Si la
respuesta es Code Snippets (PHP o HTML), el módulo resultante sigue necesitando comportarse
bien en las 3 pantallas — eso se resuelve con los breakpoints `1024/768/480px` ya
estandarizados en `00_CORE.md` Sección 7 y Sección 13 de este documento, exactamente igual
que un elemento construido en Avada necesita revisión con Responsive Option Sets.

---

## 13. Convenciones de código — sin cambios respecto a `00_CORE.md`

- Snippets tipo **HTML** con `<style>` + `<script>` juntos (Code Snippets free no separa CSS/JS).
- Prefijo `tt-` + BEM → `tt-modulo__elemento--modificador`.
- Variables CSS siempre `var(--tt-*)`, nunca hex sueltos.
- Breakpoints en `1024px` / `768px` / `480px`.
- Patrón JS obligatorio — condicional de existencia fuera de `ttReady`, lógica dentro, IIFE:

```javascript
if (document.getElementById('mi-modulo-root')) {
  function initMiModulo() {
    (function() {
      'use strict';
      // lógica del módulo
    })();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMiModulo);
  } else {
    initMiModulo();
  }
}
```

- Los módulos nunca redefinen variables del Global ni tocan elementos genéricos.
- `border-radius: 25px` en cards/botones/contenedores — firma visual Tiritaito. ⚠️ Ver Sección 9 (Toggles) y `CATALOGO_ELEMENTOS_AVADA.md` Sección 13.1: apareció un segundo valor, `10px`, en dos elementos interactivos distintos (Toggles y Forms), ambos por decisión de Hna C — sin confirmar todavía si es un cuarto token real o dos excepciones puntuales. No sustituir `25px` por `10px` en ningún sitio nuevo sin esa confirmación explícita.
- Nomenclatura de snippets PHP: `TT [Función] — [Descripción breve]` (ej. `TT Podcast — Shortcode y CSS`).
- Nomenclatura de snippets HTML: `TT Módulo — [Nombre]` (ej. `TT Módulo — Widget Devocional`).

---

## 14. Errores comunes a evitar (aprendidos de la web vieja y de la sesión de Local)

| Error | Cómo evitarlo en la web nueva |
|---|---|
| Ocultar el header con CSS en vez de con Layouts | Usar siempre Header Builder + Layout Global — el CSS de "ocultar" no debería hacer falta nunca |
| Poner el menú como Global Element en cada entrada | El Layout Global ya lo aplica automáticamente en todo el sitio |
| Mezclar Global Options de header con Header Builder | Elegir uno de los dos sistemas. Si se usa Header Builder, no tocar las opciones legacy de Global Options — quedan inactivas de todas formas |
| Construir páginas antes de tener el Layout Global terminado | Terminar Header + Footer + Global Options primero |
| Marcar un snippet "Run everywhere" porque es más fácil que decidir dónde | Es exactamente el patrón que generó el CSS de Santos/Biblioteca cargándose sin uso — decidir siempre el alcance real |
| `has_shortcode()` para condicionar CSS con Avada | Falso negativo — Avada codifica en Base64. CSS de módulos → `wp_head` siempre, incondicionalmente |
| Alinear la URL del Local con producción (añadir `/blog/`) | El Local vive en la raíz, sin `/blog/` — hacerlo provoca un bucle de redirecciones en el admin y rompe los estilos visuales (ver Sección 2) |
| Confiar en Live Link para revisar tipografía/CSS | No es fiable para eso — usar DevTools responsive directamente sobre `tiritaito-real.local` (ver Sección 2) |
| Dar una sección por cerrada solo revisándola en Desktop | Confirmado con un caso real (13 julio 2026): un bloque con imagen sin cargar y texto placeholder pasó desapercibido hasta revisar Desktop porque no se había comprobado explícitamente en las 3 vistas — revisar siempre Desktop/Medium/Small antes de cerrar (Sección 8.4) |
| Dejar que una sección ocupe `min-height: 100vh` por defecto sin decidirlo conscientemente | Revisar siempre si la sección necesita de verdad ocupar toda la pantalla, o si con la altura de su contenido basta (Sección 8.4-bis) |
| Construir una sección directamente en Avada sin ver antes 2-3 opciones de boceto | Pedir a Claude (Proyecto 3) que proponga bocetos visuales antes de empezar a construir, salvo ajustes menores (Sección 8.4-bis) |
| Escribir un snippet nuevo sin comprobar antes si ACF + un elemento nativo ya lo resuelve | El árbol de decisión (Sección 8) empieza siempre por la opción nativa — código es el último recurso, no el primero (reforzado 26 julio 2026) |
| Trabajar con dos ordenadores sin seguir el procedimiento de la Sección 2.1 | Local no sincroniza nada entre ordenadores por sí solo — improvisar sin backup previo puede dejar el segundo ordenador con un WordPress vacío o hacer perder trabajo real (Sección 2.1) |
| Confundir "Slideshows" (Options) con "Post Slider" (Builder Element) | Slideshows solo controla varias imágenes DENTRO de una misma entrada; para rotar entre entradas distintas (ej. los 9 santos) hace falta Post Slider — confusión ya cometida y corregida dos veces de forma independiente (11 agosto 2026) |

---

## 15. Roadmap — Fase 0 y Fase 1 (checklist accionable)

### Fase 0 — Aprender antes de construir

- [ ] Ver documentación oficial de Avada: Header Builder, Footer Builder, Layouts, Global Options, Off Canvas Builder
- [ ] En el Local, hacer ejercicios sin presión: crear un header visual, configurar un Layout, probar la paleta de colores
- [ ] Confirmar registro de Avada como staging (Sección 1)

### Fase 1 — Configurar la base de Avada

- [ ] Crear/confirmar el sitio en Local by Flywheel (WordPress limpio, en la raíz, sin `/blog/`)
- [ ] Instalar Avada + registrar licencia como staging
- [ ] Configurar Global Options: paleta de 13 colores, tipografía (Yeah Papa + Helvetica Neue), spacing (Sección 4)
- [ ] Diseñar el Header en Header Builder (Sección 5)
- [ ] Diseñar el Footer en Footer Builder (Sección 6)
- [ ] Decidir método de menú móvil — Off Canvas vs Flyout legacy, según submenús (Sección 9.1)
- [ ] Crear los Layouts: Global, Blog, Podcast, Landing (Sección 7)
- [ ] Guardar como Blueprint en Local
- [ ] Migrar los snippets PHP base (endpoint REST, shortcode podcast)

**Este roadmap no depende de las 6 preguntas pendientes de `ALCANCE_WEB_NUEVA.md`** — Hno A puede avanzar esta Fase 1 en paralelo, sin bloquearse por las decisiones de contenido de Hna C.

---

## 16. Checklist maestro — antes de dar una plantilla por cerrada

- [ ] ¿Ya existe un elemento nativo de Fusion Builder (solo o con ACF) que resuelva esto? (Sección 9, o `CATALOGO_ELEMENTOS_AVADA.md`) — esta pregunta va SIEMPRE primero
- [ ] ¿Ya existe un snippet global de Tiritaito equivalente?
- [ ] Si se construye desde cero: ¿pensado para reutilizarse — parámetros, no contenido fijo?
- [ ] Si es candidato a Avada Library: ¿Guardado o Global? (Sección 8 — no son intercambiables)
- [ ] ¿El JS sigue el patrón condicional + IIFE?
- [ ] ¿Usa `var(--tt-*)` en vez de hex sueltos?
- [ ] ¿Se ha probado en consola del navegador que no hay errores de elementos inexistentes?
- [ ] ¿El snippet necesita estar en TODAS las páginas, o solo en una plantilla concreta?
- [ ] ¿Se ha comprobado el peso que añade a páginas que no lo necesitan?
- [ ] **¿Se ha revisado en las 3 vistas del editor de Avada — Desktop / Medium / Small? (Sección 8.4)**
- [ ] **¿El texto se lee bien y no se desborda en la vista Small?**
- [ ] **¿Los botones e iconos son suficientemente grandes para tocar con el dedo en móvil?**
- [ ] **¿El orden de las columnas tiene sentido en móvil, no solo en ordenador?**
- [ ] **Si hay imágenes de fondo: ¿se ven bien recortadas en móvil, sin partes importantes cortadas?**
- [ ] **¿Se ha probado además en un móvil real o DevTools responsive, no solo el editor o Live Link?**
- [ ] **¿Se propusieron 2-3 bocetos visuales antes de construir esta sección, o fue un ajuste menor que no lo necesitaba? (Sección 8.4-bis)**
- [ ] **¿La sección ocupa aproximadamente lo que su contenido necesita, o hereda un `min-height: 100vh` sin haberlo decidido conscientemente? (Sección 8.4-bis)**

---

## 17. Qué queda confirmado vs qué sigue pendiente de probar en Local

**✅ Confirmado en documentación oficial (julio 2026):**
- Licencia de staging: `tiritaito-real.local` califica sin coste extra (Sección 1).
- Toggles, Image Carousel, Avada Slider, Lightbox, Modal existen como elementos nativos.
- Global Elements sincronizan el 100% del contenido — no admiten campos variables por instancia (Sección 8).
- El Flyout Menu clásico es un método legacy; el método actual es el Off Canvas Builder (Sección 9.1).
- Ni Flyout ni Off Canvas resuelven de forma nativa menús con submenús.
- ACF Pro y FileBird Pro vienen incluidos gratis con la licencia de Avada, instalables desde Avada → Plugins.
- Avada Dynamic Content tiene soporte nativo para campos ACF, incluida Options Page.
- **Responsive Option Sets permite configurar ancho, orden, márgenes, padding y fondo de forma independiente por pantalla en Container/Columna, y alineación en Botón/Imagen/Texto/Título (Sección 8.4).**
- **Responsive Typography Sensitivity y Minimum Font Size Factor controlan si y cómo se reduce el tamaño de letra en pantallas pequeñas (Sección 8.4).**
- **Local Cloud Backups (v10+) permite restaurar el mismo backup en otro ordenador conectado a la misma cuenta de nube — no es sincronización en tiempo real, es un mecanismo de "restaurar bajo demanda" (Sección 2.1).**

**✅ Confirmado en sesiones reales en Local:**
- 7 julio 2026: el dominio correcto del Local es `tiritaito-real.local`; el Setup Wizard solo admite 8 de los 13 colores y no admite fuentes propias; Off Canvas y Eventos ya activados, Portafolio activo sin caso de uso.
- **12 julio 2026: el Local NO usa subdirectorio `/blog/` — vive en la raíz.** Confirmado con WP-CLI. Intentar alinear con producción rompe el sitio (bucle de redirecciones + pérdida de estilos) — ver Sección 2.
- 12 julio 2026: el certificado SSL de Local necesita "Trust" manual — puede llegar a romper silenciosamente las respuestas de la API si no está confiado.
- 12 julio 2026: Live Link no es fiable para revisar tipografía/CSS/diseño — confirmado contra documentación oficial de Local, no solo observación propia.
- 12 julio 2026: el desbordamiento visto en los botones de "Qué hacemos" era un efecto de la limitación de Live Link con fuentes, no un fallo de maquetación real — cerrado, sin acción pendiente.
- **13 julio 2026: Responsive Option Sets confirmado funcionando en un caso real — layout de 3 columnas iguales en Desktop/Tablet que pasa a 1 columna ancha + 2 en pareja en Móvil, usando solo Column Width + Column Order por pantalla, sin código (Sección 8.4).**
- **13 julio 2026: un bloque con contenido sin terminar (imagen sin cargar, texto placeholder) pasó desapercibido hasta revisar en Desktop — confirma la necesidad del paso explícito de revisión en las 3 vistas antes de cerrar cualquier sección.**
- **14 julio 2026: sesión de equipo sobre la home confirma dos criterios de trabajo nuevos, sin verificación técnica en Local todavía (son decisiones de equipo, no hallazgos de Avada): altura de sección acotada por defecto, y previsualización con bocetos antes de construir (Sección 8.4-bis).**
- **22-23 julio 2026: piloto de ACF Options Page + CPT Novedades confirma que Dynamic Content lee campos de Options Page vía el prefijo `awb_acfop_`, pero solo si se escriben con `update_field()` — no con `update_option()` directo, que guarda en una fila distinta que Avada nunca consulta (el nombre real de la fila lleva el prefijo `options_`, no el nombre plano del campo). Confirma también que Post Cards ordena de forma nativa por Custom Field, pero no filtra por valor de campo (necesita snippet con el hook `fusion_post_cards_shortcode_query_override` — decisión de equipo, 26 julio: no se construye ese hook para Novedades, ver Sección 9).**
- **26 julio 2026: backend de Novedades reconstruido en Local desde cero (el entorno se había perdido por completo) — CPT `novedades` + ACF (6 campos) + endpoint propio `tiritaito/v1/novedades`, confirmado funcionando de extremo a extremo desde la app real.**
- **26 julio 2026: Devocional (Virgen, Brisa, Homilía, Lenguas) migrado parcialmente a ACF Options Page "Devocional — Contenido Diario" — 7 de 12 claves antiguas de `wp_options`.**
- **26 julio 2026: confirmado el header real de autenticación de la app — `X-TT-Token`, no `Authorization: Bearer`.**
- **31 julio 2026: confirmado que Local no ofrece ninguna forma de sincronización automática o en tiempo real entre dos instalaciones en ordenadores distintos — investigado explícitamente, incluyendo foros oficiales de Local y documentación de localwp.com. El mecanismo más cercano disponible es Local Cloud Backups, restaurable bajo demanda (Sección 2.1).**
- **31 julio 2026: confirmada y resuelta la causa del error `insufficient authentication scopes` / `Error 403: Insufficient Permission` al conectar Cloud Backups con Google Drive — desconectar y reconectar la cuenta, completando el consentimiento de Google hasta el final, según lo confirmado por soporte oficial de Local (community.localwp.com) y verificado en la práctica por el equipo (Sección 2.1).**
- **28 julio – 10 agosto 2026: ronda completa de configuración de Avada Global Options en Local, repartida en tres cuentas de trabajo — resultado íntegro en `CATALOGO_ELEMENTOS_AVADA.md`. Resuelve formalmente: orden real de los 8 colores del Wizard (Sección 4.1), breakpoints responsive ya alineados a ~1024px (Sección 8.4), Portfolio confirmado desactivado, panel de Performance documentado por primera vez en su tamaño real (Sección 4.4), y la distinción Slideshows/Post Slider (Sección 9).**
- **11 agosto 2026: mecanismo de tres capas de "Default Page Template = 100% Width" documentado con evidencia directa (Sección 4.0.3) — no fuerza pantalla completa, solo la habilita por Container.**

**🔲 Solo se puede confirmar dentro de Local:**
- Si Image Carousel / Avada Slider replican el comportamiento exacto de "Próximos eventos" (autoplay, swipe, modal de vídeo).
- Código fuente completo de la home — "Grupo de alabanza" y "Próximos eventos" a nivel CSS/JS.
- Si "Yeah Papa" se registra correctamente en Avada → Typography → Custom Fonts, o si hace falta volver a subir el `.woff2` ahí específicamente.
- Si la barra negra superior del header "Studio" es una fila del Header Builder o el Top Bar legacy de Global Options.
- Si el título "Studio" del header es el Site Title de WordPress o un elemento de Título suelto.
- Qué valor tiene actualmente "Responsive Typography Sensitivity" (Sección 8.4).
- **El patrón visual exacto de Novedades (destacada + rejilla, solo rejilla, u otro) — pendiente de que Carlitos comparta la referencia visual correcta con Hno C (Sección 8.4-bis; la primera captura compartida en esta sesión era del hero/slider, no del bloque de noticias, y no se usó como referencia).**
- Si Post Cards cubre el listado de "Seminarios pasados" y la portada de "Hombres de Dios" — el listado + orden por fecha sí funciona nativo (piloto 22-23 julio), pero si esas dos secciones necesitan filtrar de verdad (a diferencia de Novedades, que decidió no filtrar), esa pieza sigue sin construir.
- **Si el Off-Canvas "Menu Movil" ya creado en Local está diseñado por dentro y probado, más allá de existir como entrada con Conditions desactivadas (Sección 9.1).**
- **Si 10px sustituye a 25px como radio estándar del sitio, convive como cuarto token, o queda solo en Toggles/Forms (Sección 13, `CATALOGO_ELEMENTOS_AVADA.md` Sección 13.1) — decisión de Hna C pendiente de formalizar.**

---

## 18. Fuentes verificadas

- Avada Registration and Licensing FAQ — patrones de staging: avada.com/documentation/avada-registration-and-licensing-faq/ (act. 5 feb 2026)
- How To Set Up An Avada Staging Site: avada.com/documentation/how-to-set-up-an-avada-staging-site/
- Staging Sites vs Local Development Environments: avada.com/documentation/staging-sites-vs-local-development-environments/
- Avada Builder Library, elementos globales y guardados: avada.com/documentation/avada-builder-library-global-elements/ · avada.com/documentation/how-to-use-the-avada-builder-library/
- Toggles Element: avada.com/documentation/toggles-element/
- Flyout Menu (legacy): avada.com/documentation/flyout-menu/
- Mobile Menu Settings: avada.com/documentation/mobile-menu-settings/
- Off Canvas Builder — método actual de Flyout: avada.com/documentation/how-to-make-a-flyout-menu-with-the-off-canvas-builder/
- Global Option Header Layouts: avada.com/documentation/global-option-header-layouts/
- Image Carousel Element: avada.com/documentation/image-carousel-element/
- Avada Slider Element: avada.com/element/avada-slider/
- Lightbox Element: avada.com/documentation/lightbox-element/
- How To Use The Avada Setup Wizard: avada.com/documentation/how-to-use-the-avada-setup-wizard/
- How To Use The Performance Wizard: avada.com/documentation/how-to-use-the-performance-wizard/
- Local by Flywheel — Live Links, limitaciones confirmadas: localwp.com/help-docs/local-features/live-links/
- Sesión de diagnóstico en Local (WP-CLI, SSL, discrepancia de tokens): `CORRECCIONES_DOCUMENTACION_11-12_julio_2026.md`, redactado por Hno A
- Responsive Option Sets: avada.com/documentation/responsive-option-sets/
- Responsive Design in Avada: avada.com/documentation/responsive-design-in-avada/
- Responsive Options in Avada (breakpoints globales): avada.com/documentation/responsive-options-in-avada/
- Responsive Headings (tipografía automática): avada.com/documentation/responsive-headings/
- Column Size and Order for Responsive Design: avada.com/documentation/column-size-and-order-for-responsive-design-in-avada/
- Responsive Header Design With Avada: avada.com/documentation/responsive-header-design-with-avada/
- Altura de sección y previsualización con bocetos (Sección 8.4-bis): criterio de equipo, sesión 14 julio 2026 — no es una fuente de documentación oficial de Avada, se anota como decisión propia
- Piloto ACF Options Page + CPT (Sección 8.4, 9, 17): sesión real en Local, 22-23 julio 2026, y reconstrucción del backend, 26 julio 2026 — decisiones y hallazgos de equipo, no documentación oficial de Avada
- Local Cloud Backups — documentación oficial: localwp.com/help-docs/local-features/local-cloud-backups/
- Error "insufficient authentication scopes" en Cloud Backups + Google Drive — hilo oficial de soporte de Local (abril 2026), solución confirmada: community.localwp.com/t/creating-backup-to-google-drive-is-not-working/52070
- Ronda de tres cuentas de Global Options y catálogo de elementos (28 julio – 11 agosto 2026): trabajo real del equipo en Local, ver `CATALOGO_ELEMENTOS_AVADA.md` para el detalle completo y sus propias fuentes

---

## 19. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Hno A: registrar "Yeah Papa" en Typography → Custom Fonts y confirmar si el `.woff2` ya subido sirve o hay que resubirlo
2. Hno A: abrir el header "Studio" en Local para resolver los 4 pendientes de la tabla de la Sección 4.0.1 (barra negra, buscador/iconos, fondo acuarela, tipografía del título)
3. ✅ **Resuelto (14 agosto 2026):** Portafolio confirmado desactivado contra el export real — no hace falta decidirlo, ya está aplicado.
4. Hno A: actualizar la URL configurada en la app Tiritaito for Creators de `.../blog/wp-json` a `https://tiritaito-real.local/wp-json`
5. ✅ **Resuelto (14 agosto 2026):** Responsive Typography Sensitivity confirmado en 0.30 contra el export real (no en 0).
6. Carlitos: compartir con Hno C la referencia visual correcta de Novedades (bloque de noticias, no el hero/slider) para que Hno C se la muestre a Claude al construir la home (Sección 8.4-bis)
7. Cuando se llegue a construir el menú: confirmar con el equipo si lleva submenús desplegables, y si el Off-Canvas "Menu Movil" ya existente se termina de diseñar y se publica (Sección 9.1)
8. Hna C / equipo: decidir si 10px sustituye a 25px como radio estándar, convive como cuarto token, o queda solo en Toggles/Forms (Sección 13)
9. Carlitos + Hno A: si vuelve a hacer falta trabajar con dos ordenadores, seguir el procedimiento de la Sección 2.1
10. Barrer el export completo y corregir en Avada todos los campos con la unidad inválida "píxeles" (debe ser "px", sin espacio) — muestra ya encontrada: page_title_font_size, text_column_min_width, slider_arrow_size, header_sticky_nav_font_size, nav_dropdown_font_size, snav_font_size, megamenu_title_size, es_title_font_size, y varios countdown_*/ec_sep_heading_font_size. La lista de arriba no es completa — falta el barrido de los campos que no entraron en la revisión de esta sesión.

**Preguntas abiertas que necesitan decisión del equipo:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿El menú de la web nueva va a tener submenús desplegables? | Determina si el Off Canvas Builder (Sección 9.1) es suficiente o hace falta un workaround adicional |
| 2 | ¿Post Cards cubre el listado de "Seminarios pasados" y la portada de "Hombres de Dios"? | Solo se puede confirmar probando en Local — pendiente de sesión práctica. A diferencia de Novedades, si estas dos necesitan filtrar de verdad, sí haría falta el hook de la Sección 9 |
| 3 | ~~¿Se desactiva "Portafolio"?~~ **Resuelto 14 agosto 2026** — confirmado desactivado contra el export real, ver Sección 4.0.1. | — |
| 4 | ¿10px sustituye a 25px, convive como cuarto token, o queda solo en Toggles/Forms? | Apareció dos veces de forma independiente, en dos elementos distintos, por decisión de Hna C (Sección 13) — bloquea el border-radius de Tabs, Testimonials y Audio, dejados en 0px a propósito mientras no se resuelva |
| 5 | ¿Se confirma que la copia de `GUIA_AVADA_LOCAL.md` que maneja cada cuenta/Proyecto está al día? | La Sección 4.0.3 se añadió porque una sesión de trabajo citó un pasaje sobre "Default Page Template" que no se localizó en la versión disponible en ese momento — puede ser síntoma de una sincronización desactualizada en algún Proyecto |
| 6 | ¿Se fija como regla formal el criterio "sección = 1 página si todo su contenido cabe en un solo lugar y está muy relacionado y dirigido a un mismo público; sección = varias entradas si cada apartado tiene personalidad propia" (ej. Tiritaito y Qué Hacemos ya siguen este patrón de facto)? Carlitos necesita pensarlo con el equipo antes de fijarlo — no aplicar como regla formal todavía | Pendiente de discutir con el equipo — ver `ALCANCE_WEB_NUEVA.md` pregunta abierta #8 |

**Resuelto desde la última versión:** autenticación de Tiritaito for Creators — es token propio (`TT_WRITE_TOKEN`) vía header `X-TT-Token`, definitivo, Application Password descartado, confirmado contra el HTML real (26 julio) · dominio real del Local corregido a `tiritaito-real.local` · el Local NO usa `/blog/`, vive en la raíz · certificado SSL necesita "Trust" manual · Live Link confirmado no fiable para QA visual · ACF Pro y FileBird Pro confirmados incluidos gratis con Avada · principio de Responsive (Sección 8.4) y de altura de sección/previsualización (Sección 8.4-bis) incorporados al proceso de construcción · discrepancia de tokens resuelta — vive en `define()`, decisión final (26 julio 2026) · Post Cards de Novedades: decisión de equipo de no filtrar por `activo`, no se construye el hook (26 julio 2026) · principio de "mínimo código posible, ACF + nativo antes que Code Snippets" reforzado explícitamente en el árbol de decisión de la Sección 8 (26 julio 2026) · mecanismo de trabajo con dos ordenadores documentado, apoyado en Local Cloud Backups, con el error típico de conexión a Google Drive ya resuelto (31 julio 2026) · **breakpoints responsive confirmados y alineados a ~1024px (11 agosto 2026)** · **orden real de los 13 colores confirmado en Local, tabla de la Sección 4.1 corregida (11 agosto 2026)** · **mecanismo de "Default Page Template = 100% Width" documentado (Sección 4.0.3, 11 agosto 2026)** · **distinción Slideshows/Post Slider incorporada (Sección 9, 11 agosto 2026)** · **CATALOGO_ELEMENTOS_AVADA.md creado como referencia complementaria de "qué elemento sirve para qué necesidad" (11 agosto 2026)** · **Portafolio confirmado desactivado contra el export real (14 agosto 2026)**
· **Responsive Typography Sensitivity confirmado en 0.30 contra el export real (14 agosto 2026)** ·.

---

*Para la mayor gloria de Dios · tiritaito.com*

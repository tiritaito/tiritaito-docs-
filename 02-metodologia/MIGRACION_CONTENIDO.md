# TIRITAITO.COM — Migración de Contenido
**Qué contenido de la web vieja migrar, qué recrear limpio, y cómo — framework de decisión sección por sección**
*Fusiona: INVESTIGACION_HERRAMIENTAS_TRABAJO_2026.md (Parte 2) + cruce con METODOLOGIA_CONSTRUCCION.md*
*Audiencia: Hna C (decide qué migrar) · Hno A (ejecuta) · Carlitos (coordina)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde: **de todo lo que existe en la web vieja, ¿qué pasa a la web nueva, y con qué método?** No es "migramos todo" ni "recreamos todo" — es una decisión sección por sección, con un criterio claro para cada caso.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Qué secciones tiene la web nueva y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| Dónde y cómo construir una pieza ya decidida (Global/Guardado/Snippet) | `METODOLOGIA_CONSTRUCCION.md` |
| **Qué contenido de la web vieja migrar, cuál recrear, y el plan de SEO** | **Este documento** |

**Regla de uso:** este documento decide *qué* pasa de la web vieja a la nueva. Una vez decidido, `METODOLOGIA_CONSTRUCCION.md` decide *dónde* construirlo en Avada.

---

## 1. Marco de decisión — la pregunta no es "¿migramos?", es "¿migramos esto en concreto?"

### 1.1 Contenido que NO conviene migrar — recrear limpio en la web nueva

| Contenido | Por qué recrear en vez de migrar |
|---|---|
| Entradas de "Charlas de la Biblia" y "Rincón de Nico" | El contenido único de cada entrada es mínimo — solo cambia el shortcode `[tt_podcast feed="..."]`. Recrear a mano en el nuevo sistema de Layouts lleva minutos por canal |
| Páginas construidas como Code Block con shortcodes de `wp_options` (Conecta cada día) | No hay contenido que migrar — los datos viven en `wp_options`, no en `post_content`. Solo hay que volver a montar la página con el shortcode en Avada nuevo |
| Cualquier página con CSS de experimentos abandonados | Ya identificado como deuda técnica (`METODOLOGIA_CONSTRUCCION.md` Sección 1) — no tiene sentido migrar el problema |

**Riesgo técnico a evitar en cualquier migración:** el `post_content` de Avada serializa los Code Blocks en Base64. Un export/import XML de WordPress entre dos instalaciones de Avada puede romper esa serialización si los IDs de plugin o de builder no coinciden exactamente. Es una razón técnica adicional para recrear en vez de migrar páginas con Code Blocks complejos.

### 1.2 Contenido que SÍ conviene migrar

| Contenido | Método recomendado |
|---|---|
| Entradas de "Hombres de Dios" con contenido sustancial (biografía, oración, texto largo) | Vía Layouts de Avada — ver Sección 4 |
| Archivos de medios (imágenes, portadas de podcast, audios de homilías) | Migrar siempre, migre o no el contenido textual. Opciones: plugin de migración (All-in-One WP Migration, Duplicator) para mover `/wp-content/uploads/` completa, o copia manual vía gestor de archivos de Raiola para lo que realmente se usa |
| Datos de `wp_options` (las claves del sistema Creators) | No es "migración" clásica — es volver a ejecutar el snippet del endpoint en el WordPress nuevo y apuntar la PWA Creators al nuevo endpoint (usando el token `TT_WRITE_TOKEN`, ya definitivo — no Application Password) |

---

## 2. El problema del SEO — plan de redirecciones

Si la web nueva cambia las URLs de páginas que ya posicionan en Google (con Search Console activo), perder esas URLs sin redirección significa perder ese posicionamiento de golpe.

**Plan recomendado antes del lanzamiento:**

1. Desde Google Search Console, exportar el listado de páginas con más clics/impresiones de los últimos 12 meses
2. Para cada una, decidir la URL equivalente en la web nueva
3. Configurar redirecciones 301 (snippet PHP sencillo, o plugin de redirecciones si se prefiere gestión visual)
4. Verificar en Search Console, semanas después del lanzamiento, que no aparecen errores 404 masivos

```
FLUJO DE MIGRACIÓN SEO

Search Console (12 meses de datos)
        │
        ▼
Listado de páginas con más clics/impresiones
        │
        ▼
Para cada URL vieja → decidir URL nueva equivalente
        │
        ▼
Configurar redirección 301 (snippet o plugin)
        │
        ▼
Lanzamiento
        │
        ▼
Semanas después: revisar Search Console → errores 404 masivos ⇒ alerta
```

---

## 3. Migración de archivos de medios — sin condiciones, siempre se hace

A diferencia del contenido textual (que se decide caso por caso), los archivos de medios se migran siempre, se recree o no la página que los usa:

- **Opción A:** plugin de migración (All-in-One WP Migration, Duplicator) para mover `/wp-content/uploads/` completa
- **Opción B:** copia manual vía gestor de archivos de Raiola, solo de lo que realmente se usa

**Recomendación:** si el volumen de medios es grande y no se sabe con certeza qué se usa y qué no, Opción A es más segura (todo o nada, sin dejarse nada por el camino). Si ya se sabe exactamente qué archivos importan, Opción B es más rápida y limpia.

---

## 4. Hombres de Dios — decisión de formato actualizada

**Actualización directa de Carlitos:** "Hombres de Dios" se va a construir de nuevo mediante **Layouts de Avada** — no páginas individuales sueltas, ni el debate Posts-con-categoría-vs-Post-Cards que se planteaba en la versión anterior de este documento. Esto reemplaza esa discusión.

### 4.1 Lo que esto implica técnicamente

Un Layout Content Section con Dynamic Content necesita, igualmente, un tipo de contenido de WordPress del que tirar (Posts o un Custom Post Type) — el Layout define el diseño una sola vez, y Avada lo aplica automáticamente a cada entrada de ese tipo. Es, en la práctica, la ruta "v2" que ya estaba anotada como posible evolución futura en `METODOLOGIA_WEB_NUEVA_v2` — el equipo salta directamente a ella en vez de pasar primero por páginas sueltas.

**Buena noticia para la migración:** con esta ruta, el hallazgo de Post Cards (Sección 4 de la versión anterior de este documento) deja de ser un obstáculo — Post Cards sí funciona sobre Posts/CPT, que es exactamente lo que este enfoque usa. Ya no depende de si se convierten páginas sueltas en Posts.

### 4.2 Lo que sigue pendiente de definir en Local

- [ ] ¿Posts normales con categoría, o Custom Post Type con campos propios (ACF)? — determina la estructura de datos de cada santo
- [ ] Qué campos tiene la "ficha de santo" (nombre, biografía, oración, fecha de fiesta, podcast asociado...) — ver `ALCANCE_WEB_NUEVA.md` Sección D cuando se revise con Hna C
- [ ] Con la estructura de campos ya decidida, el método de migración se vuelve más simple: exportar/importar por categoría deja de depender del volumen, porque el contenido ya nace en el formato correcto

**Este documento se actualizará** en cuanto Hna C y Carlitos cierren esta decisión — es probable que estudiar el Alcance juntos saque más contenido migrable a la luz, como ya se ha anotado.

---

## 5. Checklist de migración por sección

| Sección (Alcance) | ¿Migrar o recrear? | Método |
|---|---|---|
| Charlas de la Biblia | Recrear | Shortcode nuevo, minutos por canal |
| Rincón de Nico | Recrear | Shortcode nuevo + accordion consolidado |
| Conecta cada día | Recrear | Montar shortcode en Avada nuevo, sin migrar contenido (vive en `wp_options`) |
| Seminarios — próximos | Recrear | Página estática nueva |
| Seminarios — pasados | Migrar (parcial) | Entradas WP — candidato a export/import si el volumen lo justifica |
| Hombres de Dios | Migrar (probable) | Vía Layouts de Avada — ver Sección 4. Método definitivo pendiente de estructura de campos |
| Oraciones | Recrear | Contenido nuevo, v1 solo texto |
| Archivos de medios (todas las secciones) | Migrar siempre | Plugin de migración o copia manual (Sección 3) |
| Datos `wp_options` | No es migración clásica | Reejecutar snippet + apuntar app al nuevo endpoint con token |

---

## 6. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Hna C + Carlitos: revisar `ALCANCE_WEB_NUEVA.md` completo y definir los campos de la "ficha de santo" para el Layout de Hombres de Dios
2. Hno A: prototipar en Local el Layout Content Section con Dynamic Content para confirmar que Post Cards y el diseño funcionan como se espera
3. Antes del lanzamiento: ejecutar el plan de redirecciones 301 de la Sección 2 con datos reales de Search Console
4. Mantener este documento abierto a revisión — es probable que la revisión del Alcance con Hna C saque más contenido migrable a la luz

**Preguntas abiertas:**

| # | Pregunta | Bloquea a |
|---|---|---|
| 1 | ¿Posts normales con categoría o Custom Post Type con ACF para "Hombres de Dios"? | Estructura de datos del Layout (Sección 4) |
| 2 | ¿Qué campos tiene la ficha de cada santo? | Diseño del Layout Content Section y método de migración |
| 3 | ¿Cuál es el volumen real de archivos de medios a migrar? | Elegir entre plugin de migración completo o copia manual selectiva (Sección 3) |

---

*Para la mayor gloria de Dios · tiritaito.com*

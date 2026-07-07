# TIRITAITO.COM — Arquitectura y Roadmap
**Visión de conjunto: FODA, política sobre la web vieja, hoja de ruta por fases, glosario del equipo**
*Fusiona: INFORME_ESTRATEGICO_2026_1.md (Resumen ejecutivo, Parte 4, 7, 8, 9, Glosario) — sin duplicar el detalle de Avada ya cubierto en `GUIA_AVADA_LOCAL.md` ni el diagnóstico técnico ya cubierto en `METODOLOGIA_CONSTRUCCION.md`*
*Audiencia: Carlitos (visión de conjunto) · útil también para Hna MF y editores (glosario incluido)*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde: **¿por qué se está haciendo la web nueva, cuáles son los riesgos y oportunidades reales, y en qué orden general se ejecuta?** Es la visión de conjunto — no el detalle técnico de cómo se construye cada pieza.

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Cómo funciona Avada / Local técnicamente | `GUIA_AVADA_LOCAL.md` |
| Dónde construir una pieza de contenido decidida | `METODOLOGIA_CONSTRUCCION.md` |
| Qué migrar de la web vieja | `MIGRACION_CONTENIDO.md` |
| Quién hace qué, cuentas de Claude, GitHub | `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` |
| Qué secciones tiene la web y en qué orden construirlas | `ALCANCE_WEB_NUEVA.md` *(pendiente de revisión final con Hna C)* |
| **Por qué este plan, FODA, fases generales, glosario** | **Este documento** |

**Nota sobre una dependencia evitada a propósito:** este documento no incluye una lista fija de "qué página se construye primero" — esa lista vive en `ALCANCE_WEB_NUEVA.md`, que todavía está pendiente de revisión final entre Carlitos y Hna C. La Fase 2 (Sección 4) remite a ese documento en vez de repetir una lista que podría cambiar, precisamente para que este Roadmap quede cerrado ya, sin necesidad de tocarlo cuando esa revisión termine.

---

## 1. Resumen ejecutivo

**Situación:** Tiritaito.com operaba sobre una base técnica con deuda acumulada — CSS huérfano, arquitectura de Avada no aprovechada, y un entorno de producción que era simultáneamente el único laboratorio de desarrollo. Diagnóstico completo en `METODOLOGIA_CONSTRUCCION.md`.

**Decisión estratégica adoptada:** construir una web nueva desde cero en Local by Flywheel, con Avada como sistema base, mientras la web actual sobrevive en mantenimiento mínimo.

**Veredicto:** correcta. Los riesgos son gestionables (Sección 2). Las oportunidades son mayores que los riesgos si se ejecuta con disciplina.

---

## 2. FODA

### 2.1 Fortalezas

| # | Fortaleza | Detalle |
|---|---|---|
| F1 | Entorno local profesional (Local by Flywheel) | Live Link, Blueprints, múltiples sitios en paralelo, SSL local idéntico a producción — detalle completo en `GUIA_AVADA_LOCAL.md` Sección 2 |
| F2 | Equipo complementario | Los roles se complementan bien (ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 1) — Hna C hace de puente entre criterio de producto y capacidad técnica, sin que ninguno de los dos falte |
| F3 | Base técnica existente | El sistema REST API, los snippets de podcast, el sistema `--tt-*`, el contrato de `wp_options` — código probado que se migra limpio, no se reinventa |
| F4 | Avada 7.15.5 es maduro | Header Builder, Footer Builder y Layouts completamente funcionales — la inversión en aprenderlo da retorno en años |
| F5 | Decisión de no heredar deuda | Empezar desde cero en local, sin presión de producción, permite hacerlo bien — es raro y valioso |

### 2.2 Debilidades

| # | Debilidad | Detalle |
|---|---|---|
| D1 | Curva de aprendizaje de Avada | Demasiadas opciones sin un mapa claro de qué ignorar — mitigado por `GUIA_AVADA_LOCAL.md` |
| D2 | Riesgo de replicar los mismos errores | Si no se formaliza la arquitectura antes de construir, la web nueva acumulará la misma deuda, solo que más rápido |
| D3 | La web vieja necesita mantenimiento mínimo | Durante el desarrollo de la nueva, la vieja puede empeorar si no se define bien qué no se toca (Sección 3) |
| D4 | Migración de contenido | Ver `MIGRACION_CONTENIDO.md` para el framework completo |
| D5 | WPMobile.app — terreno casi inexplorado | Licencia comprada, poco más — ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 7 |

### 2.3 Riesgos

| # | Riesgo | Mitigación |
|---|---|---|
| R1 | Scope creep (riesgo alto) | "Ya que estamos, hagamos también X, Y, Z" — el riesgo más peligroso. Hna C define el alcance, congelado en `ALCANCE_WEB_NUEVA.md`; lo que no está ahí no entra hasta v2 |
| R2 | La web vieja se degrada mientras tanto (riesgo medio) | Lista de "solo esto se toca" — correcciones de bugs críticos únicamente (Sección 3) |
| R3 | Avada Lock-in (riesgo bajo pero real) | Conocido y aceptado — Avada es maduro, con empresa detrás; la alternativa (Gutenberg, Elementor, Bricks) tiene sus propios lock-ins |
| R4 | Fragmentación de Claude Projects (riesgo medio) | Resuelto con la reestructuración de `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` — una cuenta por proyecto, GitHub como fuente única |
| R5 | WPMobile.app vs PWA Creators (confusión de herramientas) | Son herramientas distintas para públicos distintos — definido en `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 7 |

### 2.4 Oportunidades

| # | Oportunidad | Detalle |
|---|---|---|
| O1 | Header Builder elimina el problema del header | El hack CSS de ocultar el header desaparece — es un objeto visual que Hna C puede modificar sin código |
| O2 | Layouts eliminan el Global Element manual | El equipo editorial publica sin preocuparse de si el menú aparece |
| O3 | Paleta formal de colores en Avada | Hna C puede experimentar con tono desde Global Options sin arriesgar producción ni pedir ayuda técnica |
| O4 | Velocidad desde cero | Una web bien configurada puede cargar entre 2 y 4 veces más rápido — la deuda de velocidad no se hereda |
| O5 | Blueprint de Local como activo reutilizable | Una vez configurada correctamente, se guarda como Blueprint — base limpia para cualquier sitio futuro |
| O6 | WPMobile.app integrado desde el principio | Si la arquitectura se piensa con la app en mente desde ahora, la integración futura será mucho más limpia (ver conexión directa en `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 7.2) |

---

## 3. Qué hacer con la web vieja — modo supervivencia

**Política:** la web vieja solo se toca para correcciones de bugs que impidan su funcionamiento básico. No se añaden features. No se experimenta.

**Lista de "no tocar":**
- No actualizar Avada hasta que la nueva esté lista
- No añadir snippets PHP nuevos
- No experimentar con LiteSpeed Cache
- No cambiar estructura de páginas existentes

**Mantenimiento mínimo permitido:**
- Corrección de errores en snippets activos
- Subida de contenido por los editores
- Actualización del endpoint REST si es crítico (comunicando al equipo)
- Actualización de WordPress core (seguridad) — siempre con backup previo

**Respecto a Tiritaito for Creators:** no es parte de la web vieja ni de la nueva — proyecto independiente que continúa en paralelo sin interferir con ninguna de las dos.

---

## 4. Hoja de ruta

### Fase 0 — Aprender antes de construir

- [ ] Ver documentación oficial de Avada: Header Builder, Footer Builder, Layouts, Global Options, Off Canvas Builder (`GUIA_AVADA_LOCAL.md`)
- [ ] En el Local, ejercicios sin presión: crear un header visual, configurar un Layout, probar la paleta de colores
- [ ] Configurar Google Search Console correctamente para que Hna MF tenga datos reales (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 8)

### Fase 1 — Configurar la base de Avada

Checklist completo y verificado en `GUIA_AVADA_LOCAL.md` Sección 15 — no se repite aquí para no duplicar mantenimiento. Resumen: Global Options → Header/Footer Builder → Layouts → Blueprint → migrar snippets PHP base.

### Fase 2 — Construir páginas principales

**Esta fase sigue el orden de prioridad de `ALCANCE_WEB_NUEVA.md`** — ese documento es la fuente de verdad de qué se construye primero, no este. En el momento de escribir esto, `ALCANCE_WEB_NUEVA.md` está pendiente de revisión final entre Carlitos y Hna C; cuando se cierre, la Fase 2 se ejecuta directamente sobre esa priorización, sin necesidad de actualizar este Roadmap.

### Fase 3 — Migración de contenido

Framework completo en `MIGRACION_CONTENIDO.md`. Resumen de responsabilidad: se define sección por sección según ese documento, no en bloque.

### Fase 4 — QA y velocidad

- [ ] Test de velocidad (Google PageSpeed Insights)
- [ ] Test en móvil real
- [ ] Test con Live Link de Local (todo el equipo)
- [ ] LiteSpeed Cache configurado correctamente
- [ ] Verificar que todos los snippets funcionan

### Fase 5 — Lanzamiento

Decisión de Hna C.

---

## 5. Conclusiones y recomendaciones

**Recomendación 1 — No empezar a construir hasta terminar la Fase 0.** La tentación es abrir Avada y empezar a hacer páginas. El error que trajo la deuda técnica actual fue exactamente ese.

**Recomendación 2 — Hna C define el alcance antes de la Fase 1.** Esa pregunta tiene que tener respuesta escrita (`ALCANCE_WEB_NUEVA.md`) antes de instalar Avada en serio. Si no, el scope creep llegará.

**Recomendación 3 — Un solo desarrollador principal por sprint.** Dos personas tocando los mismos archivos de Avada en Local pueden crear conflictos. Decidir quién construye en cada sprint; el otro revisa.

**Recomendación 4 — El Blueprint de Local como entregable de Fase 1.** La Fase 1 no termina cuando "parece bien". Termina cuando el Blueprint está guardado — es el activo más valioso de esta etapa.

**Recomendación 5 — WPMobile.app: definición antes de tocarlo.** Ver `ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` Sección 7 — hay preguntas de licencia y arquitectura que conviene resolver antes de construir, no durante.

**Recomendación 6 — Activar Google Search Console completo ya.** Los datos que recoja mientras se construye la web nueva son el criterio objetivo para priorizar qué páginas van primero — no esperar a la web nueva para tener datos buenos.

---

## 6. Glosario para el equipo

*Explicaciones en una frase, pensadas para quien no tiene el vocabulario técnico de memoria.*

| Término | Qué es |
|---|---|
| Fusion Builder | El editor visual de páginas de Avada — el que se usa para maquetar páginas |
| Header Builder | Editor visual de cabeceras de Avada, separado del editor de páginas |
| Avada Layouts | Sistema de plantillas de Avada que define qué cabecera/pie usa cada tipo de página |
| Global Options | El panel de configuración global de Avada (colores, tipografía, rendimiento) |
| Global Element | Un bloque de Avada que se edita una vez y cambia en todos los sitios donde está — nunca admite contenido distinto por instancia |
| Saved Element (Guardado) | Un bloque de Avada guardado que se inserta como punto de partida — cada copia es independiente |
| Code Snippets (PHP) | Lógica de servidor: REST API, shortcodes, procesamiento de feeds |
| Code Snippets (HTML) | Módulos interactivos: reproductor de podcast, widgets devocionales |
| Local by Flywheel | Entorno de desarrollo local donde se construye la web nueva |
| Blueprint | Copia guardada de un sitio en Local — punto de partida reutilizable para el futuro |
| Live Link | Función de Local que comparte el sitio local por URL pública temporal |
| wp_options | Tabla de WordPress donde se guarda configuración del sitio (textos, URLs dinámicas) |
| REST API | Sistema de comunicación entre la PWA Creators y WordPress |
| PWA Creators | Tiritaito for Creators — herramienta interna para editores, independiente de la web |
| WPMobile.app | Herramienta para crear la app nativa para la comunidad de usuarios |
| CPT (Custom Post Type) | Un tipo de contenido de WordPress hecho a medida, además de las "Entradas" y "Páginas" normales |

---

## 7. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Carlitos + Hna C: cerrar `ALCANCE_WEB_NUEVA.md` — desbloquea la Fase 2 de este Roadmap sin necesidad de tocar este documento
2. Hno A: continuar Fase 0-1 en Local — no depende de lo anterior
3. Hna MF (o quien aplique el checklist): activar Google Search Console completo cuanto antes (Recomendación 6)

**Preguntas abiertas:**

| # | Pregunta | Por qué importa |
|---|---|---|
| 1 | ¿Cuándo se cierra la revisión de `ALCANCE_WEB_NUEVA.md` con Hna C? | Es el único bloqueante real de la Fase 2 |
| 2 | ¿Quién es "un solo desarrollador principal por sprint" en la práctica — se rota o es fijo? | Recomendación 3 — sin definir todavía |

---

*Para la mayor gloria de Dios · tiritaito.com*

# TIRITAITO.COM — Alcance de la Web Nueva
**Estructura de la web nueva: páginas, secciones internas, prioridades y método de trabajo**
*Sustituye a `historico/ALCANCE_WEB_NUEVA_v1.md` — reestructuración completa a partir de la sesión con Hna C, 8-9 julio 2026*
*Aprobación: Hna C · Redacción: Hno C*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

> **Regla de oro de este documento:**
> Lo que no está aquí no entra en la primera versión. Si algo nuevo surge durante el
> desarrollo, se anota y se evalúa. Hna C tiene la última palabra sobre qué entra y qué no.
>
> **Nota de espíritu, no solo de forma:** este documento es una brújula de trabajo, no una
> jaula. Si al construir se descubre que algo funciona mejor de otra manera, se cambia — y
> luego se actualiza aquí. La disciplina de documentar nunca debe convertirse en rigidez
> que bloquee una mejora real ni la creatividad del equipo. *Abiertos a la obra del
> Espíritu Santo.*

---

## 0. Qué es este documento

Responde: **¿qué páginas tiene la web nueva, qué lleva cada una, con qué prioridad, y qué
sigue sin decidir?**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Dónde y cómo construir en Avada una pieza ya decidida aquí (Global/Guardado/Snippet) | `METODOLOGIA_CONSTRUCCION.md` |
| Qué contenido de la web vieja migrar y con qué método | `MIGRACION_CONTENIDO.md` |
| Cómo funciona Avada/Local mecánicamente | `GUIA_AVADA_LOCAL.md` |
| **Qué construir, dónde vive cada cosa, y en qué orden** | **Este documento** |

---

## 1. Qué cambia respecto a la versión anterior (v1, histórica)

La versión anterior (`historico/ALCANCE_WEB_NUEVA_v1.md`) trataba Seminarios, Ejército de
Intercesores, Tiritaito Music, Charlas de la Biblia, Rincón de Nico, Hombres de Dios y
Oraciones como **secciones independientes de la home**, y dejaba Biblioteca pospuesta a v2.

La sesión de julio 2026 con Hna C reorganiza esto en **cuatro páginas contenedoras** (Qué
hacemos · Conecta cada día · Tiritaito · Biblioteca) que agrupan lo que antes eran
secciones sueltas, más una página que se mantiene independiente (Hombres de Dios), un
apartado que deja de ser página (Novedades, ahora parte de la home) y una pieza sin
resolver (Próximos eventos). Detalle completo en la Sección 4.

---

## 2. Mapa de la página de inicio

```
PÁGINA DE INICIO
│
├── NOVEDADES  (no es página — apartado de apertura de la home)
│     └── Tips (también en Conecta cada día) · avisos litúrgicos/eventos importantes
│         de la Iglesia · noticias de Tiritaito
│
├── QUÉ HACEMOS (página)
│     ├── 1. Seminarios          — mayor prioridad, más contenido
│     ├── 2. Grupo de alabanza   — informativo
│     └── 3. Día de familias     — informativo
│
├── CONECTA CADA DÍA (página)
│     ├── Hora de la brisa
│     ├── Mensaje de la Virgen de hoy
│     ├── Misa                     🔲 probablemente absorbe el Salmo del día
│     ├── Tip                      (también en Novedades — duplicado intencional)
│     ├── Lenguas
│     ├── 🔲 Habla por la palabra (cita aleatoria) — ubicación sin decidir
│     └── 🔲 Elige tu santo — ubicación sin decidir
│
├── TIRITAITO (página) — 4 entradas propias
│     ├── Tiritaito Music
│     ├── Rincón de Nico            ⎫ misma unidad de presentación
│     ├── Charlas de la Biblia      ⎬ de podcast (patrón [tt_podcast]),
│     └── Ejército de Intercesores  ⎭ estilo propio de contenido
│
├── BIBLIOTECA (página) — 3 apartados, presentación distinta cada uno
│     ├── Libros      — sistema PWA existente, migra a formato de entrada
│     ├── Películas   — plantilla compartida con Libros
│     └── Oraciones   — 7 tipos, 🔲 ¿entrada única densa o estructura distinta?
│
├── HOMBRES DE DIOS (página) — 9 entradas propias
│     └── Layout compartido + Containers/Columnas/Elementos Guardados
│         editados por santo (Dynamic Content descartado)
│
└── 🔲 PRÓXIMOS EVENTOS — sin resolver
      Candidato: feature "Eventos" de Avada (ya activada en el Setup Wizard)
```

---

## 3. Inventario de páginas — por prioridad

| # | Página / apartado | Tipo | Prioridad | Estado |
|---|---|---|---|---|
| — | Novedades | Apartado de home (no es página) | 🔴 Crítica | Definido |
| 1 | Qué hacemos | Página | 🔴 Crítica | Seminarios definido en parte, resto informativo |
| 2 | Conecta cada día | Página | 🔴 Crítica | Definido, con 2 piezas sin ubicar |
| 3 | Tiritaito | Página + 4 entradas | 🔴 Crítica | Definido — falta aplicar la unidad de podcast |
| 4 | Biblioteca | Página + 3 apartados | 🟡 Alta | Libros/Películas definidos, Oraciones con duda estructural |
| 5 | Hombres de Dios | Página + 9 entradas | 🟡 Alta | Método técnico confirmado (Sección 4.H) |
| — | Próximos eventos | Sin decidir | 🟢 Media | Candidato: feature Eventos de Avada |

---

## 4. Detalle por página

### A. Página de inicio

Novedades abre la página (lo primero que ve cualquier visitante, sin clic), seguido de
los accesos a las cinco páginas contenedoras. Próximos Eventos es la única pieza sin
encaje resuelto todavía en este mapa.

### B. Novedades

⚠️ **Corrijo algo que entendí mal en la ronda anterior:** asumí que era página propia por
estar en amarillo en el post-it. Carlitos aclara que **no es página ni entrada** — es el
apartado con el que se abre la home.

Contenido: tips del día (duplicado a propósito con Conecta cada día), avisos de eventos
importantes de la Iglesia o la liturgia (grandes fiestas), recordatorios generales de
Tiritaito.

*Implicación técnica a confirmar con Hno A:* sin URL propia — vive como sección del
Layout de la home, no como Post/Page independiente, salvo que se decida lo contrario.

### C. Qué hacemos

Tres apartados jerarquizados por importancia:

1. **Seminarios** — el más importante, con más contenido
2. **Grupo de alabanza** — puramente informativo
3. **Día de familias** — puramente informativo

**Seminarios — lo ya decidido:**
- Explicación de qué es un seminario
- Carteles de próximos seminarios — 🔲 se van a rediseñar para que sean más visuales
- Fechas — 🔲 posible unificación con los carteles rediseñados, para no duplicar
  información (se decide al ver el rediseño)
- Información práctica: precio, contacto, cómo llegar
- Vídeos de seminarios anteriores — 🔲 existe ya en la web vieja un sistema de reproductor
  de playlist + generador de JSON de YouTube (el mismo patrón de `tt_seminarios_json_url`
  de `00_CORE.md`); falta definir cómo se integra con el nuevo formato visual

**Grupo de alabanza y Día de familias:** contenido informativo, sin funcionalidad
adicional confirmada por ahora.

### D. Conecta cada día

| Elemento | Estado |
|---|---|
| Hora de la brisa | ✅ Definido, sección propia |
| Mensaje de la Virgen de hoy | ✅ Definido, sección propia |
| Misa | ✅ Definido — 🔲 probablemente absorbe el Salmo del día (ver abajo) |
| Tip | ✅ Definido — también en Novedades, duplicado intencional |
| Lenguas | ✅ Definido, sección propia |
| Salmo del día | 🔲 Se está evaluando integrarlo dentro de Misa (porque el salmo se lee en la misa de ese día) en vez de mantenerlo como apartado independiente |
| Habla por la palabra (cita bíblica aleatoria) | 🔲 Sin resolver dónde vive. Concepto de producto ya fijado: pensado para evangelización, fácil de compartir por QR o URL; al compartirse debe verse atractivo, y al clicar debe llevar a la sección propia de Conecta cada día donde está el generador. Pendiente confirmar si se construye así |
| Elige tu santo | 🔲 Sin resolver dónde vive |

### E. Tiritaito

Página contenedora de 4 entradas propias, cada una su propio post: **Tiritaito Music ·
Rincón de Nico · Charlas de la Biblia · Ejército de Intercesores** (detalle en F).

**Principio de unidad de presentación** (aplica igual a Oraciones, Sección G): Rincón de
Nico y Charlas de la Biblia son ambos podcasts de Tiritaito. Cada uno puede tener su
propio estilo (Rincón de Nico más colorido y orientado a niños, Charlas de la Biblia más
sobrio), **pero el formato del reproductor debe ser el mismo**. Esto ya está resuelto
técnicamente — es el patrón `[tt_podcast]` ya documentado en `METODOLOGIA_CONSTRUCCION.md`
(componente compartido, parámetros por instancia). No hace falta inventar nada, solo
aplicarlo con disciplina.

### F. Ejército de Intercesores

Estructura confirmada en esta sesión:

1. Introducción
2. **La batalla de cada día** — lo más importante, lo que verán a diario los
   intercesores para rezar: vídeo de las intenciones del día, intenciones del día
   (texto), lenguas del día
3. La batalla de la semana — intenciones de toda la semana
4. Formulario de inscripción al ejército
5. Claves de intercesión
6. Testimonios de intercesión

🔲 **Verificar con Carlitos:** `WEB_ACTUAL.docx` incluye también "Canción del Ejército",
que no apareció en el repaso de esta sesión. ¿Se mantiene, se descarta o se integra en
otro apartado?

### G. Biblioteca

Tres apartados, presentación visual distinta cada uno:

**Libros:** ya existe una aplicación PWA propia, bien desarrollada, que funciona como
sistema de biblioteca de libros. Se migra su lógica a formato de entrada de WordPress.

🔲 **Pieza nueva, sin documentar todavía:** esta PWA no aparece en ningún documento de
`tiritaito-docs` hasta ahora. No doy por hecho que sea Tiritaito for Creators — pendiente
que Hno A confirme si es la misma app, un módulo suyo, o un proyecto aparte, para
documentarla donde corresponda.

**Películas:** reutiliza el mismo sistema/plantilla que Libros — probablemente un Layout
compartido entre ambos.

**Oraciones:** dos dudas abiertas:
1. 🔲 ¿Es una sola entrada densa (por el volumen de contenido) o necesita una estructura
   distinta?
2. Con 7 tipos muy distintos en contenido (Adoraciones, Rosario, Novenas, Oración en
   lenguas, Vía Crucis, Salmos, Posesión mariana), el reto es que se sienta como una sola
   sección aunque el contenido varíe — mismo principio de unidad que en Tiritaito (E).

### H. Hombres de Dios

Mismas 9 entradas de la web actual (Papa Francisco, Papa León, Juan Pablo II, Maximiliano
Kolbe, Emiliano Tardif, Padre Pío, San Serafín de Sarov, Teresita, Ana Catalina).

✅ **Método técnico confirmado — reemplaza la decisión anterior:** un Layout único sirve
de plantilla compartida para todos los santos. Sobre esa plantilla, cada santo combina
distintos módulos (Containers, columnas, o elementos **Guardados** de la Avada Library),
editados con el contenido específico de cada uno — porque no todos los santos usan la
misma combinación de piezas (unos llevan audio, otros discursos, otros solo biografía).

**Esto descarta explícitamente el "Layout Content Section + Dynamic Content"** anotado en
`METODOLOGIA_CONSTRUCCION.md` Sección 4. No es un matiz menor: cambia si conviene un
Custom Post Type con ACF — con elementos Guardados, el valor de ACF (automatizar campos
uniformes) pierde sentido, porque los santos no comparten la misma estructura de campos.

🔲 *(Ver Sección 6 — actualizar `METODOLOGIA_CONSTRUCCION.md` cuando confirmes que este
Alcance está cerrado; no lo toco en esta sesión para no cambiar varios documentos a la vez
sin tu confirmación.)*

### I. Próximos eventos

Sin resolver. Candidato: la feature "Eventos" de Avada, ya activada en el Setup Wizard del
7 de julio (`GUIA_AVADA_LOCAL.md` Sección 4.0.1). Sin decidir si se usa tal cual o se
personaliza.

---

## 5. Principio transversal — unidad dentro de la diversidad

Aparece dos veces en esta sesión (Tiritaito, Oraciones) y conviene dejarlo como regla
general del proyecto, no solo de esas dos páginas:

> Cuando una página agrupa contenido de naturaleza distinta, cada pieza puede tener su
> propio tono o estilo visual, **pero el patrón de presentación debe ser compartido**,
> para que la persona sienta que está dentro de la misma sección aunque el contenido
> cambie.

Técnicamente es lo que ya resuelve el árbol de decisión de `GUIA_AVADA_LOCAL.md` Sección 8
(shortcode parametrizable o elemento Guardado, según quién mantenga el contenido) — no es
un principio nuevo, es la razón de negocio detrás de una decisión técnica ya tomada.

---

## 6. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Confirmar si este Alcance sustituye ya el stub de `01-producto/ALCANCE_WEB_NUEVA.md`,
   o si falta una vuelta más con Hna C
2. Una vez cerrado: actualizar `METODOLOGIA_CONSTRUCCION.md` Sección 4 (Hombres de Dios,
   Dynamic Content → Guardados) y su Sección 3 (Ejército de Intercesores ya no es sección
   suelta; Biblioteca entra en v1; Charlas de la Biblia vive bajo Tiritaito, no Biblioteca)
3. Revisar si `MIGRACION_CONTENIDO.md` necesita el mismo ajuste en su checklist de
   migración por sección

**Preguntas abiertas** (ninguna bloquea empezar a construir Qué hacemos y Conecta cada
día, que ya tienen lo esencial definido):

| # | Pregunta | Sección |
|---|---|---|
| 1 | ¿Salmo del día vive dentro de Misa, o mantiene apartado propio? | D |
| 2 | ¿Dónde viven "Habla por la palabra" y "Elige tu santo"? | D |
| 3 | ¿Cómo se integra el sistema de vídeos de seminarios pasados con el nuevo formato de carteles? | C |
| 4 | ¿"Canción del Ejército" se mantiene? | F |
| 5 | ¿La PWA de biblioteca de libros es Tiritaito for Creators, un módulo suyo, u otra cosa? | G |
| 6 | ¿Oraciones es una entrada única densa o necesita estructura distinta? | G |
| 7 | ¿Se usa la feature Eventos de Avada tal cual, o se personaliza? | I |

---

*Para la mayor gloria de Dios · tiritaito.com*

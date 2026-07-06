# Tiritaito — Metodología de Trabajo para la Web Nueva
*Documento de referencia — Proyecto de Investigación (Local by Flywheel + Avada)*

*Para la mayor gloria de Dios y honra de la Virgen María · tiritaito.com*

---

## 0. Propósito de este documento

Este documento recoge el diagnóstico técnico de la Web Actual (producción) y lo convierte en
un método de trabajo claro para la Web Nueva. No es una lista de bugs a corregir — la web
actual funciona y está en modo supervivencia. Es una guía de **cómo evitar que la Web Nueva
herede los mismos patrones de deuda técnica**, aprovechando mejor lo que Avada ya ofrece
de forma nativa.

**Alcance:** aplica solo al proyecto en Local by Flywheel. Nada de aquí debe usarse para
tocar producción salvo que se decida explícitamente lo contrario.

**Metodología de este diagnóstico:** se basa en 2 auditorías Lighthouse/PageSpeed, el código
fuente completo de una página (`ejercito-de-intercesores`), y una revisión estructural
(sin código fuente) de otras tres páginas (`home`, `san-juan-pablo-ii`, `rincon-de-nico`).
Donde el hallazgo es una hipótesis pendiente de confirmar en el entorno real, se marca
explícitamente como tal — no se presenta nada como certeza si no se ha verificado.

---

## 1. Diagnóstico heredado — Resumen ejecutivo

### 1.1 Lo que funciona bien (no reinventar en la Web Nueva)

| Elemento | Estado |
|---|---|
| Rendimiento real de usuario (datos de campo) | LCP 2,4s, CLS 0,09, TTFB 0,6s — bueno |
| Sistema de variables CSS (`--tt-*`) | Bien diseñado; el problema es que no siempre se usa |
| `fetch_feed()` server-side para el podcast | Sólido, sin proxies externos, documentado |
| Shortcode `[tt_podcast]` | **Confirmado funcionando correctamente** en "El Rincón de Nico" |
| Caché LiteSpeed + exclusión de `/wp-json/tiritaito/` | Correcta |

### 1.2 Los tres patrones de deuda técnica identificados

**A. Componentes reinventados en vez de reutilizados**
En una sola página conviven hasta tres sistemas de reproductor de audio/vídeo distintos
(`.pp-*`, `.mp-*`, `.hmds-*`) que hacen esencialmente lo mismo. El accordion "¿Qué es X?"
con vídeo aparece copiado letra por letra en al menos dos páginas distintas
(`ejercito-de-intercesores`, `rincon-de-nico`).

**B. CSS/JS global cargándose donde no se usa**
Sistemas completos como `.tiritaito-santo` (~700 líneas) o `.tiritaito-libros` (~500 líneas)
se cargan en el `<head>` de páginas que no usan ninguna de esas clases. Esto sugiere que
están marcados "Run everywhere" en Code Snippets por comodidad, no por necesidad real.

**C. Funcionalidad nativa de Avada sustituida por código custom**
El menú flyout nativo de Avada (`fusion-flyout-menu`) está presente en el HTML pero forzado
a `display:none`, y en su lugar hay un menú hamburguesa hecho a mano, con su propio CSS/JS,
pegado directamente en el contenido de página en vez de vivir como snippet global.
De forma similar, se carga Swiper.js desde `unpkg.com` en cada página mientras Avada ya
incluye sus propios estilos de Swiper condicionalmente (`avada-swiper-md-css` aparece en el
`<head>`) — **hipótesis a confirmar en Local**: es probable que el carrusel nativo de Fusion
Builder cubra la misma necesidad sin dependencia externa.

### 1.3 Causa raíz común

Todo el contenido se ha ido pegando directamente en el `post_content` de cada página,
reinventando CSS/JS en cada ocasión, en vez de construir snippets globales reutilizables
o usar los elementos nativos de Fusion Builder. No es un problema de Avada — es un problema
de disciplina de componentización y de aprovechamiento de lo que el theme ya trae.

---

## 2. Principio rector para la Web Nueva

> **Antes de escribir una línea de CSS o JS nuevo, comprobar en este orden:**
> 1. ¿Lo resuelve un elemento nativo de Fusion Builder?
> 2. ¿Ya existe un snippet global de Tiritaito que hace esto (o algo muy parecido)?
> 3. Solo si la respuesta a ambas es no, se construye un componente nuevo — y se construye
>    para ser reutilizable, no para esa página en concreto.

Este único principio, aplicado con disciplina, habría evitado los tres patrones de deuda
listados arriba.

---

## 3. Árbol de decisión — ¿Dónde va cada pieza?

```
¿Se usa en más de una página?
│
├── SÍ → Code Snippets (global)
│         │
│         ├── ¿Es lógica de servidor / shortcode?     → PHP, "Run everywhere" solo si
│         │                                              de verdad se usa en todas
│         ├── ¿Es un módulo visual con HTML+CSS+JS?    → Snippet tipo HTML, ejecución
│         │                                              condicionada a la existencia
│         │                                              del elemento en la página
│         │                                              (patrón `if (document.getElementById...))`
│         └── ¿Es un elemento visual reutilizable      → Avada Library (ver 3.1)
│              con contenido variable por página?
│
└── NO → Code Block de la entrada en Avada Live
          (nunca en Code Snippets global)
```

### 3.1 Avada Library — la herramienta que falta aprovechar

Avada permite guardar secciones, filas, columnas o elementos completos en la **Library**
(Avada → Library) como elementos globales o reutilizables. Un elemento guardado como
**global** se actualiza en todas las páginas donde está insertado al editarlo una sola vez.

**Aplicación directa a los hallazgos:**
- El bloque accordion "¿Qué es X?" + vídeo → candidato perfecto a **elemento global de
  Library** con campos variables (título, texto, ID de YouTube) en vez de copiarlo página
  a página.
- El footer, si no lo es ya → candidato a Global Layout de footer (ya existe uno:
  "Footer oficial", visto en la barra de admin — confirmar que todas las páginas lo heredan
  y no tienen un footer duplicado en su propio contenido).

*Pendiente de confirmar en Local:* el comportamiento exacto de campos variables dentro de un
elemento global (si Avada lo soporta nativamente o si requiere combinarlo con Dynamic
Content / ACF).

### 3.2 Elementos nativos de Fusion Builder a evaluar antes de programar

| Necesidad detectada en el sitio actual | Solución custom actual | Elemento nativo a evaluar |
|---|---|---|
| Menú hamburguesa / navegación móvil | HTML/CSS/JS a mano por página | Fusion Header — Flyout Menu (`fusion-flyout-menu`), ya presente en el theme pero desactivado |
| Carrusel de imágenes/vídeos | JS a mano + Swiper de `unpkg.com` | Elemento "Carousel" / "Post Cards" de Fusion Builder (usa Swiper interno) |
| Accordion desplegable | `toggle-ios` custom por página | Elemento "Toggles" de Fusion Builder |
| Modal de vídeo/imagen | Modal custom por módulo | Elemento "Modal" de Fusion Builder, o Lightbox nativo en imágenes |
| Reproductor de audio simple | 3 sistemas distintos (`.pp-*`, `.mp-*`, `.hmds-*`) | Consolidar en `[tt_podcast]` (ya probado y funcionando) para todo lo que sea episodios; evaluar si el "Tiritaito Music" con cola necesita quedarse custom por su complejidad (cola, playlists) o si una versión reducida basta |

**Importante:** estas equivalencias nativas son puntos de partida para investigar en Local,
no garantías. Cada una debe probarse antes de decidir migrar el contenido real.

---

## 4. Convenciones de código (heredadas y confirmadas — mantener)

Del sistema documentado en `00_CORE.md`, confirmadas como correctas y a seguir sin cambios:

- Snippets tipo **HTML** con `<style>` + `<script>` juntos (Code Snippets free no separa CSS/JS).
- Prefijo de clases `tt-` + BEM → `tt-modulo__elemento--modificador`.
- Variables CSS siempre `var(--tt-*)`, nunca hex sueltos.
- Breakpoints en `1024px` / `768px` / `480px`.
- Patrón JS obligatorio:
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
- `border-radius: 25px` en cards/botones/contenedores — firma visual Tiritaito.
- Fondo blanco puro, sin modo oscuro.
- Tipografía: títulos en "Yeah Papa", cuerpo en "Helvetica Neue" — **ninguna fuente de
  Google Fonts debería estar presente**; su aparición en el sitio actual (Inter, vía
  `@import` en el CSS del podcast) es deuda a no replicar.

---

## 5. Checklist antes de crear un módulo nuevo

- [ ] ¿Ya existe un elemento nativo de Fusion Builder que resuelva esto?
- [ ] ¿Ya existe un snippet global de Tiritaito con funcionalidad equivalente?
- [ ] Si se construye desde cero: ¿está pensado para reutilizarse (parámetros, no
      contenido fijo)?
- [ ] ¿El JS sigue el patrón condicional + IIFE?
- [ ] ¿Usa `var(--tt-*)` en vez de hex sueltos?
- [ ] ¿Se ha probado en consola del navegador que no hay errores de elementos inexistentes
      (`getElementById` sobre algo que no está en el DOM)?
- [ ] ¿El snippet necesita estar en TODAS las páginas, o solo en una plantilla concreta?

## 6. Checklist antes de marcar un snippet "Run everywhere"

- [ ] ¿Se usa en más del 50% de las plantillas del sitio?
- [ ] Si la respuesta es "es más fácil que decidir dónde", **no marcarlo global** —
      ese es exactamente el patrón que generó CSS de Santos y de Biblioteca cargándose
      en páginas donde no se usa.
- [ ] ¿Se ha comprobado el peso que añade a páginas que no lo necesitan?

---

## 7. Flujo de trabajo sugerido en Local by Flywheel

1. Antes de construir un módulo nuevo, revisar el inventario de snippets existentes
   (sección 8) para evitar duplicar funcionalidad con otro nombre.
2. Prototipar el módulo en Local, probando explícitamente en una página de prueba con
   DevTools abierto (pestaña Console y Network) antes de darlo por terminado.
3. Si el módulo es candidato a reutilizarse en más de una plantilla, evaluar Avada Library
   antes que Code Snippets — reduce el riesgo de copias que se desincronizan entre sí.
4. Documentar cada snippet nuevo con un comentario de cabecera: nombre, ubicación
   (Head/Footer/Library), y si toca por canal/entrada o es fijo.
5. Antes de dar una plantilla por cerrada, auditar qué CSS/JS global se está cargando en
   ella y verificar que cada uno se usa realmente (evitar el patrón de Santos/Biblioteca
   cargándose sin uso).

---

## 8. Inventario de componentes a consolidar (punto de partida)

| Componente actual | Prefijo | Dónde se ha visto | Acción propuesta para Web Nueva |
|---|---|---|---|
| Reproductor de podcast (RSS) | `.pp-*` | Global, `[tt_podcast]` | Mantener como está — funciona bien |
| Reproductor "Tiritaito Music" (con cola) | `.mp-*` | `ejercito-de-intercesores` | Evaluar si puede sustituirse por `[tt_podcast]` o si necesita quedarse por la función de cola/playlists |
| Mini reproductor de un track | `.hmds-*` | `ejercito-de-intercesores` | Candidato claro a eliminar, sustituir por `[tt_podcast]` con un solo episodio |
| Accordion "¿Qué es X?" + vídeo | `toggle-ios` | `ejercito-de-intercesores`, `rincon-de-nico` | Un solo snippet global parametrizable, o elemento "Toggles" nativo |
| Menú hamburguesa custom | (sin prefijo `tt-`) | Al menos `ejercito-de-intercesores` | Sustituir por Flyout Menu nativo de Avada |
| Sistema de Santos | `.tiritaito-santo` | Global (usado en páginas de Hombres de Dios) | Mantener, pero dejar de cargarlo en páginas que no lo usan |
| Sistema de Biblioteca | `.tiritaito-libros` | Global (aún sin páginas reales) | Igual que arriba — cargar solo donde se implemente |
| "Grupo de alabanza" (mapa + horario) | Sin confirmar | Home | **No auditado a nivel de código** — pendiente |
| Galería "Próximos eventos" | Sin confirmar | Home | **No auditado a nivel de código** — pendiente |

---

## 9. Preguntas abiertas / pendientes de auditar

- Código fuente completo de la home (`/blog/`) — para confirmar módulos "Grupo de alabanza"
  y "Próximos eventos" a nivel de CSS/JS, no solo estructura.
- Confirmar si el teaser de "Ejército de Intercesores" en portada carga bien su imagen o
  está roto (no verificable solo con extracción de texto).
- Verificar en Avada → Library si el "Footer oficial" es realmente un Global Layout
  heredado o si hay footers duplicados por página.
- Confirmar en Local si el elemento nativo "Carousel" de Fusion Builder cubre las
  necesidades del carrusel custom actual (autoplay, swipe táctil, modal de vídeo al clic).
- Confirmar si Avada Library soporta campos variables por instancia dentro de un elemento
  global, o si eso requeriría una solución adicional (ACF, Dynamic Content).

---

*Este documento se irá actualizando conforme se audite código fuente adicional
(home completa, Grupo de alabanza, Próximos eventos, Biblioteca cuando exista contenido).*

*Ad maiorem Dei gloriam · tiritaito.com*

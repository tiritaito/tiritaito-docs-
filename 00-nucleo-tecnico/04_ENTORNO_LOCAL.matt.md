<!--
  ⚠️ ARCHIVO DE TRABAJO CON DATOS FICTICIOS (PLACEHOLDER)
  Este NO es el archivo oficial de producción/Local. Todos los tokens,
  dominios, credenciales y valores concretos aquí son ficticios y NO
  funcionarán contra ningún entorno real de Tiritaito. Trabaja con
  normalidad sobre este archivo — tus cambios se revisan y se aplican
  al archivo oficial (04_ENTORNO_LOCAL.md) por el equipo interno.
  Si necesitas un valor real para probar algo end-to-end, pídelo
  directamente — no lo asumas ni lo inventes.
-->

# TIRITAITO.COM — Entorno Local (Web Nueva) — copia de trabajo, datos ficticios
*Complementa a: 00_CORE.matt.md — usar SIEMPRE los dos juntos en este contexto*
*Este es el documento que describe el entorno de desarrollo local (distinto de producción)*

---

## 1. Por qué existe este documento

El stack técnico general (patrones de código, nomenclatura, seguridad, CSS) vive en
`00_CORE.matt.md`. Este documento describe específicamente el entorno de desarrollo local
(Local by Flywheel) — su dominio, su forma de arrancar, sus herramientas propias — que es
distinto del entorno de producción.

**Regla:** antes de generar cualquier código con URLs o credenciales concretas para el
entorno local, usa siempre las constantes de este documento (ficticias en esta copia) —
nunca inventes valores nuevos ni asumas que coinciden con producción.

---

## 2. Infraestructura del entorno Local (placeholder)

| Componente | Detalle |
|---|---|
| Entorno | Local by Flywheel |
| Dominio local | `ejemplo-local.local` (placeholder — el dominio real no se comparte en esta copia) |
| WordPress | Instalado en la **raíz** del sitio local — **no** en un subdirectorio `/blog/` como producción |
| SSL | Local lo genera automáticamente — comportamiento igual que en producción. Requiere pulsar "Trust" manualmente en la pestaña SSL del sitio dentro de Local, o puede romper silenciosamente las respuestas de la API |

---

## 3. Constantes JS — versión Local (placeholder)

```javascript
// ⚠️ Valores ficticios — el entorno local real tiene su propio dominio y
// token, que no se comparten en esta copia de trabajo.
const WP_BASE  = 'https://ejemplo-local.local/wp-json';
const APP_PIN  = '0000'; // placeholder — nunca el PIN real

// Autenticación real del entorno: token propio (TT_WRITE_TOKEN) vía
// header X-TT-Token — NUNCA Application Password. El valor real del
// token no se comparte en esta copia; pídelo si lo necesitas para una
// prueba concreta.
const TT_WRITE_TOKEN = 'PLACEHOLDER_NO_ES_TOKEN_REAL';
```

**Nota de seguridad:** el token de escritura del entorno de desarrollo es un dato sensible
real — no se comparte en archivos de trabajo como este, ni siquiera para un entorno que no
es producción. Un token filtrado permite crear, editar y borrar contenido en el sistema.

---

## 4. Herramientas que solo existen en Local (aprovecharlas)

| Herramienta | Para qué sirve | Dónde está |
|---|---|---|
| Base de datos directa (Adminer) | Inspeccionar `wp_options` sin pasar por el panel de WP — más rápido al depurar | Botón "Database" en Local |
| WP-CLI | Comandos de WordPress desde terminal — pruebas rápidas de snippets, y la única vía para corregir el sitio si el panel de administración queda bloqueado | Botón "Open Site Shell" en Local |
| Live Link | URL pública temporal del sitio local — para que el equipo lo vea sin estar en el mismo ordenador/red. ⚠️ No fiable para verificar diseño/tipografía/CSS — reescribe rutas del dominio local, pero no todas | Botón "Live Link" en Local |
| Blueprint | Guarda el estado actual del sitio como plantilla reutilizable — también sirve como copia de seguridad | Menú del sitio → "Save as Blueprint" |

---

## 5. Checklist antes de cada sesión de código sobre este entorno

- [ ] Confirmar que el dominio de este documento sigue siendo el que corresponde (puede cambiar si se recrea el sitio) — para el valor real, coordinar con el equipo, no asumir
- [ ] Confirmar que el sistema de autenticación sigue siendo token propio (`TT_WRITE_TOKEN` vía `X-TT-Token`), no Application Password
- [ ] Si el código incluye una URL o credencial real, verificar que viene del equipo — nunca inventar ni reutilizar un valor de otra sesión sin confirmar que sigue vigente

---

*Para la mayor gloria de Dios · tiritaito.com*

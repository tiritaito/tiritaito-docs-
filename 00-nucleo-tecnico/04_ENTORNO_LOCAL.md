# TIRITAITO.COM — Entorno Local (Web Nueva)
*Complementa a: 00_CORE.md — usar SIEMPRE los dos juntos en este Proyecto*
*Este es el ÚNICO documento que cambia entre la Web Vieja y la Web Nueva. Los patrones (Data Layer Pattern, nomenclatura, seguridad, CSS) son los mismos y siguen viviendo en 00_CORE.md*

---

## 1. Por qué existe este documento

`00_CORE.md` describe la arquitectura **en producción** — sus URLs, sus credenciales, su dominio real. La Web Nueva se construye en un entorno distinto (Local by Flywheel), con su propio dominio y sus propias credenciales.

**Regla de este Proyecto:** antes de generar cualquier código con URLs o credenciales concretas, usa siempre las constantes de este documento — nunca las de `00_CORE.md` directamente. Si este documento no está adjunto en la conversación, pregunta antes de asumir ningún valor.

---

## 2. Infraestructura del entorno Local

| Componente | Detalle |
|---|---|
| Entorno | Local by Flywheel |
| Dominio local | **`tiritaito-real.local`** — confirmado con WP-CLI el 12 julio 2026 |
| WordPress | Instalado en la **raíz** del sitio — **NO** en `/blog/` como producción. A diferencia de `00_CORE.md`, este entorno no replica esa ruta. Intentar alinearlo con producción (añadiendo `/blog/` en Ajustes → Generales) provoca un bucle de redirecciones en el panel de administración y rompe los estilos visuales — si ocurre, se corrige solo desde WP-CLI (Site Shell), el panel queda bloqueado por el propio bucle: `wp option update siteurl 'https://tiritaito-real.local'` · `wp option update home 'https://tiritaito-real.local'` · `wp rewrite flush --hard` |
| SSL | Local lo genera automáticamente, pero **necesita "Trust" manual** en la pestaña SSL del sitio dentro de Local — sin confiarlo, el navegador bloquea la conexión y puede llegar a romper silenciosamente las respuestas de la API (`/wp-json` devolviendo un carácter suelto en vez del JSON completo) |

---

## 3. Constantes JS — versión Local (no confundir con las de producción)

```javascript
// ⚠️ Constantes del entorno LOCAL — distintas de las de producción en 00_CORE.md
const WP_BASE         = 'https://tiritaito-real.local/wp-json'; // SIN /blog/
const TT_WRITE_TOKEN   = 'PEGAR AQUÍ EL TOKEN REAL DEL LOCAL — nunca el de producción';
const APP_PIN          = '1234'; // cambiar antes de lanzar
```

⚠️ **Reconstrucción, no código verificado:** la autenticación de Tiritaito for Creators es token propio (`TT_WRITE_TOKEN`), Application Password descartado definitivamente — pero no tengo acceso al HTML real de la app para confirmar el header o parámetro exacto con el que se envía el token al servidor. Antes de copiar este bloque, verificar el mecanismo real contra `apps/v1/` o `apps/v2/` en GitHub (ver `TIRITAITO_FOR_CREATORS_VERSIONS.md`).

**Nota de seguridad:** el token del Local se crea de cero ahí — nunca copiar el de producción. Son dos WordPress distintos con credenciales distintas.

⚠️ **Discrepancia sin resolver (11-12 julio 2026):** el snippet "TT Creators + Biblioteca — Endpoint central v3" tiene un comentario que dice que los tokens ya no están en texto plano, pero el código real los sigue teniendo escritos con `define()` como respaldo. Ver `GUIA_AVADA_LOCAL.md` Sección 2 para el detalle y los comandos de verificación (`wp eval "echo TT_WRITE_TOKEN;"`, etc.).

---

## 4. Herramientas que solo existen en Local (aprovecharlas)

| Herramienta | Para qué sirve | Dónde está |
|---|---|---|
| Base de datos directa | Inspeccionar `wp_options` sin pasar por el panel de WP — más rápido al depurar | Botón "Database" en Local |
| WP-CLI | Comandos de WordPress desde terminal — pruebas rápidas de snippets, y la única vía para corregir el sitio si el panel de administración queda bloqueado | Botón "Open Site Shell" en Local |
| Live Link | URL pública temporal del sitio local. ⚠️ **No fiable para revisar diseño/tipografía/CSS** — Local no reescribe siempre todas las rutas absolutas al dominio local (confirmado en la documentación oficial de Local). Sirve solo para enseñar estructura general a Hna C — para QA visual real, usar DevTools responsive directamente sobre `tiritaito-real.local`. Detalle completo en `GUIA_AVADA_LOCAL.md` Sección 2 | Botón "Live Link" en Local |
| Blueprint | Guarda el estado actual del sitio como plantilla reutilizable | Menú del sitio → "Save as Blueprint" |

---

## 5. Checklist antes de cada sesión de código en este Proyecto

- [ ] Confirmar que el dominio sigue siendo `tiritaito-real.local`, **sin `/blog/`** (puede cambiar si se recrea el sitio)
- [ ] Confirmar que el certificado SSL sigue en estado "Trusted" en la pestaña SSL del sitio dentro de Local
- [ ] Confirmar que `TT_WRITE_TOKEN` del Local está activo y verificado (no asumir Application Password)
- [ ] Si el código incluye una URL o credencial, verificar que viene de aquí, no de `00_CORE.md`

---

*Para la mayor gloria de Dios · tiritaito.com*

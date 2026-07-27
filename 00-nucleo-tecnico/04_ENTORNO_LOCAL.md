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
| Dominio local | `tiritaito.local` ⚠️ *verificar — ver nota abajo* |
| WordPress | mismo `/blog/` subdirectorio que producción, para mantener coherencia de rutas |
| SSL | Local lo genera automáticamente — comportamiento igual que en producción |

> ⚠️ **Verificar antes de usar:** el dominio exacto se ve en Local by Flywheel, en la ficha del sitio, junto al botón "Open Site", o en Site → Domain. Si no es exactamente `tiritaito.local`, dímelo y actualizo este documento con el valor real.

---

## 3. Constantes JS — versión Local (no confundir con las de producción)

```javascript
// ⚠️ Constantes del entorno LOCAL — distintas de las de producción en 00_CORE.md
const WP_BASE  = 'https://tiritaito.local/blog/wp-json';
const WP_USER  = 'makecom'; // o el usuario Application Password creado en el Local
const WP_PASS  = 'xxxx xxxx xxxx xxxx xxxx xxxx'; // Application Password PROPIO del Local
const AUTH     = 'Basic ' + btoa(WP_USER + ':' + WP_PASS.replace(/\s/g, ''));
const APP_PIN  = '1234'; // cambiar antes de lanzar
```

**Nota de seguridad:** el Application Password del Local se crea de cero ahí — nunca copiar el de producción. Son dos WordPress distintos con credenciales distintas.

---

## 4. Herramientas que solo existen en Local (aprovecharlas)

| Herramienta | Para qué sirve | Dónde está |
|---|---|---|
| Base de datos directa | Inspeccionar `wp_options` sin pasar por el panel de WP — más rápido al depurar | Botón "Database" en Local |
| WP-CLI | Comandos de WordPress desde terminal — pruebas rápidas de snippets | Botón "Open Site Shell" en Local |
| Live Link | URL pública temporal del sitio local — para que Hna C o el equipo lo vean sin estar en el mismo ordenador | Botón "Live Link" en Local |
| Blueprint | Guarda el estado actual del sitio como plantilla reutilizable | Menú del sitio → "Save as Blueprint" |

---

## 5. Checklist antes de cada sesión de código en este Proyecto

- [ ] Confirmar que el dominio de este documento sigue siendo el actual (puede cambiar si se recrea el sitio)
- [ ] Confirmar que el Application Password local está activo
- [ ] Si el código incluye una URL o credencial, verificar que viene de aquí, no de `00_CORE.md`

---

*Para la mayor gloria de Dios · tiritaito.com*

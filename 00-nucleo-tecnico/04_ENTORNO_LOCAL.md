# TIRITAITO.COM — Entorno Local (Web Nueva)
*Complementa a: 00_CORE.md — usar SIEMPRE los dos juntos en este Proyecto*
*Es el documento que recoge las URLs y credenciales propias de la Web Nueva en Local. Los patrones (Data Layer Pattern, nomenclatura, seguridad, CSS) son los mismos y siguen viviendo en 00_CORE.md*
*Reescrito el 14 de noviembre de 2026. Fuente de verdad: `TIRITAITO_FOR_CREATORS_VERSIONS.md` (contrato y autenticación de la app) y `GUIA_AVADA_LOCAL.md` Sección 2 (particularidades de Local). Sustituye a la versión de julio, que usaba `tiritaito.local`, el subdirectorio `/blog/` y Application Password.*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 1. Por qué existe este documento

`00_CORE.md` describe la arquitectura **en producción** — sus URLs, sus credenciales, su dominio real. La Web Nueva se construye en un entorno distinto (Local by Flywheel), con su propio dominio y sus propias credenciales.

**Regla de este Proyecto:** antes de generar cualquier código con URLs o credenciales concretas, usa siempre las constantes de este documento — nunca las de `00_CORE.md` directamente. Si este documento no está adjunto en la conversación, pregunta antes de asumir ningún valor.

---

## 2. Infraestructura del entorno Local

| Componente | Detalle |
|---|---|
| Entorno | Local by Flywheel |
| Dominio | **`tiritaito-real.local`** (con guion). Cualquier código que use `tiritaito.local` falla |
| WordPress | En la **raíz** del sitio. **No hay subdirectorio `/blog/`** (producción sí lo usa) |
| SSL | Local lo genera solo. Hay que pulsar **"Trust"** en la pestaña SSL del sitio; sin eso, `/wp-json` puede devolver un carácter suelto en vez del JSON completo |
| Avada | Versión probada en Local: **7.16.3** (confirmada por Carlitos, 14 nov 2026). Registrada como staging: el dominio `*.local` califica sin coste (`GUIA_AVADA_LOCAL.md` §1) |
| Importadores | Snippets «TT Podcast — Importador» y «TT Vídeos YouTube — Importador», alcance «Run snippet everywhere» (`00_CORE.md` §1) |

⚠️ **No alinear el Local con producción añadiendo `/blog/`** en Ajustes → Generales: provoca un bucle de redirecciones en el panel y la pérdida de todos los estilos (ocurrió el 12 julio 2026). Si el panel queda bloqueado, se corrige desde WP-CLI («Open Site Shell» en Local):

```
wp option update siteurl 'https://tiritaito-real.local'
wp option update home 'https://tiritaito-real.local'
wp rewrite flush --hard
```

---

## 3. Constantes JS — versión Local

```javascript
// Constantes del entorno LOCAL — distintas de las de producción en 00_CORE.md
const WP_BASE        = 'https://tiritaito-real.local/wp-json';
const TT_WRITE_TOKEN = 'PEGAR AQUÍ EL TOKEN DEL LOCAL'; // distinto del de producción; nunca en un documento
const APP_PIN        = '1234'; // ⚠️ solo desarrollo — cambiar antes de lanzar
```

**Autenticación:** token propio (`TT_WRITE_TOKEN`) en la cabecera `X-TT-Token`. **Application Password y `Authorization: Basic` están descartados definitivamente** (`TIRITAITO_FOR_CREATORS_VERSIONS.md` §1). El token vive en un `define()` dentro del snippet del endpoint central (decisión de Carlitos, 26 julio 2026: no se mueve a `wp-config.php`). El del Local se crea de cero ahí: nunca copiar el de producción ni escribir ninguno en un documento o en un chat.

**Clave de YouTube del importador de vídeos:** `TT_YT_API_KEY`, definida en el `wp-config.php` del Local. Es **distinta** de la de producción y su valor no se escribe nunca en el repositorio.

**Rutas:** las mismas de `00_CORE.md` §4, con el `WP_BASE` de arriba. Ejemplo: `https://tiritaito-real.local/wp-json/tiritaito/v1/novedades`.

---

## 4. Herramientas que solo existen en Local (aprovecharlas)

| Herramienta | Para qué sirve | Dónde está |
|---|---|---|
| Base de datos directa (Adminer) | Inspeccionar `wp_options` y los campos ACF sin pasar por el panel | Botón "Database" |
| WP-CLI | Pruebas rápidas y única vía para arreglar el sitio si el panel se bloquea | Botón "Open Site Shell" |
| Live Link | URL pública temporal para enseñar la estructura al equipo. ⚠️ **No fiable para QA visual** (fuentes y CSS con ruta absoluta pueden no cargar): para revisar diseño, usar DevTools sobre `tiritaito-real.local` o un móvil real en la misma red | Botón "Live Link" |
| Blueprint | Foto fija del sitio, también sirve de copia de seguridad. No queda sincronizado con el original | Menú del sitio → "Save as Blueprint" |
| Cloud Backups (Local v10+) | Backup a Google Drive o Dropbox, restaurable en otro ordenador. Base del trabajo con dos ordenadores (`GUIA_AVADA_LOCAL.md` §2.1) | Pestaña "Backups" del sitio |

---

## 5. Qué es distinto de producción

Esta tabla es el punto de partida del plan de lanzamiento (investigación pendiente, ver `MIGRACION_CONTENIDO.md`).

| | Local | Producción |
|---|---|---|
| Dominio | `tiritaito-real.local` | `www.tiritaito.com` |
| Ruta de WordPress | Raíz | `/blog/` |
| `WP_BASE` | `https://tiritaito-real.local/wp-json` | `https://www.tiritaito.com/blog/wp-json` |
| Token de escritura | Propio del Local | Propio de producción |
| PIN de la app | `1234` (desarrollo) | Cambiar antes de lanzar |
| Clave de YouTube | Propia del Local | Propia de producción |
| Caché | Sin LiteSpeed | LiteSpeed (excluir `/blog/wp-json/tiritaito/`) |
| Cron de WordPress | 🔲 Sin documentar | 🔲 Sin comprobar si Raiola bloquea `wp-cron.php` (`PODCAST_ESTRUCTURA.md` §13.2) |
| Versión de Avada | 7.16.3 | 🔲 Sin comprobar |

---

## 6. Checklist antes de cada sesión de código en este Proyecto

- [ ] El dominio sigue siendo `tiritaito-real.local`, sin `/blog/` (puede cambiar si se recrea el sitio)
- [ ] El certificado SSL sigue en estado **"Trusted"**
- [ ] El token del Local está definido en el snippet del endpoint central
- [ ] Si el código incluye una URL o credencial, viene de aquí, no de `00_CORE.md`
- [ ] Si el Local se ha recreado: el snippet del endpoint, los CPT (`novedades`, `episodio`, `temporada`, vídeos) y las Options Pages de ACF siguen existiendo — no darlos por hechos
- [ ] Si la sesión usa dos ordenadores: seguir `GUIA_AVADA_LOCAL.md` §2.1

---

## 7. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Comprobar la versión de Avada de producción (5, tabla) y alinearla antes del lanzamiento.
2. Cuando se decida cómo se despliega la web nueva (raíz o `/blog/`), actualizar la sección 5.
3. La copia `04_ENTORNO_LOCAL.matt.md` queda desactualizada: el sistema de colaboración externa está en pausa (`ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md` §11), sin acción por ahora.

**Preguntas abiertas:**

| # | Pregunta | Bloquea a |
|---|---|---|
| 1 | ¿La web nueva se despliega en la raíz o en `/blog/` en producción? | URLs, redirecciones 301 y todos los `WP_BASE` |
| 2 | ¿Qué versión de Avada tiene producción? | Despliegue (desfase con 7.16.3) |
| 3 | ¿Raiola bloquea `wp-cron.php`? | Puntualidad de los importadores |

---

*Para la mayor gloria de Dios · tiritaito.com*

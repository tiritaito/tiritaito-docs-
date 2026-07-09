# TIRITAITO.COM — Migración de Contenido
**Qué se migra de la web vieja a la nueva, y qué no — decisión cerrada tras la sesión de alcance, julio 2026**
*Reemplaza el enfoque anterior (framework sección por sección) tras decisión directa de Carlitos*

*Ad maiorem Dei gloriam et Mariae Virginis honorem*

---

## 0. Qué es este documento

Responde: **de todo lo que existe en la web vieja, ¿qué pasa a la web nueva, y con qué
método?**

| Si necesitas... | Ve a este documento en su lugar |
|---|---|
| Qué secciones tiene la web nueva y con qué prioridad | `ALCANCE_WEB_NUEVA.md` |
| Dónde y cómo construir una pieza ya decidida (Global/Guardado/Snippet) | `METODOLOGIA_CONSTRUCCION.md` |
| **Qué contenido de la web vieja migrar, y el plan de SEO** | **Este documento** |

---

## 1. Decisión de alcance — simplificada

✅ **Decisión directa de Carlitos (julio 2026):** solo se migran dos cosas. **Todo lo
demás se recrea limpio en la web nueva** — ninguna entrada, página o contenido textual se
exporta/importa de la web vieja.

Esto sustituye por completo el enfoque anterior de este documento (que evaluaba
migración sección por sección, con exportación XML para Hombres de Dios según volumen,
migración de archivos de medios, etc.). Es coherente con el principio ya recogido en
`ARQUITECTURA_Y_ROADMAP.md` (Fortaleza F5): empezar de cero, sin heredar deuda, incluida
la deuda de contenido.

---

## 2. Lo que SÍ se migra

### A. SEO — redirecciones 301

Si la web nueva cambia las URLs de páginas que ya posicionan en Google (con Search
Console activo), perder esas URLs sin redirección significa perder ese posicionamiento
de golpe.

**Plan:**
1. Desde Google Search Console, exportar el listado de páginas con más clics/impresiones
   de los últimos 12 meses
2. Para cada una, decidir la URL equivalente en la web nueva
3. Configurar redirecciones 301 (snippet PHP sencillo, o plugin de redirecciones si se
   prefiere gestión visual)
4. Verificar en Search Console, semanas después del lanzamiento, que no aparecen errores
   404 masivos

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

### B. La lógica de Tiritaito for Creators

No es "migración" en el sentido clásico — es reconectar la app al WordPress nuevo:

1. Volver a ejecutar en el WordPress nuevo el snippet del endpoint central
   (`TT Creators + Biblioteca — Endpoint central`)
2. Apuntar la PWA Tiritaito for Creators al nuevo endpoint
3. Autenticación: token propio (`TT_WRITE_TOKEN`), ya definitivo — **nunca** Application
   Password (confirmado en `GUIA_AVADA_LOCAL.md` Sección 2)
4. Las claves de `wp_options` (`tt_virgen`, `tt_brisa`, `tt_homilia_audio`,
   `tt_homilia_texto`, `tt_lenguas_url`, `tt_tip_1`, `tt_tip_2`, `tt_docx_lectura_url`,
   `tt_youtube_json_url`, `tt_seminarios_json_url`) no se migran con datos históricos —
   simplemente vuelven a poblarse desde la app apuntando al sitio nuevo

---

## 3. Lo que NO se migra — se recrea limpio

Todo el contenido textual y las entradas se escriben de nuevo, directamente en el formato
y la estructura de la web nueva:

- Entradas de Hombres de Dios (biografía, oración, historia de cada santo) — coherente
  además con el método ya confirmado en `ALCANCE_WEB_NUEVA.md` Sección 4.H: cada ficha se
  construye a mano sobre el Layout compartido con elementos Guardados, no hay automatización
  posible que un export/import pudiera aprovechar
- Charlas de la Biblia, Rincón de Nico, Conecta cada día — igual que en el enfoque
  anterior, el contenido único de cada entrada es mínimo (cambia solo el shortcode o el
  dato de `wp_options`); recrear a mano lleva minutos
- Oraciones, Biblioteca (Libros, Películas) — contenido nuevo, sin intentar reutilizar el
  de la web vieja
- Cualquier página con CSS de experimentos abandonados — directamente fuera, sin
  discusión

🔲 **Asunción a confirmar, no decisión cerrada:** entiendo que "lo demás no" incluye
también los **archivos multimedia** (imágenes, portadas de podcast, audios de homilías) —
es decir, que también se suben nuevos en vez de migrar la carpeta `/wp-content/uploads/`
completa, que era la recomendación de la versión anterior de este documento. Si algún
archivo concreto sí conviene reaprovecharlo (por ejemplo, audios de homilías ya grabados
que no se van a regrabar), dímelo y lo dejo como excepción explícita aquí — pero por
defecto, con "lo demás no", lo entiendo incluido.

---

## 4. Riesgo técnico que deja de aplicar

La versión anterior de este documento advertía sobre el riesgo de que un export/import
XML de WordPress rompiera la serialización en Base64 del `post_content` de Avada entre
dos instalaciones distintas. **Ese riesgo deja de ser relevante** — al no hacerse ningún
export/import de contenido, no hay `post_content` serializado que mover de un sitio a
otro.

---

## 5. Próximos pasos y preguntas abiertas

**Próximos pasos:**
1. Confirmar si los archivos multimedia quedan fuera de la migración (Sección 3) o si
   hay excepciones puntuales
2. Cuando se acerque el lanzamiento: ejecutar el plan de redirecciones 301 (Sección 2.A)
   con datos reales de Search Console
3. Reconectar Tiritaito for Creators al WordPress nuevo (Sección 2.B) cuando el entorno
   Local esté listo para recibir tráfico real de la app

**Preguntas abiertas:**

| # | Pregunta | Bloquea a |
|---|---|---|
| 1 | ¿Los archivos multimedia se recrean/suben nuevos, o hay excepciones que sí se reaprovechan? | Sección 3 |
| 2 | ¿Cuándo se considera "cerca del lanzamiento" para ejecutar el plan de redirecciones 301? | Sección 2.A |

---

*Para la mayor gloria de Dios · tiritaito.com*

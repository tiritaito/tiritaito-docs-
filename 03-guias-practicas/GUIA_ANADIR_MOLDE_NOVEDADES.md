# Cómo añadir un molde nuevo a las Novedades de la home

*Para Álvaro y Carlota · de Proyecto 2 (Hno C) · 4 de noviembre de 2026*

---

## 1. Cómo funciona el sistema (lo mínimo que hay que saber)

- Cada novedad tiene un campo **Molde**. Es el diseño con el que se pinta en la home.
- Hay **un solo Post Card** («novedades movil», el que usa la home). Dentro lleva **un bloque por molde**, y cada bloque solo se muestra si el campo `molde` de la novedad vale lo suyo.
- **Si ninguna novedad usa un molde, ese bloque no se pinta.** No hay que hacer nada para ocultarlo.
- La app ofrece los moldes leyéndolos de ACF. **Añadir un molde no toca PHP ni la app.**
- La novedad solo aporta datos (foto, título, texto, enlace, categoría). **Los rótulos fijos** («Nuevo seminario», «Pulsa para ver»…) **pertenecen al molde**: se escriben a mano dentro de su bloque.

| Dato de la novedad | Campo ACF |
|---|---|
| Foto o vídeo | `media_url` |
| Título | `titulo` |
| Texto | `texto` |
| Enlace del botón | `enlace` |
| Etiqueta (badge) | `categoria` |
| Tipo de medio (`imagen` o `video`) | `tipo` |

## 2. Quién hace qué

| Quién | Qué |
|---|---|
| Carlota | Diseña el molde (el boceto) |
| Álvaro | Lo construye en Avada y lo da de alta en ACF |

## 3. Pasos para añadir un molde (Álvaro)

**Paso 1 — Darlo de alta en ACF.**
1. Ve a ACF → Grupos de campos → «Novedades — Campos» → campo **Molde**.
2. En **Opciones**, añade una línea nueva con este formato: `valor : Etiqueta`.
   Ejemplo: `nuevo_seminario : Nuevo seminario`.
3. El **valor** va en minúsculas, sin espacios, sin tildes y con guion bajo. Es lo que se guarda. La **etiqueta** es lo que ve el equipo en la app.
4. Guarda el grupo.

⚠️ **Nunca cambies ni borres el valor de un molde que ya use alguna novedad.** Esas novedades quedarían sin diseño y la tarjeta saldría en blanco. Si hay que retirar un molde, primero cambia el molde de todas sus novedades.

**Paso 2 — Construir el bloque en el Post Card.**
1. Abre el Post Card de la home → **Edit Post Card**.
2. Añade un bloque nuevo con el diseño del molde, debajo de los demás.
3. Conecta cada pieza a su campo de la tabla de la sección 1 (Dynamic Data → Custom Field). Los rótulos fijos se escriben a mano.
4. Ponle la condición: **Extras → Conditional Rendering → ACF Field `molde` → Equal To → el valor del paso 1.**
5. Guarda.

**Paso 3 — Comprobar.**
1. Pon el molde nuevo en una novedad de prueba (desde wp-admin, o desde la app cuando esté lista).
2. **Avada → Options → Performance → Reset Avada Caches.**
3. Abre la home en **incógnito** y mira en Desktop, Medium y Small.
4. Cambia la novedad a otro molde: el bloque nuevo debe **desaparecer sin dejar hueco**.

## 4. Lo que está comprobado y lo que no

| Punto | Estado |
|---|---|
| Un Text Block con Conditional Rendering sobre `molde` (Equal To) funciona dentro del Post Card | ✅ Probado el 4/11/2026 |
| El elemento Video con la misma condición funciona | ✅ Probado el 4/11/2026 |
| Un molde sin novedades no deja rastro ni hueco | ✅ Probado el 4/11/2026 («MOLDE B») |
| **Una Columna o Nested Columns entera admite Conditional Rendering** | 🔲 **Sin confirmar.** Si la admite, un solo bloque por molde basta. Si no, hay que poner la condición **en cada elemento** del molde |
| Cuatro moldes o más a la vez dentro del mismo Post Card | 🔲 Sin probar |
| Rendimiento con varios moldes (cada tarjeta lleva todos los bloques en el código, aunque solo se vea uno) | 🔲 Sin medir |

**Primer encargo para Álvaro:** al construir el primer molde real, comprobar si el contenedor del bloque admite Conditional Rendering y anotar el resultado. Es lo que decide cómo se construyen todos los demás.

## 5. Una novedad sin molde

Si una novedad no tiene molde, **no se pinta ningún bloque** y la tarjeta sale en blanco. Por eso el PHP asigna siempre un molde por defecto al crearla, y el campo `molde` debe tener un **valor por defecto** en ACF (el molde estándar).

## 6. Ocultar una novedad (lo hace la app)

Ocultar una novedad la pasa a **borrador** y desaparece de la web. No hay que tocar Avada. Si algo oculto sigue saliendo en producción, puede ser caché: avisa a Carlitos.

---

*Para la mayor gloria de Dios · tiritaito.com*

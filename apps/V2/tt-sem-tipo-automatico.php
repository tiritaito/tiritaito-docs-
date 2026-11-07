/**
 * TT — Tipo de seminario automático · v1.2 · 7 noviembre 2026
 * Pone a cada vídeo de seminario su TIPO (como categoría de la taxonomía `categoria_seminario`)
 * mirando el título, solo si la ficha no tiene ya ninguno. Nunca pisa un tipo puesto a mano.
 * Se puede desactivar sin perder nada.
 *
 * CAMBIOS sobre la versión de Álvaro (v1.0):
 *   - El tipo es una CATEGORÍA, no una casilla: así sirve a la vez para el panel «Más información»
 *     (Post Cards filtrado por tipo) y para la página de vídeos con pestañas por tipo (Show Filters).
 *   - Ya no depende de ACF.
 *   - Relleno de una sola vez para las fichas que YA existen (la v1.0 solo actuaba al guardar).
 *
 * ANTES DE INSTALAR:
 *   1) Crear la taxonomía `categoria_seminario` (no jerárquica, mostrar en REST, unida a `video_seminario`).
 *   2) En los ajustes del importador, fila de Seminarios: taxonomía = categoria_seminario y
 *      «crea categoría» = NO (así el importador no toca estas categorías).
 * INSTALAR (Code Snippets): tipo PHP · «Run snippet everywhere» · pegar SIN la etiqueta <?php.
 * RELLENO INICIAL: con tu usuario administrador, abrir
 *   .../wp-admin/index.php?tt_sem_tipo_relleno=1   y leer el mensaje. Se puede repetir sin riesgo.
 */
if ( ! function_exists( 'tt_sem_tipo_por_titulo' ) ) {

/** Tipos: clave interna => nombre visible de la categoría. Se pueden renombrar luego en WordPress. */
function tt_sem_tipos() {
	return array(
		'general'               => 'General',
		'jovenes'               => 'Jóvenes',
		'matrimonio'            => 'Matrimonios',
		'discipulos_apostoles'  => 'De discípulo a apóstol',
		'intercesion_profetica' => 'Intercesión profética',
		'crecimiento'           => 'Seminario de crecimiento',
	);
}

function tt_sem_tipo_por_titulo( $titulo ) {
	// Sin tildes, en minúsculas y con espacios simples.
	$t = strtolower( remove_accents( (string) $titulo ) );
	$t = trim( preg_replace( '/[^a-z0-9]+/', ' ', $t ) );

	// El orden importa: gana la primera regla que encaja.
	$reglas = array(
		'intercesion_profetica' => '/intercesion profetica/',
		'discipulos_apostoles'  => '/discipulo/',
		'crecimiento'           => '/crecimiento/',
		'matrimonio'            => '/matrimonio/',
		'jovenes'               => '/joven/',
		'general'               => '/\bgeneral\b|vida en el espiritu|\bsve\b/',
	);
	foreach ( $reglas as $valor => $patron ) {
		if ( preg_match( $patron, $t ) ) {
			return $valor;
		}
	}
	return '';
}

/** Pone el tipo si la ficha no tiene ninguno y el título lo permite. Devuelve true si lo puso. */
function tt_sem_tipo_poner( $post_id, $titulo ) {
	$tax = 'categoria_seminario';
	if ( ! taxonomy_exists( $tax ) ) {
		return false;
	}
	$ya = wp_get_object_terms( $post_id, $tax, array( 'fields' => 'ids' ) );
	if ( is_wp_error( $ya ) || ! empty( $ya ) ) {
		return false; // ya tiene tipo: no se toca
	}
	$clave = tt_sem_tipo_por_titulo( $titulo );
	$tipos = tt_sem_tipos();
	if ( '' === $clave || ! isset( $tipos[ $clave ] ) ) {
		return false;
	}
	$term = get_term_by( 'slug', $clave, $tax );
	if ( $term ) {
		$tid = (int) $term->term_id;
	} else {
		$nuevo = wp_insert_term( $tipos[ $clave ], $tax, array( 'slug' => $clave ) );
		if ( is_wp_error( $nuevo ) ) {
			return false;
		}
		$tid = (int) $nuevo['term_id'];
	}
	wp_set_object_terms( $post_id, array( $tid ), $tax, false );
	return true;
}

add_action( 'save_post_video_seminario', function ( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	tt_sem_tipo_poner( $post_id, $post->post_title );
}, 20, 2 );

// Relleno de una sola vez para las fichas que ya existen.
add_action( 'admin_init', function () {
	if ( ! isset( $_GET['tt_sem_tipo_relleno'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! taxonomy_exists( 'categoria_seminario' ) ) {
		wp_die( esc_html( 'Falta crear la taxonomía categoria_seminario.' ), 'Relleno de tipos', array( 'response' => 200, 'back_link' => true ) );
	}
	$ids = get_posts( array(
		'post_type'   => 'video_seminario',
		'post_status' => array( 'publish', 'draft', 'pending', 'private' ),
		'numberposts' => -1,
		'fields'      => 'ids',
	) );
	$con = 0;
	$sin = array();
	foreach ( $ids as $id ) {
		if ( tt_sem_tipo_poner( $id, get_the_title( $id ) ) ) {
			$con++;
		} else {
			$t = wp_get_object_terms( $id, 'categoria_seminario', array( 'fields' => 'ids' ) );
			if ( is_wp_error( $t ) || empty( $t ) ) {
				$sin[] = get_the_title( $id );
			}
		}
	}
	$msg = 'Relleno hecho: ' . $con . ' fichas con tipo nuevo; ' . count( $sin ) . ' siguen sin tipo'
		. ( $sin ? ' (' . implode( ' · ', $sin ) . ')' : '' ) . '. Las que siguen sin tipo se rellenan a mano en la ficha.';
	wp_die( esc_html( $msg ), 'Relleno de tipos', array( 'response' => 200, 'back_link' => true ) );
} );

} // fin del bloque tt_sem_tipo_por_titulo

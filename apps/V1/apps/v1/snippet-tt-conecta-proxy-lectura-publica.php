/**
 * TT Conecta — Proxy de lectura pública
 * Ruta: GET /wp-json/tiritaito/v1/conecta-publico
 * Devuelve SOLO los 4 campos del Devocional que la web ya muestra.
 * Lee directamente con get_option(): sin token en el navegador.
 */
if ( ! function_exists( 'tt_conecta_publico_datos' ) ) {
	function tt_conecta_publico_datos() {
		// Lista cerrada de claves: nunca se expone nada más.
		$claves = array( 'tt_virgen', 'tt_brisa', 'tt_homilia_audio', 'tt_homilia_texto' );
		$salida = array();
		foreach ( $claves as $clave ) {
			$valor = get_option( $clave, '' );
			$salida[ $clave ] = is_string( $valor ) ? $valor : '';
		}
		$resp = new WP_REST_Response( $salida, 200 );
		// Que nadie (navegador, LiteSpeed) guarde una copia antigua.
		$resp->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
		return $resp;
	}
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'tiritaito/v1', '/conecta-publico', array(
		'methods'             => 'GET',
		'callback'            => 'tt_conecta_publico_datos',
		'permission_callback' => '__return_true', // pública a propósito: solo lectura de 4 claves
	) );
} );

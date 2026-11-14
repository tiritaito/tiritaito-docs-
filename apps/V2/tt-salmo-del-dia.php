<?php
/**
 * TT Salmo del día — Recogida diaria
 * v1.0 · 12 noviembre 2026 · Proyecto 2 (Investigación) · SIN PROBAR EN LOCAL
 *
 * QUÉ HACE
 *  1. Pide a Evangelizo (Reader) el título del salmo de HOY (hora de Madrid).
 *  2. Extrae SOLO el número hebreo (el primero de «Salmo 23(22),…»). No guarda texto.
 *  3. Busca en el tipo `episodio` el episodio cuyo título es «Salmo N».
 *  4. Escribe en la Options Page «Salmo del día» (ACF) cuatro casillas:
 *       salmo_hoy_numero · salmo_hoy_titulo · salmo_hoy_audio_url · salmo_hoy_fecha
 *  5. Guarda también los próximos días como colchón por si Evangelizo cae.
 *
 * CÓMO SE DISPARA (sin depender de un único mecanismo)
 *  - Al primer GET de la web de cada día (después de enviar la respuesta).
 *  - WP-Cron cada hora (si el cron funciona en el servidor).
 *  - GET /wp-json/tiritaito/v1/salmo/sync  → para un cron real o un servicio de ping
 *    a las 00:05 hora de Madrid.
 *
 * ENDPOINTS (públicos, sin caché, sin datos sensibles)
 *  - GET /tiritaito/v1/salmo/hoy   → lo que hay guardado ahora
 *  - GET /tiritaito/v1/salmo/sync  → fuerza una pasada (con freno de 10 min)
 *
 * INSTALAR: Code Snippets → Añadir nuevo · título «TT Salmo del día — Recogida diaria»
 *  · tipo PHP · alcance «Run snippet everywhere» · pegar este archivo ENTERO
 *  SIN la primera línea «<?php» · guardar y activar.
 *  Antes tienen que existir las 4 casillas ACF en la Options Page «Salmo del día».
 *
 * AJUSTES: solo en tt_sdd_cfg().
 */

if ( ! function_exists( 'tt_sdd_cfg' ) ) {

	function tt_sdd_cfg() {
		return array(
			'reader_url'    => 'http://feed.evangelizo.org/v2/reader.php',
			'tz'            => 'Europe/Madrid',
			'tipo_episodio' => 'episodio',
			'taxonomia'     => 'canal',
			'canal_slug'    => '',   // vacío = no filtrar por canal; si se rellena, solo episodios de ese canal
			'campo_audio'   => 'ep_audio_url',
			'timeout'       => 6,    // segundos por petición a Evangelizo
			'futuros'       => 7,    // días de adelanto que se guardan (cron / endpoint)
			'futuros_lazy'  => 2,    // días de adelanto en la pasada al visitar la web
			'reintento_s'   => 300,  // espera mínima entre intentos fallidos
			'guardar_dias'  => 10,   // días pasados que se conservan
			'purgar_cache'  => true, // purgar LiteSpeed al cambiar el salmo de hoy
		);
	}

	/* ---------- Fechas ---------- */

	function tt_sdd_hoy() {
		$cfg = tt_sdd_cfg();
		$dt  = new DateTime( 'now', new DateTimeZone( $cfg['tz'] ) );
		return apply_filters( 'tt_sdd_hoy', $dt->format( 'Y-m-d' ) );
	}

	function tt_sdd_fecha_mas( $ymd, $dias ) {
		$cfg = tt_sdd_cfg();
		$dt  = DateTime::createFromFormat( 'Y-m-d', $ymd, new DateTimeZone( $cfg['tz'] ) );
		if ( ! $dt ) {
			return $ymd;
		}
		$dt->modify( ( $dias >= 0 ? '+' : '' ) . (int) $dias . ' day' );
		return $dt->format( 'Y-m-d' );
	}

	/* ---------- Extraer el número del título del Reader ---------- */

	/**
	 * Devuelve array( 'estado' => 'ok'|'sin_salmo'|'error', 'numero' => int|null, 'titulo' => string ).
	 * - ok: título tipo «Salmo 112(111),1-2…» o «Salmo 1,1-2…»  → numero = primer número (hebreo)
	 * - sin_salmo: respuesta válida pero sin salmo (por ejemplo un cántico)
	 * - error: vacío o página de error del Reader (que llega con HTTP 200)
	 */
	function tt_sdd_extraer_numero( $body ) {
		if ( ! is_string( $body ) || '' === trim( $body ) ) {
			return array( 'estado' => 'error', 'numero' => null, 'titulo' => '' );
		}
		$txt = html_entity_decode( strip_tags( $body ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$txt = trim( preg_replace( '/[\s\x{00A0}]+/u', ' ', $txt ) );
		if ( '' === $txt ) {
			return array( 'estado' => 'error', 'numero' => null, 'titulo' => '' );
		}
		$corto = function_exists( 'mb_substr' ) ? mb_substr( $txt, 0, 80 ) : substr( $txt, 0, 80 );
		if ( preg_match( '/wrong param|^error\s*:/iu', $txt ) ) {
			return array( 'estado' => 'error', 'numero' => null, 'titulo' => $corto );
		}
		if ( preg_match( '/^Salmo\s+(\d{1,3})\s*(?:\(\s*\d{1,3}\s*\))?\s*[,.;:]/iu', $txt, $m ) ) {
			$n = (int) $m[1];
			if ( $n >= 1 && $n <= 150 ) {
				return array( 'estado' => 'ok', 'numero' => $n, 'titulo' => $corto );
			}
		}
		return array( 'estado' => 'sin_salmo', 'numero' => null, 'titulo' => $corto );
	}

	function tt_sdd_fetch_dia( $ymd ) {
		$cfg = tt_sdd_cfg();
		$url = add_query_arg(
			array(
				'date'    => str_replace( '-', '', $ymd ),
				'type'    => 'reading_lt',
				'lang'    => 'SP',
				'content' => 'PS',
			),
			$cfg['reader_url']
		);
		$r = wp_remote_get(
			$url,
			array(
				'timeout'     => $cfg['timeout'],
				'redirection' => 3,
				'user-agent'  => 'Tiritaito/1.0 (+https://www.tiritaito.com)',
			)
		);
		if ( is_wp_error( $r ) ) {
			return array( 'estado' => 'error', 'numero' => null, 'titulo' => $r->get_error_message() );
		}
		$code = (int) wp_remote_retrieve_response_code( $r );
		if ( 200 !== $code ) {
			return array( 'estado' => 'error', 'numero' => null, 'titulo' => 'HTTP ' . $code );
		}
		return tt_sdd_extraer_numero( wp_remote_retrieve_body( $r ) );
	}

	/* ---------- Episodio del podcast ---------- */

	function tt_sdd_buscar_episodio( $n ) {
		global $wpdb;
		$cfg = tt_sdd_cfg();
		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status = 'publish' AND post_title REGEXP %s ORDER BY ID ASC LIMIT 10",
				$cfg['tipo_episodio'],
				'^Salmo[[:space:]]+' . (int) $n . '([^0-9]|$)'
			)
		);
		if ( ! is_array( $ids ) ) {
			return null;
		}
		foreach ( $ids as $id ) {
			if ( ! empty( $cfg['canal_slug'] ) && ! has_term( $cfg['canal_slug'], $cfg['taxonomia'], (int) $id ) ) {
				continue;
			}
			$audio = get_post_meta( (int) $id, $cfg['campo_audio'], true );
			if ( is_string( $audio ) && filter_var( $audio, FILTER_VALIDATE_URL ) ) {
				return array( 'id' => (int) $id, 'audio' => $audio );
			}
		}
		return null;
	}

	/* ---------- ACF (Options Page) ---------- */

	function tt_sdd_set( $nombre, $valor ) {
		if ( ! function_exists( 'update_field' ) ) {
			return false;
		}
		$clave = $nombre;
		if ( function_exists( 'acf_get_field' ) ) {
			$f = acf_get_field( $nombre );
			if ( is_array( $f ) && ! empty( $f['key'] ) ) {
				$clave = $f['key']; // escribir por clave para que ACF guarde también la referencia
			}
		}
		update_field( $clave, $valor, 'option' ); // devuelve false si el valor no cambia: no es un error
		return true;
	}

	function tt_sdd_get( $nombre ) {
		return function_exists( 'get_field' ) ? get_field( $nombre, 'option' ) : '';
	}

	/* ---------- Estado y registro ---------- */

	function tt_sdd_estado_get() {
		$e = get_option( 'tt_sdd_estado', array() );
		return is_array( $e ) ? $e : array();
	}

	function tt_sdd_estado_set( $e ) {
		update_option( 'tt_sdd_estado', $e, true ); // autoload: se lee en cada visita
	}

	function tt_sdd_log( &$e, $msg ) {
		if ( empty( $e['log'] ) || ! is_array( $e['log'] ) ) {
			$e['log'] = array();
		}
		$cfg         = tt_sdd_cfg();
		$dt          = new DateTime( 'now', new DateTimeZone( $cfg['tz'] ) );
		$e['log'][]  = $dt->format( 'Y-m-d H:i' ) . ' · ' . $msg;
		$e['log']    = array_slice( $e['log'], -20 );
	}

	/* ---------- Aplicar el salmo de hoy a las casillas ---------- */

	/**
	 * Devuelve true si el día queda resuelto (salmo con audio, o día sin salmo).
	 * Devuelve false si falta el episodio: no se marca como hecho y se reintenta.
	 */
	function tt_sdd_aplicar( $fecha, $n, &$e ) {
		$cfg = tt_sdd_cfg();
		if ( ! function_exists( 'update_field' ) ) {
			tt_sdd_log( $e, 'ERROR: ACF no está disponible (update_field)' );
			return false;
		}
		$numero = '';
		$titulo = '';
		$audio  = '';
		$hecho  = true;
		if ( $n > 0 ) {
			$ep = tt_sdd_buscar_episodio( $n );
			if ( $ep ) {
				$numero = (string) $n;
				$titulo = 'Salmo ' . $n;
				$audio  = $ep['audio'];
			} else {
				$hecho = false;
				tt_sdd_log( $e, 'AVISO: no hay episodio publicado con audio para «Salmo ' . $n . '»' );
			}
		} else {
			tt_sdd_log( $e, 'Día sin salmo en el Reader (por ejemplo un cántico): se vacían las casillas' );
		}
		tt_sdd_set( 'salmo_hoy_numero', $numero );
		tt_sdd_set( 'salmo_hoy_titulo', $titulo );
		tt_sdd_set( 'salmo_hoy_audio_url', $audio );
		tt_sdd_set( 'salmo_hoy_fecha', $fecha );

		$e['numero_hoy'] = $numero;
		if ( $hecho ) {
			$e['fecha_ok'] = $fecha;
			tt_sdd_log( $e, 'Aplicado ' . $fecha . ' → ' . ( '' === $numero ? 'sin salmo' : 'Salmo ' . $numero ) );
			if ( ! empty( $cfg['purgar_cache'] ) ) {
				do_action( 'litespeed_purge_all' );
			}
		}
		return $hecho;
	}

	/* ---------- Pasada principal ---------- */

	function tt_sdd_sync( $max_futuros = 2 ) {
		$cfg  = tt_sdd_cfg();
		$e    = tt_sdd_estado_get();
		$hoy  = tt_sdd_hoy();

		if ( get_transient( 'tt_sdd_lock' ) ) {
			$e['ocupado'] = true;
			return $e;
		}
		set_transient( 'tt_sdd_lock', 1, 90 );
		unset( $e['ocupado'] );

		$e['ultimo_intento'] = time();
		$dias = get_option( 'tt_sdd_dias', array() );
		if ( ! is_array( $dias ) ) {
			$dias = array();
		}

		// 1) Hoy
		if ( ! isset( $dias[ $hoy ] ) ) {
			$r = tt_sdd_fetch_dia( $hoy );
			if ( 'error' === $r['estado'] ) {
				$e['ultimo_error'] = 'Hoy (' . $hoy . '): ' . $r['titulo'];
				tt_sdd_log( $e, 'ERROR al pedir ' . $hoy . ': ' . $r['titulo'] );
			} else {
				$dias[ $hoy ] = array( 'n' => ( 'ok' === $r['estado'] ? (int) $r['numero'] : 0 ), 'ts' => time() );
				if ( 'sin_salmo' === $r['estado'] ) {
					tt_sdd_log( $e, 'Reader sin salmo para ' . $hoy . ': «' . $r['titulo'] . '»' );
				}
				unset( $e['ultimo_error'] );
			}
		}

		// 2) Aplicar hoy
		if ( isset( $dias[ $hoy ] ) && ( empty( $e['fecha_ok'] ) || $e['fecha_ok'] !== $hoy ) ) {
			tt_sdd_aplicar( $hoy, (int) $dias[ $hoy ]['n'], $e );
		}

		// 3) Colchón de días futuros (se para al primer error)
		$limite_futuros = isset( $dias[ $hoy ] ) ? (int) $max_futuros : 0; // si hoy falló, no insistir con los demás
		for ( $i = 1; $i <= $limite_futuros; $i++ ) {
			$d = tt_sdd_fecha_mas( $hoy, $i );
			if ( isset( $dias[ $d ] ) ) {
				continue;
			}
			$r = tt_sdd_fetch_dia( $d );
			if ( 'error' === $r['estado'] ) {
				tt_sdd_log( $e, 'Colchón: error al pedir ' . $d . ': ' . $r['titulo'] );
				break;
			}
			$dias[ $d ] = array( 'n' => ( 'ok' === $r['estado'] ? (int) $r['numero'] : 0 ), 'ts' => time() );
		}

		// 4) Limpiar días antiguos
		$limite = tt_sdd_fecha_mas( $hoy, -1 * (int) $cfg['guardar_dias'] );
		foreach ( array_keys( $dias ) as $d ) {
			if ( $d < $limite ) {
				unset( $dias[ $d ] );
			}
		}
		ksort( $dias );

		update_option( 'tt_sdd_dias', $dias, false );
		$e['ultima_sync'] = time();
		tt_sdd_estado_set( $e );
		delete_transient( 'tt_sdd_lock' );
		return $e;
	}

	/* ---------- Disparador 1: primera visita del día ---------- */

	function tt_sdd_lazy() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
			return;
		}
		if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return;
		}
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' !== $_SERVER['REQUEST_METHOD'] ) {
			return;
		}
		$cfg = tt_sdd_cfg();
		$e   = tt_sdd_estado_get();
		if ( ! empty( $e['fecha_ok'] ) && $e['fecha_ok'] === tt_sdd_hoy() ) {
			return;
		}
		if ( ! empty( $e['ultimo_intento'] ) && ( time() - (int) $e['ultimo_intento'] ) < (int) $cfg['reintento_s'] ) {
			return;
		}
		if ( function_exists( 'fastcgi_finish_request' ) ) {
			fastcgi_finish_request();
		} elseif ( function_exists( 'litespeed_finish_request' ) ) {
			litespeed_finish_request();
		}
		tt_sdd_sync( (int) $cfg['futuros_lazy'] );
	}
	add_action( 'shutdown', 'tt_sdd_lazy', 99 );

	/* ---------- Disparador 2: WP-Cron cada hora ---------- */

	function tt_sdd_cron_run() {
		$cfg = tt_sdd_cfg();
		tt_sdd_sync( (int) $cfg['futuros'] );
	}
	add_action( 'tt_sdd_cron', 'tt_sdd_cron_run' );
	add_action(
		'init',
		function () {
			if ( ! wp_next_scheduled( 'tt_sdd_cron' ) ) {
				wp_schedule_event( time() + 120, 'hourly', 'tt_sdd_cron' );
			}
		}
	);

	/* ---------- Disparador 3 y lectura: endpoints REST ---------- */

	function tt_sdd_rest_hoy() {
		$e   = tt_sdd_estado_get();
		$hoy = tt_sdd_hoy();
		$out = array(
			'hoy'          => $hoy,
			'fecha'        => (string) tt_sdd_get( 'salmo_hoy_fecha' ),
			'numero'       => (string) tt_sdd_get( 'salmo_hoy_numero' ),
			'titulo'       => (string) tt_sdd_get( 'salmo_hoy_titulo' ),
			'audio_url'    => (string) tt_sdd_get( 'salmo_hoy_audio_url' ),
			'al_dia'       => ( ! empty( $e['fecha_ok'] ) && $e['fecha_ok'] === $hoy ),
			'ultimo_error' => isset( $e['ultimo_error'] ) ? $e['ultimo_error'] : '',
		);
		$r = new WP_REST_Response( $out );
		$r->header( 'Cache-Control', 'no-store' );
		return $r;
	}

	function tt_sdd_rest_sync() {
		$cfg = tt_sdd_cfg();
		$e   = tt_sdd_estado_get();
		$hoy = tt_sdd_hoy();
		$reciente = ! empty( $e['ultima_sync'] ) && ( time() - (int) $e['ultima_sync'] ) < 600;
		if ( ! ( ! empty( $e['fecha_ok'] ) && $e['fecha_ok'] === $hoy && $reciente ) ) {
			$e = tt_sdd_sync( (int) $cfg['futuros'] );
		}
		$dias = get_option( 'tt_sdd_dias', array() );
		$out  = array(
			'hoy'          => $hoy,
			'al_dia'       => ( ! empty( $e['fecha_ok'] ) && $e['fecha_ok'] === $hoy ),
			'fecha_ok'     => isset( $e['fecha_ok'] ) ? $e['fecha_ok'] : '',
			'numero_hoy'   => isset( $e['numero_hoy'] ) ? $e['numero_hoy'] : '',
			'ultimo_error' => isset( $e['ultimo_error'] ) ? $e['ultimo_error'] : '',
			'ocupado'      => ! empty( $e['ocupado'] ),
			'dias_guardados' => is_array( $dias ) ? $dias : array(),
			'log'          => isset( $e['log'] ) ? $e['log'] : array(),
		);
		$r = new WP_REST_Response( $out );
		$r->header( 'Cache-Control', 'no-store' );
		return $r;
	}

	add_action(
		'rest_api_init',
		function () {
			register_rest_route(
				'tiritaito/v1',
				'/salmo/hoy',
				array(
					'methods'             => 'GET',
					'callback'            => 'tt_sdd_rest_hoy',
					'permission_callback' => '__return_true',
				)
			);
			register_rest_route(
				'tiritaito/v1',
				'/salmo/sync',
				array(
					'methods'             => 'GET',
					'callback'            => 'tt_sdd_rest_sync',
					'permission_callback' => '__return_true',
				)
			);
		}
	);
}

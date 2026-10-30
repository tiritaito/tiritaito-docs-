/**
 * TT Vídeos YouTube — Importador · v1.0 · 30 octubre 2026
 *
 * QUÉ HACE
 *   Lee playlists de YouTube con la API (clave en la constante TT_YT_API_KEY de wp-config.php)
 *   y mantiene UNA FICHA por vídeo (tipo `cancion`, `viacrucis`, `seminario`…), con sus
 *   categorías (una por playlist, salvo la fila marcada «no crea categoría»).
 *   Sigue TIRITAITO_MUSIC_WEB_NUEVA.md, Secciones 5, 6 y 11.
 *
 * ENTREGA 1: tabla de ajustes, lector, fichas, categorías, orden (menu_order), hash,
 *            modo simulación y pantalla de Estado (casos M0–M16).
 * ENTREGA 2: retiradas R1+R3 (25 días), freno del 20 %, semáforo 10/20 días, endpoint
 *            /tiritaito/v1/videos/estado, noindex + fuera del mapa de páginas y etiquetas
 *            para compartir (casos M17–M24).
 *
 * REGLAS (00_CORE.md)
 *   - Escribe con update_field() por CLAVE de campo (como el importador del podcast).
 *   - Sin AJAX: botones = formularios normales de wp-admin. Nunca se ejecuta al cargar la web pública.
 *   - Funciones con prefijo tt_vid_. No toca el importador del podcast (tt_pod_).
 *
 * INSTALAR (Code Snippets): tipo PHP · alcance «Run snippet everywhere» · pegar SIN la etiqueta <?php.
 * Después: Herramientas → «Vídeos YouTube — Importador» → mirar «Estado» antes de sincronizar.
 *
 * NO HACE (a propósito): no borra nada de WordPress (solo pasa a borrador y, a los 25 días, vacía
 * los datos de YouTube); no recrea fichas en la papelera; no toca fichas pasadas a borrador a mano;
 * no limpia títulos (se importan tal cual, decisión C7).
 */
if ( ! function_exists( 'tt_vid_sync_todo' ) ) {

/* =====================================================================
 * 1. CONFIGURACIÓN Y TABLA DE AJUSTES
 * ===================================================================== */

function tt_vid_cfg() {
	return apply_filters( 'tt_vid_config', array(
		'presupuesto'    => 25,       // segundos de trabajo por pasada
		'espera_cont'    => 60,       // segundos hasta la pasada de continuación
		'hora_diaria'    => '05:15',  // distinta de la del podcast (04:30)
		'freno'          => 0.20,     // fracción máxima del área que se retira de golpe
		'dias_amarillo'  => 10,
		'dias_rojo'      => 20,
		'dias_vaciar'    => 25,       // R3
		'refresco_horas' => 20,       // cada cuánto se reescribe vid_comprobado de una ficha sin cambios
		'max_paginas'    => 20,       // tope de páginas de 50 por playlist
		'kids_por_pasada'=> 100,
		'noindex'        => true,
		'etiquetas'      => true,
	) );
}

function tt_vid_ahora() {
	return (int) apply_filters( 'tt_vid_ahora', time() );
}

/** La clave vive solo en wp-config.php. Nunca se muestra ni se registra. */
function tt_vid_clave() {
	return ( defined( 'TT_YT_API_KEY' ) && is_string( TT_YT_API_KEY ) ) ? trim( TT_YT_API_KEY ) : '';
}

function tt_vid_reglas_orden() {
	return array(
		'fecha_video'   => 'Fecha del vídeo ↓',
		'texto'         => 'Mes y año del texto ↓',
		'titulo_numero' => 'Número del título',
		'posicion'      => 'Posición en YouTube',
	);
}

/** Filas iniciales (Sección 2.6). Las filas sin ID esperan a que Carlitos los pegue. */
function tt_vid_filas_defecto() {
	$t = time();
	$f = function ( $id, $area, $nombre, $crea, $ocultos, $orden, $cpt, $tax, $activa ) use ( $t ) {
		return array( 'id' => $id, 'area' => $area, 'nombre' => $nombre, 'crea_cat' => $crea, 'ocultos' => $ocultos,
			'orden' => $orden, 'cpt' => $cpt, 'tax' => $tax, 'activa' => $activa, 'creada' => $t );
	};
	return array(
		$f( 'PLX21N4KPkRTRJOH4-uPMq2YS62-l-hePc', 'musica', 'Todas', false, false, 'fecha_video', 'cancion', 'categoria_musica', true ),
		$f( 'PLX21N4KPkRTStJJCgbOy05SGymdCgtA0m', 'musica', 'Virgen María', true, false, 'fecha_video', 'cancion', 'categoria_musica', true ),
		$f( 'PLX21N4KPkRTQ1Vh96bifV73J78KfYXHSD', 'musica', 'San José', true, false, 'fecha_video', 'cancion', 'categoria_musica', true ),
		$f( '', 'musica', 'Espíritu Santo', true, false, 'fecha_video', 'cancion', 'categoria_musica', false ),
		$f( '', 'musica', 'Navidad', true, false, 'fecha_video', 'cancion', 'categoria_musica', false ),
		// Vía Crucis y Seminarios: tipos y taxonomía aún sin crear en Local → filas inactivas.
		$f( 'PLX21N4KPkRTTz6ILAWvbk1a7SBywzw_3O', 'viacrucis', 'Con Jesús hacia la Cruz', true, false, 'titulo_numero', 'viacrucis', 'categoria_viacrucis', false ),
		$f( 'PLX21N4KPkRTR9zSSsTdq85v3Oc4qYecP1', 'viacrucis', 'Vía Crucis de la Divina Misericordia 2024', true, false, 'titulo_numero', 'viacrucis', 'categoria_viacrucis', false ),
		$f( 'PLX21N4KPkRTR8_vFAGAlKswgbV7f_EF4J', 'seminarios', 'Seminarios', false, true, 'texto', 'seminario', '', false ),
	);
}

/** Filas guardadas (o las de defecto). Con $guardar = true, las de defecto se guardan la primera vez. */
function tt_vid_filas( $guardar = false ) {
	$f = get_option( 'tt_video_importador_filas', null );
	if ( ! is_array( $f ) ) {
		$f = tt_vid_filas_defecto();
		if ( $guardar ) {
			add_option( 'tt_video_importador_filas', $f, '', 'no' );
		}
	}
	return $f;
}

function tt_vid_filas_activas() {
	$out = array();
	foreach ( tt_vid_filas() as $f ) {
		if ( ! empty( $f['activa'] ) && '' !== $f['id'] ) {
			$out[] = $f;
		}
	}
	return $out;
}

/** Acepta el ID suelto o una dirección con ?list=… */
function tt_vid_id_playlist( $s ) {
	$s = trim( (string) $s );
	if ( preg_match( '/[?&]list=([A-Za-z0-9_-]+)/', $s, $m ) ) {
		return $m[1];
	}
	return preg_match( '/^[A-Za-z0-9_-]+$/', $s ) ? $s : '';
}

function tt_vid_limpiar_filas( $entrada, $previas ) {
	$reglas = tt_vid_reglas_orden();
	$salida = array();
	$vistos = array();
	foreach ( (array) $entrada as $e ) {
		if ( ! empty( $e['borrar'] ) ) {
			continue;
		}
		$id     = tt_vid_id_playlist( isset( $e['id'] ) ? wp_unslash( $e['id'] ) : '' );
		$nombre = sanitize_text_field( isset( $e['nombre'] ) ? wp_unslash( $e['nombre'] ) : '' );
		if ( '' === $id && '' === $nombre ) {
			continue;
		}
		if ( '' !== $id ) {
			if ( isset( $vistos[ $id ] ) ) {
				continue;
			}
			$vistos[ $id ] = true;
		}
		$orden = isset( $e['orden'] ) ? sanitize_key( $e['orden'] ) : 'fecha_video';
		$salida[] = array(
			'id'       => $id,
			'area'     => sanitize_key( isset( $e['area'] ) ? $e['area'] : '' ),
			'nombre'   => $nombre,
			'crea_cat' => ! empty( $e['crea_cat'] ),
			'ocultos'  => ! empty( $e['ocultos'] ),
			'orden'    => isset( $reglas[ $orden ] ) ? $orden : 'fecha_video',
			'cpt'      => sanitize_key( isset( $e['cpt'] ) ? $e['cpt'] : '' ),
			'tax'      => sanitize_key( isset( $e['tax'] ) ? $e['tax'] : '' ),
			'activa'   => ! empty( $e['activa'] ),
			'creada'   => ! empty( $e['creada'] ) ? (int) $e['creada'] : time(),
		);
	}
	return $salida;
}

/** Tipos de contenido de todas las filas (para noindex, mapa de páginas y etiquetas). */
function tt_vid_cpts() {
	$c = array();
	foreach ( tt_vid_filas() as $f ) {
		if ( '' !== $f['cpt'] ) {
			$c[ $f['cpt'] ] = $f['cpt'];
		}
	}
	return array_values( $c );
}

/* =====================================================================
 * 2. AYUDAS PEQUEÑAS (puras: no tocan la base de datos)
 * ===================================================================== */

/** Para detectar «posible duplicado»: título sin sufijo, sin tildes ni mayúsculas. Solo para avisar. */
function tt_vid_titulo_base( $t ) {
	$t = preg_split( '/\s+[\/|\-–—]\s+/u', (string) $t );
	$t = isset( $t[0] ) ? $t[0] : '';
	$t = strtolower( remove_accents( $t ) );
	return trim( preg_replace( '/[^a-z0-9]+/', ' ', $t ) );
}

function tt_vid_romano( $s ) {
	$v   = array( 'I' => 1, 'V' => 5, 'X' => 10, 'L' => 50 );
	$tot = 0;
	$n   = strlen( $s );
	for ( $i = 0; $i < $n; $i++ ) {
		$a = isset( $v[ $s[ $i ] ] ) ? $v[ $s[ $i ] ] : 0;
		$b = ( $i + 1 < $n && isset( $v[ $s[ $i + 1 ] ] ) ) ? $v[ $s[ $i + 1 ] ] : 0;
		$tot += ( $a < $b ) ? -$a : $a;
	}
	return $tot;
}

/** Número de estación/día en el título: «(XIII)», «7º», «Día 3». «Novena» NO cuenta. 0 = sin número. */
function tt_vid_numero_titulo( $t ) {
	$t = (string) $t;
	if ( preg_match( '/\(\s*([IVXL]{1,6})\s*\)/u', $t, $m ) ) {
		$n = tt_vid_romano( $m[1] );
		if ( $n > 0 && $n <= 40 ) {
			return $n;
		}
	}
	if ( preg_match( '/(?<!\d)(\d{1,2})\s*[ºª°]/u', $t, $m ) ) {
		return (int) $m[1];
	}
	if ( preg_match( '/\b(?:d[ií]a|estaci[oó]n)\s+(\d{1,2})\b/iu', $t, $m ) ) {
		return (int) $m[1];
	}
	return 0;
}

/** «Mes año» de la primera línea de la descripción o, si no, del título. Devuelve array(año, mes) o null. */
function tt_vid_fecha_texto( $descripcion, $titulo ) {
	$meses = array( 'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4, 'mayo' => 5, 'junio' => 6, 'julio' => 7,
		'agosto' => 8, 'septiembre' => 9, 'setiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12 );
	$lineas = preg_split( '/\R/u', trim( (string) $descripcion ) );
	$primera = isset( $lineas[0] ) ? $lineas[0] : '';
	foreach ( array( $primera, (string) $titulo ) as $txt ) {
		if ( preg_match( '/\b(' . implode( '|', array_keys( $meses ) ) . ')\s+(?:de\s+|del\s+)?(20\d{2})\b/iu', $txt, $m ) ) {
			return array( (int) $m[2], $meses[ strtolower( $m[1] ) ] );
		}
	}
	return null;
}

/**
 * Número de orden (menu_order, ascendente) que depende SOLO de los datos de la propia ficha:
 * añadir una canción nueva no obliga a reescribir las demás.
 */
function tt_vid_clave_orden( $regla, $d ) {
	switch ( $regla ) {
		case 'texto':
			$anio = $d['anio'];
			$mes  = $d['mes'];
			if ( ! $anio ) {
				$anio = (int) gmdate( 'Y', $d['ts'] );
				$mes  = (int) gmdate( 'n', $d['ts'] );
			}
			$anio = max( 2000, min( 2099, $anio ) );
			$mes  = max( 1, min( 12, (int) $mes ) );
			$dias = max( 0, 500000 - (int) floor( ( $d['ts'] - 946684800 ) / 86400 ) ); // desempate: vídeo más reciente primero
			return ( ( 2100 - $anio ) * 12 + ( 12 - $mes ) ) * 1000000 + min( 999999, $dias );
		case 'titulo_numero':
			return $d['numero'] > 0 ? 10 + $d['numero'] * 10 : min( 9, (int) $d['posicion'] );
		case 'posicion':
			return 10 + (int) $d['posicion'];
		case 'fecha_video':
		default:
			return max( 0, 2147000000 - (int) $d['ts'] );
	}
}

function tt_vid_hash( $d ) {
	return md5( wp_json_encode( array( $d['titulo'], $d['fecha'], $d['miniatura'], $d['anio'], $d['mes'], $d['numero'], $d['orden'], $d['area'] ) ) );
}

function tt_vid_ts_texto( $v ) {
	$v = trim( (string) $v );
	return '' === $v ? 0 : (int) strtotime( $v . ' UTC' );
}

/* =====================================================================
 * 3. API DE YOUTUBE (lector). Una caída nunca borra nada.
 * ===================================================================== */

function tt_vid_api_get( $endpoint, $args ) {
	$clave = tt_vid_clave();
	if ( '' === $clave ) {
		return array( 'ok' => false, 'tipo' => 'clave', 'error' => 'Falta la constante TT_YT_API_KEY en wp-config.php.' );
	}
	if ( 'cuota' === get_option( 'tt_video_importador_test' ) ) { // prueba M4 (botón de Estado)
		return array( 'ok' => false, 'tipo' => 'cuota', 'error' => 'Cuota diaria de YouTube agotada (respuesta simulada).' );
	}
	$url = add_query_arg( array_merge( $args, array( 'key' => $clave ) ), 'https://www.googleapis.com/youtube/v3/' . $endpoint );
	$r   = wp_remote_get( $url, array( 'timeout' => 20 ) );
	if ( is_wp_error( $r ) ) {
		return array( 'ok' => false, 'tipo' => 'red', 'error' => 'No se pudo contactar con YouTube: ' . str_replace( $clave, '***', $r->get_error_message() ) );
	}
	$code = (int) wp_remote_retrieve_response_code( $r );
	$j    = json_decode( wp_remote_retrieve_body( $r ), true );
	if ( 200 === $code && is_array( $j ) ) {
		return array( 'ok' => true, 'json' => $j );
	}
	$razon = isset( $j['error']['errors'][0]['reason'] ) ? (string) $j['error']['errors'][0]['reason'] : '';
	$msg   = isset( $j['error']['message'] ) ? (string) $j['error']['message'] : '';
	if ( in_array( $razon, array( 'quotaExceeded', 'dailyLimitExceeded', 'rateLimitExceeded' ), true ) ) {
		return array( 'ok' => false, 'tipo' => 'cuota', 'error' => 'Cuota diaria de YouTube agotada.' );
	}
	if ( 'playlistNotFound' === $razon || 404 === $code ) {
		return array( 'ok' => false, 'tipo' => 'no_existe', 'error' => 'YouTube no encuentra esa playlist (¿ID mal copiado?).' );
	}
	if ( in_array( $razon, array( 'playlistForbidden', 'forbidden' ), true ) ) {
		return array( 'ok' => false, 'tipo' => 'privada', 'error' => 'La playlist es privada o no se puede leer con una clave de API.' );
	}
	if ( 'keyInvalid' === $razon || false !== stripos( $msg, 'api key' ) || in_array( $razon, array( 'accessNotConfigured', 'ipRefererBlocked' ), true ) ) {
		return array( 'ok' => false, 'tipo' => 'clave', 'error' => 'La clave de API no es válida o no tiene activada «YouTube Data API v3».' );
	}
	return array( 'ok' => false, 'tipo' => 'api', 'error' => 'YouTube respondió con error ' . $code . ( '' !== $razon ? ' (' . $razon . ')' : '' ) . '.' );
}

function tt_vid_miniatura( $it_snippet, $vid ) {
	foreach ( array( 'medium', 'high', 'default' ) as $k ) {
		if ( ! empty( $it_snippet['thumbnails'][ $k ]['url'] ) ) {
			return esc_url_raw( $it_snippet['thumbnails'][ $k ]['url'] );
		}
	}
	return 'https://i.ytimg.com/vi/' . $vid . '/mqdefault.jpg';
}

/**
 * Lee una playlist ENTERA (todas las páginas). Si una página falla, TODA la playlist cuenta como fallida
 * (regla 3b: nunca se retira nada con una lectura incompleta).
 * Devuelve array( ok, error, tipo, items[vid => datos], avisos[] )
 */
function tt_vid_leer_playlist( $pid, $ocultos ) {
	$cfg    = tt_vid_cfg();
	$items  = array();
	$avisos = array();
	$token  = '';
	$pags   = 0;
	$nodisp = 0;
	$omit   = 0;
	do {
		$args = array( 'part' => 'snippet,contentDetails,status', 'maxResults' => 50, 'playlistId' => $pid );
		if ( '' !== $token ) {
			$args['pageToken'] = $token;
		}
		$r = tt_vid_api_get( 'playlistItems', $args );
		if ( ! $r['ok'] ) {
			return array( 'ok' => false, 'tipo' => $r['tipo'], 'error' => $r['error'], 'items' => array(), 'avisos' => array() );
		}
		foreach ( (array) ( isset( $r['json']['items'] ) ? $r['json']['items'] : array() ) as $it ) {
			$sn  = isset( $it['snippet'] ) ? $it['snippet'] : array();
			$cd  = isset( $it['contentDetails'] ) ? $it['contentDetails'] : array();
			$st  = isset( $it['status'] ) ? $it['status'] : array();
			$vid = isset( $cd['videoId'] ) ? $cd['videoId'] : ( isset( $sn['resourceId']['videoId'] ) ? $sn['resourceId']['videoId'] : '' );
			if ( ! preg_match( '/^[A-Za-z0-9_-]{11}$/', $vid ) ) {
				continue;
			}
			$tit  = trim( isset( $sn['title'] ) ? (string) $sn['title'] : '' );
			$priv = isset( $st['privacyStatus'] ) ? $st['privacyStatus'] : 'public';
			if ( 'private' === $priv || in_array( $tit, array( 'Private video', 'Deleted video', 'Vídeo privado', 'Vídeo eliminado' ), true ) ) {
				$nodisp++;
				continue;
			}
			if ( 'unlisted' === $priv && ! $ocultos ) {
				$omit++;
				continue;
			}
			if ( isset( $items[ $vid ] ) ) {
				$avisos[] = 'ID repetido en la misma playlist, se ignora: «' . $tit . '» (' . $vid . ')';
				continue;
			}
			$fecha = isset( $cd['videoPublishedAt'] ) ? $cd['videoPublishedAt'] : '';
			if ( '' === $fecha ) {
				$fecha    = isset( $sn['publishedAt'] ) ? $sn['publishedAt'] : '';
				$avisos[] = 'Sin fecha de vídeo, se usa la de añadido a la playlist: «' . $tit . '»';
			}
			$ts = $fecha ? (int) strtotime( $fecha ) : 0;
			$items[ $vid ] = array(
				'vid'       => $vid,
				'titulo'    => '' !== $tit ? $tit : 'Sin título',
				'descr'     => isset( $sn['description'] ) ? (string) $sn['description'] : '',
				'ts'        => $ts,
				'miniatura' => tt_vid_miniatura( $sn, $vid ),
				'posicion'  => isset( $sn['position'] ) ? (int) $sn['position'] : 0,
			);
		}
		$token = isset( $r['json']['nextPageToken'] ) ? (string) $r['json']['nextPageToken'] : '';
		$pags++;
	} while ( '' !== $token && $pags < (int) $cfg['max_paginas'] );

	if ( '' !== $token ) {
		return array( 'ok' => false, 'tipo' => 'api', 'error' => 'La playlist tiene más de ' . $cfg['max_paginas'] . ' páginas: lectura incompleta.', 'items' => array(), 'avisos' => array() );
	}
	if ( ! $items ) {
		return array( 'ok' => false, 'tipo' => 'vacia', 'error' => 'La playlist salió vacía: no se toca nada.', 'items' => array(), 'avisos' => array() );
	}
	if ( $nodisp ) {
		$avisos[] = $nodisp . ' vídeo(s) privados o eliminados excluidos.';
	}
	if ( $omit ) {
		$avisos[] = $omit . ' vídeo(s) no listados omitidos (esta fila no incluye ocultos).';
	}
	return array( 'ok' => true, 'error' => '', 'tipo' => '', 'items' => $items, 'avisos' => $avisos );
}

/** Datos de vídeos concretos (R1 y «Made for Kids»). Hasta 50 por llamada, 1 unidad. */
function tt_vid_videos_list( $ids, $partes ) {
	$out = array();
	foreach ( array_chunk( array_values( array_unique( $ids ) ), 50 ) as $lote ) {
		$r = tt_vid_api_get( 'videos', array( 'part' => $partes, 'id' => implode( ',', $lote ), 'maxResults' => 50 ) );
		if ( ! $r['ok'] ) {
			return array( 'ok' => false, 'error' => $r['error'], 'items' => $out );
		}
		foreach ( (array) ( isset( $r['json']['items'] ) ? $r['json']['items'] : array() ) as $v ) {
			if ( ! empty( $v['id'] ) ) {
				$out[ $v['id'] ] = $v;
			}
		}
	}
	return array( 'ok' => true, 'error' => '', 'items' => $out );
}

/* =====================================================================
 * 4. ACF: escribir siempre por CLAVE de campo (copiado del importador del podcast)
 * ===================================================================== */

function tt_vid_campos_requeridos() {
	return array(
		'vid_video_id'      => array( 'text' ),
		'vid_youtube_url'   => array( 'text' ),
		'vid_miniatura_url' => array( 'url' ),
		'vid_fecha_video'   => array( 'text' ),
		'vid_comprobado'    => array( 'text' ),
		'vid_hash'          => array( 'text' ),
	);
}

function tt_vid_campos_opcionales() {
	return array(
		'vid_kids'    => array( 'text' ),
		'sem_anio'    => array( 'number' ),
		'sem_mes'     => array( 'number' ),
		'vcr_numero'  => array( 'number' ),
	);
}

function tt_vid_campos_acf( $reiniciar = false ) {
	static $mapa = null;
	if ( $reiniciar ) {
		$mapa = null;
	}
	if ( null === $mapa ) {
		$mapa = array();
		if ( function_exists( 'acf_get_field_groups' ) && function_exists( 'acf_get_fields' ) ) {
			foreach ( (array) acf_get_field_groups() as $g ) {
				foreach ( (array) acf_get_fields( $g ) as $f ) {
					if ( ! empty( $f['name'] ) && ! isset( $mapa[ $f['name'] ] ) ) {
						$mapa[ $f['name'] ] = array( 'key' => $f['key'], 'type' => $f['type'] );
					}
				}
			}
		}
	}
	return $mapa;
}

/** Escribe por clave. Si la casilla no existe en ACF no escribe nada (las obligatorias ya se comprueban antes). */
function tt_vid_uf( $nombre, $valor, $post_id ) {
	$mapa = tt_vid_campos_acf();
	if ( ! isset( $mapa[ $nombre ] ) ) {
		return false;
	}
	return update_field( $mapa[ $nombre ]['key'], $valor, $post_id );
}

function tt_vid_grupo_en_tipo( $cpt ) {
	if ( ! function_exists( 'acf_get_field_groups' ) || ! function_exists( 'acf_get_fields' ) ) {
		return false;
	}
	foreach ( (array) acf_get_field_groups( array( 'post_type' => $cpt ) ) as $g ) {
		foreach ( (array) acf_get_fields( $g ) as $f ) {
			if ( isset( $f['name'] ) && 'vid_video_id' === $f['name'] ) {
				return true;
			}
		}
	}
	return false;
}

/** Problemas que impiden sincronizar un área (lista vacía = todo bien). Sirve para Estado y para la pasada. */
function tt_vid_problemas_area( $cpt, $tax, $necesita_tax ) {
	$p = array();
	if ( ! function_exists( 'update_field' ) ) {
		$p[] = 'ACF no está activo.';
	}
	if ( '' === $cpt || ! post_type_exists( $cpt ) ) {
		$p[] = 'No existe el tipo de contenido «' . $cpt . '».';
		return $p;
	}
	$o = get_post_type_object( $cpt );
	if ( empty( $o->public ) ) {
		$p[] = 'El tipo «' . $cpt . '» no es público (Post Cards y las fichas compartibles lo necesitan).';
	}
	if ( $necesita_tax ) {
		if ( '' === $tax || ! taxonomy_exists( $tax ) ) {
			$p[] = 'No existe la taxonomía «' . $tax . '».';
		} elseif ( ! is_object_in_taxonomy( $cpt, $tax ) ) {
			$p[] = 'La taxonomía «' . $tax . '» no está unida al tipo «' . $cpt . '».';
		}
	}
	$campos = tt_vid_campos_acf();
	foreach ( tt_vid_campos_requeridos() as $n => $tipos ) {
		if ( ! isset( $campos[ $n ] ) ) {
			$p[] = 'Falta la casilla «' . $n . '».';
		} elseif ( ! in_array( $campos[ $n ]['type'], $tipos, true ) ) {
			$p[] = 'La casilla «' . $n . '» es de tipo «' . $campos[ $n ]['type'] . '» y debería ser «' . implode( '» o «', $tipos ) . '».';
		}
	}
	if ( ! tt_vid_grupo_en_tipo( $cpt ) ) {
		$p[] = 'El grupo «TT — Vídeo YouTube» no está asignado al tipo «' . $cpt . '».';
	}
	return $p;
}

/* =====================================================================
 * 5. BASE DE DATOS
 * ===================================================================== */

/** vid => array( id, hash, estado, area, comprobado, retirada, vaciada, kids ) de las fichas de un tipo (incluida la papelera). */
function tt_vid_db_mapa( $cpt ) {
	global $wpdb;
	$filas = $wpdb->get_results( $wpdb->prepare(
		"SELECT p.ID AS id, p.post_status AS estado, v.meta_value AS vid, h.meta_value AS hash, a.meta_value AS area,
		        c.meta_value AS comprobado, r.meta_value AS retirada, x.meta_value AS vaciada, k.meta_value AS kids
		   FROM {$wpdb->posts} p
		  INNER JOIN {$wpdb->postmeta} v ON v.post_id = p.ID AND v.meta_key = 'vid_video_id' AND v.meta_value <> ''
		   LEFT JOIN {$wpdb->postmeta} h ON h.post_id = p.ID AND h.meta_key = 'vid_hash'
		   LEFT JOIN {$wpdb->postmeta} a ON a.post_id = p.ID AND a.meta_key = '_tt_vid_area'
		   LEFT JOIN {$wpdb->postmeta} c ON c.post_id = p.ID AND c.meta_key = 'vid_comprobado'
		   LEFT JOIN {$wpdb->postmeta} r ON r.post_id = p.ID AND r.meta_key = '_tt_vid_retirada'
		   LEFT JOIN {$wpdb->postmeta} x ON x.post_id = p.ID AND x.meta_key = '_tt_vid_vaciada'
		   LEFT JOIN {$wpdb->postmeta} k ON k.post_id = p.ID AND k.meta_key = 'vid_kids'
		  WHERE p.post_type = %s AND p.post_status <> 'auto-draft'
		  ORDER BY p.ID ASC",
		$cpt
	), ARRAY_A );
	$mapa = array();
	foreach ( (array) $filas as $f ) {
		if ( isset( $mapa[ $f['vid'] ] ) ) {
			continue; // si hubiera dos fichas con el mismo vídeo, manda la más antigua
		}
		$mapa[ $f['vid'] ] = array(
			'id' => (int) $f['id'], 'hash' => (string) $f['hash'], 'estado' => $f['estado'], 'area' => (string) $f['area'],
			'comprobado' => (string) $f['comprobado'], 'retirada' => (string) $f['retirada'], 'vaciada' => (string) $f['vaciada'],
			'kids' => (string) $f['kids'],
		);
	}
	return $mapa;
}

function tt_vid_db_slug_existe( $slug, $cpt ) {
	global $wpdb;
	return (bool) $wpdb->get_var( $wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_name = %s AND post_status <> 'auto-draft' LIMIT 1",
		$cpt, $slug
	) );
}

function tt_vid_slug( $titulo, $vid, $cpt ) {
	$base = sanitize_title( $titulo );
	$sufijo = strtolower( $vid );
	if ( '' === $base || false !== strpos( $base, '%' ) ) {
		return 'video-' . $sufijo;
	}
	return tt_vid_db_slug_existe( $base, $cpt ) ? $base . '-' . $sufijo : $base;
}

/* =====================================================================
 * 6. REGISTRO Y BLOQUEO (copiados del podcast, con otras claves)
 * ===================================================================== */

function tt_vid_log_get() {
	$l = get_option( 'tt_video_importador_log' );
	return is_array( $l ) ? $l : array();
}

function tt_vid_log_base( $log ) {
	foreach ( array( 'filas', 'areas', 'historial', 'ultima' ) as $k ) {
		if ( ! isset( $log[ $k ] ) || ! is_array( $log[ $k ] ) ) {
			$log[ $k ] = array();
		}
	}
	return $log;
}

/** Guarda el resultado de una fila. `ultimo_ok` solo avanza si esta pasada la leyó bien. */
function tt_vid_log_fila( $pid, $datos ) {
	$log  = tt_vid_log_base( tt_vid_log_get() );
	$prev = isset( $log['filas'][ $pid ] ) ? $log['filas'][ $pid ] : array();
	$datos['ultimo_ok'] = ! empty( $datos['ok'] ) ? tt_vid_ahora() : ( isset( $prev['ultimo_ok'] ) ? (int) $prev['ultimo_ok'] : 0 );
	$log['filas'][ $pid ] = $datos;
	update_option( 'tt_video_importador_log', $log, false );
}

function tt_vid_log_area( $area, $datos ) {
	$log = tt_vid_log_base( tt_vid_log_get() );
	$log['areas'][ $area ] = array_merge( isset( $log['areas'][ $area ] ) ? $log['areas'][ $area ] : array(), $datos );
	update_option( 'tt_video_importador_log', $log, false );
}

function tt_vid_log_ejecucion( $res ) {
	$log = tt_vid_log_base( tt_vid_log_get() );
	$log['ultima'] = $res;
	array_unshift( $log['historial'], array(
		'fin' => $res['fin'], 'origen' => $res['origen'], 'estado' => $res['estado'], 'creadas' => $res['creadas'],
		'actualizadas' => $res['actualizadas'], 'retiradas' => $res['retiradas'], 'errores' => $res['errores'],
	) );
	$log['historial'] = array_slice( $log['historial'], 0, 20 );
	update_option( 'tt_video_importador_log', $log, false );
}

function tt_vid_lock_tomar() {
	$ahora = time();
	if ( add_option( 'tt_video_importador_lock', $ahora, '', 'no' ) ) {
		return true;
	}
	if ( $ahora - (int) get_option( 'tt_video_importador_lock' ) > 600 ) {
		update_option( 'tt_video_importador_lock', $ahora, false );
		return true;
	}
	return false;
}

function tt_vid_lock_soltar() {
	delete_option( 'tt_video_importador_lock' );
}

/* =====================================================================
 * 7. CATEGORÍAS (identidad = ID de la playlist, no el nombre)
 * ===================================================================== */

function tt_vid_termino_de_playlist( $tax, $pid ) {
	$t = get_terms( array(
		'taxonomy' => $tax, 'hide_empty' => false, 'number' => 1, 'fields' => 'ids',
		'meta_query' => array( array( 'key' => '_tt_vid_playlist', 'value' => $pid ) ),
	) );
	return ( is_array( $t ) && $t ) ? (int) $t[0] : 0;
}

/** Busca o crea la categoría de una fila; si el nombre visible cambió, renombra el MISMO término. */
function tt_vid_asegurar_termino( $fila, $tax, $sim, &$r ) {
	$id = tt_vid_termino_de_playlist( $tax, $fila['id'] );
	if ( $id ) {
		$t = get_term( $id, $tax );
		if ( $t && ! is_wp_error( $t ) && $t->name !== $fila['nombre'] ) {
			if ( $sim ) {
				$r['plan'][] = 'RENOMBRAR categoría · «' . $t->name . '» → «' . $fila['nombre'] . '»';
			} else {
				wp_update_term( $id, $tax, array( 'name' => $fila['nombre'] ) );
			}
		}
		return $id;
	}
	if ( $sim ) {
		$r['plan'][] = 'CREAR categoría · «' . $fila['nombre'] . '»';
		return 0;
	}
	$ex = term_exists( $fila['nombre'], $tax );
	if ( $ex ) {
		$id = (int) ( is_array( $ex ) ? $ex['term_id'] : $ex ); // se adopta una categoría con ese nombre
	} else {
		$t = wp_insert_term( $fila['nombre'], $tax );
		if ( is_wp_error( $t ) ) {
			$e  = $t->get_error_data( 'term_exists' );
			$id = $e ? (int) $e : 0;
		} else {
			$id = (int) $t['term_id'];
		}
	}
	if ( $id ) {
		update_term_meta( $id, '_tt_vid_playlist', $fila['id'] );
	}
	return $id;
}

/* =====================================================================
 * 8. ESCRITURA DE FICHAS
 * ===================================================================== */

function tt_vid_escribir_campos( $id, $d, $ahora ) {
	tt_vid_uf( 'vid_video_id', $d['vid'], $id );
	tt_vid_uf( 'vid_youtube_url', $d['url'], $id );
	tt_vid_uf( 'vid_miniatura_url', $d['miniatura'], $id );
	tt_vid_uf( 'vid_fecha_video', $d['fecha'], $id );
	tt_vid_uf( 'vid_comprobado', gmdate( 'Y-m-d H:i:s', $ahora ), $id );
	if ( null !== $d['anio'] ) {
		tt_vid_uf( 'sem_anio', (int) $d['anio'], $id );
		tt_vid_uf( 'sem_mes', (int) $d['mes'], $id );
	}
	if ( ! empty( $d['numero'] ) ) {
		tt_vid_uf( 'vcr_numero', (int) $d['numero'], $id );
	}
	tt_vid_uf( 'vid_hash', $d['hash'], $id ); // el hash va el último: si algo falla antes, se reintenta
}

/** Crea o actualiza una ficha. Devuelve array( resultado, mensaje ). */
function tt_vid_upsert( $d, $ctx, $ex ) {
	$cfg   = tt_vid_cfg();
	$sim   = $ctx['sim'];
	$ahora = $ctx['ahora'];
	$gmt   = $d['ts'] ? gmdate( 'Y-m-d H:i:s', $d['ts'] ) : gmdate( 'Y-m-d H:i:s', $ahora );
	$local = get_date_from_gmt( $gmt );
	$res   = 'sin_cambios';
	$id    = 0;

	if ( $ex ) {
		$id     = (int) $ex['id'];
		$vuelve = ( 'draft' === $ex['estado'] && '' !== $ex['retirada'] );
		$cambia = ( $ex['hash'] !== $d['hash'] ) || $vuelve || '' !== $ex['vaciada'];
		if ( $cambia ) {
			if ( ! $sim ) {
				$r = wp_update_post( wp_slash( array(
					'ID' => $id, 'post_title' => $d['titulo'], 'post_status' => 'publish',
					'post_date' => $local, 'post_date_gmt' => $gmt, 'menu_order' => $d['orden'],
				) ), true );
				if ( is_wp_error( $r ) || ! $r ) {
					return array( 'error', 'No se pudo actualizar «' . $d['titulo'] . '»' );
				}
				tt_vid_escribir_campos( $id, $d, $ahora );
				delete_post_meta( $id, '_tt_vid_retirada' );
				delete_post_meta( $id, '_tt_vid_vaciada' );
			}
			$res = $vuelve ? 'republicada' : 'actualizada';
		} elseif ( $ahora - tt_vid_ts_texto( $ex['comprobado'] ) >= (int) $cfg['refresco_horas'] * 3600 ) {
			if ( ! $sim ) {
				tt_vid_uf( 'vid_comprobado', gmdate( 'Y-m-d H:i:s', $ahora ), $id );
			}
			$res = 'refrescada';
		}
		if ( '' === $ex['area'] && ! $sim ) {
			update_post_meta( $id, '_tt_vid_area', $ctx['area'] );
		}
	} else {
		if ( $sim ) {
			return array( 'creada', '' );
		}
		$id = wp_insert_post( wp_slash( array(
			'post_type' => $ctx['cpt'], 'post_status' => 'publish', 'post_title' => $d['titulo'],
			'post_name' => tt_vid_slug( $d['titulo'], $d['vid'], $ctx['cpt'] ),
			'post_date' => $local, 'post_date_gmt' => $gmt, 'menu_order' => $d['orden'],
		) ), true );
		if ( is_wp_error( $id ) || ! $id ) {
			return array( 'error', 'No se pudo crear «' . $d['titulo'] . '»' );
		}
		$id = (int) $id;
		update_post_meta( $id, '_tt_vid_area', $ctx['area'] );
		tt_vid_escribir_campos( $id, $d, $ahora );
		$res = 'creada';
	}

	if ( $sim || ! $id ) {
		return array( $res, '' );
	}

	// Categorías: solo se tocan las que gobierna YouTube (las de filas leídas bien en esta pasada).
	$cambio_cat = false;
	if ( $ctx['tax'] ) {
		$deseados = array();
		foreach ( $d['playlists'] as $pid ) {
			if ( ! empty( $ctx['term_por_pid'][ $pid ] ) ) {
				$deseados[] = (int) $ctx['term_por_pid'][ $pid ];
			}
		}
		$act = wp_get_object_terms( $id, $ctx['tax'], array( 'fields' => 'ids' ) );
		$act = is_wp_error( $act ) ? array() : array_map( 'intval', $act );
		$nuevos = array_values( array_unique( array_merge( array_diff( $act, $ctx['ok_terms'] ), $deseados ) ) );
		sort( $nuevos );
		sort( $act );
		if ( $nuevos !== $act ) {
			wp_set_object_terms( $id, $nuevos, $ctx['tax'], false );
			$cambio_cat = true;
		}
	}
	$pl = $d['playlists'];
	sort( $pl );
	$prev = get_post_meta( $id, '_tt_vid_playlists', true );
	$prev = is_array( $prev ) ? $prev : array();
	sort( $prev );
	if ( $pl !== $prev ) {
		update_post_meta( $id, '_tt_vid_playlists', $pl );
	}
	if ( $cambio_cat && in_array( $res, array( 'sin_cambios', 'refrescada' ), true ) ) {
		$res = 'actualizada';
	}
	return array( $res, '', $id );
}

/* =====================================================================
 * 9. SINCRONIZACIÓN DE UN ÁREA
 * ===================================================================== */

function tt_vid_sync_area( $area, $filas, $op ) {
	$cfg    = tt_vid_cfg();
	$sim    = ! empty( $op['simular'] );
	$forzar = ! empty( $op['forzar'] );
	$ahora  = tt_vid_ahora();
	$r = array(
		'area' => $area, 'creadas' => 0, 'actualizadas' => 0, 'republicadas' => 0, 'refrescadas' => 0, 'sin_cambios' => 0,
		'omitidas' => 0, 'retiradas' => 0, 'vaciadas' => 0, 'errores' => 0, 'pendiente' => false, 'freno' => null,
		'freno_evaluado' => false, 'avisos' => array(), 'plan' => array(), 'filas' => array(), 'deseadas' => 0,
	);

	// 1) La definición debe estar completa. Si no, todas las filas del área fallan y no se toca nada.
	$cpt = $filas[0]['cpt'];
	$tax = '';
	$necesita_tax = false;
	$problemas = array();
	foreach ( $filas as $f ) {
		if ( $f['cpt'] !== $cpt ) {
			$problemas[] = 'Las filas del área «' . $area . '» tienen tipos de contenido distintos.';
		}
		if ( '' !== $f['tax'] ) {
			if ( '' !== $tax && $tax !== $f['tax'] ) {
				$problemas[] = 'Las filas del área «' . $area . '» tienen taxonomías distintas.';
			}
			$tax = $f['tax'];
		}
		if ( $f['crea_cat'] ) {
			$necesita_tax = true;
		}
	}
	$problemas = array_merge( $problemas, tt_vid_problemas_area( $cpt, $tax, $necesita_tax ) );
	if ( '' === tt_vid_clave() ) {
		$problemas[] = 'Falta la constante TT_YT_API_KEY en wp-config.php.';
	}
	if ( $problemas ) {
		foreach ( $filas as $f ) {
			$r['filas'][ $f['id'] ] = array( 'id' => $f['id'], 'nombre' => $f['nombre'], 'ok' => false, 'estado' => 'error',
				'mensaje' => implode( ' ', $problemas ), 'items' => 0, 'avisos' => array(), 'fin' => $ahora );
			$r['errores']++;
		}
		return $r;
	}

	// 2) Lectura de cada fila. Una fila que falla no impide leer las demás (salvo cuota o clave).
	$lect  = array();
	$falla = false;
	$corte = null;
	foreach ( $filas as $f ) {
		if ( $corte ) {
			$x = array( 'ok' => false, 'error' => $corte['error'], 'tipo' => $corte['tipo'], 'items' => array(), 'avisos' => array() );
		} else {
			$x = tt_vid_leer_playlist( $f['id'], $f['ocultos'] );
			if ( ! $x['ok'] && in_array( $x['tipo'], array( 'cuota', 'clave' ), true ) ) {
				$corte = $x;
			}
		}
		$r['filas'][ $f['id'] ] = array( 'id' => $f['id'], 'nombre' => $f['nombre'], 'ok' => $x['ok'], 'estado' => $x['ok'] ? 'ok' : 'error',
			'mensaje' => $x['error'], 'items' => count( $x['items'] ), 'avisos' => $x['avisos'], 'fin' => $ahora );
		if ( $x['ok'] ) {
			$lect[ $f['id'] ] = $x;
		} else {
			$falla = true;
			$r['errores']++;
		}
	}

	// 3) Categorías (una por fila que las crea). Las de filas fallidas se conservan tal cual.
	$term_por_pid = array();
	$ok_terms     = array();
	if ( $tax ) {
		foreach ( $filas as $f ) {
			if ( ! $f['crea_cat'] ) {
				continue;
			}
			if ( isset( $lect[ $f['id'] ] ) ) {
				$tid = tt_vid_asegurar_termino( $f, $tax, $sim, $r );
				if ( $tid ) {
					$ok_terms[] = $tid;
				}
			} else {
				$tid = tt_vid_termino_de_playlist( $tax, $f['id'] );
			}
			$term_por_pid[ $f['id'] ] = $tid;
		}
	}

	// 4) Estado deseado: una ficha por vídeo, con los datos de la PRIMERA fila donde aparece.
	$deseadas = array();
	foreach ( $filas as $f ) {
		if ( ! isset( $lect[ $f['id'] ] ) ) {
			continue;
		}
		$items   = $lect[ $f['id'] ]['items'];
		$hay_num = false;
		if ( 'titulo_numero' === $f['orden'] ) {
			foreach ( $items as $it ) {
				if ( tt_vid_numero_titulo( $it['titulo'] ) > 0 ) {
					$hay_num = true;
					break;
				}
			}
		}
		foreach ( $items as $vid => $it ) {
			if ( isset( $deseadas[ $vid ] ) ) {
				$deseadas[ $vid ]['playlists'][] = $f['id'];
				continue;
			}
			$anio = null;
			$mes  = null;
			$num  = null;
			$regla = $f['orden'];
			if ( 'texto' === $regla ) {
				$ft = tt_vid_fecha_texto( $it['descr'], $it['titulo'] );
				if ( $ft ) {
					list( $anio, $mes ) = $ft;
				} else {
					$anio = (int) gmdate( 'Y', $it['ts'] );
					$mes  = (int) gmdate( 'n', $it['ts'] );
					$r['avisos'][] = 'Sin mes y año en el texto, se usa la fecha del vídeo: «' . $it['titulo'] . '»';
				}
			} elseif ( 'titulo_numero' === $regla ) {
				if ( ! $hay_num ) {
					$regla = 'posicion'; // respaldo: ningún título de esta lista lleva número
				} else {
					$num = tt_vid_numero_titulo( $it['titulo'] );
					if ( 0 === $num ) {
						$r['avisos'][] = 'Vídeo sin número en una lista numerada (va el primero): «' . $it['titulo'] . '»';
					}
				}
			}
			$d = array(
				'vid' => $vid, 'titulo' => $it['titulo'], 'ts' => $it['ts'], 'fecha' => $it['ts'] ? gmdate( 'Y-m-d', $it['ts'] ) : '',
				'miniatura' => $it['miniatura'], 'url' => 'https://www.youtube.com/watch?v=' . $vid,
				'anio' => $anio, 'mes' => $mes, 'numero' => $num, 'area' => $area, 'playlists' => array( $f['id'] ),
			);
			$d['orden'] = tt_vid_clave_orden( $regla, array( 'anio' => $anio, 'mes' => $mes, 'ts' => $it['ts'], 'numero' => (int) $num, 'posicion' => $it['posicion'] ) );
			$d['hash']  = tt_vid_hash( $d );
			$deseadas[ $vid ] = $d;
		}
		foreach ( $lect[ $f['id'] ]['avisos'] as $a ) {
			$r['avisos'][] = $f['nombre'] . ': ' . $a;
		}
	}
	$r['deseadas'] = count( $deseadas );

	// Avisos sin acciones: posibles duplicados (mismo título base, ID distinto) y categoría sin estar en la lista «maestra».
	$bases = array();
	foreach ( $deseadas as $vid => $d ) {
		$bases[ tt_vid_titulo_base( $d['titulo'] ) ][] = $vid;
	}
	foreach ( $bases as $b => $ids ) {
		if ( '' !== $b && count( $ids ) > 1 ) {
			$r['avisos'][] = 'Posible duplicado (no se fusiona): «' . $deseadas[ $ids[0] ]['titulo'] . '» (' . implode( ', ', $ids ) . ')';
		}
	}
	$maestras = array();
	foreach ( $filas as $f ) {
		if ( ! $f['crea_cat'] && isset( $lect[ $f['id'] ] ) ) {
			$maestras[] = $f['id'];
		}
	}
	if ( $maestras ) {
		$cat_ids = array();
		foreach ( $filas as $f ) {
			if ( $f['crea_cat'] ) {
				$cat_ids[] = $f['id'];
			}
		}
		foreach ( $deseadas as $d ) {
			if ( array_intersect( $d['playlists'], $cat_ids ) && ! array_intersect( $d['playlists'], $maestras ) ) {
				$r['avisos'][] = 'En una categoría pero no en la playlist principal: «' . $d['titulo'] . '»';
			}
		}
	}

	// 5) Aplicar crear / actualizar, con presupuesto de tiempo (siempre avanza al menos una ficha por pasada).
	$mapa = tt_vid_db_mapa( $cpt );
	$ctx  = array( 'cpt' => $cpt, 'tax' => $tax, 'area' => $area, 'sim' => $sim, 'ahora' => $ahora,
		'term_por_pid' => $term_por_pid, 'ok_terms' => array_values( array_unique( $ok_terms ) ) );
	$id_de = array();
	foreach ( $deseadas as $vid => $d ) {
		if ( ! $sim && ( $r['creadas'] + $r['actualizadas'] + $r['republicadas'] ) > 0 && microtime( true ) > $op['limite'] ) {
			$r['pendiente'] = true;
			break;
		}
		$ex = isset( $mapa[ $vid ] ) ? $mapa[ $vid ] : null;
		if ( $ex && ( 'trash' === $ex['estado'] || ( 'publish' !== $ex['estado'] && '' === $ex['retirada'] ) ) ) {
			$r['omitidas']++; // papelera o borrador/pendiente puesto a mano: no se toca
			continue;
		}
		$u = tt_vid_upsert( $d, $ctx, $ex );
		if ( isset( $u[2] ) ) {
			$id_de[ $vid ] = $u[2];
		} elseif ( $ex ) {
			$id_de[ $vid ] = $ex['id'];
		}
		switch ( $u[0] ) {
			case 'creada':      $r['creadas']++;      if ( $sim ) { $r['plan'][] = 'CREAR · ' . $d['titulo']; } break;
			case 'actualizada': $r['actualizadas']++; if ( $sim ) { $r['plan'][] = 'CAMBIAR · ' . $d['titulo']; } break;
			case 'republicada': $r['republicadas']++; if ( $sim ) { $r['plan'][] = 'REPUBLICAR · ' . $d['titulo']; } break;
			case 'refrescada':  $r['refrescadas']++;  break;
			case 'sin_cambios': $r['sin_cambios']++;  break;
			default:
				$r['errores']++;
				$r['avisos'][] = $u[1];
		}
	}

	// 6) Retiradas (R1): solo si TODO se leyó completo y no queda trabajo pendiente. Con freno del 20 %.
	if ( ! $r['pendiente'] && ! $falla ) {
		$r['freno_evaluado'] = true;
		$publ = array();
		$cand = array();
		foreach ( $mapa as $vid => $m ) {
			if ( 'publish' !== $m['estado'] || $m['area'] !== $area ) {
				continue;
			}
			$publ[] = $vid;
			if ( ! isset( $deseadas[ $vid ] ) ) {
				$cand[] = $vid;
			}
		}
		if ( $cand ) {
			if ( count( $cand ) > (float) $cfg['freno'] * count( $publ ) && ! $forzar ) {
				$tit = array();
				foreach ( array_slice( $cand, 0, 10 ) as $vid ) {
					$tit[] = get_the_title( $mapa[ $vid ]['id'] );
				}
				$r['freno'] = array( 'cantidad' => count( $cand ), 'total' => count( $publ ), 'titulos' => $tit, 'fecha' => $ahora );
				$r['avisos'][] = 'FRENO: se retirarían ' . count( $cand ) . ' de ' . count( $publ ) . ' (más del ' . round( $cfg['freno'] * 100 ) . ' %). No se retira nada hasta que se confirme.';
			} else {
				foreach ( $cand as $vid ) {
					$m = $mapa[ $vid ];
					if ( $sim ) {
						$r['plan'][] = 'RETIRAR (a borrador) · ' . get_the_title( $m['id'] );
					} else {
						wp_update_post( array( 'ID' => $m['id'], 'post_status' => 'draft' ) );
						update_post_meta( $m['id'], '_tt_vid_retirada', $ahora );
					}
					$r['retiradas']++;
				}
			}
		}
	}

	// 7) Retiradas ya en borrador: R1 (se sigue comprobando por ID) y R3 (a los 25 días sin poder confirmarse, se vacían).
	$ret = array();
	foreach ( $mapa as $vid => $m ) {
		if ( 'draft' === $m['estado'] && '' !== $m['retirada'] && '' === $m['vaciada'] && $m['area'] === $area && ! isset( $deseadas[ $vid ] ) ) {
			$ret[ $vid ] = $m;
		}
	}
	if ( $ret ) {
		$vl = tt_vid_videos_list( array_keys( $ret ), 'snippet,status' );
		if ( ! $vl['ok'] ) {
			$r['avisos'][] = 'No se pudieron comprobar las canciones retiradas: ' . $vl['error'];
		}
		foreach ( $ret as $vid => $m ) {
			$vivo = false;
			if ( $vl['ok'] && isset( $vl['items'][ $vid ] ) ) {
				$pv   = isset( $vl['items'][ $vid ]['status']['privacyStatus'] ) ? $vl['items'][ $vid ]['status']['privacyStatus'] : '';
				$vivo = in_array( $pv, array( 'public', 'unlisted' ), true );
			}
			if ( $vivo ) {
				if ( ! $sim ) {
					tt_vid_refrescar_retirada( $m['id'], $vid, $vl['items'][ $vid ], $m, $ahora );
				}
				$r['refrescadas']++;
				continue;
			}
			$ref = tt_vid_ts_texto( $m['comprobado'] );
			if ( ! $ref ) {
				$ref = (int) $m['retirada'];
			}
			if ( $ahora - $ref >= (int) $cfg['dias_vaciar'] * 86400 ) {
				if ( $sim ) {
					$r['plan'][] = 'VACIAR datos de YouTube (R3) · ' . get_the_title( $m['id'] );
				} else {
					tt_vid_vaciar( $m['id'] );
				}
				$r['vaciadas']++;
			}
		}
	}

	// 8) «Made for Kids»: se lee una vez por ficha (1 unidad por 50 vídeos).
	$campos = tt_vid_campos_acf();
	if ( ! $sim && isset( $campos['vid_kids'] ) && $id_de ) {
		$pend = array();
		foreach ( $id_de as $vid => $id ) {
			$k = isset( $mapa[ $vid ] ) ? $mapa[ $vid ]['kids'] : '';
			if ( '' === $k && '' === (string) get_post_meta( $id, 'vid_kids', true ) ) {
				$pend[ $vid ] = $id;
			}
		}
		$pend = array_slice( $pend, 0, (int) $cfg['kids_por_pasada'], true );
		if ( $pend ) {
			$kl = tt_vid_videos_list( array_keys( $pend ), 'status' );
			if ( $kl['ok'] ) {
				foreach ( $pend as $vid => $id ) {
					$mk = isset( $kl['items'][ $vid ]['status']['madeForKids'] ) ? $kl['items'][ $vid ]['status']['madeForKids'] : null;
					tt_vid_uf( 'vid_kids', null === $mk ? 'desconocido' : ( $mk ? 'sí' : 'no' ), $id );
				}
			}
		}
	}
	return $r;
}

/** R1: el vídeo sigue existiendo → se confirma y se refrescan sus datos (la ficha sigue en borrador). */
function tt_vid_refrescar_retirada( $id, $vid, $v, $m, $ahora ) {
	$sn = isset( $v['snippet'] ) ? $v['snippet'] : array();
	if ( ! empty( $sn['title'] ) && get_the_title( $id ) !== $sn['title'] ) {
		wp_update_post( wp_slash( array( 'ID' => $id, 'post_title' => $sn['title'] ) ) );
	}
	$min = tt_vid_miniatura( $sn, $vid );
	if ( (string) get_post_meta( $id, 'vid_miniatura_url', true ) !== $min ) {
		tt_vid_uf( 'vid_miniatura_url', $min, $id );
	}
	$f = ! empty( $sn['publishedAt'] ) ? gmdate( 'Y-m-d', (int) strtotime( $sn['publishedAt'] ) ) : '';
	if ( '' !== $f && (string) get_post_meta( $id, 'vid_fecha_video', true ) !== $f ) {
		tt_vid_uf( 'vid_fecha_video', $f, $id );
	}
	if ( $ahora - tt_vid_ts_texto( $m['comprobado'] ) >= (int) tt_vid_cfg()['refresco_horas'] * 3600 ) {
		tt_vid_uf( 'vid_comprobado', gmdate( 'Y-m-d H:i:s', $ahora ), $id );
	}
}

/** R3: se vacían título, miniatura, fecha y demás datos de YouTube; queda solo el ID. El slug no cambia. */
function tt_vid_vaciar( $id ) {
	wp_update_post( wp_slash( array( 'ID' => $id, 'post_title' => '', 'post_name' => (string) get_post_field( 'post_name', $id ) ) ) );
	foreach ( array( 'vid_miniatura_url', 'vid_fecha_video', 'vid_kids', 'sem_anio', 'sem_mes', 'vcr_numero', 'vid_hash' ) as $n ) {
		tt_vid_uf( $n, '', $id );
	}
	update_post_meta( $id, '_tt_vid_vaciada', 1 );
}

/* =====================================================================
 * 10. SINCRONIZACIÓN COMPLETA
 * ===================================================================== */

function tt_vid_sync_todo( $origen = 'manual', $op = array() ) {
	$sim = ! empty( $op['simular'] );
	$res = array(
		'inicio' => time(), 'fin' => time(), 'origen' => $origen, 'estado' => 'ok', 'mensaje' => '', 'simulacion' => $sim,
		'areas' => array(), 'creadas' => 0, 'actualizadas' => 0, 'sin_cambios' => 0, 'retiradas' => 0, 'errores' => 0, 'pendiente' => false,
	);
	if ( ! function_exists( 'update_field' ) ) {
		$res['estado'] = 'error';
		$res['mensaje'] = 'ACF no está activo.';
		$res['errores'] = 1;
		return $res;
	}
	if ( ! tt_vid_lock_tomar() ) {
		$res['estado'] = 'ocupado';
		$res['mensaje'] = 'Ya hay una sincronización en marcha. Espera unos minutos.';
		return $res;
	}
	try {
		$cfg = tt_vid_cfg();
		@set_time_limit( 120 );
		$op['limite'] = microtime( true ) + (float) $cfg['presupuesto'];
		tt_vid_campos_acf( true );
		$por_area = array();
		foreach ( tt_vid_filas( true ) as $f ) {
			if ( ! empty( $f['activa'] ) && '' !== $f['id'] && '' !== $f['area'] ) {
				if ( empty( $op['solo_area'] ) || $op['solo_area'] === $f['area'] ) {
					$por_area[ $f['area'] ][] = $f;
				}
			}
		}
		if ( ! $por_area ) {
			$res['mensaje'] = 'No hay ninguna fila activa con ID de playlist.';
		}
		$hechas = 0;
		foreach ( $por_area as $area => $filas ) {
			if ( $hechas > 0 && microtime( true ) > $op['limite'] ) {
				$res['pendiente'] = true;
				continue;
			}
			$hechas++;
			$opa = $op;
			$opa['forzar'] = ! empty( $op['forzar_area'] ) && $op['forzar_area'] === $area;
			$r = tt_vid_sync_area( $area, $filas, $opa );
			if ( ! $sim ) {
				foreach ( $r['filas'] as $pid => $fr ) {
					tt_vid_log_fila( $pid, $fr );
				}
				if ( $r['freno_evaluado'] ) {
					tt_vid_log_area( $area, array( 'freno' => $r['freno'] ) );
				}
			}
			$res['areas'][ $area ] = $r;
			$res['creadas']      += $r['creadas'] + $r['republicadas'];
			$res['actualizadas'] += $r['actualizadas'];
			$res['sin_cambios']  += $r['sin_cambios'];
			$res['retiradas']    += $r['retiradas'];
			$res['errores']      += $r['errores'];
			if ( $r['pendiente'] ) {
				$res['pendiente'] = true;
			}
		}
	} catch ( \Throwable $e ) {
		$res['estado'] = 'error';
		$res['mensaje'] = 'Fallo inesperado: ' . $e->getMessage();
		$res['errores']++;
	} finally {
		tt_vid_lock_soltar();
		delete_option( 'tt_video_importador_test' ); // la prueba de cuota agotada dura una sola pasada
	}
	if ( 'error' !== $res['estado'] ) {
		$res['estado'] = $res['errores'] ? 'con_errores' : ( $res['pendiente'] ? 'pendiente' : 'ok' );
	}
	if ( ! $sim ) {
		if ( $res['pendiente'] && ! wp_next_scheduled( 'tt_vid_sync_continuar' ) ) {
			wp_schedule_single_event( time() + (int) tt_vid_cfg()['espera_cont'], 'tt_vid_sync_continuar' );
		}
		$res['fin'] = time();
		tt_vid_log_ejecucion( $res );
	}
	return $res;
}

/* =====================================================================
 * 11. PROGRAMACIÓN (WP-Cron), REVISIONES
 * ===================================================================== */

function tt_vid_proxima_hora() {
	$cfg = tt_vid_cfg();
	$d   = new DateTime( 'today ' . $cfg['hora_diaria'], wp_timezone() );
	if ( $d->getTimestamp() <= time() ) {
		$d->modify( '+1 day' );
	}
	return $d->getTimestamp();
}

function tt_vid_programar() {
	$activo = ( 'no' !== get_option( 'tt_video_importador_cron', 'si' ) );
	$prox   = wp_next_scheduled( 'tt_vid_sync_diaria' );
	if ( $activo && ! $prox ) {
		wp_schedule_event( tt_vid_proxima_hora(), 'daily', 'tt_vid_sync_diaria' );
	} elseif ( ! $activo && $prox ) {
		wp_clear_scheduled_hook( 'tt_vid_sync_diaria' );
	}
}

add_action( 'tt_vid_sync_diaria', function () {
	tt_vid_sync_todo( 'cron' );
} );
add_action( 'tt_vid_sync_continuar', function () {
	tt_vid_sync_todo( 'cron' );
} );
if ( did_action( 'init' ) ) {
	tt_vid_programar();
} else {
	add_action( 'init', 'tt_vid_programar' );
}

// Las fichas no las edita nadie con revisiones.
add_filter( 'wp_revisions_to_keep', function ( $num, $post ) {
	return ( $post && in_array( $post->post_type, tt_vid_cpts(), true ) ) ? 0 : $num;
}, 10, 2 );

/* =====================================================================
 * 12. SEMÁFORO (10 / 20 días), ENDPOINT DEL ESTADO Y AVISOS EN EL ESCRITORIO
 * ===================================================================== */

/** Por fila: días desde su última sincronización CORRECTA. Estado general = el peor de las filas activas. */
function tt_vid_semaforo() {
	$cfg   = tt_vid_cfg();
	$log   = tt_vid_log_base( tt_vid_log_get() );
	$ahora = tt_vid_ahora();
	$rank  = array( 'verde' => 0, 'amarillo' => 1, 'rojo' => 2 );
	$peor  = 'verde';
	$max   = 0;
	$filas = array();
	foreach ( tt_vid_filas_activas() as $f ) {
		$p    = isset( $log['filas'][ $f['id'] ] ) ? $log['filas'][ $f['id'] ] : array();
		$ref  = ! empty( $p['ultimo_ok'] ) ? (int) $p['ultimo_ok'] : (int) $f['creada'];
		$dias = max( 0, (int) floor( ( $ahora - $ref ) / 86400 ) );
		$c    = $dias >= (int) $cfg['dias_rojo'] ? 'rojo' : ( $dias >= (int) $cfg['dias_amarillo'] ? 'amarillo' : 'verde' );
		if ( $rank[ $c ] > $rank[ $peor ] ) {
			$peor = $c;
		}
		$max = max( $max, $dias );
		$filas[] = array(
			'nombre' => $f['nombre'], 'area' => $f['area'], 'dias' => $dias, 'color' => $c,
			'ultimo_ok' => ! empty( $p['ultimo_ok'] ) ? gmdate( 'c', (int) $p['ultimo_ok'] ) : null,
			'estado' => isset( $p['estado'] ) ? $p['estado'] : 'sin sincronizar',
			'mensaje' => isset( $p['mensaje'] ) ? $p['mensaje'] : '',
		);
	}
	return array( 'color' => $peor, 'dias_max' => $max, 'filas' => $filas );
}

function tt_vid_frenos() {
	$log = tt_vid_log_base( tt_vid_log_get() );
	$out = array();
	foreach ( $log['areas'] as $area => $a ) {
		if ( ! empty( $a['freno'] ) ) {
			$out[ $area ] = $a['freno'];
		}
	}
	return $out;
}

/** Permiso del endpoint: el mismo token de la app (cabecera X-TT-Token). Sin token en el servidor, cerrado. */
function tt_vid_rest_permiso( $req ) {
	$esperado = defined( 'TT_WRITE_TOKEN' ) ? TT_WRITE_TOKEN : apply_filters( 'tt_vid_token', '' );
	if ( ! is_string( $esperado ) || '' === $esperado ) {
		return new WP_Error( 'tt_sin_token', 'El servidor no tiene configurado el token de la app.', array( 'status' => 503 ) );
	}
	return hash_equals( $esperado, (string) $req->get_header( 'X-TT-Token' ) )
		? true : new WP_Error( 'tt_token', 'No autorizado.', array( 'status' => 401 ) );
}

function tt_vid_rest_estado() {
	$s   = tt_vid_semaforo();
	$log = tt_vid_log_base( tt_vid_log_get() );
	$u   = $log['ultima'];
	$resp = new WP_REST_Response( array(
		'color'    => $s['color'],
		'dias_max' => $s['dias_max'],
		'filas'    => $s['filas'],
		'frenos'   => tt_vid_frenos(),
		'ultima'   => $u ? array( 'fin' => gmdate( 'c', (int) $u['fin'] ), 'estado' => $u['estado'], 'origen' => $u['origen'] ) : null,
		'proxima'  => ( $p = wp_next_scheduled( 'tt_vid_sync_diaria' ) ) ? gmdate( 'c', $p ) : null,
	), 200 );
	$resp->header( 'Cache-Control', 'no-store' );
	return $resp;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'tiritaito/v1', '/videos/estado', array(
		'methods'             => 'GET',
		'callback'            => 'tt_vid_rest_estado',
		'permission_callback' => 'tt_vid_rest_permiso',
	) );
} );

add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s   = tt_vid_semaforo();
	$url = admin_url( 'tools.php?page=tt-video-importador' );
	if ( 'verde' !== $s['color'] ) {
		$cl = 'rojo' === $s['color'] ? 'notice-error' : 'notice-warning';
		echo '<div class="notice ' . esc_attr( $cl ) . '"><p>' . ( 'rojo' === $s['color'] ? '🔴' : '🟡' )
			. ' <strong>Importador de vídeos YouTube:</strong> lleva ' . (int) $s['dias_max'] . ' días sin sincronizar bien alguna playlist. '
			. '<a href="' . esc_url( $url ) . '">Ver Estado</a></p></div>';
	}
	foreach ( tt_vid_frenos() as $area => $fr ) {
		echo '<div class="notice notice-warning"><p>⛔ <strong>Importador de vídeos (' . esc_html( $area ) . '):</strong> frenado, iba a retirar '
			. (int) $fr['cantidad'] . ' de ' . (int) $fr['total'] . ' fichas. <a href="' . esc_url( $url ) . '">Revisar</a></p></div>';
	}
} );

/* =====================================================================
 * 13. FICHAS COMPARTIBLES: noindex, fuera del mapa de páginas y etiquetas para compartir
 * ===================================================================== */

add_filter( 'wp_sitemaps_post_types', function ( $types ) {
	if ( tt_vid_cfg()['noindex'] ) {
		foreach ( tt_vid_cpts() as $c ) {
			unset( $types[ $c ] );
		}
	}
	return $types;
} );

add_filter( 'wp_robots', function ( $robots ) {
	$c = tt_vid_cpts();
	if ( $c && tt_vid_cfg()['noindex'] && is_singular( $c ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
} );

// Evita que Jetpack emita sus propias etiquetas (genéricas) en las fichas. 🔲 sin probar con tu versión de Jetpack.
add_filter( 'jetpack_enable_open_graph', function ( $v ) {
	$c = tt_vid_cpts();
	return ( $c && tt_vid_cfg()['etiquetas'] && is_singular( $c ) ) ? false : $v;
} );
add_filter( 'jetpack_disable_twitter_cards', function ( $v ) {
	$c = tt_vid_cpts();
	return ( $c && tt_vid_cfg()['etiquetas'] && is_singular( $c ) ) ? true : $v;
} );

add_action( 'wp_head', function () {
	$c = tt_vid_cpts();
	if ( ! $c || ! tt_vid_cfg()['etiquetas'] || ! is_singular( $c ) ) {
		return;
	}
	$id  = get_queried_object_id();
	$tit = wp_strip_all_tags( get_the_title( $id ) );
	if ( '' === $tit ) {
		return;
	}
	$img  = apply_filters( 'tt_vid_og_imagen', (string) get_post_meta( $id, 'vid_miniatura_url', true ), $id );
	$desc = apply_filters( 'tt_vid_og_descripcion', 'Escucha «' . $tit . '» en ' . get_bloginfo( 'name' ), $id );
	$url  = get_permalink( $id );
	echo "\n<!-- TT vídeos: etiquetas para compartir -->\n";
	echo '<meta property="og:type" content="website" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $tit ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
	echo '<meta name="twitter:card" content="' . ( $img ? 'summary_large_image' : 'summary' ) . '" />' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $tit ) . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '" />' . "\n";
	if ( $img ) {
		echo '<meta property="og:image" content="' . esc_url( $img ) . '" />' . "\n";
		echo '<meta name="twitter:image" content="' . esc_url( $img ) . '" />' . "\n";
	}
}, 1 );

/* =====================================================================
 * 14. PANTALLA DE ADMINISTRACIÓN (Herramientas → Vídeos YouTube — Importador)
 * ===================================================================== */

function tt_vid_hora( $ts ) {
	return $ts ? wp_date( 'd/m/Y H:i', (int) $ts ) : '—';
}

function tt_vid_diagnostico() {
	$f     = array();
	$ok    = function ( $t ) use ( &$f ) { $f[] = array( 'ok', $t ); };
	$mal   = function ( $t ) use ( &$f ) { $f[] = array( 'mal', $t ); };
	$aviso = function ( $t ) use ( &$f ) { $f[] = array( 'aviso', $t ); };

	function_exists( 'update_field' ) ? $ok( 'ACF está activo.' ) : $mal( 'ACF no está activo.' );
	'' !== tt_vid_clave() ? $ok( 'La constante TT_YT_API_KEY está definida (su valor no se muestra).' ) : $mal( 'Falta la constante TT_YT_API_KEY en wp-config.php.' );

	$net = get_transient( 'tt_vid_net' );
	if ( false === $net ) {
		$r   = wp_remote_get( 'https://www.googleapis.com/youtube/v3/videos?part=id&id=x', array( 'timeout' => 8 ) ); // sin clave: no gasta cuota
		$net = is_wp_error( $r ) ? 'mal:' . $r->get_error_message() : 'ok';
		set_transient( 'tt_vid_net', $net, 600 );
	}
	'ok' === $net ? $ok( 'El servidor sale a googleapis.com.' ) : $mal( 'El servidor no sale a googleapis.com (' . substr( $net, 4 ) . '). Hablar con Raiola.' );

	if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
		$aviso( 'WP-Cron está desactivado en wp-config.php: la sincronización diaria solo funciona con un cron real.' );
	} else {
		$ok( 'WP-Cron está activo.' );
	}

	$campos  = tt_vid_campos_acf();
	$por_area = array();
	foreach ( tt_vid_filas() as $fila ) {
		$por_area[ $fila['area'] ][] = $fila;
		if ( ! empty( $fila['activa'] ) && '' === $fila['id'] ) {
			$aviso( 'La fila «' . $fila['nombre'] . '» está activa pero sin ID de playlist.' );
		}
	}
	foreach ( $por_area as $area => $filas ) {
		$activa = false;
		$tax    = '';
		$nec    = false;
		foreach ( $filas as $fila ) {
			if ( ! empty( $fila['activa'] ) && '' !== $fila['id'] ) {
				$activa = true;
			}
			if ( '' !== $fila['tax'] ) {
				$tax = $fila['tax'];
			}
			if ( $fila['crea_cat'] ) {
				$nec = true;
			}
		}
		$probs = tt_vid_problemas_area( $filas[0]['cpt'], $tax, $nec );
		if ( ! $probs ) {
			$ok( 'Área «' . $area . '»: tipo «' . $filas[0]['cpt'] . '»' . ( $tax ? ', taxonomía «' . $tax . '»' : '' ) . ' y casillas correctos' . ( $activa ? '.' : ' (área inactiva).' ) );
		}
		foreach ( $probs as $p ) {
			$activa ? $mal( 'Área «' . $area . '»: ' . $p ) : $aviso( 'Área «' . $area . '» (inactiva): ' . $p );
		}
	}
	foreach ( tt_vid_campos_opcionales() as $n => $tipos ) {
		if ( ! isset( $campos[ $n ] ) ) {
			$aviso( 'Casilla opcional «' . $n . '» sin crear (no es obligatoria).' );
		}
	}
	return $f;
}

function tt_vid_resumen( $res ) {
	return sprintf( '%s (%s): %d creadas, %d cambiadas, %d sin cambios, %d retiradas, %d errores.%s',
		$res['simulacion'] ? 'Simulación' : 'Sincronización', $res['estado'], $res['creadas'], $res['actualizadas'],
		$res['sin_cambios'], $res['retiradas'], $res['errores'],
		$res['pendiente'] ? ' Queda trabajo pendiente: continuará solo en un minuto, o pulsa de nuevo.' : '' );
}

function tt_vid_menu() {
	add_management_page( 'Vídeos YouTube — Importador', 'Vídeos YouTube — Importador', 'manage_options', 'tt-video-importador', 'tt_vid_pagina' );
}
add_action( 'admin_menu', 'tt_vid_menu' );
add_action( 'load-tools_page_tt-video-importador', 'tt_vid_manejar_post' );

function tt_vid_manejar_post() {
	if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) || empty( $_POST['tt_vid_accion'] ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'No tienes permiso para hacer esto.' );
	}
	check_admin_referer( 'tt_vid_accion' );
	$accion = sanitize_key( wp_unslash( $_POST['tt_vid_accion'] ) );
	$uid    = get_current_user_id();
	$msg    = '';

	if ( 'guardar_filas' === $accion ) {
		$nuevas = tt_vid_limpiar_filas( isset( $_POST['filas'] ) ? (array) wp_unslash( $_POST['filas'] ) : array(), tt_vid_filas() );
		update_option( 'tt_video_importador_filas', $nuevas, false );
		$msg = 'Ajustes guardados (' . count( $nuevas ) . ' filas).';
	} elseif ( 'simular' === $accion ) {
		$res = tt_vid_sync_todo( 'manual', array( 'simular' => true ) );
		$msg = 'ocupado' === $res['estado'] || 'error' === $res['estado'] ? $res['mensaje'] : tt_vid_resumen( $res ) . ' No se ha escrito nada.';
		$sim = array();
		foreach ( $res['areas'] as $area => $r ) {
			$sim[ $area ] = array( 'plan' => array_slice( $r['plan'], 0, 80 ), 'n_plan' => count( $r['plan'] ), 'avisos' => array_slice( $r['avisos'], 0, 30 ),
				'freno' => $r['freno'], 'filas' => $r['filas'] );
		}
		set_transient( 'tt_vid_sim_' . $uid, $sim, 900 );
	} elseif ( 'sync_todo' === $accion || 'forzar' === $accion ) {
		$op = array();
		if ( 'forzar' === $accion && isset( $_POST['area'] ) ) {
			$a  = sanitize_key( wp_unslash( $_POST['area'] ) );
			$op = array( 'solo_area' => $a, 'forzar_area' => $a );
		}
		$res = tt_vid_sync_todo( 'manual', $op );
		$msg = 'ocupado' === $res['estado'] || 'error' === $res['estado'] ? $res['mensaje'] : tt_vid_resumen( $res );
	} elseif ( 'cron' === $accion ) {
		update_option( 'tt_video_importador_cron', empty( $_POST['cron_activo'] ) ? 'no' : 'si', false );
		tt_vid_programar();
		$msg = 'Ajuste de sincronización diaria guardado.';
	} elseif ( 'liberar' === $accion ) {
		tt_vid_lock_soltar();
		$msg = 'Bloqueo liberado.';
	} elseif ( 'probar_clave' === $accion ) {
		$v   = tt_vid_videos_list( array( 'M7lc1UVf-VE' ), 'id' );
		$msg = $v['ok'] ? 'La clave funciona (1 unidad de cuota gastada).' : 'La clave NO funciona: ' . $v['error'];
	} elseif ( 'test_cuota' === $accion ) {
		update_option( 'tt_video_importador_test', 'cuota', false );
		$msg = 'La próxima pasada simulará «cuota agotada» (M4). Pulsa «Sincronizar ahora».';
	} elseif ( 'test_dias' === $accion ) {
		$dias = isset( $_POST['dias'] ) ? max( 0, absint( $_POST['dias'] ) ) : 0;
		foreach ( tt_vid_filas_activas() as $f ) {
			tt_vid_log_fila( $f['id'], array( 'id' => $f['id'], 'nombre' => $f['nombre'], 'ok' => false, 'estado' => 'prueba', 'mensaje' => 'Prueba M24', 'items' => 0, 'avisos' => array(), 'fin' => time() ) );
			$log = tt_vid_log_base( tt_vid_log_get() );
			$log['filas'][ $f['id'] ]['ultimo_ok'] = time() - $dias * 86400 - 3600;
			update_option( 'tt_video_importador_log', $log, false );
		}
		$msg = 'Última sincronización correcta de todas las filas fijada en hace ' . $dias . ' días. La próxima sincronización real lo corrige.';
	}
	set_transient( 'tt_vid_aviso_' . $uid, $msg, 120 );
	wp_safe_redirect( admin_url( 'tools.php?page=tt-video-importador' ) );
	exit;
}

function tt_vid_pagina() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$uid   = get_current_user_id();
	$aviso = get_transient( 'tt_vid_aviso_' . $uid );
	if ( $aviso ) {
		delete_transient( 'tt_vid_aviso_' . $uid );
	}
	$sim    = get_transient( 'tt_vid_sim_' . $uid );
	$log    = tt_vid_log_base( tt_vid_log_get() );
	$s      = tt_vid_semaforo();
	$iconos = array( 'ok' => '✅', 'mal' => '❌', 'aviso' => '⚠️' );
	$luz    = array( 'verde' => '🟢', 'amarillo' => '🟡', 'rojo' => '🔴' );
	$prox   = wp_next_scheduled( 'tt_vid_sync_diaria' );
	$activo = ( 'no' !== get_option( 'tt_video_importador_cron', 'si' ) );
	$lock   = (int) get_option( 'tt_video_importador_lock' );
	$boton  = function ( $accion, $texto, $clase = 'secondary', $extra = '' ) {
		echo '<form method="post" style="display:inline-block;margin:0 8px 6px 0">';
		wp_nonce_field( 'tt_vid_accion' );
		echo '<input type="hidden" name="tt_vid_accion" value="' . esc_attr( $accion ) . '">' . $extra; // $extra solo lleva HTML propio de este archivo
		submit_button( $texto, $clase, 'submit', false );
		echo '</form>';
	};

	echo '<div class="wrap"><h1>Vídeos YouTube — Importador</h1>';
	if ( $aviso ) {
		echo '<div class="notice notice-info is-dismissible"><p>' . esc_html( $aviso ) . '</p></div>';
	}

	echo '<h2>Semáforo</h2><p style="font-size:16px">' . $luz[ $s['color'] ] . ' <strong>' . esc_html( ucfirst( $s['color'] ) ) . '</strong> · la fila más atrasada lleva '
		. (int) $s['dias_max'] . ' días sin sincronizar bien (amarillo a ' . (int) tt_vid_cfg()['dias_amarillo'] . ', rojo a ' . (int) tt_vid_cfg()['dias_rojo'] . ').</p>';

	echo '<h2>Estado</h2><ul>';
	foreach ( tt_vid_diagnostico() as $fila ) {
		echo '<li>' . esc_html( $iconos[ $fila[0] ] . ' ' . $fila[1] ) . '</li>';
	}
	echo '</ul>';

	foreach ( tt_vid_frenos() as $area => $fr ) {
		echo '<div class="notice notice-warning inline"><p>⛔ <strong>Freno en «' . esc_html( $area ) . '»:</strong> se retirarían '
			. (int) $fr['cantidad'] . ' de ' . (int) $fr['total'] . ' fichas (' . esc_html( tt_vid_hora( $fr['fecha'] ) ) . '). Por ejemplo: '
			. esc_html( implode( ' · ', $fr['titulos'] ) ) . '</p>';
		$boton( 'forzar', 'Aplicar de todos modos', 'primary', '<input type="hidden" name="area" value="' . esc_attr( $area ) . '">' );
		echo '</div>';
	}

	echo '<h2>Sincronizar</h2>';
	$boton( 'simular', 'Simular (no escribe nada)' );
	$boton( 'sync_todo', 'Sincronizar ahora', 'primary' );
	echo '<form method="post" style="display:inline-block">';
	wp_nonce_field( 'tt_vid_accion' );
	echo '<input type="hidden" name="tt_vid_accion" value="cron"><label><input type="checkbox" name="cron_activo" value="1"' . checked( $activo, true, false ) . '> Sincronización diaria automática</label> ';
	submit_button( 'Guardar', 'secondary', 'submit', false );
	echo '</form>';
	echo '<p>Próxima sincronización automática: <strong>' . esc_html( $activo ? tt_vid_hora( $prox ) : 'desactivada' ) . '</strong>';
	if ( $lock ) {
		echo ' · ⏳ Sincronización en marcha desde las ' . esc_html( tt_vid_hora( $lock ) ) . '. ';
		$boton( 'liberar', 'Liberar bloqueo', 'link' );
	}
	echo '</p>';

	if ( is_array( $sim ) ) {
		echo '<h2>Resultado de la última simulación</h2>';
		foreach ( $sim as $area => $r ) {
			echo '<h3>' . esc_html( $area ) . ' — ' . (int) $r['n_plan'] . ' cambios previstos</h3><ul>';
			foreach ( $r['plan'] as $l ) {
				echo '<li>' . esc_html( $l ) . '</li>';
			}
			foreach ( $r['avisos'] as $l ) {
				echo '<li>⚠️ ' . esc_html( $l ) . '</li>';
			}
			if ( ! $r['plan'] && ! $r['avisos'] ) {
				echo '<li>Nada que cambiar.</li>';
			}
			echo '</ul>';
		}
	}

	echo '<h2>Playlists (ajustes)</h2><form method="post">';
	wp_nonce_field( 'tt_vid_accion' );
	echo '<input type="hidden" name="tt_vid_accion" value="guardar_filas">';
	echo '<table class="widefat striped"><thead><tr><th>ID de la playlist (o su dirección)</th><th>Área</th><th>Nombre visible</th><th>¿Crea categoría?</th><th>¿Incluye ocultos?</th><th>Orden</th><th>Tipo</th><th>Taxonomía</th><th>Activa</th><th>Última vez bien</th><th>Último resultado</th><th>Borrar</th></tr></thead><tbody>';
	$filas = tt_vid_filas( true );
	$filas[] = array( 'id' => '', 'area' => '', 'nombre' => '', 'crea_cat' => true, 'ocultos' => false, 'orden' => 'fecha_video', 'cpt' => '', 'tax' => '', 'activa' => false, 'creada' => time() );
	$filas[] = $filas[ count( $filas ) - 1 ];
	foreach ( $filas as $i => $f ) {
		$p = ( '' !== $f['id'] && isset( $log['filas'][ $f['id'] ] ) ) ? $log['filas'][ $f['id'] ] : array();
		$n = 'filas[' . (int) $i . ']';
		echo '<tr><td><input type="text" size="30" name="' . esc_attr( $n ) . '[id]" value="' . esc_attr( $f['id'] ) . '"><input type="hidden" name="' . esc_attr( $n ) . '[creada]" value="' . (int) $f['creada'] . '"></td>';
		echo '<td><input type="text" size="10" name="' . esc_attr( $n ) . '[area]" value="' . esc_attr( $f['area'] ) . '"></td>';
		echo '<td><input type="text" size="18" name="' . esc_attr( $n ) . '[nombre]" value="' . esc_attr( $f['nombre'] ) . '"></td>';
		echo '<td><input type="checkbox" name="' . esc_attr( $n ) . '[crea_cat]" value="1"' . checked( ! empty( $f['crea_cat'] ), true, false ) . '></td>';
		echo '<td><input type="checkbox" name="' . esc_attr( $n ) . '[ocultos]" value="1"' . checked( ! empty( $f['ocultos'] ), true, false ) . '></td>';
		echo '<td><select name="' . esc_attr( $n ) . '[orden]">';
		foreach ( tt_vid_reglas_orden() as $k => $et ) {
			echo '<option value="' . esc_attr( $k ) . '"' . selected( $f['orden'], $k, false ) . '>' . esc_html( $et ) . '</option>';
		}
		echo '</select></td>';
		echo '<td><input type="text" size="10" name="' . esc_attr( $n ) . '[cpt]" value="' . esc_attr( $f['cpt'] ) . '"></td>';
		echo '<td><input type="text" size="16" name="' . esc_attr( $n ) . '[tax]" value="' . esc_attr( $f['tax'] ) . '"></td>';
		echo '<td><input type="checkbox" name="' . esc_attr( $n ) . '[activa]" value="1"' . checked( ! empty( $f['activa'] ), true, false ) . '></td>';
		echo '<td>' . esc_html( tt_vid_hora( isset( $p['ultimo_ok'] ) ? $p['ultimo_ok'] : 0 ) ) . '</td>';
		echo '<td><small>' . esc_html( ( isset( $p['estado'] ) ? $p['estado'] : '—' ) . ( ! empty( $p['mensaje'] ) ? ' · ' . $p['mensaje'] : '' ) . ( isset( $p['items'] ) ? ' · ' . $p['items'] . ' vídeos' : '' ) ) . '</small></td>';
		echo '<td><input type="checkbox" name="' . esc_attr( $n ) . '[borrar]" value="1"></td></tr>';
	}
	echo '</tbody></table>';
	submit_button( 'Guardar ajustes', 'secondary', 'submit', true );
	echo '<p><small>Dos filas vacías al final sirven para añadir playlists. Una fila sin «Crea categoría» alimenta el catálogo sin crear categoría (la pestaña «All» de Avada hace ese papel). Un área nueva necesita su tipo de contenido, su taxonomía y el grupo «TT — Vídeo YouTube» asignado: el Estado dice qué falta.</small></p></form>';

	echo '<h2>Última ejecución</h2>';
	if ( empty( $log['ultima'] ) ) {
		echo '<p>Todavía no se ha ejecutado.</p>';
	} else {
		$u = $log['ultima'];
		printf( '<p>%s · origen: %s · estado: <strong>%s</strong> · %d creadas, %d cambiadas, %d retiradas, %d errores.%s</p>',
			esc_html( tt_vid_hora( $u['fin'] ) ), esc_html( $u['origen'] ), esc_html( $u['estado'] ), (int) $u['creadas'], (int) $u['actualizadas'],
			(int) $u['retiradas'], (int) $u['errores'], $u['mensaje'] ? ' ' . esc_html( $u['mensaje'] ) : '' );
		foreach ( (array) $u['areas'] as $area => $r ) {
			if ( ! empty( $r['avisos'] ) ) {
				echo '<details><summary>Avisos de «' . esc_html( $area ) . '» (' . count( $r['avisos'] ) . ')</summary><ul>';
				foreach ( array_slice( $r['avisos'], 0, 60 ) as $a ) {
					echo '<li>' . esc_html( $a ) . '</li>';
				}
				echo '</ul></details>';
			}
		}
	}
	if ( ! empty( $log['historial'] ) ) {
		echo '<details><summary>Historial (últimas 20)</summary><ul>';
		foreach ( $log['historial'] as $h ) {
			printf( '<li>%s · %s · %s · %d creadas, %d cambiadas, %d retiradas, %d errores</li>', esc_html( tt_vid_hora( $h['fin'] ) ), esc_html( $h['origen'] ),
				esc_html( $h['estado'] ), (int) $h['creadas'], (int) $h['actualizadas'], (int) $h['retiradas'], (int) $h['errores'] );
		}
		echo '</ul></details>';
	}

	echo '<h2>Herramientas de prueba</h2><details><summary>Casos M4, M24 y comprobar la clave</summary><p>';
	$boton( 'probar_clave', 'Probar la clave (1 unidad)' );
	$boton( 'test_cuota', 'Simular «cuota agotada» en la próxima pasada' );
	echo '</p><p>';
	foreach ( array( 11, 21 ) as $d ) {
		$boton( 'test_dias', 'Fingir última sincronización correcta hace ' . $d . ' días', 'secondary', '<input type="hidden" name="dias" value="' . (int) $d . '">' );
	}
	echo '</p></details></div>';
}

} // fin del bloque tt_vid_sync_todo

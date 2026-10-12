/**
 * TT Podcast — Importador · v1.1 · 10 octubre 2026
 *
 * NOVEDADES DE LA v1.1 (sobre la v1.0)
 *   - Nuevas casillas: ep_texto (Editor, texto completo del episodio), tmp_canal_url y tmp_canal_nombre
 *     (en la temporada) y ep_temporada_url (en el episodio). Avada no puede enlazar a una entrada
 *     guardada en una casilla de «Objeto de entrada»; sí puede leer una casilla de tipo URL.
 *   - El enlace de YouTube del episodio ya no es «el primero que aparezca»: se prefiere el que va
 *     tras «ver en YouTube», después un enlace /live/, y solo al final el primero.
 *   - Un episodio sin temporada en el feed hereda la de sus vecinos por fecha (con aviso);
 *     sin número, ocupa el hueco entre sus vecinos si lo hay.
 *   - El sufijo de los slugs que chocan usa las 8 ÚLTIMAS letras del GUID (los GUID de tipo URL
 *     empiezan todos igual).
 *   - El tiempo de la descarga de la carátula no se descuenta del presupuesto de los episodios.
 *
 * QUÉ HACE
 *   Lee el feed de cada canal que tenga la casilla de feed rellena (santos de
 *   Hombres de Dios: hdd_podcast_feed · resto de canales: pod_feed) y mantiene
 *   en WordPress tres niveles:
 *     CANAL (entrada)  →  TEMPORADA (CPT "temporada")  →  EPISODIO (CPT "episodio")
 *   Rellena las relaciones: canal → temporadas, canal → episodios y temporada → episodios.
 *
 * REGLAS DE ORO (00_CORE.md)
 *   - Escribe SIEMPRE con update_field() (nunca update_option() para campos ACF).
 *   - No usa AJAX ni admin-ajax.php: el botón es un formulario normal de wp-admin.
 *   - Un solo archivo, funciones con prefijo tt_pod_ (no hay choque de nombres).
 *
 * CÓMO INSTALARLO (Code Snippets, versión gratuita)
 *   Tipo: PHP · Alcance: "Run snippet everywhere" (hace falta para WP-Cron).
 *   Pegar este archivo entero, SIN etiqueta <?php de apertura.
 *   Después: Herramientas → "Podcast — Importador" → mirar el "Estado" antes de sincronizar.
 *
 * QUÉ NO HACE (a propósito)
 *   - No borra ni despublica nada automáticamente. Un episodio que desaparece del feed
 *     se queda; un episodio en la papelera NO se vuelve a crear; un episodio pasado a
 *     borrador sigue en borrador.
 *   - No toca el título, la portada ni la descripción de una temporada una vez creada.
 *   - No usa ningún dato de prueba: los nombres de tipos y casillas están en tt_pod_cfg()
 *     y tt_pod_pares().
 */
if ( ! function_exists( 'tt_pod_sync_todo' ) ) {

/* =====================================================================
 * 1. CONFIGURACIÓN
 * ===================================================================== */

function tt_pod_cfg() {
	return apply_filters( 'tt_pod_config', array(
		'cpt_episodio'  => 'episodio',   // clave del tipo de contenido Episodio
		'cpt_temporada' => 'temporada',  // clave del tipo de contenido Temporada
		'tax_canal'     => 'canal',      // clave de la taxonomía Canal
		'presupuesto'   => 25,           // segundos de trabajo por pasada (antes de parar y continuar)
		'espera_cont'   => 60,           // segundos hasta la pasada de continuación
		'hora_diaria'   => '04:30',      // hora local de la sincronización diaria
	) );
}

/** Pares de casillas del canal. Añadir un tipo de canal = añadir un par. */
function tt_pod_pares() {
	return apply_filters( 'tt_pod_pares', array(
		'hdd' => array(
			'feed'       => 'hdd_podcast_feed',
			'caratula'   => 'hdd_podcast_caratula',
			'temporadas' => 'hdd_temporadas',
			'episodios'  => 'hdd_episodios',
		),
		'pod' => array(
			'feed'       => 'pod_feed',
			'caratula'   => 'pod_caratula',
			'temporadas' => 'pod_temporadas',
			'episodios'  => 'pod_episodios',
		),
	) );
}

/** Casillas que el importador escribe y el tipo ACF que deben tener. */
function tt_pod_campos_requeridos() {
	$r = array();
	foreach ( tt_pod_pares() as $par ) {
		$r[ $par['feed'] ]       = array( 'url' );
		$r[ $par['caratula'] ]   = array( 'image' );
		$r[ $par['temporadas'] ] = array( 'relationship' );
		$r[ $par['episodios'] ]  = array( 'relationship' );
	}
	$r['tmp_numero']       = array( 'number' );
	$r['tmp_canal']        = array( 'post_object', 'relationship' );
	$r['tmp_episodios']    = array( 'relationship' );
	$r['tmp_canal_url']    = array( 'url' );
	$r['tmp_canal_nombre'] = array( 'text' );
	$r['ep_texto']         = array( 'wysiwyg' );
	$r['ep_temporada_url'] = array( 'url' );
	$r['ep_guid']          = array( 'text' );
	$r['ep_audio_url']     = array( 'url' );
	$r['ep_youtube_url']   = array( 'text' );
	$r['ep_imagen_url']    = array( 'url' );
	$r['ep_duracion']      = array( 'text' );
	$r['ep_numero']        = array( 'number' );
	$r['ep_temporada']     = array( 'number' );
	$r['ep_temporada_ref'] = array( 'post_object', 'relationship' );
	$r['ep_hash']          = array( 'text' );
	return $r;
}

/* =====================================================================
 * 2. AYUDAS PEQUEÑAS (sin acceso a la base de datos)
 * ===================================================================== */

function tt_pod_texto( $s ) {
	$s = html_entity_decode( wp_strip_all_tags( (string) $s ), ENT_QUOTES, 'UTF-8' );
	return trim( preg_replace( '/\s+/u', ' ', $s ) );
}

function tt_pod_duracion( $raw ) {
	$raw = trim( (string) $raw );
	if ( '' === $raw ) {
		return '';
	}
	if ( ctype_digit( $raw ) ) {
		$s = (int) $raw;
	} elseif ( preg_match( '/^(\d+):(\d{1,2})(?::(\d{1,2}))?$/', $raw, $m ) ) {
		$s = ( isset( $m[3] ) && '' !== $m[3] )
			? ( (int) $m[1] * 3600 + (int) $m[2] * 60 + (int) $m[3] )
			: ( (int) $m[1] * 60 + (int) $m[2] );
	} else {
		return sanitize_text_field( $raw );
	}
	$h  = intdiv( $s, 3600 );
	$mi = intdiv( $s % 3600, 60 );
	$se = $s % 60;
	return $h > 0 ? sprintf( '%d:%02d:%02d', $h, $mi, $se ) : sprintf( '%d:%02d', $mi, $se );
}

function tt_pod_youtube( $html ) {
	$html = (string) $html;
	$id   = '([a-zA-Z0-9_-]{11})';
	$url  = '(?:https?:\/\/)?(?:www\.|m\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?|live|shorts)\/|\S*?[?&]v=)|youtu\.be\/)' . $id;
	// 1) El que va tras «ver en YouTube» / «en YouTube» / «Youtube:» (es el vídeo de ESTE episodio).
	if ( preg_match( '/(?:ver\s+)?en\s+you\s*tube\s*:?\s*(?:<a[^>]*>)?\W{0,6}' . $url . '/iu', $html, $m ) ) {
		return 'https://www.youtube.com/watch?v=' . $m[1];
	}
	// 2) Un enlace de retransmisión /live/ (la propia charla).
	if ( preg_match( '/youtube\.com\/live\/' . $id . '/i', $html, $m ) ) {
		return 'https://www.youtube.com/watch?v=' . $m[1];
	}
	// 3) El primer enlace de YouTube que aparezca.
	if ( preg_match( '/' . $url . '/i', $html, $m ) ) {
		return 'https://www.youtube.com/watch?v=' . $m[1];
	}
	return '';
}

function tt_pod_ids( $valor ) {
	if ( is_string( $valor ) && '' !== $valor && is_serialized( $valor ) ) {
		$valor = maybe_unserialize( $valor );
	}
	if ( ! is_array( $valor ) ) {
		return ( '' === $valor || null === $valor || false === $valor ) ? array() : array( (int) $valor );
	}
	$ids = array_map( 'intval', $valor );
	sort( $ids );
	return $ids;
}

function tt_pod_hash( $titulo, $contenido, $gmt, $youtube, $duracion, $numero, $temp_n, $cover_id, $temp_id ) {
	return md5( wp_json_encode( array( $titulo, $contenido, $gmt, $youtube, $duracion, (int) $numero, (int) $temp_n, (int) $cover_id, (int) $temp_id ) ) );
}

/** Slug estable (S3): título; si ya existe, título + 8 letras del GUID. */
function tt_pod_slug_episodio( $titulo, $guid, $cpt ) {
	$g8   = strtolower( substr( preg_replace( '/[^a-zA-Z0-9]/', '', (string) $guid ), -8 ) );
	$base = sanitize_title( $titulo );
	if ( '' === $base || false !== strpos( $base, '%' ) ) {
		return 'episodio-' . ( '' !== $g8 ? $g8 : substr( md5( (string) $guid . $titulo ), 0, 8 ) );
	}
	if ( tt_pod_db_slug_existe( $base, $cpt ) ) {
		return $base . '-' . ( '' !== $g8 ? $g8 : substr( md5( (string) $guid ), 0, 8 ) );
	}
	return $base;
}

/* =====================================================================
 * 3. ACCESO A LA BASE DE DATOS (todo lo que toca $wpdb o consultas va aquí)
 * ===================================================================== */

/** IDs de entradas (de cualquier tipo editable, salvo episodio/temporada) con el feed relleno. */
function tt_pod_db_canales_con_feed( $campo ) {
	$cfg   = tt_pod_cfg();
	$tipos = array_values( array_diff(
		get_post_types( array( 'show_ui' => true ), 'names' ),
		array( $cfg['cpt_episodio'], $cfg['cpt_temporada'], 'attachment' )
	) );
	if ( ! $tipos ) {
		return array();
	}
	return get_posts( array(
		'post_type'      => $tipos,
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'orderby'        => 'ID',
		'order'          => 'ASC',
		'meta_query'     => array( array( 'key' => $campo, 'value' => '', 'compare' => '!=' ) ),
	) );
}

/**
 * guid => array( id, hash, estado, legacy ) de los episodios de UN canal (incluida la papelera).
 * El dueño de un episodio es el canal guardado en su meta privada _tt_pod_canal: así renombrar
 * la entrada del canal o su taxonomía no hace que se vuelvan a crear los episodios.
 * Los episodios de la v1.0 (sin esa meta) se adoptan si llevan el término del canal o ninguno.
 * Un GUID que pertenece a OTRO canal no se toca.
 */
function tt_pod_db_mapa_episodios( $cpt, $term_id, $canal_id ) {
	global $wpdb;
	$cfg   = tt_pod_cfg();
	$filas = $wpdb->get_results( $wpdb->prepare(
		"SELECT p.ID AS id, p.post_status AS estado, g.meta_value AS guid, h.meta_value AS hash,
		        c.meta_value AS canal, tt.term_id AS term_id
		   FROM {$wpdb->posts} p
		  INNER JOIN {$wpdb->postmeta} g ON g.post_id = p.ID AND g.meta_key = 'ep_guid'
		   LEFT JOIN {$wpdb->postmeta} h ON h.post_id = p.ID AND h.meta_key = 'ep_hash'
		   LEFT JOIN {$wpdb->postmeta} c ON c.post_id = p.ID AND c.meta_key = '_tt_pod_canal'
		   LEFT JOIN {$wpdb->term_relationships} tr ON tr.object_id = p.ID
		   LEFT JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy = %s
		  WHERE p.post_type = %s AND p.post_status <> 'auto-draft'
		  ORDER BY p.ID ASC",
		$cfg['tax_canal'], $cpt
	), ARRAY_A );

	$porid = array();
	foreach ( (array) $filas as $f ) {
		$id = (int) $f['id'];
		if ( ! isset( $porid[ $id ] ) ) {
			$porid[ $id ] = array( 'id' => $id, 'guid' => (string) $f['guid'], 'hash' => (string) $f['hash'],
				'estado' => $f['estado'], 'canal' => (int) $f['canal'], 'terms' => array() );
		}
		if ( null !== $f['term_id'] ) {
			$porid[ $id ]['terms'][ (int) $f['term_id'] ] = true;
		}
	}
	$mapa    = array();
	$huerfan = array();
	foreach ( $porid as $e ) {
		if ( '' === $e['guid'] ) {
			continue;
		}
		$dato = array( 'id' => $e['id'], 'hash' => $e['hash'], 'estado' => $e['estado'], 'legacy' => false );
		if ( $e['canal'] ) {
			if ( $e['canal'] === (int) $canal_id && ! isset( $mapa[ $e['guid'] ] ) ) {
				$mapa[ $e['guid'] ] = $dato;
			}
		} elseif ( $term_id && isset( $e['terms'][ (int) $term_id ] ) ) {
			$dato['legacy'] = true; // creado por la v1.0: se adopta
			if ( ! isset( $mapa[ $e['guid'] ] ) ) {
				$mapa[ $e['guid'] ] = $dato;
			}
		} elseif ( ! $e['terms'] && ! isset( $huerfan[ $e['guid'] ] ) ) {
			$huerfan[ $e['guid'] ] = $dato;
		}
	}
	foreach ( $huerfan as $guid => $dato ) {
		if ( ! isset( $mapa[ $guid ] ) ) {
			$dato['hash']   = ''; // fuerza a reescribirlo entero
			$dato['legacy'] = true;
			$mapa[ $guid ]  = $dato;
		}
	}
	return $mapa;
}

function tt_pod_db_slug_existe( $slug, $cpt ) {
	global $wpdb;
	return (bool) $wpdb->get_var( $wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_name = %s AND post_status <> 'auto-draft' LIMIT 1",
		$cpt, $slug
	) );
}

function tt_pod_db_temporada_por_clave( $clave, $cpt ) {
	global $wpdb;
	return (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT p.ID FROM {$wpdb->posts} p
		  INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_tt_pod_clave' AND m.meta_value = %s
		  WHERE p.post_type = %s AND p.post_status <> 'auto-draft' ORDER BY p.ID ASC LIMIT 1",
		$clave, $cpt
	) );
}

/** Episodios publicados de un canal (por su meta privada _tt_pod_canal): array de array( id, temporada, numero ). */
function tt_pod_db_episodios_del_canal( $canal_id ) {
	$cfg = tt_pod_cfg();
	$q   = new WP_Query( array(
		'post_type'      => $cfg['cpt_episodio'],
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'meta_query'     => array( array( 'key' => '_tt_pod_canal', 'value' => (string) (int) $canal_id, 'compare' => '=' ) ),
	) );
	$filas = array();
	foreach ( $q->posts as $p ) {
		$filas[] = array(
			'id'        => (int) $p->ID,
			'temporada' => (int) get_post_meta( $p->ID, 'ep_temporada', true ),
			'numero'    => (int) $p->menu_order,
		);
	}
	return $filas;
}

/* =====================================================================
 * 4. ACF: escribir siempre por CLAVE de campo (así ACF guarda también la referencia)
 * ===================================================================== */

function tt_pod_campos_acf( $reiniciar = false ) {
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

function tt_pod_uf( $nombre, $valor, $post_id ) {
	$mapa = tt_pod_campos_acf();
	$sel  = isset( $mapa[ $nombre ] ) ? $mapa[ $nombre ]['key'] : $nombre;
	return update_field( $sel, $valor, $post_id );
}

/** Escribe un campo solo si cambia. Devuelve true si escribió. */
function tt_pod_uf_si_cambia( $post_id, $nombre, $nuevo ) {
	if ( (string) get_post_meta( $post_id, $nombre, true ) === (string) $nuevo ) {
		return false;
	}
	tt_pod_uf( $nombre, $nuevo, $post_id );
	return true;
}

/** Escribe una relación solo si la lista de IDs cambia. */
function tt_pod_uf_relacion( $post_id, $nombre, $ids ) {
	$ids = array_values( array_unique( array_map( 'intval', $ids ) ) );
	sort( $ids );
	if ( tt_pod_ids( get_post_meta( $post_id, $nombre, true ) ) === $ids ) {
		return false;
	}
	tt_pod_uf( $nombre, $ids, $post_id );
	return true;
}

/* =====================================================================
 * 5. REGISTRO (log por fuente) Y BLOQUEO
 * ===================================================================== */

function tt_pod_log_get() {
	$l = get_option( 'tt_podcast_importador_log' );
	return is_array( $l ) ? $l : array();
}

function tt_pod_log_base( $log ) {
	if ( ! isset( $log['podcast'] ) || ! is_array( $log['podcast'] ) ) {
		$log['podcast'] = array();
	}
	foreach ( array( 'ultima', 'canales', 'historial' ) as $k ) {
		if ( ! isset( $log['podcast'][ $k ] ) || ! is_array( $log['podcast'][ $k ] ) ) {
			$log['podcast'][ $k ] = array();
		}
	}
	return $log;
}

function tt_pod_log_canal( $id, $r ) {
	$log = tt_pod_log_base( tt_pod_log_get() );
	$log['podcast']['canales'][ (int) $id ] = $r;
	update_option( 'tt_podcast_importador_log', $log, false );
}

function tt_pod_log_ejecucion( $res ) {
	$log = tt_pod_log_base( tt_pod_log_get() );
	$log['podcast']['ultima'] = $res;
	array_unshift( $log['podcast']['historial'], array(
		'fin'          => time(),
		'origen'       => $res['origen'],
		'estado'       => $res['estado'],
		'creados'      => $res['creados'],
		'actualizados' => $res['actualizados'],
		'errores'      => $res['errores'],
		'mensaje'      => $res['mensaje'],
	) );
	$log['podcast']['historial'] = array_slice( $log['podcast']['historial'], 0, 20 );
	update_option( 'tt_podcast_importador_log', $log, false );
}

function tt_pod_lock_tomar() {
	$ahora = time();
	if ( add_option( 'tt_podcast_importador_lock', $ahora, '', 'no' ) ) {
		return true;
	}
	$t = (int) get_option( 'tt_podcast_importador_lock' );
	if ( $ahora - $t > 600 ) { // bloqueo caducado (una pasada nunca dura 10 minutos)
		update_option( 'tt_podcast_importador_lock', $ahora, false );
		return true;
	}
	return false;
}

function tt_pod_lock_soltar() {
	delete_option( 'tt_podcast_importador_lock' );
}

/* =====================================================================
 * 6. LECTURA Y ANÁLISIS DEL FEED
 * ===================================================================== */

function tt_pod_descubrir_canales( $solo_id = 0 ) {
	$canales = array();
	foreach ( tt_pod_pares() as $clave => $par ) {
		foreach ( tt_pod_db_canales_con_feed( $par['feed'] ) as $id ) {
			$id   = (int) $id;
			$feed = trim( (string) get_post_meta( $id, $par['feed'], true ) );
			if ( '' === $feed || ( $solo_id && $id !== (int) $solo_id ) ) {
				continue;
			}
			$canales[ $id ] = array(
				'id'     => $id,
				'clave'  => $clave,
				'par'    => $par,
				'feed'   => esc_url_raw( $feed ),
				'titulo' => get_the_title( $id ),
				'tipo'   => get_post_type( $id ),
				'estado' => get_post_status( $id ),
				'slug'   => (string) get_post_field( 'post_name', $id ),
			);
		}
	}
	return $canales;
}

function tt_pod_leer_feed( $url ) {
	include_once ABSPATH . WPINC . '/feed.php';
	$opciones = function ( $feed ) {
		$feed->enable_cache( false ); // siempre datos frescos
		$feed->set_timeout( 25 );
	};
	add_action( 'wp_feed_options', $opciones );
	$feed = fetch_feed( $url );
	remove_action( 'wp_feed_options', $opciones );
	return $feed;
}

/** Convierte el feed en una lista normalizada. Devuelve caratula_url, episodios y avisos. */
function tt_pod_parsear_feed( $feed ) {
	$ns  = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
	$res = array( 'caratula_url' => '', 'episodios' => array(), 'avisos' => array() );

	$img                 = $feed->get_image_url();
	$res['caratula_url'] = $img ? esc_url_raw( $img ) : '';

	$vistos = array();
	foreach ( (array) $feed->get_items() as $item ) {
		$titulo = tt_pod_texto( $item->get_title() );
		if ( '' === $titulo ) {
			$titulo = 'Sin título';
		}

		$enc   = $item->get_enclosure();
		$audio = $enc ? esc_url_raw( (string) $enc->get_link() ) : '';
		if ( '' === $audio ) {
			$res['avisos'][] = 'Sin audio, no se importa: «' . $titulo . '»';
			continue;
		}

		$guid     = trim( (string) $item->get_id() );
		$tag_guid = $item->get_item_tags( '', 'guid' );
		if ( empty( $tag_guid ) ) {
			$res['avisos'][] = 'Sin GUID en el feed (clave derivada, frágil): «' . $titulo . '»';
		}
		if ( '' === $guid || isset( $vistos[ $guid ] ) ) {
			$res['avisos'][] = 'GUID repetido o vacío, se ignora: «' . $titulo . '»';
			continue;
		}
		$vistos[ $guid ] = true;

		$raw       = (string) $item->get_description();
		$contenido = wp_kses_post( trim( $raw ) );
		if ( '' === trim( wp_strip_all_tags( $contenido ) ) ) {
			$sum = $item->get_item_tags( $ns, 'summary' );
			if ( ! empty( $sum[0]['data'] ) ) {
				$raw       = (string) $sum[0]['data'];
				$contenido = wp_kses_post( trim( $raw ) );
			}
		}

		$ts = $item->get_date( 'U' );
		if ( ! $ts ) {
			$res['avisos'][] = 'Sin fecha, se usa la de ahora: «' . $titulo . '»';
			$ts              = time();
		}

		$t_ep  = $item->get_item_tags( $ns, 'episode' );
		$t_te  = $item->get_item_tags( $ns, 'season' );
		$t_du  = $item->get_item_tags( $ns, 'duration' );
		$t_im  = $item->get_item_tags( $ns, 'image' );
		$temp  = isset( $t_te[0]['data'] ) ? absint( trim( $t_te[0]['data'] ) ) : 0;
		$imagen = '';
		if ( isset( $t_im[0]['attribs']['']['href'] ) ) {
			$imagen = esc_url_raw( $t_im[0]['attribs']['']['href'] );
		}

		$res['episodios'][] = array(
			'guid'      => $guid,
			'titulo'    => $titulo,
			'contenido' => $contenido,
			'ts'        => (int) $ts,
			'audio'     => $audio,
			'youtube'   => tt_pod_youtube( $raw ),
			'imagen'    => $imagen,
			'duracion'  => tt_pod_duracion( isset( $t_du[0]['data'] ) ? $t_du[0]['data'] : '' ),
			'numero'    => isset( $t_ep[0]['data'] ) ? absint( trim( $t_ep[0]['data'] ) ) : 0,
			'temporada' => $temp,
		);
	}

	tt_pod_resolver_temporadas( $res );
	tt_pod_numerar_faltantes( $res );
	return $res;
}

/** Un episodio sin temporada en el feed hereda la de sus vecinos por fecha. Siempre deja un aviso. */
function tt_pod_resolver_temporadas( &$res ) {
	$eps = &$res['episodios'];
	$idx = array_keys( $eps );
	usort( $idx, function ( $a, $b ) use ( &$eps ) {
		return $eps[ $a ]['ts'] <=> $eps[ $b ]['ts'];
	} );
	$n = count( $idx );
	foreach ( $idx as $pos => $i ) {
		if ( $eps[ $i ]['temporada'] > 0 ) {
			continue;
		}
		$vieja = 0;
		for ( $k = $pos - 1; $k >= 0; $k-- ) {
			if ( $eps[ $idx[ $k ] ]['temporada'] > 0 ) { $vieja = $eps[ $idx[ $k ] ]['temporada']; break; }
		}
		$nueva = 0;
		for ( $k = $pos + 1; $k < $n; $k++ ) {
			if ( $eps[ $idx[ $k ] ]['temporada'] > 0 ) { $nueva = $eps[ $idx[ $k ] ]['temporada']; break; }
		}
		if ( $vieja && $nueva && $vieja === $nueva ) {
			$t = $vieja; $como = 'sus vecinos de fecha coinciden';
		} elseif ( $vieja ) {
			$t = $vieja; $como = 'la del episodio anterior por fecha';
		} elseif ( $nueva ) {
			$t = $nueva; $como = 'la del episodio siguiente por fecha';
		} else {
			$t = 1; $como = 'no hay vecinos con temporada';
		}
		$eps[ $i ]['temporada']    = $t;
		$res['avisos'][] = 'Sin temporada en el feed, se asigna la ' . $t . ' (' . $como . '): «' . $eps[ $i ]['titulo'] . '». Conviene corregirlo en Spotify.';
	}
}

/** Los episodios sin número ocupan el hueco entre sus vecinos de fecha; si no hay hueco, se numeran a continuación. */
function tt_pod_numerar_faltantes( &$res ) {
	$grupos = array();
	foreach ( $res['episodios'] as $i => $ep ) {
		$grupos[ $ep['temporada'] ][] = $i;
	}
	foreach ( $grupos as $t => $indices ) {
		usort( $indices, function ( $a, $b ) use ( $res ) {
			return $res['episodios'][ $a ]['ts'] <=> $res['episodios'][ $b ]['ts'];
		} );
		$originales = array();
		$max        = 0;
		foreach ( $indices as $i ) {
			$originales[ $i ] = $res['episodios'][ $i ]['numero'];
			$max              = max( $max, $res['episodios'][ $i ]['numero'] );
		}
		$total = 0;
		$n     = count( $indices );
		foreach ( $indices as $pos => $i ) {
			if ( $originales[ $i ] > 0 ) {
				continue;
			}
			$a = 0;
			for ( $k = $pos - 1; $k >= 0; $k-- ) {
				if ( $originales[ $indices[ $k ] ] > 0 ) { $a = $originales[ $indices[ $k ] ]; break; }
			}
			$b = 0;
			for ( $k = $pos + 1; $k < $n; $k++ ) {
				if ( $originales[ $indices[ $k ] ] > 0 ) { $b = $originales[ $indices[ $k ] ]; break; }
			}
			if ( $a && $b && ( $b - $a ) === 2 ) {
				$res['episodios'][ $i ]['numero'] = $a + 1;
			} else {
				$res['episodios'][ $i ]['numero'] = ++$max;
			}
			$total++;
		}
		if ( $total ) {
			$res['avisos'][] = 'Temporada ' . $t . ': ' . $total . ' episodio(s) sin número en el feed; se numeran por sus vecinos de fecha (o a continuación).';
		}
	}
}

/* =====================================================================
 * 7. ESCRITURA: carátula, término, temporadas, episodios
 * ===================================================================== */

function tt_pod_descargar_imagen( $url, $padre, $descripcion, $nombre_base ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = download_url( $url, 30 );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}
	$ruta = wp_parse_url( $url, PHP_URL_PATH );
	$ext  = strtolower( pathinfo( (string) $ruta, PATHINFO_EXTENSION ) );
	if ( ! in_array( $ext, array( 'jpg', 'jpeg', 'png', 'gif', 'webp' ), true ) ) {
		$info = @getimagesize( $tmp );
		$mapa = array( 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp' );
		$ext  = ( $info && isset( $mapa[ $info['mime'] ] ) ) ? $mapa[ $info['mime'] ] : 'jpg';
	}
	$id = media_handle_sideload(
		array( 'name' => sanitize_file_name( $nombre_base . '.' . $ext ), 'tmp_name' => $tmp ),
		$padre,
		$descripcion
	);
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return 0;
	}
	return (int) $id;
}

/** La carátula del canal: si la casilla ya tiene imagen, se respeta; si no, se descarga la del feed UNA vez. */
function tt_pod_asegurar_caratula( $canal, $url ) {
	$par = $canal['par'];
	$id  = (int) get_post_meta( $canal['id'], $par['caratula'], true );
	if ( $id && get_post( $id ) ) {
		return $id;
	}
	if ( '' === $url ) {
		return 0;
	}
	$id = tt_pod_descargar_imagen( $url, $canal['id'], $canal['titulo'] . ' — carátula del podcast', 'podcast-' . $canal['slug'] );
	if ( $id ) {
		tt_pod_uf( $par['caratula'], $id, $canal['id'] );
	}
	return $id;
}

function tt_pod_asegurar_termino( $canal ) {
	$cfg      = tt_pod_cfg();
	$guardado = (int) get_post_meta( $canal['id'], '_tt_pod_term', true );
	if ( $guardado && term_exists( $guardado, $cfg['tax_canal'] ) ) {
		return $guardado; // el término del canal no depende del slug actual de su entrada
	}
	$slug = sanitize_title( $canal['slug'] );
	$t    = term_exists( $slug, $cfg['tax_canal'] );
	if ( ! $t ) {
		$t = wp_insert_term( $canal['titulo'], $cfg['tax_canal'], array( 'slug' => $slug ) );
	}
	if ( is_wp_error( $t ) ) {
		$existente = $t->get_error_data( 'term_exists' );
		$id        = $existente ? (int) $existente : 0;
	} else {
		$id = (int) ( is_array( $t ) ? $t['term_id'] : $t );
	}
	if ( $id ) {
		update_post_meta( $canal['id'], '_tt_pod_term', $id );
	}
	return $id;
}

function tt_pod_marcar_canal( $id, $canal_id ) {
	if ( (int) get_post_meta( $id, '_tt_pod_canal', true ) !== (int) $canal_id ) {
		update_post_meta( $id, '_tt_pod_canal', (int) $canal_id );
	}
}

/** Busca la temporada N del canal; si no existe, la crea con valores iniciales (título, portada). */
function tt_pod_asegurar_temporada( $canal, $n, $cover_id ) {
	$cfg   = tt_pod_cfg();
	$clave = $canal['id'] . ':' . $n;
	$id    = tt_pod_db_temporada_por_clave( $clave, $cfg['cpt_temporada'] );
	if ( $id ) {
		return array( $id, false );
	}
	$nuevo = wp_insert_post( wp_slash( array(
		'post_type'   => $cfg['cpt_temporada'],
		'post_status' => 'publish',
		'post_title'  => 'Temporada ' . $n,
		'post_name'   => sanitize_title( $canal['slug'] ) . '-temporada-' . $n,
		'menu_order'  => $n,
	) ), true );
	if ( is_wp_error( $nuevo ) || ! $nuevo ) {
		return array( 0, false );
	}
	update_post_meta( $nuevo, '_tt_pod_clave', $clave );
	tt_pod_uf( 'tmp_numero', $n, $nuevo );
	tt_pod_uf( 'tmp_canal', $canal['id'], $nuevo );
	tt_pod_uf( 'tmp_canal_url', esc_url_raw( (string) get_permalink( $canal['id'] ) ), $nuevo );
	tt_pod_uf( 'tmp_canal_nombre', $canal['titulo'], $nuevo );
	if ( $cover_id ) {
		set_post_thumbnail( $nuevo, $cover_id );
	}
	return array( (int) $nuevo, true );
}

/**
 * Crea o actualiza un episodio.
 * $ex = array( id, hash, estado ) si ya existe. Devuelve array( resultado, mensaje ).
 */
function tt_pod_upsert_episodio( $ep, $ctx, $ex ) {
	$cfg     = tt_pod_cfg();
	$gmt     = gmdate( 'Y-m-d H:i:s', $ep['ts'] );
	$local   = get_date_from_gmt( $gmt );
	$temp_id = isset( $ctx['temporadas'][ $ep['temporada'] ] ) ? (int) $ctx['temporadas'][ $ep['temporada'] ] : 0;
	$temp_url = isset( $ctx['temp_urls'][ $ep['temporada'] ] ) ? (string) $ctx['temp_urls'][ $ep['temporada'] ] : '';
	$hash    = tt_pod_hash( $ep['titulo'], $ep['contenido'], $gmt, $ep['youtube'], $ep['duracion'], $ep['numero'], $ep['temporada'], $ctx['cover_id'], $temp_id );

	if ( $ex ) {
		$id = (int) $ex['id'];
		tt_pod_marcar_canal( $id, $ctx['canal']['id'] );
		if ( $ex['hash'] === $hash ) {
			// Sin cambios de contenido: solo se refrescan las direcciones que Spotify rota.
			$tocado = false;
			$tocado = tt_pod_uf_si_cambia( $id, 'ep_audio_url', $ep['audio'] ) || $tocado;
			$tocado = tt_pod_uf_si_cambia( $id, 'ep_imagen_url', $ep['imagen'] ) || $tocado;
			$tocado = tt_pod_uf_si_cambia( $id, 'ep_texto', $ep['contenido'] ) || $tocado;
			$tocado = tt_pod_uf_si_cambia( $id, 'ep_temporada_url', $temp_url ) || $tocado;
			return array( $tocado ? 'refrescado' : 'sin_cambios', '' );
		}
		$r = wp_update_post( wp_slash( array(
			'ID'            => $id,
			'post_title'    => $ep['titulo'],
			'post_content'  => $ep['contenido'],
			'post_date'     => $local,
			'post_date_gmt' => $gmt,
			'menu_order'    => $ep['numero'],
		) ), true );
		if ( is_wp_error( $r ) || ! $r ) {
			return array( 'error', 'No se pudo actualizar «' . $ep['titulo'] . '»' );
		}
		$res = 'actualizado';
	} else {
		$id = wp_insert_post( wp_slash( array(
			'post_type'     => $cfg['cpt_episodio'],
			'post_status'   => 'publish',
			'post_title'    => $ep['titulo'],
			'post_content'  => $ep['contenido'],
			'post_name'     => tt_pod_slug_episodio( $ep['titulo'], $ep['guid'], $cfg['cpt_episodio'] ),
			'post_date'     => $local,
			'post_date_gmt' => $gmt,
			'menu_order'    => $ep['numero'],
		) ), true );
		if ( is_wp_error( $id ) || ! $id ) {
			return array( 'error', 'No se pudo crear «' . $ep['titulo'] . '»' );
		}
		$id  = (int) $id;
		tt_pod_marcar_canal( $id, $ctx['canal']['id'] );
		$res = 'creado';
	}

	tt_pod_uf( 'ep_guid', $ep['guid'], $id );
	tt_pod_uf( 'ep_audio_url', $ep['audio'], $id );
	tt_pod_uf( 'ep_youtube_url', $ep['youtube'], $id );
	tt_pod_uf( 'ep_imagen_url', $ep['imagen'], $id );
	tt_pod_uf( 'ep_duracion', $ep['duracion'], $id );
	tt_pod_uf( 'ep_numero', (int) $ep['numero'], $id );
	tt_pod_uf( 'ep_temporada', (int) $ep['temporada'], $id );
	tt_pod_uf( 'ep_temporada_ref', $temp_id, $id );
	tt_pod_uf( 'ep_temporada_url', $temp_url, $id );
	tt_pod_uf( 'ep_texto', $ep['contenido'], $id );
	if ( $ctx['term_id'] ) {
		wp_set_object_terms( $id, array( (int) $ctx['term_id'] ), $cfg['tax_canal'] );
	}
	if ( $ctx['cover_id'] && (int) get_post_thumbnail_id( $id ) !== (int) $ctx['cover_id'] ) {
		set_post_thumbnail( $id, $ctx['cover_id'] );
	}
	tt_pod_uf( 'ep_hash', $hash, $id ); // el hash se guarda el último: si algo falla antes, se reintenta
	return array( $res, '' );
}

/** Relaciones a partir del estado real de la base de datos (no del feed). */
function tt_pod_actualizar_relaciones( $canal, &$r ) {
	$cfg  = tt_pod_cfg();
	$par  = $canal['par'];
	$filas = tt_pod_db_episodios_del_canal( $canal['id'] );

	$todos = array();
	$por_t = array();
	foreach ( $filas as $f ) {
		$todos[]                    = $f['id'];
		$por_t[ $f['temporada'] ][] = $f['id'];
	}
	ksort( $por_t );

	$temporadas_ids = array();
	foreach ( $por_t as $n => $ids ) {
		$sid = tt_pod_db_temporada_por_clave( $canal['id'] . ':' . $n, $cfg['cpt_temporada'] );
		if ( ! $sid || 'publish' !== get_post_status( $sid ) ) {
			$r['avisos'][] = 'La temporada ' . $n . ' no está publicada o no existe; no se enlaza al canal.';
			continue;
		}
		$temporadas_ids[] = $sid;
		tt_pod_uf_relacion( $sid, 'tmp_episodios', $ids );
		tt_pod_uf_si_cambia( $sid, 'tmp_canal_url', esc_url_raw( (string) get_permalink( $canal['id'] ) ) );
		tt_pod_uf_si_cambia( $sid, 'tmp_canal_nombre', $canal['titulo'] );
	}
	tt_pod_uf_relacion( $canal['id'], $par['temporadas'], $temporadas_ids );
	tt_pod_uf_relacion( $canal['id'], $par['episodios'], $todos );
	$r['temporadas'] = count( $temporadas_ids );
	$r['total']      = count( $todos );
}

/* =====================================================================
 * 8. SINCRONIZACIÓN
 * ===================================================================== */

function tt_pod_sync_canal( $canal, $limite ) {
	$cfg = tt_pod_cfg();
	$t0  = microtime( true );
	$r   = array(
		'id' => $canal['id'], 'titulo' => $canal['titulo'], 'feed' => $canal['feed'],
		'estado' => 'ok', 'mensaje' => '', 'fin' => time(), 'episodios_feed' => 0,
		'creados' => 0, 'actualizados' => 0, 'refrescados' => 0, 'sin_cambios' => 0, 'omitidos' => 0,
		'errores' => 0, 'temporadas' => 0, 'total' => 0, 'pendiente' => false, 'avisos' => array(), 'segundos' => 0,
	);

	$feed = tt_pod_leer_feed( $canal['feed'] );
	if ( is_wp_error( $feed ) ) {
		$r['estado']  = 'error';
		$r['mensaje'] = 'No se pudo leer el feed: ' . $feed->get_error_message();
		$r['errores']++;
		return $r;
	}
	$p = tt_pod_parsear_feed( $feed );
	$r['avisos']         = $p['avisos'];
	$r['episodios_feed'] = count( $p['episodios'] );
	if ( ! $p['episodios'] ) {
		$r['estado']  = 'error';
		$r['mensaje'] = 'El feed no trae episodios con audio: no se toca nada.';
		$r['errores']++;
		return $r;
	}

	$term_id = tt_pod_asegurar_termino( $canal );
	if ( ! $term_id ) {
		$r['estado']  = 'error';
		$r['mensaje'] = 'No se pudo crear o encontrar el término de la taxonomía «' . $cfg['tax_canal'] . '». ¿Existe la taxonomía y está unida al tipo Episodio?';
		$r['errores']++;
		return $r;
	}
	$tenia_cover = (int) get_post_meta( $canal['id'], $canal['par']['caratula'], true );
	$cover_id    = tt_pod_asegurar_caratula( $canal, $p['caratula_url'] );
	if ( ! $tenia_cover && $cover_id ) {
		// La descarga de la carátula (única y lenta) no se descuenta del tiempo de los episodios.
		$limite = microtime( true ) + (float) $cfg['presupuesto'];
	}

	$temps = array();
	foreach ( $p['episodios'] as $ep ) {
		$temps[ $ep['temporada'] ] = 0;
	}
	ksort( $temps );
	foreach ( array_keys( $temps ) as $n ) {
		list( $sid ) = tt_pod_asegurar_temporada( $canal, $n, $cover_id );
		if ( ! $sid ) {
			$r['estado']  = 'error';
			$r['mensaje'] = 'No se pudo crear la temporada ' . $n . '. ¿Existe el tipo «' . $cfg['cpt_temporada'] . '»?';
			$r['errores']++;
			return $r;
		}
		$temps[ $n ] = $sid;
	}

	$temp_urls = array();
	foreach ( $temps as $n => $sid ) {
		$temp_urls[ $n ] = esc_url_raw( (string) get_permalink( $sid ) );
	}
	$ctx   = array( 'canal' => $canal, 'term_id' => $term_id, 'cover_id' => $cover_id, 'temporadas' => $temps, 'temp_urls' => $temp_urls );
	$mapa  = tt_pod_db_mapa_episodios( $cfg['cpt_episodio'], $term_id, $canal['id'] );
	foreach ( $mapa as $m ) { // adopción de los episodios de la v1.0
		if ( ! empty( $m['legacy'] ) && 'trash' !== $m['estado'] ) {
			tt_pod_marcar_canal( $m['id'], $canal['id'] );
		}
	}
	foreach ( $p['episodios'] as $ep ) {
		// Se para al agotar el tiempo, pero SIEMPRE tras haber escrito al menos un episodio:
		// así cada pasada avanza aunque el feed sea enorme (no puede quedarse atascada).
		if ( ( $r['creados'] + $r['actualizados'] ) > 0 && microtime( true ) > $limite ) {
			$r['pendiente'] = true;
			break;
		}
		$ex = isset( $mapa[ $ep['guid'] ] ) ? $mapa[ $ep['guid'] ] : null;
		if ( $ex && 'trash' === $ex['estado'] ) {
			$r['omitidos']++;
			continue;
		}
		list( $res, $msg ) = tt_pod_upsert_episodio( $ep, $ctx, $ex );
		switch ( $res ) {
			case 'creado':     $r['creados']++;      break;
			case 'actualizado': $r['actualizados']++; break;
			case 'refrescado': $r['refrescados']++;  break;
			case 'sin_cambios': $r['sin_cambios']++; break;
			default:
				$r['errores']++;
				$r['avisos'][] = $msg;
		}
	}

	tt_pod_actualizar_relaciones( $canal, $r );

	// Episodios que ya no están en el feed: solo se avisa, nunca se borra.
	$en_feed = array();
	foreach ( $p['episodios'] as $ep ) {
		$en_feed[ $ep['guid'] ] = true;
	}
	$huerfanos = 0;
	foreach ( $mapa as $guid => $m ) {
		if ( ! isset( $en_feed[ $guid ] ) && 'publish' === $m['estado'] ) {
			$huerfanos++;
		}
	}
	if ( $huerfanos ) {
		$r['avisos'][] = $huerfanos . ' episodio(s) publicado(s) ya no están en el feed. No se han tocado.';
	}

	if ( $r['errores'] ) {
		$r['estado'] = 'con_errores';
	} elseif ( $r['pendiente'] ) {
		$r['estado'] = 'pendiente';
	}
	$r['fin']      = time();
	$r['segundos'] = round( microtime( true ) - $t0, 1 );
	return $r;
}

function tt_pod_sync_todo( $origen = 'manual', $solo_id = 0 ) {
	$res = array(
		'inicio' => time(), 'fin' => time(), 'origen' => $origen, 'estado' => 'ok', 'mensaje' => '',
		'canales' => array(), 'creados' => 0, 'actualizados' => 0, 'sin_cambios' => 0, 'errores' => 0, 'pendiente' => false,
	);
	if ( ! function_exists( 'update_field' ) ) {
		$res['estado']  = 'error';
		$res['mensaje'] = 'ACF no está activo: no se puede escribir en las casillas.';
		$res['errores'] = 1;
		tt_pod_log_ejecucion( $res );
		return $res;
	}
	if ( ! tt_pod_lock_tomar() ) {
		$res['estado']  = 'ocupado';
		$res['mensaje'] = 'Ya hay una sincronización en marcha. Espera unos minutos.';
		return $res;
	}
	try {
		$cfg = tt_pod_cfg();
		@set_time_limit( 120 );
		$limite = microtime( true ) + (float) $cfg['presupuesto'];
		tt_pod_campos_acf( true );
		$canales = tt_pod_descubrir_canales( $solo_id );
		if ( ! $canales ) {
			$res['mensaje'] = 'No hay ningún canal con la casilla de feed rellena.';
		}
		$hechos = 0;
		foreach ( $canales as $canal ) {
			if ( $hechos > 0 && microtime( true ) > $limite ) { // siempre se procesa al menos un canal por pasada
				$res['pendiente'] = true;
				continue;
			}
			$hechos++;
			$r = tt_pod_sync_canal( $canal, $limite );
			tt_pod_log_canal( $canal['id'], $r );
			$res['canales'][ $canal['id'] ] = array( 'titulo' => $r['titulo'], 'estado' => $r['estado'], 'mensaje' => $r['mensaje'] );
			$res['creados']      += $r['creados'];
			$res['actualizados'] += $r['actualizados'];
			$res['sin_cambios']  += $r['sin_cambios'];
			$res['errores']      += $r['errores'];
			if ( $r['pendiente'] ) {
				$res['pendiente'] = true;
			}
		}
	} catch ( \Throwable $e ) {
		$res['estado']  = 'error';
		$res['mensaje'] = 'Fallo inesperado: ' . $e->getMessage();
		$res['errores']++;
	} finally {
		tt_pod_lock_soltar();
	}
	if ( 'error' !== $res['estado'] ) {
		$res['estado'] = $res['errores'] ? 'con_errores' : ( $res['pendiente'] ? 'pendiente' : 'ok' );
	}
	if ( $res['pendiente'] && ! wp_next_scheduled( 'tt_pod_sync_continuar' ) ) {
		$cfg2 = tt_pod_cfg();
		wp_schedule_single_event( time() + (int) $cfg2['espera_cont'], 'tt_pod_sync_continuar' );
	}
	$res['fin'] = time();
	tt_pod_log_ejecucion( $res );
	return $res;
}

/* =====================================================================
 * 9. PROGRAMACIÓN (WP-Cron) Y AJUSTES
 * ===================================================================== */

function tt_pod_proxima_hora() {
	$cfg = tt_pod_cfg();
	$d   = new DateTime( 'today ' . $cfg['hora_diaria'], wp_timezone() );
	if ( $d->getTimestamp() <= time() ) {
		$d->modify( '+1 day' );
	}
	return $d->getTimestamp();
}

function tt_pod_programar() {
	$activo = ( 'no' !== get_option( 'tt_podcast_importador_cron', 'si' ) );
	$prox   = wp_next_scheduled( 'tt_pod_sync_diaria' );
	if ( $activo && ! $prox ) {
		wp_schedule_event( tt_pod_proxima_hora(), 'daily', 'tt_pod_sync_diaria' );
	} elseif ( ! $activo && $prox ) {
		wp_clear_scheduled_hook( 'tt_pod_sync_diaria' );
	}
}

add_action( 'tt_pod_sync_diaria', function () {
	tt_pod_sync_todo( 'cron' );
} );
add_action( 'tt_pod_sync_continuar', function () {
	tt_pod_sync_todo( 'cron' );
} );
if ( did_action( 'init' ) ) {
	tt_pod_programar();
} else {
	add_action( 'init', 'tt_pod_programar' );
}

// Los episodios no los edita nadie a mano: sin revisiones (requisito 6 del informe).
add_filter( 'wp_revisions_to_keep', function ( $num, $post ) {
	$cfg = tt_pod_cfg();
	return ( $post && $cfg['cpt_episodio'] === $post->post_type ) ? 0 : $num;
}, 10, 2 );

/* =====================================================================
 * 10. PANTALLA DE ADMINISTRACIÓN (Herramientas → Podcast — Importador)
 * ===================================================================== */

function tt_pod_diagnostico() {
	$cfg  = tt_pod_cfg();
	$f    = array();
	$ok   = function ( $texto ) use ( &$f ) { $f[] = array( 'ok', $texto ); };
	$mal  = function ( $texto ) use ( &$f ) { $f[] = array( 'mal', $texto ); };
	$aviso = function ( $texto ) use ( &$f ) { $f[] = array( 'aviso', $texto ); };

	function_exists( 'update_field' ) ? $ok( 'ACF está activo.' ) : $mal( 'ACF no está activo.' );

	foreach ( array( 'cpt_episodio' => 'Episodio', 'cpt_temporada' => 'Temporada' ) as $k => $nom ) {
		if ( ! post_type_exists( $cfg[ $k ] ) ) {
			$mal( 'No existe el tipo de contenido «' . $cfg[ $k ] . '» (' . $nom . ').' );
			continue;
		}
		$ok( 'Existe el tipo «' . $cfg[ $k ] . '» (' . $nom . ').' );
		post_type_supports( $cfg[ $k ], 'thumbnail' )
			? $ok( 'El tipo «' . $cfg[ $k ] . '» admite imagen destacada.' )
			: $mal( 'El tipo «' . $cfg[ $k ] . '» NO admite imagen destacada: actívala en su definición.' );
	}
	if ( ! taxonomy_exists( $cfg['tax_canal'] ) ) {
		$mal( 'No existe la taxonomía «' . $cfg['tax_canal'] . '» (Canal).' );
	} else {
		is_object_in_taxonomy( $cfg['cpt_episodio'], $cfg['tax_canal'] )
			? $ok( 'La taxonomía «' . $cfg['tax_canal'] . '» está unida al tipo Episodio.' )
			: $mal( 'La taxonomía «' . $cfg['tax_canal'] . '» NO está unida al tipo Episodio.' );
	}

	$campos = tt_pod_campos_acf();
	foreach ( tt_pod_pares() as $clave => $par ) {
		if ( ! isset( $campos[ $par['feed'] ] ) && ! isset( $campos[ $par['temporadas'] ] ) ) {
			$aviso( 'Juego de casillas «' . $clave . '_*» sin crear (solo es un problema si hay canales de ese tipo).' );
		}
	}
	foreach ( tt_pod_campos_requeridos() as $nombre => $tipos ) {
		if ( ! isset( $campos[ $nombre ] ) ) {
			// Las casillas de un juego de canal sin crear ya se han avisado arriba.
			$es_canal = false;
			foreach ( tt_pod_pares() as $par ) {
				if ( in_array( $nombre, $par, true ) ) { $es_canal = true; }
			}
			if ( ! $es_canal ) {
				$mal( 'Falta la casilla «' . $nombre . '».' );
			}
		} elseif ( ! in_array( $campos[ $nombre ]['type'], $tipos, true ) ) {
			$mal( 'La casilla «' . $nombre . '» está creada como «' . $campos[ $nombre ]['type'] . '» y debería ser «' . implode( '» o «', $tipos ) . '».' );
		}
	}

	if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
		$aviso( 'WP-Cron está desactivado en wp-config.php: la sincronización diaria solo funcionará si hay un cron real del servidor.' );
	} else {
		$ok( 'WP-Cron está activo.' );
	}
	return $f;
}

function tt_pod_hora( $ts ) {
	return $ts ? wp_date( 'd/m/Y H:i', (int) $ts ) : '—';
}

function tt_pod_menu() {
	add_management_page( 'Podcast — Importador', 'Podcast — Importador', 'manage_options', 'tt-podcast-importador', 'tt_pod_pagina' );
}
add_action( 'admin_menu', 'tt_pod_menu' );
add_action( 'load-tools_page_tt-podcast-importador', 'tt_pod_manejar_post' );

function tt_pod_manejar_post() {
	if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) || empty( $_POST['tt_pod_accion'] ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'No tienes permiso para hacer esto.' );
	}
	check_admin_referer( 'tt_pod_accion' );
	$accion = sanitize_key( wp_unslash( $_POST['tt_pod_accion'] ) );
	$msg    = '';
	if ( 'sync_todo' === $accion || 'sync_canal' === $accion ) {
		$id  = ( 'sync_canal' === $accion && isset( $_POST['canal_id'] ) ) ? absint( $_POST['canal_id'] ) : 0;
		$res = tt_pod_sync_todo( 'manual', $id );
		if ( 'ocupado' === $res['estado'] || 'error' === $res['estado'] ) {
			$msg = $res['mensaje'];
		} else {
			$msg = sprintf( 'Sincronización terminada (%s): %d creados, %d actualizados, %d sin cambios, %d errores.%s',
				$res['estado'], $res['creados'], $res['actualizados'], $res['sin_cambios'], $res['errores'],
				$res['pendiente'] ? ' Queda trabajo pendiente: continuará solo en un minuto, o pulsa de nuevo.' : '' );
			if ( '' !== $res['mensaje'] ) {
				$msg .= ' ' . $res['mensaje'];
			}
		}
	} elseif ( 'cron' === $accion ) {
		update_option( 'tt_podcast_importador_cron', empty( $_POST['cron_activo'] ) ? 'no' : 'si', false );
		tt_pod_programar();
		$msg = 'Ajuste de sincronización diaria guardado.';
	} elseif ( 'liberar' === $accion ) {
		tt_pod_lock_soltar();
		$msg = 'Bloqueo liberado.';
	}
	set_transient( 'tt_pod_aviso_' . get_current_user_id(), $msg, 120 );
	wp_safe_redirect( admin_url( 'tools.php?page=tt-podcast-importador' ) );
	exit;
}

function tt_pod_pagina() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$log   = tt_pod_log_base( tt_pod_log_get() );
	$pod   = $log['podcast'];
	$clave = 'tt_pod_aviso_' . get_current_user_id();
	$aviso = get_transient( $clave );
	if ( $aviso ) {
		delete_transient( $clave );
	}
	$iconos = array( 'ok' => '✅', 'mal' => '❌', 'aviso' => '⚠️' );
	$prox   = wp_next_scheduled( 'tt_pod_sync_diaria' );
	$activo = ( 'no' !== get_option( 'tt_podcast_importador_cron', 'si' ) );
	$lock   = (int) get_option( 'tt_podcast_importador_lock' );

	echo '<div class="wrap"><h1>Podcast — Importador</h1>';
	if ( $aviso ) {
		echo '<div class="notice notice-info is-dismissible"><p>' . esc_html( $aviso ) . '</p></div>';
	}

	echo '<h2>Estado</h2><ul>';
	foreach ( tt_pod_diagnostico() as $fila ) {
		echo '<li>' . esc_html( $iconos[ $fila[0] ] . ' ' . $fila[1] ) . '</li>';
	}
	echo '</ul>';

	echo '<h2>Sincronizar</h2>';
	echo '<form method="post" style="display:inline-block;margin-right:12px">';
	wp_nonce_field( 'tt_pod_accion' );
	echo '<input type="hidden" name="tt_pod_accion" value="sync_todo">';
	submit_button( 'Sincronizar ahora', 'primary', 'submit', false );
	echo '</form>';
	echo '<form method="post" style="display:inline-block">';
	wp_nonce_field( 'tt_pod_accion' );
	echo '<input type="hidden" name="tt_pod_accion" value="cron"><label>';
	echo '<input type="checkbox" name="cron_activo" value="1"' . checked( $activo, true, false ) . '> Sincronización diaria automática</label> ';
	submit_button( 'Guardar', 'secondary', 'submit', false );
	echo '</form>';
	echo '<p>Próxima sincronización automática: <strong>' . esc_html( $activo ? tt_pod_hora( $prox ) : 'desactivada' ) . '</strong>';
	if ( $lock ) {
		echo ' · ⏳ Hay una sincronización en marcha desde las ' . esc_html( tt_pod_hora( $lock ) ) . '. ';
		echo '<form method="post" style="display:inline">';
		wp_nonce_field( 'tt_pod_accion' );
		echo '<input type="hidden" name="tt_pod_accion" value="liberar">';
		submit_button( 'Liberar bloqueo', 'link', 'submit', false );
		echo '</form>';
	}
	echo '</p>';

	echo '<h2>Canales con feed</h2>';
	$canales = tt_pod_descubrir_canales();
	if ( ! $canales ) {
		echo '<p>Ningún canal tiene la casilla de feed rellena.</p>';
	} else {
		echo '<table class="widefat striped"><thead><tr><th>Canal</th><th>Feed</th><th>Última sincronización</th><th>Estado</th><th>Episodios (feed / web)</th><th>Temporadas</th><th></th></tr></thead><tbody>';
		foreach ( $canales as $c ) {
			$u = isset( $pod['canales'][ $c['id'] ] ) ? $pod['canales'][ $c['id'] ] : array();
			echo '<tr><td><a href="' . esc_url( get_edit_post_link( $c['id'] ) ) . '">' . esc_html( $c['titulo'] ) . '</a><br><small>' . esc_html( $c['tipo'] . ' · ' . $c['estado'] ) . '</small></td>';
			echo '<td><small>' . esc_html( $c['feed'] ) . '</small></td>';
			echo '<td>' . esc_html( tt_pod_hora( isset( $u['fin'] ) ? $u['fin'] : 0 ) ) . '</td>';
			echo '<td>' . esc_html( isset( $u['estado'] ) ? $u['estado'] : 'sin sincronizar' );
			if ( ! empty( $u['mensaje'] ) ) {
				echo '<br><small>' . esc_html( $u['mensaje'] ) . '</small>';
			}
			if ( ! empty( $u['avisos'] ) ) {
				echo '<br><small>' . esc_html( implode( ' · ', array_slice( $u['avisos'], 0, 4 ) ) ) . '</small>';
			}
			echo '</td>';
			echo '<td>' . esc_html( ( isset( $u['episodios_feed'] ) ? $u['episodios_feed'] : '—' ) . ' / ' . ( isset( $u['total'] ) ? $u['total'] : '—' ) ) . '</td>';
			echo '<td>' . esc_html( isset( $u['temporadas'] ) ? $u['temporadas'] : '—' ) . '</td>';
			echo '<td><form method="post">';
			wp_nonce_field( 'tt_pod_accion' );
			echo '<input type="hidden" name="tt_pod_accion" value="sync_canal"><input type="hidden" name="canal_id" value="' . (int) $c['id'] . '">';
			submit_button( 'Sincronizar solo este', 'small', 'submit', false );
			echo '</form></td></tr>';
		}
		echo '</tbody></table>';
	}

	echo '<h2>Última ejecución</h2>';
	if ( empty( $pod['ultima'] ) ) {
		echo '<p>Todavía no se ha ejecutado.</p>';
	} else {
		$u = $pod['ultima'];
		printf(
			'<p>%s · origen: %s · estado: <strong>%s</strong> · %d creados, %d actualizados, %d sin cambios, %d errores.%s</p>',
			esc_html( tt_pod_hora( $u['fin'] ) ), esc_html( $u['origen'] ), esc_html( $u['estado'] ),
			(int) $u['creados'], (int) $u['actualizados'], (int) $u['sin_cambios'], (int) $u['errores'],
			$u['mensaje'] ? ' ' . esc_html( $u['mensaje'] ) : ''
		);
	}
	if ( ! empty( $pod['historial'] ) ) {
		echo '<details><summary>Historial (últimas 20)</summary><ul>';
		foreach ( $pod['historial'] as $h ) {
			printf( '<li>%s · %s · %s · %d creados, %d actualizados, %d errores</li>',
				esc_html( tt_pod_hora( $h['fin'] ) ), esc_html( $h['origen'] ), esc_html( $h['estado'] ),
				(int) $h['creados'], (int) $h['actualizados'], (int) $h['errores'] );
		}
		echo '</ul></details>';
	}
	echo '</div>';
}

} // fin del bloque tt_pod_sync_todo

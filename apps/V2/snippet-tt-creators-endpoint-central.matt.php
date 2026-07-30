<?php
/*
  ⚠️ ARCHIVO DE TRABAJO — PLACEHOLDER ESTRUCTURAL, NO ES COPIA DEL PHP REAL
  ═══════════════════════════════════════════════════════════════════════
  Este archivo NO es una copia sanitizada del snippet PHP real del
  endpoint central. El código PHP completo y real vive únicamente en
  snippet-tt-creators-endpoint-central.php (fuera de esta copia de
  trabajo) y no se comparte aquí por razones de seguridad — expone
  la lógica completa de autenticación y el contrato exacto del
  backend en producción/Local.

  Lo que tienes aquí es una RECONSTRUCCIÓN DE REFERENCIA: la forma
  general de las rutas, verbos HTTP, y nombres de campo tal como los
  documenta el equipo (00_CORE.matt.md, TIRITAITO_FOR_CREATORS_VERSIONS.matt.md),
  para que puedas razonar sobre el contrato sin necesitar el código
  fuente real. Los valores de configuración (token, prefijos internos,
  validaciones exactas) son ficticios o simplificados.

  Si tu tarea requiere el comportamiento EXACTO del backend real
  (por ejemplo, para reproducir un bug), pídelo directamente al
  equipo — no asumas que este archivo lo replica con fidelidad.
  ═══════════════════════════════════════════════════════════════════════
*/

// ─── Configuración — PLACEHOLDER, no son valores reales ───
define('TT_WRITE_TOKEN', 'PLACEHOLDER_NO_ES_TOKEN_REAL');
define('TT_READ_TOKEN', 'PLACEHOLDER_TOKEN_LECTURA');

// ─── Verificación de autenticación de escritura ───
function tt_verificar_token_escritura(WP_REST_Request $request) {
    $token = $request->get_header('X-TT-Token');
    return $token === TT_WRITE_TOKEN;
}

/* ════════════════════════════════════════════════════════
   REGISTRO DE RUTAS — forma general, ver 00_CORE.matt.md
   Sección 4 para la tabla completa de endpoints documentados
   ════════════════════════════════════════════════════════ */
add_action('rest_api_init', function () {

    // GET/POST /tiritaito/v1/datos — Devocional (ACF Options Page) + wp_options
    register_rest_route('tiritaito/v1', '/datos', [
        [
            'methods'             => 'GET',
            'callback'            => 'tt_leer_datos',
            'permission_callback' => '__return_true', // pública
        ],
        [
            'methods'             => 'POST',
            'callback'            => 'tt_guardar_datos',
            'permission_callback' => 'tt_verificar_token_escritura',
        ],
    ]);

    // POST /tiritaito/v1/subir — subida a Biblioteca de Medios
    register_rest_route('tiritaito/v1', '/subir', [
        'methods'             => 'POST',
        'callback'            => 'tt_subir_archivo',
        'permission_callback' => 'tt_verificar_token_escritura',
    ]);

    // GET /tiritaito/v1/medios — listado de Biblioteca de Medios
    register_rest_route('tiritaito/v1', '/medios', [
        'methods'             => 'GET',
        'callback'            => 'tt_listar_medios',
        'permission_callback' => 'tt_verificar_token_escritura', // requiere token incluso para leer
    ]);

    // DELETE /tiritaito/v1/medio/{id}
    register_rest_route('tiritaito/v1', '/medio/(?P<id>\d+)', [
        'methods'             => 'DELETE',
        'callback'            => 'tt_eliminar_medio',
        'permission_callback' => 'tt_verificar_token_escritura',
    ]);

    // Novedades — CPT propio, ver TIRITAITO_FOR_CREATORS_VERSIONS.matt.md Sección 6
    register_rest_route('tiritaito/v1', '/novedades', [
        [
            'methods'             => 'GET',
            'callback'            => 'tt_listar_novedades',
            'permission_callback' => '__return_true', // pública, sin filtrar por 'activo'
        ],
        [
            'methods'             => 'POST',
            'callback'            => 'tt_crear_novedad',
            'permission_callback' => 'tt_verificar_token_escritura',
        ],
    ]);

    register_rest_route('tiritaito/v1', '/novedades/(?P<id>\d+)', [
        [
            'methods'             => 'PUT',
            'callback'            => 'tt_editar_novedad',
            'permission_callback' => 'tt_verificar_token_escritura',
        ],
        [
            'methods'             => 'DELETE',
            'callback'            => 'tt_eliminar_novedad',
            'permission_callback' => 'tt_verificar_token_escritura',
        ],
    ]);
});

/* ════════════════════════════════════════════════════════
   DEVOCIONAL — lectura/escritura de ACF Options Page
   ════════════════════════════════════════════════════════
   IMPORTANTE: escribir SIEMPRE con update_field(), nunca con
   update_option() directo — ver 00_CORE.matt.md Sección 8,
   trampa "ACF Options Page + update_option()".
   ════════════════════════════════════════════════════════ */

// Traduce entre el nombre interno de campo ACF (sin prefijo tt_)
// y la clave que usa la app (con prefijo tt_) — placeholder de
// la lógica real de tt_mapa_campos_devocional() mencionada en
// TIRITAITO_FOR_CREATORS_VERSIONS.matt.md Sección 7.
function tt_mapa_campos_devocional() {
    return [
        'tt_virgen'        => 'virgen',
        'tt_virgen_fecha'  => 'virgen_fecha',
        'tt_brisa'         => 'brisa',
        'tt_brisa_autor'   => 'brisa_autor',
        'tt_homilia_audio' => 'homilia_audio',
        'tt_homilia_texto' => 'homilia_texto',
        'tt_lenguas_url'   => 'lenguas_url',
    ];
}

function tt_leer_datos(WP_REST_Request $request) {
    $resultado = [];

    // Devocional — ACF Options Page
    foreach (tt_mapa_campos_devocional() as $clave_api => $campo_acf) {
        $resultado[$clave_api] = get_field($campo_acf, 'option');
    }

    // Resto de wp_options — ver 00_CORE.matt.md Sección 3.2
    $claves_wp_options = [
        'tt_docx_lectura_url',
        'tt_youtube_json_url',
        'tt_seminarios_json_url',
        'tt_viacrucis_json_url',
        'tt_fiesta_dias',
    ];
    foreach ($claves_wp_options as $clave) {
        $resultado[$clave] = get_option($clave, '');
    }

    return rest_ensure_response($resultado);
}

function tt_guardar_datos(WP_REST_Request $request) {
    $body = $request->get_json_params();
    $mapa = tt_mapa_campos_devocional();
    $rechazadas = [];

    foreach ($body as $clave => $valor) {
        if (isset($mapa[$clave])) {
            // Sanitizado por tipo de campo — placeholder simplificado.
            // El PHP real distingue texto largo / URL / fecha con regex,
            // ver 00_CORE.matt.md Sección 3.1 y TIRITAITO_FOR_CREATORS_VERSIONS.matt.md
            // Sección 7 para el detalle exacto por campo.
            $valor_limpio = sanitize_textarea_field($valor);
            update_field($mapa[$clave], $valor_limpio, 'option'); // NUNCA update_option() aquí
        } elseif (in_array($clave, ['tt_docx_lectura_url', 'tt_youtube_json_url', 'tt_seminarios_json_url', 'tt_viacrucis_json_url', 'tt_fiesta_dias'])) {
            update_option($clave, sanitize_text_field($valor));
        } else {
            $rechazadas[] = $clave; // clave no reconocida — no se guarda
        }
    }

    return rest_ensure_response(['ok' => true, 'rechazadas' => $rechazadas]);
}

/* ════════════════════════════════════════════════════════
   BIBLIOTECA DE MEDIOS
   ⚠️ Ver aviso abierto en TIRITAITO_FOR_CREATORS_VERSIONS.matt.md
   Sección 7.1: sin validación de tipo MIME ni tamaño máximo en
   el backend real actual — este placeholder refleja esa misma
   ausencia deliberadamente, para que la limitación sea visible.
   ════════════════════════════════════════════════════════ */
function tt_subir_archivo(WP_REST_Request $request) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // ⚠️ Sin comprobación de tipo MIME ni tamaño — mismo estado que
    // el backend real, ver aviso arriba.
    $id = media_handle_upload('file', 0);
    if (is_wp_error($id)) {
        return new WP_Error('tt_subida_fallida', $id->get_error_message(), ['status' => 500]);
    }
    return rest_ensure_response([
        'id'         => $id,
        'source_url' => wp_get_attachment_url($id),
    ]);
}

function tt_listar_medios(WP_REST_Request $request) {
    // Placeholder simplificado — el real pagina, filtra por tipo/búsqueda
    $args = [
        'post_type'      => 'attachment',
        'posts_per_page' => $request->get_param('per_page') ?: 24,
        'paged'          => $request->get_param('page') ?: 1,
    ];
    $query = new WP_Query($args);
    return rest_ensure_response($query->posts);
}

function tt_eliminar_medio(WP_REST_Request $request) {
    $id = (int) $request['id'];
    $resultado = wp_delete_attachment($id, true);
    if (!$resultado) {
        return new WP_Error('tt_no_encontrado', 'Archivo no encontrado', ['status' => 404]);
    }
    return rest_ensure_response(['ok' => true]);
}

/* ════════════════════════════════════════════════════════
   NOVEDADES — CPT propio, ver 00_CORE.matt.md Sección 3.3 y
   TIRITAITO_FOR_CREATORS_VERSIONS.matt.md Sección 6
   ════════════════════════════════════════════════════════ */

// Conversión de fecha entre formato ACF interno (Ymd) y el que
// usa la app (YYYY-MM-DD) — placeholder de tt_novedades_fecha_a_app()
// / tt_novedades_fecha_a_acf() mencionadas en la documentación.
function tt_novedades_fecha_a_app($fecha_ymd) {
    $dt = DateTime::createFromFormat('Ymd', $fecha_ymd);
    return $dt ? $dt->format('Y-m-d') : '';
}
function tt_novedades_fecha_a_acf($fecha_app) {
    $dt = DateTime::createFromFormat('Y-m-d', $fecha_app);
    return $dt ? $dt->format('Ymd') : '';
}

function tt_listar_novedades(WP_REST_Request $request) {
    // Pública, sin filtrar por 'activo' — decisión de equipo documentada
    $query = new WP_Query([
        'post_type'      => 'novedades',
        'posts_per_page' => -1,
        'orderby'        => 'meta_value',
        'meta_key'       => 'fecha',
        'order'          => 'DESC',
    ]);

    $resultado = [];
    foreach ($query->posts as $post) {
        $resultado[] = [
            'id'        => $post->ID,
            'tipo'      => get_field('tipo', $post->ID),
            'media_url' => get_field('media_url', $post->ID),
            'texto'     => get_field('texto', $post->ID),
            'enlace'    => get_field('enlace', $post->ID),
            'fecha'     => tt_novedades_fecha_a_app(get_field('fecha', $post->ID)),
            'activo'    => (bool) get_field('activo', $post->ID),
        ];
    }
    return rest_ensure_response($resultado);
}

function tt_crear_novedad(WP_REST_Request $request) {
    $body = $request->get_json_params();

    $titulo = !empty($body['titulo'])
        ? sanitize_text_field($body['titulo'])
        : 'Novedad ' . current_time('Y-m-d H:i');

    $post_id = wp_insert_post([
        'post_type'   => 'novedades',
        'post_title'  => $titulo,
        'post_status' => 'publish',
    ]);

    if (is_wp_error($post_id)) {
        return new WP_Error('tt_creacion_fallida', $post_id->get_error_message(), ['status' => 500]);
    }

    update_field('tipo', sanitize_text_field($body['tipo'] ?? ''), $post_id);
    update_field('media_url', esc_url_raw($body['media_url'] ?? ''), $post_id);
    update_field('texto', sanitize_textarea_field($body['texto'] ?? ''), $post_id);
    update_field('enlace', esc_url_raw($body['enlace'] ?? ''), $post_id);
    update_field('fecha', tt_novedades_fecha_a_acf($body['fecha'] ?? ''), $post_id);
    update_field('activo', !empty($body['activo']), $post_id);

    return rest_ensure_response(['id' => $post_id]);
}

function tt_editar_novedad(WP_REST_Request $request) {
    $post_id = (int) $request['id'];
    $body    = $request->get_json_params();

    if (get_post_type($post_id) !== 'novedades') {
        return new WP_Error('tt_no_encontrado', 'Novedad no encontrada', ['status' => 404]);
    }

    update_field('tipo', sanitize_text_field($body['tipo'] ?? ''), $post_id);
    update_field('media_url', esc_url_raw($body['media_url'] ?? ''), $post_id);
    update_field('texto', sanitize_textarea_field($body['texto'] ?? ''), $post_id);
    update_field('enlace', esc_url_raw($body['enlace'] ?? ''), $post_id);
    update_field('fecha', tt_novedades_fecha_a_acf($body['fecha'] ?? ''), $post_id);
    update_field('activo', !empty($body['activo']), $post_id);

    return rest_ensure_response(['ok' => true]);
}

function tt_eliminar_novedad(WP_REST_Request $request) {
    $post_id = (int) $request['id'];
    if (get_post_type($post_id) !== 'novedades') {
        return new WP_Error('tt_no_encontrado', 'Novedad no encontrada', ['status' => 404]);
    }
    wp_delete_post($post_id, true);
    return rest_ensure_response(['ok' => true]);
}

/* ════════════════════════════════════════════════════════
   REGISTRO DEL CPT 'novedades'
   ════════════════════════════════════════════════════════ */
add_action('init', function () {
    register_post_type('novedades', [
        'public'       => false, // no genera páginas propias públicas
        'show_ui'      => true,  // visible en wp-admin
        'show_in_rest' => true,  // necesario para ACF y REST
        'supports'     => ['title'],
        'label'        => 'Novedades',
    ]);
});

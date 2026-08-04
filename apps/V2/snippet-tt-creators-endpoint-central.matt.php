<?php
/*
  ⚠️ ARCHIVO DE TRABAJO CON DATOS FICTICIOS (PLACEHOLDER)
  ═══════════════════════════════════════════════════════════════════════
  Este es el archivo PHP real del endpoint central, con el único valor
  sensible (el token de escritura) sustituido por un placeholder. Toda
  la lógica, nombres de función, estructura y comentarios son idénticos
  al archivo oficial (snippet-tt-creators-endpoint-central.php).

  El placeholder de abajo NO es el token real y no funcionará contra
  ningún entorno real de Tiritaito. Si necesitas probar algo end-to-end
  contra el entorno real, pide el valor directamente al equipo — no lo
  asumas ni lo inventes.
  ═══════════════════════════════════════════════════════════════════════
*/

/* ============================================================
   TT Creators + Biblioteca — Endpoint central
   Entorno: Local — NUNCA producción
   Token de escritura: X-TT-Token, comparado contra TT_WRITE_TOKEN
   Última actualización: 26 julio 2026
   Cambios de esta sesión:
   - Devocional migrado a ACF Options Page
   - Tip del día ELIMINADO por completo (no existe en la web nueva)
   - virgen_fecha y brisa_autor separados en campos propios (antes
     iban pegados dentro del texto de tt_virgen/tt_brisa) — para que
     Avada pueda darles estilo visual distinto vía Dynamic Content
   ============================================================ */

// ⚠️ Debe coincidir EXACTAMENTE con el valor del HTML de la app.
// Si se cambia aquí, hay que cambiarlo también en tiritaito-creators-v2-XX.html
// y redistribuir el archivo — no hay rotación automática.
// ⚠️ COPIA DE TRABAJO: valor ficticio, no es el token real.
if (!defined('TT_WRITE_TOKEN')) {
    define('TT_WRITE_TOKEN', 'PLACEHOLDER_NO_ES_TOKEN_REAL_PIDE_EL_VALOR_SI_LO_NECESITAS');
}


/* ============================================================
   1. VERIFICACIÓN DE TOKEN — usada como permission_callback
   ============================================================ */
function tt_verificar_token($request) {
    $token_recibido = $request->get_header('X-TT-Token');
    if (empty($token_recibido) || $token_recibido !== TT_WRITE_TOKEN) {
        return new WP_Error(
            'tt_no_autorizado',
            'Token inválido o ausente.',
            ['status' => 401]
        );
    }
    return true;
}


/* ============================================================
   2. CORS — necesario porque la app se sirve desde un Code Block
      de Avada pero llama al REST API por fetch/XHR
   ============================================================ */
add_action('init', function () {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-TT-Token');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        status_header(200);
        exit();
    }
}, 15); // priority 15, ya estandarizado en el proyecto


/* ============================================================
   3. [ELIMINADA — 26 julio 2026]
      Antes vivía aquí tt_opciones_permitidas(), la whitelist de
      12 claves que usaban tt_datos_leer/tt_datos_guardar.
      Se elimina porque queda SIN USO: el Devocional ahora se sirve
      desde ACF Options Page (Sección 6), y las claves que siguen en
      wp_options plano tienen su propia lista en
      tt_opciones_wp_pendientes(), también en la Sección 6.
   ============================================================ */


/* ============================================================
   4. REGISTRO DEL CPT 'novedades'
   ============================================================ */
add_action('init', function () {
    register_post_type('novedades', [
        'label'        => 'Novedades',
        'public'       => false,       // no genera páginas propias públicas
        'show_ui'      => true,        // sí visible en wp-admin, para revisar a mano
        'show_in_rest' => true,        // necesario para que ACF y REST lo vean
        'supports'     => ['title'],
        'menu_icon'    => 'dashicons-megaphone',
    ]);
});


/* ============================================================
   5. RUTAS REST — tiritaito/v1/*
   ============================================================ */
add_action('rest_api_init', function () {

    /* ---------- Base — Devocional (ACF) + Recursos (wp_options) ---------- */

    register_rest_route('tiritaito/v1', '/datos', [
        'methods'             => 'GET',
        'callback'            => 'tt_datos_leer',
        'permission_callback' => '__return_true', // lectura pública
    ]);

    register_rest_route('tiritaito/v1', '/datos', [
        'methods'             => 'POST',
        'callback'            => 'tt_datos_guardar',
        'permission_callback' => 'tt_verificar_token',
    ]);

    /* ---------- Base — subida de archivos a Biblioteca de Medios ---------- */

    register_rest_route('tiritaito/v1', '/subir', [
        'methods'             => 'POST',
        'callback'            => 'tt_subir_archivo',
        'permission_callback' => 'tt_verificar_token',
    ]);

    /* ---------- Base — listado y borrado de Biblioteca de Medios ---------- */

    register_rest_route('tiritaito/v1', '/medios', [
        'methods'             => 'GET',
        'callback'            => 'tt_medios_listar',
        'permission_callback' => 'tt_verificar_token',
    ]);

    register_rest_route('tiritaito/v1', '/medio/(?P<id>\d+)', [
        'methods'             => 'DELETE',
        'callback'            => 'tt_medio_borrar',
        'permission_callback' => 'tt_verificar_token',
        'args'                => [
            'id' => ['validate_callback' => function ($p) { return is_numeric($p); }],
        ],
    ]);

    /* ---------- Novedades — CPT propio ---------- */

    register_rest_route('tiritaito/v1', '/novedades', [
        'methods'             => 'GET',
        'callback'            => 'tt_novedades_listar',
        'permission_callback' => '__return_true', // lectura pública, igual que /datos
    ]);

    register_rest_route('tiritaito/v1', '/novedades', [
        'methods'             => 'POST',
        'callback'            => 'tt_novedades_crear',
        'permission_callback' => 'tt_verificar_token',
    ]);

    register_rest_route('tiritaito/v1', '/novedades/(?P<id>\d+)', [
        'methods'             => 'PUT',
        'callback'            => 'tt_novedades_editar',
        'permission_callback' => 'tt_verificar_token',
        'args'                => [
            'id' => ['validate_callback' => function ($p) { return is_numeric($p); }],
        ],
    ]);

    register_rest_route('tiritaito/v1', '/novedades/(?P<id>\d+)', [
        'methods'             => 'DELETE',
        'callback'            => 'tt_novedades_borrar',
        'permission_callback' => 'tt_verificar_token',
        'args'                => [
            'id' => ['validate_callback' => function ($p) { return is_numeric($p); }],
        ],
    ]);
});


/* ============================================================
   6. FUNCIONES — Devocional (ACF Options Page) + Recursos (wp_options)
      Híbrido: 7 claves viven en ACF (incluye ya virgen_fecha y
      brisa_autor), 5 claves se quedan en wp_options plano tal cual
      estaban. Tip (tt_tip_1/tt_tip_2) NO aparece en ninguna lista —
      se elimina del todo, no se lee ni se escribe en ningún sitio.
   ============================================================ */

// Las 7 claves de Devocional, hoy en ACF Options Page
// Nombre de campo ACF = clave de la app sin el prefijo tt_
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

// Las claves que HOY se quedan en wp_options plano, sin tocar —
// pendientes de decisión de arquitectura (ver nota para Hno C)
function tt_opciones_wp_pendientes() {
    return [
        'tt_docx_lectura_url',
        'tt_youtube_json_url',
        'tt_seminarios_json_url',
        'tt_viacrucis_json_url',
        'tt_fiesta_dias',
    ];
}

// Sanitizado por tipo de campo ACF — cada campo se limpia según lo
// que realmente contiene, en vez de un if/else binario texto/URL que
// aplicaría esc_url_raw() a un nombre de persona o a una fecha
// (podría alterar acentos, p.ej. en "José María", al tratarlos como URL)
function tt_sanitizar_campo_devocional($campo_acf, $valor) {
    $texto_largo = ['virgen', 'brisa', 'homilia_texto'];
    $urls        = ['homilia_audio', 'lenguas_url'];

    if (in_array($campo_acf, $texto_largo, true)) {
        return sanitize_textarea_field($valor);
    }
    if (in_array($campo_acf, $urls, true)) {
        return esc_url_raw($valor);
    }
    if ($campo_acf === 'virgen_fecha') {
        // Espera 'YYYY-MM-DD' del <input type="date"> de la app.
        // Si no coincide con el patrón, se descarta en vez de guardar basura.
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor) ? sanitize_text_field($valor) : '';
    }
    // brisa_autor y cualquier otro campo corto de texto simple
    return sanitize_text_field($valor);
}

function tt_datos_leer($request) {
    $resultado = [];

    // Devocional — de ACF Options Page
    foreach (tt_mapa_campos_devocional() as $clave_app => $campo_acf) {
        // 'option' le dice a ACF que lea de la Options Page, no de un post
        $resultado[$clave_app] = (string) get_field($campo_acf, 'option');
    }

    // Recursos — de wp_options plano, sin cambios
    foreach (tt_opciones_wp_pendientes() as $clave) {
        $resultado[$clave] = get_option($clave, '');
    }

    return new WP_REST_Response($resultado, 200);
}

function tt_datos_guardar($request) {
    $body       = $request->get_json_params();
    $mapa_acf   = tt_mapa_campos_devocional();
    $pendientes = tt_opciones_wp_pendientes();
    $guardadas  = [];
    $rechazadas = [];

    foreach ($body as $clave_app => $valor) {

        // Caso 1: es una de las 7 claves de Devocional → ACF Options Page
        if (isset($mapa_acf[$clave_app])) {
            $campo_acf    = $mapa_acf[$clave_app];
            $valor_limpio = tt_sanitizar_campo_devocional($campo_acf, $valor);
            update_field($campo_acf, $valor_limpio, 'option');
            $guardadas[] = $clave_app;
            continue;
        }

        // Caso 2: es una de las 5 claves que siguen en wp_options plano
        if (in_array($clave_app, $pendientes, true)) {
            // tt_fiesta_dias es una lista CSV de texto, no una URL
            $valor_limpio = ($clave_app === 'tt_fiesta_dias')
                ? sanitize_text_field($valor)
                : esc_url_raw($valor);

            update_option($clave_app, $valor_limpio);
            $guardadas[] = $clave_app;
            continue;
        }

        // Caso 3: cualquier otra clave (incluido tt_tip_1/tt_tip_2 si una
        // versión antigua de la app todavía los mandara) se rechaza
        $rechazadas[] = $clave_app;
    }

    return new WP_REST_Response([
        'guardadas'  => $guardadas,
        'rechazadas' => $rechazadas, // la app ya sabe leer esto (ver guardarFiestaDias)
    ], 200);
}


/* ============================================================
   7. FUNCIONES — subida y gestión de Biblioteca de Medios
   ============================================================ */

function tt_subir_archivo($request) {
    if (empty($_FILES['file'])) {
        return new WP_REST_Response(['error' => 'No se recibió ningún archivo'], 400);
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $attachment_id = media_handle_upload('file', 0);

    if (is_wp_error($attachment_id)) {
        return new WP_REST_Response(['error' => $attachment_id->get_error_message()], 500);
    }

    return new WP_REST_Response([
        'id'         => $attachment_id,
        'source_url' => wp_get_attachment_url($attachment_id),
    ], 200);
}

function tt_medios_listar($request) {
    $per_page = (int) ($request->get_param('per_page') ?: 24);
    $page     = (int) ($request->get_param('page') ?: 1);
    $tipo     = $request->get_param('media_type');
    $busqueda = $request->get_param('search');

    $args = [
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if (!empty($tipo))     $args['post_mime_type'] = $tipo;
    if (!empty($busqueda)) $args['s'] = sanitize_text_field($busqueda);

    $query = new WP_Query($args);
    $resultado = [];

    foreach ($query->posts as $post) {
        $id = $post->ID;
        $resultado[] = [
            'id'            => $id,
            'title'         => ['rendered' => get_the_title($id)],
            'media_type'    => explode('/', get_post_mime_type($id))[0],
            'source_url'    => wp_get_attachment_url($id),
            'date'          => $post->post_date,
            'media_details' => ['sizes' => wp_get_attachment_metadata($id)['sizes'] ?? []],
        ];
    }

    $response = new WP_REST_Response($resultado, 200);
    $response->header('X-WP-TotalPages', (string) $query->max_num_pages);
    return $response;
}

function tt_medio_borrar($request) {
    $id = (int) $request['id'];
    if (!get_post($id) || get_post_type($id) !== 'attachment') {
        return new WP_REST_Response(['error' => 'Archivo no encontrado'], 404);
    }
    $borrado = wp_delete_attachment($id, true); // true = borrado permanente
    if (!$borrado) {
        return new WP_REST_Response(['error' => 'No se pudo borrar el archivo'], 500);
    }
    return new WP_REST_Response(['ok' => true], 200);
}


/* ============================================================
   8. FUNCIONES — Novedades (conversión de fecha en la frontera)
   ============================================================ */

// De Ymd (guardado en ACF) a YYYY-MM-DD (lo que espera la app)
function tt_novedades_fecha_a_app($fecha_ymd) {
    if (empty($fecha_ymd)) return '';
    $dt = DateTime::createFromFormat('Ymd', $fecha_ymd);
    return $dt ? $dt->format('Y-m-d') : '';
}

// De YYYY-MM-DD (lo que manda la app) a Ymd (lo que espera ACF)
function tt_novedades_fecha_a_acf($fecha_app) {
    if (empty($fecha_app)) return '';
    $dt = DateTime::createFromFormat('Y-m-d', $fecha_app);
    return $dt ? $dt->format('Ymd') : '';
}

function tt_novedades_listar($request) {
    $posts = get_posts([
        'post_type'      => 'novedades',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);

    $resultado = [];
    foreach ($posts as $post) {
        $id = $post->ID;
        $resultado[] = [
            'id'        => $id,
            'tipo'      => (string) get_field('tipo', $id),
            'media_url' => (string) get_field('media_url', $id),
            'texto'     => (string) get_field('texto', $id),
            'enlace'    => (string) get_field('enlace', $id),
            'fecha'     => tt_novedades_fecha_a_app(get_field('fecha', $id)),
            'activo'    => (bool) get_field('activo', $id),
            'titulo'    => (string) get_field('titulo', $id),
        ];
    }

    return new WP_REST_Response($resultado, 200);
}

function tt_novedades_crear($request) {
    $body = $request->get_json_params();

    $tipo      = sanitize_text_field($body['tipo'] ?? '');
    $media_url = esc_url_raw($body['media_url'] ?? '');
    $texto     = sanitize_textarea_field($body['texto'] ?? '');
    $enlace    = esc_url_raw($body['enlace'] ?? '');
    $fecha_acf = tt_novedades_fecha_a_acf($body['fecha'] ?? '');
    $activo    = !empty($body['activo']);
    $titulo    = sanitize_text_field($body['titulo'] ?? '');

    $post_id = wp_insert_post([
        'post_type'   => 'novedades',
        'post_status' => 'publish',
        'post_title'  => $titulo !== '' ? $titulo : ('Novedad ' . current_time('Y-m-d H:i')),
    ]);

    if (is_wp_error($post_id) || !$post_id) {
        return new WP_REST_Response(['error' => 'No se pudo crear la novedad'], 500);
    }

    update_field('tipo', $tipo, $post_id);
    update_field('media_url', $media_url, $post_id);
    update_field('texto', $texto, $post_id);
    update_field('enlace', $enlace, $post_id);
    update_field('fecha', $fecha_acf, $post_id);
    update_field('activo', $activo, $post_id);
    update_field('titulo', $titulo, $post_id);

    return new WP_REST_Response(['id' => $post_id], 200);
}

function tt_novedades_editar($request) {
    $id = (int) $request['id'];

    $post = get_post($id);
    if (!$post || $post->post_type !== 'novedades') {
        return new WP_REST_Response(['error' => 'Novedad no encontrada'], 404);
    }

    $body = $request->get_json_params();

    $tipo      = sanitize_text_field($body['tipo'] ?? '');
    $media_url = esc_url_raw($body['media_url'] ?? '');
    $texto     = sanitize_textarea_field($body['texto'] ?? '');
    $enlace    = esc_url_raw($body['enlace'] ?? '');
    $fecha_acf = tt_novedades_fecha_a_acf($body['fecha'] ?? '');
    $activo    = !empty($body['activo']);
    $titulo    = sanitize_text_field($body['titulo'] ?? '');

    update_field('tipo', $tipo, $id);
    update_field('media_url', $media_url, $id);
    update_field('texto', $texto, $id);
    update_field('enlace', $enlace, $id);
    update_field('fecha', $fecha_acf, $id);
    update_field('activo', $activo, $id);
    update_field('titulo', $titulo, $id);

    if ($titulo !== '') {
        wp_update_post(['ID' => $id, 'post_title' => $titulo]);
    }

    return new WP_REST_Response(['ok' => true], 200);
}

function tt_novedades_borrar($request) {
    $id = (int) $request['id'];

    $post = get_post($id);
    if (!$post || $post->post_type !== 'novedades') {
        return new WP_REST_Response(['error' => 'Novedad no encontrada'], 404);
    }

    $borrado = wp_delete_post($id, true);
    if (!$borrado) {
        return new WP_REST_Response(['error' => 'No se pudo borrar la novedad'], 500);
    }

    return new WP_REST_Response(['ok' => true], 200);
}

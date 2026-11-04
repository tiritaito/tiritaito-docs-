/* ============================================================
   TT Creators + Biblioteca — Endpoint central
   Entorno: Local (tiritaito-real.local) — NUNCA producción
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
if (!defined('TT_WRITE_TOKEN')) {
    define('TT_WRITE_TOKEN', '36374494995SFGJKF453534');
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

    register_rest_route('tiritaito/v1', '/novedades/opciones', [
        'methods'             => 'GET',
        'callback'            => 'tt_novedades_opciones_listar',
        'permission_callback' => '__return_true', // solo etiquetas de desplegable, nada sensible
    ]);

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

/* ---------- Utilidades de Novedades (v2-07) ---------- */

// Cuerpo JSON de la petición, siempre como array.
// get_json_params() devuelve null si el cuerpo no es JSON válido; sin esto,
// array_key_exists() sobre null es un error fatal en PHP 8.
function tt_novedades_body($request) {
    $b = $request->get_json_params();
    return is_array($b) ? $b : [];
}

// ¿La petición trae el token de escritura correcto? (para decidir si se ven borradores)
function tt_novedades_token_valido($request) {
    $t = $request->get_header('X-TT-Token');
    return !empty($t) && hash_equals((string) TT_WRITE_TOKEN, (string) $t);
}

// Definición de un campo ACF de Novedades, buscada POR NOMBRE.
// Sin claves field_... escritas a mano: cambian entre Local y producción.
function tt_novedades_campo_acf($nombre) {
    static $cache = [];
    if (array_key_exists($nombre, $cache)) return $cache[$nombre];

    $encontrado = null;
    if (function_exists('acf_get_field_groups') && function_exists('acf_get_fields')) {
        foreach (acf_get_field_groups(['post_type' => 'novedades']) as $grupo) {
            $campos = acf_get_fields($grupo['key']);
            if (!is_array($campos)) continue;
            foreach ($campos as $campo) {
                if (isset($campo['name']) && $campo['name'] === $nombre) {
                    $encontrado = $campo;
                    break 2;
                }
            }
        }
    }

    // Respaldo: si la regla de ubicación del grupo no se resuelve por tipo de
    // entrada, pedirlo a través de cualquier novedad existente.
    if (!$encontrado && function_exists('get_field_object')) {
        $ids = get_posts([
            'post_type'   => 'novedades',
            'post_status' => 'any',
            'numberposts' => 1,
            'fields'      => 'ids',
        ]);
        if ($ids) {
            $obj = get_field_object($nombre, $ids[0]);
            if (is_array($obj) && !empty($obj['key'])) $encontrado = $obj;
        }
    }

    $cache[$nombre] = $encontrado;
    return $encontrado;
}

// Opciones de un campo Select de ACF: ['valor' => 'Etiqueta', ...]
function tt_novedades_opciones($nombre) {
    $campo = tt_novedades_campo_acf($nombre);
    if (!$campo || empty($campo['choices']) || !is_array($campo['choices'])) return [];
    return $campo['choices'];
}

// Molde que se asigna si la app no manda ninguno: el valor por defecto del campo
// en ACF y, si no hay, la primera opción. Así una novedad nunca se queda sin molde.
function tt_novedades_molde_defecto() {
    $campo = tt_novedades_campo_acf('molde');
    if ($campo && !empty($campo['default_value'])) {
        $d = is_array($campo['default_value']) ? reset($campo['default_value']) : $campo['default_value'];
        if ($d !== false && $d !== '' && $d !== null) return (string) $d;
    }
    $claves = array_keys(tt_novedades_opciones('molde'));
    return $claves ? (string) $claves[0] : '';
}

// Valida un valor contra las opciones del campo.
// Devuelve: el valor limpio si es válido · '' si viene vacío · false si NO es válido.
function tt_novedades_validar_opcion($nombre, $valor) {
    $valor = sanitize_text_field((string) $valor);
    if ($valor === '') return '';
    $validos = array_map('strval', array_keys(tt_novedades_opciones($nombre)));
    return in_array($valor, $validos, true) ? $valor : false;
}

// Guarda un campo usando su CLAVE (resuelta en el momento), con el nombre como
// último recurso. Evita la trampa de update_field() por nombre en entradas nuevas.
function tt_novedades_guardar_campo($nombre, $valor, $post_id) {
    $campo = tt_novedades_campo_acf($nombre);
    update_field($campo ? $campo['key'] : $nombre, $valor, $post_id);
}

// Ocultar / mostrar de verdad: estado de la entrada + campo `activo` sincronizados.
function tt_novedades_aplicar_activo($post_id, $activo) {
    update_field('activo', (bool) $activo, $post_id);
    wp_update_post([
        'ID'          => $post_id,
        'post_status' => $activo ? 'publish' : 'draft',
    ]);
}

// Si hay LiteSpeed Cache, vaciar la caché para que ocultar/mostrar se note ya.
// ⚠️ Sin probar: en Local no hay LiteSpeed. Si el plugin no existe, no hace nada.
function tt_novedades_purgar_cache() {
    do_action('litespeed_purge_all');
}

function tt_novedades_error($mensaje, $estado = 400) {
    return new WP_REST_Response(['error' => $mensaje], $estado);
}

/* ---------- GET /novedades/opciones ---------- */

function tt_novedades_opciones_listar($request) {
    $salida = [];
    foreach (['molde', 'categoria'] as $nombre) {
        $lista = [];
        foreach (tt_novedades_opciones($nombre) as $valor => $etiqueta) {
            $lista[] = ['valor' => (string) $valor, 'etiqueta' => (string) $etiqueta];
        }
        $salida[$nombre] = $lista;
    }
    $salida['molde_defecto'] = tt_novedades_molde_defecto();
    return new WP_REST_Response($salida, 200);
}

/* ---------- GET /novedades ---------- */

function tt_novedades_listar($request) {
    // Con token (la app): publicadas + ocultas, para poder volver a mostrarlas.
    // Sin token (lectura pública): solo lo publicado.
    $con_token = tt_novedades_token_valido($request);

    $posts = get_posts([
        'post_type'      => 'novedades',
        'posts_per_page' => -1,
        'post_status'    => $con_token ? ['publish', 'draft'] : 'publish',
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
            // La verdad es el estado real de la entrada, no el campo ACF:
            // así la app refleja siempre lo que de verdad sale en la web.
            'activo'    => ($post->post_status === 'publish'),
            'titulo'    => (string) get_field('titulo', $id),
            'categoria' => (string) get_field('categoria', $id),
            'molde'     => (string) get_field('molde', $id),
        ];
    }

    return new WP_REST_Response($resultado, 200);
}

/* ---------- POST /novedades ---------- */

function tt_novedades_crear($request) {
    $body = tt_novedades_body($request);

    $tipo      = sanitize_text_field($body['tipo'] ?? '');
    $media_url = esc_url_raw($body['media_url'] ?? '');
    $texto     = sanitize_textarea_field($body['texto'] ?? '');
    $enlace    = esc_url_raw($body['enlace'] ?? '');
    $fecha_acf = tt_novedades_fecha_a_acf($body['fecha'] ?? '');
    $titulo    = sanitize_text_field($body['titulo'] ?? '');
    // Si la app no manda `activo`, la novedad nueva nace visible.
    $activo    = array_key_exists('activo', $body) ? !empty($body['activo']) : true;

    // Validar ANTES de crear nada (un 400 no debe dejar una entrada a medias)
    $categoria = tt_novedades_validar_opcion('categoria', $body['categoria'] ?? '');
    if ($categoria === false) {
        return tt_novedades_error('Categoría no válida: no está entre las opciones del campo en ACF.');
    }
    $molde = tt_novedades_validar_opcion('molde', $body['molde'] ?? '');
    if ($molde === false) {
        return tt_novedades_error('Molde no válido: no está entre las opciones del campo en ACF.');
    }
    if ($molde === '') $molde = tt_novedades_molde_defecto();

    $post_id = wp_insert_post([
        'post_type'   => 'novedades',
        'post_status' => $activo ? 'publish' : 'draft',
        'post_title'  => $titulo !== '' ? $titulo : ('Novedad ' . current_time('Y-m-d H:i')),
    ]);

    if (is_wp_error($post_id) || !$post_id) {
        return tt_novedades_error('No se pudo crear la novedad', 500);
    }

    update_field('tipo', $tipo, $post_id);
    update_field('media_url', $media_url, $post_id);
    update_field('texto', $texto, $post_id);
    update_field('enlace', $enlace, $post_id);
    update_field('fecha', $fecha_acf, $post_id);
    update_field('activo', $activo, $post_id);
    update_field('titulo', $titulo, $post_id);
    tt_novedades_guardar_campo('categoria', $categoria, $post_id);
    tt_novedades_guardar_campo('molde', $molde, $post_id);

    tt_novedades_purgar_cache();
    return new WP_REST_Response(['id' => $post_id], 200);
}

/* ---------- PUT /novedades/{id} ---------- */

function tt_novedades_editar($request) {
    $id = (int) $request['id'];

    $post = get_post($id);
    if (!$post || $post->post_type !== 'novedades') {
        return tt_novedades_error('Novedad no encontrada', 404);
    }

    $body = tt_novedades_body($request);

    // Validar primero: si algo no vale, no se escribe nada.
    $molde = null;
    if (array_key_exists('molde', $body)) {
        $molde = tt_novedades_validar_opcion('molde', $body['molde']);
        if ($molde === false) {
            return tt_novedades_error('Molde no válido: no está entre las opciones del campo en ACF.');
        }
        if ($molde === '') $molde = tt_novedades_molde_defecto();
    }
    $categoria = null;
    if (array_key_exists('categoria', $body)) {
        $categoria = tt_novedades_validar_opcion('categoria', $body['categoria']);
        if ($categoria === false) {
            return tt_novedades_error('Categoría no válida: no está entre las opciones del campo en ACF.');
        }
    }

    $tipo      = sanitize_text_field($body['tipo'] ?? '');
    $media_url = esc_url_raw($body['media_url'] ?? '');
    $texto     = sanitize_textarea_field($body['texto'] ?? '');
    $enlace    = esc_url_raw($body['enlace'] ?? '');
    $fecha_acf = tt_novedades_fecha_a_acf($body['fecha'] ?? '');
    $titulo    = sanitize_text_field($body['titulo'] ?? '');

    update_field('tipo', $tipo, $id);
    update_field('media_url', $media_url, $id);
    update_field('texto', $texto, $id);
    update_field('enlace', $enlace, $id);
    update_field('fecha', $fecha_acf, $id);
    update_field('titulo', $titulo, $id);

    // molde, categoría y activo: solo se tocan si la app los manda.
    // (Así una edición que no los incluya no los borra ni oculta la novedad.)
    if ($molde !== null)     tt_novedades_guardar_campo('molde', $molde, $id);
    if ($categoria !== null) tt_novedades_guardar_campo('categoria', $categoria, $id);
    if (array_key_exists('activo', $body)) {
        tt_novedades_aplicar_activo($id, !empty($body['activo']));
    }

    if ($titulo !== '') {
        wp_update_post(['ID' => $id, 'post_title' => $titulo]);
    }

    tt_novedades_purgar_cache();
    return new WP_REST_Response(['ok' => true], 200);
}

/* ---------- DELETE /novedades/{id} ---------- */

function tt_novedades_borrar($request) {
    $id = (int) $request['id'];

    $post = get_post($id);
    if (!$post || $post->post_type !== 'novedades') {
        return tt_novedades_error('Novedad no encontrada', 404);
    }

    $borrado = wp_delete_post($id, true);
    if (!$borrado) {
        return tt_novedades_error('No se pudo borrar la novedad', 500);
    }

    tt_novedades_purgar_cache();
    return new WP_REST_Response(['ok' => true], 200);
}

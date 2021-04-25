<?php
date_default_timezone_set('America/Lima');
// En este archivo se registran todos los endpoints personalizados
/* Recuarda siempre agregar el permission_callback aldem_verify_token*/
add_action('rest_api_init', function () {
    register_rest_route('aldem/v1', '/ubigeos/(?P<username>\w+)/', array(
        'methods' => 'POST',
        'permission_callback' => 'aldem_verify_token',
        'callback' => 'get_aldem_ubigeos',
        'args' => array(),
    ));
});

function get_aldem_ubigeos(WP_REST_Request $request)
{
    //obtengo el id_country del parametros
    $id_country = $request->get_json_params()["id_country"];
    $ubigeos = query_getUbigeo($id_country);
    if (empty($ubigeos)) {
        return new WP_Error('no_ubigeos', "Invalid id_country: $id_country", array('status' => 404));
    }
    return $ubigeos;
}

add_action('rest_api_init', function () {
    register_rest_route('aldem/v1', '/marken_shipper/(?P<username>\w+)/', array(
        'methods' => 'POST',
        'permission_callback' => 'aldem_verify_token',
        'callback' => 'post_aldem_marken_shipper',
        'args' => array(),
    ));
});

function post_aldem_marken_shipper(WP_REST_Request $request)
{
    //obtengo el id_country del parametros
    $prefix = query_getAldemPrefix();
    $wpdb = query_getWPDB();
    $validations = [
        'nombre'                  =>  'required|max:50',
        'direccion'                  => 'required|max:50',
        'id_pais'                  => 'required|numeric',
        'id_tipo'                  => 'required|numeric',
        'id_user'                  => 'required|numeric',
    ];
    $responseValidator = adldem_UtilityValidator($request->get_json_params(), $validations);
    if ($responseValidator["validate"]) {
        // se va crear un shipper
        $nombre = sanitize_text_field($request->get_json_params()["nombre"]);
        $direccion = sanitize_text_field($request->get_json_params()["direccion"]);
        $id_pais = intval(sanitize_text_field($request->get_json_params()["id_pais"]));
        $id_user = intval(sanitize_text_field($request->get_json_params()["id_user"]));
        $id_tipo = intval(sanitize_text_field($request->get_json_params()["id_tipo"]));
        $fecha_actual = date("Y-m-d H:i:s");
        $table = "{$prefix}marken_shipper";
        $data = [
            'id_tipo' => $id_tipo,
            'descripcion' => $nombre,
            'direccion' => $direccion,
            'id_country' => $id_pais,
            'id_usuario_created' => $id_user,
            'created_at' => $fecha_actual
        ];

        $format = array(
            '%d', '%s', '%s',
            '%d', '%d',  '%s'
        );
        if ($wpdb->insert($table, $data, $format)) {
            return  aldem_rest_response(["id_marken_shipper" => $wpdb->insert_id, "nombre" => $nombre, "direccion" => $direccion], "Marken Shipper creado correctamente");
        } else {
            return  aldem_rest_response("", "Error en la creacion de Marken Shipper", 500);
        }
    } else {
        return  aldem_rest_response(aldem_transform_text_p($responseValidator["message"]), "Parametros no Validos", 404);
    }
}

add_action('rest_api_init', function () {
    register_rest_route('aldem/v1', '/getMarkenShippers/(?P<username>\w+)/', array(
        'methods' => 'POST',
        'permission_callback' => 'aldem_verify_token',
        'callback' => 'get_aldem_shippers',
        'args' => array(),
    ));
});

function get_aldem_shippers(WP_REST_Request $request)
{
    //obtengo el id_country del parametros
    $id_tipo = intval(sanitize_text_field($request->get_json_params()["id_tipo"]));
    $shippers = null;
    if ($id_tipo == 2) {
        $shippers =    query_getExportadores();
        return  aldem_rest_response($shippers);
    } else if ($id_tipo == 3) {
        $shippers = query_getImportadores();
        return  aldem_rest_response($shippers);
    } else {
        return  aldem_rest_response("", "Tipo no Valido", 404);
    }
}

add_action('rest_api_init', function () {
    register_rest_route('aldem/v1', '/shippers/(?P<username>\w+)/', array(
        'methods' => 'GET',
        'permission_callback' => 'aldem_verify_token',
        'callback' => 'get_aldem_marken_shippers_standar',
        'args' => array(),
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('aldem/v1', '/shippers/(?P<username>\w+)/', array(
        'methods' => 'POST',
        'permission_callback' => 'aldem_verify_token',
        'callback' => 'post_aldem_marken_shippers_standar',
        'args' => array(),
    ));
});

function get_aldem_marken_shippers_standar(WP_REST_Request $request)
{
    return aldem_rest_response(query_getShippers());
}
function post_aldem_marken_shippers_standar(WP_REST_Request $request)
{
    $prefix = query_getAldemPrefix();
    $wpdb = query_getWPDB();
    $validations = [
        'nombreShipper'                  =>  'required',
        'direccionShipper'                  => 'required',
        'paisShipper'                  => 'required|numeric',
        'siteShipper'                  => 'required|numeric',
        'ubigeoShipper'                  => 'numeric',
        'id_user'                  => 'required|numeric',
    ];
    $responseValidator = adldem_UtilityValidator($request->get_json_params(), $validations);
    if ($responseValidator["validate"]) {
        // se va crear un shipper
        $nombreShipper = sanitize_text_field($request->get_json_params()['nombreShipper']);
        $direccionShipper = sanitize_text_field($request->get_json_params()['direccionShipper']);
        $paisShipper = intval(sanitize_text_field($request->get_json_params()['paisShipper']));
        $siteShipper = intval(sanitize_text_field($request->get_json_params()['siteShipper']));
        $ubigeoShipper = intval(sanitize_text_field($request->get_json_params()['ubigeoShipper']));
        $id_user = intval(sanitize_text_field($request->get_json_params()['id_user']));
        $fecha_actual = date("Y-m-d H:i:s");
        $table = "{$prefix}marken_shipper";
        $data = [
            'descripcion' => $nombreShipper, 'direccion' => $direccionShipper,
            'id_country' => $paisShipper, 'id_ubigeo' => $ubigeoShipper,
            'id_marken_site' => $siteShipper,
            'id_usuario_created' => $id_user,
            'id_tipo' => 1,
            'created_at' => $fecha_actual
        ];

        $format = array(
            '%s', '%s',
            '%d', '%d', '%d', '%d', '%d', '%s'
        );
        if ($wpdb->insert($table, $data, $format)) {
            return  aldem_rest_response(["id_marken_shipper" => $wpdb->insert_id, "descripcion" => $nombreShipper, "direccion" => $direccionShipper], "Marken Shipper creado correctamente");
        } else {
            aldem_rest_response("", "Error en la creacion de Shipper", 500);
        }
    } else {
        return  aldem_rest_response(aldem_transform_text_p($responseValidator["message"]), "Parametros no Validos", 404);
    }
}

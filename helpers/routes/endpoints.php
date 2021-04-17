<?php

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

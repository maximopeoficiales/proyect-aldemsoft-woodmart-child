<?php
date_default_timezone_set('America/Lima');
// desabilita url conocidas de wordpress
remove_action("rest_api_init", "create_initial_rest_routes", 99);

// creo token cuando se registrar un usuario
/* hook de registro de cliente */
add_action('user_register', 'aldem_create_token_user_created', 10, 3);
function aldem_create_token_user_created($user_id): void
{
    global $wpdb;
    $fecha_actual = date("Y-m-d H:i:s");
    $token = wp_generate_password(30);
    $sql = "INSERT INTO {$wpdb->prefix}users_tokens (user_id,token,created_at) VALUES ($user_id,%s,%s)";
    $wpdb->query($wpdb->prepare($sql, $token, $fecha_actual));
    $wpdb->flush();
};
// fin de creacion de token

function aldem_getUserNameCurrent(): string
{
    return get_userdata(get_current_user_id())->user_login ?? "";
}
function aldem_getUserNameCurrentUserId($user_id): string
{
    return get_userdata($user_id)->user_login;
}
function aldem_getBearerToken(): string
{
    global $wpdb;
    $user_id = get_current_user_id();
    $sql = "SELECT * FROM {$wpdb->prefix}users_tokens WHERE user_id = $user_id";
    $result = $wpdb->get_results($sql);
    return $result[0]->token ?? "";
}
function aldem_getBearerTokenUserId($user_id): string
{
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}users_tokens WHERE user_id = $user_id";
    $result = $wpdb->get_results($sql);
    return $result[0]->token ?? "";
}

function aldem_verify_token(WP_REST_Request $request): bool
{
    $username = $request->get_param("username");
    $user_id = username_exists($username);
    if (!$user_id) {
        return false;
    }
    $token = str_replace("Bearer ", "", $request->get_header("Authorization"));
    return $token === aldem_getBearerTokenUserId($user_id) ? true : false;
}
function aldem_rest_response($data, $msg = "Solicitud Exitosa", $status = 200): array
{
    return [
        "status" => $status, "message" => $msg, "data" => $data
    ];
}
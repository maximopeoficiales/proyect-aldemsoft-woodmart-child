<?php
date_default_timezone_set('America/Lima');
// Hooks admin-post

// add_action( 'admin_post_nopriv_process_form', 'send_mail_data' );
add_action('admin_post_process_form', 'new_shipper_data');

// Funcion callback
function new_shipper_data()
{
    $action_name = $_POST["action_name"];
    if ($action_name === "new-shipper" || $action_name === "edit-shipper") {
        $wpdb = query_getWPDB();
        $validations = [
            'nombreShipper'                  =>  'required',
            'direccionShipper'                  => 'required',
            'direccion2Shipper'                  => 'required',
            'zipShipper'                  => 'required|numeric',
            'paisShipper'                  => 'required|numeric',
            'siteShipper'                  => 'required|numeric',
            'ubigeoShipper'                  => 'required|numeric',
            'id_user'                  => 'required|numeric',
            'id_shipper'                  => 'numeric',
        ];
        $responseValidator = adldem_UtilityValidator($_POST, $validations);
        if ($responseValidator["validate"]) {
            // se va crear un shipper
            $nombreShipper = sanitize_text_field($_POST['nombreShipper']);
            $direccionShipper = sanitize_text_field($_POST['direccionShipper']);
            $direccion2Shipper = sanitize_text_field($_POST['direccion2Shipper']);
            $zipShipper = intval(sanitize_text_field($_POST['zipShipper']));
            $paisShipper = intval(sanitize_text_field($_POST['paisShipper']));
            $siteShipper = intval(sanitize_text_field($_POST['siteShipper']));
            $ubigeoShipper = intval(sanitize_text_field($_POST['ubigeoShipper']));
            $id_user = intval(sanitize_text_field($_POST['id_user']));
            $fecha_actual = date("Y-m-d H:i:s");
            $table = "marken_shipper";
            $data = [
                'descripcion' => $nombreShipper, 'direccion' => $direccionShipper,
                'direccion2' => $direccion2Shipper, 'zip' => $zipShipper,
                'id_country' => $paisShipper, 'id_ubigeo' => $ubigeoShipper,
                'id_marken_site' => $siteShipper, 'id_usuario_created' => $id_user,
                'created_at' => $fecha_actual
            ];

            if ($action_name === "new-shipper") {
                $format = array('%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%s');
                if ($wpdb->insert($table, $data, $format)) {
                    wp_redirect(home_url("/marken_shipper/") . "?msg=" . 1);
                } else {
                    wp_redirect(home_url("/marken_shipper/") . "?msg=");
                }
            } else if ($action_name === "edit-shipper") {
                $idShipper = intval(sanitize_text_field($_POST['id_shipper']));
                unset($data["created_at"]);
                $data["updated_at"] = $fecha_actual;
                // formatos de los valores 
                $format = array('%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%s');
                if ($wpdb->update($table, $data, [
                    "id" => $idShipper
                ], $format)) {
                    wp_redirect(home_url("/marken_shipper/") . "?msg=" . 2);
                } else {
                    wp_redirect(home_url("/marken_shipper/") . "?msg=");
                }
            } else {
                wp_redirect(home_url("/marken_shipper/") . "?msg=");
            }
        } else {
            wp_redirect(home_url("/marken_shipper/") . "?errors=" . $responseValidator["message"]);
        }
    }
}

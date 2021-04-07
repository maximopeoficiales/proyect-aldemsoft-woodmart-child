<?php
date_default_timezone_set('America/Lima');
// Hooks admin-post

// add_action( 'admin_post_nopriv_process_form', 'send_mail_data' );
add_action('admin_post_process_form', 'aldem_post_new_shipper_data');
add_action('admin_post_process_form', 'aldem_post_new_export_job_hielo');
add_action('admin_post_process_form', 'aldem_post_new_export');

// Funcion callback
function aldem_post_new_shipper_data()
{
    $action_name = $_POST["action_name"];
    if ($action_name === "new-shipper" || $action_name === "edit-shipper") {
        $wpdb = query_getWPDB();
        $validations = [
            'nombreShipper'                  =>  'required',
            'direccionShipper'                  => 'required',
            'direccion2Shipper'                  => 'required',
            'zipShipper'                  => 'required',
            'paisShipper'                  => 'required|numeric',
            'siteShipper'                  => 'required|numeric',
            'ubigeoShipper'                  => 'numeric',
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
                $format = array('%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s');
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
function aldem_post_new_export_job_hielo()
{
    $wpdb = query_getWPDB();
    $action_name = $_POST["action_name"];
    if ($action_name === "new-export-hielo") {
        $job = intval(sanitize_text_field($_POST['job']));
        $waybill = sanitize_text_field($_POST['waybill']);
        $validations = [
            'kilos'                  =>  'required|numeric',
            'serie'                  => 'required|max:6',
            'numero'                  => 'required|max:15',
            'precio'                  => 'required|numeric',
            'comentario'                  => 'required|max:250',
            'job'                  => 'required|numeric',
            'user_id'                  => 'required|numeric',
        ];
        $responseValidator = adldem_UtilityValidator($_POST, $validations);
        if ($responseValidator["validate"]) {
            // se va crear un shipper
            $kilos = doubleval(sanitize_text_field($_POST['kilos']));
            $serie = sanitize_text_field($_POST['serie']);
            $numero = sanitize_text_field($_POST['numero']);
            $precio = doubleval(sanitize_text_field($_POST['precio']));
            $comentario = sanitize_text_field($_POST['comentario']);
            $user_id = intval(sanitize_text_field($_POST['user_id']));
            $fecha_actual = date("Y-m-d H:i:s");
            $table = "marken_job_hielo";
            $data = [
                "id_marken_job" => $job,
                "kilos" => $kilos,
                "id_usuario_created" => $user_id,
                "comentarios" => $comentario,
                "serie" => $serie,
                "numero" => $numero,
                "precio" => $precio,
                "created_at" => $fecha_actual,
            ];
            $format = array('%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s');
            if ($wpdb->insert($table, $data, $format)) {
                wp_redirect(home_url("/marken_export_hielo_nuevo/?job=$job&waybill=$waybill") . "&msg=" . 1);
            } else {
                wp_redirect(home_url("marken_export_hielo_nuevo/?job=$job&waybill=$waybill") . "?msg=");
            }
        } else {
            wp_redirect(home_url("/marken_export_hielo_nuevo/?job=$job&waybill=$waybill") . "&errors=" . $responseValidator["message"]);
        }
    }
}
function aldem_post_new_export()
{
    $wpdb = query_getWPDB();
    $action_name = $_POST["action_name"];
    if ($action_name === "new-job" || $action_name === "update-job") {
        $validations = [
            'waybill'                  =>  'required|max:25',
            'id_shipper'                  =>  'required|numeric',
            'consigge_nombre'                  => 'max:150',
            'consigge_direccion'                  => 'max:150',
            'id_pais'                  => 'numeric',
            'contacto'                  => 'required|max:50',
            'contacto_telf'                  => 'required|max:50',
            'reference'                  => 'required|max:150',
            'content'                  => 'required|max:250',
            'pcs'                  => 'required|numeric',
            'range'                  => 'required|max:25',
            'id_marken_type'                  => 'required|numeric',
            'id_caja'                  => 'required|numeric',
            'instrucciones'                  => 'required|max:250',
            'fecha'                  => 'required|max:10|date:Y-m-d',
            'hora'                  => 'required|max:5',
            'user_id'                  => 'required|numeric',
        ];
        if ($action_name == "update-job") {
            $validations["id_marken_job"] = "required|numeric";
            $validations["id_marken_consiggne"] = "required|numeric";
        }
        $responseValidator = adldem_UtilityValidator($_POST, $validations);
        if ($responseValidator["validate"]) {
            // se va crear un shipper
            $waybill = sanitize_text_field($_POST['waybill']);
            $id_shipper = intval(sanitize_text_field($_POST['id_shipper']));
            // consigge
            $consigge_nombre = sanitize_text_field($_POST['consigge_nombre']);
            $consigge_direccion = sanitize_text_field($_POST['consigge_direccion']);
            $id_pais = intval(sanitize_text_field($_POST['id_pais']));
            $contacto = sanitize_text_field($_POST['contacto']);
            $contacto_telf = sanitize_text_field($_POST['contacto_telf']);
            $reference = sanitize_text_field($_POST['reference']);

            $content = sanitize_text_field($_POST['content']);
            $pcs = intval(sanitize_text_field($_POST['pcs']));
            $range = sanitize_text_field($_POST['range']);
            $id_marken_type = intval(sanitize_text_field($_POST['id_marken_type']));
            $id_caja = intval(sanitize_text_field($_POST['id_caja']));
            $instrucciones = sanitize_text_field($_POST['instrucciones']);
            $fechaHora = sanitize_text_field($_POST['fecha']) . " " . sanitize_text_field($_POST['hora']) . ":00";
            $fecha_actual = date("Y-m-d H:i:s");
            $user_id = sanitize_text_field($_POST['user_id']);

            // campos necesarios para la actualizacion
            $id_marken_jobUpdated = intval(sanitize_text_field($_POST['id_marken_job']));
            $id_marken_consiggneUpdated = intval(sanitize_text_field($_POST['id_marken_consiggne']));


            // query 1
            $table = "marken_job";
            $table2 = "marken_job_consignee";
            $data = [
                "id_cliente_subtipo" => 1,
                "id_shipper" => $id_shipper,
                "waybill" => $waybill,
                "content" => $content,
                "pcs" => $pcs,
                "range" => $range,
                "id_marken_type" => $id_marken_type,
                "instrucciones" => $instrucciones,
                "id_caja" => $id_caja,
                "reference" => $reference,
                "contact" => $contacto,
                "contact_telephone" => $contacto_telf,
                "fecha_hora" => $fechaHora,
                "id_usuario_created" => $user_id,
                "created_at" => $fecha_actual,
            ];
            if ($action_name == "new-job") {
                $format = array('%d', '%d', '%s', '%s', '%d', '%s', '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s');
                $queryExistoso = $wpdb->insert($table, $data, $format);
                $id_marken_job = $wpdb->insert_id; //obtemgo el id 
                $wpdb->flush();
                $data2 = [
                    "id_marken_job" => $id_marken_job,
                    "id_pais" => $id_pais,
                    "nombre" => $consigge_nombre,
                    "direccion" => $consigge_direccion,
                    "id_usuario_created" => $user_id,
                    "created_at" => $fecha_actual,
                ];
                $format2 = array('%d', '%d', '%s', '%s',   '%d', '%s');
                $queryExistoso2 = $wpdb->insert($table2, $data2, $format2);
                $wpdb->flush();

                if ($queryExistoso && $queryExistoso2) {
                    // wp_redirect(home_url("marken_export_nuevo") . "?msg=" . 1);
                    wp_redirect(home_url("marken_export"));
                } else {
                    wp_redirect(home_url("marken_export_nuevo") . "?msg=");
                }
            } else if ($action_name == "update-job") {
                unset($data["created_at"]);
                $data["updated_at"] = $fecha_actual;
                $formatUpdated = array('%d', '%d', '%s', '%s', '%d', '%s', '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s');
                $queryExistosoUpdated = $wpdb->update($table, $data, [
                    "id" => $id_marken_jobUpdated
                ], $formatUpdated);
                $wpdb->flush();

                // query 2 updated
                $data2 = [
                    "id_pais" => $id_pais,
                    "nombre" => $consigge_nombre,
                    "direccion" => $consigge_direccion,
                    "id_usuario_created" => $user_id,
                    "created_at" => $fecha_actual,
                ];
                $formatUpdated2 = array('%d', '%s', '%s',   '%d', '%s');
                unset($data2["created_at"]);
                $data2["updated_at"] = $fecha_actual;
                $queryExistoso2Updated = $wpdb->update($table2, $data2, [
                    "id" => $id_marken_consiggneUpdated
                ], $formatUpdated2);
                $wpdb->flush();

                if ($queryExistosoUpdated && $queryExistoso2Updated) {
                    wp_redirect(home_url("marken_export_nuevo") . "?id=$id_marken_jobUpdated&msg=" . 2);
                } else {
                    wp_redirect(home_url("marken_export_nuevo") . "?id=$id_marken_jobUpdated&msg=");
                }
            }
        } else {
            wp_redirect(home_url("marken_export_nuevo") . "?errors=" . $responseValidator["message"]);
        }
    }
}

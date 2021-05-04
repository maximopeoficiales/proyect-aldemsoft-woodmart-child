<?php
date_default_timezone_set('America/Lima');
// Hooks admin-post

// add_action( 'admin_post_nopriv_process_form', 'send_mail_data' );
add_action('admin_post_process_form', 'aldem_post_new_shipper_data');
add_action('admin_post_process_form', 'aldem_post_new_export_job_hielo');
add_action('admin_post_process_form', 'aldem_post_new_export');
add_action('admin_post_process_form', 'aldem_post_new_courier');
// Funcion callback
function aldem_post_new_shipper_data()
{
    $prefix = query_getAldemPrefix();
    $action_name = $_POST["action_name"];
    if ($action_name === "new-shipper" || $action_name === "edit-shipper") {
        $wpdb = query_getWPDB();
        $validations = [
            'nombreShipper'                  =>  'required',
            'direccionShipper'                  => 'required',
            // 'direccion2Shipper'                  => 'required',
            // 'zipShipper'                  => 'required',
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
            // $direccion2Shipper = sanitize_text_field($_POST['direccion2Shipper']);
            // $zipShipper = intval(sanitize_text_field($_POST['zipShipper']));
            $paisShipper = intval(sanitize_text_field($_POST['paisShipper']));
            $siteShipper = intval(sanitize_text_field($_POST['siteShipper']));
            $ubigeoShipper = intval(sanitize_text_field($_POST['ubigeoShipper']));
            $id_user = intval(sanitize_text_field($_POST['id_user']));
            $fecha_actual = date("Y-m-d H:i:s");
            $table = "{$prefix}marken_shipper";
            $data = [
                'descripcion' => $nombreShipper, 'direccion' => $direccionShipper,
                // 'direccion2' => $direccion2Shipper,
                //  'zip' => $zipShipper,
                'id_country' => $paisShipper, 'id_ubigeo' => $ubigeoShipper,
                'id_marken_site' => $siteShipper,
                'id_usuario_created' => $id_user,
                'id_tipo' => 1,
                'created_at' => $fecha_actual
            ];

            if ($action_name === "new-shipper") {
                $format = array(
                    '%s', '%s',
                    // '%s', '%s',
                    '%d', '%d', '%d', '%d', '%d', '%s'
                );
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
                $format = array(
                    '%s', '%s'
                    // ,'%s', '%s'
                    , '%d', '%d', '%d', '%d', '%d', '%s'
                );
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
    $prefix = query_getAldemPrefix();
    $wpdb = query_getWPDB();
    $action_name = $_POST["action_name"];
    if ($action_name === "new-export-hielo") {
        $job = intval(sanitize_text_field($_POST['job']));
        $waybill = sanitize_text_field($_POST['waybill']);
        $validations = [
            'kilos'                  =>  'required|numeric',
            // 'serie'                  => 'required|max:6',
            // 'numero'                  => 'required|max:15',
            // 'precio'                  => 'required|numeric',
            'comentario'                  => 'max:250',
            'job'                  => 'required|numeric',
            'user_id'                  => 'required|numeric',
        ];
        $responseValidator = adldem_UtilityValidator($_POST, $validations);
        if ($responseValidator["validate"]) {
            // se va crear un shipper
            $kilos = doubleval(sanitize_text_field($_POST['kilos']));
            // $serie = sanitize_text_field($_POST['serie']);
            // $numero = sanitize_text_field($_POST['numero']);
            // $precio = doubleval(sanitize_text_field($_POST['precio']));
            $comentario = sanitize_text_field($_POST['comentario']);
            $user_id = intval(sanitize_text_field($_POST['user_id']));
            $fecha_actual = date("Y-m-d H:i:s");
            $table = "{$prefix}marken_job_hielo";
            $data = [
                "id_marken_job" => $job,
                "kilos" => $kilos,
                "id_usuario_created" => $user_id,
                "comentarios" => $comentario,
                // "serie" => $serie,
                // "numero" => $numero,
                // "precio" => $precio,
                "created_at" => $fecha_actual,
            ];
            $format = array(
                '%d', '%s', '%d', '%s'
                // , '%s', '%s', '%s'
                , '%s'
            );
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
    $prefix = query_getAldemPrefix();
    $wpdb = query_getWPDB();
    $action_name = $_POST["action_name"];
    if ($action_name === "new-job" || $action_name === "update-job") {
        $validations = [
            'waybill'                  =>  'required|max:35',
            'id_shipper'                  =>  'numeric',
            'consigge_nombre'                  => 'max:150',
            'consigge_direccion'                  => 'max:150',
            'id_pais'                  => 'numeric',
            'contacto'                  => 'max:50',
            'contacto_telf'                  => 'max:50',
            'reference'                  => 'max:150',
            // 'content'                  => 'required|max:250',
            'pcs'                  => 'required|numeric',
            // 'range'                  => 'required|max:25',
            'id_marken_type'                  => 'required|numeric',
            'id_caja'                  => 'required|numeric',
            'instrucciones'                  => 'max:250',
            'fecha'                  => 'required|max:10|date:Y-m-d',
            'hora'                  => 'required|max:5',
            'user_id'                  => 'required|numeric',
            'ind_activo'                  => 'required|numeric',
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

            // $content = sanitize_text_field($_POST['content']);
            $pcs = intval(sanitize_text_field($_POST['pcs']));
            // $range = sanitize_text_field($_POST['range']);
            $id_marken_type = intval(sanitize_text_field($_POST['id_marken_type']));
            $id_caja = intval(sanitize_text_field($_POST['id_caja']));
            $instrucciones = sanitize_text_field($_POST['instrucciones']);
            $fechaHora = sanitize_text_field($_POST['fecha']) . " " . sanitize_text_field($_POST['hora']) . ":00";
            $fecha_actual = date("Y-m-d H:i:s");
            $user_id = sanitize_text_field($_POST['user_id']);
            $ind_activo = intval(sanitize_text_field($_POST['ind_activo']));

            // campos necesarios para la actualizacion
            $id_marken_jobUpdated = intval(sanitize_text_field($_POST['id_marken_job']));
            $id_marken_consiggneUpdated = intval(sanitize_text_field($_POST['id_marken_consiggne']));


            // query 1
            $table = "{$prefix}marken_job";
            $table2 = "{$prefix}marken_job_consignee";
            $data = [
                "id_cliente_subtipo" => 1,
                "id_shipper" => $id_shipper,
                "waybill" => $waybill,
                // "content" => $content,
                "pcs" => $pcs,
                // "range" => $range,
                "id_marken_type" => $id_marken_type,
                "instrucciones" => $instrucciones,
                "id_caja" => $id_caja,
                "reference" => $reference,
                "contact" => $contacto,
                "contact_telephone" => $contacto_telf,
                "fecha_hora" => $fechaHora,
                "id_usuario_created" => $user_id,
                "ind_activo" => $ind_activo,
                "created_at" => $fecha_actual,
            ];
            if ($action_name == "new-job") {
                $format = array(
                    '%d', '%d', '%s'
                    // , '%s'
                    , '%d',
                    //  '%s',
                    '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s'
                );
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
                $formatUpdated = array('%d', '%d', '%s', '%d',  '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s');
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
                    wp_redirect(home_url("marken_export_nuevo") . "?editjob=$id_marken_jobUpdated&msg=" . 2);
                } else {
                    wp_redirect(home_url("marken_export_nuevo") . "?editjob=$id_marken_jobUpdated&msg=");
                }
            }
        } else {
            wp_redirect(home_url("marken_export_nuevo") . "?errors=" . $responseValidator["message"]);
        }
    }
}
function aldem_post_new_courier()
{
    $prefix = query_getAldemPrefix();
    $wpdb = query_getWPDB();
    $pagina = "marken_courier_nuevo";
    $action_name = $_POST["action_name"];
    if ($action_name === "new-courier" || $action_name === "update-courier") {
        $validations = [
            'job'                  =>  'required|max:35',
            'manifiesto'                  => 'numeric',
            'dua'                  => 'max:20',
            'guia'                  => 'max:12',
            'master'                  => 'max:20',
            'pcs'                  => 'numeric',
            'kilos'                  => 'numeric',
            'id_importador'                  => 'numeric',
            'id_exportador'                  => 'numeric',
            'incoterm'                  => 'numeric',
            'collection'                  => 'date:Y-m-d',
            'delivery'                  => 'date:Y-m-d',
            'instructions'                  => 'max:500',
            'protocolo'                  => 'max:50',
            'id_user'                  => 'required|numeric',
            'id_site'                  => 'numeric',
            'id_handling'                  => 'numeric',
            'tarifa_almacenaje'                  => 'numeric',
            'ind_transporte'                  => 'numeric',
            'ind_servicio_aduana'                  => 'numeric',
            'ind_costo_aduana'                  => 'numeric',
        ];
        if ($action_name == "update-courier") {
            $validations["id_courier_job"] = "required|numeric";
        }
        $responseValidator = adldem_UtilityValidator($_POST, $validations);
        if ($responseValidator["validate"]) {
            // se va crear un shipper
            $waybill = sanitize_text_field($_POST['job']);
            $manifiesto = intval(sanitize_text_field($_POST['manifiesto']));
            $dua = sanitize_text_field($_POST['dua']);
            $guia = sanitize_text_field($_POST['guia']);
            $guia_master = sanitize_text_field($_POST['master']);
            $pcs = intval(sanitize_text_field($_POST['pcs']));
            $peso = doubleval(sanitize_text_field($_POST['kilos']));
            $id_importador = intval(sanitize_text_field($_POST['id_importador']));
            $id_exportador = intval(sanitize_text_field($_POST['id_exportador']));
            $id_incoterm = intval(sanitize_text_field($_POST['incoterm']));
            $schd_collection = sanitize_text_field($_POST['collection']);
            $schd_delivery = sanitize_text_field($_POST['delivery']);
            $instrucciones = sanitize_text_field($_POST['instructions']);
            $protocolo = sanitize_text_field($_POST['protocolo']);
            $fecha_actual = date("Y-m-d H:i:s");
            $id_user = sanitize_text_field($_POST['id_user']);
            $id_site = intval(sanitize_text_field($_POST['id_site']));
            $id_handling = intval(sanitize_text_field($_POST['id_handling']));
            $tarifa_almacenaje = doubleval(sanitize_text_field($_POST['tarifa_almacenaje']));

            $ind_transporte = intval(sanitize_text_field(isset($_POST['ind_transporte']) ? 1 : 0));
            $ind_servicio_aduana = intval(sanitize_text_field(isset($_POST['ind_servicio_aduana']) ? 1 : 0));
            $ind_costo_aduana = intval(sanitize_text_field(isset($_POST['ind_costo_aduana']) ? 1 : 0));
            // query 1
            $table = "{$prefix}marken_job";
            $data = [
                "id_cliente_subtipo" => 3,
                "waybill" => $waybill,
                "manifiesto" => $manifiesto,
                "dua" => $dua,

                "guia" => $guia,
                "guia_master" => $guia_master,
                "peso" => $peso,
                "pcs" => $pcs,

                "id_importador" => $id_importador,
                "id_exportador" => $id_exportador,
                "id_incoterm" => $id_incoterm,
                "instrucciones" => $instrucciones,

                "schd_collection" => $schd_collection,
                "schd_delivery" => $schd_delivery,
                "protocolo" => $protocolo,
                "id_usuario_created" => $id_user,

                "id_site" => $id_site,
                "id_handling" => $id_handling,
                "tarifa_almacenaje" => $tarifa_almacenaje,

                "ind_transporte" => $ind_transporte,
                "ind_servicio_aduana" => $ind_servicio_aduana,
                "ind_costo_aduana" => $ind_costo_aduana,

                "updated_at" => $fecha_actual,
                "created_at" => $fecha_actual,
            ];
            if ($action_name == "new-courier") {
                $format = array(
                    '%d', '%s', '%d', '%s',
                    '%s', '%s', '%s', '%d',
                    '%d', '%d', '%d', '%s',
                    '%s', '%s', '%s', '%d',
                    '%s', '%s', '%s',
                    '%d', '%d', '%d',
                    '%s', '%s'
                );
                $queryExistoso = $wpdb->insert($table, $data, $format);
                $wpdb->flush();
                if ($queryExistoso) {
                    wp_redirect(home_url($pagina) . "?msg=" . 1);
                } else {
                    wp_redirect(home_url($pagina) . "?msg=");
                }
            } else if ($action_name == "update-courier") {
                $id_courier_job = intval(sanitize_text_field($_POST['id_courier_job']));
                unset($data["created_at"]);
                $format2 = $format = array(
                    '%d', '%s', '%d', '%s',
                    '%s', '%s', '%s', '%d',
                    '%d', '%d', '%d', '%s',
                    '%s', '%s', '%s', '%d',
                    '%s', '%s', '%s',
                    '%d', '%d', '%d',
                    '%s'
                );
                if ($wpdb->update($table, $data, [
                    "id" => $id_courier_job
                ], $format2)) {
                    wp_redirect(home_url($pagina) . "?editjob=$id_courier_job&msg=" . 2);
                } else {
                    wp_redirect(home_url($pagina) . "?editjob=$id_courier_job&msg=");
                }
            }
        } else {
            wp_redirect(home_url($pagina) . "?errors=" . $responseValidator["message"]);
        }
    }
    // marken_courier_nuevo
    $pagina2 = "marken_carga_nuevo";
    if ($action_name === "new-cargo" || $action_name === "update-cargo") {
        $validations = [
            'job'                  =>  'required|max:35',
            'manifiesto'                  => 'numeric',
            'dua'                  => 'max:20',
            'guia'                  => 'max:12',
            'master'                  => 'max:20',
            'pcs'                  => 'numeric',
            'kilos'                  => 'numeric',
            'id_importador'                  => 'numeric',
            'id_exportador'                  => 'numeric',
            'incoterm'                  => 'numeric',
            'collection'                  => 'date:Y-m-d',
            'delivery'                  => 'date:Y-m-d',
            'instructions'                  => 'max:500',
            'protocolo'                  => 'max:50',
            'observaciones'                  => 'max:250',
            'id_user'                  => 'required|numeric',

            'id_delivery'                  => 'numeric',
            'ind_costo_delivery'                  => 'numeric',
            'id_handling'                  => 'numeric',
            'ind_servicio_aduana'                  => 'numeric',
            'ind_costo_aduana'                  => 'numeric',

            'tarifa_almacenaje'                  => 'numeric',
            'tarifa_costo'                  => 'numeric',
            'tarifa_impuestos'                  => 'numeric',
        ];
        if ($action_name == "update-cargo") {
            $validations["id_cargo_job"] = "required|numeric";
        }
        $responseValidator = adldem_UtilityValidator($_POST, $validations);
        if ($responseValidator["validate"]) {
            // se va crear un shipper
            $waybill = sanitize_text_field($_POST['job']);
            $manifiesto = intval(sanitize_text_field($_POST['manifiesto']));
            $dua = sanitize_text_field($_POST['dua']);
            $guia = sanitize_text_field($_POST['guia']);
            $guia_master = sanitize_text_field($_POST['master']);
            $pcs = intval(sanitize_text_field($_POST['pcs']));
            $peso = doubleval(sanitize_text_field($_POST['kilos']));
            $id_importador = intval(sanitize_text_field($_POST['id_importador']));
            $id_exportador = intval(sanitize_text_field($_POST['id_exportador']));
            $id_incoterm = intval(sanitize_text_field($_POST['incoterm']));
            $schd_collection = sanitize_text_field($_POST['collection']);
            $schd_delivery = sanitize_text_field($_POST['delivery']);
            $instrucciones = sanitize_text_field($_POST['instructions']);
            $observaciones = sanitize_text_field($_POST['observaciones']);
            $protocolo = sanitize_text_field($_POST['protocolo']);
            $fecha_actual = date("Y-m-d H:i:s");
            $id_user = sanitize_text_field($_POST['id_user']);

            $id_delivery = intval(sanitize_text_field($_POST['id_delivery']));
            $ind_costo_delivery = intval(sanitize_text_field(isset($_POST['ind_costo_delivery']) ? 1 : 0));
            $id_handling = intval(sanitize_text_field($_POST['id_handling']));
            $ind_transporte = intval(sanitize_text_field(isset($_POST['ind_transporte']) ? 1 : 0));
            $ind_servicio_aduana = intval(sanitize_text_field(isset($_POST['ind_servicio_aduana']) ? 1 : 0));
            $ind_costo_aduana = intval(sanitize_text_field(isset($_POST['ind_costo_aduana']) ? 1 : 0));

            $tarifa_almacenaje = doubleval(sanitize_text_field($_POST['tarifa_almacenaje']));
            $tarifa_costo = doubleval(sanitize_text_field($_POST['tarifa_costo']));
            $tarifa_impuestos = doubleval(sanitize_text_field($_POST['tarifa_impuestos']));

            // query 1
            $table = "{$prefix}marken_job";
            $data = [
                "id_cliente_subtipo" => 2,
                "waybill" => $waybill,
                "manifiesto" => $manifiesto,
                "dua" => $dua,

                "guia" => $guia,
                "guia_master" => $guia_master,
                "peso" => $peso,
                "pcs" => $pcs,

                "id_importador" => $id_importador,
                "id_exportador" => $id_exportador,
                "id_incoterm" => $id_incoterm,
                "instrucciones" => $instrucciones,

                "schd_collection" => $schd_collection,
                "schd_delivery" => $schd_delivery,
                "protocolo" => $protocolo,
                "id_usuario_created" => $id_user,
                "observaciones" => $observaciones,

                "id_delivery" => $id_delivery,
                "id_handling" => $id_handling,

                "ind_costo_delivery" => $ind_costo_delivery,
                "ind_servicio_aduana" => $ind_servicio_aduana,
                "ind_costo_aduana" => $ind_costo_aduana,

                "tarifa_almacenaje" => $tarifa_almacenaje,
                "tarifa_costo" => $tarifa_costo,
                "tarifa_impuestos" => $tarifa_impuestos,

                "updated_at" => $fecha_actual,
                "created_at" => $fecha_actual,
            ];
            if ($action_name == "new-cargo") {
                $format = array(
                    '%d', '%s', '%d', '%s',
                    '%s', '%s', '%s', '%d',
                    '%d', '%d', '%d', '%s',
                    '%s', '%s', '%s', '%d', '%s',

                    '%d', '%d',
                    '%d', '%d', '%d', //ids
                    '%s', '%s', '%s', //doubles

                    '%s', '%s'
                );
                $queryExistoso = $wpdb->insert($table, $data, $format);
                $wpdb->flush();
                if ($queryExistoso) {
                    wp_redirect(home_url($pagina2) . "?msg=" . 1);
                } else {
                    wp_redirect(home_url($pagina2) . "?msg=");
                }
            } else if ($action_name == "update-cargo") {
                $id_cargo_job = intval(sanitize_text_field($_POST['id_cargo_job']));
                unset($data["created_at"]);
                $format2 = array(
                    '%d', '%s', '%d', '%s',
                    '%s', '%s', '%s', '%d',
                    '%d', '%d', '%d', '%s',
                    '%s', '%s', '%s', '%d', '%s',

                    '%d', '%d',
                    '%d', '%d', '%d', //ids
                    '%s', '%s', '%s', //doubles

                    '%s',
                );
                if ($wpdb->update($table, $data, [
                    "id" => $id_cargo_job
                ], $format2)) {
                    wp_redirect(home_url($pagina2) . "?editjob=$id_cargo_job&msg=" . 2);
                } else {
                    wp_redirect(home_url($pagina2) . "?editjob=$id_cargo_job&msg=");
                }
            }
        } else {
            wp_redirect(home_url($pagina2) . "?errors=" . $responseValidator["message"]);
        }
    }
}

<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

date_default_timezone_set('America/Lima');
// Hooks admin-post

// add_action( 'admin_post_nopriv_process_form', 'send_mail_data' );
add_action('admin_post_process_form', 'aldem_post_new_shipper_data');
add_action('admin_post_process_form', 'aldem_post_new_export_job_hielo');
add_action('admin_post_process_form', 'aldem_post_new_export');
add_action('admin_post_process_form', 'aldem_post_new_courier');
add_action('admin_post_process_form', 'aldem_post_new_pickup');

// ajax excel
// http://localhost/wp-admin/admin-ajax.php?action=aldem_excel
add_action('wp_ajax_aldem_excel', 'aldem_export_excel');
add_action('wp_ajax_nopriv_aldem_excel', 'aldem_export_excel');
// fin de ajax excel

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
        try {
            $validations = [
                'waybill'                  =>  'required|max:35',
                // shipper
                'remitente'                  =>  'max:250',
                'ciudad'                  => 'max:100',
                'paisremitente'                  => 'max:50',
                'id_marken_site'                  => 'numeric',
                'contacto'                  => 'max:50',
                'contacto_telf'                  => 'max:50',
                'protocolo'                  => 'max:50',

                // consignatorio
                'consignee'                  => 'max:150',
                'consigge_direccion'         => 'max:250',
                'paisconsignee'         => 'max:5',

                'pcs'                  => 'required|numeric',
                'id_marken_type'                  => 'required|numeric',
                'id_caja'                  => 'required|numeric',
                'instrucciones'                  => 'max:250',

                'fecha'                  => 'required',
                'ind_activo'                  => 'required|numeric',
                'user_id'                  => 'required|numeric',
            ];
            if ($action_name == 'update-job') {
                // id_marken_job es el primary key
                $validations["id_marken_job"] = "required|numeric";
            }
            $responseValidator = adldem_UtilityValidator($_POST, $validations);
            if ($responseValidator["validate"]) {
                // se va crear un shipper
                $waybill = sanitize_text_field($_POST['waybill']);
                // consigge
                $remitente = sanitize_text_field($_POST['remitente']);
                $ciudad = sanitize_text_field($_POST['ciudad']);
                $paisremitente = sanitize_text_field($_POST['paisremitente']);
                $id_marken_site = intval(sanitize_text_field($_POST['id_marken_site']));
                $contact = sanitize_text_field($_POST['contact']);
                $contacto_telf = sanitize_text_field($_POST['contacto_telf']);
                $protocolo = sanitize_text_field($_POST['protocolo']);

                $consignee = sanitize_text_field($_POST['consignee']);
                $consigge_direccion = sanitize_text_field($_POST['consigge_direccion']);
                $paisconsignee = sanitize_text_field($_POST['paisconsignee']);

                $pcs = intval(sanitize_text_field($_POST['pcs']));
                $id_marken_type = intval(sanitize_text_field($_POST['id_marken_type']));
                $id_caja = intval(sanitize_text_field($_POST['id_caja']));
                $instrucciones = sanitize_text_field($_POST['instrucciones']);
                $fecha = sanitize_text_field($_POST['fecha']);

                $fecha_actual = date("Y-m-d H:i:s");
                $user_id = intval(sanitize_text_field($_POST['user_id']));
                $ind_activo = intval(sanitize_text_field($_POST['ind_activo']));

                // query 1
                $table = "{$prefix}marken_job";
                $data = [
                    "id_cliente_subtipo" => 1,
                    "waybill" => $waybill,

                    "remitente" => $remitente,
                    "ciudad" => $ciudad,
                    "paisremitente" => $paisremitente,
                    "id_marken_site" => $id_marken_site,
                    "contact" => $contact,
                    "contact_telephone" => $contacto_telf,
                    "protocolo" => $protocolo,

                    "consignee" => $consignee,
                    "direccionconsignee" => $consigge_direccion,
                    "paisconsignee" => $paisconsignee,
                    "pcs" => $pcs,
                    "id_marken_type" => $id_marken_type,
                    "id_caja" => $id_caja,
                    "instrucciones" => $instrucciones,
                    "fecha_hora" => $fecha,
                    "ind_activo" => $ind_activo,
                    "id_usuario_created" => $user_id,
                    "created_at" => $fecha_actual,
                ];
                $format = array(
                    '%d', '%s',
                    '%s', '%s', '%s', '%d', '%s', '%s', '%s',
                    '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s',
                    '%d',  '%d', '%s',
                );
                if ($action_name == "new-job") {

                    $queryExistoso = $wpdb->insert($table, $data, $format);
                    $wpdb->flush();

                    if ($queryExistoso) {
                        wp_redirect(home_url("marken_export_nuevo") . "?msg=" . 1);
                    } else {
                        wp_redirect(home_url("marken_export_nuevo") . "?msg=");
                    }
                } else if ($action_name == "update-job") {
                    // obtengo el id principal del POST
                    $id_marken_job = intval(sanitize_text_field($_POST['id_marken_job']));

                    unset($data["created_at"]);
                    $data["updated_at"] = $fecha_actual;

                    $queryExistosoUpdated = $wpdb->update($table, $data, [
                        "id" => $id_marken_job
                    ], $format);
                    $wpdb->flush();

                    if ($queryExistosoUpdated) {
                        wp_redirect(home_url("marken_export_nuevo") . "?editjob=$id_marken_job&msg=" . 2);
                    } else {
                        wp_redirect(home_url("marken_export_nuevo") . "?editjob=$id_marken_job&msg=");
                    }
                }
            } else {
                wp_redirect(home_url("marken_export_nuevo") . "?errors=" . $responseValidator["message"]);
            }
        } catch (\Throwable $th) {
            echo $th;
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
            'dua'                  => 'max:50',
            'dua2'                  => 'max:15',
            'dua3'                  => 'max:15',
            'dua4'                  => 'max:15',
            // 'guia'                  => 'max:12',
            'master'                  => 'max:20',
            'pcs'                  => 'numeric',
            'kilos'                  => 'numeric',
            // 'id_importador'                  => 'numeric',
            'importador'                  => 'max:250',
            'id_exportador'                  => 'numeric',
            'incoterm'                  => 'numeric',
            'collection'                  => 'date:Y-m-d',
            'delivery'                  => 'date:Y-m-d',
            'instructions'                  => 'max:500',
            'protocolo'                  => 'max:150',
            'id_user'                  => 'required|numeric',
            'id_site'                  => 'numeric',
            'id_handling'                  => 'numeric',

            'tarifa_almacenaje'                  => 'numeric',
            'tarifa_costo'                  => 'numeric',
            'tarifa_impuestos'                  => 'numeric',

            'ind_transporte'                  => 'numeric',
            'ind_servicio_aduana'                  => 'numeric',
            'ind_costo_aduana'                  => 'numeric',

            'fecha_levante'                  => 'max:250',
            'green_channel'                  => 'numeric',
            'dam'                  => 'max:150',

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
            $dua2 = sanitize_text_field($_POST['dua2']);
            $dua3 = sanitize_text_field($_POST['dua3']);
            $dua4 = sanitize_text_field($_POST['dua4']);
            // $guia = sanitize_text_field($_POST['guia']);
            $guia_master = sanitize_text_field($_POST['master']);
            $pcs = intval(sanitize_text_field($_POST['pcs']));
            $peso = doubleval(sanitize_text_field($_POST['kilos']));
            // $id_importador = intval(sanitize_text_field($_POST['id_importador']));
            $importador = sanitize_text_field($_POST['importador']);
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
            $tarifa_costo = doubleval(sanitize_text_field($_POST['tarifa_costo']));
            $tarifa_impuestos = doubleval(sanitize_text_field($_POST['tarifa_impuestos']));

            $ind_transporte = intval(sanitize_text_field(isset($_POST['ind_transporte']) ? 1 : 0));
            $ind_servicio_aduana = intval(sanitize_text_field(isset($_POST['ind_servicio_aduana']) ? 1 : 0));
            $ind_costo_aduana = intval(sanitize_text_field(isset($_POST['ind_costo_aduana']) ? 1 : 0));

            $fecha_levante = sanitize_text_field($_POST['fecha_levante']);
            $green_channel = intval(sanitize_text_field(isset($_POST['green_channel']) ? $_POST['green_channel'] : 0));
            $dam = sanitize_text_field($_POST['dam']);
            // query 1
            $table = "{$prefix}marken_job";
            $data = [
                "id_cliente_subtipo" => 3,
                "waybill" => $waybill,
                "manifiesto" => $manifiesto,

                "dua" => $dua,
                "dua2" => $dua2,
                "dua3" => $dua3,
                "dua4" => $dua4,

                // "guia" => $guia,
                "guia_master" => $guia_master,
                "peso" => $peso,
                "pcs" => $pcs,

                // "id_importador" => $id_importador,
                "importador" => $importador,
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
                "tarifa_costo" => $tarifa_costo,
                "tarifa_impuestos" => $tarifa_impuestos,

                "ind_transporte" => $ind_transporte,
                "ind_servicio_aduana" => $ind_servicio_aduana,
                "ind_costo_aduana" => $ind_costo_aduana,

                // nuevos campos
                "fecha_levante" => $fecha_levante,
                "green_channel" => $green_channel,
                "dam" => $dam,

                "updated_at" => $fecha_actual,
                "created_at" => $fecha_actual,
            ];
            if ($action_name == "new-courier") {
                $format = array(
                    '%d', '%s', '%d', '%s',
                    // duas
                    '%s', '%s', '%s',
                    '%s', '%s', '%d',
                    '%s', '%d', '%d', '%s',
                    '%s', '%s', '%s', '%d',
                    // se agrego tarifas
                    '%s', '%s', '%s', '%s', '%s',
                    '%d', '%d', '%d',

                    '%s', '%d', '%s',

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
                    '%s', '%s', '%s',
                    '%s', '%s', '%d',
                    '%s', '%d', '%d', '%s',
                    '%s', '%s', '%s', '%d',
                    '%s', '%s', '%s', '%s', '%s',
                    '%d', '%d', '%d',

                    '%s', '%d', '%s',

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
            'dua'                  => 'max:50',
            'dua2'                  => 'max:15',
            'dua3'                  => 'max:15',
            'dua4'                  => 'max:15',
            // 'guia'                  => 'max:12',
            'master'                  => 'max:20',
            'pcs'                  => 'numeric',
            'kilos'                  => 'numeric',
            // 'id_importador'                  => 'numeric',
            'importador'                  => 'max:250',
            'id_exportador'                  => 'numeric',
            'incoterm'                  => 'numeric',
            'collection'                  => 'date:Y-m-d',
            'delivery'                  => 'date:Y-m-d',
            'instructions'                  => 'max:500',
            'protocolo'                  => 'max:150',
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

            'fecha_levante'                  => 'max:250',
            'green_channel'                  => 'numeric',
            'dam'                  => 'max:150',


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
            $dua2 = sanitize_text_field($_POST['dua2']);
            $dua3 = sanitize_text_field($_POST['dua3']);
            $dua4 = sanitize_text_field($_POST['dua4']);
            // $guia = sanitize_text_field($_POST['guia']);
            $guia_master = sanitize_text_field($_POST['master']);
            $pcs = intval(sanitize_text_field($_POST['pcs']));
            $peso = doubleval(sanitize_text_field($_POST['kilos']));
            // $id_importador = intval(sanitize_text_field($_POST['id_importador']));
            $importador = sanitize_text_field($_POST['importador']);
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
            // nuevos 3 campos
            $fecha_levante = sanitize_text_field($_POST['fecha_levante']);
            $green_channel = intval(sanitize_text_field(isset($_POST['green_channel']) ? $_POST['green_channel'] : 0));
            $dam = sanitize_text_field($_POST['dam']);


            // query 1
            $table = "{$prefix}marken_job";
            $data = [
                "id_cliente_subtipo" => 2,
                "waybill" => $waybill,
                "manifiesto" => $manifiesto,

                "dua" => $dua,
                "dua2" => $dua2,
                "dua3" => $dua3,
                "dua4" => $dua4,

                // "guia" => $guia,
                "guia_master" => $guia_master,
                "peso" => $peso,
                "pcs" => $pcs,

                // "id_importador" => $id_importador,
                "importador" => $importador,
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

                // nuevos campos
                "fecha_levante" => $fecha_levante,
                "green_channel" => $green_channel,
                "dam" => $dam,

                "updated_at" => $fecha_actual,
                "created_at" => $fecha_actual,
            ];
            if ($action_name == "new-cargo") {
                $format = array(
                    '%d', '%s', '%d', '%s',
                    // duas
                    '%s', '%s', '%s',
                    '%s', '%s', '%d',
                    '%s', '%d', '%d', '%s',
                    '%s', '%s', '%s', '%d', '%s',

                    '%d', '%d',
                    '%d', '%d', '%d', //ids
                    '%s', '%s', '%s', //doubles
                    '%s', '%d', '%s', //nuevos campos

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
                    '%s', '%s', '%s',
                    '%s', '%s', '%d',
                    '%s', '%d', '%d', '%s',
                    '%s', '%s', '%s', '%d', '%s',

                    '%d', '%d',
                    '%d', '%d', '%d', //ids
                    '%s', '%s', '%s', //doubles
                    '%s', '%d', '%s', //nuevos campos


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


function aldem_post_new_pickup()
{
    $prefix = query_getAldemPrefix();
    $wpdb = query_getWPDB();
    $pagina = "marken_pickup_nuevo";
    $action_name = $_POST["action_name"];
    if ($action_name === "new-pickup" || $action_name === "update-pickup") {
        $validations = [
            'id_cliente_subsubtipo'                  =>  'numeric',
            'schd_collection'                  => 'date:Y-m-d',
            'waybill'                  =>  'required|max:35',
            'protocolo'                  => 'max:50',
            'pcs'                  => 'numeric',
            'peso'                  => 'numeric',
            'id_exportador'                  => 'numeric',
            'id_importador'                  => 'numeric',
            'id_ubigeo'                  => 'numeric',
            'id_transporte'                  => 'numeric',
            'id_marken_site'                  => 'numeric',
            'ind_facturado'                  => 'numeric',
            'factura'                  => 'max:25',
            'guia_master'                  => 'max:20',
            'id_user'                  => 'required|numeric',
        ];
        if ($action_name == "update-pickup") {
            $validations["id_courier_job"] = "required|numeric";
        }
        $responseValidator = adldem_UtilityValidator($_POST, $validations);
        if ($responseValidator["validate"]) {
            // variables sanificadas
            $id_cliente_subsubtipo = intval(sanitize_text_field($_POST['id_cliente_subsubtipo']));
            $schd_collection = sanitize_text_field($_POST['schd_collection']);
            $waybill = sanitize_text_field($_POST['waybill']);
            $protocolo = sanitize_text_field($_POST['protocolo']);
            $factura = sanitize_text_field($_POST['factura']);
            $pcs = intval(sanitize_text_field($_POST['pcs']));
            $peso = intval(sanitize_text_field($_POST['peso']));
            $id_exportador = intval(sanitize_text_field($_POST['id_exportador']));
            $id_importador = intval(sanitize_text_field($_POST['id_importador']));
            $id_ubigeo = intval(sanitize_text_field($_POST['id_ubigeo']));
            $id_transporte = intval(sanitize_text_field($_POST['id_transporte']));
            $id_marken_site = intval(sanitize_text_field($_POST['id_marken_site']));
            $ind_facturado = intval(sanitize_text_field($_POST['ind_facturado']));
            $guia_master = (sanitize_text_field($_POST['guia_master']));
            $id_user = intval(sanitize_text_field($_POST['id_user']));
            $fecha_actual = date("Y-m-d H:i:s");

            // query 1
            $table = "{$prefix}marken_job";
            $data = [
                "id_cliente_subtipo" => 4,
                "id_cliente_subsubtipo" => $id_cliente_subsubtipo,
                "schd_collection" => $schd_collection,
                "waybill" => $waybill,
                "factura" => $factura,

                "protocolo" => $protocolo,
                "pcs" => $pcs,
                "peso" => $peso,
                "id_exportador" => $id_exportador,

                "id_importador" => $id_importador,
                "id_ubigeo" => $id_ubigeo,
                "id_transporte" => $id_transporte,
                "id_site" => $id_marken_site,

                "ind_facturado" => $ind_facturado,
                "guia_master" => $guia_master,
                "id_usuario_created" => $id_user,
                "updated_at" => $fecha_actual,
                "created_at" => $fecha_actual,
            ];
            if ($action_name == "new-pickup") {
                $format = array(
                    '%d', '%d', '%s', '%s', '%s',
                    '%s', '%d', '%s', '%d',
                    '%d', '%d', '%d', '%d',
                    '%d', '%s', '%d', '%s', '%s',
                );
                $queryExistoso = $wpdb->insert($table, $data, $format);
                $wpdb->flush();
                if ($queryExistoso) {
                    wp_redirect(home_url($pagina) . "?msg=" . 1);
                } else {
                    wp_redirect(home_url($pagina) . "?msg=");
                }
            } else if ($action_name == "update-pickup") {
                $id_courier_job = intval(sanitize_text_field($_POST['id_courier_job']));
                unset($data["created_at"]);
                $format2 = $format = array(
                    '%d', '%d', '%s', '%s', '%s',
                    '%s', '%d', '%s', '%d',
                    '%d', '%d', '%d', '%d',
                    '%d', '%s', '%d', '%s',
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
}

function aldem_export_excel()
{
    try {
        $fechaReporte = $_GET["fecha_reporte"];
        if (isset($fechaReporte)) {
            if ($_GET["type_report"] == "courier") {
                // creacion de excel para  courier
                $dataReport =  query_getMarkenCourierReporteGeneral1(intval($fechaReporte));
                $fileName = "courier-$fechaReporte.xlsx";
                $spreadsheet = aldem_generateExcelReportCourier($dataReport, $fechaReporte);
                try {
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    $content = file_get_contents($fileName);
                } catch (Exception $e) {
                    exit($e->getMessage());
                }


                header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
                unlink($fileName);
                exit($content);
            } else if ($_GET["type_report"] == "export") {
                $dataReport =  query_getMarkenExportReporteGeneral5(intval($fechaReporte));
                $fileName = "export-$fechaReporte.xlsx";
                $spreadsheet = aldem_getSpreadsheetMarkenReportExport($dataReport, $fechaReporte);
                try {
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    $content = file_get_contents($fileName);
                } catch (Exception $e) {
                    exit($e->getMessage());
                }


                header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
                unlink($fileName);
                exit($content);
            }
        }
        return;
        // $writer->save('hello world.xlsx');
    } catch (\Throwable $th) {
        echo $th;
    }
}

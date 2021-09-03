<?php
// agrego todos los shorcodes a wordpress
add_action('init', 'aldem_init_addshorcodes');
add_action('init', 'aldem_init_shorcode_getParam');

/**
 * Prefijo general de los shortcodes
 * @return string
 */
function aldem_shortcode_prefix(): string
{
    return "aldem_";
}
/**
 * Retorna todos los shorcodes creados
 * @return array
 */
function aldem_getShorcodesViewList(): array
{
    // $nameFile viene de la carpeta views
    // name-shorcode => aldem_$name, view => $nameFile
    return [
        ["name" => "form_mes_ano", "view" => "extras/form_mes_ano"],
        ["name" => "btn_eliminar_job", "view" => "extras/btn_eliminar_job"],
        ["name" => "marken_shipper", "view" => "shippers"],
        ["name" => "marken_export_nuevo", "view" => "export_nuevo"],
        ["name" => "marken_export_job_hielo_nuevo", "view" => "export_job_hielo_nuevo"],
        ["name" => "marken_export_reporte", "view" => "export_reporte"],
        ["name" => "marken_courier_nuevo", "view" => "courier_nuevo"],
        ["name" => "marken_carga_nuevo", "view" => "carga_nuevo"],
        ["name" => "marken_pick_nuevo", "view" => "pickup_nuevo"],
        ["name" => "costos", "view" => "costos"],
        ["name" => "costos_admin", "view" => "costos_admin"],
        // reportes
        ["name" => "marken_export_reporte_general", "view" => "reports/export_reporte_general"],
        ["name" => "marken_courier_reporte_general", "view" => "reports/courier_reporte_general"],
        ["name" => "marken_carga_reporte_general", "view" => "reports/carga_reporte_general"],
    ];
}

/**
 * Carga automatica de todos los shortcodes
 * @return void
 */
function aldem_init_addshorcodes()
{
    foreach (aldem_getShorcodesViewList() as $shorcode) {
        add_shortcode(aldem_shortcode_prefix() . $shorcode["name"], function ($atts) use ($shorcode) {
            ob_start();
            // include file (contents will get saved in output buffer)
            aldem_cargar_view($shorcode["view"], $atts);
            // print_r($shorcode["view"]);

            // save and return the content that has been output
            return  ob_get_clean();
        });
    }
}

/**
 * Shortcodes Custom
 * @return void
 */
function aldem_init_shorcode_getParam()
{
    add_shortcode(aldem_shortcode_prefix() . "datatableGetParam", function ($atts) {
        $datatableID = $atts["idtable"];
        $getParam = $atts["nameparam"];
        // si es envias nameparam
        if (is_null($getParam)) {
            echo do_shortcode("[wpdatatable id={$datatableID}]");
            // si el name param existe 
        } else if ($getParam != "" && isset($_GET[$getParam])) {
            $var1 = $_GET[$getParam];
            echo   do_shortcode("[wpdatatable id={$datatableID} var1={$var1}]");
        } else {
            // si no te falta enviar parametro url
            echo "<h2>Falta parametro por url: {$getParam}</h2>";
        }
    });


    add_shortcode(aldem_shortcode_prefix() . "redirectinput", function ($atts) {
        // [nameshortcode inputtype="text" destinationurl="marken_shipper" nameparam="id" textlabel="Tu nombre (requerido)" placeholder="Ingresa tu nombre" required="true" width="100%"]
        $inputtype = $atts["inputtype"];
        $destinationUrL = $atts["destinationurl"];
        $nameParam = $atts["nameparam"];
        $textlabel = $atts["textlabel"];
        $placeholder = $atts["placeholder"];
        $width = $atts["width"]  != ""  ? $atts["width"] : "30%";
        $required = $atts["required"] == "true" ? "required" : "";
        // print_r($atts);
        echo "
        <style>
            @media (max-width: 576px) {
                .input-resposive-shortcode {
                width: 100% !important;
                }
            }    
            .input-resposive-shortcode{
                    width:{$width}
            }
            
        </style>
        <form action='/{$destinationUrL}' method='get' class='input-resposive-shortcode'>
            <p><label>{$textlabel}</p>
            <div class='input-group mb-3'>
                <input type='{$inputtype}' name='{$nameParam}' class='form-control' {$required} placeholder='{$placeholder}'>
                <div class='input-group-append'>
                    <button class='btn' type='submit'>Enviar</button>
                </div>
            </div>
        </form>
        
        ";
    });
    // add_shortcode(aldem_shortcode_prefix() . "trycacth", function ($atts) {
    //     try {
    //         $shortcode = $atts["shortcode"];
    //         if (!empty($shortcode)) {
    //             echo do_shortcode($shortcode);
    //         }
    //     } catch (\Throwable $th) {
    //         echo $th;
    //     }
    // });
}

/**
 * Verifica si existe un shortcode , si existe agrega styles
 * @return void
 */
function aldem_verify_exists_shortcodes(): bool
{
    foreach (aldem_getShorcodesViewList() as $shorcode) {
        if (shortcode_exists(aldem_shortcode_prefix() . $shorcode["name"])) {
            return true;
        }
    }
    return false;
}

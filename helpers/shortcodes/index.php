<?php
// agrego todos los shorcodes a wordpress
add_action('init', 'aldem_init_addshorcodes');

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
        ["name" => "marken_shipper", "view" => "shippers"],
    ];
}

/**
 * Carga automatica de todos los shortcodes
 * @return void
 */
function aldem_init_addshorcodes()
{
    foreach (aldem_getShorcodesViewList() as $shorcode) {
        add_shortcode(aldem_shortcode_prefix() . $shorcode["name"], function () use ($shorcode) {
            ob_start();
            // include file (contents will get saved in output buffer)
            aldem_cargar_view($shorcode["view"]);
            // print_r($shorcode["view"]);

            // save and return the content that has been output
            return  ob_get_clean();
        });
    }
}

function aldem_verify_exists_shortcodes(): bool
{
    foreach (aldem_getShorcodesViewList() as $shorcode) {
        if (shortcode_exists(aldem_shortcode_prefix() . $shorcode["name"])) {
            return true;
        }
    }
    return false;
}

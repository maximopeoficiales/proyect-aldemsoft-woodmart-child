<?php
// necesario para cargar el autoload.php para todo lo que venga de vendor

require aldem_get_directory_helper() . "vendor/autoload.php";
// funciones generales by maximoprog

/**
 * Carga un archivo php especifico de la carpeta views
 * @param string $name Nombre del archivo
 * @return void
 */
function aldem_cargar_view($name)
{
     require(aldem_get_view_directory_helper() . "$name.php");
}
/**
 * Obtiene la ruta de la carpeta helpers
 * @return string
 */
function aldem_get_directory_helper(): string
{
     return get_stylesheet_directory() . '/helpers/';
}
/**
 * Obtiene la ruta de la carpeta helpers/views
 * @return string
 */
function aldem_get_view_directory_helper(): string
{
     return get_stylesheet_directory() . "/helpers/views/";
}
/**
 * Obtiene la url de una imagen la carpeta helpers/imgs
 * @param string $name Nombre de la imagen - especifica el formato
 * @return string
 */
function aldem_get_image_url_helper($name): string
{
     return get_template_directory_uri() . "/helpers/public/imgs/$name";
}
/**
 * Obtiene la url de un archivo JS de la carpeta helpers/js
 * @param string $name Nombre del script - especifica el formato
 * @return string
 */
function aldem_get_js_url_helper($name): string
{
     return get_template_directory_uri() . "/helpers/public/js/$name";
}
/**
 * Obtiene la url de un archivo CSS de la carpeta helpers/css
 * @param string $name Nombre del css - especifica el formato
 * @return string
 */

function aldem_get_css_url_helper($name): string
{
     return get_template_directory_uri() . "/helpers/public/css/$name.css";
}


// llamo al archivo a todos los archivos necesarios para el funcionamiento del miniframework
require aldem_get_directory_helper() . "utilities/utilities.php";
require aldem_get_directory_helper() . "querys/index.php";
require aldem_get_directory_helper() . "routes/endpoints.php";
require aldem_get_directory_helper() . "helpers/helpers.php";
require aldem_get_directory_helper() . "controllers/controllers.php";
require aldem_get_directory_helper() . "registers/index.php";
require aldem_get_directory_helper() . "shortcodes/index.php";

<?php
add_action("wp_enqueue_scripts", "llamar_estilos");
function llamar_estilos()
{
    wp_enqueue_style("parent-style", get_template_directory_uri() . "/style.css");
}

require get_template_directory() . "-child/helpers/functions.php";

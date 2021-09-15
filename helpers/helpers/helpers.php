<?php

function aldem_transform_text_p(string $text): string
{
    $textTransform = "";
    $arrayText = explode("-", $text);
    foreach ($arrayText as $string) {
        $textTransform .= "<p>$string</p>";
    }
    return  str_replace("<p></p>", "", $textTransform);
}



function aldem_show_message_custom($msg_success, $msg_act, $msg_error): void
{

    if (isset($_GET['errors'])) {
        echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>' . aldem_transform_text_p($_GET['errors']) . '</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
            ';
    }
    if (isset($_GET['msg'])) {


        if ($_GET['msg'] == '1') {
            echo '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>' . $msg_success . '</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                ';
        } else if ($_GET['msg'] == '2') {
            echo '
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>' . $msg_act . '</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                ';
        } else {
            echo '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>' . $msg_error . '</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                ';
        }
    }
}

/**
 * Crear un input hidden con el action como parametro
 * @return void
 */
function aldem_set_action_name(string $nameAction): void
{
    echo '
    <input type="hidden" name="action_name" value="' . $nameAction . '">';
}
/**
 * Crea un input hidden procces_form para que sea procesado
 * @return void
 */
function aldem_set_proccess_form(): void
{
    echo '
    <input type="hidden" name="action" value="process_form">
    ';
}
/**
 * Crea un input hidden con un nombre y valor
 * @return void
 */
function aldem_set_input_hidden($name, $value, $required = true): void
{
    $required = $required ? "required" : "";
    echo "<input type='hidden' name='$name' value='$value' id='$name' $required>";
}

/**
 * Crear un script necesario para traducir los datatables
 * @return void
 */
function aldem_datatables_in_spanish(): void
{
    echo ('  $.extend(true, $.fn.dataTable.defaults, {
    "language": {
        "decimal": ",",
        "thousands": ".",
        "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "infoPostFix": "",
        "infoFiltered": "(filtrado de un total de _MAX_ registros)",
        "loadingRecords": "Cargando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "processing": "Procesando...",
        "search": "Buscar:",
        "searchPlaceholder": "Término de búsqueda",
        "zeroRecords": "No se encontraron resultados",
        "emptyTable": "Ningún dato disponible en esta tabla",
        "aria": {
            "sortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        //only works for built-in buttons, not for custom buttons
        "buttons": {
            "create": "Nuevo",
            "edit": "Cambiar",
            "remove": "Borrar",
            "copy": "Copiar",
            "csv": "fichero CSV",
            "excel": "tabla Excel",
            "pdf": "documento PDF",
            "print": "Imprimir",
            "colvis": "Visibilidad columnas",
            "collection": "Colección",
            "upload": "Seleccione fichero...."
        },
        "select": {
            "rows": {
                _: "%d filas seleccionadas",
                0: "clic fila para seleccionar",
                1: "una fila seleccionada"
            }
        }
    }
});');
}


/**
 * Retorna todos los meses del año
 * @return void
 */
function aldem_getMonths(): array
{
    return array(
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    );
}

function aldem_getYears(): array
{
    $arrayAno = [];
    $anoActual = intval(date("Y"));
    $anosAtras = 30;
    $anosAdelante = 30;

    for ($i = $anoActual - $anosAtras; $i < $anoActual + $anosAdelante; $i++) {
        array_push($arrayAno, $i);
    }
    return $arrayAno;
}

/**
 * Retorna True si el usuario logueado es el usuario creador
 * @return bool
 */
function aldem_isUserCreated($id_usuario_created): bool
{
    // $validation = false;
    // $validation = (intval($id_usuario_created) === intval(get_current_user_id())) ? true : false;

    // if (intval(get_current_user_id()) === 42) {
    //     $validation = true;
    // }
    // return $validation;
    return true;
}

/**
 * Escribe en la pantalla que no tiene acceso
 * @return void
 */
function aldem_noAccess(): void
{
    echo "
        <h1 class='text-center'>No tienes acceso a esta pagina</h1>
    ";
}

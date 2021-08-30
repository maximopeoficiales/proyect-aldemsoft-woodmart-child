<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Rakit\Validation\Validator;

function adldem_UtilityValidator($data, $validations)
{
    $validator = new Validator;

    $validator->setMessage('required', "El campo :attribute es requerido");
    $validator->setMessage('numeric', "El campo :attribute debe ser un numero");
    $validator->setMessage('max', "El campo :attribute debe tener maximo de :max caracteres");
    $validator->setMessage('min', "El campo :attribute debe tener minimo de :min caracteres");
    $validator->setMessage('email', "El campo :attribute debe tener un formato de email");

    $validation = $validator->make($data, $validations);
    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $text = "";
        foreach ($errors->firstOfAll() as $key => $value) {
            $text .= strval($value) . ". -";
        }
        return ["validate" => false, "message" => $text];
    } else {
        return ["validate" => true];
    }
}
function query_getAldemPrefix()
{
    global $wpdb;
    $table_prefix = $wpdb->prefix . "aldem_";
    return $table_prefix;
}
function query_getWPDB(): wpdb
{
    global $wpdb;
    return $wpdb;
}

function aldem_cargarStyles(): void
{
    require aldem_get_directory_helper() . "public/styles.php";
}


function aldem_getRoleCodes()
{
    return [
        ["id" => 41, "name" => "(41) Despachador de aduanas"],
        ["id" => 31, "name" => "(31) Depósito temporal, Zed"],
        ["id" => 33, "name" => "(33) Depósito temporal EER"],
        ["id" => 32, "name" => "(32) Depósito aduanero"],
        ["id" => 34, "name" => "(34) Depósito temporal Postal"],
        ["id" => 50, "name" => "(50) Empresa EER"],
        ["id" => 61, "name" => "(61) Terminal Portuario"],
        ["id" => 54, "name" => "(54) Terminal de carga aéreo"],
        ["id" => 73, "name" => "(73) Zofratacna"],
    ];
}

function aldem_get_currency(string $currency)
{
    global $WOOCS;
    return $WOOCS->get_currencies()[$currency]['rate'];
}

function aldem_selectRoleCodes($nameInput = "roleCode", $rolCodeDefault = 31)
{
    $html = "
    

    <div class='input-group' style='
    flex-wrap: nowrap;'>
    <select class='custom-select' name='${nameInput}' id='${nameInput}'>
    ";
    foreach (aldem_getRoleCodes() as $roleCode) {
        $selected = $roleCode["id"] === $rolCodeDefault ? " selected" : "";
        $html .= "<option value='{$roleCode['id']}' $selected>
        {$roleCode['name']}        
        </option>";
    }
    $html .= "    
    </select> 
    <div class='input-group-append'>
        <button id='btnBuscarDua' type='button' class='btn ' style='background-color: #344176; color: white; '>
        <i class='fa fa-search-plus mr-1'></i> Buscar por DUA</button>
    </div>
    </div>
    ";
    echo $html;
}

function aldem_generate_arrayAZ(string $lengthAZ): array
{
    $arrayAZ = [];
    for ($x = 'A'; $x != $lengthAZ; $x++) {
        array_push($arrayAZ, $x);
    }
    return $arrayAZ;
}

function aldem_setColorExcel(string $color): string
{
    return str_replace("#", "", $color);
}
function aldem_getFirstCoordExcel($coord): string
{
    return explode(":", $coord)[0];
}

function aldem_cCellExcel(Spreadsheet  $spreadsheet, $range, $text, $bold = false, $fontSize = 10, $color = Color::COLOR_WHITE, $bgColor = Color::COLOR_BLACK)
{
    $excel = $spreadsheet->getActiveSheet();
    if (strpos($range, ":") !== false) {
        $excel = $excel->mergeCells($range);
    }
    $styleExcel = $excel->setCellValue(aldem_getFirstCoordExcel($range), $text)->getStyle($range);
    $styleExcel->getAlignment()->setHorizontal('center')->setVertical('center')->setWrapText(true);
    $styleExcel->getFont()->setBold($bold)->setSize($fontSize)->getColor()->setARGB(aldem_setColorExcel($color));
    $styleExcel->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(aldem_setColorExcel($bgColor));
    return $spreadsheet;
}
function aldem_getSpreadsheet1(): Spreadsheet
{

    $tipoCambio = "4.1";
    $gray = "#ACB9CA";
    $blue = "#333F4F";
    $grayWhite = "##D6DCE4";
    $yellow = "#FFFF00";
    $blueWhite = "#2F75B5";
    $blackWhite = "#808080";

    $spreadsheet = new Spreadsheet();

    // titulos
    // TARIFA PICK UP (RECOJO DE MUESTRAS
    $rangeRecojoMuestras = "B8:T8";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeRecojoMuestras, "TARIFA PICK UP (RECOJO DE MUESTRAS)", true, 18, Color::COLOR_WHITE, Color::COLOR_BLACK);

    // TARIFA HIELO SECO
    $rangeTarifaHieloSeco = "U8:AN8";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTarifaHieloSeco, "TARIFA HIELO SECO", true, 18, Color::COLOR_WHITE, Color::COLOR_BLACK);


    // CANTIDAD DE PICK UP (RECOJOS
    $rangeCantidadPickUp = "B9:J9";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeCantidadPickUp, "CANTIDAD DE PICK UP (RECOJOS)", true, 14, Color::COLOR_WHITE, $blue);

    // FECHA 1
    $rangeFecha1 = "B10:B12";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeFecha1, "FECHA", true, 11, Color::COLOR_BLACK, $blackWhite);

    // GUIAS DE LIMA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C10:F10", "GUIAS DE LIMA", true, 11, Color::COLOR_BLACK, $gray);

    // GUIAS DE PROVINCIA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "G10:J10", "GUIAS DE PROVINCIA", true, 11, Color::COLOR_BLACK, $blackWhite);

    // FROZEN
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C11:F11", "FROZEN", true, 11, Color::COLOR_BLACK, $gray);

    // FROZEN2
    $spreadsheet = aldem_cCellExcel($spreadsheet, "G11:J11", "FROZEN", true, 11, Color::COLOR_BLACK, $blackWhite);

    // AMBIENTE1
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C12", "Ambiente", true, 11, Color::COLOR_BLACK, $gray);

    // BIO I II III
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D12", "BIO I", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "E12", "BIO II", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F12", "BIO III", true, 11, Color::COLOR_BLACK, $gray);

    // AMBIENTE2
    $spreadsheet = aldem_cCellExcel($spreadsheet, "G12", "AMBIENTE", true, 11, Color::COLOR_BLACK, $blackWhite);

    // BIO I II III
    $spreadsheet = aldem_cCellExcel($spreadsheet, "H12", "BIO I", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "I12", "BIO II", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J12", "BIO III", true, 11, Color::COLOR_BLACK, $blackWhite);


    // total guias marken
    $rangeTotalGuiasMarken = "K9:K12";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTotalGuiasMarken, "TOTAL GUIAS MARKEN", true, 9, Color::COLOR_BLACK, $yellow);

    // SEGUNDA PARTE COBRO A MARKEN PICK UP DOLARES USD
    // GUIAS DE LIMA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "L10:O10", "GUIAS DE LIMA", true, 11, Color::COLOR_BLACK, $gray);

    // GUIAS DE PROVINCIA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "P10:S10", "GUIAS DE PROVINCIA", true, 11, Color::COLOR_BLACK, $blackWhite);

    // FROZEN
    $spreadsheet = aldem_cCellExcel($spreadsheet, "L11:O11", "FROZEN", true, 11, Color::COLOR_BLACK, $gray);

    // FROZEN2
    $spreadsheet = aldem_cCellExcel($spreadsheet, "P11:S11", "FROZEN", true, 11, Color::COLOR_BLACK, $blackWhite);

    // AMBIENTE1
    $spreadsheet = aldem_cCellExcel($spreadsheet, "L12", "Ambiente", true, 11, Color::COLOR_BLACK, $gray);

    // BIO I II III
    $spreadsheet = aldem_cCellExcel($spreadsheet, "M12", "BIO I", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "N12", "BIO II", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "O12", "BIO III", true, 11, Color::COLOR_BLACK, $gray);

    // AMBIENTE2
    $spreadsheet = aldem_cCellExcel($spreadsheet, "P12", "AMBIENTE", true, 11, Color::COLOR_BLACK, $blackWhite);

    // BIO I II III
    $spreadsheet = aldem_cCellExcel($spreadsheet, "Q12", "BIO I", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "R12", "BIO II", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "S12", "BIO III", true, 11, Color::COLOR_BLACK, $blackWhite);


    // COBRO A MARKEN PICK UP  DOLARES USD
    $rangeCobroMarkenPickUP = "L9:S9";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeCobroMarkenPickUP, "COBRO A MARKEN PICK UP  DOLARES USD", true, 14, Color::COLOR_WHITE, $blue);

    // TOTAL PICK UP DOLARES USD
    $rangeTotalPickUp = "T9:T12";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTotalPickUp, "TOTAL PICK UP DOLARES USD", true, 9, Color::COLOR_BLACK, $yellow);

    // TOTAL CANTIDAD DE HIELO SECO KG
    $rangeTotalHieloSeco = "U9:U11";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTotalHieloSeco, "TOTAL CANTIDAD DE HIELO SECO KG", true, 9, Color::COLOR_WHITE, $blackWhite);

    // PARA MUESTRAS EXPORTACIÓN x MAWB
    $spreadsheet = aldem_cCellExcel($spreadsheet, "U12", "PARA MUESTRAS EXPORTACIÓN x MAWB", true, 9, Color::COLOR_BLACK, $yellow);

    // OBSERVACIONES
    $rangeObservaciones = "V9:X12";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeObservaciones, "OBSERVACIONES", true, 12, Color::COLOR_WHITE, $blackWhite);

    // COSTO (INVERSIÓN) DE HIELO SECO SECO EN LIMA SOLES (SIN IGV)
    $rangeCostoHieloSeco = "Y9:Y12";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeCostoHieloSeco, "COSTO (INVERSIÓN) DE HIELO SECO SECO EN LIMA SOLES (SIN IGV)", true, 9, Color::COLOR_WHITE, $blackWhite);

    // COSTO (INVERSIÓN) DE HIELO SECO SECO EN LIMA DOLARES
    $rangeCostoHieloSecoDolares = "Z9:Z11";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeCostoHieloSecoDolares, "COSTO (INVERSIÓN) DE HIELO SECO SECO EN LIMA DOLARES", true, 9, Color::COLOR_WHITE, $blackWhite);

    // TIPO DE CAMBIO
    $rangeTipoCambio = "Z12";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTipoCambio, "Tipo de CAMBIO: $tipoCambio", true, 11, Color::COLOR_WHITE, $blackWhite);

    // TARIFA DE HIELO SECO
    $rangeTarifaHieloSeco = "AA9:AF9";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTarifaHieloSeco, "TARIFA DE HIELO SECO", true, 11, Color::COLOR_WHITE, $blackWhite);

    // texto DOLARES
    $rangeTextDolares = "AA10:AF10";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTextDolares, "DOLARES", true, 11, Color::COLOR_WHITE, $blackWhite);

    // LIMA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AA11:AC11", "LIMA", true, 11, Color::COLOR_BLACK, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AD11:AF11", "PROVINCIA", true, 11, Color::COLOR_BLACK, $blackWhite);

    // LIMA BIO
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AA12", "BIO I", true, 11, Color::COLOR_BLACK, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AB12", "BIO II", true, 11, Color::COLOR_BLACK, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AC12", "BIO III", true, 11, Color::COLOR_BLACK, $grayWhite);
    // PROVINCIA BIO
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AD12", "BIO I", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AE12", "BIO II", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AF12", "BIO III", true, 11, Color::COLOR_BLACK, $blackWhite);

    // UTILIDAD HIELO SECO

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AG9:AH9", "UTILIDAD HIELO SECO", true, 11, Color::COLOR_BLACK, $grayWhite);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AG10:AH10", "DOLARES", true, 11, Color::COLOR_BLACK, $grayWhite);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AG11:AH12", "LIMA", true, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

    // HANDLING
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AI9:AJ10", "HANDLING", true, 11, Color::COLOR_WHITE, $blackWhite);

    // TARIFA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AI11", "TARIFA", true, 11, Color::COLOR_BLACK, $yellow);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AI12", "DOLARES", true, 11, Color::COLOR_BLACK, $yellow);

    // GASTO
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AJ11", "GASTO", true, 11, Color::COLOR_BLACK, $grayWhite);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AJ12", "DOLARES", true, 11, $blue, $grayWhite);

    // UTILIDAD
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AK9", "DOLARES", true, 11, $blue, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AK10", "HANDLING", true, 11, $blue, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AK11:AK12", "DOLARES", true, 11, $blue, $grayWhite);

    // TARIFA DE TRAMITE OPERATIVO

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AL9:AL11", "TARIFA DE TRAMITE OPERATIVO", true, 11, Color::COLOR_WHITE, $blue);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AL12", "DOLARES", true, 11, Color::COLOR_WHITE, $blue);
    // TARIFA DE CAJA DE EMBALAJE MUESTRAS DE AMBIENTE
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AM9:AM11", "TARIFA DE CAJA DE EMBALAJE MUESTRAS DE AMBIENTE", true, 11, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AM12", "DOLARES", true, 11, Color::COLOR_WHITE, $blue);
    // COSTO DE ALDEM POR EMBALAR (Costo del tiempo que se usa para embalar las cajas)
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AN9:AN11", "COSTO DE ALDEM POR EMBALAR (Costo del tiempo que se usa para embalar las cajas)", true, 11, $blue, $grayWhite);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AN12", "DOLARES", true, 11, $blue, $grayWhite);

    // RESUMEN
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AP12", "TOTAL INGRESOS", true, 11, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AQ12", "TOTAL GASTOS", true, 11, $blue, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AR12", "TOTAL", true, 11, $blue, $yellow);


    foreach (aldem_generate_arrayAZ("AZ") as $col) {
        $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
    }
    return $spreadsheet;
}


function aldem_generateExcelReportCourier(): Spreadsheet
{

    $tipoCambio = "4.1";
    $gray = "#ACB9CA";
    $blue = "#002060";
    $grayWhite = "##D6DCE4";
    $yellow = "#FFFF00";
    $blueWhite = "#2F75B5";
    $blackWhite = "#808080";
    $skyblue = "#00B0F0";
    $green = "#76923C";
    $spreadsheet = new Spreadsheet();

    // data
    $fecha = "202104";
    $totalCostoMarken = query_getCostoMarken($fecha)->total_costo_marken;
    $totalGuias = query_getTotalGuias($fecha)->total_guias;
    $totalGuiasCourier = query_getTotalGuiasExport($fecha)->total_guias_courier;

    $transporteGuiaHija = query_servicioTransportePorGuiaHija();
    $courierReportC = query_courierReportQueryC();
    $costoHandlingMaster = query_getCostoHandlingPorMaster();
    // $markenCourierReporteGeneral = query_getMarkenCourierReporteGeneral($fecha);

    // titulos
    //TARIFA SERVICIO IMPORTACIONES COURIER				
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F3:J3", "TARIFA SERVICIO IMPORTACIONES COURIER", true, 18, Color::COLOR_WHITE, $skyblue);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "H4:I4", $fecha, true, 18, Color::COLOR_WHITE, $skyblue);


    // QueryA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "A7:B7", "Total costo marken", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C7", $totalCostoMarken, true, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "A8:B8", "Total guias marken", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C8", $totalGuias, true, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "A9:B9", "Total Guías Marken Courier", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C9", $totalGuiasCourier, true, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

    // QueryB
    $spreadsheet = aldem_cCellExcel($spreadsheet, "E8", "LIMA", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "E9", "PROVINCIA", true, 9, Color::COLOR_WHITE, $blue);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "F6:G6", "SERV. TRANSPORTE POR GUIA HIJA", true, 9, Color::COLOR_WHITE, $green);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F7", "PESO", true, 9, Color::COLOR_WHITE, $green);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "G7", "TARIFA (USD)", true, 9, Color::COLOR_WHITE, $green);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F8", $transporteGuiaHija[0]->peso, false, 9, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F9", $transporteGuiaHija[1]->peso, false, 9, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "G8", $transporteGuiaHija[0]->tarifa, false, 9, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "G9", $transporteGuiaHija[1]->tarifa, false, 9, Color::COLOR_BLACK, Color::COLOR_WHITE);

    // Query C
    $spreadsheet = aldem_cCellExcel($spreadsheet, "I6:I7", "TARIFA ALMACENAJE POR GUIA HIJA (USD)", true, 9, Color::COLOR_WHITE, $green);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J6:J7", "TARIFA SERV. ADUANA POR GUIA HIJA (USD)", true, 9, Color::COLOR_WHITE, $green);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K6:L7", "COSTO OPERATIVO ES FACTURADO CUANDO NO REALIZAMOS NINGUN SERVICIO / POR GUIA HIJA (USD)", true, 9, Color::COLOR_WHITE, $green);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "I8:I9", $courierReportC[0]->tarifa, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J8:J9", $courierReportC[1]->tarifa, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K8:L9", $courierReportC[2]->tarifa, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

    // query D
    $spreadsheet = aldem_cCellExcel($spreadsheet, "N6:N7", "COSTO HANDLING POR MASTER (USD)", true, 9, Color::COLOR_WHITE, $green);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "N8:N9", $costoHandlingMaster[0]->tarifa, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "N10:N11", $costoHandlingMaster[1]->tarifa, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "N12:N13", $costoHandlingMaster[2]->tarifa, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);


    // Query E
    $spreadsheet = aldem_cCellExcel($spreadsheet, "A16:A17", "MANIFIESTO EER", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B16:B17", "DUA", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C16:C17", "GUIA HIJA", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D16:D17", "FECHA DE ENTREGA", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "E16:E17", "GUIA MASTER", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F16:F17", "PROTOCOLO", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "G16:G17", "EXPORTADOR (MIAMI)", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "H16:H17", "EXPORTADOR (LIMA)", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "I16:I17", "EXPORTADOR (CANTIDAD DE BULTOS)", true, 9, Color::COLOR_WHITE, $blue);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "J16:J17", "PESO", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K16:K17", "DELIVERY (ENTREGA LOCAL) X GUIA HIJA -  DOLARES", true, 9, Color::COLOR_WHITE, $blueWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "L16:L17", "COSTO SERVICIO TRANSPORTE POR GUIA HIJA - DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "M16:M17", "TARIFA SERVICIO
    ADUANA POR GUIA HIJA - DOLARES", true, 9, Color::COLOR_WHITE, $blueWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "N16:N17", "COSTO SERV. ADUANA
    POR GUIA HIJA - DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "O16:O17", "TARIFA HANDLING POR GUIA HIJA -  USD", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "P16:P17", "COSTO HANDLING POR GUIA HIJA - DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "Q16:Q17", "TARIFA ALMACENAJE POR GUIA HIJA -  USD", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "R16:R17", "COSTO ALMACENAJE POR GUIA HIJA - DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "S16:S17", "IMPUESTOS POR GUIA HIJA - USD", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "T16:T17", "TARIFA TRAMITE OPEATIVO / GUIA HIJA - USD", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "U16:U17", "COBRO A MARKEN POR GUIA HIJA", true, 9, COLOR::COLOR_BLACK, $yellow);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "V16:V17", "COSTO VARIABLE", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "W16:W17", "TOTAL INGRESOS DOLARES", true, 9, COLOR::COLOR_WHITE, COLOR::COLOR_BLACK);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "X16:X17", "TOTAL GASTOS DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "Y16:Y17", "TOTAL UTILIDAD DOLARES", true, 9, COLOR::COLOR_BLACK, $yellow);
    // falta rellenar con data



    foreach (aldem_generate_arrayAZ("AZ") as $col) {
        $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
    }
    return $spreadsheet;
}

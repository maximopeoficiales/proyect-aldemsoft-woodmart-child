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
function aldem_getUrlExcel($tipo, $fecha)
{
    $urlAjax = admin_url("admin-ajax.php");
    return "$urlAjax?action=aldem_excel&type_report=$tipo&fecha_reporte=$fecha";
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
// valida si es nan o empty o infinty o number
function aldem_isValidNumber($number)
{
    $number = floatval($number);
    $number = empty($number) ? 0 : $number;
    $number = is_infinite($number) ? 0 : $number;
    $number = is_numeric($number) ? $number : 0;
    return number_format($number, 2);
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

function aldem_format_excelByAZ(Spreadsheet $spreadsheet, string $AZ)
{
    foreach (aldem_generate_arrayAZ($AZ) as $col) {
        $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
    }
    return $spreadsheet;
}
function aldem_setColorExcel(string $color): string
{
    return str_replace("#", "", $color);
}
function aldem_getFirstCoordExcel($coord): string
{
    return explode(":", $coord)[0];
}
function aldem_generateFreezeColum(Spreadsheet $spreadsheet, int  $numberColumn, string $rangoLetterFreeze): Spreadsheet
{

    foreach (aldem_generate_arrayAZ($rangoLetterFreeze) as $letter) {
        $spreadsheet->getActiveSheet()->freezePane($letter . $numberColumn);
    }
    return $spreadsheet;
}
function aldem_freezeColumn(Spreadsheet $spreadsheet, int $columnIndex, int $row)
{
    $spreadsheet->getActiveSheet()->freezePaneByColumnAndRow($columnIndex, $row);
}
function aldem_cCellExcel(Spreadsheet  $spreadsheet, $range, $text, $bold = false, $fontSize = 10, $color = Color::COLOR_WHITE, $bgColor = Color::COLOR_BLACK, $wrapText = true)
{
    $excel = $spreadsheet->getActiveSheet();
    if (strpos($range, ":") !== false) {
        $excel = $excel->mergeCells($range);
    }
    $styleExcel = $excel->setCellValue(aldem_getFirstCoordExcel($range), $text)->getStyle($range);
    $styleExcel->getAlignment()->setHorizontal('center')->setVertical('center')->setWrapText($wrapText);
    $styleExcel->getFont()->setBold($bold)->setSize($fontSize)->getColor()->setARGB(aldem_setColorExcel($color));
    $styleExcel->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(aldem_setColorExcel($bgColor));
    return $spreadsheet;
}
function aldem_getSpreadsheetMarkenReportExport($dataReport, $fechaReporte): Spreadsheet
{

    // querys para las primeras tablas
    $queryA = query_getMarkenExportQueryA();
    $queryB = query_getMarkenExportQueryB();
    $queryC = query_getMarkenExportQueryC();

    $tipoCambio = "4.1";
    $gray = "#ACB9CA";
    $blue = "#333F4F";
    $grayWhite = "##D6DCE4";
    $yellow = "#FFFF00";
    $blueWhite = "#2F75B5";
    $blackWhite = "#808080";

    $spreadsheet = new Spreadsheet();


    // tablas superiores
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B6:D6", "TARIFA DE HIELO SECO POR KILO EN DOLARES", true, 13, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B7:D7", "LIMA", true, 13, Color::COLOR_WHITE, $gray);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "B8", "BOX BIO I", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C8", $queryA[1]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D8", $queryA[1]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "B9", "BOX BIO II", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C9", $queryA[2]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D9", $queryA[2]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "B10", "BOX BIO III", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C10", $queryA[3]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D10", $queryA[3]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);


    $spreadsheet = aldem_cCellExcel($spreadsheet, "B11:D11", "PROVINCIA (SECOND CITY)", true, 13, Color::COLOR_WHITE, $gray);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "B12", "BOX BIO I", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C12", $queryA[5]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D12", $queryA[5]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "B13", "BOX BIO II", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C13", $queryA[6]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D13", $queryA[6]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B14", "IQUITOS - BOX BIO II", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "C14",  $queryA[7]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D14",  $queryA[7]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B15", "BOX BIO III", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "C15",  $queryA[8]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D15", $queryA[8]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "B17:D17", "HANDLING POR GUIA MASTER EN DOLARES", true, 13, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B18:D18", $queryA[11]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B19:D19", "FLETE EN DOLARES (MINIMO)", true, 13, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B20:D20", $queryA[13]->precio1, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);

    // segunda parte del tarifario 
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J6:T6", "TARIFARIO MARKEN EXPORTACIÓN", true, 13, Color::COLOR_WHITE, $gray);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "J7:L7", "TARIFA DE PICK UP POR GUIA EN DOLARES", true, 13, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J8:L8", "LIMA", true, 13, Color::COLOR_WHITE, $blueWhite);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "J9", "AMBIENTE", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K9:L9", $queryB[1]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J10", "FROZEN BIO I/BIO II/ BIO III", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K10:L10", $queryB[2]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J11:L11", "PROVINCIA (SECOND CITY)", true, 13, Color::COLOR_WHITE, $blueWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J12", "AMBIENTE", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K12:L12", $queryB[6]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J13", "FROZEN BIO I", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K13:L13", $queryB[7]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J14", "FROZEN BIO II", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K14:L14", $queryB[8]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J15", "FROZEN BIO II", true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K15:L15", $queryB[9]->tarifa, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "N7:S7", "TARIFA DE GASTOS OPERATIVOS POR GUIA MASTER EN DOLARES", true, 13, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "N8:S8", $queryC[0]->precio, true, 13, Color::COLOR_BLACK, Color::COLOR_WHITE);


    // dejare espacios abajo por si acaso


    // titulos
    // TARIFA PICK UP (RECOJO DE MUESTRAS)
    $rangeRecojoMuestras = "B30:AC30";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeRecojoMuestras, "TARIFA PICK UP (RECOJO DE MUESTRAS)", true, 18, Color::COLOR_WHITE, Color::COLOR_BLACK);

    // // TARIFA HIELO SECO
    // $rangeTarifaHieloSeco = "A30:AR30";
    // $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTarifaHieloSeco, "TARIFA HIELO SECO", true, 18, Color::COLOR_WHITE, Color::COLOR_BLACK);


    // CANTIDAD DE PICK UP (RECOJOS
    $rangeCantidadPickUp = "B31:M31";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeCantidadPickUp, "CANTIDAD DE PICK UP (RECOJOS)", true, 14, Color::COLOR_WHITE, $blue);

    // // FECHA 1
    $rangeFecha1 = "B32:B34";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeFecha1, "FECHA", true, 11, Color::COLOR_BLACK, $blackWhite, false);
    // // JOB
    $rangeFecha1 = "C32:C34";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeFecha1, "JOB", true, 11, Color::COLOR_BLACK, $blackWhite, false);

    // GUIAS DE LIMA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D32:H32", "GUIAS DE LIMA", true, 11, Color::COLOR_BLACK, $gray);

    // // GUIAS DE PROVINCIA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "I32:M32", "GUIAS DE PROVINCIA", true, 11, Color::COLOR_BLACK, $blackWhite);

    // // // FROZEN
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D33:H33", "FROZEN", true, 11, Color::COLOR_BLACK, $gray);

    // // FROZEN2
    $spreadsheet = aldem_cCellExcel($spreadsheet, "I33:M33", "FROZEN", true, 11, Color::COLOR_BLACK, $blackWhite);

    // // AMBIENTE1
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D34", "Ambiente", true, 11, Color::COLOR_BLACK, $gray, false);

    // // BIO I II III
    $spreadsheet = aldem_cCellExcel($spreadsheet, "E34", "BIO I", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F34", "BIO II", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "G34", "BIO III", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "H34", "REFRIGERADO", true, 11, Color::COLOR_BLACK, $gray, false);

    // AMBIENTE2
    $spreadsheet = aldem_cCellExcel($spreadsheet, "I34", "AMBIENTE", true, 11, Color::COLOR_BLACK, $blackWhite, false);

    // BIO I II III
    $spreadsheet = aldem_cCellExcel($spreadsheet, "J34", "BIO I", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K34", "BIO II", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "L34", "BIO III", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "M34", "REFRIGERADO", true, 11, Color::COLOR_BLACK, $blackWhite, false);


    // total guias marken
    $rangeTotalGuiasMarken = "N31:N34";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTotalGuiasMarken, "Estado", true, 9, Color::COLOR_BLACK, $yellow, false);

    // SEGUNDA PARTE COBRO A MARKEN PICK UP DOLARES USD
    // GUIAS DE LIMA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "O32:S32", "GUIAS DE LIMA", true, 11, Color::COLOR_BLACK, $gray);

    // GUIAS DE PROVINCIA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "T32:X32", "GUIAS DE PROVINCIA", true, 11, Color::COLOR_BLACK, $blackWhite);

    // FROZEN
    $spreadsheet = aldem_cCellExcel($spreadsheet, "O33:S33", "FROZEN", true, 11, Color::COLOR_BLACK, $gray);

    // FROZEN2
    $spreadsheet = aldem_cCellExcel($spreadsheet, "T33:X33", "FROZEN", true, 11, Color::COLOR_BLACK, $blackWhite);

    // AMBIENTE1
    $spreadsheet = aldem_cCellExcel($spreadsheet, "O34", "Ambiente", true, 11, Color::COLOR_BLACK, $gray, false);

    // BIO I II III
    $spreadsheet = aldem_cCellExcel($spreadsheet, "P34", "BIO I", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "Q34", "BIO II", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "R34", "BIO III", true, 11, Color::COLOR_BLACK, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "S34", "REFRIGERADO", true, 11, Color::COLOR_BLACK, $gray, false);

    // AMBIENTE2
    $spreadsheet = aldem_cCellExcel($spreadsheet, "T34", "AMBIENTE", true, 11, Color::COLOR_BLACK, $blackWhite, false);

    // BIO I II III
    $spreadsheet = aldem_cCellExcel($spreadsheet, "U34", "BIO I", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "V34", "BIO II", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "W34", "BIO III", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "X34", "REFRIGERADO", true, 11, Color::COLOR_BLACK, $blackWhite, false);


    // // COBRO A MARKEN PICK UP  DOLARES USD
    $rangeCobroMarkenPickUP = "O31:X31";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeCobroMarkenPickUP, "COBRO A MARKEN PICK UP  DOLARES USD", true, 14, Color::COLOR_WHITE, $blue);

    // // TOTAL PICK UP DOLARES USD
    $rangeTotalPickUp = "Y31:Y34";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTotalPickUp, "TOTAL PICK UP DOLARES USD", true, 9, Color::COLOR_BLACK, $yellow);

    // TOTAL CANTIDAD DE HIELO SECO KG
    $rangeTotalHieloSeco = "Z31:Z34";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTotalHieloSeco, "TOTAL CANTIDAD DE HIELO SECO KG PARA MUESTRAS EXPORTACIÓN x MAWB", true, 9, Color::COLOR_WHITE, $blackWhite);

   

    // OBSERVACIONES
    $rangeObservaciones = "AA31:AA34";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeObservaciones, "OBSERVACIONES", true, 12, Color::COLOR_WHITE, $blackWhite);

    // COSTO (INVERSIÓN) DE HIELO SECO SECO EN LIMA SOLES (SIN IGV)
    $rangeCostoHieloSeco = "AB31:AB34";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeCostoHieloSeco, "COSTO (INVERSIÓN) DE HIELO SECO SECO EN LIMA SOLES (SIN IGV)", true, 9, Color::COLOR_WHITE, $blackWhite);

    // COSTO (INVERSIÓN) DE HIELO SECO SECO EN LIMA DOLARES
    $rangeCostoHieloSecoDolares = "AC31:AC34";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeCostoHieloSecoDolares, "COSTO (INVERSIÓN) DE HIELO SECO SECO EN LIMA DOLARES Tipo de CAMBIO: $tipoCambio", true, 9, Color::COLOR_WHITE, $blackWhite);


    // // TARIFA DE HIELO SECO
    $rangeTarifaHieloSeco = "AD31:AK31";
    $spreadsheet = aldem_cCellExcel($spreadsheet, $rangeTarifaHieloSeco, "TARIFA DE HIELO SECO DOLARES", true, 11, Color::COLOR_WHITE, $blackWhite);


    // LIMA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AD32:AG33", "LIMA", true, 11, Color::COLOR_BLACK, $grayWhite);
    // PROVINCIA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AH32:AK33", "PROVINCIA", true, 11, Color::COLOR_BLACK, $blackWhite);

    // LIMA BIO
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AD34", "BIO I", true, 11, Color::COLOR_BLACK, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AE34", "BIO II", true, 11, Color::COLOR_BLACK, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AF34", "BIO III", true, 11, Color::COLOR_BLACK, $grayWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AG34", "REFRIGERADO", true, 11, Color::COLOR_BLACK, $grayWhite);

    // PROVINCIA BIO
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AH34", "BIO I", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AI34", "BIO II", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AJ34", "BIO III", true, 11, Color::COLOR_BLACK, $blackWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AK34", "REFRIGERADO", true, 11, Color::COLOR_BLACK, $blackWhite, false);

    // // // UTILIDAD HIELO SECO

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AL31:AL33", "UTILIDAD HIELO SECO DOLARES", true, 11, Color::COLOR_BLACK, $grayWhite);


    $spreadsheet = aldem_cCellExcel($spreadsheet, "AL34", "LIMA", true, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

    // HANDLING
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AM31:AN33", "HANDLING", true, 11, Color::COLOR_WHITE, $blackWhite);

    // TARIFA
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AM34", "TARIFA DOLARES", true, 11, Color::COLOR_BLACK, $yellow);

    // GASTO
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AN34", "GASTO DOLARES", true, 11, Color::COLOR_BLACK, $grayWhite);


    // UTILIDAD
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AO31:AO34", "UTILIDAD HANDLING DOLARES", true, 11, $blue, $grayWhite);

    // // // TARIFA DE TRAMITE OPERATIVO

    $spreadsheet = aldem_cCellExcel($spreadsheet, "AP31:AP34", "TARIFA DE TRAMITE OPERATIVO DOLARES", true, 11, Color::COLOR_WHITE, $blue);

    // TARIFA DE CAJA DE EMBALAJE MUESTRAS DE AMBIENTE
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AQ31:AQ34", "TARIFA DE CAJA DE EMBALAJE MUESTRAS DE AMBIENTE DOLARES", true, 11, Color::COLOR_WHITE, $blue);

    // COSTO DE ALDEM POR EMBALAR (Costo del tiempo que se usa para embalar las cajas)
    $spreadsheet = aldem_cCellExcel($spreadsheet, "AR31:AR34", "COSTO DE ALDEM POR EMBALAR (Costo del tiempo que se usa para embalar las cajas) DOLARES", true, 11, $blue, $grayWhite);


    // aldem_freezeColumn($spreadsheet, 1, 13);
    $count = 35;
    foreach ($dataReport as $dr) {
        $spreadsheet = aldem_cCellExcel($spreadsheet, "B$count", $dr->Fecha, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "C$count", $dr->Job, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "D$count", $dr->Lima_Amb, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "E$count", $dr->Lima_Bio1, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "F$count", $dr->Lima_Bio2, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "G$count", $dr->Lima_Bio3, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "H$count", $dr->Lima_refrigerado, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "I$count", $dr->Prov_Amb, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "J$count", $dr->Prov_Bio1, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "K$count", $dr->Prov_Bio2, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "L$count", $dr->Prov_Bio3, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "M$count", $dr->Prov_Refrigerado, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "N$count", $dr->estado, false, 11, Color::COLOR_WHITE, $blueWhite);

        $spreadsheet = aldem_cCellExcel($spreadsheet, "O$count", $dr->Lima_Amb_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "P$count", $dr->Lima_Bio1_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "Q$count", $dr->Lima_Bio2_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "R$count", $dr->Lima_Bio3_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "S$count", $dr->Lima_refrigerado_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "T$count", $dr->Prov_Amb_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "U$count", $dr->Prov_Bio1_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "V$count", $dr->Prov_Bio2_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "W$count", $dr->Prov_Bio3_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "X$count", $dr->Prov_Refrigerado_precio, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

        $spreadsheet = aldem_cCellExcel($spreadsheet, "Y$count", $dr->Total, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "Z$count", $dr->cantidad, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AA$count", $dr->observaciones, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AB$count", $dr->costo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AC$count", $dr->costo_dolares, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

        $spreadsheet = aldem_cCellExcel($spreadsheet, "AD$count", $dr->Lima_bio1hielo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AE$count", $dr->Lima_bio2hielo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AF$count", $dr->Lima_bio3hielo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AG$count", $dr->Lima_refrihielo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AH$count", $dr->prov_bio1hielo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AI$count", $dr->prov_bio2hielo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AJ$count", $dr->prov_bio3hielo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);

        $spreadsheet = aldem_cCellExcel($spreadsheet, "AK$count", $dr->prov_refrihielo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);



        $spreadsheet = aldem_cCellExcel($spreadsheet, "AL$count", $dr->costovariable, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AM$count", $dr->costohandling, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AN$count", $dr->gastohandling, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AO$count", $dr->utilidadhandling, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AP$count", $dr->tramiteoperativo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AQ$count", $dr->tarifacaja, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "AR$count", $dr->costoembalar, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);


        $count++;
    }
    $spreadsheet = aldem_format_excelByAZ($spreadsheet, "BZ");
    return $spreadsheet;
}


function aldem_generateExcelReportCourier($dataReport, $fechaReporte): Spreadsheet
{

    $gray = "#ACB9CA";
    $blue = "#002060";
    $grayWhite = "##D6DCE4";
    $yellow = "#FFFF00";
    $blueWhite = "#2F75B5";
    $blackWhite = "#808080";
    $skyblue = "#00B0F0";
    $green = "#76923C";
    $spreadsheet = new Spreadsheet();

    $totalCostoMarken = query_getMarkenCourierMarkenCostos($fechaReporte);
    $totalGuias = query_getMarkenCourierMarkenGuias($fechaReporte);
    $totalGuiasCourier = query_getMarkenCourierMarkenGuiasTipo($fechaReporte);

    // validaciones de campos
    $totalCostoMarken = aldem_isValidNumber($totalCostoMarken);
    $totalGuias = aldem_isValidNumber($totalGuias);
    $totalGuiasCourier = aldem_isValidNumber($totalGuiasCourier);



    $transporteGuiaHija = query_servicioTransportePorGuiaHija();
    $courierReportC = query_courierReportQueryC();
    $costoHandlingMaster = query_getCostoHandlingPorMaster();

    // titulos
    //TARIFA SERVICIO IMPORTACIONES COURIER				
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F3:J3", "TARIFA SERVICIO IMPORTACIONES COURIER", true, 18, Color::COLOR_WHITE, $skyblue);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "H4:I4", $fechaReporte, true, 18, Color::COLOR_WHITE, $skyblue);


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
    $spreadsheet = aldem_cCellExcel($spreadsheet, "A16", "MANIFIESTO EER", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "B16", "DUA", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "C16", "GUIA HIJA", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "D16", "FECHA DE ENTREGA", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "E16", "GUIA MASTER", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "F16", "PROTOCOLO", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "G16", "EXPORTADOR (MIAMI)", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "H16", "EXPORTADOR (LIMA)", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "I16", "EXPORTADOR (CANTIDAD DE BULTOS)", true, 9, Color::COLOR_WHITE, $blue);

    $spreadsheet = aldem_cCellExcel($spreadsheet, "J16", "PESO", true, 9, Color::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "K16", "DELIVERY (ENTREGA LOCAL) X GUIA HIJA -  DOLARES", true, 9, Color::COLOR_WHITE, $blueWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "L16", "COSTO SERVICIO TRANSPORTE POR GUIA HIJA - DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "M16", "TARIFA SERVICIO
    ADUANA POR GUIA HIJA - DOLARES", true, 9, Color::COLOR_WHITE, $blueWhite);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "N16", "COSTO SERV. ADUANA
    POR GUIA HIJA - DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "O16", "TARIFA HANDLING POR GUIA HIJA -  USD", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "P16", "COSTO HANDLING POR GUIA HIJA - DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "Q16", "TARIFA ALMACENAJE POR GUIA HIJA -  USD", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "R16", "COSTO ALMACENAJE POR GUIA HIJA - DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "S16", "IMPUESTOS POR GUIA HIJA - USD", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "T16", "TARIFA TRAMITE OPEATIVO / GUIA HIJA - USD", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "U16", "COSTO VARIABLE", true, 9, COLOR::COLOR_WHITE, $blue);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "V16", "TOTAL INGRESOS DOLARES", true, 9, COLOR::COLOR_WHITE, COLOR::COLOR_BLACK);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "W16", "TOTAL GASTOS DOLARES", true, 9, $blue, $gray);
    $spreadsheet = aldem_cCellExcel($spreadsheet, "X16", "TOTAL UTILIDAD DOLARES", true, 9, COLOR::COLOR_BLACK, $yellow);

    // inmovilizacion de columns
    aldem_freezeColumn($spreadsheet, 0, 17);

    // falta rellenar con data
    $count = 17;
    foreach ($dataReport as $dr) {
        // validaciones 
        $dr->Ingresos = aldem_isValidNumber($dr->Ingresos);
        $dr->Egresos = aldem_isValidNumber($dr->Egresos);

        // operaciones
        $costoVariable = $totalCostoMarken / $totalGuiasCourier;
        $costoVariable = aldem_isValidNumber($costoVariable);

        $gastos = $dr->Egresos + $totalCostoMarken / $totalGuiasCourier;
        $gastos = $gastos = aldem_isValidNumber($gastos);

        $totalUtilidad = $dr->Ingresos - $dr->Egresos - $totalCostoMarken / $totalGuiasCourier;

        $totalUtilidad  = aldem_isValidNumber($totalUtilidad);


        $spreadsheet = aldem_cCellExcel($spreadsheet, "A$count", $dr->Manifiesto, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "B$count", $dr->DUA, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "C$count", $dr->Fecha, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "D$count", $dr->Fecha_Levante, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "E$count", $dr->guia_master, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "F$count", $dr->Protocolo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "G$count", $dr->Exportador, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "H$count", $dr->Importador, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "I$count", $dr->Cantidad, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "J$count", $dr->Peso, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "K$count", $dr->delivery, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "L$count", $dr->Serv_Transporte, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "M$count", $dr->Serv_Aduana, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "N$count", $dr->Costo_Aduana, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "O$count", $dr->Tarifa_handling, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "P$count", $dr->Costo_handling, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "Q$count", $dr->tarifa_almacenaje, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "R$count", $dr->tarifa_costo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "S$count", $dr->tarifa_impuestos, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "T$count", $dr->Tramite_Operativo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "U$count", $dr->Tramite_Operativo, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "V$count", $dr->Ingresos, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "W$count", $dr->Egresos, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);
        $spreadsheet = aldem_cCellExcel($spreadsheet, "X$count", 0, false, 11, Color::COLOR_BLACK, Color::COLOR_WHITE);


        $count++;
    }


    $spreadsheet = aldem_format_excelByAZ($spreadsheet, "AZ");

    return $spreadsheet;
}

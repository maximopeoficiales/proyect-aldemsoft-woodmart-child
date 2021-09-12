<?php
// como cumple con el formato standar puedo usar esta tecnica de exportacion a excel
// wp_enqueue_script("DT_BUTTONS", "https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js", '', '1.0.0');
// wp_enqueue_script("DT_JSZIP", "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js", '', '1.0.0');
// wp_enqueue_script("DT_PDFMAKE", "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js", '', '1.0.0');
// wp_enqueue_script("DT_VFSFONTS", "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js", '', '1.0.0');
// wp_enqueue_script("DT_BUTTON_HTML5", "https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js", '', '1.0.0');
// wp_enqueue_script("DT_PRINT", "https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js", '', '1.0.0');


$fecha = $_GET["fecha"];
if (empty($fecha)) return;
try {

    $totalCostoMarken = query_getCostoMarken($fecha)->total_costo_marken;
    $totalGuias = query_getTotalGuias($fecha)->total_guias;
    $totalGuiasCourier = query_getTotalGuiasExport($fecha)->total_guias_courier;

    $transporteGuiaHija = query_servicioTransportePorGuiaHija();
    $courierReportC = query_courierReportQueryC();
    $costoHandlingMaster = query_getCostoHandlingPorMaster();
    $markenCourierReporteGeneral = query_getMarkenCourierReporteGeneral1($fecha);
} catch (\Throwable $th) {
    echo $th;
}
aldem_cargarStyles();
?>

<div class="row my-2 align-items-center">
    <div class="col-md-6 my-2">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td class="aldem-bg-blue font-weight-bold aldem-border-black">Total costo marken</td>
                    <td class=" text-center"><?= $totalCostoMarken ?></td>
                </tr>
                <tr>
                    <td class="aldem-bg-blue font-weight-bold aldem-border-black">Total guias marken</td>
                    <td class="text-center"><?= $totalGuias ?></td>
                </tr>
                <tr>
                    <td class="aldem-bg-blue font-weight-bold aldem-border-black">Total Guías Marken Courier</td>
                    <td class=" text-center"><?= $totalGuiasCourier ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6 my-2">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="1" class="border-0 aldem-border-none"></th>
                    <th colspan="2" class=" aldem-bg-green text-center aldem-border-black">SERV. TRANSPORTE POR GUIA HIJA</th>
                </tr>
                <tr>
                    <th colspan="1" class="border-0"></th>
                    <th colspan="1" class="aldem-bg-green aldem-border-black text-center">PESO</th>
                    <th colspan="1" class="aldem-bg-green aldem-border-black text-center">TARIFA (USD)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transporteGuiaHija as $guia) {
                ?>
                    <tr>
                        <td class="aldem-bg-blue aldem-border-black text-uppercase"><?= $guia->site  ?></td>
                        <td class="text-center"><?= $guia->peso ?></td>
                        <td class="text-center"><?= $guia->tarifa ?></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<div class="row my-2 align-items-center">
    <div class="col-md-6 my-2">
        <table class="table table-bordered">
            <thead class="">
                <tr>
                    <th class="aldem-bg-green aldem-border-black text-center">TARIFA ALMACENAJE PORGUIA HIJA (USD)</th>
                    <th class="aldem-bg-green aldem-border-black text-center">TARIFA SERV. ADUANA POR GUIA HIJA (USD)</th>
                    <th class="aldem-bg-green aldem-border-black text-center" style="font-size: .7rem;">COSTO OPERATIVO ES FACTURADO CUANDO NO REALIZAMOS NINGUN SERVICIO / POR GUIA HIJA (USD)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($courierReportC as $tarifa) {
                    ?>
                        <td class="text-center"><?= $tarifa->tarifa ?></td>
                    <?php } ?>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6 my-2">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="aldem-bg-green aldem-border-black text-center">COSTO HANDLING POR MASTER (USD)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($costoHandlingMaster as $tarifa) { ?>
                    <tr>
                        <td class="text-center"><?= $tarifa->tarifa ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<div class="row my-4" style="overflow-x: scroll;">
    <!-- 36 -->
    <div class="col-12">

        <table class="table aldem-table" id="table_courier_reporte_general">
            <thead>
                <tr>
                    <th class="aldem-bg-blue">MANIFIESTO EER</th>
                    <th class="aldem-bg-blue">DUA</th>
                    <th class="aldem-bg-blue">GUIA HIJA</th>
                    <th class="aldem-bg-blue">FECHA DE ENTREGA</th>
                    <th class="aldem-bg-blue">GUIA MASTER</th>
                    <th class="aldem-bg-blue">PROTOCOLO</th>
                    <th class="aldem-bg-blue">EXPORTADOR (MIAMI)</th>
                    <th class="aldem-bg-blue">IMPORTADOR (LIMA)</th>
                    <th class="aldem-bg-blue">CANTIDAD DE BULTOS</th>
                    <th class="aldem-bg-blue">PESO</th>
                    <th class="aldem-bg-skyblue">DELIVERY (ENTREGA LOCAL) X GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-gray">COSTO SERVICIO TRANSPORTE POR GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-skyblue">TARIFA SERVICIO ADUANA POR GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-gray">COSTO SERV. ADUANA POR GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-blue">TARIFA HANDLING POR GUIA HIJA - USD</th>
                    <th class="aldem-bg-gray">COSTO HANDLING POR GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-blue">TARIFA ALMACENAJE POR GUIA HIJA - USD</th>
                    <th class="aldem-bg-gray">COSTO ALMACENAJE POR GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-blue">IMPUESTOS POR GUIA HIJA - USD</th>
                    <th class="aldem-bg-blue">TARIFA TRAMITE OPEATIVO / GUIA HIJA - USD</th>
                    <th class="aldem-bg-blue">COSTO VARIABLE</th>
                    <th class="aldem-bg-black">TOTAL INGRESOS DOLARES</th>
                    <th class="aldem-bg-gray">TOTAL GASTOS DOLARES</th>
                    <th class="aldem-bg-yellow">TOTAL UTILIDAD DOLARES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($markenCourierReporteGeneral as $marken) { ?>
                    <tr>
                        <td><?= $marken->Manifiesto ?></td>
                        <td><?= $marken->DUA ?></td>
                        <td><?= $marken->Fecha ?></td>
                        <td><?= $marken->guia_master ?></td>
                        <td><?= $marken->guia_master ?></td>
                        <td><?= $marken->Protocolo ?></td>
                        <td><?= $marken->Exportador ?></td>
                        <td><?= $marken->Importador ?></td>
                        <td><?= $marken->Cantidad ?></td>
                        <td><?= $marken->Peso ?></td>
                        <td><?= $marken->delivery ?></td>
                        <td><?= $marken->Serv_Transporte ?></td>
                        <td><?= $marken->Serv_Aduana ?></td>
                        <td><?= $marken->Costo_Aduana ?></td>
                        <td><?= $marken->Tarifa_handling ?></td>
                        <td><?= $marken->Costo_handling ?></td>
                        <td><?= $marken->tarifa_almacenaje ?></td>
                        <td><?= $marken->tarifa_costo ?></td>
                        <td><?= $marken->tarifa_impuestos ?></td>
                        <td><?= $marken->Tramite_Operativo ?></td>
                        <td>0</td>
                        <td><?= $marken->Ingresos ?></td>
                        <td><?= $marken->Egresos ?></td>
                        <td>0</td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function() {
        <?php aldem_datatables_in_spanish(); ?>
        $('#table_courier_reporte_general').DataTable();

    });
</script>
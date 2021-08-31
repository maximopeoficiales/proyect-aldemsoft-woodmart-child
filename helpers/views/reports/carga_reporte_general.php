<?php
$totalCostoMarken = query_getCostoMarken($fecha)->total_costo_marken;
$totalGuias = query_getTotalGuias($fecha)->total_guias;
$totalGuiasCourier = query_getTotalGuiasExport($fecha)->total_guias_courier;

$transporteGuiaHija = query_servicioTransportePorGuiaHija();

$markenCourierReporteGeneral = query_getMarkenCourierReporteGeneral($fecha);
aldem_cargarStyles();
?>

<div class="row justify-content-center">
    <div class="col-md-6 my-4">
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
    <div class="col-md-12 my-2">
        <div class="aldem-selectionable-datatable">
            Ocultar Columna: <a class="toggle-vis" data-column="0">Nombre</a> - <a class="toggle-vis" data-column="1">SERV. TRANSPORTE POR GUIA HIJA</a> - <a class="toggle-vis" data-column="2">TARIFA ALMACENAJE POR GUIA HIJA (USD)</a> - <a class="toggle-vis" data-column="3">COSTO HANDLING POR MASTER (USD)</a> - <a class="toggle-vis" data-column="4">TRAMITE OPERATIVO...</a> - <a class="toggle-vis" data-column="5">TARIFA SERVICIO ADUANA POR GUIA HIJA (USD)</a> - <a class="toggle-vis" data-column="6">HIELO SECO POR GUIA HIJA/KG</a>
        </div>
        <table class="table table-bordered aldem-table table-responsive" id="table1TICarga">
            <thead>
                <tr>
                    <th colspan="1" class="border-0 aldem-border-none"></th>
                    <th colspan="2" class=" aldem-bg-green text-center aldem-border-black">SERV. TRANSPORTE POR GUIA HIJA</th>
                    <th rowspan="2" class=" aldem-bg-green text-center aldem-border-black">TARIFA ALMACENAJE PORGUIA HIJA (USD)</th>
                    <th rowspan="2" class=" aldem-bg-green text-center aldem-border-black">COSTO HANDLING POR MASTER (USD)</th>
                    <th colspan="3" rowspan="2" class=" aldem-bg-green text-center aldem-border-black">TRAMITE OPERATIVO ES FACTURADO CUANDO NO REALIZAMOS NINGUN SERVICIO / GUIA HIJA (USD)</th>
                    <th rowspan="2" class=" aldem-bg-green text-center aldem-border-black">TARIFA SERVICIO ADUANA POR GUIA HIJA (USD)</th>
                    <th rowspan="2" class=" aldem-bg-green text-center aldem-border-black">HIELO SECO POR GUIA HIJA/KG</th>
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
                        <td class="text-center"><?= $guia->tarifa ?></td>
                        <td class="text-center"><?= $guia->tarifa ?></td>
                        <td class="text-center" colspan="3">asdf</td>
                        <td class="text-center"><?= $guia->tarifa ?></td>
                        <td class="text-center"><?= $guia->tarifa ?></td>
                        <!--  -->
                        <td class="text-center d-none"></td>
                        <td class="text-center d-none"></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<div class="row my-4" style="overflow-x: scroll;">
    <!-- 36 -->
    <div class="col-12">

        <table class="table   aldem-table " id="table_courier_reporte_general">
            <thead>
                <tr>
                    <th class="aldem-bg-blue">DUA</th>
                    <th class="aldem-bg-blue">GUIA</th>
                    <th class="aldem-bg-blue">FECHA</th>
                    <th class="aldem-bg-blue">GUIA MASTER</th>
                    <th class="aldem-bg-blue">REFERENCIA</th>
                    <th class="aldem-bg-blue">EXPORTADOR (MIAMI)</th>
                    <th class="aldem-bg-blue">IMPORTADOR (LIMA)</th>
                    <th class="aldem-bg-blue">CANTIDAD</th>
                    <th class="aldem-bg-blue">PESO</th>
                    <th class="aldem-bg-skyblue">OBSERVACION</th>
                    <th class="aldem-bg-blue">TARIFA DELIVERY (ENTREGA AL IMPORTADOR) POR GUIA HIJA DOLARES</th>
                    <th class="aldem-bg-gray">COSTO DELIVERY (ENTREGA AL IMPORTADOR) POR GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-blue">TARIFA SERVICIO ADUANA POR GUIA HIJA DOLARES</th>
                    <th class="aldem-bg-gray">COSTO SERV. ADUANA DOLARES</th>
                    <th class="aldem-bg-blue">HANDLING POR GUIA HIJA DOLARES</th>
                    <th class="aldem-bg-gray">COSTO ALMACENAJE POR GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-blue">TARIFA ALMACENAJE POR GUIA HIJA DOLARES</th>
                    <th class="aldem-bg-gray">COSTO ALMACENAJE POR GUIA HIJA - DOLARES</th>
                    <th class="aldem-bg-blue">IMPUESTOS POR GUIA HIJA DOLARES</th>
                    <th class="aldem-bg-blue">TARIFA TRAMITE OPERATIVO POR GUIA HIJA DOLARES</th>
                    <th class="aldem-bg-yellow">COBRO MARKEN DOLARES</th>
                    <th class="aldem-bg-blue">COSTO VARIABLE</th>
                    <th class="aldem-bg-black">TOTAL INGRESOS DOLARES</th>
                    <th class="aldem-bg-gray">TOTAL GASTOS DOLARES</th>
                    <th class="aldem-bg-yellow">TOTAL UTILIDAD DOLARES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($markenCourierReporteGeneral as $marken) { ?>
                    <tr>
                        <td><?= $marken->manifiesto ?></td>
                        <td><?= $marken->dua ?></td>
                        <td><?= $marken->guia ?></td>
                        <td><?= $marken->fecha_entrega ?></td>
                        <td><?= $marken->guia_master ?></td>
                        <td><?= $marken->protocolo ?></td>
                        <td><?= $marken->exportador ?></td>
                        <td><?= $marken->importador ?></td>
                        <td><?= $marken->cantidad ?></td>
                        <td><?= $marken->peso ?></td>
                        <td><?= $marken->periodo ?></td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                        <td>data</td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        <?php aldem_datatables_in_spanish(); ?>
        let table = $('#table1TICarga').DataTable({});
        $('a.toggle-vis').on('click', function(e) {
            e.preventDefault();

            // Get the column API object
            var column = table.column($(this).attr('data-column'));

            // Toggle the visibility
            column.visible(!column.visible());
        });
    });
</script>
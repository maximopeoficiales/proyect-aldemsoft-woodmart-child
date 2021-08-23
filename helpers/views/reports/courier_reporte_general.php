<?php
$fecha = $_GET["fecha"];
if (empty($fecha)) return;
try {

    $totalCostoMarken = query_getCostoMarken($fecha)->total_costo_marken;
    $totalGuias = query_getTotalGuias($fecha)->total_guias;
    $totalGuiasCourier = query_getTotalGuiasExport($fecha)->total_guias_courier;

    $transporteGuiaHija = query_servicioTransportePorGuiaHija();
    $courierReportC = query_courierReportQueryC();
    $costoHandlingMaster = query_getCostoHandlingPorMaster();

} catch (\Throwable $th) {
    echo $th;
}
?>
<style>
    .aldem-border-black {
        border-color: black !important;
    }

    .aldem-border-none {
        border: none !important;
    }

    .aldem-bg-blue {
        background-color: #002060;
        color: #fff;
    }

    .aldem-bg-green {
        background-color: #76923C;
        color: #fff;
    }
</style>
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




<div class="row" style="overflow-x: scroll;">
    <!-- 36 -->
    <div class="col-12">
        <table class="table text-center table-report-aldem">
            <thead>
                <tr>
                    <th colspan="36" class="aldem-bg-gray aldem-text-white">TARIFA PICK UP (RECOJO DE MUESTRAS)</th>
                    <th colspan="39" class="aldem-bg-gray aldem-text-white">TARIFA HIELO SECO</th>
                </tr>
                <tr>
                    <th colspan="17" class="aldem-bg-blue">CANTIDAD DE PICK UP (RECOJOS)
                    </th>
                    <th rowspan="4" colspan="1" class="aldem-bg-yellow">TOTAL GUIAS MARKEN</th>
                    <th colspan="17" class="aldem-bg-blue">COBRO A MARKEN PICK UP DOLARES USD</th>
                    <th rowspan="4" colspan="1" class="aldem-bg-yellow">TOTAL PICK UP DOLARES USD</th>

                    <!-- 36 -->
                    <th rowspan="4" colspan="3" class="aldem-bg-black-white">CANTIDAD DE HIELO SECO KG PARA MUESTRAS EXPORTACION X MAWB</th>
                    <!-- 33 -->
                    <th rowspan="4" colspan="10" class="aldem-bg-black-white">Observaciones</th>
                    <!-- 23 -->
                    <th rowspan="4" colspan="3" class="aldem-bg-black-white">COSTO (INVERSIÓN)DE HIELO SECO SECO EN LIMA SOLES(SIN IGV)</th>
                    <!-- 20 -->
                    <th rowspan="4" colspan="3" class="aldem-bg-black-white">COSTO (INVERSIÓN)DE HIELO SECO EN LIMA DOLARES TIPO DE CAMBIO: @TC</th>
                    <!-- 17 -->
                    <th rowspan="2" colspan="6" class="aldem-bg-gray">TARIFA DE HIELO SECO DOLARES</th>
                    <!-- 11 -->
                    <th rowspan="2" colspan="2" class="aldem-bg-gray-white">UTILIDAD HIELO SECO DOLARES</th>
                    <!-- 9 -->
                    <th rowspan="2" colspan="2" class="aldem-bg-gray-white">HANDLING</th>
                    <!-- 7 -->
                    <th rowspan="4" colspan="1" class="aldem-bg-gray-white">UTILIDAD HANDLING DOLARES</th>
                    <!-- 6 -->
                    <th rowspan="4" colspan="2" class="aldem-bg-blue">TARIFA DE TRAMITE OPERATIVO DOLARES</th>
                    <th rowspan="4" colspan="2" class="aldem-bg-blue">TARIFA DE CAJA DE EMBALAJE MUESTRAS DE AMBIENTE DOLARES</th>
                    <th rowspan="4" colspan="5" class="aldem-bg-gray-white">COSTO DE ALDEM POR EMBALAR (Costo del tiempo que se usa para embalar las cajas) DOLARES</th>
                </tr>

                <tr>
                    <th colspan="1" rowspan="3" class="aldem-bg-gray">Fecha</th>
                    <th colspan="8" class="aldem-bg-gray">GUIAS DE LIMA</th>
                    <th colspan="8" class="aldem-bg-black-white">GUIAS DE PROVINCIA</th>
                    <th colspan="8" class="aldem-bg-gray">GUIAS DE LIMA</th>
                    <th colspan="9" class="aldem-bg-black-white">GUIAS DE PROVINCIA</th>
                </tr>
                <tr>
                    <th colspan="8" class="aldem-bg-gray">FROZEN</th>
                    <th colspan="8" class="aldem-bg-black-white">FROZEN</th>
                    <th colspan="8" class="aldem-bg-gray">FROZEN</th>
                    <th colspan="9" class="aldem-bg-black-white">FROZEN</th>
                    <th colspan="3" class="aldem-bg-gray-white">LIMA</th>
                    <th colspan="3" class="aldem-bg-black-white">Provincia</th>
                    <th colspan="2" class="aldem-text-black" rowspan="2">LIMA</th>
                    <th rowspan="2" class="aldem-bg-yellow">TARIFA DOLARES</th>
                    <th rowspan="2" class="aldem-bg-black-white">GASTO DOLARES</th>

                </tr>
                <tr>
                    <th colspan="2" class="aldem-bg-gray">Ambiente</th>
                    <th colspan="2" class="aldem-bg-gray">BIO I</th>
                    <th colspan="2" class="aldem-bg-gray">BIO II</th>
                    <th colspan="2" class="aldem-bg-gray">BIO II</th>

                    <th colspan="2" class="aldem-bg-black-white">Ambiente</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO I</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO II</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO II</th>

                    <th colspan="2" class="aldem-bg-gray">Ambiente</th>
                    <th colspan="2" class="aldem-bg-gray">BIO I</th>
                    <th colspan="2" class="aldem-bg-gray">BIO II</th>
                    <th colspan="2" class="aldem-bg-gray">BIO II</th>

                    <th colspan="2" class="aldem-bg-black-white">Ambiente</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO I</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO II</th>
                    <th colspan="3" class="aldem-bg-black-white">BIO II</th>

                    <!-- 6 espacios para LIMA - PROVINCIA -->
                    <th class="aldem-bg-gray">BIO I</th>
                    <th class="aldem-bg-gray">BIO II</th>
                    <th class="aldem-bg-gray">BIO II</th>
                    <th class="aldem-bg-black-white">BIO I</th>
                    <th class="aldem-bg-black-white">BIO II</th>
                    <th class="aldem-bg-black-white">BIO II</th>
                </tr>
            </thead>
            <tbody style="color:black; text-align: center;">
                <tr>
                    <td>dato 1</td>
                    <td colspan="2">dato 2</td>
                    <td colspan="2">dato 4</td>
                    <td colspan="2">dato 6</td>
                    <td colspan="2">dato 8</td>
                    <td colspan="2">dato 10</td>
                    <td colspan="2">dato 12</td>
                    <td colspan="2">dato 15</td>
                    <td colspan="2">dato 16</td>
                    <td class="aldem-bg-blue-white">dato 17</td>
                    <td colspan="2">dato 18</td>
                    <td colspan="2">dato 20</td>
                    <td colspan="2">dato 22</td>
                    <td colspan="2">dato 24</td>
                    <td colspan="2">dato 26</td>
                    <td colspan="2">dato 28</td>
                    <td colspan="2">dato 30</td>
                    <td colspan="3">dato 31</td>
                    <td>dato 34</td>
                    <td colspan="3">dato 35</td>
                    <td colspan="10">dato 38</td>
                    <td colspan="3">dato 48</td>
                    <td colspan="3">dato 51</td>
                    <td>dato 54</td>
                    <td>dato 55</td>
                    <td>dato 56</td>
                    <td>dato 57</td>
                    <td>dato 58</td>
                    <td>dato 59</td>
                    <td colspan="2">dato 60</td>
                    <td>dato 62</td>
                    <td>dato 63</td>
                    <td>dato 64</td>
                    <td colspan="2">dato 65</td>
                    <td colspan="2">dato 67</td>
                    <td colspan="5">dato 69jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
<?php
$fechaReporte = $_GET["fechareporte"];
if (empty($fechaReporte)) return;
$currencyWoo = aldem_get_currency("PEN");
$moneda = null;
if (empty($_GET["tcCustom"])) {
    $moneda = $currencyWoo;
}
if (doubleval($_GET["tcCustom"]) > 0) {
    $moneda =  $_GET["tcCustom"];
}

$dataReport = query_getMarkenExportReporteGeneral1(intval($fechaReporte));

aldem_cargarStyles();
?>

<style>
    .table-report-aldem {
        color: #fff;
        font-weight: bold;
        text-align: center;
    }

    .aldem-bg-black-white {
        background-color: #808080;
        color: #fff;

    }

    .aldem-bg-gray {
        background-color: #ACB9CA;
        color: #121212;

    }

    .aldem-bg-gray-white {
        background-color: #D6DCE4;
        color: #344176;

    }

    .aldem-bg-blue {
        background-color: #344176;
        color: #fff;
    }

    .aldem-bg-yellow {
        background-color: #FFFF00;
        color: #121212;

    }

    .aldem-bg-blue-white {
        background-color: #2F75B5;
        color: #fff;
    }

    .aldem-text-white {
        color: #fff;
    }

    .aldem-text-black {
        color: #121212;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="form-group">
            <label for="tipoCambio">Tipo de Cambio:</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="" disabled value="<?= number_format($moneda, 2) ?>">
                <div class="input-group-append">
                    <button class="btn btn-aldem-danger" type="button" id="btnResetCurrency">Reset</button>
                </div>
            </div>
        </div>


        <form action="" method="get">
            <input type="hidden" name="fechareporte" value="<?= $fechaReporte ?>">
            <div class="form-group">
                <label for="tcCustom">Ingresar Tipo de Cambio personalizado:</label>
                <input type="number" name="tcCustom" id="tcCustom" class="form-control" step="0.01" min="0.01">
            </div>
            <button type="submit" class="btn btn-success w-100 my-2 btn-aldem-verde"><i class="fas fa-sync-alt mx-1 fa-spin"></i>Actualizar el reporte</button>
        </form>
    </div>
</div>


<div class="row justify-content-end my-2 ">
    <form action="" method="get" class="p-2">
        <input type="hidden" name="">
        <input type="hidden" name="">
        <button type="submit" class="btn btn-success btn-aldem-verde"><i class="fas fa-file-excel mx-1"></i> Exportar a excel</button>
    </form>
</div>
<div class="row" style="overflow-x: scroll; height: 500px;">
    <!-- 36 -->
    <div class="col-12">
        <table class="table text-center aldem-table " id="table_export_reporte_general">
            <thead>
                <tr>
                    <th colspan="39" class="aldem-bg-gray aldem-text-white">TARIFA PICK UP (RECOJO DE MUESTRAS)</th>
                    <th colspan="43" class="aldem-bg-gray aldem-text-white">TARIFA HIELO SECO</th>
                </tr>
                <tr>
                    <th colspan="20" class="aldem-bg-blue">CANTIDAD DE PICK UP (RECOJOS)
                    </th>
                    <th rowspan="4" colspan="1" class="aldem-bg-yellow">ESTADO</th>
                    <th colspan="19" class="aldem-bg-blue">COBRO A MARKEN PICK UP DOLARES USD</th>
                    <th rowspan="4" colspan="1" class="aldem-bg-yellow">TOTAL PICK UP DOLARES USD</th>

                    <!-- 36 -->
                    <th rowspan="4" colspan="3" class="aldem-bg-black-white">CANTIDAD DE HIELO SECO KG PARA MUESTRAS EXPORTACION X MAWB</th>
                    <!-- 33 -->
                    <th rowspan="4" colspan="10" class="aldem-bg-black-white">Observaciones</th>
                    <!-- 23 -->
                    <th rowspan="4" colspan="3" class="aldem-bg-black-white">COSTO (INVERSIÓN)DE HIELO SECO SECO EN LIMA SOLES(SIN IGV)</th>
                    <!-- 20 -->
                    <th rowspan="4" colspan="3" class="aldem-bg-black-white">COSTO (INVERSIÓN)DE HIELO SECO EN LIMA DOLARES TIPO DE CAMBIO: <?= number_format($moneda, 2) ?>/th>
                        <!-- 17 -->
                    <th rowspan="2" colspan="8" class="aldem-bg-gray">TARIFA DE HIELO SECO DOLARES</th>
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
                    <th colspan="1" rowspan="3" class="aldem-bg-gray">JOB</th>
                    <th colspan="9" class="aldem-bg-gray">GUIAS DE LIMA</th>
                    <th colspan="9" class="aldem-bg-black-white">GUIAS DE PROVINCIA</th>
                    <th colspan="9" class="aldem-bg-gray">GUIAS DE LIMA</th>
                    <th colspan="10" class="aldem-bg-black-white">GUIAS DE PROVINCIA</th>
                </tr>
                <tr>
                    <th colspan="9" class="aldem-bg-gray">FROZEN</th>
                    <th colspan="9" class="aldem-bg-black-white">FROZEN</th>
                    <th colspan="9" class="aldem-bg-gray">FROZEN</th>
                    <th colspan="10" class="aldem-bg-black-white">FROZEN</th>
                    <th colspan="4" class="aldem-bg-gray-white">LIMA</th>
                    <th colspan="4" class="aldem-bg-black-white">Provincia</th>
                    <th colspan="2" class="aldem-text-black" rowspan="2">LIMA</th>
                    <th rowspan="2" class="aldem-bg-yellow">TARIFA DOLARES</th>
                    <th rowspan="2" class="aldem-bg-black-white">GASTO DOLARES</th>

                </tr>
                <tr>
                    <th colspan="2" class="aldem-bg-gray">Ambiente</th>
                    <th colspan="2" class="aldem-bg-gray">BIO I</th>
                    <th colspan="2" class="aldem-bg-gray">BIO II</th>
                    <th colspan="2" class="aldem-bg-gray">BIO II</th>
                    <th colspan="1" class="aldem-bg-gray">Refrigerado</th>

                    <th colspan="2" class="aldem-bg-black-white">Ambiente</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO I</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO II</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO II</th>
                    <th colspan="1" class="aldem-bg-black-white">Refrigerado</th>

                    <th colspan="2" class="aldem-bg-gray">Ambiente</th>
                    <th colspan="2" class="aldem-bg-gray">BIO I</th>
                    <th colspan="2" class="aldem-bg-gray">BIO II</th>
                    <th colspan="2" class="aldem-bg-gray">BIO II</th>
                    <th colspan="1" class="aldem-bg-gray">Refrigerado</th>

                    <th colspan="2" class="aldem-bg-black-white">Ambiente</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO I</th>
                    <th colspan="2" class="aldem-bg-black-white">BIO II</th>
                    <th colspan="3" class="aldem-bg-black-white">BIO II</th>
                    <th colspan="1" class="aldem-bg-black-white">Refrigerado</th>

                    <!-- 6 espacios para LIMA - PROVINCIA -->
                    <th class="aldem-bg-gray">BIO I</th>
                    <th class="aldem-bg-gray">BIO II</th>
                    <th class="aldem-bg-gray">BIO II</th>
                    <th colspan="1" class="aldem-bg-gray">Refrigerado</th>

                    <th class="aldem-bg-black-white">BIO I</th>
                    <th class="aldem-bg-black-white">BIO II</th>
                    <th class="aldem-bg-black-white">BIO II</th>
                    <th colspan="1" class="aldem-bg-black-white">Refrigerado</th>
                </tr>
            </thead>
            <tbody style="color:black; text-align: center;">
                <?php foreach ($dataReport as $dr) {
                ?>
                    <tr>
                        <td><?= $dr->Recoleccion ?></td>
                        <td><?= $dr->waybill ?></td>
                        <td colspan="2"><?= $dr->Lima_Amb ?></td>
                        <td colspan="2"><?= $dr->Lima_Bio1 ?></td>
                        <td colspan="2"><?= $dr->Lima_Bio2 ?></td>
                        <td colspan="2"><?= $dr->Lima_Bio3 ?></td>
                        <td colspan="1"><?= $dr->Lima_refrigerado ?></td>
                        <td colspan="2"><?= $dr->Prov_Amb?></td>
                        <td colspan="2"><?= $dr->Prov_Bio1?></td>
                        <td colspan="2"><?=  $dr->Prov_Bio2?></td>
                        <td colspan="2"><?=  $dr->Prov_Bio3?></td>
                        <td colspan="1"><?=  $dr->Prov_Refrigerado?></td>
                        <td class="aldem-bg-blue-white"><?=  $dr->Estado?></td>
                        <td colspan="2"><?=  $dr->Lima_Amb_precio?></td>
                        <td colspan="2"><?=  $dr->Lima_Bio1_precio?></td>
                        <td colspan="2"><?=  $dr->Lima_Bio2_precio?></td>
                        <td colspan="2"><?=  $dr->Lima_Bio3_precio?></td>
                        <td colspan="1"><?=  $dr->Lima_Refrigerado_precio?></td>
                        <td colspan="2"><?=  $dr->Prov_Amb_precio?></td>
                        <td colspan="2"><?=  $dr->Prov_Bio1_precio?></td>
                        <td colspan="2"><?=  $dr->Prov_Bio2_precio?></td>
                        <td colspan="1"><?=  $dr->Prov_Bio3_precio?></td>
                        <td colspan="3"><?=  $dr->Prov_Refrigerado_precio?></td>
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
                        <td colspan="">dato 60</td>
                        <td>refrigerado</td>
                        <td>dato 62</td>
                        <td>dato 63</td>
                        <td>dato 64</td>
                        <td colspan="1">dato 65</td>
                        <td colspan="1">ttt</td>
                        <td colspan="2">dato 67</td>
                        <td colspan="4">dato 69jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj</td>
                        <td>utlimo dattoo</td>
                    </tr>

                <?php }  ?>

            </tbody>
        </table>
    </div>
</div>

<!-- <script>
    $(document).ready(function() {
        <?php aldem_datatables_in_spanish(); ?>
        $('#table_export_reporte_general').DataTable();

    });
</script> -->

<script>
    document.querySelector("#btnResetCurrency").addEventListener("click", () => {
        const fechareporte = '<?= $fechaReporte ?>';
        const url = `${location.origin}${location.pathname}?fechareporte=${fechareporte}`;
        location.href = url;
    })
</script>
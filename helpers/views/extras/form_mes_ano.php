<?php
// print_r($atts);
$urlRedirection = $atts["url"];
$nameParam = $atts["param"];
$textButton = $atts["text_button"] ?? "Enviar";

$urlGeneralRedirection = get_site_url(null, "$urlRedirection/?$nameParam=");
aldem_cargarStyles();
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="" method="post" id="form_export_reporte_general">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mes">Mes:</label>
                                <select name="mes" id="mes" class="form-control" placeholder="Elija el Mes" aria-describedby="Mes" required style="width: 100%;">

                                    <?php foreach (aldem_getMonths() as $key => $month) {
                                    ?>

                                        <option value="<?= sprintf("%02d", $key + 1) ?>"><?= $month ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ano">Año:</label>
                                <select name="ano" id="ano" class="form-control" placeholder="Elije el año" aria-describedby="ano" required style="width: 100%;">

                                    <?php foreach (aldem_getYears() as $key2 => $year) {
                                    ?>

                                        <option value="<?= $year ?>"><?= $year ?></option>
                                    <?php }  ?>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="far fa-file-alt mr-2"></i><?= $textButton ?></button>
                        </div>
                    </div>

            </div>
            </form>

            <script>
                $('#mes').val('<?= date("m") ?>');
                $('#ano').val('<?= date("Y") ?>');
                $('#ano').select2();
                $('#mes').select2();
                document.querySelector("#form_export_reporte_general").addEventListener("submit", (e) => {
                    e.preventDefault();
                    let ano = document.querySelector("#ano").value;
                    let mes = document.querySelector("#mes").value;
                    let anoMes = `${ano}${mes}`;

                    location.href = "<?= $urlGeneralRedirection ?>" + anoMes;
                })
            </script>

        </div>
    </div>
</div>

</div>
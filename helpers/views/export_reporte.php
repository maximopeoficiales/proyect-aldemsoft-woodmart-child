<?php
$urlExportGeneral = get_site_url(null, "marken_export_reporte_general/?fechareporte=");
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        .<div class="card">
            <!-- <img class="card-img-top" src="holder.js/100x180/" alt=""> -->
            <div class="card-body">
                <form action="" method="post" id="form_export_reporte_general">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="ano_mes">Mes y Año:</label>
                                <input type="month" name="ano_mes" id="ano_mes" class="form-control" placeholder="Elija el Mes" aria-describedby="Año y Mes" required>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="far fa-file-alt mr-2"></i>Ver Reporte</button>
                        </div>
                    </div>
                </form>

                <script>
                    document.querySelector("#form_export_reporte_general").addEventListener("submit", (e) => {
                        e.preventDefault();
                        let anoMes = document.querySelector("#ano_mes").value.replace("-", "");
                        console.log(anoMes);


                        location.href = "<?= $urlExportGeneral ?>" + anoMes;
                    })
                </script>

            </div>
        </div>
    </div>

</div>
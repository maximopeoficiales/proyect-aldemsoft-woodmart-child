<?php
date_default_timezone_set('America/Lima');
$shippers = (object) query_getShippers();
$countrys = (object) query_getCountrys();
$markenTypes = (object) query_getMarkenTypes();
$markenCajas = (object) query_getMarkenCajas();

// para el update
$update = $_GET["editjob"] != null || $_GET["editjob"] != "" ? true : false;
$id_marken_job = $update ? $_GET["editjob"] : null;
$markenJob = $update ? (object) query_getMarkenJobs($id_marken_job)[0] : null;
$shipperCurrent = $update ? (object) query_getShippers($markenJob->id_shipper)[0] : null;
$consiggneCurrent = $update ? (object) query_getMarkenConsiggne(null, $markenJob->id)[0] : null;
$fechaJob = $update ? explode(" ", $markenJob->fecha_hora)[0] : null;
$horaJob = $update ? substr(explode(" ", $markenJob->fecha_hora)[1], 0, -3) : null;
?>



<?php
aldem_cargarStyles();
aldem_show_message_custom("Se ha registrado correctamente el Job 😀", "Se ha actualizado correctamente el Job😀", "Ocurrio un error 😢 en el registro del Job");
?>

<?php if ($update && !aldem_isUserCreated($markenJob->id_usuario_created)) {
    aldem_noAccess();
    return;
} ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">

                <form action="<?php echo admin_url('admin-post.php') ?>" method="post">

                    <div class="row mt-2">

                        <div class="col-md-12">
                            <div class="form-group mb-2" style="width: 100%;">
                                <label for="waybill">Waybill: </label>
                                <input type="text" name="waybill" id="waybill" class="form-control" placeholder="Ingrese el Waybill" aria-describedby="waybill" required maxlength="35" value="<?= $markenJob->waybill ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="desc_shipper">Shipper:</label>
                                <div class="input-group ">
                                    <input type="text" required class="form-control" name="desc_shipper" id="desc_shipper" placeholder="Selecciona un Shipper" aria-label="Selecciona un Shipper" aria-describedby="basic-addon2" value="<?= $shipperCurrent->nombre ?>" disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalShipper">Seleccionar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="contacto">Contact: </label>
                                <input type="text" name="contacto" id="contacto" class="form-control" placeholder="Ingrese el Contacto" aria-describedby="contacto" maxlength="50" value="<?= $markenJob->contact ?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="contacto_telf">Contact Telephone: </label>
                                <input type="text" name="contacto_telf" id="contacto_telf" class="form-control" placeholder="Ingrese el telefono del contacto" aria-describedby="contacto_telf" maxlength="50" value="<?= $markenJob->contact_telephone ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="reference">Reference: </label>
                        <input type="text" name="reference" id="reference" class="form-control" placeholder="Ingrese la Referencia" aria-describedby="reference" maxlength="150" required value="<?= $markenJob->reference ?>">
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h5>Consignatario</h5>
                            <div class="pl-2">
                                <div class="form-group mb-2">
                                    <label for="consigge_nombre">Nombre: </label>
                                    <input type="text" name="consigge_nombre" id="consigge_nombre" class="form-control" placeholder="Ingrese el Nombre del Consignatario" aria-describedby="nombre" maxlength="150" value="<?= $consiggneCurrent->nombre ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="consigge_direccion">Direccion: </label>
                                    <input type="text" name="consigge_direccion" id="consigge_direccion" class="form-control" placeholder="Ingrese la Direccion del Consignatario" aria-describedby="consigge_direccion" maxlength="150" value="<?= $consiggneCurrent->direccion ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="id_pais">Pais: </label>
                                    <select name="id_pais" id="id_pais" style="width: 100%;">
                                        <?php
                                        foreach ($countrys as $kq => $country) {
                                        ?>
                                            <option value="<?= $country->id_pais ?>"><?= $country->desc_pais ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- <div class="form-group mt-2">
                        <label for="content">Descripcion del Contenido: </label>
                        <textarea name="content" id="content" class="form-control" placeholder="Ingrese la Descripcion del Contenido" aria-describedby="content" required minlength="1" maxlength="250" style="min-height: 140px;" value=""><?= $markenJob->content ?></textarea>
                    </div> -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="pcs">PCs: </label>
                                <input type="number" name="pcs" id="pcs" class="form-control" placeholder="Ingrese el numero de Pcs" required aria-describedby="pcs" min="1" value="<?= $markenJob->pcs ?>">
                            </div>
                        </div>

                        <!-- <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="range">Rango: </label>
                                <input type="text" name="range" id="range" class="form-control" placeholder="Ingrese el Rango" aria-describedby="range" minlength="1" maxlength="25" value="<?= $markenJob->range ?>">
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="id_marken_type">Type: </label>
                                <select name="id_marken_type" id="id_marken_type" required style="width: 100%;">
                                    <?php foreach ($markenTypes as $key => $markenType) {
                                    ?>
                                        <option value="<?= $markenType->id_marken_type ?>"><?= $markenType->descripcion ?></option>
                                    <?php                                     } ?>

                                </select>
                            </div v>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="id_caja">Caja: </label>
                                <select name="id_caja" id="id_caja" required style="width: 100%;">

                                    <?php foreach ($markenCajas as $key => $markenCaja) {
                                    ?>
                                        <option value="<?= $markenCaja->id_caja ?>"><?= $markenCaja->descripcion ?></option>
                                    <?php                                     } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mt-2">
                                <label for="instrucciones">Instrucciones: </label>
                                <textarea name="instrucciones" id="instrucciones" class="form-control" placeholder="Ingrese las Instrucciones" aria-describedby="instrucciones"  maxlength="500" style="min-height: 140px;" value=""><?= $markenJob->instrucciones ?></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 mt-1">
                            <div class="form-group mb-2">
                                <label for="fecha">Fecha de Recoleccion: </label>
                                <input type="date" name="fecha" id="fecha" class="form-control" placeholder="Ingrese el numero de fecha" required aria-describedby="fecha" value="<?= $fechaJob ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mt-1">

                            <div class="form-group mb-2">
                                <label for="hora">Hora de Recoleccion: </label>
                                <input type="time" name="hora" id="hora" class="form-control" placeholder="Ingrese el numero de hora" required aria-describedby="hora" value="<?= $horaJob ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ind_activo">Estado</label>
                        <select class="form-control" name="ind_activo" id="ind_activo">
                            <option value="1" <?= $markenType->ind_activo == 1  ? "selected" : "" ?>>Activo</option>
                            <option value="2" <?= $markenType->ind_activo == 2  ? "selected" : "" ?>>Inactivo</option>
                        </select>
                    </div>
                    <?php aldem_set_proccess_form(); ?>
                    <?php aldem_set_input_hidden("user_id", get_current_user_id()); ?>
                    <!-- <?php aldem_set_input_hidden("id_shipper", ""); ?> -->
                    <!-- <?php  ?> -->
                    <?php if ($update) {
                        aldem_set_action_name("update-job");
                        aldem_set_input_hidden("id_shipper",                         $shipperCurrent->id);
                        aldem_set_input_hidden("id_marken_job", $markenJob->id);
                        aldem_set_input_hidden("id_marken_consiggne", $consiggneCurrent->id);
                    } else {
                        aldem_set_input_hidden("id_shipper", "");
                        aldem_set_action_name("new-job");
                    } ?>
                    <button type="submit" class="btn btn-success w-100"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal de shippers -->
<div class="modal" id="modalShipper" tabindex="-1" role="dialog" aria-labelledby="modalShipper" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Elige un Shipper</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseListShippers">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="table-shippers-select" style="width: 100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Direccion</th>
                            <th scope="col">Site</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shippers as $key => $shipper) {
                        ?>
                            <!-- Modal -->
                            <tr>
                                <td class="d-flex justify-content-between" style="align-items: center !important;">
                                    <span><?= $shipper->nombre ?></span>
                                    <button type="button" class="btn shipper-btn" style="background: transparent;" data-id-shipper="<?= $shipper->id_shipper  ?>" data-nombre-shipper="<?= $shipper->nombre ?>"><i class="fas fa-check-circle fa-2x" style="color: #32CC52;"></i></button>
                                </td>
                                <td><?= $shipper->direccion ?></td>
                                <td><?= $shipper->site ?></td>

                            </tr>
                        <?php }  ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-capitalize" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>


<script>
    <?php
    if ($update) {
    ?>
        $('#id_pais').val('<?= $consiggneCurrent->id_pais ?>');
        $('#id_marken_type').val('<?= $markenJob->id_marken_type ?>');
        $('#id_caja').val('<?= $markenJob->id_caja ?>');
    <?php        }
    ?>

    $('#id_pais').val('604');
    $('#id_pais').select2();
    $('#id_caja').select2();
    $('#id_marken_type').select2();


    $(document).ready(function() {
        <?php aldem_datatables_in_spanish(); ?>
        $('#table-shippers-select').DataTable();
    });

    // listeners
    (() => {
        document.addEventListener("click", (e) => {
            if (e.target.classList.value.includes("shipper-btn")) {
                let idShipper = e.target.getAttribute("data-id-shipper");
                let nombreShipper = e.target.getAttribute("data-nombre-shipper");
                document.querySelector("#id_shipper").value = idShipper;
                document.querySelector("#desc_shipper").value = nombreShipper;
                document.querySelector("#btnCloseListShippers").click();
            }
        })
    })()
</script>
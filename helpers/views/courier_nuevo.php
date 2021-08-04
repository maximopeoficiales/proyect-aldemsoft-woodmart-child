<?php

// librerias datetime
wp_enqueue_script("AldemflatPickrJS", "https://cdn.jsdelivr.net/npm/flatpickr", '', '1.0.0');

// wp_enqueue_style("AldemflatPickrCSS", "https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css", '', '1.0.0');
wp_enqueue_style("AldemflatPickrDarkCSS", "https://npmcdn.com/flatpickr/dist/themes/dark.css", '', '1.0.0');


$update = $_GET["editjob"] != null || $_GET["editjob"] != "" ? true : false;
$id_courier_job = $update ? $_GET["editjob"] : null;
$countrys = (object) query_getCountrys();
$incoTerms = query_getIncoterms();
$exportadores = query_getExportadores();
$importadores = query_getImportadores();
$handlings = query_getMarkenHandlings();
$sites = query_getMarkenSiteTipos();
$courierCurrent = $update ? query_getCourierJobs($id_courier_job)[0] : null;
// update
$exportadorCurrent = $update ?  query_getExportadores($courierCurrent->id_exportador)[0] : null;
$importadorCurrent = $update ?  query_getImportadores($courierCurrent->id_importador)[0] : null;


$uriMarkenShipper = get_site_url() . "/wp-json/aldem/v1/marken_shipper/" . aldem_getUserNameCurrent();
$uriGETMarkenShipper = get_site_url() . "/wp-json/aldem/v1/getMarkenShippers/" . aldem_getUserNameCurrent();
$uriGETMarkenShipperId = get_site_url() . "/wp-json/aldem/v1/getMarkenShipper/" . aldem_getUserNameCurrent();
$urlVerifyWaybill = get_site_url() . "/wp-json/aldem/v1/existsWaybill/" . aldem_getUserNameCurrent();
$urlVerifyLevante = get_site_url() . "/wp-json/aldem/v1/verificarLevante/" . aldem_getUserNameCurrent();
?>
<?php if ($update && !aldem_isUserCreated($courierCurrent->id_usuario_created)) {
    aldem_noAccess();
    return;
} ?>
<?php
aldem_cargarStyles();
aldem_show_message_custom("Se ha registrado correctamente el nuevo servicio de importacion courier 😀", "Se ha actualizado correctamente el servicio de importacion courier😀", "Ocurrio un error 😢 en el registro del servicio de importacion courier");
?>

<div class="row justify-content-center p-4">
    <div pcs="col-md-8" style="width: 100%;">
        <form action="<?php echo admin_url('admin-post.php') ?>" method="post" id="form_courier_nuevo">
            <div class="card my-2">
                <div class="card-header bg-dark aldem_pointer" id="headingOne" data-toggle="collapse" data-target="#courier_importantes" aria-expanded="true" aria-controls="courier_importantes" style>
                    <h2 class="mb-0">
                        <div class="d-block text-white">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">
                                    Datos Principales
                                </h5>
                                <div class="">
                                    <i class="mx-2 fas fa-sort-down  fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </h2>
                </div>

                <div id="courier_importantes" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <label for="">DUA</label>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <input type="number" name="dua1" id="dua1" class="form-control" placeholder="Ingrese el DUA1" value="<?= $courierCurrent->dua1 ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <input type="number" name="dua2" id="dua2" class="form-control" placeholder="Ingrese el DUA2" value="<?= $courierCurrent->dua2 ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <input type="number" name="dua3" id="dua3" class="form-control" placeholder="Ingrese el DUA3" value="<?= $courierCurrent->dua3 ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <input type="number" name="dua4" id="dua4" class="form-control" placeholder="Ingrese el DUA4" value="<?= $courierCurrent->dua4 ?>" required>
                                </div>
                            </div>
                        </div>
                        <button id="btnBuscarDua" type="button" class="btn btn-success w-100 my-2" style="background-color: #98ddca; color: white; border-radius: 5px;">
                            <i class="fa fa-search-plus mr-1" "></i> Buscar por DUA</button>

                        <div class=" form-group">
                                <label for="job">Job</label>
                                <input type="text" name="job" id="job" class="form-control" required placeholder="Ingrese el Job" aria-describedby="job" maxlength="25" value="<?= $courierCurrent->waybill ?>" <?= $update ? " disabled" : "" ?>>
                                <?php if ($update) {
                                ?>
                                    <input type="hidden" name="job" value="<?= $courierCurrent->waybill ?>">
                                <?php } ?>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="manifiesto">Manifiesto</label>
                                <input type="number" name="manifiesto" id="manifiesto" class="form-control" placeholder="Ingrese el Manifiesto" aria-describedby="manifiesto" value="<?= $courierCurrent->manifiesto ?>" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="dua">DUA</label>
                                <input type="text" name="dua" id="dua" class="form-control" placeholder="Ingrese la DUA" aria-describedby="DUA" maxlength="50" value="<?= $courierCurrent->dua ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- <div class="col-md-6 ">
                                <div class="form-group mb-2">
                                    <label for="guia">Guia</label>
                                    <input type="text" name="guia" id="guia" class="form-control" placeholder="Ingrese la guia" aria-describedby="guia" value="<?= $courierCurrent->guia ?>">
                                </div>
                            </div> -->
                        <div class="col-md-12 ">
                            <div class="form-group mb-2">
                                <label for="master">Master</label>
                                <input type="text" name="master" id="master" class="form-control" placeholder="Ingrese el Master" aria-describedby="master" maxlength="20" value="<?= $courierCurrent->guia_master ?>">
                                <!-- <small id="helpId" class="text-muted">Escriba los 10 caracteres de su guia master</small> -->
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="">Pcs</label>
                                <input type="number" name="pcs" id="pcs" class="form-control" placeholder="Ingrese las pcs" aria-describedby="pcs" value="<?= $courierCurrent->pcs ?>">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="kilos">Kilos: </label>
                                <input type="number" name="kilos" id="kilos" class="form-control" placeholder="Ingrese los kilos" aria-describedby="kilos" step="0.01" value="<?= $courierCurrent->peso ?>">
                            </div>
                        </div>
                    </div>


                    <label for="importador-text">Importador</label>
                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled id="importador-text" placeholder="Elija un importador" value="<?= $importadorCurrent->nombre ?>">
                        <div class="input-group-append">
                            <button class="btn  bg-aldem-secondary dropdown-toggle " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Elije una opcion</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalImportador">Seleccionar Importador</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNewImportador">Nuevo Importador</a>
                            </div>
                        </div>
                    </div>
                    <label for="exportador-text">Exportador</label>
                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled id="exportador-text" placeholder="Elija un exportador" value="<?= $exportadorCurrent->nombre ?>">
                        <div class="input-group-append">
                            <button class="btn bg-aldem-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Elije una opcion</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalExportador">Seleccionar Exportador</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNewExportador">Nuevo Exportador</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
    <div class="card my-2">
        <div class="card-header bg-dark aldem_pointer" id="headingOpcional" data-toggle="collapse" data-target="#courier_opcionales" aria-expanded="true" aria-controls="courier_opcionales" style>
            <h2 class="mb-0">
                <div class="d-block text-white">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white">
                            Datos Opcionales
                        </h5>
                        <div class="">
                            <i class="mx-2 fas fa-sort-down  fa-lg"></i>
                        </div>
                    </div>
                </div>
            </h2>
        </div>

        <div id="courier_opcionales" class="collapse my-2" aria-labelledby="headingOpcional" data-parent="#accordionExample">
            <div class="card-body">
                <div class=" form-group my-2">
                    <label for="incoterm">IncoTerm:</label>
                    <select name="incoterm" id="incoterm" class="form-control" placeholder="Elija el Incoterm" aria-describedby="Mes" style="width: 100%;">
                        <option value="">Selecciona un Incoterm</option>
                        <?php foreach ($incoTerms as $key => $incoTerm) {
                        ?>

                            <option value="<?= $incoTerm->id_incoterm ?>"><?= $incoTerm->descripcion ?></option>
                        <?php }  ?>
                    </select>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 ">
                        <div class="form-group mb-2">
                            <label for="collection">Schd Collection</label>
                            <input type="date" name="collection" id="collection" class="form-control" placeholder="Ingrese el Collection" aria-describedby="collection" value="<?= $courierCurrent->schd_collection ?>">

                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="delivery">Delivery: </label>
                            <input type="date" name="delivery" id="delivery" class="form-control" placeholder="Ingrese hora del delivery" aria-describedby="delivery" value="<?= $courierCurrent->schd_delivery ?>">

                        </div>
                    </div>
                </div>


                <div class="form-group my-2">
                    <label for="protocolo">Protocolo: </label>
                    <input type="text" name="protocolo" id="protocolo" class="form-control" placeholder="Ingrese el Protocolo" aria-describedby="protocolo" maxlength="50" value="<?= $courierCurrent->protocolo ?>">
                    <!-- <textarea name="protocolo" id="protocolo" class="form-control" placeholder="Ingrese el protocolo" aria-describedby="protocolo" maxlength="50" style="min-height: 140px;"></textarea> -->
                </div>
                <div class="form-group my-2">
                    <label for="instructions">Instructions: </label>
                    <textarea name="instructions" id="instructions" class="form-control" placeholder="Ingrese las instrucciones" aria-describedby="instructions" maxlength="500" style="min-height: 140px;"><?= $courierCurrent->instrucciones ?></textarea>
                </div>

                <!-- nuevos campos -->
                <div class="form-group my-2">
                    <label for="fecha_levante">Fecha Levante: </label>
                    <input type="text" class="form-control" id="fecha_levante" name="fecha_levante" placeholder="Ingresa la Fecha y Hora">
                </div>
                <div class="row my-4">
                    <div class="col-md-6">

                        <label for="fecha_levante">Seleccione su Green Channel: </label>
                        <div class="form-check form-check-inline d-flex justify-content-center my-2 ">
                            <label class="form-check-label d-flex justify-content-center align-items-center mx-4">
                                <input class="form-check-input" type="radio" name="green_channel" id="rb_verde" value="1" <?= $courierCurrent->green_channel == 1 ? " checked" : "" ?>>
                                <div class="" style="border-radius: 50%; width: 50px; height: 50px; background-color: #32CC52; padding: 5px; margin-left: 10px;">
                                </div>
                            </label>
                            <label class="form-check-label d-flex justify-content-center align-items-center mx-4">
                                <input class="form-check-input" type="radio" name="green_channel" id="rb_amarillo" value="2" <?= $courierCurrent->green_channel == 2 ? " checked" : "" ?>>
                                <div class="" style="border-radius: 50%; width: 50px; height: 50px; background-color: #F6EB73; padding: 5px; margin-left: 10px;">
                                </div>
                            </label>
                            <label class="form-check-label d-flex justify-content-center align-items-center mx-4">
                                <input class="form-check-input" type="radio" name="green_channel" id="rb_rojo" value="3" <?= $courierCurrent->green_channel == 3 ? " checked" : "" ?>>
                                <div class="" style="border-radius: 50%; width: 50px; height: 50px; background-color: #DC3545; padding: 5px; margin-left: 10px;">
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group my-2">
                            <label for="dam">Dam: </label>
                            <input type="text" class="form-control" id="dam" placeholder="Ingrese Dam" name="dam" maxlength="150" value="<?= $courierCurrent->dam ?>">
                        </div>
                    </div>
                </div>


                <!-- nuevos campos  -->



            </div>
        </div>


    </div>
    <div class="card my-2">
        <div class="card-header bg-dark aldem_pointer" id="headingOpcional2" data-toggle="collapse" data-target="#costos_tarifas" aria-expanded="true" aria-controls="costos_tarifas" style>
            <h2 class="mb-0">
                <div class="d-block text-white">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white">
                            Costos y Tarifas
                        </h5>
                        <div class="">
                            <i class="mx-2 fas fa-sort-down  fa-lg"></i>
                        </div>
                    </div>
                </div>
            </h2>
        </div>
        <div id="costos_tarifas" class="collapse my-2" aria-labelledby="headingOpcional2" data-parent="#accordionExample">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class=" form-group my-2">
                            <label for="id_site">Site:</label>
                            <select name="id_site" id="id_site" class="form-control" placeholder="Elija el Site" aria-describedby="site" style="width: 100%;">
                                <option value="">Selecciona un Site</option>
                                <?php foreach ($sites as $key => $site) {
                                ?>
                                    <option value="<?= $site->id_marken_site ?>"><?= $site->descripcion ?></option>
                                <?php }  ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class=" form-group my-2">
                            <label for="id_handling">Handling:</label>
                            <select name="id_handling" id="id_handling" class="form-control" placeholder="Elija el Handling" aria-describedby="handling" style="width: 100%;">
                                <option value="">Selecciona un Handling</option>
                                <?php foreach ($handlings as $key => $handling) {
                                ?>
                                    <option value="<?= $handling->id_handling ?>"><?= $handling->descripcion ?></option>
                                <?php }  ?>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="ind_transporte" id="ind_transporte" value="1" <?= $courierCurrent->ind_transporte == 1 ? "checked " : "" ?>>
                            <label class="form-check-label" for="ind_transporte">
                                Costo de Servicio de Transporte
                            </label>
                            <small id="helpId" class="text-muted">No marcar si el cliente paga el transporte</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" name="ind_servicio_aduana" type="checkbox" id="ind_servicio_aduana" value="1" <?= $courierCurrent->ind_servicio_aduana == 1 ? " checked " : "" ?>>
                            <label class="form-check-label" for="ind_servicio_aduana">
                                Tarifa Servicio Aduana
                            </label>
                            <small id="helpId" class="text-muted">No marcar si el cliente o Broker paga la tarifa del servicio de aduana</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" name="ind_costo_aduana" type="checkbox" id="ind_costo_aduana" value="1" <?= $courierCurrent->ind_costo_aduana == 1 ? " checked" : ""  ?>>
                            <label class="form-check-label" for="ind_costo_aduana">
                                Costo Servicio Aduana
                            </label>
                            <small id="helpId" class="text-muted">No marcar si el cliente paga el costo de servicio de la aduana</small>
                        </div>
                    </div>

                </div>
                <div class="form-group mb-2">
                    <label for="tarifa_almacenaje">Tarifa Almacenaje</label>
                    <input type="number" name="tarifa_almacenaje" id="tarifa_almacenaje" class="form-control" placeholder="Ingrese la Tarifa Almacenaje" aria-describedby="tarifa_almacenaje" step="0.01" value="<?= $courierCurrent->tarifa_almacenaje ?>">
                </div>
                <div class="form-group mb-2">
                    <label for="tarifa_costo">Costo Almacenaje</label>
                    <input type="number" name="tarifa_costo" id="tarifa_costo" class="form-control" placeholder="Ingrese el Costo Almacenaje" aria-describedby="tarifa_costo" step="0.01" value="<?= $courierCurrent->tarifa_costo ?>">
                </div>
                <div class="form-group mb-2">
                    <label for="tarifa_impuestos">Impuesto Tarifa</label>
                    <input type="number" name="tarifa_impuestos" id="tarifa_impuestos" class="form-control" placeholder="Ingrese los Impuestos" aria-describedby="tarifa_impuestos" step="0.01" value="<?= $courierCurrent->tarifa_impuestos ?>">
                </div>
            </div>
        </div>
    </div>

    <?php aldem_set_input_hidden("id_user", get_current_user_id()); ?>
    <?php if ($update) {
        // aldem_set_input_hidden("master", $courierCurrent->guia_master);
        aldem_set_input_hidden("id_exportador", $courierCurrent->id_exportador);
        aldem_set_input_hidden("id_importador", $courierCurrent->id_importador);
        aldem_set_input_hidden("id_courier_job", $id_courier_job);
        aldem_set_action_name("update-courier", "");
    } else {
        // aldem_set_input_hidden("master", "");
        aldem_set_input_hidden("id_exportador", "");
        aldem_set_input_hidden("id_importador", "");
        aldem_set_action_name("new-courier", "");
    } ?>
    <?php aldem_set_proccess_form(); ?>

    <button id="btnSubmit" type="submit" class="btn btn-success w-100 my-2" style="background-color: #98ddca; color: white; border-radius: 5px;"> <i class="fa fa-save mr-1"></i>Guardar</button>
    </form>
</div>
</div>

<!-- modal de Exportador -->
<div class="modal" id="modalExportador" tabindex="-1" role="dialog" aria-labelledby="modalExportador" aria-hidden="true" style="margin-top: 100px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark aldem-text-white">
                <h5 class="modal-title aldem-text-white">Elige un Exportador</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseListExportador">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-exportador">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="table-exportadores-select" style="width: 100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Pais</th>
                            <th scope="col" class="none">Direccion</th>
                            <th scope="col" class="none">Correo1</th>
                            <th scope="col" class="none">Correo2</th>
                            <th scope="col" class="none">Correo3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exportadores as $key => $exportador) {
                        ?>
                            <tr>
                                <td class="d-flex justify-content-between" style="align-items: center !important;">
                                    <span><?= $exportador->nombre ?></span>
                                    <div class="">
                                        <button type="button" class="btn edit-btn" style="background: transparent;" data-id="<?= $exportador->id_shipper  ?>" data-id-tipo="2">
                                            <i class="fas fa-edit fa-2x edit-btn" data-id="<?= $exportador->id_shipper  ?>" data-id-tipo="2" style="color: #17A2B8"></i></button>

                                        <button type="button" class="btn exportador-btn" style="background: transparent;" data-id-exportador="<?= $exportador->id_exportador  ?>" data-nombre-exportador="<?= $exportador->nombre ?>"><i class="fas fa-check-circle fa-2x exportador-btn" style="color: #32CC52;" data-id-exportador="<?= $exportador->id_exportador  ?>" data-nombre-exportador="<?= $exportador->nombre ?>"></i></button>

                                    </div>
                                </td>
                                <td><?= $exportador->desc_pais ?></td>
                                <td><?= $exportador->direccion ?></td>
                                <td><?= $exportador->correo1 ?></td>
                                <td><?= $exportador->correo2 ?></td>
                                <td><?= $exportador->correo3 ?></td>
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

<!-- modal de Importador -->
<div class="modal" id="modalImportador" tabindex="-1" role="dialog" aria-labelledby="modalImportador" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title aldem-text-white">Elige un Importador</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseListImportador">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-importador">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="table-importadores-select" style="width: 100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Pais</th>
                            <th scope="col" class="none">Direccion</th>
                            <th scope="col" class="none">Correo1</th>
                            <th scope="col" class="none">Correo2</th>
                            <th scope="col" class="none">Correo3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($importadores as $key => $importador) {
                        ?>
                            <tr>
                                <td class="d-flex justify-content-between" style="align-items: center !important;">
                                    <span><?= $importador->nombre ?></span>

                                    <div class="">
                                        <button type="button" class="btn edit-btn" style="background: transparent;" data-id="<?= $importador->id_importador  ?>" data-id-tipo="3">

                                            <i class="fas fa-edit fa-2x edit-btn" data-id="<?= $importador->id_importador  ?>" data-id-tipo="3" style="color: #17A2B8"></i></button>

                                        <button type="button" class="btn importador-btn" style="background: transparent;" data-id-importador="<?= $importador->id_importador  ?>" data-nombre-importador="<?= $importador->nombre ?>"><i class="fas fa-check-circle fa-2x importador-btn" style="color: #32CC52;" data-id-importador="<?= $importador->id_importador  ?>" data-nombre-importador="<?= $importador->nombre ?>"></i></button>
                                    </div>
                                </td>
                                <td><?= $importador->desc_pais ?></td>
                                <td><?= $importador->direccion ?></td>
                                <td><?= $importador->correo1 ?></td>
                                <td><?= $importador->correo2 ?></td>
                                <td><?= $importador->correo3 ?></td>
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
<!-- modales de creacion -->
<div class="modal" id="modalNewExportador" tabindex="-1" role="dialog" aria-labelledby="modalNewExportador" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title aldem-text-white">Crear un Exportador</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseModalExportador">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="formNewExportador">
                <div class="modal-body">
                    <div class="" id="validacionesExportador">
                    </div>
                    <div class="form-group">
                        <label for="nombreNewExportador">Nombre</label>
                        <input type="text" name="nombreNewExportador" id="nombreNewExportador" class="form-control" placeholder="Ingrese nombre" aria-describedby="nombreNewExportador" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="direccionNewExportador">Direccion</label>
                        <input type="text" name="direccionNewExportador" id="direccionNewExportador" class="form-control" placeholder="Ingrese direccion" aria-describedby="direccionNewExportador" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="correo1NewExportador">Correo1</label>
                        <input type="email" name="correo1NewExportador" id="correo1NewExportador" class="form-control" placeholder="Ingrese correo1" aria-describedby="correo1NewExportador" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="correo2NewExportador">Correo2</label>
                        <input type="email" name="correo2NewExportador" id="correo2NewExportador" class="form-control" placeholder="Ingrese correo2" aria-describedby="correo2NewExportador" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="correo3NewExportador">Correo3</label>
                        <input type="email" name="correo3NewExportador" id="correo3NewExportador" class="form-control" placeholder="Ingrese correo3" aria-describedby="correo3NewExportador" maxlength="200">
                    </div>

                    <div class="form-group">
                        <label for="paisShipper" style="display: block;">Pais:</label>
                        <select class="form-control select-countrys" name="paisNewExportador" id="paisNewExportador" style="width: 100% !important;">
                            <?php
                            foreach ($countrys as $country) {
                            ?>
                                <option value="<?= $country->id_pais ?>">
                                    <?= $country->desc_pais ?>
                                </option>
                            <?php } ?>
                        </select>

                    </div>
                    <button type="submit" class="btn btn-success w-100" style="background-color: #98ddca; color: white; border-radius: 5px;"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-capitalize" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalNewImportador" tabindex="-1" role="dialog" aria-labelledby="modalNewImportador" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title aldem-text-white">Crear un Importador</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseModalImportador">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="formNewImportador">
                <div class="modal-body">
                    <div class="" id="validacionesImportador">
                    </div>
                    <div class="form-group">
                        <label for="nombreNewImportador">Nombre</label>
                        <input type="text" name="nombreNewImportador" id="nombreNewImportador" class="form-control" placeholder="Ingrese nombre" aria-describedby="nombreNewImportador" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="direcccionNewImportador">Direccion</label>
                        <input type="text" name="direccionNewImportador" id="direccionNewImportador" class="form-control" placeholder="Ingrese direccion" aria-describedby="direccionNewImportador" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="correo1NewImportador">Correo1</label>
                        <input type="email" name="correo1NewImportador" id="correo1NewImportador" class="form-control" placeholder="Ingrese correo1" aria-describedby="correo1NewImportador" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="correo2NewImportador">Correo2</label>
                        <input type="email" name="correo2NewImportador" id="correo2NewImportador" class="form-control" placeholder="Ingrese correo2" aria-describedby="correo2NewImportador" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="correo3NewImportador">Correo3</label>
                        <input type="email" name="correo3NewImportador" id="correo3NewImportador" class="form-control" placeholder="Ingrese correo3" aria-describedby="correo3NewImportador" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="paisShipper" style="display: block;">Pais:</label>
                        <select class="form-control select-countrys" name="paisNewImportador" id="paisNewImportador" style="width: 100% !important;">
                            <?php
                            foreach ($countrys as $country) {
                            ?>
                                <option value="<?= $country->id_pais ?>">
                                    <?= $country->desc_pais ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100 btn-aldem-verde"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-capitalize" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>


<!-- formulario de edicion -->
<button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#modalEdit" id="btnOpenModalEdit">
</button>

<div class="modal" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title aldem-text-white text-capitalize" id="titleEdit"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseModalEdit">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="#" method="post" id="formEditMarken">

                <div class="modal-body">
                    <input type="hidden" id="editTipo">
                    <input type="hidden" id="idEditMarken">
                    <div class="form-group">
                        <label for="nombreEdit">Nombre</label>
                        <input type="text" name="nombreEdit" id="nombreEdit" class="form-control" placeholder="Ingrese nombre" aria-describedby="nombreEdit" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="direccionEdit">Direccion</label>
                        <input type="text" name="direccionEdit" id="direccionEdit" class="form-control" placeholder="Ingrese direccion" aria-describedby="direccionEdit" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="correo1Edit">Correo1</label>
                        <input type="email" name="correo1Edit" id="correo1Edit" class="form-control" placeholder="Ingrese correo1" aria-describedby="correo1Edit" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="correo2Edit">Correo2</label>
                        <input type="email" name="correo2Edit" id="correo2Edit" class="form-control" placeholder="Ingrese correo2" aria-describedby="correo2Edit" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="correo3Edit">Correo3</label>
                        <input type="email" name="correo3Edit" id="correo3Edit" class="form-control" placeholder="Ingrese correo3" aria-describedby="correo3Edit" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="paisShipper" style="display: block;">Pais:</label>
                        <select class="form-control select-countrys" name="paisEdit" id="paisEdit" style="width: 100% !important;">
                            <?php
                            foreach ($countrys as $country) {
                            ?>
                                <option value="<?= $country->id_pais ?>">
                                    <?= $country->desc_pais ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100 btn-aldem-verde"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-capitalize" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        <?php aldem_datatables_in_spanish(); ?>
        $('#table-exportadores-select').DataTable();
        $('#table-importadores-select').DataTable();
        // flatpckr

        $("#fecha_levante").flatpickr({
            enableTime: true,
            minTime: "09:00"
        });
        <?php if ($update && $courierCurrent->fecha_levante != "0000-00-00 00:00:00") {
        ?>
            $("#fecha_levante").flatpickr({
                enableTime: true,
                defaultDate: "<?= $courierCurrent->fecha_levante ?>"
            });
        <?php        }
        ?>
    });
    <?php

    if ($update) {
    ?>
        $('#incoterm').val('<?= $courierCurrent->id_incoterm ?>');
        $('#id_site').val('<?= $courierCurrent->id_site ?>');
        $('#id_handling').val('<?= $courierCurrent->id_handling ?>');
    <?php        }
    ?>

    $('#incoterm').select2();
    $('#paisNewImportador').val('604');
    $('#paisNewExportador').val('604');
    $('#paisNewImportador').select2();
    $('#paisNewExportador').select2();
    // formulario edit
    $('#paisEdit').select2();
    $('#id_site').select2();
    $('#id_handling').select2();
    (() => {
        const isPickup = false;
        // nuevas funciones
        const $getValue = (id) => {
            try {
                return document.querySelector(id).value
            } catch (error) {
                return "";
            }
        };
        const $setValue = (id, value = "") => {
            try {
                return document.querySelector(id).value = value;
            } catch (error) {
                return "";
            }
        };
        const showSpinnerCargando = () => {
            Swal.fire({
                title: '<strong>Cargando...</strong>',
                html: `<div class='text-center'><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>`,
                showConfirmButton: false
            })
        };
        const closeSpinnerCargando = () => {
            Swal.closeModal();
        }
        const closeModalsBoostrap = () => {
            $(" .close").click();
        }
        // necesarios para marken shipper

        const verifyWaybill = async () => {
            try {
                let myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");
                myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
                let raw = JSON.stringify({
                    "waybill": document.querySelector("#job").value,
                });
                let requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow'
                };
                let response = await (await (fetch('<?= $urlVerifyWaybill  ?>', requestOptions))).json();
                if (response.status == 200) {
                    return true;
                } else if (response.status == 404) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: `${response.data.replace("Waybill","Job")}`,
                    })
                    return false;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error Job ya registrado, ingrese otro por favor!',
                    });
                    document.querySelector("#btnSubmit").removeAttribute("disabled");
                    return false;
                }
            } catch (error) {
                // error en el servidor
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ocurrio un error en Servidor!',
                })
                return false;
            }
        }
        const getMarkenShippersAsync = async (id_tipo = 2, id_table, idTipoReal) => {
            // No mover esta parte importante para el modal
            let tipo = id_tipo == 2 ? "exportador" : "importador";
            let myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
            myHeaders.append("Content-Type", "application/json");
            let raw = JSON.stringify({
                "id_tipo": idTipoReal,
            });

            let requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };
            let response = await (await fetch("<?= $uriGETMarkenShipper ?>", requestOptions)).json();

            if (response.status == 200) {
                // todo bien
                // console.log(response.data);
                let tabladiv = document.querySelector(id_table + "_wrapper");
                let modalBody = document.querySelector("#modal-body-" + tipo);
                modalBody.innerHTML = "";
                let htmlTemplate = `
                <table class="table table-striped table-bordered dt-responsive nowrap" id="table-${tipo+"es"}-select" style="width: 100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Pais</th>
                            <th scope="col" class="none">Direccion</th>
                            <th scope="col" class="none">Correo1</th>
                            <th scope="col" class="none">Correo2</th>
                            <th scope="col" class="none">Correo3</th>
                        </tr>
                    </thead>
                    <tbody>`;
                response.data.forEach(shipper => {
                    htmlTemplate += `

                    <tr>
                        <td class="d-flex justify-content-between" style="align-items: center !important;">
                            <span>${shipper.nombre}</span>
                            <div>
                            <button type="button" class="btn edit-btn" style="background: transparent;" data-id="${shipper.id_shipper}" data-id-tipo="${idTipoReal}">
                                            
                                            <i class="fas fa-edit fa-2x edit-btn" data-id="${shipper.id_shipper}" data-id-tipo="${idTipoReal}" style="color: #17A2B8"></i>
                                            </button>
                        

                            <button type="button" class="btn ${tipo}-btn" style="background: transparent;" data-id-${tipo}="${id_tipo==2 ? shipper.id_exportador : shipper.id_importador}" data-nombre-${tipo}="${shipper.nombre}"><i class="fas fa-check-circle fa-2x ${tipo}-btn" style="color: #32CC52;" data-id-${tipo}="${id_tipo==2 ? shipper.id_exportador : shipper.id_importador}"   data-nombre-${tipo}="${shipper.nombre}"></i></button>
                        
                            </div>

                            </td>
                        <td>${shipper.desc_pais}</td>
                        <td>${shipper.direccion}</td>
                        <td>${shipper.correo1}</td>
                        <td>${shipper.correo2}</td>
                        <td>${shipper.correo3}</td>
                    </tr>
                    `;
                });
                htmlTemplate += `
                </tbody>
                <table>
                `;
                modalBody.innerHTML = htmlTemplate;
                $(id_table).DataTable();
                // $(id_table).DataTable().draw();
            } else {
                // algo salio mal
                console.error(response.message)
            }
        }
        const charginAllTables = async (idTipoReal) => {
            // aqui envia el tipo real segun el tabla correspondiente
            if (!isPickup) {
                await getMarkenShippersAsync(2, "#table-exportadores-select", 2);
                await getMarkenShippersAsync(3, "#table-importadores-select", 3);
            } else {
                // para pickup
                await getMarkenShippersAsync(4, "#table-exportadores-select", 4);
                await getMarkenShippersAsync(5, "#table-importadores-select", 5);
            }

        }
        const saveMarkenShipperAsync = async (exportador = false, idTipoReal) => {
            showSpinnerCargando();
            let tipo = exportador ? "Exportador" : "Importador";
            let id_tipo = exportador ? 2 : 3;
            let myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
            myHeaders.append("Content-Type", "application/json");
            let raw = JSON.stringify({
                "nombre": $getValue(`#nombreNew${tipo}`),
                "direccion": $getValue(`#direccionNew${tipo}`),
                "correo1": $getValue(`#correo1New${tipo}`),
                "correo2": $getValue(`#correo2New${tipo}`),
                "correo3": $getValue(`#correo3New${tipo}`),
                "id_pais": $getValue(`#paisNew${tipo}`),
                "id_tipo": idTipoReal,
                "id_user": <?= get_current_user_id() ?>,
            });

            let requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };
            let response = await (await fetch("<?= $uriMarkenShipper ?>", requestOptions)).json();
            closeSpinnerCargando();
            if (response.status == 200) {
                // todo salio correctamente
                Swal.fire({
                    icon: "success",
                    title: "Se Ha Creado Nuevo " + getTypeDescription(idTipoReal),
                    showConfirmButton: false,
                    timer: 1500
                })
                // poner texto en input
                if (exportador) {
                    document.querySelector(`#exportador-text`).value = response.data.nombre;
                    document.querySelector(`#id_exportador`).value = response.data.id_marken_shipper;
                } else {
                    document.querySelector(`#importador-text`).value = response.data.nombre;
                    document.querySelector(`#id_importador`).value = response.data.id_marken_shipper;
                }
                await charginAllTables();
            } else if (response.status == 404) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `${response.data}`,
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `${response.message}`,
                })
                console.error(response);
            }

        }
        const getMarkenShipperByIDAsync = async (id) => {
            showSpinnerCargando();
            let myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
            myHeaders.append("Content-Type", "application/json");
            let raw = JSON.stringify({
                "id": id,
            });
            let requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };
            let response = await (await fetch("<?= $uriGETMarkenShipperId ?>", requestOptions)).json();
            closeSpinnerCargando();
            if (response.status == 200) {
                return (response.data[0]);
            }
        }
        const getJsonMarkenShipper = () => {
            return {
                "id": $getValue(`#idEditMarken`),
                "nombre": $getValue(`#nombreEdit`),
                "direccion": $getValue(`#direccionEdit`),
                "correo1": $getValue(`#correo1Edit`),
                "correo2": $getValue(`#correo2Edit`),
                "correo3": $getValue(`#correo3Edit`),
                "id_pais": $getValue(`#paisEdit`),
                "id_tipo": $getValue(`#editTipo`),
                "id_user": <?= get_current_user_id() ?>,
            }
        }
        const getTypeDescription = (idTipo = 0) => {
            let descripcion = "";
            switch (parseInt(idTipo)) {
                case 2:
                    descripcion = "Exportador";
                    break;
                case 3:
                    descripcion = "Importador";
                    break;
                case 4:
                    descripcion = "Remitente";
                    break;
                case 5:
                    descripcion = "Consignatorio";
                    break;
                default:
                    descripcion = "desconocido"
                    break;
            }
            return descripcion;
        }
        const setDataFormEdit = (data) => {
            $setValue("#nombreEdit", data.nombre);
            $setValue("#direccionEdit", data.direccion);
            $setValue("#correo1Edit", data.correo1);
            $setValue("#correo2Edit", data.correo2);
            $setValue("#correo3Edit", data.correo3);
            // seteo valor al select y notifica al select
            // si es nulo ponle peru
            $("#paisEdit").val(data.id_country ?? 604);
            $("#paisEdit").trigger("change");
        }

        const executeEventFormEditMarken = async (e) => {
            if (e.target.classList.value.includes("edit-btn")) {
                let id = e.target.getAttribute("data-id");
                let idTipo = e.target.getAttribute("data-id-tipo");
                document.querySelector("#idEditMarken").value = id;
                document.querySelector("#editTipo").value = idTipo;
                document.querySelector("#titleEdit").innerHTML = "Editar " + getTypeDescription(idTipo);
                // extraer datos del shipper 
                // cierra todos los modales abiertos de boostrap
                closeModalsBoostrap();
                // obtener data del shipper
                setDataFormEdit(await getMarkenShipperByIDAsync(id));
                document.querySelector("#btnOpenModalEdit").click();
            }
        }
        const updateMarkenShipperAsync = async () => {
            showSpinnerCargando();
            let tipo = getTypeDescription($getValue("#editTipo"));
            let id_tipo = $getValue("#idEditMarken");
            let myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
            myHeaders.append("Content-Type", "application/json");
            let raw = JSON.stringify(getJsonMarkenShipper());
            let requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };
            let response = await (await fetch("<?= $uriMarkenShipper ?>", requestOptions)).json();
            if (response.status == 200) {
                // todo salio correctamente
                Swal.fire({
                    icon: "success",
                    title: "Se Ha Actualizado Correctamente El " + tipo,
                    showConfirmButton: false,
                    timer: 1500
                })

                await charginAllTables();
                closeSpinnerCargando();
            } else if (response.status == 404) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `${response.data}`,
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `${response.message}`,
                })
            }

        }

        // div de validaciones => #validacionesFormEdit
        const eventSubmitFormEditMarken = () => {

            document.querySelector("#formEditMarken").addEventListener("submit", async (e) => {
                e.preventDefault();
                await updateMarkenShipperAsync();
                e.target.reset();
                closeModalsBoostrap();
                // actualizar el marken shipper
            })
        }

        // verificar Levante

        const getDataByDuaAsync = async (e) => {
            showSpinnerCargando();
            let myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
            myHeaders.append("Content-Type", "application/json");
            let raw = JSON.stringify({
                dua1: $getValue("#dua1"),
                dua2: parseInt($getValue("#dua2")) + 2000,
                dua3: $getValue("#dua3"),
                dua4: $getValue("#dua4")
            });
            let requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };
            let response = await (await fetch("<?= $urlVerifyLevante ?>", requestOptions)).json();
            closeSpinnerCargando();
            if (response.status == 200) {
                // todo salio correctamente
                console.log(response);
                const {
                    data: {
                        fecha_levante,
                        green_channel,
                        id_importador,
                        job,
                        kilos,
                        manifiesto,
                        pcs,
                        protocolo,
                        semaforo
                    }
                } = response;
                $setValue("#job", job);
                $setValue("#manifiesto", manifiesto);
                $setValue("#pcs", pcs);
                $setValue("#kilos", kilos);
                $setValue("#protocolo", protocolo);
                $("#fecha_levante").flatpickr({
                    defaultDate: fecha_levante ?? "",
                    enableTime: true
                })
                // seleccion de semaforo
                switch (green_channel) {
                    case 1:
                        document.querySelector("#rb_verde").checked = true
                        break;
                    case 2:
                        document.querySelector("#rb_amarillo").checked = true
                        break;
                    case 3:
                        document.querySelector("#rb_rojo").checked = true
                        break;
                    default:
                        break;
                }

            } else if (response.status == 404) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `${response.data}`,
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: `${response.message}`,
                    html: `${response.data ?? ""}`,
                })
                console.error(response);
            }
        }
        // fin de nuevas funciones
        // eventos
        document.querySelector("#formNewExportador").addEventListener("submit", async (e) => {
            e.preventDefault();
            document.querySelector("#btnCloseModalExportador").click();
            try {
                await saveMarkenShipperAsync(true, 2);
                e.target.reset();
            } catch (error) {
                console.error(error);
            }
        });
        document.querySelector("#formNewImportador").addEventListener("submit", async (e) => {
            e.preventDefault();
            document.querySelector("#btnCloseModalImportador").click();
            try {
                await saveMarkenShipperAsync(false, 3);
                e.target.reset();
            } catch (error) {
                console.error(error);
            }
        });
        // seleccionador del modal
        document.addEventListener("click", async (e) => {
            if (e.target.classList.value.includes("exportador-btn")) {
                let idShipper = e.target.getAttribute("data-id-exportador");
                let nombreShipper = e.target.getAttribute("data-nombre-exportador");
                document.querySelector("#id_exportador").value = idShipper;
                document.querySelector("#exportador-text").value = nombreShipper;
                document.querySelector("#btnCloseListExportador").click();
            } else if (e.target.classList.value.includes("importador-btn")) {
                let idShipper = e.target.getAttribute("data-id-importador");
                let nombreShipper = e.target.getAttribute("data-nombre-importador");
                document.querySelector("#id_importador").value = idShipper;
                document.querySelector("#importador-text").value = nombreShipper;
                document.querySelector("#btnCloseListImportador").click();
            }
            // evento click del modal edit
            await executeEventFormEditMarken(e);
            // fin de evento modal
        });

        document.querySelector("#form_courier_nuevo").addEventListener("submit", async (e) => {
            e.preventDefault();
            document.querySelector("#btnSubmit").setAttribute("disabled", "true");
            // verificacion de waybill disponible
            let update = <?= $update ? "true" : "false" ?>;
            if (!update) {
                if (await verifyWaybill()) {
                    e.target.submit();
                }
            } else {
                e.target.submit();
            }

        });

        // verificar Levante
        document.querySelector("#btnBuscarDua").addEventListener("click", async () => {
            await getDataByDuaAsync();
        });

        eventSubmitFormEditMarken();
    })()
</script>
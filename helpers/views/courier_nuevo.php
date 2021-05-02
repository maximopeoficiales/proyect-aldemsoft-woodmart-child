<?php
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
$importadorCurrent = $update ?  query_getImportadores($importadorCurrent->id_importador)[0] : null;


$uriMarkenShipper = get_site_url() . "/wp-json/aldem/v1/marken_shipper/" . aldem_getUserNameCurrent();
$uriGETMarkenShipper = get_site_url() . "/wp-json/aldem/v1/getMarkenShippers/" . aldem_getUserNameCurrent();


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
        <form action="<?php echo admin_url('admin-post.php') ?>" method="post">
            <div class="card">
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
                        <div class="form-group">
                            <label for="job">Job</label>
                            <input type="text" name="job" id="job" class="form-control" required placeholder="Ingrese el Job" aria-describedby="job" maxlength="25" value="<?= $courierCurrent->waybill ?>">
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
                                    <input type="text" name="dua" id="dua" class="form-control" placeholder="Ingrese la DUA" aria-describedby="DUA" maxlength="20" value="<?= $courierCurrent->dua ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 ">
                                <div class="form-group mb-2">
                                    <label for="guia">Guia</label>
                                    <input type="text" name="guia" id="guia" class="form-control" placeholder="Ingrese la guia" aria-describedby="guia" value="<?= $courierCurrent->guia ?>">
                                </div>
                            </div>
                            <div class="col-md-6 ">
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
                        <div class=" form-group my-2">
                            <label for="incoterm">IncoTerm:</label>
                            <select name="incoterm" id="incoterm" class="form-control" placeholder="Elija el Incoterm" aria-describedby="Mes" required style="width: 100%;">

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
                                    <small class="text-muted text-center">Ingresa la Fecha en formato 24h</small>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class=" form-group my-2">
                                    <label for="id_site">Site:</label>
                                    <select name="id_site" id="id_site" class="form-control" placeholder="Elija el Site" aria-describedby="site" required style="width: 100%;">

                                        <?php foreach ($sites as $key => $site) {
                                        ?>
                                            <option value="<?= $site->id_maken_site ?>"><?= $site->descripcion ?></option>
                                        <?php }  ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" form-group my-2">
                                    <label for="id_handling">Handling:</label>
                                    <select name="id_handling" id="id_handling" class="form-control" placeholder="Elija el Handling" aria-describedby="handling" required style="width: 100%;">

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
                                    <input class="form-check-input" type="checkbox" name="ind_transporte" value="" id="ind_transporte" checked>
                                    <label class="form-check-label" for="ind_transporte">
                                        Costo de Servicio de Transporte
                                    </label>
                                    <small id="helpId" class="text-muted">No marcar si el cliente paga el transporte</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="ind_servicio_aduana" checked>
                                    <label class="form-check-label" for="ind_servicio_aduana">
                                        Tarifa Servicio Aduana
                                    </label>
                                    <small id="helpId" class="text-muted">No marcar si el cliente o Broker pagala tarifa sel servicio de aduana</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="ind_costo_aduana" checked>
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

            <button type="submit" class="btn btn-success w-100 my-2" style="background-color: #98ddca; color: white; border-radius: 5px;"> <i class="fa fa-save mr-1"></i>Guardar</button>
        </form>
    </div>
</div>

<!-- modal de Exportador -->
<div class="modal" id="modalExportador" tabindex="-1" role="dialog" aria-labelledby="modalExportador" aria-hidden="true" style="margin-top: 100px; ">
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
                            <th scope="col">Direccion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exportadores as $key => $exportador) {
                        ?>
                            <tr>
                                <td class="d-flex justify-content-between" style="align-items: center !important;">
                                    <span><?= $exportador->nombre ?></span>
                                    <button type="button" class="btn exportador-btn" style="background: transparent;" data-id-exportador="<?= $exportador->id_exportador  ?>" data-nombre-exportador="<?= $exportador->nombre ?>"><i class="fas fa-check-circle fa-2x exportador-btn" style="color: #32CC52;" data-id-exportador="<?= $exportador->id_exportador  ?>" data-nombre-exportador="<?= $exportador->nombre ?>"></i></button>
                                </td>
                                <td><?= $exportador->direccion ?></td>
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
                            <th scope="col">Direccion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($importadores as $key => $importador) {
                        ?>
                            <tr>
                                <td class="d-flex justify-content-between" style="align-items: center !important;">
                                    <span><?= $importador->nombre ?></span>
                                    <button type="button" class="btn importador-btn" style="background: transparent;" data-id-importador="<?= $importador->id_importador  ?>" data-nombre-importador="<?= $importador->nombre ?>"><i class="fas fa-check-circle fa-2x importador-btn" style="color: #32CC52;" data-id-importador="<?= $importador->id_importador  ?>" data-nombre-importador="<?= $importador->nombre ?>"></i></button>
                                </td>
                                <td><?= $importador->direccion ?></td>
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
                        <input type="text" name="direccionNewExportador" id="direccionNewExportador" class="form-control" placeholder="Ingrese direccion" aria-describedby="direccionNewExportador" required maxlength="50">
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
                        <input type="text" name="direccionNewImportador" id="direccionNewImportador" class="form-control" placeholder="Ingrese direccion" aria-describedby="direccionNewImportador" required maxlength="50">
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



<script>
    $(document).ready(function() {
        <?php aldem_datatables_in_spanish(); ?>
        $('#table-exportadores-select').DataTable();
        $('#table-importadores-select').DataTable();

    });
    <?php

    if ($update) {
    ?>
        $('#incoterm').val('<?= $courierCurrent->id_incoterm ?>');
    <?php        }
    ?>
    $('#incoterm').select2();


    $('#paisNewImportador').val('604');
    $('#paisNewExportador').val('604');
    $('#paisNewImportador').select2();
    $('#paisNewExportador').select2();
    $('#id_site').select2();
    $('#id_handling').select2();
    (() => {
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

        const getMarkenShippersAsync = async (id_tipo = 2, id_table) => {
            let tipo = id_tipo == 2 ? "exportador" : "importador";
            let myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
            myHeaders.append("Content-Type", "application/json");
            let raw = JSON.stringify({
                "id_tipo": id_tipo,
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
                            <th scope="col">Direccion</th>
                        </tr>
                    </thead>
                    <tbody>`;
                response.data.forEach(shipper => {
                    htmlTemplate += `

                    <tr>
                        <td class="d-flex justify-content-between" style="align-items: center !important;">
                            <span>${shipper.nombre}</span>
                            <button type="button" class="btn ${tipo}-btn" style="background: transparent;" data-id-${tipo}="${id_tipo==2 ? shipper.id_exportador : shipper.id_importador}" data-nombre-${tipo}="${shipper.nombre}"><i class="fas fa-check-circle fa-2x ${tipo}-btn" style="color: #32CC52;" data-id-${tipo}="${id_tipo==2 ? shipper.id_exportador : shipper.id_importador}"   data-nombre-${tipo}="${shipper.nombre}"></i></button>
                        </td>
                        <td>${shipper.direccion}</td>
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
        const saveMarkenShipperAsync = async (exportador = false) => {
            showSpinnerCargando();
            let tipo = exportador ? "Exportador" : "Importador";
            let id_tipo = exportador ? 2 : 3;
            let myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
            myHeaders.append("Content-Type", "application/json");
            let raw = JSON.stringify({
                "nombre": document.querySelector(`#nombreNew${tipo}`).value,
                "direccion": document.querySelector(`#direccionNew${tipo}`).value,
                "id_pais": document.querySelector(`#paisNew${tipo}`).value,
                "id_tipo": id_tipo,
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
                    title: "Se Ha Creado Nuevo " + tipo,
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
                await getMarkenShippersAsync(id_tipo, id_tipo == 2 ? "#table-exportadores-select" : "#table-importadores-select");
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

        // function formatearTargetaBanco(string) {
        //     var cleaned = ("" + string).replace(/\D/g, '').replace("-", "");
        //     if (cleaned != "") {
        //         if (cleaned.length > 10) {
        //             cleaned = cleaned.substring(0, 10);
        //             // console.log(cleaned.length);
        //         } else if (cleaned.length < 10) {
        //             cleaned = cleaned.padEnd(10);
        //             // console.log("mi tamaño es", cleaned.length);
        //         }
        //         return cleaned.substring(0, 3) + "-" + cleaned.substring(3, 6) + "-" + cleaned.substring(6, 10)
        //     } else {
        //         return "";
        //     }
        // }
        // document.querySelector("#master-text").addEventListener("keyup", (e) => {

        //     setTimeout(() => {
        //         e.target.value = formatearTargetaBanco(e.target.value);
        //         document.querySelector("#master").value = formatearTargetaBanco(e.target.value).replace(/\D/g, '').replace("-", "");
        //     }, 500);
        // })
        document.querySelector("#formNewExportador").addEventListener("submit", async (e) => {
            e.preventDefault();
            document.querySelector("#btnCloseModalExportador").click();
            try {
                await saveMarkenShipperAsync(true);
                e.target.reset();
            } catch (error) {
                console.error(error);
            }
        })
        document.querySelector("#formNewImportador").addEventListener("submit", async (e) => {
            e.preventDefault();
            document.querySelector("#btnCloseModalImportador").click();
            try {
                await saveMarkenShipperAsync(false);
                e.target.reset();
            } catch (error) {
                console.error(error);
            }
        })
        // seleccionador del modal

        document.addEventListener("click", (e) => {
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
        })
        <?php
        if ($update) {
        ?>
            // $('#master-text').val(formatearTargetaBanco($('#master-text').val()));
        <?php        }
        ?>

    })()
</script>
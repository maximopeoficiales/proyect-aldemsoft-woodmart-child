<?php


// librerias datetime
// wp_enqueue_script("AldemflatPickrJS", "https://cdn.jsdelivr.net/npm/flatpickr", '', '1.0.0');
// wp_enqueue_style("AldemflatPickrDarkCSS", "https://npmcdn.com/flatpickr/dist/themes/dark.css", '', '1.0.0');


$update = $_GET["editjob"] != null || $_GET["editjob"] != "" ? true : false;
$id_courier_job = $update ? $_GET["editjob"] : null;
$subSubTipos = query_getSubsubTipo();
$markenSites = query_getMarkenSites(null, 4);
$transportes = query_getMarkenTransporte();
$ubigeos = query_getUbigeo(604);

$countrys =  query_getCountrys();
$remitentes = query_getRemitentes();
$consignatarios = query_getConsignatorios();

$pickupCurrent = $update ? query_getPickupJobs($id_courier_job)[0] : null;

// update
$remitenteCurrent = $update ?  query_getRemitentes($pickupCurrent->id_importador)[0] : null;
$consignatorioCurrent = $update ?  query_getConsignatorios($pickupCurrent->id_exportador)[0] : null;

$uriMarkenShipper = get_site_url() . "/wp-json/aldem/v1/marken_shipper/" . aldem_getUserNameCurrent();
$uriGETMarkenShipper = get_site_url() . "/wp-json/aldem/v1/getMarkenShippers/" . aldem_getUserNameCurrent();
$uriGETMarkenShipperId = get_site_url() . "/wp-json/aldem/v1/getMarkenShipper/" . aldem_getUserNameCurrent();
$urlVerifyWaybill = get_site_url() . "/wp-json/aldem/v1/existsWaybill/" . aldem_getUserNameCurrent();
?>

<?php if ($update && !aldem_isUserCreated($pickupCurrent->id_usuario_created)) {
    aldem_noAccess();
    return;
} ?>
<?php
aldem_cargarStyles();
aldem_show_message_custom("Se ha registrado correctamente el Job 😀", "Se ha actualizado correctamente el Job😀", "Ocurrio un error 😢 en el registro del Job");
?>

<div class="row justify-content-center p-4">
    <div pcs="col-md-8" style="width: 100%;">
        <form action="<?php echo admin_url('admin-post.php') ?>" method="post" id="form_pickup_nuevo">
            <div class="row mt-2">
                <div class="col-md-6 ">
                    <div class="form-group mb-2">
                        <label for="id_cliente_subsubtipo">Tipo</label>
                        <select name="id_cliente_subsubtipo" id="id_cliente_subsubtipo" class="form-control" aria-describedby="Selecciona un Tipo" style="width: 100%;">
                            <option value="">Selecciona un Tipo</option>
                            <?php foreach ($subSubTipos as $key => $subsubtipo) {
                            ?>
                                <option value="<?= $subsubtipo->id_cliente_subsubtipo ?>"><?= $subsubtipo->descripcion ?></option>
                            <?php }  ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group mb-2">
                        <label for="schd_collection">Fecha Servicio:</label>
                        <input type="date" name="schd_collection" id="schd_collection" class="form-control" placeholder="Ingrese la Fecha de Servicio" aria-describedby="Fecha Servicio" value="<?= $pickupCurrent->schd_collection ?>">
                    </div>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-md-6 ">
                    <div class="form-group mb-2">
                        <label for="waybill">Guia</label>
                        <input type="text" name="waybill" id="waybill" class="form-control" required placeholder="Ingrese la Guia" aria-describedby="waybill" maxlength="25" value="<?= $pickupCurrent->waybill ?>" <?= $update ? " disabled" : "" ?>>
                        <?php if ($update) {
                        ?>
                            <input type="hidden" name="waybill" value="<?= $pickupCurrent->waybill ?>">
                        <?php } ?>
                    </div>
                </div>

                <div class="col-md-6 ">
                    <div class="form-group mb-2">
                        <label for="protocolo">Protocolo:</label>
                        <input type="text" name="protocolo" id="protocolo" class="form-control" placeholder="Ingrese el Protocolo" maxlength="50" aria-describedby="Protocolo" value="<?= $pickupCurrent->protocolo ?>">
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="pcs">Bultos</label>
                        <input type="number" name="pcs" id="pcs" class="form-control" min="0" placeholder="Ingrese los Bultos" aria-describedby="bultos" value="<?= $pickupCurrent->pcs ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="peso">Peso: </label>
                        <input type="number" name="peso" id="peso" class="form-control" placeholder="Ingrese el Peso" aria-describedby="peso" step="0.01" value="<?= $pickupCurrent->peso ?>">
                    </div>
                </div>
            </div>
            <!-- importadores - exportandores -->
            <div class="row mt-2">
                <div class="col-md-12">
                    <label for="importador-text">Remitente</label>
                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Elija un Remitente button" disabled id="importador-text" placeholder="Elija un Remitente" value="<?= $remitenteCurrent->nombre ?>">
                        <div class="input-group-append">
                            <button class="btn  bg-aldem-secondary dropdown-toggle " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Elije una opcion</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalImportador">Seleccionar Remitente</a>

                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNewImportador">Nuevo Remitente</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="exportador-text">Consignatorio</label>
                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Elija un Consignatorio" disabled id="exportador-text" placeholder="Elija un Consignatorio" value="<?= $consignatorioCurrent->nombre ?>">
                        <div class="input-group-append">
                            <button class="btn bg-aldem-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Elije una opcion</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalExportador">Seleccionar Consignatorio</a>

                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNewExportador">Nuevo Consignatorio</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 ">
                    <div class="form-group mb-2">
                        <label for="id_ubigeo">Origen Destino</label>
                        <select name="id_ubigeo" id="id_ubigeo" class="form-control" aria-describedby="Selecciona un Origen/Destino" style="width: 100%;">
                            <option value="">Selecciona un Origen/Destino</option>
                            <?php foreach ($ubigeos as $key => $ubigeo) {
                            ?>
                                <option value="<?= $ubigeo->id_ubigeo ?>"><?= $ubigeo->descripcion ?></option>
                            <?php }  ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group mb-2">
                        <label for="id_transporte">Tipo de Transporte</label>
                        <select name="id_transporte" id="id_transporte" class="form-control" aria-describedby="Selecciona Tipo de Transporte" style="width: 100%;">
                            <option value="">Selecciona Tipo de Transporte</option>
                            <?php foreach ($transportes as $key => $transporte) {
                            ?>
                                <option value="<?= $transporte->id_transporte ?>"><?= $transporte->descripcion ?></option>
                            <?php }  ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6 ">
                    <div class="form-group mb-2">
                        <label for="id_marken_site">Site</label>
                        <select name="id_marken_site" id="id_marken_site" class="form-control" aria-describedby="Selecciona un Site" style="width: 100%;">
                            <option value="">Selecciona un Site</option>
                            <?php foreach ($markenSites as $key => $markenSite) {
                            ?>
                                <option value="<?= $markenSite->id_marken_site ?>"><?= $markenSite->descripcion ?></option>
                            <?php }  ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group mb-2">
                        <label for="ind_facturado">Facturado</label>
                        <select name="ind_facturado" id="ind_facturado" class="form-control" aria-describedby="Selecciona una opcion" style="width: 100%;">
                            <option value="">Elija una opcion</option>
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="factura">Factura</label>
                        <input type="text" name="factura" id="factura" class="form-control" maxlength="25" placeholder="Ingrese la Factura" aria-describedby="factura" value="<?= $pickupCurrent->factura ?>">
                        <small id="" class="form-text text-muted">Solo para Depot</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="guia_master">Invoice Grupal</label>
                        <input type="text" name="guia_master" id="guia_master" class="form-control" maxlength="20" placeholder="Ingrese Invoice Grupal" aria-describedby="guia_master" value="<?= $pickupCurrent->guia_master ?>">
                        <small id="" class="form-text text-muted">Solo para SANOFI</small>
                    </div>
                </div>
            </div>


            <?php aldem_set_input_hidden("id_user", get_current_user_id()); ?>
            <?php if ($update) {
                aldem_set_input_hidden("id_exportador", $pickupCurrent->id_exportador);
                aldem_set_input_hidden("id_importador", $pickupCurrent->id_importador);
                aldem_set_input_hidden("id_courier_job", $id_courier_job);
                aldem_set_action_name("update-pickup");
            } else {
                aldem_set_input_hidden("id_exportador", "");
                aldem_set_input_hidden("id_importador", "");
                aldem_set_action_name("new-pickup");
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
                <h5 class="modal-title aldem-text-white">Elige un Consignatorio</h5>
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
                            <th scope="col">Direccion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consignatarios as $key => $exportador) {
                        ?>
                            <tr>
                                <td class="d-flex justify-content-between" style="align-items: center !important;">
                                    <span><?= $exportador->nombre ?></span>

                                    <div class="">
                                        <button type="button" class="btn edit-btn" style="background: transparent;" data-id="<?= $exportador->id_shipper  ?>" data-id-tipo="5">

                                            <i class="fas fa-edit fa-2x edit-btn" data-id="<?= $exportador->id_shipper  ?>" data-id-tipo="5" style="color: #17A2B8"></i>
                                        </button>

                                        <button type="button" class="btn exportador-btn" style="background: transparent;" data-id-exportador="<?= $exportador->id_consignatorio  ?>" data-nombre-exportador="<?= $exportador->nombre ?>"><i class="fas fa-check-circle fa-2x exportador-btn" style="color: #32CC52;" data-id-exportador="<?= $exportador->id_consignatorio  ?>" data-nombre-exportador="<?= $exportador->nombre ?>"></i></button>

                                    </div>
                                </td>
                                <td><?= $exportador->desc_pais ?></td>
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
                <h5 class="modal-title aldem-text-white">Elige un Remitente</h5>
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
                            <th scope="col">Direccion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($remitentes as $key => $importador) {
                        ?>
                            <tr>
                                <td class="d-flex justify-content-between" style="align-items: center !important;">
                                    <span><?= $importador->nombre ?></span>
                                    <div class="">
                                        <button type="button" class="btn edit-btn" style="background: transparent;" data-id="<?= $importador->id_shipper  ?>" data-id-tipo="4">

                                            <i class="fas fa-edit fa-2x edit-btn" data-id="<?= $importador->id_shipper  ?>" data-id-tipo="4" style="color: #17A2B8"></i>
                                        </button>

                                        <button type="button" class="btn importador-btn" style="background: transparent;" data-id-importador="<?= $importador->id_remitente  ?>" data-nombre-importador="<?= $importador->nombre ?>"><i class="fas fa-check-circle fa-2x importador-btn" style="color: #32CC52;" data-id-importador="<?= $importador->id_remitente  ?>" data-nombre-importador="<?= $importador->nombre ?>"></i></button>
                                    </div>

                                </td>
                                <td><?= $importador->desc_pais ?></td>
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
<!-- modales de creacion de Exportador -->
<div class="modal" id="modalNewExportador" tabindex="-1" role="dialog" aria-labelledby="modalNewExportador" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title aldem-text-white">Crear un Consignatorio</h5>
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

<!-- modales de creacion de Importador -->
<div class="modal" id="modalNewImportador" tabindex="-1" role="dialog" aria-labelledby="modalNewImportador" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title aldem-text-white">Crear un Remitente</h5>
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
    });
    <?php

    if ($update) {
    ?>
        $('#id_cliente_subsubtipo').val('<?= $pickupCurrent->id_cliente_subsubtipo ?>');
        $('#id_marken_site').val('<?= $pickupCurrent->id_site ?>');
        $('#ind_facturado').val('<?= $pickupCurrent->ind_facturado ?>');
        $('#id_ubigeo').val('<?= $pickupCurrent->id_ubigeo ?>');
        $('#id_transporte').val('<?= $pickupCurrent->id_transporte ?>');
    <?php        }
    ?>

    $('#id_cliente_subsubtipo').select2();
    $('#id_ubigeo').select2();
    $('#id_transporte').select2();
    $('#id_marken_site').select2();
    $('#ind_facturado').select2();

    $('#paisNewImportador').val('604');
    $('#paisNewExportador').val('604');
    $('#paisNewImportador').select2();
    $('#paisNewExportador').select2();
    $('#paisEdit').select2();
    (() => {
        const isPickup = true;
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
                    "waybill": document.querySelector("#waybill").value,
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
                await getMarkenShippersAsync(2, "#table-exportadores-select", 5);
                await getMarkenShippersAsync(3, "#table-importadores-select", 4);
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

        document.querySelector("#formNewExportador").addEventListener("submit", async (e) => {
            e.preventDefault();
            document.querySelector("#btnCloseModalExportador").click();
            try {
                // CONSIGNATORIO
                await saveMarkenShipperAsync(true, 5);
                e.target.reset();
            } catch (error) {
                console.error(error);
            }
        })
        document.querySelector("#formNewImportador").addEventListener("submit", async (e) => {
            e.preventDefault();
            document.querySelector("#btnCloseModalImportador").click();
            try {
                // remitente
                await saveMarkenShipperAsync(false, 4);
                e.target.reset();
            } catch (error) {
                console.error(error);
            }
        })
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
            await executeEventFormEditMarken(e);
        })


        document.querySelector("#form_pickup_nuevo").addEventListener("submit", async (e) => {
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

        })

        eventSubmitFormEditMarken();
    })()
</script>
<?php
$shippers = (object) query_getShippers();
$countrys = query_getCountrys();
$markenTypes = query_getMarkenTypes();
$markenCajas = query_getMarkenCajas();

?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">

                <form action="<?php echo admin_url('admin-post.php') ?>" method="post">

                    <div class="row mt-2">

                        <div class="col-md-12">
                            <div class="form-group mb-2" style="width: 100%;">
                                <label for="waybill">Waybill: </label>
                                <input type="text" name="waybill" id="waybill" class="form-control" placeholder="Ingrese el Waybill" aria-describedby="waybill" required maxlength="35">
                            </div>
                            <div class="form-group mb-2">
                                <label for="desc_shipper">Shipper:</label>
                                <div class="input-group ">
                                    <input type="text" required class="form-control" name="desc_shipper" id="desc_shipper" placeholder="Selecciona un Shipper" aria-label="Selecciona un Shipper" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalShipper">Seleccionar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="desc_contacto">Contacto:</label>
                                <div class="input-group mb-3">
                                    <input type="text" required class="form-control" name="desc_contacto" id="desc_contacto" placeholder="Selecciona un Contacto" aria-label="Selecciona un Contacto" aria-describedby="Selecciona un Contacto">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalContact">Seleccionar</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h5>Consignatario</h5>
                            <div class="pl-2">
                                <div class="form-group mb-2">
                                    <label for="consigge_nombre">Nombre: </label>
                                    <input type="text" name="consigge_nombre" id="consigge_nombre" class="form-control" placeholder="Ingrese el Nombre" aria-describedby="nombre" minlength="1" maxlength="150">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="consigge_direccion">Direccion: </label>
                                    <input type="text" name="consigge_direccion" id="consigge_direccion" class="form-control" placeholder="Ingrese la Direccion" aria-describedby="consigge_direccion" minlength="1" maxlength="150">
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
                                <div class="form-group mb-2">
                                    <label for="consigge_reference">Referencia: </label>
                                    <input type="text" name="consigge_reference" id="consigge_reference" class="form-control" placeholder="Ingrese la Referencia" aria-describedby="consigge_reference" minlength="1" maxlength="150">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group mt-2">
                        <label for="content">Descripcion del Contenido: </label>
                        <textarea name="content" id="content" class="form-control" placeholder="Ingrese la Descripcion del Contenido" aria-describedby="content" required minlength="1" maxlength="250" style="min-height: 140px;"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="pcs">PCs: </label>
                                <input type="number" name="pcs" id="pcs" class="form-control" placeholder="Ingrese el numero de Pcs" required aria-describedby="pcs" min="1">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="range">Rango: </label>
                                <input type="text" name="range" id="range" class="form-control" placeholder="Ingrese el Rango" aria-describedby="range" minlength="1" maxlength="25">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="id_marken_type">Tipo: </label>
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
                                <textarea name="instrucciones" id="instrucciones" class="form-control" placeholder="Ingrese las Instrucciones" aria-describedby="instrucciones" required minlength="1" maxlength="500" style="min-height: 140px;" required></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 mt-1">
                            <div class="form-group mb-2">
                                <label for="fecha">Fecha de Recoleccion: </label>
                                <input type="date" name="fecha" id="fecha" class="form-control" placeholder="Ingrese el numero de fecha" required aria-describedby="fecha">
                            </div>
                        </div>
                        <div class="col-md-6 mt-1">

                            <div class="form-group mb-2">
                                <label for="hora">Hora de Recoleccion: </label>
                                <input type="time" name="hora" id="hora" class="form-control" placeholder="Ingrese el numero de hora" required aria-describedby="hora">
                            </div>
                        </div>
                    </div>
                    <?php aldem_set_proccess_form(); ?>
                    <?php aldem_set_input_hidden("user_id", get_current_user_id()); ?>
                    <?php aldem_set_input_hidden("id_shipper", ""); ?>
                    <?php aldem_set_input_hidden("id_contact", ""); ?>
                    <?php aldem_set_action_name("new-export"); ?>
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

<!-- modal contact -->
<div class="modal" id="modalContact" tabindex="-1" role="dialog" aria-labelledby="modalContact" aria-hidden="true" style="margin-top: 100px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Elige un Contacto</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseListContacts">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                contactos
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-capitalize" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>
<script>
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
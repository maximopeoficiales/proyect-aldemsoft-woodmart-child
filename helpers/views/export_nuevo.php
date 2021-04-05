<?php
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
                                        <button class="btn btn-primary" type="button">Seleccionar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="desc_contacto">Contacto:</label>
                                <div class="input-group mb-3">
                                    <input type="text" required class="form-control" name="desc_contacto" id="desc_contacto" placeholder="Selecciona un Contacto" aria-label="Selecciona un Contacto" aria-describedby="Selecciona un Contacto">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">Seleccionar</button>
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
                                    <select name="id_pais" id="id_pais">
                                        <option value="1">Peru</option>
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
                                <select name="id_marken_type" id="id_marken_type" required>
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
                                <select name="id_caja" id="id_caja" required>

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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="fecha">Fecha de Recoleccion: </label>
                                <input type="date" name="fecha" id="fecha" class="form-control" placeholder="Ingrese el numero de fecha" required aria-describedby="fecha">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group mb-2">
                                <label for="hora">Hora de Recoleccion: </label>
                                <input type="time" name="hora" id="hora" class="form-control" placeholder="Ingrese el numero de hora" required aria-describedby="hora">
                            </div>
                        </div>
                    </div>
                    <?php aldem_set_proccess_form(); ?>
                    <?php aldem_set_input_hidden("user_id", get_current_user_id()); ?>
                    <?php aldem_set_action_name("new-export"); ?>
                    <button type="submit" class="btn btn-success w-100"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // $(function() {
    //     $("#fecha_hora").datepicker();
    // });
</script>
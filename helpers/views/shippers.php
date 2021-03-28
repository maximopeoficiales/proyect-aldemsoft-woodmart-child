<?php

$shippers = (object) query_getShippers();
$countrys = (object) query_getCountrys();
$markenSites = (object) query_getMarkenSite();
$urlUbigeos = get_site_url() . "/wp-json/aldem/v1/ubigeos";
?>
<?php
aldem_show_message_custom("Se ha registrado correctamente el shipper 😀", "Se ha actualizado correctamente el shipper 😀", "Ocurrio un error 😢 en el registro del shipper")
?>



<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <h5 class="card-header">Shippers</h5>
            <div class="card-body">
                <h5 class="card-title text-center">Lista de Shippers</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <table class="table table-striped table-bordered dt-responsive nowrap" id="table-shippers" style="width: 100%;">
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
                                    <button type="button" class="btn" data-toggle="modal" data-target="#exampleModal<?= $key + 1 ?>" onclick="$('.select-countrys-<?= $key + 1  ?>').select2();"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                </td>
                                <td><?= $shipper->direccion ?></td>
                                <td><?= $shipper->site ?></td>

                            </tr>
                        <?php }  ?>
                    </tbody>
                </table>
                <!-- modal -->
                <?php foreach ($shippers as $key1 => $ship) {
                    $disabledGlobal = shipper_isUserCreator($ship->id_usuario_created) == true ? "" : "disabled";
                ?>
                    <div class="modal" id="exampleModal<?= $key1 + 1  ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top: 100px;">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="<?php echo admin_url('admin-post.php') ?>" method="post">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title" id="exampleModalLabel">Detalle del Shipper</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="nombreShipper">Nombre: </label>
                                            <input type="text" name="nombreShipper" id="nombreShipper" class="form-control" placeholder="Ingrese su nombre" aria-describedby="helpId" value="<?= $ship->nombre ?>" <?= $disabledGlobal ?>>
                                        </div>
                                        <div class="form-group">
                                            <label for="direccionShipper">Direccion:</label>
                                            <input type="text" name="direccionShipper" id="direccionShipper" class="form-control" placeholder="Ingrese su direccion" aria-describedby="helpId" value="<?= $ship->direccion ?>" <?= $disabledGlobal ?>>
                                        </div>
                                        <div class="form-group">
                                            <label for="direccion2Shipper">Direccion2:</label>
                                            <input type="text" name="direccion2Shipper" id="direccion2Shipper" class="form-control" placeholder="Ingrese su direccion2" aria-describedby="helpId" value="<?= $ship->direccion2 ?>" <?= $disabledGlobal ?>>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="zipShipper">Zip:</label>
                                                    <input type="number" name="zipShipper" id="zipShipper" class="form-control" placeholder="Ingrese su zip" aria-describedby="helpId" min="0" value="<?= $ship->zip ?>" <?= $disabledGlobal ?>>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="paisShipper" style="display: block;">Pais:</label>
                                                    <select class="form-control select-countrys-<?= $key1 + 1 ?>" name="paisShipper" id="paisShipper" style="width: 100% !important;" <?= $disabledGlobal ?>>
                                                        <?php
                                                        foreach ($countrys as $kq => $country) {
                                                        ?>
                                                            <option value="<?= $country->id_pais ?>" <?= $ship->id_country ===  $country->id_pais ? " selected" : "" ?>><?= $country->desc_pais ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="shiteShipper">Site:</label>
                                                    <select class="form-control" name="siteShipper" id="shiteShipper" <?= $disabledGlobal ?>>
                                                        <?php foreach ($markenSites as $markenSite) { ?>

                                                            <option value="<?= $markenSite->id_marken_site ?>" <?= $ship->site ===  $markenSite->descripcion ? "  selected" : "" ?>
                                                            >
                                                                <?= $markenSite->descripcion ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ">
                                                    <label for="ubigeoShipper">Ubigeo:</label>
                                                    <select class="form-control"" name=" ubigeoShipper" id="ubigeoShipper-<?= $key1 + 1 ?>" <?= $disabledGlobal ?>>
                                                        <option selected value="<?= $ship->id_ubigeo ?>"><?= query_getUbigeo(null, $ship->id_ubigeo)[0]->descripcion ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-2">
                                            <strong>Creador por: </strong> <?= query_getNameComplete($ship->id_usuario_created)->name ?>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="action" value="process_form">
                                        <input type="hidden" name="id_shipper" value="<?= $ship->id_shipper ?>">
                                        <?php
                                        shipper_isUserCreator($ship->id_usuario_created) ? aldem_set_action_name("edit-shipper") : "";
                                        ?>
                                        <input type="hidden" name="id_user" value="<?= get_current_user_id() ?>">
                                        <button type="button" class="btn btn-danger text-capitalize" data-dismiss="modal">Salir</button>

                                        <?php if (shipper_isUserCreator($ship->id_usuario_created)) {
                                            echo ' <button type="submit" class="btn btn-success text-capitalize">
                                            <i class="fa fa-save mr-1"></i> Editar</button>';
                                        }
                                        ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="text-center">
                    <button class="btn" data-toggle="modal" data-target="#modal-newShipper" onclick="$('.new-select-countrys').select2();">Nuevo shipper</button>
                </div>

                <!-- new shipper -->
                <div class="modal" id="modal-newShipper" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top: 100px;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="<?php echo admin_url('admin-post.php') ?>" method="post">

                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Shipper</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="nombreShipper">Nombre: </label>
                                        <input type="text" name="nombreShipper" id="nombreShipper" class="form-control" placeholder="Ingrese su nombre" aria-describedby="helpId">
                                    </div>
                                    <div class="form-group">
                                        <label for="direccionShipper">Direccion:</label>
                                        <input type="text" name="direccionShipper" id="direccionShipper" class="form-control" placeholder="Ingrese su direccion" aria-describedby="helpId">
                                    </div>
                                    <div class="form-group">
                                        <label for="direccion2Shipper">Direccion2:</label>
                                        <input type="text" name="direccion2Shipper" id="direccion2Shipper" class="form-control" placeholder="Ingrese su direccion2" aria-describedby="helpId">
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="zipShipper">Zip:</label>
                                                <input type="number" name="zipShipper" id="zipShipper" class="form-control" placeholder="Ingrese su zip" aria-describedby="helpId" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="paisShipper" style="display: block;">Pais:</label>
                                                <select class="form-control new-select-countrys" name="paisShipper" id="newshipper-paisShipper" style="width: 100% !important;">
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

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="shiteShipper">Site:</label>
                                                <select class="form-control" name="siteShipper" id="shiteShipper">
                                                    <?php foreach ($markenSites as $markenSite) { ?>
                                                        <option value="<?= $markenSite->id_marken_site ?>">
                                                            <?= $markenSite->descripcion ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="ubigeoShipper">Ubigeo:</label>
                                                <select class="form-control" name="ubigeoShipper" id="newshipper-ubigeoShipper">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="action" value="process_form">
                                    <?php aldem_set_action_name("new-shipper"); ?>
                                    <input type="hidden" name="id_user" value="<?= get_current_user_id() ?>">
                                    <button type="button" class="btn btn-danger text-capitalize" data-dismiss="modal">Salir</button>
                                    <button type="submit" class="btn btn-success text-capitalize">
                                        <i class="fa fa-save mr-1"></i> Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const getUbigeos = async (id_country = 0) => {
            try {
                let myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");
                let raw = JSON.stringify({
                    "id_country": id_country,
                });

                let requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow'
                };
                return await (await (fetch('<?= $urlUbigeos ?>', requestOptions))).json();
            } catch (error) {
                return null;
            }
        }
        const setUbigeosAsync = async (id_country = 0, name_select = "") => {
            let ubigeos = await getUbigeos(id_country);
            let selectUbigeo = document.querySelector(name_select);
            // si no tiene error
            if (!ubigeos.data) {
                // cargar los ubigeos en el select
                selectUbigeo.innerHTML = "";
                let htmlTemporal = "";
                ubigeos.forEach(ubigeo => {
                    htmlTemporal += `<option value="${ubigeo.id_ubigeo}">${ubigeo.descripcion}</option>`
                });
                selectUbigeo.innerHTML = htmlTemporal;
                $(name_select).select2();
            } else {
                selectUbigeo.innerHTML = `<option value="">No tiene Ubigeos Registrados</option>`;
            }
        }
        $(document).ready(function() {
            $('#table-shippers').DataTable();

        });

        $('#newshipper-paisShipper').on('select2:select', async function(e) {
            let id_country = (e.params.data.id);
            await setUbigeosAsync(id_country, "#newshipper-ubigeoShipper");

        });

        <?php
        foreach ($shippers as $key4 => $value) {
        ?>
            $('.select-countrys-<?= $key4 + 1 ?>').on('select2:select', async function(e) {
                let id_country = (e.params.data.id);
                console.log(id_country);
                await setUbigeosAsync(id_country, "#ubigeoShipper-<?= $key4 + 1 ?>");

            });
        <?php } ?>

    })()
</script>
<?php
date_default_timezone_set('America/Lima');
wp_enqueue_script("intTelinputJS", "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js", '', '1.0.0');

wp_enqueue_style("intTelinputCSS", "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css", '', '1.0.0');

$shippers = (object) query_getShippers();
$countrys = (object) query_getCountrys();
$markenTypes = (object) query_getMarkenTypes();
$markenCajas = (object) query_getMarkenCajas();

$markenSites = (object) query_getMarkenSite();
$ubigeosPeru = (object) query_getUbigeo(604);
// para los API REST
$urlUbigeos = get_site_url() . "/wp-json/aldem/v1/ubigeos/" . aldem_getUserNameCurrent();
$urlShippers = get_site_url() . "/wp-json/aldem/v1/shippers/" . aldem_getUserNameCurrent();

// para el update
$update = $_GET["editjob"] != null || $_GET["editjob"] != "" ? true : false;
$id_marken_job = $update ? $_GET["editjob"] : null;
$markenJob = $update ? (object) query_getMarkenJobs($id_marken_job)[0] : null;
$shipperCurrent = $update ? (object) query_getShippers($markenJob->id_shipper)[0] : null;
$consiggneCurrent = $update ? (object) query_getMarkenConsiggne(null, $markenJob->id)[0] : null;
$fechaJob = $update ? explode(" ", $markenJob->fecha_hora)[0] : null;
$horaJob = $update ? substr(explode(" ", $markenJob->fecha_hora)[1], 0, -3) : null;
?>

<?php if ($update && !aldem_isUserCreated($markenJob->id_usuario_created)) {
    aldem_noAccess();
    return;
} ?>

<?php
aldem_cargarStyles();
aldem_show_message_custom("Se ha registrado correctamente el Job 😀", "Se ha actualizado correctamente el Job😀", "Ocurrio un error 😢 en el registro del Job");
?>


<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">

                <form action="<?php echo admin_url('admin-post.php') ?>" method="post" id="formNewExport">

                    <div class="row mt-2">

                        <div class="col-md-12">
                            <div class="form-group mb-2" style="width: 100%;">
                                <label for="waybill">Waybill: </label>
                                <input type="text" name="waybill" id="waybill" class="form-control" placeholder="Ingrese el Waybill" aria-describedby="waybill" required maxlength="35" value="<?= $markenJob->waybill ?>">
                            </div>

                            <label for="exportador-text">Shipper:</label>
                            <div class="input-group my-2">
                                <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled id="desc_shipper" placeholder="Elija un Shipper" value="<?= $shipperCurrent->nombre ?>">
                                <div class="input-group-append">
                                    <button class="btn bg-aldem-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Elije una opcion</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalShipper">Seleccionar Shipper</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNewShipper">Crear Shipper</a>
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
                                <label for="contacto_telf_text">Contact Telephone: </label>
                                <input type="tel" name="contacto_telf_text" id="contacto_telf_text" class="form-control" placeholder="Ingrese el telefono del contacto" aria-describedby="contacto_telf_text" maxlength="50" value="<?= $markenJob->contact_telephone ?>" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="reference">Reference: </label>
                        <input type="text" name="reference" id="reference" class="form-control" placeholder="Ingrese la Referencia" aria-describedby="reference" maxlength="150"  value="<?= $markenJob->reference ?>">
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
                                <textarea name="instrucciones" id="instrucciones" class="form-control" placeholder="Ingrese las Instrucciones" aria-describedby="instrucciones" maxlength="500" style="min-height: 140px;" value=""><?= $markenJob->instrucciones ?></textarea>
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
                        aldem_set_input_hidden("contacto_telf",                         $markenJob->contact_telephone);
                        aldem_set_input_hidden("id_marken_job", $markenJob->id);
                        aldem_set_input_hidden("id_marken_consiggne", $consiggneCurrent->id);
                    } else {
                        aldem_set_input_hidden("id_shipper", "", false);
                        aldem_set_input_hidden("contacto_telf", "", false);
                        aldem_set_action_name("new-job");
                    } ?>
                    <button type="submit" class="btn  w-100 btn-aldem-verde"> <i class="fa fa-save mr-1"></i>Guardar</button>
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
            <div class="modal-body" id="modal-body-shippers">
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
                                    <button type="button" class="btn shipper-btn" style="background: transparent;" data-id-shipper="<?= $shipper->id_shipper  ?>" data-nombre-shipper="<?= $shipper->nombre ?>"><i class="fas fa-check-circle fa-2x shipper-btn" style="color: #32CC52;" data-id-shipper="<?= $shipper->id_shipper  ?>" data-nombre-shipper="<?= $shipper->nombre ?>"></i></button>
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


<!-- modal de creacion de shipper -->
<div class="modal" id="modalNewShipper" tabindex="-1" role="dialog" aria-labelledby="modalNewShipper" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Crea un Shipper</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseNewShippers">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="formNewShipper">
                    <div class="form-group">
                        <label for="nombreShipper">Nombre: </label>
                        <input type="text" name="nombreShipper" id="nombreShipper" class="form-control" placeholder="Ingrese su nombre" aria-describedby="helpId" required>
                    </div>
                    <div class="form-group">
                        <label for="direccionShipper">Direccion:</label>
                        <input type="text" name="direccionShipper" id="direccionShipper" class="form-control" placeholder="Ingrese su direccion" aria-describedby="helpId" required>
                    </div>

                    <div class="row mt-2">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="paisShipper" style="display: block;">Pais:</label>
                                <select class="form-control select-countrys" name="paisShipper" id="paisShipper" style="width: 100% !important;">
                                    <?php
                                    foreach ($countrys as $kq => $country) {
                                    ?>
                                        <option value="<?= $country->id_pais ?>">
                                            <?= $country->desc_pais ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="shiteShipper">Site:</label>
                                <select class="form-control" name="siteShipper" id="siteShipper">
                                    <?php foreach ($markenSites as $markenSite) { ?>

                                        <option value="<?= $markenSite->id_marken_site ?>">
                                            <?= $markenSite->descripcion ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label for="ubigeoShipper">Ubigeo:</label>
                                <select class="form-control"" name=" ubigeoShipper" id="ubigeoShipper" style="width:100%">
                                    <?php foreach ($ubigeosPeru as
                                        $ubigeo) { ?>
                                        <option value="<?= $ubigeo->id_ubigeo ?>">
                                            <?= $ubigeo->descripcion ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php aldem_set_input_hidden("id_user_new", get_current_user_id()) ?>
                    <button type="submit" class="my-2 btn w-100 btn-aldem-verde"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </form>
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
    $('#paisShipper').val('604');
    $('#id_pais').select2();
    $('#id_caja').select2();
    $('#id_marken_type').select2();
    $('#paisShipper').select2();
    $('#ubigeoShipper').select2();

    $(document).ready(function() {
        <?php aldem_datatables_in_spanish(); ?>
        $('#table-shippers-select').DataTable();
        const phoneInputField = document.querySelector("#contacto_telf_text");
        const phoneInput = window.intlTelInput(phoneInputField, {
            preferredCountries: ["pe"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
        document.querySelector("#formNewExport").addEventListener("submit", (e) => {
            e.preventDefault();
            document.querySelector("#contacto_telf").value = phoneInput.getNumber();
            e.target.submit();
        })
    });

    // listeners
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
        const getUbigeos = async (id_country = 0) => {
            try {
                let myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");
                myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
                let raw = JSON.stringify({
                    "id_country": id_country,
                });

                let requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow'
                };
                return await (await (fetch('<?= $urlUbigeos  ?>', requestOptions))).json();
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
        const getShippersAsync = async () => {
            try {
                let myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");
                myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
                let requestOptions = {
                    method: 'GET',
                    headers: myHeaders,
                    redirect: 'follow'
                };
                return await (await (fetch('<?= $urlShippers  ?>', requestOptions))).json();
            } catch (error) {
                return null;
            }
        }
        const cargarTablaShipperAsync = async (id_table) => {
            let response = await getShippersAsync();
            if (response.status == 200) {
                // let tabladiv = document.querySelector(id_table + "_wrapper");
                let modalBody = document.querySelector("#modal-body-shippers");
                modalBody.innerHTML = "";
                let htmlTemplate = `
                <table class="table table-striped table-bordered dt-responsive nowrap" id="table-shippers-select" style="width: 100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Direccion</th>
                            <th scope="col">Site</th>
                        </tr>
                    </thead>
                    <tbody>`;
                response.data.forEach(shipper => {
                    htmlTemplate += `
                    <tr>
                    <td class="d-flex justify-content-between" style="align-items: center !important;">
                        <span>${shipper.nombre}</span>
                        <button type="button" class="btn shipper-btn" style="background: transparent;" data-id-shipper="${shipper.id_shipper}" data-nombre-shipper="${shipper.nombre}"><i class="fas fa-check-circle fa-2x shipper-btn" style="color: #32CC52;" data-id-shipper="${shipper.id_shipper}" data-nombre-shipper="${shipper.nombre}"></i></button>
                    </td>
                    <td>${shipper.direccion}</td>
                    <td>${shipper.site}</td>
                    </tr>
                    `;
                });
                htmlTemplate += `
                </tbody>
                <table>
                `;
                modalBody.innerHTML = htmlTemplate;
                $(id_table).DataTable();
            }
        }
        const postShipperAsync = async () => {
            showSpinnerCargando();
            let myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
            myHeaders.append("Content-Type", "application/json");
            let raw = JSON.stringify({
                "nombreShipper": document.querySelector("#nombreShipper").value,
                "direccionShipper": document.querySelector("#direccionShipper").value,
                "paisShipper": document.querySelector("#paisShipper").value,
                "siteShipper": document.querySelector("#siteShipper").value,
                "ubigeoShipper": document.querySelector("#ubigeoShipper").value,
                "id_user": <?= get_current_user_id() ?>
            });

            let requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };
            let response = await (await fetch("<?= $urlShippers ?>", requestOptions)).json();
            closeSpinnerCargando();
            if (response.status == 200) {
                await cargarTablaShipperAsync("#table-shippers-select");
                Swal.fire({
                    icon: "success",
                    title: `${response.message}`,
                    showConfirmButton: false,
                    timer: 1500
                })
                document.querySelector("#id_shipper").value = response.data.id_marken_shipper;
                document.querySelector("#desc_shipper").value = response.data.descripcion;
            } else if (response.status == 404) {
                Swal.fire({
                    icon: 'error',
                    title: `${response.message}`,
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

        $('#paisShipper').on('select2:select', async function(e) {
            let id_country = (e.params.data.id);
            await setUbigeosAsync(id_country, "#ubigeoShipper");
        });

        document.addEventListener("click", (e) => {
            if (e.target.classList.value.includes("shipper-btn")) {
                let idShipper = e.target.getAttribute("data-id-shipper");
                let nombreShipper = e.target.getAttribute("data-nombre-shipper");
                document.querySelector("#id_shipper").value = idShipper;
                document.querySelector("#desc_shipper").value = nombreShipper;
                document.querySelector("#btnCloseListShippers").click();
            }
        })
        document.querySelector("#formNewShipper").addEventListener("submit", async (e) => {
            e.preventDefault();
            document.querySelector("#btnCloseNewShippers").click();
            try {
                await postShipperAsync();
                e.target.reset();
            } catch (error) {
                console.error(error);
            }
        })

    })()
</script>
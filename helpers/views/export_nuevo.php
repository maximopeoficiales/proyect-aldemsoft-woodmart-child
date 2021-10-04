<?php
date_default_timezone_set('America/Lima');
// librerias datetime
wp_enqueue_script("AldemflatPickrJS", "https://cdn.jsdelivr.net/npm/flatpickr", '', '1.0.0');

// wp_enqueue_style("AldemflatPickrCSS", "https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css", '', '1.0.0');
wp_enqueue_style("AldemflatPickrDarkCSS", "https://npmcdn.com/flatpickr/dist/themes/dark.css", '', '1.0.0');



wp_enqueue_script("intTelinputJS", "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js", '', '1.0.0');

wp_enqueue_style("intTelinputCSS", "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css", '', '1.0.0');

// $shippers = (object) query_getShippers();
// $countrys = (object) query_getCountrys();
$markenTypes = (object) query_getMarkenTypes();
$markenCajas = (object) query_getMarkenCajas();

// $markenSites = (object) query_getMarkenSite();
// $ubigeosPeru = (object) query_getUbigeo(604);
// para los API REST
$urlUbigeos = get_site_url() . "/wp-json/aldem/v1/ubigeos/" . aldem_getUserNameCurrent();
$urlShippers = get_site_url() . "/wp-json/aldem/v1/shippers/" . aldem_getUserNameCurrent();
$urlShippersByID = get_site_url() . "/wp-json/aldem/v1/shippersByID/" . aldem_getUserNameCurrent();
$urlVerifyWaybill = get_site_url() . "/wp-json/aldem/v1/existsWaybill/" . aldem_getUserNameCurrent();

// para el update
$update = $_GET["editjob"] != null || $_GET["editjob"] != "" ? true : false;
$id_marken_job = $update ? $_GET["editjob"] : null;
$sites = query_getMarkenSiteTipos(null, 1);
$markenJob = $update ? (object) query_getMarkenJobs($id_marken_job)[0] : null;
// $shipperCurrent = $update ? (object) query_getShippers($markenJob->id_shipper)[0] : null;
// $consiggneCurrent = $update ? (object) query_getMarkenConsiggne(null, $markenJob->id)[0] : null;
// $fechaJob = $update ? explode(" ", $markenJob->fecha_hora)[0] : null;
// $horaJob = $update ? substr(explode(" ", $markenJob->fecha_hora)[1], 0, -3) : null;
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
                                <input type="text" name="waybill" id="waybill" class="form-control" placeholder="Ingrese el Waybill" aria-describedby="waybill" required maxlength="35" value="<?= $markenJob->waybill ?>" <?= $update ? " disabled" : "" ?>>
                                <?php if ($update) {
                                ?>
                                    <input type="hidden" name="waybill" value="<?= $markenJob->waybill ?>">
                                <?php } ?>
                            </div>

                            <!-- <label for="exportador-text">Shipper:</label>
                            <div class="input-group my-2">
                                <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled id="desc_shipper" placeholder="Elija un Shipper" value="<?= $shipperCurrent->nombre ?>">
                                <div class="input-group-append">
                                    <button class="btn bg-aldem-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Elije una opcion</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalShipper">Seleccionar Shipper</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNewShipper">Crear Shipper</a>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                    </div>
                    <h5>Shipper</h5>
                    <div class="row mt-2 ml-2">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="remitente">Remitente: </label>
                                <input type="text" name="remitente" id="remitente" class="form-control" placeholder="Ingrese el remitente" aria-describedby="remitente" maxlength="250" value="<?= $markenJob->remitente ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="ciudad">Ciudad: </label>
                                <input type="text" name="ciudad" id="ciudad" class="form-control" placeholder="Ingrese el ciudad" aria-describedby="ciudad" maxlength="100" value="<?= $markenJob->ciudad ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="paisremitente">Pais: </label>
                                <input type="text" name="paisremitente" id="paisremitente" class="form-control" placeholder="Ingrese el pais" aria-describedby="pais" maxlength="50" value="<?= $markenJob->paisremitente ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class=" form-group my-2">
                                <label for="id_marken_site">Site:</label>
                                <select name="id_marken_site" id="id_marken_site" class="form-control" placeholder="Elija el Site" aria-describedby="site" style="width: 100%;">
                                    <option value="">Selecciona un Site</option>
                                    <?php foreach ($sites as $key => $site) {
                                    ?>
                                        <option value="<?= $site->id_marken_site ?>"><?= $site->descripcion ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="contact">Contact: </label>
                                <input type="text" name="contact" id="contact" class="form-control" placeholder="Ingrese el Contacto" aria-describedby="contact" maxlength="50" value="<?= $markenJob->contact ?>">
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
                        <label for="protocolo">Protocolo: </label>
                        <input type="text" name="protocolo" id="protocolo" class="form-control" placeholder="Ingrese Protocolo" aria-describedby="protocolo" maxlength="50" value="<?= $markenJob->protocolo ?>">
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h5>Consignatario</h5>
                            <div class="pl-2">
                                <div class="form-group mb-2">
                                    <label for="consignee">Nombre: </label>
                                    <input type="text" name="consignee" id="consignee" class="form-control" placeholder="Ingrese el Nombre del Consignatario" aria-describedby="nombre" maxlength="150" value="<?= $markenJob->consignee ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="consigge_direccion">Direccion: </label>
                                    <input type="text" name="consigge_direccion" id="consigge_direccion" class="form-control" placeholder="Ingrese la Direccion del Consignatario" aria-describedby="consigge_direccion" maxlength="250" value="<?= $markenJob->direccionconsignee ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="paisconsignee">Pais: </label>
                                    <input type="text" name="paisconsignee" id="paisconsignee" class="form-control" placeholder="Ingrese el Prefijo del Pais" aria-describedby="paisconsignee" maxlength="5" value="<?= $markenJob->paisconsignee ?>">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="pcs">PCs: </label>
                                <input type="number" name="pcs" id="pcs" class="form-control" placeholder="Ingrese el numero de Pcs" required aria-describedby="pcs" min="1" value="<?= $update ? $markenJob->pcs : 1 ?>">
                            </div>
                        </div>


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
                                <label for="fecha">Fecha y Hora de Recoleccion: </label>
                                <input type="text" name="fecha" id="fecha" class="form-control" placeholder="Ingrese el numero de fecha" required aria-describedby="fecha">
                            </div>
                        </div>
                        <div class="col-md-6 mt-1">
                            <div class="form-group">
                                <label for="ind_activo">Estado</label>
                                <select class="form-control" name="ind_activo" id="ind_activo">
                                    <option value="1" <?= $markenType->ind_activo == 1  ? "selected" : "" ?>>Activo</option>
                                    <option value="2" <?= $markenType->ind_activo == 2  ? "selected" : "" ?>>Inactivo</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <?php aldem_set_proccess_form(); ?>
                    <?php aldem_set_input_hidden("user_id", get_current_user_id()); ?>
                    <?php if ($update) {
                        aldem_set_action_name("update-job");
                        aldem_set_input_hidden("contacto_telf",                         $markenJob->contact_telephone, false);
                        aldem_set_input_hidden("id_marken_job", $id_marken_job);
                    } else {
                        aldem_set_input_hidden("contacto_telf", "", false);
                        aldem_set_action_name("new-job");
                    } ?>
                    <button type="submit" class="btn  w-100 btn-aldem-verde" id="btnSubmit"> <i class="fa fa-save mr-1"></i>Guardar</button>

                    <?= do_shortcode("[aldem_btn_eliminar_job param='editjob']") ?>
                </form>
            </div>
        </div>
    </div>
</div>






<script>
    <?php
    if ($update) {
    ?>
        $('#id_marken_type').val('<?= $markenJob->id_marken_type ?>');
        $('#id_caja').val('<?= $markenJob->id_caja ?>');
        $('#id_marken_site').val('<?= $markenJob->id_marken_site ?>');
    <?php        }
    ?>




    $('#id_marken_site').select2();
    $('#id_caja').select2();
    $('#id_marken_type').select2();
    $('#paisShipper').select2();
    $('#ubigeoShipper').select2();

    $(document).ready(function() {
        // fecha
        $("#fecha").flatpickr({
            enableTime: true,
            minTime: "09:00"
        });
        <?php if ($update && $markenJob->fecha_hora != "0000-00-00 00:00:00") {
        ?>
            $("#fecha").flatpickr({
                enableTime: true,
                defaultDate: "<?= $markenJob->fecha_hora ?>"
            });
        <?php        }
        ?>


        <?php aldem_datatables_in_spanish(); ?>
        $('#table-shippers-select').DataTable();
        const phoneInputField = document.querySelector("#contacto_telf_text");
        const phoneInput = window.intlTelInput(phoneInputField, {
            preferredCountries: ["pe"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
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
                        html: `${response.data}`,
                    })
                    return false;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error Waybill ya registrado, ingrese otro por favor!',
                    })
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
        document.querySelector("#formNewExport").addEventListener("submit", async (e) => {
            e.preventDefault();
            // envitara el doble click
            document.querySelector("#btnSubmit").setAttribute("disabled", "true");
            document.querySelector("#contacto_telf").value = phoneInput.getNumber();
            // verificacion de waybill disponible
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
    });

    // listeners
    (() => {
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
        const closeModalsBoostrap = () => {
            $(" .close").click();
        }
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
                            <th scope="col" class="none">Direccion</th>
                            <th scope="col" class="none">Site</th>
                        </tr>
                    </thead>
                    <tbody>`;
                response.data.forEach(shipper => {
                    htmlTemplate += `
                    <tr>
                    <td class="d-flex justify-content-between" style="align-items: center !important;">
                        <span>${shipper.nombre}</span>
                        <div>
                        <button type="button" class="btn edit-btn" style="background: transparent;" data-id="${shipper.id_shipper}">
                                            
                                            <i class="fas fa-edit fa-2x edit-btn" data-id="${shipper.id_shipper}" " style="color: #17A2B8"></i>
                                            </button>
                        

                        <button type="button" class="btn shipper-btn" style="background: transparent;" data-id-shipper="${shipper.id_shipper}" data-nombre-shipper="${shipper.nombre}"><i class="fas fa-check-circle fa-2x shipper-btn" style="color: #32CC52;" data-id-shipper="${shipper.id_shipper}" data-nombre-shipper="${shipper.nombre}"></i></button>
                        </div>
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
                // document.querySelector("#id_shipper").value = response.data.id_marken_shipper;
                // document.querySelector("#desc_shipper").value = response.data.descripcion;
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
            let response = await (await fetch("<?= $urlShippersByID ?>", requestOptions)).json();
            closeSpinnerCargando();
            if (response.status == 200) {
                return (response.data[0]);
            }
        }
        const getJsonMarkenShipper = () => {
            return {
                "id": $getValue(`#idEditMarken`),
                "nombreShipper": $getValue(`#nombreEdit`),
                "direccionShipper": $getValue(`#direccionEdit`),
                "paisShipper": $getValue(`#paisEdit`),
                "siteShipper": $getValue(`#siteEdit`),
                "ubigeoShipper": $getValue(`#ubigeoEdit`),
                "id_user": <?= get_current_user_id() ?>,
            }
        }
        const setDataFormEdit = (data) => {
            console.log(data);
            $setValue("#nombreEdit", data.nombre);
            $setValue("#direccionEdit", data.direccion);
            // seteo valor al select y notifica al select
            // si es nulo ponle peru
            $("#paisEdit").val(data.id_country ?? 604);
            $("#ubigeoEdit").val(data.id_ubigeo);
            $("#siteEdit").val(data.id_marken_site);
            $("#paisEdit").trigger("change");
            $("#ubigeoEdit").trigger("change");
        }
        const executeEventFormEditMarken = async (e) => {
            if (e.target.classList.value.includes("edit-btn")) {
                let id = e.target.getAttribute("data-id");
                $setValue("#idEditMarken", id);
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
            let response = await (await fetch("<?= $urlShippers ?>", requestOptions)).json();
            if (response.status == 200) {
                // todo salio correctamente
                Swal.fire({
                    icon: "success",
                    title: "Se Ha Actualizado Correctamente El Marken Shipper",
                    showConfirmButton: false,
                    timer: 1500
                })

                await cargarTablaShipperAsync("#table-shippers-select");
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
        $('#paisShipper').on('select2:select', async function(e) {
            let id_country = (e.params.data.id);
            await setUbigeosAsync(id_country, "#ubigeoShipper");
        });
        // necesario para el formulario de edit
        $('#paisEdit').on('select2:select', async function(e) {
            let id_country = (e.params.data.id);
            await setUbigeosAsync(id_country, "#ubigeoEdit");
        });

        document.addEventListener("click", async (e) => {
            // if (e.target.classList.value.includes("shipper-btn")) {
            //     let idShipper = e.target.getAttribute("data-id-shipper");
            //     let nombreShipper = e.target.getAttribute("data-nombre-shipper");
            //     document.querySelector("#id_shipper").value = idShipper;
            //     document.querySelector("#desc_shipper").value = nombreShipper;
            //     document.querySelector("#btnCloseListShippers").click();
            // }
            await executeEventFormEditMarken(e);
        })

        // document.querySelector("#formNewShipper").addEventListener("submit", async (e) => {
        //     e.preventDefault();
        //     document.querySelector("#btnCloseNewShippers").click();
        //     try {
        //         await postShipperAsync();
        //         e.target.reset();
        //     } catch (error) {
        //         console.error(error);
        //     }
        // })
        // eventSubmitFormEditMarken();
    })()
</script>
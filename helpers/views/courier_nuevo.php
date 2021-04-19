<?php
$countrys = (object) query_getCountrys();
$incoTerms = query_getIncoterms();
$exportadores = query_getExportadores();
$importadores = query_getImportadores();
$uriMarkenShipper = get_site_url() . "/wp-json/aldem/v1/marken_shipper/" . aldem_getUserNameCurrent();
?>

<?php
aldem_cargarStyles();
aldem_show_message_custom("Se ha registrado correctamente el Courier 😀", "Se ha actualizado correctamente el Courier😀", "Ocurrio un error 😢 en el registro del Courier");
?>
<div class="row justify-content-center">
    <div pcs="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="job">Job</label>
                        <input type="text" name="job" id="job" class="form-control" placeholder="Ingrese el Job" aria-describedby="job" maxlength="25">
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="manifesto">Manifesto</label>
                                <input type="number" name="manifesto" id="manifesto" class="form-control" placeholder="Ingrese el Manifesto" aria-describedby="manifesto" min="1">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="dua">DUA</label>
                                <input type="number" name="dua" id="dua" class="form-control" placeholder="Ingrese la DUA" aria-describedby="DUA" min="1">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="guia">Guia</label>
                                <input type="text" name="guia" id="guia" class="form-control" placeholder="Ingrese la guia" aria-describedby="guia" min="1">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="master-text">Master</label>
                                <input type="text" name="master-text" id="master-text" class="form-control" placeholder="Ingrese el Master" aria-describedby="master-text" min="1">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="">Pcs</label>
                                <input type="number" name="pcs" id="pcs" class="form-control" placeholder="Ingrese las pcs" aria-describedby="pcs" min="1">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group mb-2">
                                <label for="kilos">Kilos: </label>
                                <input type="number" name="kilos" id="kilos" class="form-control" placeholder="Ingrese los kilos" aria-describedby="kilos" step="0.01">
                            </div>
                        </div>
                    </div>


                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled id="importador-text" placeholder="Elija un importador">
                        <div class="input-group-append">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: black; color: white;">Elije una opcion</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalImportador">Seleccionar Importador</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNewImportador">Nuevo Importador</a>
                            </div>
                        </div>
                    </div>
                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled id="exportador-text" placeholder="Elija un exportador">
                        <div class="input-group-append">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: black; color: white;">Elije una opcion</button>
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
                                <label for="pcs">Schd Collection</label>
                                <input type="text" name="collection" id="collection" class="form-control" placeholder="Ingrese el Collection" aria-describedby="collection" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="delivery">Delivery: </label>
                                <input type="time" name="delivery" id="delivery" class="form-control" placeholder="Ingrese hora del delivery" aria-describedby="delivery">
                            </div>
                        </div>
                    </div>


                    <div class="form-group my-2">
                        <label for="protocolo">Protocolo: </label>
                        <input type="text" name="protocolo" id="protocolo" class="form-control" placeholder="Ingrese el Protocolo" aria-describedby="protocolo" maxlength="50">
                        <!-- <textarea name="protocolo" id="protocolo" class="form-control" placeholder="Ingrese el protocolo" aria-describedby="protocolo" maxlength="50" style="min-height: 140px;"></textarea> -->
                    </div>
                    <div class="form-group my-2">
                        <label for="instructions">Instructions: </label>
                        <textarea name="instructions" id="instructions" class="form-control" placeholder="Ingrese las instrucciones" aria-describedby="instructions" maxlength="500" style="min-height: 140px;"></textarea>
                    </div>

                    <?php aldem_set_input_hidden("master", ""); ?>
                    <?php aldem_set_input_hidden("id_exportador", ""); ?>
                    <?php aldem_set_input_hidden("id_importador", ""); ?>
                    <button type="submit" class="btn btn-success w-100" style="background-color: #98ddca; color: white; border-radius: 5px;"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal de Exportador -->
<div class="modal" id="modalExportador" tabindex="-1" role="dialog" aria-labelledby="modalExportador" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Elige un Exportador</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseListExportador">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="table-exportadors-select" style="width: 100%;">
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
                                    <button type="button" class="btn exportador-btn" style="background: transparent;" data-id-exportador="<?= $exportador->id_exportador  ?>" data-nombre-exportador="<?= $exportador->nombre ?>"><i class="fas fa-check-circle fa-2x" style="color: #32CC52;"></i></button>
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

<!-- modal de Exportador -->
<div class="modal" id="modalImportador" tabindex="-1" role="dialog" aria-labelledby="modalImportador" aria-hidden="true" style="margin-top: 100px; ">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Elige un Importador</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btnCloseListImportador">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                                    <button type="button" class="btn importador-btn" style="background: transparent;" data-id-importador="<?= $importador->id_importador  ?>" data-nombre-importador="<?= $importador->nombre ?>"><i class="fas fa-check-circle fa-2x" style="color: #32CC52;"></i></button>
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
                    <button type="submit" class="btn btn-success w-100" style="background-color: #98ddca; color: white; border-radius: 5px;"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-capitalize" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>



<script>
    $('#incoterm').select2();
    $('#paisNewImportador').val('604');
    $('#paisNewExportador').val('604');
    $('#paisNewImportador').select2();
    $('#paisNewExportador').select2();
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
                    title: response.message,
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
        document.querySelector("#master-text").addEventListener("keyup", (e) => {
            function formatearTargetaBanco(string) {
                var cleaned = ("" + string).replace(/\D/g, '').replace("-", "");
                if (cleaned != "") {
                    if (cleaned.length > 10) {
                        cleaned = cleaned.substring(0, 10);
                        console.log(cleaned.length);
                    } else if (cleaned.length < 10) {
                        cleaned = cleaned.padEnd(10);
                        // console.log("mi tamaño es", cleaned.length);
                    }
                    return cleaned.substring(0, 3) + "-" + cleaned.substring(3, 6) + "-" + cleaned.substring(6, 10)
                } else {
                    return "";
                }
            }
            setTimeout(() => {
                e.target.value = formatearTargetaBanco(e.target.value);
                document.querySelector("#master").value = formatearTargetaBanco(e.target.value).replace(/\D/g, '').replace("-", "");
            }, 500);
        })
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

    })()
</script>
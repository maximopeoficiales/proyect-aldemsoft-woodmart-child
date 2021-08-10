<?php
$costos = query_getMarkenCostos();

// uris
$urlPostCostosEnabled = get_site_url() . "/wp-json/aldem/v1/postCostosEnabled/" . aldem_getUserNameCurrent();
$urlPostCostosCreate = get_site_url() . "/wp-json/aldem/v1/postCostosCreate/" . aldem_getUserNameCurrent();

aldem_cargarStyles();
?>
<div class="row justify-content-center my-4">

    <div class="col-md-8">
        <div class="card my-4">
            <div class="card-body">
                <h4 class="card-title">Crear Costo</h4>
                <form class="" id="formCreateCosto">
                    <div class="form-group">
                        <label for="name_costo">Nombre del Costo:</label>
                        <input type="text" class="form-control" name="name_costo" id="name_costo" placeholder="Ingrese el nombre del costo" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 my-2" style="background-color: #98ddca; color: white; border-radius: 5px;"><i class="fas fa-save mx-1"></i> Guardar</button>

                </form>
            </div>
        </div>
        <div class="card " id="cardCostos">
            <div class="card-body">
                <div class="text-center my-4" id="titleMarkenCostos"></div>
                <!-- items principales -->
                <div class="">
                    <div class="d-flex justify-content-around p-2 align-items-center">
                        <div class=""><b>Item</b></div>
                        <div class=""><b>Visible</b></div>
                    </div>
                    <div class="" id="containerItems">
                        <?php foreach ($costos as $costo) { ?>
                            <div class="d-flex justify-content-between p-1 align-items-center">
                                <div class="">
                                    <?= $costo->descripcion ?>
                                </div>
                                <div class="">
                                    <div class="form-group">
                                        <select class="form-control aldem_costo_item" name="costo_active" id-costo="<?= $costo->id_costo ?>">
                                            <option <?= $costo->enabled == 1 ? "selected" : "" ?> value="1"><b class="mx-2"> SI </b></option>
                                            <option <?= $costo->enabled == 0 ? "selected" : "" ?> value="0"> <b class="mx-2"> NO </b></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- fin de items principales -->

                <button type="button" id="btnSaveCostos" class="btn btn-success w-100 my-2" style="background-color: #98ddca; color: white; border-radius: 5px;"><i class="fas fa-save mx-1"></i> Guardar Costos</button>
                <!-- fin de otros costos -->
            </div>

        </div>

    </div>
</div>


<script>
    // utilities
    const $getValue = (id) => {
        try {
            return document.querySelector(id).value
        } catch (error) {
            console.error(error);
        }
    };

    const $setValue = (id, value = "") => {
        try {
            return document.querySelector(id).value = value;
        } catch (error) {
            console.error(error);
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

    const getDataUpdateCosto = () => {
        let dataUpdate = [];
        let costos = document.querySelectorAll(".aldem_costo_item");
        costos.forEach(c => {
            if (c.value != "") {
                dataUpdate.push({
                    id: c.getAttribute("id-costo"),
                    valor: c.value
                });
            }
        });
        return dataUpdate;
    }
    // api
    const handlerResponseApiAldem = async (response, success = () => {}, parametrosNoValidos = () => {}, error = () => {}) => {
        if (response.status === 200) {
            success();
        } else if (response.status == 404) {
            parametrosNoValidos();
            Swal.fire({
                icon: 'error',
                title: `${response.data}`,
                html: `${response.message}`,
            })
        } else {
            error();
            Swal.fire({
                icon: 'error',
                title: `${response.data}`,
                html: `${response.message}`,
            });
        }
    }

    // fin de api
    const postCostos = async () => {
        var myHeaders = new Headers();
        myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
        myHeaders.append("Content-Type", "application/json");
        var raw = JSON.stringify({
            costos: getDataUpdateCosto()
        });

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };
        try {
            let data = await (await (fetch('<?= $urlPostCostosEnabled  ?>', requestOptions))).json();
            return data;
        } catch (error) {
            console.error(error);
        }
    }
    const postCostosCreate = async () => {
        var myHeaders = new Headers();
        myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
        myHeaders.append("Content-Type", "application/json");
        var raw = JSON.stringify({
            descripcion: $getValue("#name_costo")
        });

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };
        try {
            let data = await (await (fetch('<?= $urlPostCostosCreate  ?>', requestOptions))).json();
            return data;
        } catch (error) {
            console.error(error);
        }
    }
    // event listeners

    const handlerClickSaveCostos = async (e) => {
        Swal.fire({
            title: '¿Estas seguro de actualizar los costos?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                // e.target.disabled = true;
                console.log();
                showSpinnerCargando();
                let response = await postCostos();
                await handlerResponseApiAldem(response);
                closeSpinnerCargando();
                Swal.fire(
                    '¡Costos Actualizados!',
                    'Los costos fueron actualizados.',
                    'success'
                )
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        })

    }

    const handlerSaveCostos = async (e) => {
        e.preventDefault();
        let name_costo = $getValue("#name_costo");
        if (name_costo != "") {
            showSpinnerCargando();
            let response = await postCostosCreate();
            await handlerResponseApiAldem(response);
            closeSpinnerCargando();
            Swal.fire(
                '¡Costo Creado!',
                'Se ha registrado un nuevo costo correctamente.',
                'success'
            )
            setTimeout(() => {
                location.reload();
            }, 1500);
        }
        return;
    }
    document.querySelector("#btnSaveCostos").addEventListener("click", handlerClickSaveCostos);
    document.querySelector("#formCreateCosto").addEventListener("submit", handlerSaveCostos);
</script>
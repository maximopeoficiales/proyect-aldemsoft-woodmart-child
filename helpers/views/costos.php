<?php
$anios = query_getAnios();
$meses = query_getMeses();

// uris
$urlGetCostos = get_site_url() . "/wp-json/aldem/v1/getCostos/" . aldem_getUserNameCurrent();
$urlPostCostos = get_site_url() . "/wp-json/aldem/v1/postCostos/" . aldem_getUserNameCurrent();
// obtencion de datos
aldem_cargarStyles();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="post" id="formCostoAnioMes">
            <div class="form-group my-2">
                <label for="">Año:</label>
                <select class="form-control" name="costo_anio" id="costo_anio" required>
                    <option value="">- Seleccione un año -</option>
                    <?php foreach ($anios as $anio) {
                    ?>
                        <option value="<?= $anio->id_anio ?>"><?= $anio->description ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group my-2">
                <label for="">Mes:</label>
                <select class="form-control" name="costo_mes" id="costo_mes" required>
                    <option value="">- Selecciona un mes -</option>
                    <?php foreach ($meses as $mes) {
                    ?>
                        <option value="<?= $mes->id_mes ?>"><?= $mes->description ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" id="btnConsultarCostos" class="btn btn-success w-100 my-2" style="background-color: #98ddca; color: white; border-radius: 5px;"><i class="fas fa-search-dollar mx-1"></i>Consultar</button>
        </form>
    </div>
</div>
<div class="row justify-content-center my-4">

    <div class="col-md-8">
        <div class="card d-none" id="cardCostos">
            <div class="card-body">
                <div class="text-center my-4" id="titleMarkenCostos"></div>
                <!-- items principales -->
                <div class="">
                    <div class="d-flex justify-content-around p-2 align-items-center">
                        <div class=""><b>Item</b></div>
                        <div class=""><b>Valor</b></div>
                    </div>
                    <div class="" id="containerItems">
                        <div class="d-flex justify-content-between p-1 align-items-center">
                            <div class="">
                                Gastos Personal
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <input type="number" class="form-control text-right aldem_costo_item" placeholder="Ingrese el Valor" value="" step='0.01' onchange="getTotalByItems()">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- fin de items principales -->

                <!-- otros costos -->
                <div class="card my-2 d-none" id="cardOtrosCostos">
                    <div class="card-header bg-dark aldem_pointer" id="headingOne" data-toggle="collapse" data-target="#otrosCostos" aria-expanded="true" aria-controls="otrosCostos" style>
                        <h2 class="mb-0">
                            <div class="d-block text-white">
                                <div class="w-100 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-white">
                                        Otros Costos
                                    </h5>
                                    <div class="">
                                        <i class="mx-2 fas fa-sort-down  fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </h2>
                    </div>

                    <div id="otrosCostos" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="d-flex justify-content-around p-2 align-items-center">
                                <div class=""><b>Item</b></div>
                                <div class=""><b>Valor</b></div>
                            </div>
                            <div class="" id="containerOtrosItems">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="">
                                        Gastos Personal
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                            <input type="number" class="form-control text-right aldem_costo_item" placeholder="Ingrese el Valor" value="" step='0.01' onchange="getTotalByItems()">
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <button type="submit" id="btnSaveCostos" class="btn btn-success w-100 my-2" style="background-color: #98ddca; color: white; border-radius: 5px;"><i class="fas fa-save mx-1"></i> Guardar Costos</button>
                <!-- fin de otros costos -->
            </div>
            <!-- total -->
            <div class=" bg-aldem-dark">
                <div class="d-flex justify-content-between p-3">
                    <div class="aldem-text-size-lg"><b>Total</b></div>
                    <div class="aldem-text-size-lg"><b id="totalCostos">0.00</b></div>
                </div>
            </div>
            <!-- fin de total -->
        </div>

    </div>
</div>


<script>
    // utilities
    const addCommas = (nStr) => {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
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
    // customs
    const getTotalByItems = () => {
        let total = 0;
        let costos = document.querySelectorAll(".aldem_costo_item");
        costos.forEach(c => {
            if (c.value != "" && c.value >= 0) {
                total += parseFloat(c.value);
            }
        })
        if (total < 0 || total == Infinity || total == NaN) {
            total = 0;
        }
        document.querySelector("#totalCostos").innerText = addCommas(total.toFixed(2));
    }
    const getTextSelectedBySelect = (id) => {
        let e = document.querySelector(id);
        return e.options[e.selectedIndex].text;
    }
    const getDataUpdateCosto = () => {
        let dataUpdate = [];
        let costos = document.querySelectorAll(".aldem_costo_item");
        costos.forEach(c => {
            if (c.value != "" && c.value >= 0) {
                dataUpdate.push({
                    id: c.getAttribute("id-costo"),
                    valor: c.value
                });
            }
        });
        return dataUpdate;
    }
    const setTitleMarkenCosto = () => {
        document.querySelector("#titleMarkenCostos").innerHTML = `
      <h3><b>Costos Marken ${getTextSelectedBySelect("#costo_mes")} - ${getTextSelectedBySelect("#costo_anio")}</b></h3>    
      `;
    }
    const hiddenShowCardCostos = (hidden = true) => {
        if (hidden) {
            document.querySelector("#cardCostos").classList.add("d-none");
        } else {
            document.querySelector("#cardCostos").classList.remove("d-none");
        }
    }
    const hiddenShowCardOtrosCostos = (hidden = true) => {
        if (hidden) {
            document.querySelector("#cardOtrosCostos").classList.add("d-none");
        } else {
            document.querySelector("#cardOtrosCostos").classList.remove("d-none");
        }
    }
    const cleanContainerItems = () => {
        document.querySelector("#containerItems").innerHTML = '';
    }
    const cleanContainerOtrosItems = () => {
        document.querySelector("#containerOtrosItems").innerHTML = '';
    }

    const setItemsContainerItems = (costos = []) => {
        cleanContainerItems();
        let $html = "";
        costos.forEach(costo => {
            $html += `
            <div class="d-flex justify-content-between p-1 align-items-center" >
                <div class="">
                    ${costo.descripcion}
                </div>
                <div class="">
                    <div class="form-group">
                        <input type="number" class="form-control text-right aldem_costo_item" placeholder="Ingrese el Valor" value="${costo.valor ?? ""}" step='0.01' onchange="getTotalByItems()" id-costo=${costo.id}>
                    </div>
                </div>
            </div>
            `;
        });
        document.querySelector("#containerItems").innerHTML = $html;
    }
    const setItemsContainerOtrosItems = (otrosCostos = []) => {
        cleanContainerOtrosItems();
        let $html = "";
        otrosCostos.forEach(costo => {
            $html += `
            <div class="d-flex justify-content-between align-items-center" >
                <div class="">
                    ${costo.descripcion}
                </div>
                <div class="">
                    <div class="form-group my-1">
                        <input type="number" class="form-control text-right aldem_costo_item" placeholder="Ingrese el Valor" value="${costo.valor ?? ""}" step='0.01' onchange="getTotalByItems()" id-costo=${costo.id}>
                    </div>
                </div>
            </div>
            `;
        });
        document.querySelector("#containerOtrosItems").innerHTML = $html;
    }
    // fin de utilities

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
    const getCostosByAnioMes = async (anio, mes) => {
        var myHeaders = new Headers();
        myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
        myHeaders.append("Content-Type", "application/json");
        var raw = JSON.stringify({
            anio,
            mes
        });

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };
        try {
            let data = await (await (fetch('<?= $urlGetCostos  ?>', requestOptions))).json();
            return data;
        } catch (error) {
            console.error(error);
        }
    }
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
            let data = await (await (fetch('<?= $urlPostCostos  ?>', requestOptions))).json();
            return data;
        } catch (error) {
            console.error(error);
        }
    }
    // event listeners
    const handlerSubmitFormAnioMes = async (e) => {
        e.preventDefault();
        let costo_anio = $getValue("#costo_anio");
        let costo_mes = $getValue("#costo_mes");
        if (costo_anio != "" && costo_mes != "") {
            showSpinnerCargando();
            setTitleMarkenCosto();
            let response = await getCostosByAnioMes(costo_anio, costo_mes);
            await handlerResponseApiAldem(response, () => {
                let costos = response.data;
                // seteo de total de Costos
                document.querySelector("#totalCostos").innerText = addCommas(parseFloat(costos.totalCostos ?? 0).toFixed(2));
                // console.log(costos);
                setItemsContainerItems(costos.costos)
                hiddenShowCardCostos(false);
                // limpio los datos
                setItemsContainerOtrosItems(costos.otrosCostos);
                if (costos.otrosCostos.length > 0) {
                    setItemsContainerOtrosItems(costos.otrosCostos)
                    hiddenShowCardOtrosCostos(false);

                } else {
                    hiddenShowCardOtrosCostos()
                }


            });
            closeSpinnerCargando();
        }
        return;
    }

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
            }
        })

    }
    // fin de api



    document.querySelector("#formCostoAnioMes").addEventListener("submit", handlerSubmitFormAnioMes);

    document.querySelector("#btnSaveCostos").addEventListener("click", handlerClickSaveCostos);
</script>
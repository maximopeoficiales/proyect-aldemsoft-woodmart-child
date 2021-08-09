<?php
$anios = query_getAnios();
$meses = query_getMeses();

// obtencion de datos
aldem_cargarStyles();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="post" id="formCostoAnioMes">
            <div class="form-group my-2">
                <label for="">Año:</label>
                <select class="form-control" name="costo_anio" id="costo_anio">
                    <option value="">- Seleccione un año -</option>
                    <?php foreach ($anios as $anio) {
                    ?>
                        <option value="<?= $anio->id_anio ?>"><?= $anio->description ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group my-2">
                <label for="">Mes:</label>
                <select class="form-control" name="costo_mes" id="costo_mes">
                    <option value="">- Selecciona un mes -</option>
                    <?php foreach ($meses as $mes) {
                    ?>
                        <option value="<?= $mes->id_mes ?>"><?= $mes->description ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" id="btnConsultarCostos" class="btn btn-success w-100 my-2" style="background-color: #98ddca; color: white; border-radius: 5px;">Consultar</button>
        </form>
    </div>
</div>
<div class="row justify-content-center my-4">

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <!-- items principales -->
                <div class="">
                    <div class="d-flex justify-content-around p-2 align-items-center">
                        <div class=""><b>Item</b></div>
                        <div class=""><b>Valor</b></div>
                    </div>

                    <div class="d-flex justify-content-between p-1 align-items-center">
                        <div class="">
                            Gastos Personal
                        </div>
                        <div class="">
                            <div class="form-group">
                                <input type="number" class="form-control text-right aldem_costo_item" placeholder="Ingrese el Valor" value="1.15" step='0.01' onchange="getTotalByItems()">
                            </div>
                        </div>

                    </div>
                </div>
                <!-- fin de items principales -->

                <!-- otros costos -->
                <div class="card my-2">
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
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="">
                                    Gastos Personal
                                </div>
                                <div class="">
                                    <div class="form-group">
                                        <input type="number" class="form-control text-right aldem_costo_item" placeholder="Ingrese el Valor" value="1.15" step='0.01' onchange="getTotalByItems()">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
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
    // fin de utilities

    // event listeners
    const handlerSubmitFormAnioMes = async (e) => {
        e.preventDefault();
        let costo_anio = $getValue("#costo_anio");
        let costo_mes = $getValue("#costo_mes");
        if (costo_anio != "" && costo_mes != "") {
            showSpinnerCargando()
            setTimeout(() => {
                closeSpinnerCargando();
            }, 2000);
        }
        return;
    }
    const getTotalByItems = () => {
        let total = 0;
        let costos = document.querySelectorAll(".aldem_costo_item");
        costos.forEach(c => {
            if (c.value != "" && c.value >= 0) {
                total += parseFloat(c.value);
            }
        })
        document.querySelector("#totalCostos").innerText = total.toFixed(2);
    }

    
    document.querySelector("#formCostoAnioMes").addEventListener("submit", handlerSubmitFormAnioMes);
</script>
<?php


?>
<?php
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
                                <input type="number" name="pcs" id="pcs" class="form-control" placeholder="Ingrese la guia" aria-describedby="pcs" min="1">
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
                        <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled id="exportador-text" placeholder="Elija un exportador">
                        <div class="input-group-append">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: black; color: white;">Elije una opcion</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Seleccionar Exportador</a>
                                <a class="dropdown-item" href="#">Nuevo Exportador</a>
                            </div>
                        </div>
                    </div>

                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled id="importador-text" placeholder="Elija un importador">
                        <div class="input-group-append">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: black; color: white;">Elije una opcion</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Seleccionar Importador</a>
                                <a class="dropdown-item" href="#">Nuevo Importador</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group my-2">
                        <label for="incoterm">IncoTerm:</label>
                        <select name="incoterm" id="incoterm" class="form-control" placeholder="Elija el Incoterm" aria-describedby="Mes" required style="width: 100%;">

                            <?php foreach (aldem_getMonths() as $key => $month) {
                            ?>

                                <option value="<?= sprintf("%02d", $key + 1) ?>"><?= $month ?></option>
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
                        <label for="instructions">Instructions: </label>
                        <textarea name="instructions" id="instructions" class="form-control" placeholder="Ingrese las instrucciones" aria-describedby="instructions" maxlength="500" style="min-height: 140px;"></textarea>
                    </div>
                    <div class="form-group my-2">
                        <label for="protocolo">Protocolo: </label>
                        <textarea name="protocolo" id="protocolo" class="form-control" placeholder="Ingrese el protocolo" aria-describedby="protocolo" maxlength="50" style="min-height: 140px;"></textarea>
                    </div>

                    <?php aldem_set_input_hidden("master", ""); ?>
                    <button type="submit" class="btn btn-success w-100"> <i class="fa fa-save mr-1"></i>Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
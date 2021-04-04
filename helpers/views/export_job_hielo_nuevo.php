<?php
aldem_show_message_custom("Se ha registrado correctamente el Export Hielo Nuevo 😀", "Se ha actualizado correctamente el Export Hielo Nuevo😀", "Ocurrio un error 😢 en el registro del Export Hielo")
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <form action="<?php echo admin_url('admin-post.php') ?>" method="post">
                <div class="card-body">
                    <h4 class="card-title text-center">Marken Export Hielo Nuevo</h4>
                    <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem vero ad voluptas totam veniam minima dicta error illum provident </p>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kilos">Kilos: </label>
                                <input type="number" name="kilos" id="kilos" class="form-control" placeholder="Ingrese los kilos" aria-describedby="kilos" required min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serie">Serie: </label>
                                <input type="text" name="serie" id="serie" class="form-control" placeholder="Ingrese la serie" aria-describedby="serie" required minlength="1" maxlength="6">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero">Numero: </label>
                                <input type="text" name="numero" id="numero" class="form-control" placeholder="Ingrese el numero" aria-describedby="numero" required minlength="1" maxlength="15">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombreShipper">Precio S/: </label>
                                <input type="number" name="precio" id="precio" class="form-control" placeholder="Ingrese el precio" aria-describedby="precio" required min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="comentario">Comentario: </label>
                        <textarea name="comentario" id="comentario" class="form-control" placeholder="Ingrese su comentario" aria-describedby="comentario" required minlength="1" maxlength="250" style="min-height: 140px;"></textarea>
                    </div>
                    <?php aldem_set_proccess_form(); ?>
                    <?php aldem_set_input_hidden("job", $_GET["job"]); ?>
                    <?php aldem_set_input_hidden("waybill", $_GET["waybill"]); ?>
                    <?php aldem_set_input_hidden("user_id", get_current_user_id()); ?>
                    <?php aldem_set_action_name("new-export-hielo"); ?>
                    <button type="submit" class="btn btn-success w-100"> <i class="fa fa-save mr-1"></i>Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
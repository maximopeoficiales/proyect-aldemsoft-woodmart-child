<?php
aldem_cargarStyles();
?>


<!-- <div class="row my-4 justify-content-center"> -->
<form action="<?php echo admin_url('admin-post.php') ?>" method="post" id="formNewExport" enctype="multipart/form-data" class="my-4">

    <?= aldem_set_input_hidden("page", $atts["page"]) ?>
    <?php aldem_set_proccess_form(); ?>
    <?php aldem_set_action_name("upload-excel-job"); ?>
    <input type="file" name="file_export_jobs" id="" accept=".csv">
    <button type="submit">Subir CSV</button>
</form>
<!-- </div> -->

<?php aldem_show_message_custom("Se ha registrado correctamente el Job 😀", "Se ha actualizado correctamente el Job😀", "Ocurrio un error 😢 en la subida del Archivo");
?>
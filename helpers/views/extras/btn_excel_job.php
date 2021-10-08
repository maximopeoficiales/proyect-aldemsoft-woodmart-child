<?php
aldem_cargarStyles();
aldem_show_message_custom("Se ha registrado correctamente el Job 😀", "Se ha actualizado correctamente el Job😀", "Ocurrio un error 😢 en la subida del Archivo");
?>


<form action="<?php echo admin_url('admin-post.php') ?>" method="post" id="formNewExport" enctype="multipart/form-data">


    <?php aldem_set_proccess_form(); ?>
    <?php aldem_set_input_hidden("user_id", get_current_user_id()); ?>
    <?php aldem_set_action_name("upload-excel-job"); ?>
    <input type="file" name="file_export_jobs" id="">
    <button type="submit">Subir CSV</button>
</form>
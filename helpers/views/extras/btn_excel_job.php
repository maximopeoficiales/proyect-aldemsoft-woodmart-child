<?php
// aldem_show_message_custom("Se ha registrado correctamente el Job 😀", "Se ha actualizado correctamente el Job😀", "Ocurrio un error 😢 en el registro del Job");
?>


<form action="<?php echo admin_url('admin-post.php') ?>" method="post" id="formNewExport" enctype="multipart/form-data">
    <?php aldem_set_action_name("upload-excel-job"); ?>
    <input type="file" name="file_export_jobs" id="">
    <button type="submit">Subir CSV</button>
</form>
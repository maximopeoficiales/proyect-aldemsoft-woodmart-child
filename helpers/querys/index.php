<?php
function query_getWPDB(): wpdb
{
    global $wpdb;
    return $wpdb;
}
function query_getShippers($id_shipper = null)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT t1.id as id_shipper,t1.descripcion as nombre,t1.*,t2.descripcion as site FROM marken_shipper t1
    INNER JOIN marken_site t2 on t2.id = t1.id_marken_site
    WHERE t2.id_cliente_subtipo = 1";
    $sql .= $id_shipper != null ? " AND t1.id= $id_shipper" : "";
    $result = $wpdb->get_results($sql);

    $wpdb->flush();
    return $result;
}

function query_getMarkenJobs($idMarkenJob = null)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT id AS id_marken_job,t1.* FROM marken_job as t1 WHERE id_cliente_subtipo = 1";
    $sql .= $idMarkenJob != null ? " AND id= $idMarkenJob" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getMarkenConsiggne($idConsiggne = null, $id_marken_job = null)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT id AS id_marken_consiggne,t1.* FROM marken_job_consignee as t1";
    if ($idConsiggne != null && $id_marken_job != null) {
        $sql .= " WHERE id= $idConsiggne AND id_marken_job = $id_marken_job ";
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }

    $sql .= $idConsiggne != null ? " WHERE id= $idConsiggne" : "";
    $sql .= $id_marken_job != null ? " WHERE id_marken_job= $id_marken_job" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getMarkenTypes($idMarkenType = null)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT  id AS id_marken_type , descripcion FROM marken_type WHERE id_cliente_subtipo = 1";
    $sql .= $idMarkenType != null ? " AND id= $idMarkenType" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getMarkenCajas($idMarkenCaja = null)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT  id AS id_caja,descripcion from marken_caja where id_cliente_subtipo = 1";
    $sql .= $idMarkenCaja != null ? " AND id= $idMarkenCaja" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getCountrys($idPais = null)
{

    $wpdb = query_getWPDB();

    $sql = "SELECT id AS id_pais, desc_pais FROM pais";
    $sql .= $idPais != null ? " WHERE id= $idPais" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getMarkenSite($idMarkenSite = null)
{

    $wpdb = query_getWPDB();

    $sql = "SELECT id AS id_marken_site, descripcion FROM marken_site WHERE id_cliente_subtipo = 1";
    $sql .= $idMarkenSite != null ? " WHERE id= $idMarkenSite" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getUbigeo($idPais = null, $idUbigeo = null)
{

    $wpdb = query_getWPDB();

    $sql = "SELECT t3.id AS id_ubigeo, CONCAT(t1.descripcion,'-',t2.descripcion,'-',t3.descripcion) AS descripcion 
    FROM ubigeo_departamento t1 
    INNER JOIN ubigeo_provincia t2 on t2.id_departamento = t1.id 
    INNER JOIN ubigeo t3 on t3.id_ubigeo_provincia = t2.id";

    $sql .= $idUbigeo != null ? " WHERE t3.id= $idUbigeo" : "";
    $sql .= $idPais != null ? " WHERE t1.id_pais= $idPais" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getUsers()
{
    $wpdb = query_getWPDB();
    $result = $wpdb->get_results("SELECT * FROM wp_users");
    $wpdb->flush();
    return $result;
}

function query_getNameComplete($id_user = null)
{
    $id_user = $id_user ?? get_current_user_id();
    $sql = "SELECT replace(GROUP_CONCAT(t2.meta_value),',',' ') AS name FROM wp_users t1
    INNER JOIN wp_usermeta t2
    ON t1.id = t2.user_id
    WHERE t1.id = $id_user AND t2.meta_key IN ('last_name', 'first_name')
    ORDER BY t2.meta_key DESC";

    $wpdb = query_getWPDB();
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result[0];
}

function shipper_isUserCreator($id_user_created): bool
{
    return get_current_user_id() === intval($id_user_created) ? true : false;
}

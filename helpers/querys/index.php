<?php
function query_getWPDB(): wpdb
{
    global $wpdb;
    return $wpdb;
}
function query_getShippers()
{
    $wpdb = query_getWPDB();
    $result = $wpdb->get_results("SELECT t1.id as id_shipper,t1.descripcion as nombre,t1.*,t2.descripcion as site FROM marken_shipper t1
    INNER JOIN marken_site t2
    WHERE t2.id_cliente_subtipo = 1");
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

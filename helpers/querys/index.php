<?php
function query_getAldemPrefix()
{
    global $wpdb;
    $table_prefix = $wpdb->prefix . "aldem_";
    return $table_prefix;
}
function query_getWPDB(): wpdb
{
    global $wpdb;
    return $wpdb;
}

function query_getShippers($id_shipper = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t1.id as id_shipper,t1.descripcion as nombre,t1.*,t2.descripcion as site FROM {$prefix}marken_shipper t1
    INNER JOIN {$prefix}marken_site t2 on t2.id = t1.id_marken_site
    WHERE t2.id_cliente_subtipo = 1
    AND t1.id_tipo=1";
    $sql .= $id_shipper != null ? " AND t1.id= $id_shipper" : "";
    $result = $wpdb->get_results($sql);

    $wpdb->flush();
    return $result;
}

function query_getCourierJobs($idMarkenJob = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();

    $sql = "SELECT id AS id_marken_job,t1.* FROM {$prefix}marken_job as t1 WHERE id_cliente_subtipo = 3";
    $sql .= $idMarkenJob != null ? " AND id= $idMarkenJob" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getCargoJobs($idMarkenJob = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();

    $sql = "SELECT id AS id_marken_job,t1.* FROM {$prefix}marken_job as t1 WHERE id_cliente_subtipo = 2";
    $sql .= $idMarkenJob != null ? " AND id= $idMarkenJob" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getPickupJobs($idMarkenJob = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();

    $sql = "SELECT id AS id_marken_job,t1.* FROM {$prefix}marken_job as t1 WHERE id_cliente_subtipo = 4";
    $sql .= $idMarkenJob != null ? " AND id= $idMarkenJob" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}


function query_getMarkenJobs($idMarkenJob = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();

    $sql = "SELECT id AS id_marken_job,t1.* FROM {$prefix}marken_job as t1 WHERE id_cliente_subtipo = 1";
    $sql .= $idMarkenJob != null ? " AND id= $idMarkenJob" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getMarkenConsiggne($idConsiggne = null, $id_marken_job = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();

    $sql = "SELECT id AS id_marken_consiggne,t1.* FROM {$prefix}marken_job_consignee as t1";
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
    $prefix = query_getAldemPrefix();
    $sql = "SELECT  id AS id_marken_type , descripcion FROM {$prefix}marken_type WHERE id_cliente_subtipo = 1";
    $sql .= $idMarkenType != null ? " AND id= $idMarkenType" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getMarkenCajas($idMarkenCaja = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT  id AS id_caja,descripcion from {$prefix}marken_caja where id_cliente_subtipo = 1";
    $sql .= $idMarkenCaja != null ? " AND id= $idMarkenCaja" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getCountrys($idPais = null)
{

    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_pais, desc_pais FROM ${prefix}pais";
    $sql .= $idPais != null ? " WHERE id= $idPais" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getMarkenSite($idMarkenSite = null)
{

    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_marken_site, descripcion FROM ${prefix}marken_site WHERE id_cliente_subtipo = 1";
    $sql .= $idMarkenSite != null ? " WHERE id= $idMarkenSite" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
//  se mejoro funcion 
function query_getMarkenSiteTipos($idMarkenSite = null, $tipo = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_marken_site, descripcion FROM ${prefix}marken_site";
    if ($idMarkenSite == null && $tipo == null) {
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }
    if ($idMarkenSite != null) {
        $sql .= " WHERE id= $idMarkenSite";
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }
    if ($tipo != null) {
        $sql .= " WHERE id_cliente_subtipo= $tipo";
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }
    if ($idMarkenSite != null && $tipo != null) {
        $sql .= " WHERE id=$idMarkenSite AND id_cliente_subtipo=$tipo";
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }
}
function query_getMarkenSites($idMarkenSite = null, $tipo = 3)
{

    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_marken_site, descripcion FROM ${prefix}marken_site WHERE id_cliente_subtipo = $tipo";
    $sql .= $idMarkenSite != null ? " WHERE id= $idMarkenSite" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getMarkenHandlings($idMarkenSite = null, $tipo = 3)
{

    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_handling, descripcion FROM ${prefix}marken_handling WHERE id_cliente_subtipo = $tipo";
    $sql .= $idMarkenSite != null ? " WHERE id= $idMarkenSite" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}


function query_getUbigeo($idPais = null, $idUbigeo = null)
{

    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t3.id AS id_ubigeo, CONCAT(t1.descripcion,'-',t2.descripcion,'-',t3.descripcion) AS descripcion 
    FROM ${prefix}ubigeo_departamento t1 
    INNER JOIN ${prefix}ubigeo_provincia t2 on t2.id_departamento = t1.id 
    INNER JOIN ${prefix}ubigeo t3 on t3.id_ubigeo_provincia = t2.id";

    $sql .= $idUbigeo != null ? " WHERE t3.id= $idUbigeo" : "";
    $sql .= $idPais != null ? " WHERE t1.id_pais= $idPais" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getUsers()
{
    // $prefix = query_getAldemPrefix();
    $wpdb = query_getWPDB();
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users");
    $wpdb->flush();
    return $result;
}

function query_getNameComplete($id_user = null)
{
    // $prefix = query_getAldemPrefix();
    $wpdb = query_getWPDB();
    $id_user = $id_user ?? get_current_user_id();
    $sql = "SELECT replace(GROUP_CONCAT(t2.meta_value),',',' ') AS name FROM {$wpdb->prefix}users t1
    INNER JOIN {$wpdb->prefix}usermeta t2
    ON t1.id = t2.user_id
    WHERE t1.id = $id_user AND t2.meta_key IN ('last_name', 'first_name')
    ORDER BY t2.meta_key DESC";

    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result[0];
}

function shipper_isUserCreator($id_user_created): bool
{
    return get_current_user_id() === intval($id_user_created) ? true : false;
}
function query_getIncoterms($incoterm = null)
{

    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_incoterm, descripcion FROM ${prefix}incoterms";
    $sql .= $incoterm != null ? " WHERE id= $incoterm" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getMarkenShipperByID($id = 0)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t1.id AS id_importador,t1.id AS id_shipper,t1.descripcion as nombre,t1.direccion,t1.*,t2.* FROM ${prefix}marken_shipper AS t1 INNER JOIN ${prefix}pais t2 ON t2.id = t1.id_country WHERE t1.id= $id";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getImportadores($idImportador = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t1.id AS id_importador,t1.id AS id_shipper,t1.descripcion as nombre,t1.direccion,t1.*,t2.* FROM ${prefix}marken_shipper AS t1 INNER JOIN ${prefix}pais t2 ON t2.id = t1.id_country WHERE t1.id_tipo = 3";
    $sql .= $idImportador != null ? " AND t1.id= $idImportador" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getExportadores($idExportador = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t1.id AS id_exportador,t1.id AS id_shipper,t1.descripcion as nombre,t1.direccion,t1.*,t2.* FROM ${prefix}marken_shipper AS t1 INNER JOIN ${prefix}pais t2 ON t2.id = t1.id_country WHERE t1.id_tipo = 2";
    $sql .= $idExportador != null ? " AND t1.id= $idExportador" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
// se agregaron remitente y consignatorios
function query_getRemitentes($idRemitente = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t1.id AS id_remitente,t1.id AS id_shipper,t1.descripcion as nombre,t1.direccion,t1.*,t2.* FROM ${prefix}marken_shipper AS t1 INNER JOIN ${prefix}pais t2 ON t2.id = t1.id_country WHERE t1.id_tipo = 4";
    $sql .= $idRemitente != null ? " AND t1.id= $idRemitente" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getConsignatorios($idConsignatorio = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t1.id AS id_consignatorio,t1.id AS id_shipper,t1.descripcion as nombre,t1.direccion,t1.*,t2.* FROM ${prefix}marken_shipper AS t1 INNER JOIN ${prefix}pais t2 ON t2.id = t1.id_country WHERE t1.id_tipo = 5";
    $sql .= $idConsignatorio != null ? " AND t1.id= $idConsignatorio" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getMarkenDelivery($idDelivery = null)
{

    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_delivery, descripcion FROM ${prefix}marken_delivery";
    $sql .= $idDelivery != null ? " WHERE id= $idDelivery" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getSubsubTipo($id = null, $idClienteSubtipo = null)
{

    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_cliente_subsubtipo, descripcion FROM ${prefix}cliente_subsubtipo";
    if ($id == null && $idClienteSubtipo == null) {
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }
    if ($id != null) {
        $sql .= " WHERE id= $id";
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }
    if ($idClienteSubtipo != null) {
        $sql .= " WHERE id_cliente_subtipo= $idClienteSubtipo";
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }
    if ($id != null && $idClienteSubtipo != null) {
        $sql .= " WHERE id=$id AND id_cliente_subtipo=$idClienteSubtipo";
        $result = $wpdb->get_results($sql);
        $wpdb->flush();
        return $result;
    }
}

function query_getMarkenTransporte($id = null)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id AS id_anio, descripcion as description FROM ${prefix}marken_transporte";
    $sql .= $id != null ? " WHERE id= $id" : "";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

// costos
function query_getAnios()
{
    $wpdb = query_getWPDB();
    $prefix = $wpdb->prefix;
    $sql = "SELECT id AS id_anio, descripcion as description FROM ${prefix}anio ORDER BY descripcion";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getMeses()
{
    $wpdb = query_getWPDB();
    $prefix = $wpdb->prefix;
    $sql = "SELECT id AS id_mes, descripcion as description FROM ${prefix}mes ORDER BY id";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_generateItemCostoByAnioMes($anio, $mes)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "INSERT INTO ${prefix}marken_costo_periodo (id_costo, id_anio, id_mes, created_at) SELECT t1.id, $anio,$mes,NOW() from ${prefix}marken_costos t1 
    WHERE t1.enabled = 1 AND NOT EXISTS (select * from ${prefix}marken_costo_periodo t2 
    WHERE t1.id = t2.id_costo AND t2.id_anio = $anio AND t2.id_mes = $mes)";
    $wpdb->query($sql);
    $wpdb->flush();
}
function query_SearchCostosByAnioMes($anio, $mes)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t2.descripcion, t1.valor FROM ${prefix}marken_costo_periodo t1
     INNER JOIN ${prefix}marken_costos t2 
     ON t1.id_costo = t2.id 
     WHERE t1.id_anio = $anio AND t1.id_mes = $mes AND t2.enabled = 1";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_SearchOthersCostosByAnioMes($anio, $mes)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t2.descripcion, t1.valor
    FROM wp_aldem_marken_costo_periodo t1
    INNER JOIN wp_aldem_marken_costos t2 ON t1.id_costo = t2.id
    WHERE t1.id_anio = $anio AND t1.id_mes = $mes AND t2.enabled = 0 AND t1.valor IS NOT NULL
    ";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

// fin de costos

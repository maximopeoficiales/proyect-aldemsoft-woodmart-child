<?php

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
    $sql = "SELECT id , descripcion FROM ${prefix}marken_transporte";
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
    $sql = "SELECT t2.descripcion, t1.valor,t1.id AS id FROM ${prefix}marken_costo_periodo t1
     INNER JOIN ${prefix}marken_costos t2 
     ON t1.id_costo = t2.id 
     WHERE t1.id_anio = $anio AND t1.id_mes = $mes AND t2.enabled = 1
     ORDER BY t1.valor DESC";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_SearchOthersCostosByAnioMes($anio, $mes)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t2.descripcion, t1.valor,t1.id AS id
    FROM ${prefix}marken_costo_periodo t1
    INNER JOIN ${prefix}marken_costos t2 ON t1.id_costo = t2.id
    WHERE t1.id_anio = $anio AND t1.id_mes = $mes AND t2.enabled = 0 AND t1.valor IS NOT NULL
    ORDER BY t1.valor DESC
    ";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getTotalCostoByAnioMes($anio, $mes)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT SUM( t1.valor)  as total
    FROM ${prefix}marken_costo_periodo t1
    INNER JOIN ${prefix}marken_costos t2 ON t1.id_costo = t2.id
    WHERE t1.id_anio = $anio AND t1.id_mes = $mes AND t1.VALOR IS NOT NULL
    ";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result[0]->total;
}


function query_getMarkenCostos()
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT t1.id as id_costo, t1.descripcion , t1.enabled
    FROM ${prefix}marken_costos t1  ORDER BY t1.enabled DESC
    ";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
// fin de costos


// reportes en marken export reporte general
function query_getMarkenReporteGeneral1()
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT date(t1.fecha_hora) AS `Recoleccion`,
    sum((case when ((`t3`.`id` = 1) and (`t4`.`id` = 1)) then 1 else 0 end)) AS `Lima_Amb`,
    sum((case when ((`t3`.`id` = 1) and (`t4`.`id` = 2)) then 1 else 0 end)) AS `Lima_Bio1`,
    sum((case when ((`t3`.`id` = 1) and (`t4`.`id` = 3)) then 1 else 0 end)) AS `Lima_Bio2`,
    sum((case when ((`t3`.`id` = 1) and (`t4`.`id` = 4)) then 1 else 0 end)) AS `Lima_Bio3`,
    sum((case when ((`t3`.`id` = 2) and (`t4`.`id` = 1)) then 1 else 0 end)) AS `Prov_Amb`,
    sum((case when ((`t3`.`id` = 2) and (`t4`.`id` = 2)) then 1 else 0 end)) AS `Prov_Bio1`,
    sum((case when ((`t3`.`id` = 2) and (`t4`.`id` = 3)) then 1 else 0 end)) AS `Prov_Bio2`,
    sum((case when ((`t3`.`id` = 2) and (`t4`.`id` = 4)) then 1 else 0 end)) AS `Prov_Bio3`,
    count(0) AS `Total Guías`,((100 * year(`t1`.`fecha_hora`)) + month(`t1`.`fecha_hora`)) AS `periodo` 
    from (((`${prefix}marken_job` `t1` 
    join `${prefix}marken_shipper` `t2` on((`t1`.`id_shipper` = `t2`.`id`))) 
    join `${prefix}marken_site` `t3` on((`t3`.`id` = `t2`.`id_marken_site`))) 
    join `${prefix}marken_type` `t4` on((`t4`.`id` = `t1`.`id_marken_type`))) 
    where t1.ind_activo <> 3
    group by 1,11";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}

function query_getMarkenReporteGeneral2()
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT date(t1.Recoleccion) as Recoleccion, 
    sum(t1.Lima_Amb * t2.precio) as Lima_Amb, sum(t1.Lima_Bio1*t3.precio) as Lima_Bio1, sum(t1.Lima_Bio2*t4.precio) as Lima_Bio2, sum(t1.Lima_Bio3*t5.precio) as Lima_Bio3,
    sum(t1.Prov_Amb * t6.precio) as Prov_Amb, sum(t1.Prov_Bio1*t7.precio) as Prov_Bio1, sum(t1.Prov_Bio2*t8.precio) as Prov_Bio2, sum(t1.Prov_Bio3*t9.precio) as Prov_Bio3,
    100 * year(t1.recoleccion) + month(t1.recoleccion) AS Periodo
    from ${prefix}marken_export_reporte_general1 t1
    inner join ${prefix}marken_export_tarifa_pickup t2 on t2.id = 3
    inner join ${prefix}marken_export_tarifa_pickup t3 on t3.id = 4
    inner join ${prefix}marken_export_tarifa_pickup t4 on t4.id = 5
    inner join ${prefix}marken_export_tarifa_pickup t5 on t5.id = 6
    inner join ${prefix}marken_export_tarifa_pickup t6 on t6.id = 8
    inner join ${prefix}marken_export_tarifa_pickup t7 on t7.id = 9
    inner join ${prefix}marken_export_tarifa_pickup t8 on t8.id = 10
    inner join ${prefix}marken_export_tarifa_pickup t9 on t9.id = 11
    group by 1,10";

    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}
function query_getMarkenExportReporte($periodo)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT
    t1.Recoleccion as Fecha, t1.Lima_Amb, t1.Lima_Bio1, t1.Lima_Bio2, t1.Lima_Bio3,
    t1.Prov_Amb, t1.Prov_Bio1, t1.Prov_Bio2, t1.Prov_Bio3, t1.`Total Guías`,
    t2.Lima_Amb, t2.Lima_Bio1, t2.Lima_Bio2, t2.Lima_Bio3,
    t2.Prov_Amb, t2.Prov_Bio1, t2.Prov_Bio2, t2.Prov_Bio3,
    t2.Lima_Amb+ t2.Lima_Bio1+ t2.Lima_Bio2+t2.Lima_Bio3+
    t2.Prov_Amb+t2.Prov_Bio1+ t2.Prov_Bio2+t2.Prov_Bio3 as `Total Pickup dolares`
    from ${prefix}marken_export_reporte_general1 as t1
    inner join ${prefix}marken_export_reporte_general2 as t2 on t1.Recoleccion = t2.Recoleccion
    where t1.periodo = $periodo";

    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return $result;
}


// Querys para la subida del csv
// devuelve true si existe el waybill
function query_existsWaybill(string  $job): bool
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT EXISTS(					
        SELECT *					
        FROM {$prefix}_marken_job					
        WHERE waybill = $job and id_cliente_subtipo = 1) as existe";
    $result = $wpdb->get_results($sql);
    $wpdb->flush();
    return intval($result[0]->existe) ? true : false;
}

// obtiene el idMarkenSite buscando por la descripcion
function query_getIdMarkenSiteByDescription(string $description): int
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT id_marken_site from {$prefix}_marken_site_data				
    where descripcion = %s";
    $result = $wpdb->get_results($wpdb->prepare($sql, $description));
    $wpdb->flush();
    return intval($result[0]->id_marken_site ?? 0);
}
function query_insertJobByCsv($idUser, $job, $remitente, $ciudad, $paisRemitente, $consignee, $paisConsignee, $fechaRecoleccion, $idMarkenSite): bool
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();

    $table = "{$prefix}marken_job";
    $data = [
        "waybill" => $job,
        "remitente" => $remitente,
        "ciudad" => $ciudad,
        "paisremitente" => $paisRemitente,
        "consignee" => $consignee,
        "paisconsignee" => $paisConsignee,
        "fecha_hora" => $fechaRecoleccion,

        "id_cliente_subtipo" => 1,
        "id_marken_site" => $idMarkenSite,
        "id_usuario_created" => $idUser,

        "updated_at" => date("Y-m-d H:i:s"),
        "created_at" => date("Y-m-d H:i:s"),
    ];

    $format = array(
        '%s', '%s', '%s', '%s', '%s', '%s', '%s',
        '%d',  '%d', '%d',
        '%s', '%s'
    );

    $queryExistoso = $wpdb->insert($table, $data, $format);
    $wpdb->flush();

    return $queryExistoso;
}

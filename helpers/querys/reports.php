<?php

function query_getMarkenCourierReporteGeneral1($periodo)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT t1.*,t2.Ingresos,t2.Egresos FROM `marken_courier_reporte_general1`as t1 JOIN
    marken_courier_reporte_general2 as t2 ON t1.id = t2.id  WHERE periodo = $periodo";
    $result = $wpdb->get_results($wpdb->prepare($sql));
    $wpdb->flush();
    return $result;
}
function query_getMarkenExportReporteGeneral1($periodo)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT * FROM `marken_export_reporte_general1` WHERE periodo = $periodo";
    $result = $wpdb->get_results($wpdb->prepare($sql));
    $wpdb->flush();
    return $result;
}

function query_getMarkenExportReporteGeneral2($periodo)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT * FROM `marken_export_reporte_general2` WHERE periodo = $periodo";
    $result = $wpdb->get_results($wpdb->prepare($sql));
    $wpdb->flush();
    return $result;
}
function query_getMarkenExportReporteGeneral5($periodo)
{
    $wpdb = query_getWPDB();
    $sql = "SELECT * FROM `marken_export_reporte_general5` WHERE periodo = $periodo";
    $result = $wpdb->get_results($wpdb->prepare($sql));
    $wpdb->flush();
    return $result;
}
function query_getCostoMarken($periodo)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT SUM(valor) AS total_costo_marken	
    from ${prefix}marken_costo_periodo t1	
    inner join wp_anio t2 on t2.id = t1.id_anio	
    inner join wp_mes t3 on t3.id = t1.id_mes	
    where t2.descripcion = left(%s,4) and t3.id = right(%s,2)";
    $result = $wpdb->get_results($wpdb->prepare($sql, $periodo, $periodo));
    $wpdb->flush();
    return $result[0];
}
function query_getTotalGuias($periodo)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT count(*) as total_guias
    from `${prefix}marken_job` `t1` 
    where t1.ind_activo <> 3
    and ((100 * year(`t1`.`fecha_hora`)) + month(`t1`.`fecha_hora`)) = %s";
    $result = $wpdb->get_results($wpdb->prepare($sql, $periodo));
    $wpdb->flush();
    return $result[0];
}
function query_getTotalGuiasExport($periodo)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT count(*) as total_guias_courier
    from `${prefix}marken_job` `t1` 
    where t1.ind_activo <> 3 and t1.id_cliente_subtipo = 3
    and ((100 * year(`t1`.`fecha_hora`)) + month(`t1`.`fecha_hora`)) = %s";
    $result = $wpdb->get_results($wpdb->prepare($sql, $periodo));
    $wpdb->flush();
    return $result[0];
}


function query_servicioTransportePorGuiaHija()
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT site, concat('hasta ',format(peso,2), ' kg') as peso,FORMAT(tarifa,2) as tarifa
    from ${prefix}marken_courier_tarifa
    where id in (1,2)";
    $result = $wpdb->get_results($wpdb->prepare($sql));
    $wpdb->flush();
    return $result;
}
function query_courierReportQueryC()
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT format(tarifa,2) as tarifa
    from ${prefix}marken_courier_tarifa
   where id in (3,4,5)";
    $result = $wpdb->get_results($wpdb->prepare($sql));
    $wpdb->flush();
    return $result;
}
function query_getCostoHandlingPorMaster()
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT concat(site,': $',tarifa) as tarifa
    from ${prefix}marken_courier_tarifa where id in (6,7,8)";
    $result = $wpdb->get_results($wpdb->prepare($sql));
    $wpdb->flush();
    return $result;
}

function query_getMarkenCourierReporteGeneral($periodo)
{
    $wpdb = query_getWPDB();
    $prefix = query_getAldemPrefix();
    $sql = "SELECT `t1`.`id`, `t1`.`manifiesto`,
     ifnull(concat(CAST(t1.dua AS CHAR),'-',CAST(t1.dua2 AS CHAR),'-',CAST(t1.dua3 AS CHAR),'-',t1.dua4),dua) AS `dua`
     , `t1`.`guia` AS `guia`
     ,`t1`.`schd_collection` AS `fecha_entrega`,`t1`.`guia_master` ,`t1`.`protocolo` , `t2`.`descripcion` AS `exportador`, `t1`.`importador`,`t1`.`pcs` AS `cantidad`,
     `t1`.`peso` AS `peso`, year(`t1`.`schd_collection`) * 100 + month(`t1`.`schd_collection`) AS `Periodo`
      from `${prefix}marken_job` `t1` 
      left join `${prefix}marken_shipper` `t2` on`t2`.`id` = `t1`.`id_exportador`
       where `t1`.`id_cliente_subtipo` = 3
    ";
    $result = $wpdb->get_results($wpdb->prepare($sql, $periodo));
    $wpdb->flush();
    return $result;
}

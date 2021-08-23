<?php
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

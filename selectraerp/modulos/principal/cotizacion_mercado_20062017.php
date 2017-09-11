<?php

$comunes = new Comunes();
$tabla = $name_form = "cotizacion_mercado";
//$tipob = @$_GET['tipo'];
//$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
//$busqueda = @$_GET['busqueda'];

/*if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];
        $busqueda = $_POST['busqueda'];
    }
    //$join = "as k inner join tipo_movimiento_almacen as t on k.tipo_movimiento_almacen=t.id_tipo_movimiento_almacen";
    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta_join($tabla, $des, $busqueda, $join, $orden);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas_join($tabla, $des, $busqueda);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera_join($tabla, $des, $busqueda, $join);
            break;
    }
    //$instruccion = $instruccion . " and operacion='+' order by id_transaccion";
    //exit(0);
} else {
    $instruccion = "SELECT * FROM $tabla ";
    //$instruccion = "SELECT * FROM $tabla as k inner join tipo_movimiento_almacen as t on k.tipo_movimiento_almacen=t.id_tipo_movimiento_almacen INNER JOIN conductores AS c ON k.id_conductor = c.id_conductor where operacion='+' order by id_transaccion";
}*/

$bdCentral= "selectrapyme_central";
$instruccion = "SELECT cm.id_estudio,cm.nro_cotizacion,cm.fecha,cm.observacion,cms.estatus_name,cmd.id_detalle_producto FROM $bdCentral.$tabla cm
INNER JOIN $bdCentral.cotizacion_mercado_estatus cms ON cms.id_estatus = cm.estatus_cotizacion
LEFT JOIN $bdCentral.cotizacion_mercado_detalle cmd ON cmd.id_estudio = cm.id_estudio
GROUP BY cm.id_estudio
ORDER BY cm.id_estudio DESC,cm.fecha_cotizacion DESC";

/*SELECT cm.id_estudio,cm.nro_cotizacion,cm.fecha,cm.observacion,cms.estatus_name,cm.retirado FROM $bdCentral.$tabla cm
INNER JOIN $bdCentral.cotizacion_mercado_estatus cms ON cms.id_estatus = cm.estatus_cotizacion
ORDER BY cm.id_estudio DESC,cm.fecha_cotizacion DESC*/

$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Nro de Cotización", "Fecha de Creación", "Observación de la Cotización", "Estatus de la Cotización"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);


/*$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);*/
$smarty->assign("cantidadFilas", $comunes->getFilas());


$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= " . $_GET["opt_seccion"]);
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
/*$smarty->assign("option_values", array("id_transaccion", "observacion", "id_almacen"));
$smarty->assign("option_output", array("Transaccion", "Observacion", "Almacen"));
$smarty->assign("option_selected", $busqueda);*/
//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************


$smarty->assign("mensaje", $comunes->Notificacion());
?>

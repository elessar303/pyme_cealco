<?php

$comunes = new Comunes();
$tabla = $name_form = "kardex_almacen";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];
        $busqueda = $_POST['busqueda'];
    }
    $join = "as k inner join tipo_movimiento_almacen as t on k.tipo_movimiento_almacen=t.id_tipo_movimiento_almacen inner join clientes as cli on k.id_cliente=cli.id_cliente";
    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta_join($tabla, $des, $busqueda, $join, $orden);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas_join($tabla, $des, $busqueda,$join);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera_join($tabla, $des, $busqueda, $join);
            break;
    }
    $instruccion = $instruccion . " and operacion='-' and (k.estado='Despachado'
    or k.estado='Facturado' or k.estado='Pendiente') and k.id_cliente<>0  group by  k.estado, cli.id_cliente order by cli.nombre,id_transaccion desc";
    //echo $instruccion; exit();
    //exit(0);
} else {
    $instruccion = "SELECT *, k.estado as estatus FROM $tabla AS k 
    INNER JOIN tipo_movimiento_almacen AS t 
    ON k.tipo_movimiento_almacen=t.id_tipo_movimiento_almacen 
    INNER JOIN clientes AS cli
    ON k.id_cliente=cli.id_cliente 
    WHERE operacion = '-' 
    and (k.estado='Pendiente'
    or k.estado='Facturado'
    or k.estado='Despachado')
    and k.id_cliente<>0
    group by   k.estado, cli.id_cliente
    order by cli.nombre,id_transaccion desc ";
}
//print_r($instruccion); exit();
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Transacci&oacute;n", "Cliente", "Fecha", "Autorizado Por", "Tipo de Movimiento", "Descripci&oacute;n", "Facturado"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);

$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = " . $_GET["opt_seccion"]);
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("cli.nombre", "id_transaccion"));
$smarty->assign("option_output", array("Cliente", "Transaccion"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//**************************************************************************
//**************************************************************************
//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************


$smarty->assign("mensaje", $comunes->Notificacion());
?>

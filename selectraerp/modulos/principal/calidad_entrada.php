<?php

$comunes = new Comunes();
$tabla = $name_form = "calidad_almacen";
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
    $join = "as k 
        LEFT JOIN tickets_entrada_salida AS tick ON k.id_ticket_entrada = tick.id
        LEFT JOIN transporte_conductores AS c ON tick.id_conductor = c.id
        LEFT JOIN clientes AS cli ON k.id_proveedor = cli.id_cliente ";
    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta_join($tabla, $des, $busqueda, $join, $orden);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas_join($tabla, $des, $busqueda, $join);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera_join($tabla, $des, $busqueda, $join);
            break;
    }
    $instruccion = $instruccion . " and tipo_acta=1 order by fecha_creacion desc";
    //exit(0);
} else {
    $instruccion = "SELECT * FROM $tabla as k 
    LEFT JOIN tickets_entrada_salida AS tick ON k.id_ticket_entrada = tick.id
    LEFT JOIN transporte_conductores AS c ON tick.id_conductor = c.id
    LEFT JOIN clientes AS cli ON k.id_proveedor = cli.id_cliente   where tipo_acta=1  order by fecha_creacion desc";
}

$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);
//echo $instruccion; exit();
$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Cod. Movimiento", "Fecha", "Autorizado Por", "Tipo de Movimiento", "Descripci&oacute;n", "Cliente", "Conductor", "C&eacute;dula"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);


$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());


$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= " . $_GET["opt_seccion"]);
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("cod_acta_calidad", "observacion", "almacen_procedencia"));
$smarty->assign("option_output", array("Cod. Movimiento", "Observacion", "Almacen"));
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

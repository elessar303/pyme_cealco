<?php

$comunes = new Comunes();

$tabla = "item";
$name_form = "item";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];
$string_cod_item_forma = "and cod_item_forma = 1"; //Productos

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];# "palabra" a buscar
        $busqueda = $_POST['busqueda'];# tipo de busqueda: por cod. item o por descripcion
    }

    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta_producto($des, $busqueda);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas_producto($tabla, $des, $busqueda, $string_cod_item_forma);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera_producto($des, $busqueda);
            break;
    }
} else {
    $instruccion = "SELECT *, (
SELECT ifnull( sum( cantidad ) , 0 )
FROM item_existencia_almacen
WHERE id_item = i.id_item) AS total_inventario from item as i where i.cod_item_forma = 1";
}

$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Cod. Item", "Descripci&oacute;n", "Precio", "Total Inventario"));
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
$smarty->assign("option_values", array("cod_item", "descripcion1"));
$smarty->assign("option_output", array("Cod. Item", "Descripción"));
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

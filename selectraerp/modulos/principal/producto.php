<?php

$comunes = new Comunes();

$tabla = $name_form = "item AS i, marca AS mc, unidad_medida AS um, grupo as grup";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];
$string_cod_item_forma = "and  i.unidadxpeso = um.id and i.id_marca=mc.id and i.cod_grupo=grup.cod_grupo"; //Productos

//$bdselectrapyme = "selectrapyme";

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar']; # "palabra" a buscar
        $busqueda = $_POST['busqueda']; # tipo de busqueda: por cod. item o por descripcion
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
    $instruccion=$instruccion. " and  i.unidadxpeso = um.id and i.id_marca=mc.id and i.cod_grupo=grup.cod_grupo order by descripcion1";
} else {
    $instruccion = "SELECT *, (
    SELECT ifnull( sum( cantidad ) , 0 )
    FROM item_existencia_almacen
    WHERE id_item = i.id_item) AS total_inventario FROM item AS i, marca AS mc, unidad_medida AS um WHERE i.cod_item_forma = 1 and  i.unidadxpeso = um.id and i.id_marca=mc.id AND estatus='A' order by i.descripcion1";
}
//echo $instruccion; exit();
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);
$smarty->assign("registros", $campos);

$parametros = $menu->ObtenerFilasBySqlSelect("SELECT moneda FROM parametros_generales;");
#$smarty->assign("cabecera", array("<td style='width:10%'>C&oacute;d. Item</td>", "<td style='width:15%'>C&oacute;d. Barras</td>", "<td style='width:35%'>Descripci&oacute;n</td>", "<td style='width:10%'>Precio</td>", "<td style='width:10%'>Total Inventario</td>", "<td style='width:10%'>Existencia M&iacute;n.</td>", "<td style='width:10%'>Existencia M&aacute;x.</td>"));
$smarty->assign("cabecera", array("C&oacute;digo", "C&oacute;d. Barras", "Descripci&oacute;n", "Precio sin IVA({$parametros[0]['moneda']})"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);

$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("codigo_barras", "descripcion1", "cod_item", "marca"));
$smarty->assign("option_output", array("C&oacute;d. Barras", "Descripci&oacute;n", "C&oacute;d. Item", "Marca"));
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

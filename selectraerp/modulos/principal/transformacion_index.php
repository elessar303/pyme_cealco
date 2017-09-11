<?php

$comunes = new Comunes();
$tabla = $name_form = "transformaciones";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];
$tabla = "transformaciones a, item b, usuarios as c";
if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];       
        $busqueda ="a.producto_id=b.id_item and a.usuario_id=c.usuario and ".$_POST['busqueda'];
        $columnas="a.id as id, b.descripcion1 as descripcion, c.usuario as usuario";
    }

    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta($tabla, $des, $busqueda,"",$columnas);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas($tabla, $des, $busqueda,"",$columnas);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera($tabla, $des, $busqueda,"",$columnas);
            break;
    }
} else {
    $instruccion = "SELECT a.id as id, b.descripcion1 as descripcion, c.usuario as usuario FROM transformaciones a, item b, usuarios as c where a.producto_id=b.id_item and a.usuario_id=c.usuario order by a.fecha_creacion desc";
    
}
//echo $instruccion; exit();
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Descripci&oacute;n", "Usuario"));
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
$smarty->assign("option_values", array("b.descripcion1","c.usuario"));
$smarty->assign("option_output", array("Descripci&oacute;n","Usuario"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//Nombre del Formulario*****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************
$smarty->assign("mensaje", $comunes->Notificacion());
?>

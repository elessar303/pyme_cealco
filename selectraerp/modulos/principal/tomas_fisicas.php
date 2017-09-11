<?php

$comunes = new Comunes();
$tabla = $name_form = "tomas_fisicas";
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
    $join = "as t inner join tomas_fisicas_detalle as td on t.id=td.id_mov inner join usuarios as u on t.id_usuario=u.cod_usuario inner join item as i on td.cod_bar=i.codigo_barras inner join tipo_toma as tip on t.tipo_toma=tip.id  inner join ubicacion as ubi on t.id_ubicacion=ubi.id group by t.id order by t.id desc";
    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta_join($tabla, $des, $busqueda, $join);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas_join($tabla, $des, $busqueda,$join);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera_join($tabla, $des, $busqueda, $join);
            break;
    }
    $instruccion = $instruccion . "group by t.id order by fecha_apertura desc";
    //exit(0);
} else {
    $instruccion = "SELECT * FROM $tabla as t inner join tomas_fisicas_detalle as td on t.id=td.id_mov inner join usuarios as u on t.id_usuario=u.cod_usuario inner join item as i on td.cod_bar=i.codigo_barras inner join tipo_toma as tip on t.tipo_toma=tip.id  inner join ubicacion as ubi on t.id_ubicacion=ubi.id group by t.id order by t.id desc";
}



$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Nro Toma F&iacute;sica", "Ubicacion", "Fecha de Apertura", "Tipo de Toma", "Usuario"));
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
$smarty->assign("option_values", array("t.id", "tip.descripcion", "u.nombreyapellido"));
$smarty->assign("option_output", array("Nro Toma F&iacute;sica", "Tipo de Toma", "Usuario"));
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

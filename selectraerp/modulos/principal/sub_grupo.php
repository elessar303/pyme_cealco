<?php

$comunes = new Comunes();
$tabla = $name_form = "grupo";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];
$tabla = "grupo g, departamentos d";
if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];       
        $busqueda ="g.id_rubro=d.cod_departamento and ".$_POST['busqueda'];
        $columnas="g.cod_grupo,g.descripcion ,d.descripcion as rubro";
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
    // $instruccion = "SELECT * FROM $tabla ";
    $instruccion = "SELECT g.cod_grupo,g.descripcion ,d.descripcion as rubro FROM grupo g, departamentos d where d.cod_departamentoSUBSTR(g.id_rubro, 1, 4)";
    
}
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("C&oacute;digo", "Descripci&oacute;n","Rubro"));
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
$smarty->assign("option_values", array("g.cod_grupo", "g.descripcion","d.descripcion"));
$smarty->assign("option_output", array("C&oacute;d. Grupo", "Descripci&oacute;n","Rubro"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//Nombre del Formulario*****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************
$smarty->assign("mensaje", $comunes->Notificacion());
?>

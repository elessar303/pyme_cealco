<?php

$comunes = new Comunes();
$tabla = $name_form = "selectrapyme_central.kardex_13_17 k, selectrapyme_central.puntos_venta pv";
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
    $join = "";
    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta($tabla, $des, $busqueda);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas($tabla, $des, $busqueda);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera($tabla, $des, $busqueda);
            break;
    }
    
    $instruccion = $instruccion . " and tipo_movimiento='Cargo' and k.codigo_siga=SUBSTRING(pv.codigo_siga_punto,4) group by cod_movimiento order by fecha desc";
    //echo $instruccion; exit();
    //exit(0);
} else {
    $instruccion = "";
}
//echo $instruccion; exit();
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Doc. Siscol", "Fecha", "Descripci&oacute;n", "RIF Proveedor","Nombre Proveedor", "Documento"));
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
$smarty->assign("option_values", array("cod_movimiento", "fecha", "id_documento", "rif", "guia_sunagro","nombre_proveedor","nombre_punto"));
$smarty->assign("option_output", array("Doc. Siscol", "Fecha", "Documento", "Rif Proveedor", "Guia Sunagro", "Nombre Proveedor", "Almacen"));
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


$smarty->assign("mensaje", "Ingrese los datos a buscar");
?>

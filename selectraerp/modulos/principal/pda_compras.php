<?php

$comunes = new Comunes();
$login = new Login();
$tabla = $name_form = "pda_maestro";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];
$dep=$login->getDepartamento();

if($dep=!'1' || $dep=!'53'){
     echo "<script type='text/javascript'>
                alert('Error, Usted no tiene permisos para ver este modulo');
                history.back(-1);
            </script>";
            //header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
            exit();
}

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];
        $busqueda = $_POST['busqueda'];
    }
     $join = "as k inner join proveedores as t on k.id_proveedor=t.id_proveedor";
    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta_join($tabla, $des, $busqueda, $join);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas_join($tabla, $des, $busqueda, $join);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera_join($tabla, $des, $busqueda, $join);
            break;
    }
} else {
    $instruccion = "SELECT  pda_maestro.id as id, orden_compra, proveedores.descripcion as descripcion FROM ". $tabla.", proveedores where pda_maestro.id_proveedor=proveedores.id_proveedor order by pda_maestro.id desc
        
        ";
        //echo $instruccion; exit();

}
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$data_parametros = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
//$smarty->assign("idfiscal",$data_parametros);

foreach ($data_parametros as $key => $item) {
    $valueSELECT[] = $item["cod_empresa"];
    $outputidfiscalSELECT[] = $item["id_fiscal"];
}

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("OC", "Proveedor"));
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
$smarty->assign("option_values", array("orden_compra", "descripcion"));
$smarty->assign("option_output", array("Orden de Compra", "Proveedor"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************
$smarty->assign("mensaje", $comunes->Notificacion());
?>
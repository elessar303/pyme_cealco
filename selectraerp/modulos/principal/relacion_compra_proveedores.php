<?php

$comunes = new Comunes();
$tabla = $name_form = "vw_relacion_factura_cliente";
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
} else {
    $instruccion = "select compra.id_compra, compra.cod_compra, compra.cod_estatus, compra.TotalTotalcompra, prov.descripcion as nombre from compra inner join proveedores prov on (compra.id_proveedor=prov.id_proveedor)";
}
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("ID", "C&oacute;d. Compra", "Nombre del Proveedor","Monto" ,"Estado"));
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
$smarty->assign("option_values", array("cod_compra", "descripcion"));
$smarty->assign("option_output", array("Cod. Compra", "Proveedor"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//**************************************************************************
//**************************************************************************
//**************************************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************
$smarty->assign("mensaje", $comunes->Notificacion());
?>

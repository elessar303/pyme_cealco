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
    $instruccion = "SELECT * FROM $tabla ORDER BY id_factura desc";
}

$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Referencia", "C&oacute;d. Factura", "Nombre del Cliente", "RIF / CI", "Fecha", "Monto", "Estado"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);

$parametros_facturacion = $comunes->ObtenerFilasBySqlSelect("SELECT tipo_facturacion FROM parametros_generales;");
$smarty->assign("reporte_factura", $parametros_facturacion[0]['tipo_facturacion'] == 2 ? "rpt_factura_formato_libre.php" : "rpt_factura.php");

$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo= {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);
//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("rif","cod_factura", "nombre"));
$smarty->assign("option_output", array("RIF","Cod. Factura", "Cliente"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************
$smarty->assign("mensaje", $comunes->Notificacion());
?>

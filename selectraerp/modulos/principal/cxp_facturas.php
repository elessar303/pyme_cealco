<?php

$comunes = new Comunes();
$tabla = "cxp_factura";
$name_form = "cxp_factura";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];
$edoCta = @$_GET['cod2'];
$cod = @$_GET['cod'];

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
    $instruccion = "SELECT *, (SELECT SUM(monto_retenido) FROM cxp_factura_detalle WHERE id_factura_fk = cxpf.id_factura) AS montoretislr FROM cxp_factura cxpf LEFT JOIN cxp_factura_pago cxpfp ON (cxpf.id_factura = cxpfp.cxp_factura_fk) WHERE cxpf.id_cxp_edocta = '{$edoCta}'";
}

$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("cod", $cod);
$smarty->assign("edoCta", $edoCta);
$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("N&uacute;mero de Fact. / NC", "N&uacute;mero de Control", "Fecha Fact. / NC", "Fecha Recepci&oacute;n", "Monto a Pagar"));
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
$smarty->assign("option_values", array("cod_factura", "cod_cont_factura", "fecha_factura", "Fecha_recepcion"));
$smarty->assign("option_output", array("N&uacute;mero de Fact. / NC", "N&uacute;mero de Control", "Fecha Fact. / NC", "Fecha Recepci&oacute;n"));
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

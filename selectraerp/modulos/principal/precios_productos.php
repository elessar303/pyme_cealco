<?php

$comunes = new Comunes();

$tabla = "sincronizacion_productos a, sincronizacion_productos_detalle b, item c";
$name_form = "sincronizacion_productos_detalle";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];
        $busqueda = "b.estatus=1 and a.id=b.id_sincro and b.codigo_barra=c.codigo_barras and ".$_POST['busqueda'];
    }
    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta($tabla, $des, $busqueda, "","*" );
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas($tabla, $des, $busqueda, "","*" );
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera($tabla, $des, $busqueda, "","*");
            break;
    }
} else {
    $instruccion = "SELECT b.id, b.codigo_barra, c.descripcion1,  b.precio, a.nombre_archivo, b.id_sincro, c.coniva1, a.fecha FROM " . $tabla." where b.estatus=1 and a.id=b.id_sincro and b.codigo_barra=c.codigo_barras order by a.id desc";
}
// as prov INNER JOIN tipo_proveedor_clasif as ti ON (prov.clase_proveedor = ti.id_pclasif)

$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$data_parametros = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
//$smarty->assign("idfiscal",$data_parametros);
$valueSELECT = $outputidfiscalSELECT = array();
foreach ($data_parametros as $key => $item) {
    $valueSELECT[] = $item["cod_empresa"];
    $outputidfiscalSELECT[] = $item["id_fiscal"];
}

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("C&oacute;digo de Barras", "Nombre", "Precio", "Archivo", "Fecha"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);

$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo = " . $_GET["opt_seccion"]);
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("codigo_barra"));
$smarty->assign("option_output", array("C&oacute;digo de Barras"));
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
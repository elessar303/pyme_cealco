<?php

$comunes = new Comunes();
$tabla = "calidad_visitas";
$name_form = "calidad_visitas";
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
$join = "as a inner join tipo_visitas as b on a.tipo_visita=b.id inner join usuarios as c on a.usuario=c.cod_usuario";
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
    $instruccion = "SELECT a.id, a.cod_acta_visita, b.descripcion_visita, c.nombreyapellido FROM calidad_visitas as a, tipo_visitas as b, usuarios as c where a.tipo_visita=b.id and c.cod_usuario=a.usuario";
}

//echo $instruccion; exit();
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

//CONSULTA DE ID FISCAL EN PARAMETROS

$data_parametros = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
//$smarty->assign("idfiscal",$data_parametros);

foreach ($data_parametros as $key => $item) {
    $valueSELECT[] = $item["cod_empresa"];
    $outputidfiscalSELECT[] = $item["id_fiscal"];
}

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("C&oacute;d. Acta", "Nombre y Apellido Responsable", "CI Responsable" , "Tel&eacute;fonos", "Tipo Visita"));
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
$smarty->assign("option_values", array("cod_acta_visita", "cedula_persona_visita", "descripcion_visita"));
$smarty->assign("option_output", array("Codigo Acta", "C.I", "Tipo Visita"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
$smarty->assign("mensaje", $comunes->Notificacion());
?>

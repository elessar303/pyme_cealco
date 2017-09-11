<?php

$comunes = new Comunes();

$tabla2="selectrapyme.unidades";
$tabla3="selectrapyme.usuarios";
$tabla = $name_form = "selectrapyme_central.votacion, selectrapyme.unidades, selectrapyme.usuarios";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];       
        $busqueda =$_POST['busqueda'];
        $columnas="*";
    }

    switch ($tipob) {
        case "exacta":
            echo $instruccion = $comunes->buscar_exacta($tabla, $des, $busqueda,"and unidades.cod_unidad=".$_SESSION['tipo_departamento']." AND unidades.descripcion=votacion.gerencia group by cedula_emp",$columnas);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas($tabla, $des, $busqueda,"and unidades.cod_unidad=".$_SESSION['tipo_departamento']." AND unidades.descripcion=votacion.gerencia group by cedula_emp",$columnas);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera($tabla, $des, $busqueda,"and unidades.cod_unidad=".$_SESSION['tipo_departamento']." AND unidades.descripcion=votacion.gerencia group by cedula_emp",$columnas);
            break;
    }
}else {
    $instruccion = "SELECT * FROM $tabla where unidades.cod_unidad=".$_SESSION['tipo_departamento']." AND unidades.descripcion=votacion.gerencia group by cedula_emp";
}
mysql_set_charset('utf8');
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);
$smarty->assign("registros", $campos);

$parametros = $menu->ObtenerFilasBySqlSelect("SELECT moneda FROM parametros_generales;");
#$smarty->assign("cabecera", array("<td style='width:10%'>C&oacute;d. Item</td>", "<td style='width:15%'>C&oacute;d. Barras</td>", "<td style='width:35%'>Descripci&oacute;n</td>", "<td style='width:10%'>Precio</td>", "<td style='width:10%'>Total Inventario</td>", "<td style='width:10%'>Existencia M&iacute;n.</td>", "<td style='width:10%'>Existencia M&aacute;x.</td>"));
$smarty->assign("cabecera", array("ID", "Cédula", "Nombres y Apellidos", "Gerencia", "Estado donde Vota", "Miembro de Mesa", "Fecha de Votación"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);

$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("cedula_emp"));
$smarty->assign("option_output", array("Ced. Empleado"));
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

if(isset($_POST["enviar"])){

$hora=$_POST["hora"];
$min=$_POST["min"];
$ampm=$_POST["ampm"];
$id=$_POST["id"];
if($ampm=="PM"){
$hora=$hora+12;
}
$min=sprintf("%02d", $min);
$hora=sprintf("%02d", $hora);
$sql="UPDATE $tabla set hora_vota='2015-12-06 ".$hora.":".$min.":00' WHERE id=".$id."";
$update = $comunes->Execute2($sql);
header("http://192.168.15.2/pyme/selectraerp/modulos/principal/?opt_menu=7&opt_seccion=226");


}
?>

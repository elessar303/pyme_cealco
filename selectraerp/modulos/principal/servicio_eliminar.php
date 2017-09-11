<?php

include("../../libs/php/clases/producto.php");

$productos = new Producto();
$campos_almacen = $productos->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("campos_almacen" , $campos_almacen );


if(isset($_POST["aceptar"]))
{

	$campos_item = $productos->ObtenerFilasBySqlSelect("select id_item from item where cod_item = '".$_POST["cod_item"]."'");
	$instruccion="delete from servicios_islr where cod_item='".$campos_item[0]["id_item"]."'";
	$productos->Execute2($instruccion);

	$instruccion="delete from item where cod_item = '".$_POST["cod_item"]."'";
	$productos->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


$campos_item = $productos->ObtenerFilasBySqlSelect("select * from item where id_item = ".$_GET["cod"]);
$smarty->assign("campos_item",$campos_item);

// Cargando departamentos en combo select
$campos_comunes = $productos->ObtenerFilasBySqlSelect("select * from departamentos");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_departamento"];
}
$smarty->assign("option_values_departamentos",$arraySelectOption);
$smarty->assign("option_output_departamentos",$arraySelectoutPut);
$smarty->assign("option_select_departamentos",$campos_item[0]["cod_departamento"]);

// Cargando grupo en combo select
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("select * from grupo");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_grupo"];
}
$smarty->assign("option_values_grupo",$arraySelectOption);
$smarty->assign("option_output_grupo",$arraySelectoutPut);
$smarty->assign("option_select_grupo",$campos_item[0]["cod_grupo"]);

// Cargando Linea en combo select
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("select * from linea");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_linea"];
}
$smarty->assign("option_values_linea",$arraySelectOption);
$smarty->assign("option_output_linea",$arraySelectoutPut);
$smarty->assign("option_select_linea",$campos_item[0]["cod_linea"]);

//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $productos->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("parametros_generales",$parametros_generales );

//Cargar Almacenes
$almacenes= $productos->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("almacenes",$almacenes );



?>

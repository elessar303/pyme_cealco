<?php
include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();
$comunes = new Comunes();
$punto = $comunes->ObtenerFilasBySqlSelect("SELECT nombre_estado as nombre, codigo_estado as id  from ".DB_SELECTRA_PYMEPP.".estados");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($punto as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["nombre"]);
}

$smarty->assign("option_values_estado", $arraySelectOption);
$smarty->assign("option_output_estado", $arraySelectOutPut1);

if($_POST['posee_vehiculo']!=1)
{
	$_POST['vehiculos']=NULL;
}
if(isset($_POST["nombres"])){

	$instruccion = "
	INSERT INTO 
	`transporte_conductores`
	(`cedula`, `nombres`, `apellidos`, `telefono`)
	VALUES
	(
	'".$_POST["cedula"]."',
	'".$_POST["nombres"]."',
	'".$_POST["apellidos"]."',
	'".$_POST["telefono"]."'
	);";
	    
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= ".$_GET["opt_seccion"]);
$smarty->assign("campo_seccion",$campos);
?>

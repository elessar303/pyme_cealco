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

$flota_vehicular=$comunes->ObtenerFilasBySqlSelect("Select id, concat(placa,'--',marca,'--',modelo,'--',unidad) as descripcion from transporte_camion");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($flota_vehicular as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion"]);
}

$smarty->assign("option_values_flota", $arraySelectOption);
$smarty->assign("option_output_flota", $arraySelectOutPut1);
if($_POST['posee_vehiculo']!=1)
{
	$_POST['vehiculos']=NULL;
}
if(isset($_POST["nombres"])){

	$instruccion = "
	INSERT INTO 
	`transporte_conductores`
	(`cedula`, `nombres`, `apellidos`, `telefono`, `flota_asignado`, `id_camion`)
	VALUES
	(
	'".$_POST["cedula"]."',
	'".$_POST["nombres"]."',
	'".$_POST["apellidos"]."',
	'".$_POST["telefono"]."',
	'".$_POST["flota_asignada_conductor"]."',
	'".$_POST['vehiculos']."'
	);";
	    
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>

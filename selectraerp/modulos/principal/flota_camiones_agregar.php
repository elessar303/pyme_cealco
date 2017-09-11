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

//booleanos
$booleanosOption[]=1;
$booleanosOption[]=0;
$booleanosOuput[]="SI";
$booleanosOuput[]="NO";
$smarty->assign("option_values_booleano", $booleanosOption);
$smarty->assign("option_output_booleano", $booleanosOuput);
//estatus Vehiculo
$vehiculo_estatus = $comunes->ObtenerFilasBySqlSelect("SELECT id, descripcion  from estatus_vehiculo");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($vehiculo_estatus as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion"]);
}

$smarty->assign("option_values_estatus_vehiculo", $arraySelectOption);
$smarty->assign("option_output_estatus_vehiculo", $arraySelectOutPut1);

//marca
$vehiculo_marca = $comunes->ObtenerFilasBySqlSelect("SELECT id, descripcion  from transporte_marca");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($vehiculo_marca as $key => $puntos)
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion"]);
}

$smarty->assign("option_values_marca_vehiculo", $arraySelectOption);
$smarty->assign("option_output_marca_vehiculo", $arraySelectOutPut1);
//

//tipo vehiculo
$vehiculo_tipo = $comunes->ObtenerFilasBySqlSelect("SELECT id, descripcion  from tipo_vehiculo");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($vehiculo_tipo as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion"]);
}

$smarty->assign("option_values_tipo_vehiculo", $arraySelectOption);
$smarty->assign("option_output_tipo_vehiculo", $arraySelectOutPut1);


if(isset($_POST["placa"])){
	$date = new DateTime($_POST['fecha_kilometraje']);
	
	$instruccion = "
	INSERT INTO `transporte_camion`(`unidad`, `placa`, `serial_motor`, `serial_carroceria`,  `marca`, `modelo`, `anio_vehiculo`, `cantidad_ejes`, `posee_caucho_repuesto`, `posee_herramientas`, `ultimo_kilometraje`, `fecha_kilometraje`, `serial_gps`, `alias_gps`, `estatus_vehiculo`, `flota_asignada`, `tipo_vehiculo`, `capacidad_carga_ton`, `vehiculo_recuperado`, `vehiculo_propio`)
	VALUES
	(
	'".$_POST["unidad"]."',
	'".$_POST["placa"]."',
	'".$_POST["serial_motor"]."',
	'".$_POST["serial_carroceria"]."',
	'".$_POST["marca"]."',
	'".$_POST["modelo"]."',
	'".$_POST["anio_vehiculo"]."',
	'".$_POST["cantidad_ejes"]."',
	'".$_POST["posee_caucho"]."',
	'".$_POST["herramientas"]."',
	'".$_POST["ultimo_kilometraje"]."',
	'".$date->format('Y-m-d')."',
	'".$_POST["serial_gps"]."',
	'".$_POST["alias_gps"]."',
	'".$_POST["estatus_vehiculo"]."',
	'".$_POST["flota_asignada"]."',
	'".$_POST["tipo_vehiculo"]."',
	'".$_POST["capacidad"]."',
	'".$_POST["vehiculo_recuperado"]."',
	'".$_POST["vehiculo_propio"]."'
	);";
	    
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>

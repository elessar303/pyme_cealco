<?php
include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();
$comunes = new Comunes();
$campos="";
if (isset($_GET["cod"])) {
    $campos = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM transporte_conductores WHERE id = " . $_GET["cod"]);
    $smarty->assign("datos", $campos);
    $smarty->assign("estado", $campos[0]['flota_asignado']);
    $smarty->assign("id", $_GET["cod"]);
}

$punto = $comunes->ObtenerFilasBySqlSelect("SELECT nombre_estado as nombre, codigo_estado as id  from ".DB_SELECTRA_PYMEPP.".estados");
$arraySelectOption="";
$arraySelectOutPut1="";

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
$smarty->assign("id_camion", $campos[0]['id_camion']);
if($campos[0]['id_camion']!=0)
{
	$smarty->assign("respuesta", 1);
}
else
{
	$smarty->assign("respuesta", 0);	
}
foreach ($punto as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["nombre"]);
}

$smarty->assign("option_values_estado", $arraySelectOption);
$smarty->assign("option_output_estado", $arraySelectOutPut1);



$arraySelection[] = 'NO';
$arrayvalue[] = 0;
$arraySelection[] = 'SI';
$arrayvalue[] = 1;
$smarty->assign("option_values_pvehiculo", $arrayvalue);
$smarty->assign("option_output_pvehiculo", $arraySelection);



if(isset($_POST["nombres"]))
{
	if($_POST['posee_vehiculo']!=1)
	{
		$_POST['vehiculos']=NULL;
	}

	$instruccion = "
	update  
	`transporte_conductores`
	set 
	`cedula`='".$_POST["cedula"]."',
	`nombres`='".$_POST["nombres"]."',
	`apellidos`='".$_POST["apellidos"]."',
	`telefono`='".$_POST["telefono"]."',
	`flota_asignado`='".$_POST["flota_asignada_conductor"]."',
	`id_camion`='".$_POST['vehiculos']."'
	where 
	id=".$_POST['id'];
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>

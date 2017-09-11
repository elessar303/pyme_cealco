<?php
include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();
$comunes = new Comunes();

if(isset($_GET['cod']))
{
	$sql="select * from transporte_modelo where id=".$_GET['cod'];
	$datos=$comunes->ObtenerFilasBySqlSelect($sql);
	$smarty->assign("datos", $datos);
	$smarty->assign("id", $_GET['cod']);
}
//marca
$vehiculo_marca = $comunes->ObtenerFilasBySqlSelect("SELECT id, descripcion  from transporte_marca");

$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($vehiculo_marca as $key => $puntos)
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion"]);
}

$smarty->assign("option_values_marca", $arraySelectOption);
$smarty->assign("option_output_marca", $arraySelectOutPut1);
$smarty->assign("marca", $datos[0]['id_marca']);


if(isset($_POST["descripcion"]))
{
	
	$instruccion = "
	update  `transporte_modelo`
	set 
	`descripcion_modelo`='".$_POST["descripcion"]."',
	`id_marca`='".$_POST["marca"]."'
	where
	id=".$_POST['id'];
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>

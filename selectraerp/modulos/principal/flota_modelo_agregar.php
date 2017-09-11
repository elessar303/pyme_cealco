<?php
include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();
$comunes = new Comunes();


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



if(isset($_POST["descripcion"]))
{
	
	$instruccion = "
	insert into transporte_modelo (id_marca, descripcion_modelo)  values 
	('".$_POST["marca"]."','".$_POST["descripcion"]."')
	";
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>

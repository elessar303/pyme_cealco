<?php
include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();
$comunes = new Comunes();



if(isset($_POST["descripcion"]))
{
	
	$instruccion = "
	insert into  `tipo_vehiculo`
	(descripcion)
	values
	('".$_POST["descripcion"]."')
	";
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>

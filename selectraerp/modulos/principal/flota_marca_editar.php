<?php
include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();
$comunes = new Comunes();

if(isset($_GET['cod']))
{
	$sql="select * from transporte_marca where id=".$_GET['cod'];
	$datos=$comunes->ObtenerFilasBySqlSelect($sql);
	$smarty->assign("datos", $datos);
	$smarty->assign("id", $_GET['cod']);
}


if(isset($_POST["descripcion"]))
{
	
	$instruccion = "
	update  `transporte_marca`
	set 
	`descripcion`='".$_POST["descripcion"]."'
	where
	id=".$_POST['id'];
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>

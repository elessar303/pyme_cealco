<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE tasas_cambio set
`fecha` = str_to_date('".$_POST["fecha"]."','%d/%m/%Y'), `tasa` = '".$_POST["tasa"]."' WHERE id = ".$_POST["id"]; 
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}



$campos = $banco->ObtenerFilasBySqlSelect("select id, date_format(fecha,'%d/%m/%Y') as fecha,tasa from tasas_cambio  WHERE id = ".$_GET["id"]);

$smarty->assign("id",$campos[0]['id']);
$smarty->assign("fecha",$campos[0]['fecha']);
$smarty->assign("tasa",$campos[0]['tasa']);




?>

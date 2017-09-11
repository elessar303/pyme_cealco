<?php
include("../../libs/php/clases/responsable.php");

$responsable = new Responsable();
if(isset($_POST["aceptar"])){
$instruccion = "delete from responsable  where cod_responsable = '".$_POST["cod_responsable"]."'";
$responsable->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

if(isset($_GET["cod"])){
$campos = $responsable->ObtenerFilasBySqlSelect("select * from responsable where cod_responsable = ".$_GET["cod"]);
$smarty->assign("datos_respontable",$campos);
}

?>

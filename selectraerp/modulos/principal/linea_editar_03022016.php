<?php
include("../../libs/php/clases/departamento.php");
$departamento = new Departamento();
if(isset($_POST["aceptar"])){
$instruccion = "
update linea set descripcion = '".$_POST["descripcion_linea"]."'
 where cod_linea = ".$_POST["cod_linea"];
$departamento->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $departamento->ObtenerFilasBySqlSelect("select * from linea where cod_linea = ".$_GET["cod"]);
$smarty->assign("campo_linea",$campos);
}


?>
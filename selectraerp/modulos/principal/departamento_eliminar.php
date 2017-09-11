<?php
include("../../libs/php/clases/departamento.php");
$departamento = new Departamento();
if(isset($_POST["aceptar"])){
$instruccion = "
delete from  departamentos where cod_departamento = ".$_POST["cod_departamento"];
$departamento->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $departamento->ObtenerFilasBySqlSelect("select * from departamentos where cod_departamento = ".$_GET["cod"]);
$smarty->assign("datos_departamento",$campos);
}


?>
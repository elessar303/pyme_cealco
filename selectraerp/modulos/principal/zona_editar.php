<?php
include("../../libs/php/clases/zona.php");
$zona = new Zona();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE zonas set
`descripcion` = '".$_POST["descripcion_zona"]."' WHERE cod_zona = ".$_POST["cod_zona"];
$zona->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $zona->ObtenerFilasBySqlSelect("select * from zonas where cod_zona = ".$_GET["cod"]);
$smarty->assign("datos_zona",$campos);
}

?>
<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["eliminar"])){


$instruccion = "delete from zonas WHERE cod_zona = ".$_POST["cod_zona"];
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from zonas where cod_zona = ".$_GET["cod"]);
$smarty->assign("datos_zona",$campos);
}

?>
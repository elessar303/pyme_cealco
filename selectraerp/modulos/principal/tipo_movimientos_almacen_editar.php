<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE tipo_movimiento_almacen set
`descripcion` = '".$_POST["descripcion"]."', `operacion` = '".$_POST["operacion"]."' WHERE id_tipo_movimiento_almacen = ".$_POST["id_tipo_movimiento_almacen"];
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from tipo_movimiento_almacen where id_tipo_movimiento_almacen = ".$_GET["cod"]);
$smarty->assign("datos_almacen",$campos);
}

?>
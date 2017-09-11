<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE ministerio set
`descripcion` = '".$_POST["descripcion_ministerio"]."' WHERE id = ".$_POST["id_ministerio"];
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["id"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from ministerio where id = ".$_GET["id"]);
$smarty->assign("datos_ministerio",$campos);
}

?>
<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE item_serial set
`serial` = '".$_POST["serial"]."' WHERE id = ".$_POST["id_serial"];
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=serial&cod=".$_POST["id_prod"]);
}


if(isset($_GET["id"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from item_serial where id = ".$_GET["id"]);
$smarty->assign("datos_serial",$campos);
}

?>
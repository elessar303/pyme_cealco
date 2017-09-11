<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["eliminar"])){


$instruccion = "delete from region WHERE id = ".$_POST["id_region"];
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["id"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from region where id = ".$_GET["id"]);
$smarty->assign("datos_region",$campos);
}

?>
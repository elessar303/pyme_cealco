<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO `item_serial` (
`id_producto`,
`serial`,
`estado`
)
VALUES (
 '".$_POST["id_prod"]."','".$_POST["serial"]."',1);";
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=".$_POST["opt_subseccion"]."&cod=".$_POST["id_prod"]);
}


?>

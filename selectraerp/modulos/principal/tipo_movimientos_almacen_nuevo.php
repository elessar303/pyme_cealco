<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO `tipo_movimiento_almacen` (
`id_tipo_movimiento_almacen` ,
`descripcion`,
`operacion`
)
VALUES (
NULL , '".$_POST["descripcion"]."', '".$_POST["operacion"]."'
);
";
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


?>

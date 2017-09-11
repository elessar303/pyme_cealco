<?php
include("../../libs/php/clases/almacen.php");
$islr = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO impuesto_iva (
`descripcion`,
`iva`,
`porcentaje`,
`fecha_creacion`,
`usuario_creacion`
)
VALUES (
'".$_POST["descripcion"]."',
'".$_POST["iva"]."',
'".$_POST["porcentaje"]."',
CURRENT_TIMESTAMP,
'".$login->getUsuario()."'
);
";

$islr->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


?>

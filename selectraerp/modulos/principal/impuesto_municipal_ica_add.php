<?php
include("../../libs/php/clases/almacen.php");
$islr = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO impuesto_ica (
`cod_impuesto_ica`,
`actividad`,
`agrupacion`,
`cod_actividad_ciu`,
`tarifa`,
`descripcion`,
`fecha_creacion`,
`usuario_creacion`
)
VALUES (
'".$_POST["cod_impuesto_ica"]."',
'".$_POST["actividad"]."',
'".$_POST["agrupacion"]."',
'".$_POST["cod_actividad_ciu"]."',
'".$_POST["tarifa"]."',
'".$_POST["descripcion"]."',
CURRENT_TIMESTAMP,
'".$login->getUsuario()."'
);
";

$islr->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


?>

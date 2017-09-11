<?php
include("../../libs/php/clases/responsable.php");
$responsable = new Responsable();
if(isset($_POST["aceptar"])){

$instruccion = "
INSERT INTO responsable (
`cod_responsable` ,
`responsable` ,
`usuario_creacion` ,
`fecha_creacion`
)
VALUES (
NULL , '".$_POST["responsable"]."', '".$login->getUsuario()."',
CURRENT_TIMESTAMP
);";
$responsable->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>

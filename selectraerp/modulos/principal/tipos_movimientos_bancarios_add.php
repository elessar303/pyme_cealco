<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["aceptar"])){
$instruccion = "
INSERT INTO tipo_movimientos_ban (
`cod_tipo_movimientos_ban` ,
`descripcion`
)
VALUES (
NULL , '".$_POST["descripcion"]."'
);
";
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


?>

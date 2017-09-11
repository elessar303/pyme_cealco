<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["aceptar"])){
$instruccion = "
INSERT INTO especialidades_proveedor (
`cod_especialidad` ,
`especialidad`
)
VALUES (
NULL , '".$_POST["especialidad"]."'
);
";
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


?>

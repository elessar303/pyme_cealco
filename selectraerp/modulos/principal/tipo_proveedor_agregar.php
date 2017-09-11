<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["aceptar"])){
$instruccion = "
INSERT INTO tipo_proveedor_clasif (
`id_pclasif` ,
`clasificacion`
)
VALUES (
NULL , '".$_POST["clasificacion"]."'
);
";
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


?>

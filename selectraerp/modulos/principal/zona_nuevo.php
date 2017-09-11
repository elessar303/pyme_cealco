<?php
include("../../libs/php/clases/zona.php");
$zona = new Zona();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO zonas (
`cod_zona` ,
`descripcion`
)
VALUES (
NULL , '".$_POST["descripcion_zona"]."'
);
";
$zona->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


?>

<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO marca (
`marca`
)
VALUES ( '".$_POST["descripcion_linea"]."'
);
";
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}
?>
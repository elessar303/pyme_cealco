<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO `ubicacion` (

`descripcion`,
`orden`,
`puede_vender`,
`id_almacen`
)
VALUES (
 '".$_POST["descripcion_ubicacion"]."', '".$_POST["orden_ubicacion"]."', '".$_POST["puede_vender"]."', '".$_POST["id_almacen"]."'
);
";
$almacen->Execute2($instruccion);
	if(isset($_GET["loc"])){
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=ubicacion&cod=".$_GET["cod"]."&idLocalidad=".$_GET["idLocalidad"]."&loc=1");
	}else{
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=ubicacion&cod=".$_GET["cod"]);
	}


}



?>

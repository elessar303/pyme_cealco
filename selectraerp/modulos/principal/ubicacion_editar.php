<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE ubicacion set
`descripcion` = '".$_POST["descripcion_ubicacion"]."', `id_almacen` = '".$_POST["id_almacen"]."' , `puede_vender` = '".$_POST["puede_vender"]."' WHERE id = ".$_GET["id"];
$almacen->Execute2($instruccion);
	if(isset($_GET["loc"])){
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=ubicacion&cod=".$_GET["cod"]."&idLocalidad=".$_GET["idLocalidad"]."&loc=1");
	}else{
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=ubicacion&cod=".$_GET["cod"]);
	}

}


if(isset($_GET["cod"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from ubicacion where id = ".$_GET["id"]);
$smarty->assign("datos_ubicacion",$campos);
}



?>
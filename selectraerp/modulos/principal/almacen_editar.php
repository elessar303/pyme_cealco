<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE almacen set
`descripcion` = '".$_POST["descripcion_almacen"]."', `id_localidad` = '".$_POST["id_localidad"]."' WHERE cod_almacen = ".$_GET["cod"];
$almacen->Execute2($instruccion);
if(isset($_GET["loc"])){
	header("Location: ?opt_menu=3&opt_seccion=175&idLocalidad=".$_GET["idLocalidad"]);	
}else{
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

}


if(isset($_GET["cod"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from almacen where cod_almacen = ".$_GET["cod"]);
$smarty->assign("datos_almacen",$campos);
}
// Cargando localidad en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM localidad" );
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_localidad", $arraySelectOption);
$smarty->assign("option_output_localidad", $arraySelectoutPut);
$smarty->assign("option_selected_localidad", $campos[0]["id_localidad"]);


?>
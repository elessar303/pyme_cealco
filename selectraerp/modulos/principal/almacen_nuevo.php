<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO `almacen` (

`descripcion`,
`id_localidad`
)
VALUES (
 '".$_POST["descripcion_almacen"]."', '".$_POST["id_localidad"]."'
);
";
$almacen->Execute2($instruccion);
	if(isset($_GET["loc"])){
			header("Location: ?opt_menu=3&opt_seccion=175&idLocalidad=".$_GET["idLocalidad"]."&loc=1");
	}else{
			header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
	}

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

?>

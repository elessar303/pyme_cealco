<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO `localidad` (

`descripcion`
,
`estado`
,
`estado_atiende`,
`codigo_SIGA`
)
VALUES (
 '".$_POST["descripcion_localidad"]."', '".$_POST["id_estado"]."','".$_POST["id_estado_atiende"]."','".$_POST["siga_localidad"]."'
);
";
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

// Cargando region en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM estados");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] =utf8_encode($item["nombre"]) ;
    $arraySelectoutPut[] = $item["id"];
}
$smarty->assign("option_values_region", $arraySelectOption);
$smarty->assign("option_output_region", $arraySelectoutPut);

?>

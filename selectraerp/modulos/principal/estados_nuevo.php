<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO `estado_region` (

`id_region`
,
`id_estado`
)
VALUES (
 '".$_POST["id_region"]."', '".$_POST["id_estado"]."'
);
";
$almacen->Execute2($instruccion);

$instruccion2="UPDATE estados set
`estatus` =1  WHERE id = ".$_POST["id_estado"];
$almacen->Execute2($instruccion2);

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=listEst&id=".$_POST["id_region"]);
}
// Cargando estado en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM estados where estatus=0 ORDER BY nombre ASC" );
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = utf8_encode($item["nombre"]);
    $arraySelectoutPut[] = $item["id"];
}
$smarty->assign("option_values_region", $arraySelectOption);
$smarty->assign("option_output_region", $arraySelectoutPut);

?>

<?php
include("../../libs/php/clases/almacen.php");

$arraySelectOption = "";
$arraySelectoutPut = "";
$almacen = new Almacen();
$campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM localidad");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectOutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_localidad", $arraySelectOption);
$smarty->assign("option_output_localidad", $arraySelectOutPut);

?>

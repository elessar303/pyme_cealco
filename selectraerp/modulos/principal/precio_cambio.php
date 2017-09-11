<?php
include("../../libs/php/clases/almacen.php");

$arraySelectOption = "";
$arraySelectoutPut = "";
$almacen = new Almacen();
$campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item order by descripcion1");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_item"];
    $arraySelectOutPut[] = $item["descripcion1"];
}
$smarty->assign("option_values_item", $arraySelectOption);
$smarty->assign("option_output_item", $arraySelectOutPut);







?>

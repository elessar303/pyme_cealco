<?php

include("../../libs/php/clases/almacen.php");

$almacen = new Almacen();

$name_form = "Billete_nuevo";
// Cargando estatus

$puntos=$almacen->ObtenerFilasBySqlSelect("select codigo_siga_punto, nombre_punto from puntos_venta");

foreach ($puntos as $key => $item) 
{
    $arraySelectOption[] = $item["codigo_siga_punto"];
    $arraySelectoutPut[] = $item["nombre_punto"];
}
$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectoutPut);

//$smarty->assign("option_selected_estatus", $campos[0]["id_localidad"]);


?>
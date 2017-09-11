<?php

include("../../libs/php/clases/banco.php");

$banco = new Banco;
$valueSELECT = "";
$outputSELECT = "";
$tipo_banco = $banco->ObtenerFilasBySqlSelect("select * from tipo_movimientos_ban");

$smarty->assign("reporte", $_GET['reporte']);
$smarty->assign("cuenta_banco", $_GET['cod_cuenta']);

foreach ($tipo_banco as $key => $item) {
    $valueSELECT[] = $item["cod_tipo_movimientos_ban"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_movimientos_ban", $valueSELECT);
$smarty->assign("option_output_tipo_movimientos_ban", $outputSELECT);
#$smarty->assign("option_selected_tipo_movimientos_ban", $datacliente[0]["cod_tipo_movimientos_ban"]);
?>

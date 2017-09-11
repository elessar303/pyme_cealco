<?php

include("../../libs/php/clases/cxp.php");
include("../../../menu_sistemas/lib/common.php");

$cxp = new CXP();
$edoCta = @$_GET['cod2'];
$cod = @$_GET['cod'];
$idFac = @$_GET['fac'];

$smarty->assign("edoCta", $edoCta);
$smarty->assign("cod", $cod);

$factura = $cxp->ObtenerFilasBySqlSelect("SELECT * FROM cxp_factura where id_factura = " . $idFac);
$smarty->assign("factura", $factura);

$retsIslr = $cxp->ObtenerFilasBySqlSelect("select cxpfd.*, li.descripcion, i.descripcion1 from cxp_factura cxpf join cxp_factura_detalle cxpfd on (cxpf.id_factura=cxpfd.id_factura_fk) join lista_impuestos li on (li.cod_impuesto=cxpfd.cod_impuesto) join item i on (i.id_item=cxpfd.id_item) where cxpfd.id_factura_fk=$idFac");
$smarty->assign("retsIslr", $retsIslr);

////////////////////////////////////////////////////////////////////////////////
$ivas = $cxp->ObtenerFilasBySqlSelect("SELECT cod_impuesto, descripcion FROM lista_impuestos WHERE cod_tipo_impuesto = 1");
$valueSELECT = "";
$outputSELECT = "";
foreach ($ivas as $iva) {
    $valueSELECT[] = $iva["cod_impuesto"];
    $outputSELECT[] = $iva["descripcion"];
}
$smarty->assign("option_values_iva", $valueSELECT);
$smarty->assign("option_output_iva", $outputSELECT);

$islrs = $cxp->ObtenerFilasBySqlSelect("SELECT cod_impuesto, descripcion FROM lista_impuestos WHERE cod_tipo_impuesto = 2");
$valueSELECT = "";
$outputSELECT = "";
foreach ($islrs as $islr) {
    $valueSELECT[] = $islr["cod_impuesto"];
    $outputSELECT[] = $islr["descripcion"];
}
$smarty->assign("option_values_islr", $valueSELECT);
$smarty->assign("option_output_islr", $outputSELECT);
?>
<?php
include("../../libs/php/clases/instrumentoformapago.php");

$iformaPago = new iFormaPago();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE instrumentopago_formapago set
`descripcion` = '".$_POST["descripcion"]."', cod_funcioninstrumento = '".$_POST["cod_funcioninstrumento"]."'  WHERE cod_formapago = ".$_POST["cod_formapago"];
$iformaPago->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


if(isset($_GET["cod"])){
$campos = $iformaPago->ObtenerFilasBySqlSelect("select * from instrumentopago_formapago  WHERE cod_formapago = ".$_GET["cod"]);
$smarty->assign("datos",$campos);

$campos2=$campos;
$valueSELECT = "";
$outputSELECT =  "";
$campos = $iformaPago->ObtenerFilasBySqlSelect("select * from instrumentopago_funcioninstrumento");
foreach($campos as $key => $item){
    $valueSELECT[] = $item["cod_funcioninstrumento"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_funcioninstrumentopago",$valueSELECT);
$smarty->assign("option_output_funcioninstrumentopago",$outputSELECT);
$smarty->assign("option_selected_funcioninstrumentopago",$campos2[0]["cod_funcioninstrumento"]);
}

?>
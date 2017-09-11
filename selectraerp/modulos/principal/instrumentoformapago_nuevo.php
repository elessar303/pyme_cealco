<?php
include("../../libs/php/clases/instrumentoformapago.php");

$iformaPago = new iFormaPago();

if(isset($_POST["aceptar"])){
$instruccion = "
INSERT INTO instrumentopago_formapago (
`cod_formapago` ,
`descripcion`,
`cod_funcioninstrumento`,
`usuario_creacion`,
`fecha_creacion`
)
VALUES (
NULL , '".$_POST["descripcion"]."','".$_POST["cod_funcioninstrumento"]."',
    '".$login->getUsuario()."', CURRENT_TIMESTAMP
);
";
$iformaPago->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}

$valueSELECT = "";
$outputSELECT =  "";
$campos = $iformaPago->ObtenerFilasBySqlSelect("select * from instrumentopago_funcioninstrumento");
foreach($campos as $key => $item){
    $valueSELECT[] = $item["cod_funcioninstrumento"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_funcioninstrumentopago",$valueSELECT);
$smarty->assign("option_output_funcioninstrumentopago",$outputSELECT);



?>

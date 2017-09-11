<?php


$comunes = new Comunes();


if(isset($_GET["cod_cuenta"])){
    $campos = $comunes->ObtenerFilasBySqlSelect("
select
tb.cod_tesor_bandodet,
tb.cod_banco,
tb.nro_cuenta,
b.descripcion as descripcion_banco,
tb.descripcion as descripcion_cuenta
from tesor_bancodet tb
inner join banco b on b.cod_banco = tb.cod_banco
 where tb.cod_tesor_bandodet = ".$_GET["cod_cuenta"]);
$smarty->assign("datos_banco",$campos);
}




if(isset ($_POST["aceptar"])){
    $comunes->Execute2("
INSERT INTO `lista_impuestos` (
`cod_impuesto` ,
`cod_formula` ,
`cod_entidad` ,
`cod_tipo_impuesto` ,
`descripcion` ,
`siglas` ,
`codificacion_impuesto` ,
`alicuota` ,
`pago_mayor_a` ,
`monto_sustraccion` ,
`fecha_aplicacion` ,
`estado` ,
`fecha_creacion` ,
`usuario_creacion`

)
VALUES (
NULL , '".$_POST["cod_formula"]."', '".$_POST["cod_entidad"]."', '".$_POST["cod_tipo_impuesto"]."',
'".$_POST["descripcion"]."','".$_POST["siglas"]."', '".$_POST["codificacion_impuesto"]."', '".$_POST["alicuota"]."',
'".$_POST["pago_mayor_a"]."', '".$_POST["monto_sustraccion"]."', '".$_POST["fecha_aplicacion"]."',
'".$_POST["estado"]."', CURRENT_TIMESTAMP , '".$login->getUsuario()."');
");
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
exit;

}

$valueSELECT = "";
$outputSELECT =  "";
$campos = $comunes->ObtenerFilasBySqlSelect("select * from tipo_impuesto");
foreach($campos as $key => $item){
    $valueSELECT[] = $item["cod_tipo_impuesto"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_funciontipoimpuesto",$valueSELECT);
$smarty->assign("option_output_funciontipoimpuesto",$outputSELECT);

$valueSELECT = "";
$outputSELECT =  "";
$campos = $comunes->ObtenerFilasBySqlSelect("select * from entidades");
foreach($campos as $key => $item){
    $valueSELECT[] = $item["cod_entidad"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_funcionentidad",$valueSELECT);
$smarty->assign("option_output_funcionentidad",$outputSELECT);

$valueSELECT = "";
$outputSELECT =  "";
$campos = $comunes->ObtenerFilasBySqlSelect("select * from formulacion_impuestos");
foreach($campos as $key => $item){
    $valueSELECT[] = $item["cod_formula"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_funcionformula",$valueSELECT);
$smarty->assign("option_output_funcionformula",$outputSELECT);

?>

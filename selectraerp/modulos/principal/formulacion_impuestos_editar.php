<?php


$comunes = new Comunes();

/*
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
*/



if(isset ($_POST["aceptar"])){
    $comunes->Execute2("
UPDATE `formulacion_impuestos` set `formula`='".$_POST["formula"]."',
`cod_entidad`='".$_POST["cod_entidad"]."', `cod_tipo_impuesto`='".$_POST["cod_tipo_impuesto"]."' ,
`descripcion`='".$_POST["descripcion"]."', `fecha_aplicacion`='".$_POST["fecha_aplicacion"]."',
`estado`='".$_POST["estado"]."' where cod_formula='".$_POST["cod_formula"]."'");
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
exit;

}

if(isset($_GET["cod"])){
$campos = $comunes->ObtenerFilasBySqlSelect("select * from formulacion_impuestos  WHERE cod_formula = ".$_GET["cod"]);
$smarty->assign("datos_formula",$campos);
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
$smarty->assign("option_selected_tipo_impuesto",$dataformula[0]["cod_tipo_impuesto"]);

$valueSELECT = "";
$outputSELECT =  "";
$campos = $comunes->ObtenerFilasBySqlSelect("select * from entidades");
foreach($campos as $key => $item){
    $valueSELECT[] = $item["cod_entidad"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_funcionentidad",$valueSELECT);
$smarty->assign("option_output_funcionentidad",$outputSELECT);
$smarty->assign("option_selected_entidad",$dataformula[0]["cod_formula"]);
?>
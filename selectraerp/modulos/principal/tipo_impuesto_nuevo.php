<?php
include("../../libs/php/clases/banco.php");
include("../../../menu_sistemas/lib/common.php") ;
$banco = new Banco();

if(isset($_POST["aceptar"])){
$instruccion = "
INSERT INTO tipo_impuesto (
`cod_tipo_impuesto` ,
`descripcion`,
`cuenta_contable`
)
VALUES (
NULL , '".$_POST["descripcion"]."','".$_POST["cuenta_contable1"]."');
";
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


// CONSULTA DE CUENTAS CONTABLES
$global=new bd(SELECTRA_CONF_PYME);
$sentencia="select * from nomempresa where bd='".$_SESSION['EmpresaFacturacion']."'";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT =  "";
$contabilidad = $banco->ObtenerFilasBySqlSelect("select * from ".$fila['bd_contabilidad'].".cwconcue where Tipo='P'");
foreach($contabilidad as $key => $cuenta){
    $valueSELECT[] = $cuenta["Cuenta"];
    $outputSELECT[] = $cuenta["Cuenta"]." - ".$cuenta["Descrip"];
}
$smarty->assign("option_values_cuenta",$valueSELECT);
$smarty->assign("option_output_cuenta",$outputSELECT);
$smarty->assign("option_selected_cuenta1",$campos[0]["cuenta_contable"]);



?>

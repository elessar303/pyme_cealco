<?php
include("../../libs/php/clases/banco.php");
include("../../../menu_sistemas/lib/common.php") ;
$banco = new Banco();


if(isset($_POST["eliminar"])){

$instruccion = "delete  from tipo_impuesto
  WHERE cod_tipo_impuesto = ".$_POST["cod_tipo_impuesto"];

$banco->Execute2($instruccion);

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
exit;
}


if(isset($_GET["cod"])){
$campos = $banco->ObtenerFilasBySqlSelect("select *, (
SELECT count(*) FROM `formulacion_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos,
(
SELECT count(*) FROM `lista_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos2 from tipo_impuesto where cod_tipo_impuesto = ".$_GET["cod"]);
$smarty->assign("datos_banco",$campos);
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
<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE cuentas_contables set
`descripcion` = '".$_POST["descripcion"]."', banco='".$_POST["banco"]."' WHERE nro_cuenta= ".$_POST["nro_cuenta"];
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
exit;
}


if(isset($_GET["cod"])){
$campos = $banco->ObtenerFilasBySqlSelect("select * from cuentas_contables  WHERE id= ".$_GET["cod"]);
$smarty->assign("datos_banco",$campos);




//select
$sql="select * from banco";
$campos1=$banco->ObtenerFilasBySqlSelect($sql);

$valueSELECT = "";
$outputSELECT = "";
foreach ($campos1 as $vals) {
    $valueSELECT[] = $vals['cod_banco'];
    $outputSELECT[] = $vals['descripcion'];
}
$smarty->assign("option_values_precio", $valueSELECT);
$smarty->assign("option_output_precio", $outputSELECT);
$smarty->assign("option_selected_cuentas", $campos[0]['banco']);
}

?>
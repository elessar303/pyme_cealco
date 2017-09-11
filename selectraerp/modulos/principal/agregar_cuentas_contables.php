<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

$sql="select * from banco";
$campos=$banco->ObtenerFilasBySqlSelect($sql);

$valueSELECT = "";
$outputSELECT = "";
foreach ($campos as $vals) {
    $valueSELECT[] = $vals['cod_banco'];
    $outputSELECT[] = $vals['descripcion'];
}
$smarty->assign("option_values_precio", $valueSELECT);
$smarty->assign("option_output_precio", $outputSELECT);



if(isset($_POST["aceptar"])){
$instruccion = "
INSERT INTO cuentas_contables (
nro_cuenta,
descripcion,
banco
)
VALUES (
'".$_POST["nro_cuenta"]."', '".$_POST["descripcion"]."','".$_POST["banco"]."'
);
";
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


?>

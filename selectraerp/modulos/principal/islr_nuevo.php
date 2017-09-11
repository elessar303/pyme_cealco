<?php
include("../../libs/php/clases/almacen.php");
$islr = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO impuestos_islr (
`cod_impuesto_islr`,
`descripcion`,
`imponibleresidente`,
`aplicaresidente`,
`imponiblenoresidente`,
`aplicanoresidente`,
`alicuotanaturalde`,
`sustraccionnaturalde`,
`pagomayoranaturalde`,
`alicuotanaturalnode`,
`sustraccionnaturalnode`,
`pagomayoranaturalnode`,
`alicuotanaturalno`,
`retencionacumuladanaturalno`,
`alicuotajuridica`,
`sustraccionjuridica`,
`pagomayorajuridica`,
`alicuotajuridicano`,
`retencionacumuladajuridicano`,
`fecha_creacion`,
`usuario_creacion`
)
VALUES (
NULL , '".$_POST["descripcion"]."','".$_POST["imponibleresidente"]."','".$_POST["aplicaresidente"]."'
,'".$_POST["imponiblenoresidente"]."','".$_POST["aplicanoresidente"]."','".$_POST["alicuotanaturalde"]."'
,'".$_POST["sustraccionnaturalde"]."','".$_POST["pagomayoranaturalde"]."','".$_POST["alicuotanaturalnode"]."'
,'".$_POST["sustraccionnaturalnode"]."','".$_POST["pagomayoranaturalnode"]."','".$_POST["alicuotanaturalno"]."'
,'".$_POST["retencionacumuladanaturalno"]."','".$_POST["alicuotajuridica"]."','".$_POST["sustraccionjuridica"]."'
,'".$_POST["pagomayorajuridica"]."','".$_POST["alicuotajuridicano"]."','".$_POST["retencionacumuladajuridicano"]."'
,CURRENT_TIMESTAMP,'".$login->getUsuario()."'
);
";
$islr->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


?>

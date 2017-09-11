<?php
include("../../libs/php/clases/almacen.php");
$islr = new Almacen();

if(isset($_POST["aceptar"])){
$instruccion = "UPDATE impuestos_islr set
`descripcion` = '".$_POST["descripcion"]."',
`imponibleresidente` = '".$_POST["imponibleresidente"]."',
`aplicaresidente` = '".$_POST["aplicaresidente"]."',
`imponiblenoresidente` = '".$_POST["imponiblenoresidente"]."',
`aplicanoresidente` = '".$_POST["aplicanoresidente"]."',
`alicuotanaturalde` = '".$_POST["alicuotanaturalde"]."',
`sustraccionnaturalde` = '".$_POST["sustraccionnaturalde"]."',
`pagomayoranaturalde` = '".$_POST["pagomayoranaturalde"]."',
`alicuotanaturalnode` = '".$_POST["alicuotanaturalnode"]."',
`sustraccionnaturalnode` = '".$_POST["sustraccionnaturalnode"]."',
`pagomayoranaturalnode` = '".$_POST["pagomayoranaturalnode"]."',
`alicuotanaturalno` = '".$_POST["alicuotanaturalno"]."',
`retencionacumuladanaturalno` = '".$_POST["retencionacumuladanaturalno"]."',
`alicuotajuridica` = '".$_POST["alicuotajuridica"]."',
`pagomayorajuridica` = '".$_POST["pagomayorajuridica"]."',
`alicuotajuridicano` = '".$_POST["alicuotajuridicano"]."',
`sustraccionjuridica` = '".$_POST["sustraccionjuridica"]."',
`retencionacumuladajuridicano` = '".$_POST["retencionacumuladajuridicano"]."'
WHERE cod_impuesto_islr = ".$_POST["cod_impuesto_islr"];
$islr->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
}


if(isset($_GET["cod"])){
$campos = $islr->ObtenerFilasBySqlSelect("select * from impuestos_islr where cod_impuesto_islr = ".$_GET["cod"]);
$smarty->assign("datos_islr",$campos);
}

?>
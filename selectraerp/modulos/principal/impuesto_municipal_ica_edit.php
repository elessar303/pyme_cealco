<?php
include("../../libs/php/clases/almacen.php");
$ica = new Almacen();

if(isset($_POST["aceptar"])){
$instruccion = "UPDATE impuesto_ica set
`descripcion` = '".$_POST["descripcion"]."',
`actividad` = '".$_POST["actividad"]."',
`agrupacion` = '".$_POST["agrupacion"]."',
`cod_actividad_ciu` = '".$_POST["cod_actividad_ciu"]."',
`tarifa` = '".$_POST["tarifa"]."'
WHERE cod_impuesto_ica= ".$_POST["cod_impuesto_ica"];
$ica->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

if(isset($_GET["cod"])){
$campos = $ica->ObtenerFilasBySqlSelect("select * from impuesto_ica where cod_impuesto_ica = ".$_GET["cod"]);
$smarty->assign("datos_ica",$campos);
}

?>

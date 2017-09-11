<?php
include("../../libs/php/clases/almacen.php");
$islr = new Almacen();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE impuesto_iva set
`descripcion` = '".$_POST["descripcion"]."',
`iva` = '".$_POST["iva"]."' ,
`porcentaje` = '".$_POST["porcentaje"]."'
WHERE cod_impuesto_iva = ".$_POST["cod_impuesto_iva"];
$islr->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $islr->ObtenerFilasBySqlSelect("select * from impuesto_iva where cod_impuesto_iva = ".$_GET["cod"]);
$smarty->assign("datos",$campos);
}

?>
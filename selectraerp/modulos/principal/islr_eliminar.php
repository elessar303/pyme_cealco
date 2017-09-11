<?php
include("../../libs/php/clases/almacen.php");
$islr = new Almacen();

if(isset($_POST["aceptar"])){
$instruccion = "DELETE FROM impuestos_islr 
WHERE cod_impuesto_islr = ".$_POST["cod_impuesto_islr"];
$islr->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
}


if(isset($_GET["cod"])){
$campos = $islr->ObtenerFilasBySqlSelect("select * from impuestos_islr where cod_impuesto_islr = ".$_GET["cod"]);
$smarty->assign("datos_islr",$campos);
}

?>
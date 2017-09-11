<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE divisas set
`Nombre` = '".$_POST["descripcion"]."' ,`Abreviatura` = '".$_POST["abreviatura"]."', `Cambio_unico` = '".$_POST["cambio"]."'  WHERE id_divisa= ".$_POST["cod_banco"];
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


if(isset($_GET["cod"])){
$campos = $banco->ObtenerFilasBySqlSelect("select * from divisas WHERE id_divisa = ".$_GET["cod"]);
$smarty->assign("datos_banco",$campos);
}

?>

<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

$agregarMoneda = $_GET['agregarMoneda'];


if(isset($_POST["aceptar"])){
$instruccion = "
INSERT INTO divisas (Nombre, Abreviatura, Cambio_unico) values ('".$_POST["nombre"]."','".$_POST["abreviatura"]."','".$_POST["tasa"]."') ";
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}

if ($agregarMoneda == 'si')
$smarty->assign("oculto",' style="visibility:hidden" ')

?>

<?php
include("../../libs/php/clases/banco.php");
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/clases/ConexionComun.php");
include("../../libs/php/clases/almacen.php");
$banco = new Banco();
$login = new Login();
$almacen = new Almacen();

if(isset($_POST["aceptar"])){

$cod_usuario=$login->getIdUsuario();
$instruccion = "UPDATE operaciones_apertura set
`status` = ".$_POST["status"]." WHERE id = ".$_POST["id"];
$banco->Execute2($instruccion);

$bitacora="INSERT INTO bitacora(id_usuario, query, fecha, observacion) VALUES (".$cod_usuario.",'".$instruccion."',CURRENT_TIMESTAMP,'Modificacion de Operacion Apertura')";
$almacen->Execute2($bitacora);

Msg::setMessage("<span style=\"color:#62875f;\">Operacion Modificada Exitosamente</span>");
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);

exit;
}


if(isset($_GET["cod"])){
$campos = $banco->ObtenerFilasBySqlSelect("select * from operaciones_apertura  WHERE id = ".$_GET["cod"]);
$smarty->assign("datos",$campos);
}

?>
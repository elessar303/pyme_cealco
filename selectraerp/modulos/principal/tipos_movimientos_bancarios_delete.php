<?php
include("../../libs/php/clases/banco.php");
$tipo_movimiento = new Banco();


if(isset($_POST["eliminar"])){


$instruccion = "delete  from tipo_movimientos_ban
  WHERE cod_tipo_movimientos_ban = ".$_POST["cod_tipo_movimientos_ban"];

$tipo_movimiento->Execute2($instruccion);

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


if(isset($_GET["cod"])){
$campos = $tipo_movimiento->ObtenerFilasBySqlSelect("select * from tipo_movimientos_ban where cod_tipo_movimientos_ban = ".$_GET["cod"]);
$smarty->assign("datos_tipo_movimiento",$campos);
}

?>
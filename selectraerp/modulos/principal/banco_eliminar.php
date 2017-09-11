<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();


if(isset($_POST["eliminar"])){


$instruccion = "delete  from banco
  WHERE cod_banco = ".$_POST["cod_banco"];

$banco->Execute2($instruccion);

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
exit;
}


if(isset($_GET["cod"])){
$campos = $banco->ObtenerFilasBySqlSelect("select * from banco where cod_banco = ".$_GET["cod"]);
$smarty->assign("datos_banco",$campos);
}

?>
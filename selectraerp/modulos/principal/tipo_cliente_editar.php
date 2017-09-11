<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE tipo_cliente set
`descripcion` = '".$_POST["descripcion"]."' WHERE cod_tipo_cliente = ".$_POST["cod_tipo_cliente"];
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
exit;
}


if(isset($_GET["cod"])){
$campos = $banco->ObtenerFilasBySqlSelect("select * from tipo_cliente  WHERE cod_tipo_cliente = ".$_GET["cod"]);
$smarty->assign("datos_tipo_cliente",$campos);
}

?>
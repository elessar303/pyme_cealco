<?php

include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();


if(isset($_POST["cod_vendedor"])){
$instruccion = "
delete from vendedor
 where cod_vendedor = ".$_POST["cod_vendedor"];

$vendedor->Execute2($instruccion);

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $vendedor->ObtenerFilasBySqlSelect("select  * from vendedor where cod_vendedor = ".$_GET["cod"]);
if($campos!=""){
$smarty->assign("datosvendedor",$campos);
}

}

?>

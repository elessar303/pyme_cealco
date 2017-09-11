<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();


if(isset($_POST["eliminar"])){


$instruccion = "delete  from tipo_proveedor_clasif
  WHERE id_pclasif = ".$_POST["id_pclasif"];

$banco->Execute2($instruccion);

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&pagina=".$_POST["pagina"]);
exit;
}


if(isset($_GET["cod"])){
$campos = $banco->ObtenerFilasBySqlSelect("select * from tipo_proveedor_clasif  WHERE id_pclasif = ".$_GET["cod"]);
$smarty->assign("datos_banco",$campos);
}

?>

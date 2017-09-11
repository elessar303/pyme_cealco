<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["aceptar"])){


$instruccion = "
update tasas_cambio  set
`fecha` =  str_to_date('".$_POST["fecha"]."','%d/%m/%Y')
`tasa` =   '".$_POST["tasa"]."' where id_divisa =  '".$_POST["id"]."' ;
";


$banco->Execute2($instruccion);

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}



?>

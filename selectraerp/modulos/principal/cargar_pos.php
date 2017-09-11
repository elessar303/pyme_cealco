<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();

if(isset($_POST["boton"] )){

 if ($_FILES['archivo']['tmp_name'] !='')

{
copy($_FILES['archivo']['tmp_name'],"/tmp/".$_FILES['archivo']['name']);



$ingreso ="load data local infile '/tmp/".$_FILES['archivo']['name']."'  replace into table post FIELDS TERMINATED BY ','  ENCLOSED BY '\"' ";



$banco->Execute2($ingreso);

$notoficacion = "<center> <h3> Los Registros del Archivo ".$_FILES['archivo']['name']." se han cargado en la base de datos Satisfactoriamente <center> </h3>";

$smarty->assign("info",$notoficacion);

$smarty->assign("datos_banco",$campos);

//header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
//exit;
}

}


?>

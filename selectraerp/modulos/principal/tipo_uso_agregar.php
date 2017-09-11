<?php
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/usuarios.php");

$usuarios = new Usuarios();

$arrayCodModulo = array();
$arrayNomModulo = array();


if (isset($_POST["aceptar"])) {




$instruccion = "
    INSERT INTO `tipo_uso`(tipo) VALUES ('".$_POST['tipo_uso']."')";    
    $usuarios->Execute2($instruccion);
    
}


?>

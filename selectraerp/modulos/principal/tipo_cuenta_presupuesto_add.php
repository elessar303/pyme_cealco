<?php
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/usuarios.php");
$usuarios = new Usuarios();
$arrayCodModulo = array();
$arrayNomModulo = array();


if (isset($_POST["aceptar"]) && isset($_POST["tipo_cuenta"])) 
{
	
	$instruccion = 
	"
    	INSERT INTO `tipo_cuenta`(tipo_cuenta) VALUES ('".$_POST['tipo_cuenta']."')
    ";
    $usuarios->Execute2($instruccion);
    echo 
	"
		<script type=\"text/javascript\">
	    alert('Registro Exitoso');
	    history.go(-2);
	      </script>
	";
	exit;
}
?>

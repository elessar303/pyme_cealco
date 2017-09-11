<?php

include("../../libs/php/clases/usuarios.php");

$usuarios = new Usuarios();

if (isset($_POST["aceptar"]) && isset($_POST["id"])) 
{
    $usuarios->Execute2("DELETE FROM tipo_cuenta WHERE id= {$_POST["id"]};");
    echo 
	"
		<script type=\"text/javascript\">
		alert('Registro Eliminado');
		history.go(-2);
	  	</script>
	";
	exit;
    
}

if (isset($_GET["cod"])) 
{
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT *  FROM tipo_cuenta WHERE id= {$_GET["cod"]};");
    $smarty->assign("datos_usuarios", $campos);
    
}
?>

<?php
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/usuarios.php");
$usuarios = new Usuarios();
$arrayCodModulo = array();
$arrayNomModulo = array();
$campos_comunes = $usuarios->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cuenta" );
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = $item["tipo_cuenta"];
}
$smarty->assign("option_values_tipo_cuenta", $arraySelectOption);
$smarty->assign("option_output_tipo_cuenta", $arraySelectoutPut);
//$smarty->assign("option_selected_localidad", $campos[0]["id_localidad"]);

if (isset($_POST["aceptar"]) && isset($_POST["tipo"])) 
{
	
	$instruccion = 
	"
    	INSERT INTO `cuenta_presupuestaria`(cuenta, tipo) VALUES ('".$_POST['cuenta']."', '".$_POST['tipo']."')
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

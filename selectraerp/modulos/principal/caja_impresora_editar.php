<?php

include("../../libs/php/clases/usuarios.php");
$usuarios = new Usuarios();


if (isset($_GET["cod"])) {
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT * FROM caja_impresora WHERE id = ".$_GET['cod']);
    $smarty->assign("datos_usuarios", $campos);
}

//DEPARTAMENTO
$valueSELECT = "";
$outputSELECT = "";
$valueSELECT1 = "";
$outputSELECT1 = "";
$smarty->assign("option_values_centro", $valueSELECT);
$smarty->assign("option_output_centro", $outputSELECT);
$smarty->assign("option_selected_centro", $campos[0]["departamento"]);

$arraySelectOption_tipo=array(0, 1);
$arraySelectOutPut_tipo=array('PYME', 'POS');

$smarty->assign("option_values_cajas_tipo", $arraySelectOption_tipo);
$smarty->assign("option_output_cajas_tipo", $arraySelectOutPut_tipo);
$smarty->assign("option_selected_cajas_tipo", $campos[0]["caja_tipo"]);



if (isset($_POST["aceptar"])) {
    $instruccion = "update caja_impresora set caja_host = '" . $_POST["nombre_caja"] . "', serial_impresora = '" .$_POST["serial"]. "' , ip = '" .$_POST["ip"]. "', caja_tipo = '" .$_POST["tipo_caja"]. "' where id = " . $_POST["id"];
    $usuarios->Execute2($instruccion);

    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
      echo "<script type=\"text/javascript\">
           history.go(-2);
       </script>";
        exit;
}
?>

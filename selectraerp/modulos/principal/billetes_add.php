<?php

include("../../libs/php/clases/almacen.php");

$almacen = new Almacen();

$name_form = "Billete_nuevo";
// Cargando estatus

    $arraySelectOption[] = 1;
    $arraySelectOption[] = 0;
    $arraySelectoutPut[] ='Activo';
    $arraySelectoutPut[] ='Inactivo';

$smarty->assign("option_values_estatus", $arraySelectOption);
$smarty->assign("option_output_estatus", $arraySelectoutPut);
//$smarty->assign("option_selected_estatus", $campos[0]["id_localidad"]);


if (isset($_POST["aceptar"])) 
{

	$login = new Login();
    $instruccion = "INSERT INTO billetes (`denominacion`,`valor`, `estatus`, `usuario_creacion`  )
                VALUES ( '{$_POST["denominacion"]}', '{$_POST["valor"]}', '{$_POST["estatus"]}', '{$login->getUsuario()}');";
    $almacen->Execute2($instruccion);
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}

$smarty->assign("name_form", $name_form);
?>
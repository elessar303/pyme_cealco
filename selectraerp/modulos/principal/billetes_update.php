<?php

include("../../libs/php/clases/departamento.php");

$departamento = new Departamento();

if (isset($_POST["aceptar"])) 
{
	$login = new Login();
    $instruccion = "UPDATE billetes SET denominacion = '{$_POST["denominacion"]}', valor = '{$_POST["valor"]}', estatus = '{$_POST["estatus"]}', usuario_creacion=  '{$login->getUsuario()}'
    WHERE id = {$_POST["id"]}";
    $departamento->Execute2($instruccion);
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
if (isset($_GET["cod"])) 
{
    $campos = $departamento->ObtenerFilasBySqlSelect("select * from billetes where id = " . $_GET["cod"]);
    $smarty->assign("datos", $campos);
    $arraySelectOption[] = 1;
    $arraySelectOption[] = 0;
    $arraySelectoutPut[] ='Activo';
    $arraySelectoutPut[] ='Inactivo';
    $smarty->assign("option_values_estatus", $arraySelectOption);
	$smarty->assign("option_output_estatus", $arraySelectoutPut);
	$smarty->assign("option_selected_estatus", $campos[0]['estatus']);

}

$smarty->assign("name_form", "departamento_editar");
?>
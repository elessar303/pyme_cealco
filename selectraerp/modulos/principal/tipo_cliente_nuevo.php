<?php

include("../../libs/php/clases/banco.php");
$banco = new Banco();

$tabla = $name_form = "tipo_cliente";
$smarty->assign("name_form", $name_form);

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= " . $_GET["opt_seccion"]);
#$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM subseccion WHERE cod_seccion= " . $_GET["opt_seccion"]." AND opt_subseccion = 'add'");
$smarty->assign("campo_seccion", $campos);

if (isset($_POST["aceptar"])) {
    $instruccion = "INSERT INTO " . $tabla . " (`cod_tipo_cliente`, `descripcion`)
                VALUES (NULL , '" . $_POST["descripcion"] . "');";
    $banco->Execute2($instruccion);
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
    exit;
}
?>

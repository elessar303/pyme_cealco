<?php

include("../../libs/php/clases/usuarios.php");

$usuarios = new Usuarios();

if (isset($_POST["aceptar"])) {
    $usuarios->Execute2("DELETE FROM usuarios WHERE cod_usuario = {$_POST["cod_usuario"]};");
    $usuarios->Execute2("DELETE FROM modulo_usuario WHERE cod_usuario = {$_POST["cod_usuario"]};");
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}

if (isset($_GET["cod"])) {
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT cod_usuario, nombreyapellido, usuario, clave FROM usuarios WHERE cod_usuario = {$_GET["cod"]};");
    $smarty->assign("datos_usuarios", $campos);
}
?>

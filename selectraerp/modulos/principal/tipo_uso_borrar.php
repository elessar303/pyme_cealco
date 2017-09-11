<?php

include("../../libs/php/clases/usuarios.php");

$usuarios = new Usuarios();

if (isset($_POST["aceptar"])) {
    $usuarios->Execute2("DELETE FROM tipo_uso WHERE id= {$_POST["id"]};");
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}

if (isset($_GET["cod"])) {
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT *  FROM tipo_uso WHERE id= {$_GET["cod"]};");
    $smarty->assign("datos_usuarios", $campos);
}
?>

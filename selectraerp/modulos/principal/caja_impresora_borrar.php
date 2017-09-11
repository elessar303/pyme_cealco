<?php

include("../../libs/php/clases/usuarios.php");

$usuarios = new Usuarios();

if (isset($_POST["aceptar"])) {
    $usuarios->Execute2("DELETE FROM caja_impresora WHERE id= {$_POST["id"]};");
    $usuarios->Execute2("DELETE FROM cierre_caja_control WHERE cajas= '{$_POST["nombre_caja"]}';");
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}

if (isset($_GET["cod"])) {
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT *  FROM caja_impresora WHERE id= {$_GET["cod"]};");
    $smarty->assign("datos_usuarios", $campos);
}
?>

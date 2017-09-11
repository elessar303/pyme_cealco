<?php

ini_set("display_errors", 1);
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}

require_once("../../config.ini.php");
require_once("../../../generalp.config.inc.php");
require_once(RAIZ_PROYECTO . "/libs/php/clases/producto.php");

$productos = new Producto();
$campos_almacen = $productos->ObtenerFilasBySqlSelect("SELECT * FROM usuarios");

$login = new Login();
$smarty->assign("acceso", -1);

if (isset($_POST["submit"])) {
    if ($login->validarAcceso($_POST['txtUsuario'], $_POST['txtContrasena']) == 1) {
        $smarty->assign("acceso", 1);
        header("Location: ../principal/?opt_menu=54");
    } else {
        $smarty->assign("acceso", 0);
    }
}

if ($login->getIdSessionActual() != "") {
    header("Location: ../principal/?opt_menu=54");
    exit;
} else {
    $smarty->display("index.tpl");
}
?>

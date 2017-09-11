<?php

if (!isset($_SESSION)) {
    session_start();
    ob_start();
}

define("RAIZ_PROYECTO", dirname(__FILE__));
require_once(RAIZ_PROYECTO . "/libs/php/smarty/Smarty.class.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/clases/Menu.php");
require_once("../../libs/php/clases/parametrosgenerales.php");
require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/clases/Mensajes.php");
$smarty = new Smarty();
$smarty->template_dir = "templates/";
$smarty->compile_dir = "templates_c";
$smarty->config_dir = "configs/";
$smarty->cache_dir = "cache/";
$smarty->caching = false;
$smarty->force_compile = true;
$smarty->compile_check = false;
$smarty->debugging = false;
?>

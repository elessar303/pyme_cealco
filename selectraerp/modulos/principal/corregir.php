<?php
ini_set("memory_limit","1024M");
ini_set("upload_max_filesize","2000M");

session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
include("../../../generalp.config.inc.php");
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");

$comunes = new ConexionComun();

$sql_corregir_itempos="update item 
inner join $pos.products on item.codigo_barras=products.CODE
set itempos=products.ID
WHERE  item.codigo_barras=products.CODE";
$comunes->Execute2($sql_corregir_itempos);
header("Location: file_upload_productos.php");
?>
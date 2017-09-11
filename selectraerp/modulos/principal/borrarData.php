<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
$comunes = new Producto();

$path_cantv="/var/www/pyme/selectraerp/uploads/cantv";
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
$path_inventario="/var/www/pyme/selectraerp/uploads/inventario";
$path_kardex="/var/www/pyme/selectraerp/uploads/kardex";
$path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
$path_consolidados="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
$path_descomprimidos="/var/www/pyme/selectraerp/uploads/recibidos/descomprimidos";
$path_temporal_productos="/var/www/pyme/selectraerp/uploads/recibidos/temporal_subida_producto";

//funcion para borrar el contenido de la carpetas;
function borrarCarpeta ($nombreCarpeta){
     chmod($nombreCarpeta,  0777);
    $handle1=  opendir($nombreCarpeta);
    while ($archivo = readdir($handle1)){
        chmod($nombreCarpeta."/".$archivo,  0777);
        unlink($nombreCarpeta."/".$archivo);
    }    
}

borrarCarpeta($path_consolidados);
borrarCarpeta($path_beneficiarios);
borrarCarpeta($path_descomprimidos);
borrarCarpeta($path_temporal_productos);

<?php    
ini_set('memory_limit', '1024M');
ini_set("max_execution_time","1000000");
session_start();
ini_set("display_errors", 1);
//Includes
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../config.ini.php");
require_once('../../../generalp.config.inc.php');
require_once('../../../includes/clases/BDControlador.php');

//FECHA PARA OBTENER LAS VENTAS DE LA SEMANA
$path_descarga_manual="/var/www/pyme/selectraerp/uploads/descarga_ventas_manuales/";
$directorio=dir("$path_descarga_manual");
while ($archivo = $directorio->read()) {
    if (substr($archivo,0,1)!=".") {
   	$archivo_descarga=$path_descarga_manual."".$archivo;
   	//echo $archivo_descarga; exit;
   	header("Content-disposition: attachment; filename=Consolidado_Ventas.zip");
	header("Content-type: application/octet-stream");
	readfile($archivo_descarga);
	}
}
?>
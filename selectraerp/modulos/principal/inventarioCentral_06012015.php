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
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
$comunes = new Producto();
$pymeC="selectrapyme_central";
//$pymeC="pyme_administrativo_estructura";

//RUTAS
$path_inventario="/var/www/pyme/selectraerp/uploads/inventarios";

$mes_des=date('m');
$anno_des=date('y');
$inventario_mes_anno="inventario_".$mes_des."_".$anno_des; 

$directorio=dir("$path_inventario");

while (false !== ($archivo = $directorio->read())) {

	if($archivo!=".." && $archivo!="." ){	
		if(file_exists($path_inventario."/".$archivo)){				
			$files=fopen($path_inventario."/".$archivo,"r");
            $cont=0;
            $dia=substr($archivo,7,2);
            $mes=substr($archivo,9,2); 
            $anno=substr($archivo,11,2);
            while (($datos = fgetcsv($files, ",")) !== FALSE) {
            $instruccion = "INSERT INTO $pymeC.inventario_".$mes."_".$anno."(
						`id`,
						`codigo_barra`,
						`ubicacion`,
						`cantidad`,
						`fecha`,
						`siga`
						)
						VALUES (
						 'NULL', 
						 '".$datos[4]."',
						 '".$datos[3]."',
						 '".$datos[1]."',
						 '20".$anno."-".$mes."-".$dia."',
						 '".$datos[5]."'
						);";
				//echo $instruccion; exit();
				$comunes->Execute2($instruccion);  
             
            } 
            fclose($files);	
            unlink($path_inventario."/".$archivo);
		}
	}
}
unset($connection);
session_destroy();
$comunes->cerrar();
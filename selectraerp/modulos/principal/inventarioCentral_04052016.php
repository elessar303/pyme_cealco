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

//RUTAS
$path_inventario="/var/www/pyme/selectraerp/uploads/inventario_central";
$path_kardex="/var/www/pyme/selectraerp/uploads/kardex_central";


$mes_des=date('m');
$anno_des=date('y');
$inventario_mes_anno="inventario_".$mes_des."_".$anno_des; 

$directorio=dir("$path_inventario");

function sanear_string($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "<", ";", ",", ":", " "),
        '',
        $string
    );
 
 
    return $string;
}


while (false !== ($archivo = $directorio->read())) {
	if($archivo!=".." && $archivo!="." ){	
		if(file_exists($path_inventario."/".$archivo)){				
			$files=fopen($path_inventario."/".$archivo,"r");
            $cont=0;
            $dia=substr($archivo,7,2);
            $mes=substr($archivo,9,2); 
            $anno=substr($archivo,11,2);
            $siga=substr($archivo,0,6);

            $comprobar=$comunes->ObtenerFilasBySqlSelect("select id from $pymeC.puntos_venta where codigo_siga_punto='".$siga."' and fecha='".."'");
            $resultado_punto=$comunes->getFilas();

            //echo $resultado_punto; exit();
            if($resultado_punto>0){

            while (($datos = fgetcsv($files, ",")) !== FALSE) {
            $datos[4]=sanear_string($datos[4]);
            $datos[3]=sanear_string($datos[3]);
            $datos[1]=sanear_string($datos[1]);

            $comprobar_insert=$comunes->ObtenerFilasBySqlSelect("select id from $inventario_mes_anno where siga='".$siga." and '20".$anno."-".$mes."-".$dia."'");
            $resultado_punto=$comunes->getFilas($comprobar_insert);

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
						 '".$siga."'
						);";
			//echo $instruccion; exit();

			$comunes->Execute2($instruccion);  
             
            	}
            	$update=$comunes->Execute2("update $pymeC.reportes_inventario set fecha=now(), reporto=1 where codigo_siga='".$siga."'");
                $update=$comunes->Execute2("update $pymeC.reportes_ventas set fecha=now(), reporto=1 where codigo_siga='".$siga."'");
        	}else{
                 error_log("Error al verificar archivo ".$archivo." Codigo SIGA: ".$siga."  fecha o codigo siga dentro del archivo".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
             }
            fclose($files);	
            unlink($path_inventario."/".$archivo);            
		}		
	}	

}

$directorio=dir("$path_kardex");

while (false !== ($archivo = $directorio->read())) {
	if($archivo!=".." && $archivo!="." ){	
		if(file_exists($path_kardex."/".$archivo)){				
			$files=fopen($path_kardex."/".$archivo,"r");
            $cont=0;
            $dia=substr($archivo,7,2);
            $mes=substr($archivo,9,2); 
            $anno=substr($archivo,11,2);
            $codigo_siga=substr($archivo,3,3);
            while (($datos = fgetcsv($files, ",")) !== FALSE) {
            $datos[0]=sanear_string($datos[0]);
            $datos[1]=sanear_string($datos[1]);
            $datos[2]=sanear_string($datos[2]);
            $datos[3]=sanear_string($datos[3]);
            $datos[4]=sanear_string($datos[4]);
            $datos[5]=sanear_string($datos[5]);
            $datos[6]=sanear_string($datos[6]);
            $datos[7]=sanear_string($datos[7]);
            $datos[8]=sanear_string($datos[8]);
            $datos[9]=sanear_string($datos[9]);
            $datos[10]=sanear_string($datos[10]);
            $datos[11]=sanear_string($datos[11]);
            $datos[12]=sanear_string($datos[12]);
            $datos[13]=sanear_string($datos[13]);
            $instruccion = "INSERT INTO $pymeC.kardex_".$mes."_".$anno."(
						`autorizador_por`, 
						`estatus`, 
						`fecha`, 
						`id_documento`, 
						`tipo_movimiento`, 
						`observacion_cabecera`, 
						`codigo_barras`, 
						`cantidad`,
						`cantidad_esperada`,
						`observacion_detalle`,
						`almacen_entrada`, 
						`almacen_salida`,
						`ubicacion_entrada`, 
						`ubicacion_salida`, 
						`codigo_siga`
						)
						VALUES (
						 '".$datos[0]."',
						 '".$datos[1]."',
						 '".$datos[2]."',
						 '".$datos[3]."',
						 '".$datos[4]."',
						 '".$datos[5]."',
						 '".$datos[6]."',
						 '".$datos[7]."',
						 '".$datos[8]."',
						 '".$datos[9]."',
						 '".$datos[10]."',
						 '".$datos[11]."',
						 '".$datos[12]."',
						 '".$datos[13]."',
						 '".$codigo_siga."');";
				//echo $instruccion; exit(); 
				$comunes->Execute2($instruccion);  
            } 
            fclose($files);	
            unlink($path_kardex."/".$archivo);            
		}		
	}	

}

unset($connection);
session_destroy();
$comunes->cerrar();
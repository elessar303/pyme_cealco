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
$path_libros="/var/www/pyme/selectraerp/uploads/libro_venta_central";
$path_ingresos="/var/www/pyme/selectraerp/uploads/control_ingresos_central";
$path_comprobantes_cab="/var/www/pyme/selectraerp/uploads/comprobantes";
$path_comprobantes_det="/var/www/pyme/selectraerp/uploads/comprobantes_detalle";
$path_comprobantes_ing="/var/www/pyme/selectraerp/uploads/comprobantes_ingresos";


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
        array("\\", "¨", "º", "~",
             "#", "@", "|", "!",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "<", ";", ",", ":"),
        '',
        $string
    );
 
 
    return $string;
}



while (false !== ($archivo = $directorio->read())) {
	if($archivo!=".." && $archivo!="." ){	
        echo $archivo." - Inventario <br>";
		if(file_exists($path_inventario."/".$archivo)){				
			$files=fopen($path_inventario."/".$archivo,"r");
            $cont=0;
            $dia=substr($archivo,7,2);
            $mes=substr($archivo,9,2); 
            $anno=substr($archivo,11,2);
            $siga=substr($archivo,0,6);
            //verificando la version del pyme
            $existe=strpos($archivo, '_v');
            if($existe!=0)
            {
                $version=substr($archivo,14,4);
            }
            else
            {
                
                $version="OLD";
            }
            
            //update para agregar version del pyme en el punto de venta
            $updateversion="update $pymeC.puntos_venta set version_pyme='".$version."' where codigo_siga_punto='".$siga."'";
            $comunes->Execute2($updateversion);
            

            $comprobar=$comunes->ObtenerFilasBySqlSelect("select id from $pymeC.puntos_venta where codigo_siga_punto='".$siga."'");
            $resultado_punto=$comunes->getFilas($comprobar);

            if($resultado_punto>0){ 

            while (($datos = fgetcsv($files, ",")) !== FALSE) {
            $datos[4]=sanear_string($datos[4]);
            $datos[3]=sanear_string($datos[3]);
            $datos[1]=sanear_string($datos[1]);

            $sql="select id from $pymeC.inventario_".$mes."_".$anno." where siga='".$siga."' and fecha='20".$anno."-".$mes."-".$dia."' and codigo_barra='".$datos[4]."'";
            $comprobar_insert=$comunes->ObtenerFilasBySqlSelect($sql); 

            $nro_filas=$comunes->getFilas($comprobar_insert);

            if ($nro_filas==0) { 

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
			     //echo $archivo." - ".$instruccion; exit();
			     $comunes->Execute2($instruccion);

			     }
             
            	}

            	$update=$comunes->Execute2("update $pymeC.reportes_inventario set fecha=now(), reporto=1 where codigo_siga='".$siga."'");
                $update=$comunes->Execute2("update $pymeC.reportes_ventas set fecha=now(), reporto=1 where codigo_siga='".$siga."'");
                $historico_insert=$comunes->Execute2("insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('".$siga."', date(now()))");


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
        echo $archivo." - Kardex<br>";
		if(file_exists($path_kardex."/".$archivo)){		



			$files=fopen($path_kardex."/".$archivo,"r");
            $cont=0;
            $dia=substr($archivo,7,2);
            $mes=substr($archivo,9,2); 
            $anno=substr($archivo,11,2);
            $codigo_siga=substr($archivo,3,3);

            $comprobar=$comunes->ObtenerFilasBySqlSelect("select id from $pymeC.puntos_venta where codigo_siga_punto='000".$codigo_siga."'");
            $resultado_punto=$comunes->getFilas($comprobar);

            if($resultado_punto>0){
            

            while (($datos = fgetcsv($files, ",")) !== FALSE) {

            $anno_valido=substr($datos[2],0,4);
            $anno=substr($datos[2],2,2); 
            $mes=substr($datos[2],5,2);


            $sql="select id from $pymeC.kardex_".$mes."_".$anno."  where codigo_siga='".$codigo_siga."' and cod_movimiento='".$datos[14]."' and codigo_barras='".$datos[6]."'";
            
            $comprobar_insert=$comunes->ObtenerFilasBySqlSelect($sql); 

            if($comprobar_insert[0]["id"]!='' && $datos[14]!=''){
                
                
                $sql_delete="DELETE FROM $pymeC.kardex_".$mes."_".$anno." where codigo_siga='".$codigo_siga."' and cod_movimiento='".$datos[14]."' and codigo_barras='".$datos[6]."'";
                $comunes->Execute2($sql_delete);
            }

            $datos[0]=sanear_string($datos[0]);
            $datos[1]=sanear_string($datos[1]);
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
            $datos[16]=sanear_string($datos[16]);
            $datos[17]=sanear_string($datos[17]);
            $datos[18]=sanear_string($datos[18]);

            //echo $archivo;

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
                        `cod_movimiento`,
						`codigo_siga`,
                        `fecha_vencimiento`,
                        `lote`,
                        `rif`,
                        `nombre_proveedor`,
                        `guia_sunagro`,
                        `id_tipo_despacho`,
                        `precio`
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
                         '".$datos[14]."',
						 '".$codigo_siga."',
                         '".$datos[15]."',
                         '".$datos[16]."',
                         '".$datos[17]."',
                         '".$datos[18]."',
                         '".$datos[19]."',
                         '".$datos[20]."',
                         '".$datos[21]."');";

                //echo $archivo." - ".$instruccion;
                if ($anno_valido=='2017') {
                	$comunes->Execute2($instruccion); 
                }				 
            }
        }
            fclose($files);	
            unlink($path_kardex."/".$archivo);            
		}		
	}	

}


$directorio=dir("$path_libros");

while (false !== ($archivo = $directorio->read())) {
    if($archivo!=".." && $archivo!="." ){   
        if(file_exists($path_libros."/".$archivo)){             
            $files=fopen($path_libros."/".$archivo,"r");

            $dia=substr($archivo,7,2);
            $mes=substr($archivo,9,2); 
            $anno=substr($archivo,11,2);
            $codigo_siga=substr($archivo,3,3);

            $sql="select id from $pymeC.libro_ventas_central where codigo_siga='000".$codigo_siga."' and archivo='".$archivo."'";

            $comprobar_insert=$comunes->ObtenerFilasBySqlSelect($sql); 


            if($comprobar_insert[0]["id"]!=''){
                $sql_delete="DELETE FROM $pymeC.libro_ventas_central where codigo_siga='000".$codigo_siga."' and archivo='".$archivo."' ";


                $comunes->Execute2($sql_delete);
            }


            while (($datos = fgetcsv($files, ",")) !== FALSE) {

            if($datos[4]==''){$datos[4]='NULL';}
            if($datos[5]==''){$datos[5]='NULL';}
            if($datos[20]==''){$datos[20]='3';}
            $instruccion = "INSERT INTO $pymeC.libro_ventas_central(
            `serial_impresora`, 
            `numero_z`, 
            `ultima_factura`, 
            `numeros_facturas`, 
            `ultima_nc`, 
            `numeros_ncs`, 
            `fecha`, 
            `monto_bruto`, 
            `monto_exento`, 
            `base_imponible`, 
            `iva`, 
            `fecha_emision`, 
            `id_usuario_creacion`, 
            `secuencia`, 
            `numero_z_usuario`, 
            `monto_bruto_usuario`, 
            `monto_exento_usuario`, 
            `base_imponible_usuario`, 
            `iva_usuario`, 
            `money`, 
            `tipo_venta`, 
            `codigo_siga`, 
            `archivo`)
            VALUES (
            '".$datos[0]."',
            ".$datos[1].",
            ".$datos[2].",
            ".$datos[3].",
            ".$datos[4].",
            ".$datos[5].",
            '".$datos[6]."',
            ".$datos[7].",
            ".$datos[8].",
            ".$datos[9].",
            ".$datos[10].",
            '".$datos[11]."',
            '".$datos[12]."',
            ".$datos[13].",
            ".$datos[14].",
            ".$datos[15].",
            ".$datos[16].",
            ".$datos[17].",
            ".$datos[18].",
            '".$datos[19]."',
            ".$datos[20].",
            '000".$datos[21]."',
            '".$archivo."'
            );";
                //echo $instruccion; exit(); 
                $comunes->Execute2($instruccion);  
            } 
            fclose($files); 
            unlink($path_libros."/".$archivo);            
        }       
    }   

}

$directorio=dir("$path_ingresos");

while (false !== ($archivo = $directorio->read())) {
    if($archivo!=".." && $archivo!="." ){   
        if(file_exists($path_ingresos."/".$archivo)){             
            $files=fopen($path_ingresos."/".$archivo,"r");

            $dia=substr($archivo,7,2);
            $mes=substr($archivo,9,2); 
            $anno=substr($archivo,11,2);
            $codigo_siga=substr($archivo,3,3);

            $sql="select id from $pymeC.ingresos_central where codigo_siga='000".$codigo_siga."' and archivo='".$archivo."'";
            $comprobar_insert=$comunes->ObtenerFilasBySqlSelect($sql); 
 

            if($comprobar_insert[0]["id"]!=''){
                $sql_delete="DELETE FROM $pymeC.ingresos_central where codigo_siga='000".$codigo_siga."' and archivo='".$archivo."' ";

                $comunes->Execute2($sql_delete);
            }

            while (($datos = fgetcsv($files, ",")) !== FALSE) {

            $instruccion = "INSERT INTO $pymeC.ingresos_central(
            `nro_deposito`, 
            `fecha_deposito`, 
            `monto_deposito`, 
            `cuenta_banco`, 
            `usuario_creacion`, 
            `nro_cataporte`, 
            `fecha_cataporte`, 
            `fecha_retiro`, 
            `usuario_creacion_cataporte`, 
            `codigo_siga`, 
            `archivo`) 
            VALUES (
            '".$datos[0]."',
            '".$datos[1]."',
            ".$datos[2].",
            '".$datos[3]."',
            '".$datos[4]."',
            '".$datos[5]."',
            '".$datos[6]."',
            '".$datos[7]."',
            '".$datos[8]."',
            '000".$datos[9]."',
            '".$archivo."'
            );";
                //echo $instruccion; exit(); 
                $comunes->Execute2($instruccion);  
            } 
            fclose($files); 
            unlink($path_ingresos."/".$archivo);            
        }       
    }   

}

$directorio=dir("$path_comprobantes_cab");

while (false !== ($archivo = $directorio->read())) {
    if($archivo!=".." && $archivo!="." ){   
        if(file_exists($path_comprobantes_cab."/".$archivo)){             
            $files=fopen($path_comprobantes_cab."/".$archivo,"r");

            $codigo_siga=substr($archivo,3,3);

            

            while (($datos = fgetcsv($files,500,";")) !== FALSE) {

                $sql="select id from $pymeC.comprobante_central where codigo_siga='000".$codigo_siga."' and id_comprobante='".$datos[0]."'";
                $comprobar_insert=$comunes->ObtenerFilasBySqlSelect($sql); 
 

                if($comprobar_insert[0]["id"]!=''){
                    $sql_delete="DELETE FROM $pymeC.comprobante_central where codigo_siga='000".$codigo_siga."' and id_comprobante='".$datos[0]."' ";

                    $comunes->Execute2($sql_delete);
                }


            $instruccion = "INSERT INTO $pymeC.comprobante_central(
            `id_comprobante`, 
            `ingreso`, 
            `iva1`, 
            `iva2`, 
            `iva3`, 
            `perdida`, 
            `cxc`, 
            `otros_ingresos`, 
            `caja`, 
            `banco`, 
            `tipo_mov`, 
            `id_usuario`, 
            `fecha`, 
            `observacion`,
            `nombre_usuario`, 
            `codigo_siga`) 
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
            '".$datos[14]."',
            '".$datos[15]."'
            );";
                //echo $instruccion; exit(); 
                $comunes->Execute2($instruccion);  
            } 
            fclose($files); 
            unlink($path_comprobantes_cab."/".$archivo);            
        }       
    }   

}

$directorio=dir("$path_comprobantes_det");

while (false !== ($archivo = $directorio->read())) {
    if($archivo!=".." && $archivo!="." ){   
        if(file_exists($path_comprobantes_det."/".$archivo)){             
            $files=fopen($path_comprobantes_det."/".$archivo,"r");

            $codigo_siga=substr($archivo,3,3);

            

            while (($datos = fgetcsv($files,1000,";")) !== FALSE) {

                $sql="SELECT id from $pymeC.comprobante_detalle_central WHERE codigo_siga='000".$codigo_siga."' and id_comprobante='".$datos[1]."' and id_comprobante_detalle='".$datos[0]."'";
                $comprobar_insert=$comunes->ObtenerFilasBySqlSelect($sql); 
 

                if($comprobar_insert[0]["id"]!=''){
                    $sql_delete="DELETE FROM $pymeC.comprobante_detalle_central WHERE codigo_siga='000".$codigo_siga."' and id_comprobante='".$datos[1]."' and id_comprobante_detalle='".$datos[0]."'";

                    $comunes->Execute2($sql_delete);
                }


            $instruccion = "INSERT INTO $pymeC.comprobante_detalle_central(
            `id_comprobante_detalle`, 
            `id_comprobante`, 
            `ingreso`, 
            `cajero`, 
            `iva1`, 
            `iva2`, 
            `iva3`, 
            `perdida`, 
            `cxc`, 
            `otros_ingresos`, 
            `caja`, 
            `id_usuario`, 
            `fecha`, 
            `tipo_venta`,
            `nombre_pyme`,
            `nombre_pos`,
            `codigo_siga`) 
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
            '".$datos[14]."',
            '".$datos[15]."',
            '".$datos[16]."'
            );";
                //echo $instruccion; exit(); 
                $comunes->Execute2($instruccion);  
            } 
            fclose($files); 
            unlink($path_comprobantes_det."/".$archivo);            
        }       
    }   

}

$directorio=dir("$path_comprobantes_ing");

while (false !== ($archivo = $directorio->read())) {
    if($archivo!=".." && $archivo!="." ){   
        if(file_exists($path_comprobantes_ing."/".$archivo)){             
            $files=fopen($path_comprobantes_ing."/".$archivo,"r");

            $codigo_siga=substr($archivo,3,3);

            

            while (($datos = fgetcsv($files,1000,";")) !== FALSE) {

                $sql="SELECT id_ingreso_detalle from $pymeC.ingresos_detalles_central WHERE codigo_siga='000".$codigo_siga."' and id_comprobante='".$datos[4]."' and id_comprobante_detalle='".$datos[1]."' and tipo_ingreso='".$datos[2]."'";
                $comprobar_insert=$comunes->ObtenerFilasBySqlSelect($sql); 
 

                if($comprobar_insert[0]["id_ingreso_detalle"]!=''){
                    $sql_delete="DELETE FROM $pymeC.ingresos_detalles_central WHERE codigo_siga='000".$codigo_siga."' and id_comprobante='".$datos[4]."' and id_comprobante_detalle='".$datos[1]."' and tipo_ingreso='".$datos[2]."'";

                    $comunes->Execute2($sql_delete);
                }


            $instruccion = "INSERT INTO $pymeC.ingresos_detalles_central(
            `id_ingreso_detalle`, 
            `id_comprobante_detalle`, 
            `tipo_ingreso`, 
            `monto`, 
            `id_comprobante`, 
            `codigo_siga`) 
            VALUES (
            '".$datos[0]."',
            '".$datos[1]."',
            '".$datos[2]."',
            '".$datos[3]."',
            '".$datos[4]."',
            '".$datos[5]."'
            );";
                //echo $instruccion; exit(); 
                $comunes->Execute2($instruccion);  
            } 
            fclose($files); 
            unlink($path_comprobantes_ing."/".$archivo);            
        }       
    }   

}
session_destroy();
$comunes->cerrar();
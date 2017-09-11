<?php
error_reporting(0);
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 0);

//Includes
include("../../../generalp.config.inc.php");
include("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
$comunes = new Producto();

//Funciones
function completar($cad,$long){
		$temp=$cad;
		for($i=1;$i<=$long-strlen($cad);$i++){
			$temp=" ".$temp;
		}
		return $temp;
		
	}
function completarD($cad,$long){
		$temp=$cad;
		for($i=1;$i<=$long-strlen($cad);$i++){
			$temp=$temp." ";
		}
		return $temp;	
	}
function compararTamano($rutaR,$rutaL,$sftp){
    
    $statinfo = ssh2_sftp_stat($sftp,$rutaR);
    $filesizeR = $statinfo['size'];
    $filesizeL=filesize($rutaL);
    $bandera=0;
    if($filesizeR==$filesizeL){
         $bandera=1;
    }else{
         $bandera=0;
    }
    return $bandera;
    
		
}

$pyme=DB_SELECTRA_FAC;
$pos=POS;

$tipo="E";
$cadena="52";
$sql="SELECT codigo_siga from parametros_generales limit 1";
$codigosiga= $comunes->ObtenerFilasBySqlSelect($sql);
$sucursal=$codigosiga[0]['codigo_siga'];
$estado="0001";
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");
$inventario="inventario";
$nomb="000".$sucursal.'_'.$dia.$mes.$ano.$hora.$min."_inventario.csv";
$nombre_inventario="000".$sucursal.'_'.$dia.$mes.$ano.$hora.$min.".csv";

//Nombre de las rutas
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
$path_inventarios="/var/www/pyme/selectraerp/uploads/inventarios";
$path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
$path_descomprimido="/var/www/pyme/selectraerp/uploads/recibidos/descomprimidos";
//ruta donde se guarda el archivo en el  punto obtenido del central
$path_consolidados="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
//ruta donde obtengo el arvhivo consolidado de las ventas del central
$path_central_cons="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
$path_subida_previa="/var/www/pyme/selectraerp/uploads/subida_previa";




//Select del archivo de Ventas
$sql_pyme="SELECT REPLACE(v.descripcion1,',','.') as descripcion1, v.cantidad, v.descripcion,v.ubicacion, i.codigo_barras
FROM $pyme.vw_existenciabyalmacen v, $pyme.item i
WHERE i.id_item = v.id_item
AND v.cantidad >0
AND i.cod_departamento = '1'
AND v.ubicacion<>'PISO DE VENTA'
ORDER BY descripcion, ubicacion";
$array_inventario=$comunes->ObtenerFilasBySqlSelect($sql_pyme);

$sql_pos="SELECT REPLACE(i.name, ',','.') as descripcion1, v.units as cantidad, 'descripcion', 'PISO DE VENTA', i.code
FROM $pos.stockcurrent v, $pos.products i
WHERE i.id = v.product
AND v.units>0
ORDER BY descripcion1";
$array_inventario_pos=$comunes->ObtenerFilasBySqlSelect($sql_pos);


//Se crea el archivo de Inventario para subir
		$cont=0;
		foreach ($array_inventario as $value) {
            foreach( $value as $key1){
                     
                     if($cont==4){
                        $contenido.=$key1.",".$sucursal.("\r\n");
                        $cont=0;
                      	}else{
                      
                		$contenido.=$key1.",";
                		$cont++; 
                		}   
                
            	}
        
                   
	 
          //  echo $contenido; exit();

        }
        $cont=0;
          foreach ($array_inventario_pos as $value) {
            foreach( $value as $key1){
                     
                     if($cont==4){
                        $contenido.=$key1.",".$sucursal.("\r\n");
                        $cont=0;
                        }else{
                      
                    $contenido.=$key1.",";
                    $cont++; 
                    }   
                
              }
        
                   
   
                //  echo $contenido; exit();
          }
$comunes->cerrar(); 
$contenido=  utf8_decode($contenido);
array_to_csv_download($contenido, $nomb);  

                
    function array_to_csv_download($array, $nomb, $delimiter=",") {
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
   //$f=$array;
    // rewrind the "file" with the csv lines
    //fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv; charset=UTF-8');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachement; filename="'.$nomb.'";');
    // make php send the generated csv lines to the browser
    echo $array;    
//fpassthru($f);
}
?>

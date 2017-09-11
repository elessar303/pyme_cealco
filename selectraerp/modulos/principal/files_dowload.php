<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
//Includes
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
//require_once "rar.php";
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

$pyme=DB_PYME;
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
$nomb="000".$sucursal.'_'.$dia.$mes.$ano.$hora.$min.".csv";
$nomb2="000".$sucursal.'_'.$dia.$mes.$ano.$hora.$min;
$nombre_inventario="000".$sucursal.'_'.$dia.$mes.$ano.$hora.$min.".csv";
$ultimaFecha = $comunes->ObtenerFilasBySqlSelect("SELECT * from $pos.control_fecha_archivo ORDER BY id DESC LIMIT 1");

//Establezco el rango de horas para el select comenzando desde la ultima vez que se actualizo el archivo
$fila=$comunes->getFilas();
if($fila==0){
	$fechaF=strftime( "%Y-%m-%d %H:%M:%S", time() );
        $fechaI=strftime( "%Y-%m-%d %H:%M:%S",strtotime("-30 minute"));

	}else{
	$fechaF=strftime( "%Y-%m-%d %H:%M:%S", time());
	$fechaI= strtotime ( '+1 second' , strtotime ( $ultimaFecha[0]["fecha"]." ".$ultimaFecha[0]["hora"].":".$ultimaFecha[0]["minutos"].":".$ultimaFecha[0]["segundos"] ) ) ;
	$fechaI=strftime( "%Y-%m-%d %H:%M:%S",$fechaI);
	}
//$fechaI="2014-04-10 14:31:44";
//Nombre de las rutas
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
$path_descarga_ventas="/var/www/pyme/selectraerp/uploads/descarga_ventas";
$path_inventarios="/var/www/pyme/selectraerp/uploads/inventarios";
$path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
$path_descomprimido="/var/www/pyme/selectraerp/uploads/recibidos/descomprimidos";
//ruta donde se guarda el archivo en el  punto obtenido del central
$path_consolidados="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
//ruta donde obtengo el arvhivo consolidado de las ventas del central
$path_central_cons="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
$path_subida_previa="/var/www/pyme/selectraerp/uploads/subida_previa";




//Select del archivo de Ventas
$sql="SELECT a.ID as id_tickets ,  a.TICKETTYPE ,  a.TICKETID ,  a.PERSON ,  a.CUSTOMER ,  a.STATUS,    b.TICKET  ,   b.LINE  ,   b.PRODUCT  ,   b.ATTRIBUTESETINSTANCE_ID  ,   b.UNITS  ,   b.PRICE  ,   b.TAXID as taxid_tikestline  ,      b.DATENEW as datenew_ticketlines,    c.ID as id_products  ,   c.REFERENCE  ,   c.CODE  ,   c.CODETYPE  ,   REPLACE(c.NAME ,',',' ') as nombre_producto  ,   c.PRICEBUY  ,   c.PRICESELL  ,   c.CATEGORY  ,   c.TAXCAT  ,   c.ATTRIBUTESET_ID  ,  c.STOCKCOST  ,   c.STOCKVOLUME  ,    cast(c.ISCOM as unsigned) as ISCOM  ,   cast(c.ISSCALE as unsigned) as ISSCALE,  c.QUANTITY_MAX  ,   c.TIME_FOR_TRY,    d.ID as id_gente  ,   d.SEARCHKEY  ,   d.TAXID  ,   REPLACE(d.NAME ,',',' ') as name_persona  ,   d.TAXCATEGORY  ,   d.CARD  ,   d.MAXDEBT  ,   d.ADDRESS  ,   d.ADDRESS2  ,   d.POSTAL  ,   d.CITY  ,   d.REGION  ,   d.COUNTRY  ,   REPLACE(d.FIRSTNAME ,',',' ') as FIRSTNAME,   REPLACE(d.LASTNAME ,',',' ') as LASTNAME  ,   d.EMAIL  ,   d.PHONE  ,   d.PHONE2  ,   d.FAX  ,   d.NOTES  ,    cast(d.VISIBLE as unsigned) as visible  ,   d.CURDATE  ,   d.CURDEBT  ,     e.ID as id_receipts  ,   e.MONEY as money_receipts  ,   e.DATENEW  ,   f.MONEY  ,   f.HOST  ,   f.HOSTSEQUENCE  ,   f.DATESTART  ,   f.DATEEND 
      from $pos.tickets as a, 
      $pos.ticketlines as b, 
      $pos.products as c, 
      $pos.customers as d, 
      $pos.receipts as e, 
      $pos.closedcash as f   
      where a.id=b.ticket and b.product=c.id 
      and a.customer=d.id and a.id=e.id and e.money=f.money 
      AND b.DATENEW BETWEEN '".$fechaI."' AND '".$fechaF."'
      ";
$array_venta = $comunes->ObtenerFilasBySqlSelect($sql);

//Se crea el archivo de Ventas para subir
$filas=$comunes->getFilas();
if($filas==0){
 echo" <script type='text/javascript'>
                alert('No Existen Ventas En El Periodo Actual');
                    history.go(-1);   
		    window.close();
                   </script>";
exit();


	$contenido="";
   
	}else{ 
		$cont=0;
		foreach ($array_venta as $value) {
            foreach( $value as $key1){
                     
                     if($cont==60){
                        $contenido.=$key1.",".$sucursal.("\r\n");
                        $cont=0;
                      	}else{
                      
                		$contenido.=$key1.",";
                		$cont++; 
                		}   
                
            	}
        
                   
	 
                //  echo $contenido; exit();
        	}
                
                
                }
               if($filas>0){
                    $instruccion = "
INSERT INTO ".$pos.".control_fecha_archivo (
`fecha`,
`hora`,
`minutos`,
`segundos`
)
VALUES (
 '".$ano."-".$mes."-".$dia."','".$hora."','".$min."','".$seg."'
);
";
$comunes->Execute2($instruccion);
$comunes->cerrar(); 
$contenido=  utf8_decode($contenido);

$pf_inv=fopen($path_descarga_ventas."/".$nomb,"w+");
fwrite($pf_inv, utf8_decode($contenido));
fclose($pf_inv);
shell_exec("cd /var/www/pyme/selectraerp/uploads/descarga_ventas");
shell_exec("bash /var/www/pyme/selectraerp/uploads/descarga_ventas/script_comprimir_cifrar.sh");
$f = fopen('php://memory', 'w');
$archivo_descarga=$path_descarga_ventas."/".$nomb2.".encrypted";
header("Content-disposition: attachment; filename=".$nomb2.".encrypted");
header("Content-type: application/octet-stream");
readfile($archivo_descarga); 
}
?>
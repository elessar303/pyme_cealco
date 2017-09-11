<?php
/*
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

//Includes
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");

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
$comunes = new Almacen();
$sql="SELECT bd from selectra_conf_pyme.nomempresa limit 1";
$bdpyme= $comunes->ObtenerFilasBySqlSelect($sql);
$pyme=$bdpyme[0]['bd'];
$pos=POS;
$tipo="E";
$cadena="52";
$sql="SELECT codigo_siga from $pyme.parametros_generales limit 1";
$codigosiga= $comunes->ObtenerFilasBySqlSelect($sql);
$sucursal=$codigosiga[0]['codigo_siga'];
$estado="0001";
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");
$nomb="000".$sucursal.'_'.$dia.$mes.$ano.$hora.".csv";
$nomb_pyme="000".$sucursal.'_'.$dia.$mes.$ano.$hora."_pyme.csv";

//Nombre de las rutas
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
$path_ventas_pyme="/var/www/pyme/selectraerp/uploads/ventas_pyme";
$path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
$path_descomprimido="/var/www/pyme/selectraerp/uploads/recibidos/descomprimidos";
//ruta donde se guarda el archivo en el  punto obtenido del central
$path_consolidados="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
//ruta donde obtengo el arvhivo consolidado de las ventas del central
$path_central_cons="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
$path_subida_previa="/var/www/pyme/selectraerp/uploads/subida_previa";

$sql_ultimo_ticket="SELECT id from $pos.ticketsnum";
$array_ticket = $comunes->ObtenerFilasBySqlSelect($sql_ultimo_ticket);
$ultimo_ticket = $array_ticket[0]['id'];

$sql_ultimo_ticket="SELECT ticket_id from $pos.ticket_control";
$array_ticket_control = $comunes->ObtenerFilasBySqlSelect($sql_ultimo_ticket);
$ultimo_ticket_control = $array_ticket_control[0]['ticket_id'];

$sql_ultimo_ticket_pyme="SELECT max(id_factura) as id from $pyme.factura";
$array_ticket_pyme = $comunes->ObtenerFilasBySqlSelect($sql_ultimo_ticket_pyme);
$ultimo_ticket_pyme = $array_ticket_pyme[0]['id'];

$sql_ultimo_ticket_pyme="SELECT ticket_id from $pyme.ticket_control_pyme"; 
$array_ticket_control = $comunes->ObtenerFilasBySqlSelect($sql_ultimo_ticket_pyme);
$ultimo_ticket_control_pyme = $array_ticket_control[0]['ticket_id'];

//Select del archivo de Ventas
$sql="SELECT a.ID as id_tickets ,  a.TICKETTYPE ,  a.TICKETID ,  a.PERSON ,  a.CUSTOMER ,  a.STATUS,    b.TICKET  ,   b.LINE  ,   b.PRODUCT  ,   b.ATTRIBUTESETINSTANCE_ID  ,   b.UNITS  ,   b.PRICE  ,   b.TAXID as taxid_tikestline  ,      b.DATENEW as datenew_ticketlines,    c.ID as id_products  ,   c.REFERENCE  ,   c.CODE  ,   c.CODETYPE  ,   REPLACE(c.NAME ,',',' ') as nombre_producto  ,   c.PRICEBUY  ,   c.PRICESELL  ,   c.CATEGORY  ,   c.TAXCAT  ,   c.ATTRIBUTESET_ID  ,  c.STOCKCOST  ,   c.STOCKVOLUME  ,    cast(c.ISCOM as unsigned) as ISCOM  ,   cast(c.ISSCALE as unsigned) as ISSCALE,  c.QUANTITY_MAX  ,   c.TIME_FOR_TRY,    d.ID as id_gente  ,   d.SEARCHKEY  ,   d.TAXID  ,   REPLACE(d.NAME ,',',' ') as name_persona  ,   d.TAXCATEGORY  ,   d.CARD  ,   d.MAXDEBT  ,   d.ADDRESS  ,   d.ADDRESS2  ,   d.POSTAL  ,   d.CITY  ,   d.REGION  ,   d.COUNTRY  ,  REPLACE(d.FIRSTNAME ,',',' ') as FIRSTNAME,  REPLACE(d.LASTNAME ,',',' ') as LASTNAME,   d.EMAIL  ,   d.PHONE  ,   d.PHONE2  ,   d.FAX  ,   d.NOTES  ,    cast(d.VISIBLE as unsigned) as visible  ,   d.CURDATE  ,   d.CURDEBT  ,     e.ID as id_receipts  ,   e.MONEY as money_receipts  ,   e.DATENEW  ,   f.MONEY  ,   f.HOST  ,   f.HOSTSEQUENCE  ,   f.DATESTART  ,   f.DATEEND
      from $pos.tickets as a,
      $pos.ticketlines as b,
      $pos.products as c,
      $pos.customers as d,
      $pos.receipts as e,
      $pos.closedcash as f
      where a.id=b.ticket and b.product=c.id
      and a.customer=d.id and a.id=e.id and e.money=f.money
      AND a.ticketid>$ultimo_ticket_control
      AND a.ticketid<=$ultimo_ticket";


$array_venta = $comunes->ObtenerFilasBySqlSelect($sql);


//Se crea el archivo de Ventas para subir
$filas=$comunes->getFilas();
if($filas==0){
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
        	}       
        $pf=fopen($path_ventas."/".$nomb,"w+");
        fwrite($pf, utf8_decode($contenido));
        
        fclose($pf);
        chmod($path_ventas."/".$nomb,  0777);
}
//Envio las ventas al servidor central
$connection = ssh2_connect('201.248.68.244', 59931); //colocar la ip del servidor central
  chmod($path_ventas."/".$nomb,  0777);  

if($connection){
    ssh2_auth_password($connection, 'root', 'admin.2021');
    $sftp=ssh2_sftp($connection);
    if($filas>0){
            if(ssh2_scp_send($connection, $path_ventas."/".$nomb, $path_subida_previa."/".$nomb, 0777)){
                echo "Subida de Ventas Exitosa";
                $comparar=compararTamano($path_subida_previa."/".$nomb, $path_ventas."/".$nomb,$sftp);
                if($comparar==0){
                    ssh2_sftp_unlink($sftp,$path_subida_previa."/".$nomb );                    
                }else{
                    $instruccion = "
                        INSERT INTO ".$pos.".control_fecha_archivo (
                        `fecha`,
                        `hora`,
                        `minutos`,
                        `segundos`,
                        `ticket_id`
                        )
                        VALUES (
                         '".$ano."-".$mes."-".$dia."','".$hora."','".$min."','".$seg."', '".$ultimo_ticket."'
                        );
                        ";
                    ssh2_sftp_rename($sftp, $path_subida_previa."/".$nomb, $path_ventas."/".$nomb);
                    $comunes->Execute2($instruccion);
                    $instruccion2="UPDATE ".$pos.".ticket_control SET ticket_id=".$ultimo_ticket."";

                    $comunes->Execute2($instruccion2);
                }
            }else{
            echo "No Exitoso";
            error_log("Error al enviar el archivo de ventas".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
            }

    }//fin del if de ventas
    //Actualizo la fecha de sincronizacion
      

}//fin del if comprobando conexion
else{
    error_log("Error al conectarse al servidor Central".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
}


//VENTAS PYME
//Select del archivo de Ventas
$sql="SELECT a.nombre, a.telefonos, a.email, a.direccion, a.rif, a.nit, b.id_factura, b.cod_factura_fiscal, b.impresora_serial, b.fechaFactura, b.subtotal, b.descuentosItemFactura, b.montoItemsFactura, b.ivaTotalFactura, b.TotalTotalFactura, b.cantidad_items, b.totalizar_total_general, b.formapago, b.fecha_creacion, f.nombreyapellido, b.usuario_creacion, d.codigo_barras, c._item_descripcion, c._item_cantidad, c._item_totalsiniva, c._item_totalconiva
FROM factura_detalle c
LEFT JOIN factura b ON b.id_factura = c.id_factura 
LEFT JOIN clientes a ON  a.id_cliente = b.id_cliente 
LEFT JOIN item d ON c.id_item = d.id_item 
LEFT JOIN factura_detalle_formapago e ON c.id_factura = e.id_factura
LEFT JOIN usuarios f ON b.cod_vendedor = f.cod_usuario
WHERE b.id_factura>$ultimo_ticket_control_pyme
AND b.id_factura<=$ultimo_ticket_pyme"; 
//echo $sql; exit();
$array_venta = $comunes->ObtenerFilasBySqlSelect($sql);
//Se crea el archivo de Ventas para subir
$filas=$comunes->getFilas();
if($filas==0){
  $contenido="";
  }else{ 
    $cont=0;
    foreach ($array_venta as $value) {
            foreach( $value as $key1){                     
                     if($cont==25){
                        $contenido.=$key1.";".$sucursal.("\r\n");
                        $cont=0;
                        }else{                      
                    $contenido.=$key1.";";
                    $cont++; 
                    }    
              }
          }       
        $pf=fopen($path_ventas_pyme."/".$nomb_pyme,"w+");
        fwrite($pf, utf8_decode($contenido));
        
        fclose($pf);
        chmod($path_ventas_pyme."/".$nomb_pyme,  0777);
}
//Envio las ventas al servidor central
$connection = ssh2_connect('201.248.68.244', 59931); //colocar la ip del servidor central
  chmod($path_ventas_pyme."/".$nomb_pyme,  0777);  

if($connection){
    ssh2_auth_password($connection, 'root', 'admin.2021');
    $sftp=ssh2_sftp($connection);
    if($filas>0){
            if(ssh2_scp_send($connection, $path_ventas_pyme."/".$nomb_pyme, $path_subida_previa."/".$nomb_pyme, 0777)){
                echo "Subida de Ventas Exitosa";
                $comparar=compararTamano($path_subida_previa."/".$nomb_pyme, $path_ventas_pyme."/".$nomb_pyme,$sftp);
                if($comparar==0){
                    ssh2_sftp_unlink($sftp,$path_subida_previa."/".$nomb_pyme );                    
                }else{
                    $instruccion = "
                        INSERT INTO control_fecha_archivo_pyme (
                        `fecha`,
                        `hora`,
                        `minutos`,
                        `segundos`,
                        `ticket_id`
                        )
                        VALUES (
                         '".$ano."-".$mes."-".$dia."','".$hora."','".$min."','".$seg."', '".$ultimo_ticket_pyme."'
                        );
                        ";
                    ssh2_sftp_rename($sftp, $path_subida_previa."/".$nomb_pyme, $path_ventas_pyme."/".$nomb_pyme);
                    $comunes->Execute2($instruccion);
                    $instruccion2="UPDATE ticket_control_pyme SET ticket_id=".$ultimo_ticket_pyme."";

                    $comunes->Execute2($instruccion2);
                }
            }else{
            echo "No Exitoso";
            error_log("Error al enviar el archivo de ventas".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
            }

    }//fin del if de ventas
    //Actualizo la fecha de sincronizacion
      

}//fin del if comprobando conexion
else{
    error_log("Error al conectarse al servidor Central".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
}

//Cierro las Conexiones
unset($connection);
$comunes->cerrar();
session_destroy();
*/
?>
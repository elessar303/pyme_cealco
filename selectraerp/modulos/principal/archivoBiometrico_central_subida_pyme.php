<?php
ini_set('memory_limit', '2048M');
ini_set("max_execution_time","1000000");
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
// $comunes = new ConexionComun();


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
$pos=POS;
$tipo="E";
$cadena="52";
$sucursal="0286";
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");
$nombre1=$cadena."_".$dia.$mes.$ano.$hora.$min;
 $nomb=$cadena."_".$dia.$mes.$ano.$hora.$min.".csv";

//PATHS
// $path_cantv="c:/wamp/www/siscolp-pdval/pyme/selectraerp/uploads/cantv";
$path_cantv="/var/www/pyme/selectraerp/uploads/cantv";
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas_pyme";
$path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
$path_consolidados="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
$path_ventas_reportesweb="/var/www/reportesweb/uploads/ventas";
$path_downloads="/var/www/pyme/selectraerp/downloads";
$path_descarga="/var/www/pyme/selectraerp/uploads/descarga_temporal";
$path_descarga_manual="/var/www/pyme/selectraerp/uploads/descarga_ventas_manuales";


//*********************************JUNIOR AYALA**************************************************
$conex1=$conn_pyme = new ConexionComun();
$pymeC="selectrapyme_central";

$directorio=dir("$path_ventas");
$contador_regxdia=0;
$contador_regxdia1=0;


while ($archivo = $directorio->read()) {

    if (substr($archivo,0,1)!=".") {

        $filas=file($path_ventas."/".$archivo);
        $i=0;
        $rs_ins=0;

        while($filas[$i]!=NULL) {
            $row = $filas[$i];
            //Eliminamos Caracteres especiales en la linea a insertar
            $row = str_replace("/","",$row);
            $row = str_replace("&","",$row);
            $row = str_replace("'","",$row);
            $row = str_replace("+","",$row);
            $row = str_replace("!","",$row);
            $row = str_replace("�","",$row);
            $row = str_replace("?","",$row);
            $row = str_replace("*","",$row);
            $row = str_replace("#","",$row);
            $row = str_replace("%","",$row);
            $row = str_replace("(","",$row);
            $row = str_replace(")","",$row);
            $row = str_replace("$","",$row);
            $row = str_replace("´","",$row);
            $row = str_replace(chr(92),"",$row); // Caracter \
            $row = str_replace(chr(147),"",$row); // Caracter "
            $row = str_replace(chr(192),"",$row); // Caracter
            $row = str_replace(chr(193),"",$row); // Caracter
            $row = str_replace(chr(194),"",$row); // Caracter
            $row = str_replace(chr(195),"",$row); // Caracter
            $row = str_replace(chr(196),"",$row); // Caracter
            $row = str_replace(chr(197),"",$row); // Caracter
            $row = str_replace(chr(186),"",$row); // Caracter
            $row = str_replace(chr(176),"",$row); // Caracter
            $row = str_replace(chr(177),"",$row); // Caracter
            $row = str_replace(chr(170),"",$row); // Caracter
            $row = str_replace(chr(34),"",$row); // Caracter
            $row = str_replace(chr(39),"",$row); // Caracter
            $row = str_replace(chr(145),"",$row); // Caracter
            $row = str_replace(chr(146),"",$row); // Caracter
            $row = str_replace(chr(147),"",$row); // Caracter
            $row = str_replace(chr(148),"",$row); // Caracter
            $row = str_replace(chr(149),"",$row); // Caracter
            $row = str_replace(chr(180),"",$row); // Caracter
            $values = explode(";",$row);
            $values[26] = preg_replace('/\s\s+/', '', $values[26]);

            $siga=substr($archivo,3,3);

            $sql="select id from $pymeC.puntos_venta where codigo_siga_punto='000".$siga."'";
            //echo $sql; exit();
            $comprobar=$comunes->ObtenerFilasBySqlSelect($sql);
            $resultado_punto=$comunes->getFilas();

            // comentado para pruebas if($resultado_punto>0 && $values[13]!='0000-00-00 00:00:00'){

                // para reportes de estatus de punto
              $verificar=$comunes->ObtenerFilasBySqlSelect("select id from $pymeC.reportes_ventas where codigo_siga='000".$siga."'");
              $resultado_verificar=$comunes->getFilas();
                if($resultado_verificar>0){
             $update=$comunes->Execute2("update $pymeC.reportes_ventas set fecha=now(), reporto=1 where codigo_siga='000".$siga."'");
             $historico=$comunes->ObtenerFilasBySqlSelect("select id from  $pymeC.reporte_ventas_historico where codigo_siga='000".$siga."' and fecha=date(now())");
           
            $historico_verificar=$comunes->getFilas();
             if($historico_verificar==0){
              //echo "insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('000".$values[61]."', date(now()))";exit();
              $historico_insert=$comunes->Execute2("insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('000".$siga."', date(now()))");
             }

             $contador_regxdia1=1;
             if(!$update)
            error_log("Error al realizar update:".$update.("\r\n"),3,"/var/www/pyme/selectraerp/uploads/error/error.log");
             }else{
             $insert=$comunes->Execute2("insert into $pymeC.reportes_ventas (codigo_siga,fecha,reporto) values ('000".$siga."', now(),1)");
             $historico=$comunes->ObtenerFilasBySqlSelect("select id from  $pymeC.reporte_ventas_historico where codigo_siga='000".$siga."' and fecha=date(now())");
             $historico_verificar=$comunes->getFilas();
             if($historico_verificar==0){
              $historico_insert=$comunes->Execute2("insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('000".$siga."', date(now()))");
             }
             $contador_regxdia1=1;
             if(!$insert)
              error_log("Error al realizar insert:".$insert.("\r\n"),3,"/var/www/pyme/selectraerp/uploads/error/error.log");
            }

            $mes_des=substr($values[18],5,2);
            $anno_des=substr($values[18],2,2);
            $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_pyme"; 

            $sql_ver_pyme="SELECT id FROM $pymeC.$ventas_mes_anno WHERE id_factura=".$values[6]." and codigo_siga='000".$siga."' and codigo_barras='".$values[21]."' and id_detalle_factura=".$values[36]."";
            //echo $sql_ver_pyme; exit();
            $comunes->ObtenerFilasBySqlSelect($sql_ver_pyme);
            $ver_pyme=$comunes->getFilas();
            if ($ver_pyme>0) {
                $sql_del="DELETE FROM $pymeC.$ventas_mes_anno WHERE id_factura=".$values[6]." and codigo_siga='000".$siga."' and codigo_barras='".$values[21]."'";
                $rs_ins=$comunes->Execute2($sql_del);

            }

            $anno_valido=substr($values[18],0,4);

            $sql ="INSERT INTO $pymeC.$ventas_mes_anno(nombre, telefonos, email, direccion, rif, nit, id_factura, cod_factura_fiscal, impresora_serial, fechaFactura, subtotal, descuentosItemFactura, montoItemsFactura, ivaTotalFactura, TotalTotalFactura, cantidad_items, totalizar_total_general, formapago, fecha_creacion, nombreyapellido, usuario_creacion, codigo_barras, _item_descripcion, _item_cantidad, _item_totalsiniva, _item_totalconiva, codigo_siga, monto_efectivo, monto_cheque, nro_cheque, banco_cheque, monto_tarjeta, monto_deposito, nro_deposito, banco_deposito, monto_credito, id_detalle_factura)
                   VALUES ('".$values[0]."','".$values[1]."','".$values[2]."','".$values[3]."','".$values[4]."','".$values[5]."','".$values[6]."',
                   '".$values[7]."','".$values[8]."','".$values[9]."','".$values[10]."','".$values[11]."','".$values[12]."',
                  '".$values[13]."','".$values[14]."','".$values[15]."','".$values[16]."','".$values[17]."','".$values[18]."','".$values[19]."','".$values[20]."','".$values[21]."','".$values[22]."','".$values[23]."','".$values[24]."','".$values[25]."', '000".$siga."','".$values[27]."', '".$values[28]."', '".$values[29]."','".$values[30]."','".$values[31]."','".$values[32]."','".$values[33]."','".$values[34]."','".$values[35]."', '".$values[36]."')"; 
                  if ($anno_valido=='2017' && $values[36]!='') {
                    $rs_ins=$conn_pyme->Execute2($sql);
                  }
                    


                if($rs_ins!=1) {
			    error_log("Error al insertar fila del archivo ".$archivo." Codigo SIGA: ".$siga." dentro del archivo"." Query: ".$sql."".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
                }

            /*comentado para pruebas
             } //fin del if de comprobar si existe el punto a insertar o si la fecha esta correcta
             else{
                 error_log("Error al verificar archivo ".$archivo." Codigo SIGA: ".$siga."  fecha o codigo siga dentro del archivo".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
             }*/

          $i++;
          } //Fin While Interno

        



        //MOVER EL ARCHIVO RECORRIDO AL MOMENTO
        if($rs_ins==1) {
            //$connection = ssh2_connect('192.168.8.8', 22);
            //ssh2_auth_password($connection, 'prueba', '123456');
            //$sftp=ssh2_sftp($connection);
            //ssh2_scp_send($connection, $path_ventas."/".$archivo, $path_ventas_reportesweb."/".$archivo, 0777);
            unlink($path_ventas."/".$archivo);
            //unset($connection);
        }

    } //Fin if (substr($archi,0,1)!=".")
   //unlink($path_ventas."/".$archivo);
}//Fin While Externo
if($contador_regxdia1>0){

  $selec_resumen=$comunes->ObtenerFilasBySqlSelect("SELECT sum(reporto) as total FROM $pymeC.reportes_ventas WHERE date(fecha)=date(now()) and reporto=1");
  $contar1=$comunes->getFilas();

          if($contar1>0){

            $verificar_fecha=$comunes->ObtenerFilasBySqlSelect("select id from $pymeC.resumen_reporte_ventas where fecha=date(now())");
            $sifecha=$comunes->getFilas();
        
            if($sifecha>0){
            $update_resumen=$comunes->Execute2("update $pymeC.resumen_reporte_ventas set total=".$selec_resumen[0]['total']."  where  fecha=date(now())");
         
             if(!$update_resumen)
            error_log("Error al realizar update_resumen:".$update_resumen.("\r\n"),3,"/var/www/pyme/selectraerp/uploads/error/error.log");
        }
          else
          { 
             $insert_resumen=$comunes->Execute2("insert into $pymeC.resumen_reporte_ventas (fecha,total) values(date(now()),".$selec_resumen[0]['total'].")");
            
              if(!$insert_resumen)
            error_log("Error al realizar update_resumen:".$insert_resumen.("\r\n"),3,"/var/www/pyme/selectraerp/uploads/error/error.log");
          }
}//fin del if de si reportaron
}//fin del if de resumen
?>
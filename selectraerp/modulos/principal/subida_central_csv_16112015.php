<?php
ini_set("memory_limit","512M");
ini_set("upload_max_filesize","300M");

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
$path_local="/var/www/pyme/selectraerp/uploads/temporal_subida_central/";



if ($_FILES['archivo_ventas']["error"] > 0)
	  {
	  echo" <script type='text/javascript'>
                alert('Error Al Subir Archivo');
                    history.go(-1);       
                   </script>";
		exit();
	  }
if(!empty($_FILES['archivo_ventas'])){
   
    //echo $_FILES['archivo_ventas']['type'];  exit();
    $string="application/csv";
	$string1=".csv";//echo substr($_FILES['archivo_ventas']['name'],-4); exit();
//	echo $valor=strnatcasecmp($string1,substr($_FILES['archivo_ventas']['name'],-4
	if(strnatcasecmp($string1,substr($_FILES['archivo_ventas']['name'],-4))!=0){

	echo"	<script type='text/javascript'>
                alert('Error El Formato De Archivo No Es Valido, Unicamente Extensiones CSV');
                    history.go(-1);       
                   </script>";exit();
}

/*    if(strnatcasecmp ($string,$_FILES['archivo_ventas']['type'])!=0){
       echo" <script type='text/javascript'>
                alert('Error El Formato De Archivo No Es Valido, Unicamente Extensiones CSV');
                    history.go(-1);       
                   </script>";
   
    };*/
    $nombre=$_FILES['archivo_ventas']['name'];
      if(move_uploaded_file($_FILES['archivo_ventas']['tmp_name'],$path_local.$_FILES['archivo_ventas']['name'])){
           echo" <script type='text/javascript'>
                alert('Exitosa Carga De Archivo');
                    history.go(-1);
                   </script>";
        }else{
            		echo" <script type='text/javascript'>
               		 alert('Error Al Cargar Archivo');
                   	 history.go(-1);       
                   	</script>";
            		exit();
            
        }
        chmod($path_local.$_FILES['archivo_ventas']['name'], 0777);
 
   

//*********************************JUNIOR AYALA**************************************************       
$conex1=$conn_pyme = new ConexionComun();
$pymeC="selectrapyme_central";
$mes_des=date('m');
$anno_des=date('y');
$ventas_mes_anno="ventas_".$mes_des."_".$anno_des;
$directorio=dir("$path_local");
$contador_regxdia=0;
while ($archivo = $directorio->read()) {
    
    if (substr($archivo,0,1)!=".") {
        $filas=file($path_local."/".$archivo);
        $i=0;
        $rs_ins=0;
        
        while($filas[$i]!=NULL) {
            $row = $filas[$i];
            //Eliminamos Caracteres especiales en la linea a insertar
            $row = str_replace("/","",$row);
            $row = str_replace("&","",$row);
            $row = str_replace("'","",$row);
            $row = str_replace(";","",$row);
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
            $values = explode(",",$row);
	    $values[61] = preg_replace('/\s\s+/', '', $values[61]);
		//verificamos si el codigo pto esta bien
		
	    	$comprobar=$comunes->ObtenerFilasBySqlSelect("select id from $pymeC.puntos_venta where codigo_siga_punto='000".$values[61]."'");
            	$resultado_punto=$comunes->getFilas();
		if($resultado_punto>0 && $values[13]!='0000-00-00 00:00:00'){
        $verificar=$comunes->ObtenerFilasBySqlSelect("select id from $pymeC.reportes_ventas where codigo_siga='000".$values[61]."'");
        $resultado_verificar=$comunes->getFilas();
        if($resultado_verificar){
          $update=$comunes->Execute2("update $pymeC.reportes_ventas set fecha=now(), reporto=1 where codigo_siga='000".$values[61]."'");
           $contador_regxdia++;
           $historico=$comunes->ObtenerFilasBySqlSelect("select id from  $pymeC.reporte_ventas_historico where codigo_siga='000".$values[61]."' and fecha=date(now())");
             $historico_verificar=$comunes->getFilas();
             if($historico_verificar==0){
              $historico_insert=$comunes->Execute2("insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('000".$values[61]."', date(now()))");
             }
          if(!$update)
            error_log("Error al realizar update:".$update.("\r\n"),3,"/var/www/pyme/selectraerp/uploads/error/error.log");
        }else{
          $insert=$comunes->Execute2("insert into $pymeC.reportes_ventas (codigo_siga,fecha,reporto) values ('000".$values[61]."', now(),1)");
          $contador_regxdia++;
           $historico=$comunes->ObtenerFilasBySqlSelect("select id from  $pymeC.reporte_ventas_historico where codigo_siga='000".$values[61]."' and fecha=date(now())");
             $historico_verificar=$comunes->getFilas();
             if($historico_verificar==0){
              $historico_insert=$comunes->Execute2("insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('000".$values[61]."', date(now()))");
             }
          if(!$insert)
              error_log("Error al realizar insert:".$insert.("\r\n"),3,"/var/www/pyme/selectraerp/uploads/error/error.log");
        }

		
            $sql ="INSERT INTO $pymeC.$ventas_mes_anno(id_tickets, TICKETTYPE, TICKETID, PERSON, CUSTOMER, STATUS, TICKET, LINE, PRODUCT, 
                   ATTRIBUTESETINSTANCE_ID, UNITS, PRICE, taxid_tikestline, datenew_ticketlines, id_products, REFERENCE, CODE, CODETYPE, 
                   nombre_producto, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ATTRIBUTESET_ID, STOCKCOST, STOCKVOLUME, ISCOM, ISSCALE, QUANTITY_MAX, 
                   TIME_FOR_TRY, id_gente, SEARCHKEY, TAXID, name_persona, TAXCATEGORY, CARD, MAXDEBT, ADDRESS, ADDRESS2, POSTAL, CITY, 
                   REGION, COUNTRY, FIRSTNAME, LASTNAME, EMAIL, PHONE, PHONE2, FAX, NOTES, visible, CURDATE, CURDEBT, id_receipts, 
                   money_receipts, DATENEW, MONEY, HOST, HOSTSEQUENCE, DATESTART, DATEEND, categoria_conversor, id_categoria_pos, cod_categoria, codigo_siga) 
                   VALUES ('".$values[0]."','".$values[1]."','".$values[2]."','".$values[3]."','".$values[4]."','".$values[5]."','".$values[6]."',
                   '".$values[7]."','".$values[8]."','".$values[9]."','".$values[10]."','".$values[11]."','".$values[12]."',
                  '".$values[13]."','".$values[14]."','".$values[15]."','".$values[16]."','".$values[17]."','".$values[18]."','".$values[19]."',
                  '".$values[20]."','".$values[21]."','".$values[22]."','".$values[23]."','".$values[24]."','".$values[25]."','".$values[26]."',
                  '".$values[27]."','".$values[28]."','".$values[29]."','".$values[30]."','".$values[31]."','".$values[32]."','".$values[33]."',
                  '".$values[34]."','".$values[35]."','".$values[36]."','".$values[37]."','".$values[38]."','".$values[39]."','".$values[40]."',
                  '".$values[41]."','".$values[42]."','".$values[43]."','".$values[44]."','".$values[45]."','".$values[46]."','".$values[47]."',
                  '".$values[48]."','".$values[49]."','".$values[50]."','".$values[51]."','".$values[52]."','".$values[53]."','".$values[54]."',
                  '".$values[55]."','".$values[56]."','".$values[57]."','".$values[58]."','".$values[59]."','".$values[60]."','NULL',
                  'NULL','NULL','000".$values[61]."')";

               	 	$rs_ins=$conn_pyme->Execute2($sql);
                
                	if($rs_ins!=1) {
                   	  error_log("Error al insertar fila del archivo ".$archivo." Codigo SIGA: ".$values[61]." dentro del archivo".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
                    		}//fin el if de verificar la inserccion
                		}//fin del if de verificar si el codigo siga es correcto al igual q la fecha
				          else{
            				 error_log("Error, las columnas del ".$archivo." o el codigo siga estan incorrectos. Codigo SIGA: ".$values[61]." dentro del archivo".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
            				} //fin del else de error en columnas
                            
                       			$i++;     
                      } //Fin While Interno
                        //entrada a reporte diario
                     
                    //SE ENVIA A LA CARPETA DE VENTAS EN EL SERVIDOR DE POSTGRES PARA SU CARGA 
                      if($rs_ins==1) {
                      rename($archivo, "./ventas_postgres/".$archivo);
            		}
                      unlink($path_local."/".$archivo);
                      }
	

    
}//Fin While Externo

 if($contador_regxdia>0){

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


$conn_pyme->cerrar();

}
$comunes->cerrar();
?>

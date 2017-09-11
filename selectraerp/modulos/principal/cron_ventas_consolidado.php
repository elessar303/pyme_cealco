<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/clases/clase_db.inc.php");

$conex1=$conn_pyme = new ConexionComun();

$path="/var/www/pyme/selectraerp/uploads/ventas/";

$directorio=dir($path);

while ($archivo = $directorio->read()) {
    
    if (substr($archivo,0,1)!=".") {
        $filas=file("/var/www/pyme/selectraerp/uploads/ventas/".$archivo);
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
            $row = str_replace("-","",$row);
            $row = str_replace("!","",$row);
            $row = str_replace("�","",$row);
            $row = str_replace("?","",$row);
            $row = str_replace("*","",$row);
            $row = str_replace("#","",$row);
            $row = str_replace("%","",$row);
            $row = str_replace("(","",$row);
            $row = str_replace(")","",$row);
            $row = str_replace(":","",$row);
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

            $sql ="INSERT INTO selectrapyme_central.ventas(id_tickets, TICKETTYPE, TICKETID, PERSON, CUSTOMER, STATUS, TICKET, LINE, PRODUCT, 
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
                  '".$values[55]."','".$values[56]."','".$values[57]."','".$values[58]."','".$values[59]."','".$values[60]."','".$values[61]."',
                  '".$values[62]."','".$values[63]."','000".$values[64]."')";
                $rs_ins=$conn_pyme->Execute2($sql);
                //echo $sql."<br>";
                if($rs_ins!=1) {
                    //echo $i."-".$archivo."<br>";
                    //echo $sql."<br>";
                    exit;
                }
                
                
           $i++;     
          } //Fin While Interno
        
          if($rs_ins==1) {unlink("/var/www/pyme/selectraerp/uploads/ventas/".$archivo);}
           
    } //Fin if (substr($archi,0,1)!=".")
    
}//Fin While Externo


?>

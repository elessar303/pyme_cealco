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
include("../../libs/php/clases/almacen.php");
$comunes = new Producto();
$almacen = new Almacen();
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
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
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
        $almacen->BeginTrans();
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

            $mes_des=substr($values[13],5,2); 
            $anno_des=substr($values[13],2,2); 
            $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;
            $ventas_anno="ventas_".$anno_des; 

            $comprobar=$comunes->ObtenerFilasBySqlSelect("select id from ".$pymeC.".puntos_venta where codigo_siga_punto='000".$values[61]."'");
            $resultado_punto=$comunes->getFilas();

            $sql="select id_ventas from ".$pymeC.".".$ventas_mes_anno." where codigo_siga='000".$values[61]."' and id_tickets='".$values[0]."' and CODE='".$values[16]."'";

            $duplicado=$comunes->ObtenerFilasBySqlSelect($sql);
            $filas_duplicado=$comunes->getFilas();
            if($resultado_punto>0 && $values[13]!='0000-00-00 00:00:00' && $filas_duplicado==0){

            $sql ="INSERT INTO ".$pymeC.".".$ventas_mes_anno."(id_tickets, TICKETTYPE, TICKETID, PERSON, CUSTOMER, STATUS, TICKET, LINE, PRODUCT,
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

            //echo $archivo; 
              
                    $insert_ventas=$almacen->ExecuteTrans($sql);
              

             } //fin del if de comprobar si existe el punto a insertar o si la fecha esta correcta

            $sql="select id_ventas from ".$pymeC.".".$ventas_anno." where codigo_siga='000".$values[61]."' and id_tickets='".$values[0]."' and CODE='".$values[16]."'";

            $duplicado2=$comunes->ObtenerFilasBySqlSelect($sql); 
            $filas_duplicado2=$comunes->getFilas();
            if($resultado_punto>0 && $values[13]!='0000-00-00 00:00:00' && $filas_duplicado2==0){

            $sql ="INSERT INTO ".$pymeC.".".$ventas_anno."(`id_tickets`, `UNITS`, `PRICE`, `datenew_ticketlines`, `CODE`, `nombre_producto`, `PRICEBUY`, `PRICESELL`, `TAXCAT`, `SEARCHKEY`, `name_persona`, `DATENEW`, `MONEY`, `HOST`, `HOSTSEQUENCE`, `DATESTART`, `DATEEND`, `categoria_conversor`, `id_categoria_pos`, `cod_categoria`, `codigo_siga`)
              SELECT `id_tickets`, `UNITS`, `PRICE`, `datenew_ticketlines`, `CODE`, `nombre_producto`, `PRICEBUY`, `PRICESELL`, `TAXCAT`, `SEARCHKEY`, `name_persona`, `DATENEW`, `MONEY`, `HOST`, `HOSTSEQUENCE`, `DATESTART`, `DATEEND`, `categoria_conversor`, `id_categoria_pos`, `cod_categoria`, `codigo_siga` FROM ".$pymeC.".".$ventas_mes_anno." where codigo_siga='000".$values[61]."' and id_tickets='".$values[0]."' and CODE='".$values[16]."'"; 

            //echo $archivo; 
              
                    $insert_ventas=$almacen->ExecuteTrans($sql);
              

             } //fin del if de comprobar si existe el punto a insertar o si la fecha esta correcta


          $i++;
          } //Fin While Interno

        



        //MOVER EL ARCHIVO RECORRIDO AL MOMENTO

        

    } //Fin while interno 
    
    if($almacen->errorTransaccion==0) {
     $fecha_log=date('d-m-Y H:i:s');
     echo "Archivo no procesado: ".$archivo."<br>";
     error_log("Error al insertar el archivo: ".$archivo." Codigo SIGA: ".$values[61].". Fecha del Log: ".$fecha_log.("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
    }else{
   	$almacen->CommitTrans(1);

    echo "Archivo procesado: ".$archivo."<br>";
    unlink($path_ventas."/".$archivo);

    $verificar=$comunes->ObtenerFilasBySqlSelect("select id from $pymeC.reportes_ventas where codigo_siga='000".$values[61]."'");
              $resultado_verificar=$comunes->getFilas();
                if($resultado_verificar>0){
             $update=$comunes->Execute2("update $pymeC.reportes_ventas set fecha=now(), reporto=1 where codigo_siga='000".$values[61]."'");
             $historico=$comunes->ObtenerFilasBySqlSelect("select id from  $pymeC.reporte_ventas_historico where codigo_siga='000".$values[61]."' and fecha=date(now())");
           
            $historico_verificar=$comunes->getFilas();
             if($historico_verificar==0){
              //echo "insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('000".$values[61]."', date(now()))";exit();
              $historico_insert=$comunes->Execute2("insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('000".$values[61]."', date(now()))");
             }

             $contador_regxdia1=1;
             if(!$update){
            //error_log("Error al realizar update:".$update.("\r\n"),3,"/var/www/pyme/selectraerp/uploads/error/error.log");
             }else{
             $insert=$comunes->Execute2("insert into $pymeC.reportes_ventas (codigo_siga,fecha,reporto) values ('000".$values[61]."', now(),1)");
             $historico=$comunes->ObtenerFilasBySqlSelect("select id from  $pymeC.reporte_ventas_historico where codigo_siga='000".$values[61]."' and fecha=date(now())");
             $historico_verificar=$comunes->getFilas();
             if($historico_verificar==0){
              $historico_insert=$comunes->Execute2("insert into  $pymeC.reporte_ventas_historico (codigo_siga,fecha) values('000".$values[61]."', date(now()))");
             }
             $contador_regxdia1=1;
             if(!$insert){ error_log("Error al realizar insert:".$insert.("\r\n"),3,"/var/www/pyme/selectraerp/uploads/error/error.log");}
            }
    unlink($path_ventas."/".$archivo);

    }
    //echo $archivo."<br><br>";

}//Fin if (substr($archi,0,1)!=".")


}//Fin While Externo

exit();
//*********************************FIN JUNIOR AYALA**************************************************************

//OBTENER ULTIMA FECHA
$ultimaFecha = $comunes->ObtenerFilasBySqlSelect("SELECT * from $pyme.control_fecha_archivo ORDER BY id DESC LIMIT 1");
$fila=$comunes->getFilas();
if($fila==0){
  $fechaF=strftime( "%Y-%m-%d %H:%M:%S", time() );
        $fechaI=strftime( "%Y-%m-%d %H:%M:%S",strtotime("-32 minute"));

}else{
  $fechaF=strftime( "%Y-%m-%d %H:%M:%S", time());
  $fechaI= strtotime ( '+1 second' , strtotime ( $ultimaFecha[0]["fecha"]." ".$ultimaFecha[0]["hora"].":".$ultimaFecha[0]["minutos"].":".$ultimaFecha[0]["segundos"] ) ) ;
  $fechaI=strftime( "%Y-%m-%d %H:%M:%S",$fechaI);

}

//fechas de prueba quitar en produccion
// $fechaI="2015-04-10 00:00:00";
// $fechaF="2015-04-10 23:00:00";
//OBTENER INFORMACION DE LOS ARCHIVOS
//nombre de la BD
$pymeC="selectrapyme_central";
// $pymeC=DB_SELECTRA_FAC;

//$fechaI="2015-05-18 15:00:02";
//$fechaF="2015-05-18 17:00:02";
//$hora==23 && $min<=37 && $min>=34
//enviando local

$fechaII=strftime( "%Y-%m-%d 00:00:00", time());
$fechaFF=strftime( "%Y-%m-%d %H:%M:%S", time());

$control_id=$comunes->ObtenerFilasBySqlSelect("select * from $pymeC.trasmision_control");

$sql="update $pymeC.trasmision_control set id=(select max(id_ventas) from $pymeC.$ventas_mes_anno where date(datenew_ticketlines)=date(now())) ";
echo $sql;
$nuevo_maximo=$comunes->Execute2($sql);

 $control_maximo=$comunes->ObtenerFilasBySqlSelect("select * from $pymeC.trasmision_control");



//enviando automatico
$array_cantv=$comunes->ObtenerFilasBySqlSelect("SELECT SUBSTRING(taxid,1,1) as nacionalidad,SUBSTRING(taxid,2) as cedula, datenew_ticketlines, b.cod_categoria, units, 52, SUBSTRING(codigo_siga,4) as sucursal, SUBSTRING( c.codigo_estado, 3 ) as estado
FROM $pymeC.$ventas_mes_anno, $pymeC.categoria_conversor AS b, $pymeC.estados AS c, $pymeC.puntos_venta AS d 
where
category = b.id_categoria_pos
AND c.codigo_estado = d.codigo_estado_punto
AND codigo_siga = d.codigo_siga_punto
AND id_ventas>".$control_id[0]['id']." and id_ventas<=".$control_maximo[0]['id']." ");






// $path_cantv="/var/www/pyme/selectraerp/uploads/cantv/";

// echo "<pre>";
// print_r($array_cantv);exit;
// echo "</pre>";

$resul=$comunes->getFilas();

// echo "<pre>";

if($resul<1){

    $contenido="";

}else{
        $cont=0;

   //CREA EL STRING PARA EL ARCHIVO Q SE  LE ENVIA A CANTV
        foreach ($array_cantv as $key1)  {
             $contenido_bloqueados.='" ","'.$key1["nacionalidad"].'-'.$key1["cedula"].'","'.$key1["datenew_ticketlines"].'","'.$key1["cod_categoria"].'",'.$key1["units"].',"'.$key1["52"].'","'.$key1["sucursal"].'","'.$key1["estado"].'"'."\r\n";
        }


     //echo $contenido_bloqueados;exit;
        //CREA EL ARCHIVO
  $pf=fopen($path_cantv."/".$nomb,"w+");
        fwrite($pf, $contenido_bloqueados);
        fclose($pf);
        chmod($path_cantv."/".$nomb,  0777);

}



if($resul>0){

//encripta y zipea el archivo
    shell_exec("cd /var/www/pyme/selectraerp/uploads/cantv");
    shell_exec("bash /var/www/pyme/selectraerp/uploads/cantv/script_comprimir_cifrar.sh");


chmod($path_cantv."/".$nombre1.".txt",  0777);
chmod($path_cantv."/".$nombre1.".encrypted",  0777);
chmod($path_cantv."/".$nombre1.".zip",  0777);
}


//CLASE PARA ENVIAR EL ARCHIVO DEL CENTRAL AL  CANTV
class SFTPConnection
{
    private $connection;
    private $sftp;
    public $valor2;
    public $auth;
    public function __construct($host, $port=22)
    {
        $this->connection = @ssh2_connect($host, $port);
        if (! $this->connection)
            throw new Exception("Could not connect to $host on port $port.");
    }

    public function login($username, $password)
    {  //$this->auth=ssh2_auth_password($this->connection, $username, $password);
        if (! $this->auth=@ssh2_auth_password($this->connection, $username, $password))
            throw new Exception("Could not authenticate with username $username " .
                                "and password $password.");

        $this->sftp = @ssh2_sftp($this->connection);
        if (! $this->sftp)
            throw new Exception("Could not initialize SFTP subsystem.");
    }

    public function uploadFile($local_file, $remote_file)
    {
        $sftp = $this->sftp;
        $stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');

        if (! $stream)
            throw new Exception("Could not open file: $remote_file");

        $data_to_send = @file_get_contents($local_file);
        if ($data_to_send === false)
            throw new Exception("Could not open local file: $local_file.");

        if (@fwrite($stream, $data_to_send) === false)
            throw new Exception("Could not send data from file: $local_file.");

        @fclose($stream);
    }

    public function download (){
      $sftp = $this->sftp;
      $auth=$this->connection;
      $handle = opendir("ssh2.sftp://$sftp/recibido/");
      while (false != ($entry = readdir($handle))){
        //echo "$entry".("\n");
        $string="tcr";
        if(strnatcasecmp ($string,substr($entry,0,3))==0){
            //echo 'recibido/'.$entry; exit();
            ssh2_scp_recv($auth, 'recibido/'.$entry, '/home/tecnologia/csv/archivollego.zip');
        }

      }

    }


    public function leer(){

        $path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
        $sftp = $this->sftp;
        $read=0;
        $handle = opendir("ssh2.sftp://$sftp/recibido/");
        while (false != ($entry = readdir($handle))){
            //echo "$entry".("\n");
            $string="tcr";
            if(strnatcasecmp ($string,substr($entry,0,3))==0){
      //  echo 'recibido/'.$entry; exit();
        //ssh2_scp_recv($auth, 'recibido/'.$entry, '/home/tecnologia/csv/archivollego.zip');
        // Local stream
                $dia=date("d");
                $mes=date("m");
                $ano=date("y");
                $hora=date("H");
                $min=date("i");
                $seg=date("s");
                $filename="consolidado_bloqueados".$dia.$mes.$ano."_".$hora.$min.$seg.".zip";
                if (!$localStream = @fopen($path_beneficiarios."/".$filename, 'w')) {

                    throw new Exception("Unable to open local file for writing: ".$path_beneficiarios."/".$filename);
                }

                if (!$remotefile = @fopen("ssh2.sftp://$sftp/recibido/$entry", 'r')) {
                    throw new Exception("Unable to open remote file for reading: ssh2.sftp://$sftp/recibido/$entry");
                }

                $fileSize = filesize("ssh2.sftp://$sftp/recibido/$entry");
                while ($read < $fileSize && ($buffer = fread($remotefile, $fileSize - $read))) {
                    // Increase our bytes read
                    $read += strlen($buffer);

                    // Write to our local file
                    if (fwrite($localStream, $buffer) === FALSE) {
                        throw new Exception("Unable to write to local file: /localdir/$fileName");
                    }
                }

                chmod($path_beneficiarios."/".$filename,  0777);
                fclose($localStream);
                fclose($remotefile);



            }
        }
    }
}//FIN DE LA CLASE

if($resul>0){

try
{

//SUBIR ARCHIVO A CANTV COMENTADO MIETRAS SE PRUEBA SOLO ENVIAR UN ARCHIVO OJO
    $sftp = new SFTPConnection("190.9.132.66", 22);
    $sftp->login("alsegura", "4lsegur4*");
    $sftp->uploadFile("$path_cantv"."/".$nombre1.".txt", "/enviado/".$nombre1.".txt");
    $sftp->uploadFile("$path_cantv"."/".$nombre1.".encrypted", "/enviado/".$nombre1.".encrypted");
//++++++++++++++

//subiendo el zip ya no es necesario
    // $sftp->uploadFile("PDVAL"."/".$nombre1.".zip", "/enviado/".$nombre1.".zip");


    $dir = "pdvalcentral/";
    $ficheroseliminados= 0;


    // $handle = opendir($dir);
    // while ($file1 = readdir($handle)) {
    //     if(is_dir($archivo)){

    //     }else
    // # if (is_file($file1)) {
    //     {
    //   if ( unlink($dir.$file1) ){
    //    $ficheroseliminados++;
    //   }
    //  }
    // }
    // echo "Fitxers eliminats : <strong>". $ficheroseliminados ."</strong>";

    // $sftp->leer();

   unset($sftp);

}
 //fin de try
catch (Exception $e)
{
    echo $e->getMessage() . "\n";
}
}

//Genero el Archivo para la descarga Manual
$handle = opendir($path_beneficiarios."/");

while (false != ($entry = readdir($handle))){ 
    
    $string="zip";
 
    if(strnatcasecmp ($string,substr($entry,-3))==0){

      $statinfo = stat($path_beneficiarios.'/'.$entry);
        $fecha_arc = strtotime(date('Y-m-d H:i:s',$statinfo['atime']));
          if($fecha_arc > $fechainicial){
            $fechainicial=$fecha_arc;
            $nombre_arc=$entry; 
        }
    }        
}

$zip = new ZipArchive;
    //$nombre_arc;
    if ($zip->open($path_beneficiarios.'/'.$nombre_arc) === TRUE) {
        $nombre_final=  substr($nombre_arc, 0,-4);

        $zip->extractTo($path_downloads."/".$nombre_final);
        $zip->close();
        chmod($path_downloads."/".$nombre_final,  0777);
        //echo 'descomprimio';
    }else{

        //echo 'no descomprimio';
}

$handle1=  opendir($path_downloads."/".$nombre_final);

while ($archivo = readdir($handle1)){
if (is_dir($archivo))//verificamos si es o no un directorio
{
 //echo "3";
}else{

    $string="TCR";
    chmod($path_downloads."/".$nombre_final."/".$archivo,  0777);
    //echo $verificar=substr($archivo,0,2);
    if(strnatcasecmp ($string,substr($archivo,0,3))==0){
            $files=fopen($path_downloads."/".$nombre_final.'/'.$archivo,"r");
            $i=0;

                            while (($datos = fgetcsv($files, ",")) !== FALSE) {

                         $cedulas[$i]=str_replace("-","",$datos[0]);
                         $productos[$i]=$datos[1];
                         $i++;

                            }

                    fclose($files);
            //unlink($path_descomprimido."/".$nombre_final."/".$archivo);
            }//fin del if
}
}

for($k=0;$k<count($productos)-1;$k++){ 
     $consul_cat=$comunes->ObtenerFilasBySqlSelect("SELECT a.code FROM selectrapyme_central.categoria_conversor AS b, selectrapyme_central.products AS a WHERE 
         b.cod_categoria =".$productos[$k]." 
    AND b.id_categoria_pos = a.category
    LIMIT 1");    

        if($consul_cat[0][code]!=""){
       
       //echo "5";

        $contenido_inventario.="NULL,".$consul_cat[0][code].',1000,'.$cedulas[$k].','.date("Y-m-d H:i:s").("\n");

        }
}

$conexion=mysql_connect("192.168.15.2","root","admin.2040"); 
mysql_select_db("selectrapyme_central",$conexion); 
$fechaFCon=strftime( "%Y-%m-%d %H:%M:%S", time() );
$fechaICon=strftime( "%Y-%m-%d 00:00:00",strtotime("-1 day"));
$sql="SELECT CODE,UNITS,TAXID,DATE(datenew_ticketlines) AS DATE FROM selectrapyme_central.$ventas_mes_anno WHERE datenew_ticketlines BETWEEN '".$fechaICon."' AND '".$fechaFCon."'"; 

//echo "SELECT CODE,UNITS,TAXID,DATE(DATENEW) AS DATE FROM selectrapyme_central.ventas WHERE DATENEW BETWEEN '".$fechaICon."' AND '".$fechaFCon."'";exit;
echo $sql;
$resultado=mysql_query($sql);
while ($fila = mysql_fetch_array($resultado)) 
{
$contenido_inventario.= "NULL".",".$fila[0].",".$fila[1].",".$fila[2].",".$fila[3].","."\n";
}
mysql_close($conexion);

//Borro Archivo para generar uno mas actualizado
function borrarCarpeta ($nombreCarpeta){
     chmod($nombreCarpeta,  0777);
    $handle1=  opendir($nombreCarpeta);
    while ($archivo = readdir($handle1)){
        chmod($nombreCarpeta."/".$archivo,  0777);
        unlink($nombreCarpeta."/".$archivo);
    }    
}
$path_descarga_manual="/var/www/pyme/selectraerp/uploads/descarga_ventas_manuales";


//Genero el Archivo para descarga manual
$archivo_consolidado="consolidado_ventas".$nomb;
$pf_ext=fopen($path_descarga.'/'.$archivo_consolidado,"w+");
//GENERO EL ARCHIVO QUE LLENARA LA TABLA EXTERNAL SALE
fwrite($pf_ext, utf8_decode($contenido_inventario));
fclose($pf_ext);
//echo "sss".$path_consolidados.'/'.$archivo_consolidado; exit();
chmod($path_descarga.'/'.$archivo_consolidado,  0777);
//comprimiendo el archivo
$zip = new ZipArchive;
//nombre sin .csv
$nombre_final=  substr($archivo_consolidado, 0,-4);
//nombre del zip
$nombrezip=$nombre_final.".zip";
borrarCarpeta($path_descarga_manual);
if($zip->open($path_descarga_manual.'/'.$nombrezip,ZIPARCHIVE::CREATE)===true){
        
        $zip->addFile($path_descarga.'/'.$archivo_consolidado,$archivo_consolidado);
//$nombre_final=  substr($archivo_consolidado, 0,-4);
//  $zip->addFile($archivo_consolidado);
    $zip->close();
    chmod($path_descarga_manual.'/'.$nombrezip,  0777);
    //echo 'comprimio';
  } else {
    //echo 'no comprimio';
}

//FIN DE BLOQUE PARA COMPRIMIR ARCHIVO DE VENTAS CONSOLIDADO PARA PUNTOS SIN CONEXION


$comunes->cerrar();
?>

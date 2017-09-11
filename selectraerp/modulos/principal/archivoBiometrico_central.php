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


//*********************************JUNIOR AYALA**************************************************
$conex1=$conn_pyme = new ConexionComun();
$pymeC="selectrapyme_central";

$directorio=dir("$path_ventas");

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
            $sql ="INSERT INTO $pymeC.ventas(id_tickets, TICKETTYPE, TICKETID, PERSON, CUSTOMER, STATUS, TICKET, LINE, PRODUCT, 
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
        //BORRA EL ARCHIVO RECORrIDO AL MOMENTO
          // if($rs_ins==1) {unlink($path_ventas."/".$archivo);}
           
    } //Fin if (substr($archi,0,1)!=".")
    
}//Fin While Externo

//*********************************FIN JUNIOR AYALA**************************************************************




//OBTENER ULTIMA FECHA
$ultimaFecha = $comunes->ObtenerFilasBySqlSelect("SELECT * from $pyme.control_fecha_archivo ORDER BY id DESC LIMIT 1");
$fila=$comunes->getFilas();
if($fila==0){
	$fechaF=strftime( "%Y-%m-%d %H:%M:%S", time() );
        $fechaI=strftime( "%Y-%m-%d %H:%M:%S",strtotime("-30 minute"));

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
$array_cantv=$comunes->ObtenerFilasBySqlSelect("SELECT SUBSTRING(taxid,1,1) as nacionalidad,SUBSTRING(taxid,2) as cedula, datenew_ticketlines, b.cod_categoria, units, 52, SUBSTRING( c.codigo_estado, 3 ) as estado
FROM $pymeC.ventas, $pymeC.categoria_conversor AS b, $pymeC.estados AS c, $pymeC.puntos_venta AS d
WHERE datenew_ticketlines
BETWEEN '".$fechaI."'
AND '".$fechaF."'
AND category = b.id_categoria_pos
AND c.codigo_estado = d.codigo_estado_punto
AND codigo_siga = d.codigo_siga_punto");


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
             $contenido_bloqueados.='" ","'.$key1["nacionalidad"].'-'.$key1["cedula"].'","'.$key1["datenew_ticketlines"].'","'.$key1["cod_categoria"].'",'.$key1["units"].',"'.$key1["52"].'","'.$sucursal.'","'.$key1["estado"].'"'."\r\n";
        }
    
      
    // echo $contenido_bloqueados;exit;
        //CREA EL ARCHIVO
  $pf=fopen($path_cantv."/".$nomb,"w+");
        fwrite($pf, $contenido_bloqueados);        
        fclose($pf);
        chmod($path_cantv."/".$nomb,  0777);  
    
}


//PROBAR EN LINUX COMENTADO POR LOS MOMENTOS
if($resul>0){


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
        //echo 'recibido/'.$entry; exit();
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
    // $sftp->uploadFile("PDVAL"."/".$nombre1.".txt", "/enviado/".$nombre1.".txt");
    // $sftp->uploadFile("PDVAL"."/".$nombre1.".encrypted", "/enviado/".$nombre1.".encrypted");
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
    
} //fin de try
catch (Exception $e)
{
    echo $e->getMessage() . "\n";
}

//OBTENEMOS DE LA BD Y GENERAMOS EL ARCHIVO EN VENTAS CONSOLIDADOS

$comunes->Execute2("SELECT * FROM selectrapyme_central.ventas WHERE datenew_ticketlines BETWEEN '".$fechaI."' AND '".$fechaF."'
into outfile '".$path_consolidados."/consolidado_ventas".$nomb."' 
FIELDS TERMINATED BY ',' 
OPTIONALLY ENCLOSED BY ''
LINES TERMINATED BY '\n'");

//FIN DEL OBTENER

}//FIN DEL IF(RESUL>0)

//RECIBIENDO EL ARCHIVO DE CANTV
$sftp = new SFTPConnection("190.9.132.66", 22);
$sftp->login("alsegura", "4lsegur4*");
$sftp->leer();
unset($sftp);

 //+++++++++++++++++   

?>

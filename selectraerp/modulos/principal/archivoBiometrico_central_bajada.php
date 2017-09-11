<?php
ini_set("memory_limit","1024M");


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
$anno_des=date('y');
$ventas_mes_anno="ventas_".$mes."_".$ano; 

//PATHS
// $path_cantv="c:/wamp/www/siscolp-pdval/pyme/selectraerp/uploads/cantv";
$path_cantv="/var/www/pyme/selectraerp/uploads/cantv";
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
$path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
$path_bajada_previa="/var/www/pyme/selectraerp/uploads/bajada_previa";
$path_consolidados="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
//$path_consolidados="c:/wamp/www/siscolp-pdval/pyme/selectraerp/uploads/consolidados_ventas";

$fechaF=strftime( "%Y-%m-%d %H:%M:%S", time() );
$fechaI=strftime( "%Y-%m-%d %H:%M:%S",strtotime("-30 minute"));

//FECHA PARA OBTENER LAS VENTAS DE LA SEMANA
$fechaFCon=strftime( "%Y-%m-%d %H:%M:%S", time() );
//$fechaICon=strftime( "%Y-%m-%d 00:00:00",strtotime("-1 day"));
$fechaICon=strftime( "%Y-%m-%d 00:00:00", time() );


//echo "SELECT CODE,UNITS,TAXID,DATE(DATENEW) AS DATE FROM selectrapyme_central.ventas WHERE DATENEW BETWEEN '".$fechaICon."' AND '".$fechaFCon."'"; exit();


//
//OBTENEMOS DE LA BD Y GENERAMOS EL ARCHIVO EN VENTAS CONSOLIDADOS
//echo "SELECT CODE,UNITS,TAXID,DATE(DATENEW) AS DATE FROM selectrapyme_central.ventas WHERE DATENEW BETWEEN '".$fechaICon."' AND '".$fechaFCon."'"; exit();
//------------------------------------------------------------

//OBTENEMOS DE LA BD Y GENERAMOS EL ARCHIVO EN VENTAS CONSOLIDADOS
$capital="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_capital/";//capital
$central="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_central/";//central
$guyana="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_guyana/";//guyana
$insular="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_insular/";//insular
$andes="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_los_andes/";//losandes
$llanos="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_los_llanos/";//los_llanos
$occidental="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_occidental/";//occidental
$oriental="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_oriental/";//oriental
$zuliana="/var/www/pyme/selectraerp/uploads/consolidados_ventas_region_zuliana/";//zuliana
$central_consolidados="/var/www/pyme/selectraerp/uploads/consolidados_ventas";//lacentral es temporal hasta que se utilicen las otras

$i=1;
for($i=1;$i<=10;$i++){
if($i==1){
    crear_archivo_pdval($i,$capital,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==2){
    crear_archivo_pdval($i,$central,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==3){
    crear_archivo_pdval($i,$guyana,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==4){
    crear_archivo_pdval($i,$insular,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==5){
    crear_archivo_pdval($i,$andes,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==6){
    crear_archivo_pdval($i,$llanos,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==7){
    crear_archivo_pdval($i,$occidental,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==8){
    crear_archivo_pdval($i,$oriental,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==9){
    crear_archivo_pdval($i,$zuliana,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
if($i==1){
    crear_archivo_pdval($i,$central_consolidados,$fechaICon,$fechaFCon,$ventas_mes_anno);
}
}



function crear_archivo_pdval($region, $carpeta,$fechaICon,$fechaFCon,$ventas_mes_anno){
$conexion=mysql_connect("192.168.15.2","root","admin.2040");
mysql_select_db("selectrapyme_central",$conexion); 
$sql="SELECT CODE,UNITS,TAXID,DATE(datenew_ticketlines) AS DATE
 FROM selectrapyme_central.$ventas_mes_anno AS a, selectrapyme_central.estados AS b, selectrapyme_central.puntos_venta AS c
WHERE a.DATENEW_ticketlines
BETWEEN '".$fechaICon."' AND '".$fechaFCon."' 
AND a.codigo_siga = c.codigo_siga_punto
AND c.codigo_estado_punto = b.codigo_estado
AND b.id_region =".$region."
"; 

$resultado = mysql_query($sql,$conexion);

$fila = mysql_fetch_array($resultado);

while ($fila = mysql_fetch_array($resultado)) 
{
$consolidado_external_sale.= "NULL,".$fila["CODE"].",".$fila["UNITS"].",".$fila["TAXID"].",".$fila["DATE"].",".("\n");
}
//---------------------------------------------------------------
//$consolidado_external_sale="";

/*$consolidadoVenta=$comunes->ObtenerFilasBySqlSelect("SELECT CODE,UNITS,TAXID,DATE(DATENEW) AS DATE FROM selectrapyme_central.ventas WHERE DATENEW BETWEEN '".$fechaICon."' AND '".$fechaFCon."'" );

if(count($fila)){
    foreach ($fila as $value) {
        $consolidado_external_sale.= "NULL,".$value["CODE"].",".$value["UNITS"].",".$value["TAXID"].",".$value["DATE"].",".("\n");
    }
}
*/
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
//$consolidado_external_sale="";
$archivo_consolidado="consolidado_ventas".$nomb;
//$path_consolidados era viejo
$pf_ext=fopen($carpeta.'/'.$archivo_consolidado,"w+");
//GENERO EL ARCHIVO QUE LLENARA LA TABLA EXTERNAL SALE
fwrite($pf_ext, utf8_decode($consolidado_external_sale));
fclose($pf_ext);
//echo "sss".$path_consolidados.'/'.$archivo_consolidado; exit();
chmod($carpeta.'/'.$archivo_consolidado,  0777);
//comprimiendo el archivo
$zip = new ZipArchive;
//nombre sin .csv
$nombre_final=  substr($archivo_consolidado, 0,-4);
//nombre del zip
$nombrezip=$nombre_final.".zip";
if($zip->open($carpeta.'/'.$nombrezip,ZIPARCHIVE::CREATE)===true){
        $zip->addFile($carpeta.'/'.$archivo_consolidado,$archivo_consolidado);
//$nombre_final=  substr($archivo_consolidado, 0,-4);
//    $zip->addFile($archivo_consolidado);
    $zip->close();
    chmod($carpeta.'/'.$nombrezip,  0777);

    //echo 'descomprimio';
	} else {
    echo 'no descomprimio';
}

mysql_close($conexion);

}//fin la funcion


//*******************************************************************
//comentado 
/*$comunes->Execute2("SELECT * FROM selectrapyme_central.ventas WHERE datenew_ticketlines BETWEEN '".$fechaI."' AND '".$fechaF."'
into outfile '".$path_consolidados."/consolidado_ventas".$nomb."' FIELDS TERMINATED BY ',' 
OPTIONALLY ENCLOSED BY ''
LINES TERMINATED BY '\n'");
*/




//FIN DEL OBTENER
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
    
    
    public function leer($filename){
        //$path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
        $path_bajada_previa="/var/www/pyme/selectraerp/uploads/bajada_previa";
        $sftp = $this->sftp; 
        $read=0;      
        $handle = opendir("ssh2.sftp://$sftp/recibido/");
        while (false != ($entry = readdir($handle))){
            //echo "$entry".("\n");
            $string="tcr_20";
            if(strnatcasecmp ($string,substr($entry,0,6))==0){
        //echo 'recibido/'.$entry; exit();
        //ssh2_scp_recv($auth, 'recibido/'.$entry, '/home/tecnologia/csv/archivollego.zip');
        // Local stream
              
                if (!$localStream = @fopen($path_bajada_previa."/".$filename, 'w')) {

                    throw new Exception("Unable to open local file for writing: ".$path_bajada_previa."/".$filename);
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
        
                chmod($path_bajada_previa."/".$filename,  0777);
                fclose($localStream);
                fclose($remotefile);  
        
          
          
            }    
        }     
    }
}//FIN DE LA CLASE



//RECIBIENDO EL ARCHIVO DE CANTV
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");
$filename="consolidado_bloqueados".$dia.$mes.$ano."_".$hora.$min.$seg.".zip";
$sftp = new SFTPConnection("190.9.132.66", 22);
$sftp->login("alsegura", "4lsegur4*");
$sftp->leer($filename);
rename($path_bajada_previa."/".$filename, $path_beneficiarios."/".$filename);
chmod($path_beneficiarios."/".$filename,  0777);
unset($sftp);
$comunes->cerrar();

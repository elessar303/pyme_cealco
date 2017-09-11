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

$fechaF=strftime( "%Y-%m-%d %H:%M:%S", time() );
$fechaI=strftime( "%Y-%m-%d %H:%M:%S",strtotime("-30 minute"));

//OBTENEMOS DE LA BD Y GENERAMOS EL ARCHIVO EN VENTAS CONSOLIDADOS

$comunes->Execute2("SELECT * FROM selectrapyme_central.ventas WHERE datenew_ticketlines BETWEEN '".$fechaI."' AND '".$fechaF."'
into outfile '".$path_consolidados."/consolidado_ventas".$nomb."' FIELDS TERMINATED BY ',' 
OPTIONALLY ENCLOSED BY ''
LINES TERMINATED BY '\n'");


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



//RECIBIENDO EL ARCHIVO DE CANTV
$sftp = new SFTPConnection("190.9.132.66", 22);
$sftp->login("alsegura", "4lsegur4*");
$sftp->leer();
unset($sftp);

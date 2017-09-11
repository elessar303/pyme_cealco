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
$comunes = new Producto();
$pyme=DB_PYME;
$pos=POS;
$tipo="E";
$cadena="52";
$sucursal1= $comunes->ObtenerFilasBySqlSelect("SELECT codigo_siga from parametros_generales");
$sucursal = $sucursal1[0]['codigo_siga'];
$estado="0001";
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");
$inventario="inventario";
$nomb="000".$sucursal.'_'.$dia.$mes.$ano.$hora.$min.".csv";
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

//Nombre de las rutas
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
$path_inventarios="/var/www/pyme/selectraerp/uploads/inventarios";
$path_beneficiarios="/var/www/pyme/selectraerp/uploads/recibidos";
$path_descomprimido="/var/www/pyme/selectraerp/uploads/recibidos/descomprimidos";
//ruta donde se guarda el archivo en el  punto obtenido del central
$path_consolidados="/var/www/pyme/selectraerp/uploads/consolidados_ventas";
//ruta donde obtengo el arvhivo consolidado de las ventas del central
$path_central_cons="/var/www/pyme/selectraerp/uploads/consolidados_ventas";

// Comienza a buscar el archivo de los clientes bloqueados
$fechainicial=strtotime('1970-01-01 00:00:00');
$connection = ssh2_connect('201.248.68.244', 59931); //colocar la ip del servidor de la region
ssh2_auth_password($connection, 'root', 'admin.2021');
$sftp=ssh2_sftp($connection);
$handle = opendir("ssh2.sftp://$sftp".$path_beneficiarios."/");   

//Abro el directorio remoto y busco el archivo mas reciente
while (false != ($entry = readdir($handle))){ 
    
    $string="zip";
 
    if(strnatcasecmp ($string,substr($entry,-3))==0){

	    $statinfo = ssh2_sftp_stat($sftp, $path_beneficiarios.'/'.$entry);
        $fecha_arc = strtotime(date('Y-m-d H:i:s',$statinfo['atime']));
        	if($fecha_arc > $fechainicial){
           	$fechainicial=$fecha_arc;
           	$nombre_arc=$entry; 
        }

    }      
   
}// fin del while

//Copio el archivo mas reciente y lo descomprimo
if(ssh2_scp_recv($connection, $path_beneficiarios.'/'.$nombre_arc, $path_beneficiarios.'/'.$nombre_arc)){
    chmod($path_beneficiarios.'/'.$nombre_arc,  0777);

    echo $comparar=compararTamano($path_beneficiarios.'/'.$nombre_arc, $path_beneficiarios.'/'.$nombre_arc,$sftp);
    if($comparar==1){
        $zip = new ZipArchive;
    //$nombre_arc;
        if ($zip->open($path_beneficiarios.'/'.$nombre_arc) === TRUE) {
            $nombre_final=  substr($nombre_arc, 0,-4);

            $zip->extractTo($path_descomprimido."/".$nombre_final);
            $zip->close();
            chmod($path_descomprimido."/".$nombre_final,  0777);
        //echo 'descomprimio';
        }else{

        //echo 'no descomprimio';
        }


    //LEYENDO EL ARCHIVO DE CLIENTES BLOQUEADOS 
        $handle1=  opendir($path_descomprimido."/".$nombre_final);

         while ($archivo = readdir($handle1)){
         if (is_dir($archivo))//verificamos si es o no un directorio
        {

        }else{

            $string="TCR";
            chmod($path_descomprimido."/".$nombre_final."/".$archivo,  0777);
            //echo $verificar=substr($archivo,0,2);
            if(strnatcasecmp ($string,substr($archivo,0,3))==0){
                    $files=fopen($path_descomprimido."/".$nombre_final.'/'.$archivo,"r");
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
    }// fin del while

    //Borra la tabla 
     $comunes->Execute2("delete from  $pos.external_sales where UNITS=1000");
    // $comunes->Execute2("delete from  $pos.external_sales where units=1000");

     $archivo_def="definitivo.csv";
     $pf_inv=fopen($path_descomprimido."/".$nombre_final."/".$archivo_def,"w+");
     $contenido_inventario="";
     for($i=0;$i<count($productos)-1;$i++){ 
     $consul_cat=$comunes->ObtenerFilasBySqlSelect("SELECT a.code FROM $pos.categoria_conversor AS b, $pos.products AS a WHERE 
         b.cod_categoria =".$productos[$i]." 
    AND b.id_categoria_pos = a.category
    LIMIT 1");

        if($consul_cat[0][code]!=""){


        $contenido_inventario.="NULL,".$consul_cat[0][code].',1000,'.$cedulas[$i].','.date("Y-m-d H:i:s").("\n");

        }
     }
     //CREA EL ARCHIVO PARA SUBIRLO A LA BD EXTERNAL SALES DEL POS
     fwrite($pf_inv, utf8_decode($contenido_inventario));
     fclose($pf_inv);
     chmod($path_descomprimido."/".$nombre_final.'/'.$archivo_def,  0777);
     //SUBE EL ARCHIVO A LA BD EXTERNAL SALES
    $sql="LOAD DATA LOCAL INFILE '".$path_descomprimido."/".$nombre_final."/".$archivo_def."' INTO TABLE  ".$pos.".external_sales FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n';";
            system("mysql -u root -h localhost --password=admin.2040 --local_infile=1 -e \"$sql\" $pos");
    }//fin if de comparar
} //fin del if ssh_send
        //VENTAS CONSOLIDADAS
// // Comienza a buscar el archivo de las ventas consolidadas
$fechainicial=strtotime('1970-01-01 00:00:00');
//$sftp=ssh2_sftp($connection);
$handle = opendir("ssh2.sftp://$sftp".$path_central_cons."/");   
while (false != ($entry = readdir($handle))){ 
//    
    $string="zip";
// //el substr es para saber si el archivo obtenido es un zip
    if(strnatcasecmp ($string,substr($entry,-3))==0){

      $statinfo = ssh2_sftp_stat($sftp, $path_central_cons."/".$entry);

      $fecha_arc = strtotime(date('Y-m-d H:i:s',$statinfo['atime']));
      if($fecha_arc > $fechainicial){
          $fechainicial=$fecha_arc;
          $nombre_arc=$entry; 
      }
    }      
//   
}// fin del while

//comando para obtener un archivo de servidor remoto
//echo $path_central_cons.'/'.$nombre_arc."<br>".$path_consolidados.'/'.$nombre_arc; exit();
if(ssh2_scp_recv($connection, $path_central_cons.'/'.$nombre_arc, $path_consolidados.'/'.$nombre_arc)){
chmod($path_consolidados.'/'.$nombre_arc,  0777);

$nombre_final=$nombre_arc;
$comparar1=compararTamano($path_central_cons.'/'.$nombre_arc, $path_consolidados.'/'.$nombre_arc,$sftp);
if($comparar1==1){
    $zip = new ZipArchive;
    //$nombre_arc;

    if ($zip->open($path_consolidados.'/'.$nombre_arc) === TRUE) {
        $nombre_final=  substr($nombre_arc, 0,-4);
        $zip->extractTo($path_consolidados.'/'.$nombre_final);
        $zip->close();
        chmod($path_consolidados.'/'.$nombre_final,  0777);
        //echo 'descomprimio';
            } else {
        //echo 'no descomprimio';
    }




    $contenido_inventario="";
    //comienza el bloque



    $handle1=  opendir($path_consolidados.'/'.$nombre_final);

    while ($archivo = readdir($handle1)){
        if (is_dir($archivo))//verificamos si es o no un directorio
    {


    }else{

      $nombre_final_csv=$archivo;


    }
    }
    chmod($path_consolidados.'/'.$nombre_final.'/'.$nombre_final_csv,  0777);
    //echo $path_consolidados.'/'.$nombre_final.'/'.$nombre_final_csv;exit();
    //SUBO EL ARCHIVO A LA BD
    $comunes->Execute2("delete from  $pos.external_sales where UNITS<>1000");
    $sql="LOAD DATA LOCAL INFILE '".$path_consolidados.'/'.$nombre_final.'/'.$nombre_final_csv."' INTO TABLE  ".$pos.".external_sales FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n'";
    system("mysql -u root -h localhost --password=admin.2040 --local_infile=1 -e \"$sql\" $pos");
}//fin de comparar1
}// fin de ssh_ send
//borro los arvhivos
//unlink($path_consolidados.'/'.$nombre_final);
//unlink($path_consolidados.'/'.$nombre_arc);


//Cierro las Conexiones
unset($connection);
$comunes->cerrar();
session_destroy();
?>

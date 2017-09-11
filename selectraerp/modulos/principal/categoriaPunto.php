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

$bdCentral=DB_REPORTE_CENTRAL;
$bdpyme=DB_SELECTRA_FAC;
$bdPos=POS;

$path_categoria="/var/www/pyme/selectraerp/uploads/categorias";
$categoria = new Producto();
$nombreArchivo="categoriaCentralizado.csv";   
//establece la conexion al servidor central
$connection = ssh2_connect('201.248.68.244', 59931); 
ssh2_auth_password($connection, 'root', 'admin.2021');

if(ssh2_scp_recv($connection, $path_categoria.'/'.$nombreArchivo, $path_categoria.'/'.$nombreArchivo)){
	chmod($path_categoria.'/'.$nombreArchivo,  0777);
	$categoria->Execute2("delete from  $bdpyme.grupo");
    $sql="LOAD DATA LOCAL INFILE '".$path_categoria.'/'.$nombreArchivo."' INTO TABLE  ".$bdpyme.".grupo FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n'";
    system("mysql -u root -h localhost --password=admin.2040 --local_infile=1 -e \"$sql\" $bdpyme");
 
    $sql="SELECT * FROM $bdpyme.grupo";
    $obtCateagoria=$categoria->ObtenerFilasBySqlSelect($sql);
    if(count($obtCateagoria)>0){
    	$categoria->Execute2("delete from  $bdPos.categories");
    	foreach ($obtCateagoria as $lista ) {
    		$instruccion = "
					INSERT INTO $bdPos.categories (
						ID,
						NAME,
						PARENTID,
						IMAGE,
						QUANTITYMAX,
						TIMEFORTRY
					)
					VALUES (
 						'".$lista["grupopos"]."','".$lista["descripcion"]."',NULL,NULL,'".$lista["cantidad_rest"]."','".$lista["dias_rest"]."'
					);";
			$categoria->Execute2($instruccion);
    	}

    }
    
	
}




unset($connection);
$categoria->cerrar();
session_destroy();




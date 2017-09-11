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
$path_categoria="/var/www/pyme/selectraerp/uploads/categorias";
$categoria = new Producto();
$sql="SELECT * FROM $bdCentral.grupo2";
$obtCateagoria=$categoria->ObtenerFilasBySqlSelect($sql);


$filas=$categoria->getFilas();
if($filas==0){
	$contenido="";
}else{ 
	
	foreach ($obtCateagoria as $value) {
        $contenido_categoria.=$value['cod_grupo'].",". str_replace(',',' ',utf8_encode($value['descripcion'])).",".$value['id_rubro'].",".$value['restringido'].",".$value['cantidad_rest'].",".$value['dias_rest'].",".$value['grupopos'].",".$value['categoria_siga'].("\n");
        	           
    }    
    $nombreArchivo="categoriaCentralizado.csv";   
    $pf=fopen($path_categoria."/".$nombreArchivo,"w+");
    fwrite($pf, utf8_decode($contenido_categoria));
        
    fclose($pf);
    chmod($path_categoria."/".$nombreArchivo,  0777);
}

$categoria->cerrar();



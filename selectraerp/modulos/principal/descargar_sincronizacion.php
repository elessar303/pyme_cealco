<?php
ob_start();
session_start();
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
require_once("../../config.ini.php");
require_once('../../../generalp.config.inc.php');
require_once("../../../general.config.inc.php");
require_once('../../../includes/clases/BDControlador.php');

$pyme=DB_SELECTRA_FAC;
$pos=POS;
$almacen = new Almacen();
$login = new Login();
$cod_usuario=$login->getIdUsuario();
$hoy=date('Y-m-d');
$sql="SELECT codigo_siga, servidor FROM $pyme.parametros_generales";
$array_sucursal=$almacen->ObtenerFilasBySqlSelect($sql);
foreach ($array_sucursal as $key => $value) {
$sucursal=$value['codigo_siga']; 
$servidor=$value['servidor']; 
}

//Ruta maestra de descarga
$ruta_master=$_SESSION['ROOT_PROYECTO']."/selectraerp/uploads";

$path_inventario=$ruta_master."/inventario";
$path_kardex=$ruta_master."/kardex";
$path_ventas=$ruta_master."/ventas";
$path_ventas_pyme=$ruta_master."/ventas_pyme";
$path_descarga=$ruta_master."/descarga_ventas";
$path_libros=$ruta_master."/libro_venta";
$path_ingresos=$ruta_master."/control_ingresos";
$path_comprobantes=$ruta_master."/comprobantes";

$directorio=dir("$path_ingresos");
$directorio2=dir("$path_libros");
$directorio3=dir("$path_inventario");
$directorio4=dir("$path_kardex");
$directorio5=dir("$path_ventas");
$directorio6=dir("$path_ventas_pyme");
$directorio7=dir("$path_comprobantes");

$zip = new ZipArchive();
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$nombre_arc="sincronizacion_data".'_'.$dia.$mes.$ano.$hora.".zip";
$nombre_archivo="000".$sucursal.'_'.$dia.$mes.$ano.$hora."_data";

if ($zip->open($path_descarga.'/'.$nombre_arc,ZIPARCHIVE::CREATE) === TRUE) {

    while ($archivo = $directorio->read()) {
        
    if (substr($archivo,0,1)!=".") {

        if (file_exists($path_ingresos.'/'.$archivo))  {
        $zip->addFile($path_ingresos.'/'.$archivo,$archivo);
        }
    }
    }

    while ($archivo = $directorio2->read()) {
    if (substr($archivo,0,1)!=".") {

        if (file_exists($path_libros.'/'.$archivo))  {
        $zip->addFile($path_libros.'/'.$archivo,$archivo);
        }
    }
    }

    while ($archivo = $directorio3->read()) {
    if (substr($archivo,0,1)!=".") {

        if (file_exists($path_inventario.'/'.$archivo))  {
        $zip->addFile($path_inventario.'/'.$archivo,$archivo);
        }
    }
    }

    while ($archivo = $directorio4->read()) {
    if (substr($archivo,0,1)!=".") {

        if (file_exists($path_kardex.'/'.$archivo))  {
        $zip->addFile($path_kardex.'/'.$archivo,$archivo);
        }
    }
    }
    while ($archivo = $directorio5->read()) {
        
    if (substr($archivo,0,1)!=".") {

        if (file_exists($path_ventas.'/'.$archivo))  {
        $zip->addFile($path_ventas.'/'.$archivo,$archivo);
        }
    }
    }

    while ($archivo = $directorio6->read()) {
        
    if (substr($archivo,0,1)!=".") {

        if (file_exists($path_ventas_pyme.'/'.$archivo))  {
        $zip->addFile($path_ventas_pyme.'/'.$archivo,$archivo);
        }
    }
    }

    while ($archivo = $directorio7->read()) {
        
    if (substr($archivo,0,1)!=".") {

        if (file_exists($path_comprobantes.'/'.$archivo))  {
        $zip->addFile($path_comprobantes.'/'.$archivo,$archivo);
        }
    }
    }

    $zip->close();
}


$directorio=dir("$path_ingresos");
$directorio2=dir("$path_libros");
$directorio3=dir("$path_inventario");
$directorio4=dir("$path_kardex");
$directorio5=dir("$path_ventas");
$directorio6=dir("$path_ventas_pyme");
$directorio7=dir("$path_comprobantes");

while ($archivo = $directorio->read()) {
    if (substr($archivo,0,1)!=".") {
        unlink($path_ingresos.'/'.$archivo);
    }
}

while ($archivo = $directorio2->read()) {
    if (substr($archivo,0,1)!=".") {
        unlink($path_libros.'/'.$archivo);
    }
}

while ($archivo = $directorio3->read()) {
    if (substr($archivo,0,1)!=".") {
        unlink($path_inventario.'/'.$archivo);
    }
}
while ($archivo = $directorio4->read()) {
    if (substr($archivo,0,1)!=".") {
        unlink($path_kardex.'/'.$archivo);
    }
}

while ($archivo = $directorio5->read()) {
    if (substr($archivo,0,1)!=".") {
        unlink($path_ventas.'/'.$archivo);
    }
}

while ($archivo = $directorio6->read()) {
    if (substr($archivo,0,1)!=".") {
        unlink($path_ventas_pyme.'/'.$archivo);
    }
}

while ($archivo = $directorio7->read()) {
    if (substr($archivo,0,1)!=".") {
        unlink($path_comprobantes.'/'.$archivo);
    }
}

ob_end_clean();    header("Content-Encoding: None", true);
ob_end_clean();
ob_end_flush();
$f = fopen('php://memory', 'w');
$archivo_descarga=$path_descarga.'/'.$nombre_arc;
header("Content-type: application/zip");
header("Content-disposition: attachment; filename=".$nombre_archivo."");
header("Content-Transfer-Encoding: binary");
header ("Content-Type: application/force-download"); 
readfile($archivo_descarga);
//header("Location: index.php");
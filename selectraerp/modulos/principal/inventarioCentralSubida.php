<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", -1);
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
error_reporting(-1);
$comunes_inve= new Producto();
$pyme=DB_SELECTRA_FAC;
$pos=POS;
$ip_central="201.248.68.244"; //ip de sede central
$puerto=59931; //puerto de sede central
$clave="admin.2021"; //clave ssh 
$estado="0001";
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");
$fecha=date("Y-m-d");

$ruta_master=$_SESSION['ROOT_PROYECTO']."/selectraerp/uploads";

$path_inventario=$ruta_master."/inventario";
$path_kardex=$ruta_master."/kardex";
$path_libros=$ruta_master."/libro_venta";
$path_ingresos=$ruta_master."/control_ingresos";
$path_ventas=$ruta_master."/ventas";
$path_ventas_pyme=$ruta_master."/ventas_pyme";
$path_comprobantes=$ruta_master."/comprobantes";

$path_inventario_central="/var/www/pyme/selectraerp/uploads/inventario_central";
$path_kardex_central="/var/www/pyme/selectraerp/uploads/kardex_central";
$path_libros_central="/var/www/pyme/selectraerp/uploads/libro_venta_central";
$path_ingresos_central="/var/www/pyme/selectraerp/uploads/control_ingresos_central";
$path_ventas_central="/var/www/pyme/selectraerp/uploads/ventas";
$path_ventas_pyme_central="/var/www/pyme/selectraerp/uploads/ventas_pyme";
$path_comprobantes_central="/var/www/pyme/selectraerp/uploads/comprobantes";
 //conexion a sede central
//Select del archivo de Inventario


$directorio=dir("$path_inventario");
while ($nombre_inventario = $directorio->read()) {
if (substr($nombre_inventario,0,1)!=".") {
$connection = ssh2_connect($ip_central, $puerto);
if($connection){
    ssh2_auth_password($connection, 'root', $clave);
    $sftp=ssh2_sftp($connection);

    if(ssh2_scp_send($connection, $path_inventario."/".$nombre_inventario, $path_inventario_central."/".$nombre_inventario, 0777)){
        echo "Inventario Exitoso<br>";
        unlink($path_inventario."/".$nombre_inventario);
        unset($connection);
        
    }else{
        echo "Inventario No Exitoso<br>";
        error_log("Error al enviar el archivo de inventario".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
        unset($connection);
        }
    }//fin de la connection
    
}
}
//Archivo de Kardex

$connection = ssh2_connect($ip_central, $puerto);
$directorio=dir("$path_kardex");
while ($nombre_kardex = $directorio->read()) {
if (substr($nombre_kardex,0,1)!=".") {
$connection = ssh2_connect($ip_central, $puerto);
if($connection){
    ssh2_auth_password($connection, 'root', $clave);
    $sftp=ssh2_sftp($connection);

    if(ssh2_scp_send($connection, $path_kardex."/".$nombre_kardex, $path_kardex_central."/".$nombre_kardex, 0777)){
        echo "Kardex Exitoso<br>";
        unlink($path_kardex."/".$nombre_kardex);
        unset($connection);
    }else{
        echo "Kardex No Exitoso<br>";
        error_log("Error al enviar el archivo de kardex".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
        unset($connection);
       }
    }//fin de la connection
    
}
}


$directorio=dir("$path_libros");
while ($nombre_libro = $directorio->read()) {
if (substr($nombre_libro,0,1)!=".") {
$connection = ssh2_connect($ip_central, $puerto);
if($connection){
    ssh2_auth_password($connection, 'root', $clave);
    $sftp=ssh2_sftp($connection);

    if(ssh2_scp_send($connection, $path_libros."/".$nombre_libro, $path_libros_central."/".$nombre_libro, 0777)){
        echo "Libro Exitoso<br>";
        unlink($path_libros."/".$nombre_libro);
        unset($connection);
    }else{
        echo "Libro No Exitoso<br>";
        error_log("Error al enviar el archivo de libro".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
        unset($connection);
       }
    }//fin de la connection

}
}

$connection = ssh2_connect($ip_central, $puerto);
$directorio=dir("$path_ingresos");
while ($nombre_ingreso = $directorio->read()) {
if (substr($nombre_ingreso,0,1)!=".") {
$connection = ssh2_connect($ip_central, $puerto);
if($connection){
    ssh2_auth_password($connection, 'root', $clave);
    $sftp=ssh2_sftp($connection);

    if(ssh2_scp_send($connection, $path_ingresos."/".$nombre_ingreso, $path_ingresos_central."/".$nombre_ingreso, 0777)){
        echo "Ingresos Exitoso<br>";
        unlink($path_ingresos."/".$nombre_ingreso);
        unset($connection);
    }else{
        echo "Ingresos No Exitoso<br>";
        error_log("Error al enviar el archivo de ingreso".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
        unset($connection);
       }
    }//fin de la connection
    
}
}

$connection = ssh2_connect($ip_central, $puerto);
$directorio=dir("$path_ventas");
while ($nombre_ventas = $directorio->read()) {
if (substr($nombre_ventas,0,1)!=".") {
$connection = ssh2_connect($ip_central, $puerto);
if($connection){
    ssh2_auth_password($connection, 'root', $clave);
    $sftp=ssh2_sftp($connection);

    if(ssh2_scp_send($connection, $path_ventas."/".$nombre_ventas, $path_ventas_central."/".$nombre_ventas, 0777)){
        echo "Ventas Exitoso<br>";
        unlink($path_ventas."/".$nombre_ventas);
        unset($connection);
    }else{
        echo "Ventas No Exitoso<br>";
        error_log("Error al enviar el archivo de ventas".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
        unset($connection);
       }
    }//fin de la connection
    
}
}

$connection = ssh2_connect($ip_central, $puerto);
$directorio=dir("$path_ventas_pyme");
while ($nombre_ventas_pyme = $directorio->read()) {
if (substr($nombre_ventas_pyme,0,1)!=".") {
$connection = ssh2_connect($ip_central, $puerto);
if($connection){
    ssh2_auth_password($connection, 'root', $clave);
    $sftp=ssh2_sftp($connection);

    if(ssh2_scp_send($connection, $path_ventas_pyme."/".$nombre_ventas_pyme, $path_ventas_pyme_central."/".$nombre_ventas_pyme, 0777)){
        echo "Ventas Pyme Exitoso<br>";
        unlink($path_ventas_pyme."/".$nombre_ventas_pyme);
        unset($connection);
    }else{
        echo "Ventas Pyme No Exitoso<br>";
        error_log("Error al enviar el archivo de ventas".("\r\n"), 3, "/var/www/pyme/selectraerp/uploads/error/error.log");
        unset($connection);
       }
    }//fin de la connection
    
}
}

$connection = ssh2_connect($ip_central, $puerto);
$directorio=dir("$path_comprobantes");
while ($nombre_comprobantes = $directorio->read()) {
if (substr($nombre_comprobantes,0,1)!=".") {
$connection = ssh2_connect($ip_central, $puerto);
if($connection){
    ssh2_auth_password($connection, 'root', $clave);
    $sftp=ssh2_sftp($connection);

    if(ssh2_scp_send($connection, $path_comprobantes."/".$nombre_comprobantes, $path_comprobantes_central."/".$nombre_comprobantes, 0777)){
        echo "Comprobantes Exitoso<br>";
        unlink($path_comprobantes."/".$nombre_comprobantes);
        unset($connection);
    }else{
        echo "Comprobantes No Exitoso<br>";
        error_log("Error al enviar el archivo de comprobantes".("\r\n"), 3, $ruta_master."/error/error.log");
        unset($connection);
       }
    }//fin de la connection
    
}
}


session_destroy();
$comunes_inve->cerrar();
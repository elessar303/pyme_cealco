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

$comunes = new Producto();


$ruta="c:\\\\wamp\\\\www\\\\pyme\\\\selectraerp\\\\uploads\\\\";
$exportar=" INTO OUTFILE 
             '".$ruta."fichero_salida.tmp'
            FIELDS TERMINATED BY ';'
            OPTIONALLY ENCLOSED BY '\\\"'
            LINES TERMINATED BY '\\r\\n' ";


    $consultar=$comunes->Execute2("SELECT
            a.*, b.caja_host,  (select codigo_siga from parametros_generales) as siga
            FROM
            libro_ventas as a, caja_impresora as b, cierre_caja_control as c
            WHERE
           a.serial_impresora=b.serial_impresora and c.estatus_cierre='0' and a.secuencia=c.secuencia ".$exportar);
   

//shell_exec('cd /var/www/pyme/selectraerp/uploads/'); //linux
//shell_exec('mv fichero_salida.tmp fichero_salida.csv'); //linux
//shell_exec('cd c:/wamp/www/pyme/selectraerp/uploads/');    
chdir('c:/wamp/www/pyme/selectraerp/uploads/');
shell_exec('move /y fichero_salida.tmp fichero_salida.csv'); //windows


$modificar=$comunes->ObtenerFilasBySqlSelect("SELECT
            c.id  as id
            FROM
            libro_ventas as a, caja_impresora as b, cierre_caja_control as c
            WHERE
           a.serial_impresora=b.serial_impresora and c.estatus_cierre='0' and a.secuencia=c.secuencia");

    foreach($modificar as $key){
        $update=$comunes->Execute2("update cierre_caja_control set estatus_cierre=1 where id=".$key['id']);
    }

$comunes->cerrar();   




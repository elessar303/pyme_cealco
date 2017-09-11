<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/siscol_pyme";
ini_set("display_errors", 1);

//Includes
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
//ruta windows
$ruta="c:/wamp/www/siscolp_pyme/selectraerp/uploads/calidad";
//ruta linux
//$ruta="/var/www/siscolp_pyme/selectraerp/uploads/libro_venta";
$comunes = new ConexionComun();

$id_visita=$comunes->ObtenerFilasBySqlSelect("select contador from correlativos where campo = 'calidad_visitas'");
// si esta vacio insertar
if(isset($id_visita[0]['contador']) || $id_visita[0]['contador']==""){
$comunes->Execute2("insert into correlativos (campo, formato, contador, descripcion) values ('calidad_visitas', '000000', '0', 'ultimo csv generado de visitas')");
$id_visita[0]['contador']=0;
}

$id_calidad_almacenes=$comunes->ObtenerFilasBySqlSelect("select contador from correlativos where campo = 'calidad_almacenes'");
//si esta vacio insertar
if(isset($id_calidad_almacenes[0]['contador']) || $id_calidad_almacenes[0]['contador']==""){
$comunes->Execute2("insert into correlativos (campo, formato, contador, descripcion) values ('calidad_almacenes', '000000', '0', 'ultimo csv generado de calidad_almacenes')");
$id_calidad_almacenes[0]['contador']=0;
}


$maxid_visitas=$comunes->ObtenerFilasBySqlSelect("select max(id) as maximo from calidad_visitas");
$maxid_calidad_almacenes=$comunes->ObtenerFilasBySqlSelect("select max(id_transaccion) as maximo from calidad_almacen");

$hoy = getdate();

//Calida almacen generar csv
$fecha=$hoy['year'].$hoy['mon'].$hoy['mday'].$hoy['hours'].$hoy['minutes'].$hoy['seconds'];
$sql="SELECT id_transaccion, usuario_creacion, fecha_creacion, cod_acta_calidad, observacion FROM calidad_almacen   
where id_transaccion between ".$id_calidad_almacenes[0]['contador']." and  ".$maxid_calidad_almacenes[0]['maximo']." 
INTO OUTFILE 
'".$ruta."/calidad_almacen_".$fecha.".csv' 
FIELDS TERMINATED BY ',' 
ENCLOSED BY \"'\" LINES TERMINATED BY '\r\n' ";
$ejecutar_csv=$comunes->Execute2($sql);
$modificar=$comunes->Execute2("update correlativos set contador='".($maxid_calidad_almacenes[0]['maximo']+1)."' where campo='calidad_almacenes'");

//calidad visita generar csv
$fecha=$hoy['year'].$hoy['mon'].$hoy['mday'].$hoy['hours'].$hoy['minutes'].$hoy['seconds'];
$sql="select a.id, a.cod_acta_visita, fecha_visita, descripcion_visita from calidad_visitas as a, tipo_visitas as b  
where a.tipo_visita=b.id and a.id between ".$id_visita[0]['contador']." and  ".$maxid_visitas[0]['maximo']." 
INTO OUTFILE 
'".$ruta."/calidad_visitas_".$fecha.".csv' 
FIELDS TERMINATED BY ',' 
ENCLOSED BY \"'\" LINES TERMINATED BY '\r\n' ";
$ejecutar_csv=$comunes->Execute2($sql);
$modificar=$comunes->Execute2("update correlativos set contador='".($maxid_visitas[0]['maximo']+1)."' where campo='calidad_visitas'");




?>
<?php
session_start();
ob_start();
ob_end_clean();    header("Content-Encoding: None", true);
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

$path_catalogo="/var/www/pyme/selectraerp/uploads/catalogo";
$pyme=DB_SELECTRA_FAC;
$almacen = new Almacen();
$login = new Login();
$cod_usuario=$login->getIdUsuario();
$hoy=date('Y-m-d');
$sql="SELECT codigo_siga FROM $pyme.parametros_generales";
$array_sucursal=$almacen->ObtenerFilasBySqlSelect($sql);
foreach ($array_sucursal as $key => $value) {
$sucursal=$value['codigo_siga']; 
}

$catalogo='Catalogo_Sede.csv';

//Select del archivo de Inventario
$sql_pyme="SELECT  `codigo_barras` , `descripcion1` ,  `unidad_empaque` ,  `seriales` ,  `cod_departamento` ,  a.cod_grupo , `cantidad_bulto` ,  `kilos_bulto` ,  `id_marca` ,  `unidadxpeso` ,  `unidad_venta` , `pesoxunidad` ,  `producto_vencimiento`, iva, marca, c.descripcion as nombre_grupo, c.id_rubro, restringido, cantidad_rest, dias_rest, c.grupopos FROM  item a, marca b, grupo c WHERE estatus = 'A' and a.id_marca=b.id and a.cod_grupo=c.cod_grupo and a.cod_departamento=2";
$array_inventario=$almacen->ObtenerFilasBySqlSelect($sql_pyme);

$contenido_inventario="";

    foreach ($array_inventario as $key => $value) {
		$contenido_inventario.=trim($value['codigo_barras']).';'.trim($value['descripcion1']).';'.$value['unidad_empaque'].';'.$value['seriales'].';'.$value['cod_departamento'].';'.$value['cod_grupo'].';'.$value['cantidad_bulto'].';'.$value['kilos_bulto'].';'.$value['id_marca'].';'.$value['unidadxpeso'].';'.$value['unidad_venta'].';'.$value['pesoxunidad'].';'.$value['producto_vencimiento'].';'.$value['iva'].';'.$value['marca'].';'.$value['nombre_grupo'].';'.$value['id_rubro'].';'.$value['restringido'].';'.$value['cantidad_rest'].';'.$value['dias_rest'].';'.$value['grupopos'].("\r\n");
	}
  
$pf_inv=fopen($path_catalogo."/".$catalogo,"w+");
fwrite($pf_inv, utf8_decode($contenido_inventario));
fclose($pf_inv);   
ob_end_clean();
$f = fopen('php://memory', 'w');
$archivo_descarga=$path_catalogo.'/'.$catalogo;
header ("Content-Type: application/force-download");
header('Content-Type:application/force-download');
header('Content-Description:File Transfer');
header('Pragma:public');
header('Expires:0');
header('Cache-Control:no-cache,must-revalidate,post-check=0,pre-check=0');
header('Cache-Control:private,false');
header("Content-Disposition:attachment;filename={$catalogo}");
header('Content-Length:'.filesize($archivo_descarga));
readfile($archivo_descarga);
//header("Location: index.php");
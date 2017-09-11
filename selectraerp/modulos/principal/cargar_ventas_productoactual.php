<?php
ini_set('memory_limit', '2048M');
ini_set("max_execution_time","1000000");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
$comunes = new Producto();
$conex1=$conn_pyme = new ConexionComun();

$BD_central="selectrapyme_central";

$fecha=strftime( "%Y-%m-%d",strtotime("-1 day"));
//$fecha=strftime("2015-12-01",strtotime("-1 day"));

$anno_des=date('y');

$mes_actual=date('m');

$mes_des=str_pad($mes, 2, "0", STR_PAD_LEFT);
	
$ventas_mes_anno="ventas_".$mes_actual."_".$anno_des; 
$ventas_anno="ventas_".$anno_des; 

$sql_truncate='DELETE FROM  '.$BD_central.'.'.$ventas_anno.'_xproducto WHERE month(fecha)='.$mes_actual.';';

$truncate_producto=$comunes->Execute2($sql_truncate);

$sql_producto='INSERT INTO '.$BD_central.'.'.$ventas_anno.'_xproducto
(`cod_bar`, `precio`, `cantidad`, `punto_venta`, `fecha`, `id_estado`, `id_tipo_punto`, `id_categoria`, `id_subcategoria`, `tipo_almacenamiento`, `id_marca`, `id_taxes`) 
SELECT 
code, price, sum(units) as units, codigo_siga, DATENEW, b.codigo_estado as id_estado, c.id_tipo as id_tipo_punto, d.cod_grupo as id_categoria, d.sub_categoria as id_subcategoria, d.tipo_almacenamiento as tipo_almacenamiento, d.id_marca as id_marca,
a.taxcat as id_taxes
FROM '.$BD_central.'.'.$ventas_anno.' a
INNER JOIN '.$BD_central.'.puntos_venta c ON a.codigo_siga=c.codigo_siga_punto 
INNER JOIN '.$BD_central.'.estados b ON c.codigo_estado_punto=b.codigo_estado
INNER JOIN selectrapyme.item d ON d.codigo_barras = a.CODE
WHERE month(DATENEW)='.$mes_actual.'
GROUP BY code, price, codigo_siga,`DATENEW`;'; 

//echo $sql_producto."<br>";
$insert_producto=$conex1->Execute2($sql_producto);

$sql_truncate_cliente='TRUNCATE TABLE  '.$BD_central.'.'.$ventas_mes_anno.'_cliente;';
//echo $sql_truncate_cliente."<br>";

$truncate_cliente=$comunes->Execute2($sql_truncate_cliente);
$sql_cliente='INSERT INTO '.$BD_central.'.'.$ventas_mes_anno.'_cliente(cliente, fecha, codigo_siga) 
SELECT count(distinct(`TAXID`)), SUBSTRING(DATENEW,1,10) as fecha , codigo_siga FROM '.$BD_central.'.'.$ventas_mes_anno.' group by codigo_siga, fecha;';

//echo $sql_cliente."<br>";
$insert_cliente=$conn_pyme->Execute2($sql_cliente);
?>
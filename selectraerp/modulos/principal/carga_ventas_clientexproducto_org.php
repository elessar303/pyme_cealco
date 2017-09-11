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
$pymeC="selectrapyme_central";

$fecha=strftime( "%Y-%m-%d",strtotime("-1 day"));
//$fecha=strftime("2015-12-01",strtotime("-1 day"));

$anno_des=date('y');

$mes_actual=date('m');
$mes=1;


while (	$mes<=$mes_actual) {
$mes_des=str_pad($mes, 2, "0", STR_PAD_LEFT);
	
$ventas_mes_anno="ventas_".$mes_des."_".$anno_des; 

$sql_truncate='TRUNCATE TABLE  '.$BD_central.'.'.$ventas_mes_anno.'_xproducto;';

//echo $sql_truncate."<br>";
$truncate_producto=$comunes->Execute2($sql_truncate);

$quitar_indices='ALTER TABLE '.$BD_central.'.'.$ventas_mes_anno.'_xproducto DISABLE KEYS';
$producto_indices=$comunes->Execute2($quitar_indices);

$sql_producto='INSERT INTO '.$BD_central.'.'.$ventas_mes_anno.'_xproducto
(`cod_bar`, `precio`, `cantidad`, `punto_venta`, `fecha`, `id_estado`, `id_tipo_punto`, `id_categoria`, `id_subcategoria`, `tipo_almacenamiento`, `id_marca`, `id_taxes`) 
SELECT 
code, price, sum(units) as units, codigo_siga, DATENEW, b.codigo_estado as id_estado, c.id_tipo as id_tipo_punto, d.cod_grupo as id_categoria, d.sub_categoria as id_subcategoria, d.tipo_almacenamiento as tipo_almacenamiento, d.id_marca as id_marca,
a.taxid_tikestline as id_taxes
FROM '.$BD_central.'.'.$ventas_mes_anno.' a
INNER JOIN '.$BD_central.'.puntos_venta c ON a.codigo_siga=c.codigo_siga_punto 
INNER JOIN '.$BD_central.'.estados b ON c.codigo_estado_punto=b.codigo_estado
LEFT JOIN selectrapyme.item d ON d.codigo_barras = a.CODE
GROUP BY code, price, codigo_siga,`DATENEW`;';

$sql_producto_new='INSERT INTO '.$BD_central.'.ventas_'.$anno_des.'_xproducto
(`cod_bar`, `precio`, `cantidad`, `punto_venta`, `fecha`, `id_estado`, `id_tipo_punto`, `id_categoria`, `id_subcategoria`, `tipo_almacenamiento`, `id_marca`, `id_taxes`) 
SELECT 
code, price, sum(units) as units, codigo_siga, DATENEW, b.codigo_estado as id_estado, c.id_tipo as id_tipo_punto, d.cod_grupo as id_categoria, d.sub_categoria as id_subcategoria, d.tipo_almacenamiento as tipo_almacenamiento, d.id_marca as id_marca,
a.taxid_tikestline as id_taxes
FROM '.$BD_central.'.'.$ventas_mes_anno.' a, '.$BD_central.'.estados b, '.$BD_central.'.puntos_venta c
WHERE a.codigo_siga=c.codigo_siga_punto 
LEFT JOIN selectrapyme.item d ON d.codigo_barras = a.CODE
AND c.codigo_estado_punto=b.codigo_estado
AND a.code=d.codigo_barras
group by code, price, codigo_siga,`DATENEW`;';

//$sql_producto."<br>";
$insert_producto=$conex1->Execute2($sql_producto);
$insert_producto=$conex1->Execute2($sql_producto_new);


$sql_truncate_cliente='TRUNCATE TABLE  '.$BD_central.'.'.$ventas_mes_anno.'_cliente;';
//echo $sql_truncate_cliente."<br>";

$truncate_cliente=$comunes->Execute2($sql_truncate_cliente);

$sql_cliente='INSERT INTO '.$BD_central.'.'.$ventas_mes_anno.'_cliente(cliente, fecha, codigo_siga) 
SELECT count(distinct(`TAXID`)), SUBSTRING(DATENEW,1,10) as fecha , codigo_siga FROM '.$BD_central.'.'.$ventas_mes_anno.' group by codigo_siga, fecha;';

//echo $sql_cliente."<br>";
$insert_cliente=$conn_pyme->Execute2($sql_cliente);

$colocar_indices='ALTER TABLE '.$BD_central.'.'.$ventas_mes_anno.'_xproducto ENABLE KEYS';
$producto_indices=$comunes->Execute2($colocar_indices);

$mes++;
}

// Modificacion para insertar las filas de los meses de ventas_mes_anio en ventas_anio
//echo "termino la primera carga";
$mes_actual=date("n");
$anio_actual=date("y");
$i=1;
$ventas_anio="ventas_".$anio_actual;
$ventas_mes_anio;
$borrar_tabla="TRUNCATE TABLE ".$pymeC.".".$ventas_anio;
$quitar_indices="ALTER TABLE ".$pymeC.".".$ventas_anio." DISABLE KEYS";
$colocar_indices="ALTER TABLE ".$pymeC.".".$ventas_anio." ENABLE KEYS";
//echo $borrar_tabla;
$desabilitar_indices=$conex1->Execute2($quitar_indices);
$desabilitar_indices=$conex1->Execute2($borrar_tabla);
$control=1;
for($i = 1; $i <= $mes_actual; $i++){
  if ($i<10){
    $ventas_mes_anio="ventas_0".$i."_".$anio_actual;
  } else {
    $ventas_mes_anio="ventas_".$i."_".$anio_actual;
  }
  $sql ="INSERT INTO $pymeC.$ventas_anio (`id_tickets`, `UNITS`, `PRICE`, `datenew_ticketlines`,`CODE`, `nombre_producto`, `PRICEBUY`, `PRICESELL`, `TAXCAT`, `SEARCHKEY`, `name_persona`, `DATENEW`, `MONEY`, `HOST`, `HOSTSEQUENCE`, `DATESTART`, `DATEEND`, `categoria_conversor`, `id_categoria_pos`, `cod_categoria`, `codigo_siga`)
                   SELECT  `id_tickets`, `UNITS`, `PRICE`, `datenew_ticketlines`,`CODE`, `nombre_producto`, `PRICEBUY`, `PRICESELL`, `TAXCAT`, `SEARCHKEY`, `name_persona`, `DATENEW`, `MONEY`, `HOST`, `HOSTSEQUENCE`, `DATESTART`, `DATEEND`, `categoria_conversor`, `id_categoria_pos`, `cod_categoria`, `codigo_siga` 
  FROM  $pymeC.$ventas_mes_anio" ; 
  //echo $sql."<br><br>";
  //echo " ,".$ventas_mes_anio;
  $insert_ventas=$conex1->Execute2($sql);
  $control=$i;
}
$habilitar_indices=$conex1->Execute2($colocar_indices);
?>
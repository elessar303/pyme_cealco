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
$mes=6;


///Recorro todos los meses hasta el actual
/*
while ( $mes<=$mes_actual) {
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


//$sql_producto."<br>";
$insert_producto=$conex1->Execute2($sql_producto);


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
// Este procedimiento se puede eliminar cuando se comince a cargar la tabla ventas_año desde el archivo plano
// que corre en archivoBiometrico_central_subida_nuevo.php
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
$borrar_tabla_Sql=$conex1->Execute2($borrar_tabla);
$control=1;
for($i = 1; $i <= $mes_actual; $i++){
  if ($i<10){
    $ventas_mes_anio="ventas_0".$i."_".$anio_actual;
  } else {
    $ventas_mes_anio="ventas_".$i."_".$anio_actual;
  }
  $sql ="INSERT INTO $pymeC.$ventas_anio (`id_tickets`, `UNITS`, `PRICE`, `datenew_ticketlines`,`CODE`, `nombre_producto`, `PRICEBUY`, `PRICESELL`, `TAXCAT`, `SEARCHKEY`, `name_persona`, `DATENEW`, `MONEY`, `HOST`, `HOSTSEQUENCE`, `DATESTART`, `DATEEND`, `categoria_conversor`, `id_categoria_pos`, `cod_categoria`, `codigo_siga`)
                   SELECT  `id_tickets`, `UNITS`, `PRICE`, `datenew_ticketlines`,`CODE`, `nombre_producto`, `PRICEBUY`, `PRICESELL`, `taxid_tikestline`, `SEARCHKEY`, `name_persona`, `DATENEW`, `MONEY`, `HOST`, `HOSTSEQUENCE`, `DATESTART`, `DATEEND`, `categoria_conversor`, `id_categoria_pos`, `cod_categoria`, `codigo_siga` 
  FROM  $pymeC.$ventas_mes_anio" ; 
  //echo $sql."<br><br>";
  //echo " ,".$ventas_mes_anio;
  //    $insert_ventas=$conex1->Execute2($sql);
  $control=$i;
}
$habilitar_indices=$conex1->Execute2($colocar_indices);

*/
// Modificación para llenar Ventas_17_xproducto con la info de Ventas_17

$ventas_anno= "ventas_".$anno_des;

$comunes->cerrar();
$conex1->cerrar();
$conn_pyme->cerrar();
$comunes = new Producto();
$conex1=$conn_pyme = new ConexionComun();


$sql_truncate='TRUNCATE TABLE  '.$BD_central.'.ventas_'.$anno_des.'_xproducto;';
$truncate_producto=$comunes->Execute2($sql_truncate);
//echo $sql_truncate; echo "<br>";
$quitar_indices='ALTER TABLE '.$BD_central.'.ventas_'.$anno_des.'_xproducto DISABLE KEYS';
$producto_indices=$comunes->Execute2($quitar_indices);
//echo $quitar_indices; echo "<br>";
$mes=7;
$mes_actual=date('m');
while ( $mes<=$mes_actual){ 
  //echo "entra en el where <br>";
  // recorro todos los dias del mes y lo introduzco en la tabla Ventas_17_xproducto dia a dia
  $comunes->cerrar();
  $conex1->cerrar();
  $conn_pyme->cerrar();
  $comunes = new Producto();
  $conex1=$conn_pyme = new ConexionComun();

  $dia=1;
  $dias_mes=cal_days_in_month(CAL_GREGORIAN, $mes, $anno_des);
  //echo "entra al mes: ".$mes."<br>";
  while ($dia<= $dias_mes) {
    $fecha_act=date("Y") . "/" . $mes . "/" . $dia;
    $sql_producto_new='INSERT INTO '.$BD_central.'.ventas_'.$anno_des.'_xproducto
(`cod_bar`, `precio`, `cantidad`, `punto_venta`, `fecha`, `id_estado`, `id_tipo_punto`, `id_categoria`, `id_subcategoria`, `tipo_almacenamiento`, `id_marca`, `id_taxes`) 
SELECT 
code, price, sum(units) as units, codigo_siga, DATENEW, b.codigo_estado as id_estado, c.id_tipo as id_tipo_punto, d.cod_grupo as id_categoria, d.sub_categoria as id_subcategoria, d.tipo_almacenamiento as tipo_almacenamiento, d.id_marca as id_marca,
a.TAXCAT AS id_taxes
FROM '.$BD_central.'.'.$ventas_anno.' a
INNER JOIN selectrapyme_central.puntos_venta c ON a.codigo_siga = c.codigo_siga_punto
INNER JOIN selectrapyme_central.estados b ON c.codigo_estado_punto = b.codigo_estado
LEFT JOIN selectrapyme.item d ON d.codigo_barras = a.CODE

WHERE DATENEW =  "' .$fecha_act.'" 

group by code, price, codigo_siga,`DATENEW`;';

    //echo $sql_producto_new."<br><br>";
    //echo $sql_producto_new; exit;
    $insert_producto=$conex1->Execute2($sql_producto_new);

    $dia++;
  }

$mes++;
}
$colocar_indices='ALTER TABLE '.$BD_central.'.ventas_'.$anno_des.'_xproducto ENABLE KEYS';
$producto_indices=$comunes->Execute2($colocar_indices);
// Fin de la modificación



?>
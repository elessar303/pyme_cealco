<?php
ini_set("display_errors", 1);
//session_start();
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");

require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
//agregado nuevo
require_once("../../config.ini.php");
require_once('../../../generalp.config.inc.php');
require_once('../../../includes/clases/BDControlador.php');

$ventas = new Almacen();
$login = new Login();
$cod_usuario=$login->getIdUsuario();
$hoy=date('Y-m-d');
$anio=date('Y');
$mes = date('m');

$anio2=date('y');

$pgsqlx = new BDControlador();
$pgsqlx->setDiver("pgsql");
$pgsqlx->conectar();

$consulta  = "select count(nop.codemp) as numero from \"SIMA016\".nphojint nop inner join \"SIMA016\".npasicaremp npa on (nop.codemp = npa.codemp) where staemp = 'A' and npa.status='V'";
$pgsqlx->setQuery($consulta);
$resultado = $pgsqlx->ejecutarSql();
$item      = $pgsqlx->fetch($resultado);
$empleados = $item[numero];


$consulta  = "select * from \"SIMA016\".presupuesto_por_mes";
$pgsqlx->setQuery($consulta);
$resultado = $pgsqlx->ejecutarSql();
$item      = $pgsqlx->fetch($resultado);
$comprometido = $item['monto_comprometido'];
$causado = $item['monto_causado'];
$pagado = $item['monto_pagado'];

$pgsql2 = new BDControlador();
$pgsql2->setDiver("pgsql2");
$pgsql2->conectar();

$consulta  = "select * from \"public\".proformas_por_mes";
$pgsql2->setQuery($consulta);
$resultado = $pgsql2->ejecutarSql();
$item = $pgsql2->fetch($resultado);
$cant_prof_emitidas = $item['cant_profomas_emitidas'];
//$causado = $item['monto_causado'];
//$pagado = $item['monto_pagado'];

$consulta = "select sum(unidades) as unidades, sum(tonelaje) as tonelaje, sum(totalconiva) as totalconiva, sum(totalsiniva) as totalsiniva, sum(clientes) as clientes from selectrapyme_central.indicadores where fecha ='$hoy'";
$array_datos = $ventas->ObtenerFilasBySqlSelect($consulta);
if(!isset($array_datos[0][unidades]))
{
    $consulta = "truncate table selectrapyme_central.indicadores;";
    $array_ventas = $ventas->Execute2($consulta);
	$sql_estados="SELECT * FROM selectrapyme_central.estados where codigo_estado<>'0010'";
    $sql_estados.=" ORDER BY codigo_estado limit 25";
	$reporte_estados = $ventas->ObtenerFilasBySqlSelect($sql_estados);

    $venta = "ventas_".$mes."_".$anio2."_cliente";
    $venta_prod = "ventas_".$mes."_".$anio2."_xproducto";

	foreach ($reporte_estados as $lista_estado )
	{

	    $sql_clientes="SELECT sum(cliente) as clientes
	        FROM selectrapyme_central.".$venta."
	        WHERE MONTH(fecha) = '$mes' and year(fecha) = '$anio'
	        AND codigo_siga IN 
            (SELECT codigo_siga_punto FROM selectrapyme_central.puntos_venta
                WHERE estatus='A'
                AND codigo_estado_punto='".$lista_estado['codigo_estado']."')";
	    $clientes = $ventas->ObtenerFilasBySqlSelect($sql_clientes);

	    $consulta = "insert into selectrapyme_central.indicadores (id, fecha, unidades, tonelaje, totalsiniva, totalconiva, clientes, codigo_estado) SELECT '', CURDATE(), sum( T1.cantidadx ) as UNITS, sum( T1.TONELAJEx ) as TONELAJE, sum(T1.TOTALSINIVAx) as TOTALSINIVA, sum(T1.TOTALCONIVAx) as TOTALCONIVA, '".$clientes[0][clientes]."', '".$lista_estado[codigo_estado]."'  FROM (SELECT  i.cod_bar, ifnull(sum(i.cantidad),0) as cantidadx, 
	                ifnull((it.pesoxunidad*um.transformar*SUM(i.cantidad))/1000000,0) as TONELAJEx,
	                ifnull(SUM(i.precio*i.cantidad),0) as TOTALSINIVAx,
	                ifnull(SUM((i.precio*(it.iva/100)+i.precio)*i.cantidad),0) as TOTALCONIVAx
	            FROM selectrapyme_central.".$venta_prod." i
	            LEFT JOIN selectrapyme_central.puntos_venta pv ON i.punto_venta =  pv.codigo_siga_punto
	            LEFT JOIN selectrapyme.item it ON i.cod_bar = it.codigo_barras
	            LEFT JOIN selectrapyme.unidad_medida um on it.unidadxpeso = um.id
	            WHERE month(i.fecha) = '$mes' and  year(i.fecha) = '$anio' and pv.codigo_estado_punto ='".$lista_estado[codigo_estado]."' and pv.id_tipo<>'7' 
	            GROUP BY i.cod_bar) as T1";
	    $array_ventas = $ventas->Execute2($consulta);
	}

    $consulta = "select sum(unidades) as unidades, sum(tonelaje) as tonelaje, sum(totalconiva) as totalconiva, sum(totalsiniva) as totalsiniva, sum(clientes) as clientes from selectrapyme_central.indicadores where fecha ='$hoy'";
    $array_datos = $ventas->ObtenerFilasBySqlSelect($consulta);
    /*$ventas->BeginTrans();

    $consulta = "insert into indicadores (id, fecha, unidades, tonelaje, totalconiva, totalsiniva) values ('', '$hoy', '".$array_ventas[0][UNITS]."', '".$array_ventas[0][TONELAJE]."', '".$array_ventas[0][TOTALCONIVA]."', '".$array_ventas[0][TOTALSINIVA]."');";
    $ventas->ExecuteTrans($consulta);

    if ($ventas->errorTransaccion == 1) {
        Msg::setMessage("<span style=\"color:#62875f;\">Producto Generado Exitosamente con en Nro. " . $nro_producto . "</span>");
    }
    if ($ventas->errorTransaccion == 0) {
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear el producto.</span>");
    }
    $ventas->CommitTrans($productos->errorTransaccion);*/

    $unidades = $array_datos[0][unidades];
    $tonelaje = $array_datos[0][tonelaje];
    $totalconiva = $array_datos[0][totalconiva];
    $totalsiniva = $array_datos[0][totalsiniva];
    $clientes = $array_datos[0][clientes];
}
else
{
    $unidades = $array_datos[0][unidades];
    $tonelaje = $array_datos[0][tonelaje];
    $totalconiva = $array_datos[0][totalconiva];
    $totalsiniva = $array_datos[0][totalsiniva];
    $clientes = $array_datos[0][clientes];
}


$sql = "SELECT v.CODE as CODE,
v.nombre_producto as nombre_producto,
SUM(v.UNITS) as UNITS,
COUNT(v.id_tickets) as TICKETS
FROM selectrapyme_central.ventas_05_16 v
WHERE MONTH(v.datenew_ticketlines) = '$mes' and YEAR(v.datenew_ticketlines) = '$anio' GROUP BY v.CODE ORDER BY TICKETS DESC limit 5;";
$reporte = $ventas->ObtenerFilasBySqlSelect($sql);

$consulta = "select indicadores.*, estados.nombre_estado as nombre from selectrapyme_central.indicadores join selectrapyme_central.estados on (estados.codigo_estado=indicadores.codigo_estado) where fecha ='$hoy' order by tonelaje DESC LIMIT 5";
$array_datos_comp = $ventas->ObtenerFilasBySqlSelect($consulta);	

//header("Location: index.php");
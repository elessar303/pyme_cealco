<?php
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

$fecha = @$_GET["fecha"];
$inicio = @$_GET["fecha"];
$final = @$_GET["fecha2"];
list($anio, $mes, $dia) = explode("-", $fecha);
$comunes = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

#$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT f.*, c.nombre, fd.*, c.rif FROM factura f inner join clientes c on c.id_cliente = f.id_cliente inner join factura_detalle_formapago fd on fd.id_factura = f.id_factura WHERE f.cod_estatus=2 AND year(f.fechaFactura) = year('" . $fecha . "') AND month(f.fechaFactura) = month('" . $fecha . "') AND day(f.fechaFactura) = day('" . $fecha . "') ORDER BY f.id_factura");    

//$sql="SELECT f.*, c.nombre, fd.*, c.rif FROM factura f INNER JOIN clientes c ON c.id_cliente = f.id_cliente INNER JOIN factura_detalle_formapago fd ON fd.id_factura = f.id_factura WHERE f.cod_estatus=2 AND year(f.fechaFactura) = {$anio} AND month(f.fechaFactura) = {$mes} AND day(f.fechaFactura) = {$dia} ORDER BY f.id_factura";
//echo $sql; exit;

$array_factura = $comunes->ObtenerFilasBySqlSelect(
"SELECT 
max(i.id_item) as id_item, 
i.cod_item, 
max(i.codigo_barras) as codigo_barras,
max(i.descripcion1) as descripcion,
sum(fd._item_cantidad) as totalunidades, 
max(fd._item_descripcion) as  _item_descripcion,
sum(fd._item_totalsiniva) AS totalsiniva, 
fd.id_item, 
sum(fd._item_totalconiva) AS totalconiva
FROM factura f
INNER JOIN factura_detalle fd ON fd.id_factura = f.id_factura 
INNER JOIN item i ON i.id_item = fd.id_item WHERE f.cod_estatus = 2 AND f.fechaFactura >= '{$inicio}' AND f.fechaFactura <= '{$final}' 
GROUP BY i.cod_item");

$tmp_fecha = strtoupper($dia . " de " . mesaletras($mes) . " de " . $anio);

$titulo="Reporte Ventas por Producto";
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");  
header ("Content-type: application/x-msexcel");  
header ("Content-Disposition: attachment; filename=\"".$titulo.".xls\"" );

?>
<html>
<table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
<tr bgcolor="#5084A9">
<td colspan="8" align="center"><b><font color="white">REPORTE DE VENTA POR PRODUCTO</font></b></td>    
</tr>
<tr bgcolor="#5084A9">
    <td><b><font color="white">N&deg;</font></b></td>
    <td align="center"><b><font color="white"><?php echo utf8_decode('Código Barras')?></font></b></td>
    <td align="center"><b><font color="white"><?php echo utf8_decode('Código')?></font></b></td>
    <td align="center"><b><font color="white"><?php echo utf8_decode('Descripción')?></font></b></td>
    <td align="center"><b><font color="white">Unidades</font></b></td>
    <td align="center"><b><font color="white">Subtotal</font></b></td>
    <td align="center"><b><font color="white">IVA</font></b></td>
    <td align="center"><b><font color="white" >Total</font></b></td>
</tr>
<?php
$i=0;


 while ($array_factura[$i]) { //While para impresion de consulta de ventas

$ivaitem=$array_factura[$i]["totalconiva"]-$array_factura[$i]["totalsiniva"];
if ($ivaitem<0){
$ivaitem=0;
}

if($array_factura[$i]["totalconiva"]==0){
$array_factura[$i]["totalconiva"]=$array_factura[$i]["totalsiniva"];
}

 ?>
<tr>
    <td><b><font><?php echo $i+1; ?></font></b></td>
    <td align="center"><b><font><?php echo $array_factura[$i]["codigo_barras"]; ?></font></b></td>
    <td align="center"><b><font><?php echo $array_factura[$i]["cod_item"]; ?></font></b></td>
    <td align="center"><b><font><?php echo $array_factura[$i]["descripcion"]; ?></font></b></td>
    <td align="center"><b><font><?php echo $array_factura[$i]["totalunidades"]; ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalsiniva"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($ivaitem, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalconiva"], 2, ',', '.'); ?></font></b></td>
</tr>

<?php

$totalUnidades+=$array_factura[$i]["totalunidades"];
$totalSiniva+=$array_factura[$i]["totalsiniva"];
$totalConiva+=$array_factura[$i]["totalconiva"];
$totalIvaitem+=$ivaitem;
$i++;
 } //Fin del While para impresion de consulta de ventas
?>
<tr>
  <td colspan="4" align="center"><b><font>TOTALES</font></b></td>
    <td align="center"><b><font><?php echo $totalUnidades; ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalSiniva, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalIvaitem, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalConiva, 2, ',', '.'); ?></font></b></td>
</tr>
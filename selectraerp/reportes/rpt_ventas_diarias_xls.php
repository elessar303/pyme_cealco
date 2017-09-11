<?php
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

$fecha = @$_GET["fecha"];
$fecha2 = @$_GET["fecha2"];
list($anio, $mes, $dia) = explode("-", $fecha);
$comunes = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

#$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT f.*, c.nombre, fd.*, c.rif FROM factura f inner join clientes c on c.id_cliente = f.id_cliente inner join factura_detalle_formapago fd on fd.id_factura = f.id_factura WHERE f.cod_estatus=2 AND year(f.fechaFactura) = year('" . $fecha . "') AND month(f.fechaFactura) = month('" . $fecha . "') AND day(f.fechaFactura) = day('" . $fecha . "') ORDER BY f.id_factura");    

//$sql="SELECT f.*, c.nombre, fd.*, c.rif FROM factura f INNER JOIN clientes c ON c.id_cliente = f.id_cliente INNER JOIN factura_detalle_formapago fd ON fd.id_factura = f.id_factura WHERE f.cod_estatus=2 AND year(f.fechaFactura) = {$anio} AND month(f.fechaFactura) = {$mes} AND day(f.fechaFactura) = {$dia} ORDER BY f.id_factura";
//echo $sql; exit;

$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "SELECT f.*, c.nombre, fd.*, c.rif
        FROM factura f
        INNER JOIN clientes c ON c.id_cliente = f.id_cliente
        INNER JOIN factura_detalle_formapago fd ON fd.id_factura = f.id_factura
        WHERE f.cod_estatus=2 AND f.fecha_creacion between '".$fecha." 00:00:00' and '".$fecha2." 23:59:59'ORDER BY f.id_factura");

$tmp_fecha = strtoupper($dia . " de " . mesaletras($mes) . " de " . $anio);

$titulo="Reporte Ventas Diarias";
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
<td colspan="14" align="center"><b><font color="white">REPORTE DE VENTA DEL <?php echo $tmp_fecha?></font></b></td>    
</tr>
<tr bgcolor="#5084A9">
    <td><b><font color="white">N&deg;</font></b></td>
    <td align="center"><b><font color="white"><?php echo utf8_decode('Nombre o Razón Social del Cliente')?></font></b></td>
    <td align="center"><b><font color="white">Rif</font></b></td>
    <td align="center"><b><font color="white">Factura</font></b></td>
    <td align="center"><b><font color="white">Subtotal</font></b></td>
    <td align="center"><b><font color="white">% IVA</font></b></td>
    <td align="center"><b><font color="white" >IVA</font></b></td>
    <td align="center"><b><font color="white" ><?php echo utf8_decode('Débito/Crédito')?></font></b></td>
    <td align="center"><b><font color="white" >Efectivo</font></b></td>
    <td align="center"><b><font color="white" ><?php echo utf8_decode('Depósito')?></font></b></td>
    <td align="center"><b><font color="white" >Cheque</font></b></td>
    <td align="center"><b><font color="white" >Otros</font></b></td>
    <td align="center"><b><font color="white" ><?php echo utf8_decode('Retención')?></font></b></td>
    <td align="center"><b><font color="white" >Total Factura</font></b></td>
</tr>
<?php
$i=0;
 while ($array_factura[$i]) { //While para impresion de consulta de ventas
 $totalFactura = $array_factura[$i]["montoItemsFactura"] + $array_factura[$i]["ivaTotalFactura"];
$porc = ($array_factura[$i]["ivaTotalFactura"] * 100) / $array_factura[$i]["montoItemsFactura"];
if (($porc >= 11.9) && ($porc < 12.5)){$porc = 12;}



 ?>
<tr>
	<td><b><font><?php echo $i+1; ?></font></b></td>
    <td align="center"><b><font><?php echo $array_factura[$i]["nombre"]; ?></font></b></td>
    <td align="center"><b><font><?php echo $array_factura[$i]["rif"]; ?></font></b></td>
    <td align="center"><b><font><?php echo $array_factura[$i]["cod_factura"]; ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalizar_base_imponible"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($porc, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["ivaTotalFactura"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalizar_monto_tarjeta"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalizar_monto_efectivo"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalizar_monto_deposito"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalizar_monto_cheque"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalizar_monto_otrodocumento"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($array_factura[$i]["totalizar_total_retencion"], 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalFactura, 2, ',', '.'); ?></font></b></td>
</tr>

<?php

$totalDebitoCredito+=$array_factura[$i]["totalizar_monto_tarjeta"];
$totalEfectivo+=$array_factura[$i]["totalizar_monto_efectivo"];
$totalDeposito+=$array_factura[$i]["totalizar_monto_deposito"];
$totalOtros+=$array_factura[$i]["totalizar_monto_otrodocumento"];
$totalVentasConIva+=$totalFactura;
$totalVentasNoGravadas+=0;
$totalBaseImponible+=$array_factura[$i]["totalizar_base_imponible"];
$totalIva+=$array_factura[$i]["ivaTotalFactura"];
$totalIvaRet+=$array_factura[$i]["totalizar_total_retencion"];
$totalCheque+=$array_factura[$i]["totalizar_monto_cheque"];
$i++;
 } //Fin del While para impresion de consulta de ventas
?>
<tr>
	<td colspan="4" align="center"><b><font>TOTALES</font></b></td>
    <td align="center"><b><font><?php echo number_format($totalBaseImponible, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font>N/A</font></b></td>
    <td align="center"><b><font><?php echo number_format($totalIva, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalDebitoCredito, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalEfectivo, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalDeposito, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalCheque, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalOtros, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalIvaRet, 2, ',', '.'); ?></font></b></td>
    <td align="center"><b><font><?php echo number_format($totalVentasConIva, 2, ',', '.'); ?></font></b></td>
</tr>
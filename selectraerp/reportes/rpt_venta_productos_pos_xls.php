<?php
$titulo="Reporte de Productos Vendidos";
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");  
header ("Content-type: application/x-msexcel");  
header ("Content-Disposition: attachment; filename=\"".$titulo.".xls\"" );
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');


$inicio = @$_GET["fecha"];
$final = @$_GET["fecha2"];
//$filtro = @$_GET["filtrado_por"];
$orden = @$_GET["ordenado_por"];
$producto=@$_GET["producto"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales"); 
        
$pos=POS;
if(!empty($producto))
{
  

  $filtro="and products.id in (".$producto.")";
}//fin del if de productos
else{

  $filtro="";
}//fin del else de productos

/*$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT products.REFERENCE,
  products.NAME,
  products.CODE,
  products.PRICESELL,
  sum(ticketlines.UNITS) as UNITS
FROM $pos.products INNER JOIN
  $pos.ticketlines ON products.ID = ticketlines.PRODUCT INNER JOIN
  $pos.tickets ON ticketlines.TICKET = tickets.ID       
WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
GROUP BY products.NAME  
ORDER BY NAME");*/

$sql="SELECT products.REFERENCE,
  products.NAME,
  products.CODE,
  ticketlines.PRICE AS PRICESELL,
  sum(ticketlines.UNITS) as UNITS,
  taxes.RATE as RATE
FROM $pos.products 
INNER JOIN  $pos.ticketlines ON products.ID = ticketlines.PRODUCT 
INNER JOIN  $pos.tickets ON ticketlines.TICKET = tickets.ID
LEFT JOIN  $pos.taxes ON ticketlines.TAXID = taxes.ID
WHERE DATENEW BETWEEN '{$inicio} 00:00:00' AND '{$final} 23:59:59'
AND ticketlines.SOLD=1
GROUP BY products.CODE,ticketlines.PRICE, ticketlines.TAXID
ORDER BY NAME"; 

$array_factura = $comunes->ObtenerFilasBySqlSelect($sql);

?>
<table border="1">
<tr>
<td colspan="8" align="center"><b><?php echo utf8_decode("REPORTE DE PRODUCTOS VENDIDOS")?></b></td>
</tr>
  <tr>
<td colspan="8" align="center"><b><?php echo "Desde {$_GET[fecha]} Hasta {$_GET[fecha2]}"; ?></b></td>
</tr>
<tr>
<td align="center"><b>Cod. Barras</b></td>
<td align="center"><b>Descripcion</b></td>
<td align="center"><b>U. Vendidas</b></td>
<td align="center"><b>Precio S/Iva</b></td>
<td align="center"><b>IVA</b></td>
<td align="center"><b>Precio C/Iva</b></td>
<td align="center"><b>Subtotal</b></td>
<td align="center"><b>Total</b></td>
</tr>

<?php    
  $precio =0;
  $unid =0;
  $i=0;
  foreach ($array_factura as $id => $reg) {
  	
  		$totsiniva=$reg["UNITS"]*$reg["PRICESELL"];
  		$totconiva=($reg["UNITS"]*$reg["PRICESELL"]*$reg["RATE"])+$reg["UNITS"]*$reg["PRICESELL"];
  		$prsiniva=$reg["PRICESELL"];
  		$prconiva=($reg["PRICESELL"]*$reg["RATE"])+$reg["PRICESELL"];
      $iva=$reg["RATE"]*100;
  		
		
	   echo "<tr><td style=mso-number-format:'@';>".$reg["CODE"]."</td><td>".$reg["NAME"]."</td><td>".number_format($reg["UNITS"], 2, ',', '.')."</td><td>".number_format($prsiniva, 2, ',', '.')."</td><td>".$iva."</td><td>".number_format($prconiva, 2, ',', '.')."</td><td>".number_format($totsiniva, 2, ',', '.')."</td><td>".number_format($totconiva, 2, ',', '.')."</td></tr>";
		$unid = $unid+$reg["UNITS"];
		$i++;
		$precio += $reg["PRICESELL"];
    $precio2 += $prconiva;
    $subtotal+=$reg["PRICESELL"]*$reg["UNITS"];
    $total+=$prconiva*$reg["UNITS"];
  }
  $precios = number_format($precio, 2, ',', '.');
  $precios2 = number_format($precio2, 2, ',','.');
  $subtotal = number_format($subtotal, 2, ',','.');
  $total = number_format($total, 2, ',', '.');
  $unids = number_format($unid, 2, ',', '.');
  
 echo "<tr><td align='center'>".$i."</td><td align='center'><b>T O T A L E S</b></td><td>".$unids."</td><td>".$precios."</td><td></td><td>".$precios2."</td><td>".$subtotal."</td><td>".$total."</td></tr>";
  ?>
  </table>
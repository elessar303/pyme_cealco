<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=productos_vendidos.xls");
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

$sql="SELECT item.descripcion1 as NAME,
  item.codigo_barras as CODE,
  factura_detalle._item_preciosiniva AS PRICESELL,
  sum(factura_detalle._item_cantidad) as UNITS,
  factura.fecha_creacion as DATENEW,
  factura_detalle._item_piva/100 as RATE
FROM item INNER JOIN
  factura_detalle ON item.id_item = factura_detalle.id_item INNER JOIN
  factura ON factura_detalle.id_factura = factura.id_factura       
WHERE factura.fecha_creacion BETWEEN '{$inicio} 00:00:00' AND '{$final} 23:59:59'
GROUP BY item.codigo_barras,factura_detalle._item_preciosiniva 
ORDER BY item.descripcion1";
//echo $sql; exit();
$array_factura = $comunes->ObtenerFilasBySqlSelect($sql);


?>
<table>
<tr>
<td colspan="5"><?php echo utf8_decode("REPORTE DE PRODUCTOS VENDIDOS")?></td>
</tr>
  <tr>
<td colspan="5"><?php echo "Desde {$_GET[fecha]} Hasta {$_GET[fecha2]}"; ?></td>
</tr>
<tr>
<td>Cod. Barras</td>
<td>Descripcion</td>
<td>Unid. Vendidas</td>

<td>Total sin IVA</td>
<td>Total con IVA</td>
<td>Precio Unit sin IVA</td>
<td>Precio Unit con IVA</td>
<td>Impuesto</td>

<?php    
  $precio =0;
  $unid =0;
  $i=0;
  $sum_totsiniva=0;
  $sum_totconiva=0;
  $sum_prsiniva=0;
  $sum_prconiva=0;
  foreach ($array_factura as $id => $reg) {
  	   


      $reg["RATE"]=number_format($reg["RATE"], 2, '.', '');
  		$totsiniva=$reg["UNITS"]*$reg["PRICESELL"];
  		$totconiva=($reg["UNITS"]*$reg["PRICESELL"]*$reg["RATE"])+$reg["UNITS"]*$reg["PRICESELL"];
  		$prsiniva=$reg["PRICESELL"];
  		$prconiva=($reg["PRICESELL"]*$reg["RATE"])+$reg["PRICESELL"];
  		
      $sum_totsiniva=$sum_totsiniva+$totsiniva;
      $sum_totconiva=$sum_totconiva+$totconiva;
      $sum_prsiniva=$sum_prsiniva+$prsiniva;
      $sum_prconiva=$sum_prconiva+$prconiva;
		
	   echo "<tr><td style=mso-number-format:'@';>".$reg["CODE"]."</td><td>".$reg["NAME"]."</td><td>".number_format($reg["UNITS"], 2, ',', '.')."</td><td>".number_format($totsiniva, 2, ',', '.')."</td><td>".number_format($totconiva, 2, ',', '.')."</td><td>".number_format($prsiniva, 2, ',', '.')."</td><td>".number_format($prconiva, 2, ',', '.')."</td><td>".number_format($reg["RATE"]*100, 0, '', '')."</td></tr>";
		$precio = $precio+$reg["PRICESELL"];
		$unid = $unid+$reg["UNITS"];
		$i++;
		$total_venta = $total_venta+$item_totales[$id];
  }
  $total_venta = number_format($total_venta, 2, ',', '.');
  
  $precios = number_format($precio, 2, ',', '.');
  $unids = number_format($unid, 2, ',', '.');
  $sum_totsiniva=number_format($sum_totsiniva, 2, ',', '.');
  $sum_totconiva=number_format($sum_totconiva, 2, ',', '.');
  $sum_prsiniva=number_format($sum_prsiniva, 2, ',', '.');
  $sum_prconiva=number_format($sum_prconiva, 2, ',', '.');
  
 echo "<tr><td>".$i."</td><td>T O T A L E S"."</td><td>".$unids."</td><td>".$sum_totsiniva."</td><td>".$sum_totconiva."</td><td>".$sum_prsiniva."</td><td>".$sum_prconiva."</td></tr>";
  ?>
  </table>
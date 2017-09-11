<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=productos_vendidos_central.xls");
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');


$inicio = @$_GET["fecha"];
$final = @$_GET["fecha2"];
$siga=@$_GET["siga"];
//$filtro = @$_GET["filtrado_por"];
$orden = @$_GET["ordenado_por"];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales"); 
        
//$bdpp = DB_SELECTRA_PYMEPP;
$bdpp=DB_REPORTE_CENTRAL;
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

if($siga!="null"){
    $siga="and siga='".$siga."'";
}else{
    $siga="";
}

#echo "SELECT * FROM $bdpp.vproducto where fecha BETWEEN '{$inicio}' AND '{$final}' $siga "; exit();
$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM $bdpp.vproducto where fecha BETWEEN '{$inicio}' AND '{$final}' $siga ");

?>
<table>
<tr>
<td colspan="5"><?php echo utf8_decode("REPORTE DE PRODUCTOS VENDIDOS")?></td>
</tr>
  <tr>
<td colspan="5"><?php echo "Desde {$_GET[fecha]} Hasta {$_GET[fecha2]}"; ?></td>
</tr>
<tr>
<td>Cod. SIGA</td>
<td>Cod. Barras</td>
<td>Codigo</td>
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
  foreach ($array_factura as $id => $reg) {
  	
  		$totsiniva=$reg["unidad"]*$reg["precio"];
  		$totconiva=($reg["unidad"]*$reg["precio"]*$reg["iva"])+$reg["unidad"]*$reg["precio"];
  		$prsiniva=$reg["precio"];
  		$prconiva=($reg["precio"]*$reg["iva"])+$reg["precio"];
  		
		
	   echo "<tr><td>".$reg["siga"]."</td><td>".$reg["codigo_barra"]."</td><td>".$reg["referencia"]."</td><td>".$reg["nombre"]."</td><td>".number_format($reg["unidad"], 2, ',', '.')."</td><td>".number_format($totsiniva, 2, ',', '.')."</td><td>".number_format($totconiva, 2, ',', '.')."</td><td>".number_format($prsiniva, 2, ',', '.')."</td><td>".number_format($prconiva, 2, ',', '.')."</td><td>".number_format($reg["iva"]*100, 0, '', '')."</td></tr>";
		$precio += $reg["precio"];
		$unid += $reg["unidad"];
		$i++;
		#$total_venta += $item_totales[$id];
  }
  #$total_venta = number_format($total_venta, 2, ',', '.');
  
  $precios = number_format($precio, 2, ',', '.');
  $unids = number_format($unid, 2, ',', '.');

  
 // echo "<tr><td>".$i."</td><td></td><td>T O T A L E S"."</td><td>".$unids."</td><td>".$precios."</td></tr>";
  ?>
  </table>

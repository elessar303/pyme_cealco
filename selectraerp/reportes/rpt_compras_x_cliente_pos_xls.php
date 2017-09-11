<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=compras_x_cliente.xls");
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');


$inicio = @$_GET["fecha"];
$final = @$_GET["fecha2"];
//$filtro = @$_GET["filtrado_por"];
$orden = @$_GET["ordenado_por"];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales"); 
        
$pos=POS;



$sql=" SELECT
     customers.SEARCHKEY AS cedula,
     customers.NAME AS cliente,
     products.NAME AS producto,
     ticketlines.PRICE,
     taxes.RATE,
     ticketlines.UNITS,
     ticketlines.DATENEW,
     products.CODE,
     products.REFERENCE
FROM
     $pos.tickets INNER JOIN $pos.ticketlines ON tickets.ID = ticketlines.TICKET
     INNER JOIN $pos.customers ON tickets.CUSTOMER = customers.ID
     INNER JOIN $pos.products ON ticketlines.PRODUCT = products.ID
INNER JOIN $pos.taxes ON ticketlines.TAXID = taxes.ID
WHERE DATENEW BETWEEN '{$inicio} 00:00:00' AND '{$final} 23:59:59'
AND ticketlines.SOLD=1
order by ticketlines.DATENEW, customers.SEARCHKEY, ticketlines.UNITS desc"; 
//echo $sql; exit();
$array_factura = $comunes->ObtenerFilasBySqlSelect($sql);
?>
<table>
<tr>
<td colspan="9" align="center"><?php echo utf8_decode("REPORTE DE PRODUCTOS VENDIDOS")?></td>
</tr>
  <tr>
<td colspan="9" align="center"><?php echo "Desde {$_GET[fecha]} Hasta {$_GET[fecha2]}"; ?></td>
</tr>
<tr>
<td>FECHA</td>
<td>CEDULA</td>
<td>CLIENTE</td>
<td>PRODUCTO</td>
<td>COD BARRAS</td>
<td>PRECIO</td>
<td>IVA</td>
<td>PRECIO CON IVA</td>
<td>CANTIDAD</td>
<td>TOTAL</td>
</tr>
<?php    
$precio =0;
$unid =0;
$i=0;
$nom="";
$efect=0;
$tj=0;
$ct=0;
$dev=0;
$total=0;
$tefect=0;
$ttj=0;
$tct=0;
$tdev=0;
$ttotal=0;
foreach ($array_factura as $id => $reg) 
{
  $precioConIva=($reg[PRICE])+($reg[PRICE]*$reg[RATE]);
  echo "<tr><td>".$reg[DATENEW]."</td><td>".$reg[cedula]."</td><td>".$reg[cliente]."</td><td>".$reg[producto]."</td><td style=mso-number-format:'@';>".$reg[CODE]."</td><td>".number_format($reg[PRICE], 2, ',', '.')."</td><td>".number_format(($reg[RATE]*100), 2, ',', '.')."</td><td>".number_format($precioConIva, 2, ',', '.')."</td><td>".number_format($reg[UNITS], 2, ',', '.')."</td><td>".number_format($precioConIva*$reg[UNITS], 2, ',', '.')."</td></tr>";
  $venta=$venta+($precioConIva*$reg[UNITS]);
  $i++;
  $precio = $precio+$reg["PRICE"];
  $unid = $unid+$reg["UNITS"];
  $i++;
}
$total_venta = number_format($venta, 2, ',', '.');
  
  $precios = number_format($precio, 2, ',', '.');
  $unids = number_format($unid, 2, ',', '.');
  
 echo "<tr><td align='center' colspan='5'>T O T A L E S"."</td><td>".$precios."</td><td colspan='2'></td><td>".$unids."</td><td>".$total_venta."</td></tr>";
  /*$total=$efect+$tj+$ct-$dev;
  			echo "<tr><td>".$nom."</td><td>".number_format($efect, 2, ',', '.')."</td><td>".number_format($tj, 2, ',', '.')."</td><td>".number_format($ct, 2, ',', '.')."</td><td>".number_format($dev, 2, ',', '.')."</td><td>".number_format($total, 2, ',', '.')."</td></tr>";
  			$tefect+=$efect;
			$ttj+=$tj;
			$tct+=$ct;
      $tdev+=$dev;
			$ttotal+=$total;
			
  			$efect=0;
		    $tj=0;
  			$ct=0;
        $dev=0;
  			$total=0;
  			$nom=$reg[NAME];
  #$total_venta = number_format($total_venta, 2, ',', '.');
  
  $precios = number_format($precio, 2, ',', '.');
  $unids = number_format($unid, 2, ',', '.');

  
  echo "<tr><td></td><td>".number_format($tefect, 2, ',', '.')."</td><td>".number_format($ttj, 2, ',', '.')."</td><td>".number_format($tct, 2, ',', '.')."</td><td>".number_format($tdev, 2, ',', '.')."</td><td>".number_format($ttotal, 2, ',', '.')."</td></tr>";
  ?>
  </table>*/
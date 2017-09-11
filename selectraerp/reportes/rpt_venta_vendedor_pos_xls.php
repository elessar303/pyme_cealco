<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ventas_x_cajero.xls");
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

$sql="SELECT people.NAME,
    payments.PAYMENT,
    sum(payments.TOTAL) as TOTAL
  FROM $pos.payments INNER JOIN
    $pos.receipts ON receipts.ID = payments.RECEIPT INNER JOIN
    $pos.tickets ON tickets.ID = receipts.ID INNER JOIN
    $pos.people ON tickets.PERSON = people.ID       
  WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
  GROUP BY people.NAME, payments.PAYMENT  
  ORDER BY NAME";

	$array_factura = $comunes->ObtenerFilasBySqlSelect(" SELECT people.NAME,
	  payments.PAYMENT,
	  sum(payments.TOTAL) as TOTAL
	FROM $pos.payments INNER JOIN
	  $pos.receipts ON receipts.ID = payments.RECEIPT INNER JOIN
	  $pos.tickets ON tickets.ID = receipts.ID INNER JOIN
	  $pos.people ON tickets.PERSON = people.ID       
	WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
	GROUP BY people.NAME, payments.PAYMENT  
	ORDER BY NAME");
?>
<table>
<tr>
<td colspan="5"><?php echo utf8_decode("REPORTE DE PRODUCTOS VENDIDOS")?></td>
</tr>
  <tr>
<td colspan="5"><?php echo "Desde {$_GET[fecha]} Hasta {$_GET[fecha2]}"; ?></td>
</tr>
<tr>
<td>NOMBRE</td>
<td>EFEC</td>
<td>TARJ</td>
<td>CT</td>
<td>DEV</td>
<td>TOTAL</td>
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
  foreach ($array_factura as $id => $reg) {
		if($i==0)
		{
			$nom=$reg[NAME];
			
		}
  		
  		if($nom!=$reg[NAME])
  		{
  			$total=$efect+$tj+$ct-$dev;
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
  		}
  		if($reg[PAYMENT]=="cash")
  		{
			 $efect=$reg[TOTAL];
  		}
		if($reg[PAYMENT]=="magcard")
  		{
			 $tj=$reg[TOTAL];
  		}
	   if($reg[PAYMENT]=="paperin")
  		{
			 $ct=$reg[TOTAL];
  		}
      if(($reg[PAYMENT]=="magcardrefund")||($reg[PAYMENT]=="cashrefund"))
      {
       $dev=$reg[TOTAL]*(-1);
      }

		
		$i++;
		#$total_venta += $item_totales[$id];
  }
  $total=$efect+$tj+$ct-$dev;
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
  </table>

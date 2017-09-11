<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ventas_x_cajero_central.xls");
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

if($siga!="null"){
    $siga="and siga='".$siga."'";
}else{
    $siga="";
}

$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM $bdpp.vcajero where fecha BETWEEN '{$inicio}' AND '{$final}' $siga");
?>
<table>
<tr>
<td colspan="5"><?php echo utf8_decode("REPORTE DE VENTAS POR CAJERO")?></td>
</tr>
  <tr>
<td colspan="5"><?php echo "Desde {$_GET[fecha]} Hasta {$_GET[fecha2]}"; ?></td>
</tr>
<tr>
<td>SIGA</td>
<td>NOMBRE</td>
<td>EFEC</td>
<td>TARJ</td>
<td>CT</td>
<td>TOTAL</td>
<?php    
  $precio =0;
  $unid =0;
  $i=0;
  $nom="";
  $efect=0;
  $tj=0;
  $ct=0;
  $total=0;
  $tefect=0;
  $ttj=0;
  $tct=0;
	$ttotal=0;
  foreach ($array_factura as $id => $reg) {
		if($i==0)
		{
			$nom=$reg[nombre];
			
		}
  		
  		if($nom!=$reg[nombre])
  		{
  			$total=$efect+$tj+$ct;
  			echo "<tr><td>".$reg[siga]."</td><td>".$nom."</td><td>".number_format($efect, 2, ',', '.')."</td><td>".number_format($tj, 2, ',', '.')."</td><td>".number_format($ct, 2, ',', '.')."</td><td>".number_format($total, 2, ',', '.')."</td></tr>";
  			$tefect+=$efect;
			$ttj+=$tj;
			$tct+=$ct;
			$ttotal+=$total;
			
  			$efect=0;
		   $tj=0;
  			$ct=0;
  			$total=0;
  			$nom=$reg[nombre];
  		}
  		if($reg[tipo_pago]=="cash")
  		{
			 $efect=$reg[monto];
  		}
		if($reg[tipo_pago]=="magcard")
  		{
			 $tj=$reg[monto];
  		}
	   if($reg[tipo_pago]=="paperin")
  		{
			 $ct=$reg[monto];
  		}
		
		$i++;
		#$total_venta += $item_totales[$id];
  }
  $total=$efect+$tj+$ct;
  			echo "<tr><td>".$reg[siga]."</td><td>".$nom."</td><td>".number_format($efect, 2, ',', '.')."</td><td>".number_format($tj, 2, ',', '.')."</td><td>".number_format($ct, 2, ',', '.')."</td><td>".number_format($total, 2, ',', '.')."</td></tr>";
  			$tefect+=$efect;
			$ttj+=$tj;
			$tct+=$ct;
			$ttotal+=$total;
			
  			$efect=0;
		   $tj=0;
  			$ct=0;
  			$total=0;
  			$nom=$reg[nombre];
  #$total_venta = number_format($total_venta, 2, ',', '.');
  
  $precios = number_format($precio, 2, ',', '.');
  $unids = number_format($unid, 2, ',', '.');

  
  echo "<tr><td></td><td></td><td>".number_format($tefect, 2, ',', '.')."</td><td>".number_format($ttj, 2, ',', '.')."</td><td>".number_format($tct, 2, ',', '.')."</td><td>".number_format($ttotal, 2, ',', '.')."</td></tr>";
  ?>
  </table>

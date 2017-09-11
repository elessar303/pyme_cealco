<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ISLR_RETENIDO.xls");
include('config_reportes.php');
include('../../menu_sistemas/lib/common.php');

$fecha = @$_GET["fecha"];


$comunes = new ConexionComun();


$arrayFacturas = $comunes->ObtenerFilasBySqlSelect("SELECT com. * , comdet . * , SUM( comdet._item_cantidad ) AS cantidadTotal, SUM( comdet._item_totalsiniva ) AS montoTotal, it. * , li . * FROM compra com JOIN compra_detalle comdet ON ( com.id_compra = comdet.id_compra ) JOIN item it ON ( comdet.id_item = it.id_item ) JOIN linea li ON ( it.cod_linea = li.cod_linea ) WHERE it.cod_item_forma <> 2 and cod_estatus <> 3 and fechacompra BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."' GROUP BY descripcion1");
?>
<table border="1">
<tr style="Font-weight: bold;" >
<TD colspan="4" align="left" >COMPRAS EFECTUADAS POR SERVICIO/PRODUCTO
</TD>
<TD align="center" ><?php echo "Desde: ".fecha($fecha).' Hasta: '.fecha($_GET["fecha_hasta"]);?>
</TD>

</tr>

<Tr style="Font-weight: bold;">
	<TD>PRODUCTOS</TD>
</Tr>

<Tr style="Font-weight: bold;">
<TD>Codigo</TD>
<TD>Linea</TD>
<TD>Descripcion</TD>
<TD>Cantidad</TD>
<TD>Total sin IVA</TD>
<tr>
<?php
$totalBase=0;
$i=0;
$totalfinal=0;

					
while($arrayFacturas[$i])
{

		echo "<td>".$arrayFacturas[$i]["cod_compra"]."</td>
		<td>".$arrayFacturas[$i]["descripcion"]."</td>
		<td>".$arrayFacturas[$i]["descripcion1"]."</td>
		<td>".$arrayFacturas[$i]["cantidadTotal"]."</td>
		<td>".number_format($arrayFacturas[$i]["montoTotal"],2,",",".")."</td>";
		
		$totalBase+=$arrayFacturas[$i]["montoTotal"];
		$totalfinal+=$totalBase;
//		$totalRet+=$arrayFacturas[$i][monto_retenido];

	echo "</tr>";
	$i++;
}
echo "<tr><td colspan='4'> TOTAL: </td> <td>".number_format($totalBase,2,",",".")."</td>";
echo "</tr>";

$arrayFacturas2 = $comunes->ObtenerFilasBySqlSelect("SELECT com. * , comdet . * , SUM( comdet._item_cantidad ) AS cantidadTotal, SUM( comdet._item_totalsiniva ) AS montoTotal, it. * , li . * FROM compra com JOIN compra_detalle comdet ON ( com.id_compra = comdet.id_compra ) JOIN item it ON ( comdet.id_item = it.id_item ) JOIN linea li ON ( it.cod_linea = li.cod_linea ) WHERE it.cod_item_forma =2 and cod_estatus <> 3 and fechacompra BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."' GROUP BY descripcion1");
?>

<Tr style="Font-weight: bold;">
	<TD>SERVICIOS</TD>
</Tr>

<Tr style="Font-weight: bold;">
<TD>Codigo</TD>
<TD>Linea</TD>
<TD>Descripcion</TD>
<TD>Cantidad</TD>
<TD>Total sin IVA</TD>
<tr>
<?php
$totalBase=0;
$i=0;


					
while($arrayFacturas2[$i])
{

		echo "<td>".$arrayFacturas2[$i]["cod_item"]."</td>
		<td>".$arrayFacturas2[$i]["descripcion"]."</td>
		<td>".$arrayFacturas2[$i]["descripcion1"]."</td>
		<td>".$arrayFacturas2[$i]["cantidadTotal"]."</td>
		<td>".number_format($arrayFacturas2[$i]["montoTotal"],2,",",".")."</td>";
		
		$totalBase+=$arrayFacturas2[$i]["montoTotal"];
		$totalfinal+=$totalBase;
//		$totalRet+=$arrayFacturas[$i][monto_retenido];

	echo "</tr>";
	$i++;
}
echo "<tr><td colspan='4'> TOTAL: </td> <td>".number_format($totalBase,2,",",".")."</td>";
echo "</tr>";

echo "<tr><td colspan='4'> TOTAL FINAL : </td> <td>".number_format($totalfinal,2,",",".")."</td>";
echo "</tr>";
?>

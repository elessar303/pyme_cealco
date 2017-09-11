<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=IVA_RETENIDO.xls");
include('config_reportes.php');
include('../../menu_sistemas/lib/common.php');



$fecha = @$_GET["fecha"];

$dias=substr($fecha,0,2);
$mes=mesaletras(substr($fecha,3,2));
$anio=substr($fecha,6,4);
if($dias<=15)
{
	$diaIni=$anio."-".substr($fecha,3,2)."-01";
	$diaFin=$anio."-".substr($fecha,3,2)."-15";
	$cad="1 Quincena ".$mes." ".$anio;
}
else
{
	$diaIni=$anio."-".substr($fecha,3,2)."-16";
	$diaFin=$anio."-".substr($fecha,3,2)."-".date("t",mktime(0, 0, 0, substr($fecha,3,2), 1,$anio));
	$cad="2 Quincena ".$mes." ".$anio;
}
$periodo=$anio.substr($fecha,3,2);
$comunes = new ConexionComun();

$datosGenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
$fechaz=$fecha."-01";
$arrayFacturas =   $comunes->ObtenerFilasBySqlSelect("SELECT cxpf.*, pro.rif as prorif , pro.descripcion as prodesc  FROM cxp_factura cxpf JOIN cxp_edocuenta cxpe ON ( cxpf.id_cxp_edocta = cxpe.cod_edocuenta ) JOIN proveedores pro ON ( pro.id_proveedor = cxpe.id_proveedor ) where fecha_recepcion BETWEEN '".$diaIni."' and '".$diaFin."' ORDER BY cxpf.cod_correlativo_iva ");
?>
<table border="1">
<tr style="Font-weight: bold;" >
<TD colspan="17" align="left" ><?php echo $datosGenerales[0]["nombre_empresa"];?>
</TD>
</tr>
<tr style="Font-weight: bold;" >
<TD colspan="17" align="left" >RIF: <?php echo $datosGenerales[0]["rif"];?>
</TD></TR>

<tr style="Font-weight: bold;" >
<TD colspan="17" align="center" >RETENCIONES DE IVA
</TD>
</tr>
<tr style="Font-weight: bold;" >
<TD colspan="17" align="center" ><?php echo $cad;?>
</TD></TR>

<Tr style="Font-weight: bold;">
<TD>Rif. Empresa</TD>
<TD>Periodo Impositivo</TD>
<TD>Fecha Doc.</TD>
<TD>Tipo Op.</TD>
<TD>Tipo Doc.</TD>
<TD>Proveedor</TD>
<TD>RIF Proveedor</TD>
<TD>Nro. Documento</TD>
<TD>Nro. Control</TD>
<TD>Monto Documento</TD>
<TD>Base Imponible</TD>
<TD>Iva Retenido</TD>
<TD>Doc. Afectado</TD>
<TD>Nro. Comprobante</TD>
<TD>Monto Exento</TD>
<TD>Alicuota</TD>
<TD>Exp</TD>
<tr>
<?php
$totalPagar=0;
$totalIva=0;
$totalIvaRet=0;
$totalExento=0;
$i=0;
while($arrayFacturas[$i])
{
// 	$porc=($arrayFacturas[$i][ivaTotalcompra]*100)/$arrayFacturas[$i][montoItemscompra];
// 	if(($porc>=11.9)&&($porc<12))
// 		$porc=12;
	if($arrayFacturas[$i][cod_correlativo_iva])
	{
		echo "<td>".$datosGenerales[0]["rif"]."</td>
		<td>".$periodo."</td>
		<td>".$arrayFacturas[$i][fecha_factura]."</td>
		<td>C</td>
		<td>1</td>
		<td>".$arrayFacturas[$i][prodesc]."</td>
		<td>".$arrayFacturas[$i][prorif]."</td>
		<td>".$arrayFacturas[$i][cod_factura]."</td>
		<td>".$arrayFacturas[$i][cod_cont_factura]."</td>";
		if($arrayFacturas[$i][cod_estatus]==3){
			echo "<td>0</td>
			<td>0</td>
			<td>0</td>";
			if($arrayFacturas[$i][factura_afectada]==''){
				echo "<td>0</td>";
			}else{
				echo "<td>".$arrayFacturas[$i][factura_afectada]."</td>";
			}
			echo "<td>".$arrayFacturas[$i][cod_correlativo_iva]."</td>
			<td>0</td>
			<td>".number_format($arrayFacturas[$i][porcentaje_iva_mayor],2,",",".")."</td>
			<td>0</td>";
		}else{
			echo "<td>".number_format($arrayFacturas[$i][monto_total_con_iva],2,",",".")."</td>
			<td>".number_format($arrayFacturas[$i][monto_base],2,",",".")."</td>
			<td>".number_format($arrayFacturas[$i][monto_retenido],2,",",".")."</td>";
			if($arrayFacturas[$i][factura_afectada]==''){
				echo "<td>0</td>";
			}else{
				echo "<td>".$arrayFacturas[$i][factura_afectada]."</td>";
			}
			echo "<td>".$arrayFacturas[$i][cod_correlativo_iva]."</td>
			<td>".number_format($arrayFacturas[$i][monto_exento],2,",",".")."</td>
			<td>".number_format($arrayFacturas[$i][porcentaje_iva_mayor],2,",",".")."</td>
			<td>0</td>";
			$totalPagar+=$arrayFacturas[$i][monto_total_con_iva];
			$totalIva+=$arrayFacturas[$i][monto_base];
			$totalIvaRet+=$arrayFacturas[$i][monto_retenido];
			$totalExento+=$arrayFacturas[$i][monto_exento];
		}
		echo "</tr>";
		
		
		
	}
	$i++;
}
echo "<tr><td colspan='9'> TOTAL: </td> <td>".number_format($totalPagar,2,",",".")."</td> <td>".number_format($totalIva,2,",",".")."</td> <td>".number_format($totalIvaRet,2,",",".")."</td><td></td>
<td></td><td>".number_format($totalExento,2,",",".")."</td> <td></td> <td></td>";
echo "</tr>";

?>

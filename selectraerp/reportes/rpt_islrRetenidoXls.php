<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ISLR_RETENIDO.xls");
include('config_reportes.php');
include('../../menu_sistemas/lib/common.php');

$fecha = @$_GET["fecha"];

$mes=mesaletras(substr($fecha,0,2));
$anio=substr($fecha,3,4);
$diaIni=$anio."-".substr($fecha,0,2)."-01";
$diaFin=$anio."-".substr($fecha,0,2)."-".date("t",mktime(0, 0, 0, substr($fecha,0,2), 1,$anio));
$cad=$mes." ".$anio;
$periodo=$anio.substr($fecha,0,2);
$comunes = new ConexionComun();

$datosGenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
$fechaz=$fecha."-01";
$arrayFacturas =   $comunes->ObtenerFilasBySqlSelect("SELECT cxpf.*, pro.rif as prorif , pro.descripcion as prodesc, cxpfd.*, li.codificacion_impuesto, li.descripcion as descli  FROM cxp_factura cxpf JOIN cxp_edocuenta cxpe ON ( cxpf.id_cxp_edocta = cxpe.cod_edocuenta ) JOIN proveedores pro ON ( pro.id_proveedor = cxpe.id_proveedor ) JOIN cxp_factura_detalle cxpfd ON (cxpfd.id_factura_fk=cxpf.id_factura) JOIN lista_impuestos li ON (li.cod_impuesto=cxpfd.cod_impuesto) where fecha_recepcion BETWEEN '".$diaIni."' and '".$diaFin."' ORDER BY cxpf.fecha_factura ");
?>
<table border="1">
<tr style="Font-weight: bold;" >
<TD colspan="4" align="left" >RELACION DE RETENCIONES ISLR
</TD>
<TD colspan="4" align="right" >Rif Agente: 
</TD>
<Td align="left" ><?php echo $datosGenerales[0]["rif"];?>
</Td>

</tr>
<Tr style="Font-weight: bold;" >
<TD colspan="4" align="left" ><?php echo $cad;?>
</TD>
<TD colspan="4" align="right" >Periodo:
</TD>
<TD align="center" ><?php echo $periodo?>
</TD>
</TR>

<Tr style="Font-weight: bold;">
<TD>Proveedor</TD>
<TD>Rif. Proveedor</TD>
<TD>Numero Factura</TD>
<TD>Numero Control</TD>
<TD>Codigo Concepto</TD>
<TD>Monto Operacion</TD>
<TD>Porcentaje Retencion</TD>
<TD>Monto Retencion ISLR</TD>
<TD>Concepto</TD>
<tr>
<?php
$totalPagar=0;
$totalIva=0;
$totalIvaRet=0;
$totalExento=0;
$i=0;
while($arrayFacturas[$i])
{
	if($arrayFacturas[$i][cod_estatus]!=3 && $arrayFacturas[$i][monto_retenido]>0)
	{
		echo "<td>".$arrayFacturas[$i][prodesc]."</td>
		<td>".$arrayFacturas[$i][prorif]."</td>
		<td>".$arrayFacturas[$i][cod_factura]."</td>
		<td>".str_replace ("-","",$arrayFacturas[$i][cod_cont_factura])."</td>
		<td>".$arrayFacturas[$i][codificacion_impuesto]."</td>
		<td>".number_format($arrayFacturas[$i][monto_base],2,",",".")."</td>
		<td>".number_format($arrayFacturas[$i][porcentaje_retenido],2,",",".")."</td>
		<td>".number_format($arrayFacturas[$i][monto_retenido],2,",",".")."</td>
		<td>".$arrayFacturas[$i][descli]."</td>";
		$totalBase+=$arrayFacturas[$i][monto_base];
		$totalRet+=$arrayFacturas[$i][monto_retenido];
	}
	echo "</tr>";
	$i++;
}
echo "<tr><td colspan='5'> TOTAL: </td> <td>".number_format($totalBase,2,",",".")."</td><td></td> <td>".number_format($totalRet,2,",",".")."</td> <td></td>";
echo "</tr>";

?>

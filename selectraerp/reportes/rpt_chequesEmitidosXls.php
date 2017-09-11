<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=CHEQUES_EMITIDOS.xls");
include('config_reportes.php');
include('../../menu_sistemas/lib/common.php');

$comunes = new ConexionComun();
$datosGenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

if(($_GET["fecha"]=='') || ($_GET["fecha2"]==''))
{
	$array_cheques = $comunes->ObtenerFilasBySqlSelect("SELECT ch.*, pro.descripcion, pro.rif, ban.descripcion as descban, codt.descripcion as descta FROM cheque ch join proveedores pro on (pro.id_proveedor=ch.id_proveedor) join chequera chq on (ch.cod_chequera=chq.cod_chequera) join tesor_bancodet codt on (codt.cod_tesor_bandodet=chq.cod_tesor_bandodet) join banco ban on (codt.cod_banco=ban.cod_banco)");
}
else
{
	$fecha =fecha_sql($_GET["fecha"]);
	$fecha2 =fecha_sql($_GET["fecha2"]);
	$array_cheques = $comunes->ObtenerFilasBySqlSelect("SELECT ch.*, pro.descripcion, pro.rif, ban.descripcion as descban, codt.descripcion as descta FROM cheque ch join proveedores pro on (pro.id_proveedor=ch.id_proveedor) join chequera chq on (ch.cod_chequera=chq.cod_chequera) join tesor_bancodet codt on (codt.cod_tesor_bandodet=chq.cod_tesor_bandodet) join banco ban on (codt.cod_banco=ban.cod_banco) WHERE fecha BETWEEN '$fecha' and '$fecha2'");
}

$title='LISTADO DE CHEQUES EMITIDOS';

?>
<table border="1">
<tr style="Font-weight: bold;" >
<TD colspan="9" align="left" ><?php echo $datosGenerales[0]["nombre_empresa"];?>
</TD>
</tr>
<tr style="Font-weight: bold;" >
<TD colspan="9" align="left" >RIF: <?php echo $datosGenerales[0]["rif"];?>
</TD></TR>

<tr style="Font-weight: bold;" >
<TD colspan="9" align="center" ><?php echo $title;?>
</TD>
</tr>
<tr style="Font-weight: bold;" >
<TD colspan="9" align="center" ><?php echo "Periodo: ".$_GET["fecha"]." Hasta " .$_GET["fecha2"];?>
</TD></TR>

<Tr style="Font-weight: bold;">
<TD>CHEQUE</TD>
<TD>CHEQUERA</TD>
<TD>CTA.</TD>
<TD>BANCO</TD>
<TD>PROVEEDOR</TD>
<TD>RIF.</TD>
<TD>FECHA</TD>
<TD>CONCEPTO</TD>
<TD>MONTO</TD>
<tr>
<?php
$i=0;
while($array_cheques[$i])
{
	echo "<tr>";
	echo "<td>".$array_cheques[$i]["cheque"]."</td>";
	echo "<td>".$array_cheques[$i]["cod_chequera"]."</td>";
	echo "<td>".$array_cheques[$i]["descta"]."</td>";
	echo "<td>".$array_cheques[$i]["descban"]."</td>";
	echo "<td>".$array_cheques[$i]["descripcion"]."</td>";
	echo "<td>".$array_cheques[$i]["rif"]."</td>";
	echo "<td>".$array_cheques[$i]["fecha"]."</td>";
	echo "<td>".$array_cheques[$i]["concepto"]."</td>";
	echo "<td>".number_format($array_cheques[$i]["monto"], 2, ',', '.')."</td>";
	echo "</tr>";
	$total+=$array_cheques[$i]["monto"];
	$i++;
}
echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>TOTAL CH.: $i</td>";
echo "<td>".number_format($total, 2, ',', '.')."</td>";
echo "</tr>";
?>

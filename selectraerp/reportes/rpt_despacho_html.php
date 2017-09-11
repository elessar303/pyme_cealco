<?php

# Modificado el sabado, 28 de enero de 2012
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
#require_once("../libs/php/ajax/numerosALetras.class.php");


$nro_despacho = @$_GET["codigo"];
$comunes = new ConexionComun();

$array_despacho = $comunes->ObtenerFilasBySqlSelect("select * from despacho where id='$nro_despacho'");


$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

$array_factura = $comunes->ObtenerFilasBySqlSelect("
SELECT f . * , c.nombre, c.direccion, c.nit, c.cod_cliente, c.rif, c.telefonos, c.direccion, v.nombre AS nom_vendedor, v.cod_vendedor AS contador, v.cod_vendedor, fd . * , fp . * , i.cod_item, ifor.descripcion AS tipo_item_
FROM factura f
INNER JOIN clientes c ON c.id_cliente = f.id_cliente
INNER JOIN factura_detalle fd ON fd.id_factura = f.id_factura
INNER JOIN vendedor v ON v.cod_vendedor = f.cod_vendedor
INNER JOIN factura_detalle_formapago fp ON f.id_factura = fp.id_factura
INNER JOIN item i ON i.id_item = fd.id_item
INNER JOIN item_forma ifor ON ifor.cod_item_forma = i.cod_item_forma
WHERE f.id_factura = '".$array_despacho[0][id_factura]."'
LIMIT 0 , 30;");


?>


<script type="text/javascript" >
function imprimir(nombre){
	var ficha = document.getElementById(nombre)
	var ventimp = window.open(' ', 'popimpr','width=700, height=600, TOOLBAR=NO, LOCATION=NO, MENUBAR=NO, SCROLLBARS=NO, RESIZABLE=NO')
var estilo="<link href=\"../estilos.css\" rel=\"stylesheet\" type=\"text/css\">"
ventimp.document.write( estilo )
	ventimp.document.write( ficha.innerHTML )
	ventimp.document.close()
	ventimp.print()
	ventimp.close()
}

</script>


<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div style="margin-left: 60px;" id="impresion">
<table>
<tr>
<td>
<?php echo $array_parametros_generales[0][nombre_empresa];?>
</td>
</tr>
<tr>
<td>
<?php echo $array_parametros_generales[0][rif];?>
</td>
</tr>
</table>

<?php 
list($anio, $mes, $dia) = explode("-", $array_factura[0]["fechaFactura"]);
$meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
  ?>

<table width="900">
<tr>
<td colspan="4" align="right">  
<?php echo "NOTA DE ENTREGA Nro. {$array_despacho[0]['cod_despacho']}";?>
</td>
</tr>

<tr>
<td align="left">  
<?php echo "RAZON SOCIAL: ". utf8_decode($array_factura[0]['nombre']);?>
</td>

<td align="right">  
<?php echo "FECHA EMISION:  {$dia}/{$mes}/{$anio}";?>
</td>
</tr>

<tr>
<td align="left">  
<?php echo utf8_decode($array_parametros_generales[0]["id_fiscal"] . ", C.I. Ó PASAPORTE: ").': '.$array_factura[0]["rif"]?>
</td>

<td align="right">  
<?php echo "FECHA VENCIMIENTO:  {$dia}/{$mes}/{$anio}";?>
</td>
</tr>

<tr>
<td align="left">  
<?php echo "DIRECCION: ".$array_parametros_generales[0]["direccion"]?>
</td>

<td align="right">  
<?php echo "FACTURA ASO.:  {$array_factura[0]["cod_factura"]}";?>
</td>
</tr>

<tr>
<td colspan="2" align="left">  
<?php echo "TELEFONOS  {$array_parametros_generales[0]['telefonos']}";?>
</td>
</tr>
</table>

<table>
<tr>
<td>
<br>
</td>
</tr>
</table>

<table>
<tr>
<td width="200">
Codigo producto
</td>

<td width="450">
Descripcion
</td>

<td width="250">
Serial
</td>

</tr>
</table> 


<table>
<?php
$despacho_items = $comunes->ObtenerFilasBySqlSelect("select dd.*, ii.codigo_barras from despacho_detalle dd left join item ii on (dd.id_item=ii.id_item) where id_despacho='$nro_despacho'");

  foreach ($despacho_items as $item) {
  	
  	echo "<tr><td width='200'>$item[codigo_barras]</td>
  	<td width='450'>".utf8_decode($item[item_descripcion])."</td>
	<td width='250'>".$item[serial]."</td></tr>"; 
  }
?>
</table>

<table>
<tr>
<td>
<br>
<br>
<br>
<br>
</td>
</tr>
</table>


<table width="900" cellpadding="0" cellspacing="0">
<tr  style=" border: 1px solid; height:70; ">
<td width="300" style=" border: 1px solid; height:70; valign:bottom;">

Entregado por:
</td>
<td width="300" style=" border: 1px solid; height:70;">
Recibido por:
</td >
<td width="300" style=" border: 1px solid; height:70;"	>
Revisado por:
</td>
</tr>
</table>
</div>







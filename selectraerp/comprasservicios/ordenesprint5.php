<?php 
if (!isset($_SESSION)) {
  session_start();
}
	require_once '../lib/config.php';
	require_once '../lib/common.php';
	include ("../header.php");
	$desTipo = $_GET['desTipo'];
	$cantidad_registros=20;

	$Conn=conexion_conf(); //= new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
	$var_sql="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der from parametros";
	$rs = query($var_sql,$Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1=$row_rs['encabezado1'];
	$var_encabezado2=$row_rs['encabezado2'];
	$var_encabezado3=$row_rs['encabezado3'];
	$var_encabezado4=$row_rs['encabezado4'];
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];	
	$var_sql="select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$var_nomemp=$row_rsu['nomemp'];
	cerrar_conexion($Conn);

?>
<!--<p style="font-size : 14pt;"> prueba</p>-->
<?php
	$conexion=conexion();
	$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
	$desTipo = (empty($_REQUEST['desTipo'])) ? '' : $_REQUEST['desTipo'];
	$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
	$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];
	
	$consulta_req="SELECT * FROM ordenes_detalles  WHERE cod_ord =".$id;
	$rs = query($consulta_req,$conexion);
	
	$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
	$pagina=obtener_pagina_actual($pagina,$num_paginas);
  
function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows)
{
	$conexion=conexion();	
	$var_sql="select * from ordenes where codigo=".$id." and estado <> 'Anulado'";
	$rs = query($var_sql,$conexion);
	$row_rs = fetch_array($rs);
	//$var_fecha=$row_rs['fecha'];
	$var_centro=$row_rs['centro_costo'];
	$var_concepto=$row_rs['concepto'];
	$var_unidad=$row_rs['unidad'];
	$var_centro=$row_rs['cod_centro'];
	$var_cod_requisicion=$row_rs['cod_requi'];
		
	$var_monto_orden=$row_rs['monto_orden'];
	$var_dias_credito=$row_rs['dias_credito'];
	$var_imponible=$row_rs['imponible'];
	$var_monto_iva=$row_rs['monto_iva'];
	$var_monto_excento=$row_rs['monto_excento'];
	$var_fecha=$row_rs['fecha'];
	$var_funcion=$row_rs['funcion'];
	$var_codigo=$row_rs['codigo_ref'];
	$var_tipo=$row_rs['tipo'];
	$var_estado=$row_rs['estado'];
	$var_cod_proveedor=$row_rs['cod_provee'];
			
	$var_sqlu="SELECT compania,rif FROM proveedores WHERE cod_proveedor=$var_cod_proveedor";
	$rsu = query($var_sqlu,$conexion);		
	$row_rsu = fetch_array($rsu);
	$var_compania=$row_rsu['compania'];
	$var_rif=$row_rsu['rif'];
	
	$var_sql="SELECT descripcion FROM unidades WHERE cod_unidad=".$var_unidad;
	$rsu = query($var_sql,$conexion);		
	$row_rsu = fetch_array($rsu);
	$var_nom_und=$row_rsu['descripcion'];
	 
	$rs = query("select descripcion from ordenes_tipos where cod_orden_tipo = '$var_tipo' ",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{	
		//echo "Pase por aqui";	
		$var_nom_tipo=$row_rs['descripcion'];
	}
		//echo "Tipo de orden".$x_cod_orden_tipo_req;

$monto_orden  = number_format($var_monto_orden,2,',','.');
// <tr>
// 		<td width="600"></td>
// 		<td width="100" align="right" class="texto8"><strong>Pág.: '.$pagina.'/'.$num_paginas.'</strong></td>
// 		<td width="100"></td>
// 	</tr>
$datos_orden=
'
<table width="800" border="1" align="center">
	<tr>
		<td align="right" colspan=4 class="texto13"  bgcolor="#CCCCCC"><strong>ORDEN DE '.strtoupper($desTipo).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No.: '.$var_codigo.'</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Numero de control N°'.$id.'</td>
	</tr>
	<tr bgcolor="#CCCCCC">
		<td align="center" width="400" class="texto8"><strong>Proveedor o Suplidor</strong></td>
		<td align="center" width="200" class="texto8"><strong>Unidad Solicitante</strong></td>
		<td align="center" width="100" class="texto8"><strong>No. R.I.F.</strong></td>
		<td align="center" width="100" class="texto8"><strong>Fecha</strong></td>
	</tr>
	<tr>
		<td class="texto8">'.$var_compania.'</td>
		<td align="center" class="texto8">'.$var_nom_und.'</td>
		<td align="center" class="texto8">'.$var_rif.'</td>
		<td align="center" class="texto8">'.fecha($var_fecha).'</td>
	</tr>
	<tr bgcolor="#CCCCCC">
		<td align="center"><strong>Monto Bs.F</strong></td>
		<td align="center"class="texto8"><strong>Requisición No.</strong></td>
		<td align="center" colspan=2 class="texto8"><strong>Fecha de Emisión</strong></td>
	</tr>
	<tr>
		<td align="center" width="200" class="texto8">'.$monto_orden.'</td>
		<td align="center" class="texto8">'.$var_cod_requisicion.'</td>
		<td align="center" colspan=2 class="texto8">'.fecha($var_fecha).'</td>
	</tr>
</table>
<table width="800" border="0" align="center" class="texto8">
  <tbody class="texto8">
	<tr>
		<td align="left" colspan="2" class="texto8"><strong>Concepto:<br> </strong>'.$var_concepto.'</td>
	</tr>
</tbody>
</table>
<br><br>
<table width="800" border="1" align="center">
	<tr bgcolor="#CCCCCC">
		<td  width="100" colspan="7" align="center" class="texto8"><strong>DETALLES MATERIALES</strong></td>
	</tr>
    <tr bgcolor="#CCCCCC">
        <td width="200" align="left" class="texto8"><strong>Descripción del Item</strong></td>
        <td width="70" align="center" class="texto8"><strong>Cantidad</strong></td>
        <td width="70" align="center" class="texto8"><strong>Unidad</strong></td>
		  <td width="115" align="right" class="texto8"><strong>Precio Unitario</strong></td>
		  <td width="115" align="right" class="texto8"><strong>Sub Total</strong></td>
		  <td width="115" align="right" class="texto8"><strong>I.V.A.</strong></td>
		  <td width="115" align="right" class="texto8"><strong>Total</strong></td>
    </tr>
  
</table>';
echo $datos_orden;
//return $codigo;
//$pie=pie_inzuvi();
?>

<?
}
function datos_partidas($id)
{
	echo '<table width="800" border="1" align="center">
	<tbody>
		<tr>
			<td bgcolor="#CCCCCC" colspan="7" align="center" class="texto8"><strong>PARTIDAS PRESUPUESTARIAS </strong></td>
		</tr>
    	<tr bgcolor="#CCCCCC">
          <td width="100" align="center" class="texto8"><strong>Programática</strong></td>
          <td width="100" align="center" class="texto8"><strong>Cuenta</strong></td>
          <td width="100" align="center" class="texto8"><strong>Ordinal</strong></td>
		  <td width="350" align="center" class="texto8"><strong>Descripción de la Cuenta</strong></td>
		  <td width="150" align="center" class="texto8"><strong>Monto Bs.F</strong></td>
    	</tr>
	</tbody>
	</table>';
	$conexion=conexion();
	$rs = query("SELECT * FROM cwpreejc where RecNoOrders = $id",$conexion);
   	while ($row_rs = fetch_array($rs)) 
	{ 
		$var_sector=$row_rs['Sector'];
		$var_programa=$row_rs['Programa'];
		$var_actividad=$row_rs['Actividad'];
		$var_partida=$row_rs['Partida'];
		$var_monto3=$row_rs['Monto'];
		$contador++;
		$rso = query("SELECT Denominacion FROM cwprecue where CodCue = '$var_partida'",$conexion);
		$row_rso = fetch_array($rso);
		$var_descripcion=$row_rso['Denominacion'];
		
	//}
	$monto_3  = number_format($var_monto3,2,',','.');
	
?>
<table width="800" border="0" align="center" cellpadding="1" cellspacing="1">
			<td width="100" align="center"><div align="center" class="texto8"><? echo $var_sector.".".$var_programa.".".$var_actividad; ?></div></td>
            <td width="100" align="center" class="texto8"><? echo $var_partida; ?></td>
            <td width="100" align="center" class="texto8"></td>
			<td width="350" align="left" class="texto8"><? echo $var_descripcion; ?></td>
			<td width="150" align="right" class="texto8"><? echo $monto_3; ?></td>	
<?	}//fin del while
}
$pie=pie_orden_inzuvi2();
?>
<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div id="impresion">
<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
echo $encabezado."<br><br>";
//imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows);
?>
<?

//$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
//echo $encabezado."<br><br>";
imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$var_nomemp,$var_rif);

//datos_de_orden($var_nomemp,$var_direccion,$var_fecha,$var_nom_und,$var_monto_orden,$var_codigo,$var_dias_credito);
?> 
<table width="800" border="0" align="center" cellpadding="2" cellspacing="2" class="">
<?
$cont=1;

		$rs = query("SELECT * FROM ordenes_detalles  WHERE cod_ord = $id",$conexion);
   	while ($row_rs = fetch_array($rs)) 
		{ 
	  	$var_cod_productos=$row_rs['cod_pro'];
		$rsu = query("SELECT unidad,descripcion FROM materiales WHERE cod_material = '$var_cod_productos' ",$conexion);
		$row_rsu = fetch_array($rsu);
		$var_unidad_materiales=$row_rsu['unidad'];
		$var_descripcion_materiales=$row_rsu['descripcion'];
		
		$rso = query("SELECT monto_excento,imponible,monto_orden,monto_iva FROM ordenes WHERE codigo = $id",$conexion);
		$row_rso = fetch_array($rso);
		$var_monto_excento=$row_rso['monto_excento'];
		$var_monto_imponible=$row_rso['imponible'];
		$var_monto_orden=$row_rso['monto_orden'];
		$var_monto_iva=$row_rso['monto_iva'];
		
		//echo "Imponible".$var_monto_imponible;

	  	$var_precio=$row_rs['precio'];
		$var_iva=$row_rs['iva'];
		$var_total=$row_rs['total'];
		$var_total_mat=$row_rs['total_gen'];

	  	//$rso->close();
		//$descripcion= $row_rs['descripcion'];
		$cantidad= $row_rs['cantidad_pedida'];

		$precio=number_format($var_precio,2,",",".");
		$total=number_format($var_total,2,",",".");
		$iva=number_format($var_iva,2,",",".");
		$total_mat=number_format($var_total_mat,2,",",".");
		
		$monto_excento=number_format($var_monto_excento,2,",",".");
		$monto_imponible=number_format($var_monto_imponible,2,",",".");
		$monto_orden=number_format($var_monto_orden,2,",",".");
		$monto_iva=number_format($var_monto_iva,2,",",".");
?>
	<tr>
         <td width="200" ><div align="left" class="texto8"><? echo $var_descripcion_materiales; ?></div></td>
         <td width="70" align="center" class="texto8"><? echo $cantidad; ?></td>
         <td width="70" align="center" class="texto8"><? echo $var_unidad_materiales; ?></td>
			<td width="115" align="right" class="texto8"><? echo $precio; ?></td>
			<td width="115" align="right" class="texto8"><? echo $total; ?></td>
			<td width="115" align="right" class="texto8"><? echo $iva; ?></td>
			<td width="115" align="right" class="texto8"><? echo $total_mat; ?></td>
	</tr>

<?
	if($cont==$cantidad_registros)
	{
	echo "</table>".$pie."<br class=\"saltopagina\">";
	
	echo $encabezado.'<br><br>';
	imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$var_nomemp,$var_rif);
	
	echo '<table width="800" border="0" align="center" cellpadding="1" cellspacing="1" class="">';
	$cont=1;

	}else{$cont++;}
}

if($cont<=$cantidad_registros){
	echo "</table>";

	echo '<table width="800" border="0" align="center" cellpadding="1" cellspacing="1" class="">
  <tbody>
    <tr>
      <td width="455" colspan="4"></td>
		<td width="115" align="right"></td>
      <td width="230" colspan="2"></td>
    </tr>
	<tr>
      <td width="270" colspan="2"></td>
      <td width="185" colspan="2" class="texto8" align="right">Sub-Total</td>
      <td width="115" align="right" class="texto8">'.$monto_imponible.'</td>
		<td width="230" colspan="2"></td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="2" class="texto8" align="right">Total I.V.A.</td>
      <td align="right" class="texto8">'.$monto_iva.'</td>
		<td colspan="2"></td>
    </tr>
	<tr>
      <td colspan="2"></td>
      <td colspan="2" class="texto8" align="right">Total Excento</td>
      <td align="right" class="texto8">'.$monto_excento.'</td>
		<td colspan="2"></td>
    </tr>
	<tr>
      <td colspan="2"></td>
      <td colspan="2"></td>
      <td class="texto8" align="right"></td>
		<td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="2" class="texto8" align="right"><strong>Total General  Bs.F</strong></td>
      <td align="right" class="texto8"><strong>'.$monto_orden.'</strong></td>
      <td colspan="2"></td>
    </tr>
	<TR><TD><br></TD></TR>
  </tbody>';
datos_partidas($id);
echo "</table>".$pie;
}// cierra el if
?>
<?  cerrar_conexion($conexion);?>
</div>
</body>
</html>

<?
//De aqui pa bajo arreglar...
?>
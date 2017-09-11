<?php 
if (!isset($_SESSION)) {
  session_start();
}
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$cantidad_registros=25;
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

<?php 
	  	
	$conexion=conexion();
  	$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro']; 		
  	$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
  	//echo "ID".$id;
	$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];
			
	$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
 	$rs = query($consulta_req,$conexion);
	
	$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
	$pagina=obtener_pagina_actual($pagina,$num_paginas);
  
function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows)
{

	$conexion=conexion();
	$var_sql="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,r.estacion,
	r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro FROM requisiciones r,centros c WHERE r.cod_requisicion=$id and r.cod_centro=c.cod_centro";
	$rs = query($var_sql,$conexion);
	$row_rs = fetch_array($rs);
	$var_fecha=$row_rs['fecha'];
	$var_nom_centro=$row_rs['des_centro'];
	$var_des=$row_rs['descripcion'];
	$var_unidad=$row_rs['unidad'];
	$var_centro=$row_rs['cod_centro'];
	$var_cod_requisicion=$row_rs['cod_requisicion'];
		
	$var_sql="SELECT descripcion FROM unidades WHERE cod_unidad=$var_unidad";
	$rsu = query($var_sql,$conexion);		
	$row_rsu = fetch_array($rsu);
	$var_nom_und=$row_rsu['descripcion'];
	
	$rso = query("SELECT monto_orden,dias_credito,imponible,monto_iva,monto_excento,funcion,codigo,concepto,cod_provee,tipo,estado,codigo_ref,fecha FROM ordenes  WHERE cod_requi = $id and estado <> 'Anulado'",$conexion);
	while ($row_rso = fetch_array($rso)) 
	{ 
		$var_monto_orden=$row_rso['monto_orden'];
		$var_dias_credito=$row_rso['dias_credito'];
		$var_imponible=$row_rso['imponible'];
		$var_monto_iva=$row_rso['monto_iva'];
		$var_monto_excento=$row_rso['monto_excento'];
		$var_fecha=$row_rso['fecha'];
		$var_funcion=$row_rso['funcion'];
		$var_codigo=$row_rso['codigo_ref'];
		$var_tipo=$row_rso['tipo'];
		$var_estado=$row_rso['estado'];
		$var_cod_proveedor=$row_rso['cod_provee'];
		
		$var_sqlu="SELECT compania,rif FROM proveedores WHERE cod_proveedor=$var_cod_proveedor";
		$rsu = query($var_sqlu,$conexion);		
		$row_rsu = fetch_array($rsu);
		$var_compania=$row_rsu['compania'];
		$var_rif=$row_rsu['rif'];
		
	}
 	    
	//echo "Tipo".$var_tipo;
	$rs = query("SELECT codigo,concepto FROM ordenes where cod_requi = '$id' and estado <> 'Anulado'",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_concepto=$row_rs['concepto'];
		$codigo=$row_rs['codigo'];	
	}
	 
	$rs = query("select descripcion from ordenes_tipos where cod_orden_tipo = '$var_tipo' ",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{	
		//echo "Pase por aqui";	
		$var_nom_tipo=$row_rs['descripcion'];
	}
		//echo "Tipo de orden".$x_cod_orden_tipo_req;

$monto_orden  = number_format($var_monto_orden,2,',','.');

$datos_orden='<br>
<table width="800" border="0" align="center">
	<tr>
		<td align="center" colspan=3 class="texto14"><strong>ORDEN DE SERVICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N° '.$codigo.'</strong></td>
	</tr>
	<tr>
		<td width="600"></td>
		<td width="100" align="right" class="texto10"><strong>Pág.: '.$pagina.'/'.$num_paginas.'</strong></td>
		<td width="100"></td>
	</tr>
</table>
 
<table width="800" border="0" align="center" class="texto10">
  <tbody class="texto10">
	<tr>
      <td width="580"  align="left" class="texto10"><strong>Señores: </strong>'.$var_compania.'</td>
      <td width="220"  align="left" class="texto10"><strong>Fecha de Emisión: </strong>'.fecha($var_fecha).'</td>
    </tr>
	<tr>
		<td  ></td>
		<td  align="left" class="texto10"><strong>Unidad Solicitante:</strong></td>
	</tr>
	<tr>
		<td ></td>
		<td align="left" class="texto10">'.$var_nom_und.'</td>
	</tr>
	<tr>
		<td  align="left" class="texto10"><strong>R.I.F.: </strong>'.$var_rif.'</td>
		<td  align="left" class="texto10"><strong>Monto Bs.F: </strong>'.$monto_orden.'</td>
	</tr>
	<tr>
		<td align="left" class="texto10"><strong>Presente.-</strong></td>
		<td align="left" class="texto10"><strong>Requisición Nº: </strong>'.$id.'</td>
	</tr>
	<tr>
		<td align="left" colspan="2" class="texto10"><strong>Descripción: </strong>'.$var_concepto.'</td>
	</tr>
</tbody>
</table>
<br><br>
<table width="800" border="0" align="center">
	<tr>
		<td  width="100" colspan="7" align="center" class="texto10"><strong>DETALLES DEL SERVICIO</strong></td>
	</tr>
    <tr>
          <td width="200" align="left" class="texto10"><strong>Descripción del Item</strong></td>
          <td width="70" align="center" class="texto10"><strong>Cantidad</strong></td>
          <td width="70" align="center" class="texto10"><strong>Unidad</strong></td>
		  <td width="115" align="right" class="texto10"><strong>Precio Unitario</strong></td>
		  <td width="115" align="right" class="texto10"><strong>Sub Total</strong></td>
		  <td width="115" align="right" class="texto10"><strong>I.V.A.</strong></td>
		  <td width="115" align="right" class="texto10"><strong>Total</strong></td>
    </tr>
  
</table>';
echo $datos_orden;
return $codigo;
}
function datos_partidas($codigo)
{
	//echo $codigo;	
	//echo "<br>";
	echo '<table width="800" border="0" align="center">
	<tbody>
		<tr>
			<td width="100" colspan="7" align="center" class="texto10"><strong>PARTIDAS PRESUPUESTARIAS </strong></td>
		</tr>
    	<tr>
          <td width="100" align="left" class="texto10"><strong>Programática</strong></td>
          <td width="100" align="center" class="texto10"><strong>Cuenta</strong></td>
          <td width="100" align="center" class="texto10"><strong>Ordinal</strong></td>
		  <td width="350" align="center" class="texto10"><strong>Descripción de la Cuenta</strong></td>
		  <td width="150" align="center" class="texto10"><strong>Monto Bs.</strong></td>
    	</tr>
	</tbody>
	</table>';
	$conexion=conexion();
	$rs = query("SELECT * FROM cwpreejc where RecNoOrders = $codigo",$conexion);
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
<table width="800" border="0" align="center" cellpadding="2" cellspacing="2" class="">
			<td width="100" ><div align="left" class="texto8"><? echo $var_sector.".".$var_programa.".".$var_actividad; ?></div></td>
            <td width="100" align="center" class="texto8"><? echo $var_partida; ?></td>
            <td width="100" align="center" class="texto8"></td>
			<td width="350" align="left" class="texto8"><? echo $var_descripcion; ?></td>
			<td width="150" align="center" class="texto8"><? echo $monto_3; ?></td>	
<?	}//fin del while
}
$pie=pie_orden_inzuvi();
?>
<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div id="impresion">
<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
echo $encabezado."<br><br>";
//imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows);

//$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
//echo $encabezado."<br><br>";
$codigo=imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_nomemp,$var_rif);

//datos_de_orden($var_nomemp,$var_direccion,$var_fecha,$var_nom_und,$var_monto_orden,$var_codigo,$var_dias_credito);
?> 
<table width="800" border="0" align="center" cellpadding="2" cellspacing="2" class="">
<?
$cont=1;

	$rs = query("SELECT * FROM requisiciones_det  WHERE cod_requisicion = $id  ORDER BY cod_requisicion_det ",$conexion);
   	while ($row_rs = fetch_array($rs)) 
	{ 
	  	$var_cod_productos=$row_rs['cod_material'];
		$rsu = query("SELECT unidad FROM materiales WHERE cod_material = '$var_cod_productos' ",$conexion);
		$row_rsu = fetch_array($rsu);
		$var_unidad_materiales=$row_rsu['unidad'];
		//$var_descripcion_materiales=$row_rsu['descripcion'];
		
		$rso = query("SELECT monto_excento FROM ordenes WHERE cod_requi = $id and cod_produ='$var_cod_productos'",$conexion);
		$row_rso = fetch_array($rso);
		$var_monto_excento=$row_rso['monto_excento'];
		//$var_monto_imponible=$row_rso['impobible'];
		//echo "Imponible".$var_monto_imponible;
	  	$rso = query("SELECT precio,iva,total,total_gen FROM ordenes_detalles WHERE cod_requisicion = $id and cod_pro='$var_cod_productos'",$conexion);
   	  	while ($row_rso = fetch_array($rso)) 
	  	{ 
	  		$var_precio=$row_rso['precio'];
			$var_iva=$row_rso['iva'];
			$var_total=$row_rso['total'];
			$var_total_gen=$row_rso['total_gen'];
	  	}
	  	//$rso->close();
		$descripcion= $row_rs['descripcion'];
		$cantidad= $row_rs['cantidad'];

		$total=$var_total+$var_iva;
		$total_iva=$total_iva+$var_iva;
		$sub_total=$sub_total+$var_total;
		
		$var_total_float  = ((real) $total);		
		$var_total_float_format  = number_format($var_total_float,2,',','.');		
		$var_total_float_format  = ((string)$var_total_float_format);
		
		$var_precio_float  = ((real) $var_precio);		
		$precio_float_format  = number_format($var_precio_float,2,',','.');		
		$precio_float_format  = ((string)$precio_float_format);

		//$var_iva_float  = ((real) $var_iva);		
		//echo $var_iva;
		$iva_float_format  = number_format($var_iva,2,',','.');		
		//$iva_float_format  = ((string)$iva_float_format);
		
		$var_monto_float  = ((real) $var_total);		
		$monto_float_format  = number_format($var_monto_float,2,',','.');		
		$monto_float_format  = ((string)$monto_float_format);

		//$iva_float  = ((real) $total_iva);		
		$iva_total_float_format  = number_format($total_iva,2,',','.');		
		//$iva_total_float_format  = ((string)$iva_total_float_format);

		$sub_total_float  = ((real) $sub_total);		
		$sub_total_float_format  = number_format($sub_total_float,2,',','.');		
		$sub_total_float_format  = ((string)$sub_total_float_format);

		$excento_float_format  = number_format($var_monto_excento,2,',','.');
 
		$total_float  = ((real) $var_total_gen);		
		$total_float_format  = number_format($total_float,2,',','.');		
		$total_float_format  = ((string)$total_float_format);

?>
	<tr class="tb-bg-in">
    	<form method="post" id="form<? echo $row_rs['id_foto'] ?>" name="form<? echo $row_rs['id_foto'] ?>" action="<? echo $filename ?>?rsac=edit&amp;id=<? echo $row_rs['id_foto'] ?>">
            <td width="200" ><div align="left" class="texto8"><? echo $descripcion; ?></div></td>
            <td width="70" align="center" class="texto8"><? echo $cantidad; ?></td>
            <td width="70" align="center" class="texto8"><? echo $var_unidad_materiales; ?></td>
			<td width="115" align="right" class="texto8"><? echo $precio_float_format; ?></td>
			<td width="115" align="right" class="texto8"><? echo $monto_float_format; ?></td>
			<td width="115" align="right" class="texto8"><? echo $iva_float_format; ?></td>
			<td width="115" align="right" class="texto8"><? echo $var_total_float_format; ?></td>
            <!--    <td width="16"><a href="javascript:;" onclick="confirmar('Seguro de Borrar?','<? echo $filename ?>?rsac=del&amp;id=<? echo $id; ?>'); return self.rValue"><img src="img_sis/ico_basket.gif" alt="Borrar" width="15" height="15" border="0" /></a></td> -->
         </form>
	</tr>

<?
	if($cont==$cantidad_registros)
	{
	echo "</table>".$pie."<br class=\"saltopagina\">";
		

	//echo $encabezado.'<br><br>';
	imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_nomemp,$var_rif);
	
	echo '<table width="1100" border="0" align="center" cellpadding="2" cellspacing="2" class="">';
	$cont=1;

	}else{$cont++;}
}

if($cont<=$cantidad_registros){
	echo "</table>";

	echo '<table width="800" border="0" align="center" cellpadding="2" cellspacing="2" class="">
  <tbody>
    <tr>
      <td width="455" colspan="4"></td>
		<td width="115" align="right" ><hr></td>
      <td width="230" colspan="2"></td>
    </tr>
	<tr>
      <td width="270" colspan="2"></td>
      <td width="185" colspan="2" class="texto10">Sub-Total ========></td>
      <td width="115" align="right" class="texto8">'.$sub_total_float_format.'</td>
		<td width="230" colspan="2"></td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="2" class="texto10">Total I.V.A. =======></td>
      <td align="right" class="texto8">'.$iva_total_float_format.'</td>
		<td width="230" colspan="2"></td>
    </tr>
	<tr>
      <td colspan="2"></td>
      <td colspan="2" class="texto10">Total Excento =====></td>
      <td align="right" class="texto8">'.$excento_float_format.'</td>
		<td width="230" colspan="2"></td>
    </tr>
	<tr>
      <td colspan="2"></td>
      <td colspan="2"></td>
      <td class="texto10" align="right" >==============</td>
		<td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="2" class="texto10">Total General =====></td>
      <td align="right" class="texto10">'.$total_float_format.'</td>
      <td colspan="2"></td>
    </tr>
  </tbody>
</table>';

datos_partidas($codigo);
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




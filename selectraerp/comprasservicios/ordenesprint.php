<?php 
require_once '../lib/config.php';
require_once '../lib/common.php';
$cantidad_registros=20;	
$Conn = conexion_conf();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
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
		$var_rif=$row_rsu['rif'];
				
cerrar_conexion($Conn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script language="JavaScript" type="text/javascript">
  //window.print();
</script>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<title>Reporte de Ordenes de Compra</title>
  <link href="../estilos.css" rel="stylesheet   " type="text/css">
  <style type="text/css">
<!--
.Estilo3 {font-size: 14px}
-->
  </style>
</head>
<body>

<?php 
	  	
	$conexion=conexion();
  	$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro']; 		
  	$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
  	//echo "ID".$id;
	$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

	$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
 	$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
	$pagina=obtener_pagina_actual($pagina,$num_paginas);

function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows,$var_nomemp,$var_rif)
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
	
	$rso = query("SELECT monto_orden,dias_credito,imponible,monto_iva,monto_excento,funcion,cod_provee,codigo,concepto,tipo,estado,codigo_ref FROM ordenes  WHERE cod_requi = $id and estado <> 'Anulado'",$conexion);
	while ($row_rso = fetch_array($rso)) 
	{ 
		$var_monto_orden=$row_rso['monto_orden'];
		$var_dias_credito=$row_rso['dias_credito'];
		$var_imponible=$row_rso['imponible'];
		$var_monto_iva=$row_rso['monto_iva'];
		$var_monto_excento=$row_rso['monto_excento'];
		$var_funcion=$row_rso['funcion'];
		$var_codigo=$row_rso['codigo_ref'];
		$var_tipo=$row_rso['tipo'];
		$var_cod_proveedor=$row_rso['cod_provee'];
		$var_estado=$row_rso['estado'];
		
		$var_sql="SELECT compania,direccion1 FROM proveedores WHERE cod_proveedor=$var_cod_proveedor";
		$rsu = query($var_sql,$conexion);
		$row_rsu = fetch_array($rsu);
		$var_compania=$row_rsu['compania'];
		$var_direccion=$row_rsu['direccion1'];
	
	}
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
	$monto_orden_float  = ((real) $var_monto_orden);		
	$monto_orden_float_format  = number_format($monto_orden_float,2,',','.');
	$monto_orden_float_format  = ((string)$monto_orden_float_format);

$datos_orden='<table width="700" border="0" align="center">
  <tbody>
    <tr>
	  <td width="100" colspan="1" align="left"><h3><strong>'.$var_rif.'</strong></h3></td>
	  <td width="100" colspan="4" align="center"><h3><strong>Orden De '.$var_nom_tipo.' N° '.$id.' </strong></h3></td>
      <td width="100" align="right" colspan="1"><h3><strong>Pag: '.$pagina.'/'.$num_paginas.'</strong></h3></td>
    </tr>
	<TR>
   		<TD colspan="6"><br></TD>
 	</TR>
    <tr>
      <td width="100" colspan="4" rowspan="2" align="left"><strong>Señores: </strong>'.$var_compania.'</td>
      <td width="150" colspan="2" align="left"><strong>Fecha De Emisión: </strong>'.fecha($var_fecha).'</td>
    </tr>
	<tr>
		<td width="150" colspan="2" align="left"><strong>Unidad Solicitante:</strong></td>
	</tr>
	<tr>
		<td width="100" colspan="4"></td>
		<td width="150" colspan="2" align="left">'.$var_nom_und.'</td>
	</tr>
	<tr>
		<td width="100" colspan="4"  rowspan="2" align="left"><strong>Dirección: </strong>'.$var_direccion.'</td>
		<td width="150" colspan="2" align="left"><strong>Monto Bs: </strong>'.$monto_orden_float_format.'</td>
	</tr>
	<tr>
		<td width="150" colspan="2" align="left"><strong>Nro de Control: </strong>'.$id.'</td>
	</tr>
	<tr>
		<td width="100" colspan="4" align="left"><strong>Presente.-</strong></td>
		<td width="150" colspan="2" align="left"><strong>Días de Credito: </strong>'.$var_dias_credito.'</td>
	</tr>
	<TR>
   		<TD colspan="6"><br></TD>
 	</TR>

	<tr>
		<td width="100" colspan="6" align="left"><strong>Descripción: </strong></td>
	</tr>
	<tr>
		<td width="150" colspan="6" align="left">'.$var_concepto.'</td>
	</tr>

	<TR>
   		<TD colspan="6"><br></TD>
 	</TR>
	<tr>
		<td width="100" colspan="6" align="center"><strong>DETALLES MATERIALES</strong></td>
	</tr>
    <tr style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
          <td width="225" align="center"><strong>Materiales</strong></td>
          <td width="75" ><strong>Cantidad</strong></td>
          <td width="75" align="center"><strong>Unidad</strong></td>
		  <td width="125" align="center"><strong>Precio Unit.</strong></td>
		  <td width="75" align="center"><strong>I.V.A.</strong></td>
		  <td width="125" align="center"><strong>Total</strong></td>
    </tr>
  </tbody>
</table>';
echo $datos_orden;
return $codigo;
}
	
function datos_partidas($codigo)
{
	echo "<br>";
	$conexion=conexion();
	$rs = query("SELECT * FROM cwpreejc where RecNoOrders = $codigo",$conexion);
   	while ($row_rs = fetch_array($rs)) 
	{ 
		$var_sector=$row_rs['Sector'];
		$var_programa=$row_rs['Programa'];
		$var_actividad=$row_rs['Actividad'];
		$var_partida=$row_rs['Partida'];
		$var_monto3=$row_rs['Monto'];
		$rso = query("SELECT Denominacion FROM cwprecue where CodCue = '$var_partida'",$conexion);
		$row_rso = fetch_array($rso);
		$var_descripcion=$row_rso['Denominacion'];
   		
	}
	echo '<table width="700" border="0" align="center">
<tbody>
		<tr>
			<td width="100" colspan="6" align="center"><strong>PARTIDAS PRESUPUESTARIAS 2007</strong></td>
		</tr>
    
		<tr style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
          <td width="100" align="center"><strong>Programatica</strong></td>
          <td width="100" ><strong>Cuenta</strong></td>
          <td width="50" align="center"><strong>Ord</strong></td>
		  <td width="350" align="center"><strong>Descripción de la Cuenta</strong></td>
		  <td width="100" align="center"><strong>Monto Bs.</strong></td>
    	</tr>
	</tbody>
	</table>';
?>
<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class="">
			<td width="100" ><div align="left"><? echo $var_sector.".".$var_programa.".".$var_actividad; ?></div></td>
            <td width="100" align="center"><? echo $var_partida; ?></td>
            <td width="50" align="center"></td>
			<td width="350" align="center"><? echo $var_descripcion; ?></td>
			<td width="100" align="center"><? echo $var_monto3; ?></td>	
<?
}
$pie=pie();
?>
<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div id="impresion">
<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
echo $encabezado."<br><br>";
$codigo=imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_nomemp,$var_rif);

//datos_de_orden($var_nomemp,$var_direccion,$var_fecha,$var_nom_und,$var_monto_orden,$var_codigo,$var_dias_credito);
?> 
<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class="">
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

		$total_iva=$total_iva+$var_iva;
		$sub_total=$sub_total+$var_total;

		$var_precio_float  = ((real) $var_precio);		
		$precio_float_format  = number_format($var_precio_float,2,',','.');		
		$precio_float_format  = ((string)$precio_float_format);

		$var_iva_float  = ((real) $var_iva);		
		$iva_float_format  = number_format($var_iva_float,2,',','.');		
		$iva_float_format  = ((string)$iva_float_format);
		
		$var_monto_float  = ((real) $var_total);		
		$monto_float_format  = number_format($var_monto_float,2,',','.');		
		$monto_float_format  = ((string)$monto_float_format);

		$iva_float  = ((real) $total_iva);		
		$iva_total_float_format  = number_format($iva_float,2,',','.');		
		$iva_total_float_format  = ((string)$iva_total_float_format);

		$sub_total_float  = ((real) $sub_total);		
		$sub_total_float_format  = number_format($sub_total_float,2,',','.');		
		$sub_total_float_format  = ((string)$sub_total_float_format);
		
		$exento_float  = ((real) $var_monto_excento);		
		$exento_float_format  = number_format($exento_float,2,',','.');		
		$exento_float_format  = ((string)$exento_float_format);
		
		$total_orden = $total_iva+$sub_total;
		$total_float  = ((real) $total_orden);		
		$total_float_format  = number_format($total_float,2,',','.');		
		$total_float_format  = ((string)$total_float_format);

?>
	<tr class="tb-bg-in">
    	<form method="post" id="form<? echo $row_rs['id_foto'] ?>" name="form<? echo $row_rs['id_foto'] ?>" action="<? echo $filename ?>?rsac=edit&amp;id=<? echo $row_rs['id_foto'] ?>">
            <td width="225" ><div align="left"><? echo $descripcion; ?></div></td>
            <td width="75" align="center"><? echo $cantidad; ?></td>
            <td width="75" align="center"><? echo $var_unidad_materiales; ?></td>
			<td width="125" align="center"><? echo $precio_float_format; ?></td>
			<td width="75" align="center"><? echo $iva_float_format; ?></td>
			<td width="125" align="right"><? echo $monto_float_format; ?></td>
            <!--    <td width="16"><a href="javascript:;" onclick="confirmar('Seguro de Borrar?','<? echo $filename ?>?rsac=del&amp;id=<? echo $id; ?>'); return self.rValue"><img src="img_sis/ico_basket.gif" alt="Borrar" width="15" height="15" border="0" /></a></td> -->
         </form>
	</tr>

<?
	if($cont==$cantidad_registros)
	{
	echo "</table>".$pie."<br class=\"saltopagina\">";
		

	echo $encabezado.'<br><br>';
	imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_nomemp,$var_rif);
	
	echo '<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class="">';
	$cont=1;

	}else{$cont++;}
}

if($cont<=$cantidad_registros){
	echo "</table>";

	echo '<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class="">
  <tbody>
    <tr>
      <td width="200"></td>
      <td width="50"></td>
      <td width="50"></td>
      <td width="25"></td>
	  <td width="25"></td>
      <td width="175" align="right"></td>
      <td width="175" align="right">________________</td>
    </tr>
	<tr>
      <td width="200"></td>
      <td width="50"></td>
      <td width="50"></td>
      <td width="25"></td>
	  <td width="25"></td>
      <td width="175" align="right">Sub-Total ==></td>
      <td width="175" align="right">'.$sub_total_float_format.'</td>
    </tr>
    <tr>
      <td width="100"></td>
      <td width="100"></td>
      <td width="100"></td>
      <td width="25"></td>
	  <td width="25"></td>
      <td width="175" align="right">Total I.V.A. ==></td>
      <td width="175" align="right">'.$iva_total_float_format.'</td>
    </tr>
	<tr>
      <td width="100"></td>
      <td width="100"></td>
      <td width="100"></td>
      <td width="25"></td>
	  <td width="25"></td>
      <td width="175" align="right">Total Exento ==></td>
      <td width="175" align="right">'.$exento_float_format.'</td>
    </tr>

	<tr>
      <td width="200"></td>
      <td width="50"></td>
      <td width="50"></td>
      <td width="25"></td>
	  <td width="25"></td>
      <td width="175" align="right"></td>
      <td width="175" align="right">===========</td>
    </tr>
    <tr>
      <td width="200"></td>
      <td width="50"></td>
      <td width="50"></td>
      <td width="25"></td>
	  <td width="25"></td>
      <td width="175" align="right">Total General ==></td>
      <td width="175" align="right">'.$total_float_format.'</td>
    </tr>
  </tbody>
</table>';
datos_partidas($codigo);
echo "</table>".$pie;
}// cierra el if 
?>
<?  cerrar_conexion($conexion);?>
</div>
</div>
</body>
</html>


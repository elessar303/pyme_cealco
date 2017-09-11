<?
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php"); // archivo que llama a la base de datos
$Conn = conexion_conf(); //new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
$cantidad_registros=20;
$var_sql="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der from parametros";
$rs = query($var_sql,$Conn);
$row_rs = fetch_array($rs);
$var_encabezado1=$row_rs['encabezado1'];
$var_encabezado2=$row_rs['encabezado2'];
$var_encabezado3=$row_rs['encabezado3'];
$var_encabezado4=$row_rs['encabezado4'];
$var_imagen_izq=$row_rs['imagen_izq'];
$var_imagen_der=$row_rs['imagen_der'];		
cerrar_conexion($Conn);

$conexion=conexion();

$var_tipo_orden=$_GET['cod_orden_tipo'];
$var_proveedor=$_GET['proveedor'];
$var_estado=$_GET['estado'];
$var_centro=$_GET['centro'];
$var_bandera = false;

if (isset($var_proveedor) && isset($var_tipo_orden) && $var_proveedor<>'')
{
	if ($var_proveedor<>'0' && $var_proveedor<>'')
	{
		$rs = query("SELECT cod_proveedor,compania FROM proveedores  where cod_proveedor = '$var_proveedor' ",$conexion);
		while ($row_rs = fetch_array($rs)) 
		{
			$var_proveedor=$row_rs['compania'];
		}
		//$rs->close();
		if ($var_estado=='Todas')
		{
			$var_sql="select * from ordenes where cod_provee = '$var_proveedor' and tipo= '$var_tipo_orden' order by codigo_ref";			
		}
		else
		{
			$var_sql="select * from ordenes where cod_provee = '$var_proveedor' and tipo= '$var_tipo_orden' and estado = '$var_estado' order by codigo_ref";			
		}
	}
	else
	{
		if ($var_estado=='Todas')
		{
			$var_sql="select * from ordenes  where tipo= '$var_tipo_orden' order by codigo";
		}
		else
		{
			$var_sql="select * from ordenes  where estado = '$var_estado' and tipo= '$var_tipo_orden' order by codigo";
		}
		$var_proveedor="TODOS";
	}	
}
else
{
	if ($var_estado=='Todas' && $var_centro=='0')
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne   order by codigo";		
		$var_bandera=true;
	}
	if ($var_estado=='Todas' && $var_centro<>'0')
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne  where centro_costo = '$var_centro' order by codigo";		
		$var_bandera=true;
	}
	if ($var_estado<>'Todas' && $var_centro=='0')
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne where estado = '$var_estado' order by codigo";		
		$var_bandera=true;
	}
	if ($var_estado<>'Todas' && $var_centro<>'0')
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne where estado = '$var_estado' and centro_costo = '$var_centro' order by codigo";		
		$var_bandera=true;
	}
}

?>
<script language="JavaScript" type="text/javascript" src="../lib/common.js">
 // window.print();
</script>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<title>Reporte de Ordenes de Compra</title>
  <link href="../estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
<!--
.Estilo1 {
	font-size: 14px;
	font-weight: bold;
}
-->
  </style>
<body>
<?

$rs = query($var_sql,$conexion);
$num_paginas=obtener_num_paginas($var_sql,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);


function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$centro,$tipo_orden,$situacion){
		$conexion=conexion();		
		//echo "CodCentro: ".$cod_centro." Centro: ".$centro;
		$rso = query($var_sql,$conexion);
   	  	while($row_rso = fetch_array($rso))
		{ 
	  		$var_codigo=$row_rso['cod_requi'];
			$var_fecha=fecha($row_rso['fecha']);
			$var_unidad=$row_rso['unidad'];
			$var_centro_costo=$row_rso['centro_costo'];
			$var_situacion=$row_rso['situacion'];
			$var_cod_provee=$row_rso['cod_provee'];
			$var_cod_requi=$row_rso['cod_requi'];
			$var_tipo=$row_rso['tipo'];
			$var_estado=$row_rso['estado'];
		}	
		//echo "Cod_Req: ".$var_codigo."<br> Consulta".$var_sql;
		$rs = query("SELECT descripcion FROM centros where cod_centro = '$var_centro_costo'",$conexion);
		
		while ($row_rs = fetch_array($rs)) 
		{		
			$var_nom_centro=$row_rs['descripcion'];		
		}
		//echo "Nomcentro".$var_nom_centro;
		//exit(0);	
		$var_sql="SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = '".$var_tipo."' ";
		$rsu = query($var_sql,$conexion);		
		$row_rsu = fetch_array($rsu);
		$var_nom_tipo=$row_rsu['descripcion'];		
		//echo "Centro".$centro;
		if($cod_centro==0){$var_centro_costo="TODOS";}else {$var_centro_costo=$var_centro_costo." - ".$var_nom_centro;} 

		if($tipo_orden<>"0"){$var_nom_orden=$var_nom_tipo;}else{$var_nom_orden="TODOS";}

		if ($situacion =="Todas"){$var_estado="Todas";}else{$var_estado=$situacion;} 
	
$datos_requisicion='<table width="700" border="0"  align="center">
  <tbody>
    <tr>
	  <td width="500" colspan="4" align="center"><h3><strong>LISTADO DE ORDENES DE '.$var_nom_tipo.' </strong></h3></td>
      <td width="200" align="right" colspan="2"><h3><strong>Pag: '.$pagina.'/'.$num_paginas.'</strong></h3></td>
    </tr>
<TR>
   <TD colspan="6"><br></TD>
 </TR>
    <tr>
      <td width="200" colspan="2"><p align="center"><strong>Centros de Costos:</strong></p>
          <p align="center">'.$var_centro_costo.'</p></td>
      <td width="250" colspan="2"><p align="center"><strong>Tipo de Requisición</strong></p>
          <p align="center">'.$var_nom_orden.'</p></td>
      <td width="250" colspan="2"><p align="center"><strong>Estado</strong></p>
          <p align="center">'.$var_estado.'</p></td>
    </tr>
	<TR>
   <TD colspan="6"><br></TD>
 </TR>

<TR>
   <TD colspan="6"><br></TD>
 </TR>
    <tr style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
        <td width="90" align="center"><strong>Numero de Control</strong></td>  
	<td width="110" align="center"><strong>Numero</strong></td>
        <td width="100" align="center"><strong>Fecha</strong></td>
        <td width="200" align="center"><strong>Proveedor</strong></td>
	<td width="100" align="center"><strong>Estado</strong></td>
	<td width="90" align="center"><strong>Monto Orden</strong></td>
	
        </tr>
  </tbody>
</table>';

echo $datos_requisicion;

}
$pie=pie();
?>

<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onClick="javascript:imprimir('impresion')"></div>
<div id="impresion">
<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
echo $encabezado."<br><br>";
imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$var_centro,$var_tipo_orden,$var_estado);
?>

<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class="">
<?
$cont=1;
$total=0;
while ($row_rs = fetch_array($rs)) 
{ 
	//$var_unidad=$row_rs['unidad'];
	$rso = query("SELECT compania FROM proveedores where cod_proveedor = '".$row_rs['cod_provee']."'", $conexion);
	$row_rso = fetch_array($rso); 
	$var_nom_compania=$row_rso['compania'];		
	$var_centro=$row_rs['centro_costo'];
	$rsu = query("SELECT descripcion FROM centros where cod_centro = '".$var_centro."'",$conexion);
	$row_rsu = fetch_array($rsu); 
	$var_nom_centro=$row_rsu['descripcion'];		
	$var_tipo=$row_rs['tipo'];
	$rsa = query("SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = $var_tipo",$conexion);		
	$row_rsa = fetch_array($rsa);
	$var_nom_tipo=$row_rsa['descripcion'];
		
?>
	<tr class="tb-bg-in">
    	<form method="post" id="form<? echo $row_rs['id_foto'] ?>" name="form<? echo $row_rs['id_foto'] ?>" action="<? echo $filename ?>?rsac=edit&amp;id=<? echo $row_rs['id_foto'] ?>">
	<td width="90" align="center"><? echo $row_rs['codigo_ref']; ?></td>
	<td width="110" ><div align="center"><? echo $row_rs['codigo']; ?></div></td>
        <td width="100" align="center"><? echo fecha($row_rs['fecha']); ?></td>
        <td width="200" align="center"><? echo $var_nom_compania; ?></td>
	<td width="100" align="center"><? echo $row_rs['estado']; ?></td>
	<td width="90" align="right"><? echo number_format($row_rs['monto_orden'],2,',','.'); ?> &nbsp;</td> <? $total = $total+$row_rs['monto_orden']; ?>
	
            <!--    <td width="16"><a href="javascript:;" onclick="confirmar('Seguro de Borrar?','<? echo $filename ?>?rsac=del&amp;id=<? echo $id; ?>'); return self.rValue"><img src="img_sis/ico_basket.gif" alt="Borrar" width="15" height="15" border="0" /></a></td> -->
         </form>
	</tr>
<?
	if($cont==$cantidad_registros)
	{
	//	echo "</table>".$pie."<br class=\"saltopagina\">";
		echo "</table><br><br><br><br><br><br><br><br><br><br><br>";	
		echo $encabezado.'<br><br>';
		imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$var_centro,$var_tipo_orden,$var_estado);

		echo '<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class="">';
		$cont=1;

	}
	else
	{
	$cont++;
	}
}

if($cont<=$cantidad_registros){
	echo " <tr height='40'><td colspan='6' align='right'  >Monto total:&nbsp;&nbsp;&nbsp; ".number_format($total,2,',','.')."&nbsp;</td></tr> ";
	echo "</table>";
//.$pie;&nbsp;
}// cierra el if 
?>
<?  cerrar_conexion($conexion);?>

</div>

</body>
</html>
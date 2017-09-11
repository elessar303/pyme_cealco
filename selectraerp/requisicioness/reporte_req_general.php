<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include('../header.php');
$cantidad_registros=15;
//$Conn=conexion_conf();
$conex=conexion_conf(); 

	$consulta="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der from parametros";
	$rs = query($consulta,$conex);
	$row_rs = fetch_array($rs);
	$var_encabezado1=$row_rs['encabezado1'];
	$var_encabezado2=$row_rs['encabezado2'];
	$var_encabezado3=$row_rs['encabezado3'];
	$var_encabezado4=$row_rs['encabezado4'];
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];	
	//$var_sql="select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros";
	//$rsu = query($var_sql,$Conn);		
	//$row_rsu = fetch_array($rsu);
	//$var_nomemp=$row_rsu['nomemp'];
		

	cerrar_conexion($conex);

$conexion=conexion();
$var_tipo_orden=$_GET[tipo_orden];
$var_estado=$_GET[estado];
$var_centro=$_GET[centro];	

if ($var_centro<>'0')
{
	$rs = query("SELECT descripcion FROM centros where cod_centro = '".$var_centro."'",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_centro=$row_rs['descripcion'];		
	}
	//$rs->close();
}

if ($var_tipo_orden<>'TODOS')
{
	$rs = query("SELECT descripcion as nom_orden FROM ordenes_tipos where cod_orden_tipo = '".$var_tipo_orden."'",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_orden=$row_rs['nom_orden'];		
	}
	//$rs->close();
}
	  

if ($var_tipo_orden<>"0" && $var_estado=="Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where tipo = '$var_tipo_orden' order by cod_requisicion";	
}
if ($var_tipo_orden<>"0" && $var_estado<>"Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' and tipo = '$var_tipo_orden' order by cod_requisicion";	
}
if ($var_tipo_orden<>"0" && $var_estado<>"Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' and situacion = '$var_estado' and tipo = '$var_tipo_orden' order by cod_requisicion";	
}

if ($var_tipo_orden<>"0" && $var_estado=="Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' and tipo = '$var_tipo_orden' order by cod_requisicion";
}

if ($var_tipo_orden=="0" && $var_estado=="Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones order by cod_requisicion";
}

if ($var_tipo_orden=="0" && $var_estado=="Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' order by cod_requisicion";		
}

if ($var_tipo_orden=="0" && $var_estado<>"Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' order by cod_requisicion";		
}

if ($var_tipo_orden=="0" && $var_estado<>"Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' and cod_centro = '$var_centro' order by cod_requisicion";		
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script language="JavaScript" type="text/javascript" src="../lib/common.js">
 // window.print();
</script>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<title>Reporte de Requisición</title>
  <link href="../estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
<!--
.Estilo1 {
	font-size: 14px;
	font-weight: bold;
}
-->
  </style>
</head>
<body>

<?php

$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro']; 		
$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

$rs = query($var_sql,$conexion);
$num_paginas=obtener_num_paginas($var_sql,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);

function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$centro,$tipo_orden,$situacion){

	$conexion=conexion();		
		/*	  
		$var_sql="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,r.estacion,
		r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.concepto,r.tipo FROM requisiciones r,centros c 
		WHERE r.cod_requisicion=$id and r.cod_centro=c.cod_centro";
		*/
		$rso = query($var_sql,$conexion);
   	  	$row_rso = fetch_array($rso); 
	  	$var_codigo=$row_rso['cod_requisicion'];
		$var_fecha=fecha($row_rso['agregada_fecha']);
		$var_unidad=$row_rso['unidad'];
		$var_centro_costo=$row_rso['cod_centro'];
		$var_situacion=$row_rso['situacion'];
		$var_cod_provee=$row_rso['cod_provee'];
		$var_cod_requi=$row_rso['cod_requi'];
		$var_tipo=$row_rso['tipo'];
			
					
		$rs = query("SELECT descripcion FROM centros where cod_centro = '$var_centro_costo'",$conexion);
		
		while ($row_rs = fetch_array($rs)) 
		{		
			$var_nom_centro=$row_rs['descripcion'];		
		}
			
		$var_sql="SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = $var_tipo";
		$rsu = query($var_sql,$conexion);		
		$row_rsu = fetch_array($rsu);
		$var_nom_tipo=$row_rsu['descripcion'];		

		if($centro==0){$var_centro_costo="TODOS";}else {$var_centro_costo=$var_centro_costo." - ".$var_nom_centro;} 

		if($tipo_orden<>"0"){$var_nom_orden=$var_nom_tipo;}else{$var_nom_orden="TODOS";}

		if ($situacion =="Todas"){$var_estado="Todas";}else{$var_estado=$var_situacion;} 
	
$datos_requisicion='<table width="700" border="0" align="center">
  <tbody>
	<tr>
	  <td width="500" colspan="7" align="center"><h3><strong>LISTADO DE REQUISICIONES </strong></h3></td>
	</TR>
    <tr>
      <td width="200" align="right" colspan="7"><h3><strong>Pag: '.$pagina.'/'.$num_paginas.'</strong></h3></td>
    </tr>
<TR>
   <TD colspan="6"><br></TD>
 </TR>
    <tr>
      <td width="200" colspan="2"><p align="center"><strong>Centros de Costos:</strong></p>
          <p align="center">'.$var_centro_costo.'</p></td>
      <td width="250" colspan="3"><p align="center"><strong>Tipo de Requisicion</strong></p>
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
    <tr style="border-bottom-style : outset; border-bottom-width : 1px; border-left-style : outset; border-left-width : 1px; border-right-style : outset; border-right-width : 1px; border-top-style : outset; border-top-width : 1px;">
          <td width="50" align="center"><strong>Numero</strong></td>
          <td width="50" align="center"><strong>Fecha</strong></td>
          <td width="50" align="center"><strong>Tipo</strong></td>
		  <td width="100" align="center"><strong>Unidad</strong></td>
		  <td width="100" align="center"><strong>Centro de Costo</strong></td>
			<td width="300" align="center"><strong>Concepto</strong></td>
		  <td width="50" align="center"><strong>Estado</strong></td>
        </tr>
  </tbody>
</table>';

echo $datos_requisicion;

}
$pie=pie_inzuvi();
?>

<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div id="impresion">
<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
echo $encabezado."<br><br>";
imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$var_centro,$var_tipo_orden,$var_estado);
?>

<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class="">
<?
$cont=1;
while ($row_rs = fetch_array($rs)) 
{ 
		$var_unidad=$row_rs['unidad'];
		$rso = query("SELECT descripcion FROM unidades where cod_unidad = '".$var_unidad."'",$conexion);
		$row_rso = fetch_array($rso); 
		$var_nom_unidad=$row_rso['descripcion'];		
		
		$cod_centro=$row_rs['cod_centro'];
		$rsu = query("SELECT descripcion FROM centros where cod_centro='".$cod_centro."'",$conexion);
		$row_rsu = fetch_array($rsu); 
		$var_nom_centro=$row_rsu['descripcion'];
		
		$var_tipo=$row_rs['tipo'];
		$rsa = query("SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = '".$var_tipo."'",$conexion);		
		$row_rsa = fetch_array($rsa);
		$var_nom_tipo=$row_rsa['descripcion'];
		
?>
	<tr class="tb-bg-in">
    	<form method="post" id="form<? echo $row_rs['id_foto'] ?>" name="form<? echo $row_rs['id_foto'] ?>" action="<? echo $filename ?>?rsac=edit&amp;id=<? echo $row_rs['id_foto'] ?>">
            <td width="50" ><div align="center"><? echo $row_rs['cod_requisicion'] ?></div></td>
            <td width="50" align="center"><? echo fecha($row_rs['agregada_fecha']) ?></td>
            <td width="50" align="center"><? echo $var_nom_tipo; ?></td>
			<td width="100"><? echo $var_nom_unidad; ?></td>
			<td width="100"><? echo $var_nom_centro; ?></td>
			<td width="300"><? echo $row_rs['concepto']; ?></td>
			<td width="50" align="center"><? echo $row_rs['situacion']; ?></td>
            <!--    <td width="16"><a href="javascript:;" onclick="confirmar('Seguro de Borrar?','<? echo $filename ?>?rsac=del&amp;id=<? echo $id; ?>'); return self.rValue"><img src="img_sis/ico_basket.gif" alt="Borrar" width="15" height="15" border="0" /></a></td> -->
         </form>
	</tr>
<?
	if($cont==$cantidad_registros){
		echo "</table>".$pie."<br class=\"saltopagina\">";

		echo $encabezado.'<br><br>';
		imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$var_centro,$var_tipo_orden,$var_estado);

		echo '<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class="">';
		$cont=1;

	}else{$cont++;}
}

if($cont<=$cantidad_registros){
	echo "</table>".$pie;
}// cierra el if 
?>
<?  cerrar_conexion($conexion);?>

</div>

</body>
</html>
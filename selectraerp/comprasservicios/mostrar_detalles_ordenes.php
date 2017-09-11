<HTML>
<HEAD><TITLE></TITLE>
<SCRIPT language="JavaScript" type="text/javascript" src="mostrar_detalles.js">
</SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
</SCRIPT>
</HEAD>

<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion_conf= conexion_conf();
$consulta_conf="select tipo_presupuesto from parametros";
$resultado_conf=query($consulta_conf,$conexion_conf);
$fila_conf=fetch_array($resultado_conf);
//$tipo_compromiso=$fila_conf['tipo_compromiso'];
$tipo_presupuesto = $fila_conf['tipo_presupuesto'];
cerrar_conexion($conexion_conf);


$url="mostrar_detalles_ordenes";
$modulo="Proveedor";
$codigo_ord=@$_GET["codigo"];
$conexion = conexion();

//$titulos1=array("Situaci&oacute;n","Orden","Orden Ref","Fecha","Tipo","Monto","Saldo","Unidad");
//$indices1=array("16","0","18","1","8","4","17","2");

//$titulos2=array("Descripci&oacute;n","Unidad","Cantidad","Precio","Total I.V.A","Total Bs.F");
//$indices2=array("1","2","11","9","13","9");

//$titulos2=array("Descripci&oacute;n","Unidad","Cantidad","Precio","Total I.V.A","Total Bs.F");
//$indices2=array("1","2","11","9","13","9");
$titulos2=array("C&oacute;digo","Cantidad","Precio ","I.V.A.");
$indices2=array("1","3","4","5");

if ($tipo_presupuesto=='Programa')
{
	$titulos3=array("Sector", "Programa", "Actividad", "Partida","Ordinal","Monto");
	$indices3=array("7","8","9","6","10","2");
}
else
{
	$titulos3=array("Proyecto o Acción Centralizada", "Acción Especifica", "Partida","Ordinal","Monto");
	$indices3=array("8","9","6","10","2");
}

$titulos4=array("ODP", "Fecha", "Monto Pago", "Situaci&oacute;n");
$indices4=array("0","4","3","10");
/*
$titulos5=array("Cuenta", "Cheque", "Fecha", "Entregado");
$indices5=array("7","5","6","63");
*/

//$consulta1 = "SELECT * FROM ordenes WHERE cod_provee='".$codigo."'";
//$resultado1 = query($consulta1, $conexion);

$consulta2 = "SELECT * FROM ordenes_detalles WHERE TRIM(cod_ord)='".trim($codigo_ord)."'";
$resultado2 = query($consulta2, $conexion);
/*$consulta3="SELECT * FROM materiales WHERE cod_material='".$materiales."'";
$resultado3 = query($consulta3,$conexion);
while ($fila3 = fetch_array($resultado3))
{
	$descripcion=$fila3['descripcion'];
	$unidad=$fila3['unidad'];
	$cantidad=$fila3['cantidad'];
	//$precio=$fila3['precio'];
	//$total_iva=$fila3['iva'];
}*/

$consulta3 = "SELECT * FROM cwpreejc WHERE TRIM(RecNoOrders)='".trim($codigo_ord)."'";
$resultado3 = query($consulta3, $conexion);

$consulta4 = "SELECT * FROM ordenes_pago WHERE TRIM(numero_ocs)='".trim($codigo_ord)."'";
$resultado4 = query($consulta4, $conexion);

//$consulta5 = "SELECT * FROM ordenes_pago WHERE codigo_cliente='".$codigo."'";
//$resultado5 = query($consulta5, $conexion);


if(num_rows($resultado2)<=0){
	echo "<tr><td>No existen registro con la busqueda especificada</td></tr>";
	exit(0);
}
?>
<BR>
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
   <tbody>
	 <tr class="tb-tit"><TD colspan="8" align="center"><strong>MATERIALES de la Orden de C/S Nro <?php echo $codigo_ord;?></strong></TD></tr>
	  <tr class="tb-head">
<?
foreach($titulos2 as $nombre2){
	echo "<td><STRONG>$nombre2</STRONG></td>";
}
?>
    	</tr> 
<?
	$i1=0;
	while($fila1=fetch_array($resultado2)){
   	$i1++;
	if($i1%2==0){
?>
   <tr class="tb-fila">
<?
	}else{
		echo "<tr>";
	}
	foreach($indices2 as $campo2)
	{
		$nom_tabla2=mysql_field_name($resultado2, $campo2);
		$var2 = $fila1[$nom_tabla2];
		if($nom_tabla2=="precio"){
			echo "<td>".number_format($var2, 2, ',','.')."</td>";;
		}elseif($nom_tabla2=="iva"){
			echo "<td>".number_format($var2, 2, ',','.')."</td>";
		}else{
			echo "<td>$var2</td>";
		}
	//$codigo_ord=$fila2["codigo"];
	//icono("javascript:detalles_ordenes('codigo=".$codigo_ord."')", "Mostrar Detalles Orden", "view.gif")
	}
	 echo "</tr>";
}
?>
  </tbody>
</table>
<?
if(num_rows($resultado3)<=0){
	echo "<tr><td>No existen registro con la busqueda especificada</td></tr>";
	exit(0);
}
?>
<BR>
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
   <tbody>
	 <tr class="tb-tit"><TD colspan="8" align="center"><strong>PARTIDAS PRESUPUESTARIAS afectadas por la Orden de C/S Nro <?php echo $codigo_ord;?></strong></TD></tr>
	  <tr class="tb-head">
<?
foreach($titulos3 as $nombre3){
	echo "<td><STRONG>$nombre3</STRONG></td>";
}
?>
	 	</tr> 
<?
	$i1=0;
	while($fila3=fetch_array($resultado3)){
   	$i1++;
	if($i1%2==0){
?>
   <tr class="tb-fila">
<?
	}else{
		echo "<tr>";
	}
	foreach($indices3 as $campo3)
	{
		$nom_tabla3=mysql_field_name($resultado3, $campo3);
		$var3 = $fila3[$nom_tabla3];
		if($nom_tabla3=="Monto"){
			echo "<td>".number_format($var3, 2, ',','.')."</td>";;
		}else{
		echo "<td>$var3</td>";
		}
	}
	//$codigo_ord=$fila2["codigo"];
	//icono("javascript:detalles_ordenes('codigo=".$codigo_ord."')", "Mostrar Detalles Orden", "view.gif");
   echo "</tr>";
	}
?>
  </tbody>
</table>
<?
if(num_rows($resultado4)<=0){
	echo "<tr><td>No existen registro con la busqueda especificada</td></tr>";
	exit(0);
}
?>
<BR>
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
   <tbody>
	 <tr class="tb-tit"><TD colspan="8" align="center"><strong>ORDENES DE PAGO ASOCIADAS a la Orden de C/S Nro <?php echo $codigo_ord;?></strong></TD></tr>
	  <tr class="tb-head">
<?
foreach($titulos4 as $nombre4){
	echo "<td><STRONG>$nombre4</STRONG></td>";
}
?>
		<td></td>
    	<td></td> 
	 	</tr> 
<?
	$i1=0;
	while($fila4=fetch_array($resultado4)){
   	$i1++;
	if($i1%2==0){
?>
   <tr class="tb-fila">
<?
	}else{
		echo "<tr>";
	}
	foreach($indices4 as $campo4)
	{
		$nom_tabla4=mysql_field_name($resultado4, $campo4);
		$var4 = $fila4[$nom_tabla4];
		if($nom_tabla4=="fecha"){
			echo "<td>".fecha($var4)."</td>";
		}elseif($nom_tabla4=="montopago"){
			echo "<td>".number_format($var4, 2, ',','.')."</td>";
		}else{
			echo "<td>$var4</td>";
		}
	}
	$codigo_odp=$fila4["numero_odp"];
	$cheque=$fila4["cheque"];
	//icono("mostrar_detalles_ordenes.php?codigo=$codigo", "Mostrar", "ico_cel.gif");
	icono("javascript:detalles_odp('codigo=".$cheque."&numero_odp=".$codigo_odp."')", "Mostrar Detalles Orden de Pago", "view.gif");
   echo "</tr>";
	}
?>
  </tbody>
</table>

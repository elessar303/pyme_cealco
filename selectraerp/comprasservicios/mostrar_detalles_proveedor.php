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

$url="mostrar_detalles_proveedor";
$modulo="Proveedor";

$titulos1=array("Situaci&oacute;n","Orden","Orden Ref","Fecha","Tipo","Monto","Saldo","Unidad");
$indices1=array("16","0","18","1","8","4","17","2");
/*
$titulos2=array("Descripci&oacute;n","Unidad","Cantidad","Precio","Total I.V.A","Total Bs.F");
$indices2=array("1","2","11","9","13","9");

$titulos3=array("Sector", "Programa", "Actividad", "Partida","Ordinal","Monto Bs.F");
$indices3=array("7","8","9","6","10","2");

$titulos4=array("ODP", "Fecha", "Monto Pago", "Situaci&oacute;n");
$indices4=array("0","4","3","10");

$titulos5=array("Cuenta", "Cheque", "Fecha", "Entregado");
$indices5=array("7","5","6","63");
*/
$codigo=@$_GET["codigo"];
$compania=@$_GET["compania"];
$conexion = conexion();
$consulta1 = "SELECT * FROM ordenes WHERE TRIM(cod_provee)='".trim($codigo)."'";
$resultado1 = query($consulta1, $conexion);

//$consulta2 = "SELECT * FROM materiales WHERE codigo='".$codigo."'";
//$resultado2 = query($consulta2, $conexion);

//$consulta3 = "SELECT * FROM cwpreejc";
//$resultado3 = query($consulta3, $conexion);

//$consulta4 = "SELECT * FROM ordenes_pago WHERE codigo_cliente='".$codigo."'";
//$resultado4 = query($consulta4, $conexion);

//$consulta5 = "SELECT * FROM ordenes_pago WHERE codigo_cliente='".$codigo."'";
//$resultado5 = query($consulta5, $conexion);

if(num_rows($resultado1)<=0){
	echo "<tr><td>No existen registro con la busqueda especificada</td></tr>";
	exit(0);
}
?>
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
   <tbody>
	 <tr class="tb-tit"><TD colspan="8" align="center"><strong>&Oacute;RDENES <?php echo $compania?></strong></TD><td></td></tr>
	  <tr class="tb-head">
<?
foreach($titulos1 as $nombre1){
	echo "<td><STRONG>$nombre1</STRONG></td>";
}
?>
		<td></td>
    	<td></td> 
	 	</tr> 
<?
	$i1=0;
	while($fila1=fetch_array($resultado1)){
   	$i1++;
	if($i1%2==0){
?>
   <tr class="tb-fila">
<?
	}else{
		echo "<tr>";
	}
	foreach($indices1 as $campo1)
	{
		$nom_tabla1=mysql_field_name($resultado1, $campo1);
		$var1 = $fila1[$nom_tabla1];
		if($nom_tabla1=="fecha"){
			echo "<td>".fecha($var1)."</td>";
		}elseif($nom_tabla1=="tipo")
		{
			//$sit=$fila[$nom_tabla1];
			$consul="select descripcion from ordenes_tipos where cod_orden_tipo=".$var1;
			$resul=query($consul,$conexion);
			$fila2=fetch_array($resul);
			$descripcion=$fila2["descripcion"];
			echo"<td>$descripcion</td>";
		}elseif($nom_tabla1=="monto_orden"){
			echo "<td>".number_format($var1, 2, ',','.')."</td>";
		}elseif($nom_tabla1=="saldo"){
			echo "<td>".number_format($var1, 2, ',','.')."</td>";
		}elseif($nom_tabla1=="unidad"){
			$consul="select descripcion from unidades where cod_unidad=".$var1;
			$resul=query($consul,$conexion);
			$fila2=fetch_array($resul);
			$descripcion=$fila2["descripcion"];
			echo"<td>$descripcion</td>";
		}else{
			echo "<td>$var1</td>";
		}
	}
	$codigo_ord=$fila1["codigo"];
	//icono("mostrar_detalles_ordenes.php?codigo=$codigo", "Mostrar", "ico_cel.gif");
	icono("javascript:detalles_ordenes('codigo=".$codigo_ord."')", "Mostrar Detalles Orden", "view.gif");
   echo "</tr>";
	}
?>
  </tbody>
</table>




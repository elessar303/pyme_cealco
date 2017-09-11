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

//$titulos1=array("Situaci&oacute;n","Orden","Orden Ref","Fecha","Tipo","Monto","Saldo","Unidad");
//$indices1=array("16","0","18","1","8","4","17","2");
/*
$titulos2=array("Descripci&oacute;n","Unidad","Cantidad","Precio","Total I.V.A","Total Bs.F");
$indices2=array("1","2","11","9","13","9");

$titulos3=array("Sector", "Programa", "Actividad", "Partida","Ordinal","Monto Bs.F");
$indices3=array("7","8","9","6","10","2");

$titulos4=array("ODP", "Fecha", "Monto Pago", "Situaci&oacute;n");
$indices4=array("0","4","3","10");
*/
$titulos5=array("Banco","Cuenta","Chequera","Cheque", "Fecha");
$indices5=array("20","1","21","2","7");

$cheque=@$_GET["codigo"];
$numero_odp=@$_GET["numero_odp"];

$conexion = conexion();
//$consulta1 = "SELECT * FROM ordenes WHERE cod_provee='".$codigo."'";
//$resultado1 = query($consulta1, $conexion);

//$consulta2 = "SELECT * FROM materiales WHERE codigo='".$codigo."'";
//$resultado2 = query($consulta2, $conexion);

//$consulta3 = "SELECT * FROM cwpreejc";
//$resultado3 = query($consulta3, $conexion);

//$consulta4 = "SELECT * FROM ordenes_pago WHERE codigo_cliente='".$codigo."'";
//$resultado4 = query($consulta4, $conexion);

$consulta5 = "SELECT * FROM cheques WHERE cheque='".$cheque."' and orden='".$numero_odp."'";
$resultado5 = query($consulta5, $conexion);

if(num_rows($resultado5)<=0){
	echo "<tr><td>No existen registro con la busqueda especificada</td></tr>";
	exit(0);
}
?>
<BR>
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
   <tbody>
	 <tr class="tb-tit"><TD colspan="8" align="center"><strong>CHEQUES Asociados a la Orden de Pago Nro <?php echo $numero_odp;?></strong></TD></tr>
	  <tr class="tb-head">
<?
foreach($titulos5 as $nombre5){
	echo "<td><STRONG>$nombre5</STRONG></td>";
}
?>
		
	 	</tr> 
<?
	$i1=0;
	while($fila5=fetch_array($resultado5)){
   	$i1++;
	if($i1%2==0){
?>
   <tr class="tb-fila">
<?
	}else{
		echo "<tr>";
	}
	foreach($indices5 as $campo5)
	{
		$nom_tabla5=mysql_field_name($resultado5, $campo5);
		$var5 = $fila5[$nom_tabla5];
		if($nom_tabla5=="fecha"){
			echo "<td>".fecha($var5)."</td>";
		}elseif($nom_tabla5=="banco"){
			$consul="select descripcion from bancos where codigo=".$var5;
			$resul=query($consul,$conexion);
			$fila2=fetch_array($resul);
			$descripcion=$fila2["descripcion"];
			echo"<td>$descripcion</td>";
		}else
		{
		echo "<td>$var5</td>";
		}
	}
	$codigo_ord=$fila5["codigo"];
	//icono("mostrar_detalles_ordenes.php?codigo=$codigo", "Mostrar", "ico_cel.gif");
	//icono("javascript:detalles_ordenes('codigo=".$codigo_ord."')", "Mostrar Detalles Orden", "view.gif");
   echo "</tr>";
	}
?>
  </tbody>
</table>




<?php
if (!isset($_SESSION)) {
  session_start();
}
require_once '../lib/common.php';

include ("../header.php");
$conexion=conexion();
//echo $conexion;

$url="crs_seleccion";
$modulo="Compromiso de Responsabilidad Social";



if(isset($_POST["imprimir"])) 
{
	$proveedor=$_POST['proveedor'];
	$requisicion=$_POST['requisicion'];
	if($proveedor!="" && $requisicion!=""){
		echo "<script language=\"javascript\" >
		location.href='../fpdf/crs_pdf.php?prov=$proveedor&req=$requisicion'</script>";
	}else{
		echo "<script language=\"javascript\" >
		alert('Debe seleccionar un campo para generar el Reporte!!!!')</script>";
	}
	
}

	

?>
<html class="fondo">
<head>
  <title></title>
  <link href="../estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
  </SCRIPT>
<script language="javascript" src="cal2.js"></script>
<script language="javascript" src="cal_conf2.js"></script>

</head>
<body>
<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<TABLE  width="100%" height="100" border="0" >

<?

$consulta="select * from proveedores ORDER BY compania";
$resultado_cuenta=query($consulta,$conexion);
$consulta="select * from requisiciones where situacion='Revisar' ORDER BY cod_requisicion,tipo";
$resultado_requisicion=query($consulta,$conexion)

?>
    <tr >
      <td colspan="7" height="30" class="tb-tit"><strong> <?echo $modulo?></strong></td>
    </tr>
	<tr>
		<td class=tb-head colspan="2"><?echo "Proveedor:"; ?></td>
		<td colspan="3"><SELECT name="proveedor" id="proveedor">
		<?
		echo "<option value=''> Seleccione un Proveedor</option>";
		while($rs=fetch_array($resultado_cuenta)){
			echo "<option value='".$rs['cod_proveedor']."'> ".$rs['compania']."</option>";
		}
		?>	
		</SELECT></td> 

	</tr>
	<tr>
		<td class=tb-head colspan="2"><?echo "Requisición:"; ?> </td>
		<td colspan="3">
		<SELECT name="requisicion" id="requisicion">
		<?
		echo "<option value=''> Seleccione una Requisición</option>";
		while(($rss=fetch_array($resultado_requisicion))){
				$conexion=conexion();
				$select="select * from  ordenes_tipos where cod_orden_tipo=".$rss['tipo'];
				$rt=query($select,$conexion);
				$tipo=fetch_array($rt);
				echo "<option value='".$rss['cod_requisicion']."'> ".$rss['cod_requisicion']."  ".$tipo['descripcion']."</option>";
		}
		?>	
		</SELECT> 
		</td> 
	</tr>
	<tr  align="right" class="tb-tit">
		<td></td>
		<td colspan="4" align="right">
				<input  type="hidden" name="imprimir"  id="imprimir" value="Imprimir" />
				<input   type="submit" name="Imprimir"  id="Imprimir" value="Imprimir"/>&nbsp;
		</td>
		
    	</tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);
?>
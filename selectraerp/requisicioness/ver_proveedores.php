<?php
require_once '../lib/common.php';
include ("../header.php");
$url="requisiciones2";
$modulo="Requisiciones";
$tabla="requisiciones";
$titulos=array("Concepto","Observaciones");
$indices=array("8","4");

$conexion=conexion();
$cod_unidad=@$_GET['codigo'];
$cod_centro=@$_GET['cod_centro'];
$id=@$_GET['id'];

$conexion=conexion();


$var_tipo_orden=$_GET[tipo_orden];
$var_estado=$_GET[estado];
$var_centro=$_GET[centro];	

?>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?
	titulo("Detalle de la Requisición Adjudicada","","buscar_reporte_req.php?tipo_orden=$var_tipo_orden&estado=$var_estado&centro=$var_centro&id=$id&acc=33");
?>

<TABLE  width="100%" height="" border="0">
<TBODY>
<?

$consulta_o="select * from ordenes where cod_requi=".$id;
$resultado_ordenes=query($consulta_o,$conexion);
$contador=1;
while($rp=fetch_array($resultado_ordenes)){
	$provee=$rp['cod_provee'];
	$consulta_p="Select * from proveedores where cod_proveedor=$provee";	
	$resultado_provee=query($consulta_p,$conexion);
	$proveedor=fetch_array($resultado_provee);
?>
	<tr>
	<TD class="tb-head" width="100%" height="30" align="center"><strong>PROVEEDOR <?echo $contador;?></strong></TD>
	</tr>
	<tr>
		<table width="100%"  border="0">
			<tr>
			<td class="tb-head" width="200"><strong>Nombre:</strong></td>
			<td colspan="6" ><strong><?echo $proveedor['compania'];?></strong></td>
			</tr>
			<tr>
			<td class="tb-head"><strong>R.I.F. :</strong></td>
			<td colspan="6" ><?echo $proveedor['rif'];?></td>
			</tr>
			<tr>
			<td class="tb-head"><strong>Telefonos: </strong></td>
			<td colspan="6"><?echo $proveedor['rep_apellidos'];?></td>
			</tr>
			<tr>
			<td class="tb-head"><strong>Dirección: </strong></td>
			<td colspan="6"><?echo $proveedor['direccion1'].' '.$proveedor['direccion2'];?></td>
			</tr>
		</table>
	</tr>
<? }?>	
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);

?>
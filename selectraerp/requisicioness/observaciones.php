<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$url="requisiciones";
$modulo="Requisiciones";
$tabla="requisiciones";
$pagina=@$_GET['pagina'];

$id=$_GET['id'];
$url2=$_GET['url'];
if(isset($_POST["guardar"])) 
{
	$ob=$_POST['obser'];
	$fecha=date("d/m/Y");
	echo $var_sql="update requisiciones set descripcion='$fecha:$ob',situacion='Registrada' WHERE cod_requisicion = $id";
	$rs = query($var_sql,$conexion);?>
	
	<script type="text/javascript">
		
		alert("Se guardo con Exito sus Observaciones!!!")
		window.opener.location.href="requisiciones_administracion_list.php?id=<?php echo $id?>&pagina=<?php echo $pagina?>"
		window.close();
	</script>
	<?
}

?>

<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']."?id=".$id."&url=".$url2."&pagina=".$pagina ?>" method="POST" target="_self">

<table class="tb-head" width="100%">
	<tr class="tb-head"  align="center"><td><strong>OBSERVACIONES</strong>
		</td>
	</tr>
  	<tr>
		<td colspan="3" height="60" align="center">
			<textarea name="obser"  cols=40 id="obser" maxlength="250"></textarea>
		</td>
	</tr>
	<tr align="center">
				
      		<td class="" align="center">
			
			<input   type="submit" name="guardar"  id="guardar" value="Guardar"  />&nbsp;
			<INPUT type="submit" name="cancelar" value="Cancelar" onclick="javascript:window.close()"></td>
	</tr>
</table>

</FORM>
</BODY>
</html>
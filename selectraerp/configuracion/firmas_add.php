<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion_conf();

$url="firmas_list";
$modulo="Firmas Reportes";
$tabla="firmas";
$titulos=array("Descripción:","Cargo del Firmante:","Nombre del Firmante:","Orden del Firmante:");
$indices=array("1","2","3","4");
$pagina=@$_GET['pagina'];

if(isset($_POST['aceptar']))
{
	
	if ((($_POST['descripcion'] == '') && ($_POST['cargo_persona'] == '') && ($_POST['nombre_persona'] == '')  )|| ($_POST['orden_reporte'] == ''))
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Datos imcompletos, no se puede realizar la operacion\")";
		echo "</SCRIPT>";
	}
	else
	{	
		$guardar=false;
		$consulta="select * from ".$tabla."";
		$resultado=query($consulta,$conexion);
		foreach($indices as $valor)
		{
			$campo=mysql_field_name($resultado,$valor);
			if($cadena_campos=="" && $cadena_valores=="")
			{
				$cadena_campos=$cadena_campos.$campo;
				$cadena_valores=$cadena_valores."'".$_POST[$campo]."'";
			}
			else
			{
				$cadena_campos=$cadena_campos.",".$campo;
				$cadena_valores=$cadena_valores.",'".$_POST[$campo]."'";
			}
		}
		
		$cadena_campos=$cadena_campos.", cod_reporte, modulo";
		$cadena_valores;
		if($_POST['REQ']==true){ 
			$consulta="select * from ".$tabla." where cod_reporte='REQUISICIONES'";
			$resultado=query($consulta,$conexion);
			$can=num_rows($resultado);
			if($can==6){
				echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
				alert('No se puede guardar mas firmas para REQUISICIONES!!!')
				</SCRIPT>";
			}else{
				$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'REQUISICIONES','Administrativo')";
				$resultado=query($consulta,$conexion);
				$guardar=true;
			}
		}
		if($_POST['PRES']==true){
			$consulta="select * from ".$tabla." where cod_reporte='PRESUPUESTO'";
			$resultado=query($consulta,$conexion);
			$can=num_rows($resultado);
			if($can==6){
				echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
				alert('No se puede guardar mas firmas para PRESUPUESTO!!!')
				</SCRIPT>";
			}else{
				$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'PRESUPUESTO','Administrativo')";
				$resultado=query($consulta,$conexion);
				$guardar=true;
			}
		}

		if($_POST['DECRETOS']==true){
			$consulta="select * from ".$tabla." where cod_reporte='DECRETOS'";
			$resultado=query($consulta,$conexion);
			$can=num_rows($resultado);
			if($can==6){
				echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
				alert('No se puede guardar mas firmas para DECRETOS!!!')
				</SCRIPT>";
			}else{
				$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'DECRETOS','Administrativo')";
				$resultado=query($consulta,$conexion);
				$guardar=true;
			}
		}
		if($_POST['ENTREGA']==true){
			$consulta="select * from ".$tabla." where cod_reporte='NOTA ENTREGA'";
			$resultado=query($consulta,$conexion);
			$can=num_rows($resultado);
			if($can==6){
				echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
				alert('No se puede guardar mas firmas para NOTA ENTREGA!!!')
				</SCRIPT>";
			}else{
				$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'NOTA ENTREGA','Administrativo')";
				$resultado=query($consulta,$conexion);
				$guardar=true;
			}
		}
		if($_POST['ALMACEN']==true){
			$consulta="select * from ".$tabla." where cod_reporte='ACTAS ALMACEN'";
			$resultado=query($consulta,$conexion);
			$can=num_rows($resultado);
			if($can==6){
				echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
				alert('No se puede guardar mas firmas para ACTAS ALMACEN!!!')
				</SCRIPT>";
			}else{
				$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'ACTAS ALMACEN','Administrativo')";
				$resultado=query($consulta,$conexion);
				$guardar=true;
			}
		}
		if($_POST['CAJ']==true){
			$consulta="select * from ".$tabla." where cod_reporte='CAJA'";
			$resultado=query($consulta,$conexion);
			$can=num_rows($resultado);
			if($can==6){
				echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
				alert('No se puede guardar mas firmas para CAJA!!!')
				</SCRIPT>";
			}else{
				$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'CAJA','Administrativo')";
				$resultado=query($consulta,$conexion);
				$guardar=true;
			}
		}
		if($_POST['TES']==true){
			$consulta="select * from ".$tabla." where cod_reporte='TESORERIA'";
			$resultado=query($consulta,$conexion);
			$can=num_rows($resultado);
			if($can==6){
				echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
				alert('No se puede guardar mas firmas para TESORERIA!!!')
				</SCRIPT>";
			}else{
				$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'TESORERIA','Administrativo')";
				$resultado=query($consulta,$conexion);
				$guardar=true;
			}
		}
		$conexion=conexion();
		$consulta="select * from ordenes_tipos ORDER BY descripcion";
		$ord=query($consulta,$conexion);
		cerrar_conexion($conexion);
		$conexion=conexion_conf();
		while ($ord_tipo=fetch_array($ord)){
			if($_POST['ORD'.$ord_tipo[0]]==true){
				$consulta="select * from ".$tabla." where cod_reporte='ORD".$ord_tipo[1]."'";
				$resultado=query($consulta,$conexion);
				$can=num_rows($resultado);
				if($can==6){
					echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
					alert('No se puede guardar mas firmas para ORD".$ord_tipo[1]."!!!')
					</SCRIPT>";
				}else{
					$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'ORD".$ord_tipo[1]."','Administrativo')";
					$resultado=query($consulta,$conexion);
					$guardar=true;
				}
			}
			if($_POST['ODP'.$ord_tipo[0]]==true){
				$consulta="select * from ".$tabla." where cod_reporte='ODP".$ord_tipo[1]."'";
				$resultado=query($consulta,$conexion);
				$can=num_rows($resultado);
				if($can==6){
					echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
					alert('No se puede guardar mas firmas para ODP".$ord_tipo[1]."!!!')
					</SCRIPT>";
				}else{
					$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.",'ODP".$ord_tipo[1]."','Administrativo')";
					$resultado=query($consulta,$conexion);
					$guardar=true;
				}
			}
		}

		cerrar_conexion($conexion);
		//header
		if ($guardar==true){
			echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
			alert('Se guardo correctamente la firma !!!')
			parent.cont.location.href=\"".$url.".php?pagina=$pagina\"
			</SCRIPT>";
		}else{
			echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
			parent.cont.location.href=\"".$url.".php?pagina=$pagina\"
			</SCRIPT>";
		}
	}	
}
else
{

	$consulta="select * from ".$tabla;
	$resultado=query($consulta,$conexion);
}
?>



<html class="fondo">

<head>
  <title></title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="lib/common.js">
  </SCRIPT>
<script language="javascript" src="cal2.js"></script>
<script language="javascript" src="cal_conf2.js"></script>
<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?pagina=1"
}

</SCRIPT>

</head>
<body>
<head>
<script language="JavaScript" type="text/JavaScript">


</script>
</head>
<body>
</body>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php $pagina;?>">

<?php $conexion=conexion();?>
<TABLE  width="100%" height="100">
<TBODY>

<tr>
	  <td colspan="2" height="30" class="tb-tit"><? titulo("Agregar nuevo registro de ".$modulo,"","firmas_list.php","12"); ?></td>
    </tr>


			
			<TR><td class=tb-head >Descripción:</td>
			<td><INPUT type="text" name="descripcion"  maxlength="14" size="50" ></td> </tr>
      		<TR><td class=tb-head >Cargo del Firmante:</td>
			<td><INPUT type="text" name="cargo_persona" maxlength="21" size="50"></td> </tr>
			<TR><td class=tb-head >Nombre del Firmante:</td>
			<td><INPUT type="text" name="nombre_persona"  maxlength="18" size="50"></td> </tr>
			<TR><td class=tb-head >Orden del Firmante:</td>
			<td><INPUT type="text" name="orden_reporte" size="50"></td> </tr>
			
</tbody>
</table>
<table  width="100%" >
	    <tr><td colspan="7" height="30" class="tb-tit"><strong>Reportes: </strong></td></tr>
			
	    
</table>
<table   width="350"      >

		<tr  >
			<td class=tb-head  >&#8226; M&oacute;dulo de Configuraci&oacute;n</td>
			<td colspan="1"  width="20" class=tb-head ></td>
			
		</tr>
		
			
		<tr>
			<td class=tb-head   >&#8226; M&oacute;dulo de Requisiciones</td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="REQ" value="1" size="100"></td>
			
		</tr>
		
		
		
		<tr>
			
			<td class=tb-head  > &#043; M&oacute;dulo de Compras y Servicios</td>
			<td colspan="1" class=tb-head></td>
		</tr>
		<?php 
			$consulta="select * from ordenes_tipos ORDER BY descripcion";
			$ord=query($consulta,$conexion);
			while ($ord_tipo=fetch_array($ord)){
		?>
		<tr >
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Ordenes de  <?php echo $ord_tipo[1]?></td>
			<td colspan="1" ><INPUT type="checkbox"  name="ORD<?php echo $ord_tipo[0]?>" value="1" size="100"></td>
			
		</tr>
		<?php }?>
		<tr>
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Notas de Entrega</td>
			<td colspan="1" ><INPUT type="checkbox"  name="ENTREGA" value="1" size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Actas de Almacen</td>
			<td colspan="1" ><INPUT type="checkbox"  name="ALMACEN" value="1" size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  >&#8226; M&oacute;dulo Ejecuci&oacute;n de Presupuesto</td>
			<td colspan="1" class=tb-head ><INPUT type="checkbox"  name="PRES" value="1" size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Decretos</td>
			<td colspan="1" ><INPUT type="checkbox"  name="DECRETOS" value="1" size="100"></td>
		</tr>
		<tr >
			<td class=tb-head >&#043; M&oacute;dulo de Ordenes de Pago</td>
			<td colspan="1" class=tb-head></td>
		</tr>
		<?php 
			$consulta="select * from ordenes_tipos ORDER BY descripcion";
			$ord=query($consulta,$conexion);
			while ($ord_tipo=fetch_array($ord)){
		?>
		<tr >
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Ordenes Pago de  <?php echo $ord_tipo[1]?></td>
			<td colspan="1" ><INPUT type="checkbox"  name="ODP<?php echo $ord_tipo[0]?>" value="1" size="100"></td>
			
		</tr>
		<?php }?>

		
		<tr>
			<td class=tb-head  >&#8226; M&oacute;dulo de Tesoreria</td>
			<td colspan="1" class=tb-head ><INPUT type="checkbox"  name="TES" value="1" size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  > &#8226; M&oacute;dulo de Caja </td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="CAJ" value="1" size="100"></td>
			
		</tr>
		
</table	>


<TABLE width="100%">
<TBODY>
    <tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onClick="javascript:cerrar('<? echo $url?>');"></td>
    </tr>
  </tbody>
</TABLE>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);

?>
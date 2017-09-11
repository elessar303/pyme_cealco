<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion_conf();

$url="firmas_list";
$modulo="Firmas Reportes";
$tabla="firmas";
$titulos=array("Descripción:","Cargo del Firmante:","Nombre del Firmante:","Orden del Firmante:");
$indices=array("1","2","3","4");
$codigo=$_GET['codigo'];
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
		$descrip=$_POST['descripcion'];
		$cargo=$_POST['cargo_persona'];
		$nombre=$_POST['nombre_persona'];
		$orden=$_POST['orden_reporte'];
		
		$true=false;
		
		if($_POST['REQ']==true){ 
			$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='REQUISICIONES' where cod_firmas=$codigo";
			$resultado=query($consulta,$conexion);
			$true=true;
		}else{
			if($_POST['PRES']==true){
				$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='PRESUPUESTO' where cod_firmas=$codigo";
				$resultado=query($consulta,$conexion);
				$true=true;
			}else{
				if($_POST['ENTREGA']==true){
					$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='NOTA ENTREGA' where cod_firmas=$codigo";
					$resultado=query($consulta,$conexion);
					$true=true;
				}else{
			
					if($_POST['ALMACEN']==true){
						$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='ACTAS ALMACEN' where cod_firmas=$codigo";
						$resultado=query($consulta,$conexion);
						$true=true;
					}else{
					
						if($_POST['CAJ']==true){
							$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='CAJA' where cod_firmas=$codigo";
							$resultado=query($consulta,$conexion);
							$true=true;
						}else{
							if($_POST['TES']==true){
								$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='TESORERIA' where cod_firmas=$codigo";
								$resultado=query($consulta,$conexion);
								$true=true;
							}else{
								if($_POST['DECRETOS']==true){
								$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='DECRETOS' where cod_firmas=$codigo";
								$resultado=query($consulta,$conexion);
								$true=true;
							}


							}
						}
					}
				}
			}
		}
		$conexion=conexion();
		$consulta="select * from ordenes_tipos ORDER BY descripcion";
		$ord=query($consulta,$conexion);
		cerrar_conexion($conexion);
		$conexion=conexion_conf();
		if ($true!=true){
			while ($ord_tipo=fetch_array($ord)){
				if ($true!=true){
					if($_POST['ORD'.$ord_tipo[0]]==true){
						$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='ORD".$ord_tipo[1]."' where cod_firmas=$codigo";
						$resultado=query($consulta,$conexion);
						$true=true;
					}
					if($_POST['ODP'.$ord_tipo[0]]==true){
						$consulta="update ".$tabla." set descripcion='$descrip',cargo_persona='$cargo',nombre_persona='$nombre',orden_reporte=$orden, cod_reporte='ORP".$ord_tipo[1]."' where cod_firmas=$codigo";
						$resultado=query($consulta,$conexion);
						$true=true;
					}
				}
			}
		}
		if ($true!=true){
			$consulta="update ".$tabla." set descripcion=$descrip,cargo_persona$cargo,nombre_persona=$nombre,orden_reporte=$orden, cod_reporte='' where cod_firmas=$codigo";
		}

		cerrar_conexion($conexion);
		//header
		echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert('Se Modifico correctamente la firma !!! ')
		parent.cont.location.href=\"".$url.".php?pagina=$pagina\"
		</SCRIPT>";
	}	
}
else
{

	$consulta="select * from ".$tabla." where cod_firmas=".$codigo;
	$resultado=query($consulta,$conexion);
	$firma=fetch_array($resultado);
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

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>?codigo=<?php echo $codigo."&pagina=".$pagina?>">

<?php $conexion=conexion();?>
<TABLE  width="100%" height="100">
<TBODY>

<tr>
	  <td colspan="2" height="30" class="tb-tit"><? titulo("Agregar nuevo registro de ".$modulo,"","firmas_list.php","12"); ?></td>
    </tr>


			
			<TR><td class=tb-head >Descripción:</td>
			<td><INPUT type="text" name="descripcion" size="50" maxlength="14" value="<?php echo $firma['descripcion']; ?>" ></td> </tr>
      			<TR><td class=tb-head >Cargo del Firmante:</td>
			<td><INPUT type="text" name="cargo_persona"  maxlength="19" size="50" value="<?php echo $firma['cargo_persona']; ?>"></td> </tr>
			<TR><td class=tb-head >Nombre del Firmante:</td>
			<td><INPUT type="text" name="nombre_persona" size="50"  maxlength="18" value="<?php echo $firma['nombre_persona']; ?>"></td> </tr>
			<TR><td class=tb-head >Orden del Firmante:</td>
			<td><INPUT type="text" name="orden_reporte" size="50" value="<?php echo $firma['orden_reporte']; ?>"></td> </tr>
			
</tbody>
</table>
<table  width="100%" >
	    <tr><td colspan="7" height="30" class="tb-tit"><strong>Reportes: </strong></td></tr>
			
	    
</table>
<?php $cod_reporte=$firma['cod_reporte'];?>
<table   width="350"      >

		<tr  >
			<td class=tb-head  >&#8226; M&oacute;dulo de Configuraci&oacute;n</td>
			<td colspan="1"  width="20" class=tb-head ></td>
			
		</tr>
		
			
		<tr>
			<td class=tb-head   >&#8226; M&oacute;dulo de Requisiciones</td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="REQ" value="1" size="100" 
			<?php if ($cod_reporte=='REQUISICIONES'){echo 'checked=true';}?> ></td>
			
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
			<td colspan="1" ><INPUT type="checkbox"  name="ORD<?php echo $ord_tipo[0]?>" value="1" size="100" <?php if ($cod_reporte=='ORD'.$ord_tipo[1]){echo 'checked=true';}?> ></td>
			
		</tr>
		<?php }?>
		<tr>
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Notas de Entrega</td>
			<td colspan="1" ><INPUT type="checkbox"  name="ENTREGA" value="1" size="100" <?php if ($cod_reporte=='NOTA ENTREGA'){echo 'checked=true';}?>></td>
		</tr>
		<tr>
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Actas de Almacen</td>
			<td colspan="1" ><INPUT type="checkbox"  name="ALMACEN" value="1" size="100" <?php if ($cod_reporte=='ACTAS ALMACEN'){echo 'checked=true';}?>></td>
		</tr>
		<tr>
			<td class=tb-head  >&#8226; M&oacute;dulo Ejecuci&oacute;n de Presupuesto</td>
			<td colspan="1" class=tb-head ><INPUT type="checkbox"  name="PRES" value="1" size="100" <?php if ($cod_reporte=='PRESUPUESTO'){echo 'checked=true';}?>></td>
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
			<td colspan="1" ><INPUT type="checkbox"  name="ODP<?php echo $ord_tipo[0]?>" value="1" size="100" <?php if ($cod_reporte=='ORP'.$ord_tipo[1]){echo 'checked=true';}?>></td>
			
		</tr>
		<?php }?>

		
		<tr>
			<td class=tb-head  >&#8226; M&oacute;dulo de Tesoreria</td>
			<td colspan="1" class=tb-head ><INPUT type="checkbox"  name="TES" value="1" size="100" <?php if ($cod_reporte=='TESORERIA'){echo 'checked=true';}?>></td>
		</tr>
		<tr>
			<td class=tb-head  > &#8226; M&oacute;dulo de Caja </td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="CAJ" value="1" size="100" <?php if ($cod_reporte=='CAJA'){echo 'checked=true';}?>></td>
			
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
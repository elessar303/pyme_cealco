<?php
require_once '../lib/common.php';
$conexion=conexion_conf();

$url="usuarios_list";
$modulo="Usuarios";
$tabla="usuarios";
$titulos=array("Nombre","Nombre de Usuario");
//$indices=array("2","1","3");
$indices=array("2","1","3","15","29","5","13","12","27","28","11","16","17","19","18","20","21","6","25","26","8","22","24","23","9","30","31","32","33","34","35","36","37","38","10");

if(isset($_POST['aceptar']))
{
	
	if (($_POST['NOM_USU'] == '') || ($_POST['LOG_USR'] == '') || ($_POST['PAS_USR'] == '') || ($_POST['CODIGOUNIDAD'] == '') || ($_POST['CODCCOS'] == '' )  )
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Datos imcompletos, no se puede realizar la operacion\")";
		echo "</SCRIPT>";
	}
	else
	{	
		$consulta="select * from ".$tabla;
		$resultado=query($consulta,$conexion);

	
		$cadena="";
		foreach($indices as $valor)
		{
			$campo=mysql_field_name($resultado,$valor);
			if($cadena=="")
			{
				$cadena=$cadena.$campo."='".$_POST[$campo]."'";
			}
			else
			{
				$cadena=$cadena.",".$campo."='".$_POST[$campo]."'";
			}
		}
	
		$consulta="update ".$tabla." set ".$cadena." where cod_usuario='".$_POST["codigo"]."'";
		
		$resultado=query($consulta,$conexion);
	
		cerrar_conexion($conexion);
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		location.href=\"".$url.".php?pagina=1\"
		</SCRIPT>";
	}
}
	//$codigo = @$_GET['codigo'];
	if($_POST['codigo'])
	{
		$codigo = $_POST['codigo'];
	}
	else
	{
		$codigo = @$_GET['codigo'];
	}
	
	$consulta="select * from ".$tabla." where cod_usuario='".$codigo."'";
	//echo $consulta;
	$resultado=query($consulta,$conexion);
	$fila=fetch_array($resultado);

?>

<html class="fondo">

<head>
  <title></title>
  <link href="../estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
  </SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	location.href=retorno+".php?pagina=1"
}
function actualizar_administrador(){
	if (document.sampleform.ADMIN.checked==true){
		
		document.sampleform.ACC_AUD.checked=true;
		document.sampleform.ACC_REQ.checked=true;
		document.sampleform.ACC_ODC.checked=true;
		document.sampleform.ACC_PRE.checked=true;
		document.sampleform.ACC_CAJ.checked=true;
		document.sampleform.ACC_ODP.checked=true;
		document.sampleform.ACC_CHQ.checked=true;
		document.sampleform.PROVEEDORES.checked=true;
		document.sampleform.PRESUPUESTO.checked=true;
		document.sampleform.ORDENES.checked=true;
		document.sampleform.COMPROMETER.checked=true;
		document.sampleform.CAUSAR.checked=true;
		document.sampleform.DCOMPROMETER.checked=true;
		document.sampleform.DCAUSAR.checked=true;
		document.sampleform.DECRETOS.checked=true;
		document.sampleform.AODP.checked=true;
		document.sampleform.ODP.checked=true;
		document.sampleform.CHEQUEODP.checked=true;
		document.sampleform.MOVBAN.checked=true;
		document.sampleform.CHEQUETER.checked=true;
		document.sampleform.EREQ.checked=true;
		document.sampleform.ECOMPRAS.checked=true;
		document.sampleform.SERVICIO.checked=true;
		document.sampleform.CONTRATO.checked=true;
		document.sampleform.AREQ.checked=true;
		document.sampleform.ACOMPROMISOS.checked=true;
		//permiso instructivo
		document.sampleform.INSTRUCTIVO.checked=true;
		//presupuesto de ingreso
		document.sampleform.INGRESOP.checked=true;
		document.sampleform.ACC_BIE.checked=true;
		
	}
	else{
		document.sampleform.ACC_AUD.checked=false;
		document.sampleform.ACC_REQ.checked=false;
		document.sampleform.ACC_ODC.checked=false;
		document.sampleform.ACC_PRE.checked=false;
		document.sampleform.ACC_CAJ.checked=false;
		document.sampleform.ACC_ODP.checked=false;
		document.sampleform.ACC_CHQ.checked=false;
		document.sampleform.PROVEEDORES.checked=false;
		document.sampleform.PRESUPUESTO.checked=false;
		document.sampleform.ORDENES.checked=false;
		document.sampleform.COMPROMETER.checked=false;
		document.sampleform.CAUSAR.checked=false;
		document.sampleform.DCOMPROMETER.checked=false;
		document.sampleform.DCAUSAR.checked=false;
		document.sampleform.DECRETOS.checked=false;
		document.sampleform.AODP.checked=false;
		document.sampleform.ODP.checked=false;
		document.sampleform.CHEQUEODP.checked=false;
		document.sampleform.MOVBAN.checked=false;
		document.sampleform.CHEQUETER.checked=false;
		document.sampleform.EREQ.checked=false;
		document.sampleform.ECOMPRAS.checked=false;
		document.sampleform.SERVICIO.checked=false;
		document.sampleform.CONTRATO.checked=false;
		document.sampleform.AREQ.checked=false;
		document.sampleform.ACOMPROMISOS.checked=false;
		//persmiso instructivo
		document.sampleform.INSTRUCTIVO.checked=false;
		//permiso presupuesto de ingreso
		document.sampleform.INGRESOP.checked=false
		document.sampleform.ACC_BIE.checked=false;
	}
}
</SCRIPT>

<script language="JavaScript" type="text/JavaScript">
function contra(){
	if(this.document.forms.sampleform.PAS_USR.value==this.document.forms.sampleform.PAS_USR2.value){
	this.document.forms.sampleform.PAS_USR.value;
	}else{
	alert("Las contraseñas introducidas no coinciden");
	this.document.forms.sampleform.PAS_USR.value="";
	this.document.forms.sampleform.PAS_USR2.value="";
	this.document.forms.sampleform.PAS_USR.focus();
	}
}
</script>

</head>
<body>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>

<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Editar un Registro de <?echo $modulo?></strong></td>
<INPUT type="hidden" name="codigo" value="<?echo $codigo?>">
    </tr>

	
		<TR><td class=tb-head colspan="2" align="center" width="180">COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</td></tr>
		<TR><td class=tb-head >Nombre&nbsp;**</td>
		<td><INPUT type="text" name="NOM_USU" size="70" value='<? echo "$fila[NOM_USU]";?>'></td> </tr>
      	<TR><td class=tb-head >Nombre de Usuario&nbsp;**</td>
		<td><INPUT type="text" name="LOG_USR" value='<? echo "$fila[LOG_USR]";?>' size="70"></td> </tr>
        <TR><td class=tb-head >Contrase&ntilde;a&nbsp;**</td>
		<td colspan="3"><INPUT type="password"name="PAS_USR" size="70"></td> </tr>
		<TR><td class=tb-head >Confirmar Contrase&ntilde;a&nbsp;**</td>
		<td colspan="3"><INPUT type="password" name="PAS_USR2" size="70" onBlur="javascript:contra()"></td></tr>
		
       
	
            <TR><td class=tb-head >A que Unidad Administrativa o Departamento pertenece?&nbsp;**</td>
			<td colspan="3">
			<?php 
				$conexionsele=conexion();
				$consultaa="select *  from unidades ORDER BY cod_unidad";
				$resultadoo=query($consultaa,$conexionsele);
				echo "<SELECT name=\"CODIGOUNIDAD\" id=\"CODIGOUNIDAD\">";
				echo "<option value='' selected='selected'> Ninguno</option>";
				while( $fila_=fetch_array($resultadoo)){
					if($fila_['cod_unidad']==$fila['CODIGOUNIDAD']){
						echo "<option selected=\"true\" value=".$fila_['cod_unidad'].">".$fila_['cod_unidad']." - ".$fila_['descripcion']."</option>";
					}
					else{
						echo "<option value=".$fila_['cod_unidad'].">".$fila_['cod_unidad']." - ".$fila_['descripcion']."</option>";
					}
				}
				echo "</SELECT>  ";
				
			?>
			</td></tr>
            <TR><td class=tb-head >A que Centro de Costo pertenece?&nbsp;**</td>
			<td colspan="3">
			<?php 
				
				$consultaa="select *  from centros ORDER BY descripcion";
				$resultadoo=query($consultaa,$conexionsele);
				?>
				<SELECT name="CODCCOS" id="CODCCOS">
				<?php
				echo "<option value='' selected='selected'> Ninguno</option>";
				while( $fila_=fetch_array($resultadoo)){
					if ($fila_['cod_centro']==$fila['CODCCOS']){	
						echo "<option selected=\"true\" value=".$fila_['cod_centro'].">".$fila_['descripcion']."</option>";
					}else{
						echo "<option value=".$fila_['cod_centro'].">".$fila_['descripcion']."</option>";
					}
				}
				echo "</SELECT>  ";
				
			?>
			</td></tr>

		



</tbody>
</table>
<table  width="100%" >
	    <tr><td colspan="7" height="30" class="tb-tit"><strong>Accesos al Sistema: </strong></td></tr>	
		<tr>
		<td align="center"><strong>Administrador</strong>
		<INPUT onclick="actualizar_administrador()" type="checkbox"  name="ADMIN" value="1" <? if( $fila[ADMIN]){ ?>checked <? } ?>	 size="100">
		</TD>
		</tr>
</table>
<table  width="350"   >

		<tr  >
			<td class=tb-head  >&#8226; M&oacute;dulo de Configuraci&oacute;n</td>
			<td colspan="1"  width="20" class=tb-head ><INPUT type="checkbox"  name="ACC_AUD" value="1" <? if( $fila[ACC_AUD]){ ?>checked <? } ?>	 size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  >&#043; M&oacute;dulo de Requisiciones</td>
			<td colspan="1" class=tb-head ><INPUT type="checkbox"  name="ACC_REQ" value="1" <? if( $fila[ACC_REQ]){ ?>checked <? } ?>	 size="100"></td>
		</tr>
		<tr >
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Enviar Requisiciones</td>
			<td colspan="1" ><INPUT type="checkbox"  name="EREQ" value="1" <? if( $fila[EREQ]){ ?>checked <? } ?> size="100"></td>
			
		</tr>
		<tr >
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Autorizar Requisiciones</td>
			<td colspan="1" ><INPUT type="checkbox"  name="AREQ" value="1" <? if( $fila[AREQ]){ ?>checked <? } ?> size="100"></td>
			
		</tr>
		<tr>
			
			<td class=tb-head  > &#043; M&oacute;dulo de Compras y Servicios</td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="ACC_ODC" value="1" <? if( $fila[ACC_ODC]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head>&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Proveedores</td>
			<td colspan="1" ><INPUT type="checkbox"  name="PROVEEDORES" value="1" <? if( $fila[PROVEEDORES]){ ?>checked <? } ?> size="100"></td>	
		</tr>
		<tr>
			<td class=tb-head > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Emitir Ordes de Compras - Servicios</td>
			<td colspan="1" ><INPUT type="checkbox"  name="ORDENES" value="1" <? if( $fila[ORDENES]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head   > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Emitir Ordenes de Compras</td>
			<td colspan="1" ><INPUT type="checkbox"  name="ECOMPRAS" value="1" <? if( $fila[ECOMPRAS]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Emitir Ordenes de Servicios</td>
			<td colspan="1" ><INPUT type="checkbox"  name="SERVICIO" value="1" <? if( $fila[SERVICIO]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Emitir Ordes de Contrato</td>
			<td colspan="1" ><INPUT type="checkbox"  name="CONTRATO" value="1" <? if( $fila[CONTRATO]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  >&#043; M&oacute;dulo Ejecuci&oacute;n de Presupuesto</td>
			<td colspan="1" class=tb-head ><INPUT type="checkbox"  name="ACC_PRE" value="1" <? if( $fila[ACC_PRE]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>	
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Opcion de Presupuesto</td>
			<td colspan="1" ><INPUT type="checkbox"  name="PRESUPUESTO" value="1" <? if( $fila[PRESUPUESTO]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Comprometer</td>
			<td colspan="1" ><INPUT type="checkbox"  name="COMPROMETER" value="1" <? if( $fila[COMPROMETER]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Autorizar Compromisos</td>
			<td colspan="1" ><INPUT type="checkbox"  name="ACOMPROMISOS" value="1" <? if( $fila[ACOMPROMISOS]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head  >&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Causar</td>
			<td colspan="1" ><INPUT type="checkbox"  name="CAUSAR" value="1" <? if( $fila[CAUSAR]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Descomprometer</td>
			<td colspan="1" ><INPUT type="checkbox"  name="DCOMPROMETER" value="1" <? if( $fila[DCOMPROMETER]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Descausar</td>
			<td colspan="1" ><INPUT type="checkbox"  name="DCAUSAR" value="1" <? if( $fila[DCAUSAR]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  >&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Decretos</td>
			<td colspan="1" ><INPUT type="checkbox"  name="DECRETOS" value="1" <? if( $fila[DECRETOS]){ ?>checked <? } ?> size="100"></td>
			
		</tr>
		
		<tr >
			<td class=tb-head >&#043; M&oacute;dulo de Ordenes de Pago</td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="ACC_ODP" value="1" <? if( $fila[ACC_ODP]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Hacer de Ordenes de Pago</td>
			<td colspan="1" ><INPUT type="checkbox"  name="ODP" value="1" <? if( $fila[ODP]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head  >&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Autorizar de Ordenes de Pago</td>
			<td colspan="1" ><INPUT type="checkbox"  name="AODP" value="1" <? if( $fila[AODP]){ ?>checked <? } ?> size="100"></td>
		
		</tr>
		<tr>
			<td class=tb-head  > &#043; M&oacute;dulo de Tesoreria</td>
			<td colspan="1" class=tb-head ><INPUT type="checkbox"  name="ACC_CHQ" value="1" <? if( $fila[ACC_CHQ]){ ?>checked <? } ?> size="100"> </td>
		</tr>
		<tr>
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Hacer Cheques por ODP</td>
			<td colspan="1" ><INPUT type="checkbox"  name="CHEQUEODP" value="1" <? if( $fila[CHEQUEODP]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td  class=tb-head  >&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Movimientos Bancarios</td>
			<td colspan="1" ><INPUT type="checkbox"  name="MOVBAN" value="1" <? if( $fila[MOVBAN]){ ?>checked <? } ?> size="100"></td>
			
		</tr>
		<tr>
			<td  class=tb-head  > &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Hacer Cheques por Terceros</td>
			<td colspan="1" ><INPUT type="checkbox"  name="CHEQUETER" value="1" <? if( $fila[CHEQUETER]){ ?>checked <? } ?> size="100"></td>
		</tr>
		<tr>
			<td class=tb-head  > &#8226; M&oacute;dulo de Caja (Entrega Cheques)</td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="ACC_CAJ" value="1" <? if( $fila[ACC_CAJ]){ ?>checked <? } ?> size="100"></td>
			
		</tr>
		<tr>
			<td class=tb-head  > &#8226; M&oacute;dulo Instructivo</td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="INSTRUCTIVO" value="1" <? if( $fila[INSTRUCTIVO]){ ?>checked <? } ?> size="100"></td>
			
		</tr>
		<tr>
			<td class=tb-head  > &#8226; M&oacute;dulo Presupuesto de Ingreso</td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="INGRESOP" value="1" <? if( $fila[INGRESOP]){ ?>checked <? } ?> size="100"></td>
			
		</tr>
		<tr>
			<td class=tb-head  > &#8226; Sistema de Bienes</td>
			<td colspan="1" class=tb-head><INPUT type="checkbox"  name="ACC_BIE" value="1" ></td>
			
		</tr>
		
</table>
<TABLE width="100%">
<TBODY>
    <tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onClick="javascript:cerrar('<?echo $url?>');"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);

?>

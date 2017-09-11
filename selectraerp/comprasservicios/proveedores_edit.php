<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;

$cod_proveedores=@$_GET['codigo'];
$url="proveedores_list";
$modulo="Proveedores";
$tabla="proveedores";
$titulos=array("Compañía","Siglas","R.I,F.","N.I.T.","Dirección","Dirección2","Email","Web","Representante Legal","Cédula","Teléfono","Días de Credito","Fecha Registro","Fecha vencimiento","Declara","Cuenta bancaria","Registro","Fecha","Número","Tomo","Capital Suscrito","Capital Pagado","Observaciones","Contraloría","Contraloría Fecha","Status");

$indices=array("1","2","3","4","5","6","14","15","9","11","10","13","24","25","20","22","23","25","26","27","28","29","30","31","32","42");

if(isset($_POST['aceptar'])){
	
	$consulta="select * from ".$tabla;
	$resultado= query($consulta,$conexion);

	$indices=array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","33","34","35","36","37","38","39","40","41","42");
	$cadena="";
	foreach($indices as $valor){
		$campo=mysql_field_name($resultado,$valor);
		if($cadena==""){
			
			$cadena=$cadena.$campo."='".$_POST[$campo]."'";
		}
		else{
			$cadena=$cadena.",".$campo."='".$_POST[$campo]."'";
		}
	}
	
	$consulta="update ".$tabla." set ".$cadena." where cod_proveedor=".$_POST["codigo"];
	//echo $consulta;
//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Se ha editado el Correctamente el registro\")
	parent.cont.location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
	

}
	$codigo = @$_GET['codigo'];
	//$descripcion= @$_GET['descripcion'];
	$consulta="select * from ".$tabla." where cod_proveedor=".$codigo;
	//echo $consulta;
	$resultado=query($consulta, $conexion);
	//echo $resultado;
	$fila=fetch_array($resultado);
	//echo $fila;
?>

<html class="fondo">

<head>
  <title></title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
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

<?

$consulta_estado="select * from estados";
$resultado_estado=query($consulta_estado,$conexion);

$consulta_islr="select * from impuestos_islr";
$resultado_islr=query($consulta_islr,$conexion);

$consulta_clasificacion="select * from clasificacion_unica";
$resultado_clasificacion=query($consulta_clasificacion,$conexion);

$consulta_cuentacontable="select * from cwconcue where Tipo='P'";
$resultado_cuentacontable=query($consulta_cuentacontable,$conexion);
?>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<TABLE  width="100%" height="100">
<TBODY>
<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Editar Registro de <?echo $modulo?></strong></td>

    </tr>
<TR><td class=tb-head width="140">C&oacute;digo</td><td><INPUT type="text" name="codigo" size="100" readonly="true" value="<?echo $codigo?>"></td> </tr>

	<?
	$i=0;
	$cont=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
	//echo $campo;
		echo "<TR>";?><td class=tb-head ><?echo "$nombre"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="100" value='<?echo "$fila[$campo]";?>'></td> </tr><?
		$i++;
		$cont++;
		if($cont==6){
		$estado = $fila['cod_estado'];
		$conEstado = "SELECT * FROM estados WHERE cod_estado='".$estado."'";
		$resEstado = query($conEstado, $conexion);
		$filaEstado = fetch_array($resEstado);
		$nomEstado = $filaEstado['nombre'];
		echo "<TR>";?><td class=tb-head ><?echo "Estado</td>";
		//echo $nomEstado;
		echo "<td colspan=\"3\"><SELECT name=\"cod_estado\" id=\"cod_estado\" onchange=\"javascript:cargar_municipio()\">";
		echo "<option value=\"0\" >Seleccione un Estado</option>";
                           while($fila_estado=fetch_array($resultado_estado)){
				$codigo_estado=$fila_estado['cod_estado'];
				$descripcion_estado=$fila_estado['nombre'];
				if($descripcion_estado==$nomEstado){
					echo "<option selected=\"true\" value=\"$codigo_estado\">$descripcion_estado</option>";
				}else{
					echo "<option value=\"$codigo_estado\">$descripcion_estado</option>";
				}
			}
      	echo "</SELECT></td> </tr>";
		$municipio = $fila['cod_municipio'];
		$conMunic = "SELECT * FROM municipios WHERE cod_municipio='".$municipio."' AND cod_estado='".$estado."'";
		$resMunic = query($conMunic, $conexion);
		$filaMunic = fetch_array($resMunic);
		$nomMunic = $filaMunic['nombre'];
		
			echo "<TR>";?><td class=tb-head ><?echo "Municipio</td>";
			echo "<td colspan=\"3\"><SELECT name=\"cod_municipio\" id=\"cod_municipio\">";
			echo "<option class='tb-fila' value=\"$nomMunic\">$nomMunic</option>";
         //echo "<option value=\"0\">Seleccione un Municipio</option>";
      	echo "</SELECT></td> </tr>";
		}
		if($cont==6){
		$clasif = $fila['clasificacion'];
		$conClasif = "SELECT * FROM clasificacion_unica WHERE codigo='".$clasif."'";
		$resClasif = query($conClasif, $conexion);
		$filaClasif = fetch_array($resClasif);
		$nomClasif = $filaClasif['descripcion'];
		echo "<TR>";?><td class=tb-head ><?echo "Clasificacion</td>";
		echo "<td colspan=\"3\"><SELECT name=\"clasificacion\" id=\"clasificacion\">";
		echo "<option value=\"0\" ></option>";
                           while($fila_clasificacion=fetch_array($resultado_clasificacion)){
					$codigo_clasificacion=$fila_clasificacion['codigo'];
					$descripcion_clasificacion=$fila_clasificacion['descripcion'];
					if ($descripcion_clasificacion==$nomClasif){
					echo "<option selected=\"true\" value=\"$codigo_clasificacion\">$descripcion_clasificacion</option>";
					}else{
					echo "<option value=\"$codigo_clasificacion\">$descripcion_clasificacion</option>";
					}
			}
		echo "</SELECT></td> </tr>";
		}
		if($cont==13){
		$cuenta_contable = $fila['cuenta_contable'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Contable</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cuenta_contable\" id=\"cuenta_contable\">";
		echo "<option value=\"0\" ></option>";
                        while($fila_ctactable=fetch_array($resultado_cuentacontable)){
				$codigo_cta=$fila_ctactable['Cuenta'];
				$descripcion=$fila_ctactable['Descrip'];
				if($codigo_cta==$cuenta_contable){
				echo "<option selected =\"true\" title=\"$descripcion\" value=\"$codigo_cta\">$codigo_cta</option>";
				}
				else{
				echo "<option title=\"$descripcion\" value=\"$codigo_cta\">$codigo_cta</option>";
				}
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==16)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Datos SENIAT</strong></td>";
		$persona = $fila['tipo_persona'];
		echo "<TR>";?><td class=tb-head ><?echo "Persona</td>";
		echo "<td colspan=\"2\">Natural"; ?> <INPUT <?if ($persona=='N'){ echo "checked=\"true\" ";} echo "  type=\"radio\" name=\"tipo_persona\" id=\"tipo_persona\" value=\"N\"";?>>Juridica 
		<INPUT <?if ($persona=='j'){ echo "checked=true";} echo " type=\"radio\" name=\"tipo_persona\" value=\"j\">";
		//echo "</td> </tr>";
		$residencia = $fila['tipo_residencia'];
		echo "<TR>";?><td class=tb-head ><?echo "Residencia</td>";
		echo "<td colspan=\"2\">Domiciliada Residente"; ?> <INPUT <?if ($residencia=='D'){ echo "checked=\"true\" ";} echo " type=\"radio\" name=\"tipo_residencia\" id=\"tipo_residencia\" value=\"D\"";?>>No Domiciliada  No Residente<INPUT <?if ($residencia=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"tipo_residencia\" value=\"N\">";
		//echo "</td> </tr>";
		$tipo = $fila['tipo_compania'];
		echo "<TR>";?><td class=tb-head ><?echo "Tipo</td>";
		echo "<td colspan=\"2\">Proveedor"; ?> <INPUT <?if ($tipo=='P'){ echo "checked=\"true\" ";} echo " type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"P\">";?>Contratista<INPUT <?if ($tipo=='C'){ echo "checked=\"true\" ";} echo " type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"C\">";?>Cooperativa<INPUT <?if ($tipo=='I'){ echo "checked=\"true\" ";} echo " type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"I\">"; ?>Fundacion<INPUT <?if ($tipo=='F'){ echo "checked=\"true\" ";} echo " type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"F\">";?>Otros<INPUT <?if ($tipo=='O'){ echo "checked=\"true\" ";} echo " type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"O\">";

		//echo "</td> </tr>";
		echo "<TR>";?><td class=tb-head ><?echo "Tipo de Retención</td>";
//tipo de retencion de impuesto sobre la renta*****************************+		
		$rentencion = $fila['cod_impuesto_isrl'];
		$conRetencion = "SELECT * FROM impuestos_islr WHERE cod_impuesto_isrl='".$rentencion."'";
		$resRetencion = query($conRetencion, $conexion);
		$filaRetencion = fetch_array($resRetencion);
		$nomRetencion = $filaRetencion['descripcion'];
		echo "<td colspan=\"3\"><SELECT name=\"cod_impuesto_isrl\" >";
		echo "<option value=\"0\" >Ninguno</option>";
                           while($fila_islr=fetch_array($resultado_islr)){
				$codigo_islr=$fila_islr['cod_impuesto_isrl'];
				$descripcion_islr=$fila_islr['descripcion'];
				if($nomRetencion==$descripcion_islr){
					echo "<option selected=\"true\" value=\"$codigo_islr\">$descripcion_islr</option>";
				}else{
					echo "<option value=\"$codigo_islr\">$descripcion_islr</option>";
				}
			}
      		echo "</SELECT></td> </tr>";
		}
		
		if($cont==23)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Datos T&eacute;cnicos</strong></td>";
		}
		if($cont==26)
		{
		$c_registro = $fila['doc_copia_registro'];
		echo "<TR>";?><td class=tb-head ><?echo "Copia Registro</td>";
		echo "<td colspan=\"2\">Si";?><INPUT <?if ($c_registro=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_copia_registro\" id=\"doc_copia_registro\" value=\"S\">";?>No<INPUT <?if ($c_registro=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_copia_registro\" id=\"doc_copia_registro\" value=\"N\">";
		
		$c_publicacion = $fila['doc_copia_publicacion'];
		echo "<TR>";?>
		<td class=tb-head ><?echo "Copia Publicacion</td>";
		echo "<td colspan=\"2\">Si"; ?>
		<INPUT <?if ($c_publicacion=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_copia_publicacion\" id=\"doc_copia_publicacion\" value=\"S\">";?>
		No<INPUT <?if ($c_publicacion=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_copia_publicacion\" id=\"doc_copia_publicacion\" value=\"N\">";

		$c_rif = $fila['doc_copia_rif'];
		echo "<TR>";?><td class=tb-head ><?echo "Copia RIF</td>";
		echo "<td colspan=\"2\">Si";?><INPUT <?if ($c_rif=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_copia_rif\" id=\"doc_copia_rif\" value=\"S\">";?>No<INPUT <?if ($c_rif=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_copia_rif\" id=\"doc_copia_rif\" value=\"N\">";
		
		$c_datos = $fila['doc_datos_tecnicos'];
		echo "<TR>";?><td class=tb-head ><?echo "Datos Tecnicos</td>";
		echo "<td colspan=\"2\">Si"; ?><INPUT <?if ($c_datos=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_datos_tecnicos\" id=\"doc_datos_tecnicos\" value=\"S\">";?>No<INPUT <?if ($c_datos=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_datos_tecnicos\" id=\"doc_datos_tecnicos\" value=\"N\">";
		
		$c_sanitario = $fila['doc_registro_sanitario'];
		echo "<TR>";?><td class=tb-head ><?echo "Registro Sanitario</td>";
		echo "<td colspan=\"2\">Si";?><INPUT <?if ($c_sanitario=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_registro_sanitario\" id=\"doc_registro_sanitario\" value=\"S\">";?>No<INPUT <?if ($c_sanitario=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_registro_sanitario\" id=\"doc_registro_sanitario\" value=\"N\">";
		
		$c_alcaldia = $fila['doc_solvencia_alcaldia'];
		echo "<TR>";?><td class=tb-head ><?echo "Solvencia Alcaldia</td>";
		echo "<td colspan=\"2\">Si";?><INPUT <?if ($c_alcaldia=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_solvencia_alcaldia\" id=\"doc_solvencia_alcaldia\" value=\"S\">";?>No<INPUT <?if ($c_alcaldia=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_solvencia_alcaldia\" id=\"doc_solvencia_alcaldia\" value=\"N\">";
		
		$c_balance = $fila['doc_balance'];
		echo "<TR>";?><td class=tb-head ><?echo "Balance</td>";
		echo "<td colspan=\"2\">Si";?><INPUT <?if ($c_balance=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_balance\" id=\"doc_balance\" value=\"S\">";?>No<INPUT <?if ($c_balance=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_balance\" id=\"doc_balance\" value=\"N\">";
		
		$c_ope = $fila['doc_ope'];
		echo "<TR>";?><td class=tb-head ><?echo "OPE</td>";
		echo "<td colspan=\"2\">Si";?><INPUT <?if ($c_ope=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_ope\" id=\"doc_ope\" value=\"S\">";?>No<INPUT <?if ($c_ope=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_ope\" id=\"doc_ope\" value=\"N\">";
		
		$c_ocei = $fila['doc_ocei'];

		echo "<TR>";?><td class=tb-head ><?echo "OCEI</td>";
		echo "<td colspan=\"2\">Si";?><INPUT <?if ($c_ocei=='S'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_ocei\" id=\"doc_ocei\" value=\"S\">";?>No<INPUT <?if ($c_ocei=='N'){ echo "checked=true";} echo " type=\"radio\" name=\"doc_ocei\" id=\"doc_ocei\" value=\"N\">";
		}
	}
?>
    </td><tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:cerrar('<?echo $url?>');"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);
?>
<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$cod_proveedores=@$_GET['codigo'];
$url="proveedores_list";
$modulo="Proveedores";
$tabla="proveedores";
$titulos=array("Compañía","Siglas","N.I.T.","Dirección 1","Dirección 2","E-mail","Página Web","Representante Legal","Cédula","Teléfono","Días de Crédito","Declara","Cuenta Bancaria","Registro","Fecha","Vence","Número","Tomo","Capital Suscrito","Capital Pagado","Observaciones","Contraloría","Contraloría Fecha","Status");
$indices=array("1","2","4","5","6","14","15","9","11","10","13","20","22","23","24","25","26","27","28","29","30","31","32","42");

if(isset($_POST['aceptar']))
{
	
	if (($_POST['cod_proveedor'] == '') || ($_POST['compania'] == '') || ($_POST['rif'] == '') || ($_POST['tipo_persona'] == '')  )
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Datos imcompletos, no se puede realizar la operacion!!\")
		location.href=\"proveedores_list.php\"";
		echo "</SCRIPT>";
		exit(0);
		
	}
	else
	{	
	
	
	$conProv = "SELECT * FROM proveedores WHERE rif='".$_POST['rif']."'";
	$resProv = query($conProv, $conexion);
	$regProv = num_rows($resProv);
	if($regProv > 0)
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"No se pudo registrar el proveedor. Su identificación ya existe\")
		parent.cont.location.href=\"".$url.".php?pagina=1\"
		</SCRIPT>";
		exit(0);
	}
	
	$consulta="select * from ".$tabla;
	$resultado=mysql_query($consulta);
	//if((!$_POST['nombre_banco'])||(!$_POST['numero_cuenta'])||(!$_POST['firstinput'])){
	//	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	//	alert(\"Datos imcompletos, no se puede realizar la operacion\")";
	//	echo "<//SCRIPT>";
	//	
	//}

	$indices=array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","33","34","35","36","37","38","39","40","41","42");
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
	$consulta="select max(cod_proveedor) as valor from proveedores";
	$resultado_proveedores=query($consulta,$conexion);

	$fila_proveedores=fetch_array($resultado_proveedores);
	$max_proveedores=$fila_proveedores['valor'];
	$valor=$max_proveedores+1;
	
	$cadena_campos.=",cod_proveedor";
	$cadena_valores.=",'".$valor."'";
/*
	$cadena_campos=$cadena_campos.",registro_fecha";
	$cadena_valores=$cadena_valores.",'".fecha_sql($_POST['registro_fecha'])."'";
	$cadena_campos=$cadena_campos.",registro_vence";
	$cadena_valores=$cadena_valores.",'".fecha_sql($_POST['registro_vence'])."'";
	$cadena_campos=$cadena_campos.",contraloria_fecha";
	$cadena_valores=$cadena_valores.",'".fecha_sql($_POST['contraloria_fecha'])."'";
*/
	$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.")";
	
	$resultado=query($consulta,$conexion);

	cerrar_conexion($conexion);
	$val=$_POST['cod_unidad'];
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
	}
}
else
{
	$consulta="select * from ".$tabla;
	$resultado=query($consulta,$conexion);
}
?>


<FORM name="<? echo $url?>" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>
<?
$consulta="select max(cod_proveedor) as valor from proveedores";
$resultado_proveedores=query($consulta,$conexion);
//echo $consulta;
$fila_proveedores=fetch_array($resultado_proveedores);
$max_proveedores=$fila_proveedores['valor'];
$valor=$max_proveedores+1;

$consulta_estado="select * from estados";
$resultado_estado=query($consulta_estado,$conexion);

$consulta_islr="select * from impuestos_islr";
$resultado_islr=query($consulta_islr,$conexion);

$consulta_clasificacion="select * from clasificacion_unica";
$resultado_clasificacion=query($consulta_clasificacion,$conexion);

$consulta_cuentacontable="select * from cwconcue where Tipo='P'";
$resultado_cuentacontable=query($consulta_cuentacontable,$conexion);
?>

<tr>
      <td colspan="2" class="row-br"><? titulo("Agregar un Nuevo Proveedor","","proveedores_list.php","28");?></td>
    </tr>
	<td class="tb-head" width="140">C&oacute;digo Proveedor</td>
      	<td colspan="6"><INPUT type="text" readonly="true" name="cod_proveedor" value="<?echo "$valor";?>"></td>
	
	<?
	$i=0;
	$cont=0;
	foreach($titulos as $nombre)
	{
		
		$campo = mysql_field_name($resultado,$indices[$i]);
	
		echo "<TR>";?><td class=tb-head ><? echo "$nombre</td>";
		echo "<td colspan=\"3\"><INPUT type=\"text\" name=\"$campo\" size=\"100\" value=\"\"></td> </tr>";
		$i++;
		$cont++;
		if ($cont == 2)
		{
			echo "<TR>";?><td class=tb-head ><? echo "R.I.F.</td>";
			echo "<td><INPUT type=\"text\" name=\"rif\" id=\"rif\" value=\"\"></td></TR>";
		}
		if($cont==6)
		{
			echo "<TR>";?><td class=tb-head ><? echo "Estado</td>";
			echo "<td colspan=\"3\"><SELECT name=\"cod_estado\" id=\"cod_estado\" onchange=\"javascript:cargar_municipio()\">";
			echo "<option seleted=\"true\" value=\"0\">Seleccione un Estado</option>";
            		while($fila_estado=fetch_array($resultado_estado))
			{
				$codigo_estado=$fila_estado['cod_estado'];
				$descripcion_estado=$fila_estado['nombre'];
				echo "<option value=\"$codigo_estado\">$descripcion_estado</option>";
			}
      		echo "</SELECT></td> </tr>";
			echo "<TR>";?><td class=tb-head ><? echo "Municipio</td>";
			echo "<td colspan=\"3\"><SELECT name=\"cod_municipio\" id=\"cod_municipio\">";
            echo "<option value=\"0\">Seleccione un Municipio</option>";
      		echo "</SELECT></td> </tr>";
		}
		if($cont==6)
		{
			echo "<TR>";?><td class=tb-head ><?	echo "Clasificaci&oacute;n</td>";
			echo "<td colspan=\"3\"><SELECT name=\"clasificacion\" id=\"clasificacion\">";
			echo "<option value=\"0\" seleted=\"true\">Seleccione una Clasificación</option>";
            		while($fila_clasificacion=fetch_array($resultado_clasificacion))
			{
				$codigo_clasificacion=$fila_clasificacion['codigo'];
				$descripcion_clasificacion=$fila_clasificacion['descripcion'];
				echo "<option value=\"$codigo_clasificacion\">$descripcion_clasificacion</option>";
			}
			echo "</SELECT></td> </tr>";
		}
		if($cont==13){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Contable</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cuenta_contable\" id=\"cuenta_contable\">";
			echo "<option value=\"0\" seleted=\"true\">Seleccione una Cuenta Contable</option>";
                        while($fila_ctactable=fetch_array($resultado_cuentacontable)){
				$codigo_cta=$fila_ctactable['Cuenta'];
				$descripcion=$fila_ctactable['Descrip'];
				echo "<option title=\"$descripcion\" value=\"$codigo_cta\">$codigo_cta</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==13)
		{
			echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Datos SENIAT</strong></td>";
			echo "<TR>";?><td class=tb-head ><?	echo "Persona</td>";
			echo "<td colspan=\"2\">Natural<INPUT type=\"radio\" name=\"tipo_persona\" id=\"tipo_persona\" value=\"N\" checked=\"true\">Jur&iacute;dica<INPUT type=\"radio\" name=\"tipo_persona\" value=\"j\">";
			//echo "</td> </tr>";
		
			echo "<TR>";?><td class=tb-head ><?	echo "Residencia</td>";
			echo "<td colspan=\"2\">Domiciliada Residente<INPUT type=\"radio\" name=\"tipo_residencia\" id=\"tipo_residencia\" value=\"D\" checked=\"true\">No Domiciliada No Residente<INPUT type=\"radio\" name=\"tipo_residencia\" value=\"N\">";
			//echo "</td> </tr>";
			echo "<TR>";?><td class=tb-head ><?	echo "Tipo</td>";
			echo "<td colspan=\"2\">Proveedor<INPUT type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"P\" checked=\"true\">Contratista<INPUT type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"C\">Cooperativa<INPUT type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"I\">Fundaci&oacute;n<INPUT type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"F\">Otros<INPUT type=\"radio\" name=\"tipo_compania\" id=\"tipo_compania\" value=\"O\">";

		//echo "</td> </tr>";
			echo "<TR>";?><td class=tb-head ><?	echo "Tipo de Retención</td>";
//tipo de retencion de impuesto sobre la renta*****************************+		

			echo "<td colspan=\"3\"><SELECT name=\"cod_impuesto_isrl\" >";
			echo "<option value=\"0\" seleted=\"true\">Seleccione un tipo de Retención</option>";
         	while($fila_islr=fetch_array($resultado_islr))	
			{
				$codigo_islr=$fila_islr['cod_impuesto_isrl'];
				$descripcion_islr=$fila_islr['descripcion'];
				echo "<option value=\"$codigo_islr\">$descripcion_islr</option>";
			}
      		echo "</SELECT></td> </tr>";
		}/*
		if($cont==14){
		echo "<TR>";?><TD class="tb-head"><?echo "Fecha Registro:</TD>";
		echo "<TD width=30><INPUT type=\"text\" name=\"registro_fecha\" id=\"registro_fecha\" size=\"15\" maxlength=\"12\" value=\"".date("d/m/Y")."\"></td><td>&nbsp;<input name=\"d_registro_fecha\" type=\"image\" id=\"d_registro_fecha\" src=\"../lib/jscalendar/cal.gif\">";
  		?>
		<script type="text/javascript">Calendar.setup({inputField:"registro_fecha",ifFormat:"%d/%m/%Y",button:"d_registro_fecha"});</script></TD>
		</TR><?

		echo "<TR>";?><TD class="tb-head"><?echo "Fecha Vencimiento Registro:</TD>";
		echo "<TD width=30><INPUT type=\"text\" name=\"registro_vence\" id=\"registro_vence\" size=\"15\" maxlength=\"12\" value=\"".date("d/m/Y")."\"></td><td>&nbsp;<input name=\"d_registro_vence\" type=\"image\" id=\"d_registro_vence\" src=\"../lib/jscalendar/cal.gif\">";
  		?>
		<script type="text/javascript">Calendar.setup({inputField:"registro_vence",ifFormat:"%d/%m/%Y",button:"d_registro_vence"});</script></TD>
		</TR><?
		}*/
		if($cont==21)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Datos Técnicos</strong></td>";
		}
		if($cont==24)
		{
		echo "<TR>";?><td class=tb-head ><? echo "Copia Registro</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_copia_registro\" id=\"doc_copia_registro\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_copia_registro\" id=\"doc_copia_registro\" value=\"N\">";
		
		echo "<TR>";?><td class=tb-head ><? echo "Copia Publicaci&oacute;n</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_copia_publicacion\" id=\"doc_copia_publicacion\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_copia_publicacion\" id=\"doc_copia_publicacion\" value=\"N\">";

		echo "<TR>";?><td class=tb-head ><? echo "Copia RIF</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_copia_rif\" id=\"doc_copia_rif\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_copia_rif\" id=\"doc_copia_rif\" value=\"N\">";
		
		echo "<TR>";?><td class=tb-head ><? echo "Datos T&eacute;cnicos</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_datos_tecnicos\" id=\"doc_datos_tecnicos\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_datos_tecnicos\" id=\"doc_datos_tecnicos\" value=\"N\">";
		
		echo "<TR>";?><td class=tb-head ><? echo "Registro Sanitario</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_registro_sanitario\" id=\"doc_registro_sanitario\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_registro_sanitario\" id=\"doc_registro_sanitario\" value=\"N\">";
		
		echo "<TR>";?><td class=tb-head ><? echo "Solvencia Alcald&iacute;a</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_solvencia_alcaldia\" id=\"doc_solvencia_alcaldia\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_solvencia_alcaldia\" id=\"doc_solvencia_alcaldia\" value=\"N\">";
		
		echo "<TR>";?><td class=tb-head ><? echo "Balance</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_balance\" id=\"doc_balance\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_balance\" id=\"doc_balance\" value=\"N\">";
		
		echo "<TR>";?><td class=tb-head ><? echo "OPE</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_ope\" id=\"doc_ope\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_ope\" id=\"doc_ope\" value=\"N\">";
		
		echo "<TR>";?><td class=tb-head ><? echo "OCEI</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"doc_ocei\" id=\"doc_ocei\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"doc_ocei\" id=\"doc_ocei\" value=\"N\">";
		}
		
	}
?>
    <tr class="tb-tit">
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
<?php

require_once '../lib/common.php';
include ("../header.php");
$conex = conexion();
//echo $conex;
$consulta="select * from cwconcue";
//echo $consulta_islr;
$resultado_islr=query($consulta,$conex);
$resultado_iva=query($consulta,$conex);
$resultado_tf=query($consulta,$conex);
$resultado_fiel=query($consulta,$conex);
$resultado_laboral=query($consulta,$conex);
$resultado_anticipo=query($consulta,$conex);
$resultado_im=query($consulta,$conex);
$resultado_bombero=query($consulta,$conex);
$resultado_701=query($consulta,$conex);
$resultado_702=query($consulta,$conex);
$resultado_ret_iva=query($consulta,$conex);
$resultado_multa=query($consulta,$conex);

cerrar_conexion($conex);

$conexion=conexion_conf();
//$conex = conexion();
//echo $conexion2;
$cod_empresa=@$_GET['codigo'];
$url="parametroslist";
$modulo="Datos de la Empresa";
$tabla="parametros";
$titulos=array("Nombre","R.I,F.","N.I.T.","Presidente","Periodo","Departamento","Cargo","Direccion","Estado","Ciudad","Telefonos","Descripcion I.S.L.R.","% I.V.A.","Descripción I.V.A.","Descripción Timbre Fiscal","Descripción Fiel Cumplimiento","Descripción Laboral","Descripcion Anticipo","% Impuesto Municipal","Descripción Impuesto Municipal","% Bomberos","Descripción Bombero","Descripcion Retención I.V.A.","Descripción Deducción o Multa","Encabezado1","Encabezado2","Encabezado3","Encabezado4");
$indices=array("1","14","15","3","4","2","5","16","18","17","19","7","11","9","38","40","42","44","20","50","21","52","48","55","29","30","31","32");

if(isset($_POST['aceptar']))
{
	
	$archivo=$HTTP_POST_FILES['imagen_izq']['name'];
	if($archivo!="")
	{
		$nombre_archivo1 = $HTTP_POST_FILES['imagen_izq']['name'];
		$tipo_archivo = $HTTP_POST_FILES['imagen_izq']['type'];
		$tamano_archivo = $HTTP_POST_FILES['imagen_izq']['size'];
		if (copy($HTTP_POST_FILES['imagen_izq']['tmp_name'],"../imagenes/".$nombre_archivo1)){
       		echo "<div align='center' style=\"background-color : #84225b; color : #fdfdfd; font-family : 'Arial Black'; font-size : 15px;\">EL archivo fue cargado exitosamente</div>";
				chmod("../imagenes/".$nombre_archivo1,0777);
    		}else{
       		echo "<div align='center' style=\"background-color : #84225b; color : #fdfdfd; font-family : 'Arial Black'; font-size : 15px;\">Ocurri&oacute; un problema cargando el archivo</div>";
    		}
		/*if(copy($HTTP_POST_FILES['imagen_izq']['tmp_name'],"../../bienes/imagenes/".$nombre_archivo1)){
       		chmod("../../bienes/imagenes/".$nombre_archivo1,0777);
    	}
		if(copy($HTTP_POST_FILES['imagen_izq']['tmp_name'],"../../hacienda/imagenes/".$nombre_archivo1)){
       		chmod("../../hacienda/imagenes/".$nombre_archivo1,0777);
    	}
		if(copy($HTTP_POST_FILES['imagen_izq']['tmp_name'],"../../nomina/imagenes/".$nombre_archivo1)){
       		chmod("../../nomina/imagenes/".$nombre_archivo1,0777);
    	}
		if(copy($HTTP_POST_FILES['imagen_izq']['tmp_name'],"../../contabilidad/frame_archivos/Imagenes/".$nombre_archivo1)){
       		chmod("../../contabilidad/frame_archivos/Imagenes/".$nombre_archivo1,0777);
    	*/
	}

		
	$archivo=$HTTP_POST_FILES['imagen_der']['name'];
	if($archivo!="")
	{
		$nombre_archivo2 = $HTTP_POST_FILES['imagen_der']['name'];
		$tipo_archivo = $HTTP_POST_FILES['imagen_der']['type'];
		$tamano_archivo = $HTTP_POST_FILES['imagen_der']['size'];
		if (copy($HTTP_POST_FILES['imagen_der']['tmp_name'], "../imagenes/".$nombre_archivo2))
		{
       		echo "<div align='center' style=\"background-color : #84225b; color : #fdfdfd; font-family : 'Arial Black'; font-size : 15px;\">EL archivo fue cargado exitosamente</div>";
				chmod("../imagenes/".$nombre_archivo2,0777);
    	}else{
       		echo "<div align='center' style=\"background-color : #84225b; color : #fdfdfd; font-family : 'Arial Black'; font-size : 15px;\">Ocurri&oacute; un problema cargando el archivo</div>";
    	}
		/*if(copy($HTTP_POST_FILES['imagen_der']['tmp_name'],"../../bienes/imagenes/".$nombre_archivo2)){
       		chmod("../../bienes/imagenes/".$nombre_archivo2,0777);
    	}
		if(copy($HTTP_POST_FILES['imagen_der']['tmp_name'],"../../hacienda/imagenes/".$nombre_archivo2)){
       		chmod("../../hacienda/imagenes/".$nombre_archivo2,0777);
    	}
		if(copy($HTTP_POST_FILES['imagen_der']['tmp_name'],"../../nomina/imagenes/".$nombre_archivo2)){
       		chmod("../../nomina/imagenes/".$nombre_archivo2,0777);
    	}
		if(copy($HTTP_POST_FILES['imagen_der']['tmp_name'],"../../contabilidad/frame_archivos/Imagenes/".$nombre_archivo2)){
       		chmod("../../contabilidad/frame_archivos/Imagenes/".$nombre_archivo2,0777);
    	}
		*/
	}	


	$consulta="select * from ".$tabla;
	//echo $consulta;
	$resultado=mysql_query($consulta);
	//if((!$_POST['nombre_banco'])||(!$_POST['numero_cuenta'])||(!$_POST['firstinput'])){
	//	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	//	alert(\"Datos imcompletos, no se puede realizar la operacion\")";
	//	echo "</SCRIPT>";
	//	
	//}
	$indices=array("0","1","14","15","3","4","2","5","16","18","17","19","8","7","10","9","11","20","21","23","24","29","30","31","32","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","57","58");
	foreach($indices as $valor){
		$campo=mysql_field_name($resultado,$valor);
		if($cadena_campos=="" && $cadena_valores==""){
		
			$cadena_campos=$cadena_campos.$campo;
			$cadena_valores=$cadena_valores."'".$_POST[$campo]."'";
		}
		else{
			$cadena_campos=$cadena_campos.",".$campo;
			$cadena_valores=$cadena_valores.",'".$_POST[$campo]."'";
		}
	}
	$cadena_campos=$cadena_campos.",imagen_izq,imagen_der";
	$cadena_valores=$cadena_valores.",'../imagenes/".$nombre_archivo1."','../imagenes/".$nombre_archivo2."'";
	
	$rrs=$_POST['consecutivo_RRS'];
	$isrl=$_POST['consecutivo_ISLR'];
	$tf=$_POST['consecutivo_TF'];
	$im=$_POST['consecutivo_IM'];
	$rp=$_POST['consecutivo_RP'];
	$iva=$_POST['consecutivo_iva'];

	$cadena_campos=$cadena_campos.",consecutivo_RRS,consecutivo_IM,consecutivo_ISLR,consecutivo_TF,consecutivo_RP,consecutivo_iva";
	$cadena_valores=$cadena_valores.",$rrs,$im,$isrl,$tf,$rp,$iva";
	

	$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.")";
	$resultado=query($consulta,$conexion);
	//echo $consulta;
	//exit(0);
	cerrar_conexion($conexion);
	//$val=$_POST['cod_unidad'];
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
	

} else{

	$consulta="select * from ".$tabla;
	//echo $consulta;
	$resultado=query($consulta,$conexion);
	//cerrar_conexion($conexion);
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
<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?pagina=1"
}

</SCRIPT>

</head>
<body>
<?
//$conexion=conexion_conf();
$consulta="select max(codigo) as valor from parametros";
$resultado_parametros=query($consulta,$conexion);
//echo $consulta;
$fila_parametros=fetch_array($resultado_parametros);
$max_parametros=$fila_parametros['valor'];
$valor=$max_parametros+1;
//cerrar_conexion($conexion);



?>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" target="_self">

<TABLE  width="100%" height="100">
<TBODY>

<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Agregar Nuevo Registro de <?echo $modulo?></strong></td>
    </tr>
	<td class="tb-head">Codigo Empresa</td>
      	<td colspan="6"><?echo "$valor";?> <INPUT type="hidden" name="codigo" value="<?echo "$valor";?>"></td>
	<?
	$i=0;
	$cont=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
		echo "<TR>";?><td class=tb-head ><?echo "$nombre</td>";
		echo "<td colspan=\"3\"><INPUT type=\"text\" name=\"$campo\" size=\"100\"></td> </tr>";
		$i++;
		$cont++;
		if($cont==11)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS DE CUENTA</strong></td>";
		}
		
		if($cont==11)
		{
		echo "<TR>";?><td class=tb-head ><?echo "Sobre Giro P</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"sobregirop\" id=\"sobregirop\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"sobregirop\" id=\"sobregirop\" value=\"N\">";
		
		echo "<TR>";?><td class=tb-head ><?echo "Sobre Giro F</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"sobregirof\" id=\"sobregirof\" value=\"S\" checked=\"true\">No<INPUT type=\"radio\" name=\"sobregirof\" id=\"sobregirof\" value=\"N\">";
		}
		
		if($cont==11){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta I.S.L.R</td>";
		echo "<td colspan=\"3\"><SELECT name=\"ctaisrl\" id=\"ctaisrl\">";
                        while($fila_islr=fetch_array($resultado_islr)){
				$codigo_islr=$fila_islr['Cuenta'];
				//$descripcion_islr=$fila_islr['Descrip'];
				echo "<option value=\"$codigo_islr\">$codigo_islr</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		}
		if($cont==12){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta I.V.A.</td>";
		echo "<td colspan=\"3\"><SELECT name=\"ctaiva\" id=\"ctaiva\">";
                        while($fila_iva=fetch_array($resultado_iva)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==14){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Timbre Fiscal</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_tf\" id=\"cta_tf\">";
                        while($fila_tf=fetch_array($resultado_tf)){
				$codigo_iva=$fila_tf['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		
		if($cont==15){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Fiel Cumplimiento</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_fiel\" id=\"cta_fiel\">";
                        while($fila_iva=fetch_array($resultado_fiel)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==16){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Laboral</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_laboral\" id=\"cta_laboral\">";
                        while($fila_iva=fetch_array($resultado_laboral)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==17){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Anticipo</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_anticipo\" id=\"cta_anticipo\">";
                        while($fila_iva=fetch_array($resultado_anticipo)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==18){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Impuesto Municipal</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_im\" id=\"cta_im\">";
                        while($fila_iva=fetch_array($resultado_im)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==20){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Bomberos</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_bombero\" id=\"cta_bombero\">";
                        while($fila_iva=fetch_array($resultado_bombero)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==22){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta 701</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_701\" id=\"cta_701\">";
                        while($fila_iva=fetch_array($resultado_701)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==22){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta 702</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_702\" id=\"cta_702\">";
                        while($fila_iva=fetch_array($resultado_702)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==22){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Retencion I.V.A.</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_ret_iva\" id=\"cta_ret_iva\">";
                        while($fila_iva=fetch_array($resultado_ret_iva)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		
		if($cont==23){
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Deducciones o Multas</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_multa\" id=\"cta_multa\">";
                        while($fila_iva=fetch_array($resultado_multa)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		
		if($cont==24){
		echo "<TR>";?><td class=tb-head ><?echo "Moneda</td>";
		echo "<td colspan=\"3\"><SELECT name=\"moneda\" id=\"moneda\">";
        echo "<option class=\"tb-fila\">Seleccione un Tipo de Moneda</option>";
		echo "<option value=\"Bs.F.\">Bolivar Fuerte (Bs.F.)</option>";
		echo "<option value=\"Bs.\">Bolivar (Bs.)</option>";
		echo "<option value=\"$\">Dolar ($)</option>";
		echo "<option value=\"€\">Euros (€)</option>";
		}
		echo "</SELECT></td> </tr>";

		if($cont==24)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS DE REPORTE</strong></td>";
		}
		
		if($cont==28)
		{
		echo "<TR>";?><td class=tb-head ><?echo "Imagen Izquierda</td>";
		echo "<td colspan=\"2\"><INPUT type=\"file\" name=\"imagen_izq\" id=\"imagen_ izq\" value=\"\"></td>";
		}
		if($cont==28)
		{
		echo "<TR>";?><td class=tb-head ><?echo "Imagen Derecha</td>";
		echo "<td colspan=\"2\"><INPUT type=\"file\" name=\"imagen_der\" id=\"imagen_der\" value=\"\"></td>";

		echo "<tr><td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>CONSECUTIVOS DEL SISTEMA</strong></td></tr>";

		
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo I.S.L.R."?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_ISLR"?>" size="60" ></td> </tr><?
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo I.V.A."?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_iva"?>" size="60"></td> </tr><?

			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Impuesto Municipal"?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_IM"?>" size="60" ></td> </tr><?
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Timbre Fiscal."?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_TF"?>" size="60"></td> </tr><?
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Aporte Social"?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_RRS"?>" size="60" ></td> </tr><?
			
					
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Recibo de Pago"?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_RP"?>" size="60" ></td> </tr><?
		
		
		}

		if($cont==29)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS DE Configuración</strong></td>";
		}

		if($cont==29){
		echo "<TR>";?><td class=tb-head ><?echo "Tipo de Presupuesto</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo_presupuesto\" id=\"tipo_presupuesto\">";
        echo "<option class=\"tb-fila\">Seleccione Tipo de Presupuesto a Ejecurar</option>";
		echo "<option value=\"0\">Presupuesto por Programatica</option>";
		echo "<option value=\"1\">Presupuesto por Proyectos</option>";
		echo "</SELECT></td> </tr>";
		}

		if($cont==29){
		echo "<TR>";?><td class=tb-head ><?echo "Tipo de Compromisos</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo_compromiso\" id=\"tipo_compromiso\">";
        echo "<option class=\"tb-fila\">Desea Comprometer automaticamente cuando realiza la orden?</option>";
		echo "<option value=\"0\">Si</option>";
		echo "<option value=\"1\">No</option>";
		echo "</SELECT></td> </tr>";

		
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
<?php
/* Última modificación: 27/02/2008 - 9:15am César III
	Corrección al seleccionar la imagen derecha, no la guardaba correctamente
 */
require_once '../lib/common.php';
include ("../header.php");

$conex = conexion();
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

$codigo=@$_GET['codigo'];

$conexion=conexion_conf();
//echo $conexion;
$url="parametroslist";
$modulo="Datos de la Empresa";
$tabla="parametros";
$titulos=array("Nombre","R.I,F.","N.I.T.","Presidente","Período","Departamento","Cargo","Dirección","Estado","Ciudad","Teléfonos","Descripción I.S.L.R.","% I.V.A.","Descripción I.V.A.","Descripción Timbre Fiscal","Descripción Fiel Cumplimiento","Descripción Laboral","Descripción Anticipo","% Impuesto Municipal","Descripción Impuesto Municipal","% Bomberos","Descripción Bombero","Descripción Retención I.V.A.","Descripción Deducción o Multa","Encabezado1","Encabezado2","Encabezado3","Encabezado4");
$indices=array("1","14","15","3","4","2","5","16","18","17","19","7","11","9","38","40","42","44","20","50","21","52","48","55","29","30","31","32","23","24","8","10","37","39","41","43","45","46","47","49","51","53","54","56","57","58");
//$indices=array("1","14","15","3","4","2","5","16","18","17","19","8","7","10","9","11","20","21","23","24","29","30","31","32","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52");
	
if(isset($_POST['aceptar'])){
	//echo "Pase por aqui";exit(0);
	//$archivo=$HTTP_POST_FILES['imagen_izq']['name'];
	//$archivo; //exit(0);


//	$archivo=$HTTP_POST_FILES['imagen_izq']['name'];
	$archivo=$HTTP_POST_FILES['imagen_izq'];
	//echo "nombre de archivo:".$archivo;
	//exit(0);
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
		
	}	


	$consulta="select * from ".$tabla;
	$resultado= query($consulta,$conexion);
	
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
	//echo "nombre arch ".$nombre_archivo1."<br>";
	$cadena1="../imagenes/".$nombre_archivo1;
	$cadena2="../imagenes/".$nombre_archivo2;
	if($nombre_archivo1!="")
	{
		$cadena=$cadena.",imagen_izq='".$cadena1."'";
	}
	if($nombre_archivo2!="")
	{
	$cadena=$cadena.",imagen_der='".$cadena2."'";
	}
	$consulta="update ".$tabla." set ".$cadena." where codigo=".$_POST["codigo"];
	//echo "la consulta ".$consulta;
	//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	
	parent.cont.location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
	

}
	$codigo = @$_GET['codigo'];
	//$descripcion= @$_GET['descripcion'];
	$consulta="select * from ".$tabla." where codigo=".$codigo;
	//echo $consulta;
	$resultado=query($consulta, $conexion);
	//echo $resultado;
	$fila=fetch_array($resultado);
	//echo $fila;
?>

<?
$consulta="select moneda from parametros where codigo=".$codigo;
	$resultado1= query($consulta,$conexion);
	$fila1=fetch_array($resultado1);
?>
<FORM name="sampleform" enctype="multipart/form-data" method="POST" target="_self" action="<?echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>

<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Editar Registro de <?echo $modulo?></strong></td>
</tr>

	<TR><td class=tb-head width="180">C&oacute;digo</td><td><INPUT type="text" name="codigo" size="100"  value="<?echo $codigo?>"></td> </tr>

		
	<?
	$i=0;
	
	foreach($titulos as $nombre){
		
		if($cont==0)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS GENERALES</strong></td>";
		}
		$campo=mysql_field_name($resultado,$indices[$i]);
		//echo $campo;
		echo "<TR>";?><td class=tb-head ><?echo "$nombre"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="100" value='<?echo "$fila[$campo]";?>'></td> </tr><?
		$i++;
		$cont++;
		
		if($cont==11)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS DE CUENTA</strong></td>";
		}
		
		if($cont==11)
		{
		echo "<TR>";?><td class=tb-head ><?echo "Sobre Giro P</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"sobregirop\" id=\"sobregirop\" value=\"S\""; if ($fila['sobregirop']=='S'){ echo "checked=\"true\" ";} echo ">No<INPUT type=\"radio\" name=\"sobregirop\" id=\"sobregirop\" value=\"N\""; if ($fila['sobregirop']=='N'){ echo "checked=\"true\" ";} echo ">";
		
		echo "<TR>";?><td class=tb-head ><?echo "Sobre Giro F</td>";
		echo "<td colspan=\"2\">Si<INPUT type=\"radio\" name=\"sobregirof\" id=\"sobregirof\" value=\"S\""; if ($fila['sobregirof']=='S'){ echo "checked=\"true\" ";} echo ">No<INPUT type=\"radio\" name=\"sobregirof\" id=\"sobregirof\" value=\"N\""; if ($fila['sobregirof']=='N'){ echo "checked=\"true\" ";} echo ">";
		}
		
		if($cont==11){
		$ctaiva=$fila['ctaisrl'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta I.S.L.R</td>";
		echo "<td colspan=\"3\"><SELECT name=\"ctaisrl\" id=\"ctaisrl\">";
			echo "<option value= \"$ctaiva\">$ctaiva</option>";
                while($fila_islr=fetch_array($resultado_islr)){
				$codigo_islr=$fila_islr['Cuenta'];
				//$descripcion_islr=$fila_islr['Descrip'];
				echo "<option value=\"$codigo_islr\">$codigo_islr</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		}
		if($cont==12){
		$ctaiva=$fila['ctaiva'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta I.V.A.</td>";
		echo "<td colspan=\"3\"><SELECT name=\"ctaiva\" id=\"ctaiva\"> ";
			echo "<option value= \"$ctaiva\">$ctaiva</option>";	
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
		$ctaiva=$fila['cta_tf'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Timbre Fiscal</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_tf\" id=\"cta_tf\">";
			echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$ctaiva=$fila['cta_fiel'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Fiel Cumplimiento</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_fiel\" id=\"cta_fiel\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$ctaiva=$fila['cta_laboral'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Laboral</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_laboral\" id=\"cta_laboral\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$ctaiva=$fila['cta_anticipo'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Anticipo</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_anticipo\" id=\"cta_anticipo\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$ctaiva=$fila['cta_im'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Impuesto Municipal</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_im\" id=\"cta_im\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$ctaiva=$fila['cta_bombero'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Bomberos</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_bombero\" id=\"cta_bombero\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$ctaiva=$fila['cta_701'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta 701</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_701\" id=\"cta_701\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$ctaiva=$fila['cta_702'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta 702</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_702\" id=\"cta_702\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
                        while($fila_iva=fetch_array($resultado_702)){
				$codigo_iva=$fila_iva['Cuenta'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_iva\">$codigo_iva</option>";
			}$ctaiva=$fila['cta_im'];
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==22){
		$ctaiva=$fila['cta_ret_iva'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Retencion I.V.A.</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_ret_iva\" id=\"cta_ret_iva\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$ctaiva=$fila['cta_multa'];
		echo "<TR>";?><td class=tb-head ><?echo "Cuenta Deducciones o Multas</td>";
		echo "<td colspan=\"3\"><SELECT name=\"cta_multa\" id=\"cta_multa\">";
				echo "<option value= \"$ctaiva\">$ctaiva</option>";
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
		$moneda=$fila1['moneda'];
		echo "<TR>";?><td class=tb-head ><?echo "Moneda</td>";
		echo "<td colspan=\"3\"><SELECT name=\"moneda\" id=\"moneda\">";
        echo "<option class=\"tb-fila\" value=\"$moneda\">$moneda</option>";
		echo "<option value=\"Bs.F.\">Bolivar Fuerte (Bs.F.)</option>";
		echo "<option value=\"Bs.\">Bolivar (Bs.)</option>";
		echo "<option value=\"$\">Dolar ($)</option>";
		echo "<option value=\"€\">Euros (€)</option>";
		echo "</SELECT></td> </tr>";
		}
		//echo"<br>";
		if($cont==24)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS DE REPORTE</strong></td>";
		}
		
		if($cont==28)
		{
		echo "<TR>";?><td class=tb-head ><?echo "Imagen Izquierda</td>";
		echo "<td colspan=\"2\"><INPUT type=\"file\" name=\"imagen_izq\" id=\"imagen_izq\" value=\"\"></td>";//echo $imagenizq;
		}
		if($cont==28)
		{
		echo "<TR>";?><td class=tb-head ><?echo "Imagen Derecha</td>";
		echo "<td colspan=\"2\"><INPUT type=\"file\" name=\"imagen_der\" id=\"imagen_der\" value=\"\"></td>";
		//echo $imagender;
		}

		if($cont==28)
		{
		echo "<tr><td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS DE CONFIGURACIÓN DE SISTEMA</strong></td></tr>";
		}

		if($cont==28){
		echo "<TR>";?><td class=tb-head ><?echo "Tipo de Presupuesto</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo_presupuesto\" id=\"tipo_presupuesto\">";
        echo "<option class=\"tb-fila\">Seleccione Tipo de Presupuesto a Ejecurar</option>";
		echo "<option value=\"0\">Presupuesto por Programatica</option>";
		echo "<option value=\"1\">Presupuesto por Proyectos</option>";
		echo "</SELECT></td> </tr>";
		}

		if($cont==28){
		echo "<TR>";?><td class=tb-head ><?echo "Tipo de Compromisos</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo_compromiso\" id=\"tipo_compromiso\">";
        echo "<option class=\"tb-fila\">Desea Comprometer automaticamente cuando realiza la orden?</option>";
		echo "<option value=\"0\">Si</option>";
		echo "<option value=\"1\">No</option>";
		echo "</SELECT></td> </tr>";
		}

		if($cont==28){
		echo "<TR>";?><td class=tb-head ><?echo "Tipo de Causados</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo_causado\" id=\"tipo_causado\">";
        echo "<option class=\"tb-fila\">Desea Causar automaticamente cuando realiza la orden de pago?</option>";
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
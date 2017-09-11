<?php
/* Última modificación: 27/02/2008 - 9:15am César III
	Corrección al seleccionar la imagen derecha, no la guardaba correctamente
 */
require_once '../lib/common.php';
include ("../header.php");

$conex = conexion();
$consulta="select * from cwconcue";
$consulta_precue="select * from cwprecue where CHARACTER_LENGTH(CodCue) = 13 order by CodCue";
//echo $consulta_islr;
$resultado_islr=query($consulta,$conex);
$resultado_iva=query($consulta_precue,$conex);
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
//$resultado_partida_iva=query($consulta_precue,$conex);

cerrar_conexion($conex);

$codigo=@$_GET['codigo'];

$conexion=conexion_conf();
//echo $conexion;
$url="parametroslist";
$modulo="Datos de la Empresa";
$tabla="parametros";
$titulos=array("Nombre","R.I.F.","N.I.T.","Presidente","Período","Departamento","Cargo","Dirección","Estado","Ciudad","Teléfonos","Descripción I.S.L.R.","% I.V.A.","Descripción I.V.A.","Descripción Timbre Fiscal","Descripción Fiel Cumplimiento","Descripción Laboral","Descripción Anticipo","% Impuesto Municipal","Descripción Impuesto Municipal","% Bomberos","Descripción Bombero","Descripción Retención I.V.A.","Descripción Deducción o Multa","Encabezado1","Encabezado2","Encabezado3","Encabezado4");
$indices=array("1","14","15","3","4","2","5","16","18","17","19","7","11","9","38","40","42","44","20","50","21","52","48","55","29","30","31","32","8","10","37","39","41","43","45","46","47","49","51","53","54","56","57","58","59","60","62");
//$indices=array("1","14","15","3","4","2","5","16","18","17","19","8","7","10","9","11","20","21","23","24","29","30","31","32","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52");
	
if(isset($_POST['aceptar']))
{
	if (($_POST['codigo'] == '') || ($_POST['nomemp'] == '') || ($_POST['rif'] == '') || ($_POST['cta_ret_iva'] == '') ||($_POST['encabezado1'] == '' ) || ($_POST['encabezado2'] == '' ) || ($_POST['tipo_presupuesto'] == '' ) || ($_POST['tipo_compromiso'] == '' ) || ($_POST['tipo_causado'] == '' )   || ($_POST['precompromisos'] == '' ))
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Datos imcompletos, no se puede realizar la operacion\")";
		echo "</SCRIPT>";
	}
	else
	{	
	
//	$archivo=$HTTP_POST_FILES['imagen_izq']['name'];
	$archivo=$HTTP_POST_FILES['imagen_izq']['name'];
	//echo "nombre de archivo:".$archivo;
	//exit(0);
	if($archivo!="")
	{
		$nombre_archivo1 = $HTTP_POST_FILES['imagen_izq']['name'];
		$tipo_archivo = $HTTP_POST_FILES['imagen_izq']['type'];
		$tamano_archivo = $HTTP_POST_FILES['imagen_izq']['size'];
		if (copy($HTTP_POST_FILES['imagen_izq']['tmp_name'],"../imagenes/".$nombre_archivo1))
		{
       		echo "<div align='center' style=\"background-color : #84225b; color : #fdfdfd; font-family : 'Arial Black'; font-size : 15px;\">EL archivo fue cargado exitosamente</div>";
				chmod("../imagenes/".$nombre_archivo1,0777);
    	}
		else
		{
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
    	}
		else
		{
       		echo "<div align='center' style=\"background-color : #84225b; color : #fdfdfd; font-family : 'Arial Black'; font-size : 15px;\">Ocurri&oacute; un problema cargando el archivo</div>";
    	}
		
	}	


	$consulta="select * from ".$tabla;
	$resultado= query($consulta,$conexion);
	
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
	$rrs=$_POST['consecutivo_RRS'];
	$isrl=$_POST['consecutivo_ISLR'];
	$tf=$_POST['consecutivo_TF'];
	$im=$_POST['consecutivo_IM'];
	$rp=$_POST['consecutivo_RP'];
	$iva=$_POST['consecutivo_iva'];
	$mp=$_POST['consecutivo_MP'];
	$e=$_POST['consecutivo_E'];
	$telfax=$_POST['telefonofax'];
	$padm=$_POST['pers_adm'];

	$cadena=$cadena.",consecutivo_RRS=$rrs, consecutivo_IM=$im,consecutivo_ISLR=$isrl,consecutivo_TF=$tf,consecutivo_RP=$rp,consecutivo_iva=$iva,consecutivo_MP=$mp,telefonofax='$telfax',pers_adm='$padm',consecutivo_E=$e";
	$consulta="update ".$tabla." set ".$cadena." where codigo=".$_POST["codigo"];
	//echo "la consulta ".$consulta;
	//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert('Se guardaron con Exito sus Cambios!!!')
	parent.cont.location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
	}
}
	if($_POST['codigo'])
	{
		$codigo = $_POST['codigo'];
	}
	else
	{
		$codigo = @$_GET['codigo'];
	}
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

<TABLE  width="100%" height="100" border="0">
<TBODY>

<tr>
      <td colspan="2" height="30"  class="tb-tit"><strong>Editar Registro de <?echo $modulo?></strong></td>
</tr>

	<TR><td class=tb-head colspan="2" align="center" width="180">COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</td></tr>
	<TR><td class=tb-head  >C&oacute;digo&nbsp;**</td><td><INPUT type="text" name="codigo" size="60"  value="<? echo $codigo?>"></td> </tr>

		
	<?
	$i=0;
	
	foreach($titulos as $nombre)
	{
		
		if($cont==0)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS GENERALES</strong></td>";
		}
		$campo=mysql_field_name($resultado,$indices[$i]);
		
		if (($campo == "codigo") || ($campo == "rif") || ($campo == "nomemp"))
		{
			echo "<TR>";?><td class=tb-head  ><? echo "$nombre"?>&nbsp;**</td>
			<td><INPUT type="text" name="<? echo $campo?>" size="60" value='<? echo "$fila[$campo]";?>'></td> </tr><?
			$i++;
			$cont++;
		}
		else
		{
			
			echo "<TR>";?><td class=tb-head  ><? echo "$nombre"?></td>
			<td><INPUT type="text" name="<? echo $campo?>" size="60" value='<? echo "$fila[$campo]";?>'></td> </tr><?
			$i++;
			$cont++;
		}
		if ($cont==10){
			echo "<TR>";?><td class=tb-head  ><? echo "Teléfono Fax"?></td>
			<td><INPUT type="text" name="telefonofax" size="60" value='<? echo $fila['telefonofax'];?>'></td> </tr><?
			
		}
		if($cont==11){
			echo "<TR>";?><td class=tb-head  ><? echo "Encargado Administración"?></td>
			<td><INPUT type="text" name="pers_adm" size="60" value='<? echo $fila['pers_adm'];?>'></td> </tr><?
		}
		if($cont==11)
		{
			echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS DE CUENTA</strong></td>";
		}
		
		if($cont==11)
		{
			$ctaiva=$fila['ctaisrl'];
			echo "<TR>";?>
        		<td class=tb-head ><? echo "Cuenta I.S.L.R</td>";
			echo "<td colspan=\"3\"><SELECT name=\"ctaisrl\" id=\"ctaisrl\">";
			echo "<option value= \"$ctaiva\">$ctaiva</option>";
            		while($fila_islr=fetch_array($resultado_islr))
			{
				$codigo_islr=$fila_islr['Cuenta'];
				//$descripcion_islr=$fila_islr['Descrip'];
				echo "<option value=\"$codigo_islr\">$codigo_islr</option>";
			}
			echo "</SELECT></td> </tr>";
			//cerrar_conexion($conex);
		}
		if($cont==12){
		$ctaiva=$fila['ctaiva'];
		echo "<TR>";?><td class=tb-head ><? echo "Partida de I.V.A.</td>";
		echo "<td colspan=\"3\"><SELECT name=\"ctaiva\" id=\"ctaiva\"> ";
			echo "<option value= \"$ctaiva\">$ctaiva</option>";
			echo "<option value= \"\"></option>";	
                        while($fila_iva=fetch_array($resultado_iva)){
				$codigo_iva=$fila_iva['CodCue'];
				$descripcion_iva=$fila_iva['Denominacion'];
				echo "<option title=\"$descripcion_iva\"value=\"$codigo_iva\">$codigo_iva</option>";
			}
		echo "</SELECT></td> </tr>";
		//cerrar_conexion($conex);
		//$conexion=conexion_conf();
		}
		if($cont==14){
		$ctaiva=$fila['cta_tf'];
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta Timbre Fiscal</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta Fiel Cumplimiento</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta Laboral</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta Anticipo</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta Impuesto Municipal</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta Bomberos</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta 701</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta 702</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta Retencion I.V.A.&nbsp;**</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Cuenta Deducciones o Multas</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Moneda</td>";
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
		echo "<TR>";?><td class=tb-head ><? echo "Imagen Izquierda &nbsp;**</td>";
		echo "<td colspan=\"2\"><INPUT type=\"file\" name=\"imagen_izq\" id=\"imagen_izq\" value=\"\"></td>";//echo $imagenizq;
		}
		if($cont==28)
		{
		echo "<TR>";?><td class=tb-head ><? echo "Imagen Derecha &nbsp;**</td>";
		echo "<td colspan=\"2\"><INPUT type=\"file\" name=\"imagen_der\" id=\"imagen_der\" value=\"\"></td>";
		//echo $imagender;
		}

		if($cont==28)
		{
		echo "<tr><td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>DATOS DE CONFIGURACIÓN DE SISTEMA</strong></td></tr>";
		}

		if($cont==28){
		$tipo_presupuesto=$fila['tipo_presupuesto'];
		echo "<TR>";?><td class=tb-head ><? echo "Tipo de Presupuesto &nbsp;**</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo_presupuesto\" id=\"tipo_presupuesto\">";
		echo "<option class=\"tb-fila\" value=\"$tipo_presupuesto\">$tipo_presupuesto</option>";
        echo "<option class=\"tb-fila\">Seleccione Tipo de Presupuesto a Ejecurar</option>";
		echo "<option value=\"Programa\">Presupuesto por Programatica</option>";
		echo "<option value=\"Proyecto\">Presupuesto por Proyectos</option>";
		echo "</SELECT></td> </tr>";
		}

		if($cont==28){
		$tipo_compromiso=$fila['tipo_compromiso'];
		echo "<TR>";?><td class=tb-head ><? echo "Tipo de Compromisos&nbsp;**</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo_compromiso\" id=\"tipo_compromiso\">";
		echo "<option class=\"tb-fila\" value=\"$tipo_compromiso\">$tipo_compromiso</option>";
        echo "<option class=\"tb-fila\">Desea Comprometer automaticamente cuando realiza la orden?</option>";
		echo "<option value=\"SI\">Si</option>";
		echo "<option value=\"NO\">No</option>";
		echo "</SELECT></td> </tr>";
		}

		if($cont==28){
		$tipo_causado=$fila['tipo_causado'];
		echo "<TR>";?><td class=tb-head ><? echo "Tipo de Causados&nbsp;**</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo_causado\" id=\"tipo_causado\">";
		echo "<option class=\"tb-fila\" value=\"$tipo_causado\">$tipo_causado</option>";
        echo "<option class=\"tb-fila\">Desea Causar automaticamente cuando realiza la orden de pago?</option>";
		echo "<option value=\"SI\">Si</option>";
		echo "<option value=\"NO\">No</option>";
		echo "</SELECT></td> </tr>";
		}

		if($cont==28){
		$precompromisos=$fila['precompromisos'];
		echo "<TR>";?><td class=tb-head ><? echo "Activar Precompromisos**</td>";
		echo "<td colspan=\"3\"><SELECT name=\"precompromisos\" id=\"precompromisos\">";
		echo "<option class=\"tb-fila\" value=\"$precompromisos\">$precompromisos</option>";
        echo "<option class=\"tb-fila\">Desea Activar el modulo de Precompromisos?</option>";
		echo "<option value=\"SI\">Si</option>";
		echo "<option value=\"NO\">No</option>";
		echo "</SELECT></td> </tr>";
		}

		if($cont==28){
		$version=$fila['version'];
		echo "<TR>";?><td class=tb-head ><? echo "Versión &nbsp;**</td>";
		echo "<td colspan=\"3\"><SELECT name=\"version\" id=\"version\">";
		echo "<option class=\"tb-fila\" value=\"$version\">$version</option>";
       		echo "<option class=\"tb-fila\">Seleccione Tipo de Versión</option>";
		echo "<option value=\"Gobernacion\">Gobernación</option>";
		echo "<option value=\"Alcaldia\">Alcaldia</option>";
		echo "<option value=\"Instituto\">Instituto</option>";
		echo "</SELECT></td> </tr>";
		}
		if($cont==28){
		$serial=$fila['serial'];
		echo "<TR>";?><td class=tb-head ><? echo "Serial &nbsp;**</td>";
		?><td><INPUT type="text" name="serial" size="70" value='<? echo "$serial";?>'></td><?}

		if($cont==28)
		{
		echo "<tr><td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>CONSECUTIVOS DEL SISTEMA</strong></td></tr>";

		
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo I.S.L.R."?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_ISLR"?>" size="60" value='<? echo $fila['consecutivo_ISLR'];?>'></td> </tr><?
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo I.V.A."?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_iva"?>" size="60" value='<? echo $fila['consecutivo_iva'];?>'></td> </tr><?

			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Impuesto Municipal"?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_IM"?>" size="60" value='<? echo $fila['consecutivo_IM'];?>'></td> </tr><?
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Timbre Fiscal."?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_TF"?>" size="60" value='<? echo $fila['consecutivo_TF'];?>'></td> </tr><?
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Aporte Social"?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_RRS"?>" size="60" value='<? echo $fila['consecutivo_RRS'];?>'></td> </tr><?
			
					
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Recibo de Pago"?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_RP"?>" size="60" value='<? echo $fila['consecutivo_RP'];?>'></td> </tr><?
			
			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo Modificación Presupuestaria"?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_MP"?>" size="60" value='<? echo $fila['consecutivo_MP'];?>'></td> </tr><?

			echo "<TR>";?><td class=tb-head ><? echo "Consecutivo de Egreso"?></td>
			<td><INPUT type="text" name="<? echo "consecutivo_E"?>" size="60" value='<? echo $fila['consecutivo_E'];?>'></td> </tr><?
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
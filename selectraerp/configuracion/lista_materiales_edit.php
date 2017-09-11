<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$paginam=@$_GET['paginam'];
$pagina=@$_GET['pagina'];
$codigo_snc=@$_GET['codigo_snc'];

$url="lista_materiales";
$modulo="Materiales";
$tabla="materiales";
$titulos=array("Descripción","Unidad de Medida","I.V.A.","Último Costo","Fecha Último Costo","Cantidad en Existencia","Mínimo","Máximo (Capacidad del Almacen)","Cantidad de Unidad","Unidad de Salida","Codigo Presupuestario","Segmento","Almacen");
$indices=array("1","2","13","9","10","6","7","8","11","12","15","16","20");



if(isset($_POST['aceptar'])){
	
	$consulta="select * from ".$tabla;
	$resultado= query($consulta,$conexion);

	$indices=array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","15","16","17","18","19","20");
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
	$codigo_snc=@$_POST['codigo_snc'];
	$consulta="update ".$tabla." set ".$cadena." where codigo_snc='".$codigo_snc."' and cod_material='".$_POST["cod_material"]."'";
	//echo $consulta;
	//exit(0);
	
	$resultado=query($consulta,$conexion);
	$pagina=@$_POST['pagina'];
	$paginam=@$_POST['paginam'];
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	
	parent.cont.location.href=\"".$url.".php?pagina=".$pagina."&codigo_snc=".$codigo_snc."&paginam=".$paginam."\"
	</SCRIPT>";
	

}
	$codigo = @$_GET['codigo'];
	$pagina = @$_GET['pagina'];
	//$descripcion= @$_GET['descripcion'];
	$consulta="select * from ".$tabla." where cod_material='".$codigo."'";
	//echo $consulta;
	$resultado=query($consulta, $conexion);
	//echo $resultado;
	$fila=fetch_array($resultado);
	//echo $fila;
?>

<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?codigo_snc=<?=$codigo_snc?>&pagina=<?=$pagina?>&paginam=<?=$paginam?>"
}

</SCRIPT>
<?
$consulta_p="select *,cue.Denominacion from  cwprepar inner join cwprecue as cue on Codigo = cue.CodCue where CHARACTER_LENGTH(Codigo) = 13 order by CodCue";

$resultado_partida=query($consulta_p,$conexion);
$consulta_m="select * from  materiales_segmento";
//echo $consulta_islr;
$resultado_seg=query($consulta_m,$conexion);
?>
<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>

<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Editar Registro de <?echo $modulo?></strong></td>

    </tr>
<TR><td class=tb-head width="200" >C&oacute;digo de SNC</td><td><INPUT type="text" name="codigo_snc" size="100" readonly="true" value="<?echo $codigo_snc?>"></td> </tr>

<TR><td class=tb-head >C&oacute;digo de Materiales</td><td><INPUT type="text" name="cod_material" size="100" readonly="true" value="<?echo $codigo?>"></td> </tr>

<INPUT type="hidden" name="pagina" t value="<?echo "$pagina";?>"></td>
<INPUT type="hidden" name="paginam" value="<?echo "$paginam";?>"></td>
	<?
	$i=0;
	$cont=0;
	foreach($titulos as $nombre)
	{
		$campo=mysql_field_name($resultado,$indices[$i]);
	//echo $campo;
		

		switch($campo)
		{

		case "codigo_cuenta":
			$codigo_partida=$fila['codigo_cuenta'];
			//echo "<TR>";?><td class=tb-head ><?echo "Partida Presupuestaria</td>";
			echo "<td colspan=\"3\"><SELECT name=\"codigo_cuenta\" id=\"codigo_cuenta\">";
			echo "<option value= \"$codigo_partida\">$codigo_partida</option>";
			echo "<option value=\"\"></option>";
                        while($fila_partida=fetch_array($resultado_partida)){
				$codigo_partida=$fila_partida['Codigo'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option title=\"$fila_partida[Denominacion]\" value=\"$codigo_partida\">$codigo_partida</option>";
			}
			echo "</SELECT></td> </tr>";
		break;

		case "segmentos":
		$consulta_s="select * from  materiales_segmento where codigo=".$fila["segmentos"];
		//echo $consulta_islr;
		$resultado_s=query($consulta_s,$conexion);
		$fila_s=fetch_array($resultado_s);

		$segmento = $fila["segmentos"];
		$familias = $fila['familias'];
		$clases = $fila['clases'];
		$productos = $fila['productos'];
		
		$consultafam = "SELECT * FROM materiales_familias WHERE codigo_segmento='".$segmento."' and codigo=".$familias;
		$resultadofam= query($consultafam, $conexion);
		$filaf = fetch_array($resultadofam);

		if($clases!=0){
			$consultacla = "SELECT * FROM materiales_clases WHERE codigo_segmento='".$segmento."' and codigo_familia='".$familias."' and codigo=".$clases;
			$resultadocla = query($consultacla, $conexion);
			$filac = fetch_array($resultadocla);
			
			$consultapro = "SELECT * FROM materiales_productos WHERE codigo_segmento='".$segmento."' and codigo_familia='".$familias."' and  codigo_clase='".$clases."' and codigo='".$productos."'";
			$resultadopro = query($consultapro, $conexion);
			$filap = fetch_array($resultadopro);
		}else{
			$consultacla = "SELECT * FROM materiales_clases WHERE codigo_segmento='".$segmento."' and codigo_familia='".$familias."'";
			$resultadocla = query($consultacla, $conexion);
			
		}

		?>
		<TR>
			<TD width="150" class="tb-head">Segmento:</TD>
			<TD>  <SELECT name="segmentos" id="segmentos" onchange="javascript:cargar_familias()">
					<option value= "<?echo $fila["segmentos"];?>"><?echo $fila_s['descripcion'];?></option>";
					<OPTION value="0" class="tb-fila">Seleccione un segmento</OPTION>
					<? while($fila1 = fetch_array($resultado_seg)) {
						$cod = $fila1['codigo'];
						$des = $fila1['descripcion'];
						echo "<option value=\"$cod\">$des</option>";
					} ?>
					</SELECT>  </TD>
		</TR>
		<TR>
			<TD width="150" class="tb-head">Familia:</TD>
			<TD>  <SELECT name="familias" id="familias">
						<option value= "<?echo $fila["familias"];?>"><?echo $filaf['descripcion'];?></option>";
						<OPTION class="tb-fila" value="0">Seleccione una familia</OPTION>
					</SELECT>  </TD>
		</TR>
		<TR>
			<TD width="150" class="tb-head">Clases:</TD>
			<TD>  <SELECT name="clases" id="clases" onchange="javascript:cargar_productos()">
					<OPTION class="tb-fila" value="0">Seleccione una clase</OPTION>
					<?if($clases!=0){?>
						<option value= "<?echo $fila["clases"];?>" selected ><?echo $filac['descripcion'];?></option>";
					<?}else{
						while($filac = fetch_array($resultadocla)){?>
							<option value= "<?echo $filac["codigo"];?>"><?echo $filac['descripcion'];?></option>";
						<?}
					}?>
						
					</SELECT>  </TD>
		</TR>
		<TR>
			<TD width="150" class="tb-head">Producto:</TD>
			<TD>  <SELECT name="productos" id="productos">
					<option value= "<?echo $fila["productos"];?>"><?if($clases!=0){echo $filap['descripcion'];}?></option>";
					<OPTION class="tb-fila" value="0" <?if($clases==0){ echo "selected";}?>>Seleccione un producto</OPTION>
					</SELECT>  </TD>
		</TR>
		<?
		break;	
		

		default:
		
		echo "<TR>";?><td class=tb-head ><?echo "$nombre"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="100" value='<?echo "$fila[$campo]";?>'></td> </tr><?
		
		//$cont++;
		break;		
		}
		$i++;
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

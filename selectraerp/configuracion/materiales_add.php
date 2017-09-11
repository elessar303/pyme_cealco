<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$codigo_snc=@$_GET['codigo_snc'];
$paginam=@$_GET['paginam'];
$url="materiales";
$modulo="Materiales";
$tabla="materiales";
$titulos=array("Descripción","Unidad de Medida","I.V.A.","Último Costo","Fecha Último Costo","Cantidad en Existencia","Mínimo","Máximo (Capacidad del Almacen)","Cantidad de Unidad","Unidad de Salida","Codigo Presupuestario","Segmento");
$indices=array("1","2","13","9","10","6","7","8","11","12","15","16");

if(isset($_POST['aceptar'])){
	
	$consulta="select * from ".$tabla;
	$resultado=mysql_query($consulta);
	//if((!$_POST['nombre_banco'])||(!$_POST['numero_cuenta'])||(!$_POST['firstinput'])){
	//	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	//	alert(\"Datos imcompletos, no se puede realizar la operacion\")";
	//	echo "</SCRIPT>";
	//	
	//}
	//echo "Fam: ".$_POST['sel_familias'];
$indices=array("0","1","14","2","3","4","5","6","7","8","9","10","11","12","13","15","16","17","18","19");
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
	//echo $cadena_campos." ";
	//echo $cadena_valores;
	
	$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.")";
	$resultado=query($consulta,$conexion);
	//echo $consulta;
	//exit(0);
	$val=$_POST['codigo_snc'];
	$paginam=$_POST['paginam'];
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?codigo_snc=".$val."&paginam=".$paginam."&pagina=1\"
	</SCRIPT>";
	

}else{

	$consulta="select * from ".$tabla;
	//echo $consulta;
	$resultado=query($consulta,$conexion);
}
?>

<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?codigo_snc=<?=$codigo_snc?>"
}

</SCRIPT>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>
<?
$consulta="select max(correlativo) as valor from materiales where codigo_snc='".$codigo_snc."'";
$resultado_materiales=query($consulta,$conexion);

$fila_materiales=fetch_array($resultado_materiales);
$max_materiales=$fila_materiales['valor'];
//echo "max: ".$max_materiales;
$val=$max_materiales+1;
$valor=$codigo_snc.$val;
/*if($max_centro==0){
$valor=$cod_unidad+0.1;
}else{
$valor=$max_centro+0.1;
}*/
$consulta_p="select *,cue.Denominacion from  cwprepar inner join cwprecue as cue on Codigo = cue.CodCue where CHARACTER_LENGTH(Codigo) = 13 order by CodCue";
//echo $consulta_islr;
$resultado_partida=query($consulta_p,$conexion);

$consulta_m="select * from  materiales_segmento";
//echo $consulta_islr;
$resultado_seg=query($consulta_m,$conexion);

?>
	<tr>
	<td colspan="2" height="30" class="tb-tit"><strong>Agregar Nuevo Registro de <?echo $modulo?></strong></td>
    </tr>
	<tr><td class="tb-head">C&oacute;digo SNC</td>
      	<td colspan="6"><?echo "$codigo_snc";?> <INPUT type="hidden" name="codigo_snc" value="<?echo "$codigo_snc";?>"></td>
	</tr>
	<tr>
	<td class="tb-head" width="200">C&oacute;digo Material</td>
      	<td colspan="6"><?echo "$valor";?> <INPUT type="hidden" name="correlativo" value="<?echo "$val";?>"><INPUT type="hidden" name="cod_material" value="<?echo "$valor";?>"></td>
	</tr>
	<INPUT type="hidden" name="pagina" value="<?echo "$pagina";?>"></td>
	<INPUT type="hidden" name="paginam" value="<?echo "$paginam";?>"></td>
	
	<?
	$i=0;
	$cont=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
		//echo $campo;
		switch($campo)
		{

		case "codigo_cuenta":

			//echo "<TR>";?><td class=tb-head ><?echo "Partida Presupuestaria</td>";
			echo "<td colspan=\"3\"><SELECT name=\"codigo_cuenta\" id=\"codigo_cuenta\">";
			echo "<option value=\"\">Seleccione una Opcion</option>";
                        while($fila_partida=fetch_array($resultado_partida)){
				$codigo_partida=$fila_partida['CodCue'];
				//$descripcion_iva=$fila_iva['descripcion'];
				echo "<option value=\"$codigo_partida\">$codigo_partida</option>";
			}
			echo "</SELECT></td> </tr>";
		break;

		case "segmentos":

		?>
		<TR>
			<TD width="150" class="tb-head">Segmento:</TD>
			<TD>  <SELECT name="segmentos" id="segmentos" onchange="javascript:cargar_familias()">
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
						<OPTION class="tb-fila" value="0">Seleccione una familia</OPTION>
					</SELECT>  </TD>
		</TR>
		<TR>
			<TD width="150" class="tb-head">Clases:</TD>
			<TD>  <SELECT name="clases" id="clases">
						<OPTION class="tb-fila" value="0">Seleccione una clase</OPTION>
					</SELECT>  </TD>
		</TR>
		<TR>
			<TD width="150" class="tb-head">Producto:</TD>
			<TD>  <SELECT name="productos" id="productos">
					<OPTION class="tb-fila" value="0">Seleccione un producto</OPTION>
					</SELECT>  </TD>
		</TR>
		<?
		break;	
		

		default:
		
		echo "<TR>";?><td class=tb-head ><?echo "$nombre</td>";
		echo "<td colspan=\"3\"><INPUT type=\"text\" name=\"$campo\" size=\"100\"></td> </tr>";
		
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

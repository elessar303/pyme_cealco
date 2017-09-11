<?
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../header.php';

$con=conexion();
$consul="select * from ordenes where cod_requi=0 and tipo=6 and codigo=1094";
$result=query($consul,$con);
$num= 0;//mysql_num_rows($result);
/*echo '<table align="center">
			<tr>
			<td width="50" class="texto8">CODIGO</td>
			<td width="50" align="center" class="texto8">FECHA</td>
			<td width="50"align="center" class="texto8">UNIDAD</td>
			<td width="50"align="center" class="texto8">TIPO</td>
			<td width="150" align="center" class="texto8">ESTADO</td>
			<td width="150" align="center" class="texto8">COMPANIA</td>
			<td width="150" align="center" class="texto8">MONTO</td>
			</tr>
			</table>';
*/
//$i=33;
while($fila=fetch_array($result))
{
	
	$codigo=$fila['codigo'];
	$cod_ref=$fila['codigo_ref'];
	//$fecha=$fila['fecha'];
	//$unidad=$fila['unidad'];
	//$proveedor=$fila['cod_provee'];
	//$tipo=$fila['tipo'];
	//$estado=$fila['estado'];
	$monto_orden=$fila['monto_orden'];
	//$imponible=$fila['imponible'];
	$monto_iva=$fila['monto_iva'];
	$precio=$monto_orden-$monto_iva;
	$cod_requi=$fila['cod_requi'];
	//$consul2="select * from proveedores where cod_proveedor=".$proveedor;
	//$result2=query($consul2,$con);
	//$fila2=fetch_array($result2);
	//$nombre= $fila2['compania'];
	//$consul3="select * from ordenes_tipos where cod_orden_tipo=".$tipo;
	//$result3=query($consul3,$con);
	//$fila3=fetch_array($result3);
	//$descripcion= $fila3['descripcion'];
	//$insert="INSERT INTO requisiciones (cod_requisicion, agregada_fecha, situacion, unidad, cod_centro, concepto, fecha, tipo) VALUES ('".$i."', '".$fila['fecha']."', 'Registrada', '".trim($fila['unidad'])."', '".trim($fila['centro_costo'])."' , '".$fila['concepto']."' , '".$fila['fecha']."' , '".$fila['tipo']."')";
	$insert="INSERT INTO ordenes_detalles (cod_ord,cod_pro,cantidad_pedida,cantidad_des,precio,iva,total,total_gen,cod_requisicion,cod_ord_ref) VALUES('".$codigo."', 'O251399001', '1', '1', '".$precio."' , '".$monto_iva."' , '".$precio."' , '".$monto_orden."','".$cod_requi."','".$cod_ref."')";
	//$resultado=query($insert, $con);
	$cons = "UPDATE ordenes SET cod_produ='O251399001',tipo=5 WHERE codigo='".$fila['codigo']."'";
	$resu = query($cons, $con);
	//echo $cons."<br>";
	echo $insert." ".$cons." ".$num."<br>";
	$i++;
	$num++;
	
	/*
	echo '<table align="center>"
			
			<tr>
			<td width="50" class="texto8">'.$codigo.'</td>
			<td width="50" align="center" class="texto8">'.fecha($fecha).'</td>
			<td width="50"align="center" class="texto8">'.$unidad.'</td>
			<td width="50"align="center" class="texto8">'.$descripcion.'</td>
			<td width="150" align="center" class="texto8">'.$estado.'</td>
			<td width="150" align="center" class="texto8">'.$nombre.'</td>
			<td width="150" align="center" class="texto8">'.$monto.'</td>
			</tr>
			</table>';
	*/
}
echo "<br>Cantidad de Ordenes: ".$num;


?>



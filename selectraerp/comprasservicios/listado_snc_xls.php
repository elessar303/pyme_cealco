<?
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=listado_snc.xls");
	
	require_once '../lib/config.php';
	require_once '../lib/common.php';
	//include ("../header.php");

	$trimestre = $_GET['periodo'];
	//$fechaHasta = $_GET['fechaHasta'];
	
	$Conn=conexion_conf();
	$var_sql = "SELECT * FROM parametros";
	$rs = query($var_sql, $Conn);
	$row_rs = fetch_array($rs);
	$rif = $row_rs['rif'];
	$estado= $row_rs['estado'];
	$periodo = $row_rs['periodo'];
	$empresa = $row_rs['nomemp'];
	$presidente = $row_rs['presidente'];
	$telefono = $row_rs['telefono'];
	$email = $row_rs['email'];
	$encargado_administracion = $row_rs['pers_adm'];
	//$trimestre= ;
	cerrar_conexion($Conn);
	
	$conexion = conexion();

	switch($trimestre)
	{
		case "1": 
			$destrimestre="I TRIMESTRE";
			$desde=$periodo."-01-01";
			$hasta=$periodo."-03-31";
			//$consulta="select * from ordenes as ord left join ordenes_detalles as ode on ord.codigo=ode.cod_ord where estado='Comprometida' and fecha between '".$desde."' and '".$hasta."'";
		break;
		case "2": 
			$destrimestre="II TRIMESTRE";
			$desde=$periodo."-04-01";
			$hasta=$periodo."-06-30";
			//$consulta="select * from ordenes where estado='Comprometida' and fecha between '".$desde."' and '".$hasta."'";
		break;
		case "3": 
			$destrimestre="III TRIMESTRE";
			$desde=$periodo."-07-01";
			$hasta=$periodo."-09-30";
			//$consulta="select * from ordenes where estado='Comprometida' and fecha between '".$desde."' and '".$hasta."'";
		break;
		case "4": 
			$destrimestre="IV TRIMESTRE";
			$desde=$periodo."-10-01";
			$hasta=$periodo."-12-31";
			//$consulta="select * from ordenes where estado='Comprometida' and fecha between '".$desde."' and '".$hasta."'";
		break;
	}
	$consulta="select * from ordenes_detalles as ode left join ordenes as ord on ord.codigo=ode.cod_ord left join materiales as mat on ode.cod_pro=mat.cod_material left join selectra.analisis_cotizaciones as ac on ode.cod_cotizacion=ac.cod_cotizacion where estado='Comprometida' and fecha between '".$desde."' and '".$hasta."'";
	//echo $consulta;

	$resultado = query($consulta,$conexion);
	//$fila=fetch_array($resultado);
?>

<table border=0 width=100%>
	<tr class=tb-head width=100%>
        	<td vAlign=top><SPAN><STRONG>Organo Responsable</STRONG></SPAN></td>
		<td vAlign=top><SPAN><STRONG><?echo $empresa;?></STRONG></SPAN></td>
	</tr>
	<tr class=tb-head width=100%>
        	<td vAlign=top><SPAN><STRONG>Organo o Ente Ejecutor</STRONG></SPAN></td>
		<td vAlign=top><?echo $empresa;?></td>
	</tr>
	<tr class=tb-head width=100%>
        	<td vAlign=top><SPAN><STRONG>RIF del Organo o ente Ejecutor</STRONG></SPAN></td>
		<td vAlign=top><?echo $rif;?></td>
	</tr>
	<tr class=tb-head width=100%>
        	<td vAlign=top><SPAN><STRONG>Funcionario Responsable</STRONG></SPAN></td>
		<td vAlign=top><?echo $presidente;?></td>
	</tr>

	<tr class=tb-head width=100%>
        	<td vAlign=top><SPAN><STRONG>Funcionario Responsable</STRONG></SPAN></td>
		<td vAlign=top><?echo $presidente;?></td>
	</tr>
	<tr class=tb-head width=100%>
        	<td vAlign=top><SPAN><STRONG>Funcionario Contacto</STRONG></SPAN></td>
		<td vAlign=top><?echo $encargado_administracion;?></td>
	</tr>
	<tr class=tb-head width=100%>
        	<td vAlign=top><SPAN><STRONG>Telefono / Correo Electronico</STRONG></SPAN></td>
		<td vAlign=top><?echo $telefono." / ".$email;?></td>
	</tr>
	<tr class=tb-head width=100%>
        	<td vAlign=top><SPAN><STRONG>Trimestre</STRONG></SPAN></td>
		<td vAlign=top><?echo $destrimestre;?></td>
	</tr>
	<tr>	
		<td vAlign=top align="center"><STRONG>Detalle de la demanda (Descripcion del bien obra o servicio</STRONG></td>
		<td vAlign=top align="center"><STRONG>Actividad</STRONG></td>
		<td vAlign=top align="center"><STRONG>Unidad de Medida</STRONG></td>
		<td vAlign=top align="center"><STRONG>Cantidad del Bien Obra o Servicio</STRONG></td>
		<td vAlign=top align="center"><STRONG>Codigo del Producto en el Catalogo de ONU</STRONG></td>
		<td vAlign=top align="center"><STRONG>Modalidad de la Contratación</STRONG></td>
		<td vAlign=top align="center"><STRONG>Número de Procedimiento, Orden de Compra o Servicio</STRONG></td>
		<td vAlign=top align="center"><STRONG>Nombre u Objeto del Procedimiento</STRONG></td>
		<td vAlign=top align="center"><STRONG>Monto Total Bs.</STRONG></td>
		<td vAlign=top align="center"><STRONG>Fecha de Inicio de Procedimiento</STRONG></td>
		<td vAlign=top align="center"><STRONG>Fecha de Cierre de Procedimiento</STRONG></td>
		<td vAlign=top align="center"><STRONG>Método Utilizado para Obtener la Oferta</STRONG></td>
		<td vAlign=top align="center"><STRONG>Número de Contrato</STRONG></td>
		<td vAlign=top align="center"><STRONG>Fecha de Inicio de Contrato</STRONG></td>
		<td vAlign=top align="center"><STRONG>Fecha de Culminación de Contrato</STRONG></td>
		<td vAlign=top align="center"><STRONG>Lugar de Ejecución</STRONG></td>
		<td vAlign=top align="center"><STRONG>Aplicación del Articulos 2 y 19 del Decreto 4998</STRONG></td>
		<td vAlign=top align="center"><STRONG>Observaciones</STRONG></td>
	</tr>

<?
	while ($fila=fetch_array($resultado))
	{
?>
	<tr>	
		<td vAlign=top align="right"><?echo $fila['descripcion'];?></td>
		<td vAlign=top align="right"><?$fila['actividad'];?></td>
		<td vAlign=top align="right"><?echo $fila['unidad'];?></td>
		<td vAlign=top align="right"><?echo $fila['cantidad_pedida'];?></td>
		<td vAlign=top align="right"><?echo $fila['segmentos']."<br>".$fila['familias']."<br>".$fila['clases']."<br>".$fila['productos'];?></td>
		<td vAlign=top align="right"><?$fila['modalidad_contratacion'];?></td>
		<td vAlign=top align="right"><?echo "Orden Nro:".$fila['cod_ord'];?></td>
		<td vAlign=top align="right"><?echo $fila['concepto'];?></td>
		<td vAlign=top align="right"><?echo $fila['monto_orden'];?></td>
		<td vAlign=top align="right"><?echo fecha($fila['fecha_requisicion']);?></td>
		<td vAlign=top align="right"><?echo fecha($fila['fecha']);?></td>
		
		<td vAlign=top align="right"><?echo $fila['nro_contrato'];?></td>
		<td vAlign=top align="right"><?echo fecha($fila['fecha_inicio']);?></td>
		<td vAlign=top align="right"><?echo fecha($fila['fecha_terminacion']);?></td>
		<td vAlign=top align="right"><?echo $estado;?></td>
		<td vAlign=top align="right"><?echo fecha($fila['fecha_terminacion']);?></td>
	</tr>
<?
	}
cerrar_conexion($conexion);
?> 
</table>
</body>
</html>
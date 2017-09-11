<?
	require_once '../lib/common.php';
	include('../header.php');
	$accion = $_GET['accion'];
	$programa = $_GET['programa'];
	$conexion = conexion();
	$consultaAct = "SELECT * FROM cwpreact WHERE RecNoPro='".$programa."'";
	$resultadoAct = query($consultaAct, $conexion);
	cerrar_conexion($conexion);
	echo "<SELECT name=\"sel_actividad\" id=\"sel_actividad\" onchange=\"javascript:cargar_partida_2()\">";
	echo "<option value=\"0\">Seleccione una actividad</option>";
	while($filaAct = fetch_array($resultadoAct))
	{
		$codigoAct = $filaAct['RecNo'];
		$descripcionAct = $filaAct['Denominacion'];
		echo "<option value=\"$codigoAct\">".$descripcionAct."</option>";
	}
	echo "</SELECT>";
	
?>
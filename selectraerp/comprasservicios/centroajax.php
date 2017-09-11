<?

require_once '../lib/common.php';
$unidad=$_GET['unidad'];
$conexion=conexion();
$consulta_centro="select * from centros where cod_unidad=$unidad order by cod_centro";
$resultado_centro=query($consulta_centro,$conexion);
cerrar_conexion($conexion);
echo "<SELECT name=\"centro\" id=\"centro\">";
	while($fila_centro=fetch_array($resultado_centro)){
	$codigo_centro=$fila_centro['cod_centro'];
	$descripcion_centro=$fila_centro['descripcion'];
	echo "<option value=\"$codigo_centro\">".$codigo_centro." - ".utf8_encode($descripcion_centro)."</option>";
}
echo "</SELECT>";

	

?>
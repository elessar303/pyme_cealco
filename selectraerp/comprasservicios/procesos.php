<?

require_once '../lib/common.php';
$estado=$_GET['estado'];
$conexion=conexion();
$consulta_municipio="select * from municipios where cod_estado=".$estado;
$resultado_municipio=query($consulta_municipio,$conexion);
cerrar_conexion($conexion);
echo "<SELECT name=\"cod_municipio\" id=\"cod_municipio\">";
while($fila_municipio=fetch_array($resultado_municipio)){
	$codigo_municipio=$fila_municipio['cod_municipio'];
	$descripcion_municipio=$fila_municipio['nombre'];
	echo "<option value=\"$codigo_municipio\">".utf8_encode($descripcion_municipio)."</option>";
}
echo "</SELECT>";

	

?>
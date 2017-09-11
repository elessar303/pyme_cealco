<?
	require_once '../lib/common.php';
	include('../header.php');
	$accion = $_GET['accion'];
	//echo "AAAAAAAAAAAAA";
	switch ($accion)
	{
		case '1':
			$unidad = $_GET['unidad'];
			$conexion = conexion();
			$consultaPro = "SELECT cod_centro,descripcion FROM centros WHERE cod_unidad='".$unidad."' ORDER BY cod_centro";
			
			$resultadoPro = query($consultaPro, $conexion);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"cod_centro\" id=\"cod_centro\">";
			echo "<option value=\"0\">Seleccione un centro de costo</option>";
			while($filaPro = fetch_array($resultadoPro)){
				$codigo_cen = $filaPro['cod_centro'];
				$descripcion_cen = $filaPro['descripcion'];
				echo "<option value=\"$codigo_cen\">".$descripcion_cen."</option>";
			}
			echo "</SELECT>";
		break;

		
	}
?>
<?
	require_once '../lib/common.php';
	include('../header.php');
	$accion = $_GET['accion'];
	switch ($accion)
	{
		case '1':
			$segmento = $_GET['segmentos'];
			$conexion = conexion();
			$consultafam = "SELECT * FROM materiales_familias WHERE codigo_segmento='".$segmento."'";
			$resultadofam= query($consultafam, $conexion);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"familias\" id=\"familias\" onchange=\"javascript:cargar_clases()\">";
			echo "<option value=\"0\">Seleccione una familia</option>";
			while($filaf = fetch_array($resultadofam)){
				$codigof = $filaf['codigo'];
				$descripcionf = $filaf['descripcion'];
				echo "<option value=\"$codigof\">".$descripcionf."</option>";
			}
			echo "</SELECT>";
		break;

		case '2':
			$segmento = $_GET['segmentos'];
			$familias = $_GET['familias'];
			$conexion = conexion();
			$consultacla = "SELECT * FROM materiales_clases WHERE codigo_segmento='".$segmento."' and codigo_familia='".$familias."'";
			$resultadocla = query($consultacla, $conexion);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"clases\" id=\"clases\" onchange=\"javascript:cargar_productos()\">";
			echo "<option value=\"0\">Seleccione una clase</option>";
			while($filac = fetch_array($resultadocla)){
				$codigoc = $filac['codigo'];
				$descripcionc = $filac['descripcion'];
				echo "<option value=\"$codigoc\">".$descripcionc." - ".$codigoc."</option>";
			}
			echo "</SELECT>";
		break;

		case '3':
			$segmento = $_GET['segmentos'];
			$familias = $_GET['familias'];
			$clases = $_GET['clases'];
			$conexion = conexion();
			$consultapro = "SELECT * FROM materiales_productos WHERE codigo_segmento='".$segmento."' and codigo_familia='".$familias."' and  codigo_clase='".$clases."'";
			$resultadopro = query($consultapro, $conexion);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"productos\" id=\"productos\">";
			echo "<option value=\"0\">Seleccione un producto</option>";
			while($filap = fetch_array($resultadopro)){
				$codigop = $filap['codigo'];
				$descripcionp = $filap['descripcion'];
				echo "<option value=\"$codigop\">".$descripcionp."</option>";
			}
			echo "</SELECT>";
		break;
	}
		
?>
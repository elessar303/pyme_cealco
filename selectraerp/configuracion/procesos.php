<?
	require_once '../lib/common.php';
	include('../header.php');
	$accion = $_GET['accion'];
	switch ($accion)
	{
		case '1':
			$sector = $_GET['sector'];
			$conexion = conexion();
			$consultaPro = "SELECT * FROM cwprogra WHERE RecNoSec='".$sector."' ORDER BY RecNo";
			$resultadoPro = query($consultaPro, $conexion);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"sel_programa\" id=\"sel_programa\" onchange=\"javascript:cargar_actividad()\">";
			echo "<option value=\"0\">Seleccione un programa</option>";
			while($filaPro = fetch_array($resultadoPro)){
				$codigoPro = $filaPro['RecNo'];
				$descripcionPro = $filaPro['Denominacion'];
				echo "<option value=\"$codigoPro\">".$descripcionPro."</option>";
			}
			echo "</SELECT>";
		break;

		case '2':
			$sector = $_GET['sector'];
			$programa = $_GET['programa'];
			$conexion = conexion();
			$consultaAct = "SELECT * FROM cwpreact WHERE RecNoPro='".$programa."'";
			$resultadoAct = query($consultaAct, $conexion);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"sel_actividad\" id=\"sel_actividad\" onchange=\"javascript:cargar_partida()\">";
			echo "<option value=\"0\">Seleccione una actividad</option>";
			while($filaAct = fetch_array($resultadoAct)){
				$codigoAct = $filaAct['RecNo'];
				$descripcionAct = $filaAct['Denominacion'];
				echo "<option value=\"$codigoAct\">".$descripcionAct."</option>";
			}
			echo "</SELECT>";
		break;
		
		case '3':
			$conexion = conexion();
			$sector = $_GET['sector'];
			$programa = $_GET['programa'];
			$actividad = $_GET['actividad'];
			//echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		   //alert(\"$sector.$programa.$actividad\")</SCRIPT>";
			$conSector = "SELECT * FROM cwsector WHERE RecNo='".$sector."'";	
			$resSector = query($conSector, $conexion);
			$filaSector = fetch_array($resSector);
			$desSector = $filaSector['Denominacion'];
			$codSector = $filaSector['Sec'];
			
			$conPrograma = "SELECT * FROM cwprogra WHERE RecNo='".$programa."'";
			//echo $conPrograma;
			$resPrograma = query($conPrograma, $conexion);
			$filaPrograma = fetch_array($resPrograma);
			$desPrograma = $filaPrograma['Denominacion'];
			$codPrograma = $filaPrograma['Programa'];
			
			$conActividad = "SELECT * FROM cwpreact WHERE RecNo='".$actividad."'";
			//echo $conActividad;
			$resActividad = query($conActividad, $conexion);
			$filaActividad = fetch_array($resActividad);
			$desActividad = $filaActividad['Denominacion'];
			$codActividad = $filaActividad['Obr'];
			
			$consult = "SELECT * FROM cwprepar LEFT JOIN cwprecue ON cwprepar.Codigo=cwprecue.CodCue WHERE cwprepar.Sector='".$codSector."' AND cwprepar.Programa='".$codPrograma."' AND cwprepar.Actividad='".$codActividad."' AND cwprecue.Tipocta='4'  ORDER BY Codigo";
			$result = query($consult, $conexion);
			//echo $consult; exit(0);
			//echo "sec ".$codSector." pro ".$codPrograma." act ".$codActividad; exit(0);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"cod\" id=\"cod\">";
			echo "<OPTION value=\"0\">Selecccione una partida</OPTION>";
			while($fila=fetch_array($result)){
				$codigo=$fila['Codigo'];
				echo "<option value=\"$codigo\">".$codigo."</option>";
			}
			echo "</SELECT>";
		break;

		case '4':
			/*$sector = $_GET['sector'];
			$programa = $_GET['programa'];
			$actividad = $_GET['actividad'];
			$conexion = conexion();
			$consulta2 = "SELECT * FROM cwprepar WHERE Sector = '".$sector."' AND Programa = '".$programa."' AND Actividad = '".$actividad."' ";
			$resultado2 = query($consulta2,$conexion);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"partida\" id=\"partida\">";
			echo "<option value=\"0\">Seleccione una partida</option>";
			while($filapar = fetch_array($resultado2)){
				$partida = $filapar['Codigo'];
				//$descripcionAct = $filaAct['Denominacion'];
				echo "<option value=\"$partida\">".$partida."</option>";
			}
			echo "</SELECT>";
			*/

			$conexion = conexion();
			$sector = $_GET['sector'];
			$programa = $_GET['programa'];
			$actividad = $_GET['actividad'];
			//echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		   //alert(\"$sector.$programa.$actividad\")</SCRIPT>";
			$conSector = "SELECT * FROM cwsector WHERE RecNo='".$sector."'";	
			$resSector = query($conSector, $conexion);
			$filaSector = fetch_array($resSector);
			$desSector = $filaSector['Denominacion'];
			$codSector = $filaSector['Sec'];
			
			$conPrograma = "SELECT * FROM cwprogra WHERE RecNo='".$programa."'";
			//echo $conPrograma;
			$resPrograma = query($conPrograma, $conexion);
			$filaPrograma = fetch_array($resPrograma);
			$desPrograma = $filaPrograma['Denominacion'];
			$codPrograma = $filaPrograma['Programa'];
			
			$conActividad = "SELECT * FROM cwpreact WHERE RecNo='".$actividad."'";
			//echo $conActividad;
			$resActividad = query($conActividad, $conexion);
			$filaActividad = fetch_array($resActividad);
			$desActividad = $filaActividad['Denominacion'];
			$codActividad = $filaActividad['Obr'];
			
			$consult = "SELECT * FROM cwprepar LEFT JOIN cwprecue ON cwprepar.Codigo=cwprecue.CodCue WHERE cwprepar.Sector='".$codSector."' AND cwprepar.Programa='".$codPrograma."' AND cwprepar.Actividad='".$codActividad."' AND cwprecue.Tipocta='4'  ORDER BY Codigo";
			$result = query($consult, $conexion);
			//echo $consult; exit(0);
			//echo "sec ".$codSector." pro ".$codPrograma." act ".$codActividad; exit(0);
			cerrar_conexion($conexion);
			echo "<SELECT name=\"partida\" id=\"partida\">";
			echo "<OPTION value=\"0\">Selecccione una partida</OPTION>";
			while($fila=fetch_array($result)){
				$codigo=$fila['Codigo'];
				echo "<option value=\"$codigo\">".$codigo."</option>";
			}
			echo "</SELECT>";
			
		break;
	}
?>
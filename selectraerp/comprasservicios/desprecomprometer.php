<?
session_start();
ob_start();

$usr = $_SESSION['nombre'];

require_once '../lib/common.php';



$conexion_conf=conexion_conf();
$consulta_conf="select tipo_compromiso from parametros";
$resultado_conf=query($consulta_conf,$conexion_conf);
$fila_conf=fetch_array($resultado_conf);
//$sobregirop=$fila_conf['sobregirop'];
$compromiso = $fila_conf['tipo_compromiso'];
//$tipo_presupuesto = $fila_conf['tipo_presupuesto'];
cerrar_conexion($conexion_conf);

if($compromiso == "SI")
{

	$conexion=conexion();
	
	$consultalkk = "SELECT * FROM cwpreejc_tmp WHERE usr = '".$usr."'";
	$resultadolkk = query($consultalkk,$conexion);
	
	while($fila = fetch_array($resultadolkk))
	{
		$conPrePar1 = "SELECT * FROM cwprepar WHERE Codigo='".$fila['Partida']."' AND Sector='".$fila['Sector']."' AND Programa='".$fila['Programa']."' AND Actividad='".$fila['Actividad']."'";
		$resPrePar1 = query($conPrePar1, $conexion);
		$filaPrePar1 = fetch_array($resPrePar1);
		$precom = $filaPrePar1['Precom'];
		$precompromiso = $precom - $fila['Monto'];
		
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$fila['Partida']."' AND Sector='".$fila['Sector']."' AND Programa='".$fila['Programa']."' AND Actividad='".$fila['Actividad']."'";
		$resPrePar = query($conPrePar, $conexion);
						
						
		$Partida_sub_niv3 = substr($fila['Partida'], 0, 11);
		$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'",$conexion);
		$filaPrePar1 = fetch_array($result_int);
		$precom = $filaPrePar1['Precom'];
		$precompromiso = $precom - $fila['Monto'];
						
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar = query($conPrePar, $conexion);
						
						
		$Partida_sub_niv2 = substr($fila['Partida'], 0, 8);
		$result_int2  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
		$filaPrePar12 = fetch_array($result_int2);
		$precom = $filaPrePar12['Precom'];
		$precompromiso = $precom - $fila['Monto'];
					
		$conPrePar2 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar2 = query($conPrePar2, $conexion);
			
			
		$Partida_sub_niv1 = substr($fila['Partida'], 0, 5);
		$result_int1  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
		$filaPrePar11 = fetch_array($result_int1);
		$precom = $filaPrePar11['Precom'];
		$precompromiso = $precom - $fila['Monto'];								
						
		$conPrePar11 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar11 = query($conPrePar11, $conexion);				
					
						
						
		if($fila['ordinal']!="")
		{
			$consulta="select * from ordinales where ordinal='".$fila['ordinal']."' and sec='".$fila['Sector']."' and pro='".$fila['Programa']."' obr='".$fila['Actividad']."'";
			$resPrePar1 = query($consulta, $conexion);
			$filaPrePar1 = fetch_array($resPrePar1);
			$precom = $filaPrePar1['Precom'];
			$precompromiso = $precom - $fila['Monto'];
							
			$conPrePar = "UPDATE ordinales SET PreCom='".$precompromiso."' WHERE ordinal='".$fila['ordinal']."' and codigo='".$fila['Partida']."' and sec='".$fila['Sector']."' and pro='".$fila['Programa']."' obr='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);					
						
		}
	}
	
	
	$consultalkk2 = "DELETE FROM cwpreejc_tmp WHERE usr = '".$usr."'";
	$resultadolkk2 = query($consultalkk2,$conexion);
	
	$consultalkk3 = "DELETE FROM cwpreejc_tmp_valores WHERE usr = '".$usr."'";
	$resultadolkk3 = query($consultalkk3,$conexion);	
	
	cerrar_conexion($conexion);
}

echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"emision_ord.php\"
	</SCRIPT>";


?>
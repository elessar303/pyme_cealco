<?
	require_once '../lib/common.php';
	include ("../header.php");
	$conexion = conexion();
	$RecNoOrders = $_GET['RecNoOrders'];
	$Diferencia = $_GET['diferencia'];
	$Monto_Orden = $_GET['monto_orden'];
	$cod_requisicion = $_GET['cod_requisicion'];
	if($Diferencia != 0){
		$var_sql="update requisiciones set situacion = 'Adjudicada' where cod_requisicion=$cod_requisicion";
		$result = query($var_sql, $conexion);

		$consulta = "UPDATE ordenes SET estado='Emitida',saldo=".$Monto_Orden." WHERE codigo='".$RecNoOrders."'";
		$resultado = query($consulta,$conexion);
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"No se puede comprometer esta orden Automaticamente! Revise la codificacion realizada En el modulo de Ordenes Por Comprometer\")
		parent.cont.location.href=\"emision_orden_compras.php\"
		</SCRIPT>";
		$con = "DELETE FROM cwpreejc WHERE RecNoOrders='".$RecNoOrders."'";
		$res = query($con, $conexion);
		exit(0);
	} else {
		$consulta = "UPDATE ordenes SET estado='Comprometida' WHERE codigo='".$RecNoOrders."'";
		$resultado = query($consulta,$conexion);

		$var_sql="update requisiciones set situacion = 'Adjudicada' where cod_requisicion=$cod_requisicion";
		$result = query($var_sql, $conexion);

		$consulta = "SELECT * FROM cwpreejc where RecNoOrders='".$RecNoOrders."'";
		$resultado = query($consulta,$conexion);
		
		while($fila = fetch_array($resultado))
		{
			$conPrePar1 = "SELECT * FROM cwprepar WHERE Codigo='".$fila['Partida']."' AND Sector='".$fila['Sector']."' AND Programa='".$fila['Programa']."' AND Actividad='".$fila['Actividad']."'";
			$resPrePar1 = query($conPrePar1, $conexion);
			$filaPrePar1 = fetch_array($resPrePar1);
			$compromiso = $filaPrePar1['AcuCom'] + $fila['Monto'];
			$precom = $filaPrePar1['Precom'];
			$precompromiso = $precom - $fila['Monto'];
			$dispo = $filaPrePar1['Dispo'];
			$disponible = $dispo - $fila['Monto'];

			$conPrePar = "UPDATE cwprepar SET Dispo= '".$disponible."', AcuCom='".$compromiso."', Precom='".$precompromiso."' WHERE Codigo='".$fila['Partida']."' AND Sector='".$fila['Sector']."' AND Programa='".$fila['Programa']."' AND Actividad='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);
			
			$Partida_sub_niv3 = substr($fila['Partida'], 0, 11);
			$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'",$conexion);
			$filaPrePar1 = fetch_array($result_int);
			$compromiso = $filaPrePar1['AcuCom'] + $fila['Monto'];
			$precom = $filaPrePar1['Precom'];
			$precompromiso = $precom - $fila['Monto'];
			$dispo = $filaPrePar1['Dispo'];
			$disponible = $dispo - $fila['Monto'];
			
			$conPrePar = "UPDATE cwprepar SET Dispo= '".$disponible."', AcuCom='".$compromiso."', Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);
			
			$Partida_sub_niv3 = substr($fila['Partida'], 0, 8);
			$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
			$filaPrePar1 = fetch_array($result_int);
			$compromiso = $filaPrePar1['AcuCom'] + $fila['Monto'];
			$precom = $filaPrePar1['Precom'];
			$precompromiso = $precom - $fila['Monto'];
			$dispo = $filaPrePar1['Dispo'];
			$disponible = $dispo - $fila['Monto'];

			$conPrePar = "UPDATE cwprepar SET Dispo= '".$disponible."', AcuCom='".$compromiso."', Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);

			$Partida_sub_niv3 = substr($fila['Partida'], 0, 5);
			$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
			$filaPrePar1 = fetch_array($result_int);
			$compromiso = $filaPrePar1['AcuCom'] + $fila['Monto'];
			$precom = $filaPrePar1['Precom'];
			$precompromiso = $precom - $fila['Monto'];
			$dispo = $filaPrePar1['Dispo'];
			$disponible = $dispo - $fila['Monto'];
		
			$conPrePar = "UPDATE cwprepar SET Dispo= '".$disponible."', AcuCom='".$compromiso."', Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);
			
			if($fila['ordinal']!=""){
				$consulta="select * from ordinales where ordinal='".$fila['ordinal']."' and sec='".$fila['Sector']."' and pro='".$fila['Programa']."' obr='".$fila['Actividad']."'";
				$resPrePar1 = query($consulta, $conexion);
				$filaPrePar1 = fetch_array($resPrePar1);
				$compromiso = $filaPrePar1['compromiso'] + $fila['Monto'];
				$precom = $filaPrePar1['Precom'];
				$precompromiso = $precom - $fila['Monto'];
				
				$conPrePar = "UPDATE ordinales SET compromiso='".$compromiso."', PreCom='".$precompromiso."' WHERE ordinal='".$fila['ordinal']."' and codigo='".$fila['Partida']."' and sec='".$fila['Sector']."' and pro='".$fila['Programa']."' obr='".$fila['Actividad']."'";
				$resPrePar = query($conPrePar, $conexion);
			}
		}
	}
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"emision_orden_compras.php\"
	</SCRIPT>";
	cerrar_conexion();
?>
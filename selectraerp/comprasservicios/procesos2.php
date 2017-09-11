<?
session_start();
ob_start();


require_once '../lib/common.php';
$conex=conexion_conf();
$consulta="select * from parametros";
$resultado_iva=query($consulta,$conex);
$fila=fetch_array($resultado_iva);
$cta_iva = $fila['ctaiva'];
cerrar_conexion($conex);

$sec = $_GET['sector'];
$pro = $_GET['programa'];
$act = $_GET['actividad'];
$mto = $_GET['mto'];
$rno = $_GET['rno'];
$rpp = $_GET['rpp'];
$par = $_GET['partida'];
$nal = $_GET['nal'];
$usr = $_SESSION['nombre'];

$cant = $_GET['canti'];
$valor = $_GET['valor'];
$iva = $_GET['iva'];

$conexion = conexion();

$consultaval = "SELECT rpp,par,mto FROM cwpreejc_tmp_valores WHERE valor = '".$valor."' AND usr = '".$usr."' ";
$resultadoval = query($consultaval,$conexion);
$fetchval = fetch_array($resultadoval);

if($fetchval['rpp'] != '')
{
	$consultaval2 = "SELECT Monto FROM cwpreejc_tmp WHERE RecPrePar = '".$fetchval['rpp']."' AND Partida = '".$fetchval['par']."' AND usr = '".$usr."' ";
	$resultadoval2 = query($consultaval2,$conexion); 
	$fetchval2 = fetch_array($resultadoval2);
	if($fetchval['mto'] == $fetchval2['Monto'])
	{
		$consultadel = "DELETE FROM cwpreejc_tmp WHERE RecPrePar = '".$rpp."' AND Partida = '".$par."' AND usr = '".$usr."' ";
		$resultadodel = query($consultadel,$conexion);
	}
	else
	{
		$mto2 = $fetchval2['Monto'] - $fetchval['mto'];
		$consultaupd2 = "UPDATE cwpreejc_tmp SET Monto = '".$mto2."', saldo = '".$mto2."' WHERE RecPrePar = '".$rpp."' AND Partida = '".$par."' AND usr = '".$usr."' ";
		$resultadoupd2 = query($consultaupd2,$conexion);
	}

	$conPrePar1 = "SELECT Precom FROM cwprepar WHERE Codigo='".$par."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
	$resPrePar1 = query($conPrePar1, $conexion);
	$filaPrePar1 = fetch_array($resPrePar1);
	$precompromiso = $filaPrePar1['Precom'] - $fetchval['mto'];
			
	$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$par."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
	$resPrePar = query($conPrePar, $conexion);
					
	$Partida_sub_niv3 = substr($par, 0, 11);
	$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'",$conexion);
	$filaPrePar1 = fetch_array($result_int);
	$precompromiso = $filaPrePar1['Precom'] - $fetchval['mto'];
					
	$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
	$resPrePar = query($conPrePar, $conexion);
					
	$Partida_sub_niv2 = substr($par, 0, 8);
	$result_int2  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'", $conexion);
	$filaPrePar12 = fetch_array($result_int2);
	$precompromiso = $filaPrePar12['Precom'] - $fetchval['mto'];
				
	$conPrePar2 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
	$resPrePar2 = query($conPrePar2, $conexion);
				
	$Partida_sub_niv1 = substr($par, 0, 5);
	$result_int1  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'", $conexion);
	$filaPrePar11 = fetch_array($result_int1);
	$precompromiso = $filaPrePar11['Precom'] - $fetchval['mto'];
				
	$conPrePar11 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
	$resPrePar11 = query($conPrePar11, $conexion);
					
	if($nal!="NO")
	{
		$consulta="select * from ordinales where ordinal='".$nal."' and sec='".$sec."' and pro='".$pro."' obr='".$act."'";
		//echo $consulta." 86p";
		$resPrePar1 = query($consulta, $conexion);
		$filaPrePar1 = fetch_array($resPrePar1);
		$precompromiso = $filaPrePar1['Precom'] - $fetchval['mto'];
						
		$conPrePar = "UPDATE ordinales SET Precom='".$precompromiso."' WHERE ordinal='".$nal."' and codigo='".$par."' and sec='".$sec."' and pro='".$pro."' obr='".$act."'";
		$resPrePar = query($conPrePar, $conexion);
	}
	
	$consultadel = "DELETE FROM cwpreejc_tmp_valores WHERE valor = '".$valor."' AND usr = '".$usr."' ";
	$resultadodel = query($consultadel,$conexion);
	
	if($valor == $cant)
	{
		$consultadel2 = "DELETE FROM cwpreejc_tmp WHERE Partida = '".$cta_iva."' AND usr = '".$usr."'";
		$resultadodel2 = query($consultadel2,$conexion);	
	}
	
}
	
else
	
{
	$consulta2 = "SELECT Monto FROM cwpreejc_tmp WHERE RecPrePar = '".$rpp."' AND Partida = '".$par."' AND usr = '".$usr."' ";
	$resultado2 = query($consulta2,$conexion); 
	$fetch2 = fetch_array($resultado2);
	
	if($fetch2['Monto'] != '')
	{
		$consultavalnum = "SELECT MAX(numero) AS max FROM cwpreejc_tmp_valores";
		$resultadovalnum = query($consultavalnum,$conexion);
		$filavalnum = fetch_array($resultadovalnum);
		$maximo = $filavalnum['max'];
		$numero = $maximo + 1;
		
			
		$convalnum = "INSERT INTO cwpreejc_tmp_valores VALUES ('".$numero."', '".$rpp."', '".$mto."','".$valor."', '".$par."', '".$usr."')";
		$resvalnum = query($convalnum, $conexion);
		
		$montonv = $mto + $fetch2['Monto'];
		$consulta3 = "UPDATE cwpreejc_tmp SET Monto = '".$montonv."', saldo = '".$montonv."' WHERE RecPrePar = '".$rpp."' AND Partida = '".$par."' AND usr = '".$usr."' ";
		$resultado3 = query($consulta3,$conexion); 
		
		// PRECOMPROMISO DE CWPREPAR
		
		$conPrePar1 = "SELECT * FROM cwprepar WHERE Codigo='".$par."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
		$resPrePar1 = query($conPrePar1, $conexion);
		$filaPrePar1 = fetch_array($resPrePar1);
		$precompromiso = $filaPrePar1['Precom'] + $mto;
				
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$par."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
		$resPrePar = query($conPrePar, $conexion);
						
		$Partida_sub_niv3 = substr($par, 0, 11);
		$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'",$conexion);
		$filaPrePar1 = fetch_array($result_int);
		$precompromiso = $filaPrePar1['Precom'] + $mto;
						
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar = query($conPrePar, $conexion);
						
		$Partida_sub_niv2 = substr($par, 0, 8);
		$result_int2  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'", $conexion);
		$filaPrePar12 = fetch_array($result_int2);
		$precompromiso = $filaPrePar12['Precom'] + $mto;
					
		$conPrePar2 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar2 = query($conPrePar2, $conexion);
					
		$Partida_sub_niv1 = substr($par, 0, 5);
		$result_int1  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'", $conexion);
		$filaPrePar11 = fetch_array($result_int1);
		$precompromiso = $filaPrePar11['Precom'] + $mto;
					
		$conPrePar11 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar11 = query($conPrePar11, $conexion);
						
		if($nal!="NO")
		{
			$consulta="select * from ordinales where ordinal='".$nal."' and sec='".$sec."' and pro='".$pro."' obr='".$act."'";
			//echo $consulta." 166p";
			$resPrePar1 = query($consulta, $conexion);
			$filaPrePar1 = fetch_array($resPrePar1);
			$precompromiso = $filaPrePar1['Precom'] + $mto;
							
			$conPrePar = "UPDATE ordinales SET Precom='".$precompromiso."' WHERE ordinal='".$nal."' and codigo='".$par."' and sec='".$sec."' and pro='".$pro."' obr='".$act."'";
			$resPrePar = query($conPrePar, $conexion);
		}
	}
	else
	{
		$consultavalnum = "SELECT MAX(numero) AS max FROM cwpreejc_tmp_valores";
		$resultadovalnum = query($consultavalnum,$conexion);
		$filavalnum = fetch_array($resultadovalnum);
		$maximo = $filavalnum['max'];
		$numero = $maximo + 1;
		
			
		$convalnum = "INSERT INTO cwpreejc_tmp_valores VALUES ('".$numero."', '".$rpp."', '".$mto."','".$valor."', '".$par."', '".$usr."')";
		$resvalnum = query($convalnum, $conexion);
		
		$consulta = "SELECT max(codigo) as codigo FROM ordenes";
		$resultado = query($consulta,$conexion);
		while ($fetch = fetch_array($resultado)) 
		{
			$rno=$fetch['codigo'];		
		}		
		$rno=$rno+1;
	
		$consultajc = "SELECT MAX(RecNo) AS maximo FROM cwpreejc_tmp";
		$resultadojc = query($consultajc,$conexion);
		$filajc = fetch_array($resultadojc);
		$maximo = $filajc['maximo'];
		$RecNo = $maximo + 1;
		$Fecha = date('Y-m-d');
		//$Ordinal = 0;
	
		$conIn = "INSERT INTO cwpreejc_tmp VALUES ('".$RecNo."', '".$rpp."', '".$mto."','".$mto."', '".$Fecha."', '".$rno."', '".$par."', '".$sec."', '".$pro."', '".$act."', '', '".$usr."')";
		$resIn = query($conIn, $conexion);
		
		// PRECOMPROMISO DE CWPREPAR
		
		$conPrePar1 = "SELECT * FROM cwprepar WHERE Codigo='".$par."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
		$resPrePar1 = query($conPrePar1, $conexion);
		$filaPrePar1 = fetch_array($resPrePar1);
		$precompromiso = $filaPrePar1['Precom'] + $mto;
				
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$par."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
		$resPrePar = query($conPrePar, $conexion);
						
		$Partida_sub_niv3 = substr($par, 0, 11);
		$result_int3  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'",$conexion);
		$filaPrePar13 = fetch_array($result_int3);
		$precompromiso = $filaPrePar13['Precom'] + $mto;
						
		$conPrePar3 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar3 = query($conPrePar3, $conexion);
						
		$Partida_sub_niv2 = substr($par, 0, 8);
		$result_int2  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'", $conexion);
		$filaPrePar12 = fetch_array($result_int2);
		$precompromiso = $filaPrePar12['Precom'] + $mto;
					
		$conPrePar2 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar2 = query($conPrePar2, $conexion);
					
		$Partida_sub_niv1 = substr($par, 0, 5);
		$result_int1  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'", $conexion);
		$filaPrePar11 = fetch_array($result_int1);
		$precompromiso = $filaPrePar11['Precom'] + $mto;
					
		$conPrePar11 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar11 = query($conPrePar11, $conexion);
						
		if($nal!="NO")
		{
			$consulta="select * from ordinales where ordinal='".$nal."' and sec='".$sec."' and pro='".$pro."' obr='".$act."'";
			//echo $consulta." 243p";
			$resPrePar1 = query($consulta, $conexion);
			$filaPrePar1 = fetch_array($resPrePar1);
			$precompromiso = $filaPrePar1['Precom'] + $mto;
							
			$conPrePar = "UPDATE ordinales SET Precom='".$precompromiso."' WHERE ordinal='".$nal."' and codigo='".$par."' and sec='".$sec."' and pro='".$pro."' obr='".$act."'";
			$resPrePar = query($conPrePar, $conexion);
		}
	}
	
	if($valor == $cant)
	{
		$consulta11 = "SELECT RecNo FROM cwprepar WHERE Codigo='".$cta_iva."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
		$resultado11 = query($consulta11,$conexion);
		$fetch11 = fetch_array($resultado11);
		
		$consulta = "SELECT max(codigo) as codigo FROM ordenes";
		$resultado = query($consulta,$conexion);
		while ($fetch = fetch_array($resultado)) 
		{
			$rno=$fetch['codigo'];		
		}		
		$rno=$rno+1;   
	
		$consultajc = "SELECT MAX(RecNo) AS maximo FROM cwpreejc_tmp ";
		$resultadojc = query($consultajc,$conexion);
		$filajc = fetch_array($resultadojc);
		$maximo = $filajc['maximo'];
		$RecNo = $maximo + 1;
		$Fecha = date('Y-m-d');
		
		$conIn2 = "INSERT INTO cwpreejc_tmp VALUES ('".$RecNo."', '".$fetch11['RecNo']."', '".$iva."','".$iva."', '".$Fecha."', '".$rno."', '".$cta_iva."', '".$sec."', '".$pro."', '".$act."', '','".$usr."')";
		$resIn2 = query($conIn2, $conexion);
		
		// PRECOMPROMISO DE CWPREPAR
		
		$conPrePar1 = "SELECT * FROM cwprepar WHERE Codigo='".$cta_iva."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
		$resPrePar1 = query($conPrePar1, $conexion);
		$filaPrePar1 = fetch_array($resPrePar1);
		$precompromiso = $filaPrePar1['Precom'] + $iva;
				
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$cta_iva."' AND Sector='".$sec."' AND Programa='".$pro."' AND Actividad='".$act."'";
		$resPrePar = query($conPrePar, $conexion);
						
		$Partida_sub_niv3 = substr($cta_iva, 0, 11);
		$result_int3  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'",$conexion);
		$filaPrePar13 = fetch_array($result_int3);
		$precompromiso = $filaPrePar13['Precom'] + $iva;
						
		$conPrePar3 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar3 = query($conPrePar3, $conexion);
						
		$Partida_sub_niv2 = substr($cta_iva, 0, 8);
		$result_int2  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'", $conexion);
		$filaPrePar12 = fetch_array($result_int2);
		$precompromiso = $filaPrePar12['Precom'] + $iva;
					
		$conPrePar2 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar2 = query($conPrePar2, $conexion);
					
		$Partida_sub_niv1 = substr($cta_iva, 0, 5);
		$result_int1  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'", $conexion);
		$filaPrePar11 = fetch_array($result_int1);
		$precompromiso = $filaPrePar11['Precom'] + $iva;
					
		$conPrePar11 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$sec."' and Programa='".$pro."' and Actividad='".$act."'";
		$resPrePar11 = query($conPrePar11, $conexion);
						
		if($nal!="NO")
		{
			$consulta="select * from ordinales where ordinal='".$nal."' and sec='".$sec."' and pro='".$pro."' obr='".$act."'";
			//echo $consulta." 314p";
			$resPrePar1 = query($consulta, $conexion);
			$filaPrePar1 = fetch_array($resPrePar1);
			$precompromiso = $filaPrePar1['Precom'] + $iva;
							
			$conPrePar = "UPDATE ordinales SET Precom='".$precompromiso."' WHERE ordinal='".$nal."' and codigo='".$cta_iva."' and sec='".$sec."' and pro='".$pro."' obr='".$act."'";
			$resPrePar = query($conPrePar, $conexion);
		}
		
	}
}

$consultaiva = "SELECT * FROM cwpreejc_tmp WHERE Partida = '".$cta_iva."' AND usr = '".$usr."' ";
$resultadoiva = query($consultaiva,$conexion);
$fila = fetch_array($resultadoiva);
if($fila['Monto'] != '')
{
	if($fila['Monto'] != $iva)
	{
		$conupdiva = "UPDATE cwpreejc_tmp SET Monto = '".$iva."', saldo = '".$iva."' WHERE Partida = '".$cta_iva."' AND usr = '".$usr."' ";
		$resultadoupdiva = query($conupdiva,$conexion);
				
		$conPrePar1 = "SELECT * FROM cwprepar WHERE Codigo='".$fila['Partida']."' AND Sector='".$fila['Sector']."' AND Programa='".$fila['Programa']."' AND Actividad='".$fila['Actividad']."'";
		$resPrePar1 = query($conPrePar1, $conexion);
		$filaPrePar1 = fetch_array($resPrePar1);
		$precom = $filaPrePar1['Precom'];
		$precompromiso = $precom - $fila['Monto'];
		$precompromiso = $precompromiso + $iva;
		
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$fila['Partida']."' AND Sector='".$fila['Sector']."' AND Programa='".$fila['Programa']."' AND Actividad='".$fila['Actividad']."'";
		$resPrePar = query($conPrePar, $conexion);
						
						
		$Partida_sub_niv3 = substr($fila['Partida'], 0, 11);
		$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'",$conexion);
		$filaPrePar1 = fetch_array($result_int);
		$precom = $filaPrePar1['Precom'];
		$precompromiso = $precom - $fila['Monto'];
		$precompromiso = $precompromiso + $iva;
						
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar = query($conPrePar, $conexion);
						
						
		$Partida_sub_niv2 = substr($fila['Partida'], 0, 8);
		$result_int2  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
		$filaPrePar12 = fetch_array($result_int2);
		$precom = $filaPrePar12['Precom'];
		$precompromiso = $precom - $fila['Monto'];
		$precompromiso = $precompromiso + $iva;
					
		$conPrePar2 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv2."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar2 = query($conPrePar2, $conexion);
			
			
		$Partida_sub_niv1 = substr($fila['Partida'], 0, 5);
		$result_int1  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
		$filaPrePar11 = fetch_array($result_int1);
		$precom = $filaPrePar11['Precom'];
		$precompromiso = $precom - $fila['Monto'];								
		$precompromiso = $precompromiso + $iva;
						
		$conPrePar11 = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv1."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar11 = query($conPrePar11, $conexion);				
					
						
						
		if($fila['ordinal']!="")
		{
			$consulta="select * from ordinales where ordinal='".$fila['ordinal']."' and sec='".$fila['Sector']."' and pro='".$fila['Programa']."' obr='".$fila['Actividad']."'";
			//echo $consulta." 384p";
			$resPrePar1 = query($consulta, $conexion);
			$filaPrePar1 = fetch_array($resPrePar1);
			$precom = $filaPrePar1['Precom'];
			$precompromiso = $precom - $fila['Monto'];
			$precompromiso = $precompromiso + $iva;
							
			$conPrePar = "UPDATE ordinales SET PreCom='".$precompromiso."' WHERE ordinal='".$fila['ordinal']."' and codigo='".$fila['Partida']."' and sec='".$fila['Sector']."' and pro='".$fila['Programa']."' obr='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);					
						
		}	
	}
}

cerrar_conexion($conexion);
?>
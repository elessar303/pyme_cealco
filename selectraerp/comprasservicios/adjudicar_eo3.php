<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include("../config_bd.php"); // archivo que llama a la base de datos
include ("../header.php");

$conexion_conf=conexion_conf();
$consulta_conf="select sobregirop,tipo_compromiso from parametros";
$resultado_conf=query($consulta_conf,$conexion_conf);
$fila_conf=fetch_array($resultado_conf);
$sobregirop=$fila_conf['sobregirop'];
$compromiso = $fila_conf['tipo_compromiso'];
//$tipo_presupuesto = $fila_conf['tipo_presupuesto'];
cerrar_conexion($conexion_conf);


$Conn2=conexion();

if(($_POST['bandera'] == 1) && ($sobregirop == 'N'))
{
	
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"NO FUE POSIBLE REALIZAR LA OPERACION, NO PUEDE SOBREGIRAR LA PARTIDA PRESUPUESTARIA!! \")
	parent.cont.location.href=\"emision_ord.php\"	
	</SCRIPT>";
	exit(O);
	//header("Location: emision_ord.php");	
}

$x_cod_riqui = @$_GET["cod_riqui"];
$x_tipo = @$_POST["tipo"];
$x_cod_riqui2 = @$_POST["cod_ri"];
$var_falta= @$_GET["falta"];
$var_total_reg = @$_GET["total_reg"];
$var_accion = @$_GET["acc"];
$var_cod_or = @$_GET["orden"];
$x_proveedor = $_POST["proveedor"];
$x_fecha = fecha_sql($_POST["fecha"]);
$x_unidad = $_POST["unidad"];
$x_centro_cos = $_POST["centro_cos"];
$x_montoorden = $_POST["montoorden"];
$x_diascredito = $_POST["diascredito"];
$x_imponible = $_POST["imponible"];
$x_montoiva = $_POST["montoiva"];
$x_montoexcento = $_POST["montoexcento"];
$x_funcion = $_POST["funcion"];
$x_tipo3 = $_POST["tipo"];
$x_concepto = $_POST["concepto"];
$x_obser = $_POST["obser"];
$x_validar_sino = $_POST["validar_sino"];






if ($x_proveedor<>'')
{
	$rs = query("SELECT * FROM proveedores where compania = '".$x_proveedor."' ",$Conn2);
	while ($row_rs = fetch_array($rs)) 
	{
		$x_cod_proveedor=$row_rs['cod_proveedor'];		
		$ced = $row_rs['rif'];
	}	
}




if(isset($_POST['Submit2']))
{
	
	

	$cad = explode('.',$ced);
	foreach ($cad as $val)
	{
		$c2 .= $val;
	}	

	$conProv = "SELECT * FROM ordenes WHERE cod_provee='".$x_cod_proveedor."' AND tipo='8' ";
	$resProv = query($conProv, $Conn2);
	$regProv = num_rows($resProv);
	if($regProv > 0){
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Este beneficiario ya tiene una orden de donación, VERIFIQUE!! \")
		</SCRIPT>";
	}
	
	cerrar_conexion($Conn2);
	
	/*
	$conexionsol = conexion_sol();
	
	$consulta20 = "SELECT * FROM beneficiario WHERE cedula = '".$c2."' AND ganador = '1' ";
	$resultado20 = query($consulta20, $conexionsol);
	//$fetch = fetch_array($resultado20);
	if($fetch20 = fetch_array($resultado20)){
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Este beneficiario es ganador en el sorteo N° $fetch20[sorteo]  \")
		</SCRIPT>";
	}

	$consulta21 = "SELECT * FROM beneficiarios_fideicomiso WHERE cedula = '".$c2."' ";
	$resultado21 = query($consulta21, $conexionsol);
	//$fetch = fetch_array($resultado20);
	if($fetch21 = fetch_array($resultado21)){
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Este beneficiario tiene asignado un fideicomiso (N° $fetch21[fideicomiso]) en el sorteo N° $fetch21[sorteo]  \")
		</SCRIPT>";
	}

	cerrar_conexion($conexionsol);
	*/
	



}
$Conn=conexion();


if (isset($_POST["fecha_o"]))
{
	$x_fecha = fecha_sql($_POST["fecha_o"]);
}

if ($var_accion=='adj')
{
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert('entro')
	</SCRIPT>";
	//$conexion=conexion();
	$rs = query("SELECT monto_orden FROM ordenes where cod_requi = '$x_cod_riqui' and estado <> 'Anulado'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_monto_orden_adj=$row_rs['monto_orden'];		
	}

	$var_sql="update ordenes set estado = 'Emitida',saldo='$var_monto_orden_adj' where cod_requi = $x_cod_riqui and estado <> 'Anulado'";			
	$result = query($var_sql, $Conn); 
	
	$var_sql="update requisiciones set situacion = 'Adjudicada' where cod_requisicion=$x_cod_riqui";
	$result = query($var_sql, $Conn); 
	cerrar_conexion($Conn);

	

		
	


echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"emision_ord.php\"
	</SCRIPT>";
	 
}//Esto adjudica la orden!!!

if ($var_accion=='cam')
{
	$rs = query("SELECT codigo FROM ordenes where cod_requi = '$x_cod_riqui' and estado <> 'Anulado'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_codigo_orden_adj=$row_rs['codigo'];		
	}
	
	$var_sql="delete from ordenes where cod_requi = '$x_cod_riqui' and estado <> 'Anulado' and codigo = '$var_codigo_orden_adj'";			
	
	$result = query($var_sql, $Conn); 
	
	$var_sql="delete from ordenes_detalles where cod_requisicion = '$x_cod_riqui' and cod_ord = '$var_codigo_orden_adj'";			
	$result = query($var_sql, $Conn); 
	
	$var_sql="update requisiciones set situacion = 'Registrada' where cod_requisicion=$x_cod_riqui";
	$result = query($var_sql, $Conn); 
	//mysql_close($conectar);
	header("Location: emision_ord.php");		
}

if ($var_falta=='1')
{
	 echo '<font color="#FF0000"><strong> Faltan Datos Requeridos. </strong></font>';
}

$rs = query("SELECT concepto FROM ordenes where cod_requi = '$x_cod_riqui' and estado <> 'Anulado'",$Conn);
while ($row_rs = fetch_array($rs)) 
{
	$var_concepto2=$row_rs['concepto'];		
}
//$rs->close();

if ($x_cod_riqui<>'')
{
	$rs = query("SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,r.tipo FROM requisiciones r where r.cod_requisicion = $x_cod_riqui",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_fecha_re = fecha($row_rs['fecha']);
		$var_situacion = $row_rs['situacion'];
		$var_descripcion = $row_rs['descripcion'];
		$var_cod_centro = $row_rs['cod_centro'];
		$var_unidad_sol = $row_rs['unidad'];
		$var_concepto = $row_rs['concepto'];	
		$x_tipo2=$row_rs['tipo'];
	}
	cerrar_conexion($Conn);
	$conexion = conexion_conf();
    $rs = query("select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_por_iva=numero($row_rs['por_isv'],0);	
	}
	//$rs->close();
	cerrar_conexion($conexion);
	$Conn=conexion();
	
	$rs33 = query("SELECT sel_sector,sel_programa,sel_actividad FROM centros where cod_centro = $var_cod_centro",$Conn);
	$fetch33 = fetch_array($rs33);
	/*echo $fetch33['sel_sector'];
	echo $fetch33['sel_programa'];
	echo $fetch33['sel_actividad'];
	*/
	$rs = query("SELECT descripcion FROM unidades where cod_unidad = $var_unidad_sol",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_unidad=$row_rs['descripcion'];		
	}
	//$rs->close();
	
	$rs = query("SELECT descripcion FROM centros where cod_centro='".$var_cod_centro."'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_centro=$row_rs['descripcion'];		
	}
	//$rs->close();
	
	
	$rs = query("SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = '$x_tipo2'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_descripcion2=$row_rs['descripcion'];		
	}	
	//$rs->close();
	$con=0;
	$rs = query("SELECT * FROM requisiciones_det WHERE (cod_requisicion = $x_cod_riqui)	ORDER BY cod_requisicion_det ",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$con=$con+1;
	}
	//$rs->close();
}

//echo "PAAPA".$var_accion;
//exit(0);
if ($var_accion=='sav' || $var_accion=='act')
{
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert('no entro')
	</SCRIPT>";
	$rs = query("SELECT max(codigo) as codigo FROM ordenes",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_codigo=$row_rs['codigo'];		
	}		
	//$rs->close();
	$var_codigo=$var_codigo+1;   		


	$rs = query("SELECT descripcion FROM ordenes_tipos where descripcion = '$x_tipo3'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_tipo_des=$row_rs['descripcion'];		
	}	
	//$rs->close();
	
	$rs = query("SELECT codigo FROM ordenes_tipos where descripcion = '$x_tipo3'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_codigo_cons=$row_rs['codigo'];		
	}	
	$var_codigo_cons=$var_codigo_cons+1;
	if ($var_accion=='sav')
	{
		echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert('entro a sav')
	</SCRIPT>";
		$var_sql="insert into ordenes values('$var_codigo','$x_fecha','$x_unidad','$x_centro_cos','$x_montoorden','$x_diascredito','$x_concepto','$x_obser','$x_tipo2','$x_imponible','$x_montoiva','$x_montoexcento','-','$x_cod_proveedor',0,'$x_cod_riqui','Revisar','0','$var_codigo_cons','')";				
		//$result = query($var_sql, $conectar);
		$rs = query("select cod_orden_tipo from ordenes_tipos where descripcion = '$var_descripcion2'",$Conn);
		while ($row_rs = fetch_array($rs)) 
		{		
			$x_cod_orden_tipo_req=$row_rs['cod_orden_tipo'];
		}
		//$rs->close();
				
		$var_sql3="update requisiciones set tipo = '$x_cod_orden_tipo_req' where cod_requisicion=$x_cod_riqui";
		$result = query($var_sql3, $conectar);
		
		$var_sql2="update ordenes_tipos set codigo = '$var_codigo_cons' where descripcion = '$x_tipo3'";		
		$result = query($var_sql2, $conectar); 
		if (!$result) 
		{
			die('Invalid query2: ' . mysql_error());
			exit();
		}
		
		
		//------------------------------------- PARA COMPROMETER PARTE 1
		if($compromiso == "SI")
		{
		echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert('entro a SIII')
		</SCRIPT>";
		$conexion = conexion();
		$consultajc = "SELECT MAX(RecNo) AS maximo FROM cwpreejc";
		$resultadojc = query($consultajc,$conexion);
		$filajc = fetch_array($resultadojc);
		$maximo = $filajc['maximo'];
		$RecNo = $maximo + 1;
		$Fecha = date('Y-m-d');
		
		
		$Ordinal = $_POST['Ordinal'];
		$_POST['Monto'] = $_POST['montoorden'];
		$_POST['RecNoOrders'] = $var_codigo;
		
		$conIn = "INSERT INTO cwpreejc VALUES ('".$RecNo."', '".$_POST['RecPrePar']."', '".$_POST['Monto']."','".$_POST['Monto']."', '".$Fecha."', '".$_POST['RecNoOrders']."', '".$_POST['Partida']."', '".$_POST['Sector']."', '".$_POST['Programa']."', '".$_POST['Actividad']."', '".$Ordinal."')";
		$resIn = query($conIn, $conexion);

		$conCW = "SELECT * FROM cwprepar WHERE Codigo='".$_POST['RecPrePar']."'";
		$resCW = query($conCW, $conexion);
		$filaCW = fetch_array($resCW);
		$precom = $filaCW['Precom'];
		$precompromiso = $precom + $_POST['Monto'];
		
		$consulta = "SELECT * FROM cwpreejc where RecNoOrders='".$_POST['RecNoOrders']."' AND Partida='".$_POST['Partida']."'";
		$resultado = query($consulta,$conexion);
		$fila = fetch_array($resultado);
		
		$conPrePar1 = "SELECT * FROM cwprepar WHERE Codigo='".$fila['Partida']."' AND Sector='".$fila['Sector']."' AND Programa='".$fila['Programa']."' AND Actividad='".$fila['Actividad']."'";
		$resPrePar1 = query($conPrePar1, $conexion);
		$filaPrePar1 = fetch_array($resPrePar1);
		$precompromiso = $filaPrePar1['Precom'] + $_POST['Monto'];
			
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$fila['Partida']."' AND Sector='".$fila['Sector']."' AND Programa='".$fila['Programa']."' AND Actividad='".$fila['Actividad']."'";
		$resPrePar = query($conPrePar, $conexion);
			
		$Partida_sub_niv3 = substr($fila['Partida'], 0, 10);
		$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'",$conexion);
		$filaPrePar1 = fetch_array($result_int);
		$precompromiso = $filaPrePar1['Precom'] + $_POST['Monto'];
			
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar = query($conPrePar, $conexion);
			
		$Partida_sub_niv3 = substr($fila['Partida'], 0, 7);
		$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
		$filaPrePar1 = fetch_array($result_int);
		$precompromiso = $filaPrePar1['Precom'] + $_POST['Monto'];
		
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar = query($conPrePar, $conexion);
		
		$Partida_sub_niv3 = substr($fila['Partida'], 0, 4);
		$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
		$filaPrePar1 = fetch_array($result_int);
		$precompromiso = $filaPrePar1['Precom'] + $_POST['Monto'];
		
		$conPrePar = "UPDATE cwprepar SET Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
		$resPrePar = query($conPrePar, $conexion);
			
		if($fila['Ordinal']!="")
		{
			$consulta="select * from ordinales where ordinal='".$fila['ordinal']."' and sec='".$fila['Sector']."' and pro='".$fila['Programa']."' obr='".$fila['Actividad']."'";
			$resPrePar1 = query($consulta, $conexion);
			$filaPrePar1 = fetch_array($resPrePar1);
			$precompromiso = $filaPrePar1['Precom'] + $_POST['Monto'];
				
			$conPrePar = "UPDATE ordinales SET Precom='".$precompromiso."' WHERE ordinal='".$fila['ordinal']."' and codigo='".$fila['Partida']."' and sec='".$fila['Sector']."' and pro='".$fila['Programa']."' obr='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);
		}
		
//-------------------------------------------------------------- PARA COMPROMETER PARTE 2


		
		$RecNoOrders = $_POST['RecNoOrders'];
		$consulta = "UPDATE ordenes SET estado='Comprometida' WHERE codigo='".$RecNoOrders."'";
		$resultado = query($consulta,$conexion);

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
			
			$Partida_sub_niv3 = substr($fila['Partida'], 0, 10);
			$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'",$conexion);
			$filaPrePar1 = fetch_array($result_int);
			$compromiso = $filaPrePar1['AcuCom'] + $fila['Monto'];
			$precom = $filaPrePar1['Precom'];
			$precompromiso = $precom - $fila['Monto'];
			$dispo = $filaPrePar1['Dispo'];
			$disponible = $dispo - $fila['Monto'];
			
			$conPrePar = "UPDATE cwprepar SET Dispo= '".$disponible."', AcuCom='".$compromiso."', Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);
			
			$Partida_sub_niv3 = substr($fila['Partida'], 0, 7);
			$result_int  = query("SELECT * FROM cwprepar WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'", $conexion);
			$filaPrePar1 = fetch_array($result_int);
			$compromiso = $filaPrePar1['AcuCom'] + $fila['Monto'];
			$precom = $filaPrePar1['Precom'];
			$precompromiso = $precom - $fila['Monto'];
			$dispo = $filaPrePar1['Dispo'];
			$disponible = $dispo - $fila['Monto'];

			$conPrePar = "UPDATE cwprepar SET Dispo= '".$disponible."', AcuCom='".$compromiso."', Precom='".$precompromiso."' WHERE Codigo='".$Partida_sub_niv3."' and Sector='".$fila['Sector']."' and Programa='".$fila['Programa']."' and Actividad='".$fila['Actividad']."'";
			$resPrePar = query($conPrePar, $conexion);

			$Partida_sub_niv3 = substr($fila['Partida'], 0, 4);
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
		
		
		
		
		

	}   
	if ($var_accion=='act')	
	{
		echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert('entro a act')
	</SCRIPT>";
		$rs = query("SELECT cod_proveedor FROM proveedores where compania = '$x_proveedor'",$Conn);
		while ($row_rs = fetch_array($rs)) 
		{
			$x_proveedor=$row_rs['cod_proveedor'];		
		}
		//$rs->close();	
		
		$var_sql="update ordenes set saldo='$x_montoorden',monto_orden='$x_montoorden',dias_credito='$x_diascredito',concepto='$x_concepto',obser='$x_obser',imponible='$x_imponible',monto_iva='$x_montoiva',monto_excento='$x_montoexcento',cod_provee='$x_proveedor',fecha='$x_fecha' where codigo = $var_cod_or and estado <> 'Anulado'";		
		
	}
	$result = query($var_sql, $conectar);	
	
	//echo "Otro".$var_accion; exit(0);
	

	$conectar=conexion();
	if ($var_accion=='act')
	{
		$con="select * from ordenes_detalles where cod_ord=".$var_cod_or;
		$res=query($con,$conectar);
		$num=mysql_num_rows($res);
		if ($num>0)
		{
			$consulta="delete from ordenes_detalles where cod_ord=".$var_cod_or;
			$resultado=query($consulta,$conectar);
		}
	}		
	for($i=1; $i<=$var_total_reg; $i++){

//echo "i: ".$i;
		$cad_mat="cod_material_o".$i;
		$cad_cant="cantidad_o".$i;
		$cad_cant_des="cantidad_des_o".$i;
		$cad_precio="precio".$i;
		$cad_iva_sino="iva_sino_o".$i;
		$cad_total="total_o".$i;
		$x_cod_material = $_POST[$cad_mat];	
		$x_cantidad = $_POST[$cad_cant];	
		$x_cantidad_des = $_POST[$cad_cant_des];
		$x_precio = $_POST[$cad_precio];	
		$x_iva_sino = $_POST[$cad_iva_sino];	
		$x_total = $_POST[$cad_total];
		$total = $x_precio*$x_cantidad;
		$sub_total = $x_iva_sino + ($x_precio*$x_cantidad);
		$x_montoorden = $_POST["montoorden"];
		//echo "cad_iva_sino: ".$cad_iva_sino;
		//echo"<br>";
		//echo "iva_sino: ".$x_iva_sino;
		//echo"var_action: ".$var_accion;
		//exit(0);
		if ($var_accion=='sav')
		{
			$var_sql="insert into ordenes_detalles values('$var_codigo','$x_cod_material','$x_cantidad','$x_cantidad_des','$x_precio','$x_iva_sino','$total','$sub_total','$x_cod_riqui','$var_codigo_cons')";
		}
		//echo "Para editar ordenes detalles".$var_accion; //exit(0);
		if ($var_accion=='act')
		{
			$var_sql="insert into ordenes_detalles values('$var_cod_or','$x_cod_material','$x_cantidad','$x_cantidad_des','$x_precio','$x_iva_sino','$total','$sub_total','$x_cod_riqui','$var_codigo_cons')";
			//$var_sql="update ordenes_detalles set precio='$x_precio',iva='$x_iva_sino',total='$x_total',total_gen='$x_montoorden' where cod_ord = '$var_cod_or' and cod_pro='$x_cod_material'";		
				//echo "Esto es accion de editar detalles".$var_sql;
		}
		$result = query($var_sql, $conectar); 
	}

	//echo $var_sql."<br>";	
	//exit(0);
/*******************************************************/
	

	$rs = query("SELECT codigo,fecha,monto_orden,dias_credito,imponible,monto_iva,monto_excento,funcion,cod_provee,cod_requi,concepto,tipo FROM ordenes where cod_requi = $x_cod_riqui and estado = 'Anulado'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_cod_orden=$row_rs['codigo'];
		$var_fecha=fecha($row_rs['fecha']);
		$var_monto_orden=$row_rs['monto_orden'];
		$var_dias_credito=$row_rs['dias_credito'];
		$var_imponible=$row_rs['imponible'];
		$var_monto_iva=$row_rs['monto_iva'];
		$var_monto_excento=$row_rs['monto_excento'];
		$var_funcion=$row_rs['funcion'];
		$var_cod_provee=$row_rs['cod_provee'];		
		$var_tipo_ord=$row_rs['tipo'];		
	}
	if (!$rs) 
	{
		die('Invalid query: ' . mysql_error());
		exit();
	}
	//$rs->close();
	
	$rs = query("select descripcion from ordenes_tipos where cod_orden_tipo = '$var_tipo_ord'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_tipo_ord_des=$row_rs[descripcion];
	}
	//$rs->close();
	

echo '<SCRIPT language="JavaScript" type="text/javascript">
parent.cont.location.href="emision_ord.php?acc=sal"
</SCRIPT>';

}

if ($var_accion=='')
{

	$rs = query("SELECT codigo,fecha,monto_orden,dias_credito,imponible,monto_iva,monto_excento,funcion,cod_provee,cod_requi,tipo FROM ordenes where cod_requi = $x_cod_riqui and estado <> 'Anulado'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_cod_orden=$row_rs['codigo'];
		$var_fecha=fecha($row_rs['fecha']);
		$var_monto_orden=$row_rs['monto_orden'];
		$var_dias_credito=$row_rs['dias_credito'];
		$var_imponible=$row_rs['imponible'];
		$var_monto_iva=$row_rs['monto_iva'];
		$var_monto_excento=$row_rs['monto_excento'];
		$var_funcion=$row_rs['funcion'];
		$var_cod_provee=$row_rs['cod_provee'];		
		$var_tipo_ord=$row_rs['tipo'];		
	}
	//$rs->close();
	$rs = query("select descripcion from ordenes_tipos where cod_orden_tipo = '$var_tipo_ord'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_tipo_ord_des=$row_rs[descripcion];
	}
	//$rs->close();
	
	$rs = query("SELECT compania FROM proveedores where cod_proveedor = '$var_cod_provee'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_cod_provee=$row_rs['compania'];		
	}
	//$rs->close();
}

?>
<?php include ("../ewconfig.php") ?>
<?php //include ("db.php") ?>
<?php include ("proveedores_info.php") ?>
<?php include ("../advsecu.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php include ("../ewupload.php") ?>
<?php 

?>


<script language="javascript" src="../lib/common.js"></script>

<script>

function actualizar_formulario(valor,num_reg)
{

total_gen = parseFloat(window.document.fproveedoresadd.montoorden.value)	

//capturamos el valor del iva
var cadena="iva_sino"+valor
var cadena_temp=document.getElementById(cadena)
valor_iva = parseFloat(cadena_temp.value)

//capturamos el valor del precio
cadena="precio"+valor

cadena_temp=document.getElementById(cadena)
textoCampo =parseFloat(cadena_temp.value)

//valor de la cantidad
cadena="cantidad_des"+valor
cadena_temp=document.getElementById(cadena)
cantidad =parseFloat(cadena_temp.value)

//alert(total_gen+" "+textoCampo+" "+cantidad)

var total=(textoCampo*cantidad)+valor_iva

cadena="total"+valor
cadena_temp=document.getElementById(cadena)
cadena_temp.value=total

cadena="total_o"+valor
cadena_temp=document.getElementById(cadena)
cadena_temp.value=total


	//valor_iva = parseFloat(window.document.fproveedoresadd.iva_sino1.value)

	//textoCampo = parseFloat(window.document.fproveedoresadd.precio1.value)
	//cantidad = parseFloat(window.document.fproveedoresadd.cantidad_des1.value)	
	//window.document.fproveedoresadd.total1.value = total
	//window.document.fproveedoresadd.total_o1.value = total
		if (valor_iva==0)
		{			
			cadena="montoexcento"+valor
			cadena_temp=document.getElementById(cadena)
			cadena_temp.value=textoCampo*cantidad
			//window.document.fproveedoresadd.montoexcento1.value = textoCampo*cantidad
		}
		if (valor_iva>0)
		{		
			cadena="montoexcento"+valor
			cadena_temp=document.getElementById(cadena)
			cadena_temp.value=0	
			//window.document.fproveedoresadd.montoexcento1.value = 0
		}
}

function checkMyFormS(valor,num_reg,var_por_iva,dispo) 
{

	var cadena,temp, i,total=0,cad_iva,temp_iva,cad_cantidad,temp_cantidad,sub_total=0
	var cad_iva_sino,temp_iva_sino,cad_iva_sino_o,temp_iva_sino_o,cadena_total,temp_total,cadena_imponible,temp_imponible

//cadena de cantidad
	cad_cantidad="cantidad_des"+valor
	temp_cantidad=document.getElementById(cad_cantidad)

//cadena de iva
	cad_iva_sino="iva_sino"+valor
	temp_iva_sino=document.getElementById(cad_iva_sino)
	cad_iva_sino_o="iva_sino_o"+valor
	temp_iva_sino_o=document.getElementById(cad_iva_sino_o)

//cadena total
	cadena_total="total"+valor
	temp_total=document.getElementById(cadena_total)
	cadena_total_o="total_o"+valor
	temp_total_o=document.getElementById(cadena_total)

//cadena imponible
	cadena_imponible="imponible"+valor
	temp_imponible=document.getElementById(cadena_imponible)

	cadena="precio"+valor
	temp_precio=document.getElementById(cadena)
	var_pre=parseFloat(temp_precio.value)
	if (!var_pre)
	{
		temp_iva_sino.value = 0
		temp_iva_sino_o.value = 0	
	}else{
		cad_iva="iva_sino_opt"+valor
		temp_iva=document.getElementById(cad_iva)
	
			if (temp_iva.checked)
			{
				temp_iva.value=1
				textoCampo1 = parseFloat(temp_precio.value)
				
				cantidad = parseFloat(temp_cantidad.value)
				textoCampo=textoCampo1*cantidad
			
				temp_iva_sino.value=parseFloat(textoCampo*var_por_iva/100)
				temp_iva_sino_o.value=parseFloat(textoCampo*var_por_iva/100)
				
				sub_total=parseFloat(textoCampo)
				
				temp_imponible.value=sub_total
				textoCampo_iva=parseFloat(temp_iva_sino_o.value)
				
				temp_total.value=textoCampo_iva+sub_total
				
				temp_total_o.value=textoCampo_iva+sub_total
				
				cadena="montoexcento"+valor
				temp=document.getElementById(cadena)
				temp.value=0
				
			}else
			{
				temp_iva.value=1
				var_monto_por_iva=parseFloat(temp_iva_sino.value)
				var_monto_sub_total=parseFloat(temp_total.value)
				temp_total.value= var_monto_sub_total-var_monto_por_iva
				temp_total_o.value= var_monto_sub_total-var_monto_por_iva
				var_monto_total=parseFloat(window.document.fproveedoresadd.montoorden.value)
				temp_iva_sino.value=0
				temp_iva_sino_o.value=0
				cadena="montoexcento"+valor
				temp=document.getElementById(cadena)
				temp.value=parseFloat(temp_total.value)
				temp_imponible.value=0
			}
		}
//codigo nuevo

	for(i=1;i<=num_reg;i++){
		cadena="iva_sino"+i
		temp=document.getElementById(cadena)
		total=total+parseFloat(temp.value)
	}
	
	window.document.fproveedoresadd.montoiva.value =total
	
	total=0
	for(i=1;i<=num_reg;i++){
		cadena="imponible"+i
		temp=document.getElementById(cadena)
		total=total+parseFloat(temp.value)
	}
	window.document.fproveedoresadd.imponible.value = total
	
	total=0
	for(i=1;i<=num_reg;i++){
		var cadena="montoexcento"+i
		var temp=document.getElementById(cadena)
		total=total+parseFloat(temp.value)
	}
	
	window.document.fproveedoresadd.montoexcento.value = total
//********************************************************************


	var_imponible = parseFloat(window.document.fproveedoresadd.imponible.value);
	var result = var_imponible.toFixed(2); 
	window.document.fproveedoresadd.imponible.value = result;	
	
	var_iva = parseFloat(window.document.fproveedoresadd.montoiva.value);
	var result2 = var_iva.toFixed(2); 
	window.document.fproveedoresadd.montoiva.value = result2;	
	
	var_montoexcento = parseFloat(window.document.fproveedoresadd.montoexcento.value);
	var result3 = var_montoexcento.toFixed(2); 
	window.document.fproveedoresadd.montoexcento.value = result3;
	
	total=parseFloat(var_imponible+var_iva+var_montoexcento);
	var result4 = total.toFixed(2); 
	window.document.fproveedoresadd.montoorden.value = result4
	var result5 = dispo-result4
	var re = 1
	if (result5 < 0)
	{
		alert("NO TIENE DISPONIBILIDAD PRESUPUESTARIA")
		window.document.fproveedoresadd.bandera.value = re
	}
	
}

function checkMyForm(valor,num_reg,var_por_iva) 
{

	var cadena,temp, i,total=0,cad_iva,temp_iva,cad_cantidad,temp_cantidad,sub_total=0
	var cad_iva_sino,temp_iva_sino,cad_iva_sino_o,temp_iva_sino_o,cadena_total,temp_total,cadena_imponible,temp_imponible

//cadena de cantidad
	cad_cantidad="cantidad_des"+valor
	temp_cantidad=document.getElementById(cad_cantidad)

//cadena de iva
	cad_iva_sino="iva_sino"+valor
	temp_iva_sino=document.getElementById(cad_iva_sino)
	cad_iva_sino_o="iva_sino_o"+valor
	temp_iva_sino_o=document.getElementById(cad_iva_sino_o)

//cadena total
	cadena_total="total"+valor
	temp_total=document.getElementById(cadena_total)
	cadena_total_o="total_o"+valor
	temp_total_o=document.getElementById(cadena_total)

//cadena imponible
	cadena_imponible="imponible"+valor
	temp_imponible=document.getElementById(cadena_imponible)

	cadena="precio"+valor
	temp_precio=document.getElementById(cadena)
	var_pre=parseFloat(temp_precio.value)
	if (!var_pre)
	{
		temp_iva_sino.value = 0
		temp_iva_sino_o.value = 0	
	}else{
		cad_iva="iva_sino_opt"+valor
		temp_iva=document.getElementById(cad_iva)
	
			if (temp_iva.checked)
			{
				temp_iva.value=1
				textoCampo1 = parseFloat(temp_precio.value)
				
				cantidad = parseFloat(temp_cantidad.value)
				textoCampo=textoCampo1*cantidad
			
				temp_iva_sino.value=parseFloat(textoCampo*var_por_iva/100)
				temp_iva_sino_o.value=parseFloat(textoCampo*var_por_iva/100)
				
				sub_total=parseFloat(textoCampo)
				
				temp_imponible.value=sub_total
				textoCampo_iva=parseFloat(temp_iva_sino_o.value)
				
				temp_total.value=textoCampo_iva+sub_total
				
				temp_total_o.value=textoCampo_iva+sub_total
				
				cadena="montoexcento"+valor
				temp=document.getElementById(cadena)
				temp.value=0
				
			}else
			{
				temp_iva.value=1
				var_monto_por_iva=parseFloat(temp_iva_sino.value)
				var_monto_sub_total=parseFloat(temp_total.value)
				temp_total.value= var_monto_sub_total-var_monto_por_iva
				temp_total_o.value= var_monto_sub_total-var_monto_por_iva
				var_monto_total=parseFloat(window.document.fproveedoresadd.montoorden.value)
				temp_iva_sino.value=0
				temp_iva_sino_o.value=0
				cadena="montoexcento"+valor
				temp=document.getElementById(cadena)
				temp.value=parseFloat(temp_total.value)
				temp_imponible.value=0
			}
		}
//codigo nuevo

	for(i=1;i<=num_reg;i++){
		cadena="iva_sino"+i
		temp=document.getElementById(cadena)
		total=total+parseFloat(temp.value)
	}
	
	window.document.fproveedoresadd.montoiva.value =total
	
	total=0
	for(i=1;i<=num_reg;i++){
		cadena="imponible"+i
		temp=document.getElementById(cadena)
		total=total+parseFloat(temp.value)
	}
	window.document.fproveedoresadd.imponible.value = total
	
	total=0
	for(i=1;i<=num_reg;i++){
		var cadena="montoexcento"+i
		var temp=document.getElementById(cadena)
		total=total+parseFloat(temp.value)
	}
	
	window.document.fproveedoresadd.montoexcento.value = total
//********************************************************************


	var_imponible = parseFloat(window.document.fproveedoresadd.imponible.value);
	var result = var_imponible.toFixed(2); 
	window.document.fproveedoresadd.imponible.value = result;	
	
	var_iva = parseFloat(window.document.fproveedoresadd.montoiva.value);
	var result2 = var_iva.toFixed(2); 
	window.document.fproveedoresadd.montoiva.value = result2;	
	
	var_montoexcento = parseFloat(window.document.fproveedoresadd.montoexcento.value);
	var result3 = var_montoexcento.toFixed(2); 
	window.document.fproveedoresadd.montoexcento.value = result3;
	
	total=parseFloat(var_imponible+var_iva+var_montoexcento);
	var result4 = total.toFixed(2); 
	window.document.fproveedoresadd.montoorden.value = result4
	
	
}



function MyForm(valor2,num_reg,var_por_iva) 
{

	actualizar_formulario(valor2,num_reg)
	checkMyForm(valor2,num_reg,var_por_iva)

	
}
function total_gen_iva(var_por_iva)
{
		if(window.document.fproveedoresadd.montoiva.value=="")
		{
			window.document.fproveedoresadd.montoiva.value="0.00000";
		}
		if(window.document.fproveedoresadd.imponible.value=="")
		{
			window.document.fproveedoresadd.imponible.value="0.00000";
		}
		if(window.document.fproveedoresadd.montoexcento.value=="")
		{
			window.document.fproveedoresadd.montoexcento.value="0.00000";
		}
		
	var_imponible = parseFloat(window.document.fproveedoresadd.imponible.value);
	var_montoexcento = parseFloat(window.document.fproveedoresadd.montoexcento.value);
	var_iva = parseFloat(window.document.fproveedoresadd.montoiva.value);
	total=parseFloat(var_imponible+var_iva+var_montoexcento);
	var result4 = total.toFixed(5); 
	window.document.fproveedoresadd.montoorden.value = result4	
	
}
function total_gen(var_por_iva)
{	
		if(window.document.fproveedoresadd.montoiva.value=="")
		{
			window.document.fproveedoresadd.montoiva.value=0.00000;
		}
		if(window.document.fproveedoresadd.imponible.value=="")
		{
			window.document.fproveedoresadd.imponible.value=0.00000;
		}
		if(window.document.fproveedoresadd.montoexcento.value=="")
		{
			window.document.fproveedoresadd.montoexcento.value=0.00000;
		}
		
		var_por_iva=parseFloat(var_por_iva);
	
	var_imponible = parseFloat(window.document.fproveedoresadd.imponible.value);
	var result = var_imponible.toFixed(2); 
	window.document.fproveedoresadd.imponible.value = result;	
	var_valor_iva=var_imponible*var_por_iva/100;
	var_iva = parseFloat(window.document.fproveedoresadd.montoiva.value);
	if (var_iva==0)
	{
		var_iva=var_iva+var_valor_iva;
	}
	if (var_iva=="NaN")
	{
		var_iva=0;
	}
	var result2 = var_iva.toFixed(2); 
	window.document.fproveedoresadd.montoiva.value = result2;	
	
	var_montoexcento = parseFloat(window.document.fproveedoresadd.montoexcento.value);
	var result3 = var_montoexcento.toFixed(2); 
	window.document.fproveedoresadd.montoexcento.value = result3;
	
	total=parseFloat(var_imponible+var_iva+var_montoexcento);
	var result4 = total.toFixed(2); 
	window.document.fproveedoresadd.montoorden.value = result4

	
}


function validar(num) 
{	
	if (num==0)
	{		
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	if (num==1)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==2)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==3)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)		
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==4)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==5)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_precio5 = parseFloat(window.document.fproveedoresadd.precio5.value)		
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_precio5==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==6)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_precio5 = parseFloat(window.document.fproveedoresadd.precio5.value)
		var_precio6 = parseFloat(window.document.fproveedoresadd.precio6.value)		
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_precio5==0 || var_precio6==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==7)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_precio5 = parseFloat(window.document.fproveedoresadd.precio5.value)
		var_precio6 = parseFloat(window.document.fproveedoresadd.precio6.value)		
		var_precio7 = parseFloat(window.document.fproveedoresadd.precio7.value)		
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_precio5==0 || var_precio6==0 || var_precio7==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==8)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_precio5 = parseFloat(window.document.fproveedoresadd.precio5.value)
		var_precio6 = parseFloat(window.document.fproveedoresadd.precio6.value)		
		var_precio7 = parseFloat(window.document.fproveedoresadd.precio7.value)		
		var_precio8 = parseFloat(window.document.fproveedoresadd.precio8.value)				
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_precio5==0 || var_precio6==0 || var_precio7==0 || var_precio8==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==9)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_precio5 = parseFloat(window.document.fproveedoresadd.precio5.value)
		var_precio6 = parseFloat(window.document.fproveedoresadd.precio6.value)		
		var_precio7 = parseFloat(window.document.fproveedoresadd.precio7.value)		
		var_precio8 = parseFloat(window.document.fproveedoresadd.precio8.value)				
		var_precio9 = parseFloat(window.document.fproveedoresadd.precio9.value)						
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_precio5==0 || var_precio6==0 || var_precio7==0 || var_precio8==0 || var_precio9==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	if (num==10)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_precio5 = parseFloat(window.document.fproveedoresadd.precio5.value)
		var_precio6 = parseFloat(window.document.fproveedoresadd.precio6.value)		
		var_precio7 = parseFloat(window.document.fproveedoresadd.precio7.value)		
		var_precio8 = parseFloat(window.document.fproveedoresadd.precio8.value)				
		var_precio9 = parseFloat(window.document.fproveedoresadd.precio9.value)						
		var_precio10 = parseFloat(window.document.fproveedoresadd.precio10.value)								
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_precio5==0 || var_precio6==0 || var_precio7==0 || var_precio8==0 || var_precio9==0 || var_precio10==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	if (num==11)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_precio5 = parseFloat(window.document.fproveedoresadd.precio5.value)
		var_precio6 = parseFloat(window.document.fproveedoresadd.precio6.value)		
		var_precio7 = parseFloat(window.document.fproveedoresadd.precio7.value)		
		var_precio8 = parseFloat(window.document.fproveedoresadd.precio8.value)				
		var_precio9 = parseFloat(window.document.fproveedoresadd.precio9.value)						
		var_precio10 = parseFloat(window.document.fproveedoresadd.precio10.value)								
		var_precio11 = parseFloat(window.document.fproveedoresadd.precio11.value)										
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_precio5==0 || var_precio6==0 || var_precio7==0 || var_precio8==0 || var_precio9==0 || var_precio10==0 || var_precio11==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}
	if (num==12)
	{		
		var_precio = parseFloat(window.document.fproveedoresadd.precio1.value)		
		var_precio2 = parseFloat(window.document.fproveedoresadd.precio2.value)		
		var_precio3 = parseFloat(window.document.fproveedoresadd.precio3.value)
		var_precio4 = parseFloat(window.document.fproveedoresadd.precio4.value)		
		var_precio5 = parseFloat(window.document.fproveedoresadd.precio5.value)
		var_precio6 = parseFloat(window.document.fproveedoresadd.precio6.value)		
		var_precio7 = parseFloat(window.document.fproveedoresadd.precio7.value)		
		var_precio8 = parseFloat(window.document.fproveedoresadd.precio8.value)				
		var_precio9 = parseFloat(window.document.fproveedoresadd.precio9.value)						
		var_precio10 = parseFloat(window.document.fproveedoresadd.precio10.value)								
		var_precio11 = parseFloat(window.document.fproveedoresadd.precio11.value)										
		var_precio12 = parseFloat(window.document.fproveedoresadd.precio12.value)												
		var_monto_orden=parseFloat(window.document.fproveedoresadd.montoorden.value)	
		var_proveedor=window.document.fproveedoresadd.proveedor.value		
		if(var_precio==0 || var_precio2==0 || var_precio3==0 || var_precio4==0 || var_precio5==0 || var_precio6==0 || var_precio7==0 || var_precio8==0 || var_precio9==0 || var_precio10==0 || var_precio11==0 || var_precio12==0 || var_monto_orden==0 || var_proveedor=='') 		
		{
		alert ("Faltan Datos Requeridos");
			return false;
		}
		else
		{
			return true;
		}
	}	
}

</script>


<? titulo("Emisión de ordenes","","emision_ord.php","1");?>

<form name="fproveedoresadd" id="fproveedoresadd" action="adjudicar_eo.php?cod_riqui=<?php echo $x_cod_riqui; ?>&total_reg=<?php echo $con;?>&orden=<?php echo $var_cod_orden; ?><?php if(!isset($var_cod_orden)){?>&acc=sav<?php } else{?>&acc=act<?php }?>" method="post"  onSubmit="return validar(<?php echo $con; ?>);">
</p>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
	<tr class="tb-head">
	  <td width="12%" class="tb-head"><strong>N&uacute;mero de Requisici&oacute;n:</strong> </td>
	  <td width="5%" align="left" ><?php  echo $x_cod_riqui;?>
        <div align="center"></div>
      <div align="center"></div></td>
	  <td width="15%" align="right"><strong>Se&ntilde;ores:</strong><input type="hidden" name="bandera" id="bandera" value="0"></td>
	
      <td width="50%" colspan="2" ><span >

      <?php if(!isset($var_cod_orden)){select("compania", "compania", "", "SELECT * FROM proveedores ORDER BY compania", "proveedor");}else{select3("compania", "compania", "", "SELECT * FROM proveedores where compania <> '$var_cod_provee' ORDER BY compania", "proveedor",3,"$var_cod_provee","$var_cod_provee");}?>
      <input name="cod_ri" type="hidden" id="cod_ri" value="<?php echo $x_cod_riqui; ?>">
</span></td>
      <td width="30%" ><?php if(isset($var_cod_orden)){ ?>
        <table width="22" border="0">
          <tr>
            <td><a href="<?php if($var_descripcion2=="Compras"){echo "ordenesprint3.php?id=";?><? echo $x_cod_riqui; }else {echo "ordenesprint4.php?id=";?><? echo $x_cod_riqui;}?>" target="_blank"><img src="../img_sis/ico_print.gif" width="16" height="16" border="0" title="Imprimir Orden" /></a></td>
          </tr>
        </table>        <?php } ?>      </td>
	</tr>
</table>
<br>
<table  width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div align="center">
      <table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">
        <tr>
          <td width="7%" class="tb-fila"><div align="center"><strong>Situaci&oacute;n</strong></div></td>
          <td width="8%" class="tb-fila"><div align="center"><strong>Fecha de Requisici&oacute;n</strong> </div></td>
          <td width="15%" class="tb-fila"><div align="center"><strong>Fecha de Orden</strong> </div></td>
          <td width="29%" class="tb-fila"><div align="center"><strong>Unidad Solicitante</strong> </div></td>
          <td width="26%" class="tb-fila"><div align="center"><strong>Centro de Costo</strong> </div></td>
          <td width="15%" class="tb-fila"><div align="center"><strong>Monto Orden</strong> </div></td>
          </tr>
        <tr>
          <td><div align="center">
            <p >
                <?php  echo $var_situacion;?>
</p>
            <table width="22" border="0">
              <tr>
                <td><a href="<?php echo "adjudicar_eo.php?cod_riqui=";?><? echo $x_cod_riqui; ?>&acc=cam" ><img src="../img_sis/ico_edit.gif" width="16" height="16" border="0" /></a></td>
              </tr>
            </table>
            <p >
			 
                  </p>
          </div></td>
          <td><div align="center"><span>
            <?php   echo $var_fecha_re; ?>
            <input name="fecha" type="hidden" id="fecha" value="<?php echo $var_fecha; ?>">
</span></div></td>
          <td><table style="border:none" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="90" align="center"><input readonly name="fecha_o" id="fecha_o" type="text"  value="<?php if(isset($var_cod_orden)) {echo $var_fecha;}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
              <td width="32">
					<input  name="image" type="image" id="b_fecha" src="../lib/jscalendar/cal.gif" />
                  <script type="text/javascript"> 
					 <!--
					 Calendar.setup( {inputField:"fecha_o",ifFormat:"%d/%m/%Y",button:"b_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
					 //-->
            	  </script></td>
            </tr>
          </table></td>
          <td><div align="center"><span>
            <?php  echo $var_unidad_sol; ?> - <?php echo $var_nom_unidad; ?>
            <input name="unidad" type="hidden" id="unidad" value="<?php echo $var_unidad_sol; ?>">
</span></div></td>
          <td><div align="center">
            <p >
              <?php  echo $var_cod_centro; ?> - <?php echo $var_nom_centro; ?>
              <input name="centro_cos" type="hidden" id="fecha3" value="<?php echo $var_cod_centro; ?>">
                  </p>
            </div></td>
          <td>
            <div align="center">
              Bs. F. 
                <input name="montoorden" type="text" id="montoorden" size="10" readonly <?php if(!isset($var_cod_orden)){ ?>value="0.00"<?php } else {?>value="<?php echo $var_monto_orden; ?>"<?php ;}?> />
            </div></td>
          </tr>
        <tr class="tb-fila">
          <td><div align="center" class="tb-fila"><strong>D&iacute;as de Cr&eacute;dito</strong> </div></td>
          <td colspan="2"><div align="center">
            <p><strong>Imponible</strong></p>
            </div></td>
          <td><div align="center"><strong>Monto I.V.A</strong></div></td>
          <td><div align="center"><strong>Monto Exento</strong> </div></td>
          <td><div align="center"><strong>Tipo de Orden</strong></div></td>
        </tr>
        <tr>
          <td height="25"><div align="center">
            <input name="diascredito" type="text" id="diascredito" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" size="10"  width="50" <?php if(!isset($var_cod_orden)){ ?> value="0.00"<?php } else {echo "value='$var_dias_credito'";}?>/>
          </div></td>
          <td colspan="2"><div align="center">
            Bs. F. 
              <input name="imponible" type="text" id="imponible"  onBlur="total_gen(<?php echo numero($var_por_iva,0); ?>);" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" size="20" width="100" <?php if ($con>0){?> readonly <?php }?> <?php if(!isset($var_cod_orden)){ ?> value="0.00"<?php } else {echo "value='$var_imponible'";}?>/>
          </div></td>
          <td><div align="center">
            Bs. F. 
              <input name="montoiva" type="text" id="montoiva"  onBlur="total_gen_iva(<?php echo numero($var_por_iva,0); ?>);" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" size="20" <?php if ($con>0){?> readonly <?php }?> <?php if(!isset($var_cod_orden)){ ?> value="0.00"<?php } else {echo "value='$var_monto_iva'";}?>/>
          </div></td>
          <td><div align="center">
            Bs. F. 
              <input name="montoexcento" type="text" id="montoexcento" onBlur="total_gen_iva(<?php echo numero($var_por_iva,0); ?>);"  size="20" <?php if ($con>0){?> <?php }?> <?php if(!isset($var_cod_orden)){ ?> value="0.00"<?php } else {echo "value='$var_monto_excento'";}?> />
          </div></td>
          <td><div align="center"><span class="ewTableHeader"><span class="ewTableAltRow">
              <?php if(!isset($var_cod_orden)){ echo select3("descripcion", "descripcion", "", "SELECT * FROM ordenes_tipos where descripcion <> '$var_descripcion2' and descripcion IS NOT NULL ORDER BY descripcion", "tipo",3,"$var_descripcion2","$var_descripcion2");}else{echo $var_descripcion2;}?>
		
          </span></span></div></td>
        </tr>
      </table>
			<br>
      <table width="100%"  border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;" >
        <tr class="tb-fila">
          <td width="50%" height="25"><strong>Concepto</strong></td>
			<td width="50%"><strong>Observaci&oacute;n</strong></td>
        </tr>
        <tr>
			<td width="50%"><textarea name="concepto"  cols=60 id="concepto"><?php if($var_concepto2<>''){echo $var_concepto2;}else{echo $var_concepto;} ?>
</textarea></td>
          <td width="50%"><textarea name="obser" cols="60" id="obser"><?php echo $var_descripcion; ?>
</textarea></td>
		
        </tr>
      </table>
	  
	  			<BR>
      <table width="100%" border="0" cellpadding="1" cellspacing="1" >
        <tr class="tb-head">
          <td width="100"><strong>C&oacute;digo</strong></td>
          <td width="600"><strong>Descripci&oacute;n</strong></td>
          <td width="256"><strong>Cantidad Pedida</strong> </td>
          <td width="256"><strong>Cantidad a Despachar </strong></td>
          <td width="256"><strong>Precio Unitario</strong></td>
          <td width="256"><p align="center"><strong>I.V.A.</strong></p>
		  	</td>
          <td width="256"><strong>Sub - Total</strong></td>
          <td width="256"><strong>I.V.A</strong></td>
          </tr>
        <?  if ($det == "add") {?>
        <? } // cierra el if ?>
        <?php 
  if ($rsac=='del')
  {

		$rs = query("delete from requisiciones_det where cod_requisicion=$id and cod_requisicion_det=$id_det",$Conn); 		
  		$rsac ="edit";
  }
 
  $con2=1;
  $i=0;
  $rs = query("SELECT * FROM requisiciones_det 
  WHERE (cod_requisicion = $x_cod_riqui)
  ORDER BY cod_requisicion_det ",$Conn);
	//echo $var_descripcion2;
	//exit(0);
	
	
	
	
   while ($row_rs = fetch_array($rs)) 
   {	
   	   if ($var_descripcion2<>"Materiales")
	   {
		   $var_cod_pro=$row_rs['cod_material'];
		   $rsm = query("SELECT iva FROM materiales WHERE cod_material='".$var_cod_pro."'",$Conn);
		   while ($row_rsm = fetch_array($rsm)) 
		   {		
		   		$var_por_iva=$row_rsm['iva'];
				$var_por_iva= number_format($var_por_iva, 0);				
		   }
		   //echo "i: ".$i++."IVa: ".$var_por_iva;//$rsm->close();
	   }	
		 //exit(0);   
	   if ($var_cod_orden<>'')
	   {
		   $var_cod_pro=$row_rs['cod_material'];
		   $rso = query("SELECT od.precio,od.iva,od.total FROM ordenes_detalles od,ordenes o
		   WHERE od.cod_requisicion = '$x_cod_riqui' and od.cod_pro='$var_cod_pro' and od.cod_ord = '$var_cod_orden' and o.estado <> 'Anulado' and od.cod_ord = o.codigo",$Conn);	
		   while ($row_rso = fetch_array($rso)) 
		   {
			   $var_precio=$row_rso['precio'];
			   //$var_iva=$row_rso['iva'];
			   $var_total=$row_rso['total'];
		   }
	   }
	   
	   
	    $rs333 = query("SELECT codigo_cuenta FROM materiales WHERE cod_material='".trim($row_rs['cod_material'])."' ",$Conn);
		$fetch333 = fetch_array($rs333);
		//echo $fetch333['codigo_cuenta'];
		$rslk = query("SELECT Dispo,RecNo,Ordinal FROM cwprepar WHERE Sector = '".$fetch33['sel_sector']."' and Programa = '".$fetch33['sel_programa']."' and Actividad = '".$fetch33['sel_actividad']."' and Codigo = '".$fetch333['codigo_cuenta']."'",$Conn);
		$fetchlk = fetch_array($rslk);
		//echo $fetchlk['Dispo'];

	
   ?>
        <?  if ($x_cod_riqui == $row_rs['cod_requisicion']) { ?>
        <tr >

          <td><input disabled name="cod_material<?php echo $con2; ?>" type="text" style="width:100%" size="10" value="<? echo trim($row_rs['cod_material']); ?>"/>

            <input type="hidden" name="cod_material_o<?php echo $con2; ?>" value="<? echo trim($row_rs['cod_material']); ?>">


	
		</td>

          <td><input disabled name="descripcion" type="text"  id="descripcion" value="<? echo trim($row_rs['descripcion']); ?>" size="50" maxlength="200" style="width:100%" /></td>

          <td><input disabled name="cantidad<?php echo $con2; ?>" type="text" id="cantidad<?php echo $con2; ?>" style="width:100%" value="<? echo trim($row_rs['cantidad']); ?>" size="10" />

            <input type="hidden" name="cantidad_o<?php echo $con2; ?>" value="<? echo trim($row_rs['cantidad']); ?>">
			
			
			<input type="hidden" name="disponibilidad" id="disponibilidad" value="<? echo $fetchlk['Dispo']; ?>">
			<input type="hidden" name="RecPrePar" id="RecPrePar" value="<? echo $fetchlk['RecNo']; ?>">
			<input type="hidden" name="Sector" id="Sector" value="<? echo $fetch33['sel_sector']; ?>">
			<input type="hidden" name="Programa" id="Programa" value="<? echo $fetch33['sel_programa']; ?>">
			<input type="hidden" name="Actividad" id="Actividad" value="<? echo $fetch33['sel_actividad']; ?>">
			<input type="hidden" name="RecNoOrders" id="RecNoOrders" value="<? echo $var_cod_orden; ?>">	
			<input type="hidden" name="Partida" id="Partida" value="<? echo $fetch333['codigo_cuenta']; ?>">
			<input type="hidden" name="Ordinal" id="Ordinal" value="<? echo $fetchlk['Ordinal']; ?>">
			
	
		</td>

          <td><input readonly="true" name="cantidad_des<?php echo $con2; ?>" type="text" id="cantidad_des<?php echo $con2; ?>" style="width:100%" value="<? echo trim($row_rs['cantidad']);?>" size="10" />

            <input type="hidden" name="cantidad_des_o<?php echo $con2; ?>" value="<? echo trim($row_rs['cantidad']); ?>"></td>

          <td><input  name="precio<?php echo $con2; ?>" type="text" id="precio<?php echo $con2; ?>" style="width:100%" <?php if(!isset($var_cod_orden)){ ?>value="0.00000"<?php } else {?>value="<?php echo $var_precio;  ?>"<?php ;}?> size="10" onBlur="MyForm(<?php echo $con2 ?>,<?php echo $con ?>,<?php echo $var_por_iva; ?>);" />            </td>

          <td><input disabled name="iva_sino<?php echo $con2; ?>" type="text" id="iva_sino<?php echo $con2; ?>" style="width:100%" <?php if(!isset($var_cod_orden)){ ?>value="0.00000"<?php } else {?>value="<?php echo  $var_iva; ?>"<?php ;}?> size="10" />

            <input id="iva_sino_o<?php echo $con2; ?>" name="iva_sino_o<?php echo $con2; ?>" type="hidden"<?php if(!isset($var_cod_orden)){ ?>value="0"<?php } else {echo "value='$var_iva'";}?>>

            <input name="montoexcento<?php echo $con2; ?>" id="montoexcento<?php echo $con2; ?>" type="hidden" value="0">

            <input id="imponible<?php echo $con2; ?>" name="imponible<?php echo $con2; ?>" type="hidden" value="0">
	<input id="cod_orden" name="cod_orden" type="hidden" value="<? echo $var_cod_orden; ?>">
		</td>

          <td><input    disabled name="total<?php echo $con2; ?>" type="text" id="total<?php echo $con2; ?>" style="width:100%" <?php if(!isset($var_cod_orden)){ ?>value="0.00000"<?php } else {?>value="<?php echo $var_total; ?>"<?php } ?> size="10" />

            <input id="total_o<?php echo $con2; ?>" name="total_o<?php echo $con2; ?>" type="hidden" <?php if(!isset($var_cod_orden)){ ?>value="0.00"<?php } else {?>value="<?php echo $var_total;} ?>"></td>

          <td><input  name="iva_sino_opt<?php echo $con2; ?>" type="checkbox" id="iva_sino_opt<? echo $con2; ?>" value="checkbox" <? if($compromiso == "SI"){?>  onchange="checkMyFormS(<?php echo $con2;?>,<?php echo $con ?>,<?php echo $var_por_iva; ?>,<? echo $fetchlk['Dispo']; ?>);" <? }else { ?> onchange="checkMyForm(<?php echo $con2;?>,<?php echo $con ?>,<?php echo $var_por_iva; ?>);" <? }?>>

              <input name="iva_sino_opt_o<?php echo $con2; ?>"  <?php if($var_iva>0){ ?> value="1" <?php }else{ echo "value='0'";} ?>" type="hidden" id="iva_sino_opt_o<?php echo $con2; $con2=$con2+1; ?>" ></td>

          </tr>
        <? } else{}?>
        <tr >
          <td colspan="8"><div align="center"></div></td>
          </tr>
        <? } // cierra el if ?>
        <?   cerrar_conexion($Conn);?>
      </table>
      <p class="tb-tit"><input align="middle" type="submit" name="Submit2" value="Guardar" /></p>
    </div>
      <table border="0" align="right" cellpadding="2" cellspacing="0">
          <tr>
            <td><? //$var_pag="proveedores_da.php?acc=add&codigo=$x_cod_proveedor&nombre=$x_nombre";  btn('ok',$var_pag) ?> </td>
            <td><? //btn('cancel','proveedores_list.php') ?></td>
          </tr>
      </table></td></tr>
</table>
</form>

<?php include ("../footer.php") ?>
<?php
//phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_cod_proveedor;
	$sFilter = ewSqlKeyWhere;
	if (!is_numeric($x_cod_proveedor)) return false;
	$x_cod_proveedor =  (get_magic_quotes_gpc()) ? stripslashes($x_cod_proveedor) : $x_cod_proveedor;
	$sFilter = str_replace("@cod_proveedor", AdjustSql($x_cod_proveedor), $sFilter); // Replace key value
	$sSql = ewBuildSql(ewSqlSelect, ewSqlWhere, ewSqlGroupBy, ewSqlHaving, ewSqlOrderBy, $sFilter, "");
	$rs = phpmkr_query($sSql,$conn) or die("Fallo al ejecutar la consulta en la l�nea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	} else {
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);		
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_cod_proveedor;
	global $x_cod_estado;
	$sFilter = ewSqlKeyWhere;

	// Check for duplicate key
	$bCheckKey = true;
	if ((@$x_cod_proveedor == "") || (is_null(@$x_cod_proveedor))) {
		$bCheckKey = false;
	} else {
		$sFilter = str_replace("@cod_proveedor", AdjustSql($x_cod_proveedor), $sFilter); // Replace key value
	}
	if ($bCheckKey) {
		$sSqlChk = ewBuildSql(ewSqlSelect, ewSqlWhere, ewSqlGroupBy, ewSqlHaving, ewSqlOrderBy, $sFilter, "");
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Fallo al ejecutar la consulta en la línea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION[ewSessionMessage] = "Valor Duplicado para la Clave Primaria";
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}
	if (@$x_cod_estado == "" || (is_null(@$x_cod_estado))) { // Check field with unique index

		// Ignore
	} else {
		$sFilter = "(`cod_estado` = " . AdjustSql($x_cod_estado) . ")";
		$sSqlChk = ewBuildSql(ewSqlSelect, ewSqlWhere, ewSqlGroupBy, ewSqlHaving, ewSqlOrderBy, $sFilter, "");
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Fallo al ejecutar la consulta en la l�nea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION[ewSessionMessage] = "Valor Duplicado para el Indice o la Clave Primaria -- `cod_estado`, valor = " . $x_cod_estado;
			phpmkr_free_result($rsChk);			
			return false;
		}
		phpmkr_free_result($rsChk);
	}

	// Field compania
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_compania"]) : $GLOBALS["x_compania"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`compania`"] = $theValue;

	// Field siglas
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_siglas"]) : $GLOBALS["x_siglas"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`siglas`"] = $theValue;

	// Field rif
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rif"]) : $GLOBALS["x_rif"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rif`"] = $theValue;

	// Field nit
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nit"]) : $GLOBALS["x_nit"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nit`"] = $theValue;

	// Field direccion1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_direccion1"]) : $GLOBALS["x_direccion1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`direccion1`"] = $theValue;

	// Field direccion2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_direccion2"]) : $GLOBALS["x_direccion2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`direccion2`"] = $theValue;

	// Field cod_estado
	$theValue = ($GLOBALS["x_cod_estado"] != "") ? intval($GLOBALS["x_cod_estado"]) : "NULL";
	$sTmp = $theValue;
	$srchFld = $sTmp;
	$strsql = "SELECT * FROM `proveedores` WHERE `cod_estado` = " . $srchFld;
	$rschk = phpmkr_query($strsql,$conn) or die("Fallo al ejecutar la consulta en la l�nea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL:' . $strsql);
	if (phpmkr_num_rows($rschk) > 0) {
		echo "Valor Duplicado para el Indice o la Clave Primaria -- cod_estado, valor = " . $sTmp . "<br>";
		echo "Presionar [Pagina anterior] para continuar!";
		die();
	}
	@phpmkr_free_result($rschk);
	$fieldList["`cod_estado`"] = $theValue;

	// Field cod_municipio
	$theValue = ($GLOBALS["x_cod_municipio"] != "") ? intval($GLOBALS["x_cod_municipio"]) : "NULL";
	$fieldList["`cod_municipio`"] = $theValue;

	// Field clasificacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_clasificacion"]) : $GLOBALS["x_clasificacion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`clasificacion`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;

	// Field web
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_web"]) : $GLOBALS["x_web"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`web`"] = $theValue;

	// Field rep_nombres
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rep_nombres"]) : $GLOBALS["x_rep_nombres"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rep_nombres`"] = $theValue;

	// Field rep_apellidos
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rep_apellidos"]) : $GLOBALS["x_rep_apellidos"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rep_apellidos`"] = $theValue;

	// Field rep_ci
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rep_ci"]) : $GLOBALS["x_rep_ci"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rep_ci`"] = $theValue;

	// Field dias_credito
	$theValue = ($GLOBALS["x_dias_credito"] != "") ? intval($GLOBALS["x_dias_credito"]) : "NULL";
	$fieldList["`dias_credito`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_vence
	$theValue = ($GLOBALS["x_fecha_vence"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_vence"]) . "'" : "Null";
	$fieldList["`fecha_vence`"] = $theValue;

	// Field tipo_persona
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_persona"]) : $GLOBALS["x_tipo_persona"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_persona`"] = $theValue;

	// Field tipo_residencia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_residencia"]) : $GLOBALS["x_tipo_residencia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_residencia`"] = $theValue;

	// Field tipo_compania
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_compania"]) : $GLOBALS["x_tipo_compania"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_compania`"] = $theValue;

	// Field cod_impuesto_isrl
	$theValue = ($GLOBALS["x_cod_impuesto_isrl"] != "") ? intval($GLOBALS["x_cod_impuesto_isrl"]) : "NULL";
	$fieldList["`cod_impuesto_isrl`"] = $theValue;

	// Field tipo_declara
	$theValue = ($GLOBALS["x_tipo_declara"] != "") ? intval($GLOBALS["x_tipo_declara"]) : "NULL";
	$fieldList["`tipo_declara`"] = $theValue;

	// Field cuenta_contable
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cuenta_contable"]) : $GLOBALS["x_cuenta_contable"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`cuenta_contable`"] = $theValue;

	// Field cuenta_bancaria
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cuenta_bancaria"]) : $GLOBALS["x_cuenta_bancaria"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`cuenta_bancaria`"] = $theValue;

	// Field registro
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_registro"]) : $GLOBALS["x_registro"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`registro`"] = $theValue;

	// Field registro_fecha
	$theValue = ($GLOBALS["x_registro_fecha"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_registro_fecha"]) . "'" : "Null";
	$fieldList["`registro_fecha`"] = $theValue;

	// Field registro_numero
	$theValue = ($GLOBALS["x_registro_numero"] != "") ? doubleval($GLOBALS["x_registro_numero"]) : "NULL";
	$fieldList["`registro_numero`"] = $theValue;

	// Field registro_tomo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_registro_tomo"]) : $GLOBALS["x_registro_tomo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`registro_tomo`"] = $theValue;

	// Field capital_suscrito
	$theValue = ($GLOBALS["x_capital_suscrito"] != "") ? doubleval($GLOBALS["x_capital_suscrito"]) : "NULL";
	$fieldList["`capital_suscrito`"] = $theValue;

	// Field capital_pagado
	$theValue = ($GLOBALS["x_capital_pagado"] != "") ? doubleval($GLOBALS["x_capital_pagado"]) : "NULL";
	$fieldList["`capital_pagado`"] = $theValue;

	// Field observaciones
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_observaciones"]) : $GLOBALS["x_observaciones"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`observaciones`"] = $theValue;

	// Field contraloria
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_contraloria"]) : $GLOBALS["x_contraloria"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`contraloria`"] = $theValue;

	// Field contraloria_fecha
	$theValue = ($GLOBALS["x_contraloria_fecha"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_contraloria_fecha"]) . "'" : "Null";
	$fieldList["`contraloria_fecha`"] = $theValue;

	// Field doc_copia_registro
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_copia_registro"]) : $GLOBALS["x_doc_copia_registro"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_copia_registro`"] = $theValue;

	// Field doc_copia_publicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_copia_publicacion"]) : $GLOBALS["x_doc_copia_publicacion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_copia_publicacion`"] = $theValue;

	// Field doc_copia_rif
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_copia_rif"]) : $GLOBALS["x_doc_copia_rif"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_copia_rif`"] = $theValue;

	// Field doc_datos_tecnicos
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_datos_tecnicos"]) : $GLOBALS["x_doc_datos_tecnicos"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_datos_tecnicos`"] = $theValue;

	// Field doc_registro_sanitario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_registro_sanitario"]) : $GLOBALS["x_doc_registro_sanitario"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_registro_sanitario`"] = $theValue;

	// Field doc_solvencia_alcaldia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_solvencia_alcaldia"]) : $GLOBALS["x_doc_solvencia_alcaldia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_solvencia_alcaldia`"] = $theValue;

	// Field doc_balance
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_balance"]) : $GLOBALS["x_doc_balance"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_balance`"] = $theValue;

	// Field doc_ope
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_ope"]) : $GLOBALS["x_doc_ope"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_ope`"] = $theValue;

	// Field doc_ocei
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_doc_ocei"]) : $GLOBALS["x_doc_ocei"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`doc_ocei`"] = $theValue;

	// Field status
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_status"]) : $GLOBALS["x_status"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`status`"] = $theValue;

	// Inserting event
	if (Recordset_Inserting($fieldList)) {

		// Insert
		$sSql = "INSERT INTO `proveedores` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";	
		phpmkr_query($sSql, $conn) or die("Fallo al ejecutar la consulta en la línea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
		$fieldList["`cod_proveedor`"] = phpmkr_insert_id($conn);
		$result = (phpmkr_affected_rows($conn) > 0);

		// Inserted event
		if ($result) Recordset_Inserted($fieldList);
	} else {
		$result = false;
	}
	return $result;
}

// Inserting event
function Recordset_Inserting(&$newrs)
{

	// Enter your customized codes here
	return true;
}

// Inserted event
function Recordset_Inserted($newrs)
{
	$table = "proveedores";
}
?>
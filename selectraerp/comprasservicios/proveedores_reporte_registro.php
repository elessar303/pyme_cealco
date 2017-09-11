<?
	include ("../header.php");
	require_once '../lib/config.php';
	require_once '../lib/common.php';

	$conexion=conexion();
	$codigo = @$_GET['codigo'];
	$consulta = "SELECT * FROM proveedores WHERE cod_proveedor='".$codigo."'";
	$resultado = query($consulta, $conexion);
	$fila = fetch_array($resultado);
	$nombre = $fila['compania'];
	$siglas = $fila['siglas'];
	$direccion = $fila['direccion1'];
	$ubicacion = $fila[''];
	$email = $fila['email'];
	$telefono = $fila[''];
	$tipo = $fila['tipo_compania'];
	$contactoNom = $fila['rep_nombres'];
	$contactoApe = $fila['rep_apellidos'];
	$representanteNom = $fila['rep_nombres'];
	$representanteApe = $fila['rep_apellidos'];
	$cedula = $fila['rep_ci'];
	$telf = $fila[''];
	$rif = $fila['rif'];
	$nit = $fila['nit'];
	$capSuscrito = $fila['capital_suscrito'];
	$capPagado = $fila['capital_pagado'];
	$fechaIns = $fila['registro_fecha'];
	$fechaReg = $fila['registro_fecha'];
	
	$conAdicionales = "SELECT * FROM proveedores_dat_adi WHERE cod_pro='".$codigo."'";
	$resAdicionales = query($conAdicionales, $conexion);
	$filaDatos = fetch_array($resAdicionales);
	$curriculum = $filaDatos['curriculo'];
	$solvencia = $filaDatos['solvencia'];
	$nivelContratacion = $filaDatos['cap_ren_rnc'];
	$capContratacion = $filaDatos['nivel_rnc'];
	$soc_nom1 = $filaDatos['soc_nom1'];
	$soc_ci1 = $filaDatos['soc_ci1'];
	$soc_cap1 = $filaDatos['soc_cap1'];
	$soc_nom2 = $filaDatos['soc_nom2'];
	$soc_ci2 = $filaDatos['soc_ci2'];
	$soc_cap2 = $filaDatos['soc_cap2'];
	$soc_nom3 = $filaDatos['soc_nom3'];
	$soc_ci3 = $filaDatos['soc_ci3'];
	$soc_cap3 = $filaDatos['soc_cap3'];
	$soc_nom4 = $filaDatos['soc_nom4'];
	$soc_ci4 = $filaDatos['soc_ci4'];
	$soc_cap4 = $filaDatos['soc_cap4'];
	$soc_nom5 = $filaDatos['soc_nom5'];
	$soc_ci5 = $filaDatos['soc_ci5'];
	$soc_cap5 = $filaDatos['soc_cap5'];
	$soc_nom6 = $filaDatos['soc_nom6'];
	$soc_ci6 = $filaDatos['soc_ci6'];
	$soc_cap6 = $filaDatos['soc_cap6'];
	$tomo_leg1 = $filaDatos['al_tomo1'];
	$nro_leg1 = $filaDatos['al_num1'];
	$reg_leg1 = $filaDatos['al_reg1'];
	$fec_leg1 = $filaDatos['al_fecha1'];
	$tomo_leg2 = $filaDatos['al_tomo2'];
	$nro_leg2 = $filaDatos['al_num2'];
	$reg_leg2 = $filaDatos['al_reg2'];
	$fec_leg2 = $filaDatos['al_fecha2'];
	$tomo_leg3 = $filaDatos['al_tomo3'];
	$nro_leg3 = $filaDatos['al_num3'];
	$reg_leg3 = $filaDatos['al_reg3'];
	$fec_leg3 = $filaDatos['al_fecha3'];
	$solSSO = $filaDatos['sol_sso'];
	$SSOdesde = $filaDatos['sso_des'];
	$SSOhasta = $filaDatos['sso_has'];
	$solINCE = $filaDatos['sol_ince'];
	$INCEdesde = $filaDatos['ince_des'];
	$INCEhasta = $filaDatos['ince_has'];
	$solMUNI = $filaDatos['sol_muni'];
	$MUNIdesde = $filaDatos['muni_des'];
	$MUNIhasta = $filaDatos['muni_has'];
	$solSNC = $filaDatos['sol_snc'];
	$SNCdesde = $filaDatos['snc_des'];
	$SNChasta = $filaDatos['snc_has'];
	$solLABORAL = $filaDatos['sol_laboral'];
	$LABdesde = $filaDatos['lab_des'];
	$LABhasta = $filaDatos['lab_has'];
	$solSUNACOOP = $filaDatos['sol_sunacop'];
	$SUNAdesde = $filaDatos['sunacop_des'];
	$SUNAhasta = $filaDatos['sunacop_has'];
	$contabilidad = $filaDatos['af_prin_conta_dpcp'];
	$gananPerd = $filaDatos['af_edo_gan_per'];
	$comisario = $filaDatos['af_inf_com'];
	$balanceApertura = $filaDatos['af_bal_ape'];
	$balanceGeneral = $filaDatos['af_bal_gen'];
	$cuentasPatrimonio = $filaDatos['af_cue_pat'];
	$anoEdoFinanciero = $filaDatos['af_ano_est_fin'];
	$flujoEfectivo = $filaDatos['af_flu_efe'];
	$notasExplicativas = $filaDatos['af_not_exp'];
	$organigrama = $filaDatos['at_con_organigrama'];
	$equipos = $filaDatos['at_lis_equi_maq'];
	$obras = $filaDatos['at_lis_obr_eje'];

	$Conn=conexion_conf();
	$var_sql = "SELECT encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der FROM parametros";
	$rs = query($var_sql, $Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1 = $row_rs['encabezado1'];
	$var_encabezado2 = $row_rs['encabezado2'];
	$var_encabezado3 = $row_rs['encabezado3'];
	$var_encabezado4 = $row_rs['encabezado4'];
	$var_imagen_izq = $row_rs['imagen_izq'];
	$var_imagen_der = $row_rs['imagen_der'];
	cerrar_conexion($Conn);

?>
<HTML class="fondo">
<HEAD><TITLE></TITLE>
<LINK rel="StyleSheet" href="../estilos.css" type="text/css">
</HEAD>
<BODY>
<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div id="impresion">
<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
echo $encabezado."<br><br>";
?>

<BR>
	<TABLE align="center" width="500" cellpadding="0" cellspacing="0" border="0">
		<TR>
			<TD align="center" colspan="2"><H3>*** REGISTRO INTERNO DE EMPRESAS ***</H3></TD>
		</TR>
		<TR>
			<TD align="center" colspan="2"><strong>Nro. de Registro: </strong><?echo $codigo?></TD>
		</TR>
		<TR>
			<TD align="center"><strong>Fecha de Inscripci&oacute;n: </strong><?echo $fechaIns;?></TD>
			<TD align="center"><strong>Fecha de Actualizaci&oacute;n: </strong><?echo $fechaIns;?></TD>
		</TR>
	</TABLE>
	<BR>
	<TABLE align="center" width="800" cellpadding="0" cellspacing="0">
		<TR style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
			<TD align="center" colspan="4"><strong>INSCRIPCI&Oacute;N REGISTRO INTERNO</strong></TD>
		</TR>
		<TR>
			<TD colspan="2"><strong>Nombre o Raz&oacute;n Social: </strong><?echo $nombre?></TD>
			<TD colspan="2"><strong>Siglas: </strong><?echo $siglas?></TD>
		</TR>
		<TR>
			<TD colspan="2"><strong>Direcci&oacute;n Principal de la Empresa: </strong><?echo $direccion?></TD>
			<TD colspan="2"><strong>Ubicaci&oacute;n Geogr&aacute;fica: </strong><?echo $ubicacion?></TD>
		</TR>
		<TR>
			<TD><strong>E-mail: </strong><?echo $email?></TD>
			<TD><strong>Tel&eacute;fono: </strong><?echo $telefono?></TD>
			<TD><strong>Tipo: </strong><?echo $tipo?></TD>
			<TD><strong>Persona Contacto: </strong><?echo $contactoApe." ".$contactoNom?></TD>
		</TR>
		<TR>
			<TD colspan="2"><strong>Representante Legal: </strong><?echo $representanteApe." ".$representanteNom?></TD>
			<TD><strong>Nro. C.I.: </strong><?echo $cedula?></TD>
			<TD><strong>Tel&eacute;fono: </strong><?echo $telf?></TD>
		</TR>
		<TR>
			<TD colspan="2"><strong>Accionistas: </strong></TD>
			<TD><strong>C.I. Nro.: </strong></TD>
			<TD><strong>% de Capital o Acci&oacute;n: </strong></TD>
		</TR>
		<TR>
			<TD colspan="2"><?echo $soc_nom1;?></TD>
			<TD><?echo $soc_ci1;?></TD>
			<TD><?echo $soc_cap1;?></TD>
		</TR>
		<TR>
			<TD colspan="2"><?echo $soc_nom2;?></TD>
			<TD><?echo $soc_ci2;?></TD>
			<TD><?echo $soc_cap2;?></TD>
		</TR>
		<TR>
			<TD colspan="2"><?echo $soc_nom3;?></TD>
			<TD><?echo $soc_ci3;?></TD>
			<TD><?echo $soc_cap3;?></TD>
		</TR>
		<TR>
			<TD colspan="2"><?echo $soc_nom4;?></TD>
			<TD><?echo $soc_ci4;?></TD>
			<TD><?echo $soc_cap4;?></TD>
		</TR>
		<TR>
			<TD colspan="2"><?echo $soc_nom5;?></TD>
			<TD><?echo $soc_ci5;?></TD>
			<TD><?echo $soc_cap5;?></TD>
		</TR>
		<TR>
			<TD colspan="2"><?echo $soc_nom6;?></TD>
			<TD><?echo $soc_ci6;?></TD>
			<TD><?echo $soc_cap6;?></TD>
		</TR>
	</TABLE>
	<BR>
	<TABLE align="center" width="800" cellpadding="0" cellspacing="0">
		<TR style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
			<TD align="center" colspan="5"><strong>ASPECTOS LEGALES</strong></TD>
		</TR>
		<TR>
			<TD><strong>Tipo</strong></TD>
			<TD><strong>Fecha</strong></TD>
			<TD><strong>Tomo</strong></TD>
			<TD><strong>Nro.</strong></TD>
			<TD><strong>Registro</strong></TD>
		</TR>
		<TR>
			<TD><??></TD>
			<TD><?echo $fec_leg1?></TD>
			<TD><?echo $tomo_leg1?></TD>
			<TD><?echo $nro_leg1?></TD>
			<TD><?echo $reg_leg1?></TD>
		</TR>
		<TR>
			<TD><??></TD>
			<TD><?echo $fec_leg2?></TD>
			<TD><?echo $tomo_leg2?></TD>
			<TD><?echo $nro_leg2?></TD>
			<TD><?echo $reg_leg2?></TD>
		</TR>
		<TR>
			<TD><??></TD>
			<TD><?echo $fec_leg2?></TD>
			<TD><?echo $tomo_leg2?></TD>
			<TD><?echo $nro_leg2?></TD>
			<TD><?echo $reg_leg2?></TD>
		</TR>
		<TR>
			<TD colspan="2"><strong>Capital Suscrito: </strong><?echo $capSuscrito?></TD>
			<TD colspan="2"><strong>Capital Pagado: </strong><?echo $capPagado?></TD>
		</TR>
		<TR>
			<TD colspan="2"><strong>R.I.F. No.: </strong><?echo $rif?></TD>
			<TD colspan="2"><strong>N.I.T. No.: </strong><?echo $nit?></TD>
		</TR>
	</TABLE>
	<BR>
	<TABLE align="center" width="800" cellpadding="0" cellspacing="0" border="0">
		<TR style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
			<TD align="center" colspan="6"><strong>SOLVENCIAS</strong></TD>
		</TR>
		<TR>
			<TD width="150">S.S.O. No.:</TD><TD width="100"><?echo $solSSO;?></TD>
			<TD width="50">Desde: </TD><TD width="120"><?echo $SSOdesde;?></TD>
			<TD width="50">Hasta: </TD><TD width="80"><?echo $SSOhasta;?></TD>
		</TR>
		<TR>
			<TD>INCE No.:</TD><TD><?echo $solINCE;?></TD>
			<TD>Desde: </TD><TD><?echo $INCEdesde;?></TD>
			<TD>Hasta: </TD><TD><?echo $INCEhasta;?></TD>
		</TR>
		<TR>
			<TD>SNC/REC No.:</TD><TD><?echo $solSNC;?></TD>
			<TD>Desde: </TD><TD><?echo $SNCdesde;?></TD>
			<TD>Hasta: </TD><TD><?echo $SNChasta;?></TD>
		</TR>
		<TR>
			<TD>Municipal No.:</TD><TD><?echo $solMUNI;?></TD>
			<TD>Desde: </TD><TD><?echo $MUNIdesde;?></TD>
			<TD>Hasta: </TD><TD><?echo $MUNIhasta;?></TD>
		</TR>
		<TR>
			<TD>Laboral No.:</TD><TD><?echo $solLABORAL;?></TD>
			<TD>Desde: </TD><TD><?echo $LABdesde;?></TD>
			<TD>Hasta: </TD><TD><?echo $LABhasta;?></TD>
		</TR>
		<TR>
			<TD>SUNACOP No.:</TD><TD><?echo $solSUNACOOP;?></TD>
			<TD>Desde: </TD><TD><?echo $SUNAdesde;?></TD>
			<TD>Hasta: </TD><TD><?echo $SUNAhasta;?></TD>
		</TR>
		<TR>
			<TD>Tipo de Empresa:</TD><TD></TD>
			<TD colspan="2">Especialidad de la Empresa/Cooperativa: </TD><TD colspan="2"></TD>
		</TR>
		<TR>
			<TD>Declaraci&oacute;n IVA:</TD><TD></TD>
			<TD colspan="2">Declaraci&oacute;n I.S.L.R.: </TD><TD colspan="2"></TD>
		</TR>
		<TR>
			<TD>Referencias Comerciales:</TD><TD></TD>
			<TD colspan="2">Referencias Bancarias: </TD><TD colspan="2"></TD>
		</TR>
	</TABLE>
	<BR>
	<TABLE align="center" width="800" cellpadding="0" cellspacing="0" border="0">
		<TR style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
			<TD align="center" colspan="6"><strong>ASPECTOS FINANCIEROS</strong></TD>
		</TR>
		<TR>
			<TD width="80">Principio de Contabilidad:</TD><TD width="25"><?if ($contabilidad=="on") echo "SI"; else echo "NO"?></TD>
			<TD width="80">Balance de Apertura: </TD><TD width="25"><?if ($balanceApertura=="on") echo "SI"; else echo "NO"?></TD>
			<TD width="80">Flujo de Efectivo: </TD><TD width="25"><?if ($flujoEfectivo=="on") echo "SI"; else echo "NO"?></TD>
		</TR>
		<TR>
			<TD width="80">Edo. de Ganancias y P&eacute;rdidas:</TD><TD width="25"><?if ($gananPerd=="on") echo "SI"; else echo "NO"?></TD>
			<TD width="80">Balance General: </TD><TD width="25"><?if ($balanceGeneral=="on") echo "SI"; else echo "NO"?></TD>
			<TD width="80">Notas Explicativas: </TD><TD width="25"><?if ($notasExplicativas=="on") echo "SI"; else echo "NO"?></TD>
		</TR>
		<TR>
			<TD width="80">Informe del Comisario:</TD><TD width="25"><?if ($comisario=="on") echo "SI"; else echo "NO"?></TD>
			<TD width="80">Cuentas del Patrimonio: </TD><TD width="25"><?if ($cuentasPatrimonio=="on") echo "SI"; else echo "NO"?></TD>
			<TD width="80">A&#241;o de Edos. Financieros: </TD><TD width="25"><?echo $anoEdoFinanciero?></TD>
		</TR>
	</TABLE>
	<BR>
	<TABLE align="center" width="800" cellpadding="0" cellspacing="0" border="0">
		<TR style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
			<TD align="center" colspan="4"><strong>ASPECTOS T&Eacute;CNICOS</strong></TD>
		</TR>
		<TR>
			<TD width="100">Organigrama de la Empresa: </TD><TD width="50"><?if ($organigrama=="on") echo "SI"; else echo "NO"?></TD>
			<TD width="100">Lista de Equipos y Maquinarias: </TD><TD width="50"><?if ($equipos=="on") echo "SI"; else echo "NO"?></TD>
		</TR>
		<TR>
			<TD width="100">Lista de Obras Ejecutadas: </TD><TD width="50"><?if ($obras=="on") echo "SI"; else echo "NO"?></TD>
			<TD width="100">Curriculum y Solvencia del Ing.: </TD><TD width="50"><?echo $curriculum." - ".$solvencia?></TD>
		</TR>
		<TR>
			<TD width="100">Capacidad de Contrataci&oacute;n RNC: </TD><TD width="50"><?echo $capContratacion;?></TD>
			<TD width="100">Nivel del Contrataci&oacute;n: </TD><TD width="50"><?echo $nivelContratacion;?></TD>
		</TR>
		<TR>
			<TD width="100">Observaciones: </TD><TD colspan="3"></TD>
		</TR>
	</TABLE>
</div>
</BODY>
</HTML>
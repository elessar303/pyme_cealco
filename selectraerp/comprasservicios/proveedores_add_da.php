<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");

$cod_proveedor = @$_GET["codigo"];
$nombre_proveedor = @$_GET["compania"];
$conexion=conexion();
$result = query("SELECT * FROM cwconcue", $conexion);
$result1 = query("SELECT * FROM cwconcue", $conexion);
$result2 = query("SELECT * FROM cwconcue", $conexion);
$result3 = query("SELECT * FROM cwconcue", $conexion);

$consulta = "SELECT * FROM proveedores_dat_adi WHERE cod_pro='".$cod_proveedor."'";
$resultado = query($consulta, $conexion);
$fila = fetch_array($resultado);
$iva = $fila['decla_iva'];
$islr = $fila['decla_islr'];
$refComercial = $fila['ref_com'];
$refBancaria = $fila['ref_ban'];
$ingeniero = $fila['ingeniero'];
$cedIngeniero = $fila['ci'];
$contrataRNC = $fila['cap_ren_rnc'];
$nivelRNC = $fila['nivel_rnc'];
$colegio = $fila['colegio'];
$curriculum = $fila['curriculo'];
$solvencia = $fila['solvencia'];
$solDesde = $fila['desde'];
$solHasta = $fila['hasta'];
$solSSO = $fila['sol_sso'];
$SSOdesde = $fila['sso_des'];
$SSOhasta = $fila['sso_has'];
$solINCE = $fila['sol_ince'];
$INCEdesde = $fila['ince_des'];
$INCEhasta = $fila['ince_has'];
$solMUNI = $fila['sol_muni'];
$MUNIdesde = $fila['muni_des'];
$MUNIhasta = $fila['muni_has'];
$solSNC = $fila['sol_snc'];
$SNCdesde = $fila['snc_des'];
$SNChasta = $fila['snc_has'];
$solLABORAL = $fila['sol_laboral'];
$LABdesde = $fila['lab_des'];
$LABhasta = $fila['lab_has'];
$solSUNACOOP = $fila['sol_sunacop'];
$SUNAdesde = $fila['sunacop_des'];
$SUNAhasta = $fila['sunacop_has'];
$organigrama = $fila['at_con_organigrama'];
$equipos = $fila['at_lis_equi_maq'];
$obras = $fila['at_lis_obr_eje'];
$contabilidad = $fila['af_prin_conta_dpcp'];
$gananPerd = $fila['af_edo_gan_per'];
$comisario = $fila['af_inf_com'];
$balanceApertura = $fila['af_bal_ape'];
$balanceGeneral = $fila['af_bal_gen'];
$cuentasPatrimonio = $fila['af_cue_pat'];
$anoEdoFinanciero = $fila['af_ano_est_fin'];
$flujoEfectivo = $fila['af_flu_efe'];
$notasExplicativas = $fila['af_not_exp'];
$soc_nom1 = $fila['soc_nom1'];
$soc_ci1 = $fila['soc_ci1'];
$soc_cap1 = $fila['soc_cap1'];
$soc_nom2 = $fila['soc_nom2'];
$soc_ci2 = $fila['soc_ci2'];
$soc_cap2 = $fila['soc_cap2'];
$soc_nom3 = $fila['soc_nom3'];
$soc_ci3 = $fila['soc_ci3'];
$soc_cap3 = $fila['soc_cap3'];
$soc_nom4 = $fila['soc_nom4'];
$soc_ci4 = $fila['soc_ci4'];
$soc_cap4 = $fila['soc_cap4'];
$soc_nom5 = $fila['soc_nom5'];
$soc_ci5 = $fila['soc_ci5'];
$soc_cap5 = $fila['soc_cap5'];
$soc_nom6 = $fila['soc_nom6'];
$soc_ci6 = $fila['soc_ci6'];
$soc_cap6 = $fila['soc_cap6'];
$tomo_leg1 = $fila['al_tomo1'];
$nro_leg1 = $fila['al_num1'];
$reg_leg1 = $fila['al_reg1'];
$fec_leg1 = $fila['al_fecha1'];
$tomo_leg2 = $fila['al_tomo2'];
$nro_leg2 = $fila['al_num2'];
$reg_leg2 = $fila['al_reg2'];
$fec_leg2 = $fila['al_fecha2'];
$tomo_leg3 = $fila['al_tomo3'];
$nro_leg3 = $fila['al_num3'];
$reg_leg3 = $fila['al_reg3'];
$fec_leg3 = $fila['al_fecha3'];
$ctaISLR = $fila['cuentaISLR'];
$ctaIM = $fila['cuentaIM'];
$ctaIVA = $fila['cuentaIVA'];
$ctaRFS = $fila['cuentaRFS'];

if(isset($_POST['guardar'])){
	$conGuardar = "INSERT INTO proveedores_dat_adi VALUES ('".$_POST['codigo']."', '".$_POST['iva']."', '".$_POST['islr']."', '".$_POST['ingeniero']."', '".$_POST['refComerciales']."', '".$_POST['refBancarias']."', '".$_POST['cedulaIng']."', '".$_POST['capacidadRNC']."', '".$_POST['nivelRNC']."', '".$_POST['colegio']."', '".$_POST['curriculum']."', '".$_POST['solvencia']."', '".$_POST['fecha5']."', '".$_POST['fecha6']."', '".$_POST['sso']."', '".$_POST['fecha7']."', '".$_POST['fecha8']."', '".$_POST['ince']."', '".$_POST['fecha9']."', '".$_POST['fecha10']."', '".$_POST['municipal']."', '".$_POST['fecha11']."', '".$_POST['fecha12']."', '".$_POST['snc']."', '".$_POST['fecha13']."', '".$_POST['fecha14']."', '".$_POST['laboral']."', '".$_POST['fecha15']."', '".$_POST['fecha16']."', '".$_POST['sunacoop']."', '".$_POST['fecha17']."', '".$_POST['fecha18']."', '".$_POST['organigrama']."', '".$_POST['equipos']."', '".$_POST['obras']."', '".$_POST['contabilidad']."', '".$_POST['gananciaPerdida']."', '".$_POST['comisario']."', '".$_POST['apertura']."', '".$_POST['general']."', '".$_POST['patrimonio']."', '".$_POST['anoFinanciero']."', '".$_POST['efectivo']."', '".$_POST['notas']."', '".$_POST['nom_soc0']."', '".$_POST['ci_soc0']."', '".$_POST['cap_soc0']."', '".$_POST['nom_soc1']."', '".$_POST['ci_soc1']."', '".$_POST['cap_soc1']."', '".$_POST['nom_soc2']."', '".$_POST['ci_soc2']."', '".$_POST['cap_soc2']."', '".$_POST['nom_soc3']."', '".$_POST['ci_soc3']."', '".$_POST['cap_soc3']."', '".$_POST['nom_soc4']."', '".$_POST['ci_soc4']."', '".$_POST['cap_soc4']."', '".$_POST['nom_soc5']."', '".$_POST['ci_soc5']."', '".$_POST['cap_soc5']."', '".$_POST['fecha19']."', '".$_POST['tomo_leg1']."', '".$_POST['nro_leg1']."', '".$_POST['reg_leg1']."', '".$_POST['fecha20']."', '".$_POST['tomo_leg2']."', '".$_POST['nro_leg2']."', '".$_POST['reg_leg2']."', '".$_POST['fecha21']."', '".$_POST['tomo_leg3']."', '".$_POST['nro_leg3']."', '".$_POST['reg_leg3']."', '".$_POST['ctaISLR']."', '".$_POST['ctaIM']."', '".$_POST['ctaIVA']."', '".$_POST['ctaRFS']."')";
	$resGuardar = query($conGuardar, $conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Datos Adicionales del Proveedor Agregados Correctamente\")
	parent.cont.location.href=\"proveedores_list.php\" </SCRIPT>";
}

if(isset($_POST['actualizar'])){
	$conActualizar = "UPDATE proveedores_dat_adi SET decla_iva='".$_POST['iva']."', decla_islr='".$_POST['islr']."', ingeniero='".$_POST['ingeniero']."', ref_com='".$_POST['refComerciales']."', ref_ban='".$_POST['refBancarias']."', ci='".$_POST['cedulaIng']."', cap_ren_rnc='".$_POST['capacidadRNC']."', nivel_rnc='".$_POST['nivelRNC']."', colegio='".$_POST['colegio']."', curriculo='".$_POST['curriculum']."', solvencia='".$_POST['solvencia']."', desde='".$_POST['fecha5']."', hasta='".$_POST['fecha6']."', sol_sso='".$_POST['sso']."', sso_des='".$_POST['fecha7']."', sso_has='".$_POST['fecha8']."', sol_ince='".$_POST['ince']."', ince_des='".$_POST['fecha9']."', ince_has='".$_POST['fecha10']."', sol_muni='".$_POST['municipal']."', muni_des='".$_POST['fecha11']."', muni_has='".$_POST['fecha12']."', sol_snc='".$_POST['snc']."', snc_des='".$_POST['fecha13']."', snc_has='".$_POST['fecha14']."', sol_laboral='".$_POST['laboral']."', lab_des='".$_POST['fecha15']."', lab_has='".$_POST['fecha16']."', sol_sunacop='".$_POST['sunacoop']."', sunacop_des='".$_POST['fecha17']."', sunacop_has='".$_POST['fecha18']."', at_con_organigrama='".$_POST['organigrama']."', at_lis_equi_maq='".$_POST['equipos']."', at_lis_obr_eje='".$_POST['obras']."', af_prin_conta_dpcp='".$_POST['contabilidad']."', af_edo_gan_per='".$_POST['gananciaPerdida']."', af_inf_com='".$_POST['comisario']."', af_bal_ape='".$_POST['apertura']."', af_bal_gen='".$_POST['general']."', af_cue_pat='".$_POST['patrimonio']."', af_ano_est_fin='".$_POST['anoFinanciero']."', af_flu_efe='".$_POST['efectivo']."', af_not_exp='".$_POST['notas']."', soc_nom1='".$_POST['nom_soc0']."', soc_ci1='".$_POST['ci_soc0']."', soc_cap1='".$_POST['cap_soc0']."', soc_nom2='".$_POST['nom_soc1']."', soc_ci2='".$_POST['ci_soc1']."', soc_cap2='".$_POST['cap_soc1']."', soc_nom3='".$_POST['nom_soc2']."', soc_ci3='".$_POST['ci_soc2']."', soc_cap3='".$_POST['cap_soc2']."', soc_nom4='".$_POST['nom_soc3']."', soc_ci4='".$_POST['ci_soc3']."', soc_cap4='".$_POST['cap_soc3']."', soc_nom5='".$_POST['nom_soc4']."', soc_ci5='".$_POST['ci_soc4']."', soc_cap5='".$_POST['cap_soc4']."', soc_nom6='".$_POST['nom_soc5']."', soc_ci6='".$_POST['ci_soc5']."', soc_cap6='".$_POST['cap_soc5']."', al_fecha1='".$_POST['fecha19']."', al_tomo1='".$_POST['tomo_leg1']."', al_num1='".$_POST['nro_leg1']."', al_reg1='".$_POST['reg_leg1']."', al_fecha2='".$_POST['fecha20']."', al_tomo2='".$_POST['tomo_leg2']."', al_num2='".$_POST['nro_leg2']."', al_reg2='".$_POST['reg_leg2']."', al_fecha3='".$_POST['fecha21']."', al_tomo3='".$_POST['tomo_leg3']."', al_num3='".$_POST['nro_leg3']."', al_reg3='".$_POST['reg_leg3']."', cuentaISLR='".$_POST['ctaISLR']."', cuentaIM='".$_POST['ctaIM']."', cuentaIVA='".$_POST['ctaIVA']."', cuentaRFS='".$_POST['ctaRFS']."' WHERE cod_pro='".$_POST['codigo']."'";
	$resActualizar = query($conActualizar, $conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Datos Adicionales del Proveedor Actualizados Correctamente\")
	parent.cont.location.href=\"proveedores_list.php\" </SCRIPT>";
}

if(isset($_POST['eliminar'])){
	$conEliminar = "DELETE FROM proveedores_dat_adi WHERE cod_pro=".$_POST["codigo"];
	$resEliminar = query($conEliminar, $conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Datos Adicionales del Proveedor Eliminados Correctamente\")
	parent.cont.location.href=\"proveedores_list.php\" </SCRIPT>";
}
?>

<html class="fondo">
<head><title></title>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../lib/cal2.js"></script>
<script language="javascript" src="../lib/cal_conf2.js"></script>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
  <tr>
    <td class="row-br"><?titulo("Datos Adicionales del Proveedor","","proveedores_list.php","28");?>
	</td>
  </tr>
</table>
<BR>
<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table width="100%" border="0">
	<tr><TD class="tb-head" colspan="3" align="center"><strong>DATOS ADICIONALES</strong></TD></tr>
	<tr>
		<td class="tb-fila">C&oacute;digo Proveedor: </td>
		<td><input type="text" size="10" align="left" name="codigo" value="<?echo $cod_proveedor?>" readonly="true"></td>
	</tr>
	<tr>
		<td class="tb-fila">Nombre Proveedor: </td>
		<td colspan="2"><input type="text" size="80" align="left" name="nombreProveedor" value="<?echo $nombre_proveedor?>" readonly="true"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Declaraci&oacute;n de IVA: </td>
		<td width="20" align="left"><input type="text" name="iva" size="20" value="<?echo $iva?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Declaraci&oacute;n de ISLR: </td>
		<td width="20" align="left"><input type="text" name="islr" size="20" value="<?echo $islr?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Referencias Comerciales: </td>
		<td width="20" align="left"><input type="text" name="refComerciales" size="20" value="<?echo $refComercial?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Referencias Bancarias: </td>
		<td width="20" align="left"><input type="text" name="refBancarias" size="20" value="<?echo $refBancaria?>"></td>
	</tr>
	<tr>
		<td width="180" class="tb-fila" align="left">Capacidad Contrataci&oacute;n RNC: </td>
		<td width="20" align="left"><input type="text" name="capacidadRNC" size="20" value="<?echo $contrataRNC?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Nivel de RNC: </td>
		<td width="20" align="left"><input type="text" name="nivelRNC" size="20" value="<?echo $nivelRNC?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Ingeniero: </td>
		<td width="20" align="left"><input type="text" name="ingeniero" size="20" value="<?echo $ingeniero?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">C.I. No.: </td>
		<td width="20" align="left"><input type="text" name="cedulaIng" size="20" value="<?echo $cedIngeniero?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Colegio: </td>
		<td width="20" align="left"><input type="text" name="colegio" size="20" value="<?echo $colegio?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Curriculum: </td>
		<td width="20" align="left"><input type="text" name="curriculum" size="20" value="<?echo $curriculum?>"></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Solvencia: </td>
		<td width="20" align="left"><input type="text" name="solvencia" size="20" value="<?echo $solvencia?>"></td>
	</tr>
	<tr>
		<td width="20" class="tb-fila" align="left">Desde:</td>
		<!--<td><input readonly name="fecha5" id="fecha5" type="text" value="<?php //echo $solDesde; ?>" size="12" maxlength="12"></td>
		<td><input name="image" type="image" id="fecha5" src="../lib/jscalendar/cal.gif"/>
		<script type="text/javascript">Calendar.setup({inputField:"fecha5",ifFormat:"%d/%m/%Y",button:"fecha5",firstDay:1,weekNumbers:false,showOthers:true} ); </script></td>-->
		
		<td> <input type="text" name="fecha5" size="20" maxlength="10" value="<?echo $solDesde?>"></td>
		<td> <a href="javascript:showCal('Calendar5')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></td>
	</tr>
	<tr>
		<td width="150" class="tb-fila" align="left">Hasta:</td>
		<td> <input type="text" name="fecha6" maxlength="10" size="20" value="<?echo $solHasta?>"></td>
		<td width="20" align="left"><a href="javascript:showCal('Calendar6')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></td>
	</tr>
</table>
<table width="100%" border="0">
	<tr><TD class="tb-head" colspan="9" align="center"><strong>SOLVENCIAS</strong></TD></tr>
	<TR>
		<TD width="10%">S.S.O.: </TD><TD width="30%"><input type="text" name="sso" size="40" maxlength="35" value="<?echo $solSSO?>"></TD>
		<TD width="10%" align="right">Desde: </TD><TD width="15%" align="right"><input type="text" name="fecha7" size="20" maxlength="10" value="<?echo $SSOdesde?>"></TD><TD width="5%"><a href="javascript:showCal('Calendar7')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
		<TD width="10%" align="right">Hasta: </TD><TD width="15%" align="right"><input type="text" name="fecha8" size="20" maxlength="10" value="<?echo $SSOhasta?>"></TD><TD width="5%"><a href="javascript:showCal('Calendar8')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
	</TR>
	<TR>
		<TD class="tb-fila">I.N.C.E.S: </TD><TD  class="tb-fila" align="left"><input type="text" name="ince" size="40" maxlength="35" value="<?echo $solINCE?>"></TD>
		<TD  class="tb-fila" align="right">Desde: </TD><TD  class="tb-fila" align="right"><input type="text" name="fecha9" size="20" maxlength="10" value="<?echo $INCEdesde?>"></TD><TD  class="tb-fila"><a href="javascript:showCal('Calendar9')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
		<TD  class="tb-fila" align="right">Hasta: </TD><TD  class="tb-fila" align="right"><input type="text" name="fecha10" size="20" value="<?echo $INCEhasta?>"></TD><TD class="tb-fila"><a href="javascript:showCal('Calendar10')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
	</TR>
	<TR>
		<TD>Municipal: </TD><TD align="left"><input type="text" name="municipal" size="40" maxlength="35" value="<?echo $solMUNI?>"></TD>
		<TD align="right">Desde: </TD><TD align="right"><input type="text" name="fecha11" size="20" maxlength="10" value="<?echo $MUNIdesde?>"></TD><TD><a href="javascript:showCal('Calendar11')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
		<TD align="right">Hasta: </TD><TD align="right"><input type="text" name="fecha12" size="20" maxlength="10" value="<?echo $MUNIhasta?>"></TD><TD><a href="javascript:showCal('Calendar12')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
	</TR>
	<TR>
		<TD class="tb-fila">S.N.C.: </TD><TD align="left" class="tb-fila"><input type="text" name="snc" size="40" maxlength="35" value="<?echo $solSNC?>"></TD>
		<TD align="right" class="tb-fila">Desde: </TD><TD class="tb-fila" align="right"><input type="text" name="fecha13" size="20" maxlength="10" value="<?echo $SNCdesde?>"></TD><TD class="tb-fila"><a href="javascript:showCal('Calendar13')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
		<TD class="tb-fila" align="right">Hasta: </TD><TD align="right" class="tb-fila"><input type="text" name="fecha14" size="20" maxlength="10" value="<?echo $SNChasta?>"></TD><TD class="tb-fila"><a href="javascript:showCal('Calendar14')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
	</TR>
	<TR>
		<TD>Laboral: </TD><TD align="left"><input type="text" name="laboral" size="40" maxlength="35" value="<?echo $solLABORAL?>"></TD>
		<TD align="right">Desde: </TD><TD align="right"><input type="text" name="fecha15" size="20" maxlength="10" value="<?echo $LABdesde?>"></TD><TD><a href="javascript:showCal('Calendar15')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
		<TD align="right">Hasta: </TD><TD align="right"><input type="text" name="fecha16" size="20" maxlength="10" value="<?echo $LABhasta?>"></TD><TD><a href="javascript:showCal('Calendar16')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
	</TR>
	<TR>
		<TD class="tb-fila">SUNACOOP: </TD><TD align="left" class="tb-fila"><input type="text" name="sunacoop" size="40" maxlength="35" value="<?echo $solSUNACOOP?>"></TD>
		<TD class="tb-fila" align="right">Desde: </TD><TD align="right" class="tb-fila"><input type="text" name="fecha17" size="20" maxlength="10" value="<?echo $SUNAdesde?>"></TD><TD class="tb-fila"><a href="javascript:showCal('Calendar17')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
		<TD class="tb-fila" align="right">Hasta: </TD><TD align="right" class="tb-fila"><input type="text" name="fecha18" size="20" maxlength="10" value="<?echo $SUNAhasta?>"></TD><TD class="tb-fila"><a href="javascript:showCal('Calendar18')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a></TD>
	</TR>
</table>
<BR>
<table width="100%">
	<tr><TD class="tb-head" colspan="2" align="center"><strong>ASPECTOS T&Eacute;CNICOS</strong></TD></tr>
	<td width="500">Consignaci&oacute;n de Organigrama</td><td><INPUT type="checkbox" name="organigrama" <?if ($organigrama=="on"){?> checked="true"<?}?>></td></tr>
   <tr><td class="tb-fila">Lista de Equipos y Maquinarias</td><td class="tb-fila"><INPUT type="checkbox" name="equipos" <?if ($equipos=="on"){?> checked="true"<?}?>></td></tr>
   <tr><td>Listado de Obras Ejecutadas</td><td><INPUT type="checkbox" name="obras" <?if ($obras=="on"){?> checked="true"<?}?>></td></tr>
</table>
<BR>
<table width="100%">
	<tr><TD class="tb-head" colspan="2" align="center"><strong>ASPECTOS FINANCIEROS</strong></TD></tr>
	<tr><td width="500">Principio de Contablidad (DPC-10)</td><td><INPUT type="checkbox" name="contabilidad" <?if ($contabilidad=="on"){?> checked="true"<?}?>></td></tr>
	<tr><td class="tb-fila">Estado de Ganancia y P&eacute;rdida</td><td class="tb-fila"><INPUT type="checkbox" name="gananciaPerdida" <?if ($gananPerd=="on"){?> checked="true"<?}?>></td></tr>
	<tr><td>Informe del Comisario</td><td><INPUT type="checkbox" name="comisario" <?if ($comisario=="on"){?> checked="true"<?}?>></td></tr>
	<tr><td class="tb-fila">Balance de Apertura </td><td class="tb-fila"><INPUT type="checkbox" name="apertura" <?if ($balanceApertura=="on"){?> checked="true"<?}?>></td></tr>
	<tr><td>Balance General </td><td><INPUT type="checkbox" name="general" <?if ($balanceGeneral=="on"){?> checked="true"<?}?>></td></tr>
	<tr><td class="tb-fila">Cuentas del Patrimonio</td><td class="tb-fila"><INPUT type="checkbox" name="patrimonio" <?if ($cuentasPatrimonio=="on"){?> checked="true"<?}?>></td></tr>
	<tr><td>A&ntilde;o de Estados Financieros</td><TD><INPUT type="text" name="anoFinanciero" size="6" maxlength="4" value="<?echo $anoEdoFinanciero?>"></TD></tr>
   <tr><td class="tb-fila">Flujo de Efectivo</td><td class="tb-fila"><INPUT type="checkbox" name="efectivo" <?if ($flujoEfectivo=="on"){?> checked="true"<?}?>></td></tr>
   <tr><td>Notas Explicativas</td><td><INPUT type="checkbox" name="notas" <?if ($notasExplicativas=="on"){?> checked="true"<?}?>></td></tr>
</table>
<BR>
<table width="100%">
	<TR><TD align="center" colspan="3" class="tb-head"><strong>SOCIOS PARTICIPANTES</strong></TD></TR>
	<tr>
          <td width="38%"><div align="center" class="tb-head"><strong>Nombre y Apellido</strong></div></td>
          <td width="24%"><div align="center" class="tb-head"><strong>C.I. No.</strong></div></td>
          <td width="38%"><div align="center" class="tb-head"><strong>Capital</strong></div></td>
        </tr>
        <tr>
          <td>
            <div align="center">
              <input name="nom_soc0" type="text" class="textbox" id="nom_soc0" value="<?php echo $soc_nom1; ?>" size="50" maxlength="20" />
            </div></td>
          <td>
            <div align="center">
              <input name="ci_soc0" type="text" class="textbox" id="ci_soc0" value="<?php echo $soc_ci1; ?>" size="25" maxlength="20" />
            </div></td>
          <td>
            <div align="center">
              <input name="cap_soc0" type="text" class="textbox" id="cap_soc0" value="<?php echo $soc_cap1; ?>" size="25" maxlength="20" />
            </div></td>
        </tr>
        <tr>
          <td>
            <div align="center">
              <input name="nom_soc1" type="text" class="textbox" id="nom_soc1" value="<?php echo $soc_nom2; ?>" size="50" maxlength="20" />
            </div></td>
          <td>
            <div align="center">
              <input name="ci_soc1" type="text" class="textbox" id="ci_soc1" value="<?php echo $soc_ci2; ?>" size="25" maxlength="20" />
            </div></td>
          <td>
            <div align="center">
              <input name="cap_soc1" type="text" class="textbox" id="cap_soc1" value="<?php echo $soc_cap2; ?>" size="25" maxlength="20" />
            </div></td>
        </tr>
        <tr>
          <td><div align="center">
            <input name="nom_soc2" type="text" class="textbox" id="nom_soc2" value="<?php echo $soc_nom3; ?>" size="50" maxlength="20" />
          </div></td>
          <td><div align="center">
            <input name="ci_soc2" type="text" class="textbox" id="ci_soc2" value="<?php echo $soc_ci3; ?>" size="25" maxlength="20" />
          </div></td>
          <td><div align="center">
            <input name="cap_soc2" type="text" class="textbox" id="cap_soc2" value="<?php echo $soc_cap3; ?>" size="25" maxlength="20" />
          </div></td>
        </tr>
        <tr>
          <td><div align="center">
            <input name="nom_soc3" type="text" class="textbox" id="nom_soc3" value="<?php echo $soc_nom4; ?>" size="50" maxlength="20" />
          </div></td>
          <td><div align="center">
            <input name="ci_soc3" type="text" class="textbox" id="ci_soc3" value="<?php echo $soc_ci4; ?>" size="25" maxlength="20" />
          </div></td>
          <td><div align="center">
            <input name="cap_soc3" type="text" class="textbox" id="cap_soc3" value="<?php echo $soc_cap4; ?>" size="25" maxlength="20" />
          </div></td>
        </tr>
        <tr>
          <td><div align="center">
            <input name="nom_soc4" type="text" class="textbox" id="nom_soc4" value="<?php echo $soc_nom5; ?>" size="50" maxlength="20" />
          </div></td>
          <td><div align="center">
            <input name="ci_soc4" type="text" class="textbox" id="ci_soc4" value="<?php echo $soc_ci5; ?>" size="25" maxlength="20" />
          </div></td>
          <td><div align="center">
            <input name="cap_soc4" type="text" class="textbox" id="cap_soc4" value="<?php echo $soc_cap5; ?>" size="25" maxlength="20" />
          </div></td>
        </tr>
        <tr>
          <td><div align="center">
            <input name="nom_soc5" type="text" class="textbox" id="nom_soc5" value="<?php echo $soc_nom6; ?>" size="50" maxlength="20" />
          </div></td>
          <td><div align="center">
            <input name="ci_soc5" type="text" class="textbox" id="ci_soc5" value="<?php echo $soc_ci6; ?>" size="25" maxlength="20" />
          </div></td>
          <td><div align="center">
            <input name="cap_soc5" type="text" class="textbox" id="cap_soc5" value="<?php echo $soc_cap6; ?>" size="25" maxlength="20" />
          </div></td>
        </tr>
</table>
<BR>
<table width="100%" align="center" border="0">
	<TR><TD align="center" colspan="6" class="tb-head"><strong>ASPECTOS LEGALES</strong></TD></TR>
         <tr>
            <td width="25%"><div align="center" class="tb-head"><strong>Tomo</strong></div></td>
            <td width="25%"><div align="center" class="tb-head"><strong>N&uacute;mero</strong></div></td>
            <td width="30%"><div align="center" class="tb-head"><strong>Registro</strong></div></td>
				<td width="15%" colspan="2"><div align="center" class="tb-head"><strong>Fecha</strong></div></td>
          </tr>
			<tr>
          <td><div align="center">
            <input name="tomo_leg1" type="text" class="textbox" id="tomo_leg1" value="<?php echo $tomo_leg1; ?>" size="20" maxlength="18">
          </div></td>
          <td><div align="center">
            <input name="nro_leg1" type="text" class="textbox" id="nro_leg1" value="<?php echo $nro_leg1; ?>" size="20" maxlength="18">
          </div></td>
			 <td><div align="center">
            <input name="reg_leg1" type="text" class="textbox" id="reg_leg1" value="<?php echo $reg_leg1; ?>" size="32" maxlength="30">
          </div></td>
          <td><div align="right">
            <input name="fecha19" type="text" class="textbox" id="fecha19" value="<?php echo $fec_leg1;?>" size="15" maxlength="10"></div></td>
			<td><div align="center">
          	<a href="javascript:showCal('Calendar19')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a>
          </div></D>
        </tr>
		  <tr>
          <td><div align="center">
            <input name="tomo_leg2" type="text" class="textbox" id="tomo_leg2" value="<?php echo $tomo_leg2; ?>" size="20" maxlength="18">
          </div></td>
          <td><div align="center">
            <input name="nro_leg2" type="text" class="textbox" id="nro_leg2" value="<?php echo $nro_leg2; ?>" size="20" maxlength="18">
          </div></td>
			 <td><div align="center">
            <input name="reg_leg2" type="text" class="textbox" id="reg_leg2" value="<?php echo $reg_leg2; ?>" size="32" maxlength="30">
          </div></td>
			<td><div align="right">
            <input name="fecha20" type="text" class="textbox" id="fecha20" value="<?php echo $fec_leg2;?>" size="15" maxlength="10"></div></td>
			<td><div align="center">
          	<a href="javascript:showCal('Calendar20')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a>
          </div></td>
        </tr>
		  <tr>
          <td><div align="center">
            <input name="tomo_leg3" type="text" class="textbox" id="tomo_leg3" value="<?php echo $tomo_leg3; ?>" size="20" maxlength="18">
          </div></td>
          <td><div align="center">
            <input name="nro_leg3" type="text" class="textbox" id="nro_leg3" value="<?php echo $nro_leg3; ?>" size="20" maxlength="18">
          </div></td>
			 <td><div align="center">
            <input name="reg_leg3" type="text" class="textbox" id="reg_leg3" value="<?php echo $reg_leg3; ?>" size="32" maxlength="30">
          </div></td>
			<td><div align="right">
            <input name="fecha21" type="text" class="textbox" id="fecha21" value="<?php echo $fec_leg3;?>" size="15" maxlength="10"></div></td>
			<TD><div align="center">
          	<a href="javascript:showCal('Calendar21')"><IMG src="../imagenes/fecha.png" width="22" height="22" border="0" align="middle"></a>
          </div></td>
        </tr>
</table>
<br>
<table width="100%">
<TR><TD align="center" colspan="6" class="tb-head"><strong>CUENTAS</strong></TD></TR>
	<TR>
		<TD class="tb-fila" width="20%">Cuenta de I.S.L.R.:</TD>
		<TD width="30%"><SELECT name="ctaISLR">
				<option class="tb-fila"><?echo $ctaISLR?></option>
				<?	while($fil=fetch_array($result)){ 
					$codigo=$fil['Cuenta'];?>
	            <option><?echo "$codigo"?></option>
					<?}?>
      </SELECT></TD>
		<TD class="tb-fila" width="20%">Cuenta de Impuesto Municipal:</TD>
		<TD width="30%"><SELECT name="ctaIM">
				<option class="tb-fila"><?echo $ctaIM?></option>
				<?while($fila1=fetch_array($result1)){ 
					$codigo1=$fila1['Cuenta'];?>
	            <option><?echo "$codigo1"?></option>
					<?}?>
      </SELECT></TD>
	</TR>
	<TR>
		<TD class="tb-fila" width="20%">Cuenta de I.V.A.:</TD>
		<TD width="30%"><SELECT name="ctaIVA">
				<option class="tb-fila"><?echo $ctaIVA?></option>
				<?while($fila2=fetch_array($result2)){
					$codigo2=$fila2['Cuenta'];?>
	            <option><?echo "$codigo2"?></option>
					<?}?>
      </SELECT></TD>
		<TD class="tb-fila" width="20%">Cuenta de Retenci&oacute;n Fondo Social:</TD>
		<TD width="30%"><SELECT name="ctaRFS">
				<option class="tb-fila"><?echo $ctaRFS?></option>
				<?while($fila3=fetch_array($result3)){ 
					$codigo3=$fila3['Cuenta'];?>
	            <option><?echo "$codigo3"?></option>
					<?}?>
      </SELECT></TD>
	</TR>
</table>
<table class="tb-tit" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center"><input type="submit" name="guardar" value="Agregar"></td>
		<td align="center"><input type="submit" name="actualizar" value="Actualizar"></td>
		<TD align="center"><input type="submit" name="eliminar" value="Eliminar"></td>
	</tr>
</table>
</form>
</body>
</html>
<?cerrar_conexion($conexion);?>

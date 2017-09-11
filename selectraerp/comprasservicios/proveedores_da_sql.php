<?php
 
/******************************************************/
/* Funcion cuadrar_mes
 * $Mes:  aqui se introduce el mes de la fecha de inicio
 * Devuelve mes incrementado
 */
 include("../config_bd.php"); // archivo que llama a la base de datos 
require_once '../lib/common.php';


$var_acc = $_POST["acc"];
$x_cod_proveedor = $_POST["codigo"];
$x_nombre = $_POST["nombre"];

if ($var_acc=='add')
{
	//$x_cod_proveedor = @$_POST["codigo"];
	//$x_nombre = @$_POST["nombre"];
	
	$var_IVA = $_POST["iva"];	
	$var_de_islr = $_POST["de_islr"];
	$var_nom_ing = $_POST["nom_ing"];
	$var_ref_com = $_POST["ref_com"];
	$var_ref_ban = $_POST["ref_ban"];
	$var_ci = $_POST["ci"];
	$var_cap_con_rnc = $_POST["cap_con_rnc"];
	$var_niv_rcn = $_POST["niv_rcn"];		
	$var_colegio = $_POST["colegio"];
	$var_curriculo = $_POST["curriculo"];
	$var_solvencia = $_POST["solvencia"];	
	$var_des_sol = $_POST["des_sol"];
	$var_des_sol=fecha_sql($var_des_sol);
	$var_has_sol = $_POST["has_sol"];
	$var_has_sol=fecha_sql($var_has_sol);
		
	$var_sso = $_POST["sso"];		
	$var_des_sso = $_POST["des_sso"];
	$var_des_sso=fecha_sql($var_des_sso);
	$var_has_sso = $_POST["has_sso"];		
	$var_has_sso=fecha_sql($var_has_sso);
	
	$var_ince = $_POST["ince"];
	$var_des_ince= $_POST["des_ince"];
	$var_des_ince=fecha_sql($var_des_ince);
	$var_has_ince= $_POST["has_ince"];	
	$var_has_ince=fecha_sql($var_has_ince);
	
	$var_municipal = $_POST["municipal"];
	$var_des_muni= $_POST["des_muni"];
	$var_des_muni=fecha_sql($var_des_muni);
	$var_has_muni= $_POST["has_muni"];	
	$var_has_muni=fecha_sql($var_has_muni);
	
	$var_snc = $_POST["snc"];
	$var_des_snc = $_POST["des_snc"];
	$var_des_snc=fecha_sql($var_des_snc);
	$var_has_snc = $_POST["has_snc"];
	$var_has_snc=fecha_sql($var_has_snc);
	
	$var_laboral = $_POST["laboral"];
	$var_des_lab = $_POST["des_lab"];
	$var_des_lab=fecha_sql($var_des_lab);
	$var_has_lab = $_POST["has_lab"];
	$var_has_lab=fecha_sql($var_has_lab);
	
	$var_sunacop = $_POST["sunacop"];
	$var_des_suna = $_POST["des_suna"];
	$var_des_suna=fecha_sql($var_des_suna);
	$var_has_suna = $_POST["has_suna"];
	$var_has_suna=fecha_sql($var_has_suna);
	
	$var_che_con_org = $_POST["che_con_org"];
	$var_che_equ_maq = @$_POST["che_equ_maq"];
	$var_che_lis_ob_eje = @$_POST["che_lis_ob_eje"];
	$var_che_pri_cont = @$_POST["che_pri_cont"];
	$var_che_est_gan_per = @$_POST["che_est_gan_per"];
	$var_che_in_com = @$_POST["che_in_com"];
	$var_che_bal_ape = @$_POST["che_bal_ape"];
	$var_che_bal_gen = @$_POST["che_bal_gen"];
	$var_che_cue_pat = @$_POST["che_cue_pat"];
	$var_anos_est_fin = @$_POST["anos_est_fin"];
	$var_che_flu_efe = @$_POST["che_flu_efe"];
	$var_che_not_exp = @$_POST["che_not_exp"];
	
	$var_nom_soc = @$_POST["nom_soc"];	
	$var_ci_soc = @$_POST["ci_soc"];	
	$var_cap_soc = @$_POST["cap_soc"];
	
	$var_nom_soc1 = @$_POST["nom_soc1"];	
	$var_ci_soc1 = @$_POST["ci_soc1"];	
	$var_cap_soc1 = @$_POST["cap_soc1"];
	
	$var_nom_soc2 = @$_POST["nom_soc2"];	
	$var_ci_soc2 = @$_POST["ci_soc2"];	
	$var_cap_soc2 = @$_POST["cap_soc2"];
	
	$var_nom_soc3 = @$_POST["nom_soc3"];	
	$var_ci_soc3 = @$_POST["ci_soc3"];	
	$var_cap_soc3 = @$_POST["cap_soc3"];
	
	$var_nom_soc4 = @$_POST["nom_soc4"];	
	$var_ci_soc4 = @$_POST["ci_soc4"];	
	$var_cap_soc4 = @$_POST["cap_soc4"];
	
	$var_nom_soc5 = @$_POST["nom_soc5"];	
	$var_ci_soc5 = @$_POST["ci_soc5"];	
	$var_cap_soc5 = @$_POST["cap_soc5"];	
	
	$var_fecha_al1 = @$_POST["fecha_al1"];
	$var_tomo1 = @$_POST["tomo1"];
	$var_num1 = @$_POST["num1"];
	$var_reg1 = @$_POST["reg1"];
	$var_fecha_al2 = @$_POST["fecha_al2"];
	$var_tomo2 = @$_POST["tomo2"];
	$var_num2 = @$_POST["num2"];
	$var_reg2 = @$_POST["reg2"];
	$var_fecha_al3 = @$_POST["fecha_al3"];
	$var_tomo3 = @$_POST["tomo3"];
	$var_num3 = @$_POST["num3"];
	$var_reg3 = @$_POST["reg3"];
	
	
	
  $var_sql="update proveedores_dat_adi SET decla_iva='$var_IVA',decla_islr='$var_de_islr',ingeniero='$var_nom_ing',
  ref_com='$var_ref_com',ref_ban='$var_ref_ban',ci='$var_ci',cap_ren_rnc='$var_cap_con_rnc',
  nivel_rnc='$var_niv_rcn',colegio='$var_colegio',curriculo='$var_curriculo',solvencia='$var_curriculo',
  desde='$var_des_sol',hasta='$var_has_sol',sol_sso='$var_sso',sso_des='$var_des_sso',sso_has='$var_has_sso',
  sol_ince='$var_ince',ince_des='$var_des_ince',ince_has='$var_has_ince',  
  sol_muni='$var_municipal',muni_des='$var_des_muni',muni_has='$var_has_muni',sol_snc='$var_snc',
  snc_des='$var_des_snc',snc_has='$var_has_snc',sol_laboral='$var_laboral',
  lab_des='$var_des_lab',lab_has='$var_has_lab',sol_sunacop='$var_sunacop',  
  sunacop_des='$var_des_suna',sunacop_has='$var_has_suna',  
  at_con_organigrama='$var_che_con_org',at_lis_equi_maq='$var_che_equ_maq',
  at_lis_obr_eje='$var_che_lis_ob_eje',af_prin_conta_dpcp='$var_che_pri_cont',af_edo_gan_per='$var_che_est_gan_per',
  af_inf_com='$var_che_in_com',af_bal_ape='$var_che_bal_ape',
  af_bal_gen='$var_che_bal_gen',af_cue_pat='$var_che_cue_pat',af_ano_est_fin='$var_anos_est_fin',
  af_flu_efe='$var_che_flu_efe',af_not_exp='$var_che_not_exp',  
  soc_nom1='$var_nom_soc',soc_ci1='$var_ci_soc',soc_cap1='$var_cap_soc',soc_nom2='$var_nom_soc1',
  soc_ci2='$var_ci_soc1',soc_cap2='$var_cap_soc1',
  soc_nom3='$var_nom_soc2',soc_ci3='$var_ci_soc2',soc_cap3='$var_cap_soc2',
  soc_nom4='$var_nom_soc3',soc_ci4='$var_ci_soc3',soc_cap4='$var_cap_soc3',
  soc_nom5='$var_nom_soc4',soc_ci5='$var_ci_soc4',soc_cap5='$var_cap_soc4',
  soc_nom6='$var_nom_soc5',soc_ci6='$var_ci_soc5',soc_cap6='$var_cap_soc5',   
  al_fecha1='$var_fecha_al1',
  al_tomo1='$var_tomo1',al_num1='$var_num1',al_reg1='$var_reg1',  
  al_fecha2='$var_fecha_al2',al_tomo2='$var_tomo2',al_num2='$var_num2',al_reg2='$var_reg2',
  al_fecha3='$var_fecha_al3',al_tomo3='$var_tomo3',al_num3='$var_num3',al_reg3='$var_reg3' WHERE cod_pro = $x_cod_proveedor";
  $result = mysql_query($var_sql, $conectar); 	
  mysql_close($conectar);
   
	//$_SESSION[ewSessionMessage] = "Registro fue Agregado Satisfactoriamente";
	//echo $var_sql;
	 header("Location: proveedores_list.php");
}

	
   
  
  

?>
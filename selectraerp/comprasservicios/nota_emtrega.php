<?
require_once '../lib/config.php';
require_once '../lib/common.php';
$Conn = new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);


$rsac = (empty($_REQUEST['rsac'])) ? '' : $_REQUEST['rsac'];
$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];
$var_accion = (empty($_REQUEST['acc'])) ? '' : $_REQUEST['acc'];
$var_rows_imp=$var_rows;
$rsac=3;
if ($var_accion=='sav')
{
	echo '<font color="#FF0000"><strong> Nota Guardada. </strong></font>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:spry="http://ns.adobe.com/spry" class="fondo">
<head>
<title>SEIC</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../lib/common.js"></script>
<script type="text/javascript" src="../lib/Prototype/prototype.js"></script>
<script type="text/javascript" src="../lib/spry/xpath.js"></script>
<script type="text/javascript" src="../lib/spry/SpryData.js"></script>

</head>
<body>
<TABLE>
	<TR class="row-br">
		<TD><?titulo("Notas de Entrega","","../menu_int.php?cod=2","28");?></TD>
	</TR>
</TABLE>


<!--<table width="100%" class="tb-tit">
  <tr>
    <td class=""><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
	  <?php  //echo $rsac; ?>
        <td><div align="left"><img src="../img_sis/icons/1.png" width="22" height="22" class="icon" />Notas de Entrega</div>          
          <div align="center">
        </div></td>
        <td><div align="right"></div>
          <table border="0" align="right" cellpadding="0" cellspacing="0" onclick="javascript:window.location='../menu_int.php?cod=2';" class="btn_bg" style="cursor: pointer;">
            <tr>
              <td style="padding: 0px;" align="right"><div align="center"><img src="../img_sis/bt_left.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>
              <td class="btn_bg"><div align="center"><img src="../img_sis/ico_up.gif" width="16" height="16" /></div></td>
              <td class="btn_bg" style="padding: 0px 4px;"><div align="center">Regresar</div></td>
              <td style="padding: 0px;" align="left"><div align="center"><img src="../img_sis/bt_right.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>
            </tr>
            <table border="0" cellspacing="0" cellpadding="0" onclick="javascript:window.location='requisicionesadd.php?cod_centro=<?php //echo $cod_centro;?>';">
              <tr>
                <td>
								
			</td>
              </tr>
            </table>
			
        </table></td>
      </tr>
</table>-->
<br>
<? switch ($rsac) {; case "3":?>
<?php $rs = $Conn->query("SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,r.estacion,r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha FROM requisiciones r where tipo = '3' and r.situacion='Revisar'");
while ($row_rs = $rs->fetch_assoc()) 
{
	$nTotalRecs=$nTotalRecs+1;
	
}

$nTotalRecs=$nTotalRecs-1;


if ($var_rows=='' | $var_rows==0 )
{	
	$var_rows_f=15;
	$var_rows=$var_rows_f;
	$var_rows_i=0;	
	$var_ade=0;
	$var_ade2=0;
	$var_atr=0;
	$var_atr2=0;
	$var_rows_de=1;
}

elseif ($var_rows<$nTotalRecs)
{
	$var_rows_i=$var_rows;
	$var_rows_de=$var_rows_i;
	$var_rows_f=15;
	$var_rows2=$var_rows-$var_rows_f;						
	$var_rows=$var_rows+$var_rows_f;
	$var_ade=1;
	$var_ade2=1;
	$var_atr2=1;
	$var_atr=1;
}		
?>
<table class="tb-head" width="100%">
  <tr>
    <td><input type="text" name="<?php echo ewTblBasicSrch; ?>" size="20" value="<?php echo $psearch;?>" /></td>
    <td><? btn('search','',1) ?></td>
    <td><? btn("show_all","emision_ord.php") ?></td>
    <td><input type="radio" name="<?php echo ewTblBasicSrchType;?>" value="" <?php if ($psearchtype == "") { ?>checked<?php } ?> />
      Frase exacta&nbsp;
      <input type="radio" name="<?php echo ewTblBasicSrchType; ?>" value="AND" <?php if ($psearchtype == "AND") { ?>checked<?php } ?> />
      Todas las palabras&nbsp;&nbsp;
      <input type="radio" name="<?php echo ewTblBasicSrchType; ?>" value="OR" <?php if ($psearchtype == "OR") { ?>checked<?php } ?> />
      Cualquier palabra</td>
  </tr>
</tabl>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="menu-bg">
  <br>
	<tr>
    <td width="110" class="tb-head"><div align="left">Situacion</div></td>
    <td width="143" class="tb-head"><div align="center">Numero</div></td>
    <td width="85" class="tb-head">Fecha</td>
    <td width="266" class="tb-head">Concepto</td>
    <td width="52" class="tb-head">Imprimir</td>
    <td width="178" class="tb-head"><div align="center">Despachar</div></td>
    <td width="155" colspan="2" class="tb-head"><div align="center">Entregar Material </div>
      </td>
    </tr>
<?php  $rs = $Conn->query("SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,r.estacion,r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha FROM requisiciones r where tipo = '3' and r.situacion='Revisar' order by r.cod_requisicion LIMIT 0,15");
while ($row_rs = $rs->fetch_assoc()) 
{
$var_cod_req=$row_rs['cod_requisicion'];
$rso = $Conn->query("SELECT count(*) as valor FROM ordenes_ne where cod_req = $var_cod_req and estado <> 'Anulado'");
while ($row_rso = $rso->fetch_assoc()) 
{
	$var_valor=$row_rso['valor'];
}
$rso->close()
?>
  <tr>
    <td><? echo $row_rs['situacion'] ?>
      <div align="center"></div></td>
    <td>
      <div align="center"><? echo $row_rs['cod_requisicion'] ?></div></td>
    <td>
      <? echo fecha($row_rs['fecha']); $var_cod_re=$row_rs['cod_requisicion']; ?>      <div align="center"></div></td>
    <td><? echo $row_rs['concepto'] ?>
      <div align="center"></div></td>
    <td><div align="center"><a href="<?php if($var_valor>0){echo "ordenesneprint.php?id=$var_cod_re";}?>"<?php if($var_valor>0){echo "target='_blank'";} ?>><img src="<?php if ($var_valor>0){echo "../img_sis/ico_print.gif";$estado="Imprimir Nota de Entrega";}else{echo "../img_sis/ico_est6.gif";$estado="Nota no Generada";}?>" title="<?echo $estado?>" width="16" height="16" border="0" /></a></div></td>
    <td><div align="center"><a href="<?php if($var_valor>0){echo "adjudicar_ne.php?cod_riqui=$var_cod_req&amp;acc=adj";} ?>"><img src="<?php if ($var_valor>0){echo "../img_sis/ico_back.gif";$estado="Despachar";}else{echo "../img_sis/ico_est6.gif";$estado="Nota no Generada";}?>" title="<?echo $estado;?>" width="16" height="16" border="0" /></a></div></td>
    <td><div align="center"><a href="adjudicar_ne.php?cod_riqui= <?php echo $row_rs['cod_requisicion'] ?>">	
        <img src="<?php if ($row_rs['situacion']=="Revisar"){echo "../img_sis/ico_ok.gif"; $estado="Generar Nota de Entrega";}else{echo "../img_sis/ico_propiedades.gif";}?>" title="<?echo $estado;?>" width="16" height="16" border="0" /></a></div></td>
	
	
  </tr>
<? };  $rs->close();?>
</table>

<? break; case "2":?>
<script type="text/javascript">
</script>
<script type="text/javascript">var ds_centros = new Spry.Data.XMLDataSet("data_centros.php?cod_unidad=<?= $cod_unidad ?>", "data/item",{ useCache:false });
</script>
<? break; default:?>
<script type="text/javascript">
var ds_unidades = new Spry.Data.XMLDataSet("data_unidades.php", "data/item",{ useCache:false });
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <form action="usuarios_list.php" name="ewpagerform" id="ewpagerform">
    <tr>
      <td nowrap="nowrap">
        <? }; //case ?>
        <?php 
if ($nTotalRecs >= 0) {
/*	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	$PrevStart = $nStartRec - $nDisplayRecs;
	if ($PrevStart < 1) { $PrevStart = 1; }
	$NextStart = $nStartRec + $nDisplayRecs;
	if ($NextStart > $nTotalRecs) { $NextStart = $nStartRec ; }
	$LastStart = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;*/
	?>
        <table width="100%" class="tb-head">
          <tr>
            <td>
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><span class="phpmaker">P&aacute;gina&nbsp;</span></td>
                  <!--first page button-->
                  <?php if ($var_atr == 0) { ?>
                  <td><img src="../images/firstdisab.gif" alt="Primera" width="16" height="16" border="0" /></td>
                  <?php } else { ?>
                  <td><a href="<?php echo "emision_ord.php" ?>"><img src="../images/first.gif" alt="Primera" width="16" height="16" border="0" /></a></td>
                  <?php } ?>
                  <!--previous page button-->
                  <?php if ($var_atr2 == 0) { ?>
                  <td><img src="../images/prevdisab.gif" alt="Anterior" width="16" height="16" border="0" /></td>
                  <?php } else { ?>
                  <td><a href="<?php echo "emision_ord.php";?>"><img src="../images/prev.gif" alt="Anterior" width="16" height="16" border="0" /></a></td>
                  <?php } ?>
                  <td><input type="text" name="pageno" value="<?php echo intval(($var_rows-1)/$var_rows_f+1); ?>" size="4" /></td>
                  <!--next page button-->
                  <?php if ($var_ade==0) { ?>
                  <td><img src="../images/nextdisab.gif" alt="Siguiente" width="16" height="16" border="0" /></td>
                  <?php } else { ?>
                  <td><a href="<?php echo "emision_ord.php";?>"><img src="../images/next.gif" alt="Siguiente" width="16" height="16" border="0" /></a></td>
                  <?php  } ?>
                  <!--last page button-->
                  <?php if ($var_ade2==0) { ?>
                  <td><img src="../images/lastdisab.gif" alt="Ultima" width="16" height="16" border="0" /></td>
                  <?php } else { ?>
                  <td><a href="<?php echo "emision_ord.php";?>"><img src="../images/last.gif" alt="Ultima" width="16" height="16" border="0" /></a></td>
                  <?php } ?>
                  <td><span class="phpmaker">&nbsp;de <?php echo intval(($nTotalRecs)/$var_rows_f+1);?></span></td>
                </tr>
            </table></td>
            <td>
              
              Registro <?php echo $var_rows_de; ?> a <?php echo $var_rows; ?> de <?php echo $nTotalRecs; ?> </td>
          </tr>
        </table>
        <?php } else { ?>
        <?php if ($sSrchWhere == "0=101") { ?>
        <span class="phpmaker"></span>
        <?php } else { ?>
        <span class="phpmaker"></span>
        <?php } ?>
        <?php } ?>      </td>
    </tr>
  </form>
</table>
    <?php include ("../footer.php") ?>
    <?php

//-------------------------------------------------------------------------------
// Function SetUpInlineEdit
// - Set up Inline Edit parameters based on querystring parameters a & key
// - Variables setup: sAction, sKey, Session(TblKeyName)

function SetUpInlineEdit($conn)
{
	global $x_cod_usuario;
	global $bInlineEdit;
	global $sAction;
	global $ewCurSec;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtolower($sAction) == "edit") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["cod_usuario"]) > 0) {
				$x_cod_usuario = $_GET["cod_usuario"];
			} else {
				$bInlineEdit = false;
			}
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION[ewSessionTblKey . "_cod_usuario"] = $x_cod_usuario; // Set up Inline Edit key
				}
			}
		} elseif (strtolower($sAction) == "cancel") { // Switch out of Inline Edit Mode
			$_SESSION[ewSessionTblKey . "_cod_usuario"] = ""; // Clear Inline Edit key
		}
	} else {
		$sAction = @$_POST["a_list"];
		if (strtolower($sAction) == "update") { // Update Record

			// Get fields from form
			$GLOBALS["x_LOG_USR"] = @$_POST["x_LOG_USR"];
			$GLOBALS["x_NOM_USU"] = @$_POST["x_NOM_USU"];
			$GLOBALS["x_PAS_USR"] = @$_POST["x_PAS_USR"];
			$GLOBALS["x_ACC_SEG"] = @$_POST["x_ACC_SEG"];
			$GLOBALS["x_ACC_AUD"] = @$_POST["x_ACC_AUD"];
			$GLOBALS["x_ACC_ODP"] = @$_POST["x_ACC_ODP"];
			$GLOBALS["x_ACC_FOR"] = @$_POST["x_ACC_FOR"];
			$GLOBALS["x_ACC_CHQ"] = @$_POST["x_ACC_CHQ"];
			$GLOBALS["x_ACC_CAJ"] = @$_POST["x_ACC_CAJ"];
			$GLOBALS["x_ACC_BIE"] = @$_POST["x_ACC_BIE"];
			$GLOBALS["x_ACC_PRE"] = @$_POST["x_ACC_PRE"];
			$GLOBALS["x_ACC_ODC"] = @$_POST["x_ACC_ODC"];
			$GLOBALS["x_ACC_REQ"] = @$_POST["x_ACC_REQ"];
			$GLOBALS["x_ACC_SER"] = @$_POST["x_ACC_SER"];
			$GLOBALS["x_CODIGOUNIDAD"] = @$_POST["x_CODIGOUNIDAD"];
			$GLOBALS["x_PRESUPUESTO"] = @$_POST["x_PRESUPUESTO"];
			$GLOBALS["x_COMPROMETER"] = @$_POST["x_COMPROMETER"];
			$GLOBALS["x_DCOMPROMETER"] = @$_POST["x_DCOMPROMETER"];
			$GLOBALS["x_CAUSAR"] = @$_POST["x_CAUSAR"];
			$GLOBALS["x_DCAUSAR"] = @$_POST["x_DCAUSAR"];
			$GLOBALS["x_DECRETOS"] = @$_POST["x_DECRETOS"];
			$GLOBALS["x_CHEQUEODP"] = @$_POST["x_CHEQUEODP"];
			$GLOBALS["x_CHEQUETER"] = @$_POST["x_CHEQUETER"];
			$GLOBALS["x_MOVBAN"] = @$_POST["x_MOVBAN"];
			$GLOBALS["x_ODP"] = @$_POST["x_ODP"];
			$GLOBALS["x_AODP"] = @$_POST["x_AODP"];
			$GLOBALS["x_PROVEEDORES"] = @$_POST["x_PROVEEDORES"];
			$GLOBALS["x_ORDENES"] = @$_POST["x_ORDENES"];
			$GLOBALS["x_CODCCOS"] = @$_POST["x_CODCCOS"];
			$GLOBALS["x_cod_usuario"] = @$_POST["x_cod_usuario"];
			if ($_SESSION[ewSessionTblKey ."_cod_usuario"] == ((get_magic_quotes_gpc())? stripslashes($x_cod_usuario) : $x_cod_usuario)) {
				if (InlineEditData($conn)) {
					$_SESSION[ewSessionMessage] = "Actualizaci&oacute;n de Registro Exitosa";
				}
			}
		}
		$_SESSION[ewSessionTblKey . "_cod_usuario"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`LOG_USR` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`NOM_USU` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`PAS_USR` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`CODCCOS` LIKE '%" . $sKeyword . "%' OR ";
	if (substr($BasicSearchSQL, -4) == " OR ") { $BasicSearchSQL = substr($BasicSearchSQL, 0, strlen($BasicSearchSQL)-4); }
	return $BasicSearchSQL;
}

//-------------------------------------------------------------------------------
// Function SetUpBasicSearch
// - Set up Basic Search parameter based on form elements pSearch & pSearchType
// - Variables setup: sSrchBasic

function SetUpBasicSearch()
{
	global $sSrchBasic, $psearch, $psearchtype;
	if ($psearch <> "") {
		if ($psearchtype <> "") {
			while (strpos($psearch, "  ") != false) {
				$psearch = str_replace("  ", " ",$psearch);
			}
			$arKeyword = split(" ", trim($psearch));
			foreach ($arKeyword as $sKeyword) {
				$sSrchBasic .= "(" . BasicSearchSQL($sKeyword) . ") " . $psearchtype . " ";
			}
		} else {
			$sSrchBasic = BasicSearchSQL($psearch);
		}
	}
	if (substr($sSrchBasic, -4) == " OR ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-4); }
	if (substr($sSrchBasic, -5) == " AND ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-5); }
	if ($psearch <> "") {
		$_SESSION[ewSessionTblBasicSrch] = $psearch;
		$_SESSION[ewSessionTblBasicSrchType] = $psearchtype;
	}
}

//-------------------------------------------------------------------------------
// Function ResetSearch
// - Clear all search parameters

function ResetSearch() 
{

	// Clear search where
	$sSrchWhere = "";
	$_SESSION[ewSessionTblSearchWhere] = $sSrchWhere;

	// Clear advanced search parameters
	$_SESSION[ewSessionTblAdvSrch . "_x_LOG_USR"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_NOM_USU"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_PAS_USR"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_SEG"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_AUD"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_ODP"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_FOR"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_CHQ"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_CAJ"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_BIE"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_PRE"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_ODC"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_REQ"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ACC_SER"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_CODIGOUNIDAD"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_PRESUPUESTO"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_COMPROMETER"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_DCOMPROMETER"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_CAUSAR"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_DCAUSAR"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_DECRETOS"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_CHEQUEODP"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_CHEQUETER"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_MOVBAN"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ODP"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_AODP"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_PROVEEDORES"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ORDENES"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_CODCCOS"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_cod_usuario"] = "";
	$_SESSION[ewSessionTblBasicSrch] = "";
	$_SESSION[ewSessionTblBasicSrchType] = "";
}

//-------------------------------------------------------------------------------
// Function RestoreSearch
// - Restore all search parameters
//

function RestoreSearch()
{

	// Restore advanced search settings
	$GLOBALS["x_LOG_USR"] = @$_SESSION[ewSessionTblAdvSrch . "_x_LOG_USR"];
	$GLOBALS["x_NOM_USU"] = @$_SESSION[ewSessionTblAdvSrch . "_x_NOM_USU"];
	$GLOBALS["x_PAS_USR"] = @$_SESSION[ewSessionTblAdvSrch . "_x_PAS_USR"];
	$GLOBALS["x_ACC_SEG"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_SEG"];
	$GLOBALS["x_ACC_AUD"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_AUD"];
	$GLOBALS["x_ACC_ODP"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_ODP"];
	$GLOBALS["x_ACC_FOR"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_FOR"];
	$GLOBALS["x_ACC_CHQ"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_CHQ"];
	$GLOBALS["x_ACC_CAJ"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_CAJ"];
	$GLOBALS["x_ACC_BIE"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_BIE"];
	$GLOBALS["x_ACC_PRE"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_PRE"];
	$GLOBALS["x_ACC_ODC"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_ODC"];
	$GLOBALS["x_ACC_REQ"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_REQ"];
	$GLOBALS["x_ACC_SER"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ACC_SER"];
	$GLOBALS["x_CODIGOUNIDAD"] = @$_SESSION[ewSessionTblAdvSrch . "_x_CODIGOUNIDAD"];
	$GLOBALS["x_PRESUPUESTO"] = @$_SESSION[ewSessionTblAdvSrch . "_x_PRESUPUESTO"];
	$GLOBALS["x_COMPROMETER"] = @$_SESSION[ewSessionTblAdvSrch . "_x_COMPROMETER"];
	$GLOBALS["x_DCOMPROMETER"] = @$_SESSION[ewSessionTblAdvSrch . "_x_DCOMPROMETER"];
	$GLOBALS["x_CAUSAR"] = @$_SESSION[ewSessionTblAdvSrch . "_x_CAUSAR"];
	$GLOBALS["x_DCAUSAR"] = @$_SESSION[ewSessionTblAdvSrch . "_x_DCAUSAR"];
	$GLOBALS["x_DECRETOS"] = @$_SESSION[ewSessionTblAdvSrch . "_x_DECRETOS"];
	$GLOBALS["x_CHEQUEODP"] = @$_SESSION[ewSessionTblAdvSrch . "_x_CHEQUEODP"];
	$GLOBALS["x_CHEQUETER"] = @$_SESSION[ewSessionTblAdvSrch . "_x_CHEQUETER"];
	$GLOBALS["x_MOVBAN"] = @$_SESSION[ewSessionTblAdvSrch . "_x_MOVBAN"];
	$GLOBALS["x_ODP"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ODP"];
	$GLOBALS["x_AODP"] = @$_SESSION[ewSessionTblAdvSrch . "_x_AODP"];
	$GLOBALS["x_PROVEEDORES"] = @$_SESSION[ewSessionTblAdvSrch . "_x_PROVEEDORES"];
	$GLOBALS["x_ORDENES"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ORDENES"];
	$GLOBALS["x_CODCCOS"] = @$_SESSION[ewSessionTblAdvSrch . "_x_CODCCOS"];
	$GLOBALS["x_cod_usuario"] = @$_SESSION[ewSessionTblAdvSrch . "_x_cod_usuario"];
	$GLOBALS["psearch"] = @$_SESSION[ewSessionTblBasicSrch];
	$GLOBALS["psearchtype"] = @$_SESSION[ewSessionTblBasicSrchType];
}

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session(TblOrderBy), Session(Tbl_Field_Sort)

function SetUpSortOrder()
{
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field `LOG_USR`
		if ($sOrder == "LOG_USR") {
			$sSortField = "`LOG_USR`";
			$sLastSort = @$_SESSION[ewSessionTblSort . "_x_LOG_USR"];
			$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			$_SESSION[ewSessionTblSort . "_x_LOG_USR"] = $sThisSort;
		} else {
			if (@$_SESSION[ewSessionTblSort . "_x_LOG_USR"] <> "") { @$_SESSION[ewSessionTblSort . "_x_LOG_USR"] = ""; }
		}

		// Field `NOM_USU`
		if ($sOrder == "NOM_USU") {
			$sSortField = "`NOM_USU`";
			$sLastSort = @$_SESSION[ewSessionTblSort . "_x_NOM_USU"];
			$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			$_SESSION[ewSessionTblSort . "_x_NOM_USU"] = $sThisSort;
		} else {
			if (@$_SESSION[ewSessionTblSort . "_x_NOM_USU"] <> "") { @$_SESSION[ewSessionTblSort . "_x_NOM_USU"] = ""; }
		}

		// Field `PAS_USR`
		if ($sOrder == "PAS_USR") {
			$sSortField = "`PAS_USR`";
			$sLastSort = @$_SESSION[ewSessionTblSort . "_x_PAS_USR"];
			$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			$_SESSION[ewSessionTblSort . "_x_PAS_USR"] = $sThisSort;
		} else {
			if (@$_SESSION[ewSessionTblSort . "_x_PAS_USR"] <> "") { @$_SESSION[ewSessionTblSort . "_x_PAS_USR"] = ""; }
		}
		$_SESSION[ewSessionTblOrderBy] = $sSortField . " " . $sThisSort;
		$_SESSION[ewSessionTblStartRec] = 1;
	}
	$sOrderBy = @$_SESSION[ewSessionTblOrderBy];
	if ($sOrderBy == "") {
		if (ewSqlOrderBy <> "" && ewSqlOrderBySessions <> "") {
			$sOrderBy = ewSqlOrderBy;
			@$_SESSION[ewSessionTblOrderBy] = $sOrderBy;
			$arOrderBy = explode(",", ewSqlOrderBySessions);
			for($i=0; $i<count($arOrderBy); $i+=2) {
				@$_SESSION[ewSessionTblSort . "_" . $arOrderBy[$i]] = $arOrderBy[$i+1];
			}
		}
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;

	if (strlen(@$_GET[ewTblStartRec]) > 0) {
		$nStartRec = @$_GET[ewTblStartRec];
		$_SESSION[ewSessionTblStartRec] = $nStartRec;
	} elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			} elseif ($nStartRec >= intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION[ewSessionTblStartRec] = $nStartRec;
		} else {
			$nStartRec = @$_SESSION[ewSessionTblStartRec];
		}
	} else {
		$nStartRec = @$_SESSION[ewSessionTblStartRec];
	}

	// Check if correct start record counter
	if (!(is_numeric($nStartRec)) || ($nStartRec == "")) { // Avoid invalid start record counter
		$nStartRec = 1; // Reset start record counter
		$_SESSION[ewSessionTblStartRec] = $nStartRec;
	} elseif ($nStartRec > $nTotalRecs) { // Avoid starting record > total records
		$nStartRec = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1; // Point to last page first record
		$_SESSION[ewSessionTblStartRec] = $nStartRec;
	}
}

//-------------------------------------------------------------------------------
// Function ResetCmd
// - Clear list page parameters
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters

function ResetCmd()
{

	// Get Reset command
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];
		if (strtolower($sCmd) == "reset") { // Reset search criteria
			ResetSearch();
		} elseif (strtolower($sCmd) == "resetall") { // Reset search criteria and session vars
			ResetSearch();
		} elseif (strtolower($sCmd) == "resetsort") { // Reset sort criteria
			$sOrderBy = "";
			$_SESSION[ewSessionTblOrderBy] = $sOrderBy;
			if (@$_SESSION[ewSessionTblSort . "_x_LOG_USR"] <> "") { $_SESSION[ewSessionTblSort . "_x_LOG_USR"] = ""; }
			if (@$_SESSION[ewSessionTblSort . "_x_NOM_USU"] <> "") { $_SESSION[ewSessionTblSort . "_x_NOM_USU"] = ""; }
			if (@$_SESSION[ewSessionTblSort . "_x_PAS_USR"] <> "") { $_SESSION[ewSessionTblSort . "_x_PAS_USR"] = ""; }
		}

		// Reset start position (Reset command)
		$nStartRec = 1;
		$_SESSION[ewSessionTblStartRec] = $nStartRec;
	}
}
?>
    <?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_cod_usuario;
	$sFilter = ewSqlKeyWhere;
	if (!is_numeric($x_cod_usuario)) return false;
	$x_cod_usuario =  (get_magic_quotes_gpc()) ? stripslashes($x_cod_usuario) : $x_cod_usuario;
	$sFilter = str_replace("@cod_usuario", AdjustSql($x_cod_usuario), $sFilter); // Replace key value
	$sSql = ewBuildSql(ewSqlSelect, ewSqlWhere, ewSqlGroupBy, ewSqlHaving, ewSqlOrderBy, $sFilter, "");
	$rs = phpmkr_query($sSql,$conn) or die("Fallo al ejecutar la consulta en la l&iacute;nea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	} else {
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_LOG_USR"] = $row["LOG_USR"];
		$GLOBALS["x_NOM_USU"] = $row["NOM_USU"];
		$GLOBALS["x_PAS_USR"] = $row["PAS_USR"];
		$GLOBALS["x_ACC_SEG"] = $row["ACC_SEG"];
		$GLOBALS["x_ACC_AUD"] = $row["ACC_AUD"];
		$GLOBALS["x_ACC_ODP"] = $row["ACC_ODP"];
		$GLOBALS["x_ACC_FOR"] = $row["ACC_FOR"];
		$GLOBALS["x_ACC_CHQ"] = $row["ACC_CHQ"];
		$GLOBALS["x_ACC_CAJ"] = $row["ACC_CAJ"];
		$GLOBALS["x_ACC_BIE"] = $row["ACC_BIE"];
		$GLOBALS["x_ACC_PRE"] = $row["ACC_PRE"];
		$GLOBALS["x_ACC_ODC"] = $row["ACC_ODC"];
		$GLOBALS["x_ACC_REQ"] = $row["ACC_REQ"];
		$GLOBALS["x_ACC_SER"] = $row["ACC_SER"];
		$GLOBALS["x_CODIGOUNIDAD"] = $row["CODIGOUNIDAD"];
		$GLOBALS["x_PRESUPUESTO"] = $row["PRESUPUESTO"];
		$GLOBALS["x_COMPROMETER"] = $row["COMPROMETER"];
		$GLOBALS["x_DCOMPROMETER"] = $row["DCOMPROMETER"];
		$GLOBALS["x_CAUSAR"] = $row["CAUSAR"];
		$GLOBALS["x_DCAUSAR"] = $row["DCAUSAR"];
		$GLOBALS["x_DECRETOS"] = $row["DECRETOS"];
		$GLOBALS["x_CHEQUEODP"] = $row["CHEQUEODP"];
		$GLOBALS["x_CHEQUETER"] = $row["CHEQUETER"];
		$GLOBALS["x_MOVBAN"] = $row["MOVBAN"];
		$GLOBALS["x_ODP"] = $row["ODP"];
		$GLOBALS["x_AODP"] = $row["AODP"];
		$GLOBALS["x_PROVEEDORES"] = $row["PROVEEDORES"];
		$GLOBALS["x_ORDENES"] = $row["ORDENES"];
		$GLOBALS["x_CODCCOS"] = $row["CODCCOS"];
		$GLOBALS["x_cod_usuario"] = $row["cod_usuario"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
    <?php

//-------------------------------------------------------------------------------
// Function EditData
// - Variables used: field variables

function InlineEditData($conn)
{
	global $x_cod_usuario;
	$sFilter = ewSqlKeyWhere;
	if (!is_numeric($x_cod_usuario)) return false;
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cod_usuario) : $x_cod_usuario;
	$sFilter = str_replace("@cod_usuario", AdjustSql($sTmp), $sFilter); // Replace key value
	$sSql = ewBuildSql(ewSqlSelect, ewSqlWhere, ewSqlGroupBy, ewSqlHaving, ewSqlOrderBy, $sFilter, "");
	$rs = phpmkr_query($sSql,$conn) or die("Fallo al ejecutar la consulta en la l&iacute;nea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);

	// Get old recordset
	$oldrs = phpmkr_fetch_array($rs);
	if (phpmkr_num_rows($rs) == 0) {
		return false; // Update Failed
	} else {
		$x_LOG_USR = @$_POST["x_LOG_USR"];
		$x_NOM_USU = @$_POST["x_NOM_USU"];
		$x_PAS_USR = @$_POST["x_PAS_USR"];
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_LOG_USR"]) : $GLOBALS["x_LOG_USR"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`LOG_USR`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_NOM_USU"]) : $GLOBALS["x_NOM_USU"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`NOM_USU`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_PAS_USR"]) : $GLOBALS["x_PAS_USR"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`PAS_USR`"] = $theValue;

		// Updating event
		if (Recordset_Updating($fieldList, $oldrs)) {

			// Update
			$sSql = "UPDATE `usuarios` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE " . $sFilter;
			phpmkr_query($sSql,$conn) or die("Fallo al ejecutar la consulta en la l&iacute;nea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
			$result = (phpmkr_affected_rows($conn) >= 0);

			// Updated event
			if ($result) Recordset_Updated($fieldList, $oldrs);
		} else {
			$result = false; // Update Failed
		}
	}
	return $result;
}

// Updating Event
function Recordset_Updating(&$newrs, $oldrs)
{

	// Enter your customized codes here
	return true;
}

// Updated event
function Recordset_Updated($newrs, $oldrs)
{
	$table = "usuarios";
}
?>
</body>
</html>
<? $Conn->close();?>

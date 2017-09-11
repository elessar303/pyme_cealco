<?php 
session_start();
ob_start();
?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0


require_once "../lib/common.php";
include ("../header.php");


?>

<script type="text/javascript" src="../lib/common.js" ></script>

<?php include ("../ewconfig.php") ?>
<?php //include ("../db.php") ?>
<?php include ("materiales_info.php") ?>
<?php include ("../advsecu.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php include ("../ewupload.php") ?>
<?php
if (!IsLoggedIn()) {
	ob_end_clean();
	header("Location: login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_cod_material = NULL;
$ox_cod_material = NULL;
$z_cod_material = NULL;
$ar_x_cod_material = NULL;
$ari_x_cod_material = NULL;
$x_cod_materialList = NULL;
$x_cod_materialChk = NULL;
$cbo_x_cod_material_js = NULL;
$x_descripcion = NULL;
$ox_descripcion = NULL;
$z_descripcion = NULL;
$ar_x_descripcion = NULL;
$ari_x_descripcion = NULL;
$x_descripcionList = NULL;
$x_descripcionChk = NULL;
$cbo_x_descripcion_js = NULL;
$x_unidad = NULL;
$ox_unidad = NULL;
$z_unidad = NULL;
$ar_x_unidad = NULL;
$ari_x_unidad = NULL;
$x_unidadList = NULL;
$x_unidadChk = NULL;
$cbo_x_unidad_js = NULL;
$x_parametro = NULL;
$ox_parametro = NULL;
$z_parametro = NULL;
$ar_x_parametro = NULL;
$ari_x_parametro = NULL;
$x_parametroList = NULL;
$x_parametroChk = NULL;
$cbo_x_parametro_js = NULL;
$x_codigo = NULL;
$ox_codigo = NULL;
$z_codigo = NULL;
$ar_x_codigo = NULL;
$ari_x_codigo = NULL;
$x_codigoList = NULL;
$x_codigoChk = NULL;
$cbo_x_codigo_js = NULL;
$x_codccos = NULL;
$ox_codccos = NULL;
$z_codccos = NULL;
$ar_x_codccos = NULL;
$ari_x_codccos = NULL;
$x_codccosList = NULL;
$x_codccosChk = NULL;
$cbo_x_codccos_js = NULL;
$x_existencia = NULL;
$ox_existencia = NULL;
$z_existencia = NULL;
$ar_x_existencia = NULL;
$ari_x_existencia = NULL;
$x_existenciaList = NULL;
$x_existenciaChk = NULL;
$cbo_x_existencia_js = NULL;
$x_minimo = NULL;
$ox_minimo = NULL;
$z_minimo = NULL;
$ar_x_minimo = NULL;
$ari_x_minimo = NULL;
$x_minimoList = NULL;
$x_minimoChk = NULL;
$cbo_x_minimo_js = NULL;
$x_maximo = NULL;
$ox_maximo = NULL;
$z_maximo = NULL;
$ar_x_maximo = NULL;
$ari_x_maximo = NULL;
$x_maximoList = NULL;
$x_maximoChk = NULL;
$cbo_x_maximo_js = NULL;
$x_ultimo_costo = NULL;
$ox_ultimo_costo = NULL;
$z_ultimo_costo = NULL;
$ar_x_ultimo_costo = NULL;
$ari_x_ultimo_costo = NULL;
$x_ultimo_costoList = NULL;
$x_ultimo_costoChk = NULL;
$cbo_x_ultimo_costo_js = NULL;
$x_fecha_ultimo_costo = NULL;
$ox_fecha_ultimo_costo = NULL;
$z_fecha_ultimo_costo = NULL;
$ar_x_fecha_ultimo_costo = NULL;
$ari_x_fecha_ultimo_costo = NULL;
$x_fecha_ultimo_costoList = NULL;
$x_fecha_ultimo_costoChk = NULL;
$cbo_x_fecha_ultimo_costo_js = NULL;
$x_cantidad_unidad = NULL;
$ox_cantidad_unidad = NULL;
$z_cantidad_unidad = NULL;
$ar_x_cantidad_unidad = NULL;
$ari_x_cantidad_unidad = NULL;
$x_cantidad_unidadList = NULL;
$x_cantidad_unidadChk = NULL;
$cbo_x_cantidad_unidad_js = NULL;
$x_unidad_salida = NULL;
$ox_unidad_salida = NULL;
$z_unidad_salida = NULL;
$ar_x_unidad_salida = NULL;
$ari_x_unidad_salida = NULL;
$x_unidad_salidaList = NULL;
$x_unidad_salidaChk = NULL;
$cbo_x_unidad_salida_js = NULL;
?>
<?php
$nStartRec = 0;
$nStopRec = 0;
$nTotalRecs = 0;
$nRecCount = 0;
$nRecActual = 0;
$sKeyMaster = "";
$sDbWhereMaster = "";
$sSrchAdvanced = "";
$psearch = "";
$psearchtype = "";
$sDbWhereDetail = "";
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sOrderBy = "";
$sSqlMaster = "";
$sListTrJs = "";
$bEditRow = "";
$nEditRowCnt = "";
$sDeleteConfirmMsg = "";
$nDisplayRecs = "20";
$nRecRange = 10;

// Open connection to the database
$conn = conexion(); //phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle reset command
ResetCmd();

// Get search criteria for Basic (Quick) Search
$psearch = (!get_magic_quotes_gpc()) ? addslashes(@$_GET[ewTblBasicSrch]) : @$_GET[ewTblBasicSrch];
$psearchtype = @$_GET[ewTblBasicSrchType];
SetUpBasicSearch();

// Build search criteria
if ($sSrchAdvanced != "") {
	if ($sSrchWhere <> "") $sSrchWhere .= " AND ";
	$sSrchWhere .= "(" . $sSrchAdvanced . ")"; // Advanced Search
}
if ($sSrchBasic != "") {
	if ($sSrchWhere <> "") $sSrchWhere .= " AND ";
	$sSrchWhere .= "(" . $sSrchBasic . ")"; // Basic Search
}

// Save search criteria
if ($sSrchWhere != "") {
	$_SESSION[ewSessionTblSearchWhere] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;TableAltRow;
	$_SESSION[ewSessionTblStartRec] = $nStartRec;
} else {
	$sSrchWhere = @$_SESSION[ewSessionTblSearchWhere];
	RestoreSearch();
}

// Build filter condition
$sDbWhere = "";
if ($sDbWhereDetail <> "") {
	if ($sDbWhere <> "") $sDbWhere .= " AND ";
	$sDbWhere .= "(" . $sDbWhereDetail . ")";
}
if ($sSrchWhere <> "") {
	if ($sDbWhere <> "") $sDbWhere .= " AND ";
	$sDbWhere .= "(" . $sSrchWhere . ")";
}

// Set up sorting order
$sOrderBy = "";
SetUpSortOrder();
$sSql = ewBuildSql(ewSqlSelect, ewSqlWhere, ewSqlGroupBy, ewSqlHaving, ewSqlOrderBy, $sDbWhere, $sOrderBy);

// echo $sSql . "<br>"; // Uncomment to show SQL for debugging
?>

<script type="text/javascript">
<!--
//EW_LookupFn = "../ewlookup.php"; // ewlookup file name
//EW_AddOptFn = "../ewaddopt.php"; // ewaddopt.php file name

//-->
</script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../ewp.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator
EW_UploadAllowedFileExt = "gif,jpg,jpeg,bmp,png,doc,xls,pdf,zip"; // allowed upload file extension

//-->
</script>
<script type="text/javascript">
<!--
var firstrowoffset = 1; // first data row start at
var tablename = 'ewlistmain'; // table name
var lastrowoffset = 0; // footer row
var usecss = true; // use css
var rowclass = 'ewTableRow'; // row class
var rowaltclass = 'ewTableAltRow'; // row alternate class
var rowmoverclass = 'ewTableHighlightRow'; // row mouse over class
var rowselectedclass = 'ewTableSelectRow'; // row selected class
var roweditclass = 'ewTableEditRow'; // row edit class
var rowcolor = '#FFFFCC'; // row color
var rowaltcolor = '#EEF2F5'; // row alternate color
var rowmovercolor = '#DDEEFF'; // row mouse over color
var rowselectedcolor = '#DDEEFF'; // row selected color
var roweditcolor = '#FFCC66'; // row edit color

//-->
</script>
<script type="text/javascript">
<!--
	var EW_DHTMLEditors = [];

//-->
</script>
<? 
	$materialess = $_GET['matee'];
	echo $materialess;
?>

<script langauge="javascript">
function cerrar()
{
	this.window.close()
}

var contador2 = 0;


function pass_value(thevalue,theid,indice,unidad,iva){
var  temp = opener.document.getElementById('contador')


contador = parseInt(temp.value)
contador=contador+1
temp.value=contador
var tabla= opener.document.getElementById('tabla_materiales')

contador2 =parseInt(window.opener.document.sampleform.contador.value)

var fila=  opener.document.createElement("tr")
var columna1=  opener.document.createElement("td")
columna1.innerHTML="<input name=\"cod_material[]\" disabled=\"true\" id=\"cod_material[]\" type=\"text\" readonly=\"true\" size=\"15\" value=\""+theid+"\"><input name=\"codigo"+contador2+"\"  id=\"codigo"+contador2+"\" type=\"hidden\"  value=\""+theid+"\">"


var columna2=  opener.document.createElement("td")
columna2.innerHTML="<input name=\"descrip[]\" type=\"text\" class=\"form-txt\" id=\"descrip\" size=\"100\" maxlength=\"200\" disabled=\"true\" style=\"width:100%\" readonly=\"true\" value='"+thevalue+"'>"

var columna3=  opener.document.createElement("td")
columna3.innerHTML="<input name=\"cantidad"+contador2+"\"  type=\"text\" id=\"cantidad"+contador2+"\" style=\"width:100%\" size=\"10\" onBlur=\"actualizar_formulario("+contador2+") \" value=\"0\" />"



var columna4=  opener.document.createElement("td")
columna4.innerHTML="<input name=\"precio"+contador2+"\" type=\"text\" id=\"precio"+contador2+"\" style=\"width:100%\" size=\"10\" value=\"0\" onBlur=\"actualizar_formulario("+contador2+") \" />"

var columna5=  opener.document.createElement("td")
columna5.innerHTML="<input name=\"iva_sino"+contador2+"\" disabled=\"true\" id=\"iva_sino"+contador2+"\" type=\"text\" readonly=\"true\" style=\"width:100%\" size=\"12\" value=\"0\" ><input name=\"iva_sino_mate"+contador2+"\"  id=\"iva_sino_mate"+contador2+"\" type=\"hidden\"  value="+iva+" style=\"width:100%\" size=\"12\" value=\"0\" >"

var columna6=  opener.document.createElement("td")
columna6.innerHTML="<input name=\"total"+contador2+"\"  disabled=\"true\" id=\"total"+contador2+"\" type=\"text\" readonly=\"true\" style=\"width:100%\" size=\"12\" value=\"0\" >"

var columna7=  opener.document.createElement("td")
columna7.innerHTML="<input  name=\"iva_sino_opt"+contador2+"\" type=\"checkbox\" id=\"iva_sino_opt"+contador2+"\" value=\"checkbox\"  onchange=\"actualizar_iva("+contador2+");\">"

var columna8=  opener.document.createElement("td")
columna8.innerHTML="<input  name=\"correl"+contador2+"\" type=\"hidden\" id=\"correl"+contador2+"\" value=\"0\" >"

fila.appendChild(columna1)
fila.appendChild(columna2)
fila.appendChild(columna3)
fila.appendChild(columna4)
fila.appendChild(columna5)
fila.appendChild(columna6)
fila.appendChild(columna7)
fila.appendChild(columna8)

tabla.appendChild(fila)

}
</script>
<?php

// Set up recordset
$rs = query($sSql, $conn) or die("Fallo al ejecutar la consulta en la línea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
$nTotalRecs = num_rows($rs);
if ($nDisplayRecs <= 0) { // Display all records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set up start record position
?>
<?php if (@$_SESSION[ewSessionMessage] <> "") { ?>
<p><span class="ewmsg"><?php echo $_SESSION[ewSessionMessage]; ?></span></p>
<?php $_SESSION[ewSessionMessage] = "";  } ?>
<table width="100%" class="tb-tit">
  <tr>
    <td class="row-br"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="../img_sis/icons/13.png" width="22" height="22" class="icon" /> Materiales</td>
		  <td align="right"><INPUT type="button" name="cerrar" value="Cerrar" onclick="javascript:cerrar()"></td>
        </tr>
      </table></td>
  </tr>
</table>
<table class="ewBasicSearch" width="100%">
<form id="fmaterialeslistsrch" name="fmaterialeslistsrch" action="materiales_search.php" >
<table width="100%" class="tb-head">
	<tr><td>
<table>
  <tr>
			<td><input type="text" name="<?php echo ewTblBasicSrch; ?>" size="20" value="<?php echo $psearch;?>"></td>
			<td><? btn('search','fmaterialeslistsrch',1) ?></td>
			<td><? btn('show_all','materiales_search.php?cmd=reset') ?></td>
	    <td><input type="radio" name="<?php echo ewTblBasicSrchType;?>" value="" <?php if ($psearchtype == "") { ?>checked<?php } ?>>Frase exacta&nbsp;<input type="radio" name="<?php echo ewTblBasicSrchType; ?>" value="AND" <?php if ($psearchtype == "AND") { ?>checked<?php } ?>>Todas las palabras&nbsp;&nbsp;<input type="radio" name="<?php echo ewTblBasicSrchType; ?>" value="OR" <?php if ($psearchtype == "OR") { ?>checked<?php } ?>>Cualquier palabra</td>
  </tr>
</table>
	</td></tr>
</form>
</table>
<?php if ($nTotalRecs > 0)  { ?>
<table width="100%"  id="ewlistmain">
<form method="post">
	<!-- Table header -->
	<tr class="tb-head">
		<td valign="top" style="width: 20%;"><span>
	<a href="materiales_search.php?order=<?php echo urlencode("cod_material"); ?>">
	C&oacute;digo<?php if (@$_SESSION[ewSessionTblSort . "_x_cod_material"] == "ASC") { ?><img src="../images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION[ewSessionTblSort . "_x_cod_material"] == "DESC") { ?><img src="../images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
	</a>
		</span></td>
		<td valign="top" style="width: 60%;"><span>
	<a href="materiales_search.php?order=<?php echo urlencode("descripcion"); ?>">
	Descripci&oacute;n<?php if (@$_SESSION[ewSessionTblSort . "_x_descripcion"] == "ASC") { ?><img src="../images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION[ewSessionTblSort . "_x_descripcion"] == "DESC") { ?><img src="../images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
	</a>
		</span></td>
		<td valign="top" style="width: 20%;"><span>
	<a href="materiales_search.php?order=<?php echo urlencode("unidad"); ?>">
	Unidad<?php if (@$_SESSION[ewSessionTblSort . "_x_unidad"] == "ASC") { ?><img src="../images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION[ewSessionTblSort . "_x_unidad"] == "DESC") { ?><img src="../images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
	</a>
		</span></td>
	</tr>
<?php

// Set the last record to display
$nStopRec = $nStartRec + $nDisplayRecs - 1;

// Move to the first record
$nRecCount = $nStartRec - 1;
if (num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStartRec -1);
}
$nRecActual = 0;
$indice=0+$nStartRec;
while (($row = @fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		//$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' ";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"tb-fila\"";
		}
		$x_cod_material = $row["cod_material"];
		$x_descripcion = $row["descripcion"];
		$x_unidad = $row["unidad"];
		$x_parametro = $row["parametro"];
		$x_codigo = $row["codigo"];
		$x_codccos = $row["codccos"];
		$x_existencia = $row["existencia"];
		$x_minimo = $row["minimo"];
		$x_maximo = $row["maximo"];
		$x_ultimo_costo = $row["ultimo_costo"];
		$x_fecha_ultimo_costo = $row["fecha_ultimo_costo"];
		$x_cantidad_unidad = $row["cantidad_unidad"];
		$x_unidad_salida = $row["unidad_salida"];
		$x_iva=$row['iva'];

$cadenas=split('"',$x_descripcion);
$descripcionj="";
foreach($cadenas as $value){
	$descripcionj=$descripcionj."".$value;
}
?>

	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>  style="cursor : pointer;" onclick="javascript:pass_value('<? echo $descripcionj?>','<?php echo $x_cod_material; ?>','<?=$indice?>','<?php echo $x_unidad; ?>','<?php echo $x_iva; ?>')">
<?$indice++;?>
		<!-- cod_material -->
		<td style="width: 20%;"><span>
<?php echo $x_cod_material; ?>
</span></td>
		<!-- descripcion -->
		<td style="width: 60%;"><span>
<?php


echo $x_descripcion; ?>
</span></td>
		<!-- unidad -->
		<td style="width: 20%;"><span>
<?php echo $x_unidad; ?>
</span></td>
</tr>
<?php
	}
}
?>
</form>
</table>
<?php 
}

// Close recordset and connection
phpmkr_free_result($rs);
cerrar_conexion($conn);
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<form action="materiales_search.php" name="ewpagerform" id="ewpagerform">
	<tr>
		<td nowrap>
<?php
if ($nTotalRecs > 0) {
	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	$PrevStart = $nStartRec - $nDisplayRecs;
	if ($PrevStart < 1) { $PrevStart = 1; }
	$NextStart = $nStartRec + $nDisplayRecs;
	if ($NextStart > $nTotalRecs) { $NextStart = $nStartRec ; }
	$LastStart = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
	?>
<table width="100%" class="tb-head">
  <tr>
    <td>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker">Página&nbsp;</span></td>
<!--first page button-->
	<?php if ($nStartRec == 1) { ?>
	<td><img src="../images/firstdisab.gif" alt="Primera" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="materiales_search.php?start=1"><img src="../images/first.gif" alt="Primera" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><img src="../images/prevdisab.gif" alt="Anterior" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="materiales_search.php?start=<?php echo $PrevStart; ?>"><img src="../images/prev.gif" alt="Anterior" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><img src="../images/nextdisab.gif" alt="Siguiente" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="materiales_search.php?start=<?php echo $NextStart; ?>"><img src="../images/next.gif" alt="Siguiente" width="16" height="16" border="0"></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><img src="../images/lastdisab.gif" alt="Ultima" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="materiales_search.php?start=<?php echo $LastStart; ?>"><img src="../images/last.gif" alt="Ultima" width="16" height="16" border="0"></a></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;de <?php echo intval(($nTotalRecs-1)/$nDisplayRecs+1);?></span></td>
	</tr>
	</table>
	</td>
    <td>
	<?php if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	Registro <?php echo $nStartRec; ?> a <?php echo $nStopRec; ?> de <?php echo $nTotalRecs; ?>
	</td>
  </tr>
</table>
<?php } else { ?>
	<?php if ($sSrchWhere == "0=101") { ?>
	<span class="phpmaker"></span>
	<?php } else { ?>
	<span class="phpmaker">No se encontraron registros</span>
	<?php } ?>
<?php } ?>
		</td>
	</tr>
</form>
</table>
<?php include ("../footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`descripcion` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`unidad_salida` LIKE '%" . $sKeyword . "%' OR ";
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
	$_SESSION[ewSessionTblAdvSrch . "_x_cod_material"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_descripcion"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_unidad"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_parametro"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_codigo"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_codccos"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_existencia"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_minimo"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_maximo"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_ultimo_costo"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_fecha_ultimo_costo"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_cantidad_unidad"] = "";
	$_SESSION[ewSessionTblAdvSrch . "_x_unidad_salida"] = "";
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
	$GLOBALS["x_cod_material"] = @$_SESSION[ewSessionTblAdvSrch . "_x_cod_material"];
	$GLOBALS["x_descripcion"] = @$_SESSION[ewSessionTblAdvSrch . "_x_descripcion"];
	$GLOBALS["x_unidad"] = @$_SESSION[ewSessionTblAdvSrch . "_x_unidad"];
	$GLOBALS["x_parametro"] = @$_SESSION[ewSessionTblAdvSrch . "_x_parametro"];
	$GLOBALS["x_codigo"] = @$_SESSION[ewSessionTblAdvSrch . "_x_codigo"];
	$GLOBALS["x_codccos"] = @$_SESSION[ewSessionTblAdvSrch . "_x_codccos"];
	$GLOBALS["x_existencia"] = @$_SESSION[ewSessionTblAdvSrch . "_x_existencia"];
	$GLOBALS["x_minimo"] = @$_SESSION[ewSessionTblAdvSrch . "_x_minimo"];
	$GLOBALS["x_maximo"] = @$_SESSION[ewSessionTblAdvSrch . "_x_maximo"];
	$GLOBALS["x_ultimo_costo"] = @$_SESSION[ewSessionTblAdvSrch . "_x_ultimo_costo"];
	$GLOBALS["x_fecha_ultimo_costo"] = @$_SESSION[ewSessionTblAdvSrch . "_x_fecha_ultimo_costo"];
	$GLOBALS["x_cantidad_unidad"] = @$_SESSION[ewSessionTblAdvSrch . "_x_cantidad_unidad"];
	$GLOBALS["x_unidad_salida"] = @$_SESSION[ewSessionTblAdvSrch . "_x_unidad_salida"];
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

		// Field `cod_material`
		if ($sOrder == "cod_material") {
			$sSortField = "`cod_material`";
			$sLastSort = @$_SESSION[ewSessionTblSort . "_x_cod_material"];
			$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			$_SESSION[ewSessionTblSort . "_x_cod_material"] = $sThisSort;
		} else {
			if (@$_SESSION[ewSessionTblSort . "_x_cod_material"] <> "") { @$_SESSION[ewSessionTblSort . "_x_cod_material"] = ""; }
		}

		// Field `descripcion`
		if ($sOrder == "descripcion") {
			$sSortField = "`descripcion`";
			$sLastSort = @$_SESSION[ewSessionTblSort . "_x_descripcion"];
			$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			$_SESSION[ewSessionTblSort . "_x_descripcion"] = $sThisSort;
		} else {
			if (@$_SESSION[ewSessionTblSort . "_x_descripcion"] <> "") { @$_SESSION[ewSessionTblSort . "_x_descripcion"] = ""; }
		}

		// Field `unidad`
		if ($sOrder == "unidad") {
			$sSortField = "`unidad`";
			$sLastSort = @$_SESSION[ewSessionTblSort . "_x_unidad"];
			$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			$_SESSION[ewSessionTblSort . "_x_unidad"] = $sThisSort;
		} else {
			if (@$_SESSION[ewSessionTblSort . "_x_unidad"] <> "") { @$_SESSION[ewSessionTblSort . "_x_unidad"] = ""; }
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
			if (@$_SESSION[ewSessionTblSort . "_x_cod_material"] <> "") { $_SESSION[ewSessionTblSort . "_x_cod_material"] = ""; }
			if (@$_SESSION[ewSessionTblSort . "_x_descripcion"] <> "") { $_SESSION[ewSessionTblSort . "_x_descripcion"] = ""; }
			if (@$_SESSION[ewSessionTblSort . "_x_unidad"] <> "") { $_SESSION[ewSessionTblSort . "_x_unidad"] = ""; }
		}

		// Reset start position (Reset command)
		$nStartRec = 1;
		$_SESSION[ewSessionTblStartRec] = $nStartRec;
	}
}
?>

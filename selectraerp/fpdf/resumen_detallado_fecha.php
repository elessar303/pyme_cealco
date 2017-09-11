<?php
	require_once '../lib/config.php';
	require_once '../lib/common.php';
	include('../header.php');
	$conexion = conexion();
	$consulta1 = "SELECT * FROM cwsector where RecNo <>99 ORDER BY RecNo";
	$resultado1 = query($consulta1, $conexion);
	

?>

<FORM name="sampleform" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<TABLE width="100%">
		<TR class="row-br">
			<TD colspan="2"><?titulo("Resumen Detallado","","reportes.php?cod=70","70");?></TD>
		</TR>
		
		
		
		<TR>
			<TD class="tb-fila"> Fecha Hasta: </TD>
			<TD>  <INPUT type="text" name="fechaH" id="fechaH" size="15" maxlength="12" value="<?echo date("d/m/Y")?>">&nbsp;<input name="h_fecha" type="image" id="h_fecha" src="../lib/jscalendar/cal.gif">
			<script type="text/javascript">Calendar.setup({inputField:"fechaH",ifFormat:"%d/%m/%Y",button:"h_fecha"});</script></TD>
		</TR>
		
		<TR class="tb-tit">
			<TD colspan="2" align="right"><INPUT type="submit" name="enviar" value="Aceptar"></TD>
		</TR>
	</TABLE>
</FORM>
</BODY>
</HTML>
<?
if(isset($_POST['enviar']))
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		location.href=\"../fpdf/resumen_detallado.php?fechaHasta=".$_POST['fechaH']."\"</SCRIPT>";
	}
?>

<?
	require_once '../lib/config.php';
	require_once '../lib/common.php';
	require_once '../lib/pdfcommon.php';
	include("../header.php");
	$conexion = conexion();
	$consulta = "SELECT * FROM bancos";
	$resultado = query($consulta, $conexion);
	
	if(isset($_POST['enviar']))
	{
		$fD = explode('/',$_POST['fechaD']);
		$fechaD = $fD[2]."-".$fD[1]."-".$fD[0];
		$fH = explode('/',$_POST['fechaH']);
		$fechaH = $fH[2]."-".$fH[1]."-".$fH[0];

		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		location.href=\"../fpdf/reportes_pro_printpdf.php?tipo=".$_POST['tipo']."\"</SCRIPT>";
		
	}
?>

<FORM name="sampleform" method="POST" target="_blank" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<TABLE width="100%" border="">
		<TR class="row-br">
			<TD><?titulo("Seleccione Datos del Reporte","","../menu_int.php?cod=2","70");?></TD>
		</TR>
	</TABLE>
	<BR>
	<table width="50%"  border="1" align="center">
		<tr>
                    <th class="tb-head" colspan="3" scope="col">Reporte de Proveedores</th>
                </tr>
                <tr>
                    <th class="tb-fila" colspan="3" scope="col">Ordenar por: </th>
                </tr>
                <tr>
                    <th class="tb-head" width="56" scope="col">C&oacute;digo</th>
                    <th class="tb-head" width="74" scope="col">Nombre</th>
                    <th class="tb-head" width="48" scope="col">Tipo</th>
                </tr>
		<tr align="center">
			<? iconoNuevopdf("reportes_pro_printpdf.php?buscar=cod_proveedor", "Imprimir","ico_print.gif"); 
			iconoNuevopdf("reportes_pro_printpdf.php?buscar=compania", "Imprimir","ico_print.gif");  iconoNuevopdf("reportes_pro_printpdf.php?buscar=tipo_compania", "Imprimir","ico_print.gif"); ?></TD>
		</tr>
	</table>
	<BR>
	<TABLE width="50%" border="1" align="center">
		<tr>
                    <th class="tb-head" colspan="3" scope="col">Reporte de Proveedores por Tipo</th>
                </tr>
		<TR>
			<TD class="tb-fila">Tipo de proveedor: </TD>
			<TD colspan="2"><SELECT name="tipo" id="tipo">
					<option value="0">Seleccione..</option>
					<option value="P">Proveedor</option>
					<option value="C">Contratista</option>
					<option value="I">Cooperativa</option>
					<option value="F">Fundación</option>
					<option value="O">Otros</option>
			?>
			</SELECT></TD>
		<TR>
			<TD colspan="3" height="10"></TD>
		</TR>
		<TR class="tb-tit">
			<TD colspan="3" align="right"><INPUT type="submit" name="enviar" value="Aceptar"></TD>
		</TR>
		</TR>
		
	</TABLE>
</FORM>
</BODY>
</HTML>
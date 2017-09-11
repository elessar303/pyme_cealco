<?
	require_once '../lib/config.php';
	require_once '../lib/common.php';
	include ("../header.php");
	$conexion=conexion();
	$codigo = @$_GET['codigo'];
	$consulta = "SELECT * FROM proveedores WHERE cod_proveedor='".$codigo."'";
	$resultado = query($consulta, $conexion);
	$fila = fetch_array($resultado);
	$tipo = $fila["tipo_compania"];
	$nombre = $fila["compania"];
	$razon = $fila["siglas"];

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
<TABLE width="800" border="0" cellpadding="0" cellspacing="0" align="center">
	<TR>
		<TD align="center"><H3>*** CONSTANCIA DE INSCRIPCI&Oacute;N ***</H3></TD>
	</TR>
</TABLE>
<TABLE align="center" width="800" border="0">
	<TR>
		<TD colspan="4" align="right" height="40"><?echo "Maracaibo, ".date('d/m/Y')."";?></TD>
	</TR>
	<TR style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
		<TD align="left" width="80" height="40"><strong>C&oacute;digo:</strong></TD>
		<TD align="left" width="80"><?echo $codigo?></TD>
		<TD align="left" width="80" height="40"><strong>Tipo:</strong></TD>
		<TD align="left"><?echo $tipo?></TD>
	</TR>
	<TR style="border-left-style : outset; border-right-style : outset; border-top-style : outset;">
		<TD colspan="4" width="80" align="left" height="40"><strong>Nombre:</strong> <?echo $nombre?></TD>
	</TR>
	<TR style="border-left-style: outset; border-right-style: outset;">
		<TD colspan="4" height="40"><strong>Raz&oacute;n:</strong> <?echo $razon?></TD>
	</TR>
	<TR style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
		<TD colspan="4" align="center" height="50">V&aacute;lido Hasta: </TD>
	</TR>
	<TR>
		<TD colspan="4" align="center" height="60" valign="bottom">_________________________</TD>
	</TR>
	<TR>
		<TD colspan="4" align="center" height="30">Contralor</TD>
	</TR>
</TABLE>
</div>
</BODY>
</HTML>
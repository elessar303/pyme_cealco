<?php
	require_once '../lib/config.php';
	require_once '../lib/common.php';
	include('../header.php');
//	$conexion = conexion();
//	$consulta1 = "SELECT * FROM cwsector ORDER BY RecNo";
//	$resultado1 = query($consulta1, $conexion);
	if(isset($_POST['enviar']))
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		location.href=\"listado_snc_xls.php?periodo=".$_POST['radio']."\"</SCRIPT>";
	}
?>

<FORM name="seleccion" method="POST" target="_blank" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<TABLE width="100%">
		<TR class="row-br">
			<TD colspan="2"><? titulo("Seleccione Periodo del Listado del SNC","","menu_int.php?cod=2","70");?></TD>
		</TR>
		<TR>
			<TD width="150" class="tb-fila">Periodo:</TD>
			<TD><label>
			  <input type="radio" name="radio" id="radio" value="1" />1er. Trimestre
			</label>
              <label> <br />
              <input type="radio" name="radio" id="radio" value="2" />2do. Trimestre
			</label>
              <label> <br />
              <input type="radio" name="radio" id="radio" value="3" />3er. Trimestre
			</label>
              <label> <br />
              <input type="radio" name="radio" id="radio" value="4" />4to. Trimestre
			</label></TD>
	  </TR>

		<TR class="tb-tit">
			<TD colspan="2" align="right"><INPUT type="submit" name="enviar" value="Aceptar"></TD>
		</TR>
	</TABLE>
</FORM>
</BODY>
</HTML>
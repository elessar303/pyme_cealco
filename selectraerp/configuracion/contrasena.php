<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion_conf();
//echo $conexion;

$cod_proveedores=@$_GET['codigo'];
$url="contrasena";
$modulo="Cambio de Contraseña";
$tabla="usuarios";


if(isset($_POST['aceptar'])){
	
	

	if($_POST['codigo']!=$_POST['verificar_codigo']){
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Las contraseñas introducidas no coinciden!\")
		</SCRIPT>";
	}else{
		$consulta="select * from ".$tabla." where LOG_USR='$_POST[nombre]'";
		$resultado= query($consulta,$conexion);
		if(num_rows($resultado)==0){
			echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
			alert(\"El usuario no Existe!\")
			</SCRIPT>";
		}else{
			$consulta="update ".$tabla." set PAS_USR='$_POST[codigo]' where LOG_USR='$_POST[nombre]'";
			$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
			echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
				alert(\"Se ha cambiado su contarseña! Debe Cerrar su Sesión\")
				
				window.close()
				
				
			</SCRIPT>";
	
		}

	}

	
	//echo $consulta;
//exit(0);
	
	
	//cerrar_conexion($conexion);
	
	

}
	
?>

<html class="fondo">

<head>
  <title></title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
  </SCRIPT>
<script language="javascript" src="cal2.js"></script>
<script language="javascript" src="cal_conf2.js"></script>
<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	//parent.cont.location.href=retorno+".php?pagina=1"
	window.close()
}
function contra(){
	if(this.document.forms.sampleform.codigo.value==this.document.forms.sampleform.verificar_codigo.value){
		this.document.forms.sampleform.codigo.value;
	}else{
		alert("Las contraseñas introducidas no coinciden");
		this.document.forms.sampleform.codigo.value="";
		this.document.forms.sampleform.verificar_codigo.value="";
		this.document.forms.sampleform.codigo.focus();
	}
}

</SCRIPT>

</head>
<body>


<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<TABLE  width="100%" height="100">
<TBODY>
<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Cambio de contraseña</strong></td>

    </tr>
<TR><td class=tb-head >Usuario</td><td><INPUT type="text" name="nombre" size="20"  readonly="true" value="<?= $_SESSION['nombre'] ?>"></td> </tr>
	</tr>
<TR><td class=tb-head >Nueva Contraseña</td><td><INPUT  type="password" name="codigo" size="20"  value="<?echo $codigo?>"></td> </tr>
	</tr>
<TR><td class=tb-head >Confirmar Contraseña</td><td><INPUT  type="password" name="verificar_codigo" size="20" onBlur="javascript:contra()" value="<?echo $codigo?>"></td> </tr>
    </td><tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:cerrar('<?echo $url?>');"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);
?>
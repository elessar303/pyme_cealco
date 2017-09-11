<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");

require_once("../../libs/php/clases/ConexionComun.php");

require_once("../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");

$pymeC="selectrapyme_central";
$comunes = new Producto();
$conex1=$conn_pyme = new ConexionComun();
$punto=$_GET['punto'];
$sql_tipo="SELECT * FROM $pymeC.puntos_tipo";
$sql="SELECT * FROM $pymeC.puntos_venta WHERE id=".$punto." order by estatus";
$query=$comunes->ObtenerFilasBySqlSelect($sql);
foreach ($query as $key => $value) {

?>
<script language="JavaScript"> 
<!-- 
var nav4 = window.Event ? true : false; 
function acceptNum(evt){  
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57  
var key = nav4 ? evt.which : evt.keyCode;  
return (key <= 13 || (key >= 48 && key <= 57 || key==46)); 
} 
//--> 
</script>

<table style="width:100%; background-color:white;font-size: 13px; font-family:TheSansCorrespondence;" cellpadding="1" cellspacing="1" class="seleccionLista" border="0">
<form name="formulario" id="formulario" method="post">
            <thead>
            <tr>
                <th colspan="6" class="tb-head" style="text-align:center;">
                LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
            	</th>
            </tr>
            </thead>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Tipo de Punto</font></td>
                <td align="center"><font color="white">Punto de Venta</font></td>
                <td align="center"><font color="white">IP</font></td>
                <td align="center"><font color="white">Sincronizaci&oacute;n</font></td>
                <td align="center"><font color="white">Status</font></td>
                <td align="center"><font color="white">Acci&oacute;n</font></td>
            </tr>
            <tr >
                <td>
                <select name="tipo_punto" id="tipo_punto" title="Sincronizacion" required="required" >
                <?php $query_tipo=$comunes->ObtenerFilasBySqlSelect($sql_tipo);
                foreach ($query_tipo as $key_tipo => $value_tipo) { ?>
                <option value="<?php echo $value_tipo['id_tipo'] ?>" <?php if($value['id_tipo']==$value_tipo['id_tipo']){echo "selected";}?>><?php echo $value_tipo['descripcion_tipo'] ?></option>
                <?php } ?>
                </td>
                <td align="center"><?php echo $value['nombre_punto']?></td>
                <td align="center"><input type="text" value="<?php echo $value['ip_punto_venta']?>" name="ip_punto_venta" onKeyPress="return acceptNum(event)" disabled="disabled"></td>
                <td align="center">
                <select name="tipo_sincro" id="tipo_sincro" title="Sincronizacion" required="required" disabled="disabled">
                <option value="1" <?php if($value['tipo_sincro']=='1'){echo "selected";}?>>Autom&aacute;tico</option>
				<option value="2" <?php if($value['tipo_sincro']=='2'){echo "selected";}?>>Manual</option>
                </select>
                </td>
                <td align="center">
                <select name="estatus" id="estatus" title="Sincronizacion" required="required" disabled="disabled">
                <option value="A" <?php if($value['estatus']=='A'){echo "selected";}?>>Activo</option>
				<option value="I" <?php if($value['estatus']=='I'){echo "selected";}?>>Inactivo</option>
                </select>
                </td>
                <td align="center">
                <input type="submit" id="guardar" name="guardar" value="Guardar"/>
                </td>

            </tr>
</form>
</table>

<?php
}
if (isset($_POST['guardar'])){

$tipo_punto=$_POST['tipo_punto'];

$sql="UPDATE $pymeC.puntos_venta set id_tipo=".$_POST['tipo_punto']." where id=".$punto."";
$rs_ins=$conn_pyme->Execute2($sql);
?>
<script type="text/javascript">
    window.opener.location.reload(true)
    window.close();
</script>
<?php
}
?>
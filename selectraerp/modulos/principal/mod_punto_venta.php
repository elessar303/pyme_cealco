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
/*$sql="SELECT * FROM $pymeC.puntos_venta WHERE id=".$punto." order by estatus";
$query=$comunes->ObtenerFilasBySqlSelect($sql);*/

///Seccion nueva
$sql_estado="SELECT * FROM $pymeC.estados";
//$query=$comunes->ObtenerFilasBySqlSelect($sql);

$sql_servidor="SELECT * FROM $pymeC.tipo_servidor";
//$query3=$comunes->ObtenerFilasBySqlSelect($sql);

$sql_conexion="SELECT * FROM $pymeC.tipo_conexion";
//$query4=$comunes->ObtenerFilasBySqlSelect($sql);

$sql_velocidad="SELECT * FROM $pymeC.velocidad_conexion";
//$query5=$comunes->ObtenerFilasBySqlSelect($sql);

$sql_proveedor="SELECT * FROM $pymeC.proveedor_conexion";
//$query6=$comunes->ObtenerFilasBySqlSelect($sql);
///Fin de la seccion nueva

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

<div style="overflow:auto; height:100%;">
<table align="center" style="width:80%; background-color:white;font-size: 13px; font-family:TheSansCorrespondence;" cellpadding="1" cellspacing="1" class="seleccionLista" border="0">
<form name="formulario" id="formulario" method="post">
            <thead>
            <tr>
                <th colspan="6" class="tb-head" style="text-align:center;">
                LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
            	</th>
            </tr>
            </thead>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Estado</font></td>
                <td align="center"><font color="white">Tipo de Punto</font></td>
            </tr>
            <tr>
                <td align="center"> 
                    <select name="estado" id="estado" style="width:200px;" required="required">
                    <?php foreach ($query as $fila) {?>
                    <option value="<?php echo $fila['codigo_estado'];?>"><?php echo $fila['nombre_estado'];?></option>
                    <?php }?>
                        <?php $query7=$comunes->ObtenerFilasBySqlSelect($sql_estado);
                        foreach ($query7 as $fila7 => $estado) {?>
                        <option value="<?php echo $estado['codigo_estado'] ?>" <?php if($value['codigo_estado_punto']==$estado['codigo_estado']){echo "selected";}?>><?php echo $estado['nombre_estado'] ?></option>
                        <?php }?>
                    </select>
                </td>
                <td align="center">
                    <select name="tipo_punto" id="tipo_punto" title="Sincronizacion" required="required">
                        <?php $query_tipo=$comunes->ObtenerFilasBySqlSelect($sql_tipo);
                        foreach ($query_tipo as $key_tipo => $value_tipo) { ?>
                        <option value="<?php echo $value_tipo['id_tipo'] ?>" <?php if($value['id_tipo']==$value_tipo['id_tipo']){echo "selected";}?>><?php echo $value_tipo['descripcion_tipo'] ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Punto de Venta</font></td>
                <td align="center"><font color="white">Direcci&oacute;n</font></td>
            </tr>
            <tr>
                <td align="center"><input size="40" type="text" value="<?php echo $value['nombre_punto']?>" name="nombre_punto"></td>
                <td align="center"><input size="40" type="text" value="<?php echo $value['direccion_punto']?>" name="direccion_punto"></td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Capacidad SECO</font></td>
                <td align="center"><font color="white">Capacidad FRIO</font></td>
            </tr>
            <tr>
                <td align="center"><input type="text" value="<?php echo $value['capacidad_seco']?>" name="capacidad_seco" onKeyPress="return acceptNum(event)"></td>
                <td align="center"><input type="text" value="<?php echo $value['capacidad_frio']?>" name="capacidad_frio" onKeyPress="return acceptNum(event)"></td>
                
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">IP</font></td>
                <td align="center"><font color="white">Codigo Siga</font></td>
            </tr>
            <tr>
                <td align="center"><input type="text" value="<?php echo $value['ip_punto_venta']?>" name="ip_punto_venta" onKeyPress="return acceptNum(event)"></td>
                <td align="center"><input type="text" value="<?php echo $value['codigo_siga_punto']?>" name="codigo_siga" onKeyPress="return acceptNum(event)"></td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Nro de Cajas</font></td>
                <td align="center"><font color="white">Cantidad de Servidores</font></td>
            </tr>
            <tr>
                <td align="center"><input type="text" value="<?php echo $value['numero_cajas']?>" name="numero_cajas"></td>
                <td align="center"><input type="text" value="<?php echo $value['numero_servidores']?>" name="numero_servidores"></td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Tipo de Servidor</font></td>
                <td align="center"><font color="white">Tipo de Conexi&oacute;n</font></td>
            </tr>
            <tr>
                <td align="center">
                    <select name="tipo_servidor" id="tipo_servidor" title="Sincronizacion" required="required">
                        <?php $query3=$comunes->ObtenerFilasBySqlSelect($sql_servidor);
                        foreach ($query3 as $fila3 => $nombre_server) {?>
                        <option value="<?php echo $nombre_server['id_tipo_servidor'] ?>" <?php if($value['tipo_servidor']==$nombre_server['id_tipo_servidor']){echo "selected";}?>><?php echo $nombre_server['nombre_servidor'] ?></option>
                        <?php }?>
                    </select>
                </td>
                <td align="center">
                    <select name="tipo_conexion" id="tipo_conexion" title="Sincronizacion" required="required">
                        <?php $query4=$comunes->ObtenerFilasBySqlSelect($sql_conexion);
                        foreach ($query4 as $fila4 => $tipo_conexion) {?>
                        <option value="<?php echo $tipo_conexion['id_tipo_conexion'] ?>" <?php if($value['tipo_conexion']==$tipo_conexion['id_tipo_conexion']){echo "selected";}?>><?php echo $tipo_conexion['nombre_conexion'] ?></option>
                        <?php }?>
                    </select>
                </td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Velocidad de Conexi&oacute;n</font></td>
                <td align="center"><font color="white">Proveedor</font></td>
            </tr>
            <tr>
                <td align="center">
                    <select name="velocidad_conexion" id="velocidad_conexion" title="Sincronizacion" required="required">
                        <?php $query5=$comunes->ObtenerFilasBySqlSelect($sql_velocidad);
                        foreach ($query5 as $fila5 => $velocidad_conexion) {?>
                        <option value="<?php echo $velocidad_conexion['id_velocidad'] ?>" <?php if($value['velocidad_conexion']==$velocidad_conexion['id_velocidad']){echo "selected";}?>><?php echo $velocidad_conexion['nombre_velocidad'] ?></option>
                        <?php }?>
                    </select>
                </td>
                <td align="center">
                    <select name="proveedor_conexion" id="proveedor_conexion" title="Sincronizacion" required="required">
                        <?php $query6=$comunes->ObtenerFilasBySqlSelect($sql_proveedor);
                        foreach ($query6 as $fila6 => $proveedor_conexion) {?>
                        <option value="<?php echo $proveedor_conexion['id_proveedor_conexion'] ?>" <?php if($value['proveedor_conexion']==$proveedor_conexion['id_proveedor_conexion']){echo "selected";}?>><?php echo $proveedor_conexion['nombre_proveedor'] ?></option>
                        <?php }?>
                    </select>
                </td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Sincronizaci&oacute;n</font></td>
                <td align="center"><font color="white">Status</font></td>
            </tr>
            <tr>
                <td align="center">
                    <select name="tipo_sincro" id="tipo_sincro" title="Sincronizacion" required="required">
                        <option value="1" <?php if($value['tipo_sincro']=='1'){echo "selected";}?>>Autom&aacute;tico</option>
                        <option value="2" <?php if($value['tipo_sincro']=='2'){echo "selected";}?>>Manual</option>
                    </select>
                </td>
                <td align="center">
                    <select name="estatus" id="estatus" title="Sincronizacion" required="required">
                        <option value="A" <?php if($value['estatus']=='A'){echo "selected";}?>>Activo</option>
        				<option value="I" <?php if($value['estatus']=='I'){echo "selected";}?>>Inactivo</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="submit" id="guardar" name="guardar" value="Guardar"/>
                </td>
            </tr>
</form>
</table>
</div>

<?php
}
if (isset($_POST['guardar'])){

$estado=$_POST['estado'];
$codigo_siga=$_POST['codigo_siga'];
$nombre_punto=$_POST['nombre_punto'];
$capacidad_seco=$_POST['capacidad_seco'];
$capacidad_frio=$_POST['capacidad_frio'];
$ip_punto=$_POST['ip_punto'];
$tipo_sincro=$_POST['tipo_sincro'];
$estatus=$_POST['estatus'];
$numero_cajas=$_POST['numero_cajas'];
$numero_servidores=$_POST['numero_servidores'];
$tipo_servidor=$_POST['tipo_servidor'];
$tipo_conexion=$_POST['tipo_conexion'];
$velocidad_conexion=$_POST['velocidad_conexion'];
$proveedor_conexion=$_POST['proveedor_conexion'];


$sql="UPDATE $pymeC.puntos_venta set codigo_siga_punto ='".$codigo_siga."' , codigo_estado_punto ='".$estado."', tipo_sincro=".$_POST['tipo_sincro'].",
estatus='".$_POST['estatus']."', ip_punto_venta='".$_POST['ip_punto_venta']."', capacidad_seco=".$_POST['capacidad_seco'].", capacidad_frio=".$_POST['capacidad_frio'].", id_tipo=".$_POST['tipo_punto'].",
nombre_punto='".$_POST['nombre_punto']."', numero_cajas = '".$numero_cajas."', numero_servidores = '".$numero_servidores."',
tipo_servidor = '".$tipo_servidor."', tipo_conexion = '".$tipo_conexion."', velocidad_conexion = '".$velocidad_conexion."',
proveedor_conexion = '".$proveedor_conexion."'
where id=".$punto."";
$rs_ins=$conn_pyme->Execute2($sql);
?>
<script type="text/javascript">
    window.opener.location.reload(true)
    window.close();
</script>
<?php
}
?>
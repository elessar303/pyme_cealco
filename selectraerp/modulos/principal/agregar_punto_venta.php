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

$sql="SELECT * FROM $pymeC.estados";
$query=$comunes->ObtenerFilasBySqlSelect($sql);

$sql="SELECT * FROM $pymeC.puntos_tipo";
$query2=$comunes->ObtenerFilasBySqlSelect($sql);

$sql="SELECT * FROM $pymeC.tipo_servidor";
$query3=$comunes->ObtenerFilasBySqlSelect($sql);

$sql="SELECT * FROM $pymeC.tipo_conexion";
$query4=$comunes->ObtenerFilasBySqlSelect($sql);

$sql="SELECT * FROM $pymeC.velocidad_conexion";
$query5=$comunes->ObtenerFilasBySqlSelect($sql);

$sql="SELECT * FROM $pymeC.proveedor_conexion";
$query6=$comunes->ObtenerFilasBySqlSelect($sql);

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
<table style="width:80%; background-color:white;font-size: 13px; font-family:TheSansCorrespondence;" cellpadding="1" cellspacing="1" class="seleccionLista" border="0" align="center">
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
                <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='center'> 
                    <select name="estado" id="estado" style="width:200px;">
                    <?php foreach ($query as $fila) {?>
                    <option value="<?php echo $fila['codigo_estado'];?>"><?php echo $fila['nombre_estado'];?></option>
                    <?php }?>
                    </select>
                </td>
                <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='center'> 
                    <select name="tipo_punto" id="tipo_punto" style="width:200px;">
                    <?php foreach ($query2 as $fila2) {?>
                    <option value="<?php echo $fila2['id_tipo'];?>"><?php echo $fila2['descripcion_tipo'];?></option>
                    <?php }?>
                    </select>
                </td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">C&oacute;digo SIGA</font></td>
                <td align="center"><font color="white">Punto de Venta</font></td>
            </tr>
            <tr>
                <td align="center"><input type="text" name="codigo_siga"></td>
                <td align="center"><input type="text" name="nombre_punto" size="40"></td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">IP</font></td>
                <td align="center"><font color="white">Direcci&oacute;n</font></td>
            </tr>
            <tr>
                <td align="center">
                <input type="text" name="ip_punto_venta" onKeyPress="return acceptNum(event)">
                </td>
                <td align="center"><input type="text" name="direccion_punto" size="40"></td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Capacidad SECO</font></td>
                <td align="center"><font color="white">Capacidad FRIO</font></td>
            </tr>
            <tr>
                <td align="center"><input type="text" name="capacidad_seco" onKeyPress="return acceptNum(event)"></td>
                <td align="center"><input type="text" name="capacidad_frio" onKeyPress="return acceptNum(event)"></td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Nro de Cajas</font></td>
                <td align="center"><font color="white">Cantidad de Servidores</font></td>
            </tr>
            <tr>
            <td align="center"><input type="text" name="numero_cajas"></td>
            <td align="center"><input type="text" name="numero_servidores"></td>
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Tipo de Servidor</font></td>
                <td align="center"><font color="white">Tipo de Conexi&oacute;n</font></td>
            </tr>
            <tr>
            <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='center'>
                    <select name="tipo_servidor" id="tipo_servidor" style="width:200px;">
                        <?php foreach ($query3 as $fila3) {?>
                        <option value="<?php echo $fila3['id_tipo_servidor'];?>"><?php echo $fila3['nombre_servidor'];?></option>
                        <?php }?>
                    </select>
                </td>
                <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='center'>
                    <select name="tipo_conexion" id="tipo_conexion" style="width:200px;">
                        <?php foreach ($query4 as $fila4) {?>
                        <option value="<?php echo $fila4['id_tipo_conexion'];?>"><?php echo $fila4['nombre_conexion'];?></option>
                        <?php }?>
                    </select>
                </td>
                
            </tr>
            <tr class="tb-head" bgcolor="#5084A9">
                <td align="center"><font color="white">Velocidad de Conexi&oacute;n</font></td>
                <td align="center"><font color="white">Proveedor</font></td>
            </tr>
            <tr>
                <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='center'>
                    <select name="velocidad_conexion" id="velocidad_conexion" style="width:200px;">
                        <?php foreach ($query5 as $fila5) {?>
                        <option value="<?php echo $fila5['id_velocidad'];?>"><?php echo $fila5['nombre_velocidad'];?></option>
                        <?php }?>
                    </select>
                </td>
                <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='center'>
                    <select name="proveedor_conexion" id="proveedor_conexion" style="width:200px;">
                        <?php foreach ($query6 as $fila6) {?>
                        <option value="<?php echo $fila6['id_proveedor_conexion'];?>"><?php echo $fila6['nombre_proveedor'];?></option>
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
                <option value="1">Autom&aacute;tico</option>
                <option value="2">Manual</option>
                </select>
                </td>
                <td align="center">
                <select name="estatus" id="estatus" title="Sincronizacion" required="required">
                <option value="A">Activo</option>
                <option value="I">Inactivo</option>
                </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                <input type="submit" id="guardar" name="guardar" value="Guardar"/>
                </td>
            </tr>
</form>
</table>
</div>

<?php

if (isset($_POST['guardar'])){

    $estado=$_POST['estado'];
    $codigo_siga=$_POST['codigo_siga'];
    $nombre_punto=$_POST['nombre_punto'];
    $direccion_punto=$_POST['direccion_punto'];
    $ip_punto=$_POST['ip_punto_venta'];
    $tipo_sincro=$_POST['tipo_sincro'];
    $estatus=$_POST['estatus'];
    $tipo_punto=$_POST['tipo_punto'];
    $numero_cajas=$_POST['numero_cajas'];
    $numero_servidores=$_POST['numero_servidores'];
    $tipo_servidor=$_POST['tipo_servidor'];
    $tipo_conexion=$_POST['tipo_conexion'];
    $velocidad_conexion=$_POST['velocidad_conexion'];
    $proveedor_conexion=$_POST['proveedor_conexion'];

    $sql="SELECT id FROM $pymeC.puntos_venta WHERE codigo_siga_punto='$codigo_siga'";
    $num=mysql_num_rows(mysql_query($sql));
    if($num==0){
    $sql="INSERT INTO $pymeC.puntos_venta(codigo_siga_punto, nombre_punto, direccion_punto, codigo_estado_punto, tipo_sincro,
        estatus, ip_punto_venta, id_tipo, numero_cajas, numero_servidores, tipo_servidor, tipo_conexion, velocidad_conexion,
        proveedor_conexion) 
    VALUES ('$codigo_siga','$nombre_punto','$direccion_punto','$estado',$tipo_sincro,'$estatus','$ip_punto','$tipo_punto',
        '$numero_cajas','$numero_servidores','$tipo_servidor','$tipo_conexion','$velocidad_conexion','$proveedor_conexion')";
    $rs_ins=$conn_pyme->Execute2($sql);

    $sql2="INSERT INTO $pymeC.reportes_ventas(codigo_siga, fecha, reporto) VALUES ('$codigo_siga','2015-09-01 00:00:00',0)";
    $rs_ins2=$conn_pyme->Execute2($sql2);
    $sql2="INSERT INTO $pymeC.reportes_inventario(codigo_siga, fecha, reporto) VALUES ('$codigo_siga','2015-09-01 00:00:00',0)";
    $rs_ins2=$conn_pyme->Execute2($sql2);
    ?>
    <script type="text/javascript">
        window.alert("Punto de Venta Agregado");
        window.opener.location.reload(true)
        window.close();
    </script>
    <?php
    }else{?>
        <script type="text/javascript">
        window.alert("Codigo SIGA ya se encuentra registrado");
        </script>
        <?php
    }
}//if Guardar
?>
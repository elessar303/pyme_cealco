<?php

session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/almacen.php");

$ubicacion=new Almacen();

$estado=$_POST['estado']; 
$producto=$_POST['producto'];
$fecha1=$_POST['fecha1'];
$fecha2=$_POST['fecha2']; 
$categoria=$_POST['categoria'];
$puntodeventa=$_POST['puntodeventa'];
$cedula_nac=$_POST['cedula_nac'];

$estado = str_replace('"','',$estado);
$producto = str_replace('"','',$producto);
$categoria = str_replace('"','',$categoria);
$puntodeventa = str_replace('"','',$puntodeventa);

$fecha1_format = str_replace('"','',$fecha1);
$fecha2_format = str_replace('"','',$fecha2);
$fecha_complete1='00:00:00';
$fecha_complete2='23:00:00';

if($estado!='00')
        {
        $search=$search." AND codigo_estado='".$estado."'";
        }
    if($puntodeventa!='')
        {
        $search=$search." AND codigo_siga_punto='".$puntodeventa."'";
        }
    
 if($producto!='')
        {
        $search=$search." AND nombre_producto like '%".$producto."%'";
        }
 if($cedula_nac!='')
        {
        $search=$search." AND taxid like '%".$cedula_nac."%'";
        }
if($categoria!='')
        {
        $search=$search." AND descripcion like '%".$categoria."%'";
        }
    
    $sql="SELECT nombre_producto, price, taxid, name_persona, codigo_siga, units, datenew_ticketlines, codigo_siga_punto, nombre_punto, codigo_estado_punto, codigo_estado, nombre_estado, descripcion  
    FROM selectrapyme_central.ventas left join selectrapyme_central.grupo on grupo.grupopos=ventas.category, selectrapyme_central.puntos_venta, selectrapyme_central.estados
    WHERE codigo_estado_punto=codigo_estado
    AND codigo_siga_punto=codigo_siga
    AND datenew_ticketlines BETWEEN ('".$fecha1_format." ".$fecha_complete1."') AND ('".$fecha2_format." ".$fecha_complete2."')
    ".$search." order by nombre_estado";

$query=$ubicacion->ObtenerFilasBySqlSelect($sql);

$titulo="Reporte Consolidado";
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");  
header ("Content-type: application/x-msexcel");  
header ("Content-Disposition: attachment; filename=\"".$titulo.".xls\"" );

$i=1;
?>
<html>
<table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
<tr bgcolor="#5084A9">
<td colspan="7"><b><font color="white">REPORTE CONSOLIDADO DE VENTAS</font></b></td>    
</tr>
<tr bgcolor="#5084A9">
    <td><b><font color="white">N&deg;</font></b></td>
    <td align="center"><b><font color="white">C&eacute;dula</font></b></td>
    <td align="center"><b><font color="white">Beneficiario</font></b></td>
    <td align="center"><b><font color="white">Producto</font></b></td>
    <td align="center"><b><font color="white">Precio</font></b></td>
    <td align="center"><b><font color="white">Cantidad</font></b></td>
    <td align="center"><b><font color="white" >Punto de Venta</font></b></td>
</tr>
<?php
$total_cantidad=0;
foreach ($query as $fila)
{
$total_cantidad=$total_cantidad+$fila["units"];
?>
<tr><td align="left"><?php echo $i;?></td>
    <td align="left"><?php echo $fila["taxid"];?></td> 
    <td align="center"><?php echo $fila["name_persona"];?></td>
    <td align="center"><?php echo $fila["nombre_producto"];?></td>
    <td align="center"><?php echo number_format($fila["price"], 2, '.', '');?></td>
    <td align="center"><?php echo $fila["units"];?></td>
    <td align="center"><?php echo $fila["nombre_punto"];?></td>
</tr>
    
<?php
$i++;
}
?>
<tr bgcolor="#5084A9">
<td colspan="5"><b><font color="white">Total Unidades:</font></b></td>
<td><b><font color="white"><?php echo $total_cantidad;?></font></b></td>
</tr>
</table>
</html>
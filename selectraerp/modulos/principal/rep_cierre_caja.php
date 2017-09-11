<?php

include("../../../general.config.inc.php");
include("../../../generalp.config.inc.php");

$caja_host = $_POST['caja_host'];
$fecha_inic = $_POST['fecha_inic'];
$fecha_cierre = $_POST['fecha_cierre'];
/*$fecha2=$_POST['fecha2']; 
$categoria=$_POST['categoria'];
$puntodeventa=$_POST['puntodeventa'];
$cedula_nac=$_POST['cedula_nac'];*/

$caja_host = str_replace('"','',$caja_host);
$fecha_inic = str_replace('"','',$fecha_inic);
$fecha_cierre = str_replace('"','',$fecha_cierre);
/*$puntodeventa = str_replace('"','',$puntodeventa);

$fecha1_format = str_replace('"','',$fecha1);
$fecha2_format = str_replace('"','',$fecha2);
$fecha_complete1='00:00:00';
$fecha_complete2='23:00:00';*/

$bd=DB_SELECTRA_FAC;
$bd2=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;

        $conexion = mysql_connect($host, $user, $pass, $bd2)or die("Error en la conexion");
            echo "CONEXION CON EXITO <br>";

        $sql = "SELECT HOST,DATESTART,DATEEND FROM $bd2.CLOSEDCASH ORDER BY HOSTSEQUENCE DESC";
        //$consulta = mysql_query($conexion, "SELECT * FROM closedcash")
        $consulta = mysql_query($sql,$conexion)or die("Error al traer los datos de la tabla closedcash");
    
    
    $extraer = mysql_fetch_array($consulta);

$titulo="Reporte de Cajas";
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
<td colspan="7"><b><font color="white">REPORTE DE CIERRE DE CAJAS</font></b></td>    
</tr>
<tr bgcolor="#5084A9">
    <td><b><font color="white">N&deg;</font></b></td>
    <td align="center"><b><font color="white">Caja</font></b></td>
    <td align="center"><b><font color="white">Fecha de Apertura</font></b></td>
    <td align="center"><b><font color="white">Fecha de Cierre</font></b></td>
   <!-- <td align="center"><b><font color="white">Precio</font></b></td>
    <td align="center"><b><font color="white">Cantidad</font></b></td>
    <td align="center"><b><font color="white" >Punto de Venta</font></b></td> -->
</tr>
<?php
$total_cantidad=0;
foreach ($extraer as $fila)
{
$total_cantidad=$total_cantidad+$fila[0];
?>
<tr><td align="left"><?php echo $i;?></td>
    <td align="left"><?php echo $fila["HOST"];?></td> 
    <td align="center"><?php echo $fila["DATESTART"];?></td>
    <td align="center"><?php echo $fila["DATEEND"];?></td>
   <!-- <td align="center"><?php //echo number_format($fila["price"], 2, '.', '');?></td>
    <td align="center"><?php //echo $fila["units"];?></td>
    <td align="center"><?php //echo $fila["nombre_punto"];?></td> -->
</tr>
    
<?php
$i++;
}
?>

           <!-- <tr class="">
                <?php
                //{assign var="counter" value="1"}
                //{foreach $dato}?>
                <!--Colocamos luego del punto (.) el nombre de la columna que llamaremos en mayúscula-->
                <!--<td width="15%" align="center"><?php //echo $counter; ?></td>
                <td width="15%" align="center"><?php //echo $dato['HOST']; ?></td>
                <td width="15%" align="center"><?php //echo $dato['DATESTART']; ?></td>
                <td width="15%" align="center"><?php //echo $dato['DATEEND']; ?></td>
                <td width="15%" align="center"></td>
                <?php //{/foreach}
                //{assign var="counter" value=$counter++}
                ?>
            </tr> -->
            

<tr bgcolor="#5084A9">
<td colspan="5"><b><font color="white">Total Unidades:</font></b></td>
<td><b><font color="white"><?php echo $total_cantidad;?></font></b></td>
</tr>
</table>
</html>
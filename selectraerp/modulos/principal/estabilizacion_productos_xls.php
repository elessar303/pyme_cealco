<?php
require_once("../../libs/php/adodb5/adodb.inc.php");
include("../../../general.config.inc.php");
include("../../../generalp.config.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/configuracion/config.php");
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
require_once("../../libs/php/clases/login.php");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=compras_x_cliente.xls");
$comunes = new ConexionComun();

$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item a where a.codigo_barras not in (SELECT codigo_barras from productos_centrales)");
?>
<table>
<tr>
<td colspan="2" align="center"><b><?php echo utf8_decode("Productos Creados en el Punto de Venta que no estan en Sede Central")?></b></td>
</tr>
<tr>
<td>COD BARRAS</td>
<td>PRODUCTO</td>
</tr>
<?php    
foreach ($array_factura as $id => $reg) 
{
    echo "<tr><td>".utf8_decode($reg[codigo_barras])."</td><td>".utf8_decode($reg[descripcion1])."</td></tr>";
  
}
?>
</table>

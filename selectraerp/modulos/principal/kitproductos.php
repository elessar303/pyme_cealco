<?php


require_once("../../config.ini.php");
include("../../libs/php/clases/producto.php");
$productos = new Producto();
$campos_almacen = $productos->ObtenerFilasBySqlSelect("select * from productos_kit");

$i=1;
if ($_GET['codigo']!='')
{

$campos_kit = $productos->ObtenerFilasBySqlSelect("SELECT *,round(i.precio1 * p.cantidad,2) as sub_total FROM productos_kit p , item i where  p.id_item_hijo = i.id_item and p.id_item_padre= (select id_item from item where cod_item ='".$_GET['codigo']."' )");

$i=1;
if ($campos_kit){
foreach ($campos_kit as $campo) {
   

$cadenita.=
 '<tr> <td> <input id="item'.$i.'"  type="hidden" readonly name="item'.$i.'" size="0" style=" visibility:hidden" value="'.$campo['id_item_hijo'].'" > <input id="id'.$i.'" value="'.$campo['cod_item'].'"   style="text-transform:uppercase; " readonly name="id'.$i.'" size="5" onchange="buscarProducto(\'id'.$i.'\',\'nombre'.$i.'\',\'empaque'.$i.'\',\'cantidad'.$i.'\');" > </td> <td>  <input id="nombre'.$i.'"  readonly name="nombre'.$i.'" size="10" style=" " value="'.$campo['descripcion1'].'" > </td> <td>  <input id="empaque'.$i.'"  readonly name="empaque'.$i.'" size="3" style=" visibility:visible" value="'.$campo['unidad_empaque'].'"> </td>  <td>  <input id="cantidad'.$i.'" name="cantidad'.$i.'"  size="3" style="" onchange="calcularPrecio(this,\'precio'.$i.'\',\'sub_total'.$i.'\');" value="'.$campo['cantidad'].'" > </td> <td>  <input id="precio'.$i.'" readonly name="precio'.$i.'"  size="7" style=" " value="'.$campo['costo_actual'].'"> </td> <td>  <input readonly id="sub_total'.$i.'" name="sub_total'.$i.'"   size="7" style=" " value="'.$campo['sub_total'].'"> </td> <td> <div id="eliminar'.$i.'" style=" cursor:pointer"> <img src="../../imagenes/cerrar.png "  height="14px" onclick="eliminarProducto(\''.$i.'\');"></div> </td> </tr>';

$i++;


}

}

}
echo  $_GET['codigo']  ;



echo '<h1>Productos en el Kit<h1><table> '  ;

echo " <div id='cuadros'> <table style=''> ";
echo '<tr width=\'400px\'  ';
echo ' <td > </td>  <td style="text-align:right" colspan=4  > <div style="display:inline; position:relative"> <input id="busqueda"  style="color:gray;   weight:20px; width:200px; height:22px;" name="busqueda" size="4" onkeyup="buscarProductoLista(this.value);" >  <div id="cuadroBusqueda" style="width:200px;color:#ccddff; font-weight:bold; background:gray;  text-align:left; top:20px; position:absolute">Inserte palabras claves del producto a seleccionar</div> </div>   <input id="id0"  style="color:red;  font-weight:bold;  weight:20px; height:22px; text-transform:uppercase" name="id0" size="4" onchange="this.value=this.value.toUpperCase(); buscarProducto(\'id0\',\'nombre0\',\'empaque0\',\'cantidad0\');" >  </td> ';
echo '</tr> '; 
echo '<tr>';

echo " <table style='background:#dddddd; -moz-border-radius:20px;'> ";
echo ' <tr>  <td colspan="6"> <br>  <br> <br></td></tr>';
echo ' <tr> <td> ID </td> <td> Descripción </td> <td> Unidad </td> <td> Cant. </td> <td> Cos. Actual </td> <td> Sub Total</td></tr>';

echo $cadenita;
for ($i=$i; $i <=20;$i++)
{




echo '<tr>';
echo ' <td> <input id="item'.$i.'"  type="hidden" readonly name="item'.$i.'" size="0" style=" visibility:hidden"> <input id="id'.$i.'"  style="text-transform:uppercase; visibility:hidden" readonly name="id'.$i.'" size="5" onchange="buscarProducto(\'id'.$i.'\',\'nombre'.$i.'\',\'empaque'.$i.'\',\'cantidad'.$i.'\');" > </td> <td>  <input id="nombre'.$i.'"  readonly name="nombre'.$i.'" size="10" style=" visibility:hidden" > </td> <td>  <input id="empaque'.$i.'"  readonly name="empaque'.$i.'" size="3" style=" visibility:hidden"> </td>  <td>  <input id="cantidad'.$i.'" name="cantidad'.$i.'"  size="3" style=" visibility:hidden" onchange="calcularPrecio(this,\'precio'.$i.'\',\'sub_total'.$i.'\');" > </td> <td>  <input id="precio'.$i.'" readonly name="precio'.$i.'"  size="7" style=" visibility:hidden"> </td> <td>  <input readonly id="sub_total'.$i.'" name="sub_total'.$i.'"  size="7" style=" visibility:hidden"> </td> <td> <div id="eliminar'.$i.'" style="visibility:hidden;cursor:pointer"  > <img src="../../imagenes/cerrar.png "  height="14px" onclick="eliminarProducto(\''.$i.'\');" ></div> </td>';
echo '</tr> '; 



}

echo ' </table> Costo Total <input id="suma_total" size="10" readonly></div> '  ;


?>

<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
//////////almacen///////////
$almacen = new Proveedores();
$campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM almacen;");
foreach ($campos as $key => $item) 
{
    $arraySelectOption[] = $item["cod_almacen"];
    $arraySelectoutPut[] = $item["descripcion"];
}
//$smarty->assign("name_form", "reporte");
$smarty->assign("option_values_almacen", $arraySelectOption);
$smarty->assign("option_output_almacen", $arraySelectoutPut);
///////ubicacion///////////
$arraySelectOption="";
$arraySelectoutPut="";
$ubica= new Proveedores();
$campos=$ubica->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion where descripcion<>'PISO DE VENTA'");
foreach($campos as $key=>$item)
{
    $arraySelectOption[]=$item["id"];
    $arraySelectOutPut[]=$item["descripcion"];
}
$smarty->assign("option_values_ubicacion1", $arraySelectOption); 
$smarty->assign("option_values_ubicacion2", $arraySelectOutPut);


$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

$cliente = new Clientes();
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM clientes ORDER BY nombre");
foreach ($campos as $key => $item) {
    $arraySelectOption2[] = $item["id_cliente"];
    $arraySelectOutPut2[] = $item["nombre"]." - ".$item["rif"];
}
$smarty->assign("option_values_cliente", $arraySelectOption2);
$smarty->assign("option_output_cliente", $arraySelectOutPut2);

$campos = $ubica->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE cod_item_forma=1 ORDER BY codigo_barras");
foreach ($campos as $key => $item) {
    $arraySelectOption3[] = $item["id_item"];
    $arraySelectOutPut3[] = $item["codigo_barras"]." - ".$item["descripcion1"];
}
$smarty->assign("option_values_producto", $arraySelectOption3);
$smarty->assign("option_output_producto", $arraySelectOutPut3);

$fecha = new DateTime();
$fecha->modify('first day of this month');
$smarty->assign("firstday", $fecha->format('Y-m-d'));
$fecha->modify('last day of this month');
$smarty->assign("lastday", $fecha->format('Y-m-d'));
#$smarty->assign("fecha_mes_anio", $fecha->format('F Y'));

if(isset($_POST['aceptar'])){

    $almacen=$_POST['almacen_entrada'];
    $ubicacion=$_POST['ubicacion'];
    $cliente=$_POST['cliente'];
    $item=$_POST['item'];
    $desde=$_POST['fecha'];
    $hasta=$_POST['fecha2'];
    echo '
    <script type="text/javascript">
    window.open("../../fpdf/imprimir_materialpdf2.php?almacen='.$almacen.'&ubicacion='.$ubicacion.'&cliente='.$cliente.'&item='.$item.'&desde='.$desde.'&hasta='.$hasta.'");
    location.href="?opt_menu=7&opt_seccion=126";
    window.open("../../fpdf/imprimir_materialpdf3.php?almacen='.$almacen.'&ubicacion='.$ubicacion.'&cliente='.$cliente.'&item='.$item.'&desde='.$desde.'&hasta='.$hasta.'");
    location.href="?opt_menu=7&opt_seccion=126";
</script>';
}
?>

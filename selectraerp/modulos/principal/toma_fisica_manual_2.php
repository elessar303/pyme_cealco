<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");

$almacen = new Almacen();
$campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM almacen");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["cod_almacen"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("name_form", "reporte");
$smarty->assign("option_values_almacen", $arraySelectOption);
$smarty->assign("option_output_almacen", $arraySelectoutPut);

$arraySelectOption = "";
$arraySelectoutPut = "";
$provee = new Proveedores();
$campos = $provee->ObtenerFilasBySqlSelect("SELECT * FROM proveedores");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_provee", $arraySelectOption);
$smarty->assign("option_output_provee", $arraySelectoutPut);

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = ".$_GET["opt_seccion"]."");
$smarty->assign("campo_seccion", $campos);

$arraySelectOption = "";
$arraySelectoutPut = "";
$producto = new Producto();
$campos = $producto->ObtenerFilasBySqlSelect("SELECT * FROM item");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_item"];
    $arraySelectOutPut[] = $item["descripcion1"];
}
$smarty->assign("option_values_producto", $arraySelectOption);
$smarty->assign("option_output_producto", $arraySelectOutPut);
///////////////////////////////////////////////////////////////

$arraySelectOption = "";
$arraySelectoutPut = "";
$campos = $producto->ObtenerFilasBySqlSelect("SELECT * FROM departamentos");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["cod_departamento"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_departamento", $arraySelectOption);
$smarty->assign("option_output_departamento", $arraySelectoutPut);
//////////////////////////////////////////////////////////////


//FILTRO PARA EL CODIGO SIGA
$filtro_siga_id="";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from ubicacion");
foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["descripcion"];
    $arrayubi[] = $item["id"];
    
}
$smarty->assign("option_values_nombre_ubi", $arrayubiN);
$smarty->assign("option_values_id_ubi", $arrayubi);
//FIN DEL FILTRO PARA EL CODIGO SIGA

$arraySelectOption = "";
$arraySelectoutPut = "";
$cliente = new Clientes();
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM clientes");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_cliente"];
    $arraySelectOutPut[] = $item["nombre"];
}
$smarty->assign("option_values_cliente", $arraySelectOption);
$smarty->assign("option_output_cliente", $arraySelectOutPut);

$fecha = new DateTime();
$fecha->modify('first day of this month');
$smarty->assign("firstday", $fecha->format('Y-m-d'));
$fecha->modify('last day of this month');
$smarty->assign("lastday", $fecha->format('Y-m-d'));
#$smarty->assign("fecha_mes_anio", $fecha->format('F Y'));
?>

<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
$bdCentral= "selectrapyme_central";
$almacen = new Almacen();
$campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM almacen;");
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
$campos = $provee->ObtenerFilasBySqlSelect("SELECT * FROM proveedores;");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_provee", $arraySelectOption);
$smarty->assign("option_output_provee", $arraySelectoutPut);

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
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
//FILTRO PARA EL CODIGO SIGA
//$bdpp = DB_SELECTRA_PYMEPP;
$bdpp=DB_REPORTE_CENTRAL;
$filtro_siga_id="";
$siga=new Almacen();
$cod_sig=$siga->ObtenerFilasBySqlSelect("select distinct siga from $bdpp.vproducto");
foreach ($cod_sig as $key => $item) {
    $arraycod_sigainput[] = $item["siga"];
    
}
$smarty->assign("option_values_siga", $arraycod_sigainput);
//FIN DEL FILTRO PARA EL CODIGO SIGA


// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";
$cliente = new Clientes();

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from $bdCentral.puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectOutPut1);

//estados
$arraySelectOption2 = "";
$arraySelectoutPut2 = "";
$cliente = new Clientes();
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM $bdCentral.estados");
foreach ($campos as $key => $item) {
    $arraySelectOption2[] = $item["codigo_estado"];
    $arraySelectOutPut2[] = $item["nombre_estado"];
}
$smarty->assign("option_values_estado", $arraySelectOption2);
$smarty->assign("option_output_estado", $arraySelectOutPut2);

//categoria
$arraySelectOption3 = "";
$arraySelectoutPut3 = "";
$cliente = new Clientes();
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM $bdCentral.grupo");
foreach ($campos as $key => $item) {
    $arraySelectOption3[] = $item["cod_grupo"];
    $arraySelectOutPut3[] = $item["descripcion"];
}
$smarty->assign("option_values_categoria", $arraySelectOption3);
$smarty->assign("option_output_categoria", $arraySelectOutPut3);

$fecha = new DateTime();
$fecha->modify('first day of this month');
$smarty->assign("firstday", $fecha->format('Y-m-d'));
$fecha->modify('last day of this month');
$smarty->assign("lastday", $fecha->format('Y-m-d'));
#$smarty->assign("fecha_mes_anio", $fecha->format('F Y'));
?>

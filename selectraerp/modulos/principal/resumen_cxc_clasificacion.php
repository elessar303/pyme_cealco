<?php
//tipo_proveedor_clasi
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
$almacen = new Almacen();
$proveedores = new Proveedores();	
$campos = $almacen->ObtenerFilasBySqlSelect("select * from almacen ");
foreach($campos as $key => $item){
    $arraySelectOption[] = $item["cod_almacen"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_almacen",$arraySelectOption);
$smarty->assign("option_output_almacen",$arraySelectoutPut);

$arraySelectOption="";
$arraySelectoutPut="";
$provee = new Proveedores();
$campos = $provee->ObtenerFilasBySqlSelect("select * from tipo_cliente");
foreach($campos as $key => $item){
    $arraySelectOption[] = $item["cod_tipo_cliente"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_provee",$arraySelectOption);
$smarty->assign("option_output_provee",$arraySelectoutPut);

//ESPECIALIDAD
$valueSELECT = "";
$outputSELECT =  "";
$tprecio  = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_cliente");
foreach($tprecio as $key => $item){
    $valueSELECT[] = $item["cod_tipo_cliente"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_especialidad",$valueSELECT);
$smarty->assign("option_output_especialidad",$outputSELECT);
?>

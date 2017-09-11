<?php
include("../../libs/php/clases/cxc.php");
include("../../../menu_sistemas/lib/common.php") ;

include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
$id_cliente=$_GET['id_cliente'];
$smarty->assign("id_cliente",$id_cliente);

$almacen = new Almacen();
$campos = $almacen->ObtenerFilasBySqlSelect("select * from almacen");
foreach($campos as $key => $item){
    $arraySelectOption[] = $item["cod_almacen"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_almacen",$arraySelectOption);
$smarty->assign("option_output_almacen",$arraySelectoutPut);

$arraySelectOption="";
$arraySelectoutPut="";
$provee = new Proveedores();
$campos = $provee->ObtenerFilasBySqlSelect("select * from proveedores ");
foreach($campos as $key => $item){
    $arraySelectOption[] = $item["id_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_provee",$arraySelectOption);
$smarty->assign("option_output_provee",$arraySelectoutPut);
$cxc = new cxc();
$total = $cxc->ObtenerFilasBySqlSelect("select * from clientes where id_cliente=".$id_cliente);
$smarty->assign("total",$total);


?>

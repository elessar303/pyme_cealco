<?php
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/producto.php");
$almacen = new Almacen();
$producto = new Producto();
if(isset($_POST["aceptar"])){



$instruccion = "delete from item_existencia_almacen 
	where cod_almacen = '".$_POST["cod_almacen"]."' and id_item = '".$_POST["cod_producto"]."'
";

$almacen->Execute2($instruccion);


header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=".$_POST["opt_subseccion"]."&cod=".$_POST["cod_almacen"]);
}

$rs_almacen = $almacen->ObtenerFilasBySqlSelect("
select almacen.*, vw_existenciabyalmacen.cantidad from vw_existenciabyalmacen inner join almacen on almacen.cod_almacen = vw_existenciabyalmacen.cod_almacen
 where vw_existenciabyalmacen.cod_almacen = ".$_GET["cod"]." and vw_existenciabyalmacen.id_item = ".$_GET["cod2"]);

$smarty->assign("rs_almacen",$rs_almacen);


$rs_productos = $producto->ObtenerFilasBySqlSelect("select * from item where cod_item_forma = 1 and id_item =".$_GET["cod2"]);

foreach($rs_productos as $key => $item){
    $arraySelectOption[] = $item["descripcion1"];
    $arraySelectoutPut[] = $item["id_item"];
}
$smarty->assign("option_values_producto",$arraySelectOption);
$smarty->assign("option_output_producto",$arraySelectoutPut);
$smarty->assign("option_selected_producto",$rs_productos[0]["cod2"] );

$smarty->assign("opt_subseccion","viewProductos");

?>

<?php
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/producto.php");
$almacen = new Almacen();
$producto = new Producto();
if(isset($_POST["aceptar"])){

$rs = $almacen->ObtenerFilasBySqlSelect("select * from vw_existenciabyalmacen 
	where cod_almacen = ".$_POST["cod_almacen"]." and id_item = ".$_POST["cod_producto"]."
	");


if(count($rs)!=""){

$cantidadNEW=0;
$cantidadOLD= $rs[0]["cantidad"];
$cantidadNEW = $cantidadOLD + $_POST["cantidad"];  

$instruccion = "update item_existencia_almacen set cantidad = '".$cantidadNEW."'
	where cod_almacen = '".$_POST["cod_almacen"]."' and id_item = '".$_POST["cod_producto"]."'
";
}else{ 	

$instruccion = "
		INSERT INTO `item_existencia_almacen` (
		`cod_item_existencia_almacen` ,
		`cod_almacen` ,
		`id_item` ,
		`cantidad`
		)
		VALUES (
		NULL , '".$_POST["cod_almacen"]."', '".$_POST["cod_producto"]."', '".$_POST["cantidad"]."'
		);
";

}

$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=".$_POST["opt_subseccion"]."&cod=".$_POST["cod_almacen"]);
}

$rs_almacen = $almacen->ObtenerFilasBySqlSelect("select * from almacen  where cod_almacen = ".$_GET["cod"]);

$smarty->assign("rs_almacen",$rs_almacen);
$rs_productos = $producto->ObtenerFilasBySqlSelect("select * from item where cod_item_forma = 1");

foreach($rs_productos as $key => $item){
    $arraySelectOption[] = $item["descripcion1"];
    $arraySelectoutPut[] = $item["id_item"];
}
$smarty->assign("option_values_producto",$arraySelectOption);
$smarty->assign("option_output_producto",$arraySelectoutPut);
$smarty->assign("option_selected_producto",$rs_productos[0]["cod_producto"] );

$smarty->assign("opt_subseccion","viewProductos");

?>

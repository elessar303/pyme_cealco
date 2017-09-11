<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");

$producto = new Producto();
if(isset($_POST["aceptar"])){
	$items=$_POST["id_item"];
	$coniva1=$_POST["coniva1"];
	$coniva2=$_POST["coniva2"];
	$coniva3=$_POST["coniva3"];
	$coniva4=$_POST["coniva4"];
	$coniva5=$_POST["coniva5"];
	$coniva6=$_POST["coniva6"];
	$i=0;
	$producto->BeginTrans();
	foreach ($items as $key => $item) {
   	 
   	$consulta="update item set coniva1='".$coniva1[$i]."', coniva2='".$coniva2[$i]."', coniva3='".$coniva3[$i]."', coniva4='".$coniva4[$i]."', coniva5='".$coniva5[$i]."', coniva6='".$coniva6[$i]."'   where id_item='".$item["id_item"]."'";
   	$producto->ExecuteTrans($consulta);
   	$i++;
	}

	if($producto->errorTransaccion==0)
	{
		Msg::setMessage("<span style=\"color:red;\">Error al tratar de realizar la transaccion.</span>");
	}
	else 
	{
		Msg::setMessage("<span style=\"color:green;\">Cambio de precio exitoso.</span>");
	}
	$producto->CommitTrans($producto->errorTransaccion);
	
	//header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
	//exit;
}


$arraySelectOption = "";
$arraySelectoutPut = "";
$producto = new Producto();
$campos = $producto->ObtenerFilasBySqlSelect("SELECT * FROM item order by id_item");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_item"];
    $arraySelectOutPut[] = $item["cod_item"]." -- ".$item["descripcion1"];
}
$smarty->assign("option_values_producto", $arraySelectOption);
$smarty->assign("option_output_producto", $arraySelectOutPut);

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

$fecha = new DateTime();
$fecha->modify('first day of this month');
$smarty->assign("firstday", $fecha->format('Y-m-d'));
$fecha->modify('last day of this month');
$smarty->assign("lastday", $fecha->format('Y-m-d'));
#$smarty->assign("fecha_mes_anio", $fecha->format('F Y'));
?>

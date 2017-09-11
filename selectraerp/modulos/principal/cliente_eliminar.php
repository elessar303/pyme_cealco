<?php
include("../../libs/php/clases/clientes.php");

$clientes = new Clientes();


if(isset($_POST["aceptar"])){ // si el usuario iso post

$instruccion = "
delete from clientes where cod_cliente = '".$_POST["cod_cliente"]."'";
$clientes->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}else{

if(!isset($_GET["cod"])){
    header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
}
$datacliente = $clientes->ObtenerFilasBySqlSelect("select * from clientes where id_cliente = ".$_GET["cod"]);

//CARGAMOS EL COMBO cod_vendedor
$valueSELECT = "";
$outputSELECT =  "";
$tprecio  = $clientes->ObtenerFilasBySqlSelect("select * from vendedor");
foreach($tprecio as $key => $item){
    $valueSELECT[] = $item["cod_vendedor"];
    $outputSELECT[] = $item["nombre"];
}
$smarty->assign("option_values_vendedor",$valueSELECT);
$smarty->assign("option_output_vendedor",$outputSELECT);
$smarty->assign("option_selected_vendedor",$datacliente[0]["cod_vendedor"]);

//CARGAMOS EL COMBO cod_zona
$valueSELECT = "";
$outputSELECT =  "";
$tprecio  = $clientes->ObtenerFilasBySqlSelect("select * from zonas");
foreach($tprecio as $key => $item){
    $valueSELECT[] = $item["cod_zona"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_zona",$valueSELECT);
$smarty->assign("option_output_zona",$outputSELECT);
$smarty->assign("option_selected_zona",$datacliente[0]["cod_zona"]);



//CARGAMOS EL COMBO COD_TIPO_CLIENTE
$valueSELECT = "";
$outputSELECT =  "";
$tcliente = $clientes->ObtenerFilasBySqlSelect("select * from tipo_cliente");
foreach($tcliente as $key => $item){
    $valueSELECT[] = $item["cod_tipo_cliente"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_cliente",$valueSELECT);
$smarty->assign("option_output_tipo_cliente",$outputSELECT);
$smarty->assign("option_selected_tipo_cliente",$datacliente[0]["cod_tipo_cliente"]);


//CARGAMOS EL COMBO COD_TIPO_PRECIO
$valueSELECT = "";
$outputSELECT =  "";
$tprecio  = $clientes->ObtenerFilasBySqlSelect("select * from tipo_precio");
foreach($tprecio as $key => $item){
    $valueSELECT[] = $item["cod_tipo_precio"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_precio",$valueSELECT);
$smarty->assign("option_output_tipo_precio",$outputSELECT);
$smarty->assign("option_selected_tipo_precio",$datacliente[0]["cod_tipo_precio"]);

//CARGAMOS EL COMBO contribuyente_especial
$smarty->assign("option_values_contribuyente_especial",array(0,1));
$smarty->assign("option_output_contribuyente_especial",array("No","Si"));
$smarty->assign("option_selected_contribuyente_especial",$datacliente[0]["contribuyente_especial"]);

//CARGAMOS EL COMBO calc_reten_impuesto_islr
$smarty->assign("option_values_calc_reten_impuesto_islr",array(0,1));
$smarty->assign("option_output_calc_reten_impuesto_islr",array("No","Si"));
$smarty->assign("option_selected_calc_reten_impuesto_islr",$datacliente[0]["calc_reten_impuesto_islr"]);

//CARGAMOS EL COMBO permitecredito
$smarty->assign("option_values_permitecredito",array(0,1));
$smarty->assign("option_output_permitecredito",array("No","Si"));
$smarty->assign("option_selected_permitecredito",$datacliente[0]["permitecredito"]);

$smarty->assign("datacliente",$datacliente);

  //select de distrito
$valueSELECT = "";
$outputSELECT =  "";
$distrito  = $clientes->ObtenerFilasBySqlSelect("select * from distrito_escolar");
foreach($distrito as $key => $item){
    $valueSELECT[] = $item["id"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_distrito",$valueSELECT);
$smarty->assign("option_output_distrito",$outputSELECT);
 $smarty->assign("option_selected_distrito", $datacliente[0]["id_distrito"]);

}

?>

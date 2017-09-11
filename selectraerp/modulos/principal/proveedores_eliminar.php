<?php

include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/correlativos.php");
$proveedores = new Proveedores();
$correlativos = new Correlativos();


if(isset($_POST["aceptar"])){

$proveedores->BeginTrans();
$instruccion =  "
delete  from proveedores 
                 where id_proveedor = '".$_POST["id_proveedor"]."'
";
$proveedores->ExecuteTrans($instruccion);
if($proveedores->errorTransaccion==1){Msg::setMessage("<span style=\"color:#62875f;\">Proveedor eliminado exitosamente</span>");}
if($proveedores->errorTransaccion==0){Msg::setMessage("<span style=\"color:red;\">Error al tratar de realizar la transaccion.</span>");}
$proveedores->CommitTrans($proveedores->errorTransaccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}



if(isset($_GET["cod"])){
    
$campos = $proveedores->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor = ".$_GET["cod"]);
$smarty->assign("datos_proveedores",$campos);
}


// Cargando tipo_comercio en combo select
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_comercio");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["cod_tipo_comercio"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_comercio",$arraySelectOption);
$smarty->assign("option_output_tipo_comercio",$arraySelectoutPut);




// Cargando tipo_comercio en combo select
 $arraySelectOption = $arraySelectoutPut = "";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_proveedor");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["cod_tipo_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_proveedor",$arraySelectOption);
$smarty->assign("option_output_tipo_proveedor",$arraySelectoutPut);




// Cargando tipo_comercio en combo select
 $arraySelectOption = $arraySelectoutPut = "";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_origen_proveedor");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["cod_tipo_origen_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_origen_proveedor",$arraySelectOption);
$smarty->assign("option_output_tipo_origen_proveedor",$arraySelectoutPut);






//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $proveedores->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("parametros_generales",$parametros_generales );

//Cargar Almacenes
$almacenes= $proveedores->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("almacenes",$almacenes );



?>

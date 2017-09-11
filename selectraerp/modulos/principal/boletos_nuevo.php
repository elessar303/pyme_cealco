<?php

include("../../libs/php/clases/boletos.php");
include("../../libs/php/clases/correlativos.php");

$boletos = new Boletos();
$correlativos = new Correlativos();
$campos_almacen = $boletos->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("campos_almacen" , $campos_almacen );
if(isset($_POST["aceptar"])){

$boletos->BeginTrans();
$nro_boleto = $correlativos->getUltimoCorrelativo("cod_boleto", 1,"si","B");

$instruccion =  "
INSERT INTO `item`
(`cod_item`, `descripcion1`, `descripcion2`, `descripcion3`, `referencia`,
`precio1`, `utilidad1`, `coniva1`, 
`precio2`, `utilidad2`, `coniva2`, 
`precio3`, `utilidad3`, `coniva3`, 
`monto_exento`, `iva`, `cod_departamento`, 
`cod_grupo`, `cod_linea`, 
`estatus`,`usuario_creacion`,
`fecha_creacion`, `cod_item_forma`
) VALUES
(
'".$nro_boleto."',
'".$_POST["descripcion1"]."',
'".$_POST["descripcion2"]."',
'".$_POST["descripcion3"]."',
'".$_POST["referencia"]."',
'".$_POST["precio1"]."',
'".$_POST["utilidad1"]."',
'".$_POST["coniva1"]."',
'".$_POST["precio2"]."',
 '".$_POST["utilidad2"]."',
'".$_POST["coniva2"]."',
'".$_POST["precio3"]."',
'".$_POST["utilidad3"]."',
'".$_POST["coniva3"]."',
'".$_POST["monto_exento"]."',
'".$_POST["iva"]."',

'".$_POST["cod_departamento"]."',
'".$_POST["cod_grupo"]."',
'".$_POST["cod_linea"]."',

'".$_POST["estatus"]."',
'".$login->getUsuario()."',
CURRENT_TIMESTAMP,
3
);
";

$boletos->ExecuteTrans($instruccion);
if($boletos->errorTransaccion==1){Msg::setMessage("<span style=\"color:#62875f;\">Boleto Generado Exitosamente con en Nro. ".$nro_boleto."</span>");}
if($boletos->errorTransaccion==0){Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear el boleto.</span>");}
$nro_boleto = $correlativos->getUltimoCorrelativo("cod_boleto", 1,"no","");
$boletos->ExecuteTrans("update correlativos set contador = '".$nro_boleto."' where campo = 'cod_boleto'");
$boletos->CommitTrans($boletos->errorTransaccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}


$smarty->assign("nro_boletoOLD",$correlativos->getUltimoCorrelativo("cod_boleto", 0,"si","B"));
$smarty->assign("nro_boletoNEW",$correlativos->getUltimoCorrelativo("cod_boleto", 1,"si","B"));

$ultimocodigo = $boletos->ObtenerFilasBySqlSelect("select cod_item from item  where cod_item_forma = 3 order by id_item desc limit 0,1");
$smarty->assign("ultimo_codigo",$ultimocodigo);

// Cargando departamentos en combo select
$campos_comunes = $boletos->ObtenerFilasBySqlSelect("select * from departamentos");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_departamento"];
}
$smarty->assign("option_values_departamentos",$arraySelectOption);
$smarty->assign("option_output_departamentos",$arraySelectoutPut);

// Cargando grupo en combo select
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $boletos->ObtenerFilasBySqlSelect("select * from grupo");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_grupo"];
}
$smarty->assign("option_values_grupo",$arraySelectOption);
$smarty->assign("option_output_grupo",$arraySelectoutPut);


// Cargando Linea en combo select
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $boletos->ObtenerFilasBySqlSelect("select * from linea");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_linea"];
}
$smarty->assign("option_values_linea",$arraySelectOption);
$smarty->assign("option_output_linea",$arraySelectoutPut);

//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $boletos->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("parametros_generales",$parametros_generales );

//Cargar Almacenes
$almacenes= $boletos->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("almacenes",$almacenes );



?>

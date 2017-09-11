<?php
include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/factura.php");
include("../../libs/php/clases/correlativos.php");

$clientes = new Clientes();
$factura = new Factura();

$correlativos = new Correlativos();

if(isset($_POST["PFactura2"])){ // si el usuario iso post

$factura->BeginTrans();

/**
 * Verificamos si la factura fue pagada completa.
 */
if($_POST["input_totalizar_total_general"]==$_POST["input_totalizar_monto_cancelar"]){
    $marca = "X"; // indicamos con esto en el campo <marca> de la tabla cxc_edocuenta que fue pagada
    $cod_estatus =  "2"; // cod_estatus = 2 indicada que esta pagada
}else{
    $marca = NULL;
    $cod_estatus =  "1"; // cod_estatus = 1 indicada que esta en Proceso.
}


//obtener correlativo:
//obtenemos el correlativo de la factura
$nro_factura = $correlativos->getUltimoCorrelativo("cod_factura_boleto", 0, "si","B");
$formateo_nro_factura = $nro_factura;
$sql_cabecera = "
INSERT INTO `boleto_factura` (
`cod_factura_boleto`,
`id_cliente`,
`cod_vendedor`,
`fechaFactura`,
`subtotal`,
`descuentosItemFactura`,
`montoItemsFactura`,
`ivaTotalFactura`,
`TotalTotalFactura`,
`cantidad_items`,
`totalizar_sub_total`,
`totalizar_descuento_parcial`,
`totalizar_total_operacion`,
`totalizar_pdescuento_global`,
`totalizar_descuento_global`,
`totalizar_monto_iva`,
`totalizar_total_general`,
`fecha_creacion`,
`usuario_creacion`,`cod_estatus`) VALUES
(
'".$nro_factura."',
".$_POST["id_cliente"].",".$_POST["cod_vendedor"].",'".$_POST["input_fechaFactura"]."',
".$_POST["input_subtotal"].",".$_POST["input_descuentosItemFactura"].",".$_POST["input_montoItemsFactura"].",
".$_POST["input_ivaTotalFactura"].",".$_POST["input_TotalTotalFactura"].",".$_POST["input_cantidad_items"].",
".$_POST["input_totalizar_sub_total"].",".$_POST["input_totalizar_descuento_parcial"].",".$_POST["input_totalizar_total_operacion"].",
".$_POST["input_totalizar_pdescuento_global"].",".$_POST["input_totalizar_descuento_global"].",".$_POST["input_totalizar_monto_iva"].",
".$_POST["input_totalizar_total_general"].",
CURRENT_TIMESTAMP,'".$login->getUsuario()."',
    '".$cod_estatus."'
);";
$factura->ExecuteTrans($sql_cabecera);


$id_facturaTrans = $factura->getInsertID();

$SQLdetalle_formapago = "
INSERT INTO boleto_factura_detalle_formapago (
`cod_factura_detalle_formapago_boleto` ,
`id_factura_boleto` ,
`totalizar_monto_cancelar` ,
`totalizar_saldo_pendiente` ,
`totalizar_cambio` ,
`totalizar_monto_efectivo` ,
`opt_cheque` ,
`totalizar_monto_cheque` ,
`totalizar_nro_cheque` ,
`totalizar_nombre_banco` ,
`opt_tarjeta` ,
`totalizar_monto_tarjeta` ,
`totalizar_nro_tarjeta` ,
`totalizar_tipo_tarjeta` ,
`opt_deposito` ,
`totalizar_monto_deposito` ,
`totalizar_nro_deposito` ,
`totalizar_banco_deposito` ,
`fecha_vencimiento` ,
`observacion` ,
`persona_contacto` ,
`telefono` ,
`fecha_creacion` ,
`usuario_creacion`
)
VALUES (
NULL , '".$id_facturaTrans."',
".$_POST["input_totalizar_monto_cancelar"].",
".$_POST["input_totalizar_saldo_pendiente"].",
".$_POST["input_totalizar_cambio"].",
".$_POST["input_totalizar_monto_efectivo"].",
'".$_POST["opt_cheque"]."',
".$_POST["input_totalizar_monto_tarjeta"].",
'".$_POST["input_totalizar_nro_cheque"]."',
'".$_POST["input_totalizar_nombre_banco"]."',
'".$_POST["opt_tarjeta"]."',
".$_POST["input_totalizar_monto_tarjeta"].",
".$_POST["input_totalizar_nro_tarjeta"].",
'".$_POST["input_totalizar_tipo_tarjeta"]."',
'".$_POST["opt_deposito"]."',
'".$_POST["input_totalizar_banco_deposito"]."',
".$_POST["input_totalizar_nro_deposito"].",
'".$_POST["input_totalizar_banco_deposito"]."',
'".$_POST["fecha_vencimiento"]."',
'".$_POST["observacion"]."',
'".$_POST["persona_contacto"]."',
'".$_POST["telefono"]."',
CURRENT_TIMESTAMP ,
'".$login->getUsuario()."'
);";
$factura->ExecuteTrans($SQLdetalle_formapago);




/**
 * Insertamos en la tabla de cuentas por cobrar la cabecera del asiento.
 */
$SQL_CXC = "INSERT INTO cxc_edocuenta (
`cod_edocuenta` ,
`id_cliente` ,
`documento` ,
`numero` ,
`monto` ,
`fecha_emision` ,
`observacion` ,
`vencimiento_fecha` ,
`vencimiento_persona_contacto` ,
`vencimiento_telefono` ,
`vencimiento_descripcion` ,
`usuario_creacion` ,
`fecha_creacion`,
`marca`
)
VALUES (
NULL ,
'".$_POST["id_cliente"]."',
'FACxBOL',
'".$nro_factura."',
'".$_POST["input_totalizar_total_general"]."',
'".date("Y-m-d")."',
'FACTURA DE BOLETO ".$nro_factura."',
'".$_POST["fecha_vencimiento"]."',
'".$_POST["persona_contacto"]."' ,
'".$_POST["telefono"]."' ,
'".$_POST["observacion"]."' ,
'".$login->getUsuario()."',
CURRENT_TIMESTAMP,
'".$marca."'
);";
$factura->ExecuteTrans($SQL_CXC);
    $id_cxc = $factura->getInsertID();




$SQL_CXC_DET = "INSERT INTO cxc_edocuenta_detalle (
`cod_edocuenta_detalle` ,
`cod_edocuenta` ,
`documento` ,
`numero` ,
`descripcion` ,
`tipo` ,
`monto` ,
`usuario_creacion` ,
`fecha_creacion`,
`fecha_emision_edodet`
)
VALUES (
NULL , '".$id_cxc."', 'PAGOxFAC.BOL', '".$nro_factura."R', 'Factura Boleto ".$nro_factura."',
    'd', '".$_POST["input_totalizar_total_general"]."', '".$login->getUsuario()."',
CURRENT_TIMESTAMP, '".date("Y-m-d")."'
);";
/**
 * Se inserta el detalle de la CxC en este caso el asiento del DEBITO.
 */
$factura->ExecuteTrans($SQL_CXC_DET);
$cod_edocuenta_detalle = $factura->getInsertID();


/**
 * Aumentamos el valor del correlativo del Pago o Abono de Factura.

$factura->ExecuteTrans("update correlativos set contador = '".$correlativos->getUltimoCorrelativo("cod_pago_o_abono",1)."' where campo = 'cod_pago_o_abono'");
 */

/**
* Obtenemos el siguiente numero de correlativo de Pago x Abono a Factura.

$cod_pago_o_abono = $correlativos->getUltimoCorrelativo("cod_pago_o_abono",0,"si","");
*/

/**
 * Verificamos el pago fue completo, un abono ó fue un credito.
 */
if($_POST["totalizar_monto_cancelar"]>0&&$_POST["totalizar_monto_cancelar"]<=$_POST["input_totalizar_total_general"]){
        $SQL_CXC_DET = "INSERT INTO cxc_edocuenta_detalle (
        `cod_edocuenta_detalle` ,
        `cod_edocuenta` ,
        `documento` ,
        `numero` ,
        `descripcion` ,
        `tipo` ,
        `monto` ,
        `usuario_creacion` ,
        `fecha_creacion`
        )
        VALUES (
        NULL , '".$id_cxc."', 'PAGOxFACxBOL', '".$nro_factura."R', 'Pago Factura Boleto ".$nro_factura."',
            'c', '".$_POST["totalizar_monto_cancelar"]."', '".$login->getUsuario()."',
        CURRENT_TIMESTAMP
        );";
        /**
         * Se inserta el detalle de la CxC en este caso el asiento del CREDITO.
         */
        $factura->ExecuteTrans($SQL_CXC_DET);


}


/**
 * SQL para generar el detalle de forma pago en la tabla de cxc_edocuenta_formapago.
 */
$SQL_cxc_formapago = "
INSERT INTO cxc_edocuenta_formapago (
`cod_cxc_edocuenta_formapago` ,
`cod_edocuenta_detalle` ,
`totalizar_monto_cancelar` ,
`totalizar_saldo_pendiente` ,
`totalizar_cambio` ,
`totalizar_monto_efectivo` ,
`opt_cheque` ,
`totalizar_monto_cheque` ,
`totalizar_nro_cheque` ,
`totalizar_nombre_banco` ,
`opt_tarjeta` ,
`totalizar_monto_tarjeta` ,
`totalizar_nro_tarjeta` ,
`totalizar_tipo_tarjeta` ,
`opt_deposito` ,
`totalizar_monto_deposito` ,
`totalizar_nro_deposito` ,
`totalizar_banco_deposito` ,
`fecha_creacion` ,
`usuario_creacion`
)
VALUES (
NULL ,
'".$cod_edocuenta_detalle."',
".$_POST["input_totalizar_monto_cancelar"].",
".$_POST["input_totalizar_saldo_pendiente"].",
".$_POST["input_totalizar_cambio"].",
".$_POST["input_totalizar_monto_efectivo"].",
'".$_POST["opt_cheque"]."',
".$_POST["input_totalizar_monto_tarjeta"].",
'".$_POST["input_totalizar_nro_cheque"]."',
'".$_POST["input_totalizar_nombre_banco"]."',
'".$_POST["opt_tarjeta"]."',
".$_POST["input_totalizar_monto_tarjeta"].",
".$_POST["input_totalizar_nro_tarjeta"].",
'".$_POST["input_totalizar_tipo_tarjeta"]."',
'".$_POST["opt_deposito"]."',
'".$_POST["input_totalizar_banco_deposito"]."',
".$_POST["input_totalizar_nro_deposito"].",
'".$_POST["input_totalizar_banco_deposito"]."',
CURRENT_TIMESTAMP , '".$login->getUsuario()."'
);
";
$factura->ExecuteTrans($SQL_cxc_formapago);




for($i=0;$i<(int)$_POST["input_cantidad_items"];$i++){

$detalle_item_instruccion = "
INSERT INTO boleto_factura_detalle (
`id_detalle_factura_boleto` ,
`id_factura_boleto` ,
`id_item` ,
`_item_descripcion` ,
`_item_cantidad` ,
`_item_preciosiniva` ,
`_item_descuento` ,
`_item_montodescuento` ,
`_item_piva` ,
`_item_totalsiniva` ,
`_item_totalconiva` ,
`usuario_creacion` ,
`fecha_creacion`,
`_item_almacen`
)
VALUES (
NULL , '".$id_facturaTrans."', '".$_POST["_item_codigo"][$i]."', '".$_POST["_item_descripcion"][$i]."',
    '".$_POST["_item_cantidad"][$i]."', '".$_POST["_item_preciosiniva"][$i]."',
        '".$_POST["_item_descuento"][$i]."', '".$_POST["_item_montodescuento"][$i]."',
            '".$_POST["_item_piva"][$i]."', '".$_POST["_item_totalsiniva"][$i]."', '".$_POST["_item_totalconiva"][$i]."', '".$login->getUsuario()."',
CURRENT_TIMESTAMP, '".$_POST["_item_almacen"][$i]."'
);";
$factura->ExecuteTrans($detalle_item_instruccion);



$canpedido = $_POST["_item_cantidad"][$i];

for($z=1;$z<=$canpedido;$z++){

$nrocodebarboleto = $correlativos->getUltimoCorrelativo("cod_codebar", 0, "si");

$campos_boletos = $factura->ObtenerFilasBySqlSelect("select * from item where id_item = ".$_POST["_item_codigo"][$i]);


$sql_ = "
INSERT INTO boleto_codebar (
`cod_boleto_codebar_` ,
`id_factura_boleto` ,
`boleto_descripcion` ,
`precio` ,
`id_cliente` ,
`fecha_creacion` ,
`usuario_creacion` ,
`fecha`, `codebar`
)
VALUES (
NULL , '".$id_facturaTrans."', '".$campos_boletos[0]["descripcion1"]."',
    '".$_POST["_item_preciosiniva"][$i]."', '".$_POST["id_cliente"]."',
CURRENT_TIMESTAMP , '".$login->getUsuario()."', '".date("Y-m-d")."',
    '".$nrocodebarboleto."'
);
";


$factura->ExecuteTrans($sql_);


$newnrocodebarboleto = $correlativos->getUltimoCorrelativo("cod_codebar", 1, "no");
$factura->ExecuteTrans("update correlativos set contador = '".$newnrocodebarboleto."' where campo = 'cod_codebar'");
}




$campos = $factura->ObtenerFilasBySqlSelect("
                    select * from item_existencia_almacen
                            where
                    id_item  = '".$_POST["_item_codigo"][$i]."' and
                    cod_almacen = '".$_POST["_item_almacen"][$i]."'");
$cantidadExistente = $campos[0]["cantidad"];

$factura->ExecuteTrans("update item_existencia_almacen set
    cantidad = '".($cantidadExistente-$_POST["_item_cantidad"][$i])."'
 where id_item  = '".$_POST["_item_codigo"][$i]."' and cod_almacen = '".$_POST["_item_almacen"][$i]."'");
$factura->ExecuteTrans("delete from item_precompromiso where cod_item_precompromiso = '".$_POST["_cod_item_precompromiso"][$i]."'");



}//for($i=0;$i<(int)$_POST["input_cantidad_items"];$i++){
$nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_factura_boleto", 1, "no");
$nro_factura = $correlativos->getUltimoCorrelativo("cod_factura_boleto", 1, "no");
$factura->ExecuteTrans("update correlativos set contador = '".$nro_factura."' where campo = 'cod_factura_boleto'");
$nro_factura -= 1;
if($factura->errorTransaccion==1){Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Factura Boleto Generado Exitosamente con el <b>Nro. ".$formateo_nro_factura."</b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: ".number_format($_POST["input_totalizar_total_general"], 2, ",", ".") ." Bs.</b><br><img src=\"../../libs/imagenes/cancelar.png\"> <b>Monto Cancelado: ".number_format($_POST["input_totalizar_monto_cancelar"], 2, ",", ".") ." Bs.</b><br><img src=\"../../libs/imagenes/ico_view.gif\"> <b><span style=\"color:red;\">Monto Pendiente: ".number_format($_POST["input_totalizar_saldo_pendiente"], 2, ",", ".") ." Bs.</span></b><br><img src=\"../../libs/imagenes/cambio.png\"> <b>Monto Cambio: ".number_format($_POST["input_totalizar_cambio"], 2, ",", ".") ." Bs.</b><br>Para imprimir la factura <a href=\"#\" onclick=\"window.open(\'../../reportes/rpt_factura_boletos.php?codigo=$formateo_nro_factura\');\">haga click aqui</a></span>");}
if($factura->errorTransaccion==0){Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la factura.</span>");}
$factura->CommitTrans($factura->errorTransaccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}else{

if(!isset($_GET["cod"])){
    header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
    exit;
}


$factura->Execute2("
 delete from item_precompromiso where
        idSessionActualphp = '".$login->getIdSessionActual()."'      and
        usuario_creacion = '".$login->getUsuario()."'");

$nro_factura = $correlativos->getUltimoCorrelativo("cod_factura_boleto", 0,"si","B");
$smarty->assign("nro_factura",$nro_factura);

$datacliente = $clientes->ObtenerFilasBySqlSelect("select * from clientes where id_cliente = ".$_GET["cod"]);
if(count($datacliente)==0){

    $pagina .= "<html>";
    $pagina .= "<body style=\"background-color:#f8f8f8\">";
    $pagina .= "<div  style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius: 8px;padding:5px; margin-left: 20%;margin-right: 20%;margin-top:5%;   font-size: 13px; \">";
    $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion esta permitida:</b> <br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Verifique que el cliente al que desea facturar exista.</span><br>
            ";
    $pagina .= "<hr><span style=\"color:#1e6602\">Para mas información contacte al administrador.</span>";
if(count($campos)>0) $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> ".$campos[0]["descripcion_optmenu"]." - <b>Sección:</b> ".$campos[0]["descripcion_optseccion"]." >> <b>".$campos[0]["opt_subseccion"].":</b> ".$campos[0]["descripcion"];
    $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]."'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
    $pagina .= "</div>";
    $pagina .= "</body>";
    $pagina .= "</html>";
    echo utf8_decode($pagina);
    exit;
}

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

$datos_almacen = $clientes->ObtenerFilasBySqlSelect("select * from almacen");
$valueSELECT="";
$outputSELECT="";
foreach($datos_almacen as $key => $item){
    $valueSELECT[] = $item["cod_almacen"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_almacen",$valueSELECT);
$smarty->assign("option_output_almacen",$outputSELECT);
$datos_item_forma = $clientes->ObtenerFilasBySqlSelect("select * from item_forma where cod_item_forma in (3)");
$valueSELECT="";
$outputSELECT="";
foreach($datos_item_forma as $key => $item){
    $valueSELECT[] = $item["cod_item_forma"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_item_forma",$valueSELECT);
$smarty->assign("option_output_item_forma",$outputSELECT);

$impuesto_iva = $clientes->ObtenerFilasBySqlSelect("select * from impuesto_iva");
$valueSELECT="";
$outputSELECT="";
foreach($impuesto_iva as $key => $item){
    $valueSELECT[] = $item["cod_impuesto_iva"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_impuesto_iva",$valueSELECT);
$smarty->assign("option_output_impuesto_iva",$outputSELECT);

$datos_islr_forma = $clientes->ObtenerFilasBySqlSelect("select * from impuesto_islr");
$valueSELECT="";
$outputSELECT="";
foreach($datos_islr_forma as $key => $item){
    $valueSELECT[] = $item["cod_islr"];
    $outputSELECT[] = $item["descripcion"];
}

$smarty->assign("option_values_islr",$valueSELECT);
$smarty->assign("option_output_islr",$outputSELECT);


$datos_banco = $clientes->ObtenerFilasBySqlSelect("select * from banco order by descripcion");
$valueSELECT="";
$outputSELECT="";
foreach($datos_banco as $key => $item){
    $valueSELECT[] = $item["cod_banco"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_banco",$valueSELECT);
$smarty->assign("option_output_banco",$outputSELECT);

$datos_instrumento_pago = $clientes->ObtenerFilasBySqlSelect("select * from instrumentopago_formapago where cod_funcioninstrumento in ( 1,2) order by descripcion");
$valueSELECT="";
$outputSELECT="";
foreach($datos_instrumento_pago as $key => $item){
    $valueSELECT[] = $item["cod_formapago"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_instrumento_pago_tarjeta",$valueSELECT);
$smarty->assign("option_output_instrumento_pago_tarjeta",$outputSELECT);




}
$smarty->assign("idpreciolibre",codTipoPrecioLibre);
$smarty->assign("idprecio1",codTipoPrecio1);
$smarty->assign("idprecio2",codTipoPrecio2);
$smarty->assign("idprecio3",codTipoPrecio3);





?>

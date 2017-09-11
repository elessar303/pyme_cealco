<?php
include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/cxc.php");
include("../../libs/php/clases/correlativos.php");
include("../../libs/php/clases/factura.php");

$clientes = new Clientes();
$cxc = new CXC();
$correlativos = new Correlativos();
$factura = new Factura();

if(isset($_POST["PFactura2"])){ // si el usuario iso post

        if($_POST["totalizar_monto_cancelar"]>0&&$_POST["totalizar_monto_cancelar"]+$_POST["totalizar_total_retencion"]<=$_POST["totalizar_total_general"]){

                $cxc->BeginTrans();
                $id_cxc = $_POST["cod_edocuenta"];
                /**
                 * Verificamos si la factura fue pagada completa.
                 */
                
                if($_POST["totalizar_monto_cancelar"]+$_POST["totalizar_total_retencion"]==$_POST["totalizar_total_general"]){
                    $marca = "X"; // indicamos con esto en el campo <marca> de la tabla cxc_edocuenta que fue pagada
                    $cod_estatus =  "2"; // cod_estatus = 2 indicada que esta pagada
                    $instruccion = "update factura f inner join cxc_edocuenta cxc
                        on cxc.numero = f.cod_factura

                set f.cod_estatus = ".$cod_estatus.",
                    f.fecha_pago= '".date("Y-m-d")."',
                    cxc.marca = 'X'
                     where cxc.cod_edocuenta = ".$_POST["cod_edocuenta"];
                    $cxc->ExecuteTrans($instruccion);
                }


        $cod_pago_o_abono = $correlativos->getUltimoCorrelativo("cod_pago_o_abono", 0, "si","");

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
        NULL , '".$id_cxc."', 'ABO/PAGOxFAC', '".$cod_pago_o_abono."', '".$_POST["descripcion_pagoabono"]."',
            'c', '".$_POST["totalizar_monto_cancelar"]."', '".$login->getUsuario()."',
        CURRENT_TIMESTAMP, '".$_POST["fecha_emision"]."'
        );";
        /**
         * Se inserta el detalle de la CxC en este caso el asiento del CREDITO.
         */
        $cxc->ExecuteTrans($SQL_CXC_DET);
        $cod_edocuenta_detalle = $cxc->getInsertID();


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

                                            `opt_otrodocumento` ,
                                            `totalizar_monto_otrodocumento` ,
                                            `totalizar_nro_otrodocumento` ,
                                            `totalizar_banco_otrodocumento` ,

                                            `fecha_creacion` ,
                                            `usuario_creacion`
                                            )
                                            VALUES (
                                              NULL ,
                                            '".$cod_edocuenta_detalle."',
                                            '".$_POST["totalizar_monto_cancelar"]."',
                                            '".$_POST["totalizar_saldo_pendiente"]."',
                                            '".$_POST["totalizar_cambio"]."',
                                            '".$_POST["totalizar_monto_efectivo"]."',
                                            '".$_POST["opt_cheque"]."',
                                            '".$_POST["totalizar_monto_tarjeta"]."',
                                            '".$_POST["totalizar_nro_cheque"]."',
                                            '".$_POST["totalizar_nombre_banco"]."',
                                            '".$_POST["opt_tarjeta"]."',
                                            '".$_POST["totalizar_monto_tarjeta"]."',
                                            '".$_POST["totalizar_nro_tarjeta"]."',
                                            '".$_POST["totalizar_tipo_tarjeta"]."',
                                            '".$_POST["opt_deposito"]."',
                                            '".$_POST["totalizar_banco_deposito"]."',
                                            '".$_POST["totalizar_nro_deposito"]."',
                                            '".$_POST["totalizar_banco_deposito"]."',

                                            '".$_POST["opt_otrodocumento"]."',
                                            '".$_POST["totalizar_banco_otrodocumento"]."',
                                            '".$_POST["totalizar_nro_otrodocumento"]."',
                                            '".$_POST["totalizar_banco_otrodocumento"]."',
                                            CURRENT_TIMESTAMP , '".$login->getUsuario()."'
                                            );
                                            ";
                                            $cxc->ExecuteTrans($SQL_cxc_formapago);
                                           

        $nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_pago_o_abono", 1, "no");
        $nro_factura = $correlativos->getUltimoCorrelativo("cod_pago_o_abono", 1, "no");
        $cxc->ExecuteTrans("update correlativos set contador = '".$nro_factura."' where campo = 'cod_pago_o_abono'");
        $nro_factura -= 1;
        if($cxc->errorTransaccion==1){Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Pago/Abono generado exitosamente bajo el <b>Nro. ".$cod_pago_o_abono."</b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: ".number_format($_POST["totalizar_total_general"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/cancelar.png\"> <b>Monto Cancelado: ".number_format($_POST["totalizar_monto_cancelar"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total Retencion: ".number_format($_POST["totalizar_total_retencion"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/ico_view.gif\"> <b><span style=\"color:red;\">Monto Pendiente: ".number_format($_POST["totalizar_saldo_pendiente"], 2, ",", ".") ." </span></b><br><img src=\"../../libs/imagenes/cambio.png\"> <b>Monto Cambio: ".number_format($_POST["totalizar_cambio"], 2, ",", ".") ."</b></span>");}
        if($cxc->errorTransaccion==0){Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la factura.</span>");}
        $cxc->CommitTrans($cxc->errorTransaccion);
        }else{
            if($_POST["totalizar_monto_cancelar"]>0&&$_POST["totalizar_monto_cancelar"]>$_POST["totalizar_total_general"]){
            //si elcliente paga de mas
                $cxc->BeginTrans();
                                    $id_cxc = $_POST["cod_edocuenta"];
                                    /**
                                     * Verificamos si la factura fue pagada completa.
                                     */


                                  
                                        $marca = "X"; // indicamos con esto en el campo <marca> de la tabla cxc_edocuenta que fue pagada
                                        $cod_estatus =  "2"; // cod_estatus = 2 indicada que esta pagada
                                        $instruccion = "update factura f inner join cxc_edocuenta cxc
                                            on cxc.numero = f.cod_factura

                                    set f.cod_estatus = ".$cod_estatus.",
                                        f.fecha_pago= '".date("Y-m-d")."',
                                        cxc.marca = 'X'
                                         where cxc.cod_edocuenta = ".$_POST["cod_edocuenta"];
                                        $cxc->ExecuteTrans($instruccion);
                                    


                            $cod_pago_o_abono = $correlativos->getUltimoCorrelativo("cod_pago_o_abono", 0, "si","");

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
                            NULL , '".$id_cxc."', 'ABO/PAGOxFAC', '".$cod_pago_o_abono."', '".$_POST["descripcion_pagoabono"]."',
                                'c', '".$_POST["totalizar_total_general"]."', '".$login->getUsuario()."',
                            CURRENT_TIMESTAMP, '".$_POST["fecha_emision"]."'
                            );";
                            /**
                             * Se inserta el detalle de la CxC en este caso el asiento del CREDITO.
                             */
                            $cxc->ExecuteTrans($SQL_CXC_DET);
                            $cod_edocuenta_detalle = $cxc->getInsertID();


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
                                            '".$_POST["totalizar_monto_cancelar"]."',
                                            '".$_POST["totalizar_saldo_pendiente"]."',
                                            '".$_POST["totalizar_cambio"]."',
                                            '".$_POST["totalizar_monto_efectivo"]."',
                                            '".$_POST["opt_cheque"]."',
                                            '".$_POST["totalizar_monto_tarjeta"]."',
                                            '".$_POST["totalizar_nro_cheque"]."',
                                            '".$_POST["totalizar_nombre_banco"]."',
                                            '".$_POST["opt_tarjeta"]."',
                                            '".$_POST["totalizar_monto_tarjeta"]."',
                                            '".$_POST["totalizar_nro_tarjeta"]."',
                                            '".$_POST["totalizar_tipo_tarjeta"]."',
                                            '".$_POST["opt_deposito"]."',
                                            '".$_POST["totalizar_banco_deposito"]."',
                                            '".$_POST["totalizar_nro_deposito"]."',
                                            '".$_POST["totalizar_banco_deposito"]."',
                                            CURRENT_TIMESTAMP , '".$login->getUsuario()."'
                                            );
                                            ";
                                            $cxc->ExecuteTrans($SQL_cxc_formapago);




                            $nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_pago_o_abono", 1, "no");
                            $nro_factura = $correlativos->getUltimoCorrelativo("cod_pago_o_abono", 1, "no");
                            $cxc->ExecuteTrans("update correlativos set contador = '".$nro_factura."' where campo = 'cod_pago_o_abono'");
                            $nro_factura -= 1;
                            if($cxc->errorTransaccion==1){Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Pago/Abono generado exitosamente bajo el <b>Nro. ".$cod_pago_o_abono."</b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: ".number_format($_POST["totalizar_total_general"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/cancelar.png\"> <b>Monto Cancelado: ".number_format($_POST["totalizar_monto_cancelar"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total Retencion: ".number_format($_POST["totalizar_total_retencion"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/ico_view.gif\"> <b><span style=\"color:red;\">Monto Pendiente: ".number_format($_POST["totalizar_saldo_pendiente"], 2, ",", ".") ." Bs.</span></b><br><img src=\"../../libs/imagenes/cambio.png\"> <b>Monto Cambio: ".number_format($_POST["totalizar_cambio"], 2, ",", ".") ." Bs.</b></span>");}
                            if($cxc->errorTransaccion==0){Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la factura.</span>");}
                            $cxc->CommitTrans($cxc->errorTransaccion);

             }
            
        }

//Insert en la tabla de impuestos

//echo $_POST["cantidad_impuesto"]."<br>";

for($i=1;$i<=(int)$_POST["cantidad_impuesto"];$i++){
//echo "<br>".$i."<br>";
if($_POST["cod_impuesto$i"]!=""&&$_POST["totalizar_monto_retencion$i"]>0&&$_POST["totalizar_monto_cancelar"]>0&&$_POST["totalizar_monto_cancelar"]<$_POST["totalizar_total_general"])
{

    if($_POST["cod_tipo_impuesto$i"]==1)
    {
        $base_imponible=$_POST["totalizar_monto_iva"];
    }else
    {
        $base_imponible=$_POST["totalizar_base_imponible"];
    }

$detalle_tabla_impuesto = "
INSERT INTO tabla_impuestos (
`id_tabla_impuestos` ,
`id_documento` ,
`tipo_documento` ,
`numero_control_factura` ,
`id_fiscal` ,
`id_cliente` ,
`cod_tipo_impuesto` ,
`cod_impuesto` ,
`totalizar_pbase_retencion` ,
`totalizar_monto_retencion` ,
`totalizar_base_imponible` ,
`totalizar_monto_exento` ,
`usuario_creacion` ,
`fecha_creacion`
)
VALUES (
NULL , '".$_POST["id_factura"]."', 'f', '".$_POST["numero_control_factura"]."', '".$_POST["id_fiscal"]."',
    '".$_POST["id_cliente"]."', '".$_POST["cod_tipo_impuesto$i"]."', '".$_POST["cod_impuesto$i"]."',
        '".$_POST["totalizar_pbase_retencion$i"]."', '".$_POST["totalizar_monto_retencion$i"]."',
            '".$base_imponible."', '".$_POST["totalizar_monto_exento$i"]."',
                '".$login->getUsuario()."',CURRENT_TIMESTAMP
);";
$factura->ExecuteTrans($detalle_tabla_impuesto);

//if($_POST["totalizar_monto_cancelar"]>0&&$_POST["totalizar_monto_cancelar"]<$_POST["input_totalizar_total_general"]){
        $SQL_CXC_DET2 = "INSERT INTO cxc_edocuenta_detalle (
        `cod_edocuenta_detalle` ,
        `cod_edocuenta` ,
        `documento` ,
        `numero` ,
        `descripcion` ,
        `tipo` ,
        `monto` ,
        `usuario_creacion` ,
        `fecha_creacion` ,
        `fecha_emision_edodet`
        )
        VALUES (
        NULL , '".$id_cxc."', 'PAGOxFAC', '".$_POST["numero_control_factura"]."', 'Retenciones de Impuesto a Factura ".$_POST["numero_control_factura"]."',
            'c', '".$_POST["totalizar_monto_retencion$i"]."', '".$login->getUsuario()."',
        CURRENT_TIMESTAMP, '".$_POST["fecha_emision"]."'
        );";
        /**
         * Se inserta el detalle de la CxC en este caso el asiento de lDEBITO.
         */
        $factura->ExecuteTrans($SQL_CXC_DET2);


//}// FIN DEL IF DE NSERTAR DETALLE DE IMPUESTOS EN ESTADO DE CUENTA

} // FIN DEL IF DE INSERTAR IMPUESTOS EN LA TABLA IMPUESTOS
}
     


header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=edocuenta&cod=".$_POST["codcliente"]);
exit;
} // si el usuario iso post


if(!isset($_GET["cod"])){
    header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
    exit;
}

if(!isset($_GET["cod2"])){
    header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
    exit;
}


/**
 * Cabecera del Estado de Cuenta.
 */
$cabecera_estadodecuenta = $cxc->ObtenerFilasBySqlSelect('
select 
ifnull(sum(debito),0.00) as debito,
ifnull(sum(credito),0.00) as credito,
ifnull(sum(debito) -  sum(credito),0.00)  as saldo_pendiente,
(
select  count(cod_edocuenta) from cxc_edocuenta c where
c.marca = "X" and c.documento = "FAC" and c.id_cliente = vw_cxc.id_cliente
) as facturas_pagadas,
(
select  count(cod_edocuenta) from cxc_edocuenta c where c.marca <> "X" and c.documento = "FAC" and c.id_cliente = vw_cxc.id_cliente
) as facturas_pendientes,
(select  count(cod_edocuenta) from cxc_edocuenta c where
c.documento = "FAC" and c.id_cliente = vw_cxc.id_cliente) as total_facturas
 FROM vw_cxc where id_cliente =  '.$_GET['cod'].' and cod_edocuenta = '.$_GET['cod2'].' group by vw_cxc.id_cliente');
$smarty->assign("cabecera_estadodecuenta",$cabecera_estadodecuenta);


$datacliente = $clientes->ObtenerFilasBySqlSelect("select * from clientes where id_cliente = ".$_GET["cod"]);
if(count($datacliente)==0){

    $pagina .= "<html>";
    $pagina .= "<body style=\"background-color:#f8f8f8\">";
    $pagina .= "<div  style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius: 8px;padding:5px; margin-left: 20%;margin-right: 20%;margin-top:5%;   font-size: 13px; \">";
    $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion esta permitida:</b> <br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Verifique que el cliente al que desea facturar exista.</span><br>";
    $pagina .= "<hr><span style=\"color:#1e6602\">Para mas información contacte al administrador.</span>";
if(count($campos)>0) $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> ".$campos[0]["descripcion_optmenu"]." - <b>Sección:</b> ".$campos[0]["descripcion_optseccion"]." >> <b>".$campos[0]["opt_subseccion"].":</b> ".$campos[0]["descripcion"];
    $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]."'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
    $pagina .= "</div>";
    $pagina .= "</body>";
    $pagina .= "</html>";
    echo utf8_decode($pagina);
    exit;
}
$smarty->assign("datacliente",$datacliente);

$filas_estadodecuenta = $cxc->ObtenerFilasBySqlSelect("
   select
cod_edocuenta,
id_cliente,
documento,
numero,
monto,
case marca when 'X' then 'Pagada' else 'Pendiente' end as estado,
observacion,
fecha_emision,
vencimiento_fecha,
vencimiento_persona_contacto,
vencimiento_telefono,
vencimiento_descripcion
 from cxc_edocuenta
 where id_cliente  = ".$_GET["cod"]." order by numero, estado");
$smarty->assign("registros",$filas_estadodecuenta);


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


$datos_tipodocumento = $clientes->ObtenerFilasBySqlSelect("select * from instrumentopago_formapago  order by descripcion");
$valueSELECT="";
$outputSELECT="";
foreach($datos_tipodocumento as $key => $item){
    $valueSELECT[] = $item["cod_formapago"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_otrodocumento",$valueSELECT);
$smarty->assign("option_output_tipo_otrodocumento",$outputSELECT);

$factura = $factura->ObtenerFilasBySqlSelect("select * from factura left join cxc_edocuenta on cod_factura=numero where cod_edocuenta='".$_GET['cod2']."'");
$smarty->assign("factura",$factura);

$impuesto = $clientes->ObtenerFilasBySqlSelect("select * from tipo_impuesto");
$smarty->assign("tipo_impuesto",$impuesto);
$cantidadimpuesto = $clientes->ObtenerFilasBySqlSelect("select count(cod_tipo_impuesto) as cantidad_impuesto from tipo_impuesto");
$smarty->assign("numero_impuesto",$cantidadimpuesto);

$consulta="select li.descripcion as descripcion,li.cod_impuesto as cod_impuesto,
        li.cod_tipo_impuesto as cod_tipo_impuesto
        from lista_impuestos as li
        left join tipo_impuesto as ti on li.cod_tipo_impuesto=ti.cod_tipo_impuesto where li.cod_entidad=".$datacliente[0]["cod_entidad"];
$datos_impuesto = $clientes->ObtenerFilasBySqlSelect($consulta);
$smarty->assign("dato_impuesto",$datos_impuesto);

?>

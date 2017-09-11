<?php
session_start();
ini_set("display_errors", 1);
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/factura.php");
include("../../libs/php/clases/correlativos.php");
include("../../libs/php/clases/almacen.php");
require_once("../../libs/php/clases/parametrosgenerales.php");

#include("../../libs/php/clases/login.php");
include("txt.php");




$parametros = new ParametrosGenerales();
$clientes = new Clientes();
$factura = new Factura();
$almacen = new Almacen();
$correlativos = new Correlativos();
$obj_txt = new txt();
$login = new Login();

$usuario = $login->getUsuario();
$usuario_vende= $login->getIdUsuario();
// Si el usuario hizo post

$puede_vender=$clientes->ObtenerFilasBySqlSelect("select vendedor from usuarios where cod_usuario='".$login->getIdUsuario()."'");
if($puede_vender[0]['vendedor']==0){
    echo "<script type='text/javascript'>alert('Error, Usted No Posee Permisos Para Vender');
    window.close();
    </script>";

                            }

if (isset($_POST["transaccion"])) {
    
    $factura->BeginTrans();
    # Verificamos si la factura fue pagada completa.
    if ($_POST["input_totalizar_total_general"] == $_POST["input_totalizar_monto_cancelar"] + $_POST["totalizar_total_retencion"] || $_POST["input_totalizar_monto_cancelar"] > $_POST["input_totalizar_total_general"]) {
        $marca = "X"; // indicamos con esto en el campo <marca> de la tabla cxc_edocuenta que fue pagada
        $cod_estatus = "2"; // cod_estatus = 2 indicada que esta pagada
    } else {
        $marca = "P";
        $cod_estatus = "1"; // cod_estatus = 1 indicada que esta en Proceso.
    }
    # obtenemos el correlativo de la factura
    $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 0, "si");
    $formateo_nro_factura = $nro_factura;

    $sql = "INSERT INTO `factura` (
        `id_cliente`,`cod_factura`,`cod_vendedor`,`fechaFactura`,
        `subtotal`,`descuentosItemFactura`,`montoItemsFactura`,
        `ivaTotalFactura`,`TotalTotalFactura`,`cantidad_items`,
        `totalizar_sub_total`,`totalizar_descuento_parcial`,`totalizar_total_operacion`,
        `totalizar_pdescuento_global`,`totalizar_descuento_global`,
        `totalizar_base_imponible`,`totalizar_monto_iva`,
        `totalizar_total_general`,`totalizar_total_retencion`,`fecha_creacion`,
        `usuario_creacion`,`cod_estatus`,`formapago`, `impresora_serial` 
        )
    VALUES(
        {$_POST["id_cliente"]}, '{$nro_factura}', '{$usuario_vende}', '{$_POST["input_fechaFactura"]}',
        {$_POST["input_subtotal"]}, {$_POST["input_descuentosItemFactura"]}, {$_POST["input_montoItemsFactura"]},
        {$_POST["input_ivaTotalFactura"]}, {$_POST["input_totalizar_total_general"]}, {$_POST["input_cantidad_items"]},
        {$_POST["input_totalizar_sub_total"]}, {$_POST["input_totalizar_descuento_parcial"]}, {$_POST["input_totalizar_total_operacion"]},
        {$_POST["input_totalizar_pdescuento_global"]}, {$_POST["input_totalizar_descuento_global"]},
        {$_POST["totalizar_base_imponible"]}, {$_POST["input_totalizar_monto_iva"]},
        {$_POST["input_totalizar_total_general"]}, {$_POST["totalizar_total_retencion"]}, CURRENT_TIMESTAMP,
        '{$usuario}', '{$cod_estatus}', '{$_POST["forma_pago"]}', '".impresora_serial."'
        );";
    $factura->ExecuteTrans($sql);
    $id_facturaTrans = $factura->getInsertID();

    // insertar sql en caja
  /*  $insert_modulo_caja = "INSERT INTO 
        `caja_ing_cob_sal_x_cli` 
            (`id_caja_ing_cob_sal_x_cli`, `fecha`, `comprobante`, `numero`, `saldo`,`monto`, `id_cliente`) 
        VALUES (NULL, '".date("Y-m-d")."', 'FACT', '{$nro_factura}', {$_POST["input_totalizar_total_general"]},0, {$_POST["id_cliente"]});";
    
    $factura->ExecuteTrans($insert_modulo_caja);*/

    /*
     * Codigo fuente añadido para la facturacion de pedidos, notas de entrega y cotizaciones.
     * Se cambia el status del documento mercantil respectivo que se facturara y se le asocia la factura
     */
    $tienePedido = $factura->ObtenerFilasBySqlSelect("SELECT * FROM pedido WHERE id_cliente= {$_GET["cod"]} AND cod_estatus = 1 AND id_factura = 0 AND id_pedido = {$_POST['pedido_seleccionado']};");
    $tieneNotaEntrega = $factura->ObtenerFilasBySqlSelect("SELECT * FROM nota_entrega WHERE id_cliente= {$_GET["cod"]} AND cod_estatus = 1 AND id_factura = 0 AND id_nota_entrega = {$_POST['nota_entrega_seleccionada']};");
    $tieneCotizacion = $factura->ObtenerFilasBySqlSelect("SELECT * FROM cotizacion_presupuesto WHERE id_cliente= {$_GET["cod"]} AND cod_estatus = 1 AND id_factura = 0 AND id_cotizacion = {$_POST['cotizacion_seleccionada']};");

    if ($tienePedido) {
        $factura->ExecuteTrans("UPDATE pedido SET cod_estatus = 2, id_factura = {$id_facturaTrans} WHERE id_cliente = {$_GET['cod']} AND id_pedido = {$_POST['pedido_seleccionado']};");
    }
    if ($tieneNotaEntrega) {
        $factura->ExecuteTrans("UPDATE nota_entrega SET cod_estatus = 2, id_factura = {$id_facturaTrans} WHERE id_cliente = {$_GET['cod']} AND id_nota_entrega = {$_POST['nota_entrega_seleccionada']};");
    }
    if ($tieneCotizacion) {
        $factura->ExecuteTrans("UPDATE cotizacion_presupuesto SET cod_estatus = 2, id_factura = {$id_facturaTrans} WHERE id_cliente = {$_GET['cod']} AND id_cotizacion = {$_POST['cotizacion_seleccionada']};");
    }
    if($_POST["fecha_vencimiento"] == "")
        $_POST["fecha_vencimiento"] = "NULL";
    else
        $_POST["fecha_vencimiento"] = "'".$_POST["fecha_vencimiento"]."'";
     $_POST["fecha_vencimiento"];   
    $SQLdetalle_formapago = "INSERT INTO factura_detalle_formapago (
        `id_factura` ,`totalizar_monto_cancelar` ,
        `totalizar_saldo_pendiente` ,`totalizar_cambio` ,`totalizar_monto_efectivo` ,
        `opt_cheque` ,`totalizar_monto_cheque` ,`totalizar_nro_cheque` ,
        `totalizar_nombre_banco` ,`opt_tarjeta` ,`totalizar_monto_tarjeta` ,
        `totalizar_nro_tarjeta` ,`totalizar_tipo_tarjeta` ,`opt_deposito` ,
        `totalizar_monto_deposito` ,`totalizar_nro_deposito` ,
        `totalizar_banco_deposito` ,`opt_otrodocumento` ,`totalizar_monto_otrodocumento` ,
        `totalizar_nro_otrodocumento` ,`totalizar_banco_otrodocumento` ,`fecha_vencimiento` ,
        `observacion` ,`persona_contacto` ,`telefono` ,`fecha_creacion` ,`usuario_creacion`)
    VALUES ({$id_facturaTrans}, '{$_POST["input_totalizar_monto_cancelar"]}',
            '{$_POST["input_totalizar_saldo_pendiente"]}', '{$_POST["input_totalizar_cambio"]}', '{$_POST["input_totalizar_monto_efectivo"]}',
            '{$_POST["opt_cheque"]}', '{$_POST["input_totalizar_monto_cheque"]}', '{$_POST["input_totalizar_nro_cheque"]}',
            '{$_POST["input_totalizar_nombre_banco"]}', '{$_POST["opt_tarjeta"]}', '{$_POST["input_totalizar_monto_tarjeta"]}',
            '{$_POST["input_totalizar_nro_tarjeta"]}', '{$_POST["input_totalizar_tipo_tarjeta"]}', '{$_POST["opt_deposito"]}',
            '{$_POST["input_totalizar_monto_deposito"]}', '{$_POST["input_totalizar_nro_deposito"]}',
            '{$_POST["input_totalizar_banco_deposito"]}', '{$_POST["opt_otrodocumento"]}',
            '{$_POST["totalizar_monto_otrodocumento"]}', '{$_POST["totalizar_nro_otrodocumento"]}',
            '{$_POST["totalizar_banco_otrodocumento"]}', '{$_POST["fecha_vencimiento"]}',
            '{$_POST["observacion"]}', '{$_POST["persona_contacto"]}', '{$_POST["telefono"]}', CURRENT_TIMESTAMP, '{$usuario}');";

    $factura->ExecuteTrans($SQLdetalle_formapago);

    $consulta = "INSERT INTO factura_impuestos (
            `id_factura` ,`totalizar_base_retencion` ,
            `totalizar_pbase_retencion` ,`totalizar_descripcion_base_retencion` ,
            `cod_impuesto_iva` , `totalizar_monto_iva2` ,`totalizar_monto_1x1000`,`usuario_creacion`,`fecha_creacion`)
        VALUES (
            '{$id_facturaTrans}', '{$_POST["totalizar_base_retencion"]}', '{$_POST["totalizar_pbase_retencion"]}',
            '{$_POST["totalizar_descripcion_base_retencion"]}', '{$_POST["cod_impuesto_iva"]}',
            '{$_POST["totalizar_monto_iva2"]}', '{$_POST["totalizar_monto_1x1000"]}', '{$usuario}', CURRENT_TIMESTAMP);";

    $factura->ExecuteTrans($consulta);



   /* $consulta = "INSERT INTO `factura_gasto` 
        (   `id_factura`, 
            `transporte_salida`,
            `transporte`, 
            `empaques`, 
            `seguro`, 
            `flete`, 
            `comisiones`, 
            `manejo`, 
            `otros`, 
            `total_fob_gasto`,
            `copias`
            ) 
    VALUES (
        {$id_facturaTrans}, 
        {$_POST["transpaso_salida"]}, 
        {$_POST["transporte"]}, 
        {$_POST["empaques"]}, 
        {$_POST["seguro"]}, 
        {$_POST["flete"]}, 
        {$_POST["comisiones"]}, 
        {$_POST["manejo"]}, 
        {$_POST["otros"]}, 
        {$_POST["total_fob_gatos"]},
        {$_POST["copias"]}
        );";
    $factura->ExecuteTrans($consulta);*/

    // tabla: factura_formato_salida
    /*$consulta = "
    INSERT INTO  `factura_formato_salida` (
        `id_factura` ,
        `tipo` ,
        `id_cliente` ,
        `via` ,
        `marca`
    )
    VALUES (
        {$id_facturaTrans}, 
        '{$_POST["forma_salida_tipo"]}',
        '{$_POST["forma_salida_id_cliente"]}',
        '{$_POST["forma_salida_via"]}',
        '{$_POST["forma_salida_marca"]}'
    );
    ";
    $factura->ExecuteTrans($consulta);
    */

    # Insertamos en la tabla de cuentas por cobrar la cabecera del asiento.
    $SQL_CXC = "INSERT INTO cxc_edocuenta (
            `id_cliente`, `documento`, `numero`, `monto`,
            `fecha_emision`, `observacion`, `vencimiento_fecha`,
            `vencimiento_persona_contacto`, `vencimiento_telefono`,
            `vencimiento_descripcion`, `usuario_creacion`, `fecha_creacion`, `marca`)
        VALUES (
            '{$_POST["id_cliente"]}', 'FAC', '{$nro_factura}',
            '{$_POST["input_totalizar_total_general"]}', '" . date("Y-m-d") . "',
            'FACTURA {$nro_factura}', '{$_POST["fecha_vencimiento"]}',
            '{$_POST["persona_contacto"]}', '{$_POST["telefono"]}',
            '{$_POST["observacion"]}', '{$usuario}', CURRENT_TIMESTAMP, '{$marca}');";

    $factura->ExecuteTrans($SQL_CXC);
    $id_cxc = $factura->getInsertID();

    $SQL_CXC_DET = "INSERT INTO cxc_edocuenta_detalle (
            `cod_edocuenta` ,`documento` ,`numero` ,
            `descripcion` ,`tipo` ,`monto`,
            `usuario_creacion`, `fecha_creacion`,`fecha_emision_edodet`)
        VALUES (
            '{$id_cxc}', 'PAGOxFAC', '{$nro_factura}R',
            'Factura {$nro_factura}', 'd', '{$_POST["input_totalizar_total_general"]}',
            '{$usuario}', CURRENT_TIMESTAMP, '" . date("Y-m-d") . "');";
    # Se inserta el detalle de la CxC en este caso el asiento del DEBITO.
    $factura->ExecuteTrans($SQL_CXC_DET);
    $cod_edocuenta_detalle = $factura->getInsertID();
    /**
     * Aumentamos el valor del correlativo del Pago o Abono de Factura.
     * $factura->ExecuteTrans("update correlativos set contador = '".$correlativos->getUltimoCorrelativo("cod_pago_o_abono",1)."' where campo = 'cod_pago_o_abono'");
     */
    /**
     * Obtenemos el siguiente numero de correlativo de Pago x Abono a Factura.
     * $cod_pago_o_abono = $correlativos->getUltimoCorrelativo("cod_pago_o_abono",0,"si","");
     */
    # Verificamos el pago fue completo, un abono o fue un credito
    $monto_cxc = 0;
    if ($_POST["totalizar_monto_cancelar"] > 0 && $_POST["totalizar_monto_cancelar"] <= $_POST["input_totalizar_total_general"]) {
        $monto_cxc = $_POST["totalizar_monto_cancelar"];
    } elseif ($_POST["totalizar_monto_cancelar"] > $_POST["input_totalizar_total_general"]) {
        # verificamos si el monto a cancelar es mayor al general a pagar
        $monto_cxc = $_POST["input_totalizar_total_general"];
    }
    $SQL_CXC_DET = "INSERT INTO cxc_edocuenta_detalle (
            `cod_edocuenta`, `documento`, `numero`, `descripcion`,
                `tipo`, `monto`, `usuario_creacion`,
            `fecha_creacion`, `fecha_emision_edodet`)
            VALUES (
            '{$id_cxc}', 'PAGOxFAC', '{$nro_factura}R', 'Pago Factura {$nro_factura}',
                'c', '{$monto_cxc}', '{$usuario}',
            CURRENT_TIMESTAMP, '{$_POST["input_fechaFactura"]}');";
    # Se inserta el detalle de la CxC en este caso el asiento del CREDITO.
    $factura->ExecuteTrans($SQL_CXC_DET);

    # SQL para generar el detalle de forma pago en la tabla de cxc_edocuenta_formapago.
    $SQL_cxc_formapago = "INSERT INTO cxc_edocuenta_formapago (
            `cod_edocuenta_detalle`, `totalizar_monto_cancelar`,
            `totalizar_saldo_pendiente`, `totalizar_cambio`, `totalizar_monto_efectivo`,
            `opt_cheque`, `totalizar_monto_cheque`, `totalizar_nro_cheque`,
            `totalizar_nombre_banco`, `opt_tarjeta`, `totalizar_monto_tarjeta`,
            `totalizar_nro_tarjeta`, `totalizar_tipo_tarjeta`, `opt_deposito`,
            `totalizar_monto_deposito`, `totalizar_nro_deposito`, `totalizar_banco_deposito`,
            `opt_otrodocumento`, `totalizar_monto_otrodocumento`, `totalizar_nro_otrodocumento`,
            `totalizar_banco_otrodocumento`, `fecha_creacion`, `usuario_creacion`)
        VALUES (
            '{$cod_edocuenta_detalle}', '{$_POST["input_totalizar_monto_cancelar"]}',
            '{$_POST["input_totalizar_saldo_pendiente"]}', '{$_POST["input_totalizar_cambio"]}', '{$_POST["input_totalizar_monto_efectivo"]}',
            '{$_POST["opt_cheque"]}', '{$_POST["input_totalizar_monto_tarjeta"]}', '{$_POST["input_totalizar_nro_cheque"]}',
            '{$_POST["input_totalizar_nombre_banco"]}', '{$_POST["opt_tarjeta"]}', '{$_POST["input_totalizar_monto_tarjeta"]}',
            '{$_POST["input_totalizar_nro_tarjeta"]}', '{$_POST["input_totalizar_tipo_tarjeta"]}', '{$_POST["opt_deposito"]}',
            '{$_POST["input_totalizar_banco_deposito"]}', '{$_POST["input_totalizar_nro_deposito"]}', '{$_POST["input_totalizar_banco_deposito"]}',
            '{$_POST["opt_otrodocumento"]}', '{$_POST["totalizar_banco_otrodocumento"]}', '{$_POST["totalizar_nro_otrodocumento"]}',
            '{$_POST["totalizar_banco_otrodocumento"]}', CURRENT_TIMESTAMP , '{$usuario}');";
    $factura->ExecuteTrans($SQL_cxc_formapago);
    # Insert en la tabla de impuestos
    # echo $_POST["cantidad_impuesto"]."<br>";
    for ($i = 1; $i <= (int) $_POST["cantidad_impuesto"]; $i++) {
        if ($_POST["cod_impuesto$i"] != "" && $_POST["totalizar_monto_retencion$i"] > 0 && $_POST["totalizar_monto_cancelar"] > 0 && $_POST["totalizar_monto_cancelar"] < $_POST["input_totalizar_total_general"]) {

            $base_imponible = $_POST["cod_tipo_impuesto$i"] == 1 ? $_POST["totalizar_monto_iva"] : $_POST["totalizar_base_imponible"];

            $detalle_tabla_impuesto = "INSERT INTO tabla_impuestos (
                    `id_documento`, `tipo_documento`, `numero_control_factura`,
                    `id_fiscal`, `id_cliente`, `cod_tipo_impuesto`, `cod_impuesto`,
                    `totalizar_pbase_retencion`, `totalizar_monto_retencion`, `totalizar_base_imponible`,
                    `totalizar_monto_exento`, `usuario_creacion`, `fecha_creacion`)
                VALUES (
                    '{$id_facturaTrans}', 'f', '{$_POST["numero_control_factura"]}', '{$_POST["id_fiscal"]}',
                    '{$_POST["id_cliente"]}', '{$_POST["cod_tipo_impuesto$i"]}', '{$_POST["cod_impuesto$i"]}',
                    '{$_POST["totalizar_pbase_retencion$i"]}', '{$_POST["totalizar_monto_retencion$i"]}',
                    '{$base_imponible}', '{$_POST["totalizar_monto_exento$i"]}',
                    '{$usuario}',CURRENT_TIMESTAMP);";
            $factura->ExecuteTrans($detalle_tabla_impuesto);

            //if($_POST["totalizar_monto_cancelar"]>0&&$_POST["totalizar_monto_cancelar"]<$_POST["input_totalizar_total_general"]){
            $SQL_CXC_DET2 = "INSERT INTO cxc_edocuenta_detalle (
                    `cod_edocuenta`, `documento`, `numero`, `descripcion`,
                    `tipo`, `monto`, `usuario_creacion`, `fecha_creacion`, `fecha_emision_edodet`)
                VALUES (
                    '{$id_cxc}', 'PAGOxFAC', '{$nro_factura}', 'Retenciones de Impuesto a Factura {$nro_factura}',
                    'c', '{$_POST["totalizar_monto_retencion$i"]}', '{$usuario}', CURRENT_TIMESTAMP, '{$_POST["input_fechaFactura"]}');";
            # Se inserta el detalle de la CxC en este caso el asiento de lDEBITO.
            $factura->ExecuteTrans($SQL_CXC_DET2);
            //}// FIN DEL IF DE NSERTAR DETALLE DE IMPUESTOS EN ESTADO DE CUENTA
        } // FIN DEL IF DE INSERTAR IMPUESTOS EN LA TABLA IMPUESTOS
    }

    $kardex_almacen_instruccion = "INSERT INTO kardex_almacen (
            `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
            `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`)
       VALUES (
            '2', '{$usuario}', 'Salida por Ventas',
            '{$_POST["input_fechaFactura"]}', '{$usuario}', CURRENT_TIMESTAMP,
            '{$_POST["estado_entrega"]}', '{$_POST["input_fechaFactura"]}');";

    $id_transaccion = 0;
    if (!$tienePedido && !$tieneNotaEntrega) {
        $almacen->ExecuteTrans($kardex_almacen_instruccion);
        $id_transaccion = $almacen->getInsertID();
    }

    $lineas = array();
    #$lineas_tfhka = array();

    $total = $_POST["input_totalizar_total_general"];
    $efectivo = $_POST["input_totalizar_monto_efectivo"];
    $cheque = $_POST["input_totalizar_monto_cheque"];
    $tarjeta = $_POST["input_totalizar_monto_tarjeta"];
    $deposito = $_POST["input_totalizar_monto_deposito"];
    $otro = $_POST["opt_otrodocumento"];

    for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {
        /*$detalle_item_instruccion = "
            INSERT INTO factura_detalle (
                `id_factura`, `id_item`,
                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                `fecha_creacion`, `_item_almacen`, 
                `_cantidad_bulto`,
                `_cantidad_bulto_kilos`, 
                `_unidad_empaque`,
                `_ganancia_item_individual`,
                `_porcentaje_ganancia`,
                `_totalm3`,
                `_totalft3`
                )
            VALUES (
                '{$id_facturaTrans}', '{$_POST["_item_codigo"][$i]}',
                '{$_POST["_item_descripcion"][$i]}', '{$_POST["_item_cantidad"][$i]}', '{$_POST["_item_preciosiniva"][$i]}',
                '{$_POST["_item_descuento"][$i]}', '{$_POST["_item_montodescuento"][$i]}', '{$_POST["_item_piva"][$i]}',
                '{$_POST["_item_totalsiniva"][$i]}', '{$_POST["_item_totalconiva"][$i]}', '{$usuario}',
                CURRENT_TIMESTAMP, '{$_POST["_item_almacen"][$i]}',
                '{$_POST["_cantidad_bulto"][$i]}',
                '{$_POST["_cantidad_bulto_kilos"][$i]}',
                '{$_POST["_unidad_empaque"][$i]}',
                '{$_POST["_ganancia_item_individual"][$i]}',
                '{$_POST["_porcentaje_ganancia"][$i]}',
                '{$_POST["_totalm3"][$i]}',
                '{$_POST["_totalft3"][$i]}'
                );";*/
                $detalle_item_instruccion = "
            INSERT INTO factura_detalle (
                `id_factura`, `id_item`,
                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                `fecha_creacion`, `_item_almacen`
                )
            VALUES (
                '{$id_facturaTrans}', '{$_POST["_item_codigo"][$i]}',
                '{$_POST["_item_descripcion"][$i]}', '{$_POST["_item_cantidad"][$i]}', '{$_POST["_item_preciosiniva"][$i]}',
                '{$_POST["_item_descuento"][$i]}', '{$_POST["_item_montodescuento"][$i]}', '{$_POST["_item_piva"][$i]}',
                '{$_POST["_item_totalsiniva"][$i]}', '{$_POST["_item_totalconiva"][$i]}', '{$usuario}',
                CURRENT_TIMESTAMP, '{$_POST["_item_almacen"][$i]}'
                );";
        $factura->ExecuteTrans($detalle_item_instruccion);

        $descrip_producto = strlen($_POST["_item_descripcion"][$i]) < 39 ? str_pad($_POST["_item_descripcion"][$i], 39) : substr($_POST["_item_descripcion"][$i], 0, 39);
        $item = $factura->ObtenerFilasBySqlSelect("SELECT cod_item, descripcion2 , seriales FROM item WHERE id_item = {$_POST["_item_codigo"][$i]}");
        $codigo_item = $item[0]['cod_item'];
        $cantidad = number_format($_POST["_item_cantidad"][$i], 2, ",", "");
        $precio = number_format($_POST["_item_preciosiniva"][$i], 2, ",", "");
        $iva = number_format($_POST["_item_piva"][$i], 2, ",", "");
        $descuento_item = number_format($_POST["_item_descuento"][$i], 2, ",", "");
        $seriales = $item[0]['seriales'];
        /* $espacios = 30 - (strlen($codigo_item) + strlen($cantidad));
          for ($j = 0; $j < $espacios; $j++) {
          $codigo_item .= " ";
          } */
        $codigo_item = str_pad($codigo_item, 30 - strlen($cantidad), " ", STR_PAD_RIGHT);
        #$linea_producto.=$descrip_producto . " " . $codigo_item . $cantidad . str_pad($precio, 12, ' ', STR_PAD_LEFT) . str_pad($iva, 7, ' ', STR_PAD_LEFT) . "\n";
        $lineas[$i] = array("descripcion" => $descrip_producto, "codigo" => $codigo_item, "cantidad" => $cantidad, "precio" => $precio, "iva" => $iva, "descuento_item" => $descuento_item);

        if ($item[0]['descripcion2'] != "") {# antes $descrip_producto[0]['descripcion2']
            #$linea_producto.=$item[0]['descripcion2'] . "\n"; # antes $descrip_producto[0]['descripcion2']
            #$lineas["descripcion2"] = $item[0]['descripcion2'];
        }
        #fwrite($archivo_spooler, $linea_producto);
        if (!$tienePedido && !$tieneNotaEntrega) {
            $kardex_almacen_detalle_instruccion = "
            INSERT INTO kardex_almacen_detalle (
                `id_transaccion` , `id_almacen_entrada` ,
                `id_almacen_salida` , `id_item` , `cantidad`)
            VALUES (
                '{$id_transaccion}', '', '{$_POST["_item_almacen"][$i]}',
                '{$_POST["_item_codigo"][$i]}', '{$_POST["_item_cantidad"][$i]}');";

            $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

            if ($_POST["estado_entrega"] == 'Entregado') {
                $campos = $factura->ObtenerFilasBySqlSelect("
                        SELECT cantidad FROM item_existencia_almacen
                        WHERE id_item  = '{$_POST["_item_codigo"][$i]}' AND cod_almacen = '{$_POST["_item_almacen"][$i]}' AND id_ubicacion='{$_POST["_item_ubicacion"][$i]}';");
                # Verificar que se esta facturando para no descontar de la existencia,
                # ya que el propio pedido desconto
                #if (!$tienePedido) {
                $factura->ExecuteTrans("
                    UPDATE item_existencia_almacen
                    SET cantidad = '" . ($campos[0]["cantidad"] - $_POST["_item_cantidad"][$i]) . "'
                    WHERE id_item  = '{$_POST["_item_codigo"][$i]}' AND cod_almacen = '{$_POST["_item_almacen"][$i]}' AND id_ubicacion='{$_POST["_item_ubicacion"][$i]}';");
                #}
                $factura->ExecuteTrans("DELETE FROM item_precompromiso WHERE cod_item_precompromiso = '{$_POST["_cod_item_precompromiso"][$i]}';");
            }
        }
        /* Funcionalidad adicionada como requisito especifico del CCP */
        if ($_POST["_id_cuota"][$i] != 'undefined') {
            $factura->ExecuteTrans("UPDATE `cuota_cliente` SET estatus = 1 WHERE id = {$_POST["_id_cuota"][$i]};");
            $cuota_generada = $factura->ObtenerFilasBySqlSelect("SELECT id_cuota_generada FROM `cuota_cliente` WHERE id = {$_POST["_id_cuota"][$i]};");
            $factura->ExecuteTrans("INSERT INTO `cuota_cliente_movimientos` (id_cuota_generada, id_cliente, tipo, monto) VALUES ({$cuota_generada[0]["id_cuota_generada"]}, {$_POST["id_cliente"]}, 1, {$_POST["_item_preciosiniva"][$i]})");
        }
    
        if($seriales==1)
        {
            $cantt=$_POST["_item_cantidad"][$i];
            
            if($kk==0)
            {
                //nuevo 17
                $correlativos = new Correlativos();
                $nro_correlativo = $correlativos->getUltimoCorrelativo("cod_despacho", 1, "si");
                 $factura->ExecuteTrans("insert into despacho (cod_despacho, id_factura, fecha_creacion, usuario, cantidad_item, estatus) values ('".$nro_correlativo."', '$id_facturaTrans', CURRENT_TIMESTAMP, '{$usuario}','$cantt',0)");
                    $id_despacho = $factura->getInsertID();              
                 $kk=1;
                 
            }
            else 
            {
                 $factura->ExecuteTrans("update despacho set cantidad_item=cantidad_item+$cantt where id='$id_despacho'");
            }
            
            for($ii=1; $ii<=$cantt; $ii++) 
            {
             $factura->ExecuteTrans("insert into despacho_detalle (id_despacho, id_item, item_descripcion) values ('$id_despacho', '{$_POST["_item_codigo"][$i]}', '{$_POST["_item_descripcion"][$i]}')");
            }   
        }
    }
    
    $parametros_impresora_fiscal = $parametros->ObtenerFilasBySqlSelect("SELECT tipo_facturacion, swterceroimp, impresora_marca, impresora_serial, porcentaje_impuesto_principal, iva_a, iva_b, iva_c, cod_almacen, precio_menor FROM parametros_generales;");

    /*
     * Consulta eliminada. Datos recibidos desde el formulario en factura_nueva.tpl
     * $datos_cliente = $factura->ObtenerFilasBySqlSelect("SELECT nombre, direccion, telefonos, rif FROM clientes WHERE id_cliente = {$_POST["id_cliente"]}");
     */
    #Si el tipo de facturacion es Fiscal
    if ($parametros_impresora_fiscal[0]['tipo_facturacion'] == 1) {
        if ($parametros_impresora_fiscal[0]['swterceroimp'] == 1) {
            /*
             * Comenzar a crear el archivo para el spooler:
             * Directorio para guardar el archivo Selectra.001
             * $directorio = "spooler/" podría ser utilizado como directorio de prueba
             * para ver el archivo generado antes de que sea accedido por la Spooler Fiscal.
             * Mientras que $directorio = "C:\FACTURAS\" es el directorio de produccion en Win
             */
            $directorio = "C:\FACTURAS\\";
            $nombre_archivo_spooler = "Selectra.001";
            $ruta = $directorio . $nombre_archivo_spooler;
            $archivo_spooler = fopen($ruta, "w");

            chmod($directorio, 0777);
            chmod($ruta, 0777);

            $cabecera = "FACTURA:    " . str_pad($nro_factura, 8, "0", STR_PAD_LEFT) . "\n";
            $cabecera.= "CLIENTE:    " . str_pad($_POST['nombre'], 35) . "\n";
            $cabecera.= "DIRECCION1: " . str_pad(strtoupper($_POST['direccion']), 35) . "\n";
            $cabecera.= "DIRECCION2:\n";
            $cabecera.= "TELEFONO:   {$_POST['telefonos']}\n";
            $cabecera.= "RIF/CI:     {$_POST['id_fiscal']}\n";
            $cabecera.= "DESCRIPCION                             CODIGO                    CANT      PRECIO    IVA\n";

            fwrite($archivo_spooler, $cabecera);
            #$linea_producto.=$descrip_producto . " " . $codigo_item . $cantidad . str_pad($precio, 12, ' ', STR_PAD_LEFT) . str_pad($iva, 7, ' ', STR_PAD_LEFT) . "\n";
            $detalles = "";
            foreach ($lineas as &$linea) {
                $detalles = $linea["descripcion"] . " " . $linea["codigo"] . $linea["cantidad"] . str_pad($linea["precio"], 12, ' ', STR_PAD_LEFT) . str_pad($linea["iva"], 7, ' ', STR_PAD_LEFT) . "\n";
                fwrite($archivo_spooler, $detalles);
            }
            $resumen = $obj_txt->formatearLineasDetallesPago("DESCUENTO:", number_format(($_POST["input_totalizar_pdescuento_global"] > 0 ? $_POST["input_totalizar_pdescuento_global"] : 0), 2, ",", "")) . " %\n"; #$linea_producto = $obj_txt->formatearLineasDetallesPago("DESCUENTO:", $obj_txt->formatearCantidadDecimales($_POST["input_totalizar_pdescuento_global"] > 0 ? $_POST["input_totalizar_pdescuento_global"] : 0)) . " %\n";
            $resumen.= $obj_txt->formatearLineasDetallesPago("TOTAL NETO:", number_format($_POST["totalizar_base_imponible"], 2, ",", "")) . "\n"; #$linea_producto.=$obj_txt->formatearLineasDetallesPago("TOTAL NETO:", $obj_txt->formatearCantidadDecimales($_POST["totalizar_base_imponible"])) . "\n";
            $resumen.= $obj_txt->formatearLineasDetallesPago("TOTAL CANCELADO:", number_format($_POST["totalizar_monto_cancelar"], 2, ",", "")) . "\n"; #$linea_producto.=$obj_txt->formatearLineasDetallesPago("TOTAL CANCELADO:", $obj_txt->formatearCantidadDecimales($_POST["totalizar_monto_cancelar"])) . "\n";
            $resumen.= $obj_txt->formatearLineasDetallesPago("EFECTIVO:", number_format($_POST["input_totalizar_monto_efectivo"], 2, ",", "")) . "\n"; #$linea_producto.=$obj_txt->formatearLineasDetallesPago("EFECTIVO:", $obj_txt->formatearCantidadDecimales($_POST["input_totalizar_monto_efectivo"])) . "\n";
            $resumen.= $obj_txt->formatearLineasDetallesPago("CHEQUES:", number_format($_POST["input_totalizar_monto_cheque"], 2, ",", "")) . "\n"; #$linea_producto.=$obj_txt->formatearLineasDetallesPago("CHEQUES:", $obj_txt->formatearCantidadDecimales($_POST["input_totalizar_monto_cheque"])) . "\n";
            $resumen.= $obj_txt->formatearLineasDetallesPago("TARJETA:", number_format($_POST["input_totalizar_monto_tarjeta"], 2, ",", "")) . "\n"; #$linea_producto.=$obj_txt->formatearLineasDetallesPago("TARJETA:", $obj_txt->formatearCantidadDecimales($_POST["input_totalizar_monto_tarjeta"])) . "\n";
            $resumen.= $obj_txt->formatearLineasDetallesPago("CREDITO:", number_format($_POST["input_totalizar_saldo_pendiente"], 2, ",", "")) . "\n"; #$linea_producto.=$obj_txt->formatearLineasDetallesPago("CREDITO:", $obj_txt->formatearCantidadDecimales($_POST["input_totalizar_saldo_pendiente"])) . "\n";
            $resumen.= "USUARIOS:         " . $login->getNombreApellidoUSuario() . "\n";
            $resumen.= "COMENTARIO1:      NO SE ACEPTAN DEVOLUCIONES DESPUES DE 24 HORAS \n";
            #$resumen.= "COMENTARIO2:      <ESCRIBA ALGO AQUI>\n";
            #$resumen.= "COMENTARIO2:      <ESCRIBA ALGO AQUI>\n";
            $resumen.= "DATOS PARA LAS  \"D E V O L U C I O N E S\"\n";
            $resumen.= "FACTDEVOL:        0000000000\n";
            $resumen.= "FECHADEVOL:       00/00/0000\n";
            $resumen.= "HORADEVOL:        00:00:00\n";
            $resumen.= "SERIALIMP:        {$parametros_impresora_fiscal[0]['impresora_serial']}\n";
            $resumen.= "COO-BEMATECH:     \n";

            fwrite($archivo_spooler, $resumen);
            fclose($archivo_spooler);
            /*
             * En este punto comienza el parseo de la BD (DBF) del Spooler Fiscal
             * para obtener los datos fiscales de la factura y almacenarlos en la tabla
             * factura de la BD de Selectra.
             */

            include ("../../libs/php/clases/spooler/SpoolerConfDB.php");
            $dbf = new SpoolerConfDB ();
            #$dbf->setDirDBF();
            $factura_fiscal = $dbf->obtenerUltimoRegistroDBF();
            $factura->ExecuteTrans("UPDATE factura SET cod_factura_fiscal = '{$factura_fiscal['NUMDOC']}', nroz = '{$factura_fiscal['NROZ']}', impresora_serial = '{$factura_fiscal['IMPSERIAL']}' WHERE id_factura = '{$id_facturaTrans}'");

            unset($cabecera, $detalles, $resumen, $archivo_spooler, $dbf);
        } elseif ($parametros_impresora_fiscal[0]['swterceroimp'] == 0) {
            //variable para verificar si la impresora imprimio
            $estadoImpresora=0;
            //variable con el error de la impresora (daniel)
            $erroImpresora=0;
            switch ($parametros_impresora_fiscal[0]['impresora_marca']) {
                case "hasar":
                    include ("../../libs/php/clases/hasar/HasarPHP.php");
                    $objHasar = new HasarPHP();

                    $NR = $parametros_impresora_fiscal[0]['impresora_serial'];
                    $objHasar->setSerial($NR);
                    $objHasar->setPort("p3");

                    $BS = $objHasar->BS;
                    $FS = $objHasar->FS;
                    $TH = $objHasar->TH;
                    $LF = $objHasar->LF;
                    $DF = $objHasar->DF;
                    $TD = $objHasar->TD;
                    $PI = $objHasar->PI;
                    $FD = "";

                    $fp = fopen($objHasar->file, "w");
                    // lo de abajo estaba comentado #$fp = fopen("C:\\Tools\\factura.txt", "w");
                    #$fp = fopen("C:\\Tools\\factura.txt", "w");
                    $write = fputs($fp, $BS . $FS . "1" . $LF);
                    $write = fputs($fp, $TH . $FS . "1" . $FS . "Ref. Interna: " . $nro_factura . $LF);
                    $write = fputs($fp, $DF . $FS . $_POST['nombre'] . $FS . $_POST['id_fiscal'] . $FS . $FD . $FS . $NR . $FS . $FS . $FS . $TD . $LF);

                    foreach ($lineas as $linea) {
                        #$linea["precio"] = (float) $linea["precio"];
                        #$linea["iva"] = (float) $linea["iva"];
                        $write = fputs($fp, $PI . $FS . trim($linea["descripcion"]) . $FS .
                                number_format($linea["cantidad"], 2, ".", "") . $FS .
                                str_replace(",", ".", $linea["precio"]) . $FS .
                                str_replace(",", ".", $linea["iva"]) . $FS .
                                "M" . $FS .
                                $linea["codigo"] . $LF);
                        /* if ($linea["descuento_item"] > 0) {
                          $d = explode(",", $linea["descuento_item"]);
                          $descuento = (string) $d[0] . $d[1];
                          $descuento = str_pad($descuento, 4, "0", STR_PAD_LEFT);
                          $string = "p-{$descuento}\n";
                          $write = fputs($fp, utf8_encode($string));
                          } */
                    }
                    $medio_pago = "";
                    $formas_pago_descripcion = array("efectivo" => 1, "debito" => 2, "credito" => 3, "cheque" => 4);
                    $pago_directo = true;
                    if ($total <= $efectivo || $total <= $cheque || $total <= $tarjeta || $total <= $deposito || $total <= $otro) {

                        if ($total <= $efectivo) {
                            $medio_pago = "1:" . number_format($efectivo, 2, ".", "");
                        } else if ($total <= $cheque) {
                            $medio_pago = "4:" . number_format($cheque, 2, ".", "");
                        } else if ($total <= $tarjeta) {
                            $medio_pago = "3:" . number_format($tarjeta, 2, ".", "");
                        }
                        $string = $medio_pago;
                    } else {
                        $pago_directo = false;
                        if ($efectivo > 0) {
                            $efectivo = number_format($_POST["input_totalizar_monto_efectivo"], 2, ".", "");
                            $medio_pago .= "efectivo:{$efectivo},";
                        }
                        if ($cheque > 0) {
                            $cheque = number_format($_POST["input_totalizar_monto_cheque"], 2, ".", "");
                            $medio_pago .= "cheque:{$cheque},";
                        }
                        if ($tarjeta > 0) {
                            $tarjeta = number_format($_POST["input_totalizar_monto_tarjeta"], 2, ".", "");
                            $medio_pago .= "credito:{$tarjeta},";
                        }
                        $string = $medio_pago;
                    }
                    $formas_pago = array();
                    if (!$pago_directo) {
                        $formas_pago = array();
                        $formas_pago = explode(",", $string);
                        array_pop($formas_pago);
                        foreach ($formas_pago as $key => $forma) {
                            $a = array();
                            $a = explode(":", $forma);
                            $write = fputs($fp, "D" . $FS . strtoupper($a[0]) . $FS . $a[1] . $FS . "T" . $FS . $formas_pago_descripcion[$a[0]] . $LF);
                        }
                    } else {
                        $formas_pago = explode(":", $medio_pago);
                        foreach ($formas_pago_descripcion as $key => $value) {
                            if ($value == $formas_pago[0]) {
                                $este = $key;
                            }
                        }
                        $write = fputs($fp, "D" . $FS . strtoupper($este) . $FS . $formas_pago[1] . $FS . "T" . $FS . $formas_pago[0] . $LF);
                    }
                    $write = fputs($fp, "E" . $LF);
                    $write = fputs($fp, "{" . $LF);
                    fclose($fp);

                    $datos = $objHasar->sendFileCmd();
                    $factura_hasar = explode("|", $datos);
                    $factura->ExecuteTrans("UPDATE factura SET cod_factura_fiscal = '{$factura_hasar[7]}' WHERE id_factura = '{$id_facturaTrans}'");

                    break;
                case "dascon":
                case "hka112":
                case "bixolon":
                    include ("../../libs/php/clases/tfhka/TfhkaPHP.php");
                    $itObj = new Tfhka();
                    /* include_once "../../libs/php/clases/tfhka/TFHKA_DemoPHP/ifphptfhka.php";
                      $itObj = new Ifphptfhka();
                      $itObj->my_ip = "169.254.219.213"; */

                    $i0 = explode(".", $parametros_impresora_fiscal[0]['iva_c']);
                    $index_0 = (string) $i0[0] . $i0[1];
                    $i1 = explode(".", $parametros_impresora_fiscal[0]['iva_a']);
                    $index_1 = (string) $i1[0] . $i1[1];
                    $i2 = explode(".", $parametros_impresora_fiscal[0]['porcentaje_impuesto_principal']);
                    $index_2 = (string) $i2[0] . $i2[1];
                    $i3 = explode(".", $parametros_impresora_fiscal[0]['iva_b']);
                    $index_3 = (string) $i3[0] . $i3[1];
                    $tasa_impuesto = array(/* Exento */$index_0 => " ", /* 8 */ $index_1 => '"', /* 12 */ $index_2 => "!", /* 22 */ $index_3 => "#");

                    $archivo = "C:\IntTFHKA\ArchivoFactura.txt";
                    $fp = fopen($archivo, "w");

                    $string = "";
                    $write = fputs($fp, $string);

                    $nombre = strlen(trim($_POST['nombre'])) <= 40 ? trim($_POST['nombre']) : substr(trim($_POST['nombre']), 0, 40);
                    $string = "iS*{$nombre}\n";
                    #$string .= "i00" . "" . "\n";
                    $string .= "iR*{$_POST['id_fiscal']}\n";
                    $direccion = $_POST['direccion'] != "" ? strlen(trim($_POST['direccion'])) <= 40 ? strtoupper(trim($_POST['direccion'])) : strtoupper(substr(trim($_POST['direccion']), 0, 39))  : "";
                    $string .= $direccion != "" ? "i01DIRECCION: {$direccion}\n" : "";
                    $telefono = trim($_POST['telefonos']);
                    $string .= $telefono != "" ? "i02TELEFONO: {$telefono}\n" : "";

                    $write = fputs($fp, utf8_encode($string));

                    foreach ($lineas as $linea) {

                        $p = explode(",", $linea["precio"]);
                        $precio = (string) $p[0] . $p[1];
                        $precio = str_pad($precio, 10, "0", STR_PAD_LEFT);

                        $cantidad = explode(",", $linea["cantidad"]);
                        $cantidad = str_pad((string) $cantidad[0], 5, "0", STR_PAD_LEFT) . str_pad((string) $cantidad[1], 3, "0", STR_PAD_RIGHT);
                        $cantidad = str_pad($cantidad, 8 - strlen($cantidad), "0", STR_PAD_LEFT);
                        /* echo "cantidad: " . $cantidad . "<br>"; */

                        #$descripcion = strlen($linea["descripcion"]) <= 40 ? $linea["descripcion"] : substr($linea["descripcion"], 0, 39);
                        $descripcion = trim($linea["descripcion"]);
                        /* echo "descripcion: " . $descripcion . "<br>"; */

                        $t = explode(",", $linea["iva"]);
                        $tasa = (string) $t[0] . $t[1];

                        $string = $tasa_impuesto[$tasa] . $precio . $cantidad . $descripcion . "\n";

                        $write = fputs($fp, utf8_encode($string));
                        #$itObj->sendCmdTcp(utf8_encode($tasa_impuesto[$tasa] . $precio . $cantidad . $descripcion));

                        if ($linea["descuento_item"] > 0) {
                            $d = explode(",", $linea["descuento_item"]);
                            $descuento = (string) $d[0] . $d[1];
                            $descuento = str_pad($descuento, 4, "0", STR_PAD_LEFT);
                            $string = "p-{$descuento}\n";
                            $write = fputs($fp, utf8_encode($string));
                            #$itObj->sendCmdTcp(utf8_encode("p-{$descuento}"));
                        }
                    }
                    if ($total <= $efectivo || $total <= $cheque || $total <= $tarjeta || $total <= $deposito || $total <= $otro) {
                        $medio_pago = "1"; /* Pago directo */
                        if ($total <= $efectivo) {
                            $medio_pago .= "01" . "\n";
                        } else if ($total <= $cheque) {
                            $medio_pago .= "05" . "\n";
                        } else if ($total <= $tarjeta) {
                            $medio_pago .= "10" . "\n";
                        } else if ($total <= $deposito) {
                            $medio_pago .= "08" . "\n";
                          } /*else if ($total <= $otro) {

                          } */
                        $string = $medio_pago;
                    } else {
                        /* Pago parcial */
                        $medio_pago = "";
                        if ($efectivo > 0) {
                            $efectivo = number_format($_POST["input_totalizar_monto_efectivo"], 2, ",", "");
                            $efectivo = explode(",", $efectivo);
                            $efectivo = (string) $efectivo[0] . $efectivo[1];
                            $efectivo = str_pad($efectivo, 12, "0", STR_PAD_LEFT);
                            $medio_pago .= "2" . "01" . $efectivo . "\n";
                        }
                        if ($cheque > 0) {
                            $cheque = number_format($_POST["input_totalizar_monto_cheque"], 2, ",", "");
                            $cheque = explode(",", $cheque);
                            $cheque = (string) $cheque[0] . $cheque[1];
                            $cheque = str_pad($cheque, 12, "0", STR_PAD_LEFT);
                            $medio_pago .= "2" . "05" . $cheque . "\n";
                        }
                        if ($tarjeta > 0) {
                            $tarjeta = number_format($_POST["input_totalizar_monto_tarjeta"], 2, ",", "");
                            /* $deposito = $_POST["input_totalizar_monto_deposito"];#number_format($_POST["input_totalizar_monto_deposito"], 2, ",", "");
                              $otro = $_POST["opt_otrodocumento"];#number_format($_POST["opt_otrodocumento"], 2, ",", ""); */
                            $tarjeta = explode(",", $tarjeta);
                            $tarjeta = (string) $tarjeta[0] . $tarjeta[1];
                            $tarjeta = str_pad($tarjeta, 12, "0", STR_PAD_LEFT);
                            $medio_pago .= "2" . "10" . $tarjeta . "\n";
                        }
                        if ($deposito > 0) {
                            //$tarjeta = number_format($_POST["input_totalizar_monto_tarjeta"], 2, ",", "");
                            $deposito = $_POST["input_totalizar_monto_deposito"];#number_format($_POST["input_totalizar_monto_deposito"], 2, ",", "");
                            /*  $otro = $_POST["opt_otrodocumento"];#number_format($_POST["opt_otrodocumento"], 2, ",", ""); */
                            $deposito = explode(",", $deposito);
                            $deposito = (string) $deposito[0] . $deposito[1];
                            $deposito = str_pad($deposito, 12, "0", STR_PAD_LEFT);
                            $medio_pago .= "2" . "08" . $deposito . "\n";
                        }
                        $string = $medio_pago;
                    }
                    $string = "3\n" . $string;
                    $write = fputs($fp, utf8_encode($string));
                    fclose($fp);

                    $lineas_escritas = $itObj->SendFileCmd($archivo);
                    $imprimio=$itObj->ImpresoraEstado();
                    if($imprimio!=0){
                        $erroImpresora=1;
                    }
                    #$itObj->sendCmdTcp(utf8_encode($string));

                    $itObj->UploadStatusCmd('S1');

                    include_once("../../libs/php/clases/tfhka/implementacion_tfhka.php");

                    $contenido_s1 = getStatusInformativo();
                    $nro_factura_fiscal = substr($contenido_s1, 21, 8);
                    $factura->ExecuteTrans("UPDATE factura SET cod_factura_fiscal = '{$nro_factura_fiscal}' WHERE id_factura = '{$id_facturaTrans}'");

                    unset($efectivo, $cheque, $tarjeta, $deposito, $otro, $lineas_escritas, $archivo, $write, $string, $fp, $medio_pago, $itObj, $lineas, $linea);
                    break;
                    
                    case "vmax":                  
                    $directorio = "C:\Elepos\Spooler\\";                   
                    $nombre_archivo_spooler = "files\selectrapos.txt";
                    $ruta = $directorio. $nombre_archivo_spooler;
                    
                    $archivo = fopen($ruta, "w");                   
                    chmod($directorio, 0777);
                    chmod($ruta, 0777);               
                    $cabecera = "<ABRIR_CF," .str_replace(",","",$_POST['nombre']). "," .$_POST['id_fiscal']. ">\n";
                  
                    fwrite($archivo, $cabecera);
                    $detalles = "";
                    foreach ($lineas as &$linea) {
                            if($linea["iva"]>0)
                            {
                                $iva=1;
                            }
                            else {
                                $iva=0; 
                            }
                        $descripcion = str_replace(",", "", $linea["descripcion"]);
                        $detalles = "<ITEM_CF," .strtoupper($descripcion). "," .(str_replace(",", ".", $linea["cantidad"])*1000). "," .str_replace(",", "", $linea["precio"]). "," .$iva . "," . "0" . "," . "0" . ">" . "\n";
                        fwrite($archivo, $detalles);
                    }
                    $resumen.= "<SUBTOTAL_CF>\n";
                    //$resumen.="<PAGO_CF," .number_format($_POST["totalizar_monto_cancelar"], 2, "", "") . ", EFECTIVO>\n";
                    
                    if ($total <= $efectivo || $total <= $cheque || $total <= $tarjeta || $total <= $deposito || $total <= $otro)
                    {
                        $medio_pago = ""; /* Pago directo */
                        if ($total <= $efectivo)
                        {
                            $medio_pago = "EFECTIVO";
                        }
                        else if ($total <= $cheque) 
                        {
                            $medio_pago = "CHEQUE";
                        }
                        else if ($total <= $tarjeta)
                        {
                            $medio_pago = "TARJETA";
                        }
                        else if ($total <= $deposito)
                        {
                            $medio_pago = "DEPOSITO";
                        } 
                        $string = $medio_pago;
                    } 
                    else 
                    {
                        /* Pago parcial */
                        $medio_pago1 = "";
                        $medio_pago2 = "";
                                $medio_pago3 = "";
                        if ($efectivo > 0) {
                            /*$efectivo = number_format($_POST["input_totalizar_monto_efectivo"], 2, ",", "");
                            $efectivo = explode(",", $efectivo);
                            $efectivo = (string) $efectivo[0] . $efectivo[1];
                            $efectivo = str_pad($efectivo, 12, "0", STR_PAD_LEFT);*/
                            $efectivo = $_POST["input_totalizar_monto_efectivo"];
                            $medio_pago1 = "EFECTIVO";
                        }
                        if ($cheque > 0) {
                            /*$cheque = number_format($_POST["input_totalizar_monto_cheque"], 2, ",", "");
                            $cheque = explode(",", $cheque);
                            $cheque = (string) $cheque[0] . $cheque[1];
                            $cheque = str_pad($cheque, 12, "0", STR_PAD_LEFT);*/
                            $cheque = $_POST["input_totalizar_monto_cheque"];
                            $medio_pago2 = "CHEQUE";
                        }
                        if ($tarjeta > 0) {
                            /*$tarjeta = number_format($_POST["input_totalizar_monto_tarjeta"], 2, ",", "");
                            $tarjeta = explode(",", $tarjeta);
                            $tarjeta = (string) $tarjeta[0] . $tarjeta[1];
                            $tarjeta = str_pad($tarjeta, 12, "0", STR_PAD_LEFT);*/
                            $medio_pago3 = "TARJETA";
                            $tarjeta = $_POST["input_totalizar_monto_tarjeta"];
                        }
                        if ($deposito > 0) {
                            /*$tarjeta = number_format($_POST["input_totalizar_monto_tarjeta"], 2, ",", "");
                            $tarjeta = explode(",", $tarjeta);
                            $tarjeta = (string) $tarjeta[0] . $tarjeta[1];
                            $tarjeta = str_pad($tarjeta, 12, "0", STR_PAD_LEFT);*/
                            $medio_pago4 = "DEPOSITO";
                            $deposito = $_POST["input_totalizar_monto_deposito"];
                        }
                        $string = $medio_pago;
                    }
                        
                    if($medio_pago!="")
                    {
                            $resumen.="<PAGO_CF," .number_format($total, 2, "", "") . ", ".$medio_pago.">\n";
                    }
                
                      if($medio_pago1!="")
                    {
                            $resumen.="<PAGO_CF," .number_format($efectivo, 2, "", "") . ", ".$medio_pago1.">\n";
                    }
                
                        if($medio_pago2!="")
                    {
                            $resumen.="<PAGO_CF," .number_format($cheque, 2, "", "") . ", ".$medio_pago2.">\n";
                    }
                    
                    if($medio_pago3!="")
                    {
                            $resumen.="<PAGO_CF," .number_format($tarjeta, 2, "", "") . ", ".$medio_pago3.">\n";
                    }

                    if($medio_pago4!="")
                    {
                            $resumen.="<PAGO_CF," .number_format($deposito, 2, "", "") . ", ".$medio_pago4.">\n";
                    }
                    $resumen.= "<CERRAR_CF>\n";
                    $resumen.= "<GAVETA_A>\n";
                   

                    fwrite($archivo, $resumen);
                    fclose($archivo);
                            $comando="Comando.bat";
                            exec($comando);                    
                    
                    break;
            }
        }
    }
    #$nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_factura", 1, "no");
    $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 1, "no");
    $factura->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_factura}' WHERE campo = 'cod_factura';");
    $nro_despacho1 = $correlativos->getUltimoCorrelativo("cod_factura", 1, "no");
    $factura->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_despacho1}' WHERE campo = 'cod_despacho';");

    /* foreach ($prefijos_iva as $k=>$value) {
      echo "array[{$k}]=>{$value}<br/>";
      }exit; */
    unset($sql, $SQLdetalle_formapago, $consulta, $SQL_CXC, $SQL_CXC_DET, $SQL_cxc_formapago, $detalle_tabla_impuesto, $SQL_CXC_DET2, $kardex_almacen_instruccion, $detalle_item_instruccion, $kardex_almacen_detalle_instruccion);
    $nro_factura -= 1;
    // if ($factura->errorTransaccion == 1) {
        $enlace_factura_pdf = "";
     //     switch($parametros_impresora_fiscal[0]['tipo_facturacion']){
     //      case 0:#Facturacion Sistema (PDF)
     //      $tipo_facturacion = 0;
     //      break;
     //      case 1:#Facturacion Fiscal
     //      $tipo_facturacion = 1;
     //      break;
     //      case 2:#Facturacion Formato Libre
     //      $tipo_facturacion = 2;
     //      break;
     //      } 
     //    $reporte = (($parametros_impresora_fiscal[0]['tipo_facturacion'] == 0 || $parametros_impresora_fiscal[0]['tipo_facturacion'] == 1) ? "rpt_factura_rapida_nueva.php" : "rpt_factura_formato_libre.php") . "?codigo={$formateo_nro_factura}";
     //    $reporte = ($tipo_facturacion == 0 ? "rpt_factura.php" : $tipo_facturacion == 2 ? "rpt_factura_formato_libre.php" : "") . "?codigo={$formateo_nro_factura}";
     //    $enlace_factura_pdf = "<br/><span style=\"text-align:center;\">Para Visualizar Factura <a href=\"#\" onclick=\"window.open(\'../../reportes/" . $reporte . "\');\">Click Aqu&iacute;</a></span>";
     //    echo $reporte;
     //    Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"/> Factura Generada Exitosamente con el <b>Nro. {$formateo_nro_factura}</b><br/><img src=\"../../libs/imagenes/monto.png\"/> <b>Monto Total: " . number_format($_POST["input_totalizar_total_general"], 2, ",", ".") . " </b><br/><img src=\"../../libs/imagenes/cancelar.png\"/> <b>Monto Cancelado: " . number_format($_POST["input_totalizar_monto_cancelar"], 2, ",", ".") . " </b><br/><img src=\"../../libs/imagenes/monto.png\"/><b>Monto Retenci&oacute;n: " . number_format($_POST["totalizar_total_retencion"], 2, ",", ".") . " </b><br/><img src=\"../../libs/imagenes/ico_view.gif\"/> <b><span style=\"color:red;\">Monto Pendiente: " . number_format($_POST["input_totalizar_saldo_pendiente"], 2, ",", ".") . " </span></b><br/><img src=\"../../libs/imagenes/cambio.png\"/> <b>Monto Cambio: " . number_format($_POST["input_totalizar_cambio"], 2, ",", ".") . " </b><br/>{$enlace_factura_pdf}<br/><br/></span>");
     // }
    //AQUI else 
    if ($factura->errorTransaccion == 0) {
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la factura.</span>");
    }
    $valorImpresora= 0;
    if($erroImpresora==1){
        $valorImpresora= 0;
    }else{
        $valorImpresora= 1;    
         $factura->CommitTrans($factura->errorTransaccion);
    }
    echo json_encode($valorImpresora);
    exit;
   




    // $url = sprintf("Location: ?opt_menu=%s&opt_seccion=%s&opt_subseccion=newfactura_rapida&cod=%s&layout=2", $_POST["opt_menu"],$_POST["opt_seccion"],$_POST["id_cliente"]);
    
    //$url = sprintf("Location: ../../reportes/" . $reporte);
    $url = sprintf("Location: ?opt_menu=5&opt_seccion=58&opt_subseccion=newfactura_rapida&cod=1&layout=2");
    header($url);
    exit;
} else {
    $id_cliente = $_GET["cod"];
   
   
    if (!isset($id_cliente)) {
        header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"]);
        exit;
    }
    $factura->Execute2("DELETE FROM item_precompromiso WHERE idSessionActualphp = '" . $login->getIdSessionActual() . "' and usuario_creacion = '{$usuario}'");

    $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 0, "si");
    $smarty->assign("nro_factura", $nro_factura);

    ##########################################################################################
    # Codigo añadido para obtener los pedidos, notas de entrega y cotizaciones asociados a
    # un cliente para facturar a partir del respectivo documento mercantil.
    $smarty->assign("pedidos", $factura->ObtenerFilasBySqlSelect("SELECT * FROM pedido WHERE id_cliente = {$id_cliente} AND cod_estatus = 1;"));
    $smarty->assign("notas_entrega", $factura->ObtenerFilasBySqlSelect("SELECT * FROM nota_entrega WHERE id_cliente = {$id_cliente} AND cod_estatus = 1;"));
    $smarty->assign("cotizaciones", $factura->ObtenerFilasBySqlSelect("SELECT * FROM cotizacion_presupuesto WHERE id_cliente = {$id_cliente} AND cod_estatus = 1;"));
    ##########################################################################################

    $datacliente = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM clientes WHERE id_cliente = {$id_cliente};");
   
   

    if (count($datacliente) == 0) {
       
        $pagina .= "<html>";
        $pagina .= "<body style=\"background-color:#f8f8f8\">";
        $pagina .= "<div style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius:8px;padding:5px;margin-left:20%;margin-right: 20%;margin-top:5%;font-size:13px; \">";
        $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion no esta permitida:</b> <br>";
        $pagina .= "<span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Verifique que el cliente al que desea facturar exista.</span><br>";
        $pagina .= "<hr><span style=\"color:#1e6602\">Para mas informaci&oacute;n contacte al administrador.</span>";
        if (count($campos) > 0)
            $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> " . $campos[0]["descripcion_optmenu"] . " - <b>SecciÃ³n:</b> " . $campos[0]["descripcion_optseccion"] . " >> <b>" . $campos[0]["opt_subseccion"] . ":</b> " . $campos[0]["descripcion"];
        $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"] . "'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
        $pagina .= "</div>";
        $pagina .= "</body>";
        $pagina .= "</html>";
         utf8_decode($pagina);
        exit;
    }

    //CARGAMOS EL COMBO cod_vendedor
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM vendedor ORDER BY nombre");
    foreach ($tprecio as $key => $item) {
        $valueSELECT[] = $item["cod_vendedor"];
        $outputSELECT[] = $item["nombre"];
    }
    $smarty->assign("option_values_vendedor", $valueSELECT);
    $smarty->assign("option_output_vendedor", $outputSELECT);
    $smarty->assign("option_selected_vendedor", $datacliente[0]["cod_vendedor"]);
    $smarty->assign("lista_vendedores", $tprecio);
    //vendedor
    $login = new Login();
    $smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());
    $smarty->assign("id_usuario", $login->getIdUsuario());

    
    //CARGAMOS EL COMBO cod_zona
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM zonas");
    foreach ($tprecio as $key => $item) {
        $valueSELECT[] = $item["cod_zona"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_zona", $valueSELECT);
    $smarty->assign("option_output_zona", $outputSELECT);
    $smarty->assign("option_selected_zona", $datacliente[0]["cod_zona"]);

    //CARGAMOS EL COMBO COD_TIPO_CLIENTE
    $valueSELECT = "";
    $outputSELECT = "";
    $tcliente = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cliente");
    foreach ($tcliente as $key => $item) {
        $valueSELECT[] = $item["cod_tipo_cliente"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_tipo_cliente", $valueSELECT);
    $smarty->assign("option_output_tipo_cliente", $outputSELECT);
    $smarty->assign("option_selected_tipo_cliente", $datacliente[0]["cod_tipo_cliente"]);

    //CARGAMOS EL COMBO COD_TIPO_PRECIO
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM tipo_precio");
    foreach ($tprecio as $key => $item) {
        $valueSELECT[] = $item["cod_tipo_precio"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_tipo_precio", $valueSELECT);
    $smarty->assign("option_output_tipo_precio", $outputSELECT);
    $smarty->assign("option_selected_tipo_precio", $datacliente[0]["cod_tipo_precio"]);

    //CARGAMOS EL COMBO contribuyente_especial
    $smarty->assign("option_values_contribuyente_especial", array(0, 1));
    $smarty->assign("option_output_contribuyente_especial", array("No", "Si"));
    $smarty->assign("option_selected_contribuyente_especial", $datacliente[0]["contribuyente_especial"]);

    //CARGAMOS EL COMBO calc_reten_impuesto_islr
    $smarty->assign("option_values_calc_reten_impuesto_islr", array(0, 1));
    $smarty->assign("option_output_calc_reten_impuesto_islr", array("No", "Si"));
    $smarty->assign("option_selected_calc_reten_impuesto_islr", $datacliente[0]["calc_reten_impuesto_islr"]);

    //CARGAMOS EL COMBO permitecredito
    $smarty->assign("option_values_permitecredito", array(0, 1));
    $smarty->assign("option_output_permitecredito", array("No", "Si"));
    $smarty->assign("option_selected_permitecredito", $datacliente[0]["permitecredito"]);

    $smarty->assign("datacliente", $datacliente);

    $datos_almacen = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM almacen");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_almacen as $key => $item) {
        $valueSELECT[] = $item["cod_almacen"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_almacen", $valueSELECT);
    $smarty->assign("option_output_almacen", $outputSELECT);
    $datos_item_forma = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM item_forma WHERE cod_item_forma in (1,2)");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_item_forma as $key => $item) {
        $valueSELECT[] = $item["cod_item_forma"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_item_forma", $valueSELECT);
    $smarty->assign("option_output_item_forma", $outputSELECT);

    $impuesto = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM tipo_impuesto");
    $smarty->assign("tipo_impuesto", $impuesto);

    $cantidadimpuesto = $clientes->ObtenerFilasBySqlSelect("select count(cod_tipo_impuesto) as cantidad_impuesto from tipo_impuesto");
    $smarty->assign("numero_impuesto", $cantidadimpuesto);

    $consulta = "select li.descripcion as descripcion,li.cod_impuesto as cod_impuesto,
        li.cod_tipo_impuesto as cod_tipo_impuesto
        from lista_impuestos as li
        left join tipo_impuesto as ti on li.cod_tipo_impuesto=ti.cod_tipo_impuesto WHERE li.cod_entidad=" . $datacliente[0]["cod_entidad"];
    $datos_impuesto = $clientes->ObtenerFilasBySqlSelect($consulta);
    $smarty->assign("dato_impuesto", $datos_impuesto);

    $datos_banco = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM banco ORDER BY descripcion");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_banco as $key => $item) {
        $valueSELECT[] = $item["cod_banco"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_banco", $valueSELECT);
    $smarty->assign("option_output_banco", $outputSELECT);

    $datos_instrumento_pago = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM instrumentopago_formapago WHERE cod_funcioninstrumento in ( 1,2) order by descripcion");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_instrumento_pago as $key => $item) {
        $valueSELECT[] = $item["cod_formapago"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_instrumento_pago_tarjeta", $valueSELECT);
    $smarty->assign("option_output_instrumento_pago_tarjeta", $outputSELECT);

    $datos_tipodocumento = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM instrumentopago_formapago ORDER BY descripcion");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_tipodocumento as $key => $item) {
        $valueSELECT[] = $item["cod_formapago"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_tipo_otrodocumento", $valueSELECT);
    $smarty->assign("option_output_tipo_otrodocumento", $outputSELECT);

    $sql = "SELECT cc.id, cc.id_cuota_generada, cc.id_cliente, cm.cuota_id, cm.id_item, cm.precio AS precio, cm.mes AS mes, cm.anio AS anio, c.descripcion\n"
            . "FROM cuota_cliente AS cc\n"
            . "INNER JOIN cuota_mes AS cm ON cc.id_cuota_generada = cm.id\n"
            . "INNER JOIN cuota AS c ON cm.cuota_id = c.id\n"
            . "WHERE cc.id_cliente = {$id_cliente} AND cc.estatus = 0;";
    $cuotas = $clientes->ObtenerFilasBySqlSelect($sql);
    #$cuotas = $clientes->ObtenerFilasBySqlSelect("SELECT cc.id, cc.id_cuota_generada, cc.id_cliente, cm.cuota_id, cm.id_item, cm.precio AS precio, cm.mes AS mes, cm.anio AS anio, c.descripcion FROM cuota_cliente AS cc INNER JOIN cuota_mes AS cm ON cc.id_cuota_generada = cm.id INNER JOIN cuota AS c ON cm.cuota_id = c.id WHERE cc.id_cliente = {$id_cliente} AND cc.estatus = 0;");
    $smarty->assign("tiene_cuotas", count($cuotas));
    $smarty->assign("cuotas", $cuotas);
}
$name_form = "factura";
$smarty->assign("name_form", $name_form);
$smarty->assign("idpreciolibre", codTipoPrecioLibre);
$smarty->assign("idprecio1", codTipoPrecio1);
$smarty->assign("idprecio2", codTipoPrecio2);
$smarty->assign("idprecio3", codTipoPrecio3);

$parametros_impresora_fiscal = $parametros->ObtenerFilasBySqlSelect("SELECT cod_almacen, precio_menor, id_ubicacion FROM parametros_generales;");

$smarty->assign("precio_por_defecto", $parametros_impresora_fiscal[0]['precio_menor']);
$smarty->assign("cod_almacen_defecto", $parametros_impresora_fiscal[0]["cod_almacen"]);
$smarty->assign("cod_ubicacion_defecto", $parametros_impresora_fiscal[0]["id_ubicacion"]);


//formato de salida
$valueSELECT = $outputSELECT = array("Transpaso", "Salida");
$smarty->assign("option_values_formato_salida_tipo", $valueSELECT);
$smarty->assign("option_output_formato_salida_tipo", $outputSELECT);


$valueSELECT = $outputSELECT = array("Terrestre", "Aerea", "Maritima");
$smarty->assign("option_values_formato_salida_via", $valueSELECT);
$smarty->assign("option_output_formato_salida_via", $outputSELECT);

$valueSELECT = $outputSELECT = array("Credito","Debito");
$smarty->assign("option_values_leyenda_tipo_condicion_pago", $valueSELECT);
$smarty->assign("option_output_leyenda_tipo_condicion_pago", $outputSELECT);


?>
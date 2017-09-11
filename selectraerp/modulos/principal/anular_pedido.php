<?php

include("../../libs/php/clases/DevolucionFactura.php");
include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/factura.php");
include("../../libs/php/clases/almacen.php");

$factura_devolucion = new DevolucionFactura();
$parametros = new ParametrosGenerales();
$clientes = new Clientes();
$factura = new Factura();
$almacen = new Almacen();

$consulta = "SELECT * FROM pedido p
    INNER JOIN pedido_detalle pd ON pd.id_pedido = p.id_pedido
    INNER JOIN clientes c ON c.id_cliente = p.id_cliente
    WHERE p.cod_pedido = '{$_GET["codigo"]}';";
$dataDevolucion = $factura_devolucion->ObtenerFilasBySqlSelect($consulta);

#$consulta = "SELECT * FROM pedido_detalle pd INNER JOIN item i ON i.id_item = pd.id_item WHERE pd.id_pedido = '" . $dataDevolucion[0]["id_pedido"] . "'";
$consulta = "SELECT * FROM pedido_detalle WHERE id_pedido = '{$dataDevolucion[0]["id_pedido"]}';";
$dataDetallePedido = $factura_devolucion->ObtenerFilasBySqlSelect($consulta);

$smarty->assign("dataDetalleFactura", $dataDetallePedido);

$parametros_generales = $parametros->ObtenerFilasBySqlSelect("SELECT nombre_impuesto_principal FROM parametros_generales;");
#$smarty->assign("cabecera",array("Seleccion","Codigo","Descripcion","Cantidad","Precio","Descuento","%","Total Sin IVA","I.V.A","Total con I.V.A"));
$smarty->assign("cabecera", array("C&oacute;digo", "Descripci&oacute;n", "Cantidad", "Precio", "Descuento", "%", "Total Neto", $parametros_generales[0]["nombre_impuesto_principal"], "Monto Total"));
$smarty->assign("dataDevolucion", $dataDevolucion);

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= " . $_GET["opt_seccion"]);
$smarty->assign("campo_seccion", $campos);

$smarty->assign("name_form","anular_pedido");

if (isset($_POST["anular"])) {
    if ($dataDevolucion[0]["cod_estatus"] == 1) {
        // si el usuario hizo post
        $factura_devolucion->BeginTrans();
        $factura->BeginTrans();
        $cod_estatus = "3";

        $factura->ExecuteTrans("UPDATE pedido SET cod_estatus = '3' WHERE id_pedido = " . $dataDevolucion[0]["id_pedido"]);

        $kardex_almacen_instruccion = "
		INSERT INTO kardex_almacen (
			`id_transaccion`, `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
			`fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`)
		VALUES (
			NULL, '3', '" . $login->getUsuario() . "', 'Entrada por Devolución de Pedido', CURRENT_TIMESTAMP,
                        '" . $login->getUsuario() . "', CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP);";
        $almacen->ExecuteTrans($kardex_almacen_instruccion);
        $id_transaccion = $almacen->getInsertID();
        #$factura_devolucion->MostarSQL();echo "<br>".$id_transaccion;exit;
        ////$consulta = "SELECT p.*, pd.*, i.*, ie.* FROM  pedido p INNER JOIN pedido_detalle pd ON pd.id_pedido = p.id_pedido INNER JOIN item i ON i.id_item = pd.id_item INNER JOIN item_existencia_almacen ie ON i.id_item = ie.id_item WHERE p.cod_pedido = '" . $_GET["codigo"] . "'";
        ////$dataDetallePedido = $factura_devolucion->ObtenerFilasBySqlSelect($consulta);
        $cantItems = $factura_devolucion->getFilas();
        #echo $cantItems;exit;

        for ($i = 0; $i < $cantItems; $i++) {

            $kardex_almacen_detalle_instruccion = "
            INSERT INTO kardex_almacen_detalle (
                `id_transaccion_detalle`, `id_transaccion`, `id_almacen_entrada`,
                `id_almacen_salida`, `id_item`, `cantidad`)
            VALUES (
                NULL , '" . $id_transaccion . "', '" . $dataDevolucion[$i]["_item_almacen"] . "',
                '" . $dataDevolucion[$i]["_item_almacen"] . "', '" . $dataDevolucion[$i]["id_item"] . "', '" . $dataDevolucion[$i]["_item_cantidad"] . "');";

            $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
#echo "transaccion: ".$id_transaccion." codigo:".$dataDetallePedido[$i]["id_item"]."alamacen: ".$dataDetallePedido[$i]["_item_almacen"]." cantidad: ".$dataDetallePedido[$i]["_item_cantidad"]."<br>";
            #if ($_POST["estado_entrega"] == 'Entregado') {
            $campos = $factura_devolucion->ObtenerFilasBySqlSelect("
                        select * from item_existencia_almacen
                                where id_item  = '" . $dataDevolucion[$i]["id_item"] . "' and
                        cod_almacen = '" . $dataDevolucion[$i]["_item_almacen"] . "'");

            #$cantidadExistente = $campos[0]["cantidad"];
            $factura_devolucion->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($campos[0]["cantidad"] + $dataDevolucion[$i]["_item_cantidad"]) . "'
        where id_item  = '" . $dataDevolucion[$i]["id_item"] . "' and cod_almacen = '" . $dataDevolucion[$i]["_item_almacen"] . "'");
            //$factura_devolucion->ExecuteTrans("delete from item_precompromiso where cod_item_precompromiso = '" . $_POST["_cod_item_precompromiso"][$i] . "'");
            #}
        }
#exit;
        if ($factura_devolucion->errorTransaccion == 1) {
            #Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Factura Generada Exitosamente con el <b>Nro. " . $formateo_nro_factura . "</b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: " . number_format($_POST["input_totalizar_total_general"], 2, ",", ".") . " </b><br><img src=\"../../libs/imagenes/cancelar.png\"> <b>Monto Cancelado: " . number_format($_POST["input_totalizar_monto_cancelar"], 2, ",", ".") . " </b><br><img src=\"../../libs/imagenes/monto.png\"><b>Monto Retencion: " . number_format($_POST["totalizar_total_retencion"], 2, ",", ".") . " </b><br><img src=\"../../libs/imagenes/ico_view.gif\"> <b><span style=\"color:red;\">Monto Pendiente: " . number_format($_POST["input_totalizar_saldo_pendiente"], 2, ",", ".") . " </span></b><br><img src=\"../../libs/imagenes/cambio.png\"> <b>Monto Cambio: " . number_format($_POST["input_totalizar_cambio"], 2, ",", ".") . " </b><br>Para imprimir la factura <!--a href=\"#\" onclick=\"window.open(\'../../reportes/rpt_factura.php?codigo=$formateo_nro_factura\');\">haga click aqui</a--></span>");
            Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Pedido " . $_GET["codigo"] . " Anulado Exitosamente </b></span>");
        }
        if ($factura_devolucion->errorTransaccion == 0) {
            Msg::setMessage("<span style=\"color:red;\">Error al Anular el Pedido.</span>");
        }
        $factura_devolucion->CommitTrans($factura->errorTransaccion);
        header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
        exit;
    } elseif ($dataDevolucion[0]["cod_estatus"] == 2) {
        header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"]."&cod_msj=2");
        #exit;
    } else {
        header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"]."&cod_msj=3");
        #exit;
    }
}
if (!isset($_GET["codigo"])) {
    header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"]);
    exit;
}
?>
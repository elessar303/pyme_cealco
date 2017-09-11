<?php
session_start();
ini_set("display_errors", 1);

require_once("../../../libs/php/adodb5/adodb.inc.php");
require_once("../../../libs/php/configuracion/config.php");
require_once("../../../libs/php/clases/ConexionComun.php");
require_once("../../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../../libs/php/clases/correlativos.php");
require_once "../../../libs/php/clases/numerosALetras.class.php";
include("../../../../menu_sistemas/lib/common.php");

if (isset($_GET["opt"]) == true || isset($_POST["opt"]) == true) {
    $conn = new ConexionComun();
    $login = new Login();
    $opt = (isset($_GET["opt"])) ? $_GET["opt"] : $_POST["opt"];

    switch ($opt) {
        case "eliminar_asientoCXP":
            $instruccion = "SELECT * FROM cxp_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);

            $instruccion = "delete from tabla_impuestos WHERE numero_control_factura = '" . $campos[0]["numero"] . "' and tipo_documento='c' and totalizar_monto_retencion='" . $campos[0]["monto"] . "'";
            $conn->Execute2($instruccion);

//,fecha_anulacion='".$_GET["fecha"]."',observacion_anulado='".$_GET["motivoAnulacion"]."'
            $instruccion = "update cxp_edocuenta_detalle set marca='',estado = '0',fecha_anulacion='" . $_GET["fecha"] . "',observacion_anulado='" . $_GET["motivoAnulacion"] . "' WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);

            $instruccion = "update cxp_edocuenta set marca = '' WHERE cod_edocuenta = " . $campos[0]["cod_edocuenta"];
            $conn->Execute2($instruccion);

            $instruccion = "delete from cxp_factura_pago WHERE cxp_edocuenta_detalle_fk = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);

            $instruccion = "delete from cxp_edocuenta_formapago WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);
//echo $instruccion;
            break;
        case "eliminar_asientoCXC":

            $instruccion = "SELECT * FROM cxc_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);

            $instruccion = "delete from cxc_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            echo $conn->Execute2($instruccion);

            $instruccion = "delete from tabla_impuestos WHERE numero_control_factura = '" . $campos[0]["numero"] . "' and tipo_documento='f' and totalizar_monto_retencion='" . $campos[0]["monto"] . "'";
            echo $conn->Execute2($instruccion);

            $instruccion = "update cxc_edocuenta set marca = '' WHERE cod_edocuenta = " . $campos[0]["cod_edocuenta"];
            echo $instruccion;
            $conn->Execute2($instruccion);

            break;
        case "impuestos":
            $instruccion = "SELECT * FROM lista_impuestos as li
            left join formulacion_impuestos as fi on li.cod_formula=fi.cod_formula
            WHERE cod_impuesto= '" . $_GET["cod_impuesto"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);
            $PORCENTAJE = $campos[0]["alicuota"];
            $PAGOMAYORA = $campos[0]["pago_mayor_a"];
            $MONTOSUSTRACCION = $campos[0]["monto_sustraccion"];
            $MONTOBASE = $_GET["monto_base"];
            $formula = $campos[0]["formula"];
            $resultado = eval($formula);
//alert($formula)
            $calculo = $_GET["monto_islr"] * $porcentaje;
            echo "[{'total_retencion':'" . $MONTO . "','porcentaje':'" . $campos[0]["alicuota"] . "','formula':'" . $campos[0]["formula"] . "','resultado':'" . $MONTO . "','codigo_impuesto':'" . $campos[0]["cod_impuesto"] . "','cod_tipo_impuesto':'" . $campos[0]["cod_tipo_impuesto"] . "'}]";
            break;
        case "impuesto_iva":
            $instruccion = "SELECT * FROM impuesto_iva WHERE cod_impuesto_iva = " . $_GET["cod_impuesto_iva"];
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);
            $calculo = $_GET["montoiva"] * ($campos[0]["porcentaje"] / 100);
            echo "[{'total_iva':'" . ($calculo) . "','porcentaje':'" . $campos[0]["porcentaje"] . "'}]";
            break;
        case "ValidarCodigoitem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE cod_item = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "DetalleCliente":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM clientes WHERE id_cliente = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : json_encode($campos);
            break;
        case "Detalleproveedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM proveedor WHERE id_proveedor = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : json_encode($campos);
            break;
        case "ValidarCodigoCliente":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM clientes WHERE cod_cliente = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "ValidarCodigoVendedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor WHERE cod_vendedor = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "ValidarNombreUsuario":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM usuarios WHERE usuario = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "Selectitem":
#$campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` AS i INNER JOIN `item_existencia_almacen` AS ie ON i.id_item = ie.id_item WHERE i.cod_item_forma` = '" . $_GET["v1"] . "' AND i.estatus = 'A' AND ie.cantidad>0");
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `cod_item_forma` = '" . $_GET["v1"] . "' and estatus = 'A' order by descripcion1 asc");
//SELECT * FROM `item` as i left join compra as c on c.id_proveedor=6 left join compra_detalle as cd on c.id_compra=cd.id_compra WHERE i.cod_item_forma = 1 and i.id_item)
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "Selectitemporproveedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `cod_item_forma` = '" . $_GET["v1"] . "' and estatus = 'A' order by descripcion1 asc");
//SELECT * FROM `item` as i left join compra as c on c.id_proveedor=6 left join compra_detalle as cd on c.id_compra=cd.id_compra WHERE i.cod_item_forma = 1 and i.id_item)
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "DetalleSelectitem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `id_item` = '" . $_GET["v1"] . "'");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "CargarAlmacenesDisponiblesByIdItem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_existenciabyalmacen WHERE id_item = '" . $_GET["v1"] . "' and cantidad > 0 order by cod_almacen");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "verificarExistenciaItemByAlmacen":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_precomprometidos WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "'");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "precomprometeritem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_precomprometidos
        WHERE id_item = '" . $_GET["v1"] . "' and cod_almacen = '" . $_GET["codalmacen"] . "'");

            $cantidadExistenteOLD = $campos[0]["cantidad"];
            $cantidadPedidad = $_GET["cpedido"];

            $cantidadExistenteNEW = $cantidadExistenteOLD - $cantidadPedidad;
            if ($cantidadExistenteNEW < 0) {
                echo "[{'id':'-99','observacion':'La cantidad Pedida es mayor a la existente.'}]";
                exit;
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE id_item = " . $_GET["v1"] . " and cod_item_forma = 1"); // 1: item producto
            if (count($campos) > 0) {
//if(strcmp($_GET["tipo_transaccion"],"presupuesto")){
#echo $_GET["tipo_transaccion"];exit;
                $sql = "INSERT INTO item_precompromiso (
                        `id_item_precomiso`, `cod_item_precompromiso`, `id_item`, `cantidad`, `usuario_creacion`,
                        `fecha_creacion`, `idSessionActualphp`, `almacen`)
                        VALUES (
                        NULL , '" . $_GET["codprecompromiso"] . "','" . $_GET["v1"] . "', '" . $_GET["cpedido"] . "', '" . $login->getUsuario() . "',
                        CURRENT_TIMESTAMP , '" . $login->getIdSessionActual() . "','" . $_GET["codalmacen"] . "');";
                $conn->Execute2($sql);
                echo "[{'id':'1','observacion':''}]";
//}
            } else {
                echo "[{'id':'-1','observacion':''}]";
            }
            break;
             case "agregarSeriales":
            
                $form=array();
                    //echo $_GET["formulario"];
                    $form=json_decode($_GET["formulario"],true);
                    $ii=0;
                    foreach ($form as $i => $input)
                    {
                        if($input[name]=="item")
                            $item=$input[value];
                                            
                        $cad="serial".$ii;
                
                        if($input[name]==$cad) 
                        {
                            $ii++;
                            //$tallas[]=$input[value];
                            $ser=$input[value];
                            $conn->ExecuteTrans("insert into item_serial_temp (id_producto, serial, estado, usuario_creacion,idsessionactual) values ('$item', '$ser', '1', '".$login->getUsuario()."', '".$login->getIdSessionActual()."')");
                        }
                    }      
                
            break;
        case 'seleccionarPedidoPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM pedido_detalle WHERE id_pedido = {$_GET["id_pedido"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        case 'seleccionarNotaEntregaPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM nota_entrega_detalle WHERE id_nota_entrega = {$_GET["id_nota"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        case 'seleccionarCotizacionPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM cotizacion_presupuesto_detalle WHERE id_cotizacion = {$_GET["id_cotizacion"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        case 'tipoFacturacion':
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT tipo_facuracion FROM parametros_generales");
            echo json_encode($campos);
            break;
        case "delete_precomprometeritem":
            $sql = "delete from item_precompromiso
                    WHERE cod_item_precompromiso = '" . $_GET["codprecompromiso"] . "'  and
                    idSessionActualphp = '" . $login->getIdSessionActual() . "'      and
                    usuario_creacion = '" . $login->getUsuario() . "' and id_item = '" . $_GET["v1"] . "'";
            $conn->Execute2($sql);
            break;
        case "det_edocuentacxp":
            $data_parametros = $conn->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
            foreach ($data_parametros as $key => $lista) {
                $valueSELECT[] = $lista["cod_empresa"];
                $outputidfiscalSELECT[] = $lista["moneda"];
            }
            $campos = $conn->ObtenerFilasBySqlSelect("
                SELECT *, vw_cxp.numero AS num_cdet, cxp_edocuenta.vencimiento_persona_contacto, cxp_edocuenta.vencimiento_telefono, cxp_edocuenta.vencimiento_descripcion
                FROM vw_cxp
                INNER JOIN cxp_edocuenta ON cxp_edocuenta.cod_edocuenta = vw_cxp.cod_edocuenta
                WHERE vw_cxp.cod_edocuenta = " . $_GET["cod_edocuenta"]);
            if (count($campos) == 0) {
                exit;
            }
            echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Documento</th>
                        <th style="border-bottom: 1px solid #949494;">N&uacute;mero</th>
                        <th style="border-bottom: 1px solid #949494;width:120px;">Fecha Emisi&oacute;n</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">Descripci&oacute;n</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Abonos/Pagos</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Deuda</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Opt</th>
                    </thead>
                    <tbody>';

            $acuDebitos = 0;
            $acuCreditos = 0;
            foreach ($campos as $key => $item) {
                if ($item["estado"] <> '0') {
                    echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">' . $item["cod_edocuenta_detalle"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:110px;">' . $item["documento_cdet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;">' . $item["num_cdet"] . '</td>
                            <td align="center" style="border-bottom: 1px solid #949494;width:120px;">' . $item["fecha_emision_edodet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:300px;">' . $item["descripcion"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;width:110px;">' . number_format($item['debito'], 2, ",", ".") . ' ' . $lista["moneda"] . ' </td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['credito'], 2, ",", ".") . ' ' . $lista["moneda"] . ' </td>
                            <td align="right" style="border-bottom: 1px solid #949494;">';

                    if ($key > 0) {
                        echo "<input type='hidden' id='detalle_asiento' name='detalle_asiento' value='" . $item["cod_edocuenta_detalle"] . "'>";
                        echo '<img onclick="javascript: guardarr(' . $item["cod_edocuenta_detalle"] . ')" style="cursor:pointer;" title="Eliminar Asiento" src="../../libs/imagenes/cancel.gif">';
                    }

                    echo '</td>
        </tr>';
                }
                $acuDebitos += $item['debito'];
                $acuCreditos += $item['credito'];
            }
            echo '
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;width:300px;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Total Pagos,Abonos/Deudas:</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuCreditos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Saldo Pendiente:</b></td>
                            <td colspan="2"align="right" style="border-bottom: 1px solid #949494;"><b style="color:red;">' . number_format($acuCreditos - $acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;">

                            </td>
                        </tr>
    ';

            if ($campos[0]["marca"] != "X") {
                echo '

                        <tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=85&opt_seccion=88&opt_subseccion=pagoabonoCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Agregar Pago/Abono</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
			<td colspan="7" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=85&opt_seccion=88&opt_subseccion=facturasCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Facturas/Notas de Credito</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
        ';
            }
            if ($campos[0]["marca"] == "X") {
                echo '

                        <tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;">
                        </td>
			<td colspan="7" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=85&opt_seccion=88&opt_subseccion=facturasCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Facturas/Notas de Credito</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
        ';
            }


            echo
            '</tbody>
    </table>
    </div>
    </td>
    </tr>';




            break;

        case "det_edocuenta":
            $data_parametros = $conn->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
            foreach ($data_parametros as $key => $lista) {
                $valueSELECT[] = $lista["cod_empresa"];
                $outputidfiscalSELECT[] = $lista["moneda"];
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *
,vw_cxc.numero as num_cdet
,cxc_edocuenta.vencimiento_persona_contacto,
cxc_edocuenta.vencimiento_telefono,
cxc_edocuenta.vencimiento_descripcion from vw_cxc
 inner join cxc_edocuenta on cxc_edocuenta.cod_edocuenta = vw_cxc.cod_edocuenta
WHERE vw_cxc.cod_edocuenta = " . $_GET["cod_edocuenta"]);
            if (count($campos) == 0) {
                exit;
            }
            echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div  style=" background-color:#fdfdfd; border: 1px solid #ededed;-moz-border-radius: 7px;padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px;  font-size: 13px; ">
                <table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Documento</th>
                        <th style="border-bottom: 1px solid #949494;">Numero</th>
                        <th style="border-bottom: 1px solid #949494;width:120px;">Fecha EmisiÃ³n</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">DescripciÃ³n</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Deuda</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Pago/Abono</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Opt</th>
                    </thead>
                    <tbody>';


            $acuDebitos = 0;
            $acuCreditos = 0;
            foreach ($campos as $key => $item) {
                echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">' . $item["cod_edocuenta_detalle"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:110px;">' . $item["documento_cdet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;">' . $item["num_cdet"] . '</td>
                            <td align="center" style="border-bottom: 1px solid #949494;width:120px;">' . $item["fecha_emision_edodet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:300px;">' . $item["descripcion"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['debito'], 2, ",", ".") . ' ' . $lista["moneda"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['credito'], 2, ",", ".") . ' ' . $lista["moneda"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">';
//if($item['debito']=="0.00"){
                if ($key > 0) {
                    echo '<img class="eliminarAsiento"  style="cursor:pointer;" title="Eliminar Asiento" src="../../libs/imagenes/cancel.gif">';
                    echo "<input type='hidden' id='detalle_asiento' name='detalle_asiento' value='" . $item["cod_edocuenta_detalle"] . "'>";
                }

                echo '</td>
        </tr>';

                $acuDebitos += $item['debito'];
                $acuCreditos += $item['credito'];
            }
            echo '
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;width:300px;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Total Deudas,Pagos/Abonos:</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuCreditos, 2, ",", ".") . '  ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Saldo Pendiente:</b></td>
                            <td colspan="2"align="right" style="border-bottom: 1px solid #949494;"><b style="color:red;">' . number_format($acuDebitos - $acuCreditos, 2, ",", ".") . '  ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="7" align="right" style="border-bottom: 1px solid #949494;width:300px;">

                            </td>
                        </tr>
    ';


            if ($campos[0]["marca"] != "X") {
                echo '<tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;width:110px;">
                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=6&opt_seccion=59&opt_subseccion=pagooabono&cod=' . $_GET["codigo_cliente"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Agregar Pago/Abono</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                            <br>
                            <img src="../../libs/imagenes/ico_user.gif"> Persona de Contacto: ' . $campos[0]["vencimiento_persona_contacto"] . '<br>
                            <img src="../../libs/imagenes/ico_cel.gif"> Telefono de Contacto: ' . $campos[0]["vencimiento_telefono"] . '<br>
                            <img src="../../libs/imagenes/ico_view.gif"> ObservaciÃ³n: ' . $campos[0]["vencimiento_descripcion"] . '<br>
                            <img src="../../libs/imagenes/ew_calendar.gif"> Fecha de Vencimiento: ' . $campos[0]["vencimiento_fecha"] . '<br>

                        </td>
                        </tr>
        ';
            }
            echo '</tbody></table></div></td></tr>';
            break;
        case "det_items":
            if ($_GET["id_tipo_movimiento_almacen"] == '3' || $_GET["id_tipo_movimiento_almacen"] == '1') {
                $operacion = "Entrada";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad AS cantidad_item
        FROM kardex_almacen_detalle AS kad JOIN kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen LEFT JOIN item AS ite ON kad.id_item=ite.id_item WHERE kad.id_transaccion = " . $_GET["id_transaccion"]);
            } else if ($_GET["id_tipo_movimiento_almacen"] == '4' || $_GET["id_tipo_movimiento_almacen"] == '2' || $_GET["id_tipo_movimiento_almacen"] == '8') {
                $operacion = "Salida";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion = " . $_GET["id_transaccion"]);
            } else if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                $operacion = "Traslado";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
        from kardex_almacen_detalle as kad left join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion = " . $_GET["id_transaccion"]);
                $campos1 = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion = " . $_GET["id_transaccion"]);
            }
//$campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
//from kardex_almacen_detalle as kad left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE id_transaccion = ".$_GET["id_transaccion"]);
//echo $campos;
            if (count($campos) == 0) {
                exit;
            }

            if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                echo '<tr class="detalle_items">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="width:110px; font-weight: bold; text-align: center;">ID</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n Entrada</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n Salida</th>
                        <th style="width:300px; font-weight: bold;">Item</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                    </thead>
                    <tbody>';
            } else {
                echo '<tr class="detalle_items">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="width:110px; font-weight: bold; text-align: center;">ID</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n ' . $operacion . '</th>
                        <th style="width:300px; font-weight: bold;">Item</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                    </thead>
                    <tbody>';
            }

            foreach ($campos as $key => $item) {
                if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["id_transaccion_detalle"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["descripcion"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["descripcion"] . '</td>
                            <td style="width:300px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                        </tr>';
                } else {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["id_transaccion_detalle"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["descripcion"] . '</td>
                            <td style="width:300px; padding-left:10px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                        </tr>';
                }
            }

            if ($campos[0]["estado"] == "Pendiente") {
                echo '<tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;width:110px;">
<br/><!--form>
<label for="fecha">Fecha</label><input type="text" name="fecha">
<label for="control">Nro. Control</label><input type="text" name="control">
<label for="factura">Nro. Factura</label><input type="text" name="factura"-->
<table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=3&opt_seccion=109&opt_subseccion=add&cod=' . $_GET["id_transaccion"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Realizar Entrada</td>
                                    <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                            <!--/form-->
                        </td>
                        </tr>';
            }
            echo
            '</tbody>
    </table>
    </div>
    </td>
    </tr>';

            break;

        case "getAlmacen":

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM almacen");

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;

        case 'listaCXPpendientes':
            header("Content-Type: text/plain");
            $groupByBeneficiario = isset($_POST["groupBene"]) ? 'si' : 'no';
            if ($groupByBeneficiario == "no") {
                $sql_ = "SELECT   pro.id_proveedor, cxpd. * , pro.descripcion AS beneficiario, pro.rif, cxpd.monto as monto_pagar, (
    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'd'
    ) AS sum_debito, (

    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'c'
    ) AS sum_credito, (
    (

    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'c'
    ) - (
    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'd' )
    ) AS monto_pendiente
    FROM cxp_edocuenta_detalle cxpd
    INNER JOIN cxp_edocuenta cxp ON cxpd.cod_edocuenta = cxp.cod_edocuenta
    INNER JOIN proveedores pro ON pro.id_proveedor = cxp.id_proveedor
    WHERE cxpd.marca = 'P'
    ";

                if (isset($_POST["id_proveedor"])) {
                    $sql_ .= " and  pro.id_proveedor = " . $_POST["id_proveedor"];
                }


                $campos = $conn->ObtenerFilasBySqlSelect($sql_);



                $start = isset($_POST['start']) ? $_POST['start'] : 0; //posiciÃ³n a iniciar
                $limit = isset($_POST['limit']) ? $_POST['limit'] : 30; //nÃºmero de registros a mostrar

                echo json_encode(array(
                    "success" => true,
                    "total" => count($campos),
                    "data" => array_splice($campos, $start, $limit)
                ));
            }

            if ($groupByBeneficiario == "si") {
                $sql_ = "SELECT  distinct  pro.id_proveedor, pro.descripcion AS beneficiario
FROM cxp_edocuenta_detalle cxpd
INNER JOIN cxp_edocuenta cxp ON cxpd.cod_edocuenta = cxp.cod_edocuenta
INNER JOIN proveedores pro ON pro.id_proveedor = cxp.id_proveedor
WHERE cxpd.marca = 'P'";


                $campos = $conn->ObtenerFilasBySqlSelect($sql_);

                echo json_encode(array(
                    "success" => true,
                    "total" => count($campos),
                    "data" => $campos
                ));
            }





            break;
        case "convertiraLetras":

            header("Content-Type: text/plain");

            $n = new numerosALetras();
            $numero = $_GET["monto"];
            $num_letras = $n->convertir($numero);

            $array = array(
                "success" => true,
                "monto" => $num_letras
            );
            echo json_encode($array);
            break;
        case "tesodetasientos":
            header("Content-Type: text/plain");
            $cod_cheque = $_POST["cod_cheque"];
            $sql_ = "
                SELECT cod_cheque_bauchedet, cod_cheque, descripcion, cuenta_contable,
                CASE tipo WHEN  'd' THEN monto ELSE  '' END AS debito,
                CASE tipo WHEN  'c' THEN monto ELSE  '' END AS credito
                FROM `cheque_bache_det` WHERE cod_cheque = {$cod_cheque} ORDER BY tipo DESC;";
            /* SELECT
              cod_cheque_bauchedet,
              cod_cheque,
              descripcion,
              cuenta_contable,
              case tipo when 'd' then monto else '' end as debito,
              case tipo when 'c' then monto else '' end as credito
              FROM `cheque_bache_det` WHERE cod_cheque = " . $cod_cheque . " order by tipo desc
              "; */
            $campos = $conn->ObtenerFilasBySqlSelect($sql_);
            echo json_encode(array(
                "success" => true,
                "total" => count($campos),
                "data" => $campos
            ));
            break;
        case "store_cuContable":
            header("Content-Type: text/plain");
// CONSULTA DE CUENTAS CONTABLES
            $global = new bd(SELECTRA_CONF_PYME);

            if (isset($_POST["query"])) {
                /* if ($_POST["query"] == "") {
                  $cuentalike = " order by cuenta";
                  } else {
                  $cuentalike = " and upper(concat(cuenta,' .-',Descrip)) like upper('%" . $_POST["query"] . "%') order by cuenta";
                  } */
                $cuentalike = ($_POST["query"] == "") ? " ORDER BY cuenta" : " AND UPPER (CONCAT(cuenta,' .-',Descrip)) LIKE UPPER('%{$_POST["query"]}%') ORDER BY cuenta";
            }
            $sentencia = "SELECT * FROM nomempresa WHERE bd = '{$_SESSION['EmpresaFacturacion']}';";
            $contabilidad = $global->query($sentencia);
            $fila = $contabilidad->fetch_assoc();
            $campos_cuentas_cont = $conn->ObtenerFilasBySqlSelect("SELECT CONCAT(cuenta,' .-',Descrip) AS descripcion, cuenta FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo = 'P'" . $cuentalike);
//echo "select cuenta as descripcion, cuenta from ".$fila['bd_contabilidad'].".cwconcue WHERE Tipo='P'".$cuentalike." order Cuenta";
            $campos_cuentas_cant = $conn->ObtenerFilasBySqlSelect("SELECT cuenta AS descripcion, cuenta FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo = 'P'" . $cuentalike);

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_cuentas_cant),
                "data" => $campos_cuentas_cont
            ));
            break;
        case "store_vendedores":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor ORDER BY nombre");
#$campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor WHERE nombre = '".$_GET["nombre_vendedor"]."'");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case "store_tipoCuenta":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cuenta_banco");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case "aCheBaucheDetCRUP":

            if ($_POST["cod_cheque_bauchedet"] != "" && $_POST["in_deleted"] != 1) {//UPDATIAR
                $sql = "
            update cheque_bache_det set
                        `monto` = " . $_POST["monto"] . ",
                        `tipo` = '" . (($_POST["tipo_a"] == "Debito") ? 'd' : 'c') . "',
                        `descripcion` = '" . $_POST["descripcion"] . "',
                        cuenta_contable = '" . $_POST["cuenta_contable"] . "'
           WHERE cod_cheque_bauchedet = " . $_POST["cod_cheque_bauchedet"];
                $conn->Execute2($sql);
            } elseif ($_POST["in_deleted"] == "1") {

                $sql = "delete from cheque_bache_det WHERE cod_cheque_bauchedet = " . $_POST["cod_cheque_bauchedet"];
                $conn->Execute2($sql);
            } else {//NUEVO ASIENTO CHEQUE BAUCHE DET
                $sql = "
            INSERT INTO `cheque_bache_det` (
                        `cod_cheque`,
                        `monto`,
                        `tipo`,
                        `fecha`,
                        `descripcion`,
                        `fecha_creacion`,
                        `usuario_creacion`,cuenta_contable)
                        VALUES (
                            " . $_POST["cod_cheque"] . ",
                            " . $_POST["monto"] . ",
                            '" . (($_POST["tipo_a"] == "Debito") ? 'd' : 'c') . "',
                            '" . date("Y-m-d") . "',
                            '" . $_POST["descripcion"] . "',
                            CURRENT_TIMESTAMP,
                            '" . $_SESSION['usuario'] . "',
                            '" . $_POST["cuenta_contable"] . "');";
                $conn->Execute2($sql);
            }

            echo json_encode(array(
                "success" => true,
                "msg" => "Asiento registrado exitosamente."
            ));


            break;
        case "listaProveedores":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("
    select
        id_proveedor,
        cod_proveedor,
        descripcion as  beneficiario,
        direccion,
        telefonos,
        fax,
        email,
        rif,
        nit
    from
        proveedores
	WHERE
	estatus='A'");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case 'movimientos_bancarios_conciliar':

            list($dia1, $mes1, $anio1) = explode("/", $_POST["fecha1_"]);
            list($dia2, $mes2, $anio2) = explode("/", $_POST["fecha2_"]);
            $fecha1 = $anio1 . "-" . $mes1 . "-" . $dia1;
            $fecha2 = $anio2 . "-" . $mes2 . "-" . $dia2;
            $cod_cuenta = $_POST["cod_cuenta"];
            $sql = "
SELECT
mb.cod_movimiento_ban,
mb.cod_tesor_bancodet,
tm.descripcion as tipo_movimiento_desc,
mb.numero_movimiento,
mb.fecha_movimiento,
mb.concepto,
case when mb.tipo_movimiento =  3 or mb.tipo_movimiento =  4 then mb.monto  else 0 end debe,
case when mb.tipo_movimiento  =  1 or mb.tipo_movimiento =  2 then mb.monto  else 0 end haber,
mb.tipo_movimiento,
mb.estado,
mb.cod_conciliacion,
'false' as conciliar
 FROM `movimientos_bancarios` mb inner join tipo_movimientos_ban tm
 on tm.cod_tipo_movimientos_ban = mb.tipo_movimiento
 WHERE mb.fecha_movimiento between '" . $fecha1 . "' and '" . $fecha2 . "'
 and mb.cod_tesor_bancodet = " . $cod_cuenta . "  and mb.cod_conciliacion is null
order by mb.cod_movimiento_ban";
            $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));

            break;

        case 'cxpIvaFactura':

            $MONTOBASE = $_GET[montoBase];
            $codIva = $_GET[codIva];

            $ivas = $conn->ObtenerFilasBySqlSelect("select li.alicuota, fi.formula from lista_impuestos li join formulacion_impuestos fi on (li.cod_formula=fi.cod_formula) WHERE li.cod_impuesto=$codIva");
            $PORCENTAJE = $ivas[0][alicuota];
            eval($ivas[0][formula]);
            echo $cad = $PORCENTAJE . '-' . $MONTO;
            break;

        case 'cxpRetIslrFactura':
            $par1 = $conn->ObtenerFilasBySqlSelect("select unidad_tributaria from parametros_generales");
            $id_item = $_GET[servicio];
            $cod_entidad = $_GET[entidad];
            $item_totalsiniva = $_GET[montoBase];
            $islr = $conn->ObtenerFilasBySqlSelect("select si.cod_lista_impuesto, fi.formula, li.alicuota, li.pago_mayor_a, li.monto_sustraccion, li.descripcion, li.cod_impuesto from servicios_islr si join lista_impuestos li on (si.cod_lista_impuesto=li.cod_impuesto) join formulacion_impuestos fi on (fi.cod_formula=li.cod_formula) WHERE si.cod_item=$id_item and li.cod_entidad=$cod_entidad and li.pago_mayor_a<$item_totalsiniva");
            if ($islr[0]) {
                $UT = $par1[0]["unidad_tributaria"];
                $FACTORSUST = $islr[0]["monto_sustraccion"];
                $FACTORM = $islr[0]["pago_mayor_a"];
                $PORCENTAJE = $islr[0]["alicuota"];
                $MONTOBASE = $item_totalsiniva;
                $formula = $islr[0]["formula"];
                eval($formula);

                echo number_format($MONTO, 2, ".", "");
            }
            else
                echo $cad = 0;
            break;

        case 'retencionesFactura':

            $codFacs = $_GET["facs"];

            $retenciones = $conn->ObtenerFilasBySqlSelect("SELECT cpf.cod_impuesto, li.descripcion, sum(cpf.monto_iva) as base, porcentaje_iva_retenido, sum(cpf.monto_retenido) as montoRet, sum(cpf.monto_exento) as exento, li.cod_tipo_impuesto FROM cxp_factura cpf JOIN lista_impuestos li ON ( li.cod_impuesto = cpf.cod_impuesto ) WHERE id_factura in ($codFacs) group by cpf.cod_impuesto");
            $reg = '';
            $i = 0;
            foreach ($retenciones as $key => $campos) {
                if ($campos[montoRet] > 0) {
                    $reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_iva_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
                    $i++;
                }
            }

            $retenciones2 = $conn->ObtenerFilasBySqlSelect("SELECT cpfd.cod_impuesto, li.descripcion, sum(cpfd.monto_base) as base, porcentaje_retenido, sum(cpfd.monto_retenido) as montoRet, li.cod_tipo_impuesto FROM cxp_factura_detalle cpfd JOIN lista_impuestos li ON ( li.cod_impuesto = cpfd.cod_impuesto ) WHERE id_factura_fk in ($codFacs) group by cpfd.cod_impuesto");
            foreach ($retenciones2 as $key => $campos) {
                $reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
                $i++;
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;

// 	case 'retencionesFactura':
//
// 		$codFacs=$_GET["facs"];
// 		$retenciones2=$conn->ObtenerFilasBySqlSelect("SELECT cpfd.cod_impuesto, li.descripcion, sum(cpfd.monto_base) as base, porcentaje_retenido, sum(cpfd.monto_retenido) as montoRet, li.cod_tipo_impuesto FROM cxp_factura_detalle cpfd JOIN lista_impuestos li ON ( li.cod_impuesto = cpfd.cod_impuesto ) WHERE id_factura_fk in ($codFacs) group by cpfd.cod_impuesto");
// 		foreach($retenciones2 as $key => $campos)
// 		{
// 			$reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
// 			$i++;
// 		}
// 		$reg.="*l*l*l*".$i;
// 		echo $reg;
// 	break;

        case 'anticipos':

            $edoCta = $_GET["edoCta"];
            $retenciones2 = $conn->ObtenerFilasBySqlSelect("SELECT * FROM cxp_edocuenta_detalle WHERE cod_edocuenta=$edoCta AND tipo='d' and cod_edocuenta_detalle not in (select cxp_edocuenta_detalle_fk from cxp_factura_pago) and marca in ('P','X')");
            $reg = '';
            $i = 0;
            foreach ($retenciones2 as $key => $campos) {
                $reg.="<tr><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='numero$i' id='numero$i' value='$campos[numero]'></TD><TD>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='monto$i' id='monto$i' value='$campos[monto]'></TD><TD><input name='optAnticipo{$i}' id='optAnticipo{$i}' type='checkbox' onchange='javascript:totalAnticipos();' value='{$i}'></TD></tr>";
                $i++;
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;

        case 'cambiarClave';
            $clave = $_GET["clave1"];
            $clave2 = $_GET["claveOLD"];

            $usuario = $login->getIdUsuario();
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM usuarios WHERE cod_usuario = '" . $login->getIdUsuario() . "' and
		 clave='" . $_GET["claveOLD"] . "'");

//echo "SELECT * FROM usuarios WHERE cod_usuario = '".$login->getIdUsuario()."' and
// clave='".$_GET["claveOLD"]."'";
//count($campos);
            if (count($campos) == 0) {
                echo "1";
            } else {
                /* echo "update usuarios set
                  `clave` = '".$_GET["clave1"]."'
                  WHERE cod_usuario = ".$login->getIdUsuario();
                 */
                $sql = "UPDATE usuarios SET `clave` = '" . $_GET["clave1"] . "' WHERE cod_usuario ='{$usuario}'";
                $conn->Execute2($sql);
//echo "-1";
            }
            break;
        case 'anularFactura';
            $idFac = $_GET["idFac"];
            $sql = "UPDATE cxp_factura SET cod_estatus = 2 WHERE id_factura = {$idFac};";
            $conn->Execute2($sql);
            break;
        case "eliminar_ordenCXP":
            $instruccion = "UPDATE cxp_edocuenta SET marca='A', fecha_anulacion='{$_GET["fechaOrden"]}', observacion_anulado='{$_GET ["motivoAnulacionOrden"]}' WHERE cod_edocuenta = '{$_GET["cod"]}'";
            $conn->Execute2($instruccion);
            /*
             * Modificado por: Charli Vivenes
             * Objetivo: incluir la eliminacion de las entradas en el inventario cuando se cancela la compra.
             * Desccripcion: se creo la tabla 'compra_kardex' para mantener la relacion entre el kardex y la compra
             *
             */
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id_kardex FROM compra_kardex WHERE id_compra = {$_GET["cod"]};");
            $campos2 = $conn->ObtenerFilasBySqlSelect("SELECT estado FROM kardex_almacen WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            $campos3 = $conn->ObtenerFilasBySqlSelect("SELECT * FROM kardex_almacen_detalle WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            $instruccion = "INSERT INTO kardex_almacen (tipo_movimiento_almacen, autorizado_por, observacion, fecha, usuario_creacion, fecha_creacion, estado, fecha_ejecucion)
                VALUES (8, 'Nadie', 'Salida por Devolucion Compra', '{$_GET["fechaOrden"]}', '{$_SESSION["usuario"]}', CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP);";
            $conn->ExecuteTrans($instruccion);
            $id_kardex_almacen = $conn->getInsertID();
            /*
             * En este punto decidi registrar el detalle de la devolución.
             * Por ello está comentada la condición que fue relegada al interior del foreach.
             */
#if ($campos2[0]["estado"] == "Entregado") {
            foreach ($campos3 as $key => $kardex_almacen_detalle) {
                $conn->ExecuteTrans("INSERT INTO kardex_almacen_detalle (id_transaccion_detalle, id_transaccion, id_almacen_entrada, id_almacen_salida, id_item, cantidad)
                    VALUES (NULL, '{$id_kardex_almacen}', '0', '{$kardex_almacen_detalle["id_almacen_entrada"]}', '{$kardex_almacen_detalle["id_item"]}', '{$kardex_almacen_detalle["cantidad"]}');");
                if ($campos2[0]["estado"] == "Entregado") {
                    $conn->ExecuteTrans("UPDATE item_existencia_almacen SET cantidad = cantidad - '{$kardex_almacen_detalle["cantidad"]}'
                    WHERE id_item  = '{$kardex_almacen_detalle["id_item"]}' AND cod_almacen = '{$kardex_almacen_detalle["id_almacen_entrada"]}';");
                }
            }
#}
            if ($campos2[0]["estado"] == "Pendiente") {
                $conn->ExecuteTrans("UPDATE kardex_almacen SET estado = 'Cancelado' WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            }
            break;
        case 'movimiento':
            $cliente = $_GET["cliente"];
//$movimiento=$conn->ObtenerFilasBySqlSelect("SELECT * FROM movimientos_bancarios  WHERE id_cliente=$cliente AND monto<>monto_aplicado");
            $movimiento = $conn->ObtenerFilasBySqlSelect("SELECT cod_movimiento_ban, numero_movimiento, concepto, (monto-(ifnull(monto_aplicado,0.00))) as monto, fecha_movimiento FROM movimientos_bancarios  WHERE id_cliente=$cliente and estatus IS NULL");
            $reg = '';
            $i = 0;
            if ($movimiento) {
                foreach ($movimiento as $key => $campos) {
                    $reg.="<tr><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='numerom$i' id='numerom$i' value='$campos[numero_movimiento]'><input type='hidden' name='codmov$i' id='codmov$i' value='$campos[cod_movimiento_ban]'></TD><TD>$campos[concepto]</TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='fechamov$i' id='fechamov$i' value='" . fecha($campos[fecha_movimiento]) . "'></TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montosss$i' id='montosss$i' value='$campos[monto]'></TD><TD><input name='optMov{$i}' id='optMov{$i}' type='checkbox' onchange='javascript:totalPagos();' value='{$i}'></TD></tr>";
                    $i++;
                }
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;
        case "insertar_cajas_operativas":
        $obtener_id=$conn->ObtenerFilasBySqlSelect("Select id from operaciones where libro_venta=-1 limit 1");
        $conn->Execute2("update operaciones set libro_venta='".$_POST['cajas_operativas']."' where id=".$obtener_id[0]['id']."");

        break;


case "abrir_cajas":

// si es -1 nunca entro a libro de ventas, si es un numero distinto de 0 es que falta cerrar libro y si es 0 todo fue bien
//pregunto si es -1 si es correcto es primera entrada a libro de venta debo preguntar cuantas cajas operativas
        $verificar = $conn->ObtenerFilasBySqlSelect("Select max(fecha) as fecha from operaciones where libro_venta=-1");
        if(isset($verificar[0]['fecha'])){


         echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                        <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:200px; align:left;">¿Indique La Cantidad De Cajas Operativas?:</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;"><input type="text" name="cajas_operativas" id="cajas_operativas"  class="form-text serialSec " style="width:100px; align:center"></th>
                        </thead>
                       
                    '; 
                }else{
                    // si no es -1 quiere decir q ya declaro cuantas cajas operativas y debe ir al proceso normal.
                    echo "1";
                }

        break;

case "insertar_libro_ventas":
        
         $obtener_serial=$conn->ObtenerFilasBySqlSelect("SELECT count(serial_impresora) as seriales from caja_impresora where serial_impresora='".$_POST['serial']."'");
         if($obtener_serial['0']['seriales']==0){
            echo -4;
            exit();
         }
         $obtenr_nuevo_money=$conn->ObtenerFilasBySqlSelect("select money as money from ".POS.".closedcash where host='".$_POST['caja']."'  and dateend is null");
       
        $conn->BeginTrans();

        $obtener_secuencia=$conn->ObtenerFilasBySqlSelect("SELECT max(b.HOSTSEQUENCE) as max_pos FROM ".POS.".closedcash as b WHERE b.HOST='".$_POST['caja']."' and b.dateend is not null");
        $campos2=$conn->ExecuteTrans("update cierre_caja_control set secuencia='".$obtener_secuencia[0]['max_pos']."', estatus_cierre=0 where cajas='".$_POST['caja']."' ");
    if($_POST['ultima_nc']==""){
        $_POST['ultima_nc']='NULL';
    }
    if($_POST['nro_ncs']==""){
        $_POST['nro_ncs']='NULL';
    }
        
        $campos = $conn->ExecuteTrans("INSERT INTO libro_ventas(
                serial_impresora,
                numero_z,
                numero_z_usuario,
               
              
                money
                ) 
            VALUES
                (
                '".$_POST['serial']."',
                '".$_POST['z']."',
                '".$_POST['z_usuario']."',
                '".$obtenr_nuevo_money[0]['money']."'
                )
                ");

            $update = $conn->ExecuteTrans("
            update libro_ventas set
           
            ultima_factura= '".$_POST['ultima_factura']."',
            numeros_facturas='".$_POST['nro_facturas']."',
            ultima_nc= ".$_POST['ultima_nc'].",
            numeros_ncs= ".$_POST['nro_ncs'].",
            fecha= '".$_POST['fecha']."',
            monto_bruto='".$_POST['monto_bruto']."',
            monto_exento= '".$_POST['monto_exento']."',
            base_imponible='".$_POST['base_imponible']."',
            iva=  '".$_POST['iva']."',
            monto_bruto_usuario='".$_POST['monto_bruto_usuario']."',
            monto_exento_usuario='".$_POST['monto_exento_usuario']."',
            base_imponible_usuario='".$_POST['base_imponible_usuario']."',
            iva_usuario='".$_POST['iva_usuario']."',
            fecha_emision= now(),
            id_usuario_creacion= '".$_SESSION['cod_usuario']."',
            secuencia= '".($obtener_secuencia[0]['max_pos']-1)."' 
            where money = '".$_POST['money']."'");
    

            //operaciones
            $verificar_limite=$conn->ObtenerFilasBySqlSelect("select id, libro_venta-1 as libros from operaciones order by fecha desc limit 1");
            if($verificar_limite[0]['libros']<0){
                echo "5"; exit();
            }
            $operaciones=$conn->ExecuteTrans(
                "UPDATE operaciones SET libro_venta = libro_venta-1 where id='".$verificar_limite[0]['id']."'"
                );


       
       $conn->CommitTrans(1);
       
 
       $verificar=1;
        if($verificar==1){
            echo 1; 
        }else{
            echo -1;
        };
        


        break;


case "formulario_libro_ventas":
        $serial =$conn->ObtenerFilasBySqlSelect("Select serial_impresora from caja_impresora where caja_host='".$_POST['caja']."'");
        $ultimo_z=$conn->ObtenerFilasBySqlSelect("select numero_z_usuario as z, money from libro_ventas where serial_impresora='".$serial[0]['serial_impresora']."' order by numero_z_usuario desc limit 1");
        
         if(!isset($ultimo_z[0]['z'])){
            $ultimo_z[0]['z']="";
            $ultimo_z[0]['money']="";
            }else{
                $ultimo_z[0]['z']=$ultimo_z[0]['z']+1;}
         echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px; align:left;">Serial Impresora</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Nro. Z</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Ultima Factura</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Nro. Facturas</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;" value=0>Ultima Nota Credito</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;" value=0>Nro. Notas De Credito</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Fecha</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Monto Bruto</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Monto Exento</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Base Imponible</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">IVA</th>
                    </thead>

                    <tbody>
                    <td align="center" ><input type="text" name="serial" id="serial"  class="form-text serialSec " required style="width:100px;" value='.$serial[0]['serial_impresora'].' readonly></td>
                    <td align="center" ><input  type="hidden" name="z" id="z"  class="form-text serialSec " style="width:40px;" value='.$ultimo_z[0]['z'].'><input  type="text" name="z_usuario" id="z_usuario"  class="form-text serialSec " style="width:40px;" value='.$ultimo_z[0]['z'].'></td>
                    <th align="center" ><input type="text" name="ultima_factura" id="ultima_factura"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name="nro_facturas" id="nro_facturas"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name="ultima_nc" id="ultima_nc"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name="nro_ncs" id="nro_ncs"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name=fecha id="fecha" readonly="readonly" class="form-text serialSec " required  style="width:80px; align:center"></th>

                    <script type="text/javascript">//<![CDATA[
                        var cal = Calendar.setup({
                            onSelect: function(cal) {
                                cal.hide();
                            }
                        });
                        cal.manageFields("fecha", "fecha", "%Y-%m-%d");
                    //]]>
                    </script>
                
                    <th align="center" ><input type="hidden" name="monto_bruto" id="monto_bruto" class="form-text serialSec " style="width:100px; align:center"><input type="text" name="monto_bruto_usuario" id="monto_bruto_usuario" onfocus=calcular();  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="monto_exento" id="monto_exento"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="monto_exento_usuario" id="monto_exento_usuario"  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="base_imponible" id="base_imponible"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="base_imponible"_usuarrio id="base_imponible_usuario"  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="iva" id="iva"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="iva_usuario" id="iva_usuario"  class="form-text serialSec " style="width:100px; align:center">
                    <input type="hidden" name="caja" id="caja"  value="'.$_POST['caja'].'"/>
                    <input type="hidden" name="money" id="money"  value="'.$ultimo_z[0]['money'].'"/>
                    <input type="hidden" name="cierres" id="cierres"  value="'.$_POST['cierres'].'"/>
                    </th>
                    </tbody>';



        break;





 case "guardar_arqueo":
        $_POST['efectivo']=0;
        $_POST['efectivo']+=$_POST['billete_100']*100;
        $_POST['efectivo']+=$_POST['billete_50']*50;
        $_POST['efectivo']+=$_POST['billete_20']*20;
        $_POST['efectivo']+=$_POST['billete_10']*10;
        $_POST['efectivo']+=$_POST['billete_5']*5;
        $_POST['efectivo']+=$_POST['billete_2']*2;
        $_POST['efectivo']+=$_POST['total_monedas'];
        $observacion="";
        if($_POST['efectivo']>$_POST['sistema']){
        $observacion="Existe un Sobrante de:".$_POST['efectivo']-$_POST['sistema'];    
        }else{
            if($_POST['efectivo']<$_POST['sistema']){
            $observacion="Existe un Faltante de:".$_POST['efectivo']-$_POST['sistema'];    
            }else{
                $observacion="Sin Observaciones";    
            }
        }
        
        $conn->BeginTrans();
        $obtener_fechas=$conn->ObtenerFilasBySqlSelect("select fecha_inicio, fecha_fin, secuencia from control_cierre_cajero where id_cajero='".$_POST['cajero']."' order by secuencia desc limit 1");
        $insert=$conn->Execute2("INSERT INTO arqueo_cajero(id_cajero, billetes_100, billetes_50, billetes_20, billetes_10, billetes_5, billetes_2, monedas, efectivo, tarjeta, tickets, total_sistema, fecha_arqueo, fecha_venta_ini, fecha_venta_fin, observaciones, cod_usuario) 
            VALUES
            (   '".$_POST['cajero']."',
                ".$_POST['billete_100'].",
                ".$_POST['billete_50'].",
                ".$_POST['billete_20'].",
                ".$_POST['billete_10'].",
                ".$_POST['billete_5'].",
                ".$_POST['billete_2'].",
                ".$_POST['total_monedas'].",
                '".$_POST['efectivo']."',
                '".$_POST['total_tarjeta']."',
                '".$_POST['total_ticket']."',
                '".$_POST['sistema']."',
                now(),
                '".$obtener_fechas[0]['fecha_fin']."',
                now(),
                '".$observacion."',
                '".$_SESSION["cod_usuario"]."'
                )
            ");

        $cambiar_estatus=$conn->Execute2("update  ".POS.".people set visible=0 where id='".$_POST['cajero']."'");
        $inserta_visible=$conn->Execute2("insert into ".POS.".people_caja (id_people) values('".$_POST['cajero']."')");
        $inserta_tabla_control=$conn->Execute2("insert into control_cierre_cajero (id_cajero,secuencia,fecha_inicio,fecha_fin)
         values('".$_POST['cajero']."', ".($obtener_fechas[0]['secuencia']+1).", '".$obtener_fechas[0]['fecha_fin']."', now() )");
        
         //operaciones
            $verificar_limite=$conn->ObtenerFilasBySqlSelect("select id, cierre_cajero-1 as cajeros from operaciones order by fecha desc limit 1");
            if($verificar_limite[0]['cajeros']<0){
                echo "5"; exit();
            }
            $operaciones=$conn->ExecuteTrans(
                "UPDATE operaciones SET cierre_cajero = cierre_cajero-1 where id='".$verificar_limite[0]['id']."'"
                );


        $conn->CommitTrans(1);
        $verificar=1;
        if($verificar==1){
            
            echo 1;
        }else{
            echo 2;
        }


        break;


 case "abrir_cajeros":

// si es -1 nunca entro a libro de ventas, si es un numero distinto de 0 es que falta cerrar cajero y si es 0 todo fue bien
//pregunto si es -1 si es correcto es primera entrada a cajero  debo preguntar cuantos cajeros abriran
        $verificar = $conn->ObtenerFilasBySqlSelect("Select max(fecha) as fecha from operaciones where cierre_cajero=-1");
        if(isset($verificar[0]['fecha'])){


         echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                        <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:200px; align:left;">¿Indique La Cantidad De Cajeros A Trabajar?:</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;"><input type="text" name="cajeros_activos" id="cajeros_activos"  class="form-text serialSec " style="width:100px; align:center"></th>
                        </thead>
                       
                    '; 
                }else{
                    // si no es -1 quiere decir q ya declaro cuantas cajas operativas y debe ir al proceso normal.
                    echo "1";
                }

        break;


  case "insertar_cajeros_activos":
        $obtener_id=$conn->ObtenerFilasBySqlSelect("Select id from operaciones where cierre_cajero=-1 limit 1");
        $conn->Execute2("update operaciones set cierre_cajero='".$_POST['cajeros_activos']."' where id=".$obtener_id[0]['id']);
       echo "1";
        break;

        case "filtroItem":
            /**
             * Procedimiento de busqueda de productos/servicios
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      lviera@armadillotec.com
             *      lviera86@gmail.com
             *
             */
            $tipo_item = (isset($_POST["cmb_tipo_item"])) ? $_POST["cmb_tipo_item"] : 1;

            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {
//filtro para productos
                if ($tipo_item == 1) {
                    $codigo = (isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "";
                    $codigo_barras = (isset($_POST["codigoBarrasProducto"])) ? $_POST["codigoBarrasProducto"] : "";
                    $descripcion = (!isset($_POST["descripcionProducto"])) ? "" : $_POST["descripcionProducto"];

                    $andWHERE = " and ";
                    if ($codigo != "") {
                        $andWHERE .= " upper(cod_item) like upper('%" . $codigo . "%')";
                    }
################################################################################
                    if ($codigo_barras != "") {
                        $andWHERE .= " upper(codigo_barras) like upper('%" . $codigo_barras . "%')";
                    }
################################################################################
                    if ($descripcion != "") {
                        if ($codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(descripcion1) like upper('%" . $descripcion . "%')";
                    }

                    if ($codigo == "" && $descripcion == "" && $codigo_barras == "") {
                        $andWHERE = "";
                    }
                }

//filtro para productos
                if ($tipo_item == 2) {
                    $codigo = (isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "";
                    $descripcion = (!isset($_POST["descripcionProducto"])) ? "" : $_POST["descripcionProducto"];

                    $andWHERE = " and ";
                    if ($codigo != "") {
                        $andWHERE .= " upper(cod_item) like upper('%" . $codigo . "%')";
                    }
                    if ($descripcion != "") {
                        if ($codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(descripcion1) like upper('%" . $descripcion . "%')";
                    }
                    if ($codigo == "" && $descripcion == "") {
                        $andWHERE = "";
                    }
                }
                $sql = "SELECT * FROM item WHERE cod_item_forma = " . $tipo_item . " " . $andWHERE;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);

                $sql = "SELECT * FROM item WHERE cod_item_forma = " . $tipo_item . " " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            } else {
                $sql = "SELECT * FROM item WHERE cod_item_forma = " . $tipo_item;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT * FROM item WHERE cod_item_forma = " . $tipo_item . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));

            break;
        case "agregar_factura":
            /**
             * Procedimiento de registro de facturas sin generacion de inventario
             *
             * Realizado por:
             * Charli J. Vivenes Rengel
             *
             * Correo:
             *      cvivenes@asys.com.ve
             *      cjvrinf@gmail.com
             *
             */
#$compra = new Compra();
            $correlativos = new Correlativos();

#$compra->BeginTrans();
            $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "si");

            $sql = "INSERT INTO `compra` (
              `id_compra`, `cod_compra`, `id_proveedor`, `cod_vendedor`,
              `fechacompra`, `montoItemscompra`, `ivaTotalcompra`, `TotalTotalcompra`, `monto_excento`,
              `cantidad_items`, `cod_estatus`, `fecha_creacion`, `usuario_creacion`,
              `responsable`, `centrocosto`, `num_factura_compra`, `num_cont_factura`)
              VALUES (
              NULL , '" . $nro_compra . "', '" . $_GET["id_proveedor"] . "', '',
              '" . $_GET["fecha_emision"] . "', '" . $_GET["subtotal_factura"] . "', '" . $_GET["iva_factura"] . "', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["exento_factura"] . "',
              '0', '1', CURRENT_TIMESTAMP , '" . $_GET["usuario"] . "',
              '" . $_GET["responsable"] . "', '', '" . $_GET["num_factura"] . "', '" . $_GET["num_control"] . "');";

            #$compra->ExecuteTrans($sql);
            $conn->ExecuteTrans($sql);

            $sql_cxp = "INSERT INTO cxp_edocuenta (
		`cod_edocuenta`, `id_proveedor`, `documento`,
		`numero`, `monto`, `fecha_emision`,
		`observacion`, `vencimiento_fecha`, `vencimiento_persona_contacto`,
		`vencimiento_telefono`, `vencimiento_descripcion`,
		`usuario_creacion`, `fecha_creacion`, `marca`)
                VALUES (
		NULL, '" . $_GET["id_proveedor"] . "', 'FACxCOM',
		'" . $nro_compra . "', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["fecha_emision"] . "',
		'Compra " . $nro_compra . "', '" . $_GET["fecha_vence"] . "', '',
		'', '' , '" . $_GET["usuario"] . "', '" . $_GET["fecha_emision"] . "', 'P');";

#$compra->ExecuteTrans($sql_cxp);
            $conn->ExecuteTrans($sql_cxp);
            $id_cxp = $conn->getInsertID();

            $SQL_cxp_DET = "INSERT INTO cxp_edocuenta_detalle (
		`cod_edocuenta_detalle`, `cod_edocuenta`, `documento`,
		`numero`, `descripcion`, `tipo`,
		`monto`, `usuario_creacion`, `fecha_creacion`,
		`fecha_emision_edodet`, `marca`)
                VALUES (
		NULL ,'" . $id_cxp . "','PAGOxCOM',
                '" . $nro_compra . "R','compra " . $nro_compra . "','c',
                '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "','" . $_GET["usuario"] . "', CURRENT_TIMESTAMP,
		'" . $_GET["fecha_emision"] . "','P');";
# Se inserta el detalle de la cxp en este caso el asiento del DEBITO.
#$compra->ExecuteTrans($SQL_cxp_DET);
            $conn->ExecuteTrans($SQL_cxp_DET);
            $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "no");
            $conn->ExecuteTrans("UPDATE correlativos SET contador = '" . $nro_compra . "' WHERE campo LIKE 'cod_compra'");

            $cod_impuesto = $alicuota = $monto_retenido = 0;
            if ($_GET["retencion_iva"]) {
                $cod_impuesto = $_GET["cod_impuesto"];
                $alicuota = $_GET["alicuota"];
                $monto_retenido = $_GET["iva_factura"] * $alicuota / 100;
            }
#$sql_tipo_impuesto;
//responsable='+responsable+'&num_factura='++'&='+num_control+'&='+exento_factura+'&subtotal_factura='+subtotal_factura+'&='+base_factura+'&iva_factura='+iva_factura+'&='+fecha_emision+'&fecha_vence='+fecha_vence+'&id_proveedor='+id_proveedor+'&usuario='+usuario,
            $sql_cxp_factura = "INSERT INTO cxp_factura (
                    id_factura, cod_factura, cod_cont_factura, id_cxp_edocta, fecha_factura, fecha_recepcion,
                    monto_base, monto_exento, anticipo, monto_total_con_iva, monto_total_sin_iva,
                    cod_impuesto, porcentaje_iva_mayor, monto_iva, porcentaje_iva_retenido, monto_retenido,
                    total_a_pagar, cod_estatus, fecha_pago, fecha_creacion, usuario_creacion,
                    tipo, factura_afectada, libro_compras, cod_correlativo_iva, cod_correlativo_islr)
                VALUES (
                    NULL, '" . $_GET["num_factura"] . "', '" . $_GET["num_control"] . "', '" . $id_cxp . "', '" . $_GET["fecha_emision"] . "', '" . $_GET["fecha_emision"] . "',
                    '" . $_GET["base_factura"] . "', '" . $_GET["exento_factura"] . "', '0', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["subtotal_factura"] . "',
                    '" . $cod_impuesto . "', 12, '" . $_GET["iva_factura"] . "', " . $alicuota . ", " . $monto_retenido . ",
                    '" . ($_GET["subtotal_factura"] + $_GET["iva_factura"] - $monto_retenido) . "', '1', '', CURRENT_TIMESTAMP, '" . $_GET["usuario"] . "',
                    'FAC', '" . $_GET["num_factura"] . "', '{$_GET["libro_compras"]}', '', '')";
            $conn->ExecuteTrans($sql_cxp_factura);

            $id_cxp_factura = $conn->getInsertID();
            $sql_cxp_factura_det = "INSERT INTO cxp_factura_detalle (
                    id_factura, id_factura_fk, monto_base, porcentaje_retenido, cod_impuesto, monto_retenido, id_item)
                VALUES (
                    NULL, '" . $id_cxp_factura . "', '" . $_GET["base_factura"] . "', '" . $_GET["alicuota"] . "', '', '')";
            $conn->ExecuteTrans($sql_cxp_factura_det);
            break;
    }
}
?>

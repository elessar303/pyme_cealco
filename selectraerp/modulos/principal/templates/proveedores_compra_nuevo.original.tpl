<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_compra.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formCOMPRAS.js"></script>
    </head>
    <body>
        <div id="loading" style="position:absolute; width:80%; text-align:center; top:180px; visibility:hidden;">
            <!--img src="../../../includes/imagenes/36.gif"/-->
        </div>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="Datosproveedor" value=""/>
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <table style="width:100%">
                <tr class="row-br">
                    <td>
                        <table style="width:100%;" class="tb-tit">
                            <tbody>
                                <tr>
                                    <td style="width:900px;"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <!--<Datos del proveedor y vendedor>-->
            <div id="dp" class="x-hide-display">
                <img align="absmiddle" src="../../../includes/imagenes/ico_user.gif"/>
                <span style="font-family:'Verdana';"><b>Proveedor:&nbsp;</b>{$dataproveedor[0].descripcion}</span>
                <span style="font-family:'Verdana';"><b>RIF:&nbsp;</b>{$dataproveedor[0].rif}</span>
                <input type="hidden" name="id_proveedor" value="{$dataproveedor[0].id_proveedor}"/>
                <br/>
                <!--img align="absmiddle" src="../../../includes/imagenes/ico_user.gif">
                <span style="font-family:'Verdana';"><b>Contacto:</b></span>
                <select name="cod_vendedor" id="cod_vendedor">{html_options output=$option_output_vendedor values=$option_values_vendedor selected=$option_selected_vendedor}</select-->
            </div>
            <!--</Datos del proveedor y vendedor>-->
            <div id="dcompra" class="x-hide-display">
                <table>
                    <tr>
                        <td>
                            <img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"/>
                            <span style="font-family:'Verdana';"><b>Responsable (*)</b></span>
                        </td>
                        <td>
                            <input type="text" maxlength="70" name="responsable" id="responsable" value="{$responsable}"/>
                        </td>
                    </tr>
                    <!---->
                    <tr>
                        <td>
                            <img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/03.png"/>
                            <span style="font-family:'Verdana';"><b>N&uacute;mero Factura</b></span>
                        </td>
                        <td>
                            <input type="text" name="num_factura" maxlength="70" id="num_factura"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/03.png"/>
                            <span style="font-family:'Verdana';"><b>N&uacute;mero Control</b></span>
                        </td>
                        <td>
                            <input type="text" name="num_cont_factura" maxlength="70" id="num_control_factura"/>
                        </td>
                    </tr><!---->
                    <tr>
                        <td>
                            <img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/fecha.png"/>
                            <span style="font-family:'Verdana';"><b>Fecha Factura</b></span>
                        </td>
                        <td>
                            <!-- fecha_emision en la tabla cxp_edocuenta-->
                            <!-- fechacompra en la tabla compra-->
                            <input type="text" name="input_fechacompra" id="input_fechacompra" />
                            <!--input type="hidden" name="fechacompra" id="fechacompra" value='{$smarty.now|date_format:"%d-%m-%Y"}' size="20" />
                            <div style="color:#4e6a48" id="fechacompra">{$smarty.now|date_format:"%d-%m-%Y"}</div-->
                            {literal}
                                <script type="text/javascript">//<![CDATA[
                                    var cal = Calendar.setup({
                                            onSelect: function(cal) { cal.hide() }
                                    });
                                    cal.manageFields("input_fechacompra", "input_fechacompra", "%Y-%m-%d");
                                    //]]>
                                </script>
                            {/literal}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"/>
                            <span style="font-family:'Verdana';"><b>Estado de Entrega</b></span>
                        </td>
                        <td>
                            <select name="estado_entrega" id="estado_entrega">
                                <option value="Entregado">Entregado</option>
                                <option value="Pendiente">Pendiente</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="PanelGeneralCompra">
                <div id="tabproducto" class="x-hide-display">
                    <div id="contenedorTAB">
                        <div id="div_tab1">
                            <div class="grid">
                                <table style="width: 100%" class="lista">
                                    <thead>
                                        <tr>
                                            <th class="tb-tit">C&oacute;digo</th>
                                            <th class="tb-tit">Descripci&oacute;n</th>
                                            <th class="tb-tit">Cantidad</th>
                                            <th class="tb-tit" title="Precio">Precio</th>
                                            <th class="tb-tit">Total</th>
                                            <th class="tb-tit">Opc.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="sf_admin_row_1">
                                            <td colspan="4">
                                                <div class="span_cantidad_items"><span style="font-size: 10px;">Cantidad de Items: 0</span></div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tabservicio" class="x-hide-display">
                    <div id="contenedorTAB">
                        <div id="div_tab">
                            <div class="grid2">
                                <table width="100%" class="lista2">
                                    <thead>
                                        <tr>
                                            <th class="tb-tit">C&oacute;digo</th>
                                            <th class="tb-tit">Descripci&oacute;n</th>
                                            <th class="tb-tit">Cantidad</th>
                                            <th title="Precio" class="tb-tit">Precio</th>
                                            <th class="tb-tit">Total</th>
                                            <th class="tb-tit">Opc.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="sf_admin_row_1">
                                            <td colspan="4">
                                                <div class="span_cantidad_items2"><span style="font-size: 10px;">Cantidad de Items: 0</span></div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tabpago" class="x-hide-display">
                    <div id="contenedorTAB21">
                        <!-- TAB1 -->
                        <div class="tabpanel2">
                            <table>
                                <tr>
                                    <td align="right" colspan="4" width="50%" class="tb-head">
                                        <b>Total Compra</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head">
                                        Fecha de Vencimiento
                                    </td>
                                    <td>
                                        <input type="text" size="10" readonly style="border: 1px solid black;margin-bottom:5px;" value="" id="fecha_vencimiento" name="fecha_vencimiento" />
                                        {literal}
                                            <script type="text/javascript">//<![CDATA[
                                              var cal = Calendar.setup({
                                                  onSelect: function(cal) { cal.hide() }
                                              });
                                              cal.manageFields("fecha_vencimiento", "fecha_vencimiento", "%Y-%m-%d");
                                            //]]>
                                            </script>
                                        {/literal}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >
                                        Monto a Cancelar
                                    </td>
                                    <td>
                                        <input readonly="readonly" type="text"  class="ctotalizar_" name="totalizar_monto_cancelar" value="{$cabecera_estadodecuenta[0].saldo_pendiente}" id="totalizar_monto_cancelar">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items"/>
            <input type="hidden" title="input_tiva" value="0" name="input_tiva" id="input_tiva"/>
            <input type="hidden" title="input_tsiniva" value="0" name="input_tsiniva" id="input_tsiniva"/>
            <input type="hidden" title="input_tciniva" value="0" name="input_tciniva" id="input_tciniva"/>
            <div id="displaytotal" class="x-hide-display"></div>
            <div id="displaytotal2" class="x-hide-display"></div>
            <input type="hidden" name="input_totalizar_sub_total"           id="input_totalizar_sub_total" value=""/>
            <input type="hidden" name="input_totalizar_descuento_parcial"   id="input_totalizar_descuento_parcial" value=""/>
            <input type="hidden" name="input_totalizar_total_operacion"     id="input_totalizar_total_operacion" value=""/>
            <input type="hidden" name="input_totalizar_pdescuento_global"   id="input_totalizar_pdescuento_global" value=""/>
            <input type="hidden" name="input_totalizar_descuento_global"    id="input_totalizar_descuento_global" value=""/>
            <input type="hidden" name="input_totalizar_monto_iva"           id="input_totalizar_monto_iva" value=""/>
            <input type="hidden" name="input_totalizar_total_general"       id="input_totalizar_total_general" value=""/>
            <input type="hidden" name="input_totalizar_monto_cancelar"      id="input_totalizar_monto_cancelar" value=""/>
            <input type="hidden" name="input_totalizar_saldo_pendiente"     id="input_totalizar_saldo_pendiente" value=""/>
            <input type="hidden" name="input_totalizar_cambio"              id="input_totalizar_cambio" value=""/>
            <input type="hidden" name="input_totalizar_monto_efectivo"      id="input_totalizar_monto_efectivo" value=""/>
            <input type="hidden" name="input_totalizar_monto_cheque"        id="input_totalizar_monto_cheque" value=""/>
            <input type="hidden" name="input_totalizar_nro_cheque"          id="input_totalizar_nro_cheque" value=""/>
            <input type="hidden" name="input_totalizar_nombre_banco"        id="input_totalizar_nombre_banco" value=""/>
            <input type="hidden" name="input_totalizar_monto_tarjeta"       id="input_totalizar_monto_tarjeta" value=""/>
            <input type="hidden" name="input_totalizar_nro_tarjeta"         id="input_totalizar_nro_tarjeta" value=""/>
            <input type="hidden" name="input_totalizar_tipo_tarjeta"        id="input_totalizar_tipo_tarjeta" value=""/>
            <input type="hidden" name="input_totalizar_monto_deposito"      id="input_totalizar_monto_deposito" value=""/>
            <input type="hidden" name="input_totalizar_nro_deposito"        id="input_totalizar_nro_deposito" value=""/>
            <input type="hidden" name="input_totalizar_banco_deposito"      id="input_totalizar_banco_deposito" value=""/>
        </form>
        <div id="incluirproducto" class="x-hide-display">
            <p><b>Productos</b></p>
            <p><select style="width:100%" id="items" name="items"></select></p>
            <p><b>Almac&eacute;n</b></p>
            <p><select id="almacen" name="almacen"></select></p>
            <p><b>C&oacute;digo Fabricante</b></p>
            <p><input type="text" name="codigofabricante" id="codigofabricante"></p>
            <p><b>Cantidad Unitaria</b></p>
            <p><input type="text" name="cantidadunitaria" id="cantidadunitaria"></p>
            <p><b>Costo Unitario</b></p>
            <p><input type="text" name="costounitario" id="costounitario"></p>
            <hr/>
            <p><b>Total</b></p>
            <p><input type="text" style="background-color: #c1c0c1;" readonly name="totalitem_tmp" id="totalitem_tmp"></p>
        </div>
        <div id="incluirservicio" class="x-hide-display">
            <p><b>Servicios</b></p>
            <p><select style="width:100%" id="items2" name="items2"></select></p>
            <p><b>Cantidad Unitaria</b></p>
            <p><input type="text" name="cantidadunitaria2" id="cantidadunitaria2"></p>
            <p><b>Costo Unitario</b></p>
            <p><input type="text" name="costounitario2" id="costounitario2"></p>
            <hr/>
            <p><b>Total</b></p>
            <p><input type="text" style="background-color: #c1c0c1;" readonly name="totalitem_tmp2" id="totalitem_tmp2"></p>
        </div>
    </body>
</html>
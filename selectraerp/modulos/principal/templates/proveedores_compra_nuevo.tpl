<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        {include file="snippets/header_form.tpl"}
        <script type="text/javascript" src="../../libs/js/event_compra.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formCOMPRAS.js"></script>
        <!--link type="text/css" rel="stylesheet" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script-->
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
            <script type="text/javascript">
            //<![CDATA[
                Ext.onReady(function(){
                    $("#num_factura").focus();
                });
                /*function validarFechas(fecha){
                    if(fecha.value<document.formulario.input_fechacompra.value){
                        Ext.Msg.alert("Alerta","Fecha invalida");
                            return false;
                    }
                }*/
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <div id="loading" style="position:absolute; width:80%; text-align:center; top:180px; visibility:hidden;">
            <img src="../../../includes/imagenes/36.gif"/>
        </div>
        <!-- La variable $name_form viene del *.php correspondiente-->
        <form name="formulario" id="form-{$name_form}" method="post" action="">
            <div id="datosGral">
                <input type="hidden" name="Datosproveedor" value=""/>
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                {include file="snippets/regresar_boton.tpl"}
                <!--Datos del proveedor y vendedor-->
                <div id="dp">
                    <img src="../../../includes/imagenes/ico_user.gif" align="absmiddle"/>
                    <span style="font-family:'Verdana';"><b>Proveedor:&nbsp;</b>{$dataproveedor[0].descripcion}</span>
                    <span style="font-family:'Verdana';"><b>RIF:&nbsp;</b>{$dataproveedor[0].rif}</span>
                    <input type="hidden" name="id_proveedor" value="{$dataproveedor[0].id_proveedor}"/>
                </div>
                <!--Datos de la compra-->
                <div id="dcompra">
                    <table>
                        <tr>
                            <td>
                                <img src="../../../includes/imagenes/28.png" width="17" height="17" align="absmiddle"/>
                                <span style="font-family:'Verdana';"><b>Responsable (*)</b></span>
                            </td>
                            <td>
                                <input type="text" name="responsable" id="responsable" value="{$responsable}" size="30" maxlength="70" class="form-text"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="../../../includes/imagenes/03.png" width="17" height="17" align="absmiddle"/>
                                <span style="font-family:'Verdana';"><b>N&uacute;mero Factura</b></span>
                            </td>
                            <td>
                                <input type="text" name="num_factura" id="num_factura" size="30" maxlength="70" class="form-text"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="../../../includes/imagenes/03.png" width="17" height="17" align="absmiddle"/>
                                <span style="font-family:'Verdana';"><b>N&uacute;mero Control</b></span>
                            </td>
                            <td>
                                <input type="text" name="num_cont_factura" id="num_control_factura" size="30" maxlength="70" class="form-text" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="../../../includes/imagenes/fecha.png" width="17" height="17" align="absmiddle"/>
                                <span style="font-family:'Verdana';"><b>Fecha Factura</b></span>
                            </td>
                            <td>
                                <!-- fecha_emision en la tabla cxp_edocuenta-->
                                <!-- fechacompra en la tabla compra-->
                                <input type="text" name="input_fechacompra" id="input_fechacompra" value='{$smarty.now|date_format:"%Y-%m-%d"}' size="23" readonly class="form-text"/>
                                <button id="boton_fechacompra">...</button>
                                {literal}
                                    <script type="text/javascript">
                                    //<![CDATA[
                                        Calendar.setup({
                                            weekNumbers: true,
                                            //showTime: true,
                                            onSelect: function(cal) { cal.hide(); }
                                        }).manageFields("boton_fechacompra", "input_fechacompra", "%Y-%m-%d");
                                        /*$("#input_fechacompra").datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            showOtherMonths:true,
                                            selectOtherMonths: true,
                                            numberOfMonths: 1,
                                            //yearRange:"-100:+100",
                                            dateFormat:"yy-mm-dd",
                                            showOn:"both"//button
                                        });*/
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
                                <select name="estado_entrega" id="estado_entrega" class="form-text">
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
                                                <td colspan="6">
                                                    <div class="span_cantidad_items">
                                                        <span style="font-size: 10px;">Cantidad de Items: 0</span>
                                                    </div>
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
                                    <table style="width:100%" class="lista2">
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
                                                <td colspan="6">
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
                                <table style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th colspan="4" style="text-align:center;" class="tb-head">Total Compra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="width:15%" class="tb-head">
                                                Fecha de Vencimiento
                                            </td>
                                            <td colspan="3">
                                                <input type="text" name="fecha_vencimiento" id="fecha_vencimiento" value='{$smarty.now|date_format:"%Y-%m-%d"}' size="10" readonly class="form-text"/>
                                                <button id="boton_fecha_vencimiento">...</button>
                                                {literal}
                                                    <script type="text/javascript">//<![CDATA[
                                                      var cal = Calendar.setup({
                                                          onSelect: function(c) {
                                                            /*var date = Calendar.intToDate(c.selection.get());
                                                            fechaCompra.args.min = date;
                                                            fechaCompra.redraw();*/
                                                            c.hide();
                                                          }
                                                      });
                                                      cal.manageFields("boton_fecha_vencimiento", "fecha_vencimiento", "%Y-%m-%d");
                                                    //]]>
                                                    </script>
                                                {/literal}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:2px" class="tb-head"></td>
                                            <td colspan="3" style="padding:2px"></td>
                                        </tr>
                                        <tr>
                                            <td style="width:15%" class="tb-head">
                                                Monto a Cancelar
                                            </td>
                                            <td colspan="3">
                                                <input type="text" name="totalizar_monto_cancelar" id="totalizar_monto_cancelar" value="{$cabecera_estadodecuenta[0].saldo_pendiente}" readonly class="form-text ctotalizar_" />
                                                <input type="radio" name="forma_pago" value="0" checked class="form-text" /> Contado
                                                <input type="radio" name="forma_pago" value="1" class="form-text" /> Cr&eacute;dito
                                            </td>
                                        </tr>
                                    </tbody>
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
            <p><input type="text" style="background-color: #c1c0c1;" readonly name="totalitem_tmp" id="totalitem_tmp"/></p>
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
            <p><input type="text" style="background-color: #c1c0c1;" readonly name="totalitem_tmp2" id="totalitem_tmp2"/></p>
        </div>
    </div>
</body>
</html>
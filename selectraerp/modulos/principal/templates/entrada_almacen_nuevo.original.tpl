<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formAlmacen.js"></script>
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="Datosproveedor" value=""/>
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <table style="width:100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:900px;"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'">
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
                <br/>
                <table>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';"><b>Autorizado Por (*):</b></span>
                        </td>
                        <td>
                            <!--input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="{$detalles_pendiente[0].autorizado_por}"/-->
                            <input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="{$nombre_usuario}"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';"><b>Observaciones</b></span>
                        </td>
                        <td>
                            <input type="text" name="observaciones" maxlength="100" id="observaciones" value="{$detalles_pendiente[0].observacion}"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';"><b>Fecha</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fechacompra" id="input_fechacompra" value='{$smarty.now|date_format:"%d-%m-%Y"}'/>
                            <!--div style="color:#4e6a48" id="fechacompra">{$smarty.now|date_format:"%d-%m-%Y"}</div-->
                            {literal}
                                <script type="text/javascript">//<![CDATA[
                                    var cal = Calendar.setup({onSelect: function(cal) { cal.hide() }});
                                    cal.manageFields("input_fechacompra", "input_fechacompra", "%d-%m-%Y");
                                //]]></script>
                            {/literal}
                        </td>
                    </tr>
                    <!--
                    Codigo fuente añadido para cubrir la funcionalidad de registrar los
                    datos de una compra: fecha, nro. de factura y nro de control. Esto
                    después de haber realizado el registro de la compra que ha quedado
                    pendiente por entregar.
                    -->
                    {if $cod <> ""}
                        <!--Implica que se hará la entrada al inventario de una compra pendiente-->
                        <tr>
                            <td>
                                <span style="font-family:'Verdana';"><b>Nro. Factura</b></span>
                            </td>
                            <td>
                                <input type="text" name="nro_factura" maxlength="70" id="nro_factura"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-family:'Verdana';"><b>Nro. Control</b></span>
                            </td>
                            <td>
                                <input type="text" name="nro_control" maxlength="70" id="nro_control"/>
                            </td>
                        </tr>
                    {/if}
                </table>
            </div>
            <!--</Datos del proveedor y vendedor>-->
            <div id="dcompra" class="x-hide-display"></div>
            <div id="PanelGeneralCompra">
                <div id="tabproducto" class="x-hide-display">
                    <div id="contenedorTAB">
                        <div id="div_tab1">
                            <div class="grid">
                                <table class="lista" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="tb-tit" style="text-align: center;">C&oacute;digo</th>
                                            <th class="tb-tit" style="text-align: center;">Descripci&oacute;n</th>
                                            <th class="tb-tit" style="text-align: center;">Cantidad</th>
                                            <th class="tb-tit" style="text-align: center;">Opci&oacute;n</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {if $cod <> ""}
                                            {foreach from=$productos_pendientes_entrada key=i item=prod}
                                                <tr>
                                                    <td style="text-align: left; padding-left: 20px;">{$prod.codigo_barras}</td><!--id_item-->
                                                    <td style="text-align: left; padding-left: 20px;">{$prod.descripcion1}</td>
                                                    <td style="text-align: right; padding-right: 20px;">{$prod.cantidad}</td>
                                                    <td></td>
                                                </tr>
                                            {/foreach}
                                        {/if}
                                    </tbody>
                                    <tfoot>
                                        <tr class="sf_admin_row_1">
                                            <td colspan="4">
                                                <div class="span_cantidad_items">
                                                    <span style="font-size: 12px; font-style: italic; text-align: left;">Cantidad de Items: 0</span>
                                                </div>
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
                            <table></table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items">
            <div id="displaytotal" class="x-hide-display"></div>
            <div id="displaytotal2" class="x-hide-display"></div>
        </form>
        <div id="incluirproducto" class="x-hide-display">
            <p>
                <label for="almacen"><b>Almac&eacute;n</b></label>
                <select id="almacen" name="almacen"></select>
            </p>
            <p>
                <label><b>Productos</b></label><br/>
                <select style="width:100%" id="items" name="items"></select>
            </p>
            <p>
                <label><b>Cantidad Unitaria</b></label><br/>
                <input type="text" name="cantidadunitaria" id="cantidadunitaria"/>
            </p>
            <p>
                <label><b>Cantidad Existente en Almac&eacute;n</b></label><br/>
                <input type="text" name="cantidad_existente" id="cantidad_existente"/>
            </p>
        </div>
    </body>
</html>
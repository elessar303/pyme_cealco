<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                $("#detalle_factura_").hide();
                sw=0;
                $.setValoresInput = function(nombreObjetoDestino,nombreObjetoActual){
                    $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
                }
                $("#lick_detalle").click(function(){
                    if(sw===0){
                        $("#detalle_factura_").show(150);
                        $(this).html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/drop-add2.gif\"> Ocultar</div>");
                        sw=1;
                    }else{
                        $("#detalle_factura_").hide(150);
                        $(this).html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/drop-add.gif\"> Ver detalles</div>");
                        sw=0;
                    }
                });
            });
            //]]>
            </script>
        {/literal}
        {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" method="post">
            <input type="hidden" name="DatosCliente" value=""/>
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <div id="datosGral">
                {include file="snippets/regresar_boton.tpl"}
                <!--<Datos del cliente y vendedor>-->
                <div class="subcontenedor">
                    <img style="float:left" src="../../../includes/imagenes/ico_user.gif"/>
                    <span style="font-family:'Verdana';"><b>Cliente: </b>{$dataDevolucion[0].nombre}</span>
                    <span style="font-family:'Verdana';"><b>RIF: </b>{$dataDevolucion[0].rif}</span>
                    <input type="hidden" name="id_cliente" value="{$dataDevolucion[0].id_cliente}"/>
                </div>
                <!--<Datos de la factura>-->
                <div class="subcontenedor">
                    <div style="float: left; margin-right: 20px;">
                        Fecha: <input type="hidden" name="input_fechaFactura" id="input_fechaFactura" value='{$smarty.now|date_format:"%Y-%m-%d"}'>
                        <div style="color:#4e6a48" id="fechaFactura">{$dataDevolucion[0].fechaNotaEntrega|date_format:"%d-%m-%Y"}</div>
                    </div>
                    <div style="margin-right: 20px;">
                        Nota de Entrega N&uacute;mero
                        <div style="font-size:15px; color:red; font-weight: bold;" id="numFactura">{$dataDevolucion[0].cod_nota_entrega}</div>
                    </div>
                    <div id="lick_detalle" style="cursor:pointer;width:100px;"><img style="float:left" src="../../../includes/imagenes/drop-add.gif"/> Ver Detalles</div>
                    <div id="detalle_factura_">
                        <div class="resumen">
                            Sub-Total
                            <div style="font-size:20px; color:#2e931a;" id="subTotal">{$DatosGenerales[0].moneda} {$dataDevolucion[0].subtotal}</div>
                            <input type="hidden" name="input_subtotal" id="input_subtotal" value=""/>
                        </div>
                        <div class="resumen">
                            Descuento
                            <input type="hidden" name="input_descuentosItemFactura" id="input_descuentosItemFactura" value=""/>
                            <div style="font-size:20px; color:red; text-decoration:line-through;" id="descuentosItemFactura">{$DatosGenerales[0].moneda} {$dataDevolucion[0].descuentosItemNotaEntrega}</div>
                        </div>
                        <div class="resumen">
                            Monto Items
                            <input type="hidden" name="input_montoItemsFactura" id="input_montoItemsFactura" value=""/>
                            <div style="font-size:20px;color:#2e931a;" id="montoItemsFactura">{$DatosGenerales[0].moneda} {$dataDevolucion[0].montoItemsNotaEntrega}</div>
                        </div>
                        <div class="resumen">
                            {$DatosGenerales[0].nombre_impuesto_principal}
                            <input type="hidden" name="input_ivaTotalFactura" id="input_ivaTotalFactura" value=""/>
                            <div style="font-size:20px; color:#2e931a;" id="ivaTotalFactura">{$DatosGenerales[0].moneda} {$dataDevolucion[0].ivaTotalNotaEntrega}</div>
                        </div>
                        <div class="resumen">
                            Total
                            <input type="hidden" name="input_TotalTotalFactura" id="input_TotalTotalFactura" value=""/>
                            <div style="font-size:20px; color:#00005e;" id="TotalTotalFactura">{$DatosGenerales[0].moneda} {$dataDevolucion[0].totalizar_total_general}</div>
                        </div>
                        <br/><br/><br/>
                        <input type="hidden" name="input_cantidad_items" id="input_cantidad_items" value=""/>
                        <div class="span_cantidad_items">
                            <span style="font-size: 15px;">Cantidad de Items: {$dataDevolucion[0].cantidad_items}</span>
                        </div>
                    </div>
                </div>
                <!--</Datos de la factura>-->
                <!--<Tab de pasos de factura>-->
                <div id="contenedorTAB_factura_paso1">
                    <div class="subcontenedor">
                        <table class="seleccionLista">
                            <tbody>
                                <tr class="tb-head" >
                                    {foreach from=$cabecera key=i item=campos}
                                        <td>{$campos}</td>
                                    {/foreach}
                                </tr>
                                {foreach from=$dataDetalleFactura key=i item=campos}
                                    {if $i%2 eq 0}
                                        {assign var=color value=""}
                                    {else}
                                        {assign var=color value="#cacacf"}
                                    {/if}
                                    <tr bgcolor="{$color}">
                                        <td style="text-align:center;">{$campos.cod_item}</td>
                                        <td>{$campos._item_descripcion}</td>
                                        <td class="posicion_cantidades">{$campos._item_cantidad}</td>
                                        <td class="posicion_cantidades">{$campos._item_preciosiniva}</td>
                                        <td class="posicion_cantidades">{$campos._item_descuento}</td>
                                        <td class="posicion_cantidades">{$campos._item_totalsiniva}</td>
                                        <td class="posicion_cantidades">{$campos._item_totalsiniva}</td>
                                        <td class="posicion_cantidades">{$campos._item_piva}</td>
                                        <td class="posicion_cantidades">{$campos._item_totalconiva}</td>
                                    </tr>
                                    {assign var=ultimo_cod_valor value=$campos.id_cliente}
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                    <input type="submit" id="anular" name="anular" value="Anular Nota Entrega" />
                </div>
                <input type="hidden" id="{$dataDevolucion[0].id_nota_entrega}" name="{$dataDevolucion[0].id_nota_entrega}" />
            </div>
        </form>
        <div id="info" style="display:none;"></div>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form name="formulario" id="formulario" method="post">
            <input type="hidden" name="DatosCliente" value="">
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">
            <table width="100%">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                                <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <!--<Datos del cliente y vendedor>-->
            <div style="background-color:#ffffff; border: 1px solid #ededed;-moz-border-radius: 7px;padding:5px; margin-top:0.3%; font-size: 13px; ">
                <img align="absmiddle" src="../../libs/imagenes/ico_user.gif">
                <span style="font-family:'Verdana';"><b>Cliente: </b>{$dataDevolucion[0].nombre}</span>
                <span style="font-family:'Verdana';"><b>RIF: </b>{$dataDevolucion[0].rif}</span>
                <input type="hidden" name="id_cliente" value="{$dataDevolucion[0].id_cliente}">
            </div>
            <!--</Datos del cliente y vendedor>-->

            <!--<Datos de la factura>-->
            <div  style="background-color:#ffffff; border: 1px solid #ededed;-moz-border-radius: 7px;padding:5px; margin-top:0.3%;margin-right: 0.3%;   font-size: 13px; ">
                <div style="float: left; margin-right: 20px;">
                    Fecha: <input type="hidden" name="input_fechaFactura" id="input_fechaFactura" value='{$smarty.now|date_format:"%Y-%m-%d"}'>
                    <div  style="color:#4e6a48" id="fechaFactura">{$dataDevolucion[0].fechaPedido|date_format:"%d-%m-%Y"}</div>
                </div>

                <div style=" margin-right: 20px;">
                    Pedido Numero
                    <div style="font-size:15px;color:red;" id="numFactura">{$dataDevolucion[0].cod_pedido}</div>
                </div>
                {literal}
                    <script>
                    $(document).ready(function(){
                        $("#detalle_factura_").hide();
                        sw=0;

                        $.setValoresInput = function(nombreObjetoDestino,nombreObjetoActual){
                            $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
                        }
                        $("#lick_detalle").click(function(){
                        if(sw==0){
                                $("#detalle_factura_").show(150);
                                $(this).html("<img align=\"absmiddle\" src=\"../../libs/imagenes/drop-add2.gif\"> Ocultar</div>");
                                sw=1;
                        }else{
                            if(sw==1){
                                    $("#detalle_factura_").hide(150);
                                    $(this).html("<img align=\"absmiddle\" src=\"../../libs/imagenes/drop-add.gif\"> Ver Detalles</div>");
                                    sw=0;
                            }
                        }
                        });
                    });
                    </script>
                {/literal}
                <div id="lick_detalle" style="cursor:pointer;width:100px;"><img align="absmiddle" src="../../libs/imagenes/drop-add.gif"> Ver Detalles</div>
                <div id="detalle_factura_">
                    <div style=" margin-right: 20px;">
                        Sub-Total
                        <div style="font-size:15px;color:#2e931a;" id="subTotal">{$dataDevolucion[0].subtotal} Bs.</div>
                        <input type="hidden" name="input_subtotal" id="input_subtotal" value="">
                    </div>
                    <div style=" margin-right: 20px;">
                        Descuento
                        <input type="hidden" name="input_descuentosItemFactura" id="input_descuentosItemFactura" value="">
                        <div style="color:red;text-decoration:line-through;" id="descuentosItemFactura">{$dataDevolucion[0].descuentosItemPedido} Bs.</div>
                    </div>

                    <div style=" margin-right: 50px;">
                        Monto Items
                        <input type="hidden" name="input_montoItemsFactura" id="input_montoItemsFactura" value="">
                        <div  style="font-size:15px;color:#2e931a;" id="montoItemsFactura">{$dataDevolucion[0].montoItemsPedido} Bs.</div>
                    </div>

                    <div style=" margin-right: 20px;">
                        I.V.A
                        <input type="hidden" name="input_ivaTotalFactura" id="input_ivaTotalFactura" value="">
                        <div style="color:#2e931a;" id="ivaTotalFactura">{$dataDevolucion[0].ivaTotalPedido} Bs.</div>
                    </div>

                    <div style="float: left; margin-right: 20px;">
                        Total
                        <input type="hidden" name="input_TotalTotalFactura" id="input_TotalTotalFactura" value="">
                        <div style="font-size:15px;color:#00005e;" id="TotalTotalFactura">{$dataDevolucion[0].TotalTotalPedido} Bs.</div>
                    </div>
                    <br><br><br>
                    <input type="hidden" name="input_cantidad_items" id="input_cantidad_items" value="">
                    <div class="span_cantidad_items"><span style="font-size: 10px;">Cantidad de Items: {$dataDevolucion[0].cantidad_items}</span></div>
                </div>
            </div>
            <!--</Datos de la factura>-->
            <!--<Tab de pasos de factura>-->
            {literal}
                <script>
                    $(document).ready(function(){
                        $("#contenedorTAB_factura_paso2").hide();
                        $("#tab1_pasos").click(function(){
                            $("#contenedorTAB_factura_paso1").show();
                            $("#contenedorTAB_factura_paso2").hide();
                            $("#tab1_pasos").removeClass("click_paso_OFF").addClass("click_paso_ON").find("img").attr("src", "../../libs/imagenes/113.png");
                            $("#tab2_pasos").removeClass("click_paso_OFF").addClass("click_paso_OFF").find("img").attr("src", "../../libs/imagenes/6_off.png");

                        });
                        $("#tab2_pasos").click(function(){
                             cant_filas = $(".grid table.lista tbody").find("tr").length;
                   if(cant_filas==0){
                        $.facebox("<span style='color: red;'>Debe agregar un Item para esta operación</span>");
                        return false;
                    }
                         $(".ctotalizar_").each(function(){
                            if($(this).val()==""){
                                $(this).val("");
                            }
                         });
                         $.totalizarFactura();

                         $.setValoresInput("#input_totalizar_sub_total","#totalizar_sub_total");
                         $.setValoresInput("#input_totalizar_descuento_parcial","#totalizar_descuento_parcial");
                         $.setValoresInput("#input_totalizar_total_operacion","#totalizar_total_operacion");

                         $.setValoresInput("#input_totalizar_pdescuento_global","#totalizar_pdescuento_global");
                         $.setValoresInput("#input_totalizar_descuento_global","#totalizar_descuento_global");
                         $.setValoresInput("#input_totalizar_monto_iva","#totalizar_monto_iva");
                         $.setValoresInput("#input_totalizar_total_general","#totalizar_total_general");

                //#FORMA PAGO
                         $.setValoresInput("#input_totalizar_monto_cancelar","#totalizar_monto_cancelar");
                         $.setValoresInput("#input_totalizar_saldo_pendiente","#totalizar_saldo_pendiente");
                         $.setValoresInput("#input_totalizar_cambio","#totalizar_cambio");

                //#INSTRUMENTO DE PAGO
                         $.setValoresInput("#input_totalizar_monto_efectivo","#totalizar_monto_efectivo");
                         $.setValoresInput("#input_totalizar_monto_cheque","#totalizar_monto_cheque");
                         $.setValoresInput("#input_totalizar_nro_cheque","#totalizar_nro_cheque");
                         $.setValoresInput("#input_totalizar_nombre_banco","#totalizar_nombre_banco");
                         $.setValoresInput("#input_totalizar_monto_tarjeta","#totalizar_monto_tarjeta");
                         $.setValoresInput("#input_totalizar_nro_tarjeta","#totalizar_nro_tarjeta");
                         $.setValoresInput("#input_totalizar_tipo_tarjeta","#totalizar_tipo_tarjeta");
                         $.setValoresInput("#input_totalizar_monto_deposito","#totalizar_monto_deposito");
                         $.setValoresInput("#input_totalizar_nro_deposito","#totalizar_nro_deposito");
                         $.setValoresInput("#input_totalizar_banco_deposito","#totalizar_banco_deposito");
                            $("#contenedorTAB_factura_paso2").show();
                            $("#contenedorTAB_factura_paso1").hide();
                            $("#tab2_pasos").removeClass("click_paso_OFF").addClass("click_paso_ON").find("img").attr("src", "../../libs/imagenes/6.png");
                            $("#tab1_pasos").removeClass("click_paso_OFF").addClass("click_paso_OFF").find("img").attr("src", "../../libs/imagenes/113_OFF.png");
                        });
                    });
                </script>

                <style>
                    .click_paso_ON{
                        background-color:#35358c;color:#ffffff; border: 2px solid #070742;-moz-border-radius: 7px; float:left;padding:5px; margin-top:0.3%;  font-size: 13px;
                    }
                    .click_paso_OFF{
                        background-color:#eaeaea;color:#bababa; border: 1px solid #f8f8f8;-moz-border-radius: 7px;padding:5px;float:left; margin-left: 0.3%; margin-top:0.3%;  font-size: 13px;
                    }
                </style>
            {/literal}
            <!--<div id="tabs_pasos">
                <div id="tab1_pasos" class="click_paso_ON">
                    <img src="../../libs/imagenes/113.png" align="absmiddle" width="20" align="absmiddle" height="20">  <b>PASO 1</b>
                </div>
                <div id="tab2_pasos" class="click_paso_OFF">
                    <img src="../../libs/imagenes/6_off.png" align="absmiddle" width="20" align="absmiddle" height="20">  <b>PASO 2</b>
                </div>
            </div>-->
            <!--</Tab de pasos de factura>-->
            <!--</contenedor factura paso 1>-->
            <div id="contenedorTAB_factura_paso1">
                <div style="background-color:#ffffff; border: 1px solid #ededed;-moz-border-radius: 7px;padding:5px; margin-top:0.3%;margin-right: 0.3%;   font-size: 13px; ">
                    <table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
                        <tbody>
                            <tr class="tb-head" >
                                {foreach from=$cabecera key=i item=campos}
                                    <td><strong>{$campos}</strong></td>
                                {/foreach}
                                <td>
                                </td>
                            </tr>
                            {foreach from = $dataDetalleFactura key = i item = campos}
                                {if $i%2==0}
                                    <tr bgcolor="">
                                    {else}
                                    <tr bgcolor="#e1e1e1">
                                    {/if}
                                    <!--td style="border-bottom: 1px solid black;"><input type="checkbox" value="{$campos.id_item}" name="id_item"></td-->
                                    <td style="border-bottom: 1px solid black;">{$campos.cod_item}</td>
                                    <td style="border-bottom: 1px solid black;">{$campos._item_descripcion}</td>
                                    <td style="border-bottom: 1px solid black;">{$campos._item_cantidad}</td>
                                    <td style="border-bottom: 1px solid black;">{$campos._item_preciosiniva}</td>
                                    <td style="border-bottom: 1px solid black;">{$campos._item_descuento}</td>
                                    <td style="border-bottom: 1px solid black;">{$campos._item_totalsiniva}</td>
                                    <td style="border-bottom: 1px solid black;">{$campos._item_totalsiniva}</td>
                                    <td style="border-bottom: 1px solid black;">{$campos._item_piva}</td>
                                    <td style="border-bottom: 1px solid black;">{$campos._item_totalconiva}</td>
                                </tr>
                                {assign var = ultimo_cod_valor value=$campos.id_cliente}
                            {/foreach}
                        </tbody>
                    </table>
                </div>
                <input type="submit" id="anular" name="anular" value="Anular Pedido" />
            </div>
            <!--input type="hidden" name="id_pedido" id="{$dataDevolucion[0].id_pedido}" name ="{$dataDevolucion[0].id_pedido}"/-->
            <input type="hidden" id="{$dataDevolucion[0].id_pedido}" name="{$dataDevolucion[0].id_pedido}"/>
        </form>
        <div id="info" style="display:none;">
        </div>
    </body>
</html>
<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común creación de cabeceras de los
    formularios.
2,_ Factorizacion y eliminacion de codigo redundante así como separación
    de contenido y de presentación
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho código en un snippet de para obtener las bondades de la
    reutilización.
2._ Separar el contenido de su presentación para así tener código HTML correcto.
-->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
    
{literal}
<script >
    $(document).ready(function(){
  
         function cargarUbicaciones() {    
        idAlmacen=$("#cod_almacen").val();     
        $.ajax({
            type: 'POST',
            data: 'opt=cargaUbicacion&idAlmacen='+idAlmacen,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#ubicacion").find("option").remove();
                $("#ubicacion").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#ubicacion").find("option").remove();
                this.vcampos = eval(data);
                 $("#ubicacion").append("<option value=''>Seleccione..</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#ubicacion").append("<option value='"+this.vcampos[i].id+"'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
    }
$("#cod_almacen").change(function(){
    cargarUbicaciones();
});
  
  
    });
</script>
{/literal}
        <form name="formulario" id="formulario" method="post">
            <input type="hidden" name="transaccion" id="transaccion" value="nota"/>
            <input type="hidden" name="DatosCliente" value=""/>
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <input type="hidden" name="descripcion" value="{$subseccion[0].descripcion}"/>
            <input type="hidden" name="moneda" id="moneda" value="{$DatosGenerales[0].moneda}"/>
            <table style="width:100%">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%">
                            <tbody>
                                <tr>
                                    <td style="width:900px;">
                                        <span style="float:left">
                                            <img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />
                                            {$subseccion[0].descripcion}
                                        </span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'" >
                                            <tr>
                                                <td style="padding: 0px; text-align:right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align:left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
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
            <div class="contenedor_factura" style="width:99%; float:left;">
                <img style="vertical-align: middle;" src="../../../includes/imagenes/ico_user.gif"/>
                <span style="font-family:'Verdana';"><b>Cliente: </b></span>
                <span style="font-family:'Verdana';">{$datacliente[0].nombre}</span>
                <input type="hidden" name="id_cliente" value="{$datacliente[0].id_cliente}"/>
                <input type="hidden" name="id_fiscal" value="{$datacliente[0].rif}"/>
                <input type="hidden" name="numero_control_factura" value="{$nro_cotizacion}"/>
                <br/>
                <img src="../../../includes/imagenes/ico_user.gif" style="vertical-align: middle;"/>
                <span style="font-family:'Verdana';"><b>Vendedor:</b></span>
                <select name="cod_vendedor" id="cod_vendedor">
                    {html_options output=$option_output_vendedor values=$option_values_vendedor selected=$option_selected_vendedor}
                </select>
                <br/>
                <!--img align="absmiddle" src="../../../includes/imagenes/ico_user.gif">
                <span style="font-family:'Verdana';"><b>Estado de Entrega de Materiales</b></span>
                <select name="estado_entrega" id="estado_entrega">
                    <option value="Entregado">Entregado</option>
                    <option value="Pendiente">Pendiente</option>
                </select>
                </td-->
            </div>
            <!--<Datos de la factura>-->
            {include file="snippets/panel_detalles_operacion.tpl"}
            <!--<Tab de pasos de factura>-->
            <div id="tabs_pasos">
                <div id="tab1_pasos" class="click_paso_ON">
                    <img src="../../../includes/imagenes/113.png" align="absmiddle" width="20" align="absmiddle" height="20">  <b>Paso 1</b>
                </div>
                <div id="tab2_pasos" class="click_paso_OFF">
                    <img src="../../../includes/imagenes/6_off.png" align="absmiddle" width="20" align="absmiddle" height="20">  <b>Paso 2</b>
                </div>
            </div>
            <br/><br/><br/>
            <!--</contenedor factura paso 1>-->
            <div id="contenedorTAB_factura_paso1">
                <table width="100%">
                    <tr>
                        <td colspan="2" >
                            <table style="cursor: pointer;" class="MostrarTabla" id="MostrarTabla" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img id="ImgMensaje" src="../../../includes/imagenes/drop-add.gif" width="16" height="16"/></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;width:120px"><div id="LabelMensaje">Agregar Nuevo Item</div></td>
                                    <td style="padding: 0px;" align="left"><img  src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr valign="top" id="PanelFactura">
                        <td colspan="2" valign="center">
                            <div id="contenedorTAB">
                                <table>
                                    <tr>
                                        <td valign="top">
                                            <!-- Inicio Item -->
                                            <div id="div_tab1">
                                                <table width="100%" >
                                                    <tr style="border:1px solid #f3f3f3;">
                                                        <td colspan="2" style="padding:10px;background-color:white;" valign="top">
                                                            <a href="#" style="color: blue;text-decoration: underline;" id="seleccionItem"><b>
                                                                    <img align="absmiddle" src="../../../includes/imagenes/ico_search_1.gif"/>
                                                                    Buscar Producto/Servicio</b></a>
                                                        </td>
                                                    </tr>
                                                    <tr style="border:1px solid #f3f3f3;">
                                                        <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">

                                                            <div style="font-weight: bold;" id="descripcion_tipo_forma"><b><span style="font-family:'Verdana';">Items</span></b></div>

                                                        </td>
                                                        <td style="background-color:white;padding:5px;" valign="top">
                                                            <input type="hidden" name="cod_item_forma"/>
                                                            <input type="hidden" name="id_item"/>
                                                            <input type="hidden" name="descripcion_input_item"/>
                                                            <div id="descripcion_item"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <table width="100%" >
                                                                <tr style="border:1px solid #f3f3f3;">
                                                                    <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">
                                                                        <span style="font-family:'Verdana';"><b>Almacen</b></span>
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                        <select name="cod_almacen" id="cod_almacen">
                                                                            {html_options output=$option_output_almacen values=$option_values_almacen }
                                                                        </select>
                                                                    </td>
                                                                    <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">
                                                                        <span style="font-family:'Verdana';"><b>Tipo Precio</b></span>
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                        <select name="cod_tipo_precio" id="cod_tipo_precio">
                                                                            {html_options values=$option_values_tipo_precio output=$option_output_tipo_precio selected=$option_selected_tipo_precio}
                                                                        </select>
                                                                    </td>
                                                                </tr>
																					<tr style="border:1px solid #f3f3f3;">
                                                                    <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">
                                                                        <span style="font-family:'Verdana';"><b>Ubicacion</b></span>
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                        <select name="ubicacion" id="ubicacion"><option value="">Seleccione...</option>
                                                                        </select>
                                                                    </td>
                                                                    <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">
                                                                      
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                        
                                                                    </td>
                                                                </tr>                                                                
                                                                
                                                                
                                                                
                                                                <tr style="border:1px solid #f3f3f3;">
                                                                    <td style="background-color:white;" colspan="4" valign="top">
                                                                        <div id="LabelCantidadExistente"></div>
                                                                    </td>
                                                                </tr>
                                                                <tr style="border:1px solid #f3f3f3;">
                                                                    <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">
                                                                        <span style="font-family:'Verdana';"><b>Cantidad</b></span>
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                        <input type="text"  size="10"  name="cantidadPedido" value="0" id="cantidadPedido">
                                                                    </td>
                                                                    <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">
                                                                        <span style="font-family:'Verdana';"><b>Precio sin Iva</b></span>
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                        <input type="text"  size="10"  name="precioProductoPedido" value="0" readonly="readonly" id="precioProductoPedido">
                                                                    </td>
                                                                </tr>
                                                                <tr style="border:1px solid #f3f3f3;">
                                                                    <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">
                                                                        <span style="font-family:'Verdana';"><b>Descuento</b></span>
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                        <input type="text" name="descuentoPedido" value="0" title="Descuento maximo del cliente: {$datacliente[0].porc_parcial} %" size="10"  id="descuentoPedido">	 %
                                                                    </td>
                                                                    <td valign="top" width="100px" style="background-color:#5084a9; color:white;padding-left:20px;padding-top:5px">
                                                                        <span style="font-family:'Verdana';"><b>Monto Descto.</b></span>
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                        <input type="text" name="montodescuentoPedido"  value="0"  size="10"  readonly="readonly" id="montodescuentoPedido">
                                                                    </td>
                                                                </tr>
                                                                <tr style="border:1px solid #f3f3f3;">
                                                                    <td style="background-color:white;" valign="top"></td>
                                                                    <td style="background-color:white;" valign="top"></td>
                                                                    <td style="background-color:white;" valign="center">Total</td>
                                                                    <td colspan="2" style="background-color:white;" valign="top">
                                                                        <input type="text" value="0" size="10" name="totalPedido" readonly="readonly" id="totalPedido">
                                                                    </td>
                                                                </tr>
                                                                <tr style="border:1px solid #f3f3f3;">
                                                                    <td style="background-color:white;" valign="top">
                                                                    </td>
                                                                    <td style="background-color:white;" valign="top">
                                                                    </td>
                                                                    <td style="background-color:white;" valign="center">
                                                                    </td>
                                                                    <td colspan="2" style="background-color:white;" valign="top">
                                                                        <table >
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td><span style="float:left"></td>
                                                                                    <td width="75">
                                                                                        <table style="cursor: pointer;" class="btn_bg" id="addTabla" border="0" cellpadding="0" cellspacing="0">
                                                                                            <tr>
                                                                                                <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                                                                <td class="btn_bg"><img src="../../../includes/imagenes/drop-add.gif" width="16" height="16" /></td>
                                                                                                <td class="btn_bg" nowrap style="padding: 0px 1px;">Incluir</td>
                                                                                                <td  style="padding: 0px;" align="left"><img  src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                    <td width="75">
                                                                                        <table style="cursor: pointer;" class="btn_bg" id="cancelaradd" border="0" cellpadding="0" cellspacing="0">
                                                                                            <tr>
                                                                                                <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                                                                <td class="btn_bg"><img src="../../../includes/imagenes/drop-no.gif" width="16" height="16" /></td>
                                                                                                <td class="btn_bg" nowrap style="padding: 0px 1px;">Cancelar</td>
                                                                                                <td  style="padding: 0px;" align="left"><img  src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!-- /Fin Inicio Item -->
                                        </td>
                                        <td valign="top">
                                            <!-- Inicio Detalle Item -->
                                            <div>
                                                <table style="border: 1px solid #949494">
                                                    <tr>
                                                        <td valign="top" style="background-color:white;">
                                                            <table id="tabla_total"  bgcolor="white">
                                                                <thead>
                                                                    <tr>
                                                                        <th align="center">Precios</th>
                                                                        <th align="center">Con Iva</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><input readonly="readonly" class="campo_decimal" id="fila_precio1" name="precio1" value="{$campos_item[0].precio1}"size="10" type="text"></td>
                                                                        <td><input readonly="readonly" readonly="readonly"class="campo_decimal" id="fila_precio1_iva" value="{$campos_item[0].coniva1}" name="coniva1" size="10" type="text"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input readonly="readonly" class="campo_decimal" id="fila_precio2" name="precio2" value="{$campos_item[0].precio2}"  size="10" type="text"></td>
                                                                        <td><input readonly="readonly" class="campo_decimal" id="fila_precio2_iva" value="{$campos_item[0].coniva2}" name="coniva2" size="10" type="text"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input readonly="readonly" class="campo_decimal" id="fila_precio3" name="precio3" value="{$campos_item[0].precio3}"  size="10" type="text"></td>
                                                                        <td><input readonly="readonly" class="campo_decimal" id="fila_precio3_iva"  value="{$campos_item[0].coniva3}" name="coniva3" size="10" type="text"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div id="LabelDetalleItem"></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!-- /Fin Detalle Item -->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
                <div id="contenedorTAB">
                    <div id="div_tab1">
                        <div class="grid">
                            <table width="100%" class="lista">
                                <thead>
                                    <tr>
                                        <th class="tb-tit">Codigo</th>
                                        <th class="tb-tit">Descripcion</th>
                                        <th class="tb-tit">Cantidad</th>
                                        <th title="Precio sin Iva" class="tb-tit">Precio</th>
                                        <th class="tb-tit">Descuento</th>
                                        <th title="% del Descuento" class="tb-tit">%</th>
                                        <th class="tb-tit">Total Sin I.V.A</th>
                                        <th class="tb-tit">% I.V.A</th>
                                        <th class="tb-tit">Total con I.V.A</th>
                                        <th class="tb-tit">Opt</th>
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
            <!--</contenedor factura paso 1>-->
            <!--</contenedor factura paso 2>-->
            <div id="contenedorTAB_factura_paso2">
                <div id="divTotalizarFactura">
                    <div id="tabs">
                        <table style="margin-left:20px;" >
                            <tr style="height:25px;">
                                <td id="tab1" class="tab">
                                    <img src="../../../includes/imagenes/1.png" width="20" align="absmiddle" height="20"><b>Totalizar Cotizaci&oacute;n</b>&nbsp;
                                </td>
                                <td>&nbsp;&nbsp;</td>
                                <td id="tab2" class="tab">
                                    <img src="../../../includes/imagenes/1.png" width="20" align="absmiddle" height="20"><b>Retenciones y Forma de Pago</b>&nbsp;
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="contenedorTAB21">
                        <!-- TAB1 -->
                        <div class="tabpanel1">
                            <table>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head">Sub Total</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_sub_total" value="" id="totalizar_sub_total">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="tb-head">Descuento Parcial</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_descuento_parcial" id="totalizar_descuento_parcial">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="tb-head">Total Operación</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_total_operacion" id="totalizar_total_operacion">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="3" class="tb-head">Descuento Global</td>
                                    <td>
                                        <input type="text" class="ctotalizar_" size="2" maxlength="3" value="0" name="totalizar_pdescuento_global" id="totalizar_pdescuento_global"> % =
                                        <input type="text" class="ctotalizar_" style="width: 100px" readonly value="0" name="totalizar_descuento_global" id="totalizar_descuento_global">
                                    </td>
                                </tr>
                                <!--
                                <tr>
                                <td colspan="3" class="tb-head">
                                Total Neto
                                </td>
                                <td>
                                    <input type="text" value="0" name="totalizar_neto" id="totalizar_neto">
                                </td>
                                </tr>-->
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Base Imponible</td>
                                    <td>
                                        <input readonly type="text" value="0" name="totalizar_base_imponible" id="totalizar_base_imponible">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="3" class="tb-head">Monto I.V.A</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_monto_iva" id="totalizar_monto_iva">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="3" class="tb-head">Monto Exento de I.V.A.</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_monto_exento" id="totalizar_monto_exento">
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Total General</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_total_general" id="totalizar_total_general">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Total Retenciones</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_total_retencion" value="0" id="totalizar_total_retencion">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Total a Factura Menos Retenciones</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_total_factura" value="0" id="totalizar_total_factura">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tabpanel2">
                            <table>
                                {foreach from=$tipo_impuesto key=a item=impuesto}
                                    <tr>
                                        <td align="right" colspan="4" width="50%" class="tb-head" >
                                            <b>{$impuesto.descripcion}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" width="50%" class="tb-head" >Base para Retención</td>
                                        <td>
                                            {if $impuesto.cod_tipo_impuesto eq 1}
                                                <input type="text" readonly name="totalizar_monto_iva" id="totalizar_monto_iva">
                                            {else}
                                                <input type="text" name="totalizar_base_imponible" id="totalizar_base_imponible">
                                            {/if}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" width="50%" class="tb-head" >Porcentaje de Retención</td>
                                        <td>
                                            <input type="text" readonly name="totalizar_pbase_retencion{$impuesto.cod_tipo_impuesto}" value="0" id="totalizar_pbase_retencion{$impuesto.cod_tipo_impuesto}"> %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" width="50%" class="tb-head" >Mont. Retención</td>
                                        <td>
                                            <input type="text" readonly name="totalizar_monto_retencion{$impuesto.cod_tipo_impuesto}" value="0" id="totalizar_monto_retencion{$impuesto.cod_tipo_impuesto}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" width="50%" class="tb-head" >Descripción de Retención</td>
                                        <td>
                                            <select name="cod_impuesto{$a+1}" id="cod_impuesto{$a+1}">
                                                <option>Seleccione una Retencion</option>
                                                {foreach from =$dato_impuesto  key = i item = miItem}
                                                    {if $impuesto.cod_tipo_impuesto eq $miItem.cod_tipo_impuesto}
                                                        <option value={$miItem.cod_impuesto}>{$miItem.descripcion}</option>
                                                    {/if}
                                                {/foreach}
                                            </select>
                                            <input type="hidden" size="5" name="tipo_impuesto" value="{$impuesto.cod_tipo_impuesto}" id="tipo_impuesto">
                                        </td>
                                        <td><input type="hidden" size="5" name="cod_tipo_impuesto{$impuesto.cod_tipo_impuesto}" value="{$impuesto.cod_tipo_impuesto}" id="cod_tipo_impuesto{$impuesto.cod_tipo_impuesto}"></td>
                                        <td><input type="hidden" size="5" name="i{$impuesto.cod_tipo_impuesto}" value="{$a+1}" id="i{$impuesto.cod_tipo_impuesto}"></td>
                                    </tr>
                                {/foreach}
                                <tr>
                                    <td align="right" colspan="4" width="50%" class="tb-head" >
                                        <b>Total Retenciones</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="3" class="tb-head">Monto Retencion de Impuestos</td>
                                    <td>
                                        <input type="text" readonly name="totalizar_total_retencion" value="0" id="totalizar_total_retencion">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="4" width="50%" class="tb-head" >
                                        <b>Total Facturación</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Monto a Cancelar</td>
                                    <td>
                                        <input type="text"  class="ctotalizar_" name="totalizar_monto_cancelar" value="0" id="totalizar_monto_cancelar">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="3" width="50%" class="tb-head" >Saldo Pendiente</td>
                                    <td>
                                        <input type="text" class="ctotalizar_" style="background-color: #eaeaea;" readonly name="totalizar_saldo_pendiente" value="0" id="totalizar_saldo_pendiente">
                                        <div id="info_pago_pendiente" style="border: 1px solid #dbdbdb;background-color:#fbfbfb;margin-left:5px;margin-top:5px;margin-bottom:7px;padding-left:5px;color:#504b4b;">
                                            <b>Especifique los siguientes campos:</b>
                                            <br><br>
                                            <img align="absmiddle" style="margin-bottom:5px;" src="../../../includes/imagenes/ew_calendar.gif"> Fecha Vencimiento: <br>
                                            <input type="text" size="10" readonly style="border: 1px solid black;margin-bottom:5px;" value="0000-00-00" id="fecha_vencimiento" name="fecha_vencimiento" class=""/>  Ej: 2009-11-01
                                            {literal}
                                                <script type="text/javascript">//<![CDATA[
                                                  var cal = Calendar.setup({
                                                      onSelect: function(cal) { cal.hide() }
                                                  });
                                                  cal.manageFields("fecha_vencimiento", "fecha_vencimiento", "%Y-%m-%d");
                                                //]]></script>
                                                {/literal}
                                            <br>
                                            <img align="absmiddle" src="../../../includes/imagenes/ico_view.gif"> Observacion:<br>
                                            <textarea name="observacion"></textarea>
                                            <br>
                                            <img align="absmiddle" src="../../../includes/imagenes/ico_user.gif"> Persona Contacto:<br>
                                            <input type="text" name="persona_contacto" class=""/><br>
                                            <img align="absmiddle" src="../../../includes/imagenes/ico_cel.gif"> Telefono:<br>
                                            <input type="text" name="telefono"/><br>
                                            <span style="font-size:9px;color:red;">Nota: Debe llenar todos los campos.</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Cambio</td>
                                    <td>
                                        <input type="text" class="ctotalizar_" style="background-color: #eaeaea;" readonly name="totalizar_cambio" value="0" id="totalizar_cambio">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="4" width="50%" class="tb-head" >
                                        <b>Instrumento de Pago</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >En Efectivo</td>
                                    <td>
                                        <input type="text" class="ctotalizar_" value="0" name="totalizar_monto_efectivo" id="totalizar_monto_efectivo">  (*)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Cheque</td>
                                    <td>
                                        <select name="opt_cheque" id="opt_cheque">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Monto Cheque</td>
                                    <td>
                                        <input type="text" class="ctotalizar_" value="0" name="totalizar_monto_cheque" id="totalizar_monto_cheque"> (*)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Nro. Cheque</td>
                                    <td>
                                        <input type="text" class="ctotalizar_" value="0"  name="totalizar_nro_cheque"  id="totalizar_nro_cheque">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"  width="50%" class="tb-head" >Banco</td>
                                    <td>
                                        <select class="ctotalizar_" name="totalizar_nombre_banco"  id="totalizar_nombre_banco" >
                                            <option value="0">S/I</option>
                                            {html_options output=$option_output_banco values=$option_values_banco}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Tarjeta</td>
                                    <td>
                                        <select name="opt_tarjeta" id="opt_tarjeta">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Monto Tarjeta</td>
                                    <td>
                                        <input type="text" value="0" class="ctotalizar_"   name="totalizar_monto_tarjeta"  id="totalizar_monto_tarjeta">  (*)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Nro. Tarjeta</td>
                                    <td>
                                        <input type="text" value="0" class="ctotalizar_" name="totalizar_nro_tarjeta"  id="totalizar_nro_tarjeta">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Tipo de Tarjeta</td>
                                    <td>
                                        <select class="ctotalizar_" name="totalizar_tipo_tarjeta"  id="totalizar_tipo_tarjeta" >
                                            <option value="0">S/I</option>
                                            {html_options output=$option_output_instrumento_pago_tarjeta values=$option_values_instrumento_pago_tarjeta}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Deposito</td>
                                    <td>
                                        <select name="opt_deposito" id="opt_deposito">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Monto del Deposito</td>
                                    <td>
                                        <input type="text" value="0" class="ctotalizar_"  name="totalizar_monto_deposito"  id="totalizar_monto_deposito">   (*)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Nro. de Deposito</td>
                                    <td>
                                        <input type="text" class="ctotalizar_"  name="totalizar_nro_deposito"  id="totalizar_nro_deposito" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="tb-head" >Banco Deposito</td>
                                    <td>
                                        <select class="ctotalizar_" name="totalizar_banco_deposito"  id="totalizar_banco_deposito" >
                                            <option value="0">S/I</option>
                                            {html_options output=$option_output_banco values=$option_values_banco}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Otro Documento</td>
                                    <td>
                                        <select name="opt_otrodocumento" id="opt_otrodocumento">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Tipo de Documento</td>
                                    <td>
                                        <select class="ctotalizar_" name="totalizar_tipo_otrodocumento"  id="totalizar_tipo_otrodocumento" >
                                            <option value="0">S/I</option>
                                            {html_options output=$option_output_tipo_otrodocumento values=$option_values_tipo_otrodocumento}
                                        </select>   (*)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Monto</td>
                                    <td>
                                        <input type="text" value="0" class="ctotalizar_"  name="totalizar_monto_otrodocumento"  id="totalizar_monto_otrodocumento">   (*)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="50%" class="tb-head" >Numero</td>
                                    <td>
                                        <input type="text" class="ctotalizar_"  name="totalizar_nro_otrodocumento"  id="totalizar_nro_otrodocumento" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="tb-head" >Banco</td>
                                    <td>
                                        <select class="ctotalizar_" name="totalizar_banco_otrodocumento"  id="totalizar_banco_otrodocumento" >
                                            <option value="0">S/I</option>
                                            {html_options output=$option_output_banco values=$option_values_banco}
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <center></center>
                </div>
                <input type="submit" id="PFactura2" name="PFactura2" value="Procesar">
            </div>
            <!--</contenedor factura paso 2>-->
            <input type="hidden" name="cantidadItem" id="cantidadItem">
            <input type="hidden" name="cantidadTotalItem" id="cantidadTotalItem">
            <input type="hidden" name="ivaItem" id="ivaItem">
            <input type="hidden" name="cantidadItemComprometidoByAlmacen" id="cantidadItemComprometidoByAlmacen">
            <input type="hidden" name="informacionitem" id="informacionitem">
            <input type="hidden" name="idpreciolibre" id="idpreciolibre"  value="{$idpreciolibre}">
            <input type="hidden" name="idprecio1" id="idprecio1"  value="{$idprecio1}">
            <input type="hidden" name="idprecio2" id="idprecio2"  value="{$idprecio2}">
            <input type="hidden" name="idprecio3" id="idprecio3"  value="{$idprecio3}">
            <input type="hidden" name="cantidad_impuesto" value="{$numero_impuesto[0].cantidad_impuesto}" id="cantidad_impuesto">
            <input type="hidden" name="input_totalizar_sub_total"           id="input_totalizar_sub_total" value="">
            <input type="hidden" name="input_totalizar_descuento_parcial"   id="input_totalizar_descuento_parcial" value="">
            <input type="hidden" name="input_totalizar_total_operacion"     id="input_totalizar_total_operacion" value="">
            <input type="hidden" name="input_totalizar_pdescuento_global"   id="input_totalizar_pdescuento_global" value="">
            <input type="hidden" name="input_totalizar_descuento_global"    id="input_totalizar_descuento_global" value="">
            <input type="hidden" name="input_totalizar_monto_iva"           id="input_totalizar_monto_iva" value="">
            <input type="hidden" name="input_totalizar_total_general"       id="input_totalizar_total_general" value="">
            <input type="hidden" name="input_totalizar_monto_cancelar"      id="input_totalizar_monto_cancelar" value="">
            <input type="hidden" name="input_totalizar_saldo_pendiente"     id="input_totalizar_saldo_pendiente" value="">
            <input type="hidden" name="input_totalizar_cambio"              id="input_totalizar_cambio" value="">
            <input type="hidden" name="input_totalizar_monto_efectivo" id="input_totalizar_monto_efectivo" value="">
            <input type="hidden" name="input_totalizar_monto_cheque" id="input_totalizar_monto_cheque" value="">
            <input type="hidden" name="input_totalizar_nro_cheque" id="input_totalizar_nro_cheque" value="">
            <input type="hidden" name="input_totalizar_nombre_banco" id="input_totalizar_nombre_banco" value="">
            <input type="hidden" name="input_totalizar_monto_tarjeta" id="input_totalizar_monto_tarjeta" value="">
            <input type="hidden" name="input_totalizar_nro_tarjeta" id="input_totalizar_nro_tarjeta" value="">
            <input type="hidden" name="input_totalizar_tipo_tarjeta" id="input_totalizar_tipo_tarjeta" value="">
            <input type="hidden" name="input_totalizar_monto_deposito" id="input_totalizar_monto_deposito" value="">
            <input type="hidden" name="input_totalizar_nro_deposito" id="input_totalizar_nro_deposito" value="">
            <input type="hidden" name="input_totalizar_banco_deposito" id="input_totalizar_banco_deposito" value="">
        </form>
        <div id="info" style="display:none;"></div>
    </body>
</html>
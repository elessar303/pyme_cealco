        <link type="text/css" media="screen" rel="stylesheet" href="../../libs/css/nueva_factura.css" />
        {literal}
        <style type="text/css">
            .focusInput {
                background: #47FF47;
            }

            .cajaResumenGanancia{
               padding: 0 0 10px 0px;
                border: 1px solid #DFDFDF;
                width: 300px;
                background: #EAFFFF;
                margin: 10px;
            }

            .cajaResumenGanancia .header-cajaResumenGanancia{
                width: 100%;
                background: #00C3D5;
                height: 10px;
            }

            .cajaResumenGanancia .cajaGananciaPorc{
                float:left;
                width: 50%;
            }

            .cajaResumenGanancia .cajaGananciaMonto{
                float: right;
                width: 50%;
                text-align: right;
            }


            .foto-item-tmp {
                border: 1px solid #CFCACA;
                width: 100px;
                height: 100px;
                background: #F0FAFF;
                position: absolute;
            }

        </style>
        <script type="text/javascript">

        $(document).ready(function(){
            //agregar evento al icono cesta clap
            $("#cestaclap").click(function(ev)
            {
               
                win=new Ext.Window(
                {
                    title:'Seleccionar Cestas Disponible ',
                    height:200,
                    width:359,
                    autoScroll:true,
                    modal:true,
                    bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
                    closeAction:'hide',
                    contentEl:'incluirproducto',
                    buttons:[
                    {
                        text:'Incluir',
                        icon: '../../libs/imagenes/drop-add.gif',
                        handler:function()
                        {
                            cesta=$("#cesta").val();
                            var campos="";
                            if($("#cesta").val()=="")
                            {
                                Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                                return false;
                            }
                            else
                            {
                               
                                //comienza la pesadilla
                                var data = 
                                        {
                                            productoSeleccionado : {},
                                            config_general : {}
                                        };
                                $.ajax({
                                    type: 'GET',
                                    data: 'opt=cestadatosfactura&'+'maestroid='+cesta,
                                    url: '../../libs/php/ajax/ajax.php',
                                    success: function(datos) 
                                    {
                                        
                                        this.vcampos = eval(datos);
                                        
                                        
                                        for (i = 0; i < this.vcampos.length; i++) 
                                        {
                                            //$("input[name='filtro_cantidad']").val(this.vcampos[i].cantidad);
                                            parametros=
                                            {
                                                "BuscarBy":true,
                                                "cmb_tipo_item":1,
                                                "codigoProducto" :this.vcampos[i].id_item,
                                                "descripcionProducto" : this.vcampos[i].descripcion1,
                                                "limit": 10,
                                                "opt": 'filtroItemByRCCBCesta',
                                                "start":0,
                                                "tipo_item":1,
                                                "cestamaestro" : cesta,

                                            };

                                            $.ajax({
                                                type: 'POST',
                                                data: parametros,
                                                url: '../../libs/php/ajax/ajax.php',
                                                success: function(data1) 
                                                {
                                                  
                                                   this.vcamposdata = eval(data1);
                                                    for (j = 0; j < this.vcamposdata.length; j++) 
                                                    {
                                                        campos=this.vcamposdata[j];
                                                        
                                                            $("input[name='filtro_cantidad']").val(this.vcamposdata[j].desdeb1);
                                                            data.productoSeleccionado = campos;
                                                           
                                                            $("#informacionitem").val(JSON.stringify(campos));

                                                            if( !_.str.isBlank(data.productoSeleccionado.foto)) 
                                                            {
                                                                $("#foto-item-tmp").attr("src","../../imagenes/"+data.productoSeleccionado.foto);
                                                            }
                                                            
                                                            //bandera que indica que es una cesta clap
                                                            
                                                            $("input[name='cesta_clap']").val(1);
                                                            $("input[name='filtro_descripcion']").val(data.productoSeleccionado.descripcion1);
                                                            $("input[name='filtro_referencia']").val(data.productoSeleccionado.referencia);
                                                            $("input[name='filtro_unidad']").val(data.productoSeleccionado.unidad_empaque);

                                                            $("input[name='filtro_bulto']").val(data.productoSeleccionado.cantidad_bulto);
                                                            $("input[name='filtro_kilos']").val(data.productoSeleccionado.kilos_bulto);
                                                            
                                                            $("input[name='filtro_precio']").val(data.productoSeleccionado.precio1);
                                                            $("input[name='filtro_importe']").val("0.00");

                                                            $("input[name='filtro_cantidad']").focus();
                                                            $("input[name='filtro_cantidad']").select();
                                                            setTimeout($.agregarArticuloCesta(campos),30000);
                                                    }

                                                }
                                            })

                                            
                                        }//fin dle for
                                    }
                                }); //fin del ajax
                                 win.hide();


                                ///////////////////////////////////////////////////////
                             
                                
                                ///////////////////////////////////////////////////////
                                
                            }
                                         
                           
                        },
                    },
                    {
                        text:'Cerrar',
                        icon: '../../libs/imagenes/cancel.gif',
                        handler:function()
                        {
                            win.hide();
                        }
                    }
                    ]
                }); 
                win.show();
                eventos_form.Limpiar();
                
            });
       
                //funcion para cargar los puntos 
                  $("#estado").change(function() {
                    estados = $("#estado").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getPuntos&'+'estados='+estados,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#puntodeventa").find("option").remove();
                                $("#puntodeventa").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#puntodeventa").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#puntodeventa").append("<option value='0'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#puntodeventa").append("<option value='" + this.vcampos[i].siga+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                                }
                            }
                        }); 
                        $("#puntodeventa").val(0);
                  });
           
            });
        </script>
        {/literal}

        <script type="text/javascript" src="../../libs/js/nueva_factura_rapida_totalizarfactura_pedido.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_clientes_factura_rapida.js"></script>
        <script type="text/javascript" src="../../libs/js/nueva_factura_rapida_scripts.js"></script>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada_clap.js"></script>
        <script type="text/javascript" src="../../libs/js/evento_cestaClap.js"></script>

        <script type="text/javascript" src="../../libs/js/factura.js"></script>

        <form name="formulario" id="formulario" method="post">
            <input type="hidden" name="transaccion" id="transaccion" value="factura"/>
            <input type="hidden" name="DatosCliente" value=""/>
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>

            <input type="hidden" name="precio_por_defecto" value="{$precio_por_defecto}"/>
            <input type="hidden" name="cod_almacen_defecto" value="{$cod_almacen_defecto}"/>
            <input type="hidden" id="cod_ubicacion_defecto" name="cod_ubicacion_defecto" value="{$cod_ubicacion_defecto}"/>

            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <input type="hidden" name="moneda" id="moneda" value="{$DatosGenerales[0].moneda}"/>
            <input type="hidden" name="impresora_marca" id="impresora_marca" value="{$DatosGenerales[0].impresora_marca}"/>
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
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <!--<Datos del cliente y vendedor>-->
            <div class="contenedor_factura" style="width:99%; float:left;">
                <div class="contenedor_factura" style="width:50%; height:15%; float:left;">
                    <img src="../../../includes/imagenes/ico_user.gif" style="vertical-align: middle;"/>
                    {if $datacliente[0].foto != ""}
                    <img alt="Sin Foto" src="../../imagenes/{$datacliente[0].foto}" title="Cliente: {$datacliente[0].nombre}" style="width: 70px;border: 1px solid #E7E6E6;padding: 5px;float: left;height: 70px;"/>
                    {else}
                    <img alt="Sin Foto" src="../../imagenes/sin_imagen.jpg" title="Cliente: {$datacliente[0].nombre}" style="width: 70px;border: 1px solid #E7E6E6;padding: 5px;float: left;height: 70px;"/>
                    {/if}
                    <span style="font-family:'Verdana';"><b>Cliente:&nbsp;</b>{$datacliente[0].nombre}</span><br/>
                    {if $datacliente[0].id_cliente == 0}
                    <img src="../../../includes/imagenes/ico_propiedades.gif" style="vertical-align: middle;"/>
                    <span style="font-family:'Verdana';"><b>Estado:&nbsp;</b><select  name="estado_destino" id="estado" class="form-text">
                        <option value="9999">Todos</option>
                        {html_options output=$option_values_nombre_estado values=$option_values_id_estado}
                    </select></span>
                    <br/>
                    <img src="../../../includes/imagenes/ico_propiedades.gif" style="vertical-align: middle;"/>
                    <span style="font-family:'Verdana';"><b>Establecimiento:&nbsp;</b><select  name="puntodeventa" id="puntodeventa" style="max-width:50%;">
                        <option value="0">Todos</option> 
                        {html_options output=$option_output_punto values=$option_values_punto}
                    </select></span>
                    {/if}
                    <input type="hidden" name="id_cliente" id="id_cliente" value="{$datacliente[0].id_cliente}"/>
                    <input type="hidden" name="id_fiscal" value="{$datacliente[0].rif}"/>
                    <input type="hidden" name="nombre" value="{$datacliente[0].nombre}"/>
                    <input type="hidden" name="direccion" value="{$datacliente[0].direccion}"/>
                    <input type="hidden" name="telefonos" value="{$datacliente[0].telefonos}"/>
                    <input type="hidden" name="numero_control_factura" value="{$nro_factura}"/>
                     <input type="hidden" name="cesta_clap" value=""/>
                    <br/>
                    <img src="../../../includes/imagenes/ico_user.gif" style="vertical-align: middle;"/>
                    
                    <br/>
                    <img src="../../../includes/imagenes/ico_user.gif" style="vertical-align: middle;"/>
                    <span style="font-family:'Verdana';"><b>Estado de Entrega de Materiales</b></span>
                    <select name="estado_entrega" id="estado_entrega">
                        <option value="Pendiente" selected="selected">Pendiente</option>
                    </select>
                    <br/>
                    <img src="../../../includes/imagenes/ico_propiedades.gif" style="vertical-align: middle;"/>
                    <span style="font-family:'Verdana';"><b> Facturacion</b></span>
                    <select name="facturacion" id="facturacion">
                        <option value="siscol" selected="selected">Siscol</option>
                        <option value="externa" >Externa</option>
                    </select>
                </div>
                <div class="contenedor_factura" style="width:45%; height:10%; float:right;">
                    <!--<div class="subcontenedor_factura" id="cotizaciones">
                        <img src="../../../includes/imagenes/4.png" title="Facturar Cotizaciones"/>
                        <br/>
                        <span style="font-family:'Verdana';"><b>Cotizaciones</b></span>
                    </div>-->
                    <!--<div class="subcontenedor_factura" id="notas_entrega">
                        <img src="../../../includes/imagenes/9.png" title="Facturar Notas de Entrega"/>
                        <br/>
                        <span style="font-family:'Verdana';"><b>Notas de Entrega</b></span>
                    </div>-->
                    <!--<div class="subcontenedor_factura" id="cestaclap">
                        <img src="../../libs/imagenes/foodClap.png"  width="30" height="30" title="Facturar Pedido"/>
                        <br/>
                        <span style="font-family:'Verdana';"><b>Agregar Cesta Clap</b></span>
                    </div>
                    <div class="subcontenedor_factura" id="pedidos">
                        <img src="../../../includes/imagenes/02.png" title="Facturar Pedido"/>
                        <br/>
                        <span style="font-family:'Verdana';"><b>Pedidos</b></span>
                    </div>-->
                    {if $espera eq 1}
                    <div class="subcontenedor_factura" id="factura_espera">
                        <img src="../../../includes/imagenes/65.png" title="Facturas en espera"/><!--save_f2.png-->
                        <br/>
                        <span style="font-family:'Verdana';"><b>Facturas en espera</b></span>
                    </div>
                    {/if}
                    {if $cuotas}
                    {if $tiene_cuotas gt 0}
                    <div class="subcontenedor_factura" id="cuotas">
                        <img src="../../../includes/imagenes/cuota.png" title="Facturar Cuotas"/>
                        <br/>
                        <span style="font-family:'Verdana';"><b>Cuotas ({$tiene_cuotas})</b></span>
                    </div>
                    {/if}
                    {/if}
                </div>
            </div>
            <div id="lista_pedidos" style="clear:left;" class="contenedor_factura">
                {if $pedidos}
                <table style="width:100%; background-color: buttonface;" class="listado">
                    <thead>
                        <tr>
                            <th class="tb-tit" style="text-align:center; width:100px;">C&oacute;d. Pedido</th>
                            <th class="tb-tit" style="text-align:center;">Fecha de Emisi&oacute;n</th>
                            <th class="tb-tit" style="text-align:center; width:100px;">Monto ({$DatosGenerales[0].moneda})</th>
                            <th class="tb-tit" style="text-align:center; width:80px;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$pedidos key=a item=pedido}
                        {if $a mod 2 eq 0}
                        {assign var=color value=""}
                        {else}
                        {assign var=color value="#cacacf"}
                        {/if}
                        <tr bgcolor="{$color}">
                            <td style="border:1px solid white; text-align: center;">{$pedido.cod_pedido}</td>
                            <td style="border:1px solid white; text-align: center;">{$pedido.fechaPedido}</td>
                            <td style="border:1px solid white; text-align: right; padding-right: 20px;">{$pedido.totalizar_total_general}</td>
                            <td style="border:1px solid white; text-align: center;">
                                <div id="seleccionarPedido">
                                    <input type="hidden" id="{$pedido.id_pedido}" name="{$pedido.id_pedido}" value="{$pedido.id_pedido}"/>
                                    <img title="Seleccionar Pedido Nro. {$pedido.cod_pedido}" src="../../../includes/imagenes/add.gif" style="cursor: pointer;"/>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
                <input type="hidden" id="pedido_seleccionado" name="pedido_seleccionado" value=""/>
                {else}
                <div style="background-color: #f9c0c0; border: solid red; border-width: 1px; border-radius: 5px; font-style: italic; text-align: center;">
                    Sin Registros Asociados
                </div>
                {/if}
            </div>
            <div id="lista_notas_entrega" style="clear:left;" class="contenedor_factura">
                {if $notas_entrega}
                <table style="width:100%; background-color: buttonface;" class="listado">
                    <thead>
                        <tr>
                            <th class="tb-tit" style="text-align:center; width:100px;">C&oacute;d. Nota Entrega</th>
                            <th class="tb-tit" style="text-align:center;">Fecha de Emisi&oacute;n</th>
                            <th class="tb-tit" style="text-align:center; width:100px;">Monto ({$DatosGenerales[0].moneda})</th>
                            <th class="tb-tit" style="text-align:center; width:80px;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$notas_entrega key=a item=nota_entrega}
                        {if $a mod 2 eq 0}
                        {assign var=color value=""}
                        {else}
                        {assign var=color value="#cacacf"}
                        {/if}
                        <tr bgcolor="{$color}">
                            <td style="border:1px solid white; text-align:center;">{$nota_entrega.cod_nota_entrega}</td>
                            <td style="border:1px solid white; text-align:center;">{$nota_entrega.fechaNotaEntrega}</td>
                            <td style="border:1px solid white; text-align:right; padding-right: 20px;">{$nota_entrega.totalizar_total_general}</td>
                            <td style="border:1px solid white; text-align:center;">
                                <div id="seleccionarNotaEntrega">
                                    <input type="hidden" id="{$nota_entrega.id_nota_entrega}" name="{$nota_entrega.id_nota_entrega}" value="{$nota_entrega.id_nota_entrega}"/>
                                    <img title="Seleccionar Nota Entrega Nro. {$nota_entrega.cod_nota_entrega}" src="../../../includes/imagenes/add.gif" style="cursor: pointer;"/>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
                <input type="hidden" id="nota_entrega_seleccionada" name="nota_entrega_seleccionada" value=""/>
                {else}
                <div style="background-color: #f9c0c0; border: solid red; border-width: 1px; border-radius: 5px; font-style: italic; text-align: center;">
                    Sin Registros Asociados
                </div>
                {/if}
            </div>
            <div id="lista_cotizaciones" style="clear:left;" class="contenedor_factura">
                {if $cotizaciones}
                <table style="width:100%; background-color: buttonface;" class="listado">
                    <thead>
                        <tr>
                            <th class="tb-tit" style="text-align:center; width:100px;">C&oacute;d. Cotizaci&oacute;n</th>
                            <th class="tb-tit" style="text-align:center;">Fecha de Emisi&oacute;n</th>
                            <th class="tb-tit" style="text-align:center; width:100px;">Monto ({$DatosGenerales[0].moneda})</th>
                            <th class="tb-tit" style="text-align:center; width:80px;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$cotizaciones key=a item=cotizacion}
                        {if $a mod 2 eq 0}
                        {assign var=color value=""}
                        {else}
                        {assign var=color value="#cacacf"}
                        {/if}
                        <tr bgcolor="{$color}">
                            <td style="border:1px solid white; text-align:center;">{$cotizacion.cod_cotizacion}</td>
                            <td style="border:1px solid white; text-align:center;">{$cotizacion.fecha_cotizacion}</td>
                            <td style="border:1px solid white; text-align:right; padding-right: 20px;">{$cotizacion.totalizar_total_general}</td>
                            <td style="border:1px solid white; text-align:center;">
                                <div id="seleccionarCotizacion">
                                    <input type="hidden" id="{$cotizacion.id_cotizacion}" name="{$cotizacion.id_cotizacion}" value="{$cotizacion.id_cotizacion}"/>
                                    <img title="Seleccionar Cotizaci&oacute;n Nro. {$cotizacion.cod_cotizacion}" src="../../../includes/imagenes/add.gif" style="cursor: pointer;"/>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
                <input type="hidden" id="cotizacion_seleccionada" name="cotizacion_seleccionada" value=""/>
                {else}
                <div style="background-color: #f9c0c0; border: solid red; border-width: 1px; border-radius: 5px; font-style: italic; text-align: center;">
                    Sin Registros Asociados
                </div>
                {/if}
            </div>
            <div id="lista_cuotas" style="clear:left;" class="contenedor_factura">
                {if $tiene_cuotas gt 0}
                <table style="width:100%; background-color: buttonface;" class="listado">
                    <thead>
                        <tr>
                            <th class="tb-tit" style="text-align:center; width:100px;">C&oacute;digo</th>
                            <th class="tb-tit" style="text-align:center;">Descripci&oacute;n</th>
                            <th class="tb-tit" style="text-align:center; width:100px;">Monto ({$DatosGenerales[0].moneda})</th>
                            <th class="tb-tit" style="text-align:center; width:80px;" colspan="2">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$cuotas key=a item=cuota}
                        {if $a mod 2 eq 0}
                        {assign var=color value=""}
                        {else}
                        {assign var=color value="#cacacf"}
                        {/if}
                        <tr bgcolor="{$color}" style="border:1px solid white;">
                            <td style="border:1px solid white; text-align:right; padding-right: 20px;">{$cuota.id}</td>
                            <!--td style="border:1px solid white; text-align:right; padding-right: 20px;">{$cuota.id_item}</td-->
                            <td style="border:1px solid white; text-align:left; padding-left: 20px;">{$cuota.descripcion} {$cuota.anio}-{if $cuota.mes lt 10}0{$cuota.mes}{else}{$cuota.mes}{/if}</td>
                            <td style="border:1px solid white; text-align:right; padding-right: 20px;">{$cuota.precio}</td>
                            <td style="border:1px solid white; text-align:center; width: 40px;">
                                <div id="seleccionarCuota">
                                    <!-- Este input hidden no funciona para lo que quiero hacer hacer aqui -->
                                    <input type="hidden" name="{$cuota.id_cuota}" value="{$cuota.id_cuota}"/>
                                    <img title="Seleccionar Cuota Nro. {$cuota.id}" src="../../../includes/imagenes/add.gif" style="cursor: pointer;"/>
                                </div>
                            </td>
                            <td style="border:1px solid white; text-align:center; width: 40px;">
                                <input type="checkbox" name="cuotas_seleccionadas[]" id="cuotas_seleccionadas" value="{$cuota.id}" />
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="border: 1px solid white; text-align: right; padding-right: 10px;" colspan="2">
                                <i style="padding-bottom: 0;">Para los elementos que est&aacute;n seleccionados</i>&nbsp;
                                &nbsp;
                                <a href="#" id="incluir_cuotas" style="color: blue; padding-bottom: 0;">Incluir</a>
                            </td>
                            <td style="border: 1px solid white; text-align: right;" colspan="2">
                                <a href="#" id="marcar" style="color: blue; padding-bottom: 0;">Marcar</a>
                                &nbsp;/&nbsp;
                                <a href="#" id="desmarcar" style="color: blue; padding-bottom: 0;">Desmarcar</a>
                            </td>
                            <td style="border: 1px solid white; width: 15px; text-align: center;">
                                <!--<img src="../../../includes/imagenes/arrow_rtr.gif" width="25" height="15"/> -->
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <input type="hidden" id="cuota_seleccionada" name="cuota_seleccionada" value=""/>
                {/if}
            </div>
            <!--<Datos de la factura>-->
            <div style="clear:left;" class="contenedor_factura">
                <div style="float: left; margin-right: 20px;">
                    <!--Fecha: -->
                    <input type="hidden" name="input_fechaFactura" id="input_fechaFactura" value='{$smarty.now|date_format:"%Y-%m-%d"}'/>
                    <!--div style="color:#4e6a48" id="fechaFactura">{*$smarty.now|date_format:"%d-%m-%Y"*}</div-->
                </div>
                <!--div style=" margin-right: 20px;">
                    Factura Numero<div style="font-size:15px;color:red;" id="numFactura">{*$nro_factura*}</div>
                </div-->
                {literal}
                    <script type="text/javascript">//<![CDATA[
                        $(document).ready(function() {
                            $("#detalle_factura_").show();
                            sw = 1;
                            $.setValoresInput = function(nombreObjetoDestino, nombreObjetoActual) {
                                $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
                            };
                            $("#lick_detalle").click(function() {
                                if (sw === 0) {
                                    $("#detalle_factura_").show(150);
                                    $(this).html("<div><img style=\"vertical-align: middle\" src=\"../../../includes/imagenes/drop-add2.gif\"> Ocultar detalles</div>");
                                    sw = 1;
                                } else {
                                    if (sw === 1) {
                                        $("#detalle_factura_").hide(150);
                                        $(this).html("<div><img style=\"vertical-align: middle\" src=\"../../../includes/imagenes/drop-add.gif\"> Ver detalles</div>");
                                        sw = 0;
                                    }
                                }
                            });
                        });//]]>
    </script>
    {/literal}
    <div id="lick_detalle" style="cursor:pointer; width:150px;"><img style="vertical-align: middle;" src="../../../includes/imagenes/drop-add2.gif"/> Ocultar detalles</div>
    <div id="detalle_factura_">
    <br>
        <div class="resumen">
            Sub-Total
            <div style="font-size:20px; color:#2e931a;" id="subTotal">0.00 {$DatosGenerales[0].moneda}</div>
            <input type="hidden" name="input_subtotal" id="input_subtotal" value=""/>
        </div>
        <div class="resumen">
            Descuento
            <input type="hidden" name="input_descuentosItemFactura" id="input_descuentosItemFactura" value=""/>
            <div style="font-size:20px; color:red; text-decoration:line-through;" id="descuentosItemFactura">0.00 {$DatosGenerales[0].moneda}</div>
        </div>
        <div class="resumen">
            Monto Items
            <input type="hidden" name="input_montoItemsFactura" id="input_montoItemsFactura" value=""/>
            <div style="font-size:20px; color:#2e931a;" id="montoItemsFactura">0.00 {$DatosGenerales[0].moneda}</div>
        </div>
        <div class="resumen">
            {$DatosGenerales[0].nombre_impuesto_principal}
            <input type="hidden" name="input_ivaTotalFactura" id="input_ivaTotalFactura" value=""/>
            <div style="font-size:20px; color:#2e931a;" id="ivaTotalFactura">0.00 {$DatosGenerales[0].moneda}</div>
        </div>
        <div class="resumen">
            Total
            <input type="hidden" name="input_TotalTotalFactura" id="input_TotalTotalFactura" value=""/>
            <div style="font-size:20px; color:#00005e;" id="TotalTotalFactura">0.00 {$DatosGenerales[0].moneda}</div>
        </div>
        

        <input type="hidden" name="total_bultos" value="">
        <input type="hidden" name="peso_total_item" value="">
        <input type="hidden" name="total_m3" value="">
        <input type="hidden" name="total_ft3" value="">

        <input type="hidden" name="total_porcentaje_ganancia" value="">
        <input type="hidden" name="total_monto_ganancia_total" value="">

       
        <div style="clear:both;"></div>
        {if ($perfil neq '3') }  
       <!--  <div style="float:right" class="cajaResumenGanancia"> -->
            <!--<div class="header-cajaResumenGanancia"></div>
            <div class="cajaGananciaPorc">-->
                <input type="hidden" name="input_TotalDisponible" id="input_total_ganancia_porcenaje" value=""/>
                <!--<div style="font-size:15px; color:#00005e;padding: 5px 0 0 5px;" id="TotalDisponible"><span id="total_ganancia_porcenaje">0.00</span> %</div>-->
                <!-- </div> -->

            <!-- <div class="cajaGananciaMonto"> -->
                <input type="hidden" name="input_TotalDisponible" id="input_total_ganancia_monto" value=""/>
             <!--   <div style="font-size:15px; color:#00005e;padding: 5px 5px 0px 0px;">{$DatosGenerales[0].moneda} <span id="total_ganancia_monto">0.00</span></div>-->
            <!-- </div>
            <div style="clear:both;"></div> -->
        <!-- </div> -->
          {/if}
        <div style="clear:both;"></div>
        <input type="hidden" name="input_cantidad_items" id="input_cantidad_items" value=""/>
        <div class="span_cantidad_items">
            <span style="font-size: 15px; font-style: italic;">Cantidad de Items: 0</span>
        </div>
    </div>
</div>

<div class="contenedor_factura">
    <div id="tabs_pasos">
        <div id="tab1_pasos" class="click_paso_ON">
            <img src="../../../includes/imagenes/113.png" width="20" height="20" style="vertical-align: middle;"/><b>PASO 1</b>
        </div>
        <div id="tab2_pasos" class="click_paso_OFF">
            <img src="../../../includes/imagenes/6_off.png" width="20" height="20" style="vertical-align: middle;"/><b>PASO 2</b>
        </div>
        <div style="display:inline; float:right;"  id="procesar"  class="click_paso_OFF">
        <input style="cursor: pointer" type="button" id="PFactura2" name="PFactura2" value="Procesar Factura"/>
        </div>
    </div>
    <br/><br/><br/>
    <!--<contenedor factura paso 1>-->
    <div id="contenedorTAB_factura_paso1">
        <table style="width:100%">
            <tr style="vertical-align:top;" id="PanelFactura">
                <td colspan="2" style="vertical-align: central;">
                    <div id="contenedorTAB" style="background-color: white;">
                        <table>
                            <tr>
                                <td>
                                    <!-- Inicio Item -->
                                    <div id="div_tab1">
                                        <table style="width:100%;">
                                            <tr>
                                                <td class="etiqueta">
                                                    <div id="descripcion_tipo_forma">
                                                        <!--span style="font-family:'Verdana'; font-weight: bold;">Item</span-->
                                                        <span style="font-family:'Verdana';">Item</span>
                                                    </div>
                                                </td>
                                                <td class="elemento">
                                                    <input type="hidden" name="cod_item_forma"/>
                                                    <input type="hidden" name="id_item"/>
                                                    <input type="text" name="descripcion_input_item" id="descripcion_item" size="50" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td class="etiqueta">
                                                                <!--span style="font-family:'Verdana';"><b>Almac&eacute;n</b></span-->
                                                                <span style="font-family:'Verdana';">Almac&eacute;n</span>
                                                            </td>
                                                            <td class="elemento">
                                                                <select name="cod_almacen" id="cod_almacen">
                                                                </select>
                                                            </td>
                                                            <td class="etiqueta">
                                                                <span style="font-family:'Verdana';">Tipo Precio</span>
                                                            </td>
                                                            <td class="elemento">
                                                                <select name="cod_tipo_precio" id="cod_tipo_precio">
                                                                    {html_options values=$option_values_tipo_precio output=$option_output_tipo_precio selected=$option_selected_tipo_precio}
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="etiqueta">
                                                                <span style="font-family:'Verdana';">Existencia</span>
                                                            </td>
                                                            <td class="elemento">
                                                                <input type="text" size="10" name="cantidadExistente" value="0" disabled id="cantidadExistente"/>
                                                            </td>

                                                            <td class="etiqueta">
                                                                <span style="font-family:'Verdana';">Precio sin {$DatosGenerales[0].nombre_impuesto_principal}</span>
                                                            </td>
                                                            <td class="elemento">
                                                                <input type="text" size="10"  name="precioProductoPedido" value="0" readonly id="precioProductoPedido"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="etiqueta">
                                                                <span style="font-family:'Verdana';">Cantidad</span>
                                                            </td>
                                                            <td class="elemento">
                                                                <input type="text" size="10" name="cantidadPedido" value="0" id="cantidadPedido">
                                                            </td>
                                                            <td style="border-top-style: dotted; border-width: 1px; border-color: #8d8f91; vertical-align:middle; width:20%; text-align: right; padding-right: 15px; white-space: nowrap; vertical-align:middle; width:20%; text-align: right; padding-right: 15px; white-space: nowrap;">
                                                                <!--span style="font-family:'Verdana';"><b>Monto Descto.</b></span-->
                                                                <span style="font-family:'Verdana';">Monto Descto.</span>
                                                            </td>
                                                            <td class="elemento">
                                                                <input type="text" name="montodescuentoPedido"  value="0"  size="10"  readonly id="montodescuentoPedido"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="etiqueta">
                                                                <span style="font-family:'Verdana';">Descuento</span>
                                                            </td>
                                                            <td class="elemento">
                                                                <input type="text" name="descuentoPedido" value="0" title="Descuento maximo del cliente: {$datacliente[0].porc_parcial} %" size="10"  id="descuentoPedido"/>     %
                                                            </td>
                                                            <span style="font-family:'Verdana';"><b>Monto Descto.</b></span>
                                                        </td>
                                                        <td class="elemento">
                                                            <input type="text" name="montodescuentoPedido"  value="0"  size="10"  readonly id="montodescuentoPedido"/>
                                                        </td-->
                                                        <td class="etiqueta">
                                                            <!--span style="font-family:'Verdana';"><b>Total</b></span-->
                                                            <span style="font-family:'Verdana';">Total</span>
                                                        </td>
                                                        <td class="elemento">
                                                            <input type="text" value="0" size="10" name="totalPedido" readonly id="totalPedido"/>
                                                        </td>
                                                    </tr>
                                                    <td style="background-color:white; vertical-align:top;">
                                                    </td>
                                                    <td style="background-color:white; vertical-align:top;">
                                                    </td>
                                                    <td style="background-color:#5084a9; color:white; padding-left:20px; padding-top:5px; vertical-align:top; width:100px;">
                                                        <span style="font-family:'Verdana';"><b>Total</b></span>
                                                    </td>
                                                    <td colspan="2" style="background-color:white; vertical-align:top;">
                                                        <input type="text" value="0" size="10" name="totalPedido" readonly id="totalPedido"/>
                                                    </td>
                                                </tr-->
                                                <tr>
                                                    <td colspan="2" style="background-color:white; vertical-align:top;">
                                                    </td>
                                                </td>
                                                <td style="background-color:white; vertical-align: central;">
                                                </td-->
                                                <td colspan="2" style="background-color:white; vertical-align:top;">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td><span style="float:left"></span></td>
                                                                <td style="width:75px;">
                                                                    <table style="cursor: pointer;" class="btn_bg" id="addTabla">
                                                                        <tr>
                                                                            <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                                            <td class="btn_bg"><img src="../../../includes/imagenes/drop-add.gif" width="16" height="16" /></td>
                                                                            <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Incluir</td>
                                                                            <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <td width="75">
                                                                    <table style="cursor: pointer;" class="btn_bg" id="cancelaradd">
                                                                        <tr>
                                                                            <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                                            <td class="btn_bg"><img src="../../../includes/imagenes/next_to_prove/b_drop.png" width="16" height="16" /></td>
                                                                            <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Cancelar</td>
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
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- /Fin Inicio Item -->
                    </td>
                    <td class="elemento">
                        <!-- Inicio Detalle Item -->
                        <div>
                            <table style="border: 1px solid #949494;">
                                <tr>
                                    <td style="vertical-align: top;">
                                        <table id="tabla_total" style="background-color: white;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; font-size: 20px; color: #00005e; font-style: italic;">Precio</th>
                                                    <th style="text-align: center; font-size: 20px; color: #00005e; font-style: italic;">Con {$DatosGenerales[0].nombre_impuesto_principal}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input readonly class="campo_decimal" id="fila_precio1" name="precio1" value="{$campos_item[0].precio1}" type="text" size="10" style="font-size: 20px; text-align: right; padding-right: 20px;"/></td>
                                                    <td><input readonly class="campo_decimal" id="fila_precio1_iva" value="{$campos_item[0].coniva1}" name="coniva1" type="text" size="10" style="font-size: 20px; text-align: right; padding-right: 20px;"/></td>
                                                </tr>
                                                <tr>
                                                    <td><input readonly class="campo_decimal" id="fila_precio2" name="precio2" value="{$campos_item[0].precio2}" type="text" size="10" style="font-size: 20px; text-align: right; padding-right: 20px;"/></td>
                                                    <td><input readonly class="campo_decimal" id="fila_precio2_iva" value="{$campos_item[0].coniva2}" name="coniva2" type="text" size="10" style="font-size: 20px; text-align: right; padding-right: 20px;"/></td>
                                                </tr>
                                                <tr>
                                                    <td><input readonly class="campo_decimal" id="fila_precio3" name="precio3" value="{$campos_item[0].precio3}" type="text" size="10" style="font-size: 20px; text-align: right; padding-right: 20px;"/></td>
                                                    <td><input readonly class="campo_decimal" id="fila_precio3_iva" value="{$campos_item[0].coniva3}" name="coniva3" type="text" size="10" style="font-size: 20px; text-align: right; padding-right: 20px;"/></td>
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
        <div>
            <table id="tabla-productos">
                <thead>
                    <tr>
                        <th class="tb-tit">C&oacute;digo</th>
                        <th class="tb-tit">Referencia</th>
                        <th class="tb-tit">Descripci&oacute;n</th>
                        <th class="tb-tit">Unidad</th>
                        <th class="tb-tit">Bulto por Cantidad</th>
                        <th class="tb-tit">Bulto por Kilos</th>
                        <th class="tb-tit">Cantidad</th>
                        <th class="tb-tit">Bulto</th>
                        <th class="tb-tit">Descuento</th>
                        <th class="tb-tit">Precio</th>
                        <th class="tb-tit">Importe</th>
                        <th class="tb-tit">Acci&oacute;n</th>
                    </tr>
                    <tr style="background: #616161;">
                        <th>
                            <input type="text" name="filtro_codigo" style="width: 100%;" class="p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_referencia" style="background: #DDD;" readonly="readonly" class="w200 p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_descripcion" style="background: #DDD;" readonly="readonly" class="w200 p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_unidad" readonly="readonly" style="width: 100%;background: #DDD;" class="p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_bulto" readonly="readonly" value="0.00" style="width: 100%;background: #DDD;" class="p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_kilos" readonly="readonly" value="0.00" style="width: 100%;background: #DDD;" class="p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_cantidad" value="0"style="width: 100%;" class="p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_bultos_total" readonly="readonly"  value="0" style="width: 100%;background: #DDD;" class="p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_descuento" value="0.00" style="width: 100%;" class="p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_precio" readonly="readonly" value="0.00" style="width: 100%;background: #DDD;" class="p4">
                        </th>
                        <th>
                            <input type="text" name="filtro_importe" readonly="readonly" value="0.00" style="width: 100%;background: #DDD;" class="p4">
                        </th>
                        <th style="text-align:center;"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <div class="grid">
                <table style="width:100%;" class="lista">
                    <thead>
                        <tr>
                            <th style="text-align: center" class="tb-tit">Img</th>
                            <th style="text-align: center" class="tb-tit">C&oacute;digo</th>
                            <th style="text-align: center" class="tb-tit">Descripci&oacute;n</th>
                            <th style="text-align: center" class="tb-tit">Unidad</th>
                            <th style="text-align: center" class="tb-tit"><span title="Bulto por cantidad">Bulto por..</span></th>
                            <th style="text-align: center;width:30px;" class="tb-tit"></th>
                            <th style="text-align: center" class="tb-tit">Cantidad</th>
                            <th style="text-align: center" class="tb-tit">Bultos</th>
                            <th style="text-align: center" title="Precio sin {$DatosGenerales[0].nombre_impuesto_principal}" class="tb-tit">Precio</th>
                            <th style="text-align: center" class="tb-tit">Descuento</th>
                            <th style="text-align: center" title="% del Descuento" class="tb-tit">%</th>
                            <th style="text-align: center" class="tb-tit">Total Sin {$DatosGenerales[0].nombre_impuesto_principal}</th>
                            <th style="text-align: center" class="tb-tit">{$DatosGenerales[0].nombre_impuesto_principal}</th>
                            <th style="text-align: center" class="tb-tit">Total con {$DatosGenerales[0].nombre_impuesto_principal}</th>
                            <th style="text-align: center" class="tb-tit">Opci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr class="sf_admin_row_1">
                            <td colspan="10">
                                <div class="span_cantidad_items">
                                    <span style="font-size: 14px; font-style: italic;">Cantidad de Items: 0</span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<!--</contenedor factura paso 1>-->


<!--<contenedor factura paso 2 >-->
<div id="contenedorTAB_factura_paso2">
    <div id="divTotalizarFactura">
        <div id="tabs">
            <table style="margin-left:20px;" >
                <tr style="height:25px;">
                    <td id="tab1" class="tab">
                        <img src="../../../includes/imagenes/1.png" width="20" height="20" style="vertical-align: middle;"/><b>Totalizar Factura </b>&nbsp;
                    </td>
                    <td>&nbsp;&nbsp;</td>
                    <!--<td id="tab2" class="tab">
                        <img src="../../../includes/imagenes/1.png" width="20" height="20" style="vertical-align: middle;"/><b>Retenciones</b>&nbsp;
                    </td>
                    <td>&nbsp;&nbsp;</td>-->
                    <td id="tab3" class="tab">
                        <img src="../../../includes/imagenes/1.png" width="20" height="20" style="vertical-align: middle;"/><b>Gasto</b>&nbsp;
                    </td>
                    <td>&nbsp;&nbsp;</td>
                    <td id="tab5" class="tab">
                     <b>Formato de Salida</b>&nbsp;
                 </td>
                 <td>&nbsp;&nbsp;</td>
                 <td id="tab4" class="tab">
                    <img src="../../../includes/imagenes/1.png" width="20" height="20" style="vertical-align: middle;"/><b>Cerrar Orden</b>&nbsp;
                </td>
            </tr>
        </table>
    </div>
    <div id="contenedorTAB21">
        <!-- TAB1 -->
        <div class="tabpanel1">
            <table>
            
                <!--<tr>
                <td colspan="3" class="tb-head" style="width:50%;">
                    Pago
                </td>
                <td >
                    
                    <input type="radio" name="forma_pago" id="pago_contado" value="contado"  /> Contado
                    <input type="radio" name="forma_pago" id="pago_credito" value="credito" checked /> Cr&eacute;dito
                </td>
            </tr>-->
                <tr>
                    <td colspan="3" class="tb-head" style="width:50%;">
                        Sub Total
                    </td>
                    <td>
                        <input type="text" readonly name="totalizar_sub_total" value="" id="totalizar_sub_total"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head">
                        Descuento Parcial
                    </td>
                    <td>
                        <input type="text" readonly name="totalizar_descuento_parcial" id="totalizar_descuento_parcial"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head">
                        Total Operaci&oacute;n
                    </td>
                    <td>
                        <input type="text" readonly name="totalizar_total_operacion" id="totalizar_total_operacion"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head" style="vertical-align:top;">
                        Descuento Global
                    </td>
                    <td>
                        <input type="text" class="ctotalizar_" size="2" value="0" name="totalizar_pdescuento_global" id="totalizar_pdescuento_global"/> % = {$DatosGenerales[0].moneda}
                        <input type="text" class="ctotalizar_" style="width: 100px" value="0" name="totalizar_descuento_global" id="totalizar_descuento_global"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head" style="width:50%;">
                        Base Imponible
                    </td>
                    <td>
                        <input readonly type="text" value="0" name="totalizar_base_imponible" id="totalizar_base_imponible"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head" style="vertical-align:top;">
                        Monto {$DatosGenerales[0].nombre_impuesto_principal}
                    </td>
                    <td>
                        <input type="text" readonly name="totalizar_monto_iva" id="totalizar_monto_iva"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head" style="vertical-align:top;">
                        Monto Exento de {$DatosGenerales[0].nombre_impuesto_principal}
                    </td>
                    <td>
                        <input type="text" readonly name="totalizar_monto_exento" id="totalizar_monto_exento"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head" style="width:50%;">
                        Total General
                    </td>
                    <td>
                        <input type="text" readonly name="totalizar_total_general" id="totalizar_total_general"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head" style="width:50%;">
                        Total Retenciones
                    </td>
                    <td>
                        <input type="text" readonly name="totalizar_total_retencion" value="0" id="totalizar_total_retencion"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head" style="width:50%;">
                        Total Factura Menos Retenciones
                    </td>
                    <td>
                        <input type="text" readonly name="totalizar_total_factura" value="0" id="totalizar_total_factura"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="tabpanel3">
        <table style="width:100%;">
            <tr>
                <td colspan="4" class="tb-head" style="text-align:right; width:100%;">
                    <b>&nbsp;&nbsp;</b>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Total F.O.B.
                </td>
                <td colspan="2">
                    <input type="text" value="0.00" name="total_fob"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Traspaso/Salida
                </td>
                <td colspan="2">
                    <input type="text" class="input-gastos" name="transpaso_salida" value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Transporte
                </td>
                <td colspan="2">
                    <input type="text" class="input-gastos"  name="transporte" value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Empaques
                </td>
                <td colspan="2">
                    <input type="text" class="input-gastos" name="empaques" value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Seguro
                </td>
                <td colspan="2">
                    <input type="text" class="input-gastos" name="seguro" value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Flete
                </td>
                <td colspan="2">
                    <input type="text" class="input-gastos" name="flete" value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Comisiones
                </td>
                <td colspan="2">
                    <input type="text" class="input-gastos" name="comisiones" value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Otros
                </td>
                <td colspan="2">
                    <input type="text" class="input-gastos" name="otros" value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Manejo
                </td>
                <td colspan="2">
                    <input type="text" class="input-gastos" name="manejo"  value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="tb-head" style="width:50%;">
                    &nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Total F.O.B.
                </td>
                <td colspan="2">
                    <input type="text"  name="total_fob_gatos" value="0.00"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Copias
                </td>
                <td colspan="2">
                    <input type="number" name="copias" value="1"/>
                </td>
            </tr>
        </table>
    </div>

    <div class="tabpanel5">
            <table style="width:100%;">
                <tr>
                    <td colspan="4" class="tb-head" style="text-align:right; width:100%;">
                        <b>&nbsp;&nbsp;</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="tb-head" style="width:50%;">
                        Tipo
                    </td>
                    <td colspan="2">
                        <select name="forma_salida_tipo">
                            {html_options output=$option_output_formato_salida_tipo values=$option_values_formato_salida_tipo}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="tb-head" style="width:50%;">
                        Cliente Traspaso
                    </td>
                    <td colspan="2">
                        <input type="text" style="width:300px;" name="forma_salida_cliente" id="forma_salida_cliente" value=""/> <a id="btnFiltroFormatoSalidaCliente" href="#">Buscar Cliente</a>
                        <input type="hidden" id="forma_salida_id_cliente" name="forma_salida_id_cliente"/> 
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="tb-head" style="width:50%;">
                        Vía
                    </td>
                    <td colspan="2">
                       <select name="forma_salida_via">
                        {html_options output=$option_output_formato_salida_via values=$option_values_formato_salida_via}
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tb-head" style="width:50%;">
                    Marca
                </td>
                <td colspan="2">
                    <input type="text" name="forma_salida_marca" value=""/>
                </td>
            </tr>
        </table>
    </div>
    <div class="tabpanel4">
                        
                        <table style="display:none">
                            <tr>
                                <td colspan="4" class="tb-head" style="text-align:right; width:50%;">
                                    <b>Total Facturaci&oacute;n</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">
                                    Monto a Cancelar
                                </td>
                                <td colspan="2">
                                    <input type="text" class="ctotalizar_" name="totalizar_monto_cancelar" value="0" id="totalizar_monto_cancelar"/>
                                    <input type="radio" name="forma_pago" id="pago_contado" value="contado" checked /> Contado
                                    <input type="radio" name="forma_pago" id="pago_credito" value="credito" /> Cr&eacute;dito
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="vertical-align:top; width:50%;">
                                    Saldo Pendiente
                                </td>
                                <td colspan="2">
                                    <input type="text" class="ctotalizar_" style="background-color: #eaeaea;" readonly name="totalizar_saldo_pendiente" value="0" id="totalizar_saldo_pendiente"/>
                                    <div id="info_pago_pendiente" style="border: 1px solid #dbdbdb;background-color:#fbfbfb;margin-left:5px;margin-top:5px;margin-bottom:7px;padding-left:5px;color:#504b4b;">
                                        <b>Especifique los siguientes campos:</b>
                                        <br/><br/>
                                        <img align="absmiddle" style="margin-bottom:5px;" src="../../../includes/imagenes/ew_calendar.gif"/> Fecha Vencimiento: <br/>
                                        <input type="text" size="10" readonly style="border: 1px solid black; margin-bottom:5px;" value="0000-00-00" id="fecha_vencimiento" name="fecha_vencimiento" class=""/>  Ej: 2009-11-01
                                        {literal}
                                            <script type="text/javascript">//<![CDATA[
                                                var cal = Calendar.setup({
                                                    onSelect: function(cal) {
                                                        cal.hide();
                                                    }
                                                });
                                                cal.manageFields("fecha_vencimiento", "fecha_vencimiento", "%Y-%m-%d");
                                                //]]></script>
                                            {/literal}
                                        <br/>
                                        <img align="absmiddle" src="../../../includes/imagenes/ico_view.gif"/> Observaci&oacute;n:<br/>
                                        <textarea name="observacion"></textarea>
                                        <br/>
                                        <img align="absmiddle" src="../../../includes/imagenes/ico_user.gif"/> Persona Contacto:<br/>
                                        <input type="text" name="persona_contacto" class=""/><br/>
                                        <img align="absmiddle" src="../../../includes/imagenes/ico_cel.gif"/> Tel&eacute;fono:<br/>
                                        <input type="text" name="telefono"/><br/>
                                        <span style="font-size:9px;color:red;">
                                            Nota: Debe llenar todos los campos.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">
                                    Cambio
                                </td>
                                <td colspan="2">
                                    <input type="text" class="ctotalizar_" style="background-color: #eaeaea;" readonly name="totalizar_cambio" value="0" id="totalizar_cambio"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="tb-head" style="text-align:right; width:50%;">
                                    <b>Instrumento de Pago</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">
                                    En Efectivo
                                </td>
                                <td colspan="2">
                                    <input type="text" class="ctotalizar_" value="0" name="totalizar_monto_efectivo" id="totalizar_monto_efectivo"/>  (*)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Cheque</td>
                                <td colspan="2">
                                    <select name="opt_cheque" id="opt_cheque">
                                        <option value="0">No</option>
                                        <option value="1">S&iacute;</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Monto Cheque</td>
                                <td colspan="2">
                                    <input type="text" class="ctotalizar_" value="0" name="totalizar_monto_cheque" id="totalizar_monto_cheque"/> (*)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Nro. Cheque</td>
                                <td colspan="2">
                                    <input type="text" class="ctotalizar_" value="0" name="totalizar_nro_cheque" id="totalizar_nro_cheque"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" width="50%" class="tb-head">Banco</td>
                                <td colspan="2">
                                    <select class="ctotalizar_" name="totalizar_nombre_banco" id="totalizar_nombre_banco" >
                                        <option value="0">S/I</option>
                                        {html_options output=$option_output_banco values=$option_values_banco}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Tarjeta</td>
                                <td colspan="2">
                                    <select name="opt_tarjeta" id="opt_tarjeta">
                                        <option value="0">No</option>
                                        <option value="1">S&iacute;</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Monto Tarjeta</td>
                                <td colspan="2">
                                    <input type="text" value="0" class="ctotalizar_" name="totalizar_monto_tarjeta" id="totalizar_monto_tarjeta"/>  (*)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Nro. Tarjeta</td>
                                <td colspan="2">
                                    <input type="text" value="0" class="ctotalizar_" name="totalizar_nro_tarjeta" id="totalizar_nro_tarjeta"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Tipo de Tarjeta</td>
                                <td colspan="2">
                                    <select class="ctotalizar_" name="totalizar_tipo_tarjeta"  id="totalizar_tipo_tarjeta" >
                                        <option value="0">S/I</option>
                                        {html_options output=$option_output_instrumento_pago_tarjeta values=$option_values_instrumento_pago_tarjeta}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Dep&oacute;sito</td>
                                <td colspan="2">
                                    <select name="opt_deposito" id="opt_deposito">
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Monto del Dep&oacute;sito</td>
                                <td colspan="2">
                                    <input type="text" value="0" class="ctotalizar_" name="totalizar_monto_deposito" id="totalizar_monto_deposito"/>   (*)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Nro. de Dep&oacute;sito</td>
                                <td colspan="2">
                                    <input type="text" class="ctotalizar_" name="totalizar_nro_deposito" id="totalizar_nro_deposito" value="0"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head">Banco Dep&oacute;sito</td>
                                <td colspan="2">
                                    <select class="ctotalizar_" name="totalizar_banco_deposito" id="totalizar_banco_deposito">
                                        <option value="0">S/I</option>
                                        {html_options output=$option_output_banco values=$option_values_banco}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Otro Documento</td>
                                <td colspan="2">
                                    <select name="opt_otrodocumento" id="opt_otrodocumento">
                                        <option value="0">No</option>
                                        <option value="1">S&iacute;</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Tipo de Documento</td>
                                <td colspan="2">
                                    <select class="ctotalizar_" name="totalizar_tipo_otrodocumento" id="totalizar_tipo_otrodocumento">
                                        <option value="0">S/I</option>
                                        {html_options output=$option_output_tipo_otrodocumento values=$option_values_tipo_otrodocumento}
                                    </select>   (*)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Monto</td>
                                <td colspan="2">
                                    <input type="text" value="0" class="ctotalizar_" name="totalizar_monto_otrodocumento" id="totalizar_monto_otrodocumento"/>   (*)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head" style="width:50%;">Numero</td>
                                <td colspan="2">
                                    <input type="text" class="ctotalizar_" name="totalizar_nro_otrodocumento" id="totalizar_nro_otrodocumento" value="0"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="tb-head">Banco</td>
                                <td colspan="2">
                                    <select class="ctotalizar_" name="totalizar_banco_otrodocumento" id="totalizar_banco_otrodocumento" >
                                        <option value="0">S/I</option>
                                        {html_options output=$option_output_banco values=$option_values_banco}
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
            </div>
                        
                        <!--input style="cursor: pointer" type="submit" id="PFacturaEspera" name="PFacturaEspera" value="Factura en Espera"/-->
                    </div>
                    <!--</contenedor factura paso 2>-->
                </div>
                <input type="hidden" name="cantidadItem" id="cantidadItem"/>
                <input type="hidden" name="cantidadTotalItem" id="cantidadTotalItem"/>
                <input type="hidden" name="ivaItem" id="ivaItem"/>
                <input type="hidden" name="cantidadItemComprometidoByAlmacen" id="cantidadItemComprometidoByAlmacen"/>
                <input type="hidden" name="informacionitem" id="informacionitem"/>
                <input type="hidden" name="idpreciolibre" id="idpreciolibre" value="{$idpreciolibre}"/>
                <input type="hidden" name="idprecio1" id="idprecio1" value="{$idprecio1}"/>
                <input type="hidden" name="idprecio2" id="idprecio2" value="{$idprecio2}"/>
                <input type="hidden" name="idprecio3" id="idprecio3" value="{$idprecio3}"/>
                <input type="hidden" name="cantidad_impuesto" value="{$numero_impuesto[0].cantidad_impuesto}" id="cantidad_impuesto"/>
                <input type="hidden" name="input_totalizar_sub_total" id="input_totalizar_sub_total" value=""/>
                <input type="hidden" name="input_totalizar_descuento_parcial" id="input_totalizar_descuento_parcial" value=""/>
                <input type="hidden" name="input_totalizar_total_operacion" id="input_totalizar_total_operacion" value=""/>
                <input type="hidden" name="input_totalizar_pdescuento_global" id="input_totalizar_pdescuento_global" value=""/>
                <input type="hidden" name="input_totalizar_descuento_global" id="input_totalizar_descuento_global" value=""/>
                <input type="hidden" name="input_totalizar_monto_iva" id="input_totalizar_monto_iva" value=""/>
                <input type="hidden" name="input_totalizar_total_general" id="input_totalizar_total_general" value=""/>
                <input type="hidden" name="input_totalizar_monto_cancelar" id="input_totalizar_monto_cancelar" value=""/>
                <input type="hidden" name="input_totalizar_saldo_pendiente" id="input_totalizar_saldo_pendiente" value=""/>
                <input type="hidden" name="input_totalizar_cambio" id="input_totalizar_cambio" value=""/>
                <input type="hidden" name="input_totalizar_monto_efectivo" id="input_totalizar_monto_efectivo" value=""/>
                <input type="hidden" name="input_totalizar_monto_cheque" id="input_totalizar_monto_cheque" value=""/>
                <input type="hidden" name="input_totalizar_nro_cheque" id="input_totalizar_nro_cheque" value=""/>
                <input type="hidden" name="input_totalizar_nombre_banco" id="input_totalizar_nombre_banco" value=""/>
                <input type="hidden" name="input_totalizar_monto_tarjeta" id="input_totalizar_monto_tarjeta" value=""/>
                <input type="hidden" name="input_totalizar_nro_tarjeta" id="input_totalizar_nro_tarjeta" value=""/>
                <input type="hidden" name="input_totalizar_tipo_tarjeta" id="input_totalizar_tipo_tarjeta" value=""/>
                <input type="hidden" name="input_totalizar_monto_deposito" id="input_totalizar_monto_deposito" value=""/>
                <input type="hidden" name="input_totalizar_nro_deposito" id="input_totalizar_nro_deposito" value=""/>
                <input type="hidden" name="input_totalizar_banco_deposito" id="input_totalizar_banco_deposito" value=""/>
            </form>
            <div id="info" style="display:none;">
            </div>

            {literal}
        <script type="text/javascript">//<![CDATA[

            $(function() {
                $("input[name='filtro_codigo']").addClass("focusInput").focus();
                $("#lista_pedidos").hide();
                $("#lista_notas_entrega").hide();
                $("#lista_cotizaciones").hide();
                $("#lista_cuotas").hide();
                var click_pedido = click_nota = click_presupuesto = click_cuota = false;
                
                $("#pedidos").click(function() {
                    $("#lista_notas_entrega, #lista_cotizaciones, #lista_cuotas").hide(150);
                    if(!click_pedido){
                        click_pedido = true;
                        click_nota = click_presupuesto = click_cuota = false;
                        $("#pedidos").css("background-color", "buttonface");
                        $("#notas_entrega, #cotizaciones, #cuotas").css("background-color", "");
                        $("#lista_pedidos").show(150);
                    }
                    else{
                        click_pedido = false;
                        $("#lista_pedidos").hide(150);
                        $("#pedidos").css("background-color", "");
                    }
                });
                $("#notas_entrega").click(function() {
                    $("#lista_cotizaciones, #lista_pedidos, #lista_cuotas").hide(150);
                    if(!click_nota){
                        click_nota = true;
                        click_pedido = click_presupuesto = click_cuota = false;
                        $("#notas_entrega").css("background-color", "buttonface");
                        $("#lista_notas_entrega").show(150);
                        $("#pedidos, #cotizaciones, #cuotas").css("background-color", "");
                    }
                    else{
                        click_nota = false;
                        $("#lista_notas_entrega").hide(150);
                        $("#notas_entrega").css("background-color", "");
                    }
                });
                $("#cotizaciones").click(function() {
                    $("#lista_notas_entrega, #lista_pedidos, #lista_cuotas").hide(150);
                    if(!click_presupuesto){
                        click_presupuesto = true;
                        click_notas = click_pedido = click_cuota = false;
                        $("#cotizaciones").css("background-color", "buttonface");
                        $("#lista_cotizaciones").show(150);
                        $("#pedidos, #notas_entrega, #cuotas").css("background-color", "");
                    }
                    else{
                        click_presupuesto = false;
                        $("#lista_cotizaciones").hide(150);
                        $("#cotizaciones").css("background-color", "");
                    }
                });
                $("#cuotas").click(function() {
                    $("#lista_notas_entrega, #lista_pedidos, #lista_cotizaciones").hide(150);
                    if(!click_cuota){
                        click_cuota = true;
                        click_notas = click_pedido = click_presupuesto = false;
                        $("#cuotas").css("background-color", "buttonface");
                        $("#lista_cuotas").show(150);
                        $("#pedidos, #notas_entrega, #cotizaciones").css("background-color", "");
                    }
                    else{
                        click_cuota = false;
                        $("#lista_cuotas").hide(150);
                        $("#cuotas").css("background-color", "");
                    }
                });
                var bg;
                $("table.listado").find("tbody tr").mouseover(function(){/*libs/js/comunes.js*/
                    bg = $(this).css("background");
                    $(this).css("background", "activecaption");
                }).mouseout(function(){
                    $(this).css("background", bg);
                });
                $("#marcar").click(function(){
                    $("input[type=checkbox]").each(function (){
                        $(this).attr('checked', true);
                    });
                });
                $("#desmarcar").click(function(){
                    $("input[type=checkbox]").each(function (){
                        $(this).attr('checked', false);
                    });
                });
                $("#incluir_cuotas").click(function(){
                    var cuotas_selec = [];
                    var haySeleccionadas = false;
                    $("input[type=checkbox]:checked#cuotas_seleccionadas").each(function (){
                        cuotas_selec.push($(this).val());
                        document.getElementsByName("id_cuotas_seleccionadas")
                        haySeleccionadas = true;
                    });
                    //Ext.Msg.alert("Informaci&oacute;n", haySeleccionadas ? cuotas_selec.join("<br/>") : "Seleccione almenos un &Iacute;tem.");//alert(cuotas_seleccionadas.join("\n"));
                    if(!haySeleccionadas){
                        Ext.Msg.alert("Alerta", "Seleccione almenos un &Iacute;tem.");
                        return false;
                    }
                });
            });
            //]]>
        </script>
        {/literal}

        <!--Tab de pasos de factura-->
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(function() {
                    $("#contenedorTAB_factura_paso2").hide();
                    $("#tab1_pasos").click(function() {
                        $("#contenedorTAB_factura_paso1").show();
                        $("#contenedorTAB_factura_paso2").hide();
                        $("#tab1_pasos").removeClass("click_paso_OFF").addClass("click_paso_ON").find("img").attr("src", "../../../includes/imagenes/113.png");
                        $("#tab2_pasos").removeClass("click_paso_OFF").addClass("click_paso_OFF").find("img").attr("src", "../../../includes/imagenes/6_off.png");
                        $("#procesar").removeClass("click_paso_OFF").addClass("click_paso_OFF").find("img").attr("src", "../../../includes/imagenes/6_off.png");
                        $("#PFactura2").attr('disabled','disabled');
                    });
                    $("#tab2_pasos").click(function() {
                        cant_filas = $(".grid table.lista tbody").find("tr").length;
                        if (cant_filas === 0) {
                            Ext.MessageBox.show({
                                title: 'Notificacion',
                                msg: "Debe agregar un Item para esta operacion",
                                buttons: Ext.MessageBox.OK,
                                animEl: 'tab2_pasos',
                                icon: 'ext-mb-warning'
                            });
                            return false;
                        }
                        $(".ctotalizar_").each(function() {
                            if ($(this).val() === "") {
                                $(this).val("");
                            }
                        });
                        $.totalizarFactura();

                        $.setValoresInput("#input_totalizar_sub_total", "#totalizar_sub_total");
                        $.setValoresInput("#input_totalizar_descuento_parcial", "#totalizar_descuento_parcial");
                        $.setValoresInput("#input_totalizar_total_operacion", "#totalizar_total_operacion");

                        $.setValoresInput("#input_totalizar_pdescuento_global", "#totalizar_pdescuento_global");
                        $.setValoresInput("#input_totalizar_descuento_global", "#totalizar_descuento_global");
                        $.setValoresInput("#input_totalizar_monto_iva", "#totalizar_monto_iva");
                        $.setValoresInput("#input_totalizar_total_retencion", "#totalizar_total_retencion");
                        $.setValoresInput("#input_totalizar_total_general", "#totalizar_total_general");

                        //#FORMA PAGO
                        $.setValoresInput("#input_totalizar_monto_cancelar", "#totalizar_monto_cancelar");
                        $.setValoresInput("#input_totalizar_saldo_pendiente", "#totalizar_saldo_pendiente");
                        $.setValoresInput("#input_totalizar_cambio", "#totalizar_cambio");

                        //#INSTRUMENTO DE PAGO
                        $.setValoresInput("#input_totalizar_monto_efectivo", "#totalizar_monto_efectivo");
                        $.setValoresInput("#input_totalizar_monto_cheque", "#totalizar_monto_cheque");
                        $.setValoresInput("#input_totalizar_nro_cheque", "#totalizar_nro_cheque");
                        $.setValoresInput("#input_totalizar_nombre_banco", "#totalizar_nombre_banco");
                        $.setValoresInput("#input_totalizar_monto_tarjeta", "#totalizar_monto_tarjeta");
                        $.setValoresInput("#input_totalizar_nro_tarjeta", "#totalizar_nro_tarjeta");
                        $.setValoresInput("#input_totalizar_tipo_tarjeta", "#totalizar_tipo_tarjeta");
                        $.setValoresInput("#input_totalizar_monto_deposito", "#totalizar_monto_deposito");
                        $.setValoresInput("#input_totalizar_nro_deposito", "#totalizar_nro_deposito");
                        $.setValoresInput("#input_totalizar_banco_deposito", "#totalizar_banco_deposito");

                        $("#contenedorTAB_factura_paso2").show();
                        $("#contenedorTAB_factura_paso1").hide();

                        $("#tab2_pasos").removeClass("click_paso_OFF").addClass("click_paso_ON").find("img").attr("src", "../../../includes/imagenes/6.png");
                        $("#tab1_pasos").removeClass("click_paso_OFF").addClass("click_paso_OFF").find("img").attr("src", "../../../includes/imagenes/113_OFF.png");
                        $("#procesar").removeClass("click_paso_OFF").addClass("click_paso_ON").find("img").attr("src", "../../../includes/imagenes/6.png");
                        $("#PFactura2").removeAttr('disabled');
                    });
        
        $("#btnFiltroFormatoSalidaCliente").click(function(e){

            e.preventDefault();
            pBuscarCliente.main.mostrarWin();
        });
                });//]]>
    </script>
    {/literal}

    <!--Agregar combo select de la cesta-->
    <div id="incluirproducto" class="x-hide-display">
          
        <p>
            <label><b>Cesta Clap</b></label><br/>
            <select name="cesta" id="cesta" class="form-text" style="width:300px;">
                <option value="">Seleccione...</option>
                {html_options values=$option_values_cesta output=$option_output_cesta}
            </select>
        </p>

        
    </div>
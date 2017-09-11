<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="author" content="HZ" />
        <title></title>
        
        <script type="text/javascript" src="../../libs/js/event_cotizacion_entrada.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formCotizacion.js"></script>

        {literal}

        <script language="JavaScript" type="text/JavaScript">//<![CDATA[

        function soloNumeros(e){
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 57)
        }

        function calcularMonto(precio2,precio3){
            
            precio2.value = parseFloat(precio3.value + (parseFloat(precio3.value) * 0.3));
            //precio2.value = redondear(precio.value,2);

        }

        function  calcular_todo()
        {
            var aux= document.getElementById('formulario2');

            calcularMonto(aux.precio_1,aux.precio);
        }

        //]]>

        //prueba para la fecha
        //$(function () {
        //$("#datepicker").datepicker();
        //});


        $(document).ready(function(){

            $("#rubros").change(function(event){
                rubro=$("#rubros").val();
                // alert(rubro);
                if (rubro!='') {
                    // alert(rubro);
                    $.ajax({
                        type: "GET",
                        url: "../../libs/php/ajax/ajax.php",
                        data: "opt=ObtenerIVA&rubro="+rubro,
                        beforeSend: function(){},
                        success: function(data){
                            resultado = eval(data);
                            rc = resultado[0].rc;
                            iva = resultado[0].ivaproduct;
                            // alert(iva);
                            if (rc == 0) {
                                $("#ivaproduct").val(iva);
                            }else{
                                $("#ivaproduct").val(iva);
                            }

                            //Prueba para cambiar los datos al cambiar el rubro seleccionado
                            var po=$("#porcentaje_operatividad").val();
                            var pu=$("#precio_unitario").val();
                            var iva=$("#ivaproduct").val();
                            
                            //var n=pu.replace(",",".");

                            var operativo=((pu*po)/100);
                            var op = operativo.toFixed(2);
                            //document.writeln(operativo);
                            $("#operatividad").val(op);
                           
                            operativo2=parseFloat(pu)+parseFloat(op);
                            $("#costo_sin_iva").val(operativo2);

                            costoiva=((operativo2*iva)/100);
                            coniva=(parseFloat(costoiva))+operativo2;
                            // $("#costo_iva").val(coniva);

                            var precioventa = coniva.toFixed(2);
                            $("#pvp").val(precioventa);
                        }
                    });
                }
            });

            $("#precio_unitario").change(function(event) {
            
                var po=$("#porcentaje_operatividad").val();
                var pu=$("#precio_unitario").val();
                var iva=$("#ivaproduct").val();
                
                //var n=pu.replace(",",".");

                $("#porcentaje_operatividad").val(0);

                var operativo=((pu*po)/100);
                var op = operativo.toFixed(2);
                //document.writeln(operativo);
                $("#operatividad").val(op);
               
                operativo2=parseFloat(pu)+parseFloat(op);
                $("#costo_sin_iva").val(operativo2);

                costoiva=((operativo2*iva)/100);
                coniva=(parseFloat(costoiva))+operativo2;
                // $("#costo_iva").val(coniva);

                var precioventa = coniva.toFixed(2);
                $("#pvp").val(precioventa);
           
            });

            //prueba para colocarle a mano el porcentaje
            $("#porcentaje_operatividad").change(function(event) {
            
                var po=$("#porcentaje_operatividad").val();
                var pu=$("#precio_unitario").val();
                var iva=$("#ivaproduct").val();
                
                //var n=pu.replace(",",".");

                var operativo=((pu*po)/100);
                var op = operativo.toFixed(2);
                //document.writeln(operativo);
                $("#operatividad").val(op);
               
                operativo2=parseFloat(pu)+parseFloat(op);
                $("#costo_sin_iva").val(operativo2);

                costoiva=((operativo2*iva)/100);
                coniva=(parseFloat(costoiva))+operativo2;
                // $("#costo_iva").val(coniva);

                var precioventa = coniva.toFixed(2);
                $("#pvp").val(precioventa);
           
            });

            $("#porcentaje_ganancia").change(function(event) {
            
                var pg=$("#porcentaje_ganancia").val();
                var iva=$("#ivaproduct").val();

                var operativo=(pg/100);

                var csiva=$("#costo_sin_iva").val();
                
                var mg = csiva*operativo;

                $("#margen_ganancia").val(mg.toFixed(2));

                var total = ((((parseFloat(csiva)+parseFloat(mg))*iva)/100)+(parseFloat(csiva)+parseFloat(mg)));

                $("#pvp").val(total.toFixed(2));

            });
        });

        </script>
        {/literal}
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        {literal}
        <style type="text/css">
        .invisible
        {
        display: none;
        }
        </style>
        {/literal}

   
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
                                    <td style="width:900px;">
                                        <span style="float:left;">
                                            <img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon"/>
                                            {$subseccion[0].descripcion}
                                        </span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';">
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
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Creado Por (*)</b></span>
                        </td>
                        <td>
                            <!--input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="{$detalles_pendiente[0].autorizado_por}"/-->
                            <input type="text" maxlength="100" name="creado_por" id="creado_por" value="{$campos_cotizacion[0].autorizado_por}" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Observaciones</b></span>
                        </td>
                        <td>
                            <input type="text" name="observaciones" maxlength="100" id="observaciones" value="{$campos_cotizacion[0].observacion}" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>N° de la Cotización</b></span>
                        </td>
                        <td>
                            <input type="test" name="nro_cotizacion" id="nro_cotizacion" value="{$campos_cotizacion[0].nro_cotizacion}" maxlength="100" size="30" class="form-text" readonly="readonly">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha de Creación</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fechacrea" id="input_fechacotiza" value='{$smarty.now|date_format:"%Y-%m-%d"}' size="30" maxlength="70" class="form-text" readonly="readonly"/>
                            <!--div style="color:#4e6a48" id="fechacompra">{$smarty.now|date_format:"%d-%m-%Y"}</div-->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha de Recepción</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fechacotiza" id="input_fechacotiza" value='{$campos_cotizacion[0].fecha_recep_mercadeo}' size="30" maxlength="70" class="form-text" readonly="readonly"/>
                            <!--div style="color:#4e6a48" id="fechacompra">{$smarty.now|date_format:"%d-%m-%Y"}</div-->
                            {literal}
                                <script type="text/javascript">//<![CDATA[
                                //comentado el 4/02/15 no me parece correcto que se ponga cuaquier fecha. daniel
                                    // var cal = Calendar.setup({
                                    //     onSelect: function(cal) {
                                    //         cal.hide();
                                    //     }
                                    // });
                                    // cal.manageFields("input_fechacompra", "input_fechacompra", "%Y-%m-%d");
                                //]]>
                                </script>
                            {/literal}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha de Cotizaci&oacute;n</b></span>
                        </td>
                        <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                            <input type="date" name="fecha_cotizacion" step="1" value='{$campos_cotizacion[0].fecha_cotizacion}' class="form-text" readonly="readonly"/>
                            <!--button id="boton_fecha">...</button-->
                            <!--<input type="text" name="fecha2" id="fecha2" size="20" value='{$smarty.now|date_format:"%d-%m-%Y"}' readonly class="form-text" />-->
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Validez de la Cotizaci&oacute;n</b></span>
                        </td>
                        <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                            <input type="date" name="validez_fecha" step="1" value='{$campos_cotizacion[0].validez_fecha}' class="form-text" readonly="readonly"/>
                            <!--button id="boton_fecha">...</button-->
                            <!--<input type="text" name="fecha2" id="fecha2" size="20" value='{$smarty.now|date_format:"%d-%m-%Y"}' readonly class="form-text" />-->
                        </td>
                    </tr>
                       
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Tipo de Transporte</b></span>
                        </td>
                        <td>
                            <input type="text" name="tipo_transporte" maxlength="100" id="tipo_transporte" value="{$tipo_transporte[0].descripcion}" size="30" maxlength="70" class="form-text" readonly="readonly" />

                            <!--<select id="tipo_transporte" name="tipo_transporte" class="form-text" style="width:205px">
                                <option value="0"> Seleccionar Tipo de Transporte</option>
                                <option value="1">Propio (De PDVAL)</option>
                                <option value="2">Empresa (Del Proveedor)</option>
                            </select>-->
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Proveedor</b></span>
                        </td>
                        <td>
                            <input type="text" name="tipo_transporte" maxlength="100" id="tipo_transporte" value="{$proveedor[0].descripcion}" size="30" maxlength="70" class="form-text" readonly="readonly" />

                            <!--<select id="proveedores" name="proveedores" class="form-text" style="width:205px">
                                <option value="0"> Seleccione el Proveedor</option>
                                {html_options values=$option_values_id_proveedor output=$option_output_descripcion}
                            </select>-->
                        </td>
                    </tr>

                    <!-- <tr>    
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Estado del Mercadeo</b></span>
                        </td> -->
                            <!--ESTADOS-->
                        <!-- <td> 
                                <select name="estado" id="estado" class="form-text" style="width:205px">
                                    <option value="0">Todos</option>
                                    {html_options values=$option_values_id_estado output=$option_output_nombre_estado}
                                </select>
                        </td>
                    </tr> -->

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
                                            <!-- <th class="tb-tit" style="text-align: center;">Id del Rubro</th> -->
                                            <th class="tb-tit" style="text-align: center;">Descripci&oacute;n</th>
                                            <th class="tb-tit" style="text-align: center;">Costo Total sin IVA</th>
                                            <th class="tb-tit" style="text-align: center;">IVA</th>
                                            <th class="tb-tit" style="text-align: center;">Precio Sugerido por Vendedor</th>
                                            <th class="tb-tit" style="text-align: center;">Margen de Ganancia</th>
                                            <th class="tb-tit" style="text-align: center;">P.V.P</th>
                                            <th class="tb-tit" style="text-align: center;">Estatus Producto</th>
                                            <th class="tb-tit" style="text-align: center;">Producto Troquelado</th>
                                            <th class="tb-tit" style="text-align: center;">Opci&oacute;n</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {if $cod eq ""}
                                            {foreach from=$productos_cotizacion_mercado key=i item=prod}
                                                <tr>
                                                    <!-- <td style="text-align: left; padding-left: 20px;">{$prod.producto}</td> -->
                                                    <td style="text-align: left; padding-left: 20px;">{$prod.nombre_rubro}</td>
                                                    <td style="text-align: right; padding-right: 20px;">{$prod.precio}</td>
                                                    <td></td>
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
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items"/>
            <div id="displaytotal" class="x-hide-display"></div>
            <div id="displaytotal2" class="x-hide-display"></div>
        </form>
        <form name="formulario2" id="formulario2">
        <div id="incluirproducto" class="x-hide-display">
            <!-- <p>
                <label for="establecimiento"><b>Establecimiento</b></label><br/>
                <select id="establecimiento" name="establecimiento" class="form-text" style="width:205px">
                    <option value="0">Seleccione el Establecimiento</option>
                    {html_options values=$option_values_id_establecimiento output=$option_output_nombre_establecimiento selected=$establecimiento}
                </select>
            </p> -->
            <!--<p>
                <label for="rubros"><b>Rubros</b></label><br/>
                <select id="rubros" name="rubros" class="form-text" style="width:100%">
                    <option value="0">Seleccione el Rubro</option>
                    {html_options values=$option_values_id_rubro output=$option_output_nombre_rubro selected=$rubros}
                </select>
            </p> -->
            <!-- <p>
                <label><b>Costo Unitario</b></label><br/>
                <input type="text" name="precio" id="precio" class="form-text"/>
            </p>
            <p>
                <label><b>Costo Operativo Sin IVA</b></label><br/>

                <input type="hidden" name="ivaproduct" id="ivaproduct">
            </p> -->
            <!-- <input type="hidden" name="ivaproduct" id="ivaproduct"> -->
            <table>
                <tr>
                    <td>Productos</td>
                    <td>
                        <select id="rubros" name="rubros" class="form-text" style="width:100%">
                            <option value="0">-- Seleccione el Producto --</option>
                            {html_options values=$option_values_id_item output=$option_output_nombre_producto selected=$rubros}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Costo Unitario</td>
                    <td>
                        <input type="text" name="precio_unitario" id="precio_unitario" class="form-text"/>
                    </td>
                </tr>
                <tr>
                    <td>Porcentaje de Operatividad</td>
                    <td>
                        <input type="text" name="porcentaje_operatividad" id="porcentaje_operatividad" class="form-text"/>
                    </td>
                </tr>
                <tr>
                    <td>Costo Operativo</td>
                    <td>
                        <input type="text" name="operatividad" id="operatividad" size="30" class="form-text" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td>Costo Total sin IVA</td>
                    <td>
                        <input type="text" name="costo_sin_iva" id="costo_sin_iva" size="30" class="form-text" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td>IVA</td>
                    <td>
                        <input type="text" name="ivaproduct" id="ivaproduct" size="30" class="form-text" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td>Troquel de Producto</td>
                    <td>
                        <select id="troquel" name="troquel" class="form-text" style="width:50%">
                            <option value="0">-- Seleccione --</option>
                            <option value="1">Troquelado</option>
                            <option value="2">No Troquelado</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Precio Sugerido por Proveedor</td>
                    <td>
                        <input type="text" name="precio_sugerido" id="precio_sugerido" class="form-text"/>
                    </td>
                </tr>
                <tr>
                    <td>Porcentaje de Ganancia</td>
                    <td>
                        <input type="text" name="porcentaje_ganancia" id="porcentaje_ganancia" class="form-text"/>
                        <!-- <select id="porcentaje_ganancia" name="porcentaje_ganancia" class="form-text" style="width:50%">
                            <option value="0">-- Seleccione el Porcentaje --</option>
                            <option value="0.05">5%</option>
                            <option value="0.1">10%</option>
                            <option value="0.15">15%</option>
                            <option value="0.2">20%</option>
                            <option value="0.25">25%</option>
                            <option value="0.3">30%</option>
                        </select> -->
                    </td>
                </tr>
                <tr>
                    <td>Margen de Ganancia</td>
                    <td>
                        <input type="text" name="margen_ganancia" id="margen_ganancia" class="form-text" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td>P.V.P</td>
                    <td>
                        <input type="text" name="pvp" id="pvp" class="form-text" disabled="disabled" />
                    </td>
                </tr>
            </table>
        </div>
        </form>
    </body>
</html>
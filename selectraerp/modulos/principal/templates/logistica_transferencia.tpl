<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada_pda.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formPDA.js"></script>
         <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
        {literal}
        <script language="JavaScript" type="text/JavaScript">
         
        function comprobarfechavencimiento() {
        var consulta;     
        consulta = $("#items").val();                      
                        $.ajax({
                              type: "POST",
                              url: "comprobar_fecha_vencimiento.php",
                              data: "b="+consulta,
                              dataType: "html",
                              asynchronous: false, 
                              error: function(){
                                    alert("error peticion ajax");
                              },
                              success: function(data){  

                                $("#resultado2").html(data);
                                document.getElementById("fecha_vencimiento").focus();
                                
                              }
                  });

        }

        $(document).ready(function()
        {
            $("#nro_documento").blur(function()
            {
                valor = $(this).val();
                if(valor!='')
                {
                    $.ajax({
                        type: "GET",
                        url:  "../../libs/php/ajax/ajax.php",
                        data: "opt=ValidarOrdenCompra&v1="+valor,
                        beforeSend: function()
                        {
                            $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                        },
                        success: function(data)
                        {
                            resultado = data
                            if(resultado=="-1")
                            {
                                $("#nro_documento").val("").focus();
                                $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Nro. de Documento Ya Existe.</b></span>");
                            }
                            if(resultado=="1")
                            {//cod de item disponble
                                $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nro. de Documento Disponible</b></span>");
                            }
                        }
                    });
                }
            });

            $("#estado_destino").change(function() {
                estados = $("#estado_destino").val();
                    $.ajax({
                        type: 'GET',
                        data: 'opt=getPuntos&'+'estados='+estados,
                        url: '../../libs/php/ajax/ajax.php',
                        beforeSend: function() 
                        {
                            $("#punto_destino").find("option").remove();
                            $("#punto_destino").append("<option value=''>Cargando..</option>");
                        },
                        success: function(data) 
                        {
                            $("#punto_destino").find("option").remove();
                            this.vcampos = eval(data);
                            $("#punto_destino").append("<option value=''>Seleccione Punto</option>");
                            for (i = 0; i <= this.vcampos.length; i++) 
                            {
                                $("#punto_destino").append("<option value='" + this.vcampos[i].siga+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                            }
                        }
                    }); 
                            
            });
            $("#estado_origen").change(function() {
                estados = $("#estado_origen").val();
                    $.ajax({
                        type: 'GET',
                        data: 'opt=getPuntos&'+'estados='+estados,
                        url: '../../libs/php/ajax/ajax.php',
                        beforeSend: function() 
                        {
                            $("#punto_origen").find("option").remove();
                            $("#punto_origen").append("<option value=''>Cargando..</option>");
                        },
                        success: function(data) 
                        {
                            $("#punto_origen").find("option").remove();
                            this.vcampos1 = eval(data);
                            $("#punto_origen").append("<option value=''>Seleccione Punto</option>");
                            for (i = 0; i < this.vcampos1.length; i++) 
                            {
                                $("#punto_origen").append("<option value='" + this.vcampos1[i].siga+ "'>" + this.vcampos1[i].nombre_punto + "</option>");
                            }
                        }
                    }); 
                            
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
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Autorizado Por (*)</b></span>
                        </td>
                        <td>
                            <input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="{$nombre_usuario}" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                            <input type="hidden" maxlength="100" name="tipo_log" id="tipo_log" value="{$tipo_log}" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                        </td>
                    </tr>
                    

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Nro. De Documento</b></span>
                        </td>
                        <td>
                            <input type="text" name="nro_documento" maxlength="100" id="nro_documento" size="30" maxlength="70" class="form-text" />
                            <div id="notificacionVUsuario"></div>
                            <input type="hidden" name="tipo_entrada" maxlength="100" id="tipo_entrada" size="30" maxlength="70" value="1" class="form-text" readonly=true/>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <p>
                            <label><b>Transporte</b></label><br/>
                            </p>
                        </td>
                        <td>
                        <select name="transporte" id="transporte" class="form-text" style="width:205px">
                        <option value="Gerencia De Transporte">Gerencia De Transporte</option>
                        <!--<option value="Transporte Proveedor">Transporte Proveedor</option>-->
                        </select>
                        <!--<input type="text" name="transporte" id="transporte" class=" form-text" size="30">-->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Observaciones</b></span>
                        </td>
                        <td>
                            <input type="text" name="observaciones" maxlength="100" id="observaciones" value="{$detalles_pendiente[0].observacion}" size="30"  class="form-text"/>
                        </td>
                    </tr>
                    
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
                                        {if $cod neq ""}
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
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items"/>
            <div id="displaytotal" class="x-hide-display"></div>
            <div id="displaytotal2" class="x-hide-display"></div>
        </form>
        <div id="incluirproducto" class="x-hide-display">
            
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra"  class="form-text" >
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>

            <p>
                <label><b>Productos</b></label><br/>
                <input type="hidden" name="items" id="items"  class="form-text">
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly  class="form-text" style="width:205px">
               
            </p>
           
            <p>
                <label><b>Cantidad Unitaria</b></label><br/>
                <input type="text" name="cantidadunitaria" id="cantidadunitaria"/  class="form-text" style="width:205px">
            </p>
            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Estado Origen (*)</b></span><br>
                <select name="estado_origen" id="estado_origen" class="form-text" style="width:205px">
                    <option value="">Seleccione...</option>
                    {html_options values=$option_values_id_estado output=$option_values_nombre_estado}
                </select>
            </p>
            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Punto Origen</b></span><br>
                <select name="punto_origen" id="punto_origen" class="form-text" style="width:205px" class="form-text" style="width:205px">
                    <option value=""> Seleccione El Estado </option>
                </select>
            </p>
            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Estado Destino (*)</b></span><br>
                <select name="estado_destino" id="estado_destino" class="form-text" style="width:205px">
                    <option value="">Seleccione...</option>
                    {html_options values=$option_values_id_estado output=$option_values_nombre_estado}
                </select>
            </p>
            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Punto Destino</b></span><br>
                <select name="punto_destino" id="punto_destino" class="form-text" style="width:205px" class="form-text" style="width:205px">
                    <option value=""> Seleccione El Estado </option>
                </select>
            </p>
             <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Inicio</b></span><br>
                <input type="text" name="input_fechaplanificacion_inicio" id="input_fechaplanificacion_inicio" value='{$smarty.now|date_format:"%d/%m/%Y"}' size="30" maxlength="70" class="form-text" readonly="readonly" style="width:205px"  />
                <div id="notificacionVUsuariofecha1"></div>
                    {literal}
                    <script type="text/javascript">
                    //<![CDATA[
                    var cal = Calendar.setup(
                    {
                        onSelect: function(cal) 
                        {
                            cal.hide();
                        }
                    });
                    cal.manageFields("input_fechaplanificacion_inicio", "input_fechaplanificacion_inicio", "%d/%m/%Y");
                    //]]>
                    </script>
                    {/literal}
                <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Fin</b></span><br>
                <input type="text" name="input_fechaplanificacion_fin" id="input_fechaplanificacion_fin" value='{$smarty.now|date_format:"%d/%m/%Y"}' size="30" maxlength="70" class="form-text" readonly="readonly" style="width:205px" />
                <div id="notificacionVUsuariofecha2"></div>
                    {literal}
                    <script type="text/javascript">
                    //<![CDATA[
                    var cal = Calendar.setup(
                    {
                        onSelect: function(cal) 
                        {
                            cal.hide();
                        }
                    });
                    cal.manageFields("input_fechaplanificacion_fin", "input_fechaplanificacion_fin", "%d/%m/%Y");
                    //]]>
                    </script>
                    {/literal}
            </p>
            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Observación</b></span><br>
                 <input type="text" name="observacion_detalle" id="observacion_detalle" class="form-text" style="width:205px"/>
            </p>
            
        </div>
    </body>
</html>
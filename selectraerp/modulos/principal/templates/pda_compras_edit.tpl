<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada_pdaeditar.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formPDA.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js">
        </script>
        {literal}
        <script language="JavaScript" type="text/JavaScript">
        $(document).ready(function()
        {
            $("#nro_documento").blur(function()
            {
                                valor = $(this).val();
                                if(valor!=''){
                                    $.ajax({
                                        type: "GET",
                                        url:  "../../libs/php/ajax/ajax.php",
                                        data: "opt=ValidarOrdenCompra&v1="+valor,
                                        beforeSend: function(){
                                            $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                                        },
                                        success: function(data){
                                            resultado = data
                                            if(resultado=="-1"){
                                                $("#nro_documento").val("").focus();
                                                $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Nro. de Documento Ya Existe.</b></span>");
                                            }
                                            if(resultado=="1"){//cod de item disponble
                                                $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nro. de Documento Disponible</b></span>");
                                            }
                                        }
                                    });
                                }
            });
        });
         
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
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Proveedor (*)</b></span>
                        </td>
                        <td>
                            <select name="id_proveedor" id="id_proveedor" class="form-text" disabled style="width:205px">
                            <option value="">Seleccione...</option>
                            {html_options values=$option_values_proveedor output=$option_output_proveedor selected=$maestro[0].id_proveedor}
                            </select>
                           
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Instalación</b></span>
                        </td>
                        <td>
                            <select name="instalacion" id="instalacion" class="form-text" disabled style="width:205px">
                            <option value="0">Instalación Principal</option>
                            {html_options values=$option_values_instalacion output=$option_output_instalacion selected=$maestro[0].id_instalacion_origen}
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Nro Orden De Compra</b></span>
                        </td>
                        <td>
                            <input type="text" name="nro_documento" value="{$maestro[0].orden_compra}" maxlength="100" id="nro_documento" size="30" maxlength="70" class="form-text" />
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
                        <input type="text" name="transporte" id="transporte" class=" form-text" size="30" value="{$maestro[0].transporte}" readonly="true">
                        <input type="hidden" name="id_maestro" id="id_maestro" class=" form-text" size="30" value="{$maestro[0].id}" readonly="true">
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Observaciones</b></span>
                        </td>
                        <td>
                            <input type="text" name="observaciones" maxlength="100" id="observaciones" value="{$maestro[0].observacion}" readonly="true" size="30"  class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Inicio</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fechaplanificacion" id="input_fechaplanificacion" value="{$maestro[0].fecha_planificacion}" readonly="true" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                            
                            {literal}
                                <script type="text/javascript">
                                //<![CDATA[
                                    /* var cal = Calendar.setup({
                                         onSelect: function(cal) {
                                             cal.hide();
                                         }
                                     });
                                     cal.manageFields("input_fechaplanificacion", "input_fechaplanificacion", "%d-%m-%Y");*/
                              //]]>
                                </script>
                            {/literal}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Fin</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fechaplanificacion_fin" id="input_fechaplanificacion_fin" value='{$maestro[0].fecha_planificacion_fin}' size="30" maxlength="70" class="form-text" readonly="readonly" /><div id="notificacionVUsuariofecha2"></div>
                            {literal}
                                <script type="text/javascript">
                                //<![CDATA[
                                    /* var cal = Calendar.setup({
                                         onSelect: function(cal) {
                                             cal.hide();
                                         }
                                     });
                                     cal.manageFields("input_fechaplanificacion_fin", "input_fechaplanificacion_fin", "%d/%m/%Y");*/
                              //]]>
                                </script>
                            {/literal}
                        </td>
                    </tr>
                </table>
                <table style="width:100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar" />
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


        
        
        <div id="dcompra" class="x-hide-display">
            <div id="PanelGeneralCompra">
                <div id="tabproducto">
                </div>
            </div>
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items"/>
            <div id="displaytotal" class="invisible"></div>
            <div id="displaytotal2" class="invisible"></div>
            
        </div>
    </form>  

    </body>
</html>
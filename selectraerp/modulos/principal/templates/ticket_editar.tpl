<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <link href="../../libs/js/select2/dist/css/select2.min.css" rel="stylesheet" />
        <script src="../../libs/js/select2/dist/js/select2.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        

        {assign var=name_form value="vendedor_nuevo"}
        {include file="snippets/header_form.tpl"}
        {literal}
            <script type="text/javascript" src="../../../includes/js/jquery-ui-timepicker-addon.js"></script>
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){

                    $('#conductores').select2(
                    {
                        placeholder: "Seleccione ...",
                        allowClear: true
                    });

                    $("input[name='cancelar']").button();//Coloca estilo JQuery
                    $("input[name='aceptar']").button().click(function(){

                        if($("#conductores option:selected").val()==''){
                            Ext.Msg.alert("Alerta","Debe Seleccionar el conductor");
                            $("#conductores").focus();
                            return false;
                        }

                        if($("#fecha_entrada").val()==''){
                            Ext.Msg.alert("Alerta","Debe Colocar la Fecha de salida");
                            $("#fecha_salida").focus();
                            return false;
                        }

                        if($("#peso_entrada").val()==''){
                            Ext.Msg.alert("Alerta","Debe Colocar el Peso de salida");
                            $("#peso_salida").focus();
                            return false;
                        }

                    });

                    $("#fecha_entrada").datetimepicker({
                    changeMonth: false,
                    changeYear: false,
                    showOtherMonths:false,
                    selectOtherMonths: false,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    showOn: "both",//button,
                    onClose: function( selectedDate) {
                        //$( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                        $( "#fecha_salida" ).datetimepicker("option", "minDate", selectedDate);
                        $( "#fecha_salida" ).datetimepicker("option", "minTime", selectedDate);
                    }
                    });


                    $("#fecha_salida").datetimepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    showOn: "both",//button,
                    onClose: function( selectedDate) {
                        //$( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                        $( "#fecha_entrada" ).datetimepicker("option", "maxDate", selectedDate);
                        $( "#fecha_entrada" ).datetimepicker("option", "maxTime", selectedDate);
                    }
                    });

                });
                //Panel
                Ext.ns('Selectra.pyme.vendedores');
                Selectra.pyme.vendedores.TabPanelVendedores = {
                    init: function(){
                        var panelDatos = new Ext.Panel({
                            contentEl:'div_tab1',
                            title: 'Datos Generales'
                        });
                        this.tabs = new Ext.TabPanel({
                            renderTo:'contenedorTAB',
                            activeTab:0,
                            plain:true,
                            defaults:{
                                autoHeight: true
                            },
                            items:[
                                panelDatos
                            ]
                        });
                    }
                }
                Ext.onReady(Selectra.pyme.vendedores.TabPanelVendedores.init, Selectra.pyme.vendedores.TabPanelVendedores);
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral">
                {include file = "snippets/regresar.tpl"}
                <input type="hidden" name="cod_empresa" value="{$DatosGenerales[0].cod_empresa}"/>
                <input type="hidden" name="id_ticket" value="{$datos_ticket[0].id}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <div id="contenedorTAB">
                    <div id="div_tab1" class="x-hide-display">
                        <table style="width:100%">
                            <thead>
                                <tr>
                                    <th colspan="4" class="tb-head" style="text-align:center;">
                                        COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="label">Tipo de Ticket **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="tipo_ticket" id="tipo_ticket" class="form-text" style="width:205px" class="form-text" disabled="disabled">
                                            <option value="1" {if $datos_ticket[0].tipo_ticket == 1} selected="selected"{/if}>Entrada</option>
                                            <option value="2" {if $datos_ticket[0].tipo_ticket ==  2} selected="selected"{/if}>Salida</option>
                                        </select>
                                    </td>
                                    <td class="label">Seleccione el Conductor **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="conductores" id="conductores" class="form-text" style="width:205px" class="form-text" disabled="disabled">
                                            {html_options values=$option_values_conductores output=$option_output_conductores selected=$datos_ticket[0].id_conductor}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label">Hora Entrada **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="fecha_entrada" id="fecha_entrada" size="20" value='{$datos_ticket[0].hora_entrada|date_format:"%Y-%m-%d %H:%M"}' class="form-text" readonly="readonly" disabled="disabled" />
                                    </td>

                                    <td class="label">Peso Entrada **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="peso_entrada" id="peso_entrada" size="20" value='{$datos_ticket[0].peso_entrada}' class="form-text" readonly="readonly" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Hora Salida **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="fecha_salida" id="fecha_salida" size="20" value='' class="form-text" />
                                    </td>

                                    <td class="label">Peso Salida **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="peso_salida" id="peso_salida" size="20" value='' class="form-text"/>
                                    </td>
                                </tr>
                                <tr id="placadiv" >
                                    <td class="label">Placa **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="placa" id="placa" size="20"  class="form-text" value="{$datos_ticket[0].placa}" readonly="readonly" />
                                    </td>

                                    <td class="label"></td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <table style="width: 100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" id="aceptar" name="aceptar" value="Guardar"/>
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
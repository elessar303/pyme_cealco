<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        

        {assign var=name_form value="vendedor_nuevo"}
        {include file="snippets/header_form.tpl"}
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){

                //funcion para cargar los puntos 
                    $("#marca").change(function() {
                        estados = $("#marca").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getTransporteModelo&'+'marca='+estados,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() 
                            {
                                $("#modelo").find("option").remove();
                                $("#modelo").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) 
                            {
                                $("#modelo").find("option").remove();
                                this.vcampos = eval(data);
                                for (i = 0; i < this.vcampos.length; i++) 
                                {
                                    $("#modelo").append("<option value='" + this.vcampos[i].id+ "'>" + this.vcampos[i].descripcion_modelo + "</option>");
                                }
                            }
                        }); 
                    });
                
                $("#serial_gps").blur(function()
                    {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarSerialGps&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                                },
                                success: function(data)
                                {
                                    
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                    
                                        $("#serial_gps").val("").focus();
                                        $("#notificacionVUsuariogpss").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Serial GPS Ya Existe.</b></span>");
                                    }
                                    if(resultado=="1")
                                    {
                                    
                                    //cod de item disponble
                                        $("#notificacionVUsuariogpss").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Serial GPS Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });

                    $("#alias_gps").blur(function()
                    {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarAliasGps&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#alias_gps").val("").focus();
                                        $("#notificacionVUsuarioaliasgps").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Alias GPS Ya Existe.</b></span>");
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionVUsuarioaliasgps").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Alias GPS Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });

                $("#unidad").blur(function()
                {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarUnidadTransporte&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#unidad").val("").focus();
                                        $("#notificacionVUsuariou").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Nro. de Unidad Ya Existe.</b></span>");
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionVUsuariou").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nro. de Unidad Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });
                $("#placa").blur(function()
                {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarPlacaTransporte&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#placa").val("").focus();
                                        $("#notificacionVUsuariop").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Nro. de Placa Ya Existe.</b></span>");
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionVUsuariop").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nro. de Placa Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });
                $("#serial_motor").blur(function()
                {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarSerialMotor&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#serial_motor").val("").focus();
                                        $("#notificacionVUsuariosm").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Serial Motor Ya Existe.</b></span>");
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionVUsuariosm").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Serial Motor Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });
                $("#serial_carroceria").blur(function()
                {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarSerialCarroceria&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#serial_carroceria").val("").focus();
                                        $("#notificacionVUsuariosc").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Serial Cacorreceria Ya Existe.</b></span>");
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionVUsuariosc").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Serial Carroceria Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });

                    $("input[name='cancelar']").button();//Coloca estilo JQuery
                    $("input[name='aceptar']").button().click(function(){
                        if($("#nombres").val()=='' || $("#apellidos").val()=='' || $("#cedula").val()=='' || $("#telefono").val()=='' || $("#flota_asignada_conductor").val()=='')
                        {
                            Ext.Msg.alert("Alerta","Debe llenar los campos obligatorios");
                            $("#nombre").focus();
                            return false;
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
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden" name="cod_empresa" value="{$DatosGenerales[0].cod_empresa}"/>
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
                                    <td colspan="3" class="label">Unidad **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="unidad" placeholder="Unidad" size="60" id="unidad" class="form-text" value="{$datos[0].unidad}"/>
                                        <div id="notificacionVUsuariou"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Placa **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="placa" placeholder="Placa" size="60" id="placa" class="form-text" value="{$datos[0].placa}"/>
                                        <div id="notificacionVUsuariop"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Serial Motor **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="serial_motor" placeholder="Serial Motor" size="60" id=serial_motor class="form-text" value="{$datos[0].serial_motor}"/>
                                        <div id="notificacionVUsuariosm"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Serial Carroceria **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="serial_carroceria" placeholder="Serial Carroceria" size="60" id="serial_carroceria" class="form-text" value="{$datos[0].serial_carroceria}"/><div id="notificacionVUsuariosc"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Marca Vehiculo **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="marca" id="marca" class="form-text" style="width:205px" class="form-text">
                                            {html_options values=$option_values_marca_vehiculo output=$option_output_marca_vehiculo selected=$marca}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Modelo Vehiculo **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="modelo" id="modelo" class="form-text" style="width:205px" class="form-text">
                                            {html_options values=$option_values_modelo_vehiculo output=$option_output_modelo_vehiculo selected=$modelo}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Año Vehiculo **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="anio_vehiculo" placeholder="Año Vehiculo" size="60" id="anio_vehiculo" class="form-text" value="{$datos[0].anio_vehiculo}"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">¿Cantidad De Ejes? **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="cantidad_ejes" placeholder="Cantidad Ejes" size="60" id="cantidad_ejes" class="form-text" value="{$datos[0].cantidad_ejes}"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">¿Posee Caucho De Repuesto? **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="posee_caucho" id="posee_caucho" class="form-text" style="width:205px" class="form-text">
                                                    {html_options values=$option_values_booleano output=$option_output_booleano selected=$caucho}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">¿Posee Herramientas? **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="herramientas" id="herramientas" class="form-text" style="width:205px" class="form-text">
                                                    {html_options values=$option_values_booleano output=$option_output_booleano selected=$herramientas}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Ultimo Kilometraje **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="ultimo_kilometraje" placeholder="Ultimo Kilometraje" size="60" id="ultimo_kilometraje" class="form-text" value="{$datos[0].ultimo_kilometraje}"/>
                                        <input type="hidden" name="ultimok" value="{$datos[0].ultimo_kilometraje}"/>
                                        <input type="hidden" name="fecha_ultimok" value="{$datos[0].fecha_kilometraje}"/>
                                    </td>
                                </tr>

                                <!--<tr>
                                    <td colspan="3" class="label">Fecha Ultimo Kilometraje **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="fecha_kilometraje" placeholder="Fecha Ultimo Kilomentraje" size="60" readonly="readonly" id="fecha_kilometraje" class="form-text" value="{$datos[0].fecha_kilometraje}"/>
                                        {literal}
                                            <script type="text/javascript">//<![CDATA[
                                                var cal = Calendar.setup({
                                                    onSelect: function(cal) {
                                                        cal.hide();
                                                    }
                                                });
                                                cal.manageFields("fecha_kilometraje", "fecha_kilometraje", "%d-%m-%Y");
                                            //]]>
                                            </script>
                                        {/literal}
                                    </td>
                                </tr>-->

                                <tr>
                                    <td colspan="3" class="label">Serial GPS **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="serial_gps" placeholder="Serial GPS" size="60" id="serial_gps" class="form-text" value="{$datos[0].serial_gps}"/><div id="notificacionVUsuariogpss"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Alias GPS **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="alias_gps" placeholder="Alias GPS" size="60" id="alias_gps" class="form-text" value="{$datos[0].alias_gps}"/><div id="notificacionVUsuarioaliasgps"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">¿Estatus Del Vehiculo? **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="estatus_vehiculo" id="estatus_vehiculo" class="form-text" style="width:205px" class="form-text">
                                                    {html_options values=$option_values_estatus_vehiculo output=$option_output_estatus_vehiculo selected=$estatus}
                                        </select>
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="3" class="label">Flota Asiganada **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="flota_asignada" id="flota_asignada" class="form-text" style="width:205px" class="form-text">
                                                    {html_options values=$option_values_estado output=$option_output_estado selected=$estado}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Tipo De Vehiculo **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="tipo_vehiculo" id="tipo_vehiculo" class="form-text" style="width:205px" class="form-text">
                                                    {html_options values=$option_values_tipo_vehiculo output=$option_output_tipo_vehiculo selected=$tipo}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Capacidad En TN **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="capacidad" placeholder="Capacidad En TN." size="60" id="capacidad" class="form-text" value="{$datos[0].capacidad_carga_ton}"/>
                                        <input type="hidden" name="id" value="{$id}">
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">¿Vehiculo Refrigerado? **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="vehiculo_recuperado" id="vehiculo_recuperado" class="form-text" style="width:205px" class="form-text">
                                                    {html_options values=$option_values_booleano output=$option_output_booleano selected=$recuperado}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">¿Vehiculo Propio? **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="vehiculo_propio" id="vehiculo_propio" class="form-text" style="width:205px" class="form-text">
                                                    {html_options values=$option_values_booleano output=$option_output_booleano selected=$propio}
                                        </select>
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
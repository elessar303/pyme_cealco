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
                
               
                $("#descripcion").blur(function()
                {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarDescripcionTipoVehiculo&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionDescripcion").html(MensajeEspera("<b>Veficando Marca..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#descripcion").val("").focus();
                                        $("#notificacionDescripcion").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, Tipo Vehiculo Ya Existe.</b></span>");
                                        return false;
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionDescripcion").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Tipo Vehiculo Disponible</b></span>");
                                        return true;
                                    }
                                }
                            });
                        }
                    });
                });

                function validacion()
                {
                    valor = $('#descripcion').val();
                        if(valor!='' || $('#marca').val()=="")
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarDescripcionModelo&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionDescripcion").html(MensajeEspera("<b>Veficando Marca..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#descripcion").val("").focus();
                                        $("#notificacionDescripcion").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, Marca Ya Existe.</b></span>");
                                        return false;
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionDescripcion").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Marca Disponible</b></span>");
                                        return true;
                                    }
                                }
                            });
                        }
                        else
                        {   
                            alert('Error Campo Vacio');
                            $("#descripcion").val("").focus();
                            return false;
                        }
                    
                }
               
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
        <form id="form-{$name_form}" name="formulario" action="" method="post" onsubmit="return validacion()">
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
                                    <td colspan="3" class="label">Descripcion **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="hidden" name="id" value="{$datos[0].id}"/>
                                        <input type="text" name="descripcion" placeholder="Descripcion" size="60" id="descripcion" class="form-text" value=""/>
                                        <div id="notificacionDescripcion"></div>
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
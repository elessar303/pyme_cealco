<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <!--link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>-->
            <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function()
            {
                $("#orden_ubicacion").keyup(function()
                {
                    valor = $("#orden_ubicacion").val();
                    valor2 = $("#id_almacen").val();
                    if(valor!='' &&  valor2!='')
                    {
                        $.ajax({
                            type: "GET",
                            url:  "../../libs/php/ajax/ajax.php",
                            data: "opt=ValidarOrdenUbicacion&v1="+valor+"&v2="+valor2,
                            beforeSend: function()
                            {
                                $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de orden..</b>"));
                            },
                            success: function(data)
                            {
                                resultado = data
                                if(resultado=="-1")
                                {
                                    $("#orden_ubicacion").val("").focus();
                                    $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Nro. de Orden Ya Existe.</b></span>");
                                    return false;

                                }
                                if(resultado=="1")
                                {//cod de item disponble
                                    $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nro. de orden Disponible</b></span>");
                                }
                            }
                        });
                    }
                });
              
                $("input[name='aceptar']").click(function(){
                    valor = $("#orden_ubicacion").val();
                    valor2 = $("#id_almacen").val();
                    
                    if($("#descripcion_ubicacion").val()=="" || $("#orden_ubicacion").val()==""){
                        $("#descripcion_ubicacion").focus();
                        Ext.Msg.alert("Alerta","Debe Ingresar todos los campos!");
                        return false;
                    }
                });
            });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral">
                {include file = "snippets/regresar_boton_ubi.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <input type="hidden" name="id_almacen" id="id_almacen" value="{$smarty.get.cod}"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head" style="text-align:center;">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                      
                        <tr>
                            <td colspan="3" class="label">
                                Descripci&oacute;n
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="descripcion_ubicacion" size="60" id="descripcion_ubicacion" class="form-text" />
                            </td>
                        </tr> 
                        <tr>
                            <td colspan="3" class="label">
                                Orden
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="orden_ubicacion" size="60" id="orden_ubicacion" class="form-text" />
                                <div id="notificacionVUsuario"></div>
                            </td>
                        </tr> 
                         <!--<tr>
                            <td colspan="3" class="label">
                                Puede vender
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="checkbox" name="puede_vender" value=1 id="puede_vender" class="form-text" />
                            </td>
                        </tr>-->               
                        
                    </tbody>
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
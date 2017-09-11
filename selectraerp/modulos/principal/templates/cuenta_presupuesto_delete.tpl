<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {assign var=name_form value="usuarios_nuevo"}
        {include file="snippets/header_form.tpl"}
        <!--Para estilo JQuery en botones-->
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/md5_crypt.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
       
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function()
                {
                    $("#tipo_cuenta").blur(function()
                    {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax(
                            {
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=tipoCuentaPresupuestoa&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVUsuario").html(MensajeEspera("<b>Veficando tipo cuenta..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado==-1)
                                    {
                                        $("#tipo_cuenta").val("").focus();
                                        $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este tipo cueneta ya existe.</b></span>");
                                    }
                                    if(resultado==1)
                                    {//cod de item disponble
                                        $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nombre de tipo cuenta Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });
                });/*end of document.ready*/
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar.tpl"}
                <table style="width: 100%">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="label">Cuenta Presupuesto **</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type='hidden' name='id' id='id' value='{$datos_usuarios[0].id}'  />
                                <input type="text" name="cuenta" placeholder="Cuenta" size="60" value='{$datos_usuarios[0].cuenta}' id="cuenta" class="form-text" readonly />
                                <div id='notificacionVUsuario'>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">Tipo Cuenta **</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="tipo" id="tipo" class="form-text" disabled>
                                    {html_options values=$option_values_tipo_cuenta output=$option_output_tipo_cuenta selected=$option_selected_tipo_cuenta}
                               </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Eliminar" />
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
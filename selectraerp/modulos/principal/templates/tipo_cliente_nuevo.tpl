<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        {literal}
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#descripcion").focus();
                    $("#form-{/literal}{$name_form}{literal}").submit(function(){
                            if($("#descripcion").val()==""){
                                $.facebox("Debe especificar una descripci&oacute;n");
                                $("#descripcion").focus();
                                return false;
                            }
                    });
                });
            </script>
        {/literal}
        {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" method="post">
            <div id="datosGral" class="x-hide-display">
            {include file = "snippets/regresar_boton.tpl"}
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <!--table width="100%">
                <tr>
                    <td>
                        <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'">
                                            <tr>
                                                <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                                <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table-->
            <table style="width: 100%">
                <tr>
                    <td colspan="4" class="tb-head" align="center">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="3" width="30%" class="tb-head" >
                        Codigo
                    </td>
                    <td>
                        <input type="text" name="cod_tipo_cliente" value="#Numero" id="cod_tipo_cliente" >
                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="3" width="30%" class="tb-head">Descripción</td>
                    <td>
                        <input type="text" name="descripcion" size="60" id="descripcion" />
                    </td>
                </tr>
            </table>
            <table style="width: 100%">
                <tbody>
                    <tr class="tb-tit" style="text-align: right">
                        <td>
                            <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                            <input type="button" name="cancelar" onclick="javascript: document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'" value="Cancelar"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>
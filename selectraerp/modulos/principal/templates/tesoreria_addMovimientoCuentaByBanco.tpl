<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){
                    $("#descripcion").focus();
                    $("#formulario").submit(function(){
                            if($("#descripcion").val()==""){
                                $.facebox("Debe especificar la descripcion de la forma de pago");
                                $("#descripcion").focus();
                                return false;
                            }
                    });
                });
                //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <input type="hidden" name="codBanco" value="{$smarty.get.cod}"/>
            <input type="hidden" name="codCuenta" value="{$smarty.get.cod_cuenta}"/>
            <table width="100%">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                                <td style="padding: 0px;" align="left"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" >
                <tr>
                    <td valign="top" colspan="2" width="30%" class="tb-head" >
                        N&uacute;mero Movimiento
                    </td>
                    <td>
                        <input type="text" name="numero_movimiento" size="60" id="numero_movimiento" />
                    </td>
                </tr>
                <tr>
                    <td valign="top"  colspan="2" width="30%" class="tb-head" >
                        Fecha de Pago: <span style="color:red;">*</span>
                    </td>
                    <td>
                        <input size="14" type="text" name="fecha" id="fecha" value="{$hoy}" />
                        {literal}
                            <script type="text/javascript">//<![CDATA[
                            var cal = Calendar.setup({
                                    onSelect: function(cal) { cal.hide() }
                            });
                            cal.manageFields("fecha", "fecha", "%d/%m/%Y");
                            //]]>
                            </script>
                        {/literal}
                    </td>
                </tr>
                <!--tr>
                    <td valign="top" colspan="2" width="30%" class="tb-head" >
                       Cliente
                    </td>
                    <td>
                        <select name="id_cliente" id="id_cliente" style="width:130px" >
                        <option values="0" >Seleccione un Cliente </option>
                {html_options values=$option_values_cliente output=$option_output_cliente selected=$option_output}
             </select>
            </td>
        </tr-->
                <tr>
                    <td valign="top"  colspan="2" width="30%" class="tb-head" >
                        Monto
                    </td>
                    <td >
                        <input type="text" name="monto" size="60" id="monto" >
                    </td>
                </tr>
                <tr>
                    <td valign="top"  colspan="2" width="30%" class="tb-head" >
                        Concepto de Movimiento
                    </td>
                    <td >
                        <input type="text" name="concepto" size="60" id="concepto" >
                    </td>
                </tr>
                <tr>
                    <td valign="top"  colspan="2" width="30%" class="tb-head" >
                        Tipo de Movimiento
                    </td>
                    <td >
                        <select name="cod_tipo_movimiento" id="cod_tipo_movimiento">
                            {html_options output=$option_output_funciontipomovimiento values=$option_values_funciontipomovimiento}
                        </select>

                    </td>
                </tr>
            </table>
            <table width="100%" border="0">
                <tbody>
                    <tr class="tb-tit" align="right">
                        <td>
                            <input type="submit" name="aceptar" id="aceptar" value="Guardar">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>
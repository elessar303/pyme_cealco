<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script src="../../libs/js/cxp_facturas_list.js" type="text/javascript"></script>
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function() {
                    $("#buscar").click(function() {
                        $("form").submit();
                    });
                });
                function direccionar(url) {
                    window.location.href = url;
                }
                //]]>
            </script>
        {/literal}
    </head>
    <body>
        <div id="loading" style="position:absolute; width:80%; text-align:center; top:180px; visibility:hidden;">
            <img src="../../../includes/imagenes/36.gif" />
        </div>
        <form name="{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <table style="width: 100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width:900px;">
                                        <span style="float:left"><img src="{$campo_seccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location = '?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=addFac&cod2={$edoCta}&amp;cod={$cod}';">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Agregar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location = '?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edocuenta&amp;cod={$cod}';">
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
            <table class="tb-head" style="width: 100%;">
                <tr>
                    <td><input type="text" name="buscar" value="{$smarty.post.buscar}{$smarty.get.des}" size="20"/></td>
                    <td>
                        <select name="busqueda">
                            {html_options values=$option_values selected=$option_selected output=$option_output}
                        </select>
                    </td>
                    <td>
                        <table style="cursor: pointer;" onclick="javascript:window.location = '?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=facturasCXP&amp;cod={$cod}&amp;cod2={$edoCta}';" class="btn_bg" id="buscar">
                            <tr>
                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                <td class="btn_bg"><img src="../../../includes/imagenes/search.gif" width="16" height="16" /></td>
                                <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Buscar</td>
                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="cursor: pointer;" onclick="javascript:window.location = '?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=facturasCXP&amp;cod={$cod}&amp;cod2={$edoCta}';" class="btn_bg">
                            <tr>
                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                <td class="btn_bg"><img src="../../../includes/imagenes/list.gif" width="16" height="16" /></td>
                                <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Mostrar todo</td>
                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:120px;"><input type="radio" name="palabra" value="exacta" />Palabra exacta</td>
                    <td style="width:140px;"><input type="radio" name="palabra" value="todas" />Todas las palabras</td>
                    <td style="width:150px;"><input type="radio" name="palabra" value="cualquiera" checked/>Cualquier palabra</td>
                    <td style="width:386px;"></td>
                </tr>
            </table>
            <br/>
            <table class="seleccionLista">
                <tbody>
                    <tr class="tb-head" >
                        {foreach from=$cabecera key=i item=campos}
                            <td>
                                {$campos}
                            </td>
                        {/foreach}
                        <td colspan="4">Opciones</td>
                    </tr>
                    {if $cantidadFilas eq 0}
                        <tr>
                            <td colspan="9" style="text-align: center;">
                                {$mensaje}
                            </td>
                        </tr>
                    {else}
                        {foreach from=$registros key=i item=campos}
                            {if $i%2 eq 0}
                                {assign var=color value=""}
                            {else}
                                {assign var=color value="#cacacf"}
                            {/if}
                            <tr bgcolor="{$color}">
                                <td style="text-align: right; padding-right: 20px;">{$campos.cod_factura}</td>
                                <td style="text-align: right; padding-right: 20px;">{$campos.cod_cont_factura}</td>
                                <td style="text-align: center;">{$campos.fecha_factura}</td>
                                <td style="text-align: center;">{$campos.fecha_recepcion}</td>
                                <td style="text-align: right; color: red; font-weight:bold; padding-right: 20px;">{$campos.total_a_pagar|number_format:2:",":"."}</td>
                                <td style="text-align: center;">
                                    <img style="cursor: pointer;" class="editar" onclick="javascript:window.location.href = '?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=view&fac={$campos.id_factura}&cod2={$edoCta}&cod={$cod}';" title="Ver Documeto (FAC, NC, ND)" src="../../../includes/imagenes/view.gif"/>
                                </td>
                                {if $campos.cxp_factura_pago_pk==NULL && $campos.cod_estatus==1}
                                    <td style="text-align: center;"><img style="cursor: pointer;" class="eliminar" onclick="javascript:anularFactura('{$campos.id_factura}');" title="Anular Documento" src="../../../includes/imagenes/cancel.gif"/></td>
                                    {else}
                                    <td style="text-align: center;"><img style="cursor: pointer;" class="eliminar" title="No puede anular; tiene pago asociado o ya fue anulada" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                    {/if}
                                    {if $campos.montoretislr>0 && ($campos.cod_estatus==1 || $campos.cod_estatus==2)}
                                    <td style="text-align: center;"><img style="cursor: pointer;" class="imprimir" onclick="javascript: window.location.href = '../../reportes/rpt_reporte_islr.php?id_factura={$campos.id_factura}';" title="Imprimir Comprobante del I.S.L.R." src="../../../includes/imagenes/ico_print.gif"/></td>
                                    {else}
                                    <td style="text-align: center;"><img style="cursor: pointer;" class="eliminar" title="No posee retenci&oacute;n de ISLR" src="../../../includes/imagenes/ico_est6.gif"></td>
                                    {/if}
                                    {if $campos.monto_retenido>0 && ($campos.cod_estatus==1 || $campos.cod_estatus==2)}
                                    <td style="text-align: center;"><img style="cursor: pointer;" class="imprimir" onclick="javascript: window.location.href = '../../reportes/rpt_reporte_iva.php?id_factura={$campos.id_factura}';" title="Imprimir Comprobante del I.V.A." src="../../../includes/imagenes/ico_print.gif"/></td>
                                    {else}
                                    <td style="text-align: center;"><img style="cursor: pointer;" class="eliminar" title="No posee retenci&oacute;n de IVA" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                    {/if}
                            </tr>
                            {assign var=ultimo_cod_valor value=$campos.num_fac}
                        {/foreach}
                    {/if}
                </tbody>
            </table>
            <table class="tb-head" style="width: 100%;">
                <tbody>
                    <tr>
                        <td><span>P&aacute;gina&nbsp;</span></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}&pagina=1&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" border="0"></a></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}&pagina={$pagina-1}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" border="0"></a></td>
                        <td><input type="text" name="numero_pagina" value="{$pagina}" onblur="javascript: paginacion('?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}', this.value, '&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}');" size="4"></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}&pagina={$pagina+1}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" border="0"></a></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}&pagina={$num_paginas}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/last.gif" alt="Ultima" title="Ultima" width="16" height="16" border="0"></a></td>
                        <td colspan="14" style="width: 100%; text-align: center;">P&aacute;gina {$pagina} de {$num_paginas}</td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>
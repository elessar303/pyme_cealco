<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_boton.tpl"}
                <!--table style="width: 100%;">
                    <tr class="row-br">
                        <td>
                            <table class="tb-tit" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td width="900"><span style="float:left"><img src="{$campo_seccion[0].img_ruta}" width="22" height="22" class="icon" />Seleccione el Banco de la transacción</span></td>
                                        <td width="75">
                                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
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
            <table style="cursor: pointer;" class="btn_bg" id="buscar">
                <tr>
                    <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                    <td class="btn_bg"><img src="../../../includes/imagenes/search.gif" width="16" height="16" /></td>
                    <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Buscar</td>
                    <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                </tr>
            </table>
        </td>
        <td>
            <table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" class="btn_bg">
                <tr>
                    <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                    <td class="btn_bg"><img src="../../../includes/imagenes/list.gif" width="16" height="16" /></td>
                    <td class="btn_bg" nowrap style="padding: 0px 4px;">Mostrar todo</td>
                    <td style="padding: 0px;" align="left"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                </tr>
            </table></td>
        <td width="120"><input type="radio" name="palabra" value="exacta"/>Palabra exacta</td>
        <td width="140"><input type="radio" name="palabra" value="todas"/>Todas las palabras</td>
        <td width="150"><input checked type="radio" name="palabra" value="cualquiera"/>Cualquier palabra</td>
        <td colspan="3" width="386"></td>
    </tr>
</table-->
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista" style="width: 100%;">
                    <tbody>
                        <tr class="tb-head" >
                            {foreach from=$cabecera key=i item=campos}
                                <td>
                                    <strong>{$campos}</strong>
                                </td>
                            {/foreach}
                            <td style="text-align: center; width: 100px;"><strong>Opciones</strong></td>
                        </tr>
                        {if $cantidadFilas == 0}
                            <tr><td colspan="3" style="text-align: center;">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td style="width: 100px; text-align: right; padding-right: 30px;">{$campos.cod_banco}</td>
                                    <td>{$campos.descripcion}</td>
                                    <td style="text-align: center; width: 100px;">
                                        <img style="cursor: pointer;" class="seleccionChequeraActiva" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=verChequerasByBanco&cod={$campos.cod_banco}'" title="Ver Chequeras" src="../../../includes/imagenes/add.gif"/>
                                    </td>
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.cod_banco}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file="snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
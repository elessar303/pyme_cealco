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
                {include file = "snippets/regresar_buscar_botones.tpl"}
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head" >
                            {foreach from=$cabecera key=i item=campos}
                                <td>
                                    <strong>{$campos}</strong>
                                </td>
                            {/foreach}
                            <td colspan="2"><strong>Opciones</strong></td>
                        </tr>
                        {if $cantidadFilas == 0}
                            <tr><td colspan="3" style="text-align:center;">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td style="width:100px;">{$campos.cod_banco}</td>
                                    <td>{$campos.descripcion}</td>
                                    <td style="width:50px; text-align: center;">
                                        <img style="cursor: pointer;" class="editar"  onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=edit&cod={$campos.cod_banco}'" title="Editar" src="../../../includes/imagenes/edit.gif">
                                    </td>
                                    <td style="width:50px; text-align: center;">
                                        <img style="cursor: pointer;" class="verCuentas" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=viewcuentasByBanco&cod={$campos.cod_banco}'" title="Ver Cuentas Asociadas"  src="../../../includes/imagenes/ico_view.gif">
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
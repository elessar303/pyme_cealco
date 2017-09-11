<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form name="form-{$name_form}" id="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_solo.tpl"}
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                        {foreach from=$cabecera key=i item=campos}
                            <td style="text-align:center"><b>{$campos}</b></td>
                        {/foreach}
                            <td colspan="1" style="text-align:center;"><b>Opciones</b></td>
                        </tr>
                        {if $cantidadFilas == 0}
                        <tr><td colspan="5">{$mensaje}</td></tr>
                        {else}
                        {foreach from=$registros key=i item=campos}
                            {if $i%2==0}
                            {assign var=color value=""}
                            {else}
                            {assign var=color value="#cacacf"}
                            {/if}
                        <tr bgcolor="{$color}">
                            <td>{$campos.cod_item}</td>
                            <td>{$campos.descripcion1}</td>                        
                            <td style="text-align:right">{$campos.coniva1}</td>
                            <td style="text-align:right">{$campos.total_inventario}</td>
                            <td style="width: 30px; text-align:center">
                            {if ($campos.total_inventario > $campos.existencia_min) }
                                <img title="OK" src="../../../includes/imagenes/ico_ok.gif"/>
                            {else}
                                <img title="Existencia Bajo Minimos" src="../../../includes/imagenes/ico_note_1.gif"/>
                            {/if}
                            </td>
                        </tr>
                        {assign var=ultimo_cod_valor value=$campos.id_item}
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
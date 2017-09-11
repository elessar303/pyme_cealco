<!DOCTYPE html>
<!--Creado por: Charli Vivenes, email: cjvrinf@gmail.com-->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {assign var=nom_menu value=$campo_seccion[0].nom_menu}
        {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_buscar_botones.tpl"}
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <thead>
                        <tr class="tb-head" >
                            {foreach from=$cabecera key=i item=campos}
                                <td><strong>{$campos}</strong></td>
                            {/foreach}
                            <td colspan="2" style="text-align:center;"><strong>Opciones</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        {if $cantidadFilas eq 0}
                        <td colspan="6" style="text-align: center;">{$mensaje}</td>
                    {else}
                        {foreach from=$registros key=i item=campos}
                            {if $i%2 eq 0}
                                {assign var=color value=""}
                            {else}
                                {assign var=color value="#cacacf"}
                            {/if}
                            <tr bgcolor="{$color}">
                                <td style="text-align: center; width: 150px;">{$campos.cod_cliente}</td>
                                <td style="padding-left: 20px;">{$campos.nombre}</td>
                                <td style="text-align: right; width: 150px; padding-right: 20px;">{$campos.rif}</td>
                                <td style="text-align: right; width: 150px; padding-right: 20px;">{$campos.telefonos}</td>
                                <td style="text-align: center; width: 30px; cursor: pointer;">
                                    <img style="cursor: pointer;" class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id_cliente}';" title="Editar Cliente" src="../../../includes/imagenes/edit.gif"/>
                                </td>
                                <td style="text-align: center; width: 30px; cursor: pointer;">
                                    {if ($campos.estado eq "A") }
                                        <img style="cursor: pointer;" class="newfactura_rapida" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=newfactura_rapida&amp;cod={$campos.id_cliente}';" title="Nueva Factura" src="../../../includes/imagenes/factu.png"/>
                                    {else}
                                        <img title="Cliente Bloqueado" src="../../../includes/imagenes/ico_note_1.gif"/>
                                    {/if}
                                </td>
                            </tr>
                            {assign var=ultimo_cod_valor value=$campos.id_cliente}
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
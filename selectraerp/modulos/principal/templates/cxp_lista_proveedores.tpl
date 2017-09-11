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
                            <td style="text-align: center; width: 100px;">
                                <strong>Opciones</strong>
                            </td>
                        </tr>
                        {if $cantidadFilas == 0}
                            <tr>
                                <td colspan="5" style="text-align: center; width: 100%;">
                                    {$mensaje}
                                </td>
                            </tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td style="text-align: center; width: 100px;">{$campos.cod_proveedor}</td>
                                    <td>{$campos.descripcion}</td>
                                    <td>{$campos.rif}</td>
                                    <td style="text-align: right;">{$campos.telefonos}</td>
                                    <td style="text-align: center;">
                                        <img style="cursor: pointer;" class="edocuenta" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edocuenta&amp;cod={$campos.id_proveedor}'" title="Estado de Cuenta" src="../../../includes/imagenes/edocuenta.png"/>
                                    </td>
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.id_cliente}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file="snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
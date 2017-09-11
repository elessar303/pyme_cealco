<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href="../../../includes/js/jquery-ui-1.10/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet">
<script src="../../../includes/js/jquery-ui-1.10/js/jquery-1.9.0.js"></script>
<script src="../../../includes/js/jquery-ui-1.10/js/jquery-ui-1.10.0.custom.min.js"></script>
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
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td>{$campos}</td>
                            {/foreach}
                            <td colspan="2" style="text-align:center;">Opciones</td>
                        </tr>
                        {if $cantidadFilas eq 0}
                            <tr><td colspan="4">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2 eq 0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td style="text-align: center; width: 100px; padding-right: 10px;">{$campos.denominacion}</td>
                                    <td style="text-align: center; padding-left: 10px;">{$campos.valor}</td>
                                    {if $campos.estatus eq 1}
                                    <td style="text-align: center; padding-left: 10px;">Activo</td>
                                    {else}
                                    <td style="text-align: center; padding-left: 10px;">Inactivo</td>
                                    {/if}
                                    <td style="text-align:center; width: 30px; cursor: pointer;">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id}'" title="Editar" src="../../../includes/imagenes/edit.gif">
                                    </td>
                                    <!--<td style="text-align:center; width: 30px; cursor: pointer;">
                                        <img class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=delete&amp;cod={$campos.cod_departamento}'" title="Eliminar" src="../../../includes/imagenes/delete.gif">
                                    </td>-->
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.cod_departamento}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
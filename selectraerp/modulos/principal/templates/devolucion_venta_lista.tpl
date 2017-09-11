<!DOCTYPE html>
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
                {include file = "snippets/regresar_solo.tpl"}
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head" >
                            {foreach from=$cabecera key=i item=campos}
                                <td>{$campos}</td>
                            {/foreach}
                            <td colspan="2" style="text-align:center;">Opciones</td>
                        </tr>
                        {if $cantidadFilas eq 0}
                            <tr>
                                <td colspan="8">{$mensaje}</td>
                            </tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2 eq 0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td style="width:30px; text-align:right; padding-right:5px;">{$campos.id_factura}</td>
                                    <td style="width:100px; text-align:center;">{$campos.cod_factura}</td>
                                    <td style="padding-left:10px;">{$campos.nombre}</td>
                                    <td style="width:100px; text-align:center;">{$campos.rif}</td>
                                    <td style="width:100px; text-align:center;">{$campos.fechaFactura|date_format:"%d-%m-%Y"}</td>
                                    <td style="width:100px; text-align:right; padding-right:10px;">{$campos.totalizar_total_general}</td>
                                    <td style="width:30px; text-align:center; cursor:pointer;"><img class="impresion" onclick="javascript:window.open('../../reportes/rpt_factura.php?codigo={$campos.cod_factura}','');" title="Imprimir Factura" src="../../../includes/imagenes/ico_print.gif"/></td>
                                    <td style="width:30px; text-align:center; cursor:pointer;"><img class="anular" onclick="javascript:window.location.href = '?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=devolver_ps&codigo={$campos.cod_factura}';" title="Anular Factura" src="../../../includes/imagenes/delete.png"/></td>
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.id_factura}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
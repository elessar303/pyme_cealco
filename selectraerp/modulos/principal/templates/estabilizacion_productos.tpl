<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        {literal}
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file="snippets/regresar_buscar_botones.tpl"}
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                        <td colspan="2">
                        Productos Creados en el Punto de Venta que no estan en Sede Central
                         - <a href="estabilizacion_productos_xls.php">Descargar Lista <img src="../../../includes/imagenes/document-save.png" alt="Descargar" height="15" width="15"></a>
                        </td>
                        </tr>
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td><strong>{$campos}</strong></td>
                            {/foreach}
                        </tr>
                        {if $cantidadFilas eq 0}
                            <tr><td colspan="2" align="center">{$mensaje}</td></tr>
                        {else}
                            {foreach from = $registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td style="text-align: center; width: 200px;">{$campos.codigo_barras}</td>
                                    <td style="text-align: center;">{$campos.descripcion1}</td>
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.id_proevedor}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
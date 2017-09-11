<!DOCTYPE html>
<!--Creado por: -->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <!--metadata autor="Charli Vivenes" email="cjvrinf@gmail.com"/-->        
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
                            <td><b>{$campos}</b></td>
                        {/foreach}
                            <td colspan="1" style="text-align:center;"><b>Opciones</b></td>
                        </tr>
                        {if $cantidadFilas == 0}
                        <tr><td colspan="8">{$mensaje}</td></tr>
                        {else}
                        {foreach from = $registros key=i item=campos}
                            {if $i%2==0}
                                {assign var=bgcolor value=""}
                            {else}
                                {assign var=bgcolor value="#cacacf"}
                            {/if}
                        <tr bgcolor="{$bgcolor}">
                            <td>{$campos.id_factura}</td>
                            <td>{$campos.cod_factura}</td>
                            <td>{$campos.nombre}</td>
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="impresion" onclick="javascript: window.open('../../reportes/rpt_factura.php?codigo={$campos.cod_factura}','')" title="Imprimir Factura"  src="../../../includes/imagenes/ico_print.gif"/>
                            </td>
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
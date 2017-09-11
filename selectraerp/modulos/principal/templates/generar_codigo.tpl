<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como 
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snippet de código para obtener las bondades de la reutilización.
-->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;id={$smarty.get.id}" method="post">
            <div id="datosGral" class="x-hide-display">
            {include file = "snippets/regresar_buscar_botones.tpl"}
            {include file = "snippets/tb_head_sub_id.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                        {foreach from=$cabecera key=i item=campos}
                            <td><b>{$campos}</b></td>
                        {/foreach}
                            <td colspan="2" style="text-align:center;"><b>Opciones</b></td>
                        </tr>
                    {if $cantidadFilas == 0}
                        <tr><td colspan="3">{$mensaje}</td></tr>
                    {else}
                        {foreach from = $registros key=i item=campos}
                            <!--{if $i%2==0}<tr bgcolor="">{else}<tr bgcolor="#e1e1e1">{/if}-->
                            {if $i%2==0}
                                {assign var=bgcolor value=""}
                            {else}
                                {assign var=bgcolor value="#cacacf"}
                            {/if}
                        <tr bgcolor="{$bgcolor}">
                            <td>{$campos.codigo}</td>
                            <td>{$campos.usuario}</td>
                            <td>{$campos.fecha}</td>
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="impresion" onclick="javascript:window.open('../../reportes/detalle_codigo.php?id_transaccion={$campos.id}', '');" title="Imprimir Detalle de Codigo" src="../../../includes/imagenes/ico_print.gif">
                            </td><!--
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=deleteEstado&amp;id={$smarty.get.id}&amp;cod={$campos.id}'" title="Eliminar" src="../../../includes/imagenes/delete.gif"/> 
                            </td>-->
                            
                            
                        </tr>
                        {assign var=ultimo_cod_valor value=$campos.id}
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
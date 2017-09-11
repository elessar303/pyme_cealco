<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snipper de código para obtener las bondades de la reutilización.
-->
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
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td><strong>{$campos}</strong></td>
                            {/foreach}
                            <td colspan="1" style="text-align:center;"><strong>Opciones</strong></td>
                        </tr>
                        {if $cantidadFilas == 0}
                            <tr><td colspan="6" style="text-align: center;">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                
                                    <td style="text-align: center;">{$campos.cod_acta_visita}</td>
                                    <td style="text-align: center; padding-left: 20px;">{$campos.nombreyapellido}</td>
                                    <td style="text-align: center;">{$campos.cedula_persona_visita}</td>
                                    <td style="text-align: right; padding-right: 20px;">{$campos.telefono}</td>
                                    <td style="text-align: right; padding-right: 20px;">{$campos.descripcion_visita}</td>
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/calidad_visita.php?id={$campos.id}&cod_acta={$campos.cod_acta_visita}', '');" title="Imprimir Detalle de Visita" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>
                                    <!--<td style="text-align: center; cursor: pointer; width: 30px;">
                                        <img style="cursor: pointer;" class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=delete&amp;cod={$campos.id_cliente}'" title="Eliminar"  src="../../../includes/imagenes/delete.gif"/>
                                    </td>-->
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
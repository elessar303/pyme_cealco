<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción (es):
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común para creación de cabeceras de los
    formularios.
2,_ Factorizacion y eliminación de codigo redundante así como separación
    de contenido y de presentación
Objetivos (es):
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho código en un snippet para aprovechar las bondades de la
    reutilización.
2._ Separar el contenido de su presentación para así tener código HTML correcto.
-->
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    {include file="snippets/header_form.tpl"}
</head>
<body>
    <form id="form-{$name_form}" name="formulario" action="" method="post">
        <div id="datosGral" class="x-hide-display">
            {include file = "snippets/regresar_buscar_botones.tpl"}
            {include file = "snippets/tb_head.tpl"}
            <br/>
            <table class="seleccionLista">
                <thead>
                    <tr class="tb-head">
                        {foreach from=$cabecera key=i item=campos}
                        <th style="text-align:center;">{$campos}</th>
                        {/foreach}
                    
                    </tr>
                </thead>
                <tbody>
                    {if $cantidadFilas eq 0}
                    <tr><td colspan="10" style="text-align:center;">{$mensaje}</td></tr>
                    {else}
                    {foreach from=$registros key=i item=campos}
                    {if $i mod 2 eq 0}
                    {assign var=bgcolor value=""}
                    {else}
                    {assign var=bgcolor value="#cacacf"}
                    {/if}
                    <tr bgcolor="{$bgcolor}">
                    {if $campos.NAME eq null}
                    <td style="text-align:center;">{$campos.nombreyapellido}</td>
                    {else}
                    <td style="text-align:center;">{$campos.NAME}</td>
                    {/if}
                    <td style="padding-left:10px; text-align:center;">{$campos.fecha}</td>
                    {if $campos.NAME eq null}
                    <td style="text-align:center; padding-right:20px;">{$campos.cod_usuario}</td>
                    {else}
                    <td style="text-align:center;">{$campos.ID}</td>
                    {/if}
                    <td style="text-align:center;">{$campos.efectivo}</td>
                    <!--<td style="cursor:pointer; width:30px; text-align:center">
                        <img class="editar" onclick="javascript: window.open('../../reportes/imprimir_arqueo_cajero.php?id={$campos.id}');" title="Imprimir Copia Dep&oacute;sito" src="../../../includes/imagenes/ico_print.gif"/>
                    </td>-->    
                    <!--
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=delete&amp;cod={$campos.id_item}'" title="Eliminar" src="../../../includes/imagenes/delete.gif"/>
                    </td> -->
                    
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
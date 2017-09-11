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
                    <tr class="tb-head" style="text-align:center;">
                        {foreach from=$cabecera key=i item=campos}
                        <th style="text-align:center;">{$campos}</th>
                        {/foreach}
                    </tr>
                </thead>
                <tbody>
                    {if $cantidadFilas eq 0}
                    <tr><td colspan="9" style="text-align:center;">{$mensaje}</td></tr>
                    {else}
                    {foreach from=$registros key=i item=campos}
                    {if $i mod 2 eq 0}
                    {assign var=bgcolor value=""}
                    {else}
                    {assign var=bgcolor value="#cacacf"}
                    {/if}
                    <tr bgcolor="{$bgcolor}">
                      
                      <!-- <td style="text-align:center;">
                        {if $campos.foto eq ""}                                		
                        &nbsp;
                        {else}
                        <img src="../../imagenes/{$campos.foto}" width="100" align="absmiddle" height="100"/>
                        {/if}                                		
                    </td>-->
                    {assign var="coniva" value="`$campos.precio1*$campos.iva/100`"}
                    <td style="text-align:right; padding-right:20px;">{$campos.codigo_barras}</td>
                    <td style="padding-left:10px;">{$campos.descripcion1} - {$campos.marca} {$campos.pesoxunidad|string_format:"%.2f"}{$campos.nombre_unidad}</td>
                    <td style="text-align:center; padding-right:20px;">{$campos.descripcion}</td>
                    <td class="cantidades">{$campos.precio1|string_format:"%.2f"}</td>
                    <td class="cantidades">{$campos.iva|string_format:"%.2f"}</td>
                    <td class="cantidades">{$coniva+$campos.precio1|string_format:"%.2f"}</td>
                    <!--
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=delete&amp;cod={$campos.id_item}'" title="Eliminar" src="../../../includes/imagenes/delete.gif"/>
                    </td> -->
         
                    <!-- boton donde se agregan los seriales -->
                      {if ($campos.seriales eq 1) }
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=serial&amp;cod={$campos.id_item}'" title="agregar serial" src="../../../includes/imagenes/add.gif"/>
                    </td>
                    {/if}

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
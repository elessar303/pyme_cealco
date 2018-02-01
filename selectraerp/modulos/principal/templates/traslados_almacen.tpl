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
        <script type="text/javascript" src="../../libs/js/salida_almacen.js"></script>
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_buscar_botones.tpl"}
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head" >
                            {foreach from=$cabecera key=i item=campos}
                                <td>{$campos}</td>
                            {/foreach}
                            <td style="text-align:center;">Opciones</td>
                        </tr>
                        {if $cantidadFilas eq 0}
                            <tr><td colspan="6">{$mensaje}</td></tr>
                            {else}
                                {foreach from = $registros item=campos key=i}
                                    {if $i%2 eq 0}
                                        {assign var=color value=""}
                                    {else}
                                        {assign var=color value="#cacacf"}
                                    {/if}
                                <tr bgcolor="{$color}" style="cursor: pointer;" class="detalle">
                                    <td style="text-align: right; padding-right: 20px;">{$campos.id_transaccion}</td>
                                    <td style="text-align:center;">{$campos.fecha|date_format:"%d-%m-%Y"}</td>
                                    <td style="padding-left: 20px;">{$campos.autorizado_por}</td>
                                    <td style="padding-left: 20px;">{$campos.descripcion}</td>
                                    <td style="padding-left: 20px;">{$campos.observacion}</td>
                                    <td style="width:50px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif"/>
                                        <input type="hidden" name="id_transaccion" value="{$campos.id_transaccion}"/>
                                        <input type="hidden" name="id_tipo_movimiento_almacen" value="{$campos.id_tipo_movimiento_almacen}"/>
                                    </td>
                                    <!--td style="width:30px; text-align:center;">
                                        {if $campos.estado eq "Entregado"}
                                            <img title="Entregado" src="../../../includes/imagenes/ico_ok.gif"/>
                                        {elseif $campos.estado eq "Pendiente"}
                                            <img title="Pendiente" src="../../../includes/imagenes/ico_note.gif"/>
                                        {/if}
                                    </td-->
                                </tr>
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
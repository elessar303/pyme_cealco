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
        <script type="text/javascript" src="../../libs/js/entrada_cotizacion_agregar.js"></script>
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_boton.tpl"}
                <!-- {include file = "snippets/tb_head_mercadeo.tpl"} -->
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td>{$campos}</td>
                            {/foreach}
                            <td colspan="3" style="text-align:center;">Opciones</td>
                        </tr>
                        {if $cantidadFilas eq 0}
                            <tr><td colspan="3">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros item=campos key=i}
                                {if $i%2 eq 0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}" style="cursor: pointer;" class="detalle">
                                    <td style="text-align:center; padding-right: 20px;">{$campos.nro_cotizacion}</td>
                                    <td style="text-align:center;">{$campos.fecha|date_format:"%d-%m-%Y"}</td>
                                    <td style="padding-left: 20px;">{$campos.observacion}</td>
                                    <td style="text-align: center">{$campos.estatus_name}</td>
                                    <!-- <td style="padding-left: 20px;">{$campos.producto}</td>
                                    <td style="padding-left: 20px;">{$campos.precio}</td> -->
                                    <!--<td style="padding-left: 20px;">{$campos.empresa_transporte}</td>
                                    <td style="padding-left: 20px;">{$campos.nombre_conductor}</td>
                                    <td style="padding-left: 20px;">{$campos.cedula_conductor}</td>
                                    <td style="padding-left: 20px;">{$campos.placa}</td>
                                    <td style="padding-left: 20px;">{$campos.guia_sunagro}</td>-->
                                    {if $campos.id_detalle_producto neq ''}
                                    <td style="width:30px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif" title="Ver detalle" id="target"/>
                                        <input type="hidden" name="id_estudio" value="{$campos.id_estudio}"/>
                                        <input type="hidden" name="id_menu" value="{$smarty.get.opt_menu}"/>
                                        <input type="hidden" name="id_seccion" value="{$smarty.get.opt_seccion}"/>
                                    </td>
                                    {else}
                                    <td style="width:30px; text-align: center;">
                                        <img class="boton_detalle2" src="../../../includes/imagenes/delete.png" title="Sin productos asignados"/>
                                        <input type="hidden" name="id_estudio" value="{$campos.id_estudio}"/>
                                        <input type="hidden" name="id_menu" value="{$smarty.get.opt_menu}"/>
                                        <input type="hidden" name="id_seccion" value="{$smarty.get.opt_seccion}"/>
                                    </td>
                                    {/if}

                                    <!-- <td style="width: 30px; text-align:center;">
                                        {if $campos.estado eq "Entregado"}
                                            <img title="Entregado" src="../../../includes/imagenes/ico_ok.gif"/>
                                        {elseif $campos.estado eq "Pendiente"}
                                            <img title="Pendiente" src="../../../includes/imagenes/ico_note.gif"/>
                                        {else}
                                            <img title="Cancelado" src="../../../includes/imagenes/delete.png"/>
                                        {/if}
                                    </td> -->
                                     <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/entrada_cotizacion.php?id_estudio={$campos.id_estudio}', '');" title="Imprimir Detalle de la Cotización" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>
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
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <script type="text/javascript" src="../../libs/js/entrada_almacen.js"></script>
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
                            <td colspan="3" style="text-align:center;">Opciones</td>
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
                                    <td style="padding-left: 20px;">{$campos.nombre}</td>
                                    <td style="text-align: center;">{$campos.fecha|date_format:"%d-%m-%Y"}</td>
                                    <td style="padding-left: 20px;">{$campos.autorizado_por}</td>
                                    <td style="padding-left: 20px;">{$campos.observacion_despacho}</td>
                                    <td style="padding-left: 20px;">{$campos.observacion}</td>
                                    <td style="padding-left: 20px;">{$campos.estatus}</td>
                                    <td style="width:50px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif"/>
                                        <input type="hidden" name="id_transaccion" value="{$campos.id_transaccion}"/>
                                        <input type="hidden" name="id_tipo_movimiento_almacen" value="{$campos.id_tipo_movimiento_almacen}"/>
                                    </td>
                                    <td style="width: 30px; text-align:center">
                                        {if $campos.cod_movimiento neq "" }
                                            <img title="Entregado" src="../../../includes/imagenes/ico_ok.gif"/>
                                        {elseif $campos.cod_movimiento eq ""}
                                            <img title="Pendiente" src="../../../includes/imagenes/ico_note.gif"/>
                                        {/if}
                                    </td>
                                     <td style="cursor:pointer; width:30px; text-align:center" colspan="2">
                                     {if $campos.observacion neq "Pedido Anulado"}
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id_transaccion}'" title="Gestionar Despacho" src="../../../includes/imagenes/edit.gif"/>
                                    {elseif $campos.cod_movimiento neq ""}
                                    <img class="impresion" onclick="javascript:window.open('../../reportes/entrada_almacen.php?id_transaccion={$campos.id_transaccion}', '');" title="Imprimir Detalle de Movimiento" src="../../../includes/imagenes/ico_print.gif"/>
                                    {/if}
                                    {if ($campos.id_tipo_despacho eq "" || $campos.id_tipo_despacho eq "0") && $campos.cod_movimiento neq ""}
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=tipodespacho&amp;cod={$campos.id_transaccion}'" title="Agregar Tipo Despacho" src="../../../includes/imagenes/edit.gif"/>
                                    {/if}
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
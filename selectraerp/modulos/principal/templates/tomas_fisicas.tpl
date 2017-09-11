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
                {include file = "snippets/regresar_boton.tpl"}
                {include file = "snippets/tb_head.tpl"}
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
                                    <td style="text-align: center; padding-right: 20px;">{$campos.id_mov}</td>
                                    <td style="text-align: center; padding-right: 20px;">{$campos.descripcion}</td>
                                    <td style="text-align:center;">{$campos.fecha_apertura|date_format:"%d-%m-%Y"}</td>
                                    {if $campos.tipo_toma eq 1}
                                    <td style="padding-left: 20px; text-align:center;">Rapida</td>
                                    {else}
                                    <td style="padding-left: 20px; text-align:center;">Completa</td>
                                    {/if}
                                    <td style="padding-left: 20px; text-align:center;">{$campos.nombreyapellido}</td>
                                    
                                   <td style="cursor:pointer; width:30px; text-align:center">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;cod={$campos.id_mov}&amp;editar=1&amp;opt_subseccion=add'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                                    </td>
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/toma_fisica_almacen.php?id={$campos.id_mov}', '');" title="Imprimir Detalle de la toma" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>

                                     {if $campos.toma eq 4 and $campos.estatus_toma neq 1}
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;cod={$campos.id_mov}&amp;editar=1&amp;opt_subseccion=ajuste'" title="Ajustar Inventario segun Toma Fisica" src="../../../includes/imagenes/ico_up.gif"/>
                                    </td>
                                    {/if}

                                    {if $campos.estatus_toma eq 1}
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;cod={$campos.id_mov}&amp;editar=2&amp;opt_subseccion=ajuste'" title="Ver Ajuste Realizado" src="../../../includes/imagenes/ico_search.gif"/>
                                    </td>
                                    {/if}
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
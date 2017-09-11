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
        <table class="navegacion" style="width: 100%;">
            <tr>
                <td>
                    <table class="tb-tit" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="float:left">
                                        <input name="imagen" id="imagen" type="hidden" value="{$campo_seccion[0].img_ruta}"/>
                                    </span>
                                </td>
                                <td class="btn" style="float:right; padding-right: 15px;">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px;">Regresar</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                
                                </td>
                                <td class="btn" style="float:right">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=add&amp;tipo_log=balance'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px; width: 400px; height: 21px;"> Balance</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>

                                </td>
                                <td class="btn" style="float:right">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=add&amp;tipo_log=empaque'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 0px; width: 400px; height: 21px;"> Empaque</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 3px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td class="btn" style="float:right; margin-right: 20px;">
                                    <table class="btn_bg"  onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=transferencia&amp;tipo_log=transferencia'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 0px; width: 400px; height: 21px;"> Transferencia</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 3px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td><strong>{$campos}</strong></td>
                            {/foreach}
                            <td colspan="2" style="text-align:center;"><strong>Opciones</strong></td>
                        </tr>
                        {if $cantidadFilas == 0}
                            <tr><td colspan="6" style="text-align:center;">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td style="text-align:center; width: 100px;">{$campos.orden_compra}</td>
                                    <td style="text-align:center;">{$campos.fecha_planificacion|date_format:"%d/%m/%Y"}</td>
                                    <td style="text-align:center; padding-left: 20px;">{$campos.descripcion}</td>
                                    <td style="cursor: pointer; width: 30px; text-align:center;">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;id={$campos.id}'" title="Productos De La Orden" src="../../../includes/imagenes/edit.gif"/>
                                    </td>
                                    
                                        <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/pda_logistica.php?id_transaccion={$campos.id}', '');" title="Imprimir Detalles De La Orden" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>
                                    
                                    <!--<td style="cursor: pointer; width: 30px; text-align:center;">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=newCompra&amp;cod={$campos.id_proveedor}'" width="17" height="17" title="Generar Nueva Compra" src="../../../includes/imagenes/40.png"/>
                                    </td>-->
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
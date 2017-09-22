<!DOCTYPE html>
<!--
Modificado por: daniel fernandez

-->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=ubicacion&amp;cod={$smarty.get.cod}" method="post">
            <div id="datosGral" class="x-hide-display">
            {include file = "snippets/regresar_buscar_botones_ubicacion.tpl"}
            {include file = "snippets/tb_head_sub.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                        {foreach from=$cabecera key=i item=campos}
                            <td><b>{$campos}</b></td>
                        {/foreach}
                            <td colspan="3" style="text-align:center;"><b>Opciones</b></td>
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
                            <td>{$campos.id}</td>
                            <td>{$campos.descripcion}</td>                           
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="editar" {if $smarty.get.loc } onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=editUbicacion&amp;cod={$smarty.get.cod}&amp;id={$campos.id}&amp;idLocalidad={$smarty.get.idLocalidad}&amp;loc=1'" {else}onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=editUbicacion&amp;cod={$smarty.get.cod}&amp;id={$campos.id}'" {/if} title="Editar" src="../../../includes/imagenes/edit.gif"/>
                            </td>
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="Personalizar" {if $smarty.get.loc } onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=propiedadesUbicacion&amp;cod={$smarty.get.cod}&amp;id={$campos.id}&amp;id={$campos.id}&amp;idLocalidad={$smarty.get.idLocalidad}&amp;loc=1'"{else} onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=propiedadesUbicacion&amp;cod={$smarty.get.cod}&amp;id={$campos.id}'" {/if} title="Personalizar" src="../../../includes/imagenes/ico_propiedades.gif"/> 
                            </td>
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="eliminar" {if $smarty.get.loc } onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=deleteUbicacion&amp;cod={$smarty.get.cod}&amp;id={$campos.id}&amp;id={$campos.id}&amp;idLocalidad={$smarty.get.idLocalidad}&amp;loc=1'"{else} onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=deleteUbicacion&amp;cod={$smarty.get.cod}&amp;id={$campos.id}'" {/if} title="Eliminar" src="../../../includes/imagenes/delete.gif"/> 
                            </td>
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
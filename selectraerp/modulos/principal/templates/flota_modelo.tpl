<!DOCTYPE html>
<html>
    <head>
        <title>Flota De Vehiculos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <script type="text/javascript">
        {literal}
        function eliminar(objeto)
        {
            id=objeto.id;
            if(confirm('¿Esta Seguro De Eliminar Este Elemento?'))
            {

                parametros=
                {
                    "opt": "eliminarModeloTransporte",
                    "id": id,
                };
                $.ajax({
                    type: 'POST',
                    data: parametros,
                    url: '../../libs/php/ajax/ajax.php',
                    success: function(data) 
                    {
                        this.vcampos = eval(data);
                        if(data==1)
                        {
                            Ext.Msg.alert("¡Eliminacion Exitosa!");
                            location.reload();
                        }
                        else
                        {
                            Ext.Msg.alert("Error, Contacte al administrador del sistema");
                        }
                    }
                });

            }
            else
            {
                return false;
            }
        };
        {/literal}
        
        </script>

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
                                <td><b>{$campos}</b></td>
                            {/foreach}
                            <td colspan="2" style="text-align:center;"><b>Opciones</b></td>
                        </tr>
                        {if $cantidadFilas == 0}
                            <tr><td colspan="4">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                        <tr bgcolor="{$color}">
                            <td align="center">{$campos.id}</td>
                            <td align="center">{$campos.descripcion_modelo}</td>
                            <td style="cursor:pointer; width:30px; text-align:center;">
                                <img class="editar"  onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id}&amp;pagina={$pagina}'" title="Editar" src="../../libs/imagenes/edit.gif"/>
                            </td>
                           <td style="cursor:pointer; width:30px; text-align:center;">
                                <img class="eliminar" onclick="eliminar(this)" id="{$campos.id}" title="Eliminar" src="../../libs/imagenes/delete.gif"/>
                            </td>
                        </tr>
    {assign var = ultimo_cod_valor value=$campos.cod_especialidad}
    {/foreach}
{/if}
  </tbody>
</table>
    {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
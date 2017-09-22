<!DOCTYPE html>
<html>
    <head>
        
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        
        {include file="snippets/header_form.tpl"}
        {literal}
            <script type="text/javascript">
                
                function eliminar(id)
                {
                    if(confirm("Seguro que desea eliminar este elemento"))
                    {
                        //se llama a la funcion del ajax para eliminar los servicios asociados a una ubicacion
                        $.ajax(
                        {
                            type: "GET",
                            url:  "../../libs/php/ajax/ajax.php",
                            data: "opt=eliminarServicioUbicacion&v1="+id,
                            success: function(data)
                            {
                                resultado = data
                                if(resultado==-1)
                                {
                                    alert('Erro Al Intentar Borrar Un Registro, Consulte Al Administrador');
                                }
                                if(resultado==1)
                                {
                                    alert('Registro Borrado');
                                    location.reload();
                                }
                            }
                        });
                    }
                };
                
            </script>
        {/literal}
        
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=ubicacion&amp;cod={$smarty.get.cod}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_buscar_botones_ubicacion_propiedades.tpl"}
                {include file = "snippets/tb_head_sub.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head" >
                            {foreach from=$cabecera key=i item=campos}
                                <td align="center">
                                    <b>{$campos}</b>
                                </td>
                            {/foreach}
                            <td colspan="3" style="text-align:center;">
                                <b>Opciones</b>
                            </td>
                        </tr>
                    {if $cantidadFilas == 0}
                        <tr>
                            <td colspan="3" align="center">
                                {$mensaje}
                            </td>
                        </tr>
                    {else}
                        {foreach from = $registros key=i item=campos}
                            {if $i%2==0}
                                {assign var=bgcolor value=""}
                            {else}
                                {assign var=bgcolor value="#cacacf"}
                            {/if}
                            <tr bgcolor="{$bgcolor}">
                                <td>
                                    {$campos.id}
                                </td>
                                <td>
                                    {$campos.descripcion1}
                                </td>
                                <td style="cursor: pointer; width: 30px; text-align:center">
                                    <img class="eliminar" id="{$campos.id}" onclick="eliminar(this.id);" title="Eliminar" src="../../../includes/imagenes/delete.gif"/> 
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
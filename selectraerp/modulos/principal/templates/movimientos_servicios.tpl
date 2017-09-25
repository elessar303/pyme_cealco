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
                {include file = "snippets/regresar_solo.tpl"}
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
                            <td colspan="2" style="text-align:center;">
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
                                <td style="cursor: pointer; width:100px; text-align:center;">
                                    {$campos.movimiento_servicio}
                                </td>
                                <td style="cursor: pointer; width:500px; text-align:left;">
                                    {$campos.descripcion}
                                </td>
                                <td style="cursor: pointer; width:50px; text-align:center;" >
                                    <img class="Agregar Servicio" {if $smarty.get.loc } onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=movimientoservicio&amp;cod={$smarty.get.cod}&amp;idmovimiento={$campos.id}&amp;loc=1'"{else} onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=movimientoservicio&amp;cod={$smarty.get.cod}&amp;idmovimiento={$campos.movimiento_servicio}&amp;loc=1'" {/if} title="Agregar Servicio" src="../../../includes/imagenes/ico_propiedades.gif"/> 
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
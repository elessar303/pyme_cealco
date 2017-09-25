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
                            data: "opt=eliminarServicioMovimiento&v1="+id,
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
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px;">Regresar</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                    <!-- Estudiar la posibilidad de sustituit la tabla anterior por el snippets regresar_boton.tpl-->
                                </td>
                                <td class="btn" style="float:right">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=movimientoservicioadd&amp;idmovimiento={$idmovimiento}'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px;">Agregar</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
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
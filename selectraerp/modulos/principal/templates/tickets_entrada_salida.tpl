<!DOCTYPE html>
<html>
    <head>
        <title>Modulo Transporte Conductores</title>
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
                    "opt": "eliminarConductor",
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
                            <td  style="text-align:center;" colspan="2"><b>Opciones</b></td>
                        </tr>
                        {if $cantidadFilas == 0}
                        <tr>
                            <td colspan="8" align="center">{$mensaje}</td>
                        </tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                        <tr bgcolor="{$color}">
                            <td align="center">{$campos.id_ticket}</td>
                            <td align="center">{$campos.cedula}</td>
                            <td align="center">{$campos.nombres} {$campos.apellidos}</td>
                            <td align="center">{$campos.hora_entrada|date_format:"%d-%m-%Y %I:%M %p"}</td>
                            <td align="center">{$campos.peso_entrada}</td>
                                {if $campos.hora_salida == "0000-00-00 00:00:00"}
                                <td align="center">{$campos.hora_salida}</td>
                                {else}
                                <td align="center">{$campos.hora_salida|date_format:"%d-%m-%Y %I:%M %p"}</td>
                                {/if}
                            <td align="center">{$campos.peso_salida}</td>

                            {if $campos.hora_salida == "0000-00-00 00:00:00"}
                            <td style="cursor:pointer; width:30px; text-align:center;">
                                <img class="editar"  onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id_ticket}&amp;pagina={$pagina}'" title="Editar" src="../../libs/imagenes/edit.gif"/>
                            </td>
                            {/if}
                            {if $campos.hora_salida != "0000-00-00 00:00:00"}
                            <td style="cursor:pointer; width:30px; text-align:center;">
                                <img class="ver" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=view&amp;cod={$campos.id_ticket}&amp;pagina={$pagina}'" title="Ver" src="../../libs/imagenes/view.gif"/>
                            </td>
                            {/if}

                            {if $campos.id_transaccion != ""}
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="impresion" onclick="javascript:window.open('../../reportes/entrada_almacen_calidad.php?id_transaccion={$campos.id_transaccion}', '');" title="Imprimir Detalle de Movimiento" src="../../../includes/imagenes/ico_print.gif"/>
                            </td>
                            {/if}
                            {if $campos.id_transaccion == ""}
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="impresion" title="Sin Movimiento Asociado" src="../../../includes/imagenes/ico_note.gif"/>
                            </td>
                            {/if}
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
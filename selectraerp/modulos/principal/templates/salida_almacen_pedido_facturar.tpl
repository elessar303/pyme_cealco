<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <script type="text/javascript" src="../../libs/js/entrada_almacen_servicios.js"></script>
        <script language="JavaScript" type="text/javascript" src="../../libs/js/funciones.js"></script>
        {literal}
        <script type="text/javascript">//<![CDATA[
            function eliminar( factura, id)
            {
                if(confirm("¿Esta seguro de querer eliminar la factura "+ factura + " de id " + id + "?"))
                {
                    //ajax de eliminar factura
                    $.ajax(
                    {
                        type: "POST",
                        data: 'opt=EliminarFacturaPedido&'+'factura='+factura+'&id='+id,
                        url: '../../libs/php/ajax/ajax.php',
                        dataType: "html",
                        asynchronous: false, 
                        error: function()
                        {
                            alert("error petici�n ajax");
                        },
                        success: function(data)
                        {
                            if(data==1)
                            {
                                alert("Factura Eliminada");
                               // location.reload();
                            }
                            else
                            {
                                alert("Error al Eliminar Factura, Consulte al Administrador");
                            }
                        }
                    });
                }
                else
                {
                    return false;
                }
                
            };
        </script>
        {/literal}
        
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_solo.tpl"}
                {include file = "snippets/tb_head_botoncargo.tpl"}
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
                                    <td style="padding-left: 20px;">{$campos.descripcion}</td>
                                    <td style="padding-left: 20px;">{$campos.observacion}</td>
                                    <td style="text-align: center;">
                                        {if $campos.facturado eq 0}
                                            <img src="../../../includes/imagenes/ico_cancel.gif"/>
                                        {else}
                                            <img src="../../../includes/imagenes/ico_ok.gif"/>
                                        {/if}
                                    </td>
                                    <td style="width:50px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif"/>
                                        <input type="hidden" name="id_transaccion" value="{$campos.id_transaccion}"/>
                                        <input type="hidden" name="estatus" value="{$campos.estatus}"/>
                                        <input type="hidden" name="id_cliente" value="{$campos.id_cliente}"/>
                                        <input type="hidden" name="id_tipo_movimiento_almacen" value="{$campos.id_tipo_movimiento_almacen}"/>
                                    </td>
                                    <td style="cursor:pointer; width:30px; text-align:center" >
                                        {if $campos.facturado eq 0}
                                           <img style="cursor: pointer;" class="newfactura" onclick="javascript: window.open('cron_cargosautomaticos.php?despacho={$campos.id_cliente}','window','menubar=1,resizable=1,fullscreen=yes');" title="Cerrar Pedido" src="../../../includes/imagenes/factu.png"/>
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
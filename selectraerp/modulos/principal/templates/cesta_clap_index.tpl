<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snippet de código para obtener las bondades de la reutilización.
-->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <script type="text/javascript" src="../../libs/js/clap_detalle.js"></script>
        {literal}
        <script language="JavaScript" type="text/JavaScript"> 

        function eliminarDetalleClap(objeto) 
        {
            consulta=objeto.id;
            $.ajax({
                type: "POST",
                url: '../../libs/php/ajax/ajax.php',
                data: "opt=eliminarClapDetalle&id="+consulta,
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
                        alert('Se Ha Eliminado El Elemento');
                        location.reload();
                    }
                    else
                    {
                        alert('Error al intentar eliminar elemento, consulte al administrador');
                        location.reload();
                    }
                    
                }
            });
        }
         </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_buscar_botones.tpl"}
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
                                  
                                    <td style="padding-left: 20px;">{$campos.id}</td>
                                    <td style="padding-left: 20px;">{$campos.nombre}</td>
                                    <td style="text-align:center;">{$campos.created_at|date_format:"%d-%m-%Y"}</td>
                                    
                                    <td style="width:30px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif" title="Ver detalle"/>
                                        <input type="hidden" name="id_transaccion" value="{$campos.id}"/>
                                       
                                    </td>
                                    
                                    <!--  <td style="cursor: pointer; width: 30px; text-align:center">
                                      <img class="impresion" onclick="javascript:window.open('../../reportes/entrada_almacen_calidad.php?id_transaccion={$campos.id_transaccion}', '');" title="Imprimir Detalle de Movimiento" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>-->
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
<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción (es):
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común para creación de cabeceras de los
    formularios.
2,_ Factorizacion y eliminación de codigo redundante así como separación
    de contenido y de presentación
Objetivos (es):
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho código en un snippet para aprovechar las bondades de la
    reutilización.
2._ Separar el contenido de su presentación para así tener código HTML correcto.
-->
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    {include file="snippets/header_form.tpl"}
</head>
<body>
    <form id="form-{$name_form}" name="formulario" action="" method="post">
        <div id="datosGral" class="x-hide-display">
            {include file = "snippets/regresar_boton.tpl"}
            
            <br/>
            <table class="seleccionLista">
                <thead>
                    <tr class="tb-head">
                        
                        <th colspan="11" align="center"><h3>Archivos</h3></th>
                        
                        <!--<th colspan="4">Opciones</th>-->
                    </tr>
                </thead>
                <tbody>
                    {if $cantidadFilas eq 0}
                    <tr><td colspan="10" style="text-align:center;">{$mensaje}</td></tr>
                    {else}
                    {foreach from=$archivos key=i item=archivo}
                    {if $i mod 2 eq 0}
                    {assign var=bgcolor value=""}
                    {else}
                    {assign var=bgcolor value="#cacacf"}
                    {/if}
                <tr bgcolor="{$bgcolor}">
                    <!--<td style="text-align:center;">{$campos.codigo_barras}</td>-->
                    {if $archivo ne '.' && $archivo ne '..'}
                    
                    <td style="text-align:center; font-size: large;" ><a style="display: block; width: 100%; height: 100%; " href="../../uploads/precios/{$archivo}" download="{$archivo}"><span style="color:#0c880c; text-transform: uppercase;">{$archivo}</span></a></td>
                    
                    <!-- <td>
                        {$archivo|substr:13:-4}
                    </td> -->

                    <!--<td style="padding-left:10px;">{$campos.descripcion1}</td>-->
                    <!--<td class="cantidades">{$campos.precio_sin_iva}</td>-->

                    <!--<td style="cursor:pointer; width:30px; text-align:center">
                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id_item}'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                    </td>-->
                    
                    <!--<td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="editar" onclick="" title="Procesar" src="../../../includes/imagenes/3.png"/>
                        
                    </td>-->
                    <!--Imprime el reporte de prductos actualizados/registrados HZ-->
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="impresion" onclick="javascript:window.open('../../reportes/reporte_variacion_precio.php?id_var_precio_cab={$archivo|substr:13:-4}', '');" title="Imprimir Detalle de la Variaci&oacute;n" src="../../../includes/imagenes/ico_print.gif"/>
                    </td>
                    
                    {/if}
                        
                    <!-- boton donde se agregan los seriales -->
                      

                </tr>
                {assign var=ultimo_cod_valor value=$campos.id_item}
                {/foreach}
                {/if}
            </tbody>
        </table>
        <!--{include file = "snippets/navegacion_paginas.tpl"}-->
    </div>
</form>
</body>
</html>
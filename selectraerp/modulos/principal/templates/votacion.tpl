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
    {include file="snippets/inclusiones_reportes.tpl"}

</head>
<body>
    <form id="form-{$name_form}" name="formulario" action="" method="post">
        <div id="datosGral" class="x-hide-display">
            {include file = "snippets/regresar_buscar_botones.tpl"}
            {include file = "snippets/tb_head.tpl"}
            <br/>
            <table class="seleccionLista">
                <thead>
                    <tr class="tb-head">
                        {foreach from=$cabecera key=i item=campos}
                        <th>{$campos}</th>
                        {/foreach}
                        <th colspan="4">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    {if $cantidadFilas eq 0}
                    <tr><td colspan="10" style="text-align:center;">{$mensaje}</td></tr>
                    {else}
                    {foreach from=$registros key=i item=campos}
                    {if $i mod 2 eq 0}
                    {assign var=bgcolor value=""}
                    {else}
                    {assign var=bgcolor value="#cacacf"}
                    {/if}

                    <tr bgcolor="{$bgcolor}">
                    <td style="text-align:left;">{$campos.id}</td>
                    <td style="text-align:center; font-size:11px">{$campos.nacionalidad_emp}{$campos.cedula_emp}</td>
                    <td style="text-align:center; font-size:11px">{$campos.nombre_emp} <br>{$campos.apellido_emp}</td>
                    <td class="cantidades" style="text-align:center; font-size:11px">{$campos.gerencia}</td>
                    <td class="cantidades" style="text-align:center; font-size:11px" width="130px">{$campos.estado_centro}</td>
                    <td class="cantidades" style="text-align:center; font-size:11px">{$campos.miembro}</td>
                    <td class="cantidades" style="text-align:center; font-size:11px" colspan="2" width="250px">
                    {if $campos.hora_vota neq '0000-00-00 00:00:00'}
                    <input type="text"value="{$campos.hora_vota}">
                    {/if}
                    {if $campos.hora_vota eq '0000-00-00 00:00:00'}
                        Hora: <input type="number" name="hora" id="hora" size="3" min="1" max="12" value="{$campos.hora_vota}"> 
                        Min: <input type="number" name="min" id="mib" size="3" min="0" max="59" value="{$campos.hora_vota}"> 
                        <select name="ampm" style="text-align:center; font-size:11px">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                        </select></td>
                    {/if}

                    <input type="text" name="id" id="id" value="{$campos.id}" hidden="hidden">
                    {if $campos.hora_vota eq '0000-00-00 00:00:00' && $boton_form eq 'SI'}         
                    <td style="cursor:pointer; width:30px; text-align:center">
                        <input type="submit" name="enviar" value="Guardar">
                    </td>
                    {/if}
                </tr>
                {assign var=ultimo_cod_valor value=$campos.id_item}
                {/foreach}
                {/if}
            </tbody>
        </table>
        {include file = "snippets/navegacion_paginas.tpl"}
    </div>
</form>
</body>
</html>
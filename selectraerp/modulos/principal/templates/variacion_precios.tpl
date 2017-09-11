<!DOCTYPE html>
<!--
Creado por: Lucas Sosa
Acción (es):

-->
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    {include file="snippets/header_form.tpl"}

    {literal}
<script language="JavaScript">
function crearArchivo(id, correl)
{
    var c = confirm("Seguro desea Generar el Archivo?")
    if(c == true)
    {
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=crearArchivo&v1="+id+"&correl="+correl,
            beforeSend: function(){
                $("#notificacion").html(MensajeEspera("<b>Generando Archivo<b>"));
            },
            success: function(data){
                $("#notificacion").html("");
                $("#notificacion").html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Archivo Generado</b></span>");
                alert('Archivo Generado');
                setTimeout(
                function() 
                {
                   location.reload();
                }, 1000);
            }
        });
    }
}

</script>
{/literal}
</head>
<body>
<div id="notificacion"></div>
    <form id="form-{$name_form}" name="formulario" action="" method="post">
        <div id="datosGral" class="x-hide-display">
            {include file = "snippets/regresar_buscar_botones.tpl"}
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
                    <!--<td style="text-align:center;">{$campos.codigo_barras}</td>-->
                    <td style="text-align:center; padding-right:20px;">{$campos.correlativo}</td>
                    <!--<td style="padding-left:10px;">{$campos.descripcion1}</td>-->
                    <!--<td class="cantidades">{$campos.precio_sin_iva}</td>-->
                    <td  style="text-align:center;">{$campos.fecha}</td>
                    <td style="text-align:center;">{$campos.usuario}</td>
                    <!--<td style="cursor:pointer; width:30px; text-align:center">
                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id_item}'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                    </td>-->
                    {if $campos.estatus eq 'Activo'}
                        <td style="cursor: pointer; width: 30px; text-align:center">
                            <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id_var_precio_cab}'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                        </td>
                    {else}
                        <td style="cursor: pointer; width: 30px; text-align:center">
                            
                        </td>
                    {/if}
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="procesar" onclick="crearArchivo({$campos.id_var_precio_cab},{$campos.correlativo})" width="24px" title="Generar Archivo" src="../../../includes/imagenes/3.png"/>
                    </td>
                    <!--Imprime el reporte de prductos actualizados/registrados HZ-->
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="impresion" onclick="javascript:window.open('../../reportes/reporte_variacion_precio.php?id_var_precio_cab={$campos.correlativo}', '');" title="Imprimir Detalle de la Variaci&oacute;n" src="../../../includes/imagenes/ico_print.gif"/>
                    </td>
                    
                        
                    <!-- boton donde se agregan los seriales -->
                      

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
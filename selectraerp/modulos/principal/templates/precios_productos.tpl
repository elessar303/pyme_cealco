<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        {literal}
            <script type="text/javascript">
            function actualizar_precio(prod, precio, trans, nombre, id_det_sincro, precio_old)
            {

            //verficar si existen pendientes con el mismo producto
            if(id_det_sincro!=""){
                parametros = {
                "id_det_sincro" : id_det_sincro, "opt" : "pendiente_producto", "codigo_barra" : prod,
            }
                bandera=0;
                 $.ajax({
                    type: "POST",
                    url:  "../../libs/php/ajax/ajax.php",
                    data: parametros,
                    success: function(data) {

                    if(data!=1){
                        alert("Error, Tiene Actualizaciones Pendientes De Precios Anteriores.");
                        return false;
                    }else{

                        varr=confirm('¿Desea Actualizar El Precio del Producto: '+prod+'? \n ('+nombre+') \n Precio Actual: '+precio_old+' - Precio Nuevo:'+precio)
                        if(varr){
                        if(prod.value!=''){
                        $.ajax({
                        type: "GET",
                        url:  "../../libs/php/ajax/ajax.php",
                        data: "opt=actualizar_precio_producto2&prod="+prod+"&precio="+precio+"&trans="+trans,
                        success: function() {
                        window.location.reload(true);
                        }
                        });
                        }
                        }
                    }
                    }
                    });
               

            }else{
                return false;
            }

            
            

        
        }
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file="snippets/regresar_buscar_botones.tpl"}
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                        <td colspan="6">
                        Productos Pendiente por Actualizacion de Precios
                        </td>
                        </tr>
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td><strong>{$campos}</strong></td>
                            {/foreach}
                            <td colspan="2"><strong>Opciones</strong></td> 
                        </tr>
                        {if $cantidadFilas eq 0}
                            <tr><td colspan="6" align="center">{$mensaje}</td></tr>
                        {else}
                            {foreach from = $registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                		<!--<td style="text-align:center;">
				                        {if $campos.foto eq ""}                                		
				                        &nbsp;
				                        {else}
				                        <img src="../../imagenes/{$campos.foto}" width="100" align="absmiddle" height="100"/>
				                        {/if}                                		
				                    		</td>-->
                                    <td style="text-align: center; width: 200px;">{$campos.codigo_barra}</td>
                                    <td style="text-align: center; width: 400px;">{$campos.descripcion1}</td>
                                    <td style="text-align: center;">{$campos.precio}</td>
                                    <td style="text-align: center;">{$campos.nombre_archivo}</td>
                                    <td style="text-align: center;">{$campos.fecha}</td>
                                    <td style="cursor: pointer; width: 30px; text-align: center;" colspan="2">
                                        <img class="editar" onclick="actualizar_precio('{$campos.codigo_barra}',{$campos.precio},{$campos.id_sincro}, '{$campos.descripcion1}',{$campos.id}, {$campos.coniva1});" title="Actualizar" src="../../../includes/imagenes/back.gif"/>
                                    </td>
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.id_proevedor}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {assign var=name_form value="cedula_dia_editar"}
        {include file="snippets/header_form.tpl"}
        <!--Para estilo JQuery en botones-->
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/md5_crypt.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        <script type="text/javascript" src="../../libs/js/cedula_dia_tabs.js"></script>
        {literal}
            <script type='text/javascript'>
             function validar(){
                 var todo_correcto=true;
                 if(isNaN(document.getElementById('precio_producto').value)){
                 todo_correcto = false;
                }
                 
                if(document.getElementById('precio_producto').value==null || document.getElementById('precio_producto').value.length==0 || /^\s*$/.test(document.getElementById('precio_producto').value)){
                 todo_correcto = false;
                }
                if(document.getElementById('nombre_producto').value==null || document.getElementById('nombre_producto').value.length==0 || /^\s*$/.test(document.getElementById('nombre_producto').value)){
                 todo_correcto = false;
                }
                
                
                
                if(!todo_correcto){
alert('Alguno De Los Campos Esta Incorrecto');
return todo_correcto;
}

             };
             
             
             </script>
            
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" action="" method="post" onsubmit='return validar();'>
            <div id="datosGral">
                {include file = "snippets/regresar_boton.tpl"}
                
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <input type="hidden" name="day" value="{$smarty.get.cod}"/>
                <div id="contenedorTAB">
                    <div id="div_tab1" class="x-hide-display">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="4" class="tb-head">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="label">
                                        NOMBRE:
                                    </td>
                                    
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <p style="size: 14px;"><input type="text" value="{$producto_c.nombre}" id="nombre_producto" size="30" name="nombre_producto" class="form-text"/></p>
                                    </td>
                                </tr>
                                
                                
                                
                                
                                <tr>
                                    <td colspan="3" class="label">
                                        *PRECIO
                                    </td>
                                    
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="number" name="precio_producto"  id="precio_producto" value="" size="30" id="precio_producto" class="form-text" placeholder="PRECIO SIN IVA" required/>
                                    </td>
                                    
                                </tr>
                                <tr>
                                <td colspan="3" class="label">
                                        *CATEGORIA
                                    </td>
                                    
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                    <select style="width: 290px;;" name="categoria" id="categoria" class="form-text"> <option value="0">Seleccione...</option>
                                    {html_options values=$option_values_item_cat output=$option_output_item_cat selected=$tax[0].category}
                                </select>    
                                    
                                    </td>
                                </tr>
                                <tr>
                                <td colspan="3" class="label">
                                        *IMPUESTO
                                    </td>
                                    
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <!--<input type="number" name="precio_producto"  id="precio_producto" value="" size="30" id="precio_producto" class="form-text" placeholder="PRECIO SIN IVA" required/>-->
                                        <select style="width: 290px;;" name="impuesto" id="impuesto" class="form-text"> <option value="0">Seleccione...</option>
                                    {html_options values=$option_values_item_taxes output=$option_output_item_taxes selected=$tax[0].taxcat}
                                </select>
                                
                                
                                    </td>
                                </tr>
                               <!-- <tr>
                                    <td colspan="3" class="label">
                                        Mínimo
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="minimo" value="{$days_id[0].MIN}" size="30" id="minimo" class="form-text"/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Máximo
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="maximo" value="{$days_id[0].MAX}" size="30" id="maximo" class="form-text"/>
                                        
                                    </td>
                                </tr>-->
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                <table style="width: 100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar Cambios" />
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>

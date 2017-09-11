<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
            <script type="text/javascript">
            //<![CDATA[
    $(document).ready(function(){
        $("#descripcion_almacen").focus();
        $("input[name='cancelar']").button();
        $("input[name='aceptar']").button().click(function(){
                if($("#descripcion_almacen").val()==""){
                    $("#descripcion_almacen").focus();
                    Ext.Msg.alert("Alerta","Debe Ingresar la descripción del almacen!");
                    return false;
                }

        });
    });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" method="post" action="">
            <div id="datosGral">
                {if $smarty.get.loc}
                     {include file = "snippets/regresar_boton_alm_loc.tpl"}   
                {else}
                     {include file = "snippets/regresar_boton.tpl"}
                {/if}
               
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head" style="text-align:center;">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="label">
                              Ministerio
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <select name="id_ministerio" id="id_ministerio" class="form-text">
                                 {html_options values=$option_values_ministerio output=$option_output_ministerio selected=$option_selected_ministerio}
                               </select>
                               
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                                Descripci&oacute;n
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="descripcion_distrito" value="{$datos_distrito[0].descripcion}" size="60" id="descripcion_distrito" class="form-text" />
                            </td>
                        </tr>    
                         <tr>
                            <td colspan="3" class="label">
                               Estado
                            </td> 

                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <select name="id_estado" id="id_estado" class="form-text">
                                 {html_options values=$option_output_region output=$option_values_region selected=$option_selected_estado}
                               </select>
                               
                            </td>
                         </tr>
                            <tr>
                            <td colspan="3" class="label">
                                Municipio
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="municipio" size="60" id="municipio" value="{$datos_distrito[0].municipio}" class="form-text" />
                            </td>
                        </tr>                  
                        
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar Cambios"/>
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </form>
    </body>
</html>
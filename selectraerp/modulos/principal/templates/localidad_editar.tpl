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
        $("#descripcion_localidad").focus();      
        $("input[name='aceptar']").click(function(){
                if($("#descripcion_localidad").val()==""){
                    $("#descripcion_localidad").focus();
                    Ext.Msg.alert("Alerta","Debe Ingresar la descripción del localidad!");
                    return false;
                }

        });

        // agregado el 22/01/14 para activar la region segun el estado q pertenece
                        function cargarRegion(idSelect,idEstado){
                            var paramentros="opt=cargarRegion&idEstado="+idEstado;
                            $.ajax({
                                type: "POST",
                                url: "../../libs/php/ajax/ajax.php",
                                data: paramentros,
                                beforeSend: function(datos){
                                    $("#"+idSelect).html('Cargando...');
                                },
                                success: function(datos){
                                    $("#"+idSelect).html(datos);
                                },
                                error: function(datos,falla, otroobj){
                                    $("#"+idSelect).html(' Error...');
                                }
                            });
                    };
                   // fin de la funcion 
                   // llamada de la funcion para cargar region
                        id_estado=$("#id_estado_atiende").val();                        
                        cargarRegion("regionCot",id_estado);
                    // cuando cambia estado que atiende salta la funcion ajax
                       $("#id_estado_atiende").change(function(){
                              id_estado=$("#id_estado_atiende").val(); 
                              cargarRegion("regionCot",id_estado);
                       });
                   //fin de la llamada
    });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" method="post" action="">
            <div id="datosGral">
                {include file = "snippets/regresar.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <input type="hidden" name="id_localidad" value="{$datos_localidad[0].id}"/>
               
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
                                Codigo SIGA
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="siga_localidad" value="{$datos_localidad[0].codigo_SIGA}" size="60" id="siga_localidad" class="form-text" />
                            </td>
                        </tr>
                         <tr>
                            <td colspan="3" class="label">
                                Descripci&oacute;n
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="descripcion_localidad" size="60" id="descripcion_localidad"value="{$datos_localidad[0].descripcion}" class="form-text" />
                            </td>
                        </tr>
                         <tr>
                            <td colspan="3" class="label">
                                Estado
                            </td> 

                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <select name="id_estado" id="id_estado" class="form-text">
                                 {html_options values=$option_values_localidad  output=$option_output_localidad selected=$option_selected_estado }
                               </select>
                               
                            </td>
                        </tr>  
                        <tr>
                            <td colspan="3" class="label">
                               Estado que la atiende
                            </td> 

                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <select name="id_estado_atiende" id="id_estado_atiende" class="form-text">
                                {html_options values=$option_values_localidad  output=$option_output_localidad selected=$option_selected_estadoA }
                               </select>
                               
                            </td>
                        </tr> 
                        <tr>
                            <td colspan="3" class="label">
                               Region que pertenece 
                            </td> 

                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <div style="padding: 10px;background: #E0E0E0;width: 320px" id="regionCot"></div>
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
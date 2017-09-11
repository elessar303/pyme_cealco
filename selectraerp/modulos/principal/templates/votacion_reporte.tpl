<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Lucas Sosa" />
        <title></title>
        {include file="snippets/inclusiones_reportes.tpl"}
        {literal}
        <style type="text/css">
            .imgajax{               
                position: absolute;
                top: 50%;
                left: 50%;
                margin-top: 100px; 
            }
            .cargando{
                margin-top: 10px;
                font-size: 18px;
                text-align: center;
            }

              .custom-combobox {
                position: relative;
                display: inline-block;
              }
              .custom-combobox-toggle {
                position: absolute;
                top: 0;
                bottom: 0;
                margin-left: -1px;
                padding: 0;
              }
              .custom-combobox-input {
                margin: 0;
                padding: 5px 10px;
                width: 171px;
              }
           
        </style>
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                //ajax para el reporte por html
                $("#enviarajax").click(function() {
                punto=$("#punto").val();
                estados=$("#estados").val();
                territorio=$("#territorio").val();
                consolidado=$("#consolidado").val();
                    $.ajax({
                            type: 'GET',
                             data: "opt=reporte_votacion&estados="+estados+"&punto="+punto+"&territorio="+territorio+"&consolidado="+consolidado,
                            url: '../../libs/php/ajax/ajax1.php',
                            beforeSend: function() {
                                $("#contenido_reporte").empty();
                                $("#contenido_reporte").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');


                            },
                            success: function(data) {     
                                 $("#contenido_reporte").empty();
                                  $("#contenido_reporte").html(data);
                            }
                    });//fin del ajax    

                });//fin de la funcion aceptar            
            });
            </script>
        {/literal}
        <script type="text/javascript" src="../../libs/js/underscore.js"></script>
        <script type="text/javascript" src="../../libs/js/underscore.string.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>
    </head>
    <body>
        <form name="formulario" id="formulario" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
                <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
                <table style="width:100%; background-color:white;">
                    <thead>
                        <tr>
                            <th colspan="8" class="tb-head" style="text-align:center;">
                                LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label">GERENCIA ASOCIADA:</td>
                            <td width="100px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="estados" id="estados" style="width:200px;" class="form-text">
                                <option value="0">Todos</option>
                                {html_options values=$option_values_estado output=$option_output_estado}
                                
                                </select>
                            </td>
                            <td class="label">TERRITORIO:</td>
                            <td width="100px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="territorio" id="territorio" style="width:200px;" class="form-text">
                                <option value="0">Todos</option>
                                {html_options values=$option_values_tipo_territorio output=$option_values_tipo_territorio}
                                
                                </select>
                            </td>
                            <td class="label">VOTO:</td>
                             <!-- PUNTOS -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="punto" id="punto" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>
                                    <option value="1">Si</option>
                                    <option value="2">No</option>

                                </select>
                            </td>
                            <td class="label">CONSOLIDADO:</td>
                            <td width="100px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="consolidado" id="consolidado" style="width:200px;" class="form-text">
                                <option value="0">No</option>
                                <option value="1">ESTADOS</option>
                                <option value="2">GERENCIA</option>                                
                                </select>
                            </td>
                        </tr>
                        <tr class="tb-head">
                            <td colspan="8">
                                <input type="button" id="enviarajax" name="aceptar" value="Mostrar" />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <div style="margin-top: 20px;position:relative" id="contenido_reporte">
           
           
        </div>
    </body>
</html>
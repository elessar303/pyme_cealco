<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Lucas Sosa" />
        <title></title>
        {include file="snippets/inclusiones_reportes.tpl"}
        {literal}

            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){

                //ajax para el reporte por html
                $("#enviarajax").click(function() {
                cedula=$("#cedula").val();
               
                    $.ajax({
                            type: 'POST',
                            data: "opt=pregunta1&cedula="+cedula,
                            url: '../../libs/php/ajax/ajax1.php',
                            beforeSend: function() {
                                $("#contenido").empty();
                                $("#contenido").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                            },
                            success: function(data) {     
                                 $("#contenido").empty();
                                 $("#contenido").html(data);
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
        <form name="formulario" id="formulario" action="" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_boton_ubicacion.tpl"} 
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
                <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
                <table style="width:100%; background-color:white;">
                    
                        <tr>
                          <td class="label">Cédula</td>
                          <td >
                            <input type="text" name="cedula" id="cedula" style="float: left;" class="form-text" />
                          </td>
                        </tr>
                        <tr class="tb-head">
                            <td colspan="6">
                                <input type="button" id="enviarajax" name="aceptar" value="Mostrar" />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <div style="margin-center: 20px;position:relative" id="contenido">
           
           
        </div>
    </body>
</html>
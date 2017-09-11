<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Humberto Zapata" />
        <title></title>
        {include file="snippets/inclusiones_reportes.tpl"}
        <link type="text/css" media="screen" rel="stylesheet" href="../../libs/css/nueva_factura.css" />
       
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
            

        </style>
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                $("#cliente").autocomplete({
                    source: "../../libs/php/ajax/autocomplete_cliente.php",
                    minLength: 3, // how many character when typing to display auto complete
                    select: function(e, ui) {//define select handler
                        $("#cod_cliente").val(ui.item.id);
                    }
                });
                $("#producto").autocomplete({
                    source: "../../libs/php/ajax/autocomplete_producto.php",
                    minLength: 3,
                    select: function(e, ui) {//define select handler
                        $("#cod_producto").val(ui.item.id);
                    }
                });
                $("#fecha").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "dd-mm-yy",
                    timeFormat: 'HH:mm:ss',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        //$( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                        $( "#fecha2" ).datetimepicker("option", "minDate", selectedDate);
                    }
                });
                $("#fecha2").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "dd-mm-yy",
                    timeFormat: 'HH:mm:ss',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datetimepicker( "option", "maxDate", selectedDate );
                    }
                });

                //ajax para el reporte por html
                $("#enviarajax").click(function() {
                  
                  /*Seleccion obligatoria del codigo de barras
                  hz*/
                  /*if(
                    $("#codigo_barras").val()==""
                  ){
                    Ext.Msg.alert("Alerta","Debe Especificar el Código de Barras.");
                    return false;
                  };*/


                
                desde=$("#fecha").val();
                hasta=$("#fecha2").val();
                codigo_barras=$("#codigo_barras").val();
                //var codigo_barras = document.getElementById("codigo_barras").value;
                desdeAux = desde.split("-");
                desde = desdeAux[2]+"-"+desdeAux[1]+"-"+desdeAux[0];
                hastaAux = hasta.split("-");
                hasta = hastaAux[2]+"-"+hastaAux[1]+"-"+hastaAux[0];
                
               
                    $.ajax({
                            type: 'GET',
                            data: "opt=reporte_productoEstablecimiento&desde="+desde+"&hasta="+hasta+"&codigo_barras="+codigo_barras,
                            url: '../../libs/php/ajax/ajax.php',
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

                

                  //funcion para cargar los puntos 
                  /*$("#estados").change(function() {
                    estados = $("#estados").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getPuntos&'+'estados='+estados,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#punto").find("option").remove();
                                $("#punto").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#punto").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#punto").append("<option value='0'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#punto").append("<option value='" + this.vcampos[i].siga+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                                }
                            }
                        }); 
                        $("#punto").val(0);
                  });*/

                    $("input[name='buscar']").click(function(){
                    //var teclaTabMasP  = 13;
                    //var codeCurrent = ev.keyCode;
                    var value = $(this).val();
                    //if(teclaTabMasP == codeCurrent){ 
                        //if(_.str.isBlank(value)) { 
                            pBuscaItem.main.mostrarWin();
                            return false;
                        //}

                        //$.filtrarArticulo(value, "filtroItemByRCCB");

                        //return false;
                   // }
                });

                  
            });
            //]]>
            </script>
        {/literal}
        <!--<script type="text/javascript" src="../../libs/js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../libs/js/jquery.numeric.pack.js"></script>
        <script type="text/javascript" src="../../libs/js/backbone.js"></script>
        <script type="text/javascript" src="../../libs/js/facebox/facebox.js"></script>-->
        <script type="text/javascript" src="../../libs/js/underscore.js"></script>
        <script type="text/javascript" src="../../libs/js/underscore.string.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>
        <!--<script type="text/javascript" src="../../libs/js/nueva_factura_rapida_scripts.js"></script>-->
        
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
                                <th colspan="6" class="tb-head" style="text-align:center;">
                                    LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="label">Per&iacute;odo **</td>
                                <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="fecha" id="fecha" size="20" value='{$smarty.now|date_format:"%d-%m-%Y"}' readonly class="form-text" />
                                    <!--button id="boton_fecha">...</button-->
                                    <input type="text" name="fecha2" id="fecha2" size="20" value='{$smarty.now|date_format:"%d-%m-%Y"}' readonly class="form-text" />
                                </td>
                            </tr>

                            <tr>
                              <td class="label">C&oacute;digo de Barras</td>
                              <td >
                                <input type="text" name="codigo_barras" id="codigo_barras" style="float: left;" class="form-text" />
                                <input type="button" id="buscar" name="buscar" value="Buscar" style="float: left;" />
                              </td>
                            </tr>
                            
                            
                            <!--<tr>-->
                              <!-- ESTADOS -->
                                <!--<td class="label">ESTADOS</td>
                                <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                    <select name="estados" id="estados" style="width:200px;" class="form-text">
                                        <option value="0">Todos</option>
                                   
                                    {html_options values=$option_values_estado output=$option_output_estado}
                                    
                                    </select>
                                </td>
                                <td width="80px" style="width:80px" class="label">ESTABLECIMIENTOS</td>-->
                                 <!-- PUNTOS -->
                                <!--<td  style="padding-top:2px; padding-bottom: 2px; ">
                                    <select name="punto" id="punto" style="width:200px;" class="form-text">
                                        <option value="0">Todos</option>                               
                                    {html_options values=$option_values_punto output=$option_output_punto}
                                    
                                    </select>
                                </td>
                               
                            </tr>-->
                             
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
        <div style="margin-top: 20px;position:relative" id="contenido_reporte">
           
           
        </div>
    </body>
</html>
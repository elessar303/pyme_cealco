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

              $("#categoria").change(function() {
                    categoria = $("#categoria").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getSubCategoria&'+'categoria='+categoria,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#sub_categoria").find("option").remove();
                                $("#sub_categoria").append("<option value=''>Cargando..</option>");
                                $("#producto").find("option").remove();
                                $("#producto").append("<option value='0'>Todos</option>");
                            },
                            success: function(data) {
                                $("#sub_categoria").find("option").remove();
                                //$("#producto").find("option").remove();
                                this.vcampos = eval(data);
                                if($("#categoria").val()==0){
                                  $("#sub_categoria").append("<option value='0'>Todos</option>");
                                  
                                }else{
                                  $("#sub_categoria").append("<option value='0'>Todos</option>");
                                  for (i = 0; i <= this.vcampos.length; i++) {
                                      $("#sub_categoria").append("<option value='" + this.vcampos[i].id_sub_grupo+ "'>" + this.vcampos[i].descripcion + "</option>");
                                  }
                                };
                            }
                        }); 
                        $("#sub_categoria").val(0);
                    });

                 $("input[name='buscar']").click(function(){
                    var value = $(this).val();
                            pBuscaItem.main.mostrarWin();
                            return false;
                    });

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
                $("#fecha").datetimepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    timeFormat: 'HH:mm:ss',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        //$( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                        $( "#fecha2" ).datetimepicker("option", "minDate", selectedDate);
                    }
                });
                $("#fecha2").datetimepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    timeFormat: 'HH:mm:ss',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datetimepicker( "option", "maxDate", selectedDate );
                    }
                });

                //ajax para el reporte por html
                $("#enviarajax").click(function() {
                //punto=$("#punto").val();
                //estados=$("#estados").val();
                //desde=$("#fecha").val();
                //hasta=$("#fecha2").val();
                categoria=$("#categoria").val();
                sub_categoria=$("#sub_categoria").val();
                producto=$("#codigo_barras").val();
                //tipo_punto=$("#tipo_punto").val();
                tipo_almacenamiento=$("#tipo_almacenamiento").val();
                marca_producto=$("#marca_producto").val();
               
                    $.ajax({
                            type: 'GET',
                            data: "opt=reporte_productoTotal&categoria="+categoria+"&producto="+producto+"&tipo_almacenamiento="+tipo_almacenamiento+"&marca_producto="+marca_producto+"&sub_categoria="+sub_categoria,
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

                  
            });
            //]]>


//SCRIP AUTOCOMPLETAR SELECT....!!!

    (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Mostrar Todo" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " Sin Resultado" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });

  })( jQuery );
 
  /*$(function() {
    $( "#combobox" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#combobox" ).toggle();
    });
  });

    $(function() {
    $( "#marca_producto" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#marca_producto" ).toggle();
    });
  });

$(function() {
    $( "#producto" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#producto" ).toggle();
    });
  });*/

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
                            <th colspan="6" class="tb-head" style="text-align:center;">
                                REPORTE DE PRODUCTOS ACTIVOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td class="label">Per&iacute;odo **</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="fecha" id="fecha" size="20" value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly class="form-text" />-->
                                <!--button id="boton_fecha">...</button-->
                                <!--<input type="text" name="fecha2" id="fecha2" size="20" value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly class="form-text" />
                            </td>
                        </tr> -->
                        
                        <!--<tr> ESTE tr YA ESTABA COMENTADO...!!!
                            <td class="label">Ordenar por</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="filtrado_por" id="filtrado_por" class="form-text">-->
                                    <!--option value="null">Seleccione un campo</option-->
                            <!--        <option value="REFERENCE">C&oacute;digo</option>
                                    <option value="NAME">Descripci&oacute;n</option>
                                </select>
                            </td>
                        </tr>-->
                        
                        <!--<tr>-->
                          <!-- ESTADOS -->
                            <!--<td class="label">ESTADOS</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="estados" id="estados" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>
                               
                                {html_options values=$option_values_estado output=$option_output_estado}
                                
                                </select>
                            </td>
                            <td width="80px" style="width:80px" class="label">PUNTOS</td>-->
                             <!-- PUNTOS -->
                            <!--<td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="punto" id="punto" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>                               
                                {html_options values=$option_values_punto output=$option_output_punto}
                                
                                </select>
                            </td>
                            <td width="80px" style="width:80px" class="label">TIPOS DE PUNTOS</td>-->
                             <!-- PUNTOS -->
                            <!--<td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="tipo_punto" id="tipo_punto" style="width:200px;" class="form-text">
                                    <option value="">Todos</option>                               
                                {html_options values=$option_values_tipo_punto output=$option_output_tipo_punto}
                                
                                </select>
                            </td>
                           
                        </tr>-->
                         <tr>
                          <!-- ESTADOS -->
                            
                            
                             <!-- PUNTOS -->
                            <td class="label">C&Oacute;DIGO DE BARRAS</td>
                              <td >
                                <input type="text" name="codigo_barras" id="codigo_barras" style="float: left;" class="form-text" />
                                <input type="button" id="buscar" name="buscar" value="Buscar" style="float: left;" />
                              </td>

                            
                            <td width="100px" style="width:100px" class="label">ALMACENAMIENTO</td>
                             <!-- PUNTOS -->
                            <td  style="padding-top:2px; padding-bottom: 2px; padding-left: 2px ">
                                <select name="tipo_almacenamiento" id="tipo_almacenamiento" style="width:200px;" class="form-text">
                                    <option value="">Todos</option>                               
                                    <option value="SECO">SECO</option>
                                    <option value="FRIO">FRIO</option>
                                </select>
                            </td>
                           
                        </tr>
                        <!-- COMENTADO HASTA QUE SE UNIFIQUEN LAS CATEGORIAS -->
                         <tr> 
                         <td class="label">CATEGORIA</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="categoria" id="categoria" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>
                               
                                {html_options values=$option_values_categoria output=$option_output_categoria}
                                
                                </select>
                            </td>

                            <td class="label">SUB-CATEGORIA</td>
                              <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                  <select name="sub_categoria" id="sub_categoria" style="width:200px;" class="form-text">
                                      <option value="0">Todos</option>
                                 
                                  {html_options values=$option_values_subcategoria output=$option_output_subcategoria}
                                  
                                  </select>
                              </td>
                                  
                         </tr>    

                         <tr>
                            <td class="label">MARCA</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="marca_producto" id="marca_producto" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>
                                
                                {html_options values=$option_values_marca output=$option_output_marca}
                                
                                </select>
                            </td>
                        </tr>
                        
                        <!-- <tr>
                            <td class="label">Formato Reporte</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <div id="formato">
                                    <input type="radio" id="radio1" name="radio" value="0" checked/><label for="radio1">Hoja de C&aacute;lculo</label>-->
                                  <!-- <input type="radio" id="radio2" name="radio" value="1"  />
                                    <input type="radio" id="radio2" name="radio" value="1"  /><label for="radio2">Formato PDF</label>
                                </div>
                            </td>
                        </tr> -->
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
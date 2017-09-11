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
                        $( "#fecha2" ).datepicker("option", "minDate", selectedDate);
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
                        $( "#fecha" ).datepicker( "option", "maxDate", selectedDate ); 
                    }
                });

                //ajax para el reporte por html
                $("#enviarajax").click(function() {
                //punto=$("#punto").val();
                //estados=$("#estados").val();
                desde=$("#fecha").val();
                desdeAux = desde.split("-");
                desde = desdeAux[2]+"-"+desdeAux[1]+"-"+desdeAux[0];
                hasta=$("#fecha2").val();
                hastaAux = hasta.split("-");
                hasta = hastaAux[2]+"-"+hastaAux[1]+"-"+hastaAux[0];
                //categoria=$("#categoria").val();
                //producto=$("#producto").val();
                //tipo_punto=$("#tipo_punto").val();
                //tipo_almacenamiento=$("#tipo_almacenamiento").val();
                //art_siscolp_siga=$("#art_siscolp_siga").val();
                //marca_producto=$("#marca_producto").val();
               
                    $.ajax({
                            type: 'GET',
                            data: "opt=reporte_art_autorizados&desde="+desde+"&hasta="+hasta,
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
  });*/

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
  });

            </script>
        {/literal}
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
                                ARTICULOS AUTORIZADOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label">Desde **</td>
                            <td colspan="2" style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="fecha" id="fecha" size="20" value='{$smarty.now|date_format:"%d-%m-%Y"}' readonly class="form-text" />
                                <!--<button id="boton_fecha">...</button>-->
                            </td>
                            <td class="label">Hasta **</td>
                            <td colspan="2" style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="fecha2" id="fecha2" size="20" value='{$smarty.now|date_format:"%d-%m-%Y"}' readonly class="form-text" />
                            </td>
                        <!--</tr> -->
                        
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

                        </tr>
 <!--<td colspan="3" class="label">Rubro</td>
 <td>
  <select name="cod_departamento" id="cod_departamento" class="form-text">
{html_options values=$option_output_departamentos output=$option_values_departamentos}
  </select>
 </td>
</tr>
<tr>
 <td colspan="3" class="label">--><!-- {$DatosGenerales[0].string_clasificador_inventario2} --><!--Categor&iacute;a</td>-->
 <!--<td>
  <select name="cod_grupo" id="cod_grupo" class="form-text">-->
<!-- {html_options values=$option_output_grupo output=$option_values_grupo} -->
  <!--</select>
 </td>
</tr>
<tr>
 <td colspan="3" class="label">--><!-- {$DatosGenerales[0].string_clasificador_inventario2} --><!--Sub Categor&iacute;a</td>-->
 <!--<td>
  <select name="sub_categoria" id="sub_categoria" class="form-text">-->
<!-- {html_options values=$option_output_grupo output=$option_values_grupo} -->
  <!--</select>
 </td>
</tr>-->

                         
                        
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
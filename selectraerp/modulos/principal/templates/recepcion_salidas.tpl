<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snippet de código para obtener las bondades de la reutilización.
-->
<!--
Modificado por: Melquiade Pichardo
Fecha: 10-04-2017
Acción:
1._ Modificar condiciones de filtro de consulta para selección de elementos de salida.
Objetivos:
1._ Ampliar la cantidad de opciones en el filtro de la consulta para los elementos de salida de almacen.
-->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script type="text/javascript" src="../../libs/js/recepcion_entradas.js"></script>
        {include file="snippets/header_form.tpl"}
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

                /*Modificacion para barrer los selects de sub-categoria y producto
                HZ*/
                $("#sub_categoria").find("option").remove();
                $("#producto").find("option").remove();
                //$("#sub_categoria").append("<option value='0' disabled>Todos</option>");

                //ajax para el reporte por html
                $("#enviarajax").click(function() {
                punto=$("#punto").val();
                estados=$("#estados").val();
                desde=$("#fecha").val();
                hasta=$("#fecha2").val();
                desdeAux = desde.split("-");
                desde = desdeAux[2]+"-"+desdeAux[1]+"-"+desdeAux[0];
                hastaAux = hasta.split("-");
                hasta = hastaAux[2]+"-"+hastaAux[1]+"-"+hastaAux[0];
                categoria=$("#categoria").val();
                sub_categoria=$("#sub_categoria").val();
                producto=$("#producto").val();
                tipo_punto=$("#tipo_punto").val();
                tipo_almacenamiento=$("#tipo_almacenamiento").val();
                marca_producto=$("#combobox").val();
                indices=$("#indices").val();
                codigo_barras=$("#codigo_barras").val();
               
                    $.ajax({
                            type: 'GET',
                            data: "opt=reporte_productoCentral&punto="+punto+"&estados="+estados+"&desde="+desde+"&hasta="+hasta+"&categoria="+categoria+"&producto="+producto+"&tipo_punto="+tipo_punto+"&tipo_almacenamiento="+tipo_almacenamiento+"&marca_producto="+marca_producto+"&sub_categoria="+sub_categoria+"&indices="+indices+"&codigo_barras="+codigo_barras,
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

                /*BARRE LO QUE HAY EN SUB-CATEGORIA Y PRODUCTO
                HZ*/
                //$("#sub_categoria").find("option").remove();
                //$("#sub_categoria").append("<option value='0' disabled>Todos</option>");
                if($("#categoria").val()=='0'){
                  $("#sub_categoria").append("<option value='0'>Todos</option>");
                  $("#producto").append("<option value='0'>Todos</option>");
                };
                /*if($("#categoria").val()=='0'){
                  if($("#sub_categoria").val()=='0'){
                    $("#producto").append("<option value='0'>Todos</option>");
                  };
                };*/
                /*if($("#categoria").val()=='0'){
                  $("#producto").append("<option value='0'>Todos</option>");
                };*/

                  //funcion para cargar los puntos 
                  $("#estados").change(function() {
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
                  });

                  //Prueba para traer las SUB-CATEGORIAS dependiendo de la CATEGORIA
                  //funcion para cargar la SUB-CATEGORIA
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

                  //Prueba para traer los PRODUCTOS dependiendo de la SUB-CATEGORIA
                  //funcion para cargar los PRODUCTOS
                  $("#sub_categoria").change(function() {
                    sub_categoria = $("#sub_categoria").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getProductos&'+'sub_categoria='+sub_categoria,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#producto").find("option").remove();
                                $("#producto").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#producto").find("option").remove();
                                this.vcampos = eval(data);
                                if ($("#sub_categoria").val()==0){
                                  $("#producto").append("<option value='0'>Todos</option>");
                                }else{
                                  $("#producto").append("<option value='0'>Todos</option>");
                                  for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#producto").append("<option value='" + this.vcampos[i].codigo_barras+ "'>" + this.vcampos[i].descripcion1 + "</option>");
                                  }
                                };
                            }
                        }); 
                        $("#producto").val(0);
                    });
              $("input[name='buscar2']").click(function(){
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

//SCRIP AUTOCOMPLETAR SELECT.

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
    $( "#categoria" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#categoria" ).toggle();
    });
  });

/*$(function() {
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
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_boton.tpl"}
                
                <!--{include file = "snippets/tb_head.tpl"}-->
                <!--Agregnado EL filtro-->
                
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
                                <input type="text" name="fecha" id="fecha" size="20" value='{$fecha_desde|date_format:"%d-%m-%Y"}' readonly class="form-text" />
                                <!--button id="boton_fecha">...</button-->
                                <input type="text" name="fecha2" id="fecha2" size="20" value='{$fecha_hasta|date_format:"%d-%m-%Y"}' readonly class="form-text" />
                            </td>
                        </tr>
                        <tr>
                          <!-- ESTADOS -->
                            <td class="label">Estados</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="estados" id="estados" style="width:200px;" class="form-text">
                               
                                {html_options values=$option_values_id_estado output=$option_values_nombre_estado selected=$estados}
                                
                                </select>
                            </td>
                            <td width="80px" style="width:80px" class="label">Establecimientos</td>
                             <!-- ESTABLECIMIENTO -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="punto" id="punto" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>                               
                                {html_options values=$option_values_punto output=$option_output_punto selected=$punto}
                                
                                </select>
                            </td>
                        <tr>
                         <td width="80px" style="width:80px" class="label">DOC. SISCOL</td>
                             <!-- TIPOS DE ESTABLECIMIENTO -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <input type="text" name="doc_siscol" id="doc_siscol" style="width:200px;float: left;" class="form-text" value='{$doc_siscol}'/>
                            </td>

                            <td width="100px" style="width:100px" class="label">Descripcion</td>
                             <!-- PRODUCTO -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <input type="text" name="descripcion" id="descripcion" style="width:200px;float: left;" class="form-text" value='{$descripcion}'/>
                            </td>
                            <!-- 
                            <td width="100px" style="width:100px" class="label">Documento</td>
                            <td width="200px"  style="padding-top:2px; padding-bottom: 2px; ">
                                <input type="text" name="documento" id="documento" style="float: left;" class="form-text" value='{$documento}'/>
                            </td>
                            -->
                        </tr>
                           
                        </tr>
                        <!-- 
                        <tr>

                          SUB-CATEGORIA 
                            <td class="label">RIF Proveedor</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="rif_proveedor" id="rif_proveedor" style="float: left;" class="form-text" value='{$rif_proveedor}'/>
                            </td>

                            <td width="100px" style="width:100px" class="label">Nombre Proveedor</td>
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <input type="text" name="nombre_proveedor" id="nombre_proveedor" style="float: left;" class="form-text" value='{$nombre_proveedor}'/>
                            </td>
                           
                        </tr>

                        <tr>
                          <td width="100px" style="width:100px" class="label">Guia Sunagro</td>
                            <td width="200px"  style="padding-top:2px; padding-bottom: 2px; ">
                                <input type="text" name="guia" id="guia" style="float: left;" class="form-text" value='{$guia}'/>
                            </td>

                            </tr>
                         -->
                          
                        

                        <tr>
                        <td width="80px" style="width:80px" class="label">Tipo de Despacho</td>
                             <!-- ESTABLECIMIENTO -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="despacho" id="despacho" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>                               
                                {html_options values=$option_values_id_despacho output=$option_values_des_despacho selected=$despacho}
                                
                                </select>
                            </td>

                        <td width="100px" style="width:100px" class="label">Codigo de Barras</td>
                             <!-- ALMACENAMIENTO -->
                        <td width="200px"  style="padding-top:2px; padding-bottom: 2px; ">
                                 <input type="text" name="codigo_barras" id="codigo_barras" style="float: left;" class="form-text" value='{$codigo_barras}'/>
                                <input type="button" id="buscar2" name="buscar2" value="Buscar" style="float: left;" />
                        </td>
                            

                        </tr>
                        
                        <tr align="center" class="tb-head">
                            <td colspan="4" align="center">
                              <input type="submit" id="enviarajax" name="aceptar" value="Mostrar" align="right" />
                              <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}';" />
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
                
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td>{$campos}</td>
                            {/foreach}
                            <td colspan="3" style="text-align:center;">Opciones</td>
                        </tr>
                        {if $registros eq 0}
                            <tr><td colspan="7" align="center">{$mensaje}</td></tr>
                        {else}
                        {$total = 0 }  
                            {foreach from=$registros item=campos key=i}
                                {if $i%2 eq 0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}" style="cursor: pointer;" class="detalle">
                                    <td style="text-align: left;width:75px">{$campos.cod_movimiento}</td>
                                    <td style="text-align:center;width:75px">{$campos.fecha|date_format:"%d-%m-%Y"}</td>
                                    <td style="">{$campos.observacion_cabecera|truncate:150:"...":true}</td>
                                    <td style="text-align:center">{$campos.almacen_salida}</td>
                                    <td style="text-align:center">{$campos.ubicacion_salida}</td>
                                    <td style="text-align:center">{$campos.tipo_despacho}</td>
                                    <td style="text-align:center">{$campos.total}</td>
                                    {assign var="total" value=$total+$campos.total}
                                    <td style="width:30px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif" title="Ver detalle"/>
                                        <input type="hidden" name="id_transaccion" value="{$campos.cod_movimiento}"/>
                                        <input type="hidden" name="id_tipo_movimiento_almacen" value="{$campos.tipo_movimiento}"/>
                                    </td>
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/entrada_almacen.php?id_transaccion={$campos.cod_movimiento}', '');" title="Imprimir Detalle de Movimiento" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>
                                </tr>
                            {/foreach}
                            <tr class="tb-head">
                            <td colspan="6" style="text-align:center">TOTAL UNIDADES</td>
                            <td style="text-align:center">{$total}</td>
                            </tr>
                        {/if}
                    </tbody>
                </table>
                <!DOCTYPE html>
                    <html>
                        <head>
                            <title></title>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                        </head>
                        <body>
                            <table class="tb-head">
                                <tbody>
                                    <tr>
                                        <td><span>P&aacute;gina&nbsp;</span></td>
                                        <td>
                                            <a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;pagina=1&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}&amp;instruccion={$instruccion}">
                                                <img src="../../../includes/imagenes/b_firstpage.png" title="Primera" alt="Primera" style="width:16px; height: 16px; border: 0px"/>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;pagina={$pagina-1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}&amp;instruccion={$instruccion}">
                                                <img src="../../../includes/imagenes/b_prevpage.png" alt="Anterior" title="Anterior" style="width:16px; height: 16px; border: 0px"/>
                                            </a>
                                        </td>
                                        <td>
                                            <input type="text" name="numero_pagina" value="{$pagina}" onblur="javascript:paginacion('?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}',this.value,'&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}');" size="4">
                                        </td>
                                        <td>
                                            <a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;pagina={$pagina+1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}&amp;instruccion={$instruccion}">
                                                <img src="../../../includes/imagenes/b_nextpage.png" alt="Siguiente" title="Siguiente" style="width:16px; height: 16px; border: 0px"/>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;pagina={$num_paginas}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}&amp;instruccion={$instruccion}">
                                                <img src="../../../includes/imagenes/b_lastpage.png" alt="&Uacute;ltima" title="&Uacute;ltima" style="width:16px; height: 16px; border: 0px"/>
                                            </a>
                                        </td>
                                        <td style="width:100%; text-align:center;">&nbsp;P&aacute;gina {$pagina} de {$num_paginas}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </body>
                    </html>
            </div>
        </form>
    </body>
</html>
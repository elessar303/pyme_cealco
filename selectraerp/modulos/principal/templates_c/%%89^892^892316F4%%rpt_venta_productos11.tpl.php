<?php /* Smarty version 2.6.21, created on 2017-08-28 16:11:34
         compiled from rpt_venta_productos11.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'rpt_venta_productos11.tpl', 431, false),array('function', 'html_options', 'rpt_venta_productos11.tpl', 454, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Lucas Sosa" />
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
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
                    timeFormat: \'HH:mm:ss\',
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
                    timeFormat: \'HH:mm:ss\',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datetimepicker( "option", "maxDate", selectedDate );
                    }
                });

                /*Modificacion para barrer los selects de sub-categoria y producto
                HZ*/
                $("#sub_categoria").find("option").remove();
                $("#producto").find("option").remove();
                //$("#sub_categoria").append("<option value=\'0\' disabled>Todos</option>");

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
                            type: \'GET\',
                            data: "opt=reporte_productoCentral_xprecio&punto="+punto+"&estados="+estados+"&desde="+desde+"&hasta="+hasta+"&categoria="+categoria+"&producto="+producto+"&tipo_punto="+tipo_punto+"&tipo_almacenamiento="+tipo_almacenamiento+"&marca_producto="+marca_producto+"&sub_categoria="+sub_categoria+"&indices="+indices+"&codigo_barras="+codigo_barras,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() {
                                $("#contenido_reporte").empty();
                                $("#contenido_reporte").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');


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
                //$("#sub_categoria").append("<option value=\'0\' disabled>Todos</option>");
                if($("#categoria").val()==\'0\'){
                  $("#sub_categoria").append("<option value=\'0\'>Todos</option>");
                  $("#producto").append("<option value=\'0\'>Todos</option>");
                };
                /*if($("#categoria").val()==\'0\'){
                  if($("#sub_categoria").val()==\'0\'){
                    $("#producto").append("<option value=\'0\'>Todos</option>");
                  };
                };*/
                /*if($("#categoria").val()==\'0\'){
                  $("#producto").append("<option value=\'0\'>Todos</option>");
                };*/

                  //funcion para cargar los puntos 
                  $("#estados").change(function() {
                    estados = $("#estados").val();
                        $.ajax({
                            type: \'GET\',
                            data: \'opt=getPuntos&\'+\'estados=\'+estados,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() {
                                $("#punto").find("option").remove();
                                $("#punto").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#punto").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#punto").append("<option value=\'0\'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#punto").append("<option value=\'" + this.vcampos[i].siga+ "\'>" + this.vcampos[i].nombre_punto + "</option>");
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
                            type: \'GET\',
                            data: \'opt=getSubCategoria&\'+\'categoria=\'+categoria,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() {
                                $("#sub_categoria").find("option").remove();
                                $("#sub_categoria").append("<option value=\'\'>Cargando..</option>");
                                $("#producto").find("option").remove();
                                $("#producto").append("<option value=\'0\'>Todos</option>");
                            },
                            success: function(data) {
                                $("#sub_categoria").find("option").remove();
                                //$("#producto").find("option").remove();
                                this.vcampos = eval(data);
                                if($("#categoria").val()==0){
                                  $("#sub_categoria").append("<option value=\'0\'>Todos</option>");
                                  
                                }else{
                                  $("#sub_categoria").append("<option value=\'0\'>Todos</option>");
                                  for (i = 0; i <= this.vcampos.length; i++) {
                                      $("#sub_categoria").append("<option value=\'" + this.vcampos[i].id_sub_grupo+ "\'>" + this.vcampos[i].descripcion + "</option>");
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
                            type: \'GET\',
                            data: \'opt=getProductos&\'+\'sub_categoria=\'+sub_categoria,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() {
                                $("#producto").find("option").remove();
                                $("#producto").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#producto").find("option").remove();
                                this.vcampos = eval(data);
                                if ($("#sub_categoria").val()==0){
                                  $("#producto").append("<option value=\'0\'>Todos</option>");
                                }else{
                                  $("#producto").append("<option value=\'0\'>Todos</option>");
                                  for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#producto").append("<option value=\'" + this.vcampos[i].codigo_barras+ "\'>" + this.vcampos[i].descripcion1 + "</option>");
                                  }
                                };
                            }
                        }); 
                        $("#producto").val(0);
                    });
            
            $("input[name=\'buscar\']").click(function(){
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
        '; ?>


        <script type="text/javascript" src="../../libs/js/underscore.js"></script>
        <script type="text/javascript" src="../../libs/js/underscore.string.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>

    </head>
    <body>
        <form name="formulario" id="formulario" method="post">
            <div id="datosGral" class="x-hide-display" style="overflow: auto;">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
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
                                <input type="text" name="fecha" id="fecha" size="20" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
' readonly class="form-text" />
                                <!--button id="boton_fecha">...</button-->
                                <input type="text" name="fecha2" id="fecha2" size="20" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
' readonly class="form-text" />
                            </td>
                        </tr>
                        
                        <!--<tr>
                            <td class="label">Ordenar por</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="filtrado_por" id="filtrado_por" class="form-text">
                                    <!--option value="null">Seleccione un campo</option-->
                            <!--        <option value="REFERENCE">C&oacute;digo</option>
                                    <option value="NAME">Descripci&oacute;n</option>
                                </select>
                            </td>
                        </tr>-->
                        
                        <tr>
                          <!-- ESTADOS -->
                            <td class="label">ESTADOS</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="estados" id="estados" style="width:200px;" class="form-text">
                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_estado'],'output' => $this->_tpl_vars['option_output_estado']), $this);?>

                                
                                </select>
                            </td>
                            <td width="80px" style="width:80px" class="label">ESTABLECIMIENTO</td>
                             <!-- ESTABLECIMIENTO -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="punto" id="punto" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_punto'],'output' => $this->_tpl_vars['option_output_punto']), $this);?>

                                
                                </select>
                            </td>
                        <tr>
                         <td width="80px" style="width:80px" class="label">TIPOS DE ESTABLECIMIENTO</td>
                             <!-- TIPOS DE ESTABLECIMIENTO -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="tipo_punto" id="tipo_punto" style="width:200px;" class="form-text">
                                    <option value="">Todos</option>                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_tipo_punto'],'output' => $this->_tpl_vars['option_output_tipo_punto']), $this);?>

                                
                                </select>
                            </td>
                            <!-- CATEGORIA -->
                            <td class="label">CATEGORIA</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="categoria" id="categoria" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>
                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_categoria'],'output' => $this->_tpl_vars['option_output_categoria']), $this);?>

                                
                                </select>
                            </td>
                        </tr>
                           
                        </tr>
                        <tr>

                          <!-- SUB-CATEGORIA -->
                            <td class="label">SUB-CATEGORIA</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="sub_categoria" id="sub_categoria" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>
                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_subcategoria'],'output' => $this->_tpl_vars['option_output_subcategoria']), $this);?>

                                
                                </select>
                            </td>

                            <td width="100px" style="width:100px" class="label">PRODUCTO</td>
                             <!-- PRODUCTO -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="producto" id="producto" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_productos'],'output' => $this->_tpl_vars['option_output_productos']), $this);?>

                                
                                </select>
                            </td>
                           
                        </tr>

                        <tr>
                          <td width="100px" style="width:100px" class="label">ALMACENAMIENTO</td>
                             <!-- ALMACENAMIENTO -->
                            <td width="200px"  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="tipo_almacenamiento" id="tipo_almacenamiento" style="width:200px;" class="form-text">
                                    <option value="">Todos</option>                               
                                    <option value="SECO">SECO</option>
                                    <option value="FRIO">FRIO</option>
                                </select>
                            </td>
                        <!-- MARCA -->
                          <td class="label">MARCA</td>
                            <td width="200px" style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="combobox" id="combobox" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>
                                
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_marca'],'output' => $this->_tpl_vars['option_output_marca']), $this);?>

                                
                                </select>
                            </td>
                        </tr>
                        <tr>
                          <td width="100px" style="width:100px" class="label">INDICES</td>
                             <!-- Regulado -->
                            <td width="200px"  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="indices" id="indices" style="width:200px;" class="form-text">
                                    <option value="">Todos</option>                               
                                    <option value="regulado">Regulado</option>
                                    <option value="cestack_basica">Cesta Basica</option>
                                    <option value="bcv">BCV</option>
                                    <option value="sae">Sae</option>
                                </select>
                            </td>
                            <td class="label">C&Oacute;DIGO DE BARRAS</td>
                              <td >
                                <input type="text" name="codigo_barras" id="codigo_barras" style="float: left;" class="form-text" />
                                <input type="button" id="buscar" name="buscar" value="Buscar" style="float: left;" />
                              </td>
                        </tr>
                        
                        <!-- <tr>
                            <td class="label">Formato Reporte</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <div id="formato">
                                    <input type="radio" id="radio1" name="radio" value="0" checked/><label for="radio1">Hoja de C&aacute;lculo</label>
                                  <!--   <input type="radio" id="radio2" name="radio" value="1"  />
                                    <input type="radio" id="radio2" name="radio" value="1"  /><label for="radio2">Formato PDF</label>
                                </div>
                            </td>
                        </tr> -->
                        <tr align="center" class="tb-head">
                            <td colspan="4" align="center">
                              <input type="button" id="enviarajax" name="aceptar" value="Mostrar" align="right" />
                              <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
';" />
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
<?php /* Smarty version 2.6.21, created on 2017-08-25 10:51:29
         compiled from facturas_clientes_pyme.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'facturas_clientes_pyme.tpl', 371, false),array('function', 'html_options', 'facturas_clientes_pyme.tpl', 383, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Humberto Zapata" />
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
            /*Seccion nueva de CSS por Humberto Zapata*/
            /*div.fixedHeaderTable {
                position: relative;
            }
            div.fixedHeaderTable table {
                width:100%;
            }
            div.fixedHeaderTable tbody {
                height: 200px;
                overflow-y: auto;
                overflow-x: hidden;
            }
            div.fixedHeaderTable table th {
                background-color:#CCCCCC;font-weight:bold
            }
            div.fixedHeaderTable table td {
                background-color:#EEEEEE
            }
            div.fixedHeaderTable thead td, div.fixedHeaderTable thead th {
                position:relative;
            }*/
            /* IE7 hacks */
           /* div.fixedHeaderTable {
                *position: relative;
                *height: 200px;
                *overflow-y: scroll;
                *overflow-x: hidden;
                *padding-right:16px;
            }
            div.fixedHeaderTable thead tr {
                *position: relative;
                _position: absolute;
                *top: expression(this.offsetParent.scrollTop-2);
                *background:none;
                background-color:#FFFFFF
            }
            div.fixedHeaderTable tbody {
                *height: auto;
                *position:absolute;
                *top:50px;
            }*/
            /* IE6 hacks */
            /*div.fixedHeaderTable {
                _width:expression(this.offsetParent.clientWidth-20);
                _overflow: auto;
                _overflow-y: scroll;
                _overflow-x: hidden;
            }
            div.fixedHeaderTable thead tr {
                _position: relative
            }*/

            /*Segunda prueba*/
            /*@import url(http://fonts.googleapis.com/css?family=Roboto+Slab:300);
* {margin:0;padding:0; border: 0 none; position: relative;}
*, *:before, *:after {box-sizing: inherit;}
html{
  box-sizing: border-box;
  background: #0D757D;
  font-size: 1rem;
  color: #e6eff0;
  font-family: Roboto Slab;
}
h1 {
  font-weight: normal;
  font-variant: small-caps;
  text-align: center;
}
table * {height: auto; min-height: none;}*/ /* fixed ie9 & <*/
/*table {
  background: #15BFCC;
  table-layout: fixed;
  margin: 1rem auto;
  width: 98%;
  box-shadow: 0 0 4px 2px rgba(0,0,0,.4);
  border-collapse: collapse;
  border: 1px solid rgba(0,0,0,.5);
  border-top: 0 none;
}
thead {
  background: #FF7361;
  text-align: center;
  z-index: 2;
}
thead tr {
  padding-right: 17px;
  box-shadow: 0 4px 6px rgba(0,0,0,.6);
  z-index: 2;
}
thead th {
  border-right: 2px solid rgba(0,0,0,.2);
  padding: .7rem 0;
  font-size: 1.5rem;
  font-weight: normal;
  font-variant: small-caps;
}
tbody {
  display: block;
  height: calc(50vh - 1px);
  min-height: calc(200px + 1 px);*/
  /*use calc for fixed ie9 & <*/
  /*overflow-Y: scroll;
  color: #000;
}
tr {
  display: block;
  overflow: hidden;
}
tbody tr:nth-child(odd) {
  background: rgba(0,0,0,.2);
}
th, td {
  width: 21%;
  float:left;
}
td {
  padding: .5rem 0 .5rem 1rem;
  border-right: 2px solid rgba(0,0,0,.2);
}
td:nth-child(2n) {color: #fff;}
[data-campo=\'Mail\'] {
  width: 30%;
}  
th:last-child, td:last-child {
  width: 7%;
  text-align: center;
  border-right: 0 none;
  padding-left: 0;
}


@media only screen and (max-width:600px) {
  table {
    border-top: 1px solid ;
  }
  thead {display: none;}
  tbody {
    height: auto;
    max-height: 55vh;
  }
  tr {
    border-bottom: 2px solid rgba(0,0,0,.35);
  }
  tbody tr:nth-child(odd) {background: #15BFCC;}
  tbody tr:nth-child(even) {background:#FF7361;}
  td {
    display: block;
    width: 100%;
    min-width: 100%;
    padding: .4rem .5rem .4rem 40%;
    border-right: 0 none;
  }
  td:before {
    content: attr(data-campo);
    background: rgba(0,0,0,.1);
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: auto;
    min-width: 37%;
    padding-left: .5rem;
    font-family: monospace;
    font-size: 150%;
    font-variant: small-caps;
    line-height: 1.8;
  }
  tbody td:last-child {
    text-align: left;
    padding-left: 40%;
  }
  td:nth-child(even) {
    background: rgba(0,0,0,.2);
  }
}
a {color: #FF7361}*/
/*Fin de la segunda prueba*/

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

                //ajax para el reporte por html
                $("#enviarajax").click(function() {
                  /*Seleccion obligatoria del estado
                  hz*/
                  if(
                    $("#estados").val()=="" ||
                    $("#estados").val()=="0" 
                  ){
                    Ext.Msg.alert("Alerta","Debe Especificar el Estado.");
                    return false;
                  };

                  /*if(
                    $("#punto").val()=="" ||
                    $("#punto").val()=="0"
                  ){
                    Ext.Msg.alert("Alerta","Debe Especificar el Establecimiento.");
                    return false;
                  };*/

                punto=$("#punto").val();
                estados=$("#estados").val();
                desde=$("#fecha").val();
                hasta=$("#fecha2").val();
                desdeAux = desde.split("-");
                desde = desdeAux[2]+"-"+desdeAux[1]+"-"+desdeAux[0];
                hastaAux = hasta.split("-");
                hasta = hastaAux[2]+"-"+hastaAux[1]+"-"+hastaAux[0];
                unidades=$("#unidades").val();
                //producto=$("#producto").val();
                codigo_barras=$("#codigo_barras").val();
               
                    $.ajax({
                            type: \'GET\',
                            data: "opt=reporte_productoSobreLimite4&punto="+punto+"&estados="+estados+"&desde="+desde+"&hasta="+hasta,
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
            </script>
        '; ?>


        <script type="text/javascript" src="../../libs/js/underscore.js"></script>
        <script type="text/javascript" src="../../libs/js/underscore.string.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>

    </head>
    <body>
        <form name="formulario" id="formulario" method="post">
            <div id="datosGral" class="x-hide-display">
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
                            
                            <tr>
                              <!-- ESTADOS -->
                                <td class="label">ESTADOS</td>
                                <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                    <select name="estados" id="estados" style="width:200px;" class="form-text">
                                   
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_estado'],'output' => $this->_tpl_vars['option_output_estado']), $this);?>

                                    
                                    </select>
                                </td>
                                <td width="80px" style="width:80px" class="label">ESTABLECIMIENTOS</td>
                                 <!-- PUNTOS -->
                                <td  style="padding-top:2px; padding-bottom: 2px; ">
                                    <select name="punto" id="punto" style="width:200px;" class="form-text">
                                        <option value="0">Todos</option>                               
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_punto'],'output' => $this->_tpl_vars['option_output_punto']), $this);?>

                                    
                                    </select>
                                </td>
                            </tr>

                             
                            <tr class="tb-head">
                                <td colspan="6">
                                    <input type="button" id="enviarajax" name="aceptar" value="Mostrar" />
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
<?php /* Smarty version 2.6.21, created on 2017-08-08 23:52:42
         compiled from cotizacion_mercado_nuevo2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'cotizacion_mercado_nuevo2.tpl', 166, false),array('function', 'html_options', 'cotizacion_mercado_nuevo2.tpl', 195, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="author" content="HZ" />
        <title></title>
        
        <script type="text/javascript" src="../../libs/js/event_cotizacion_entrada2.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formCotizacion2.js"></script>


        <?php echo '

        <script language="JavaScript" type="text/JavaScript">//<![CDATA[

        function soloNumeros(e){
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 57)
        }

        function calcularMonto(precio2,precio3){
            
            precio2.value = parseFloat(precio3.value + (parseFloat(precio3.value) * 0.3));
            //precio2.value = redondear(precio.value,2);

        }

        function  calcular_todo()
        {
            var aux= document.getElementById(\'formulario2\');

            calcularMonto(aux.precio_1,aux.precio);
        }

        $(document).ready(function(){

            // Fecha smarty
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
                    $( "#fecha" ).datetimepicker("option", "minDate", selectedDate);
                }
            });
        });

        // function validaritem(){
        //     //return false;
        //     vcoditem = $("#nro_cotizacion").val();
        //     if(vcoditem!=\'\'){
        //         $.ajax({
        //             type: "GET",
        //             url:  "../../libs/php/ajax/ajax.php",
        //             data: "opt=ValidarCodigoCotizacion&v1="+vcoditem,
        //             beforeSend: function(){
        //                 $("#notificacion_codigo_cotizacion").html(MensajeEspera("<b>Veficando Cod. barras..<b>"));
        //             },
        //             success: function(data){
        //                 resultado = data;
        //                 if(resultado=="-1"){
        //                     //$("#cod_item").val("").focus();
        //                     $("#notificacion_codigo_cotizacion").html("<img align=\\"absmiddle\\" src=\\"../../libs/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"><b>Disculpe, este c&oacute;digo ya existe.</b></span><input type=\\"hidden\\" id=\\"nroresultado\\" value= \\"-1\\"/>");
        //                 }
        //                 else if(resultado=="1"){//cod de item disponble, originalmente sin "else"
        //                     $("#notificacion_codigo_cotizacion").html("<img align=\\"absmiddle\\" src=\\"../../libs/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> C&oacute;digo Disponible</b></span>");
        //                 }
        //             }
        //         });
        //     }
        // };

        </script>
        '; ?>

        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        <?php echo '
        <style type="text/css">
        .invisible
        {
        display: none;
        }
        </style>
        '; ?>


   
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">

            <input type="hidden" name="Datosproveedor" value=""/>
            <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
            <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
            <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
            <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
            <table style="width:100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:900px;">
                                        <span style="float:left;">
                                            <img src="<?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon"/>
                                            <?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>

                                        </span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <!--<Datos del proveedor y vendedor>-->
            <div id="dp" class="x-hide-display">
                <br/>
                <table>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Creado Por (*)</b></span>
                        </td>
                        <td>
                            <input type="text" maxlength="100" name="creado_por" id="creado_por" value="<?php echo $this->_tpl_vars['nombre_usuario']; ?>
" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                            <input type="hidden" name="estatus_cotizacion" id="estatus_cotizacion" value="3">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Observaciones</b></span>
                        </td>
                        <td>
                            <input type="text" name="observaciones" maxlength="100" id="observaciones" value="<?php echo $this->_tpl_vars['detalles_pendiente'][0]['observacion']; ?>
" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>N° de la Cotización</b></span>
                        </td>
                        <td>
                            <input type="test" name="nro_cotizacion" id="nro_cotizacion" maxlength="100" size="30" class="form-text" onchange="validaritem()" />
                            <div id="notificacion_codigo_cotizacion"></div>
                        </td>
                    </tr> -->
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha de Creación</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fechacotiza" id="input_fechacotiza" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' size="30" maxlength="70" class="form-text" readonly="readonly"/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha de Cotizaci&oacute;n</b></span>
                        </td>
                        <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                            <input type="date" name="fecha_cotizacion" step="1" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' class="form-text" />
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Validez de la Cotizaci&oacute;n</b></span>
                        </td>
                        <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                            <input type="date" name="validez_fecha" step="1" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' class="form-text" />
                        </td>
                    </tr>
                       
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Tipo de Transporte</b></span>
                        </td>
                        <td>
                            <select id="tipo_transporte" name="tipo_transporte" class="form-text" style="width:205px">
                                <option value="0"> Seleccionar Tipo de Transporte</option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_transporte'],'output' => $this->_tpl_vars['option_output_transporte_name']), $this);?>

                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Proveedor</b></span>
                        </td>
                        <td>
                            <select id="proveedores" name="proveedores" class="form-text" style="width:205px">
                                <option value="0"> Seleccione el Proveedor</option>
                                <!--<option value="1">Lista de Proveedores...</option>-->
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_proveedor'],'output' => $this->_tpl_vars['option_output_descripcion']), $this);?>

                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <!--</Datos del proveedor y vendedor>-->
            <div id="dcompra" class="x-hide-display"></div>
            <div id="PanelGeneralCompra">
                <div id="tabpago" class="x-hide-display">
                    <div id="contenedorTAB21">
                        <!-- TAB1 -->
                        <div class="tabpanel2">
                            <table></table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items"/>
            <div id="displaytotal" class="x-hide-display"></div>
            <div id="displaytotal2" class="x-hide-display"></div>
        </form>
        
    </body>
</html>
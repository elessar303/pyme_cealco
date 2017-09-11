<?php /* Smarty version 2.6.21, created on 2017-08-13 22:32:59
         compiled from rpt_movimiento_inventario.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'rpt_movimiento_inventario.tpl', 96, false),array('function', 'html_options', 'rpt_movimiento_inventario.tpl', 104, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes" />
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes2.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
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
                    dateFormat: "yy-mm-dd",
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                    }
                });
                $("#fecha2").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datepicker( "option", "maxDate", selectedDate );
                    }
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

        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>
    </head>
    <body>
        <form name="formulario" id="formulario" method="post">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_solo.tpl", 'smarty_include_vars' => array()));
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
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="3"/>
                <table style="width:100%; background-color:white;" align="center">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head" style="text-align:center;">
                                LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label">Per&iacute;odo **</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="fecha" id="fecha" size="20" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' readonly class="form-text" />
                                <!--button id="boton_fecha">...</button-->
                                <input type="text" name="fecha2" id="fecha2" size="20" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' readonly class="form-text" />
                            </td>
                            <td class="label">Tipos de Movimiento:</td>
                            <td style="padding-top:2px; padding-bottom: 2px;" align="left">
                                <select name="tipo_mov" id="tipo_mov" style="width:200px;" class="form-text">
                                    <option value="0">Todos...</option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_tipo_mov1'],'output' => $this->_tpl_vars['option_values_tipo_mov2']), $this);?>

                            </select>
                            </td>
                            
                        </tr>
                        <tr>
                       <td class="label">C&Oacute;DIGO DE BARRAS</td>
                              <td >
                                <input type="text" name="filtro_codigo" id="filtro_codigo" style="float: left;" class="form-text" />
                                <input type="button" id="buscar" name="buscar" value="Buscar" style="float: left;" />
                            </td>
                        <td class="label">Lote</td>
                              <td >
                                <input type="text" name="siga" id="siga" style="float: left;" class="form-text" />
                            </td>
                            <!-- 
                            <td class="label">Ordenar por</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="ordenado_por" id="ordenado_por" class="form-text">
                                
                                    <option value="cod_item">C&oacute;digo</option>
                                    <option value="descripcion1">Descripci&oacute;n</option>
                                </select>
                            </td>
                        </tr> -->
                        <!-- <tr>
                            <td class="label">Filtrar por</td>
                            -->
                            
                               <!--  <input type="text" name="proucto" id="producto" placeholder="Descripci&oacute;n del producto" title="Descripci&oacute;n del producto" style="width:200px;" class="form-text"/>
                                <input type="hidden" name="cod_producto" id="cod_producto" />
                                &nbsp;y/o&nbsp; -->
                                <!--select name="cliente" id="cliente" style="width:200px;" class="form-text">
                                    <option>Seleccione un cliente</option>
                                                            </select-->
                               <!--  <input type="text" name="cliente" id="cliente" placeholder="Nombre del cliente" title="Nombre del cliente" style="width:200px;" class="form-text"/>
                                <input type="hidden" name="cod_cliente" id="cod_cliente" />
                            </td>--> 
                        </tr> 
                        <tr>
                            <td class="label">Formato Reporte</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <div id="formato">
                                    <input type="radio" id="radio1" name="radio" value="0" /><label for="radio1">Hoja de C&aacute;lculo</label>
                                    <input type="radio" id="radio2" name="radio" value="1" checked /><label for="radio2">Formato PDF</label>
                                </div>
                            </td>
                        </tr>
                        <tr class="tb-tit">
                            <td colspan="6">
                                <input type="submit" id="aceptar" name="aceptar" value="Enviar" onclick="javascript:valida_envia('rpt_movimientos_inv.php','');" />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
<?php /* Smarty version 2.6.21, created on 2017-08-28 15:13:18
         compiled from rep_transmision_inventario.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'rep_transmision_inventario.tpl', 83, false),array('function', 'html_options', 'rep_transmision_inventario.tpl', 106, false),array('function', 'counter', 'rep_transmision_inventario.tpl', 145, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Junior Ayala" />
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </head>
    <?php echo '
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                $("#fecha1").datepicker({
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


                //funcion para cargar los puntos 
                  $("#estado").change(function() {
                    estados = $("#estado").val();
                        $.ajax({
                            type: \'GET\',
                            data: \'opt=getPuntos&\'+\'estados=\'+estados,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() {
                                $("#puntodeventa").find("option").remove();
                                $("#puntodeventa").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#puntodeventa").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#puntodeventa").append("<option value=\'0\'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#puntodeventa").append("<option value=\'" + this.vcampos[i].siga+ "\'>" + this.vcampos[i].nombre_punto + "</option>");
                                }
                            }
                        }); 
                        $("#puntodeventa").val(0);
                  });
            });
            //]]>
            </script>
        '; ?>

    <body>
        <form name="formulario" id="formulario" method="post">
        <div id="datosGral" class="x-hide-display">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <table style="width:100%; background-color:white;" cellpadding="1" cellspacing="1">
                <thead>
                    <tr>
                        <th colspan="6" class="tb-head" style="text-align:center;">
                        REPORTE DE TRANSMISIÓN DE VENTAS
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <!--<tr>
                        <td width="20%" align='right'>Fecha</td>
                           <td "paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                               <input type="text" name="fecha1" id="fecha1" size="20" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' readonly class="form-text" />
                               <!--button id="boton_fecha">...</button-->
                               <!--<input type="text" name="fecha2" id="fecha2" size="20" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' readonly class="form-text" />
                            </td>
                    </tr>-->
                    
                    <tr>
                                  
                        <td  width="20%" align='right' class="label">Tipo Sincronizaci&oacute;n:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left' colspan="3">
                        <select name="sincro" id="sincro" style="width:200px;" class="form-text">
                                    <option value="00"<?php if ($this->_tpl_vars['sincro'] == '00'): ?> selected="seleted"<?php endif; ?>>Todos</option>
                                    <option value="1" <?php if ($this->_tpl_vars['sincro'] == '1'): ?> selected="seleted"<?php endif; ?>>Autom&aacute;tico</option>
                                    <option value="2" <?php if ($this->_tpl_vars['sincro'] == '2'): ?> selected="seleted"<?php endif; ?>>Manual</option>
                                    <option value="3" <?php if ($this->_tpl_vars['sincro'] == '3'): ?> selected="seleted"<?php endif; ?>>Codigos Errados</option>
                                </select>
                        </td> 
                    </tr>    
                    <tr>    
                        <td  width="20%" align='right' class="label">Estado:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'> 
                                <select name="estado" id="estado" style="width:200px;" class="form-text">

                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_estado'],'output' => $this->_tpl_vars['option_values_nombre_estado'],'selected' => $this->_tpl_vars['estado']), $this);?>

                                </select>
                        </td>
                        <td width="80px" style="width:80px" class="label">Punto de Venta</td>
                             <!-- PUNTOS -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="puntodeventa" id="puntodeventa" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_punto'],'output' => $this->_tpl_vars['option_output_punto'],'selected' => $this->_tpl_vars['puntodeventa']), $this);?>

                                
                                </select>
                            </td>                     
                    </tr>
                    <tr class="tb-tit">
                    		<td colspan="3" align="center">Total Registrados=<?php echo $this->_tpl_vars['resultado']; ?>
 &nbsp;&nbsp; Total Reportaron=<?php echo $this->_tpl_vars['reportaron1']; ?>
 &nbsp;&nbsp; Porcentaje=<?php echo $this->_tpl_vars['porcentaje']; ?>
% &nbsp;&nbsp; *Pulse Sobre El Color Para Ver El Estatus</td>
                            <td colspan="4" align='center'>
                                <input type="submit" id="aceptar" name="aceptar" value="Mostrar"/>
                                <input type="submit" id="cancelar" name="cancelar" value="Limpiar"/>
                            </td>
                    </tr>
                </tbody>
        </table>
        </div>
        </form>
        <?php if ($this->_tpl_vars['aceptar'] == 'Mostrar'): ?>
        <table class="seleccionLista">
            <tr class="tb-head">
                <td>N&deg; <?php echo $this->_tpl_vars['resultado']; ?>
</td>
                <td>Estado</td>
                <td>Codigo Siga</td>
                <td>Punto de Venta</td>
                <td>Sincronizacion</td>
                <td>Fecha</td>
                <td>Reporto</td>
            </tr>
            <?php $this->assign('counter', '1'); ?>
            <?php $_from = $this->_tpl_vars['consulta']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['dato']):
        $this->_foreach['outer']['iteration']++;
?>
            <tr >
                
                <td align="center"><?php echo smarty_function_counter(array(), $this);?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['nombre_estado']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['codigo_siga']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['nombre_punto']; ?>
 <?php echo $this->_tpl_vars['dato']['version_pyme']; ?>
</td>
                <td align="center"><?php if ($this->_tpl_vars['dato']['tipo_sincro'] == '1'): ?>Automatico<?php else: ?>Manual<?php endif; ?></td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['fecha']; ?>
</td>
                <td align="center" TD BGCOLOR= <?php if ($this->_tpl_vars['dato']['color'] == 'verde'): ?>'#00FF00' title='Normal' <?php endif; ?><?php if ($this->_tpl_vars['dato']['color'] == 'amarillo'): ?>'#FFFF00' title='Ha Reportado Al Menos Una Vez Al Dia' <?php endif; ?><?php if ($this->_tpl_vars['dato']['color'] == 'rojo'): ?>'#FF0000' title='Mas De 12H Sin Reportar' <?php endif; ?><?php if ($this->_tpl_vars['dato']['color'] == 'negro'): ?>'#000000' title='Mas De 2 Dias Sin Reportar' <?php endif; ?>;> </td>
            </tr>
            <?php $this->assign('counter', $this->_tpl_vars['counter']++); ?>
            <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>
        </table>
    </body>
</html>  
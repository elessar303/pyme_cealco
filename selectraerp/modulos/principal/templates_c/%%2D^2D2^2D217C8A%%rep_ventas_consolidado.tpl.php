<?php /* Smarty version 2.6.21, created on 2016-05-03 10:06:38
         compiled from rep_ventas_consolidado.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'rep_ventas_consolidado.tpl', 59, false),array('modifier', 'string_format', 'rep_ventas_consolidado.tpl', 148, false),array('function', 'html_options', 'rep_ventas_consolidado.tpl', 90, false),array('function', 'counter', 'rep_ventas_consolidado.tpl', 140, false),)), $this); ?>
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
            });
            //]]>
            </script>
        '; ?>

    <body>
        <form name="formulario" id="formulario" method="post">
        <div id="datosGral" class="x-hide-display">
        <table style="width:100%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista">
                <thead>
                    <tr>
                        <th colspan="6" class="tb-head" style="text-align:center;">
                        LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td  width="20%" align='right'>Fecha Desde:**</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" width="30%">
                            <input type="text" name="fecha1" id="fecha1" size="15"
                            <?php if ($this->_tpl_vars['fecha1'] == ""): ?>
                            value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' readonly />
                            <?php else: ?>
                            value='<?php echo $this->_tpl_vars['fecha1']; ?>
' readonly />
                            <?php endif; ?>
                        </td>
 
                        <td  width="20%" align='right'>Categor&iacute;a:</td>
                         <td width="30%" align='left'>
                            <input type="text" name="categoria" id="categoria" size="20" value="<?php echo $this->_tpl_vars['categoria']; ?>
" />
                        </td>
                    </tr>
                    <tr>
                        <td  width="20%" align='right'>Fecha Hasta:**</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" width="30%">
                            <input type="text" name="fecha2" id="fecha2" size="15"
                            <?php if ($this->_tpl_vars['fecha2'] == ""): ?>
                            value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' readonly />
                            <?php else: ?>
                            value='<?php echo $this->_tpl_vars['fecha2']; ?>
' readonly />
                            <?php endif; ?>
                        </td>                      
                        <td  width="20%" align='right'>Producto:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                        <input type="text" name="producto" id="producto" size="20" value="<?php echo $this->_tpl_vars['producto']; ?>
"/>
                        </td> 
                    </tr>    
                    <tr>    
                        <td  width="20%" align='right'>Estado:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'> 
                                <select name="estado" id="estado" style="width:200px;">
                                    <option value="00">Todos</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_estado'],'output' => $this->_tpl_vars['option_values_nombre_estado']), $this);?>

                                </select>
                        </td>
                        <td  width="20%" align='right'>Punto de Venta:</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                            <input type="text" name="puntodeventa" id="puntodeventa" size="10" value="<?php echo $this->_tpl_vars['puntodeventa']; ?>
" /> (C&oacute;digo SIGA)
                        </td>                                    
                    </tr>
                    <tr>
                    <td  width="20%" align='right'>Beneficiario:
                        <select name="nac" id="nac" title="Inicial de Cedula" required="required">
                        <option value="V" <?php if ($this->_tpl_vars['nac'] == 'V'): ?>echo "selected";<?php endif; ?>>V</option>
                        <option value="E" <?php if ($this->_tpl_vars['nac'] == 'E'): ?>echo "selected";<?php endif; ?>>E</option>
                        <option value="P" <?php if ($this->_tpl_vars['nac'] == 'P'): ?>echo "selected";<?php endif; ?>>P</option>
                        </select>
                    </td>

                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                        <input type="text" name="cedula" id="cedula" size="20" value="<?php echo $this->_tpl_vars['cedula']; ?>
"/>
                        </td> 
                    </tr>
                    <tr class="tb-head">
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
                <td>N&deg;</td>
                <td>Estado</td>
                <td>Punto de Venta</td>
                <td>C&eacute;dula</td>
                <td>Beneficiario</td>
                <td>Fecha</td>
                <td>Categoria</td>
                <td>Producto</td>
                <td>Precio</td>
                <td>Cantidad</td>
                <td>Total</td>
            </tr>
            <?php $this->assign('counter', '1'); ?>
            <?php $_from = $this->_tpl_vars['consulta']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['dato']):
        $this->_foreach['outer']['iteration']++;
?>
            <tr >
                
                <td align="left"><?php echo smarty_function_counter(array(), $this);?>
</td>
                <td align="left"><?php echo $this->_tpl_vars['dato']['nombre_estado']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['nombre_punto']; ?>
</td>
                <td align="left"><?php echo $this->_tpl_vars['dato']['taxid']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['name_persona']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['datenew_ticketlines']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['descripcion']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['nombre_producto']; ?>
</td>
                <td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['dato']['price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['units']; ?>
</td>
                <td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['dato']['units']*$this->_tpl_vars['dato']['price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>

                
            </tr>
            <?php $this->assign('counter', $this->_tpl_vars['counter']++); ?>
            <?php endforeach; endif; unset($_from); ?>
	    <tr class="tb-head">
            <form name="formulario2" id="formulario2" method="post" action="rep_ventas_consolidado_exportar.php">
                <td hidden="hidden">
                <input type="text" name="cedula_nac" id="cedula_nac" value='"<?php echo $this->_tpl_vars['cedula_nac']; ?>
"'/>
                <input type="text" name="producto" id="producto" value='"<?php echo $this->_tpl_vars['producto']; ?>
"'/>
                <input type="text" name="categoria" id="categoria" value='"<?php echo $this->_tpl_vars['categoria']; ?>
"'/>
                <input type="text" name="puntodeventa" id="puntodeventa" value='"<?php echo $this->_tpl_vars['puntodeventa']; ?>
"'/>
                <input type="text" name="estado" id="estado" value='"<?php echo $this->_tpl_vars['estado']; ?>
"'/>
                <input type="text" name="fecha1" id="fecha1" value='"<?php echo $this->_tpl_vars['fecha1']; ?>
"'/>
                <input type="text" name="fecha2" id="fecha2" value='"<?php echo $this->_tpl_vars['fecha2']; ?>
"'/>

                </td>
                <td colspan="7" align='center'>
                <input type="submit" id="exportar" name="exportar" value="Exportar a Excel"/>
                </td>
            </tr>
        </form>
        </table>
        <?php endif; ?>
    </body>
</html>    
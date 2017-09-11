<?php /* Smarty version 2.6.21, created on 2017-08-28 11:07:31
         compiled from confg_punto_venta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'confg_punto_venta.tpl', 83, false),array('function', 'counter', 'confg_punto_venta.tpl', 143, false),)), $this); ?>
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
        <table style="width:100%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista">
                <thead>
                    <tr>
                        <th colspan="9" class="tb-head" style="text-align:center;">
                        LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>    
                        <td align='right' class="label" width="50px" style="width:50px">Estado:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='left'> 
                                <select name="estados" id="estados" style="width:200px;" class="form-text">
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_estado'],'output' => $this->_tpl_vars['option_values_nombre_estado']), $this);?>

                                </select>
                        </td>
                        <td align='right' class="label" width="50px" style="width:50px">Tipo:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='left'> 
                                <select name="tipo_punto" id="tipo_punto" style="width:200px;" class="form-text">
                                    <option value="">Todos</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_tipo'],'output' => $this->_tpl_vars['option_values_descripcion_tipo']), $this);?>

                                </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="50px" style="width:50px" class="label">Establecimiento:</td>
                             <!-- PUNTOS -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="punto" id="punto" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_punto'],'output' => $this->_tpl_vars['option_output_punto']), $this);?>

                                
                                </select>
                            </td>
                        <td align='right' class="label" width="50px" style="width:50px">Nombre:</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" align='left'>
                            <input type="text" name="nombrepunto" id="nombrepunto" size="10" value="<?php echo $this->_tpl_vars['nombrepunto']; ?>
" class="form-text"/> 
                        </td>
                        <?php if ($_GET['opt_menu'] != '7'): ?>
                        <td valign='middle'>
                        Agregar Nuevo
                        <a href="agregar_punto_venta.php" target="popup" onClick="window.open(this.href, this.target, 'width=900,height=250,top=200,left=300,scrollbars=NO,menubar=NO,titlebar= NO,status=NO,toolbar=NO'); return false;"><img valign='middle' src="../../imagenes/add.gif"></a>
                        </td>
                        <?php endif; ?>                                
                    </tr>
                    <tr class="tb-head">
                            <td colspan="5" align='center'>
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
                <td>Tipo de Punto</td>
                <td>Codigo SIGA</td>
                <td>Estado</td>
                <td>Punto de Venta</td>
                <td>IP</td>
                <td>Sincronizaci&oacute;n</td>
                <td>Status</td>
                <td>Acci&oacute;n</td>

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
                <td align="left"><?php echo $this->_tpl_vars['dato']['descripcion_tipo']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['codigo_siga_punto']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['nombre_estado']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['nombre_punto']; ?>
</td>
                <td align="center"><a href="http://<?php echo $this->_tpl_vars['dato']['ip_punto_venta']; ?>
/pyme/entrada/index.php" target="_blank"><?php echo $this->_tpl_vars['dato']['ip_punto_venta']; ?>
</a></td>
                <td align="center">
                <?php if ($this->_tpl_vars['dato']['tipo_sincro'] == '1'): ?>
                Automatico
                <?php elseif ($this->_tpl_vars['dato']['tipo_sincro'] == '2'): ?>
                Manual
                <?php endif; ?>
                </td>
                <td align="center">
                <?php if ($this->_tpl_vars['dato']['estatus'] == 'A'): ?>
                Activo
                <?php elseif ($this->_tpl_vars['dato']['estatus'] == 'I'): ?>
                Inactivo
                <?php endif; ?>
                </td>
                <?php if ($this->_tpl_vars['opcion_menu'] != '7'): ?>
                <td align="center">
                <a href="mod_punto_venta.php?punto=<?php echo $this->_tpl_vars['dato']['id']; ?>
" target="popup" onClick="window.open(this.href, this.target, 'width=700,height=250,top=200,left=300,scrollbars=NO,menubar=NO,titlebar= NO,status=NO,toolbar=NO'); return false;">Editar</a>
                </td>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['opcion_menu'] == '7'): ?>
                <td align="center">
                <a href="mod_punto_venta_merc.php?punto=<?php echo $this->_tpl_vars['dato']['id']; ?>
" target="popup" onClick="window.open(this.href, this.target, 'width=700,height=250,top=200,left=300,scrollbars=NO,menubar=NO,titlebar= NO,status=NO,toolbar=NO'); return false;">Editar</a>
                </td>
                <?php endif; ?>

            </tr>
            <?php $this->assign('counter', $this->_tpl_vars['counter']++); ?>
            <?php endforeach; endif; unset($_from); ?>
	    <tr class="tb-head">
            <form name="formulario2" id="formulario2" method="post" action="">
                <td hidden="hidden">
                <input type="sql" name="sql" id="sql" value='"<?php echo $this->_tpl_vars['sql']; ?>
"'/>
                </td>
                <td colspan="9" align='center'>
                <input type="submit" id="exportar" name="exportar" value="Exportar a Excel"/>
                </td>
            </tr>
        </form>
        </table>
        <?php endif; ?>
    </body>
</html>    
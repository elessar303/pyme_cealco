<?php /* Smarty version 2.6.21, created on 2015-06-30 10:19:05
         compiled from rpt_devolucion_diaria_ventas.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'rpt_devolucion_diaria_ventas.tpl', 34, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes" />
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </head>
    <body>
        <form name="formulario" id="formulario" method="post">
            <div id="datosGral" class="x-hide-display">
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <table style="width:100%">
                    <tbody>
                        <tr>
                            <td class="tb-tit">
                                <input name="imagen" id="imagen" type="hidden" value="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%; height:100px">
                    <tbody>
                        <tr>
                            <td colspan="6" class="tb-head" style="text-align:center">
                                LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                            </td>
                        </tr>
                        <tr>
                            <td style="width:170px">Fecha **</td>
                            <td>
                                <input type="text" name="fecha" id="fecha" size="20" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
" class="form-text" readonly />
                                <!--button id="boton_fecha">...</button-->
                                <?php echo '
                                    <script type="text/javascript">//<![CDATA[
                                        $("#fecha").datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            showOtherMonths:true,
                                            selectOtherMonths: true,
                                            numberOfMonths: 1,
                                            //yearRange: "-100:+100",
                                            dateFormat: "yy-mm-dd",
                                            showOn: "both"//button
                                        });
                                        /*var cal = Calendar.setup({
                                            onSelect: function(cal) { cal.hide() }
                                        });
                                        cal.manageFields("boton_fecha", "fecha", "%Y-%m-%d");*/
                                    //]]>
                                    </script>
                                '; ?>

                            </td>
                        </tr>
                        <tr class="tb-tit" style="text-align:right">
                            <td colspan="3" style="text-align:left">
                                <input type="radio" name="radio" value="0" /> Hoja de C&aacute;lculo
                                <input type="radio" name="radio" value="1" checked /> Formato PDF
                            </td>
                            <td colspan="3">
                                <input type="submit" id="aceptar" name="aceptar" value="Enviar" onclick="javascript:valida_envia('rpt_devolucion_diaria_ventas.php','');" class="form-text"/>
                                <input type="button" name="cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
';" value="Cancelar" class="form-text"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
<?php /* Smarty version 2.6.21, created on 2016-05-03 10:05:15
         compiled from seleccionarFecha7.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'seleccionarFecha7.tpl', 25, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <div id="datosGral">
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
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="6" class="tb-head" style="text-align:center;">LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label">Fecha **</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="fecha" id="fecha" size="20" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m") : smarty_modifier_date_format($_tmp, "%Y-%m")); ?>
' class="form-text" readonly />
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
                                                dateFormat: "yy-mm",
                                                showOn: "both"
                                            });
                                        /*var cal = Calendar.setup({
                                            onSelect: function(cal) { cal.hide() }
                                        });
                                        cal.manageFields("boton_fecha", "fecha", "%Y-%m");*/
                                    //]]>
                                    </script>
                                '; ?>

                            </td>
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
                            <!--td colspan="3" style="text-align:left">
                                <input type="radio" name="radio" value="0" /> Hoja de C&aacute;lculo
                                <input type="radio" name="radio" value="1" checked /> Formato PDF
                            </td-->
                            <td colspan="6">
                                <input type="submit" id="aceptar" name="aceptar" value="Enviar" onclick="valida_envia('rpt_librocompras_pdf.php','rpt_libroDeCompras.php');" />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=7';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
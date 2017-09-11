<?php /* Smarty version 2.6.21, created on 2017-01-23 19:56:51
         compiled from billetes_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'billetes_add.tpl', 71, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        <?php echo '
            <script type="text/javascript">
            //<![CDATA[
                $(document).ready(function(){
                    $("#descripcion_departamento").focus();
                    $("input[name=\'cancelar\']").button();
                    $("input[name=\'aceptar\']").button().click(
                        function(){
                            if($("#descripcion_departamento").val()==""){
                                //alert("Debe Ingresar la descripción del Departamento!.");
                                Ext.Msg.alert("Alerta", "Debe Ingresar la descripción del Departamento!");
                                $("#descripcion_departamento").focus();
                                return false;
                            }
                        }
                    );
                });
                //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="form-<?php echo $this->_tpl_vars['name_form']; ?>
" action="" method="post">
            <div id="datosGral">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <td colspan="4" class="tb-head">
                                &nbsp;
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="label">
                                Denominaci&oacute;n
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px">
                                <input type="text" name="denominacion"  id="denominacion" class="form-text" placeholder="Ejemplo: Bs."/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                               Valor
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px">
                                <input type="text" name="valor" size="60" id="valor" class="form-text" placeholder="Ejemplo: 100"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                               Estatus
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px">
                                <select name="estatus" id="estatus" class="form-text">
                                 <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_estatus'],'output' => $this->_tpl_vars['option_output_estatus'],'selected' => $this->_tpl_vars['option_selected_estatus']), $this);?>

                               </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
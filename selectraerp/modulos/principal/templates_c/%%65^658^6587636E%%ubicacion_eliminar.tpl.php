<?php /* Smarty version 2.6.21, created on 2014-02-10 14:20:13
         compiled from ubicacion_eliminar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'ubicacion_eliminar.tpl', 34, false),)), $this); ?>
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
                $("input[name=\'cancelar\'], input[name=\'eliminar\']").button();
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
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton_ubi.tpl", 'smarty_include_vars' => array()));
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
                <input type="hidden" name="id_almacen" value="<?php echo $_GET['cod']; ?>
"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head" style="text-align:center;">
                                ELIMINAR EL ALMAC&Eacute;N <?php echo ((is_array($_tmp=$this->_tpl_vars['datos_ubicacion'][0]['descripcion'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="label">
                                C&oacute;digo
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" readonly name="cod_almacen" value="<?php echo $this->_tpl_vars['datos_ubicacion'][0]['id']; ?>
" id="id_ubicacion" class="form-text"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                                Descripci&oacute;n
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="descripcion_ubicacion" readonly value="<?php echo $this->_tpl_vars['datos_ubicacion'][0]['descripcion']; ?>
" size="60" id="descripcion_ubicacion" class="form-text"/>
                            </td>
                        </tr>   
                        <tr>
                            <td colspan="3" class="label">
                                Puede vender
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input <?php if ($this->_tpl_vars['datos_ubicacion'][0]['puede_vender'] == '1'): ?> checked <?php endif; ?>  type="checkbox" name="puede_vender" value=1 id="puede_vender" class="form-text" readonly />
                            </td>
                        </tr>                     
                        
                    </tbody>
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="eliminar" id="aceptar" value="Eliminar Almac&eacute;n" />
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
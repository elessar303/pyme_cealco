<?php /* Smarty version 2.6.21, created on 2017-09-11 13:58:17
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'index.tpl', 18, false),)), $this); ?>
<?php echo $this->_tpl_vars['cambio']; ?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>.: SISCOL :.</title>
        <link rel="shortcut icon" href="../../../includes/imagenes/logo.ico" />
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </head>
    <body>
        <div id="Cabecera">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera_principal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
        <div id="Lateral">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
        <div id="Contenido">
            <div class="Relleno">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ((is_array($_tmp=@$this->_tpl_vars['archivotpl'])) ? $this->_run_mod_handler('default', true, $_tmp, "sin_informacion.tpl") : smarty_modifier_default($_tmp, "sin_informacion.tpl")), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php echo $this->_tpl_vars['msgAUsuario']; ?>

                <?php if ($this->_tpl_vars['msgAUsuario'] != ""): ?>
                    <?php echo '
                        <script type="text/javascript">//<![CDATA[
                            Ext.onReady(function() {
                                new Ext.Window({
                                    title: \'Notificaci&oacute;n de Transacci&oacute;n\',
                                    modal: true,
                                    autoHeight: true,
                                    width: 300,
                                    html: \''; ?>
<?php echo $this->_tpl_vars['msgAUsuario']; ?>
<?php echo '\'
                                }).show();
                            });
                            //]]>
                        </script>
                    '; ?>

                <?php endif; ?>
            </div>
        </div>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "foolter.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </body>
</html>
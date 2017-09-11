<?php /* Smarty version 2.6.21, created on 2017-09-11 13:58:15
         compiled from secciones_by_opt-menu.tpl */ ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes"/>
        <meta name="email" content="cjvrinf@gmail.com"/>
        <?php $this->assign('name_form', 'opciones'); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="form-<?php echo $this->_tpl_vars['name_form']; ?>
">
            <input name="imagen" id="imagen" type="hidden" value="<?php echo $this->_tpl_vars['cabeceraSeccionesByOptMenu'][0]['img_ruta']; ?>
"/>
            <div id="datosGral" class="x-hide-display">
                <table style="width: 100%">
                    <tr>
                        <td>
                            <div>
                                <table style="width: 100%">
                                    <tr>
                                        <td>
                                            <table style="width: 100%">
                                                <?php $this->assign('contarTD', 0); ?>
                                                <?php $this->assign('contarTR', 0); ?>
                                                <?php $this->assign('sw', 0); ?>
                                                <?php $_from = $this->_tpl_vars['seccionesByOptMenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['varArreglo']):
?>
                                                    <?php if ($this->_tpl_vars['varArreglo']['nom_menu'] <> ""): ?>
                                                        <?php if ($this->_tpl_vars['i']%5 == 0 && $this->_tpl_vars['contarTD'] == 0): ?>
                                                            <tr>
                                                            <?php endif; ?>
                                                            <td style="width: 20%">
                                                                <div class="box">
                                                                    <table width="150" height="90" border="0" cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $this->_tpl_vars['varArreglo']['cod_modulo']; ?>
'">
                                                                        <tr>
                                                                            <td>
                                                                                <div style="text-align:center">
                                                                                    <img width="45" height="45" src="<?php echo $this->_tpl_vars['varArreglo']['img_ruta']; ?>
" class="icon"/>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div style="text-align:center; color:#048; font: 1.0em Verdana, Arial, sans-serif; font-weight:normal;"><?php echo $this->_tpl_vars['varArreglo']['nom_menu']; ?>
</div>
                                                                                <!--div style="text-align:center; color:grey; font: 1.1em Verdana, Arial, sans-serif; font-weight:bold;" class="boton-text"><?php echo $this->_tpl_vars['varArreglo']['nom_menu']; ?>
</div-->
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                            <?php $this->assign('sw', 1); ?>
                                                            <?php $this->assign('contarTD', $this->_tpl_vars['contarTD']+1); ?>
                                                            <?php if ($this->_tpl_vars['contarTD']%5 == 0 && $this->_tpl_vars['i'] <> 0 || $this->_tpl_vars['contarTD']%5 == 0 && $this->_tpl_vars['i'] <> 5): ?>
                                                                <?php $this->assign('contarTD', 0); ?>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; endif; unset($_from); ?>
                                                <?php if ($this->_tpl_vars['contarTD'] <> 5 && $this->_tpl_vars['sw'] == 1): ?>
                                                </table>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>
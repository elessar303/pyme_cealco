<?php /* Smarty version 2.6.21, created on 2017-08-24 11:40:37
         compiled from tesoreria_bancoSeleccion.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'tesoreria_bancoSeleccion.tpl', 40, false),)), $this); ?>
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
    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="form-<?php echo $this->_tpl_vars['name_form']; ?>
" action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
" method="post">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <!--table style="width: 100%;">
                    <tr class="row-br">
                        <td>
                            <table class="tb-tit" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td width="900"><span style="float:left"><img src="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" />Seleccione el Banco de la transacción</span></td>
                                        <td width="75">
                                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                    <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                    <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                    <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="tb-head" style="width: 100%;">
                    <tr>
                        <td><input type="text" name="buscar" value="<?php echo $_POST['buscar']; ?>
<?php echo $_GET['des']; ?>
" size="20"/></td>
                        <td>
                            <select name="busqueda">
                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values'],'selected' => $this->_tpl_vars['option_selected'],'output' => $this->_tpl_vars['option_output']), $this);?>

            </select>
        </td>
        <td>
            <table style="cursor: pointer;" class="btn_bg" id="buscar">
                <tr>
                    <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                    <td class="btn_bg"><img src="../../../includes/imagenes/search.gif" width="16" height="16" /></td>
                    <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Buscar</td>
                    <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                </tr>
            </table>
        </td>
        <td>
            <table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'" class="btn_bg">
                <tr>
                    <td style="padding: 0px;" align="right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                    <td class="btn_bg"><img src="../../../includes/imagenes/list.gif" width="16" height="16" /></td>
                    <td class="btn_bg" nowrap style="padding: 0px 4px;">Mostrar todo</td>
                    <td style="padding: 0px;" align="left"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                </tr>
            </table></td>
        <td width="120"><input type="radio" name="palabra" value="exacta"/>Palabra exacta</td>
        <td width="140"><input type="radio" name="palabra" value="todas"/>Todas las palabras</td>
        <td width="150"><input checked type="radio" name="palabra" value="cualquiera"/>Cualquier palabra</td>
        <td colspan="3" width="386"></td>
    </tr>
</table-->
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/tb_head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <br/>
                <table class="seleccionLista" style="width: 100%;">
                    <tbody>
                        <tr class="tb-head" >
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td>
                                    <strong><?php echo $this->_tpl_vars['campos']; ?>
</strong>
                                </td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td style="text-align: center; width: 100px;"><strong>Opciones</strong></td>
                        </tr>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="3" style="text-align: center;"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                        <?php else: ?>
                            <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <?php if ($this->_tpl_vars['i']%2 == 0): ?>
                                    <?php $this->assign('color', ""); ?>
                                <?php else: ?>
                                    <?php $this->assign('color', "#cacacf"); ?>
                                <?php endif; ?>
                                <tr bgcolor="<?php echo $this->_tpl_vars['color']; ?>
">
                                    <td style="width: 100px; text-align: right; padding-right: 30px;"><?php echo $this->_tpl_vars['campos']['cod_banco']; ?>
</td>
                                    <td><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                                    <td style="text-align: center; width: 100px;">
                                        <img style="cursor: pointer;" class="seleccionChequeraActiva" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=verChequerasByBanco&cod=<?php echo $this->_tpl_vars['campos']['cod_banco']; ?>
'" title="Ver Chequeras" src="../../../includes/imagenes/add.gif"/>
                                    </td>
                                </tr>
                                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['cod_banco']); ?>
                            <?php endforeach; endif; unset($_from); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/navegacion_paginas.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </div>
        </form>
    </body>
</html>
<?php /* Smarty version 2.6.21, created on 2017-07-11 09:39:13
         compiled from devolucion_venta_lista.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'devolucion_venta_lista.tpl', 39, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $this->assign('nom_menu', $this->_tpl_vars['campo_seccion'][0]['nom_menu']); ?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_solo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/tb_head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head" >
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td><?php echo $this->_tpl_vars['campos']; ?>
</td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="2" style="text-align:center;">Opciones</td>
                        </tr>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr>
                                <td colspan="8"><?php echo $this->_tpl_vars['mensaje']; ?>
</td>
                            </tr>
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
                                    <td style="width:30px; text-align:right; padding-right:5px;"><?php echo $this->_tpl_vars['campos']['id_factura']; ?>
</td>
                                    <td style="width:100px; text-align:center;"><?php echo $this->_tpl_vars['campos']['cod_factura']; ?>
</td>
                                    <td style="padding-left:10px;"><?php echo $this->_tpl_vars['campos']['nombre']; ?>
</td>
                                    <td style="width:100px; text-align:center;"><?php echo $this->_tpl_vars['campos']['rif']; ?>
</td>
                                    <td style="width:100px; text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['fechaFactura'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td>
                                    <td style="width:100px; text-align:right; padding-right:10px;"><?php echo $this->_tpl_vars['campos']['totalizar_total_general']; ?>
</td>
                                    <td style="width:30px; text-align:center; cursor:pointer;"><img class="impresion" onclick="javascript:window.open('../../reportes/rpt_factura.php?codigo=<?php echo $this->_tpl_vars['campos']['cod_factura']; ?>
','');" title="Imprimir Factura" src="../../../includes/imagenes/ico_print.gif"/></td>
                                    <td style="width:30px; text-align:center; cursor:pointer;"><img class="anular" onclick="javascript:window.location.href = '?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=devolver_ps&codigo=<?php echo $this->_tpl_vars['campos']['cod_factura']; ?>
';" title="Anular Factura" src="../../../includes/imagenes/delete.png"/></td>
                                </tr>
                                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id_factura']); ?>
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
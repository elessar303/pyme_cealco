<?php /* Smarty version 2.6.21, created on 2017-09-05 14:06:56
         compiled from salida_almacen_pedido_facturar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'salida_almacen_pedido_facturar.tpl', 36, false),)), $this); ?>
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
        <script type="text/javascript" src="../../libs/js/entrada_almacen.js"></script>
        <script language="JavaScript" type="text/javascript" src="../../libs/js/funciones.js"></script>
    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="form-<?php echo $this->_tpl_vars['name_form']; ?>
" action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
" method="post">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_buscar_botones.tpl", 'smarty_include_vars' => array()));
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
                            <tr><td colspan="6"><?php echo $this->_tpl_vars['mensaje']; ?>
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
" style="cursor: pointer;" class="detalle">
                                    <td style="text-align: right; padding-right: 20px;"><?php echo $this->_tpl_vars['campos']['id_transaccion']; ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['nombre']; ?>
</td>
                                    <td style="text-align: center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['autorizado_por']; ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['observacion']; ?>
</td>
                                    <td style="text-align: center;">
                                    <?php if ($this->_tpl_vars['campos']['facturado'] == 0): ?><img src="../../../includes/imagenes/ico_cancel.gif"/><?php else: ?><img src="../../../includes/imagenes/ico_ok.gif"/>
                                    <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                    <?php if ($this->_tpl_vars['campos']['cod_movimiento'] == ''): ?><img src="../../../includes/imagenes/ico_cancel.gif"/><?php else: ?><img src="../../../includes/imagenes/ico_ok.gif"/>
                                    <?php endif; ?>
                                    </td>
                                    <td style="width:50px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif"/>
                                        <input type="hidden" name="id_transaccion" value="<?php echo $this->_tpl_vars['campos']['id_transaccion']; ?>
"/>
                                        <input type="hidden" name="id_tipo_movimiento_almacen" value="<?php echo $this->_tpl_vars['campos']['id_tipo_movimiento_almacen']; ?>
"/>
                                    </td>
                                     <td style="cursor:pointer; width:30px; text-align:center" colspan="2">
                                       <img style="cursor: pointer;" class="newfactura" onclick="javascript: window.open('pagos.php?despacho=<?php echo $this->_tpl_vars['campos']['id_transaccion']; ?>
','window','menubar=1,resizable=1,fullscreen=yes');" title="Nueva Factura Rapida" src="../../../includes/imagenes/factu.png"/>
                                </td>
                                </tr>
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
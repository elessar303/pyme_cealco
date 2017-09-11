<?php /* Smarty version 2.6.21, created on 2015-06-30 10:05:17
         compiled from relacion_pedidos_clientes.tpl */ ?>
<!DOCTYPE html>
<!--Creado por: -->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes" />
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
" method="post">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
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
                    <thead>
                        <tr class="tb-head" >
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campo']):
?>
                                <th><?php echo $this->_tpl_vars['campo']; ?>
</th>
                            <?php endforeach; endif; unset($_from); ?>
                            <th colspan="2" style="text-align:center;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="9" style="text-align:center; background-color:#f99696;"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                        <?php else: ?>
                            <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campo']):
?>
                                <?php if ($this->_tpl_vars['campo']['cod_estatus'] == 1): ?>
                                    <?php $this->assign('status', 'Pendiente'); ?>
                                    <?php $this->assign('color', "#f3ed8b"); ?><!--amarillo-->
                                <?php elseif ($this->_tpl_vars['campo']['cod_estatus'] == 2): ?>
                                    <?php $this->assign('status', 'Facturado'); ?>
                                    <?php $this->assign('color', "#a3fba3"); ?><!--verde-->
                                <?php else: ?>
                                    <?php $this->assign('status', 'Anulado'); ?>
                                    <?php $this->assign('color', "#f99696"); ?><!--rojo-->
                                <?php endif; ?>
                                <tr bgcolor="<?php echo $this->_tpl_vars['color']; ?>
">
                                    <td style="text-align: right; padding-right: 10px; width: 40px;"><?php echo $this->_tpl_vars['campo']['id_pedido']; ?>
</td>
                                    <td style="text-align: center;"><?php echo $this->_tpl_vars['campo']['cod_pedido']; ?>
</td>
                                    <td style="padding-left: 10px;"><?php echo $this->_tpl_vars['campo']['nombre']; ?>
</td>
                                    <td style="text-align: center;"><?php echo $this->_tpl_vars['campo']['rif']; ?>
</td>
                                    <td style="text-align: center;"><?php echo $this->_tpl_vars['campo']['fechaPedido']; ?>
</td>
                                    <td style="text-align: right; padding-right: 10px;"><?php echo $this->_tpl_vars['campo']['totalizar_total_general']; ?>
</td>
                                    <td style="text-align: center;"><?php echo $this->_tpl_vars['status']; ?>
</td>
                                    <td style="text-align: center; cursor: pointer; width: 30px;"><img class="impresion" onclick="javascript:window.open('../../reportes/rpt_pedido.php?codigo=<?php echo $this->_tpl_vars['campo']['cod_pedido']; ?>
', '')" title="Imprimir Pedido"  src="../../../includes/imagenes/ico_print.gif"/></td>
                                    <td style="text-align: center; cursor: pointer; width: 30px;">
                                        <?php if ($this->_tpl_vars['campo']['cod_estatus'] == 1): ?>
                                            <img class="anular" onclick="javascript: window.location.href = '?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=delete&amp;codigo=<?php echo $this->_tpl_vars['campo']['cod_pedido']; ?>
'" title="Anular Pedido" src="../../../includes/imagenes/cancel.gif"/>
                                        <?php elseif ($this->_tpl_vars['campo']['cod_estatus'] == 2): ?>
                                            <img title="Este pedido no puede ser anulado porque ha sido facturado" src="../../../includes/imagenes/ico_note.gif"/>
                                        <?php else: ?>
                                            <img title="Este pedido fue anulado" src="../../../includes/imagenes/delete.png"/>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campo']['id_pedido']); ?>
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
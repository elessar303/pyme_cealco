<?php /* Smarty version 2.6.21, created on 2017-08-18 01:05:48
         compiled from tomas_fisicas.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'tomas_fisicas.tpl', 36, false),)), $this); ?>
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
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/tb_head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td><?php echo $this->_tpl_vars['campos']; ?>
</td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="3" style="text-align:center;">Opciones</td>
                        </tr>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="3"><?php echo $this->_tpl_vars['mensaje']; ?>
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
                                    <td style="text-align: center; padding-right: 20px;"><?php echo $this->_tpl_vars['campos']['id_mov']; ?>
</td>
                                    <td style="text-align: center; padding-right: 20px;"><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                                    <td style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['fecha_apertura'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td>
                                    <?php if ($this->_tpl_vars['campos']['tipo_toma'] == 1): ?>
                                    <td style="padding-left: 20px; text-align:center;">Rapida</td>
                                    <?php else: ?>
                                    <td style="padding-left: 20px; text-align:center;">Completa</td>
                                    <?php endif; ?>
                                    <td style="padding-left: 20px; text-align:center;"><?php echo $this->_tpl_vars['campos']['nombreyapellido']; ?>
</td>
                                    
                                   <td style="cursor:pointer; width:30px; text-align:center">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;cod=<?php echo $this->_tpl_vars['campos']['id_mov']; ?>
&amp;editar=1&amp;opt_subseccion=add'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                                    </td>
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/toma_fisica_almacen.php?id=<?php echo $this->_tpl_vars['campos']['id_mov']; ?>
', '');" title="Imprimir Detalle de la toma" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>

                                     <?php if ($this->_tpl_vars['campos']['toma'] == 4 && $this->_tpl_vars['campos']['estatus_toma'] != 1): ?>
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;cod=<?php echo $this->_tpl_vars['campos']['id_mov']; ?>
&amp;editar=1&amp;opt_subseccion=ajuste'" title="Ajustar Inventario segun Toma Fisica" src="../../../includes/imagenes/ico_up.gif"/>
                                    </td>
                                    <?php endif; ?>

                                    <?php if ($this->_tpl_vars['campos']['estatus_toma'] == 1): ?>
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;cod=<?php echo $this->_tpl_vars['campos']['id_mov']; ?>
&amp;editar=2&amp;opt_subseccion=ajuste'" title="Ver Ajuste Realizado" src="../../../includes/imagenes/ico_search.gif"/>
                                    </td>
                                    <?php endif; ?>
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
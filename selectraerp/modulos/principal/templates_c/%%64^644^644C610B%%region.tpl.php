<?php /* Smarty version 2.6.21, created on 2017-08-18 01:03:54
         compiled from region.tpl */ ?>
<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como 
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snippet de código para obtener las bondades de la reutilización.
-->
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
                        <tr class="tb-head">
                        <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                            <td><b><?php echo $this->_tpl_vars['campos']; ?>
</b></td>
                        <?php endforeach; endif; unset($_from); ?>
                            <td colspan="3" style="text-align:center;"><b>Opciones</b></td>
                        </tr>
                    <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                        <tr><td colspan="3"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                    <?php else: ?>
                        <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                            <!--<?php if ($this->_tpl_vars['i']%2 == 0): ?><tr bgcolor=""><?php else: ?><tr bgcolor="#e1e1e1"><?php endif; ?>-->
                            <?php if ($this->_tpl_vars['i']%2 == 0): ?>
                                <?php $this->assign('bgcolor', ""); ?>
                            <?php else: ?>
                                <?php $this->assign('bgcolor', "#cacacf"); ?>
                            <?php endif; ?>
                        <tr bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
                            <td><?php echo $this->_tpl_vars['campos']['id']; ?>
</td>
                            <td><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>                           
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;id=<?php echo $this->_tpl_vars['campos']['id']; ?>
'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                            </td>
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="eliminar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=delete&amp;id=<?php echo $this->_tpl_vars['campos']['id']; ?>
'" title="Eliminar" src="../../../includes/imagenes/delete.gif"/> 
                            </td>
                            <td style="cursor: pointer; width: 30px; text-align:center">
                                <img class="eliminar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=listEst&amp;id=<?php echo $this->_tpl_vars['campos']['id']; ?>
'" title="Estados" src="../../../includes/imagenes/add.gif"/> 
                            </td>
                            
                        </tr>
                        <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id']); ?>
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
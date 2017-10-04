<?php /* Smarty version 2.6.21, created on 2017-10-04 17:47:40
         compiled from producto.tpl */ ?>
<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción (es):
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común para creación de cabeceras de los
    formularios.
2,_ Factorizacion y eliminación de codigo redundante así como separación
    de contenido y de presentación
Objetivos (es):
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho código en un snippet para aprovechar las bondades de la
    reutilización.
2._ Separar el contenido de su presentación para así tener código HTML correcto.
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
" name="formulario" action="" method="post">
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
                <thead>
                    <tr class="tb-head">
                        <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                        <th><?php echo $this->_tpl_vars['campos']; ?>
</th>
                        <?php endforeach; endif; unset($_from); ?>
                        <th colspan="4">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                    <tr><td colspan="10" style="text-align:center;"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                    <?php else: ?>
                    <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                    <?php if ($this->_tpl_vars['i'] % 2 == 0): ?>
                    <?php $this->assign('bgcolor', ""); ?>
                    <?php else: ?>
                    <?php $this->assign('bgcolor', "#cacacf"); ?>
                    <?php endif; ?>
                    <tr bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['cod_item']; ?>
</td>
                    <td style="text-align:right; padding-right:20px;"><?php echo $this->_tpl_vars['campos']['codigo_barras']; ?>
</td>
                    <td style="padding-left:10px;"><?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
 - <?php echo $this->_tpl_vars['campos']['marca']; ?>
 <?php echo $this->_tpl_vars['campos']['pesoxunidad']; ?>
<?php echo $this->_tpl_vars['campos']['nombre_unidad']; ?>
</td>
                    <td class="cantidades"><?php echo $this->_tpl_vars['campos']['precio1']; ?>
</td>
                    <td style="cursor:pointer; width:30px; text-align:center">
                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;cod=<?php echo $this->_tpl_vars['campos']['id_item']; ?>
'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                    </td>
                    <!--
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="eliminar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=delete&amp;cod=<?php echo $this->_tpl_vars['campos']['id_item']; ?>
'" title="Eliminar" src="../../../includes/imagenes/delete.gif"/>
                    </td>
                    -->
                    <td style="width: 30px; text-align:center">
                        <?php if (( $this->_tpl_vars['campos']['total_inventario'] > $this->_tpl_vars['campos']['existencia_min'] )): ?>
                        <img title="OK" src="../../../includes/imagenes/ico_ok.gif"/>
                        <?php else: ?>
                        <img title="Existencia Bajo M&iacute;nimo" src="../../../includes/imagenes/ico_note.gif"/>
                        <?php endif; ?>
                    </td>
                    <!-- boton donde se agregan los seriales -->
                      <?php if (( $this->_tpl_vars['campos']['seriales'] == 1 )): ?>
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="eliminar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=serial&amp;cod=<?php echo $this->_tpl_vars['campos']['id_item']; ?>
'" title="agregar serial" src="../../../includes/imagenes/add.gif"/>
                    </td>
                    <?php endif; ?>

                </tr>
                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id_item']); ?>
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
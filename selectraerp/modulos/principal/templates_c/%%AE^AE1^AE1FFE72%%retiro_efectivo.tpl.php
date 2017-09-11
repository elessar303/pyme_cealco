<?php /* Smarty version 2.6.21, created on 2017-06-12 17:43:45
         compiled from retiro_efectivo.tpl */ ?>
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
                        <th style="text-align:center;"><?php echo $this->_tpl_vars['campos']; ?>
</th>
                        <?php endforeach; endif; unset($_from); ?>
                    
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
                    <?php if ($this->_tpl_vars['campos']['NAME'] == null): ?>
                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['nombreyapellido']; ?>
</td>
                    <?php else: ?>
                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['NAME']; ?>
</td>
                    <?php endif; ?>
                    <td style="padding-left:10px; text-align:center;"><?php echo $this->_tpl_vars['campos']['fecha']; ?>
</td>
                    <?php if ($this->_tpl_vars['campos']['NAME'] == null): ?>
                    <td style="text-align:center; padding-right:20px;"><?php echo $this->_tpl_vars['campos']['cod_usuario']; ?>
</td>
                    <?php else: ?>
                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['ID']; ?>
</td>
                    <?php endif; ?>
                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['efectivo']; ?>
</td>
                    <!--<td style="cursor:pointer; width:30px; text-align:center">
                        <img class="editar" onclick="javascript: window.open('../../reportes/imprimir_arqueo_cajero.php?id=<?php echo $this->_tpl_vars['campos']['id']; ?>
');" title="Imprimir Copia Dep&oacute;sito" src="../../../includes/imagenes/ico_print.gif"/>
                    </td>-->    
                    <!--
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="eliminar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=delete&amp;cod=<?php echo $this->_tpl_vars['campos']['id_item']; ?>
'" title="Eliminar" src="../../../includes/imagenes/delete.gif"/>
                    </td> -->
                    
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
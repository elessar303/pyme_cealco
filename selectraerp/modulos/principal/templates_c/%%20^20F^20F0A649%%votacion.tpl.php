<?php /* Smarty version 2.6.21, created on 2017-08-28 10:00:27
         compiled from votacion.tpl */ ?>
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
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
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
                    <td style="text-align:left;"><?php echo $this->_tpl_vars['campos']['id']; ?>
</td>
                    <td style="text-align:center; font-size:11px"><?php echo $this->_tpl_vars['campos']['nacionalidad_emp']; ?>
<?php echo $this->_tpl_vars['campos']['cedula_emp']; ?>
</td>
                    <td style="text-align:center; font-size:11px"><?php echo $this->_tpl_vars['campos']['nombre_emp']; ?>
 <br><?php echo $this->_tpl_vars['campos']['apellido_emp']; ?>
</td>
                    <td class="cantidades" style="text-align:center; font-size:11px"><?php echo $this->_tpl_vars['campos']['gerencia']; ?>
</td>
                    <td class="cantidades" style="text-align:center; font-size:11px" width="130px"><?php echo $this->_tpl_vars['campos']['estado_centro']; ?>
</td>
                    <td class="cantidades" style="text-align:center; font-size:11px"><?php echo $this->_tpl_vars['campos']['miembro']; ?>
</td>
                    <td class="cantidades" style="text-align:center; font-size:11px" colspan="2" width="250px">
                    <?php if ($this->_tpl_vars['campos']['hora_vota'] != '0000-00-00 00:00:00'): ?>
                    <input type="text"value="<?php echo $this->_tpl_vars['campos']['hora_vota']; ?>
">
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['campos']['hora_vota'] == '0000-00-00 00:00:00'): ?>
                        Hora: <input type="number" name="hora" id="hora" size="3" min="1" max="12" value="<?php echo $this->_tpl_vars['campos']['hora_vota']; ?>
"> 
                        Min: <input type="number" name="min" id="mib" size="3" min="0" max="59" value="<?php echo $this->_tpl_vars['campos']['hora_vota']; ?>
"> 
                        <select name="ampm" style="text-align:center; font-size:11px">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                        </select></td>
                    <?php endif; ?>

                    <input type="text" name="id" id="id" value="<?php echo $this->_tpl_vars['campos']['id']; ?>
" hidden="hidden">
                    <?php if ($this->_tpl_vars['campos']['hora_vota'] == '0000-00-00 00:00:00' && $this->_tpl_vars['boton_form'] == 'SI'): ?>         
                    <td style="cursor:pointer; width:30px; text-align:center">
                        <input type="submit" name="enviar" value="Guardar">
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
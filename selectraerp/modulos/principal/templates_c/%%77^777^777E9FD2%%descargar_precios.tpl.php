<?php /* Smarty version 2.6.21, created on 2017-08-28 16:19:26
         compiled from descargar_precios.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substr', 'descargar_precios.tpl', 54, false),)), $this); ?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            
            <br/>
            <table class="seleccionLista">
                <thead>
                    <tr class="tb-head">
                        
                        <th colspan="11" align="center"><h3>Archivos</h3></th>
                        
                        <!--<th colspan="4">Opciones</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                    <tr><td colspan="10" style="text-align:center;"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                    <?php else: ?>
                    <?php $_from = $this->_tpl_vars['archivos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['archivo']):
?>
                    <?php if ($this->_tpl_vars['i'] % 2 == 0): ?>
                    <?php $this->assign('bgcolor', ""); ?>
                    <?php else: ?>
                    <?php $this->assign('bgcolor', "#cacacf"); ?>
                    <?php endif; ?>
                <tr bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
                    <!--<td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['codigo_barras']; ?>
</td>-->
                    <?php if ($this->_tpl_vars['archivo'] != '.' && $this->_tpl_vars['archivo'] != '..'): ?>
                    
                    <td style="text-align:center; font-size: large;" ><a style="display: block; width: 100%; height: 100%; " href="../../uploads/precios/<?php echo $this->_tpl_vars['archivo']; ?>
" download="<?php echo $this->_tpl_vars['archivo']; ?>
"><span style="color:#0c880c; text-transform: uppercase;"><?php echo $this->_tpl_vars['archivo']; ?>
</span></a></td>
                    
                    <!-- <td>
                        <?php echo ((is_array($_tmp=$this->_tpl_vars['archivo'])) ? $this->_run_mod_handler('substr', true, $_tmp, 13, -4) : substr($_tmp, 13, -4)); ?>

                    </td> -->

                    <!--<td style="padding-left:10px;"><?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
</td>-->
                    <!--<td class="cantidades"><?php echo $this->_tpl_vars['campos']['precio_sin_iva']; ?>
</td>-->

                    <!--<td style="cursor:pointer; width:30px; text-align:center">
                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;cod=<?php echo $this->_tpl_vars['campos']['id_item']; ?>
'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                    </td>-->
                    
                    <!--<td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="editar" onclick="" title="Procesar" src="../../../includes/imagenes/3.png"/>
                        
                    </td>-->
                    <!--Imprime el reporte de prductos actualizados/registrados HZ-->
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="impresion" onclick="javascript:window.open('../../reportes/reporte_variacion_precio.php?id_var_precio_cab=<?php echo ((is_array($_tmp=$this->_tpl_vars['archivo'])) ? $this->_run_mod_handler('substr', true, $_tmp, 13, -4) : substr($_tmp, 13, -4)); ?>
', '');" title="Imprimir Detalle de la Variaci&oacute;n" src="../../../includes/imagenes/ico_print.gif"/>
                    </td>
                    
                    <?php endif; ?>
                        
                    <!-- boton donde se agregan los seriales -->
                      

                </tr>
                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id_item']); ?>
                <?php endforeach; endif; unset($_from); ?>
                <?php endif; ?>
            </tbody>
        </table>
        <!--<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/navegacion_paginas.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>-->
    </div>
</form>
</body>
</html>
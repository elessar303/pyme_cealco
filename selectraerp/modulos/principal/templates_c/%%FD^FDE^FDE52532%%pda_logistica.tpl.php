<?php /* Smarty version 2.6.21, created on 2017-08-24 11:27:11
         compiled from pda_logistica.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'pda_logistica.tpl', 102, false),)), $this); ?>
<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snipper de código para obtener las bondades de la reutilización.
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
        <table class="navegacion" style="width: 100%;">
            <tr>
                <td>
                    <table class="tb-tit" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="float:left">
                                        <input name="imagen" id="imagen" type="hidden" value="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
"/>
                                    </span>
                                </td>
                                <td class="btn" style="float:right; padding-right: 15px;">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px;">Regresar</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                
                                </td>
                                <td class="btn" style="float:right">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=add&amp;tipo_log=balance'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px; width: 400px; height: 21px;"> Balance</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>

                                </td>
                                <td class="btn" style="float:right">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=add&amp;tipo_log=empaque'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 0px; width: 400px; height: 21px;"> Empaque</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 3px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td class="btn" style="float:right; margin-right: 20px;">
                                    <table class="btn_bg"  onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=transferencia&amp;tipo_log=transferencia'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 0px; width: 400px; height: 21px;"> Transferencia</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 3px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
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
                                <td><strong><?php echo $this->_tpl_vars['campos']; ?>
</strong></td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="2" style="text-align:center;"><strong>Opciones</strong></td>
                        </tr>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="6" style="text-align:center;"><?php echo $this->_tpl_vars['mensaje']; ?>
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
                                    <td style="text-align:center; width: 100px;"><?php echo $this->_tpl_vars['campos']['orden_compra']; ?>
</td>
                                    <td style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['fecha_planificacion'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>
</td>
                                    <td style="text-align:center; padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                                    <td style="cursor: pointer; width: 30px; text-align:center;">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;id=<?php echo $this->_tpl_vars['campos']['id']; ?>
'" title="Productos De La Orden" src="../../../includes/imagenes/edit.gif"/>
                                    </td>
                                    
                                        <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/pda_logistica.php?id_transaccion=<?php echo $this->_tpl_vars['campos']['id']; ?>
', '');" title="Imprimir Detalles De La Orden" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>
                                    
                                    <!--<td style="cursor: pointer; width: 30px; text-align:center;">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=newCompra&amp;cod=<?php echo $this->_tpl_vars['campos']['id_proveedor']; ?>
'" width="17" height="17" title="Generar Nueva Compra" src="../../../includes/imagenes/40.png"/>
                                    </td>-->
                                </tr>
                                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id_proevedor']); ?>
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
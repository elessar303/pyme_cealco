<?php /* Smarty version 2.6.21, created on 2017-08-24 11:40:44
         compiled from tesoreria_movimientos_bancarios.tpl */ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <?php echo '
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){
                    $("#buscar").click(function(){
                        $("form").submit();
                    });
                });
                function direccionar(url){
                    window.location.href=url;
                }
            //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form name="<?php echo $this->_tpl_vars['name_form']; ?>
" action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
" method="post">
            <table style="width: 100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="900"><span style="float:left"><img src="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" /><?php echo $this->_tpl_vars['campo_seccion'][0]['nom_menu']; ?>
</span></td>
                                    <td width="75">
                                    </td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
'">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <!--<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>-->
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/tb_head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <br/>
            <table class="seleccionLista" style="width:100%;">
                <tbody>
                    <tr class="tb-head" >
                        <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                            <td>
                                <strong><?php echo $this->_tpl_vars['campos']; ?>
</strong>
                            </td>
                        <?php endforeach; endif; unset($_from); ?>
                        <td>
                            <strong>Opciones</strong>
                        </td>
                    </tr>
                    <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">
                                <?php echo $this->_tpl_vars['mensaje']; ?>

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
                                <td style="text-align: center; width: 100px;"><?php echo $this->_tpl_vars['campos']['cod_banco']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                                <td style="text-align: center; width: 100px;">
                                    <img style="cursor: pointer;" class="verCuentas" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=viewmovimientosByBanco&amp;cod=<?php echo $this->_tpl_vars['campos']['cod_banco']; ?>
'" title="Ver Cuentas Asociadas" src="../../../includes/imagenes/ico_view.gif"/>
                                </td>
                            </tr>
                            <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['cod_banco']); ?>
                        <?php endforeach; endif; unset($_from); ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/navegacion_paginas.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </form>
    </body>
</html>
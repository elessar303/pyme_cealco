<?php /* Smarty version 2.6.21, created on 2016-09-12 15:19:34
         compiled from tesoreria_banco_cuentasSeleccioneParaCuenta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'tesoreria_banco_cuentasSeleccioneParaCuenta.tpl', 50, false),)), $this); ?>
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
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=<?php echo $_GET['opt_subseccion']; ?>
" method="post">
            <table style="width: 100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="900"><span style="float:left"><img src="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" />Seleccione La cuenta por la que emitira el cheque</span></td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'" name="buscar" border="0" cellpadding="0" cellspacing="0">
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
            <table class="tb-head" style="width: 100%;">
                <tr>
                    <td><input type="text" name="buscar" value="<?php echo $_POST['buscar']; ?>
<?php echo $_GET['des']; ?>
" size="20"/></td>
                    <td>
                        <select name="busqueda">
                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values'],'selected' => $this->_tpl_vars['option_selected'],'output' => $this->_tpl_vars['option_output']), $this);?>

                        </select>
                    </td>
                    <td>
                        <table style="cursor: pointer;" class="btn_bg" id="buscar">
                            <tr>
                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                <td class="btn_bg"><img src="../../../includes/imagenes/search.gif" width="16" height="16" /></td>
                                <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Buscar</td>
                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=<?php echo $_GET['opt_subseccion']; ?>
&cod=<?php echo $_GET['cod']; ?>
'" class="btn_bg" name="buscar" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                <td class="btn_bg"><img src="../../../includes/imagenes/list.gif" width="16" height="16" /></td>
                                <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Mostrar todo</td>
                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:120px;"><input type="radio" name="palabra" value="exacta" />Palabra exacta</td>
                    <td style="width:140px;"><input type="radio" name="palabra" value="todas" />Todas las palabras</td>
                    <td style="width:150px;"><input type="radio" name="palabra" value="cualquiera" checked/>Cualquier palabra</td>
                    <td style="width:386px;"></td>
                </tr>
            </table>
            <br/>
            <table class="seleccionLista" style="width: 100%;">
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
                        <td><strong>Opciones</strong></td>
                    </tr>
                    <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                        <tr><td colspan="5"><?php echo $this->_tpl_vars['mensaje']; ?>
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
                                <td style="text-align: right; padding-right: 20px;"><?php echo $this->_tpl_vars['campos']['cod_tesor_bandodet']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['campos']['nro_cuenta']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['campos']['tipo_cuenta']; ?>
</td>
                                <td style="width: 100px; text-align: center;">
                                    <img style="cursor: pointer;" width="16" height="16" class="varChequera"  onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=SeleccionlistaChequeraCuentaByBanco&cod=<?php echo $_GET['cod']; ?>
&cod_cuenta=<?php echo $this->_tpl_vars['campos']['cod_tesor_bandodet']; ?>
'" title="Chequera" src="../../../includes/imagenes/add.gif"/>
                                </td>
                            </tr>
                            <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['cod_tesor_bandodet']); ?>
                        <?php endforeach; endif; unset($_from); ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <table class="tb-head" style="width: 100%;">
                <tbody>
                    <tr>
                        <td><span>P&aacute;gina&nbsp;</span></td>
                        <td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=verChequerasByBanco&cod=<?php echo $_GET['cod']; ?>
&pagina=1&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../../includes/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" border="0"></a></td>
                        <td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=verChequerasByBanco&cod=<?php echo $_GET['cod']; ?>
&pagina=<?php echo $this->_tpl_vars['pagina']-1; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../../includes/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" border="0"></a></td>
                        <td><input type="text" name="numero_pagina" value="<?php echo $this->_tpl_vars['pagina']; ?>
" onblur="javascript: paginacion('?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=verChequerasByBanco&cod=<?php echo $_GET['cod']; ?>
',this.value,'&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
')" size="4"></td>
                        <td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=verChequerasByBanco&cod=<?php echo $_GET['cod']; ?>
&pagina=<?php echo $this->_tpl_vars['pagina']+1; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../../includes/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" border="0"></a></td>
                        <td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=verChequerasByBanco&cod=<?php echo $_GET['cod']; ?>
&pagina=<?php echo $this->_tpl_vars['num_paginas']; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../../includes/imagenes/last.gif" alt="Ultima" title="Ultima" width="16" height="16" border="0"></a></td>
                        <td colspan="14" style="width: 100%; text-align: center;">P&aacute;gina <?php echo $this->_tpl_vars['pagina']; ?>
 de <?php echo $this->_tpl_vars['num_paginas']; ?>
</td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>
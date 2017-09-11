<?php /* Smarty version 2.6.21, created on 2017-08-24 11:40:59
         compiled from tesoreria_bancoSeleccionTransf.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'tesoreria_bancoSeleccionTransf.tpl', 87, false),)), $this); ?>
<?php echo '
    <script type="text/javascript" language="JavaScript">
    $(document).ready(function(){
            $("#buscar").click(function(){
                    $("form").submit();
            });
    });
    function direccionar(url){
            window.location.href=url;
    }
    Ext.onReady(function(){
            var img=$("#imagen").val()
            var formpanel = new Ext.Panel({
                    title:\' <img src=\'+img+\' width="22" height="22" class="icon" /> Seleccione el Banco de la transacci&oacute;n\',
                    autoHeight: 600,
                    width: \'100%\',
                    collapsible: false,
                    titleCollapse: true ,
                    contentEl:\'datosGral\',
                    frame:true
            });
    /*
            formpanel3= new Ext.Panel({
                    title:\'Cálculo de Retencion de ISLR\',
                    autoHeight: 300,
                    width: \'100%\',
                    collapsible: true,
                    titleCollapse: true ,
                    contentEl:\'datosIslr\',
                    frame:true,
                    buttons: [
                    {
                            text:\'Guardar Fac./Nc.\',
                            handler: function(){
                            GuardarFactura();
                            }
                    }]
            });*/
            formpanel.render("banco");
    //         formpanel3.render("formulario");
    });
    </script>
'; ?>

<form name="<?php echo $this->_tpl_vars['name_form']; ?>
" id="<?php echo $this->_tpl_vars['name_form']; ?>
" action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
" method="post">
    <div id="datosGral" class="x-hide-display">
        <table width="100%">
            <!--tr class="row-br"-->
            <tr>
                <td>
                    <!--table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%"-->
                    <table cellspacing="0" cellpadding="1" border="0" width="100%">
                        <tbody>
                            <tr align="right">
                            <!--<td width="900"><span style="float:left"><img src="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" />Seleccione el Banco de la transacción</span></td>-->
                                <td width="75">
                                    <input name="imagen" id="imagen" type="hidden" value="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
">
                                    <!--     <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=add'" name="buscar" border="0" cellpadding="0" cellspacing="0">-->
                                    <!--        <tr>
                                            <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/add.gif" width="16" height="16" /></td>
                                            <td class="btn_bg" nowrap style="padding: 0px 4px;">Agregar</td>
                                            <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    </tr> -->
                                    <!-- </table>-->
                                </td>
                                <td width="75" align="right">
                                    <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                            <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                            <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <table class="tb-head" width="100%">
            <tr>
                <td><input type="text" name="buscar" value="<?php echo $_POST['buscar']; ?>
<?php echo $_GET['des']; ?>
" size="20"></td>
                <td>
                    <select name="busqueda">
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values'],'selected' => $this->_tpl_vars['option_selected'],'output' => $this->_tpl_vars['option_output']), $this);?>

                    </select>
                </td>
                <td>
                    <table style="cursor: pointer;" class="btn_bg" name="buscar" id="buscar"  border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                            <td class="btn_bg"><img src="../../libs/imagenes/search.gif" width="16" height="16" /></td>
                            <td class="btn_bg" nowrap style="padding: 0px 4px;">Buscar</td>
                            <td style="padding: 0px;" align="left"><img src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'" class="btn_bg" name="buscar" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                            <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
                            <td class="btn_bg" nowrap style="padding: 0px 4px;">Mostrar todo</td>
                            <td style="padding: 0px;" align="left"><img src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                        </tr>
                    </table>
                </td>
                <td width="120"><input checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
                <td width="140"><input checked="true" type="radio" name="palabra" value="todas">Todas las palabras</td>
                <td width="150"><input checked="true" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
                <td colspan="3" width="386"></td>
            </tr>
        </table>
        <br>
        <table width="100%" class="seleccionLista" cellspacing="0" border="0" cellpadding="1" align="center">
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
                    </td>
                </tr>
                <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                <td colspan="3">
                    <?php echo $this->_tpl_vars['mensaje']; ?>

                </td>
            <?php else: ?>
                <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                    <?php if ($this->_tpl_vars['i']%2 == 0): ?>
                        <tr bgcolor="">
                        <?php else: ?>
                        <tr  bgcolor="#e1e1e1">
                        <?php endif; ?>
                        <td ><?php echo $this->_tpl_vars['campos']['cod_banco']; ?>
</td>
                        <td><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                        <td>
                            <img style="cursor: pointer;" class="seleccionChequeraActiva"  onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=verCuentasPorBanco&cod=<?php echo $this->_tpl_vars['campos']['cod_banco']; ?>
'" title="ver Cuentas" src="../../libs/imagenes/add.gif">
                        </td>
                    </tr>
                    <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['cod_banco']); ?>
                <?php endforeach; endif; unset($_from); ?>
            <?php endif; ?>
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" class="tb-head" width="100%">
            <tbody>
                <tr>
                    <td><span>P&aacute;gina&nbsp;</span></td>
                    <td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&pagina=1&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../libs/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" border="0"></a></td>
                    <td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&pagina=<?php echo $this->_tpl_vars['pagina']-1; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../libs/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" border="0"></a></td>
                    <td><input type="text" name="numero_pagina" value="<?php echo $this->_tpl_vars['pagina']; ?>
" onblur="javascript: paginacion('?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
',this.value,'&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
')" size="4"></td>
                    <td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&pagina=<?php echo $this->_tpl_vars['pagina']+1; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../libs/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" border="0"></a></td>
                    <td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&pagina=<?php echo $this->_tpl_vars['num_paginas']; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../libs/imagenes/last.gif" alt="Ultima" title="Ultima" width="16" height="16" border="0"></a></td>
                    <td colspan="14" width="100%" align="center">P&aacute;gina <?php echo $this->_tpl_vars['pagina']; ?>
 de <?php echo $this->_tpl_vars['num_paginas']; ?>
</td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
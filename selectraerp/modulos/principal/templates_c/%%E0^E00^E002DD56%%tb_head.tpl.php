<?php /* Smarty version 2.6.21, created on 2017-09-11 13:22:24
         compiled from snippets/tb_head.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'snippets/tb_head.tpl', 13, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <table class="tb-head">
            <tr>
                <td><input id="inputbuscar" name="buscar" type="text" value="<?php echo $_POST['buscar']; ?>
<?php echo $_GET['des']; ?>
" size="20"/></td>
                <td>
                    <select name="busqueda">
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values'],'selected' => $this->_tpl_vars['option_selected'],'output' => $this->_tpl_vars['option_output']), $this);?>

                    </select>
                </td>
                <td>
                    <table style="cursor: pointer; padding: 0px;" class="btn_bg" id="buscar">
                        <tr>
                            <td><img src="../../../includes/imagenes/bt_left.gif" alt="" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                            <td class="btn_bg"><img src="../../../includes/imagenes/search.gif" width="16" height="16" /></td>
                            <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Buscar</td>
                            <td><img src="../../../includes/imagenes/bt_right.gif" alt="" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table style="cursor:pointer; padding:0px;" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'" class="btn_bg" >
                        <tr>
                            <td><img src="../../../includes/imagenes/bt_left.gif" alt="" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                            <td class="btn_bg"><img src="../../../includes/imagenes/list.gif" width="16" height="16" /></td>
                            <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Mostrar todo</td>
                            <td><img src="../../../includes/imagenes/bt_right.gif" alt="" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                        </tr>
                    </table>
                </td>
                <td style="width:120px;"><input type="radio" name="palabra" value="exacta" />Palabra exacta</td>
                <td style="width:150px;"><input type="radio" name="palabra" value="cualquiera"/>Cualquier palabra</td>
                <td style="width:140px;"><input type="radio" name="palabra" value="todas" checked/>Todas las palabras</td>
                <td style="width:386px;"></td>
            </tr>
        </table>
    </body>
</html>
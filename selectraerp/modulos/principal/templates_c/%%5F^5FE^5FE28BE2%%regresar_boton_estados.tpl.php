<?php /* Smarty version 2.6.21, created on 2014-05-14 16:17:27
         compiled from snippets/regresar_boton_estados.tpl */ ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <table class="navegacion">
            <tr>
                <td>
                    <table class="tb-tit" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="float:left">
                                        <input name="imagen" id="imagen" type="hidden" value="<?php if ($this->_tpl_vars['subseccion'][0]['img_ruta'] == null): ?><?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
<?php else: ?><?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
<?php endif; ?>" />
                                    </span>
                                </td>
                                <td class="btn">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=listEst&amp;id=<?php echo $_GET['id']; ?>
'">                                  
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px;">Regresar</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
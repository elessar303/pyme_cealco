<?php /* Smarty version 2.6.21, created on 2017-05-16 13:09:07
         compiled from caja_impresora_borrar.tpl */ ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $this->assign('name_form', 'usuarios_eliminar'); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!--Para estilo JQuery en botones-->
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/md5_crypt.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        <?php echo '
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){
                    $("input[name=\'cancelar\'], input[name=\'aceptar\']").button();//Coloca estilo JQuery
                });
            //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="" method="post">
            <div id="datosGral">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
                <input type="hidden" name="cod_usuario" value="<?php echo $_GET['cod']; ?>
"/>
                <div id="contenedorTAB">
                    <table style="width: 100%; background-color: white;">
                        <thead>
                            <tr>
                                <th colspan="4" class="tb-head">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="label">
                                    Nombre Caja **<input type='hidden' name='id' id='id' value='<?php echo $this->_tpl_vars['datos_usuarios'][0]['id']; ?>
' />
                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="nombre_caja" readonly value="<?php echo $this->_tpl_vars['datos_usuarios'][0]['caja_host']; ?>
" size="60" id="nombre_caja" class="form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    Serial **
                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="serial" value="<?php echo $this->_tpl_vars['datos_usuarios'][0]['serial_impresora']; ?>
" readonly="readonly" size="60" id="serial" class="form-text"/>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
                <table style="width:100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Eliminar">
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
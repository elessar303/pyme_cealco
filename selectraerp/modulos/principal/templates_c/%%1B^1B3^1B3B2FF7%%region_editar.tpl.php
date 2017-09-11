<?php /* Smarty version 2.6.21, created on 2017-08-18 01:03:50
         compiled from region_editar.tpl */ ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        <?php echo '
            <script type="text/javascript">
            //<![CDATA[
    $(document).ready(function(){
        $("#descripcion_region").focus();
        $("input[name=\'cancelar\']").button();
        $("input[name=\'aceptar\']").button().click(function(){
                if($("#descripcion_region").val()==""){
                    $("#descripcion_region").focus();
                    Ext.Msg.alert("Alerta","Debe Ingresar la descripción de la region!");
                    return false;
                }

        });
    });
            //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" method="post" action="">
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
                <input type="hidden" name="id_region" value="<?php echo $this->_tpl_vars['datos_region'][0]['id']; ?>
"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head" style="text-align:center;">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                         <tr>
                            <td colspan="3" class="label">
                                Descripci&oacute;n
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="descripcion_region" size="60" id="descripcion_region"value="<?php echo $this->_tpl_vars['datos_region'][0]['descripcion']; ?>
" class="form-text" />
                            </td>
                        </tr>
                                              
                       
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar Cambios"/>
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </form>
    </body>
</html>
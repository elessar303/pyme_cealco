<?php /* Smarty version 2.6.21, created on 2017-06-19 14:32:55
         compiled from cuenta_presupuesto_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'cuenta_presupuesto_add.tpl', 78, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $this->assign('name_form', 'usuarios_nuevo'); ?>
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
                $(document).ready(function()
                {
                    $("#cuenta").blur(function()
                    {
                        valor = $(this).val();
                        if(valor!=\'\')
                        {
                            $.ajax(
                            {
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=CuentaPresupuesto&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVUsuario").html(MensajeEspera("<b>Veficando tipo cuenta..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado==-1)
                                    {
                                        $("#cuenta").val("").focus();
                                        $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, esta cuenta ya existe.</b></span>");
                                    }
                                    if(resultado==1)
                                    {//cod de item disponble
                                        $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Nombre de tipo cuenta Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });
                });/*end of document.ready*/
            //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="" method="post">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <table style="width: 100%">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="label">Cuenta Presupuesto **</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="cuenta" placeholder="Cuenta" size="60" id="cuenta" class="form-text"/>
                                <div id='notificacionVUsuario'>
                                </div>

                            </td>
                        </tr>
                         <tr>
                            <td colspan="3" class="label">Tipo Cuenta **</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="tipo" id="tipo" class="form-text">
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_tipo_cuenta'],'output' => $this->_tpl_vars['option_output_tipo_cuenta'],'selected' => $this->_tpl_vars['option_selected_tipo_cuenta']), $this);?>

                               </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar" />
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
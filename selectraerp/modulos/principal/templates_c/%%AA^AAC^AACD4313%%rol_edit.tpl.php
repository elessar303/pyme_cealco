<?php /* Smarty version 2.6.21, created on 2017-05-16 13:02:04
         compiled from rol_edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'rol_edit.tpl', 99, false),)), $this); ?>
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
        <!--link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script-->
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>

        <?php echo '
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function(){
              
                $("input[name=\'aceptar\']").click(function(){
                    if($("#nombre").val()==""){
                        $("#nombre").focus();
                        Ext.Msg.alert("Alerta","Debe Ingresar el Nombre!");
                        $("#nombre").focus();
                        return false;
                    }
                    if($("#cedula").val()==""){
                        $("#cedula").focus();
                        Ext.Msg.alert("Alerta","Debe Ingresar la Cedula!");
                        $("#cedula").focus();
                        return false;
                    }
                    if($("#cargo").val()==""){
                        $("#cargo").focus();
                        Ext.Msg.alert("Alerta","Debe Ingresar el Cargo!");
                        $("#cargo").focus();
                        return false;
                    }
                    if($("#id_rol").val()==""){
                        $("#id_rol").focus();
                        Ext.Msg.alert("Alerta","Ya no se encuentran Casillas Disponibles, edite las ya existentes!");
                        $("#id_rol").focus();
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
                                Nombre y Apellido
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="nombre" size="60" id="nombre" class="form-text" value="<?php echo $this->_tpl_vars['campos'][0]['nombre_persona']; ?>
" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                                Cedula de Identidad
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="cedula" size="60" id="cedula" class="form-text" value="<?php echo $this->_tpl_vars['campos'][0]['cedula_persona']; ?>
"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                               Cargo
                            </td> 

                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="cargo" size="60" id="cargo" class="form-text" value="<?php echo $this->_tpl_vars['campos'][0]['nombre_persona']; ?>
"/>
                               
                            </td>
                        </tr>
                         <tr>
                            <td colspan="3" class="label">
                               Casilla
                            </td> 
                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <select name="id_rol" id="id_rol" class="form-text">
                               <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_output_region'],'output' => $this->_tpl_vars['option_values_region'],'selected' => $this->_tpl_vars['campos'][0]['descripcion']), $this);?>

                               </select>
                               
                            </td>
                         </tr>
                         
                      
                        
                    </tbody>
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
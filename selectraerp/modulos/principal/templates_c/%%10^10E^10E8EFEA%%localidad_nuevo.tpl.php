<?php /* Smarty version 2.6.21, created on 2016-09-21 18:17:02
         compiled from localidad_nuevo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'localidad_nuevo.tpl', 100, false),)), $this); ?>
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
                    if($("#descripcion_localidad").val()==""){
                        $("#descripcion_localidad").focus();
                        Ext.Msg.alert("Alerta","Debe Ingresar la descripción de la localidad!");
                        return false;
                    }
                });

                    // agregado el 22/01/14 para activar la region segun el estado q pertenece
                        function cargarRegion(idSelect,idEstado){
                            var paramentros="opt=cargarRegion&idEstado="+idEstado;
                            $.ajax({
                                type: "POST",
                                url: "../../libs/php/ajax/ajax.php",
                                data: paramentros,
                                beforeSend: function(datos){
                                    $("#"+idSelect).html(\'Cargando...\');
                                },
                                success: function(datos){
                                    $("#"+idSelect).html(datos);
                                },
                                error: function(datos,falla, otroobj){
                                    $("#"+idSelect).html(\' Error...\');
                                }
                            });
                    };
                   // fin de la funcion 
                   // llamada de la funcion para cargar region
                        id_estado=$("#id_estado_atiende").val();                        
                        cargarRegion("regionCot",id_estado);
                    // cuando cambia estado que atiende salta la funcion ajax
                       $("#id_estado_atiende").change(function(){
                              id_estado=$("#id_estado_atiende").val(); 
                              cargarRegion("regionCot",id_estado);
                       });
                   //fin de la llamada

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
                                Codigo SIGA
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="siga_localidad" size="60" id="siga_localidad" class="form-text" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                                Descripci&oacute;n
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="descripcion_localidad" size="60" id="descripcion_localidad" class="form-text" />
                            </td>
                        </tr>
                         <tr>
                            <td colspan="3" class="label">
                               Estado
                            </td> 

                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <select name="id_estado" id="id_estado" class="form-text">
                                 <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_output_region'],'output' => $this->_tpl_vars['option_values_region']), $this);?>

                               </select>
                               
                            </td>
                         </tr>
                         <tr>
                            <td colspan="3" class="label">
                               Estado que la atiende
                            </td> 

                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <select name="id_estado_atiende" id="id_estado_atiende" class="form-text">
                                 <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_output_region'],'output' => $this->_tpl_vars['option_values_region']), $this);?>

                               </select>
                               
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                               Region que pertenece 
                            </td> 

                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <div style="padding: 10px;background: #E0E0E0;width: 320px" id="regionCot"></div>
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
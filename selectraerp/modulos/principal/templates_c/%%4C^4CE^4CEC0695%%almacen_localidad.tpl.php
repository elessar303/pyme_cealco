<?php /* Smarty version 2.6.21, created on 2017-09-18 19:35:46
         compiled from almacen_localidad.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'almacen_localidad.tpl', 64, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
            
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        <title>Almacen</title>
      
        
        <?php echo '
<script language="JavaScript">
   $(document).ready(function(){
        function cargarAlmacen(idSelect,idLocalidad){
            var paramentros="opt=cargarAlmacen&idLocalidad="+idLocalidad;
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
    $(\'#localidad_almacen\').change(function(){        
       id_localidad=$("#localidad_almacen").val();
       cargarAlmacen("contAlmacen",id_localidad);
    });
     id_localidad=$("#localidad_almacen").val();
       cargarAlmacen("contAlmacen",id_localidad);
});
</script>
'; ?>

    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <div id="datosGral">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_solo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <input type="hidden" name="cant_fechas" value="2"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="6" class="tb-head" style="text-align:center;">ALMACENES </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label">Localidad</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <!--label for="fecha_desde">Desde</label-->
                                <select style="width: 290px;;" name="localidad_almacen" id="localidad_almacen" class="form-text">
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_localidad'],'output' => $this->_tpl_vars['option_output_localidad'],'selected' => $_GET['idLocalidad']), $this);?>

                                </select>
                            </td>
                        </tr>                      
                    </tbody>
                </table>
                <table style="width:100%; background-color: white;">
                  <tr>
                      <td><div style="margin-top: 20px" id="contAlmacen"></div></td>
                  </tr>
                </table>
            </div>
                        
        </form>
    </body>
</html>
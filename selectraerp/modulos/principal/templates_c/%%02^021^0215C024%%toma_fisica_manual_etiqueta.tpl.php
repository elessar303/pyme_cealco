<?php /* Smarty version 2.6.21, created on 2017-07-13 14:18:57
         compiled from toma_fisica_manual_etiqueta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'toma_fisica_manual_etiqueta.tpl', 71, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Lucas Sosa" />
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusionesFpdUbicacion.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                function cargarUbicaciones() {    
        idAlmacen=$("#almacen_entrada").val();     
        if(idAlmacen!=0){
        $.ajax({
            type: \'POST\',
            data: \'opt=cargaUbicacion&idAlmacen=\'+idAlmacen,
            url: \'../../libs/php/ajax/ajax.php\',
            beforeSend: function() {
                $("#ubicacion").find("option").remove();
                $("#ubicacion").append("<option value=\'\'>Cargando..</option>");
            },
            success: function(data) {
                $("#ubicacion").find("option").remove();
                this.vcampos = eval(data);
                 $("#ubicacion").append("<option value=\'\'>Seleccione..</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#ubicacion").append("<option value=\'"+this.vcampos[i].id+"\'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
        }//fin el if
        else{
             $("#ubicacion").find("option").remove();
             $("#ubicacion").append("<option value=\'\'>Seleccione..</option>");
            }
        }
        $("#almacen_entrada").change(function(){
            cargarUbicaciones();
        });
                
            });
            //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form name="formulario" id="formulario" method="post"  target="_blank" action="../../reportes/realizar_toma_fisica_etiqueta.php">
            <div id="datosGral" class="x-hide-display">
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
                <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
                <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
                <table style="width:100%; background-color:white;">
                    <thead>
                        <tr>
                            <th colspan="6" class="tb-head" style="text-align:center;">
                                LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>                      
                       
                <tr>
                    <td class="label">Almacen</td>
                    <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                    <select  name="almacen_entrada" id="almacen_entrada" class="form-text">
                     <option value="0">Seleccione</option>              
                        <?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['option_output_almacen'],'values' => $this->_tpl_vars['option_values_almacen']), $this);?>

                    </select>
                    </td>
                        </tr>
                         <tr>
                            <td class="label">Ubicacion</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="ubicacion" id="ubicacion" style="width:200px;" class="form-text">
                                    <option value="0">Seleccione...</option>              
                                <!--<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_ubi'],'output' => $this->_tpl_vars['option_values_nombre_ubi']), $this);?>
-->
                                
                            </select>
                               </td>
                        </tr>

                        <tr>
                            <td class="label">Formato Reporte</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <div id="formato">
                                  <!--   <input type="radio" id="radio1" name="radio" value="0" checked/><label for="radio1">Hoja de C&aacute;lculo</label> -->
                                   
                                    <input type="radio" id="radio2" name="radio" value="1" checked /><label for="radio2">Formato PDF</label>
                                </div>
                            </td>
                        </tr>
                        <tr class="tb-tit">
                            <td colspan="6">
                                <input type="submit" id="aceptar" name="aceptar" value="Enviar"  />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
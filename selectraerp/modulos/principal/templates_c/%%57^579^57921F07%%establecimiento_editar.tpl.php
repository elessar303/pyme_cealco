<?php /* Smarty version 2.6.21, created on 2017-08-25 13:43:15
         compiled from establecimiento_editar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'establecimiento_editar.tpl', 87, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
        <meta name="autor" content="Lucas Sosa" />
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
            <link href="../../libs/js/select2/dist/css/select2.min.css" rel="stylesheet" />
            <script src="../../libs/js/select2/dist/js/select2.min.js"></script>
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                $("#municipio").select2();
                $("#parroquia").select2();
                $("#ciudad").select2();

                $("#cliente").autocomplete({
                    source: "../../libs/php/ajax/autocomplete_cliente.php",
                    minLength: 3, // how many character when typing to display auto complete
                    select: function(e, ui) {//define select handler
                        $("#cod_cliente").val(ui.item.id);
                    }
                });
                $("#producto").autocomplete({
                    source: "../../libs/php/ajax/autocomplete_producto.php",
                    minLength: 3,
                    select: function(e, ui) {//define select handler
                        $("#cod_producto").val(ui.item.id);
                    }
                });
                $("#aceptar").click(function(){
                   archivo=$("#archivo_productos").val();                   
                   if(archivo==\'\'){
                        alert("El campo esta vacio!");
                        return false;
                   }    
                  tamano= archivo.length;   
                  principio=tamano - 5;                  
                  tipo=archivo.substring(principio,tamano); 
                  if(tipo!="_data"){
                     alert("Extencion no valida! El archivo debe terminar con _data");
                    return false;  
                   }                   
                });
            });
            //]]>
            </script>
            <script language="JavaScript"> 
                var nav4 = window.Event ? true : false; 
                function acceptNum(evt){  
                // NOTE: Backspace = 8, Enter = 13, \'0\' = 48, \'9\' = 57  
                var key = nav4 ? evt.which : evt.keyCode;  
                return (key <= 13 || (key >= 48 && key <= 57 || key==46)); 
                } 
            </script>
        '; ?>

    </head>
    <body>
        <form name="formulario" id="formulario" method="post" enctype="multipart/form-data">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="id_punto" value="<?php echo $this->_tpl_vars['datos_punto'][0]['id']; ?>
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
                            <th colspan="8" class="tb-head" style="text-align:center;">
                                AGREGAR PUNTO DE VENTA
                            </th>
                        </tr>
                    </thead>
                    <tbody>                        
                       
                        <tr>
                            <td class="label">C&oacute;digo Siga:</td>
                            <td colspan="2" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="codigo_siga" id="codigo_siga" maxlength="6" onKeyPress="return acceptNum(event)" value="<?php echo $this->_tpl_vars['datos_punto'][0]['codigo_siga_punto']; ?>
"></input>
                            </td>
                            <td class="label">Estado:</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="estados" id="estados" style="width:200px;" class="form-text">
                                    <option value="00" disabled="disabled" selected="selected">Seleccione</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_estado'],'output' => $this->_tpl_vars['option_values_nombre_estado'],'selected' => $this->_tpl_vars['option_selected_estados']), $this);?>

                                </select>
                            </td>

                            <td class="label">Ciudad:</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="ciudad" id="ciudad" style="width:200px;" class="form-text">
                                    <option value="00" disabled="disabled" selected="selected">Seleccione</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_ciudades'],'output' => $this->_tpl_vars['option_output_ciudades'],'selected' => $this->_tpl_vars['option_selected_ciudad']), $this);?>

                                </select>
                            </td>
                            
                        </tr>
                        <tr>
                            <td class="label">C&oacute;digo Sica:</td>
                            <td colspan="2" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="codigo_sica" id="codigo_sica" maxlength="6" onKeyPress="return acceptNum(event)" value="<?php echo $this->_tpl_vars['datos_punto'][0]['codigo_sica_punto']; ?>
"></input>
                            </td>
                            <td class="label">Municipio:</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="municipio" id="municipio" style="width:200px;" class="form-text select">
                                    <option value="0">Seleccione</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_municipio'],'output' => $this->_tpl_vars['option_output_municipio'],'selected' => $this->_tpl_vars['option_selected_municipio']), $this);?>

                                </select>
                            </td>
                            <td class="label">Parroquia:</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="parroquia" id="parroquia" style="width:200px;" class="form-text">
                                    <option value="0">Seleccione</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_parroquia'],'output' => $this->_tpl_vars['option_output_parroquia'],'selected' => $this->_tpl_vars['option_selected_parroquia']), $this);?>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Nombre Establecimiento:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="nombre_punto" id="nombre_punto" value="<?php echo $this->_tpl_vars['datos_punto'][0]['nombre_punto']; ?>
"></input>
                            </td>
                            <td class="label">Tipo de Servidor:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="tipo_servidor" id="tipo_servidor" style="width:150px;" class="form-text">
                                    <option disabled="disabled" selected="selected">Seleccione</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_tipo_servidor'],'output' => $this->_tpl_vars['option_values_nombre_servidor'],'selected' => $this->_tpl_vars['option_selected_tipo_servidor']), $this);?>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">IP:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="ip_punto" id="ip_punto" onKeyPress="return acceptNum(event)" value="<?php echo $this->_tpl_vars['datos_punto'][0]['ip_punto_venta']; ?>
"></input>
                            </td>
                            <td class="label">Tipo de Conexi&oacute;n:</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="tipo_conexion" id="tipo_conexion" style="width:150px;" class="form-text">
                                        <option disabled="disabled" selected="selected">Seleccione</option>
                                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_tipo_conexion'],'output' => $this->_tpl_vars['option_values_nombre_conexion'],'selected' => $this->_tpl_vars['option_selected_tipo_conexion']), $this);?>

                                    </select>
                            </td>
                            <td class="label">Nro de Conexi&oacute;n (Afiliaci&oacute;n):</td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input class="form-text" type="text" name="nro_conexion" id="nro_conexion" value="<?php echo $this->_tpl_vars['datos_punto'][0]['nro_conexion']; ?>
" style="width:150px;"></input>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Direcci&oacute;n:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="direccion" id="direccion" value="<?php echo $this->_tpl_vars['datos_punto'][0]['direccion_punto']; ?>
"></input>
                            </td>
                            <td class="label">Velocidad de Conexi&oacute;n:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="velocidad" id="velocidad" style="width:150px;" class="form-text">
                                    <option disabled="disabled" selected="selected">Seleccione</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_velocidad'],'output' => $this->_tpl_vars['option_values_nombre_velocidad'],'selected' => $this->_tpl_vars['option_selected_nombre_velocidad']), $this);?>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Capacidad Seco:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="seco" id="seco" onKeyPress="return acceptNum(event)" value="<?php echo $this->_tpl_vars['datos_punto'][0]['capacidad_seco']; ?>
"></input>
                            </td>
                            <td class="label">Proveedor de Conexi&oacute;n:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="proveedor_conexion" id="proveedor_conexion" style="width:150px;" class="form-text">
                                    <option disabled="disabled" selected="selected">Seleccione</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_proveedor_conexion'],'output' => $this->_tpl_vars['option_values_nombre_proveedor'],'selected' => $this->_tpl_vars['option_selected_nombre_proveedor']), $this);?>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Capacidad Frio:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="frio" id="frio" onKeyPress="return acceptNum(event)" value="<?php echo $this->_tpl_vars['datos_punto'][0]['capacidad_frio']; ?>
"></input>
                            </td>
                            <td class="label">Estatus:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="estatus" id="estatus" title="Sincronizacion" required="required"  style="width:150px;" class="form-text">
                                    <option disabled="disabled" selected="selected">Seleccione</option>
                                    <option value="A" <?php if ($this->_tpl_vars['datos_punto'][0]['estatus'] == 'A'): ?> selected <?php endif; ?>>Activo</option>
                                    <option value="I" <?php if ($this->_tpl_vars['datos_punto'][0]['estatus'] == 'I'): ?> selected <?php endif; ?>>Inactivo</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Nro Cajas:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="cajas" id="cajas" onKeyPress="return acceptNum(event)" value="<?php echo $this->_tpl_vars['datos_punto'][0]['numero_cajas']; ?>
"></input>
                            </td>
                            <td class="label">Sincronizaci&oacute;n:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="tipo_sincro" id="tipo_sincro" title="Sincronizacion" required="required"  style="width:150px;" class="form-text">
                                    <option disabled="disabled" selected="selected">Seleccione</option>
                                    <option value="1" <?php if ($this->_tpl_vars['datos_punto'][0]['tipo_sincro'] == '1'): ?> selected <?php endif; ?>>Autom&aacute;tico</option>
                                    <option value="2" <?php if ($this->_tpl_vars['datos_punto'][0]['tipo_sincro'] == '2'): ?> selected <?php endif; ?>>Manual</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        <td class="label">Cantidad Servidores:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                            <input class="form-text" type="text" name="servidores" id="servidores" onKeyPress="return acceptNum(event)" value="<?php echo $this->_tpl_vars['datos_punto'][0]['numero_servidores']; ?>
" style="width:150px;"></input>
                        </td>
                        <td class="label">Tipo de Establecimiento:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="tipo_punto" id="tipo_punto" style="width:150px;" class="form-text">
                                    <option disabled="disabled" selected="selected">Seleccione</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_tipo'],'output' => $this->_tpl_vars['option_values_descripcion_tipo'],'selected' => $this->_tpl_vars['option_selected_descripcion_tipo']), $this);?>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Lineas Afiliadas:</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                            <textarea class="form-text" name="lineas_afiliadas" id="lineas_afiliadas" style="width:250px;"><?php echo $this->_tpl_vars['datos_punto'][0]['lineas_afiliadas']; ?>
</textarea>
                        </td>
                        </tr>
                        <tr class="tb-tit">
                            <td colspan="8">
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
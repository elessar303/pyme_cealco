<?php /* Smarty version 2.6.21, created on 2017-09-18 18:32:36
         compiled from traslado_almacen_nuevo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'traslado_almacen_nuevo.tpl', 103, false),array('modifier', 'date_format', 'traslado_almacen_nuevo.tpl', 143, false),)), $this); ?>
<script type="text/javascript" src="../../libs/js/event_almacen_salida.js"></script>
<script type="text/javascript" src="../../libs/js/eventos_formAlmacen.js"></script>
<script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
<link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
<?php echo '
<script >
    $(document).ready(function(){
         function cargarUbicaciones() {    
        idAlmacen=$("#almacen_entrada").val();     
        $.ajax({
            type: \'POST\',
            data: \'opt=cargaUbicacion&idAlmacen=\'+idAlmacen,
            url: \'../../libs/php/ajax/ajax.php\',
            beforeSend: function() {
                $("#ubicacion_entrada").find("option").remove();
                $("#ubicacion_entrada").append("<option value=\'\'>Cargando..</option>");
            },
            success: function(data) {
                $("#ubicacion_entrada").find("option").remove();
                this.vcampos = eval(data);
                 $("#ubicacion_entrada").append("<option value=\'\'>Seleccione..</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#ubicacion_entrada").append("<option value=\'"+this.vcampos[i].id+"\'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
    }
$("#almacen_entrada").change(function(){
    cargarUbicaciones();
});
  cargarUbicaciones();
  
    });

function solonumeros(evt){
            // Backspace = 8, Enter = 13, ’0\' = 48, ’9\' = 57, ‘.’ = 46
            tecla = (document.all) ? e.keyCode : e.which;

             //Tecla de retroceso para borrar, siempre la permite
            if (tecla==8){
            return true;
            }
                
            // Patron de entrada, en este caso solo acepta numeros
            patron =/[0-9-.]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
            }
</script>
'; ?>

<form name="formulario" id="formulario" method="post" action="">
    <input type="hidden" name="Datosproveedor" value="">
    <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
    <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
    <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">
    <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
">
    <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td width="900"><span style="float:left"><img src="<?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" /><?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>
</span></td>
                            <td width="75">
                                <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                        <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                        <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                        <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <!--<Datos del proveedor y vendedor>-->
    <div id="dp" class="x-hide-display">
        <br>
        <table>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/28.png"-->
                    <span style="font-family:'Verdana';"><b>Elaborado Por (*):</b></span>
                </td>
                <td>
                    <input type="text" maxlength="70" name=" autorizado_por" id="autorizado_por" 
                    value="<?php echo $this->_tpl_vars['nombre_usuario']; ?>
" class="form-text" readonly>
                </td>
            </tr>
            <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Proveedor (*):</b></span>
                        </td>
                        <td>
                            <!--input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="<?php echo $this->_tpl_vars['detalles_pendiente'][0]['autorizado_por']; ?>
"/-->
                            <select name="id_proveedor" id="id_proveedor" class="form-text" style="width:205px">
                            <option value="">...</option>
                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_proveedor'],'output' => $this->_tpl_vars['option_output_proveedor']), $this);?>

                            </select>
                        </td>
                    </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/8.png"-->
                    <span style="font-family:'Verdana';"><b>Observaciones</b></span>
                </td>
                <td>
                    <input type="text" name="observaciones" maxlength="70"  id="observaciones" class="form-text">
                </td>
            </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/ico_user.gif"-->
                    <span style="font-family:'Verdana';"><b>Almacen de Entrada:</b></span>
                </td>
                <td>
                    <select  name="almacen_entrada" id="almacen_entrada" class="form-text">
                        <?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['option_output_almacen'],'values' => $this->_tpl_vars['option_values_almacen']), $this);?>

                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/ico_user.gif"-->
                    <span style="font-family:'Verdana';"><b>Ubicacion de Entrada:</b></span>
                </td>
                <td>
                    <select  name="ubicacion_entrada" id="ubicacion_entrada" class="form-text">
                       
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-family:'Verdana';"><b>Fecha:</b></span>
                </td>
                <td>                    
                    <input type="hidden" name="input_fechacompra" id="input_fechacompra" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
'>
                    <div class="form-text" style="color:#4e6a48" id="fechacompra"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</div>
                </td>
            </tr>
        </table>
    </div>
    <!--</Datos del proveedor y vendedor>-->
    <div id="dcompra" class="x-hide-display" >
    
    <div id="PanelGeneralCompra">
        <div id="tabproducto" class="x-hide-display">
            <div id="contenedorTAB">
                <div id="div_tab1">
                    <div class="grid">
                        <table width="100%" class="lista">
                            <thead>
                                <tr>
                                    <th class="tb-tit">Codigo</th>
                                    <th class="tb-tit">Descripcion</th>
                                    <th class="tb-tit">Cantidad</th>
                                    <th class="tb-tit">Opt</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="sf_admin_row_1">
                                    <td colspan="4">
                                        <div class="span_cantidad_items"><span style="font-size: 10px;">Cantidad de Items: 0</span></div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="tabpago" class="x-hide-display">
            <div id="contenedorTAB21">
                <!-- TAB1 -->
                <div class="tabpanel2">
                    <table>
                    </table>
                </div>
            </div>
        </div>
    </div></div>
    <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items">
    <input type="hidden" title="input_tiva" value="0" name="input_tiva" id="input_tiva">
    <input type="hidden" title="input_tsiniva" value="0" name="input_tsiniva" id="input_tsiniva">
    <input type="hidden" title="input_tciniva" value="0" name="input_tciniva" id="input_tciniva">
    <div id="displaytotal" class="x-hide-display"></div>
    <div id="displaytotal2" class="x-hide-display"></div>
</form>
<div id="incluirproducto" class="x-hide-display">
    <label>
        <p><b>Almacen de Salida</b></p>
        <p><select id="almacen" name="almacen"></select></p>
    </label>
      <label>
        <p><b>Ubicacion de Salida</b></p>
        <p><select id="ubicacion" name="ubicacion"></select></p>
    </label>
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra">
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>
    <label>        
        <p><b>Productos</b></p>
        <p><input type="hidden" name="items" id="items">
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly>
        <!--    <select style="width:100%" id="items" name="items"></select>-->
        </p>
    </label>
    <label>
        <p><b>Lote</b></p>
        <p><input type="text" name="nlote" id="nlote" onkeypress="return solonumeros(event)"></p>
    </label>
    <label>
        <p><b>Cantidad Unitaria</b></p>
        <p><input type="text" name="cantidadunitaria" id="cantidadunitaria" onkeypress="return solonumeros(event)"></p>
    </label>
    <label>
        <p><b>Cantidad Existente en la Ubicacion</b></p>
        <p><input type="text" name="cantidad_existente" id="cantidad_existente" readonly ></p>
    </label>
</div>
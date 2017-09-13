<?php /* Smarty version 2.6.21, created on 2017-09-13 19:37:51
         compiled from entrada_almacen_nuevo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'entrada_almacen_nuevo.tpl', 159, false),array('modifier', 'date_format', 'entrada_almacen_nuevo.tpl', 186, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formAlmacen.js"></script>
         <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
        function comprobarconductor() {
        var consulta;     
        consulta = $("#nacionalidad_conductor").val()+$("#cedula_conductor").val();                      
                        $.ajax({
                              type: "POST",
                              url: "comprobar_conductor.php",
                              data: "b="+consulta,
                              dataType: "html",
                              asynchronous: false, 
                              error: function(){
                                    alert("error petici�n ajax");
                              },
                              success: function(data){  

                                $("#resultado").html(data);
                                document.getElementById("conductor").focus();
                                ///// verificamos su estado

                              }
                  });

        }
         

        function comprobarfechavencimiento() {
        var consulta;     
        consulta = $("#items").val();                      
                        $.ajax({
                              type: "POST",
                              url: "comprobar_fecha_vencimiento.php",
                              data: "b="+consulta,
                              dataType: "html",
                              asynchronous: false, 
                              error: function(){
                                    alert("error petici�n ajax");
                              },
                              success: function(data){  

                                $("#resultado2").html(data);
                                document.getElementById("fecha_vencimiento").focus();
                                ///// verificamos su estado

                              }
                  });

        }

        function soloNumeros(e){
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 57)
        }

        $(document).ready(function(){
        //funcion para cargar los puntos 
                  $("#estado").change(function() {
                    estados = $("#estado").val();
                        $.ajax({
                            type: \'GET\',
                            data: \'opt=getPuntos&\'+\'estados=\'+estados,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() {
                                $("#puntodeventa").find("option").remove();
                                $("#puntodeventa").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#puntodeventa").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#puntodeventa").append("<option value=\'0\'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#puntodeventa").append("<option value=\'" + this.vcampos[i].siga+ "\'>" + this.vcampos[i].nombre_punto + "</option>");
                                }
                            }
                        }); 
                        $("#puntodeventa").val(0);
                  });
        });

        </script>
        '; ?>

        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        <?php echo '
        <style type="text/css">
        .invisible
        {
        display: none;
        }
        </style>
        '; ?>

   
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="Datosproveedor" value=""/>
            <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
            <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
            <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
            <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
            <table style="width:100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:900px;">
                                        <span style="float:left;">
                                            <img src="<?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon"/>
                                            <?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>

                                        </span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
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
                <br/>
                <table>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Elaborado Por:</b></span>
                        </td>
                        <td>
                            <!--input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="<?php echo $this->_tpl_vars['detalles_pendiente'][0]['autorizado_por']; ?>
"/-->
                            <input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="<?php echo $this->_tpl_vars['nombre_usuario']; ?>
" size="30" maxlength="70" class="form-text" readonly="readonly"/>
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
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Nro de Documento (*):</b></span>
                        </td>
                        <td>
                            <input type="text" name="nro_documento" maxlength="100" id="nro_documento" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Observaciones:</b></span>
                        </td>
                        <td>
                            <input type="text" name="observaciones" maxlength="100" id="observaciones" value="<?php echo $this->_tpl_vars['detalles_pendiente'][0]['observacion']; ?>
" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Entrada:</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fechacompra" id="input_fechacompra" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' size="30" maxlength="70" class="form-text" readonly="readonly"/>
                            <!--div style="color:#4e6a48" id="fechacompra"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</div-->
                            <?php echo '
                                <script type="text/javascript">//<![CDATA[
                                //comentado el 4/02/15 no me parece correcto que se ponga cuaquier fecha. daniel
                                    // var cal = Calendar.setup({
                                    //     onSelect: function(cal) {
                                    //         cal.hide();
                                    //     }
                                    // });
                                    // cal.manageFields("input_fechacompra", "input_fechacompra", "%Y-%m-%d");
                                //]]>
                                </script>
                            '; ?>

                        </td>
                    </tr>

                    <!--Seccion nueva para ingresar el punto de venta del que se enviaron los productos-->
                    <tr>    
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Estado de Procedencia (*):</b></span>
                        </td>
                            <!--ESTADOS-->
                        <td> 
                                <select name="estado" id="estado" class="form-text" style="width:205px">
                                    <option value="9999">Todos</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_estado'],'output' => $this->_tpl_vars['option_values_nombre_estado'],'selected' => $this->_tpl_vars['estado']), $this);?>

                                </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Punto de Procedencia (*):</b></span>
                        </td>
                             <!-- PUNTOS -->
                            <td>
                                <select name="puntodeventa" id="puntodeventa" class="form-text" style="width:205px">
                                    <option value="0">Todos</option>                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_punto'],'output' => $this->_tpl_vars['option_output_punto'],'selected' => $this->_tpl_vars['puntodeventa']), $this);?>

                                
                                </select>
                            </td>                     
                    </tr>
                    <!--Cierre de la Seccion nueva-->

                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Empresa Transporte (*):</b></span>
                        </td>
                        <td>
                            <input type="text" name="empresa_transporte" maxlength="100" id="empresa_transporte" size="30" maxlength="70" class="form-text" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>C&eacute;dula del Conductor (*):</b></span>
                        </td>
                        <td>
                            <select name="nacionalidad_conductor" id="nacionalidad_conductor" class="form-text">
                              <option value="">..</option>
                              <option value="V">V</option>
                              <option value="E">E</option>
                            </select>
                            <input type="text" name="cedula_conductor" maxlength="8" id="cedula_conductor" size="21"  class="form-text" onBlur="comprobarconductor(this.id)" onKeyPress="return soloNumeros(event)"/>
                        </td>
                    </tr>
                    <tr>
                    <td style="font-family:'Verdana';font-weight:bold;">
                    <span style="font-family:Verdana"><b>Nombre del Conductor (*):</b></span>
                    </td>
                    <td>
                    <div id="resultado" style="font-family:'Verdana';font-weight:bold;">
                    
                    </div>
                    </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Placa (*):</b></span>
                        </td>
                        <td>
                            <input type="text" name="placa" maxlength="100" id="placa" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Nro Gu&iacute;a SUNAGRO (*):</b></span>
                        </td>
                        <td>
                            <input type="text" name="codigo_sica" id="codigo_sica" maxlength="100" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Orden Despacho Vehicular (*):</b></span>
                        </td>
                        <td>
                            <input type="text" name="orden_despacho" id="orden_despacho" maxlength="100" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Nro de Contenedor (*):</b></span>
                        </td>
                        <td>
                            <input type="text" name="nro_contenedor" id="nro_contenedor" maxlength="100" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <!-- Firmas Casillas-->
                    <tr>
                        <td colspan="2" align="center"><span style="font-family:'Verdana';font-weight:bold;"><b>CASILLA DE FIRMAS:</b></span></td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Aprobado Por:</b></span>
                        </td>
                        <td>
                            <select name="id_aprobado" id="id_aprobado" class="form-text" style="width:205px">                        
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_aprobado'],'output' => $this->_tpl_vars['option_output_aprobado']), $this);?>

                                
                                </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Receptor:</b></span>
                        </td>
                        <td>
                            <select name="id_receptor" id="id_receptor" class="form-text" style="width:205px">                        
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_receptor'],'output' => $this->_tpl_vars['option_output_receptor']), $this);?>

                                
                                </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Seguridad:</b></span>
                        </td>
                        <td>
                           <select name="id_seguridad" id="id_seguridad" class="form-text" style="width:205px">                        
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_seguridad'],'output' => $this->_tpl_vars['option_output_seguridad']), $this);?>

                                
                                </select>
                        </td>
                    </tr>
                    <!--
                    Codigo fuente añadido para cubrir la funcionalidad de registrar los
                    datos de una compra: fecha, nro. de factura y nro de control. Esto
                    después de haber realizado el registro de la compra que ha quedado
                    pendiente por entregar.
                    -->
                    <?php if ($this->_tpl_vars['cod'] != ""): ?>
                        <!--Implica que se hará la entrada al inventario de una compra pendiente-->
                        <tr>
                            <td>
                                <span style="font-family:'Verdana';"><b>Nro. Factura (*)</b></span>
                            </td>
                            <td>
                                <input type="text" name="nro_factura" maxlength="70" id="nro_factura" value="<?php echo $this->_tpl_vars['datos_factura'][0]['num_factura_compra']; ?>
" size="30" maxlength="70" class="form-text"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-family:'Verdana';"><b>Nro. Control (*)</b></span>
                            </td>
                            <td>
                                <input type="text" name="nro_control" maxlength="70" id="nro_control" value="<?php echo $this->_tpl_vars['datos_factura'][0]['num_cont_factura']; ?>
" size="30" maxlength="70" class="form-text"/>
                            </td>
                        </tr>
                        <td>
                            <span style="font-family:'Verdana';"><b>Fecha Factura (*)</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fecha_factura" id="input_fecha_factura" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' size="30" maxlength="70" class="form-text"/>
                            <?php echo '
                                <script type="text/javascript">//<![CDATA[
                                    var cal = Calendar.setup({
                                        onSelect: function(cal) {
                                            cal.hide();
                                        }
                                    });
                                    cal.manageFields("input_fecha_factura", "input_fecha_factura", "%Y-%m-%d");
                                //]]>
                                </script>
                            '; ?>

                        </td>
                    <?php endif; ?>
                </table>
            </div>
            <!--</Datos del proveedor y vendedor>-->
            <div id="dcompra" class="x-hide-display"></div>
            <div id="PanelGeneralCompra">
                <div id="tabproducto" class="x-hide-display">
                    <div id="contenedorTAB">
                        <div id="div_tab1">
                            <div class="grid">
                                <table class="lista" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="tb-tit" style="text-align: center;">C&oacute;digo</th>
                                            <th class="tb-tit" style="text-align: center;">Descripci&oacute;n</th>
                                            <th class="tb-tit" style="text-align: center;">Cantidad</th>
                                            <th class="tb-tit" style="text-align: center;">Opci&oacute;n</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($this->_tpl_vars['cod'] != ""): ?>
                                            <?php $_from = $this->_tpl_vars['productos_pendientes_entrada']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['prod']):
?>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 20px;"><?php echo $this->_tpl_vars['prod']['codigo_barras']; ?>
</td><!--id_item-->
                                                    <td style="text-align: left; padding-left: 20px;"><?php echo $this->_tpl_vars['prod']['descripcion1']; ?>
</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo $this->_tpl_vars['prod']['cantidad']; ?>
</td>
                                                    <td></td>
                                                </tr>
                                            <?php endforeach; endif; unset($_from); ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="sf_admin_row_1">
                                            <td colspan="4">
                                                <div class="span_cantidad_items">
                                                    <span style="font-size: 12px; font-style: italic; text-align: left;">Cantidad de Items: 0</span>
                                                </div>
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
                            <table></table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items"/>
            <div id="displaytotal" class="x-hide-display"></div>
            <div id="displaytotal2" class="x-hide-display"></div>
        </form>
        <div id="incluirproducto" class="x-hide-display">
            <p>
                <label for="almacen"><b>Almac&eacute;n</b></label>
                <select id="almacen" name="almacen"></select>
            </p>
             <p>
                <label for="ubicacion"><b>Ubicac&iacute;on</b></label>
                <select id="ubicacion" name="ubicacion"></select>
            </p>
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra">
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>

            <p>
                <label><b>Productos</b></label><br/>
                <input type="hidden" name="items" id="items">
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly>
                <!--<select style="width:100%" id="items" name="items" onchange="comprobarfechavencimiento(this.id)"></select>-->
            </p>
            <p>
            <label><b>Posee Fecha de Vencimiento</b></label><br/>
            <input type="text" name="fecha_vence" id="fecha_vence" readonly>
            <!--<div id="resultado2"></div>-->
            </p>
            <p>
                <label><b>Fecha de vencimiento</b></label><br/>
                <input type="text" name="fVencimiento" id="fVencimiento"/>
                <?php echo '
                    <script type="text/javascript">//<![CDATA[
                        var cal = Calendar.setup({
                            onSelect: function(cal) {
                                cal.hide();
                            }
                        });
                        cal.manageFields("fVencimiento", "fVencimiento", "%Y-%m-%d");
                    //]]>
                    </script>
                '; ?>

            </p>
            <p>
            <label><b>Fecha de elaboración</b></label><br/>
            <input type="text" name="fechaelaboracion" id="fechaelaboracion"/>
            <?php echo '
            <script type="text/javascript">//<![CDATA[
            var cal = Calendar.setup({
            onSelect: function(cal) {
            cal.hide();
            }
            });
            cal.manageFields("fechaelaboracion", "fechaelaboracion", "%Y-%m-%d");
            //]]>
            </script>
            '; ?>

            </p>
             <p>
                <label><b> Numero de Lote</b></label><br/>
                <input type="text" name="nlote" id="nlote"/>
            </p>
            <p>
                <label><b>Cantidad Unitaria</b></label><br/>
                <input type="text" name="cantidadunitaria" id="cantidadunitaria"/>
            </p>
            <p>
                <label><b>Cantidad Esperada</b></label><br/>
                <input type="text" name="cantidaddeberia" id="cantidaddeberia"/>
            </p>
             <p style="display: none" id="observacion">
                <label><b>Observacion de diferencia</b></label><br/>
                <textarea name="observacion1" id="observacion1" style="width: 290px"></textarea>
            </p>
            <p>
                <label><b>Inventario a la Fecha</b></label><br/>
                <input type="text" name="cantidad_existente" id="cantidad_existente" readonly/>
            </p>
        </div>
    </body>
</html>
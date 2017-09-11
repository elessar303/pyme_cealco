<?php /* Smarty version 2.6.21, created on 2017-03-01 15:14:17
         compiled from pda_logistica_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'pda_logistica_add.tpl', 160, false),array('modifier', 'date_format', 'pda_logistica_add.tpl', 296, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada_pda.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formPDA.js"></script>
         <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
         
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
                                    alert("error peticion ajax");
                              },
                              success: function(data){  

                                $("#resultado2").html(data);
                                document.getElementById("fecha_vencimiento").focus();
                                
                              }
                  });

        }

         $(document).ready(function(){

            //regla unique para orden de comprar o documento de indentidad
            $("#nro_documento").blur(function()
            {
                valor = $(this).val();
                if(valor!=\'\')
                {
                    $.ajax({
                        type: "GET",
                        url:  "../../libs/php/ajax/ajax.php",
                        data: "opt=ValidarOrdenCompra&v1="+valor,
                        beforeSend: function()
                        {
                            $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                        },
                        success: function(data)
                        {
                            resultado = data
                            if(resultado=="-1")
                            {
                                $("#nro_documento").val("").focus();
                                $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, este Nro. de Documento Ya Existe.</b></span>");
                            }
                            if(resultado=="1")
                            {//cod de item disponble
                                $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Nro. de Documento Disponible</b></span>");
                            }
                        }
                    });
                }
            });

        //funcion para cargar los puntos 
                  $("#id_proveedor").change(function() {
                    proveedor = $("#id_proveedor").val();
                        $.ajax({
                            type: \'GET\',
                            data: \'opt=getInstalaciones&\'+\'idProveedor=\'+proveedor,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() {
                                $("#instalacion").find("option").remove();
                                $("#instalacion").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#instalacion").find("option").remove();
                                this.vcampos = eval(data);
                                $("#instalacion").append("<option value=\'0\'>Instalacion Principal</option>");
                                for (i = 0; i < this.vcampos.length; i++) {
                                    $("#instalacion").append("<option value=\'" + this.vcampos[i].codigo_sica+ "\'>" + this.vcampos[i].instalacion + "</option>");
                                }
                            }
                        }); 
                        $("#instalacion").val(0);
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
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Autorizado Por (*)</b></span>
                        </td>
                        <td>
                            <input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="<?php echo $this->_tpl_vars['nombre_usuario']; ?>
" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                            <input type="hidden" maxlength="100" name="tipo_log" id="tipo_log" value="<?php echo $this->_tpl_vars['tipo_log']; ?>
" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Proveedor (*)</b></span>
                        </td>
                        <td>
                            <select name="id_proveedor" id="id_proveedor" class="form-text" style="width:205px">
                            <option value="">Seleccione...</option>
                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_proveedor'],'output' => $this->_tpl_vars['option_output_proveedor']), $this);?>

                            </select>
                        </td>
                    </tr>

                  
                    <tr>
                        <?php if ($this->_tpl_vars['tipo_log'] == 'balance'): ?>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Nro. Orden De Compra</b></span>
                        </td>
                        <td>
                            <input type="text" name="nro_documento" maxlength="100" id="nro_documento" size="30" maxlength="70" class="form-text" readonly=true/>
                            <input type="hidden" name="tipo_entrada" maxlength="100" id="tipo_entrada" size="30" maxlength="70" value="1" class="form-text" readonly=true/>
                        </td>
                        <?php else: ?>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Nro. De Documento</b></span>
                        </td>
                        <td>
                            <input type="text" name="nro_documento" maxlength="100" id="nro_documento" size="30" maxlength="70" class="form-text" />
                            <div id="notificacionVUsuario"></div>
                            <input type="hidden" name="tipo_entrada" maxlength="100" id="tipo_entrada" size="30" maxlength="70" value="1" class="form-text" readonly=true/>
                        </td>
                        <?php endif; ?>
                        
                    </tr>
                    
                   <tr>
                        <td>
                            <p>
                            <label><b>Transporte</b></label><br/>
                            </p>
                        </td>
                        <td>
                        <select name="transporte" id="transporte" class="form-text" style="width:205px">
                            <option value="Gerencia De Transporte">Gerencia De Transporte</option>
                            <option value="Transporte Proveedor">Transporte Proveedor</option>
                        </select>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Observaciones</b></span>
                        </td>
                        <td>
                            <input type="text" name="observaciones" maxlength="100" id="observaciones" value="<?php echo $this->_tpl_vars['detalles_pendiente'][0]['observacion']; ?>
" size="30"  class="form-text"/>
                        </td>
                    </tr>
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
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra" class="form-text"/>
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>

            <p>
                <label><b>Productos</b></label><br/>
                <input type="hidden" name="items" id="items" class="form-text" />
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly class="form-text" style="width:205px" />
               
            </p>
           
            <p>
                <label><b>Cantidad Unitaria</b></label><br/>
                <input type="text" name="cantidadunitaria" id="cantidadunitaria" class="form-text" style="width:205px"/>
            </p>

            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Instalación</b></span><br>
          
                <select name='instalacion' id='instalacion' class="form-text" style="width:205px">
                    <option value=''>Seleccione Un Proveedor...</option>
                </select>
            </p>
            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Inicio</b></span><br>
                <input type="text" name="input_fechaplanificacion_inicio" id="input_fechaplanificacion_inicio" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>
' size="30" maxlength="70" class="form-text" readonly="readonly" style="width:205px"  />
                <div id="notificacionVUsuariofecha1"></div>
                    <?php echo '
                    <script type="text/javascript">
                    //<![CDATA[
                    var cal = Calendar.setup(
                    {
                        onSelect: function(cal) 
                        {
                            cal.hide();
                        }
                    });
                    cal.manageFields("input_fechaplanificacion_inicio", "input_fechaplanificacion_inicio", "%d/%m/%Y");
                    //]]>
                    </script>
                    '; ?>

                <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Fin</b></span><br>
                <input type="text" name="input_fechaplanificacion_fin" id="input_fechaplanificacion_fin" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>
' size="30" maxlength="70" class="form-text" readonly="readonly" style="width:205px" />
                <div id="notificacionVUsuariofecha2"></div>
                    <?php echo '
                    <script type="text/javascript">
                    //<![CDATA[
                    var cal = Calendar.setup(
                    {
                        onSelect: function(cal) 
                        {
                            cal.hide();
                        }
                    });
                    cal.manageFields("input_fechaplanificacion_fin", "input_fechaplanificacion_fin", "%d/%m/%Y");
                    //]]>
                    </script>
                    '; ?>

            </p>
             <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Observación</b></span><br>
                 <input type="text" name="observacion_detalle" id="observacion_detalle" class="form-text" style="width:205px"/>
            </p>
            
        </div>
    </body>
</html>
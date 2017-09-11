<?php /* Smarty version 2.6.21, created on 2017-08-24 11:40:23
         compiled from cxp_estadodecuenta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'cxp_estadodecuenta.tpl', 150, false),array('modifier', 'number_format', 'cxp_estadodecuenta.tpl', 197, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        <!--agregar_factura.js, nuevo script que gestiona la inclusión de una factura por compras que no generan inventario para la venta-->
        <script type="text/javascript" src="../../libs/js/agregar_factura.js"></script>
        <script type="text/javascript" src="../../libs/js/cxp_edocuenta.js"></script>
        <script type="text/javascript" src="../../libs/js/anular_factura.js"></script>
        <script type="text/javascript" src="../../libs/js/anular_orden_compra.js"></script>
    </head>
    <body>
        <div id="loading" style="position:absolute; width:80%; text-align:center; top:180px; visibility:hidden;">
            <img src="../../../includes/imagenes/36.gif"/>
        </div>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="DatosCliente" value=""/>
            <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
            <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
            <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
            <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
            <input type="hidden" name="url_delete_asientos" value="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=<?php echo $_GET['opt_subseccion']; ?>
&amp;cod=<?php echo $_GET['cod']; ?>
"/>
            <table style="width: 100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="900">
                                        <span style="float:left">
                                            <img src="<?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" />
                                            <?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>

                                        </span>
                                    </td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:agregarFactura();">
                                            <tr>
                                                <td style="padding: 0px; text-align: right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Agregar Factura</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';">
                                            <tr>
                                                <td style="padding: 0px; text-align: right"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <div id="agregarfactura" class="x-hide-display">
                <table style="width: 100%">
                    <thead>
                        <tr>
                            <th colspan="2" style="font-family:'Verdana'; text-align: center;" class="tb-tit"><b>Proporcione Datos de la Factura</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <img style="vertical-align: middle;" width="17" height="17" src="../../../includes/imagenes/28.png"/>
                                <span style="font-family:'Verdana'; vertical-align: middle;"><b>Responsable (*)</b></span>
                            </td>
                            <td>
                                <input type="hidden" name="id_proveedor_factura" id="id_proveedor_factura" value="<?php echo $this->_tpl_vars['datacliente'][0]['id_proveedor']; ?>
"/>
                                <input type="hidden" name="usuario_creacion_factura" id="usuario_creacion_factura" value="<?php echo $this->_tpl_vars['usuario']; ?>
"/>
                                <input type="hidden" name="alicuota" id="alicuota" value="<?php echo $this->_tpl_vars['data_retencion'][0]['alicuota']; ?>
"/>
                                <input type="hidden" name="cod_impuesto" id="cod_impuesto" value="<?php echo $this->_tpl_vars['data_retencion'][0]['cod_impuesto']; ?>
"/>
                                <input type="text" maxlength="70" name="responsable_agregar" id="responsable_agregar" value="<?php echo $this->_tpl_vars['responsable']; ?>
"/>
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/03.png"/>
                                <span style="font-family:'Verdana';"><b>N&uacute;mero Factura (*)</b></span>
                            </td>
                            <td><input type="text" name="num_factura_agregar" maxlength="70" id="num_factura_agregar"/></td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/03.png"/>
                                <span style="font-family:'Verdana';"><b>N&uacute;mero Control</b></span>
                            </td>
                            <td><input type="text" name="num_control_factura_agregar" maxlength="70" id="num_control_factura_agregar"/></td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/03.png"/>
                                <span style="font-family:'Verdana';"><b>Sub Total (*)</b></span>
                            </td>
                            <td><input type="text" name="subtotal_factura" maxlength="70" id="subtotal_factura"/></td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/03.png"/>
                                <span style="font-family:'Verdana';"><b>Monto Exento (*)</b></span>
                            </td>
                            <td><input type="text" name="exento_factura" maxlength="70" id="exento_factura"/></td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/03.png"/>
                                <span style="font-family:'Verdana';"><b>Base Imponible (*)</b></span>
                            </td>
                            <td><input type="text" name="base_factura" maxlength="70" id="base_factura"/></td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/03.png"/>
                                <span style="font-family:'Verdana';"><b>IVA (*)</b></span>
                            </td>
                            <td><input type="text" name="iva_factura" maxlength="70" id="iva_factura"/></td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/03.png"/>
                                <span style="font-family:'Verdana';"><b>Retenci&oacute;n de IVA</b></span>
                            </td>
                            <td>
                                <input type="radio" name="retencion_iva" value="1" />S&iacute;
                                <input type="radio" name="retencion_iva" value="0" checked />No
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/03.png"/>
                                <span style="font-family:'Verdana';"><b>Libro de Compras</b></span>
                            </td>
                            <td>
                                <input type="radio" name="libro_compras" value="1" />S&iacute;
                                <input type="radio" name="libro_compras" value="0" checked />No
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/fecha.png"/>
                                <span style="font-family:'Verdana';"><b>Fecha Emisi&oacute;n (*)</b></span>
                            </td>
                            <td>
                                <input type="text" name="fecha_emision_fact" id="fecha_emision_fact" size="20" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
"/>
                                <?php echo '
                                    <script type="text/javascript">//<![CDATA[
                                    var cal = Calendar.setup({
                                            onSelect: function(cal) { cal.hide(); }
                                    });
                                    cal.manageFields("fecha_emision_fact", "fecha_emision_fact", "%Y-%m-%d");
                                    //]]>
                                    </script>
                                '; ?>

                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td>
                                <img width="17" height="17" src="../../../includes/imagenes/fecha.png"/>
                                <span style="font-family:'Verdana';"><b>Fecha Vencimiento (*)</b></span>
                            </td>
                            <td>
                                <input type="text" name="fecha_vence_fact" id="fecha_vence_fact" size="20" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
"/>
                                <?php echo '
                                    <script type="text/javascript">//<![CDATA[
                                    var cal = Calendar.setup({
                                            onSelect: function(cal) { cal.hide(); }
                                    });
                                    cal.manageFields("fecha_vence_fact", "fecha_vence_fact", "%Y-%m-%d");
                                    //]]></script>
                                '; ?>

                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="color: red;"><i>Los campos marcados con (*) son obligatorios</i></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!--Datos del cliente y vendedor-->
            <div style="background-color:#ffffff; border: 1px solid #ededed; -moz-border-radius: 7px; -webkit-border-radius: 7px; padding:5px; margin-top:0.3%; font-size: 13px; ">
                <img align="absmiddle" src="../../../includes/imagenes/ico_user.gif">
                <span style="font-family:'Verdana';"><b>Proveedor:&nbsp;</b><?php echo $this->_tpl_vars['datacliente'][0]['descripcion']; ?>
&nbsp;<b>CI/RIF:&nbsp;</b><?php echo $this->_tpl_vars['datacliente'][0]['rif']; ?>
</span>
                <input type="hidden" name="id_cliente" value="<?php echo $this->_tpl_vars['datacliente'][0]['id_proveedor']; ?>
"/>
            </div>
            <!--Resumen de estado de cuenta-->
            <div style="font-family:'Verdana'; font-size: 15px; border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius: 7px; -webkit-border-radius: 7px; margin-top: 0.3%;"><!--margin-right: 0.3%;-->
                <div style="float: left; margin-right: 20px;">
                    <b>D&eacute;bitos</b>
                    <div style="color: rgb(78, 106, 72); color: red;"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['cabecera_estadodecuenta'][0]['debito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
</b></div>
                </div>
                <div style="float: left; margin-right: 20px;">
                    <b>Cr&eacute;ditos</b>
                    <div style="color: rgb(78, 106, 72); color:#166a09 ;"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['cabecera_estadodecuenta'][0]['credito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
</b></div>
                </div>
                <div style="float: left; margin-right: 20px;">
                    <b>Facturas Pagadas</b>
                    <div style="color: #105a04;"><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['facturas_pagadas']; ?>
</b></div>
                </div>
                <div style="float: left; margin-right: 20px;">
                    <b>Facturas Pendientes </b>
                    <div style="color: rgb(78, 106, 72); color: red;"><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['facturas_pendientes']; ?>
</b></div>
                </div>
                <div style="float: left; margin-right: 20px;">
                    <b>Facturas Totales </b>
                    <div style="color: rgb(78, 106, 72);"><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['total_facturas']; ?>
</b></div>
                </div>
                <div style="margin-right: 20px;">
                    <b>Saldo Pendiente</b>
                    <div style="color: red;"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['cabecera_estadodecuenta'][0]['saldo_pendiente'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
</b></div>
                </div>
            </div>
            <!--<TABLA DE CUENTAS POR COBRAR>-->
            <div style="background-color:#ffffff; border: 1px solid #ededed; border-radius: 7px; padding:5px; margin-top:0.3%; font-size: 13px;">
                <table style="width: 100%;">
                    <thead>
                        <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                        <th style="text-align: center; font-family:'Verdana'; font-size: 15px;"><b><?php echo $this->_tpl_vars['campos']; ?>
</b></th>
                    <?php endforeach; endif; unset($_from); ?>
                    </thead>
                    <tbody>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="8" style="text-align: center;"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                        <?php else: ?>
                            <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <?php if ($this->_tpl_vars['campos']['estado'] != 'Anulada'): ?>
                                    <?php if ($this->_tpl_vars['i']%2 == 0): ?>
                                        <?php $this->assign('color', ""); ?>
                                    <?php else: ?>
                                        <?php $this->assign('color', "#cacacf"); ?>
                                    <?php endif; ?>
                                    <tr style="cursor: pointer;" bgcolor="<?php echo $this->_tpl_vars['color']; ?>
" class="edocuenta">
                                        <td style="text-align:center;">
                                            <img class="boton_edocuenta" src="../../../includes/imagenes/drop-add.gif"/>
                                            <input type="hidden" name="cod_proveedor" value="<?php echo $this->_tpl_vars['campos']['id_proveedor']; ?>
"/>
                                            <input type="hidden" name="cod_edocuenta" value="<?php echo $this->_tpl_vars['campos']['cod_edocuenta']; ?>
"/>
                                        </td>
                                        <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['documento']; ?>
</td>
                                        <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['numero']; ?>
</td>
                                        <td style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['fecha_emision'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td>
                                        <td style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['vencimiento_fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td>
                                        <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['observacion']; ?>
</td>
                                        <td style="text-align:right; padding-right: 55px;"><?php echo $this->_tpl_vars['empresa'][0]['moneda']; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['monto'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : number_format($_tmp, 2, ",", ".")); ?>
</td>
                                        <td style="text-align:center;">
                                            <?php if ($this->_tpl_vars['campos']['estado'] == 'Pagada'): ?>
                                                <img title="Pagada" src="../../../includes/imagenes/ico_ok.gif"/>
                                            <?php elseif ($this->_tpl_vars['campos']['estado'] == 'Pendiente'): ?>
                                                <img title="Pendiente" src="../../../includes/imagenes/ico_note_1.gif"/>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align:center">
                                            <?php if ($this->_tpl_vars['campos']['estado'] == 'Pagada' || $this->_tpl_vars['campos']['montodet'] > 0): ?>
                                                <img style="cursor: pointer;" class="eliminar" title="No puede anular, tiene pago asociado" src="../../../includes/imagenes/ico_est6.gif"/>
                                            <?php else: ?>
                                                <img style="cursor: pointer;" class="eliminar" onclick="javascript:anularFactura('<?php echo $this->_tpl_vars['campos']['cod_edocuenta']; ?>
');" title="Anular Cuenta Por Pagar" src="../../../includes/imagenes/cancel.gif"/>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!--/TABLA DE CUENTAS POR COBRAR-->
        </form>
        <div id="info" style="display:none;"></div>
        <!-- Pantalla de Observaciones de Anular Factura -->
        <div id="anularfactura" class="x-hide-display">
            <!-- Pantalla de Observaciones de Anular Factura -->
            <table style="width: 100%">
                <thead>
                    <tr style="text-align: center;">
                        <th colspan="4" style="vertical-align: middle;" class="tb-tit"><b>Ingrese el Motivo de la Anulaci&oacute;n</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="vertical-align: top; width: 40%;" colspan="3" class="tb-head">Fecha**</td>
                        <td>
                            <input type="text" name="fecha" id="fecha" size="20"  value="<?php echo $this->_tpl_vars['campos_item'][0]['fecha']; ?>
"/>
                            <?php echo '
                                <script type="text/javascript">//<![CDATA[
                                var cal = Calendar.setup({
                                        onSelect: function(cal) { cal.hide(); }
                                });
                                cal.manageFields("fecha", "fecha", "%d-%m-%Y");
                                //]]>
                                </script>
                            '; ?>

                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; width: 40%;" colspan="3" class="tb-head">Motivo de Anulaci&oacute;n</td>
                        <td>
                            <textarea maxlength="250" id="motivoAnulacion" name="motivoAnulacion" rows="2" cols="20"></textarea>
                            <input type='hidden' id='detalle_asiento' name='detalle_asiento' value='".$item["cod_edocuenta_detalle"]."'/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pantalla  de Anular Orden de Compra -->
        <div id="anularordencompra" class="x-hide-display">
            <table style="width: 100%">
                <thead>
                    <tr style="text-align:center;">
                        <th colspan="4" style="vertical-align: middle;" class="tb-tit"><b>Ingrese el Motivo de la Anulaci&oacute;n</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="vertical-align: top; width: 40%;" colspan="3" class="tb-head">Fecha**</td>
                        <td>
                            <input type="text" name="fechaOrden" id="fechaOrden" size="20"  value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
"/>
                            <?php echo '
                                <script type="text/javascript">//<![CDATA[
                                    var cal = Calendar.setup({
                                        onSelect: function(cal) { cal.hide(); }
                                    });
                                    cal.manageFields("fechaOrden", "fechaOrden", "Y-%m-%d");
                                //]]>
                                </script>
                            '; ?>

                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; width: 40%;" colspan="3" class="tb-head" >Motivo de Anulaci&oacute;n</td>
                        <td>
                            <textarea maxlength="250" id="motivoAnulacionOrden" name="motivoAnulacionOrden" rows="2" cols="20"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
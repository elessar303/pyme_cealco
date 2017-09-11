<?php /* Smarty version 2.6.21, created on 2017-08-24 11:37:38
         compiled from vendedor_editar.tpl */ ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        <script type="text/javascript" src="../../libs/js/vendedor_tabs.js"></script>
        <?php $this->assign('name_form', 'vendedor_editar'); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){
                    var resultado;
                    $("#cod_vendedor").change(function(){
                        var valor = $(this).val();
                        if(valor!=""){
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarCodigoVendedor&v1="+valor,
                                beforeSend: function(){
                                    $("#notificacionVCodVendedor").html(MensajeEspera("<b>Veficando C&oacute;digo Vendedor...<b>"));
                                },
                                success: function(data){
                                    resultado = data
                                    if(resultado=="-1"){
                                        //$("#cod_vendedor").val("").focus();
                                        $("#notificacionVCodVendedor").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, este c&oacute;digo ya existe.</b></span>");
                                    }
                                    if(resultado=="1"){//cod de item disponble
                                        $("#notificacionVCodVendedor").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> C&oacute;digo Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });
                    $("input[name=\'cancelar\']").button();//Coloca estilo JQuery
                    $("input[name=\'aceptar\']").button().click(function(){
                        if($("#nombre").val()=="" || $("#cod_vendedor").val()==""){
                            Ext.Msg.alert("Alerta","Debe llenar los campos obligatorios");
                            $("#nombre").focus();
                            return false;
                        }
                        if(resultado=="-1"){
                            return false;
                        }
                    });
                    $(".validadDecimales").numeric();
                    $(".validadDecimales").blur(function(){
                        if($(this).val()!=\'\' && $(this).val()!=\'.\'){
                            $(this).val(parseFloat($(this).val()));
                        }else{
                            $(this).val("0.00");
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
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="cod_empresa" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['cod_empresa']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
                <div id="contenedorTAB">
                    <div id="div_tab1" class="x-hide-display">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="4" class="tb-head" style="text-align: center;">COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="label">
                                        C&oacute;digo
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <div style="float: left;">
                                            <input type="text" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['cod_vendedor']; ?>
" name="cod_vendedor" id="cod_vendedor" size="60" class="form-text"/>
                                            <input type="hidden" name="id_vendedor" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['cod_vendedor']; ?>
" id="id_vendedor" />
                                        </div>
                                        <div id="notificacionVCodVendedor" style="float: left; padding-left: 10px;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Nombre **
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="nombre" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['nombre']; ?>
" id="nombre" size="60" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Direcci&oacute;n
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="direccion1" id="direccion1" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['direccion1']; ?>
" size="60" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Direccion 2
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="direccion2" id="direccion2"  value="<?php echo $this->_tpl_vars['datosvendedor'][0]['direccion2']; ?>
" size="60" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Telefonos
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="telefonos"  value="<?php echo $this->_tpl_vars['datosvendedor'][0]['telefonos']; ?>
" id="telefonos" size="60" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Fax
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="fax" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['fax']; ?>
" id="fax" size="60" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        E-Mail
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text"  id="email" name="email"  value="<?php echo $this->_tpl_vars['datosvendedor'][0]['email']; ?>
" size="60" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Clase
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="clase" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['clase']; ?>
" id="clase" size="60" class="form-text"/>
                                        <!--select name="clase" id="clase" class="form-text">
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['clase'] == 1): ?> selected <?php endif; ?> value="1">xxx;</option>
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['clase'] == 0): ?> selected <?php endif; ?> value="0">xxx</option>
                                        </select-->
                                    </td>
                                </tr>
                        </table>
                    </div>
                    <div id="div_tab2" class="x-hide-display">
                        <table style="width: 100%;">
                            <tr>
                                <td colspan="4" class="tb-head" style="text-align: center;">
                                    COMISI&Oacute;N POR VENTAS
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % Por debajo del m&iacute;nimo
                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['venta_x_debajo_minimo']; ?>
" name="venta_x_debajo_minimo" id="venta_x_debajo_minimo"  size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % a <?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio1']; ?>

                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="venta_a_precio1" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['venta_a_precio1']; ?>
" id="venta_a_precio1"  size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % a <?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio2']; ?>

                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="venta_a_precio2" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['venta_a_precio2']; ?>
"  id="venta_a_precio2"  size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % a <?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio3']; ?>

                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="venta_a_precio3" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['venta_a_precio3']; ?>
" id="venta_a_precio3" size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % por Servicio
                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="venta_x_servicio"  value="<?php echo $this->_tpl_vars['datosvendedor'][0]['venta_x_servicio']; ?>
" id="venta_x_servicio" size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % ger&eacute;ricos
                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="venta_gerericos"  value="<?php echo $this->_tpl_vars['datosvendedor'][0]['venta_gerericos']; ?>
" id="venta_gerericos" size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="tb-head" style="text-align: center;">
                                    COMISI&Oacute;N POR COBRANZA
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % Por debajo del m&iacute;nimo
                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="comision_x_debajo_minimo" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['comision_x_debajo_minimo']; ?>
" id="comision_x_debajo_minimo" size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % a <?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio1']; ?>

                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="comision_a_precio1" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['comision_a_precio1']; ?>
" id="comision_a_precio1" size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % a <?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio2']; ?>

                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="comision_a_precio2" value="<?php echo $this->_tpl_vars['datosvendedor'][0]['comision_a_precio2']; ?>
" id="comision_a_precio2" size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % a <?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio3']; ?>

                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="comision_a_precio3"  value="<?php echo $this->_tpl_vars['datosvendedor'][0]['comision_a_precio3']; ?>
" id="comision_a_precio3" size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % por Servicio
                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="comision_x_servicio"   value="<?php echo $this->_tpl_vars['datosvendedor'][0]['comision_x_servicio']; ?>
" id="comision_x_servicio" size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="label">
                                    % geréricos
                                </td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="text" name="comision_gerericos"  value="<?php echo $this->_tpl_vars['datosvendedor'][0]['comision_gerericos']; ?>
" id="comision_gerericos"  size="60" class="validadDecimales form-text"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="div_tab3" class="x-hide-display">
                        <table style="width:100%">
                            <tbody>
                                <tr><td colspan="3" class="tb-head" style="text-align: center;">&nbsp;</td></tr>
                                <tr>
                                    <td class="label">
                                        ¿Usa Tabla de Cobros?
                                        <select name="comision_tabla_de_cobros" id="comision_tabla_de_cobros" class="form-text">
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['comision_tabla_de_cobros'] == 1): ?> selected <?php endif; ?> value="1">S&iacute;</option>
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['comision_tabla_de_cobros'] == 0): ?> selected <?php endif; ?> value="0">No</option>
                                        </select>
                                        <!--/td>
                                        <td colspan="2" style="padding-top:2px; padding-bottom: 2px;"-->
                                        &nbsp;Tipo
                                        <select name="tipo_comision" id="tipo_comision" class="form-text">
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['tipo_comision'] == 'Monto'): ?> selected <?php endif; ?> value="Monto">Monto</option>
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['tipo_comision'] == 'Porcentaje'): ?> selected <?php endif; ?> value="Porcentaje">Porcentaje</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de Cobro 1 Desde&nbsp;<input type="text" name="rancosdesde1" id="rancosdesde1" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="rancoshasta1" id="rancoshasta1" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor1" id="factor1" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de Cobro 2 Desde&nbsp;<input type="text" name="rancosdesde2" id="rancosdesde2" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="rancoshasta2" id="rancoshasta2" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor2" id="factor2" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de Cobro 3 Desde&nbsp;<input type="text" name="rancosdesde3" id="rancosdesde3" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="rancoshasta3" id="rancoshasta3" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor3" id="factor3" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de Cobro 4 Desde&nbsp;<input type="text" name="rancosdesde4" id="rancosdesde4" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="rancoshasta4" id="rancoshasta4" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor4" id="factor4" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de Cobro 5 Desde&nbsp;<input type="text" name="rancosdesde5" id="rancosdesde5" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="rancoshasta5" id="rancoshasta5" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor5" id="factor5" value="0.00" size="30" class="validadDecimales form-text"/></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="div_tab4" class="x-hide-display">
                        <table style="width:100%">
                            <tbody>
                                <tr><td colspan="3" class="tb-head" style="text-align: center;">&nbsp;</td></tr>
                                <tr>
                                    <td class="label">
                                        ¿Usa Tabla de Ventas?
                                        <select name="comision_tabla_de_cobrosven" id="comision_tabla_de_cobrosven" class="form-text">
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['comision_tabla_de_cobrosven'] == 1): ?> selected <?php endif; ?> value="1">S&iacute;</option>
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['comision_tabla_de_cobrosven'] == 0): ?> selected <?php endif; ?> value="0">No</option>
                                        </select>
                                        <!--/td>
                                        <td style="padding-top:2px; padding-bottom: 2px;"-->
                                        &nbsp;Tipo
                                        <select name="tipo_comisionven" id="tipo_comisionven" class="form-text">
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['comision_tabla_de_cobrosven'] == 'Monto'): ?> selected <?php endif; ?> value="Monto">Monto</option>
                                            <option <?php if ($this->_tpl_vars['datosvendedor'][0]['comision_tabla_de_cobrosven'] == 'Porcentaje'): ?> selected <?php endif; ?> value="Porcentaje">Porcentaje</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de ventas 1 Desde&nbsp;<input type="text" name="ranvendesde1" id="ranvendesde1"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="ranvenhasta1" id="ranvenhasta1"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor1ven" id="factor1ven"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de ventas 2 Desde&nbsp;<input type="text" name="ranvendesde2" id="ranvendesde2"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="ranvenhasta2" id="ranvenhasta2"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor2ven" id="factor2ven"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de ventas 3 Desde&nbsp;<input type="text" name="ranvendesde3" id="ranvendesde3"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="ranvenhasta3" id="ranvenhasta3"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor3ven" id="factor3ven"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de ventas 4 Desde&nbsp;<input type="text" name="ranvendesde4" id="ranvendesde4"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text" name="ranvenhasta4" id="ranvenhasta4"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor4ven" id="factor4ven" value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                </tr>
                                <tr>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Rango de ventas 5 Desde&nbsp;<input type="text" name="ranvendesde5" id="ranvendesde5"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Hasta&nbsp;<input type="text"  name="ranvenhasta5" id="ranvenhasta5"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                    <td class="label" style="padding-top:2px; padding-bottom: 2px;">Factor&nbsp;<input type="text" name="factor5ven" id="factor5ven"  value="0.00"  size="30" class="validadDecimales form-text" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <table style="width: 100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" id="aceptar" name="aceptar" value="Guardar Cambios"/>
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
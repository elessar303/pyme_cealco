<?php /* Smarty version 2.6.21, created on 2017-09-11 13:58:17
         compiled from parametros_generales.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'parametros_generales.tpl', 165, false),)), $this); ?>
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
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
         <script type="text/javascript" src="../../libs/js/jquery.numeric.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        <script type="text/javascript" src="../../libs/js/parametros_generales_tabs.js"></script>
        <script type="text/javascript" src="../../libs/js/ajax.js"></script>
        <?php echo '
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function() {
                    $("input[name=\'cancelar\']").button();//Coloca estilo JQuery
                    $("input[name=\'aceptar\']").button().click(function() {
                        /*var tipo = document.getElementById("tipo_facturacion");
                        if (tipo.options[tipo.selectedIndex].value === "1") {*/
                        if ($("input[name=\'tipo_facturacion\']:checked").val()==="1"){
                            if ($("#impresora_modelo").val() === "" /*|| $("#impresora_marca").val() === ""*/ || $("#impresora_serial").val() === "" || ($("#impresora_marca option:selected").val()==="spooler" && $("#impresora_marca_spooler").val()==="")) {
                                Ext.Msg.alert("Alerta", "Proporcione Datos de Impresora Fiscal");
                                return false;//Evita que el formulario sea enviado
                            }
                        }
                    });

                    $("#tipofacturacion").buttonset();

                    if ($("input[name=\'tipo_facturacion\']:checked").val()==="1"){
                        $("#impresora_marca").attr("disabled", false);
                        $("#impresora_modelo").attr("disabled", false);
                        $("#impresora_serial").attr("disabled", false);
                    }
                    else{
                        $("#impresora_marca_spooler").css("display", "none");
                        $("#impresora_marca_spooler").attr("disabled", true);
                    }
                    if($("#impresora_marca").val()==="spooler"){
                        var impresora_marca_spooler = $("#impresora_marca_spooler").val().split(":");
                        $("#impresora_marca_spooler").val(impresora_marca_spooler[1]);
                        $("#impresora_marca_spooler").css("display", "inline");
                    }
                    $("#impresora_marca").change(function(){
                        if($(this).val()=="spooler"){
                            $("#impresora_marca_spooler").css("display", "inline");
                            $("#impresora_marca_spooler").attr("disabled", false);
                            $("#swterceroimp").val(1);
                        }
                        else{
                            $("#impresora_marca_spooler").css("display", "none");
                            $("#impresora_marca_spooler").attr("disabled", true);
                            $("#swterceroimp").val(0);
                        }
                    });
                    $("input[name=\'tipo_facturacion\']").change(function() {
                        if ($("input[name=\'tipo_facturacion\']:checked").val()==="1"){
                            $("#impresora_marca").attr("disabled", false);
                            $("#impresora_modelo").attr("disabled", false);
                            $("#impresora_serial").attr("disabled", false);
                        }
                        else{
                            $("#impresora_marca").attr("disabled", true);
                            $("#impresora_modelo").attr("disabled", true);
                            $("#impresora_serial").attr("disabled", true);
                            $("#impresora_marca_spooler").css("display", "none");
                            $("#impresora_marca_spooler").attr("disabled", true);
                        }
                    });
                    $("#error_impresora_modelo").hide();
                    $("#impresora_modelo").blur(function() {
                        if ($("#impresora_modelo").val() === "") {
                            $("#error_impresora_modelo").show();
                            $("#error_impresora_modelo").focus();
                            return false;
                        }
                    });
                    $("#porcentaje_impuesto_principal, #iva_a, #iva_b, #iva_c,#porcentaje_impuesto2, #porcentaje_impuesto3, #pprovee_sobr_impu_princ, #pclient_sobr_impu_princ").numeric();
                    $(".validadDecimales").blur(function() {
                        $(this).val(($(this).val() != \'\' && $(this).val() != \'.\' && $(this).val() != \'0\') ? parseFloat($(this).val()) : "0.00");
                    });
                    $("#nombre_impuesto_principal").blur(function() {
                        $(".vstring_nombre_impuesto_principal").html($(this).val());
                    });
                    $("#string_impuesto2").blur(function() {
                        $("#vstring_porcentaje_impuesto2").html($(this).val());
                    });
                    $("#string_impuesto3").blur(function() {
                        $("#vstring_porcentaje_impuesto3").html($(this).val());
                    });

                     function cargarUbicaciones() {    
                        idAlmacen=$("#cod_almacen").val();     
                        $.ajax({
                            type: \'POST\',
                            data: \'opt=cargaUbicacion&idAlmacen=\'+idAlmacen,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() {
                                $("#id_ubicacion").find("option").remove();
                                $("#id_ubicacion").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#id_ubicacion").find("option").remove();
                                this.vcampos = eval(data);
                                 $("#id_ubicacion").append("<option value=\'\'>Seleccione..</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#id_ubicacion").append("<option value=\'"+this.vcampos[i].id+"\'>" + this.vcampos[i].descripcion + "</option>");
                                }
                            }
                        });
                    }

                    $("#cod_almacen").change(function() {
                       cargarUbicaciones();                       
                    });
                    
                });
                //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="form-<?php echo $this->_tpl_vars['name_form']; ?>
" action="" method="post" enctype="multipart/form-data">
            <div id="datosGral">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_solo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="cod_empresa" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['cod_empresa']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <div id="contenedorTAB">
                    <!-- TAB1 -->
                    <div id="div_tab1" class="x-hide-display">
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                    <th colspan="3" class="tb-head" style="text-align:center;">
                                        COMPLETE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="label">
                                        Nombre Empresa
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="nombre_empresa" id="nombre_empresa" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['nombre_empresa']; ?>
" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Direcci&oacute;n
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <textarea id="direccion" name="direccion" class="form-text" style="width:300px;"><?php echo $this->_tpl_vars['DatosGenerales'][0]['direccion']; ?>
</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Estado
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select  name="estado" id="estado" class="form-text" style="width:300px;">
                                        <option value="9999">Seleccione</option>
                                        <?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['option_values_nombre_estado'],'values' => $this->_tpl_vars['option_values_id_estado'],'selected' => $this->_tpl_vars['DatosGenerales'][0]['estado']), $this);?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Ciudad
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" id="ciudad" name="ciudad" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['ciudad']; ?>
" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Tel&eacute;fonos
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="telefonos" id="telefonos" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['telefonos']; ?>
" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Nombre de ID Fiscal 1
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['id_fiscal']; ?>
" name="id_fiscal" id="id_fiscal" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        N&uacute;mero de ID Fiscal 1
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" id="rif" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['rif']; ?>
" name="rif" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Nombre de ID Fiscal 2
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['id_fiscal2']; ?>
" id="id_fiscal2" name="id_fiscal2" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        N&uacute;mero de ID Fiscal 2
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['nit']; ?>
" type="text" name="nit" id="nit" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Logo Reportes
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="file" name="img_izq" id="img_izq" class="form-text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Logo Reportes
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="file" name="img_der" id="img_der" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Arquitectura Servidor
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="servidor" id="servidor" style="width:200px;" class="form-text">
                                            <option value="00" disabled="disabled" selected="selected">Seleccione</option>
                                            <option value="1" <?php if ($this->_tpl_vars['DatosGenerales'][0]['servidor'] == '1'): ?> selected <?php endif; ?>>Linux</option>
                                            <option value="0" <?php if ($this->_tpl_vars['DatosGenerales'][0]['servidor'] == '0'): ?> selected <?php endif; ?>>Windows</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- TAB2 -->
                    <div id="div_tab2" class="x-hide-display">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="3" class="tb-head" style="text-align:center;">
                                        T&Iacute;TULOS DE PRECIOS
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="label">
                                        Precio 1
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio1']; ?>
" name="titulo_precio1" id="titulo_precio1" placeholder="Descripci&oacute;n Precio 1" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Precio 2
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio2']; ?>
" type="text" id="titulo_precio2" name="titulo_precio2" placeholder="Descripci&oacute;n Precio 2" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Precio 3
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio3']; ?>
" type="text" id="titulo_precio3" name="titulo_precio3" placeholder="Descripci&oacute;n Precio 3" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Precio por defecto
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="precio_menor" id="precio_menor" class="form-text">
                                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_precio'],'selected' => $this->_tpl_vars['option_selected_precio'],'output' => $this->_tpl_vars['option_output_precio']), $this);?>

                                        </select>
                                    </td>
                                </tr>
                                 <tr>
                                    <td colspan="2" class="label">
                                        Cuenta Captadora
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="cuenta_captadora" id="cuenta_captadora" class="form-text">
                                            <option value="0">Seleccione Una Cuenta </option>
                                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuentas'],'selected' => $this->_tpl_vars['option_selected_cuentas'],'output' => $this->_tpl_vars['option_output_cuentas']), $this);?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Cuenta Sobrante
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="cuenta_sobrante" id="cuenta_sobrante" class="form-text">
                                             <option value="0">Seleccione Una Cuenta </option>
                                             <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuentas'],'selected' => $this->_tpl_vars['option_selected_cuentas_sobrantes'],'output' => $this->_tpl_vars['option_output_cuentas']), $this);?>
                                            
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">Codigo SIGA
                                       
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['codigo_siga']; ?>
" type="text" id="codigo_siga" name="codigo_siga" placeholder="codigo SIGA"  class="form-text" maxlength="3" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Almacen por defecto
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="cod_almacen" id="cod_almacen" class="form-text">
                                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_almacen'],'selected' => $this->_tpl_vars['option_selected_almacen'],'output' => $this->_tpl_vars['option_output_almacen']), $this);?>

                                        </select>
                                    </td>
                                </tr>
                                 <tr>
                                    <td colspan="2" class="label">
                                        Ubicacion De Venta
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="ubicacion_venta" id="ubicacion_venta" alt="Seleccione la ubicacion de venta del punto" class="form-text">
                                            <option value="0">Seleccione Ubicacion De Venta </option>
                                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_tipoventas'],'selected' => $this->_tpl_vars['option_selected_tipoventas'],'output' => $this->_tpl_vars['option_output_tipoventas']), $this);?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Ubicacion por defecto
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="id_ubicacion" id="id_ubicacion" class="form-text">
                                           <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_ubicacion'],'selected' => $this->_tpl_vars['option_selected_ubicacion'],'output' => $this->_tpl_vars['option_output_ubicacion']), $this);?>

                                        </select>                                   </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Sincronizacion de inventario
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">

                                      <input name="sincronizacionInv" id="sincronizacionInv" <?php if ($this->_tpl_vars['DatosGenerales'][0]['sincronizacion_inv'] == '1'): ?> checked <?php endif; ?>  value="1"   type="checkbox">  
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Codigo Kardex
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">

                                      <input name="codigokardex" id="codigokardex" <?php if ($this->_tpl_vars['DatosGenerales'][0]['codigo_kardex'] == '1'): ?> checked <?php endif; ?>  value="1"   type="checkbox">  
                                    </td>
                                </tr>
                                 <tr>
                                    <td colspan="2" class="label">
                                        punto con fortinet
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">

                                      <input name="fortinet" id="fortinet" <?php if ($this->_tpl_vars['DatosGenerales'][0]['fortinet'] == '1'): ?> checked <?php endif; ?>  value="1"   type="checkbox">  
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">

                                        Unidad Tributaria (UT)
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['unidad_tributaria']; ?>
" type="text" id="unidad_tributaria" name="unidad_tributaria" placeholder="Valor UT"  class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Impuesto
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['nombre_impuesto_principal']; ?>
" type="text" id="nombre_impuesto_principal" name="nombre_impuesto_principal" placeholder="Descripci&oacute;n (Siglas)" class="form-text"/>
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['porcentaje_impuesto_principal']; ?>
" type="text" id="porcentaje_impuesto_principal" name="porcentaje_impuesto_principal" size="10" placeholder="Valor 1" class="form-text"/>
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['iva_a']; ?>
" type="text" id="iva_a" name="iva_a" size="10" placeholder="Valor 2" class="form-text"/>
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['iva_b']; ?>
" type="text" id="iva_b" name="iva_b" size="10" placeholder="Valor 3" class="form-text"/>
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['iva_c']; ?>
" type="text" id="iva_c" name="iva_c" size="10" placeholder="Valor 4" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Nombre Moneda Base
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name='moneda_base' onchange="cargarUrl('buscarAbreviatura.php?miId=' + this.value, 'trasparente');" class="form-text">
                                            <?php echo $this->_tpl_vars['monedaActual']; ?>

                                            <?php $_from = $this->_tpl_vars['divisas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['miItem']):
?>
                                                <option value="<?php echo $this->_tpl_vars['miItem']['id_divisa']; ?>
"><?php echo $this->_tpl_vars['miItem']['Nombre']; ?>
</option>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                        <a href='?opt_menu=1&amp;opt_seccion=104&amp;agregarMoneda=si' style='color:blue'> Editar Monedas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        S&iacute;mbolo Moneda Base
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['moneda']; ?>
" type="text" readonly name="moneda" id="moneda" size="9" style="background:#dddddd" class="form-text"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="label">
                                        Monto Máximo Caja Principal
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['max_caja_principal']; ?>
" type="text"  name="max_caja_principal" id="max_caja_principal" size="9"  class="form-text"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="label">
                                        Monto Máximo Perdida
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['fina_limite_max']; ?>
" type="text"  name="fina_limite_max" id="fina_limite_max" size="9"  class="form-text"/>
                                    </td>
                                </tr>
                                 
                                 
                                
                       <tr>
                          <td valign="top" colspan="3" width="30%" class="tb-head">Cuenta Contable Ingreso</td>
                           <td>
                            <select name="cuenta_credito_fiscal" style="width:200px;" id="cuenta_credito_fiscal">
                                <option value=""> Seleccione una cuenta </option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta_ingreso'],'output' => $this->_tpl_vars['option_output_cuenta_ingreso'],'selected' => $this->_tpl_vars['option_selected_cuenta_ingreso']), $this);?>

                            </select>
                          </td>
                        </tr>
                                
                                
                         <tr>
                          <td valign="top" colspan="3" width="30%" class="tb-head">Cuenta Contable Iva1</td>
                           <td>
                            <select name="cuenta_debito_fiscal" style="width:200px;" id="cuenta_debito_fiscal">
                                <option value=""> Seleccione una cuenta </option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta_iva1'],'output' => $this->_tpl_vars['option_output_cuenta_iva1'],'selected' => $this->_tpl_vars['option_selected_cuenta_iva1']), $this);?>

                            </select>
                          </td>
                        </tr>
                        
                          <tr>
                          <td valign="top" colspan="3" width="30%" class="tb-head">Cuenta Contable Iva2</td>
                           <td>
                            <select name="cuenta_retencion_iva" style="width:200px;" id="cuenta_retencion_iva">
                                <option value=""> Seleccione una cuenta </option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta_iva2'],'output' => $this->_tpl_vars['option_output_cuenta_iva2'],'selected' => $this->_tpl_vars['option_selected_cuenta_iva2']), $this);?>

                            </select>
                          </td>
                        </tr>                       
                        <tr>
                          <td valign="top" colspan="3" width="30%" class="tb-head">Cuenta Contable Iva3</td>
                           <td>
                            <select name="cuenta_retencion_islr" style="width:200px;" id="cuenta_retencion_islr">
                                <option value=""> Seleccione una cuenta </option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta_iva3'],'output' => $this->_tpl_vars['option_output_cuenta_iva3'],'selected' => $this->_tpl_vars['option_selected_cuenta_iva3']), $this);?>

                            </select>
                          </td>
                        </tr>        
                        <tr>
                          <td valign="top" colspan="3" width="30%" class="tb-head">Cuenta Contable Otros Ingresos</td>
                           <td>
                            <select name="cuenta_retencion_tf" style="width:200px;" id="cuenta_retencion_tf">
                                <option value=""> Seleccione una cuenta </option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta_otrosingresos'],'output' => $this->_tpl_vars['option_output_cuenta_otrosingresos'],'selected' => $this->_tpl_vars['option_selected_cuenta_otrosingresos']), $this);?>

                            </select>
                          </td>
                        </tr>        
                        <tr>
                         <td valign="top" colspan="3" width="30%" class="tb-head">Cuenta Contable Perdida</td>
                         <td>
                            <select name="cuenta_retencion_im" style="width:200px;" id="cuenta_retencion_im">
                                <option value=""> Seleccione una cuenta </option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta_perdida'],'output' => $this->_tpl_vars['option_output_cuenta_perdida'],'selected' => $this->_tpl_vars['option_selected_cuenta_perdida']), $this);?>

                            </select>
                         </td>
                        </tr>
                        <tr>
                         <td valign="top" colspan="3" width="30%" class="tb-head">Cuenta Contable CXC</td>
                         <td>
                            <select name="cuenta_cxc" style="width:200px;" id="cuenta_cxc">
                                <option value=""> Seleccione una cuenta </option>
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta_cxc'],'output' => $this->_tpl_vars['option_output_cuenta_cxc'],'selected' => $this->_tpl_vars['option_selected_cuenta_cxc']), $this);?>

                            </select>
                         </td>
                        </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    <!-- TAB3 -->
                    <div id="div_tab3" class="x-hide-display">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="3" class="tb-head">
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="label">
                                        Nombre de Categor&iacute;a 1 de Inventario
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="string_clasificador_inventario1" id="string_clasificador_inventario1" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario1']; ?>
" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Nombre de Categor&iacute;a 2 de Inventario
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="string_clasificador_inventario2" id="string_clasificador_inventario2" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario2']; ?>
" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Nombre de Categor&iacute;a 3 de Inventario
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="string_clasificador_inventario3" id="string_clasificador_inventario3" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario3']; ?>
" class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /TAB3 -->
                    <!-- TAB4 -->
                    <div id="div_tab4" class="x-hide-display">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="3" class="tb-head">
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="label">
                                        Tipo de Facturaci&oacute;n
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <!--select name="tipo_facturacion" id="tipo_facturacion" class="form-text">
                                                                                </select-->
                                        <div id="tipofacturacion">
                                            <input type="radio" id="radio1" name="tipo_facturacion" value="0" <?php if ($this->_tpl_vars['option_selected_facturacion'] == 0): ?>checked<?php endif; ?> /><label for="radio1">Sistema (PDF)</label>
                                            <input type="radio" id="radio2" name="tipo_facturacion" value="1" <?php if ($this->_tpl_vars['option_selected_facturacion'] == 1): ?>checked<?php endif; ?> /><label for="radio2">Impresora Fiscal</label>
                                            <input type="radio" id="radio3" name="tipo_facturacion" value="2" <?php if ($this->_tpl_vars['option_selected_facturacion'] == 2): ?>checked<?php endif; ?> /><label for="radio3">Formato Libre</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Marca
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <!--input type="text" name="impresora_modelo" id="impresora_modelo" value="" class="form-text" style="width:300px;"/>
                                        <span id="error_impresora_modelo">No deje vac&iacute;o</span-->
                                        <!--select id="impresora_marca" name="impresora_marca" disabled class="form-text">
                                            <option>Seleccione Marca</option>
                                            <option value="spooler" selected>S&uacute;per Spooler Fiscal</option>
                                            <option value="bixolon" selected>Bixolon</option>
                                            <option value="hka112" selected>HKA112</option>
                                            <option value="dascon" selected>Tally Dascon</option>
                                            <option value="hasar" selected>Hasar</option>
                                        </select-->
                                        <select name="impresora_marca" id="impresora_marca" disabled class="form-text">
                                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_impresora_marca'],'selected' => $this->_tpl_vars['option_selected_impresora_marca'],'output' => $this->_tpl_vars['option_output_impresora_marca']), $this);?>

                                        </select>
                                        <input type="text" name="impresora_marca_spooler" id="impresora_marca_spooler" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['impresora_marca']; ?>
" placeholder="Marca de Impresora Fiscal" style="display:none" class="form-text" />
                                        <input type="hidden" name="swterceroimp" id="swterceroimp" value="<?php echo $this->_tpl_vars['swterceroimp']; ?>
" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Modelo
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="impresora_modelo" id="impresora_modelo" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['impresora_modelo']; ?>
" disabled class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="label">
                                        Serial
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="impresora_serial" id="impresora_serial" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['impresora_serial']; ?>
" disabled class="form-text" style="width:300px;"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <table style="width: 100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Aceptar" />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
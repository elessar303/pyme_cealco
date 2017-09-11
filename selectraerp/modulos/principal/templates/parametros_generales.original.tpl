<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script type="text/javascript" src="../../libs/js/configtabparametros_generales.js"></script>
        <script type="text/javascript" src="../../libs/js/ajax.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function() {
                    $("#error_impresora_modelo").hide();
                    $("#impresora_modelo").blur(function() {
                        if ($("#impresora_modelo").val() === "") {
                            $("#error_impresora_modelo").show();
                            $("#error_impresora_modelo").focus();
                            return false;
                        }
                    });
                    $("#porcentaje_impuesto_principal, #iva_a,#iva_b,#iva_c,#porcentaje_impuesto2, #porcentaje_impuesto3, #pprovee_sobr_impu_princ, #pclient_sobr_impu_princ").numeric();
                    $(".validadDecimales").blur(function() {
                        /*if($(this).val()!=''&&$(this).val()!='.'&&$(this).val()!='0'){
                         $(this).val(parseFloat($(this).val()));
                         }else{
                         $(this).val("0.00");
                         }*/
                        $(this).val(($(this).val() != '' && $(this).val() != '.' && $(this).val() != '0') ? parseFloat($(this).val()) : "0.00");
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
                    $("#aceptar").click(function() {
                        var tipo = document.getElementById("tipo_facturacion");
                        if (tipo.options[tipo.selectedIndex].value === "1") {
                            if ($("#impresora_modelo").val() === "" || $("#impresora_marca").val() === "" || $("#impresora_serial").val() === "") {
                                alert("Proporcione Datos de Impresora Fiscal");
                                return false;//Evita que el formulario sea enviado
                            }
                            else {
                                //document.formulario.submit();//No funciona
                                var dataString = 'nombre_empresa=' + $("#nombre_empresa").val() + '&impresora_modelo=' + $("#impresora_modelo").val() + '&impresora_marca=' + $("#impresora_marca").val() + '&impresora_serial=' + $("#impresora_serial").val();
                                //alert (dataString);return false;
                                $.ajax({
                                    /*type: "POST",
                                     url: "../parametros_generales.php",
                                     data: dataString,
                                     success: function() {
                                     }*/
                                });
                            }
                        }
                        else {
                            $("#formulario").submit();
                        }
                    });
                });
                function habilitarParametrosImpresoraFiscal() {
                    var tipo = document.getElementById("tipo_facturacion");
                    document.getElementById("tab5").className = "tab " + ((tipo.options[tipo.selectedIndex].value === "1") ? "visible" : "oculto");
                }
                function validarParametrosFiscales() {
                    /*Esta funcion fue sustituida por un método JQuery arriba*/
                    var tipo = document.getElementById("tipo_facturacion");
                    if (tipo.options[tipo.selectedIndex].value === "1") {
                        var impresora_modelo = document.getElementById("impresora_modelo");
                        var impresora_marca = document.getElementById("impresora_marca");
                        var impresora_serial = document.getElementById("impresora_serial");
                        if (impresora_modelo.value === "" || impresora_marca.value === "" || impresora_serial.value === "") {
                            alert("Proporcione Datos de Impresora Fiscal");
                            return false;
                        }
                        else {
                            //alert(document.formulario.valueOf());
                        }
                    }
                    else {
                        document.formulario.submit();
                    }
                }
                //]]>
            </script>
            <!-- Stylo Tab -->
            <style type="text/css">
                .sobreobjeto{
                    background-color:#d7d7d7;
                }
                .mouseOVER{
                    background-color:#ededed;
                }
                .tab{
                    text-align:left;
                    background-color:#d0d0d0;
                    padding-left:10px;
                    padding-right:10px;
                    font-size:11px;
                    font-family: arial;
                    color:#a0a0a0;
                    cursor: pointer;
                    width:auto;
                    border-left: 1px solid #8d8f91;
                    border-right: 1px solid #8d8f91;
                    border-top: 1px solid #8d8f91;
                }
                .sobreboton{
                    background-color:#bec0c1;
                }
                .click{
                    background: url('../../../includes/imagenes/tb_tit.jpg') repeat-x;
                    color:black;
                    border-left: 1px solid #8d8f91;
                    border-right: 1px solid #8d8f91;
                    border-top: 1px solid #8d8f91;
                }
                #contenedorTAB{
                    background-color: #e3ebf1;
                    /*-webkit-border-radius: 5px;*/
                    border-radius: 5px;
                    border: 1px solid #adafb0;
                    padding: 2px;
                }
                #tabs{
                    margin-top:15px;
                }
                .oculto{
                    display: none;
                }
                .visible{
                    display: table-cell;
                }
            </style>
            <!-- Stylo Tab -->
        {/literal}
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="cod_empresa" value="{$DatosGenerales[0].cod_empresa}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <table style="width:100%;">
                <tbody>
                    <tr>
                        <td class="tb-tit">
                            <img src="../../../includes/imagenes/10.png" width="20" height="20" align="absmiddle"/><strong>Par&aacute;metros Generales</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="tabs">
                <table style="margin-left:20px;">
                    <tr style="height:25px;">
                        <td id="tab1" class="tab">
                            <img src="../../../includes/imagenes/12.png" width="20" height="20" style="vertical-align: middle;"/><b>Informaci&oacute;n de la Empresa</b>
                        </td>
                        <td>&nbsp;&nbsp;</td>
                        <td id="tab2" class="tab">
                            <img src="../../../includes/imagenes/4.png" width="20" height="20" align="absmiddle"/><b>Financieros</b>
                        </td>
                        <td>&nbsp;&nbsp;</td>
                        <td id="tab4" class="tab">
                            <img src="../../../includes/imagenes/64.png" width="20" height="20" align="absmiddle"/><b>Clasificadores de Inventario</b>
                        </td>
                        <td>&nbsp;&nbsp;</td>
                        <td id="tab5" class="tab {if $DatosGenerales[0].tipo_facturacion eq 1}visible{else}oculto{/if}">
                            <img src="../../../includes/imagenes/64.png" width="20" height="20" align="absmiddle"/><b>Impresora Fiscal</b>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="contenedorTAB">
                <!-- TAB1 -->
                <div id="div_tab1">
                    <table style="width: 100%">
                        <tr>
                            <td colspan="3" class="tb-head" style="text-align:center;">
                                COMPLETE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Nombre Empresa
                            </td>
                            <td>
                                <input type="text" name="nombre_empresa" id="nombre_empresa" value="{$DatosGenerales[0].nombre_empresa}" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Direcci&oacute;n
                            </td>
                            <td>
                                <textarea id="direccion" name="direccion" class="form-text" style="width:300px;">{$DatosGenerales[0].direccion}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Ciudad
                            </td>
                            <td>
                                <input type="text" id="ciudad" name="ciudad" value="{$DatosGenerales[0].ciudad}" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Tel&eacute;fonos
                            </td>
                            <td>
                                <input type="text" name="telefonos" id="telefonos" value="{$DatosGenerales[0].telefonos}" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Nombre de ID Fiscal 1
                            </td>
                            <td>
                                <input type="text" value="{$DatosGenerales[0].id_fiscal}" name="id_fiscal" id="id_fiscal" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                N&uacute;mero de ID Fiscal 1
                            </td>
                            <td>
                                <input type="text" id="rif" value="{$DatosGenerales[0].rif}" name="rif" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Nombre de ID Fiscal 2
                            </td>
                            <td>
                                <input type="text" value="{$DatosGenerales[0].id_fiscal2}" id="id_fiscal2" name="id_fiscal2" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                N&uacute;mero de ID Fiscal 2
                            </td>
                            <td>
                                <input value="{$DatosGenerales[0].nit}" type="text" name="nit" id="nit" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <!--tr>
                            <td valign="top"  colspan="2" width="20%" class="tb-head" >
                                A&ntilde;o del periodo
                            </td>
                            <td>
                                <input value="{$DatosGenerales[0].periodo_fiscal}" type="text" readonly  name="periodo_fiscal" id="periodo_fiscal" size="9" style='background:#dddddd'>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="2" width="20%" class="tb-head" >
                                Servicio para CXP a medicos:
                            </td>
                            <td>
                                <select name="serv" id="serv">
                                    <option>seleccione</option>
                        {html_options values=$option_values_servicio selected=$option_selected_servicio output=$option_output_servicio}
                    </select>
                </td>
            </tr-->
                        <!--<tr>
                                <td colspan="4" class="tb-head" align="center">
                                  Correlativos
                              </td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="2" width="20%" class="tb-head" >
                                Impuesto Valor Agregado (I.V.A.)
                            </td>
                            <td >
                                <input value="{$DatosGenerales[0].num_iva}" type="text" readonly  name="moneda" id="moneda" size="9" style='background:#dddddd'>
                            </td>
                        </tr>-->
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Logo Reportes
                            </td>
                            <td>
                                <input type="file" name="img_izq" id="img_izq" class="form-text"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Logo Reportes
                            </td>
                            <td>
                                <input type="file" name="img_der" id="img_der" class="form-text"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /TAB1 -->
                <!-- TAB2 -->
                <div id="div_tab2">
                    <table style="width: 100%">
                        <!--tr>
                            <td colspan="4" class="tb-head" align="center">
                                &nbsp;
                            </td>
                        </tr-->
                        <tr>
                            <td colspan="3" class="tb-head" align="center">
                                T&Iacute;TULOS DE PRECIOS
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Precio 1
                            </td>
                            <td>
                                <input type="text" value="{$DatosGenerales[0].titulo_precio1}" name="titulo_precio1" id="titulo_precio1" placeholder="Descripci&oacute;n Precio 1" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Precio 2
                            </td>
                            <td>
                                <input value="{$DatosGenerales[0].titulo_precio2}" type="text" id="titulo_precio2" name="titulo_precio2" placeholder="Descripci&oacute;n Precio 2" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Precio 3
                            </td>
                            <td>
                                <input value="{$DatosGenerales[0].titulo_precio3}" type="text" id="titulo_precio3" name="titulo_precio3" placeholder="Descripci&oacute;n Precio 3" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="tb-head" align="center">
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Indique Cu&aacute;l es el Precio Menor
                            </td>
                            <td>
                                <select name="precio_menor" id="precio_menor" class="form-text">
                                    <!--option {if $DatosGenerales[0].precio_menor eq 1}selected{/if} value="1">Precio 1</option>
                                    <option {if $DatosGenerales[0].precio_menor eq 2}selected{/if} value="2">Precio 2</option>
                                    <option {if $DatosGenerales[0].precio_menor eq 3}selected{/if} value="3">Precio 3</option-->
                                    {html_options values=$option_values_precio selected=$option_selected_precio output=$option_output_precio}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Unidad Tributaria (UT)
                            </td>
                            <td>
                                <input value="{$DatosGenerales[0].unidad_tributaria}" type="text" id="unidad_tributaria" name="unidad_tributaria" placeholder="Valor UT"  class="form-text"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Impuesto
                            </td>
                            <td>
                                <input value="{$DatosGenerales[0].nombre_impuesto_principal}" type="text" id="nombre_impuesto_principal" name="nombre_impuesto_principal" placeholder="Descripci&oacute;n (Siglas)" class="form-text"/>
                                <input value="{$DatosGenerales[0].porcentaje_impuesto_principal}" type="text" id="porcentaje_impuesto_principal" name="porcentaje_impuesto_principal" placeholder="Valor 1" class="form-text"/>
                                <input value="{$DatosGenerales[0].iva_a}" type="text" id="iva_a" name="iva_a" placeholder="Valor 2" class="form-text"/>
                                <input value="{$DatosGenerales[0].iva_b}" type="text" id="iva_b" name="iva_b" placeholder="Valor 3" class="form-text"/>
                                <input value="{$DatosGenerales[0].iva_c}" type="text" id="iva_c" name="iva_c" placeholder="Valor 4" class="form-text"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Tipo de Facturaci&oacute;n
                            </td>
                            <td>
                                <select name="tipo_facturacion" id="tipo_facturacion" class="form-text" onchange="javascript:habilitarParametrosImpresoraFiscal();">
                                    <!--option {if $DatosGenerales[0].tipo_facturacion eq 0} selected {/if} value="0">Sistema (PDF)</option>
                                    <option {if $DatosGenerales[0].tipo_facturacion eq 1} selected {/if} value="1">Impresora Fiscal</option>
                                    <option {if $DatosGenerales[0].tipo_facturacion eq 2} selected {/if} value="2">Formato Libre</option-->
                                    {html_options values=$option_values_facturacion selected=$option_selected_facturacion output=$option_output_facturacion}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Nombre Moneda Base
                            </td>
                            <td>
                                <select name='moneda_base' onchange="cargarUrl('buscarAbreviatura.php?miId=' + this.value, 'trasparente');" class="form-text">
                                    {$monedaActual}
                                    {foreach from =$divisas key=i item=miItem}
                                        <option value="{$miItem.id_divisa}">
                                            {$miItem.Nombre}
                                        </option>
                                    {/foreach}
                                </select>
                                <a href='?opt_menu=1&amp;opt_seccion=104&amp;agregarMoneda=si' style='color:blue'> Editar Monedas</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                S&iacute;mbolo Moneda Base
                            </td>
                            <td>
                                <input value="{$DatosGenerales[0].moneda}" type="text" readonly name="moneda" id="moneda" size="9" style="background:#dddddd" class="form-text"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /TAB2 -->
                <!-- TAB4 -->
                <div id="div_tab4">
                    <table style="width: 100%">
                        <tr>
                            <td colspan="3" class="tb-head" align="center">
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Nombre de Categor&iacute;a 1 de Inventario
                            </td>
                            <td>
                                <input type="text" name="string_clasificador_inventario1" id="string_clasificador_inventario1" value="{$DatosGenerales[0].string_clasificador_inventario1}" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Nombre de Categor&iacute;a 2 de Inventario
                            </td>
                            <td>
                                <input type="text" name="string_clasificador_inventario2" id="string_clasificador_inventario2" value="{$DatosGenerales[0].string_clasificador_inventario2}" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Nombre de Categor&iacute;a 3 de Inventario
                            </td>
                            <td>
                                <input type="text" name="string_clasificador_inventario3" id="string_clasificador_inventario3" value="{$DatosGenerales[0].string_clasificador_inventario3}" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /TAB4 -->
                <!-- TAB5 -->
                <div id="div_tab5">
                    <table style="width: 100%">
                        <tr>
                            <td colspan="3" class="tb-head" align="center">
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;" >
                                Modelo
                            </td>
                            <td>
                                <input type="text" name="impresora_modelo" id="impresora_modelo" value="{$DatosGenerales[0].impresora_modelo}" class="form-text" style="width:300px;"/>
                                <span id="error_impresora_modelo">No deje vacio</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Marca
                            </td>
                            <td>
                                <input type="text" name="impresora_marca" id="impresora_marca" value="{$DatosGenerales[0].impresora_marca}" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tb-head" style="width:20%; padding-left:10px; vertical-align: central;">
                                Serial
                            </td>
                            <td>
                                <input type="text" name="impresora_serial" id="impresora_serial" value="{$DatosGenerales[0].impresora_serial}" class="form-text" style="width:300px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /TAB5 -->
            </div>
            <table style="width: 100%">
                <tbody>
                    <tr class="tb-tit" style="text-align: right;">
                        <td>
                            <!--input type="submit" name="aceptar" id="aceptar" value="Aceptar" class="form-text" onclick="javascript:validarParametrosFiscales();"/-->
                            <input type="submit" name="aceptar" id="aceptar" value="Aceptar" class="form-text"/>
                            <input type="button" name="cancelar" value="Cancelar" class="form-text" onclick="javascript:document.location.href = '?opt_menu={$smarty.get.opt_menu}';"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>
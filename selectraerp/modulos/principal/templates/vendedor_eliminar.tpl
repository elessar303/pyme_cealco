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
        {assign var=name_form value="vendedor_eliminar"}
        {include file="snippets/header_form.tpl"}
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){
                    $("input, selected").attr("readonly","readonly");
                    $("#aceptar").removeAttr("readonly");
                    $("input[name='aceptar'], input[name='cancelar']").button();//Coloca estilo JQuery
                });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral">
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden"  name="cod_empresa" value="{$DatosGenerales[0].cod_empresa}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <div id="contenedorTAB">
                    <div id="div_tab1" class="x-hide-display">
                        <table style="width: 100%">
                            <thead>

                                <tr>
                                    <th colspan="4" class="tb-head" style="text-align: center;">
                                        COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        Codigo
                                    </td>
                                    <td >
                                        <input type="text" value="{$datosvendedor[0].cod_vendedor}"  name="cod_vendedor" id="cod_vendedor" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        Nombre **
                                    </td>
                                    <td >
                                        <input type="text" name="nombre" value="{$datosvendedor[0].nombre}" id="nombre" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        Dirección
                                    </td>
                                    <td >
                                        <input type="text" name="direccion1" id="direccion1" value="{$datosvendedor[0].direccion1}" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        Direccion 2
                                    </td>
                                    <td >
                                        <input type="text" name="direccion2" id="direccion2"  value="{$datosvendedor[0].direccion2}"  size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        Telefonos
                                    </td>
                                    <td >
                                        <input type="text" name="telefonos"  value="{$datosvendedor[0].telefonos}" id="telefonos" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        Fax
                                    </td>
                                    <td >
                                        <input type="text" name="fax" value="{$datosvendedor[0].fax}" id="fax" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        E-Mail
                                    </td>
                                    <td >
                                        <input type="text"  id="email" name="email"  value="{$datosvendedor[0].email}" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        Clase
                                    </td>
                                    <td >
                                        <input type="text" name="clase"  value="{$datosvendedor[0].clase}" id="clase" size="60">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="div_tab2" class="x-hide-display">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td colspan="4" class="tb-head" style="text-align: center;">
                                        COMISIÓN POR VENTAS
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % Por debajo del minimo
                                    </td>
                                    <td >
                                        <input type="text" value="{$datosvendedor[0].venta_x_debajo_minimo}" name="venta_x_debajo_minimo" class="validadDecimales" value="0.00" id="venta_x_debajo_minimo" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % a {$DatosGenerales[0].titulo_precio1}
                                    </td>
                                    <td >
                                        <input type="text" name="venta_a_precio1" value="{$datosvendedor[0].venta_a_precio1}" class="validadDecimales" value="0.00" id="venta_a_precio1" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % a {$DatosGenerales[0].titulo_precio2}
                                    </td>
                                    <td >
                                        <input type="text" name="venta_a_precio2" value="{$datosvendedor[0].venta_a_precio2}"  class="validadDecimales" value="0.00" id="venta_a_precio2" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % a {$DatosGenerales[0].titulo_precio3}
                                    </td>
                                    <td >
                                        <input type="text" name="venta_a_precio3" value="{$datosvendedor[0].venta_a_precio3}"  class="validadDecimales" value="0.00" id="venta_a_precio3" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % por Servicio
                                    </td>
                                    <td >
                                        <input type="text" name="venta_x_servicio"  value="{$datosvendedor[0].venta_x_servicio}"  class="validadDecimales" value="0.00" id="venta_x_servicio" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % geréricos
                                    </td>
                                    <td >
                                        <input type="text" name="venta_gerericos"  value="{$datosvendedor[0].venta_gerericos}"   class="validadDecimales" value="0.00" id="venta_gerericos" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="tb-head" align="center">
                                        COMISIÓN POR COBRANZA
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % Por debajo del minimo
                                    </td>
                                    <td >
                                        <input type="text" name="comision_x_debajo_minimo" value="{$datosvendedor[0].comision_x_debajo_minimo}"  class="validadDecimales" value="0.00" id="comision_x_debajo_minimo" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % a {$DatosGenerales[0].titulo_precio1}
                                    </td>
                                    <td >
                                        <input type="text" name="comision_a_precio1" value="{$datosvendedor[0].comision_a_precio1}"  class="validadDecimales" value="0.00" id="comision_a_precio1" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % a {$DatosGenerales[0].titulo_precio2}
                                    </td>
                                    <td >
                                        <input type="text" name="comision_a_precio2" value="{$datosvendedor[0].comision_a_precio2}"  class="validadDecimales" value="0.00" id="comision_a_precio2" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % a {$DatosGenerales[0].titulo_precio3}
                                    </td>
                                    <td >
                                        <input type="text" name="comision_a_precio3"  value="{$datosvendedor[0].comision_a_precio3}"  class="validadDecimales" value="0.00" id="comision_a_precio3" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % por Servicio
                                    </td>
                                    <td >
                                        <input type="text" name="comision_x_servicio"   value="{$datosvendedor[0].comision_x_servicio}"  class="validadDecimales" value="0.00" id="comision_x_servicio" size="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                                        % geréricos
                                    </td>
                                    <td >
                                        <input type="text" name="comision_gerericos"  value="{$datosvendedor[0].comision_gerericos}"  class="validadDecimales" value="0.00" id="comision_gerericos" size="60">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="div_tab3" class="x-hide-display">
                        <table style="width:100%">
                            <tbody>
                                <tr>
                                    <td colspan="2">¿Usa Tabla de Cobros?
                                        <select name="comision_tabla_de_cobros" id="comision_tabla_de_cobros">
                                            <option {if $datosvendedor[0].comision_tabla_de_cobros eq 1 }selected {/if} value="1">Si</option>
                                            <option {if $datosvendedor[0].comision_tabla_de_cobros eq 0 }selected {/if}value="0">No</option>
                                        </select>
                                    </td>
                                    <td height="50">Tipo
                                        <select name="tipo_comision" id="tipo_comision">
                                            <option {if $datosvendedor[0].tipo_comision eq "Monto" } selected {/if} value="Monto">Monto</option>
                                            <option {if $datosvendedor[0].tipo_comision eq "Porcentaje" } selected {/if} value="Porcentaje">Porcentaje</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Rango de Cobro 1 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="rancosdesde1" id="rancosdesde1" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"  name="rancoshasta1" id="rancoshasta1" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales"  value="0.00" name="factor1" id="factor1"  size="30"></td>
                                </tr>
                                <tr>
                                    <td><b>Rango de Cobro 2 Desde</b> <input class="validadDecimales" value="0.00"  type="text" name="rancosdesde2" id="rancosdesde2" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"   name="rancoshasta2" id="rancoshasta2" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales"  value="0.00" name="factor2" id="factor2"  size="30"></td>
                                </tr>
                                <tr>
                                    <td><b>Rango de Cobro 3 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="rancosdesde3" id="rancosdesde3" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"   name="rancoshasta3" id="rancoshasta3" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales" value="0.00"  name="factor3" id="factor3"  size="30"></td>
                                </tr>
                                <tr>
                                    <td><b>Rango de Cobro 4 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="rancosdesde4" id="rancosdesde4" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"   name="rancoshasta4" id="rancoshasta4" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales"  value="0.00" name="factor4" id="factor4"  size="30"></td>
                                </tr>
                                <tr>
                                    <td><b>Rango de Cobro 5 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="rancosdesde5" id="rancosdesde5" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"   name="rancoshasta5" id="rancoshasta5" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales" value="0.00" name="factor5" id="factor5"  size="30"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="div_tab4" class="x-hide-display">
                        <table style="width:100%">
                            <tbody>
                                <tr>
                                    <td colspan="2">¿Usa Tabla de Ventas?
                                        <select name="comision_tabla_de_cobrosven" id="comision_tabla_de_cobrosven">
                                            <option  {if $datosvendedor[0].comision_tabla_de_cobrosven eq 1 } selected {/if} value="1">Si</option>
                                            <option  {if $datosvendedor[0].comision_tabla_de_cobrosven eq 0 } selected {/if} value="0">No</option>
                                        </select>
                                    </td>
                                    <td height="50">Tipo
                                        <select name="tipo_comisionven" id="tipo_comisionven">
                                            <option {if $datosvendedor[0].comision_tabla_de_cobrosven eq "Monto" } selected {/if}  value="Monto">Monto</option>
                                            <option {if $datosvendedor[0].comision_tabla_de_cobrosven eq "Porcentaje" } selected {/if}  value="Porcentaje">Porcentaje</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Rango de ventas 1 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="ranvendesde1" id="ranvendesde1" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"  name="ranvenhasta1" id="ranvenhasta1" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales"  value="0.00" name="factor1ven" id="factor1ven"  size="30"></td>
                                </tr>
                                <tr>
                                    <td><b>Rango de ventas 2 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="ranvendesde2" id="ranvendesde2" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"  name="ranvenhasta2" id="ranvenhasta2" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales"  value="0.00" name="factor2ven" id="factor2ven"  size="30"></td>
                                </tr>
                                <tr>
                                    <td><b>Rango de ventas 3 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="ranvendesde3" id="ranvendesde3" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"  name="ranvenhasta3" id="ranvenhasta3" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales"  value="0.00" name="factor3ven" id="factor3ven"  size="30"></td>
                                </tr>
                                <tr>
                                    <td><b>Rango de ventas 4 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="ranvendesde4" id="ranvendesde4" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"  name="ranvenhasta4" id="ranvenhasta4" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales"  value="0.00" name="factor4ven" id="factor4ven"  size="30"></td>
                                </tr>
                                <tr>
                                    <td><b>Rango de ventas 5 Desde</b> <input class="validadDecimales" value="0.00" type="text" name="ranvendesde5" id="ranvendesde5" size="30"></td>
                                    <td><b>Hasta</b> <input type="text" class="validadDecimales" value="0.00"  name="ranvenhasta5" id="ranvenhasta5" size="30"></td>
                                    <td><b>Factor</b> <input type="text" class="validadDecimales"  value="0.00" name="factor5ven" id="factor5ven"  size="30"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <table style="width: 100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" id="aceptar" name="aceptar" value="Eliminar Vendedor"/>
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
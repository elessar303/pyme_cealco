<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script src="../../libs/js/cxp_facturas_compra.js" type="text/javascript"></script>
        <script src="../../libs/js/cxp_facturas.js" type="text/javascript"></script>
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <table style="width:100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:900px;">
                                        <span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" /> {$subseccion[0].descripcion} </span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=facturasCXP&amp;cod={$cod}&amp;cod2={$edoCta}'">
                                            <tr>
                                                <td style="padding: 0px; text-align:right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align:left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <div id="datosGral" class="x-hide-display" >
                <table style="width: 600px;">
                    <tr>
                        <td style="padding:2px; text-align:center; width:150px;">
                            N&uacute;mero de Fac/NC
                            <input size="14" type="text" name="num_fac" id="num_fac" onblur="javascript:calcular_iva_fact()"/>
                        </td>
                        <td style="text-align:center; width:150px;">
                            N&uacute;mero de Control
                            <input size="14" type="text" name="num_cont" id="num_cont" onblur="javascript:calcular_iva_fact()"/>
                        </td>
                        <td style="text-align:center; width:150px;">
                            Fecha de Fac/NC
                            <input size="14" type="text" name="fecha_fac" id="fecha_fac" value="{$hoy}" />
                            {literal}
                                <script type="text/javascript">//<![CDATA[
                                var cal = Calendar.setup({
                                        onSelect: function(cal) { cal.hide() }
                                });
                                cal.manageFields("fecha_fac", "fecha_fac", "%d/%m/%Y");
                                //]]>
                                </script>
                            {/literal}
                        </td>
                        <td style="text-align:center; width:150px;">
                            Fecha de Recepci&oacute;n
                            <input size="14" type="text" name="fecha_fac_rec" id="fecha_fac_rec" value="{$hoy}" />
                            {literal}
                                <script type="text/javascript">//<![CDATA[
                                var cal = Calendar.setup({
                                        onSelect: function(cal) { cal.hide() }
                                });
                                cal.manageFields("fecha_fac_rec", "fecha_fac_rec", "%d/%m/%Y");
                                //]]>
                                </script>
                            {/literal}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:2px; text-align:center; width:150px;">
                            Monto Base
                            <input size="14" type="text" name="montoBase" id="montoBase" value="{$totalBase}" onblur="javascript:calcular_iva_fact()"/>
                        </td>
                        <td style="text-align:center; width:150px;">
                            Monto Exento
                            <input size="14" type="text" name="montoExento" id="montoExento" value="{$totalExc}" onblur="javascript:calcular_iva_fact()"/>
                        </td>
                        <td style="text-align:center; width:150px;">
                            Anticipo&nbsp;&nbsp;&nbsp;&nbsp;
                            <input size="14" type="text" name="anticipo" id="anticipo" onclick="javascript:cargarAnticipo(), winAnt.show();" onblur="javascript:calcular_iva_fact()" value="0.00"/>
                        </td>
                        <td style="text-align:center; width:150px;">
                            Monto Total con I.V.A.
                            <input size="14" type="text" name="montoConIva" id="montoConIva" value="{$totalConIva}"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:2px; text-align:center; width:150px;">
                            Monto Total sin I.V.A.
                            <input size="14" type="text" name="montoSinIva" id="montoSinIva" value="{$totalSinIva}"/>
                        </td>
                        <td style="text-align:center; width:150px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nota de Cr&eacute;dito?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!-- <input name="optNc" id="optNc" type="checkbox" onchange="javascript:habilitarFacturaAfectada()" value="NC"> -->
                            <select name="optNc" id="optNc" onchange="javascript:habilitarFacturaAfectada()">
                                <option value="FAC">Factura</option>
                                <option value="NC">Nota de Cr&eacute;dito</option>
                                <option value="ND">Nota de D&eacute;bito</option>
                            </select>
                        </td>
                        <td style="text-align:center; width:150px;">
                            Factura Afectada
                            <select name="facturaAfectada" id="facturaAfectada" disabled>
                                <option value="">Seleccione</option>
                                {html_options values=$option_values_fac output=$option_output_fac}
                            </select>
                        </td>
                        <td style="text-align:center; width:150px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Libro de Compras?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="libroCompras" id="libroCompras" type="checkbox" checked value="1"/>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="datosIva" class="x-hide-display">
                <table>
                    <tr style="border: 1px solid lightblue;" >
                        <td style="padding:2px; text-align: center; width: 150px;">
                            <input name="optIva" id="optIva" type="checkbox" onchange="javascript:calcular_retiva_fact()"/>
                        </td>
                        <td style="padding:3px; text-align: center;" colspan="3">
                            <select name="ivas" id="ivas" onchange="javascript:calcular_retiva_fact()">
                                {html_options values=$option_values_iva output=$option_output_iva selected=$option_selected_impuesto}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:2px; text-align: center; width: 150px;">
                            Porcentaje I.V.A.
                            <input size="14" name="porcentajeIva" type="text" id="porcentajeIva" value="{$mayorPiva}" onblur="javascript:calcular_iva_fact()"/>
                        </td>
                        <td style="padding:2px; text-align: center; width: 150px;">
                            Monto I.V.A.
                            <input size="18" readonly name="montoIva" type="text" class="" id="montoIva" value="{$montoIva}" />
                        </td>
                        <td style="padding:2px; text-align: center; width: 150px;">
                            Porcentaje Retenido
                            <input size="14" readonly name="porcentajeRetIva" type="text" class="" id="porcentajeRetIva" />
                        </td>
                        <td style="padding:2px; text-align: center; width: 150px;">
                            Monto Retenido
                            <input size="14" readonly name="montoRetenido" type="text" class="" id="montoRetenido" value="0.00"/>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="datosIslr" class="x-hide-display">
                <table style="width: 600px;">
                
                		<!--
                    <tr style="border: 1px solid lightblue;" >
                        <td style="padding: 2px; text-align: center; width: 150px;">
                            <input name="optISLR" id="optISLR" type="checkbox" onchange="javascript:calcular_retislr_fact()"/>
                        </td>
                        <td style="padding:3px; text-align: center;" colspan="3">
                            <select name="islrs" id="islrs" onchange="javascript:calcular_retislr_fact()">
                                {html_options values=$option_values_islr output=$option_output_islr selected=$option_selected_impuesto_islr}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:2px; text-align: center; width: 250px;">
                            Monto Base Serv.
                            <input size="14" name="montoServ" type="text" id="montoServ" title="{$campos[6]}" value="{$campos[6]}" onblur="javascript:calcular_retislr_fact('{$i}')"/>
                        </td>
                        <td style="padding:2px; text-align: center; width: 150px;">
                            Servicio
                            <input size="23" readonly name="Serv" type="text" id="Serv" title="{$campos[4]}" value="{$campos[4]}"/>
                        </td>
                        <td style="padding:2px; text-align: center; width: 150px;">
                            Descripci&oacute;n
                            <input  size="45" readonly name="desc" type="text" id="desc" title="{$campos[1]}" value="{$campos[1]}"/>
                        </td>
                        <td style="padding:2px; text-align: center; width: 150px;">
                            Porcentaje Retenido
                            <input size="14" readonly name="porcentajeRetIslr" type="text" class="" id="porcentajeRetIslr" value="{$campos[2]}"/>
                        </td>
                        <td style="padding:2px; text-align: center; width: 150px;">
                            Monto Retenido
                            <input type="text" size="14" readonly name="montoRetenidoIslr" class="" id="montoRetenidoIslr" value="{$campos[0]|number_format:2:".":""}">
                            <input type="hidden" name="codIslr" id="codIslr" value="{$campos[3]}"/>
                            <input type="hidden" name="codServ" id="codServ" value="{$campos[5]}"/>
                            <input type="hidden" name="codEntidad" id="codEntidad" value="{$campos[7]}"/>
                            <input type="hidden" name="codigo" id="codigo" value="{$i}"/>
                        </td>
                    </tr>-->
                    {foreach from=$retIslr key=i item=campos}
                        <tr>
                            <td style="padding:2px; text-align: center; width: 150px;">
                                Monto base Serv.
                                <input size="14" name="montoServ{$i}" type="text" id="montoServ{$i}" title="{$campos[6]}" value="{$campos[6]}" onblur="javascript:calcular_retislr_fact('{$i}')"></td>
                            <td style="padding:2px; text-align: center; width: 150px;">
                                Servicio
                                <input size="23px" readonly name="Serv{$i}" type="text" id="Serv{$i}" title="{$campos[4]}" value="{$campos[4]}"></td>
                            <td style="padding:2px; text-align: center; width: 150px;">
                                Descripci&oacute;n
                                <input  size="45px" readonly  name="desc" type="text" id="desc" title="{$campos[1]}" value="{$campos[1]}"></td>
                            <td style="padding:2px; text-align: center; width: 150px;">
                                % Retenido
                                <input size="14" readonly name="porcentajeRetIslr{$i}" type="text" class="" id="porcentajeRetIslr{$i}" value="{$campos[2]}">
                            </td>
                            <td style="padding:2px; text-align: center; width: 150px;">
                                Monto Retenido
                                <input type="text" size="14" readonly name="montoRetenidoIslr{$i}" class="" id="montoRetenidoIslr{$i}" value="{$campos[0]|number_format:2:".":""}">
                                <input type="hidden" name="codIslr{$i}" id="codIslr{$i}" value="{$campos[3]}"/>
                                <input type="hidden" name="codServ{$i}" id="codServ{$i}" value="{$campos[5]}"/>
                                <input type="hidden" name="codEntidad{$i}" id="codEntidad{$i}" value="{$campos[7]}"/>
                                <input type="hidden" name="codigo{$i}" id="codigo{$i}" value="{$i}"/>
                            </td>
                        </tr>
                    {/foreach}
                        <input type="hidden" name="cantidad" id="cantidad" value="{$i}"/>
                        <input type="hidden" name="idCompra" id="idCompra" value="{$idCompra}"/>
                        <input type="hidden" name="edoCta" id="edoCta" value="{$edoCta}"/>
                        <input type="hidden" name="cod" id="cod" value="{$cod}"/>
                    </table>
                    <table style="margin: 30px 0 0 0; width: 100%;">
                        <tr style="text-align: center;">
                            <td>
                                <span style="font-size: 16px; color: red;"> Total A Pagar: </span>
                                <input type="text" style="border:0px; background-color:#dfe8f6; height: 30px; font-size: 20px; color: red;" size="20" name="totalPagar" id="totalPagar" value="{$totalConIva|number_format:2:".":""}">
                            </td>
                        </tr>
                    </table>
                    <table width="100%" border="0">
                    <tbody>
                    <tr class="tb-tit" align="right">
                    <td>
                    <input name="cantidad" type="hidden" id="cantidad" value="{$i}">
                    <input type="submit" name="aceptar" id="aceptar" value="Guardar">
                    </td>
                    </tr>
                    </tbody>
                    </table>
                </div>
            </form>
            <div id="anticipox" class="x-hide-display">
                <p><b>Anticipos</b></p>
                <div class="grid">
                    <table style="width:100%;" class="lista">
                        <thead>
                            <tr >
                                <th class="tb-tit">Id</th>
                                <th class="tb-tit">Descripci&oacute;n</th>
                                <th class="tb-tit">Monto</th>
                                <th class="tb-tit">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="anticipos">
                        </tbody>
                    </table>
                </div>
                <p>
                    <b>Total:</b>
                    <input type="hidden" name="cantAnt" id="cantAnt"/>
                    <input type="text" value="0.00" readonly name="totalAtn" id="totalAtn"/>
                </p>
            </div>
        </body>
    </html>
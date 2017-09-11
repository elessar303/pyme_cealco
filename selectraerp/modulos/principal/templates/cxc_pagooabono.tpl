<script src="../../libs/js/cxc_edocuenta.js" type="text/javascript"></script>
<script src="../../libs/js/cxc_pagooabono.js" type="text/javascript"></script>
<link href="../../libs/css/nueva_factura.css" media="screen" rel="stylesheet" type="text/css" />

<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="DatosCliente" value="">
<input type="hidden" name="codcliente" value="{$smarty.get.cod}">
<input type="hidden" name="cod_edocuenta" value="{$smarty.get.cod2}">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
<input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">
  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>



                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>


<!--<Datos del cliente y vendedor>-->
<div  style="background-color:#ffffff; border: 1px solid #ededed;-moz-border-radius: 7px;padding:5px; margin-top:0.3%;  font-size: 13px; ">
<img align="absmiddle" src="../../libs/imagenes/ico_user.gif">
<span style="font-family:'Verdana';"><b>Cliente: </b></span>
<span style="font-family:'Verdana';">{$datacliente[0].nombre}</span>
<span style="font-family:'Verdana';"><b> Factura: </b></span>
<span style="font-family:'Verdana';">{$factura[0].cod_factura}</span>
<input type="hidden" name="id_cliente" value="{$datacliente[0].id_cliente}">
<input type="hidden" name="id_fiscal" value="{$datacliente[0].rif}">
</div>
<!--</Datos del cliente y vendedor>-->

<div style="border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px; -moz-border-radius-bottomright: 7px; -moz-border-radius-bottomleft: 7px; margin-top: 0.3%; margin-right: 0.3%; font-size: 13px;">


<div style="float: left; margin-right: 20px;">
<b>Debitos</b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: #166a09;"><b>{$cabecera_estadodecuenta[0].debito}</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Creditos </b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: red;"><b>{$cabecera_estadodecuenta[0].credito}</b></div>
</div>

<div style="margin-right: 20px;">
<b>Saldo Pendiente</b>
<div  style="font-size: 15px; color: red;"><b>{$cabecera_estadodecuenta[0].saldo_pendiente}</b></div>
</div>
</div>



<!--</contenedor factura paso 2>-->
<div id="contenedorTAB_factura_paso2">

    <div id="divTotalizarFactura">

<div id="tabs">


{literal}
<script>
    $(document).ready(function(){
        $("#tab2").trigger("click");
    });


</script>
{/literal}
<table style="margin-left:20px;" >
    <tr style="height:25px;">
        <td id="tab2" class="tab">
           <img src="../../libs/imagenes/1.png" width="20" align="absmiddle" height="20"><b>Forma de Pago</b>&nbsp;
        </td>


    </tr>

</table>
</div>

<div id="contenedorTAB21">
<!-- TAB1 -->
<div class="tabpanel2">
<table>
    
{foreach from=$tipo_impuesto key=a item=impuesto}
<tr>
    <td  align="right" colspan="4" width="50%" class="tb-head" >
        <b>{$impuesto.descripcion}</b>
    </td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Base para Retención
</td>
<td>
    {if $impuesto.cod_tipo_impuesto eq 1}
        <input type="text" readonly name="totalizar_monto_iva" value="{$factura[0].totalizar_monto_iva}" id="totalizar_monto_iva">
    {else}
        <input type="text" name="totalizar_base_imponible" value="{$factura[0].totalizar_base_imponible}" id="totalizar_base_imponible">
    {/if}
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Porcentaje de Retención
</td>
<td >
    <input type="text" readonly name="totalizar_pbase_retencion{$impuesto.cod_tipo_impuesto}" value="0" id="totalizar_pbase_retencion{$impuesto.cod_tipo_impuesto}"> %
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Mont. Retención
</td>
<td >
    <input type="text" readonly name="totalizar_monto_retencion{$impuesto.cod_tipo_impuesto}" value="0" id="totalizar_monto_retencion{$impuesto.cod_tipo_impuesto}">
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Descripción de Retención
</td>
<td >

    <select name="cod_impuesto{$a+1}" id="cod_impuesto{$a+1}">
        <option>Seleccione una Retencion
        </option>
         {foreach from =$dato_impuesto  key = i item = miItem}
            {if $impuesto.cod_tipo_impuesto eq $miItem.cod_tipo_impuesto}

                <option value={$miItem.cod_impuesto}>
                    {$miItem.descripcion}
                </option>
             {/if}
         {/foreach}
     </select>
    <input type="hidden" size="5" name="tipo_impuesto" value="{$impuesto.cod_tipo_impuesto}" id="tipo_impuesto">
</td>

<td> <input type="hidden" size="5" name="cod_tipo_impuesto{$impuesto.cod_tipo_impuesto}" value="{$impuesto.cod_tipo_impuesto}" id="cod_tipo_impuesto{$impuesto.cod_tipo_impuesto}"> </td>
<td> <input type="hidden" size="5" name="i{$impuesto.cod_tipo_impuesto}" value="{$a+1}" id="i{$impuesto.cod_tipo_impuesto}"> </td>
</tr>

{/foreach}

<tr>
    <td  align="right" colspan="4" width="50%" class="tb-head" >
    <b>Total Retenciones</b>
     </td>
</tr>

<tr>
<td valign="top" colspan="3" class="tb-head">
Monto Retencion de Impuestos
</td>
<td>
   <input type="text" readonly name="totalizar_total_retencion" value="0" id="totalizar_total_retencion">
</td>
</tr>

<tr>
    <td  align="right" colspan="4" width="50%" class="tb-head" >
        <b>Total Facturación</b>
</td>

</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Fecha de Emisións
</td>
<td >
<input type="text" size="10" readonly style="border: 1px solid black;margin-bottom:5px;" value="" id="fecha_emision" name="fecha_emision" class=""/>  Ej: 2009-11-01

{literal}
    <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() }
      });
      cal.manageFields("fecha_emision", "fecha_emision", "%Y-%m-%d");
    //]]></script>
{/literal}


</td>
</tr>


<tr>
<td  colspan="3" width="50%" class="tb-head" >
Descripción del Pago/abono
</td>
<td >
<input type="text" name="descripcion_pagoabono"  style="border: 1px solid black;margin-bottom:5px;" />

</td>
</tr>


<tr>
<td  colspan="3" width="50%" class="tb-head" >
Monto a Cancelar
</td>
<td >
    <input type="text"  class="ctotalizar_" name="totalizar_monto_cancelar" value="{$cabecera_estadodecuenta[0].saldo_pendiente}" id="totalizar_monto_cancelar">
</td>
</tr>
<tr>
    <td  valign="top" colspan="3" width="50%" class="tb-head" >
Saldo Pendiente
</td>
<td >
    <input type="text" class="ctotalizar_" style="background-color: #eaeaea;" readonly name="totalizar_saldo_pendiente" value="0" id="totalizar_saldo_pendiente">


<div id="info_pago_pendiente" style="border: 1px solid #dbdbdb;background-color:#fbfbfb;margin-left:5px;margin-top:5px;margin-bottom:7px;padding-left:5px;color:#504b4b;">
<b>Especifique los siguientes campos:</b>
<br><br>
<img align="absmiddle" style="margin-bottom:5px;" src="../../libs/imagenes/ew_calendar.gif"> Fecha Vencimiento: <br>
<input type="text" size="10" readonly style="border: 1px solid black;margin-bottom:5px;" value="0000-00-00" id="fecha_vencimiento" name="fecha_vencimiento" class=""/>  Ej: 2009-11-01

{literal}
    <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() }
      });
      cal.manageFields("fecha_vencimiento", "fecha_vencimiento", "%Y-%m-%d");
    //]]></script>
{/literal}

<br>
<img align="absmiddle" src="../../libs/imagenes/ico_view.gif"> Observacion:<br>
<textarea name="observacion"></textarea>
<br>
<img align="absmiddle" src="../../libs/imagenes/ico_user.gif"> Persona Contacto:<br>
<input type="text" name="persona_contacto" class=""/><br>
<img align="absmiddle" src="../../libs/imagenes/ico_cel.gif"> Telefono:<br>
<input type="text" name="telefono"/><br>

<span style="font-size:9px;color:red;">
Nota: Debe llenar todos los campos.
</span>
</div>

</td>




</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Cambio
</td>
<td >
    <input type="text" class="ctotalizar_" style="background-color: #eaeaea;" readonly name="totalizar_cambio" value="0" id="totalizar_cambio">
</td>
</tr>

<tr>
    <td  align="right" colspan="4" width="50%" class="tb-head" >
        <b>Instrumento de Pago</b>
</td>

</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
En Efectivo
</td>
<td >
    <input type="text" class="ctotalizar_" name="totalizar_monto_efectivo" id="totalizar_monto_efectivo" value="{$cabecera_estadodecuenta[0].saldo_pendiente}">  (*)
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Cheque
</td>
<td >
    <select name="opt_cheque" id="opt_cheque">
        <option value="0">No</option>
        <option value="1">Si</option>
    </select>
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Monto Cheque
</td>
<td >
    <input type="text" class="ctotalizar_" value="0" name="totalizar_monto_cheque" id="totalizar_monto_cheque"> (*)
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Nro. Cheque
</td>
<td >
    <input type="text" class="ctotalizar_" value="0"  name="totalizar_nro_cheque"  id="totalizar_nro_cheque">
</td>
</tr>
<tr>
<td  colspan="3"  width="50%" class="tb-head" >
Banco
</td>
<td >

<select class="ctotalizar_" name="totalizar_nombre_banco"  id="totalizar_nombre_banco" >
    <option value="0">S/I</option>
    {html_options output=$option_output_banco values=$option_values_banco}
</select>

</td>
</tr>

<tr>
<td  colspan="3" width="50%" class="tb-head" >
Tarjeta
</td>
<td >
    <select name="opt_tarjeta" id="opt_tarjeta">
        <option value="0">No</option>
        <option value="1">Si</option>
    </select>
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Monto Tarjeta
</td>
<td >
    <input type="text" value="0" class="ctotalizar_"   name="totalizar_monto_tarjeta"  id="totalizar_monto_tarjeta">  (*)
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Nro. Tarjeta
</td>
<td >
    <input type="text" value="0" class="ctotalizar_" name="totalizar_nro_tarjeta"  id="totalizar_nro_tarjeta">
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Tipo de Tarjeta
</td>
<td >


<select class="ctotalizar_" name="totalizar_tipo_tarjeta"  id="totalizar_tipo_tarjeta" >
<option value="0">S/I</option>
{html_options output=$option_output_instrumento_pago_tarjeta values=$option_values_instrumento_pago_tarjeta}
</select>

</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Deposito
</td>
<td >
    <select name="opt_deposito" id="opt_deposito">
        <option value="0">No</option>
        <option value="1">Si</option>
    </select>
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Monto del Deposito
</td>
<td >
    <input type="text" value="0" class="ctotalizar_"  name="totalizar_monto_deposito"  id="totalizar_monto_deposito">   (*)
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Nro. de Deposito
</td>
<td >
    <input type="text" class="ctotalizar_"  name="totalizar_nro_deposito"  id="totalizar_nro_deposito" value="0">
</td>
</tr>
<tr>
<td  colspan="3" class="tb-head" >
Banco Deposito
</td>
<td >

<select class="ctotalizar_" name="totalizar_banco_deposito"  id="totalizar_banco_deposito" >
    <option value="0">S/I</option>
    {html_options output=$option_output_banco values=$option_values_banco}
</select>
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Otro Documento
</td>
<td >
    <select name="opt_otrodocumento" id="opt_otrodocumento">
        <option value="0">No</option>
        <option value="1">Si</option>
    </select>
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Tipo de Documento
</td>
<td >
<select class="ctotalizar_" name="totalizar_tipo_otrodocumento"  id="totalizar_tipo_otrodocumento" >
<option value="0">S/I</option>
{html_options output=$option_output_tipo_otrodocumento values=$option_values_tipo_otrodocumento}
</select>   (*)
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Monto
</td>
<td >
    <input type="text" value="0" class="ctotalizar_"  name="totalizar_monto_otrodocumento"  id="totalizar_monto_otrodocumento">   (*)
</td>
</tr>
<tr>
<td  colspan="3" width="50%" class="tb-head" >
Numero
</td>
<td >
    <input type="text" class="ctotalizar_"  name="totalizar_nro_otrodocumento"  id="totalizar_nro_otrodocumento" value="0">
</td>
</tr>
<tr>
<td  colspan="3" class="tb-head" >
Banco
</td>
<td >
<select class="ctotalizar_" name="totalizar_banco_otrodocumento"  id="totalizar_banco_otrodocumento" >
    <option value="0">S/I</option>
    {html_options output=$option_output_banco values=$option_values_banco}
</select>
</td>
</tr>
</table>
</div>
</div>
<hr>
<center></center>
</div>

<input type="submit" id="PFactura2" name="PFactura2" value="Procesar Factura">


</div>
<!--</contenedor factura paso 2>-->





<input type="hidden" name="totalizar_total_general" value="{$cabecera_estadodecuenta[0].saldo_pendiente}">
<input type="hidden" name="cantidad_impuesto" value="{$numero_impuesto[0].cantidad_impuesto}" id="cantidad_impuesto">
<input type="hidden" name="numero_control_factura" value="{$factura[0].cod_factura}" id="numero_control_factura">
<input type="hidden" name="id_factura" value="{$factura[0].id_factura}" id="id_factura">

</form>

<div id="info" style="display:none;">

</div>
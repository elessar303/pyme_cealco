<script src="../../libs/js/cxp_edocuenta.js" type="text/javascript"></script>
<script src="../../libs/js/cxp_pagooabono.js" type="text/javascript"></script>
<!-- <link href="../../libs/css/nueva_factura.css" media="screen" rel="stylesheet" type="text/css" /> -->

<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="DatosCliente" value="">
<input type="hidden" name="cod" value="{$smarty.get.cod}">
<input type="hidden" name="cod_edocuenta" value="{$smarty.get.cod2}">
<input type="hidden" name="id_fiscal" value="{$dataproveedor[0].rif}">
<input type="hidden" name="id_proveedor" value="{$dataproveedor[0].id_proveedor}">
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
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=edocuenta&cod={$cod}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
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
<span style="font-family:'Verdana';"><b>Proveedor: </b></span>
<span style="font-family:'Verdana';">{$dataproveedor[0].descripcion}</span>
<span style="font-family:'Verdana';"><b>Compra: </b></span>
<span style="font-family:'Verdana';">{$factura[0].cod_compra}</span>
<input type="hidden" name="id_proveedor" value="{$dataproveedor[0].id_proveedor}">
<input type="hidden" name="id_fiscal" value="{$dataproveedor[0].rif}">
</div>
<!--</Datos del cliente y vendedor>-->

<div style="border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px; -moz-border-radius-bottomright: 7px; -moz-border-radius-bottomleft: 7px; margin-top: 0.3%; margin-right: 0.3%; font-size: 13px;">


<div style="float: left; margin-right: 20px;">
<b>Debitos</b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: red ;"><b>{$cabecera_estadodecuenta[0].debito|number_format:2:",":"."}</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Creditos </b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: #166a09;"><b>{$cabecera_estadodecuenta[0].credito|number_format:2:",":"."}</b></div>
</div>

<div style="margin-right: 20px;">
<b>Saldo Pendiente</b>
<div  style="font-size: 15px; color: red;"><b>{$cabecera_estadodecuenta[0].saldo_pendiente|number_format:2:",":"."}</b></div>
</div>
</div>


<div id="PanelPagoFacturas">
<div id="tabpago" class="x-hide-display">
<div id="contenedorTAB">
<div id="div_tab1">
<div class="grid2">
	<table width="50%" class="lista2">
	<tr>
	<td align="left" align="left" width="150px">
	Fecha de emision: <span style="color:red;">*</span>
	</td>
	<td>
	<input size="14" type="text" name="fecha" id="fecha" value="{$hoy}" >
	
	{literal}
	<script type="text/javascript">//<![CDATA[
	
	var cal = Calendar.setup({
		onSelect: function(cal) { cal.hide() }
	});
	cal.manageFields("fecha", "fecha", "%d/%m/%Y");
	//]]>
	</script>
	{/literal}
	</td>
	</tr>
	
	<tr>
	<td style="padding:2px;" align="left"  width="150px">
	Descripcion del pago o abono: <span style="color:red;">*</span>
	</td>
	<td>
	<input  size="45px" name="descripcion" type="text" id="descripcion" title="{$campos[6]}" value="{$campos[6]}"></td>
	</tr>
	<tr>
	<td style="padding:15px 2px; vertical-align:center;" align="left"  width="150px">
	Monto a Cancelar: <span style="color:red;">*</span>
	</td>
	<td>
	<input  size="23px" style=" background-color:#ffffff; height: 30px; font-size: 16px; color: green;"  name="montoPago" type="text" id="montoPago" onblur="javascript: montoCanMenosPend();"  value="0.00">
	</td>
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Monto Pendiente:
	</td>
	<td>
	<input size="23" readonly="true"  name="montoPendiente" type="text" id="montoPendiente" value="{$cabecera_estadodecuenta[0].saldo_pendiente}">
	</td>
	</tr>
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Total monto Base Iva: 
	</td>
	<td>
	<input size="23" readonly="true"  name="montoBaseIva" type="text" id="montoBaseIva" value="0.00">
	</td>
	</tr>
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Total Exento: 
	</td>
	<td>
	<input size="23" readonly="true"  name="montoExento" type="text" id="montoExento" value="0.00">
	</td>
	</tr>
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Total IVA:
	</td>
	<td>
	<input size="23" readonly="true"  name="totalIva" type="text" id="totalIva" value="0.00">
	</td>
	</tr>
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Total Retenciones: 
	</td>
	<td>
	<input size="23" readonly="true"  name="totalRet" type="text" id="totalRet" value="0.00">
	</td>
	</tr>
	</table>
</div>
</div>

	<div class="grid">
	<table width="100%" class="lista">
	<thead>
	<tr >
	<th class="tb-tit">Descripcion de la Retención</th>
	<th class="tb-tit">Base para Retención</th>
	<th class="tb-tit">Porcentaje de Retención</th>
	<th class="tb-tit">Monto Retención</th>
	</tr>
	</thead>
	<tbody id="ret">
	</tbody>
	</table>
	</div>

</div>
</div>

<div id="tabfacturas" class="x-hide-display">
<div id="contenedorTAB">
<div id="div_tab">
<div class="grid2">
	<table width="100%" class="lista2">
	<thead>
	<tr >
	<th class="tb-tit">Tipo Doc.</th>
	<th class="tb-tit">Num. Doc.</th>
	<th class="tb-tit">Num. Control</th>
	<th class="tb-tit">Fecha</th>
	<th class="tb-tit">Monto</th>
	<th class="tb-tit">Incluir en pago?</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$fac key=i item=campos}
	<tr>
	<td style="padding:2px;" align="center"  width="150px">
	{$campos.tipo}
	</td>
	<td style="padding:2px;" align="center"  width="150px">
	{$campos.cod_factura}
	</td>
	<td style="padding:2px;" align="center"  width="150px">
	{$campos.cod_cont_factura}
	</td>
	<td  style="padding:2px;" align="center"  width="150px">
	{$campos.fecha_factura|fecha}
	</td>
	<td  style="padding:2px;" align="center"  width="150px">
	{$campos.total_a_pagar}
	</td>
	<td align="center"  style="padding:2px;" align="center"   width="150px">
	<input name="optAgregar{$i}" id="optAgregar{$i}" type="checkbox" onchange="javascript:montoCheck({$i});" value="{$i}">
	<input name="monto{$i}" type="hidden" id="monto{$i}" value="{$campos.total_a_pagar}">
	<input name="codServ{$i}" type="hidden" id="codServ{$i}" value="{$campos[5]}">
	<input name="codEntidad{$i}" type="hidden" id="codEntidad{$i}" value="{$campos[7]}">
	<input name="codigo{$i}" type="hidden" id="codigo{$i}" value="{$campos.id_factura}">
	<input name="montoIva{$i}" type="hidden" id="montoIva{$i}" value="{$campos.monto_iva}">
	<input name="montoBaseIva{$i}" type="hidden" id="montoBaseIva{$i}" value="{$campos.monto_base}">
	<input name="montoExento{$i}" type="hidden" id="montoExento{$i}" value="{$campos.monto_exento}">
	<input name="montoIvaRet{$i}" type="hidden" id="montoIvaRet{$i}" value="{$campos.monto_retenido}">
	<input name="codigoIva{$i}" type="hidden" id="codigoIva{$i}" value="{$campos.cod_impuesto}">
	<input name="tipoDoc{$i}" type="hidden" id="tipoDoc{$i}" value="{$campos.tipo}">
	<input name="cod_factura{$i}" type="hidden" id="cod_factura{$i}" value="{$campos.cod_factura}">
	</td>
	</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr class="sf_admin_row_2">
	<td colspan="4">
	<input name="cantidad" type="hidden" id="cantidad" value="0">
	<input name="cantidadImp" type="hidden" id="cantidadImp" value="0">
	<input name="facturas" type="hidden" id="facturas" value="{$i+1}">
	<input name="idFacturas" type="hidden" id="idFacturas" value="">
	<input name="totalNotaCred" type="hidden" id="totalNotaCred" value="0">
	<div class="span_cantidad_items"><span style="font-size: 10px;">Cantidad de Facturas: 0</span></div>
	</td>
	</tr>
	</tfoot>
	</table>
</div>
</div>
</div>
</div>
</div>
<input type="hidden" name="totalizar_total_general" id="totalizar_total_general" value="{$cabecera_estadodecuenta[0].saldo_pendiente}">
<input type="hidden" name="cantidad_impuesto" value="{$numero_impuesto[0].cantidad_impuesto}" id="cantidad_impuesto">
<input type="hidden" name="cod_compra" value="{$factura[0].cod_compra}" id="cod_compra">
<input type="hidden" name="id_compra" value="{$factura[0].id_compra}" id="id_compra">
</form>
<div id="info" style="display:none;">
</div>
<script src="../../libs/js/cxc_edocuenta.js" type="text/javascript"></script>
<script src="../../libs/js/cxc_pendiente.js" type="text/javascript"></script>
<!-- <link href="../../libs/css/nueva_factura.css" media="screen" rel="stylesheet" type="text/css" /> -->

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

<input type="hidden" name="id_proveedor" value="{$datacliente[0].id_cliente}">

</div>
<!--</Datos del cliente y vendedor>-->

<div style="border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px; -moz-border-radius-bottomright: 7px; -moz-border-radius-bottomleft: 7px; margin-top: 0.3%; margin-right: 0.3%; font-size: 13px;">


<div style="float: left; margin-right: 20px;">
<b>Pendiente</b>
<div ><b><input type="text" readonly  id="pendiente_p" name="pendiente_p" value="{$total[0].monto}"  style="border:0px; font-size: 15px; color: red;font-weight: bold;" /></b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Enviar </b>
<div ><b><input type="text" readonly  id="enviar_total" name="enviar_total" value="0.00"  style="border:0px; font-size: 15px; color: rgb(78, 106, 72);font-weight: bold;" /></b></div>
</div>

<div style="margin-right: 20px;">
<b>Saldo Pendiente</b>
<div ><b><input type="text" readonly  id="pendiente_total" name="pendiente_total" value="0.00"  style="border:0px; font-size: 15px; color: red;font-weight: bold;" /></b></div>
</div>
</div>
<div id="buscar_panel" style="border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px; -moz-border-radius-bottomright: 7px; -moz-border-radius-bottomleft: 7px; margin-top: 0.3%; margin-right: 0.3%; font-size: 13px;">
	<input id="buscar_factura" type="text" name="buscar_factura" onkeypress="javascript:if(event.keyCode == 13)seleccionar();" value="" />
	<input type="button" class="btn" value="Buscar" onclick="javascript:seleccionar();" />
	<input id="informacion" name="informacion" value="" style="border:0;width:200px;" />
	
</div>
<div id="PanelPagoFacturas">

<div id="tabfacturas" class="x-hide-display">
<div id="contenedorTAB">
<div id="div_tab">
<div class="grid">
	<table width="100%" class="lista">
	<thead>
	<tr  >
	<th class="tb-tit" >Num. Factura</th>
	<th class="tb-tit">Num. Control</th>
	<th class="tb-tit">Fecha</th>
	<th class="tb-tit">Monto</th>
	<th class="tb-tit">Enviar</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$fac key=i item=campos}
	<tr>
	<td style="padding:2px;" align="center"  width="150px">
	<input type="text" readonly="true" id="numero{$i}" name="numero{$i}" value="{$campos.numero}" style="border:0;width:60px;">
	
	</td>
	<td style="padding:2px;" align="center"  width="150px">
	{$campos.control}
	</td>
	<td  style="padding:2px;" align="center"  width="150px">
	{$campos.fecha_emision|fecha}
	</td>
	<td  style="padding:2px;" align="center"  width="150px">
	<input readonly id="monto{$i}" value="{$campos.monto}">
	</td>
	<td align="center"  style="padding:2px;" align="center"   width="150px">
	<input name="optAgregar{$i}" id="optAgregar{$i}" type="checkbox" onchange="javascript:montoCheck({$i});" value="{$i}">
	</td>
	<td><input name="id_fac{$i}" type="hidden" id="id_fac{$i}" value="{$campos.cod_edocuenta}"></td>
	</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr class="sf_admin_row_2">
	<td colspan="4">
	<input name="cantidad" type="hidden" id="cantidad" value="0">
	<input name="cantidad_item" type="hidden" id="cantidad_item" value="{$i}">
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
<input type="hidden" name="totalizar_total_general" value="{$cabecera_estadodecuenta[0].saldo_pendiente}">
<input type="hidden" name="cantidad_impuesto" value="{$numero_impuesto[0].cantidad_impuesto}" id="cantidad_impuesto">
<input type="hidden" name="cod_compra" value="{$factura[0].cod_compra}" id="cod_compra">
<input type="hidden" name="id_compra" value="{$factura[0].id_compra}" id="id_compra">
</form>
<div id="info" style="display:none;">
</div>

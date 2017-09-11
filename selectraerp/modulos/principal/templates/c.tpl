<script src="../../libs/js/cxp_edocuenta.js" type="text/javascript"></script>
<script src="../../libs/js/cxp_facturas_medico.js" type="text/javascript"></script>
<!-- <link href="../../libs/css/nueva_factura.css" media="screen" rel="stylesheet" type="text/css" /> -->

<form name="formulario" id="formulario" method="POST" action="">

<input type="hidden" name="cod" value="{$smarty.get.cod}">

<input type="hidden" name="id_fiscal" value="{$dataproveedor[0].rif}">
<input type="hidden" name="id_proveedor" value="{$dataproveedor[0].id_proveedor}">
<input type="hidden" name="cod" value="{$dataproveedor[0].id_proveedor}">
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
<span style="font-family:'Verdana';"><b>Medico: </b></span>
<span style="font-family:'Verdana';">{$dataproveedor[0].descripcion}</span>
<input type="hidden" name="id_proveedor" value="{$dataproveedor[0].id_proveedor}">
<input type="hidden" name="id_fiscal" value="{$dataproveedor[0].rif}">
</div>
<!--</Datos del cliente y vendedor>-->



<div style="border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px; -moz-border-radius-bottomright: 7px; -moz-border-radius-bottomleft: 7px; margin-top: 0.3%; margin-right: 0.3%; font-size: 13px;">

<div style="margin-right: 20px;">
<b>Total seleccionado:</b>
<div  style="font-size: 15px; color: red;"><span class="pend"><b>0.00</b></span></div>
</div>
</div>
</div>

<div id="PanelPagoFacturas">

<div id="tabfacturas" class="x-hide-display">
<div id="contenedorTAB">
<div id="div_tab">
<div class="grid2">
	<table width="100%" class="lista2">
	<thead>
	<tr >
	<th class="tb-tit">Num. Factura</th>
	<th class="tb-tit">Fecha</th>
	<th class="tb-tit">Monto</th>
	<th class="tb-tit">Incluir en pago?</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$facs key=i item=campos}
	{if $campos.monto}
	<tr>
	<td style="padding:2px;" align="center"  width="150px">
	{$campos.factura_fk}
	</td>
	<td  style="padding:2px;" align="center"  width="150px">
	{$campos.fecha_fac|fecha}
	</td>
	<td  style="padding:2px;" align="center"  width="150px">
	<input type="text" name="monto{$i}" id="monto{$i}" style="border:0px; " value="{$campos.monto}">
	</td>
	<td align="center"  style="padding:2px;" align="center"   width="150px">
	<input name="optAgregar{$i}" id="optAgregar{$i}" type="checkbox" onchange="javascript:montoCheck();" value="{$i}">
	<input name="codigo{$i}" type="hidden" id="codigo{$i}" value="{$campos.cxp_factura_medico_pk}">
	</td>
	</tr>
	{/if}
	{/foreach}
	</tbody>
	<tfoot>
	<tr class="sf_admin_row_2">
	<td colspan="4">
	<input name="cantidad" type="hidden" id="cantidad" value="0">
	<input name="facturas" type="hidden" id="facturas" value="{$i+1}">
	<input name="idFacturas" type="hidden" id="idFacturas" value="">
	<input name="montoPago" type="hidden" id="montoPago" value="">
	<div class="span_cantidad_items"><span style="font-size: 10px;">Cantidad de Facturas: 0</span></div>
	</td>
	</tr>
	<tr>
	<td colspan="4" align="left">
	<div align="left"><input type="submit" id="aceptar" name="aceptar" value="Enviar"></div>
	</td>
	</tr>
	</tfoot>
	</table>
</div>
</div>
</div>
</div>
</div>
</form>
<script src="../../libs/js/cxp_edocuenta.js" type="text/javascript"></script>
<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="DatosCliente" value="">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
<input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">

<input type="hidden" name="url_delete_asientos" value="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion={$smarty.get.opt_subseccion}&cod={$smarty.get.cod}">


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
<span style="font-family:'Verdana';"><b>Proveedor </b></span>
<span style="font-family:'Verdana';">{$datacliente[0].descripcion}</span>
<input type="hidden" name="id_cliente" value="{$datacliente[0].id_cliente}">
</div>
<!--</Datos del cliente y vendedor>-->


<div style="border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px; -moz-border-radius-bottomright: 7px; -moz-border-radius-bottomleft: 7px; margin-top: 0.3%; margin-right: 0.3%; font-size: 13px;">


<div style="float: left; margin-right: 20px;">
<b>Debitos</b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: red;"><b>{$cabecera_estadodecuenta[0].debito|number_format:2:",":"."}</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Creditos </b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color:#166a09 ;"><b>{$cabecera_estadodecuenta[0].credito|number_format:2:",":"."}</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Facturas Pagadas </b>
<div   style="color: #105a04;font-size: 15px; "><b>{$cabecera_estadodecuenta[0].facturas_pagadas}</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Facturas Pendientes </b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: red;"><b>{$cabecera_estadodecuenta[0].facturas_pendientes}</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Facturas Totales </b>
<div   style="color: rgb(78, 106, 72);font-size: 15px;"><b>{$cabecera_estadodecuenta[0].total_facturas}</b></div>
</div>

<div style="margin-right: 20px;">
<b>Saldo Pendiente</b>
<div  style="font-size: 15px; color: red;"><b>{$cabecera_estadodecuenta[0].saldo_pendiente|number_format:2:",":"."}</b></div>
</div>
</div>


<!--<TABLA DE CUENTAS POR COBRAR>-->
<div  style="background-color:#ffffff; border: 1px solid #ededed;-moz-border-radius: 7px;padding:5px; margin-top:0.3%;  font-size: 13px; ">
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
    <thead>
            {foreach from=$cabecera key=i item=campos}
                <th align="left">{$campos}</th>
            {/foreach}
    </thead>

<tbody>
{if $cantidadFilas == 0}
<tr>
  <td colspan="8">
      {$mensaje}
  </td>

</tr>
{else}
    {foreach from = $registros item = campos key = i}
    <tr style=" cursor: pointer;" class="edocuenta" bgcolor="#ececec">
        <td width="25" align="center">
        <img class="boton_edocuenta" src="../../libs/imagenes/drop-add.gif">
        <input type="hidden" name="cod_proveedor" value="{$campos.id_proveedor}">
        <input type="hidden" name="cod_edocuenta" value="{$campos.cod_edocuenta}">
        </td>
        <td >{$campos.documento}</td>
        <td >{$campos.numero}</td>
        <td >{$campos.fecha_emision|date_format:"%d-%m-%Y"} </td>
        <td >{$campos.vencimiento_fecha|date_format:"%d-%m-%Y"}</td>
        <td >{$campos.observacion}</td>
        <td align="left">{$empresa[0].moneda} {$campos.monto|number_format:2:",":"."}</td>
        <td>
        {if $campos.estado eq "Pagada"}
        <img title="Pagada" src="../../libs/imagenes/ico_ok.gif">
        {/if}

        {if $campos.estado eq "Pendiente"}
            <img title="Pendiente" src="../../libs/imagenes/ico_note_1.gif">
        {/if}


        </td>
        <td> <img style="cursor: pointer;" class="edocuenta" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=edocuenta&cod={$campos.id_cliente}'" title="Pago o Abono"  src="../../libs/imagenes/edocuenta.png"></td>
    </tr>
    {/foreach}
{/if}
</tbody>
</table>
</div>
<!--</TABLA DE CUENTAS POR COBRAR>-->
</form>
<div id="info" style="display:none;">
</div>
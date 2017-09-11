<?php /* Smarty version 2.6.21, created on 2017-02-10 19:52:53
         compiled from cxc_pagar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'cxc_pagar.tpl', 188, false),)), $this); ?>
<script src="../../libs/js/cxc_edocuenta.js" type="text/javascript"></script>
<script src="../../libs/js/cxc_pagar.js" type="text/javascript"></script>
<!-- <link href="../../libs/css/nueva_factura.css" media="screen" rel="stylesheet" type="text/css" /> -->

<form name="formularioxx" id="formularioxx" method="post" action="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
">
<input type="hidden" name="DatosCliente" value="">
<input type="hidden" name="codcliente" value="<?php echo $_GET['cod']; ?>
">
<input type="hidden" name="cod_edocuenta" value="<?php echo $_GET['cod2']; ?>
">
<input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
<input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
<input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">
<input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
">
  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="<?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" /><?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>
</span></td>

                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'" name="buscar" border="0" cellpadding="0" cellspacing="0">
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
<span style="font-family:'Verdana';"><?php echo $this->_tpl_vars['datacliente'][0]['nombre']; ?>
</span>

<input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $this->_tpl_vars['datacliente'][0]['id_cliente']; ?>
">

</div>
<!--</Datos del cliente y vendedor>-->

<div style="border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px; -moz-border-radius-bottomright: 7px; -moz-border-radius-bottomleft: 7px; margin-top: 0.3%; margin-right: 0.3%; font-size: 13px;">


<div style="float: left; margin-right: 20px;">
<b>Pendiente</b>
<div ><b><input type="text" readonly  id="pendiente_p" name="pendiente_p" value="<?php echo $this->_tpl_vars['total'][0]['monto']; ?>
"  style="border:0px; font-size: 15px; color: red;font-weight: bold;" /></b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Pagar </b>
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
<div id="movimientox" class="x-hide-display">
	<label>
	<p><b>Movimientos Bancarios</b></p>
	<div class="grid">
	<table width="100%" class="lista">
	<thead>
	<tr >
	<th class="tb-tit">Numero</th>
	<th class="tb-tit">Concepto</th>
	<th class="tb-tit">Fecha</th>
	<th class="tb-tit">Monto</th>
	<th class="tb-tit">&nbsp;</th>
	</tr>
	</thead>
	<tbody id="movimiento">
	</tbody>
	</table>
	</div>
	</label>
	<label>
	<p><b>Total:</b>
	<input type="hidden" name="cantMov" value="0" id="cantMov">
	<input type="text" value="0.00" readonly="true" name="totalPags" id="totalPags">
	</p>
	</label>
</div>
<div id="PanelPagoFacturas">
<div id="tabpago" class="x-hide-display">
<div id="contenedorTAB">
<div id="div_tab1">
<div class="grid2">
	
	
	<table width="50%" class="lista2">
	<tr>
	<td  colspan="2" width="50%" class="tb-tit2" >
	DETALLE DEL PAGO
	</td>
	</tr>

	
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Monto Base: 
	</td>
	<td>
	<input size="23" readonly="true"  name="montoBase" type="text" id="montoBase" value="0.00">
	</td>
	</tr>

	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Monto  Nota Credito: 
	</td>
	<td>
	<input size="23" readonly="true"  name="totaliva" type="text" id="totaliva" value="0.00">
	</td>
	</tr>
		<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Monto  Abonado: 
	</td>
	<td>
	<input size="23" readonly="true"  name="totalabono" type="text" id="totalabono" value="0.00">
	</td>
	</tr>

	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Monto Retenci&oacute;n ISLR: 
	</td>
	<td>
	<input size="23" readonly="true"  name="totalRetislr" type="text" id="totalRetislr" value="0.00">
	</td>
	</tr>

	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Monto Total: 
	</td>
	<td>
	<input size="23" readonly="true"  name="montoTotal" type="text" id="montoTotal" value="0.00">
	</td>
	</tr>
	<tr>
	<td  colspan="2" width="50%" class="tb-tit2" >
	DESCRIPCION DEL PAGO
	</td>
	</tr>
	<tr>
	<td style="padding:2px;" align="left"  width="150px">
	Fecha de Pago: <span style="color:red;">*</span>
	</td>
	<td>
	<input size="14" type="text" name="fechad" id="fechad" value="" >
	
	<?php echo '
	<script type="text/javascript">//<![CDATA[
	
	var cal = Calendar.setup({
		onSelect: function(cal) { cal.hide() }
	});
	cal.manageFields("fecha", "fecha", "%d/%m/%Y");
	//]]>
	</script>
	'; ?>

	</td>
	</tr>
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Banco: 
	</td>
	<td>
	<select name="banco" id="banco" style="width:150px" >
        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_banco'],'output' => $this->_tpl_vars['option_output_banco'],'selected' => $this->_tpl_vars['option_output']), $this);?>
 
     </select>

	</td>
	</tr>
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Forma de Pago: 
	</td>
	<td>
	<select class="ctotalizar_" name="tipo_trans"  id="tipo_trans" >
		<option value="CH">Cheque</option>
		<option value="DP">Deposito</option>
		<option value="TS">Transferencia</option>
	</select>
	</td>
	</tr>
	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Transacci&oacute;n N: 
	</td>
	<td>
	<input size="23"   name="trans" type="text" id="trans" value="0">
	</td>
	</tr>

	<tr>
	<td  style="padding:2px;" align="left"  width="150px">
	Monto del Pago:
	</td>
	<td>
	<input size="23" readonly="true" name="pagoRecibido" type="text" id="pagoRecibido" value="0">
	</td>
	</tr>
	</table>
</div>
</div>



</div>
</div>

<div id="tabfacturas" class="x-hide-display">
<div id="contenedorTAB">
<div id="div_tab">
<div class="grid">
	<table width="100%" class="lista">
	<thead>
	<tr  >
	<th class="tb-tit" >Num. Fac.</th>
	<th class="tb-tit">Num. Control</th>
	<th class="tb-tit">Fecha</th>
	<th class="tb-tit">Monto Base</th>
	<th class="tb-tit">Saldo</th>
	<th class="tb-tit">Mto. a pagar</th>
	<th class="tb-tit">N. Credito</th>
	<th class="tb-tit">Abonado</th>
	<th class="tb-tit">Ret. ISLR</th>
	<th class="tb-tit">Mto. ISLR</th>
	<th class="tb-tit">Enviar</th>
	</tr>
	</thead>
	<tbody>
	<?php $_from = $this->_tpl_vars['fac']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
	<tr>
	<td  align="center"  width="50px">
	<input type="text" readonly="true" id="numero<?php echo $this->_tpl_vars['i']; ?>
" name="numero<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['campos']['numero']; ?>
" style="border:0;width:60px;">
	
	</td>
	<td  align="center"  width="100px">
	<?php echo $this->_tpl_vars['campos']['control']; ?>

	</td>
	<td  align="center"  width="100px">

	<?php echo $this->_tpl_vars['campos']['fecha_emision']; ?>

	</td>
	<td   align="center"  width="100px">
	<input type="text" readonly="true" name="montob<?php echo $this->_tpl_vars['i']; ?>
" id="montob<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['campos']['monto_base']; ?>
" style="border:0;width:80px;">
	</td>
	<td   align="center"  width="100px">
	<input type="text" readonly="true" name="saldo<?php echo $this->_tpl_vars['i']; ?>
" id="saldo<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['campos']['saldo']; ?>
" style="border:0;width:80px;">
	</td>
	<td   align="center"  width="100px">
	<input type="text" name="montopag<?php echo $this->_tpl_vars['i']; ?>
" id="montopag<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['campos']['saldo']; ?>
" style="border:0;width:80px;">
	</td>
	<td   align="center"  width="100px">
	<?php echo $this->_tpl_vars['campos']['nota_credito']; ?>

	</td>
	
	<td  align="center"  width="100px" >
	<input type="text" size="10"  id="montoabono<?php echo $this->_tpl_vars['i']; ?>
" name="montoabono<?php echo $this->_tpl_vars['i']; ?>
" onblur="actualizar_abono();" >
	</td>
	<td>
	<select name="islr<?php echo $this->_tpl_vars['i']; ?>
" id="islr<?php echo $this->_tpl_vars['i']; ?>
" style="width:80px" onclick="retencion_islr();">
	<option>Seleccione una Retencion
	</option>
	<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_tipo_islr'],'output' => $this->_tpl_vars['option_output_tipo_islr'],'selected' => $this->_tpl_vars['option_output']), $this);?>
 
	</select>

	</td>
	<td align="center"  width="100px">
	<input  type="text" size="10"  id="im<?php echo $this->_tpl_vars['i']; ?>
" name="im<?php echo $this->_tpl_vars['i']; ?>
" value="0.00" onblur="actualizar_impuesto();">
	</td>
	<td><input name="id_fac<?php echo $this->_tpl_vars['i']; ?>
" type="hidden" id="id_fac<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['campos']['cod_edocuenta']; ?>
">
<!-- 	<td align="center"  style="padding:2px;" align="center"   width="100px"> -->
	<input name="optAgregar<?php echo $this->_tpl_vars['i']; ?>
" id="optAgregar<?php echo $this->_tpl_vars['i']; ?>
" type="checkbox" onchange="javascript:montoCheck(<?php echo $this->_tpl_vars['i']; ?>
);" value="<?php echo $this->_tpl_vars['i']; ?>
">
	</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	</tbody>
	<tfoot>
	<tr class="sf_admin_row_2">
	<td colspan="4">
	<input type="hidden" name="codigomov" value="0" id="codigomov">
	<input name="cantidad" type="hidden" id="cantidad" value="0">
	<input name="cantidad_item" type="hidden" id="cantidad_item" value="<?php echo $this->_tpl_vars['i']; ?>
">
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
<input type="hidden" name="totalizar_total_general" value="<?php echo $this->_tpl_vars['total'][0]['monto']; ?>
">
<input type="hidden" name="cantidad_impuesto" value="<?php echo $this->_tpl_vars['numero_impuesto'][0]['cantidad_impuesto']; ?>
" id="cantidad_impuesto">
<input type="hidden" name="cod_compra" value="<?php echo $this->_tpl_vars['factura'][0]['cod_compra']; ?>
" id="cod_compra">
<input type="hidden" name="id_compra" value="<?php echo $this->_tpl_vars['factura'][0]['id_compra']; ?>
" id="id_compra">


</form>
<div id="info" style="display:none;">
</div>
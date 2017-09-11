<?php /* Smarty version 2.6.21, created on 2016-10-04 14:41:25
         compiled from cxc_estadodecuenta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'cxc_estadodecuenta.tpl', 111, false),)), $this); ?>
<script src="../../libs/js/cxc_edocuenta.js" type="text/javascript"></script>
<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="DatosCliente" value="">
<input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
<input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
<input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">
<input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
">

<input type="hidden" name="url_delete_asientos" value="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=<?php echo $_GET['opt_subseccion']; ?>
&cod=<?php echo $_GET['cod']; ?>
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
<input type="hidden" name="id_cliente" value="<?php echo $this->_tpl_vars['datacliente'][0]['id_cliente']; ?>
">
</div>
<!--</Datos del cliente y vendedor>-->


<div style="border: 1px solid rgb(237, 237, 237); padding: 5px; background-color: rgb(255, 255, 255); -moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px; -moz-border-radius-bottomright: 7px; -moz-border-radius-bottomleft: 7px; margin-top: 0.3%; margin-right: 0.3%; font-size: 13px;">


<div style="float: left; margin-right: 20px;">
<b>Debitos</b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: #166a09;"><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['debito']; ?>
</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Creditos </b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: red;"><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['credito']; ?>
</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Facturas Pagadas </b>
<div   style="color: #105a04;font-size: 15px; "><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['facturas_pagadas']; ?>
</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Facturas Pendientes </b>
<div   style="color: rgb(78, 106, 72);font-size: 15px; color: red;"><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['facturas_pendientes']; ?>
</b></div>
</div>

<div style="float: left; margin-right: 20px;">
<b>Facturas Totales </b>
<div   style="color: rgb(78, 106, 72);font-size: 15px;"><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['total_facturas']; ?>
</b></div>
</div>

<div style="margin-right: 20px;">
<b>Saldo Pendiente</b>
<div  style="font-size: 15px; color: red;"><b><?php echo $this->_tpl_vars['cabecera_estadodecuenta'][0]['saldo_pendiente']; ?>
</b></div>
</div>
</div>


<!--<TABLA DE CUENTAS POR COBRAR>-->
<div  style="background-color:#ffffff; border: 1px solid #ededed;-moz-border-radius: 7px;padding:5px; margin-top:0.3%;  font-size: 13px; ">
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
    <thead>
            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                <th align="left"><?php echo $this->_tpl_vars['campos']; ?>
</th>
            <?php endforeach; endif; unset($_from); ?>
    </thead>

<tbody>
<?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
<tr>
  <td colspan="8">
      <?php echo $this->_tpl_vars['mensaje']; ?>

  </td>
</tr>
<?php else: ?>
    <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
    <tr style=" cursor: pointer;" class="edocuenta" bgcolor="#ececec">
        <td width="25" align="center">
        <img class="boton_edocuenta" src="../../libs/imagenes/drop-add.gif">
        <input type="hidden" name="cod_cliente" value="<?php echo $this->_tpl_vars['campos']['id_cliente']; ?>
">
        <input type="hidden" name="cod_edocuenta" value="<?php echo $this->_tpl_vars['campos']['cod_edocuenta']; ?>
">
        </td>
        <td ><?php echo $this->_tpl_vars['campos']['documento']; ?>
</td>
        <td ><?php echo $this->_tpl_vars['campos']['numero']; ?>
</td>
        <td ><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['fecha_emision'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
 </td>
   	<?php if ($this->_tpl_vars['campos']['vencimiento_fecha'] == 0000 -00 -00): ?>
	<td>PENDIENTE POR AUTORIZAR </td>
	<?php else: ?>
	<td ><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['vencimiento_fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td>
	 <?php endif; ?>
        <td  ><?php echo $this->_tpl_vars['campos']['observacion']; ?>
</td>
        <td align="left"><?php echo $this->_tpl_vars['empresa'][0]['moneda']; ?>
 <?php echo $this->_tpl_vars['campos']['monto']; ?>
</td>
        <td>
        <?php if ($this->_tpl_vars['campos']['estado'] == 'Pagada'): ?>
        <img title="Pagada" src="../../libs/imagenes/ico_ok.gif">
        <?php endif; ?>

        <?php if ($this->_tpl_vars['campos']['estado'] == 'Pendiente'): ?>
            <img title="Pendiente" src="../../libs/imagenes/ico_note_1.gif">
        <?php endif; ?>


        </td>
        <td><img style="cursor: pointer;" class="edocuenta" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=edocuenta&cod=<?php echo $this->_tpl_vars['campos']['id_cliente']; ?>
'" title="Pago o Abono"  src="../../libs/imagenes/edocuenta.png"></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
</tbody>
</table>
</div>



<!--</TABLA DE CUENTAS POR COBRAR>-->














</form>

<div id="info" style="display:none;">

</div>


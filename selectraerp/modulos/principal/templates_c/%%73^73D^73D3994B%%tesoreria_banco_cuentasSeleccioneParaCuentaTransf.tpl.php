<?php /* Smarty version 2.6.21, created on 2017-08-24 11:39:58
         compiled from tesoreria_banco_cuentasSeleccioneParaCuentaTransf.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'tesoreria_banco_cuentasSeleccioneParaCuentaTransf.tpl', 65, false),)), $this); ?>
<?php echo '
<script language="JavaScript">
$(document).ready(function(){
	$("#buscar").click(function(){
		$("FORM").submit();
	});
});
function direccionar(url){
	window.location.href=url;
}

Ext.onReady(function(){

	var img=$("#imagen").val()
	var formpanel = new Ext.Panel({
		title:\' <img src=\'+img+\' width="22" height="22" class="icon" /> Seleccione La cuenta por la que se realizara la transacción\',
		autoHeight: 300,
		width: \'100%\',
	
		collapsible: false,
		titleCollapse: true ,
		contentEl:\'datosGral\',
		frame:true
	});
        formpanel.render("tesor_bancodet");
});

</script>
'; ?>


<FORM name="<?php echo $this->_tpl_vars['name_form']; ?>
" id="<?php echo $this->_tpl_vars['name_form']; ?>
" action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=<?php echo $_GET['opt_subseccion']; ?>
" method="POST">

<div  id="datosGral" class="x-hide-display" >
	<table width="100%">
		<tr >
		<td>
		<table  cellspacing="0" cellpadding="1" border="0" width="100%">
			<tbody>
			<tr align="right">
			<!--   <td width="900"><span style="float:left"><img src="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" />Seleccione La cuenta por la que emitira el cheque</span></td>-->
			<td width="75">
			<input name="imagen" id="imagen" type="hidden" value="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
">
			</td>
			<td width="75" align="right">
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
	<table class="tb-head" width="100%">
		<tr>
		<td><input type="text" name="buscar" value="<?php echo $_POST['buscar']; ?>
<?php echo $_GET['des']; ?>
" size="20"></td>
		<td>
		<select name="busqueda">
		<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values'],'selected' => $this->_tpl_vars['option_selected'],'output' => $this->_tpl_vars['option_output']), $this);?>

		</select>
		</td>
        	<td>
		<table style="cursor: pointer;" class="btn_bg" name="buscar" id="buscar"  border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
			<td class="btn_bg"><img src="../../libs/imagenes/search.gif" width="16" height="16" /></td>
			<td class="btn_bg" nowrap style="padding: 0px 4px;">Buscar</td>
			<td style="padding: 0px;" align="left"><img src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
			</tr>
		</table>
		</td>
		<td>
		<table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=<?php echo $_GET['opt_subseccion']; ?>
&cod=<?php echo $_GET['cod']; ?>
'" class="btn_bg" name="buscar" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
			<td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
			<td class="btn_bg" nowrap style="padding: 0px 4px;">Mostrar todo</td>
			<td style="padding: 0px;" align="left"><img src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
			</tr>
		</table>
		</td>
		<td width="120"><input checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
		<td width="140"><input checked="true" type="radio" name="palabra" value="todas">Todas las palabras</td>
		<td width="150"><input checked="true" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>

		<td colspan="3" width="386"></td>
		</tr>
	</table>
	<BR>
	<table width="100%" class="seleccionLista" cellspacing="0" border="0" cellpadding="1" align="center">
		<tbody>
		<tr class="tb-head" >
		<?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
			<td>
			<STRONG><?php echo $this->_tpl_vars['campos']; ?>
</STRONG>
			</td>
		<?php endforeach; endif; unset($_from); ?>
		<td>
		</td>
		</tr>
		<?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
			<td colspan="3">
			<?php echo $this->_tpl_vars['mensaje']; ?>

			</td>
		<?php else: ?>
    			<?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
				<?php if ($this->_tpl_vars['i']%2 == 0): ?>
					<tr bgcolor="">
				<?php else: ?>
					<tr  bgcolor="#e1e1e1">
				<?php endif; ?>
				<td ><?php echo $this->_tpl_vars['campos']['cod_tesor_bandodet']; ?>
</td>
				<td><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
				<td><?php echo $this->_tpl_vars['campos']['nro_cuenta']; ?>
</td>
				<td><?php echo $this->_tpl_vars['campos']['tipo_cuenta']; ?>
</td>
				<td>
				<img  style="cursor: pointer;" width="16" height="16" class="varChequera"  onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=transferencias&cod=<?php echo $_GET['cod']; ?>
&cod_cuenta=<?php echo $this->_tpl_vars['campos']['cod_tesor_bandodet']; ?>
'" title="Transferencias" src="../../libs/imagenes/add.gif">
				</td>
				</tr>
				<?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['cod_tesor_bandodet']); ?>
			<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		</tbody>
	</table>

	<table cellpadding="0" cellspacing="0" border="0" class="tb-head" width="100%">
		<tbody>
		<tr>
		<td><span>P&aacute;gina&nbsp;</span></td>
		<td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=viewcuentasByBanco&cod=<?php echo $_GET['cod']; ?>
&pagina=1&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../libs/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" border="0"></a></td>
		<td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=viewcuentasByBanco&cod=<?php echo $_GET['cod']; ?>
&pagina=<?php echo $this->_tpl_vars['pagina']-1; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../libs/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" border="0"></a></td>
		<td><input type="text" name="numero_pagina" value="<?php echo $this->_tpl_vars['pagina']; ?>
" onblur="javascript: paginacion('?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=viewcuentasByBanco&cod=<?php echo $_GET['cod']; ?>
',this.value,'&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
')" size="4"></td>
		<td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=viewcuentasByBanco&cod=<?php echo $_GET['cod']; ?>
&pagina=<?php echo $this->_tpl_vars['pagina']+1; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../libs/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" border="0"></a></td>
		<td><a href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=viewcuentasByBanco&cod=<?php echo $_GET['cod']; ?>
&pagina=<?php echo $this->_tpl_vars['num_paginas']; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img src="../../libs/imagenes/last.gif" alt="Ultima" title="Ultima" width="16" height="16" border="0"></a></td>
		<td colspan="14" width="100%" align="center">P&aacute;gina <?php echo $this->_tpl_vars['pagina']; ?>
 de <?php echo $this->_tpl_vars['num_paginas']; ?>
</td>
		</tr>
		</tbody>
	</table>
</div>
</FORM>
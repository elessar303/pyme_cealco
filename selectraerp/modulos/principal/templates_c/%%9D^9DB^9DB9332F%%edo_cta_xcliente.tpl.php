<?php /* Smarty version 2.6.21, created on 2017-08-24 11:38:53
         compiled from edo_cta_xcliente.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'edo_cta_xcliente.tpl', 67, false),)), $this); ?>
<?php echo '
<script type="text/javascript">
    $(document).ready(function(){
        $("#buscar").click(function(){
            $("form").submit();
        });
    });
    function direccionar(url){
        window.location.href=url;
    }
</script>
'; ?>

<form name="<?php echo $this->_tpl_vars['name_form']; ?>
"
	action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
"
	method="post">
	<table width="100%">
		<tr class="row-br">
			<td>
				<table class="tb-tit" cellspacing="0" cellpadding="1" border="0"
					width="100%">
					<tbody>
						<tr>
							<td width="900"><span style="float: left"><img
									src="<?php echo $this->_tpl_vars['campo_seccion'][0]['img_ruta']; ?>
" width="22" height="22"
									class="icon" /><?php echo $this->_tpl_vars['campo_seccion'][0]['nom_menu']; ?>
</span></td>

							<td width="75">
								<table style="cursor: pointer;" class="btn_bg"
									onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=add'"
									name="buscar" border="0" cellpadding="0" cellspacing="0">
									<!--  <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/add.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 4px;">Agregar</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr> -->
								</table>
							</td>
							<td width="75">
								<table style="cursor: pointer;" class="btn_bg"
									onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
'"
									name="buscar" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding: 0px;" align="right"><img
											src="../../libs/imagenes/bt_left.gif" alt="" width="4"
											height="21" style="border-width: 0px;" /></td>
										<td class="btn_bg"><img src="../../libs/imagenes/back.gif"
											width="16" height="16" /></td>
										<td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
										<td style="padding: 0px;" align="left"><img
											src="../../libs/imagenes/bt_right.gif" alt="" width="4"
											height="21" style="border-width: 0px;" /></td>
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
			<td><input type="text" name="buscar"
				value="<?php echo $_POST['buscar']; ?>
<?php echo $_GET['des']; ?>
" size="20"></td>
			<td><select name="busqueda"> <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values'],'selected' => $this->_tpl_vars['option_selected'],'output' => $this->_tpl_vars['option_output']), $this);?>

			</select>
			</td>
			<td><table style="cursor: pointer;" class="btn_bg" name="buscar"
					id="buscar" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td style="padding: 0px;" align="right"><img
							src="../../libs/imagenes/bt_left.gif" alt="" width="4"
							height="21" style="border-width: 0px;" /></td>
						<td class="btn_bg"><img src="../../libs/imagenes/search.gif"
							width="16" height="16" /></td>
						<td class="btn_bg" nowrap style="padding: 0px 4px;">Buscar</td>
						<td style="padding: 0px;" align="left"><img
							src="../../libs/imagenes/bt_right.gif" alt="" width="4"
							height="21" style="border-width: 0px;" /></td>
					</tr>
				</table></td>
			<td><table style="cursor: pointer;"
					onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'"
					class="btn_bg" name="buscar" border="0" cellpadding="0"
					cellspacing="0">
					<tr>
						<td style="padding: 0px;" align="right"><img
							src="../../libs/imagenes/bt_left.gif" alt="" width="4"
							height="21" style="border-width: 0px;" /></td>
						<td class="btn_bg"><img src="../../libs/imagenes/list.gif"
							width="16" height="16" /></td>
						<td class="btn_bg" nowrap style="padding: 0px 4px;">Mostrar todo</td>
						<td style="padding: 0px;" align="left"><img
							src="../../libs/imagenes/bt_right.gif" alt="" width="4"
							height="21" style="border-width: 0px;" /></td>
					</tr>
				</table></td>
			<td width="120"><input checked="true" type="radio" name="palabra"
				value="exacta">Palabra exacta</td>
			<td width="140"><input checked="true" type="radio" name="palabra"
				value="todas">Todas las palabras</td>
			<td width="150"><input checked="true" type="radio" name="palabra"
				value="cualquiera">Cualquier palabra</td>
			<td colspan="3" width="386"></td>
		</tr>
	</table>
	<BR>
	<table class="seleccionLista" width="100%" cellspacing="0" border="0"
		cellpadding="1" align="center">
		<tbody>
			<tr class="tb-head">
				<?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
				<td><strong><?php echo $this->_tpl_vars['campos']; ?>
</strong>
				</td> <?php endforeach; endif; unset($_from); ?>
				<td></td>
			</tr>
			<?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
			<td colspan="3"><?php echo $this->_tpl_vars['mensaje']; ?>
</td>
			<?php else: ?>
			<?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
			<?php if ($this->_tpl_vars['i']%2 == 0): ?>
			<tr bgcolor="">
			<?php else: ?>			
			<tr bgcolor="#e1e1e1">
				<?php endif; ?>
				<td><?php echo $this->_tpl_vars['campos']['cod_cliente']; ?>
</td>
				<td><?php echo $this->_tpl_vars['campos']['nombre']; ?>
</td>
				<td><?php echo $this->_tpl_vars['campos']['rif']; ?>
</td>
				<td><?php echo $this->_tpl_vars['campos']['telefonos']; ?>
</td>
				<td>
					<!--<img  style="cursor: pointer;" class="editar"  onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=edit&cod=<?php echo $this->_tpl_vars['campos']['id_proveedor']; ?>
'" title="Editar" src="../../libs/imagenes/edit.gif">
	<img  style="cursor: pointer;" class="eliminar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=delete&cod=<?php echo $this->_tpl_vars['campos']['id_proveedor']; ?>
'" title="Eliminar"  src="../../libs/imagenes/delete.gif">-->
					<!-- <img  style="cursor: pointer;" class="eliminar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=cxpFacturasMedico&cod=<?php echo $this->_tpl_vars['campos']['id_proveedor']; ?>
'" title="Facturas Por Pagar a Medicos"  src="../../libs/imagenes/factu.png"> -->
					<img style="cursor: pointer;" class="imprimir"
					onclick="javascript: window.location.href='../../reportes/rpt_cxc_estado_cuenta2.php?id_cliente=<?php echo $this->_tpl_vars['campos']['id_cliente']; ?>
';"
					title="Imprimir Estado de Cuenta"
					src="../../libs/imagenes/ico_print.gif"> <img
					style="cursor: pointer;" class="eliminar"
					onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=imprimirPresentacion&id_cliente=<?php echo $this->_tpl_vars['campos']['id_cliente']; ?>
'"
					title="Reporte de Facturas Presentadas"
					src="../../libs/imagenes/ico_list.gif"> <!-- 
<img  style="cursor: pointer;" class="eliminar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&opt_subseccion=imprimirFacturas&id_proveedor=<?php echo $this->_tpl_vars['campos']['id_proveedor']; ?>
'" title="Imprimir Relacion de Facturas/Servicios"  src="../../libs/imagenes/ico_list.gif"> -->

				</td>
			</tr>
			<?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id_cliente']); ?> <?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		</tbody>
	</table>

	<table cellpadding="0" cellspacing="0" border="0" class="tb-head"
		width="100%">
		<tbody>
			<tr>
				<td><span>P&aacute;gina&nbsp;</span></td>
				<td><a
					href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&pagina=1&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img
						src="../../libs/imagenes/first.gif" title="Primera" alt="Primera"
						width="16" height="16" border="0"> </a></td>
				<td><a
					href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&pagina=<?php echo $this->_tpl_vars['pagina']-1; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img
						src="../../libs/imagenes/prev.gif" alt="Anterior" title="Anterior"
						width="16" height="16" border="0"> </a></td>
				<td><input type="text" name="numero_pagina" value="<?php echo $this->_tpl_vars['pagina']; ?>
"
					onblur="javascript: paginacion('?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
',this.value,'&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
')"
					size="4"></td>
				<td><a
					href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&pagina=<?php echo $this->_tpl_vars['pagina']+1; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img
						src="../../libs/imagenes/next.gif" alt="Siguiente"
						title="Siguiente" width="16" height="16" border="0"> </a></td>
				<td><a
					href="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&pagina=<?php echo $this->_tpl_vars['num_paginas']; ?>
&&tipo=<?php echo $this->_tpl_vars['tipo']; ?>
&des=<?php echo $this->_tpl_vars['des']; ?>
&busqueda=<?php echo $this->_tpl_vars['busqueda']; ?>
&codigo=<?php echo $this->_tpl_vars['ultimo_cod_valor']; ?>
"><img
						src="../../libs/imagenes/last.gif" alt="Ultima" title="Ultima"
						width="16" height="16" border="0"> </a></td>
				<td colspan="14" width="100%" align="center">P&aacute;gina <?php echo $this->_tpl_vars['pagina']; ?>

					de <?php echo $this->_tpl_vars['num_paginas']; ?>
</td>
			</tr>
		</tbody>
	</table>
</FORM>
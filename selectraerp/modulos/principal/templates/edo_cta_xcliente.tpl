{literal}
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
{/literal}
<form name="{$name_form}"
	action="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}"
	method="post">
	<table width="100%">
		<tr class="row-br">
			<td>
				<table class="tb-tit" cellspacing="0" cellpadding="1" border="0"
					width="100%">
					<tbody>
						<tr>
							<td width="900"><span style="float: left"><img
									src="{$campo_seccion[0].img_ruta}" width="22" height="22"
									class="icon" />{$campo_seccion[0].nom_menu}</span></td>

							<td width="75">
								<table style="cursor: pointer;" class="btn_bg"
									onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=add'"
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
									onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}'"
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
				value="{$smarty.post.buscar}{$smarty.get.des}" size="20"></td>
			<td><select name="busqueda"> {html_options values=$option_values
					selected=$option_selected output=$option_output}
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
					onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'"
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
				{foreach from=$cabecera key=i item=campos}
				<td><strong>{$campos}</strong>
				</td> {/foreach}
				<td></td>
			</tr>
			{if $cantidadFilas == 0}
			<td colspan="3">{$mensaje}</td>
			{else}
			{foreach from = $registros key= i item = campos}
			{if $i%2==0}
			<tr bgcolor="">
			{else}			
			<tr bgcolor="#e1e1e1">
				{/if}
				<td>{$campos.cod_cliente}</td>
				<td>{$campos.nombre}</td>
				<td>{$campos.rif}</td>
				<td>{$campos.telefonos}</td>
				<td>
					<!--<img  style="cursor: pointer;" class="editar"  onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=edit&cod={$campos.id_proveedor}'" title="Editar" src="../../libs/imagenes/edit.gif">
	<img  style="cursor: pointer;" class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=delete&cod={$campos.id_proveedor}'" title="Eliminar"  src="../../libs/imagenes/delete.gif">-->
					<!-- <img  style="cursor: pointer;" class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=cxpFacturasMedico&cod={$campos.id_proveedor}'" title="Facturas Por Pagar a Medicos"  src="../../libs/imagenes/factu.png"> -->
					<img style="cursor: pointer;" class="imprimir"
					onclick="javascript: window.location.href='../../reportes/rpt_cxc_estado_cuenta2.php?id_cliente={$campos.id_cliente}';"
					title="Imprimir Estado de Cuenta"
					src="../../libs/imagenes/ico_print.gif"> <img
					style="cursor: pointer;" class="eliminar"
					onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=imprimirPresentacion&id_cliente={$campos.id_cliente}'"
					title="Reporte de Facturas Presentadas"
					src="../../libs/imagenes/ico_list.gif"> <!-- 
<img  style="cursor: pointer;" class="eliminar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=imprimirFacturas&id_proveedor={$campos.id_proveedor}'" title="Imprimir Relacion de Facturas/Servicios"  src="../../libs/imagenes/ico_list.gif"> -->

				</td>
			</tr>
			{assign var = ultimo_cod_valor value=$campos.id_cliente} {/foreach}
			{/if}
		</tbody>
	</table>

	<table cellpadding="0" cellspacing="0" border="0" class="tb-head"
		width="100%">
		<tbody>
			<tr>
				<td><span>P&aacute;gina&nbsp;</span></td>
				<td><a
					href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&pagina=1&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img
						src="../../libs/imagenes/first.gif" title="Primera" alt="Primera"
						width="16" height="16" border="0"> </a></td>
				<td><a
					href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&pagina={$pagina-1}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img
						src="../../libs/imagenes/prev.gif" alt="Anterior" title="Anterior"
						width="16" height="16" border="0"> </a></td>
				<td><input type="text" name="numero_pagina" value="{$pagina}"
					onblur="javascript: paginacion('?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}',this.value,'&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}')"
					size="4"></td>
				<td><a
					href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&pagina={$pagina+1}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img
						src="../../libs/imagenes/next.gif" alt="Siguiente"
						title="Siguiente" width="16" height="16" border="0"> </a></td>
				<td><a
					href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&pagina={$num_paginas}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img
						src="../../libs/imagenes/last.gif" alt="Ultima" title="Ultima"
						width="16" height="16" border="0"> </a></td>
				<td colspan="14" width="100%" align="center">P&aacute;gina {$pagina}
					de {$num_paginas}</td>
			</tr>
		</tbody>
	</table>
</FORM>

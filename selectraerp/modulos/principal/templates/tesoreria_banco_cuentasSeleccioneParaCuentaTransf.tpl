{literal}
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
		title:' <img src='+img+' width="22" height="22" class="icon" /> Seleccione La cuenta por la que se realizara la transacción',
		autoHeight: 300,
		width: '100%',
	
		collapsible: false,
		titleCollapse: true ,
		contentEl:'datosGral',
		frame:true
	});
        formpanel.render("tesor_bancodet");
});

</script>
{/literal}

<FORM name="{$name_form}" id="{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion={$smarty.get.opt_subseccion}" method="POST">

<div  id="datosGral" class="x-hide-display" >
	<table width="100%">
		<tr >
		<td>
		<table  cellspacing="0" cellpadding="1" border="0" width="100%">
			<tbody>
			<tr align="right">
			<!--   <td width="900"><span style="float:left"><img src="{$campo_seccion[0].img_ruta}" width="22" height="22" class="icon" />Seleccione La cuenta por la que emitira el cheque</span></td>-->
			<td width="75">
			<input name="imagen" id="imagen" type="hidden" value="{$campo_seccion[0].img_ruta}">
			</td>
			<td width="75" align="right">
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
	<table class="tb-head" width="100%">
		<tr>
		<td><input type="text" name="buscar" value="{$smarty.post.buscar}{$smarty.get.des}" size="20"></td>
		<td>
		<select name="busqueda">
		{html_options values=$option_values selected=$option_selected output=$option_output}
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
		<table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion={$smarty.get.opt_subseccion}&cod={$smarty.get.cod}'" class="btn_bg" name="buscar" border="0" cellpadding="0" cellspacing="0">
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
		{foreach from=$cabecera key=i item=campos}
			<td>
			<STRONG>{$campos}</STRONG>
			</td>
		{/foreach}
		<td>
		</td>
		</tr>
		{if $cantidadFilas == 0}
			<td colspan="3">
			{$mensaje}
			</td>
		{else}
    			{foreach from = $registros key = i item = campos}
				{if $i%2==0}
					<tr bgcolor="">
				{else}
					<tr  bgcolor="#e1e1e1">
				{/if}
				<td >{$campos.cod_tesor_bandodet}</td>
				<td>{$campos.descripcion}</td>
				<td>{$campos.nro_cuenta}</td>
				<td>{$campos.tipo_cuenta}</td>
				<td>
				<img  style="cursor: pointer;" width="16" height="16" class="varChequera"  onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=transferencias&cod={$smarty.get.cod}&cod_cuenta={$campos.cod_tesor_bandodet}'" title="Transferencias" src="../../libs/imagenes/add.gif">
				</td>
				</tr>
				{assign var = ultimo_cod_valor value=$campos.cod_tesor_bandodet}
			{/foreach}
		{/if}
		</tbody>
	</table>

	<table cellpadding="0" cellspacing="0" border="0" class="tb-head" width="100%">
		<tbody>
		<tr>
		<td><span>P&aacute;gina&nbsp;</span></td>
		<td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=viewcuentasByBanco&cod={$smarty.get.cod}&pagina=1&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../libs/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" border="0"></a></td>
		<td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=viewcuentasByBanco&cod={$smarty.get.cod}&pagina={$pagina-1}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../libs/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" border="0"></a></td>
		<td><input type="text" name="numero_pagina" value="{$pagina}" onblur="javascript: paginacion('?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=viewcuentasByBanco&cod={$smarty.get.cod}',this.value,'&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}')" size="4"></td>
		<td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=viewcuentasByBanco&cod={$smarty.get.cod}&pagina={$pagina+1}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../libs/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" border="0"></a></td>
		<td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=viewcuentasByBanco&cod={$smarty.get.cod}&pagina={$num_paginas}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../libs/imagenes/last.gif" alt="Ultima" title="Ultima" width="16" height="16" border="0"></a></td>
		<td colspan="14" width="100%" align="center">P&aacute;gina {$pagina} de {$num_paginas}</td>
		</tr>
		</tbody>
	</table>
</div>
</FORM>

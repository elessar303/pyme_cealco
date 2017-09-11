<script src="../../libs/js/cxp_facturas_list.js" type="text/javascript"></script>
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

</script>
{/literal}

<div id="loading" style="position:absolute; width:80%; text-align:center; top:180px; visibility:hidden;">
<img src="../../libs/imagenes/36.gif" border=0></div>
<FORM name="{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}" method="POST">
    <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="{$campo_seccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                      
                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=addFac&cod2={$edoCta}&cod={$cod}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/add.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 4px;">Agregar</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                       
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
<table class="tb-head" width="100%">
  <tr>
      <td><input type="text" name="buscar" value="{$smarty.post.buscar}{$smarty.get.des}" size="20"></td>
	<td>
<select name="busqueda">
    
    {html_options values=$option_values selected=$option_selected output=$option_output}
</select>
        </td>
        <td><table style="cursor: pointer;" class="btn_bg" name="buscar" id="buscar"  border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
		  <td class="btn_bg"><img src="../../libs/imagenes/search.gif" width="16" height="16" /></td>
		  <td class="btn_bg" nowrap style="padding: 0px 4px;">Buscar</td>
		  <td style="padding: 0px;" align="left"><img src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
		</tr>
	  </table></td>
	<td><table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&cod2={$edoCta}'" class="btn_bg" name="buscar" border="0" cellpadding="0" cellspacing="0">
		 <tr>
                  <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
		  <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
		  <td class="btn_bg" nowrap style="padding: 0px 4px;">Mostrar todo</td>
		  <td style="padding: 0px;" align="left"><img src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
		</tr>
	  </table></td>
	<td width="120"><input checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input checked="true" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input checked="true" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>

	<td colspan="3" width="386"></td>
  </tr>
</table>
<BR>
<table class="seleccionLista" width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
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
        <td >{$campos.cod_factura}</td>
        <td>{$campos.cod_cont_factura}</td>
	<td >{$campos.fecha_factura}</td>
        <td>{$campos.fecha_recepcion}</td>
	<td style="color:red; font-weight:bold;">{$campos.total_a_pagar|number_format:2:",":"."}</td>
        <td>
<img  style="cursor: pointer;" class="editar"  onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=view&fac={$campos.id_factura}&cod2={$edoCta}&cod={$cod}'" title="Ver Documeto (FAC, NC, ND)" src="../../libs/imagenes/view.gif">

{if $campos.cxp_factura_pago_pk==NULL && $campos.cod_estatus==1}
<img  style="cursor: pointer;" class="eliminar" onclick="javascript:anularFactura('{$campos.id_factura}')" title="Anular Documento"  src="../../libs/imagenes/cancel.gif">
{else}
<img  style="cursor: pointer;" class="eliminar" title="No puede anular, tiene pago asociado o ya fue anulada"  src="../../libs/imagenes/ico_est6.gif">
{/if}

{if $campos.montoretislr>0 && ($campos.cod_estatus==1 || $campos.cod_estatus==2)}
<img  style="cursor: pointer;" class="imprimir" onclick="javascript: window.location.href='../../reportes/rpt_reporte_islr.php?id_factura={$campos.id_factura}';" title="Imprimir Comprobante del I.S.L.R."  src="../../libs/imagenes/ico_print.gif">
{else}
<img  style="cursor: pointer;" class="eliminar" title="No posee retención de ISLR"  src="../../libs/imagenes/ico_est6.gif">
{/if}

{if $campos.monto_retenido>0 && ($campos.cod_estatus==1 || $campos.cod_estatus==2)}
<img  style="cursor: pointer;" class="imprimir" onclick="javascript: window.location.href='../../reportes/rpt_reporte_iva.php?id_factura={$campos.id_factura}';" title="Imprimir Comprobante del I.V.A."  src="../../libs/imagenes/ico_print.gif">
{else}
<img  style="cursor: pointer;" class="eliminar" title="No posee retención de IVA"  src="../../libs/imagenes/ico_est6.gif">
{/if}


        </td>
    </tr>
    {assign var = ultimo_cod_valor value=$campos.num_fac}
    {/foreach}
{/if}
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="tb-head" width="100%">
  <tbody>
    <tr>
      <td><span>P&aacute;gina&nbsp;</span></td>
      <td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}&pagina=1&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../libs/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" border="0"></a></td>
      <td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}&pagina={$pagina-1}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../libs/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" border="0"></a></td>
      <td><input type="text" name="numero_pagina" value="{$pagina}" onblur="javascript: paginacion('?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}',this.value,'&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}')" size="4"></td>
      <td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}&pagina={$pagina+1}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../libs/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" border="0"></a></td>
      <td><a href="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=facturasCXP&cod={$cod}&cod2={$edoCta}&pagina={$num_paginas}&&tipo={$tipo}&des={$des}&busqueda={$busqueda}&codigo={$ultimo_cod_valor}"><img src="../../libs/imagenes/last.gif" alt="Ultima" title="Ultima" width="16" height="16" border="0"></a></td>
      <td colspan="14" width="100%" align="center">P&aacute;gina {$pagina} de {$num_paginas}</td>
    </tr>
  </tbody>
</table></FORM>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        {literal}
            <script type="text/javascript">//<![CDATA[
    $(document).ready(function(){
            $("#buscar").click(function(){
                    $("form").submit();
            });
    });

    function direccionar(url){
            window.location.href=url;
    }

    Ext.onReady(function(){
    var img=$("#imagen").val();
    var formpanel = new Ext.Panel({
                    title:' <img src='+img+' width="22" height="22" class="icon" /> Transacciones Realizadas',
                    autoHeight: 300,
                    width: '100%',
                    collapsible: false,
                    titleCollapse: true ,
                    contentEl:'datosGral',
                    frame:true
            });
            formpanel.render("transferencia");
    });
    //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form name="{$name_form}" id="{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion={$smarty.get.opt_subseccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td width="900"></td>
                                        <td width="75">
                                            <input name="imagen" id="imagen" type="hidden" value="{$campo_seccion[0].img_ruta}">
                                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=hacerTransferencia&cod={$cod}&cod_cuenta={$cod_cuenta}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                    <td class="btn_bg"><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                                    <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Agregar</td>
                                                    <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="75">
                                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=verCuentasPorBanco&cod={$cod}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                    <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                    <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                    <td style="padding: 0px; text-align: left;"><img  src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="tb-head" style="width: 100%;">
                    <tr>
                        <td><input type="text" name="buscar" value="{$smarty.post.buscar}{$smarty.get.des}" size="20"/></td>
                        <td>
                            <select name="busqueda">
                                {html_options values=$option_values selected=$option_selected output=$option_output}
                            </select>
                        </td>
                        <td>
                            <table style="cursor: pointer;" class="btn_bg" id="buscar">
                                <tr>
                                    <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../../includes/imagenes/search.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Buscar</td>
                                    <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion={$smarty.get.opt_subseccion}&cod={$smarty.get.cod}'" class="btn_bg" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../../includes/imagenes/list.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Mostrar todo</td>
                                    <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td> 
                        <td style="width:120px;"><input type="radio" name="palabra" value="exacta" />Palabra exacta</td>
                        <td style="width:140px;"><input type="radio" name="palabra" value="todas" />Todas las palabras</td>
                        <td style="width:150px;"><input type="radio" name="palabra" value="cualquiera" checked/>Cualquier palabra</td>
                        <td style="width:386px;"></td>
                    </tr>
                </table>
                <br/>
                <table class="seleccionLista" style="width: 100%;">
                    <tbody>
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td>
                                    <strong>{$campos}</strong>
                                </td>
                            {/foreach}
                            <!--td>Opciones</td-->
                        </tr>
                        {if $cantidadFilas == 0}
                            <tr>
                                <td colspan="3" style="text-align: center;">
                                    {$mensaje}
                                </td>
                            </tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td>{$campos.tipo}</td>
                                    <td>{$campos.transferencia_numero}</td>
                                    <td>{$campos.descripcion}</td>
                                    <td>{$campos.concepto}</td>
                                    <td style="text-align: center;">{$campos.fecha|fecha}</td>
                                    <td style="color:red; font-weight:bold; text-align: right; padding-right: 20px;">{$campos.monto|number_format:2:",":"."}</td>
                                    <td style="text-align: right; padding-right: 20px;">{$campos.descest}</td>
                                    <!--td>
                                        <img  style="cursor: pointer;" width="16" height="16" class="varChequera"  onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=hacerTransferencia&cod={$smarty.get.cod}&cod_cuenta={$campos.cod_tesor_bandodet}'" title="Transferir" src="../../../includes/imagenes/add.gif"/>
                                    </td-->
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.cod_tesor_bandodet}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                <table class="tb-head" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td><span>P&aacute;gina&nbsp;</span></td>
                            <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=viewcuentasByBanco&amp;cod={$smarty.get.cod}&amp;pagina=1&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" /></a></td>
                            <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=viewcuentasByBanco&amp;cod={$smarty.get.cod}&amp;pagina={$pagina-1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" /></a></td>
                            <td><input type="text" name="numero_pagina" value="{$pagina}" onblur="javascript: paginacion('?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=viewcuentasByBanco&amp;cod={$smarty.get.cod}',this.value,'&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}')" size="4"/></td>
                            <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=viewcuentasByBanco&amp;cod={$smarty.get.cod}&amp;pagina={$pagina+1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" /></a></td>
                            <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=viewcuentasByBanco&amp;cod={$smarty.get.cod}&amp;pagina={$num_paginas}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/last.gif" alt="&Uacute;ltima" title="&Uacute;ltima" width="16" height="16" /></a></td>
                            <td colspan="14" style="width: 100%; text-align: center;">P&aacute;gina {$pagina} de {$num_paginas}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
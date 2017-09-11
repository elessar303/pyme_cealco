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
    //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form name="{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}" method="post">
            <table style="width:100%">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 900px;"><span style="float:left"><img src="{$campo_seccion[0].img_ruta}" width="22" height="22" class="icon" />{$datos_banco[0].descripcion_banco}, Cuenta {$datos_banco[0].nro_cuenta} - {$subseccion[0].descripcion}</span></td>
                                    <td style="width: 75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=addMovimientoCuentaByBanco&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}'">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Agregar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width: 75;">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion=93&amp;opt_subseccion=viewmovimientosByBanco&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}'">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
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
                    <td><input type="text" name="buscar" value="{$smarty.post.buscar}{$smarty.get.des}" size="20"></td>
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
                        <table style="cursor: pointer;" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}'" class="btn_bg">
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
            <BR>
            <table class="seleccionLista" style="width:100%;">
                <tbody>
                    <tr class="tb-head" >
                        {foreach from=$cabecera key=i item=campos}
                            <td>
                                <strong>{$campos}</strong>
                            </td>
                        {/foreach}
                        <td colspan="2">
                            <strong>Opciones</strong>
                        </td>
                    </tr>
                    {if $cantidadFilas == 0}
                        <tr>
                            <td colspan="8" style="text-align: center;">
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
                                <td style="text-align: center; width: 100px;">{$campos.cod_movimiento_ban}</td>
                                <td>{$campos.descripcion}</td>
                                <td style="text-align: right; padding-right: 20px;">{$campos.numero_movimiento}</td>
                                <td style="text-align: center;">{$campos.fecha_movimiento|date_format:"%d/%m/%Y"}</td>
                                <td style="text-align: right; padding-right: 20px;">{$campos.monto|number_format:2:",":"."}</td>
                                <td>{$campos.concepto}</td>
                                <td style="text-align: center; width: 50px;">
                                    <img style="cursor: pointer;" class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=editMovimientoCuentaByBanco&cod={$smarty.get.cod}&cod_cuenta={$campos.cod_tesor_bancodet}&cod_movimiento_ban={$campos.cod_movimiento_ban}&pagina={$smarty.get.pagina}'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                                </td>
                                <td style="text-align: center; width: 50px;">
                                    <img style="cursor: pointer;" width="16" height="16" class="eliminar"  onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=deleteMovimientoCuentaByBanco&cod={$smarty.get.cod}&cod_cuenta={$campos.cod_tesor_bancodet}&cod_movimiento_ban={$campos.cod_movimiento_ban}&pagina={$smarty.get.pagina}'" title="Eliminar Movimiento Bancario" src="../../../includes/imagenes/delete.gif"/>
                                </td>
                            </tr>
                            {assign var=ultimo_cod_valor value=$campos.cod_chequera}
                        {/foreach}
                    {/if}
                </tbody>
            </table>
            <table class="tb-head" style="width: 100%">
                <tbody>
                    <tr>
                        <td><span>P&aacute;gina&nbsp;</span></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;pagina=1&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" border="0"></a></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;pagina={$pagina-1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" border="0"></a></td>
                        <td><input type="text" name="numero_pagina" value="{$pagina}" onblur="javascript: paginacion('?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}',this.value,'&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}')" size="4"></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;pagina={$pagina+1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" border="0"></a></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;pagina={$num_paginas}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/last.gif" alt="&Uacute;ltima" title="&Uacute;ltima" width="16" height="16"></a></td>
                        <td colspan="14" style="width: 100%; text-align: center;">P&aacute;gina {$pagina} de {$num_paginas}</td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <table class="tb-head">
            <tr>
                <td><input id="inputbuscar" name="buscar" type="text" value="{$smarty.post.buscar}{$smarty.get.des}" size="20"/></td>
                <td>
                    <select name="busqueda">
                        {html_options values=$option_values selected=$option_selected output=$option_output}
                    </select>
                </td>
                <td>
                    <table style="cursor: pointer; padding: 0px;" class="btn_bg" id="buscar">
                        <tr>
                            <td><img src="../../../includes/imagenes/bt_left.gif" alt="" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                            <td class="btn_bg"><img src="../../../includes/imagenes/search.gif" width="16" height="16" /></td>
                            <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Buscar</td>
                            <td><img src="../../../includes/imagenes/bt_right.gif" alt="" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table style="cursor:pointer; padding:0px;" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'" class="btn_bg" >
                        <tr>
                            <td><img src="../../../includes/imagenes/bt_left.gif" alt="" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                            <td class="btn_bg"><img src="../../../includes/imagenes/list.gif" width="16" height="16" /></td>
                            <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Mostrar todo</td>
                            <td><img src="../../../includes/imagenes/bt_right.gif" alt="" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                        </tr>
                    </table>
                </td>
                <td style="width:120px;"><input type="radio" name="palabra" value="exacta" />Palabra exacta</td>
                <td style="width:150px;"><input type="radio" name="palabra" value="cualquiera"/>Cualquier palabra</td>
                <td style="width:140px;"><input type="radio" name="palabra" value="todas" checked/>Todas las palabras</td>
                <td style="width:386px;"></td>
            </tr>
        </table>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <table class="navegacion" style="width: 100%;">
            <tr>
                <td>
                    <table class="tb-tit" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="float:left">
                                        <input name="imagen" id="imagen" type="hidden" value="{$campo_seccion[0].img_ruta}"/>
                                    </span>
                                </td>
                                <td class="btn" style="float:right; padding-right: 15px;">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px;">Regresar</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                    <!-- Estudiar la posibilidad de sustituit la tabla anterior por el snippets regresar_boton.tpl-->
                                </td>
                                <td class="btn" style="float:right">
                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=addDesp&amp;cod={$smarty.get.cod}'">
                                        <tr>
                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                            <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" /></td>
                                            <td style="padding: 0px 4px;">Agregar</td>
                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
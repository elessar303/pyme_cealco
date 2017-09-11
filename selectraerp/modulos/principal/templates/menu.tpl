<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
    </head>
    <body>
        <table style="width: 100%;">
            <tr>
                <td colspan="2">
                    <img src="../../../includes/imagenes/sep_menu2.png" width="200"/>
                </td>
            </tr>
            {foreach from= $itemMenuPrincipal key=i item=matriz}
                <tr style="cursor: pointer;">
                    <td style="width:35px;">
                        <span style="float:left">
                            <img src="{$matriz.img_ruta}" style="text-align: center;"/>
                        </span>
                    </td>
                    <td style="height:40px;" class="menu">
                        <a href="?opt_menu={$matriz.cod_modulo}">{$matriz.nom_menu}</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <img src="../../../includes/imagenes/sep_menu2.png" width="200"/>
                    </td>
                </tr>
            {/foreach}
        </table>
    </body>
</html>
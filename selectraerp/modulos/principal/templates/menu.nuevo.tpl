<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes"/>
        <title></title>
        <link href="../../../includes/css/menu.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <ul>
            {foreach key=i item=matriz from= $itemMenuPrincipal}
            <li><a href="?opt_menu={$matriz.cod_modulo}"><img src="{$matriz.img_ruta}"/>{$matriz.nom_menu}</a></li>
            {/foreach}
        </ul>
    </body>
</html>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <table class="tb-head">
            <tbody>
                <tr>
                    <td><span>P&aacute;gina&nbsp;</span></td>
                    <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;pagina=1&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;id={$ultimo_cod_valor}&amp;cod={$smarty.get.cod}"><img src="../../../includes/imagenes/b_firstpage.png" title="Primera" alt="Primera" style="width:16px; height: 16px; border: 0px"/></a></td>
                    <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;pagina={$pagina-1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;id={$ultimo_cod_valor}&amp;cod={$smarty.get.cod}"><img src="../../../includes/imagenes/b_prevpage.png" alt="Anterior" title="Anterior" style="width:16px; height: 16px; border: 0px"/></a></td>
                    <td><input type="text" name="numero_pagina" value="{$pagina}" onblur="javascript:paginacion('?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}',this.value,'&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;id={$ultimo_cod_valor}&amp;cod={$smarty.get.cod}');" size="4"></td>
                    <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;pagina={$pagina+1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;id={$ultimo_cod_valor}&amp;cod={$smarty.get.cod}"><img src="../../../includes/imagenes/b_nextpage.png" alt="Siguiente" title="Siguiente" style="width:16px; height: 16px; border: 0px"/></a></td>
                    <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;pagina={$num_paginas}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;id={$ultimo_cod_valor}&amp;cod={$smarty.get.cod}"><img src="../../../includes/imagenes/b_lastpage.png" alt="&Uacute;ltima" title="&Uacute;ltima" style="width:16px; height: 16px; border: 0px"/></a></td>
                    <td style="width:100%; text-align:center;">&nbsp;P&aacute;gina {$pagina} de {$num_paginas}</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes"/>
        <meta name="email" content="cjvrinf@gmail.com"/>
        {assign var=name_form value="opciones"}
        {include file="snippets/header_form.tpl"}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}">
            <input name="imagen" id="imagen" type="hidden" value="{$cabeceraSeccionesByOptMenu[0].img_ruta}"/>
            <div id="datosGral" class="x-hide-display">
                <table style="width: 100%">
                    <tr>
                        <td>
                            <div>
                                <table style="width: 100%">
                                    <tr>
                                        <td>
                                            <table style="width: 100%">
                                                {assign var=contarTD value=0}
                                                {assign var=contarTR value=0}
                                                {assign var=sw value=0}
                                                {foreach from=$seccionesByOptMenu key=i item=varArreglo }
                                                    {if $varArreglo.nom_menu <> ""}
                                                        {if $i%5 eq 0 and $contarTD eq 0}
                                                            <tr>
                                                            {/if}
                                                            <td style="width: 20%">
                                                                <div class="box">
                                                                    <table width="150" height="90" border="0" cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$varArreglo.cod_modulo}'">
                                                                        <tr>
                                                                            <td>
                                                                                <div style="text-align:center">
                                                                                    <img width="45" height="45" src="{$varArreglo.img_ruta}" class="icon"/>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div style="text-align:center; color:#048; font: 1.0em Verdana, Arial, sans-serif; font-weight:normal;">{$varArreglo.nom_menu}</div>
                                                                                <!--div style="text-align:center; color:grey; font: 1.1em Verdana, Arial, sans-serif; font-weight:bold;" class="boton-text">{$varArreglo.nom_menu}</div-->
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                            {assign var = sw value= 1}
                                                            {assign var = contarTD value= $contarTD+1}
                                                            {if $contarTD%5 eq 0 and $i <> 0 or  $contarTD%5 eq 0 and $i <> 5}
                                                                {assign var = contarTD value= 0}
                                                            </tr>
                                                        {/if}
                                                    {/if}
                                                {/foreach}
                                                {if $contarTD <> 5 and $sw eq 1}
                                                </table>
                                            {/if}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function(){
                $("input[name='cancelar'], input[name='eliminar']").button();
            });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="" method="post">
            <div id="datosGral">
                {if $smarty.get.loc}
                     {include file = "snippets/regresar_boton_alm_loc.tpl"}   
                {else}
                     {include file = "snippets/regresar_boton.tpl"}
                {/if}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head" style="text-align:center;">
                                ELIMINAR EL DISTRITO ESCOLAR
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="label">
                                C&oacute;digo
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" readonly name="id_distrito" value="{$datos_distrito[0].id}" id="id_distrito" class="form-text"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="label">
                                Descripci&oacute;n
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="descripcion_distrito" readonly value="{$datos_distrito[0].descripcion}" size="60" id="descripcion_distrito" class="form-text"/>
                            </td>
                        </tr>                       
                          <tr>
                            <td colspan="3" class="label">
                              Ministerio
                            </td>
                            <td style="padding-top:2px; padding-bottom: 2px;">
                               <select name="id_localidad" id="id_localidad" class="form-text" disabled>
                                 {html_options values=$option_values_ministerio output=$option_output_ministerio selected=$option_selected_ministerio}
                               </select>
                               
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="eliminar" id="aceptar" value="Eliminar Distrito" />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>

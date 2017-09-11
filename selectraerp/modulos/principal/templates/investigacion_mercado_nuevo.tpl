<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_mercadeo_entrada.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formMercadeo.js"></script>
        {literal}
        <script language="JavaScript" type="text/JavaScript">

        function soloNumeros(e){
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 57)
        }

        /*$(document).ready(function(){
        //funcion para cargar los puntos 
                  $("#estado").change(function() {
                    estados = $("#estado").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getPuntos&'+'estados='+estados,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#puntodeventa").find("option").remove();
                                $("#puntodeventa").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#puntodeventa").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#puntodeventa").append("<option value='0'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#puntodeventa").append("<option value='" + this.vcampos[i].siga+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                                }
                            }
                        }); 
                        $("#puntodeventa").val(0);
                  });
        });*/

        </script>
        {/literal}
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        {literal}
        <style type="text/css">
        .invisible
        {
        display: none;
        }
        </style>
        {/literal}
   
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="Datosproveedor" value=""/>
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <table style="width:100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:900px;">
                                        <span style="float:left;">
                                            <img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon"/>
                                            {$subseccion[0].descripcion}
                                        </span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';">
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
            <!--<Datos del proveedor y vendedor>-->
            <div id="dp" class="x-hide-display">
                <br/>
                <table>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Creado Por (*)</b></span>
                        </td>
                        <td>
                            <!--input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="{$detalles_pendiente[0].autorizado_por}"/-->
                            <input type="text" maxlength="100" name="creado_por" id="creado_por" value="{$nombre_usuario}" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Observaciones</b></span>
                        </td>
                        <td>
                            <input type="text" name="observaciones" maxlength="100" id="observaciones" value="{$detalles_pendiente[0].observacion}" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha de Creación</b></span>
                        </td>
                        <td>
                            <input type="text" name="input_fechacompra" id="input_fechacompra" value='{$smarty.now|date_format:"%Y-%m-%d"}' size="30" maxlength="70" class="form-text" readonly="readonly"/>
                            <!--div style="color:#4e6a48" id="fechacompra">{$smarty.now|date_format:"%d-%m-%Y"}</div-->
                            {literal}
                                <script type="text/javascript">//<![CDATA[
                                //comentado el 4/02/15 no me parece correcto que se ponga cuaquier fecha. daniel
                                    // var cal = Calendar.setup({
                                    //     onSelect: function(cal) {
                                    //         cal.hide();
                                    //     }
                                    // });
                                    // cal.manageFields("input_fechacompra", "input_fechacompra", "%Y-%m-%d");
                                //]]>
                                </script>
                            {/literal}
                        </td>
                    </tr>

                    <tr>    
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Estado del Mercadeo</b></span>
                        </td>
                            <!--ESTADOS-->
                        <td> 
                                <select name="estado" id="estado" class="form-text" style="width:205px">
                                    <option value="0">Todos</option>
                                    {html_options values=$option_values_id_estado output=$option_output_nombre_estado}
                                </select>
                        </td>
                    </tr>

                </table>
            </div>
            <!--</Datos del proveedor y vendedor>-->
            <div id="dcompra" class="x-hide-display"></div>
            <div id="PanelGeneralCompra">
                <div id="tabproducto" class="x-hide-display">
                    <div id="contenedorTAB">
                        <div id="div_tab1">
                            <div class="grid">
                                <table class="lista" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <!-- <th class="tb-tit" style="text-align: center;">Id del Rubro</th> -->
                                            <th class="tb-tit" style="text-align: center;">Establecimiento</th>
                                            <th class="tb-tit" style="text-align: center;">Descripci&oacute;n</th>
                                            <th class="tb-tit" style="text-align: center;">Precio</th>
                                            <th class="tb-tit" style="text-align: center;">Opci&oacute;n</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {if $cod neq ""}
                                            {foreach from=$productos_estudio_mercado key=i item=prod}
                                                <tr>
                                                    <!-- <td style="text-align: left; padding-left: 20px;">{$prod.producto}</td> -->
                                                    <td style="text-align: left; padding-left: 20px;">{$prod.nombre_rubro}</td>
                                                    <td style="text-align: right; padding-right: 20px;">{$prod.precio}</td>
                                                    <td></td>
                                                </tr>
                                            {/foreach}
                                        {/if}
                                    </tbody>
                                    <tfoot>
                                        <tr class="sf_admin_row_1">
                                            <td colspan="4">
                                                <div class="span_cantidad_items">
                                                    <span style="font-size: 12px; font-style: italic; text-align: left;">Cantidad de Items: 0</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tabpago" class="x-hide-display">
                    <div id="contenedorTAB21">
                        <!-- TAB1 -->
                        <div class="tabpanel2">
                            <table></table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items"/>
            <div id="displaytotal" class="x-hide-display"></div>
            <div id="displaytotal2" class="x-hide-display"></div>
        </form>
        <div id="incluirproducto" class="x-hide-display">
            <p>
                <label for="establecimiento"><b>Establecimiento</b></label><br/>
                <select id="establecimiento" name="establecimiento" class="form-text" style="width:205px">
                    <option value="0">Seleccione el Establecimiento</option>
                    {html_options values=$option_values_id_establecimiento output=$option_output_nombre_establecimiento selected=$establecimiento}
                    
                </select>
            </p>
            <p>
                <label for="rubros"><b>Rubros</b></label><br/>
                <select id="rubros" name="rubros" class="form-text" style="width:100%">
                    <option value="0">Seleccione el Rubro</option>
                    {html_options values=$option_values_id_rubro output=$option_output_nombre_rubro selected=$rubros}
                </select>
            </p>
             <p>
                <label><b>Precio / Costo</b></label><br/>
                <input type="text" name="precio" id="precio" class="form-text"/>
            </p>
        </div>
    </body>
</html>
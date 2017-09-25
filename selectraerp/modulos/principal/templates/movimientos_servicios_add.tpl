<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function()
            {
                
                //funcion para buscar el producto
                $("button[name='buscar2']").click(function()
                {
                    var value = $(this).val();
                    pBuscaItem.main.mostrarWin();
                });
                
            });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="formulario" name="formulario"  method="post">
            <div id="datosGral">
                <!--botton atras personalizado-->
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
                                                    <table class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=movimientoservicio&amp;idmovimiento={$idmovimiento}'">
                                                        <tr>
                                                            <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                                            <td><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                            <td style="padding: 0px 4px;">Regresar</td>
                                                            <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" /></td>
                                                        </tr>
                                                    </table>
                                                    <!-- Estudiar la posibilidad de sustituit la tabla anterior por el snippets regresar_boton.tpl-->
                                                </td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </body>
                </html>
                <!--fin botones personales-->
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <input type="hidden" name="id_almacen" value="{$smarty.get.cod}"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head" style="text-align:center;">
                                &nbsp;
                                Agregar Servicio a la Ubicación
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                      
                        <tr>
                            <td colspan="3" class="label">
                                C&Oacute;DIGO DEL SERVICIO</td>
                             <td>
                                <input type="text" name="filtro_codigo" id="filtro_codigo" style="float: left;" class="form-text" />
                                <input type="hidden" name="idmovimiento" id="idmovimiento" value="{$idmovimiento}" />
                                <input type="hidden" name="cod" id="cod" value="{$cod}" />
                                <button type="button" id="buscar2" name="buscar2">Buscar</button>
                            </td>
                        </tr> 
                          
                    </tbody>
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        {literal}
            <script type="text/javascript">//<![CDATA[
                function valida_envia(){
                    //valido el codigo
                    if (document.formulario.fecha_desde.value.length==0||document.formulario.fecha_hasta.value.length==0){
                        alert("Debe proporcionar ambas Fechas para el Reporte")
                        if (document.formulario.fecha_desde.value.length==0){document.formulario.fecha_desde.focus()}
                        else if(document.formulario.fecha_desde.value.length==0){document.formulario.fecha_hasta.focus()}
                        return false;
                    }
                    else if(document.formulario.fecha_desde.value.length>document.formulario.fecha_hasta.value.length){
                        alert("Debe Proporcionar un Rango de Fechas Valido para el Reporte")
                    }
                    var fecha_desde=document.formulario.fecha_desde.value;
                    var fecha_hasta=document.formulario.fecha_hasta.value;
                    var tipomov=document.formulario.tipomov.value;
                    var cuenta_banco=document.formulario.cuenta_banco.value;
                    //alert("banco:"+banco+"\ntipo mov:"+tipomov+"\nfecha desde:"+fecha_desde+"\nfecha hasta:"+fecha_hasta)

                    if (document.formulario.reporte.value==1){
                                        //location.href="../fpdf/r_bancariosconsaldopdf.php?banco="+banco+"&tipomov="+tipomov+"&fechaDesde="+fechaD+"&fechaHasta="+fechaH;
                            window.open('../../reportes/rpt_movimientos_bancarios_consaldo_pdf.php?fecha_desde='+fecha_desde+"&fecha_hasta="+fecha_hasta+"&cuenta_banco="+cuenta_banco+"&tipomov="+tipomov);
                    }else if (document.formulario.reporte.value==2){
                                        //location.href=\"../fpdf/r_movimientospdf.php?banco=".$_POST['banco']."&tipomov=".$_POST['tipomov']."&fechaDesde=".$_POST['fechaD']."&fechaHasta=".$_POST['fechaH']";
                            window.open('../../reportes/rpt_movimientos_bancarios_pdf.php?fecha_desde='+fecha_desde+"&fecha_hasta="+fecha_hasta+"&cuenta_banco="+cuenta_banco+"&tipomov="+tipomov);
                    }
                    //el formulario se envia
                    document.formulario.submit();
                }//]]>
            </script>
        {/literal}
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <table style="width:100%">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%">
                            <tbody>
                                <tr>
                                    <td width="900">
                                        <span style="float:left">
                                            <img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />
                                            {$subseccion[0].descripcion}
                                        </span>
                                    </td>
                                    <td width="75">
                                    </td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion=93&amp;opt_subseccion=viewmovimientosByBanco&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}'">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="{$reporte}" name="reporte" id="reporte"/>
            <input type="hidden" value="{$cuenta_banco}" name="cuenta_banco" id="cuenta_banco"/>
            <table style="width: 100%">
                <tr>
                    <td class="tb-head" style="width: 170px">Fecha Desde **</td>
                    <td>
                        <input type="text" name="fecha_desde" id="fecha_desde" size="20" value="{$campos_item[0].fecha}"/>
                        {literal}
                            <script type="text/javascript">//<![CDATA[
                                var cal = Calendar.setup({
                                    onSelect: function(cal) { cal.hide() }
                                });
                                cal.manageFields("fecha_desde", "fecha_desde", "%Y-%m-%d");
                            //]]>
                            </script>
                        {/literal}
                    </td>
                </tr>
                <tr>
                    <td class="tb-head">Fecha Hasta **</td>
                    <td>
                        <input type="text" name="fecha_hasta" id="fecha_hasta" size="20" value="{$campos_item[0].fecha_hasta}"/>
                        {literal}
                            <script type="text/javascript">//<![CDATA[
                                var cal = Calendar.setup({
                                    onSelect: function(cal) { cal.hide() }
                                });
                                cal.manageFields("fecha_hasta", "fecha_hasta", "%Y-%m-%d");
                            //]]>
                            </script>
                        {/literal}
                    </td>
                </tr>
                <tr>
                    <td class="tb-head">Tipo de Movimiento</td>
                    <td colspan="3">
                        <select name="tipomov" id="tipomov">
                            <option value="0">Todos</option>
                            {html_options values=$option_values_tipo_movimientos_ban selected=$option_selected_tipo_movimientos_ban output=$option_output_tipo_movimientos_ban}
                        </select>
                    </td>
                </tr>
                <tr class="tb-tit">
                    <td colspan="3" style="text-align: right;">
                        <input type="submit" id="enviar" name="enviar" value="Enviar" onclick="valida_envia()" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
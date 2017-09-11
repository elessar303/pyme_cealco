<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        {include file="snippets/inclusiones_reportes.tpl"}
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <div id="datosGral">
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="cant_fechas" value="2"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="6" class="tb-head" style="text-align:center;">LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label">Fecha Desde</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <!--label for="fecha_desde">Desde</label-->
                                <input type="text" name="fecha" id="fecha" size="20" value="{$firstday|date_format:"%Y-%m-%d"}" readonly class="form-text" />
                                <!--button id="boton_fecha">...</button-->
                                <!--label for="fecha_hasta">Hasta</label-->
                                <input type="text" name="fecha2" id="fecha2" size="20" value="{$smarty.now|date_format:"%Y-%m-%d"}" readonly class="form-text" />
                                {literal}
                                    <script type="text/javascript">//<![CDATA[
                                    $("#fecha").datepicker({
                                        changeMonth: true,
                                        changeYear: true,
                                        showOtherMonths:true,
                                        selectOtherMonths: true,
                                        yearRange: "-100:+100",
                                        showOn: "both",
                                        dateFormat: "yy-mm-dd",
                                        //defaultDate: "+1w",
                                        //showButtonPanel: true,
                                        onClose: function(selectedDate) {
                                            $("#fecha2").datepicker("option", "minDate", selectedDate);
                                        }
                                    });
                                    $("#fecha2").datepicker({
                                        changeMonth: true,
                                        changeYear: true,
                                        showOtherMonths:true,
                                        selectOtherMonths: true,
                                        yearRange: "-100:+100",
                                        showOn: "both",
                                        dateFormat: "yy-mm-dd",
                                        onClose: function(selectedDate) {
                                            $("#fecha").datepicker("option", "maxDate", selectedDate);
                                        }
                                    });
                                    /*$("#fecha").datepicker({//Esto era para cuando estaba solo para seleccionar el mes.
                                        changeMonth: true,
                                        changeYear: true,
                                        //dateFormat: "M yy",
                                        dateFormat: "yy-mm",
                                        showButtonPanel: true,
                                        showOn: "both",
                                        currentText: "Este mes",
                                        onChangeMonthYear: function (year, month) {
                                            //$(this).val($.datepicker.formatDate('M yy', new Date(year, month - 1, 1)));
                                            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month - 1, 1)));
                                        },
                                        onClose: function() {
                                            var month = $(".ui-datepicker-month :selected").val();
                                            var year = $(".ui-datepicker-year :selected").val();
                                            //$(this).val($.datepicker.formatDate('M yy', new Date(year, month, 1)));
                                            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
                                        }
                                    }).focus(function () {
                                        $(".ui-datepicker-calendar").hide();
                                    });*/

                                    /*var cal = Calendar.setup({
                                        onSelect: function(cal) { cal.hide() }
                                    });
                                    cal.manageFields("boton_fecha", "fecha", "%Y-%m");*/
                                    //]]>
                                    </script>
                                {/literal}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Formato Reporte</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <div id="formato">
                                    <input type="radio" id="radio1" name="radio" value="0" /><label for="radio1">Hoja de C&aacute;lculo</label>
                                    <input type="radio" id="radio2" name="radio" value="1" checked /><label for="radio2">Formato PDF</label>
                                </div>
                            </td>
                        </tr>
                        <tr class="tb-tit">
                            <!--td colspan="3" style="text-align:left">
                                <input type="radio" name="radio" value="0" /> Hoja de C&aacute;lculo
                                <input type="radio" name="radio" value="1" checked /> Formato PDF
                            </td-->
                            <td colspan="6">
                                <input type="submit" id="aceptar" name="aceptar" value="Enviar" onclick="javascript:valida_envia('rpt_libroventas_pdf.php','rpt_libroDeVentas.php');" />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=7';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
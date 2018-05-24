<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Humberto Zapata" />
        <title>TOMA FISICA</title>
        {include file="snippets/inclusiones_reportes.tpl"}
        
        {literal}

         <script language="JavaScript" type="text/JavaScript">
        </script>
        {/literal}
        {literal}
        <style type="text/css">
            .imgajax{               
                position: absolute;
                top: 50%;
                left: 50%;
                margin-top: 100px; 
            }
            .cargando{
                margin-top: 10px;
                font-size: 18px;
                text-align: center;
            }

        </style>
            <script type="text/javascript">
            $(document).ready(function(){

                 $("#almacen_entrada").change(function(){
                    cargarUbicaciones();
                });

                 $('#almacen_entrada').select2({
                    placeholder: "Todo...",
                    allowClear: true
                });

                 $('#ubicacion').select2({
                    placeholder: "Todo...",
                    allowClear: true
                });

                 $('#cliente').select2({
                    placeholder: "Todo...",
                    allowClear: true
                });

                 $('#item').select2({
                    placeholder: "Todo...",
                    allowClear: true
                });

                 $("#fecha").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    //timeFormat: 'HH:mm:ss',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        //$( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                        $( "#fecha2" ).datepicker("option", "minDate", selectedDate);
                    }
                });
                $("#fecha2").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    //timeFormat: 'HH:mm:ss',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datepicker( "option", "maxDate", selectedDate );
                    }
                });

                function cargarUbicaciones() {
                    idAlmacen=$("#almacen_entrada").val();     
                    if(idAlmacen!=0)
                    {
                        $.ajax({
                            type: 'POST',
                            data: 'opt=cargaUbicacion3&idAlmacen='+idAlmacen,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#ubicacion").find("option").remove();
                                $("#ubicacion").append("<option value='0'>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#ubicacion").find("option").remove();
                                this.vcampos = eval(data);
                                 $("#ubicacion").append("<option value='0'>Todo...</option>");
                                for (i = 0; i < this.vcampos.length; i++) {
                                    $("#ubicacion").append("<option value='"+this.vcampos[i].id+"'>" + this.vcampos[i].descripcion + "</option>");
                                }
                            }
                        });
                    }//fin el if
                    else
                    {
                         $("#ubicacion").find("option").remove();
                         $("#ubicacion").append("<option value='0'>Todo...</option>");
                    }
                }

                
                });
    </script>
    {/literal}
    
    <link href="../../libs/js/select2/dist/css/select2.min.css" rel="stylesheet" />
    <script src="../../libs/js/select2/dist/js/select2.min.js"></script>
        
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
                <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>

                
                    <table style="width:100%; background-color:white;">
                        <thead>
                            <tr>
                                <th colspan="8" class="tb-head" style="text-align:center;">
                                    LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                
                                <td class="label">Almacen</td>
                                <td  style="padding-top:2px; padding-bottom: 2px;">
                                <select  name="almacen_entrada" id="almacen_entrada" style="width:200px;" class="form-text">
                                <option value="0">Todo...</option>              
                                {html_options output=$option_output_almacen values=$option_values_almacen }
                                </select>
                                </td>

                                <td class="label">Ubicación:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="ubicacion" id="ubicacion" style="width:200px;" class="form-text">
                                        <option value="0" selected="selected">Todo...</option>
                                </select>
                                </td>

                                <td class="label">Cliente:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="cliente" id="cliente" style="width:200px;" class="form-text">
                                        <option value="0" selected="selected">Todo...</option>
                                        {html_options output=$option_output_cliente values=$option_values_cliente selected=$cliente}
                                    </select>
                                </td>

                                <td class="label">Producto:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="item" id="item" style="width:200px;" class="form-text">
                                        <option value="0" selected="selected">Todo...</option>
                                        {html_options output=$option_output_producto values=$option_values_producto selected=$item}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                            <td class="label">Fecha de Vencimiento: </td>

                            <td class="label">Desde: </td>
                            <td style="padding-top:2px; padding-bottom: 2px;" colspan="2">
                                <input type="text" name="fecha" id="fecha" size="20" value='' readonly class="form-text" />
                            </td>
                            <td class="label">Hasta: </td>
                            <td style="padding-top:2px; padding-bottom: 2px;" colspan="2">
                                <input type="text" name="fecha2" id="fecha2" size="20" value='' readonly class="form-text" />
                            </td>
                            </tr>                         
                            <tr class="tb-head">
                                <td colspan="8">
                                    <input type="submit" id="enviarajax" name="aceptar" value="Mostrar"/>
                                    <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}';" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </form>
 </div>
</body>
</html>

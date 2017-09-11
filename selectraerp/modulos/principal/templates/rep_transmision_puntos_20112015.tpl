<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Junior Ayala" />
        <title></title>
        {include file="snippets/inclusiones_reportes.tpl"}
    </head>
    {literal}
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                $("#fecha1").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
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
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datepicker( "option", "maxDate", selectedDate );
                    }
                });
            });
            //]]>
            </script>
        {/literal}
    <body>
        <form name="formulario" id="formulario" method="post">
        <div id="datosGral" class="x-hide-display">
        <table style="width:100%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista">
                <thead>
                    <tr>
                        <th colspan="6" class="tb-head" style="text-align:center;">
                        REPORTE DE TRANSMISIÓN DE VENTAS
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <!--<tr>
                        <td width="20%" align='right'>Fecha</td>
                           <td "paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                               <input type="text" name="fecha1" id="fecha1" size="20" value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly class="form-text" />
                               <!--button id="boton_fecha">...</button-->
                               <!--<input type="text" name="fecha2" id="fecha2" size="20" value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly class="form-text" />
                            </td>
                    </tr>-->
                    
                    <tr>
                                  
                        <td  width="20%" align='right'>Tipo Sincronizaci&oacute;n:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                        <select name="sincro" id="sincro" style="width:200px;">
                                    <option value="00"{if $sincro  eq "00"} selected="seleted"{/if}>Todos</option>
                                    <option value="1" {if $sincro  eq "1"} selected="seleted"{/if}>Autom&aacute;tico</option>
                                    <option value="2" {if $sincro  eq "2"} selected="seleted"{/if}>Manual</option>
                                    <option value="3" {if $sincro  eq "3"} selected="seleted"{/if}>Codigos Errados</option>
                                </select>
                        </td> 
                    </tr>    
                    <tr>    
                        <td  width="20%" align='right'>Estado:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'> 
                                <select name="estado" id="estado" style="width:200px;">
                                    <option value="9999">Todos</option>
                                    {html_options values=$option_values_id_estado output=$option_values_nombre_estado selected=$estado}
                                </select>
                        </td>
                        <td  width="20%" align='right'>Punto de Venta:</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                            <input type="text" name="puntodeventa" id="puntodeventa" size="10" {if $puntodeventa  eq ""}
                            value=''
                            {else}
                            value='{$puntodeventa}'
                            {/if}/> (C&oacute;digo SIGA)
                        </td>                                    
                    </tr>
                    <tr ><td colspan="3" align="center">Total Registrados={$resultado} &nbsp;&nbsp; Total Reportaron={$reportaron1} &nbsp;&nbsp; Porcentaje={$porcentaje}% &nbsp;&nbsp; *Pulse Sobre El Color Para Ver El Estatus</td>
                            <td colspan="4" align='center'>
                                <input type="submit" id="aceptar" name="aceptar" value="Mostrar"/>
                                <input type="submit" id="cancelar" name="cancelar" value="Limpiar"/>
                            </td>
                    </tr>
                </tbody>
        </table>
        </div>
        </form>
        {if $aceptar=="Mostrar"}
        <table class="seleccionLista">
            <tr class="tb-head">
                <td>N&deg; {$resultado}</td>
                <td>Estado</td>
                <td>Codigo Siga</td>
                <td>Punto de Venta</td>
                <td>Sincronizacion</td>
                <td>Fecha</td>
                <td>Reporto</td>
            </tr>
            {assign var="counter" value="1"}
            {foreach key=key name=outer item=dato from=$consulta}
            <tr >
                
                <td align="center">{counter}</td>
                <td align="center">{$dato.nombre_estado}</td>
                <td align="center">{$dato.codigo_siga}</td>
                <td align="center">{$dato.nombre_punto}</td>
                <td align="center">{if $dato.tipo_sincro eq '1'}Automatico{else}Manual{/if}</td>
                <td align="center">{$dato.fecha}</td>
                <td align="center" TD BGCOLOR= {if $dato.color eq 'verde'}'#00FF00' title='Normal' {/if}{if $dato.color eq 'amarillo'  }'#FFFF00' title='Ha Reportado Al Menos Una Vez Al Dia' {/if}{if $dato.color eq 'rojo'}'#FF0000' title='Mas De 12H Sin Reportar' {/if}{if $dato.color eq 'negro'}'#000000' title='Mas De 2 Dias Sin Reportar' {/if};> </td>
            </tr>
            {assign var="counter" value=$counter++}
            {/foreach}
        {/if}
        </table>
    </body>
</html>  
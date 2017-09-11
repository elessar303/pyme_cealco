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
                        LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td  width="20%" align='right'>Fecha Desde:**</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" width="30%">
                            <input type="text" name="fecha1" id="fecha1" size="15"
                            {if $fecha1 eq ""}
                            value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly />
                            {else}
                            value='{$fecha1}' readonly />
                            {/if}
                        </td>
 
                        <td  width="20%" align='right'>Categor&iacute;a:</td>
                         <td width="30%" align='left'>
                            <input type="text" name="categoria" id="categoria" size="20" value="{$categoria}" />
                        </td>
                    </tr>
                    <tr>
                        <td  width="20%" align='right'>Fecha Hasta:**</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" width="30%">
                            <input type="text" name="fecha2" id="fecha2" size="15"
                            {if $fecha2 eq ""}
                            value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly />
                            {else}
                            value='{$fecha2}' readonly />
                            {/if}
                        </td>                      
                        <td  width="20%" align='right'>Producto:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                        <input type="text" name="producto" id="producto" size="20" value="{$producto}"/>
                        </td> 
                    </tr>    
                    <tr>    
                        <td  width="20%" align='right'>Estado:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'> 
                                <select name="estado" id="estado" style="width:200px;">
                                    <option value="00">Todos</option>
                                    {html_options values=$option_values_id_estado output=$option_values_nombre_estado}
                                </select>
                        </td>
                        <td  width="20%" align='right'>Punto de Venta:</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                            <input type="text" name="puntodeventa" id="puntodeventa" size="10" value="{$puntodeventa}" /> (C&oacute;digo SIGA)
                        </td>                                    
                    </tr>
                    <tr>
                    <td  width="20%" align='right'>Beneficiario:
                        <select name="nac" id="nac" title="Inicial de Cedula" required="required">
                        <option value="V" {if $nac eq 'V'}echo "selected";{/if}>V</option>
                        <option value="E" {if $nac eq 'E'}echo "selected";{/if}>E</option>
                        <option value="P" {if $nac eq 'P'}echo "selected";{/if}>P</option>
                        </select>
                    </td>

                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" width="30%" align='left'>
                        <input type="text" name="cedula" id="cedula" size="20" value="{$cedula}"/>
                        </td> 
                    </tr>
                    <tr class="tb-head">
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
                <td>N&deg;</td>
                <td>Estado</td>
                <td>Punto de Venta</td>
                <td>C&eacute;dula</td>
                <td>Beneficiario</td>
                <td>Fecha</td>
                <td>Categoria</td>
                <td>Producto</td>
                <td>Precio</td>
                <td>Cantidad</td>
                <td>Total</td>
            </tr>
            {assign var="counter" value="1"}
            {foreach key=key name=outer item=dato from=$consulta}
            <tr >
                
                <td align="left">{counter}</td>
                <td align="left">{$dato.nombre_estado}</td>
                <td align="center">{$dato.nombre_punto}</td>
                <td align="left">{$dato.taxid}</td>
                <td align="center">{$dato.name_persona}</td>
                <td align="center">{$dato.datenew_ticketlines}</td>
                <td align="center">{$dato.descripcion}</td>
                <td align="center">{$dato.nombre_producto}</td>
                <td align="center">{$dato.price|string_format:"%.2f"}</td>
                <td align="center">{$dato.units}</td>
                <td align="center">{$dato.units*$dato.price|string_format:"%.2f"}</td>

                
            </tr>
            {assign var="counter" value=$counter++}
            {/foreach}
	    <tr class="tb-head">
            <form name="formulario2" id="formulario2" method="post" action="rep_ventas_consolidado_exportar.php">
                <td hidden="hidden">
                <input type="text" name="cedula_nac" id="cedula_nac" value='"{$cedula_nac}"'/>
                <input type="text" name="producto" id="producto" value='"{$producto}"'/>
                <input type="text" name="categoria" id="categoria" value='"{$categoria}"'/>
                <input type="text" name="puntodeventa" id="puntodeventa" value='"{$puntodeventa}"'/>
                <input type="text" name="estado" id="estado" value='"{$estado}"'/>
                <input type="text" name="fecha1" id="fecha1" value='"{$fecha1}"'/>
                <input type="text" name="fecha2" id="fecha2" value='"{$fecha2}"'/>

                </td>
                <td colspan="7" align='center'>
                <input type="submit" id="exportar" name="exportar" value="Exportar a Excel"/>
                </td>
            </tr>
        </form>
        </table>
        {/if}
    </body>
</html>    

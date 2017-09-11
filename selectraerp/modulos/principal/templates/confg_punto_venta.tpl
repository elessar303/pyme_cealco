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


                //funcion para cargar los puntos 
                  $("#estados").change(function() {
                    estados = $("#estados").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getPuntos&'+'estados='+estados,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#punto").find("option").remove();
                                $("#punto").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#punto").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#punto").append("<option value='0'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#punto").append("<option value='" + this.vcampos[i].siga+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                                }
                            }
                        }); 
                        $("#punto").val(0);
                  });
            });
            //]]>
            </script>
        {/literal}
    <body>
        <form name="formulario" id="formulario" method="post">
        <div id="datosGral" class="x-hide-display">
        {include file = "snippets/regresar_boton.tpl"}
        <table style="width:100%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista">
                <thead>
                    <tr>
                        <th colspan="9" class="tb-head" style="text-align:center;">
                        LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>    
                        <td align='right' class="label" width="50px" style="width:50px">Estado:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='left'> 
                                <select name="estados" id="estados" style="width:200px;" class="form-text">
                                    {html_options values=$option_values_id_estado output=$option_values_nombre_estado}
                                </select>
                        </td>
                        <td align='right' class="label" width="50px" style="width:50px">Tipo:</td>
                        <td style="paddinaligng-top:2px; padding-bottom: 2px;" align='left'> 
                                <select name="tipo_punto" id="tipo_punto" style="width:200px;" class="form-text">
                                    <option value="">Todos</option>
                                    {html_options values=$option_values_id_tipo output=$option_values_descripcion_tipo}
                                </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="50px" style="width:50px" class="label">Establecimiento:</td>
                             <!-- PUNTOS -->
                            <td  style="padding-top:2px; padding-bottom: 2px; ">
                                <select name="punto" id="punto" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>                               
                                {html_options values=$option_values_punto output=$option_output_punto}
                                
                                </select>
                            </td>
                        <td align='right' class="label" width="50px" style="width:50px">Nombre:</td>
                        <td style="padding-top:2px; padding-bottom: 2px;" align='left'>
                            <input type="text" name="nombrepunto" id="nombrepunto" size="10" value="{$nombrepunto}" class="form-text"/> 
                        </td>
                        {if $smarty.get.opt_menu neq "7"}
                        <td valign='middle'>
                        Agregar Nuevo
                        <a href="agregar_punto_venta.php" target="popup" onClick="window.open(this.href, this.target, 'width=900,height=250,top=200,left=300,scrollbars=NO,menubar=NO,titlebar= NO,status=NO,toolbar=NO'); return false;"><img valign='middle' src="../../imagenes/add.gif"></a>
                        </td>
                        {/if}                                
                    </tr>
                    <tr class="tb-head">
                            <td colspan="5" align='center'>
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
                <td>Tipo de Punto</td>
                <td>Codigo SIGA</td>
                <td>Estado</td>
                <td>Punto de Venta</td>
                <td>IP</td>
                <td>Sincronizaci&oacute;n</td>
                <td>Status</td>
                <td>Acci&oacute;n</td>

            </tr>
            {assign var="counter" value="1"}
            {foreach key=key name=outer item=dato from=$consulta}
            <tr >
                
                <td align="left">{counter}</td>
                <td align="left">{$dato.descripcion_tipo}</td>
                <td align="center">{$dato.codigo_siga_punto}</td>
                <td align="center">{$dato.nombre_estado}</td>
                <td align="center">{$dato.nombre_punto}</td>
                <td align="center"><a href="http://{$dato.ip_punto_venta}/pyme/entrada/index.php" target="_blank">{$dato.ip_punto_venta}</a></td>
                <td align="center">
                {if $dato.tipo_sincro eq "1"}
                Automatico
                {elseif $dato.tipo_sincro eq "2"}
                Manual
                {/if}
                </td>
                <td align="center">
                {if $dato.estatus eq "A"}
                Activo
                {elseif $dato.estatus eq "I"}
                Inactivo
                {/if}
                </td>
                {if $opcion_menu neq "7"}
                <td align="center">
                <a href="mod_punto_venta.php?punto={$dato.id}" target="popup" onClick="window.open(this.href, this.target, 'width=700,height=250,top=200,left=300,scrollbars=NO,menubar=NO,titlebar= NO,status=NO,toolbar=NO'); return false;">Editar</a>
                </td>
                {/if}
                {if $opcion_menu eq "7"}
                <td align="center">
                <a href="mod_punto_venta_merc.php?punto={$dato.id}" target="popup" onClick="window.open(this.href, this.target, 'width=700,height=250,top=200,left=300,scrollbars=NO,menubar=NO,titlebar= NO,status=NO,toolbar=NO'); return false;">Editar</a>
                </td>
                {/if}

            </tr>
            {assign var="counter" value=$counter++}
            {/foreach}
	    <tr class="tb-head">
            <form name="formulario2" id="formulario2" method="post" action="">
                <td hidden="hidden">
                <input type="sql" name="sql" id="sql" value='"{$sql}"'/>
                </td>
                <td colspan="9" align='center'>
                <input type="submit" id="exportar" name="exportar" value="Exportar a Excel"/>
                </td>
            </tr>
        </form>
        </table>
        {/if}
    </body>
</html>    

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" />
        {include file="snippets/inclusiones_reportes.tpl"}
        {literal}
        <script language="JavaScript" type="text/JavaScript">
            //variable Global
            var acumulado=0;
            function generardeposito() 
            {
                var checkboxValues = "";
                $('input[id="id_depositos[]"]:checked').each(function() 
                {
                    checkboxValues += "'"+$(this).val() + "',";
                });
                if(checkboxValues=="")
                {
                    $("#resultado").empty();
                    return;
                }
                //eliminamos la última coma.
                checkboxValues = checkboxValues.substring(0, checkboxValues.length-1);
                parametros = {"arreglo" : checkboxValues, "opt" : "calcular_monto_cataporte_tickets"}
                $.ajax(
                {
                    type: "POST",
                    url: "../../libs/php/ajax/ajax.php",
                    data: parametros,
                    dataType: "html",
                    asynchronous: false,
                    beforeSend: function() 
                    {
                        $("#resultado").empty();
                        $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                    },
                    error: function()
                    {
                        alert("error peticion ajax");
                    },
                    success: function(data)
                    {
                        $("#resultado").html(data);
                        ///// verificamos su estado
                    }
                });
            }
            
            function modificar()
            {
              document.getElementById('monto_usuario_tr').style.visibility='visible';
              document.getElementById('monto_usuario_tr1').style.visibility='visible';
            }

            function enviar()
            {
                nro = $("#cantidad").val();
                var siga = {/literal}{$parametros[0].codigo_siga}{literal};
                var monto=document.getElementById('monto_usuario').value;
                $.ajax(
                {
                    type: "POST",
                    url: "../../libs/php/ajax/ajax.php",
                    data: {"opt": "MontoAcumulado", "tipo": 1} ,
                    dataType: "html",
                    asynchronous: false,
                   
                    error: function()
                    {
                        alert("error peticion ajax");
                    },
                    success: function(data)
                    {
                        acumulado=parseFloat(data);
                    }
                });
                if(document.form1.monto_usuario.value=='' )
                {
                    alert('Debe Ingresar el Monto');
                    document.form1.monto_usuario.focus();
                    return false;
                }
                if(document.form1.monto_usuario.value > acumulado)
                {
                    alert('El monto ingresado es mayor al acumulado');
                    document.form1.monto_usuario.focus();
                     return false;
                }
                if(document.form1.nro_cataporte.value=='' )
                {
                    alert('Debe Ingresar el Nro del Cataporte');
                    document.form1.nro_cataporte.focus();
                    exit;
                }
                if(document.form1.cantidad.value=='' )
                {
                    alert('Debe Ingresar la Cantidad de Bolsas');
                    document.form1.cantidad.focus();
                    exit;
                }
                if(document.form1.cat_val.value==1 )
                {
                    alert('Debe Ingresar un Nro de Cataporte No Usado');
                    document.form1.nro_cataporte.focus();
                    exit;
                }
                if(document.form1.banco.value=='' )
                {
                    alert('Debe Ingresar Banco - Cuenta');
                    document.form1.cantidad.focus();
                    exit;
                }
                for(var i = 1; i<=nro; i++)
                {
                    if(document.getElementById('depositos'+i).value=='' )
                    {
                        alert('Debe Ingresar el Nro de Bolsa '+i);
                        document.getElementById('depositos'+i).focus();
                        exit;
                    }
                }
                if (confirm('¿Generar Cataporte: '+siga+'?\n OJO: Verificar que este Codigo SIGA corresponda a este punto de venta'))
                {
                    if (confirm('¿Esta seguro de generar cataporte con monto de: '+monto+'?\n'))
                    {
                        document.form1.submit();
                    }
                }
            }

            $(document).ready(function ()
            {
                $.ajax(
                {
                    url: 'verificar_cataporte.php',
                    type: "post",
                    data: "val=limpiar",
                    dataType: 'html',
                    beforeSend: function() 
                    {
                        $("#resultado").empty();
                        $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                    },
                    success: function(data)
                    {
                        $("#resultado").html(data);
                    }
                });
            });

            function crearCampos(cantidad)
            {
                var div = document.getElementById("campos_dinamicos");
                while(div.firstChild)div.removeChild(div.firstChild); // remover elementos;
                for(var i = 1, cantidad = Number(cantidad); i <= cantidad; i++)
                {
                    var salto = document.createElement("P");
                    var input = document.createElement("input");
                    var text = document.createTextNode("Bolsa Cataporte " + i + ": ");
                    input.setAttribute("name", "depositos[]");
                    input.setAttribute("id", "depositos"+i);
                    input.setAttribute("size", "20");
                    input.setAttribute("onkeyup", "aMays(event, this)");
                    input.setAttribute("onblur", "aMays(event, this)");
                    input.className = "input";
                    salto.appendChild(text);
                    salto.appendChild(input);
                    div.appendChild(salto);
                }
            }
            
            function aMays(e, elemento) 
            {
                tecla=(document.all) ? e.keyCode : e.which; 
                elemento.value = elemento.value.toUpperCase();
            }

            function comprobar() 
            {
                consulta = $("#nro_cataporte").val();    
                parametros={'opt': 'verificar_cataporte', 'val': consulta}
                $("#resultado2").delay(1000).queue(function(n) 
                {
                    $("#resultado2").html('<img src="imagenes/ajax-loader.gif" />');
                            $.ajax(
                            {
                                type: "POST",
                                url: "../../libs/php/ajax/ajax.php",
                                data: parametros,
                                dataType: "html",
                                error: function()
                                {
                                    alert("error petici�n ajax");
                                },
                                success: function(data)
                                {
                                    $("#resultado2").html(data);
                                    n();
                                }
                            });
                });
            }

        </script>       
        {/literal}        
    <title>Cataporte</title>
    </head>

    <body>
        <form name="formulario" id="formulario">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar.tpl"}
                <table style="width:50%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista" align="center">
                    <thead>
                        <tr>
                            <th class="tb-head" style="text-align:center;" colspan="5">Generar Cataporte Tickets para Punto de Venta: {$parametros[0].codigo_siga}</th>
                        </tr>       
                        <tr>
                        	<td align="left">N°</td>
                        	<td align="center">Fecha de Transferencia</td>
                        	<td align="center">N° de Transferencia</td>
                        	<td align="center">Monto Ticket Acumulado</td>
                        	<td align="center">Acci&oacute;n</td>
        				</tr>
                        {assign var="counter" value="1"}
                        {assign var="nro" value="1"}
                		{foreach key=key name=outer item=dato from=$consulta}
                		<tr>
        	                <td align="left">{$nro}</td>
        	                <td align="center">{$dato.fecha_deposito|date_format:"%d-%m-%Y"}</td>
        	                <td align="center">{$dato.nro_deposito}</td>
                            <td align="center">{$dato.monto_acumulado_tickets|number_format:2:".":""}</td>
        	                <td align="center">
                                {if $dato.monto_acumulado_tickets <= '0.00'}
                                    <p>Sin tickets <p>
                                {else}
                                    <input type="checkbox" value="{$dato.nro_deposito}" id="id_depositos[]" onclick="generardeposito();" />
                                {/if}

                            </td>
                        
                		</tr>
                   		{assign var="counter" value=$counter+1}
                        {assign var="nro" value=$nro+1}
                        {/foreach}
                    </thead>
                    <tbody>
                        <tr class="tb-head" align="center">
                            <td align='center' colspan="5">
                            &nbsp;
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <br>
        <form method='POST' action='generar_cataporte_fin_tickets.php' name='form1' >
            <div id="resultado" width='50%' align="center"></div>
            <input type="text" name="opt_menu" value="{$smarty.get.opt_menu}" hidden="hidden">
            <input type="text" name="opt_seccion" value="{$smarty.get.opt_seccion}" hidden="hidden">
        </form>  
    </body>
</html>    

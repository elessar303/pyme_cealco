<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {assign var=name_form value="usuarios_nuevo"}
        {include file="snippets/header_form.tpl"}
        <!--Para estilo JQuery en botones-->
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/md5_crypt.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
       
        {literal}
            <script type="text/javascript">//<![CDATA[
                //variable Global
                var acumulado=0;
                function comprobar() 
                    {
                        consulta = $("#nro_referencia").val();
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
                $(document).ready(function()
                {
                    
            
                    $("input[name='cancelar']").button();//Coloca estilo JQuery
                    $("input[name='aceptar']").button().click(function()
                    {
                        
                       
                        credito=parseFloat(document.formulario.monto_visa.value) + parseFloat(document.formulario.monto_master.value);
                        debito=parseFloat(document.formulario.monto_debito.value) + parseFloat(document.formulario.monto_alimentacion.value);
                        total=credito+debito;
                        
                        $.ajax(
                        {
                            type: "POST",
                            url: "../../libs/php/ajax/ajax.php",
                            data: {"opt": "MontoAcumulado", "tipo": 2} ,
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
                        if(total > acumulado)
                        {
                            Ext.Msg.alert("Alerta","El monto ingresado es mayor al acumulado");
                            document.formulario.monto_visa.focus();
                            return false;
                        }
                       
                    });

                    //funcion para sumar total credito
                    $("#monto_master").on('blur', function()
                    {
                        if(isNaN($("#monto_visa").val()) || isNaN($("#monto_master").val()))
                        {
                            alert("Debe introducir solo numeros en 'Monto Visa' y 'Monto Master'");
                            $("#monto_visa").focus();
                        }
                        else
                        {
                            
                            $("#total_credito").val(parseFloat($("#monto_visa").val())+parseFloat($("#monto_master").val()));
                            
                        }
                    });
                    //funcion para sumar total debito
                    $("#monto_alimentacion").on('blur', function()
                    {
                        if(isNaN($("#monto_debito").val()) || isNaN($("#monto_alimentacion").val()))
                        {
                            alert("Debe introducir solo numeros en 'Monto Debito' y 'Monto Alimentacion'");
                            $("#monto_debito").focus();
                        }
                        else
                        {
                            $("#total_debito").val(parseFloat($("#monto_debito").val())+parseFloat($("#monto_alimentacion").val()));
                        }
                    });
                    //funcion para cargar las cuentas
                    $("#banco").change(function() 
                    {
                        banco = $("#banco").val();
                            $.ajax({
                                type: 'GET',
                                data: 'opt=getCuentas&'+'banco='+banco,
                                url: '../../libs/php/ajax/ajax.php',
                                success: function(data) 
                                {
                                    if(data!=-1)
                                    {
                                        $("#cuenta").find("option").remove();
                                        $("#cuenta").append("<option value='-1'>Seleccione una Cuenta</option>");
                                        this.vcampos = eval(data);
                                        for (i = 0; i < this.vcampos.length; i++)
                                        {
                                            $("#cuenta").append("<option value='" + this.vcampos[i].id+ "'>" + this.vcampos[i].cuenta+"</option>");
                                        }
                                    }
                                    else
                                    {
                                        $("#cuenta").find("option").remove();
                                        $("#cuenta").append("<option value='-1'>No Existen Cuentas Asociadas</option>");
                                    }
                                }
                            }); 
                    });

                });/*end of document.ready*/
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar.tpl"}
                <table style="width: 100%">
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="edocuenta_detalle">
                            <td colspan="8">
                                <div style=" background-color:#A9A9F5; border: 1px solid #ededed; width: 80%; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px; overflow-x: scroll; white-space: nowrap;">
                                    <table >
                                        <thead>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:50px; align:center;">Siga</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:50px; align:center;">Nro. Referencia<br></th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:80px; align:center;">Fecha</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:100px; align:center;">Banco</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:100px; align:center;">Cuenta</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;" value=0>Afiliación Credito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;" value=0>Terminal Credito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Lote Credito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Monto Visa</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Monto Master</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Total Credito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Afiliacion Debito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Terminal  Debito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Lote Debito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Monto Debito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Monto Alimentación</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Total Debito</th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:70px; align:center;">Total Depósito</th>
                                        </thead>

                                        <tbody>
                                            
                                            <td align="center" >
                                                <input type="text" name="siga" class="form-text serialSec " required style="width:50px;" value='{$siga}' readonly />
                                            </td>
                                            <td align="center" >
                                                <input type="text" name="nro_referencia" id="nro_referencia" class="form-text serialSec " required style="width:50px;" value='' onBlur='comprobar(this.id)'/>
                                                
                                            </td>
                                            <td align="center" >
                                                <input  type="text" name="fecha" id="fecha"  class="form-text serialSec " style="width:80px;"/>
                                                {literal}
                                                    <script type="text/javascript">
                                                        //<![CDATA[
                                                        var cal = Calendar.setup({
                                                            onSelect: function(cal) { cal.hide() }
                                                        });
                                                        cal.manageFields("fecha", "fecha", "%Y-%m-%d");
                                                        //]]>
                                                    </script>
                                                {/literal}
                                            </td>

                                            <th align="center" >
                                                <select name="banco" id="banco" class="form-text" style="width:100px;">
                                                    <option value="-1">Seleccione Banco</option>
                                                    {html_options values=$option_values_banco output=$option_output_banco}  
                                                </select>
                                            </th>
                                            <th align="center" >
                                                <select name="cuenta" id="cuenta" class="form-text" style="width:100px">
                                                    {html_options values=$option_output_punto output=$option_output_punto selected=$puntodeventa}
                                                </select>
                                                
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="afiliacion_credito" id="afiliacion_credito"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="terminal_credito" id="terminal_credito"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="lote_credito" id="lote_credito"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="monto_visa" id="monto_visa"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="monto_master" id="monto_master"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>

                                            <th align="center" >
                                                <input type="text" name="total_credito" id="total_credito"  class="form-text serialSec " style="width:70px; align:center"/>
                                                <input type="hidden" name="total_credito_sistema" id="total_credito_sistema"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>

                                            <th align="center" >
                                                <input type="text" name="afiliacion_debito" id="afiliacion_debito"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="terminal_debito" id="terminal_debito"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="lote_debito" id="lote_debito"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="monto_debito" id="monto_debito"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>
                                            <th align="center" >
                                                <input type="text" name="monto_alimentacion" id="monto_alimentacion"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>

                                            <th align="center" >
                                                <input type="text" name="total_debito" id="total_debito"  class="form-text serialSec " style="width:70px; align:center"/>
                                                <input type="hidden" name="total_debito_sistema" id="total_debito_sistema"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>

                                            <th align="center" >
                                                <input type="text" name="total_deposito" id="total_deposito"  class="form-text serialSec " style="width:70px; align:center"/>
                                                <input type="hidden" name="total_deposito_sistema" id="total_deposito_sistema"  class="form-text serialSec " style="width:70px; align:center"/>
                                            </th>

                                        </tbody>
                                        <th colspan="18">
                                        <div id='resultado2'></div>
                                        </th>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                                
                                
                   
                <table style="width:100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar" />
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
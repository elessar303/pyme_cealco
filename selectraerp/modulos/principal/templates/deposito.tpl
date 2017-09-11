<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="autor" /> 
    {include file="snippets/inclusiones_reportes.tpl"} 
    {literal}
        <script language="JavaScript" type="text/JavaScript">

            function generardeposito() 
            {

                venta_pyme=$("#venta_pyme").val(); var checkboxValues = ""; 
                $('input[id="id_cajeros[]"]:checked').each(function() 
                { 
                    checkboxValues += $(this).val() + ","; 
                }); 

                if(checkboxValues=="")
                { 
                    $("#resultado").empty(); return; 
                } 
                //eliminamos la última coma. 
                checkboxValues = checkboxValues.substring(0, checkboxValues.length-1); 
                parametros = { "arreglo" : checkboxValues, "opt" : "calcular_monto_deposito", "venta_pyme" : venta_pyme } 
                $.ajax(
                { 
                    type: "POST", url: "../../libs/php/ajax/ajax.php",
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
                        $("#resultado").html(data); ///// verificamos su estado 
                    }
                }); 
            }

            function enviar()
            { 
                var siga = {/literal}{$parametros[0].codigo_siga}{literal};
                if(document.form1.banco.value=='000')
                { 
                    alert('Debe Seleccionar un Banco'); return false; 
                } 
                if (confirm('¿Generar Deposito para Punto de Venta: '+siga+'?\n OJO: Verificar que este Codigo SIGA corresponda a este punto de venta'))
                {
                    document.form1.submit(); 
                } 
            }

            $(document).ready(function ()
                { 
                    $.ajax
                    ({
                        url: 'verificar_deposito.php', type: "post", dataType: 'html', beforeSend: function() 
                        { 
                            $("#resultado").empty(); $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>'); 
                        }, 
                        success: function(data)
                        { 
                            $("#resultado").html(data); 
                        } 
                    }); 
                });

        </script>
    {/literal}
    <title>Cierre de Ventas</title>

</head>

<body>
  <form name="formulario" id="formulario">
    <input type="hidden" name="venta_pyme" id="venta_pyme" value="{$venta_pyme}" />
    <div id="datosGral" class="x-hide-display">
      {include file = "snippets/regresar.tpl"}
      <table style="width:50%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista" align="center">
        <thead>
          <tr>
            <th class="tb-head" style="text-align:center;" colspan="7">Generar Transferencia para Punto de Venta: {$parametros[0].codigo_siga}</th>
          </tr>
          <tr>
            <td align="left">N°</td>
            <td align="center">Fecha</td>
            <td align="center">Cajero</td>
            <td align="center">Fecha Inicio</td>
            <td align="center">Fecha Fin</td>
            <td align="center">Monto</td>
            <td align="center">Acci&oacute;n</td>
          </tr>
          {assign var="counter" value="1"} {assign var="nro" value="1"} 
          {foreach key=key name=outer item=dato from=$consulta}
              <tr>
                <td align="left">{$nro}</td>
                <td align="center">{$dato.fecha_arqueo}</td>
                <td align="center">{$dato.NAME}</td>
                <td align="center">{$dato.fecha_venta_ini}</td>
                <td align="center">{$dato.fecha_venta_fin}</td>
                <td align="center">{$dato.efectivo|number_format:2:".":""}</td>
                <td align="center"><input type="radio" value="{$dato.id}" id="id_cajeros[]"  name="id_cajeros[]" onclick="generardeposito()"></td>
              </tr>
              {assign var="counter" value=$counter+1} 
              {assign var="nro" value=$nro+1} 
          {/foreach}
        </thead>
        <tbody>
          <tr class="tb-head" align="center">
            <td align='center' colspan="7">
              &nbsp;
            </td>

          </tr>
        </tbody>
      </table>
  </form>
  </div>
  <br>
  <form method='POST' action='generar_deposito_fin2.php' name='form1'>
    <div id="resultado" width='50%' align="center"></div>
    <input type="text" name="opt_menu" value="{$smarty.get.opt_menu}" hidden="hidden">
    <input type="text" name="opt_seccion" value="{$smarty.get.opt_seccion}" hidden="hidden">
  </form>
</body>

</html>

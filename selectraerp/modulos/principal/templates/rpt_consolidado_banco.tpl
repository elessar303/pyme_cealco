<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="autor" content="Lucas Sosa" />
    <title></title>
    {include file="snippets/inclusiones_reportes.tpl"}
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

            .custom-combobox {
              position: relative;
              display: inline-block;
            }
            .custom-combobox-toggle {
              position: absolute;
              top: 0;
              bottom: 0;
              margin-left: -1px;
              padding: 0;
            }
            .custom-combobox-input {
              margin: 0;
              padding: 5px 10px;
              width: 171px;
            }
      </style>
      <script type="text/javascript">//<![CDATA[
        $(document).ready(function()
        {
          $("#fecha").datepicker(
          {
            changeMonth: true,
            changeYear: true,
            showOtherMonths:true,
            selectOtherMonths: true,
            dateFormat: "dd-mm-yy",
            timeFormat: 'HH:mm:ss',
            showOn: "both",//button,
            onClose: function( selectedDate ) 
            {
              $( "#fecha2" ).datetimepicker("option", "minDate", selectedDate);
            }
          });
          
          $("#fecha2").datepicker(
          {
            changeMonth: true,
            changeYear: true,
            showOtherMonths:true,
            selectOtherMonths: true,
            dateFormat: "dd-mm-yy",
            timeFormat: 'HH:mm:ss',
            showOn: "both",//button,
            onClose: function( selectedDate ) 
            {
              $( "#fecha" ).datetimepicker( "option", "maxDate", selectedDate );
            }
          });
        });
      //]]>
      </script>
    {/literal}
    <script type="text/javascript" src="../../libs/js/underscore.js"></script>
    <script type="text/javascript" src="../../libs/js/underscore.string.js"></script>
    <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>
  </head>
  <body>
    <form name="formulario" id="formulario" method="post" action="../../reportes/comprobante_contable_consolidado_banco.php">
      <div id="datosGral" class="x-hide-display" style="overflow: auto;">
        {include file = "snippets/regresar_solo.tpl"}
        <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
        <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
        <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
        <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
        <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
        <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
        <table style="width:100%; background-color:white;">
          <thead>
            <tr>
              <th colspan="6" class="tb-head" style="text-align:center;">
                  LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="label">Per&iacute;odo **</td>
              <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                <input type="text" name="fecha" id="fecha" size="20" value='{$smarty.now|date_format:"%d-%m-%Y"}' readonly class="form-text" />
              </td>
            </tr>
             <tr>
              <td class="label">Nro. Comprobante **</td>
              <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                <input type="text" name="comprobante" id="comprobante" size="20" value=''  class="form-text" />
              </td>
            </tr>
            <tr align="center" class="tb-head">
              <td colspan="4" align="center">
                <input type="submit" id="enviarajax" name="aceptar" value="Mostrar" align="right" />
                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}';" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </form>
  </body>
</html>
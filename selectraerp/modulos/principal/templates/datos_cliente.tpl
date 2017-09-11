<table  width="100%" border="0" >
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Codigo
    </td>
    <td >
        <input type="text" name="cod_cliente" readonly value="{$datacliente[0].cod_cliente}" id="cod_cliente" >
    </td>
</tr>
<tr>
        <td colspan="4" class="tb-head" align="center">
          &nbsp;
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Nombre **
    </td>
    <td >
        <input type="text" name="nombre" value="{$datacliente[0].nombre}" size="60"  id="nombre" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Representante
    </td>
    <td >
        <input type="text" name="representante" size="60" value="{$datacliente[0].representante}" id="representante" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Dirección **
    </td>
    <td >
        <input type="text" name="direccion" size="60" value="{$datacliente[0].direccion}" id="direccion" >
    </td>
</tr>


<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Telefonos **
    </td>
    <td >
        <input type="text" name="telefonos" size="60" value="{$datacliente[0].telefonos}" id="telefonos" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Permite Creditos
    </td>
    <td>
        <select name="permitecredito" id="permitecredito">
{html_options values=$option_values_permitecredito output=$option_output_permitecredito selected=$option_selected_permitecredito}
        </select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Limite
    </td>
    <td >
        <input type="text" name="limite" value="{$datacliente[0].limite}" class="validadDecimales" value="0.00" size="60" id="limite" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Días
    </td>
    <td >
        <input type="text" name="dias" class="validadDecimales" value="{$datacliente[0].dias}" value="0.00" size="60" id="dias" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Tolerancia
    </td>
    <td >
        <input type="text" name="tolerancia" class="validadDecimales" value="{$datacliente[0].tolerancia}"   size="60" id="tolerancia" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Descuento Parcial
    </td>
    <td >
        <input type="text" name="porc_parcial"  class="validadDecimales" value="{$datacliente[0].porc_parcial}"   size="60" id="porc_parcial" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Descuento Global
    </td>
    <td >
        <input type="text" name="porc_descuento_global"  class="validadDecimales"  value="{$datacliente[0].porc_descuento_global}"  size="60" id="porc_descuento_global" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
     ¿Se calcula retención impuesto ISLR?
    </td>
    <td >
         <select name="calc_reten_impuesto_islr" id="calc_reten_impuesto_islr">
{html_options values=$option_values_calc_reten_impuesto_islr output=$option_output_calc_reten_impuesto_islr selected=$option_selected_calc_reten_impuesto_islr}
        </select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Retencion ISLR
    </td>
    <td >
        <input type="text" name="porc_descuento_global"  value="{$datacliente[0].porc_descuento_global}"  class="validadDecimales" size="60" id="porc_descuento_global" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Vendedor
    </td>
    <td >
        <select name="cod_vendedor" id="cod_vendedor">
          <option value="0"></option>
          {html_options values=$option_values_vendedor output=$option_output_vendedor selected=$option_selected_vendedor}
        </select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Zona
    </td>
    <td >
        <select name="cod_zona" id="cod_zona">
            <option value="0"></option>
            {html_options values=$option_values_zona output=$option_output_zona selected=$option_selected_zona}
        </select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      R.I.F. **
    </td>
    <td >
        <input type="text" name="rif" size="60" value="{$datacliente[0].rif}" id="rif" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      NIT
    </td>
    <td >
        <input type="text" name="nit" size="60" value="{$datacliente[0].nit}" id="nit" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Constribuyente Especial
    </td>
    <td >
        <select name="contribuyente_especial" id="contribuyente_especial">
            <option value="0"></option>
    {html_options values=$option_values_contribuyente_especial output=$option_output_contribuyente_especial selected=$option_selected_contribuyente_especial}
        </select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Retencion por Cliente
    </td>
    <td >
        <input type="text" name="retenido_por_cliente" value="{$datacliente[0].retenido_por_cliente}" class="validadDecimales"  size="60" id="retenido_por_cliente" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Tipo de Cliente
    </td>
    <td >
<select name="cod_tipo_cliente" id="cod_tipo_cliente">
<option value="5"></option>
    {html_options values=$option_values_tipo_cliente output=$option_output_tipo_cliente selected=$option_selected_tipo_cliente}
</select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Tipo de Precio
    </td>
    <td >
        <select name="cod_tipo_precio" id="cod_tipo_precio">
            <option value="0"></option>
{html_options values=$option_values_tipo_precio output=$option_output_tipo_precio selected=$option_selected_tipo_precio}
        </select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Clase
    </td>
    <td >
        <input type="text" name="clase" size="60" value="{$datacliente[0].clase}" id="clase" >
    </td>
</tr>

</table>
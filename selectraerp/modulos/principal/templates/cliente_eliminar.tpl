{literal}



<script language="JavaScript">
    $(document).ready(function(){

        $("form input").attr("readonly", "readonly");

        $("#aceptar").removeAttr("readonly");
        $("#nombre").focus();
        $("#formulario").submit(function(){
                if($("#nombre").val()==""&&$("#direccion").val()==""&&$("#telefonos").val()==""&&$("#rif").val()==""){
                    alert("Debe llenar todos los campos obligatorios (**)!");

                    return false;
                }

        });

       $(".validadDecimales").numeric();
       $(".validadDecimales").blur(function(){
            if($(this).val()!=''&&$(this).val()!='.'){
                $(this).val(parseFloat($(this).val()));
            }else{
                $(this).val("0.00");
            }
        });


    });
</script>
{/literal}

<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
<input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">

  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>

                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

<table   width="100%" border="0" >
<tr>
        <td colspan="4" class="tb-head" align="center">
          LOS CAMPOS CON ** SON OBLIGATORIOS&nbsp;
      </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Codigo
    </td>
    <td >
        <input type="text" name="cod_cliente"  value="{$datacliente[0].cod_cliente}" id="cod_cliente" >
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
       Fecha de Nacimiento
    </td>
    <td >
<input type="text" name="fnacimiento"   value="{$datacliente[0].fnacimiento}" id="fnacimiento" size="15" maxlength="12" value="">&nbsp;Ej.: 0000-00-00

    </td>
</tr>
 <tr>
        <td colspan="3" style="width:30%; vertical-align: top;" class="tb-head" >
          Distrito Escolar 
        </td>
        <td >
             <select name="id_distrito" id="id_distrito">
                {html_options values=$option_values_distrito output=$option_output_distrito selected=$option_selected_distrito }
             </select>
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
       Alterna
    </td>
    <td >
        <input type="text" name="altena" size="60" value="{$datacliente[0].altena}" id="altena" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Alterna 2
    </td>
    <td >
        <input type="text" name="alterna2" size="60" value="{$datacliente[0].alterna2}" id="alterna2" >
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
       Fax
    </td>
    <td >
        <input type="text" name="fax" size="60" value="{$datacliente[0].fax}" id="fax" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       E-Mail
    </td>
    <td >
        <input type="text" name="email" size="60" value="{$datacliente[0].email}" id="email" >
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
        <input type="text" name="porc_reten_impuesto_islr"  value="{$datacliente[0].porc_reten_impuesto_islr}"  class="validadDecimales" size="60" id="porc_reten_impuesto_islr" >
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




<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="aceptar" id="aceptar" value="Eliminar Cliente">
    </td>
    </tr>
    </tbody>
</table>

</form>

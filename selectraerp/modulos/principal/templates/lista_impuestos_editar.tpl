{literal}
<script language="JavaScript">
    $(document).ready(function(){
        $("#descripcion").focus();
        $("#formulario").submit(function(){
                if($("#descripcion").val()==""){
                    $.facebox("Debe especificar la descripcion de la forma de pago");
                    $("#descripcion").focus();
                    return false;
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
<input type="hidden" name="codBanco" value="{$smarty.get.cod}">
<input type="hidden" name="codCuenta" value="{$smarty.get.cod_cuenta}">
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
          &nbsp;
      </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Codigo
    </td>
    <td >
        <input type="text" name="cod_impuesto" value="{$datos_impuesto[0].cod_impuesto}" id="cod_impuesto" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Entidad
    </td>
    <td >
<select name="cod_entidad" id="cod_entidad">
{html_options output=$option_output_funcionentidad values=$option_values_funcionentidad selected=$datos_impuesto[0].cod_entidad}
</select>

    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Impuesto Aplicado
    </td>
    <td >
<select name="cod_tipo_impuesto" id="cod_tipo_impuesto">
{html_options output=$option_output_funciontipoimpuesto values=$option_values_funciontipoimpuesto selected=$datos_impuesto[0].cod_tipo_impuesto}
</select>

    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Formula a aplicar
    </td>
    <td >
<select name="cod_formula" id="cod_formula">
{html_options output=$option_output_funcionformula values=$option_values_funcionformula selected=$datos_impuesto[0].cod_formula}
</select>

    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Fecha de Aplicacion
    </td>
    <td >
<input type="text" name="fecha_aplicacion" value="{$datos_impuesto[0].fecha_aplicacion}" id="fecha_aplicacion" size="15" maxlength="12">&nbsp;Ej.: 0000-00-00

    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Descripcion
    </td>
    <td >
        <input type="text" name="descripcion" size="60" value="{$datos_impuesto[0].descripcion}" id="descripcion" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Abreviatura
    </td>
    <td >
        <input type="text" name="siglas" size="60" value="{$datos_impuesto[0].siglas}" id="siglas" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Codificacion del Impuesto
    </td>
    <td >
        <input type="text" name="codificacion_impuesto" size="60" value="{$datos_impuesto[0].codificacion_impuesto}" id="codificacion_impuesto" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       % Porcentaje a Aplicar
    </td>
    <td>
        <input type="text" name="alicuota" size="60" value="{$datos_impuesto[0].alicuota}" id="alicuota" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Pago Mayor a
    </td>
    <td>
        <input type="text" name="pago_mayor_a" size="60" value="{$datos_impuesto[0].pago_mayor_a}" id="pago_mayor_a">
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Monto de Sustraccion
    </td>
    <td >
        <input type="text" name="monto_sustraccion" size="60" value="{$datos_impuesto[0].monto_sustraccion}" id="monto_sustraccion" >
    </td>
</tr>

</table>
<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="aceptar" id="aceptar" value="Guardar">
    </td>
    </tr>
    </tbody>
</table>

</form>

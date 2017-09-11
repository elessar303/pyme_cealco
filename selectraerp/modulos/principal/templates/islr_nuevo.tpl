{literal}
<script language="JavaScript">
    $(document).ready(function(){
        $("#descripcion").focus();
        $("#formulario").submit(function(){
                if($("#descripcion").val()==""){
                    alert("Debe Ingresar todos campos");
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
    <td valign="top"  colspan="3" width="30%" class="tb-head">
       Codigo ISLR
    </td>
    <td>
        <input type="text" name="cod_impuesto_islr" value="#numero" id="cod_impuesto_islr">
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head">
       Descripción
    </td>
    <td>
        <input type="text" name="descripcion" size="60" id="descripcion" >
    </td>
</tr>
<tr>
        <td colspan="4" class="tb-head" align="center">
          Base Imponible Residentes / Domiciliados
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Imponible
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#imponibleresidente").numeric();
            });


        </script>
        {/literal}

        <input type="text"  name="imponibleresidente" size="60" id="imponibleresidente" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Aplica Residente Domiciliada
    </td>
    <td>

        <input type="text"  name="aplicaresidente" size="60" id="aplicaresidente" >
    </td>
</tr>
<tr>
        <td colspan="4" class="tb-head" align="center">
          Base Imponible No Residentes / No Domiciliados
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Imponible
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#imponiblenoresidente").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="imponiblenoresidente" size="60" id="imponiblenoresidente" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Aplica Residente No Domiciliada
    </td>
    <td>

        <input type="text" name="aplicanoresidente" size="60" id="aplicanoresidente" >
    </td>
</tr>
<tr>
        <td colspan="4" class="tb-head" align="center">
          Personas Naturales Residentes Declarantes
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Alicuota
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#alicuotanaturalde").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="alicuotanaturalde" size="60" id="alicuotanaturalde" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Monto de Sustraccion
    </td>
    <td>

        <input type="text" name="sustraccionnaturalde" size="60" id="sustraccionnaturalde" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Pago Mayores A
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#pagomayoranaturalde").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="pagomayoranaturalde" size="60" id="pagomayoranaturalde" >
    </td>
</tr>

<tr>
        <td colspan="4" class="tb-head" align="center">
          Personas Naturales Residentes No Declarantes
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Alicuota
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#alicuotanaturalnode").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="alicuotanaturalnode" size="60" id="alicuotanaturalnode" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Monto de Sustraccion
    </td>
    <td>

        <input type="text" name="sustraccionnaturalnode" size="60" id="sustraccionnaturalnode" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Pago Mayores A
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#pagomayoranaturalnode").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="pagomayoranaturalnode" size="60" id="pagomayoranaturalnode" >
    </td>
</tr>

<tr>
        <td colspan="4" class="tb-head" align="center">
         Persona Natural No Residente
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Alicuota
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#alicuotanaturalno").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="alicuotanaturalno" size="60" id="alicuotanaturalno" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Retencion Acumulativa
    </td>
    <td>

        <input type="text" name="retencionacumuladanaturalno" size="60" id="retencionacumuladanaturalno" >
    </td>
</tr>

<tr>
        <td colspan="4" class="tb-head" align="center">
          Personas Juridicas Residentes
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Alicuota
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#alicuotajuridica").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="alicuotajuridica" size="60" id="alicuotajuridica" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Monto de Sustraccion
    </td>
    <td>
        <input type="text" name="sustraccionjuridica" size="60" id="sustraccionjuridica" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Pago Mayores A
    </td>
    <td>

        {literal}
        <script>

            $(document).ready(function(){
                $("#pagomayorajuridica").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="pagomayorajuridica" size="60" id="pagomayorajuridica" >
    </td>
</tr>

<tr>
        <td colspan="4" class="tb-head" align="center">
         Persona Juridica No Domiciliada
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      % Alicuota
    </td>
    <td >

        {literal}
        <script>

            $(document).ready(function(){
                $("#alicuotajuridicano").numeric();
            });


        </script>
        {/literal}

        <input type="text" name="alicuotajuridicano" size="60" id="alicuotajuridicano" >
    </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Retencion Acumulativa
    </td>
    <td>

        <input type="text" name="retencionacumuladajuridicano" size="60" id="retencionacumuladajuridicano" >
    </td>
</tr>

</table>




<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="aceptar" id="aceptar" value="Guardar Cambios">
    </td>
    </tr>
    </tbody>
</table>

</form>


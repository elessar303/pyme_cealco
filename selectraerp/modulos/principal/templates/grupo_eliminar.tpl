<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
{literal}
<script language="JavaScript">
    $(document).ready(function(){
        $("#descripcion_departamento").focus();
        $("#formulario").submit(function(){
                if($("#descripcion_departamento").val()==""){
                    alert("Debe Ingresar la descripción del Departamento!.");
                    $("#descripcion_departamento").focus();
                    return false;
                }

        });

         $("#restringido_grupo").click(function() {          
           if($("#restringido_grupo").is(":checked")){
                $("#restringido").show();
           }else{
                $("#restringido").hide();
                $("#cantidad_grupo").val("");
                $("#dias_grupo").val("");
           }
        });
           if($("#restringido_grupo").is(":checked")){
                $("#restringido").show();
               
           }else{
                $("#restringido").hide();
                $("#cantidad_grupo").val("");
                $("#dias_grupo").val("");

           }
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
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Rubro
    </td>
     <td style="padding-top:2px; padding-bottom: 2px;">
        <select disabled name="rubro" id="rubro" class="form-text" readonly >
            {html_options values=$option_values_rubro output=$option_output_rubro selected=$option_selected_rubro}
        </select>
                               
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Descripción
    </td>
    <td style="padding-top:2px; padding-bottom: 2px;">
        <input class="form-text" type="text" name="descripcion_grupo" size="60" id="descripcion_grupo" value="{$datos_grupo[0].descripcion}" readonly>
    </td>
</tr>
<tr style="height: 30px;">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Restringido
    </td>
    <td style="padding-top:2px; padding-bottom: 2px;">
        <input disabled  class="form-text" type="checkbox" name="restringido_grupo"  {if $datos_grupo[0].restringido eq "1" } checked {/if}  value="1" id="restringido_grupo" readonly >
    </td>
</tr>
<tr>
    <td>
        <tbody style="display: none" id="restringido">
            <tr style="height: 30px;">
                <td valign="top"  colspan="3" width="30%" class="tb-head" >
                   Cantidad Restringido
                </td>
                <td style="padding-top:2px; padding-bottom: 2px;">
                    <input  class="form-text" type="text" name="cantidad_grupo" value="{$datos_grupo[0].cantidad_rest}" size="30" id="cantidad_grupo" readonly >
                </td>
            </tr style="height: 30px;">
             <tr>
                <td valign="top"  colspan="3" width="30%" class="tb-head" >
                   Dias Restringido
                </td>
                <td style="padding-top:2px; padding-bottom: 2px;">
                    <input  class="form-text" value="{$datos_grupo[0].dias_rest}" type="text" name="dias_grupo" size="30" id="dias_grupo" readonly >
                </td>
            </tr>
        </tbody >
    </td>
</tr>
</table>




<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="aceptar" id="aceptar" value="Eliminar">
    </td>
    </tr>
    </tbody>
</table>

</form>



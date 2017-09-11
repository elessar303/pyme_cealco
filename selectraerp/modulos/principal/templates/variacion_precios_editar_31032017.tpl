<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/ajax.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
{literal}
<script language="JavaScript">
function validarItem(cod, nom)
{
    //$("#nombre0").html(cod.value);

    if(cod.value!=''){
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=ValidarCodigoBarraItem&v1="+cod.value,
            beforeSend: function(){
                $("#notificacionVCoditem").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
            },
            success: function(data){
                if(data == "NO EXISTE EL PRODUCTO")
                {
                    $("#"+nom).html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ico_note.gif\"><span style=\"color:red;\"><b>"+data+"</b></span>");
                    cod.focus();
                }
                else
                {
                    $("#"+nom).html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b>"+data+"</b></span>");
                }
                /*if(resultado=="-1"){
                    //$("#cod_item").val("").focus();
                    $("#notificacionVCoditem").html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ico_note.gif\"><span style=\"color:red;\"><b>Disculpe, este c&oacute;digo ya existe.</b></span>");
                }
                else if(resultado=="1"){//cod de item disponble, originalmente sin "else"
                    $("#notificacionVCoditem").html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> C&oacute;digo Disponible</b></span>");
                }*/
            }
        });
    }
}
   /* $(document).ready(function(){
        


        $("#restringido_grupo").click(function() {          
           if($("#restringido_grupo").is(":checked")){
                $("#restringido").show();
           }else{
                $("#cantidad_grupo").val("");
                $("#dias_grupo").val("");
                $("#restringido").hide();
           }
        });
        /*$("#rubro").click(function() {          
           if($("#rubro").val()==2){
                $("#restringido2").show();
           }else{
                $("#restringido2").hide();
           }
        });*/

   /* });*/
</script>
{/literal}

<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
<input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">
<input type="hidden" name="num_campos" value="20">
<input type="hidden" name="id_var_precio_cab" value="{$smarty.get.cod}">

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

<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="aceptar" id="aceptar" value="Guardar">
    </td>
    </tr>
    </tbody>
</table>

<table   width="100%" border="0" >
    <thead>
        <tr class="tb-head">
            <th style="width:220px;" >Codigo de Barras</th>
            <th style="width:400px;">Nombre</th>
            <th>Precio</th>
            <th>Estado</th>
            <th>Motivo de la variaci&oacute;n</th>
            <th>Observacion</th>
        </tr>
    </thead>

    <tbody>

        {foreach from=$registros key=i item=campos}
        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="codigo_barras{$i}" onblur="validarItem(this, 'nombre{$i}')" size="30" id="codigo_barras{$i}" value="{$campos.codigo_barra}" autofocus>
            </td>
            <td><div id="nombre{$i}"></div>
                <!--<input class="form-text" type="text" name="nombre{$i}" size="45" id="nombre{$i}" value="{$campos.descripcion1}" readonly>-->
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="precio{$i}" size="10" id="precio{$i}" value="{$campos.precio_sin_iva}">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                <select class="form-text" type="text" name="estado{$i}" >
                    <!--<option value="">Todos</option>-->
                    {html_options values=$option_values_estados output=$option_output_estados selected=$campos.id_estado}
                </select>
            </td>

            <td>
                <select class="form-text" type="text" name="motivo{$i}" id="motivo{$i}" >
                    <option value="">--Seleccione el motivo--</option>
                    {html_options values=$option_values_motivo output=$option_output_motivo selected=$campos.id_motivo}
                </select>
            </td>
            <td>
                <input type="text" name="observacion{$i}" id="observacion{$i}" class="form-text" size="25" value="{$campos.observacion}">
            </td>
        </tr>

        {/foreach}
        {assign var=i value=$i+1}
        {section name=k start=$i loop=20 step=1}
        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="codigo_barras{$smarty.section.k.index}" onblur="validarItem(this, 'nombre{$smarty.section.k.index}')" size="30" id="codigo_barras{$smarty.section.k.index}" >
            </td>
            <td><div id="nombre{$smarty.section.k.index}"></div></td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="precio{$smarty.section.k.index}" size="10" id="precio{$smarty.section.k.index}" value="0.00">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                <select class="form-text" type="text" name="estado{$smarty.section.k.index}" >
                    <option value="">Todos</option>                               
                    {html_options values=$option_values_estados output=$option_output_estados}
                </select>
            </td>

            <td>
                <select class="form-text" type="text" name="motivo{$smarty.section.k.index}" id="motivo{$smarty.section.k.index}" >
                    <option value="">--Seleccione el motivo--</option>
                    {html_options values=$option_values_motivo output=$option_output_motivo}
                </select>
            </td>
            <td>
                <input type="text" name="observacion{$smarty.section.k.index}" id="observacion{$smarty.section.k.index}" class="form-text" size="25" >
            </td>
        </tr>

        {/section}
    </tbody>
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

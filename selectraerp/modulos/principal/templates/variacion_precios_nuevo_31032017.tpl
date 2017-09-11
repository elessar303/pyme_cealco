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
        <link type="text/css" media="screen" rel="stylesheet" href="../../libs/css/nueva_factura.css" />
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

function buscarItem(num){

        //var teclaTabMasP  = 13;
        //var codeCurrent = ev.keyCode;
        //window.alert(num);
        //window.alert('motivo'+num);
        //var value = $(this).val();
        
        //if(teclaTabMasP == codeCurrent){
            //if(_.str.isBlank(value)) {
    pBuscaItem.main.mostrarWin(num);
    return false;
            //}
            //$.filtrarArticulo(value, "filtroItemByRCCB");
            //return false;
        // }

}


$(document).ready(function(){

    $("#aceptar").click(function() {

        /*Seleccion obligatoria del motivo de la variacion
        hz*/
        if(
            $("#motivo").val()=="" ||
            $("#motivo").val()=="0" 
          ){
            Ext.Msg.alert("Alerta","Debe Especificar el Estado.");
            return false;
        };
    });
    /*$("input[name='buscar']").click(function(){

        //var teclaTabMasP  = 13;
        //var codeCurrent = ev.keyCode;
        var value = $(this).val();
        window.alert(value);
        //if(teclaTabMasP == codeCurrent){
            //if(_.str.isBlank(value)) {
                pBuscaItem.main.mostrarWin();
                return false;
            //}
            //$.filtrarArticulo(value, "filtroItemByRCCB");
            //return false;
        // }
    });
    
    /*$("#codigo_barras2").change(function(event) {

        codbarra=$("#codigo_barras2").val();
        
        //cantidad2=$("#costo_iva").val();
        var n = cantidad;
        //var n2 = cantidad2;
        //var mg = n*n2;

        //$("#margen_ganancia").val(mg);

        //var precioventa = parseFloat(n2)+parseFloat(mg);
        $("#codigo_barras").val(n);

    });*


        /*$("#restringido_grupo").click(function() {          
           if($("#restringido_grupo").is(":checked")){
                $("#restringido").show();
           }else{
                $("#cantidad_grupo").val("");
                $("#dias_grupo").val("");
                $("#restringido").hide();
           }
        });*
        /*$("#rubro").click(function() {          
           if($("#rubro").val()==2){
                $("#restringido2").show();
           }else{
                $("#restringido2").hide();
           }
        });*/

});

/*window.onload = function () {
    //document.formularioContacto.nombre.focus();
    document.formulario.addEventListener('submit', validarFormulario);
}
 
function validarFormulario(evObject) {
evObject.preventDefault();
var todoCorrecto = true;
var formulario = document.formulario;
for (var i=0; i<formulario.length; i++) {
                if(formulario[i].motivo{$smarty.section.i.index}.value =='') {
                               if (formulario[i].value == null || formulario[i].value.length == 0 || /^\s*$/.test(formulario[i].value)){
                               alert (formulario[i].name+ ' no puede estar vacío o contener sólo espacios en blanco');
                               todoCorrecto=false;
                               }
                }
                }
if (todoCorrecto ==true) {formulario.submit();}
}*/

                
                    function validar() {
                        //obteniendo el valor que se puso en campo text del formulario
                        miCampoTexto = document.getElementById("motivo{$smarty.section.i.index}").value;
                        alert(miCampoTexto);
                        //la condición
                        if (miCampoTexto.length == 0) {
                            Ext.Msg.alert("Alerta","Debe Especificar el Motivo de la variacion.")
                            return false;
                        }
                        return true;
                    }
                


</script>
{/literal}
    <script type="text/javascript" src="../../libs/js/underscore.js"></script>
    <script type="text/javascript" src="../../libs/js/underscore.string.js"></script>
    <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida2.js"></script>
</head>

<!-- <form name="formulario" id="formulario" method="POST" action="" onsubmit="return validar()"> -->
<form name="formulario" id="formulario" method="POST" action="" >
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
<input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">
<input type="hidden" name="num_campos" value="50">

  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" name="find" border="0" cellpadding="0" cellspacing="0">
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
            <th style="width:200px;">Nombre</th>
            <th>Precio Sin IVA</th>
            <th>Estado</th>
            <th>Motivo de la variaci&oacute;n</th>
            <th>Observacion</th>
        </tr>
    </thead>

    <tbody>
        {section name=i start=0 loop=50 step=1}

        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                
                <!--<input type="text" name="codigo_barras{$smarty.section.i.index}" id="codigo_barras{$smarty.section.i.index}" onblur="validarItem(this, 'nombre{$smarty.section.i.index}')" class="form-text" size="30" style="float: left;">-->
                <input type="text" name="codigo_barras{$smarty.section.i.index}" id="codigo_barras{$smarty.section.i.index}" style="float: left;" onblur="validarItem(this, 'nombre{$smarty.section.i.index}')" class="form-text" />
                <!--<input type="text" name="codigo_barras" id="codigo_barras" style="float: left;" class="form-text" onblur="validarItem(this, 'nombre{$smarty.section.i.index}')" />-->

                <input type="button" id="buscar{$smarty.section.i.index}" name="buscar" value="Buscar" style="float: left;" onClick="buscarItem({$smarty.section.i.index})" />
            </td>
            <td><div id="nombre{$smarty.section.i.index}"></div></td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="precio{$smarty.section.i.index}" size="10" id="precio{$smarty.section.i.index}" value="0.00">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                <select class="form-text" type="text" name="estado{$smarty.section.i.index}" >
                    <!--<option value="0">Todos</option>-->
                    {html_options values=$option_values_estados output=$option_output_estados}
                </select>
            </td>

            <td>
                <select class="form-text" type="text" name="motivo{$smarty.section.i.index}" id="motivo{$smarty.section.i.index}" >
                    <option value="">--Seleccione el motivo--</option>
                    {html_options values=$option_values_motivo output=$option_output_motivo}
                </select>
            </td>
            <td>
                <input type="text" name="observacion{$smarty.section.i.index}" id="observacion{$smarty.section.i.index}" class="form-text" size="25" >
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
</html>


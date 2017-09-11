<script language="JavaScript" src="../../libs/js/md5_crypt.js"></script>
{literal}
    <script language="JavaScript">
        $(document).ready(function(){
            $("#usuario").focus();
            $("#formulario").submit(function(){
                    if($("#usuario").val()==""||$("#nombreyapellido").val()==""){
                                        alert("Debe llenar todos los campos");
                            return false;
                    }else{
                                            if ($("#clave1").val() != '' || $("#clave2").val() != '') {
                                                    if ($("#clave1").val() != $("#clave2").val()) {
                                                            alert("Las claves no coinciden!.");
                                                            $("#clave1, #clave2").val("");
                                                            $("#clave1").focus();
                                                            return false;
                                                    }
                                                    else {
                                                            claveMD5 = hex_md5($("#clave1").val());
                                                            $("#clave1, #clave2").val(claveMD5);

                                                    }
                                            }
                                    }
            });

                    /*
    $("#usuario").blur(function(){
                            valor = $(this).val();
                            if(valor!=''){
                                    $.ajax({
                            type: "GET",
                            url:  "../../libs/php/ajax/ajax.php",
                            data: "opt=ValidarNombreUsuario&v1="+valor,
                            beforeSend: function(){
                                $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nombre de Usuario..<b>"));
                            },
                            success: function(data){
                                resultado = data
                            if(resultado=="-1"){
                                    $("#usuario").val("").focus();
                                    $("#notificacionVUsuario").html("<img align=\"absmiddle\"  src=\"../../libs/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Usuario ya existe.</b></span>");
                            }
                                if(resultado=="1"){//cod de item disponble
                                    $("#notificacionVUsuario").html("<img align=\"absmiddle\"  src=\"../../libs/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nombre de usuario Disponible</b></span>");
                            }
                            }
                    });
                            }
                    });
    */


        });
    </script>
{/literal}

<form name="formulario" id="formulario" method="POST" action="">
    <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
    <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
    <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
    <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">
    <input type="hidden" name="cod_usuario" value="{$smarty.get.cod}">

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
                Usuario **
            </td>
            <td >
                <input type="text" name="usuario" value="{$datos_usuarios[0].usuario}" readonly="readonly" size="60" id="usuario" >
                <div id="notificacionVUsuario"></div>
            </td>
        </tr>
        <tr>
            <td valign="top"  colspan="3" width="30%" class="tb-head" >
                Nombre y Apellido **
            </td>
            <td >
                <input type="text" name="nombreyapellido" value="{$datos_usuarios[0].nombreyapellido}" size="60" id="nombreyapellido" >
            </td>
        </tr>
        <tr>
            <td valign="top"  colspan="3" width="30%" class="tb-head" >
                Clave Anterior
            </td>
            <td >
                <input maxlength="90" readonly="readonly" type="password"  value="{$datos_usuarios[0].nombreyapellido}" name="clave1" size="60" id="claveOLD" >
            </td>
        </tr>
        <tr>
            <td valign="top"  colspan="3" width="30%" class="tb-head" >
                Nueva Clave
            </td>
            <td >
                <input maxlength="90" type="password" name="clave1" size="60" id="clave1" >
            </td>
        </tr>
        <tr>
            <td valign="top"  colspan="3" width="30%" class="tb-head" >
                Confirmar Nueva Clave
            </td>
            <td >
                <input maxlength="90" type="password" name="clave2" size="60" id="clave2" >
            </td>
        </tr>
        <tr>
            <td valign="top"  colspan="3" width="30%" class="tb-head" >
                Departamento
            </td>
            <td >
                <select name="cod_unidad" id="cod_unidad">
                    {html_options values=$option_values_centro output=$option_output_centro selected=$option_selected_centro}
                </select>
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

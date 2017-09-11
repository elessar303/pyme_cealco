<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {assign var=name_form value="usuarios_nuevo"}
        {include file="snippets/header_form.tpl"}
        <!--Para estilo JQuery en botones-->
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/md5_crypt.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        <script type="text/javascript" src="../../libs/js/usuarios_tabs.js"></script>
        {literal}
            <script type="text/javascript">//<![CDATA[
                function add_opciones_perfil(){
                    var perfil_opciones = document.getElementsByName("perfil");
                    var perfil_opcion_seleccionada;
                    for(var i = 0; i<perfil_opciones.length;i++){
                        if (perfil_opciones[i].checked){
                            perfil_opcion_seleccionada = perfil_opciones[i].value;
                            i = perfil_opciones.length;
                        }
                    }
                    if(perfil_opcion_seleccionada=="1"){
                        document.getElementById("checkbox_opcion_0").checked=true;
                        document.getElementById("checkbox_opcion_1").checked=true;
                        document.getElementById("checkbox_opcion_2").checked=true;
                        document.getElementById("checkbox_opcion_3").checked=true;
                        document.getElementById("checkbox_opcion_4").checked=true;
                        document.getElementById("checkbox_opcion_5").checked=true;
                        document.getElementById("checkbox_opcion_6").checked=true;
                        document.getElementById("checkbox_opcion_7").checked=true;
                        document.getElementById("checkbox_opcion_8").checked=true;
                        document.getElementById("checkbox_opcion_9").checked=true;
                        document.getElementById("checkbox_opcion_10").checked=true;
                    }
                    else if(perfil_opcion_seleccionada=="2"){
                        document.getElementById("checkbox_opcion_0").checked=false;
                        document.getElementById("checkbox_opcion_1").checked=true;
                        document.getElementById("checkbox_opcion_2").checked=true;
                        document.getElementById("checkbox_opcion_3").checked=true;
                        document.getElementById("checkbox_opcion_4").checked=true;
                        document.getElementById("checkbox_opcion_5").checked=true;
                        document.getElementById("checkbox_opcion_6").checked=true;
                        document.getElementById("checkbox_opcion_7").checked=true;
                        document.getElementById("checkbox_opcion_8").checked=true;
                        document.getElementById("checkbox_opcion_9").checked=true;
                        document.getElementById("checkbox_opcion_10").checked=false;
                    }else /*if(perfil_opcion_seleccionada=="3")*/{
                        document.getElementById("checkbox_opcion_0").checked=false;
                        document.getElementById("checkbox_opcion_1").checked=false;
                        document.getElementById("checkbox_opcion_2").checked=false;
                        document.getElementById("checkbox_opcion_3").checked=false;
                        document.getElementById("checkbox_opcion_4").checked=false;
                        document.getElementById("checkbox_opcion_5").checked=true;
                        document.getElementById("checkbox_opcion_6").checked=false;
                        document.getElementById("checkbox_opcion_7").checked=false;
                        document.getElementById("checkbox_opcion_8").checked=false;
                        document.getElementById("checkbox_opcion_9").checked=false;
                        document.getElementById("checkbox_opcion_10").checked=false;
                    }
                }
                $(document).ready(function(){
                    $("#nombreyapellido").focus();
                    $("input[name='cancelar']").button();//Coloca estilo JQuery
                    $("input[name='aceptar']").button().click(function(){
                        var usu = document.formulario.usuario.value;
                        var nom = document.formulario.nombreyapellido.value;
                        var c1 = document.formulario.clave1.value;
                        var c2 = document.formulario.clave2.value;
                        //if($("#usuario").val()==="" || $("#nombreyapellido").val()==="" || $("#clave1").val()==="" || $("#clave2").val()===""){
                        if(usu==""||nom==""||c1==""||c2==""){
                            //alert("Debe llenar todos los campos");
                            Ext.Msg.alert("Alerta","Debe llenar todos los campos");
                            return false;
                        }else{
                            if(c1 !== c2){
                               Ext.Msg.alert("Alerta","Las claves no coinciden!");
                                $("#clave1, #clave2").val("");
                                $("#clave1").focus();
                                return false;
                            }else{
                                claveMD5  = hex_md5(c1);
                                //claveMD5  = hex_md5($("#clave1").val());
                                $("#clave1, #clave2").val(claveMD5);
                            }
                        }
                    });
                    $("#usuario").blur(function(){
                        valor = $(this).val();
                        if(valor!=''){
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarNombreUsuario&v1="+valor,
                                beforeSend: function(){
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nombre de Usuario..</b>"));
                                },
                                success: function(data){
                                    resultado = data
                                    if(resultado=="-1"){
                                        $("#nombreyapellido").val("").focus();
                                        $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Usuario ya existe.</b></span>");
                                    }
                                    if(resultado=="1"){//cod de item disponble
                                        $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nombre de usuario Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });
                });/*end of document.ready*/
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <div id="contenedorTAB">
                    <div id="div_tab1" class="x-hide-display">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="4" class="tb-head">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="label">C&oacute;digo</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="cod_usuario" placeholder="No escriba nada aqu&iacute;" id="cod_usuario" readonly class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">Nombre y Apellido **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="nombreyapellido" placeholder="Nombre y apellido del usuario" size="60" id="nombreyapellido" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">Usuario **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="usuario" placeholder="Nombre de usuario para iniciar sesi&oacute;n" size="60" id="usuario" class="form-text"/>
                                        <div id="notificacionVUsuario"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">Clave</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input maxlength="90" type="password" name="clave1" placeholder="Contrase&ntilde;a para el usuario" size="60" id="clave1" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">Confirmar Clave</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input maxlength="90" type="password" name="clave2" placeholder="Repita la contrase&ntilde;a anterior" size="60" id="clave2" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">{$DatosGenerales[0].string_clasificador_inventario1}</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="cod_unidad" id="cod_unidad" class="form-text">
                                            <option>Asigne un {$DatosGenerales[0].string_clasificador_inventario1}</option>
                                            {html_options values=$option_output_departamentos output=$option_values_departamentos}
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="div_tab2" class="x-hide-display">
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="tb-head" style="text-align:center"><input type="radio" name="perfil" id="perfil_admin" value="1" onclick="add_opciones_perfil();" />Administrador</th>
                                    <th class="tb-head" style="text-align:center"><input type="radio" name="perfil" id="perfil_geren" value="2" onclick="add_opciones_perfil();" />Gerente</th>
                                    <th class="tb-head" style="text-align:center"><input type="radio" name="perfil" id="perfil_emple" value="3" onclick="add_opciones_perfil();" checked />Empleado/Vendedor</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$modulos key=i item=opcion}
                                    <tr>
                                        <td colspan="3" style="text-align: left; padding-left: 20px; padding-top:8px; padding-bottom: 8px;">
                                            <!--Por defecto se crea el usuario con perfil "Vendedor"-->
                                            <input type="checkbox" name="valor_modulo[]" value="{$opcion.cod_modulo}" id="checkbox_opcion_{$i}" {if $opcion.cod_modulo eq 5}checked{/if}/>
                                            <span style="padding-left: 10px;">{$opcion.nom_menu}</span>
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
                <table style="width:100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar" />
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
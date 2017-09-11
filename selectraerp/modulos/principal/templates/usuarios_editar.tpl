<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {assign var=name_form value="usuarios_editar"}
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
                    for(var i=0; i<perfil_opciones.length;i++){
                        if (perfil_opciones[i].checked){
                            perfil_opcion_seleccionada=perfil_opciones[i].value;
                            i=perfil_opciones.length;
                        }
                    }
                    if(perfil_opcion_seleccionada=="1"){
                        document.getElementById("cajero").checked=false;
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
                        document.getElementById("cajero").checked=false;
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
                        document.getElementById("cajero").checked=true;
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
                    $("#clave1").focus();
                        //$("input[type='password']").val('valor');
                        //$('input:password[name="clave1"]').val('valor');
                    $("input[name='cancelar']").button();//Coloca estilo JQuery
                    $("input[name='aceptar']").button().click(function(){
                        var c1 = document.formulario.clave1.value;//$("#clave1").val()
                        var c2 = document.formulario.clave2.value;//$("#clave2").val()
                        var nom = document.formulario.nombreyapellido.value;//$("#nombreyapellido").val()
                        if(nom === ""){
                            Ext.Msg.alert("Alerta","Debe suministrar Nombre y Apellido");
                                /*alert("clave1:"+$("#clave2").val())
                                alert("clave2:"+document.formulario.clave2.value)
                                alert("nombreyapellido:"+document.formulario.nombreyapellido.value)
                                alert("nuevo:"+$('input:password[name="clave1"]').val())
                                alert("nuevo:"+$('input:password[name="clave2"]').val())
                                alert("nuevo:"+$('input:text[name="nombreyapellido"]').val())*/
                                //$('input[type=password]').each(function(index,value){alert ($(value).val());});
                            return false;
                        }//else{
                        if (c1!=="" || c2!=="") {
                            if (c1 !== c2){
                                Ext.Msg.alert("Alerta","Las claves no coinciden!!");
                                $("#clave1, #clave2").val("");
                                return false;
                            }
                            else {
                                // claveMD5 = hex_md5($("#clave1").val());
                                claveMD5 = hex_md5(c1);                               
                                $("#clave1, #clave2").val(claveMD5);
                            }
                        }
                    });
                });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral">
                {include file = "snippets/regresar.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <input type="hidden" name="cod_usuario" value="{$smarty.get.cod}"/>
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
                                    <td colspan="3" class="label">
                                        Nombre y Apellido **
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="nombreyapellido" value="{$datos_usuarios[0].nombreyapellido}" size="60" id="nombreyapellido" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Usuario **
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="usuario" value="{$datos_usuarios[0].usuario}" readonly="readonly" size="60" id="usuario" class="form-text"/>
                                        <div id="notificacionVUsuario"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Clave Anterior
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input maxlength="90" readonly type="password" value="{$datos_usuarios[0].nombreyapellido}" name="claveOLD" size="60" id="claveOLD" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Nueva Clave
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input maxlength="90" type="password" name="clave1" value="" size="60" id="clave1" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">
                                        Confirmar Nueva Clave
                                    </td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input maxlength="90" type="password" name="clave2" value="" size="60" id="clave2" class="form-text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">{$DatosGenerales[0].string_clasificador_inventario1}</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="cod_unidad" id="cod_unidad" class="form-text">
                                            {html_options values=$option_values_centro output=$option_output_centro selected=$option_selected_centro}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="label">Estatus</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="estatus" id="estatus" class="form-text">
                                          <option {if $datos_usuarios[0].estatus eq "1" } selected {/if} value="1">Activo</option>
                                          <option {if $datos_usuarios[0].estatus eq "0" } selected {/if} value="0">Inactivo</option>
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
                                    <th class="tb-head" style="text-align:center"><input type="radio" name="perfil" id="perfil_emple" value="3" onclick="add_opciones_perfil();" />Empleado/Vendedor</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$modulos key=i item=opcion}
                                    {assign var=opcion_selec value="0"}
                                    {foreach from=$modulos_usuario key=j item=opcion_usuario}
                                        {if $opcion.cod_modulo eq $opcion_usuario.cod_modulo}
                                            {assign var=opcion_selec value="1"}
                                        {/if}
                                    {/foreach}
                                    <tr>
                                        <td colspan="3" style="text-align: left; padding-left: 20px; padding-top:8px; padding-bottom: 8px;">
                                            <input type="checkbox" name="valor_modulo[]" value="{$opcion.cod_modulo}" id="checkbox_opcion_{$i}" {if $opcion_selec eq "1"}checked{/if}/>
                                            <span style="padding-left: 10px;">{$opcion.nom_menu}</span>
                                        </td>
                                    </tr>
                                {/foreach}
                                <tr>
                                <td colspan="3" style="text-align: left; padding-left: 20px; padding-top:8px; padding-bottom: 8px;">
                                <input type="checkbox" name="cajero" id="cajero" {if $option_check_cajero eq "1"}checked {/if} />
                                <span style="padding-left :10px;">Cajero </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: left; padding-left: 20px; padding-top:8px; padding-bottom: 8px;">
                                <input type="checkbox" name="pedido" id="pedido" {if $option_check_pedido eq "1"}checked {/if} />
                                <span style="padding-left :10px;">Anular Pedidos </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: left; padding-left: 20px; padding-top:8px; padding-bottom: 8px;">
                                <input type="checkbox" name="ajuste_inventario" id="ajuste_inventario" 
                                {if $option_check_ajuste_inventario eq "1"}checked {/if} />
                                <span style="padding-left :10px;">Ajuste de Inventario </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <table style="width: 100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar Cambios" />
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
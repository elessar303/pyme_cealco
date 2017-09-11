<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        {literal}
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                $("#formulario").submit(function(){
                if($("#descripcion").val()=="" || $("#rif").val()==""){
                    alert("Debe llenar todos los campos obligatorios (**)!");
                    return false;
                }
                if(document.getElementById("rif").value.length < 10){
                alert('Ingrese un RIF de al menos 10 caracteres');
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

     $(".validadNumerico").numeric();
       $(".validadNumerico").blur(function(){
            if($(this).val()!=''&&$(this).val()!='.'){
                $(this).val(parseInt($(this).val()));
            }else{
                $(this).val("0");
            }
        });
});
//]]>
            </script>
        {/literal}
        <script src="../../libs/js/validar_rif.js" type="text/javascript"></script>
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <table style="width: 100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table style="width: 100%;">
                <tr>
                    <td colspan="4" class="tb-head" style="text-align: center;">
                        LOS CAMPOS MARCADOS CON ** SON OBLIGATORIOS&nbsp;
                    </td>
                </tr>
                <tr hidden="hidden">
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        C&oacute;digo Proveedor**
                    </td>
                    <td>
                        <input type="text" name="cod_proveedor" value="{$cod_proveedor}" readonly id="cod_proveedor" />
                    </td>
                </tr>
                
 <!--<tr>
 <td  colspan="3"style="width:30%; vertical-align: top;" class="tb-head">Foto</td>
 <td>
  <input type="file" name="foto" id="foto"  class="form-text"/>
  
 </td>
</tr>-->

            
                
                <tr>
                    <td colspan="4" class="tb-head" style="text-align: center;">
                        &nbsp;
                    </td>
                </tr>
                <tr hidden="hidden">
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Compa&ntilde;&iacute;a**
                    </td>
                    <td>
                        <input type="text" name="compania" size="60" value="" id="compania" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        {html_options values=$option_values_parametros output=$option_output_idfiscal} **
                    </td>
                    <td>
                        <input type="text" name="rif" size="60" id="rif" maxlength="30"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Descripci&oacute;n**
                    </td>
                    <td>
                        <input type="text" name="descripcion" size="60" value="" id="descripcion" />
                    </td>
                </tr>
                <tr hidden="hidden">
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Direcci&oacute;n **
                    </td>
                    <td>
                        <input type="text" name="direccion" size="60" id="direccion" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Tel&eacute;fonos **
                    </td>
                    <td>
                        <input type="text" name="telefonos" size="60" id="telefonos" />
                    </td>
                </tr>
                <tr hidden="hidden">
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Fax
                    </td>
                    <td>
                        <input type="text" name="fax" size="60" id="fax" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        E-Mail
                    </td>
                    <td>
                        <input type="text" name="email" size="60" id="email" />
                    </td>
                </tr>
                <tr hidden="hidden">
                    <td class="tb-head" colspan="3">
                        Tipo de Proveedor
                    </td>
                    <td>
                        <select name="id_pclasif" id="id_pclasif">
                            {html_options values=$option_values_clasi output=$option_output_clasi selected=$option_selected_clasi}
                        </select>
                    </td>
                </tr>
                <!--<tr>
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Especialidad
                    </td>
                    <td >
                        <select name="cod_especialidad" id="cod_especialidad">
                            {html_options values=$option_values_especialidad output=$option_output_especialidad selected=$option_selected_especialidad}
                        </select>
                    </td>
                </tr>-->
                <tr hidden="hidden">
                    <td class="tb-head" colspan="3">
                        Estatus
                    </td>
                    <td>
                        <select name="estatus" id="estatus">
                            <option value="A">Activo</option>
                            <option value="I">Inactivo</option>
                        </select>
                    </td>
                </tr>
                <tr hidden="hidden">
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Tipo de Entidad
                    </td>
                    <td >
                        <select name="cod_entidad" id="cod_entidad">
                            {html_options values=$option_values_entidad output=$option_output_entidad selected=$option_selected_entidad}
                        </select>
                    </td>
                </tr>
                <tr hidden="hidden">
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Retenci&oacute;n del I.V.A.
                    </td>
                    <td>
                        <select name="cod_impuesto_proveedor" id="cod_impuesto_proveedor">
                            {html_options values=$option_values_impuesto output=$option_output_impuesto}
                        </select>
                    </td>
                </tr>
                <!--<tr>
                    <td colspan="3" style="width: 15%; vertical-align: top;" class="tb-head" >
                        Cuenta Contable
                    </td>
                    <td>
                        <select name="cuenta_contable" id="cuenta_contable">
                            {html_options values=$option_values_cuenta output=$option_output_cuenta}
                        </select>
                    </td>
                </tr>-->
            </table>
            <table style="width: 100%;">
                <tbody>
                    <tr class="tb-tit" style="text-align: right;">
                        <td>
                            <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>
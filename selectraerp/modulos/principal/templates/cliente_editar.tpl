<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
        <script type="text/javascript">//<![CDATA[
        $(document).ready(function(){
            $("#nombre").focus();
            $("#formulario").submit(function(){
                if($("#tipoCliente").val()==0){
                     if($("#cod_cliente").val()==""||$("#nombre").val()==""||$("#direccion").val()==""||$("#telefonos").val()==""||$("#rif").val()==""){
                    alert("Debe llenar todos los campos obligatorios (**)!");
                    return false;
                      }

                }
             if($("#tipoCliente").val()==1){
                     if($("#cod_cliente").val()==""||$("#nombre").val()==""||$("#direccion").val()==""||$("#telefonos").val()==""||$("#id_distrito").val()=="" ||$("#parroquia").val()=="" ||$("#subsistema").val()=="" ||$("#dependecia").val()=="" ||$("#modalidadIngesta").val()=="" ||$("#turno").val()=="" ||$("#matricula").val()==""){
                    alert("Debe llenar todos los campos obligatorios (**)!");
                    return false;
                }  
             }
            });

            $("#cod_cliente").change(function(){
                //return false;
                var valor = $(this).val();
                //alert(valor);
                if(valor!=''){
                    $.ajax({
                        type: "GET",
                        url:  "../../libs/php/ajax/ajax.php",
                        data: "opt=ValidarCodigoCliente&v1="+valor,
                        beforeSend: function(){
                            $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Cod. Cliente..<b>"));
                        },
                        success: function(data){
                            var resultado = data
                            if(resultado=="-1"){
                                $("#cod_cliente").val("").focus();
                                $("#notificacionVCodCliente").html("<img align=\"absmiddle\"  src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este c&oacute;digo ya existe.</b></span>");
                            }
                            if(resultado=="1"){//cod de item disponble
                                $("#notificacionVCodCliente").html("<img align=\"absmiddle\"  src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> C&oacute;digo Disponible</b></span>");
                            }
                        }
                    });
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
              $("#tipoCliente").change(function(){
                
              if($(this).val()==0){                 
                   $("#paeCliente").hide();
                   $("#pdvalHogar").show();
                  
              }
               if($(this).val()==1){
                 $("#pdvalHogar").hide();
                 $("#paeCliente").show();
                 
              }        

            });

            $("#cod_tipo_cliente").change(function(){
               if($(this).val()==4){
                 $("#porc_descuento_global").val(4.00);
                }
                if($(this).val()!=4){
                 $("#porc_descuento_global").val(0.00);
                }

            });
      
      // seteo automatico de el tipo de cliente

        if($("#tipoCliente").val()==0){
            $("#paeCliente").hide();
            $("#pdvalHogar").show();

        }
        if($("#tipoCliente").val()==1){
              $("#pdvalHogar").hide();
              $("#paeCliente").show();
          }   
       

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
            <table style="width:100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%; padding: 1px;">
                            <tbody>
                                <tr>
                                    <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                                    <td width="75">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'">
                                            <tr>
                                                <td style="padding: 0px; text-align:right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align:left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
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
                    <tr height="20px"></tr>
                    <td colspan="3"  class="label" >Tipo de cliente</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cod_tipo_cliente" id="cod_tipo_cliente" class="form-text" style="width:300px;">
                          {html_options values=$option_values_tipo_cliente output=$option_output_tipo_cliente selected=$option_selected_tipo_cliente}  
                        </select>
                    </td>
                </tr>
                <tr>
                    <tr height="20px"></tr>
                    <td colspan="3"  class="label" >Codigo</td>
                    <td style="padding-top:2px; padding-bottom: 2px;" >
                        <input type="text" name="cod_cliente" value="{$datacliente[0].cod_cliente}" readonly id="cod_cliente" class="form-text" style="width:300px;" >
                          <input type="hidden" name="id_cliente" value="{$datacliente[0].id_cliente}" id="id_cliente" />

                        <div id="notificacionVCodCliente"></div>
                    </td>
                </tr>

                <!-- no tenia nada -->
<!--                 <tr>
                    <td colspan="4" class="tb-head" align="center">
                        &nbsp;
                    </td>
                </tr> -->
                <tr>
                    <td  colspan="3"class="label" >Foto</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="file" name="foto" id="foto"  class="form-text" style="width:300px;"/>
                    </td>
                </tr>
               
              <!--   inncesario  -->
               <!--  <tr>
                    <td colspan="3" style="width:30%; vertical-align: top;" class="tb-head"></td>
                    <td  align="left"> 
                        <img src="../../imagenes/{$datacliente[0].foto}" width="100" align="absmiddle" height="100"/> 
                    </td> 
                </tr>  -->
                <tr>
                    <td colspan="3" class="label" >Nombre del Cliente **</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="nombre" size="60" value="{$datacliente[0].nombre}" id="nombre" class="form-text" style="width:300px;" >
                    </td>
                </tr>
                <tr>
                    <td colspan="3"  class="label" >Direcci&oacute;n **</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="direccion" value="{$datacliente[0].direccion}"  id="direccion" class="form-text" style="width:300px;" >
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label">Telefonos ** </td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="telefonos" value="{$datacliente[0].telefonos}"  id="telefonos" class="form-text" style="width:300px;" >
                    </td>
                </tr>
                 <tr>
                    <td colspan="3" class="label" >CI o RIF **</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" value="{$datacliente[0].rif}" name="rif" class="form-text" style="width:300px;" id="rif" >
                        <span id="rif_error" class="error" name="rif_error"><i>Formato Inv&aacute;lido...</i></span>
                    </td>
                </tr>
                
                <!-- pdval hogar -->
                <tbody id="pdvalHogar">
                 <tr>
                    <td colspan="3" class="label" >Vendedor</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cod_vendedor" id="cod_vendedor" class="form-text" style="width:300px;">
                            {html_options values=$option_values_vendedor output=$option_output_vendedor selected=$option_selected_vendedor}
                        </select>
                    </td>
                </tr>
                  <tr>
                    <td colspan="3" class="label" >Fax</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" value="{$datacliente[0].fax}" name="fax" size="60" id="fax" class="form-text" style="width:300px;" >
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label" >E-Mail</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="email" size="60" id="email" value="{$datacliente[0].email}" class="form-text" style="width:300px;" >
                    </td>
                </tr>
                <tr>
                 <tr>
                    <td colspan="3" class="label" >Estado Cliente</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="estado" id="estado" class="form-text" style="width:300px;">
                             {html_options values=$option_values_estado output=$option_output_estado selected=$option_selected_estado}
                           
                        </select>
                    </td>
                </tr>
              
             <!--    <tr>
                    <td colspan="3" class="label" >Representante</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input class="form-text" style="width:300px;" type="text" name="representante"  id="representante" >
                    </td>
                </tr> -->

              

             <!--    <tr>
                    <td colspan="3" class="label"  >Alterna</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="altena" id="altena" class="form-text" style="width:300px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label" >Alterna 2</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="alterna2"  id="alterna2" class="form-text" style="width:300px;" >
                    </td>
                </tr>              -->  
              
              <!--   <tr>
                    <td colspan="3" class="label" >Permite Creditos</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="permitecredito" id="permitecredito" class="form-text">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </td>
                </tr> -->
             <!--    <tr>
                    <td colspan="3" class="label" >Limite</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="limite" class="validadDecimales form-text" value="0.00"  id="limite" >
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label"  >D&iacute;as</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="dias" class="validadDecimales form-text" value="0.00"  id="dias" >
                    </td>
                </tr> -->
              <!--   <tr>
                    <td colspan="3" class="label" >Tolerancia</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="tolerancia" class="validadDecimales form-text" value="0.00"  id="tolerancia" >
                    </td>
                </tr> -->
               <!--  <tr>
                    <td colspan="3" class="label" > % Descuento Parcial</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="porc_parcial"  class="validadDecimales form-text" value="0.00"  id="porc_parcial" >
                    </td>
                </tr> -->
               <tr>
                    <td colspan="3" class="label" >
                        % Descuento Global
                    </td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="porc_descuento_global"  class="validadDecimales form-text" value="{$datacliente[0].porc_descuento_global}"  id="porc_descuento_global" >
                    </td>
                </tr>
               
            <!--     <tr>
                    <td colspan="3" class="label" >Zona</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cod_zona" id="cod_zona" class="form-text" style="width:300px;">
                            {html_options values=$option_values_zona output=$option_output_zona }
                        </select>
                    </td>
                </tr> -->
               
                <tr>
                    <td colspan="3" class="label">NIT
                       <!--  {html_options values=$option_values_parametros output=$option_output_idfiscal2} -->

                    </td>
                    <td >
                        <input type="text" name="nit" value="{$datacliente[0].nit}" class="form-text" style="width:300px;" id="nit" >
                        <span id="nit_error" class="error" name="nit_error"   >Formato Invalido..</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label" >Constribuyente Especial</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="contribuyente_especial" id="contribuyente_especial" class="form-text" style="width:300px;">
                           {html_options values=$option_values_contribuyente_especial output=$option_output_contribuyente_especial selected=$option_selected_contribuyente_especial}
                        </select>
                    </td>
                </tr>
              <!--   <tr>
                    <td colspan="3" class="label" >Retencion por Cliente</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="retenido_por_cliente" class="validadDecimales form-text" value="0.00"  style="width:300px;" id="retenido_por_cliente" >
                    </td>
                </tr> -->
                <tr>
                    <td colspan="3" class="label" >Tipo de Entidad</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cod_entidad" id="cod_entidad" class="form-text" style="width:300px;">
                            {html_options values=$option_values_entidad output=$option_output_entidad selected=$option_selected_entidad}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label" >Tipo de Precio</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cod_tipo_precio" id="cod_tipo_precio" class="form-text" style="width:300px;">
                             {html_options values=$option_values_tipo_precio output=$option_output_tipo_precio selected=$option_selected_tipo_precio}
                        </select>
                    </td>
                </tr>
              <!--   <tr>
                    <td colspan="3" class="label" >Clase</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="clase" class="form-text" style="width:300px;" id="clase" >
                    </td>
                </tr>
 -->
             <!--    <tr>
                    <td colspan="3" class="label" >Tipo de Cliente</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cod_tipo_cliente" id="cod_tipo_cliente" class="form-text" style="width:300px;">
                            {html_options values=$option_values output=$option_output }
                        </select>
                    </td>
                </tr> -->
              <!--   <tr>
                    <td colspan="3" class="label" >Cuenta Contable</td>
                    <td >
                        <select name="cuenta_contable" id="cuenta_contable" class="form-text" style="width:300px;">
                            {html_options values=$option_values_cuenta output=$option_output_cuenta}
                        </select>
                    </td>
                </tr> -->
            </tbody>
            <!-- fin de pdval hogar -->
            <!-- PAE -->
            <tbody id="paeCliente">
                  <tr>
                    <td colspan="3" class="label" >Distrito Escolar**</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                         <select name="id_distrito" id="id_distrito" class="form-text" style="width:300px;">
                            {html_options values=$option_values_distrito output=$option_output_distrito selected=$selected_output_distrito }
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label" >Parroquia**</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="parroquia" size="60" id="parroquia" value="{$datacliente[0].parroquia}" class="form-text" style="width:300px;" >
                    </td>
                </tr>
                 <tr>
                    <td colspan="3" class="label" >Sub-Sistema**</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="subsistema" id="subsistema" class="form-text" style="width:300px;">
                             {html_options options=$valores_subsistema selected=$selected_subsistema }
                        </select>
                    </td>
                </tr>
                 <tr>
                    <td colspan="3" class="label" >Dependecia**</td>
                   <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="Dependencia" id="Dependencia" class="form-text" style="width:300px;">
                            {html_options options=$valores_dependencia selected=$selected_dependencia }
                            
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label" >Modalidad Ingesta**</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="modalidadIngesta" id="modalidadIngesta" class="form-text" style="width:300px;">
                             {html_options options=$valores_modalidad_ing selected=$selected_modalidad_ing }
                        </select>
                    </td>
                </tr>
                  <tr>
                    <td colspan="3" class="label" >Turno**</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="turno" id="turno" class="form-text" style="width:300px;">
                            {html_options options=$valores_turno selected=$selected_turno }
                        </select>
                    </td>
                </tr>
                  <tr>matricula
                    <td colspan="3" class="label" >Matricula**</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="matricula" size="60" id="matricula" value="{$datacliente[0].matricula}" class="form-text" style="width:300px;" >
                    </td>
                </tr>
                  <tr>
                    <td colspan="3" class="label" >Director del plantel**</td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="director" size="60" id="director" value="{$datacliente[0].director_plantel}" class="form-text" style="width:300px;" >
                    </td>
                </tr>
            </tbody>
            <!--fin de PAE -->
            </table>              
            <table style="width:100%;">
                <tbody>
                    <tr class="tb-tit" style="text-align:right;">
                        <td>
                            <input type="submit" name="aceptar" id="aceptar" value="Guardar Cambios">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>
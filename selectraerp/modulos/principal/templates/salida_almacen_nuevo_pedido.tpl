<script src="../../libs/js/event_almacen_salida.js" type="text/javascript"></script>
<script src="../../libs/js/eventos_formAlmacen.js" type="text/javascript"></script>
<script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
{literal}
    <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                //funcion para cargar los puntos 
                  $("#estado").change(function() {
                    estados = $("#estado").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getPuntos&'+'estados='+estados,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#puntodeventa").find("option").remove();
                                $("#puntodeventa").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#puntodeventa").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#puntodeventa").append("<option value='0'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#puntodeventa").append("<option value='" + this.vcampos[i].siga+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                                }
                            }
                        }); 
                        $("#puntodeventa").val(0);
                  });
            });


            function comprobarconductor() {
        var consulta;     
        consulta = $("#nacionalidad_conductor").val()+$("#cedula_conductor").val();                      
                        $.ajax({
                              type: "POST",
                              url: "comprobar_conductor.php",
                              data: "b="+consulta,
                              dataType: "html",
                              asynchronous: false, 
                              error: function(){
                                    alert("error petici�n ajax");
                              },
                              success: function(data){  

                                $("#resultado").html(data);
                                document.getElementById("conductor").focus();
                                ///// verificamos su estado

                              }
                  });

        }
    </script>
{/literal}
 <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
<form name="formulario" id="formulario" method="POST" action="gestionar_pedido_despacho.php">
    <input type="hidden" name="Datosproveedor" value="">
    <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
    <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
    <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
    <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
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

    <!--<Datos del proveedor y vendedor>-->
    <div id="dp" class="x-hide-display">
        <br>
        <table>
        <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/28.png"/-->
                    <span style="font-family:'Verdana';"><b>Nro Pedido</b></span>
                </td>
                <td>
                    <input class="form-text" type="text" maxlength="100"  size="30" name="nro_pedido" id="nro_pedido" value="{$smarty.get.cod} "readonly/>
                </td>
            </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/28.png"/-->
                    <span style="font-family:'Verdana';"><b>Cliente</b></span>
                </td>
                <td>
                    <input class="form-text" type="text" maxlength="100"  size="30" name="cliente" id="cliente" value="{$cliente_nombre} "readonly/>
                </td>
            </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/28.png"/-->
                    <span style="font-family:'Verdana';"><b>Autorizado Por (*)</b></span>
                </td>
                <td>
                    <input class="form-text" type="text" maxlength="100"  size="30" name="autorizado_por" id="autorizado_por" value="{$nombre_usuario}" readonly/>
                </td>
            </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/8.png"/-->
                    <span style="font-family:'Verdana';"><b>Observaciones</b></span>
                </td>
                <td>
                    <input class="form-text" type="text"  size="30" name="observaciones" maxlength="100" id="observaciones"/>
                </td>
            </tr>
            <!--
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/ico_user.gif"
                    <span style="font-family:'Verdana';"><b>Estado Destino:</b></span>
                </td>
                <td>
                    <select  name="estado_destino" id="estado" class="form-text" disabled="disabled">
                        <option value="9999">Todos</option>
                        {html_options output=$option_values_nombre_estado values=$option_values_id_estado}
                    </select>
                </td>
            </tr>
            -->
            {if $almacen_destino neq ''}
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/ico_user.gif"-->
                    <span style="font-family:'Verdana';"><b>Almacen de Destino:</b></span>
                </td>
                <td>
                    <select  name="puntodeventa" id="puntodeventa" class="form-text" disabled="disabled">
                        <option value="0">Todos</option> 
                        {html_options output=$option_output_punto values=$option_values_punto selected=$almacen_destino}
                    </select>
                </td>
            </tr>
            {/if}
            <tr>
                <td>
                    <span style="font-family:'Verdana';"><b>Fecha</b></span>
                </td>
                <td>
                    <input class="form-text" maxlength="100" type="text" name="input_fechacompra" id="input_fechacompra"  size="30" value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly/>
                    <!--div  style="color:#4e6a48" id="fechacompra">{$smarty.now|date_format:"%d-%m-%Y"}</div-->
                    {literal}
                        <script type="text/javascript">//<![CDATA[
                            // var cal = Calendar.setup({onSelect: function(cal) { cal.hide() }});
                            // cal.manageFields("input_fechacompra", "input_fechacompra", "%d-%m-%Y");
                        //]]></script>
                    {/literal}
                </td>
            </tr>

            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/ico_user.gif"-->
                    <span style="font-family:'Verdana';"><b>Prescintos:</b></span>
                </td>
                <td>
                    <input class="form-text" type="text" maxlength="100"  size="30" name="prescintos" id="prescintos"/>
                </td>
            </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/ico_user.gif"-->
                    <span style="font-family:'Verdana';"><b>Codigo Jornada:</b></span>
                </td>
                <td>
                    <input class="form-text" type="text" maxlength="100"  size="30" name="id_jornada" id="id_jornada"/>
                </td>
            </tr>

            <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Tipo despacho:</b></span>
                        </td>
                        <td>
                            <select name="id_tipo_despacho" id="id_tipo_despacho" class="form-text" style="width:205px">                        
                                {html_options values=$option_values_tipodespacho output=$option_output_tipodespacho}
                                
                                </select>
                        </td>
                    </tr>
            <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>C&eacute;dula del Conductor</b></span>
                        </td>
                        <td>
                            <select name="nacionalidad_conductor" id="nacionalidad_conductor" class="form-text">
                              <option value="">..</option>
                              <option value="V">V</option>
                              <option value="E">E</option>
                            </select>
                            <input type="text" name="cedula_conductor" maxlength="8" id="cedula_conductor" size="21"  class="form-text" onBlur="comprobarconductor(this.id)" onKeyPress="return soloNumeros(event)"/>
                        </td>
                    </tr>
                    <tr>
                    <td style="font-family:'Verdana';font-weight:bold;">
                    <span style="font-family:Verdana"><b>Nombre del Conductor</b></span>
                    </td>
                    <td>
                    <div id="resultado" style="font-family:'Verdana';font-weight:bold;">
                    
                    </div>
                    </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Placa</b></span>
                        </td>
                        <td>
                            <input type="text" name="placa" maxlength="100" id="placa" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Marca</b></span>
                        </td>
                        <td>
                            <input type="text" name="marca" maxlength="100" id="marca" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Color</b></span>
                        </td>
                        <td>
                            <input type="text" name="color" maxlength="100" id="color" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><span style="font-family:'Verdana';font-weight:bold;"><b>CASILLA DE FIRMAS:</b></span></td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Aprobado Por:</b></span>
                        </td>
                        <td>
                            <select name="id_aprobado" id="id_aprobado" class="form-text" style="width:205px">                        
                                {html_options values=$option_values_aprobado output=$option_output_aprobado}
                                
                                </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Despachador:</b></span>
                        </td>
                        <td>
                            <select name="id_despachador" id="id_despachador" class="form-text" style="width:205px">                        
                                {html_options values=$option_values_receptor output=$option_output_receptor}
                                
                                </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Seguridad:</b></span>
                        </td>
                        <td>
                           <select name="id_seguridad" id="id_seguridad" class="form-text" style="width:205px">                        
                                {html_options values=$option_values_seguridad output=$option_output_seguridad}
                                
                                </select>
                        </td>
                    </tr>
                    <tr>
                    {if $estado_pedido eq 'Facturado' or $estado_pedido eq 'Pendiente'}
                    <td>                    
                    <input type="submit" name="aceptar" value="Gestionar Despacho" onclick="return confirm('¿Gestionar Despacho?');">
                    </td>
                    {if $anular eq '1' and $estado_pedido neq 'Facturado' and $observacion_kardex neq 'Pedido Anulado'}
                    <td>
                    <input type="submit" name="anular" value="Anular Pedido" onclick="return confirm('¿Seguro Desea Anular este Pedido?');">
                    {/if}
                    {/if}
                    </td>
                    </tr>

        </table>

    </div>
    <!--</Datos del proveedor y vendedor>-->


    <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items">
    <input type="hidden" title="input_tiva" value="0" name="input_tiva" id="input_tiva">
    <input type="hidden" title="input_tsiniva" value="0" name="input_tsiniva" id="input_tsiniva">
    <input type="hidden" title="input_tciniva" value="0" name="input_tciniva" id="input_tciniva">

</form>



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
<form name="formulario" id="formulario" method="POST" >
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
                    
                <td colspan="2" align="center"> 
                    <br><input type="submit" name="aceptar" value="Agregar Tipo Despacho" onclick="return confirm('¿Agregar Tipo Despacho?');">
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



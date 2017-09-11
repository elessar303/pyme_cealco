<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada_calidad.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formAlmacen_calidad.js"></script>
         <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
        {literal}
        <script language="JavaScript" type="text/JavaScript">
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
                                    alert("error petición ajax");
                              },
                              success: function(data){  

                                $("#resultado").html(data);
                                document.getElementById("conductor").focus();
                                ///// verificamos su estado

                              }
                  });

        }



        function soloNumeros(e){
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 57)
        }

        $(document).ready(function(){
        //funcion para cargar los puntos 

            $("#cantidadunitaria").keyup(function()
            {
                    $("#ext-gen71").attr("disabled", "disabled");
            });
            $("#cantidadunitaria").blur(function()
            {
                
                $("#ext-gen71").attr("disabled", "disabled");
                id_item=$("#items").val();
                cantidadunitaria=$("#cantidadunitaria").val();
                cantidadCombo=$("#cantidad").val();

                $.ajax({
                            type: 'GET',
                            data: 'opt=GetDisponibleClap&idItem='+id_item+'&cantidadCombo='+cantidadCombo+'&cantidadunitaria='+cantidadunitaria,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() 
                            {
                                $("#puntodeventa").find("option").remove();
                                $("#puntodeventa").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) 
                            {
                                if(data==1)
                                {
                                     $("#ext-gen71").attr("disabled", false);
                                }
                                if(data==0)
                                {
                                    $("#cantidadunitaria").val('');
                                    alert("No existe cantidad suficiente para este producto");
                                }
                                else
                                {
                                    if(data==3)
                                    {
                                        $("#cantidadunitaria").val('');
                                        alert("Solo Se aceptan Cantidades Positivas");
                                    }
                                    else
                                    {
                                        $("#ext-gen71").attr("disabled", false);
                                    }
                                }
                                
                            }


                        }); 
                $("#ext-gen71").attr("disabled", false);
            });
                  
        });

        </script>
        {/literal}
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        {literal}
        <style type="text/css">
        .invisible
        {
        display: none;
        }
        </style>
        {/literal}
   
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="Datosproveedor" value=""/>
            <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
            <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
            <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
            <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
            <table style="width:100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:900px;">
                                        <span style="float:left;">
                                            <img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon"/>
                                            {$subseccion[0].descripcion}
                                        </span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';">
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
            <!--<Datos del proveedor y vendedor>-->
            <div id="dp" class="x-hide-display">
                <br/>
                <table>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Autorizado Por (*)</b></span>
                        </td>
                        <td>
                            <!--input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="{$detalles_pendiente[0].autorizado_por}"/-->
                            <input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="{$nombre_usuario}" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Nombre (*)</b></span>
                        </td>
                        <td>
                            <!--input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="{$detalles_pendiente[0].autorizado_por}"/-->
                             <input type="text" name="nombre" maxlength="100" id="nombre" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Cantidad Cajas(*)</b></span>
                        </td>
                        <td>
                            <input type="numeric" name="cantidad" maxlength="100" id="cantidad" size="30" maxlength="70" class="form-text"/>
                        </td>
                    </tr>
                    
                </table>
            </div>
            <!--</Datos del proveedor y vendedor>-->
            <div id="dcompra" class="x-hide-display"></div>
            <div id="PanelGeneralCompra">
                <div id="tabproducto" class="x-hide-display">
                    <div id="contenedorTAB">
                        <div id="div_tab1">
                            <div class="grid">
                                <table class="lista" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="tb-tit" style="text-align: center;">C&oacute;digo</th>
                                            <th class="tb-tit" style="text-align: center;">Descripci&oacute;n</th>
                                            <th class="tb-tit" style="text-align: center;">Cantidad</th>
                                            <th class="tb-tit" style="text-align: center;">Opci&oacute;n</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {if $cod neq ""}
                                            {foreach from=$productos_pendientes_entrada key=i item=prod}
                                                <tr>
                                                    <td style="text-align: left; padding-left: 20px;">{$prod.codigo_barras}</td><!--id_item-->
                                                    <td style="text-align: left; padding-left: 20px;">{$prod.descripcion1}</td>
                                                    <td style="text-align: right; padding-right: 20px;">{$prod.cantidad}</td>
                                                    <td></td>
                                                </tr>
                                            {/foreach}
                                        {/if}
                                    </tbody>
                                    <tfoot>
                                        <tr class="sf_admin_row_1">
                                            <td colspan="4">
                                                <div class="span_cantidad_items">
                                                    <span style="font-size: 12px; font-style: italic; text-align: left;">Cantidad de Items: 0</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tabpago" class="x-hide-display">
                    <div id="contenedorTAB21">
                        <!-- TAB1 -->
                        <div class="tabpanel2">
                            <table></table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items"/>
            <div id="displaytotal" class="x-hide-display"></div>
            <div id="displaytotal2" class="x-hide-display"></div>
        </form>
        <div id="incluirproducto" class="x-hide-display">
          
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra">
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>

            <p>
                <label><b>Productos</b></label><br/>
                <input type="hidden" name="items" id="items">
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly>
                <!--<select style="width:100%" id="items" name="items" onchange="comprobarfechavencimiento(this.id)"></select>-->
            </p>
            
            <p>
                <label><b>Cantidad Unitaria</b></label><br/>
                <input type="text" name="cantidadunitaria" id="cantidadunitaria"/>
            </p>
         
        </div>
    </body>
</html>
<?php /* Smarty version 2.6.21, created on 2017-08-18 01:05:00
         compiled from transformacion_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'transformacion_add.tpl', 240, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada_calidad.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formAlmacen_calidad.js"></script>
         <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
        
        $(document).ready(function()
        {
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
                cantidadCombo=1;//$("#cantidad").val();

                $.ajax({
                            type: \'GET\',
                            data: \'opt=GetDisponibleClap&idItem=\'+id_item+\'&cantidadCombo=\'+cantidadCombo+\'&cantidadunitaria=\'+cantidadunitaria,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() 
                            {
                                $("#puntodeventa").find("option").remove();
                                $("#puntodeventa").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) 
                            {
                                if(data==1)
                                {
                                     $("#ext-gen71").attr("disabled", false);
                                }
                                if(data==0)
                                {
                                    $("#cantidadunitaria").val(\'\');
                                    alert("No existe cantidad suficiente para este producto");
                                }
                                else
                                {
                                    if(data==3)
                                    {
                                        $("#cantidadunitaria").val(\'\');
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

        function verificarunico(valor)
        {
            dato=document.getElementsByName("rubro[]");
            i=0;
            $.each(dato, function (id_item, descripcion1) 
            { 
                if(valor.value==descripcion1.value)
                {
                    i++;
                }
                //si se repite dos veces quiere decir que ya esta seleccionado
                if(i==2)
                {
                    alert("Ya Seleccione El producto.");
                    valor.selectedIndex = 0;
                    return false;
                }

            }); 
        }

        function verificar()
        {
            
            if($("#rubro1").val()!="" && $("#cantidad1").val()!="")
            {
                document.getElementById("boton_agregar1").style.visibility = "visible";
            }
            else
            {
                document.getElementById("boton_agregar1").style.visibility = "hidden";   
            }
        }

        function agregarProducto()
        {
            //ajax para insertar
            cantidad=parseInt($("#cantidad_dinamica").val());
            rubro=$("#rubro1").val();
            rubro_nombre=$("#rubro1 option:selected").text();
            cantidades=$("#cantidad1").val();
            producto=document.getElementsByName("rubro[]");
            nombre=document.getElementsByName("nombre[]");
            cantidadvalores=document.getElementsByName("cantidad[]");
            arregloproducto=[];
            arreglocantidad=[];
            arreglonombre=[];
            for(var i = 0; i < nombre.length; i++)
            {
                if(typeof nombre[i].value!="undefined")
                {
                    arreglonombre.push(nombre[i].value);
                    
                }
            }
            for(var i = 0; i < producto.length; i++)
            {
                if(typeof producto[i].value!="undefined")
                {
                    arregloproducto.push(producto[i].value);
                    
                }
            }
            for(var i = 0; i < cantidadvalores.length; i++)
            {
                if(typeof cantidadvalores[i].value!="undefined")
                {
                    arreglocantidad.push(cantidadvalores[i].value);
                    
                }
            }

            cantidad+=1;
            $("#resultado").empty();
            $.ajax({
                type: \'POST\',
                data: \'opt=transformacionproductos&cantidad=\'+cantidad+\'&arreglocantidad=\'+arreglocantidad+\'&arregloproducto=\'+arregloproducto+\'&rubro=\'+rubro+\'&cantidades=\'+cantidades+\'&rubro_nombre=\'+rubro_nombre+\'&arreglonombre=\'+arreglonombre,
                    url: \'../../libs/php/ajax/ajax.php\',
                beforeSend: function()
                {
                    $("#resultado").empty();
                    $("#resultado").append("<option value=\'\'>Cargando..</option>");
                },
                success: function(data) 
                {
                    $("#resultado").empty();
                    $("#resultado").html(data);
                    $("#cantidad_dinamica").val(cantidad);
                    document.getElementById("boton_agregar1").style.visibility = "hidden";
                }
            });
        };

        </script>
        '; ?>

        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        <?php echo '
        <style type="text/css">
        .invisible
        {
        display: none;
        }
        </style>
        '; ?>

   
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <input type="hidden" name="Datosproveedor" value=""/>
            <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
            <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
            <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
            <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
            <input type="hidden" name="cantidad_dinamica" value="1" id="cantidad_dinamica"/>
            <table style="width:100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:900px;">
                                        <span style="float:left;">
                                            <img src="<?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon"/>
                                            <?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>

                                        </span>
                                    </td>
                                    <td style="width:75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';">
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
                <table border="0">
                    <tr>
                        <td align="left" width="80px">
                            <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Autorizado Por (*)</b></span>
                        </td>
                        <td align="left" width="560px">
                            <!--input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="<?php echo $this->_tpl_vars['detalles_pendiente'][0]['autorizado_por']; ?>
"/-->
                            <input type="text" maxlength="100" name="autorizado_por" id="autorizado_por" value="<?php echo $this->_tpl_vars['nombre_usuario']; ?>
" size="30" maxlength="70" class="form-text" readonly="readonly"/>
                        </td>
                        

                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="resultado">
                                <table>
                                <tr>
                                    <td>
                                        <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                                        <span style="font-family:'Verdana';font-weight:bold;"><b>Producto (*)</b></span>
                                    </td>
                                    <td>
                                        <select name="rubro1" id="rubro1" class="form-text" onChange='verificar()'>
                                            <option value="">Seleccione una opcion</option>
                                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_rubro'],'output' => $this->_tpl_vars['option_output_rubro']), $this);?>

                                            
                                        </select>
                                        <input type="hidden" name="rubro[]" id="rubro[]" />
                                        <input type="hidden" name="cantidad[]" id="cantidad[]"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <!--img align="absmiddle" width="17" height="17" src="../../../includes/imagenes/28.png"-->
                                        <span style="font-family:'Verdana';font-weight:bold;"><b>Cantidad (*)</b></span>
                                    </td>
                                    <td>
                                        <input type="numeric" name="cantidad1" maxlength="100" id="cantidad1" size="30" maxlength="70" class="form-text" value="" onkeyup='verificar()'/>
                                    </td>
                                </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <div id="boton_agregar" > 
                        <table class="btn_bg" onclick="agregarProducto()" align="center" border="0" style="visibility: hidden;" id="boton_agregar1">
                            <tr style="border-width: 0px; cursor: pointer;">
                                <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" />
                                </td>
                                <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" />
                                </td>
                                <td style="padding: 0px 6px;">Agregar Otro Producto
                                </td>
                                <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    
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
                                        <?php if ($this->_tpl_vars['cod'] != ""): ?>
                                            <?php $_from = $this->_tpl_vars['productos_pendientes_entrada']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['prod']):
?>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 20px;"><?php echo $this->_tpl_vars['prod']['codigo_barras']; ?>
</td><!--id_item-->
                                                    <td style="text-align: left; padding-left: 20px;"><?php echo $this->_tpl_vars['prod']['descripcion1']; ?>
</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo $this->_tpl_vars['prod']['cantidad']; ?>
</td>
                                                    <td></td>
                                                </tr>
                                            <?php endforeach; endif; unset($_from); ?>
                                        <?php endif; ?>
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
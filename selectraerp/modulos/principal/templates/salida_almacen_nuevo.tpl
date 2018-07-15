<script src="../../libs/js/event_almacen_salida.js" type="text/javascript"></script>
<script src="../../libs/js/eventos_formAlmacen.js" type="text/javascript"></script>
<script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
{literal}
    <script type="text/javascript">//<![CDATA[
    $(document).ready(function()
    {

            //Funcion para cargar los datos del predespacho
            $("#id_predespacho").change(function() {
                cargarPredespacho()
            });
            //funcion para cargar los puntos 
            $("#estado").change(function() 
            {
                estados = $("#estado").val();
                $.ajax(
                {
                    type: 'GET',
                    data: 'opt=getPuntos&'+'estados='+estados,
                    url: '../../libs/php/ajax/ajax.php',
                    beforeSend: function() 
                    {
                        $("#puntodeventa").find("option").remove();
                        $("#puntodeventa").append("<option value=''>Cargando..</option>");
                    },
                    success: function(data) 
                    {
                        $("#puntodeventa").find("option").remove();
                        this.vcampos = eval(data);
                        $("#puntodeventa").append("<option value='0'>Todos</option>");
                        for (i = 0; i <= this.vcampos.length; i++) 
                        {
                            $("#puntodeventa").append("<option value='" + this.vcampos[i].siga+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                        }
                    }
                });
                $("#puntodeventa").val(0);
            });
            //se habilita ventana modal para solicitar clave secreta (si esta activa)
            $.ajax(
            {
                type: 'GET',
                data: 'opt=getCodigoKardex&',
                url: '../../libs/php/ajax/ajax.php',
                success: function(data) 
                {
                    if(data==1)
                    {
                        var ventana = document.getElementById('miVentana');
                        ventana.style.marginTop = '100px';
                        ventana.style.left = ((document.body.clientWidth-350) / 2) +  'px';
                        ventana.style.display = 'block';
                        document.getElementById("autorizado_por").disabled=true;
                        document.getElementById("observaciones").disabled=true;
                        document.getElementById("prescintos").disabled=true;
                        document.getElementById("cedula_conductor").disabled=true;
                        document.getElementById("nacionalidad_conductor").disabled=true;
                        document.getElementById("marca").disabled=true;
                        document.getElementById("color").disabled=true;
                        document.getElementById("placa").disabled=true;
                    }
                }
            }); 
        });

        //función que verifica el codigo seleccionado
        function procesarCodigo()
        {   
            var codigo = $("#codigo_seguridad").val();
            $.ajax(
            {
                type: 'GET',
                data: 'opt=getverificacionCodigo&'+'codigo='+codigo,
                url: '../../libs/php/ajax/ajax.php',
                success: function(data) 
                {
                    if(data==1)
                    {
                        document.getElementById('miVentana').style.display='none';
                        $("#codigo_kardex").val(codigo);
                        document.getElementById("autorizado_por").disabled=false;
                        document.getElementById("observaciones").disabled=false;
                        document.getElementById("prescintos").disabled=false;
                        document.getElementById("cedula_conductor").disabled=false;
                        document.getElementById("nacionalidad_conductor").disabled=false;
                        document.getElementById("marca").disabled=false;
                        document.getElementById("color").disabled=false;
                        document.getElementById("placa").disabled=false;
                    }
                    else
                    {
                        if(data==-4)
                        {
                            alert("Clave Vencida");
                            return false;
                        }
                        if(data==-1)
                        {
                            alert("Codigo ya se uso");
                            return false;
                        }
                        if(data==-2)
                        {
                            alert("No puede dejar el codigo vacio");
                            return false;
                        }
                        if(data==-3)
                        {
                            alert("Este Codigo no Corresponde a la tienda");
                            return false;
                        }
                        if(data!=-4 && data!=-1 && data!=-2 && data!=-3)
                        {
                            alert("Error, Verifique el formato de la clave");
                            return false;
                        }
                    }
                }
            }); 

        }
        //fin del verificar codigo

        function cargarPredespacho(){
            id_predespacho = $("#id_predespacho").val();
            $.ajax(
            {
                type: 'GET',
                data: 'opt=getPredespacho&'+'predespacho='+id_predespacho,
                url: '../../libs/php/ajax/ajax.php',
                success: function(data) 
                {
                    var datos = eval(data);
                    $("#prescintos").val(datos[0].prescintos);
                    $("#cliente").val(datos[0].id_proveedor);
                    $("#observaciones").val(datos[0].observacion);
                    $("#id_ticket").val(datos[0].id_ticket_entrada);
                    cargarDetallePredepacho();

                }
            });
        }

        function cargarDetallePredepacho(){
            id_predespacho = $("#id_predespacho").val();
            $.ajax(
            {
                type: 'GET',
                data: 'opt=getPredespachoDetalle&'+'predespacho='+id_predespacho,
                url: '../../libs/php/ajax/ajax.php',
                success: function(data) 
                {
                    $("#resultado").html(data);
                }
            });
        }

        function comprobarconductor() 
        {
            var consulta;     
            consulta = $("#nacionalidad_conductor").val()+$("#cedula_conductor").val();                      
            $.ajax(
            {
              type: "POST",
              url: "comprobar_conductor.php",
              data: "b="+consulta,
              dataType: "html",
              asynchronous: false, 
              error: function()
              {
                alert("error petici�n ajax");
            },
            success: function(data)
            {
                $("#resultado").html(data);
                document.getElementById("conductor").focus();
                
            }
        });
        }
    </script>
    {/literal}
    <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
    <form name="formulario" id="formulario" method="POST" action="">
        <input type="hidden" name="Datosproveedor" value="">
        <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
        <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
        <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
        <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
        <input type="hidden" name="pesooculto" id="pesooculto" value="0">
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
                            <span style="font-family:'Verdana';"><b>Elaborado Por (*):</b></span>
                        </td>
                        <td>
                            <input class="form-text" type="text" maxlength="100"  size="30" name="autorizado_por" id="autorizado_por" value="{$nombre_usuario}" readonly/>
                            <input class="form-text" type="hidden" maxlength="100"  size="30" name="codigo_kardex" id="codigo_kardex"/>
                        </td>
                        <td rowspan="13"><div id="resultado"></div></td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';"><b>Observaciones:</b></span>
                        </td>
                        <td>
                            <input class="form-text" type="text"  size="30" name="observaciones" maxlength="100" id="observaciones"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';"><b>Seleccionar Pre Despacho (*):</b></span>
                        </td>
                        <td>
                            <select  name="id_predespacho" id="id_predespacho" class="form-text">
                                <option value="">Seleccione...</option>
                                {html_options output=$option_output_despachos values=$option_values_despachos}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';"><b>Cliente (*):</b></span>
                        </td>
                        <td>
                            <select  name="cliente" id="cliente" class="form-text">
                                <option value="">Seleccione...</option>
                                {html_options output=$option_output_nombre_cliente values=$option_values_id_cliente}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-family:'Verdana';"><b>Fecha:</b></span>
                        </td>
                        <td>
                            <input class="form-text" maxlength="100" type="text" name="input_fechacompra" id="input_fechacompra"  size="30" value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly/>
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
                            <span style="font-family:'Verdana';"><b>Prescintos:</b></span>
                        </td>
                        <td>
                            <input class="form-text" type="text" maxlength="100"  size="30" name="prescintos" id="prescintos"/>
                        </td>
                    </tr>
                    <tr style="display: none">
                        <td>
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Ticket Salida</b></span>
                        </td>
                        <!-- PUNTOS -->
                        <td>
                            <input type="text" name="id_ticket" id="id_ticket" value="">
                        </td>                     
                    </tr>
                    <!-- Firmas Casillas-->
                    <tr>
                        <td colspan="2" align="center"><span style="font-family:'Verdana';font-weight:bold;"><b>CASILLA DE FIRMAS:</b></span></td>
                    </tr>
                    <tr>
                        <td>
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
                            <span style="font-family:'Verdana';font-weight:bold;"><b>Seguridad:</b></span>
                        </td>
                        <td>
                         <select name="id_seguridad" id="id_seguridad" class="form-text" style="width:205px">                        
                            {html_options values=$option_values_seguridad output=$option_output_seguridad}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table>
                            <thead>
                                <tr>
                                    <th class="tb-head"><b>Servicios Asociados Al Movimiento</b></th>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        {foreach key=key item=servicios from=$checkbox}

                        <label><input type="checkbox" id="{$servicios.id}" name='cajas[]' value="{$servicios.id}" checked="checked"/>{$servicios.nombre}</label>&nbsp;

                        {/foreach}
                    </td>
                </tr>
            </table>
        </div>
        <!--</Datos del proveedor y vendedor>-->
        <div  id="dcompra" class="x-hide-display" >
        </div>
        <div id="PanelGeneralCompra">
            <div id="tabproducto" class="x-hide-display">
                <div id="contenedorTAB">
                    <div id="div_tab1">
                        <div class="grid">
                            <table width="100%" class="lista">
                                <thead>
                                    <tr >
                                        <th class="tb-tit">Codigo</th>
                                        <th class="tb-tit">Descripcion</th>
                                        <th class="tb-tit">Cantidad</th>
                                        <th class="tb-tit">Peso</th>
                                        <th class="tb-tit">Opt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr class="sf_admin_row_1">
                                        <td colspan="4">
                                            <div class="span_cantidad_items"><span style="font-size: 10px;">Cantidad de Items: 0</span></div>
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
                        <table>
                        </table>

                    </div>
                </div>
            </div>
        </div>



        <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items">
        <input type="hidden" title="input_tiva" value="0" name="input_tiva" id="input_tiva">
        <input type="hidden" title="input_tsiniva" value="0" name="input_tsiniva" id="input_tsiniva">
        <input type="hidden" title="input_tciniva" value="0" name="input_tciniva" id="input_tciniva">

        <div id="displaytotal"  class="x-hide-display"></div>
        <div id="displaytotal2"  class="x-hide-display"></div>

    </form>


    <div id="incluirproducto" class="x-hide-display">
        <label>
            <p><b>Almacen</b></p>
            <p><select id="almacen" name="almacen"></select></p>
        </label>
        <label>
            <p><b>Ubicacion</b></p>
            <p><select id="ubicacion" name="ubicacion"></select></p>
        </label>
        <p>
            <label><b>Codigo de barra</b></label><br/>
            <input type="text" name="codigoBarra" id="codigoBarra">
            <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
        </p>
        <label>
            <p><b>Productos</b></p>
            <input type="hidden" name="items" id="items">
            <input type="hidden" name="marca" id="marca">
            <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly>
            <!--<p><select style="width:100%" id="items" name="items"></select></p>-->
        </label>
        <label>
            <p><b>Lote</b></p>
            <p><input type="text" name="nlote" id="nlote" onkeypress="return solonumeros(event)"></p>
        </label>
        <label>
            <p><b>Cantidad Unitaria</b></p>
            <p><input type="text" name="cantidadunitaria" id="cantidadunitaria"></p>
        </label>
        <label>
            <p><b>Cantidad Existente en la Ubicacion</b></p>
            <p><input type="text" name="cantidad_existente" id="cantidad_existente" readonly ></p>
        </label>
        <label>
            <p><b>Peso</b></p>
            <p><input type="text" name="peso" id="peso"></p>
        </label>
        <label>
            <p><b>Peso Existente en la Ubicacion</b></p>
            <p><input type="text" name="peso_existente" id="peso_existente" readonly ></p>
        </label>

    </div>

    <div id='miVentana' style='position: fixed; width: 350px; height: 190px; top: 0; left: 0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; border: #333333 3px solid; background-color: #FAFAFA; color: #000000; display:none;  -moz-opacity:0.8; -webkit-opacity:0.8; -o-opacity:0.9; -ms-opacity:0.9; background-color: #808080; overflow: auto; width: 500px; background: #fff; padding: 30px; -moz-border-radius: 7px; border-radius: 7px; -webkit-box-shadow: 0 3px 20px rgba(0,0,0,1); -moz-box-shadow: 0 3px 20px rgba(0,0,0,1); box-shadow: 0 3px 20px rgba(0,0,0,1); background: -moz-linear-gradient(#fff, #ccc); background: -webkit-gradient(linear, right bottom, right top, color-stop(1, rgb(255,255,255)), color-stop(0.57, rgb(230,230,230)));  
    '>
    <h1>Agregue el Codigo de seguridad asignado para la salida</h1>
    <table border="0"  align="center" width="200px">
        <tr>
            <td align="center" >
                <b>Codigo</b>
            </td>
            <td>
                <input type="text" name="codigo_seguridad" id="codigo_seguridad" class='form-text' />
            </td>
        </tr>
    </table>
    <p>
        <input align="center" type="submit" value="Procesar" style="  margin-right: 220px;" onclick="procesarCodigo()" />
    </p>
</div>

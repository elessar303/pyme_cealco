<script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>

<link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" /> 
{literal}
<script>
$(document).ready(function() {
    function cargarUbicaciones() {
        idAlmacen = $("#almacen_entrada").val();
        if($("#cliente").val()!=null)
        {
            cliente = $("#cliente").val();
        }
        else
        {
            cliente=$("#id_proveedor").val();
        }
        
        array=[];
        //eliminar_serial
        $("input[name='_id_ubicacion_entrada[]']").each(function(indice, elemento) 
        {
            //console.log('El elemento con el índice '+indice+' contiene '+$(elemento).val());
            array.push($(elemento).val());
        });
        
        /*
        /Modificado el 7-02-2019 wwjimenez
        //debemos mandar el array con las ubicaciones que no deben salir
        */
        
        $.ajax({
            type: 'POST',
            data: 'opt=cargaUbicacionTraslado&idAlmacen=' + idAlmacen+ '&cliente='+ cliente+"&ubicacionesQuitar="+array,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#ubicacion_entrada").find("option").remove();
                $("#ubicacion_entrada").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#ubicacion_entrada").find("option").remove();
                this.vcampos = eval(data);
                $("#ubicacion_entrada").append("<option value=''>Seleccione..</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#ubicacion_entrada").append("<option value='" + this.vcampos[i].id + "'>" + this.vcampos[i].descripcion +  ' - ' + this.vcampos[i].orden + "</option>");
                }
            }
        });
    }
    
    
    function cargarsalidaalmacen() 
    {
      
        if($("#cliente").val()!=null)
        {
            cliente = $("#cliente").val();
        }
        else
        {
            cliente=$("#id_proveedor").val();
        }
        
        $.ajax({
            type: 'GET',
            data: 'opt=getAlmacen&cliente='+cliente,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#almacen_salida").find("option").remove();
                $("#almacen_salida").append("<option value=''>Cargando..</option>");
            },
            success: function(data) 
            {
                $("#almacen_salida").find("option").remove();
                this.vcampos = eval(data);
                     $("#almacen_salida").append("<option value='0'>Seleccione...</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#almacen_salida").append("<option value='" + this.vcampos[i].cod_almacen + "'>" + this.vcampos[i].descripcion + "</option>");
                }
                
                
            }
        });
    }
    //funcion para cargar la salida disponible del cliente.
    function cargarsalidaalmacend() 
    {
        if($("#cliente").val()!=null)
        {
            cliente = $("#cliente").val();
        }
        else
        {
            cliente=$("#id_proveedor").val();
        }
        
        $.ajax({
            type: 'GET',
            data: 'opt=getAlmacend&cliente='+cliente,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#almacen_salida_disponible").find("option").remove();
                $("#almacen_salida_disponible").append("<option value=''>Cargando..</option>");
            },
            success: function(data) 
            {
                $("#almacen_salida_disponible").find("option").remove();
                this.vcampos = eval(data);
                     $("#almacen_salida_disponible").append("<option value='0'>Seleccione...</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#almacen_salida_disponible").append("<option value='" + this.vcampos[i].cod_almacen + "'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
    }
    
    //funcion para cargar la salida disponible del cliente.
    function cargardestinoalmacen() 
    {
        if($("#cliente").val()!=null)
        {
            cliente = $("#cliente").val();
        }
        else
        {
            cliente=$("#id_proveedor").val();
        }
        
        $.ajax({
            type: 'GET',
            data: 'opt=getAlmacendestino&cliente='+cliente,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#almacen_entrada_disponible").find("option").remove();
                $("#almacen_entrada_disponible").append("<option value=''>Cargando..</option>");
            },
            success: function(data) 
            {
                $("#almacen_entrada_disponible").find("option").remove();
                this.vcampos = eval(data);
                     $("#almacen_entrada_disponible").append("<option value='0'>Seleccione...</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#almacen_entrada_disponible").append("<option value='" + this.vcampos[i].cod_almacen + "'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
    }
    //funcion para buscar el combo dependiente
    function listarubicacionesd(idalmacen, tipoSql, idubicacion)
    {
        cliente=$("#id_proveedor").val();
        var paramentros="opt=cargaUbicacionNuevodorigen&idUbicacion="+idubicacion+"&tipoSql="+tipoSql+"&cliente="+cliente;
        $.ajax({
            type: "POST",
            url: "../../libs/php/ajax/ajax.php",
            data: paramentros,
            beforeSend: function(datos){
                $("#"+idalmacen).html('<option value = 0> Cargando... </option>');
            },
            success: function(datos)
            {
            
                $("#"+idalmacen).html(datos);
                
            },
            error: function(datos,falla, otroobj){
                $("#"+idSelect).html('<option value = 0> Error... </option>');
            }
        });
    };
    
    //funcion para buscar el combo dependiente
    function listarubicacionesdestino(idalmacen, tipoSql, idubicacion)
    {
        cliente=$("#id_proveedor").val();
        array=[];
        //eliminar_serial
        $("input[name='_id_ubicacion_entrada[]']").each(function(indice, elemento) 
        {
            //console.log('El elemento con el índice '+indice+' contiene '+$(elemento).val());
            array.push($(elemento).val());
        });
        
        /*
        /Modificado el 7-02-2019 wwjimenez
        //debemos mandar el array con las ubicaciones que no deben salir
        */
        
        var paramentros="opt=cargaUbicacionNuevodestino&idUbicacion="+idubicacion+"&tipoSql="+tipoSql+"&cliente="+cliente+"&ubicacionesQuitar="+array;
        $.ajax({
            type: "POST",
            url: "../../libs/php/ajax/ajax.php",
            data: paramentros,
            beforeSend: function(datos){
                $("#"+idalmacen).html('<option value = 0> Cargando... </option>');
            },
            success: function(datos){
                $("#"+idalmacen).append("<option value='0'>Seleccione...</option>");
                $("#"+idalmacen).html(datos);
            },
            error: function(datos,falla, otroobj){
                $("#"+idSelect).html('<option value = 0> Error... </option>');
            }
        });
    };
    function cargarsalidaubicaciones() {
        idAlmacen = $("#almacen_salida").val();
        cliente = $("#id_proveedor").val();
        
        $.ajax({
            type: 'POST',
            data: 'opt=cargaUbicacionClienteDisposicion&idAlmacen=' + idAlmacen + '&cliente=' + cliente,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#ubicacion_salida").find("option").remove();
                $("#ubicacion_salida").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#ubicacion_salida").find("option").remove();
                this.vcampos = eval(data);
                $("#ubicacion_salida").append("<option value=''>Seleccione..</option>");
                for (i = 0; i < this.vcampos.length; i++) {
                    $("#ubicacion_salida").append("<option value='" + this.vcampos[i].id + "'>" + this.vcampos[i].descripcion + ' - ' +  this.vcampos[i].orden +  "</option>");
                }
            }
        });
    }
    //evento para el almacen destino
    $("#almacen_entrada").change(function() 
    {
        document.getElementById("almacen_entrada_disponible").disabled=true;
        if($("#almacen_entrada").val()==0)
        {
            document.getElementById("almacen_entrada_disponible").disabled=false;
        }
        cargarUbicaciones();
    });
    //evento para el almacen destino disponible
    $("#almacen_entrada_disponible").change(function() 
    {
        document.getElementById("almacen_entrada").disabled=true;
        if($("#almacen_entrada_disponible").val()==0)
        {
            document.getElementById("almacen_entrada").disabled=false;
        }
        idAlmacen = $("#almacen_entrada_disponible").val();
        listarubicacionesdestino("ubicacion_entrada_disponible", 0, idAlmacen);
    });
    
    $("#id_proveedor").change(function() 
    {
        cargarsalidaalmacen();
        cargarsalidaalmacend();
        cargardestinoalmacen();
    });
    //evento de cambio en el combo de almacen origen
    $("#almacen_salida").change(function() 
    {
        cargarsalidaubicaciones();
        document.getElementById("almacen_salida_disponible").disabled=true;
        if($("#almacen_salida").val()==0)
        {
            document.getElementById("almacen_salida_disponible").disabled=false;
        }
    });
    //evento del cambio en el combo almacen origen disponible
    $("#almacen_salida_disponible").change(function() 
    {
        
        document.getElementById("almacen_salida").disabled=true;
        if($("#almacen_salida_disponible").val()==0)
        {
            document.getElementById("almacen_salida").disabled=false;
        }
        idAlmacen = $("#almacen_salida_disponible").val();
        listarubicacionesd("ubicacion_salida_disponible", 0, idAlmacen);
    });
    

    

});

function solonumeros(evt) 
{
    // Backspace = 8, Enter = 13, ’0' = 48, ’9' = 57, ‘.’ = 46
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros
    patron = /[0-9-.]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
</script>
{/literal}
<script type="text/javascript" src="../../libs/js/event_almacen_traslado2.js"></script>
<script type="text/javascript" src="../../libs/js/eventos_formAlmacentraslado2.js"></script>
<form name="formulario" id="formulario" method="post" action="">
    <input type="hidden" name="pesooculto" id="pesooculto" value="0">
    <input type="hidden" name="Datosproveedor" value="">
    <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
    <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
    <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
    <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">
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
                                        <td style="padding: 0px;" align="left"><img src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
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
                    <input type="text" maxlength="70" name=" autorizado_por" id="autorizado_por" value="{$nombre_usuario}" class="form-text" readonly>
                </td>
            
            
                <td>
                    <span style="font-family:'Verdana';font-weight:bold;"><b>Cliente (*):</b></span>
                </td>
                <td>
                    <select name="id_proveedor" id="id_proveedor" class="form-text" style="width:205px">
                        <option value="">...</option>
                        {html_options values=$option_values_proveedor output=$option_output_proveedor}
                    </select>
                </td>
            
            
                <td>
                    <span style="font-family:'Verdana';"><b>Observaciones</b></span>
                </td>
                <td>
                    <input type="text" name="observaciones" maxlength="70" id="observaciones" class="form-text">
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-family:'Verdana';"><b>Fecha:</b></span>
                </td>
                <td>
                    <input type="hidden" name="input_fechacompra" id="input_fechacompra" value='{$smarty.now|date_format:"%Y-%m-%d"}'>
                    <div class="form-text" style="color:#4e6a48" id="fechacompra">{$smarty.now|date_format:"%d-%m-%Y"}</div>
                </td>
            </tr>
            <tr align="center">
                <th colspan="6" style= "text-align: center;">
                    
                    <b><h3>Movimientos</h3></b>
                    
                </th>
            </tr>
            <tr align="center">
                
                <th colspan="3" style= "text-align: center;">
                    <b><h3>Origen</h3></b>
                </th>
                <th colspan="3"  style= "text-align: center;">
                    <b><h3>Destino</h3></b>
                </th>
            </tr>
            
            <tr>
                
                <td>
                    <span style="font-family:'Verdana';"><b>Almacen Origen:</b></span>
                </td>
                <td colspan="2">
                    <select name="almacen_salida" id="almacen_salida" class="form-text">
                        
                    </select>
                </td>
                <td>
                    <span style="font-family:'Verdana';"><b>Almacen Destino:</b></span>
                </td>
                <td colspan="2" align="center">
                    <select name="almacen_entrada" id="almacen_entrada" class="form-text">
                        <option value=''>Seleccione..</option>
                        {html_options output=$option_output_almacen values=$option_values_almacen}
                    </select>
                </td>
            </tr>
            <tr>
                
                <td>
                    <span style="font-family:'Verdana';"><b>Ubicacion Origen:</b></span>
                </td>
                <td colspan="2">
                    <select name="ubicacion_salida" id="ubicacion_salida" class="form-text">
                    </select>
                </td>
                <td>
                    <span style="font-family:'Verdana';"><b>Ubicacion Destino:</b></span>
                </td>
                
                <td colspan="2" align="center">
                    <select name="ubicacion_entrada" id="ubicacion_entrada" class="form-text">
                    </select>
                </td>
            </tr>
            <tr align="center">
                <th colspan="6" style= "text-align: center;">
                    
                    <b><h3>Disponibles</h3></b>
                    
                </th>
            </tr>
            <tr>
                <td>
                    <span style="font-family:'Verdana';"><b>Almacen Origen Disponible:</b></span>
                </td>
                <td colspan="2">
                    <select name="almacen_salida_disponible" id="almacen_salida_disponible" class="form-text">
                        
                    </select>
                </td>
                <td>
                    <span style="font-family:'Verdana';"><b>Almacen Destino Disponible:</b></span>
                </td>
                <td colspan="2" align="center">
                    <select name="almacen_entrada_disponible" id="almacen_entrada_disponible" class="form-text">
                        {html_options output=$option_output_almacen values=$option_values_almacen}
                    </select>
                </td>
            </tr>
            <tr>
                
                <td>
                    <span style="font-family:'Verdana';"><b>Ubicacion Origen Disponible:</b></span>
                </td>
                <td colspan="2">
                    <select name="ubicacion_salida_disponible" id="ubicacion_salida_disponible" class="form-text">
                        
                    </select>
                </td>
                <td>
                    <span style="font-family:'Verdana';"><b>Ubicacion Destino Disponible:</b></span>
                </td>
                
                <td colspan="2" align="center">
                    <select name="ubicacion_entradadisponible" id="ubicacion_entrada_disponible" class="form-text">
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
    <div id="dcompra" class="x-hide-display">
        <div id="PanelGeneralCompra">
            <div id="tabproducto" class="x-hide-display">
                <div id="contenedorTAB">
                    <div id="div_tab1">
                        <div class="grid">
                            <table width="100%" class="lista">
                                <thead>
                                    <tr>
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
    </div>
    <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items">
    <input type="hidden" title="input_tiva" value="0" name="input_tiva" id="input_tiva">
    <input type="hidden" title="input_tsiniva" value="0" name="input_tsiniva" id="input_tsiniva">
    <input type="hidden" title="input_tciniva" value="0" name="input_tciniva" id="input_tciniva">
    <div id="displaytotal" class="x-hide-display"></div>
    <div id="displaytotal2" class="x-hide-display"></div>
</form>
<div id="incluirproducto" class="x-hide-display">
    <!--<label>
        <p><b>Almacen de Salida</b></p>
        <p>
            <select id="almacen" name="almacen"></select>
        </p>
    </label>
    <label>
        <p><b>Ubicacion de Salida</b></p>
        <p>
            <select id="ubicacion" name="ubicacion"></select>
        </p>
    </label>
    -->
    <p>
        <label><b>Codigo de barra</b></label>
        <br/>
        <input type="text" name="codigoBarra" id="codigoBarra">
        <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
    </p>
    <label>
        <p><b>Productos</b></p>
        <p>
            
            <input type="hidden" name="bandera_origen_disponible" id="bandera_origen_disponible">
            <input type="hidden" name="marca" id="marca">
            <input type="hidden" name="items" id="items">
            <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly>
            <!--    <select style="width:100%" id="items" name="items"></select>-->
        </p>
    </label>
    <label>
        <p><b>Lote</b></p>
        <p>
            <input type="text" name="nlote" id="nlote" onkeypress="return solonumeros(event)" readonly="readonly">
        </p>
    </label>
    <label>
        <p><b>Cantidad Unitaria</b></p>
        <p>
            <input type="text" name="cantidadunitaria" id="cantidadunitaria" onkeypress="return solonumeros(event)">
        </p>
    </label>
    <label>
        <p><b>Peso</b></p>
        <p>
            <input type="text" name="peso" id="peso" onkeypress="return solonumeros(event)">
        </p>
    </label>
    <label>
        <p><b>Cantidad Existente en la Ubicacion</b></p>
        <p>
            <input type="text" name="cantidad_existente" id="cantidad_existente" readonly>
        </p>
    </label>
    <label>
        <p><b>Peso Existente en la Ubicacion</b></p>
        <p>
            <input type="text" name="peso_existente" id="peso_existente" readonly>
        </p>
    </label>
</div>


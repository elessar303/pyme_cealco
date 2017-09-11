<script type="text/javascript" src="../../libs/js/event_almacen_salida_calidad.js"></script>
<script type="text/javascript" src="../../libs/js/eventos_formAlmacen_salida_calidad.js"></script>
<script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida_entrada.js"></script>
<link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
{literal}
<script >
    $(document).ready(function(){
         function cargarUbicaciones() {    
        idAlmacen=$("#almacen_entrada").val();     
        $.ajax({
            type: 'POST',
            data: 'opt=cargaUbicacion&idAlmacen='+idAlmacen,
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
                    $("#ubicacion_entrada").append("<option value='"+this.vcampos[i].id+"'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
    }
$("#almacen_entrada").change(function(){
    cargarUbicaciones();
});
  cargarUbicaciones();
  
 

    });

function no_aprobado(valor) {
           
        if(valor==0){
            alert('Explique Causa De no Aprobación');
            $("#causa").show();
        }

        }


function solonumeros(evt){
            // Backspace = 8, Enter = 13, ’0' = 48, ’9' = 57, ‘.’ = 46
            tecla = (document.all) ? e.keyCode : e.which;

             //Tecla de retroceso para borrar, siempre la permite
            if (tecla==8){
            return true;
            }
                
            // Patron de entrada, en este caso solo acepta numeros
            patron =/[0-9-.]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
            }
</script>
{/literal}
<form name="formulario" id="formulario" method="post" action="">
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
                                        <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
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
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/28.png"-->
                    <span style="font-family:'Verdana';"><b>Autorizado Por (*):</b></span>
                </td>
                <td>
                    <input type="text" maxlength="70" name=" autorizado_por" id="autorizado_por" 
                    value="{$nombre_usuario}" class="form-text" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/8.png"-->
                    <span style="font-family:'Verdana';"><b>Observaciones</b></span>
                </td>
                <td>
                    <input type="text" name="observaciones" maxlength="70"  id="observaciones" class="form-text">
                </td>
            </tr>
            <!--<tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/ico_user.gif"-->
                    <!--<span style="font-family:'Verdana';"><b>Almacen de Entrada:</b></span>
                </td>
                <td>
                    <select  name="almacen_entrada" id="almacen_entrada" class="form-text">
                        {html_options output=$option_output_almacen values=$option_values_almacen}
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <!--img align="absmiddle" width="17" height="17" src="../../libs/imagenes/ico_user.gif"-->
                  <!--  <span style="font-family:'Verdana';"><b>Ubicacion de Entrada:</b></span>
                </td>
                <td>
                    <select  name="ubicacion_entrada" id="ubicacion_entrada" class="form-text">
                       
                    </select>
                </td>
            </tr>-->
            <tr>
                <td>
                    <span style="font-family:'Verdana';"><b>Fecha:</b></span>
                </td>
                <td>                    
                    <input type="hidden" name="input_fechacompra" id="input_fechacompra" value='{$smarty.now|date_format:"%Y-%m-%d"}'>
                    <div class="form-text" style="color:#4e6a48" id="fechacompra">{$smarty.now|date_format:"%d-%m-%Y"}</div>
                </td>
            </tr>
        </table>
    </div>
    <!--</Datos del proveedor y vendedor>-->
    <div id="dcompra" class="x-hide-display" >
    
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
    </div></div>
    <input type="hidden" title="input_cantidad_items" value="0" name="input_cantidad_items" id="input_cantidad_items">
    <input type="hidden" title="input_tiva" value="0" name="input_tiva" id="input_tiva">
    <input type="hidden" title="input_tsiniva" value="0" name="input_tsiniva" id="input_tsiniva">
    <input type="hidden" title="input_tciniva" value="0" name="input_tciniva" id="input_tciniva">
    <div id="displaytotal" class="x-hide-display"></div>
    <div id="displaytotal2" class="x-hide-display"></div>
</form>
<div id="incluirproducto" class="x-hide-display">
    <label>
        <p><b>Almacen de Salida</b></p>
        <p><select id="almacen" name="almacen"></select></p>
    </label>
      <label>
        <p><b>Ubicacion de Salida</b></p>
        <p><select id="ubicacion" name="ubicacion"></select></p>
    </label>
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra">
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>
    <label>        
        <p><b>Productos</b></p>
        <p><input type="hidden" name="items" id="items">
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly>
        <!--    <select style="width:100%" id="items" name="items"></select>-->
        </p>
    </label>
    <p>
                <label><b>Fecha de vencimiento</b></label><br/>
                <input type="text" name="fVencimiento" id="fVencimiento"/>
                {literal}
                    <script type="text/javascript">//<![CDATA[
                        var cal = Calendar.setup({
                            onSelect: function(cal) {
                                cal.hide();
                            }
                        });
                        cal.manageFields("fVencimiento", "fVencimiento", "%Y-%m-%d");
                    //]]>
                    </script>
                {/literal}
            </p>
             <p>
                <label><b> Numero de Lote</b></label><br/>
                <input type="text" name="nlote" id="nlote"/>
            </p>
            <p>
                <label for="tipo_uso"><b>Tipo De Uso</b></label>
                <select id="tipo_uso1" name="tipo_uso"></select>
            </p>

              <!--<p>
                <label><b>Estatus</b></label><br/>
                <select name="estatus_producto" id="estatus_producto" size="1" onchange="no_aprobado(this.value);">
                    <option value="" disabled selected>Seleccione </option>
                    <option value="1">Aprobado</option>
                    <option value="0">No Aprobado</option>
                    <option value="3">Pendiente</option>
                </select>
                
            </p>-->
            <p id="causa">
                <label><b>Observación</b></label><br/>
                <textarea name="observacion1" id="observacion1" style="width: 290px"></textarea>
            </p>
    <label>
        <p><b>Cantidad Unitaria</b></p>
        <p><input type="text" name="cantidadunitaria" id="cantidadunitaria" onkeypress="return solonumeros(event)"></p>
    </label>
    <label>
        <p><b>Cantidad Existente en la Ubicacion</b></p>
        <p><input type="text" name="cantidad_existente" id="cantidad_existente" readonly ></p>
    </label>
</div>

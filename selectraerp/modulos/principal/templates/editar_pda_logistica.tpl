<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
         {literal}
        <script language="JavaScript" type="text/JavaScript">
        
        //eliminar distribuccion
        function eliminarDistribucion(objeto){

            if(confirm("¿Esta Seguro de eliminar esta Distribución?"))
            {
                
                $.ajax({
                        type: 'GET',
                        data: 'opt=eliminarDistribucion&'+'id_distribucion='+objeto.id,
                        url: '../../libs/php/ajax/ajax.php',
                        beforeSend: function() 
                        {
                            
                        },
                        success: function(data) 
                        {
                            if(data==1)
                            {
                                Ext.Msg.alert("Eliminado Exitosamente");
                                location.reload();
                            }
                            else
                            {
                                Ext.Msg.alert("Error Durante La Eliminacion, consulte al administrador");
                            }
                            
                        }
                    }); 
            }
        }
        //fin de la eliminacion de distribucion

         //ventanna emergente y ajax de insertar
        function activar(objeto){
          
             win = new Ext.Window({
             title:'Distribucción del producto',
             height:360,
             width:459,
             autoScroll:true,
            
             modal:true,
             bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
             closeAction:'hide',
             contentEl:'incluirproducto',
             buttons:[
                    {
                        text:'Incluir',
                        icon: '../../libs/imagenes/drop-add.gif',
                        handler:function()
                        {
                                destino=$("#destino").val();
                                fecha_inicio=new $("#fecha_inicio").val();
                                fecha_fin=$("#fecha_hasta").val();
                                cantidad=$("#cantidad").val();
                                observacion=$("#observaciones").val();

                                if($("#destino").val()==""||$("#fecha_inicio").val()==""||$("#fecha_fin").val()==""||$("#cantida").val()==""||$("#cantidad").val()<0)
                                {
                                    Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                                    return false;
                                }
                                var x = fecha_inicio.split("-");
                                var z = fecha_fin.split("-");
                                fecha1 = x[1] + "/" + x[0] + "/" + x[2];
                                fecha2 = z[1] + "/" + z[0] + "/" + z[2];
                                if(Date.parse(fecha1) > Date.parse(fecha2))
                                {
                                    $("#fecha_inicio").css("border-color",$(this).val()===""?"red":"");
                                    $("#fecha_inicio").css("border-width",$(this).val()===""?"1px":"");
                                    Ext.Msg.alert("Alerta!", "Fecha Inicial No puede ser mayor a Fecha Final");
                                    return false;
                                }

                            //verificar que cantida no sobrepase el limite
                            $.ajax({
                            type: 'POST',
                            data: 'opt=distribucion_limite&iddetalle='+objeto.id+'&cantidad='+cantidad,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                               
                            },
                            success: function(data) 
                            {
                               
                                this.vcampos = eval(data);
                                if(data==0)
                                {
                                Ext.Msg.alert("Error, La cantidad Ingresada supera la existencia del producto");
                                return false;
                                }
                                else
                                {
                                    if(data=="-1")
                                    {
                                        Ext.Msg.alert("Error, La cantidad Ingresada debe ser positiva");
                                        return false;
                                    }
                                    else
                                    {
                                        //ajax para insertar
                                        $.ajax({
                                            type: 'POST',
                                            data: 'opt=distribucion&iddetalle='+objeto.id+'&destino='+destino+'&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin+'&cantidad='+cantidad+'&observacion='+observacion,
                                            url: '../../libs/php/ajax/ajax.php',
                                            beforeSend: function() {
                                            },
                                            success: function(data) {
                                                this.vcampos = eval(data);
                                                if(data==1)
                                                {
                                                    Ext.Msg.alert("Se ha registrado Una Distribucción");
                                                    location.reload();
                                                }
                                                else
                                                {
                                                    Ext.Msg.alert("Error, Contacte al administrador del sistema");
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                            });
                        }
                    },
                        {
                            text:'Cerrar',
                            icon: '../../libs/imagenes/cancel.gif',
                            handler:function()
                            {
                                 win.hide();
                            }
                        },
                    ]
            });

             win.show();
        }
         //fin de ventana emergente e insertar
         //detalle de los productos
    $(document).ready(function() {
            $('td.detalle1').click(function() {
            objeto = $(this);
             tr=objeto.closest('tr');
            //Deseleccionamos cualquier fila cambiandole el color del tr
            tr.parents("tbody").find(".detalle").attr("bgcolor", "#ececec");
            //Seleccionamos la fila a la cual se dio click para conocer detalles
            tr.attr("bgcolor", "#b6ceff");
            //Removemos cualquier detalle que este cargado en la tabla de estado de cuenta
            tr.parents("tbody").find(".detalle_items").remove();
            //Le colocamos la imagen que indica que puede hacer click para desplegar informacion
            tr.parents("tbody").find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add.gif");
            //Le coloca la imagenes a la fila tr que disparo el evento click.
            tr.find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add2.gif");
            id_transaccion = objeto.find("input[name='id_transaccion']").val();
            //Cargamos los debitos y creditos
            $.ajax({
                type: 'POST',
                data: 'opt=det_pda&id_transaccion=' + id_transaccion,
                url: '../../libs/php/ajax/ajax.php',
                beforeSend: function() {
                },
                success: function(data) {
                  
                   tr.after(data);
                    
                    //objeto.after(data);
                }
            });
        });
    });
    $(document).ready(function(){
        $("#estado_destino").change(function() {
            estados = $("#estado_destino").val();
                $.ajax({
                    type: 'GET',
                    data: 'opt=getPuntos&'+'estados='+estados,
                    url: '../../libs/php/ajax/ajax.php',
                    beforeSend: function() 
                    {
                        $("#destino").find("option").remove();
                        $("#destino").append("<option value=''>Cargando..</option>");
                    },
                    success: function(data) 
                    {
                        $("#destino").find("option").remove();
                        this.vcampos = eval(data);
                        $("#destino").append("<option value=''>Seleccione Punto</option>");
                        for (i = 0; i <= this.vcampos.length; i++) 
                        {
                            $("#destino").append("<option value='" + this.vcampos[i].siga+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                        }
                    }
                }); 
                        
        });

    });
        </script>
        {/literal}
    </head>
    <body>
        <form id="form-{$name_form}" name="form-{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar.tpl"}
                
                <br/>
                <table class="seleccionLista">
                    <thead>
                        <tr class="tb-head" >
                            {foreach from=$cabecera key=i item=campos}
                                <td>{$campos}</td>
                            {/foreach}
                            <td colspan="2" style="text-align:center;">Opciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        {if $cantidadFilas == 0}
                            <tr><td colspan="5">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}"" class="detalle">
                                    <td align="center">{$campos.orden_compra}</td>
                                    <td align="center">{$campos.fecha_planificacion}</td>
                                     <td align="center">{$campos.fecha_planificacion_fin}</td>
                                      <td align="center">{$campos.cantidad}</td>
                                    <td align="center">{$campos.descripcion1} - {$campos.marca} {$campos.pesoxunidad} {$campos.nombre_unidad}</td>
                                    <td align="center">{$campos.descripcion}</td>
                                    
                                    <td style="cursor:pointer; width:30px; text-align:center;">
                                        <img class="editar" id="{$campos.id}" width="25" height="25" onclick="activar(this)" title="Distribuir"src="../../../includes/imagenes/15.png" />
                                    </td>
                                    <td style="width:50px; text-align: center;" class="detalle1">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif"/>
                                        <input type="hidden" name="id_transaccion" value="{$campos.id}"/>
                                        
                                    </td>
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.cod_usuario}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
        <!-- incluir productos -->
        <div id="incluirproducto" class="x-hide-display">
            <p>
                <label for="almacen"><b>Estado Destino</b></label>
                <select name="estado_destino" id="estado_destino" class="form-text" style="width:205px" class="form-text">
                    {html_options values=$option_values_estado output=$option_output_estado selected=$estado}
                </select>
            </p>
            <br>
            <p>
                <label for="almacen"><b>Punto Destino</b></label>
                <select name="destino" id="destino" class="form-text" style="width:205px" class="form-text">
                    <option value=""> Seleccione El Estado </option>
                    {html_options values=$option_values_punto output=$option_output_punto selected=$puntodeventa}
                </select>
            </p>
             <br>
            <p>
                <label><b>Fecha Inicio</b></label><br/>
                <input type="text" name="fecha_inicio" id="fecha_inicio"/ class="form-text">
                {literal}
                    <script type="text/javascript">//<![CDATA[
                        var cal = Calendar.setup({
                            onSelect: function(cal) {
                                cal.hide();
                            }
                        });
                        cal.manageFields("fecha_inicio", "fecha_inicio", "%d-%m-%Y");
                    //]]>
                    </script>
                {/literal}
            </p>
            <p>
                <label><b>Fecha Hasta</b></label><br/>
                <input type="text" name="fecha_hasta" id="fecha_hasta" class="form-text"/>
                {literal}
                    <script type="text/javascript">//<![CDATA[
                        var cal = Calendar.setup({
                            onSelect: function(cal) {
                                cal.hide();
                            }
                        });
                        cal.manageFields("fecha_hasta", "fecha_hasta", "%d-%m-%Y");
                    //]]>
                    </script>
                {/literal}
            </p>
             <p>
                <label for="almacen"><b>Cantidad</b></label><br/>
                <input type="text" id="cantidad" name="cantidad" class="form-text">
            </p>
            <p>
                <label for="almacen"><b>Observaciones</b></label><br/>
                <input type="text" id="observaciones" name="observaciones" class="form-text">
            </p>
           
        </div>
    </body>
</html>
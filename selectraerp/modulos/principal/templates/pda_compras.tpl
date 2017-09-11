<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snipper de código para obtener las bondades de la reutilización.
-->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        {literal}
        <script language="JavaScript" type="text/JavaScript">
        //ventanna emergente y ajax de insertar
         
 
        function editarPDA(objeto)
        {

            //ajax para precargar datos
            $.ajax(
            {
                type: 'POST',
                data: 'opt=getPDACompras&iddetalle='+objeto.id,
                url: '../../libs/php/ajax/ajax.php',
                success: function(data) 
                {
                    vcampos = eval(data);
                    
                    $("#id_proveedor").find("option").remove();
                    $("#id_proveedor").append("<option value=''>Seleccione..</option>");
                    for (i = 0; i < vcampos.length; i++) 
                    {
                        
                        $("#id_proveedor").append("<option value='" + vcampos[i].id_proveedor+ "'>" + vcampos[i].descripcion_proveedor + "</option>");
                        var dateAr = vcampos[i].fecha_inicio.split('-');
                        vcampos[i].fecha_inicio = dateAr[2].slice(0,2) + '/' + dateAr[1] + '/' + dateAr[0];
                        date_fin = vcampos[i].fecha_fin.split('-');
                        vcampos[i].fecha_fin = date_fin[2].slice(0,2) + '/' + date_fin[1] + '/' + date_fin[0];
                        $("#input_fechaplanificacion_inicio").val(vcampos[i].fecha_inicio);
                        $("#input_fechaplanificacion_fin").val(vcampos[i].fecha_fin);
                        $("#observacion_detalle").val(vcampos[i].observaciones);
                        $("#codigoBarra").val(vcampos[i].codigo_barras);
                        $("#items_descripcion").val(vcampos[i].producto);
                        $("#cantidadunitaria").val(vcampos[i].cantidad);

                    }

                }
            });
            win = new Ext.Window({
            title:'Editar PDA',
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
                    destino=$("#instalacion").val();
                    fecha_inicio=new $("#input_fechaplanificacion_inicio").val();
                    fecha_fin=$("#input_fechaplanificacion_fin").val();
                    cantidad=$("#cantidadunitaria").val();
                    observacion=$("#observacion_detalle").val();
                    if($("#instalacion").val()==""||$("#input_fechaplanificacion_inicio").val()==""||$("#input_fechaplanificacion_fin").val()==""||$("#observacion_detalle").val()==""||$("#cantidadunitaria").val()==""||$("#cantidadunitaria").val()<0)
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
                            $("#input_fechaplanificacion_inicio").css("border-color",$(this).val()===""?"red":"");
                            $("#input_fechaplanificacion_inicio").css("border-width",$(this).val()===""?"1px":"");
                            Ext.Msg.alert("Alerta!", "Fecha Inicial No puede ser mayor a Fecha Final");
                            return false;
                        }

                        //verificar que cantida no sobrepase el limite
                        $.ajax(
                        {
                            type: 'POST',
                            data: 'opt=updatePDACompras&iddetalle='+objeto.id+'&destino='+destino+'&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin+'&cantidad='+cantidad+'&observacion='+observacion,
                            url: '../../libs/php/ajax/ajax.php',
                            success: function(data) 
                            {
                                   
                                this.vcampos = eval(data);
                                if(data==1)
                                {
                                    Ext.Msg.alert("Exitosa La Modificación");
                                    location.reload();
                                }
                                else
                                {
                                    Ext.Msg.alert("Error, consulte al administrador");
                                    location.reload();
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

        $(document).ready(function() 
        {
            $("#id_proveedor").change(function() 
            {
                proveedor = $("#id_proveedor").val();
                $.ajax({
                    type: 'GET',
                    data: 'opt=getInstalaciones&'+'idProveedor='+proveedor,
                    url: '../../libs/php/ajax/ajax.php',
                    beforeSend: function() 
                    {
                        $("#instalacion").find("option").remove();
                        $("#instalacion").append("<option value=''>Cargando..</option>");
                    },
                    success: function(data) 
                    {
                        $("#instalacion").find("option").remove();
                        this.vcampos = eval(data);
                        $("#instalacion").append("<option value='0'>Instalacion Principal</option>");
                        for (i = 0; i < this.vcampos.length; i++) 
                        {
                            $("#instalacion").append("<option value='" + this.vcampos[i].codigo_sica+ "'>" + this.vcampos[i].instalacion + "</option>");
                        }
                    }
                }); 
                $("#instalacion").val(0);
            });
            
            $('td.detalle1').click(function() 
            {
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
                $.ajax(
                {
                    type: 'POST',
                    data: 'opt=det_pda_compras&id_transaccion=' + id_transaccion,
                    url: '../../libs/php/ajax/ajax.php',
                    beforeSend: function() {
                    },
                    success: function(data) 
                    {
                       tr.after(data);
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
                {include file = "snippets/regresar_buscar_botones.tpl"}
                {include file = "snippets/tb_head.tpl"}
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                            {foreach from=$cabecera key=i item=campos}
                                <td><strong>{$campos}</strong></td>
                            {/foreach}
                            <td colspan="2" style="text-align:center;"><strong>Opciones</strong></td>
                        </tr>
                        {if $cantidadFilas == 0}
                            <tr><td colspan="6" style="text-align:center;">{$mensaje}</td></tr>
                        {else}
                            {foreach from=$registros key=i item=campos}
                                {if $i%2==0}
                                    {assign var=color value=""}
                                {else}
                                    {assign var=color value="#cacacf"}
                                {/if}
                                <tr bgcolor="{$color}">
                                    <td style="text-align:center; width: 100px;">{$campos.orden_compra}</td>
                                    
                                    <td style="text-align:center; padding-left: 20px;">{$campos.descripcion}</td>
                                    <td style="cursor: pointer; width: 30px; text-align:center;">
                                    {if $campos.orden_compra eq ""}
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.id}'" title="Falta Orden De Compra" src="../../../includes/imagenes/ico_note.gif"/>
                                        {else}
                                        <img class="editar"  title="Orden Generada" src="../../../includes/imagenes/ico_ok.gif"/>
                                        {/if}
                                    </td>
                                    <!--<td style="cursor: pointer; width: 30px; text-align:center;">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=newCompra&amp;cod={$campos.id_proveedor}'" width="17" height="17" title="Generar Nueva Compra" src="../../../includes/imagenes/40.png"/>
                                    </td>-->
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/pda_compras.php?id_transaccion={$campos.id}', '');" title="Imprimir Detalle de Movimiento" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>
                                    <td style="width:50px; text-align: center;" class="detalle1">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif"/>
                                        <input type="hidden" name="id_transaccion" value="{$campos.id}"/>
                                    </td>
                                     <!--<td style="cursor:pointer; width:30px; text-align:center;">
                                        <img class="editar" id="{$campos.id}" width="17" height="17" onclick="activar(this)" title="Distribuir"src="../../../includes/imagenes/add.gif" />-->
                                    </td>
                                </tr>
                                {assign var=ultimo_cod_valor value=$campos.id_proevedor}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                {include file = "snippets/navegacion_paginas.tpl"}
            </div>
        </form>
        <div id="incluirproducto" class="x-hide-display">
            
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra" class="form-text"/>
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>

            <p>
                <label><b>Productos</b></label><br/>
                <input type="hidden" name="items" id="items" class="form-text" />
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly class="form-text" style="width:205px" />
               
            </p>
           
            <p>
                <label><b>Cantidad Unitaria</b></label><br/>
                <input type="text" name="cantidadunitaria" id="cantidadunitaria" class="form-text" style="width:205px"/>
            </p>
             <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Proveedor (*)</b></span>
            </p>
            <p>
                <select name="id_proveedor" id="id_proveedor" class="form-text" style="width:205px">
                    <option value="">Seleccione...</option>
                    {html_options values=$option_values_proveedor output=$option_output_proveedor}
                </select>
            </p>

            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Instalación</b></span><br>
          
                <select name='instalacion' id='instalacion' class="form-text" style="width:205px">
                    <option value=''>Seleccione Un Proveedor...</option>
                </select>
            </p>
            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Inicio</b></span><br>
                <input type="text" name="input_fechaplanificacion_inicio" id="input_fechaplanificacion_inicio" value='{$smarty.now|date_format:"%d/%m/%Y"}' size="30" maxlength="70" class="form-text" readonly="readonly" style="width:205px"  />
                <div id="notificacionVUsuariofecha1"></div>
                    {literal}
                    <script type="text/javascript">
                    //<![CDATA[
                    var cal = Calendar.setup(
                    {
                        onSelect: function(cal) 
                        {
                            cal.hide();
                        }
                    });
                    cal.manageFields("input_fechaplanificacion_inicio", "input_fechaplanificacion_inicio", "%d/%m/%Y");
                    //]]>
                    </script>
                    {/literal}
                <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Fin</b></span><br>
                <input type="text" name="input_fechaplanificacion_fin" id="input_fechaplanificacion_fin" value='{$smarty.now|date_format:"%d/%m/%Y"}' size="30" maxlength="70" class="form-text" readonly="readonly" style="width:205px" />
                <div id="notificacionVUsuariofecha2"></div>
                    {literal}
                    <script type="text/javascript">
                    //<![CDATA[
                    var cal = Calendar.setup(
                    {
                        onSelect: function(cal) 
                        {
                            cal.hide();
                        }
                    });
                    cal.manageFields("input_fechaplanificacion_fin", "input_fechaplanificacion_fin", "%d/%m/%Y");
                    //]]>
                    </script>
                    {/literal}
            </p>
             <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Observación</b></span><br>
                 <input type="text" name="observacion_detalle" id="observacion_detalle" class="form-text" style="width:205px"/>
            </p>
            
        </div>
    </body>
</html>
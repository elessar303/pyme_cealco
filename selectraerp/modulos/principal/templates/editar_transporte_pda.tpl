<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
         {literal}
        <script language="JavaScript" type="text/JavaScript">
        function activar(objeto, update=null){
            
            //get id
            id=objeto['id'];
            if(objeto['name']=='camion')
            {
                tipo=1;
                titulo="Agregar Camión";
                formulario="incluircamion";
                //verificar si es update, en caso afirmativo cargar el valor para realizar el selected
                if(update==1)
                {
                    parametros=
                    {
                        "opt": "obtenerIdCamion",
                        "id": id
                    };
                    $.ajax(
                    {
                        type: 'POST',
                        data: parametros,
                        url: '../../libs/php/ajax/ajax.php',
                        success: function(data) 
                        {
                            if(data=="")
                            {
                                
                                Ext.Msg.alert("¡Error al cargar distribución, consulte al administrador!");
                            }
                            else
                            {
                                
                                 var objeto_json = JSON.parse(data);
                                var option = $('#camion').children('option[value="'+ objeto_json[0].id_transporte_camion +'"]');
                                option.attr('selected',true);
                                $('#fecha').val(objeto_json[0].fecha_ejecucion_transporte);
                            }
                        }
                    });
                }
            }
            else
            {
                tipo=2;
                titulo="Agregar Conductor";
                formulario="incluirconductor";

                if(update==1)
                {
                    parametros=
                    {
                        "opt": "obtenerIdConductor",
                        "id": id
                    };
                    $.ajax(
                    {
                        type: 'POST',
                        data: parametros,
                        url: '../../libs/php/ajax/ajax.php',
                        success: function(data) 
                        {
                            if(data==-1)
                            {
                                
                                Ext.Msg.alert("¡Error al cargar distribución, consulte al administrador!");
                            }
                            else
                            {
                                
                                var option = $('#conductor').children('option[value="'+ data +'"]');
                                option.attr('selected',true);
                            }
                        }
                    });
                }
            }
            win = new Ext.Window({
            title: titulo,
            height:200,
            width:400,
            autoScroll:true,
            modal:true,
            bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
            closeAction:'hide',
            contentEl: formulario,
            buttons:[
                    {
                        text:'Incluir',
                        icon: '../../libs/imagenes/drop-add.gif',
                        handler:function()
                        {
                        //formulario de camion
                        if(tipo==1)
                        {
                            camion=$("#camion").val();
                            fecha=$("#fecha").val();
                            if(camion=="")
                            {
                                Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                                return false;
                            }
                            else
                            {
                                parametros={
                                                "opt": "pdaCamion",
                                                "camion": camion,
                                                "fecha": fecha,
                                                "id_distribucion": id
                                            };
                            }
                        }
                        else // formulario Conductor
                        {
                            conductor=$("#conductor").val();
                            if(conductor=="")
                            {
                                Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                                    return false;
                            }
                            else
                            {
                                parametros={
                                                "opt": "pdaConductor",
                                                "conductor": conductor,
                                                "id_distribucion": id
                                            };
                            }
                        }
                            
                            $.ajax({
                                type: 'POST',
                                data: parametros,
                                url: '../../libs/php/ajax/ajax.php',
                                beforeSend: function() {
                                },
                                success: function(data) {
                                    this.vcampos = eval(data);
                                        if(data==1)
                                        {
                                            Ext.Msg.alert("¡Registro Exitoso!");
                                            location.reload();
                                        }
                                        else
                                        {
                                            Ext.Msg.alert("Error, Contacte al administrador del sistema");
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
         
        $(document).ready(function() {
            $('td.detalle1').click(function() {
            objeto = $(this);
             tr=objeto.closest('tr');
            tr.parents("tbody").find(".detalle").attr("bgcolor", "#ececec");
            tr.attr("bgcolor", "#b6ceff");
            tr.parents("tbody").find(".detalle_items").remove();
            tr.parents("tbody").find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add.gif");
            tr.find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add2.gif");
            id_transaccion = objeto.find("input[name='id_transaccion']").val();
            $.ajax({
                type: 'POST',
                data: 'opt=det_pda_transporte&id_transaccion=' + id_transaccion,
                url: '../../libs/php/ajax/ajax.php',
                beforeSend: function() {
                },
                success: function(data) {
                   tr.after(data);
                }
            });
        });

            $("#camion_estado").change(function() {
                        estados = $("#camion_estado").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getPdaCamionEstado&'+'camion_estado='+estados,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() 
                            {
                                $("#camion").find("option").remove();
                                $("#camion").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) 
                            {
                                $("#camion").find("option").remove();
                                this.vcampos = eval(data);
                                if(this.vcampos[0].id==-1)
                                {
                                     $("#camion").append("<option value=''>No Posee Flota En Ese Estado</option>");
                                }
                                else
                                {
                                    for (i = 0; i < this.vcampos.length; i++) 
                                    {
                                        $("#camion").append("<option value='" + this.vcampos[i].id+ "'>" + this.vcampos[i].nombre + "</option>");
                                    }
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
                                <tr bgcolor="{$color}" class="detalle">
                                    <td>{$campos.orden_compra}</td>
                                    <td>{$campos.fecha_planificacion}</td>
                                    <td>{$campos.descripcion1}</td>
                                    <td>{$campos.descripcion}</td>
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
        <!--Formulario de Camion -->
        <div id="incluircamion" class="x-hide-display">
            <p>
                <label for="almacen"><b>Seleccione El Estado</b></label><br/>
                <select name="camion_estado" id="camion_estado" class="form-text" style="width:205px">
                    {html_options values=$option_values_estado output=$option_output_estado selected=$estado}
                </select>
            </p>
            <p>
                <label for="almacen"><b>Seleccione El Camión</b></label><br/>
                <select name="camion" id="camion" class="form-text" style="width:205px">
                    <option value=""> Seleccione Un Estado </option>
                    {html_options values=$option_values_camion output=$option_output_camion selected=$camion}
                </select>
            </p>
            <P>
                <label for="almacen"><b>Fecha Despacho</b></label><br/>
                <input type="text" name="fecha" placeholder="Fecha Despacho" size="30px" id="fecha" class="form-text"/>
                 {literal}
                    <script type="text/javascript">//<![CDATA[
                        var cal = Calendar.setup({
                            onSelect: function(cal) 
                            {
                                cal.hide();
                            }
                        });
                        cal.manageFields("fecha", "fecha", "%d-%m-%Y");
                        //]]>
                    </script>
                {/literal}
            </P>
                                
        </div>
        <!--Formulario de Conductor-->
        <div id="incluirconductor" class="x-hide-display">
            <p>
                <label for="almacen"><b>Seleccione El Conductor Encargado</b></label><br/>
                <select name="conductor" id="conductor" class="form-text" style="width:205px" class="form-text">
                    {html_options values=$option_values_conductor output=$option_output_conductor selected=$conductor}
                </select>
            </p>
           
        </div>
    </body>
</html>
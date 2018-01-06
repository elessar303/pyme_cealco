<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {assign var=name_form value="usuarios_nuevo"}
        {include file="snippets/header_form.tpl"}
        <!--Para estilo JQuery en botones-->
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/md5_crypt.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
       {literal}
            <script type="text/javascript">//<![CDATA[
               
               //funcion para llamar al pdf
               function llamarPdf(id)
                {
                    window.open('../../fpdf/Pdfticketpaleta.php?id='+id);
                };
               //funcion para buscar el combo dependiente
                function listarubicaciones(idalmacen, tipoSql, idubicacion)
                {
                    var paramentros="opt=cargaUbicacionNuevo&idUbicacion="+idubicacion+"&tipoSql="+tipoSql;
                    $.ajax({
                        type: "POST",
                        url: "../../libs/php/ajax/ajax.php",
                        data: paramentros,
                        beforeSend: function(datos){
                            $("#"+idalmacen).html('<option value = 0> Cargando... </option>');
                        },
                        success: function(datos){
                            $("#"+idalmacen).html(datos);
                        },
                        error: function(datos,falla, otroobj){
                            $("#"+idSelect).html('<option value = 0> Error... </option>');
                        }
                    });
                };
               $(document).ready(function()
               {
                    
                    if($("#ticketestatus").val()==1)
                    {                    
                        $("#ticket").keyup(function()
                        {
                            valor = $(this).val();
                            if(valor!='')
                            {
                                $.ajax(
                                {
                                    type: "GET",
                                    url:  "../../libs/php/ajax/ajax.php",
                                    data: "opt=ValidarTicketEntrada&v1="+valor,
                                    beforeSend: function(){
                                        //$("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nombre de Usuario..</b>"));
                                    },
                                    success: function(data)
                                    {
                                        resultado = data
                                        if(resultado=="-1")
                                        {
                                            $("#ticket").val("").focus();
                                            $("#notificacionticket").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Ticket Ya fue usado.</b></span>");
                                        }
                                        if(resultado=="1")
                                        {//cod de item disponble
                                            $("#notificacionticket").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Ticket Disponible</b></span>");
                                        }
                                    }
                                });
                            }
                        });
                    }
                    //campos select2
                    $('#ubicacion_principal').select2(
                    {
                        placeholder: "Seleccione ...",
                        allowClear: true
                    });
                    $('#ubicacion_detalle').select2(
                    {
                        placeholder: "Seleccione ...",
                        allowClear: true
                    });
                    
                    //combo dependiente
                    $("#ubicacion_principal").change(function()
                    {
                        idubicacion=$("#ubicacion_principal").val();
                        listarubicaciones("ubicacion_detalle", 0, idubicacion);
                    });
                    
                    //enviando el formulario
                    $( "#form-usuarios_nuevo").submit(function( event ) 
                    {
                        //se toman los valores
                        var principal = $('#ubicacion_principal').val();
                        var detalle = $('#ubicacion_detalle').val();
                        var cantidad = $('#peso_unidad').val();
                        var peso = $('#peso').val();
                        var movimiento = $('#movimiento').val();
                        var ticketestatus = $('#ticketestatus').val();
                        if(ticketestatus ==1)
                        {
                            ticket= $.trim($('#ticket').val());
                            if(ticket == "" || ticket==null)
                            {
                                Ext.Msg.alert("Alerta","Debe llenar todos los campos");
                                return false;
                            }
                        }
                        else
                        {
                            ticket="";
                        }
                        
                        if(principal=="" || detalle=="" || principal=="0" || detalle=="0" || cantidad=="" || cantidad<1 || peso=="" || peso<1)
                        {
                            Ext.Msg.alert("Alerta","Debe llenar todos los campos");
                            return false;
                        }
                        else
                        {
                            event.preventDefault();
                        }
                        
                        parametros=
                      {
                        'opt': 'EntradaNuevaAlmacen',
                        'id_movimiento' : movimiento,
                        'ubicacion_principal' : principal,
                        'ubicacion_detalle' : detalle,
                        'cantidad' : cantidad,
                        'peso' : peso,
                        'ticket' : ticket,
                        
                      };
                        $.ajax(
                        {
                            type: "POST",
                            url:  "../../libs/php/ajax/ajax.php",
                            data: parametros,
                            beforeSend: function()
                            {
                                $("#gridpaletas").html(MensajeEspera("<b>Actualizando..</b>"));
                            },
                            success: function(data)
                            {
                                resultado = data['estatus'];
                                if(resultado==-1)
                                {
                                    $("#grridpaletas").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, ocurrio un error.</b></span>");
                                }
                                if(resultado!=-1)
                                {
                                    //si se procesó correctamente se hace llamado a la tabla de movimiento
                                    location.reload();
                                    
                                }
                            }
                        }); 
                        //fin ajax
                        
                    });
                    
                    
                    $("#nombre_caja").blur(function()
                    {
                        valor = $(this).val();
                        if(valor!='')
                        {
                            $.ajax(
                            {
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=Validarnombre_caja&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nombre de Caja..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado==-1)
                                    {
                                        $("#nombre_caja").val("").focus();
                                        $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, esta Caja ya existe.</b></span>");
                                    }
                                    if(resultado==1)
                                    {//cod de item disponble
                                        $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nombre de Caja Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });
                    $("#peso_unidad").keyup(function()
                    {
                        valor = parseFloat($(this).val());
                        if(isNaN(valor))
                        {
                            Ext.Msg.alert("Alerta","El Peso Unidad debe ser un número válido");
                            $(this).val("");
                            return false;
                        }
                        valortotal = $('#valortotal').val();
                        if(valor > valortotal)
                        {
                            Ext.Msg.alert("Alerta","El producto no debe superar el limite total");
                            $(this).val(0);
                            return false;
                        }
                    });
                    $("#peso").keyup(function()
                    {
                        peso = parseFloat($(this).val());
                        if(isNaN(peso))
                        {
                            Ext.Msg.alert("Alerta","El Peso debe ser un número válido");
                            $(this).val("");
                            return false;
                        }
                        valorpaleta = $('#valorpaleta').val();
                        if(peso > valorpaleta)
                        {
                            Ext.Msg.alert("Alerta","El producto no debe superar el limite de paleta");
                            $(this).val(0);
                            return false;
                        }
                    });
                });/*end of document.ready*/
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar.tpl"}
                <table style="width: 100%" border="0">
                    <tr>
                        <td colspan="4">
                            <table width=500px; align='center'>
                                <thead>
                                    <tr>
                                        <th class="tb-head" ><b>Producto</b></th>
                                        <th class="tb-head" ><b>Total Cantidad</b></th>
                                        <th class="tb-head" ><b>Unidad Paleta</b></th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td align="center">
                                        <b>{$nombre_producto}</b>
                                    </td>
                                    <td align="center">
                                        <b>{$total}</b>
                                        <input type='hidden' value = '{$total}' name='valortotal' id='valortotal'/>
                                        <input type='hidden' value = '{$movimiento}' name='movimiento' id='movimiento'/>
                                    </td>
                                    <td align="center">
                                        <b>{$paleta}</b>
                                        <input type='hidden' value = '{$paleta}' name='valorpaleta' id='valorpaleta'/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <thead>
                        <tr>
                            <th colspan="4" class="tb-head">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="3" class="label">
                            Ubicacion Principal **
                        </td>
                        <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                            <select name="ubicacion_principal" id="ubicacion_principal" style="width:200px;" class="form-text">
                                <option value="0">Seleccione ...</option>                               
                                {html_options values=$option_values_ubicacion_principal output=$option_output_ubicacion_principal}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="label">
                            Ubicacion Detalle **
                        </td>
                        <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                            <select name="ubicacion_detalle" id="ubicacion_detalle" style="width:200px;" class="form-text">
                                
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="label">
                            Unidad **
                        </td>
                        <td style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="peso_unidad" placeholder="Unidad" size="60" id="peso_unidad" class="form-text"/>
                            <input type="hidden" name="ticketestatus" id="ticketestatus" value="{$ticket}" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="label">
                            Peso **
                        </td>
                        <td style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="peso" placeholder="Peso" size="60" id="peso" class="form-text"/>
                            <input type="hidden" name="ticketestatus" id="ticketestatus" value="{$ticket}" />
                        </td>
                    </tr>
                    {if $ticket eq 1}
                        <tr>
                    {else}
                        <tr style="display: none;">
                    {/if}
                        <td colspan="3" class="label">
                            Ticket Entrada **
                        </td>
                        <td style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="ticket" placeholder="Ticket Entrada" size="60" id="ticket" class="form-text"/>
                        </td>
                    </tr>
                    <tr  style="text-align: center;">
                        <td align="center" style="padding-top:2px; padding-right: 2px;" colspan="4">
                            <input type="submit" name="aceptar" id="aceptar" value="Generar Paleta" class="form-text"/>
                            <div id="notificacionticket">
                                
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
        <div id='gridpaletas' align="center" style='text-align: cemter'>
            
            <table width=100%; align='center' border="1">
                <thead>
                    <tr>
                        <th class="tb-head" ><b>Cliente</b></th>
                        <th class="tb-head" ><b>Codigo Barras</b></th>
                        <th class="tb-head" ><b>Descripcion</b></th>
                        <th class="tb-head" ><b>Lote</b></th>
                        <th class="tb-head" ><b>Cantidad</b></th>
                        <th class="tb-head" ><b>Peso</b></th>
                        <th class="tb-head" ><b>Ubicación Principal</b></th>
                        <th class="tb-head" ><b>Ubicación Detalle</b></th>
                        <th class="tb-head" ><b>Nro. Recepcion</b></th>
                        <th class="tb-head" ><b>Generar Ticket</b></th>
                    </tr>
                </thead>
                <tbody
                    
                    {$datostabla}
                    
                </tbody>
            </table>
            
        </div>
    </body>
</html>
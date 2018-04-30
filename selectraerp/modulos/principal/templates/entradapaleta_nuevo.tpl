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
        <link href="../../libs/js/select2/dist/css/select2.min.css" rel="stylesheet" />
        <script src="../../libs/js/select2/dist/js/select2.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
       {literal}
            <script type="text/javascript">//<![CDATA[
               
               //funcion para llamar al pdf
               function llamarPdf(id)
                {
                    window.open('../../fpdf/Pdfticketpaleta.php?id='+id);
                };
                function llamarTXT(id)
                {
                    window.open('../../fpdf/Simpleticketpaleta.php?id='+id);
                };
                
                function EliminarPaleta(id)
                {
                    
                    if(confirm("¿Esta seguro de querer Eliminar la Paleta?"))
                    {
                        var paramentros="opt=EliminarPaleta&v1="+id;
                        $.ajax(
                        {
                            type: "POST",
                            url: "../../libs/php/ajax/ajax.php",
                            data: paramentros,
                            
                            success: function(datos)
                            {
                                if(datos==1)
                                {
                                    alert("Se ha eliminado la Paleta");
                                    location.reload();
                                    
                                }
                                else
                                {
                                    if(datos==2)
                                    {
                                        alert("Paleta No se puede Eliminar, verifique que no sea la unica paleta existente");
                                    }
                                }
                            },
                            
                        });
                    }
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
                    $("#pesoempaque").keyup(function()
                    {
                        pesobruto=$("#pesobruto").val();
                        pesoestiva=$("#pesoestiva").val();
                        pesoempaque=$("#pesoempaque").val();
                        if( (isNaN(pesobruto) || pesobruto=="") || (isNaN(pesoestiva) || pesoestiva=="") || (isNaN(pesoempaque) || pesoempaque=="")  )
                        {
                            alert("Debe introducir solo Números en los campos pesos");
                            $("#pesoempaque").val("");
                            this.focus();
                            return false;
                        }
                        else
                        {
                            pesototal=parseFloat(pesobruto) - (parseFloat(pesoempaque)+parseFloat( pesoestiva)) ;
                            $("#peso").val(pesototal);
                        }
                    });
                    $("#cerrar").click(function()
                    {
                        
                        if(confirm("¿Esta seguro de querer cerrar la entrada?"))
                        {
                            valor = $('#movimiento').val();
                            $.ajax(
                            {
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=CerrarEntrada&v1="+valor,
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="1")
                                    {
                                        alert("Se ha cerrado la entrada");
                                        location.reload();
                                        
                                    }
                                    else
                                    {
                                        alert("Error al intentar cerrar la entrada, consulte al administrador");
                                    }
                                    
                                }
                            });
                        }
                        else
                        {
                            return false;
                        }
                    });
                    //fin de cerrar la entrada
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
                        var cajas = JSON.stringify($('[name="cajas[]"]').serializeArray());
                        var principal = $('#ubicacion_principal').val();
                        var detalle = $('#ubicacion_detalle').val();
                        var cantidad = $('#peso_unidad').val();
                        var peso = $('#peso').val();
                        var pesobruto = $('#pesobruto').val();
                        var pesoestiva = $('#pesoestiva').val();
                        var pesoempaque = $('#pesoempaque').val();
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
                        
                        if(principal=="" || detalle=="" || principal=="0" || detalle=="0" || cantidad=="" || cantidad<1 || peso=="" || peso<1 || pesobruto=="" || pesobruto<1 || pesoestiva=="" || pesoestiva<0 || pesoempaque=="" || pesoempaque<0)
                        {
                            Ext.Msg.alert("Alerta","Debe llenar todos los campos");
                            return false;
                        }
                        else
                        {
                            event.preventDefault();
                        }
                        if(document.getElementById('observacion_limite_1').style.display!='none')
                        {
                            observacion_limite= $.trim($('#observacion_limite').val());
                        }
                        else
                        {
                            observacion_limite=null;
                        }
                        
                        parametros=
                      {
                        'opt': 'EntradaNuevaAlmacen',
                        'id_movimiento' : movimiento,
                        'ubicacion_principal' : principal,
                        'ubicacion_detalle' : detalle,
                        'cantidad' : cantidad,
                        'peso' : peso,
                        'peso_bruto' : pesobruto,
                        'peso_estiva' : pesoestiva,
                        'peso_empaque' : pesoempaque,
                        'ticket' : ticket,
                        'cajas' : cajas,
                        'observacion_limite' : observacion_limite,
                        
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
                            Ext.Msg.alert("Alerta","La Unidad debe ser un número válido");
                            $(this).val("");
                            return false;
                        }
                        valortotal = $('#valortotal').val();
                        if(valor > valortotal)
                        {
                            if(confirm("Esta Superando el limite total de unidad, ¿esta seguro de querer realizar la operación?"))
                            {
                                alert("Por favor ingrese La observación");
                                $("#observacion_limite_1").removeAttr("style");
                                $("#observacion_limite").focus();
                            }
                            else
                            {
                                Ext.Msg.alert("Alerta","El producto no debe superar el limite total");
                                $(this).val(0);
                                return false;    
                            }
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
                                        <th class="tb-head" ><b>Total Cantidad Unitaria</b></th>
                                        <th class="tb-head" ><b>Peso Paleta</b></th>
                                        <th class="tb-head" ><b>Total Peso Ingresado</b></th>
                                        <th class="tb-head" ><b>Total Peso Bruto</b></th>
                                        <th class="tb-head" ><b>Total Peso Estiva</b></th>
                                        <th class="tb-head" ><b>Total Peso Empaque</b></th>
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
                                    <td align="center">
                                        <b>{$totalpeso}</b>
                                        <input type='hidden' value = '{$totalpeso}' name='totalpeso' id='totalpeso'/>
                                    </td>
                                    <td align="center">
                                        <b>{$totalpesobruto}</b>
                                        <input type='hidden' value = '{$totalpesobruto}' name='totalpesobruto' id='totalpesobruto'/>
                                    </td>
                                    <td align="center">
                                        <b>{$totalpesoestiva}</b>
                                        <input type='hidden' value = '{$totalpesoestiva}' name='totalpesoestiva' id='totalpesoestiva'/>
                                    </td>
                                    <td align="center">
                                        <b>{$totalpesoempaque}</b>
                                        <input type='hidden' value = '{$totalpesoempaque}' name='totalpesoempaque' id='totalpesoempaque'/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {if $visiblecerrar neq 2}
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
                            Peso Neto**
                        </td>
                        <td style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="peso" placeholder="Peso" size="60" id="peso" class="form-text" readonly="readonly"/>
                            <input type="hidden" name="ticketestatus" id="ticketestatus" value="{$ticket}"  readonly="readonly" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="label">
                            Peso Bruto**
                        </td>
                        <td style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="pesobruto" placeholder="Peso" size="60" id="pesobruto" class="form-text"/>
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="label">
                            Peso Estiva**
                        </td>
                        <td style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="pesoestiva" placeholder="Peso" size="60" id="pesoestiva" class="form-text"/>
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="label">
                            Peso Empaque**
                        </td>
                        <td style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="pesoempaque" placeholder="Peso" size="60" id="pesoempaque" class="form-text"/>
                            
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
                            <input type="text" name="ticket" placeholder="Ticket Entrada" size="60" id="ticket" class="form-text" value="{$id_ticket_entrada}" readonly="true" />
                        </td>
                    </tr>
                    <tr id="observacion_limite_1"  style="display : none;">
                        <td colspan="3" class="label">
                            Observación Límite **
                        </td>
                        <td style="padding-top:2px; padding-bottom: 2px;">
                            <input type="text" name="observacion_limite" placeholder="Observacion" size="60" id="observacion_limite" class="form-text"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">
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
                        <td colspan="4">
                            {foreach key=key item=servicios from=$checkbox}
                              
                              <label><input type="checkbox" id="{$servicios.id}" name='cajas[]' value="{$servicios.id}" checked="checked"/>{$servicios.nombre}</label>&nbsp;

                            {/foreach}
                        </td>
                    </tr>
                    <tr  style="text-align: center;">
                        <td align="center" style="padding-top:2px; padding-right: 2px;" colspan="4">
                            {if $visiblecerrar eq 1}
                            <input type="button" name="cerrar" id="cerrar" value="Cerrar Entrada" class="form-text"/>
                            {/if}
                            <input type="submit" name="aceptar" id="aceptar" value="Generar Paleta" class="form-text"/>
                            <div id="notificacionticket">
                                
                            </div>
                        </td>
                    </tr>
                {/if}            
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
                        <th class="tb-head" colspan="2" style="text-align: center;"><b>Generar Ticket</b></th>
                        <th class="tb-head" ><b> Eliminar</b></th>
                    </tr>
                </thead>
                <tbody
                    
                    {$datostabla}
                    
                </tbody>
            </table>
            
        </div>
    </body>
</html>
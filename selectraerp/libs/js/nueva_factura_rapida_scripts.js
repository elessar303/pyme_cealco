/**
 *
 * Creacion de ventana para la busqueda de items.
 * Fecha de Creacion: dom 4 de marzo de 2012, 10:49:51 PM
 *
 * Autor: Luis E. Viera Fernandez.
 * Correo:
 *  lviera@armadillotec.com
 *  lviera86@gmail.com
 *
 * Adaptaciones y Mejoras: Charli J. Vivenes Rengel.
 * Correo:
 *  cvivenes@asys.com.ve
 *  cjvrinf@gmail.com
 *
 */
 var data = {
        productoSeleccionado : {},
        config_general : {}
    };

$(function(){
    ContarInputItem = 0;
    
    $("input[name='totalPedido'],input[name='montodescuentoPedido'],input[name='descuentoPedido'],input[name='cantidadPedido'],input[name='precioProductoPedido']").numeric();
    $('a[rel*=facebox]').facebox();
    $("#PanelFactura").hide();
    $(".grid table.lista tbody tr").live("mouseover", function(){
        $(this).find("td").css("background-color", "#f1f3f3");
        $(".info_detalle").css("background-color", "#507e95");
    }).live("mouseout", function(){
        $(this).find("td").css("background-color", "");
        $(".info_detalle").css("background-color", "#507e95");
    });
    
    function fn_cantidad(){
        var_montoItemsFactura = 0;
        var_ivaTotalFactura= 0;
        var_descuentosItemFactura =0;
        var_TotalTotalFactura = 0;
        var_total_costo_actual = 0;
        var_total_porcentaje_costo_ganancia = 0;
        var_subTotal = 0;
        var_cantidad_por_bulto = 0;
        var_ganancia_total_item = 0;
        var_porcentaje_ganancia_total_items = 0;

        var_total_peso = 0;
        var_totalm3 = 0;
        var_totalft3 = 0;

        $(".grid table.lista tbody").find("tr").each(function(){
            var_subTotal = parseFloat(var_subTotal) +  parseFloat($(this).find("td.cantidad").attr("rel"))*parseFloat($(this).find("td.preciosiniva").text());
            var_montoItemsFactura = parseFloat(var_montoItemsFactura) + parseFloat($(this).find("td.totalsiniva").text());
            var_ivaTotalFactura =  parseFloat(var_ivaTotalFactura) + parseFloat($(this).find("td.piva").attr("rel"));
            var_descuentosItemFactura =  parseFloat(var_descuentosItemFactura) + parseFloat($(this).find("td.montodescuento").html());
            var_TotalTotalFactura = parseFloat(var_montoItemsFactura) + parseFloat(var_ivaTotalFactura);
            cantidad_por_bulto = parseInt($(this).find("td").find("input[name='_cantidad_bulto[]']").val());
            cantidad_items = parseFloat($(this).find("td.cantidad").attr("rel"));
            tcos_actual = parseFloat($(this).find("td").find("input[name='_id_costo_actual[]']").val());
            var_ganancia_total_item += parseFloat($(this).find("td").find("input[name='_ganancia_item_individual[]']").val());

            var_total_peso += parseFloat($(this).find("td").find("input[name='_peso_total_item[]']").val());

            var_totalm3 += parseFloat($(this).find("td").find("input[name='_totalm3[]']").val());
            var_totalft3 += parseFloat($(this).find("td").find("input[name='_totalft3[]']").val());

            if(_.isNaN(tcos_actual)){
                tcos_actual = 0;
            }

            var_cantidad_por_bulto += cantidad_por_bulto;
            var_total_costo_actual += parseFloat(tcos_actual);

            var_porcentaje_ganancia_total_items = ((var_ganancia_total_item)*100)/var_montoItemsFactura;
        });

        
        $("#Totalm3").html(var_totalm3);
        $("#TotalFT3").html(var_totalft3);

        
        $("#subTotal").html(var_subTotal.toFixed(2)+" "+$("#moneda").val());
        $("input[name='input_subtotal']").attr("value",var_subTotal.toFixed(2));
        $("#montoItemsFactura").html(var_montoItemsFactura.toFixed(2)+" "+$("#moneda").val());
        $("input[name='input_montoItemsFactura']").attr("value",var_montoItemsFactura.toFixed(2));
        $("#ivaTotalFactura").html(var_ivaTotalFactura.toFixed(2)+" "+$("#moneda").val());
        $("input[name='input_ivaTotalFactura']").attr("value",var_ivaTotalFactura.toFixed(2));
        $("#descuentosItemFactura").html(var_descuentosItemFactura.toFixed(2)+" "+$("#moneda").val());
        $("input[name='input_descuentosItemFactura']").attr("value",var_descuentosItemFactura.toFixed(2));
        $("#TotalTotalFactura").html(var_TotalTotalFactura.toFixed(2)+" "+$("#moneda").val());
        $("input[name='input_TotalTotalFactura']").attr("value",var_TotalTotalFactura.toFixed(2));
        cantidad = $(".grid table.lista tbody").find("tr").length;
        $(".span_cantidad_items").html("<span style=\"font-size: 15px; font-style: italic;\">Cantidad de Items: "+cantidad+"</span>");
        $("input[name='input_cantidad_items']").attr("value",cantidad.toFixed(3));
        $("input[name='input_TotalBultos']").attr("value",var_cantidad_por_bulto);
        
        $("#TotalBultos").html(var_cantidad_por_bulto);
        $("#total_ganancia_monto").html(var_ganancia_total_item);
        $("#total_ganancia_porcenaje").html(var_porcentaje_ganancia_total_items.toFixed(2));
        $("#PesoTotal").html(var_total_peso);

       
        $("input[name='total_bultos']").val(var_cantidad_por_bulto);
        $("input[name='peso_total_item']").val(var_total_peso);
        $("input[name='total_m3']").val(var_totalm3)
        $("input[name='total_ft3']").val(var_totalft3)

        $("input[name='total_porcentaje_ganancia']").val(var_porcentaje_ganancia_total_items.toFixed(2))
        $("input[name='total_monto_ganancia_total']").val(var_ganancia_total_item);

        $.totalizarFactura();

        

    }

    $(".info_detalle").live("click", function(){
        cod = $(this).parent('tr').find("input[name='idItem_']").val();
        $.ajax({
            type: 'GET',
            data: 'cod='+cod,
            url:  'info_servicio_item.php',
            beforeSend: function(){
            //$.facebox.loading();
            },
            success: function(data){
                var win ;
                cerrarWin = new Ext.Button({
                    text:'Cerrar Ventana',
                    iconCls:'cancelar',
                    handler:function(){
                        win.close();
                    }
                });
                win = new Ext.Window({
                    title:'Informaci&oacute;n del item',
                    modal:true,
                    iconCls:'iconTitulo',
                    constrain:true,
                    autoScroll:true,
                    html:data,
                    action:'hide',
                    height:400,
                    width:450,
                    buttonAlign:'center',
                    buttons:[
                    cerrarWin
                    ]
                });
                win.show();
            }
        });
    });
    $("img.eliminar").live("click",function(){
        //$.facebox($(this).parents("tr").find("td").eq(0).html());
        //$(this).parents("tr").fadeOut("normal");
        iditem = $(this).parents("tr").find("td").eq(9).find("input[name='_item_codigo[]']").attr("value");
        coditemprecompromiso = $(this).parents("tr").find("td").eq(9).find("input[name='_cod_item_precompromiso[]']").attr("value");
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=delete_precomprometeritem&v1="+iditem+"&codprecompromiso="+coditemprecompromiso
        });
        $(this).parents("tr").fadeOut("normal",function(){
            $(this).remove();
            fn_cantidad();
        });
    });

     $(".eliminar_precomp").live("click",function(){
        //$.facebox($(this).parents("tr").find("td").eq(0).html());
        //$(this).parents("tr").fadeOut("normal");
         iditem=$(this).parent('tr').find("input[name='idItem1_']").val();
        //= $(this).parents("tr").find("td").eq(9).find("input[name='idItem1_']").attr("value");
        coditemprecompromiso= $(this).parent('tr').find("input[name='cod_pre']").val();
         //= $(this).parents("tr").find("td").eq(9).find("input[name='_cod_item_precompromiso']").attr("value");
//        alert(iditem);
//        alert(coditemprecompromiso);
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=delete_precomprometeritem&v1="+iditem+"&codprecompromiso="+coditemprecompromiso
        });
        $(this).parents("tr").fadeOut("normal",function(){
            $(this).remove();
            fn_cantidad();
        });
    });
    /*
    * Esta funcion permite crear tantos input se necesiten para la creacion de la tr
    * de la tabla de items (los que se cargan a la hora de la factura)
    **/
    $.inputHidden = function(Input,Value,ID){
        return '<input type="hidden" name="'+Input+''+ID+'" value="'+Value+'">';
    }

    var getTipoPrecio = function(){
        var tipo_precio = parseInt($("input[name='precio_por_defecto']").val());
        var preciosiniva = 0;

        var p = {
            precio1: 1,
            precio2: 2,
            precio3: 3,
        };
        
        
        switch(tipo_precio){
            case p.precio1:
                preciosiniva = parseFloat(data.productoSeleccionado.precio1);
                break;
            case p.precio2:
                preciosiniva = parseFloat(data.productoSeleccionado.precio2);
                break;
            case p.precio3:
                preciosiniva = parseFloat(data.productoSeleccionado.precio3);
                break;            
        }
      
        return preciosiniva;
    }

    $.agregarArticulo = function(){
        var dataitem = JSON.parse($("#informacionitem").val());
       

        if(dataitem==undefined){
            Ext.MessageBox.show({
                title: 'Notificaci&oacute;n',
                msg: "Debe cargar un producto o servicio",
                buttons: Ext.MessageBox.OK,
                animEl: 'addTabla',
                icon: 'ext-mb-warning'
            });
            return false;
        }
        
        codigo = dataitem.id_item;
        producto = dataitem.cod_item;//codigo_barras
        descripcion = dataitem.descripcion1;
        cantidad = $("input[name='filtro_cantidad']").val();
         //alert(cantidad);

        if(parseFloat(cantidad)==0){
            Ext.MessageBox.show({
                title: 'Notificaci&oacute;n',
                msg: "Debe especificar la cantidad!",
                buttons: Ext.MessageBox.OK
            });
            $("#filtro_cantidad").focus();
            return false
        }
        cantidad = parseFloat(cantidad);
        var preciosiniva = getTipoPrecio();

        if(preciosiniva==0){
            Ext.MessageBox.show({
                title: 'Notificaci&oacute;n',
                msg: 'El campo precio sin Iva debe ser distinto de cero (0)!',
                buttons: Ext.MessageBox.OK
            });
            return false;
        }
        var restarDescuento = false;
        descuento = $("input[name='filtro_descuento']").val();
        if(!_.str.isBlank(descuento)){
            if(descuento.indexOf("%")>=0){
                if(_.isNaN(parseFloat(descuento))){
                    Ext.MessageBox.show({
                        title: 'Notificaci&oacute;n',
                        msg: 'El valor del descuento es invalido, verifique!',
                        buttons: Ext.MessageBox.OK
                    });
                    return false;
                }
                descuento = parseFloat(descuento) / 100;
            } else {
                restarDescuento = true;
            }
        } else {
            descuento = 0;
        }

        if(descuento>0 || restarDescuento==true){
            if(restarDescuento){
                montodescuento  = descuento;
                descuento = 0;
            }else{
                montodescuento  = parseFloat((( preciosiniva * cantidad ) * descuento).toFixed(3));
            }
        } else {
            montodescuento = 0;
        }

        totalsiniva  = ( preciosiniva * cantidad )  - montodescuento;
        
        if(dataitem.monto_exento==1){// es exento
            iva = 0;
            piva = parseFloat(0);
            totalconiva = 0;
        }else{
            iva = dataitem.iva;
            piva = parseFloat((totalsiniva*iva)/100);
            totalconiva = parseFloat(piva) + parseFloat(totalsiniva);
        }
        almacen = parseInt($("input[name='cod_almacen_defecto']").val());
        ubicacion = parseInt($("input[name='cod_ubicacion_defecto']").val());
        cliente = parseInt($("input[name='id_cliente']").val());
        cuota=0;
        addTabla(cliente,codigo,descripcion,cantidad,preciosiniva,descuento,montodescuento,totalsiniva,iva,piva,totalconiva,almacen,producto,ubicacion);
    };



    /**
        Regla para calculo de cantidad por bulto
    */

    function reglaCalculoPorBulto(param){
        var contar=0;
        var cantidad_pedida=param.cantidad || 0;
        var i=0;
        var bulto=0; 
        var cantidad_bulto = param.cantidad_bulto;

        while(i<cantidad_pedida){
            if(i%cantidad_bulto==0){
                bulto++;
            } 
            i+=1;
        }  

        return bulto;
    }

    /**
    * Esta funcion permite cargar los item en la tabla.
    **/

    function addTabla(cliente,codigo, descripcion, cantidad, preciosiniva, descuento, montodescuento, totalsiniva, iva, piva, totalconiva, almacen, producto,ubicacion, cuota){
        ContarInputItem += 1;
        
        var foto = (_.str.isBlank(data.productoSeleccionado.foto)) ? "sin_imagen.jpg" : data.productoSeleccionado.foto;
        var descuento_ = 0;
        var campos = "";
        descuento_ = (descuento>0) ? descuento * 100 : descuento;
        
        var cantidad_bulto = parseFloat(data.productoSeleccionado.cantidad_bulto);
        var unidad_empaque_descripcion =data.productoSeleccionado.unidad_empaque;

        if(_.isNaN(cantidad_bulto)){
            alert("Debe verificar la cantidad por "+unidad_empaque_descripcion);
            return false;
        }

        if(cantidad_bulto<=0){
            alert("Debe especificar la cantidad por "+unidad_empaque_descripcion);
            return false;
        }

        var cantidad_bultos_totales = reglaCalculoPorBulto({
                                        "cantidad":       cantidad,
                                        "cantidad_bulto": cantidad_bulto
                                      });

        var porcentaje_ganancia_x_item = 0;

        var costo_actual = parseFloat(data.productoSeleccionado.costo_actual);

        var preciosiniva = getTipoPrecio();

        var ganancia_item_individual = ((preciosiniva-costo_actual)*cantidad)-montodescuento;

        var porcentaje_ganancia = ganancia_item_individual*100/ ((preciosiniva*cantidad)-montodescuento);
        var cantidad_bulto_kilos = data.productoSeleccionado.kilos_bulto;

        porcentaje_ganancia = porcentaje_ganancia.toFixed(2);

        $("#foto-item-tmp").attr("src","../../imagenes/sin_imagen.jpg");

        campos += $.inputHidden("_cod_item_precompromiso",ContarInputItem,"[]");
        campos += $.inputHidden("_item_codigo",codigo,"[]");
        campos += $.inputHidden("_item_codigo_producto",producto,"[]");
        campos += $.inputHidden("_item_almacen",almacen,"[]");
        campos += $.inputHidden("_item_ubicacion",ubicacion,"[]");
        campos += $.inputHidden("_item_cantidad",cantidad,"[]");
        campos += $.inputHidden("_item_preciosiniva",parseFloat(preciosiniva).toFixed(2),"[]");
        campos += $.inputHidden("_item_descuento",descuento_,"[]");
        campos += $.inputHidden("_item_montodescuento",montodescuento,"[]");
        campos += $.inputHidden("_item_totalsiniva",totalsiniva,"[]");
        campos += $.inputHidden("_item_piva",parseFloat(iva).toFixed(2),"[]");
        campos += $.inputHidden("_item_iva",piva,"[]");
        campos += $.inputHidden("_item_totalconiva",totalconiva.toFixed(2),"[]");
        campos += $.inputHidden("_item_descripcion",descripcion,"[]");
        campos += $.inputHidden("_id_cuota",cuota,"[]");/*Adicionado como requisito especifico del CCP*/
        campos += $.inputHidden("_id_cantidad_bulto",data.productoSeleccionado.cantidad_bulto,"[]");/*Adicionado como requisito especifico del CCP*/
        campos += $.inputHidden("_id_costo_actual",costo_actual,"[]");

        campos += $.inputHidden("_cantidad_bulto",cantidad_bultos_totales,"[]"); // Cantidad por bulto.
        campos += $.inputHidden("_cantidad_bulto_kilos",cantidad_bulto_kilos,"[]"); // Cantidad por bulto.
        campos += $.inputHidden("_unidad_empaque",unidad_empaque_descripcion,"[]");
        campos += $.inputHidden("_ganancia_item_individual",ganancia_item_individual,"[]");
        campos += $.inputHidden("_porcentaje_ganancia",porcentaje_ganancia,"[]");

        var totalm3 = data.productoSeleccionado.cubi4;
        var totalft3 = data.productoSeleccionado.cubi5;
        var peso_total_item = parseFloat(cantidad)/parseFloat(data.productoSeleccionado.cantidad_bulto) * parseFloat(data.productoSeleccionado.kilos_bulto);

        campos += $.inputHidden("_peso_total_item",peso_total_item,"[]");
        campos += $.inputHidden("_totalm3",totalm3,"[]");
        campos += $.inputHidden("_totalft3",totalft3,"[]");

        //alert(campos);return false;
        if (data.productoSeleccionado.cod_item_forma == 1) { // Si es un Producto
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=precomprometeritem&v1="+codigo+"&cpedido="+cantidad+"&codalmacen="+almacen+"&ubicacion="+ubicacion+"&codprecompromiso="+ContarInputItem+"&cliente="+cliente,//+"&tipo_transaccion="+transaccion,
                beforeSend: function(){

                },
                success: function(data){                   
                   // alert(data);
                    result = eval(data);

                    if(result[0].id=="-99"){
                        Ext.MessageBox.show({
                            title: 'Notificaci&oacute;n',
                            msg: result[0].observacion,
                            buttons: Ext.MessageBox.OK,
                            icon: 'ext-mb-warning'
                        });
                        $("#cod_almacen").trigger("change");
                        return false;
                    }
                    if(result[0].id=="-98"){
                        Ext.MessageBox.show({
                            title: 'Notificaci&oacute;n',
                            msg: result[0].observacion,
                            buttons: Ext.MessageBox.OK,
                            icon: 'ext-mb-warning'
                        });
                        $("#cod_almacen").trigger("change");
                        return false;
                    }
                    if(data!="-1"){
                        campos += '<input type="hidden" name="_pitem_almacen" value="'+almacen+'">';
                        campos += '<input type="hidden" name="_idpitem_almacen" value="'+data+'">';
                    }else{
                        campos += '<input type="hidden" name="_pitem_almacen" value="">';
                        campos += '<input type="hidden" name="_idpitem_almacen" value="">';
                    }

                    html  = "<tr>";
                    html += "<td><img src=\"../../imagenes/"+foto+"\" width=\"50\" align=\"absmiddle\" height=\"50\"/></td>";
                    html += "<td title=\"Haga click aqu&iacute; para ver detalles\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white; text-align: center;\" href=\"#info\">"+producto+"</a><input type='hidden' name='idItem_' value='"+codigo+"'/></td>";
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+descripcion+"</td>";
                    
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+unidad_empaque_descripcion+"</td>";
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+cantidad_bulto+"</td>";
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+porcentaje_ganancia+"</td>";

                    html += "<td style='text-align: right; padding-right: 20px;' class='cantidad' rel='"+cantidad+"'>"+cantidad+"</td>";

                    html += "<td style='text-align: right; padding-right: 20px;' class='cantidad_bultos_totales' rel='"+cantidad_bultos_totales+"'>"+cantidad_bultos_totales+"</td>";

                    html += "<td style='text-align: right; padding-right: 20px;' class='preciosiniva'>"+parseFloat(preciosiniva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' class='montodescuento'>"+parseFloat(montodescuento).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(descuento_).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' class='totalsiniva'>"+parseFloat(totalsiniva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' title='"+$("#moneda").val()+" "+piva.toFixed(3)+"'>"+parseFloat(iva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' class='piva' rel='"+parseFloat(piva).toFixed(3)+"'>"+totalconiva.toFixed(3)+"</td>";
                    //html += "<td style='text-align: center;'><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
                     html += "<td style='text-align: center;' class=\"eliminar_precomp\"><input type='hidden' name='idItem1_' value='"+codigo+"'/><input type='hidden' name='cod_pre' value='"+ContarInputItem+"'/><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
                    html += "</tr>";
                    $(".grid table.lista tbody").append(html);
                    $("#MostrarTabla").trigger("click");
                    fn_cantidad();
                    $.limpiarCamposFiltro();

                }
            });
        }else{
            //total=parseFloat(totalsiniva)+parseFloat(piva);
            campos += '<input type="hidden" name="_pitem_almacen" value="">';
            campos += '<input type="hidden" name="_idpitem_almacen" value="">';

            html  = "<tr>";
            html += "<td title=\"Haga click aqu&iacute; para ver detalles\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white; text-align: center;\" href=\"#info\">"+producto+"</a><input type='hidden' name='idItem_' value='"+codigo+"'/></td>";
            html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+descripcion+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' rel='"+cantidad+"'>"+cantidad+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(preciosiniva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(descuento).toFixed(3)+"</td>";//antes solo descuento
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(montodescuento).toFixed(3)+"</td>";//antes solo montodescuento
            html += "<td style='text-align: right; padding-right: 20px;' class='totalsiniva'>"+parseFloat(totalsiniva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' title='"+$("#moneda").val()+" "+parseFloat(piva).toFixed(3)+"'>"+parseFloat(iva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' class='piva' rel='"+parseFloat(piva).toFixed(3)+"'>"+totalconiva.toFixed(3)+"</td>";
            //html += "<td style='text-align: center;'><img style=\"cursor: pointer;\" class=\"eliminar\" title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
             html += "<td style='text-align: center;' class=\"eliminar_precomp\"><input type='hidden' name='idItem1_' value='"+codigo+"'/><input type='hidden' name='cod_pre' value='"+ContarInputItem+"'/><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
            html += "</tr>";
            $(".grid table.lista tbody").append(html);
            $("#MostrarTabla").trigger("click");
            fn_cantidad();
        }
    }
    /**
     * Este es el evento click del boton incluir (item)
     **/

    $("#seleccionarPedido").live("click",function(){
        if(confirm('Incluir Pedido') == false){
            return false;
        }
        codigo = $(this).children().val();
        //codigo_producto = $("#cod_item").val();
        $("#pedido_seleccionado").val(codigo);
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=seleccionarPedidoPendiente&id_pedido="+codigo,
            success: function(response){
                resultado=eval(response);
                i=0;
                while(resultado.length)
                {
                    
                    iva=(resultado[i]._item_piva!=0)&&(resultado[i]._item_piva!='')?parseFloat(resultado[i]._item_piva):0;
                    
                    data.productoSeleccionado = resultado[i];
                    var cliente=0
                    addTabla(cliente,resultado[i].id_item,resultado[i]._item_descripcion,resultado[i]._item_cantidad,resultado[i]._item_preciosiniva,resultado[i]._item_descuento,resultado[i]._item_montodescuento,resultado[i]._item_totalsiniva,iva,resultado[i]._item_preciosiniva*resultado[i]._item_cantidad*iva/100,parseFloat(resultado[i]._item_totalconiva),resultado[i]._item_almacen,resultado[i].cod_item);
                    // addTabla(codigo,descripcion,cantidad,preciosiniva,descuento,montodescuento,totalsiniva,iva,piva,totalconiva,almacen,producto);
                    i=i+1;
                }
            //fn_cantidad();
            }
        });
        desplegarOcultarPanelItem();
        $("#lista_pedidos").hide();
        return 0;
    });
    $("#seleccionarNotaEntrega").live("click",function(){
        if(confirm('Incluir Nota Entrega') == false){
            return false;
        }
        codigo = $(this).children().val();
        $("#nota_entrega_seleccionada").val(codigo);
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=seleccionarNotaEntregaPendiente&id_nota="+codigo,
            success: function(data){
                resultado=eval(data);
                i=0;
                while(resultado.length)
                {
                    iva=(resultado[i]._item_piva!=0)&&(resultado[i]._item_piva!='')?parseFloat(resultado[i]._item_piva):0;
                    addTabla(resultado[i].id_item,resultado[i]._item_descripcion,resultado[i]._item_cantidad,resultado[i]._item_preciosiniva,resultado[i]._item_descuento,resultado[i]._item_montodescuento,resultado[i]._item_totalsiniva,iva,resultado[i]._item_preciosiniva*resultado[i]._item_cantidad*iva/100,parseFloat(resultado[i]._item_totalconiva),resultado[i]._item_almacen,resultado[i].cod_item);
                    i=i+1;
                }
            //fn_cantidad();
            }
        });
        desplegarOcultarPanelItem();
        $("#lista_notas_entrega").hide();
        return 0;
    });
    $("#seleccionarCotizacion").live("click",function(){
        if(confirm('Incluir Cotizacion') == false){
            return false;
        }
        codigo = $(this).children().val();
        $("#cotizacion_seleccionada").val(codigo);
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=seleccionarCotizacionPendiente&id_cotizacion="+codigo,
            success: function(data){
                resultado=eval(data);
                i=0;
                while(resultado.length)
                {
                    iva=(resultado[i]._item_piva!=0)&&(resultado[i]._item_piva!='')?parseFloat(resultado[i]._item_piva):0;
                    var cliente=0
                    addTabla(cliente,resultado[i].id_item,resultado[i]._item_descripcion,resultado[i]._item_cantidad,resultado[i]._item_preciosiniva,resultado[i]._item_descuento,resultado[i]._item_montodescuento,resultado[i]._item_totalsiniva,iva,resultado[i]._item_preciosiniva*resultado[i]._item_cantidad*iva/100,parseFloat(resultado[i]._item_totalconiva),resultado[i]._item_almacen,resultado[i].cod_item);
                    i=i+1;
                }
            }
        });
        desplegarOcultarPanelItem();
        $("#lista_cotizaciones").hide();
        return 0;
    });
    $("#incluir_cuotas").live("click", function(){
        if(confirm('Incluir Cuota') == false){
            return false;
        }
        var cuotas_seleccionadas = [];
        $("input[type=checkbox]:checked#cuotas_seleccionadas").each(function (){
            cuotas_seleccionadas.push($(this).val());
            $(this).removeAttr("checked");
        });
        //alert(cuotas_seleccionadas);//Aqui tengo el array
        /*Stringify the javascript object (json) with*/
        var c = JSON.stringify(cuotas_seleccionadas);

        $.post("../../libs/php/ajax/ajax.php", {
            "opt": "ponerCuotaPagada",
            "cliente": $("#id_cliente").val(),
            cuotas:c
        },
        function(data){
            var resultado = eval(data);
            var i = 0;
            while(resultado.length){
                var iva = 0;//(resultado[i]._item_piva!=0 && resultado[i]._item_piva!='') ? parseFloat(resultado[i]._item_piva) : 0;
                var descripcion = resultado[i].descripcion + " " + resultado[i].anio + "-" + (resultado[i].mes < 10 ? "0" + resultado[i].mes : resultado[i].mes);
                var cantidad = 1;//Siempre 1
                var descuento = 0;//Siempre 0
                var montodescuento = 0;//Siempre 0
                var preciosiniva = resultado[i].precio;
                var totalsiniva = resultado[i].precio;//Siempre es cantidad = 1
                var totalconniva = 0;
                var almacen = 0;
                var cliente = 0;
                addTabla(cliente,resultado[i].id_item, descripcion, cantidad, resultado[i].precio, descuento, montodescuento, totalsiniva, iva, preciosiniva*cantidad*iva/100, parseFloat(totalconniva), almacen, resultado[i].cod_item, resultado[i].id);
                i=i+1;
            }
        }, "json");
        desplegarOcultarPanelItem();
        $("#lista_cuotas").hide();
        return 0;
    });
    /*
    * Este ajax carga el tipo de precio que aplica para el cliente. Esto se especifica en los datos del cliente.
    */
    $.ajax({
        type: "GET",
        url:  "../../libs/php/ajax/ajax.php",
        data: "opt=DetalleCliente&v1="+$("input[name='id_cliente']").val(),
        beforeSend: function(){
        // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
        },
        success: function(data){
            datoscliente = eval(data);
            $("input[name='DatosCliente']").val(data);
            preciolibre = $("#idpreciolibre").val();
            if (datoscliente[0].cod_tipo_precio != preciolibre ){
                $("#cod_tipo_precio").attr("disabled","disabled");
            }else{
                $("#precioProductoPedido").removeAttr("readonly");
            }
        }
    });

    function desplegarOcultarPanelItem(){
        if($("#LabelMensaje").html()=="Agregar Nuevo Item"){
            $("#LabelMensaje").html("Ocultar Panel");
            $("#ImgMensaje").attr("src","../../libs/imagenes/drop-add2.gif");
            $("#PanelFactura").show();
        }else{
            $("#LabelMensaje").html("Agregar Nuevo Item");
            $("#ImgMensaje").attr("src","../../libs/imagenes/drop-add.gif");
            $("#cancelaradd").trigger("click");
            $("#PanelFactura").hide();
        }
    }
    /**
    * Este evento permite desplegar u ocultar el panel para agregar el item.
    **/
    $("#MostrarTabla").click(function(){
        /*valor = $("#LabelMensaje").html();
        if(valor=='Agregar Nuevo Item'){
            $("#LabelMensaje").html("Ocultar Panel");
            $("#ImgMensaje").attr("src","../../libs/imagenes/drop-add2.gif");
            $("#PanelFactura").show();
        }else{
            $("#LabelMensaje").html("Agregar Nuevo Item");
            $("#ImgMensaje").attr("src","../../libs/imagenes/drop-add.gif");
            $("#cancelaradd").trigger("click");
            $("#PanelFactura").hide();
        }*/
        desplegarOcultarPanelItem();
    });
    /**
    * Este evento permite cancelar o limpiar el panel del item a incluir.
    **/
    $("#cancelaradd").click(function(){
        $("input[name='totalPedido'],input[name='montodescuentoPedido'],input[name='descuentoPedido'],input[name='precioProductoPedido'],input[name='cantidadPedido']").val("0");
        //      $("input[name='cod_item_forma'], input[name='id_item']").removeAttr("disabled");
        //      $("input[name='id_item']").find("option").remove();
        $("input[name='cod_item_forma']").val("");
        /*$("#descripcion_tipo_forma").html("Item");
        $("#descripcion_tipo_forma").attr("style", "font-family:'Verdana'; font-weight: bold;");*/
        $("#descripcion_tipo_forma").attr("style", "font-family:'Verdana';");
        $("#descripcion_item").html("");
        $("input[name='cod_item_forma'], input[name='id_item']").val("");
        /*$("#descripcion_tipo_forma").html("Item");
        $("#descripcion_tipo_forma").attr("style", "font-family:'Verdana'; font-weight: bold;");*/
        $("#cod_almacen").val("");
        $("#descripcion_item").val("");
        $("#cod_almacen").removeAttr("disabled");
        $("#LabelDetalleItem").html("");
        $("#informacionitem").val("");
        $("#fila_precio1").val("");
        $("#fila_precio2").val("");
        $("#fila_precio3").val("");
        //$("#LabelCantidadExistente").html("");
        $("#fila_precio1_iva").val("");
        $("#fila_precio2_iva").val("");
        $("#fila_precio3_iva").val("");
        $("#cod_almacen").find("option").remove();
        $("#cantidadItem, #cantidadTotalItem,#cantidadItemComprometidoByAlmacen,").val("");
        // AÃ±adido 10-06-2013 luego de modificar la funcion $("#LabelMensaje").click(function(){} para acceder rapidamente a la ventana de busqueda de Items.
        $("#LabelMensaje").html("Agregar Nuevo Item");
        $("#ImgMensaje").attr("src","../../libs/imagenes/drop-add.gif");
        $("#PanelFactura").hide();
    });
    $("#cantidadPedido").blur(function(){
        //descuentopedido = parseFloat($("#descuentoPedido").val());
        //$("#montodescuentoPedido").val("0");
        if($(this).val()==''){
            $(this).val("0")
            $("#totalPedido").val("0");
            $("#descuentoPedido").val("0");
            $("#montodescuentoPedido").val("0");
            return false;
        }
        $("#descuentoPedido").trigger("blur");
        if($("#cantidadPedido").val()==0){
            $("#totalPedido").val(parseFloat(0));
            $("#descuentoPedido").val("0");
            $("#montodescuentoPedido").val("0");
            return false;
        }
        $(this).val(parseFloat($(this).val()));
        cantidad = parseFloat($(this).val());
        if ($("input[name='cod_item_forma']").val() == 1) { // Si es igual al Producto
            cantidadActual = $("#cantidadItem").val();
            if (cantidad > cantidadActual) {
                Ext.MessageBox.show({
                    title: 'Notificaci&oacute;n',
                    msg: "Disculpe, la cantidad pedida es mayor que la existente, verifique existencia.",
                    buttons: Ext.MessageBox.OK,
                    animEl: 'cantidadPedido',
                    icon: 'ext-mb-warning'
                });
                $(this).val("0").focus();
                return false;
            }
        }
        if (cantidad == 0) {
            $("#totalPedido").val(parseFloat(0));
            $("#descuentoPedido").val("0");
            $("#montodescuentoPedido").val("0");
            return false;
        }
        else {
            porcentaje  = $("#descuentoPedido").val();
            precioitem = $("#precioProductoPedido").val();
            descuento = parseFloat(porcentaje/100) * parseFloat(precioitem);
            total = parseFloat(cantidad) * parseFloat($("#precioProductoPedido").val()-parseFloat(descuento));
            $("#totalPedido").val(parseFloat(total.toFixed(3)));
        }
    }).click(function(){
        if($(this).val()==''){
            $(this).val("0");
            $("#totalPedido").val("0");
            $("#descuentoPedido").val("0");
            $("#montodescuentoPedido").val("0");
            return false;
        }
        porcentaje  = $("#descuentoPedido").val();
        precioitem = $("#precioProductoPedido").val();
        descuento = parseFloat(porcentaje/100) * parseFloat(precioitem);
        total = parseFloat($(this).val()) * parseFloat($("#precioProductoPedido").val()-parseFloat(descuento));
        $("#totalPedido").val(parseFloat(total.toFixed(3)));
    });
    $("#descuentoPedido").blur(function(){
        datoscliente = eval($("input[name='DatosCliente']").val());
        if($(this).val()==''){
            $(this).val("0");
        }
        porcentaje = $(this).val();
        if (parseFloat(porcentaje) > parseFloat(datoscliente[0].porc_parcial)) {
            Ext.MessageBox.show({
                title: 'NotificaciÃƒÂ³n',
                msg: "El porcentaje no puede ser mayor al limite de cliente",
                buttons: Ext.MessageBox.OK,
                animEl: 'descuentoPedido',
                icon: 'ext-mb-warning'
            });
            $(this).val("0");
            porcentaje = 0;
        }
        cantidad = $("#cantidadPedido").val();
        precioitem = $("#precioProductoPedido").val();
        descuento = parseFloat(porcentaje) * (parseFloat(precioitem) * parseFloat(cantidad)) / 100 ;
        total = parseFloat(precioitem) * parseFloat(cantidad) - descuento;
        $("#montodescuentoPedido").val(descuento.toFixed(3));//antes solo descuento
        $("#totalPedido").val(total.toFixed(3));
    });

    $("#cod_tipo_precio").change(function(){
        valor = $(this).val();
        switch(valor){
            case $("#idpreciolibre").val():
                $("#precioProductoPedido").removeAttr("readonly");
                $("#precioProductoPedido").trigger("blur");
                break;
            case $("#idprecio1").val():
                precio = $("#fila_precio1").val();
                $("#precioProductoPedido").val(precio);
                $("#precioProductoPedido").attr("readonly","readonly");
                break;
            case $("#idprecio2").val():
                precio = $("#fila_precio2").val();
                $("#precioProductoPedido").val(precio);
                $("#precioProductoPedido").attr("readonly","readonly");
                break;
            case $("#idprecio3").val():
                precio = $("#fila_precio3").val();
                $("#precioProductoPedido").val(precio);
                $("#precioProductoPedido").attr("readonly","readonly");
                break;
        }
        $("#cantidadPedido").trigger("blur");
    });

    $("#precioProductoPedido").blur(function(){
        if($(this).val()==''){
            $(this).val("0");
        }
        $(this).val(parseFloat($(this).val()));

        $("#cantidadPedido").trigger("blur");
    });
    /*
     * Funcion que Instancia a la ventana de busqueda de items (Productos y servicios).
     * Paquete pBuscarItem en buscar_productos_Servicios.js
     */
    $("#LabelMensaje").click(function(){
        /*Creado para sustituir $("#seleccionItem").click(function(){}); abajo*/
        if($(this).html() == "Agregar Nuevo Item"){
            pBuscaItem.main.mostrarWin();
        }
    });
    /*$("#seleccionItem").click(function(){
            pBuscaItem.main.mostrarWin();
    });*/


    $("body").keypress(function(ev){
        var teclaTabMasP  = 402;
        var teclaTabMasL  = 32;
        var codeCurrent = (ev.keyCode == 0) ? ev.charCode : ev.keyCode;



        if( teclaTabMasL == codeCurrent) {
            $.limpiarCamposFiltro();
        }

        if(teclaTabMasP == codeCurrent){
            pBuscaItem.main.mostrarWin();
            return false;
        }
    });

    $.limpiarCamposFiltro = function () {
        $("input[name='filtro_descripcion']").val("");
        $("input[name='filtro_referencia']").val("");
        $("input[name='filtro_descripcion']").val("");
        $("input[name='filtro_unidad']").val("");
        $("input[name='filtro_referencia']").val("");
        $("input[name='filtro_bulto']").val("0.00");
        $("input[name='filtro_descuento']").val("0.00");
        $("input[name='filtro_precio']").val("0.00");
        $("input[name='filtro_importe']").val("0.00");
        $("input[name='filtro_kilos']").val("0.00");
        $("input[name='filtro_bultos_total']").val("0");
        
        $("input[name='filtro_cantidad']").val("0");
        data.productoSeleccionado = {};
        $("#informacionitem").val("");
        $("input[name='filtro_codigo']").val("").focus();
    }


    $.filtrarArticulo = function(value, tipoFiltro){
            var switchFiltro = tipoFiltro || "filtroItem"; // para el caso de pedido es: filtroItemByRCCB
            pBuscaItem.main.storeProductos.baseParams.opt = switchFiltro;
            pBuscaItem.main.storeProductos.baseParams.limit = pBuscaItem.main.limitePaginacion;
            pBuscaItem.main.storeProductos.baseParams.start = pBuscaItem.main.iniciar;
            pBuscaItem.main.storeProductos.baseParams.cmb_tipo_item = 1;//productos
            pBuscaItem.main.storeProductos.baseParams.codigoProducto = value;
            pBuscaItem.main.storeProductos.baseParams.BuscarBy = true;
            
            pBuscaItem.main.storeProductos.load({ 
                callback: function(records, operation, success) { 
                    if (success) {
                        if(records.length > 1){
                            pBuscaItem.main.mostrarWin();
                        } else if(records.length == 1){
                            data.productoSeleccionado = records[0].json;
                            $("#informacionitem").val(JSON.stringify(records[0].json));

                            if( !_.str.isBlank(data.productoSeleccionado.foto)) {
                                $("#foto-item-tmp").attr("src","../../imagenes/"+data.productoSeleccionado.foto);
                            }
                            
                            // data.productoSeleccionado.foto1
                            $("input[name='filtro_descripcion']").val(data.productoSeleccionado.descripcion1);
                            $("input[name='filtro_referencia']").val(data.productoSeleccionado.referencia);
                            $("input[name='filtro_unidad']").val(data.productoSeleccionado.unidad_empaque);
                            $("input[name='filtro_bulto']").val(data.productoSeleccionado.cantidad_bulto);
                            $("input[name='filtro_kilos']").val(data.productoSeleccionado.kilos_bulto);
                            $("input[name='filtro_precio']").val(data.productoSeleccionado.precio1);
                            $("input[name='filtro_importe']").val("0.00");
                            $("input[name='filtro_cantidad']").focus();
                            $("input[name='filtro_cantidad']").select();
                        } else {
                            $.mensajeNotificacion({mensaje:'No se encontraron productos asociados'});
                            $.limpiarCamposFiltro();
                            data.productoSeleccionado = {};
                        } 
                    } 
                } 
            });
    };

    $("input[name='filtro_codigo']").keypress(function(ev){
        var teclaTabMasP  = 13;
        var codeCurrent = ev.keyCode;
        var value = $(this).val();
        if(teclaTabMasP == codeCurrent){ 
            if(_.str.isBlank(value)) { 
                pBuscaItem.main.mostrarWin();
                return false;
            }

            $.filtrarArticulo(value, "filtroItemByRCCB");

            return false;
        }
    });
    
    $.validarCantidad = function(val){
        var value = parseInt(val);
        if(_.isNaN(value)){
            $.mensajeNotificacion({mensaje: "Debe especificar una cantidad valida."});
            value = 0;
        } else if(value==0) {
            $.mensajeNotificacion({mensaje: "La cantidad debe ser mayor a cero (0)"});
            value = 0;
        }
        return value;
    }

    $.calculoImporte = function(){

        if(_.isUndefined(data.productoSeleccionado.id_item)){
                $("input[name='filtro_cantidad']").val(0);
                $("input[name='filtro_codigo']").focus();
                return false;
        }

        var precioProducto = parseFloat($("input[name='filtro_precio']").val());
        var cantidad = parseFloat($("input[name='filtro_cantidad']").val());


        var cantidad_bulto = parseFloat(data.productoSeleccionado.cantidad_bulto);
        var unidad_empaque_descripcion =data.productoSeleccionado.unidad_empaque;

        if(_.isNaN(cantidad_bulto)){
            alert("Debe verificar la cantidad por "+unidad_empaque_descripcion);
            return false;
        }

        if(cantidad_bulto<=0){
            alert("Debe especificar la cantidad por "+unidad_empaque_descripcion);
            return false;
        }

        var cantidad_bultos_totales = reglaCalculoPorBulto({
                                        "cantidad":       cantidad,
                                        "cantidad_bulto": cantidad_bulto
                                      });

        var total_ = precioProducto*cantidad;
        if(_.isNaN(total_)){
            total_ = "0.00";
            $("input[name='filtro_cantidad']").val(0);
        }
        $("input[name='filtro_importe']").val(total_.toFixed(3));
        $("input[name='filtro_bultos_total']").val(cantidad_bultos_totales);
    }

    $.verificarExistenciaEnAlmacen = function(value){
        var cantidad_solicitada = parseInt($("input[name='filtro_cantidad']").val());
            if(_.isUndefined(data.productoSeleccionado.id_item)){
                $("input[name='filtro_cantidad']").val(0);
                $("input[name='filtro_codigo']").focus();
                return false;
            }

            if(_.isNaN(cantidad_solicitada)){
                $.mensajeNotificacion({mensaje: "Debe especificar una cantidad valida."});
                return false;
            } else if(value==0) {
                $.mensajeNotificacion({mensaje: "La cantidad debe ser mayor a cero (0)"});
                return false;
            }

            $.existenciaPorArticulo({
                id_item: data.productoSeleccionado.id_item,
                fcallback: function(resultado){
                    var cantidad_existente = parseFloat(resultado[0].cantidad);
                    var cod_almacen = parseFloat(resultado[0].cod_almacen);

                    var pregunta_valida_contra_stock = $("select[name='validar_stock'] :selected").val();
                    if(pregunta_valida_contra_stock=="si"){
                        if(cantidad_solicitada>cantidad_existente){
                            $.mensajeNotificacion({
                                mensaje: "El producto actual no posee la cantidad solicitada, disponibilidad actual: "+cantidad_existente
                            });
                            return false;
                        }
                    }
                    
                    $.calculoImporte();
                    $("input[name='filtro_descuento']").focus();
                }
            });
    };

    $("input[name='filtro_cantidad']").blur(function(ev){
        var value = $(this).val();
        $.verificarExistenciaEnAlmacen(value);
    });

    $("input[name='filtro_cantidad']").keypress(function(ev){
        var teclaTabMasP  = 13;
        var codeCurrent = ev.keyCode;
        var value = $(this).val();
        if(teclaTabMasP == codeCurrent){
            var self = this;
            
            $.verificarExistenciaEnAlmacen(value);
        }
    });


    $("input[name='filtro_descuento']").keypress(function(ev){
        var teclaTabMasP  = 13;
        var codeCurrent = ev.keyCode;
        var value = $(this).val();
        if(teclaTabMasP == codeCurrent){
            if(_.str.isBlank(value)){
                $("input[name='filtro_descuento']").val(0);
            }

            if(_.isUndefined(data.productoSeleccionado.id_item)){
                $("input[name='filtro_codigo']").focus();
                return false;
            }
            $.agregarArticulo();
        }
    });
     
    $.mensajeNotificacion = function(param){
        Ext.MessageBox.show({
            title: 'Notificaci&oacute;n',
            msg: param.mensaje,
            buttons: Ext.MessageBox.OK
        });
    
    }

    $.existenciaPorArticulo = function(parametros){
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=ExistenciaProductoAlmacenDefaultByIdItem&v1="+parametros.id_item,
            beforeSend: function(){
            //$("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
            },
            success: function(data){
                var resultado = eval(data);
                if(resultado[0].id=="-1"){
                    Ext.MessageBox.show({
                        title: 'Notificaci&oacute;n',
                        msg: "Verifique existencia.",
                        buttons: Ext.MessageBox.OK,
                        animEl: 'addTabla',
                        icon: 'ext-mb-warning'
                    });
                    return false;
                }else{
                    parametros.fcallback(resultado);
                }//Fin de if(resultado[0].id=="-1")
            }
        });
    }

    $("input.input-gastos").numeric();
    $("input[name='copias']").numeric();
});

$.agregarArticuloCesta = function(records){
    
        data.productoSeleccionado = records;
        var dataitem = JSON.parse($("#informacionitem").val());
       

        if(dataitem==undefined){
            Ext.MessageBox.show({
                title: 'Notificaci&oacute;n',
                msg: "Debe cargar un producto o servicio",
                buttons: Ext.MessageBox.OK,
                animEl: 'addTabla',
                icon: 'ext-mb-warning'
            });
            return false;
        }
        
        codigo = dataitem.id_item;
        producto = dataitem.cod_item;//codigo_barras
        descripcion = dataitem.descripcion1;
        cantidad = $("input[name='filtro_cantidad']").val();
        

        /*if(parseFloat(cantidad)==0){
            Ext.MessageBox.show({
                title: 'Notificaci&oacute;n',
                msg: "Debe especificar la cantidad!",
                buttons: Ext.MessageBox.OK
            });
            $("#filtro_cantidad").focus();
            return false
        }
        cantidad = parseFloat(cantidad);*/
        var preciosiniva="";
        var tipo_precio = parseInt($("input[name='precio_por_defecto']").val());
        var preciosiniva = 0;

        var p = {
            precio1: 1,
            precio2: 2,
            precio3: 3,
        };
        
        /*
        switch(tipo_precio){
            case p.precio1:
                preciosiniva = parseFloat(data.productoSeleccionado.precio1);
                break;
            case p.precio2:
                preciosiniva = parseFloat(data.productoSeleccionado.precio2);
                break;
            case p.precio3:
                preciosiniva = parseFloat(data.productoSeleccionado.precio3);
                break;            
        }*/
         if(datoscliente[0].cod_tipo_cliente!=4){
            preciosiniva = parseFloat(data.productoSeleccionado.precio1);
        }

        if(datoscliente[0].cod_tipo_cliente==4){
            preciosiniva = parseFloat(data.productoSeleccionado.precio2);
        }

        //

        if(preciosiniva==0){
            Ext.MessageBox.show({
                title: 'Notificaci&oacute;n',
                msg: 'El campo precio sin Iva debe ser distinto de cero (0)!',
                buttons: Ext.MessageBox.OK
            });
            return false;
        }
        var restarDescuento = false;
        descuento = $("input[name='filtro_descuento']").val();
        if(!_.str.isBlank(descuento)){
            if(descuento.indexOf("%")>=0){
                if(_.isNaN(parseFloat(descuento))){
                    Ext.MessageBox.show({
                        title: 'Notificaci&oacute;n',
                        msg: 'El valor del descuento es invalido, verifique!',
                        buttons: Ext.MessageBox.OK
                    });
                    return false;
                }
                descuento = parseFloat(descuento) / 100;
            } else {
                restarDescuento = true;
            }
        } else {
            descuento = 0;
        }

        if(descuento>0 || restarDescuento==true){
            if(restarDescuento){
                montodescuento  = descuento;
                descuento = 0;
            }else{
                montodescuento  = parseFloat((( preciosiniva * cantidad ) * descuento).toFixed(3));
            }
        } else {
            montodescuento = 0;
        }

        totalsiniva  = ( preciosiniva * cantidad )  - montodescuento;
        
        if(dataitem.monto_exento==1){// es exento
            iva = 0;
            piva = parseFloat(0);
            totalconiva = 0;
        }else{
            iva = dataitem.iva;
            piva = parseFloat((totalsiniva*iva)/100);
            totalconiva = parseFloat(piva) + parseFloat(totalsiniva);
        }
        almacen = parseInt($("input[name='cod_almacen_defecto']").val());
        ubicacion = parseInt($("input[name='cod_ubicacion_defecto']").val());
        cliente = parseInt($("input[name='id_cliente']").val());
        cuota=0;
        addTabla2(records, cliente,codigo,descripcion,cantidad,preciosiniva,descuento,montodescuento,totalsiniva,iva,piva,totalconiva,almacen,producto,ubicacion);
    };

     /**
    * Esta funcion permite cargar los item en la tabla.
    **/

    function addTabla2(records, cliente,codigo, descripcion, cantidad, preciosiniva, descuento, montodescuento, totalsiniva, iva, piva, totalconiva, almacen, producto,ubicacion, cuota){
        ContarInputItem += 1;
        data.productoSeleccionado=records;
       
        var foto = (_.str.isBlank(data.productoSeleccionado.foto)) ? "sin_imagen.jpg" : data.productoSeleccionado.foto;
        var descuento_ = 0;
        var campos = "";
        descuento_ = (descuento>0) ? descuento * 100 : descuento;
        
        var cantidad_bulto = parseFloat(data.productoSeleccionado.cantidad_bulto);
        var unidad_empaque_descripcion =data.productoSeleccionado.unidad_empaque;

        if(_.isNaN(cantidad_bulto)){
            alert("Debe verificar la cantidad por "+unidad_empaque_descripcion);
            return false;
        }

        if(cantidad_bulto<=0){
            alert("Debe especificar la cantidad por "+unidad_empaque_descripcion);
            return false;
        }
        //funcion 
        var contar=0;
        var cantidad_pedida=cantidad || 0;
        var i=0;
        var bulto=0; 
       

        while(i<cantidad_pedida){
            if(i%cantidad_bulto==0){
                bulto++;
            } 
            i+=1;
        }

        var cantidad_bultos_totales = bulto;

        var porcentaje_ganancia_x_item = 0;

        var costo_actual = parseFloat(data.productoSeleccionado.costo_actual);

        //funcion
        var tipo_precio = parseInt($("input[name='precio_por_defecto']").val());
        var preciosiniva = 0;

        var p = {
            precio1: 1,
            precio2: 2,
            precio3: 3,
        };
        
        switch(tipo_precio){
            case p.precio1:
                preciosiniva = parseFloat(data.productoSeleccionado.precio1);
                break;
            case p.precio2:
                preciosiniva = parseFloat(data.productoSeleccionado.precio2);
                break;
            case p.precio3:
                preciosiniva = parseFloat(data.productoSeleccionado.precio3);
                break;            
        }

        //
        var ganancia_item_individual = ((preciosiniva-costo_actual)*cantidad)-montodescuento;

        var porcentaje_ganancia = ganancia_item_individual*100/ ((preciosiniva*cantidad)-montodescuento);
        var cantidad_bulto_kilos = data.productoSeleccionado.kilos_bulto;

        porcentaje_ganancia = porcentaje_ganancia.toFixed(2);

        $("#foto-item-tmp").attr("src","../../imagenes/sin_imagen.jpg");

        campos += $.inputHidden("_cod_item_precompromiso",ContarInputItem,"[]");
        campos += $.inputHidden("_item_codigo",codigo,"[]");
        campos += $.inputHidden("_item_codigo_producto",producto,"[]");
        campos += $.inputHidden("_item_almacen",almacen,"[]");
        campos += $.inputHidden("_item_ubicacion",ubicacion,"[]");
        campos += $.inputHidden("_item_cantidad",cantidad,"[]");
        campos += $.inputHidden("_item_preciosiniva",parseFloat(preciosiniva).toFixed(2),"[]");
        campos += $.inputHidden("_item_descuento",descuento_,"[]");
        campos += $.inputHidden("_item_montodescuento",montodescuento,"[]");
        campos += $.inputHidden("_item_totalsiniva",totalsiniva,"[]");
        campos += $.inputHidden("_item_piva",parseFloat(iva).toFixed(2),"[]");
        campos += $.inputHidden("_item_iva",piva,"[]");
        campos += $.inputHidden("_item_totalconiva",totalconiva.toFixed(2),"[]");
        campos += $.inputHidden("_item_descripcion",descripcion,"[]");
        campos += $.inputHidden("_id_cuota",cuota,"[]");/*Adicionado como requisito especifico del CCP*/
        campos += $.inputHidden("_id_cantidad_bulto",data.productoSeleccionado.cantidad_bulto,"[]");/*Adicionado como requisito especifico del CCP*/
        campos += $.inputHidden("_id_costo_actual",costo_actual,"[]");

        campos += $.inputHidden("_cantidad_bulto",cantidad_bultos_totales,"[]"); // Cantidad por bulto.
        campos += $.inputHidden("_cantidad_bulto_kilos",cantidad_bulto_kilos,"[]"); // Cantidad por bulto.
        campos += $.inputHidden("_unidad_empaque",unidad_empaque_descripcion,"[]");
        campos += $.inputHidden("_ganancia_item_individual",ganancia_item_individual,"[]");
        campos += $.inputHidden("_porcentaje_ganancia",porcentaje_ganancia,"[]");

        var totalm3 = data.productoSeleccionado.cubi4;
        var totalft3 = data.productoSeleccionado.cubi5;
        var peso_total_item = parseFloat(cantidad)/parseFloat(data.productoSeleccionado.cantidad_bulto) * parseFloat(data.productoSeleccionado.kilos_bulto);

        campos += $.inputHidden("_peso_total_item",peso_total_item,"[]");
        campos += $.inputHidden("_totalm3",totalm3,"[]");
        campos += $.inputHidden("_totalft3",totalft3,"[]");

        //alert(campos);return false;
        if (data.productoSeleccionado.cod_item_forma == 1) { // Si es un Producto
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=precomprometeritem&v1="+codigo+"&cpedido="+cantidad+"&codalmacen="+almacen+"&ubicacion="+ubicacion+"&codprecompromiso="+ContarInputItem+"&cliente="+cliente,//+"&tipo_transaccion="+transaccion,
                beforeSend: function(){

                },
                success: function(data){                   
                   // alert(data);
                    result = eval(data);

                    if(result[0].id=="-99"){
                        Ext.MessageBox.show({
                            title: 'Notificaci&oacute;n',
                            msg: result[0].observacion,
                            buttons: Ext.MessageBox.OK,
                            icon: 'ext-mb-warning'
                        });
                        $("#cod_almacen").trigger("change");
                        return false;
                    }
                    if(result[0].id=="-98"){
                        Ext.MessageBox.show({
                            title: 'Notificaci&oacute;n',
                            msg: result[0].observacion,
                            buttons: Ext.MessageBox.OK,
                            icon: 'ext-mb-warning'
                        });
                        $("#cod_almacen").trigger("change");
                        return false;
                    }
                    if(data!="-1"){
                        campos += '<input type="hidden" name="_pitem_almacen" value="'+almacen+'">';
                        campos += '<input type="hidden" name="_idpitem_almacen" value="'+data+'">';
                    }else{
                        campos += '<input type="hidden" name="_pitem_almacen" value="">';
                        campos += '<input type="hidden" name="_idpitem_almacen" value="">';
                    }

                    html  = "<tr>";
                    html += "<td><img src=\"../../imagenes/"+foto+"\" width=\"50\" align=\"absmiddle\" height=\"50\"/></td>";
                    html += "<td title=\"Haga click aqu&iacute; para ver detalles\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white; text-align: center;\" href=\"#info\">"+producto+"</a><input type='hidden' name='idItem_' value='"+codigo+"'/></td>";
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+descripcion+"</td>";
                    
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+unidad_empaque_descripcion+"</td>";
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+cantidad_bulto+"</td>";
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+porcentaje_ganancia+"</td>";

                    html += "<td style='text-align: right; padding-right: 20px;' class='cantidad' rel='"+cantidad+"'>"+cantidad+"</td>";

                    html += "<td style='text-align: right; padding-right: 20px;' class='cantidad_bultos_totales' rel='"+cantidad_bultos_totales+"'>"+cantidad_bultos_totales+"</td>";

                    html += "<td style='text-align: right; padding-right: 20px;' class='preciosiniva'>"+parseFloat(preciosiniva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' class='montodescuento'>"+parseFloat(montodescuento).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(descuento_).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' class='totalsiniva'>"+parseFloat(totalsiniva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' title='"+$("#moneda").val()+" "+piva.toFixed(3)+"'>"+parseFloat(iva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' class='piva' rel='"+parseFloat(piva).toFixed(3)+"'>"+totalconiva.toFixed(3)+"</td>";
                    //html += "<td style='text-align: center;'><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
                     html += "<td style='text-align: center;' class=\"eliminar_precomp\"><input type='hidden' name='idItem1_' value='"+codigo+"'/><input type='hidden' name='cod_pre' value='"+ContarInputItem+"'/><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
                    html += "</tr>";
                    $(".grid table.lista tbody").append(html);
                    $("#MostrarTabla").trigger("click");
                    ////////////////////////////////////////////
                    //funcion
                    var_montoItemsFactura = 0;
                    var_ivaTotalFactura= 0;
                    var_descuentosItemFactura =0;
                    var_TotalTotalFactura = 0;
                    var_total_costo_actual = 0;
                    var_total_porcentaje_costo_ganancia = 0;
                    var_subTotal = 0;
                    var_cantidad_por_bulto = 0;
                    var_ganancia_total_item = 0;
                    var_porcentaje_ganancia_total_items = 0;

                    var_total_peso = 0;
                    var_totalm3 = 0;
                    var_totalft3 = 0;

                    $(".grid table.lista tbody").find("tr").each(function(){
                        var_subTotal = parseFloat(var_subTotal) +  parseFloat($(this).find("td.cantidad").attr("rel"))*parseFloat($(this).find("td.preciosiniva").text());
                        var_montoItemsFactura = parseFloat(var_montoItemsFactura) + parseFloat($(this).find("td.totalsiniva").text());
                        var_ivaTotalFactura =  parseFloat(var_ivaTotalFactura) + parseFloat($(this).find("td.piva").attr("rel"));
                        var_descuentosItemFactura =  parseFloat(var_descuentosItemFactura) + parseFloat($(this).find("td.montodescuento").html());
                        var_TotalTotalFactura = parseFloat(var_montoItemsFactura) + parseFloat(var_ivaTotalFactura);
                        cantidad_por_bulto = parseInt($(this).find("td").find("input[name='_cantidad_bulto[]']").val());
                        cantidad_items = parseFloat($(this).find("td.cantidad").attr("rel"));
                        tcos_actual = parseFloat($(this).find("td").find("input[name='_id_costo_actual[]']").val());
                        var_ganancia_total_item += parseFloat($(this).find("td").find("input[name='_ganancia_item_individual[]']").val());

                        var_total_peso += parseFloat($(this).find("td").find("input[name='_peso_total_item[]']").val());

                        var_totalm3 += parseFloat($(this).find("td").find("input[name='_totalm3[]']").val());
                        var_totalft3 += parseFloat($(this).find("td").find("input[name='_totalft3[]']").val());

                        if(_.isNaN(tcos_actual)){
                            tcos_actual = 0;
                        }

                        var_cantidad_por_bulto += cantidad_por_bulto;
                        var_total_costo_actual += parseFloat(tcos_actual);

                        var_porcentaje_ganancia_total_items = ((var_ganancia_total_item)*100)/var_montoItemsFactura;
                    });

                    
                    $("#Totalm3").html(var_totalm3);
                    $("#TotalFT3").html(var_totalft3);

                    
                    $("#subTotal").html(var_subTotal.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_subtotal']").attr("value",var_subTotal.toFixed(2));
                    $("#montoItemsFactura").html(var_montoItemsFactura.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_montoItemsFactura']").attr("value",var_montoItemsFactura.toFixed(2));
                    $("#ivaTotalFactura").html(var_ivaTotalFactura.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_ivaTotalFactura']").attr("value",var_ivaTotalFactura.toFixed(2));
                    $("#descuentosItemFactura").html(var_descuentosItemFactura.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_descuentosItemFactura']").attr("value",var_descuentosItemFactura.toFixed(2));
                    $("#TotalTotalFactura").html(var_TotalTotalFactura.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_TotalTotalFactura']").attr("value",var_TotalTotalFactura.toFixed(2));
                    cantidad = $(".grid table.lista tbody").find("tr").length;
                    $(".span_cantidad_items").html("<span style=\"font-size: 15px; font-style: italic;\">Cantidad de Items: "+cantidad+"</span>");
                    $("input[name='input_cantidad_items']").attr("value",cantidad.toFixed(3));
                    $("input[name='input_TotalBultos']").attr("value",var_cantidad_por_bulto);
                    
                    $("#TotalBultos").html(var_cantidad_por_bulto);
                    $("#total_ganancia_monto").html(var_ganancia_total_item);
                    $("#total_ganancia_porcenaje").html(var_porcentaje_ganancia_total_items.toFixed(2));
                    $("#PesoTotal").html(var_total_peso);

                   
                    $("input[name='total_bultos']").val(var_cantidad_por_bulto);
                    $("input[name='peso_total_item']").val(var_total_peso);
                    $("input[name='total_m3']").val(var_totalm3)
                    $("input[name='total_ft3']").val(var_totalft3)

                    $("input[name='total_porcentaje_ganancia']").val(var_porcentaje_ganancia_total_items.toFixed(2))
                    $("input[name='total_monto_ganancia_total']").val(var_ganancia_total_item);

                    $.totalizarFactura();

                    

               
                    ////////////////////////////////////////////
                    $.limpiarCamposFiltro();

                }
            });
        }else{
            //total=parseFloat(totalsiniva)+parseFloat(piva);
            campos += '<input type="hidden" name="_pitem_almacen" value="">';
            campos += '<input type="hidden" name="_idpitem_almacen" value="">';

            html  = "<tr>";
            html += "<td title=\"Haga click aqu&iacute; para ver detalles\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white; text-align: center;\" href=\"#info\">"+producto+"</a><input type='hidden' name='idItem_' value='"+codigo+"'/></td>";
            html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+descripcion+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' rel='"+cantidad+"'>"+cantidad+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(preciosiniva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(descuento).toFixed(3)+"</td>";//antes solo descuento
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(montodescuento).toFixed(3)+"</td>";//antes solo montodescuento
            html += "<td style='text-align: right; padding-right: 20px;' class='totalsiniva'>"+parseFloat(totalsiniva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' title='"+$("#moneda").val()+" "+parseFloat(piva).toFixed(3)+"'>"+parseFloat(iva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' class='piva' rel='"+parseFloat(piva).toFixed(3)+"'>"+totalconiva.toFixed(3)+"</td>";
            //html += "<td style='text-align: center;'><img style=\"cursor: pointer;\" class=\"eliminar\" title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
             html += "<td style='text-align: center;' class=\"eliminar_precomp\"><input type='hidden' name='idItem1_' value='"+codigo+"'/><input type='hidden' name='cod_pre' value='"+ContarInputItem+"'/><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
            html += "</tr>";
            $(".grid table.lista tbody").append(html);
            $("#MostrarTabla").trigger("click");
            var_montoItemsFactura = 0;
                    var_ivaTotalFactura= 0;
                    var_descuentosItemFactura =0;
                    var_TotalTotalFactura = 0;
                    var_total_costo_actual = 0;
                    var_total_porcentaje_costo_ganancia = 0;
                    var_subTotal = 0;
                    var_cantidad_por_bulto = 0;
                    var_ganancia_total_item = 0;
                    var_porcentaje_ganancia_total_items = 0;

                    var_total_peso = 0;
                    var_totalm3 = 0;
                    var_totalft3 = 0;

                    $(".grid table.lista tbody").find("tr").each(function(){
                        var_subTotal = parseFloat(var_subTotal) +  parseFloat($(this).find("td.cantidad").attr("rel"))*parseFloat($(this).find("td.preciosiniva").text());
                        var_montoItemsFactura = parseFloat(var_montoItemsFactura) + parseFloat($(this).find("td.totalsiniva").text());
                        var_ivaTotalFactura =  parseFloat(var_ivaTotalFactura) + parseFloat($(this).find("td.piva").attr("rel"));
                        var_descuentosItemFactura =  parseFloat(var_descuentosItemFactura) + parseFloat($(this).find("td.montodescuento").html());
                        var_TotalTotalFactura = parseFloat(var_montoItemsFactura) + parseFloat(var_ivaTotalFactura);
                        cantidad_por_bulto = parseInt($(this).find("td").find("input[name='_cantidad_bulto[]']").val());
                        cantidad_items = parseFloat($(this).find("td.cantidad").attr("rel"));
                        tcos_actual = parseFloat($(this).find("td").find("input[name='_id_costo_actual[]']").val());
                        var_ganancia_total_item += parseFloat($(this).find("td").find("input[name='_ganancia_item_individual[]']").val());

                        var_total_peso += parseFloat($(this).find("td").find("input[name='_peso_total_item[]']").val());

                        var_totalm3 += parseFloat($(this).find("td").find("input[name='_totalm3[]']").val());
                        var_totalft3 += parseFloat($(this).find("td").find("input[name='_totalft3[]']").val());

                        if(_.isNaN(tcos_actual)){
                            tcos_actual = 0;
                        }

                        var_cantidad_por_bulto += cantidad_por_bulto;
                        var_total_costo_actual += parseFloat(tcos_actual);

                        var_porcentaje_ganancia_total_items = ((var_ganancia_total_item)*100)/var_montoItemsFactura;
                    });

                    
                    $("#Totalm3").html(var_totalm3);
                    $("#TotalFT3").html(var_totalft3);

                    
                    $("#subTotal").html(var_subTotal.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_subtotal']").attr("value",var_subTotal.toFixed(2));
                    $("#montoItemsFactura").html(var_montoItemsFactura.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_montoItemsFactura']").attr("value",var_montoItemsFactura.toFixed(2));
                    $("#ivaTotalFactura").html(var_ivaTotalFactura.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_ivaTotalFactura']").attr("value",var_ivaTotalFactura.toFixed(2));
                    $("#descuentosItemFactura").html(var_descuentosItemFactura.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_descuentosItemFactura']").attr("value",var_descuentosItemFactura.toFixed(2));
                    $("#TotalTotalFactura").html(var_TotalTotalFactura.toFixed(2)+" "+$("#moneda").val());
                    $("input[name='input_TotalTotalFactura']").attr("value",var_TotalTotalFactura.toFixed(2));
                    cantidad = $(".grid table.lista tbody").find("tr").length;
                    $(".span_cantidad_items").html("<span style=\"font-size: 15px; font-style: italic;\">Cantidad de Items: "+cantidad+"</span>");
                    $("input[name='input_cantidad_items']").attr("value",cantidad.toFixed(3));
                    $("input[name='input_TotalBultos']").attr("value",var_cantidad_por_bulto);
                    
                    $("#TotalBultos").html(var_cantidad_por_bulto);
                    $("#total_ganancia_monto").html(var_ganancia_total_item);
                    $("#total_ganancia_porcenaje").html(var_porcentaje_ganancia_total_items.toFixed(2));
                    $("#PesoTotal").html(var_total_peso);

                   
                    $("input[name='total_bultos']").val(var_cantidad_por_bulto);
                    $("input[name='peso_total_item']").val(var_total_peso);
                    $("input[name='total_m3']").val(var_totalm3)
                    $("input[name='total_ft3']").val(var_totalft3)

                    $("input[name='total_porcentaje_ganancia']").val(var_porcentaje_ganancia_total_items.toFixed(2))
                    $("input[name='total_monto_ganancia_total']").val(var_ganancia_total_item);

                    $.totalizarFactura();

                    

               
                    ////////////////////////////////////////////
        }
    }
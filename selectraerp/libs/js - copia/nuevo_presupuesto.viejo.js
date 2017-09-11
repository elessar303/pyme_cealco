$(document).ready(function(){
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
        var_subTotal = 0;
        $(".grid table.lista tbody").find("tr").each(function(){
            var_subTotal = parseFloat(var_subTotal) +  parseFloat($(this).find("td").eq(2).attr("rel"))*parseFloat($(this).find("td").eq(3).html());
            var_montoItemsFactura = parseFloat(var_montoItemsFactura) + parseFloat($(this).find("td").eq(6).html());
            var_ivaTotalFactura =  parseFloat(var_ivaTotalFactura) + parseFloat($(this).find("td").eq(8).attr("rel"));
            var_descuentosItemFactura =  parseFloat(var_descuentosItemFactura) + parseFloat($(this).find("td").eq(5).html());
            var_TotalTotalFactura = parseFloat(var_montoItemsFactura) +  parseFloat(var_ivaTotalFactura);
        });
        $("#subTotal").html(var_subTotal.toFixed(2)+" Bs.");
        $("input[name='input_subtotal']").attr("value",var_subTotal.toFixed(2));
        $("#montoItemsFactura").html(var_montoItemsFactura.toFixed(2)+" Bs.");
        $("input[name='input_montoItemsFactura']").attr("value",var_montoItemsFactura.toFixed(2));
        $("#ivaTotalFactura").html(var_ivaTotalFactura.toFixed(2)+" Bs.");
        $("input[name='input_ivaTotalFactura']").attr("value",var_ivaTotalFactura.toFixed(2));
        $("#descuentosItemFactura").html(var_descuentosItemFactura.toFixed(2)+" Bs.");
        $("input[name='input_descuentosItemFactura']").attr("value",var_descuentosItemFactura.toFixed(2));
        $("#TotalTotalFactura").html(var_TotalTotalFactura.toFixed(2)+" Bs.");
        $("input[name='input_TotalTotalFactura']").attr("value",var_TotalTotalFactura.toFixed(2));
        cantidad = $(".grid table.lista tbody").find("tr").length;
        $(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Items: "+cantidad+"</span>");
        $("input[name='input_cantidad_items']").attr("value",cantidad.toFixed(2));
        $.totalizarFactura();
    }
    $(".info_detalle").live("click", function(){
        cod = $(this).parent('tr').find("a[rel*=facebox]").text();
        $.ajax({
            type: 'GET',
            data: 'cod='+cod,
            url:  'info_servicio_item.php',
            beforeSend: function(){
                $.facebox.loading();
            },
            success: function(data){
                $.facebox(data);
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
 /*
 * Esta funcion permite crear tantos input se necesiten para la creacion de la tr
 * de la tabla de items (los que se cargan a la hora de la factura)
 **/
    $.inputHidden = function(Input,Value,ID){
        return '<input type="hidden" name="'+Input+''+ID+'" value="'+Value+'">';
    }
 /**
 * Esta funcion permite cargar los item en la tabla.
 **/
    function addTabla(codigo,descripcion,cantidad,preciosiniva,descuento,montodescuento,totalsiniva,iva,piva,totalconiva,almacen, producto){
        ContarInputItem += 1;
        campos = "";
        campos += $.inputHidden("_cod_item_precompromiso",ContarInputItem,"[]");
        campos += $.inputHidden("_item_codigo",codigo,"[]");
        campos += $.inputHidden("_item_codigo_producto",producto,"[]");
        campos += $.inputHidden("_item_almacen",almacen,"[]");
        campos += $.inputHidden("_item_cantidad",cantidad,"[]");
        campos += $.inputHidden("_item_preciosiniva",parseFloat(preciosiniva).toFixed(2),"[]");
        campos += $.inputHidden("_item_descuento",descuento,"[]");
        campos += $.inputHidden("_item_montodescuento",montodescuento,"[]");
        campos += $.inputHidden("_item_totalsiniva",totalsiniva,"[]");
        campos += $.inputHidden("_item_piva",parseFloat(piva).toFixed(2),"[]");
        campos += $.inputHidden("_item_iva",iva,"[]");
        campos += $.inputHidden("_item_totalconiva",totalconiva.toFixed(2),"[]");
        campos += $.inputHidden("_item_descripcion",descripcion,"[]");
        if ($("select[name='cod_item_forma']").val() == 1) { // Si es igual al Producto
            $.ajax({
                //type: "GET",
                //url:  "../../libs/php/ajax/ajax.php",
                //data: "opt=precomprometeritem&v1="+codigo+"&cpedido="+cantidad+"&codalmacen="+almacen+"&codprecompromiso="+ContarInputItem,//+"&tipo_transaccion="+transaccion,
                beforeSend: function(){

                },
                success: function(){
                    //result = eval(data);
                    /*if(result[0].id=="-99"){
                        $.facebox(result[0].observacion);
                        $("#cod_almacen").trigger("change");
                        return false;
                    }*/
                    /*if(data!="-1"){
                        campos += '<input type="hidden" name="_pitem_almacen" value="'+almacen+'">';
                        campos += '<input type="hidden" name="_idpitem_almacen" value="'+data+'">';
                    }else{
                        campos += '<input type="hidden" name="_pitem_almacen" value="">';
                        campos += '<input type="hidden" name="_idpitem_almacen" value="">';
                    }*/
                    html  = "               <tr >";
                    html += "		<td title=\"Haga click aqui para ver el detalle del Item\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white;\" href=\"#info\">"+producto+"</a></td>";
                    html += "		<td aligns='right' class=\"filter-column\" style=\"width:auto;\">"+descripcion+"</td>";
                    html += "		<td rel='"+cantidad+"'>"+cantidad+"</td>";
                    html += "		<td >"+parseFloat(preciosiniva).toFixed(2)+"</td>";
                    html += "		<td >"+descuento+"</td>";
                    html += "		<td >"+montodescuento+"</td>";
                    html += "		<td >"+totalsiniva+"</td>";
                    html += "		<td title='"+piva.toFixed(2)+"' >"+parseFloat(piva).toFixed(2)+"</td>";
                    html += "		<td rel='"+parseFloat(piva).toFixed(2)+"'>"+totalconiva.toFixed(2)+"</td>";
                    html += "		<td><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
                    html += "               </tr>";
                    $(".grid table.lista tbody").append(html);
                    $("#MostrarTabla").trigger("click");
                    fn_cantidad();
                }
            });
        }else{
            campos += '<input type="hidden" name="_pitem_almacen" value="">';
            campos += '<input type="hidden" name="_idpitem_almacen" value="">';

            html  = "               <tr >";
            html += "		<td title=\"Haga click aqui para ver el detalle del Item\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white;\" href=\"#info\">"+producto+"</a></td>";
            html += "		<td aligns='right' class=\"filter-column\" style=\"width:auto;\">"+descripcion+"</td>";
            html += "		<td rel='"+cantidad+"'>"+cantidad+"</td>";
            html += "		<td >"+parseFloat(preciosiniva).toFixed(2)+"</td>";
            html += "		<td >"+descuento+"</td>";
            html += "		<td >"+montodescuento+"</td>";
            html += "		<td >"+totalsiniva+"</td>";
            html += "		<td title='"+parseFloat(piva).toFixed(2)+" Bs.' >"+parseFloat(piva).toFixed(2)+"</td>";
            html += "		<td rel='"+parseFloat(piva).toFixed(2)+"'>"+totalconiva.toFixed(2)+"</td>";
            html += "		<td><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
            html += "               </tr>";
            $(".grid table.lista tbody").append(html);
            $("#MostrarTabla").trigger("click");
            fn_cantidad();
        }
    }
 /*
 * Este es el evento click del boton incluir (item)
 **/
    $("#addTabla").click(function(){
        if($("#cod_almacen").val()==null){
            almacen="";
        }else{
            almacen=$("#cod_almacen").val();
        }
        dataitem = eval($("#informacionitem").val());
        codigo = $("select[name='id_item']").val();
        //producto = $("#cod_item").val();////// dice "unddefined"
        //producto = $("select[name='cod_item']").val();////// dice "null"
        producto = dataitem[0]['cod_item'];//// salio bueno!!!
        descripcion = $("select[name='id_item'] :selected").text();
        cantidad = $("#cantidadPedido").val();
        if(parseInt(cantidad)==0){
            $.facebox("Debe especificar la cantidad!");
            $("#cantidadPedido").focus();
            return false
        }
        preciosiniva =  $("#precioProductoPedido").val();
        if(preciosiniva==0){
            $.facebox("El campo precio sin Iva debe ser distinto de cero (0)!");
            return false;
        }
        descuento = $("#descuentoPedido").val();
        montodescuento  = $("#montodescuentoPedido").val();
        totalsiniva  = $("#totalPedido").val();
        if(dataitem[0]['monto_exento']==1){// es exento
            iva = 0;
            piva = parseFloat(0);
            totalconiva = 0;
        }else{
            iva = dataitem[0].iva;
            piva = parseFloat((totalsiniva*iva)/100);
            totalconiva = parseFloat(piva) + parseFloat(totalsiniva);
        }
        addTabla(codigo,descripcion,cantidad,preciosiniva,descuento,montodescuento,totalsiniva,iva,piva,totalconiva,almacen, producto);
    });
    $("#seleccionarPedido").live("click",function(){
        if(confirm('Incluir Pedido?') == false){return false;}        
        codigo = $(this).children().val();
        //codigo_producto = $("#cod_item").val();
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=seleccionarPedidoPendiente&id_pedido="+codigo,
            success: function(data){
                resultado=eval(data);
                i=0;
                while(resultado.length)
                {
                    if((resultado[i]._item_piva!=0)&&(resultado[i]._item_piva!='')){
                        //iva=(parseFloat(resultado[i]._item_totalsiniva)*parseFloat(resultado[i]._item_piva))/100;
                        iva = 12;//Momentaneo mientras averiguo como redondear cifras
                    }    
                    else
                        iva=0;                    
                    addTabla(resultado[i].id_item,resultado[i]._item_descripcion,resultado[i]._item_cantidad,resultado[i]._item_preciosiniva,resultado[i]._item_descuento,resultado[i]._item_montodescuento,resultado[i]._item_totalsiniva,iva,resultado[i]._item_piva,parseFloat(resultado[i]._item_totalconiva),resultado[i]._item_almacen,resultado[i].cod_item);
                    i=i+1;
                }
                //fn_cantidad();
            }
        });
    });
 /*
 * Este ajax carga el tipo de precio que aplica para este
 * cliente. esto se especifica en los datos del cliente.
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
 /**
 * Este evento permite desplegar o ocultar el panel para agregar el item.
 **/
    $("#MostrarTabla").click(function(){
        valor = $("#LabelMensaje").html();
        if(valor=='Agregar Nuevo Item'){
            $("#LabelMensaje").html("Ocultar Panel");
            $("#ImgMensaje").attr("src","../../libs/imagenes/drop-add2.gif");
            $("#PanelFactura").show();
        }else{
            $("#LabelMensaje").html("Agregar Nuevo Item");
            $("#ImgMensaje").attr("src","../../libs/imagenes/drop-add.gif");
            $("#cancelaradd").trigger("click");
            $("#PanelFactura").hide();
        }
    });
 /**
 * Este evento permite cancelar o limpiar el panel del item a incluir.
 **/
    $("#cancelaradd").click(function(){
        $("input[name='totalPedido'],input[name='montodescuentoPedido'],input[name='descuentoPedido'],input[name='precioProductoPedido'],input[name='cantidadPedido']").val("0");
        $("select[name='cod_item_forma'], select[name='id_item']").removeAttr("disabled");
        $("select[name='id_item']").find("option").remove();
        $("select[name='cod_item_forma']").val("");
        $("#cod_almacen").val("");
        $("#descripcion_item").val("");
        $("#cod_almacen").removeAttr("disabled");
        $("#LabelDetalleItem").html("");
        $("#informacionitem").val("");
        $("#fila_precio1").val("");
        $("#fila_precio2").val("");
        $("#fila_precio3").val("");
        $("#LabelCantidadExistente").html("");
        $("#fila_precio1_iva").val("");
        $("#fila_precio2_iva").val("");
        $("#fila_precio3_iva").val("");
        $("#cod_almacen").find("option").remove();
        $("#cantidadItem, #cantidadTotalItem,#cantidadItemComprometidoByAlmacen,").val("");
    });
    $("select[name='cod_item_forma']").change(function(){
        if($("select[name='cod_item_forma']").val()==2){ // Si es igual al de Servicio
            $("#cod_almacen").attr("disabled","disabled");
        }else{
            $("#cod_almacen").removeAttr("disabled");
        }
        $("#descripcion_item").html("<b>"+$("select[name='cod_item_forma'] :selected").text()+"</b>");
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=Selectitem&v1="+$("select[name='cod_item_forma']").val(),
            beforeSend: function(){
            // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
            },
            success: function(data){
                resultado = eval(data)
                if(resultado[0].id=="-1"){
                }else{
                    $("select[name='id_item']").find("option").remove();
                    $("select[name='id_item']").append("<option value=''></option>");
                    for (i = 0; i < resultado.length; i++) {
                        $("select[name='id_item']").append("<option value='" + resultado[i].id_item + "'>" + resultado[i].cod_item + " " +resultado[i].descripcion1 + "</option>");
                    }
                    $("select[name='cod_item_forma']").attr("disabled","disabled");
                }
            }
        });
    });//Fin del Evento
    $("select[name='id_item']").change(function(){
        valor = $(this).val();
        if(valor==''){
            $("#cancelaradd").trigger("click");
            return false;
        }
        $(this).attr("disabled","disabled");
        $("#LabelDetalleItem").html("");
        $("#fila_precio1").val("");
        $("#fila_precio2").val("");
        $("#fila_precio3").val("");
        $("#fila_precio1_iva").val("");
        $("#fila_precio2_iva").val("");
        $("#fila_precio3_iva").val("");
        $("#cod_almacen").find("option").remove();
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=DetalleSelectitem&v1="+$(this).val(),
            beforeSend: function(){
            // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
            },
            success: function(data){
                resultado = eval(data);
                $("#informacionitem").val(data);
                if(resultado[0].id=="-1"){
                    return false;
                }else{
                    datoscliente = eval($("input[name='DatosCliente']").val());
                    if(datoscliente[0].cod_tipo_precio==$("#idpreciolibre").val()){}//Libre
                    if(datoscliente[0].cod_tipo_precio==$("#idprecio1").val()){
                        $("#precioProductoPedido").val(parseFloat(resultado[0].precio1));
                    }//Precio 1
                    if(datoscliente[0].cod_tipo_precio==$("#idprecio2").val()){
                        $("#precioProductoPedido").val(parseFloat(resultado[0].precio2));
                    }//Precio 2
                    if(datoscliente[0].cod_tipo_precio==$("#idprecio3").val()){
                        $("#precioProductoPedido").val(parseFloat(resultado[0].precio3));
                    }//Precio 3
                    //$("select[name='id_item']").attr("disabled","disabled");
                    if(resultado[0].monto_exento==1){
                        string_detalle ="<b>Monto Exento:</b> Si";
                    }
                    if(resultado[0].monto_exento==0){
                        string_detalle ="<b>Monto Exento:</b> No | <b>Iva:</b> "+resultado[0].iva
                    }
                    $("#LabelDetalleItem").html(string_detalle);
                    $("#fila_precio1").val(resultado[0].precio1);
                    $("#fila_precio2").val(resultado[0].precio2);
                    $("#fila_precio3").val(resultado[0].precio3);
                    $("#fila_precio1_iva").val(resultado[0].coniva1);
                    $("#fila_precio2_iva").val(resultado[0].coniva2);
                    $("#fila_precio3_iva").val(resultado[0].coniva3);
                    $("#ivaItem").val(resultado[0].iva);
                    $("#cod_tipo_precio").trigger("change");
                }
            }
        });
        if($("select[name='cod_item_forma']").val()==1){ // Si es igual al Producto
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=CargarAlmacenesDisponiblesByIdItem&v1="+$(this).val(),
                beforeSend: function(){
                // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                },
                success: function(data){
                    resultado = eval(data)
                    /*if(resultado[0].id=="-1"){
                        $("#cancelaradd").trigger("click");
                        $.facebox("<span style=\"color:red;\"><b>Verifique existencia.</b></span>");
                        return false;
                    }else{*/
                        $("#cod_almacen").find("option").remove();
                        $("#cod_almacen").append("<option value=''></option>");

                        for (i = 0; i < resultado.length; i++) {
                            $("#cod_almacen").append("<option value='" + resultado[i].cod_almacen + "'>" + resultado[i].descripcion + "</option>");
                        }
                    //}//Fin de if(resultado[0].id=="-1")
                }
            });
        }
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
        if ($("select[name='cod_item_forma']").val() == 1) { // Si es igual al Producto
            cantidadActual = $("#cantidadItem").val();
            if (cantidad > cantidadActual) {
                //$.facebox("Disculpe, la cantidad pedida es mayor que la existente, verifique3 existencia.");
                //$(this).val("0").focus();
                //return false;
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
            $("#totalPedido").val(parseFloat(total.toFixed(2)));
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
        $("#totalPedido").val(parseFloat(total.toFixed(2)));
    });

    $("#descuentoPedido").blur(function(){
        datoscliente = eval($("input[name='DatosCliente']").val());
        if($(this).val()==''){
            $(this).val("0");
        }
        porcentaje = $(this).val();
        if (parseFloat(porcentaje) > parseFloat(datoscliente[0].porc_parcial)) {
            $.facebox("El porcentaje no puede ser mayor al limite de cliente");
            $(this).val("0");
            porcentaje = 0;
        }
        cantidad = $("#cantidadPedido").val();
        precioitem = $("#precioProductoPedido").val();
        descuento = parseFloat(porcentaje) * (parseFloat(precioitem) * parseFloat(cantidad)) / 100 ;
        total = parseFloat(precioitem) * parseFloat(cantidad) - descuento;
        $("#montodescuentoPedido").val(descuento);
        $("#totalPedido").val(total.toFixed(2));
    });
    $("#cod_almacen").change(function(){
        //LabelCantidadExistente
        codItem = $("select[name='id_item']").val();
        if($(this).val()!=''){
            if ($("select[name='cod_item_forma']").val() == 1) { // Si es igual al Producto
                $.ajax({
                    type: "GET",
                    url:  "../../libs/php/ajax/ajax.php",
                    data: "opt=verificarExistenciaItemByAlmacen&v1="+$(this).val()+"&v2="+codItem,
                    beforeSend: function(){
                    // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                    },
                    success: function(data){
                        resultado = eval(data)
                        /*if(resultado[0].id=="-1"){
                            $.facebox("<span style=\"color:red;\"><b>Verifique existencia.</b></span>");
                            return false;
                        }else{*/
                            $("#LabelCantidadExistente").html("Cantidad Existente: "+resultado[0].cantidad);
                            $("#cantidadItem").val(resultado[0].cantidad);
                            $("#cantidadPedido").val("0");
                            $("#cantidadPedido").trigger("blur");
                        //}
                    }
                });
            }
        }
    })
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
});

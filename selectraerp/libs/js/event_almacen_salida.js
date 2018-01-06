var win;
var existencia_pos=""; //declarando variable para ver si hay en el pos
Ext.onReady(function(){
    $("input[name='cantidadunitaria'], input[name='cantidad_existente']").numeric();
    $.setValoresInput = function(nombreObjetoDestino,nombreObjetoActual){
        $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
    }
    $.inputHidden = function(Input,Value,ID){
        return '<input type="hidden" name="'+Input+''+ID+'" value="'+Value+'">';
    }
    $("img.eliminar").live("click",function(){
        $(this).parents("tr").fadeOut("normal",function(){
            $(this).remove();
            eventos_form.CargarDisplayMontos();
        });
    });

      function cargarUbicaciones() {    
        idAlmacen=$("#almacen").val();     
        $.ajax({
            type: 'POST',
            data: 'opt=cargaUbicacion&idAlmacen='+idAlmacen,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#ubicacion").find("option").remove();
                $("#ubicacion").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#ubicacion").find("option").remove();
                this.vcampos = eval(data);
                $("#ubicacion").append("<option value=''>Seleccione..</option>");
                for (i = 0; i <= this.vcampos.length; i++)
                {
                    $("#ubicacion").append("<option value='" + this.vcampos[i].id + "'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
    }
       function cargarCantidad(){ 
       //reseteo el campo      
        $("#cantidad_existente").val("");         
        codAlmacen = $("select[name='almacen']").val();
        ubicacion = $("#ubicacion").val(); 
        //cliente
        cliente = $("#cliente").val();
        nombre_ubi = $("#ubicacion option:selected").html();
        item=$("#items").val();
        lote = $("#nlote").val();
        id_proveedor=$("#id_proveedor").val();
        id_proveedor=cliente;
        if(cliente=="" || cliente==null)
        {
            
            alert("Seleccione cliente");
            $("#cliente").focus();
        }
        if(item!='' && codAlmacen!='0' && ubicacion!="" && nombre_ubi!='PISO DE VENTA'){
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=verificarExistenciaItemByAlmacen&v1="+codAlmacen+"&v2="+item+"&ubicacion="+ubicacion+"&lote="+lote+"&id_proveedor="+id_proveedor,
                beforeSend: function(){
                // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                },
                success: function(data){
                    resultado = eval(data)
                    if(resultado[0].id=="-1"){
                        alert("Verifique existencia de producto seleccionado")
                    }else{
                        $("#cantidad_existente").val(resultado[0].cantidad);
                    }
                }
            });
        }

        if(item!='' && codAlmacen!='0' && ubicacion!="" && nombre_ubi=='PISO DE VENTA'){
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=verificarExistenciaItemByAlmacen&v1="+codAlmacen+"&v2="+item+"&ubicacion="+ubicacion+"&cliente="+cliente,
                beforeSend: function(){
                // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                },
                success: function(data){
                    resultado = eval(data)
                    
                    if(resultado[0].id=="-1"){
                        alert("Verifique existencia de producto seleccionado")
                    }else{
                        $("#cantidad_existente").val(resultado[0].cantidad);
                    }
                }
            });
        }

    }
    //mia
    function consultar_ubicacion(){        
        //reseteo el campo      
        codAlmacen = $("select[name='almacen']").val();
        ubicacion = $("#ubicacion").val(); 
        item=$("#items").val();         
        if(item!='' && codAlmacen!='0' && ubicacion!="" ){ 
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=ver_ubicacion&v1="+codAlmacen+"&v2="+item+"&ubicacion="+ubicacion,
                beforeSend: function(){
                // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                },
                success: function(data){
                    resultado = eval(data)
                    if(resultado[0].id=="-1"){ 
                        alert("Verifique existencia de producto seleccionado")
                    }else{  
                       if(resultado[0].puede_vender=='1' && resultado[0].ubicacion==2){
                        bandera=1;
                        cargarCantidadPOS();
                        }else{
                            bandera=0;
                        //no es piso de venta
                        }
                        
                      /* alert(cantidad_pos);
                        var newdiv = document.createElement('div');
                        newdiv.innerHTML = "Cantidad POS "  + " <br><input type='text' name='pos_cantidad' id='pos_cantidad'>";
                        document.getElementById('incluirproducto').appendChild(newdiv);
                        $("#pos_cantidad").val(resultado[0].UNITS);*/
                    }
                }
            });
        }

    }
    //walter
    function traerdatos()
    {
        codAlmacen = $("select[name='almacen']").val();
        id = $("#ubicacion").val(); 
        cliente = $("#cliente").val(); 
        if(id!=null && isNaN(id)==false)
        {
            $.ajax({
                    type: "GET",
                    url:  "../../libs/php/ajax/ajax.php",
                    data: "opt=DatosExistenciaItemByAlmacen&v1="+codAlmacen+"&ubicacion="+id+"&cliente="+cliente,
                    success: function(data)
                    {
                        resultado = eval(data)
                        if(resultado[0].id=="-1"){ 
                            alert("No se Encontro relación ubicación - cliente")
                        }else
                        { 
                            $("#items").val(resultado[0].id_item);
                            $("#items").change();
                            $("#cantidad_existente").val(resultado[0].cantidad);
                            $("#peso_existente").val(resultado[0].peso);
                            $("#nlote").val(resultado[0].lote);
                            cargarproductoitem(resultado[0].id_item);

                        }
                    }
                });
        }
    }

    function cargarproductoitem(id)
    {
        $.ajax({
                    type: "GET",
                    url:  "../../libs/php/ajax/ajax.php",
                    data: "opt=productoiddescripcion&v1="+id,
                    success: function(data)
                    {
                        resultado = eval(data)
                        if(resultado[0].id=="-1"){ 
                            alert("Error, No se Encontro")
                        }else
                        { 
                         $("#codigoBarra").val(resultado[0].codigo_barras);
                         $("#items_descripcion").val(resultado[0].descripcion1);
                        }
                    }
                });
    } //fin walter
    
    function cargarCantidadPOS(){        
        //reseteo el campo      
    
        //$("#cantidad_existente").val("");         
        codAlmacen = $("select[name='almacen']").val();
        ubicacion = $("#ubicacion").val(); 
        item=$("#items").val();         
        if(item!='' && codAlmacen!='0' && ubicacion!="" ){ 
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=cargarCantidadPOS_1&v1="+codAlmacen+"&v2="+item+"&ubicacion="+ubicacion,
                beforeSend: function(){
                // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                },
                success: function(data){
                    resultado = eval(data)
                    if(resultado[0].id=="-1"){ 
                        alert("Verifique existencia de producto seleccionado");
                       
                        
                    }else{  
                       
                        existencia_pos=resultado[0].UNITS;
                        $("#cantidad_existente").val(resultado[0].cantidad);
                        //alert(resultado[0].cantidad);
                        /*var newdiv = document.createElement('div');
                        newdiv.innerHTML = "Cantidad POS "  + " <br><input type='text' name='pos_cantidad' id='pos_cantidad'>";
                        document.getElementById('incluirproducto').appendChild(newdiv);
                        $("#pos_cantidad").val(resultado[0].UNITS);*/
                    }
                }
            });
        }

    }
    
    //fin mia
    
    
     /*$.filtrarArticulo = function(value, tipoFiltro){
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
    };*/
    
    function cargarProductoCodigo() {    
        codigoBarra=$("#codigoBarra").val();     
        $.ajax({
            type: 'POST',
            data: 'opt=cargaProductoCodigo&codigoBarra='+codigoBarra,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
               
            },
            success: function(data) {                                 
                this.vcampos = eval(data);             
                  if( this.vcampos[0].band==-1){
                    alert("El codigo de barra no es correcto!");
                  }else{
                     var idItem=this.vcampos[0].id_item;
                     $("#items").val(idItem);
                     $("#items").change();
                  }
               
            }
        });
    }


$("#almacen").change(function(){
    cargarUbicaciones();
});
/*$("#buscarCodigo").click(function(){
    cargarProductoCodigo();
});*/
$("#buscarCodigo").click(function(){
      
      //pBuscaItem.main.mostrarWin();
    //cargarProductoCodigo();
    var value = $('#codigoBarra').val();
      if(value=="") {
                pBuscaItem.main.mostrarWin();
                return false;
            }
            cargarProductoCodigo();
          //  $.filtrarArticulo(value, "filtroItemByRCCB");

            return false;
        


});

$("#codigoBarra").keypress(function(e){    
    if(e.which==13 || e.which=='13' ){
        cargarProductoCodigo();
    }    
});

//cargo la cantidad cuando cambie el item, el almacen o la ubicacion
    $("#items").change(function(){
       cargarCantidad();
    });
    $("#ubicacion").change(function(){
       cargarCantidad();
       traerdatos();
    });
     $("#almacen").change(function(){
       cargarCantidad();
    });
    $("#nlote").change(function(){
       cargarCantidad();
    });
    //cargo la cantidad del pos cuando verifico que ubicacion es piso de venta
     
   /* $("#ubicacion").change(function(){
       cargarCantidadPOS();
    });*/
    $("#ubicacion").change(function(){
       consultar_ubicacion();
    });
    
     $("#items").change(function(){
       consultar_ubicacion();
    });
     $("#almacen").change(function(){
       consultar_ubicacion();
    });
    $("#cantidadunitaria").keyup(function(){
       if($('#cantidadunitaria').val()<=0 || ( parseFloat($('#cantidad_existente').val()) - parseFloat($('#cantidadunitaria').val()) ) <=0)
       {
            $("#peso").val(0);
       }
       else
       {
            $("#peso").val("");
       }
    });
    
    

    $(".info_detalle").live("click", function(){
        cod = $(this).parent('tr').find("a[rel*=facebox]").text();
        var mask = new Ext.LoadMask(Ext.get("Contenido"), {
            msg:'Cargando..',
            removeMask:false
        });
        $.ajax({
            type: 'GET',
            data: 'cod='+cod,
            url:  'info_servicio_item.php',
            beforeSend: function(){
                mask.show();
            },
            success: function(data){
                var win_tmp = new Ext.Window({
                    title:'Detalle del Producto',
                    height: 400,
                    width: 350,
                    frame:true,
                    autoScroll:true,
                    modal:true,
                    html: data,
                    buttons:[{
                        text:'Cerrar',
                        handler:function(){
                            win_tmp.hide();
                        }
                    }]
                });

                win_tmp.show(this);
                mask.hide();
            }
        });
    });

    win = new Ext.Window({
        title:'Seleccionar Producto',
        height:360,
        width:459,
        autoScroll:true,
        tbar:[
        {
            text:'Actualizar lista de Productos',
            icon: '../../libs/imagenes/ico_search.gif',
            handler: function(){
                eventos_form.cargarProducto();
            }
        },
        {
            text:'Actualizar lista de Almacenes',
            icon: '../../libs/imagenes/ico_search.gif',
            handler: function(){
                eventos_form.cargarAlmacenes();
            }
        },
        {
            text:'Limpiar',
            icon: '../../libs/imagenes/back.gif',
            handler: function(){
                eventos_form.Limpiar();
            }
        }
        ],
        modal:true,
        bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
        closeAction:'hide',
        contentEl:'incluirproducto',
        buttons:[
        {
            text:'Incluir',
            icon: '../../libs/imagenes/drop-add.gif',
            handler:function(){
                              
                if($("#items").val()==""||$("#almacen").val()==""||$("#cantidadunitaria").val()==""){
                    Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                    return false;
                }
                 if($("#cantidad_existente").val()==""  || $("#cantidad_existente").val()==0 || $("#cantidad_existente").val()=="0" ){
                     Ext.Msg.alert("Alerta","Debe tener existencia para poder realizar la salida");
                    return false;
                }
                
                // existente=document.getElementById('cantidad_existente').value;
                // solicitada=document.getElementById('cantidadunitaria').value;  

                            solicitada=$("#cantidadunitaria").val(); 
                            pesoold=$("#peso").val(); 
                            existente=$("#cantidad_existente").val(); 
                            solicitada=  parseInt(solicitada);
                            existente=  parseInt(existente);
                           
                         
                if(pesoold=="")
                {
                    Ext.Msg.alert("Alerta","El peso no puede ser vacio");
                    return false;   
                }



                if( solicitada > existente ){
                    
                    Ext.Msg.alert("Alerta","La cantidad a descargar no puede ser mayor a la existente");
                    return false;
                }
                if(bandera==1 && existencia_pos!="" ){
                if(   solicitada > existencia_pos   ){
                    
                    Ext.Msg.alert("Alerta","La cantidad a descargar no puede ser mayor a la existente en el POS");
                    return false;
                }
                }
                
                if(solicitada <=0 )
                {
                    Ext.Msg.alert("Alerta","La cantidad a descargar no puede ser igual o menor a 0");
                    return false;
                }
                if( solicitada < existente && pesoold<=0 )
                {
                    
                    Ext.Msg.alert("Alerta","El peso no puede ser 0 si existen unidades");
                    return false;
                }
                if( solicitada == existente && pesoold!=0 )
                {
                    
                    Ext.Msg.alert("Alerta","El peso debe ser 0 si no quedan unidades");
                    return false;
                }

                

                eventos_form.IncluirRegistros({
                    id_item:            $("#items").val(),
                    descripcion:        $("#items :selected").text()=="" ? $("#items_descripcion").val() : $("#items :selected").text(),
                    id_almacen:         $("#almacen").val(),
                    id_ubicacion:         $("#ubicacion").val(),
                    cantidad:           $("#cantidadunitaria").val(),/*,*/
                    peso:           $("#peso").val(),/*,*/
                    nlote:           $("#nlote").val()/*,*/
                });

            }
        },
        {
            text:'Cerrar',
            icon: '../../libs/imagenes/cancel.gif',
            handler:function(){
                win.hide();
            }
        },
        ]
    });

    var formpanel = new Ext.Panel({
        title:'Datos del Proveedor',
        autoHeight: 300,
        width: '100%',
        collapsible: true,
        titleCollapse: true ,
        contentEl:'dp',
        frame:true
    });
    
    var formpanel_dcompra = new Ext.Panel({
        title:'Informaci&oacute;n del Cargo',
        autoHeight: 300,
        width: '100%',
        collapsible: true,
        titleCollapse: true ,
        contentEl:'dcompra',
        frame:true
    });

    var tab = new Ext.TabPanel({
        /*title:'Informaci&oacute;n del Cargo',
        autoHeight: 300,
        width: '100%',
        collapsible: true,
        titleCollapse: true ,*/

        frame:true,
        contentEl:'PanelGeneralCompra',
        activeTab:0,
        height:300,
        items:[
        {
            title:'Productos',
            contentEl:'tabproducto',
            autoScroll:true,
            tbar: [
            {
                text:'Agregar Producto',
                icon: '../../libs/imagenes/add.gif',
                handler: function(){
                    eventos_form.init();
                    $("#ubicacion").empty();
                    $("#cantidad_existente").val("");
                    win.show();
                }
            },
            {
                xtype:'label',
                contentEl: 'displaytotal',
                fn:  eventos_form.CargarDisplayMontos()
            }
            ]
        },
        {
            id : 'remove-this-tab', //Crea un id para usarlo mas adelante HZ
            title:'Registrar Movimiento',
            contentEl:'tabpago',
            autoScroll:true,
            tbar: [
            {
                text:'<b>Registrar Descarga</b>',
                icon: '../../libs/imagenes/back.gif',
                iconAlign: 'left',
                height: 20,
                scope: this,/*Prueba para quitar la pestaña del tab*/
                handler: function(){
                    
                    eventos_form.GenerarCompraX();
                    
                    /*aqui creo que deberia meter la funcion para deshabilitar el boton una vez presionado 
                    para evitar la duplicidad de los datos*/

                    /*document.getElementById("Registrar Descarga").value = "Enviando...";
                    document.getElementById("Registrar Descarga").disabled = true;
                    return true;*/
                    /*Remueve esta pestaña del tab HZ*/
                    var pruebatab = Ext.getCmp('remove-this-tab');
                    tab.remove(pruebatab);
                }
            },
            {
                xtype:'label',
                contentEl: 'displaytotal2',
                fn:  eventos_form.CargarDisplayMontos()
            }
            ]
        }
        ]
    });
    formpanel.render("formulario");
    formpanel_dcompra.render("formulario");
    tab.render("formulario");
});


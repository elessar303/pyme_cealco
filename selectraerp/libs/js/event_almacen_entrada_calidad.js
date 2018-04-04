var win;

Ext.onReady(function(){
    $("input[name='totalizar_monto_efectivo'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque'], input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta'],input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito'],input[name='totalizar_nro_otrodocumento'],input[name='totalizar_monto_otrodocumento']").numeric();
    $("#autorizado_por, #nro_control, #nro_factura").blur(function(){
        $(this).css("border-color",$(this).val()===""?"red":"");
        $(this).css("border-width",$(this).val()===""?"1px":"");
    });
    $.setValoresInput = function(nombreObjetoDestino,nombreObjetoActual){
        $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
    }
    $.inputHidden = function(Input,Value,ID){
        return '<input type="hidden" name="'+Input+''+ID+'" value="'+Value+'">';
    }
    $("img.eliminar").live("click",function(){
        $(this).parents("tr").fadeOut("normal",function(){
         $(this).remove();
           
            //la cantidad de productos cargados
            eventos_form.CargarDisplayMontos();
           
           
        });
    });
    
    
    
    $(".eliminar_serial").live("click",function(){
            cod=$(this).parent('tr').find("a[rel*=borrar_serial]").text();
            $(".grid table.lista tbody").find('tr[id^='+cod+']').remove();
                $.ajax({
            type: 'POST',
            data: 'opt=eliminarserial&idproduc_serial='+cod,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#ubicacion").find("option").remove();
                $("#ubicacion").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
              // alert(data);
                
            }
        });
            
             
            $(this).parents("tr").fadeOut("normal",function(){
              
            $(this).remove();
            //la cantidad de productos cargados
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
                for (i = 0; i <= this.vcampos.length; i++) {
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
        item=$("#items").val();         
        if(item!='' && codAlmacen!='0' && ubicacion!="" && ubicacion!=2){
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=verificarExistenciaItemByAlmacen&v1="+codAlmacen+"&v2="+item+"&ubicacion="+ubicacion,
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

        if(item!='' && codAlmacen!='0' && ubicacion!="" && ubicacion==2){
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=verificarExistenciaItemByAlmacen2&v1="+codAlmacen+"&v2="+item+"&ubicacion="+ubicacion,
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

$("#buscarCodigo").click(function(){
      
      //pBuscaItem.main.mostrarWin();
    //cargarProductoCodigo();
    var value = $('#codigoBarra').val();
      if(value=="") {
                pBuscaItem.main.mostrarWin();
                return false;
            }

            //$.filtrarArticulo(value, "filtroItemByRCCB");

            return false;
        


});
  

$("#codigoBarra").keypress(function(e){    
    if(e.which==13 || e.which=='13' ){
        cargarProductoCodigo();
    }    
});


$("#cantidaddeberia").change(function(){
  cdeberia= $("#cantidaddeberia").val();
  cunitaria=$("#cantidadunitaria").val();
  if(cunitaria!=""){
        if(cdeberia!=cunitaria){
            $("#observacion").show();
            Ext.Msg.alert("Alerta","Existe una diferencia entre lo que salio y lo que entro, llene el campo observacion");
        }else{
             $("#observacion").hide();
        }
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
        height:400,
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

                if($("#items").val()==""||
                    $("#almacen").val()==""||
                    $("#almacen").val()=="0"||
                    $("#ubicacion").val()==""||
                     $("#marca").val()==""||                    
                    $("#cantidadunitaria").val()==""||
                    (($("#fVencimiento").val()=="") && ($("#fecha_vencimiento").val()=="Si")) ||
                    $("#nlote").val()==""||
                    $("#observacion1").val()==""
                ){
                    Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                    return false;
                }
                if( ( parseInt($("#costo_declarado").val()) < 1 && parseInt($("#costo_referencial").val()) < 1) )
                {
                    Ext.Msg.alert("Alerta","Error, El costo declarado y referencial no pueden ser 0.");
                    return false;
                }
                if( ( parseInt($("#costo_declarado").val()) < 1 && parseInt($("#costo_referencial").val()) > 0) )
                {
                   $("#costo_declarado").val($("#costo_referencial").val());
                }
                if($("#estatus").val()==0){                 
                    if($.trim($("#causa").val()) ==''){
                           Ext.Msg.alert("Alerta","Debe especificar la causa de la no aprobacion.");
                           return false;
                    }
                }

                eventos_form.IncluirRegistros({
                    //cod_item:           $("#items").val(),/////// Añadido
                    id_item:            $("#items").val(),
                    descripcion:        $("#items :selected").text()=="" ? $("#items_descripcion").val() : $("#items :selected").text(),
                    id_almacen:         $("#almacen").val(),
                    tipo_uso:           $("#tipo_uso1").val(),
                    id_ubicacion:       $("#ubicacion").val(),
                    cantidad:           $("#cantidadunitaria").val(),
                    costo_referencial:  $("#costo_referencial").val(),
                    costo_declarado:    $("#costo_declarado").val(),
                    marca:              $("#marca").val(),
                    vencimiento:        $("#fVencimiento").val(),
                    lote:               $("#nlote").val(),
                    c_esperada:         $("#cantidaddeberia").val(),
                    observacion:        $("#observacion1").val(),
                    estatus:            $("#estatus_producto").val()
                    
                    
                });
                
                var cod = $("#items").val();
                var cant = $("#cantidadunitaria").val();
			        var mask = new Ext.LoadMask(Ext.get("Contenido"), {
			            msg:'Cargando..',
			            removeMask:false
			        });
			        $.ajax({
			            type: 'GET',
			            data: 'opt=cantidadSeriales&cod='+cod+'&cant='+cant,
			            url:  '../../libs/php/ajax/ajax.php',
			            beforeSend: function(){
			                //mask.show();
			            },
			            success: function(data){
			            	resultado = data
                            //if(resultado=="-1"){exit;}
                    		if(resultado!=-1){
			                var win_tmp = new Ext.Window({
			                    title:'Registro de Seriales',
			                    height: 400,
			                    width: 350,
			                    frame:true,
                                closable:false,
			                    autoScroll:true,
			                    modal:true,
			                    html: data,
			                    buttons:[{
			                        text:'Guardar',
			                        handler: function(){
                                        //VALIDAR SERIALES NO REPETIDOS (DANIEL)
                                        cant=0;
                                        $(".serialSec").each(function(index,value){
                                            cant++;
                                            $(this).css("border-color","#CCCCCC");
                                            
                                        });                              

                                        
                                        var bandera=0;
                                        var igual=0;
                                        for (var i = 0; i < cant ; i++) {                                           
                                            valor=$("#serial"+i).val();
                                            $( ".serialSec" ).each(function( index ) {
                                                if($(this).attr('id')!="serial"+i){
                                                     if($(this).val()==valor){
                                                        igual=1;
                                                        $(this).css("border-color","#DF0101");
                                                     }  
                                                }
                                            }); 
                                        };
                                        $( ".serialSec" ).each(function( index ) {
                                            if($(this).val()==""){
                                                bandera=1;    
                                            }

                                        }); 

                           
                                        if(bandera==0 && igual==0){
                                            formulario=$("#seriales").serialize();  

                                            $.ajax({                                            
                                                type: 'GET',
                                                data: "opt=VerificarSerial&cant="+cant+"&"+formulario,
                                                dataType: "json",
                                                url: '../../libs/php/ajax/ajax.php',
                                                beforeSend: function() {
                                                  
                                                },
                                                success: function(data) {                                                   
                                                    console.log(data[0].idSerial);
                                                    if(data[0].idSerial==0){
                                                        eventos_form.IncluirSeriales({
                                                            formulario: JSON.stringify($("#seriales").serializeArray())
                                                        });
                                                        win_tmp.close(); 
                                                    }else{
                                                        $.each(data,function(index,value){
                                                             $("#"+value.idSerial).css("border-color","#DF0101");                                                         
                                                        
                                                        });  
                                                        alert("los Seriales marcados ya han sido cargado");                                                 
                                                        return false;
                                                    }
                                                                                                 

                                                  

                                                }
                                            });//fin del ajax de validar seriales


                                           
                                        }else{
                                            alert("debe ingresar todos los seriales o estan repetidos los seriales");
                                            return false;
                                        }
			                       
						            }
					            }]                                  
			                });
			                win_tmp.show(this);
			                //mask.hide();
                            //agregando las funcionalidades al los botones de generar seriales
                                $("#gSerial").click(function(event) {                                  
                                  serialG= $("#iSerialGen").val();
                                  if(serialG==""){
                                    alert("Ingrese un serial para la serie");
                                  }else{
                                      serialG= $("#iSerialGen").val();
                                      serialG=parseInt(serialG);  
                                      valor=0;
                                    $( ".serialSec" ).each(function( index ) {
                                        id= $(this).attr("id");                                       
                                       sum= serialG + valor;
                                        valor= valor + 1;
                                         $( "#"+id ).val(sum);
                                    });
                                  }
                                });

			              }
			            }
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
            id : 'remove-this-tab',//Crea un id para usarlo mas adelante HZ
            title:'Registrar Movimiento',
            contentEl:'tabpago',
            autoScroll:true,
            tbar: [
            {
                text:'<b>Registrar Entrada</b>',
                icon: '../../libs/imagenes/back.gif',
                iconAlign: 'left',
                height: 20,
                scope: this,
                handler: function(){
                    eventos_form.GenerarCompraX();
                    /*Remueve esta pestaña del tab HZ*/
                    var pruebatab = Ext.getCmp('remove-this-tab');
                    tab.hide(pruebatab);
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

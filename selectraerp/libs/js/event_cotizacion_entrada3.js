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
            eventos_form.CargarDisplayMontos();
        });
    });

//verificando la cantidad cada vez que se cambia el item, la ubicacion y el alamcen
    $("#items").change(function(){
      cargarCantidad();
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
        /*{
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
        },*/
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

                if(
                    $("#rubros").val()=="0"
                ){
                    Ext.Msg.alert("Debe seleccionar el rubro a cotizar");
                    return false;
                };

                //Prueba para validar que el precio sea numerico HZ
                if ($("#precio_unitario").val()=="") {
                    Ext.Msg.alert("Debe colocar el costo unitario");
                    return false;
                };
                if (isNaN($("#precio_unitario").val())) {
                    Ext.Msg.alert("El Costo Unitario debe ser numérico");
                    return false;
                };
                if (isNaN($("#precio_sugerido").val())) {
                    Ext.Msg.alert("El Precio Sugerido por el Vendedor debe ser numérico");
                    return false;
                };
                if ($("#porcentaje_operatividad").val()=="" || $("#porcentaje_operatividad").val()=="0") {
                    Ext.Msg.alert("Debe colocar el porcentaje de la operatividad");
                    return false;
                };
                if (isNaN($("#porcentaje_operatividad").val())) {
                    Ext.Msg.alert("El porcentaje de operatividad debe ser numérico");
                    return false;
                };

                eventos_form.IncluirRegistros({
                    producto:                $("#rubros").val(),
                    descripcion:             $("#rubros :selected").text(),
                    costo_sin_iva:           $("#costo_sin_iva").val(),
                    precio_sugerido:         $("#precio_sugerido").val(),
                    ivaproduct:              $("#ivaproduct").val(),
                    margen_ganancia:         $("#margen_ganancia").val(),
                    pvp:                     $("#pvp").val(),
                    precio_unitario:         $("#precio_unitario").val(),
                    porcentaje_operatividad: $("#porcentaje_operatividad").val(),
                    operatividad:            $("#operatividad").val(),
                    porcentaje_ganancia:     $("#porcentaje_ganancia").val(),
                    troquel:                 $("#troquel").val(),
                    describe_troquel:        $("#troquel :selected").text()

                });

                // alert(descripcion);
                var pvp2 = $("#pvp").val();
                //Ext.Msg.alert(pvp2);
                var cod = $("#rubros").val();
                //var cod = $("#establecimiento").val();

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
        title:'Datos de la Cotización',
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
                text:'<b>Agregar Productos a Cotizar</b>',
                icon: '../../libs/imagenes/add.gif',
                handler: function(){
                    eventos_form.init();
                    //$("#ubicacion").empty();
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
            title:'Registrar Cotización',
            contentEl:'tabpago',
            autoScroll:true,
            tbar: [
            {
                text:'<b>Registrar Entrada</b>',
                icon: '../../libs/imagenes/back.gif',
                iconAlign: 'left',
                height: 20,
                handler: function(){
                    eventos_form.GenerarCompraX();
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
    //formpanel_dcompra.render("formulario");
    tab.render("formulario");
});

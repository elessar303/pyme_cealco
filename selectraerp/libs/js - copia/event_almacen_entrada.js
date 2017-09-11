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
    $("#items").change(function(){
        //LabelCantidadExistente
        codAlmacen = $("select[name='almacen']").val();
        if($(this).val()!=''){
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=verificarExistenciaItemByAlmacen&v1="+codAlmacen+"&v2="+$(this).val(),
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
                eventos_form.IncluirRegistros({
                    //cod_item:           $("#items").val(),/////// Añadido
                    id_item:            $("#items").val(),
                    descripcion:        $("#items :selected").text(),
                    id_almacen:         $("#almacen").val(),
                    cantidad:           $("#cantidadunitaria").val()
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
                    eventos_form.init()
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
            title:'Registrar Movimiento',
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
    formpanel_dcompra.render("formulario");
    tab.render("formulario");
});

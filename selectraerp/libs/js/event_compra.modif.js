var win;
var win2;

Ext.onReady(function(){
    $("input[name='totalizar_monto_efectivo'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque'], input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta'],input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito'],input[name='totalizar_nro_otrodocumento'],input[name='totalizar_monto_otrodocumento']").numeric();

    $.setValoresInput = function(nombreObjetoDestino,nombreObjetoActual){
        $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
    }

    $.inputHidden = function(Input,Value,ID){
        return '<input type="hidden" name="'+Input+''+ID+'" value="'+Value+'">';
    }

    $("#cantidadunitaria, #costounitario").numeric();

    $("#cantidadunitaria2, #costounitario2").numeric();

    $("#cantidadunitaria, #costounitario").blur(function(){
        canuni = $("#cantidadunitaria").val();
        costo = $("#costounitario").val();
        resultado =  parseFloat(canuni)*parseFloat(costo);
        resulta = !isNaN(resultado) ? resultado.toFixed(2) : 0.00;
        $("#totalitem_tmp").val(resulta);
    });

    $("#cantidadunitaria2, #costounitario2").blur(function(){
        canuni = $("#cantidadunitaria2").val();
        costo = $("#costounitario2").val();
        resultado =  parseFloat(canuni)*parseFloat(costo);
        resulta = !isNaN(resultado) ? resultado.toFixed(2) : 0.00;
        $("#totalitem_tmp2").val(resulta);
    });

    $("img.eliminar").live("click",function(){
        $(this).parents("tr").fadeOut("normal",function(){
            $(this).remove();
            eventos_form.CargarDisplayMontos2();
            eventos_form.CargarDisplayMontos();
            $("#totalizar_monto_cancelar").trigger("click");
        });
    });

    $("#opt_cheque").change(function(){
        valor = $(this).val();
        if(valor==0){
            $("#totalizar_nombre_banco,input[name='totalizar_nro_cheque'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque']").val("0");
            $("#totalizar_nombre_banco,input[name='totalizar_nro_cheque'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque']").attr("readonly", "readonly");
        }
        else if(valor==1){
            $("#totalizar_nombre_banco,input[name='totalizar_nro_cheque'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque']").val("0");
            $("#totalizar_nombre_banco,input[name='totalizar_nro_cheque'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque']").removeAttr("readonly");
        }
    });

    $("#opt_tarjeta").change(function(){
        valor = $(this).val();
        if(valor==0){
            $("#totalizar_tipo_tarjeta,input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta']").val("0");
            $("#totalizar_tipo_tarjeta,input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta']").attr("readonly", "readonly");
        }
        else if(valor==1){
            $("#totalizar_tipo_tarjeta,input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta']").val("0");
            $("#totalizar_tipo_tarjeta,input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta']").removeAttr("readonly");
        }
    });

    $("#opt_deposito").change(function(){
        valor = $(this).val();
        if(valor==0){
            $("#totalizar_banco_deposito,input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").val("0");
            $("#totalizar_banco_deposito,input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").attr("readonly", "readonly");
        }
        else if(valor==1){
            $("#totalizar_banco_deposito,input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").val("0");
            $("#totalizar_banco_deposito,input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").removeAttr("readonly");
        }
    });

    $("#opt_otrodocumento").change(function(){
        valor = $(this).val();
        if(valor==0){
            $("#totalizar_banco_otrodocumento,#totalizar_tipo_otrodocumento,input[name='totalizar_monto_otrodocumento'], input[name='totalizar_nro_otrodocumento']").val("0");
            $("#totalizar_banco_otrodocumento,#totalizar_tipo_otrodocumento,input[name='totalizar_monto_otrodocumento'], input[name='totalizar_nro_otrodocumento']").attr("readonly", "readonly");
        }
        else if(valor==1){
            $("#totalizar_banco_otrodocumento,#totalizar_tipo_otrodocumento,input[name='totalizar_monto_otrodocumento'], input[name='totalizar_nro_otrodocumento']").val("0");
            $("#totalizar_banco_otrodocumento,#totalizar_tipo_otrodocumento,input[name='totalizar_monto_otrodocumento'], input[name='totalizar_nro_otrodocumento']").removeAttr("readonly");
        }
    });

    $("#opt_cheque, #opt_tarjeta, #opt_deposito,  #opt_otrodocumento").val(0).trigger("change");

    $("#totalizar_monto_cancelar").bind("click", function(){
        $(this).numeric();
        monto = eval($("#input_tciniva").val());
        $(this).val(monto.toFixed(2)).select();
        $("input[name='totalizar_saldo_pendiente']").val(0);
        $("input[name='totalizar_monto_efectivo']").val(monto.toFixed(2));
        $("input[name='totalizar_cambio']").val(0);
    }).blur(function(){
        montoOLD = eval($(this).val());
        montoOLD = parseFloat(montoOLD);
        totalgeneral  =  eval($("#input_tciniva").val());

        if(totalgeneral<=0){
            Ext.Msg.alert("Alerta!","El monto general es cero, provablemente usted deba cargar un item.");
            return false;
        }
        if(montoOLD<0){
            Ext.Msg.alert("Alerta!","El monto debe ser positivo.");

            $(this).trigger("click");
            $(this).focus();
            return false;
        }
        if(montoOLD==0){
            $("input[name='totalizar_saldo_pendiente']").val(totalgeneral);
            $("input[name='totalizar_monto_efectivo']").val(0);
            $("input[name='totalizar_cambio']").val(0);
            return false;
        }
        if(montoOLD==""){
            resultado = totalgeneral;
            $(this).val(resultado);
            $(this).select();
            $("input[name='totalizar_saldo_pendiente'], input[name='totalizar_cambio'], input[name='totalizar_monto_efectivo']").val("0");
            return false;
        }
        //total_pendiente = parseFloat(totalgeneral) - parseFloat(montoOLD);
        if( montoOLD  < totalgeneral){
            restante = parseFloat(totalgeneral) - parseFloat(montoOLD);
            $("input[name='totalizar_saldo_pendiente']").val(restante.toFixed(2));
            $("input[name='totalizar_monto_efectivo']").val(montoOLD.toFixed(2));
            return false;
        }
        if(montoOLD>totalgeneral){
            restante = parseFloat(montoOLD) - parseFloat(resultado);
            $("input[name='totalizar_cambio']").val(restante.toFixed(2));
            $("input[name='totalizar_monto_efectivo']").val(totalgeneral);
            return false;
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
        title:'Cargar Producto',
        height:360,
        width:550,//459,
        autoScroll:true,
        tbar:[
        {
            text:'Agregar Producto',
            icon: '../../../includes/imagenes/ico_search.gif',
            handler: function(){
                pBuscaItem.main.mostrarWin();
            }
        },
        {
            text:'Actualizar Lista de Productos',
            icon: '../../../includes/imagenes/ico_search.gif',
            handler: function(){
                eventos_form.cargarProducto();
            }
        },
        {
            text:'Actualizar Lista de Almacenes',
            icon: '../../../includes/imagenes/ico_search.gif',
            handler: function(){
                eventos_form.cargarAlmacenes();
            }
        },
        {
            text:'Limpiar',
            icon: '../../../includes/imagenes/Clear.gif',
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
            icon: '../../../includes/imagenes/drop-add.gif',
            handler:function(){
                if($("#items").val()==""||$("#almacen").val()==""||$("#cantidadunitaria").val()==""||$("#costounitario").val()==""){
                    Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                    return false;
                }

                if(isNaN($("#totalitem_tmp").val())){
                    Ext.Msg.alert("Alerta","Error en el Cálculo, verifique e intente de nuevo.");
                    $("#cantidadunitaria, #costounitario, #totalitem_tmp").val("");
                    $("#cantidadunitaria").focus();
                    return false;
                }

                if($("#totalitem_tmp").val()<=0){
                    Ext.Msg.alert("Alerta","El monto debe ser Mayor a cero (0)");
                    return false;
                }

                eventos_form.IncluirRegistros({
                    id_item:            $("#items").val(),
                    //descripcion:        $("#items :selected").text(),
                    descripcion:        $("#items").val(),
                    id_almacen:         $("#almacen").val(),
                    codfabricante:      $("#codigofabricante").val(),
                    cantidad:           $("#cantidadunitaria").val(),
                    precio:             $("#costounitario").val(),
                    totalsiniva:        (($("#cantidadunitaria").val())*($("#costounitario").val())),
                    iva:                '12.0%',
                    totalconiva:        '200'
                });
            }
        },
        {
            text:'Cerrar',
            icon: '../../../includes/imagenes/cancel.gif',
            handler:function(){
                win.hide();
            }
        },
        ]
    });

    win2 = new Ext.Window({
        title:'Cargar Servicio',
        height:360,
        width:459,
        autoScroll:true,
        tbar:[
        {
            text:'Actualizar lista de Servicios',
            icon: '../../../includes/imagenes/ico_search.gif',
            handler: function(){
                eventos_form.cargarServicio();
            }
        },
        {
            text:'Limpiar',
            icon: '../../../includes/imagenes/back.gif',
            handler: function(){
                eventos_form.Limpiar();
            }
        }
        ],
        modal:true,
        bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
        closeAction:'hide',
        contentEl:'incluirservicio',
        buttons:[
        {
            text:'Incluir',
            icon: '../../../includes/imagenes/drop-add.gif',
            handler:function(){
                if($("#items2").val()==""||$("#cantidadunitaria2").val()==""||$("#costounitario2").val()==""){
                    Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                    return false;
                }

                if(isNaN($("#totalitem_tmp2").val())){
                    Ext.Msg.alert("Alerta","Error en el Calculo, verifique e intente de nuevo.");
                    $("#cantidadunitaria2, #costounitario2, #totalitem_tmp2").val("");
                    $("#cantidadunitaria2").focus();
                    return false;
                }

                if($("#totalitem_tmp2").val()<=0){
                    Ext.Msg.alert("Alerta","El monto debe ser Mayor a cero (0)");
                    return false;
                }

                eventos_form.IncluirRegistros2({
                    id_item:            $("#items2").val(),
                    descripcion:        $("#items2 :selected").text(),
                    cantidad:           $("#cantidadunitaria2").val(),
                    precio:             $("#costounitario2").val(),
                    totalsiniva:        (($("#cantidadunitaria2").val())*($("#costounitario2").val())),
                    iva:                '12.0%',
                    totalconiva:        '200'
                });
            }
        },
        {
            text:'Cerrar',
            icon: '../../../includes/imagenes/cancel.gif',
            handler:function(){
                win2.hide();
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

    var formpanel_dcompra = new Ext.Panel({//originalmente sin var
        title:'Información de la Compra',
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
                icon: '../../../includes/imagenes/add.gif',
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
            title:'Servicios',
            contentEl:'tabservicio',
            autoScroll:true,
            tbar: [
            {
                text:'Agregar Servicio',
                icon: '../../../includes/imagenes/add.gif',
                handler: function(){
                    eventos_form.init2()
                    win2.show();
                }
            },
            {
                xtype:'label',
                contentEl: 'displaytotal',
                fn:  eventos_form.CargarDisplayMontos2()
            }
            ]
        },
        {
            title:'Forma Pago',
            contentEl:'tabpago',
            autoScroll:true,
            tbar: [
            {
                text:'<b>Generar Compra</b>',
                icon: '../../../includes/imagenes/back.gif',
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
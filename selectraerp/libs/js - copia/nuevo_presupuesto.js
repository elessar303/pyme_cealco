/************* INICIO DE PAQUETE *********************/
/**
 *
 * Creación de ventana para la busqueda de items.
 * Fecha de Creación: dom 4 de mar, 10:49:51 PM
 *
 * Autor: Luis E. Viera Fernandez.
 *
 *
 * Correo:
 *  lviera@armadillotec.com
 *  lviera86@gmail.com
 *
 */
Ext.ns("pBuscaItem");

pBuscaItem.main = {
    limitePaginacion:10,
    iniciar:0,
    init:function(){
        this.storeProductos = this.getLista();
        this.cancelar = new Ext.Button({
            text:'Cerrar ventana',
            iconCls:'cancelar',
            handler:function(){
                pBuscaItem.main.ocularWin();
            }
        });
        this.codigoPro = new Ext.form.TextField({
            fieldLabel:'Código',
            name:'codigoProducto'
        });
        this.codigoPro.on('specialkey', function(f, event){
            if(event.getKey() == event.ENTER) {
                pBuscaItem.main.aplicarFiltroByFormularioProducto();
            }
        }, this);
        this.descripcionPro = new Ext.form.TextField({
            fieldLabel:'Descripción',
            name:'descripcionProducto',
            //itemId:'descripcionProducto',
            width:250
        });
        this.descripcionPro.on('specialkey', function(f, event){
            if(event.getKey() == event.ENTER) {
                pBuscaItem.main.aplicarFiltroByFormularioProducto();
            }
        }, this);
        this.cmbTipoItem = new Ext.form.ComboBox({
            store: new Ext.data.ArrayStore({
                id:0,
                fields:[
                'value',
                'display'
                ],
                data:[
                [1,'Producto'],
                [2,'Servicio']
                ]
            }),
            displayField:'display',
            fieldLabel:'Típo',
            valueField:'value',
            hiddenName:'cmb_tipo_item',
            typeAhead: true,
            mode: 'local',
            value:'1',
            width:80,
            forceSelection: true,
            triggerAction: 'all',
            selectOnFocus:true
        });
        this.buscarPro = new Ext.Button({
            text:'Buscar',
            iconCls:'iconBuscar',
            handler:function(){
                pBuscaItem.main.aplicarFiltroByFormularioProducto();

            }
        });
        this.limpiarPro = new Ext.Button({
            text:'Limpiar Filtro',
            iconCls:'iconLimpiar',
            handler:function(){

                pBuscaItem.main.limpiarFiltroProductos();

            }
        });
        this.fieldsetProductos = new Ext.form.FieldSet({
            title:'Parametros de Busqueda',
            items:[
            {
                layout:'column',
                border:false,
                defaults:{
                    layout:'form',
                    labelAlign:'top',
                    border:false
                },
                items:[
                {
                    columnWidth:.12,
                    items:[
                    this.cmbTipoItem
                    ]
                },
                {
                    columnWidth:.22,
                    items:[
                    this.codigoPro
                    ]
                },
                {
                    columnWidth:.4,
                    items:[
                    this.descripcionPro
                    ]
                }
                ]
            }
            ]
        });
        this.gridProductos = new Ext.grid.GridPanel({
            store:this.storeProductos,
            style:'padding:8px',
            height:250,
            width:773,
            loadMask:true,
            frame:true,
            stripeRows: true,
            autoScroll:true,
            stateful: true,
            columns:[
            new Ext.grid.RowNumberer(),
            {
                header:'Id',
                hidden:true,
                width:120,
                menuDisabled:true,
                dataIndex:'id_item'
            },

            {
                header:'Id',
                hidden:true,
                width:120,
                menuDisabled:true,
                dataIndex:'cod_item_forma'
            },

            {
                header:'Código',
                width:120,
                sortable: true,
                menuDisabled:true,
                dataIndex:'cod_item'
            },

            {
                header:'Descripción',
                width:410,
                sortable: true,
                menuDisabled:true,
                dataIndex:'descripcion1'
            }
            ],
            bbar: new Ext.PagingToolbar({
                pageSize: pBuscaItem.main.limitePaginacion,
                store: this.storeProductos,
                displayInfo: true,
                html:'<div style="padding-left:5px;color:black;font-size:10px;"><b>Nota: Doble Click sobre el producto a cargar</b></div>',
                displayMsg: '<span style="color:black">Registros: {0} - {1} de {2}</span>',
                emptyMsg: "<span style=\"color:black\">No se encontraron registros</span>"
            })
        });
        this.gridProductos.on('rowcontextmenu', function(grid, rowIndex, event){
            event.stopEvent();
            var record = pBuscaItem.main.storeProductos.getAt(rowIndex);
            var menu_importar = new Ext.menu.Menu({
                tbar:[
                {
                    xtype:'displayfield',
                    value:'x'
                }
                ],
                items:[
                {
                    text:'Ver '+record.data["descripcion1"]+" (<b>"+record.data["cod_item"]+"</b>)",
                    cls:'iconInformacion',
                    handler:function(){

                        Ext.Ajax.request({
                            method:'GET',
                            url:  'info_servicio_item.php',
                            params:{
                                cod:record.data["id_item"]
                            },
                            failure: function(form, action) {
                                Ext.MessageBox.alert('Error en transacción', action.result.msg);
                            },
                            success:function(result, request){
                                var win;
                                cerrarWin = new Ext.Button({
                                    text:'Cerrar Ventana',
                                    iconCls:'cancelar',
                                    handler:function(){
                                        win.close();
                                    }
                                });
                                seleccionar = new Ext.Button({
                                    text:'Seleccionar '+((record.data["cod_item_forma"]==1)?'producto':'servicio'),
                                    iconCls:'seleccionar',
                                    handler:function(){
                                        win.close();
                                        pBuscaItem.main.ocularWin();
                                        $.cargarItem({
                                            id_item:            record.data["id_item"],
                                            tipo_item:          record.data["cod_item_forma"],
                                            cod_item:           record.data["cod_item"],
                                            descripcion_item:   record.data["descripcion1"]
                                        });
                                    }
                                });
                                win =  new Ext.Window({
                                    title:'Información: '+record.data["descripcion1"],
                                    modal:true,
                                    iconCls:'iconTitulo',
                                    constrain:true,
                                    autoScroll:true,
                                    html:result.responseText,
                                    action:'hide',
                                    height:400,
                                    width:450,
                                    buttonAlign:'center',
                                    buttons:[
                                    seleccionar,
                                    cerrarWin
                                    ]
                                });
                                win.show();
                            }
                        });
                    }
                }
                ]
            });
            menu_importar.showAt(event.getXY());
        });
        //Evento Doble Click
        this.gridProductos.on('rowdblclick', function( grid, row, evt){
            this.record = pBuscaItem.main.storeProductos.getAt(row);
            pBuscaItem.main.ocularWin();
            $.cargarItem({
                id_item:            this.record.data["id_item"],
                tipo_item:          this.record.data["cod_item_forma"],
                cod_item:           this.record.data["cod_item"],
                descripcion_item:   this.record.data["descripcion1"]
            });
        });
        this.formProductos = new Ext.form.FormPanel({
            border:false,
            items:[
            this.fieldsetProductos
            ]
        });
        this.tabProductos = new Ext.Panel({
            title:'Productos/Servicios',
            style:'padding:5px;',
            items:[
            this.formProductos,
            {
                layout:'column',
                border:false,
                defaults:{
                    border:false
                },
                items:[
                {
                    columnWidth:.1,
                    items:[
                    this.buscarPro
                    ]
                },
                {
                    items:[
                    this.limpiarPro
                    ]
                }
                ]
            },
            this.gridProductos
            ],
            autoHeight:true
        });
        this.tabPanel = new Ext.TabPanel({
            title: '',
            activeTab:0,
            height:400,
            items:[
            this.tabProductos
            ]
        });
        this.win = new Ext.Window({
            title:'Busqueda de Item',
            modal:true,
            iconCls:'iconTitulo',
            constrain:true,
            action:'hide',
            frame:true,
            closable:false,
            width:800,
            items:[
            this.tabPanel
            ],
            autoHeight:true,
            buttonAlign:'center',
            buttons:[
            this.cancelar
            ]
        });
    },
    mostrarWin:function(){
        this.win.show();
        this.limpiarFiltroProductos();
        setTimeout(function(){
            pBuscaItem.main.codigoPro.focus();
        },200);
    },
    ocularWin:function(){
        this.win.setVisible(false);
    },
    limpiarFiltroProductos:function(){
        pBuscaItem.main.formProductos.getForm().reset();
        pBuscaItem.main.storeProductos.baseParams={};
        pBuscaItem.main.storeProductos.baseParams.opt = 'filtroItem';
        pBuscaItem.main.storeProductos.baseParams.limit = pBuscaItem.main.limitePaginacion;
        pBuscaItem.main.storeProductos.baseParams.start = pBuscaItem.main.iniciar;
        pBuscaItem.main.storeProductos.baseParams.tipo_item = 1;//productos
        pBuscaItem.main.storeProductos.load();
    },
    aplicarFiltroByFormularioProducto: function(){
        //Capturamos los campos con su value para posteriormente verificar cual
        //esta lleno y trabajar en base a ese.
        var campo = pBuscaItem.main.formProductos.getForm().getValues();
        pBuscaItem.main.storeProductos.baseParams={};
        var swfiltrar = false;
        for(campName in campo){
            if(campo[campName]!=''){
                swfiltrar = true;
                eval("pBuscaItem.main.storeProductos.baseParams."+campName+" = '"+campo[campName]+"';");
            }
        }
        pBuscaItem.main.storeProductos.baseParams.opt = 'filtroItem';
        pBuscaItem.main.storeProductos.baseParams.limit = pBuscaItem.main.limitePaginacion;
        pBuscaItem.main.storeProductos.baseParams.start = pBuscaItem.main.iniciar;
        pBuscaItem.main.storeProductos.baseParams.tipo_item = 1;//productos
        pBuscaItem.main.storeProductos.baseParams.BuscarBy = true;
        pBuscaItem.main.storeProductos.load();
    },
    getLista: function(){
        this.store = new Ext.data.JsonStore({
            url:'../../libs/php/ajax/ajax.php',
            params:{
                opt:'filtroItem'
            },
            root:'data',
            fields:[{name: 'id_item'},{name: 'cod_item_forma'},{name: 'cod_item'},{name: 'descripcion1'},]
        });
        return this.store;
    }
};
Ext.onReady(pBuscaItem.main.init,pBuscaItem.main);
/************* FIN DE PAQUETE *********************/
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
        $("input[name='input_cantidad_items']").attr("value",cantidad.toFixed(2));
        $.totalizarFactura();
    }
    $(".info_detalle").live("click", function(){
        cod = $(this).parent('tr').find("input[name='idItem_']").val();
        $.ajax({
            type: 'GET',
            data: 'cod='+cod,
            url:  'info_servicio_item.php',
            beforeSend: function(){
            //                $.facebox.loading();
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
                    title:'Información del item',
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
        campos += $.inputHidden("_item_piva",parseFloat(iva).toFixed(2),"[]");
        campos += $.inputHidden("_item_iva",piva,"[]");
        campos += $.inputHidden("_item_totalconiva",totalconiva.toFixed(2),"[]");
        campos += $.inputHidden("_item_descripcion",descripcion,"[]");
        if ($("input[name='cod_item_forma']").val() == 1) { // Si es igual al Producto
            $.ajax({
                //type: "GET",
                //url:  "../../libs/php/ajax/ajax.php",
                //data: "opt=precomprometeritem&v1="+codigo+"&cpedido="+cantidad+"&codalmacen="+almacen+"&codprecompromiso="+ContarInputItem,//+"&tipo_transaccion="+transaccion,
                beforeSend: function(){

                },
                success: function(data){
                    /*result = eval(data);
                    if(result[0].id=="-99"){
                       Ext.MessageBox.show({
                        title: 'Notificación',
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
                    }*/
                    html  = "<tr>";
                    html += "<td title=\"Haga click aqu&iacute; para ver detalles\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white; text-align: center;\" href=\"#info\">"+producto+"</a></td>";
                    html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+descripcion+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' rel='"+cantidad+"'>"+cantidad+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(preciosiniva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(descuento).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(montodescuento).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(totalsiniva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' title='"+$("#moneda").val()+" "+piva.toFixed(3)+"'>"+parseFloat(iva).toFixed(3)+"</td>";
                    html += "<td style='text-align: right; padding-right: 20px;' rel='"+parseFloat(piva).toFixed(3)+"'>"+totalconiva.toFixed(3)+"</td>";
                    html += "<td style='text-align: center;'><img style=\"cursor: pointer;\" class=\"eliminar\" title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
                    html += "</tr>";
                    $(".grid table.lista tbody").append(html);
                    $("#MostrarTabla").trigger("click");
                    fn_cantidad();
                }
            });
        }else{
            campos += '<input type="hidden" name="_pitem_almacen" value=""/>';
            campos += '<input type="hidden" name="_idpitem_almacen" value=""/>';

            html  = "<tr>";
            html += "<td title=\"Haga click aqui para ver detalles\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white; text-align: center;\" href=\"#info\">"+producto+"</a></td>";
            html += "<td style='text-align: left;' class=\"filter-column\" style=\"width:auto;\">"+descripcion+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' rel='"+cantidad+"'>"+cantidad+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(preciosiniva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(descuento).toFixed(3)+"</td>";//antes solo descuento
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(montodescuento).toFixed(3)+"</td>";//antes solo montodescuento
            html += "<td style='text-align: right; padding-right: 20px;'>"+parseFloat(totalsiniva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' title='"+$("#moneda").val()+" "+parseFloat(piva).toFixed(3)+"'>"+parseFloat(iva).toFixed(3)+"</td>";
            html += "<td style='text-align: right; padding-right: 20px;' rel='"+parseFloat(piva).toFixed(3)+"'>"+totalconiva.toFixed(3)+"</td>";
            html += "<td style='text-align: center;'><img style=\"cursor: pointer;\" class=\"eliminar\" title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\"/>"+campos+"</td>";
            html += "</tr>";
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
        if(dataitem==undefined){
            Ext.MessageBox.show({
                title: 'Notificación',
                msg: "Debe cargar un producto o servicio",
                buttons: Ext.MessageBox.OK,
                animEl: 'addTabla',
                icon: 'ext-mb-warning'
            });
            return false;
        }
        codigo = $("input[name='id_item']").val();
        //producto = $("#cod_item").val();////// dice "unddefined"
        //producto = $("select[name='cod_item']").val();////// dice "null"
        producto = dataitem[0]['cod_item'];//// salio bueno!!!
        descripcion = $("input[name='descripcion_input_item']").val();
        cantidad = $("#cantidadPedido").val();
        if(parseInt(cantidad)==0){
            Ext.MessageBox.show({
                title: 'Notificación',
                msg: "Debe especificar la cantidad!",
                buttons: Ext.MessageBox.OK,
                animEl: 'addTabla',
                icon: 'ext-mb-warning'
            });
            $("#cantidadPedido").focus();
            return false
        }
        preciosiniva =  $("#precioProductoPedido").val();
        if(preciosiniva==0){
            Ext.MessageBox.show({
                title: 'Notificación',
                msg: 'El campo precio sin Iva debe ser distinto de cero (0)!',
                buttons: Ext.MessageBox.OK,
                animEl: 'addTabla',
                icon: 'ext-mb-warning'
            });
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
        if(confirm('Incluir Pedido?') == false){
            return false;
        }
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
                    iva=(resultado[i]._item_piva!=0)&&(resultado[i]._item_piva!='')?parseFloat(resultado[i]._item_piva):0;
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
        //      $("input[name='cod_item_forma'], input[name='id_item']").removeAttr("disabled");
        //      $("input[name='id_item']").find("option").remove();
        $("input[name='cod_item_forma']").val("");
        $("#descripcion_tipo_forma").html("Item");
        $("#descripcion_item").html("");
        $("input[name='cod_item_forma'], input[name='id_item']").val("");
        $("#descripcion_tipo_forma").html("Item");
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
            /*Ext.MessageBox.show({
                   title: 'Notificación',
                   msg: "Disculpe, la cantidad pedida es mayor que la existente, verifique existencia.",
                   buttons: Ext.MessageBox.OK,
                   animEl: 'cantidadPedido',
                   icon: 'ext-mb-warning'
                });
                $(this).val("0").focus();
                return false;*/
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
            Ext.MessageBox.show({
                title: 'Notificación',
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
        $("#montodescuentoPedido").val(descuento);
        $("#totalPedido").val(total.toFixed(2));
    });
    $("#cod_almacen").change(function(){
        //LabelCantidadExistente
        codItem = $("input[name='id_item']").val();
        if($(this).val()!=''){
            if ($("input[name='cod_item_forma']").val() == 1) { // Si es igual al Producto
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
                            Ext.MessageBox.show({
                               title: 'Notificación',
                               msg: "Verifique existencia.",
                               buttons: Ext.MessageBox.OK,
                               animEl: 'cod_almacen',
                               icon: 'ext-mb-warning'
                            });
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

    //funcion que llama a la ventana de busqueda de items (Productos y servicios.
    $("#seleccionItem").click(function(){
        pBuscaItem.main.mostrarWin();
    });
    $.cargarItem = function(parametros){
        var item, tipo_item, cod_item, descripcion,_item;
        item = parametros.id_item;
        tipo_item = parametros.tipo_item;
        cod_item = parametros.cod_item;
        descripcion_item = parametros.descripcion_item;
        $("input[name='id_item']").val(item);
        $("input[name='cod_item_forma']").val(tipo_item)
        $("input[name='descripcion_input_item']").val(descripcion_item)
        $("input[name='tipo_item']").val(tipo_item);
        $("#descripcion_item").html("Codigo "+cod_item+", "+descripcion_item);
        $("#descripcion_tipo_forma").html(((tipo_item==1)?'Producto':'Servicio'));
        valor = item;
        if(valor==''){
            $("#cancelaradd").trigger("click");
            return false;
        }
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
            data: "opt=DetalleSelectitem&v1="+valor,
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
                    if(resultado[0].monto_exento==1){
                        string_detalle ="<b>Monto Exento:</b> Si";
                    }
                    if(resultado[0].monto_exento==0){
                        string_detalle ="<b>Monto Exento:</b> No | <b>Iva:</b> "+resultado[0].iva;
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
        if(tipo_item==1){ // Si es igual al Producto
            $.ajax({
                type: "GET",
                url:  "../../libs/php/ajax/ajax.php",
                data: "opt=CargarAlmacenesDisponiblesByIdItem&v1="+valor,
                beforeSend: function(){
                // $("#descripcion_item").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                },
                success: function(data){
                    resultado = eval(data)
                    /*if(resultado[0].id=="-1"){
                        $("#cancelaradd").trigger("click");
                        Ext.MessageBox.show({
                            title: 'Notificación',
                            msg: "Verifique existencia.",
                            buttons: Ext.MessageBox.OK,
                            animEl: 'addTabla',
                            icon: 'ext-mb-warning'
                        });
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
    }//fin $.cargarItem = function(parametros){

});
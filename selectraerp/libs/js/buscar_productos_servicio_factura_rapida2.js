/**
 * Creación de ventana para la busqueda de items.
 * Fecha de Creación: dom 4 de marzo de 2012, 10:49:51 PM
 *
 * Autor: Luis E. Viera Fernandez.
 * Correo:
 *  lviera@armadillotec.com
 *  lviera86@gmail.com
 *
 * Adaptaciones y Mejoras: Charli J. Vívenes Rengel.
 * Correo:
 *  cvivenes@asys.com.ve
 *  cjvrinf@gmail.com
 *
 */

Ext.ns("pBuscaItem");
/**
 * @class pBuscaItem
 */
pBuscaItem.main = {
    limitePaginacion:10,
    iniciar:0,
    init:function(){
        this.valor = 0;
        this.storeProductos = this.getLista();
        this.cancelar = new Ext.Button({
            text:'Cerrar ventana',
            iconCls:'cancelar',
            handler:function(){
                $("input[name='filtro_codigo']").focus();
                pBuscaItem.main.ocultarWin();
            }
        });
        this.seleccionar = new Ext.Button({
            text:'Selecionar Producto',
            iconCls:'seleccionar',
            handler:function(){
                //En construcción...
            }
        });
        this.codigoPro = new Ext.form.TextField({
            fieldLabel:'Código',
            name:'codigoProducto'
        });
        ///////////////////////////////////////////////////////////////////////////
        this.codigoBarrasPro = new Ext.form.TextField({
            fieldLabel:'Código de Barra',
            name:'codigoBarrasProducto',
            width:140
        });
        this.codigoBarrasPro.on('specialkey', function(f, event){
            if(event.getKey() == event.ENTER) {
                pBuscaItem.main.aplicarFiltroByFormularioProducto();
            }
        }, this);
        //this.codigoBarrasProd.focus();
        ///////////////////////////////////////////////////////////////////////////
        this.codigoPro.on('specialkey', function(f, event){
            if(event.getKey() == event.ENTER) {
                pBuscaItem.main.aplicarFiltroByFormularioProducto();
            }
        }, this);
        this.descripcionPro = new Ext.form.TextField({
            fieldLabel:'Nombre',
            name:'descripcionProducto',
            width:200
        });
        this.descripcionPro.on('specialkey', function(f, event){
            if(event.getKey() == event.ENTER) {
                pBuscaItem.main.aplicarFiltroByFormularioProducto();
            }
        }, this);
        this.referencia = new Ext.form.TextField({
            fieldLabel:'Referencia',
            name:'referencia',
            width:90
        });
        this.referencia.on('specialkey', function(f, event){
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
            width:70,
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
            title:'Parámetros de Busqueda',
            //defaultButtom:codigoBarrasPro,
            //tabIndex:1,
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
                    columnWidth:.10,
                    items:[
                    this.cmbTipoItem
                    ]
                },
                {
                    columnWidth:.19,
                    items:[
                    this.codigoPro
                    ]
                },
                {
                    columnWidth:.21,
                    items:[
                    this.codigoBarrasPro
                    ]
                },
                {
                    columnWidth:.15,
                    items:[
                    this.referencia
                    ]
                },
                {
                    columnWidth:.3,
                    items:[
                    this.descripcionPro
                    ]
                }
                ]
            }
            ]
        });
        
        this.renderImagenProducto = function(val, meta, record, rowIndex, colIndex, store) {
            var html = "";
            var nombreImagenFoto = store.data.itemAt(rowIndex).json.foto;
            if ( !_.str.isBlank(nombreImagenFoto) ) {
                html = "<img width='70' src='../../imagenes/"+nombreImagenFoto+"'>";
            }else{
                html = "<img width='70' src='../../imagenes/sin_imagen.jpg'>";
            }
            return html;
        }
    
        this.gridProductos = new Ext.grid.GridPanel({
            store:this.storeProductos,
            style:'padding:8px',
            height:320,
            width:773,
            loadMask:true,
            frame:true,
            stripeRows: true,
            autoScroll:true,
            stateful: true,
            columns:[
                new Ext.grid.RowNumberer(),
                {header:'Img', width:120, height:300,  renderer: this.renderImagenProducto, menuDisabled:true, dataIndex:'foto'},
                {header:'Id', hidden:true, width:120, menuDisabled:true, dataIndex:'posee_talla_color'},
                {header:'Id', hidden:true, width:120, menuDisabled:true, dataIndex:'id_item'},
                {header:'Id', hidden:true, width:120, menuDisabled:true, dataIndex:'cod_item_forma'},
                {header:'Código', width:120, sortable: true, menuDisabled:true, dataIndex:'cod_item'},
                {header:'Código de Barras', width:120, sortable: true, menuDisabled:true, dataIndex:'codigo_barras'},
                {header:'Descripción', width:410, sortable: true, menuDisabled:true, dataIndex:'descripcion1'}
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
                                        pBuscaItem.main.ocultarWin();

                                        var filtro_referencia = record.data["cod_item"];
                                        $("input[name='filtro_codigo']").val(filtro_referencia);
                                        $.filtrarArticulo(filtro_referencia);
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
            //window.alert(pBuscaItem.main.valor);
            this.record = pBuscaItem.main.storeProductos.getAt(row);
            //Colocacion del focus para el input HZ
            $("#codigo_barras"+pBuscaItem.main.valor).val(this.record.data["codigo_barras"]).focus();
            pBuscaItem.main.ocultarWin();

            

            var filtro_referencia = this.record.data["id_item"];
            //$("input[name='filtro_codigo']").val(this.record.data["cod_item"]);
            
            //$.filtrarArticulo(filtro_referencia);
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
            height:470,
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
            //this.seleccionar,
            this.cancelar
            ]
        });
    },
    mostrarWin:function( num ){
        
        this.win.show();
        this.limpiarFiltroProductos();
        pBuscaItem.main.valor = num;
        //window.alert(pBuscaItem.main.valor);
        setTimeout(function(){
            //pBuscaItem.main.codigoPro.focus();
            pBuscaItem.main.codigoBarrasPro.focus();
        },200);
    },
    ocultarWin:function(){
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
            fields:[
            {
                name: 'id_item'
            },
            {
                name: 'posee_talla_color'
            },
            {
                name: 'cod_item_forma'
            },
            {
                name: 'cod_item'
            },
            {
                name: 'codigo_barras'
            },
            {
                name: 'descripcion1'
            },
            ]
        });
        return this.store;
    }
};
Ext.onReady(pBuscaItem.main.init,pBuscaItem.main);
/************* FIN DE PAQUETE *********************/

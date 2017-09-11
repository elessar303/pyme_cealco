var record, formCXPpendientes, wintmpCXPpendientes;
var storeCXPpendientes = new Ext.data.JsonStore({
    url:'../../libs/php/ajax/ajax.php',
    baseParams:{opt:'listaCXPpendientes'},
    root: 'data',
    totalRecords:'total',
    fields: [
        {name: 'beneficiario'},
        {name: 'id_proveedor'},
        {name: 'rif'},
        {name: 'cod_edocuenta'},
        {name: 'documento'},
        {name: 'numero'},
        {name: 'sum_debito', type:'float'},
        {name: 'sum_credito', type:'float'},
        {name: 'monto_pagar', type:'float'},
    ]
});
storeCXPpendientes.load();

var gripCXPpendientes = new Ext.grid.GridPanel({
    tbar:[{
            text:'Actualizar Lista',
            handler:function(){
                storeCXPpendientes.load();
            }
    },'-','Haga <b>doble click</b> sobre la fila'
    ],
    store: storeCXPpendientes,
    columns:[
        new Ext.grid.RowNumberer(),
        {header:'ID', dataIndex:'id_proveedor', hidden:true},
        {header:'<b>Beneficiario</b>', dataIndex:'beneficiario',sortable: true},
        {header:'<b>R.I.F</b>', dataIndex:'rif',sortable: true},
        {header:'<span style=\'color:red\'><b>Total Por Pagar</b></span>', dataIndex:'monto_pagar', autoWidth:true,sortable: true},
        {header:'<span style=\'color:#101010;\'><b>Total Debito</b></span>', dataIndex:'sum_debito', autoWidth:true,sortable: true},
        {header:'<span style=\'color:#101010\'><b>Total Credito</b></span>', dataIndex:'sum_credito', autoWidth:true,sortable: true},
        {header:'Edo. Cuenta', dataIndex:'cod_edocuenta',sortable: true},
        {header:'Documento', dataIndex:'documento', width:150,sortable: true},
        {header:'Numero', dataIndex:'numero', width:150,sortable: true},
        ],
    bbar: [
        new Ext.PagingToolbar({
            store: storeCXPpendientes,
            displayInfo: true,
            autoWidth: true,
            displayMsg: '{0} - {1} de {2} CxP Pendientes por Cancelar',
            emptyMsg: 'Error con el servidor',
            pageSize: 10
        })
    ],
    listeners: {
        'rowdblclick': function(grid, rowIndex, e){
            record = grid.getStore().getAt(rowIndex);
            if(!formCXPpendientes){
            formCXPpendientes = new Ext.form.FormPanel({
                border:false,
                labelWidth: 80,
                defaults: {
                xtype:'textfield',
                width: 150
                },
                items:[
                {id:'rif',xtype:'textfield',fieldLabel:'R.I.F',name:'rif',readOnly:true},
                {id:'beneficiario',xtype:'textfield',fieldLabel:'Beneficiario',name:'beneficiario',readOnly:true},
                {id:'cod_edocuenta',xtype:'textfield',fieldLabel:'Nro. CxP',name:'cod_edocuenta',readOnly:true},
                {xtype:'hidden',id:'id_proveedor',name:'id_proveedor'},
                {id:'documento',xtype:'textfield',fieldLabel:'Documento',name:'documento',readOnly:true},
                {id:'numero',xtype:'textfield',fieldLabel:'Numero',name:'numero',readOnly:true},
                //{id:'monto_pagar',xtype:'textfield',fieldLabel:'Monto Pendiente',name:'monto_pagar',readOnly:true},
                {xtype:'fieldset',title:'Detalle de monto a Pagar',autoWidth:true,defaults: {width: 210},
                    items:  [
                        {
                            id:'monto_pagar',
                            xtype:'numberfield',
                            fieldLabel:'Monto a Pagar',
                            name:'monto_pagar',
                            allowBlank:false,
                            blankText:'Debe ingresar el monto del pago'
                        },
                        {
                            id:'fechapago',
                            xtype:'datefield',
                            labelWidth: 75, // label settings here cascade unless overridden
                            fieldLabel:'Fecha del Pago',
                            name:'fechapago',
                            format:'d/m/Y',
                            value: new Date(),
                            allowBlank:false
                        },
                        {
                            xtype:'textarea',
                            id:'concepto_cheque',
                            name:'concepto_cheque',
                            width:300,
                            labelWidth: 75, // label settings here cascade unless overridden
                            fieldLabel:'Concepto',
                            allowBlank:false
                        },
                        {
                            xtype:'textfield',
                            id:'num_transf',
                            name:'num_transf',
                            labelWidth: 75, // label settings here cascade unless overridden
                            fieldLabel:'Transferencia Numero',
                            allowBlank:false
                        },
                        {
                            xtype:'textfield',
                            id:'ref',
                            name:'ref',
                            labelWidth: 75, // label settings here cascade unless overridden
                            fieldLabel:'Referencia',
                            allowBlank:false
                        }
                            ]
                }
                
                ]
            });
            }
            
            formCXPpendientes.getForm().loadRecord(record);


            if(!wintmpCXPpendientes){
            wintmpCXPpendientes = new Ext.Window({
                title: 'Información del Pago',
                width:500,
                height:470,
                closeAction :'hide',
                modal: true,
                contraint: true,
                bodyStyle: 'padding:10px;background-color:#fff',
                autoScroll:true,
                items: [formCXPpendientes],
                buttons: [
                    {text:'Cargar Información',
                        handler: function(){
                           if(formCXPpendientes.getForm().isValid()){
                                //monto_pago = Ext.get("monto_pago").getValue();
                                monto_pago = Ext.get("monto_pagar").getValue();

                                id_proveedor = Ext.get("id_proveedor").getValue();
                                beneficiario = Ext.get("beneficiario").getValue();
                                rif = Ext.get("rif").getValue();
                                
                                edocuenta  = Ext.get("cod_edocuenta").getValue();
                                numcompra  = Ext.get("numero").getValue();
                                cam_fechapago  = Ext.get("fechapago").getValue();
				cam_num_transf  = Ext.get("num_transf").getValue();
				cam_ref  = Ext.get("ref").getValue();


                                Ext.get("cam_beneficiario").dom.value=beneficiario;
                                Ext.get("cam_rif").dom.value=rif;
                                Ext.get("cam_cod_edocuenta").dom.value=edocuenta;
                                Ext.get("cam_id_proveedor").dom.value=id_proveedor;
                                Ext.get("cam_numero_compra").dom.value=numcompra;
                                Ext.get("cam_fechapago").dom.value=cam_fechapago;
                                Ext.get("cam_monto_pago").dom.value=monto_pago;
                                Ext.get("cam_concepto_cheque").dom.value=Ext.get("concepto_cheque").getValue();
				Ext.get("cam_transferencia_numero").dom.value=cam_num_transf;
				Ext.get("cam_referencia").dom.value=cam_ref;
                                Ext.Ajax.request({
    url:'../../libs/php/ajax/ajax.php',
    method:'GET',
    params:{opt:'convertiraLetras',monto:monto_pago},
    success:function(result, request){
        var jsonData = Ext.util.JSON.decode(result.responseText);
        Ext.get("cam_monto_leltra").dom.value= jsonData.monto;

    }
});

                                winCXPpendientes.hide();
                                wintmpCXPpendientes.hide();
                                
                           }else{
                               Ext.Msg.alert("Alerta","Disculpe los campos de Detalle de pago");
                           }
                        }
                    },
                    {text:'Cancelar',
                        handler:function(){
                            wintmpCXPpendientes.hide();
                        }
                    }
                ]


            });
            }
            wintmpCXPpendientes.show();

            

        }
    },
  
    border: false,
    stripeRows: true
});

var winCXPpendientes = new Ext.Window({
    title:'Cuentas por Pagar Pendientes',
    width: 950,
    layout:'fit',
    contraint:true,
    height: 400,
    closeAction:'hide',
    modal:true,
    items:[gripCXPpendientes],
    buttons:[{text:'Cancelar',handler:function(){winCXPpendientes.hide();}}]
});

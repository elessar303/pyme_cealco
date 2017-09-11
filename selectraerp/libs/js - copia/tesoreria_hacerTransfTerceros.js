var record, formTerceros, wintmpTerceros;

var storeProveedores = new Ext.data.JsonStore({
    url:'../../libs/php/ajax/ajax.php',
    baseParams:{opt:'listaProveedores'},
    root: 'data',
    totalRecords:'total',
    fields: [
        {name: 'id_proveedor'},
        {name: 'cod_proveedor'},
        {name: 'beneficiario'},
        {name: 'direccion'},
        {name: 'telefonos'},
        {name: 'fax'},
        {name: 'email'},
        {name: 'rif'},
        {name: 'nit'}
    ]
});
storeProveedores.load();


var gripTerceros = new Ext.grid.GridPanel({
    tbar:[{
            text:'Actualizar Lista',
            handler:function(){
                storeProveedores.load();
            }
    },'-','Haga <b>doble click</b> sobre la fila'
    ],
    store: storeProveedores,
    columns:[
            new Ext.grid.RowNumberer(),
            {header:'ID', dataIndex:'id_proveedor', hidden:true},
            {header:'<b>Beneficiario</b>', dataIndex:'beneficiario',sortable: true},
            {header:'<b>R.I.F</b>', dataIndex:'rif',sortable: true},
            {header:'<b>Dirección</b>', dataIndex:'direccion',sortable: true},
            {header:'<b>Telefonos</b>', dataIndex:'telefonos',sortable: true},
            {header:'<b>Email</b>', dataIndex:'email',sortable: true},
            {header:'<b>Nit</b>', dataIndex:'nit',sortable: true},
        ],
    bbar: [
        new Ext.PagingToolbar({
            store: storeProveedores,
            displayInfo: true,
            autoWidth: true,
            displayMsg: '{0} - {1} de {2}',
            emptyMsg: 'Error con el servidor',
            pageSize: 10
        })
    ],
    listeners: {
        'rowdblclick': function(grid, rowIndex, e){
            record = grid.getStore().getAt(rowIndex);
            if(!formTerceros){
            formTerceros = new Ext.form.FormPanel({
                border:false,
                labelWidth: 80,
                defaults: {
                xtype:'textfield',
                width: 150
                },
                items:[
                {id:'rifter',xtype:'textfield',fieldLabel:'R.I.F',name:'rif',readOnly:true},
                {id:'beneficiarioter',xtype:'textfield',fieldLabel:'Beneficiario',name:'beneficiario',readOnly:true},
                {xtype:'hidden',id:'id_proveedorter',name:'id_proveedor'},
                {xtype:'fieldset',title:'Detalle de monto a Pagar',autoWidth:true,defaults: {width: 210},
                    items:  [
                        {
                            id:'monto_pagarter',
                            xtype:'numberfield',
                            fieldLabel:'Monto a Pagar',
                            name:'monto_pagar',
                            allowBlank:false,
                            blankText:'Debe ingresar el monto del pago'
                        },
                        {
                            id:'fechapagoter',
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
                            id:'concepto_chequeter',
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
            
            formTerceros.getForm().loadRecord(record);


            if(!wintmpTerceros){
            wintmpTerceros = new Ext.Window({
                title: 'Información del Pago',
                width:500,
                autoHeight:true,
                closeAction :'hide',
                modal: true,
                contraint: true,
                bodyStyle: 'padding:10px;background-color:#fff',
                autoScroll:true,
                items: [formTerceros],
                buttons: [
                    {text:'Cargar Información',
                        handler: function(){
                           if(formTerceros.getForm().isValid()){
                                //monto_pago = Ext.get("monto_pago").getValue();
                                monto_pago = Ext.get("monto_pagarter").getValue();

                                id_proveedor = Ext.get("id_proveedorter").getValue();
                                beneficiario = Ext.get("beneficiarioter").getValue();
                                rif = Ext.get("rifter").getValue();

                                edocuenta  = 0;//Ext.get("cod_edocuenta").getValue();
                                numcompra  = 0;//Ext.get("numero").getValue();
                                cam_fechapago  = Ext.get("fechapagoter").getValue();
				cam_num_transf  = Ext.get("num_transf").getValue();
				cam_ref  = Ext.get("ref").getValue();

                                Ext.get("cam_beneficiario").dom.value=beneficiario;
                                Ext.get("cam_rif").dom.value=rif;
                                Ext.get("cam_cod_edocuenta").dom.value=edocuenta;
                                Ext.get("cam_id_proveedor").dom.value=id_proveedor;
                                Ext.get("cam_numero_compra").dom.value=numcompra;
                                Ext.get("cam_fechapago").dom.value=cam_fechapago;
                                Ext.get("cam_monto_pago").dom.value=monto_pago;
                                Ext.get("opt").dom.value="guardarChequeTer";
                                Ext.get("cam_concepto_cheque").dom.value=Ext.get("concepto_chequeter").getValue();
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

                                winT.hide();
                                wintmpTerceros.hide();
                                
                           }else{
                               Ext.Msg.alert("Alerta","Disculpe los campos de Detalle de pago");
                           }
                        }
                    },
                    {text:'Cancelar',
                        handler:function(){
                            wintmpTerceros.hide();
                        }
                    }
                ]


            });
            }
            wintmpTerceros.show();

            

        }
    },
  
    border: false,
    stripeRows: true
});

var winT = new Ext.Window({
    title:'Lista de Proveedores',
    width: 950,
    layout:'fit',
    contraint:true,
    height: 400,
    closeAction:'hide',
    modal:true,
    items:[gripTerceros],
    buttons:[{text:'Cancelar',handler:function(){winT.hide();}}]
});

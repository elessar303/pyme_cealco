Ext.ns("pChequeVarios");

pChequeVarios.main = {
    id_proveedor:0,
    sumPago:0,
    cxpVariasNComp:[],
    cxpVariasIdCxP:[],
    rifProveedor:'',
    nombreProveedor:'',
    documento:'',
    fvoidFormPago: function(){
        if(!this.formCXPpendientes){
            this.formCXPpendientes = new Ext.form.FormPanel({
            border:false,
            labelWidth: 80,
            defaults: {
            xtype:'textfield',
            width: 150
            },
            items:[
            {id:'rif_pv',xtype:'textfield',fieldLabel:'R.I.F',name:'rif_pv',readOnly:true},
            {id:'beneficiario_pv',xtype:'textfield',fieldLabel:'Beneficiario',name:'beneficiario_pv',readOnly:true},
            {id:'cod_edocuenta_pv',xtype:'textfield',fieldLabel:'Nro. CxP',name:'cod_edocuenta_pv',readOnly:true},
            {xtype:'hidden',id:'id_proveedor_pv',name:'id_proveedor_pv'},
            {id:'documento_pv',xtype:'textfield',fieldLabel:'Documento',name:'documento_pv',readOnly:true},
            {id:'numero_pv',xtype:'textfield',fieldLabel:'Numero',name:'numero_pv',readOnly:true},
            {xtype:'fieldset',title:'Detalle de monto a Pagar',autoWidth:true,defaults: {width: 210},
            items:  [
                {
                    id:'monto_pagar_pv',
                    xtype:'numberfield',
                    fieldLabel:'Monto a Pagar',
                    name:'monto_pagar_pv',
                    allowBlank:false,
                    readOnly:true,
                    blankText:'Debe ingresar el monto del pago'
                },
                {
                    id:'fechapago_pv',
                    xtype:'datefield',
                    labelWidth: 75, // label settings here cascade unless overridden
                    fieldLabel:'Fecha del Pago',
                    name:'fechapago_pv',
                    format:'d/m/Y',
                    value: new Date(),
                    allowBlank:false
                },
                {
                    xtype:'textarea',
                    id:'concepto_cheque_pv',
                    name:'concepto_cheque_pv',
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

                if(!this.wintmpCXPpendientes){
                this.wintmpCXPpendientes = new Ext.Window({
                    title: 'Información del Pago',
                    width:500,
                    height:470,
                    closeAction :'hide',
                    modal: true,
                    constrain: true,
                    bodyStyle: 'padding:10px;background-color:#fff',
                    autoScroll:true,
                    items: [pChequeVarios.main.formCXPpendientes],
                    buttons: [
                        {text:'Cargar Información',
                            handler: function(){
                               if(pChequeVarios.main.formCXPpendientes.getForm().isValid()){
                                    //monto_pago = Ext.get("monto_pago").getValue();
                                    monto_pago = Ext.get("monto_pagar_pv").getValue();

                                    id_proveedor = Ext.get("id_proveedor_pv").getValue();
                                    beneficiario = Ext.get("beneficiario_pv").getValue();
                                    rif = Ext.get("rif_pv").getValue();

                                    edocuenta  = Ext.get("cod_edocuenta_pv").getValue();
                                    numcompra  = Ext.get("numero_pv").getValue();
                                    cam_fechapago  = Ext.get("fechapago_pv").getValue();
				cam_num_transf  = Ext.get("num_transf").getValue();
				cam_ref  = Ext.get("ref").getValue();


                                    Ext.get("cam_beneficiario").dom.value=beneficiario;
                                    Ext.get("cam_rif").dom.value=rif;
                                    Ext.get("cam_cod_edocuenta").dom.value=edocuenta;
                                    Ext.get("cam_id_proveedor").dom.value=id_proveedor;
                                    Ext.get("cam_numero_compra").dom.value=numcompra;
                                    Ext.get("cam_fechapago").dom.value=cam_fechapago;
                                    Ext.get("cam_monto_pago").dom.value=monto_pago;
                                    Ext.get("opt").dom.value="guardarChequeVariasCxP";
                                    Ext.get("cam_concepto_cheque").dom.value=Ext.get("concepto_cheque_pv").getValue();
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

                                    pChequeVarios.main.winCXPpendientes.hide();
                                    pChequeVarios.main.wintmpCXPpendientes.hide();

                               }else{
                                   Ext.Msg.alert("Alerta","Disculpe los campos de Detalle de pago");
                               }
                            }
                        },
                        {text:'Cancelar',
                            handler:function(){
                                pChequeVarios.main.wintmpCXPpendientes.hide();
                            }
                        }
                    ]


                });
                }

                pChequeVarios.main.wintmpCXPpendientes.show();
                Ext.get("id_proveedor_pv").dom.value = pChequeVarios.main.id_proveedor;
                Ext.get("monto_pagar_pv").dom.value = pChequeVarios.main.montoPago.getValue();
                Ext.get("rif_pv").dom.value = pChequeVarios.main.rifProveedor;
                Ext.get("beneficiario_pv").dom.value= pChequeVarios.main.nombreProveedor;
                Ext.get("documento_pv").dom.value= pChequeVarios.main.documento;

                Ext.get("cod_edocuenta_pv").dom.value= pChequeVarios.main.cxpVariasIdCxP.join("/");
                Ext.get("numero_pv").dom.value= pChequeVarios.main.cxpVariasNComp.join("/");




    },
    fvoid:function(){
    this.storeCXPpendientes = this.getStoreCXPpendientes();
    this.storeCXPpendientesCombo = this.getStoreCXPpendientesBene();
    pChequeVarios.main.id_proveedor=0;

    this.montoPago = new Ext.form.TextField({
       name:'montoapagar',
       readOnly:true
    });

    this.botonPago = new Ext.Button({
       text:'Pagar',
       handler:function(){
        if(pChequeVarios.main.id_proveedor==0){
            Ext.Msg.alert("Alerta","Debe seleccionar un proveedor");
            return false;
        }

        if(pChequeVarios.main.montoPago>0){
            Ext.Msg.alert("Alerta","El monto a pagar debe ser mayor a 0.");
            return false;
        }

        if(pChequeVarios.main.cxpVariasNComp.length<2){
            Ext.Msg.alert("Alerta","Por esta opción usted debe seleccionar mas de un pago.");
            return false;
        }



        pChequeVarios.main.fvoidFormPago();


       }
    });

    this.displaylabel = new Ext.form.DisplayField({
       html:'Monto a Pagar'
    });

    this.beneficiario = new Ext.form.ComboBox({
        resizable:true,
        store: this.storeCXPpendientesCombo,
        typeAhead: true,
        valueField: 'id_proveedor',
        displayField:'beneficiario',
        hiddenName:'id_proveedor',
        forceSelection:true,
        name: '_beneficiario',
        triggerAction: 'all',
        emptyText:'Seleccione Beneficiario',
        selectOnFocus: true,
        mode: 'local',
        width:200,
        resizable:true
    });

    this.beneficiario.on("select",function(combo,record,index){
        this.reader = pChequeVarios.main.storeCXPpendientesCombo.getAt(index);
        this.codigo = this.reader.data["id_proveedor"];
        pChequeVarios.main.id_proveedor = this.codigo;
    });


    this.Filtrar = new Ext.Button({
       text:'Filtrar',
       handler:function(){
        if(pChequeVarios.main.id_proveedor==0){
            Ext.Msg.alert("Alerta","Debe seleccionar un proveedor");
            return false;
        }
        pChequeVarios.main.rifProveedor="";
        pChequeVarios.main.nombreProveedor="";
        pChequeVarios.main.documento = "";
        pChequeVarios.main.montoPago.setValue(0);
        pChequeVarios.main.storeCXPpendientes.load({params:{id_proveedor:pChequeVarios.main.id_proveedor}});
       }
    });
    
    this.Limpiar = new Ext.Button({
       text:'Limpiar',
       handler:function(){
                    pChequeVarios.main.rifProveedor="";
                    pChequeVarios.main.nombreProveedor="";
                    pChequeVarios.main.documento
                    pChequeVarios.main.storeCXPpendientes.load({params:{id_proveedor:0}});
                    pChequeVarios.main.beneficiario.setValue("");
                    pChequeVarios.main.id_proveedor=0;
                    pChequeVarios.main.montoPago.setValue(0);
       }
    });

    this.compositefield_ = new Ext.form.CompositeField({
        fieldLabel:'Beneficiario',
        items:[
            this.beneficiario,
            this.Filtrar,
            this.Limpiar,
            this.displaylabel,
            this.montoPago,
            this.botonPago
        ]
    });



    this.storeCXPpendientesCombo.load();


    this.seleccionmodal = new Ext.grid.CheckboxSelectionModel({
        singleSelect: false,
        listeners: {
            'rowselect': function(s,ri,rd){
                pChequeVarios.main.cxpVariasNComp = [];
                pChequeVarios.main.cxpVariasIdCxP = [];
                pChequeVarios.main.sumPago=0;
                pChequeVarios.main.seleccionmodal.each(function(rec){
                    pChequeVarios.main.cxpVariasNComp.push((rec.get("numero")).replace(" ",""));
                    pChequeVarios.main.cxpVariasIdCxP.push((rec.get("cod_edocuenta")).replace(" ",""));
                    pChequeVarios.main.sumPago += rec.get("monto_pagar");

                    pChequeVarios.main.rifProveedor=rec.get("rif");
                    pChequeVarios.main.documento = rec.get("documento");
                    pChequeVarios.main.nombreProveedor=rec.get("beneficiario");
                });
                pChequeVarios.main.montoPago.setValue((eval(pChequeVarios.main.sumPago)).toFixed(2));
            },
            rowdeselect: function(s,ri,rd){
                pChequeVarios.main.cxpVariasNComp = [];
                pChequeVarios.main.cxpVariasIdCxP = [];
                pChequeVarios.main.sumPago=0;
                pChequeVarios.main.seleccionmodal.each(function(rec){
                    pChequeVarios.main.cxpVariasNComp.push(rec.get("numero"));
                    pChequeVarios.main.cxpVariasIdCxP.push(rec.get("cod_edocuenta"));
                    pChequeVarios.main.sumPago += rec.get("monto_pagar");
                });
                pChequeVarios.main.montoPago.setValue(pChequeVarios.main.sumPago);
            }
        }
    });

    this.gripCXPpendientes = new Ext.grid.GridPanel({
        tbar:[{
                text:'Actualizar Lista',
                handler:function(){
                    if(pChequeVarios.main.id_proveedor==0){
                        Ext.Msg.alert("Alerta","Debe seleccionar un proveedor");
                        return false;
                    }
                    pChequeVarios.main.montoPago.setValue(0);
                    pChequeVarios.main.storeCXPpendientes.load({params:{id_proveedor:pChequeVarios.main.id_proveedor}});
                }
        },'-','Haga <b>doble click</b> sobre la fila'
        ],
        store: this.storeCXPpendientes,
        sm:this.seleccionmodal,
        columns:[
                new Ext.grid.RowNumberer(),
                this.seleccionmodal,
                {header:'ID', dataIndex:'id_proveedor', hidden:true},
                {header:'<b>Beneficiario</b>', menuDisabled:true,dataIndex:'beneficiario',sortable: true},
                {header:'<b>R.I.F</b>', dataIndex:'rif',menuDisabled:true,sortable: true},
                {header:'<span style=\'color:red\'><b>Total Por Pagar</b></span>',menuDisabled:true, dataIndex:'monto_pagar', autoWidth:true,sortable: true},
                {header:'<span style=\'color:#101010;\'><b>Total Debito</b></span>',menuDisabled:true, dataIndex:'sum_debito',hidden:true, autoWidth:true,sortable: true},
                {header:'<span style=\'color:#101010\'><b>Total Credito</b></span>',menuDisabled:true, dataIndex:'sum_credito',hidden:true, autoWidth:true,sortable: true},
                {header:'Edo. Cuenta',menuDisabled:true, hidden:true, dataIndex:'cod_edocuenta',sortable: true},
                {header:'Documento',menuDisabled:true, dataIndex:'documento', width:150,sortable: true},
                {header:'Numero',menuDisabled:true, dataIndex:'numero', width:150,sortable: true},
            ],
        bbar:[
            new Ext.PagingToolbar({
                store: pChequeVarios.main.storeCXPpendientes,
                displayInfo: true,
                autoWidth: true,
                displayMsg: '{0} - {1} de {2} CxP Pendientes por Cancelar',
                emptyMsg: 'Error con el servidor',
                pageSize: 10
            })
        ],
        height:200,
        border: false,
        stripeRows: true
    });
    
    this.panel_ = new Ext.FormPanel({
       title:'Buscar Proveedor',
       width:400,
       frame:true,
       autoHeight:true,
       items:[
           this.compositefield_,
           this.gripCXPpendientes
       ]
    });

    this.winCXPpendientes = new Ext.Window({
        title:'Cuentas por Pagar Pendientes',
        width: 950,
        closable:false,
        layout:'fit',
        contraint:true,
        autoHeight:true,
        closeAction:'hide',
        modal:true,
        items:[this.panel_ ],
        buttons:[{text:'Cancelar',handler:function(){pChequeVarios.main.winCXPpendientes.hide();}}]
    });
    this.winCXPpendientes.show();


  },
  getStoreCXPpendientes:function(){
   return new Ext.data.JsonStore({
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
  },
  getStoreCXPpendientesBene:function(){
   return new Ext.data.JsonStore({
        url:'../../libs/php/ajax/ajax.php',
        baseParams:{opt:'listaCXPpendientes',groupBene:'si'},
        root: 'data',
        totalRecords:'total',
        fields: [
            {name: 'beneficiario'},
            {name: 'id_proveedor'}
        ]
    });
  }
};
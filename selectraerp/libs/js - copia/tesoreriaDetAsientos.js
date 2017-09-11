Ext.ns("DetAsiento");
DetAsiento.main = {
  codCheque_:0,
  init:function(){
        this.store = this.getStoreAsientosBy();
        this.storeCuContable = this.getStoreCuContable();
  },
  detalle_asiento:function(opcion){
        DetAsiento.main.codCheque_ = opcion.ID;
        Ext.ux.grid.GroupSummary.Calculations['totalDebitos'] = function(d, record, field){
            if(!isNaN(parseFloat(record.data.debito))){
                d+=parseFloat(record.data.debito);
            }
            return d;
        };
        
        Ext.ux.grid.GroupSummary.Calculations['totalCreditos'] = function(c, record, field){
            if(!isNaN(parseFloat(record.data.credito))){
                c+=parseFloat(record.data.credito);
            }
            return c;
        };
        this.summary = new Ext.ux.grid.GroupSummary();
        
        this.gridDetAsientos = new Ext.grid.GridPanel({
           frame:true,
           height:160,
           loadMask:true,
           store:this.store,
           columns:[
               new Ext.grid.RowNumberer(),
               {header:'codigo',dataIndex:'cod_cheque', hidden:true, width:230, sortable: true},
               {header:'',dataIndex:'cod_cheque_bauchedet', hidden:true, width:230, sortable: true},
               {header:'Descripción',dataIndex:'descripcion', width:230,menuDisabled:true, sortable: true},
               {header:'Cuenta contable',dataIndex:'cuenta_contable',menuDisabled:true, sortable: true},
               {header:'Debito',dataIndex:'debito',summaryType: 'totalDebitos',align:'right', menuDisabled:true,xtype: 'numbercolumn',format: '0,0.00', sortable: true},
               {header:'Credito',dataIndex:'credito',summaryType: 'totalCreditos',align:'right',menuDisabled:true,xtype: 'numbercolumn',format: '0,0.00', sortable: true}
           ],
           view: new Ext.grid.GroupingView({
                forceFit:false,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Asientos - Detalle Cheque N° '+opcion.ID+'" : "Asiento - Detalle Cheque N° '+opcion.ID+'"]})',
                forceFit: true,
                showGroupName: false,
                enableNoGroups: false,
		enableGroupingMenu: false,
                hideGroupedColumn: true
            }),
            sm: new Ext.grid.RowSelectionModel({
                        singleSelect: true,
                        listeners: {
                                 rowselect: function(sm, row, rec) {
                                    DetAsiento.main.nuevo.setDisabled(true);
                                    DetAsiento.main.guardar.setDisabled(false);
                                    DetAsiento.main.eliminar.setDisabled(false);
                                    DetAsiento.main.limpiar.setDisabled(false);
                                    DetAsiento.main.DisabledCampos({condicion:false});

                                    DetAsiento.main.codCheque.setValue(rec.json.cod_cheque);
                                    DetAsiento.main.codChequeBauchedet.setValue(rec.json.cod_cheque_bauchedet);
                                    
                                    DetAsiento.main.tx_descripcion.setValue(rec.json.descripcion);
                                    DetAsiento.main.cmbCuContable.setValue(rec.json.cuenta_contable);
                                    if(rec.json.debito!=""){
                                        DetAsiento.main.monto.setValue(rec.json.debito);
                                        DetAsiento.main.tipoA.setValue("Debito");
                                    }
                                    if(rec.json.credito!=""){
                                        DetAsiento.main.monto.setValue(rec.json.credito);
                                        DetAsiento.main.tipoA.setValue("Credito");
                                    }
                                    
                              
                                }
                         }
           }),
           stripeRows: true,
           plugins:this.summary,
           autoScroll:true,
           stateful: true
        });



        this.codChequeBauchedet = new Ext.form.Hidden({
            name:'cod_cheque_bauchedet',
            value:''
        });

        this.opt = new Ext.form.Hidden({
            name:'opt',
            value:''
        });

        this.codCheque = new Ext.form.Hidden({
            name:'cod_cheque',
            value:''
        });

        this.tx_descripcion = new Ext.form.TextField({
           fieldLabel:'Descripción',
           name:'descripcion',
           value:''
        });

        this.cmbCuContable = new Ext.form.ComboBox({
            fieldLabel:'Cuenta Contable',
            resizable:true,
            store: this.storeCuContable,
            typeAhead: false,
            valueField: 'cuenta',
            displayField:'descripcion',
            hiddenName:'cuenta_contable',
            forceSelection:true,
            name: 'cmbCuContable',
            triggerAction: 'all',
            selectOnFocus: true,
            minChars:1,
            mode: 'remote',
            width:190,
            resizable:true,
            allowBlank:false
        });
        this.storeCuContable.load();

        this.monto = new Ext.form.NumberField({
           fieldLabel:'Monto',
           name:'monto',
           value:''
        });

        this.in_deleted = new Ext.form.Hidden({
           name:'in_deleted',
           value:0
        });

        this.tipoA = new Ext.form.ComboBox({
            fieldLabel:'Tipo',
            resizable:true,
            store: ["Debito","Credito"],
            typeAhead: true,
            hiddenName:'tipo_a',
            forceSelection:true,
            name: 'tipoa',
            triggerAction: 'all',
            selectOnFocus: true,
            mode: 'local',
            width:190,
            resizable:true,
            allowBlank:false
        });
        this.nuevo = new Ext.Button({
           text:'Nuevo',
           handler:function(){
                DetAsiento.main.codCheque.setValue(opcion.ID);
                DetAsiento.main.DisabledCampos({condicion:false});
                DetAsiento.main.nuevo.setDisabled(true);
                DetAsiento.main.guardar.setDisabled(false);
                DetAsiento.main.eliminar.setDisabled(true);
                DetAsiento.main.limpiar.setDisabled(false);
                DetAsiento.main.formPanelAsiento.getForm().reset();
           }
        });

        this.eliminar = new Ext.Button({
           text:'Eliminar',
           disabled:true,
           handler:function(){
               DetAsiento.main.in_deleted.setValue(1);
               DetAsiento.main.onGuardar();
           }
        });
        
        this.limpiar = new Ext.Button({
           text:'Limpiar',
           disabled:true,
           handler:function(){
                    DetAsiento.main.DisabledCampos({condicion:true});
                    DetAsiento.main.nuevo.setDisabled(false);
                    DetAsiento.main.guardar.setDisabled(true);
                    DetAsiento.main.eliminar.setDisabled(true);
                    DetAsiento.main.limpiar.setDisabled(true);
                    DetAsiento.main.formPanelAsiento.getForm().reset();
           }
        });

        this.guardar = new Ext.Button({
           text:'Guardar',
           disabled:true,
           handler:this.onGuardar
        });
        
        this.formPanelAsiento = new Ext.FormPanel({
            style:'padding-top:5px',
            frame:true,
            items:[
                this.codCheque,
                this.codChequeBauchedet,
                this.opt,
                this.tx_descripcion,
                this.cmbCuContable,
                this.monto,
                this.in_deleted,
                this.tipoA
            ],
            buttonAlign:'center',
            buttons:[
                this.nuevo,this.guardar,this.eliminar,this.limpiar
            ]
        });

        this.panel2 = new Ext.Panel({
            title: '',
            frame:true,
            items:[this.gridDetAsientos,this.formPanelAsiento],
            iconCls: 'icon-cheque',
            bodyStyle:'padding: 2px',
            autoScroll:true,
            autoWidth:true
        });
   
        this.win = new Ext.Window({
            constrain:true,
            modal:true,
            closable:false,
            items:[this.panel2],
            autoScroll:true,
            width:700,
            autoHeight:true,
            buttonAlign:'right',
            buttons:[
                {
                    text:'Cerrar',
                    handler:function(){
                        DetAsiento.main.win.hide();
                    }
                }
            ]
        });
   

    this.store.load({
        params:{
            opt:'tesodetasientos',
            cod_cheque:opcion.ID}
        });

    DetAsiento.main.nuevo.setDisabled(false);
    DetAsiento.main.guardar.setDisabled(true);
    DetAsiento.main.eliminar.setDisabled(true);
    DetAsiento.main.limpiar.setDisabled(true);
    DetAsiento.main.formPanelAsiento.getForm().reset();
    DetAsiento.main.DisabledCampos({condicion:true});
    
    this.win.setTitle('Cliente: '+opcion.benef+" - Monto Cheque: "+opcion.monto);
    this.win.show();
    
    
  },
  getStoreAsientosBy:function(){
        this.store_ = new Ext.data.GroupingStore({
                proxy: new Ext.data.HttpProxy({
                    url:'../../libs/php/ajax/ajax.php',
                    method: 'POST'
                }),
                reader: new Ext.data.JsonReader({
                    root: 'data',
                    totalProperty: 'total'
                },
                [
                    {name:'cod_cheque_bauchedet'},
                    {name:'descripcion'},
                    {name:'cod_cheque'},
                    {name:'cuenta_contable'},
                    {name:'debito'},
                    {name:'credito'}
                ]),
                sortInfo:{
                    field: 'cod_cheque',
                    direction: "ASC"
                },
                groupField:'cod_cheque'
        });
        return this.store_;

  },
  getStoreCuContable:function(){
    this.storex = new Ext.data.JsonStore({
            url:'../../libs/php/ajax/ajax.php',
            baseParams:{
                opt:'store_cuContable'
            },
            root:'data',

            fields:[
                {name:'descripcion'},
                {name:'cuenta'}
            ]
    });
    return this.storex;
  },
  DisabledCampos:function(opcion){
        DetAsiento.main.tx_descripcion.setDisabled(opcion.condicion);
        DetAsiento.main.monto.setDisabled(opcion.condicion);
        DetAsiento.main.cmbCuContable.setDisabled(opcion.condicion);
        DetAsiento.main.tipoA.setDisabled(opcion.condicion);
  },
  onGuardar:function(){
        if(!DetAsiento.main.formPanelAsiento.getForm().isValid()){
           Ext.Msg.alert("Alerta","Debe llenar todos los campos");
           return false;
        }
                    DetAsiento.main.opt.setValue("aCheBaucheDetCRUP");
                    DetAsiento.main.codCheque.setValue(DetAsiento.main.codCheque_);
                    DetAsiento.main.formPanelAsiento.getForm().submit({
                    waitTitle: "Validando..",
                    method:'POST',
                    url: "../../libs/php/ajax/ajax.php",
                    waitMsg : "Espere un momento por favor......",
                    failure: function(sender,action){
                        Ext.MessageBox.alert('Error en transacción', action.result.msg+"\nTransacción:"+action.result.sql);
                    },
                    success: function(sender,action) {
                        if(action.result.success){
                             Ext.MessageBox.show({
                                 title: 'Mensaje',
                                 msg: action.result.msg,
                                 closable: false,
                                 icon: Ext.MessageBox.INFO,
                                 buttons: Ext.MessageBox.OK
                             });

                             DetAsiento.main.DisabledCampos({condicion:true});
                             DetAsiento.main.nuevo.setDisabled(false);
                             DetAsiento.main.guardar.setDisabled(true);
                             DetAsiento.main.eliminar.setDisabled(true);
                             DetAsiento.main.limpiar.setDisabled(true);
                             DetAsiento.main.formPanelAsiento.getForm().reset();

                            DetAsiento.main.store.load({
                                    params:{
                                        opt:'tesodetasientos',
                                        cod_cheque:DetAsiento.main.codCheque_}
                                    });


                        }
                    }
               });


               
  }
};
Ext.onReady(DetAsiento.main.init,DetAsiento.main);


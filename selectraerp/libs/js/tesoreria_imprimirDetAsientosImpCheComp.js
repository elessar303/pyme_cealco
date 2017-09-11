Ext.ns("Impresiones");

Impresiones.main = {
    id:0,
    init:function(){

    },
    detalle_impresion:function(opcion){
        Impresiones.main.id = opcion.ID;
        this.btnComprobanteCheque = new Ext.Button({
           text:'<b>Comprobante de cheque</b>',
           scale: 'large',
           style:'padding:5px;',
           iconAlign: 'top',
           iconCls:'icon-cheque',
           width:'100%',
           handler:function(){
               window.open("../../reportes/rpt_reporte_cheque.php?cod_cheque="+Impresiones.main.id);
           }
        });

        this.btnImpresionCheque = new Ext.Button({
           text:'<b>Impresión de cheque</b>',
           scale: 'large',
           style:'padding:5px;',
           iconAlign: 'top',
           iconCls:'icon-cheque',
           width:'100%',
           handler:function(){
               window.open("../../reportes/rpt_reporte_chequeImpresion.php?cod_cheque="+Impresiones.main.id);
           }
        });

    

 
        this.fielsetComprobantes = new Ext.form.FieldSet({
            title:'Detalle de Comprobantes de Impresion',
            autoWidth: true,
            autoHeight:true,
            collapsible: true,
            collapsed: true
        });

        this.panel_ = new Ext.Panel({
            title: '',
            frame:true,
            items:[
                {
                    layout:'column',
                    
                    items:[
                        {
                            defaults:{style:'padding:5px;'},
                            items:[
                                {
                                    style:'padding:5px;',
                                    columnsWidth:.5,
                                    items:[
                                        this.btnComprobanteCheque,
                                        
                                    ]
                                },
                                {
                                    columnsWidth:.5,
                                    items:[
                                        this.btnImpresionCheque
                                    ]
                                }
                            ]
                        } 
                    ]
                }
            ],
            iconCls: 'icon-cheque',
            autoScroll:true
        });


        if(!this.win){
                this.win = new Ext.Window({
                    constrain:true,
                    title:'Ficha del Movimiento',
                    modal:true,
                    closable:false,
                    items:[this.panel_],
                    autoScroll:true,
                    width:305,
                    autoHeight:true,
                    buttonAlign:'center',
                    buttons:[
                        {
                            text:'Cerrar',
                            handler:function(){
                                Impresiones.main.win.hide();
                            }
                        }
                    ]
                });
        }
        this.win.show();

        
    }
};

Ext.onReady(Impresiones.main.init, Impresiones.main);
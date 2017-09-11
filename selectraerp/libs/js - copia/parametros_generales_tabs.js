Ext.ns('Selectra.pyme.parametros');

Selectra.pyme.parametros.TabPanelParametrosGenerales = {
    init: function(){
        var panelInfoEmpresa = new Ext.Panel({
            contentEl:'div_tab1',
            //itemId:'info',
            title: 'Informaci&oacute;n de la Empresa'
        });
        var panelFinancieros = new Ext.Panel({
            contentEl:'div_tab2',
            //itemId:'fina',
            title:'Financieros'
            /*handler:function(){
            }*/
            /*listeners: {
                activate: this.addTab
            }*/
        });
        var panelClasificadores = new Ext.Panel({
            contentEl:'div_tab3',
            //itemId:'clas',
            title: 'Clasificadores de Inventario'
        });
        var panelImpresoraFiscal = new Ext.Panel({
            contentEl:'div_tab4',
            //itemId:'prin',
            title:'Facturaci&oacute;n'
            /*handler:function(){
                Ext.Ajax.request({
                    method:'GET',
                    url:'../../libs/php/ajax/ajax.php',
                    baseParams:{
                        opt:'tipoFacturacion'
                    },
                    data:'root',
                    fields:[{
                        name: 'tipo_facturacion'
                    }]
                });
            },
            // Obtengo el valor devuelto de BD para el campo 'tipo_facturacion' y habilito o no el panel
            disabled:Ext.get("tipo_facturacion").dom.value!=1?true:false*/
        });
        this.tabs = new Ext.TabPanel({
            renderTo:'contenedorTAB',
            //width:450,
            activeTab:0,
            //frame:true,
            plain:true,
            defaults:{
                autoHeight: true
            },
            items:[
                panelInfoEmpresa, panelFinancieros, panelClasificadores, panelImpresoraFiscal
            /*{
                contentEl:'div_tab1',
                itemId:'info',
                title: 'Informaci&oacute;n de la Empresa'
            },
            {
                contentEl:'div_tab2',
                itemId:'fina',
                title:'Financieros',
                //listeners: {
                  //  activate: handleActivate
                //},
                handler:function(){
                    //var tipo = document.getElementById('tipo_facturacion');
                    //if(tipo.options[tipo.selectedIndex].value === "1"){
                      //  alert('nada')
                    //}
                    //handleActivate();
                    this.addTab();
                }
            },
            {
                contentEl:'div_tab3',
                itemId:'clas',
                title: 'Clasificadores de Inventario'
            },
            {
                contentEl:'div_tab4',
                itemId:'prin',
                title:'Impresora Fiscal',
                disabled:true
            }*/
            ]
        });
    }
}
Ext.onReady(Selectra.pyme.parametros.TabPanelParametrosGenerales.init, Selectra.pyme.parametros.TabPanelParametrosGenerales);
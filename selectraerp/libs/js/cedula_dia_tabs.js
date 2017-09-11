Ext.ns('Selectra.pyme.cedula_dia');

Selectra.pyme.cedula_dia.TabPanelCedulaDia = {
    init: function(){
        var panelDatos = new Ext.Panel({
            contentEl:'div_tab1',
            title: 'Restricción'
        });
       
        this.tabs = new Ext.TabPanel({
            renderTo:'contenedorTAB',
            activeTab:0,
            plain:true,
            defaults:{
                autoHeight: true
            },
            items:[
                panelDatos
            ]
        });
    }
}
Ext.onReady(Selectra.pyme.cedula_dia.TabPanelCedulaDia.init, Selectra.pyme.cedula_dia.TabPanelCedulaDia);
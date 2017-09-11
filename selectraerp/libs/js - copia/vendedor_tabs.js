Ext.ns('Selectra.pyme.vendedores');

Selectra.pyme.vendedores.TabPanelVendedores = {
    init: function(){
        var panelDatos = new Ext.Panel({
            contentEl:'div_tab1',
            title: 'Datos Generales'
        });
        var panelPerfil = new Ext.Panel({
            contentEl:'div_tab2',
            title:'C&aacute;lculo Simple de Comisiones'
        });
        var panelComisionCobro = new Ext.Panel({
            contentEl:'div_tab3',
            title:'Comisi&oacute;n por tabla de cobranza'
        });
        var panelComisionVenta = new Ext.Panel({
            contentEl:'div_tab4',
            title:'Comisi&oacute;n por tabla de Ventas'
        });
        this.tabs = new Ext.TabPanel({
            renderTo:'contenedorTAB',
            activeTab:0,
            plain:true,
            defaults:{
                autoHeight: true
            },
            items:[
                panelDatos, panelPerfil, panelComisionCobro, panelComisionVenta
            ]
        });
    }
}
Ext.onReady(Selectra.pyme.vendedores.TabPanelVendedores.init, Selectra.pyme.vendedores.TabPanelVendedores);
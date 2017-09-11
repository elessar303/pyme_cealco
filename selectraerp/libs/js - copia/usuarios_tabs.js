Ext.ns('Selectra.pyme.usuarios');

Selectra.pyme.usuarios.TabPanelUsuarios = {
    init: function(){
        var panelDatos = new Ext.Panel({
            contentEl:'div_tab1',
            title: 'Datos del usuario'
        });
        var panelPerfil = new Ext.Panel({
            contentEl:'div_tab2',
            title:'Perfil del usuario'
        });
        this.tabs = new Ext.TabPanel({
            renderTo:'contenedorTAB',
            activeTab:0,
            plain:true,
            defaults:{
                autoHeight: true
            },
            items:[
                panelDatos, panelPerfil
            ]
        });
    }
}
Ext.onReady(Selectra.pyme.usuarios.TabPanelUsuarios.init, Selectra.pyme.usuarios.TabPanelUsuarios);
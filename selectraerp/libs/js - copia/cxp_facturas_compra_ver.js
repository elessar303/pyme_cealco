

Ext.onReady(function(){

	 var formpanel = new Ext.Panel({
            title:'Datos Generales',
            autoHeight: 300,
            width: '100%',

            collapsible: false,
            titleCollapse: true ,
            contentEl:'datosGral',
            frame:true
        });

	 var formpanel2 = new Ext.Panel({
            title:'Cálculo de Retención de IVA',
            autoHeight: 300,
            width: '100%',

            collapsible: false,
            titleCollapse: true ,
            contentEl:'datosIva',
            frame:true
        });

	formpanel3= new Ext.Panel({
		title:'Cálculo de Retencion de ISLR',
		autoHeight: 300,
		width: '100%',
		collapsible: true,
		titleCollapse: true ,
		contentEl:'datosIslr',
		frame:true
	});
        formpanel.render("formulario");
	formpanel2.render("formulario");
        formpanel3.render("formulario");


});
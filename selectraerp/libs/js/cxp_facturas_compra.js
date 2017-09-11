
function GuardarFactura()
{

    respon1 = $("#num_fac").val();
    respon2 = $("#num_cont").val();
    if((respon1=="")||(respon2==""))
    {
        Ext.Msg.alert("Alerta!","Debe cargar el numero de factura y numero de control");
        return false;
    }
    $("#formulario").submit();
}

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
        title:'Cálculo de Retención de ISLR',
        autoHeight: 300,
        width: '100%',
        collapsible: true,
        titleCollapse: true ,
        contentEl:'datosIslr',
        frame:true,
        buttons: [
        {
            text:'Guardar Fac./Nc.',
            handler: function(){
                GuardarFactura();
            }
        }]
    });
    formpanel.render("formulario");
    formpanel2.render("formulario");
    formpanel3.render("formulario");


});
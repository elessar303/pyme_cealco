function agregarFactura(){
    winAgregarFactura = new Ext.Window(
    {
        title:'Agregar Factura',
        height:350,
        width:400,
        autoScroll:true,
        modal:true,
        bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
        closeAction:'hide',
        contentEl:'agregarfactura',
        buttons:[
        {
            text:'Agregar',
            icon: '../../libs/imagenes/save.gif',
            handler:function()
            {
                guardar();
            }
        },
        {
            text:'Cancelar',
            icon: '../../libs/imagenes/cancel.gif',
            handler:function()
            {
                winAgregarFactura.hide();
            }
        }
        ]
    });

    winAgregarFactura.show();

    function guardar(){
        var responsable=$("#responsable_agregar").val();
        var num_factura=$("#num_factura_agregar").val();
        var num_control=$("#num_control_factura_agregar").val();
        var subtotal_factura=$("#subtotal_factura").val();
        var exento_factura=$("#exento_factura").val();
        var base_factura=$("#base_factura").val();
        var iva_factura=$("#iva_factura").val();
        var fecha_emision=$("#fecha_emision_fact").val();
        var fecha_vence=$("#fecha_vence_fact").val();
        var id_proveedor=$("#id_proveedor_factura").val();
        var usuario=$("#usuario_creacion_factura").val();
        var cod_impuesto=$("#cod_impuesto").val();
        var alicuota_impuesto=$("#alicuota").val();
        var retencion_iva=$("input[name='retencion_iva']:checked").val();
        var libro_compras=$("input[name='libro_compras']:checked").val();

        if(responsable=="" || num_factura=="" || num_control=="" || subtotal_factura=="" || exento_factura=="" || base_factura=="" || iva_factura=="" || fecha_emision=="" || fecha_vence==""){
            //alert("Debe llenar todos los campos.");
            Ext.Msg.alert("Alerta","Debe llenar todos los campos.");
            return false;
        }
        $.ajax({
            type: 'GET',
            data: 'opt=agregar_factura&responsable='+responsable+'&num_factura='+num_factura+'&num_control='+num_control+'&exento_factura='+exento_factura+'&subtotal_factura='+subtotal_factura+'&base_factura='+base_factura+'&iva_factura='+iva_factura+'&fecha_emision='+fecha_emision+'&fecha_vence='+fecha_vence+'&id_proveedor='+id_proveedor+'&retencion_iva='+retencion_iva+'&libro_compras='+libro_compras+'&cod_impuesto='+cod_impuesto+'&alicuota='+alicuota_impuesto+'&usuario='+usuario,
            url:  '../../libs/php/ajax/ajax.php',
            beforeSend: function(){
                document.getElementById("loading").style.visibility = 'visible';
            },
            success: function(){
                Ext.Msg.alert("Alerta","Factura guardada con &Eacute;xito.");
                document.getElementById("loading").style.visibility = 'hidden';
                winAgregarFactura.hide();
                location.reload()
            }
        });
    }
}
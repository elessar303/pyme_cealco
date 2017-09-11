function anularFactura(valor) {
    //alert("Factura compra: "+valor);
    cod = valor;

    winAnOrden = new Ext.Window({
        title: 'Anular Orden de Compra',
        height: 195,
        width: 320,
        autoScroll: true,
        modal: true,
        bodyStyle: 'padding-right:10px;padding-left:10px;padding-top:5px;',
        closeAction: 'hide',
        contentEl: 'anularordencompra',
        buttons: [
            {
                text: 'Anular Factura',
                icon: '../../libs/imagenes/save.gif',
                handler: function(){
                    a(cod);
                }
            },
            {
                text: 'Cerrar',
                icon: '../../libs/imagenes/cancel.gif',
                handler: function(){
                    winAnOrden.hide();
                }
            }
        ]
    });

    winAnOrden.show();

    function a(cod) {
        //alert(cod);return false;

        //cod = valor;
        if ($("#motivoAnulacionOrden").val() === "" || $("#fechaOrden").val() === "") {
            Ext.Msg.alert("Alerta", "Debe llenar todos los campos.");
            return false;
        }

        //det_transaccion = $(this).parents("td").find("input[name='detalle_asiento']").val();
        var motivoAnulacionOrden = $("#motivoAnulacionOrden").val();
        var fechaOrden = $("#fechaOrden").val();

        $.ajax({
            type: 'GET',
            data: 'opt=eliminar_ordenCXP&cod=' + cod + '&motivoAnulacionOrden=' + motivoAnulacionOrden + '&fechaOrden=' + fechaOrden,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                document.getElementById("loading").style.visibility = 'visible';
            },
            success: function(data) {
                //resultado = data
                //alert(resultado);
                Ext.Msg.alert("Alerta", "CxP anulada con éxito.");
                document.getElementById("loading").style.visibility = 'hidden';
                winAnOrden.hide();
                location.reload();
            }
        });
    }
}
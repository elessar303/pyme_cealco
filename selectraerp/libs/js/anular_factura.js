function guardarr(valor)
{
    // alert(valor)
    cod = valor;

    winAnFac = new Ext.Window({
        title: 'Anular Factura',
        height: 195,
        width: 320,
        autoScroll: true,
        modal: true,
        bodyStyle: 'padding-right:10px;padding-left:10px;padding-top:5px;',
        closeAction: 'hide',
        contentEl: 'anularfactura',
        buttons: [
            {
                text: 'Anular Factura',
                icon: '../../libs/imagenes/save.gif',
                handler: function()
                {
                    guardarr2(cod);
                }
            },
            {
                text: 'Cerrar',
                icon: '../../libs/imagenes/cancel.gif',
                handler: function()
                {
                    winAnFac.hide();
                }
            }
        ]
    });

    winAnFac.show();

    function guardarr2(valor) {
        //alert(valor)
        //return false;
        det_transaccion = valor;
        if ($("#motivoAnulacion").val() === "" || $("#fecha").val() === "") {
            Ext.Msg.alert("Alerta", "Debe llenar todos los campos.");
            return false;
        }
        //det_transaccion = $(this).parents("td").find("input[name='detalle_asiento']").val();
        var motivoAnulacion = $("#motivoAnulacion").val();
        var fecha = $("#fecha").val();

        $.ajax({
            type: 'GET',
            data: 'opt=eliminar_asientoCXP&cod=' + det_transaccion + '&motivoAnulacion=' + motivoAnulacion + '&fecha=' + fecha,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                document.getElementById("loading").style.visibility = 'visible';
            },
            success: function(data) {
                //resultado = data
                //alert(resultado);
                Ext.Msg.alert("Alerta", "Factura anulada con éxito.");
                document.getElementById("loading").style.visibility = 'hidden';
                winAnFac.hide();
                location.reload();
            }
        });
    }
}
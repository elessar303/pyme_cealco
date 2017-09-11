$(document).ready(function() {
    $('tr.edocuenta').mouseover(function() {
        if ($(this).attr("bgcolor") != "#b6ceff") {
            $(this).attr("bgcolor", "#fbf6f6");
        }
    }).mouseout(function() {
        if ($(this).attr("bgcolor") != "#b6ceff") {
            $(this).attr("bgcolor", "#ececec");
        }
    });
    $(".eliminarAsiento").live("click", function(e) {
        if (confirm("¿Est&aacute; seguro(a) que desea eliminar este asiento?")) {
            det_transaccion = $(this).parents("td").find("input[name='detalle_asiento']").val();
            $.ajax({
                type: 'GET',
                data: 'opt=eliminar_asientoCXC&cod=' + det_transaccion,
                url: '../../libs/php/ajax/ajax.php',
                beforeSend: function() {
                },
                success: function(data) {
                    //objeto.after(data);
                    if (data == 1) {
                    }
                }
            });
            window.location.href = $("input[name='url_delete_asientos']").val();
        }
    });
    //$('tr.detalle').click(function()
    $('tr.detalle').click(function() {
        objeto = $(this);
        //Deseleccionamos cualquier fila cambiandole el color del tr
        objeto.parents("tbody").find(".detalle").attr("bgcolor", "#ececec");
        //Seleccionamos la fila a la cual se dio click para conocer detalles
        $(this).attr("bgcolor", "#b6ceff");
        //Removemos cualquier detalle que este cargado en la tabla de estado de cuenta
        objeto.parents("tbody").find("tr.detalle_items").remove();
        
        
        //objeto.find(".boton_detalle").css("display","none");
        //$("#detalle").toggle("slow");
        
        //$("#respuesta").hide("slow");

        /*PRUEBAS PARA OCULTAR EL <TR>*/
        
        /*FIN DE LAS PRUEBAS*/

        objeto.find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add2.gif");
        //$("tr.detalle_items").toggle("slow");

        /*objeto.find("input[name='desplegado']").attr("value","true");
        if(objeto.find("input[name='desplegado']").val()==="true")
            objeto.parents("tbody").find(".detalle_items").remove();*/
        //Cargamos el codigo del cliente y el codigo del estado de cuenta de X factura.
        id_estudio = objeto.find("input[name='id_estudio']").val();
        estatus_cotizacion = objeto.find("input[name='estatus_cotizacion']").val();
        id_menu = objeto.find("input[name='id_menu']").val();
        id_seccion = objeto.find("input[name='id_seccion']").val();
        //id_tipo_movimiento_almacen = objeto.find("input[name='id_tipo_movimiento_almacen']").val();
        //Cargamos los debitos y creditos
        $.ajax({
            type: 'GET',
            data: 'opt=det_cotizacion_agregar&id_estudio=' + id_estudio +
            '&estatus_cotizacion='+estatus_cotizacion+'&id_menu=' + id_menu +
            '&id_seccion=' + id_seccion,
            //data: 'opt=det_cotizacion&id_estudio=' + id_estudio,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
            },
            success: function(data) {
                objeto.after(data);
            }
        });
        
    });

});
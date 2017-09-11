$(document).ready(function() {

    //fechas
    $("#fecha").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "dd-mm-yy",
                    timeFormat: 'HH:mm:ss',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        //$( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                        $( "#fecha2" ).datetimepicker("option", "minDate", selectedDate);
                    }
                });
                $("#fecha2").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "dd-mm-yy",
                    timeFormat: 'HH:mm:ss',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datetimepicker( "option", "maxDate", selectedDate );
                    }
                });

                //funcion para cargar los puntos 
                $("#estados").change(function() 
                {
                    estados = $("#estados").val();
                        $.ajax({
                            type: 'GET',
                            data: 'opt=getPuntos&'+'estados='+estados,
                            url: '../../libs/php/ajax/ajax.php',
                            beforeSend: function() {
                                $("#punto").find("option").remove();
                                $("#punto").append("<option value=''>Cargando..</option>");
                            },
                            success: function(data) {
                                $("#punto").find("option").remove();
                                this.vcampos = eval(data);
                                     $("#punto").append("<option value='0'>Todos</option>");
                                for (i = 0; i <= this.vcampos.length; i++) {
                                    $("#punto").append("<option value='" + this.vcampos[i].nombre_punto+ "'>" + this.vcampos[i].nombre_punto + "</option>");
                                }
                            }
                        }); 
                        $("#punto").val(0);
                  });
                //

    /*
    $('tr.detalle').click(function() {
        objeto = $(this);
        //Deseleccionamos cualquier fila cambiandole el color del tr
        objeto.parents("tbody").find(".detalle").attr("bgcolor", "#ececec");
        //Seleccionamos la fila a la cual se dio click para conocer detalles
        $(this).attr("bgcolor", "#b6ceff");
        //Removemos cualquier detalle que este cargado en la tabla de estado de cuenta
        objeto.parents("tbody").find(".detalle_items").remove();
        //Le colocamos la imagen que indica que puede hacer click para desplegar informacion
        objeto.parents("tbody").find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add.gif");
        //Le coloca la imagenes a la fila tr que disparo el evento click.
        objeto.find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add2.gif");
        objeto.find("input[name='desplegado']").attr("value","true");
        if(objeto.find("input[name='desplegado']").val()==="true")
            objeto.parents("tbody").find(".detalle_items").remove();
        //Cargamos el codigo del cliente y el codigo del estado de cuenta de X factura.
        id_transaccion = objeto.find("input[name='id_transaccion']").val();
        id_tipo_movimiento_almacen = objeto.find("input[name='id_tipo_movimiento_almacen']").val();
        //Cargamos los debitos y creditos
        $.ajax({
            type: 'GET',
            data: 'opt=det_items_recepcion&id_transaccion=' + id_transaccion + '&id_tipo_movimiento_almacen=' + id_tipo_movimiento_almacen,
            url: '../../libs/php/ajax/ajax2.php',
            beforeSend: function() {
            },
            success: function(data) {
                objeto.after(data);
            }
        });
    });
    */
            $('tr.detalle').click(function() {
        objeto = $(this);
        //si se le da click al mismo objeto se debe quitar el detalle
        if(objeto.next().hasClass('detalle_items'))
        {
            objeto.parents("tbody").find(".detalle_items").remove();
             objeto.parents("tbody").find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add.gif");
            return;
        }
        //Deseleccionamos cualquier fila cambiandole el color del tr
        objeto.parents("tbody").find(".detalle").attr("bgcolor", "#ececec");
        //Seleccionamos la fila a la cual se dio click para conocer detalles
        $(this).attr("bgcolor", "#b6ceff");
         //Removemos cualquier detalle que este cargado en la tabla de estado de cuenta
        objeto.parents("tbody").find(".detalle_items").remove();
        //Le colocamos la imagen que indica que puede hacer click para desplegar informacion
        objeto.parents("tbody").find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add.gif");
        //Le coloca la imagenes a la fila tr que disparo el evento click.
        objeto.find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add2.gif");
        /*objeto.find("input[name='desplegado']").attr("value","true");
        if(objeto.find("input[name='desplegado']").val()==="true")
            objeto.parents("tbody").find(".detalle_items").remove();*/
        //Cargamos el codigo del cliente y el codigo del estado de cuenta de X factura.
        id_transaccion = objeto.find("input[name='id_transaccion']").val();
        id_tipo_movimiento_almacen = objeto.find("input[name='id_tipo_movimiento_almacen']").val();
        //Cargamos los debitos y creditos
        $.ajax({
            type: 'GET',
            data: 'opt=det_items_recepcion&id_transaccion=' + id_transaccion + '&id_tipo_movimiento_almacen=' + id_tipo_movimiento_almacen,
            url: '../../libs/php/ajax/ajax2.php',
            beforeSend: function() {
            },
            success: function(data) {
                objeto.after(data);
            }
        });
    });
});
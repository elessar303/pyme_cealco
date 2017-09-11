//eventos_paneles_documento_mercantil.js
$(document).ready(function(){
    $("#detalle_factura_").show();
    sw=1;
    $.setValoresInput = function(nombreObjetoDestino,nombreObjetoActual){
        $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
    }
    $("#lick_detalle").click(function(){
        if(sw==0){
            $("#detalle_factura_").show(150);
            $(this).html("<div><img style=\"vertical-align: middle\" src=\"../../../includes/imagenes/drop-add2.gif\"> Ocultar detalles</div>");
            sw=1;
        }else{
            if(sw==1){
                $("#detalle_factura_").hide(150);
                $(this).html("<div><img style=\"vertical-align: middle\" src=\"../../../includes/imagenes/drop-add.gif\"> Ver detalles</div>");
                sw=0;
            }
        }
    });

    $("#contenedorTAB_factura_paso2").hide();
    $("#tab1_pasos").click(function(){
        $("#contenedorTAB_factura_paso1").show();
        $("#contenedorTAB_factura_paso2").hide();
        $("#tab1_pasos").removeClass("click_paso_OFF").addClass("click_paso_ON").find("img").attr("src", "../../../includes/imagenes/113.png");
        $("#tab2_pasos").removeClass("click_paso_OFF").addClass("click_paso_OFF").find("img").attr("src", "../../../includes/imagenes/6_off.png");
    });
    $("#tab2_pasos").click(function(){
        cant_filas = $(".grid table.lista tbody").find("tr").length;
        if(cant_filas==0){
            $.facebox("<span style='color: red;'>Debe agregar un Item para esta operación</span>");
            return false;
        }
        $(".ctotalizar_").each(function(){
            if($(this).val()==""){
                $(this).val("");
            }
        });
        $.totalizarFactura();

        $.setValoresInput("#input_totalizar_sub_total","#totalizar_sub_total");
        $.setValoresInput("#input_totalizar_descuento_parcial","#totalizar_descuento_parcial");
        $.setValoresInput("#input_totalizar_total_operacion","#totalizar_total_operacion");

        $.setValoresInput("#input_totalizar_pdescuento_global","#totalizar_pdescuento_global");
        $.setValoresInput("#input_totalizar_descuento_global","#totalizar_descuento_global");
        $.setValoresInput("#input_totalizar_monto_iva","#totalizar_monto_iva");
        $.setValoresInput("#input_totalizar_total_retencion","#totalizar_total_retencion");
        $.setValoresInput("#input_totalizar_total_general","#totalizar_total_general");

        //#FORMA PAGO
        $.setValoresInput("#input_totalizar_monto_cancelar","#totalizar_monto_cancelar");
        $.setValoresInput("#input_totalizar_saldo_pendiente","#totalizar_saldo_pendiente");
        $.setValoresInput("#input_totalizar_cambio","#totalizar_cambio");

        //#INSTRUMENTO DE PAGO
        $.setValoresInput("#input_totalizar_monto_efectivo","#totalizar_monto_efectivo");
        $.setValoresInput("#input_totalizar_monto_cheque","#totalizar_monto_cheque");
        $.setValoresInput("#input_totalizar_nro_cheque","#totalizar_nro_cheque");
        $.setValoresInput("#input_totalizar_nombre_banco","#totalizar_nombre_banco");
        $.setValoresInput("#input_totalizar_monto_tarjeta","#totalizar_monto_tarjeta");
        $.setValoresInput("#input_totalizar_nro_tarjeta","#totalizar_nro_tarjeta");
        $.setValoresInput("#input_totalizar_tipo_tarjeta","#totalizar_tipo_tarjeta");
        $.setValoresInput("#input_totalizar_monto_deposito","#totalizar_monto_deposito");
        $.setValoresInput("#input_totalizar_nro_deposito","#totalizar_nro_deposito");
        $.setValoresInput("#input_totalizar_banco_deposito","#totalizar_banco_deposito");

        $("#contenedorTAB_factura_paso2").show();
        $("#contenedorTAB_factura_paso1").hide();

        $("#tab2_pasos").removeClass("click_paso_OFF").addClass("click_paso_ON").find("img").attr("src", "../../../includes/imagenes/6.png");
        $("#tab1_pasos").removeClass("click_paso_OFF").addClass("click_paso_OFF").find("img").attr("src", "../../../includes/imagenes/113_OFF.png");

    });
});
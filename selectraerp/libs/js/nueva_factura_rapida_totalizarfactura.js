$(function(){
    montoOLD = 0;
    listo = false;
    $("#info_pago_pendiente").hide();

    //alert($("input[name='cantidad_impuesto']").val())

    for($i=1;$i<=$("input[name='cantidad_impuesto']").val();$i++){
        contador ="i"+$i;
        //var num=document.getElementById(contador)
        //var tipo_impuesto=document.getElementById('tipo_impuesto')
        //alert($("input[name='"+cod_tipo_impuesto+"']").val())

        $("#cod_impuesto"+$("input[name='"+contador+"']").val()).change(function(){
            if($(this).val()!=""){
                //alert(tipo_impuesto.value)
                //alert($("#cod_impuesto"+$("input[name='tipo_impuesto']").val()).val())

                if($("input[name='tipo_impuesto']").val()==1){
                    base = $("input[name='totalizar_monto_iva']").val();
                }
                else{
                    base = $("input[name='totalizar_base_imponible']").val();
                }

                $.ajax({
                    type: 'GET',
                    data: 'opt=impuestos&cod_impuesto='+$(this).val()+"&monto_base="+parseFloat(base),
                    url:  '../../libs/php/ajax/ajax.php',
                    beforeSend: function(){
                    },
                    success: function(data){
                        campos = eval(data);
                        input1 ="totalizar_monto_retencion"+campos[0].cod_tipo_impuesto;
                        input2 ="totalizar_pbase_retencion"+campos[0].cod_tipo_impuesto;
                        inputformula="formula"+campos[0].cod_tipo_impuesto;
                        inputimpuesto="codigo_impuesto"+campos[0].cod_tipo_impuesto
                        inputresultado="resultado"+campos[0].cod_tipo_impuesto
                        $("input[name='"+input1+"']").val(campos[0].total_retencion);
                        $("input[name='"+input2+"']").val(campos[0].porcentaje);
                        $("input[name='"+inputformula+"']").val(campos[0].formula);
                        $("input[name='"+inputimpuesto+"']").val(campos[0].codigo_impuesto);
                        $("input[name='"+inputresultado+"']").val(campos[0].cod_tipo_impuesto);
                        totalretencion =0;
                        for($i=1;$i<=$("input[name='cantidad_impuesto']").val();$i++){
                            inputmontoretencion ="totalizar_monto_retencion"+$i;
                            //inputpbaseretencion ="totalizar_pbase_retencion"+$i;
                            //alert($("input[name='"+inputmontoretencion+"']").val())
                            totalretencion = parseFloat(totalretencion) + (parseFloat($("input[name='"+inputmontoretencion+"']").val()));
                        }
                        //alert($("input[name='totalizar_total_retencion']").val(totalretencion.toFixed(2)))
                        $("input[name='totalizar_total_retencion']").val(totalretencion.toFixed(2));
                        //$("input[name='totalizar_total_general']").val();
                        $("input[name='totalizar_total_factura']").val(($("input[name='totalizar_total_general']").val()-parseFloat(totalretencion)).toFixed(2));
                        $("input[name='totalizar_monto_cancelar']").val(parseFloat(($("input[name='totalizar_total_general']").val()-parseFloat(totalretencion)).toFixed(2)));
                        //alert($("input[name='totalizar_total_factura']").val());
                        $("input[name='totalizar_monto_efectivo']").val(($("input[name='totalizar_total_general']").val()-parseFloat(totalretencion)).toFixed(2));
                    }
                });
            }
        });
    } // FIN DEL FOR DE CALCULO DE CADA SELECCION DE IMPUESTO

    $("#totalizar_base_retencion").blur(function(){
        valor = $(this).val();
        valor2 = $("input[name='totalizar_total_operacion']").val();
        if(parseFloat(valor)>parseFloat(valor2)){
            $.facebox("El monto no puede ser mayor al de sub total.");
            $("input[name='totalizar_base_retencion']").val($("input[name='totalizar_total_operacion']").val());
            return false;
        }
    });

    $("#cod_impuesto_iva").change(function(){
        if($(this).val()!=""){
            $.ajax({
                type: 'GET',
                data: 'opt=impuesto_iva&cod_impuesto_iva='+$(this).val()+"&montoiva="+$("input[name='totalizar_monto_iva']").val(),
                url:  '../../libs/php/ajax/ajax.php',
                beforeSend: function(){

                },
                success: function(data){
                    campos = eval(data);
                    iva = campos[0].total_iva;
                    $("input[name='totalizar_monto_iva2']").val(iva);
                }
            });
        }
    });

    $.totalizarFactura = function(){
        if(listo==false){
            datoscliente = eval($("input[name='DatosCliente']").val());
            tmp_subtotal=0;
            tmp_descuento=0;
            tmp_iva=0;
            tmp_totalizar_total_general =0;
            totalretencion =0;
            //alert(cod_tipo_impuesto)

            $(".ctotalizar_").each(function(){
                $(this).attr("value", "0");
            });

            $("input.input-gastos").val("0.00");

            $("#cod_impuesto_iva").val("");

            // $(".grid table.lista tbody").find("tr").each(function(){
            //     tmp_subtotal += parseFloat(var_subTotal) +  parseFloat($(this).find("td.cantidad").attr("rel"))*parseFloat($(this).find("td.preciosiniva").text());
            //     tmp_descuento  += parseFloat($(this).find("td.montodescuento").html());
            //     tmp_iva += parseFloat($(this).find("td.piva").attr("rel"));
            //     tmp_totalizar_total_general += parseFloat($(this).find("td").eq(7).html());
            // });

            var var_montoItemsFactura = 0;
            var var_ivaTotalFactura= 0;
            var var_descuentosItemFactura =0;
            var var_TotalTotalFactura = 0;
            var var_total_costo_actual = 0;
            var var_total_porcentaje_costo_ganancia = 0;
            var var_subTotal = 0;
            var var_cantidad_por_bulto = 0;
            var var_ganancia_total_item = 0;
            var var_porcentaje_ganancia_total_items = 0;
            $(".grid table.lista tbody").find("tr").each(function(){
                tmp_subtotal = parseFloat(tmp_subtotal) +  parseFloat($(this).find("td.cantidad").attr("rel"))*parseFloat($(this).find("td.preciosiniva").text());
                var_montoItemsFactura = parseFloat(var_montoItemsFactura) + parseFloat($(this).find("td.totalsiniva").text());
                tmp_iva =  parseFloat(tmp_iva) + parseFloat($(this).find("td.piva").attr("rel"));
                
                tmp_descuento =  parseFloat(tmp_descuento) + parseFloat($(this).find("td.montodescuento").html());
                
                var_TotalTotalFactura = parseFloat(var_montoItemsFactura) + parseFloat(var_ivaTotalFactura);
                cantidad_por_bulto = parseInt($(this).find("td").find("input[name='_cantidad_bulto[]']").val());
                cantidad_items = parseInt($(this).find("td.cantidad").attr("rel"));
                tcos_actual = parseFloat($(this).find("td").find("input[name='_id_costo_actual[]']").val());
                var_ganancia_total_item += parseFloat($(this).find("td").find("input[name='_ganancia_item_individual[]']").val());

                if(_.isNaN(tcos_actual)){
                    tcos_actual = 0;
                }

                var_cantidad_por_bulto += cantidad_por_bulto;
                var_total_costo_actual += parseFloat(tcos_actual);

                var_porcentaje_ganancia_total_items = ((var_ganancia_total_item)*100)/var_montoItemsFactura;
            });

            calculo = tmp_subtotal - tmp_descuento;
            
            
            // Calcula el total de retenciones para restarselo al Total de Factura
            $("input[name='totalizar_pdescuento_global'],input[name='totalizar_descuento_global']").val(0);
            $("input[name='totalizar_sub_total']").val(parseFloat(tmp_subtotal).toFixed(2));
            $("input[name='totalizar_descuento_parcial']").val(parseFloat(tmp_descuento).toFixed(2));
            $("input[name='totalizar_total_operacion']").val((parseFloat(tmp_subtotal)-parseFloat(tmp_descuento)).toFixed(2));
            restar = parseFloat(tmp_subtotal)-parseFloat(tmp_descuento);
            $("input[name='totalizar_base_imponible'], input[name='total_fob'], input[name='total_fob_gatos']").val(tmp_subtotal.toFixed(2));
            $("input[name='totalizar_monto_iva'], input[name='tmpiva_'], input[name='totalizar_monto_iva2']").val(tmp_iva.toFixed(2));
            $("#totalizar_total_general").val(parseFloat((parseFloat(tmp_subtotal)+parseFloat(tmp_iva)).toFixed(2)));
            //$("input[name='totalizar_monto_cancelar']").val($("input[name='totalizar_total_general']").val());
            $("input[name='totalizar_monto_efectivo']").val($("input[name='totalizar_total_general']").val());

            return false;
        }
    }//Fin de $.totalizarFactura = function()

    $("input.input-gastos").blur(function(){
       
        var totalGasto = total_fob = 0;
        var totalSinGastos = parseFloat($("input[name='total_fob']").val());
        $("input.input-gastos").each(function(){  
            var valor = parseFloat($(this).val());
            if(_.isNaN(valor)){
                valor = 0;
            }
            totalGasto += parseFloat(valor);
        });
			
			
        total_fob = parseFloat(totalSinGastos) + parseFloat(totalGasto);
        
			var iva=parseFloat($("#totalizar_monto_iva").val());
			var totaltotal= parseFloat(total_fob) + parseFloat(iva);
        
        $("#totalizar_sub_total").val(total_fob.toFixed(2));
        $("#totalizar_total_operacion").val(total_fob.toFixed(2));
        $("#totalizar_total_general").val(parseFloat(totaltotal.toFixed(2)));
        $("input[name='total_fob_gatos']").val(total_fob.toFixed(2));

    });

    //$(".tabpanel2").hide();
    $(".tabpanel3").hide();
    $(".tabpanel4").hide();
    $(".tabpanel5").hide();
    $(".tabpanel6").hide();
    
    $("#tab1").addClass("click");

    centinela = false;

    $(".tab").live("mouseover", function(){
        $(this).addClass("sobreboton");
    }).live("mouseout", function(){
        $(this).removeClass("sobreboton");
    }).live("click",function(){
        $(".tab").removeClass("click");
        $(this).addClass("click");
        tabclick = $(this).attr("id");

        if(tabclick=="tab1"){
            $(".tabpanel1").show();
           // $(".tabpanel2").hide();
            $(".tabpanel3").hide();
            $(".tabpanel4").hide();
            $(".tabpanel5").hide();
            $(".tabpanel6").hide();
        }

        /*if(tabclick=="tab2"){
            $(".tabpanel1").hide();
            $(".tabpanel2").show();
            $(".tabpanel3").hide();
            $(".tabpanel4").hide();
            $(".tabpanel5").hide();
            $(".tabpanel6").hide();
            //$("#totalizar_monto_cancelar").trigger("click")
        }*/

        if(tabclick=="tab3"){
            $(".tabpanel1").hide();
           // $(".tabpanel2").hide();
            $(".tabpanel3").show();
            $(".tabpanel4").hide();
            $(".tabpanel5").hide();
            $(".tabpanel6").hide();
            //$("#totalizar_monto_cancelar").trigger("click")
        }

        if(tabclick=="tab4"){
            $(".tabpanel1").hide();
            //$(".tabpanel2").hide();
            $(".tabpanel3").hide();
            $(".tabpanel4").show();
            $(".tabpanel5").hide();
            $(".tabpanel6").hide();
            $("#totalizar_monto_cancelar").trigger("click");
        }

        if(tabclick=="tab5"){
            $(".tabpanel1").hide();
            //$(".tabpanel2").hide();
            $(".tabpanel3").hide();
            $(".tabpanel4").hide();
            $(".tabpanel5").show();
            $(".tabpanel6").hide();
        }

        if(tabclick=="tab6"){
            $(".tabpanel1").hide();
           // $(".tabpanel2").hide();
            $(".tabpanel3").hide();
            $(".tabpanel4").hide();
            $(".tabpanel5").hide();
            $(".tabpanel6").show();
        }

        $("input[name='totalizar_monto_efectivo'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque'], input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta'],input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito'],input[name='totalizar_nro_otrodocumento'],input[name='totalizar_monto_otrodocumento']").numeric();

        if (centinela==false){
            centinela=true;
            $("#opt_cheque, #opt_tarjeta, #opt_deposito, #opt_otrodocumento").val(0).trigger("change");
            $("#totalizar_descripcion_base_retencion").trigger("change");
            $("#cod_impuesto_iva").trigger("change");
        }
    });

    $("input[name='totalizar_monto_efectivo'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque'], input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta'],input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").live("change",function(){
        valor = $(this).val();
        if(valor==""){           
            $(this).val("0");
            return false;
        }
        valor = parseFloat($(this).val());
        if(valor=="NaN"){
            $(this).val("0");
            return false;
        }else{
            $(this).val(valor);
        }
    });

    $("#totalizar_pdescuento_global").click(function(){
        $.totalizarFactura();
        $(this).numeric();
        if($(this).val()=="") {
            $(this).val("0");
        }
        $(this).val($(this).val()).select();
    }).change(function(){
        if($(this).val()==""||$(this).val()>100||$(this).val()=="."){
            $(this).val("0");
            $("input[name='totalizar_descuento_global']").val("0").toFixed(2);
            $("input[name='totalizar_base_imponible'], input[name='totalizar_neto']").val($("input[name='totalizar_total_operacion']").val());
            return false;
        }
        if($(this).val()==0){
            $("input[name='totalizar_descuento_global']").val(0).toFixed(2);
            $("input[name='totalizar_total_general']").val(parseFloat($("input[name='totalizar_total_operacion']").val())+parseFloat($("input[name='totalizar_monto_iva']").val()));
            return false;
        }

        datoscliente = eval($("input[name='DatosCliente']").val());
        $(this).val(parseFloat($(this).val()));

        if(parseFloat($(this).val()) > parseFloat(datoscliente[0]['porc_descuento_global'])){
            alert("El porcentaje global no puede ser mayor al del cliente: "+datoscliente[0]['porc_descuento_global']+" %");
            $("input[name='totalizar_descuento_global']").val(0).toFixed(2);
            $("input[name='totalizar_base_imponible'], input[name='totalizar_neto']").val($("input[name='totalizar_total_operacion']").val());
            $(this).val("0");
            return false;
        }

        tmp_totalizar_total_general = 0;
        tmp_totalizar_descuento_global = parseFloat($("input[name='totalizar_total_operacion']").val())*parseFloat($(this).val())/100;
        //tmp_totalizar_descuento_global = parseFloat($("input[name='totalizar_total_operacion']").val() * $(this).val() / 100).toFixed(2);
        tmp_totalizar_monto_iva =  $("input[name='totalizar_monto_iva']").val() - $("input[name='totalizar_monto_iva']").val() * $("input[name='totalizar_pdescuento_global']").val()/100;
        tmp_totalizar_base_imponible = $("input[name='totalizar_total_operacion']").val() - $("input[name='totalizar_descuento_global']").val();
        tmp_totalizar_total_general = parseFloat(tmp_totalizar_base_imponible)  + parseFloat(tmp_totalizar_monto_iva) - parseFloat(tmp_totalizar_descuento_global);

        $("input[name='totalizar_base_imponible']").val(tmp_totalizar_base_imponible.toFixed(2));
        $("input[name='totalizar_monto_iva'], input[name='tmpiva_'], input[name='tmpiva_']").val(tmp_totalizar_monto_iva.toFixed(2));
        $("input[name='totalizar_total_general']").val(tmp_totalizar_total_general.toFixed(2));
        $("input[name='totalizar_descuento_global']").val(parseFloat(tmp_totalizar_descuento_global.toFixed(2)));
        $("input[name='totalizar_neto'], input[name='totalizar_base_imponible']").val(parseFloat($("input[name='totalizar_total_operacion']").val())-parseFloat($("input[name='totalizar_descuento_global']").val()));
        $("input[name='totalizar_monto_cancelar']").val($("input[name='totalizar_total_general']").val());
        $("input[name='totalizar_monto_cancelar']").select();
        $("input[name='totalizar_monto_efectivo']").val($("input[name='totalizar_total_general']").val());
    });

    $("#totalizar_descuento_global").click(function(){
        $.totalizarFactura();
        $(this).numeric();
        if($(this).val()=="") {
            $(this).val("0");
        }
        $(this).val($(this).val()).select();
    }).change(function(){
        if($(this).val()==""||$(this).val()>100||$(this).val()=="."){
            $(this).val("0");
            $("input[name='totalizar_descuento_global']").val("0");
            $("input[name='totalizar_base_imponible'], input[name='totalizar_neto']").val($("input[name='totalizar_total_operacion']").val());
            return false;
        }
        if($(this).val()==0){
            $("input[name='totalizar_descuento_global']").val(0);
            $("input[name='totalizar_total_general']").val(parseFloat($("input[name='totalizar_total_operacion']").val())+parseFloat($("input[name='totalizar_monto_iva']").val()));
            return false;
        }
        datoscliente = eval($("input[name='DatosCliente']").val());
        $(this).val(parseFloat($(this).val()));
        tmp_totalizar_pdescuento_global = parseFloat($(this).val())*100/parseFloat($("input[name='totalizar_total_operacion']").val());

        if(parseFloat(tmp_totalizar_pdescuento_global) > parseFloat(datoscliente[0]['porc_descuento_global'])){
            alert("El porcentaje global no puede ser mayor al del cliente: "+datoscliente[0]['porc_descuento_global']+" %");
            $("input[name='totalizar_descuento_global']").val(0);
            $("input[name='totalizar_base_imponible'], input[name='totalizar_neto']").val($("input[name='totalizar_total_operacion']").val());
            $(this).val("0");
            return false;
        }

        tmp_totalizar_total_general = 0;
        tmp_totalizar_descuento_global = parseFloat($("input[name='totalizar_total_operacion']").val())*parseFloat(tmp_totalizar_pdescuento_global)/100;
        tmp_totalizar_base_imponible = $("input[name='totalizar_total_operacion']").val() - $("input[name='totalizar_descuento_global']").val();
        tmp_totalizar_monto_iva =  $("input[name='totalizar_monto_iva']").val() - $("input[name='totalizar_monto_iva']").val() * tmp_totalizar_pdescuento_global/100;
        tmp_totalizar_total_general = parseFloat(tmp_totalizar_base_imponible) + parseFloat(tmp_totalizar_monto_iva);

        $("input[name='totalizar_base_imponible']").val(tmp_totalizar_base_imponible.toFixed(2));
        $("input[name='totalizar_monto_iva'], input[name='tmpiva_'], input[name='tmpiva_']").val(tmp_totalizar_monto_iva.toFixed(2));
        $("input[name='totalizar_total_general']").val(tmp_totalizar_total_general.toFixed(2));
        $("input[name='totalizar_descuento_global']").val(parseFloat(tmp_totalizar_descuento_global.toFixed(2)));
        $("input[name='totalizar_pdescuento_global']").val(parseFloat(tmp_totalizar_pdescuento_global.toFixed(2)));
        $("input[name='totalizar_neto'], input[name='totalizar_base_imponible']").val(parseFloat($("input[name='totalizar_total_operacion']").val())-parseFloat($("input[name='totalizar_descuento_global']").val()));
        $("input[name='totalizar_monto_cancelar']").val($("input[name='totalizar_total_general']").val());
        $("input[name='totalizar_monto_cancelar']").select();
        $("input[name='totalizar_monto_efectivo']").val($("input[name='totalizar_total_general']").val());
    });
    $('input:radio[name="forma_pago"]').change(
        function(){
            if ($(this).is(':checked') && $(this).val() == 'credito'){
                montoOLD = $("input[name='totalizar_monto_cancelar']").val();
                $("input[name='totalizar_monto_cancelar']").val(0);
                $("input[name='totalizar_monto_cancelar']").select();
                retenciones = 0;
                retenciones = parseFloat($("#totalizar_total_retencion").val());
                resultado = parseFloat($("input[name='totalizar_total_general']").val());
                total_pendiente = parseFloat(resultado) - parseFloat(retenciones);
                if(montoOLD==""){
                    resultado = parseFloat($("input[name='totalizar_total_general']").val());
                    $("input[name='totalizar_monto_cancelar']").val(total_pendiente);
                    $("input[name='totalizar_monto_cancelar']").select();
                    $("input[name='totalizar_saldo_pendiente'], input[name='totalizar_cambio']").val("0");
                    return false;
                }
                resultado = parseFloat($("input[name='totalizar_total_factura']").val());
                $("input[name='totalizar_monto_efectivo']").val(total_pendiente.toFixed(2));
                if(parseFloat($(this).val())<parseFloat(total_pendiente)){
                    restante = parseFloat(total_pendiente) - parseFloat($(this).val());
                    $("input[name='totalizar_saldo_pendiente']").val(restante.toFixed(2));
                    $("input[name='totalizar_monto_efectivo']").val(restante.toFixed(2));
                }
                if(parseFloat($(this).val())>parseFloat(resultado)){
                    restante = parseFloat($(this).val()) - parseFloat(resultado);
                    $("input[name='totalizar_cambio']").val(restante.toFixed(2));
                    $("input[name='totalizar_monto_efectivo']").val($(this).val());
                }
                $("input[name='totalizar_monto_efectivo']").val($("input[name='totalizar_monto_cancelar']").val());
                if(parseFloat($(this).val())>parseFloat(resultado)){
                    cambio=parseFloat($("input[name='totalizar_monto_cancelar']").val())-parseFloat($("input[name='totalizar_total_general']").val());
                    $("input[name='totalizar_cambio']").val(cambio.toFixed(2));
                }
                if(parseFloat($("input[name='totalizar_saldo_pendiente']").val())>0){
                    $("#info_pago_pendiente").show(100);
                }
            }
            else if($(this).is(':checked') && $(this).val() == 'contado'){
                retenciones = 0;
                datoscliente = eval($("input[name='DatosCliente']").val());
                retenciones = parseFloat($("#totalizar_total_retencion").val());
                resultado = parseFloat($("input[name='totalizar_total_general']").val());
                total_pendiente = parseFloat(resultado) - parseFloat(retenciones);

                $("input[name='totalizar_monto_cancelar']").val(total_pendiente.toFixed(2));
                $("input[name='totalizar_monto_cancelar']").select();
                $("input[name='totalizar_monto_efectivo']").val(total_pendiente.toFixed(2));

                $("input[name='totalizar_saldo_pendiente'], input[name='totalizar_cambio']").val("0");
                $("#opt_cheque, #opt_tarjeta, #opt_deposito, #opt_otrodocumento").val(0).trigger("change");
                $("#info_pago_pendiente").find("input").attr("value", "");
                $("#info_pago_pendiente textarea").val("");
                $("#info_pago_pendiente").hide(100);
            }
        }
        );
    $("input[name='totalizar_monto_cancelar']").click(function(){
        $(this).numeric();
        retenciones = 0;
        datoscliente = eval($("input[name='DatosCliente']").val());
        retenciones = parseFloat($("#totalizar_total_retencion").val());

        //gastos = parseFloat($("input[name='total_fob_gatos']").val());
        resultado = parseFloat($("input[name='totalizar_total_general']").val());

        total_pendiente = parseFloat(resultado) - parseFloat(retenciones); //+ parseFloat(gastos);

        $("input[name='totalizar_monto_cancelar']").val(total_pendiente.toFixed(2));
        $("input[name='totalizar_monto_cancelar']").select();
        $("input[name='totalizar_monto_efectivo']").val(total_pendiente.toFixed(2));

        $("input[name='totalizar_saldo_pendiente'], input[name='totalizar_cambio']").val("0");
        $("#opt_cheque, #opt_tarjeta, #opt_deposito, #opt_otrodocumento").val(0).trigger("change");
        $("#info_pago_pendiente").find("input").attr("value", "");
        $("#info_pago_pendiente textarea").val("");
        $("#info_pago_pendiente").hide(100);
    }).blur(function(){
        montoOLD = $(this).val();
        retenciones = 0;
        retenciones = parseFloat($("#totalizar_total_retencion").val());
        resultado = parseFloat($("input[name='totalizar_total_general']").val());

        //gastos = parseFloat($("input[name='total_fob_gatos']").val());

        total_pendiente = parseFloat(resultado) - parseFloat(retenciones); // + parseFloat(gastos);

        if(montoOLD==""){
            resultado = parseFloat($("input[name='totalizar_total_general']").val());
            $("input[name='totalizar_monto_cancelar']").val(total_pendiente);
            $("input[name='totalizar_monto_cancelar']").select();
            $("input[name='totalizar_saldo_pendiente'], input[name='totalizar_cambio']").val("0");
            return false;
        }

        resultado = parseFloat($("input[name='totalizar_total_factura']").val());

        $("input[name='totalizar_monto_efectivo']").val(total_pendiente.toFixed(2));

        if(parseFloat($(this).val())<parseFloat(total_pendiente)){
            restante = parseFloat(total_pendiente) - parseFloat($(this).val());
            $("input[name='totalizar_saldo_pendiente']").val(restante.toFixed(2));
            $("input[name='totalizar_monto_efectivo']").val(restante.toFixed(2));
        }

        if(parseFloat($(this).val())>parseFloat(resultado)){
            restante = parseFloat($(this).val()) - parseFloat(resultado);
            $("input[name='totalizar_cambio']").val(restante.toFixed(2));
            $("input[name='totalizar_monto_efectivo']").val($(this).val());
        }

        $("input[name='totalizar_monto_efectivo']").val($("input[name='totalizar_monto_cancelar']").val())

        if(parseFloat($(this).val())>parseFloat(resultado)){
            cambio=parseFloat($("input[name='totalizar_monto_cancelar']").val())-parseFloat($("input[name='totalizar_total_general']").val());
            $("input[name='totalizar_cambio']").val(cambio.toFixed(2));
        }
        if(parseFloat($("input[name='totalizar_saldo_pendiente']").val())>0){
            $("#info_pago_pendiente").show(100);
        }
    });

    $("input[name='PFactura2']").live("click", function(){

        sum0 = $("input[name='totalizar_monto_efectivo']").val();

        //validar si selecciono el modo de pago con cheque
        valor = $("#opt_cheque").val();
        sw =false;
        if(valor==1){
            if($("input[name='totalizar_monto_cheque']").val()=="0"){
                sw=true;
            }
            if($("input[name='totalizar_nro_cheque']").val()=="0"){
                sw=true;
            }
            if($("#totalizar_nombre_banco").val()=="0"){
                sw=true;
            }
            if(sw==true){
                $.facebox("Verifique los datos de instrumento pago (CHEQUE)");
                return false;
            }
            sum1 =  $("input[name='totalizar_monto_cheque']").val();
        }else{
            sum1=0;
        }

        //validar si selecciono el modo de pago con retencion iva walter
        valor = $("#opt_retencion_iva").val();
        sw =false;
        if(valor==1){
            if($("input[name='totalizar_monto_retenido']").val()=="0"){
                sw=true;
            }
            if($("input[name='totalizar_nro_retencion']").val()=="0"){
                sw=true;
            }
            
            if(sw==true){
                $.facebox("Verifique los datos de instrumento pago (Retención)");
                return false;
            }
            sum6 =  $("input[name='totalizar_monto_retenido']").val();
        }else{
            sum6=0;
        }

        //validar 1x1000 walter
        valor = $("#opt_1x1000").val();
        sw =false;
        if(valor==1){
            if($("input[name='totalizar_monto_retenido1x1000']").val()=="0"){
                sw=true;
            }
            if($("input[name='totalizar_nro_retencion1x1000']").val()=="0"){
                sw=true;
            }
            
            if(sw==true){
                $.facebox("Verifique los datos de instrumento pago (Retención 1x1000)");
                return false;
            }
            sum7 =  $("input[name='totalizar_monto_retenido1x1000']").val();
        }else{
            sum7=0;
        }

        //validar credito walter
        valor = $("#opt_credito2").val();
        sw =false;
        if(valor==1){
            if($("input[name='totalizar_credito2']").val()=="0"){
                sw=true;
            }
                       
            if(sw==true){
                $.facebox("Verifique los datos de instrumento pago (Credito)");
                return false;
            }
            sum8 =  $("input[name='totalizar_credito2']").val();
        }else{
            sum8=0;
        }

        //validar si selecciono el modo de pago con tarjeta
        valor = $("#opt_tarjeta").val();
        sw =false;
        if(valor==1){
            if($("input[name='totalizar_monto_tarjeta']").val()=="0"){
                sw=true;
            }
            if($("input[name='totalizar_nro_tarjeta']").val()=="0"){
                sw=true;
            }
            if($("#totalizar_tipo_tarjeta").val()=="0"){
                sw=true;
            }
            if(sw==true){
                $.facebox("Verifique los datos de instrumento pago (TARJETA)");
                return false;
            }
            sum2 =  $("input[name='totalizar_monto_tarjeta']").val();
        }else{
            sum2=0;
        }

        //validar si selecciono el modo de pago con deposito
        valor = $("#opt_deposito").val();
        sw =false;
        if(valor==1){
            if($("input[name='totalizar_monto_deposito']").val()=="0"){
                sw=true;
            }
            if($("input[name='totalizar_nro_deposito']").val()=="0"){
                sw=true;
            }
            if($("#totalizar_banco_deposito").val()=="0"){
                sw=true;
            }
            if($("#totalizar_banco_deposito_cuenta").val()=="0" || $("#totalizar_banco_deposito_cuenta").val()=="-1"){
                sw=true;
            }
            if(sw==true){
                $.facebox("Verifique los datos de instrumento pago (DEPOSITO)");
                return false;
            }
            sum3 =  $("input[name='totalizar_monto_deposito']").val();
        }else{
            sum3=0;
        }

        //validar si selecciono el modo de pago otro documento
        valor = $("#opt_otrodocumento").val();
        sw =false;
        if(valor==1){

            if($("#totalizar_tipo_otrodocumento").val()=="0"){
                sw=true;
            }
            if($("input[name='totalizar_monto_otrodocumento']").val()=="0"){
                sw=true;
            }
            //if($("input[name='totalizar_nro_otrodocumento']").val()=="0"){sw=true;}
            //if($("#totalizar_banco_otrodocumento").val()=="0"){sw=true;}
            if(sw==true){
                $.facebox("Verifique los datos de instrumento pago (OTRO DOCUMENTO)");
                return false;
            }
            sum4 =  $("input[name='totalizar_monto_otrodocumento']").val();
        }else{
            sum4=0;
        }

        total_acancelar= parseFloat(sum0) + parseFloat(sum1) + parseFloat(sum2)+ parseFloat(sum3)  + parseFloat(sum4) + parseFloat(sum6) + parseFloat(sum7) + parseFloat(sum8);
       // alert(total_acancelar);
        //alert(total_acancelar+" "+parseFloat($("input[name='totalizar_monto_cancelar']").val()));
        tcancelar = $("input[name='totalizar_monto_cancelar'").val();

        if(parseFloat(total_acancelar)<parseFloat(tcancelar)){
            $.facebox("Verifique los montos de instrumento de pago, son distintos al del monto a cancelar.");
            return false;
        }
        $.setValoresInput("#input_totalizar_sub_total","#totalizar_sub_total");
        $.setValoresInput("#input_totalizar_descuento_parcial","#totalizar_descuento_parcial");
        $.setValoresInput("#input_totalizar_total_operacion","#totalizar_total_operacion");

        $.setValoresInput("#input_totalizar_pdescuento_global","#totalizar_pdescuento_global");
        $.setValoresInput("#input_totalizar_descuento_global","#totalizar_descuento_global");
        $.setValoresInput("#input_totalizar_monto_iva","#totalizar_monto_iva");
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
        var cuenta = document.getElementById("totalizar_banco_deposito_cuenta");
        var cuentaText = cuenta.options[cuenta.selectedIndex].text;
        document.getElementById("input_totalizar_banco_deposito_cuenta").value=cuentaText;
        //$.setValoresInput("#input_totalizar_banco_deposito_cuenta",cuentaText);

        valor = $("input[name='totalizar_saldo_pendiente']").val();
        if(parseFloat(valor)>0){

            if($("input[name='fecha_vencimiento']").val()==""){
                $.facebox("Debe especificar la fecha vencimiento.");
                return false;
            }

            if($("input[name='observacion']").val()==""){
                $.facebox("Debe especificar una observaci&oacute;n.");
                return false;
            }

            if($("input[name='persona_contacto']").val()==""){
                $.facebox("Debe especificar la persona de contacto.");
                return false;
            }

            if($("input[name='telefono']").val()==""){
                $.facebox("Debe especificar el telefono.");
                return false;
            }
        }

        if(!confirm("Emitir")){
            return false;
        }else{
            //COLOCAR EL PROCEDIMIENTO PARA VERIFICAR ESTATUS DE LA IMPRESORA FISCAL
         
            $.ajax({
                type: 'GET',
                data: 'opt=estatusImpresora',
                url: '../../libs/php/ajax/ajax.php',
                beforeSend: function() {
                    
                },
                success: function(data) { 
                    if(data==0){
                        alert("La impresora Fiscal no esta conectada o esta apagada!");
                        
                       // return false;                          
                    }else{
                        var datos = $("#formulario").serialize();
                        $.ajax({
                            type: 'POST',
                            data: datos,
                            url: '../../modulos/principal/factura_rapida_nueva.php',
                            beforeSend: function() {
                                
                            },
                            success: function(data) {     
                                if(data==0)
                                {
                                    alert("La impresora Fiscal No puede ejecutar la accion! informe la situacion a un tecnico1");                        
                                    return false;
                                }
                                else
                                {
                                    if(data==100 || data==200 || data==300 || data==400)
                                    {
                                        alert("Error, faltan datos en la retencion o el Nro. de retención ya se usó");
                                        return false;
                                    }
                                    else
                                    {
                                        if(data==500)
                                        {
                                            alert("Error, La facturación crediticia debe ser por el monto total de la factura");
                                        }
                                        else
                                        {
                                        window.close();
                                        }
                                    }
                               
                                }
                            }
                        });//fin del ajax 2 
                       
                        //$("#formulario").submit();
                    }
                }
            });//fin del ajax 1
            
           
           // $("#formulario").submit();
        }
         return false;
    });

    $("#opt_cheque").change(function(){
        valor = $(this).val();

        if(valor==0){
            $("#totalizar_nombre_banco,input[name='totalizar_nro_cheque'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque']").val("0");
            $("#totalizar_nombre_banco,input[name='totalizar_nro_cheque'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque']").attr("readonly", "readonly");
        }

        if(valor==1){
            $("#totalizar_nombre_banco,input[name='totalizar_nro_cheque'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque']").val("0");
            $("#totalizar_nombre_banco,input[name='totalizar_nro_cheque'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque']").removeAttr("readonly");
        }

    });
    //cambios walter
    $("#opt_retencion_iva").change(function(){
        valor = $(this).val();

        if(valor==0){
            $("#totalizar_monto_retenido,input[name='totalizar_monto_retenido'], input[name='totalizar_monto_retenido']").val("0");
            $("#totalizar_monto_retenido,input[name='totalizar_monto_retenido'], input[name='totalizar_monto_retenido']").attr("readonly", "readonly");
        }

        if(valor==1){
            $("#totalizar_monto_retenido,input[name='totalizar_monto_retenido'], input[name='totalizar_monto_retenido']").val("0");
            $("#totalizar_monto_retenido,input[name='totalizar_monto_retenido'], input[name='totalizar_monto_retenido']").removeAttr("readonly");
        }

    });

    $("#opt_credito2").change(function(){
        valor = $(this).val();

        if(valor==0){
            $("#totalizar_credito2,input[name='totalizar_credito2'], input[name='totalizar_credito2']").val("0");
            $("#totalizar_credito2,input[name='totalizar_credito2'], input[name='totalizar_credito2']").attr("readonly", "readonly");
        }

        if(valor==1){
            $("#totalizar_credito2,input[name='totalizar_credito2'], input[name='totalizar_credito2']").val("0");
            $("#totalizar_credito2,input[name='totalizar_credito2'], input[name='totalizar_credito2']").removeAttr("readonly");
        }

    });

    $("#opt_1x1000").change(function(){
        valor = $(this).val();

        if(valor==0){
            $("#totalizar_monto_retenido1x100,input[name='totalizar_monto_retenido1x1000'], input[name='totalizar_monto_retenido1x1000']").val("0");
            $("#totalizar_monto_retenido1x1000,input[name='totalizar_monto_retenido1x1000'], input[name='totalizar_monto_retenido1x1000']").attr("readonly", "readonly");
        }

        if(valor==1){
            $("#totalizar_monto_retenido1x100,input[name='totalizar_monto_retenido1x1000'], input[name='totalizar_monto_retenido1x1000']").val("0");
            $("#totalizar_monto_retenido1x1000,input[name='totalizar_monto_retenido1x1000'], input[name='totalizar_monto_retenido1x1000']").removeAttr("readonly");
        }

    });

    $("#opt_tarjeta").change(function(){
        valor = $(this).val();

        if(valor==0){
            $("#totalizar_tipo_tarjeta,input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta']").val("0");
            $("#totalizar_tipo_tarjeta,input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta']").attr("readonly", "readonly");
        }

        if(valor==1){
            $("#totalizar_tipo_tarjeta,input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta']").val("0");
            $("#totalizar_tipo_tarjeta,input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta']").removeAttr("readonly");
        }

    });

    $("#opt_deposito").change(function(){
        valor = $(this).val();

        if(valor==0){
            $("#totalizar_banco_deposito,input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").val("0");
            $("#totalizar_banco_deposito,input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").attr("readonly", "readonly");
        }

        if(valor==1){
            $("#totalizar_banco_deposito,input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").val("0");
            $("#totalizar_banco_deposito,input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").removeAttr("readonly");
        }

    });

    $("#opt_otrodocumento").change(function(){
        valor = $(this).val();

        if(valor==0){
            $("#totalizar_banco_otrodocumento,#totalizar_tipo_otrodocumento,input[name='totalizar_monto_otrodocumento'], input[name='totalizar_nro_otrodocumento']").val("0");
            $("#totalizar_banco_otrodocumento,#totalizar_tipo_otrodocumento,input[name='totalizar_monto_otrodocumento'], input[name='totalizar_nro_otrodocumento']").attr("readonly", "readonly");
        }

        if(valor==1){
            $("#totalizar_banco_otrodocumento,#totalizar_tipo_otrodocumento,input[name='totalizar_monto_otrodocumento'], input[name='totalizar_nro_otrodocumento']").val("0");
            $("#totalizar_banco_otrodocumento,#totalizar_tipo_otrodocumento,input[name='totalizar_monto_otrodocumento'], input[name='totalizar_nro_otrodocumento']").removeAttr("readonly");
        }
    });

    $(".ctotalizar_").attr("autocomplete","off");
    $(".ctotalizar_, #totalizar_monto_cancelar").blur(function(){
        valor = $(this).val();
        if(valor==NaN||valor=='.'){
            $(this).val(0)
        }
    });
});

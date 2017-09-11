/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
montoOLD = 0;
listo = false;

$("#info_pago_pendiente").hide();
$("#opt_cheque, #opt_tarjeta, #opt_deposito, #opt_otrodocumento").val(0).trigger("change");

for($i=1;$i<=$("input[name='cantidad_impuesto']").val();$i++)
{
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
            }else
            {
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
                       for($i=1;$i<=$("input[name='cantidad_impuesto']").val();$i++)
                       {
                        inputmontoretencion ="totalizar_monto_retencion"+$i;

                        //inputpbaseretencion ="totalizar_pbase_retencion"+$i;
                        //alert($("input[name='"+inputmontoretencion+"']").val())
                       totalretencion = parseFloat(totalretencion) + (parseFloat($("input[name='"+inputmontoretencion+"']").val()));
                       }
                       //alert($("input[name='totalizar_total_retencion']").val(totalretencion.toFixed(2)))
                       $("input[name='totalizar_total_retencion']").val(totalretencion.toFixed(2));

                       //$("input[name='totalizar_total_general']").val();
                        $("input[name='totalizar_total_factura']").val(($("input[name='totalizar_total_general']").val()-parseFloat(totalretencion)).toFixed(2));
                        $("input[name='totalizar_monto_cancelar']").val(($("input[name='totalizar_total_general']").val()-parseFloat(totalretencion)).toFixed(2));
                        //alert($("input[name='totalizar_total_factura']").val());
                        $("input[name='totalizar_monto_efectivo']").val(($("input[name='totalizar_total_general']").val()-parseFloat(totalretencion)).toFixed(2));
                    }
            });
}
});
} // FIN DEL FOR DE CALCULO DE CADA SELECCION DE IMPUESTO


$.totalizarFactura = function(){
    if(listo==false){
    datoscliente = eval($("input[name='DatosCliente']").val());
    tmp_subtotal=0;
    tmp_descuento=0;
    tmp_iva=0;
    tmp_totalizar_total_general =0;

   $(".ctotalizar_").each(function(){
       $(this).attr("value", "0");
   })

    $(".grid table.lista tbody").find("tr").each(function(){
    tmp_subtotal += parseFloat($(this).find("td").eq(2).attr("rel"))*parseFloat($(this).find("td").eq(3).html());
    tmp_descuento  += parseFloat($(this).find("td").eq(5).html());
    tmp_iva += parseFloat($(this).find("td").eq(8).attr("rel"));
    tmp_totalizar_total_general += parseFloat($(this).find("td").eq(6).html());
    });



 $("input[name='totalizar_pdescuento_global'],input[name='totalizar_descuento_global']").val(0)
    $("input[name='totalizar_sub_total']").val(parseFloat(tmp_subtotal).toFixed(2));
    $("input[name='totalizar_descuento_parcial']").val(parseFloat(tmp_descuento).toFixed(2));
    $("input[name='totalizar_total_operacion']").val((parseFloat(tmp_subtotal)-parseFloat(tmp_descuento)).toFixed(2));
    restar= parseFloat(tmp_subtotal)-parseFloat(tmp_descuento)
    $("input[name='totalizar_base_imponible']").val(restar.toFixed(2));
    $("input[name='totalizar_monto_iva']").val(tmp_iva.toFixed(2));
    $("input[name='totalizar_total_general']").val((parseFloat(tmp_totalizar_total_general)+parseFloat(tmp_iva)).toFixed(2));

    $("input[name='totalizar_monto_cancelar']").val($("input[name='totalizar_total_general']").val());
    $("input[name='totalizar_monto_efectivo']").val($("input[name='totalizar_total_general']").val());
    $("select[name='totalizar_descripcion_base_retencion']").find("option").remove();
          return false;}
}//Fin de $.totalizarFactura = function(){ ********************************


        $(".tabpanel2").hide();
        $("#tab1").addClass("click");


        $(".tab").live("mouseover", function(){
            $(this).addClass("sobreboton");
        }).live("mouseout", function(){
            $(this).removeClass("sobreboton");
        }).live("click",function(){
           $(".tab").removeClass("click");
           $(this).addClass("click");
           tabclick = $(this).attr("id");
            if(tabclick=="tab1"){$(".tabpanel2").hide();$(".tabpanel1").show();}
            if(tabclick=="tab2"){$(".tabpanel1").hide();$(".tabpanel2").show();}
            $("input[name='totalizar_monto_efectivo'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque'], input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta'],input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").numeric();
        });

    $("input[name='totalizar_monto_efectivo'], input[name='totalizar_monto_cheque'], input[name='totalizar_nro_cheque'], input[name='totalizar_monto_tarjeta'], input[name='totalizar_nro_tarjeta'],input[name='totalizar_monto_deposito'], input[name='totalizar_nro_deposito']").live("change",function(){
        valor = $(this).val();
        if(valor==""){$(this).val("0");return false;}
        valor = parseFloat($(this).val());
        if(valor=="NaN"){
            $(this).val("0");return false;
        }else{
            $(this).val(valor);
        }
    });


    $("#totalizar_pdescuento_global").live("click" , function(){
        $.totalizarFactura();
         $(this).numeric();
         if($(this).val()=="") {$(this).val("0");}
         $(this).val($(this).val()).select();
    }).live("change",function(){
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
        if(parseFloat($(this).val())>parseFloat(datoscliente[0]['porc_descuento_global'])){
          //  alert("El porcentaje global no puede ser mayor al del cliente: "+datoscliente[0]['porc_descuento_global']+"%");
             $("input[name='totalizar_descuento_global']").val(0);
             $("input[name='totalizar_base_imponible'], input[name='totalizar_neto']").val($("input[name='totalizar_total_operacion']").val());
            $(this).val("0");
            return false;
        }


        tmp_totalizar_total_general=0;
        tmp_totalizar_descuento_global = parseFloat($("input[name='totalizar_total_operacion']").val())*parseFloat($(this).val())/100;
        tmp_totalizar_monto_iva =  $("input[name='totalizar_monto_iva']").val() - ( $("input[name='totalizar_monto_iva']").val() * $("input[name='totalizar_pdescuento_global']").val()/100 ) ;


        tmp_totalizar_base_imponible = $("input[name='totalizar_total_operacion']").val() - $("input[name='totalizar_descuento_global']").val();
        tmp_totalizar_total_general = parseFloat(tmp_totalizar_base_imponible)  + parseFloat(tmp_totalizar_monto_iva) - parseFloat(tmp_totalizar_descuento_global);



        $("input[name='totalizar_base_imponible']").val(tmp_totalizar_base_imponible.toFixed(2));
        $("input[name='totalizar_monto_iva']").val(tmp_totalizar_monto_iva.toFixed(2));


        $("input[name='totalizar_total_general']").val(tmp_totalizar_total_general.toFixed(2));
        $("input[name='totalizar_descuento_global']").val(parseFloat(tmp_totalizar_descuento_global.toFixed(2)));




        $("input[name='totalizar_neto'], input[name='totalizar_base_imponible']").val(parseFloat($("input[name='totalizar_total_operacion']").val())-parseFloat($("input[name='totalizar_descuento_global']").val()));


    $("input[name='totalizar_monto_cancelar']").val($("input[name='totalizar_total_general']").val());
    $("input[name='totalizar_monto_cancelar']").select();
    $("input[name='totalizar_monto_efectivo']").val($("input[name='totalizar_total_general']").val());

    });




$("input[name='totalizar_monto_cancelar']").click(function(){
 $(this).numeric();
 datoscliente = eval($("input[name='DatosCliente']").val());
/* if(datoscliente[0]['calc_reten_impuesto_islr']==1){
     resultado = parseFloat($("input[name='totalizar_base_retencion']").val()) - parseFloat($("input[name='totalizar_monto_retencion']").val())
     $("input[name='totalizar_monto_cancelar']").select();
    $("input[name='totalizar_monto_cancelar'], input[name='totalizar_monto_efectivo']").val(resultado.toFixed(2));
 }else{*/
    $("input[name='totalizar_monto_cancelar']").val($("input[name='totalizar_total_general']").val());
    $("input[name='totalizar_monto_cancelar']").select();
    $("input[name='totalizar_monto_efectivo']").val($("input[name='totalizar_total_general']").val());
//}

$("input[name='totalizar_saldo_pendiente'], input[name='totalizar_cambio']").val("0");
$("#opt_cheque, #opt_tarjeta, #opt_deposito, #opt_otrodocumento").val(0).trigger("change");

 //$("#info_pago_pendiente").find("input").attr("value", "");
 //$("#info_pago_pendiente textarea").val("");

 //$("#info_pago_pendiente").hide(100);


}).blur(function(){
    montoOLD = $(this).val();

        if(montoOLD==""){
            resultado = parseFloat($("input[name='totalizar_total_general']").val());
            $("input[name='totalizar_monto_cancelar']").val(resultado);
            $("input[name='totalizar_monto_cancelar']").select();
            $("input[name='totalizar_saldo_pendiente'], input[name='totalizar_cambio']").val("0");
        return false;
        }

        resultado = parseFloat($("input[name='totalizar_total_general']").val());
        if(parseFloat($(this).val())<parseFloat(resultado)){
        restante = parseFloat(resultado) - parseFloat($(this).val());
        $("input[name='totalizar_saldo_pendiente']").val(restante.toFixed(2));
        $("input[name='totalizar_monto_efectivo']").val($(this).val());
        }

        if(parseFloat($(this).val())>parseFloat(resultado)){
        restante = parseFloat($(this).val()) - parseFloat(resultado);
        $("input[name='totalizar_cambio']").val(restante.toFixed(2));
        $("input[name='totalizar_monto_efectivo']").val($(this).val());

        }

        resultado =  parseFloat($("input[name='totalizar_cambio']").val()) + parseFloat($("input[name='totalizar_saldo_pendiente']").val())+ parseFloat( $("input[name='totalizar_monto_cancelar']").val());
   });

      $.setValoresInput = function(nombreObjetoDestino,nombreObjetoActual){
        $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
    }

$("input[name='PFactura2']").live("click", function(){


    if($("input[name='fecha_emision']").val()==""){
        $.facebox("Debe ingresar la fecha de emisión");
        $("input[name='fecha_emision']").focus();
        return false;
    }

    if($("input[name='descripcion_pagoabono']").val()==""){
        $.facebox("Debe la descripcion del abono");
        $("input[name='descripcion_pagoabono']").focus();
        return false;
    }


    sum0 = $("input[name='totalizar_monto_efectivo']").val();



    //validar si selecciono el modo de pago con cheque
    valor = $("#opt_cheque").val();
    sw =false;
    if(valor==1){
        if($("input[name='totalizar_monto_cheque']").val()=="0"){sw=true;}
        if($("input[name='totalizar_nro_cheque']").val()=="0"){sw=true;}
        if($("#totalizar_nombre_banco").val()=="0"){sw=true;}
        if(sw==true){
            $.facebox("Verifique los datos de instrumento pago (CHEQUE)");
            return false;
        }
        sum1 =  $("input[name='totalizar_monto_cheque']").val();
    }else{sum1=0;}


    //validar si selecciono el modo de pago con tarjeta
    valor = $("#opt_tarjeta").val();
    sw =false;
    if(valor==1){
        if($("input[name='totalizar_monto_tarjeta']").val()=="0"){sw=true;}
        if($("input[name='totalizar_nro_tarjeta']").val()=="0"){sw=true;}
        if($("#totalizar_tipo_tarjeta").val()=="0"){sw=true;}
        if(sw==true){
            $.facebox("Verifique los datos de instrumento pago (TARJETA)");
            return false;
        }
        sum2 =  $("input[name='totalizar_monto_tarjeta']").val();
    }else{sum2=0;}

    //validar si selecciono el modo de pago con deposito
    valor = $("#opt_deposito").val();
    sw =false;
    if(valor==1){
        if($("input[name='totalizar_monto_deposito']").val()=="0"){sw=true;}
        if($("input[name='totalizar_nro_deposito']").val()=="0"){sw=true;}
        if($("#totalizar_banco_deposito").val()=="0"){sw=true;}
        if(sw==true){
            $.facebox("Verifique los datos de instrumento pago (DEPOSITO)");
            return false;
        }
        sum3 =  $("input[name='totalizar_monto_deposito']").val();
    }else{sum3=0;}


 //validar si selecciono el modo de pago otro documento
    valor = $("#opt_otrodocumento").val();
    sw =false;
    if(valor==1){

        if($("#totalizar_tipo_otrodocumento").val()=="0"){sw=true;}
        if($("input[name='totalizar_monto_otrodocumento']").val()=="0"){sw=true;}
        //if($("input[name='totalizar_nro_otrodocumento']").val()=="0"){sw=true;}
        //if($("#totalizar_banco_otrodocumento").val()=="0"){sw=true;}
        if(sw==true){
            $.facebox("Verifique los datos de instrumento pago (OTRO DOCUMENTO)");
            return false;
        }
        sum4 =  $("input[name='totalizar_monto_otrodocumento']").val();
    }else{sum4=0;}

    total_acancelar= parseFloat(sum0) + parseFloat(sum1) + parseFloat(sum2)+ parseFloat(sum3)  + parseFloat(sum4);
    //alert(total_acancelar+" "+parseFloat($("input[name='totalizar_monto_cancelar']").val()));
    if(parseFloat(total_acancelar) > parseFloat($("input[name='totalizar_monto_cancelar']").val()) || parseFloat(total_acancelar) < parseFloat($("input[name='totalizar_monto_cancelar']").val()) ){
            $.facebox("Verifique los montos de instrumento de pago, son distintos al del monto a cancelar.");
            return false;
    }


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

        valor = $("input[name='totalizar_saldo_pendiente']").val();

    if(parseFloat(valor)>0){
/*
        if($("input[name='fecha_vencimiento']").val()==""){
            $.facebox("Debe especificar la fecha vencimiento.");
            return false;
        }

        if($("input[name='observacion']").val()==""){
            $.facebox("Debe especificar una observación.");
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
*/

    }


    if(!confirm("¿Esta seguro de generar la factura?")){
        return false;
    }
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
        if(valor=='NaN'||valor=='.'){$(this).val(0)}
     });




});





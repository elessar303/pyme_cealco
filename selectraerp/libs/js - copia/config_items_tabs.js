function validarNumero(e) {
    var tecla = (document.all) ? e.keyCode : e.which;
	if(tecla!=0){
		if(tecla!=8){
			if(tecla<48||tecla>57){
				return false;
			}else{
				return true;
			}
		}
	}
} // fin del function validarLetras(e) {


$(document).ready(function(){
 var suma = 0;


   

$("#tab1").addClass("click");
          $("#div_tab1").show();
          $("#div_tab2").hide();
          $("#div_tab3").hide();
          $("#div_tab4").hide();
          campos_numericos = ".opt_escala, input[name='comisiones1'],input[name='comisiones2'],input[name='comisiones2'] , #costo_actual, #costo_promedio, #costo_anterior,#factor_cambio,#ultimo_costo, .campo_decimal, .campo_cantidad_almacen"

            $(campos_numericos).numeric();
            $(campos_numericos).blur(function(){
            if($(this).val()!=''&&$(this).val()!='.'){
                $(this).val(parseFloat($(this).val()));
            }else{
                $(this).val("0.00");
            }
        });

          //$("#costo_promedio").attr("readonly", "readonly");





    $(".campo_cantidad_almacen").blur(function(){
       suma=0;
        $(".campo_cantidad_almacen").each(function(){
            if($(this).val()!=""){
            suma = parseInt(suma) + parseInt($(this).val());
            $("#existencia_total").val(suma);
            }
        });
    });


        $("#factor_cambio, #ultimo_costo").attr("disabled","disabled");
        $("#tabla_total thead tr .precio_referencial, #tabla_total tbody tr .precio_referencial").hide();
        $("#tabla_total thead tr .opt_escala, #tabla_total tbody tr .opt_escala").hide();
        $("#tabla_total thead tr .comisiones, #tabla_total tbody tr .comisiones").hide();
        $(".input_tipo_producto").hide();
        $("#tipo_comision_x_producto").hide();
        //$(".monto_iva").hide();



        $("#tab1").mouseover(function(){
            $(this).addClass("sobreboton");
        }).mouseout(function(){
            $(this).removeClass("sobreboton");
        });

    


        $("#tipo_producto").change(function(){
            opt =  $("#tipo_producto").val();
            if(opt=='importado'){
                $("#factor_cambio, #ultimo_costo").removeAttr("disabled");
                $(".input_tipo_producto").show();
                $("#tabla_total tbody tr .precio_referencial, #tabla_total thead tr .precio_referencial").show();
            }else{
                $("#factor_cambio, #ultimo_costo").attr("disabled","disabled").val("");
                $(".input_tipo_producto").hide();
                $("#tabla_total tbody tr .precio_referencial,#tabla_total thead tr .precio_referencial").hide();
            }
        });

        $("#precio_x_escala").change(function(){
            opt = $("#precio_x_escala").val();
            if(opt==1){
                $("#tabla_total thead tr .opt_escala, #tabla_total tbody tr .opt_escala").show();
            }else{
                $("#tabla_total thead tr .opt_escala, #tabla_total tbody tr .opt_escala").hide();
            }
        });

        $("#comision_x_producto").change(function(){
        opt = $("#comision_x_producto").val();
        if(opt==1){
                $("#tabla_total thead tr .comisiones, #tabla_total tbody tr .comisiones").show();
                $("#tipo_comision_x_producto").show();
        }else{
                $("#tabla_total thead tr .comisiones, #tabla_total tbody tr .comisiones").hide();
                $("#tipo_comision_x_producto").hide();
        }
        });

        $("#monto_exento").change(function(){
            opt = $(this).val();
            if(opt==1){
                $(".monto_iva").hide();
            }else{
                $(".monto_iva").show();
            }
        });

        $("#tab2").mouseover(function(){
            $(this).addClass("sobreboton");
        }).mouseout(function(){
            $(this).removeClass("sobreboton");
        });

        $("#tab3").mouseover(function(){
            $(this).addClass("sobreboton");
        }).mouseout(function(){
            $(this).removeClass("sobreboton");
        });
        
        $("#tab4").mouseover(function(){
            $(this).addClass("sobreboton");
        }).mouseout(function(){
            $(this).removeClass("sobreboton");
        });

$("#unidad_empaque").blur(function(){
        $(".string_empaque").html($(this).val());
});



        $("#tab1").click(function(){
            $("#tab1").addClass("click");
            $("#tab2").removeClass("click");
            $("#tab3").removeClass("click");
            $("#tab4").removeClass("click");
             $("#div_tab1").show();
             $("#div_tab2").hide();
             $("#div_tab3").hide();
				$("#div_tab4").hide();
        });

        $("#tab2").click(function(){
            $("#tab2").addClass("click");
            $("#tab1").removeClass("click");
            $("#tab3").removeClass("click");
				$("#tab4").removeClass("click");
             $("#div_tab2").show();
             $("#div_tab1").hide();
             $("#div_tab3").hide();
             $("#div_tab4").hide();
        });

        $("#tab3").click(function(){
            $("#tab3").addClass("click");
            $("#tab1").removeClass("click");
            $("#tab2").removeClass("click");
            $("#tab4").removeClass("click");
             $("#div_tab2").hide();
             $("#div_tab1").hide();
             $("#div_tab3").show();
             $("#div_tab4").hide();
        });
			$("#tab4").click(function(){
            $("#tab4").addClass("click");
            $("#tab1").removeClass("click");
            $("#tab2").removeClass("click");
            $("#tab3").removeClass("click");
             $("#div_tab2").hide();
             $("#div_tab1").hide();
             $("#div_tab4").show();
             $("#div_tab3").hide();
        });

    });
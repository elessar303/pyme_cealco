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
} // fin del function 

$(document).ready(function(){
    var suma = 0;

    $("#tab1").addClass("click");
    $("#div_tab1").show();
    $("#div_tab2").hide();
    $("#div_tab4").hide();
    $("#div_tab5").hide();

    $("input, select, textarea").focus(function(){
        $(this).addClass("sobreobjeto");
    }).blur(function(){
        $(this).removeClass("sobreobjeto");
    }).mouseover(function(){
        $(this).addClass("mouseOVER");
    }).mouseout(function(){
        $(this).removeClass("mouseOVER");
    });

    $("#tab1, #tab2, #tab4, #tab5").mouseover(function(){
        $(this).addClass("sobreboton");
    }).mouseout(function(){
        $(this).removeClass("sobreboton");
    });

    $("#tab1").click(function(){
        $("#tab1").addClass("click");
        $("#tab2").removeClass("click");
        $("#tab4").removeClass("click");
        $("#tab5").removeClass("click");
        $("#div_tab1").show();
        $("#div_tab2").hide();
        $("#div_tab4").hide();
        $("#div_tab5").hide();
    });

    $("#tab2").click(function(){
        $("#tab2").addClass("click");
        $("#tab1").removeClass("click");
        $("#tab4").removeClass("click");
        $("#tab5").removeClass("click");
        $("#div_tab2").show();
        $("#div_tab1").hide();
        $("#div_tab4").hide();
        $("#div_tab5").hide();
    });

    $("#tab4").click(function(){
        $("#tab1").removeClass("click");
        $("#tab2").removeClass("click");
        $("#tab4").addClass("click");
        $("#tab5").removeClass("click");
        $("#div_tab1").hide();
        $("#div_tab2").hide();
        $("#div_tab4").show();
        $("#div_tab5").hide();
    });

    $("#tab5").click(function(){
        $("#tab1").removeClass("click");
        $("#tab2").removeClass("click");
        $("#tab4").removeClass("click");
        $("#tab5").addClass("click");
        $("#div_tab1").hide();
        $("#div_tab2").hide();
        $("#div_tab4").hide();
        $("#div_tab5").show();
    });

});
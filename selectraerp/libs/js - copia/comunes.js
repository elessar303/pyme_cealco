function paginacion(url,valor,campos){
    window.location.href=url+"&pagina="+valor+"&"+campos
}

function MensajeEspera(mensaje){
    return '<img src="../../libs/imagenes/ajax-cargando.gif"> '+mensaje;
}

$(document).ready(function(){
    $("input").attr("autocomplete", "off");
    $("input, select").focus(function(){
        $(this).addClass("sobreobjeto");
    }).blur(function(){
        $(this).removeClass("sobreobjeto");
    }).mouseover(function(){
        $(this).addClass("mouseOVER");
    }).mouseout(function(){
        $(this).removeClass("mouseOVER");
    });
    $("#Lateral table tr").mouseover(function(){ // esta function crea un estilo sobre la celda por donde se pasa el cursor del mouse.
        $(this).addClass("menu-bg-hover");
    });
    $("#Lateral table tr").mouseout(function(){ // esta function remueve el estilo que fue creado para dar color a la celda del menu principal
        $(this).removeClass("menu-bg-hover");
    });

    $("#Lateral table tr").click(function(e){ // Esta function es para cuando el usuario da click en el menu principal
        if($(this).find("a").attr("href")!=undefined){
            window.location.href = $(this).find("a").attr("href");
        }
    });

    $(".seleccionLista").find("tbody tr").mouseover(function(){
        $(this).addClass("seleccion");
    }).mouseout(function(){
        $(this).removeClass("seleccion");
    });
    
    
});



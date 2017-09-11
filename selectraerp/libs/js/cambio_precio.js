$(document).ready(function(){

    $('#articulo_fin').change(function(){
        
        
        itemIni = $('#articulo_ini').val();
        itemFin =  $('#articulo_fin').val();
        //cargamos los debitos y creditos
        $.ajax({
            type: 'GET',
            data: 'opt=cambioPrecio&itemini='+itemIni+'&itemfin='+itemFin,
            url:  '../../libs/php/ajax/ajax.php',
            beforeSend: function(){
            },
            success: function(data){
            	$('#items').empty();
                $('#items').append(data);
            }
        });
    });
});
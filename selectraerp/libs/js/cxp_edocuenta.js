$(document).ready(function(){

    $('tr.edocuenta').mouseover(function(){
        if($(this).attr("bgcolor")!="#b6ceff"){
            $(this).attr("bgcolor","#fbf6f6");
        }
    }).mouseout(function(){
        if($(this).attr("bgcolor")!="#b6ceff"){
            $(this).attr("bgcolor","#ececec");
        }
    });



    $(".eliminarAsiento").live("click",function(e){
       
       if(confirm("¿Esta seguro(a) que desea eliminar este asiento?")){
          det_transaccion = $(this).parents("td").find("input[name='detalle_asiento']").val();

            $.ajax({
                    type: 'GET',
                    data: 'opt=eliminar_asientoCXP&cod='+det_transaccion,
                    url:  '../../libs/php/ajax/ajax.php',
                    beforeSend: function(){
			document.getElementById("loading").style.visibility = 'visible';
			},
                    success: function(data){
                       //objeto.after(data);
//                        if(data==1){
//                            
//                             
//                        }
// 			alert(data)
			document.getElementById("loading").style.visibility = 'hidden';
			location.reload()
                    }
             });

           //window.location.href = $("input[name='url_delete_asientos']").val();
          
       }
        
    });


    $('tr.edocuenta').click(function(){
       objeto = $(this);
       //Deseleccionamos cualquier fila cambiandole el color del tr
       objeto.parents("tbody").find(".edocuenta").attr("bgcolor","#ececec");
       //Seleccionamos la fila a la cual se dio click para conocer detalles
       $(this).attr("bgcolor","#b6ceff");
       //Removemos cualquier detalle que este cargado en la tabla de estado de cuenta
       objeto.parents("tbody").find(".edocuenta_detalle").remove();
       //Le colocamos la imagen que indica que puede hacer click para desplegar informacion
       objeto.parents("tbody").find(".boton_edocuenta").attr("src", "../../libs/imagenes/drop-add.gif");
       //Le coloca la imagenes a la fila tr que disparo el evento click.
       objeto.find(".boton_edocuenta").attr("src", "../../libs/imagenes/drop-add2.gif");       
       //Cargamos el codigo del proveedor y el codigo del estado de cuenta de X factura.
       cod_edocuenta = objeto.find("input[name='cod_edocuenta']").val();
       cod_proveedor =  objeto.find("input[name='cod_proveedor']").val();
       //cargamos los debitos y creditos
       $.ajax({
                    type: 'GET',
                    data: 'opt=det_edocuentacxp&codigo_proveedor='+cod_proveedor+'&cod_edocuenta='+cod_edocuenta,
                    url:  '../../libs/php/ajax/ajax.php',
                    beforeSend: function(){

                    },
                    success: function(data){
                       objeto.after(data);
                    }
            });
        });

});
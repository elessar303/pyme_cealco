<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

        <title></title>
        {literal}
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
            <style type="text/css">
                .contRespuesta{
                    position: absolute;
                    width: 400px;
                    height: 200px;
                    top:50%;
                    left: 50%;
                    margin-top: -100px;
                    margin-left: -200px;
                    background-color: #dfe8f6;
                    border: 1px solid #99bbe8;
                    -moz-border-radius: 5px;
                    -webkit-border-radius: 5px;

                }
                .wait{
                    background: url("../../imagenes/ajax-loader.gif") no-repeat ;
                    z-index: 10;
                    width: 54px;
                    height: 55px; 
                    margin-left: 25px;
                    margin-bottom: 10px;
                   
                }
                .contCarga{
                    position: absolute;
                    top: 45px;
                    left:140px;
                    font-size: 20px;            

                }
               
            </style>
            <script type="text/javascript">
                $(document).ready(function(){
                    //comentado para realizarlo por ajax 12/02/15
                    // window.location=window.confirm("Sincronizacion POS->Pyme")?"sincro_pos_pyme.php?generar=si&bandera=0":"?opt_menu=106";

                    //ajax para mandar el boton pos pyme y sincronizacion
                    $(".contCarga").hide();
                    // $(".botonS").click(function(){
                        var paramentros="generar=si&bandera=0";                      
                        $.ajax({
                            type: "POST",                            
                            url: "sincro_pos_pyme.php",
                            data: paramentros,                                             
                            beforeSend: function(datos){
                               $(".contCarga").show();
                               
                              // console.log("voy");
                               
                            },
                            success: function(datos){
                               $(".contCarga").hide();
                               // $(".contRespuesta").hide();
                               $(".contRespuesta").html(datos);
                               
                               //hacer con json 19-02-15
                                console.log("llego");
                                //validacion de la conexion
                               
                                if($("#conexion").val()==1){
                                  alert("Error de conexion");
                                  $('.contRespuesta').html('<div align="center"><img  width=200px;  src="../../../includes/imagenes/error1.png"/></div>');                          
                                }
                                //validacion de que ya se sincronizo el dia de hoy
                                if($("#sincroHoy").val()==1){
                                  alert("Ya se realizo la sincronizacion por hoy");
                                  document.location.href='index.php?opt_menu=106';
                                }
                                if($("#horaMenor").val()==1){
                                  alert("La sincronizacion no se puede hacer en un ahora menor a las 12:00 PM");
                                  document.location.href='index.php?opt_menu=106';
                                }
                                // $(".contRespuesta").html(datos);
                                var x = document.getElementById("funciona").value;
                                if(x=1){
                                alert("Se ha Sincronizado con exito");
                                $('.contRespuesta').html('<div align="center"><img  width=200px;  src="../../../includes/imagenes/check.png"/></div>');
                                //validacion de que no son mas de las 12
                                /* if($("#horaMenor").val()==1){
                                  alert("La sincronizacion no se puede hacer en un ahora menor a las 12:00 PM");
                                  document.location.href='index.php?opt_menu=106';
                                }*/
                                }
                                console.log($("#sincroHoy").val());
                               
                            },
                            error: function(datos,falla, otroobj){
                                $("#contCarga").html(' Error...');
                            }
                        });
                    // }) ; 
                       

                });
            </script>
        {/literal}
    </head>
    <body>
        <div class="contRespuesta">
            <div class="contCarga">
                <div class="wait"></div>
                <div>Procesando...</div>
            </div>
         <!--    <div class="botonS">sincronizar</div> -->
            
        </div>
    </body>
</html>

<!DOCTYPE html>
<html>
    <head>
    <style type="text/css">



</style>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" />
        {include file="snippets/inclusiones_reportes.tpl"}
        {literal}
        <script language="JavaScript" type="text/JavaScript">
        function check_tipo(id){
          
          if(id=="pyme_check")
            document.getElementById("pos_check").checked=false;
          else
            document.getElementById("pyme_check").checked=false;
        
        }

        function procesar_tipo_venta(){
          if(document.getElementById("pos_check").checked==true){
              document.getElementById('miVentana').style.display='none';
              var nested=document.getElementById('tipo_de_venta_mostrar');
              var pyme_div=document.getElementById('ambas_pyme');
              nested.parentNode.removeChild(nested);
              pyme_div.parentNode.removeChild(pyme_div);
              document.getElementById('ambas_pos').style.display='block';
            }
            else{

            document.getElementById('miVentana').style.display='none';
            var nested=document.getElementById('tipo_de_venta_mostrar');
            var pos_div=document.getElementById('ambas_pos');
            nested.parentNode.removeChild(nested);
            pos_div.parentNode.removeChild(pos_div);
            document.getElementById('ambas_pyme').style.display='block';

          }




        }





        $( document ).ready(function() {
          
          //verificar cajas con la cual van a trabajar
                  

                                 
                                  verificar_tipo_venta();
 
                                
                        
                          });


      function verificar_tipo_venta(){


                  


                  parametros={
                     "opt": "verificar_tipo_venta"
                     };

                        $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                               
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                                if(data==1){
                                 
                  var ventana = document.getElementById('miVentana');
                  ventana.style.marginTop = '100px';
                  ventana.style.left = ((document.body.clientWidth-350) / 2) +  'px';
                  ventana.style.display = 'block';
                  document.getElementById("caja").disabled=true;                
 
                                
                                }else{
                                    
                                    $('#boton').css("visibility", "visible");
                                    //$("#resultado").html(data);
                                }
                              }
                          });


      }




        function calcular(){
           fecha = $("#fecha").val();
           caja= $("#caja").val();

          if(fecha==""){
           alert("Campo Fecha no puede ser Vacio");
          } else{
           parametros={
            "fecha": fecha, "caja": caja, "opt": "calcular"
           };

            $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                               // $("#resultado").empty();
                                
                                },
                              error: function(){
                                   // alert("error petición ajax");
                                },
                              success: function(data){

                                if(data==-1){
                                  alert("No hay Datos Para La Fecha indicada");
                                  location.reload();
                                }else{
                                  if(data==-2){
                                    alert("Error,La Fecha Indicada No Concuerda Con el Ultimo Cierre De Caja Registrado!");
                                    history.back(-1); exit();
                                  }
                              // $('#boton').css("visibility", "visible");
                               var res = data.split(" ");
                               //$bruto=res['1'].toFixed(2);  
                                $("#monto_bruto").val(parseFloat((res['1'])).toFixed(2));
                                $("#monto_exento").val(parseFloat(res['2']).toFixed(2));
                                $("#base_imponible").val(parseFloat(res['0']).toFixed(2));
                                $("#iva").val(parseFloat(res['3']).toFixed(2));
                                 $("#monto_bruto_usuario").val(parseFloat((res['1'])).toFixed(2));
                                $("#monto_exento_usuario").val(parseFloat(res['2']).toFixed(2));
                                $("#base_imponible_usuario").val(parseFloat(res['0']).toFixed(2));
                                $("#iva_usuario").val(parseFloat(res['3']).toFixed(2));
                                //$("#resultado").html(data);
                                ///// verificamos su estado
                                }
                              }
                  });

        }


        }

        function enviar(){
                      cajas_operativas= $("#cajas_operativas").val();
                      if(cajas_operativas!=null){
                         parametros= {
                          "cajas_operativas": cajas_operativas,
                          "opt": "insertar_cajas_operativas"
                        }
                              $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                               
                                    
                                    alert("Registro Exitoso");
                                    $('#boton').css("visibility", "visible");
                                    $("#resultado").html("");
                                }
                                });
                              

                             return true;
                            // location.relooad();
                 
                               }
              tipoventa=$("#tipo_venta").val();

            if(tipoventa=="" || tipoventa==null){
             serial= $("#serial").val();
             z= $("#z").val();
             z_usuario= $("#z_usuario").val();
             ultima_factura= $("#ultima_factura").val();
             nro_facturas = $("#nro_facturas").val();
             ultima_nc = $("#ultima_nc").val();
             nro_ncs = $("#nro_ncs").val();
             fecha = $("#fecha").val();
             monto_bruto = $("#monto_bruto").val();
             monto_exento = $("#monto_exento").val();
             base_imponible = $("#base_imponible").val();
             iva = $("#iva").val();
             money = $("#money").val();
             monto_bruto_usuario = $("#monto_bruto_usuario").val();
             monto_exento_usuario = $("#monto_exento_usuario").val();
             base_imponible_usuario = $("#base_imponible_usuario").val();
             iva_usuario = $("#iva_usuario").val();
             caja= $("#caja").val();
             cierres= $("#cierres").val();
           }else{
            serial= $("#serial").val();
             z= $("#z").val();
             z_usuario= $("#z_usuario").val();
             ultima_factura= $("#ultima_factura").val();
             nro_facturas = $("#nro_facturas").val();
             ultima_nc = $("#ultima_nc").val();
             nro_ncs = $("#nro_ncs").val();
             fecha = $("#fecha").val();
             monto_bruto = $("#monto_bruto").val();
             monto_exento = $("#monto_exento").val();
             base_imponible = $("#base_imponible").val();
             iva = $("#iva").val();
             monto_bruto_usuario = $("#monto_bruto_usuario").val();
             monto_exento_usuario = $("#monto_exento_usuario").val();
             base_imponible_usuario = $("#base_imponible_usuario").val();
             iva_usuario = $("#iva_usuario").val();
             money = $("#money").val();
             tipo_venta=tipoventa;
             caja= $("#caja").val();
             cierres= $("#cierres").val();

           }
             if(serial=="" || z_usuario=="" || ultima_factura=="" || nro_facturas=="" ||  fecha=="" || monto_bruto_usuario=="" || monto_exento_usuario=="" || base_imponible_usuario=="" || iva_usuario==""){
              alert("Faltan Datos En El Formulario, Por Favor Corregir");
             }else{



             //alert(serial+z+ultima_factura+nro_facturas+ultima_nc+nro_ncs+fecha+monto_bruto+monto_exento+base_imponible+iva);
             if(tipoventa=="" || tipoventa==null){

             parametros= {
                "serial": serial,
                "z": z,
                "z_usuario" : z_usuario,
                 "ultima_factura": ultima_factura,
                 "nro_facturas": nro_facturas,
                 "ultima_nc": ultima_nc,
                 "nro_ncs": nro_ncs,
                 "fecha": fecha,
                 "monto_bruto": monto_bruto,
                 "monto_exento": monto_exento,
                 "base_imponible": base_imponible,
                 "iva": iva,
                 "money": money,
                 "monto_bruto_usuario": monto_bruto_usuario,
                 "monto_exento_usuario": monto_exento_usuario,
                 "base_imponible_usuario": base_imponible_usuario,
                 "iva_usuario": iva_usuario,
                 "opt": "insertar_libro_ventas",
                 "caja": caja,
                 "cierres": cierres

             };
           }else{

            parametros= {
                "serial": serial,
                "z": z,
                "z_usuario" : z_usuario,
                 "ultima_factura": ultima_factura,
                 "nro_facturas": nro_facturas,
                 "ultima_nc": ultima_nc,
                 "nro_ncs": nro_ncs,
                 "fecha": fecha,
                 "monto_bruto": monto_bruto,
                 "monto_exento": monto_exento,
                 "base_imponible": base_imponible,
                 "iva": iva,
                 "money": money,
                 "tipoventa":tipoventa,
                 "monto_bruto_usuario": monto_bruto_usuario,
                 "monto_exento_usuario": monto_exento_usuario,
                 "base_imponible_usuario": base_imponible_usuario,
                 "iva_usuario": iva_usuario,
                 "opt": "insertar_libro_ventas",
                 "caja": caja,
                 "cierres": cierres

             };

             }



             $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                               if(data==1){
                               $('#boton').css("visibility", "hidden");
                               $("#resultado").empty();
                               alert("Registro Exitoso de Libro");
                               location.reload();
                               //location.reload();
                            }else{
                              if(data==-4){
                                alert("Serial De La impresora No Esta Asociado A La Caja Seleccionada");
                                location.reload();
                                exit();
                              }else{
                              if(data==5){
                                  alert("Error, Se Ha Superado La Cantidad De Cajas Operativas");
                                  location.reload();
                                }else{
                                  alert("Error, Numero Z, Numero Nota Credito,  No pueden Estar Repetidos Para El Mismo Serial De Impresora");
                              $('#boton').css("visibility", "hidden");
                               $("#resultado").empty();
                             location.reload();
                            }
                          }
                                ///// verificamos su estado

                              }
                            }
                  });
                 }
            
                } 


        function comprobar_cierre_caja(caja){
          
          parametros={
            "caja": caja, "opt": "comprobar_cierre_caja"};
          

          $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                                if(data==-1){
                                    alert("Error, Esta Caja Permanece Abierta, cierrela antes de continuar.");
                                     $('#boton').css("visibility", "hidden");
                                      location.reload();
                                }else{
                                  cierres=1;
                                  if(data==1){
                                    cargar_formulario(caja,cierres);}else{
                                      cargar_formulario(caja,cierres+1); //en caso de varios cierres
                                    }

                                }

                               //$('#boton').css("visibility", "visible");
                               //$("#resultado").html(data);
                               ///// verificamos su estado

                              }
                  });



        }



        function cargar_formulario(caja, cierres){ 
                parametros ={
                "caja": caja, "cierres": cierres, "opt":'formulario_libro_ventas'
            };
               $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                               $('#boton').css("visibility", "visible");
                                $("#resultado").html(data);
                                ///// verificamos su estado

                              }
                  });
        }

        function verificar_caja(){
            caja=consulta = $("#caja").val();
            if(caja!=0){
            parametros ={
                "caja": caja, "opt":'verificar_caja'
            };
             $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                                if(data==-1){
                                    alert("Esta Caja No Tiene Serial de Impresora Asociado, Redirigirse Al modulo De Agregar Impresora en Configuracion");
                                     $('#boton').css("visibility", "hidden");
                                      location.reload();


                                }else{
                                    //comprobar_cierre_caja(caja);
                                    cargar_formulario(caja,2); // se envia varios cierres con la nueva dorma de agregar cajeros

                                }
                                
                                ///// verificamos su estado

                              }
                  });      

    }
        }

        // funciones del pyme
        function verificar_caja1(tipo){

           caja=consulta = $("#caja").val();
            if(caja!=0){
            parametros ={
                "caja": caja, "opt":'verificar_caja'
            };

             $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {

                                $("#resultado").empty();
                                $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                                if(data==-1){
                                    alert("Esta Caja No Tiene Serial de Impresora Asociado, Redirigirse Al modulo De Agregar Impresora en Configuracion");
                                     $('#boton').css("visibility", "hidden");
                                      location.reload();


                                }else{

                                    
                                    comprobar_cierre_caja_pyme(caja, tipo);
                                     //cargar_formulario1(caja,0,tipo);

                                }
                                
                                ///// verificamos su estado

                              }
                  });      


        }
      }



        function comprobar_cierre_caja_pyme(caja, tipo){
          
                                    cargar_formulario1(caja,0,tipo);
          

                              

                               //$('#boton').css("visibility", "visible");
                               //$("#resultado").html(data);
                               ///// verificamos su estado

                              



        }


        function cargar_formulario1(caja, cierres, tipo){
          parametros ={
                "caja": caja, "cierres": cierres, "opt":'formulario_libro_ventas_pyme', "tipo": tipo
            };
               $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                               $('#boton').css("visibility", "visible");
                                $("#resultado").html(data);
                                ///// verificamos su estado

                              }
                  });




        }


        function calcular_pyme(){
           fecha = $("#fecha").val();
           caja= $("#caja").val();
           serial=$("#serial").val();

          if(fecha==""){
           alert("Campo Fecha no puede ser Vacio");
          } else{
           parametros={
            "fecha": fecha, "caja": caja, "serial": serial, "opt": "calcular_pyme"
           };

            $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                               // $("#resultado").empty();
                                
                                },
                              error: function(){
                                   // alert("error petición ajax");
                                },
                              success: function(data){

                                if(data==-1){
                                  alert("No hay Datos Para La Fecha indicada");
                                  location.reload();
                                }else{
                                  if(data==-2){
                                    alert("Error,La Fecha Indicada No Concuerda Con el Ultimo Cierre De Caja Registrado!");
                                    //history.back(-1); exit();
                                  }
                              // $('#boton').css("visibility", "visible");
                               var res = data.split(" ");
                               //$bruto=res['1'].toFixed(2);  
                                $("#monto_bruto").val(parseFloat((res['1'])).toFixed(2));
                                $("#monto_exento").val(parseFloat(res['2']).toFixed(2));
                                $("#base_imponible").val(parseFloat(res['0']).toFixed(2));
                                $("#iva").val(parseFloat(res['3']).toFixed(2));
                                 $("#monto_bruto_usuario").val(parseFloat((res['1'])).toFixed(2));
                                $("#monto_exento_usuario").val(parseFloat(res['2']).toFixed(2));
                                $("#base_imponible_usuario").val(parseFloat(res['0']).toFixed(2));
                                $("#iva_usuario").val(parseFloat(res['3']).toFixed(2));
                                //$("#resultado").html(data);
                                ///// verificamos su estado
                                }
                              }
                  });

        }


        }






      


        </script>
        {/literal}        
        <title>Cierre de Ventas</title>
        
    </head>

    <body>
      <div id="div_principal">
        <form name="formulario" id="formulario">
        <div id="datosGral" class="x-hide-display">
        {include file = "snippets/regresar.tpl"}
        <table style="width:50%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista" align="center">
                <thead>
                    <tr>
                        <th class="tb-head" style="text-align:center;">Seleccionar La Caja A Generar Libro De Ventas</th>
                    </tr>
                    
                    <tr>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                       <div id="tipo_de_venta_mostrar">
                        {if $ubicacion_venta eq "1"}
                        <select name="caja" id="caja" style="width:200px;" class="form-text" onchange="verificar_caja();">
                        <option value="0">Seleccione Caja...</option>
                        {html_options values=$option_values_cajas output=$option_output_cajas}
                        </select>
                        {else}
                         <select name="caja" id="caja" style="width:200px;" class="form-text" onchange="verificar_caja1({$ubicacion_venta});">
                        <option value="0">Seleccione Caja...</option>
                        {html_options values=$option_values_cajas output=$option_output_cajas}
                        </select>
                        {/if}
                        </div>
                    </td>
                   </tr>
                   

                   <tr>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                       <div id="ambas_pos"  style="display: none;"> 
                        <select name="caja" id="caja" style="width:200px;" class="form-text" onchange="verificar_caja();">
                        <option value="0">Seleccione Caja...</option>
                        {html_options values=$option_values_cajas output=$option_output_cajas}
                        </select>
                        </div>
                        <div id="ambas_pyme" style="display: none;">
                         <select name="caja" id="caja" style="width:200px;" class="form-text" onchange="verificar_caja1({$ubicacion_venta});">
                        <option value="0">Seleccione Caja...</option>
                        {html_options values=$option_values_cajas_pyme output=$option_output_cajas_pyme}
                        </select>
                      </div>
                        
                    </td>
                   </tr>


                </thead>
                
        </table>
        </form>
        </div>
        <br>
        <form method='POST'  name='form1' target="ventanaForm" >
        <div id="resultado" width='50%' align="center">
         
        </div>
        <div id="boton" style="visibility:hidden;"><tbody>
                    <tr class="tb-head" align="center">
                            <td align='center'>
                                <input type="button" id="aceptar" name="aceptar" value="Crear" onclick="enviar()" />
                                
                            </td>
                    </tr>
                </tbody>
            </div>
        </form>  
    </body>
    <div id='miVentana' style='position: fixed; width: 350px; height: 190px; top: 0; left: 0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; border: #333333 3px solid; background-color: #FAFAFA; color: #000000; display:none;  -moz-opacity:0.8; -webkit-opacity:0.8; -o-opacity:0.9; -ms-opacity:0.9; background-color: #808080; overflow: auto; width: 500px; background: #fff; padding: 30px; -moz-border-radius: 7px; border-radius: 7px; -webkit-box-shadow: 0 3px 20px rgba(0,0,0,1); -moz-box-shadow: 0 3px 20px rgba(0,0,0,1); box-shadow: 0 3px 20px rgba(0,0,0,1); background: -moz-linear-gradient(#fff, #ccc); background: -webkit-gradient(linear, right bottom, right top, color-stop(1, rgb(255,255,255)), color-stop(0.57, rgb(230,230,230)));  
'>
<h1>Seleccionar Ubicacion De Venta Del Libro De Venta</h1>
<table border="4"  align="center" width="200px">
  <tr>
    <td align="center" >
      <b>PYME</b>
    </td>
    <td >
      <input type="checkbox" name="pyme_check" id="pyme_check" onclick="check_tipo(this.id)" style="margin-top: 2px; margin-left:2px;"/>
    </td>
  </tr>
<tr>
  <td align="center">
    <b>POS</b>
  </td>
  <td>
    <input type="checkbox" name="pos_check" id="pos_check" onclick="check_tipo(this.id)" style="margin-top: 2px; margin-left:2px;" />
  </td>
</tr>
</tr>
</table>


    <p><input align="center" type="submit" value="Procesar" style="  margin-right: 220px;" onclick="procesar_tipo_venta()" /></p>


</div>
</div>
</body>
</html>    

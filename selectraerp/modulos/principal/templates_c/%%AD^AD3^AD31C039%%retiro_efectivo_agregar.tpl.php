<?php /* Smarty version 2.6.21, created on 2016-10-31 14:57:22
         compiled from retiro_efectivo_agregar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'retiro_efectivo_agregar.tpl', 305, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" />
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
        $( document ).ready(function() {
          
          //verificar cajas con la cual van a trabajar
           parametros={
           "opt": "abrir_cajeros"
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

                                }else{
                                    verificar_tipo_venta();
                                    //$(\'#boton\').css("visibility", "visible");
                                    //$("#resultado").html(data);
                                }

                              }
                  });






          });
            


            function procesar_tipo_venta(){
          if(document.getElementById("pos_check").checked==true){
              document.getElementById(\'miVentana\').style.display=\'none\';
              var nested=document.getElementById(\'tipo_de_venta_mostrar\');
              var pyme_div=document.getElementById(\'ambas_pyme\');
              nested.parentNode.removeChild(nested);
              pyme_div.parentNode.removeChild(pyme_div);
              document.getElementById(\'ambas_pos\').style.display=\'block\';
              document.getElementById("cajero").disabled=false;  
            }
            else{

            document.getElementById(\'miVentana\').style.display=\'none\';
            var nested=document.getElementById(\'tipo_de_venta_mostrar\');
            var pos_div=document.getElementById(\'ambas_pos\');
            nested.parentNode.removeChild(nested);
            pos_div.parentNode.removeChild(pos_div);
            document.getElementById(\'ambas_pyme\').style.display=\'block\';
            document.getElementById("cajero").disabled=false;  

          }




        }




             function check_tipo(id){
          
          if(id=="pyme_check")
            document.getElementById("pos_check").checked=false;
          else
            document.getElementById("pyme_check").checked=false;
        
        }

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
                                 
                  var ventana = document.getElementById(\'miVentana\');
                  ventana.style.marginTop = \'100px\';
                  ventana.style.left = ((document.body.clientWidth-350) / 2) +  \'px\';
                  ventana.style.display = \'block\';
                  document.getElementById("cajero").disabled=true;                
 
                                
                                }else{
                                    
                                    $(\'#boton\').css("visibility", "visible");
                                    //$("#resultado").html(data);
                                }
                              }
                          });


      }

        
        
        


            
        function enviar(){
             
                 billete_100=$("#billete_100").val();
                  billete_50=$("#billete_50").val();
                  billete_20=$("#billete_20").val();
                  billete_10=$("#billete_10").val();
                  billete_5=$("#billete_5").val();
                  billete_2=$("#billete_2").val();
                  total_monedas=$("#total_monedas").val();
                  cajero=$("#cajero").val();
             
             if(billete_100=="" || billete_50=="" || billete_20=="" || billete_10=="" || billete_5=="" || billete_2=="" || total_monedas=="" ){
              alert("Faltan Datos En El Formulario, Por Favor Corregir");
              }
              else
              {
             
                              tipo_venta_pyme= $("#tipo_venta_pyme").val();
                               parametros={
                                  "billete_100":  billete_100,
                                  "billete_50":   billete_50,
                                   "billete_20":  billete_20,
                                   "billete_10":  billete_10,
                                   "billete_5":   billete_5,
                                   "billete_2": billete_2,
                                   "cajero": cajero,
                                   "tipo_venta_pyme": tipo_venta_pyme,
                                   "total_monedas": total_monedas,
                                                                  
                                   "opt": "guardar_arqueo_retiro"

                                };
                              guardar_arqueo_retiro(parametros);
                              $("#resultado").empty();
                            
               }//fin del else
            
                }

                 function guardar_arqueo_retiro(parametros){
            $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                                 data=data.split("_");
                                if(data[0]==1){
                               alert("Retiro Exitoso");
                               location.reload();
                               //location.reload();
                              /*var url = \'../../reportes/imprimir_arqueo_cajero.php\';
                              var form = $(\'<form action="\' + url + \'" method="post">\' +
                                \'<input type="text" name="id" value="\' + data[1] + \'" />\' +
                                \'<input type="text" name="original" value="1" />\' +
                                \'</form>\');
                              $(\'body\').append(form);  // This line is not necessary
                              $(form).submit();*/

                                }else{
                                 if(data==2){
                                  alert("Error Al Insertar Registro (Consulte Al Administrador).");
                                  location.reload();
                                }
                               
                                }


                              }
                  });


        } 


          function cargar_total(){
            
            caja1= (!isNaN($("#billete_100").val()) ? parseFloat($("#billete_100").val()*100) : 0);
            caja2= (!isNaN($("#billete_50").val()) ? parseFloat($("#billete_50").val()*50) : 0);
            caja3= (!isNaN($("#billete_20").val()) ? parseFloat($("#billete_20").val()*20) : 0);
            caja4= (!isNaN($("#billete_10").val()) ? parseFloat($("#billete_10").val()*10) : 0);
            caja5= (!isNaN($("#billete_5").val()) ? parseFloat($("#billete_5").val()*5) : 0);
            caja6= (!isNaN($("#billete_2").val()) ? parseFloat($("#billete_2").val()*2) : 0);
            caja7= (!isNaN($("#total_monedas").val()) ? parseFloat($("#total_monedas").val()) : 0);

            if(caja1 < 0 || caja2 < 0 || caja3 < 0 || caja4 < 0 || caja5 < 0 || caja6 < 0 || caja7 < 0){
              alert("Error, No se Aceptan cantidades Negativas");
              location.reload();

            }
          caja8=parseFloat(caja1+caja2+caja3+caja4+caja5+caja6+caja7);

          document.getElementById("total_efectivo").value = caja8;

          }


        function cargar_formulario(){ 
                cajero= $("#cajero").val();
                parametros ={
                "cajero": cajero, "opt":\'formulario_arqueo_cajero_retiro\'
            };
               $.ajax({
                              type: "POST",
                              url: "../../libs/php/ajax/ajax.php",
                              data: parametros,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
                                },
                              error: function(){
                                    alert("error petición ajax");
                                },
                              success: function(data){
                               $(\'#boton\').css("visibility", "visible");
                                $("#resultado").html(data);
                                ///// verificamos su estado

                              }
                  });
        }

        

        


                //arquero cajero por pyme *********************************+++
             
             

        </script>
        '; ?>
        
        <title>Cierre de Ventas</title>
        
    </head>

    <body>
        <form name="formulario" id="formulario">
        <div id="datosGral" class="x-hide-display">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <table style="width:50%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista" align="center">
                <thead>
                    <tr>
                        <th class="tb-head" style="text-align:center;">Seleccionar El Cajero A Cerrar (Arqueo)</th>
                    </tr>
                    <tr>
                      <td align="center"> 
                        <div id="tipo_de_venta_mostrar">
                      <table>

                    <?php if ($this->_tpl_vars['ubicacion_venta'] == '1'): ?>
                    <tr>

                    <?php if ($this->_tpl_vars['option_values_cajas'] != ""): ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cajero" id="cajero" style="width:200px;" class="form-text" onchange="cargar_formulario();">
                        <option value="-1">Seleccione Cajero...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas'],'output' => $this->_tpl_vars['option_output_cajas']), $this);?>

                        </select>
                    </td>
                    <?php else: ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                      <input type="hidden" id="cajero" />
                        <p align="center" > No Existen Cajeros con Ventas el Dia de Hoy </p>
                    </td>
                    <?php endif; ?>
                    </tr>
                    <?php else: ?>
                    <tr>
                    <?php if ($this->_tpl_vars['option_values_cajas_pyme'] != ""): ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cajero" id="cajero" style="width:200px;" class="form-text" onchange="cargar_formulario();">
                        <option value="-1">Seleccione Cajero...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas_pyme'],'output' => $this->_tpl_vars['option_output_cajas_pyme']), $this);?>

                        </select>
                        <input type="hidden" name="tipo_venta_pyme" id="tipo_venta_pyme" value="0" />
                    </td>
                    <?php else: ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                      <input type="hidden" id="cajero" />
                        <p align="center" > No Existen Cajeros con Ventas el Dia de Hoy </p>
                    </td>
                    <?php endif; ?>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </td>
            </tr>

                    <!-- Otra Opcion -->
                    <tr><td align="center"><div id="ambas_pos"  style="display: none;"> <table><tr>
                    <?php if ($this->_tpl_vars['option_values_cajas'] != ""): ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cajero" id="cajero" style="width:200px;" class="form-text" onchange="cargar_formulario();">
                        <option value="-1">Seleccione Cajero...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas'],'output' => $this->_tpl_vars['option_output_cajas']), $this);?>

                        </select>
                        
                    </td>
                    <?php else: ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                      <input type="hidden" id="cajero" />
                        <p align="center" > No Existen Cajeros con Ventas el Dia de Hoy </p>
                    </td>
                    <?php endif; ?>
                    </tr>
                  </table></div> </td></tr>

                  <tr><td align="center"><div id="ambas_pyme"  style="display: none;"> <table><tr>
                    <?php if ($this->_tpl_vars['option_values_cajas_pyme'] != ""): ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cajero" id="cajero" style="width:200px;" class="form-text" onchange="cargar_formulario();">
                        <option value="-1">Seleccione Cajero...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas_pyme'],'output' => $this->_tpl_vars['option_output_cajas_pyme']), $this);?>

                        </select>
                        <input type="hidden" name="tipo_venta_pyme" id="tipo_venta_pyme" value="0" />
                    </td>
                    <?php else: ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                      <input type="hidden" id="cajero" />
                        <p align="center" > No Existen Cajeros con Ventas el Dia de Hoy </p>
                    </td>
                    <?php endif; ?>
                    </tr>
                  </table></div></td></tr>




                </thead>
                
        </table>
        </form>
        </div>
        <br>
        <form method='POST' action='agregar_libro_fin.php' name='form1' target="ventanaForm" onsubmit="window.open('', 'ventanaForm', '')">
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
<h1>Seleccionar Ubicacion De Venta </h1>
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














</html>    
<!DOCTYPE html>
<html>
    <head>
            
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script type="text/javascript" src="../../libs/js/event_almacen_entrada.js"></script>
        <script type="text/javascript" src="../../libs/js/eventos_formAlmacen.js"></script>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        
        
        <title>Almacen</title>
      
        
        {literal}
   
<script language="JavaScript">
    
   $(document).ready(function(){

        function CambioPrecios1(idSelect,id_producto){ 
            
            $("#contMensaje").html("");
            $("#contPrecio").empty();
            var paramentros="opt=CambioPrecios1&id_producto="+id_producto;
            $.ajax({
                type: "POST",
                url: "../../libs/php/ajax/ajax.php",
                data: paramentros,
                beforeSend: function(datos){
                    $("#"+idSelect).html('Cargando...');
                },
                success: function(datos){
                    $("#"+idSelect).html(datos);
                },
                error: function(datos,falla, otroobj){
                    $("#"+idSelect).html(' Error...');
                }
            });
        }   

        function CambioPrecios3(cantidad,producto,region){
                param="";
                $(".precioV").each(function() {
                  id1= $(this).attr('id');
                  precio=$(this).val();
                       param=param+"&precioh"+id1+"="+precio+"&"+id1+"="+id1;   
                    
                });
              // paramentros= $("#formulario").serialize();
              paramentros="opt=CambioPrecios3&cantidad="+cantidad+"&producto="+producto+"&region="+region;  
              paramentros=paramentros+param;                    
            $.ajax({
                type: "POST",
                url: "../../libs/php/ajax/ajax.php",
                data: paramentros,
                beforeSend: function(datos){
                    $("#contMensaje").html('Cargando...');
                },
                success: function(datos){
                    // $("#contMensaje").html("Precios cambiado exitosamente");
                    // $("#contMensaje").html(datos);
                    alert("Precios cambiados exitosamente");
                    CambioPrecios1("contProducto",producto);
                },
                error: function(datos,falla, otroobj){
                    $("#contMensaje").html(' Error...');
                }
            });
        }   

         function CambioPrecios2(producto,region,tipoPrecio,idSelect){
               $("#contMensaje").html("");

            var paramentros="opt=CambioPrecios2&producto="+producto+"&region="+region+"&tipoPrecio="+tipoPrecio;

            $.ajax({
                type: "POST",
                url: "../../libs/php/ajax/ajax.php",
                data: paramentros,
                beforeSend: function(datos){
                    $("#"+idSelect).html('Cargando...');
                },
                success: function(datos){
                    $("#"+idSelect).html(datos);                   
                    //funcion que se crea despues de los botones ajax para que se a tomado por el dom
                    $("#agregarP").click(function(event) {
                          bandera=0;
                        $(".precioV").each(function(index, el) {
                            if ($(this).val()=="") {
                                bandera=1;     
                            }
                        });
                    if(bandera==0){
                      cantidad = $("#cantPrecio").val();
                      producto = $("#producto").val();
                      region = $("#region").val();                 

                      CambioPrecios3(cantidad,producto,region);
                    }else{
                        alert("Debe completar los campos para ingresar los precios");
                    }
                    });
                  


                },
                error: function(datos,falla, otroobj){
                    $("#"+idSelect).html(' Error...');
                }
            });
        }     

        $("#producto").change(function() {
           id_producto= $("#producto").val();
          
           CambioPrecios1("contProducto",id_producto);
        });   
        $("#region").change(function() {
          $("#contPrecio").empty();
        });   
        
        $("#buscarP").click(function() {
            producto=$("#producto").val();
            region=$("#region").val();
            precio=0;          
            $(".tipo_p").each( function(index, val) {
                if($(this).is(':checked')){
                     precio=1;
                }              
            });

            if(producto==0 || region==0 || precio==0){
               alert("debe seleccionar un producto, una region y almenos un tipo de precio...");           
            }else{
                producto=$("#producto").val();
                region=$("#region").val();
                tipoPrecio=[];
                 $(".tipo_p").each( function(index, val) {
                if($(this).is(':checked')){
                     tipoPrecio.push($(this).val());
                }              
            });              
                CambioPrecios2(producto,region,tipoPrecio,"contPrecio");

            }
        });


$("#buscarCodigo").click(function(){ 
    cargarProductoCodigo_cambio();
});

  
  function cargarProductoCodigo_cambio() {    
        codigoBarra=$("#codigoBarra").val();     
        $.ajax({
            type: 'POST',
            data: 'opt=cargaProductoCodigo&codigoBarra='+codigoBarra,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
               
            },
            success: function(data) {                      
                this.vcampos = eval(data);             
                  if( this.vcampos[0].band==-1){
                    alert("El codigo de barra no es correcto!");
                  }else{
                     var idItem=this.vcampos[0].id_item;
                     $("#producto").val(idItem);
                     $("#producto").change();
                  }
               
            }
        });
    }
    
  
  
  
  //$("#producto").change(function() {
  
 
   
  
  
});


 function cambio_precio(){ 
           id_producto= $("#codigo_barras_c").val();
           
   var option_menu=document.getElementById("option_menu").value;
   option_menu= option_menu.substring(0, option_menu.length-1);
   var option_section=document.getElementById("option_section").value;
   option_section= option_section.substring(0, option_section.length-1);
   
   var por_nombre=document.getElementById("nombre_producto_c").value;
   var porId=document.getElementById("codigo_barras_c").value;
   //alert('?opt_menu='+option_menu+'&opt_seccion='+option_section+'&opt_subseccion=edit&cod='+porId);
        //window.open("mipagina.php?varible=valor&variable2= valor");
   
        
   //window.open("'?opt_menu=option_menu&amp;opt_seccion=option_section&amp;opt_subseccion=edit&amp;cod=porId'");
   
   window.location.href='?opt_menu='+option_menu+'&opt_seccion='+option_section+'&opt_subseccion=edit&cod='+porId+'&nombre='+por_nombre;
           
          
           
        };
</script>
{/literal}
    </head>
    <body>
       <!-- <form name="formulario" id="formulario" method="post" action="">-->
            <div id="datosGral">
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="cant_fechas" value="2"/>
                <table style="width:100%; background-color: white;" border=1>
                    <thead>
                        <tr>
                            <th colspan="6" class="tb-head" style="text-align:center;">CAMBIO DE PRECIO </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td class="label"> <p>
               <label><b>Codigo de barra</b></label><br/></td><td>
               <input type="text" style="width: 290px; height: 20px;" name="codigoBarra" id="codigoBarra">
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>
               
            </td>
                            
                        </tr>
                        <tr>
                            <td class="label">Producto</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <!--label for="fecha_desde">Desde</label-->
                                <select style="width: 290px;;" name="producto" id="producto" class="form-text"> <option value="0">Seleccione...</option>
                                    {html_options values=$option_values_item output=$option_output_item selected=0}
                                </select>
                            </td>
                            
                        </tr>
                        
                        
                        
                         

                    </tbody>
                </table>
                <table style="width:100%; background-color: white; border: 5px; alignment-adjust: auto;" border="10">
                        
                     
                    <tr>
                        <td style="width:400px;" ><div  id="contProducto">
                            
                            </div></td>
                            <!--<td style="cursor:pointer; width:30px; text-align:center; margin-top: 20px;">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.DAY}'" title="Editar" src="../../../includes/imagenes/edit.gif">
                                    </td>-->
                            <td  style="cursor:pointer; width:30px; text-align:center; margin-top: 100px;">
                                        <img  style="margin-top: 5px; width: 15px;" class="editar" onclick="cambio_precio()" title="Editar" src="../../../includes/imagenes/edit.gif">
                                    </td>
                            <!--<td  style="cursor:pointer; width:30px; text-align:center; margin-top: 100px;">
                                        <img  style="margin-top: 5px; width: 15px;" class="editar" onclick="javascript: window.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=edit&amp;cod={$campos.DAY}'" title="Editar" src="../../../includes/imagenes/edit.gif">
                                    </td>-->
                    <input type="hidden" name="option_menu" id="option_menu" value ={$smarty.get.opt_menu}/>
                    <input type="hidden" name="option_section" id="option_section" value ={$smarty.get.opt_seccion}/>
                        
                    </tr> 
                
                   
                </table>
            </div>
                        
       <!-- </form>-->
                                
              
            
    </body>
</html>
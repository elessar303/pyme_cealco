<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {include file="snippets/header_form.tpl"}
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        {literal}
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function(){
               $("input[name='aceptar']").button().click(function(){
                    var vacio=0;
                    $('.validar').each(function(index, value){
                           id= ($(this).attr('id'));
                           id= id.substring(6);
                           valor=$("#serial"+id).val();                         
                           if (valor=="") {
                                vacio=vacio + 1;                            
                           };                       
                    });
                    if(vacio > 0){
                           Ext.Msg.alert("Alerta","Debe Ingresar todos los seriales");
                           return false;
                    } 
                     var error1=0;
                     $('.oculto').each(function(index, value){
                           id1= ($(this).attr('id'));                           
                           valor1=$(this).val();                         
                           if (valor1=="1") {
                                error1=error1 + 1;                            
                           };                       
                    });
                     if(error1 > 0){
                           Ext.Msg.alert("Alerta","existen seriales incorrectos");
                           return false;
                    }  


                });
                        function validarSerialRep(serial,id,idItem){
                              var cantidad=0;
                              $('.validar').each(function(){
                                  valor1= $(this).val();   
                                  id_campo1=$(this).attr('id');                                           
                                  num= id_campo1.substring(6);
                                  idItem1=$("#idItem"+num).val();
                                
                                  if(id!=id_campo1){  
                                    if(serial==valor1 && idItem1==idItem ){
                                      cantidad= cantidad + 1;
                                    }
                                  }

                              });
                             
                                  if(cantidad!=0){
                                    return 1;
                                  }else{
                                    return 0;
                                  }
                        };
                // agregado el 31/01/14 para validar los seriales en despacho
                        function ValidarSerial(idSelect,serial,num,idItem){
                            var paramentros="opt=ValidarSerial&serial="+serial+"&num="+num+"&idItem="+idItem;
                            $.ajax({
                                type: "POST",
                                url: "../../libs/php/ajax/ajax.php",
                                data: paramentros,
                                beforeSend: function(datos){
                                   
                                },
                                success: function(datos){
                                    $("#"+idSelect).html(datos);
                                },
                                error: function(datos,falla, otroobj){
                                    $("#"+idSelect).html(' Error...');
                                }
                            });
                        };
                   // fin de la funcion 

                     // agregado el 31/01/14 para validar los seriales en despacho
                        function BuscarSerial(idSelect,idItem,num){
                            var paramentros="opt=BuscarSerial&idItem="+idItem+"&num="+num;
                            $.ajax({
                                type: "POST",
                                url: "../../libs/php/ajax/ajax.php",
                                data: paramentros,
                                beforeSend: function(datos){
                                   
                                },
                                success: function(datos){
                                    $("#"+idSelect).html(datos);
                                },
                                error: function(datos,falla, otroobj){
                                    $("#"+idSelect).html(' Error...');
                                }
                            });
                        };
                   // fin de la funcion 
                   // llamada de la funcion para validar seriales
                      $('.validar').each(function(index, value){
                            $(this).change(function() {
                                valor= $(this).val();   
                                id_campo=$(this).attr('id');                                              
                                num= id_campo.substring(6);
                                idItem=$("#idItem"+num).val();                                          
                                select="validar"+num; 
                                 val=validarSerialRep(valor,id_campo,idItem);                               
                                if(val==0){                                 
                                   ValidarSerial(select,valor,num, idItem);
                                 } else{
                                   Ext.Msg.alert("Alerta","existen seriales repetidos del mismo articulo");
                                     $(this).val("");
                                 }                                                    
                               
                            });      
                      });

                      $('.busSerial').each(function(index, value){
                            $(this).click(function() {
                                id_campo= $(this).attr('id');
                                num= id_campo.substring(6);
                                $("#numSerial").val("");
                                idItem=$("#idItem"+num).val();                           
                                idSelect="serialesBusc";                             
                                $('#serialesBusc').empty();  
                                $('#fondo').show();
                                $('#modal').show();
                                $("#numSerial").val(num);
                                BuscarSerial(idSelect,idItem,num);                              
                            });  
                      });

                      $("#fondo").click(function() {
                          $('#fondo').hide();
                          $('#modal').hide();
                          $('#serialesBusc').empty();

                      });
                      $("#serialesBusc").change(function() {
                          num= $("#numSerial").val();
                          serial= $('#serialesBusc').val();
                          $("#serial"+num).val(serial);
                          $("#serial"+num).change();
                          $('#fondo').hide();
                          $('#modal').hide();
                          $('#serialesBusc').empty();
                      });

                  
                      
            });
            //]]>
            </script>
            <style>
     .fondo { 
         background: #000000;
         display: none;
         position: absolute;
         left: 0; top: 0;
         width: 100%;
         height: 100%;
         z-index: 1001;
         opacity: .75; /* opacidad para Firefox */
       
      }
      .modal{ 
        display: none;
        position: absolute;
        overflow: auto;
        z-index:1002;
        left: 50%;  top: 50%; /* la posición de la ventana modal */
        width: 280px;
        height: 150px;
        margin: -75px 0 0 -140px;
        background: #99bbe8;
      
      }
     #serialesBusc{
        width: 201px;
        margin: 58px 0 0 34px;
     } 
        </style>
        {/literal}
        
    </head>
    <body>
        <!-- ventana modal -->
        <!-- realizar el fondo trasnparente y negro y el div  -->
        <div id="fondo" class="fondo"></div>
        <div id="modal" class="modal">
            <div id="contSer">
                <input type="hidden" id="numSerial" value="">
                <select class="form-text" name="" id="serialesBusc">
                
                </select>
            </div>
        </div>
       
        <!-- fin de la ventana modal -->
        <form id="form-{$name_form}" name="formulario" action="" method="post">
            <div id="datosGral">
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
                <input type="hidden" name="id_despD" value="{$smarty.get.cod}"/>
                <table style="width:100%; background-color: white;">
                  
                    <tbody>
                        <tr class="tb-head">                       
                            <td width="40%" colspan="3"><b>Nombre Producto </b></td>
                            <td width="40%" colspan="3"><b>Serial</b></td>
                            <td width="40%" colspan="2"></td>                               
                        </tr>                        
                        {foreach from=$datos_despacho key=i item=campos}
                           
                        
                        <tr>
                           
                            <td width="40%" colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                               <b>{$campos.item_descripcion}</b>
                            </td>
                       
                            
                            <td width="40%" colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <input  {if $campos.serial ne "" } readonly value="{$campos.serial}"{/if}  type="text" name="serial{$i}" size="60" id="serial{$i}" class="form-text validar" />
                            </td>
                            <td width="10%"><div id="validar{$i}" style="width: 20px;height: 20px;"></div></td>
                            
                            <td width="10%">{ if $campos.estatus eq 0}<div class="busSerial" id="buscar{$i}" style="width: 20px;height: 20px;cursor: pointer;">
                              <img src="../../../includes/imagenes/search.gif" alt="Buscar serial">
                            </div>{/if}</td>
                          
                             <input name="id{$i}" type="hidden" value="{$campos.id}">
                             <input id="idItem{$i}" name="id_item{$i}" type="hidden" value="{$campos.id_item}">
                        </tr>
                        {/foreach}
                        <input name="cant" type="hidden" value="{$i}">
                    </tbody>
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
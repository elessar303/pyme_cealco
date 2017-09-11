<?php /* Smarty version 2.6.21, created on 2016-12-19 19:30:36
         compiled from detalleDesp_nuevo.tpl */ ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        <?php echo '
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function(){
               $("input[name=\'aceptar\']").button().click(function(){
                    var vacio=0;
                    $(\'.validar\').each(function(index, value){
                           id= ($(this).attr(\'id\'));
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
                     $(\'.oculto\').each(function(index, value){
                           id1= ($(this).attr(\'id\'));                           
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
                              $(\'.validar\').each(function(){
                                  valor1= $(this).val();   
                                  id_campo1=$(this).attr(\'id\');                                           
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
                                    $("#"+idSelect).html(\' Error...\');
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
                                    $("#"+idSelect).html(\' Error...\');
                                }
                            });
                        };
                   // fin de la funcion 
                   // llamada de la funcion para validar seriales
                      $(\'.validar\').each(function(index, value){
                            $(this).change(function() {
                                valor= $(this).val();   
                                id_campo=$(this).attr(\'id\');                                              
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

                      $(\'.busSerial\').each(function(index, value){
                            $(this).click(function() {
                                id_campo= $(this).attr(\'id\');
                                num= id_campo.substring(6);
                                $("#numSerial").val("");
                                idItem=$("#idItem"+num).val();                           
                                idSelect="serialesBusc";                             
                                $(\'#serialesBusc\').empty();  
                                $(\'#fondo\').show();
                                $(\'#modal\').show();
                                $("#numSerial").val(num);
                                BuscarSerial(idSelect,idItem,num);                              
                            });  
                      });

                      $("#fondo").click(function() {
                          $(\'#fondo\').hide();
                          $(\'#modal\').hide();
                          $(\'#serialesBusc\').empty();

                      });
                      $("#serialesBusc").change(function() {
                          num= $("#numSerial").val();
                          serial= $(\'#serialesBusc\').val();
                          $("#serial"+num).val(serial);
                          $("#serial"+num).change();
                          $(\'#fondo\').hide();
                          $(\'#modal\').hide();
                          $(\'#serialesBusc\').empty();
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
        '; ?>

        
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
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="" method="post">
            <div id="datosGral">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
                <input type="hidden" name="id_despD" value="<?php echo $_GET['cod']; ?>
"/>
                <table style="width:100%; background-color: white;">
                  
                    <tbody>
                        <tr class="tb-head">                       
                            <td width="40%" colspan="3"><b>Nombre Producto </b></td>
                            <td width="40%" colspan="3"><b>Serial</b></td>
                            <td width="40%" colspan="2"></td>                               
                        </tr>                        
                        <?php $_from = $this->_tpl_vars['datos_despacho']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                           
                        
                        <tr>
                           
                            <td width="40%" colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                               <b><?php echo $this->_tpl_vars['campos']['item_descripcion']; ?>
</b>
                            </td>
                       
                            
                            <td width="40%" colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <input  <?php if ($this->_tpl_vars['campos']['serial'] != ""): ?> readonly value="<?php echo $this->_tpl_vars['campos']['serial']; ?>
"<?php endif; ?>  type="text" name="serial<?php echo $this->_tpl_vars['i']; ?>
" size="60" id="serial<?php echo $this->_tpl_vars['i']; ?>
" class="form-text validar" />
                            </td>
                            <td width="10%"><div id="validar<?php echo $this->_tpl_vars['i']; ?>
" style="width: 20px;height: 20px;"></div></td>
                            
                            <td width="10%"><?php if ($this->_tpl_vars['campos']['estatus'] == 0): ?><div class="busSerial" id="buscar<?php echo $this->_tpl_vars['i']; ?>
" style="width: 20px;height: 20px;cursor: pointer;">
                              <img src="../../../includes/imagenes/search.gif" alt="Buscar serial">
                            </div><?php endif; ?></td>
                          
                             <input name="id<?php echo $this->_tpl_vars['i']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['campos']['id']; ?>
">
                             <input id="idItem<?php echo $this->_tpl_vars['i']; ?>
" name="id_item<?php echo $this->_tpl_vars['i']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['campos']['id_item']; ?>
">
                        </tr>
                        <?php endforeach; endif; unset($_from); ?>
                        <input name="cant" type="hidden" value="<?php echo $this->_tpl_vars['i']; ?>
">
                    </tbody>
                </table>
                <table style="width:100%">
                    <tbody>
                        <tr class="tb-tit">
                            <td>
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Lucas Sosa" />
        <title></title>
        {include file="snippets/inclusiones_reportes.tpl"}
        {literal}
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                $("#cliente").autocomplete({
                    source: "../../libs/php/ajax/autocomplete_cliente.php",
                    minLength: 3, // how many character when typing to display auto complete
                    select: function(e, ui) {//define select handler
                        $("#cod_cliente").val(ui.item.id);
                    }
                });
                $("#producto").autocomplete({
                    source: "../../libs/php/ajax/autocomplete_producto.php",
                    minLength: 3,
                    select: function(e, ui) {//define select handler
                        $("#cod_producto").val(ui.item.id);
                    }
                });
                $("#aceptar").click(function(){
                   archivo=$("#archivo1").val();
                   alert(archivo);return false;
                   if(archivo){

                   }
                });
               
               
            });
            //]]>
            </script>
        {/literal}
    </head>
 
    <body>
   <br>
     <form enctype="multipart/form-data" action="subida_central_csv.php" method="POST">
         <table border=0>      
    <!-- MAX_FILE_SIZE debe preceder el campo de entrada de archivo -->
    
    <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
    <tr><td>Seleccione El Archivo a Enviar: </td></tr>
    <tr><td><input name="archivo_ventas" id="archivo_ventas" type="file" /> </td></tr>
    <tr><td>
            <input style="float: left" type="submit" value="Enviar Archivo" /></td></tr>
    
    </table>
</form>
        
       
        
        
    </body>
</html>

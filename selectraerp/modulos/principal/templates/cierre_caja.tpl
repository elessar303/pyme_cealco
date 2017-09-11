<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" />
        {include file="snippets/inclusiones_reportes.tpl"}
        {literal}
        <script language="JavaScript" type="text/JavaScript">
        function cerrarcaja() {
        var consulta;     
        consulta = $("#caja").val();             
                        $.ajax({
                              type: "POST",
                              url: "cierre_caja_pos.php",
                              data: "b="+consulta,
                              dataType: "html",
                              asynchronous: false,
                              beforeSend: function() {
                                $("#resultado").empty();
                                $("#resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
                                },
                              error: function(){
                                    alert("error petici�n ajax");
                                },
                              success: function(data){
                                $("#resultado").html(data);
                                ///// verificamos su estado

                              }
                  });

        }



        function enviar(){ 
                if (confirm('¿Cerrar Caja?')){ 
                window.open("", "ventanaForm", "");
                document.form1.submit() 
                window.close();
                location.reload();
                } 
        } 
        </script>
        {/literal}        
        <title>Cierre de Ventas</title>
        
    </head>

    <body>
        <form name="formulario" id="formulario">
        <div id="datosGral" class="x-hide-display">
        {include file = "snippets/regresar_boton.tpl"}
        <table style="width:50%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista" align="center">
                <thead>
                    <tr>
                        <th class="tb-head" style="text-align:center;">Seleccionar la Caja a Cerrar</th>
                    </tr>
                    <tr>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="caja" id="caja" style="width:200px;" class="form-text">
                        <option value="0">...</option>                               
                        {html_options values=$option_values_cajas output=$option_output_cajas}
                        </select>
                    </td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tb-head" align="center">
                            <td align='center'>
                                <input type="button" id="aceptar" name="aceptar" value="Cerrar Caja" onclick="cerrarcaja()" />
                                <input type="submit" id="cancelar" name="cancelar" value="Limpiar"/>
                            </td>
                    </tr>
                </tbody>
        </table>
        </form>
        </div>
        <br>
        <form method='POST' action='cierre_caja_fin.php' name='form1' target="ventanaForm" onsubmit="window.open('', 'ventanaForm', '')">
        <div id="resultado" width='50%' align="center"></div>
        </form>  
    </body>
</html>    

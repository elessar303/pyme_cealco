<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>        
        {literal}
         <link type="text/css" rel="stylesheet" href="../../../includes/css/sincronizacion.css"/>
      
         

            <script type="text/javascript">
                $(document).ready(function(){
                    // window.location=window.confirm("Sincronizacion Pyme Central, Desea continuar?")?"act_central_ccs.php?generar=si":"?opt_menu=106";
                    $(".contCarga").hide();
                });
            </script>
        {/literal}
    </head>
    <body>
        <div class="contRespuesta">
            <div class="contsinc">
                <h1>Sincronizacion central</h1>
                <div class="contBoton">                    
                    <div style="margin-top: 20px">Ultima sincronzacion: {$fechaDebido} realizada el dia: {$fechaCierre} </div>
                  
              
                        <div style="margin-top: 30px">
                            <span>Desde</span>
                            <select>
                                <option value="{$fechaDebido}">{$fechaDebido}</option>
                            </select>
                             <span style="margin-left: 20px">Hasta</span>
                            <select>
                                {html_options values=$option_values_fechaSincro output=$option_values_fechaSincro}
                            </select>
                        </div>
              <!--      
                        <!-- cierre del if -->
                </div>
            </div>
            <div class="contCarga">
                <div class="wait"></div>
                <div>Procesando...</div>
            </div>
         <!--    <div class="botonS">sincronizar</div> -->
            
        </div>
    </body>
</html>
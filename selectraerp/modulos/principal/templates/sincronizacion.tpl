<!DOCTYPE html>
<html>
    <head>
        <title>.: M&oacute;dulo de Facturaci&oacute;n e Inventario :.</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!--Aqui estaba una inclusion del header.tpl, {include file="header.tpl"}-->
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/login.otro.css" />
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos.css" />
        <link rel="shortcut icon" href="../../../includes/imagenes/selectra.ico" />
        {literal}
            <style type="text/css">
                .oculto { display: none; }
                .visible { display: inline; }
                #cont1 {
                    float:left;
                    margin-left:5px;
                    margin-right:5px;
                    margin-top: 20px;
                    color:#666666;
                    width:400px;
                    text-align: center;
                }
            </style>
            <script>
                 $(document).ready(function(){
                    $("#sincro").click(function(event) {                       
                          window.location=window.confirm("Sincronizacion POS->Pyme")?"../principal/sincro_pos_pyme.php?generar=si&bandera=1":"?opt_menu=106";
                    });
                  
                });

            </script>
        {/literal}
    </head>
    <body>
        <div id="login">
            <div id="logo">
                <img src="../../../includes/imagenes/siscolp.png" width="350"/>
            </div>
            <div id="cont1">
               {if $usuarioB==1 }

                   <h2>
                        <div>Para desbloquer el sistema, se debe sincronizar los datos al servidor en el siguiente link 
                        </div>  
                   </h2> 
                    <div id="sincro" style="margin-top:10px;cursor:pointer">
                       <h3 style="color: green">Sincronizacion PYME->POS</h3>
                    </div> 
               {else}
                  <h2>
                    <div>para desbloquera debe ingresar con permisos de administrador</div>  
                  </h2>
               {/if}
                <div style="margin-top: 20px;">
                    <a style="font-size:15px" href="../principal/index.php?logout=off">Volver al inicio</a>
                </div>

            </div>
            
            
        </div>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <title>.: SISCOL :.</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/login.otro.css" />
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos.css" />
        <link rel="shortcut icon" href="../../../includes/imagenes/logo.ico" />
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
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
                         
                   window.location=window.confirm("Aperturar Planta")?"../principal/apertura_tienda.php?generar=si&bandera=1":"?opt_menu=106";
                     
                    });
                  
                });

                 function nobackbutton(){
                 window.location.hash="no-back-button";
                window.location.hash="Again-No-back-button" //chrome
               window.onhashchange=function(){window.location.hash="no-back-button";}
            }

            </script>
          
        {/literal}
    </head>
    <body onload="nobackbutton();">
        <div id="login">
            <div id="logo">
                <img src="../../../includes/imagenes/siscolp.png" width="350"/>
            </div>
            <div id="cont1">
               {if $usuarioB==1 }
                   <h2>
                        <div>Para desbloquear el sistema, debe <u>Aperturar Planta</u> en el Siguiente LINK:
                        </div>  
                   </h2> 
                    <div id="sincro" style="margin-top:10px;cursor:pointer">
                       <h3 style="color: green">Apertura de Planta</h3>
                    </div> 
               {else}
                  <h2>
                    <div>Para desbloquera debe ingresar con permisos de administrador</div>  
                  </h2>
               {/if}
                <div style="margin-top: 20px;">
                    <a style="font-size:15px" href="../principal/index.php?logout=off">Volver al inicio</a>
                </div>
            </div>
        </div>
    </body>
</html>
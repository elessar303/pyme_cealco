<!DOCTYPE html>
<html>
    <head>
        <title>.: SISCOL :.</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!--Aqui estaba una inclusion del header.tpl, {include file="header.tpl"}-->
        <link type="text/css" rel="stylesheet" href="../../../includes/css/login.otro.css" />
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos.css" />
        <link rel="shortcut icon" href="../../../includes/imagenes/logo.ico" />
        {literal}
            <style type="text/css">
                .oculto { display: none; }
                .visible { display: inline; }
            </style>
        {/literal}
    </head>
    <body>
        <div id="login">
            <div id="logo">
                <img src="../../../includes/imagenes/siscolp.png" width="350"/>
            </div>
            <div id="cont">
                <form method="post">
                    <ul>
                        <li></li>
                        <li>
                            <label>Iniciar sesi&oacute;n</label>
                        </li>
                            <!-- <label for="username"><b>Nombre de usuario</b></label> -->
                            <input type="text" name="txtUsuario" id="username" placeholder="Nombre de usuario" class="form-text" />
                            {literal}
                                <script type="text/javascript">//<![CDATA[
                                        document.getElementById("username").focus();
                                //]]>
                                </script>
                            {/literal}
                        </li>
                        <li>
                            <br/>
                            <!-- <label for="password"><b>Contrase&ntilde;a</b></label> -->
                            <input type="password" name="txtContrasena" id="password" placeholder="Contrase&ntilde;a" class="form-text" />
                        </li>
                        <li>
                            <input type="submit" name="submit" id="submit" value="Ingresar" />
                        </li>
                        <!--li>
                            <a style="color: blue" href="../../../sistemas.php" ><img src="../../../includes/imagenes/back.gif"/>&nbsp;Ir al Men&uacute; de Sistemas</a>
                        </li-->
                    </ul>
                    <!--div>
                        <input type="submit" name="submit" id="submit" value="Ingresar" />
                    </div-->
                </form>
                <div style="text-align: center;">
                <a style="color: blue;" href="../../../sistemas.php"><img src="../../../includes/imagenes/back.gif"/>&nbsp;Ir al Men&uacute; de Sistemas</a>
            </div>
            </div>
            
            <div style="text-align: center">
                <span id="mensaje_acceso" class="oculto" style="color: red; white-space: nowrap;">Acceso denegado: Nombre de usuario o contrase&ntilde;a incorrecto.</span>
                {if $acceso eq 0}
                    <br/>
                    <script type="text/javascript">//<![CDATA[
                        document.getElementById("mensaje_acceso").className = "visible";
                    //]]>
                    </script>
                {/if}
            </div>
            <div style="text-align: center">
                <span id="mensaje_acceso2" class="oculto" style="color: red; white-space: nowrap;">Acceso denegado: Usuario Inactivo. Consultar con el Administrador</span>
                {if $acceso2 eq 0}
                    <br/>
                    <script type="text/javascript">//<![CDATA[
                        document.getElementById("mensaje_acceso2").className = "visible";
                    //]]>
                    </script>
                {/if}
            </div>
        </div>
    </body>
</html>
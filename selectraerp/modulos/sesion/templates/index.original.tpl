<html>
    <head>
        {include file="header.tpl"}
    </head>
    <body>
        <form method="post">
            <center>
                <div id="form_login">
                    <div id="campos">
                        <label for="usuario"><b>Usuario</b></label>
                        <input type="text" name="txtUsuario" autocomplete="off" maxlength="40" style="width:60%" value="" />
                        <br>
                        <label for="Clave"><b>Clave</b> </label>
                        <input type="password" name="txtContrasena" autocomplete="off" maxlength="40" style="width:60%" value="" />
                    </div>
                    <div id="botones">
                        <div class="button">
                            <img src="../../libs/imagenes/acep.png" height="21" align="absmiddle">
                            <input type="submit" name="submit"  value="Aceptar" />
                        </div>
                        <div class="button">
                            <img src="../../libs/imagenes/salir.png" height="21" align="absmiddle" >
                            <input type="reset" name="reset" value="Cancelar" />
                            </br>
                        </div>
                    </div>
                </div>
                <br>
                <table align="center">
                    <tr>
                        <td align="center" ><a target="_self" href="../../../sistemas.php" ><img src="../../imagenes/back.png" border="0" width="16" height="16" align="absmiddle"/>&nbsp;Ir a menu de Sistemas</a></td>
                    </tr>
                </table>
            </center>
        </form>
    </body>
</html>
